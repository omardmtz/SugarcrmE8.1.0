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
  'LBL_MODULE_NAME' => 'Salg',
  'LBL_MODULE_TITLE' => 'Salg: Startside',
  'LBL_SEARCH_FORM_TITLE' => 'Søg efter salg',
  'LBL_VIEW_FORM_TITLE' => 'Salgsvisning',
  'LBL_LIST_FORM_TITLE' => 'Salgsliste',
  'LBL_SALE_NAME' => 'Salgsnavn:',
  'LBL_SALE' => 'Salg:',
  'LBL_NAME' => 'Salgsnavn',
  'LBL_LIST_SALE_NAME' => 'Navn',
  'LBL_LIST_ACCOUNT_NAME' => 'Kontonavn',
  'LBL_LIST_AMOUNT' => 'Beløb',
  'LBL_LIST_DATE_CLOSED' => 'Luk',
  'LBL_LIST_SALE_STAGE' => 'Salgsfase',
  'LBL_ACCOUNT_ID'=>'Virksomheds-id',
  'LBL_TEAM_ID' =>'Team-id',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LISTE_DATO_LUKKET',
//END DON'T CONVERT
  'UPDATE' => 'Salg - valutaopdatering',
  'UPDATE_DOLLARAMOUNTS' => 'Opdater USD-beløb',
  'UPDATE_VERIFY' => 'Bekræft beløb',
  'UPDATE_VERIFY_TXT' => 'Bekræfter, at beløbsværdierne i salg er gyldige decimaltal med kun numeriske tegn(0-9) og decimaler(.)',
  'UPDATE_FIX' => 'Ret beløb',
  'UPDATE_FIX_TXT' => 'Forsøger at rette ugyldige beløb ved at oprette en gyldig decimal ud fra det aktuelle beløb. Alle ændrede beløb sikkerhedskopieres i databasefeltet amount_backup. Hvis du kører denne og får fejl, skal du ikke køre den igen uden at gendanne ud fra sikkerhedskopien, da det kan overskrive sikkerhedskopien med nye ugyldige data.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Opdater USD-beløb for salg baseret på de aktuelle valutakurser. Denne værdi bruges til at beregne diagrammer og vise valutabeløb.',
  'UPDATE_CREATE_CURRENCY' => 'Opretter ny valuta:',
  'UPDATE_VERIFY_FAIL' => 'Registrer mislykket verifikation:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Aktuelt beløb:',
  'UPDATE_VERIFY_FIX' => 'Kørsel af rettelse vil give',
  'UPDATE_INCLUDE_CLOSE' => 'Medtag lukkede poster',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nyt beløb:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Ny valuta:',
  'UPDATE_DONE' => 'Udført',
  'UPDATE_BUG_COUNT' => 'Der blev fundet fejl og gjort forsøg på at løse dem:',
  'UPDATE_BUGFOUND_COUNT' => 'Der blev fundet fejl:',
  'UPDATE_COUNT' => 'Poster opdateret:',
  'UPDATE_RESTORE_COUNT' => 'Postbeløb gendannet:',
  'UPDATE_RESTORE' => 'Gendan beløb',
  'UPDATE_RESTORE_TXT' => 'Gendanner beløbsværdier ud fra de sikkerhedskopier, der blev oprettet under rettelsen.',
  'UPDATE_FAIL' => 'Kunne ikke opdatere -',
  'UPDATE_NULL_VALUE' => 'Beløbet er NULL. Angiver det til 0 -',
  'UPDATE_MERGE' => 'Flet valutaer',
  'UPDATE_MERGE_TXT' => 'Flet flere valutaer til en fælles valuta. Hvis der er flere valutaposter for samme valuta, skal du flette dem. Derved flettes også valutaerne for alle andre moduler.',
  'LBL_ACCOUNT_NAME' => 'Virksomhedsnavn:',
  'LBL_AMOUNT' => 'Beløb:',
  'LBL_AMOUNT_USDOLLAR' => 'Beløb i USD:',
  'LBL_CURRENCY' => 'Valuta:',
  'LBL_DATE_CLOSED' => 'Forventet lukkedato:',
  'LBL_TYPE' => 'Type:',
  'LBL_CAMPAIGN' => 'Kampagne:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Kundeemner',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekter',  
  'LBL_NEXT_STEP' => 'Næste trin:',
  'LBL_LEAD_SOURCE' => 'Kilde til kundeemne:',
  'LBL_SALES_STAGE' => 'Salgsfase:',
  'LBL_PROBABILITY' => 'Sandsynlighed "%":',
  'LBL_DESCRIPTION' => 'Beskrivelse:',
  'LBL_DUPLICATE' => 'Muligt identisk salg',
  'MSG_DUPLICATE' => 'Den salgspost, du er ved at oprette, kan være en dublet af en salgspost, der allerede findes. Salgsposter, som indeholder lignende navne, er vist nedenfor.<br>Klik på Gem for at fortsætte med at oprette dette nye salg, eller klik på Annuller for at vende tilbage til modulet uden at oprette salget.',
  'LBL_NEW_FORM_TITLE' => 'Opret salg',
  'LNK_NEW_SALE' => 'Opret salg',
  'LNK_SALE_LIST' => 'Salg',
  'ERR_DELETE_RECORD' => 'Der skal angives et postnummer for at slette salget.',
  'LBL_TOP_SALES' => 'Mine bedste åbne salg',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Er du sikker på, at du vil fjerne denne kontakt fra salget?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Er du sikker på, at du vil fjerne dette salg fra projektet?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktiviteter',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Historik',
    'LBL_RAW_AMOUNT'=>'Bruttobeløb',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakter',
	'LBL_ASSIGNED_TO_NAME' => 'Tildelt til:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Tildelt bruger',
  'LBL_MY_CLOSED_SALES' => 'Mine lukkede salg',
  'LBL_TOTAL_SALES' => 'Salg i alt',
  'LBL_CLOSED_WON_SALES' => 'Lukkede vundne salg',
  'LBL_ASSIGNED_TO_ID' =>'Tildelt til id',
  'LBL_CREATED_ID'=>'Oprettet af id',
  'LBL_MODIFIED_ID'=>'Ændret af id',
  'LBL_MODIFIED_NAME'=>'Ændret af brugernavn',
  'LBL_SALE_INFORMATION'=>'Salgsoplysninger',
  'LBL_CURRENCY_ID'=>'Valuta-id',
  'LBL_CURRENCY_NAME'=>'Valutanavn',
  'LBL_CURRENCY_SYMBOL'=>'Valutasymbol',
  'LBL_EDIT_BUTTON' => 'Rediger',
  'LBL_REMOVE' => 'Fjern',
  'LBL_CURRENCY_RATE' => 'Valutakurs',

);

