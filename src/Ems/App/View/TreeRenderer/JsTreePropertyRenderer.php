<?php namespace Ems\App\View\TreeRenderer;


use BeeTree\Contracts\Node;

class JsTreePropertyRenderer
{

    protected $titleKey = 'name';

    protected $iconClass = '';

    protected $currentId;

    public function __construct($titleProperty='name', $currentId=null)
    {
        $this->titleProperty = $titleProperty;
        $this->currentId = $currentId;
    }

    public function title($titleKey)
    {
        $this->titleKey = $titleKey;
        return $this;
    }

    public function iconClass($iconClass)
    {
        $this->iconClass = $iconClass;
        return $this;
    }

    public function render(Node $node, Node $passedNode)
    {

        $liClasses = ['jstree-open'];

        if ($node->isRoot()) {
            $liClasses[] = 'root-node';
        }

        if ($this->iconClass) {
            $liClasses[] = $this->iconClass;
        }

        $spanClasses = [];

        if ($passedNode->getId() == $node->getId()){
            $spanClasses[] = 'active';
        }

        $liClass = implode(' ', $liClasses);
        $spanClass = implode(' ', $spanClasses);

        $title = $node->{$this->titleKey};
        $id = $node->getId();

        return "<li data-id=\"{$id}\" class=\"$liClass\"><span class=\"$spanClass\">{$title}</span>";

    }

}