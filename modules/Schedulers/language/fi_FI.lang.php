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
'LBL_OOTB_WORKFLOW'		=> 'Prosessoi Work Flow -tehtävät',
'LBL_OOTB_REPORTS'		=> 'Suorita raporttigeneraation ajastetut toiminnot',
'LBL_OOTB_IE'			=> 'Tarkista saapuvan postin kansiot',
'LBL_OOTB_BOUNCE'		=> 'Suorita yöllinen prosessi palautuneille kampanjasähköposteille',
'LBL_OOTB_CAMPAIGN'		=> 'Suorita yölliset massaviestikampanjat',
'LBL_OOTB_PRUNE'		=> 'Karsi tietokanta kuukauden ensimmäisenä päivänä',
'LBL_OOTB_TRACKER'		=> 'Karsi seurantataulukot',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Poista vanhat tietuelistat',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Poista väliaikaiset tiedostot.',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Poista diagnostiikkatyökalujen tiedostot.',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Poista väliaikaiset PDF-tiedostot.',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Päivitä tracker_sessions -taulukko',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Suorita sähköpostin muistutusilmoitukset',
'LBL_OOTB_CLEANUP_QUEUE' => 'Puhdista työjono',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Luo tulevaisuuteen ajanjaksoja',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Päivitä KBContent-artikkelit.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Julkaise hyväksytyt artikkelit ja sulje KB-artikkelit.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflown ajoitettu työ',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Muodosta uudelleen denormalisoidut tiimin suojaustiedot',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Aikaväli:',
'LBL_LIST_LIST_ORDER' => 'Ajastimet:',
'LBL_LIST_NAME' => 'Ajastin',
'LBL_LIST_RANGE' => 'Väli:',
'LBL_LIST_REMOVE' => 'Poista:',
'LBL_LIST_STATUS' => 'Tila:',
'LBL_LIST_TITLE' => 'Aikataululista:',
'LBL_LIST_EXECUTE_TIME' => 'Tullaan ajamaan:',
// human readable:
'LBL_SUN'		=> 'sunnuntai',
'LBL_MON'		=> 'maanantai',
'LBL_TUE'		=> 'tiistai',
'LBL_WED'		=> 'keskiviikko',
'LBL_THU'		=> 'torstai',
'LBL_FRI'		=> 'perjantai',
'LBL_SAT'		=> 'lauantai',
'LBL_ALL'		=> 'joka päivä',
'LBL_EVERY_DAY'	=> 'Joka päivä',
'LBL_AT_THE'	=> 'at the',
'LBL_EVERY'		=> 'joka',
'LBL_FROM'		=> 'Alkaen',
'LBL_ON_THE'	=> 'Tasa',
'LBL_RANGE'		=> 'loppuen',
'LBL_AT' 		=> 'at',
'LBL_IN'		=> 'in',
'LBL_AND'		=> 'ja',
'LBL_MINUTES'	=> 'minuuttia',
'LBL_HOUR'		=> 'tunnin välein',
'LBL_HOUR_SING'	=> 'tasatunneittain',
'LBL_MONTH'		=> 'kuukausittain',
'LBL_OFTEN'		=> 'Niin usein kuin mahdollista.',
'LBL_MIN_MARK'	=> 'minuutin välein',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'h',
'LBL_DAY_OF_MONTH' => 'päivämäärä',
'LBL_MONTHS' => 'kk',
'LBL_DAY_OF_WEEK' => 'viikonpäivä',
'LBL_CRONTAB_EXAMPLES' => 'Edellämainittu käyttää standardinmukaista crontab-notaatiota.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Cron-määritykset ajetaan palvelimen aikavyöhykkeen mukaan (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Aseta ajastuksien suoritusaika sen mukaan.',
// Labels
'LBL_ALWAYS' => 'Aina',
'LBL_CATCH_UP' => 'Suorita, jos myöhässä',
'LBL_CATCH_UP_WARNING' => 'Älä rastita tätä, jos työ kestää enemmän kuin vain hetken.',
'LBL_DATE_TIME_END' => 'Päättyy',
'LBL_DATE_TIME_START' => 'Alkaa',
'LBL_INTERVAL' => 'Aikaväli',
'LBL_JOB' => 'Tehtävä',
'LBL_JOB_URL' => 'Tehtävän URL',
'LBL_LAST_RUN' => 'Viimeisin onnistunut ajokerta',
'LBL_MODULE_NAME' => 'Ajastetut tehtävät',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar Scheduler',
'LBL_MODULE_TITLE' => 'Ajastetut tehtävät',
'LBL_NAME' => 'Nimi',
'LBL_NEVER' => 'Ei koskaan',
'LBL_NEW_FORM_TITLE' => 'Uusi aikataulu',
'LBL_PERENNIAL' => 'jatkuva',
'LBL_SEARCH_FORM_TITLE' => 'Ajastimien haku',
'LBL_SCHEDULER' => 'Ajastin:',
'LBL_STATUS' => 'Tila',
'LBL_TIME_FROM' => 'Aktiivinen, lähtien',
'LBL_TIME_TO' => 'Aktiivinen, päättyen',
'LBL_WARN_CURL_TITLE' => 'cURL-varoitus:',
'LBL_WARN_CURL' => 'Varoitus:',
'LBL_WARN_NO_CURL' => 'Tässä järjestelmässä ei ole otettu käyttöön cURL-kirjastoja PHP-moduulina (--with-curl=/polku/curl/kirjastoon). Ota yhteys järjestelmänvalvojaan korjataksesi asian. Ilman cURL-toimintoja, ajastetut tehtävät eivät voi käyttää säikeitä.',
'LBL_BASIC_OPTIONS' => 'Perusasetukset',
'LBL_ADV_OPTIONS'		=> 'Lisäasetukset',
'LBL_TOGGLE_ADV' => 'Näytä lisäasetukset',
'LBL_TOGGLE_BASIC' => 'Näytä perusasetukset',
// Links
'LNK_LIST_SCHEDULER' => 'Ajastetut tehtävät',
'LNK_NEW_SCHEDULER' => 'Ajasta tehtävä',
'LNK_LIST_SCHEDULED' => 'Ajastetut tehtävät',
// Messages
'SOCK_GREETING' => "Tämä on SugarCRM:n ajastuspalvelu.<br />[ Mahdolliset daemon-komennot: start|restart|shutdown|status ]<br />Poistuaksesi kirjoita ‘quit’.  Sammuttaaksesi palvelun kirjoita ‘shutdown’.",
'ERR_DELETE_RECORD' => 'Tietuenumero tulee määritellä, jotta voit poistaa tilin.',
'ERR_CRON_SYNTAX' => 'Kelvoton Cron-syntaksi',
'NTC_DELETE_CONFIRMATION' => 'Haluatko poistaa tämän tietueen?',
'NTC_STATUS' => 'Aseta tila epäaktiiviseksi poistaaksesi tämän ajastuksen ajastettujen tehtävien pudotusvalikoista',
'NTC_LIST_ORDER' => 'Aseta järjestys, jossa tämä ajastin näkyy ajastettujen tehtävien pudotusvalikoissa',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Asettaaksesi Windowsin ajastetut tehtävät',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Asettaaksesi Crontabin',
'LBL_CRON_LINUX_DESC' => 'Huomio: ajaaksesi Sugarin ajastettuja tehtäviä, lisää seuraava rivi crontab-tiedostoon:',
'LBL_CRON_WINDOWS_DESC' => 'Huomio: ajaaksesi Sugarin ajastettuja tehtäviä, luo komentotiedosto Windowsin ajastettujen tehtävien ajamaksi. Komentotiedoston tulisi sisältää seuraavat komennot:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Tehtäväloki',
'LBL_EXECUTE_TIME'			=> 'Suoritusaika',

//jobstrings
'LBL_REFRESHJOBS' => 'Virkistä tehtävät',
'LBL_POLLMONITOREDINBOXES' => 'Tarkista saapuvan postin tilit',
'LBL_PERFORMFULLFTSINDEX' => 'Täysi tekstinhakuindeksin päivitys',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Poista väliaikaiset PDF-tiedostot',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Julkaise hyväksytyt artikkelit ja sulje KB-artikkelit.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch jonon ajoittaja',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Poista diagnostiikkatyökalujen tiedostot',
'LBL_SUGARJOBREMOVETMPFILES' => 'Poista väliaikaiset tiedostot',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Muodosta uudelleen denormalisoidut tiimin suojaustiedot',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Suorita yölliset massaviestikampanjat',
'LBL_ASYNCMASSUPDATE' => 'Suorita asynkroniset massapäivitykset',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Suorita yöllinen prosessi palautuneille kampanjasähköposteille',
'LBL_PRUNEDATABASE' => 'Karsi tietokanta kuukauden ensimmäisenä päivänä',
'LBL_TRIMTRACKER' => 'Karsi seurantataulukot',
'LBL_PROCESSWORKFLOW' => 'Prosessoi Work Flow -tehtävät',
'LBL_PROCESSQUEUE' => 'Suorita raporttigeneroinnin ajastetut tehtävät',
'LBL_UPDATETRACKERSESSIONS' => 'Päivitä tracker sessionin taulut',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Luo tulevaisuuteen ajanjaksoja',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Suorita sähköpostimuistutusten lähetys',
'LBL_CLEANJOBQUEUE' => 'Puhdista työjono',
'LBL_CLEANOLDRECORDLISTS' => 'Siisti vanhat tietuelistat',
'LBL_PMSEENGINECRON' => 'Advanced Workflown aikatauluttaja',
);

