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
    'LBL_MODULE_NAME' => 'File d&#39;attente des tâches',
    'LBL_MODULE_NAME_SINGULAR' => 'File d&#39;attente des tâches',
    'LBL_MODULE_TITLE' => 'Liste d&#39;attente des tâches : Accueil',
    'LBL_MODULE_ID' => 'File d&#39;attente des tâches',
    'LBL_TARGET_ACTION' => 'Action',
    'LBL_FALLIBLE' => 'Faillible',
    'LBL_RERUN' => 'Ré-exécution',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'Afficher file d&#39;attente des tâches',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuration',
    'LBL_CONFIG_PAGE' => 'Configuration de la file d&#39;attente',
    'LBL_JOB_CANCEL_BUTTON' => 'Annuler',
    'LBL_JOB_PAUSE_BUTTON' => 'Pause',
    'LBL_JOB_RESUME_BUTTON' => 'Reprise',
    'LBL_JOB_RERUN_BUTTON' => 'Remettre en file d&#39;attente',
    'LBL_LIST_NAME' => 'Nom',
    'LBL_LIST_ASSIGNED_USER' => 'Demandé par',
    'LBL_LIST_STATUS' => 'Statut',
    'LBL_LIST_RESOLUTION' => 'Résolution',
    'LBL_NAME' => 'Nom du Job',
    'LBL_EXECUTE_TIME' => 'Date d&#39;execution',
    'LBL_SCHEDULER_ID' => 'Planificateur',
    'LBL_STATUS' => 'Statut',
    'LBL_RESOLUTION' => 'Résultat',
    'LBL_MESSAGE' => 'Messages',
    'LBL_DATA' => 'Données du job',
    'LBL_REQUEUE' => 'Réessayer suite échec',
    'LBL_RETRY_COUNT' => 'Tentatives max.',
    'LBL_FAIL_COUNT' => 'Échecs',
    'LBL_INTERVAL' => 'Intervalle min. entre chaque essai',
    'LBL_CLIENT' => 'Client propriétaire',
    'LBL_PERCENT' => 'Pourcentage accompli',
    'LBL_JOB_GROUP' => 'Groupe de jobs',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Résolution en file d&#39;attente',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Résolution partielle',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolution Complete',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Échec de la résolution',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Résolution annulée',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Résolution en cours d&#39;exécution',
    // Errors
    'ERR_CALL' => "Ne peut pas appeler la fonction : %s",
    'ERR_CURL' => "cURL non présent - exécution des jobs URL impossible",
    'ERR_FAILED' => "Échec inattendu, veuillez vérifier les logs PHP et sugarcrm.log",
    'ERR_PHP' => "%s [%d] : %s sur %s à la ligne %d",
    'ERR_NOUSER' => "Pas d'ID d'utilisateur précisé pour le job",
    'ERR_NOSUCHUSER' => "ID utilisateur %s non trouvé",
    'ERR_JOBTYPE' => "Type de job inconnu : %s",
    'ERR_TIMEOUT' => "Forcé l'échec sur timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Job %1$s (%2$s) en échec au lancement du CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Impossible de charger le bean avec l&#39;id : %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Cannot find handler for route %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Extension pour cette file d&#39;attente n&#39;est pas installée',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Certains champs sont vides',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Configuration de la file d&#39;attente',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuration principale',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuration Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuration AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuration Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Aide configuration de la file d&#39;attente des tâches',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Section de Configuration principale.</b></p> <ul><li>Exécuteur : <ul><li><i>Standard</i> - n'utiliser qu'un processus pour les travailleurs.</li>     <li><i>Parallèle</i> - utiliser quelques processus pour les travailleurs.</li>     </ul></li> <li>Adaptateur : <ul><li>de <i>File d'attente par défaut</i> - Ceci n'utilisera que la base de données de Sugar sans aucune file d'attente des messages.</li>     <li><i>Amazon SQS</i> - Amazon Simple Queue Service est un service de messagerie à file d'attente distribuée introduit par Amazon.com.     Il prend en charge la programmation, l'envoi de messages via les applications de service web comme moyen de communiquer sur Internet.</li>     <li> <i>RabbitMQ</i> - logiciel libre de messagerie (parfois appelé middleware orienté message) qui implémente le Advanced Message Queuing Protocol (AMQP).</li>     <li><i>Gearman</i> - est environnement pour applications open source pour répartir les tâches informatiques appropriées à plusieurs ordinateurs, de sorte que les tâches exigeantes puissent s'accomplir plus rapidement.</li>     <li><i>Immédiat</i> - comme la file d'attente par défaut, mais exécute la tâche immédiatement après l'ajout.</li>     </ul>     </li> </ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Aide sur la configuration Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Section de Configuration Amazon SQS.</b></p> <ul><li>ID de clé d'accès : <i>Entrez votre numéro id de clé d'accès pour Amazon SQS</i></li> <li> Touche d'accès au Secret : <i>Entrez votre clé d'accès secret pour Amazon SQS</i></li> <li>Région : <i>saisissez la région du serveur Amazon SQS</i></li> <li>Nom de la file d'attente : <i>saisir le nom de file d'attente du serveur Amazon SQS</i></li></ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Aide à la configuration AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Section configuration AMQP.</b></p>
<ul>
    <li>Serveur URL : <i>Saisissez l'URL du serveur de file d'attente de vos messages.</i></li>
    <li>Connexion : <i>Saisissez votre connexion pour RabbitMQ</i></li>
    <li>Mot de passe : <i>Saisissez votre mot de passe pour RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Aide pour la configuration Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Section Configuration Gearman.</b></p>
<ul>
    <li>Serveur URL : <i>Su serveur de votre messagerie.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptateur',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Roue',
    'LBL_SERVER_URL' => 'URL serveur',
    'LBL_LOGIN' => 'Connexion',
    'LBL_ACCESS_KEY' => 'ID clé d&#39;accès',
    'LBL_REGION' => 'Région',
    'LBL_ACCESS_KEY_SECRET' => 'Clé d&#39;accès secrète',
    'LBL_QUEUE_NAME' => 'Nom de l&#39;adaptateur',
);
