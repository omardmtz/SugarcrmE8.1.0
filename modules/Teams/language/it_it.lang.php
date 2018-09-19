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
    'ERR_ADD_RECORD' => 'Per aggiungere un utente a questo gruppo deve essere specificato il numero del record.',
    'ERR_DUP_NAME' => 'Il nome del gruppo esiste già, si prega di sceglierne un altro.',
    'ERR_DELETE_RECORD' => 'Per eliminare il gruppo deve essere specificato il numero del record.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Errore. Il gruppo selezionato <b>({0})</b> è un gruppo che hai scelto di eliminare. Si prega di selezionare un altro ruppo.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Errore. Non è possibile cancellare un utente il cui gruppo privato non sia stato cancellato.',
    'LBL_DESCRIPTION' => 'Descrizione:',
    'LBL_GLOBAL_TEAM_DESC' => 'Visibile Globalmente',
    'LBL_INVITEE' => 'Membri del Gruppo',
    'LBL_LIST_DEPARTMENT' => 'Dipartimento',
    'LBL_LIST_DESCRIPTION' => 'Descrizione',
    'LBL_LIST_FORM_TITLE' => 'Elenco Gruppi',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_FIRST_NAME' => 'Nome:',
    'LBL_LAST_NAME' => 'Cognome:',
    'LBL_LIST_REPORTS_TO' => 'Dipende Da',
    'LBL_LIST_TITLE' => 'Funzione',
    'LBL_MODULE_NAME' => 'Gruppi',
    'LBL_MODULE_NAME_SINGULAR' => 'Gruppo',
    'LBL_MODULE_TITLE' => 'Gruppi: Home',
    'LBL_NAME' => 'Gruppo:',
    'LBL_NAME_2' => 'Nome Gruppo (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Nome Gruppo Primario',
    'LBL_NEW_FORM_TITLE' => 'Nuovo Gruppo',
    'LBL_PRIVATE' => 'Privato',
    'LBL_PRIVATE_TEAM_FOR' => 'Gruppo privato per:',
    'LBL_SEARCH_FORM_TITLE' => 'Cerca Gruppo',
    'LBL_TEAM_MEMBERS' => 'Membri del Gruppo',
    'LBL_TEAM' => 'Gruppi:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Utenti',
    'LBL_USERS' => 'Utenti',
    'LBL_REASSIGN_TEAM_TITLE' => 'Ci sono dei record assegnati ai seguenti gruppi: <b>{0}</b><br>Prima di procedere con l´eliminazione del gruppo, è necessario riassegnare questi record ad un nuovo gruppo. Si prega di selezionare il gruppo da usare come sostituto.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Riassegnazione',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Riassegnazione [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Procedere con l´aggiornamento del nuovo gruppo per i record selezionati?',
    'LBL_REASSIGN_TABLE_INFO' => 'Aggiornamento tabella {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'L´operatione è stata completata con successo.',
    'LNK_LIST_TEAM' => 'Gruppi',
    'LNK_LIST_TEAMNOTICE' => 'Avvisi del Gruppo',
    'LNK_NEW_TEAM' => 'Crea Gruppo',
    'LNK_NEW_TEAM_NOTICE' => 'Crea Avviso del Gruppo',
    'NTC_DELETE_CONFIRMATION' => 'Sei sicuro di voler eliminare questo record?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Sei sicuro di voler eliminare questo utente come membro?',
    'LBL_EDITLAYOUT' => 'Modifica Layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Autorizzazioni basate sul gruppo',
    'LBL_TBA_CONFIGURATION_DESC' => 'Abilita l&#39;accesso del gruppo e gestisci l&#39;accesso per modulo.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Abilita autorizzazioni basate sul gruppo',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Seleziona i moduli da abilitare',
    'LBL_TBA_CONFIGURATION_TITLE' => 'L&#39;abilitazione delle autorizzazioni basate sul gruppo permetterà di assegnare diritti specifici di accesso ai gruppi e agli utenti per i singoli moduli, tramite la gestione dei ruoli.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
La disabilitazione delle autorizzazioni basate sul gruppo per un modulo annullerà tutti i dati associati ad esse per quel
 modulo, comprese le Definizioni dei processi o i Processi che utilizzano la funzione. Sono compresi i Ruoli che utilizzano l'opzione
 "Proprietario e gruppo selezionato" per quel modulo e tutti i dati relativi alle autorizzazioni basate sul gruppo per i record di quel modulo.
 Consigliamo anche di utilizzare lo strumento Quick Repair e Rebuild per cancellare la cache del sistema dopo aver disabilitato le autorizzazioni
 basate sul gruppo per tutti i moduli.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Avvertenza:</strong> la disabilitazione delle autorizzazioni basate sul gruppo per un modulo annullerà tutti i dati associati ad esse per quel modulo, comprese le Definizioni dei processi o i Processi che utilizzano la funzione. Sono compresi i Ruoli che utilizzano l'opzione "Proprietario e gruppo selezionato" per quel modulo e tutti i dati relativi alle autorizzazioni basate sul gruppo 
per i record di quel modulo. Consigliamo anche di utilizzare lo strumento Quick Repair and Rebuild per cancellare la cache 
del sistema dopo aver disabilitato le autorizzazioni basate sul gruppo per tutti i moduli.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
La disabilitazione delle autorizzazioni basate sul gruppo per un modulo annullerà tutti i dati associati ad esse per quel 
modulo, comprese le Definizioni dei processi o i Processi che utilizzano la funzione. Sono compresi i Ruoli che utilizzano l'opzione "Proprietario e gruppo selezionato" per quel modulo e tutti i dati relativi alle autorizzazioni basate sul gruppo per i record di quel modulo.
 Consigliamo anche di utilizzare lo strumento Quick Repair and Rebuild per cancellare la cache del sistema dopo aver disabilitato le autorizzazioni 
basate sul gruppo per tutti i moduli. Se non si ha l'accesso all'utilizzo di Quick Repair and Rebuild, contattare un amministratore con
 accesso al menu Ripara.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Avvertenza:</strong> la disabilitazione delle autorizzazioni basate sul gruppo per un modulo annullerà tutti i dati associati ad esse per quel modulo, comprese le Definizioni dei processi o i Processi che utilizzano la funzione. Sono compresi i Ruoli che utilizzano l'opzione "Proprietario e gruppo selezionato" per quel modulo e tutti i dati relativi alle autorizzazioni basate sul gruppo per i record di quel modulo.
 Consigliamo anche di utilizzare lo strumento Quick Repair and Rebuild per cancellare la cache del sistema dopo
aver disabilitato le autorizzazioni 
basate sul gruppo per tutti i moduli. Se non si ha l'accesso all'utilizzo di Quick Repair and Rebuild, contattare 
un amministratore con accesso al menu Ripara.
STR
,
);
