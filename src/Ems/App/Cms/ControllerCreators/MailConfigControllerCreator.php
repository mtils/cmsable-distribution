<?php namespace Ems\App\Cms\ControllerCreators;

use App;
use Ems\Contracts\Mail\MailConfig;
use Illuminate\Contracts\Container\Container;
use Cmsable\Model\SiteTreeNodeInterface;
use Cmsable\Routing\ControllerCreatorInterface;
use Cmsable\PageType\CurrentPageTypeProviderInterface as PageTypes;
use Illuminate\Routing\Router;
use Cmsable\Html\Breadcrumbs\Factory as BreadcrumbFactory;

class MailConfigControllerCreator implements ControllerCreatorInterface
{

    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $controllerName The classname of the routed controller
     * @param \Cmsable\Model\SiteTreeNodeInterface $page (optional) Null if no match
     * @return \Illuminate\Routing\Controller
     **/
    public function createController($name, SiteTreeNodeInterface $page=NULL){

        $controller = $this->container->make($name);

        if (!$page) {
            return $controller;
        }
        if (!$rootId = $this->getTreeRootId($page)) {
            return $controller;
        }

        $controller->redirectIndexToTree($rootId);

        return $controller;

    }

    protected function getTreeRootId(SiteTreeNodeInterface $page)
    {
        if ($page->getPageTypeId() == 'cmsable.mailconfig-editor.system') {
            return MailConfig::SYSTEM;
        }
    }

}