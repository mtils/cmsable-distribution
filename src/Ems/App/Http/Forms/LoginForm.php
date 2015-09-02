<?php namespace Ems\App\Http\Forms;

use URL;
use FormObject\Form;
use FormObject\Field\TextField;
use FormObject\Field\PasswordField;
use FormObject\Field\LiteralField;
use Illuminate\Translation\Translator;
use Illuminate\Contracts\Routing\UrlGenerator;

class LoginForm extends Form
{

    public $validationRules = [

        'email'     => 'email|required',
        'password'  => 'required'

    ];

    protected $translator;

    protected $url;

    public function __construct(Translator $translator, UrlGenerator $url)
    {
        $this->translator = $translator;
        $this->url = $url;
    }

    public function createFields()
    {

        $fields = parent::createFields();

        $forgotPassword = $this->translator->get('ems::forms.login.forgot-password.title');
        $forgotPasswordUrl = $this->url->route('password.create-email');

        $fields->push(
            Form::text('email')
        )->push(
            Form::password('password')
        )->push(
            Form::literal('forgot-password')->setContent(
             "<p><a href=\"$forgotPasswordUrl\">$forgotPassword</a></p>"
            )
        );

        return $fields;
    }

    public function createActions()
    {
        return parent::createActionList('login');
    }
}