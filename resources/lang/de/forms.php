<?php

return [

    'base' => [
        'field_confirmed'   => ':attribute wiederholen',
    ],

    'actions' => [
        'send'              => ['title'=>'Absenden'],
        'change'            => ['title'=>'Ändern'],
        'save'              => ['title'=>'Speichern'],
        'create'            => ['title'=>'Anlegen'],
        'submit'            => ['title'=>'Absenden']
    ],

    'user' => [
        'main'        => ['title'       => 'Allgemeines'],
        'email'       => ['title'       => 'E-Mail'],
        'groups__ids' => ['title'       => 'Zugewiesene Benutzer-Gruppen'],
    ],

    'password-email' => [
        'email'          => ['title'=>'E-Mail']
    ],

    'password-reset' => [
        'password'          => ['title' => 'Neues Passwort']
    ],

    'password-reset-plugin' => [
        'resetmail'                     => 'E-Mail',
        'resetmail_subject'             => 'Betreff',
        'resetmail_body'                => 'Text der Nachricht',
        'resetmail_body_description'    => 'Die Nachricht muss {link} enthalten, um den Link für das Zurücksetzen des Passworts hineinzusetzen.',
        'resetpage'                     => 'Zurücksetz-Seite',
        'resetpage_title'               => 'Titel',
        'resetpage_content'             => 'Inhalt',
        'resetpage_description'         => 'Dies ist die Seite auf welche der Benutzer zum Ändern seines Passworts geleitet wird',
    ],

    'page' => [
        'resetmail' => ['title' => 'E-Mail'],
        'ctlsettings__resetmail_subject' => ['title' => '']
    ]

];