<?php

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$mod_strings = array(
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Nadzorna ploča za popis bilješki',

    'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da biste izbrisali račun.',
    'LBL_ACCOUNT_ID' => 'ID računa:',
    'LBL_CASE_ID' => 'ID slučaja:',
    'LBL_CLOSE' => 'Zatvori:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID kontakta:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Bilješke',
    'LBL_DESCRIPTION' => 'Opis',
    'LBL_EMAIL_ADDRESS' => 'Adresa e-pošte:',
    'LBL_EMAIL_ATTACHMENT' => 'Prilog poruke e-pošte',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Prilog poruke e-pošte za',
    'LBL_FILE_MIME_TYPE' => 'Vrsta MIME-a',
    'LBL_FILE_EXTENSION' => 'Datotečni nastavak',
    'LBL_FILE_SOURCE' => 'Izvor datoteke',
    'LBL_FILE_SIZE' => 'Veličina datoteke',
    'LBL_FILE_URL' => 'URL datoteke',
    'LBL_FILENAME' => 'Prilog:',
    'LBL_LEAD_ID' => 'ID pot. klij.:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Posljednja izmjena',
    'LBL_LIST_FILENAME' => 'Prilog',
    'LBL_LIST_FORM_TITLE' => 'Popis bilješki',
    'LBL_LIST_RELATED_TO' => 'Povezano s',
    'LBL_LIST_SUBJECT' => 'Predmet',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Bilješke',
    'LBL_MODULE_NAME_SINGULAR' => 'Bilješka',
    'LBL_MODULE_TITLE' => 'Bilješke: početno',
    'LBL_NEW_FORM_TITLE' => 'Stvori bilješku ili dodaj prilog',
    'LBL_NEW_FORM_BTN' => 'Dodaj bilješku',
    'LBL_NOTE_STATUS' => 'Bilješka',
    'LBL_NOTE_SUBJECT' => 'Predmet:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Bilješke i prilozi',
    'LBL_NOTE' => 'Bilješka:',
    'LBL_OPPORTUNITY_ID' => 'ID prilike:',
    'LBL_PARENT_ID' => 'Nadređeni ID:',
    'LBL_PARENT_TYPE' => 'Nadređena vrsta',
    'LBL_EMAIL_TYPE' => 'Vrsta poruke e-pošte',
    'LBL_EMAIL_ID' => 'ID e-pošte',
    'LBL_PHONE' => 'Telefon:',
    'LBL_PORTAL_FLAG' => 'Prikazati u portalu?',
    'LBL_EMBED_FLAG' => 'Umetnuti u e-poštu?',
    'LBL_PRODUCT_ID' => 'ID prodane stavke:',
    'LBL_QUOTE_ID' => 'ID ponude:',
    'LBL_RELATED_TO' => 'Povezano s:',
    'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje bilješke',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Predmet:',
    'LNK_IMPORT_NOTES' => 'Uvezi bilješke',
    'LNK_NEW_NOTE' => 'Stvori bilješku ili prilog',
    'LNK_NOTE_LIST' => 'Prikaži bilješke',
    'LBL_MEMBER_OF' => 'Član:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodijeljeni korisnik',
    'LBL_OC_FILE_NOTICE' => 'Prijavite se na poslužitelj za prikaz datoteke',
    'LBL_REMOVING_ATTACHMENT' => 'Uklanjanje priloga...',
    'ERR_REMOVING_ATTACHMENT' => 'Uklanjanje priloga nije uspjelo...',
    'LBL_CREATED_BY' => 'Stvorio/la',
    'LBL_MODIFIED_BY' => 'Izmijenio/la',
    'LBL_SEND_ANYWAYS' => 'Jeste li sigurni da želite poslati/spremiti poruku e-pošte bez predmeta?',
    'LBL_LIST_EDIT_BUTTON' => 'Uredi',
    'LBL_ACTIVITIES_REPORTS' => 'Izvješće o aktivnostima',
    'LBL_PANEL_DETAILS' => 'Detalji',
    'LBL_NOTE_INFORMATION' => 'Pregled',
    'LBL_MY_NOTES_DASHLETNAME' => 'Moje bilješke',
    'LBL_EDITLAYOUT' => 'Uredi izgled' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Ime',
    'LBL_LAST_NAME' => 'Prezime',
    'LBL_EXPORT_PARENT_TYPE' => 'Povezano s modulom',
    'LBL_EXPORT_PARENT_ID' => 'Povezano s ID-om',
    'LBL_DATE_ENTERED' => 'Datum stvaranja',
    'LBL_DATE_MODIFIED' => 'Datum izmjene',
    'LBL_DELETED' => 'Izbrisano',
    'LBL_REVENUELINEITEMS' => 'Stavke prihoda',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} sastoji se od pojedinačnih modula {{plural_module_name}} koji sadrže tekst ili prilog koji je bitan za povezani zapis. Zapisi modula {{module_name}} mogu biti povezani s jednim zapisom u većini modula putem polja fleksibilnog povezivanja i također može biti povezan s jednim modulom {{contacts_singular_module}}. Modul {{plural_module_name}} može sadržavati općeniti tekst o zapisu ili čak i prilog povezan sa zapisom. Module {{plural_module_name}} u Sugaru možete stvoriti na različite načine, kao primjerice putem modula {{plural_module_name}}, uvozom modula {{plural_module_name}}, putem podploča za povijest itd. Nakon što stvorite zapis o modulu {{module_name}}, možete vidjeti i urediti informacije koje pripadaju modulu {{module_name}} putem prikaza zapisa o modulu {{plural_module_name}}. Svaki zapis o modulu {{module_name}} može biti povezan s drugim zapisima u Sugaru, poput modula {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} i brojnih drugih.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} sastoji se od pojedinačnih modula {{plural_module_name}} koji sadrže tekst ili priloge koji su bitni za povezani zapis.

- Uredite polja ovog zapisa tako da kliknete na pojedino polje ili gumb Uredi.
- Pogledajte ili izmijenite poveznice na ostale zapise u podskupinama tako da prebacite donje lijevo okno na „Prikaz podataka”.
- Objavljujte i pregledavajte komentare korisnika i bilježite povijest promjena u modulu {{activitystream_singular_module}} tako da prebacite donje lijevo okno na „Pregled aktivnosti”.
- Slijedite ili označite ovaj zapis kao omiljen s pomoću ikona koje se nalaze desno od naziva zapisa.
- Dodatne radnje dostupne su u padajućem izborniku Radnje koji se nalazi desno od gumba Uredi.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Za stvaranje modula {{module_name}}:
1. Unesite vrijednosti polja po želji.
 - Polja označena „Obavezno” moraju se ispuniti prije spremanja.
 - Kliknite na „Prikaži više” da biste otkrili dodatna polja ako je potrebno.
2. Kliknite na „Spremi” da biste završili novi zapis i vratili se na prethodnu stranicu.',
);
