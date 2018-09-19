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
    'LBL_NOTES_LIST_DASHBOARD' => 'Dashboard elenco note',

    'ERR_DELETE_RECORD' => 'Per eliminare l´azienda deve essere specificato il numero del record.',
    'LBL_ACCOUNT_ID' => 'ID Azienda:',
    'LBL_CASE_ID' => 'ID Reclamo:',
    'LBL_CLOSE' => 'Chiudi:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID Contatto:',
    'LBL_CONTACT_NAME' => 'Contatto:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Note',
    'LBL_DESCRIPTION' => 'Note',
    'LBL_EMAIL_ADDRESS' => 'Indirizzo Email:',
    'LBL_EMAIL_ATTACHMENT' => 'Allegato Email',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Allegato e-mail per',
    'LBL_FILE_MIME_TYPE' => 'Tipo Mime',
    'LBL_FILE_EXTENSION' => 'Estensione file',
    'LBL_FILE_SOURCE' => 'Origine file',
    'LBL_FILE_SIZE' => 'Dimensione file',
    'LBL_FILE_URL' => 'URL File',
    'LBL_FILENAME' => 'Allegato:',
    'LBL_LEAD_ID' => 'ID Lead:',
    'LBL_LIST_CONTACT_NAME' => 'Contatto',
    'LBL_LIST_DATE_MODIFIED' => 'Ultima Modifica',
    'LBL_LIST_FILENAME' => 'Allegato',
    'LBL_LIST_FORM_TITLE' => 'Elenco Note',
    'LBL_LIST_RELATED_TO' => 'Relativo a',
    'LBL_LIST_SUBJECT' => 'Oggetto',
    'LBL_LIST_STATUS' => 'Stato',
    'LBL_LIST_CONTACT' => 'Contatto',
    'LBL_MODULE_NAME' => 'Note',
    'LBL_MODULE_NAME_SINGULAR' => 'Nota',
    'LBL_MODULE_TITLE' => 'Note: Home',
    'LBL_NEW_FORM_TITLE' => 'Nuova Nota o Aggiungi Allegato',
    'LBL_NEW_FORM_BTN' => 'Aggiungi una Nota',
    'LBL_NOTE_STATUS' => 'Nota',
    'LBL_NOTE_SUBJECT' => 'Oggetto della Nota:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Allegati',
    'LBL_NOTE' => 'Nota:',
    'LBL_OPPORTUNITY_ID' => 'ID Opportunità:',
    'LBL_PARENT_ID' => 'ID Padre:',
    'LBL_PARENT_TYPE' => 'Tipo Padre',
    'LBL_EMAIL_TYPE' => 'Tipo e-mail',
    'LBL_EMAIL_ID' => 'ID e-mail',
    'LBL_PHONE' => 'Telefono:',
    'LBL_PORTAL_FLAG' => 'Mostra nel Portale?',
    'LBL_EMBED_FLAG' => 'Inserisci nell´email?',
    'LBL_PRODUCT_ID' => 'ID Prodotto:',
    'LBL_QUOTE_ID' => 'ID Offerta:',
    'LBL_RELATED_TO' => 'Riferito A:',
    'LBL_SEARCH_FORM_TITLE' => 'Cerca Nota',
    'LBL_STATUS' => 'Stato',
    'LBL_SUBJECT' => 'Oggetto:',
    'LNK_IMPORT_NOTES' => 'Importa Note',
    'LNK_NEW_NOTE' => 'Nuova Nota o Allegato',
    'LNK_NOTE_LIST' => 'Visualizza Note',
    'LBL_MEMBER_OF' => 'Membro di:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Assegnato a',
    'LBL_OC_FILE_NOTICE' => 'Per visualizzare i file si prega di connettersi al server.',
    'LBL_REMOVING_ATTACHMENT' => 'Rimozione allegato...',
    'ERR_REMOVING_ATTACHMENT' => 'Rimozione allegato fallita...',
    'LBL_CREATED_BY' => 'Creato Da',
    'LBL_MODIFIED_BY' => 'Modificato Da',
    'LBL_SEND_ANYWAYS' => 'Questa email non ha un oggetto. Inviare/Salvare ugualmente?',
    'LBL_LIST_EDIT_BUTTON' => 'Modifica',
    'LBL_ACTIVITIES_REPORTS' => 'Report Attività',
    'LBL_PANEL_DETAILS' => 'Dettagli',
    'LBL_NOTE_INFORMATION' => 'Informazioni Nota',
    'LBL_MY_NOTES_DASHLETNAME' => 'Le mie note',
    'LBL_EDITLAYOUT' => 'Modifica Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Nome',
    'LBL_LAST_NAME' => 'Cognome',
    'LBL_EXPORT_PARENT_TYPE' => 'Riferito al Modulo',
    'LBL_EXPORT_PARENT_ID' => 'Riferito a',
    'LBL_DATE_ENTERED' => 'Data Creazione',
    'LBL_DATE_MODIFIED' => 'Data Modifica',
    'LBL_DELETED' => 'Eliminato',
    'LBL_REVENUELINEITEMS' => 'Elementi dell&#39;opportunità',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Il modulo {{plural_module_name}} consiste in singole {{plural_module_name}} che contengono un testo o un allegato collegato al record relazionato. I records {{module_name}} possono essere relazionati ad un record in più moduli attraverso il campo flex relate, e essere relazioni ad un singolo {{contacts_singular_module}}. Le {{plural_module_name}} possono contenere un testo generico o un allegato relativo al record. Esistono vari modi per creare {{plural_module_name}} in Sugar, ad esempio attraverso il modulo {{plural_module_name}}, attraverso l´importazione di {{plural_module_name}}, attraverso il sottopannello Cronologia ecc. Una volta che il record {{module_name}} è stato creato, puoi visualizzare e modificare informazioni relative al modulo {{module_name}} attraverso la vista elenco delle {{plural_module_name}}. Ogni {{module_name}} potrebbe essere relazionata ad un record di Sugar come {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} e molti altri.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Il modulo {{plural_module_name}} consiste in singole {{plural_module_name}} che contengono un testo o un allegato collegato al record relazionato. 
  - Puoi modificare i campi di questi records cliccando il singolo campo o il pulsante Modifica. 
  - Puoi visualizzare o modificare i collegamenti agli altri records nei sottopannelli agendo sul pulsante "Visualizza Dati" in basso a sinistra. 
  - Puoi creare e visualizzare i commenti degli utenti e la cronologia delle modifiche dei records nel modulo {{activitystream_singular_module}} agendo sul pulsante "Activity Stream" in basso a sinistra. 
  - Puoi seguire o impostare come preferito questo record usando le icone alla destra del nome del record. 
  - Azioni aggiuntive sono disponibili nel menù a tendina delle Azioni alla destra del pulsante Modifica.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Per creare un {{module_name}}: 
 1. Compila i campi con i valori desiderati. 
 - I campi contrassegnati come "Obbligatorio" devono essere compilati prima del salvataggio. 
 - Clicca "Altri dettagli" per visualizzare ulteriori campi se necessario. 
 2. Cliccare "Salva" per salvare il nuovo record e tornare alla pagina precedente.',
);
