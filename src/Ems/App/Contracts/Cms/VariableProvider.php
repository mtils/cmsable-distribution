<?php


namespace Ems\App\Contracts\Cms;


/**
 * A VariableProvider is used to show a user a tree of variables which she can
 * add to a text.
 *
 **/
interface VariableProvider
{
    /**
     * Return an array of variables in the following format:
     *
     * [
     *     ['name' => '{contact}', 'title' => 'The Contact', 'children' => [
     *          ['name' => '{contact.id}', 'title'=>'ID'],
     *          ['name' => '{contact.forename}', 'title'=>'First Name'],
     *          ['name' => '{contact.surname}', 'title' =>'Last Name'],
     *          ['name' => '{contact.address}', 'title' =>'Address', 'children'=> [
     *              ['name' => '{contact.address.id}', 'title' => 'ID'],
     *              ['name' => '{contact.address.street}', 'title' => 'Street']
     *          ]]
     *      ]],
     *      ['name' => '{user}', 'title' => 'User', 'children' => [
     *          ['name' => '{user.email}', 'title'=> 'Email Address']
     *      ]],
     *      ['name' => '{today}', 'title' => 'Todays date'],
     *      ['name' => '{now}', 'title' => 'The curren time']
     *  ]
     *
     * Resource is the currently edited object. For example the page that is
     * currently edited.
     *
     * @param string $resourceName
     * @param object $resource (optional)
     * @return array
     **/
    public function variables($resourceName, $resource=null);
}
