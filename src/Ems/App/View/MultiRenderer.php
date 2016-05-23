<?php namespace Ems\App\View;

use RuntimeException;
use Cmsable\Support\HoldsContainer;
use Ems\Support\Traits\Extendable;
use BeeTree\Contracts\Node;
use BeeTree\Contracts\DatabaseNode;
use BeeTree\Contracts\Repository;
use BeeTree\Support\Sorter;
use Cmsable\Support\ReceivesContainerWhenResolved;

class MultiRenderer implements ReceivesContainerWhenResolved
{
    use HoldsContainer;
    use Extendable;

    public function tree(Node $node)
    {

        if (!$this->isPopulatedNode($node)) {
            $node = $this->toPopulated($node);
        }

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

    protected function isPopulatedNode(Node $node)
    {

        if ($node instanceof DatabaseNode) {
            return $node->treeIsPopulated();
        }

        return true;

    }

    protected function toPopulated(DatabaseNode $node)
    {
        if (!$node instanceof Repository) {
            throw new RuntimeException('I can only fill nodes which are instances of Beetree\Contracts\Repository');
        }
        $treeRootId = $node->isRoot() ? $node->getId() : $node->getAttribute($node->getRootIdName());
        $tree = $node->tree($treeRootId);

        $flat = (new Sorter)->flatify($tree);

        foreach ($flat as $sortedNode) {
            if ($sortedNode->getId() == $node->getId()) {
                return $sortedNode;
            }
        }

        return $tree;
    }

}