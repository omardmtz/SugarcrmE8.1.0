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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Definicije radnog toka',
  'LBL_MODULE_NAME_SINGULAR' => 'Definicija Radnog Toka',
  'LBL_MODULE_ID' => 'Radni tok',  
  'LBL_MODULE_TITLE' => 'Radni tok: Početna strana',
  'LBL_SEARCH_FORM_TITLE' => 'Pratraga radnog toka',
  'LBL_LIST_FORM_TITLE' => 'Lista radnih tokova',
  'LBL_NEW_FORM_TITLE' => 'Kreiraj definiciju radnog toka',
  'LBL_LIST_NAME' => 'Naziv',
  'LBL_LIST_TYPE' => 'Izvršenje se javlja:',
  'LBL_LIST_BASE_MODULE' => 'Ciljani modul:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_NAME' => 'Naziv:',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_TYPE' => 'Izvršenje se javlja:',
  'LBL_STATUS' => 'Status:',
  'LBL_BASE_MODULE' => 'Ciljani modul:',
  'LBL_LIST_ORDER' => 'Redosled procesiranja:',
  'LBL_FROM_NAME' => 'Ime pošiljaoca:',
  'LBL_FROM_ADDRESS' => 'Adresa pošiljaoca:',  
  'LNK_NEW_WORKFLOW' => 'Kreiraj definiciju radnog toka',
  'LNK_WORKFLOW' => 'Lista definicija radnog toka', 
  
  
  'LBL_ALERT_TEMPLATES' => 'Šabloni upozorenja',
  'LBL_CREATE_ALERT_TEMPLATE' => 'Kreiraj šablon upozorenja:',
  'LBL_SUBJECT' => 'Naslov:',
  
  'LBL_RECORD_TYPE' => 'Odnosi se na:',
 'LBL_RELATED_MODULE'=> 'Povezani modul:',
  
  
  'LBL_PROCESS_LIST' => 'Redosled radnog toka',
	'LNK_ALERT_TEMPLATES' => 'Šabloni Email upozorenja',
	'LNK_PROCESS_VIEW' => 'Redosled radnog toka',
  'LBL_PROCESS_SELECT' => 'Molim, odaberite modul:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> 'Beleška: Morate da kreirate okidač za ovog objekta radnog toka da bi funkcionisao',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> 'Beleška: Da bi slali upozorenja, unesite informacije o SMTP serveru u Administraicji > Email podešavnaja.',
  'LBL_FIRE_ORDER' => 'Redosled procesiranja:',
  'LBL_RECIPIENTS' => 'Priomaoci',
  'LBL_INVITEES' => 'Pozvani',
  'LBL_INVITEE_NOTICE' => 'Upozorenje, morate odabrati bar jednog pozvanog da bi ovo kreirali.',
  'NTC_REMOVE_ALERT' => 'Da li ste sigurni da želite da uklonite ovaj radni tok?',
  'LBL_EDIT_ALT_TEXT' => 'Alternativni tekst',
  'LBL_INSERT' => 'Unesi',
  'LBL_SELECT_OPTION' => 'Molim, izaberite opciju.',
  'LBL_SELECT_VALUE' => 'Morate odabrati vrednost.',
  'LBL_SELECT_MODULE' => 'Molim, izaberite povezani modul.',
  'LBL_SELECT_FILTER' => 'Morate odabrati polje sa kojim ćete filtrirati povezani modul.',
  'LBL_LIST_UP' => 'gore',
  'LBL_LIST_DN' => 'dole',
  'LBL_SET' => 'Podesi',
  'LBL_AS' => 'kao',
  'LBL_SHOW' => 'Prikaži',
  'LBL_HIDE' => 'Sakrij',
  'LBL_SPECIFIC_FIELD' => 'specifično polje',
  'LBL_ANY_FIELD' => 'bilo koje polje',
  'LBL_LINK_RECORD'=>'Link za zapis',
  'LBL_INVITE_LINK'=>'Link za Sastanak/Poziv pozivanje',
  'LBL_PLEASE_SELECT'=>'Molim, izaberite',
  'LBL_BODY'=>'Tekst:',
  'LBL__S'=>'&#39;s',
  'LBL_ALERT_SUBJECT'=>'UPOZORENJE RADNOG TOKA',
  'LBL_ACTION_ERROR'=>'Ova akcija ne može da se izvrši. Izmenite akciju tako da sva polja i vrednosti polja budu validna.',
  'LBL_ACTION_ERRORS'=>'Beleška: Jedna ili više akcija ispod sadrže greške.',
  'LBL_ALERT_ERROR'=>'Ovo upozorenje ne može da se izvrši.  Izmenite upozorenje tako da sva podešavanja budu validna.',
  'LBL_ALERT_ERRORS'=>'Beleška: Jedno ili više upozorenja ispod sadrži greške.',
  'LBL_TRIGGER_ERROR'=>'Beleška: Ovaj okidač sadrži vrednosti koje nisu validne i neće se okinuti.',
  'LBL_TRIGGER_ERRORS'=>'Beleška: Jedan ili više okidača ispod sadrži greške.',
  'LBL_UP' => 'Gore' /*for 508 compliance fix*/,
  'LBL_DOWN' => 'Dole' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'Izmeni raspored' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'RadniTok'),
  'LBL_EMAILTEMPLATES_TYPE' => 'Tip',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Napomena:</strong> Funkcionalnosti Sugar tok posla i Upravljanje tokom posla će biti uklonjene u sledećem izdanju Sugar-a. Korisnici izdanja Sugar Enterprise bi trebalo da počnu da koriste funkcionalnost koju omogućava Sugar napredni tok posla. Kliknite na <a href="http://www.sugarcrm.com/wf-eol" target="_blank">here</a> za više informacija.',
);

