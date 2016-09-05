<?php namespace Ems\App\Validators;

use Cmsable\Resource\ResourceValidator;

class MailConfigurationValidator extends ResourceValidator
{

    protected $rules = [
        'name'     => 'min:3|max:255|required',
        'template'  => 'required'
    ];

}