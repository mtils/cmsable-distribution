<?php namespace Ems\App\Validators;

use Cmsable\Resource\ResourceValidator;

class PasswordEmailValidator extends ResourceValidator
{
    protected $rules = ['email' => 'email|required'];
}
