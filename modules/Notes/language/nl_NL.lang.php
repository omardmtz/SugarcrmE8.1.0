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
    'LBL_NOTES_LIST_DASHBOARD' => 'Dashboard notitielijst',

    'ERR_DELETE_RECORD' => 'U dient een recordnummer op te geven om de organisatie te verwijderen.',
    'LBL_ACCOUNT_ID' => 'Organisatie ID:',
    'LBL_CASE_ID' => 'Casus-ID:',
    'LBL_CLOSE' => 'Sluiten:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Persoon ID:',
    'LBL_CONTACT_NAME' => 'Persoon:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notities',
    'LBL_DESCRIPTION' => 'Notitie',
    'LBL_EMAIL_ADDRESS' => 'E-mailadres:',
    'LBL_EMAIL_ATTACHMENT' => 'E-mailbijlage',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'E-mailbijlage voor',
    'LBL_FILE_MIME_TYPE' => 'Mime type',
    'LBL_FILE_EXTENSION' => 'Bestandsextensie',
    'LBL_FILE_SOURCE' => 'Bron bestand',
    'LBL_FILE_SIZE' => 'Bestandsgrootte',
    'LBL_FILE_URL' => 'Bestand URL',
    'LBL_FILENAME' => 'Bijlage:',
    'LBL_LEAD_ID' => 'Lead ID:',
    'LBL_LIST_CONTACT_NAME' => 'Persoon',
    'LBL_LIST_DATE_MODIFIED' => 'Datum gewijzigd',
    'LBL_LIST_FILENAME' => 'Bijlage',
    'LBL_LIST_FORM_TITLE' => 'Notitielijst',
    'LBL_LIST_RELATED_TO' => 'Gerelateerd aan',
    'LBL_LIST_SUBJECT' => 'Onderwerp',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Persoon',
    'LBL_MODULE_NAME' => 'Notities',
    'LBL_MODULE_NAME_SINGULAR' => 'Notitie',
    'LBL_MODULE_TITLE' => 'Notities: Start',
    'LBL_NEW_FORM_TITLE' => 'Nieuwe Notitie of Bijlage',
    'LBL_NEW_FORM_BTN' => 'Voeg een notitie toe',
    'LBL_NOTE_STATUS' => 'Notitie',
    'LBL_NOTE_SUBJECT' => 'Onderwerp:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notities & bijlagen',
    'LBL_NOTE' => 'Notitie:',
    'LBL_OPPORTUNITY_ID' => 'Opportunity-ID:',
    'LBL_PARENT_ID' => 'Parent ID:',
    'LBL_PARENT_TYPE' => 'Type bovenliggend',
    'LBL_EMAIL_TYPE' => 'Type e-mail',
    'LBL_EMAIL_ID' => 'E-mail-ID',
    'LBL_PHONE' => 'Telefoon:',
    'LBL_PORTAL_FLAG' => 'Weergeven in portal?',
    'LBL_EMBED_FLAG' => 'Invoegen in e-mail?',
    'LBL_PRODUCT_ID' => 'Product ID:',
    'LBL_QUOTE_ID' => 'Offerte ID:',
    'LBL_RELATED_TO' => 'Gerelateerd aan:',
    'LBL_SEARCH_FORM_TITLE' => 'Notitie zoeken',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Onderwerp:',
    'LNK_IMPORT_NOTES' => 'Importeer Notities',
    'LNK_NEW_NOTE' => 'Nieuwe Notitie of Bijlage',
    'LNK_NOTE_LIST' => 'Bekijk Notities',
    'LBL_MEMBER_OF' => 'Onderdeel van:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Toegewezen aan',
    'LBL_OC_FILE_NOTICE' => 'Login op de server om het bestand te bekijken',
    'LBL_REMOVING_ATTACHMENT' => 'Bijlage wordt verwijderd...',
    'ERR_REMOVING_ATTACHMENT' => 'Bijlage kan niet worden verwijderd...',
    'LBL_CREATED_BY' => 'Aangemaakt door',
    'LBL_MODIFIED_BY' => 'Gewijzigd door',
    'LBL_SEND_ANYWAYS' => 'Deze e-mail heeft geen onderwerp. Toch verzenden/bewaren?',
    'LBL_LIST_EDIT_BUTTON' => 'Wijzigen',
    'LBL_ACTIVITIES_REPORTS' => 'Activiteitenrapport',
    'LBL_PANEL_DETAILS' => 'Details',
    'LBL_NOTE_INFORMATION' => 'Notitie overzicht',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mijn Notities',
    'LBL_EDITLAYOUT' => 'Wijzig weergave' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Voornaam',
    'LBL_LAST_NAME' => 'Achternaam',
    'LBL_EXPORT_PARENT_TYPE' => 'Gerelateerd aan de module',
    'LBL_EXPORT_PARENT_ID' => 'Gerelateerd aan ID',
    'LBL_DATE_ENTERED' => 'Datum aangemaakt',
    'LBL_DATE_MODIFIED' => 'Datum gewijzigd',
    'LBL_DELETED' => 'Verwijderd',
    'LBL_REVENUELINEITEMS' => 'Opportunityregels',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'De {{plural_module_name}} module bevat losse {{plural_module_name}} die tekst of een bijlage bevatten behorende bij het gekoppelde record. {{module_name}} records kunnen gekoppeld worden aan één record van de meeste modules met behulp van het flex relate veld en kunnen ook gekoppeld worden aan een enkel {{contacts_singular_module}}. {{plural_module_name}} kunnen algemene tekst over een record bevatten of zelfs een bijlage behorend bij het record. Er zijn meerdere manieren waarop u een {{plural_module_name}} kunt aanmaken in Sugar, zoals bijvoorbeeld via de {{plural_module_name}} module, importeren van {{plural_module_name}}, met behulp van Historie subpanels, etc. Als een {{module_name}} record is aangemaakt, kunt u deze bekijken en de gegevens behorende bij deze {{module_name}} aanpassen via de {{plural_module_name}} record view. Elk {{module_name}} record kan vervolgens gekoppeld worden naar andere Sugar records zoals {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, en vele anderen.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'De {{plural_module_name}} module bevat losse {{plural_module_name}} die tekst of een bijlage bevatten behorende bij het gekoppelde record.

- Pas de gegevens aan door op een individueel veld of op de Wijzigen knop te klikken.
- Bekijk of pas koppelingen met andere records aan in de subpanels door de "Data View" in te schakelen in het paneel linksonder.
- Maak en bekijk opmerkingen van gebruikers en de wijzigingshistorie door "Activity Stream" in te schakelen in het paneel linksonder.
- Volg of maak het record favoriet door gebruik te maken van de icoontjes rechts naast de naam van het record.
- Extra acties zijn beschikbaar via het dropdown actie menu rechts naast de Wijzigen knop.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Om een {{module_name}} aan te maken:
1. Voer de gewenste gegevens in.
 - Velden die "verplicht" zijn, moeten ingevuld zijn voordat het record opgeslagen kan worden.
 - Klik op "Toon meer" om extra velden te tonen.
2. Klik op "Opslaan" om het record op te slaan en terug te keren naar de voorgaande pagina.
 - Kies "Sla op en bekijk" om de nieuwe {{module_name}} in record view te bekijken.
 - Kies "Sla op en maak nieuwe aan" om direct een nieuwe {{module_name}} aan te maken.',
);
