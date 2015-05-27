<?php namespace Ems\App\Managers;

use Cmsable\Model\Resource\EloquentResourceManager;
use Illuminate\Database\Eloquent\Model;
use FormObject\Form;
use App;

class UserManager extends EloquentResourceManager
{

    public static $modelClass = 'App\User';

    public static $resourceName = 'users';

    public static $formClass = 'Ems\App\Http\Forms\UserForm';

    protected $userModel;

    public function __construct()
    {
    }

    public function getModel()
    {
        if (!$this->userModel) {
            $class = static::$modelClass;
            $this->userModel = new $class;
        }

        return $this->userModel;
    }

    public function resourceName()
    {
        return static::$resourceName;
    }

    public function newForm(array $attributes=[])
    {
        return App::make(static::$formClass);
    }

    public function update($model, array $newAttributes)
    {
        parent::update($model, $newAttributes);

        $model->groups()->sync($newAttributes['groups']['ids']);

        return $model;
    }

}