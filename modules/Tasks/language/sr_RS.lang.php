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

$mod_strings = array (
  // Dashboard Names
  'LBL_TASKS_LIST_DASHBOARD' => 'Kontrolna tabla liste zadataka',

  'LBL_MODULE_NAME' => 'Zadaci',
  'LBL_MODULE_NAME_SINGULAR' => 'Zadatak',
  'LBL_TASK' => 'Zadaci:',
  'LBL_MODULE_TITLE' => 'Zadaci: Početna strana',
  'LBL_SEARCH_FORM_TITLE' => 'Pretraga zadataka',
  'LBL_LIST_FORM_TITLE' => 'Lista zadataka',
  'LBL_NEW_FORM_TITLE' => 'Kreiraj zadatak',
  'LBL_NEW_FORM_SUBJECT' => 'Naslov:',
  'LBL_NEW_FORM_DUE_DATE' => 'Datum završetka:',
  'LBL_NEW_FORM_DUE_TIME' => 'Vreme završetka:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Zatvori',
  'LBL_LIST_SUBJECT' => 'Naslov',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Prioritet',
  'LBL_LIST_RELATED_TO' => 'Povezano sa',
  'LBL_LIST_DUE_DATE' => 'Datum završetka',
  'LBL_LIST_DUE_TIME' => 'Vreme završetka',
  'LBL_SUBJECT' => 'Naslov:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Datum završetka:',
  'LBL_DUE_TIME' => 'Vreme završetka:',
  'LBL_PRIORITY' => 'Prioritet:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Datum i vreme završetka:',
  'LBL_START_DATE_AND_TIME' => 'Datum i vreme početka:',
  'LBL_START_DATE' => 'Datum početka:',
  'LBL_LIST_START_DATE' => 'Datum početka:',
  'LBL_START_TIME' => 'Vreme početka:',
  'LBL_LIST_START_TIME' => 'Vreme početka',
  'DATE_FORMAT' => '(gggg-mm-dd)',
  'LBL_NONE' => 'Nijedna',
  'LBL_CONTACT' => 'Kontakt',
  'LBL_EMAIL_ADDRESS' => 'Email adresa:',
  'LBL_PHONE' => 'Telefon:',
  'LBL_EMAIL' => 'Email adresa:',
  'LBL_DESCRIPTION_INFORMATION' => 'Opisne informacije',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_NAME' => 'Naslov:',
  'LBL_CONTACT_NAME' => 'Ime kontakta:',
  'LBL_LIST_COMPLETE' => 'Završi:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'Nema datuma završetka',
  'LBL_DATE_START_FLAG' => 'Nema datuma početka',
  'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da bi obrisali kontakt.',
  'ERR_INVALID_HOUR' => 'Molim, unesite sat između 0 i 24',
  'LBL_DEFAULT_PRIORITY' => 'Srednje',
  'LBL_LIST_MY_TASKS' => 'Moji aktuelni zadaci',
  'LNK_NEW_TASK' => 'Kreiraj zadatak',
  'LNK_TASK_LIST' => 'Pregledaj zadatke',
  'LNK_IMPORT_TASKS' => 'Uvezi zadatke',
  'LBL_CONTACT_FIRST_NAME'=>'Ime kontakta',
  'LBL_CONTACT_LAST_NAME'=>'Prezime kontakta',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodeljeni korisnik',
  'LBL_ASSIGNED_TO_NAME'=>'Dodeljeno',
  'LBL_LIST_DATE_MODIFIED' => 'Datum izmene',
  'LBL_CONTACT_ID' => 'ID broj kontakta:',
  'LBL_PARENT_ID' => 'Matični ID broj:',
  'LBL_CONTACT_PHONE' => 'Telefon kontakta:',
  'LBL_PARENT_NAME' => 'Matični tip:',
  'LBL_ACTIVITIES_REPORTS' => 'Izveštaj o Aktivnostima',
  'LBL_EDITLAYOUT' => 'Izmeni raspored' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Pregled zadatka',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Beleške',
  'LBL_REVENUELINEITEMS' => 'Stavke prihoda',
  //For export labels
  'LBL_DATE_DUE' => 'Datum zaduživanja',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Ime dodeljenog korisnika',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID broj dodeljenog korisnika',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'ID korisnika koji je promenio',
  'LBL_EXPORT_CREATED_BY' => 'ID broj osobe koja je kreirala',
  'LBL_EXPORT_PARENT_TYPE' => 'Povezano sa Modulom',
  'LBL_EXPORT_PARENT_ID' => 'Povezano sa ID-jem',
  'LBL_TASK_CLOSE_SUCCESS' => 'Zadatak uspešno zatvoren.',
  'LBL_ASSIGNED_USER' => 'Dodeljeno',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Beleške',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} modul se sastoji od fleksibilnih akcija, za-uraditi stavki, ili od drugih tipova aktivnosti koji zahteva kompletiranje. {{module_name}} zapisi mogu biti vezani za jedan zapis u većini modula preko flex polja za vezu i može biti vezan za jedan {{contacts_singular_module}}. Postoji nekoliko načina da se kreira {{plural_module_name}} u Sugar-u kao što su preko {{plural_module_name}} modula, dupliranjem, uvozom {{plural_module_name}}, itd. Jednom kada je {{module_name}} zapis kreiran, moguć je pregled i izmena podataka koji se tiču {{module_name}} preko {{plural_module_name}} pregleda zapisa. Zavisno od detalja na {{module_name}}, moguć je pregled i izmena {{module_name}} informacija preko Kalendar modula. Svaki {{module_name}} zapis onda može da ukazuje na zapis Sugar-a kao što su {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, i mnogi drugi.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

- Edit this record&#39;s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} modul se sastoji od fleksibilnih akcija, stavki koje treba obaviti, ili od drugih tipova aktivnosti koji zahtevaju kompletiranje. 

Da bi {{module_name}} bio kreiran: 
1. Obezbediti vrednost za polja po želji. 
- Polja označena kao "Obavezna" moraju biti uneta pre čuvanja podataka. 
- Kliknite na "Pokaži više" za prikaz dodatnih polja ako je potrebno. 
2. Kliknite na "Sačuvaj" da dovršite novi zapis i povratak na predhodnu stranu.',

);
