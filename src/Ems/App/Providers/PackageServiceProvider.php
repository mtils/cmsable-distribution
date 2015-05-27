<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;
use FormObject\Form;

class PackageServiceProvider extends ServiceProvider
{

    protected $packageNS = 'ems';

    protected $packagePath = '';

    public function boot()
    {
        $this->loadTranslationsFrom(
            $this->resourcePath('lang'),
            $this->packageNS
        );

        $this->registerAdminViewPath();

        $this->registerFormTranslations();
    }

    public function register()
    {
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

}