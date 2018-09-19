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
  'LBL_MODULE_NAME' => 'Pardavimas',
  'LBL_MODULE_TITLE' => 'Pardavimas: pradžia',
  'LBL_SEARCH_FORM_TITLE' => 'Pardavimo paieška',
  'LBL_VIEW_FORM_TITLE' => 'Pardavimo rodinys',
  'LBL_LIST_FORM_TITLE' => 'Pardavimų sąrašas',
  'LBL_SALE_NAME' => 'Pardavimo pavadinimas:',
  'LBL_SALE' => 'Pardavimas:',
  'LBL_NAME' => 'Pardavimo pavadinimas',
  'LBL_LIST_SALE_NAME' => 'Pavadinimas',
  'LBL_LIST_ACCOUNT_NAME' => 'Kliento pavadinimas',
  'LBL_LIST_AMOUNT' => 'Suma',
  'LBL_LIST_DATE_CLOSED' => 'Uždaryti',
  'LBL_LIST_SALE_STAGE' => 'Pardavimo etapas',
  'LBL_ACCOUNT_ID'=>'Kliento ID',
  'LBL_TEAM_ID' =>'Komandos ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Pardavimas – valiutos atnaujinimas',
  'UPDATE_DOLLARAMOUNTS' => 'Atnaujinti sumas JAV doleriais',
  'UPDATE_VERIFY' => 'Patikrinti sumas',
  'UPDATE_VERIFY_TXT' => 'Patikrina, ar pardavimo sumos yra leistini dešimtainiai skaičiai, susidedantys tik iš skaitinių simbolių nuo 0 iki 9 ir iš dešimtainio skirtuko (,)',
  'UPDATE_FIX' => 'Pataisyti sumas',
  'UPDATE_FIX_TXT' => 'Mėginamos pataisyti visos neleistinos sumos, sukuriant iš dabartinės sumos leistiną dešimtainį skačių. Bet kuri modifikuota suma įrašoma kaip atsarginė kopija į „sumų_atsarginių kopijų“ duomenų bazės lauką. Jei, paleidę šią funkciją, pastebite trikčių, prieš paleisdami ją iš naujo, atkurkite tą sumą iš atsarginės kopijos, nes to nepadarius pataisyta klaidinga suma gali perrašyti atsarginės kopijos sumą.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Atnaujinti pardavimų sumas JAV doleriais pagal dabartinius nustatytus valiutų kursus. Ši vertė yra naudojama apskaičiuojant diagaramose ir sąrašo rodiniuose naudojamas valiutų sumas.',
  'UPDATE_CREATE_CURRENCY' => 'Kuriama nauja valiuta:',
  'UPDATE_VERIFY_FAIL' => 'Rasti neteisingi įrašai:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Dabartinė suma:',
  'UPDATE_VERIFY_FIX' => 'Paleidus taisymo funkciją, ji būtų',
  'UPDATE_INCLUDE_CLOSE' => 'Įtraukti uždarytus įrašus',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nauja suma:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nauja valiuta:',
  'UPDATE_DONE' => 'Atlikta',
  'UPDATE_BUG_COUNT' => 'Rastos ir mėgintos pašalinti triktys:',
  'UPDATE_BUGFOUND_COUNT' => 'Rastos triktys:',
  'UPDATE_COUNT' => 'Atnaujinti įrašai:',
  'UPDATE_RESTORE_COUNT' => 'Rasta atkurtų sumų:',
  'UPDATE_RESTORE' => 'Atkurtos sumos',
  'UPDATE_RESTORE_TXT' => 'Atkuria sumų vertes iš taisymo funkcijos sukurtų atsarginių kopijų.',
  'UPDATE_FAIL' => 'Nepavyko atnaujinti -',
  'UPDATE_NULL_VALUE' => 'Suma yra NULL, todėl nustatoma jos vertė 0 -',
  'UPDATE_MERGE' => 'Sulieti valiutas',
  'UPDATE_MERGE_TXT' => 'Sulieja kelias valiutas į vieną valiutą. Jei yra keli įrašai ta pačia valiuta, jie bus sulieti į vieną. Tai leis sulieti ir visų kitų modulių valiutas.',
  'LBL_ACCOUNT_NAME' => 'Kliento pavadinimas:',
  'LBL_AMOUNT' => 'Suma:',
  'LBL_AMOUNT_USDOLLAR' => 'Suma, USD:',
  'LBL_CURRENCY' => 'Valiuta:',
  'LBL_DATE_CLOSED' => 'Numatoma uždarymo data:',
  'LBL_TYPE' => 'Tipas:',
  'LBL_CAMPAIGN' => 'Kampanija:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Galimi klientai',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projektai',  
  'LBL_NEXT_STEP' => 'Kitas veiksmas:',
  'LBL_LEAD_SOURCE' => 'Galimo kliento šaltinis:',
  'LBL_SALES_STAGE' => 'Pardavimo etapas:',
  'LBL_PROBABILITY' => 'Tikimybė (%):',
  'LBL_DESCRIPTION' => 'Aprašas:',
  'LBL_DUPLICATE' => 'Galimas pardavimo dubliavimasis',
  'MSG_DUPLICATE' => 'Jūsų kuriamas pardavimo įrašas gali dubliuoti jau esantį pardavimo įrašą. Pardavimų įrašai panašiais pavadinimais išvardyti toliau.<br>Jei norite tęsti šio naujo pardavimo kūrimą, spustelėkite „Įrašyti“, jei norite grįžti į modulį nesukurdami šio pardavimo, spustelėkite „Atšaukti“.',
  'LBL_NEW_FORM_TITLE' => 'Kurti pardavimą',
  'LNK_NEW_SALE' => 'Kurti pardavimą',
  'LNK_SALE_LIST' => 'Pardavimas',
  'ERR_DELETE_RECORD' => 'Turite nurodyti įrašo numerį, kad galėtumėte panaikinti šį pardavimą.',
  'LBL_TOP_SALES' => 'Mano geriausi atidaryti pardavimai',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Ar tikrai norite pašalinti šį kontaktą iš šio pardavimo?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Ar tikrai norite pašalinti šį pardavimą iš šio projekto?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Veiklos',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Istorija',
    'LBL_RAW_AMOUNT'=>'Pradinė suma',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktai',
	'LBL_ASSIGNED_TO_NAME' => 'Vartotojas:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Priskirtas vartotojas',
  'LBL_MY_CLOSED_SALES' => 'Mano uždaryti pardavimai',
  'LBL_TOTAL_SALES' => 'Iš viso pardavimų',
  'LBL_CLOSED_WON_SALES' => 'Uždaryti kaip laimėti pardavimai',
  'LBL_ASSIGNED_TO_ID' =>'Priskirta ID',
  'LBL_CREATED_ID'=>'Kūrėjo ID',
  'LBL_MODIFIED_ID'=>'Modifikuotojo ID',
  'LBL_MODIFIED_NAME'=>'Modifikavusio vartotojo vardas',
  'LBL_SALE_INFORMATION'=>'Pardavimo informacija',
  'LBL_CURRENCY_ID'=>'Valiutos ID',
  'LBL_CURRENCY_NAME'=>'Valiutos pavadinimas',
  'LBL_CURRENCY_SYMBOL'=>'Valiutos simbolis',
  'LBL_EDIT_BUTTON' => 'Redaguoti',
  'LBL_REMOVE' => 'Pašalinti',
  'LBL_CURRENCY_RATE' => 'Valiutos kursas',

);

