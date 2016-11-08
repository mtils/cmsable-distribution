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
use Cmsable\Foundation\Http\CleanedRequest;
use Ems\Core\Collections\NestedArray;

class PackageServiceProvider extends ServiceProvider
{

    protected $packageNS = 'ems';

    protected $packagePath = '';

    protected $publicDir = '';

    protected $assetNamespace = 'cmsable';

    public function boot()
    {

//         $this->registerModelPresenter();

        Router::macro('treeResource', function($name, $controller, $options=[]) {
            $registrar = new TreeResourceRegistrar($this);
            $registrar->register($name, $controller, $options);
        });

        $this->loadTranslationsFrom(
            $this->resourcePath('lang'),
            $this->packageNS
        );

        $this->registerAdminViewPath();

        $this->registerFormTranslations();

        $this->registerFileDBExtension();

        $this->registerMultiRenderer();

        $this->registerPermissionRepository();

        $this->registerVersatileSearchFactory();

        $this->registerCmsAccessFilter();

        $this->publishes([
            $this->resourcePath('assets/public') => public_path($this->publicDir)
        ], 'public');

    }

    public function register()
    {

        $this->registerDefaultTextParser();
        $this->registerAutoBreadCrumbProvider();
        $this->registerCustomValidatorRules();
        $this->registerValidatorNamespace();
        $this->registerFormNamespace();
        $this->registerVersatileDateFormat();
        $this->registerVersatileCriteria();
        $this->registerAutocompleteMorpher();
        $this->registerCsvMorpher();
        $this->registerSystemAccessChecker();
        $this->registerGenericResourceProvider();
        $this->registerTextProvider();
        $this->registerVariablesProvider();

        $this->app->afterResolving('Cmsable\Widgets\Contracts\Registry', function($reg, $app){
            $this->registerWidgets($reg);
        });

        $this->app->afterResolving('Ems\Contracts\Assets\Registry', function ($registry, $app) {
            $this->registerAssetPaths($registry);
        });

        $this->app->resolving('Ems\Contracts\Assets\BuildConfigRepository', function ($repo, $app) {
            $this->registerAssetBuilds($repo);
        });

        $this->app->afterResolving('Ems\Contracts\Core\InputCorrector', function($corrector) {
            $this->registerInputCorrectors($corrector);
            $corrector->setChain(['dotted', 'nested']);
        });

        $this->app->afterResolving('Ems\Contracts\Core\InputCaster', function($caster) {
            $this->registerInputCasters($caster);
            $caster->setChain(
                ['no_leading_underscore', 'no_actions', 'no_confirmations', 'xtype_caster']
            );
        });

        $this->publicDir = 'vendor/'.$this->assetNamespace;

        $this->registerAssetGroupPrefix();

        $this->app['events']->listen('router.matched', function()
        {
            $this->app->resolving(function(CleanedRequest $request, $app)
            {
                $request->setRedirector($app['Illuminate\Routing\Redirector']);
            });
        });

    }

    protected function registerDefaultTextParser()
    {
        $this->app->singleton('Ems\Contracts\Core\TextParser', function ($app) {

            $queue = $app->make('Ems\Core\TextParserQueue');

            $queue->add($app->make('Ems\Core\VariablesTextParser'));

            return $queue;

        });
    }
    
    protected function registerCustomValidatorRules()
    {
        $this->app->afterResolving('Illuminate\Contracts\Validation\Factory', function ($factory){
            $factory->extend('local_date', 'Ems\App\Services\Validation\LokalizedRules@validateLocalDate');
        });
    }

    protected function registerValidatorNamespace()
    {
        $this->app->afterResolving('Cmsable\Resource\ValidatorClassFinder', function($finder){
            $finder->appendNamespace('Ems\App\Validators');
        });
    }

    protected function registerFormNamespace()
    {
        $this->app->afterResolving('Cmsable\Resource\FormClassFinder', function($finder){
            $finder->appendNamespace('Ems\App\Http\Forms');
        });
    }

    protected function registerVersatileDateFormat()
    {
        $this->app->afterResolving('versatile.type-introspector', function($introspector){
            $dateFormat = $this->app['translator']->get('ems::base.datetime-format');
            $introspector->setDefaultDateFormat($dateFormat);
        });
    }

    protected function registerVersatileCriteria()
    {
        $this->app->afterResolving('versatile.criteria-builder', function($builder){
            $builder->setCriteriaPrototype(new Criteria);
        });
    }

    protected function registerAutocompleteMorpher()
    {
        $this->app['events']->listen('cmsable::responding.application/json', function($response, $morpher) {

            $autocomplete = $this->app->make('Ems\App\View\AutocompleteContentMorpher');
            return $autocomplete->morphIfQueried($response, $morpher);

        });
    }

    protected function registerCsvMorpher()
    {
        $this->app['events']->listen('cmsable::responding.text/csv', function($response, $morpher) {

            $csvContentMorpher = $this->app->make('Ems\App\View\CsvContentMorpher');
            return $csvContentMorpher->morphIfQueried($response, $morpher);

        });
    }

    protected function registerVersatileSearchFactory()
    {
        $this->app->singleton('versatile.search-factory', function($app) {
            return $app->make('Ems\App\Search\ResourceSearchFactory');
        });

        $this->app->afterResolving('versatile.search-factory', function($factory) {

            $searchFactory = $this->app->make('Ems\App\Search\CustomBuilderFactory');
            $factory->forModelClass('Illuminate\Database\Eloquent\Model', [$searchFactory, 'createSearch']);

        });
    }

    protected function registerCmsAccessFilter()
    {

        $this->app['cmsable.cms']->whenScope('*', function ($scope, $route, $request, $page)
        {

            if ($page && $page->view_permission != 'page.public-view' && ! $this->app['auth']->allowed($page->view_permission)) {

                if (!$this->app['auth']->user()->isGuest()) {
                    $this->app['Cmsable\View\Contracts\Notifier']->error($this->app['translator']->get('ems::messages.pages/current.insufficient-permissions'));
                    return redirect()->back();
                }

                $intendedUrl = $this->app['url']->full();
                if (!str_contains($intendedUrl, '_debugbar')) {
                    $this->app['session']->set('url.intended', $intendedUrl);
                }

                $this->app['Cmsable\View\Contracts\Notifier']->error($this->app['translator']->get('ems::messages.pages/current.not-authenticated'));

                return $this->app['redirect']->route('session.create')->withErrors([
                    $this->app['translator']->get('user.noaccess')
                ]);
            }
        });

    }

    protected function registerModelPresenter()
    {

        $this->app->singleton('versatile.model-presenter', function($app) {
            return $app->make('Ems\App\View\ResourceModelPresenter');
        });

    }

    protected function registerAdminViewPath()
    {

        $this->app['cmsable.cms']->whenScope('admin', function ()
        {

            $this->app['auth']->forceActual(true);

            $adminThemePath = $this->resourcePath('views/admin');

            $formRenderer = Form::getRenderer();
            $formRenderer->addPath("$adminThemePath/forms");

            $this->app['view']->getFinder()->prependLocation($adminThemePath);

            if(!$appViewPath = $this->app['config']->get('view.paths')) {
                return;
            }

            $appAdminViewPath = $appViewPath[0] . '/admin';

            if (!is_dir($appAdminViewPath)) {
                return;
            }

            $this->app['view']->getFinder()->prependLocation($appAdminViewPath);

        });

        $this->app->afterResolving('FormObject\Factory', function($factory, $app){
            $factory->appendNamespace('Ems\App\Http\Forms\Fields');
        });

    }

    protected function registerFormTranslations()
    {
        Form::provideAdditionalNamer(function($chain){

            $class = 'FormObject\Support\Laravel\Naming\TranslationNamer';

            foreach ($chain->getByClass($class) as $namer) {
                $namer->prependNamespace($this->packageNS);
            }

        });
    }

    protected function registerPermissionRepository()
    {
        $this->app->alias('ems.permissions', 'Permit\Permission\RepositoryInterface');

        $this->app->singleton('ems.permissions', function($app){
            $repo = $app->make('Permit\Support\Laravel\Permission\TranslatorRepository');
            $repo->setTranslationRoot('cmsable::permissions');
            return $repo;
        });

        $this->app->afterResolving('ems.permissions', function($permissions){
            $permissions->addCode('page.public-view');
            $permissions->addCode('page.logged-view');
            $permissions->addCode('cms.access');
            $permissions->addCode('page.edit');
            $permissions->addCode('page.delete');
            $permissions->addCode('page.add-child');
            $permissions->addCode('superuser');
            //page.public-view
        });

    }

    protected function registerAutoBreadCrumbProvider()
    {

        $this->app['events']->listen('resource::bus.started', function($bus) {
            $provider = $this->app->make('Ems\App\View\AutoBreadcrumbProvider');
            $this->app->instance('ems.auto-breadcrumbs', $provider);
        });

        $this->app['events']->listen('cmsable::breadcrumbs-load', function($factory) {

            if (!$this->app->bound('ems.auto-breadcrumbs')) {
                return;
            }

            $provider = $this->app['ems.auto-breadcrumbs'];

            $factory->provideFallback([$provider, 'addBreadcrumbs']);

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

    protected function registerFileDBExtension()
    {

//         $class = 'Cmsable\Controller\SiteTree\SiteTreeController';
// 
//         $this->app->afterResolving($class, function($controller, $app){
//             $controller->extend('jsConfig', function(&$jsConfig){
//                 $this->addCKEditorRoute($jsConfig);
//             });
//         });


        $class = 'FileDB\Controller\FileController';

        $this->app->afterResolving($class, function($controller, $app) {
            $controller->provideOpenLinkAttributes('ckeditor', function($file){

                return [
                    'href'    => $file->url,
                    'onclick' => "window.opener.CKEDITOR.tools.callFunction(1, $(this).attr('href')); window.close(); return false;",
                ];

            });
        });

        $this->app->afterResolving($class, function($controller, $app) {
            $controller->provideOpenLinkAttributes('image-field', function($file){

                return [
                    'href'    => $file->url,
                    'onclick' => "window.opener.assignToLastClickedImageField($file->id, $(this).attr('href')); window.close(); return false;",
                ];

            });
        });

        $this->app->afterResolving($class, function($controller, $app) {
            $controller->provideOpenLinkAttributes('upload-field', function($file){

                return [
                    'href'    => $file->url,
                    'onclick' => "window.opener.assignToLastClickedUploadField({$file->id}, '{$file->name}'); window.close(); return false;",
                ];

            });
        });

    }

    protected function registerMultiRenderer()
    {

        $this->app->singleton('cmsable-dist.multi-renderer', function($app){
            return $app->make('Ems\App\View\MultiRenderer');
        });

        $this->app->afterResolving('cmsable-dist.multi-renderer', function($renderer){
            $this->extendMultiRenderer($renderer);
        });

    }

    protected function extendMultiRenderer($renderer)
    {

        $renderer->extend('css', function() {

            $eventName = "cmsable-dist.css." . $this->app['router']->currentRouteName();

            if ($results = $this->app['events']->fire($eventName, [], false)) {
                $style = implode("\n", $results);
                return $style;
            }

            return '';
        });
    }

    protected function registerSystemAccessChecker()
    {
        $this->app->resolving('Permit\Access\CheckerChain', function($chain) {

            $chain->prependChecker($this->app->make('Ems\App\Services\Auth\Permit\SystemAccessChecker'));

        });
    }

    protected function registerGenericResourceProvider()
    {
        $this->app->afterResolving('Ems\Core\GenericResourceProvider', function($texts, $app) {
            $texts->replaceInKeys('.', '/');
        });
    }

    protected function registerTextProvider()
    {
        $this->app->singleton('Ems\Contracts\Core\TextProvider', function($app) {
            return $app->make('Ems\Core\Laravel\TranslatorTextProvider');
        });
    }

    protected function registerVariablesProvider()
    {
        $this->app->singleton('Ems\App\Contracts\Cms\VariableProvider', function($app) {
            return $app->make('Ems\App\Cms\VariableProviderQueue');
        });

        $this->app->afterResolving('Ems\App\Cms\AbstractVariableProvider', function($varProvider, $app) {
            $varProvider->setPresenter($app['Versatile\View\Contracts\ModelPresenter']);
            $varProvider->setTitleIntrospector($app['Versatile\Introspection\Contracts\TitleIntrospector']);
            $varProvider->setTextProvider($app['Ems\Contracts\Core\TextProvider']);
        });

    }

    protected function registerWidgets(WidgetRegistry $registry)
    {
        $registry->set('cmsable.widgets.image-box', 'Ems\App\Widgets\ImageBoxWidget');
        $registry->set('cmsable.widgets.content-box', 'Ems\App\Widgets\ContentBoxWidget');
    }

    protected function addCKEditorRoute(&$jsConfig)
    {
        $jsConfig['window.fileroute'] = $this->app['url']->route('files.index');
    }

    protected function registerAssetPaths($registry)
    {
        $assetPath = public_path($this->publicDir);
        $registry->map($this->assetNamespace.'.css', $assetPath, url($this->publicDir));
        $registry->map($this->assetNamespace.'.js', $assetPath, url($this->publicDir));
    }

    protected function registerAssetBuilds($repo)
    {

        $repo->store([
            'group' => $this->assetNamespace . '.js',
            'target' => 'js/cmsable.js',
            'parsers' => 'patchwork/jsqueeze',
            'files' => [
                'plugins/jQuery/jQuery-2.1.3.min.js',
                'plugins/jQueryUI/jquery-ui-1.11.4.min.js',
                'bootstrap/js/bootstrap.min.js'
            ],
            'managerOptions' => ['check_compiled_file_exists'=>false]
        ]);

        $repo->store([
            'group' => $this->assetNamespace . '.css',
            'target' => 'css/cmsable.css',
            'parsers' => 'cssmin/cssmin',
            'files' => [
                'css/filemanager.css',
                'AdminLTE/css/AdminLTE.min.css',
                'css/admin.css',
                'css/sitetree.css'
            ],
            'managerOptions' => ['check_compiled_file_exists'=>false]
        ]);
    }

    protected function registerInputCorrectors($corrector)
    {

        $corrector->extend('nested', function($input) {
            return NestedArray::toNested($input, '.');
        });

        $corrector->extend('dotted', function($input) {

            $cleaned = [];
            foreach ($input as $key=>$value) {
                // form naming to dots
                $cleaned[str_replace('__','.', $key)] = $value;
            }

            return $cleaned;

        });

    }

    protected function registerInputCasters($caster)
    {

        $caster->extend('no_leading_underscore', function($input) {

            $cleaned = [];
            foreach ($input as $key=>$value) {
                // tokens, _method...
                if (!starts_with($key,'_')) {
                    $cleaned[$key] = $value;
                }
            }

            return $cleaned;

        });

        $caster->extend('no_actions', function($input) {

            $cleaned = [];
            foreach ($input as $key=>$value) {
                // form actions
                if (!str_contains($key,'-')) {
                    $cleaned[$key] = $value;
                }
            }

            return $cleaned;

        });

        $caster->extend('no_confirmations', function($input) {

            $cleaned = [];
            foreach ($input as $key=>$value) {
                // form actions
                if (!ends_with($key, '_confirmation')) {
                    $cleaned[$key] = $value;
                }
            }

            return $cleaned;

        });

        $xtypeCaster = $this->app->make('Ems\App\Services\Casting\TypeIntrospectorCaster');

        $caster->extend('xtype_caster', $xtypeCaster);

    }

    protected function registerAssetGroupPrefix()
    {
        $this->app->afterResolving('Ems\Assets\Laravel\AssetsBladeDirectives', function ($directives) {
            $directives->mapDirectoryToGroupPrefix($this->resourcePath('views/admin'), $this->assetNamespace);
        });
    }

}
