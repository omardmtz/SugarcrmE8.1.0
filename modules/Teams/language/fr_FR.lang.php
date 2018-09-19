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
    'ERR_ADD_RECORD' => 'Vous devez spécifier un ID pour ajouter un utilisateur à cette équipe.',
    'ERR_DUP_NAME' => 'Le nom de l&#39;équipe existe déjà, merci de choisir un autre nom.',
    'ERR_DELETE_RECORD' => 'Un ID doit être spécifié pour toute suppression.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Erreur. L&#39;équipe sélectionnée <b>({0})</b> est l&#39;équipe que vous avez choisie pour être supprimée. Veuillez sélectionner une autre équipe.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Erreur. Vous ne pouvez pas supprimer un utilisateur dont l&#39;équipe privée n&#39;a pas été supprimée.',
    'LBL_DESCRIPTION' => 'Description :',
    'LBL_GLOBAL_TEAM_DESC' => 'Équipe comprenant tous les utilisateurs',
    'LBL_INVITEE' => 'Membres de &#39;équipe',
    'LBL_LIST_DEPARTMENT' => 'Service',
    'LBL_LIST_DESCRIPTION' => 'Description',
    'LBL_LIST_FORM_TITLE' => 'Liste d&#39;équipes',
    'LBL_LIST_NAME' => 'Nom',
    'LBL_FIRST_NAME' => 'Prénom :',
    'LBL_LAST_NAME' => 'Nom de famille :',
    'LBL_LIST_REPORTS_TO' => 'Rend compte à',
    'LBL_LIST_TITLE' => 'Fonction',
    'LBL_MODULE_NAME' => 'Équipes',
    'LBL_MODULE_NAME_SINGULAR' => 'Équipe',
    'LBL_MODULE_TITLE' => 'Équipes',
    'LBL_NAME' => 'Nom de l&#39;équipe :',
    'LBL_NAME_2' => 'Nom de l&#39;équipe(2) :',
    'LBL_PRIMARY_TEAM_NAME' => 'Nom de l&#39;équipe principale',
    'LBL_NEW_FORM_TITLE' => 'Nouvelle équipe',
    'LBL_PRIVATE' => 'Privée',
    'LBL_PRIVATE_TEAM_FOR' => 'Équipe privée de',
    'LBL_SEARCH_FORM_TITLE' => 'Rechercher une équipe',
    'LBL_TEAM_MEMBERS' => 'Membres de &#39;équipe',
    'LBL_TEAM' => 'Équipes :',
    'LBL_USERS_SUBPANEL_TITLE' => 'Membres',
    'LBL_USERS' => 'Membres',
    'LBL_REASSIGN_TEAM_TITLE' => 'Il y a des enregistrements assignés à l&#39;équipe suivante : <b>{0}</b><br>Avant de supprimer ce(s) équipe(s), vous devez au préalable réassigner ces enregistrements à une nouvellle équipe. Sélectionner une équipe à utiliser en remplacement.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Réassigner',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Réassigner [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Êtes-vous sûr(e) de vouloir mettre à jour les enregistrements sélectionnés afin d&#39;utiliser cette nouvelle équipe ?',
    'LBL_REASSIGN_TABLE_INFO' => 'Table mise à jour {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'L&#39;opération a été réalisée avec succès.',
    'LNK_LIST_TEAM' => 'Équipes',
    'LNK_LIST_TEAMNOTICE' => 'Notification aux équipes',
    'LNK_NEW_TEAM' => 'Nouvelle équipe',
    'LNK_NEW_TEAM_NOTICE' => 'Créer une notification aux équipes',
    'NTC_DELETE_CONFIRMATION' => 'Êtes-vous sûr de vouloir supprimer cet enregistrement ?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Êtes-vous sûr(e) de vouloir supprimer la relation avec cet utilisateur ?',
    'LBL_EDITLAYOUT' => 'Éditer la mise en page' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Autorisations d&#39;équipe',
    'LBL_TBA_CONFIGURATION_DESC' => 'Activer l’accès de l’équipe et gérer l’accès par module.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Activer les autorisations d&#39;équipe',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Sélectionnez les modules à activer',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Activer des autorisations d&#39;équipe vous permettra d&#39;attribuer des droits d&#39;accès spécifiques aux équipes et aux utilisateurs pour les modules individuels, via la Gestion des rôles.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Désactiver des autorisations d'équipe pour un module retournera toutes les données associées aux autorisations d'équipe pour ce module, y compris les Définitions de processus ou les Processus utilisant cette fonctionnalité. Ceci inclut tous les rôles utilisant l'option « Propriétaire et équipe sélectionnée » pour ce module, ainsi que toutes les données d'autorisation d'équipe pour les enregistrements de ce module. Nous recommandons également d'utiliser la Réparation rapide et l'outil Reconstruire pour effacer votre cache système après avoir désactivé les autorisations d'équipe pour tous les modules.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Warning:</strong> Désactiver des autorisations d'équipe pour un module retournera toutes les données associées aux autorisations d'équipe pour ce module, y compris les Définitions de processus ou les Processus utilisant cette fonctionnalité. Ceci inclut tous les rôles utilisant l'option « Propriétaire et équipe sélectionnée » pour ce module, ainsi que toutes les données d'autorisation d'équipe pour les enregistrements de ce module. Nous recommandons également d'utiliser la Réparation rapide et l'outil Reconstruire pour effacer votre cache système après avoir désactivé les autorisations d'équipe pour tous les modules.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Désactiver les autorisations d'équipe pour un module permet de retourner toutes les données associées aux autorisations d'équipe pour ce module, y compris les définitions de processus ou les processus utilisant cette fonctionnalité. Ceci inclut tous les rôles utilisant l'option « Propriétaire et équipe sélectionnée » pour ce module, ainsi que toutes les données d'autorisation d'équipe pour les enregistrements de ce module. Nous recommandons également d'utiliser la Réparation rapide et l'outil Reconstruire pour effacer le cache du système après la désactivation des autorisations d'équipe pour tout module. Si vous n'avez pas accès à Réparation rapide et Reconstruire, contactez un administrateur qui peut accéder au menu Réparation.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Warning:</strong> Désactiver les autorisations d'équipe pour un module permet de retourner toutes les données associées aux autorisations d'équipe pour ce module, y compris les définitions de processus ou les processus utilisant cette fonctionnalité. Ceci inclut tous les rôles utilisant l'option « Propriétaire et équipe sélectionnée » pour ce module, ainsi que toutes les données d'autorisation d'équipe pour les enregistrements de ce module. Nous recommandons également d'utiliser la Réparation rapide et l'outil Reconstruire pour effacer le cache du système après la désactivation des autorisations d'équipe pour tout module. Si vous n'avez pas accès à Réparation rapide et Reconstruire, contactez un administrateur qui peut accéder au menu Réparation.
STR
,
);
