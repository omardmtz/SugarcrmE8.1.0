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
    'LBL_MODULE_NAME' => 'Ochrana osobných údajov',
    'LBL_MODULE_NAME_SINGULAR' => 'Ochrana osobných údajov',
    'LBL_NUMBER' => 'Číslo',
    'LBL_TYPE' => 'Typ',
    'LBL_SOURCE' => 'Zdroj',
    'LBL_REQUESTED_BY' => 'Vyžiadal',
    'LBL_DATE_OPENED' => 'Dátum otvorenia',
    'LBL_DATE_DUE' => 'Termín',
    'LBL_DATE_CLOSED' => 'Dátum uzatvorenia',
    'LBL_BUSINESS_PURPOSE' => 'Odsúhlasené podnikateľské účely',
    'LBL_LIST_NUMBER' => 'Číslo',
    'LBL_LIST_SUBJECT' => 'Predmet',
    'LBL_LIST_PRIORITY' => 'Priorita',
    'LBL_LIST_STATUS' => 'Stav',
    'LBL_LIST_TYPE' => 'Typ',
    'LBL_LIST_SOURCE' => 'Zdroj',
    'LBL_LIST_REQUESTED_BY' => 'Vyžiadal',
    'LBL_LIST_DATE_DUE' => 'Termín',
    'LBL_LIST_DATE_CLOSED' => 'Dátum uzatvorenia',
    'LBL_LIST_DATE_MODIFIED' => 'Dátum úpravy',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Zmenil',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Priradený používateľ',
    'LBL_SHOW_MORE' => 'Zobraziť ďalšie aktivíty na ochranu osobných údajov',
    'LNK_DATAPRIVACY_LIST' => 'Zobraziť aktivity na ochranu osobných údajov',
    'LNK_NEW_DATAPRIVACY' => 'Vytvoriť aktivitu na ochranu osobných údajov',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Záujemcovia',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Ciele',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Účty',
    'LBL_LISTVIEW_FILTER_ALL' => 'Všetky aktivity na ochranu osobných údajov',
    'LBL_ASSIGNED_TO_ME' => 'Moje aktivity na ochranu osobných údajov',
    'LBL_SEARCH_AND_SELECT' => 'Vyhľadať a vybrať aktivity na ochranu osobných údajov',
    'TPL_SEARCH_AND_ADD' => 'Vyhľadať a pridať aktivity na ochranu osobných údajov',
    'LBL_WARNING_ERASE_CONFIRM' => 'Chystáte sa natrvalo vymazať polia {0}. Po ich vymazaní sa už tieto údaje nebudú dať žiadnym spôsobom obnoviť. Naozaj chcete pokračovať?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Polia v počte {0} sú označené na vymazanie. Potvrdením zrušíte vymazanie, zachováte všetky údaje a označíte túto požiadavku za zamietnutú. Naozaj chcete pokračovať?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Chystáte sa označiť túto žiadosť za dokončenú. Jej stav sa tým natrvalo nastaví na dokončenú a nebude sa dať opätovne otvoriť. Naozaj chcete pokračovať?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Chystáte sa označiť túto žiadosť za zamietnutú. Jej stav sa tým natrvalo nastaví na zamietnutú a nebude sa dať opätovne otvoriť. Naozaj chcete pokračovať?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Úspešne ste vytvorili aktivitu na ochranu osobných údajov <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Zamietnuť',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Dokončiť',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Vymazať a dokončiť',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Vymazať polia vybraté pomocou podpanelov',
    'LBL_COUNT_FIELDS_MARKED' => 'Polia označené na vymazanie',
    'LBL_NO_RECORDS_MARKED' => 'Žiadne polia ani záznamy neboli označené na vymazanie.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Informačný panel so záznamami o ochrane osobných údajov',

    // list view
    'LBL_HELP_RECORDS' => 'Modul ochrany osobných údajov sleduje aktivity na ochranu osobných údajov vrátane žiadostí o súhlas a žiadostí subjektu, ktoré podporujú postupy vašej organizácie v oblasti ochrany osobných údajov. Vytvorte záznamy o ochrane osobných údajov, ktoré sa vzťahujú na záznam jednotlivca (napríklad kontakt), aby ste mohli sledovať súhlas alebo prijať opatrenia týkajúce sa žiadosti o ochranu osobných údajov.',
    // record view
    'LBL_HELP_RECORD' => 'Modul ochrany osobných údajov sleduje aktivity na ochranu osobných údajov vrátane žiadostí o súhlas a žiadostí subjektu, ktoré podporujú postupy vašej organizácie v oblasti ochrany osobných údajov. Vytvorte záznamy o ochrane osobných údajov, ktoré sa vzťahujú na záznam jednotlivca (napríklad kontakt), aby ste mohli sledovať súhlas alebo prijať opatrenia týkajúce sa žiadosti o ochranu osobných údajov. Po dokončení potrebnej akcie môžu používatelia s rolou Správca ochrany údajov kliknúť na tlačidlo „Dokončiť“ alebo „Odmietnuť“, aby aktualizovali stav.

V prípade žiadostí o vymazanie vyberte možnosť „Označiť na vymazanie“ pre každý z jednotlivých záznamov uvedených v podpaneloch nižšie. Po výbere všetkých požadovaných polí kliknutím na položku „Vymazať a dokončiť“ natrvalo odstráňte hodnoty polí a označte záznam o ochrane osobných údajov za dokončený.',
);
