<?php namespace Ems\App\View;

use Cmsable\Support\HoldsContainer;
use Ems\Support\Traits\Extendable;
use BeeTree\Contracts\Node;
use Cmsable\Support\ReceivesContainerWhenResolved;

class MultiRenderer implements ReceivesContainerWhenResolved
{
    use HoldsContainer;
    use Extendable;

    public function tree(Node $node)
    {
        $renderer = $this->container->make('Ems\App\View\TreeRenderer');
        $renderer->setNode($node);
        return $renderer;
    }

    public function classTitle($object, $quantity=1)
    {
        $namer = $this->container->make('Versatile\Introspection\Contracts\TitleIntrospector');
        return $namer->objectTitle($object, $quantity);
    }

    public function __call($method, array $params=[])
    {
        return $this->callExtension($method, $params);
    }

}