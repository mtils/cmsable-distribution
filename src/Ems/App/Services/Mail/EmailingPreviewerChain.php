<?php


namespace Ems\App\Services\Mail;


use Ems\App\Contracts\Mail\EmailingPreviewer;
use DateTime;
use Ems\Contracts\Mail\MailConfig;
use Ems\Contracts\Mail\Message;
use Ems\Core\Patterns\TraitOfResponsibility;

class EmailingPreviewerChain implements EmailingPreviewer
{

    use TraitOfResponsibility;

    /**
     * {@inheritdoc}
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @return bool
     **/
    public function canPreview(MailConfig $config)
    {
        foreach ($this->candidates as $previewer) {
            if ($previewer->canPreview($config)) {
                return true;
            }
        }

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
        return $this->findReturningTrueOrFail('canPreview', $config)
                    ->recipientSearch($config);
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
        return $this->findReturningTrueOrFail('canPreview', $config)
                    ->previewMessage($config, $recipient, $data, $plannedSendDate);
    }

}
