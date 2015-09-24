<?php

namespace Ems\App\Services\Inquiry;

use Ems\App\Contracts\Messaging\RecipientsProvider;
use Ems\Core\NamedObject;
use Cmsable\PageType\ConfigRepositoryInterface as PageTypeConfigs;

class PageTypeConfigRecipientsProvider implements RecipientsProvider
{

    public $configKey = 'recipients';

    public $defaultTopicName = 'General';

    public $defaultRecipient = '';

    /**
     * @var \Cmsable\PageType\ConfigRepositoryInterface
     **/
    protected $pageTypeConfigs;

    protected $pageId;

    public $pageTypeId = 'cmsable.inquiry-page';

    protected $topicCache = [];

    protected $recipientCache = [];

    /**
     * @param \Cmsable\PageType\ConfigRepositoryInterface $pageTypeConfigs
     **/
    public function __construct(PageTypeConfigs $pageTypeConfigs)
    {
        $this->pageTypeConfigs = $pageTypeConfigs;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $topicId (optional)
     * @param string $context (optional)
     *
     * @return array
     **/
    public function recipientsFor($topicId = 1, $context=RecipientsProvider::ANY)
    {

        if (!$this->checkContext($context)) {
            return [];
        }

        $this->load();
        $pageId = $this->pageId ?: 0;

        if (!isset($this->recipientCache[$pageId])) {
            return [$this->defaultRecipient];
        }

        if (!isset($this->recipientCache[$pageId][$topicId])) {
            return [$this->defaultRecipient];
        }

        if (!$this->recipientCache[$pageId][$topicId]) {
            return [$this->defaultRecipient];
        }

        return [$this->recipientCache[$pageId][$topicId]];

    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $context A additional method to allow groups
     * @param string $context (optional)
     *
     * @return \Traversable
     **/
    public function getTopics($context = RecipientsProvider::ANY)
    {

        if (!$this->checkContext($context)) {
            return [];
        }

        $this->load();
        $pageId = $this->pageId ?: 0;

        if (!isset($this->topicCache[$pageId])) {
            return [$this->getDefaultTopic()];
        }

        if (!count($this->topicCache[$pageId])) {
            return [$this->getDefaultTopic()];
        }

        return $this->topicCache[$pageId];
    }

    /**
     * {@inheritdoc}
     *
     * @param int $topicId
     * @param string $context (optional)
     *
     * @return \Ems\Contracts\Core\Named
     **/
    public function getTopic($topicId = 1, $context = RecipientsProvider::ANY)
    {

        if (!$this->checkContext($context)) {
            return $this->getDefaultTopic();
        }

        $this->load();

        $pageId = $this->pageId ?: 0;

        if (!isset($this->topicCache[$pageId])) {
            return $this->getDefaultTopic();
        }

        if (!isset($this->topicCache[$pageId][$topicId])) {
            return $this->getDefaultTopic();
        }

        return $this->topicCache[$pageId][$topicId];

    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }

    protected function load()
    {

        $pageId = $this->pageId ?: 0;

        if (isset($this->topicCache[$pageId])) {
            return;
        }

        $pageId = $this->pageId ?: 0;

        $this->topicCache[$pageId] = [];
        $this->recipientCache[$pageId] = [];

        $config = $this->pageTypeConfigs->getConfig($this->pageTypeId, $this->pageId);

        if ($config['single_or_multiple'] == 'single') {
            $this->recipientCache[$pageId][1] = $config['single_recipient'];
            $this->topicCache[$pageId][1] = new NamedObject(1, $this->defaultTopicName);
            return;
        }

        foreach ($config[$this->configKey] as $id=>$data) {
            $this->recipientCache[$pageId][$id] = $data['recipient'];
            $this->topicCache[$pageId][$id] = new NamedObject($id, $data['topic']);
        }

    }

    protected function checkContext($context)
    {
        return ($context == RecipientsProvider::ANY || $context == RecipientsProvider::INQUIRY);
    }

    protected function getDefaultTopic()
    {
        return new NamedObject(1, $this->defaultTopicName);
    }

}
