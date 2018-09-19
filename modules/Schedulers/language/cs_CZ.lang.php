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
'LBL_OOTB_WORKFLOW'		=> 'Zpracování úkolů workflow',
'LBL_OOTB_REPORTS'		=> 'Spustit Report Generation Scheduled Tasks --geneorvání reportů dle naplánovancýh úkolů--',
'LBL_OOTB_IE'			=> 'Kontrola poštovních schránek pro příchozí poštu',
'LBL_OOTB_BOUNCE'		=> 'Spouštět noční zpracování vrácených e-mailů z kampaní',
'LBL_OOTB_CAMPAIGN'		=> 'Spouštět noční hromadné rozesílání e-mailových kampaní',
'LBL_OOTB_PRUNE'		=> 'Provést údržbu databáze každého prvního v měsíci',
'LBL_OOTB_TRACKER'		=> 'Omezit trekovací tabulky',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Odstranit seznamy starých záznamů',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Vymazat prozatimní soubory',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Vymazat soubory diagnostiky',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Vymazat prozatimní PDF soubory',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Aktualizovat tracker_sessions tabulku',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Spustit oznamování připomenutí e-mailem',
'LBL_OOTB_CLEANUP_QUEUE' => 'Vyčistit frontu úloh',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Vytvořit budoucí časovou periodu',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Aktualizovat články KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publikovat schválené články a vypršet platnost článkům znalostní báze.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Plánovaná úloha Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Znovu sestavit nenormalizovaná data zabezpečení týmu',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Naplánované úlohy:',
'LBL_LIST_NAME' => 'Naplánovaná úloha:',
'LBL_LIST_RANGE' => 'Rozpětí:',
'LBL_LIST_REMOVE' => 'Odstranit:',
'LBL_LIST_STATUS' => 'Stav:',
'LBL_LIST_TITLE' => 'Seznam naplánovaných úloh:',
'LBL_LIST_EXECUTE_TIME' => 'Spustí se:',
// human readable:
'LBL_SUN'		=> 'Neděle',
'LBL_MON'		=> 'Pondělí',
'LBL_TUE'		=> 'Úterý',
'LBL_WED'		=> 'Středa',
'LBL_THU'		=> 'Čtvrtek',
'LBL_FRI'		=> 'Pátek',
'LBL_SAT'		=> 'Sobota',
'LBL_ALL'		=> 'Každý den',
'LBL_EVERY_DAY'	=> 'Každý den',
'LBL_AT_THE'	=> 'V',
'LBL_EVERY'		=> 'Každý',
'LBL_FROM'		=> 'Odesílatel',
'LBL_ON_THE'	=> 'V',
'LBL_RANGE'		=> 'do',
'LBL_AT' 		=> 'v',
'LBL_IN'		=> 'v',
'LBL_AND'		=> 'a',
'LBL_MINUTES'	=> 'minuty',
'LBL_HOUR'		=> 'hodin',
'LBL_HOUR_SING'	=> 'hodina',
'LBL_MONTH'		=> 'měsíc',
'LBL_OFTEN'		=> 'Tak často jak jen možné.',
'LBL_MIN_MARK'	=> 'označení minuty',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'hod',
'LBL_DAY_OF_MONTH' => 'datum',
'LBL_MONTHS' => 'měs',
'LBL_DAY_OF_WEEK' => 'den',
'LBL_CRONTAB_EXAMPLES' => 'To co je nahoře používá standartní crontab zápis',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Funkcionalita CRONu založená na časové zóně nastavené na serveru (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Prosím, nastavte spuštění plánovače času odpovídajícím způsobem.',
// Labels
'LBL_ALWAYS' => 'Vždy',
'LBL_CATCH_UP' => 'Spustit, pokud mine',
'LBL_CATCH_UP_WARNING' => 'Odškrtněte toto, pokud tento program poběží déle než chvilku.',
'LBL_DATE_TIME_END' => 'Datum a čas konce',
'LBL_DATE_TIME_START' => 'Datum a čas začátku',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Práce',
'LBL_JOB_URL' => 'URL úlohy',
'LBL_LAST_RUN' => 'Poslední úspěšný běh',
'LBL_MODULE_NAME' => 'Sugar plány',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar plány',
'LBL_MODULE_TITLE' => 'Plánovač',
'LBL_NAME' => 'Název naplánované úlohy',
'LBL_NEVER' => 'Nikdy',
'LBL_NEW_FORM_TITLE' => 'Nový plán',
'LBL_PERENNIAL' => 'trvalý',
'LBL_SEARCH_FORM_TITLE' => 'Hledání v plánu',
'LBL_SCHEDULER' => 'Naplánovaná úloha:',
'LBL_STATUS' => 'Stav',
'LBL_TIME_FROM' => 'Aktivní od',
'LBL_TIME_TO' => 'Aktivní do',
'LBL_WARN_CURL_TITLE' => 'cURL varování:',
'LBL_WARN_CURL' => 'Varování:',
'LBL_WARN_NO_CURL' => 'Tento systém nemá cURL knihovny aktnivní/zkompilované v PHP. Prosím kontaktujte svého administrátora, aby tento problém vyřešil. Bey cURL knihovny plánovače nebudou fungovat.',
'LBL_BASIC_OPTIONS' => 'Základní nastavení',
'LBL_ADV_OPTIONS'		=> 'Rozšířené volby',
'LBL_TOGGLE_ADV' => 'Rozšířené volby',
'LBL_TOGGLE_BASIC' => 'Základní volby',
// Links
'LNK_LIST_SCHEDULER' => 'Plánovač',
'LNK_NEW_SCHEDULER' => 'Vytvořit plán',
'LNK_LIST_SCHEDULED' => 'Naplánované úkoly',
// Messages
'SOCK_GREETING' => "Toto je rozhraní pro SugarCRM plánovací službu. Příkazy daemona jsou: start|restart|shutdown|status Pro odchod napište [quit]. Pro vypnutí služby [shutdown]",
'ERR_DELETE_RECORD' => 'Pro vymazání plánovače musí být specifikováno číslo záznamu.',
'ERR_CRON_SYNTAX' => 'Špatná Cron syntax',
'NTC_DELETE_CONFIRMATION' => 'Jste si jist, že chcete smazat tento záznam?',
'NTC_STATUS' => 'Pro vymazání musíte nastavit Neaktivní z výběrového listu.',
'NTC_LIST_ORDER' => 'Set the order this Schedule will appear in the Scheduler dropdown lists',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Nastavení Windows scheduleru',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Nastavení Crontabu',
'LBL_CRON_LINUX_DESC' => 'Přidejte tuto řádku do Vašeho crontabu:',
'LBL_CRON_WINDOWS_DESC' => 'Vytvořte dávkový soubor s následujícími příkazy:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Log akcí',
'LBL_EXECUTE_TIME'			=> 'Čas spuštění',

//jobstrings
'LBL_REFRESHJOBS' => 'Obnovit práce',
'LBL_POLLMONITOREDINBOXES' => 'Zkontrolovat Inbound Mail Accounts',
'LBL_PERFORMFULLFTSINDEX' => 'Full-textový vyhledávač',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Odstranit dočasné soubory PDF',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publikovat schválené články a ukončit platnost článkům znalostní báze.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Plánovač fronty vyhledávače Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Odstranit soubory diagnostického nástroje',
'LBL_SUGARJOBREMOVETMPFILES' => 'Odstranit dočasné soubory',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Znovu sestavit nenormalizovaná data zabezpečení týmu',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Spouštět noční hromadné rozesílání e-mailových kampaní',
'LBL_ASYNCMASSUPDATE' => 'Provést asynchroní hromadnou úpravu',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Spouštět noční zpracování vrácených e-mailů z kampaní',
'LBL_PRUNEDATABASE' => 'Provést údržbu databáze každého prvního v měsíci',
'LBL_TRIMTRACKER' => 'Omezit trekovací tabulky',
'LBL_PROCESSWORKFLOW' => 'Zpracování úkolů workflow',
'LBL_PROCESSQUEUE' => 'Spustit Report Generation Scheduled Tasks --geneorvání reportů dle naplánovancýh úkolů--',
'LBL_UPDATETRACKERSESSIONS' => 'Aktualizovat trekovací tabulky',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Vytvořit budoucí časovou periodu',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Spustit odesílání připomenutí e-mailem',
'LBL_CLEANJOBQUEUE' => 'Vyčištění fronty úloh',
'LBL_CLEANOLDRECORDLISTS' => 'Vyčistit seznamy starých záznamů',
'LBL_PMSEENGINECRON' => 'Plánovač Advanced Workflow',
);

