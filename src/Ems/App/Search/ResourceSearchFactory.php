<?php

namespace Ems\App\Search;


use Versatile\Search\ProxySearchFactory;
use Versatile\Search\Contracts\Criteria as CriteriaContract;


class ResourceSearchFactory extends ProxySearchFactory
{

    protected $resourceFactories = [];

    /**
     * {@inheritdoc}
     *
     * @param \Versatile\Search\Contracts\Criteria $criteria
     * @return \Versatile\Search\Contracts\Search
     **/
    public function search(CriteriaContract $criteria)
    {

        if ($search = $this->findResourceSearch($criteria)) {
            return $search;
        }

        return parent::search($criteria);

    }

    public function forResource($resource, callable $factory)
    {
        $this->resourceFactories[$resource] = $factory;
        return $this;
    }

    protected function findResourceSearch(CriteriaContract $criteria)
    {
        if (!$resource = $criteria->resource()) {
            return;
        }

        if (!isset($this->resourceFactories[$resource])) {
            return;
        }

        return call_user_func($this->resourceFactories[$resource], $criteria);
    }
}