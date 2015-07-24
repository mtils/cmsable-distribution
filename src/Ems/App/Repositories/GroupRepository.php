<?php namespace Ems\App\Repositories;

use Cmsable\Resource\EloquentRepository;
use Illuminate\Database\Eloquent\Model;
use FormObject\Form;
use App;

class GroupRepository extends EloquentRepository
{

    public static $modelClass = 'App\Group';

    public static $resourceName = 'groups';

    public static $formClass = 'Ems\App\Http\Forms\GroupForm';

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

    public function newForm(array $attributes=[])
    {
        return App::make(static::$formClass);
    }

}