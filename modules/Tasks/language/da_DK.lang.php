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
  'LBL_TASKS_LIST_DASHBOARD' => 'Opgaveliste-dashboard',

  'LBL_MODULE_NAME' => 'Opgaver',
  'LBL_MODULE_NAME_SINGULAR' => 'Opgave',
  'LBL_TASK' => 'Opgaver:',
  'LBL_MODULE_TITLE' => 'Opgaver: Startside',
  'LBL_SEARCH_FORM_TITLE' => 'Søg efter opgave',
  'LBL_LIST_FORM_TITLE' => 'Liste over opgaver',
  'LBL_NEW_FORM_TITLE' => 'Opret opgave',
  'LBL_NEW_FORM_SUBJECT' => 'Emne:',
  'LBL_NEW_FORM_DUE_DATE' => 'Forfaldsdato:',
  'LBL_NEW_FORM_DUE_TIME' => 'Forfaldstidspunkt:',
  'LBL_NEW_TIME_FORMAT' => '"24:00"',
  'LBL_LIST_CLOSE' => 'Luk',
  'LBL_LIST_SUBJECT' => 'Emne',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Prioritet',
  'LBL_LIST_RELATED_TO' => 'Relateret til',
  'LBL_LIST_DUE_DATE' => 'Forfaldsdato',
  'LBL_LIST_DUE_TIME' => 'Forfaldstidspunkt',
  'LBL_SUBJECT' => 'Emne:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Forfaldsdato:',
  'LBL_DUE_TIME' => 'Forfaldstidspunkt:',
  'LBL_PRIORITY' => 'Prioritet:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Forfaldsdato og -klokkeslæt:',
  'LBL_START_DATE_AND_TIME' => 'Startdato og -klokkeslæt:',
  'LBL_START_DATE' => 'Startdato:',
  'LBL_LIST_START_DATE' => 'Startdato',
  'LBL_START_TIME' => 'Starttidspunkt:',
  'LBL_LIST_START_TIME' => 'Starttidspunkt',
  'DATE_FORMAT' => '"åååå-mm-dd"',
  'LBL_NONE' => 'Ingen',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'E-mail-adresse:',
  'LBL_PHONE' => 'Telefon:',
  'LBL_EMAIL' => 'E-mail-adresse:',
  'LBL_DESCRIPTION_INFORMATION' => 'Beskrivelsesoplysninger',
  'LBL_DESCRIPTION' => 'Beskrivelse:',
  'LBL_NAME' => 'Navn:',
  'LBL_CONTACT_NAME' => 'Kontaktnavn',
  'LBL_LIST_COMPLETE' => 'Fuldført:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'Ingen forfaldsdato',
  'LBL_DATE_START_FLAG' => 'Ingen startdato',
  'ERR_DELETE_RECORD' => 'Du skal angive et postnummer for at slette kontakten.',
  'ERR_INVALID_HOUR' => 'Angiv et tidspunkt mellem 0 og 24',
  'LBL_DEFAULT_PRIORITY' => 'Medium',
  'LBL_LIST_MY_TASKS' => 'Mine åbne opgaver',
  'LNK_NEW_TASK' => 'Opret opgave',
  'LNK_TASK_LIST' => 'Opgaver',
  'LNK_IMPORT_TASKS' => 'Importér opgaver',
  'LBL_CONTACT_FIRST_NAME'=>'Kontaktens fornavn',
  'LBL_CONTACT_LAST_NAME'=>'Kontaktens efternavn',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Tildelt bruger',
  'LBL_ASSIGNED_TO_NAME'=>'Tildelt til:',
  'LBL_LIST_DATE_MODIFIED' => 'Ændret den',
  'LBL_CONTACT_ID' => 'Kontakt-id:',
  'LBL_PARENT_ID' => 'Overordnet id:',
  'LBL_CONTACT_PHONE' => 'Kontaktens telefon:',
  'LBL_PARENT_NAME' => 'Overordnet type:',
  'LBL_ACTIVITIES_REPORTS' => 'Aktivitetsrapport',
  'LBL_EDITLAYOUT' => 'Rediger layout' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Opgave oversigt',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Noter',
  'LBL_REVENUELINEITEMS' => 'Omsætningsposter',
  //For export labels
  'LBL_DATE_DUE' => 'Forfaldsdato',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Tildelt brugernavn',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Tildelt bruger-id',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Ændret af id',
  'LBL_EXPORT_CREATED_BY' => 'Oprettet af id',
  'LBL_EXPORT_PARENT_TYPE' => 'Relateret til modul',
  'LBL_EXPORT_PARENT_ID' => 'Relateret til id',
  'LBL_TASK_CLOSE_SUCCESS' => 'Opgave afsluttet med succes.',
  'LBL_ASSIGNED_USER' => 'Tildelt til',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Noter',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Den {{plural_module_name}} modul består af fleksible tiltag, to-do elementer eller anden form for aktivitet, som kræver afslutning. {{MODULE_NAME}} optegnelser kan relateres til én post i de fleste moduler via flex relatere område og kan også være relateret til et enkelt {{contacts_singular_module}}. Der er forskellige måder, du kan oprette {{plural_module_name}} i Sugar såsom via {{plural_module_name}} modul, duplikering, import {{plural_module_name}} osv. Når den {{MODULE_NAME}} post er oprettet, kan du få vist og redigere oplysninger om den {{MODULE_NAME}} via {{plural_module_name}} post visning. Afhængigt af detaljer om {{MODULE_NAME}}, kan du også være i stand til at se og redigere den {{MODULE_NAME}} informationer via kalender-modulet. Hver {{MODULE_NAME}} post kan så forholde sig til andre Sugar optegnelser, såsom {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, og mange andre.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Den {{plural_module_name}} modul består af fleksible tiltag, to-do elementer eller anden form for aktivitet, som kræver afslutning. - Rediger denne posts felter ved at klikke på et enkelt felt eller på knappen Rediger. - Se eller ændre links til andre poster i underpaneler ved at skifte nederste venstre rude til "Data View". - Foretag og vis brugernes kommentarer og post ændringshistorie i {{activitystream_singular_module}} ved at skifte det nederste venstre rude til "Activity Stream". - Følg eller favourite denne post med ikonerne til højre for posten navn. - Yderligere handlinger er tilgængelige i dropdown menuen Handlinger til højre for knappen Rediger.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Den {{plural_module_name}} modul består af fleksible tiltag, to-do elementer eller anden form for aktivitet, som kræver afslutning.

For at oprette en {{module_name}}:
1. Give værdier for felterne som ønsket.
- Felter mærket "Påkrævet" skal være afsluttet, før du gemmer.
- Klik på "Vis mere" for at eksponere yderligere felter, hvis det er nødvendigt.
2. Klik på "Gem" for at færdiggøre den nye post og vende tilbage til den forrige side.',

);
