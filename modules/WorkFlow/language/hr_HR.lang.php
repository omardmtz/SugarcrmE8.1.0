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
  'LBL_MODULE_NAME' => 'Definicije tijeka rada',
  'LBL_MODULE_NAME_SINGULAR' => 'Definicija tijeka rada',
  'LBL_MODULE_ID' => 'Tijek rada',  
  'LBL_MODULE_TITLE' => 'Tijek rada: početno',
  'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje tijeka rada',
  'LBL_LIST_FORM_TITLE' => 'Popis tijeka rada',
  'LBL_NEW_FORM_TITLE' => 'Stvori definiciju tijeka rada',
  'LBL_LIST_NAME' => 'Naziv',
  'LBL_LIST_TYPE' => 'Izvršavanje se događa:',
  'LBL_LIST_BASE_MODULE' => 'Modul meta:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_NAME' => 'Naziv:',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_TYPE' => 'Izvršavanje se događa:',
  'LBL_STATUS' => 'Status:',
  'LBL_BASE_MODULE' => 'Modul meta:',
  'LBL_LIST_ORDER' => 'Redoslijed procesa:',
  'LBL_FROM_NAME' => 'Ime pošiljatelja:',
  'LBL_FROM_ADDRESS' => 'Adresa pošiljatelja:',  
  'LNK_NEW_WORKFLOW' => 'Stvori definiciju tijeka rada',
  'LNK_WORKFLOW' => 'Popiši definicije tijeka rada', 
  
  
  'LBL_ALERT_TEMPLATES' => 'Predlošci upozorenja',
  'LBL_CREATE_ALERT_TEMPLATE' => 'Stvori predložak upozorenja:',
  'LBL_SUBJECT' => 'Predmet:',
  
  'LBL_RECORD_TYPE' => 'Primjenjuje se na:',
 'LBL_RELATED_MODULE'=> 'Povezani modul:',
  
  
  'LBL_PROCESS_LIST' => 'Slijed tijeka rada',
	'LNK_ALERT_TEMPLATES' => 'Predlošci e-pošte za upozorenje',
	'LNK_PROCESS_VIEW' => 'Slijed tijeka rada',
  'LBL_PROCESS_SELECT' => 'Odaberite modul:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> 'Napomena: morate stvorite okidač da bi objekt tijeka rada mogao djelovati',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> 'Obavijest: da biste slali upozorenja, navedite informacije o poslužitelju za SMTP u Administrator > Postavke e-pošte. ',
  'LBL_FIRE_ORDER' => 'Redoslijed obrade:',
  'LBL_RECIPIENTS' => 'Primatelji',
  'LBL_INVITEES' => 'Pozv. korisnici',
  'LBL_INVITEE_NOTICE' => 'Pažnja, morate odabrati barem jednog pozvanog korisnika da biste ovo stvorili. ',
  'NTC_REMOVE_ALERT' => 'Jeste li sigurni da želite ukloniti ovaj tijek rada?',
  'LBL_EDIT_ALT_TEXT' => 'Zamjenski tekst',
  'LBL_INSERT' => 'Umetni',
  'LBL_SELECT_OPTION' => 'Odaberite opciju.',
  'LBL_SELECT_VALUE' => 'Morate odabrati vrijednost.',
  'LBL_SELECT_MODULE' => 'Odaberite povezani modul.',
  'LBL_SELECT_FILTER' => 'Morate odabrati polje putem kojeg ćete filtrirati povezani modul.',
  'LBL_LIST_UP' => 'gore',
  'LBL_LIST_DN' => 'dolj',
  'LBL_SET' => 'Postavi',
  'LBL_AS' => 'kao',
  'LBL_SHOW' => 'Prikaži',
  'LBL_HIDE' => 'Sakrij',
  'LBL_SPECIFIC_FIELD' => 'određeno polje',
  'LBL_ANY_FIELD' => 'bilo koje polje',
  'LBL_LINK_RECORD'=>'Poveži sa zapisom',
  'LBL_INVITE_LINK'=>'Poveznica za pozivanje na sastanak/poziv',
  'LBL_PLEASE_SELECT'=>'Odaberite',
  'LBL_BODY'=>'Tijelo:',
  'LBL__S'=>'od',
  'LBL_ALERT_SUBJECT'=>'UPOZORENJE O TIJEKU RADA',
  'LBL_ACTION_ERROR'=>'Nije moguće izvršiti radnju. Uredite radnju tako da sva polja i vrijednosti polja budu ispravne.',
  'LBL_ACTION_ERRORS'=>'Obavijest: jedna ili više radnji u nastavku sadrži pogreške.',
  'LBL_ALERT_ERROR'=>'Nije moguće izvršiti upozorenje. Uredite upozorenje tako da sve postavke budu ispravne.',
  'LBL_ALERT_ERRORS'=>'Obavijest: jedno ili više upozorenja u nastavku sadrži pogreške.',
  'LBL_TRIGGER_ERROR'=>'Obavijest: ovaj okidač sadrži neispravne vrijednosti i neće se pokrenuti.',
  'LBL_TRIGGER_ERRORS'=>'Obavijest: jedan ili više okidača u nastavku sadrže pogreške.',
  'LBL_UP' => 'Gore' /*for 508 compliance fix*/,
  'LBL_DOWN' => 'Dolje' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'Uredi izgled' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'Tijek rada'),
  'LBL_EMAILTEMPLATES_TYPE' => 'Vrsta',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Napoma:</strong> Iz budućeg izdanja Sugara uklonit će se funkcije Sugar tijek rada i Upravljanje tijekom rada. Korisnici izdanja Sugar Enterprise trebali bi početi s upotrebom funkcije Sugar napredni tijek rada. Više informacija potražite na <a href="http://www.sugarcrm.com/wf-eol" target="_blank">here</a>.',
);

