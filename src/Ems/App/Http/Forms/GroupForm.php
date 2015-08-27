<?php namespace Ems\App\Http\Forms;

use Auth;
use FormObject\Form;
use FormObject\Field\TextField;
use FormObject\Field\SelectManyField;
use FormObject\FieldList;
use Collection\Map\Extractor;
use Permit\Groups\ManagerInterface as GroupManager;
use Permit\Permission\RepositoryInterface as Permissions;
use Cmsable\Resource\Contracts\ResourceForm;

class GroupForm extends Form
{

    protected $groups;

    protected $permissions;

    public function __construct(GroupManager $groups, Permissions $permissions)
    {
        $this->groups = $groups;
        $this->permissions = $permissions;
    }

    public function setModel($model)
    {
        $this->fillByArray($model->toArray());

        foreach($model->permissionCodes() as $code){
            $perm = $model->getPermissionAccess($code);
            if($perm == 1){
                $permsArray[] = (string)$code;
            }
        }

        $this->fillByArray(['codes'=>$permsArray], 'permission');

        return parent::setModel($model);
    }

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->setSwitchable(true);

        $mainFields = FieldList::create('main')
                                ->setSwitchable(true);

        $mainFields->push(
            Form::text('name')
        );

        /*--------------------------------------------------------------------------
        | Groups
        |--------------------------------------------------------------------------*/

        $mainFields->push($this->createPermissionField());

        $fields->push($mainFields);

        return $fields;

    }

    public function createActions()
    {
        return parent::createActionList('save');
    }

    protected function createPermissionField()
    {

        $permissions = $this->permissions->all();

        $extractor = new Extractor('getCode()', 'getTitle()');

        return Form::selectMany('permission__codes')
                                ->setClassName('MultiCheckboxField')
                                ->setSrc($permissions, $extractor);

    }
}