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

    protected function fillModel(Model $group, array $attributes)
    {

        parent::fillModel($group, $attributes);

        if (!isset($attributes['permission']['codes'])) {
            return;
        }

        $sendedCodes = $attributes['permission']['codes'];

        foreach ($group->permissionCodes() as $code) {
            $group->setPermissionAccess($code, (int)in_array($code, $sendedCodes));
        }

        foreach ($sendedCodes as $idx=>$code) {
            $group->setPermissionAccess($code, 1);
        }

    }

}