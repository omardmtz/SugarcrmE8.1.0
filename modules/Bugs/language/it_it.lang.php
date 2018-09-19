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
  'LBL_BUGS_LIST_DASHBOARD' => 'Dashboard elenco bug',
  'LBL_BUGS_RECORD_DASHBOARD' => 'Dashboard record bug',

  'LBL_MODULE_NAME' => 'Bug',
  'LBL_MODULE_NAME_SINGULAR'	=> 'Bug',
  'LBL_MODULE_TITLE' => 'Tracker Bug: Home',
  'LBL_MODULE_ID' => 'Bug',
  'LBL_SEARCH_FORM_TITLE' => 'Cerca bug',
  'LBL_LIST_FORM_TITLE' => 'Elenco bug',
  'LBL_NEW_FORM_TITLE' => 'Nuovo bug',
  'LBL_CONTACT_BUG_TITLE' => 'Contatto-Bug:',
  'LBL_SUBJECT' => 'Oggetto:',
  'LBL_BUG' => 'Bug:',
  'LBL_BUG_NUMBER' => 'Numero Bug:',
  'LBL_NUMBER' => 'Numero:',
  'LBL_STATUS' => 'Stato:',
  'LBL_PRIORITY' => 'Priorità:',
  'LBL_DESCRIPTION' => 'Descrizione:',
  'LBL_CONTACT_NAME' => 'Nome Contatto:',
  'LBL_BUG_SUBJECT' => 'Oggetto Bug:',
  'LBL_CONTACT_ROLE' => 'Ruolo:',
  'LBL_LIST_NUMBER' => 'Numero.',
  'LBL_LIST_SUBJECT' => 'Oggetto',
  'LBL_LIST_STATUS' => 'Stato',
  'LBL_LIST_PRIORITY' => 'Priorità',
  'LBL_LIST_RELEASE' => 'Rilascia',
  'LBL_LIST_RESOLUTION' => 'Risoluzione',
  'LBL_LIST_LAST_MODIFIED' => 'Ultima modifica',
  'LBL_INVITEE' => 'Contatti',
  'LBL_TYPE' => 'Tipo:',
  'LBL_LIST_TYPE' => 'Tipo',
  'LBL_RESOLUTION' => 'Soluzione:',
  'LBL_RELEASE' => 'Rilascia:',
  'LNK_NEW_BUG' => 'Segnala bug',
  'LNK_CREATE'  => 'Segnala bug',
  'LNK_CREATE_WHEN_EMPTY'    => 'Segnala un bug adesso.',
  'LNK_BUG_LIST' => 'Bug',
  'LBL_SHOW_MORE' => 'Mostra Più Bugs',
  'NTC_REMOVE_INVITEE' => 'Sei sicuro di voler rimuovere questo contatto dal Bug ?',
  'NTC_REMOVE_ACCOUNT_CONFIRMATION' => 'Sei sicuro di voler rimuovere questo Bug dall´azienda ?',
  'ERR_DELETE_RECORD' => 'Per eliminare il bug deve essere specificato il numero del record.',
  'LBL_LIST_MY_BUGS' => 'I miei Bug',
  'LNK_IMPORT_BUGS' => 'Importa Bug',
  'LBL_FOUND_IN_RELEASE' => 'Trovato nella release:',
  'LBL_FIXED_IN_RELEASE' => 'Risolto nella release:',
  'LBL_LIST_FIXED_IN_RELEASE' => 'Risolto nella release',
  'LBL_WORK_LOG' => 'Registro Operazioni:',
  'LBL_SOURCE' => 'Fonte:',
  'LBL_PRODUCT_CATEGORY' => 'Categoria:',

  'LBL_CREATED_BY' => 'Creato da:',
  'LBL_DATE_CREATED' => 'Data creazione:',
  'LBL_MODIFIED_BY' => 'Ultima modifica fatta da:',
  'LBL_DATE_LAST_MODIFIED' => 'Data Modifica:',

  'LBL_LIST_EMAIL_ADDRESS' => 'Indirizzo Email',
  'LBL_LIST_CONTACT_NAME' => 'Nome contatto',
  'LBL_LIST_ACCOUNT_NAME' => 'Azienda',
  'LBL_LIST_PHONE' => 'Telefono',
  'NTC_DELETE_CONFIRMATION' => 'Sei sicuro di voler rimuovere questo contatto da questo Bug ?',

  'LBL_DEFAULT_SUBPANEL_TITLE' => 'Bug Tracker',
  'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Attività',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'Cronologia',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contatti',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Aziende',
  'LBL_CASES_SUBPANEL_TITLE' => 'Reclami',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Progetti',
  'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documenti',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Assegnato a:',
	'LBL_ASSIGNED_TO_NAME' => 'Assegnato a:',

	'LNK_BUG_REPORTS' => 'Visualizza report sui bug',
	'LBL_SHOW_IN_PORTAL' => 'Mostra nel portale',
	'LBL_BUG_INFORMATION' => 'Informazioni Bug',

    //For export labels
	'LBL_FOUND_IN_RELEASE_NAME' => 'Trovato nel nome release',
    'LBL_PORTAL_VIEWABLE' => 'Portale visibile',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nome Utente Assegnato',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID Utente Assegnato',
    'LBL_EXPORT_FIXED_IN_RELEASE_NAMR' => 'Risolto nel nome release',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificato da ID',
    'LBL_EXPORT_CREATED_BY' => 'Creato da ID',

    //Tour content
    'LBL_PORTAL_TOUR_RECORDS_INTRO' => 'Il modulo Bugs serve a visualizzare e segnalare bugs. Usa le frecce sotto per effettuare un tour veloce.',
    'LBL_PORTAL_TOUR_RECORDS_PAGE' => 'Questa pagina mostra la lista di Bugs esistenti pubblicati.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER' => 'Puoi filtrare la lista dei Bugs fornendo i termini di ricerca.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER_EXAMPLE' => 'Per esempio, potresti usarlo per trovare un Bug che è stato segnalato in precedenza.',
    'LBL_PORTAL_TOUR_RECORDS_CREATE' => 'Se hai trovato un nuovo Bug che vuoi segnalare, puoi cliccare qui per segnalare un nuovo Bug.',
    'LBL_PORTAL_TOUR_RECORDS_RETURN' => 'Cliccando quì verrai riportato sempre su questa vista.',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Note',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Il modulo {{plural_module_name}} è utilizzato per tenere traccia e gestire i problemi legati ai prodotti, comunemente definiti {{plural_module_name}} o difetti, che sono riscontrati sia internamente che dai clienti. I {{plural_module_name}} possono essere ulteriormente valutati monitorando l’anomalia riscontrata ed essere in seguito risolti nella release. Il modulo {{plural_module_name}} fornisce agli utenti un modo rapido per riesaminare tutti i dettagli di {{module_name}} e il processo da utilizzare per porvi rimedio. Dopo aver creato o inoltrato un {{module_name}}}}, è possibile visualizzare e modificare le informazioni pertinenti tramite la vista record del modulo stesso. Ciascun record di {{module_name}} può essere poi correlato a diversi record di Sugar quali, a titolo esemplificativo {{calls_module}}, {{contacts_module}}, {{cases_module}} ecc.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Il modulo {{plural_module_name}} è utilizzato per tenere traccia e gestire i problemi legati ai prodotti, comunemente definiti {{plural_module_name}} o difetti, che sono riscontrati sia internamente che dai clienti. - Modificare i campi di questo record facendo clic su ciascuno di essi o sul pulsante Modifica. - Visualizzare o modificare i collegamenti agli altri record nei sottopannelli spostando la visualizzazione del riquadro in basso a sinistra su "Vista dati". - Creare e visualizzare i commenti degli utenti e registrare la cronologia delle modifiche nel {{activitystream_singular_module}} spostando la visualizzazione del riquadro in basso a sinistra su "Activity Stream". - Seguire o impostare come preferito questo record usando le icone alla destra del nome del record. - Nel menu a discesa Azioni, alla destra del pulsante Modifica, sono disponibili azioni aggiuntive.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Il modulo {{plural_module_name}} è utilizzato per tracciare e gestire problemi legati ai prodotti, comunemente indicati in {{plural_module_name}} o difetti, sia trovati internamente che riportati dai clienti.

Per creare un {{module_name}}:
1. Fornire i valori desiderati per i campi.
- I campi contrassegnati con "Richiesto" devono essere compilati prima del salvataggio.
- Fare clic su "Altri dettagli" per visualizzare ulteriori campi, se necessario.
2. Fare clic su "Salva" per finalizzare il nuovo record e tornare alla pagina precedente.',
);
