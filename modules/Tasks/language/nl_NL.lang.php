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
  'LBL_TASKS_LIST_DASHBOARD' => 'Dashboard Takenlijst',

  'LBL_MODULE_NAME' => 'Taken',
  'LBL_MODULE_NAME_SINGULAR' => 'Taak',
  'LBL_TASK' => 'Taken:',
  'LBL_MODULE_TITLE' => 'Taken: Home',
  'LBL_SEARCH_FORM_TITLE' => 'Taken Zoeken',
  'LBL_LIST_FORM_TITLE' => 'Takenlijst',
  'LBL_NEW_FORM_TITLE' => 'Nieuwe Taak',
  'LBL_NEW_FORM_SUBJECT' => 'Onderwerp:',
  'LBL_NEW_FORM_DUE_DATE' => 'Einddatum:',
  'LBL_NEW_FORM_DUE_TIME' => 'Eindtijd:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Sluiten',
  'LBL_LIST_SUBJECT' => 'Onderwerp',
  'LBL_LIST_CONTACT' => 'Persoon',
  'LBL_LIST_PRIORITY' => 'Prioriteit',
  'LBL_LIST_RELATED_TO' => 'Gerelateerd aan',
  'LBL_LIST_DUE_DATE' => 'Einddatum',
  'LBL_LIST_DUE_TIME' => 'Eindtijd',
  'LBL_SUBJECT' => 'Onderwerp:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Einddatum:',
  'LBL_DUE_TIME' => 'Eindtijd:',
  'LBL_PRIORITY' => 'Prioriteit:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Einddatum & tijd:',
  'LBL_START_DATE_AND_TIME' => 'Aanvangsdatum & tijd:',
  'LBL_START_DATE' => 'Aanvangsdatum:',
  'LBL_LIST_START_DATE' => 'Aanvangsdatum:',
  'LBL_START_TIME' => 'Aanvangstijd:',
  'LBL_LIST_START_TIME' => 'Aanvangstijd:',
  'DATE_FORMAT' => '(jjjj-mm-dd)',
  'LBL_NONE' => 'Geen',
  'LBL_CONTACT' => 'Persoon:',
  'LBL_EMAIL_ADDRESS' => 'E-mailadres',
  'LBL_PHONE' => 'Telefoon:',
  'LBL_EMAIL' => 'E-mailadres:',
  'LBL_DESCRIPTION_INFORMATION' => 'Omschrijving',
  'LBL_DESCRIPTION' => 'Omschrijving:',
  'LBL_NAME' => 'Naam:',
  'LBL_CONTACT_NAME' => 'Naam Persoon:',
  'LBL_LIST_COMPLETE' => 'Afgerond:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'Geen einddatum',
  'LBL_DATE_START_FLAG' => 'Geen aanvangsdatum',
  'ERR_DELETE_RECORD' => 'U dient een recordnummer op te geven om dit record te kunnen verwijderen',
  'ERR_INVALID_HOUR' => 'Voer hier a.u.b een uur in tussen 0 en 24',
  'LBL_DEFAULT_PRIORITY' => 'Middel',
  'LBL_LIST_MY_TASKS' => 'Mijn Openstaande Taken',
  'LNK_NEW_TASK' => 'Nieuwe Taak',
  'LNK_TASK_LIST' => 'Bekijk Taken',
  'LNK_IMPORT_TASKS' => 'Importeer taken',
  'LBL_CONTACT_FIRST_NAME'=>'Voornaam persoon',
  'LBL_CONTACT_LAST_NAME'=>'Achternaam persoon',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Toegewezen aan',
  'LBL_ASSIGNED_TO_NAME'=>'Toegewezen aan:',
  'LBL_LIST_DATE_MODIFIED' => 'Datum gewijzigd',
  'LBL_CONTACT_ID' => 'ID Persoon',
  'LBL_PARENT_ID' => 'Bovenliggende ID',
  'LBL_CONTACT_PHONE' => 'Telefoonnummer persoon',
  'LBL_PARENT_NAME' => 'Bovenliggend Type:',
  'LBL_ACTIVITIES_REPORTS' => 'Activiteitenrapport',
  'LBL_EDITLAYOUT' => 'Wijzig weergave' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Overzicht',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notities',
  'LBL_REVENUELINEITEMS' => 'Opportunityregels',
  //For export labels
  'LBL_DATE_DUE' => 'Vervaldatum',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Toegewezen aan',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Toegewezen aan ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Gewijzigd door ID',
  'LBL_EXPORT_CREATED_BY' => 'Aangemaakt door ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Gerelateerd aan module',
  'LBL_EXPORT_PARENT_ID' => 'Gerelateerd aan ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Taak succesvol afgesloten.',
  'LBL_ASSIGNED_USER' => 'Toegewezen aan',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notities',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'De {{plural_module_name}} module bestaat uit flexibele acties, to-do items, of andere activiteiten die uitgevoerd moeten worden. {{module_name}} records kunnen gekoppeld worden aan een record in de meeste modules door middel van het flex relate veld en kan ook gekoppeld worden aan een enkel {{contacts_singular_module}}. Er zijn meerdere manieren waarop u een {{plural_module_name}} in Sugar kunt aanmaken, zoals bijvoorbeeld via de {{plural_module_name}} module, door te kopiëren, importeren van {{plural_module_name}}, etc. Zo een {{module_name}} is aangemaakt, kunt u deze bekijken en de gegevens behorende bij de {{module_name}} aanpassen via de {{plural_module_name}} record view. Afhankelijk van de gegevens van de {{module_name}}, kunt u de gegevens van de {{module_name}} ook aanpassen via de Calendar module. Elk {{module_name}} record kan gekoppeld worden aan andere Sugar records zoals bijvoorbeeld {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, en vele anderen.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'De {{plural_module_name}} module bestaat uit flexibele acties, to-do items, of andere activiteiten die uitgevoerd moeten worden.

- Pas de gegevens aan door op een individueel veld of op de Wijzigen knop te klikken.
- Bekijk of pas koppelingen met andere records aan in de subpanels door de "Data View" in te schakelen in het paneel linksonder..
- Maak en bekijk opmerkingen van gebruikers en de wijzigingshistorie door "Activiteitenstroom" in te schakelen in het paneel linksonder.
- Volg of maak het record favoriet door gebruik te maken van de icoontjes rechts naast de naam van het record.
- Extra acties zijn beschikbaar via het dropdown actie menu rechts naast de Wijzigen knop.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'The {{plural_module_name}} module bestaat uit flexibele acties, to-do items, of andere activiteiten die uitgevoerd moeten worden.

Om een {{module_name}} aan te maken:
1. Voer de gewenste gegevens in.
 - Velden die "verplicht" zijn, moeten ingevuld zijn voordat het record opgeslagen kan worden.
 - Klik op "Toon meer" om extra velden te tonen.
2. Klik op "Opslaan" om het nieuwe record op te slaan en terug te keren naar de voorgaande pagina.',

);
