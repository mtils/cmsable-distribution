<?php


namespace Ems\App\Cms;

use Ems\App\Contracts\Cms\VariableProvider;
use Ems\Core\Patterns\TraitOfResponsibility;


class VariableProviderQueue implements VariableProvider
{

    use TraitOfResponsibility;

    protected $callReversed = false;

    /**
     * {@inheritdoc}
     *
     * @param string $resourceName
     * @param object $resource (optional)
     * @return array
     **/
    public function variables($resourceName, $resource = null)
    {

        $variables = [];

        foreach ($this->candidates as $provider) {
            $variables = array_merge($variables, $provider->variables($resourceName, $resource));
        }

        return $variables;
    }

}
