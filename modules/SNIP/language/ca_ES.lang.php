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
    'LBL_MODULE_NAME' => 'Arxivament del correu electrònic',
    'LBL_SNIP_SUMMARY' => "Email Archiving is an automatic importing service that allows users to import emails into Sugar by sending them from any mail client or service to a Sugar-provided email address. Each Sugar instance has its own unique email address. To import emails, a user sends to the provided email address using the TO, CC, BCC fields. The Email Archiving service will import the email into the Sugar instance. The service imports the email, along with any attachments, images and Calendar events, and creates records within the application that are associated with existing records based on matching email addresses. Email Archiving és un servei automàtic d&#39;importació que permet als usuaris importar correus electrònics en Sugar mitjançant l&#39;enviament de qualsevol client de correu o servei a un Sugar proporcionat per la direcció de correu electrònic. Cada instància de Sugar té la seva pròpia adreça de correu electrònic única. Per a importar missatges de correu electrònic, un usuari envia a l&#39;adreça de correu electrònic proporcionada amb l&#39;TO, CC, BCC. El servei d&#39;Email Archivingva a importar el correu electrònic en el cas del Sugar. El servei de les importacions del correu electrònic, juntament amb els arxius adjunts, imatges i cites del calendari, i crea els registres dins de l&#39;aplicació que s&#39;associen amb els registres existents basats en la coincidència d&#39;adreces de correu electrònic.<br><br>Exemple: Com a usuari, quan veig un compte, vaig a ser capaç de veure tots els correus electrònics que estan associats amb el compte basada en l&#39;adreça de correu electrònic al registre de compte. També vaig a ser capaç de veure els correus electrònics que estan associats amb els contactes relacionats amb el compte.<br><br>Acceptar els termes a continuació i feu clic a Activa per començar a utilitzar el servei. Vostè serà capaç de desactivar el servei en qualsevol moment. Una vegada que el servei està habilitat, l&#39;adreça de correu electrònic que s&#39;utilitzarà per al servei a la pantalla.<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'No s&#39;ha pogut contactar amb el servei Email Archiving: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Arxivament del correu electrònic',
    'LBL_DISABLE_SNIP' => 'Deshabilitar',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Clau única d&#39;aplicació',
    'LBL_SNIP_USER' => 'Usuari Email Archiving',
    'LBL_SNIP_PWD' => 'Contrassenya d&#39;arxivament de correu electrònic',
    'LBL_SNIP_SUGAR_URL' => 'La URL de la instancia de Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'Email Archiving URL de servei',
    'LBL_SNIP_USER_DESC' => 'Usuari Email Archiving',
    'LBL_SNIP_KEY_DESC' => 'Email Archiving clau OAuth. S&#39;utilitza per accedir a aquesta instància per a la importació de missatges de correu electrònic.',
    'LBL_SNIP_STATUS_OK' => 'Habilitat',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Aquesta instància de Sugar s&#39;ha connectat al servidor d&#39;Email Archiving.',
    'LBL_SNIP_STATUS_ERROR' => 'Error',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Aquesta instància té una llicència d&#39;Email Archiving vàlida, però el servidor va tornar el següent missatge d&#39;error:',
    'LBL_SNIP_STATUS_FAIL' => 'No es pot registrar amb el servidor Email Archiving',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'El servei Email Archiving no està disponible actualment. O bé el servei no està funcionant o la connexió amb Sugar falla.',
    'LBL_SNIP_GENERIC_ERROR' => 'El servei Email Archiving no està disponible actualment. O bé el servei no està funcionant o la connexió amb Sugar falla.',

	'LBL_SNIP_STATUS_RESET' => 'No s&#39;executen',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problema: %s',
    'LBL_SNIP_NEVER' => "Mai",
    'LBL_SNIP_STATUS_SUMMARY' => "Email Archiving estat del servei:",
    'LBL_SNIP_ACCOUNT' => "Compte",
    'LBL_SNIP_STATUS' => "Estat",
    'LBL_SNIP_LAST_SUCCESS' => "Darrera connexió correcta",
    "LBL_SNIP_DESCRIPTION" => "El servei d&#39;Email Archiving és un sistema d&#39;arxivament automàtic de correu electrònic",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Li permet veure correus electrònics que van ser enviats o rebuts des dels seus contactes dins de SugarCRM, sense haver d&#39;importar manualment i vincular els correus electrònics",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Per utilitzar arxiu d&#39;Email Archiving, vostè ha de comprar una llicència per a la instància de SugarCRM",
    "LBL_SNIP_PURCHASE" => "Feu clic aquí per comprar",
    'LBL_SNIP_EMAIL' => 'Direcció d&#39;Email Archiving',
    'LBL_SNIP_AGREE' => "Estic d&#39;acord en els termes anteriors i l&#39; <a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">acord de privacitat</a>.",
    'LBL_SNIP_PRIVACY' => 'acord de privacitat',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback a fracassat',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'L&#39;Email Archiving servidor no és capaç d&#39;establir una connexió amb la instància de Sugar. Torneu-ho de nou o poseu-vos en <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">contacte amb atenció al client</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Activa Email Archiving',
    'LBL_SNIP_BUTTON_DISABLE' => 'Desactiva Email Archiving',
    'LBL_SNIP_BUTTON_RETRY' => 'Proveu connectar-se de nou',
    'LBL_SNIP_ERROR_DISABLING' => 'S&#39;ha produït un error en intentar comunicar-se amb el servidor d&#39;arxiu d&#39;Email Archiving, i el servei no pot ser desactivat',
    'LBL_SNIP_ERROR_ENABLING' => 'S&#39;ha produït un error en intentar comunicar-se amb el servidor d&#39;arxiu d&#39;Email Archiving, i el servei no pot ser activat',
    'LBL_CONTACT_SUPPORT' => 'Torneu-ho de nou o poseu-vos en contacte amb suport de SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Si us plau, poseu-vos en contacte amb suport de SugarCRM per obtenir ajuda.',
    'ERROR_BAD_RESULT' => 'Mal resultat retornat pel servei',
	'ERROR_NO_CURL' => 'Extensió cURL és necessària, però no ha estat activat',
	'ERROR_REQUEST_FAILED' => 'No s&#39;ha pogut contactar amb el servidor',

    'LBL_CANCEL_BUTTON_TITLE' => 'Cancel·la',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Aquest és l&#39;estat del servei d&#39;Email Archiving en la instància. L&#39;estat reflecteix que la connexió entre el servidor d Email Archiving i la instància de Sugar és èxitossa.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Aquest és l&#39;arxiu d&#39;Email Archiving de correu electrònic per enviar a fi d&#39;importar els correus electrònics en Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Aquesta és la URL del servidor d&#39;Email Archiving. Totes les sol·licituds, com ara habilitar i deshabilitar el servei d&#39;Email Archiving, serà retransmès a través d&#39;aquesta URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Aquesta és la URL de serveis web de la instància de Sugar. L&#39;Email Archiving del servidor es connectarà al seu servidor a través d&#39;aquesta URL.',
);
