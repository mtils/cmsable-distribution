<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;
use FormObject\Form;
use Cmsable\Controller\SiteTree\SiteTreeController;
use Illuminate\Routing\Router;
use Ems\App\Http\Route\TreeResourceRegistrar;
use Ems\App\Search\Criteria;

class PackageServiceProvider extends ServiceProvider
{

    protected $packageNS = 'ems';

    protected $packagePath = '';

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

    }

    public function register()
    {

        $this->registerAutoBreadCrumbProvider();
        $this->registerValidatorNamespace();
        $this->registerFormNamespace();
        $this->registerVersatileDateFormat();
        $this->registerVersatileCriteria();
        $this->registerAutocompleteMorpher();
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
    }

    protected function addCKEditorRoute(&$jsConfig)
    {
        $jsConfig['window.fileroute'] = $this->app['url']->route('files.index');
    }

}