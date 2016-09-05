<?php


namespace Ems\App\Repositories;

use DateTime;
use Cmsable\Resource\BeeTreeRepository;
use Ems\App\Contracts\Mail\SystemMailConfigRepository;
use Ems\Contracts\Mail\MailConfigProvider;
use Ems\Core\ResourceNotFoundException;
use Ems\App\MailConfig;
use Ems\App\MailContent;
use Permit\CurrentUser\ContainerInterface as Auth;


class MailConfigRepository extends BeeTreeRepository implements SystemMailConfigRepository, MailConfigProvider
{

    /**
     * @var callable
     **/
    protected $systemUserIdProvider;

    /**
     * @var int
     **/
    protected $systemUserId;

    /**
     * @var \Permit\CurrentUser\ContainerInterface
     **/
    protected $auth;

    protected $filterAttributes = true;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function getModel()
    {
        return new MailConfig;
    }

    public function resourceName()
    {
        return 'mail-configurations';
    }

    public function find($id)
    {

        $id = (int)$id;

        if ($config = parent::find($id)) {
            return $config;
        }

        if (!in_array($id, [MailConfig::SYSTEM, MailConfig::NEWSLETTER, MailConfig::USER])) {
            return;
        }

        $configs = $this->createDefaultConfigurations();

        if (isset($configs[$id])) {
            return $configs[$id];
        }

        throw new \RuntimeException("Config with id $id not found even afer trying to create default configurations");
    }

    public function storeAsChildOf(array $attributes, $parent, $position=null)
    {

        $model = parent::storeAsChildOf($attributes, $parent, $position);

        $this->syncRelated($model, $attributes);

        return $model;
    }

    public function update($model, array $attributes=[])
    {
        $model = parent::update($model, $attributes);
        $this->syncRelated($model, $attributes);
        return $model;
    }

    /**
     * Set a callable which will return your database system user id
     *
     * @param callable $provider
     * @return self
     **/
    public function provideSystemUserId(callable $provider)
    {
        $this->systemUserIdProvider = $provider;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $resourceName
     * @param mixed $resourceId (optional)
     * @return \Ems\Contracts\Mail\MailConfig
     **/
    public function configFor($resourceName, $resourceId=null)
    {
        $all = $this->model()->newQuery()
                    ->where('resource_name', $resourceName)
                    ->where('root_id', MailConfig::SYSTEM)
                    ->get();

        $nullHit = null;
        $idHit = null;

        foreach ($all as $config) {
            if ($config) {
                if ($config->foreign_id == $resourceId) {
                    $idHit = $config;
                }
                if (!$config->foreign_id) {
                    $nullHit = $config;
                }
            }
        }

        $result = $idHit ? $idHit : $nullHit;

        if ($result) {
            return $result;
        }

        throw new ResourceNotFoundException("No config found for '$resourceName' and id '$resourceId'");
    }

    /**
     * {@inheritdoc}
     *
     * @param string $resourceName
     * @param array $attributes
     * @param int $id (optional)
     **/
    public function findOrCreate($resourceName, array $attributes, $id=null)
    {
        if ($config = $this->configFor($resourceName, $id)) {
            return $config;
        }

        $this->model()->unguard(true);

        $parent = isset($attributes['parent']) ? $attributes['parent'] : $this->find(MailConfig::SYSTEM);
        $attributes = $this->passedToCreateAttributes($resourceName, $attributes, $id);

        $config = $this->storeAsChildOf($attributes, $parent);

        $this->model()->reguard();
        return $config;
    }

    protected function passedToCreateAttributes($resourceName, array $passedAttributes, $id)
    {

        $createAttributes = [
            'root_id' => MailConfig::SYSTEM,
            'owner_id' => $this->getSystemUserId(),
            'resource_name' => $resourceName
        ];

        foreach(['template', 'sender', 'content', 'name'] as $key) {
            if (isset($passedAttributes[$key])) {
                $createAttributes[$key] = $passedAttributes[$key];
            }
        }

//         if (isset($passedAttributes['parent'])) {
//             $createAttributes['parent_id'] = $passedAttributes['parent']->getId();
//             return $createAttributes;
//         }

        return $createAttributes;

    }

    protected function syncRelated($model, array $attributes)
    {

        if (isset($attributes['content']) && is_array($attributes['content'])) {
            $this->syncContents($model, $attributes['content']);
        }
    }

    protected function syncContents($model, $attributes)
    {
        if (!$contents = MailContent::where('mail_configuration_id', $model->getId())->first()) {
            $contents = new MailContent(['mail_configuration_id'=>$model->getId()]);
        }
        $attributes['owner_id'] = $this->auth->user()->getAuthId();
        $contents->fill($attributes);
        $contents->save();
    }

    protected function createDefaultConfigurations()
    {

        $systemUserId = $this->getSystemUserId();

        $ids = [
            MailConfig::SYSTEM => 'System',
            MailConfig::NEWSLETTER => 'Newsletter',
            MailConfig::USER => 'Benutzer',
        ];

        $models = [];

        foreach ($ids as $id=>$name) {
            $models[$id] = $this->model()->forceCreate([
                'id'            => $id,
                'root_id'       => $id,
                'resource_name' => $this->resourceName(),
                'name' => $name,
                'owner_id' => $systemUserId,
                'template' => 'emails.default'
            ]);
        }

        return $models;
    }

    protected function getSystemUserId()
    {

        if ($this->systemUserId) {
            return $this->systemUserId;
        }

        if (!$this->systemUserIdProvider) {
            throw new \RuntimeException('Assign a system user id provider to create default mail categories');
        }

        $this->systemUserId = call_user_func($this->systemUserIdProvider);

        return $this->systemUserId;
    }

}
