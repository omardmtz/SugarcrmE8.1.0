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
  'LBL_MODULE_NAME' => 'Verkoop',
  'LBL_MODULE_TITLE' => 'Verkoop: Start',
  'LBL_SEARCH_FORM_TITLE' => 'Verkoop: Zoeken',
  'LBL_VIEW_FORM_TITLE' => 'Verkoop: Bekijken',
  'LBL_LIST_FORM_TITLE' => 'Verkoop: Lijst',
  'LBL_SALE_NAME' => 'Verkoopnaam:',
  'LBL_SALE' => 'Verkoop:',
  'LBL_NAME' => 'Verkoopnaam',
  'LBL_LIST_SALE_NAME' => 'Naam',
  'LBL_LIST_ACCOUNT_NAME' => 'Organisatienaam',
  'LBL_LIST_AMOUNT' => 'Bedrag',
  'LBL_LIST_DATE_CLOSED' => 'Sluiten',
  'LBL_LIST_SALE_STAGE' => 'Verkoopstadium',
  'LBL_ACCOUNT_ID'=>'Organisatie ID',
  'LBL_TEAM_ID' =>'Team-ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Verkoop - Valuta Update',
  'UPDATE_DOLLARAMOUNTS' => 'Update U.S. Dollar Bedragen',
  'UPDATE_VERIFY' => 'Controleer Bedragen',
  'UPDATE_VERIFY_TXT' => 'Verifieert dat de bedragen in verkoop geldige decimale getallen zijn met alleen numerieke tekens (0-9) en decimalen (.)',
  'UPDATE_FIX' => 'Herstel Bedragen',
  'UPDATE_FIX_TXT' => 'Poging tot het vaststellen van ongeldige bedragen door het creëren van een geldig decimaal getal van het huidige bedrag. Alle gewijzigde bedragen worden als een back-up in de amount_backup database veld gezet. Als u dit toepast en fouten constateert, dient u eerst de backup te restoren alvorens het nogmaals uit te voeren, anders overschrijft u de backup met ongeldige data.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Update van de US Dollar bedragen voor de verkoop op basis van de huidige wisselkoersen. Deze waarde wordt gebruikt om grafieken en lijstweergave valuta bedragen te berekenen.',
  'UPDATE_CREATE_CURRENCY' => 'Nieuwe valuta aanmaken:',
  'UPDATE_VERIFY_FAIL' => 'Record Failed Verification:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Huidig Bedrag:',
  'UPDATE_VERIFY_FIX' => 'Running Fix would give',
  'UPDATE_INCLUDE_CLOSE' => 'Include Closed Records',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nieuw bedrag:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nieuwe valuta:',
  'UPDATE_DONE' => 'Klaar',
  'UPDATE_BUG_COUNT' => 'Bugs Found and Attempted to Resolve:',
  'UPDATE_BUGFOUND_COUNT' => 'Bugs Found:',
  'UPDATE_COUNT' => 'Records Updated:',
  'UPDATE_RESTORE_COUNT' => 'Record Amounts Restored:',
  'UPDATE_RESTORE' => 'Restore Amounts',
  'UPDATE_RESTORE_TXT' => 'Restores amount values from the backups created during fix.',
  'UPDATE_FAIL' => 'Could not update -',
  'UPDATE_NULL_VALUE' => 'Amount is NULL setting it to 0 -',
  'UPDATE_MERGE' => 'Merge Currencies',
  'UPDATE_MERGE_TXT' => 'Voeg meerdere valuta samen tot één enkele valuta. Als er zich meerdere valutarecords bevinden voor dezelfde valuta kunt u ze samenvoegen. Hierdoor worden ook de valuta voor alle andere modules samengevoegd.',
  'LBL_ACCOUNT_NAME' => 'Organisatienaam:',
  'LBL_AMOUNT' => 'Bedrag:',
  'LBL_AMOUNT_USDOLLAR' => 'Bedrag USD:',
  'LBL_CURRENCY' => 'Valuta:',
  'LBL_DATE_CLOSED' => 'Verwachte afsluitdatum:',
  'LBL_TYPE' => 'Type:',
  'LBL_CAMPAIGN' => 'Campagne:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projecten',  
  'LBL_NEXT_STEP' => 'Volgende stap:',
  'LBL_LEAD_SOURCE' => 'Bron voor lead:',
  'LBL_SALES_STAGE' => 'Verkoopstadium:',
  'LBL_PROBABILITY' => 'Waarschijnlijkheid (%):',
  'LBL_DESCRIPTION' => 'Beschrijving:',
  'LBL_DUPLICATE' => 'Mogelijke dubbele verkoop',
  'MSG_DUPLICATE' => 'Het verkooprecord dat u wilt aanmaken kan een kopie zijn van een verkooprecord dat reeds bestaat. Verkooprecords die soortgelijke namen bevatten worden hieronder weergegeven.<br>Klik op Opslaan om door te gaan met het aanmaken van deze nieuwe verkoop of klik op Annuleren om terug te keren naar de module zonder de verkoop aan te maken.',
  'LBL_NEW_FORM_TITLE' => 'Nieuwe Verkoop',
  'LNK_NEW_SALE' => 'Nieuwe Verkoop',
  'LNK_SALE_LIST' => 'Verkoop',
  'ERR_DELETE_RECORD' => 'Er moet een recordnummer worden gespecificeerd om de verkoop te verwijderen.',
  'LBL_TOP_SALES' => 'Mijn Top Openstaande Verkopen',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Ben je zeker dat je deze contactpersoon wil verwijderen uit de verkoop?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Ben je zeker dat je deze verkoop wil verwijderen uit het project?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activiteiten',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Geschiedenis',
    'LBL_RAW_AMOUNT'=>'Ruw Bedrag',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Personen',
	'LBL_ASSIGNED_TO_NAME' => 'Toegewezen aan:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Toegewezen aan',
  'LBL_MY_CLOSED_SALES' => 'Mijn gewonnen verkopen',
  'LBL_TOTAL_SALES' => 'Totale Verkopen',
  'LBL_CLOSED_WON_SALES' => 'Gewonnen Verkopen',
  'LBL_ASSIGNED_TO_ID' =>'Toegewezen aan ID',
  'LBL_CREATED_ID'=>'Aangemaakt door ID',
  'LBL_MODIFIED_ID'=>'Gewijzigd door ID',
  'LBL_MODIFIED_NAME'=>'Gewijzigd door Gebruiker',
  'LBL_SALE_INFORMATION'=>'Verkoopinformatie',
  'LBL_CURRENCY_ID'=>'Valuta ID',
  'LBL_CURRENCY_NAME'=>'Valutanaam',
  'LBL_CURRENCY_SYMBOL'=>'Valuta symbool',
  'LBL_EDIT_BUTTON' => 'Wijzig',
  'LBL_REMOVE' => 'Verwijder',
  'LBL_CURRENCY_RATE' => 'Wisselkoers',

);

