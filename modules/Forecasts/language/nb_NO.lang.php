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
    'LBL_FORECASTS_DASHBOARD' => 'Dashbord for prognoser',

    //module strings.
    'LBL_MODULE_NAME' => 'Prognoser',
    'LBL_MODULE_NAME_SINGULAR' => 'Prognose',
    'LNK_NEW_OPPORTUNITY' => 'Opprett mulighet',
    'LBL_MODULE_TITLE' => 'Prognoser',
    'LBL_LIST_FORM_TITLE' => 'Engasjerte prognoser',
    'LNK_UPD_FORECAST' => 'Arbeidsflate for prognoser',
    'LNK_QUOTA' => 'Kvoter',
    'LNK_FORECAST_LIST' => 'Prognosehistorikk',
    'LBL_FORECAST_HISTORY' => 'Prognoser: Historikk',
    'LBL_FORECAST_HISTORY_TITLE' => 'Prognoser: Historikk',

    //var defs
    'LBL_TIMEPERIOD_NAME' => 'Tidsperiode',
    'LBL_USER_NAME' => 'Brukernavn',
    'LBL_REPORTS_TO_USER_NAME' => 'Rapporterer til',

    //forecast table
    'LBL_FORECAST_ID' => 'ID',
    'LBL_FORECAST_TIME_ID' => 'Tidsperiode-ID',
    'LBL_FORECAST_TYPE' => 'Prognosetype',
    'LBL_FORECAST_OPP_COUNT' => 'Muligheter',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => 'Pipeline salgsmulighet antall',
    'LBL_FORECAST_OPP_WEIGH'=> 'Vektet mengde',
    'LBL_FORECAST_OPP_COMMIT' => 'Trolig utfall',
    'LBL_FORECAST_OPP_BEST_CASE'=>'Beste utfall',
    'LBL_FORECAST_OPP_WORST'=>'Verste utfall',
    'LBL_FORECAST_USER' => 'Bruker',
    'LBL_DATE_COMMITTED'=> 'Engasjeringsdato',
    'LBL_DATE_ENTERED' => 'Inngangsdato',
    'LBL_DATE_MODIFIED' => 'Endringsdato',
    'LBL_CREATED_BY' => 'Opprettet av',
    'LBL_DELETED' => 'Slettet',
    'LBL_MODIFIED_USER_ID'=>'Endret av',
    'LBL_WK_VERSION' => 'Versjon',
    'LBL_WK_REVISION' => 'Revisjon',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => 'Tidsperiode:',
    'LBL_QC_OPPORTUNITY_COUNT' => 'Mulighetsberegning:',
    'LBL_QC_WEIGHT_VALUE' => 'Vektet mengde:',
    'LBL_QC_COMMIT_VALUE' => 'Engasjeringsmengde:',
    'LBL_QC_COMMIT_BUTTON' => 'Engasjér',
    'LBL_QC_WORKSHEET_BUTTON' => 'Arbeidsflate',
    'LBL_QC_ROLL_COMMIT_VALUE' => 'Rollup-engajseringsmengde:',
    'LBL_QC_DIRECT_FORECAST' => 'Mine direkte prognoser:',
    'LBL_QC_ROLLUP_FORECAST' => 'Min grupeps prognoser:',
    'LBL_QC_UPCOMING_FORECASTS' => 'Mine prognoser',
    'LBL_QC_LAST_DATE_COMMITTED' => 'Siste engasjeringsdato:',
    'LBL_QC_LAST_COMMIT_VALUE' => 'Siste engasjeringsmengde:',
    'LBL_QC_HEADER_DELIM'=> 'Til',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "Mulighet",
    'LBL_OW_ACCOUNTNAME' => "Bedrift",
    'LBL_OW_REVENUE' => "Mengde",
    'LBL_OW_WEIGHTED' => "Vektet mengde",
    'LBL_OW_MODULE_TITLE'=> 'Arbeidsflate for mulighet',
    'LBL_OW_PROBABILITY'=>'Trolighet',
    'LBL_OW_NEXT_STEP'=>'Neste skritt',
    'LBL_OW_DESCRIPTION'=>'Beskrivelse',
    'LBL_OW_TYPE'=>'Type',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'Direketrapport',
    'LBL_FDR_OPPORTUNITIES'=>'Muligheter i prognose:',
    'LBL_FDR_WEIGH'=>'Vektet mulighetsmengde:',
    'LBL_FDR_COMMIT'=>'Engasjeringsmengde',
    'LBL_FDR_DATE_COMMIT'=>'Engasjeringsdato',

    //detail view.
    'LBL_DV_HEADER' => 'Prognoser: Arbeidsflate',
    'LBL_DV_MY_FORECASTS' => 'Mine prognoser',
    'LBL_DV_MY_TEAM' => "Min gruppes prognoser" ,
    'LBL_DV_TIMEPERIODS' => 'Tidsperioder:',
    'LBL_DV_FORECAST_PERIOD' => 'Tidsperiode for prognose',
    'LBL_DV_FORECAST_OPPORTUNITY' => 'Prognosemuligheter',
    'LBL_SEARCH' => 'Velg',
    'LBL_SEARCH_LABEL' => 'Velg',
    'LBL_COMMIT_HEADER' => 'Engasjér prognose',
    'LBL_DV_LAST_COMMIT_DATE' =>'Siste engasjeringsdato:',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'Siste engasjeringsmengde:',
    'LBL_DV_FORECAST_ROLLUP' => 'Prognose-rollup',
    'LBL_DV_TIMEPERIOD' => 'Tidsperiode:',
    'LBL_DV_TIMPERIOD_DATES' => 'Datoomfang',
    'LBL_LOADING_COMMIT_HISTORY' => 'Laster forpliktet historie...',

    //list view
    'LBL_LV_TIMPERIOD'=> 'Tidsperiode',
    'LBL_LV_TIMPERIOD_START_DATE'=> 'Startdato',
    'LBL_LV_TIMPERIOD_END_DATE'=> 'Sluttdato',
    'LBL_LV_TYPE'=> 'Prognosetype',
    'LBL_LV_COMMIT_DATE'=> 'Engasjeringsdato',
    'LBL_LV_OPPORTUNITIES'=> 'Muligheter',
    'LBL_LV_WEIGH'=> 'Vektet mengde',
    'LBL_LV_COMMIT'=> 'Engasjeringsmengde',

    'LBL_COMMIT_NOTE' => 'Velg den mengden som du vil engasjere for den valgte tidsperioden:',
    'LBL_COMMIT_TOOLTIP' => 'For å aktivere forpliktelse: Endre en verdi i regnearket',
    'LBL_COMMIT_MESSAGE' => 'Vil du engasjere disse mengdene?',
    'ERR_FORECAST_AMOUNT' => 'Engasjeringsmengden er påkrevd og må være et tall.',

    // js error strings
    'LBL_FC_START_DATE' => 'Startdato',
    'LBL_FC_USER' => 'Planlagt for',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'Ingen aktive tidsperioder for Prognose.',
    'LBL_FDR_ADJ_AMOUNT'=>'Justerte mengder',
    'LBL_SAVE_WOKSHEET'=>'Lagre arbeidsflate',
    'LBL_RESET_WOKSHEET'=>'Nullstill arbeidsflate',
    'LBL_SHOW_CHART'=>'Vis fremstilling',
    'LBL_RESET_CHECK'=>'Alle data for arbeidsflate for den valgte tidsperioden og innloggede bruker vil fjernes. Fortsett?',

    'LB_FS_LIKELY_CASE'=>'Trolig utfall',
    'LB_FS_WORST_CASE'=>'Verste utfall',
    'LB_FS_BEST_CASE'=>'Beste utfall',
    'LBL_FDR_WK_LIKELY_CASE'=>'Beregnet trolig utfall',
    'LBL_FDR_WK_BEST_CASE'=> 'Beregnet beste utfall',
    'LBL_FDR_WK_WORST_CASE'=>'Beregnet verste utfall',
    'LBL_FDR_C_BEST_CASE'=>'Beste utfall',
    'LBL_FDR_C_WORST_CASE'=>'Verste utfall:',
    'LBL_FDR_C_LIKELY_CASE'=>'Trolig utfall:',
    'LBL_QC_LAST_BEST_CASE'=>'Siste engasjeringsmengde (Beste utfall):',
    'LBL_QC_LAST_LIKELY_CASE'=>'Siste engasjeringsmengde (Trolig utfall):',
    'LBL_QC_LAST_WORST_CASE'=>'Siste engasjeringsmengde (Verste utfall):',
    'LBL_QC_ROLL_BEST_VALUE'=>'Rollup-engasjeringsmengde (Beste utfall):',
    'LBL_QC_ROLL_LIKELY_VALUE'=>'Rollup-engasjeringsmengde (Trolig utfall):',
    'LBL_QC_ROLL_WORST_VALUE'=>'Rollup-engasjeringsmengde (Verste utfall):',
    'LBL_QC_COMMIT_BEST_CASE'=>'Engasjeringsmengde (Beste utfall):',
    'LBL_QC_COMMIT_LIKELY_CASE'=>'Engasjeringsmengde (Trolig utfall):',
    'LBL_QC_COMMIT_WORST_CASE'=>'Engasjeringsmengde (Verste utfall):',
    'LBL_CURRENCY' => 'Valuta',
    'LBL_CURRENCY_ID' => 'Valuta ID',
    'LBL_CURRENCY_RATE' => 'Valutakurs',
    'LBL_BASE_RATE' => 'Grunnlagsrente',

    'LBL_QUOTA' => 'Budsjett',
    'LBL_QUOTA_ADJUSTED' => 'Budsjett (Justert)',

    'LBL_FORECAST_FOR'=>'Arbeidsflate for prognose:',
    'LBL_FMT_ROLLUP_FORECAST'=>'(Rollup)',
    'LBL_FMT_DIRECT_FORECAST'=>'(Direkte)',

    //labels used by the chart.
    'LBL_GRAPH_TITLE'=>'Prongnosehistorikk',
    'LBL_GRAPH_QUOTA_ALTTEXT'=>'Kvote for %s',
    'LBL_GRAPH_COMMIT_ALTTEXT'=>'Engasjeringsmengde for %s',
    'LBL_GRAPH_OPPS_ALTTEXT'=>'Mulighetsverdier lukket i %s',

    'LBL_GRAPH_QUOTA_LEGEND'=>'Kvote',
    'LBL_GRAPH_COMMIT_LEGEND'=>'Engasjert prognos',
    'LBL_GRAPH_OPPS_LEGEND'=>'Lukkede muligheter',
    'LBL_TP_QUOTA'=>'Kvote:',
    'LBL_CHART_FOOTER'=>'Prognosehistorikk<br/>Kvote vs Prognosemengde vs Lukkede mulighetsverdier',
    'LBL_TOTAL_VALUE'=>'Totale:',
    'LBL_COPY_AMOUNT'=>'Total mengde',
    'LBL_COPY_WEIGH_AMOUNT'=>'Total vektet mengde',
    'LBL_WORKSHEET_AMOUNT'=>'Total beregnet mengde',
    'LBL_COPY'=>'Kopiér verdier',
    'LBL_COMMIT_AMOUNT'=>'Summering av engasjerte verdier.',
    'LBL_CUMULATIVE_TOTAL'=>'Akkumulert Total',
    'LBL_COPY_FROM'=>'Kopiér verdi fra:',

    'LBL_CHART_TITLE'=>'Verdi vs. Engasjert vs. Faktisk',

    'LBL_FORECAST' => 'Prognose',
    'LBL_COMMIT_STAGE' => 'Forpliktet stadie',
    'LBL_SALES_STAGE' => 'Fase',
    'LBL_AMOUNT' => 'Beløp',
    'LBL_PERCENT' => 'Prosent',
    'LBL_DATE_CLOSED' => 'Forventet avslutningsdato',
    'LBL_PRODUCT_ID' => 'Produkt-ID',
    'LBL_QUOTA_ID' => 'Budsjett ID',
    'LBL_VERSION' => 'Versjon',
    'LBL_CHART_BAR_LEGEND_CLOSE' => 'Skjul strekforklaring',
    'LBL_CHART_BAR_LEGEND_OPEN' => 'Vis strekforklaring',
    'LBL_CHART_LINE_LEGEND_CLOSE' => 'Skjul linjeforklaring',
    'LBL_CHART_LINE_LEGEND_OPEN' => 'Vis linjeforklaring',

    //Labels for forecasting history log and endpoint
    'LBL_ERROR_NOT_MANAGER' => 'Feil: bruker {0} har ikke administratortilgang til å be om Prognoser for {1}',
    'LBL_UP' => 'opp',
    'LBL_DOWN' => 'ned',
    'LBL_PREVIOUS_COMMIT' => 'Seneste forpliktelse:',

    'LBL_COMMITTED_HISTORY_SETUP_FORECAST' => 'Prognose oppsett',
    'LBL_COMMITTED_HISTORY_UPDATED_FORECAST' => 'Oppdatert prognose',
    'LBL_COMMITTED_HISTORY_1_SHOWN' => '{{{intro}}} {{{first}}}',
    'LBL_COMMITTED_HISTORY_2_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}',
    'LBL_COMMITTED_HISTORY_3_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}, og {{{third}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_CHANGED' => 'sannsynlig {{{direction}}} {{{from}}} til {{{to}}}',
    'LBL_COMMITTED_HISTORY_BEST_CHANGED' => 'best {{{direction}}} {{{from}}} til {{{to}}}',
    'LBL_COMMITTED_HISTORY_WORST_CHANGED' => 'verst {{{direction}}} {{{from}}} til {{{to}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_SAME' => 'trolig forble det samme',
    'LBL_COMMITTED_HISTORY_BEST_SAME' => 'best forble det samme',
    'LBL_COMMITTED_HISTORY_WORST_SAME' => 'verst forble det samme',


    'LBL_COMMITTED_THIS_MONTH' => 'Denne måned på {0}',
    'LBL_COMMITTED_MONTHS_AGO' => '{0} måneder siden på {1}',

    //Labels for jsTree implementation
    'LBL_TREE_PARENT' => 'Overordnet',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{0} (me)',

    //Labels for worksheet items
    'LBL_EXPECTED_OPPORTUNITIES' => 'Forventede salgsmuligheter',
    'LBL_DISPLAYED_TOTAL' => 'Visning Totalt',
    'LBL_TOTAL' => 'Total',
    'LBL_OVERALL_TOTAL' => 'Samlet total',
    'LBL_EDITABLE_INVALID' => 'Ugyldig verdi for {0}',
    'LBL_EDITABLE_INVALID_RANGE' => 'Verdien må være mellom {0} og{1}',
    'LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD' => 'Du har ulagrede endringer i regnearket.',
    'LBL_WORKSHEET_EXPORT_CONFIRM' => 'Bare lagret eller forpliktet data vil bli eksportert. Avbryt for å avbryte. Bekreft for å eksportere lagrede data.',
    'LBL_WORKSHEET_ID' => 'Regneark ID',

    // Labels for Chart Options
    'LBL_DATA_SET' => 'Datasett:',
    'LBL_GROUP_BY' => 'Gruppér etter:',
    'LBL_CHART_OPTIONS' => 'Diagramvalg',
    'LBL_CHART_AMOUNT' => 'Beløp',
    'LBL_CHART_TYPE' => 'Type',

    // Labels for Data Filters
    'LBL_FILTERS' => 'Filtre',

    // Labels for toggle buttons
    'LBL_MORE' => 'Mer',
    'LBL_LESS' => 'Mindre',

    // Labels for Progress
    'LBL_PROJECTED' => 'Projisert',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_QUOTA' => 'Trolig over budsjett',
    'LBL_DISTANCE_LEFT_LIKELY_TO_QUOTA' => 'Trolig under budsjett',
    'LBL_DISTANCE_ABOVE_BEST_FROM_QUOTA' => 'Best over budsjett',
    'LBL_DISTANCE_LEFT_BEST_TO_QUOTA' => 'Best under budsjett',
    'LBL_DISTANCE_ABOVE_WORST_FROM_QUOTA' => 'Verst over budsjett',
    'LBL_DISTANCE_LEFT_WORST_TO_QUOTA' => 'Verst under budsjett',
    'LBL_CLOSED' => 'Lukket Vunnet',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_CLOSED' => 'Trolig over Lukket',
    'LBL_DISTANCE_LEFT_LIKELY_TO_CLOSED' => 'Trolig under Lukket',
    'LBL_DISTANCE_ABOVE_BEST_FROM_CLOSED' => 'Best over Lukket',
    'LBL_DISTANCE_LEFT_BEST_TO_CLOSED' => 'Best under Lukket',
    'LBL_DISTANCE_ABOVE_WORST_FROM_CLOSED' => 'Verst over Lukket',
    'LBL_DISTANCE_LEFT_WORST_TO_CLOSED' => 'Verst under Lukket',
    'LBL_REVENUE' => 'Omsetning',
    'LBL_PIPELINE_REVENUE' => 'Pipeline omsetning',
    'LBL_PIPELINE_OPPORTUNITIES' => 'Pipeline salgsmuligheter',
    'LBL_LOADING' => 'Laster inn',
    'LBL_IN_FORECAST' => 'I prognose',

    // Actions Dropdown
    'LBL_ACTIONS' => 'Handlinger',
    'LBL_EXPORT_CSV' => 'Eksporter CSV',
    'LBL_CANCEL' => 'Avbryt',

    'LBL_CHART_FORECAST_FOR' => 'for {0}',
    'LBL_FORECAST_TITLE' => 'Prognose: {0}',
    'LBL_CHART_INCLUDED' => 'Inkludert',
    'LBL_CHART_NOT_INCLUDED' => 'Ikke inkludert',
    'LBL_CHART_ADJUSTED' => '(Justert)',
    'LBL_SAVE_DRAFT' => 'Lagre utkast',
    'LBL_CHANGES_BY' => 'Endringer ved {0}',
    'LBL_FORECAST_SETTINGS' => 'Innstillinger',

    // config panels strings
    'LBL_FORECASTS_CONFIG_TITLE' => 'Prognose oppsett',

    'LBL_FORECASTS_MISSING_STAGE_TITLE' => 'Prognoser konfigurasjonsfeil:',
    'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES' => 'Prognose modulen har blitt feil konfigurert og er ikke lenger tilgjengelig. Salgsfase Vant og salgsfase Tapt mangler fra de tilgjengelige salgstrinn verdier. Ta kontakt med din administrator.',
    'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE' => 'Prognosers tilgangsfeil:',
    'LBL_FORECASTS_ACLS_NO_ACCESS_MSG' => 'Du har ikke tilgang til Prognoser modulen. Ta kontakt med din administrator.',

    'LBL_FORECASTS_RECORDS_ACLS_NO_ACCESS_MSG' => 'Du har ikke tilgang til prognosemodulens poster. Ta kontakt med administrator.',

    // Panel and BreadCrumb Labels
    'LBL_FORECASTS_CONFIG_BREADCRUMB_WORKSHEET_LAYOUT' => 'Regneark layout',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES' => 'Rekkevidder',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS' => 'Scenarier',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_TIMEPERIODS' => 'Tidsperioder',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_VARIABLES' => 'Variabler',

    // Admin UI
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_SETTINGS' => 'Prognose instillinger',
    'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS' => 'Tidsperiode',
    'LBL_FORECASTS_CONFIG_TITLE_RANGES' => 'Prognose rekkevidder',
    'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS' => 'Scenarier',
    'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS' => 'Regneark kolonner',
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_BY' => 'Vis prognose regneark ved',

    'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY' => 'Prognose ved',

    'LBL_FORECASTS_CONFIG_TITLE_MESSAGE_TIMEPERIODS' => 'Regnskapsår startdato:',

    'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS' => 'Konfigurer tidsperioden som vil bli brukt i Prognose modulen. <br /><br />Vær oppmerksom på at tidsperiode innstillinger ikke kan endres etter første oppsettet. <br /><br />Start med å velge startdatoen for regnskapsåret. Deretter velger du hvilken type tidsperiode for prognosen. Datointervallet for tidsperioden vil bli beregnet automatisk basert på dine valg. Undertidsperioden er base for prognose regnearket. <br /><br />Den synlige fremtid og fortids tidsperioder vil avgjøre antallet synlige underperioder i Prognose modulen. Brukerne er i stand til å se og redigere prognose tallene i de synlige delperiodene.',
    'LBL_FORECASTS_CONFIG_HELP_RANGES' => 'Konfigurer hvordan du vil kategorisere {{forecastByModule}}. <br><br>Merk at områdeinnstillingene ikke kan endres etter første forpliktelse. For oppgraderte forekomster låses områdeinnstillingen med eksisterende prognosedata.<br><br>Du kan velge to eller flere kategorier basert på sannsynlighetsområder som ikke er basert på sannsynlighet. <br><br>Det er avkryssingruter til venstre for dine egendefinerte kategorier; bruk disse for å bestemme hvilke områder som vil inkluderes i prognosebeløpet forpliktet og rapportert til sjefer. <br><br>En bruker kan endre inkluder-/ekskluderstatusen og -kategorien til {{forecastByModule}} manuelt fra arbeidsarket.',
    'LBL_FORECASTS_CONFIG_HELP_SCENARIOS' => 'Velg kolonnene du ønsker at brukeren kan fylle ut for sine Prognoser for hver {{forecastByModuleSingular}}. Vær oppmerksom på de sannsynlige beløp som er knyttet til det beløpet som vises i {{forecastByModule}}; på grunn av dette kan ikke sannsynlighet kolonnen skjules.',
    'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS' => 'Velg hvilke kolonner du ønsker å se i Prognose modulen. Listen over felt vil kombinere regnearket og tillater brukeren å velge hvordan den konfigurerer sin visning.',
    'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY' => 'Jeg er et eksempel for prognosen ved hvordan-tekste!',
    'LBL_FORECASTS_CONFIG_SETTINGS_SAVED' => 'Prognose konfigurasjonsinstillinger har blitt lagret.',

    // timeperiod config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_SETUP_NOTICE' => 'Tidsperiode innstillinger kan ikke endres etter første oppsettet.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_DESC' => 'Konfigurer tidsperioder som brukes for Prognoser modulen.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_TYPE' => 'Velg hvilken type år organisasjonen din bruker for regnskap.',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD' => 'Velg type Tidsperiode',
    'LBL_FORECASTS_CONFIG_LEAFPERIOD' => 'Velg under periode som du ønsker å vise din Tidsperiode over:',
    'LBL_FORECASTS_CONFIG_START_DATE' => 'Velg regnskapsår startdato',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_FORWARD' => 'Velg antall fremtidige tidsperioder for å se på i regnearket. Dette tall gjelder basen i tidsperiode som er valgt. For eksempel velger du 2 under Årlig Tidsperiode, vil det vise 8 fremtidige kvartal.',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_BACKWARD' => 'Velg antall tidligere tidsperioder for å se på i regnearket.<br><i>Dette tall gjelder basen tidsperiode valgt. For eksempel velger du 2 med kvartalperiode, vises de seks siste måneder</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_FISCAL_YEAR' => 'Den valgte startdatoen indikerer at regnskapsåret kan strekke seg over to år. Vennligst velg hvilket år som skal brukes som regnskapsåret:',
    'LBL_FISCAL_YEAR' => 'Regnskapsår',

    // worksheet layout config
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_TEXT' => 'Velg hvordan å fylle ut Prognose regnearket:',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_OPPORTUNITIES' => 'Salgsmuligheter',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_PRODUCT_LINE_ITEMS' => 'Omsetningsposter',
    'LBL_REVENUELINEITEM_NAME' => 'Omsetningspost navn',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LAYOUT_DETAIL_MESSAGE' => 'Regneark vil bli fylt ut med:',

    // ranges config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_RANGES_SETUP_NOTICE' => 'Range innstillinger kan ikke endres etter første lagrede utkast eller forpliktet i Prognose modulen. For et oppgradert eksempel imidlertid, Range innstillinger kan ikke endres etter at det første oppsettet, da prognose data er allerede tilgjengelig gjennom oppgraderingen.',
    'LBL_FORECASTS_CONFIG_RANGES' => 'Prognose Range Alternativer:',
    'LBL_FORECASTS_CONFIG_RANGES_OPTIONS' => 'Velg hvordan du ønsker å kategorisere {{forecastByModule}}.',
    'LBL_FORECASTS_CONFIG_SHOW_BINARY_RANGES_DESCRIPTION' => 'Dette alternativet gir en bruker muligheten til å spesifisere {{forecastByModule}} som skal inkluderes eller ekskluderes fra en prognose.',
    'LBL_FORECASTS_CONFIG_SHOW_BUCKETS_RANGES_DESCRIPTION' => 'Dette alternativet gir en bruker muligheten til å kategorisere sine {{forecastByModule}} som ikke er inkludert i forpliktede, men er upside og har potensial til å lukke hvis alt går bra og {{forecastByModule}} som skal bli ekskludert fra prognosen.',
    'LBL_FORECASTS_CONFIG_SHOW_CUSTOM_BUCKETS_RANGES_DESCRIPTION' => 'Egendefinerte Ranges: Dette alternativet gir en bruker muligheten til å kategorisere sine {{forecastByModule}} for å bli forpliktet til prognosen i et forpliktende range, utelatt range og eventuelle andre som du har satt opp.',
    'LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO' => 'Ekskluder Range er fra 0% til minimum av forrige Prognose Range som standard.',

    'LBL_FORECASTS_CONFIG_RANGES_ENTER_RANGE' => 'Oppgi Range navn…',

    // scenarios config
    //TODO-sfa refactors the code references for scenarios to be scenarios (SFA-337).
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS' => 'Velg scenarier for å inkludere på Prognose regneark.',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LIKELY_INFO' => 'Sannsynlig er basert på beløpet oppgitt i {{forecastByModule}} modulen.',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY' => 'Sannsynlig',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST' => 'Beste',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST' => 'Verst',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS' => 'Vis projiserte scenarier i totalene',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_LIKELY' => 'Vis sannsynlig tilfelle totaler',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_BEST' => 'Vis beste tilfelle totaler',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_WORST' => 'Vis verste tilfelle totaler',

    // variables config
    'LBL_FORECASTS_CONFIG_VARIABLES' => 'Variabler',
    'LBL_FORECASTS_CONFIG_VARIABLES_DESC' => 'Formlene for Metrics Tabellen er avhengige av salgsfasen for {{forecastByModule}} som trenger å bli ekskludert fra pipleline, dvs. {{forecastByModule}} som er lukket, og tapt.',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_LOST_STAGE' => 'Vennligst velg salgsfasen som representerer lukket og tapt {{forecastByModule}}:',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_WON_STAGE' => 'Vennligst velg salgsfasen som representerer lukket og vunnet {{forecastByModule}}:',
    'LBL_FORECASTS_CONFIG_VARIABLES_FORMULA_DESC' => 'Derfor vil pipeline formelen være:',

    'LBL_FORECASTS_WIZARD_SUCCESS_TITLE' => 'Vellykket:',
    'LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE' => 'Prognoser modulen er vellykket satt opp. Vennligst vent mens modulen laster.',
    'LBL_FORECASTS_TABBED_CONFIG_SUCCESS_MESSAGE' => 'Prognosens konfigurasjonsinstillinger har blitt lagret. Vennligst vent mens modulen laster.',
    // Labels for Success Messages:
    'LBL_FORECASTS_WORKSHEET_SAVE_DRAFT_SUCCESS' => 'Du har lagret Prognose regnearket som et utkast for den valgte tidsperioden.',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS' => 'Du har forpliktet din prognose',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO' => 'Du har forpliktet din prognose til {{manager}}',

    // custom ranges
    'LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME' => 'Egendefinert Range',
    'LBL_UNAUTH_FORECASTS' => 'Uautorisert tilgang til Prognose innstillinger.',
    'LBL_FORECASTS_RANGES_BASED_TITLE' => 'Ranges basert på sannsynligheter',
    'LBL_FORECASTS_CUSTOM_BASED_TITLE' => 'Egendefinerte Ranges basert på sannsynligheter',
    'LBL_FORECASTS_CUSTOM_NO_BASED_TITLE' =>'Ranges ikke basert på sannsynlighet',

    // worksheet columns config
    'LBL_DISCOUNT' => 'Rabatt',
    'LBL_OPPORTUNITY_STATUS' => 'Salgsmulighets status',
    'LBL_OPPORTUNITY_NAME' => 'Salgsmulighet navn:',
    'LBL_PRODUCT_TEMPLATE' => 'Produktkatalog',
    'LBL_CAMPAIGN' => 'Kampanje',
    'LBL_TEAMS' => 'Teams',
    'LBL_CATEGORY' => 'Kategori',
    'LBL_COST_PRICE' => 'Kostpris',
    'LBL_TOTAL_DISCOUNT_AMOUNT' => 'Totalt rabatt beløp',
    'LBL_FORECASTS_CONFIG_WORKSHEET_TEXT' => 'Velg hvilke kolonner som skal vises i regneark-visning. Som standard, vil følgende felt velges:',

    // forecast details dashlet
    'LBL_DASHLET_FORECAST_NOT_SETUP' => 'Prognoser er ikke konfigurert og må settes opp for å bruke denne widgeten. Vennligst ta kontakt med systemansvarlig.',
    'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' => 'Prognoser er ikke konfigurert og må settes opp for å bruke denne widgeten.',
    'LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT' => 'Vennligst klikk her for å konfigurere Prognose modulen.',
    'LBL_DASHLET_MY_PIPELINE' => 'Min Pipeline',
    'LBL_DASHLET_MY_TEAMS_PIPELINE' => "Mitt teams Pipeline",
    'LBL_DASHLET_PIPELINE_CHART_NAME' => 'Prognose Pipeline diagram',
    'LBL_DASHLET_PIPELINE_CHART_DESC' => 'Viser nåværende pipeline diagram',
    'LBL_FORECAST_DETAILS_DEFICIT' => 'Underskudd',
    'LBL_FORECAST_DETAILS_SURPLUS' => 'Overskudd',
    'LBL_FORECAST_DETAILS_SHORT' => 'For lite med',
    'LBL_FORECAST_DETAILS_EXCEED' => 'Overgå med',
    'LBL_FORECAST_DETAILS_NO_DATA' => 'Ingen data',
    'LBL_FORECAST_DETAILS_MEETING_QUOTA' => 'Møte budsjett',

    'LBL_ASSIGN_QUOTA_BUTTON' => 'Tildele budsjett',
    'LBL_ASSIGNING_QUOTA' => 'Tildeler budsjett',
    'LBL_QUOTA_ASSIGNED' => 'Budsjettene har blitt vellykket tildelt.',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_TITLE' => 'Prognosers tilgangsfeil:',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_MSG' => 'Du har ikke tilgang til å konfigurere prognoser. Vennligst kontakt din systemadministrator.',
    'WARNING_DELETED_RECORD_RECOMMIT_1' => 'Denne posten var inkludert i en ',
    'WARNING_DELETED_RECORD_RECOMMIT_2' => 'Den fjernes og du må igjen forplikte din ',

    'LBL_DASHLET_MY_FORECAST' => 'Min Prognose',
    'LBL_DASHLET_MY_TEAMS_FORECAST' => "Mitt teams prognoser",

    'LBL_WARN_UNSAVED_CHANGES_CONFIRM_SORT' => 'Du har ulagrede endringer. Er du sikker på at du vil sortere regnearket og forkaste endringene?',

    // Forecasts Records View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}}-modulen bygger inn {{forecastby_singular_module}}-poster for å lage {{forecastworksheets_module}} og forutse salg. Brukere kan jobbe mot salg{{quotas_module}} på individ-, gruppe- og salgsorganitorisk nivå. Før brukere får tilgang til {{plural_module_name}}-modulen, må en administrator velge organisasjonens ønskede tidsperioder, intervaller og scenarier.

Salgsrepresentanter bruker {{plural_module_name}}-modulen for å jobbe med tildelt {{forecastby_module}} mens den aktuelle perioden løper. Disse brukeren legger inn totalprognoser for personlige salg basert på {{forecastby_module}} som de forventer å lukke. Salgsledere jobber med egne {{forecastby_singular_module}}-poster i likhet med andre salgsrepresentanter. I tillegg summerer de rapportørenes innsendte beløp for å forutse gruppens totale salg og jobbe mot gruppens mål for hver periode. Ytterligere innsikt tilbys av elementene i det utvidbare intelligenspanelet, dette inkluderer analyser for individuelle regneark og analyser for lederens grupperegneark.'
);
