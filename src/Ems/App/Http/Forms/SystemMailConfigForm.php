<?php


namespace Ems\App\Http\Forms;

use FormObject\Form;
use FormObject\Field\Action;
use Ems\Contracts\Core\ResourceProvider as Templates;
use Ems\Contracts\Mail\MessageContentProvider as Contents;
use Ems\App\Contracts\Cms\VariableProvider;
use Ems\Contracts\Mail\MailConfig;

class SystemMailConfigForm extends Form
{

    /**
     * @var \Ems\Contracts\Core\ResourceProvider
     **/
    protected $templates;

    /**
     * @var \Ems\Contracts\Mail\MessageContentProvider
     **/
    protected $contents;

    /**
     * @var \Ems\App\Contracts\Cms\VariableProvider
     **/
    protected $variables;


    public function __construct(Templates $templates, Contents $contents, VariableProvider $variables)
    {
        $this->templates = $templates;
        $this->contents = $contents;
        $this->variables = $variables;
    }

    public function setModel($model)
    {
        parent::setModel($model);
        $this->fillByArray($model->toArray());

        if ($contents = $this->contents->contentsFor($model)) {
            $this->fillByArray($contents->toArray(), 'content');
        }

        return $this;
    }

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            Form::text('name'),
            Form::selectOne('template')->setSrc($this->templates->all())->by('getId()', 'getName()'),
            Form::text('content__subject'),
            Form::html('content__body')->setAttribute('data-variables', json_encode($this->getVariables()))
        );

        return $fields;

    }

    protected function getVariables()
    {
        if (!$this->model) {
            return [];
        }

        if (!$this->model instanceof MailConfig) {
            return [];
        }

        return $this->variables->variables($this->model->resourceName(), $this->model);
        return [
            ['name' => '{contact}', 'title' => 'Kontakt', 'children' => [
                ['name' => '{contact.id}', 'title'=>'ID'],
                ['name' => '{contact.forename}', 'title'=>'Vorname'],
                ['name' => '{contact.surname}', 'title' =>'Nachname'],
                ['name' => '{contact.address}', 'title' =>'Adresse', 'children'=> [
                    ['name' => '{contact.address.id}', 'title' => 'ID'],
                    ['name' => '{contact.address.street}', 'title' => 'Straße']
                ]]
            ]],
            ['name' => '{user}', 'title' => 'Benutzer', 'children' => [
                ['name' => '{user.email}', 'title'=> 'E-Mail']
            ]],
            ['name' => '{today}', 'title' => 'Heutiges Datum'],
            ['name' => '{now}', 'title' => 'Jetzige Uhrzeit']
        ];
    }

    public function createActions()
    {
        return parent::createActionList('save');
    }

}
