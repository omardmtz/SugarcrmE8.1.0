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
  'LBL_TASKS_LIST_DASHBOARD' => 'Dashboard elenco compiti',

  'LBL_MODULE_NAME' => 'Compiti',
  'LBL_MODULE_NAME_SINGULAR' => 'Compito',
  'LBL_TASK' => 'Compiti:',
  'LBL_MODULE_TITLE' => 'Compiti: Home',
  'LBL_SEARCH_FORM_TITLE' => 'Cerca Compito',
  'LBL_LIST_FORM_TITLE' => 'Elenco Compiti',
  'LBL_NEW_FORM_TITLE' => 'Nuovo Compito',
  'LBL_NEW_FORM_SUBJECT' => 'Oggetto:',
  'LBL_NEW_FORM_DUE_DATE' => 'Data Scadenza:',
  'LBL_NEW_FORM_DUE_TIME' => 'Ora Scadenza:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Chiudi',
  'LBL_LIST_SUBJECT' => 'Oggetto',
  'LBL_LIST_CONTACT' => 'Contatto',
  'LBL_LIST_PRIORITY' => 'Priorità',
  'LBL_LIST_RELATED_TO' => 'Collegato a',
  'LBL_LIST_DUE_DATE' => 'Data Scadenza',
  'LBL_LIST_DUE_TIME' => 'Ora Scadenza',
  'LBL_SUBJECT' => 'Oggetto:',
  'LBL_STATUS' => 'Stato:',
  'LBL_DUE_DATE' => 'Data Scadenza:',
  'LBL_DUE_TIME' => 'Ora Scadenza:',
  'LBL_PRIORITY' => 'Priorità:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Data & Ora Scadenza',
  'LBL_START_DATE_AND_TIME' => 'Data e Ora Inizio:',
  'LBL_START_DATE' => 'Data Inizio:',
  'LBL_LIST_START_DATE' => 'Data Inizio',
  'LBL_START_TIME' => 'Ora Inizio:',
  'LBL_LIST_START_TIME' => 'Ora Inizio',
  'DATE_FORMAT' => '(aaaa-mm-jj)',
  'LBL_NONE' => 'Nessuno',
  'LBL_CONTACT' => 'Contatto:',
  'LBL_EMAIL_ADDRESS' => 'Indirizzo Email:',
  'LBL_PHONE' => 'Telefono:',
  'LBL_EMAIL' => 'Email:',
  'LBL_DESCRIPTION_INFORMATION' => 'Descrizione',
  'LBL_DESCRIPTION' => 'Descrizione:',
  'LBL_NAME' => 'Nome:',
  'LBL_CONTACT_NAME' => 'Nome Contatto:',
  'LBL_LIST_COMPLETE' => 'Completa:',
  'LBL_LIST_STATUS' => 'Stato',
  'LBL_DATE_DUE_FLAG' => 'Nessuna Data di Scadenza',
  'LBL_DATE_START_FLAG' => 'Nessuna Data Inizio',
  'ERR_DELETE_RECORD' => 'Per eliminare il contatto deve essere specificato il numero del record.',
  'ERR_INVALID_HOUR' => 'Si prega di inserisci un´ora compresa tra 0 e 24',
  'LBL_DEFAULT_PRIORITY' => 'Medio',
  'LBL_LIST_MY_TASKS' => 'I miei Compiti Aperti',
  'LNK_NEW_TASK' => 'Nuovo Compito',
  'LNK_TASK_LIST' => 'Visualizza Compiti',
  'LNK_IMPORT_TASKS' => 'Importa Compiti',
  'LBL_CONTACT_FIRST_NAME'=>'Nome Contatto',
  'LBL_CONTACT_LAST_NAME'=>'Cognome Contatto',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Assegnato a',
  'LBL_ASSIGNED_TO_NAME'=>'Assegnato a:',
  'LBL_LIST_DATE_MODIFIED' => 'Data Modificata',
  'LBL_CONTACT_ID' => 'ID Contatto:',
  'LBL_PARENT_ID' => 'ID Padre:',
  'LBL_CONTACT_PHONE' => 'Telefono Contatto:',
  'LBL_PARENT_NAME' => 'Tipo Relazione:',
  'LBL_ACTIVITIES_REPORTS' => 'Report Attività',
  'LBL_EDITLAYOUT' => 'Modifica Layout' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Informazioni Compito',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Note',
  'LBL_REVENUELINEITEMS' => 'Elementi dell&#39;opportunità',
  //For export labels
  'LBL_DATE_DUE' => 'Data Scadenza',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nome Utente Assegnato',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID Utente Assegnato',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificato da ID',
  'LBL_EXPORT_CREATED_BY' => 'Creato da ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Collegato al Modulo',
  'LBL_EXPORT_PARENT_ID' => 'Collegato a ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Compito concluso con successo.',
  'LBL_ASSIGNED_USER' => 'Assegnato a',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Note',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Il modulo {{plural_module_name}} consiste in azioni flessibili, attività da svolgere o altri tipi di attività che devono essere completate. I records del modulo {{module_name}} possono essere relazionati ad un record in più moduli attraverso il campo flex relate e possono anche essere relazionati ad un singolo {{contacts_singular_module}}. Esistono diversi modi per creare {{plural_module_name}} in Sugar, ad esempio dal modulo {{plural_module_name}}, duplicando un record esistente, attraverso l´importazione di {{plural_module_name}}, ecc. Una volta che il record {{module_name}} è stato creato, puoi visualizzare e modificare le informazioni relative al modulo {{module_name}} attraverso la vista elenco del modulo {{plural_module_name}}. In base ai dettagli del modulo {{module_name}}, puoi anche visualizzare e modificare le informazioni del modulo {{module_name}} attraverso il modulo Calendario. Ogni record del modulo {{module_name}} può essere poi relazionato ad altri record di Sugar come {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, e molti altri.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Il modulo {{plural_module_name}} consiste in azioni flessibili, attività da svolgere o altri tipi di attività che devono essere completate. 
- Puoi modificare i campi di questi records cliccando il singolo campo o il pulsante Modifica. 
- Puoi visualizzare o modificare i collegamenti agli altri record nei sottopannelli agendo sul pulsante "Visualizza dati" in basso a sinistra
- Puoi creare e visualizzare i commenti degli utenti e la cronologia delle modifiche ai record nel modulo {{activitystream_singular_module}} agendo sul pulsante  "Activity Stream" in basso a sinistra. 
- Puoi seguire o impostare come preferito questo record usando le icone alla destra del nome del record. 
- Azioni aggiuntive sono disponibili nel menù a tendina delle Azioni alla destra del pulsante Modifica.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Il modulo {{plural_module_name}} consiste in azioni flessibili, attività da svolgere, o altri tipi di attività che devono essere completate. 

Per creare un {{module_name}}: 
1. Compila i campi con i valori desiderati. 
- I campi contrassegnati come "Obbligatorio" devono essere compilati prima del salvataggio. 
- Clicca "Mostra più Moduli" per visualizzare ulteriori campi se necessario.
2. Clicca "Salva" per salvare il nuovo record e tornare alla pagina precedente.',

);
