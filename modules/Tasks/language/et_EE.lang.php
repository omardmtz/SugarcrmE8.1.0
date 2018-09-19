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
  'LBL_TASKS_LIST_DASHBOARD' => 'Ülesannete loendi töölaud',

  'LBL_MODULE_NAME' => 'Ülesanded',
  'LBL_MODULE_NAME_SINGULAR' => 'Ülesanne',
  'LBL_TASK' => 'Ülesanded:',
  'LBL_MODULE_TITLE' => ' Ülesanded: avaleht',
  'LBL_SEARCH_FORM_TITLE' => 'Ülesande otsing',
  'LBL_LIST_FORM_TITLE' => ' Ülesannete loend',
  'LBL_NEW_FORM_TITLE' => 'Loo ülesanne',
  'LBL_NEW_FORM_SUBJECT' => 'Teema:',
  'LBL_NEW_FORM_DUE_DATE' => 'Tähtaeg:',
  'LBL_NEW_FORM_DUE_TIME' => 'Tähtaja kellaaeg:',
  'LBL_NEW_TIME_FORMAT' => '(24.00)',
  'LBL_LIST_CLOSE' => 'Sulge',
  'LBL_LIST_SUBJECT' => 'Teema',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Tähtsus',
  'LBL_LIST_RELATED_TO' => 'Seotud',
  'LBL_LIST_DUE_DATE' => 'Tähtaeg:',
  'LBL_LIST_DUE_TIME' => 'Tähtaja kellaaeg',
  'LBL_SUBJECT' => 'Teema:',
  'LBL_STATUS' => 'Olek:',
  'LBL_DUE_DATE' => 'Tähtaeg:',
  'LBL_DUE_TIME' => 'Tähtaja kellaaeg:',
  'LBL_PRIORITY' => 'Tähtsus:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Tähtaeg ja tähtaja aeg:',
  'LBL_START_DATE_AND_TIME' => 'Alguskuupäev ja -aeg:',
  'LBL_START_DATE' => 'Alguskuupäev:',
  'LBL_LIST_START_DATE' => 'Alguskuupäev',
  'LBL_START_TIME' => 'Algusaeg:',
  'LBL_LIST_START_TIME' => 'Algusaeg',
  'DATE_FORMAT' => '(aaaa-kk-pp)',
  'LBL_NONE' => 'Ükski',
  'LBL_CONTACT' => 'Kontaktisik:',
  'LBL_EMAIL_ADDRESS' => 'E-posti aadress:',
  'LBL_PHONE' => 'Telefoninumber:',
  'LBL_EMAIL' => 'E-posti aadress:',
  'LBL_DESCRIPTION_INFORMATION' => 'Kirjelduse teave',
  'LBL_DESCRIPTION' => 'Kirjeldus:',
  'LBL_NAME' => 'Nimi:',
  'LBL_CONTACT_NAME' => 'Kontaktisiku nimi',
  'LBL_LIST_COMPLETE' => 'Lõpeta:',
  'LBL_LIST_STATUS' => 'Olek',
  'LBL_DATE_DUE_FLAG' => 'Tähtaega pole',
  'LBL_DATE_START_FLAG' => 'Alguskuupäeva pole',
  'ERR_DELETE_RECORD' => 'Kontakti kustutamiseks täpsustage kirje numbrit.',
  'ERR_INVALID_HOUR' => 'Sisestage tund vahemikus 0 ja 24',
  'LBL_DEFAULT_PRIORITY' => 'Keskmine',
  'LBL_LIST_MY_TASKS' => 'Minu avatud ülesanded',
  'LNK_NEW_TASK' => 'Loo ülesanne',
  'LNK_TASK_LIST' => 'Vaata ülesandeid',
  'LNK_IMPORT_TASKS' => 'Impordi ülesanded',
  'LBL_CONTACT_FIRST_NAME'=>'Kontakti eesnimi',
  'LBL_CONTACT_LAST_NAME'=>'Kontakti perekonnanimi',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Määratud kasutaja',
  'LBL_ASSIGNED_TO_NAME'=>'Määratud kasutajale:',
  'LBL_LIST_DATE_MODIFIED' => 'Muutmiskuupäev',
  'LBL_CONTACT_ID' => 'Kontakti ID:',
  'LBL_PARENT_ID' => 'Ema ID:',
  'LBL_CONTACT_PHONE' => 'Kontakttelefon:',
  'LBL_PARENT_NAME' => 'Ema tüüp:',
  'LBL_ACTIVITIES_REPORTS' => 'Tegevuste aruanne',
  'LBL_EDITLAYOUT' => 'Muuda paigutust' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Ülevaade',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Märkused',
  'LBL_REVENUELINEITEMS' => 'Tuluartiklid',
  //For export labels
  'LBL_DATE_DUE' => 'Kehtivuse tähtaeg',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Määratud kasutaja nimi',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Määratud kasutaja ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Muutja ID',
  'LBL_EXPORT_CREATED_BY' => 'Looja ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Seotud moodul',
  'LBL_EXPORT_PARENT_ID' => 'Seotud ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Ülesanne on edukalt suletud.',
  'LBL_ASSIGNED_USER' => 'Määratud kasutajale',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Märkused',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Moodulid {{plural_module_name}} koosnevad paindlikest toimingutest, tehtavatest üksustest või muud tüüpi lõpetamist vajavast tegevusest. Mooduli {{module_name}} kirjed võivad olla seotud ühe kirjega enamikes moodulites paindliku seostamise välja kaudu ja võivad olla seotud ka üksiku mooduliga {{contacts_singular_module}}. Mooduli {{plural_module_name}} loomiseks Sugaris on mitu moodust, nt mooduli {{plural_module_name}}, dubleerimise, mooduli {{plural_module_name}} importimise jne kaudu. Kui {{module_name}} kirje on loodud, saate mooduliga {{module_name}} seotud teavet vaadata ja redigeerida mooduli {{plural_module_name}} kirje vaate kaudu. Mooduli {{module_name}} üksikasjadest olenevalt võib teil olla võimalik vaadata ja redigeerida mooduli {{module_name}} teavet mooduli Kalender kaudu. Iga {{module_name}} kirje võib seejärel seostuda muude Sugari kirjetega, nagu {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} ja paljude muudega.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moodulid {{plural_module_name}} koosnevad paindlikest toimingutest, tehtavatest üksustest või muud tüüpi lõpetamist vajavast tegevusest.

- Redigeerige selle kirje välju, klõpsates üksikut välja või nuppu Redigeeri.
- Vaadake või muutke alampaneelides linke teistele kirjetele, valides alumisel vasakpoolsel paanil kuva Andmevaade.
- Koostage ja vaadake kasutaja kommentaare ning salvestage muutuse ajalugu moodulis {{activitystream_singular_module}}, valides alumisel vasakpoolsel paanil kuva Tegevuste voog.
- Jälgige või lisage see kirje lemmikute hulka, kasutades kirje nimest paremal asuvaid ikoone.
- Täiendavad toimingud on saadaval tegevuste rippmenüüs, mis asub nupust Redigeeri paremal.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

Mooduli {{module_name}} loomiseks tehke järgmist.
1. Esitage väljade väärtused soovi järgi.
 - Väljad märkega Kohustuslik tuleb täita enne salvestamist.
 - Vajaduse korral lisaväljade avaldamiseks klõpsake suvandit Kuva rohkem.
2. Uue kirje lõpetamiseks ja eelmisele lehele naasmiseks klõpsake nuppu Salvesta.',

);
