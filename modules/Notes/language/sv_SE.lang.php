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
    'LBL_NOTES_LIST_DASHBOARD' => 'Instrumentpanel med anteckningslistor',

    'ERR_DELETE_RECORD' => 'Ett postnummer måste specificeras för att kunna radera organisationen.',
    'LBL_ACCOUNT_ID' => 'Organisations ID:',
    'LBL_CASE_ID' => 'Ärende ID:',
    'LBL_CLOSE' => 'Stäng:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontakt ID:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Anteckningar',
    'LBL_DESCRIPTION' => 'Beskrivning',
    'LBL_EMAIL_ADDRESS' => 'Emailadress:',
    'LBL_EMAIL_ATTACHMENT' => 'Emailbilaga',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'E-postbilaga för',
    'LBL_FILE_MIME_TYPE' => 'Mime typ',
    'LBL_FILE_EXTENSION' => 'Filändelse',
    'LBL_FILE_SOURCE' => 'Filkälla',
    'LBL_FILE_SIZE' => 'Filstorlek',
    'LBL_FILE_URL' => 'Fil URL',
    'LBL_FILENAME' => 'Bilaga:',
    'LBL_LEAD_ID' => 'Lead ID',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Senast ändrad',
    'LBL_LIST_FILENAME' => 'Bilaga',
    'LBL_LIST_FORM_TITLE' => 'Lista anteckningar',
    'LBL_LIST_RELATED_TO' => 'Relaterad till',
    'LBL_LIST_SUBJECT' => 'Ämne',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Anteckningar',
    'LBL_MODULE_NAME_SINGULAR' => 'Anteckning',
    'LBL_MODULE_TITLE' => 'Anteckningar:Hem',
    'LBL_NEW_FORM_TITLE' => 'Skapa anteckning eller bilaga',
    'LBL_NEW_FORM_BTN' => 'Lägg till en Anteckning',
    'LBL_NOTE_STATUS' => 'Anteckning',
    'LBL_NOTE_SUBJECT' => 'Anteckningsämne',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Bilagor',
    'LBL_NOTE' => 'Anteckning:',
    'LBL_OPPORTUNITY_ID' => 'Affärsmöjlighet ID:',
    'LBL_PARENT_ID' => 'Föräldra ID:',
    'LBL_PARENT_TYPE' => 'Föräldratyp',
    'LBL_EMAIL_TYPE' => 'Typ av e-post',
    'LBL_EMAIL_ID' => 'E-post ID',
    'LBL_PHONE' => 'Telefon:',
    'LBL_PORTAL_FLAG' => 'Visa i portal?',
    'LBL_EMBED_FLAG' => 'Infoga i mail?',
    'LBL_PRODUCT_ID' => 'Produkt ID:',
    'LBL_QUOTE_ID' => 'Offert ID:',
    'LBL_RELATED_TO' => 'Relaterad till:',
    'LBL_SEARCH_FORM_TITLE' => 'Sök anteckning',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Ämne:',
    'LNK_IMPORT_NOTES' => 'Importera anteckningar',
    'LNK_NEW_NOTE' => 'Skapa anteckning eller bilaga',
    'LNK_NOTE_LIST' => 'Anteckningar',
    'LBL_MEMBER_OF' => 'Medlem av:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Tilldelad användare',
    'LBL_OC_FILE_NOTICE' => 'Var god logga in på servern för att se filen',
    'LBL_REMOVING_ATTACHMENT' => 'Raderar bilaga...',
    'ERR_REMOVING_ATTACHMENT' => 'Misslyckades att radera bilagan...',
    'LBL_CREATED_BY' => 'Skapad av',
    'LBL_MODIFIED_BY' => 'Redigerad av',
    'LBL_SEND_ANYWAYS' => 'Mailet saknar ämne. Skicka/spara ändå?',
    'LBL_LIST_EDIT_BUTTON' => 'Redigera',
    'LBL_ACTIVITIES_REPORTS' => 'Aktivitetsrapport',
    'LBL_PANEL_DETAILS' => 'Detaljer',
    'LBL_NOTE_INFORMATION' => 'Översikt',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mina Anteckning',
    'LBL_EDITLAYOUT' => 'Redigera layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Förnamn',
    'LBL_LAST_NAME' => 'Efternamn',
    'LBL_EXPORT_PARENT_TYPE' => 'Relaterad Till Modul',
    'LBL_EXPORT_PARENT_ID' => 'Relaterad Till ID',
    'LBL_DATE_ENTERED' => 'Datum skapat',
    'LBL_DATE_MODIFIED' => 'Senast ändrad',
    'LBL_DELETED' => 'Raderad',
    'LBL_REVENUELINEITEMS' => 'Intäktsposter',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}}modul består av enskilda {{plural_module_name}} som innehåller text eller en bilaga relevant för relaterad post. {{modul}}poster kan relateras till en post i de flesta moduler och kan också vara relaterade till en enda {{contacts_singular_module}}. {{plural_module_name}} kan hålla allmän text om en post eller en bilaga i samband med posten. Det finns olika sätt som du kan skapa {{plural_module_name}} i Sugar exempel via {{plural_module_name}}modulen, importera {{plural_module_name}}, via Historia underpaneler, etc. När {{modul}}post skapas, kan du visa och redigera information som hänför sig till {{module}} via {{plural_module_name}} potsvyn. 
Varje {{module}} post kan då avse andra Sugardokument såsom {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, och många andra.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Den {{plural_module_name}} modul består av enskilda {{plural_module_name}} som innehåller text eller en bilaga relevant för relaterad post.  Redigera denna postens fält genom att klicka ett enskilt fält eller på knappen Redigera. - Visa eller ändra länkar till andra poster i underpaneler, även {{campaigns_singular_module}} mottagare, genom att växla den nedre vänstra rutan till "Data View". 
- Utför och se användarkommentarer och eller se förändringar i {{activitystream_singular_module}} genom att växla den nedre vänstra rutan på "Activity Stream". - Följ som favorit med hjälp av ikonerna till höger om namnet. - Ytterligare åtgärder finns i dropdown menyn Åtgärder till höger om knappen Redigera.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'För att skapa en {{module_name}}:
1. Fyll i fälten med aktuella värden.
 - Fält markerade som &#39;Obligatoriska&#39; måste fyllas i innan du sparar.
 - Klicka på &#39;Visa fler&#39; för att få fler fält om det behövs.
2. Klicka på &#39;Spara&#39; för att färdigställa posten och gå tillbaks till den förra sidan.',
);
