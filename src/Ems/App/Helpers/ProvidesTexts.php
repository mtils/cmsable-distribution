<?php namespace Ems\App\Helpers;

use App;
use Route;
use OutOfBoundsException;
use DateTime;

trait ProvidesTexts
{

    protected $_translator;

    protected $_translationNamespace = 'ems::';

    protected $currentRouteKey;

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
        $baseKey = $this->baseTransKey('message');
        $route = $this->currentRouteKey();
        $completeKey = "$baseKey.$route.$key";
        return $this->trans($completeKey, $replace, $locale);
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

    protected function translationNamespace()
    {
        return $this->_translationNamespace;
    }

}