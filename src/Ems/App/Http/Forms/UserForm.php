<?php namespace Ems\App\Http\Forms;

use Auth;
use FormObject\Form;
use FormObject\Field\TextField;
use FormObject\Field\SelectManyField;
use FormObject\FieldList;
use Collection\Map\Extractor;
use Permit\Groups\ManagerInterface as GroupManager;
use Cmsable\Resource\Contracts\ResourceForm;

class UserForm extends Form
{

    protected $groups;

    public function __construct(GroupManager $groups)
    {
        $this->groups = $groups;
    }

    public function setModel($model)
    {
        $this->fillByArray($model->toArray());
        $this->fillByArray(['ids'=>$model->groups()->getRelatedIds()], 'groups');
        return parent::setModel($model);
    }

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->setSwitchable(true);

        $mainFields = FieldList::create('main')
                                ->setSwitchable(true);

        $mainFields->push(
            Form::text('email')
        );

        /*--------------------------------------------------------------------------
        | Groups
        |--------------------------------------------------------------------------*/

        $mainFields->push($this->createGroupField());

        $fields->push($mainFields);

        return $fields;

    }

    public function createActions()
    {
        return parent::createActionList('save');
    }

    protected function createGroupField()
    {

        $groups = $this->groups->findAccessableGroupsFor(Auth::user());

        return SelectManyField::create('groups__ids')
                                ->setClassName('MultiCheckboxField')
                                ->setSrc($groups, new Extractor('id', 'name'));

    }
}