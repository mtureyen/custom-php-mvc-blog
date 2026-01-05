<?php
return [
    // Navigation & Allgemein
    'welcome' => 'Hallo',
    'nav_login' => 'Einloggen',
    'nav_register' => 'Registrieren',
    'nav_logout' => 'Logout',
    'nav_create' => 'Neuen Beitrag schreiben',
    'back_home' => 'Zurück zur Startseite',

    // Startseite (Home)
    'latest_posts' => 'Neueste Beiträge',
    'no_posts' => 'Noch keine Beiträge vorhanden.',
    'from' => 'Von',
    'at' => 'am',
    'read_more' => 'Weiterlesen →',
    'alt_image' => 'Bild zum Beitrag',

    // Beitrag erstellen (Create)
    'create_heading' => 'Neuen Beitrag schreiben',
    'label_title' => 'Titel:',
    'ph_title' => 'Titel eingeben...',
    'label_content' => 'Inhalt:',
    'ph_content' => 'Schreib was...',
    'label_image' => 'Bild hochladen (optional, max. 5 MB):',
    'btn_publish' => 'Veröffentlichen',

    // Detail Seite
    'back_overview' => 'Zurück zur Übersicht',
    'written_by' => 'Geschrieben von',
    'comments_headline' => 'Kommentare',
    'label_your_comment' => 'Dein Kommentar:',
    'btn_submit_comment' => 'Kommentieren',
    'msg_login_to_comment' => 'Bitte <a href="/login">einloggen</a>, um zu kommentieren.',
    'msg_no_comments' => 'Noch keine Kommentare. Sei der Erste!',
    'placeholder_no_image' => 'Kein Bild',

    // Login Seite
    'heading_login' => 'Einloggen',
    'label_username' => 'Benutzername:',
    'label_password' => 'Passwort:',
    'btn_login' => 'Login',
    'text_no_account' => 'Noch kein Account?',
    'link_register_here' => 'Hier registrieren',

    // Register Seite
    'heading_register' => 'Account erstellen',
    'label_password_repeat' => 'Passwort wiederholen:',
    'btn_register' => 'Registrieren',
    'text_have_account' => 'Schon einen Account?',
    'link_login_here' => 'Hier einloggen',
    'link_cancel_home' => 'Abbrechen und zurück zur Startseite',

    // PostController Error Handler
    'err_img_too_big' => 'Das Bild ist zu groß! Bitte maximal 5 MB hochladen.',
    'err_img_save' => 'Fehler beim Speichern des Bildes.',
    'err_img_type' => 'Nur JPG, PNG, GIF oder WEBP erlaubt!',
    'err_server_limit' => 'Die Datei ist viel zu groß (Server-Limit überschritten).',
    'err_fill_fields' => 'Bitte Titel und Inhalt ausfüllen!',

    // AuthController Error Handler
    'err_login_failed' => 'Falscher Benutzername oder Passwort!',
    'err_user_taken' => 'Der Benutzername ist bereits vergeben!',
    'err_no_spaces' => 'Der Benutzername darf keine Leerzeichen enthalten!',
    'err_username_chars' => 'Der Benutzername darf nur Buchstaben, Zahlen und Unterstriche enthalten!',
    'err_username_length' => 'Der Benutzername muss zwischen 3 und 18 Zeichen lang sein!',
    'err_pw_too_short' => 'Das Passwort muss mindestens 8 Zeichen lang sein!',
    'err_pw_mismatch' => 'Die Passwörter stimmen nicht überein!',
];