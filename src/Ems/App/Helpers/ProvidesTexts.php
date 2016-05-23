<?php namespace Ems\App\Helpers;

use App;
use Route;
use OutOfBoundsException;
use DateTime;
use ReflectionObject;

trait ProvidesTexts
{

    protected $_translator;

    protected $_translationNamespace = null;

    protected $currentRouteKey;

    protected $_currentRoute;

    protected $_groupToRootname = [
        'form'      => 'forms',
        'message'   => 'messages',
        'email'     => 'emails',
        'base'      => 'base'
    ];

    protected function trans($key, array $replace = array(), $locale = null)
    {
        return $this->translator()->get($key, $replace, $locale);
    }

    protected function routeMessage($key, array $replace = array(), $locale = null)
    {
        return $this->trans($this->routeMessageKey($key), $replace, $locale);
    }

    protected function routeMessageKey($key)
    {
        $baseKey = $this->baseTransKey('message');
        $route = $this->currentRouteKey();
        return "$baseKey.$route.$key";
    }

    protected function routeMailText($key, array $replace = array(), $locale = null)
    {
        $baseKey = $this->baseTransKey('email');
        $route = $this->currentRouteKey();
        $completeKey = "$baseKey.$route.$key";
        return $this->trans($completeKey, $replace, $locale);
    }

    protected function formatDate(DateTime $date=null)
    {
        $dateTime = $date ?: new DateTime;
        return $dateTime->format($this->trans('ems::base.date-format'));

    }

    protected function formatDateTime(DateTime $date=null)
    {
        $dateTime = $date ?: new DateTime;
        return $dateTime->format($this->trans('ems::base.datetime-format'));
    }

    protected function formatTime(DateTime $date=null)
    {
        $dateTime = $date ?: new DateTime;
        return $dateTime->format($this->trans('ems::base.time-format'));
    }

    protected function transKey($root)
    {
        if (isset($this->_groupToRootname[$root])) {
            return $this->_groupToRootname[$root];
        }
    }

    protected function baseTransKey($group)
    {
        if (!isset($this->_groupToRootname[$group])) {
            throw new OutOfBoundsException("No translation route key known for group $group");
        }

        return $this->translationNamespace() . $this->_groupToRootname[$group];
    }

    protected function translator()
    {
        if (!$this->_translator) {
            $this->_translator = App::make('Cmsable\Translation\TranslatorInterface');
        }
        return $this->_translator;
    }

    protected function currentRouteKey()
    {
        if (!$this->currentRouteKey) {
            $this->currentRouteKey = str_replace('.','/',Route::currentRouteName());
        }
        return $this->currentRouteKey;
    }

    protected function currentRoute()
    {
        if (!$this->_currentRoute) {
            $this->_currentRoute = Route::current();
        }
        return $this->_currentRoute;
    }

    protected function translationNamespace()
    {
        if ($this->_translationNamespace === null) {
            $this->_translationNamespace = $this->detectTranslationNamespace();
        }
        return $this->_translationNamespace;
    }

    public function setTranslastionNamespace($namespace)
    {
        $this->_translationNamespace = $namespace;
        return $this;
    }

    protected function detectTranslationNamespace()
    {
        $classNamespace = $this->classNamespaceForTranslation();

        if (starts_with($classNamespace, 'App\\')) {
            return '';
        }

        $segments = explode('\\', $classNamespace);

        if (!$segments) {
            return '';
        }

        return snake_case($segments[0], '-') . '::';
    }

    protected function classNamespaceForTranslation()
    {
        return (new \ReflectionObject($this))->getNamespaceName();
    }

    protected function hasRouteMessage($key)
    {
        $msgKey = $this->routeMessageKey($key);
        $msg = $this->routeMessage($key);
        if ($msg == $msgKey) {
            return false;
        }
        return trim($msg) != '';
    }

    protected function isTransKey($msg)
    {
        return starts_with($msg, $this->_translationNamespace)
               && (mb_strpos($msg, ' ') === false);
    }

}