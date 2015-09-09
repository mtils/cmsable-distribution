<?php


namespace Ems\App\View;

use Cmsable\Resource\ResourceBus;
use Versatile\View\Contracts\ModelPresenter;
use Cmsable\Resource\Contracts\Mapper;
use Cmsable\Resource\Contracts\ModelClassFinder as ClassFinderContract;
use Illuminate\Support\Pluralizer;
use Cmsable\Html\Breadcrumbs\StoreInterface;
use Illuminate\Contracts\Routing\UrlGenerator;

class AutoBreadcrumbProvider
{

    use ResourceBus;

    protected $presenter;

    protected $mapper;

    protected $store;

    protected $url;

    protected $resourceCache = [];

    public function __construct(ModelPresenter $presenter, Mapper $mapper,
                                StoreInterface $store, UrlGenerator $url)
    {
        $this->presenter = $presenter;
        $this->mapper = $mapper;
        $this->store = $store;
        $this->url = $url;
        $this->installResourceListeners();
    }

    public function addBreadcrumbs($route, $breadcrumbs)
    {

        list($resource, $action) = $this->splitRoute($route);

        if (!$resource) {
            return $breadcrumbs;
        }

        if ($action == 'index') {
            $this->appendIndexQuery($route, $breadcrumbs);
            return $breadcrumbs;
        }

        $args = func_get_args();

        if (!$id = array_pop($args)) {
            return $breadcrumbs;
        }

        if (!is_scalar($id) || !is_numeric($id)) {
            return $breadcrumbs;
        }

        if (!$model = $this->getModelFromCache($resource, $id)) {
            return $breadcrumbs;
        }

        $this->syncParentUrl($resource, $breadcrumbs);


        if ($shortName = $this->presenter->shortName($model)) {
            $breadcrumbs->add($shortName);
        }

        return $breadcrumbs;

    }

    protected function appendIndexQuery($route, $breadcrumbs)
    {

        $lastCrumb = $breadcrumbs->last();
        $requestPath = $this->getCurrentPath();

        if ($lastCrumb->getPath() != $requestPath) {
            return;
        }

        $parsedUrl = parse_url($this->url->full());

        if (!isset($parsedUrl['query'])) {
            return;
        }

        $params = [];

        parse_str($parsedUrl['query'], $params);

        if (!$params) {
            return;
        }

        $path = $parsedUrl['scheme'].'://'.$parsedUrl['host'].$parsedUrl['path'];

        $path .= '?' . http_build_query($params);

        $lastCrumb->setPath($path);

    }

    protected function syncParentUrl($resource, $breadcrumbs)
    {
        if (!$last = $breadcrumbs->last()) {
            return;
        }

        if ($url = $this->findStoredUrlByPath("$resource.index", $last->getPath())) {
            $last->setPath($url);
        }
    }

    protected function findStoredUrlByPath($routeName, $path)
    {
        if ($crumb = $this->findStoredCrumbByPath($routeName, $path)) {
            return $crumb->getPath();
        }
    }

    protected function findStoredCrumbByPath($routeName, $path)
    {
        foreach ($this->store->getStoredCrumbs($routeName) as $crumb) {
            if ($this->getPath($crumb->getPath())) {
                return $crumb;
            }
        }
    }

    protected function getPath($url)
    {
        if (!starts_with($url, 'http')) {
            return $url;
        }

        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['path'])) {
            return trim($parsedUrl['path'], " /");
        }
    }

    protected function getModelFromCache($resource, $id)
    {
        $cacheId = $this->cacheId($resource, $id);
        if (isset($this->resourceCache[$cacheId])) {
            return $this->resourceCache[$cacheId];
        }
    }

    protected function getCurrentPath()
    {

        $request = $this->url->getRequest();

        if  (method_exists($request, 'originalPath')) {
            return $request->originalPath();
        }

        return $request->path();

    }

    protected function installResourceListeners()
    {
        $this->listen('resource::*.found', function($model) {
            $resourceName = $this->modelToResource($model);
            $cacheId = $this->cacheId($resourceName, $model->getKey());
            $this->resourceCache[$cacheId] = $model;
        });
    }

    protected function modelToResource($model)
    {
        $class = class_basename($model);
        return Pluralizer::plural(snake_case($class, '-'));
    }

    protected function cacheId($resource, $id)
    {
        return "$resource<|v|>$id";
    }

    protected function splitRoute($routeName)
    {
        $split = explode('.', $routeName);
        if (count($split) == 2) {
            return $split;
        }

        return ['',''];

    }

}