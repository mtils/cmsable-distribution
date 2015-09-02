<?php

return [
    'password/send-email' => [
        'reset-sent'     => 'Vorgang erfolgreich. Dem Benutzer mit dieser E-Mail Adresse wurde eine Mail zugesendet.',
        'user-not-found' => 'Ein Benutzer mit dieser E-Mail Adresse wurde nicht gefunden.',
        'token-collision-forever'   => 'Für dieses Benutzerkonto können gerade keine Passwörter zurückgesetzt werden.',
        'token-collision-until'     => 'Fehler. Für dieses Konto kann am :valid_until erst wieder eine Passwort-Zurücksetzung veranlasst werden.',
    ],
    'password/store-reset' => [
        'password-changed'          => 'Ihr Passwort wurde geändert.',
        'user-not-found'            => 'Der Benutzer wurde nicht gefunden.',
        'token-expired'             => 'Der Link ist um :expired_at abglaufen.',
        'token-invalid'             => 'Der übergebene Zurücksetzungs-Schlüssel ist ungültig.',
    ],
    'users/update' => [
        'updated'                   => 'Der Benutzer wurde gespeichert.',
        'not-found'                 => 'Der Benutzer wurde nicht gefunden.'
    ],
    'users/store' => [
        'stored'                    => 'Der Benutzer wurde angelegt.'
    ],
    'users/activate' => [
        'activated'                 => 'Der Benutzer wurde aktiviert.',
        'not-found'                 => 'Der Benutzer wurde nicht gefunden.'
    ],
    'users/destroy' => [
        'destroyed'                 => 'Der Benutzer wurde gelöscht.',
        'not-found'                 => 'Der Benutzer wurde nicht gefunden.'
    ],
    'session/store' => [
        'credentials-invalid'       => 'Benutzername oder Passwort falsch',
        'credentials-not-found'     => 'Benutzername oder Passwort falsch',
        'user-not-activated'        => 'Der Benutzer wurde noch nicht aktiviert',
        'user-suspended'            => 'Der Benutzer wurde wegen zuvieler fehlgeschlagener Anmeldeversucht gesperrt',
        'user-banned'               => 'Das Benutzerkonto wurde deaktiviert',
    ],
    'session/destroy' => [
        'loggedout'                    => 'Sie wurden abgemeldet.',
        'loggedout-as'                 => 'Sie sind nun wieder als :email angemeldet.'
    ],
    'session/login-as' => [
        'loggedout'                    => 'Sie wurden abgemeldet.',
        'loggedin-as'                  => 'Sie sind nun als :email angemeldet.',
        'user-not-found'               => 'Der Benutzer wurde nicht gefunden',
        'not-authenticated'            => 'Sie müssen angemeldet sein um diese Funktion nutzen zu können',
        'login-as-same'                => 'Sie können sich nicht als sie selbst ausgeben',
        'login-as-admin'               => 'Es ist nicht möglich, sich als Administrator auszugeben',
        'login-as-system'              => 'Sie können sich nicht als System-Benutzer anmelden',
        'permission-denied'            => 'Sie haben nicht die erforderlichen Rechte um sich als jemand anders auszugeben',
        'less-permissions'             => 'Sie können sich nicht als ein Benutzer ausgeben, welcher ein Benutzerrecht hat dass Sie nicht haben',
    ],
];