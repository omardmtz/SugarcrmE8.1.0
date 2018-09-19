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
  'LBL_MODULE_NAME' => 'Myynti',
  'LBL_MODULE_TITLE' => 'Myynti: Etusivu',
  'LBL_SEARCH_FORM_TITLE' => 'Myynnin haku',
  'LBL_VIEW_FORM_TITLE' => 'Myyntinäkymä',
  'LBL_LIST_FORM_TITLE' => 'Myyntilista',
  'LBL_SALE_NAME' => 'Myynnin nimi:',
  'LBL_SALE' => 'Myynti:',
  'LBL_NAME' => 'Nimi',
  'LBL_LIST_SALE_NAME' => 'Nimi',
  'LBL_LIST_ACCOUNT_NAME' => 'Asiakkaan nimi',
  'LBL_LIST_AMOUNT' => 'Määrä',
  'LBL_LIST_DATE_CLOSED' => 'Sulje',
  'LBL_LIST_SALE_STAGE' => 'Myynnin vaihe',
  'LBL_ACCOUNT_ID'=>'Asiakkaan ID',
  'LBL_TEAM_ID' =>'Tiimin ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Myynti - Valuuttapäivitys',
  'UPDATE_DOLLARAMOUNTS' => 'Päivitä Yhdysvaltain dollarimäärät',
  'UPDATE_VERIFY' => 'Varmista arvot',
  'UPDATE_VERIFY_TXT' => 'Varmistaa, että arvot myynneissä ovat sallittuja desimaalilukuja, jotka sisältävät vain numeromerkkejä (0-9) ja desimaali<b>pisteitä</b> (.)',
  'UPDATE_FIX' => 'Korjaa arvot',
  'UPDATE_FIX_TXT' => 'Yrittää korjata virheelliset arvot luomalla kelpaavan desimaaliluvun nykyisestä arvosta. Muutetut arvot varmuuskopioidaan amount_backup -tietokantatauluun. Jos suoritat tämän ja huomaat virheitä, älä suorita sitä uudestaan ennen varmuuskopiosta palauttamista sillä suorittaminen saattaa korvata varmuuskopion uudella, virheellisellä datalla.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Päivitä Yhdysvaltain dollariarvot myynneille perustuen nykyisiin valuuttakursseihin. Tällä lasketaan kaavoja ja listanäkymävaluutta-arvoja.',
  'UPDATE_CREATE_CURRENCY' => 'Luodaan uutta valuuttaa:',
  'UPDATE_VERIFY_FAIL' => 'Tietueen todennus epäonnistui:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Nykyinen arvo:',
  'UPDATE_VERIFY_FIX' => 'Korjauksen suorittaminen antaisi',
  'UPDATE_INCLUDE_CLOSE' => 'Sisällytä suljetut tietueet',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Uusi määrä:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Uusi valuutta:',
  'UPDATE_DONE' => 'Tehty',
  'UPDATE_BUG_COUNT' => 'Virheitä löydetty ja yritetty korjata:',
  'UPDATE_BUGFOUND_COUNT' => 'Virheitä löydetty:',
  'UPDATE_COUNT' => 'Tietueita päivitetty:',
  'UPDATE_RESTORE_COUNT' => 'Tietuearvoja palautettu:',
  'UPDATE_RESTORE' => 'Palauta arvot',
  'UPDATE_RESTORE_TXT' => 'Palauttaa määräarvot korjauksen aikana tehtyistä varmuuskopioista.',
  'UPDATE_FAIL' => 'Ei voitu päivittää -',
  'UPDATE_NULL_VALUE' => 'Määrä on NULL, arvoksi asetetaan 0 -',
  'UPDATE_MERGE' => 'Yhdistä Valuutat',
  'UPDATE_MERGE_TXT' => 'Yhdistää useita valuuttoja yhteen valuuttaan. Jos on monta valuuttatietuetta samalle valuutalle, yhdistät ne yhteen. Tämä yhdistää valuutat kaikille muille moduuleille.',
  'LBL_ACCOUNT_NAME' => 'Asiakkaan nimi:',
  'LBL_AMOUNT' => 'Määrä:',
  'LBL_AMOUNT_USDOLLAR' => 'Määrä USD:',
  'LBL_CURRENCY' => 'Valuutta:',
  'LBL_DATE_CLOSED' => 'Odotettu sulkupäivämäärä:',
  'LBL_TYPE' => 'Tyyppi:',
  'LBL_CAMPAIGN' => 'Kampanja:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Liidit',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projektit',  
  'LBL_NEXT_STEP' => 'Seuraava vaihe:',
  'LBL_LEAD_SOURCE' => 'Liidin lähde:',
  'LBL_SALES_STAGE' => 'Myyntivaihe:',
  'LBL_PROBABILITY' => 'Todennäköisyys (%):',
  'LBL_DESCRIPTION' => 'Kuvaus',
  'LBL_DUPLICATE' => 'Mahdollinen kopio myynnistä',
  'MSG_DUPLICATE' => 'Myyntitietue, jota olet luomassa saattaa olla kopio olemassa olevasta myyntitietueesta. Myyntitietueet joilla on samankaltainen nimi ovat listattu alla.<br/>Klikkaa Tallenna jatkaaksesi tämän myynnin luomista, tai klikkaa Peruuta palataksesi moduuliin luomatta myyntiä.',
  'LBL_NEW_FORM_TITLE' => 'Luo Myynti',
  'LNK_NEW_SALE' => 'Luo Myynti',
  'LNK_SALE_LIST' => 'Myynti',
  'ERR_DELETE_RECORD' => 'Tietuenumero tulee määritellä jotta voit poistaa tilin.',
  'LBL_TOP_SALES' => 'Paras avoin myyntini',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Oletko varma, että haluat poistaa tämän kontaktin myynnistä?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Oletko varma, että haluat poistaa tämän myynnin projektista?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktiviteetit',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Historia',
    'LBL_RAW_AMOUNT'=>'Raaka arvo',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktit',
	'LBL_ASSIGNED_TO_NAME' => 'Vastuuhenkilö',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Vastuukäyttäjä',
  'LBL_MY_CLOSED_SALES' => 'Minun suljetut myynnit',
  'LBL_TOTAL_SALES' => 'Kokonaismyynti',
  'LBL_CLOSED_WON_SALES' => 'Suljetut voitetut myynnit',
  'LBL_ASSIGNED_TO_ID' =>'Vastuuhenkilön ID',
  'LBL_CREATED_ID'=>'Luojan ID',
  'LBL_MODIFIED_ID'=>'Muokkaajan ID',
  'LBL_MODIFIED_NAME'=>'Muokkaajan nimi',
  'LBL_SALE_INFORMATION'=>'Myynti Tiedot',
  'LBL_CURRENCY_ID'=>'Valuutta ID',
  'LBL_CURRENCY_NAME'=>'Valuutan nimi',
  'LBL_CURRENCY_SYMBOL'=>'Valuuttasymboli',
  'LBL_EDIT_BUTTON' => 'Muokkaa',
  'LBL_REMOVE' => 'Poista',
  'LBL_CURRENCY_RATE' => 'Valuuttakurssi',

);

