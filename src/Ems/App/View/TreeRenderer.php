<?php namespace Ems\App\View;


use BeeTree\Support\ViewHelper;
use BeeTree\Support\Node;
use Signal\Support\FindsClasses;
use Illuminate\Contracts\Container\Container;

class TreeRenderer
{

    use FindsClasses;

    protected $helper;

    protected $container;

    protected $parameters = [];

    protected $rendererClass = 'jsTreeProperty';

    protected $namespaces = ['Ems\App\View\TreeRenderer'];

    protected $currentId = null;

    protected $node;

    public function __construct(ViewHelper $helper, Container $container)
    {
        $this->helper = $helper;
        $this->container = $container;
    }

    public function renderWith($rendererClass)
    {
        $this->rendererClass = $rendererClass;
        return $this;
    }

    public function init(Node $node, $currentId=null)
    {
        $this->parameters = func_get_args();
        return $this;
    }

    public function __toString()
    {
        
    }

} 
