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
  'LBL_MODULE_NAME' => 'Sleva',
  'LBL_MODULE_TITLE' => 'Prodej: Hlavní stránka',
  'LBL_SEARCH_FORM_TITLE' => 'Vyhledat prodej',
  'LBL_VIEW_FORM_TITLE' => 'Zobrazit prodej',
  'LBL_LIST_FORM_TITLE' => 'Seznam prodeje',
  'LBL_SALE_NAME' => 'Jméno prodeje:',
  'LBL_SALE' => 'Prodej:',
  'LBL_NAME' => 'Jméno prodeje',
  'LBL_LIST_SALE_NAME' => 'Název',
  'LBL_LIST_ACCOUNT_NAME' => 'Jméno společnosti',
  'LBL_LIST_AMOUNT' => 'Částka',
  'LBL_LIST_DATE_CLOSED' => 'Zavřít',
  'LBL_LIST_SALE_STAGE' => 'Fáze prodeje:',
  'LBL_ACCOUNT_ID'=>'ID společnosti',
  'LBL_TEAM_ID' =>'ID týmu',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Prodej - aktualizace měny',
  'UPDATE_DOLLARAMOUNTS' => 'Aktualizace CZK částek',
  'UPDATE_VERIFY' => 'Ověření částek',
  'UPDATE_VERIFY_TXT' => 'Ověřuje, že částka hodnoty v prodeji jsou platná desetinná čísla s pouze číselnými znaky (0-9) a desetinnými místy (.)',
  'UPDATE_FIX' => 'Opravit částky',
  'UPDATE_FIX_TXT' => 'Pokus o opravu neplatné částky vytvořením platného čísla ze zadaných částek. Pro modifikované částky bude vytvořena záloha v databázovém poli amount_backup. Pokud zjistíte, že je tato procedura chybná, nespouštějte ji znovu bez předchozího obnovení částek ze zálohy, jinak byste mohli přepsat zazálohované částky neplatnými údaji.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Aktualizovat částky CZK na základě současných kurzů měn. Tato částka je použitá pro výpočet grafů a v seznamu Zobrazit částky v cizí měně.',
  'UPDATE_CREATE_CURRENCY' => 'Vytvoření nové měny:',
  'UPDATE_VERIFY_FAIL' => 'Ověření záznamu selhalo:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Aktuální částka:',
  'UPDATE_VERIFY_FIX' => 'Spuštění opravy znamená',
  'UPDATE_INCLUDE_CLOSE' => 'Včetně uzavřených záznamů',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nová částka:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nová měna:',
  'UPDATE_DONE' => 'Dokončeno',
  'UPDATE_BUG_COUNT' => 'Byly nalezeny chyby a proběhla snaha je opravit:',
  'UPDATE_BUGFOUND_COUNT' => 'Počet nalezených chyb:',
  'UPDATE_COUNT' => 'Aktualizováno záznamů:',
  'UPDATE_RESTORE_COUNT' => 'Obnoveno záznamů:',
  'UPDATE_RESTORE' => 'Obnovit částky',
  'UPDATE_RESTORE_TXT' => 'Obnovit hodnoty částek ze zálohy vytvořené během opravy.',
  'UPDATE_FAIL' => 'Není možno aktualizovat -',
  'UPDATE_NULL_VALUE' => 'Částka je NULL - nastavena na 0 -',
  'UPDATE_MERGE' => 'Sloučit měny',
  'UPDATE_MERGE_TXT' => 'Sloučit různé měny do jedné. Pokud zjistíte, že existují pro stejnou měnu různé záznamy, můžete je sloučit dohromady. Měny budou sloučeny také ve všech ostatních modulech.',
  'LBL_ACCOUNT_NAME' => 'Název společnosti:',
  'LBL_AMOUNT' => 'Částka:',
  'LBL_AMOUNT_USDOLLAR' => 'Částka USD:',
  'LBL_CURRENCY' => 'Měna:',
  'LBL_DATE_CLOSED' => 'Předpokládané datum uzavření:',
  'LBL_TYPE' => 'Typ:',
  'LBL_CAMPAIGN' => 'Kampaň:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Zájemci',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekty',  
  'LBL_NEXT_STEP' => 'Další krok:',
  'LBL_LEAD_SOURCE' => 'Zdroj příležitosti:',
  'LBL_SALES_STAGE' => 'Fáze prodeje:',
  'LBL_PROBABILITY' => 'Pravděpodobnost (%):',
  'LBL_DESCRIPTION' => 'Popis:',
  'LBL_DUPLICATE' => 'Možné duplicitní prodeje',
  'MSG_DUPLICATE' => 'Vytvořením tohoto Prodeje můžete potencionálně vytvořit duplicitní položku Prodeje. Můžete buď vybrat Prodej ze seznamu nebo můžete kliknout na Přidat nový Prodej pro vytvoření nového obchodu s předchozími údaji.',
  'LBL_NEW_FORM_TITLE' => 'Vytvořit prodej',
  'LNK_NEW_SALE' => 'Vytvořit prodej',
  'LNK_SALE_LIST' => 'Sleva',
  'ERR_DELETE_RECORD' => 'Číslo záznamu musí být uvedeno pro odstranění prodeje.',
  'LBL_TOP_SALES' => 'Mé otevřené prodeje',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Jste si jisti, že chcete odstranit tento kontakt z prodeje?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Jste si jisti, že chcete odstranit tuto prodeji od projektu?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktivity',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Historie',
    'LBL_RAW_AMOUNT'=>'hrubá částka',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
	'LBL_ASSIGNED_TO_NAME' => 'Uživatel:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Zodpovědný uživatel',
  'LBL_MY_CLOSED_SALES' => 'Moje uzavřené prodeje',
  'LBL_TOTAL_SALES' => 'Celkové prodeje',
  'LBL_CLOSED_WON_SALES' => 'Úspěšný prodej uzavřený',
  'LBL_ASSIGNED_TO_ID' =>'Přiděleno ID',
  'LBL_CREATED_ID'=>'Vytvořeno podle ID',
  'LBL_MODIFIED_ID'=>'Změněno podle ID',
  'LBL_MODIFIED_NAME'=>'Změněno podle už. jména',
  'LBL_SALE_INFORMATION'=>'Obchodní informace',
  'LBL_CURRENCY_ID'=>'ID měny',
  'LBL_CURRENCY_NAME'=>'Název měny',
  'LBL_CURRENCY_SYMBOL'=>'Symbol měny',
  'LBL_EDIT_BUTTON' => 'Editace',
  'LBL_REMOVE' => 'Odstranit',
  'LBL_CURRENCY_RATE' => 'Kurz měny',

);

