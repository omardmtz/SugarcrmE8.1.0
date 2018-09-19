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
    'LBL_MODULE_NAME' => 'Adatvédelem',
    'LBL_MODULE_NAME_SINGULAR' => 'Adatvédelem',
    'LBL_NUMBER' => 'Szám',
    'LBL_TYPE' => 'Típus',
    'LBL_SOURCE' => 'Forrás',
    'LBL_REQUESTED_BY' => 'Kérte',
    'LBL_DATE_OPENED' => 'Megnyitás dátuma',
    'LBL_DATE_DUE' => 'Esedékesség dátuma',
    'LBL_DATE_CLOSED' => 'Lezárás dátuma',
    'LBL_BUSINESS_PURPOSE' => 'Hozzájárulás üzleti célokhoz a következőhöz',
    'LBL_LIST_NUMBER' => 'Szám',
    'LBL_LIST_SUBJECT' => 'Tárgy',
    'LBL_LIST_PRIORITY' => 'Prioritás',
    'LBL_LIST_STATUS' => 'Állapot',
    'LBL_LIST_TYPE' => 'Típus',
    'LBL_LIST_SOURCE' => 'Forrás',
    'LBL_LIST_REQUESTED_BY' => 'Kérte:',
    'LBL_LIST_DATE_DUE' => 'Esedékesség dátuma',
    'LBL_LIST_DATE_CLOSED' => 'Lezárás dátuma',
    'LBL_LIST_DATE_MODIFIED' => 'Módosítás dátuma',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Módosította:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Hozzárendelt felhasználó',
    'LBL_SHOW_MORE' => 'További adatvédelmi tevékenységek megjelenítése',
    'LNK_DATAPRIVACY_LIST' => 'Adatvédelmi tevékenységek megjelenítése',
    'LNK_NEW_DATAPRIVACY' => 'Adatvédelmi tevékenység létrehozása',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Ajánlások',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kapcsolatok',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Célok',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Kliensek',
    'LBL_LISTVIEW_FILTER_ALL' => 'Összes adatvédelmi tevékenység',
    'LBL_ASSIGNED_TO_ME' => 'Saját adatvédelmi tevékenységek',
    'LBL_SEARCH_AND_SELECT' => 'Adatvédelmi tevékenységek keresése és kiválasztása',
    'TPL_SEARCH_AND_ADD' => 'Adatvédelmi tevékenységek keresése és hozzáadása',
    'LBL_WARNING_ERASE_CONFIRM' => 'Ön {0} mező végleges törlésére készül. A törlést követően nem áll rendelkezésre opció a vonatkozó adatok visszanyerésére. Biztos benne, hogy folytatni szeretné?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => '{0} mezőt törlésre jelölt. Megerősítés esetén a törlést visszavonja, a rendszer megőrzi az összes adatot, a kérés pedig elutasított állapotot vesz fel. Biztos benne, hogy folytatni szeretné?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'A kérés befejezettnek jelölésére készül. Az állapot így véglegesen Befejezettre vált, a kérés pedig nem nyitható meg újból. Biztos benne, hogy folytatni szeretné?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'A kérés elutasítottnak jelölésére készül. Az állapot így véglegesen Elutasítottra vált, a kérés pedig nem nyitható meg újból. Biztos benne, hogy folytatni szeretné?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Sikeresen létrehozta a következő adatvédelmi tevékenységet: <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Elutasítás',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Befejezés',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Törlés és befejezés',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Kiválasztott mezők törlése az alpaneleken keresztül',
    'LBL_COUNT_FIELDS_MARKED' => 'Törlésre jelölt mezők',
    'LBL_NO_RECORDS_MARKED' => 'Nincs egyetlen törlésre jelölt mező vagy rekord sem.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Adatvédelmi rekord műszerfal',

    // list view
    'LBL_HELP_RECORDS' => 'Az Adatvédelem modul nyomon követi az adatvédelmi tevékenységeket, beleértve a hozzájárulási és tárgyra vonatkozó kéréseket a szervezet adatvédelmi eljárásai támogatásához. A hozzájárulás követéséhez vagy adatvédelmi kérés nyomán valamilyen intézkedés foganatosításához hozzon létre adatvédelmi rekordokat, amelyek valamilyen egyéni rekordhoz (pl. kapcsolati adatokhoz) kapcsolódnak.',
    // record view
    'LBL_HELP_RECORD' => 'Az Adatvédelem modul nyomon követi az adatvédelmi tevékenységeket, beleértve a hozzájárulási és tárgyra vonatkozó kéréseket a szervezet adatvédelmi eljárásai támogatásához. A hozzájárulás követéséhez vagy adatvédelmi kérés nyomán valamilyen intézkedés foganatosításához hozzon létre adatvédelmi rekordokat, amelyek valamilyen egyéni rekordhoz (pl. kapcsolati adatokhoz) kapcsolódnak. Amint végrehajtotta a szükséges tevékenységet, az adatvédelmi menedzser szerepkörrel rendelkező felhasználók az állapot frissítéséhez kattinthat a „Befejezés” vagy „Elutasítás” lehetőségre.

Törlési kérésekhez válassza ki az alábbi alpanelekben megjelenített egyéni rekordok melletti „Megjelölés törléshez” lehetőségeket. Amint kiválasztotta az összes kívánt mezőt, a „Törlés és befejezés” lehetőségre kattintással eltávolítja a mezőértékeket, az adatvédelmi rekordot pedig befejezettnek jelöli.',
);
