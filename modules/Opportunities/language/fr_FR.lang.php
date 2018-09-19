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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Tableau de bord de la liste d&#39;affaires',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Tableau de bord de l&#39;enregistrement d&#39;affaires',

    'LBL_MODULE_NAME' => 'Affaires',
    'LBL_MODULE_NAME_SINGULAR' => 'Affaire',
    'LBL_MODULE_TITLE' => 'Affaires',
    'LBL_SEARCH_FORM_TITLE' => 'Rechercher une Affaire',
    'LBL_VIEW_FORM_TITLE' => 'Vue Affaire',
    'LBL_LIST_FORM_TITLE' => 'Liste des Affaires',
    'LBL_OPPORTUNITY_NAME' => 'Nom Affaire :',
    'LBL_OPPORTUNITY' => 'Affaire :',
    'LBL_NAME' => 'Nom Affaire',
    'LBL_INVITEE' => 'Contacts',
    'LBL_CURRENCIES' => 'Devises',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Affaires',
    'LBL_LIST_ACCOUNT_NAME' => 'Compte',
    'LBL_LIST_DATE_CLOSED' => 'Clôture',
    'LBL_LIST_AMOUNT' => 'Réaliste',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Montant',
    'LBL_ACCOUNT_ID' => 'Compte (ID)',
    'LBL_CURRENCY_RATE' => 'Taux de change',
    'LBL_CURRENCY_ID' => 'Devise (ID)',
    'LBL_CURRENCY_NAME' => 'Devise',
    'LBL_CURRENCY_SYMBOL' => 'Symbole de la devise',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Affaire - Devise mise à jour',
    'UPDATE_DOLLARAMOUNTS' => 'Mise à jour des montants en devise par défaut',
    'UPDATE_VERIFY' => 'Vérifier les montants',
    'UPDATE_VERIFY_TXT' => 'Vérifie que les montants des Affaires contiennent des valeurs numériques correctes (valeurs numériques (0-9) et séparateur (,.)',
    'UPDATE_FIX' => 'Réparer les montants',
    'UPDATE_FIX_TXT' => 'Tentative de correction des montants invalides. Les anciens montants seront sauvegardés en base dans le champ amount_backup. Si vous constatez des dysfonctionnement ne l’exécuter pas une seconde fois car vous perdriez votre backup du champ.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Mise à jour des montants des Affaires basés sur le cours actuel des devises. Cette valeur est utilisée pour réaliser les montants en devises des graphiques et vues liste.',
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
    'UPDATE_RESTORE' => 'Restaurer les montants',
    'UPDATE_RESTORE_TXT' => 'Restaurer les montants depuis le backup créé lors de la réparation.',
    'UPDATE_FAIL' => 'Impossible de mettre à jour -',
    'UPDATE_NULL_VALUE' => 'Le montant est NULL et remplacé par 0 -',
    'UPDATE_MERGE' => 'Fusionner les devises',
    'UPDATE_MERGE_TXT' => 'Fusionner les devises. Si vous notez qu&#39;il y a plusieurs devises enregistrées pour la même devise, vous pouvez choisir de les fusionner. Cela va également fusionner les devises pour tous les autres modules.',
    'LBL_ACCOUNT_NAME' => 'Compte:',
    'LBL_CURRENCY' => 'Devise:',
    'LBL_DATE_CLOSED' => 'Date de Clôture :',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Timestamp date clôture attendue',
    'LBL_TYPE' => 'Type :',
    'LBL_CAMPAIGN' => 'Campagne :',
    'LBL_NEXT_STEP' => 'Prochaine étape:',
    'LBL_LEAD_SOURCE' => 'Origine Principale:',
    'LBL_SALES_STAGE' => 'Phase de vente:',
    'LBL_SALES_STATUS' => 'Statut',
    'LBL_PROBABILITY' => 'Probabilité (%):',
    'LBL_DESCRIPTION' => 'Description:',
    'LBL_DUPLICATE' => 'Possibilité de duplication des Affaires',
    'MSG_DUPLICATE' => 'Possibilité de doublon avec une Affaire existante. La liste ci-dessous reprend les Affaires similaires. Vous pouvez soit en sélectionner une dans la liste ci-dessous, soit cliquer sur "Sauvegarder" pour poursuivre la création, soit cliquer sur "Annuler".',
    'LBL_NEW_FORM_TITLE' => 'Créer une Affaire',
    'LNK_NEW_OPPORTUNITY' => 'Créer une Affaire',
    'LNK_CREATE' => 'Créer Affaire',
    'LNK_OPPORTUNITY_LIST' => 'Affaires',
    'ERR_DELETE_RECORD' => 'Un ID doit être sélectionné pour toute suppression.',
    'LBL_TOP_OPPORTUNITIES' => 'Top des Affaires',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Êtes-vous sûr de vouloir supprimer ce contact pour cette Affaire ?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Êtes-vous sûr de vouloir supprimer cette Affaire de ce Projet ?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Affaires',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activités à Réaliser',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historique et Activités terminées',
    'LBL_RAW_AMOUNT' => 'Montant Brut',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacts',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documents',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projets',
    'LBL_ASSIGNED_TO_NAME' => 'Assigné à',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assigné à',
    'LBL_LIST_SALES_STAGE' => 'Phase de vente',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Mes Affaires clôturées',
    'LBL_TOTAL_OPPORTUNITIES' => 'Total des Affaires',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Affaires cloturées et gagnées',
    'LBL_ASSIGNED_TO_ID' => 'Assigné à (ID)',
    'LBL_CREATED_ID' => 'Créé par (ID)',
    'LBL_MODIFIED_ID' => 'Modifié par (ID)',
    'LBL_MODIFIED_NAME' => 'Modifié par',
    'LBL_CREATED_USER' => 'Créé par',
    'LBL_MODIFIED_USER' => 'Modifié par',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Campagnes',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projets',
    'LABEL_PANEL_ASSIGNMENT' => 'Assignation',
    'LNK_IMPORT_OPPORTUNITIES' => 'Import Affaires',
    'LBL_EDITLAYOUT' => 'Éditer la mise en page' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Campagne (ID)',
    'LBL_OPPORTUNITY_TYPE' => 'Type',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Assigné à',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Assigné à (ID)',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modifié par (ID)',
    'LBL_EXPORT_CREATED_BY' => 'Créé par (ID)',
    'LBL_EXPORT_NAME' => 'Nom',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Historique des emails des contacts liés',
    'LBL_FILENAME' => 'Pièce jointe',
    'LBL_PRIMARY_QUOTE_ID' => 'Devis principal',
    'LBL_CONTRACTS' => 'Contrats',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Contrats',
    'LBL_PRODUCTS' => 'Produits',
    'LBL_RLI' => 'Lignes de revenu',
    'LNK_OPPORTUNITY_REPORTS' => 'Rapports sur les Affaires',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Devis',
    'LBL_TEAM_ID' => 'Équipe (ID)',
    'LBL_TIMEPERIODS' => 'Périodes',
    'LBL_TIMEPERIOD_ID' => 'Période (ID)',
    'LBL_COMMITTED' => 'Soumis',
    'LBL_FORECAST' => 'Incluse dans Prévision',
    'LBL_COMMIT_STAGE' => 'Étape de soumission',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prévision',
    'LBL_WORKSHEET' => 'Feuille de travail',

    'TPL_RLI_CREATE' => 'Une affaire doit avoir au minimum une ligne de revenu.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Créer une Ligne de revenu',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Lignes de devis',
    'LBL_RLI_SUBPANEL_TITLE' => 'Lignes de revenu',

    'LBL_TOTAL_RLIS' => '# du total des lignes de revenu',
    'LBL_CLOSED_RLIS' => '# de lignes de revenu gagnées',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Vous ne pouvez pas supprimer une Affaire qui contient des lignes de revenu terminées',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Un ou plusieurs enregistrements sélectionnés contient des lignes de revenu terminées et ne peuvent donc pas être supprimés.',
    'LBL_INCLUDED_RLIS' => '# éléments de ligne de revenu inclus',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Devis',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Hiérarchie Affaire',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Définit la date de clôture sur l&#39;Affaire, par rapport à la date la plus proche ou la plus lointaine des dates sur les Lignes de revenu associées',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Montant du portefeuille',

    'LBL_OPPORTUNITY_ROLE'=>'Rôle pour cette Affaire',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'En cliquant sur Confirmer, vous effacerez TOUTES les données des Prévisions et modifierez la vue des Affaires. Si vous ne souhaitez pas faire cela veuillez cliquer sur Annuler.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'En cliquant sur Confirmer, vous effacerez TOUTES les données des Prévisions et modifierez la vue des Affaires. '
        .'TOUTES les définitions des processus avec un module cible de Lignes de revenu seront également désactivées. '
        .'Si ce n’est pas ce que vous souhaitiez, cliquez sur Annuler pour revenir aux paramètres précédents.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Si toutes les Lignes de revenu sont closes et qu&#39;au moins une a été gagnée,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'la phase de vente de l&#39;Affaire est définie à "Gagné"',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Si toutes les Lignes de revenu sont dans la phase de vente "Perdu",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'la phase de vente de l&#39;Affaire est définie à "Perdu"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Si au moins une Ligne de revenu est toujours ouverte,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'la phase de vente de l&#39;Affaire sera définie à la phase la moins avancée.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Une fois que vous avez lancé cette modification, les notes de résumé des lignes de revenu seront constituées en tâche de fond. Lorsque les notes sont complètes et disponibles, une notification sera envoyée à l&#39;adresse de votre profil. Si votre instance est configurée pour les {{forecasts_module}}, Sugar enverra également une notification lorsque vos enregistrements de {{module_name}} seront synchronisés avec le module {{forecasts_module}} et disponibles pour de nouvelles {{forecasts_module}}. Veuillez noter que votre instance doit être configurée pour envoyer des emails via Admin > Configuration emails afin que les notifications soient envoyées.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Une fois que vous avez lancé cette modification, des enregistrements de ligne de revenu seront créés pour chaque module {{module_name}} existant en tâche de fond. Lorsque les lignes de revenu seront complètes et disponibles, une notification sera envoyée à l&#39;adresse email de votre profil utilisateur. Veuillez noter que votre instance doit être configurée pour envoyer un email via Admin > Configuration email pour que la notification soit envoyée.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Le module {{plural_module_name}} vous permet de suivre des ventes individuelles du début jusqu&#39;à la fin. Chaque enregistrement de {{module_name}} représente une vente potentielle et inclut des données de vente pertinentes ainsi que des données liées à d&#39;autres enregistrements importants tels que {{quotes_module}}, {{contacts_module}}, etc. Un {{module_name}} progressera typiquement à travers plusieurs phases de vente jusqu&#39;à être marqué « Gagné » ou « Perdu ». {{plural_module_name}} peut être exploité encore d&#39;avantage en utilisant le module de {{forecasts_singular_module}} de Sugar pour comprendre et prédire des tendances de vente ainsi que pour axer le travail sur l&#39;atteinte de quotas de vente.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Le module {{plural_module_name}} vous permet de suivre des ventes individuelles et les articles associés à celles-ci depuis le début jusqu&#39;à la fin. Chaque enregistrement {{module_name}} représente une vente potentielle et inclut des données de vente pertinentes ainsi que des données liées à d&#39;autres enregistrements importants tels que {{quotes_module}}, {{contacts_module}}, etc. 

- Éditez les champs de cet enregistrement en cliquant sur un champ individuel ou sur le bouton Éditer.
- Visualisez ou modifiez des liens à d&#39;autres enregistrements dans les sous-panneaux en changeant le panneau en bas à gauche à « Vue des données ».
- Créez et visualisez des commentaires d&#39;utilisateur et enregistrez l&#39;historique de changements dans le {{activitystream_singular_module}} en changeant le panneau en bas à gauche à « Flux d&#39;activité ».
- Suivez ou ajoutez à favoris cet enregistrement à l&#39;aide des icônes à la droite du nom de l&#39;enregistrement.
- Des actions supplémentaires sont disponibles dans le menu déroulant d&#39;Actions à la droite du bouton Éditer.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Le module {{plural_module_name}} vous permet de suivre des ventes individuelles et les articles associés à celles-ci depuis le début jusqu&#39;à la fin. Chaque enregistrement {{module_name}} représente une vente potentielle et inclut des données de vente pertinentes ainsi que des données liées à d&#39;autres enregistrements importants tels que {{quotes_module}}, {{contacts_module}}, etc. 

Pour créer un {{module_name}} :
1. Remplissez les champs souhaités.
- Les champs marqués « Obligatoire » doivent être remplis avant la sauvegarde.
- Cliquez sur « Voir plus » pour afficher des champs supplémentaires, si nécessaire.
2. Cliquez sur « Sauvegarder » pour finaliser le nouvel enregistrement et retourner à la page précédente.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Synchronisé avec Marketo®',
    'LBL_MKTO_ID' => 'ID du Lead dans Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Top 10 des Affaires',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Afficher le top 10 des Affaires dans un graphique à bulles.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Mes Affaires',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Les Affaires de mon équipe",
);
