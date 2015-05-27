<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use FormObject\Field\TextField;

class PasswordEmailForm extends Form
{

    public $validationRules = [
        'email'     => 'email|required'
    ];

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            TextField::create('email')
        );

        return $fields;
    }

}