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
    'LBL_MODULE_NAME' => 'Coadă de operaţii',
    'LBL_MODULE_NAME_SINGULAR' => 'Coadă de operaţii',
    'LBL_MODULE_TITLE' => 'Coadă de operaţii: Pagină de pornire',
    'LBL_MODULE_ID' => 'Coadă de operaţii',
    'LBL_TARGET_ACTION' => 'Actiune',
    'LBL_FALLIBLE' => 'Failibil',
    'LBL_RERUN' => 'Reexecutare',
    'LBL_INTERFACE' => 'Interfaţă',
    'LINK_SCHEDULERSJOBS_LIST' => 'Vizualizaţi coada de operaţii',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuraţie',
    'LBL_CONFIG_PAGE' => 'Setări coadă de operaţii',
    'LBL_JOB_CANCEL_BUTTON' => 'Anulare',
    'LBL_JOB_PAUSE_BUTTON' => 'Pauză',
    'LBL_JOB_RESUME_BUTTON' => 'Reluare',
    'LBL_JOB_RERUN_BUTTON' => 'Reaşezare în coadă',
    'LBL_LIST_NAME' => 'Nume',
    'LBL_LIST_ASSIGNED_USER' => 'Solicitat de',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_RESOLUTION' => 'Rezoluţie',
    'LBL_NAME' => 'Nume slujba',
    'LBL_EXECUTE_TIME' => 'Executa Timpul',
    'LBL_SCHEDULER_ID' => 'Programator',
    'LBL_STATUS' => 'Disponibilitate',
    'LBL_RESOLUTION' => 'Rezolutie',
    'LBL_MESSAGE' => 'Mesaje',
    'LBL_DATA' => 'Data',
    'LBL_REQUEUE' => 'reincercati',
    'LBL_RETRY_COUNT' => 'Reincercari maxime',
    'LBL_FAIL_COUNT' => 'defectiuni',
    'LBL_INTERVAL' => 'Intervalul minim dintre incercari',
    'LBL_CLIENT' => 'Client propriu',
    'LBL_PERCENT' => 'Pecent complet',
    'LBL_JOB_GROUP' => 'locuri de muncă de grup',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Rezoluţie plasată în coadă',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Rezoluţie parţială',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Rezoluţie completă',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Eroare rezoluţie',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Rezoluţie anulată',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Rezoluţie în executare',
    // Errors
    'ERR_CALL' => "Nu se poate apela funcţia de:% s",
    'ERR_CURL' => "Nu Curl - nu se poate rula URL-ul locuri de munca",
    'ERR_FAILED' => "Eşec neaşteptat, vă rugăm să verificaţi  PHP şi sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s în %s pe linia %d",
    'ERR_NOUSER' => "Fara utillizator pt acest job",
    'ERR_NOSUCHUSER' => "Utilizator inexistent",
    'ERR_JOBTYPE' => "Tip job necunoscut",
    'ERR_TIMEOUT' => "Esec fortat pe timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Iov% 1 $ s (% 2 $ s) a eşuat în termen Cron',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Imposibil de încărcat duza cu ID: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Imposibil de încărcat rutina pentru ruta %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Extensia pentru această coadă nu este instalată',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Unele câmpuri sunt goale',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Setări coadă de operaţii',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuraţie principală',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuraţie Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuraţie AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuraţie Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ajutor configuraţie coadă de operaţii',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Secţiunea de configurare principală.</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>Standard</i> - utilizaţi un singur proces pentru muncitori.</li>
    <li><i>Parallel</i> - utilizaţi mai multe procese pentru muncitori.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>Coadă implicită</i> - Aceasta va utiliza doar Baza de date Sugar fără nicio coadă de mesaje.</li>
    <li><i>Amazon SQS</i> - Serviciul simplu de Coadă Amazon este un serviciu de mesagerie distribuită în coadă
    lansat de Amazon.com.
    Acesta acceptă trimiterea programată a mesajelor prin aplicaţii de servicii web ca modalitate de
    comunicare pe Internet.</li>
    <li><i>RabbitMQ</i> - este un software broker de mesaje open source (denumit uneori middleware orientat pe mesaje)
    care implementează Protocolul avansat de plasare în coadă a mesajelor (AMQP).</li>
    <li><i>Gearman</i> - este un cadru de aplicaţie open source conceput să distribuie operaţii corespunzătoare de computer
mai multor computere, astfel încât operaţiile mari să poată fi efectuate mai repede.</li>
    <li><i>Immediate</i> - La fel ca şi coada implicită, dar execută operaţia imediat după adăugare.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Ajutor Configurare Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Secţiunea Configurare Amazon SQS.</b></p>
<ul>
    <li>ID cheie de acces: <i>Introduceţi numărul ID al cheii dvs. de acces pentru Amazon SQS</i></li>
    <li>Cheie de acces Secret: <i>Introduceţi cheia dvs. de acces Secret pentru Amazon SQS</i></li>
    <li>Regiune: <i>Introduceţi regiuna pentru serverul Amazon SQS</i></li>
    <li>Nume coadă: <i>Introduceţi numele cozii pentru serverul Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Ajutor Configurare AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Secţiunea Configurare AMQP.</b></p>
<ul>
    <li>URL server: <i>Introduceţi adresa URL a serverului pentru cozile de mesaje.</i></li>
    <li>Nume utilizator: <i>Introduceţi numele dvs. de utilizator pentru RabbitMQ</i></li>
    <li>Parolă: <i>Introduceţi parola dvs. pentru RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ajutor Configurare Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Secţiunea Configurare Gearman.</b></p>
<ul>
    <li>URL server: <i>Introduceţi adresa URL a serverului pentru cozile de mesaje.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Runner',
    'LBL_SERVER_URL' => 'URL server',
    'LBL_LOGIN' => 'Nume utilizator',
    'LBL_ACCESS_KEY' => 'ID cheie de acces',
    'LBL_REGION' => 'Regiune',
    'LBL_ACCESS_KEY_SECRET' => 'Cheie de acces Secret',
    'LBL_QUEUE_NAME' => 'Nume Adapter',
);
