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
    ],
    'inquiry' => [
        'recipients_tab'                => ['title'=>'Empfänger'],
        'send_to_single'                => ['title'=>'Alle Mails an einen Empfänger senden'],
        'send_to_multiple'              => ['title'=>'An verschiedene Empfänger senden'],
        'single_recipient'              => ['title'=>'Empfänger'],
        'topic'                         => ['title'=>'Thema'],
        'recipient'                     => ['title'=>'Empfänger Email-Adresse'],
    ],
    'testimonials-page' => [
        'filter_tags_ids' => ['title'=>'Referenzen mit diesen Stichwörtern anzeigen']
    ],
    'blog-page' => [
        'filter_tags_ids' => ['title'=>'Nur Blog Einträge mit diesen Stichwörtern anzeigen']
    ],
    'widget-anchor-plugin' => [
        'no-pages-with-areas'   => 'Keine Seiten mit Komponenten gefunden',
        'area-containing-widgets'   => 'Bereich welcher die Komponenten enthält'
    ]
];
