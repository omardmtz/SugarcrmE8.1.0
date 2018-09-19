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
    'LBL_NOTES_LIST_DASHBOARD' => 'Pulpit listy uwag',

    'ERR_DELETE_RECORD' => 'Aby usunąć Kontrahenta, musisz podać numer rekordu.',
    'LBL_ACCOUNT_ID' => 'ID kontrahenta:',
    'LBL_CASE_ID' => 'ID zgłoszenia:',
    'LBL_CLOSE' => 'Zamknij:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID Kontaktu:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notatki',
    'LBL_DESCRIPTION' => 'Opis',
    'LBL_EMAIL_ADDRESS' => 'Adres e-mail:',
    'LBL_EMAIL_ATTACHMENT' => 'Załącznik wiadomości',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Załącznik wiadomości e-mail',
    'LBL_FILE_MIME_TYPE' => 'Typ Mime',
    'LBL_FILE_EXTENSION' => 'Rozszerzenie pliku',
    'LBL_FILE_SOURCE' => 'Źródło pliku',
    'LBL_FILE_SIZE' => 'Rozmiar pliku',
    'LBL_FILE_URL' => 'Adres URL pliku',
    'LBL_FILENAME' => 'Załącznik:',
    'LBL_LEAD_ID' => 'ID Namiaru:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Ostatnia modyfikacja',
    'LBL_LIST_FILENAME' => 'Załącznik',
    'LBL_LIST_FORM_TITLE' => 'Lista notatek',
    'LBL_LIST_RELATED_TO' => 'Powiązano z',
    'LBL_LIST_SUBJECT' => 'Temat',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Notatki',
    'LBL_MODULE_NAME_SINGULAR' => 'Notatka',
    'LBL_MODULE_TITLE' => 'Notatki: Strona główna',
    'LBL_NEW_FORM_TITLE' => 'Utwórz notatkę lub dodaj załącznik',
    'LBL_NEW_FORM_BTN' => 'Dodaj notatkę',
    'LBL_NOTE_STATUS' => 'Notatka',
    'LBL_NOTE_SUBJECT' => 'Temat:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notatki i załączniki',
    'LBL_NOTE' => 'Uwaga:',
    'LBL_OPPORTUNITY_ID' => 'ID szansy:',
    'LBL_PARENT_ID' => 'ID elementu nadrzędnego:',
    'LBL_PARENT_TYPE' => 'Typ nadrzędny',
    'LBL_EMAIL_TYPE' => 'Typ wiadomości e-mail',
    'LBL_EMAIL_ID' => 'Identyfikator wiadomości e-mail',
    'LBL_PHONE' => 'Telefon:',
    'LBL_PORTAL_FLAG' => 'Wyświetlić w portalu?',
    'LBL_EMBED_FLAG' => 'Umieścić w wiadomości?',
    'LBL_PRODUCT_ID' => 'ID pozycji oferty:',
    'LBL_QUOTE_ID' => 'ID oferty:',
    'LBL_RELATED_TO' => 'Powiązano z:',
    'LBL_SEARCH_FORM_TITLE' => 'Wyszukiwanie notatek',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Temat:',
    'LNK_IMPORT_NOTES' => 'Importuj notatki',
    'LNK_NEW_NOTE' => 'Utwórz notatkę lub załącznik',
    'LNK_NOTE_LIST' => 'Wyświetl notatki',
    'LBL_MEMBER_OF' => 'Należy do:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Przypisano do',
    'LBL_OC_FILE_NOTICE' => 'Zaloguj się na serwer, aby zobaczyć plik',
    'LBL_REMOVING_ATTACHMENT' => 'Usuwanie załącznika...',
    'ERR_REMOVING_ATTACHMENT' => 'Nie można usunąć załącznika...',
    'LBL_CREATED_BY' => 'Utworzono przez',
    'LBL_MODIFIED_BY' => 'Zmodyfikowano przez',
    'LBL_SEND_ANYWAYS' => 'Czy na pewno chcesz wysłać/zapisać wiadomość e-mail bez tematu?',
    'LBL_LIST_EDIT_BUTTON' => 'Edytuj',
    'LBL_ACTIVITIES_REPORTS' => 'Raport aktywności',
    'LBL_PANEL_DETAILS' => 'Szczegóły',
    'LBL_NOTE_INFORMATION' => 'Informacje ogólne',
    'LBL_MY_NOTES_DASHLETNAME' => 'Moje notatki',
    'LBL_EDITLAYOUT' => 'Edytuj układ' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Imię',
    'LBL_LAST_NAME' => 'Nazwisko',
    'LBL_EXPORT_PARENT_TYPE' => 'Powiązane z modułem',
    'LBL_EXPORT_PARENT_ID' => 'Powiązane z ID',
    'LBL_DATE_ENTERED' => 'Data utworzenia',
    'LBL_DATE_MODIFIED' => 'Data modyfikacji',
    'LBL_DELETED' => 'Usunięto',
    'LBL_REVENUELINEITEMS' => 'Pozycje szansy',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Moduł {{plural_module_name}} zawiera poszczególne {{plural_module_name}}, w których znajduje się tekst lub załączniki odpowiednie dla powiązanego rekordu.
Rekordy {{module_name}} mogą być powiązane z jednym rekordem w większości modułów poprzez pole elastycznego powiązania lub z pojedynczym rekordem typu {{contacts_singular_module}}.
{{plural_module_name}} mogą zawierać ogólny tekst dotyczący rekordu lub załącznik powiązany z rekordem.
W Sugar można na różne sposoby tworzyć {{plural_module_name}}, na przykład poprzez moduł {{plural_module_name}}, importowanie {{plural_module_name}}, za pomocą paneli podrzędnych Historia itp.
Po utworzeniu rekordu {{module_name}}, można wyświetlać i edytować informacje dotyczące rekordu {{module_name}}, korzystając z widoku rekordu {{plural_module_name}}. Każdy rekord {{module_name}} można następnie powiązać z innymi rekordami Sugar, takimi jak {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} i wieloma innymi.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moduł {{plural_module_name}} zawiera poszczególne {{plural_module_name}}, w których znajduje się tekst lub załączniki odpowiednie dla powiązanego rekordu.

- Edytuj pola tego rekordu poprzez kliknięcie odpowiedniego pola lub przycisku Edytuj.
- Przeglądaj lub modyfikuj powiązania z innymi rekordami w panelach podrzędnych poprzez przełączenie widoku dolnego lewego panelu na Widok danych.
- Dodawaj i przeglądaj komentarze użytkowników i historię zmian rekordu w module {{activitystream_singular_module}} poprzez przełączenie widoku dolnego lewego panelu na Panel aktywności.
- Obserwuj lub dodaj do ulubionych ten rekord za pomocą ikon znajdujących się z prawej strony nazwy rekordu.
- Dodatkowe działania dostępne są w liście rozwijalnej menu Działania po prawej stronie przycisku Edytuj.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Aby utworzyć rekord {{module_name}}:
1. Wprowadź odpowiednie wartości w polach.
- Pola oznaczone jako Wymagane należy uzupełnić przed zapisem.
- Kliknij opcję Pokaż więcej, aby w razie konieczności wyświetlić dodatkowe pola.
2. Kliknij opcję Zapisz, aby zapisać nowy rekord i powrócić do poprzedniej strony.',
);
