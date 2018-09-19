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
  'LBL_TASKS_LIST_DASHBOARD' => 'Nadzorna ploča za popis zadataka',

  'LBL_MODULE_NAME' => 'Zadaci',
  'LBL_MODULE_NAME_SINGULAR' => 'Zadatak',
  'LBL_TASK' => 'Zadaci: ',
  'LBL_MODULE_TITLE' => ' Zadaci: Početna',
  'LBL_SEARCH_FORM_TITLE' => ' Pretraživanje zadataka',
  'LBL_LIST_FORM_TITLE' => ' Popis zadataka',
  'LBL_NEW_FORM_TITLE' => ' Stvori zadatak',
  'LBL_NEW_FORM_SUBJECT' => 'Predmet:',
  'LBL_NEW_FORM_DUE_DATE' => 'Krajnji rok:',
  'LBL_NEW_FORM_DUE_TIME' => 'Krajnje vrijeme:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Zatvori',
  'LBL_LIST_SUBJECT' => 'Predmet',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Prioritet',
  'LBL_LIST_RELATED_TO' => 'Povezano s',
  'LBL_LIST_DUE_DATE' => 'Krajnji rok',
  'LBL_LIST_DUE_TIME' => 'Krajnje vrijeme',
  'LBL_SUBJECT' => 'Predmet:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Krajnji rok:',
  'LBL_DUE_TIME' => 'Krajnje vrijeme:',
  'LBL_PRIORITY' => 'Prioritet:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Krajnji datum i vrijeme:',
  'LBL_START_DATE_AND_TIME' => 'Datum i vrijeme početka:',
  'LBL_START_DATE' => 'Datum početka:',
  'LBL_LIST_START_DATE' => 'Datum početka',
  'LBL_START_TIME' => 'Vrijeme početka:',
  'LBL_LIST_START_TIME' => 'Vrijeme početka',
  'DATE_FORMAT' => '(dd-mm-gggg)',
  'LBL_NONE' => 'Nema',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'Adresa e-pošte:',
  'LBL_PHONE' => 'Telefon:',
  'LBL_EMAIL' => 'Adresa e-pošte:',
  'LBL_DESCRIPTION_INFORMATION' => 'Informacije o opisu',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_NAME' => 'Ime:',
  'LBL_CONTACT_NAME' => 'Ime kontakta ',
  'LBL_LIST_COMPLETE' => 'Dovršeno:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'Nema krajnjeg roka',
  'LBL_DATE_START_FLAG' => 'Nema datuma početka',
  'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da biste izbrisali kontakt.',
  'ERR_INVALID_HOUR' => 'Unesite sat između 0 i 24',
  'LBL_DEFAULT_PRIORITY' => 'Srednji',
  'LBL_LIST_MY_TASKS' => 'Moji otvoreni zadaci',
  'LNK_NEW_TASK' => 'Stvori zadatak',
  'LNK_TASK_LIST' => 'Prikaži zadatke',
  'LNK_IMPORT_TASKS' => 'Uvezi zadatke',
  'LBL_CONTACT_FIRST_NAME'=>'Ime kontakta',
  'LBL_CONTACT_LAST_NAME'=>'Prezime kontakta',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodijeljeni korisnik',
  'LBL_ASSIGNED_TO_NAME'=>'Dodijeljeno:',
  'LBL_LIST_DATE_MODIFIED' => 'Datum izmjene',
  'LBL_CONTACT_ID' => 'ID kontakta:',
  'LBL_PARENT_ID' => 'Nadređeni ID:',
  'LBL_CONTACT_PHONE' => 'Telefon kontakta:',
  'LBL_PARENT_NAME' => 'Nadređena vrsta:',
  'LBL_ACTIVITIES_REPORTS' => 'Izvješće o aktivnostima',
  'LBL_EDITLAYOUT' => 'Uredi izgled' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Pregled',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Bilješke',
  'LBL_REVENUELINEITEMS' => 'Stavke prihoda',
  //For export labels
  'LBL_DATE_DUE' => 'Krajnji datum',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Ime dodijeljenog korisnika',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID dodijeljenog korisnika',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Izmijenio ID',
  'LBL_EXPORT_CREATED_BY' => 'Stvorio ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Povezano s modulom',
  'LBL_EXPORT_PARENT_ID' => 'Povezano s ID-om',
  'LBL_TASK_CLOSE_SUCCESS' => 'Zadatak uspješno završen.',
  'LBL_ASSIGNED_USER' => 'Dodijeljeno',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Bilješke',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} sastoji se od fleksibilnih radnji, obaveznih stavki ili druge vrste aktivnosti koje zahtijevaju dovršenje. Zapisi o modulu {{module_name}} mogu biti povezani s jednim zapisom u većini modula putem polja za fleksibilno povezivanje, a mogu biti povezani i s jednim modulom {{contacts_singular_module}}. Module {{plural_module_name}} u Sugaru možete stvoriti na više načina, npr. putem modula {{plural_module_name}}, dupliciranjem, uvozom modula {{plural_module_name}} itd. Nakon stvaranja modula {{module_name}}, možete vidjeti i uređivati informacije koje pripadaju modulu {{module_name}} putem prikaza zapisa o modulu {{plural_module_name}}. Ovisno o detaljima o modulu {{module_name}}, možda ćete također moći vidjeti i uređivati informacije o modulu {{module_name}} putem modula kalendara. Svaki zapis o modulu {{module_name}} tada se može povezati s drugim Sugar zapisima poput onih o modulima {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} i mnogim drugima.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} sastoji se od fleksibilnih radnji, obaveznih stavki ili druge vrste aktivnosti koje zahtijevaju dovršenje.

- Uredite polja ovog zapisa tako da kliknete na pojedino polje ili gumb Uredi.
- Pogledajte ili izmijenite poveznice na ostale zapise u podpločama tako da prebacite donje lijevo okno na „Prikaz podataka”.
- Objavljujte i pregledavajte komentare korisnika i bilježite povijest promjena u modulu {{activitystream_singular_module}} tako da prebacite donje lijevo okno na „Pregled aktivnosti”.
- Slijedite ili označite ovaj zapis kao omiljen s pomoću ikona koje se nalaze desno od naziva zapisa.
- Dodatne radnje dostupne su u padajućem izborniku Radnje koji se nalazi desno od gumba Uredi.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Modul {{plural_module_name}} sastoji se od fleksibilnih radnji, obaveznih stavki ili druge vrste aktivnosti koje zahtijevaju dovršenje.

Da biste stvorili {{module_name}}:
1. Unesite vrijednosti polja po želji.
 - Polja označena „Obavezno” moraju se ispuniti prije spremanja.
 - Kliknite na „Prikaži više” da biste otkrili dodatna polja ako je potrebno.
2. Kliknite na „Spremi” da biste završili novi zapis i vratili se na prethodnu stranicu.',

);
