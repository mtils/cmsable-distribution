<?php

namespace Ems\App\Search;


use Cmsable\Support\FindsClasses;
use Versatile\Search\BuilderSearch as BaseBuilderSearch;
use Versatile\Search\Contracts\Criteria as CriteriaContract;
use Cmsable\Resource\ResourceBus;
use Illuminate\Contracts\Container\Container;


class CustomBuilderFactory
{

    use ResourceBus;
    use FindsClasses;

    public $builderSuffix = 'QueryBuilder';

    protected $app;

    protected $namespaces = ['App\Search'];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function createSearch(CriteriaContract $criteria)
    {

        $builder = $this->createBuilder($criteria);

        if (!$resource = $criteria->resource()) {
            return $builder;
        }

        if (!method_exists($builder, 'onBuilding')) {
            return $builder;
        }

        $builder->onBuilding(function($builder) use ($resource) {
            $this->fire($this->eventName("$resource.query"), $builder);
        });

        return $builder;

    }

    protected function createBuilder(CriteriaContract $criteria)
    {

        if ($bestClass = $this->bestBuilderClass($criteria)) {
            return $this->app->make($bestClass);
        }

        return $this->makeDefaultBuilder($criteria);
    }

    protected function bestBuilderClass(CriteriaContract $criteria)
    {
        if (!$resource = $criteria->resource()) {
            return $this->findClass(class_basename($criteria->modelClass()));
        }

        $baseName = $this->resourceToClass($resource) . $this->builderSuffix;

        return $this->findClass($baseName);
    }

    protected function makeDefaultBuilder(CriteriaContract $criteria)
    {
        $modelClass = $criteria->modelClass();
        $builder = $this->app->make('Versatile\Query\Builder', ['model' => new $modelClass]);
        return $this->app->make('Versatile\Search\BuilderSearch',[
            'builder'  => $builder,
            'criteria' => $criteria
        ]);
    }

    protected function resourceToClass($resource)
    {
        $class = ucfirst(camel_case($resource));
        return substr($class, 0, strlen($class)-1);
    }
}
