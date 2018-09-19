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
    'LBL_MODULE_NAME' => 'Datu privātums',
    'LBL_MODULE_NAME_SINGULAR' => 'Datu privātums',
    'LBL_NUMBER' => 'Numurs',
    'LBL_TYPE' => 'Tips',
    'LBL_SOURCE' => 'Avots',
    'LBL_REQUESTED_BY' => 'Pieprasīja',
    'LBL_DATE_OPENED' => 'Atvēršanas datums',
    'LBL_DATE_DUE' => 'Izpildes termiņš',
    'LBL_DATE_CLOSED' => 'Slēgšanas datums',
    'LBL_BUSINESS_PURPOSE' => 'Ir piekrišana biznesa mērķiem',
    'LBL_LIST_NUMBER' => 'Numurs',
    'LBL_LIST_SUBJECT' => 'Temats',
    'LBL_LIST_PRIORITY' => 'Prioritāte',
    'LBL_LIST_STATUS' => 'Statuss',
    'LBL_LIST_TYPE' => 'Tips',
    'LBL_LIST_SOURCE' => 'Avots',
    'LBL_LIST_REQUESTED_BY' => 'Pieprasīja',
    'LBL_LIST_DATE_DUE' => 'Izpildes termiņš',
    'LBL_LIST_DATE_CLOSED' => 'Slēgšanas datums',
    'LBL_LIST_DATE_MODIFIED' => 'Modificēšanas datums',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Modificēja',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Piešķirtais lietotājs',
    'LBL_SHOW_MORE' => 'Rādīt vairāk datu privātuma darbību',
    'LNK_DATAPRIVACY_LIST' => 'Skatīt datu privātuma darbības',
    'LNK_NEW_DATAPRIVACY' => 'Izveidot datu privātuma darbību',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Interesenti',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktpersonas',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Mērķi',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Konti',
    'LBL_LISTVIEW_FILTER_ALL' => 'Visas datu privātuma darbības',
    'LBL_ASSIGNED_TO_ME' => 'Manas datu privātuma darbības',
    'LBL_SEARCH_AND_SELECT' => 'Meklēt un atlasīt datu privātuma darbības',
    'TPL_SEARCH_AND_ADD' => 'Meklēt un pievienot datu privātuma darbības',
    'LBL_WARNING_ERASE_CONFIRM' => 'Jūs gatavojaties neatgriezeniski izdzēst {0} lauku(s). Pēc dzēšanas pabeigšanas šos datus atgūt nav iespējams. Vai tiešām vēlaties turpināt?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Jūs esat atzīmējis dzēšanai {0} lauku(s). Apstiprināšana pārtrauks dzēšanu, saglabās visus datus un atzīmēs šo pieprasījumu kā noraidītu. Vai tiešām vēlaties turpināt?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Jūs gatavojaties atzīmēt šo pieprasījumu kā pabeigtu. Tas neatgriezeniski iestatīs statusu uz Pabeigts, un tas no jauna nebūs atverams. Vai tiešām vēlaties turpināt?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Jūs gatavojaties atzīmēt šo pieprasījumu kā noraidītu. Tas neatgriezeniski iestatīs statusu uz Noraidīts, un tas no jauna nebūs atverams. Vai tiešām vēlaties turpināt?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Jūs veiksmīgi izveidojāt datu privātuma darbību <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Noraidīt',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Pabeigt',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Izdzēst un pabeigt',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Izdzēst ar apakšpaneļu palīdzību atlasītos laukus',
    'LBL_COUNT_FIELDS_MARKED' => 'Dzēšanai atzīmētie lauki',
    'LBL_NO_RECORDS_MARKED' => 'Neviens lauks vai ieraksts nav atzīmēts dzēšanai.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Datu privātuma ierakstu informācijas panelis',

    // list view
    'LBL_HELP_RECORDS' => 'Datu privātuma modulis izseko privātuma darbības, tostarp piekrišanas un tematu pieprasījumus, lai atbalstītu jūsu organizācijas privātuma procedūras. Lai izsekotu piekrišanu vai veiktu darbību ar privātuma pieprasījumu, Izveidojiet ar indivīda ierakstu (piemēram, kontaktinformāciju) saistītus datu privātuma ierakstus.',
    // record view
    'LBL_HELP_RECORD' => 'Datu privātuma modulis izseko privātuma darbības, tostarp piekrišanas un tematu pieprasījumus, lai atbalstītu jūsu organizācijas privātuma procedūras. Lai izsekotu piekrišanu vai veiktu darbību ar privātuma pieprasījumu, Izveidojiet ar indivīda ierakstu (piemēram, kontaktinformāciju) saistītus datu privātuma ierakstus. Kad nepieciešamā darbība ir pabeigta, lietotāji datu privātuma pārvaldnieka lomā, lai atjauninātu statusu, var noklikšķināt “Pabeigt” vai “Noraidīt”.

Pieprasījumu dzēšanai katram zemāk esošajā apakšpanelī minētajam indivīda ierakstam atlasiet “Atzīmēt dzēšanai”. Kad visi vēlamie lauki ir atlasīti, ar noklikšķināšanu uz “Izdzēst un pabeigt” tiks neatgriezeniski noņemtas šo lauku vērtības un datu privātuma ieraksts atzīmēts kā pabeigts.',
);
