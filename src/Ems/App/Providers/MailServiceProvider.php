<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;
use FormObject\Form;
use Cmsable\Controller\SiteTree\SiteTreeController;
use Illuminate\Routing\Router;
use Ems\App\Http\Route\TreeResourceRegistrar;
use Ems\App\Search\Criteria;
use Ems\App\Repositories\MailConfigRepository;
use Cmsable\Widgets\Contracts\Registry as WidgetRegistry;
use Ems\App\Http\Forms\SystemMailConfigForm;
use Ems\Core\GenericResourceProvider;
use Cmsable\Cms\Action\Registry as Actions;
use Cmsable\PageType\PageType;

class MailServiceProvider extends ServiceProvider
{

    protected $packageNS = 'ems';

    protected $packagePath = '';

    protected $routeGroup = [
        'namespace' =>  'Ems\App\Http\Controllers'
    ];

    public function boot()
    {
        $this->registerSystemUserIdFinder();
        $this->registerAdminMailPage();
        $this->registerMailConfigController();
    }

    public function register()
    {

        $this->registerAddressExtractor();
        $this->registerMailContentProvider();
        $this->registerMailBodyRenderer();
        $this->registerMessageComposer();
        $this->registerMailConfigRepository();
        $this->registerMailTemplateRepository();
        $this->registerMailTransport();
        $this->registerMailer();
        $this->registerMailConfigForm();
        $this->registerMailPreviews();
        $this->registerAbstractEmailingPreviewer();
        $this->registerDefaultMailPreviewer();

        $this->app->afterResolving('cmsable.actions', function ($actions) {
            $this->bootActions($actions);
        });

    }

    protected function resourcePath($dir='')
    {
        $resourcePath = $this->packagePath('resources');

        if ($dir) {
            return "$resourcePath/$dir";
        }

        return $resourcePath;
    }

    protected function packagePath($dir='')
    {
        if (!$this->packagePath) {
            $this->packagePath = realpath(__DIR__.'/../../../../');
        }
        if ($dir) {
            return $this->packagePath . "/$dir";
        }
        return $this->packagePath;
    }

    protected function registerSystemUserIdFinder()
    {

        $this->app->afterResolving('Ems\App\Repositories\MailConfigRepository', function($repo){
            $repo->provideSystemUserId(function(){
                $user = $this->app['auth.driver']->getProvider()
                             ->retrieveByCredentials(['email'=>'system@local.com']);
                return $user->id;
            });
        });

    }

    protected function registerAddressExtractor()
    {
        $this->app->singleton('Ems\Contracts\Mail\AddressExtractor', function($app) {
            return $app->make('Ems\Mail\GuessingAddressExtractor');
        });
    }

    protected function registerMailConfigRepository()
    {
        $this->app->singleton('Ems\App\Contracts\Mail\SystemMailConfigRepository', function(){
            return $this->app['Ems\App\Repositories\MailConfigRepository'];
        });

        $this->app->singleton('Ems\Contracts\Mail\MailConfigProvider', function($app){

            $chain = $app['Ems\Mail\MailConfigProviderChain'];

            $manualProvider = $app->make('Ems\Mail\ManualConfigProvider');

            $manualProvider->mapResourceNameToTemplate(true);

            $chain->add($manualProvider);

            $chain->add($app->make('Ems\App\Contracts\Mail\SystemMailConfigRepository'));

            return $chain;

        });
    }

    protected function registerMailBodyRenderer()
    {
        $this->app->singleton('Ems\Contracts\Mail\BodyRenderer', function($app) {
            return $app->make('Ems\Mail\Laravel\ViewBodyRenderer');
        });
    }

    protected function registerMailContentProvider()
    {
        $this->app->singleton('Ems\Contracts\Mail\MessageContentProvider', function() {
            return $this->app->make('Ems\Mail\Laravel\EloquentMessageContentProvider', [new \Ems\App\MailContent]);
        });
    }

    protected function registerMessageComposer()
    {
        $this->app->singleton('Ems\Contracts\Mail\MessageComposer', function($app) {

            $addressExtractor = $app->make('Ems\Mail\GuessingAddressExtractor');

            $systemFrom = $app['config']->get('mail.from.address');

            $addressExtractor->mapToEmail('system', $systemFrom);

            $composer = $app->make('Ems\Mail\MessageComposer', [
                $addressExtractor,
                $app->make('Ems\Contracts\Mail\BodyRenderer'),
                $app->make('Ems\Contracts\Mail\MessageContentProvider')
            ]);

            $composer->processDataWith($app->make('Ems\Mail\MessageTextParser'));

            return $composer;

        });

        $this->app->afterResolving('Ems\App\Cms\VariableProviderQueue', function($queue, $app) {
            // TODO: This is fired twice with the same queue
            if ($queue->containsClass('Ems\App\Services\Mail\ComposerVariableProvider')) {
                return;
            }
            $queue->add($app->make('Ems\App\Services\Mail\ComposerVariableProvider'));
        });
    }

    protected function registerMailTemplateRepository()
    {
        $this->app->singleton('mail-templates', function($app) {
            $texts = $app->make('Ems\Contracts\Core\TextProvider')->forDomain('emails.templates');
            $repo = $app->make('Ems\Core\GenericResourceProvider', [$texts]);
            $repo->add('emails.empty');
            return $repo;
        });
    }

    protected function registerMailTransport()
    {
        $this->app->singleton('Ems\Contracts\Mail\Transport', function($app) {
            return $app->make('Ems\Mail\Swift\Transport', [$app->make('mailer')->getSwiftMailer()]);
        });
    }

    protected function registerMailer()
    {
        $this->app->singleton('Ems\Contracts\Mail\Mailer', function($app) {
            $mailer = $app->make('Ems\Mail\Mailer');
            if ($overwriteTo = env('MAIL_OVERWRITE_TO')) {
                $mailer->alwaysSendTo($overwriteTo);
            }
            return $mailer;
        });
    }

    protected function registerMailConfigForm()
    {
        $this->app->bind('Ems\App\Http\Forms\SystemMailConfigForm', function($app) {
            return new SystemMailConfigForm(
                $app['mail-templates'],
                $app['Ems\Contracts\Mail\MessageContentProvider'],
                $app['Ems\App\Contracts\Cms\VariableProvider']);
        });
    }

    protected function registerMailPreviews()
    {
        $this->app->bind('Ems\App\Contracts\Mail\EmailingPreviewer', function ($app) {
            return $app->make('Ems\App\Services\Mail\EmailingPreviewerChain');
        });

    }

    protected function registerAbstractEmailingPreviewer()
    {
        $this->app->afterResolving('Ems\App\Services\Mail\AbstractEmailingPreviewer', function ($previewer, $app) {

            $modelClass = $app->make('cmsable.resource-distributor')->modelClass('users');
            $criteria = $app->make('versatile.criteria-builder')->criteria($modelClass, []);
            $keys = $app->make('versatile.model-presenter')->keys($modelClass);

            $search = $app->make('versatile.search-factory')->search($criteria)->withKey($keys);

            $previewer->setUserSearch($search);
            $previewer->setComposer($app->make('Ems\Contracts\Mail\MessageComposer'));
            $previewer->setMailer($app->make('Ems\Contracts\Mail\Mailer'));

        });
    }

    protected function registerDefaultMailPreviewer()
    {
        $this->app->resolving('Ems\App\Services\Mail\EmailingPreviewerChain', function ($chain, $app) {
            $chain->add($app->make('Ems\App\Services\Mail\GenericEmailPreviewer'));
        });
    }

    protected function registerMailConfigController()
    {

//         $this->app->afterResolving('cmsable.resource-mapper', function($mapper) {
//             $mapper->mapFormClass('session', 'Ems\App\Http\Forms\LoginForm');
//             $mapper->mapValidatorClass('mail-configurations', 'Ems\App\Validators\LoginValidator');
//         });

        $this->app['events']->listen('cmsable.pageTypeLoadRequested', function($pageTypes){

            $pageType = PageType::create('cmsable.mailconfig-editor.system')
                                  ->setCategory('mails')
                                  ->setRouteScope('admin')
                                  ->setTargetPath('mail-configurations')
                                  ->setControllerCreatorClass('Ems\App\Cms\ControllerCreators\MailConfigControllerCreator');

            $pageTypes->add($pageType);


        });

        $this->app->router->group($this->routeGroup, function($router) {


            $router->get('mail-configurations/{mail_configurations}/preview-mailing',[
                'as'    => 'mail-configurations.preview-mailing',
                'uses'  => 'MailConfigController@previewMailing'
            ]);

            $router->post('mail-configurations/{mail_configurations}/preview-mailing/{preview_mailing}/send',[
                'as'    => 'mail-configurations.preview-mailing.send',
                'uses'  => 'MailConfigController@sendPreviewMailing'
            ]);

            $router->resource('mail-configurations','MailConfigController');

//             $router->get('mail-configurations/{mail-configurations}/preview-mailing',[
//                 'as'    => 'mail-configurations.preview-mailing',
//                 'uses'  => 'MailConfigController@previewMailing'
//             ]);


        });

        $this->app['events']->listen('sitetree.mails-added', function(&$mails){

            $mailsConfigs = [
                'id'                => 'mail-configs.system',
                'page_type'         => 'cmsable.mailconfig-editor.system',
                'url_segment'       => 'mail-configurations',
                'icon'              => 'fa-gears',
                'title'             => $this->app['translator']->get('ems::admintree.mail-config-system.title'),
                'menu_title'        => $this->app['translator']->get('ems::admintree.mail-config-system.menu_title'),
                'show_in_menu'      => true,
                'show_in_aside_menu'=> false,
                'show_in_search'    => true,
                'show_when_authorized' => true,
                'redirect_type'     => 'none',
                'redirect_target'   => 0,
                'content'           => $this->app['translator']->get('ems::admintree.mail-config-system.content'),
                'view_permission'   => 'cms.access',
                'edit_permission'   => 'superuser'
            ];

            $mails['children'][] = $mailsConfigs;

        });

    }

    protected function registerAdminMailPage()
    {

        $this->app['events']->listen('sitetree.filled', function(&$adminTreeArray){

            $security = [
                'id'                => 'mails',
                'page_type'         => 'cmsable.redirector',
                'url_segment'       => 'mails',
                'icon'              => 'fa-envelope',
                'title'             => $this->app['translator']->get('ems::admintree.mails.title'),
                'menu_title'        => $this->app['translator']->get('ems::admintree.mails.menu_title'),
                'show_in_menu'      => true,
                'show_in_aside_menu'=> false,
                'show_in_search'    => true,
                'show_when_authorized' => true,
                'redirect_type'     => 'internal',
                'redirect_target'   => 'firstchild',
                'content'           => $this->app['translator']->get('ems::admintree.mails.content'),
                'view_permission'   => 'cms.access',
                'edit_permission'   => 'superuser'
            ];

            $this->app['events']->fire('sitetree.mails-added',[&$security]);

            $adminTreeArray['children'][] = $security;

        });
    }

    protected function bootActions(Actions $actions)
    {

        $texts = $this->app->make('Ems\Contracts\Core\TextProvider');

        $actions->onItem(['Ems\App\MailConfig','App\MailConfig'], function($group, $user, $resource) use ($texts) {

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('mail-configurations.preview-mailing', [$resource->getId()]);
            $preview = $group->newAction();
            $preview->setName('preview');
            $preview->setIcon('fa-search');
            $preview->showIn('mail-configurations','save', 'info');
            $preview->setTitle($texts->get('ems::forms.actions.preview.title'));
            $preview->setOnClick("openModalIframe('$url');");
            $group->push($preview);

        });

    }

}
