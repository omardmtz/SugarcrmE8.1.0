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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Dashbord for muligheterliste',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Dashbord for muligheteroppføring',

    'LBL_MODULE_NAME' => 'Muligheter',
    'LBL_MODULE_NAME_SINGULAR' => 'Salgsmulighet',
    'LBL_MODULE_TITLE' => 'Muligheter: Hjem',
    'LBL_SEARCH_FORM_TITLE' => 'Salgsmulighet søk',
    'LBL_VIEW_FORM_TITLE' => 'Mulighet-visning',
    'LBL_LIST_FORM_TITLE' => 'Mulighet-liste',
    'LBL_OPPORTUNITY_NAME' => 'Salgsmulighet navn:',
    'LBL_OPPORTUNITY' => 'Mulighet:',
    'LBL_NAME' => 'Mulighet-navn',
    'LBL_INVITEE' => 'Kontakter',
    'LBL_CURRENCIES' => 'Valuta',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Navn',
    'LBL_LIST_ACCOUNT_NAME' => 'Bedriftnavn',
    'LBL_LIST_DATE_CLOSED' => 'Lukk',
    'LBL_LIST_AMOUNT' => 'Mengde',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Beløp USD:',
    'LBL_ACCOUNT_ID' => 'Bedrift-ID',
    'LBL_CURRENCY_RATE' => 'Valutakurs',
    'LBL_CURRENCY_ID' => 'Valuta-ID',
    'LBL_CURRENCY_NAME' => 'Valuta-navn',
    'LBL_CURRENCY_SYMBOL' => 'Valutategn',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Opportunity - valutaoppdatering',
    'UPDATE_DOLLARAMOUNTS' => 'Oppdatér U.S. Dollar-beløp',
    'UPDATE_VERIFY' => 'Bekreft beløp',
    'UPDATE_VERIFY_TXT' => 'Bekrefter at verdien i Muligheter er gyldige desimaltall som kun inneholder numeriske tegn (0-9) og desimaler (.)',
    'UPDATE_FIX' => 'Ordne beløp',
    'UPDATE_FIX_TXT' => 'Prøver å ordne det slik at ugyldige beløp blir gitt en gyldig desimal fra den nåværende beløp. Alle endrede beløp får oppbakking via mengde_backup databasefeltet. Hvis du utfører denne handlingen og oppdager feil, vennligst ikke prøv igjen før du har gjenopprettet ved hjelp av backupen. Hvis ikke kan backup-dataene overskrives med nye ugyldige data.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Oppdatér U.S. Dollar-beløpet for Muligheter basert på den nåværende valutakursen. Denne verdien brukes for å kalkulere Grafer og Listevisning av Valutabeløp.',
    'UPDATE_CREATE_CURRENCY' => 'Oppretter ny valuta:',
    'UPDATE_VERIFY_FAIL' => 'Registerkontrollen mislyktes:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Nåværende beløp:',
    'UPDATE_VERIFY_FIX' => 'Å kjøre ordningen ville gitt',
    'UPDATE_INCLUDE_CLOSE' => 'Inkluderer lukkede registre',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Nytt beløp:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Ny valuta:',
    'UPDATE_DONE' => 'Ferdig',
    'UPDATE_BUG_COUNT' => 'Bug ble funnet og prøvd løst:',
    'UPDATE_BUGFOUND_COUNT' => 'Bug funnet:',
    'UPDATE_COUNT' => 'Registre ble oppdatert:',
    'UPDATE_RESTORE_COUNT' => 'Registermengder ble gjenopprettet:',
    'UPDATE_RESTORE' => 'Gjenopprett beløp',
    'UPDATE_RESTORE_TXT' => 'Gjenopprett beløp fra backup som ble til ved opprettingen.',
    'UPDATE_FAIL' => 'Kunne ikke oppdatere -',
    'UPDATE_NULL_VALUE' => 'Mengden er NULL som gir 0 -',
    'UPDATE_MERGE' => 'Fusjonér valutaer',
    'UPDATE_MERGE_TXT' => 'Fusjonér multiplume valutaer til en enkelt valuta. Hvis det finnes flere oppføringer for samme valuta, kan du slå de sammen til én. Dette vil også slå sammen valutaene for alle andre moduler.',
    'LBL_ACCOUNT_NAME' => 'Bedriftnavn:',
    'LBL_CURRENCY' => 'Valuta:',
    'LBL_DATE_CLOSED' => 'Forventet avslutningsdato:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Forventet lukkedato Tidsstempel',
    'LBL_TYPE' => 'Type:',
    'LBL_CAMPAIGN' => 'Kampanje:',
    'LBL_NEXT_STEP' => 'Neste skritt:',
    'LBL_LEAD_SOURCE' => 'Emne-kilder',
    'LBL_SALES_STAGE' => 'Salgssteg:',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Sannsynlighet (%):',
    'LBL_DESCRIPTION' => 'Beskrivelse:',
    'LBL_DUPLICATE' => 'Mulig dobbeltOpportunity',
    'MSG_DUPLICATE' => 'Denne Opportunity oppføringen som du er iferd med å opprette kan være en kopi av en Opportunity som allerede finnes. Opportunity oppføringer med lignende navn listes nedenfor.<br>Klikk på lagre for å fortsette med opprettelsen av denne Opportunity, eller klikk på Avbryt for å gå tilbake uten å opprette en ny Opportunity.',
    'LBL_NEW_FORM_TITLE' => 'Opprett Opportunity',
    'LNK_NEW_OPPORTUNITY' => 'Opprett Opportunity',
    'LNK_CREATE' => 'Opprett avtale',
    'LNK_OPPORTUNITY_LIST' => 'Vis Opportunities',
    'ERR_DELETE_RECORD' => 'Et registernummer må oppgis for å slette denne Opportunity.',
    'LBL_TOP_OPPORTUNITIES' => 'Mine topp ti salgsmuligheter',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Er du sikker på at du vil fjerne denne Kontakten fra den valgte Opportunity?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Er du sikker på at du vil fjerne denne Opportunity fra det valgte prosjektet?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Muligheter',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Handlinger',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historie',
    'LBL_RAW_AMOUNT' => 'Råmengde',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Emner',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaker',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokumenter',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Prosjekter',
    'LBL_ASSIGNED_TO_NAME' => 'Tildelt:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Tildelt bruker',
    'LBL_LIST_SALES_STAGE' => 'Salgsnivå',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Mine lukkede salgsmuligheter',
    'LBL_TOTAL_OPPORTUNITIES' => 'Totalt antall salgsmuligheter',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Lukkede Vunnet Salgsmuligheter',
    'LBL_ASSIGNED_TO_ID' => 'Tildelt ID',
    'LBL_CREATED_ID' => 'Opprettet av ID',
    'LBL_MODIFIED_ID' => 'Endret av ID',
    'LBL_MODIFIED_NAME' => 'Endret av brukernavn',
    'LBL_CREATED_USER' => 'Opprettet bruker',
    'LBL_MODIFIED_USER' => 'Endret bruker',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Kampanjer',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Prosjekter',
    'LABEL_PANEL_ASSIGNMENT' => 'Tildeling',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importer salgsmuligheter',
    'LBL_EDITLAYOUT' => 'Redigér oppsett' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Kampanje-ID',
    'LBL_OPPORTUNITY_TYPE' => 'Salgsmulighets-type',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Tildelt Brukernavn',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Tildelt Bruker-ID',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Endret av ID',
    'LBL_EXPORT_CREATED_BY' => 'Opprettet Av ID',
    'LBL_EXPORT_NAME' => 'Navn',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Relaterte kontakters e-poster',
    'LBL_FILENAME' => 'Vedlegg',
    'LBL_PRIMARY_QUOTE_ID' => 'Primært tilbud',
    'LBL_CONTRACTS' => 'Kontrakter',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Kontrakter',
    'LBL_PRODUCTS' => 'Tilbuds linjeelementer',
    'LBL_RLI' => 'Omsetning linjeelementer',
    'LNK_OPPORTUNITY_REPORTS' => 'Vis Opportunity rapporter',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Tilbud',
    'LBL_TEAM_ID' => 'Gruppe-ID',
    'LBL_TIMEPERIODS' => 'Tidsperioder',
    'LBL_TIMEPERIOD_ID' => 'Tidsperiode-ID',
    'LBL_COMMITTED' => 'Forpliktet',
    'LBL_FORECAST' => 'Inkluder i prognose',
    'LBL_COMMIT_STAGE' => 'Forpliktet stadie',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prognose',
    'LBL_WORKSHEET' => 'Regneark',

    'TPL_RLI_CREATE' => 'En salgsmulighet må ha en tilknyttet omsetningspost.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Opprett en omsetningspost',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Produkter',
    'LBL_RLI_SUBPANEL_TITLE' => 'Omsetninsposter',

    'LBL_TOTAL_RLIS' => '# av Totalt omsetningsposter',
    'LBL_CLOSED_RLIS' => '# av Lukket Omsetningsposter',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Du kan ikke slette Muligheter som inneholder lukkede Omsetning poster',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'En eller flere av de valgte postene inneholder avsluttet omsetningsposter og kan ikke slettes.',
    'LBL_INCLUDED_RLIS' => '# av inkluderte omsetningsposter',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Tilbud',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Salgsmuligheter Hiraki',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Still Forventet Lukk Dato feltet på de resulterende Salgsmuligheter poster for å være de tidligste eller seneste nære datoene for de eksisterende Revenue Linjeelementer',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Pipeline-totalen er ',

    'LBL_OPPORTUNITY_ROLE'=>'Salgsmulighetens rolle',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notater',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Ved å klikke på Bekreft , vil du bli slettet alle prognoser data og endre Salgsmuliheter. Hvis dette er ikke hva du mente , trykk Avbryt for å gå tilbake til tidligere innstillinger .',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Ved å klikke Bekreft vil du slette ALLE prognosedata og endre visning av muligheter. '
        .'ALLE prosessdefinisjoner med en målmodul for omsetningsposter vil også deaktiveres. '
        .'Hvis dette er ikke hva du mente, klikker du på avbryt for å gå tilbake til tidligere innstillinger.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Hvis alle Revenue Linjeelementer er lukker og minst en er satt til Vunnet',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'Salgsmuligheten er satt til Vunnet',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Hvis alle Revenue Linjeelementer er satt til Tapt i Salgsmulighet Status',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'Salgsmulighet status er satt til "Tapt"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Hvis noen Revenue Line Items fortsatt er åpne',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'Salgsmuligheten vil bli markert med seneste Salgsmulighet status',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Etter du starte denne endringen, vil Revenue Line Item summering notater bli bygget i bakgrunnen. Når notene er fullstendige og tilgjengelig, vil en melding bli sendt til e-postadressen på din brukerprofil. Hvis forekomsten er satt opp for {{forecasts_module}}, sukker vil også sende deg en melding når {{module_name}} poster synkroniseres til {{forecasts_module}} modul og tilgjengelig for ny {{forecasts_module}}. Vær oppmerksom på at forekomsten må konfigureres til å sende e-post via Admin > E-postinnstillinger for at meldingene skal sendes.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Etter du starte denne endringen , vil Revenue linjeelement poster opprettes for hver eksisterende { { module_name } } i bakgrunnen . Når Revenue Linjeelementer er komplett og tilgjengelig, vil en melding bli sendt til e-postadressen på din brukerprofil. Vær oppmerksom på at forekomsten må konfigureres til å sende e-post via Admin > E-postinnstillinger for at varsling skal sendes.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Med modulen {{plural_module_name}} kan du spore individuelt salg fra start til slutt. Hver {{module_name}}-oppføring representerer et potensielt salg, inkluderer relevante salgsdata og er også relaterte til andre viktige oppføringer som {{quotes_module}}, {{contacts_module}} osv. En {{module_name}} vil vanligvis gå gjennom flere salgstrinn før den enten merkes som "Lukket vunnet" eller "Lukket tapt". {{plural_module_name}} kan brukes ytterligere sammen med Sugars {{forecasts_singular_module}}-modul for å forstå og forutsi salgstrender samt for å fokusere arbeidet på å oppnå salgskvoter.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Med {{plural_module_name}}-modulen kan du følge individuelle salg og produktene som hører til salgene fra begynnelse til slutt. Hver {{module_name}}-post representerer en et forestående salg, inkluderer relevante salgsdata og knyttes til andre viktige poster som {{quotes_module}}, {{contacts_module}} osv.

– Rediger postfeltet ved å klikke på et enkeltfelt eller Rediger-knappen.
– Vis eller rediger lenker til andre poster i underpanelene ved å endre panelet nede til venstre til «Datavisning».
– Lag og se endringslogg for brukerkommentarer og -poster i {{activitystream_singular_module}} ved å endre panelet nede til venstre til «Aktivitetstrøm».
– Følg eller merk posten som favoritt ved bruk av ikonene til høyre for postnavnet.
– Ytterlighere handlinger er tilgjengelige i rullegardinmenyen for Handlinger til høyre for Rediger-knappen.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Med {{plural_module_name}}-modulen kan du følge individuelle salg og produktene som hører til salgene fra begynnelse til slutt. Hver {{module_name}}-post representerer en et forestående salg, inkluderer relevante salgsdata og knyttes til andre viktige poster som {{quotes_module}}, {{contacts_module}} osv.

Slik oppretter du {{module_name}}:
1. Angi verdiene for feltene som ønsket.
– Felt merket med "Obligatorisk" må fullføres før du lagrer.
– Klikk på "Vis mer" for å se ytterligere felt ved behov.
2. Klikk på "Lagre" for å ferdigstille den nye posten og gå tilbake til den forrige siden.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Synkroniser til Marketo®',
    'LBL_MKTO_ID' => 'Marketo lead-ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Topp 10 Salgsmuligheter',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Viser Topp 10 Salgsmuligheter i et boblediagram.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Mine Salgsmuligheterr',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Mitt teams TOP 10 Salgsmuligheter",
);
