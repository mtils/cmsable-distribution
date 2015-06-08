<?php namespace Ems\App\Validators;

use Cmsable\Resource\ResourceValidator;

class UserValidator extends ResourceValidator
{

    protected $rules = [
        'email'         => 'email|required',
        'groups.ids'    => 'required'
    ];

}