<?php


namespace Ems\App\Services\Mail;


use Ems\App\Contracts\Mail\EmailingPreviewer;
use DateTime;
use Ems\Contracts\Mail\MailConfig;
use Ems\Contracts\Mail\Message;
use Ems\Core\Patterns\TraitOfResponsibility;
use Illuminate\Database\Eloquent\Model;
use Versatile\Search\Contracts\Search as UserSearch;
use Ems\Contracts\Mail\Mailer;
use Ems\Contracts\Mail\MessageComposer;

/**
 * This class is a template and its depedencies will be filled by the container
 * to allow your own constructor (and dependencies)
 **/
abstract class AbstractEmailingPreviewer implements EmailingPreviewer
{

    /**
     * @var \Versatile\Search\Contracts\Search
     **/
    protected $userSearch;

    /**
     * @var Ems\Contracts\Mail\Mailer
     **/
    protected $mailer;

    /**
     * @var Ems\Contracts\Mail\MessageComposer
     **/
    protected $composer;


    /**
     * {@inheritdoc}
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @return bool
     **/
    public function canPreview(MailConfig $config)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @param \DateTime $plannedSendDate
     * @return \Versatile\Search\Contracts\Search
     **/
    public function recipientSearch(MailConfig $config, DateTime $plannedSendDate=null)
    {
        return $this->userSearch;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @param mixed $recipient
     * @param array $data
     * @param \DateTime $plannedSendDate
     * @return \Ems\Contracts\Mail\Message
     **/
    public function previewMessage(MailConfig $config, $recipient, array $data=[], DateTime $plannedSendDate=null)
    {
        $message = $this->newMessage($recipient);
        $data = array_merge($data, $this->additionalData($config, $recipient, $plannedSendDate));
        $this->composer->fill($config, $message, $data, $plannedSendDate);
        return $message;
    }

    public function setUserSearch(UserSearch $userSearch)
    {
        $this->userSearch = $userSearch;
        return $this;
    }

    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
        return $this;
    }

    public function setComposer(MessageComposer $composer)
    {
        $this->composer = $composer;
        return $this;
    }

    protected function additionalData(MailConfig $config, $recipient, DateTime $plannedSendDate=null)
    {
        return [];
    }

    protected function newMessage($recipient)
    {
        return $this->mailer->message()->setRecipient($recipient);
    }

}
