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
'LBL_OOTB_WORKFLOW'		=> 'Actions des Processus de Workflow',
'LBL_OOTB_REPORTS'		=> 'Lancer les actions planifiées de génération de rapports',
'LBL_OOTB_IE'			=> 'Vérifier les boîtes aux emails entrants',
'LBL_OOTB_BOUNCE'		=> 'Lancer le process nocturne de gestion des bounces des campagnes emails',
'LBL_OOTB_CAMPAIGN'		=> 'Lancer le process nocturne d&#39;envoi des Campagnes emails',
'LBL_OOTB_PRUNE'		=> 'Purger la BDD le premier de chaque mois',
'LBL_OOTB_TRACKER'		=> 'Purger les tables des Suivis',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Nettoyage des ancients enregistrements',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Supprimer les fichiers temporaires',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Supprimer les fichiers de diagnostic',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Supprimer les fichiers PDF temporaires',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Mettre à jour la table tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Lancez les notifications de rappel par email',
'LBL_OOTB_CLEANUP_QUEUE' => 'Nettoyer la file des jobs',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Créer les Périodes futures',
'LBL_OOTB_HEARTBEAT' => 'Heartbeat Sugar',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Mise à jour des articles KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publier les articles approuvés & les articles de la base de connaissances expirés.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Tâche planifiée d&#39;Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Reconstruire les données de sécurité par équipe dénormalisées',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Fréquence',
'LBL_LIST_LIST_ORDER' => 'Actions planifiées :',
'LBL_LIST_NAME' => 'Planificateur :',
'LBL_LIST_RANGE' => 'Plage d&#39;activité',
'LBL_LIST_REMOVE' => 'Supprimer :',
'LBL_LIST_STATUS' => 'Statut :',
'LBL_LIST_TITLE' => '&nbsp;',
'LBL_LIST_EXECUTE_TIME' => 'Va s&#39;éxécuter à :',
// human readable:
'LBL_SUN'		=> 'Le dimanche',
'LBL_MON'		=> 'Le lundi',
'LBL_TUE'		=> 'Le mardi',
'LBL_WED'		=> 'Le mercredi',
'LBL_THU'		=> 'Le jeudi',
'LBL_FRI'		=> 'Le vendredi',
'LBL_SAT'		=> 'Le samedi',
'LBL_ALL'		=> 'Tous les jours',
'LBL_EVERY_DAY'	=> 'Tous les jours',
'LBL_AT_THE'	=> 'A le',
'LBL_EVERY'		=> 'Tous',
'LBL_FROM'		=> 'De',
'LBL_ON_THE'	=> 'Toutes les',
'LBL_RANGE'		=> 'à',
'LBL_AT' 		=> 'à',
'LBL_IN'		=> 'et',
'LBL_AND'		=> 'et',
'LBL_MINUTES'	=> 'minutes',
'LBL_HOUR'		=> 'heures',
'LBL_HOUR_SING'	=> 'heures',
'LBL_MONTH'		=> 'mois',
'LBL_OFTEN'		=> 'Aussi souvent que possible.',
'LBL_MIN_MARK'	=> 'minutes',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'hrs',
'LBL_DAY_OF_MONTH' => 'date',
'LBL_MONTHS' => 'mois',
'LBL_DAY_OF_WEEK' => 'jour',
'LBL_CRONTAB_EXAMPLES' => 'Les valeurs ci dessus utilisent les notations standard de la crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Les tâches planifiées sont basées sur la timezone du serveur (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Veuillez en tenir compte lorsque vous paramétrez des tâches planifiées.',
// Labels
'LBL_ALWAYS' => '- toujours -',
'LBL_CATCH_UP' => 'Relancer si manqué',
'LBL_CATCH_UP_WARNING' => 'Décocher si cette action peut prendre du temps à s&#39;exectuer.',
'LBL_DATE_TIME_END' => 'Date & heure de fin',
'LBL_DATE_TIME_START' => 'Date & heure de démarrage',
'LBL_INTERVAL' => 'Intervalle',
'LBL_JOB' => 'Tâche',
'LBL_JOB_URL' => 'URL du job',
'LBL_LAST_RUN' => 'Dernière exécution',
'LBL_MODULE_NAME' => 'Planificateur',
'LBL_MODULE_NAME_SINGULAR' => 'Planificateur Sugar',
'LBL_MODULE_TITLE' => 'Actions planifiées',
'LBL_NAME' => 'Nom du Job',
'LBL_NEVER' => '- jamais -',
'LBL_NEW_FORM_TITLE' => 'Nouvelle action planifiée',
'LBL_PERENNIAL' => '- jamais -',
'LBL_SEARCH_FORM_TITLE' => 'Recherche action planifiée',
'LBL_SCHEDULER' => 'Planificateur :',
'LBL_STATUS' => 'Statut',
'LBL_TIME_FROM' => 'Actif de :',
'LBL_TIME_TO' => 'Actif jusqu à',
'LBL_WARN_CURL_TITLE' => 'Alerte cURL :',
'LBL_WARN_CURL' => 'Attention :',
'LBL_WARN_NO_CURL' => 'Ce système ne dispose pas des librairies cURL (activées/compilées) dans le module PHP (--with-curl=/path/to/curl_library).  Veuillez contacter votre administrateur pour résoudre ce problème.  Sans la fonctionnalité cURL, le planificateur ne pourra traiter ses jobs.',
'LBL_BASIC_OPTIONS' => 'Paramétrages Essentiels',
'LBL_ADV_OPTIONS'		=> 'Options avancées',
'LBL_TOGGLE_ADV' => 'Options avancées',
'LBL_TOGGLE_BASIC' => 'Options de base',
// Links
'LNK_LIST_SCHEDULER' => 'Actions planifiées',
'LNK_NEW_SCHEDULER' => 'Nouvelle action planifiée',
'LNK_LIST_SCHEDULED' => 'Jobs Planifiés',
// Messages
'SOCK_GREETING' => "Ceci est une interface pour le Service de Planification SugarCRM.<br /><br />[ Commandes disponibles du serveur : start|restart|shutdown|status ]<br /><br />Pour quitter, tapez 'quit'. Pour arréter le service 'shutdown'.",
'ERR_DELETE_RECORD' => 'Un ID doit être spécifié pour supprimer la planification.',
'ERR_CRON_SYNTAX' => 'Syntaxe de l&#39;action planifiée invalide',
'NTC_DELETE_CONFIRMATION' => 'Êtes-vous sûr de vouloir supprimer cet enregistrement ?',
'NTC_STATUS' => 'Passer le statut à Inactif pour supprimer cette planification de les listes déroulantes du planificateur',
'NTC_LIST_ORDER' => 'L&#39;ordre de cette planification va apparaitre dans les listes déroulantes du planificateur',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Pour configurer le planificateur Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Pour configurer la Crontab',
'LBL_CRON_LINUX_DESC' => 'Note : Afin d&#39;exécuter les Tâches planifiées SugarCRM, ajouter cette ligne dans votre crontab :',
'LBL_CRON_WINDOWS_DESC' => 'Note : Afin d&#39;exécuter les Tâches planifiées SugarCRM, créer un fichier batch avec les commandes suivantes :',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Executions',
'LBL_EXECUTE_TIME'			=> 'Date d&#39;execution',

//jobstrings
'LBL_REFRESHJOBS' => 'Rafraichir les jobs',
'LBL_POLLMONITOREDINBOXES' => 'Vérifier les boîtes emails entrantes',
'LBL_PERFORMFULLFTSINDEX' => 'système d&#39;indexation de recherche Full-Text',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Supprimer les fichiers PDF temporaires',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publier les articles approuvés et les articles expirés de la base de connaissance.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Planificateur de file d&#39;attente Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Supprimer les fichiers de diagnostic',
'LBL_SUGARJOBREMOVETMPFILES' => 'Supprimer les fichiers temporaires',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Reconstruire les données de sécurité par équipe dénormalisées',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Lancer le process nocturne d&#39;envoi des Campagnes d&#39;emailing',
'LBL_ASYNCMASSUPDATE' => 'Réaliser les mises à jour globales de manière asynchrone',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Lancer le processus de nuit des Campagnes d&#39;Emails de type Bounce',
'LBL_PRUNEDATABASE' => 'Purger la BDD le premier de chaque mois',
'LBL_TRIMTRACKER' => 'Purger les tables des historiques',
'LBL_PROCESSWORKFLOW' => 'Actions des Processus de Workflow',
'LBL_PROCESSQUEUE' => 'Lancer les actions planifiées de génération de rapports',
'LBL_UPDATETRACKERSESSIONS' => 'Mettre à jour la table tracker_sessions',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Créer les Périodes futures',
'LBL_SUGARJOBHEARTBEAT' => 'Heartbeat Sugar',
'LBL_SENDEMAILREMINDERS'=> 'Lancer l&#39;envoi des rappels par email',
'LBL_CLEANJOBQUEUE' => 'Nettoyer le job de la file d&#39;attente',
'LBL_CLEANOLDRECORDLISTS' => 'Supprime les ancients enregistrements',
'LBL_PMSEENGINECRON' => 'Planificateur d&#39;Advanced Workflow',
);

