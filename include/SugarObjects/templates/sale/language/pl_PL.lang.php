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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'Sprzedaż',
  'LBL_MODULE_TITLE' => 'Sprzedaż: Strona główna',
  'LBL_SEARCH_FORM_TITLE' => 'Wyszukiwanie w sprzedaży',
  'LBL_VIEW_FORM_TITLE' => 'Widok sprzedaży',
  'LBL_LIST_FORM_TITLE' => 'Lista sprzedaży',
  'LBL_SALE_NAME' => 'Nazwa sprzedaży:',
  'LBL_SALE' => 'Sprzedaż:',
  'LBL_NAME' => 'Nazwa sprzedaży',
  'LBL_LIST_SALE_NAME' => 'Nazwa',
  'LBL_LIST_ACCOUNT_NAME' => 'Nazwa kontrahenta',
  'LBL_LIST_AMOUNT' => 'Kwota',
  'LBL_LIST_DATE_CLOSED' => 'Zamknij',
  'LBL_LIST_SALE_STAGE' => 'Etap sprzedaży',
  'LBL_ACCOUNT_ID'=>'ID kontrahenta',
  'LBL_TEAM_ID' =>'ID zespołu',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Sprzedaż — aktualizacja waluty',
  'UPDATE_DOLLARAMOUNTS' => 'Aktualizuj kwotę w USD',
  'UPDATE_VERIFY' => 'Weryfikuj kwoty',
  'UPDATE_VERIFY_TXT' => 'Sprawdza, czy wartości kwot w module sprzedaży są wyrażeniami dziesiętnymi, złożonymi wyłącznie ze znaków numerycznych (0-9) i separatorów dziesiętnych (.)',
  'UPDATE_FIX' => 'Popraw kwoty',
  'UPDATE_FIX_TXT' => 'Przeprowadza próbę naprawy niewłaściwych kwot przez utworzenie prawidłowych wyrażeń dziesiętnych. Każda modyfikacja kwoty jest zachowana w bazie danych, w polu amount_backup. Jeśli wykonasz tę operację i pojawi się błąd, nie uruchamiaj jej ponownie, zanim nie zostaną przywrócone poprzednie wartości. Może to spowodować nadpisanie danych błędnymi wartościami.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Uaktualnia kwoty w złotówkach dla sprzedaży na podstawie ustawionych obecnie kursów waluty. Ta wartość służy do tworzenia wykresów i Widoku listy kwot waluty.',
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
  'LBL_AMOUNT' => 'Kwota:',
  'LBL_AMOUNT_USDOLLAR' => 'Kwota w USD:',
  'LBL_CURRENCY' => 'Waluta:',
  'LBL_DATE_CLOSED' => 'Oczekiwana data zakończenia:',
  'LBL_TYPE' => 'Typ:',
  'LBL_CAMPAIGN' => 'Kampania:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Namiary',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekty',  
  'LBL_NEXT_STEP' => 'Następny krok:',
  'LBL_LEAD_SOURCE' => 'Źródło pozyskania:',
  'LBL_SALES_STAGE' => 'Etap sprzedaży:',
  'LBL_PROBABILITY' => 'Prawdopodobieństwo (%):',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_DUPLICATE' => 'Sprzedaż prawdopodobnie już istnieje',
  'MSG_DUPLICATE' => 'Rekord sprzedaży, który zamierzasz utworzyć, może spowodować zduplikowanie rekordu sprzedaży, który już istnieje. Rekordy sprzedaży zawierające podobne nazwy są wymienione poniżej.<br>Kliknij przycisk Zapisz, aby kontynuować tworzenie tej sprzedaży, lub kliknij przycisk Anuluj, aby powrócić do modułu bez tworzenia sprzedaży.',
  'LBL_NEW_FORM_TITLE' => 'Utwórz sprzedaż',
  'LNK_NEW_SALE' => 'Utwórz sprzedaż',
  'LNK_SALE_LIST' => 'Sprzedaż',
  'ERR_DELETE_RECORD' => 'Aby usunąć sprzedaż, należy podać numer rekordu.',
  'LBL_TOP_SALES' => 'Moje najlepsze otwarte sprzedaże',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Czy na pewno chcesz usunąć ten kontakt ze sprzedaży?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Czy na pewno chcesz usunąć tę sprzedaż z projektu?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Wydarzenia',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Historia',
    'LBL_RAW_AMOUNT'=>'Kwota początkowa',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakty',
	'LBL_ASSIGNED_TO_NAME' => 'Użytkownik:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Przypisano do',
  'LBL_MY_CLOSED_SALES' => 'Moje zamknięte sprzedaże',
  'LBL_TOTAL_SALES' => 'Wszystkie sprzedaże',
  'LBL_CLOSED_WON_SALES' => 'Zrealizowana sprzedaż',
  'LBL_ASSIGNED_TO_ID' =>'Przydzielono do (ID)',
  'LBL_CREATED_ID'=>'Utworzone przez (ID)',
  'LBL_MODIFIED_ID'=>'Zmodyfikowano przez (ID)',
  'LBL_MODIFIED_NAME'=>'Zmodyfikowane przez (nazwa użytkownika)',
  'LBL_SALE_INFORMATION'=>'Informacje o sprzedaży',
  'LBL_CURRENCY_ID'=>'ID waluty',
  'LBL_CURRENCY_NAME'=>'Nazwa waluty',
  'LBL_CURRENCY_SYMBOL'=>'Symbol waluty',
  'LBL_EDIT_BUTTON' => 'Edytuj',
  'LBL_REMOVE' => 'Usuń',
  'LBL_CURRENCY_RATE' => 'Stawka waluty',

);

