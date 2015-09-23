<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use FormObject\Field\TextField;
use FormObject\Field\PasswordField;
use FormObject\Field\HiddenField;

class PasswordResetForm extends Form
{

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            Form::password('password'),
            Form::password('password_confirmation'),
            Form::hidden('token')
        );

        return $fields;
    }

    public function createActions()
    {
        $actions = parent::createActions();
        $actions->get('action_submit');
        return $actions;
    }
}