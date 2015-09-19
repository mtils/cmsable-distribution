<?php


namespace Ems\App\View;


use Versatile\View\ModelPresenterRegistry;
use Cmsable\Resource\Contracts\Distributor;
use Cmsable\Resource\Contracts\ModelClassFinder;
use Cmsable\Resource\Contracts\Mapper;

class ResourceModelPresenter extends ModelPresenterRegistry
{

    /**
     * @var \Cmsable\Resource\Contracts\Distributor
     **/
    protected $distributor;

    /**
     * @var \Cmsable\Resource\Contracts\ModelClassFinder
     **/
    protected $finder;

    /**
     * @var \Cmsable\Resource\Contracts\Mapper
     **/
    protected $mapper;

    /**
     * @var array
     **/
    protected $resourceProviders = [
        'shortName'         => [],
        'keys'              => [],
        'searchableKeys'    => []
    ];

    /**
     * @param \Cmsable\Resource\Contracts\Distributor $distributor
     **/
    public function __construct(Distributor $distributor, ModelClassFinder $finder,
                                Mapper $mapper)
    {
        $this->distributor = $distributor;
        $this->finder = $finder;
        $this->mapper = $mapper;
    }

    /**
     * {@inheritdoc}
     *
     * @param object $object
     * @param string $view (optional)
     * @return string
     **/
    public function shortName($object, $view=self::VIEW_DEFAULT)
    {

        $resource = $this->distributor->getCurrentResource();

        $class = get_class($object);

//         die($this->distributor->);

        return parent::shortName($object, $view);
    }

    /**
     * Add a callable which will handling getting the shortName for $resource
     *
     * @param string $resource
     * @param callable $provider
     * @return self
     **/
    public function provideResourceShortName($resource, callable $provider)
    {
        $this->resourceProviders['shortName'][$resource] = $provider;
        return $this;
    }

    /**
     * Add a callable which will handling getting the keys for $resource
     *
     * @param string $resource
     * @param callable $provider
     * @return self
     **/
    public function provideResourceKeys($resource, callable $provider)
    {
        $this->resourceProviders['keys'][$resource] = $provider;
        return $this;
    }

    /**
     * Add a callable which will handling getting the searchableKeys for $resource
     *
     * @param string $resource
     * @param callable $provider
     * @return self
     **/
    public function provideSearchableKeys($resource, callable $provider)
    {
        $this->resourceProviders['searchableKeys'][$resource] = $provider;
        return $this;
    }

    protected function isResource($class, $resource)
    {
        if ($modelClass = $this->mapper->modelClass($resource)) {
            return $modelClass == $class;
        }

        if ($modelClass = $this->finder->modelClass()
    }

}