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

$mod_strings = array (

    // Dashboard Names
    'LBL_FORECASTS_DASHBOARD' => 'Dashboard prognoses',

    //module strings.
    'LBL_MODULE_NAME' => 'Forecasts',
    'LBL_MODULE_NAME_SINGULAR' => 'Forecast',
    'LNK_NEW_OPPORTUNITY' => 'Nieuwe opportunity',
    'LBL_MODULE_TITLE' => 'Forecasts',
    'LBL_LIST_FORM_TITLE' => 'Committed forecasts',
    'LNK_UPD_FORECAST' => 'Forecastwerkblad',
    'LNK_QUOTA' => 'Bekijk quotas',
    'LNK_FORECAST_LIST' => 'Bekijk forecast historie',
    'LBL_FORECAST_HISTORY' => 'Forecasts: historie',
    'LBL_FORECAST_HISTORY_TITLE' => 'Geschiedenis',

    //var defs
    'LBL_TIMEPERIOD_NAME' => 'Periode',
    'LBL_USER_NAME' => 'Gebruiker',
    'LBL_REPORTS_TO_USER_NAME' => 'Rapporteert aan',

    //forecast table
    'LBL_FORECAST_ID' => 'Voorspellings-ID',
    'LBL_FORECAST_TIME_ID' => 'Periode ID',
    'LBL_FORECAST_TYPE' => 'Forecast type',
    'LBL_FORECAST_OPP_COUNT' => 'Opportunities',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => 'Aantal opportunities in pijplijn',
    'LBL_FORECAST_OPP_WEIGH'=> 'Gewogen bedrag',
    'LBL_FORECAST_OPP_COMMIT' => 'Meest waarschijnlijke scenario',
    'LBL_FORECAST_OPP_BEST_CASE'=>'Beste scenario',
    'LBL_FORECAST_OPP_WORST'=>'Slechtste scenario',
    'LBL_FORECAST_USER' => 'Gebruiker',
    'LBL_DATE_COMMITTED'=> 'Datum committed',
    'LBL_DATE_ENTERED' => 'Datum ingevoerd',
    'LBL_DATE_MODIFIED' => 'Datum gewijzigd',
    'LBL_CREATED_BY' => 'Gemaakt door',
    'LBL_DELETED' => 'Verwijderd',
    'LBL_MODIFIED_USER_ID'=>'Gewijzigd door',
    'LBL_WK_VERSION' => 'Versie',
    'LBL_WK_REVISION' => 'Revisie:',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => 'Periode:',
    'LBL_QC_OPPORTUNITY_COUNT' => 'Opportunity aantal:',
    'LBL_QC_WEIGHT_VALUE' => 'Gewogen bedrag:',
    'LBL_QC_COMMIT_VALUE' => 'Committed bedrag:',
    'LBL_QC_COMMIT_BUTTON' => 'Toewijzen',
    'LBL_QC_WORKSHEET_BUTTON' => 'Werkblad',
    'LBL_QC_ROLL_COMMIT_VALUE' => 'Rollup commit bedrag:',
    'LBL_QC_DIRECT_FORECAST' => 'Mijn directe forecast:',
    'LBL_QC_ROLLUP_FORECAST' => 'Mijn groepsforecast:',
    'LBL_QC_UPCOMING_FORECASTS' => 'Mijn forecasts',
    'LBL_QC_LAST_DATE_COMMITTED' => 'Laatste datum commit:',
    'LBL_QC_LAST_COMMIT_VALUE' => 'Laatste bedrag commit:',
    'LBL_QC_HEADER_DELIM'=> 'Aan',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "Opportunity",
    'LBL_OW_ACCOUNTNAME' => "Organisatie",
    'LBL_OW_REVENUE' => "Bedrag",
    'LBL_OW_WEIGHTED' => "Gewogen bedrag",
    'LBL_OW_MODULE_TITLE'=> 'Opportunitywerkblad',
    'LBL_OW_PROBABILITY'=>'Waarschijnlijkheid',
    'LBL_OW_NEXT_STEP'=>'Volgende stap',
    'LBL_OW_DESCRIPTION'=>'Beschrijving',
    'LBL_OW_TYPE'=>'Type',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'Rapporteerder',
    'LBL_FDR_OPPORTUNITIES'=>'Opportunities in forecast:',
    'LBL_FDR_WEIGH'=>'Gewogen bedrag van opportunities:',
    'LBL_FDR_COMMIT'=>'Committed bedrag',
    'LBL_FDR_DATE_COMMIT'=>'Commit datum',

    //detail view.
    'LBL_DV_HEADER' => 'Forecasts: werkblad',
    'LBL_DV_MY_FORECASTS' => 'Mijn forecasts',
    'LBL_DV_MY_TEAM' => "Mijn team&#39;s forecasts" ,
    'LBL_DV_TIMEPERIODS' => 'Perioden:',
    'LBL_DV_FORECAST_PERIOD' => 'Forecastperiode',
    'LBL_DV_FORECAST_OPPORTUNITY' => 'Forecast opportunities',
    'LBL_SEARCH' => 'Selecteer',
    'LBL_SEARCH_LABEL' => 'Selecteer',
    'LBL_COMMIT_HEADER' => 'Forecast commit',
    'LBL_DV_LAST_COMMIT_DATE' =>'Laatste datum commit:',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'Laatste commit bedrag:',
    'LBL_DV_FORECAST_ROLLUP' => 'Forecast rollup',
    'LBL_DV_TIMEPERIOD' => 'Periode:',
    'LBL_DV_TIMPERIOD_DATES' => 'Datum reeks:',
    'LBL_LOADING_COMMIT_HISTORY' => 'Laden commit historie...',

    //list view
    'LBL_LV_TIMPERIOD'=> 'Periode',
    'LBL_LV_TIMPERIOD_START_DATE'=> 'Begindatum',
    'LBL_LV_TIMPERIOD_END_DATE'=> 'Einddatum',
    'LBL_LV_TYPE'=> 'Forecast type',
    'LBL_LV_COMMIT_DATE'=> 'Datum committed',
    'LBL_LV_OPPORTUNITIES'=> 'Opportunities',
    'LBL_LV_WEIGH'=> 'Gewogen bedrag',
    'LBL_LV_COMMIT'=> 'Committed bedrag',

    'LBL_COMMIT_NOTE' => 'Geef de bedragen in waarvoor u commitment wilt afgeven in de gekozen tijdsperiode:',
    'LBL_COMMIT_TOOLTIP' => 'Om commit in te schakelen: wijzig een waarde in het werkblad',
    'LBL_COMMIT_MESSAGE' => 'Wilt u voor deze bedragen commitment vastleggen?',
    'ERR_FORECAST_AMOUNT' => 'Het commit bedrag is verplicht en moet numeriek zijn.',

    // js error strings
    'LBL_FC_START_DATE' => 'Startdatum',
    'LBL_FC_USER' => 'Gepland voor',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'Geen actieve perioden aanwezig voor forecasting',
    'LBL_FDR_ADJ_AMOUNT'=>'Aangepast bedrag',
    'LBL_SAVE_WOKSHEET'=>'Werkblad opslaan',
    'LBL_RESET_WOKSHEET'=>'Werkblad opnieuw instellen',
    'LBL_SHOW_CHART'=>'Bekijk grafiek',
    'LBL_RESET_CHECK'=>'Alle werkbladgegevens voor deze periode en gebruiker zullen worden verwijderd. Doorgaan?',

    'LB_FS_LIKELY_CASE'=>'Waarschijnlijk geval',
    'LB_FS_WORST_CASE'=>'Slechtste geval',
    'LB_FS_BEST_CASE'=>'Beste geval',
    'LBL_FDR_WK_LIKELY_CASE'=>'Schatting meest waarschijnlijk',
    'LBL_FDR_WK_BEST_CASE'=> 'Schatting beste case',
    'LBL_FDR_WK_WORST_CASE'=>'Schatting slechtste geval',
    'LBL_FDR_C_BEST_CASE'=>'Beste geval',
    'LBL_FDR_C_WORST_CASE'=>'Slechtste geval',
    'LBL_FDR_C_LIKELY_CASE'=>'Waarschijnlijk geval',
    'LBL_QC_LAST_BEST_CASE'=>'Laatste commit bedrag (beste geval):',
    'LBL_QC_LAST_LIKELY_CASE'=>'Laatste commit bedrag (waarschijnlijk geval):',
    'LBL_QC_LAST_WORST_CASE'=>'Laatste commit bedrag (slechtste geval):',
    'LBL_QC_ROLL_BEST_VALUE'=>'Rollup commit bedrag (beste geval):',
    'LBL_QC_ROLL_LIKELY_VALUE'=>'Rollup commit Bedrab (waarschijnlijkgGeval):',
    'LBL_QC_ROLL_WORST_VALUE'=>'Rollup commit bedrag (slechtste geval):',
    'LBL_QC_COMMIT_BEST_CASE'=>'Commit bedrag (beste geval):',
    'LBL_QC_COMMIT_LIKELY_CASE'=>'Commit bedrag (waarschijnlijk Gevag):',
    'LBL_QC_COMMIT_WORST_CASE'=>'Commit bedrag (slechtste geval):',
    'LBL_CURRENCY' => 'Valuta',
    'LBL_CURRENCY_ID' => 'Valuta ID',
    'LBL_CURRENCY_RATE' => 'Valutakoers',
    'LBL_BASE_RATE' => 'Basiskoers',

    'LBL_QUOTA' => 'Quota',
    'LBL_QUOTA_ADJUSTED' => 'Quota (aangepast)',

    'LBL_FORECAST_FOR'=>'Forecastwerkblad voor:',
    'LBL_FMT_ROLLUP_FORECAST'=>'(Rollup)',
    'LBL_FMT_DIRECT_FORECAST'=>'(Direct)',

    //labels used by the chart.
    'LBL_GRAPH_TITLE'=>'Forecasthistorie',
    'LBL_GRAPH_QUOTA_ALTTEXT'=>'Quota voor %s',
    'LBL_GRAPH_COMMIT_ALTTEXT'=>'Vastgelegd bedrag voor %s',
    'LBL_GRAPH_OPPS_ALTTEXT'=>'Waarde van gewonnen opportunities in %s',

    'LBL_GRAPH_QUOTA_LEGEND'=>'Quota',
    'LBL_GRAPH_COMMIT_LEGEND'=>'Committed forecast',
    'LBL_GRAPH_OPPS_LEGEND'=>'Gewonnen opportunities',
    'LBL_TP_QUOTA'=>'Quota:',
    'LBL_CHART_FOOTER'=>'Forecasthistorie<br/>Quota versus Bedrag Forecast versus Gewonnen Opportunities Waarde',
    'LBL_TOTAL_VALUE'=>'Totalen:',
    'LBL_COPY_AMOUNT'=>'Totaal bedrag',
    'LBL_COPY_WEIGH_AMOUNT'=>'Totaal gewogen',
    'LBL_WORKSHEET_AMOUNT'=>'Totaal schatting',
    'LBL_COPY'=>'Kopieer waarden',
    'LBL_COMMIT_AMOUNT'=>'Som van de committed waarden',
    'LBL_CUMULATIVE_TOTAL'=>'Cumulatief totaal',
    'LBL_COPY_FROM'=>'Kopieer waarde van:',

    'LBL_CHART_TITLE'=>'Quota versus Comitted versus Actueel',

    'LBL_FORECAST' => 'Forecast',
    'LBL_COMMIT_STAGE' => 'Commitment stadium',
    'LBL_SALES_STAGE' => 'Stadium',
    'LBL_AMOUNT' => 'Bedrag',
    'LBL_PERCENT' => 'Percentage',
    'LBL_DATE_CLOSED' => 'Verwachte afsluitdatum:',
    'LBL_PRODUCT_ID' => 'Product-ID',
    'LBL_QUOTA_ID' => 'Quota ID',
    'LBL_VERSION' => 'Versie',
    'LBL_CHART_BAR_LEGEND_CLOSE' => 'Balklegenda verbergen',
    'LBL_CHART_BAR_LEGEND_OPEN' => 'Balklegenda tonen',
    'LBL_CHART_LINE_LEGEND_CLOSE' => 'Regellegenda verbergen',
    'LBL_CHART_LINE_LEGEND_OPEN' => 'Regellegenda tonen',

    //Labels for forecasting history log and endpoint
    'LBL_ERROR_NOT_MANAGER' => 'Error: gebruiker {0} heeft geen toegang om forecasts aan te vragen voor {1}',
    'LBL_UP' => 'omhoog',
    'LBL_DOWN' => 'omlaag',
    'LBL_PREVIOUS_COMMIT' => 'Laatste commitment',

    'LBL_COMMITTED_HISTORY_SETUP_FORECAST' => 'Forecast instellingen',
    'LBL_COMMITTED_HISTORY_UPDATED_FORECAST' => 'Vernieuwde forecast',
    'LBL_COMMITTED_HISTORY_1_SHOWN' => '{{{intro}}} {{{first}}}',
    'LBL_COMMITTED_HISTORY_2_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}',
    'LBL_COMMITTED_HISTORY_3_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}, en {{{third}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_CHANGED' => 'meest waarschijnlijk {{{direction}}} {{{from}}} naar {{{to}}}',
    'LBL_COMMITTED_HISTORY_BEST_CHANGED' => 'beste {{{direction}}} {{{from}}} naar {{{to}}}',
    'LBL_COMMITTED_HISTORY_WORST_CHANGED' => 'slechtste {{{direction}}} {{{from}}} naar {{{to}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_SAME' => 'meest waarschijnlijk is gelijk gebleven',
    'LBL_COMMITTED_HISTORY_BEST_SAME' => 'beste is gelijk gebleven',
    'LBL_COMMITTED_HISTORY_WORST_SAME' => 'slechtste is gelijk gebleven',


    'LBL_COMMITTED_THIS_MONTH' => 'Deze maand op {0}',
    'LBL_COMMITTED_MONTHS_AGO' => '{0} maanden geleden op {1}',

    //Labels for jsTree implementation
    'LBL_TREE_PARENT' => 'Bovenliggend',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{0} (eigen)',

    //Labels for worksheet items
    'LBL_EXPECTED_OPPORTUNITIES' => 'Verwachte opportunities',
    'LBL_DISPLAYED_TOTAL' => 'Getoond totaal',
    'LBL_TOTAL' => 'Totaal',
    'LBL_OVERALL_TOTAL' => 'Eindtotaal',
    'LBL_EDITABLE_INVALID' => 'Ongeldige waarde voor {0}',
    'LBL_EDITABLE_INVALID_RANGE' => 'Waarde moet liggen tussen {0} en {1}',
    'LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD' => 'U heeft wijzigingen doorgevoerd in uw werkblad die nog niet zijn opgeslagen',
    'LBL_WORKSHEET_EXPORT_CONFIRM' => 'Let op: alleen opgeslagen of gecommitteerde data kan geëxporteerd worden. Klik op akkoord om door te gaan met exporteren of klik op annuleren om terug te gaan naar de werkmap.',
    'LBL_WORKSHEET_ID' => 'Werkblad ID',

    // Labels for Chart Options
    'LBL_DATA_SET' => 'Data Set',
    'LBL_GROUP_BY' => 'Groeperen op',
    'LBL_CHART_OPTIONS' => 'Grafiek mogelijkheden',
    'LBL_CHART_AMOUNT' => 'Bedrag',
    'LBL_CHART_TYPE' => 'Type',

    // Labels for Data Filters
    'LBL_FILTERS' => 'Filters',

    // Labels for toggle buttons
    'LBL_MORE' => 'Meer',
    'LBL_LESS' => 'Minder',

    // Labels for Progress
    'LBL_PROJECTED' => 'Geprojecteerd',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_QUOTA' => 'Waarschijnlijk boven quotum',
    'LBL_DISTANCE_LEFT_LIKELY_TO_QUOTA' => 'Waarschijnlijk onder quotum',
    'LBL_DISTANCE_ABOVE_BEST_FROM_QUOTA' => 'Beste boven quotum',
    'LBL_DISTANCE_LEFT_BEST_TO_QUOTA' => 'Beste onder quotum',
    'LBL_DISTANCE_ABOVE_WORST_FROM_QUOTA' => 'Slechtste boven quotum',
    'LBL_DISTANCE_LEFT_WORST_TO_QUOTA' => 'Slechtste onder quotum',
    'LBL_CLOSED' => 'Afgesloten - Gewonnen',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_CLOSED' => 'Waarschijnlijk boven gesloten',
    'LBL_DISTANCE_LEFT_LIKELY_TO_CLOSED' => 'Waarschijnlijk onder gesloten',
    'LBL_DISTANCE_ABOVE_BEST_FROM_CLOSED' => 'Beste boven gesloten',
    'LBL_DISTANCE_LEFT_BEST_TO_CLOSED' => 'Beste onder gesloten',
    'LBL_DISTANCE_ABOVE_WORST_FROM_CLOSED' => 'Slechtste boven gesloten',
    'LBL_DISTANCE_LEFT_WORST_TO_CLOSED' => 'Slechtste onder gesloten',
    'LBL_REVENUE' => 'Opbrengst',
    'LBL_PIPELINE_REVENUE' => 'Pijplijn opbrengst',
    'LBL_PIPELINE_OPPORTUNITIES' => 'Pijplijn opportunities',
    'LBL_LOADING' => 'Bezig met laden',
    'LBL_IN_FORECAST' => 'Meenemen in forecast',

    // Actions Dropdown
    'LBL_ACTIONS' => 'Acties',
    'LBL_EXPORT_CSV' => 'Exporteer CSV',
    'LBL_CANCEL' => 'Annuleren',

    'LBL_CHART_FORECAST_FOR' => 'voor {0}',
    'LBL_FORECAST_TITLE' => 'forecast: {0}',
    'LBL_CHART_INCLUDED' => 'Inclusief',
    'LBL_CHART_NOT_INCLUDED' => 'Exclusief',
    'LBL_CHART_ADJUSTED' => '(Bijgesteld)',
    'LBL_SAVE_DRAFT' => 'Concept opslaan',
    'LBL_CHANGES_BY' => 'Wijzigingen door {0}',
    'LBL_FORECAST_SETTINGS' => 'Instellingen',

    // config panels strings
    'LBL_FORECASTS_CONFIG_TITLE' => 'forecast instellingen',

    'LBL_FORECASTS_MISSING_STAGE_TITLE' => 'Forecast configuratiefout:',
    'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES' => 'De forecast module is onjuist geconfigureerd en is niet langer beschikbaar. Verkoopstadium "Gesloten - Gewonnen" en "Gesloten - Verloren" ontbreken in de beschikbare waarden voor Verkoopstadia. Neem contact op met uw administrator.',
    'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE' => 'Forecast toegangsfout:',
    'LBL_FORECASTS_ACLS_NO_ACCESS_MSG' => 'U heeft geen toegang tot de forecast module. Neem contact op met uw administrator.',

    'LBL_FORECASTS_RECORDS_ACLS_NO_ACCESS_MSG' => 'U heeft geen toegang tot de records van de module Voorspellingen. Neem contact op met uw beheerder.',

    // Panel and BreadCrumb Labels
    'LBL_FORECASTS_CONFIG_BREADCRUMB_WORKSHEET_LAYOUT' => 'Werkbladlayout',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES' => 'Bereik',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS' => 'Scenario&#39;s',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_TIMEPERIODS' => 'Perioden',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_VARIABLES' => 'Variabelen',

    // Admin UI
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_SETTINGS' => 'Forecast instellingen',
    'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS' => 'Periode',
    'LBL_FORECASTS_CONFIG_TITLE_RANGES' => 'Forecast bereik',
    'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS' => 'Scenario&#39;s',
    'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS' => 'Werkbladkolommen',
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_BY' => 'Toon forecastwerkbladen per',

    'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY' => 'Forecast per',

    'LBL_FORECASTS_CONFIG_TITLE_MESSAGE_TIMEPERIODS' => 'Begindatum van fiscaal jaar:',

    'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS' => 'Configureer de periode die gebruikt zal worden in de forecast module.<br><br>Let op dat de gekozen instellingen voor de perioden niet gewijzigd kunnen worden.<br><br>Begin door de begindatum van uw fiscale jaar te kiezen. Kies vervolgens de periode voor de forecast. Het bereik van de perioden zal automatisch berekend worden op basis van uw selecties. De onderliggende periode is de basis voor het forecastwerkblad.<br><br>De zichtbare toekomstige en historische perioden zullen het aantal van zichtbare onderliggende perioden bepalen in de forecast module. Gebruikers zijn in staat om de waarden te tonen en aan te passen in de zichtbare onderliggende perioden.',
    'LBL_FORECASTS_CONFIG_HELP_RANGES' => 'Configureer hoe u {{forecastByModule}} wilt indelen. <br><br>Houd er rekening mee dat de bereikinstellingen na de eerste toewijzing niet kunnen worden gewijzigd. Voor bijgewerkte gevallen is de bereikinstelling vergrendeld in de huidige voorspellingsgegevens.<br><br>U kunt twee of meer categorieën selecteren op basis van waarschijnlijkheidsbereik of u kunt categorieën aanmaken die niet zijn gebaseerd op waarschijnlijkheid. <br><br>Er zijn selectievakjes links van uw aangepaste categorieën. Deze kunt u gebruiken om te bepalen welk bereik wordt opgenomen in het gebruikte bedrag van de voorspelling en aan managers wordt gerapporteerd. <br><br>Een gebruiker kan de status insluiten/uitsluiten en de categorie {{forecastByModule}} handmatig aanpassen vanuit zijn/haar worksheet.',
    'LBL_FORECASTS_CONFIG_HELP_SCENARIOS' => 'Kies de kolommen die de gebruiker in moet vullen voor elke forecast van elke bij het voorspellen van {{forecastByModuleSingular}}. Let hierbij op dat de "Meest waarschijnlijke" waarde gekoppeld is aan de getoonde waarde bij {{forecastByModule}}; Vandaar dat de "Meest waarschijnlijke" kolom niet verborgen kan worden.',
    'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS' => 'Kies de kolommen die u wil zien in de forecast module. Het werkblad zal samengesteld worden uit de lijst met velden, waarbij de gebruiker kan kiezen hoe deze getoond wordt.',
    'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY' => 'Ik ben een placeholder voor "Hoe werkt &#39;Forecast door&#39;!"',
    'LBL_FORECASTS_CONFIG_SETTINGS_SAVED' => 'Forecast instellingen zijn opgeslagen',

    // timeperiod config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_SETUP_NOTICE' => 'Let op: de periode-instellingen zijn niet meer wijzigbaar na het opslaan.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_DESC' => 'Configueer de periodes waarover gebruikers forecast kunnen maken',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_TYPE' => 'Selecteer het type jaar dat uw organisatie gebruikt voor de boekhouding.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD' => 'Kies de periodes waarover u wilt gaan forecasten:',
    'LBL_FORECASTS_CONFIG_LEAFPERIOD' => 'Kies de deelperiode die u wilt tonen in uw periode:',
    'LBL_FORECASTS_CONFIG_START_DATE' => 'Kies de datum waarmee uw periodes jaarlijks mee beginnen:',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_FORWARD' => 'Kies het aantal toekomstige periodes die zichtbaar in het werkblad.<br><i>Dit aantal is van toepassing op de gekozen basis periode. Bijvoorbeeld door 2 te kiezen met een jaarlijkse periode, zullen 8 toekomstige kwartalen getoond worden.</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_BACKWARD' => 'Selecteer het aantal afgelopen tijdsperiodes die in het werkblad kunnen worden weergegeven.<br><i>Dit aantal is van toepassing op de geselecteerde basis tijdsperiode. Bijvoorbeeld door 2 te kiezen met een driemaandelijkse periode, zal de afgelopen 6 maanden getoond worden</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_FISCAL_YEAR' => 'De gekozen startdatum geeft aan dat het fiscale jaar over twee jaar uitgespreid is. Kies welk jaar als fiscaal jaar gekozen moet worden.',
    'LBL_FISCAL_YEAR' => 'Fiscaal jaar',

    // worksheet layout config
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_TEXT' => 'Kies hoe het forecast werkblad gevuld moet worden:',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_OPPORTUNITIES' => 'Opportunities',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_PRODUCT_LINE_ITEMS' => 'Opportunityregels',
    'LBL_REVENUELINEITEM_NAME' => 'Naam van de opportunityregel',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LAYOUT_DETAIL_MESSAGE' => 'Werkbladen worden samengesteld uit:',

    // ranges config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_RANGES_SETUP_NOTICE' => 'Let op: de bereik-pagina is niet meer wijzigbaar na de eerste &#39;commit&#39; of opslag als concept; Voor een bijgewerkte instatie, kunnen de bereiken niet gewijzigd worden na de eerste opslag, omdat er forecastgegevens zijn gemigreerd vanuit de oudere instantie.',
    'LBL_FORECASTS_CONFIG_RANGES' => 'Opties voor forecast bereik:',
    'LBL_FORECASTS_CONFIG_RANGES_OPTIONS' => 'Selecteer de wijze waarop u e {{forecastByModule}}. wil categoriseren.',
    'LBL_FORECASTS_CONFIG_SHOW_BINARY_RANGES_DESCRIPTION' => 'Deze optie geeft de gebruiker de keuzemogelijkheid om (niet) meegenomen te worden in een forecast.',
    'LBL_FORECASTS_CONFIG_SHOW_BUCKETS_RANGES_DESCRIPTION' => 'Deze optie geeft een gebruiker de mogelijkheid om hun {{forecastByModule}} te categoriseren die die niet zijn meegenomen in de commit, maar positief lijken uit te vallen en een grote slagingskans hebben en de {{forecastByModule}} zijn uitgesloten van de forecast.',
    'LBL_FORECASTS_CONFIG_SHOW_CUSTOM_BUCKETS_RANGES_DESCRIPTION' => 'Custom bereik: Deze optie geeft de gebruiker de mogelijkheid om de opportunities die vastgelegd gaan worden te categoriseren volgens een bereik, uitgesloten bereik en andere categoriën die ingesteld zijn.',
    'LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO' => 'Het uitgesloten bereik ligt standaard van 0% tot het minimum van de voorgaande forecast bereik.',

    'LBL_FORECASTS_CONFIG_RANGES_ENTER_RANGE' => 'Voer naam van het bereik in...',

    // scenarios config
    //TODO-sfa refactors the code references for scenarios to be scenarios (SFA-337).
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS' => 'Kies de scenario&#39;s die meegenomen moeten worden in het forecastwerkblad.',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LIKELY_INFO' => '&#39;Meest waarschijnlijk&#39; is gebaseerd op de waarde die in de {{forecastByModule}} is ingevuld.',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY' => 'Meest waarschijnlijk',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST' => 'Beste',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST' => 'Slechtste',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS' => 'Toon geprojecteerde scenario&#39;s in de totalen',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_LIKELY' => 'Toon &#39;Meest Waarschijnlijke&#39; scenario totalen',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_BEST' => 'Toon &#39;Beste&#39; scenario totalen',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_WORST' => 'Toon &#39;Slechtste&#39; scenario totalen',

    // variables config
    'LBL_FORECASTS_CONFIG_VARIABLES' => 'Variabelen',
    'LBL_FORECASTS_CONFIG_VARIABLES_DESC' => 'De formules voor de &#39;Metrics Table&#39; zijn gebaseerd op het verkoopstadium voor  {{forecastByModule}} die niet meegenomen moeten worden in de pijplijn. Bijv.  {{forecastByModule}} die zijn gesloten en verloren.',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_LOST_STAGE' => 'Selecteer het verkoopstadium dat gesloten en verloren  {{forecastByModule}} weergeeft:',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_WON_STAGE' => 'Selecteer het verkoopstadium dat gesloten en gewonnen  {{forecastByModule}} weergeeft:',
    'LBL_FORECASTS_CONFIG_VARIABLES_FORMULA_DESC' => 'Daarmee zal de pijplijnformule als volgt zijn:',

    'LBL_FORECASTS_WIZARD_SUCCESS_TITLE' => 'Geslaagd:',
    'LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE' => 'U heeft met succes de forecast module ingericht. Een moment geduld terwijl de module geladen wordt.',
    'LBL_FORECASTS_TABBED_CONFIG_SUCCESS_MESSAGE' => 'De instellingen zijn opgeslagen. Wacht terwijl de module opnieuw geladen wordt.',
    // Labels for Success Messages:
    'LBL_FORECASTS_WORKSHEET_SAVE_DRAFT_SUCCESS' => 'U heeft het forecastwerkblad voor de gekozen periode als concept opgeslagen.',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS' => 'U heeft het forecastwerkblad voor de gekozen periode vastgelegd.',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO' => 'U heeft commitment afgegeven op uw Forecast richting {{manager}}',

    // custom ranges
    'LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME' => 'Custom bereik',
    'LBL_UNAUTH_FORECASTS' => 'Geen toegang tot de forecast instellingen.',
    'LBL_FORECASTS_RANGES_BASED_TITLE' => 'Bereik gebaseerd op waarschijnlijkheid',
    'LBL_FORECASTS_CUSTOM_BASED_TITLE' => 'Custom bereik gebaseerd op waarschijnlijkheid',
    'LBL_FORECASTS_CUSTOM_NO_BASED_TITLE' =>'Bereik niet gebaseerd op waarschijnlijkheid',

    // worksheet columns config
    'LBL_DISCOUNT' => 'Korting',
    'LBL_OPPORTUNITY_STATUS' => 'Opportunity status',
    'LBL_OPPORTUNITY_NAME' => 'Opportunitynaam',
    'LBL_PRODUCT_TEMPLATE' => 'Productcatalogus',
    'LBL_CAMPAIGN' => 'Campagne',
    'LBL_TEAMS' => 'Teams',
    'LBL_CATEGORY' => 'Categorie',
    'LBL_COST_PRICE' => 'Kostprijs',
    'LBL_TOTAL_DISCOUNT_AMOUNT' => 'Totale hoeveelheid korting',
    'LBL_FORECASTS_CONFIG_WORKSHEET_TEXT' => 'Kies welke kolommen getoond moeten worden in de werkblad weergave. Standaard zullen de volgende velden geselecteerd zijn:',

    // forecast details dashlet
    'LBL_DASHLET_FORECAST_NOT_SETUP' => 'Forecasts is niet geconfigureerd en dient ingesteld te worden om gebruik te maken van deze widget. Neem contact op met uw administrator.',
    'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' => 'Forecasts is niet geconfigureerd en dient ingesteld te worden om gebruik te maken van deze widget.',
    'LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT' => 'Klik hier om de Forecast module te configureren.',
    'LBL_DASHLET_MY_PIPELINE' => 'Mijn salesfunnel',
    'LBL_DASHLET_MY_TEAMS_PIPELINE' => "De salesfunnel van mijn team",
    'LBL_DASHLET_PIPELINE_CHART_NAME' => 'Forecast pijplijn grafiek',
    'LBL_DASHLET_PIPELINE_CHART_DESC' => 'Toont huidige pijplijn grafiek.',
    'LBL_FORECAST_DETAILS_DEFICIT' => 'Tekort',
    'LBL_FORECAST_DETAILS_SURPLUS' => 'Overschot',
    'LBL_FORECAST_DETAILS_SHORT' => 'Verwacht tekort met',
    'LBL_FORECAST_DETAILS_EXCEED' => 'Overschreden met',
    'LBL_FORECAST_DETAILS_NO_DATA' => 'Geen gegevens',
    'LBL_FORECAST_DETAILS_MEETING_QUOTA' => 'Quota bereikt',

    'LBL_ASSIGN_QUOTA_BUTTON' => 'Quota toewijzen',
    'LBL_ASSIGNING_QUOTA' => 'Toewijzen van quota',
    'LBL_QUOTA_ASSIGNED' => 'Quota&#39;s zijn succesvol toegewezen',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_TITLE' => 'Forecast toegangsfout',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_MSG' => 'U heeft geen rechten om forecasts te configureren. Neem contact op met uw administrator.',
    'WARNING_DELETED_RECORD_RECOMMIT_1' => 'Dit record is opgenomen in een ',
    'WARNING_DELETED_RECORD_RECOMMIT_2' => 'Het zal worden verwijderd en u een nieuwe toewijzing moeten doen van uw ',

    'LBL_DASHLET_MY_FORECAST' => 'Mijn forecast',
    'LBL_DASHLET_MY_TEAMS_FORECAST' => "Forecast van mijn team",

    'LBL_WARN_UNSAVED_CHANGES_CONFIRM_SORT' => 'U staat op het punt om het record te verlaten zonder de wijzigingen op te slaan. Weet u zeker dat u dit wil?',

    // Forecasts Records View Help Text
    'LBL_HELP_RECORDS' => 'De {{plural_module_name}} module bestaat uit {{opportunities_singular_module}} records om {{module_name}} ing {{worksheet_module}} s op te zetten en sales te voorspellen. Gebruikers kunnen toewerken naar sales targets op individueel, team en organisatie niveau. Voordat gebruikers toegang krijgen tot de {{plural_module_name}} module, moet een gebruiker met de beheerdersrol de {{plural_module_name}} module configureren voor de gewenste Tijdsperiodes, Ranges en Scenario&#39;s. Accountmanagers gebruiken de {{plural_module_name}} module om gedurende de actuele tijdsperiode met hun toegewezen {{opportunities_module}} te werken. Deze gebruikers zullen de totale voorspellingen committeren gebaseerd op hun individuele sales van de {{opportunities_module}} die ze verwachten te sluiten. Sales managers werken met hun eigen {{opportunities_singular_module}} records vergelijkbaar met de accountmanagers. Bovendien verzamelen zij de gecommitteerde {{module_name}} targets van hun teamleden om hun eigen team sales totaal te voorspellen en toe te werken naar het team target voor elke periode. Additionele inzichten worden aangeboden door onderdelen van het Intelligence Pane, inclusief analyse voor een individuele {{opportunities_module}} worksheet en voor een managers&#39; team worksheets.'
);
