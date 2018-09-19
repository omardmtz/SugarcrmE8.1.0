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
    'LBL_MODULE_NAME' => 'Archiviazione Email',
    'LBL_SNIP_SUMMARY' => "L´archiviazione email è un servizio di importazione automatica che consente agli utenti di archiviare emails da un qualsiasi client o servizio di posta ad un indirizzo Sugar fornito. Ogni istanza Sugar ha il proprio indirizzo email. Per importare le email, l´utente invia all´inidirizzo email fornito utilizzando i campi A, CC, BCC. Il servizio di archiviazione email importerà l´email nell´instanza Sugar.  Esso importa email, ed eventuali allegati, immagini e attività del Calendario, e crea records nell´applicazione che sono associati a quelli già esistenti sulla base di indirizzi email corrispondenti.<br /><br />Esempio: un utente, quando visualizza un´azienda, potrà vedere tutte le email associate all´azienda sulla base dell´indirizzo email dell´azienda. Potrà vedere anche le email associate ai contatti relazionati all´azienda. <br /><br />Accetta i termini indicati di seguito e clicca Attiva per iniziare ad utilizzare il servizio. Sarà possibile disattivarlo in qualsiasi momento. Una volta attivato, verrò visualizzato l´indirizzo email utilizzato per l´importazione.",
	'LBL_REGISTER_SNIP_FAIL' => 'Impossibile contattare il servizio di archiviazione email: %s!',
	'LBL_CONFIGURE_SNIP' => 'Archiviazione Email',
    'LBL_DISABLE_SNIP' => 'Disabilitare',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Chiave univoca di applicazione',
    'LBL_SNIP_USER' => 'Utente per l´archiviazione email',
    'LBL_SNIP_PWD' => 'Password per l´archiviazione email',
    'LBL_SNIP_SUGAR_URL' => 'URL istanza Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL del servizio archiviazione email',
    'LBL_SNIP_USER_DESC' => 'Utente per l´archiviazione email',
    'LBL_SNIP_KEY_DESC' => 'Chiave OAuth di archiviazione email. Utilizzata per accedere a questa istanza per importare emails.',
    'LBL_SNIP_STATUS_OK' => 'Attivato',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Questa istanza di Sugar è stata collegata con successo al server per l´archiviazione delle email.',
    'LBL_SNIP_STATUS_ERROR' => 'Errore',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Questa istanza ha una licenza server per l´archiviazione delle email valida, ma il server restituisce il seguente messaggio di errore:',
    'LBL_SNIP_STATUS_FAIL' => 'Non è possibile registrarsi con il server per l´archiviazione email',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Il servizio per l´archiviazione delle email non è attualmente disponibile. O il servizio è giù o la connessione a questa istanza di Sugar è fallita.',
    'LBL_SNIP_GENERIC_ERROR' => 'Il servizio per l´archiviazione delle email non è attualmente disponibile. O il servizio è giù o la connessione a questa istanza di Sugar è fallita.',

	'LBL_SNIP_STATUS_RESET' => 'Non ancora eseguito',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problema: %s',
    'LBL_SNIP_NEVER' => "Mai",
    'LBL_SNIP_STATUS_SUMMARY' => "Stato del servizio per l´archiviazione delle email:",
    'LBL_SNIP_ACCOUNT' => "Azienda",
    'LBL_SNIP_STATUS' => "Stato",
    'LBL_SNIP_LAST_SUCCESS' => "Ultima esecuzione corretta",
    "LBL_SNIP_DESCRIPTION" => "Il servizio per l´archiviazione delle email è un sistema di archiviazione email automatico",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Esso ti permette di vedere le email che sono state inviate a o da i contatti dentro SugarCRM, senza che tu debba manualmente importare e collegare le email",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Per poter usare il servizio di Archiviazione Email, devi acquistare una licenza per la tua istanza di SugarCRM",
    "LBL_SNIP_PURCHASE" => "Clicca qui per acquistare",
    'LBL_SNIP_EMAIL' => 'Indirizzo per l´archiviazione email',
    'LBL_SNIP_AGREE' => "Accetto i termini sopra indicati e <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>l´accordo di riservatezza</a>.",
    'LBL_SNIP_PRIVACY' => 'Contratto di riservatezza',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback fallito',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Il server per l´archiviazione email non è in grado di stabilire una connessione con la tua istanza Sugar. Si prega di riprovare o <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">contatta l´assistenza clienti </a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Attiva Archiviazione email',
    'LBL_SNIP_BUTTON_DISABLE' => 'Disattiva Archiviazione email',
    'LBL_SNIP_BUTTON_RETRY' => 'Riprova connessione',
    'LBL_SNIP_ERROR_DISABLING' => 'Si è verificato un errore nel tentativo di comunicare con il server per l´archiviazione email, e il servizio non può disabilitato',
    'LBL_SNIP_ERROR_ENABLING' => 'Si è verificato un errore nel tentativo di comunicare con il server per l´archiviazione email, e il servizio non può disabilitato',
    'LBL_CONTACT_SUPPORT' => 'Si prega di riprovare o contattare il supporto della SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Si prega di contattare il supporto SugarCRM per assistenza',
    'ERROR_BAD_RESULT' => 'Restituiti dal servizio risultati negativi',
	'ERROR_NO_CURL' => 'Estensione cURL necessaria, ma non abilitata',
	'ERROR_REQUEST_FAILED' => 'Impossibile contattare il server',

    'LBL_CANCEL_BUTTON_TITLE' => 'Annulla',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Questo è lo stato del servizio per l´archiviazione email nella tua istanza. Lo stato mostra se la connessione tra il server per l´archiviazione email e la tua istanza Sugar è corretta.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Questo è l´indirizzo a cui inviare per l´archiviazione email in Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Questo è l´URL del server per l´archiviazione email. Tutte le richieste, come l´attivazione o la disattivazione del servizio, saranno inoltrate tramite questo URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Questo l´URL webservices della tua istanza Sugar. Il server per l´archiviazione email si connette al tuo server mediante questo URL.',
);
