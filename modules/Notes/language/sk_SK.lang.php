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
    'LBL_NOTES_LIST_DASHBOARD' => 'Informačný panel so zoznamom poznámok',

    'ERR_DELETE_RECORD' => 'K odstráneniu verzie musíte zadať číslo záznamu.',
    'LBL_ACCOUNT_ID' => 'Číslo účtu:',
    'LBL_CASE_ID' => 'Číslo udalosti:',
    'LBL_CLOSE' => 'Zavrieť',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Číslo kontaktu:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Poznámky',
    'LBL_DESCRIPTION' => 'Poznámka',
    'LBL_EMAIL_ADDRESS' => 'Email adresa:',
    'LBL_EMAIL_ATTACHMENT' => 'Email príloha',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Príloha e-mailu pre',
    'LBL_FILE_MIME_TYPE' => 'Typ MIME',
    'LBL_FILE_EXTENSION' => 'Prípona súboru',
    'LBL_FILE_SOURCE' => 'Zdroj súboru',
    'LBL_FILE_SIZE' => 'Veľkosť súboru',
    'LBL_FILE_URL' => 'Súbor URL',
    'LBL_FILENAME' => 'Príloha:',
    'LBL_LEAD_ID' => 'Číslo vedeného:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Naposledy zmenené',
    'LBL_LIST_FILENAME' => 'Príloha',
    'LBL_LIST_FORM_TITLE' => 'Zoznam poznámok',
    'LBL_LIST_RELATED_TO' => 'Vzťahujúce sa na',
    'LBL_LIST_SUBJECT' => 'Predmet',
    'LBL_LIST_STATUS' => 'Stav',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Poznámky',
    'LBL_MODULE_NAME_SINGULAR' => 'Poznámka',
    'LBL_MODULE_TITLE' => 'Poznámky: Hlavná stránka',
    'LBL_NEW_FORM_TITLE' => 'Vytvoriť poznámku alebo pridať prílohu',
    'LBL_NEW_FORM_BTN' => 'Pridaj poznámku',
    'LBL_NOTE_STATUS' => 'Poznámka',
    'LBL_NOTE_SUBJECT' => 'Predmet:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Prílohy',
    'LBL_NOTE' => 'Poznámka:',
    'LBL_OPPORTUNITY_ID' => 'Číslo príležitosti:',
    'LBL_PARENT_ID' => 'Číslo rodiča:',
    'LBL_PARENT_TYPE' => 'Typ rodiča',
    'LBL_EMAIL_TYPE' => 'Typ e-mailu',
    'LBL_EMAIL_ID' => 'ID e-mailu',
    'LBL_PHONE' => 'Telefón:',
    'LBL_PORTAL_FLAG' => 'Zobraziť na portáli?',
    'LBL_EMBED_FLAG' => 'Vložiť do emailu?',
    'LBL_PRODUCT_ID' => 'Číslo produktu:',
    'LBL_QUOTE_ID' => 'Číslo citátu',
    'LBL_RELATED_TO' => 'Vzťahuje sa na:',
    'LBL_SEARCH_FORM_TITLE' => 'Vyhľadať poznámku',
    'LBL_STATUS' => 'Stav',
    'LBL_SUBJECT' => 'Predmet:',
    'LNK_IMPORT_NOTES' => 'Import poznámok',
    'LNK_NEW_NOTE' => 'Vytvoriť poznámku alebo prílohu',
    'LNK_NOTE_LIST' => 'Zobrazenie poznámok',
    'LBL_MEMBER_OF' => 'Člen v:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Pridelený uživateľ',
    'LBL_OC_FILE_NOTICE' => 'K zobrazeniu súboru sa prihláste na serveri, prosím',
    'LBL_REMOVING_ATTACHMENT' => 'Odobratie prílohy',
    'ERR_REMOVING_ATTACHMENT' => 'Odobratie prílohy zlyhalo ...',
    'LBL_CREATED_BY' => 'Vytvoril',
    'LBL_MODIFIED_BY' => 'Upravil',
    'LBL_SEND_ANYWAYS' => 'Tomuto emailu chýba predmet. Odoslať/uložiť aj tak?',
    'LBL_LIST_EDIT_BUTTON' => 'Upraviť',
    'LBL_ACTIVITIES_REPORTS' => 'Výkaz o ativitách',
    'LBL_PANEL_DETAILS' => 'Detaily',
    'LBL_NOTE_INFORMATION' => 'Prehľad poznámok',
    'LBL_MY_NOTES_DASHLETNAME' => 'Moje poznámky',
    'LBL_EDITLAYOUT' => 'Upraviť rozloženie' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Meno',
    'LBL_LAST_NAME' => 'Priezvisko',
    'LBL_EXPORT_PARENT_TYPE' => 'Viazané na modul',
    'LBL_EXPORT_PARENT_ID' => 'Viazané na ID',
    'LBL_DATE_ENTERED' => 'Dátum vytvorenia',
    'LBL_DATE_MODIFIED' => 'Dátum úpravy',
    'LBL_DELETED' => 'Vymazaný',
    'LBL_REVENUELINEITEMS' => 'Revenue Line Items',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} obsahuje jednotlivé {{plural_module_name}}, ktoré obsahujú text alebo prílohu súvisiacu s prílušným záznamom. Záznamy modulu {{module_name}} môžu súvisieť s jedným záznamom vo väčšine modulov prostredníctvom poľa flexibilnej väzby a tiež môžu súvisieť s jedným modulom {{contacts_singular_module}}. Záznam {{plural_module_name}} môže obsahovať všeobecný text o zázname alebo aj prílohu súvisiacu so záznamom. Modul {{plural_module_name}} môžete v aplikácii Sugar vytvoriť rôznymi spôsobmi, napríklad prostredníctvom modulu {{plural_module_name}}, importovania modulu {{plural_module_name}}, prostredníctvom podpanelov História atď. Po vytvorení záznamu {{module_name}} môžete zobraziť a upraviť informácie týkajúce sa modulu {{module_name}} prostredníctvom zobrazenia záznamu {{plural_module_name}}. Každý záznam {{module_name}} môže potom súvisieť s inými záznamami v aplikácii Sugar, ako sú napríklad moduly {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} a mnohé ďalšie.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of individual {{plural_module_name}} that contain text or an attachment pertinent to the related record.

- Edit this record&#39;s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'To create a {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalize the new record and return to the previous page.',
);
