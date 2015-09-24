<?php

namespace Ems\App\Http\Forms;

use Config;
use FormObject\Form;
use Ems\App\Contracts\Messaging\RecipientsProvider as Recipients;
use Collection\Map\Extractor;

class InquiryForm extends Form
{

    protected $topicRecipients = [];

    protected $msgContext = Recipients::INQUIRY;

    /**
     * @var \Ems\App\Contracts\Messaging\RecipientsProvider
     **/
    protected $recipients;

    public function __construct(Recipients $recipients)
    {
        $this->recipients = $recipients;
    }

    public function createFields()
    {

        $fields = parent::createFields();

        $fields->push(
            Form::text('name')
        )->push(
            Form::text('email')
        )->push(
            $this->getTopicField()
        )->push(
            Form::text('message')->setMultiLine(true)
        );

        return $fields;
    }

    public function createActions()
    {
        return $this->createActionList('submit');
    }

    public function setModel($model)
    {
        $this->fillByArray($model);
    }

    public function setRecipients(Recipients $recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    protected function getTopicField()
    {

        $topics = $this->recipients->getTopics($this->msgContext);

        if (count($topics) == 1) {
            return Form::hidden('topic')->setValue(array_values($topics)[0]->getId());
        }

        $extractor = new Extractor('getId()', 'getName()');

        return Form::selectOne('topic')->setSrc($topics, $extractor);

    }

}