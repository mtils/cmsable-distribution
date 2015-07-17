<?php namespace Ems\App\Cms\ControllerCreators;

use App;
use Illuminate\Contracts\Container\Container;
use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Routing\ControllerCreatorInterface;
use Cmsable\PageType\CurrentPageTypeProviderInterface as PageTypes;
use Illuminate\Routing\Router;
use Cmsable\Html\Breadcrumbs\Factory as BreadcrumbFactory;

class FileControllerCreator implements ControllerCreatorInterface
{

    protected $pageTypes;

    protected $router;

    protected $breadcrumbs;

    public function __construct(PageTypes $pageTypes, Router $router,
                                BreadcrumbFactory $breadcrumbs)
    {
        $this->pageTypes = $pageTypes;
        $this->router = $router;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $controllerName The classname of the routed controller
     * @param \Cmsable\Model\SiteTreeNodeInterface $page (optional) Null if no match
     * @return \Illuminate\Routing\Controller
     **/
    public function createController($name, SiteTreeNodeInterface $page=NULL){

        $controller = App::make($name);
        $controller->setTemplate('files.index');
        return $controller;

    }

    protected function createControllerForResetAction($name, $page)
    {

        $controller = App::make($name);

        if (!$config = $this->pageTypes->currentConfig()) {
            return $controller;
        }

        if (!$title = $config->resetpage_title) {
            return $controller;
        }

        $content = $config->resetpage_content;

        $this->breadcrumbs->register('password.create-reset', function($breadcrumbs, $code=null) use ($title, $content) {
            $breadcrumbs->add($title, null, $title, $content);
        });


        return App::make($name);

    }

}