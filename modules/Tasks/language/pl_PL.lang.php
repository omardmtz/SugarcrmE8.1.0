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
  'LBL_TASKS_LIST_DASHBOARD' => 'Pulpit list zadań',

  'LBL_MODULE_NAME' => 'Zadania',
  'LBL_MODULE_NAME_SINGULAR' => 'Zadanie',
  'LBL_TASK' => 'Zadania:',
  'LBL_MODULE_TITLE' => 'Zadania: Strona główna',
  'LBL_SEARCH_FORM_TITLE' => ' Wyszukiwanie zadań',
  'LBL_LIST_FORM_TITLE' => 'Lista zadań',
  'LBL_NEW_FORM_TITLE' => 'Utwórz zadanie',
  'LBL_NEW_FORM_SUBJECT' => 'Temat:',
  'LBL_NEW_FORM_DUE_DATE' => 'Data wymagalności:',
  'LBL_NEW_FORM_DUE_TIME' => 'Godzina zakończenia:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Zamknij',
  'LBL_LIST_SUBJECT' => 'Temat',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Priorytet',
  'LBL_LIST_RELATED_TO' => 'Powiązano z',
  'LBL_LIST_DUE_DATE' => 'Data wymagalności',
  'LBL_LIST_DUE_TIME' => 'Godzina zakończenia',
  'LBL_SUBJECT' => 'Temat:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Data wymagalności:',
  'LBL_DUE_TIME' => 'Godzina zakończenia:',
  'LBL_PRIORITY' => 'Priorytet:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Data i czas wymagalności:',
  'LBL_START_DATE_AND_TIME' => 'Data i czas rozpoczęcia:',
  'LBL_START_DATE' => 'Data rozpoczęcia:',
  'LBL_LIST_START_DATE' => 'Data rozpoczęcia',
  'LBL_START_TIME' => 'Czas rozpoczęcia:',
  'LBL_LIST_START_TIME' => 'Czas rozpoczęcia',
  'DATE_FORMAT' => '(rrrr-mm-dd)',
  'LBL_NONE' => 'Brak',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'Adres e-mail:',
  'LBL_PHONE' => 'Telefon:',
  'LBL_EMAIL' => 'Adres e-mail:',
  'LBL_DESCRIPTION_INFORMATION' => 'Informacje opisowe',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_NAME' => 'Nazwa:',
  'LBL_CONTACT_NAME' => 'Nazwa kontaktu ',
  'LBL_LIST_COMPLETE' => 'Zakończone:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'Brak daty wymagalności',
  'LBL_DATE_START_FLAG' => 'Brak daty rozpoczęcia',
  'ERR_DELETE_RECORD' => 'Musisz podać numer rekordu, aby usunąć kontakt.',
  'ERR_INVALID_HOUR' => 'Wpisz godzinę pomiędzy 0 a 24',
  'LBL_DEFAULT_PRIORITY' => 'Średni',
  'LBL_LIST_MY_TASKS' => 'Moje otwarte zadania',
  'LNK_NEW_TASK' => 'Utwórz zadanie',
  'LNK_TASK_LIST' => 'Wyświetl zadania',
  'LNK_IMPORT_TASKS' => 'Importuj zadania',
  'LBL_CONTACT_FIRST_NAME'=>'Imię kontaktu',
  'LBL_CONTACT_LAST_NAME'=>'Nazwisko kontaktu',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Przypisano do',
  'LBL_ASSIGNED_TO_NAME'=>'Przydzielono do:',
  'LBL_LIST_DATE_MODIFIED' => 'Data modyfikacji',
  'LBL_CONTACT_ID' => 'ID kontaktu:',
  'LBL_PARENT_ID' => 'ID elementu nadrzędnego:',
  'LBL_CONTACT_PHONE' => 'Telefon kontaktu:',
  'LBL_PARENT_NAME' => 'Typ nadrzędny:',
  'LBL_ACTIVITIES_REPORTS' => 'Raport aktywności',
  'LBL_EDITLAYOUT' => 'Edytuj układ' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Informacje ogólne',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notatki',
  'LBL_REVENUELINEITEMS' => 'Pozycje szansy',
  //For export labels
  'LBL_DATE_DUE' => 'Data wymagalności',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Przypisano do (nazwa użytkownika)',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Przypisano do (ID użytkownika)',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Zmodyfikowano przez (ID)',
  'LBL_EXPORT_CREATED_BY' => 'Utworzone przez (ID)',
  'LBL_EXPORT_PARENT_TYPE' => 'Powiązane z modułem',
  'LBL_EXPORT_PARENT_ID' => 'Powiązane z ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Zamknięcie zadania powiodło się.',
  'LBL_ASSIGNED_USER' => 'Przydzielono do',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notatki',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Moduł {{plural_module_name}} składa się z elastycznych działań, elementów do wykonania lub jakichkolwiek innych czynności wymagających ukończenia. {{module_name}} może być powiązane z jednym rekordem w większości modułów poprzez pole „Powiązane z” oraz z jednym rekordem typu {{contacts_singular_module}}. Jest kilka sposobów na utworzenie rekordów typu {{plural_module_name}} w Sugar, np. poprzez moduł {{plural_module_name}}, duplikowanie lub importowanie rekordów typu {{plural_module_name}} itp. Po utworzeniu {{module_name}} można przeglądać i edytować informacje dotyczące rekordu {{module_name}} poprzez widok rekordu {{plural_module_name}}. W zależności od szczegółów rekordu {{module_name}} {{module_name}} można przeglądać i edytować poprzez moduł Kalendarz. Każdy rekord {{module_name}} może zostać powiązany z innymi rekordami Sugar, np. z {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} oraz wieloma innymi.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moduł {{plural_module_name}} składa się z elastycznych działań, elementów do wykonania lub jakichkolwiek innych czynności wymagających ukończenia.

- Edytuj pola tego rekordu poprzez kliknięcie odpowiedniego pola lub przycisku Edytuj.
- Przeglądaj lub modyfikuj powiązania z innymi rekordami w panelach podrzędnych poprzez przełączenie widoku dolnego lewego panelu na Widok danych.
- Dodawaj i przeglądaj komentarze użytkowników i historię zmian rekordu w module {{activitystream_singular_module}} poprzez przełączenie widoku dolnego lewego panelu na Panel aktywności.
- Obserwuj lub dodaj do ulubionych ten rekord za pomocą ikon znajdujących się z prawej strony nazwy rekordu.
- Dodatkowe działania dostępne są w liście rozwijalnej menu Działania po prawej stronie przycisku Edytuj.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Moduł {{plural_module_name}} składa się z elastycznych działań, elementów do wykonania lub jakichkolwiek innych czynności wymagających ukończenia.

Aby utworzyć {{module_name}}:
1. Wprowadź odpowiednie wartości w polach.
- Pola oznaczone jako Wymagane należy uzupełnić przed zapisaniem.
- Kliknij opcję Pokaż więcej, aby w razie konieczności wyświetlić dodatkowe pola.
2. Kliknij opcję Zapisz, aby zapisać nowy rekord i powrócić do poprzedniej strony.',

);
