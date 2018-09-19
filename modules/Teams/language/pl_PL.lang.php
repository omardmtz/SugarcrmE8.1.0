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
    'ERR_ADD_RECORD' => 'Aby dodać użytkownika do tego zespołu, należy określić numer rekordu.',
    'ERR_DUP_NAME' => 'Nazwa zespołu już istnieje, wybierz inną.',
    'ERR_DELETE_RECORD' => 'Aby usunąć ten zespół, należy podać numer rekordu.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Błąd.  Wybrany zespół<b>({0})</b> został oznaczony do usunięcia. Wybierz inny zespół.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Błąd.  Nie można usunąć użytkownika, którego prywatny zespół nie został usunięty.',
    'LBL_DESCRIPTION' => 'Opis:',
    'LBL_GLOBAL_TEAM_DESC' => 'Widoczne globalnie',
    'LBL_INVITEE' => 'Uczestnicy zespołu',
    'LBL_LIST_DEPARTMENT' => 'Dział',
    'LBL_LIST_DESCRIPTION' => 'Opis',
    'LBL_LIST_FORM_TITLE' => 'Lista zespołów',
    'LBL_LIST_NAME' => 'Nazwa',
    'LBL_FIRST_NAME' => 'Imię:',
    'LBL_LAST_NAME' => 'Nazwisko:',
    'LBL_LIST_REPORTS_TO' => 'Zwierzchnik',
    'LBL_LIST_TITLE' => 'Tytuł',
    'LBL_MODULE_NAME' => 'Zespoły',
    'LBL_MODULE_NAME_SINGULAR' => 'Zespół',
    'LBL_MODULE_TITLE' => 'Zespoły: Strona główna',
    'LBL_NAME' => 'Nazwa zespołu:',
    'LBL_NAME_2' => 'Nazwa zespołu(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Podstawowa nazwa zespołu',
    'LBL_NEW_FORM_TITLE' => 'Nowy zespół',
    'LBL_PRIVATE' => 'Prywatne',
    'LBL_PRIVATE_TEAM_FOR' => 'Prywatny zespół dla:',
    'LBL_SEARCH_FORM_TITLE' => 'Wyszukiwanie zespołu',
    'LBL_TEAM_MEMBERS' => 'Uczestnicy zespołu',
    'LBL_TEAM' => 'Zespoły:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Użytkownicy',
    'LBL_USERS' => 'Użytkownicy',
    'LBL_REASSIGN_TEAM_TITLE' => 'Do następujących zespołów przydzielone są rekordy: <b>{0}</b><br>Przed usunięciem zespołu musisz ponownie przydzielić te rekordy do nowego zespołu. Wybierz zespół, który zastąpi poprzedni.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Ponownie przydziel',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Ponownie przydziel',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Kontynuować aktualizację rekordów, aby używały nowego zespołu?',
    'LBL_REASSIGN_TABLE_INFO' => 'Aktualizowanie tabeli {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operacja zakończyła się pomyślnie.',
    'LNK_LIST_TEAM' => 'Zespoły',
    'LNK_LIST_TEAMNOTICE' => 'Powiadomienia zespołu',
    'LNK_NEW_TEAM' => 'Utwórz zespół',
    'LNK_NEW_TEAM_NOTICE' => 'Utwórz powiadomienie dla zespołu',
    'NTC_DELETE_CONFIRMATION' => 'Czy na pewno chcesz usunąć ten rekord?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Czy na pewno chcesz usunąć członkostwo tego użytkownika?',
    'LBL_EDITLAYOUT' => 'Edytuj układ' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Uprawnienia oparte na zespole',
    'LBL_TBA_CONFIGURATION_DESC' => 'Włącz dostęp zespołu i zarządzaj dostępem przez moduł.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Włącz uprawnienie oparte na zespole',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Wybierz moduły do włączenia',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Włączenie uprawnień opartych na zespole umożliwia przypisanie określonych praw dostępu do zespołów i użytkowników dla poszczególnych modułów poprzez zarządzanie rolami.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Wyłączenie uprawnień opartych na zespole dla modułu odwróci wszelkie dane skojarzone z uprawnieniami opartymi na zespole dla tego modułu, w tym definicje procesów lub procesów wykorzystujących funkcje. Obejmuje to wszystkie role wykorzystujące opcję "Właściciel i wybrany zespół" dla tego modułu i dane uprawnień opartych na zespole dla rekordów w tym module. Zalecamy również wykorzystanie narzędzi szybkiej naprawy i przebudowy do wyczyszczenia pamięci podręcznej systemu po wyłączeniu uprawnień opartych na zespole dla danego modułu.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Ostrzeżenie!</strong> Wyłączenie uprawnień opartych na zespole dla modułu odwróci wszelkie dane skojarzone z uprawnieniami opartymi na zespole dla tego modułu, w tym definicje procesów lub procesów wykorzystujących funkcje. Obejmuje to wszystkie role wykorzystujące opcję "Właściciel i wybrany zespół" dla tego modułu i dane uprawnień opartych na zespole dla rekordów w tym module. Zalecamy również wykorzystanie narzędzi szybkiej naprawy i przebudowy do wyczyszczenia pamięci podręcznej systemu po wyłączeniu uprawnień opartych na zespole dla danego modułu.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Wyłączenie uprawnień opartych na zespole dla modułu odwróci wszelkie dane skojarzone z uprawnieniami opartymi na zespole dla tego modułu, w tym definicje procesów lub procesów wykorzystujących funkcje. Obejmuje to wszystkie role wykorzystujące opcję "Właściciel i wybrany zespół" dla tego modułu i dane uprawnień opartych na zespole dla rekordów w tym module. Zalecamy również wykorzystanie narzędzi szybkiej naprawy i przebudowy do skasowania pamięci podręcznej systemu po wyłączeniu uprawnień opartych na zespole dla danego modułu. Jeśli nie masz dostępu do usługi szybkiej naprawy i przebudowy, skontaktuj się z administratorem z dostępem do menu naprawy.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Ostrzeżenie!</strong> Wyłączenie uprawnień opartych na zespole dla modułu odwróci wszelkie dane skojarzone z uprawnieniami opartymi na zespole dla tego modułu, w tym definicje procesów lub procesów wykorzystujących funkcje. Obejmuje to wszystkie role wykorzystujące opcję "Właściciel i wybrany zespół" dla tego modułu i dane uprawnień opartych na zespole dla rekordów w tym module. Zalecamy również wykorzystanie narzędzi szybkiej naprawy i przebudowy do skasowania pamięci podręcznej systemu po wyłączeniu uprawnień opartych na zespole dla danego modułu. Jeśli nie masz dostępu do usługi szybkiej naprawy i przebudowy, skontaktuj się z administratorem z dostępem do menu naprawy.
STR
,
);
