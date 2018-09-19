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
  'LBL_MODULE_NAME' => 'Predaj',
  'LBL_MODULE_TITLE' => 'Predaj: Domov',
  'LBL_SEARCH_FORM_TITLE' => 'Vyhľadať predaj',
  'LBL_VIEW_FORM_TITLE' => 'Zobraziť predaj',
  'LBL_LIST_FORM_TITLE' => 'Zoznam predajov',
  'LBL_SALE_NAME' => 'Názov predaja:',
  'LBL_SALE' => 'Predaj:',
  'LBL_NAME' => 'Názov predaja',
  'LBL_LIST_SALE_NAME' => 'Názov',
  'LBL_LIST_ACCOUNT_NAME' => 'Názov účtu',
  'LBL_LIST_AMOUNT' => 'Suma',
  'LBL_LIST_DATE_CLOSED' => 'Zavrieť',
  'LBL_LIST_SALE_STAGE' => 'Fáza predaja',
  'LBL_ACCOUNT_ID'=>'ID účtu',
  'LBL_TEAM_ID' =>'ID tímu',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Obchodná príležitosť - aktualizácia meny',
  'UPDATE_DOLLARAMOUNTS' => 'Aktualizácia súm v EUR',
  'UPDATE_VERIFY' => 'Overiť sumy',
  'UPDATE_VERIFY_TXT' => 'Overí, že hodnoty sumy v predaji sú platné desatinné čísla s iba číselnými znakmi (0 - 9) a desatinnými miestami (,)',
  'UPDATE_FIX' => 'Stanoviť sumy',
  'UPDATE_FIX_TXT' => 'Pokúša sa opraviť neplatné sumy vytváraním platných desatinných čísiel z aktuálnej sumy. Akákoľvek upravená suma je zálohovaná v databázovom poli amount_backup. Ak si počas tohot postupu všimnete chyby, neskúšajte to znovu bez obnovy zálohy, mohli by ste totiž prepísať zálohu neplatnými údajmi.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Aktualizujte sumy pre obchod na základe aktuálneho menového kurzu. Táto hodnota sa používa na vypočítanie grafov a zobrazenie zoznamu súm v mene.',
  'UPDATE_CREATE_CURRENCY' => 'Vytvorenie novej meny:',
  'UPDATE_VERIFY_FAIL' => 'Záznam sa nepodarilo overiť:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Aktuálna suma:',
  'UPDATE_VERIFY_FIX' => 'Spustenie opravy znamená',
  'UPDATE_INCLUDE_CLOSE' => 'Zahrnúť uzatvorené záznamy',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nová suma:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nová mena:',
  'UPDATE_DONE' => 'Dokončené',
  'UPDATE_BUG_COUNT' => 'Nájdené chyby a pokus o vyriešenie:',
  'UPDATE_BUGFOUND_COUNT' => 'Nájdené chyby:',
  'UPDATE_COUNT' => 'Aktualizované záznamy:',
  'UPDATE_RESTORE_COUNT' => 'Obnovené sumy záznamov:',
  'UPDATE_RESTORE' => 'Obnoviť sumy',
  'UPDATE_RESTORE_TXT' => 'Obnovuje hodnoty súm zo zálohy vytvorenej počas opravy.',
  'UPDATE_FAIL' => 'Nemožno aktualizovať -',
  'UPDATE_NULL_VALUE' => 'Množstvo je NULA nastavené na 0 -',
  'UPDATE_MERGE' => 'Zlúčenie mien',
  'UPDATE_MERGE_TXT' => 'Zlúčenie viacerých mien do jednej meny. Ak je tu viac záznamov mien pre rovnakú menu, zlúčte ich do jednej. Zlúčia sa tak aj meny všetkých modulov.',
  'LBL_ACCOUNT_NAME' => 'Názov účtu:',
  'LBL_AMOUNT' => 'Suma:',
  'LBL_AMOUNT_USDOLLAR' => 'Suma EUR:',
  'LBL_CURRENCY' => 'Mena:',
  'LBL_DATE_CLOSED' => 'Očakávaný dátum uzávierky:',
  'LBL_TYPE' => 'Typ:',
  'LBL_CAMPAIGN' => 'Kampaň:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Záujemcovia',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekty',  
  'LBL_NEXT_STEP' => 'Ďalší krok:',
  'LBL_LEAD_SOURCE' => 'Zdroj príležitosti:',
  'LBL_SALES_STAGE' => 'Fáza predaja:',
  'LBL_PROBABILITY' => 'Pravdepodobnosť (%):',
  'LBL_DESCRIPTION' => 'Popis:',
  'LBL_DUPLICATE' => 'Možná duplicita predajov',
  'MSG_DUPLICATE' => 'Je pravdepodobné, že vytvárate duplicitný záznam predaja. Záznamy predajov obsahujúce podobné názvy sú uvedené nižšie.<br>Kliknutím na tlačidlo Uložiť pokračujte vo vytváraní tohto predaja alebo kliknite na tlačidlo Zrušiť a vrátite sa na modul bez vytvorenia predaja.',
  'LBL_NEW_FORM_TITLE' => 'Vytvoriť predaj',
  'LNK_NEW_SALE' => 'Vytvoriť predaj',
  'LNK_SALE_LIST' => 'Predaj',
  'ERR_DELETE_RECORD' => 'Na odstránenie predaja musíte zadať číslo záznamu.',
  'LBL_TOP_SALES' => 'Moje TOP otvorené predaje',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Naozaj chcete odstrániť tento kontakt z predaja?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Naozaj chcete odstrániť tento predaj z projektu?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktivity',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'História',
    'LBL_RAW_AMOUNT'=>'Hrubá suma',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
	'LBL_ASSIGNED_TO_NAME' => 'Používateľ:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Priradený používateľ',
  'LBL_MY_CLOSED_SALES' => 'Moje uzatvorené predaje',
  'LBL_TOTAL_SALES' => 'Všetky predaje',
  'LBL_CLOSED_WON_SALES' => 'Uzatvorený úspešný predaj',
  'LBL_ASSIGNED_TO_ID' =>'Priradené k ID',
  'LBL_CREATED_ID'=>'Vytvorené používateľom s ID',
  'LBL_MODIFIED_ID'=>'Zmenené podľa ID',
  'LBL_MODIFIED_NAME'=>'Upravené používateľom s menom',
  'LBL_SALE_INFORMATION'=>'Informácie o predaji',
  'LBL_CURRENCY_ID'=>'ID meny',
  'LBL_CURRENCY_NAME'=>'Názov meny',
  'LBL_CURRENCY_SYMBOL'=>'Symbol meny',
  'LBL_EDIT_BUTTON' => 'Upraviť',
  'LBL_REMOVE' => 'Odstrániť',
  'LBL_CURRENCY_RATE' => 'Menový kurz',

);

