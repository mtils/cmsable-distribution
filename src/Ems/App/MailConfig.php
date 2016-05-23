<?php


namespace Ems\App;

use BeeTree\Eloquent\BeeTreeNode;
use BeeTree\Eloquent\AdjacencyList\WholeOrderedTreeModel;
use Ems\Contracts\Mail\MailConfig as ConfigContract;
use Ems\Contracts\Mail\MailConfigProvider;
use Permit\Permission\PermissionableInterface;

class MailConfig extends WholeOrderedTreeModel implements ConfigContract, MailConfigProvider, PermissionableInterface
{

    public static $systemUserId = 2;

    protected $table = 'mail_configurations';

    protected $guarded = ['id','parent_id'];

    protected $wholeTreeColumns = [
        'id',
        'resource_name',
        'name',
        'parent_id',
        'owner_id',
        'recipient_list_id',
        'template',
        'sender',
        'schedule_id',
        'created_at',
        'updated_at'
    ];

    /**
     * {@inheritdoc}
     *
     * @return mixed (int|string)
     *
     * @see \Ems\Contracts\Core\Identifiable
     **/
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     *
     * @see \Ems\Contracts\Core\Named
     **/
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     *
     * @see \Ems\Contracts\Core\AppliesToResource
     **/
    public function resourceName()
    {
        return $this->resource_name;
    }

    /**
     * {@inheritdoc}
     *
     * @return \Ems\Contracts\Mail\MailConfig
     **/
    public function parent()
    {
        return $this->parentNode();
    }

    /**
     * {@inheritdoc}
     *
     * @return \Traversable|array[\Ems\Contracts\Mail\MailConfig]
     **\
    public function children()
    {
        return $this->childNodes();
    }

    /**
     * {@inheritdoc}
     *
     * @return \Ems\Contracts\Mail\RecipientList
     **/
    public function recipientList()
    {
        throw new \RuntimeException('Not implemented right now');
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     **/
    public function template()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     **/
    public function data()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     **/
    public function sender()
    {
        return $this->sender;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     **/
    public function areOccurrencesGenerated()
    {
        return false;
    }

    /**
     * @param bool $enabled (optional)
     * @return self
     **/
    public function enableGeneratedOccurences($enabled=true)
    {
        throw new \RuntimeException('Not implemented right now');
    }

    public function schedule()
    {
        throw new \RuntimeException('Not implemented right now');
    }

    /**
     * {@inheritdoc}
     *
     * @param string $resourceName
     * @param \DateTime $plannedSendDate (optional)
     * @return \Ems\Contracts\Mail\MailConfig
     **/
    public function configFor($resourceName, DateTime $plannedSendDate=null)
    {
        return static::where('resource_name', $resourceName)->first();
    }

    public function getDataAttribute()
    {
        if(!isset($this->attributes['data']) && $this->exists){
            $this->attributes['data'] = static::where(
                $this->getKeyName(),$this->__get($this->getKeyName())
                )->pluck('data');
        }
        return parent::getAttributeFromArray('data');
    }

    public function requiredPermissionCodes($context = self::ACCESS)
    {
        if ($this->owner_id == static::$systemUserId && in_array($context, [self::ALTER, self::DESTROY])) {
            return ['system'];
        }
        return ['cms.access'];
    }

    protected function wholeTreeColumns()
    {
        return $this->wholeTreeColumns;
    }

}