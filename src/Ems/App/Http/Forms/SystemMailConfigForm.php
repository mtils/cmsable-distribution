<?php


namespace Ems\App\Http\Forms;

use FormObject\Form;


class SystemMailConfigForm extends Form
{

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            Form::text('name'),
            Form::selectOne('template')
        );

        return $fields;

    }

    public function createActions()
    {
        return parent::createActionList('save');
    }

}