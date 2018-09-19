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
    'LBL_NOTES_LIST_DASHBOARD' => 'Kontrolna tabla liste napomena',

    'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da bi obrisali kompaniju.',
    'LBL_ACCOUNT_ID' => 'ID broj kompanije:',
    'LBL_CASE_ID' => 'ID broj slučaja:',
    'LBL_CLOSE' => 'Zatvori:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID broj kontakta:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Beleške',
    'LBL_DESCRIPTION' => 'Beleška',
    'LBL_EMAIL_ADDRESS' => 'Email adresa:',
    'LBL_EMAIL_ATTACHMENT' => 'Email prilog',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Prilog e-poruke za',
    'LBL_FILE_MIME_TYPE' => 'MIME tip',
    'LBL_FILE_EXTENSION' => 'Oznaka tipa datoteke',
    'LBL_FILE_SOURCE' => 'Izvor datoteke',
    'LBL_FILE_SIZE' => 'Veličina datoteke',
    'LBL_FILE_URL' => 'URL fajla',
    'LBL_FILENAME' => 'Prilog:',
    'LBL_LEAD_ID' => 'ID broj potencijalnog klijenta:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Poslednja izmena',
    'LBL_LIST_FILENAME' => 'Prilog',
    'LBL_LIST_FORM_TITLE' => 'Lista beležaka',
    'LBL_LIST_RELATED_TO' => 'Povezano sa',
    'LBL_LIST_SUBJECT' => 'Naslov',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Beleške',
    'LBL_MODULE_NAME_SINGULAR' => 'Beleška',
    'LBL_MODULE_TITLE' => 'Beleške: Početna strana',
    'LBL_NEW_FORM_TITLE' => 'Kreiraj belešku ili dodaj prilog',
    'LBL_NEW_FORM_BTN' => 'Dodaj zabelešku',
    'LBL_NOTE_STATUS' => 'Beleška',
    'LBL_NOTE_SUBJECT' => 'Naslov:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Prilozi',
    'LBL_NOTE' => 'Beleška:',
    'LBL_OPPORTUNITY_ID' => 'ID broj prodajne prilike:',
    'LBL_PARENT_ID' => 'Matični ID broj:',
    'LBL_PARENT_TYPE' => 'Matični tip',
    'LBL_EMAIL_TYPE' => 'Tip e-poruke',
    'LBL_EMAIL_ID' => 'ID e-poruke',
    'LBL_PHONE' => 'Telefon:',
    'LBL_PORTAL_FLAG' => 'Da li da prikažem u portalu?',
    'LBL_EMBED_FLAG' => 'Ugradi u email?',
    'LBL_PRODUCT_ID' => 'ID broj proizvoda:',
    'LBL_QUOTE_ID' => 'ID broj ponude:',
    'LBL_RELATED_TO' => 'Povezano sa:',
    'LBL_SEARCH_FORM_TITLE' => 'Pretraga beležaka',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Naslov:',
    'LNK_IMPORT_NOTES' => 'Uvezi beleške',
    'LNK_NEW_NOTE' => 'Kreiraj belešku ili prilog',
    'LNK_NOTE_LIST' => 'Pregledaj beleške',
    'LBL_MEMBER_OF' => 'Član:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodeljeni korisnik',
    'LBL_OC_FILE_NOTICE' => 'Molim, prijavite se na server da bi pogledali ovaj fajl',
    'LBL_REMOVING_ATTACHMENT' => 'Uklanjam prilog...',
    'ERR_REMOVING_ATTACHMENT' => 'Neuspešno uklanjanje priloga...',
    'LBL_CREATED_BY' => 'Autor',
    'LBL_MODIFIED_BY' => 'Promenio',
    'LBL_SEND_ANYWAYS' => 'Ovaj email je bez naslova. Da li da ga ipak pošaljem/sačuvam?',
    'LBL_LIST_EDIT_BUTTON' => 'Izmeni',
    'LBL_ACTIVITIES_REPORTS' => 'Izveštaj o Aktivnostima',
    'LBL_PANEL_DETAILS' => 'Detalji',
    'LBL_NOTE_INFORMATION' => 'Pregled beleške',
    'LBL_MY_NOTES_DASHLETNAME' => 'Moje beleške',
    'LBL_EDITLAYOUT' => 'Izmeni raspored' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Ime',
    'LBL_LAST_NAME' => 'Prezime',
    'LBL_EXPORT_PARENT_TYPE' => 'Povezano sa Modulom',
    'LBL_EXPORT_PARENT_ID' => 'Povezano sa ID-jem',
    'LBL_DATE_ENTERED' => 'Datum kreiranja',
    'LBL_DATE_MODIFIED' => 'Datum izmene',
    'LBL_DELETED' => 'Obrisan',
    'LBL_REVENUELINEITEMS' => 'Stavke prihoda',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '•	{{plural_module_name}} modul se sastoji od individualnih {{plural_module_name}} koji sadrže tekst ili prilog koji se tiču povezanog zapisa.  {{module_name}} zapis može biti povezan sa jednim zapisom u većini modula pomoću flex povezanog polja i takođe može biti povezan sa jednim {{contacts_singular_module}}. {{plural_module_name}}  može da sadrži generčki tekst o zapisu ili o prilogu vezanim za zapis. Postoji nekoliko načina da se kreira {{plural_module_name}} u Sugar-u kao što su preko {{plural_module_name}} modula, uvozom {{plural_module_name}}, preko podokvira Istorija itd.  Jednom kada je {{module_name}} kreiran, moguće je pregledati i izmeniti informacije koji se tiču {{module_name}} kroz {{module_name}}-og pregleda zapisa. Svaki {{module_name}} zapis može biti uvezan sa drugim Sugar-ovim zapisima kao što su  {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, i mnogi drugi.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} se sastoji od pojedinačne {{plural_module_name}} koja sadrži tekst ili prilog koji je relevantan za određeni dokument.

- Ova polja sa zapisima menjajte tako što ćete kliknuti na pojedinačno polje ili na dugme Izmena.
- Pogledajte ili modifikujte linkove ka drugim zapisima u podpanelima tako što ćete prebaciti donji levi pano na „Prikaz podataka“.
- Napravite i pogledajte komentare korisnika i zabeležite promenu istorije u {{activitystream_singular_module}} tako što ćete prebaciti donji levi panel na „Tok aktivnosti“.
- Pratite ili učinite omiljenim ovaj zapis pomoću ikona sa desne strane imena zapisa.
- Dodatne radnje su dostupne u padajućem meniju Akcije koji se nalazi sa desne strane dugmeta Izmena.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Da bi {{module_name}} bio kreiran: 
1. Obezbediti vrednost za polja po želji.
- Polja označena kao "Obavezna" moraju biti uneta pre čuvanja podataka. 
- Kliknite na "Pokaži više" za prikaz dodatnih polja ako je potrebno. 
2. Kliknite na "Sačuvaj" da dovršimo novi zapis i povratak na predhodnu stranu.',
);
