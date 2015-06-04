<?php namespace Ems\App\Http\Route;

use Illuminate\Routing\ResourceRegistrar;

class TreeResourceRegistrar extends ResourceRegistrar
{

    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = [
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy',
        'childrenIndex',
        'childrenCreate',
        'childrenStore',
        'childrenShow',
        'childrenEdit',
        'childrenUpdate',
        'childrenDestroy',
        'parentShow',
        'parentEdit',
        'parentUpdate',
    ];

    protected $nameToMethod = [
        'children.index'    => 'children',
        'children.create'   => 'createChild',
        'children.store'    => 'storeChild',
        'children.show'     => 'showChild',
        'children.edit'     => 'editChild',
        'children.update'   => 'updateChild',
        'children.destroy'  => 'destroyChild',
        'parent.show'       => 'showParent',
        'parent.edit'       => 'editParent',
        'parent.update'     => 'updateParent',
    ];

    public function register($name, $controller, array $options = [])
    {
        parent::register($name, $controller, $options);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenIndex($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children");
        $action = $this->getResourceAction($name, $controller, 'children.index', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenCreate($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children") . "/create";
        $action = $this->getResourceAction($name, $controller, 'children.create', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenStore($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children");
        $action = $this->getResourceAction($name, $controller, 'children.store', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenShow($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children") . '/{children}';
        $action = $this->getResourceAction($name, $controller, 'children.show', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenEdit($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children") . '/{children}/edit';
        $action = $this->getResourceAction($name, $controller, 'children.edit', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenUpdate($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children") . '/{children}';
        $action = $this->getResourceAction($name, $controller, 'children.update', $options);

        return $this->router->put($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceChildrenDestroy($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.children") . '/{children}';
        $action = $this->getResourceAction($name, $controller, 'children.destroy', $options);

        return $this->router->delete($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceParentShow($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.parent");
        $action = $this->getResourceAction($name, $controller, 'parent.show', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceParentEdit($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.parent").'/edit';
        $action = $this->getResourceAction($name, $controller, 'parent.edit', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceParentUpdate($name, $base, $controller, $options)
    {

        $uri = $this->getResourceUri("$name.parent");
        $action = $this->getResourceAction($name, $controller, 'parent.update', $options);

        return $this->router->put($uri, $action);
    }

    /**
     * Get the action array for a resource route.
     *
     * @param  string  $resource
     * @param  string  $controller
     * @param  string  $method
     * @param  array   $options
     * @return array
     */
    protected function getResourceAction($resource, $controller, $method, $options)
    {
            $name = $this->getResourceName($resource, $method, $options);

            return ['as' => $name, 'uses' => $controller.'@'.$this->getMethodName($method)];
    }

    protected function getMethodName($method)
    {
        if (!strpos($method, '.')) {
            return $method;
        }

        if (isset($this->nameToMethod[$method])) {
            return $this->nameToMethod[$method];
        }

        return lcfirst(str_replace(' ','', ucwords(str_replace(['-', '_', '.'], ' ', $method))));
    }

}