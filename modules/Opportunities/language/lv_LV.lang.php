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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Darījumu saraksta instrumentu panelis',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Darījumu ierakstu instrumentu panelis',

    'LBL_MODULE_NAME' => 'Iespējas',
    'LBL_MODULE_NAME_SINGULAR' => 'Iespēja',
    'LBL_MODULE_TITLE' => 'Iespējas: Sākums',
    'LBL_SEARCH_FORM_TITLE' => 'Iespēju meklēšana',
    'LBL_VIEW_FORM_TITLE' => 'Iespēju skatījums',
    'LBL_LIST_FORM_TITLE' => 'Iespēju saraksts',
    'LBL_OPPORTUNITY_NAME' => 'Iespējas nosaukums:',
    'LBL_OPPORTUNITY' => 'Iespēja:',
    'LBL_NAME' => 'Iespējas nosaukums',
    'LBL_INVITEE' => 'Kontaktpersonas',
    'LBL_CURRENCIES' => 'Valūtas',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Nosaukums',
    'LBL_LIST_ACCOUNT_NAME' => 'Uzņēmuma nosaukums',
    'LBL_LIST_DATE_CLOSED' => 'Aizvērt',
    'LBL_LIST_AMOUNT' => 'Ticamākā',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Kopsumma',
    'LBL_ACCOUNT_ID' => 'Uzņēmuma ID',
    'LBL_CURRENCY_RATE' => 'Valūtas kurss',
    'LBL_CURRENCY_ID' => 'Valūtas ID',
    'LBL_CURRENCY_NAME' => 'Valūtas nosaukums',
    'LBL_CURRENCY_SYMBOL' => 'Valūtas simbols',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Iespēja - Atjaunināt valūtu',
    'UPDATE_DOLLARAMOUNTS' => 'Atjaunot summas ASV dolāros',
    'UPDATE_VERIFY' => 'Pārbaudīt summas',
    'UPDATE_VERIFY_TXT' => 'Pārbauda vai iespēju summu vērtības ir derīgi decimālie skaitļi, kuri satur tikai ciparus0-9) un decimālos punktus(.)',
    'UPDATE_FIX' => 'Izlabot summas',
    'UPDATE_FIX_TXT' => 'Mēģina izlabot jebkuras nepareizās summas, veidojot pareizu decimālo formu pašreizējai summai. Jebkura modificētā summa ir dublēta datubāzes laukā - amount_backup. Ja izpildot šo darbību, pamanāt kļūdas, pirms darbības atkārtotas izpildes atjaunojiet summas no dublējuma. Pretējā gadījumā dublējums var tikt pārrakstīts ar nederīgiem datiem.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Atjaunot pamatvalūtu summas iespējām, balstoties uz patreizējo valūtas kursu. Šī vērtība tiek lietota, lai aprēķinātu Grafikus un Saraksta skatījuma valūtu summas.',
    'UPDATE_CREATE_CURRENCY' => 'Veido jaunu valūtu:',
    'UPDATE_VERIFY_FAIL' => 'Ieraksta pārbaude neveiksmīga:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Patreizējā summa:',
    'UPDATE_VERIFY_FIX' => 'Pēc izlabošanas iegūsim',
    'UPDATE_INCLUDE_CLOSE' => 'Iekļaut aizvērtos ierakstus',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Jaunā summa:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Jaunā valūta:',
    'UPDATE_DONE' => 'Pabeigts',
    'UPDATE_BUG_COUNT' => 'Atrastas kļūdas, tika mēģināts tās izlabot:',
    'UPDATE_BUGFOUND_COUNT' => 'Atrastās kļūdas:',
    'UPDATE_COUNT' => 'Atjaunināti ieraksti:',
    'UPDATE_RESTORE_COUNT' => 'Reģistrētas atjaunotas summas:',
    'UPDATE_RESTORE' => 'Atjaunot summas',
    'UPDATE_RESTORE_TXT' => 'Atjauno summu vērtības no labošanas laikā veidotajiem dublējumiem.',
    'UPDATE_FAIL' => 'Nav iespējams atjaunināt -',
    'UPDATE_NULL_VALUE' => 'Summa ir NULL, tiek iestatīta uz 0 -',
    'UPDATE_MERGE' => 'Sapludināt valūtas',
    'UPDATE_MERGE_TXT' => 'Sapludināt vairāku valūtu informāciju vienā valūtā. Ja ir vairāki ieraksti vienai un tai pašai valūtai, tie tiks sapludināti kopā. Rezultātā notiks valūtu sapludināšana arī visos citos moduļos.',
    'LBL_ACCOUNT_NAME' => 'Uzņēmuma nosaukums:',
    'LBL_CURRENCY' => 'Valūta:',
    'LBL_DATE_CLOSED' => 'Plānotais slēgšanas datums:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Plānotā slēgšanas datuma laikspiedols',
    'LBL_TYPE' => 'Veids',
    'LBL_CAMPAIGN' => 'Kampaņa:',
    'LBL_NEXT_STEP' => 'Nākamā darbība:',
    'LBL_LEAD_SOURCE' => 'Interesenta avots',
    'LBL_SALES_STAGE' => 'Pārdošanas posms:',
    'LBL_SALES_STATUS' => 'Statuss',
    'LBL_PROBABILITY' => 'Varbūtība (%):',
    'LBL_DESCRIPTION' => 'Apraksts:',
    'LBL_DUPLICATE' => 'Varbūtējs iespējas dublikāts',
    'MSG_DUPLICATE' => 'Veidojamā iespēja varbūt ir dublikāts citai iespējai. Līdzīgi nosauktās iespējas uzskaitītas zemāk.<br>Klikšķiniet saglabāt, lai turpinātu veidot šo iespēju, vai klikšķiniet Atcelt, lai atgrieztos modulī, neveidojot iespēju.',
    'LBL_NEW_FORM_TITLE' => 'Izveidot iespēju',
    'LNK_NEW_OPPORTUNITY' => 'Izveidot iespēju',
    'LNK_CREATE' => 'Izveidot darījumu',
    'LNK_OPPORTUNITY_LIST' => 'Aplūkot iespējas',
    'ERR_DELETE_RECORD' => 'Lai dzēstu iespēju, jānorāda ieraksta numurs.',
    'LBL_TOP_OPPORTUNITIES' => 'Manas top atvērtās iespējas',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Vai jūs tiešām vēlaties izņemt šo kontaktpersonu no iespējas?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Vai jūs vēlāties izņemt šo iespēju no projekta?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Iespējas',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Darbības',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Vēsture',
    'LBL_RAW_AMOUNT' => 'Neapstrādāta summa',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Interesenti',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktpersonas',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokumenti',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekti',
    'LBL_ASSIGNED_TO_NAME' => 'Piešķirts lietotājam:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Piešķirtais lietotājs',
    'LBL_LIST_SALES_STAGE' => 'Pārdošanas posms',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Manas aizvērtās iespējas',
    'LBL_TOTAL_OPPORTUNITIES' => 'Iespēju kopskaits',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Aizvērtās izcīnītās iespējas',
    'LBL_ASSIGNED_TO_ID' => 'Piešķirts lietotājam:',
    'LBL_CREATED_ID' => 'Izveidotāja ID',
    'LBL_MODIFIED_ID' => 'Modificētāja ID',
    'LBL_MODIFIED_NAME' => 'Modificēja lietotājs',
    'LBL_CREATED_USER' => 'Izveidoja lietotājs',
    'LBL_MODIFIED_USER' => 'Modificēja lietotājs',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Kampaņas',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projekti',
    'LABEL_PANEL_ASSIGNMENT' => 'Piešķīrums',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importēt iespējas',
    'LBL_EDITLAYOUT' => 'Rediģēt izkārtojumu' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Kampaņas ID',
    'LBL_OPPORTUNITY_TYPE' => 'Iespējas tips',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Piešķirts lietotājam',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Piešķirts lietotājam ID',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificētāja ID',
    'LBL_EXPORT_CREATED_BY' => 'Izveidotāja ID',
    'LBL_EXPORT_NAME' => 'Nosaukums',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Saistīto kontaktpersonu e-pasti',
    'LBL_FILENAME' => 'Pielikums',
    'LBL_PRIMARY_QUOTE_ID' => 'Sākotnējais piedāvājums',
    'LBL_CONTRACTS' => 'Līgumi',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Līgumi',
    'LBL_PRODUCTS' => 'Produkti',
    'LBL_RLI' => 'Ieņēmumu posteņi',
    'LNK_OPPORTUNITY_REPORTS' => 'Aplūkot iespēju atskaites',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Piedāvājumi',
    'LBL_TEAM_ID' => 'Darba grupas ID',
    'LBL_TIMEPERIODS' => 'Laika periodi',
    'LBL_TIMEPERIOD_ID' => 'Laika perioda ID',
    'LBL_COMMITTED' => 'Iesniegts',
    'LBL_FORECAST' => 'Iekļatu prognozē',
    'LBL_COMMIT_STAGE' => 'Iesniegt posmu',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prognoze',
    'LBL_WORKSHEET' => 'Darblapa',

    'TPL_RLI_CREATE' => 'Iespējai jābūt piesaistītam Ieņēmumu postenim. <a href="javascript:void(0);" id="createRLI"Izveidot Ieņēmumu posteni</a>.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Izveidot ieņēmumu posteni.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Piedāvājuma rindas',
    'LBL_RLI_SUBPANEL_TITLE' => 'Ieņēmumu posteņi',

    'LBL_TOTAL_RLIS' => '# no visiem ieņēmumu posteņiem',
    'LBL_CLOSED_RLIS' => '# no slēgtajiem ieņēmumu posteņiem',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Jūs nevarat dzēst iespējas, kuras satur aizvērtus ieņēmumu posteņus',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Viens vai vairāki no atlasītajiem ierakstiem satur aizvērtus ieņēmumu posteņus un nevar tikt dzēsti.',
    'LBL_INCLUDED_RLIS' => '# no ietvertajiem ieņēmumu posteņiem',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Piedāvājumi',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Iespējas hierarhija',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Iespēju ierakstos plānoto slēgšanas datumu uzstādiet tā lai tie atbilst esošo ieņēmumu rindu agrākajam vai vēlākajam slēgšanas datumam.',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Piltuves kopsumma ir',

    'LBL_OPPORTUNITY_ROLE'=>'Iespējas loma',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Piezīmes',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Spiežot apstiprināt tiks izdzēsti VISI prognožu dati un mainīts jūsu Pārdošanas iespēju skats. Ja tas nav, tas ko vēlaties, spiediet Atcelt un atgriezties pie iepriekšējiem uzstādījumiem.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Noklikšķinot uz "Apstiprināt", tiks izdzēsti VISI prognozes dati, kā arī mainīts Darījumu skatījums. '
        .'Tāpat tiks atspējotas VISAS procesu definīcijas ar ieņēmumu posteņu mērķa moduli. '
        .'Ja tas nav tas, ko vēlējāties, spiediet Atcelt un atgriezties pie iepriekšējiem iestatījumiem.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Ja visas ieņēmumu rindas ir aizvērtas un vismaz viena vinnēta,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'iespējas pārdošanas fāze tiek iestatīta uz "Aizvērts - iegūts"',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Ja visas ieņēmumu rindas ir statusā Aizvērts zaudēts',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'iespējas pārdošanas fāze tiek iestatīta uz "Aizvērts - zaudēts"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Ja visas ieņēmumu rindas ir joproijām atvērtas,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'iespēja pārdošanas fāze tiek iestatīta uz zemāko pieejamo.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Pēc tam, kad uzsāksiet šo izmaiņu, fonā tiks izveidotas ieņēmumu posteņu kopsavilkuma piezīmes. Tad, kad piezīmes būs pabeigtas un pieejamas, paziņojums tiks nosūtīts uz e-pasta adresi jūsu lietotāja profilā. Ja jūsu instancē ir iestatīts {{forecasts_module}}, Sugar nosūtīs jums arī paziņojumu, kad jūsu {{module_name}} ieraksti tiks sninhronizēti ar {{forecasts_module}} moduli un būs pieejami jaunam {{forecasts_module}}. Lūdzu, ņemiet vērā, ka jūsu instancei ir jābūt konfigurētai uz e-pastu nosūtīšanu caur Admin > e-pasta iestatījumiem, lai paziņojumi tiktu nosūtīti.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Pēc tam, kad jūs uzsāksiet šo izmaiņu, fonā tiks izveidoti ieņēmumu posteņu ieraksti katram esošajam {{module_name}}. Kad ieņēmumu posteņi tiks pabeigti un būs pieejami, paziņojums tiks nosūtīts uz e-pasta adresi jūsu lietotāja profilā. Lūdzu, ņemiet vērā, ka jūsu instancei ir jābūt konfigurētai uz e-pastu nosūtīšanu caur Admin > e-pasta iestatījumiem, lai paziņojumi tiktu nosūtīti.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modulis {{plural_module_name}} ļauj sekot atsevišķiem pārdošanas darījumiem no sākuma līdz beigām. Katrs {{module_name}} ieraksts ir kāda produkta potenciāls pārdošanas darījums, un tas ietver atbilstošu pārdošanas informāciju, kā arī ir saistīts ar citiem svarīgiem ierakstiem, piemēram, moduļiem {{quotes_module}}, {{contacts_module}} u. c. Modulis {{module_name}} parasti iziet vairākus pārdošanas posmus, līdz tas tiek atzīmēts kā „Aizvērts-noslēgts” vai „Aizvērts-zaudēts”. Moduļa {{plural_module_name}} izmantošanu var vēl vairāk uzlabot, izmantojot „Sugar” moduli {{forecasts_singular_module}}, ar kura palīdzību var saprast un prognozēt pārdošanas tendences, kā arī koncentrēt darbu uz pārdošanas kvotu sasniegšanu.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modulis {{plural_module_name}} ļauj sekot atsevišķiem pārdošanas darījumiem un tiem piederošajiem produktiem no sākuma līdz beigām. Katrs moduļa {{module_name}} ieraksts ir potenciāls pārdošanas darījums un ietver atbilstošus pārdošanas datus, kā arī ir saistīts ar citiem svarīgiem ierakstiem, piemēram, moduli {{quotes_module}}, {{contacts_module}}, utt.

- Rediģējiet ieraksta laukus, klikšķinot uz atsevišķā laika vai pogas Rediģēt.
- Apskatiet vai rediģējiet saites uz citiem ierakstiem, apakšpaneļos ieslēdzot datu skatu kreisajā apakšējā panelī.
- Veidojiet un apskatiet lietotāju komentārus un ierakstu izmaiņu vēsturi modulī {{activitystream_singular_module}}, ieslēdzot darbību plūsmu kreisajā apakšējā panelī.
- Sekojiet šim ierakstam vai pievienojiet to izlasei, izmantojot ikonas pa labi no ieraksta nosaukuma.
- Papildu darbības ir pieejamas nolaižamajā darbību izvēlnē pa labi no pogas Rediģēt.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Modulis {{plural_module_name}} ļauj sekot atsevišķiem pārdošanas darījumiem un tiem piederošajiem produktiem no sākuma līdz beigām. Katrs moduļa {{module_name}} ieraksts ir potenciāls pārdošanas darījums un ietver atbilstošus pārdošanas datus, kā arī ir saistīts ar citiem svarīgiem ierakstiem, piemēram, moduli {{quotes_module}}, {{contacts_module}}, utt.

Lai izveidotu moduli {{module_name}}:
1. Ievadiet atbilstošos laukos vērtības.
 - Pirms saglabāšanas ir jāaizpilda lauki, kuri ir atzīmēti kā obligāti.
 - Ja nepieciešams redzēt papildu laukus spiediet, "Rādīt vairāk".
2. Spiediet "Saglabāt", lai pabeigtu jauna ieraksta izveidi un atgrieztos iepriekšējā lapā.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sinhronizēt ar Marketo&reg;',
    'LBL_MKTO_ID' => 'Marketo Lead ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Top 10 Pārdošanas iespējas',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Attēlo 10 labākas iespējas burbuļu diagrammā.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Manas Iespējas',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Manas darba grupas iespējas",
);
