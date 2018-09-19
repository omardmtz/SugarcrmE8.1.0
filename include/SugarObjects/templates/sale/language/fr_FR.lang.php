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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'Ventes',
  'LBL_MODULE_TITLE' => 'Ventes : Accueil',
  'LBL_SEARCH_FORM_TITLE' => 'Recherche de ventes',
  'LBL_VIEW_FORM_TITLE' => 'Vue de ventes',
  'LBL_LIST_FORM_TITLE' => 'Listes des ventes',
  'LBL_SALE_NAME' => 'Nom de la vente :',
  'LBL_SALE' => 'Vente :',
  'LBL_NAME' => 'Nom de la vente',
  'LBL_LIST_SALE_NAME' => 'Nom',
  'LBL_LIST_ACCOUNT_NAME' => 'Nom Compte',
  'LBL_LIST_AMOUNT' => 'Montant',
  'LBL_LIST_DATE_CLOSED' => 'Fermée',
  'LBL_LIST_SALE_STAGE' => 'Etape de Vente',
  'LBL_ACCOUNT_ID'=>'Réf Compte',
  'LBL_TEAM_ID' =>'Équipe (ID)',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Ventes - Devise Mise à Jour',
  'UPDATE_DOLLARAMOUNTS' => 'Mise à  Jour des montants en devise par défaut',
  'UPDATE_VERIFY' => 'Vérifier les Montants',
  'UPDATE_VERIFY_TXT' => 'Vérifie la valeur des montants  : chiffre uniquement et séparateur decimal (.)',
  'UPDATE_FIX' => 'Réparer les montants',
  'UPDATE_FIX_TXT' => 'Tentative de correction des montants invalides. Les anciens montants seront sauvegardés en base.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Mise à jour des montants en dollars Us des Affaires basés sur le cours actuel des devises. Cette valeur est utilisée pour réaliser les montants en devises des graphiques et vues liste.',
  'UPDATE_CREATE_CURRENCY' => 'Créer une devise :',
  'UPDATE_VERIFY_FAIL' => 'Échec de Verification de cet enregistrement :',
  'UPDATE_VERIFY_CURAMOUNT' => 'Montant actuel :',
  'UPDATE_VERIFY_FIX' => 'Lancer la réparation',
  'UPDATE_INCLUDE_CLOSE' => 'Inclure enregistrements clos',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nouveau Montant :',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nouvelle devise :',
  'UPDATE_DONE' => 'Terminé',
  'UPDATE_BUG_COUNT' => 'Bugs trouvés, tentative de correction :',
  'UPDATE_BUGFOUND_COUNT' => 'Bugs trouvés :',
  'UPDATE_COUNT' => 'Enregistrement mis à jour :',
  'UPDATE_RESTORE_COUNT' => 'Montants enregistrés restaurés :',
  'UPDATE_RESTORE' => 'Restaurer les Montants',
  'UPDATE_RESTORE_TXT' => 'Montants restaurés sur la sauvegarde créée lors de la réparation.',
  'UPDATE_FAIL' => 'Mise à Jour Impossible',
  'UPDATE_NULL_VALUE' => 'Montant NULL, mise à 0',
  'UPDATE_MERGE' => 'Fusionner les devises',
  'UPDATE_MERGE_TXT' => 'Fusionner plusieurs devises dans la monnaie unique. S&#39;il existe plusieurs enregistrements de devise pour la même devise, fusionnez-les ensemble. Cela va fusionner également les devises pour tous les autres modules.',
  'LBL_ACCOUNT_NAME' => 'Nom du Compte :',
  'LBL_AMOUNT' => 'Montant :',
  'LBL_AMOUNT_USDOLLAR' => 'Montant en dollars US :',
  'LBL_CURRENCY' => 'Devise :',
  'LBL_DATE_CLOSED' => 'Date de Clôture :',
  'LBL_TYPE' => 'Type :',
  'LBL_CAMPAIGN' => 'Campagne :',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projets',  
  'LBL_NEXT_STEP' => 'Prochaine étape:',
  'LBL_LEAD_SOURCE' => 'Origine Principale :',
  'LBL_SALES_STAGE' => 'Phase de vente :',
  'LBL_PROBABILITY' => 'Probabilité (%) :',
  'LBL_DESCRIPTION' => 'Description :',
  'LBL_DUPLICATE' => 'Possibilité de doublon des Ventes',
  'MSG_DUPLICATE' => 'Possibilité de doublon des Ventes. Vous pouvez sélectionner une vente dans la liste ci dessous.<br>Cliquez sur "Sauvegarder" pour poursuivre la création, ou sur "Annuler "pour retourner au module sans créer la vente.',
  'LBL_NEW_FORM_TITLE' => 'Créer une Vente',
  'LNK_NEW_SALE' => 'Créer une Vente',
  'LNK_SALE_LIST' => 'Ventes',
  'ERR_DELETE_RECORD' => 'Un ID doit être spécifié pour toute suppression.',
  'LBL_TOP_SALES' => 'Top des Ventes',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Êtes-vous sûr(e) de vouloir supprimer ce contact de cette vente ?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Êtes-vous sûr(e) de vouloir supprimer cette vente de ce projet ?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activités à réaliser',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Historique et Activités terminées',
    'LBL_RAW_AMOUNT'=>'Montant Brut',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacts',
	'LBL_ASSIGNED_TO_NAME' => 'Assigné à :',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigné à',
  'LBL_MY_CLOSED_SALES' => 'Mes Ventes clôturées',
  'LBL_TOTAL_SALES' => 'Total des Ventes',
  'LBL_CLOSED_WON_SALES' => 'Ventes clôturées et gagnées',
  'LBL_ASSIGNED_TO_ID' =>'Assigné à (ID)',
  'LBL_CREATED_ID'=>'Créé par (ID)',
  'LBL_MODIFIED_ID'=>'Modifié par (ID)',
  'LBL_MODIFIED_NAME'=>'Modifié par',
  'LBL_SALE_INFORMATION'=>'Informations de Ventes',
  'LBL_CURRENCY_ID'=>'Devise ID',
  'LBL_CURRENCY_NAME'=>'Devise',
  'LBL_CURRENCY_SYMBOL'=>'Devise symbole',
  'LBL_EDIT_BUTTON' => 'Éditer',
  'LBL_REMOVE' => 'Supprimer',
  'LBL_CURRENCY_RATE' => 'Taux de change',

);

