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

$mod_strings = array(
    // Dashboard Names
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Instrumentpanel med lista över möjligheter',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Instrumentpanel med poster över möjligheter',

    'LBL_MODULE_NAME' => 'Affärsmöjligheter',
    'LBL_MODULE_NAME_SINGULAR' => 'Affärsmöjlighet',
    'LBL_MODULE_TITLE' => 'Affärsmöjligheter: Hem',
    'LBL_SEARCH_FORM_TITLE' => 'Sök affärsmöjligheter',
    'LBL_VIEW_FORM_TITLE' => 'Visa affärsmöjlighet',
    'LBL_LIST_FORM_TITLE' => 'Lista affärsmöjligheter',
    'LBL_OPPORTUNITY_NAME' => 'Namn på affärsmöjlighet:',
    'LBL_OPPORTUNITY' => 'Affärsmöjlighet:',
    'LBL_NAME' => 'Namn på affärsmöjlighet:',
    'LBL_INVITEE' => 'Kontakter',
    'LBL_CURRENCIES' => 'Valutor',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Namn',
    'LBL_LIST_ACCOUNT_NAME' => 'Organisationsnamn',
    'LBL_LIST_DATE_CLOSED' => 'Stäng',
    'LBL_LIST_AMOUNT' => 'Summa',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Belopp',
    'LBL_ACCOUNT_ID' => 'Konto-ID',
    'LBL_CURRENCY_RATE' => 'Valutakursen',
    'LBL_CURRENCY_ID' => 'Valuta ID',
    'LBL_CURRENCY_NAME' => 'Valutanamn',
    'LBL_CURRENCY_SYMBOL' => 'Valutasymbol',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Affärsmöjlighet - Valuta uppdatering',
    'UPDATE_DOLLARAMOUNTS' => 'Uppdatera summor',
    'UPDATE_VERIFY' => 'Verifiera summor',
    'UPDATE_VERIFY_TXT' => 'Verifierar att summans värde i affärsmöjligheterna har giltiga decimalsiffror, endast numeriska tecken (0-9) och decimaler (.)',
    'UPDATE_FIX' => 'Fixa summor',
    'UPDATE_FIX_TXT' => 'Försöker fixa ogiltiga summor genom att skapa ett giltigt decimaltal baserat på den aktuella valutan. Alla modifierade summor backupas till databasfältet amount_backup. Om du kör den här funktionen och noterar någon bugg, kör i så fall inte om funktionen innan du återställt data från backupen, eftersom du annars riskerar att skriva över backupen med ny ogiltig data.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Uppdatera beloppet som angivits i US Dollar för de aktuella affärsmöjligheterna med den nya valutakonverteringsfaktorn. Detta värde används för att skapa grafer och lista summor.',
    'UPDATE_CREATE_CURRENCY' => 'Skapa ny valuta:',
    'UPDATE_VERIFY_FAIL' => 'Objektet misslyckades vid verifieringen:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Aktuell summa:',
    'UPDATE_VERIFY_FIX' => 'Genomför fix skulle ge',
    'UPDATE_INCLUDE_CLOSE' => 'Inkludera stängda poster',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Ny summa:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Ny valuta:',
    'UPDATE_DONE' => 'Klar',
    'UPDATE_BUG_COUNT' => 'Buggar hittades och en lösning håller på att tas fram:',
    'UPDATE_BUGFOUND_COUNT' => 'Hittade buggar:',
    'UPDATE_COUNT' => 'Uppdaterade poster:',
    'UPDATE_RESTORE_COUNT' => 'Objektets summa återställd:',
    'UPDATE_RESTORE' => 'Återställ summor',
    'UPDATE_RESTORE_TXT' => 'Återställer summorna från backuperna som skapdes under fixen.',
    'UPDATE_FAIL' => 'Kunde inte uppdatera -',
    'UPDATE_NULL_VALUE' => 'Summan är NULL sätter det till 0 -',
    'UPDATE_MERGE' => 'Slå samman valutor',
    'UPDATE_MERGE_TXT' => 'Slå samman flera valutor till en valuta. Om det finns flera valutaposter angivna för samma valuta slås de samman. Denna funktion slår även samman valutorna för alla andra moduler.',
    'LBL_ACCOUNT_NAME' => 'Organisationsnamn:',
    'LBL_CURRENCY' => 'Valuta:',
    'LBL_DATE_CLOSED' => 'Förväntat avslutsdatum:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Förväntad Close Datum Tidsstämpel',
    'LBL_TYPE' => 'Typ:',
    'LBL_CAMPAIGN' => 'Kampanj:',
    'LBL_NEXT_STEP' => 'Nästa steg:',
    'LBL_LEAD_SOURCE' => 'Leadkälla',
    'LBL_SALES_STAGE' => 'Säljnivå:',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Möjlighet (%):',
    'LBL_DESCRIPTION' => 'Beskrivning:',
    'LBL_DUPLICATE' => 'Möjlig kopia av affärsmöjligheten',
    'MSG_DUPLICATE' => 'Den affärsmöjlighet du försöker skapa kan vara en kopia på en existerande affärsmöjlighet. Affärsmöjligheter med liknande namn listas nedan.<br> Klicka på Spara för att ändå spara den nya affärsmöjligheten eller klicka Avbryt för att återvända till modulen utan att skapa affärsmöjligheten.',
    'LBL_NEW_FORM_TITLE' => 'Skapa affärsmöjlighet',
    'LNK_NEW_OPPORTUNITY' => 'Skapa affärsmöjlighet',
    'LNK_CREATE' => 'Skapa ärende',
    'LNK_OPPORTUNITY_LIST' => 'Affärsmöjligheter',
    'ERR_DELETE_RECORD' => 'Ett objektnummer måste specificeras för att kunna radera affärsmöjligheten.',
    'LBL_TOP_OPPORTUNITIES' => 'Mina mest intressanta affärsmöjligheter',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Är du säker på att du vill ta bort kontakten från affärsmöjligheten?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Är du säker på att du vill ta bort affärsmöjligheten från projektet?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Affärsmöjligheter',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Aktiviteter',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historik',
    'LBL_RAW_AMOUNT' => 'Belopp',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakter',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokument',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekt',
    'LBL_ASSIGNED_TO_NAME' => 'Tilldelad till:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Tilldelad användare',
    'LBL_LIST_SALES_STAGE' => 'Säljnivå',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Mina stängda affärsmöjligheter',
    'LBL_TOTAL_OPPORTUNITIES' => 'Alla affärsmöjligheter',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Stängda vunna affärsmöjligheter',
    'LBL_ASSIGNED_TO_ID' => 'Tilldelad till användare:',
    'LBL_CREATED_ID' => 'Skapad av ID',
    'LBL_MODIFIED_ID' => 'Redigerad av ID',
    'LBL_MODIFIED_NAME' => 'Redigerad av användarnamn',
    'LBL_CREATED_USER' => 'Skapad användare',
    'LBL_MODIFIED_USER' => 'Ändrad användare',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Kampanjer',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projekt',
    'LABEL_PANEL_ASSIGNMENT' => 'Tilldelning',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importera affärsmöjligheter',
    'LBL_EDITLAYOUT' => 'Redigera layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Kampanj ID',
    'LBL_OPPORTUNITY_TYPE' => 'Typ av Affärsmöjlighet',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Tilldelat Användarnamn',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Tilldelad Användar ID',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Ändrad av ID',
    'LBL_EXPORT_CREATED_BY' => 'Skapad av ID',
    'LBL_EXPORT_NAME' => 'Namn',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Relaterad Kontakts Emails',
    'LBL_FILENAME' => 'Bilaga',
    'LBL_PRIMARY_QUOTE_ID' => 'Primär offert',
    'LBL_CONTRACTS' => 'Kontrakt',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Kontrakt',
    'LBL_PRODUCTS' => 'Offert poster',
    'LBL_RLI' => 'Intäktsposter',
    'LNK_OPPORTUNITY_REPORTS' => 'Affärsmöjlighet rapporter',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Offerter',
    'LBL_TEAM_ID' => 'Lag-ID',
    'LBL_TIMEPERIODS' => 'Tidsperioder',
    'LBL_TIMEPERIOD_ID' => 'Tidsperiod ID',
    'LBL_COMMITTED' => 'Incheckad',
    'LBL_FORECAST' => 'Inkludera i prognos',
    'LBL_COMMIT_STAGE' => 'Commit fas',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prognos',
    'LBL_WORKSHEET' => 'Arbetsark',

    'TPL_RLI_CREATE' => 'En affärsmöjlighet måste ha en tillhörande intäktspost.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Skapa en intäktspost.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Produkter',
    'LBL_RLI_SUBPANEL_TITLE' => 'Intäktsposter',

    'LBL_TOTAL_RLIS' => '# av totala intäktsposter',
    'LBL_CLOSED_RLIS' => '# av stängda intäktsposter',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Du kan inte ta bort affärsmöjligheter som innehåller stängda Intäktsposter',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'En eller flera av de valda posterna innehåller stängda intäktsposter och kan inte tas bort.',
    'LBL_INCLUDED_RLIS' => 'Antal inkomstposter',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Offerter',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Affärsmöjlighet Hierarki',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Sätt fältet Förväntat säljdatum på Affärsmöjlighetsposterna till det tidigaste eller senaste försäljningsdatumet i existerande inkomstposter',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Pipeline Totalt är',

    'LBL_OPPORTUNITY_ROLE'=>'Affärsmöjlighetsroll',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Anteckningar',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Genom att klicka på Godkänn tar du bort ALL Prognosdata och förändrar Affärsmöjlighetsvyn. Om inte detta är vad du vill, tryck på avbryt för att gå tillbaka till tidigare inställningar.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Genom att klicka på bekräfta, kommer du att radera alla prognosdata och ändra vyn Möjligheter. '
        .'Också alla processdefinitioner med en träffmodul för inkomstbringande artiklar kommer att inaktiveras. '
        .'Om detta inte är vad du tänkte gära, klicka på Avbryt för att återgå till föregående inställningar.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Om alla Inkomstposter är stängda och minst en vanns.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'Affärsmöjlighetens säljfas är satt till "Stängd vunnen".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Om alla Inkomstposter är i försäljningsskedet "Stängd förlorad",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'Affärsmöjlighetens säljfas är satt till "Stängd förlorad"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Om några Inkomstposter fortfarande är öppna,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'kommer Möjligheten att markeras som det lägsta Försäljningsskedet.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'När du initierat förändringen kommer Inkomstposternas sammanfattningar att byggas i bakgrunden. När de är klara och tillgängliga kommer du att få ett meddelande till mailadressen på din profil. Om din instans är inställd för {{forecasts_module}} kommer Sugar även att skicka ett meddelande när {{module_name}}-posterna synkas till {{forecasts_module}} och finns tillgängliga för nya {{forecasts_module}}. Obs. att din instans måste vara inställd på att skicka email via Admin -> Emailinställningar för att meddelanden ska kunna skickas.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Efter att förändringen påbörjas kommer Inkomstposter att skapas för varje {{module_name}} i bakgrunden. När de är klara och tillgängliga kommer du att få ett meddelande till mailadressen på din profil. Obs. att din instans måste vara inställd på att skicka email via Admin -> Emailinställningar för att meddelanden ska kunna skickas.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modulen {{plural_module_name}} låter dig spåra enskilda affärer från början till slut. Varje {{module_name}}-post representerar en potentiell affär och innehåller relevant försäljningsdata samt relaterar till andra viktiga poster som {{quotes_module}}, {{contacts_module}}, etc. En {{module_name}} kommer vanligtvis framskrida genom flera försäljningssteg tills det markeras antingen "Stängd vunnen" eller "Stängd förlorad". {{plural_module_name}} kan utnyttjas ännu mer genom att använda Sugars {{forecasts_singular_module}}-modul för att förstå och förutsäga försäljningstrender samt fokusera arbetet för att uppnå säljmål.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}}-modulen låter dig spåra individuella affärer och innehållet i dem från början till slut. Varje {{module_name}}-post representerar en potentiell affär och innehåller relevant försäljningsdata samt relaterar till andra viktiga poster som {{quotes_module}},{{contacts_module}}, etc.

- Redigera postens fält genom att klicka på dem eller på knappen Redigera.
- Visa eller redigera länkar till andra poster i underpanelerna genom att växla den nedre vänstra panelen till "Datavy".
- Skapa och visa kommentarer, och håll koll på användningshistoriken i {{activitystream_singular_module}} genom att växla den nedre vänstra panelen till "Aktivitetsström".
- Följ eller favoritmarkera posten med ikonerna till höger om postnamnet.
- Ytterligare åtgärder hittar du i menyn Åtgärder till höger om knappen Redigera.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}}-modulen låter dig spåra individuella affärer och innehållet i dem från början till slut. Varje {{module_name}}-post representerar en potentiell affär och innehåller relevant försäljningsdata samt relaterar till andra viktiga poster som {{quotes_module}},{{contacts_module}}, etc.

För att skapa en {{module_name}}:
1. Fyll i fälten med aktuella värden.
- Fält markerade som "Obligatoriska" måste fyllas i innan du sparar.
- Klicka på "Visa fler" för att få fler fält om det behövs.
2. Klicka på "Spara" för att färdigställa posten och gå tillbaks till den förra sidan.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Synka till Market&reg;',
    'LBL_MKTO_ID' => 'Marketo Lead ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Topp 10 Affärsmöjligheter',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Visar topp tio Affärsmöjligheter i ett bubbeldiagram.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Mina Affärsmöjligheter',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Mitt Teams Affärsmöjligheter",
);
