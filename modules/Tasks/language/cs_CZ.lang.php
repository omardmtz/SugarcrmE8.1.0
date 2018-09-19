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
  'LBL_TASKS_LIST_DASHBOARD' => 'Řídicí panel seznamu úkolů',

  'LBL_MODULE_NAME' => 'Úkoly',
  'LBL_MODULE_NAME_SINGULAR' => 'Úkol',
  'LBL_TASK' => 'Úkoly:',
  'LBL_MODULE_TITLE' => 'Úkoly',
  'LBL_SEARCH_FORM_TITLE' => 'Vyhledat úkol',
  'LBL_LIST_FORM_TITLE' => 'Seznam úkolů',
  'LBL_NEW_FORM_TITLE' => 'Přidat úkol',
  'LBL_NEW_FORM_SUBJECT' => 'Předmět:',
  'LBL_NEW_FORM_DUE_DATE' => 'Udělat do dne:',
  'LBL_NEW_FORM_DUE_TIME' => 'Udělat do (čas):',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Zavřít',
  'LBL_LIST_SUBJECT' => 'Předmět',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Priorita',
  'LBL_LIST_RELATED_TO' => 'Týká se',
  'LBL_LIST_DUE_DATE' => 'Do data',
  'LBL_LIST_DUE_TIME' => 'Termín (čas)',
  'LBL_SUBJECT' => 'Předmět:',
  'LBL_STATUS' => 'Stav:',
  'LBL_DUE_DATE' => 'Udělat do dne:',
  'LBL_DUE_TIME' => 'Udělat do (čas):',
  'LBL_PRIORITY' => 'Priorita:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Dokončit do datum a čas:',
  'LBL_START_DATE_AND_TIME' => 'Počáteční datum & čas:',
  'LBL_START_DATE' => 'Počáteční datum:',
  'LBL_LIST_START_DATE' => 'Začátek',
  'LBL_START_TIME' => 'Počátenčí čas:',
  'LBL_LIST_START_TIME' => 'Počáteční čas',
  'DATE_FORMAT' => '(rrrr-mm-dd)',
  'LBL_NONE' => 'žádný',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'Emailová adresa:',
  'LBL_PHONE' => 'Telefon',
  'LBL_EMAIL' => 'Emailová adresa:',
  'LBL_DESCRIPTION_INFORMATION' => 'Popis',
  'LBL_DESCRIPTION' => 'Popis:',
  'LBL_NAME' => 'Název:',
  'LBL_CONTACT_NAME' => 'Kontaktní jméno:',
  'LBL_LIST_COMPLETE' => 'Dokončeno:',
  'LBL_LIST_STATUS' => 'Stav',
  'LBL_DATE_DUE_FLAG' => 'Nepřesné datum',
  'LBL_DATE_START_FLAG' => 'Nezahájené datum',
  'ERR_DELETE_RECORD' => 'Pro smazání kontaktu musí být zadáno číslo záznamu.',
  'ERR_INVALID_HOUR' => 'Prosím zadejte hodinu mezi 0 a 24',
  'LBL_DEFAULT_PRIORITY' => 'Střední',
  'LBL_LIST_MY_TASKS' => 'Moje otevřené úkoly',
  'LNK_NEW_TASK' => 'Přidat úkol',
  'LNK_TASK_LIST' => 'Úkoly',
  'LNK_IMPORT_TASKS' => 'Importuj úkoly',
  'LBL_CONTACT_FIRST_NAME'=>'Křestní jméno',
  'LBL_CONTACT_LAST_NAME'=>'Příjmení',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Zodpovědný uživatel',
  'LBL_ASSIGNED_TO_NAME'=>'Přiřazeno (komu):',
  'LBL_LIST_DATE_MODIFIED' => 'Datum poslední úpravy',
  'LBL_CONTACT_ID' => 'ID kontaktu:',
  'LBL_PARENT_ID' => 'ID zdrojové:',
  'LBL_CONTACT_PHONE' => 'kontaktní telefon:',
  'LBL_PARENT_NAME' => 'Typ zdroje:',
  'LBL_ACTIVITIES_REPORTS' => 'Report aktivit',
  'LBL_EDITLAYOUT' => 'Úprava rozvržení' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Přehled úkolu',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Poznámky',
  'LBL_REVENUELINEITEMS' => 'Řádky obchodu',
  //For export labels
  'LBL_DATE_DUE' => 'Datum splnění',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Přiřazený uživatel',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID přiřazeného uživatele',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'ID modifikátora',
  'LBL_EXPORT_CREATED_BY' => 'Vytvořeno od ID:',
  'LBL_EXPORT_PARENT_TYPE' => 'Týkající se modulu',
  'LBL_EXPORT_PARENT_ID' => 'Týkající se ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Úkol úspěšně uzavřen',
  'LBL_ASSIGNED_USER' => 'Přiřazeno komu',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Poznámky',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} se skládá z flexibilních akcí, to-do položek, nebo jiného druhu činnosti, která vyžaduje dokončení. Záznamy {{module_name}} mohou být navázány na jeden záznam z většiny modulů pomocí flex relačního pole a také mohou být navázány na jeden {{contacts_singular_module}}. Existují různé způsoby, jak vytvořit {{plural_module_name}} v Sugar, např. pomocí modulu {{plural_module_name}}, duplikací, importem {{plural_module_name}} atp. Po vytvoření {{module_name}} můžete informace o {{module_name}} zobrazovat a měnit v přehledu záznamu {{plural_module_name}}. V závslosti na podrobnostech {{module_name}} můžete také být schopni zobrazovat a měnit informace o {{module_name}} prostřednictvím modulu Kalendář. Každý záznam v modulu {{module_name}} pak může být navázán na další záznamy v Sugar, jako jsou {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} a mnoho dalších.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul Úkoly se skládá z flexibilních akcí, to-do položek, nebo jiného druhu činnosti, která vyžaduje dokončení. Upravte pole tohoto záznamu klepnutím na jednotlivé pole nebo na tlačítko Upravit. - Zobrazte nebo upravte odkazy na jiné záznamy v subpanelech, včetně příjemců Kampaně, přepnutím spodního levého podokna na “Zobrazení dat” - Vytvořte a zobrazte uživatelské komentáře a historii změn v modulu Aktivity přepnutím spodního levého podokna na “Aktivity”. - Sledujte záznam nebo si ho přidejte do oblíbených pomocí tlačítek vpravo od názvu záznamu. - Další akce jsou k dispozici v rozbalovací nabídce vpravo od tlačítka Upravit.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Modul {{plural_module_name}} se skládá z flexibilních akcí, položek k provedení nebo jiného druhu činnosti, která vyžaduje dokončení.

Vytvoření modulu {{module_name}}:
1. Vyplňte hodnoty polí dle potřeby.
 - Pole označená jako „Povinné“ musí být vyplněna před uložením.
 - V případě potřeby klikněte na položku „Zobrazit více“ pro zobrazení dalších polí.
2. Kliknutím na tlačítko „Uložit“ dokončete nový záznam a vraťte se na předchozí stránku.',

);
