<?php

namespace Ems\App\Http\Requests;


use ReflectionObject;
use Illuminate\Contracts\Validation\ValidationException;
use Cmsable\Http\Resource\CleanedRequest as CmsableCleanedRequest;
use Ems\App\Helpers\ProvidesTexts;

class CleanedRequest extends CmsableCleanedRequest
{

    use ProvidesTexts;

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return mixed
     */
    protected function failedValidation(ValidationException $exception)
    {

        if ($this->hasRouteMessage('validation-failed')) {
            $this->notifier()->error($this->routeMessage('validation-failed'));
        }

        return parent::failedValidation($exception);
    }

    protected function notifier()
    {
        return $this->container->make('Cmsable\View\Contracts\Notifier');
    }

    protected function classNamespaceForTranslation()
    {
        $actionName = $this->currentRoute()->getActionName();
        if (!$actionName) {
            return (new \ReflectionObject($this))->getNamespaceName();
        }
        list($controllerClass, $action) = explode('@',$actionName);
        return $controllerClass;
    }

}