<?php namespace Ems\App\Http\Forms;

use FormObject\Form;
use Illuminate\Translation\Translator;
use Illuminate\Contracts\Routing\UrlGenerator;

class LoginForm extends Form
{

    /**
     * @var \Illuminate\Translation\Translator
     **/
    protected $translator;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     **/
    protected $url;

    /**
     * @param \Illuminate\Translation\Translator $translator
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     **/
    public function __construct(Translator $translator, UrlGenerator $url)
    {
        $this->translator = $translator;
        $this->url = $url;
    }

    /**
     * @return \FormObject\FieldList
     **/
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
             "<a href=\"$forgotPasswordUrl\">$forgotPassword</a>"
            )
        );

        return $fields;
    }

    /**
     * @return \FormObject\FieldList
     **/
    public function createActions()
    {
        return parent::createActionList('login');
    }
}