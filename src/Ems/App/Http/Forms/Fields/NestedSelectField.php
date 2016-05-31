<?php


namespace Ems\App\Http\Forms\Fields;

use FormObject\Field\SelectOneField;
use BeeTree\ModelInterface;
use BeeTree\Helper;
use BeeTree\NodeInterface;

class NestedSelectField extends SelectOneField{

    protected $rootClass;

    protected $rootIds = array();

    protected $model;

    protected $emptyEntry;
    /**
     * Is the root selectable?
     * @var bool
     */
    protected $withRoot = false;

    /**
     * @return bool
     */
    public function getWithRoot()
    {
        return $this->withRoot;
    }

    /**
     * @param bool $withRoot
     * @return $this
     */
    public function setWithRoot($withRoot)
    {
        $this->withRoot = $withRoot;
        return $this;
    }

    public function __construct($name, $title){
        parent::__construct($name, $title);
    }

    public function getRootClass(){
        return $this->rootClass;
    }

    public function setRootClass($class){
        $this->rootClass = $class;
        return $this;
    }

    public function getRootIds(){
        if(!$this->rootIds){
            return array(1);
        }
        return $this->rootIds;
    }

    public function setRootIds(array $ids){
        $this->rootIds = $ids;
        return $this;
    }

    public function getModel(){
        return $this->model;
    }

    public function setModel(ModelInterface $model){
        $this->model = $model;
        return $this;
    }

    public function getSrc(){
        $src = array();

        if($this->emptyEntry){
            $src[$this->emptyEntry->id] = $this->emptyEntry;
        }

        foreach($this->getRootIds() as $rootId){
            foreach(Helper::flatify($this->model->tree($rootId)) as $node){
                $src[$node->getIdentifier()] = $node;
            }
        }
        return $src;
    }

    public function getEmptyEntry(){
        return $this->emptyEntry;
    }

    public function setEmptyEntry(NodeInterface $entry){
        $this->emptyEntry = $entry;
        return $this;
    }

}
