<?php namespace Ems\App\Http\Forms;

use FormObject\Form;

class PasswordEmailForm extends Form
{

    /**
     * @return \FormObject\FieldList
     **/
    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            Form::text('email')
        );

        return $fields;
    }

    /**
     * @return \FormObject\FieldList
     **/
    public function createActions()
    {
        $actions = parent::createActions();
        $actions->get('action_submit')->setTitle('Zusenden');
        return $actions;
    }
}