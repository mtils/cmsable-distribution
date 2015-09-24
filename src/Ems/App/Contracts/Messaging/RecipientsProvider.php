<?php

namespace Ems\App\Contracts\Messaging;

interface RecipientsProvider
{

    const ANY = 'any';

    const LOG_MESSAGE = 'log';

    const SYSTEM_EVENT = 'event';

    const INQUIRY = 'inquiry';

    const APPOINTMENT = 'appointment';

    /**
     * Return an array of email addresses or strings suitable for an email to
     * header for $topicId.
     *
     * @param mixed $topicId (optional)
     * @param string $context (optional)
     *
     * @return array
     **/
    public function recipientsFor($topicId = 1, $context=self::ANY);

    /**
     * Return all available topics.
     *
     * @param mixed $context A additional method to allow groups
     * @param string $context (optional)
     *
     * @return \Traversable
     **/
    public function getTopics($context = self::ANY);

    /**
     * Get the topic for $topicId or the default topic.
     *
     * @param int $topicId
     * @param string $context (optional)
     *
     * @return \Ems\Contracts\Core\Named
     **/
    public function getTopic($topicId = 1, $context = self::ANY);
}
