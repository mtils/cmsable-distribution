<?php namespace Ems\App\Validators;

use Cmsable\Resource\ResourceValidator;

class LoginValidator extends ResourceValidator
{

    protected $rules = [
        'email'     => 'email|required',
        'password'  => 'required'
    ];

}