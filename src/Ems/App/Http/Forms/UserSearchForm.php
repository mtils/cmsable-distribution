<?php namespace Ems\App\Http\Forms;

use Auth;
use FormObject\Form;
use Collection\Map\Extractor;
use Permit\Groups\ManagerInterface as GroupManager;

class UserSearchForm extends Form
{

    protected $groups;

    public function __construct(GroupManager $groups)
    {
        $this->groups = $groups;
        $this->setMethod(self::GET);
    }

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            Form::text('email')
        );

        /*--------------------------------------------------------------------------
        | Groups
        |--------------------------------------------------------------------------*/

        $fields->push($this->createGroupField());

        return $fields;

    }

    public function createActions()
    {
        return parent::createActionList('search');
    }

    protected function createGroupField()
    {

        $groups = $this->groups->findAccessableGroupsFor(Auth::user());

        return Form::selectMany('groups__ids')
                     ->setSrc($groups, new Extractor('id', 'name'));

    }
}