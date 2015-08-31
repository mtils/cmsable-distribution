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
    'users/activate' => [
        'activated'                 => 'Der Benutzer wurde aktiviert.',
        'not-found'                 => 'Der Benutzer wurde nicht gefunden.'
    ]
];