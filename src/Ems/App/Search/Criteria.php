<?php


namespace Ems\App\Search;

use Versatile\Search\Criteria as BaseCriteria;


class Criteria extends BaseCriteria
{

    protected $resource;

    public function resource()
    {
        return $this->resource;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }
}