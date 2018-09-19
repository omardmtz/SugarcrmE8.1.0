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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Pulpit listy możliwości',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Pulpit rekordów możliwości',

    'LBL_MODULE_NAME' => 'Szanse',
    'LBL_MODULE_NAME_SINGULAR' => 'Szansa',
    'LBL_MODULE_TITLE' => 'Szanse: Strona główna',
    'LBL_SEARCH_FORM_TITLE' => 'Wyszukiwanie możliwości',
    'LBL_VIEW_FORM_TITLE' => 'Widok szans',
    'LBL_LIST_FORM_TITLE' => 'Lista szans',
    'LBL_OPPORTUNITY_NAME' => 'Nazwa szansy:',
    'LBL_OPPORTUNITY' => 'Szansa:',
    'LBL_NAME' => 'Nazwa szansy',
    'LBL_INVITEE' => 'Kontakty',
    'LBL_CURRENCIES' => 'Waluty',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Nazwa',
    'LBL_LIST_ACCOUNT_NAME' => 'Nazwa kontrahenta',
    'LBL_LIST_DATE_CLOSED' => 'Oczekiwana data zakończenia',
    'LBL_LIST_AMOUNT' => 'Prawdopodobny',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Przeliczona kwota',
    'LBL_ACCOUNT_ID' => 'ID kontrahenta',
    'LBL_CURRENCY_RATE' => 'Stawka waluty',
    'LBL_CURRENCY_ID' => 'ID waluty',
    'LBL_CURRENCY_NAME' => 'Nazwa waluty',
    'LBL_CURRENCY_SYMBOL' => 'Symbol waluty',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Szansa — Aktualizacja waluty',
    'UPDATE_DOLLARAMOUNTS' => 'Aktualizuj kwotę w USD',
    'UPDATE_VERIFY' => 'Weryfikuj kwoty',
    'UPDATE_VERIFY_TXT' => 'Weryfikuje, czy wartości w Szansach są zapisane w postaci dziesiętnej przy użyciu cyfr (0-9) i separatora (,).',
    'UPDATE_FIX' => 'Popraw kwoty',
    'UPDATE_FIX_TXT' => 'Przeprowadza próbę naprawy niewłaściwych kwot przez utworzenie prawidłowych wyrażeń dziesiętnych. Każda modyfikacja kwoty jest zachowana w bazie danych, w polu amount_backup. Jeśli wykonasz tę operację i pojawi się błąd, nie uruchamiaj jej ponownie, zanim nie zostaną przywrócone poprzednie wartości. Może to spowodować nadpisanie danych błędnymi wartościami.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Aktualizuj kwoty szans w oparciu o aktualne stawki kursowe USD. Wartości te są używane do sporządzania wykresów oraz kwot widoku listy.',
    'UPDATE_CREATE_CURRENCY' => 'Tworzenie nowej waluty:',
    'UPDATE_VERIFY_FAIL' => 'Weryfikacja rekordu nie powiodła się:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Bieżąca kwota:',
    'UPDATE_VERIFY_FIX' => 'Wykonanie naprawy powinno dać',
    'UPDATE_INCLUDE_CLOSE' => 'Uwzględnij uwagę zamknięte rekordy',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Nowa kwota:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nowa waluta:',
    'UPDATE_DONE' => 'Wykonano',
    'UPDATE_BUG_COUNT' => 'Znaleziono błędy i podjęto próbę naprawy:',
    'UPDATE_BUGFOUND_COUNT' => 'Znalezione błędy:',
    'UPDATE_COUNT' => 'Zaktualizowane rekordy:',
    'UPDATE_RESTORE_COUNT' => 'Przywrócono kwoty rekordów:',
    'UPDATE_RESTORE' => 'Przywróć kwoty',
    'UPDATE_RESTORE_TXT' => 'Przywraca wartości kwot z kopii zapasowej podczas naprawy.',
    'UPDATE_FAIL' => 'Nie można zaktualizować — ',
    'UPDATE_NULL_VALUE' => 'Kwota ma wartość NULL. Ustawiono na 0 —',
    'UPDATE_MERGE' => 'Scal waluty',
    'UPDATE_MERGE_TXT' => 'Scal wiele walut w jedną. Jeśli dla tej samej waluty występuje kilka rekordów waluty, można je połączyć. Spowoduje to również połączenie walut dla wszystkich innych modułów.',
    'LBL_ACCOUNT_NAME' => 'Nazwa kontrahenta:',
    'LBL_CURRENCY' => 'Waluta:',
    'LBL_DATE_CLOSED' => 'Oczekiwana data zakończenia:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Znacznik czasu oczekiwanej daty zakończenia',
    'LBL_TYPE' => 'Typ:',
    'LBL_CAMPAIGN' => 'Kampania:',
    'LBL_NEXT_STEP' => 'Następny krok:',
    'LBL_LEAD_SOURCE' => 'Źródło pozyskania',
    'LBL_SALES_STAGE' => 'Etap sprzedaży',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Prawdopodobieństwo (%)',
    'LBL_DESCRIPTION' => 'Opis',
    'LBL_DUPLICATE' => 'Szansa prawdopodobnie już istnieje',
    'MSG_DUPLICATE' => 'Utworzenie tej szansy prawdopodobnie spowoduje powstanie duplikatu już istniejącej szansy. Istniejące rekordy szans o podobnych nazwach są wymienione poniżej.<br>Kliknij Zapisz, aby utworzyć nową szansę, lub kliknij Anuluj, aby powrócić do modułu bez utworzenia szansy.',
    'LBL_NEW_FORM_TITLE' => 'Utwórz szansę',
    'LNK_NEW_OPPORTUNITY' => 'Utwórz szansę',
    'LNK_CREATE' => 'Utwórz transakcję',
    'LNK_OPPORTUNITY_LIST' => 'Wyświetl szanse',
    'ERR_DELETE_RECORD' => 'Należy podać numer rekordu, aby usunąć szansę.',
    'LBL_TOP_OPPORTUNITIES' => 'Moje najważniejsze szanse',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Czy na pewno usunąć powiązanie tego kontaktu z szansą?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Czy na pewno chcesz usunąć powiązanie tego zadania z szansą?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Szanse',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Aktywności',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historia',
    'LBL_RAW_AMOUNT' => 'Kwota początkowa',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Namiary',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokumenty',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekty',
    'LBL_ASSIGNED_TO_NAME' => 'Przydzielono do:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Przypisano do',
    'LBL_LIST_SALES_STAGE' => 'Etap sprzedaży',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Moje zamknięte szanse',
    'LBL_TOTAL_OPPORTUNITIES' => 'Wszystkie szanse',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Szanse zakończone sukcesem',
    'LBL_ASSIGNED_TO_ID' => 'Przypisano do:',
    'LBL_CREATED_ID' => 'Utworzone przez (ID)',
    'LBL_MODIFIED_ID' => 'Zmodyfikowano przez (ID)',
    'LBL_MODIFIED_NAME' => 'Zmodyfikowane przez (nazwa użytkownika)',
    'LBL_CREATED_USER' => 'Utworzono przez',
    'LBL_MODIFIED_USER' => 'Zmodyfikowane przez',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Szansa kampanii',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projekty',
    'LABEL_PANEL_ASSIGNMENT' => 'Przydział',
    'LNK_IMPORT_OPPORTUNITIES' => 'Import szans',
    'LBL_EDITLAYOUT' => 'Edytuj układ' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID kampanii',
    'LBL_OPPORTUNITY_TYPE' => 'Typ szansy',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Przypisano do (nazwa użytkownika)',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Przypisano do (ID użytkownika)',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Zmodyfikowano przez (ID)',
    'LBL_EXPORT_CREATED_BY' => 'Utworzone przez (ID)',
    'LBL_EXPORT_NAME' => 'Nazwa',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Adresy e-mail powiązanych kontaktów',
    'LBL_FILENAME' => 'Załącznik',
    'LBL_PRIMARY_QUOTE_ID' => 'Oferta początkowa',
    'LBL_CONTRACTS' => 'Umowy',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Umowy',
    'LBL_PRODUCTS' => 'Pozycje oferty',
    'LBL_RLI' => 'Pozycje szansy',
    'LNK_OPPORTUNITY_REPORTS' => 'Wyświetl raporty szans',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Oferty',
    'LBL_TEAM_ID' => 'ID zespołu',
    'LBL_TIMEPERIODS' => 'Przedziały czasu',
    'LBL_TIMEPERIOD_ID' => 'ID przedziału czasu',
    'LBL_COMMITTED' => 'Zadeklarowano',
    'LBL_FORECAST' => 'Uwzględnij w prognozie',
    'LBL_COMMIT_STAGE' => 'Etap wykonania',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prognoza',
    'LBL_WORKSHEET' => 'Arkusz',

    'TPL_RLI_CREATE' => 'Szansa musi być powiązana z pozycją szansy.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Utwórz pozycję szansy',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Pozycje oferty',
    'LBL_RLI_SUBPANEL_TITLE' => 'Pozycje szansy',

    'LBL_TOTAL_RLIS' => 'Liczba łącznych pozycji szans',
    'LBL_CLOSED_RLIS' => 'Liczba zamkniętych pozycji szans',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Nie możesz usunąć szans, które zawierają zamknięte Pozycje szansy',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Co najmniej jeden wybrany rekord zawiera zamknięte Pozycje szansy i nie może zostać usunięty.',
    'LBL_INCLUDED_RLIS' => 'Liczba uwzględnionych pozycji szans',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Oferty',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Hierarchia szans',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Ustaw Oczekiwaną datę zamknięcia danej Szansy na najwcześniejszą lub najpóźniejszą datę zamknięcia istniejących Pozycji szansy.',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Wartość całkowita lejka sprzedaży wynosi ',

    'LBL_OPPORTUNITY_ROLE'=>'Rola w szansie',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notatki',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Klikając Potwierdź, usuniesz WSZYSTKIE dane Prognoz i zmienisz Widok szans. Jeśli nie było to Twoim zamiarem, kliknij anuluj, aby wrócić do poprzednich ustawień.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Klikając przycisk Potwierdź, wymażesz WSZYSTKIE dane prognozy i zmienisz widok możliwości. '
        .'Również WSZYSTKIE definicje procesów z modułem docelowym pozycji wiersza przychodów zostaną wyłączone. '
        .'Jeśli to nie o to ci chodziło, kliknij przycisk anuluj, aby powrócić do poprzednich ustawień.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Jeśli wszystkie Pozycje szansy są zamknięte i co najmniej jedna została zakończona sukcesem,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'etap sprzedaży zostanie ustawiony jako Zakończone sukcesem.',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Jeśli wszystkie Pozycje szansy mają etap sprzedaży Zakończone porażką,',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'etap sprzedaży zostanie ustawiony jako Zakończone porażką.',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Jeśli jakiekolwiek Pozycje szansy są wciąż otwarte,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'Szansa zostanie oznaczona najmniej zaawansowanym etapem sprzedaży.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Po zainicjowaniu tej zmiany notatki podsumowujące Pozycje szans będą budowane w tle. Kiedy notatki będą ukończone i dostępne, na twój adres e-mail użytkownika zostanie wysłane powiadomienie. Jeśli Twoja instancja jest ustawiona dla modułu {{forecasts_module}}, Sugar wyśle również powiadomienie, kiedy rekordy Twoich Szans zostaną zsynchronizowane z modułem {{forecasts_module}} i dostępne dla nowych Prognoz. Zwróć uwagę, że Twoja instancja musi zostać skonfigurowana w panelu Administrator > Ustawienia poczty e-mail, aby powiadomienie zostało wysłane.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Po zainicjowaniu tej zmiany rekordy Pozycji szans zostaną utworzone dla każdej istniejącej {{module_name}} w tle. Kiedy Pozycje szansy będą ukończone i dostępne, na Twój adres e-mail użytkownika zostanie wysłane powiadomienie. Zwróć uwagę, że Twoja instancja musi zostać skonfigurowana w panelu Administrator > Ustawienia poczty e-mail, aby powiadomienie zostało wysłane.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Moduł {{plural_module_name}} umożliwia śledzenie indywidualnej sprzedaży od początku do końca. Każdy rekord {{module_name}} zawiera potencjalną sprzedaż i obejmuje odpowiadające dane sprzedaży, a także odnoszące się do innych ważnych rekordów, takich jak {{quotes_module}}, {{contacts_module}} itp. {{module_name}} zazwyczaj będzie przechodzić przez kilka etapów sprzedaży, dopóki nie zostanie oznaczony jako Zakończone sukcesem lub Zakończone porażką. Rekordy {{plural_module_name}} mogą być dalej wykorzystywane za pomocą modułu {{forecasts_singular_module}} w systemie Sugar do zrozumienia i przewidywania trendów w sprzedaży, a także koncentracji pracy w celu osiągnięcia targetów sprzedaży.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moduł {{plural_module_name}} umożliwia śledzenie indywidualnej sprzedaży i powiązanych z nią pozycji od początku do końca. Każdy rekord {{module_name}} zawiera potencjalną sprzedaż i obejmuje odpowiadające dane sprzedaży, a także odnoszące się do innych ważnych rekordów, takich jak {{quotes_module}}, {{contacts_module}} itp. 

- Edytuj pola tego rekordu poprzez kliknięcie odpowiedniego pola lub przycisku Edytuj.
- Przeglądaj lub modyfikuj powiązania z innymi rekordami w panelach podrzędnych, przełączając widok lewego dolnego panelu na Widok danych.
- Dodawaj i przeglądaj komentarze użytkowników i zapisuj historię zmian rekordu w module {{activitystream_singular_module}} poprzez przełączenie widoku dolnego lewego panelu na Panel aktywności.
- Obserwuj lub dodaj do ulubionych ten rekord za pomocą ikon znajdujących się z prawej strony nazwy rekordu.
- Dodatkowe działania dostępne są w liście rozwijanej menu Działania po prawej stronie przycisku Edytuj.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Moduł {{plural_module_name}} umożliwia śledzenie indywidualnej sprzedaży i powiązanych z nią pozycji od początku do końca. Każdy rekord {{module_name}} zawiera potencjalną sprzedaż i obejmuje odpowiadające dane sprzedaży, a także odnoszące się do innych ważnych rekordów, takich jak {{quotes_module}}, {{contacts_module}} itp. 

Aby utworzyć rekord {{module_name}}:
1. Wprowadź żądane wartości w polach.
 - Pola oznaczone jako Wymagane należy wypełnić przez zapisaniem.
 - W razie potrzeby kliknij opcję Wyświetl więcej, aby wyświetlić dodatkowe pola.
2. Kliknij opcję Zapisz, aby zakończyć nowy rekord i wrócić do poprzedniej strony.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Synchronizuj z Marketo&reg;',
    'LBL_MKTO_ID' => 'ID namiaru Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => '10 najlepszych szans sprzedażowych',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Wyświetla najlepsze 10 szans sprzedażowych w wykresie bąbelkowym.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Moje szanse',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Szanse mojego zespołu",
);
