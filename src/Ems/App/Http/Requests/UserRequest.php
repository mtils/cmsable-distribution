<?php namespace Ems\App\Http\Requests;

use Cmsable\Http\Resource\SaveRequest;
use Ems\App\Http\Forms\UserForm;

class UserRequest extends SaveRequest
{

    protected $form;

    public function __construct(UserForm $form)
    {
        $this->form = $form;
    }

    public function rules()
    {
        return [
            'email'         => 'email|required',
            'groups__ids'   => 'required'
        ];
    }

    public function attributes()
    {
        return $this->form->getValidator()->buildAttributeNames($this->form);
    }

}