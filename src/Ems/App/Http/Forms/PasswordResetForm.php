<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use FormObject\FieldList;
use FormObject\Field\Action;
use FormObject\Field\TextField;
use FormObject\Field\PasswordField;
use FormObject\Field\HiddenField;
use FormObject\Field\LiteralField;


class PasswordResetForm extends Form
{

    public $validationRules = [
        'password'     => 'required|confirmed'
    ];

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            PasswordField::create('password'),
            PasswordField::create('password_confirmation'),
            HiddenField::create('token')
        );

        return $fields;
    }

    public function createActions()
    {
        $actions = new FieldList;
        $actions->setForm($this);
        $actions->push(Action::create('change'));
        return $actions;
    }
}