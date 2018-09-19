<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

$mod_strings = array(
	'LBL_BASIC_SEARCH'					=> 'Recherche Basique',
	'LBL_ADVANCED_SEARCH'				=> 'Recherche Avancée',
	'LBL_BASIC_TYPE'					=> 'Type basique',
	'LBL_ADVANCED_TYPE'					=> 'Type avancé',
	'LBL_SYSOPTS_1'						=> 'Veuillez sélectionner parmi les options de configuration ci-dessous.',
    'LBL_SYSOPTS_2'                     => 'Préciser un type de base de données pour l\'installation de votre Sugar.',
	'LBL_SYSOPTS_CONFIG'				=> 'Paramètres système',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Sélection de la base de données',
    'LBL_SYSOPTS_DB_TITLE'              => 'Type de base de données',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Veuillez corriger les erreurs suivantes avant de continuer :',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Veuillez positionner les droits d\'écriture sur les répertoires suivants :',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Votre version de DB2 (%s) n\'est pas prise en charge par Sugar. Vous devez installer une version compatible avec Sugar. Veuillez vous référer à la matrice de compatibilité dans les Release Notes pour les versions de DB2 prises en charge.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Vous devez avoir un client Oracle installé et configuré si vous sélectionnez Oracle.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
	'ERR_DB_OCI8_CONNECT'				=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
	'ERR_DB_OCI8_VERSION'				=> 'Votre version d\'Oracle (%s) n\'est pas prise en charge par Sugar. Vous devez installer une version compatible avec Sugar. Veuillez consulter la matrice de compatibilité présente dans les Release Notes pour connaitre les versions d\'Oracle qui sont prises en charge.',
    'LBL_DBCONFIG_ORACLE'               => 'Merci de saisir le nom de votre base de données. Il doit s\'agir de l\'espace de nom assigné par défaut à votre utilisateur de base de données ((SID de tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Requêtes sur les affaires ',
	'LBL_Q1_DESC'						=> 'Affaires par type',
	'LBL_Q2_DESC'						=> 'Affaires par compte',
	'LBL_R1'							=> 'Rapport sur le portefeuille d\'affaire à 6 mois',
	'LBL_R1_DESC'						=> 'Affaires sur les 6 prochains mois ventilées par mois et par type',
	'LBL_OPP'							=> 'Jeu de donnée pour les affaires ',
	'LBL_OPP1_DESC'						=> 'Paramétrage de l\'apparence pour les requêtes personnalisées',
	'LBL_OPP2_DESC'						=> 'Cette requête sera positionnée en dessous de la première requête du rapport',
    'ERR_DB_VERSION_FAILURE'			=> 'Impossible de valider la version de votre système de base de données.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Veuillez fournir le nom d\'utilisateur pour le profil administrateur de SugarCRM. ',
	'ERR_ADMIN_PASS_BLANK'				=> 'Le mot de passe admin de SugarCRM ne peut pas être vide. ',

    'ERR_CHECKSYS'                      => 'Des erreurs ont été détectées durant la vérification de compatibilité. Pour que votre installation de SugarCRM se fasse correctement, veuillez effectuer les modifications appropriées pour corriger les problèmes signalés ci-dessous puis cliquez sur le bouton revérification ou relancez l\'installation.',
    'ERR_CHECKSYS_CALL_TIME'            => 'La fonction Allow Call Time Pass Reference est définie sur On (veuillez la désactiver dans le php.ini)',

	'ERR_CHECKSYS_CURL'					=> 'Non trouvé : le planificateur de Sugar sera exécuté avec une fonctionnalité réduite. Le service d\'archivage d\'e-mails ne sera pas exécuté.',
    'ERR_CHECKSYS_IMAP'					=> 'Introuvable : Les mails entrants et les campagnes (Email) nécessitent la librairie IMAP dans PHP. Aucun des 2 ne pourra fonctionner.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'La fonction Magic Quotes GPC ne peut pas être mise à "On" lorsque vous utilisez MSSQL SERVER.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Attention : ',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> ' (Mettre ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M ou plus dans votre fichier php.ini)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Version Minimum 4.1.2 - Version trouvée : ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Impossible d\'écrire ou lire les paramètres de session. Impossible de continuer l\'installation.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Répertoire Invalide',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Attention : Impossible d\'écrire',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Votre version de PHP n\' est pas supportée par SugarCRM. Vous devez installer une version de PHP compatible avec SugarCRM. Merci de consulter la matrice de compatibilté disponible dans le document "Release Notes" pour voir quelle versions de PHP sont supportées par SugarCRM. Votre version actuelle est',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Votre version de IIS n\'est pas prise en charge par SugarCRM. Vous devez installer une version compatible avec SugarCRM. Veuillez consulter la matrice de compatibilité présente dans les Release Notes pour connaitre les versions d\'Oracle qui sont prises en charge. Votre version est ',
	'ERR_CHECKSYS_FASTCGI'              => 'Nous avons détecté que vous n\'utilisez pas PHP en mode FastCGI. Vous devez installer/configurer une version de PHP compatible avec Sugar.  Veuillez consulter la matrice de compatibilité présente dans les Release Notes pour connaitre les versions prises en charge. Veuillez consulter <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a> pour plus de détails ',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'Pour une utilisation optimale du SAPI IIS/FastCGI, positionnez fastcgi.logging à 0 dans votre fichier php.ini.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Version de PHP installée non prise en charge : ( ver',
    'LBL_DB_UNAVAILABLE'                => 'Base de données indisponible',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Le support de base de données n\'a pas été trouvé. Veuillez vous assurer d\'avoir les pilotes nécessaires pour un des suivants types de bases de données pris en chargeˆ: MySQL, MS SQLServer, Oracle, ou DB2. Il se peut que vous deviez décommenter l\'extension dans le fichier php.ini, ou recompiler avec le fichier binaire correct, selon votre version de PH. Veuillez consulter votre manuel PHP pour plus d\'informations sur la façon d\'activer le support de base de données.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Les fonctions associées aux bibliothèques de parsing XML qui sont nécessaires à Sugar n\'ont pas été retrouvées. Vous pourriez avoir besoin de décommenter l\'extension dans le fichier php.ini, ou recompiler avec le bon fichier binaire, en fonction de votre version de PHP. Veuillez vous référer à votre manuel PHP pour plus d\'informations.',
    'LBL_CHECKSYS_CSPRNG' => 'Générateur de nombres aléatoires',
    'ERR_CHECKSYS_MBSTRING'             => 'Les fonctions associées avec l\'extension Multibyte de PHP (mbstring) qui sont nécessaires pour Sugar n\'ont pas été trouvées. <br/><br/>En général, le module mbstring n\'est pas activé par défaut dans PHP et doit être activé avec --enable-mbstring quand le binaire PHP est constitué. Veuillez vous référer au manuel PHP pour plus d\'informations sur l\'activation de mbstring.',
    'ERR_CHECKSYS_MCRYPT'               => "Mcrypt module isn't loaded. Please refer to your PHP Manual for more information on how to load mcrypt module.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'session.save_path n\'est pas défini dans le fichier de configuration php (php.ini) ou le répertoire défini n\'existe pas. Vous pourriez avoir besoin de définir un répertoire pour la variable save_path dans le fichier php.ini ou vérifier que le répertoire défini existe.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Le répertoire défini dans la variable session.save_path du fichier de configuration php (php.ini) est non modifiable. Vous devrez peut-être modifier les permissions sur celui-ci (chmod 766) ou faire un clic droit dessus et décocher l\'option de lecture seule, en fonction de votre système d\'exploitation.<br>Veuillez prendre les mesures nécessaires pour permettre l\'accès aux répertoires en écriture.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Le fichier de configuration existe, mais il n\'est pas en écriture. Veuillez prendre les mesures nécessaires pour rendre le fichier en écriture. En fonction de votre système d\'exploitation, ceci pourrait vous demander de changer les permissions en exécutant "chmod 766", ou faire un clic-droit sur le nom du fichier pour accès aux propriétés et décochez l\'option de lecture seule.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Le fichier config override existe mais n\'est pas accessible en écriture. Veuillez effectuer les modifications de droits nécessaires sur ce fichier afin de le rendre accessible en écriture par le serveur web. En fonction de votre système d\'exploitation, vous pouvez changer les droits en exécutant la commande chmod 766, or en effectuant un clic droit sur le fichier puis en décochant l\'option lecture seule.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Le Répertoire Custom existe, mais est non modifiable. Vous devrez peut-être modifier les permissions sur celui-ci (chmod 766) ou faire un clic droit dessus et décocher l\'option de lecture seule, en fonction de votre système d\'exploitation. Veuillez prendre les mesures nécessaires pour permettre l\'accès aux fichiers en écriture.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "Les fichiers ou répertoires énumérés ci-dessous ne sont pas modifiables. Veuillez prendre les mesures nécessaires pour permettre l'écriture. En fonction de votre système d'exploitation, il peut vous demander de changer les permissions sur les fichiers (chmod 755), ou de faire un clic droit sur le répertoire parent, désélectionnez l'option \"lecture seule\" et de l'appliquer à tous les sous-dossiers.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Safe Mode est activé (veuillez le désactiver dans le fichier php.ini)',
    'ERR_CHECKSYS_ZLIB'					=> 'Support ZLib introuvable : SugarCRM peut gagner beaucoup en performance avec la compression zlib activée.',
    'ERR_CHECKSYS_ZIP'					=> 'Le support ZIP n\'a pas été détecté : SugarCRM nécessite le support ZIP pour compresser les fichiers.',
    'ERR_CHECKSYS_BCMATH'				=> 'Support de BCMATH non trouvé : SugarCRM requiert BCMATH pour une meilleure précision mathématique.',
    'ERR_CHECKSYS_HTACCESS'             => 'Le test d\'écriture du fichier .htaccess a échoué. Cela signifie généralement que vous n\'avez pas autorisé la directive AllowOverride pour le répertoire contenant Sugar.',
    'ERR_CHECKSYS_CSPRNG' => 'Exception CSPRNG',
	'ERR_DB_ADMIN'						=> 'Le login ou le mot de passe de l\'administrateur de la base de données est invalide, et une connexion à la base de de données n\'a pas pu être établie. Veuillez entrer un nom d\'utilisateur et un mot de passe valides. (Erreur : ',
    'ERR_DB_ADMIN_MSSQL'                => 'Le login ou le mot de passe de l\'administrateur de la base de données est invalide, et une connexion à la base de de données n\'a pas pu être établie. Veuillez entrer un nom d\'utilisateur et un mot de passe valides.',
	'ERR_DB_EXISTS_NOT'					=> 'La base de données spécifiée n\'existe pas.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'La base de données contient déjà des données de configuration. Pour lancer une installation avec la base de données sélectionnées, veuillez relancer l\'installation et sélectionnez :"Effacer et recréer les tables existantes de SugarCRM ?" Pour mettre à jour, utilisez l\'assistant de mise à jour dans la console d\'administration.  Veuillez lire la documentation de mise à jour située <a href="http://www.sugarforge.org/content/downloads/" target="_new">ici</a>.',
	'ERR_DB_EXISTS'						=> 'Une base de données avec le même nom existe déjà--Impossible d\'en créer une autre avec le même nom.',
    'ERR_DB_EXISTS_PROCEED'             => 'Une base de données avec le même nom existe déjà. <br>Utiliser le bouton Précédent et choisissez un nouveau nom de base de données ou vous pouvez choisir de cliquer sur le bouton Suivant et continuer, mais <strong> toutes les tables existantes et leurs données seront définitivement effacées.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Le nom de l’hôte (Hostname) ne peut pas être vide.',
	'ERR_DB_INVALID'					=> 'Le type de base de données sélectionnée est invalide.',
	'ERR_DB_LOGIN_FAILURE'				=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Le nom du serveur, le login ou le mot de passe de la base de données sont invalides, et Sugar ne peut pas se connecter au serveur. Veuillez saisir un nom de serveur, un login et un mot de passe valides',
	'ERR_DB_MYSQL_VERSION'				=> 'Votre version de MySQL (%s) n\'est pas prise en charge par Sugar. Vous devez installer une version compatible avec SugarCRM. Merci de vous référer à la matrice de compatibilité disponible dans les Release Note pour connaitre les versions de MySQL actuellement prises en charge.',
	'ERR_DB_NAME'						=> 'Le nom de la base de données ne peut pas être vide.',
	'ERR_DB_NAME2'						=> "Le nom de la base de données ne peut pas contenir les caractères '', '/', ou '.'",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Le nom de la base de données ne peut pas contenir les caractères '', '/', ou '.'",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Le nom de la base de données ne peut pas commencer par le chiffre '#', ou '@' et ne peut contenir les caractères e, '\"', \"'\", '*', '/', '\\', '?', ' :', '<', '>', '&', '!', ou '-'",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Le nom de la base de données peut se composer uniquement de caractères alphanumériques et des symboles « # », « _ », « - », « : », « . », « / » ou « $ »",
	'ERR_DB_PASSWORD'					=> 'Les mots de passe pour SugarCRM ne correspondent pas. Veuillez renseigner des mots de passe identiques dans les champs requis.',
	'ERR_DB_PRIV_USER'					=> 'L\'identifiant Admin de la base de données est nécessaire.',
	'ERR_DB_USER_EXISTS'				=> 'L\'utilisateur pour SugarCRM existe déjà--Impossible d\'en créer un second avec le même identifiant. Veuillez entrer un nouveau nom d\'utilisateur.',
	'ERR_DB_USER'						=> 'Entrez un nom d\'utilisateur pour l\'administrateur de base de données de Sugar.',
	'ERR_DBCONF_VALIDATION'				=> 'Veuillez corriger les erreurs suivantes avant de continuer :',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'Le mot de passe pour la base de données Sugar est invalide.',
	'ERR_ERROR_GENERAL'					=> 'Les erreurs suivantes ont été rencontrées :',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Impossible d\'effacer le fichier : ',
	'ERR_LANG_MISSING_FILE'				=> 'Impossible de trouver le fichier : ',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Pas de pack de langue trouvé dans include/language à l\'intérieur de : ',
	'ERR_LANG_UPLOAD_1'					=> 'Un problème a été rencontré avec votre upload. Veuillez réessayer.',
	'ERR_LANG_UPLOAD_2'					=> 'PHP n\'a pas pu déplacer le fichier temporaire vers le répertoire de mises à jour.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP n\'a pas pu déplacer le fichier temporaire vers le répertoire d\'upgrade.',
	'ERR_LICENSE_MISSING'				=> 'Les champs requis ne sont pas tous renseignés',
	'ERR_LICENSE_NOT_FOUND'				=> 'Fichier de licence introuvable !',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Le répertoire de Logs définis n\'est pas valide.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Impossible d\'écrire dans le répertoire de Logs défini.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Un répertoire de Logs est obligatoire si vous voulez spécifier votre propre répertoire.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Impossible d\'exécuter le script directement.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Vous ne pouvez pas utiliser de guillemet simple pour ',
	'ERR_PASSWORD_MISMATCH'				=> 'Les mots de passe pour SugarCRM Admin ne correspondent pas. Veuillez renseigner des mots de passe identiques dans les champs requis.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Impossible d\'écrire dans le fichier <span class=stop>config.php</span>.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Vous pouvez continuer cette installation en créant manuellement le fichier config.php et en collant les informations de configuration ci-dessous dans le fichier. Cependant, vous <strong>devez </strong> créer le fichier config.php avant de procéder à l\'étape suivante.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Avez-vous créé le fichier config.php ?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Attention : impossible d\'écrire dans le fichier config.php.  Veuillez vous assurer qu\'il existe.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Impossible d\'écrire dans le fichier ',
	'ERR_PERFORM_HTACCESS_2'			=> ' .',
	'ERR_PERFORM_HTACCESS_3'			=> 'Si vous voulez sécuriser votre fichier de Logs d\'un accès par un navigateur, créez un fichier .htaccess dans le répertoire de Logs avec cette ligne :',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>Nous n\'avons pas détecté de connexion internet.</b> Dès que vous en avez une, veuillez vous rendre sur <a href="http://www.sugarcrm.com/crm/products/offline-product-registration.html">http://www.sugarcrm.com/crm/products/offline-product-registration.html</a> pour vous enregistrer auprès de SugarCRM. En nous laissant savoir comment votre société compte utiliser SugarCRM, nous pouvons vous garantir de vous fournir toujours la bonne application pour vos besoins de business.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Le répertoire de session défini n\'est pas un répertoire valide.',
	'ERR_SESSION_DIRECTORY'				=> 'Impossible d\'écrire dans le répertoire de session défini.',
	'ERR_SESSION_PATH'					=> 'Un répertoire de session est nécessaire si vous voulez définir votre propre répertoire.',
	'ERR_SI_NO_CONFIG'					=> 'Vous n\'avez pas inclus de fichier config_si.php dans la racine ou vous n\'avez pas spécifié de $sugar_config_si dans le fichier config.php',
	'ERR_SITE_GUID'						=> 'ID d\'Application nécessaire si vous voulez spécifier votre propre application.',
    'ERROR_SPRITE_SUPPORT'              => "La bibliothèque GD n'a pas été détectée, vous ne pourrez donc pas utiliser la technologie CSS Sprite dans Sugar.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Attention : Votre configuration de PHP doit être modifiée pour autoriser les fichiers d\'au moins 6MB à être uploadés.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Taille des fichiers d\'upload',
	'ERR_URL_BLANK'						=> 'URL ne peut pas être vide.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Impossible de localiser le fichier d\'installation de',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Le fichier chargé n\'est pas compatible avec cette version (édition Professional, Enterprise ou Ultimate) de Sugar : ',
	'ERROR_LICENSE_EXPIRED'				=> "Erreur : Votre licence a expiré il y a ",
	'ERROR_LICENSE_EXPIRED2'			=> " jour(s). Veuillez vous rendre dans <a href=\"index.php?action=LicenseSettings&module=Administration\">\"Gestion de licences\"</a>  dans l'écran Admin pour renseigner votre nouvelle clé.  Si vous ne spécifiez pas de nouvelle clé dans les 30 jours suivant l'expiration de votre clé, vous ne pourrez plus vous connecter à l'application.",
	'ERROR_MANIFEST_TYPE'				=> 'Le fichier Manifest doit spécifier le type du package.',
	'ERROR_PACKAGE_TYPE'				=> 'Le fichier Manifest spécifie un type de package inconnu',
	'ERROR_VALIDATION_EXPIRED'			=> "ERREUR : Votre clé de validation a expiré",
	'ERROR_VALIDATION_EXPIRED2'			=> " jour(s). Veuillez vous rendre dans <a href=\"index.php?action=LicenseSettings&module=Administration\">\"Gestion de licences\"</a>  dans l'écran Admin pour renseigner votre nouvelle clé.  Si vous ne spécifiez pas de nouvelle clé dans les 30 jours suivant l'expiration de votre clé, vous ne pourrez plus vous connecter à l'application.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Le fichier uploadé est incompatible avec cette version de Sugar : ',

	'LBL_BACK'							=> 'Retour',
    'LBL_CANCEL'                        => 'Annuler',
    'LBL_ACCEPT'                        => 'Accepter',
	'LBL_CHECKSYS_1'					=> 'Afin que votre installation de SugarCRM fonctionne correctement, veuillez vous assurer que toutes les vérifications du système listées ci-dessous sont en vert. Si au moins une est en rouge, veuillez corriger le problème avant de continuer.<BR><BR>Pour obtenir de l\'aide sur ces vérifications systèmes, rendez-vous sur <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.',
	'LBL_CHECKSYS_CACHE'				=> 'Écriture possible dans les sous-répertoires de cache',
    'LBL_DROP_DB_CONFIRM'               => 'Une base de données avec le même nom existe déjà. <br>Vous pouvez soit :<br> 1. Cliquer sur le bouton Annuler et choisir un nouveau nom de base de données, soit<br>2. Cliquer sur le bouton Accepter et continuer.  Toutes les tables existantes de la base de données seront définitivement effacées.<strong>En d\'autres termes, toutes les tables et les données pré-existantes seront supprimées.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'La fonction Allow Call Time Pass Reference est désactivée',
    'LBL_CHECKSYS_COMPONENT'			=> 'Composants',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Composants Optionnels',
	'LBL_CHECKSYS_CONFIG'				=> 'Écriture dans le fichier de config de SugarCRM (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Fichier de configuration SugarCRM accessible en écriture (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'Module cURL',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Configuration "session.save_path"',
	'LBL_CHECKSYS_CUSTOM'				=> 'Écriture possible dans le répertoire utilisateur',
	'LBL_CHECKSYS_DATA'					=> 'Écriture possible dans les sous-répertoires de données',
	'LBL_CHECKSYS_IMAP'					=> 'Module IMAP',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'Module MB Strings',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt Module',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (Pas de limite)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (Illimité)',
	'LBL_CHECKSYS_MEM'					=> 'Limite Mémoire PHP',
	'LBL_CHECKSYS_MODULE'				=> 'Écriture possible dans les sous-répertoires des modules et les fichiers',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'Version de MySQL',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Non Disponible',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> 'Votre fichier de configuration php (php.ini) est situé ici :',
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (ver ',
	'LBL_CHECKSYS_PHPVER'				=> 'Version PHP',
    'LBL_CHECKSYS_IISVER'               => 'Version IIS',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Re-vérifier',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'La fonction PHP Safe Mode est désactivée',
	'LBL_CHECKSYS_SESSION'				=> 'Écriture possible dans chemin de sauvegarde des sessions (',
	'LBL_CHECKSYS_STATUS'				=> 'Statut',
	'LBL_CHECKSYS_TITLE'				=> 'Vérification du System Acceptée',
	'LBL_CHECKSYS_VER'					=> 'Trouvé : ( ver ',
	'LBL_CHECKSYS_XML'					=> 'Parseur XML',
	'LBL_CHECKSYS_ZLIB'					=> 'Module ZLIB Compression',
	'LBL_CHECKSYS_ZIP'					=> 'Module de gestion ZIP',
    'LBL_CHECKSYS_BCMATH'				=> 'Module de précision mathématique arbitraire',
    'LBL_CHECKSYS_HTACCESS'				=> 'La directive AllowOverride est positionnée dans le .htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'Veuillez corriger les fichiers ou répertoires suivants avant de poursuivre :',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Veuillez corriger les fichiers ou répertoires de module suivants avant de poursuivre :',
    'LBL_CHECKSYS_UPLOAD'               => 'Répertoire de téléchargement accessible en écriture',
    'LBL_CLOSE'							=> 'Fermer',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'à créer',
	'LBL_CONFIRM_DB_TYPE'				=> 'Type de base de données',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Veuillez confirmer les paramètres ci-dessous. Si vous voulez modifier une des valeurs, cliquez sur "précédent". Sinon cliquez sur "suivant" pour commencer l\'installation.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Information concernant la License',
	'LBL_CONFIRM_NOT'					=> 'pas',
	'LBL_CONFIRM_TITLE'					=> 'Confirmer les paramètres',
	'LBL_CONFIRM_WILL'					=> 'va',
	'LBL_DBCONF_CREATE_DB'				=> 'Créer la base de données',
	'LBL_DBCONF_CREATE_USER'			=> 'Créer Utilisateur [Alt+N]',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Avertissement : Toutes les données de Sugar seront effacées<br> si cette case est cochée.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Effacer et recréer les tables Sugar existantes ?',
    'LBL_DBCONF_DB_DROP'                => 'Supprimer les tables',
    'LBL_DBCONF_DB_NAME'				=> 'Nom de la base de données',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Mot de passe de base de données',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Re-saisissez votre mot de passe pour la base de données',
	'LBL_DBCONF_DB_USER'				=> 'Utilisateur de la base de données',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Utilisateur de la base de données',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Utilisateur admin de la base de données',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Mot de passe admin de la base de données',
	'LBL_DBCONF_DEMO_DATA'				=> 'Remplir la base de données avec des données de démo ?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Données de démo',
	'LBL_DBCONF_HOST_NAME'				=> 'Nom de l\'Hôte (HostName)',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Instance Hôte',
	'LBL_DBCONF_HOST_PORT'				=> 'Port',
    'LBL_DBCONF_SSL_ENABLED'            => 'Activez la connexion SSL',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Veuillez saisir les infos de configuration de la base de données. Si vous n\'êtes pas sûr des champs à renseigner, nous vous conseillons d\'utiliser les valeurs par défaut.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Utiliser du texte multi-octet dans les données de démo ?',
    'LBL_DBCONFIG_MSG2'                 => 'Entrer le nom de votre serveur de base de données (par exemple localhost ou serveursql.monsite.com).',
    'LBL_DBCONFIG_MSG3'                 => 'Entrer un nom pour la base de données qui va accueillir les données de votre SugarCRM.',
    'LBL_DBCONFIG_B_MSG1'               => 'Entrer le nom et le mot de passe admin pour la base de données. Cet admin doit avoir les permissions adéquates pour créer et écrire dans la base de données de Sugar.',
    'LBL_DBCONFIG_SECURITY'             => 'Pour des raisons de sécurité, vous devez avoir un et un seul admin pour la base de données Sugar. Cet utilisateur doit être capable d\'écrire, mettre à jour et extraire des données sur la base de données de Sugar qui seront créés pour cette instance. Cet utilisateur peut être l\'administrateur de la base de données spécifiée ci-dessus, ou vous pouvez fournir des informations d\'un utilisateur de base de données nouveau ou existant.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Le faire pour moi',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Fournir un utilisateur existant',
    'LBL_DBCONFIG_CREATE_DD'            => 'Définir un utilisateur à créer',
    'LBL_DBCONFIG_SAME_DD'              => 'Identique à l\'utilisateur admin',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Recherche Full Text',
    'LBL_FTS_INSTALLED'                 => 'installé',
    'LBL_FTS_INSTALLED_ERR1'            => 'Les options de recherche Full Text ne sont pas installées.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Vous pouvez installer l\'application mais vous ne pourrez utiliser la fonctionnalité de recherche Full Text. Veuillez vous référer à votre manuel d’installation de la base de données pour savoir comment procéder ou contacter votre administrateur.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Mot de passe de l\'utilisateur privilégié de la base de données',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Le compte utilisateur de la base de données est-il celui d\'un utilisateur privilégié ?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'Cet utilisateur privilégié doit avoir les bonnes autorisations pour créer une base de données, créer/supprimer tables, créer des utilisateurs. Cet utilisateur privilégié va être utilisé pour exécuter ces tâches pendant le processus d\'installation. Vous pouvez aussi utiliser le même utilisateur que ci-dessus si cet utilisateur a des privilèges suffisants.',
	'LBL_DBCONF_PRIV_USER'				=> 'Identifiant de l\'utilisateur privilégié de la base de données',
	'LBL_DBCONF_TITLE'					=> 'Configuration de la base de données',
    'LBL_DBCONF_TITLE_NAME'             => 'Nom de la base de données',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Informations concernant l\'utilisateur de la base de données',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'Après avoir fait cette modification, vous pouvez cliquer sur le bouton "Démarrer" ci-dessous pour lancer l\'installation.  <i>Une fois l\'installation terminée, vous pouvez modifier la valeur de \'installer_locked\' à \'true\'.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'Le processus d\'installation a été lancé déjà une fois. Pour des mesures de sécurité, son lancement a été désactivé une seconde fois. Si vous êtes absolument certain que vous voulez le lancer encore une fois, veuillez trouver (ou ajouter) dans votre fichier config.php la variable suivante :  \'installer_locked\'  et renseignez sa valeur à \'false\'.  La ligne doit se présenter comme suit :',
	'LBL_DISABLED_HELP_1'				=> 'Pour obtenir de l\'aide sur installation, veuillez vous rendre sur SugarCRM',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'forums d\'aide',
	'LBL_DISABLED_TITLE_2'				=> 'L\'installation SugarCRM a été désactivée',
	'LBL_DISABLED_TITLE'				=> 'Installation SugarCRM désactivée',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Définir ce jeu de caractère comme le jeu le plus couramment utilisé dans votre locale',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'paramètres emails sortants',
    'LBL_EMAIL_CHARSET_CONF'            => 'Jeux de caractères pour les emails sortants ',
	'LBL_HELP'							=> 'Aide',
    'LBL_INSTALL'                       => 'Installation',
    'LBL_INSTALL_TYPE_TITLE'            => 'Options d\'installation',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Sélectionner votre type d\'installation',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>Installation standard</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>Installation personnalisée</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Cette clé n\'est pas requise pour l\'installation mais sera requise pour les fonctionnalités générales. Vous pourrez entrer cette clé ultérieurement après avoir fini votre installation et vous être authentifié dans l\'application.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Informations minimum requises pour l\'installation. Recommandé pour les utilisateurs non expérimentés.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Fournit des options supplémentaires pour personnaliser votre installation. Ces options sont également disponibles après l\'installation dans l\'écran Administration. Recommandé pour les utilisateurs expérimentés.',
	'LBL_LANG_1'						=> 'Si vous voulez installer un pack de langue autre que celui par défaut en anglais US, vous pouvez le faire ci-dessous. Vous pourrez également télécharger et installer des modules linguistiques dans l\'application Sugar. Sinon, cliquez sur "Suivant" pour passer à l\'étape suivante.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Installation',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Supprimer',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Désinstaller',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Uploader',
	'LBL_LANG_NO_PACKS'					=> 'aucun',
	'LBL_LANG_PACK_INSTALLED'			=> 'Les packs de langues suivants ont été installés : ',
	'LBL_LANG_PACK_READY'				=> 'Les packs de langues suivants sont prêts à être installés : ',
	'LBL_LANG_SUCCESS'					=> 'Le pack de langue a été uploadé avec succès.',
	'LBL_LANG_TITLE'			   		=> 'Pack de langue',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'Installer SugarCRM maintenant. Cela peut prendre quelques minutes.',
	'LBL_LANG_UPLOAD'					=> 'Uploader un pack de langue',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Acceptation de la licence',
    'LBL_LICENSE_CHECKING'              => 'Vérification de la compatibilité du système',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Contrôle de l\'environnement',
    'LBL_LICENSE_CHKDB_HEADER'          => 'Verification des identifiants de DB, FTS.',
    'LBL_LICENSE_CHECK_PASSED'          => 'Test pour la compatibilité système réussi.',
    'LBL_LICENSE_REDIRECT'              => 'Redirection dans ',
	'LBL_LICENSE_DIRECTIONS'			=> 'Si vous avez votre information de licence, veuillez la renseigner dans les champs ci-dessous.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Entrer la Clé de téléchargement',
	'LBL_LICENSE_EXPIRY'				=> 'Date expiration',
	'LBL_LICENSE_I_ACCEPT'				=> 'Accepter',
	'LBL_LICENSE_NUM_USERS'				=> 'Nombre d\'utilisateurs',
	'LBL_LICENSE_PRINTABLE'				=> ' Aperçu avant impression ',
    'LBL_PRINT_SUMM'                    => 'Imprimer un résumé',
	'LBL_LICENSE_TITLE_2'				=> 'License SugarCRM',
	'LBL_LICENSE_TITLE'					=> 'Information concernant la License',
	'LBL_LICENSE_USERS'					=> 'Utilisateurs autorisés',

	'LBL_LOCALE_CURRENCY'				=> 'Paramètres de la devise',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Devise par défaut',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Symbole de la devise',
	'LBL_LOCALE_CURR_ISO'				=> 'Code de la devise (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> 'Séparateur de millier',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Séparateur décimal',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Exemple',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Chiffres significatifs',
	'LBL_LOCALE_DATEF'					=> 'Format de date par défaut',
	'LBL_LOCALE_DESC'					=> 'Les paramètres régionaux spécifiés seront reflétés globalement dans l\'instance de Sugar.',
	'LBL_LOCALE_EXPORT'					=> 'Jeux de caractères pour les imports/exports<br> <i> (Email, .csv, vCard, PDF, import de données)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Export avec délimiteur (.csv)',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Paramétrage d\'import/export',
	'LBL_LOCALE_LANG'					=> 'Langue par défaut',
	'LBL_LOCALE_NAMEF'					=> 'Format du nom par défaut',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = Civilité<br />f = Prénom<br />l = Nom',
	'LBL_LOCALE_NAME_FIRST'				=> 'David',
	'LBL_LOCALE_NAME_LAST'				=> 'Livingstone',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr.',
	'LBL_LOCALE_TIMEF'					=> 'Format de l\'heure par défaut',
	'LBL_LOCALE_TITLE'					=> 'Paramètres Locaux',
    'LBL_CUSTOMIZE_LOCALE'              => 'Personnaliser vos paramètres locaux',
	'LBL_LOCALE_UI'						=> 'Interface Utilisateur',

	'LBL_ML_ACTION'						=> 'Action',
	'LBL_ML_DESCRIPTION'				=> 'Description',
	'LBL_ML_INSTALLED'					=> 'Date d\'installation',
	'LBL_ML_NAME'						=> 'Nom',
	'LBL_ML_PUBLISHED'					=> 'Date de publication',
	'LBL_ML_TYPE'						=> 'Type',
	'LBL_ML_UNINSTALLABLE'				=> 'Désinstallable',
	'LBL_ML_VERSION'					=> 'Version',
	'LBL_MSSQL'							=> 'MSSQL Server',
	'LBL_MSSQL_SQLSRV'				    => 'SQL Server (Driver Microsoft SQL Server pour PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (extension mysqli)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Suivant',
	'LBL_NO'							=> 'Non',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Définir le mot de passe pour l\'admin du site',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'auditer la table / ',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Création du fichier de configuration Sugar',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Création de la base de données</b> ',
	'LBL_PERFORM_CREATE_DB_2'			=> ' <b>ok</b> ',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Création de l\'utilisateur et mot de passe de la base de données...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Création de données Sugar par défaut',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Création de l\'utilisateur et mot de passe pour le localhost...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Création des tables relationnelles Sugar',
	'LBL_PERFORM_CREATING'				=> 'création / ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Création des rapports par défaut',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Création de tâches du planificateur par défaut',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Insertion des paramètres par défaut',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Création des utilisateurs par défaut',
	'LBL_PERFORM_DEMO_DATA'				=> 'Insertion dans la base de données des données de démo (ceci peut prendre un peu de temps)...',
	'LBL_PERFORM_DONE'					=> 'terminé<br>',
	'LBL_PERFORM_DROPPING'				=> 'suppression / ',
	'LBL_PERFORM_FINISH'				=> 'Terminer',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Mise à jour de l\'information de licence',
	'LBL_PERFORM_OUTRO_1'				=> 'Le paramétrage de Sugar ',
	'LBL_PERFORM_OUTRO_2'				=> ' est maintenant terminé !',
	'LBL_PERFORM_OUTRO_3'				=> 'Temps total : ',
	'LBL_PERFORM_OUTRO_4'				=> ' secondes.',
	'LBL_PERFORM_OUTRO_5'				=> 'Mémoire utilisée Approx. : ',
	'LBL_PERFORM_OUTRO_6'				=> ' octets.',
	'LBL_PERFORM_OUTRO_7'				=> 'Votre système est maintenant installé et configuré pour être utilisé.',
	'LBL_PERFORM_REL_META'				=> 'relation meta ... ',
	'LBL_PERFORM_SUCCESS'				=> 'Succès !',
	'LBL_PERFORM_TABLES'				=> 'Création de tables de l\'application Sugar, tables d\'audit, et relations des métadonnées',
	'LBL_PERFORM_TITLE'					=> 'Paramétrage',
	'LBL_PRINT'							=> 'Imprimer',
	'LBL_REG_CONF_1'					=> 'Veuillez remplir le formulaire ci-dessous pour recevoir des annonces de produits, des nouvelles relatives à la formation, des offres spéciales et des invitations à des événements spéciaux de SugarCRM. Nous ne revendons pas, ni ne louons, ni ne partageons, ni ne redistribuons d\'une quelconque façon les informations transmises ici.',
	'LBL_REG_CONF_2'					=> 'Votre nom et votre adresse email sont les seuls champs requis pour l\'enregistrement. Tous les autres champs sont optionnels, mais nous seront d\'une grande utilité. Nous ne revendons pas, ni ne louons, ni ne partageons, ni ne redistribuons d\'une quelconque façon les informations transmises ici.',
	'LBL_REG_CONF_3'					=> 'Merci de vous être enregistré. Cliquez sur le bouton Terminer pour vous connecter sur SugarCRM. Vous aurez besoin de vous connecter pour la première fois avec l\'utilisateur "admin" et le mot de passe renseigné lors de la 2ème étape.',
	'LBL_REG_TITLE'						=> 'Enregistrement',
    'LBL_REG_NO_THANKS'                 => 'Non merci',
    'LBL_REG_SKIP_THIS_STEP'            => 'Passer cette étape',
	'LBL_REQUIRED'						=> '* Champs requis',

    'LBL_SITECFG_ADMIN_Name'            => 'Nom de l\'utilisateur admin de Sugar',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Re-saisissez le mot de passe de l\'admin de Sugar',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Attention : Vous allez remplacer le mot de passe admin de toute installation précédente.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Mot de passe de l\'admin de Sugar',
	'LBL_SITECFG_APP_ID'				=> 'ID Application',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Renseigner l\'ID d\'Application autogénérée qui empêche les sessions d\'une instance de Sugar d\'être utilisées sur une autre instance. Si vous avez un cluster d\'installation de Sugar, elles doivent toute partager le même ID d\'Application.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Fournir un ID d\'Application',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Renseigner le répertoire par défaut ou résident les logs de Sugar. Peu importe où vous mettez le fichier de Logs, l\'accès à celui-ci par un navigateur sera limité par une redirection du .htaccess.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Utiliser un répertoire personnalisé pour les Logs',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Fournissez un répertoire sécurisé pour enregistrer les informations de session et empêcher les données de sessions d\'être vulnérables sur les serveurs mutualisés.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Utiliser un répertoire de session personnalisé pour Sugar',
	'LBL_SITECFG_DIRECTIONS'			=> 'Veuillez saisir les informations de configuration de votre site. Si vous n\'êtes pas sûr des champs, nous vous suggérons d\'utiliser les valeurs par défaut.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Veuillez corriger les erreurs suivantes avant de continuer :</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Répertoire de Logs',
	'LBL_SITECFG_SESSION_PATH'			=> 'Chemin vers le répertoire de Session<br>(doit être accessible en écriture)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Sécurité avancée de votre instance',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'Si vous sélectionnez cette option, le système vérifiera périodiquement les mises à jour pour les versions de l\'application.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Activer les Mises à Jour Sugar ?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Configuration des Mises à Jour',
	'LBL_SITECFG_TITLE'					=> 'Configuration du site',
    'LBL_SITECFG_TITLE2'                => 'Identifier l\'instance Sugar',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Sécurité du site',
	'LBL_SITECFG_URL'					=> 'URL de l\'instance Sugar',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Utiliser les paramètres par défaut ?',
	'LBL_SITECFG_ANONSTATS'             => 'Envoyer des statistiques complètement anonymes ?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'Si vous cochez cette option, Sugar enverra des statistiques d\'utilisation <b>anonymes</b> à SugarCRM Inc. chaque fois que votre système vérifiera la présence de nouvelles versions. Ces informations nous permettront de mieux comprendre comment vous utilisez l\'application et nous aideront à orienter les améliorations du produit.',
    'LBL_SITECFG_URL_MSG'               => 'Entrez l\'URL qui sera utilisée pour accéder à votre instance de Sugar après l\'installation. Cette URL sera également utilisée comme base de travail pour les différentes pages de l\'application. L\'URL doit impérativement inclure le nom de la machine ou le nom du serveur ou une adresse IP sur laquelle le serveur est accessible.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Entrez un nom pour votre système. Ce nom apparaîtra dans la barre de titre du navigateur quand les utilisateurs navigueront sur votre application Sugar.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Après l\'installation, vous allez devoir utiliser l\'utilisateur administrateur de votre SugarCRM (par défaut il s\'agit de "admin") pour vous authentifier. Entrez ici un mot de passe pour cet utilisateur. Ce mot de passe pourra être changé par la suite. Vous pouvez également renseigner un autre login que "admin" si vous le souhaitez.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Sélectionnez les paramètres de collation (tri) de votre système. Ces paramètres vont créer des tables avec la langue que vous utilisez. Si votre langue ne nécessite aucune spécificité alors utilisez la valeur par défaut.',
    'LBL_SPRITE_SUPPORT'                => 'Support du Sprite',
	'LBL_SYSTEM_CREDS'                  => 'Droits Système',
    'LBL_SYSTEM_ENV'                    => 'Environnement Système',
	'LBL_START'							=> 'Début',
    'LBL_SHOW_PASS'                     => 'Afficher les mots de passe',
    'LBL_HIDE_PASS'                     => 'Masquer les mots de passe',
    'LBL_HIDDEN'                        => '<i>(masqué)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Choisir votre langue</b>',
	'LBL_STEP'							=> 'Étape',
	'LBL_TITLE_WELCOME'					=> 'Bienvenue sur SugarCRM ',
	'LBL_WELCOME_1'						=> 'Cet installeur crée les tables de la base de données SugarCRM et enregistre les paramètres de configuration nécessaires pour démarrer. Le processus d\'installation ne devrait pas dépasser une dizaine de minutes.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Êtes-vous prêt à installer ?',
    'REQUIRED_SYS_COMP' => 'Composants système requis',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Avant de commencer, vérifiez que vous avez les composants requis dans les versions 
                      nécessaires :<br>
                      <ul>
                      <li> Base de données/Gestionnaire de base de données (Exemples : MySQL, SQL Server, Oracle, DB2)</li>
                      <li> Serveur Web (Apache, IIS)</li>
                      <li> Elasticsearch</li>
                      </ul>
                      Consulter la matrice de comptabilité dans les notes de la release pour
                      vérifier la compatibilité des versions des composants nécessaires à votre installation de Sugar.<br>',
    'REQUIRED_SYS_CHK' => 'Vérification système initiale',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Lorsque vous lancez le processus d\'installation, une vérification du système sera effectuée sur le serveur Web sur lequel les fichiers de Sugar sont situés, afin de
                      s’assurer que le système est configuré correctement et dispose de tous les éléments nécessaires
                      pour mener à bien l\'installation. <br><br>
                      Le système vérifie tous les éléments suivants :<br>
                      <ul>
                      <li><b>Version PHP</b> &#8211; doit être compatible
                      avec l\'application</li>
                                        <li><b>Variables de session</b> &#8211; doit fonctionner correctement</li>
                                            <li> <b>MB Strings</b> &#8211; doit être installé et activé dans php.ini</li>

                      <li> <b>Support base de données</b> &#8211; doit exister pour MySQL, SQL
                      Server, Oracle, ou DB2</li>

                      <li> <b>Config.php</b> &#8211; doit exister et disposer des
                                   autorisations nécessaires pour le rendre accessible en écriture</li>
					  <li>Les fichiers de Sugar suivants doivent être accessibles en écriture :<ul><li><b>/custom</li>
<li>/cache</li>
<li>/modules</li>
<li>/upload</b></li></ul></li></ul>
                                  Si la vérification échoue, vous ne serez pas en mesure de procéder à l\'installation. Un message d\'erreur s’affichera, expliquant pourquoi votre système
                                  a échoué lors de la vérification.
                                  Après avoir fait les changements nécessaires, vous pourrez re-lancer la vérification
                                   et ainsi poursuivre votre installation.<br>',
    'REQUIRED_INSTALLTYPE' => 'Installation Typique ou Personnalisée',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Après que la vérification système est effectuée avec succès vous pouvez choisir 
                       le type d'installation que vous souhaitez effectuer.<br><br>
                      Pour les installations <b>Typique</b> et <b>Personnalisée</b>, les différences sont les suivantes :<br>
                      <ul>
                      <li> <b>Type de base de données</b> qui peut contenir les données Sugar <ul><li>Base de données compatible
                      : MySQL, MS SQL Server, Oracle, DB2.<br><br></li></ul></li>
                      <li> <b>Nom du serveur web ou de la machine (host) sur laquelle la base de données se situe
                      <ul><li>Cela peut être <i>localhost</i> si la base de données est sur votre ordinateur ou sur la même machine que le serveur web contenant vos fichiers Sugar.<br><br></li></ul></li>
                      <li><b>Nom de la base de données </b> que vous voulez utiliser pour stocker vos données Sugar</li>
                        <ul>
                          <li> Vous avez peut-être déjà une base de données existante que vous souhaitez utiliser. Si
                          Si vous fournissez un nom de base de données existante, les tables de cette base de données
                          seront supprimées durant l'installation de la base de données Sugar.</li>
                          <li> Si vous n'avez pas déjà une base de données, le nom que vous avez fourni sera utilisé pour
                          créer la nouvelle base de données durant l'installation.<br><br></li>
                        </ul>
                      <li><b>L'utilisateur et le mot de passe administrateur de la base de données</b> <ul><li>L'administrateur de base de données doit avoir le droit de créer des tables et des utilisateurs ainsi que d'écrire dans la base.</li><li>Vous devez 
                      contacter votre administrateur de base de données pour cette information si la base de données
                      n'est pas située en local et/ou si vous n'êtes pas l'administrateur de la base de données.<br><br></ul></li></li>
                      <li> <b>Nom d'utilisateur et mot de passe de la base de données Sugar.</b>
                      </li>
                        <ul>
                          <li> L'utilisateur peut être l'administrateur de la base de données, ou vous pouvez fournir
                          un nom d'un autre utilisateur de la base de données.</li>
                          <li> Si vous voulez créer un nouvel utilisateur pour cela, vous devez 
                          fournir un nouveau nom et mot de passe durant la procédure d'installation,
                          et cet utilisateur sera créé durant l'installation. </li>
                        </ul>
                    <li> <b>Port et hôte Elasticsearch</b>
                      </li>
                        <ul>
                          <li> L'hôte Elasticsearch est l'hôte qu'exécute le moteur de recherche. La valeur par défaut est localhost en supposant que vous exécutez le moteur de recherche sur le même serveur que Sugar.</li>
                          <li>Port ElasticSearch est le numéro de port qui permet à Sugar de se connecter au moteur de recherche. La valeur par défaut est 9200, qui est la valeur par défaut d'ElasticSearch. </li>
                        </ul>
                        </ul><p>

                      Pour l'installation  <b>Personnalisée</b> vous devez savoir :<br>
                      <ul>
                      <li> <b>URL qui sera utilisée pour accéder à l'instance de Sugar</b>  après son installation.
                      Cette URL doit inclure le serveur web ou le nom de la machine ou l'adresse IP.<br><br></li>
                                  <li> [Optional] <b>Chemin du répertoire de session</b> Si vous souhaitez utiliser un répertoire
                                  session directory for Sugar information in personnel pour les sessions afin de prévenir 
                                  les risques liés aux serveurs partagés.<br><br></li>
                                  <li> [Optional] <b>Chemin vers le répertoire de log</b> Si vous souhaitez surcharger la valeur par défaut du répertoire de log de Sugar.<br><br></li>
                                  <li> [Optional] <b>Application ID</b> Si vous souhaitez surcharger l'ID généré automatiquement
                                   qui assure que les sessions de cette instance de Sugar ne sont pas utilisées par d'autres instances.<br><br></li>
                                  <li><b>Le Jeu de caractères</b> le plus couramment utilisé dans votre région.<br><br></li></ul>
                                  Pour plus d'informations, veuillez consulter le Guide d'Installation.
                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Veuillez lire les informations importantes ci-après avant de procéder à l\'installation. Ces informations vous aideront à déterminer si oui ou non vous êtes prêt à installer l\'application en ce moment.',


	'LBL_WELCOME_2'						=> 'Pour la documentation d’installation, veuillez consulter <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>. <BR><BR> Pour contacter un technicien de SugarCRM et obtenir de l\'aide sur l\'installation, connectez-vous au <a target="_blank" href="http://support.sugarcrm.com">Portail de support de SugarCRM</a> et envoyez un ticket.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Choisir votre langue</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Assistant d\'installation',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Bienvenue sur SugarCRM ',
	'LBL_WELCOME_TITLE'					=> 'Assistant de configuration SugarCRM',
	'LBL_WIZARD_TITLE'					=> 'Assistant de configuration SugarCRM : ',
	'LBL_YES'							=> 'Oui',
    'LBL_YES_MULTI'                     => 'Oui - Multi-octets',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Actions des Processus de Workflow',
	'LBL_OOTB_REPORTS'		=> 'Lancer les actions planifiées de génération de rapports',
	'LBL_OOTB_IE'			=> 'Vérifier les boîtes aux emails entrants',
	'LBL_OOTB_BOUNCE'		=> 'Lancer le process nocturne de gestion des bounces des campagnes emails',
    'LBL_OOTB_CAMPAIGN'		=> 'Lancer le process nocturne d\'envoi des Campagnes emails',
	'LBL_OOTB_PRUNE'		=> 'Purger la BDD le premier de chaque mois',
    'LBL_OOTB_TRACKER'		=> 'Purger la table des historiques utilisateurs le premier de chaque mois',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Lancez les notifications de rappel par email',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Mettre à jour la table tracker_sessions',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Nettoyer la file des jobs',


    'LBL_FTS_TABLE_TITLE'     => 'Paramètres de la recherche Full-Text',
    'LBL_FTS_HOST'     => 'Hôte',
    'LBL_FTS_PORT'     => 'Port',
    'LBL_FTS_TYPE'     => 'Type de moteur de recherche',
    'LBL_FTS_HELP'      => 'Pour activer la recherche full-text, sélectionnez le type de moteur de recherche puis saisissez les paramètres d\'accès à celui-ci. Par défaut le moteur de recherche Elastic Search est inclus dans SugarCRM.',
    'LBL_FTS_REQUIRED'    => 'Elastic Search est requis pour le bon fonctionnement de l\'application.',
    'LBL_FTS_CONN_ERROR'    => 'Impossible de se connecter au serveur hébergeant le moteur de recherche full-text, merci de vérifier les paramètres saisis.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Version complète du serveur recherche de texte indisponible, veuillez vérifier vos paramètres.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Version non prise en charge de Recherche élastique détectée. Veuillez utiliser les versions : %s',

    'LBL_PATCHES_TITLE'     => 'Installer les derniers Patches',
    'LBL_MODULE_TITLE'      => 'Télécharger et Installer des packs de traduction',
    'LBL_PATCH_1'           => 'Si vous souhaitez passer cette étape, cliquez sur le bouton Next.',
    'LBL_PATCH_TITLE'       => 'Patch système',
    'LBL_PATCH_READY'       => 'Le(s) patch(s) sont prêts à être installés.',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "SugarCRM utilise les sessions PHP pour stocker des informations importantes lorsqu'il est connecté à ce serveur Web. Votre installation PHP n'est pas configurée correctement pour utiliser les sessions.
											<br><br>Le paramètre <b>'session.save_path'</b> ne pointe pas vers un répertoire valide.  <br>
											<br> Merci de corriger votre <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>configuration PHP</a> dans le fichier php.ini présenté ci-dessous.",
	'LBL_SESSION_ERR_TITLE'				=> 'Erreur de configuration de sessions PHP',
	'LBL_SYSTEM_NAME'=>'Nom du Système',
    'LBL_COLLATION' => 'Paramètres de collation',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Le nom du système doit être renseigné',
	'LBL_PATCH_UPLOAD' => 'Uploader un patch',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'Le mode Php Backward Compatibility est activé. Positionnez zend.ze1_compatibility_mode à Off pour la suite',

    'advanced_password_new_account_email' => array(
        'subject' => 'Information de votre compte utilisateur',
        'description' => 'Ce modèle est utilisé quand l\'administrateur envoi un nouveau mot de passe à un utilisateur.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Voici votre login utilisateur et mot de passe temporaire :</p><p>Login : $contact_user_user_name </p><p>Mot de passe : $contact_user_user_hash </p><br><p>$config_site_url</p><br><p>Une fois identifié sur l\'application avec les identifiants ci-dessus, vous devrez réinitialiser votre mot de passe à une valeur de votre choix.</p>   </td>         </tr><tr><td colspan=\\"2\\"></td>         </tr> </tbody></table> </div>',
        'txt_body' =>
'
Voici votre login utilisateur et mot de passe temporaire :
Login : $contact_user_user_name
Mot de passe : $contact_user_user_hash

$config_site_url

Une fois identifié sur l\'application avec les identifiants ci-dessus, vous devrez réinitialiser votre mot de passe à une valeur de votre choix.',
        'name' => 'Email mot de passe auto généré',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Réinitialiser le mot de passe de votre compte utilisateur',
        'description' => "Ce modèle est utilisé pour transmettre à un utilisateur un lien à cliquer pour réinitialiser le mot de passe de son compte.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\ »><p>Vous venez de demander le $contact_user_pwd_last_changed de pouvoir réinitialiser le mot de passe de votre compte utilisateur.</p><p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p><p> $contact_user_link_guid </p>  </td>         </tr><tr><td colspan=\\"2\\"></td>         </tr> </tbody></table> </div>',
        'txt_body' =>
'
Vouz venez de demander le $contact_user_pwd_last_changed de pouvoir réinitialiser le mot de passe de votre compte utilisateur.

Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :

$contact_user_link_guid',
        'name' => 'Mot de passe d\'email oublié',
        ),
);
