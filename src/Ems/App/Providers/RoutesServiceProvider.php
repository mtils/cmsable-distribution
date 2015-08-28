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
        $this->registerAdminSecurityPage();
        $this->registerUserController();
        $this->registerGroupController();
        $this->registerPasswordController();
    }

    public function register()
    {
        $this->registerFileDBPageType();
    }

    protected function registerAdminSecurityPage()
    {

        $this->app['events']->listen('sitetree.filled', function(&$adminTreeArray){

            $security = [
                'id'                => 'files',
                'page_type'         => 'cmsable.redirector',
                'url_segment'       => 'security',
                'icon'              => 'fa-shield',
                'title'             => $this->app['translator']->get('ems::admintree.security.title'),
                'menu_title'        => $this->app['translator']->get('ems::admintree.security.menu_title'),
                'show_in_menu'      => true,
                'show_in_aside_menu'=> false,
                'show_in_search'    => true,
                'show_when_authorized' => true,
                'redirect_type'     => 'internal',
                'redirect_target'   => 'firstchild',
                'content'           => $this->app['translator']->get('ems::admintree.security.content'),
                'view_permission'   => 'cms.access',
                'edit_permission'   => 'superuser'
            ];

            $this->app['events']->fire('sitetree.security-added',[&$security]);

            $adminTreeArray['children'][] = $security;

        });
    }

    protected function registerUserController()
    {

        $this->app['events']->listen('cmsable.pageTypeLoadRequested', function($pageTypes){

            $pageType = PageType::create('cmsable.users-editor')
                                  ->setCategory('security')
                                  ->setRouteScope('admin')
                                  ->setTargetPath('users');

            $pageTypes->add($pageType);


        });

        $this->app->router->group($this->routeGroup, function($router){

            $router->resource('users','UserController');

            $router->put('users/{users}/activate',[
                'as'  => 'users.activate',
                'uses'=>'UserController@activate'
            ]);

        });

        $this->app['cmsable.actions']->onItem('App\User', function($group, $user, $resource){

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('users.edit', [$resource->getAuthIdentifier()]);
            $editUser = new Action();
            $editUser->setName('users-edit')->setTitle(
                $this->app['translator']->get('ems::actions.users.edit')
            );
            $editUser->setUrl($url);
            $editUser->setIcon('fa-edit');
            $editUser->showIn('users');
            $group->push($editUser);

        });

        $this->app['cmsable.actions']->onItem('App\User', function($group, $user, $resource){

            if ($resource->isActivated()) {
                return;
            }

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('users.activate', [$resource->getAuthIdentifier()]);
            $activateUser = new Action();
            $activateUser->setName('users-activate')->setTitle(
                $this->app['translator']->get('ems::actions.users.activate')
            );
            $activateUser->setIcon('fa-check');

            $activateUser->setOnClick("putRequest('$url', window.location.href); return false;");
            $activateUser->showIn('users','edit');

            $group->push($activateUser);

        });

        $this->app['cmsable.actions']->onItem('App\User', function($group, $user, $resource){

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('users.destroy', [$resource->getAuthIdentifier()]);
            $deleteUser = new Action();
            $deleteUser->setName('users-destroy')->setTitle(
                $this->app['translator']->get('ems::actions.users.destroy')
            );
            $deleteUser->setIcon('fa-trash');
            $deleteUser->showIn('users');

            $deleteUser->setOnClick("deleteResource('$url', this); return false;");
            $deleteUser->showIn('users','save','delete-request','danger','edit');
            $deleteUser->setData('confirm-message',
                $this->app['translator']->get('ems::actions.users.destroy-confirm')
            );

            $group->push($deleteUser);

        });

        $this->app['events']->listen('sitetree.security-added', function(&$security){

            $users = [
                'id'                => 'files',
                'page_type'         => 'cmsable.users-editor',
                'url_segment'       => 'users',
                'icon'              => 'fa-user',
                'title'             => $this->app['translator']->get('ems::admintree.users.title'),
                'menu_title'        => $this->app['translator']->get('ems::admintree.users.menu_title'),
                'show_in_menu'      => true,
                'show_in_aside_menu'=> false,
                'show_in_search'    => true,
                'show_when_authorized' => true,
                'redirect_type'     => 'none',
                'redirect_target'   => 0,
                'content'           => $this->app['translator']->get('ems::admintree.users.content'),
                'view_permission'   => 'cms.access',
                'edit_permission'   => 'superuser'
            ];

            $security['children'][] = $users;

        });

        $serviceProvider = $this;

        $this->app['cmsable.breadcrumbs']->register('users.edit', function($breadcrumbs, $userId) use ($serviceProvider) {

            foreach($this->getStoredCrumbs('users.index') as $crumb) {
                $breadcrumbs->add($crumb);
            }

            $user = $serviceProvider->app['Permit\Registration\UserRepositoryInterface']->retrieveById($userId);

            $breadcrumbs->add($user->getEmailForPasswordReset());

        });

    }

    protected function registerGroupController()
    {

        $this->app['events']->listen('cmsable.pageTypeLoadRequested', function($pageTypes){

            $pageType = PageType::create('cmsable.groups-editor')
                                  ->setCategory('security')
                                  ->setRouteScope('admin')
                                  ->setTargetPath('groups');

            $pageTypes->add($pageType);


        });

        $this->app->router->group($this->routeGroup, function($router){
            $router->resource('groups','GroupController');
        });

        $this->app['cmsable.actions']->onItem('App\Group', function($group, $user, $resource){

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('groups.edit', [$resource->getGroupId()]);
            $editGroup = new Action();
            $editGroup->setName('groups-edit')->setTitle(
                $this->app['translator']->get('ems::actions.groups.edit')
            );
            $editGroup->setUrl($url);
            $editGroup->setIcon('fa-edit');
            $editGroup->showIn('groups');
            $group->push($editGroup);

        });

        $this->app['cmsable.actions']->onItem('App\Group', function($group, $user, $resource){

            if (!$this->app['auth']->allowed('cms.access')){
                return;
            }

            $url = $this->app['url']->route('groups.destroy', [$resource->getGroupId()]);
            $deleteGroup = new Action();
            $deleteGroup->setName('groups-destroy')->setTitle(
                $this->app['translator']->get('ems::actions.groups.destroy')
            );
            $deleteGroup->setIcon('fa-trash');
            $deleteGroup->showIn('groups');

            $deleteGroup->setOnClick("deleteResource('$url', this); return false;");
            $deleteGroup->showIn('groups','save','delete-request','danger');
            $deleteGroup->setData('confirm-message',
                $this->app['translator']->get('ems::actions.groups.destroy-confirm')
            );

            $group->push($deleteGroup);

        });

        $this->app['events']->listen('sitetree.security-added', function(&$security){

            $users = [
                'id'                => 'files',
                'page_type'         => 'cmsable.groups-editor',
                'url_segment'       => 'groups',
                'icon'              => 'fa-users',
                'title'             => $this->app['translator']->get('ems::admintree.groups.title'),
                'menu_title'        => $this->app['translator']->get('ems::admintree.groups.menu_title'),
                'show_in_menu'      => true,
                'show_in_aside_menu'=> false,
                'show_in_search'    => true,
                'show_when_authorized' => true,
                'redirect_type'     => 'none',
                'redirect_target'   => 0,
                'content'           => $this->app['translator']->get('ems::admintree.groups.content'),
                'view_permission'   => 'cms.access',
                'edit_permission'   => 'superuser'
            ];

            $security['children'][] = $users;

        });

        $serviceProvider = $this;

        $this->app['cmsable.breadcrumbs']->register('groups.edit', function($breadcrumbs, $groupId) use ($serviceProvider) {

            foreach($this->getStoredCrumbs('groups.index') as $crumb) {
                $breadcrumbs->add($crumb);
            }

            $group = $serviceProvider->app['Permit\Groups\GroupRepositoryInterface']->findByGroupId($groupId);

            $breadcrumbs->add($group->name);

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