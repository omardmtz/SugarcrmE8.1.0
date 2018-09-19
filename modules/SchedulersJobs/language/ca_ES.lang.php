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
    'LBL_MODULE_NAME' => 'Cua de feina',
    'LBL_MODULE_NAME_SINGULAR' => 'Cua de feina',
    'LBL_MODULE_TITLE' => 'Cua de feina: inici',
    'LBL_MODULE_ID' => 'Cua de feina',
    'LBL_TARGET_ACTION' => 'Acció',
    'LBL_FALLIBLE' => 'Falibles',
    'LBL_RERUN' => 'Torneu a executar',
    'LBL_INTERFACE' => 'Interfície',
    'LINK_SCHEDULERSJOBS_LIST' => 'Veure la cua de treball',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuració',
    'LBL_CONFIG_PAGE' => 'Configuració de cua de feina',
    'LBL_JOB_CANCEL_BUTTON' => 'Cancel·la',
    'LBL_JOB_PAUSE_BUTTON' => 'Pausa',
    'LBL_JOB_RESUME_BUTTON' => 'Reiniciar',
    'LBL_JOB_RERUN_BUTTON' => 'Tornar a posar a la cua',
    'LBL_LIST_NAME' => 'Nom',
    'LBL_LIST_ASSIGNED_USER' => 'Sol·licitada per',
    'LBL_LIST_STATUS' => 'Estat',
    'LBL_LIST_RESOLUTION' => 'Resolució',
    'LBL_NAME' => 'Nom de la feina',
    'LBL_EXECUTE_TIME' => 'Hora d´Execució',
    'LBL_SCHEDULER_ID' => 'Planificador',
    'LBL_STATUS' => 'Estat',
    'LBL_RESOLUTION' => 'Resolució',
    'LBL_MESSAGE' => 'Missatges',
    'LBL_DATA' => 'Data de la tasca',
    'LBL_REQUEUE' => 'Torneu a intentar en cas de fallada',
    'LBL_RETRY_COUNT' => 'Intents màxims',
    'LBL_FAIL_COUNT' => 'Falles',
    'LBL_INTERVAL' => 'Interval mínim entre intents',
    'LBL_CLIENT' => 'Ser propietari de client',
    'LBL_PERCENT' => 'Percentatge completat',
    'LBL_JOB_GROUP' => 'Treball de grup',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolució en cua',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolució parcial',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolució completa',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Resolució fracàs',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Resolució anul·lada',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Execució de resolució',
    // Errors
    'ERR_CALL' => "No es pot cridar a la funció: %s",
    'ERR_CURL' => "No CURL - no es pot executar treballs d&#39;URL",
    'ERR_FAILED' => "Error inesperat, si us plau, consulteu els registres de PHP i sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s en %s en línia %d",
    'ERR_NOUSER' => "No ID d&#39;usuari especificat per al treball",
    'ERR_NOSUCHUSER' => "ID d&#39;usuari %s no trobat",
    'ERR_JOBTYPE' => "Tipus de treball desconegut: %s",
    'ERR_TIMEOUT' => "Fracàs forçós per temps d&#39;espera",
    'ERR_JOB_FAILED_VERBOSE' => 'El treball %1$s (%2$s) va fallar en executar el cron',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'No es pot carregar el bean amb id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'No pot trobar controlador per ruta %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'L&#39;extensió per aquesta cua no s&#39;instal·la',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Alguns dels camps estan buits',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Configuració de cua de feina',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuració principal',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuració Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuració d&#39;AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuració d&#39;Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ajuda de configuració de cua de feines',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Secció de configuració principal.</b></p> 
<ul><li>Corredor: <ul><li><i>estàndard</i> - ús només d'un procés de treballadors.</li>     <li><i>Paral·lel</i> - utilitzar uns processos de treballadors.</li>     </ul></li> <li>Adaptador: <ul><li><i>Per defecte cua</i> - s'utilitzarà només de sucre base de dades sense cap cua de missatge.</li>     <li><i>Amazones SQS</i> - Amazon Simple cua Service és una cua distribuït introduït per Amazon.com servei de missatgeria.     Suporta programàtic enviament de missatges mitjançant les aplicacions de servei web com una manera de comunicar-se a través d'Internet.</li>     <li><i>RabbitMQ</i> - és missatge corredor programari de codi obert (de vegades anomenat orientada a missatge middleware) que implementa la avançada missatge cues Protocol (AMQP).</li>     <li><i>Gearman</i> - és un marc d'aplicació de codi obert dissenyat per distribuir tasques informàtiques adequades per diversos equips, tan grans tasques es pot fer més ràpidament.</li>     <li><i>Immediat</i> - com la cua per defecte però executa tasca immediatament després d'afegir.</li>     </ul>     </li> </ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Ajuda de configuració d&#39;Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Secció de configuració de l'Amazon SQS.</b></p> <ul><li>ID de la clau d'accés: <i>Enter el seu número de identificador clau d'accés per Amazon SQS</i></li> <li>Secret clau d'accés: <i>introduir la clau d'accés secret per Amazon SQS</i></li> <li>comarca: <i>entrar a la regió de servidor d'Amazones SQS</i></li> <li>nom de cua: <i>Introduïu el nom del servidor d'Amazones SQS cua</i></li></ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Ajuda de configuració d&#39;AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Secció de configuració d'AMQP.</b></p> <ul><li>URL de servidor: <i>introduïu l'URL del seu ' servidor missatge cua.</i></li>     <li>Login: <i>introduir la connexió per a RabbitMQ</i></li>     <li>Contrasenya: <i>introduir la contrasenya per a RabbitMQ</i></li></ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ajuda de configuració de Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Secció de configuració de Gearman.</b></p>
<ul>
    <li>URL del servidor: <i>Introdueixi la URL del servidor de cua de missatges.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptador',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Corredor',
    'LBL_SERVER_URL' => 'URL del servidor',
    'LBL_LOGIN' => 'Inici de sessió',
    'LBL_ACCESS_KEY' => 'ID de clau d&#39;accés',
    'LBL_REGION' => 'Regió',
    'LBL_ACCESS_KEY_SECRET' => 'Clau d&#39;accés secret',
    'LBL_QUEUE_NAME' => 'Nom d&#39;adaptador',
);
