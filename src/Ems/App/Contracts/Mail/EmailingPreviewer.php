<?php


namespace Ems\App\Contracts\Mail;

use DateTime;
use Ems\Contracts\Mail\MailConfig;
use Ems\Contracts\Mail\Message;

/**
 * An EmailingPreviewer allows previews of mails before
 * they will be sent
 **/
interface EmailingPreviewer
{

    /**
     * Return if this previewer can preview messages of $config
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @return bool
     **/
    public function canPreview(MailConfig $config);

    /**
     * Return a search of reciepients to preview this mail. Remember that
     * there should be a search result so if the mail sent by this config
     * normally is sent to distinct people which are not always available
     * the user looking at the preview wants to also see a search result
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @param \DateTime $plannedSendDate
     * @return \Versatile\Search\Contracts\Search
     **/
    public function recipientSearch(MailConfig $config, DateTime $plannedSendDate=null);

    /**
     * Return a message to preview the one that will be sent to $reciepient
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @param mixed $recipient
     * @param array $data
     * @param \DateTime $plannedSendDate
     * @return \Ems\Contracts\Mail\Message
     **/
    public function previewMessage(MailConfig $config, $recipient, array $data=[], DateTime $plannedSendDate=null);

}
