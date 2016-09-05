<?php


namespace Ems\App\Cms;

use Ems\App\Contracts\Cms\VariableProvider;
use Versatile\View\Contracts\ModelPresenter;
use Versatile\Introspection\Contracts\TitleIntrospector;
use Ems\Contracts\Core\TextProvider;
use Ems\Core\Collections\NestedArray;

abstract class AbstractVariableProvider implements VariableProvider
{

    /**
     * @var \Versatile\View\Contracts\ModelPresenter
     **/
    protected $presenter;

    /**
     * @var \Versatile\Introspection\Contracts\TitleIntrospector
     **/
    protected $titles;

    /**
     * @var \Ems\Contracts\Core\TextProvider
     **/
    protected $texts;

    /**
     * {@inheritdoc}
     *
     * @param string $resourceName
     * @param object $resource (optional)
     * @return array
     **/
    abstract public function variables($resourceName, $resource = null);

    /**
     * @param \Versatile\View\Contracts\ModelPresenter $presenter
     * @return self
     **/
    public function setPresenter(ModelPresenter $presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }

    /**
     * @param \Versatile\Introspection\Contracts\TitleIntrospector $titles
     * @return self
     **/
    public function setTitleIntrospector(TitleIntrospector $titles)
    {
        $this->titles = $titles;
        return $this;
    }

    /**
     * @param \Ems\Contracts\Core\TextProvider $texts
     * @return self
     **/
    public function setTextProvider(TextProvider $texts)
    {
        $this->texts = $texts;
        return $this;
    }

    /**
     * Collects the variables by ModelPresenter
     *
     * @param string $prefix
     * @param string $class
     * @return array
     **/
    protected function collectVariables($keys, $prefix, $class)
    {
        $vars = [];
        $this->buildVariables(NestedArray::pathsToNested($keys), $prefix, $class, '', $vars);
        return $vars;
    }

    /**
     * Collects the variables by ModelPresenter
     *
     * @param string $prefix
     * @param string $class
     * @return array
     **/
    protected function modelKeys($class)
    {
        return $this->presenter->keys($class, ModelPresenter::VIEW_ALL);
    }

    /**
     * Iterates recursively over the provides keys of ModelPresenter
     *
     * @param array $nested
     * @param string $prefix
     * @param string $keyPrefix
     * @param array $vars
     **/
    protected function buildVariables($nested, $prefix, $class, $keyPrefix, array &$vars)
    {

        foreach ($nested as $key=>$children) {

            $var = ['name' => '{' . "$prefix.$key" . '}', 'title' => $this->titles->keyTitle($class, "$keyPrefix$key")];


            if (!$children) {
                $vars[] = $var;
                continue;
            }

            $var['children'] = [];

            $this->buildVariables($children, "$prefix.$key", $class, "$keyPrefix$key.", $var['children']);

            $vars[] = $var;

        }
    }

}
