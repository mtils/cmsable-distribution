<?php


namespace Ems\App\Contracts\Mail;

interface SystemMailConfigRepository
{

    /**
     * Find a mail config for $resourceName. If an id is passed find one
     * which was explicitly saved for this $resourceName and the passed id
     * If no one with the id was found, return the config which was not
     * explicitly set for this id
     *
     * @param string $resourceName
     * @param int $id (optional)
     **/
    public function configForResource($resourceName, $id=null);

    /**
     * Find config for $resourceName. If non is found, create one with the
     * attributes under $attributes.
     * Optional pass an id to store different config objects for different ids
     *
     * possible array values:
     * [
     *    'parent'      => MailConfig,
     *    'template'    => 'emails.warnings.quota-exceed',
     *    'sender'      => 'quotarobot@youdomain.de',
     *    'content'    => [
     *        'subject' => 'You quota seems to exceed',
     *        'body'    => 'Please delete your files'
     *    ]
     * ]
     *
     * @param string $resourceName
     * @param array $attributes
     * @param int $id (optional)
     **/
    public function findOrCreate($resourceName, array $attributes, $id=null);
}