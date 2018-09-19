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
    'LBL_MODULE_NAME' => 'Radha e punës',
    'LBL_MODULE_NAME_SINGULAR' => 'Radha e punës',
    'LBL_MODULE_TITLE' => 'Radha e punës: Shtëpi',
    'LBL_MODULE_ID' => 'Radha e punës',
    'LBL_TARGET_ACTION' => 'Veprim',
    'LBL_FALLIBLE' => 'I rremë',
    'LBL_RERUN' => 'Rihap',
    'LBL_INTERFACE' => 'Ndërfaqja',
    'LINK_SCHEDULERSJOBS_LIST' => 'Shiko radhën e punës',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfigurimi',
    'LBL_CONFIG_PAGE' => 'Cilësimet e radhës së punës',
    'LBL_JOB_CANCEL_BUTTON' => 'Anulo',
    'LBL_JOB_PAUSE_BUTTON' => 'Pauzë',
    'LBL_JOB_RESUME_BUTTON' => 'Rifillo',
    'LBL_JOB_RERUN_BUTTON' => 'Riradhit',
    'LBL_LIST_NAME' => 'Emri',
    'LBL_LIST_ASSIGNED_USER' => 'Kërkohet nga',
    'LBL_LIST_STATUS' => 'Statusi',
    'LBL_LIST_RESOLUTION' => 'Rezolucioni',
    'LBL_NAME' => 'Emri i punës',
    'LBL_EXECUTE_TIME' => 'Koha e ekzekutimit',
    'LBL_SCHEDULER_ID' => 'Planifikuesi',
    'LBL_STATUS' => 'Statusi i punës',
    'LBL_RESOLUTION' => 'Rezultati',
    'LBL_MESSAGE' => 'Mesazhe',
    'LBL_DATA' => 'Të dhënat e punës',
    'LBL_REQUEUE' => 'Përsëritje e dështimit',
    'LBL_RETRY_COUNT' => 'Përpjekjet maksimale',
    'LBL_FAIL_COUNT' => 'Dështimet',
    'LBL_INTERVAL' => 'Intervali minimal mes përpjekjeve',
    'LBL_CLIENT' => 'Klient i pronësuar',
    'LBL_PERCENT' => 'Përqindje e përfunduar',
    'LBL_JOB_GROUP' => 'grup pune',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Rezolucioni në radhë',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Rezolucion i pjesshëm',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Rezolucioni i plotë',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Defekt në rezolucion',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Rezolucioni u anulua',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Rezolucioni aktiv',
    // Errors
    'ERR_CALL' => "Nuk mund të thërras funksionin: %s",
    'ERR_CURL' => "Nu ka CURL - nuk mund të hapë punë URL",
    'ERR_FAILED' => "Dështim i papritur, ju lutemi kontrolloni hyrjet PHP dhe sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s në %s në linje %d",
    'ERR_NOUSER' => "Nuk ka ID të specifikuar të përdoruesit për këtë punë.",
    'ERR_NOSUCHUSER' => "ID e përdoruesit %s nuk është gjetur",
    'ERR_JOBTYPE' => "Lloj i panjohur i punës.%s",
    'ERR_TIMEOUT' => "Dështim i detyruar në skadim kohe",
    'ERR_JOB_FAILED_VERBOSE' => 'Puna %1$s (%2$s) dështoi në drejtim të CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Nuk mund të ngarkojë detyrën me id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Nuk mund të gjejë emrin për itinerarin %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Zgjatimi për këtë radhë nuk është instaluar',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Disa nga fushat janë bosh',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Cilësimet e radhës së punës',
    'LBL_CONFIG_MAIN_SECTION' => 'Konfigurimi kryesor',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Konfigurimi Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Konfigurimi AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Konfigurimi Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ndihmë në konfigurimin e radhës së punës',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Seksioni kryesor i konfigurimit.</b></p>
<ul>
    <li>Konvejeri:
    <ul>
    <li><i>Standard</i> - përdorni vetëm një proces për punëtorët.</li>
    <li><i>Paralel</i> - përdorni disa procese për punëtorët.</li>
    </ul>
    </li>
    <li>Përshtatësi:
    <ul>
    <li><i>Radha e paracaktuar</i> - Kjo do të përdorë vetëm bazën e të dhënave të Sugar pa asnjë radhë mesazhi.</li>
    <li><i>Amazon SQS</i> - Shërbimi i thjeshtë i radhës Amazon është një shërbim mesazhimi me radhë të shpërndarë, i prezantuar nga Amazon.com.
    Ai mbështet dërgimin programatik të mesazheve nëpërmjet aplikacioneve të shërbimit në rrjet si mënyrë për të komunikuar nga interneti.</li>
    <li><i>RabbitMQ</i> - është një softuer mesazhesh i ndërmjetëm me burim të hapur (ndonjëherë i quajtur midëluer i orientuar nga mesazhet)
    i cili implementon Protokollin e avancuar të radhës së mesazheve (AMQP).</li>
    <li><i>Gearman</i> - është një kuadër aplikacioni me burim të hapur, i ndërtuar për të shpërndarë detyrat e përshtatshme të kompjuterëve në shumë kompjuterë, në mënyrë që detyrat të kryhen më shpejt.</li>
    <li><i>I menjëhershëm</i> - Njësoj si radha e paracaktuar, por e ekzekuton detyrën menjëherë pasi shtimit.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Ndihmë në konfigurimin e Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Seksioni i konfigurimit të Amazon SQS.</b></p>
<ul>
    <li>ID-ja e çelësit të hyrjes: <i>Vendosni numrin e ID-së së çelësit të hyrjes për Amazon SQS</i></li>
    <li>Çelësi sekret i hyrjes: <i>Vendosni çelësin sekret të hyrjes për Amazon SQS</i></li>
    <li>Rajoni: <i>Vendosni rajonin e serverit Amazon SQS</i></li>
    <li>Emri i radhës: <i>Vendosni emrin e radhës së serverit të Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Ndihmë në konfigurimin e AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Seksioni i konfigurimit të AMQP.</b></p>
<ul>
    <li>URL-ja e serverit: <i>Vendosni URL-në e serverit të radhës së mesazhit.</i></li>
    <li>Identifikimi: <i>Vendosni identifikimin tuaj për RabbitMQ</i></li>
    <li>Fjalëkalimi: <i>Vendosni fjalëkalimin tuaj për RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ndihmë në konfigurimin e Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Seksioni i konfigurimit të Gearman.</b></p>
<ul>
    <li>URL-ja e serverit: <i>Vendosni URL-në e serverit të radhës së mesazhit.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Përshtatës',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Luajtës',
    'LBL_SERVER_URL' => 'URL e serverit',
    'LBL_LOGIN' => 'Identifikohu',
    'LBL_ACCESS_KEY' => 'ID-ja e çelësit të hyrjes',
    'LBL_REGION' => 'Rajoni',
    'LBL_ACCESS_KEY_SECRET' => 'Çelës sekret hyrjeje',
    'LBL_QUEUE_NAME' => 'Emri i përshatësit',
);
