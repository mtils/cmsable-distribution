<?php namespace Ems\App\Validators;

use Cmsable\Resource\ResourceValidator;

class GroupValidator extends ResourceValidator
{

    protected $rules = [
        'name'         => 'min:2|max:255|required'
    ];

}