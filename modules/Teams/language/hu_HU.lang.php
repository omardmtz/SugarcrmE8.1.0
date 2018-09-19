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
    'ERR_ADD_RECORD' => 'Adjon meg egy azonosítót a felhasználó felvételéhez.',
    'ERR_DUP_NAME' => 'Létező csoportnév, válasszon másikat.',
    'ERR_DELETE_RECORD' => 'Adjon meg egy azonosítót a csoport törléséhez.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Hiba. A kiválasztott csoportot ({0}) törlésre jelölte. Válasszon ki másikat!',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Hiba: lehetséges, hogy addig nem törölhető egy felhasználó, amíg privát csoportját nem törli.',
    'LBL_DESCRIPTION' => 'Leírás:',
    'LBL_GLOBAL_TEAM_DESC' => 'Mindenhol látható',
    'LBL_INVITEE' => 'Csoporttagok',
    'LBL_LIST_DEPARTMENT' => 'Osztály',
    'LBL_LIST_DESCRIPTION' => 'Leírás',
    'LBL_LIST_FORM_TITLE' => 'Csoportlista',
    'LBL_LIST_NAME' => 'Név',
    'LBL_FIRST_NAME' => 'Keresztnév:',
    'LBL_LAST_NAME' => 'Vezetéknév:',
    'LBL_LIST_REPORTS_TO' => 'Jelentést tesz',
    'LBL_LIST_TITLE' => 'Beosztás',
    'LBL_MODULE_NAME' => 'Csoportok',
    'LBL_MODULE_NAME_SINGULAR' => 'Csoport',
    'LBL_MODULE_TITLE' => 'Csoportok: Főoldal',
    'LBL_NAME' => 'Csoportnév:',
    'LBL_NAME_2' => 'Csoportnév (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Elsődleges csoportnév',
    'LBL_NEW_FORM_TITLE' => 'Új csoport',
    'LBL_PRIVATE' => 'Magán',
    'LBL_PRIVATE_TEAM_FOR' => 'Magán csoport:',
    'LBL_SEARCH_FORM_TITLE' => 'Csoport keresése',
    'LBL_TEAM_MEMBERS' => 'Csoporttagok',
    'LBL_TEAM' => 'Csoportok:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Felhasználók',
    'LBL_USERS' => 'Felhasználók',
    'LBL_REASSIGN_TEAM_TITLE' => 'A következő csoport(ok)hoz vannak bejegyzések: {0}<br />Mielőtt törli a csoporto(ka)t, helyezze át a bejegyzéseket egy új csoporthoz. Válassza ki a helyettesítő csoportot!',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Helyettesítés',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Helyettesítés [Alt + R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Hozzáadja a frissítést az új csoporthoz?',
    'LBL_REASSIGN_TABLE_INFO' => 'Módosítás {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'A művelet sikeresen befejeződött.',
    'LNK_LIST_TEAM' => 'Csoportok',
    'LNK_LIST_TEAMNOTICE' => 'Csoport közlemények',
    'LNK_NEW_TEAM' => 'Csoport létrehozása',
    'LNK_NEW_TEAM_NOTICE' => 'Új csoport közlemény létrehozása',
    'NTC_DELETE_CONFIRMATION' => 'Biztosan törölni akarja ezt a rekordot?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Biztosan meg akarja szüntetni a munkatárs csoporttagságát?',
    'LBL_EDITLAYOUT' => 'Elrendezés szerkesztése' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Csoport-alapú engedélyek',
    'LBL_TBA_CONFIGURATION_DESC' => 'Engedélyezze a csoport-hozzáférést és kezelje a hozzáférést a modulban.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Csapat-alapú engedélyek engedélyezése',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Válasszon ki modulokat engedélyezéshez',
    'LBL_TBA_CONFIGURATION_TITLE' => 'A csapat-alapú engedélyek lehetővé teszik az ön számára, hogy a Szerepfelügyelet segítségével specifikus hozzáférhetőségi lehetőségeket biztosítson bizonyos csapatok, illetve egyéni modulú felhasználók számára.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Egy modul csoport-alapú engedélyeinek tiltása visszatéríti majd minden csoport-alapú engedélyekkel társult adatát a 
 modulnak, beleértve a folyamat mehatározásokat és az adott funkciót használó folyamatokat is. Ez magába foglalja a Szerepeket is, melyek a
 "Személy és kijelölt csoport" opciót használják annál a modulnál, és minden csoport-alapú engedély adatot a modulon belüli rekordok kapcsán.
 Ugyanakkor ajánljuk a Gyors Javítás és Helyreállítás eszközöket is a rendszer gyórsítótárának tisztításához csoport-alapú tiltás után 
 az adott modulra vonatkozó engedélyek kapcsán.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Figyelmeztetés:</strong>Egy modul csoport - alapú engedélyeinek tiltása visszatéríti majd minden 
 csoport-alapú engedélyekkel társult adatát a modulnak, beleértve a folyamat mehatározásokat és az adott funkciót használó folyamatokat is. Ez 
 magába foglalja a Szerepeket is, melyek a"Személy és kijelölt csoport" opciót használják annál a modulnál, és minden csoport-alapú engedély adatot 
 a modulon belüli rekordok kapcsán. Ugyanakkor ajánljuk a Gyors Javítás és Helyreállítás eszközöket is a rendszer gyórsítótárának tisztításához 
 csoport-alapú tiltás után 
 az adott modulra vonatkozó engedélyek kapcsán.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Egy modul csoport-alapú engedélyeinek tiltása visszatéríti majd minden csoport-alapú engedélyekkel társult adatát a 
 modulnak, beleértve a folyamat mehatározásokat és az adott funkciót használó folyamatokat is. Ez magába foglalja a Szerepeket is, melyek a
 "Személy és kijelölt csoport" opciót használják annál a modulnál, és minden csoport-alapú engedély adatot a modulon belüli rekordok kapcsán.
 Ugyanakkor ajánljuk a Gyors Javítás és Újraépítés eszközöket is a rendszer gyórsítótárának tisztításához csoport-alapú tiltás után 
 az adott modulra vonatkozó engedélyek kapcsán. Hogyha nem tud hozzáférni a Gyors Javításhoz és Helyreállításhoz, lépjen kapcsolatba a rendszergazdával
 melyhez a Javítás menüben férhez hozzá.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Figyelmeztetés:</strong>Egy modul csoport - alapú engedélyeinek tiltása visszatéríti majd minden 
 csoport-alapú engedélyekkel társult adatát a modulnak, beleértve a folyamat mehatározásokat és az adott funkciót használó folyamatokat is. Ez 
 magába foglalja a Szerepeket is, melyek a"Személy és kijelölt csoport" opciót használják annál a modulnál, és minden csoport-alapú engedély adatot a 
 modulon belüli rekordok kapcsán. Ugyanakkor ajánljuk a Gyors Javítás és Újraépítés eszközöket is a rendszer gyórsítótárának tisztításához 
 csoport-alapú tiltás után az adott modulra vonatkozó engedélyek kapcsán. Hogyha nem tud hozzáférni a Gyors Javításhoz és Helyreállításhoz, lépjen kapcsolatba a rendszergazdával
 melyhez a Javítás menüben férhez hozzá.
STR
,
);
