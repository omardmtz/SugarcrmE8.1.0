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
    'LBL_MODULE_NAME' => 'E-mail Archivering',
    'LBL_SNIP_SUMMARY' => "E-mail Archivering is een automatische import voorziening die gebruikers in staat stelt om e0mail in Sugar te importeren door ze vanaf een willekeurige e-mail client naar een Sugar e-mailadres te versturen. Elke Sugar instantie heeft een eigen uniek e-mailadres. Om e-mails te importeren, verstuurt een gebruiker e-mails naar het opgegeven e-mailadres door gebruik te maken van het &#39;Aan&#39;, &#39;CC&#39; en/of &#39;BCC&#39; velden. De E-mail Archiveringsdienst zal de e-mail importeren in de Sugar instantie. De e-mail wordt incl. bijlagen, afbeeldingen en kalenderitems geïmporteerd en maakt nieuwe records aan in de applicatie die gekoppeld zijn met bestaande records gebaseerd op de e-mailadressen.<br /><br><br /><br><br />Voorbeeld: Als gebruiker, als ik een Organisatie bekijk, zal ik alle e-mail die horen bij die Organisatie gebaseerd op het bijbehorende e-mailadres van de Organisatie. Ik zal tevens e-mails zien die horen bij de (Contact)Personen van de Organisatie.<br /><br><br /><br><br />Accepteer de voorwaarden hieronder en klik op &#39;Instellen&#39; om de dienst te gebruiken. U kunt de dienst op elk gewenst tijdstip stopzetten en activeren. Als de dienst geactiveerd is, zal het e-mailadres voor het archiveren getoond worden.<br /><br><br /><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Verbinding met E-mail archiveringsdienst mislukt: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'E-mail Archivering',
    'LBL_DISABLE_SNIP' => 'Uitschakelen',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Unieke sleutel toepassing',
    'LBL_SNIP_USER' => 'E-mail Archiveringsgebruiker',
    'LBL_SNIP_PWD' => 'E-mail Archiveringswachtwoord',
    'LBL_SNIP_SUGAR_URL' => 'URL van deze Sugar instantie',
	'LBL_SNIP_CALLBACK_URL' => 'URL van de E-mail Archiveringsdienst',
    'LBL_SNIP_USER_DESC' => 'E-mail Archiveringsgebruiker',
    'LBL_SNIP_KEY_DESC' => 'E-mail Archivering OAuth key. Gebruikt voor toegang naar de instantie om e-mails te importeren.',
    'LBL_SNIP_STATUS_OK' => 'Ingeschakeld',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Deze Sugar instantie is succesvol verbonden met de E-mail Archiveringsserver.',
    'LBL_SNIP_STATUS_ERROR' => 'Fout',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Deze instantie heeft een geldige E-mail Archiveringslicentie, maar de server heeft de volgened foutmelding doorgegeven:',
    'LBL_SNIP_STATUS_FAIL' => 'Registratie bij de E-mail Archiveringsserver kon niet worden voltooid',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'De e-mailarchiveringsdienst is nu niet beschikbaar. De dienst is offline of de verbinding kon niet met deze Sugar instantie tot stand worden gebracht.',
    'LBL_SNIP_GENERIC_ERROR' => 'De e-mailarchiveringsdienst is nu niet beschikbaar. De dienst is offline of de verbinding kon niet met deze Sugar instantie tot stand worden gebracht.',

	'LBL_SNIP_STATUS_RESET' => 'Nog niet uitgevoerd',
	'LBL_SNIP_STATUS_PROBLEM' => 'Probleem: %s',
    'LBL_SNIP_NEVER' => "Nooit",
    'LBL_SNIP_STATUS_SUMMARY' => "Status van E-mail Archiveringsdienst:",
    'LBL_SNIP_ACCOUNT' => "Account",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Laatste keer voltooid",
    "LBL_SNIP_DESCRIPTION" => "E-mail Archiveringsdienst is een automatisch e-mailarchiveringssysteem.",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Het stelt u in staat om verstuurde e-mail naar of van uw contacten binnen SugarCRM in te zien, zonder deze handmatig te importeren en te koppelen.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Om de E-mail Archivering te gebruiken, dient u een licentie aan te schaffen voor uw SugarCRM instantie.",
    "LBL_SNIP_PURCHASE" => "Klik hier om aan te schaffen",
    'LBL_SNIP_EMAIL' => 'E-mail Archiveringsadres',
    'LBL_SNIP_AGREE' => "I agree to the above terms and the <a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">privacy agreement</a>.",
    'LBL_SNIP_PRIVACY' => 'privacy-overeenkomst',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback mislukt',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'De E-mail Archiveringsserver kan geen verbinding met uw Sugar instantie tot stand brengen. Probeer opnieuw of <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">neem contact op met customer support</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Schakel E-mail Archivering <b>in</b>',
    'LBL_SNIP_BUTTON_DISABLE' => 'Schakel E-mail Archivering <b>uit</b>',
    'LBL_SNIP_BUTTON_RETRY' => 'Probeer opnieuw verbinding te maken',
    'LBL_SNIP_ERROR_DISABLING' => 'Er is een fout opgetreden tijdens het maken van een verbinding met de E-mail Archiveringsserver en de dienst kon niet worden uitgeschakeld.',
    'LBL_SNIP_ERROR_ENABLING' => 'Er is een fout opgetreden tijdens het maken van een verbinding met de E-mail Archiveringsserver en de dienst kon niet worden ingeschakeld.',
    'LBL_CONTACT_SUPPORT' => 'Probeer opnieuw of neem contact op met SugarCRM Support.',
    'LBL_SNIP_SUPPORT' => 'Neem contact op met SugarCRM Support voor assistentie.',
    'ERROR_BAD_RESULT' => 'Slecht resultaat teruggekregen van de service',
	'ERROR_NO_CURL' => 'cURL extensies zijn verplicht maar zijn niet ingeschakeld',
	'ERROR_REQUEST_FAILED' => 'Kon geen contact maken met de server',

    'LBL_CANCEL_BUTTON_TITLE' => 'Annuleren',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Dit is de status van de E-mail Archiveringsdienst voor uw instantie. De status geeft weer of de verbinding tussen de E-mail Archiveringsdienst en uw instantie tot stand is gebracht.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Dit is het E-mail Archiveringsadres dat gebruikt moet worden voor het importeren van e-mails in uw instantie van SugarCRM.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Dit is de URL van de E-mail Archiveringsserver. Alle verzoeken, zoals het in-/uitschakelen van het archiveren, zullen via deze URL doorgegeven worden.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Dit is het webservices URL van uw Sugar instantie. De E-mail Archiveringsserver zal verbinding maken via deze URL.',
);
