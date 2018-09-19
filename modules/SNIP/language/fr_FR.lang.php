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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Archivageautomatique d&#39;emails',
    'LBL_SNIP_SUMMARY' => "L'archivage automatique d'emails est un service d'import automatique des emails qui permet aux utilisateurs d'importer des emails dans Sugar simplement en les envoyant depuis n'importe quel client ou service de messagerie vers une adresse email gérée par Sugar. Chaque instance Sugar possède une et une seule boite mail d'import automatique. Pour importer des emails, les utilisateurs doivent envoyer un email à cette adresse d'import automatique en utilisant l'un des champs A, CC ou CCI. Le service d'import automatique importera automatiquement cet email avec toute pièce jointe dans l'instance Sugar. Le service importe l'email avec ses pièces jointes, ses images, les évènements de type Calendrier dans SugarCRM en le liant aux enregistrements ayant comme email l'un des destinataire de l'email original.<br><br>Exemple :  En tant qu'utilisateur, quand je visionne un compte, je vais être capable d'afficher l'historique de tous les emails qui sont associés a ce compte en se basant sur l'adresse email renseignée dans la fiche compte. Je vais aussi être capable d'afficher l'historique des emails qui sont associés à des contacts liés a ce compte.<br><br>Acceptez les termes ci-dessous et cliquez sur Activer pour commencer à utiliser le service. Vous serez en mesure de désactiver le service à tout moment. Une fois le service activé, l'adresse email à utiliser pour le service sera affichée.
<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Échec lors de la tentative d&#39;accès au service d&#39;Archivage automatique d&#39;emails : %s !<br>',
	'LBL_CONFIGURE_SNIP' => 'Archivageautomatique d&#39;emails',
    'LBL_DISABLE_SNIP' => 'Désactiver',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Clé unique de l&#39;application',
    'LBL_SNIP_USER' => 'Utilisateur Archivage automatique d&#39;emails',
    'LBL_SNIP_PWD' => 'Mot de passe d&#39;archivage automatique d&#39;emails',
    'LBL_SNIP_SUGAR_URL' => 'URL de l&#39;instance SugarCRM actuelle',
	'LBL_SNIP_CALLBACK_URL' => 'URL du service d&#39;archivage automatique d&#39;emails',
    'LBL_SNIP_USER_DESC' => 'Utilisateur d&#39;Archivage automatique d&#39;emails',
    'LBL_SNIP_KEY_DESC' => 'Clé OAuth pour l&#39;archivage d&#39;email. Utilisée pour accéder à cette instance dans le but d&#39;importer des emails.',
    'LBL_SNIP_STATUS_OK' => 'Activé',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Cette instance Sugar s&#39;est connectée avec succès au serveur d&#39;Archivage automatique d&#39;emails.',
    'LBL_SNIP_STATUS_ERROR' => 'Erreur',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Cette instance possède une licence Archivage automatique d&#39;emails valide, cependant le serveur a retourné le message d&#39;erreur suivant :',
    'LBL_SNIP_STATUS_FAIL' => 'Impossible de s&#39;enregistrer auprès du serveur Archivage automatique d&#39;emails',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Le service Archivage automatique d&#39;emails est actuellement inaccessible. Le service est peut-être actuellement arrêté ou la connexion réseau de cette instance Sugar est défectueuse.',
    'LBL_SNIP_GENERIC_ERROR' => 'Le service Archivage automatique d&#39;emails est actuellement inaccessible. Le service est peut-être actuellement arrêté ou la connexion réseau de cette instance Sugar est défectueuse.',

	'LBL_SNIP_STATUS_RESET' => 'Non démarré',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problème : %s',
    'LBL_SNIP_NEVER' => "Jamais",
    'LBL_SNIP_STATUS_SUMMARY' => "Statut de l'Archivage automatique d'emails :",
    'LBL_SNIP_ACCOUNT' => "Compte",
    'LBL_SNIP_STATUS' => "Statut",
    'LBL_SNIP_LAST_SUCCESS' => "Dernière exécution réussie",
    "LBL_SNIP_DESCRIPTION" => "Sugar Ease est un service d'archivage automatique des emails",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Il vous permet de voir les emails qui ont été envoyés vers ou à partir de vos contacts à l'intérieur de SugarCRM, sans avoir à importer manuellement ces emails et de les liées à vos données",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Afin d'utiliser Archivage automatique d'emails, vous devez acheter une licence pour votre instance SugarCRM",
    "LBL_SNIP_PURCHASE" => "Cliquez ici pour acheter",
    'LBL_SNIP_EMAIL' => 'Adresse pour l&#39;Archivage automatique d&#39;emails',
    'LBL_SNIP_AGREE' => "J'accepte les termes ci-dessus et l'<a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">accord de confidentialité</a>.",
    'LBL_SNIP_PRIVACY' => 'accord de confidentialité',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Échec du pingback',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Le serveur Archivage automatique d&#39;emails ne peut pas établir de connexion avec votre instance de Sugar. Veuillez réessayer ou <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">contacter le support client</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Activer Archivage automatique d&#39;emails',
    'LBL_SNIP_BUTTON_DISABLE' => 'Désactiver Archivage automatique d&#39;emails',
    'LBL_SNIP_BUTTON_RETRY' => 'Tenter une nouvelle connexion',
    'LBL_SNIP_ERROR_DISABLING' => 'Une erreur s&#39;est produite en essayant de communiquer avec le serveur Archivage automatique d&#39;emails, et le service n&#39;a pas été désactivé',
    'LBL_SNIP_ERROR_ENABLING' => 'Une erreur s&#39;est produite en essayant de communiquer avec le serveur Archivage automatique d&#39;emails, et le service n&#39;a pas été activé',
    'LBL_CONTACT_SUPPORT' => 'Veuillez réessayer ou contacter le support SugarCRM',
    'LBL_SNIP_SUPPORT' => 'Veuillez contacter le support SugarCRM pour obtenir de l&#39;assistance',
    'ERROR_BAD_RESULT' => 'Un mauvais résultat a été retourné par le service.',
	'ERROR_NO_CURL' => 'L&#39;extension PHP cURL est requise pour ce service mais elle n&#39;est apparemment pas disponible sur votre hébergement.',
	'ERROR_REQUEST_FAILED' => 'Impossible de contacter le serveur',

    'LBL_CANCEL_BUTTON_TITLE' => 'Annuler',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Ceci est le statut du service Archivage automatique d&#39;emails pour votre instance. Ce statut indique si la connexion est active entre le serveur Archivage automatique d&#39;emails et votre instance de Sugar.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Ceci est l&#39;adresse email dce l&#39;archivage automatique d&#39;emails a utiliser afin d&#39;importer vos emails dans SugarCRM.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Ceci est l&#39;URL du serveur Archivage automatique d&#39;emails. Toutes les opérations comme l&#39;activation ou la désactivation du service Archivage automatique d&#39;emails utilisent cette URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Ceci est l&#39;URL d&#39;accès aux Web Services de votre instance Sugar. Le serveur Archivage automatique d&#39;emails communique avec votre serveur au travers de cette URL.',
);
