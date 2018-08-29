<?php
/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 06.10.14
 * Time: 20:21
 */
return array(
    'title' => 'Hilfe',
    'choose' => 'Wähle Dein Hilfe-Thema',
    'login' => 'Anmeldung',
    'prob_login' => 'Probleme bei der Anmeldung?',
    'reservation' => array(
    ),
    'home' => array(
        'Diese Webseite dient der Planung & Verrechnung von Ferien für Familien-Gemeinschaften.',
        'Es gibt eine bestimmte Anzahl Betten, einen Belegungs-Turnus.',
        'Sobald Du Dein Profil vervollstänigt hast, kanns Du loslegen!',
    ),
    'login_text' => array(
        'Gib Deinen Benutzername (vorname.nachname) oder Deine E-Mail ein.',
        'Gib Dein Passwort ein.',
        'Falls Du angemeldet bleiben willst, check das Häkchen bei \'Angemeldet bleiben\'.',
        'Solange Du Dich nicht explizit abmeldest, musst Du Dich beim nächsten Besuch nicht mehr anmelden.',
        'Klicke auf \'Anmelden\''
    ),
    'login_prob' => array(
        'Die Seite ist in der Testphase.',
        'Somit ändert sich immer wieder was.',
        'Leere erst mal deinen Browsercache',
        'FireFox :',
        'https://support.mozilla.org/de/kb/Wie-Sie-den-Cache-leeren-konnen',
        'Chrome: ',
        'https://support.google.com/chrome/answer/95582?hl=de',
        'Safari:',
        'http://de.kioskea.net/faq/1512-safari-cache-leeren-und-cookies-loschen',
        '2. Gehe auf https://palazzin.ch',
        '3. Klicke auf "Passwort vergessen"',
        '4. Gib deine email und ein neues passwort ein',
        'das sollte helfen...',
    ),
    'too-login_text' => array(
        'Schreibe eine E-Mail an <a href="mailto:' . Constants::webMasterMail . '?subject=' . trans('errors.title-too-much-logins') . '&body=' . trans('address.dear', ['f' => 'r']) . ' ' . Constants::webMasterName . '">' . Constants::webMasterMail . '</a>.',
        'Gib Deinen Benutzernamen an (vorname.nachname).',
        'Gib die obenstehende Fehlermeldung an, falls vorhanden',
        'Warte auf Hilfe (24h)',
    ),
    'other_topics' => 'Andere Hilfethemen',
    'no_help' => 'Zu diesem Thema wurde noch keine Hilfe geschrieben',
);
