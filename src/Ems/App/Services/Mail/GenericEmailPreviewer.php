<?php


namespace Ems\App\Services\Mail;


use Ems\Contracts\Mail\MailConfig;

class GenericEmailPreviewer extends AbstractEmailingPreviewer
{

    /**
     * {@inheritdoc}
     *
     * @param \Ems\Contracts\Mail\MailConfig $config
     * @return bool
     **/
    public function canPreview(MailConfig $config)
    {
        return true;
    }

}
