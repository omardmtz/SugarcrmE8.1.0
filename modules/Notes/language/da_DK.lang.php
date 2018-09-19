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
    'LBL_NOTES_LIST_DASHBOARD' => 'Noteliste-dashboard',

    'ERR_DELETE_RECORD' => 'Du skal angive et postnummer for at slette virksomheden.',
    'LBL_ACCOUNT_ID' => 'Virksomheds-id:',
    'LBL_CASE_ID' => 'Sags-id:',
    'LBL_CLOSE' => 'Luk:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontakt-id:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Noter',
    'LBL_DESCRIPTION' => 'Note',
    'LBL_EMAIL_ADDRESS' => 'E-mail-adresse:',
    'LBL_EMAIL_ATTACHMENT' => 'Vedhæftet fil til e-mail',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Vedhæftet fil til',
    'LBL_FILE_MIME_TYPE' => 'Mime-type',
    'LBL_FILE_EXTENSION' => 'Filtype',
    'LBL_FILE_SOURCE' => 'Filkilde',
    'LBL_FILE_SIZE' => 'Filstørrelse',
    'LBL_FILE_URL' => 'Fil-URL',
    'LBL_FILENAME' => 'Vedhæftet fil:',
    'LBL_LEAD_ID' => 'Kundeemne-id:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Sidst ændret:',
    'LBL_LIST_FILENAME' => 'Vedhæftet fil',
    'LBL_LIST_FORM_TITLE' => 'Noteliste',
    'LBL_LIST_RELATED_TO' => 'Relateret til',
    'LBL_LIST_SUBJECT' => 'Emne',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Noter',
    'LBL_MODULE_NAME_SINGULAR' => 'Note',
    'LBL_MODULE_TITLE' => 'Noter: Startside',
    'LBL_NEW_FORM_TITLE' => 'Opret note eller vedhæftet fil',
    'LBL_NEW_FORM_BTN' => 'Tilføj en note',
    'LBL_NOTE_STATUS' => 'Note',
    'LBL_NOTE_SUBJECT' => 'Noteemne:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Noter & vedhæftede filer',
    'LBL_NOTE' => 'Note:',
    'LBL_OPPORTUNITY_ID' => 'Salgsmuligheds-id:',
    'LBL_PARENT_ID' => 'Overordnet id:',
    'LBL_PARENT_TYPE' => 'Overordnet type',
    'LBL_EMAIL_TYPE' => 'E-mailtype',
    'LBL_EMAIL_ID' => 'E-mail-ID',
    'LBL_PHONE' => 'Telefon:',
    'LBL_PORTAL_FLAG' => 'Vis i portal?',
    'LBL_EMBED_FLAG' => 'Indsæt i e-mail?',
    'LBL_PRODUCT_ID' => 'Produkt-id:',
    'LBL_QUOTE_ID' => 'Tilbuds-id:',
    'LBL_RELATED_TO' => 'Relateret til:',
    'LBL_SEARCH_FORM_TITLE' => 'Søg efter note',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Emne:',
    'LNK_IMPORT_NOTES' => 'Importér noter',
    'LNK_NEW_NOTE' => 'Opret note eller vedhæftet fil',
    'LNK_NOTE_LIST' => 'Noter',
    'LBL_MEMBER_OF' => 'Medlem af:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Tildelt bruger',
    'LBL_OC_FILE_NOTICE' => 'Log på serveren for at få vist filen',
    'LBL_REMOVING_ATTACHMENT' => 'Fjerner vedhæftet fil...',
    'ERR_REMOVING_ATTACHMENT' => 'Det lykkedes ikke at fjerne den vedhæftede fil...',
    'LBL_CREATED_BY' => 'Oprettet af',
    'LBL_MODIFIED_BY' => 'Ændret af',
    'LBL_SEND_ANYWAYS' => 'Denne e-mail har intet emne. Vil du sende/gemme alligevel?',
    'LBL_LIST_EDIT_BUTTON' => 'Rediger',
    'LBL_ACTIVITIES_REPORTS' => 'Aktivitetsrapport',
    'LBL_PANEL_DETAILS' => 'Detaljer',
    'LBL_NOTE_INFORMATION' => 'Note oversigt',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mine noter',
    'LBL_EDITLAYOUT' => 'Rediger layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Fornavn',
    'LBL_LAST_NAME' => 'Efternavn',
    'LBL_EXPORT_PARENT_TYPE' => 'Relateret til modul',
    'LBL_EXPORT_PARENT_ID' => 'Relateret til id',
    'LBL_DATE_ENTERED' => 'Oprettet den',
    'LBL_DATE_MODIFIED' => 'Ændret den',
    'LBL_DELETED' => 'Slettet',
    'LBL_REVENUELINEITEMS' => 'Omsætningsposter',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Den {{plural_module_name}} modul består af individuel {{plural_module_name}}, der indeholder tekst eller en vedhæftet fil relevant til den relaterede post. {{module_name}} poster kan relateres til én post i de fleste moduler via flex relatere område og kan også være relateret til et enkelt {{contacts_singular_module}}. {{plural_module_name}} kan holde generisk tekst om en post eller endda en vedhæftet fil relateret til posten. Der er forskellige måder, du kan oprette {{plural_module_name}} i Sugar såsom via {{plural_module_name}} modul, importerer {{plural_module_name}} via Historik underpaneler osv. Når den {{module_name}} post er oprettet, du kan se og redigere oplysninger om den {{module_name}} via {{plural_module_name}} post liste. Hver {{module_name}} post kan så forholde sig til andre Sugar poster, såsom {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, og mange andre.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modulet {{plural_module_name}} består af individuel {{plural_module_name}}, der indeholder tekst eller en vedhæftet fil, der er relevant for den relaterede post. 

- Rediger denne posts felter ved at klikke på et enkelt felt eller på knappen Rediger. 
- Se eller ændre links til andre poster i underpaneler ved at skifte den nederste venstre rude til "Data View". 
- Foretag og vis brugernes kommentarer og post ændring historie i {{activitystream_singular_module}} ved at skifte den nederste venstre rude til "Activity Stream". 
- Følg eller favorisér denne post med ikonerne til højre for posten navn. 
- Yderligere handlinger er tilgængelige i dropdown menuen Handlinger til højre for knappen Rediger.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'For at oprette en {{module_name}}: 
1. Giv værdier til felterne som ønsket. 
- Felter mærket "Påkrævet" skal være afsluttet, før du gemmer. 
- Klik på "Vis mere" for at eksponere yderligere felter, hvis det er nødvendigt. 
2. Klik på "Gem" for at færdiggøre den nye post og vend tilbage til den forrige side.',
);
