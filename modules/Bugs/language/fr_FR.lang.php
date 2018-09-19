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

$mod_strings = array (
  // Dashboard Names
  'LBL_BUGS_LIST_DASHBOARD' => 'Tableau de bord de la liste de bugs',
  'LBL_BUGS_RECORD_DASHBOARD' => 'Tableau de bord d&#39;enregistrements de bugs',

  'LBL_MODULE_NAME' => 'Suivi Bugs',
  'LBL_MODULE_NAME_SINGULAR'	=> 'Bug',
  'LBL_MODULE_TITLE' => 'Bugs : accueil',
  'LBL_MODULE_ID' => 'Bugs',
  'LBL_SEARCH_FORM_TITLE' => 'Rechercher un Bug',
  'LBL_LIST_FORM_TITLE' => 'Liste des Bugs',
  'LBL_NEW_FORM_TITLE' => 'Nouveau Bug',
  'LBL_CONTACT_BUG_TITLE' => 'Contact-Bug :',
  'LBL_SUBJECT' => 'Sujet :',
  'LBL_BUG' => 'Bug :',
  'LBL_BUG_NUMBER' => 'Numéro de bug :',
  'LBL_NUMBER' => 'Numéro :',
  'LBL_STATUS' => 'Statut :',
  'LBL_PRIORITY' => 'Priorité :',
  'LBL_DESCRIPTION' => 'Description :',
  'LBL_CONTACT_NAME' => 'Nom du Contact:',
  'LBL_BUG_SUBJECT' => 'Sujet du Bug :',
  'LBL_CONTACT_ROLE' => 'Rôle :',
  'LBL_LIST_NUMBER' => 'Num.',
  'LBL_LIST_SUBJECT' => 'Sujet',
  'LBL_LIST_STATUS' => 'Statut',
  'LBL_LIST_PRIORITY' => 'Priorité',
  'LBL_LIST_RELEASE' => 'Version',
  'LBL_LIST_RESOLUTION' => 'Résolution',
  'LBL_LIST_LAST_MODIFIED' => 'Dernière modification',
  'LBL_INVITEE' => 'Contacts',
  'LBL_TYPE' => 'Type :',
  'LBL_LIST_TYPE' => 'Type',
  'LBL_RESOLUTION' => 'État :',
  'LBL_RELEASE' => 'Version :',
  'LNK_NEW_BUG' => 'Signaler Bug',
  'LNK_CREATE'  => 'Signaler Bug',
  'LNK_CREATE_WHEN_EMPTY'    => 'Signaler un Bug maintenant.',
  'LNK_BUG_LIST' => 'Bugs',
  'LBL_SHOW_MORE' => 'Afficher plus de bugs',
  'NTC_REMOVE_INVITEE' => 'Êtes-vous sûr(e) de vouloir supprimer ce Contact du Bug ?',
  'NTC_REMOVE_ACCOUNT_CONFIRMATION' => 'Êtes-vous sûr(e) de vouloir supprimer ce Bug de ce Compte ?',
  'ERR_DELETE_RECORD' => 'un ID doit être spécifié pour toute suppression.',
  'LBL_LIST_MY_BUGS' => 'Mes Bugs',
  'LNK_IMPORT_BUGS' => 'Import Bugs',
  'LBL_FOUND_IN_RELEASE' => 'Trouvé en Release :',
  'LBL_FIXED_IN_RELEASE' => 'Fixé en Release :',
  'LBL_LIST_FIXED_IN_RELEASE' => 'Fixé en Release',
  'LBL_WORK_LOG' => 'Réflexion menée :',
  'LBL_SOURCE' => 'Source :',
  'LBL_PRODUCT_CATEGORY' => 'Catégorie :',

  'LBL_CREATED_BY' => 'Créé par :',
  'LBL_DATE_CREATED' => 'Date de création :',
  'LBL_MODIFIED_BY' => 'Modifié par :',
  'LBL_DATE_LAST_MODIFIED' => 'Date de modification :',

  'LBL_LIST_EMAIL_ADDRESS' => 'Adresse Email',
  'LBL_LIST_CONTACT_NAME' => 'Nom Contact',
  'LBL_LIST_ACCOUNT_NAME' => 'Nom Compte',
  'LBL_LIST_PHONE' => 'Téléphone',
  'NTC_DELETE_CONFIRMATION' => 'Êtes-vous sûr(e) de vouloir supprimer ce Contact du Bug ?',

  'LBL_DEFAULT_SUBPANEL_TITLE' => 'Bugs',
  'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activités à Réaliser',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'Historique',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacts',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Comptes',
  'LBL_CASES_SUBPANEL_TITLE' => 'Tickets',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projets',
  'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documents',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigné à',
	'LBL_ASSIGNED_TO_NAME' => 'Assigné à',

	'LNK_BUG_REPORTS' => 'Rapports de Bugs',
	'LBL_SHOW_IN_PORTAL' => 'Afficher dans le portail',
	'LBL_BUG_INFORMATION' => 'Informations Bug',

    //For export labels
	'LBL_FOUND_IN_RELEASE_NAME' => 'Trouvé dans la release',
    'LBL_PORTAL_VIEWABLE' => 'Visible sur le portail',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigné à',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigné à (ID)',
    'LBL_EXPORT_FIXED_IN_RELEASE_NAMR' => 'Fixé dans la Release',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modifié par (ID)',
    'LBL_EXPORT_CREATED_BY' => 'Créé par (ID)',

    //Tour content
    'LBL_PORTAL_TOUR_RECORDS_INTRO' => 'Le module de Bug sert à déclarer et suivre les bugs. Utilisez les flèches ci-dessous pour effectuer un tour rapide.',
    'LBL_PORTAL_TOUR_RECORDS_PAGE' => 'Cette page affiche la liste des bugs publiés.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER' => 'Vous pouvez filtrer la liste des bugs en fournissant un terme de recherche.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER_EXAMPLE' => 'Par exemple, vous pourriez l&#39;utiliser pour trouver un bug que vous avez déclaré préalablement.',
    'LBL_PORTAL_TOUR_RECORDS_CREATE' => 'Si vous avez trouvé un nouveau Bug que vous souhaitez déclarer, vous pouvez cliquer ici pour déclarer un nouveau bug.',
    'LBL_PORTAL_TOUR_RECORDS_RETURN' => 'En cliquant ici vous pouvez revenir sur les bugs à tout moment.',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Le module {{plural_module_name}} permet de gérer et de suivre les problèmes relatifs à vos produits, communément appelés {{plural_module_name}} ou défauts, les problèmes détectés en inter ou remontés par des clients. Les enregistrements du module {{plural_module_name}} pourront être qualifiés ultérieurement en repérant ceux qui sont trouvés et réparés. Les enregistrements du module {{plural_module_name}} offrent la possibilité aux utilisateurs d&#39;avoir une vue rapidement concernant un enregistrement du module {{module_name}} et du processus utilisé pour corriger cela. Une fois l&#39;enregistrement créé ou soumis dans le module {{module_name}}, vous pouvez afficher et modifier les informations directement via le module {{module_name}} en allant sur la vue Enregistrement du module {{module_name}}. Chaque enregistrement du module {{module_name}} peut avoir des relations avec d&#39;autres enregistrements présents dans Sugar comme des {{calls_module}}, {{contacts_module}}, {{cases_module}}, ainsi que de nombreux autres modules.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Le module {{plural_module_name}} permet de gérer et de suivre les problèmes relatifs à vos produits, sont communément appelés {{plural_module_name}} ou défauts, les problèmes détectés en inter ou remontés par des clients.

- Éditer chaque champs en cliquant directement sur le champ concerné ou en cliquant sur le bouton Éditer.
- Afficher ou modifier les liaisons avec les autres enregistrements via les sous-panels.
- Afficher et participer aux commentaire et au flux d&#39;activité via le module {{activitystream_singular_module}} en cliquant sur le bouton "Flux d’activité".
- Suivre ou mettre en favoris l&#39;enregistrement en utilisant les icônes prévues à cet effet à droite du nom de l&#39;enregistrement.
- Des actions complémentaires sont disponibles dans la listes déroulantes des actions à droite du bouton Éditer.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Le module {{plural_module_name}} permet de gérer et de suivre les problèmes liés à vos produits, généralement dénommés {{plural_module_name}} ou défauts, détectés en interne ou remontés par des clients.

Pour créer un enregistrement {{module_name}}, les étapes suivantes sont nécessaires :
1. Remplir les champs souhaités.
- Les champs identifiés comme "Obligatoire" doivent être complétés avant la sauvegarde.
- Cliquer sur "Voir plus" pour afficher plus de champs, si nécessaire.
2. Cliquer sur "Sauvegarder" pour finaliser l&#39;enregistrement et retourner sur la page précédente.',
);
