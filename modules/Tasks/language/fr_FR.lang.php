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
  'LBL_TASKS_LIST_DASHBOARD' => 'Tableau de bord de la liste de tâches',

  'LBL_MODULE_NAME' => 'Tâches',
  'LBL_MODULE_NAME_SINGULAR' => 'Tâche',
  'LBL_TASK' => 'Tâches :',
  'LBL_MODULE_TITLE' => ' Tâches : Accueil',
  'LBL_SEARCH_FORM_TITLE' => 'Recherche de Tâche',
  'LBL_LIST_FORM_TITLE' => 'Liste des Tâches',
  'LBL_NEW_FORM_TITLE' => 'Planifier une Tâche',
  'LBL_NEW_FORM_SUBJECT' => 'Sujet :',
  'LBL_NEW_FORM_DUE_DATE' => 'Date d&#39;échéance :',
  'LBL_NEW_FORM_DUE_TIME' => 'Heure échéance :',
  'LBL_NEW_TIME_FORMAT' => '(24 :00)',
  'LBL_LIST_CLOSE' => 'Fermer',
  'LBL_LIST_SUBJECT' => 'Sujet',
  'LBL_LIST_CONTACT' => 'Contact',
  'LBL_LIST_PRIORITY' => 'Priorité',
  'LBL_LIST_RELATED_TO' => 'Relatif à',
  'LBL_LIST_DUE_DATE' => 'Date d&#39;échéance',
  'LBL_LIST_DUE_TIME' => 'Heure d&#39;échéance',
  'LBL_SUBJECT' => 'Sujet :',
  'LBL_STATUS' => 'Statut :',
  'LBL_DUE_DATE' => 'Date d&#39;échéance :',
  'LBL_DUE_TIME' => 'Heure échéance :',
  'LBL_PRIORITY' => 'Priorité :',
  'LBL_COLON' => ' :',
  'LBL_DUE_DATE_AND_TIME' => 'Date & Heure échéance :',
  'LBL_START_DATE_AND_TIME' => 'Date et Heure début :',
  'LBL_START_DATE' => 'Date de début :',
  'LBL_LIST_START_DATE' => 'Date de début',
  'LBL_START_TIME' => 'Heure de début :',
  'LBL_LIST_START_TIME' => 'Heure de début',
  'DATE_FORMAT' => '(aaaa-mm-jj)',
  'LBL_NONE' => 'Indéfini',
  'LBL_CONTACT' => 'Contact :',
  'LBL_EMAIL_ADDRESS' => 'Adresse Email :',
  'LBL_PHONE' => 'Téléphone :',
  'LBL_EMAIL' => 'Email:',
  'LBL_DESCRIPTION_INFORMATION' => 'Description',
  'LBL_DESCRIPTION' => 'Description :',
  'LBL_NAME' => 'Nom :',
  'LBL_CONTACT_NAME' => 'Nom Contact :',
  'LBL_LIST_COMPLETE' => 'Terminé :',
  'LBL_LIST_STATUS' => 'Statut',
  'LBL_DATE_DUE_FLAG' => 'Aucune date de deadline',
  'LBL_DATE_START_FLAG' => 'Aucune date de début',
  'ERR_DELETE_RECORD' => 'Un ID doit être spécifié pour toute suppression.',
  'ERR_INVALID_HOUR' => 'Merci de saisir une heure entre 0 et 24',
  'LBL_DEFAULT_PRIORITY' => 'Moyenne',
  'LBL_LIST_MY_TASKS' => 'Mes Tâches ouvertes',
  'LNK_NEW_TASK' => 'Créer Tâche',
  'LNK_TASK_LIST' => 'Tâches',
  'LNK_IMPORT_TASKS' => 'Import Tâche',
  'LBL_CONTACT_FIRST_NAME'=>'Prénom du Contact',
  'LBL_CONTACT_LAST_NAME'=>'Nom du Contact',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigné à',
  'LBL_ASSIGNED_TO_NAME'=>'Assigné à :',
  'LBL_LIST_DATE_MODIFIED' => 'Date de modification',
  'LBL_CONTACT_ID' => 'ID Contact :',
  'LBL_PARENT_ID' => 'ID Parent :',
  'LBL_CONTACT_PHONE' => 'Téléphone du Contact :',
  'LBL_PARENT_NAME' => 'Type de Parent :',
  'LBL_ACTIVITIES_REPORTS' => 'Rapport d&#39;activités',
  'LBL_EDITLAYOUT' => 'Éditer la mise en page' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Informations Tâches',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notes',
  'LBL_REVENUELINEITEMS' => 'Lignes de revenu',
  //For export labels
  'LBL_DATE_DUE' => 'Date échéance',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigné à',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigné à (ID)',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modifié par (ID)',
  'LBL_EXPORT_CREATED_BY' => 'Créé par (ID)',
  'LBL_EXPORT_PARENT_TYPE' => 'Relatif à',
  'LBL_EXPORT_PARENT_ID' => 'Relatif à (ID)',
  'LBL_TASK_CLOSE_SUCCESS' => 'Tâche clôturée avec succès.',
  'LBL_ASSIGNED_USER' => 'Assigné à',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Le module {{plural_module_name}} permet de gérer des actions, des listes de tâches, ou tout autre type d&#39;activité qui requiert un achèvement. Les {{module_name}} peuvent être reliées à un enregistrement principal de la majorité des modules via le champ "Relatif à" et peuvent être reliées aussi à un {{contacts_singular_module}} principal. Il y a plusieurs manière de créer des {{plural_module_name}} dans Sugar via le module {{plural_module_name}}, dupliquer ou importer des {{plural_module_name}}, etc. Une fois la {{module_name}} créée, vous pouvez voir et éditer les informations de la {{module_name}} via la vue Enregistrement du module {{plural_module_name}}. En fonction des informations sur la {{module_name}}, vous pouvez afficher et éditer la {{module_name}} via le module Calendrier. Chaque enregistrement de {{module_name}} peut être lié à d&#39;autre enregistrements comme des {{accounts_module}}, {{contacts_module}} ou {{opportunities_module}}.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Le module {{plural_module_name}} permet de gérer des actions, des listes de tâches, ou tout autres type d&#39;activité qui requiert un achèvement.

- Éditer chaque champs en cliquant directement sur le champ concerné ou en cliquant sur le bouton Éditer.
- Afficher ou modifier les liaisons avec les autres enregistrements via les sous-panels.
- Afficher et participer aux commentaire et au flux d&#39;activité via le module {{activitystream_singular_module}} en cliquant sur le bouton "Flux d’activité".
- Suivre ou mettre en favoris l&#39;enregistrement en utilisant les icônes prévues à cet effet à droite du nom de l&#39;enregistrement.
- Des actions complémentaires sont disponibles dans la listes déroulantes des actions à droite du bouton Éditer.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Le module {{plural_module_name}} permet de gérer des actions, des listes de tâches ou tout autre type d&#39;activité à exécuter.

Pour créer un enregistrement {{module_name}}, les étapes suivantes sont nécessaires :
1. Remplir les champs souhaités.
 - Les champs identifiés comme "Obligatoire" doivent être complétés avant la sauvegarde.
 - Cliquer sur "Voir plus" pour afficher plus de champs, si nécessaire.
2. Cliquer sur "Sauvegarder" pour finaliser l&#39;enregistrement et retourner sur la page précédente.',

);
