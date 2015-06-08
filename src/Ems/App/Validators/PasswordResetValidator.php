<?php namespace Ems\App\Validators;

use Cmsable\Resource\ResourceValidator;

class PasswordResetValidator extends ResourceValidator
{
    protected $rules = ['password' => 'required|confirmed'];
}
