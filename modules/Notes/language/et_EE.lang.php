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
    'LBL_NOTES_LIST_DASHBOARD' => 'Märkmete loendi töölaud',

    'ERR_DELETE_RECORD' => 'Konto kustutamiseks täpsustage kirje numbrit.',
    'LBL_ACCOUNT_ID' => 'Konto ID:',
    'LBL_CASE_ID' => 'Juhtumi ID:',
    'LBL_CLOSE' => 'Sulge:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontakti ID:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Märkused',
    'LBL_DESCRIPTION' => 'Kirjeldus',
    'LBL_EMAIL_ADDRESS' => 'E-posti aadress:',
    'LBL_EMAIL_ATTACHMENT' => 'E-kirja manus',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'E-kirja manus saajale',
    'LBL_FILE_MIME_TYPE' => 'MIME tüüp',
    'LBL_FILE_EXTENSION' => 'Faililaiend',
    'LBL_FILE_SOURCE' => 'Faili allikas',
    'LBL_FILE_SIZE' => 'Faili maht',
    'LBL_FILE_URL' => 'Faili URL',
    'LBL_FILENAME' => 'Manus:',
    'LBL_LEAD_ID' => 'Müügivihje ID:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Viimati muudetud',
    'LBL_LIST_FILENAME' => 'Manus',
    'LBL_LIST_FORM_TITLE' => 'Märkuste loend',
    'LBL_LIST_RELATED_TO' => 'Seotud',
    'LBL_LIST_SUBJECT' => 'Teema',
    'LBL_LIST_STATUS' => 'Olek',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Märkused',
    'LBL_MODULE_NAME_SINGULAR' => 'Märkus',
    'LBL_MODULE_TITLE' => 'Märkused: avaleht',
    'LBL_NEW_FORM_TITLE' => 'Loo märkus või lisa manus',
    'LBL_NEW_FORM_BTN' => 'Lisa märkus',
    'LBL_NOTE_STATUS' => 'Märkus',
    'LBL_NOTE_SUBJECT' => 'Teema:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Märkused ja manused',
    'LBL_NOTE' => 'Märkus:',
    'LBL_OPPORTUNITY_ID' => 'Müügivõimaluse ID:',
    'LBL_PARENT_ID' => 'Ema ID:',
    'LBL_PARENT_TYPE' => 'Ema tüüp',
    'LBL_EMAIL_TYPE' => 'E-kirja tüüp',
    'LBL_EMAIL_ID' => 'E-kirja ID',
    'LBL_PHONE' => 'Telefoninumber:',
    'LBL_PORTAL_FLAG' => 'Kas kuvada portaalis?',
    'LBL_EMBED_FLAG' => 'Kas manustada e-kirja?',
    'LBL_PRODUCT_ID' => 'Pakkumuse artikli ID:',
    'LBL_QUOTE_ID' => 'Pakkumuse ID:',
    'LBL_RELATED_TO' => 'Seotud:',
    'LBL_SEARCH_FORM_TITLE' => 'Märkuse otsing',
    'LBL_STATUS' => 'Olek',
    'LBL_SUBJECT' => 'Teema:',
    'LNK_IMPORT_NOTES' => 'Impordi märkused',
    'LNK_NEW_NOTE' => 'Loo märkus või manus',
    'LNK_NOTE_LIST' => 'Vaata märkuseid',
    'LBL_MEMBER_OF' => 'Liige:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Määratud kasutaja',
    'LBL_OC_FILE_NOTICE' => 'Faili vaatamiseks logige serverisse sisse',
    'LBL_REMOVING_ATTACHMENT' => 'Manuse eemaldamine ...',
    'ERR_REMOVING_ATTACHMENT' => 'Manuse eemaldamine ebaõnnestus ...',
    'LBL_CREATED_BY' => 'Loonud',
    'LBL_MODIFIED_BY' => 'Muutja',
    'LBL_SEND_ANYWAYS' => 'Kas olete kindel, et soovite e-kirja saata/salvestada teemata?',
    'LBL_LIST_EDIT_BUTTON' => 'Redigeeri',
    'LBL_ACTIVITIES_REPORTS' => 'Tegevuste aruanne',
    'LBL_PANEL_DETAILS' => 'Üksikasjad',
    'LBL_NOTE_INFORMATION' => 'Ülevaade',
    'LBL_MY_NOTES_DASHLETNAME' => 'Minu märkused',
    'LBL_EDITLAYOUT' => 'Muuda paigutust' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Eesnimi',
    'LBL_LAST_NAME' => 'Perekonnanimi',
    'LBL_EXPORT_PARENT_TYPE' => 'Seotud moodul',
    'LBL_EXPORT_PARENT_ID' => 'Seotud ID',
    'LBL_DATE_ENTERED' => 'Loomiskuupäev',
    'LBL_DATE_MODIFIED' => 'Muutmiskuupäev',
    'LBL_DELETED' => 'Kustutatud',
    'LBL_REVENUELINEITEMS' => 'Tuluartiklid',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Moodul {{plural_module_name}} koosneb üksikust moodulist {{plural_module_name}}, mis sisaldab seotud kirje asjakohast teksti või manust. Mooduli {{module_name}} kirjed võivad olla seotud ühe kirjega enamikes moodulites paindliku seostamise välja kaudu ja võivad olla seotud ka üksiku mooduliga {{contacts_singular_module}}. Moodul {{plural_module_name}} võib sisaldada üldist teavet kirje kohta või isegi kirjega seotud manust. Mooduli {{plural_module_name}} loomiseks Sugaris on mitu moodust, nt mooduli {{plural_module_name}}, mooduli {{plural_module_name}} importimise, alampaneelide Ajalugu kaudu jne. Kui mooduli {{module_name}} kirje on loodud, saate mooduliga {{module_name}} seotud teavet vaadata ja redigeerida mooduli {{plural_module_name}} kirje vaate kaudu. Iga {{module_name}} kirje võib seejärel seostuda muude Sugari kirjetega, nagu {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} ja paljude muudega.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moodul {{plural_module_name}} koosneb üksikust moodulist {{plural_module_name}}, mis sisaldab seotud kirje asjakohast teksti või manust.

- Redigeerige kirje välju, klõpsates individuaalsel väljal või nupul Redigeeri.
- Vaadake või muutke alampaneelides linke teistele kirjetele, valides alumisel vasakpoolsel paanil kuva Andmevaade.
- Koostage ja vaadake kasutaja kommentaare ning salvestage muutuse ajalugu moodulis {{activitystream_singular_module}}, valides alumisel vasakpoolsel paanil kuva Tegevuste voog.
- Jälgige või lisage see kirje lemmikute hulka, kasutades kirje nimest paremal asuvaid ikoone.
- Täiendavad toimingud on saadaval tegevuste rippmenüüs, mis asub nupust Redigeeri paremal.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Mooduli {{module_name}} loomiseks tehke järgmist.
1. Esitage väljade väärtused soovi järgi.
 - Väljad märkega Kohustuslik tuleb täita enne salvestamist.
 - Vajaduse korral lisaväljade avaldamiseks klõpsake suvandit Kuva rohkem.
2. Uue kirje lõpetamiseks ja eelmisele lehele naasmiseks klõpsake nuppu Salvesta.',
);
