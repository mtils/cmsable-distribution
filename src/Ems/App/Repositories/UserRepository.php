<?php namespace Ems\App\Repositories;

use Cmsable\Resource\EloquentRepository;
use Illuminate\Database\Eloquent\Model;
use FormObject\Form;
use App;

class UserRepository extends EloquentRepository
{

    public static $modelClass = 'App\User';

    public static $resourceName = 'users';

    public static $formClass = 'Ems\App\Http\Forms\UserForm';

    protected $userModel;

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

    public function update($model, array $newAttributes)
    {
        parent::update($model, $newAttributes);

        $model->groups()->sync($newAttributes['groups']['ids']);

        return $model;
    }

}