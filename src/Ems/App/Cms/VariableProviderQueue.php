<?php


namespace Ems\App\Cms;

use function array_filter;
use function array_pop;
use function array_slice;
use function array_unshift;
use Ems\App\Contracts\Cms\VariableProvider;
use Ems\Core\Patterns\TraitOfResponsibility;
use function explode;
use function implode;


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

        // Do some post processing to allow later adding of nested variables

        $deleteQueue = [];

        foreach ($variables as $i=>$variable) {

            // Cut the delimiters
            $key = $this->normalizeKey($variable['name']);

            if (!$this->isNestedVariableKey($key)) {
                continue;
            }

            $segments = $this->splitKey($key);

            // Cut last, this is the a property, not a findable node
            $last = array_pop($segments);
            if (!$target = &$this->findVariable($variables, $segments)) {
                continue;
            }

            $target['children'][] = $variable;

            $deleteQueue[] = $variable['name'];

        }

        if (!$deleteQueue) {
            return $variables;
        }

        return array_filter($variables, function ($variable) use ($deleteQueue) {
            return !in_array($variable['name'], $deleteQueue);
        });
    }

    /**
     * @param string $key
     * @return string
     */
    protected function normalizeKey($key)
    {
        // Cut the delimiters
        return substr($key, 1, -1);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isNestedVariableKey($key)
    {
        return (bool)strpos($key, '.'); // starting with a point is not a variable
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function splitKey($key)
    {
        return explode('.', $key);
    }

    /**
     * Find the node of a variable by its path (key)
     *
     * @param array $variables
     * @param array $segments
     * @param int   $offset (default:1)
     *
     * @return array
     */
    protected function &findVariable(&$variables, array $segments, $offset=1)
    {
        $pathStack = array_slice($segments, 0, $offset);
        $path = implode('.', $pathStack);

        if (!$target = &$this->findInDirectChildren($variables, $path)) {
            $result = [];
            return $result;
        }

        if ($offset === count($segments)) {
            return $target;
        }

        return $this->findVariable($target['children'], $segments, $offset+1);

    }

    /**
     * @param $variables
     * @param string $key
     *
     * @return |null
     */
    protected function &findInDirectChildren(&$variables, $key)
    {
        foreach ($variables as $i=>$variable) {
            $normalized = $this->normalizeKey($variable['name']);
            if ($variable['name'] == $key || $normalized == $key) {
                return $variables[$i];
            }
        }
        $result = null;
        return $result;
    }
}
