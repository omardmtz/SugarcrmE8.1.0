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
    'LBL_MODULE_NAME' => 'Tööde järjekord',
    'LBL_MODULE_NAME_SINGULAR' => 'Tööde järjekord',
    'LBL_MODULE_TITLE' => 'Tööde järjekord: avaleht',
    'LBL_MODULE_ID' => 'Tööde järjekord',
    'LBL_TARGET_ACTION' => 'Tegevus',
    'LBL_FALLIBLE' => 'Fallible',
    'LBL_RERUN' => 'Käivita uuesti',
    'LBL_INTERFACE' => 'Liides',
    'LINK_SCHEDULERSJOBS_LIST' => 'Kuva tööde järjekord',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Konfiguratsioon',
    'LBL_CONFIG_PAGE' => 'Tööde järjekorra sätted',
    'LBL_JOB_CANCEL_BUTTON' => 'Tühista',
    'LBL_JOB_PAUSE_BUTTON' => 'Peata',
    'LBL_JOB_RESUME_BUTTON' => 'Jätka',
    'LBL_JOB_RERUN_BUTTON' => 'Pane uuesti järjekorda',
    'LBL_LIST_NAME' => 'Nimi',
    'LBL_LIST_ASSIGNED_USER' => 'Taotleja',
    'LBL_LIST_STATUS' => 'Olek',
    'LBL_LIST_RESOLUTION' => 'Resolutsioon',
    'LBL_NAME' => 'Töö nimi',
    'LBL_EXECUTE_TIME' => 'Täitmisaeg',
    'LBL_SCHEDULER_ID' => 'Planeerija',
    'LBL_STATUS' => 'Töö olek',
    'LBL_RESOLUTION' => 'Tulemus',
    'LBL_MESSAGE' => 'Sõnumid',
    'LBL_DATA' => 'Töö andmed',
    'LBL_REQUEUE' => 'Tõrke korral proovige uuesti',
    'LBL_RETRY_COUNT' => 'Maksimaalsed uued katsed',
    'LBL_FAIL_COUNT' => 'Tõrked',
    'LBL_INTERVAL' => 'Minimaalne proovimiste vaheline vahemik',
    'LBL_CLIENT' => 'Omav klient',
    'LBL_PERCENT' => 'Lõpetamise protsent',
    'LBL_JOB_GROUP' => 'Töögrupp',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolutsioon järjekorda pandud',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolutsioon osaline',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolutsioon täielik',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Resolutsiooni tõrge',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Tühistatud resolutsioon',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Resolutsioon töötab',
    // Errors
    'ERR_CALL' => "Funktsiooni kutse pole võimalik: %s",
    'ERR_CURL' => "CURL puudub – URL-töid ei saa käivitada",
    'ERR_FAILED' => "Ootamatu tõrge, kontrollige PHP logisid ja suvandit sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s suvandis %s real %d",
    'ERR_NOUSER' => "Ühtegi kasutaja ID-d pole töö puhul määratud",
    'ERR_NOSUCHUSER' => "Kasutaja ID-d %s ei leitud",
    'ERR_JOBTYPE' => "Tundmatu töö tüüp: %s",
    'ERR_TIMEOUT' => "Sunnitud tõrge ajalõpul",
    'ERR_JOB_FAILED_VERBOSE' => 'Töö %1$s (%2$s) nurjus CRON-i käivitamisel',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Ei saa laadida uba ID-ga: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Marsruudi %s draiverit pole võimalik leida',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Selle järjekorra laiend pole installitud',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Mõni väli on tühi',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Tööde järjekorra sätted',
    'LBL_CONFIG_MAIN_SECTION' => 'Põhikonfiguratsioon',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearmani konfiguratsioon',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP konfiguratsioon',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs konfiguratsioon',
    'LBL_CONFIG_SERVERS_TITLE' => 'Tööde järjekorra konfiguratsiooni spikker',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Jaotis Põhikonfiguratsioon.</b></p>
<ul>
<li>Käivitaja:
<ul>
<li><i>Standardne</i> – kasutage töötajate puhul ainult ühte protsessi.</li>
<li><i>Paralleelne</i> – kasutage töötajate puhul paari protsessi.</li>
</ul>
</li>
<li>Adapter:
<ul>
<li><i>Vaikejärjekord</i> – see kasutab ainult Sugari andmebaasi ilma teate järjekorrata.</li>
<li><i>Amazon SQS</i> – Amazon Simple Queue Service on Amazon.com-i kehtestatud järjekorra teatamise hajusteenus.
See toetab teadete programmilist saatmist veebiteenuse kaudu interneti teel suhtlemise viisina.</li>
<li><i>RabbitMQ</i> – on avatud lähtekoodiga teate vahendaja tarkvara (mõnikord kutsutakse ka nimetusega teatekeskne vahevara), mis rakendab protokolli Advanced Message Queuing Protocol (AMQP).</li>
<li><i>Gearman</i> – on avatud lähtekoodiga rakenduse raamistik, mis on loodud asjakohaste arvutitoimingute levitamiseks mitmele arvutile, nii et suuri toiminguid oleks võimalik teha kiiremini.</li>
<li><i>Kohene</i> – nagu vaikejärjekord, kuid täidab ülesande kohe pärast lisamist.</li>
</ul>
</li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS-i konfiguratsiooni spikker',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Jaotis Amazon SQS konfiguratsioon.</b></p>
<ul>
<li>Pääsuvõtme ID: <i>sisestage Amazon SQS-i puhul oma pääsuvõtme ID</i></li>
<li>Salajane pääsuvõti: <i>sisestage Amazon SQS-i puhul salajane pääsuvõti</i></li>
<li>Piirkond: <i>sisestage Amazon SQS-i serveri piirkond</i></li>
<li>Järjekorra nimi: <i>sisestage Amazon SQS-i serveri järjekorra nimi</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP konfiguratsiooni spikker',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Jaotis AMQP konfiguratsioon.</b></p>
<ul>
<li>Serveri URL: <i>sisestage oma teadete järjekorra serveri URL.</i></li>
<li>Logi sisse: <i>sisestage oma RabbitMQ sisselogimisandmed</i></li>
<li>Parool: <i>sisestage oma RabbitMQ parool</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearmani konfiguratsiooni spikker',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Jaotis Gearmani konfiguratsioon.</b></p>
<ul>
<li>Serveri URL: <i>sisestage oma teadete järjekorra serveri URL.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adapter',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Käitaja',
    'LBL_SERVER_URL' => 'Serveri URL',
    'LBL_LOGIN' => 'Logi sisse',
    'LBL_ACCESS_KEY' => 'Pääsuvõtme ID',
    'LBL_REGION' => 'Piirkond',
    'LBL_ACCESS_KEY_SECRET' => 'Salajane pääsuvõti',
    'LBL_QUEUE_NAME' => 'Adapteri nimi',
);
