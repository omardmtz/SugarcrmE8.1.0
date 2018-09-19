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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Tableau de bord de la liste de notes',

    'ERR_DELETE_RECORD' => 'Vous devez spécifier un ID pour toute suppression.',
    'LBL_ACCOUNT_ID' => 'Compte (ID) :',
    'LBL_CASE_ID' => 'Ticket (ID) :',
    'LBL_CLOSE' => 'Clos :',
    'LBL_COLON' => ' :',
    'LBL_CONTACT_ID' => 'Contact (ID):',
    'LBL_CONTACT_NAME' => 'Contact :',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notes',
    'LBL_DESCRIPTION' => 'Note',
    'LBL_EMAIL_ADDRESS' => 'Adresse Email:',
    'LBL_EMAIL_ATTACHMENT' => 'Pièces jointes',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Pièce jointe d&#39;e-mail pour',
    'LBL_FILE_MIME_TYPE' => 'Type Mime',
    'LBL_FILE_EXTENSION' => 'Extension de fichier',
    'LBL_FILE_SOURCE' => 'Source de fichier',
    'LBL_FILE_SIZE' => 'Taille de fichier',
    'LBL_FILE_URL' => 'URL Fichier',
    'LBL_FILENAME' => 'Pièce jointe :',
    'LBL_LEAD_ID' => 'Lead (ID) :',
    'LBL_LIST_CONTACT_NAME' => 'Contact',
    'LBL_LIST_DATE_MODIFIED' => 'Dernière modification',
    'LBL_LIST_FILENAME' => 'Pièce jointe',
    'LBL_LIST_FORM_TITLE' => 'Listing des Notes',
    'LBL_LIST_RELATED_TO' => 'Relatif à',
    'LBL_LIST_SUBJECT' => 'Sujet',
    'LBL_LIST_STATUS' => 'Statut',
    'LBL_LIST_CONTACT' => 'Contact',
    'LBL_MODULE_NAME' => 'Notes',
    'LBL_MODULE_NAME_SINGULAR' => 'Note',
    'LBL_MODULE_TITLE' => 'Notes',
    'LBL_NEW_FORM_TITLE' => 'Créer Note',
    'LBL_NEW_FORM_BTN' => 'Ajouter une note',
    'LBL_NOTE_STATUS' => 'Note',
    'LBL_NOTE_SUBJECT' => 'Sujet :',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Pièce(s) jointe(s)',
    'LBL_NOTE' => 'Note :',
    'LBL_OPPORTUNITY_ID' => 'Affaire (ID) :',
    'LBL_PARENT_ID' => 'Parent (ID):',
    'LBL_PARENT_TYPE' => 'Type Parentée',
    'LBL_EMAIL_TYPE' => 'Type d&#39;e-mail',
    'LBL_EMAIL_ID' => 'ID d&#39;e-mail',
    'LBL_PHONE' => 'Téléphone :',
    'LBL_PORTAL_FLAG' => 'Afficher dans le Portail?',
    'LBL_EMBED_FLAG' => 'Joindre à l&#39;email?',
    'LBL_PRODUCT_ID' => 'Produit (ID) :',
    'LBL_QUOTE_ID' => 'Devis (ID) :',
    'LBL_RELATED_TO' => 'Lié à :',
    'LBL_SEARCH_FORM_TITLE' => 'Rechercher une Note',
    'LBL_STATUS' => 'Statut',
    'LBL_SUBJECT' => 'Sujet :',
    'LNK_IMPORT_NOTES' => 'Notes d&#39;import',
    'LNK_NEW_NOTE' => 'Créer Note',
    'LNK_NOTE_LIST' => 'Notes',
    'LBL_MEMBER_OF' => 'Membre de:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigné à',
    'LBL_OC_FILE_NOTICE' => 'Merci de vous connecter au serveur pour visualiser le fichier',
    'LBL_REMOVING_ATTACHMENT' => 'Suppression de la pièce jointe...',
    'ERR_REMOVING_ATTACHMENT' => 'Échec lors de la suppression de la pièce jointe...',
    'LBL_CREATED_BY' => 'Créé par',
    'LBL_MODIFIED_BY' => 'Modifié par',
    'LBL_SEND_ANYWAYS' => 'Votre email est sans sujet !. Voulez vous tout de même envoyer/sauvegarder ?',
    'LBL_LIST_EDIT_BUTTON' => 'Éditer',
    'LBL_ACTIVITIES_REPORTS' => 'Rapport d&#39;activités',
    'LBL_PANEL_DETAILS' => 'Détails',
    'LBL_NOTE_INFORMATION' => 'Informations Note',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mes notes',
    'LBL_EDITLAYOUT' => 'Éditer la mise en page' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Prénom',
    'LBL_LAST_NAME' => 'Nom de Famille',
    'LBL_EXPORT_PARENT_TYPE' => 'Relatif à',
    'LBL_EXPORT_PARENT_ID' => 'Relatif à (ID)',
    'LBL_DATE_ENTERED' => 'Date de création',
    'LBL_DATE_MODIFIED' => 'Date de modification',
    'LBL_DELETED' => 'Supprimé',
    'LBL_REVENUELINEITEMS' => 'Lignes de revenu',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Le module {{plural_module_name}} permet gérer des {{plural_module_name}} qui contiennent du texte ou un document en relation avec un enregistrement. Les enregistrements du module {{module_name}} peuvent être liés à la majorité des autres enregistrement via le champ "Relatif à" ainsi qu&#39;a un enregistrement particulier du module {{contacts_singular_module}}. Les enregistrement du module {{plural_module_name}}. Il y a plusieurs manière de créer des enregistrements dans le module  {{plural_module_name}} au sein de Sugar, comme par exemple via le module {{plural_module_name}} lui-même, en dupliquant un enregistrement existant, en utilisant la fonction d&#39;import du module {{plural_module_name}}, etc. Une fois l&#39;enregistrement créé dans le module {{module_name}}, vous pouvez afficher et modifier les informations directement via le module {{module_name}} en allant sur la vue Enregistrement du module {{plural_module_name}}. Chaque {{module_name}} peuvent avoir des relations avce d&#39;autres enregistrements présents dans Sugar comme les {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, et de nombreux autres.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Le module {{plural_module_name}} permet gérer des {{plural_module_name}} qui contiennent du texte ou un document en relation avec un enregistrement.

- Éditer chaque champs en cliquant directement sur le champ concerné ou en cliquant sur le bouton Éditer.
- Afficher ou modifier les liaisons avec les autres enregistrements via les sous-panels.
- Afficher et participer aux commentaire et au flux d&#39;activité via le module {{activitystream_singular_module}} en cliquant sur le bouton "Flux d’activité".
- Suivre ou mettre en favoris l&#39;enregistrement en utilisant les icônes prévues à cet effet à droite du nom de l&#39;enregistrement.
- Des actions complémentaires sont disponibles dans la listes déroulantes des actions à droite du bouton Éditer.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Pour créer un enregistrement {{module_name}}, les étapes suivantes sont nécessaires :
1. Remplir les champs souhaités.
 - Les champs identifiés comme "Obligatoire" doivent être complétés avant la sauvegarde.
 - Cliquer sur "Voir plus" pour afficher plus de champs, si nécessaire.
2. Cliquer sur "Sauvegarder" pour finaliser l&#39;enregistrement et retourner sur la page précédente.',
);
