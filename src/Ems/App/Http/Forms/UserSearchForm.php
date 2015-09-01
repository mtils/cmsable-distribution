<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use Collection\Map\Extractor;
use Permit\Groups\ManagerInterface as GroupManager;
use Permit\CurrentUser\ContainerInterface as Auth;

class UserSearchForm extends Form
{

    protected $groups;

    protected $auth;

    public function __construct(GroupManager $groups, Auth $auth)
    {
        $this->groups = $groups;
        $this->auth = $auth;
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

        $groups = $this->groups->findAccessableGroupsFor($this->auth->user());

        return Form::selectMany('groups__ids')
                     ->setSrc($groups, new Extractor('id', 'name'));

    }
}