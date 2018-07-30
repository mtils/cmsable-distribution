<?php namespace Ems\App\View;


use Cmsable\Support\FindsClasses;
use Exception;
use BeeTree\Support\ViewHelper;
use BeeTree\Contracts\Node;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;

class TreeRenderer
{

    use FindsClasses;

    protected $helper;

    protected $container;

    protected $parameters = [];

    protected $rendererClass = 'jsTreeProperty';

    protected $namespaces = ['Ems\App\View\TreeRenderer'];

    protected $exceptionHandler;

    protected $currentId = null;

    protected $node;

    protected $fromNode;

    protected $callQueue = [];

    public function __construct(ViewHelper $helper, Container $container,
                                ExceptionHandler $exceptionHandler)
    {
        $this->helper = $helper;
        $this->container = $container;
        $this->exceptionHandler = $exceptionHandler;
    }

    public function renderWith($rendererClass)
    {
        $this->rendererClass = $rendererClass;
        return $this;
    }

    public function setNode(Node $node)
    {
        $this->node = $node;
        return $this;
    }

    public function from(Node $node)
    {
        $this->fromNode = $node;
    }

    public function __toString()
    {

        try{

            $renderer = $this->container->make($this->findClass($this->rendererClass()));

            $this->performQueuedCalls($renderer);

            $liCreator = function(Node $node) use ($renderer) {
                return $renderer->render($node, $this->node);
            };

            return $this->helper->toUnorderedList($this->getRenderRootNode(), $liCreator);
        } catch (Exception $e) {
            $this->exceptionHandler->report($e);
        }

        return '';

    }

    protected function getRenderRootNode()
    {
        if ($this->fromNode) {
            return $this->fromNode;
        }

        if ($root = $this->helper->root($this->node)) {
            return $root;
        }

        return $this->node;
    }

    protected function rendererClass()
    {
        $passed = $this->rendererClass;
        $end = substr($passed, 0-strlen('Renderer'));

        $class = $end == 'Renderer' ? $passed : $passed.'Renderer';
        return ucfirst($class);

    }

    protected function performQueuedCalls($renderer)
    {
        foreach ($this->callQueue as $call) {
            call_user_func_array([$renderer, $call['method']], $call['params']);
        }
    }

    public function __call($method, array $params=[])
    {
        $this->callQueue[] = ['method'=>$method, 'params'=>$params];
        return $this;
    }

} 
