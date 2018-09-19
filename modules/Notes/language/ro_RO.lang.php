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
    'LBL_NOTES_LIST_DASHBOARD' => 'Tabloul de bord Listă note',

    'ERR_DELETE_RECORD' => 'Trebuie să specifici un număr de înregistrare pentru a șterge contul.',
    'LBL_ACCOUNT_ID' => 'Identitate Cont',
    'LBL_CASE_ID' => 'Identitate caseta:',
    'LBL_CLOSE' => 'Inchide:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Identificare Contact',
    'LBL_CONTACT_NAME' => 'Contact',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Note',
    'LBL_DESCRIPTION' => 'Nota',
    'LBL_EMAIL_ADDRESS' => 'Adresa Email:',
    'LBL_EMAIL_ATTACHMENT' => 'Atasare email',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Atașament e-mail pentru',
    'LBL_FILE_MIME_TYPE' => 'Tipul de mima',
    'LBL_FILE_EXTENSION' => 'Extensie fișier',
    'LBL_FILE_SOURCE' => 'Sursă fișier',
    'LBL_FILE_SIZE' => 'Dimensiune fișier',
    'LBL_FILE_URL' => 'Fisier URL',
    'LBL_FILENAME' => 'Atasament',
    'LBL_LEAD_ID' => 'Identitate Lead',
    'LBL_LIST_CONTACT_NAME' => 'Contact',
    'LBL_LIST_DATE_MODIFIED' => 'Ultima modificare',
    'LBL_LIST_FILENAME' => 'Atasament',
    'LBL_LIST_FORM_TITLE' => 'Lista note',
    'LBL_LIST_RELATED_TO' => 'Asociat cu:',
    'LBL_LIST_SUBJECT' => 'Subiect',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Contact',
    'LBL_MODULE_NAME' => 'Note',
    'LBL_MODULE_NAME_SINGULAR' => 'Nota',
    'LBL_MODULE_TITLE' => 'Note: Acasa',
    'LBL_NEW_FORM_TITLE' => 'Creeaza Nota sau Ataseaza Atasament',
    'LBL_NEW_FORM_BTN' => 'Adauga nota',
    'LBL_NOTE_STATUS' => 'Nota',
    'LBL_NOTE_SUBJECT' => 'Note Subiect',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Atasate',
    'LBL_NOTE' => 'Nota:',
    'LBL_OPPORTUNITY_ID' => 'Identitate Oportunitate',
    'LBL_PARENT_ID' => 'parent id',
    'LBL_PARENT_TYPE' => 'Tip Parinte',
    'LBL_EMAIL_TYPE' => 'Tip e-mail',
    'LBL_EMAIL_ID' => 'ID e-mail',
    'LBL_PHONE' => 'Telefon',
    'LBL_PORTAL_FLAG' => 'Arata in portal?',
    'LBL_EMBED_FLAG' => 'Incorporat in email?',
    'LBL_PRODUCT_ID' => 'Identitate produs:',
    'LBL_QUOTE_ID' => 'ID ofertă:',
    'LBL_RELATED_TO' => 'Asociat cu:',
    'LBL_SEARCH_FORM_TITLE' => 'Cauta nota',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Subiect',
    'LNK_IMPORT_NOTES' => 'Importa Notite',
    'LNK_NEW_NOTE' => 'Creează Notă sau Ataşament',
    'LNK_NOTE_LIST' => 'Note',
    'LBL_MEMBER_OF' => 'Membru al',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Utilizator Atribuit',
    'LBL_OC_FILE_NOTICE' => 'Va rugam logati-va la server pentru a vedea fisierul',
    'LBL_REMOVING_ATTACHMENT' => 'Indepartare atasament...',
    'ERR_REMOVING_ATTACHMENT' => 'A esuat in a indeparta atasament....',
    'LBL_CREATED_BY' => 'Creat de',
    'LBL_MODIFIED_BY' => 'Modificat de',
    'LBL_SEND_ANYWAYS' => 'Emailul nu are subiect...Salveaza/trimite oricum?',
    'LBL_LIST_EDIT_BUTTON' => 'Editeaza',
    'LBL_ACTIVITIES_REPORTS' => 'Raport Activitati',
    'LBL_PANEL_DETAILS' => 'Detalii',
    'LBL_NOTE_INFORMATION' => 'Trecere in revista nota',
    'LBL_MY_NOTES_DASHLETNAME' => 'Notarile mele',
    'LBL_EDITLAYOUT' => 'Editeaza Plan General' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Prenume',
    'LBL_LAST_NAME' => 'Nume de Familie',
    'LBL_EXPORT_PARENT_TYPE' => 'Legat de modul',
    'LBL_EXPORT_PARENT_ID' => 'Legat de ID',
    'LBL_DATE_ENTERED' => 'Data creării',
    'LBL_DATE_MODIFIED' => 'Data Modificarii',
    'LBL_DELETED' => 'Şters',
    'LBL_REVENUELINEITEMS' => 'Elemente venit',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modulul {{plural_module_name}} este format din {{plural_module_name}} individuali/e care conţin text sau un ataşament potrivit înregistrării aferente. Înregistrările {{module_name}} pot fi legate de o înregistrare în majoritatea modulelor prin câmpul relaţionat flexibil sau mai pot fi legate de un/o singur/ă {{contacts_singular_module}}. {{plural_module_name}} poate conţine text generic despre înregistrare, sau chiar un ataşament privind înregistrarea. Există mai multe modalităţi prin care puteţi crea {{plural_module_name}} în Sugar, cum ar fi prin intermediul modulului {{plural_module_name}}, prin import de {{plural_module_name}}, prin panouri secundare de Istoric etc. Odată ce a fost creată înregistrarea {{module_name}}, puteţi vizualiza şi edita informaţiile privitoare la {{module_name}} prin intermediul ferestrei de vizualizare a înregistrării {{plural_module_name}}. Fiecare înregistrare {{module_name}} poate apoi relaţiona cu alte înregistrări Sugar, cum ar fi {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} şi multe altele.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modulul {{plural_module_name}} este format din {{plural_module_name}} individuali/e care conţin text sau un ataşament potrivit înregistrării aferente. - Editează câmpurile acestei înregistrări apăsând pe fiecare câmp individual sau pe butonul Editare. - Vizualizează sau modifică linkuri către alte înregistrări in panourile secundare, trecând fereastra din stânga jos în stadiul "Vizualizare Date". - Creează şi vizualizează comentariile utilizatorilor şi istoricul modificărilor în {{activitystream_singular_module}} trecând fereastra din stânga jos în stadiul "Flux de activitate". - Urmăreşte sau marchează ca favorit această înregistrare folosind pictogramele din dreapta numelui înregistrării. - Sunt disponibile acţiuni suplimentare în meniul cu listă derulantă Acţiuni din dreapta butonului Editare.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Pentru a crea {{module_name}}:
1. Introduceţi valori pentru câmpuri după cum doriţi.
- Câmpurile marcate cu "Obligatoriu" trebuie să fie completate înainte de a salva.
- Faceţi clic pe "Afişare mai multe" pentru a afişa câmpuri suplimentare, dacă este necesar.
2. Faceţi clic pe "Salvare" pentru a finaliza noua înregistrare şi pentru a reveni la pagina anterioară.',
);
