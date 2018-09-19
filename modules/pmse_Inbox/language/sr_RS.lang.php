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
    'LBL_MODULE_NAME' => 'Procesi',
    'LBL_MODULE_TITLE' => 'Procesi',
    'LBL_MODULE_NAME_SINGULAR' => 'Procesi',
    'LNK_LIST' => 'Prikazati procese',
    'LNK_PMSE_INBOX_PROCESS_MANAGEMENT' => 'Lista procesa',
    'LNK_PMSE_INBOX_UNATTENDED_PROCESSES' => 'Neprisutni procesi',

    'LBL_CAS_ID' => 'Broj procesa',
    'LBL_PMSE_HISTORY_LOG_NOTFOUND_USER' => "Nepoznato (prema Id korisnika:%s')",
    'LBL_PMSE_HISTORY_LOG_TASK_HAS_BEEN' => "zadatak je bio",
    'LBL_PMSE_HISTORY_LOG_TASK_WAS' => "zadatak je bio ",
    'LBL_PMSE_HISTORY_LOG_EDITED' => "izmenjen",
    'LBL_PMSE_HISTORY_LOG_CREATED' => "kreiran",
    'LBL_PMSE_HISTORY_LOG_ROUTED' => "usađen",
    'LBL_PMSE_HISTORY_LOG_DONE_UNKNOWN' => "Urađeno kao nepoznat zadatak",
    'LBL_PMSE_HISTORY_LOG_CREATED_CASE' => "je kreirao proces br. %s ",
    'LBL_PMSE_HISTORY_LOG_DERIVATED_CASE' => "dodeljen mu je proces br. %s ",
    'LBL_PMSE_HISTORY_LOG_CURRENTLY_HAS_CASE' => "trenutno ima proces br. %s ",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME' => "'%s'",
    'LBL_PMSE_HISTORY_LOG_ACTION_PERFORMED'  => ". Obavljen akcija je bila: <span style=\"font-weight: bold\">[%s]</span>",
    'LBL_PMSE_HISTORY_LOG_ACTION_STILL_ASSIGNED' => " Zadatak je još uvek dodeljen",
    'LBL_PMSE_HISTORY_LOG_MODULE_ACTION'  => " na %s zapisu %s",
    'LBL_PMSE_HISTORY_LOG_WITH_EVENT'  => " sa slučajem %s",
    'LBL_PMSE_HISTORY_LOG_WITH_GATEWAY'  => ". Sa kapijom %s je procenjen i usađen u sledeći zadatak ",
    'LBL_PMSE_HISTORY_LOG_NOT_REGISTERED_ACTION'  => "neregistrovana akcija",
    'LBL_PMSE_HISTORY_LOG_NO_YET_STARTED' => '(još nije počelo)',
    'LBL_PMSE_HISTORY_LOG_FLOW' => 'je dodeljen da nastavi zadatak',

    'LBL_PMSE_HISTORY_LOG_START_EVENT' => "%s %s zapis, što je uzrokovalo da napredni tok posla pokrene proces #%s",
    'LBL_PMSE_HISTORY_LOG_GATEWAY'  => "%s %s %s je procenjen i usmeren na sledeći zadatak",
    'LBL_PMSE_HISTORY_LOG_EVENT'  => "%s događaj %s je %s",
    'LBL_PMSE_HISTORY_LOG_END_EVENT'  => "Kraj",
    'LBL_PMSE_HISTORY_LOG_CREATED'  => "kreiran",
    'LBL_PMSE_HISTORY_LOG_MODIFIED'  => "izmenjen",
    'LBL_PMSE_HISTORY_LOG_STARTED'  => "započet",
    'LBL_PMSE_HISTORY_LOG_PROCESSED'  => "obrađen",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_SELF_SERVICE'  => "Aktivnost %s na %s zapisu je dostupna za samousluživanje",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY'  => "%s aktivnost %s na %szapisu",
    'LBL_PMSE_HISTORY_LOG_ASSIGNED'  => "je dodeljena",
    'LBL_PMSE_HISTORY_LOG_ROUTED'  => "je usmerena",
    'LBL_PMSE_HISTORY_LOG_ACTION'  => "%s radnja %s je obrađena na %s zapisu",
    'LBL_PMSE_HISTORY_LOG_ASSIGN_USER_ACTION'  => "dodeljen proces #%s %s %s zapisa %s radnje %s",
    'LBL_PMSE_HISTORY_LOG_ON'  => "na",
    'LBL_PMSE_HISTORY_LOG_AND'  => "i",

    'LBL_PMSE_LABEL_APPROVE' => 'Odobriti',
    'LBL_PMSE_LABEL_REJECT' => 'Odbiti',
    'LBL_PMSE_LABEL_ROUTE' => 'Put',
    'LBL_PMSE_LABEL_CLAIM' => 'Tvrditi',
    'LBL_PMSE_LABEL_STATUS' => 'Status',
    'LBL_PMSE_LABEL_REASSIGN' => 'Odaberi novog procesnog korisnika',
    'LBL_PMSE_LABEL_CHANGE_OWNER' => 'Promeni dodeljeno korisniku',
    'LBL_PMSE_LABEL_EXECUTE' => 'Izvrši',
    'LBL_PMSE_LABEL_CANCEL' => 'Otkaži',
    'LBL_PMSE_LABEL_HISTORY' => 'Istorija',
    'LBL_PMSE_LABEL_NOTES' => 'Prikažu beleške',
    'LBL_PMSE_LABEL_ADD_NOTES' => 'Dodaj beleške',

    'LBL_PMSE_FORM_OPTION_SELECT' => 'Izaberi...',
    'LBL_PMSE_FORM_LABEL_USER' => 'Korisnik',
    'LBL_PMSE_FORM_LABEL_TYPE' => 'Tip',
    'LBL_PMSE_FORM_LABEL_NOTE' => 'Beleška',

    'LBL_PMSE_BUTTON_SAVE' => 'Sačuvaj',
    'LBL_PMSE_BUTTON_CLOSE' => 'Zatvori',
    'LBL_PMSE_BUTTON_CANCEL' => 'Otkaži',
    'LBL_PMSE_BUTTON_REFRESH' => 'Osveži',
    'LBL_PMSE_BUTTON_CLEAR' => 'Obriši',
    'LBL_PMSE_WARNING_CLEAR' => 'Da li ste sigurni da želite da obrišete podatke zapisa? Ovo se ne može uraditi.',

    'LBL_PMSE_FORM_TOOLTIP_SELECT_USER' => 'Dodeljuje proces korisniku.',
    'LBL_PMSE_FORM_TOOLTIP_CHANGE_USER' => 'Ažurira polje "Dodeljeno" na zapisu korisniku.',

    'LBL_PMSE_ALERT_REASSIGN_UNSAVED_FORM' => 'Postoje nesačuvane promene u trenutnom obliku, one će biti odbačene ako ponovo dodelite trenutni zadatak. Da li želite da nastavite?',
    'LBL_PMSE_ALERT_REASSIGN_SUCCESS' => 'Proces je uspešno ponovo dodeljen',

    'LBL_PMSE_LABEL_CURRENT_ACTIVITY' => 'Trenutna aktivnost',
    'LBL_PMSE_LABEL_ACTIVITY_DELEGATE_DATE' => 'Datum dodele aktivnosti',
    'LBL_PMSE_LABEL_ACTIVITY_START_DATE' => 'Datum početka',
    'LBL_PMSE_LABEL_EXPECTED_TIME' => 'Očekivano vreme',
    'LBL_PMSE_LABEL_DUE_DATE' => 'Datum završetka',
    'LBL_PMSE_LABEL_CURRENT' => 'Trenutni',
    'LBL_PMSE_LABEL_OVERDUE' => 'U kašnjenju',
    'LBL_PMSE_LABEL_PROCESS' => 'Procesi',
    'LBL_PMSE_LABEL_PROCESS_AUTHOR' => 'Advanced Workflow',
    'LBL_PMSE_LABEL_UNASSIGNED' => 'Nije dodeljeno',

    'LBL_RECORD_NAME'  => "Naziv zapisa",
    'LBL_PROCESS_NAME'  => "Naziv procesa",
    'LBL_PROCESS_DEFINITION_NAME'  => "Naziv procesne definicije",
    'LBL_OWNER' => 'Dodeljeno',
    'LBL_ACTIVITY_OWNER'=>'Korisnik procesa',
    'LBL_PROCESS_OWNER'=>'Vlasnik procesa',
    'LBL_STATUS_COMPLETED' => 'Procesi su komletirani',
    'LBL_STATUS_TERMINATED' => 'Završen proces',
    'LBL_STATUS_IN_PROGRESS' => 'Proces u progresu',
    'LBL_STATUS_CANCELLED' => 'Procesi su otkazani',
    'LBL_STATUS_ERROR' => 'Procesne greške',

    'LBL_PMSE_TITLE_PROCESSESS_LIST'  => 'Lista procesa',
    'LBL_PMSE_TITLE_UNATTENDED_CASES' => 'Neprisutni procesi',
    'LBL_PMSE_TITLE_REASSIGN' => 'Promeni dodeljeno korisniku',
    'LBL_PMSE_TITLE_AD_HOC' => 'Odaberi novog procesnog korisnika',
    'LBL_PMSE_TITLE_ACTIVITY_TO_REASSIGN' => "Odaberi novog procesnog korisnika",
    'LBL_PMSE_TITLE_HISTORY' => 'Istorija procesa',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR' => 'Proces #%s: Trenutni status',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR_OBJ' => 'Proces #{{id}}: trenutni status',
    'LBL_PMSE_TITLE_LOG_VIEWER' => 'Prikaz evidencije funkcije Advanced Workflow',
    'LBL_PMSE_TITLE_PROCESS_NOTES' => 'Beleške procesa',

    'LBL_PMSE_MY_PROCESSES' => 'Moji procesi',
    'LBL_PMSE_SELF_SERVICE_PROCESSES' => 'Samouslužni procesi',

    'LBL_PMSE_ACTIVITY_STREAM_APPROVE'=>"&0 on <strong>%s</strong> Prihvaćeno ",
    'LBL_PMSE_ACTIVITY_STREAM_REJECT'=>"&0 on <strong>%s</strong> Odbijeno ",
    'LBL_PMSE_ACTIVITY_STREAM_ROUTE'=>'&0 on <strong>%s</strong> Usađeno ',
    'LBL_PMSE_ACTIVITY_STREAM_CLAIM'=>"&0 on <strong>%s</strong> Potvrđeno ",
    'LBL_PMSE_ACTIVITY_STREAM_REASSIGN'=>"&0 on <strong>%s</strong> dodeljeno korisniku &1 ",
    'LBL_PMSE_CANCEL_MESSAGE' => "Da li ste sigurni da želite da prekinete broj procesa #{}?",
    'LBL_ASSIGNED_USER'=>"Dodeljen korisnik",
    'LBL_PMSE_SETTING_NUMBER_CYCLES' => "Greška u broju ciklusa",
    'LBL_PMSE_SHOW_PROCESS' => 'Prikaži proces',
    'LBL_PMSE_FILTER' => 'Filter',

    'LBL_PA_PROCESS_APPROVE_QUESTION' => 'Da li ste sigurni da želite da odobrite ovaj proces?',
    'LBL_PA_PROCESS_REJECT_QUESTION' => 'Da li ste sigurni da želite da odbijete ovaj proces?',
    'LBL_PA_PROCESS_ROUTE_QUESTION' => 'Da li ste sigurni da želite da usmerite ovaj proces?',
    'LBL_PA_PROCESS_APPROVED_SUCCESS' => 'Proces je uspešno odobren',
    'LBL_PA_PROCESS_REJECTED_SUCCESS' => 'Proces je uspešno odbijen',
    'LBL_PA_PROCESS_ROUTED_SUCCESS' => 'Proces je uspešno usmeren',
    'LBL_PA_PROCESS_CLOSED' => 'Proces koji pokušavate da pogledate je zatvoren.',
    'LBL_PA_PROCESS_UNAVAILABLE' => 'Proces koji pokušavate d pogledate nije trenutno dostupan.',

    'LBL_PMSE_ASSIGN_USER' => 'Dodeliti korisniku',
    'LBL_PMSE_ASSIGN_USER_APPLIED' => 'Primenjen je dodeljen korisnik',

    'LBL_PMSE_LABEL_PREVIEW' => 'Pregled dizajna procesa',
);

