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
  'LBL_MODULE_NAME' => 'Prodaja',
  'LBL_MODULE_TITLE' => 'Prodaja: Poč. str.',
  'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje prodaje',
  'LBL_VIEW_FORM_TITLE' => 'Prikaz prodaje',
  'LBL_LIST_FORM_TITLE' => 'Popis prodaje',
  'LBL_SALE_NAME' => 'Naziv prodaje:',
  'LBL_SALE' => 'Prodaja:',
  'LBL_NAME' => 'Naziv prodaje',
  'LBL_LIST_SALE_NAME' => 'Naziv',
  'LBL_LIST_ACCOUNT_NAME' => 'Naziv računa',
  'LBL_LIST_AMOUNT' => 'Iznos',
  'LBL_LIST_DATE_CLOSED' => 'Zatvori',
  'LBL_LIST_SALE_STAGE' => 'Faza prodaje',
  'LBL_ACCOUNT_ID'=>'ID računa',
  'LBL_TEAM_ID' =>'ID tima',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Prodaja - Ažuriranje valute',
  'UPDATE_DOLLARAMOUNTS' => 'Ažuriraj iznose u američkim dolarima',
  'UPDATE_VERIFY' => 'Provjeri iznose',
  'UPDATE_VERIFY_TXT' => 'Provjerava jesu li vrijednosti iznosa prodaje valjani decimalni brojevi koji sadrže samo brojčane znakove (0 – 9) i decimalne znakove (,)',
  'UPDATE_FIX' => 'Popravi iznose',
  'UPDATE_FIX_TXT' => 'Pokušava popraviti nevaljane iznose stvaranjem valjanog decimalnog broja od trenutačnog iznosa. Izmijenjeni iznos sigurnosno se kopira u polje baze podataka „amount_backup”. Ako pokrenete ovu funkciju i primijetite pogreške, nemojte je ponovno pokrenuti prije vraćanja iz sigurnosne kopije jer to može prebrisati sigurnosnu kopiju novim nevaljanim podacima.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Ažurirajte iznose u američkim dolarima za prodaju na temelju trenutačno postavljenih valutnih tečajeva. Ta se vrijednost upotrebljava za izračun iznosa valute u grafikonima i prikazu popisa.',
  'UPDATE_CREATE_CURRENCY' => 'Stvaranje nove valute:',
  'UPDATE_VERIFY_FAIL' => 'Neuspjela provjera zapisa:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Trenutačni iznos:',
  'UPDATE_VERIFY_FIX' => 'Pokretanje Popravljanja dalo bi',
  'UPDATE_INCLUDE_CLOSE' => 'Uključi zatvorene zapise',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Novi iznos:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nova valuta:',
  'UPDATE_DONE' => 'Dovršeno',
  'UPDATE_BUG_COUNT' => 'Broj pogrešaka koje su pronađene i koje se pokušalo riješiti:',
  'UPDATE_BUGFOUND_COUNT' => 'Pronađeno pogrešaka:',
  'UPDATE_COUNT' => 'Ažurirano zapisa:',
  'UPDATE_RESTORE_COUNT' => 'Vraćeno iznosa zapisa:',
  'UPDATE_RESTORE' => 'Vrati iznose',
  'UPDATE_RESTORE_TXT' => 'Vraća vrijednosti iznosa iz sigurnosnih kopija stvorenih tijekom popravljanja.',
  'UPDATE_FAIL' => 'Nije moguće ažurirati - ',
  'UPDATE_NULL_VALUE' => 'Iznos ima vrijednost NULL kad je postavljen na 0 -',
  'UPDATE_MERGE' => 'Spoji valute',
  'UPDATE_MERGE_TXT' => 'Spojite više valuta u jednu. Ako postoji više zapisa s valutama za jednu valutu, spojite ih. To će također spojiti valute za sve ostale module.',
  'LBL_ACCOUNT_NAME' => 'Naziv računa:',
  'LBL_AMOUNT' => 'Iznos:',
  'LBL_AMOUNT_USDOLLAR' => 'Iznos (USD):',
  'LBL_CURRENCY' => 'Valuta:',
  'LBL_DATE_CLOSED' => 'Očekivani datum zatvaranja:',
  'LBL_TYPE' => 'Vrsta:',
  'LBL_CAMPAIGN' => 'Kampanja:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Pot. klij.',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekti',  
  'LBL_NEXT_STEP' => 'Sljedeći korak:',
  'LBL_LEAD_SOURCE' => 'Izvor poten. klijenta:',
  'LBL_SALES_STAGE' => 'Faza prodaje:',
  'LBL_PROBABILITY' => 'Vjerojatnost (%):',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_DUPLICATE' => 'Mogući duplikat prodaje',
  'MSG_DUPLICATE' => 'Zapis o prodaji koji ćete stvoriti možda je duplikat već postojećeg zapisa o prodaji. Zapisi o prodajama koji sadrže slična imena navedeni su u nastavku.<br>Kliknite na Spremi za nastavak stvaranja ove nove prodaje ili kliknite na Odustani za povratak na modul bez stvaranja prodaje.',
  'LBL_NEW_FORM_TITLE' => 'Stvori prodaju',
  'LNK_NEW_SALE' => 'Stvori prodaju',
  'LNK_SALE_LIST' => 'Prodaja',
  'ERR_DELETE_RECORD' => 'Broj zapisa mora biti naveden za brisanje prodaje.',
  'LBL_TOP_SALES' => 'Moja najveća otvorena prodaja',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Jeste li sigurni da želite ukloniti ovaj kontakt iz prodaje?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Jeste li sigurni da želite ukloniti ovu prodaju iz projekta?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktivnosti',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Povijest',
    'LBL_RAW_AMOUNT'=>'Bruto iznos',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakti',
	'LBL_ASSIGNED_TO_NAME' => 'Korisnik:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodijeljeni korisnik',
  'LBL_MY_CLOSED_SALES' => 'Moje zatvorene prodaje',
  'LBL_TOTAL_SALES' => 'Ukupna prodaja',
  'LBL_CLOSED_WON_SALES' => 'Prodaje zatvorene kao uspjele',
  'LBL_ASSIGNED_TO_ID' =>'Dodijeljeno ID-u',
  'LBL_CREATED_ID'=>'Stvorio ID',
  'LBL_MODIFIED_ID'=>'Izmijenio ID',
  'LBL_MODIFIED_NAME'=>'Izmijenilo korisničko ime',
  'LBL_SALE_INFORMATION'=>'Informacije o prodaji',
  'LBL_CURRENCY_ID'=>'ID valute',
  'LBL_CURRENCY_NAME'=>'Naziv valute',
  'LBL_CURRENCY_SYMBOL'=>'Simbol valute',
  'LBL_EDIT_BUTTON' => 'Uredi',
  'LBL_REMOVE' => 'Ukloni',
  'LBL_CURRENCY_RATE' => 'Valutni tečaj',

);

