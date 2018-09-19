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
    'LBL_MODULE_NAME' => 'Ochrana osobních údajů',
    'LBL_MODULE_NAME_SINGULAR' => 'Ochrana osobních údajů',
    'LBL_NUMBER' => 'Číslo',
    'LBL_TYPE' => 'Typ',
    'LBL_SOURCE' => 'Zdroj',
    'LBL_REQUESTED_BY' => 'Žadatel',
    'LBL_DATE_OPENED' => 'Datum otevření',
    'LBL_DATE_DUE' => 'Udělat do dne',
    'LBL_DATE_CLOSED' => 'Datum uzavření',
    'LBL_BUSINESS_PURPOSE' => 'Se souhlasem pro obchodní účely',
    'LBL_LIST_NUMBER' => 'Číslo',
    'LBL_LIST_SUBJECT' => 'Předmět',
    'LBL_LIST_PRIORITY' => 'Priorita',
    'LBL_LIST_STATUS' => 'Stav',
    'LBL_LIST_TYPE' => 'Typ',
    'LBL_LIST_SOURCE' => 'Zdroj',
    'LBL_LIST_REQUESTED_BY' => 'Žadatel',
    'LBL_LIST_DATE_DUE' => 'Udělat do dne',
    'LBL_LIST_DATE_CLOSED' => 'Datum uzavření',
    'LBL_LIST_DATE_MODIFIED' => 'Datum změny',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Změnil',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Přiřazený uživatel',
    'LBL_SHOW_MORE' => 'Zobrazit více aktivit spojených s ochranou osobních údajů',
    'LNK_DATAPRIVACY_LIST' => 'Zobrazit aktivity spojené s ochranou osobních údajů',
    'LNK_NEW_DATAPRIVACY' => 'Vytvořit aktivitu spojenou s ochranou osobních údajů',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Zájemci',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Adresáti',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Společnosti',
    'LBL_LISTVIEW_FILTER_ALL' => 'Všechny aktivity spojené s ochranou osobních údajů',
    'LBL_ASSIGNED_TO_ME' => 'Moje aktivity spojené s ochranou osobních údajů',
    'LBL_SEARCH_AND_SELECT' => 'Vyhledat a vybrat aktivity spojené s ochranou osobních údajů',
    'TPL_SEARCH_AND_ADD' => 'Vyhledat a přidat aktivity spojené s ochranou osobních údajů',
    'LBL_WARNING_ERASE_CONFIRM' => 'Chystáte se trvale vymazat {0} pole (polí). Neexistuje žádná možnost obnovení těchto dat po dokončení vymazání. Opravdu chcete pokračovat?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Máte {0} pole (polí) označené k vymazání. Potvrzením bude vymazání přerušeno, budou zachována všechna data a tento požadavek bude označen jako zamítnutý. Opravdu chcete pokračovat?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Chystáte se tento požadavek označit jako dokončený. Tím se stav trvale nastaví na Dokončeno a nemůže být znovu otevřen. Opravdu chcete pokračovat?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Chystáte se tento požadavek označit jako zamítnutý. Tím se stav trvale nastaví na Zamítnuto a nemůže být znovu otevřen. Opravdu chcete pokračovat?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Úspěšně jste vytvořili aktivitu spojenou s ochranou osobních údajů <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Zamítnout',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Dokončit',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Vymazat a dokončit',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Vymazat pole vybraná prostřednictvím dílčích panelů',
    'LBL_COUNT_FIELDS_MARKED' => 'Pole označená k vymazání',
    'LBL_NO_RECORDS_MARKED' => 'K vymazání nebyla označena žádná pole nebo záznamy.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Řídicí panel záznamů o ochraně osobních údajů',

    // list view
    'LBL_HELP_RECORDS' => 'Modul Ochrana osobních údajů sleduje aktivity spojené s ochranou osobních údajů, včetně souhlasu a požadavků subjektů, za účelem podpory postupů ochrany osobních údajů vaší organizace. Vytváří záznamy o ochraně osobních údajů souvisejících se záznamem jednotlivce (např. kontakt), aby bylo možné sledovat poskytnutý souhlas nebo podniknout opatření týkající se požadavku na ochranu osobních údajů.',
    // record view
    'LBL_HELP_RECORD' => 'Modul Ochrana osobních údajů sleduje aktivity spojené s ochranou osobních údajů, včetně souhlasu a požadavků subjektů, za účelem podpory postupů ochrany osobních údajů vaší organizace. Vytváří záznamy o ochraně osobních údajů souvisejících se záznamem jednotlivce (např. kontakt), aby bylo možné sledovat poskytnutý souhlas nebo podniknout opatření týkající se požadavku na ochranu osobních údajů. Jakmile je potřebné opatření dokončeno, mohou uživatelé v roli Správce ochrany osobních údajů aktualizovat stav kliknutím na možnosti „Dokončeno“ nebo „Zamítnuto“.

U požadavků na vymazání údajů vyberte možnost „Označit k vymazání“ pro všechny záznamy jednotlivce v níže uvedených dílčích panelech. Jakmile jsou vybrána všechna požadovaná pole, kliknutím na možnost „Vymazat a dokončit“ trvale odstraníte hodnoty polí a označíte záznam s osobními údaji jako dokončený.',
);
