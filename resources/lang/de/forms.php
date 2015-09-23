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
        'submit'            => ['title'=>'Absenden'],
        'search'            => ['title'=>'Suchen'],
        'login'             => ['title'=>'Anmelden'],
    ],

    'user' => [
        'main'        => ['title'       => 'Allgemeines'],
        'email'       => ['title'       => 'E-Mail'],
        'groups__ids' => ['title'       => 'Zugewiesene Benutzer-Gruppen'],
    ],

    'user-search' => [
        'main'        => ['title'       => 'Allgemeines'],
        'email'       => ['title'       => 'E-Mail'],
        'groups__ids' => ['title'       => 'Benutzer-Gruppen'],
    ],

    'group' => [
        'main'        => ['title'       => 'Allgemeines'],
        'name'       => ['title'        => 'Name'],
        'permission__codes'       => ['title'        => 'Berechtigungen'],
    ],

    'password-email' => [
        'email'             => ['title'=>'E-Mail'],
        'fill-to-send-mail' => ['title'=>'Tragen Sie Ihre E-Mail Adresse ein um Ihr Passwort zurückzusetzen'],
        'jump-to-login'     => ['title'=>'Abbrechen']
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
    ],

    'login' => [
        'email' => ['title' => 'E-Mail'],
        'password' => ['title' => 'Passwort'],
        'forgot-password' => ['title' => 'Passwort vergessen?'],
        'remember-me' => ['title' => 'Angemeldet bleiben'],
        'register-new' => ['title' => 'Neues Konto registrieren'],
        'login-to-start' => ['title' => 'Melden Sie sich an um die Seite zu öffnen'],
    ],

    'inquiry' => [
        'name' => ['title' => 'Ihr Name'],
        'email' => ['title' => 'Ihre E-Mail Adresse'],
        'message' => ['title' => 'Nachricht']
    ]

];