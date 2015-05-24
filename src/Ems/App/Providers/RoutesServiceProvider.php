<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;
use Cmsable\PageType\PageType;

class RoutesServiceProvider extends ServiceProvider
{

    protected $routeGroup = [
        'namespace' =>  'Ems\App\Http\Controllers'
    ];

    public function boot()
    {
        $this->registerPasswordController();
    }

    public function register()
    {
        
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

    }

}