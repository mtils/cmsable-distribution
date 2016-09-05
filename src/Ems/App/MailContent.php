<?php


namespace Ems\App;

use Illuminate\Database\Eloquent\Model;
use Ems\Contracts\Mail\MailConfig as ConfigContract;
use Permit\Permission\PermissionableInterface;
use Ems\Contracts\Mail\MessageContent;

class MailContent extends Model
{

    public static $systemUserId = 2;

    protected $table = 'mail_contents';

    protected $guarded = ['id'];

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
     * @see 
     **/
    public function subject()
    {
        return $this->getAttribute('subject');
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     **/
    public function body()
    {
        return $this->getAttribute('body');
    }

    /**
     * Returns the configuration which will build the message
     *
     * @return \Ems\Contracts\Mail\MailConfig
     **/
    public function config()
    {
        return $this->belongsTo('Ems\App\MailConfig');
    }

    /**
     * Return the person which wrote the content
     *
     * @return mixed
     **/
    public function originator()
    {
        return $this->owner;
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
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

    public function requiredPermissionCodes($context = self::ACCESS)
    {
        if ($this->owner_id == static::$systemUserId && in_array($context, [self::ALTER, self::DESTROY])) {
            return ['system'];
        }
        return ['cms.access'];
    }

}
