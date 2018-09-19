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
    'LBL_MODULE_NAME' => 'Coda lavoro',
    'LBL_MODULE_NAME_SINGULAR' => 'Coda lavoro',
    'LBL_MODULE_TITLE' => 'Coda lavoro: Home',
    'LBL_MODULE_ID' => 'Coda lavoro',
    'LBL_TARGET_ACTION' => 'Azione',
    'LBL_FALLIBLE' => 'Fallibile',
    'LBL_RERUN' => 'Esegui nuovamente',
    'LBL_INTERFACE' => 'Interfaccia',
    'LINK_SCHEDULERSJOBS_LIST' => 'Visualizza Coda lavoro',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configurazione',
    'LBL_CONFIG_PAGE' => 'Impostazioni Coda lavoro',
    'LBL_JOB_CANCEL_BUTTON' => 'Annulla',
    'LBL_JOB_PAUSE_BUTTON' => 'Pausa',
    'LBL_JOB_RESUME_BUTTON' => 'Riprendi',
    'LBL_JOB_RERUN_BUTTON' => 'Rimetit in coda',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_LIST_ASSIGNED_USER' => 'Richiesto da',
    'LBL_LIST_STATUS' => 'Stato',
    'LBL_LIST_RESOLUTION' => 'Risoluzione',
    'LBL_NAME' => 'Funzione',
    'LBL_EXECUTE_TIME' => 'Tempo Esecuzione',
    'LBL_SCHEDULER_ID' => 'Schedulatore',
    'LBL_STATUS' => 'Stato',
    'LBL_RESOLUTION' => 'Risultato',
    'LBL_MESSAGE' => 'Messaggi',
    'LBL_DATA' => 'Data Job',
    'LBL_REQUEUE' => 'Riprova in caso di errore',
    'LBL_RETRY_COUNT' => 'Numero massimo di tentativi',
    'LBL_FAIL_COUNT' => 'Errori',
    'LBL_INTERVAL' => 'Intervallo minimo tra tentativi',
    'LBL_CLIENT' => 'Cliente proprietario',
    'LBL_PERCENT' => 'Percentuale completata',
    'LBL_JOB_GROUP' => 'Gruppo Job',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Risoluzione messa in coda',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Risoluzione parziale',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Risoluzione completa',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Risoluzione guasto',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Risoluzione annullata',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Risoluzione in corso',
    // Errors
    'ERR_CALL' => "Impossibile eseguire la funzione: %s",
    'ERR_CURL' => "Nessun CURL - impossibile  eseguire URL jobs",
    'ERR_FAILED' => "Interruzione imprevista, si pregs di controllare i log PHP e sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s in %s sulla riga %d",
    'ERR_NOUSER' => "Nessun ID utente specificato per il job",
    'ERR_NOSUCHUSER' => "Impossibile trovare User ID %",
    'ERR_JOBTYPE' => "Tipo job non specificato: %",
    'ERR_TIMEOUT' => "Fallimento forzato su timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Lavoro %1$s (%2$s) non andato a buon fine in esecuzione CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Non è stato possibile caricare bean con id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Non è possibile trovare handler per il percorso %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'L&#39;etensione per questa coda non è installata',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Alcuni dei campi sono vuoti',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Impostazioni Coda lavoro',
    'LBL_CONFIG_MAIN_SECTION' => 'Configurazione principale',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configurazione Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configurazione AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configurazione Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Aiuto in fase di configurazione coda di lavoro',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Sezione di configurazione principale.</b></p>
<ul>
    <li>Esecutore:
    <ul>
    <li><i>Standard</i> - usare solo un processo per i lavoratori.</li>
    <li><i>Parallelo</i> - usare pochi processi per i lavoratori.</li>
    </ul>
    </li>
    <li>Adattatore:
    <ul>
    <li><i>Default Queue</i> - Ciò userà solo il Database di Sugar senza nessuna coda di messaggi.</li>
    <li><i>Amazon SQS</i> - Il Servizio Amazon Simple Queue è un servizio di
    messaggistica distribuita introdotto da Amazon.com.
    Supporta l'invio programmatico dei messaggi tramite le applicazioni del servizio web come modo per comunicare su Internet.</li>
    <li><i>RabbitMQ</i> - è un software broker di messaggi open source (a volte chiamato middleware orientato verso il messaggi)
    che implementa il Protocollo di Coda Messaggi Avanzata (AMQP).</li>
    <li><i>Gearman</i> - è un framework applicazione open source progettato per distribuire compiti computer adeguati a diversi computer, di modo da eseguire più rapidamente le attività più lunghe.</li>
    <li><i>Immediato</i> - Come la coda predefinita ma esegue l'attività immediatamente dopo l'aggiunta.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Aiuto di configurazione Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Sezione Configurazione Amazon SQS.</b></p>
<ul>
    <li>ID chiave accesso: <i>Inserire il numero ID chiave di accesso per Amazon SQS</i></li>
    <li>Chiave di accesso segreto: <i>Inserire la chiave di accesso segreto per Amazon SQS</i></li>
    <li>Regione: <i>Enter the region of Amazon SQS server</i></li>
    <li>Nome coda: <i>Inserire il nome della coda del server Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Aiuto Configurazione AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Sezione Configurazione AMQP.</b></p>
<ul>
    <li>URL Server: <i>Inserire l'URL del server di coda del messaggio.</i></li>
    <li>Login: <i>Inserire il login per RabbitMQ</i></li>
    <li>Password: <i>Inserire la password per RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Aiuto Configurazione Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Sezione Configurazione Gearman.</b></p>
<ul>
    <li>Server URL: <i>Inserire l'URL del server di coda del messaggio.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adattatore',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Esecutore',
    'LBL_SERVER_URL' => 'URL Server',
    'LBL_LOGIN' => 'Accesso',
    'LBL_ACCESS_KEY' => 'ID chiave accesso',
    'LBL_REGION' => 'Regione',
    'LBL_ACCESS_KEY_SECRET' => 'Chiave accesso segreto',
    'LBL_QUEUE_NAME' => 'Nome adattatore',
);
