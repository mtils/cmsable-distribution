<?php namespace Ems\App\Http\Forms\Fields;

use FormObject\Attributes;
use FormObject\Field\SelectManyField;
use Versatile\Introspection\Contracts\PathIntrospector;
use Versatile\view\Contracts\ModelPresenter;
use Cmsable\Support\FindsResourceClasses;
use Illuminate\Contracts\Routing\UrlGenerator;
use Collection\ValueGetter\DottedObjectAccess;
use Collection\Column;
use RuntimeException;

class BelongsToManySelectField extends SelectManyField
{

    use FindsResourceClasses;

    /**
     * @var string
     **/
    protected $queryUrl = '';

    /**
     * @var \Versatile\Introspection\Contracts\PathIntrospector;
     **/
    protected $introspector;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     **/
    protected $url;

    /**
     * @var \Versatile\view\Contracts\ModelPresenter
     **/
    protected $scaffold;

    public function __construct(PathIntrospector $introspector,
                                UrlGenerator $url,
                                ModelPresenter $scaffold)
    {
        $this->introspector = $introspector;
        $this->url = $url;
        $this->scaffold = $scaffold;
        parent::__construct();
    }

    public function getQueryUrl()
    {
        if (!$this->queryUrl) {
            return $this->calculateQueryUrl();
        }
        return $this->queryUrl;
    }

    public function setQueryUrl($url)
    {
        $this->queryUrl = $url;
        return $this;
    }

    public function getSrc()
    {

        if ($this->src) {
            return $this->src;
        }

        $this->src = $this->autofill();

        return $this->src;
    }

    protected function calculateQueryUrl()
    {

        $model = $this->modelOrFail();

        $class = $this->introspector->classOfPath($model, $this->getName());

        $url = $this->url->route($this->resourceName($class).'.index');

        $foreign = $this->resourceName(get_class($model)) . '.' . $this->getName();

        return $url . "?_view=autocomplete&_foreign=$foreign";

    }

    protected function autofill()
    {

        $model = $this->modelOrFail();

        $accessor = new DottedObjectAccess;
        $accessor->dot = '__';

        $related = $accessor(new Column, $model, $this->getName());

        if (!$this->form->wasSubmitted()) {
            $this->form->fillByArray([$this->getName() => $related->modelKeys()]);
            return $this->relatedToArray($related);
        }

        if (!$value = $this->getValue()) {
            return [];
        }

        return $this->relatedToArray($this->getFromRelation($model, $value));

    }

    protected function relatedToArray($related)
    {
        $array = [];
        foreach ($related as $model) {
            $array[$this->scaffold->id($model)] = $this->scaffold->shortName($model);
        }
        return $array;
    }

    protected function modelOrFail()
    {
        if (!$model = $this->form->getModel()) {
            throw new RuntimeException('Assign a model to the form to calculate resource urls');
        }
        return $model;
    }

    protected function getFromRelation($model, array $keys)
    {

        if (!method_exists($model, $this->getName())) {
            throw new RuntimeException("Couldnt find relation $this->name on model " . get_class($model));
        }

        $relation = $model->{$this->getName()}();
        $related = $relation->getRelated();
        $relatedKey = $relation->getRelated()->getKeyName();

        return $related->newQuery()->whereIn($relatedKey, $keys)->get();

    }

    public function updateAttributes(Attributes $attributes)
    {
        parent::updateAttributes($attributes);
        $attributes->set('data-query-url', $this->getQueryUrl());
        $attributes->set('data-query-param', 'q');

    }
}