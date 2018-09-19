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
    'LBL_MODULE_NAME' => 'Ochrona danych osobowych',
    'LBL_MODULE_NAME_SINGULAR' => 'Ochrona danych osobowych',
    'LBL_NUMBER' => 'Numer',
    'LBL_TYPE' => 'Typ',
    'LBL_SOURCE' => 'Źródło',
    'LBL_REQUESTED_BY' => 'Zlecone przez',
    'LBL_DATE_OPENED' => 'Data otwarcia',
    'LBL_DATE_DUE' => 'Data wymagalności',
    'LBL_DATE_CLOSED' => 'Data zakończenia',
    'LBL_BUSINESS_PURPOSE' => 'Wyrażono zgodę na cele biznesowe dla',
    'LBL_LIST_NUMBER' => 'Numer',
    'LBL_LIST_SUBJECT' => 'Temat',
    'LBL_LIST_PRIORITY' => 'Priorytet',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_TYPE' => 'Typ',
    'LBL_LIST_SOURCE' => 'Źródło',
    'LBL_LIST_REQUESTED_BY' => 'Zlecone przez',
    'LBL_LIST_DATE_DUE' => 'Data wymagalności',
    'LBL_LIST_DATE_CLOSED' => 'Data zakończenia',
    'LBL_LIST_DATE_MODIFIED' => 'Data modyfikacji',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Zmodyfikowane przez',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Przypisany użytkownik',
    'LBL_SHOW_MORE' => 'Pokaż więcej działań z zakresu ochrony prywatności danych',
    'LNK_DATAPRIVACY_LIST' => 'Pokaż działania z zakresu ochrony prywatności danych',
    'LNK_NEW_DATAPRIVACY' => 'Utwórz działanie z zakresu ochrony prywatności danych',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Namiary',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Odbiorcy',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Kontrahenci',
    'LBL_LISTVIEW_FILTER_ALL' => 'Wszystkie działania z zakresu ochrony prywatności danych',
    'LBL_ASSIGNED_TO_ME' => 'Moje działania z zakresu ochrony prywatności danych',
    'LBL_SEARCH_AND_SELECT' => 'Wyszukaj i wybierz działania z zakresu ochrony prywatności danych',
    'TPL_SEARCH_AND_ADD' => 'Wyszukaj i dodaj działania z zakresu ochrony prywatności danych',
    'LBL_WARNING_ERASE_CONFIRM' => 'Zamierzasz trwale usunąć {0} pól(pole). Nie ma możliwości odzyskania danych po zakończeniu usuwania. Czy na pewno chcesz kontynuować?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Masz {0} pola(pól) oznaczone do usunięcia. Potwierdzenie przerwie ich usuwanie, zachowa wszystkie dane i oznaczy to żądanie jako odrzucone. Czy na pewno chcesz kontynuować?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Zamierzasz oznaczyć to żądanie jako ukończone. To trwale ustawi status na Zakończone i uniemożliwi jego ponowne otwarcie. Czy na pewno chcesz kontynuować?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Zamierzasz oznaczyć to żądanie jako odrzucone. To trwale ustawi status na Odrzucone i uniemożliwi jego ponowne otwarcie. Czy na pewno chcesz kontynuować?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Pomyślnie utworzyłeś działanie z zakresu ochrony danych <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Odrzuć',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Zakończ',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Usuń i zakończ',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Usuń pola wybrane za pośrednictwem paneli podrzędnych',
    'LBL_COUNT_FIELDS_MARKED' => 'Pola oznaczone do usunięcia',
    'LBL_NO_RECORDS_MARKED' => 'Brak pól lub rekordów oznaczonych do usunięcia.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Pulpit nawigacyjny rekordów ochrony prywatności danych',

    // list view
    'LBL_HELP_RECORDS' => 'Moduł ochrony danych śledzi działania z zakresu ochrony prywatności, w tym zgody i żądania podmiotów danych w celu obsługi procedur ochrony prywatności danej organizacji. Utwórz rekordy ochrony danych osobowych związane z rekordem danej osoby (np. kontaktu) do śledzenia zgody lub w celu podjęcia działań na żądanie prywatności.',
    // record view
    'LBL_HELP_RECORD' => 'Moduł ochrony danych śledzi działania z zakresu ochrony prywatności, w tym zgody i żądania podmiotów danych w celu obsługi procedur ochrony prywatności danej organizacji. Utwórz rekordy danych ochrony prywatności związane z rekordem danej osoby (np. kontaktu) do śledzenia zgody lub w celu podjęcia działań na żądanie prywatności. Po zakończeniu niezbędnych działań użytkownik pełniący rolę Menedżera prywatności może kliknąć przycisk „Zakończ” lub „Odrzuć”, aby zaktualizować status. 

W przypadku żądań usunięcia danych wybierz „Zaznacz do usunięcia” dla każdego z poszczególnych rekordów wyszczególnionych w panelach podrzędnych poniżej. Gdy wszystkie wymagane pola zostaną zaznaczone, kliknij przycisk „Usuń i zakończ”, aby trwale usunąć wartości pól i oznaczyć rekord prywatności danych jako zakończony.',
);
