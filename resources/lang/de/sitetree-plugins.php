<?php

return [

    'password-reset' => [
        'resetmail'                     => ['title'=>'E-Mail'],
        'resetmail_subject'             => ['title'=>'Betreff'],
        'resetmail_body'                => ['title'=>'Text der Nachricht',
                                            'description'=>'Die Nachricht muss {link} enthalten, um den Link für das Zurücksetzen des Passworts hineinzusetzen.'],
        'resetpage'                     => ['title'=>'Zurücksetz-Seite'],
        'resetpage_title'               => ['title'=>'Titel'],
        'resetpage_content'             => ['title'=>'Inhalt',
                                            'description'=>'Dies ist die Seite auf welche der Benutzer zum Ändern seines Passworts geleitet wird'],
    ]

];