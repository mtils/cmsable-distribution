<?php


namespace Ems\App\Services\Mail;

use Ems\Contracts\Mail\MailConfig;
use Ems\Contracts\Mail\MessageComposer;
use Ems\App\Cms\AbstractVariableProvider;

/**
 * This class provides all variables the MessageComposer assigns
 *
 **/
class ComposerVariableProvider extends AbstractVariableProvider
{

    /**
     * {@inheritdoc}
     *
     * @param string $resourceName
     * @param object $resource (optional)
     * @return array
     **/
    public function variables($resourceName, $resource = null)
    {
        if (!$resource instanceof MailConfig) {
            return [];
        }

        $recipientKey = MessageComposer::RECIPIENT;

        $recipient = [
            'name'  => "{$recipientKey}",
            'title' => $this->texts->get('ems::mail.base.recipient'),
            'children' => $this->collectVariables($this->modelKeys('App\User'), $recipientKey, 'App\User')
        ];

        return [
            $recipient,
            ['name' => '{' . MessageComposer::ORIGINATOR . '}', 'title' => $this->texts->get('ems::mail.base.originator')]
        ];

    }

}
