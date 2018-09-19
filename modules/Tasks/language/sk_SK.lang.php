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
  'LBL_TASKS_LIST_DASHBOARD' => 'Informačný panel so zoznamom úloh',

  'LBL_MODULE_NAME' => 'Úlohy',
  'LBL_MODULE_NAME_SINGULAR' => 'Úloha',
  'LBL_TASK' => 'úlohy',
  'LBL_MODULE_TITLE' => 'Úlohy: Domov',
  'LBL_SEARCH_FORM_TITLE' => 'Vyhľadávanie úloh',
  'LBL_LIST_FORM_TITLE' => 'Zoznam úloh',
  'LBL_NEW_FORM_TITLE' => 'Vytvoriť plohu',
  'LBL_NEW_FORM_SUBJECT' => 'Predmet:',
  'LBL_NEW_FORM_DUE_DATE' => 'Do dátumu:',
  'LBL_NEW_FORM_DUE_TIME' => 'do času',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Zavrieť',
  'LBL_LIST_SUBJECT' => 'Predmet',
  'LBL_LIST_CONTACT' => 'Kontakt:',
  'LBL_LIST_PRIORITY' => 'Priorita',
  'LBL_LIST_RELATED_TO' => 'Nadriadený',
  'LBL_LIST_DUE_DATE' => 'Do dátumu',
  'LBL_LIST_DUE_TIME' => 'do času',
  'LBL_SUBJECT' => 'Predmet:',
  'LBL_STATUS' => 'Stav:',
  'LBL_DUE_DATE' => 'Do dátumu:',
  'LBL_DUE_TIME' => 'do času',
  'LBL_PRIORITY' => 'Priorita:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Do dátumu a času:',
  'LBL_START_DATE_AND_TIME' => 'dátum a čas začiatku',
  'LBL_START_DATE' => 'Dátum začiatku:',
  'LBL_LIST_START_DATE' => 'Dátum začiatku:',
  'LBL_START_TIME' => 'čas začiatku',
  'LBL_LIST_START_TIME' => 'Čas zahájenia',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => 'Nič',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'Email adresa:',
  'LBL_PHONE' => 'Telefón:',
  'LBL_EMAIL' => 'Emailová adresa:',
  'LBL_DESCRIPTION_INFORMATION' => 'Popis informácie',
  'LBL_DESCRIPTION' => 'Popis:',
  'LBL_NAME' => 'Názov',
  'LBL_CONTACT_NAME' => 'Meno kontaktu:',
  'LBL_LIST_COMPLETE' => 'kompletný',
  'LBL_LIST_STATUS' => 'Stav',
  'LBL_DATE_DUE_FLAG' => 'nie do dátumu',
  'LBL_DATE_START_FLAG' => 'nie do času',
  'ERR_DELETE_RECORD' => 'K odstráneniu kontaktu musíte zadať číslo záznamu.',
  'ERR_INVALID_HOUR' => 'Prosím vložte platnú hodinu.',
  'LBL_DEFAULT_PRIORITY' => 'Stredne',
  'LBL_LIST_MY_TASKS' => 'Moje otvorené úlohy',
  'LNK_NEW_TASK' => 'Vytvoriť úlohu',
  'LNK_TASK_LIST' => 'Zobrazenie úloh',
  'LNK_IMPORT_TASKS' => 'Import úloh',
  'LBL_CONTACT_FIRST_NAME'=>'Meno kontaktu',
  'LBL_CONTACT_LAST_NAME'=>'Priezvisko kontaktu',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Pridelený uživateľ',
  'LBL_ASSIGNED_TO_NAME'=>'Pridelený k:',
  'LBL_LIST_DATE_MODIFIED' => 'Dátum úpravy',
  'LBL_CONTACT_ID' => 'ID kontaktu:',
  'LBL_PARENT_ID' => 'ID rodiča:',
  'LBL_CONTACT_PHONE' => 'číslo kontaktu',
  'LBL_PARENT_NAME' => 'Materská kategória:',
  'LBL_ACTIVITIES_REPORTS' => 'Výkaz o ativitách',
  'LBL_EDITLAYOUT' => 'Upraviť rozloženie' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Prehľad',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Poznámky',
  'LBL_REVENUELINEITEMS' => 'Revenue Line Items',
  //For export labels
  'LBL_DATE_DUE' => 'Priamy dátum',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Pridelené meno užívateľa',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Pridelené užívateľské ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Upravené podľa ID',
  'LBL_EXPORT_CREATED_BY' => 'Vytvorené podľa ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Viazané na modul',
  'LBL_EXPORT_PARENT_ID' => 'Viazané na ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Úloha úspešne uzavretá.',
  'LBL_ASSIGNED_USER' => 'Pridelený k',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Poznámky',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} obsahuje flexibilné akcie, položky úloh alebo iný typ aktivity vyžadujúci dokončenie. Záznamy modulu {{module_name}} môžu súvisieť s jedným záznamom vo väčšine modulov prostredníctvom poľa flexibilnej väzby a tiež môžu súvisieť s jedným modulom {{contacts_singular_module}}. Modul {{plural_module_name}} môžete v aplikácii Sugar vytvoriť rôznymi spôsobmi, napríklad prostredníctvom modulu {{plural_module_name}}, duplikácie, importovania modulu {{plural_module_name}} atď. Po vytvorení záznamu {{module_name}} môžete zobraziť a upraviť informácie týkajúce sa modulu {{module_name}} prostredníctvom zobrazenia záznamu {{plural_module_name}}. V závislosti od podrobností v module {{module_name}} tiež môžete zobraziť a upraviť informácie o module {{module_name}} prostredníctvom modulu Kalendár. Každý záznam {{module_name}} môže potom súvisieť s inými záznamami v aplikácii Sugar, ako sú napríklad moduly {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} a mnohé ďalšie.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} obsahuje flexibilné akcie, položky úloh alebo iný typ aktivity vyžadujúci dokončenie.

- Polia tohto záznamu môžete upraviť kliknutím na jednotlivé polia alebo na tlačidlo Upraviť.
- Prepojenia na iné záznamy môžete zobraziť alebo upraviť v podpaneloch prepnutím spodného ľavého poľa na možnosť „Zobrazenie údajov“.
- Vytvorte a zobrazte používateľské komentáre a históriu zmien záznamu v module {{activitystream_singular_module}} prepnutím spodného ľavého poľa na „Aktivity“.
- Sledujte alebo označte tento záznam ako obľúbený pomocou ikon vpravo od názvu záznamu.
- Ďalšie akcie sú k dispozícii v rozbaľovacej ponuke Akcie umiestnenej napravo od tlačidla Upraviť.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Modul {{plural_module_name}} obsahuje flexibilné akcie, položky úloh alebo iný typ aktivity vyžadujúci dokončenie.

Ak chcete vytvoriť modul {{module_name}}:
1. Zadajte požadované hodnoty do polí.
 - Polia označené ako „Povinné“ treba pred uložením vyplniť.
 - Ak chcete rozbaliť dodatočné polia, kliknite na možnosť „Zobraziť viac“.
2. Kliknite na možnosť „Uložiť“ na dokončenie nového záznamu a návrat na predchádzajúcu stránku.',

);
