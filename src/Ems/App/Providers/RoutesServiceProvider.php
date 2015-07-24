<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;
use Cmsable\PageType\PageType;
use Cmsable\Cms\Action\Action;

class RoutesServiceProvider extends ServiceProvider
{

    protected $routeGroup = [
        'namespace' =>  'Ems\App\Http\Controllers'
    ];

    public function boot()
    {
        $this->registerUserController();
        $this->registerPasswordController();
    }

    public function register()
    {
        $this->registerFileDBPageType();
    }

    protected function registerUserController()
    {

        $this->app->router->group($this->routeGroup, function($router){
            $router->resource('users','UserController');
        });

        $this->app['cmsable.actions']->onItem('App\User', function($group, $user, $resource){

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('users.edit', [$resource->getAuthIdentifier()]);
            $editUser = new Action();
            $editUser->setName('users-edit')->setTitle('Edit user');
            $editUser->setUrl($url);
            $editUser->setIcon('fa-edit');
            $editUser->showIn('users');
            $group->push($editUser);

        });

        $this->app['cmsable.actions']->onItem('App\User', function($group, $user, $resource){

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('users.destroy', [$resource->getAuthIdentifier()]);
            $deleteUser = new Action();
            $deleteUser->setName('users-destroy')->setTitle('Delete user');
            $deleteUser->setIcon('fa-trash');
            $deleteUser->showIn('users');

            $deleteUser->setOnClick("deleteResource('$url', this); return false;");
            $deleteUser->showIn('users','save','delete-request','danger');
            $deleteUser->setData('confirm-message', 'Really delete this user?');

            $group->push($deleteUser);

        });

    }


    protected function registerPasswordController()
    {

        $this->app->router->group($this->routeGroup, function($router){

            $router->get('password/email', [
                'uses' => 'PasswordController@createEmail',
                'as' => 'password.create-email'
            ]);

            $router->post('password/email', [
                'uses' => 'PasswordController@sendEmail',
                'as' => 'password.send-email'
            ]);

            $router->get('password/reset/{token}', [
                'uses' => 'PasswordController@createReset',
                'as' => 'password.create-reset'
            ]);

            $router->post('password/reset/{token}', [
                'uses' => 'PasswordController@storeReset',
                'as' => 'password.store-reset'
            ]);
        });

        $this->app['events']->listen('cmsable.pageTypeLoadRequested', function($pageTypes){

            $pageType = PageType::create('cmsable.password-reset')
                                  ->setCategory('default')
                                  ->setFormPluginClass('Ems\App\Cms\Plugins\PasswordResetPlugin')
                                  ->setTargetPath('password/email')
                                  ->setControllerCreatorClass('Ems\App\Cms\ControllerCreators\PasswordResetControllerCreator')
                                  ->setRouteNames(['password.create-reset']);

            $pageTypes->add($pageType);

            $configTypeRepo = $this->app->make('Cmsable\PageType\ConfigTypeRepositoryInterface');

            $configTypeRepo->setTemplate('cmsable.password-reset',[
                'resetmail_subject' => '',
                'resetmail_body'    => '',
                'resetpage_title'   => '',
                'resetpage_content' => ''
            ]);


        });

        $this->app->afterResolving('cmsable.resource-detector', function($mapper) {
            $mapper->mapToRoute('password.create-email', 'password-emails');
            $mapper->mapToRoute('password.send-email', 'password-emails');
            $mapper->mapToRoute('password.create-reset', 'password-resets');
            $mapper->mapToRoute('password.store-reset', 'password-resets');
        });

//         $this->app->afterResolving('cmsable.resource-mapper', function($mapper) {
//             $mapper->mapFormClass('password-emails','Ems\App\Http\Forms\PasswordEmailForm');
//         });

    }

    public function registerFileDBPageType()
    {
        $this->app['events']->listen('cmsable.pageTypeLoadRequested', function($pageTypes){

            $pageType = PageType::create('cmsable.files')
                                  ->setCategory('default')
//                                   ->setFormPluginClass('Ems\App\Cms\Plugins\PasswordResetPlugin')
                                  ->setTargetPath('files')
                                  ->setControllerCreatorClass('Ems\App\Cms\ControllerCreators\FileControllerCreator');
//                                   ->setRouteNames(['password.create-reset']);

            $pageTypes->add($pageType);

//             $configTypeRepo = $this->app->make('Cmsable\PageType\ConfigTypeRepositoryInterface');

//             $configTypeRepo->setTemplate('cmsable.password-reset',[
//                 'resetmail_subject' => '',
//                 'resetmail_body'    => '',
//                 'resetpage_title'   => '',
//                 'resetpage_content' => ''
//             ]);


        });

        $this->app['events']->listen('sitetree.filled', function(&$adminTreeArray){
            $adminTreeArray['children'][] = [
                'id'                => 'files',
                'page_type'         => 'cmsable.files',
                'url_segment'       => 'manage-files',
                'icon'              => 'fa-folder-o',
                'title'             => $this->app['translator']->get('ems::admintree.files.title'),
                'menu_title'        => $this->app['translator']->get('ems::admintree.files.menu_title'),
                'show_in_menu'      => true,
                'show_in_aside_menu'=> false,
                'show_in_search'    => true,
                'show_when_authorized' => true,
                'redirect_type'     => 'none',
                'redirect_target'   => '',
                'content'           => $this->app['translator']->get('ems::admintree.files.content'),
                'view_permission'   => 'cms.access',
                'edit_permission'   => 'superuser'
            ];
        }, -1);

    }

}