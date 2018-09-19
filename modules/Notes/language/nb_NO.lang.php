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
    'LBL_NOTES_LIST_DASHBOARD' => 'Dashbord for notatliste',

    'ERR_DELETE_RECORD' => 'Du må oppgi et registernummer for å slette denne bedriften.',
    'LBL_ACCOUNT_ID' => 'Bedrift-ID:',
    'LBL_CASE_ID' => 'Sak-ID:',
    'LBL_CLOSE' => 'Lukk:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontakt-ID:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notater',
    'LBL_DESCRIPTION' => 'Beskrivelse',
    'LBL_EMAIL_ADDRESS' => 'E-postadresse:',
    'LBL_EMAIL_ATTACHMENT' => 'E-postvedlegg',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'E-postvedlegg for',
    'LBL_FILE_MIME_TYPE' => 'Mimetype',
    'LBL_FILE_EXTENSION' => 'Filtype',
    'LBL_FILE_SOURCE' => 'Filkilde',
    'LBL_FILE_SIZE' => 'Filstørrelse',
    'LBL_FILE_URL' => 'Fil-URL',
    'LBL_FILENAME' => 'Vedlegg:',
    'LBL_LEAD_ID' => 'Emne-ID:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Sist endret',
    'LBL_LIST_FILENAME' => 'Vedlegg',
    'LBL_LIST_FORM_TITLE' => 'Notatliste',
    'LBL_LIST_RELATED_TO' => 'Beslektet med',
    'LBL_LIST_SUBJECT' => 'Emne',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Notater',
    'LBL_MODULE_NAME_SINGULAR' => 'Notat',
    'LBL_MODULE_TITLE' => 'Notater: Hjem',
    'LBL_NEW_FORM_TITLE' => 'Opprett notat eller vedlegg',
    'LBL_NEW_FORM_BTN' => 'Legg til et notat',
    'LBL_NOTE_STATUS' => 'Notat',
    'LBL_NOTE_SUBJECT' => 'Emne for notat:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Vedlegg',
    'LBL_NOTE' => 'Notat:',
    'LBL_OPPORTUNITY_ID' => 'Mulighets-ID:',
    'LBL_PARENT_ID' => 'Opphavs-ID:',
    'LBL_PARENT_TYPE' => 'Opphavstype:',
    'LBL_EMAIL_TYPE' => 'E-posttype',
    'LBL_EMAIL_ID' => 'E-post-ID',
    'LBL_PHONE' => 'Telfonnummer:',
    'LBL_PORTAL_FLAG' => 'Vis i portal?',
    'LBL_EMBED_FLAG' => 'Kapsle inn i e-post?',
    'LBL_PRODUCT_ID' => 'Produkt-ID:',
    'LBL_QUOTE_ID' => 'Tilbuds-ID:',
    'LBL_RELATED_TO' => 'Beslektet med:',
    'LBL_SEARCH_FORM_TITLE' => 'Notatsøk',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Emne:',
    'LNK_IMPORT_NOTES' => 'Importér notater',
    'LNK_NEW_NOTE' => 'Opprett notat eller vedlegg',
    'LNK_NOTE_LIST' => 'Notater',
    'LBL_MEMBER_OF' => 'Medlem av:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Tildelt bruker',
    'LBL_OC_FILE_NOTICE' => 'Vennligst logg inn på serveren for å se filen',
    'LBL_REMOVING_ATTACHMENT' => 'Fjerner vedlegg...',
    'ERR_REMOVING_ATTACHMENT' => 'Mislyktes i gjerne vedlegg...',
    'LBL_CREATED_BY' => 'Opprettet av',
    'LBL_MODIFIED_BY' => 'Endret av',
    'LBL_SEND_ANYWAYS' => 'Denne e-postmeldingen har intet emne. Vil du sende/lagre likevel?',
    'LBL_LIST_EDIT_BUTTON' => 'Rediger',
    'LBL_ACTIVITIES_REPORTS' => 'Aktivitets Rapport',
    'LBL_PANEL_DETAILS' => 'Detaljer',
    'LBL_NOTE_INFORMATION' => 'Note oversikt',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mine notater',
    'LBL_EDITLAYOUT' => 'Redigér oppsett' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Fornavn',
    'LBL_LAST_NAME' => 'Etternavn',
    'LBL_EXPORT_PARENT_TYPE' => 'Relatert til Modul',
    'LBL_EXPORT_PARENT_ID' => 'Relatert til ID',
    'LBL_DATE_ENTERED' => 'Opprettet dato',
    'LBL_DATE_MODIFIED' => 'Endret',
    'LBL_DELETED' => 'Slettet',
    'LBL_REVENUELINEITEMS' => 'Omsetningsposter',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} modulen består av individuell {{plural_module_name}} som inneholder tekst eller et vedlegg relevant for den relaterte posten. {{module_name}} poster kan være relatert til en registrering i de fleste moduler via flex-relaterte felt, og kan også være relatert til en enkelt {{contacts_singular_module}}. {{plural_module_name}} kan inneholde generisk tekst om en post eller et vedlegg knyttet til posten. Det finnes ulike måter du kan opprette {{plural_module_name}} i Sugar eksempel via {{plural_module_name}} modul, import {{plural_module_name}}, via Historie underpaneler etc. Når {{module_name}} posten er opprettet, kan du se på og redigere informasjon knyttet til {{module_name}} via {{plural_module_name}} postvisning. Hver {{module_name}} registrering kan da forholde seg til andre Sugar poster som {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, og mange andre.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Den {{plural_module_name}} modulen består av individuelle {{plural_module_name}} som inneholder tekst eller et vedlegg relevant for den relaterte posten. - Rediger denne registrering felt ved å klikke på en enkelt felt eller Rediger-knappen. - Vis eller endre lenker til andre poster i underpaneler ved å veksle nedre venstre rute til "Data View". - Lag og vis brukerkommentarer og postendringshistorie i {{activitystream_singular_module}} ved å veksle nedre venstre rute til "Aktivitetstrøm". - Følg eller favoritt denne posten ved hjelp av ikonene til høyre for registreringsnavnet. - Ytterligere tiltak er tilgjengelig i rullgardinmenyen Handlinger til høyre på Rediger-knappen.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'For å opprette {{module_name}}: 
1. Legg inn verdier i feltene som ønsket. 
- Felt som er merket "Obligatorisk" må oppdateres før du lagrer. 
- Klikk "Vis mer" for å avsløre flere felt hvis det er nødvendig. 
2. Klikk "Lagre" for å sluttføre den nye posten og gå tilbake til forrige side.',
);
