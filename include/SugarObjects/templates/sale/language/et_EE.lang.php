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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'Müük',
  'LBL_MODULE_TITLE' => 'Müük: avaleht',
  'LBL_SEARCH_FORM_TITLE' => 'Müügi otsing',
  'LBL_VIEW_FORM_TITLE' => 'Müügi vaade',
  'LBL_LIST_FORM_TITLE' => 'Müügi loend',
  'LBL_SALE_NAME' => 'Müügi nimi:',
  'LBL_SALE' => 'Müük:',
  'LBL_NAME' => 'Müügi nimi',
  'LBL_LIST_SALE_NAME' => 'Nimi',
  'LBL_LIST_ACCOUNT_NAME' => 'Konto nimi',
  'LBL_LIST_AMOUNT' => 'Summa',
  'LBL_LIST_DATE_CLOSED' => 'Sulge',
  'LBL_LIST_SALE_STAGE' => 'Müügietapp',
  'LBL_ACCOUNT_ID'=>'Konto ID',
  'LBL_TEAM_ID' =>'Meeskonna ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Müük – valuuta uuendamine',
  'UPDATE_DOLLARAMOUNTS' => 'Uuenda USD summasid',
  'UPDATE_VERIFY' => 'Kontrolli summasid',
  'UPDATE_VERIFY_TXT' => 'Kontrollib, kas müügi summa väärtused on kehtivad kümnendarvud ainult numbrimärkide (0–9) ja kümnendikega (.)',
  'UPDATE_FIX' => 'Fikseeri summad',
  'UPDATE_FIX_TXT' => 'Püüab fikseerida kehtetud summad, luues kehtiva kümnendarvu praegusest summast. Mis tahes muudetud summa varundatakse andmebaasifaili amount_backup. Selle käivitamisel ja programmivea märkamisel ärge taaskäivitage seda ilma varundist taastamata, kuna see võib varundi uute valede andmetega üle kirjutada.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Uuendage müügi USD summad praeguste määratud valuutakursside alusel. Seda väärtust kasutatakse graafikute ja loendi vaate valuutasummade arvutamiseks.',
  'UPDATE_CREATE_CURRENCY' => 'Uue valuuta loomine:',
  'UPDATE_VERIFY_FAIL' => 'Kirje kontrolli ebaõnnestumine:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Praegune summa:',
  'UPDATE_VERIFY_FIX' => 'Paranduse käivitamine annaks',
  'UPDATE_INCLUDE_CLOSE' => 'Lisa lõpetatud kirjed',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Uus summa:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Uus valuuta:',
  'UPDATE_DONE' => 'Tehtud',
  'UPDATE_BUG_COUNT' => 'Programmivead on leitud ja neid üritatakse lahendada:',
  'UPDATE_BUGFOUND_COUNT' => 'Leitud programmivead:',
  'UPDATE_COUNT' => 'Uuendatud kirjed:',
  'UPDATE_RESTORE_COUNT' => 'Taastatud kirje summad:',
  'UPDATE_RESTORE' => 'Taasta summad',
  'UPDATE_RESTORE_TXT' => 'Taastab summa väärtused parandamisel loodud varunditest.',
  'UPDATE_FAIL' => 'Ei saa uuendada – ',
  'UPDATE_NULL_VALUE' => 'Summa on NULL, selle seadistamine väärtusele 0 –',
  'UPDATE_MERGE' => 'Mesti valuutad',
  'UPDATE_MERGE_TXT' => 'Mitme valuuta mestimine üheks valuutaks. Kui sama valuta puhul on mitu valuutakirjet, mestige need kokku. See mestib ka kõigi teiste moodulite valuutad.',
  'LBL_ACCOUNT_NAME' => 'Konto nimi:',
  'LBL_AMOUNT' => 'Summa:',
  'LBL_AMOUNT_USDOLLAR' => 'Summa USD:',
  'LBL_CURRENCY' => 'Valuuta:',
  'LBL_DATE_CLOSED' => 'Oodatav sulgemiskuupäev:',
  'LBL_TYPE' => 'Tüüp:',
  'LBL_CAMPAIGN' => 'Kampaania:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Müügivihjed',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projektid',  
  'LBL_NEXT_STEP' => 'Järgmine samm:',
  'LBL_LEAD_SOURCE' => 'Müügivihje allikas:',
  'LBL_SALES_STAGE' => 'Müügietapp:',
  'LBL_PROBABILITY' => 'Tõenäosus (%):',
  'LBL_DESCRIPTION' => 'Kirjeldus:',
  'LBL_DUPLICATE' => 'Võimalik topeltmüük',
  'MSG_DUPLICATE' => 'Teie loodav müügikirje võib olla juba olemasoleva müügikirje duplikaat. Sarnaseid nimesid sisaldavad müügikirjed on loetletud allpool.<br>Uue müügi loomise jätkamseks klõpsake nuppu Salvesta või klõpsake nuppu Tühista moodulisse naasmiseks ilma müüki loomata.',
  'LBL_NEW_FORM_TITLE' => 'Loo müük',
  'LNK_NEW_SALE' => 'Loo müük',
  'LNK_SALE_LIST' => 'Müük',
  'ERR_DELETE_RECORD' => 'Müügi kustutamiseks täpsustage kirje numbrit.',
  'LBL_TOP_SALES' => 'Minu TOP avatud müügid',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Kas olete kindel, et soovite selle kontakti müügist eemaldada?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Kas olete kindel, et soovite selle müügi projektist eemaldada?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Tegevused',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Ajalugu',
    'LBL_RAW_AMOUNT'=>'Algsumma',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktid',
	'LBL_ASSIGNED_TO_NAME' => 'Kasutaja:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Määratud kasutaja',
  'LBL_MY_CLOSED_SALES' => 'Minu suletud müük',
  'LBL_TOTAL_SALES' => 'Müük kokku',
  'LBL_CLOSED_WON_SALES' => 'Lõpetatud võidetud müügid',
  'LBL_ASSIGNED_TO_ID' =>'Määratud ID',
  'LBL_CREATED_ID'=>'Looja ID',
  'LBL_MODIFIED_ID'=>'Muutja ID',
  'LBL_MODIFIED_NAME'=>'Muutja kasutajanimi',
  'LBL_SALE_INFORMATION'=>'Müügiinfo',
  'LBL_CURRENCY_ID'=>'Valuuta ID',
  'LBL_CURRENCY_NAME'=>'Valuuta nimi',
  'LBL_CURRENCY_SYMBOL'=>'Valuuta sümbol',
  'LBL_EDIT_BUTTON' => 'Redigeeri',
  'LBL_REMOVE' => 'Eemalda',
  'LBL_CURRENCY_RATE' => 'Valuutakurss',

);

