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
  'LBL_BUGS_LIST_DASHBOARD' => 'Panel för bugglistor',
  'LBL_BUGS_RECORD_DASHBOARD' => 'Panel för buggposter',

  'LBL_MODULE_NAME' => 'Buggar',
  'LBL_MODULE_NAME_SINGULAR'	=> 'Bugg',
  'LBL_MODULE_TITLE' => 'Bugg tracker: Hem',
  'LBL_MODULE_ID' => 'Buggar',
  'LBL_SEARCH_FORM_TITLE' => 'Sök bugg',
  'LBL_LIST_FORM_TITLE' => 'Lista buggar',
  'LBL_NEW_FORM_TITLE' => 'Ny bugg',
  'LBL_CONTACT_BUG_TITLE' => 'Kontakt-Bugg:',
  'LBL_SUBJECT' => 'Ämne:',
  'LBL_BUG' => 'Bugg:',
  'LBL_BUG_NUMBER' => 'Bugg nummer:',
  'LBL_NUMBER' => 'Nummer:',
  'LBL_STATUS' => 'Status:',
  'LBL_PRIORITY' => 'Prioritet:',
  'LBL_DESCRIPTION' => 'Beskrivning:',
  'LBL_CONTACT_NAME' => 'Kontakt namn:',
  'LBL_BUG_SUBJECT' => 'Bugg ämne:',
  'LBL_CONTACT_ROLE' => 'Roll:',
  'LBL_LIST_NUMBER' => 'Num.',
  'LBL_LIST_SUBJECT' => 'Ämne',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_LIST_PRIORITY' => 'Prioritet',
  'LBL_LIST_RELEASE' => 'Release',
  'LBL_LIST_RESOLUTION' => 'Upplösning',
  'LBL_LIST_LAST_MODIFIED' => 'Senast ändrad',
  'LBL_INVITEE' => 'Kontakter',
  'LBL_TYPE' => 'Typ:',
  'LBL_LIST_TYPE' => 'Typ',
  'LBL_RESOLUTION' => 'Lösning:',
  'LBL_RELEASE' => 'Release:',
  'LNK_NEW_BUG' => 'Rapportera bugg',
  'LNK_CREATE'  => 'Rapportera bugg',
  'LNK_CREATE_WHEN_EMPTY'    => 'Rapportera en bugg nu.',
  'LNK_BUG_LIST' => 'Buggar',
  'LBL_SHOW_MORE' => 'Visa Fler Buggar',
  'NTC_REMOVE_INVITEE' => 'Är du säker på att du vill ta bort kontakten från buggen?',
  'NTC_REMOVE_ACCOUNT_CONFIRMATION' => 'Är du säker på att du vill ta bort buggen från organisationen?',
  'ERR_DELETE_RECORD' => 'Ett objektnummer måste specificeras för att radera buggen.',
  'LBL_LIST_MY_BUGS' => 'Mina tilldelade buggar',
  'LNK_IMPORT_BUGS' => 'Importera buggar',
  'LBL_FOUND_IN_RELEASE' => 'Hittad i release:',
  'LBL_FIXED_IN_RELEASE' => 'Åtgärdad i release:',
  'LBL_LIST_FIXED_IN_RELEASE' => 'Åtgärdad i release:',
  'LBL_WORK_LOG' => 'Arbetslogg:',
  'LBL_SOURCE' => 'Källa:',
  'LBL_PRODUCT_CATEGORY' => 'Kategori:',

  'LBL_CREATED_BY' => 'Skapad av:',
  'LBL_DATE_CREATED' => 'Skapande datum:',
  'LBL_MODIFIED_BY' => 'Senast ändrad av:',
  'LBL_DATE_LAST_MODIFIED' => 'Redigeringsdatum:',

  'LBL_LIST_EMAIL_ADDRESS' => 'Emailadress',
  'LBL_LIST_CONTACT_NAME' => 'Kontaktnamn',
  'LBL_LIST_ACCOUNT_NAME' => 'Organisationsnamn',
  'LBL_LIST_PHONE' => 'Telefon',
  'NTC_DELETE_CONFIRMATION' => 'Är du säker på att du vill radera konatakten från buggen?',

  'LBL_DEFAULT_SUBPANEL_TITLE' => 'Buggtracker',
  'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Aktiviteter',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'Historik',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakter',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Organisationer',
  'LBL_CASES_SUBPANEL_TITLE' => 'Ärenden',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekt',
  'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokument',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Tilldelad användare',
	'LBL_ASSIGNED_TO_NAME' => 'Tilldelad till',

	'LNK_BUG_REPORTS' => 'Buggrapporter',
	'LBL_SHOW_IN_PORTAL' => 'Visa i portal',
	'LBL_BUG_INFORMATION' => 'Översikt',

    //For export labels
	'LBL_FOUND_IN_RELEASE_NAME' => 'Hittad i Publicerat Namn',
    'LBL_PORTAL_VIEWABLE' => 'Portabel Visning Möjlig',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Tilldelat Användarnamn',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Tilldelad Användar ID',
    'LBL_EXPORT_FIXED_IN_RELEASE_NAMR' => 'Fixad i Publicerat Namn',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Ändrad av ID',
    'LBL_EXPORT_CREATED_BY' => 'Skapad av ID',

    //Tour content
    'LBL_PORTAL_TOUR_RECORDS_INTRO' => 'Bugg modulen är för visning och rapportering buggar. Använd pilarna nedan för att gå igenom en snabbtur.',
    'LBL_PORTAL_TOUR_RECORDS_PAGE' => 'Denna sida visar en lista av befintlig publicerade buggar.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER' => 'Du kan filtrera ner i listan över buggar genom att tillhandahålla en sökterm.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER_EXAMPLE' => 'Till exempel kan du använda detta för att hitta en bugg som har rapporterats tidigare.',
    'LBL_PORTAL_TOUR_RECORDS_CREATE' => 'Om du har hittat en ny bugg som du vill rapportera, kan du klicka här för att rapportera en ny bugg.',
    'LBL_PORTAL_TOUR_RECORDS_RETURN' => 'Genom att klicka här kommer du tillbaka till den här vyn när som helst.',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Anteckningar',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}}-modulen används för att spåra och hantera produktrelaterade problem, som vanligtvis kallas {{plural_module_name}} eller defekter, antingen hittat internt eller rapporteras av kunder. {{plural_module_name}} kan ytterligare gallras genom att spåra och hittas problem som löses i en viss release. {{plural_module_name}}modulen ger användarna ett sätt att snabbt se över alla detaljer i {{modul}} och den process som används för att rätta till det.  När en {{module}} skapas eller lämnas, kan du visa och redigera information som rör den {{module}}. Varje {{module}}post kan då avse andra Sugar dokument såsom {{calls_module}}, {{contacts_module}}, {{cases_module}}, och många andra.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}}-modulen används för att spåra och hantera produktrelaterade problem, som vanligtvis kallas {{plural_module_name}} eller defekter, antingen hittat internt eller rapporteras av kunder.  Redigera denna postens fält genom att klicka ett enskilt fält eller på knappen Redigera. - Visa eller ändra länkar till andra poster i underpaneler, även {{campaigns_singular_module}} mottagare, genom att växla den nedre vänstra rutan till "Data View". - Utför och se användarkommentarer och eller se förändringar i {{activitystream_singular_module}} genom att växla den nedre vänstra rutan på "Activity Stream". - Följ som favorit med hjälp av ikonerna till höger om namnet. - Ytterligare åtgärder finns i dropdown menyn Åtgärder till höger om knappen Redigera.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}}-modulen används för att spåra och hantera produktrelaterade problem som vanligtvis kallas {{plural_module_name}} eller defekter, som antingen hittats internt eller rapporteras av kunder.

För att skapa en {{module_name}}:
1. Fyll i fälten med aktuella värden.
 - Fält markerade som &#39;Obligatoriska&#39; måste fyllas i innan du sparar.
 - Klicka på &#39;Visa fler&#39; för att få fler fält om det behövs.
2. Klicka på &#39;Spara&#39; för att färdigställa posten och gå tillbaks till den förra sidan.',
);
