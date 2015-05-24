<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use FormObject\Field\TextField;
use FormObject\Field\PasswordField;
use FormObject\Field\HiddenField;
use FormObject\Field\LiteralField;

class PasswordResetForm extends Form
{

    use ProvidesFormTexts;

    public $validationRules = [
        'password'     => 'required|confirmed'
    ];

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            PasswordField::create('password', $this->fieldTitle('password')),
            PasswordField::create('password_confirmation', $this->confirmedName('password')),
            HiddenField::create('token')
        );

        return $fields;
    }

    public function createActions()
    {
        $actions = parent::createActions();
        $actions->get('action_submit')->setTitle($this->actionTitle('change'));
        return $actions;
    }
}