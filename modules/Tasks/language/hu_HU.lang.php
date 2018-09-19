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
  'LBL_TASKS_LIST_DASHBOARD' => 'Feladatok listája műszerfal',

  'LBL_MODULE_NAME' => 'Feladatok',
  'LBL_MODULE_NAME_SINGULAR' => 'Feladat',
  'LBL_TASK' => 'Feladatok:',
  'LBL_MODULE_TITLE' => 'Feladatok: Főoldal',
  'LBL_SEARCH_FORM_TITLE' => 'Feladat keresése',
  'LBL_LIST_FORM_TITLE' => 'Feladatlista',
  'LBL_NEW_FORM_TITLE' => 'Új feladat lérehozása',
  'LBL_NEW_FORM_SUBJECT' => 'Tárgy:',
  'LBL_NEW_FORM_DUE_DATE' => 'Határidő:',
  'LBL_NEW_FORM_DUE_TIME' => 'Esedékesség ideje:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Zárás',
  'LBL_LIST_SUBJECT' => 'Tárgy',
  'LBL_LIST_CONTACT' => 'Kapcsolat',
  'LBL_LIST_PRIORITY' => 'Prioritás',
  'LBL_LIST_RELATED_TO' => 'Kapcsolódó',
  'LBL_LIST_DUE_DATE' => 'Esedékesség dátuma',
  'LBL_LIST_DUE_TIME' => 'Esedékesség időpontja',
  'LBL_SUBJECT' => 'Tárgy:',
  'LBL_STATUS' => 'Állapot:',
  'LBL_DUE_DATE' => 'Esedékesség dátuma:',
  'LBL_DUE_TIME' => 'Esedékesség időpontja:',
  'LBL_PRIORITY' => 'Prioritás:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Esedékesség dátuma és ideje:',
  'LBL_START_DATE_AND_TIME' => 'Kezdés dátuma és ideje:',
  'LBL_START_DATE' => 'Kezdés dátuma:',
  'LBL_LIST_START_DATE' => 'Kezdés dátuma',
  'LBL_START_TIME' => 'Kezdés időpontja:',
  'LBL_LIST_START_TIME' => 'Kezdés időpontja',
  'DATE_FORMAT' => '(éééé-hh-nn)',
  'LBL_NONE' => 'Nincs',
  'LBL_CONTACT' => 'Kapcsolat:',
  'LBL_EMAIL_ADDRESS' => 'Email cím:',
  'LBL_PHONE' => 'Telefonszám:',
  'LBL_EMAIL' => 'Email cím:',
  'LBL_DESCRIPTION_INFORMATION' => 'Információ leírása',
  'LBL_DESCRIPTION' => 'Leírás:',
  'LBL_NAME' => 'Név:',
  'LBL_CONTACT_NAME' => 'Kapcsolat neve:',
  'LBL_LIST_COMPLETE' => 'Kész:',
  'LBL_LIST_STATUS' => 'Állapot',
  'LBL_DATE_DUE_FLAG' => 'Nincs esedékes dátum',
  'LBL_DATE_START_FLAG' => 'Nincs kezdő dátum',
  'ERR_DELETE_RECORD' => 'Adjon meg egy azonosítót a kapcsolat törléséhez!',
  'ERR_INVALID_HOUR' => 'Kérem, adja meg az órát 0 és 24 között',
  'LBL_DEFAULT_PRIORITY' => 'Közepes',
  'LBL_LIST_MY_TASKS' => 'Befejezetlen feladataim',
  'LNK_NEW_TASK' => 'Feladat létrehozása',
  'LNK_TASK_LIST' => 'Feladatok megtekintése',
  'LNK_IMPORT_TASKS' => 'Feladatok importálása',
  'LBL_CONTACT_FIRST_NAME'=>'Kapcsolat keresztneve',
  'LBL_CONTACT_LAST_NAME'=>'Kapcsolat vezetékneve',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Felelős felhasználó',
  'LBL_ASSIGNED_TO_NAME'=>'Felelős:',
  'LBL_LIST_DATE_MODIFIED' => 'Módosítás dátuma',
  'LBL_CONTACT_ID' => 'Kapcsolat azonosítója:',
  'LBL_PARENT_ID' => 'Szülő azonosítója:',
  'LBL_CONTACT_PHONE' => 'Kapcsolat telefonszáma:',
  'LBL_PARENT_NAME' => 'Szülő típusa:',
  'LBL_ACTIVITIES_REPORTS' => 'Tevékenységek jelentése',
  'LBL_EDITLAYOUT' => 'Elrendezés szerkesztése' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Feladat áttekintése',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Jegyzetek',
  'LBL_REVENUELINEITEMS' => 'Bevételi sor tételek',
  //For export labels
  'LBL_DATE_DUE' => 'Esedékesség dátuma',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Felelős felhasználó neve',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Felelős felhasználói azonosító',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Módosító azonosítója',
  'LBL_EXPORT_CREATED_BY' => 'Létrehozói azonosító',
  'LBL_EXPORT_PARENT_TYPE' => 'Kapcsolódó modul neve',
  'LBL_EXPORT_PARENT_ID' => 'Kapcsolódó azonosító',
  'LBL_TASK_CLOSE_SUCCESS' => 'Feladat sikeresen lezárva.',
  'LBL_ASSIGNED_USER' => 'Felelős',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Feljegyzések',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'A {{plural_module_name}} modul olyan tevékenységeket listáz, amelyek még teljesítésre várnak. Egy {{module_name}} rekord tartozhat kapcsolati, vagy egyéb bejegyzésekhez. Új tétel létrehozása történhet hozzáadással, kettőzéssel, importálással, illetve egyéb módokon is. Az új bejegyzések adatait rekord nézetben van lehetősége szerkeszteni, illetve egyes bejegyzések a Naptár modulban is szerkeszthetők lesznek. Egy {{module_name}} tétel kapcsolódhat számtalan más Sugar rekordhoz is, így a Kliens, Kapcsolati, vagy Lehetőségek modul elemeihez is.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'A {{plural_module_name}} modul olyan tevékenységeket listáz, amelyek még teljesítésre várnak. - Szerkessze ennek a rekordnak a mezőit külön-külön, vagy kattintson a Szerkesztés gombra! Tekintse meg, vagy szerkessze a linkeket a bal alsó "Adatnézet" kapcsoló használatával! Olvassa el, vagy írjon felhasználói hozzászólásokat a "Tevékenységfolyam" opcióval! A rekord neve mellett található ikonok segítségével jelölje be kedvencének a tartalmat, vagy kövesse annak utóéletét! Egyéb tevékenységek a Szerkesztés gombtól jobbra szereplő műveleti gomb legördülő menüjében találhatók.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'A {{plural_module_name}} modul flexibilis tevékenységeket, tennivalókat, vagy egyéb olyan tevékenységeket listáz, amelyek még teljesítésre várnak. 

{{module_name}} létrehozásához: 
1. töltse ki a mezőket! 
- A kötelező mezők kitöltése nélkül mentés nem lehetséges. 
- Ha hiányzó mezőt talál, kattintson a "Több mutatása" opcióra a további mezők felfedéséhez! 
2. Kattintson a "Mentés" gombra a rekord mentéséhez és a korábbi nézethez való visszatéréshez.',

);
