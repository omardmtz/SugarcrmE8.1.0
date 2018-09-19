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
  'LBL_MODULE_TITLE' => 'Salg: Hjem',
  'LBL_SEARCH_FORM_TITLE' => 'Salg søk',
  'LBL_VIEW_FORM_TITLE' => 'Salg vis',
  'LBL_LIST_FORM_TITLE' => 'Salgsliste',
  'LBL_SALE_NAME' => 'Salgsnavn:',
  'LBL_SALE' => 'Salg:',
  'LBL_NAME' => 'Salgsnavn',
  'LBL_LIST_SALE_NAME' => 'Navn',
  'LBL_LIST_ACCOUNT_NAME' => 'Kontoens navn',
  'LBL_LIST_AMOUNT' => 'Beløp',
  'LBL_LIST_DATE_CLOSED' => 'Lukk',
  'LBL_LIST_SALE_STAGE' => 'Salgsfase',
  'LBL_ACCOUNT_ID'=>'Bedrift ID',
  'LBL_TEAM_ID' =>'Team ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Salg - valutaoppdatering',
  'UPDATE_DOLLARAMOUNTS' => 'Oppdater U.S. Dollar-beløp',
  'UPDATE_VERIFY' => 'Bekreft beløp',
  'UPDATE_VERIFY_TXT' => 'Bekrefter at beløpsverdiene i salg er gyldige desimaltall med bare numeriske tegn (0-9) og desimaler (.)',
  'UPDATE_FIX' => 'Fastsette beløp',
  'UPDATE_FIX_TXT' => 'Forsøk på å fikse eventuelle ugyldige beløp ved å opprette en gyldig desimal fra nåværende beløp. Enhver endring av beløpet blir sikkerhetskopiert i "amount_backup" databasfeltet. Hvis du kjører dette og merker bugs, ikke kjør det uten gjenoppretting fra sikkerhetskopien siden det kan overskrive  backup med nye ugyldige data.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Oppdater US Dollar beløp for salg basert på gjeldende valutakurser. Denne verdien brukes til å beregne grafer og listevisning av Valutabeløp.',
  'UPDATE_CREATE_CURRENCY' => 'Oppretter ny valuta:',
  'UPDATE_VERIFY_FAIL' => 'Registerkontrollen mislyktes:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Nåværende beløp:',
  'UPDATE_VERIFY_FIX' => 'Kjøre fastsettelsen ville gi',
  'UPDATE_INCLUDE_CLOSE' => 'Inkluderer lukkede poster',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nytt beløp:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Ny valuta:',
  'UPDATE_DONE' => 'Ferdig',
  'UPDATE_BUG_COUNT' => 'Bug ble funnet og prøvd løst:',
  'UPDATE_BUGFOUND_COUNT' => 'Bug funnet:',
  'UPDATE_COUNT' => 'Poster ble oppdatert:',
  'UPDATE_RESTORE_COUNT' => 'Registrert beløp ble gjenopprettet:',
  'UPDATE_RESTORE' => 'Gjenopprett beløp',
  'UPDATE_RESTORE_TXT' => 'Gjenoppretter beløpsverdier fra sikkerhetskopier opprettet under reparasjon.',
  'UPDATE_FAIL' => 'Kunne ikke oppdatere -',
  'UPDATE_NULL_VALUE' => 'Beløpet er NULL som gir 0 -',
  'UPDATE_MERGE' => 'Flett valutaer',
  'UPDATE_MERGE_TXT' => 'Flett flere valutaer i en enkelt valuta. Hvis det er flere valutaposter for samme valuta, fletter du dem sammen. Dette vil også flette valutaene for alle andre moduler.',
  'LBL_ACCOUNT_NAME' => 'Bedriftnavn:',
  'LBL_AMOUNT' => 'Beløp:',
  'LBL_AMOUNT_USDOLLAR' => 'Beløp USD:',
  'LBL_CURRENCY' => 'Valuta:',
  'LBL_DATE_CLOSED' => 'Forventet avslutningsdato:',
  'LBL_TYPE' => 'Type:',
  'LBL_CAMPAIGN' => 'Kampanje:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Emner',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Prosjekter',  
  'LBL_NEXT_STEP' => 'Neste skritt:',
  'LBL_LEAD_SOURCE' => 'Emne-kilde:',
  'LBL_SALES_STAGE' => 'Salgsfase:',
  'LBL_PROBABILITY' => 'Sannsynlighet (%):',
  'LBL_DESCRIPTION' => 'Beskrivelse:',
  'LBL_DUPLICATE' => 'Mulig dobbelt tilbud',
  'MSG_DUPLICATE' => 'Det salget du er i ferd med å opprette kan være en kopi av et salg som allerede eksisterer. Salg som inneholder lignende navn er listet nedenfor.<br />Klikk Lagre for å fortsette å lagre det nye salg, eller klikk Avbryt for å gå tilbake til modulen uten å lagre salget.',
  'LBL_NEW_FORM_TITLE' => 'Opprett salg',
  'LNK_NEW_SALE' => 'Opprett salg',
  'LNK_SALE_LIST' => 'Salg',
  'ERR_DELETE_RECORD' => 'Du må oppgi postens nummer for å slette salget.',
  'LBL_TOP_SALES' => 'Mine topp åpne salg',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Er du sikker på at du vil fjerne denne kontakten fra det valgte salget?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Er du sikker på at du vil fjerne dette salget fra prosjektet?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktiviteter',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Historikk',
    'LBL_RAW_AMOUNT'=>'Råbeløp',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakter',
	'LBL_ASSIGNED_TO_NAME' => 'Bruker:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Tildelt bruker',
  'LBL_MY_CLOSED_SALES' => 'Mine stengte salg',
  'LBL_TOTAL_SALES' => 'Totale salg',
  'LBL_CLOSED_WON_SALES' => 'Lukkete vunnete salg',
  'LBL_ASSIGNED_TO_ID' =>'Tildelt til ID:',
  'LBL_CREATED_ID'=>'Opprettet av ID',
  'LBL_MODIFIED_ID'=>'Endret av ID',
  'LBL_MODIFIED_NAME'=>'Endret av brukernavn',
  'LBL_SALE_INFORMATION'=>'Salgsinformasjon',
  'LBL_CURRENCY_ID'=>'Valuta ID',
  'LBL_CURRENCY_NAME'=>'Valutaens navn',
  'LBL_CURRENCY_SYMBOL'=>'Valutategn',
  'LBL_EDIT_BUTTON' => 'Rediger',
  'LBL_REMOVE' => 'Fjern',
  'LBL_CURRENCY_RATE' => 'Valutakurs',

);

