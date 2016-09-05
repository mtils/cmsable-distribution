<?php


namespace Ems\App\Contracts\Mail;

interface SystemMailConfigRepository
{

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
