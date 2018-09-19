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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'Munkafolyamat feladatainak végrehajtása',
'LBL_OOTB_REPORTS'		=> 'Jelentéskészítő ütemezett feladatok futtatása',
'LBL_OOTB_IE'			=> 'Ellenőrizze a bejövő postaládákat',
'LBL_OOTB_BOUNCE'		=> 'Email-kampány visszapattanóinak éjszakai feldolgozása',
'LBL_OOTB_CAMPAIGN'		=> 'Éjszakai email-kampány indítása',
'LBL_OOTB_PRUNE'		=> 'Adatbázis vágása a hónap első napján',
'LBL_OOTB_TRACKER'		=> 'Követő táblázatok vágása',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Régi rekord listák metszése',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Ideiglenes fájlok eltávolítása',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Diagnosztikai eszközfájlok eltávolítása',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Ideiglenes PDF fájlok eltávolítása',
'LBL_UPDATE_TRACKER_SESSIONS' => 'tracker_sessions tábláinak frissítése',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Futtassa az Email Emlékeztető Értesítéseket',
'LBL_OOTB_CLEANUP_QUEUE' => 'Munkalista törlése',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Időperiódusok létrehozása',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'KBContent cikkek frissítése.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Jóváhagyott cikkek közzététele és KB cikkek lejárttá tétele.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow ütemezett feladat',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Denormalizált csapat biztonsági adatok újraépítése',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervallum:',
'LBL_LIST_LIST_ORDER' => 'Ütemezők:',
'LBL_LIST_NAME' => 'Ütemező:',
'LBL_LIST_RANGE' => 'Tartomány:',
'LBL_LIST_REMOVE' => 'Töröl:',
'LBL_LIST_STATUS' => 'Állapot:',
'LBL_LIST_TITLE' => 'Ütemezőlista:',
'LBL_LIST_EXECUTE_TIME' => 'Következő végrehajtás:',
// human readable:
'LBL_SUN'		=> 'Vasárnap',
'LBL_MON'		=> 'Hétfő',
'LBL_TUE'		=> 'Kedd',
'LBL_WED'		=> 'Szerda',
'LBL_THU'		=> 'Csütörtök',
'LBL_FRI'		=> 'Péntek',
'LBL_SAT'		=> 'Szombat',
'LBL_ALL'		=> 'Minden nap',
'LBL_EVERY_DAY'	=> 'Minden nap',
'LBL_AT_THE'	=> 'Ekkor',
'LBL_EVERY'		=> 'Minden',
'LBL_FROM'		=> 'Ettől',
'LBL_ON_THE'	=> 'Pontosan',
'LBL_RANGE'		=> 'Eddig',
'LBL_AT' 		=> 'ekkor',
'LBL_IN'		=> 'múlva',
'LBL_AND'		=> 'és',
'LBL_MINUTES'	=> 'perc',
'LBL_HOUR'		=> 'óra',
'LBL_HOUR_SING'	=> 'óra',
'LBL_MONTH'		=> 'hónap',
'LBL_OFTEN'		=> 'Amilyen gyakran lehetséges.',
'LBL_MIN_MARK'	=> 'percjelző',


// crontabs
'LBL_MINS' => 'perc',
'LBL_HOURS' => 'óra',
'LBL_DAY_OF_MONTH' => 'dátum',
'LBL_MONTHS' => 'hó',
'LBL_DAY_OF_WEEK' => 'nap',
'LBL_CRONTAB_EXAMPLES' => 'A fentiek szabványos crontab jelölést használnak.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'A cron specifikációk a szerver időzónája szerint futnak (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Kérem, adja meg pontosan a kivitelezés idejét!',
// Labels
'LBL_ALWAYS' => 'Mindig',
'LBL_CATCH_UP' => 'Végrehajtás, ha nem talált',
'LBL_CATCH_UP_WARNING' => 'Törölje, ha ennek a munkának a futtatása több időt vehet igénybe!',
'LBL_DATE_TIME_END' => 'Befejezés dátuma és ideje',
'LBL_DATE_TIME_START' => 'Kezdés dátuma és ideje',
'LBL_INTERVAL' => 'intervallum',
'LBL_JOB' => 'Munka',
'LBL_JOB_URL' => 'Munka URL',
'LBL_LAST_RUN' => 'Legutóbbi sikeres végrehajtás',
'LBL_MODULE_NAME' => 'Sugar Ütemező',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar Ütemező',
'LBL_MODULE_TITLE' => 'Ütemezők',
'LBL_NAME' => 'A munka megnevezése',
'LBL_NEVER' => 'Soha',
'LBL_NEW_FORM_TITLE' => 'Új ütemezés létrehozása',
'LBL_PERENNIAL' => 'folyamatos',
'LBL_SEARCH_FORM_TITLE' => 'Ütemező keresése',
'LBL_SCHEDULER' => 'Ütemező:',
'LBL_STATUS' => 'Állapot',
'LBL_TIME_FROM' => 'Aktív ettől',
'LBL_TIME_TO' => 'Aktív eddig',
'LBL_WARN_CURL_TITLE' => 'cURL figyelem:',
'LBL_WARN_CURL' => 'Figyelem:',
'LBL_WARN_NO_CURL' => 'Ez a rendszer nem rendelkezik  engedélyezett cURL könyvtárral /  PHP modulba befordítva --with-curl=/path/to/curl_library). Kérem, lépjen kapcsolatba a rendszergazdával, hogy megoldja ezt a problémát. A cURL funkcionalitás nélkül, az ütemező nem tudja végrehajtani a feladatokat.',
'LBL_BASIC_OPTIONS' => 'Alapbeállítások',
'LBL_ADV_OPTIONS'		=> 'Speciális beállítások',
'LBL_TOGGLE_ADV' => 'Speciális beállítások megjelenítése',
'LBL_TOGGLE_BASIC' => 'Alapbeállítások megjelenítése',
// Links
'LNK_LIST_SCHEDULER' => 'Ütemezők',
'LNK_NEW_SCHEDULER' => 'Ütemező létrehozása',
'LNK_LIST_SCHEDULED' => 'Ütemezett feladatok',
// Messages
'SOCK_GREETING' => "Ez a SugarCRM Ütemező szolgáltatása. [Elérhető kiszolgáló parancsok: start | restart | shutdown | status] A kilépéshez írja be a \"quit\" szót, a szolgáltatás leállításához pedig billentyűzze be a \"shutdown\" parancsot!",
'ERR_DELETE_RECORD' => 'Adjon meg egy azonosítót az ütemezés törléséhez!',
'ERR_CRON_SYNTAX' => 'Érvénytelen cron szintaxis',
'NTC_DELETE_CONFIRMATION' => 'Biztos benne, hogy törölni kívánja ezt rekordot?',
'NTC_STATUS' => 'Hogy eltávolítsa ezt az ütemezést a legördülő listából, állítsa állapotát inaktívra',
'NTC_LIST_ORDER' => 'Állítsa be a sorrendet, ahogy a határidő meg fog jelenni az Ütemező legördülő listájában',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Átlépés a Windows feladatütemező beállításaiba',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Átlépés a cron-beállítás fülre',
'LBL_CRON_LINUX_DESC' => 'Megjegyzés: ahhoz, hogy futtatni tudja a Sugar Ütemezőt, adja hozzá a következő sort a crontab fájlhoz:',
'LBL_CRON_WINDOWS_DESC' => 'Megjegyzés: ahhoz, hogy futtatni tudja a Sugar Ütemezőt, hozzon létre egy batch fájlt, amelyet lefuttat a Windows Ütemezett feladatok között. A kötegelt fájlnak tartalmaznia kell a következő parancsokat:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Munkanapló',
'LBL_EXECUTE_TIME'			=> 'Végrehajtás ideje',

//jobstrings
'LBL_REFRESHJOBS' => 'Munkák frissítése',
'LBL_POLLMONITOREDINBOXES' => 'Ellenőrizze a beérkező leveleit',
'LBL_PERFORMFULLFTSINDEX' => 'Rendszerindexálás szöveges kereséshez',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Ideiglenes PDF fájlok eltávolítása',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Jóváhagyott cikkek publikálása és KB cikkek elévültté nyilvánítása.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch sorütemező',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Diagnosztikai eszközfájlok törlése',
'LBL_SUGARJOBREMOVETMPFILES' => 'Ideiglenes fájlok törlése',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Denormalizált csapat biztonsági adatok újraépítése',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Tömeges kampány emailek éjszakai kiküldése',
'LBL_ASYNCMASSUPDATE' => 'Aszinkron tömeges frissítések végrehajtása',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Email-kampány visszapattanóinak éjszakai feldolgozásának indítása',
'LBL_PRUNEDATABASE' => 'Adatbázis vágása a hónap első napján',
'LBL_TRIMTRACKER' => 'Követő táblák vágása',
'LBL_PROCESSWORKFLOW' => 'Munkafolyamat feladatainak végrehajtása',
'LBL_PROCESSQUEUE' => 'Jelentéskészítő ütemezett feladatok futtatása',
'LBL_UPDATETRACKERSESSIONS' => 'Követő folyamattáblák frissítése',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Időperiódusok létrehozása',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Email emlékeztetők küldésének futtatása',
'LBL_CLEANJOBQUEUE' => 'Munkalista törlése',
'LBL_CLEANOLDRECORDLISTS' => 'Régi rekord listák törlése',
'LBL_PMSEENGINECRON' => 'Advanced Workflow ütemező',
);

