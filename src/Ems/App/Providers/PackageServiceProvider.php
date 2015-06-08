<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;
use FormObject\Form;
use Cmsable\Controller\SiteTree\SiteTreeController;
use Illuminate\Routing\Router;
use Ems\App\Http\Route\TreeResourceRegistrar;

class PackageServiceProvider extends ServiceProvider
{

    protected $packageNS = 'ems';

    protected $packagePath = '';

    public function boot()
    {

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
    }

    public function register()
    {
        $this->registerValidatorNamespace();
        $this->registerFormNamespace();
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

    protected function addCKEditorRoute(&$jsConfig)
    {
        $jsConfig['window.fileroute'] = $this->app['url']->route('files.index');
    }

}