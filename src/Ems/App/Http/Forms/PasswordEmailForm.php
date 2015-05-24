<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use FormObject\Field\TextField;

class PasswordEmailForm extends Form
{

    use ProvidesFormTexts;

    public $validationRules = [
        'email'     => 'email|required'
    ];

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            TextField::create('email', $this->fieldTitle('email'))
        );

        return $fields;
    }

    public function createActions()
    {
        $actions = parent::createActions();
        $actions->get('action_submit')->setTitle($this->actionTitle('send'));
        return $actions;
    }
}