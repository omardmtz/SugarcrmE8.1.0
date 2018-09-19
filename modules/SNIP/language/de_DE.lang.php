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
    'LBL_MODULE_NAME' => 'E-Mail-Archivierung',
    'LBL_SNIP_SUMMARY' => "Die E-Mail-Archivierung ist ein automatischer Importdienst für das Importieren von E-Mails nach Sugar durch das Senden von einem beliebigen E-Mail-Programm oder -Service an eine von Sugar angegebene E-Mail-Adresse. Jede Sugar-Instanz hat eine eigene E-Mail-Adresse. Ein Benutzer muss einfach nur die entsprechende Adresse in die Felder TO, CC, BCC eingeben. Alle E-Mails werden zusammen mit Anhängen, Bildern und Kalendereinträgen importiert, und es werden in der Anwendung Datensätze erstellt, die mit bestehenden Datensätzen verbunden sind, je nach der übereinstimmenden E-Mail-Adresse.
<br><br>Beispiel: Wenn man als Benutzer ein Konto betrachtet, sieht man alle mit diesem Konto je nach der im Datensatz für dieses Konto angegebenen E-Mail-Adresse verbundenen E-Mails. Außerdem werden dem Benutzer alle E-Mails angezeigt, die mit Kontakten verbunden sind, die wiederum mit dem Konto verknüpft sind.
<br><br>Akzeptieren Sie die Nutzerungsbedingungen und klicken Sie auf \"Aktivieren\", um den Dienst zu verwenden. Sie können diesen jederzeit deaktivieren. Daraufhin wird die E-Mail-Adresse angezeigt, die für den Dienst verwendet werden muss.
<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Zugriff auf E-Mail-Archivierungsdienst fehlgeschlagen: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'E-Mail-Archivierung',
    'LBL_DISABLE_SNIP' => 'Deaktivieren',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Eindeutiger Schlüssel für Anwendung',
    'LBL_SNIP_USER' => 'E-Mail-Archivierung Benutzer',
    'LBL_SNIP_PWD' => 'E-Mail-Archivierung Kennwort',
    'LBL_SNIP_SUGAR_URL' => 'URL dieser Sugar Instanz',
	'LBL_SNIP_CALLBACK_URL' => 'E-Mail-Archivierungsdienst-URL',
    'LBL_SNIP_USER_DESC' => 'E-Mail-Archivierung Benutzer',
    'LBL_SNIP_KEY_DESC' => 'OAuth-Schlüssel für E-Mail-Archivierung. Wird für den Zugriff auf diese Instanz verwendet, um E-Mails zu importieren.',
    'LBL_SNIP_STATUS_OK' => 'Aktiviert',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Diese Sugar-Instanz ist mit dem E-Mal-Archivierungsdienst verbunden.',
    'LBL_SNIP_STATUS_ERROR' => 'Fehler:',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Diese Instanz hat eine gültige E-Mail-Archivierungsserverlizenz, aber der Server hat die folgende Fehlermeldung zurückgegeben:',
    'LBL_SNIP_STATUS_FAIL' => 'Registrierung beim E-Mail-Archiverungsserver fehlgeschlagen',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Der E-Mail-Archivierungsdienst ist momentan nicht verfügbar. Entweder ist der Dienst ausgefallen oder die Verbindung zur Sugar-Instanz ist fehlgeschlagen.',
    'LBL_SNIP_GENERIC_ERROR' => 'Der E-Mail-Archivierungsdienst ist momentan nicht verfügbar. Entweder ist der Dienst ausgefallen oder die Verbindung zur Sugar-Instanz ist fehlgeschlagen.',

	'LBL_SNIP_STATUS_RESET' => 'Noch nicht gelaufen',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problem: %s',
    'LBL_SNIP_NEVER' => "Nie",
    'LBL_SNIP_STATUS_SUMMARY' => "Status E-Mail-Archivierungsdienst:",
    'LBL_SNIP_ACCOUNT' => "Firma",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Zuletzt erfolgreich ausgeführt",
    "LBL_SNIP_DESCRIPTION" => "Der E-Mail-Archivierungsdienst ist ein automatisches E-Mail-Archivierungssystem",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Sie können damit an oder von Kontakten in SugarCRM gesendete E-Mails anzeigen, ohne diese manuell importieren und verlinken zu müssen",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Um den E-Mail-Archivierungsdienst zu verwenden, muss eine Lizenz für Ihr System erworben werden",
    "LBL_SNIP_PURCHASE" => "Bitte hier kaufen",
    'LBL_SNIP_EMAIL' => 'E-Mail-Archivierungsadresse',
    'LBL_SNIP_AGREE' => "Ich bin mit der folgenden Bedingungen und der <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>Datenschutzvereinbarung</a> einverstanden.",
    'LBL_SNIP_PRIVACY' => 'Datenschutzvereinbarung',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback fehlgeschlagen',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Der E-Mail Archiverungsserver kann keine Verbindung mit Ihrer Sugar-Instanz herstellen. Bitte später versuchen oder den <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">Sugar-Support kontaktieren</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'E-Mail-Archivierung aktivieren',
    'LBL_SNIP_BUTTON_DISABLE' => 'E-Mail-Archivierung deaktivieren',
    'LBL_SNIP_BUTTON_RETRY' => 'Erneut verbinden',
    'LBL_SNIP_ERROR_DISABLING' => 'Ein Fehler ist aufgetreten bei der Verbindung mit dem E-Mail-Archivierungsserver und der Dienst kann nicht deaktiviert werden',
    'LBL_SNIP_ERROR_ENABLING' => 'Ein Fehler ist aufgetreten bei der Verbindung mit dem E-Mail-Archivierungsserver und der Dienst kann nicht aktiviert werden',
    'LBL_CONTACT_SUPPORT' => 'Bitte später erneut versuchen oder den Sugar-Support kontaktieren.',
    'LBL_SNIP_SUPPORT' => 'Bitte kontaktieren Sie den Sugar-Support.',
    'ERROR_BAD_RESULT' => 'Fehlermeldung vom Dienst erhalten',
	'ERROR_NO_CURL' => 'cURL-Erweiterung ist notwendig, ist aber nicht aktiviert',
	'ERROR_REQUEST_FAILED' => 'Der Server kann nicht erreicht werden',

    'LBL_CANCEL_BUTTON_TITLE' => 'Abbrechen',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Hier wird der Status der E-Mail-Archivierungsdienstes auf diesem System angezeigt. Der Status zeigt, ob diesen Dienst gerade aktiv ist oder nicht.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Dies ist die E-Mail-Archivierungsadresse für das Importieren von E-Mails in Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Dies ist die URL des E-Mail-Archivierungsservers. Alle Anfragen, z. B. um den Dienst zu aktivieren oder zu deaktivieren, werden über diese URL gesendet.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Dies ist die Webdienst-URL Ihrer Sugar-Instanz. Der E-Mail-Archivierungsserver wird mit dieser URL verbunden.',
);
