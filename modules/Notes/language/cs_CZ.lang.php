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
    'LBL_NOTES_LIST_DASHBOARD' => 'Řídicí panel seznamu poznámek',

    'ERR_DELETE_RECORD' => 'Pro vymazání zaměstnance musíte specifikovat číslo záznamu.',
    'LBL_ACCOUNT_ID' => 'ID společnosti:',
    'LBL_CASE_ID' => 'ID případu:',
    'LBL_CLOSE' => 'Uzavřen:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID kontaktu:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Poznámky',
    'LBL_DESCRIPTION' => 'Popis',
    'LBL_EMAIL_ADDRESS' => 'Emailová adresa:',
    'LBL_EMAIL_ATTACHMENT' => 'Příloha emailu',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Příloha e-mailu pro',
    'LBL_FILE_MIME_TYPE' => 'MIME typ',
    'LBL_FILE_EXTENSION' => 'Přípona souboru',
    'LBL_FILE_SOURCE' => 'Zdroj souboru',
    'LBL_FILE_SIZE' => 'Velikost souboru',
    'LBL_FILE_URL' => 'URL souboru',
    'LBL_FILENAME' => 'Příloha:',
    'LBL_LEAD_ID' => 'ID příležitosti:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Poslední modifikace',
    'LBL_LIST_FILENAME' => 'Příloha',
    'LBL_LIST_FORM_TITLE' => 'Seznam poznámek',
    'LBL_LIST_RELATED_TO' => 'Vztahuje se k',
    'LBL_LIST_SUBJECT' => 'Předmět',
    'LBL_LIST_STATUS' => 'Stav',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Poznámky',
    'LBL_MODULE_NAME_SINGULAR' => 'Poznámka',
    'LBL_MODULE_TITLE' => 'Poznámky',
    'LBL_NEW_FORM_TITLE' => 'Přidat poznámku nebo přílohu',
    'LBL_NEW_FORM_BTN' => 'Přidat poznámku',
    'LBL_NOTE_STATUS' => 'Poznámka',
    'LBL_NOTE_SUBJECT' => 'Předmět:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Přílohy',
    'LBL_NOTE' => 'Poznámka:',
    'LBL_OPPORTUNITY_ID' => 'ID obchodu:',
    'LBL_PARENT_ID' => 'ID zdrojové:',
    'LBL_PARENT_TYPE' => 'Typ zdroje',
    'LBL_EMAIL_TYPE' => 'Typ e-mailu',
    'LBL_EMAIL_ID' => 'ID e-mailu',
    'LBL_PHONE' => 'Telefon',
    'LBL_PORTAL_FLAG' => 'Zobrazit v portálu?',
    'LBL_EMBED_FLAG' => 'Vložit do emailu?',
    'LBL_PRODUCT_ID' => 'ID produktu:',
    'LBL_QUOTE_ID' => 'ID nabídky:',
    'LBL_RELATED_TO' => 'Vztahuje se k:',
    'LBL_SEARCH_FORM_TITLE' => 'Vyhledat poznámku',
    'LBL_STATUS' => 'Stav',
    'LBL_SUBJECT' => 'Předmět:',
    'LNK_IMPORT_NOTES' => 'Importovat poznámky',
    'LNK_NEW_NOTE' => 'Vytvořit poznámku nebo přílohu',
    'LNK_NOTE_LIST' => 'Poznámky',
    'LBL_MEMBER_OF' => 'Člen:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Zodpovědný uživatel',
    'LBL_OC_FILE_NOTICE' => 'Prosím přihlašte se k serveru pro zobrazní souboru',
    'LBL_REMOVING_ATTACHMENT' => 'Odstraňuji přílohu...',
    'ERR_REMOVING_ATTACHMENT' => 'Chyba při odstraňování přílohy...',
    'LBL_CREATED_BY' => 'Vytvořil:',
    'LBL_MODIFIED_BY' => 'Modifikováno kym',
    'LBL_SEND_ANYWAYS' => 'Tento e-mail nemá žádný předmět. Přesto odeslat/uložit?',
    'LBL_LIST_EDIT_BUTTON' => 'Editace',
    'LBL_ACTIVITIES_REPORTS' => 'Report aktivit',
    'LBL_PANEL_DETAILS' => 'Detaily',
    'LBL_NOTE_INFORMATION' => 'Přehled úkolu',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mé poznámky',
    'LBL_EDITLAYOUT' => 'Úprava rozvržení' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Jméno',
    'LBL_LAST_NAME' => 'Příjmení',
    'LBL_EXPORT_PARENT_TYPE' => 'Týkající se modulu',
    'LBL_EXPORT_PARENT_ID' => 'Týkající se ID',
    'LBL_DATE_ENTERED' => 'Datum zahájení',
    'LBL_DATE_MODIFIED' => 'Datum poslední úpravy',
    'LBL_DELETED' => 'Odstranit',
    'LBL_REVENUELINEITEMS' => 'Řádky obchodu',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} obsahuje jednotlivé {{plural_module_name}}, které obsahují text nebo přílohu vztahující se k souvisejícímu záznamu. Záznamy {{module_name}} mohou být navázány na jeden záznam ve většině modulů prostřednictvím pole flexibilní vazby a může být také navázáno na jeden {{contacts_singular_module}}. {{plural_module_name}} mohou obsahovat generický text o záznamu nebo dokonce přílohu související se záznamem. Jsou různé způsoby, jak můžete vytvořit {{plural_module_name}} v Sugaru, jako pomocí modulu {{plural_module_name}}, importem {{plural_module_name}}, prostřednictvím subpanelu Historie atd. Jakmile je {{module_name}} vytvořen, můžete zobrazit a upravit informace vztahující se k {{module_name}} prostřednictvím {{plural_module_name}} Record View. Každý záznam {{module_name}} může poté souviset s dalšími záznamy Sugaru jako například {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} a mnoha dalšími.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} obsahuje jednotlivé {{plural_module_name}}, které obsahují text nebo přílohu vztahující se k souvisejícímu záznamu.

- Upravte pole tohoto záznamu kliknutím na jednotlivá pole nebo na tlačítko Upravit.
- Zobrazte nebo upravte vazby na ostatní záznamy v subpanelech přepnutím levého spodního panelu na "Datový pohled".
- Vytvořte a zobrazte uživatelské komentáře a historii změn záznamu v modulu {{activitystream_singular_module}} přepnutím spodního levého panelu na "Tok aktivit".
- Sledujte nebo označte záznam jako oblíbený pomocí ikon vpravo od názvu záznamu.
- Další akce jsou dostupné v rozbalovacím menu Akce vpravo od tlačítka Upravit.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Vytvoření {{module_name}}:
1. Vyplňte hodnoty polí dle potřeby.
 - Pole označená jako „Povinné“ musí být vyplněna před uložením.
 - V případě potřeby klikněte na položku „Zobrazit více“ pro zobrazení dalších polí.
2. Kliknutím na tlačítko „Uložit“ dokončete nový záznam a vraťte se na předchozí stránku.',
);
