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
    'LBL_LOADING' => 'Ładowanie' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Ukryj opcje' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Usuń' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Oparte na systemie SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Rola',
'help'=>array(
    'package'=>array(
            'create'=>'Wprowadź <b>Nazwę</b> pakietu. Nazwa pakietu musi zaczynać się od litery i może zawierać wyłącznie znaki alfanumeryczne i podkreślenia. Nie może zawierać spacji ani znaków specjalnych. (Przykład: HR_Zarządzanie)<br/><br/> Możesz podać <b>Autora</b> i <b>Opis</b> pakietu. <br/><br/>Kliknij <b>Zapisz</b>, aby utworzyć pakiet.',
            'modify'=>'Właściwości i możliwe działania dla <b>pakietu</b> pojawiają się tutaj.<br><br>Możesz zmodyfikować <b>Nazwę</b>, <b>Autora</b> oraz <b>Opis</b> pakietu, widok pakietu oraz wszystkie moduły w nim zawarte.<br><br>Kliknij <b>Nowy moduł</b>, aby utworzyć moduł dla pakietu.<br><br>Jeśli pakiet zawiera przynajmniej jeden moduł, możesz zarówno <b>Opublikować</b> i <b>Zamieścić</b> pakiet, jak również <b>Eksportować</b> modyfikacje dokonane w pakiecie.',
            'name'=>'To jest <b>Nazwa</b> bieżącego pakietu. <br/><br/>Nazwa musi rozpoczynać się od litery i zawierać tylko znaki alfanumeryczne i podkreślenia. Nie może zawierać spacji i znaków specjalnych. (Przykład: HR_Zarządzanie)',
            'author'=>'To jest <b>Autor</b>, który jest wyświetlany podczas instalacji jako twórca pakietu.<br><br>Autor może być zarówno osobą, jak i firmą.',
            'description'=>'To jest <b>Opis</b> pakietu wyświetlany podczas instalacji.',
            'publishbtn'=>'Kliknij <b>Publikuj</b>, aby zapisać wszystkie wprowadzone dane i utworzyć plik .zip, który jest instalacyjną wersją pakietu.<br><br>Użyj <b>Zarządzania modułami</b>, aby załadować plik .zip i zainstalować pakiet.',
            'deploybtn'=>'Kliknij <b>Zamieść</b>, aby zapisać wprowadzone dane i zainstalować pakiet zawierający wszystkie moduły na tej instancji.',
            'duplicatebtn'=>'Kliknij <b>Duplikuj</b>, aby skopiować zawartość pakietu do nowego pakietu i wyświetlić nowy pakiet. <br/><br/>Nazwa nowego pakietu zostanie wygenerowana automatycznie poprzez dodanie numeru do nazwy pakietu, z którego został utworzony. Możesz zmienić nazwę nowego pakietu przez wprowadzenie nowej <b>Nazwy</b> i kliknięcie <b>Zapisz</b>.',
            'exportbtn'=>'Kliknij <b>Eksport</b> aby utworzyć plik .zip, zawierający modyfikacje utworzone w pakiecie.<br><br> Wygenerowany plik nie jest plikiem instalacyjnym pakietu.<br><br>Poprzez <b>Zarządzanie modułami</b> można zaimportować plik .zip i wyświetlić pakiet wraz z modyfikacjami w Kreatorze modułów.',
            'deletebtn'=>'Kliknij <b>Usuń</b>, aby usunąć pakiet i wszystkie powiązane z nim pliki.',
            'savebtn'=>'Kliknij <b>Zapisz</b>, aby zapisać wszystkie wprowadzone dane powiązane z pakietem.',
            'existing_module'=>'Kliknij ikonę <b>Moduł</b>, aby edytować właściwości i dostosować pola, relacje i widoki, powiązane z tym modułem.',
            'new_module'=>'Kliknij <b>Utwórz moduł</b>, aby utworzyć nowy moduł dla tego pakietu.',
            'key'=>'Ten pięcioliterowy, alfanumeryczny <b>Klucz</b> będzie używany jako przedrostek we wszystkich katalogach, nazwach klasy i tabelach baz danych dla każdego modułu tego pakietu.<br><br>Klucz jest używany w celu zapewnienia unikatowości nazw tabel.',
            'readme'=>'Kliknij, aby dodać tekst do pliku <b>Readme</b> dla tego pakietu.<br><br>Będzie on wyświetlany podczas instalacji.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Podaj <b>Nazwę</b> modułu. <b>Etykieta</b>, którą wprowadzisz, pojawi się w zakładce nawigacyjnej. <br/><br/>Aby można było wyświetlać zakładkę nawigacyjną w module, należy zaznaczyć opcję <b>Zakładka nawigacyjna</b>.<br/>/><br/>Zaznacz pole <b>Bezpieczeństwo zespołu</b>, aby pole wyboru zespołu pojawiło się w rekordach modułu. <br/><br/>Następnie wybierz typ modułu, który chcesz utworzyć. <br/><br/>Wybierz typ szablonu. Każdy z szablonów zawiera określony zestaw pól oraz wstępnie zdefiniowane widoki, używane jako baza dla modułu. <br/><br/>Kliknij <b>Zapisz</b>, aby utworzyć moduł.',
        'modify'=>'Istnieje możliwość zmiany właściwości modułu lub dostosowania opcji <b>Pola</b>, <b>Relacje</b> i <b>Widoki</b> powiązanych z modułem.',
        'importable'=>'Zaznaczenie pola <b>Importowalny</b> umożliwi wykonywanie importu dla tego modułu.<br><br>Łącze do Kreatora importu pojawi się w panelu Skróty modułu. Kreator importu pozwala importować dane z zewnętrznych źródeł do własnego modułu.',
        'team_security'=>'Zaznaczenie pola <b>Bezpieczeństwo zespołu</b> spowoduje włączenie funkcji zabezpieczenia dostępu na poziomie zespołu dla tego modułu. <br/><br/>Jeśli funkcja jest włączona dla tego modułu, pola wyboru zespołu pojawią się w rekordach tego modułu. ',
        'reportable'=>'Zaznaczenie tego pola umożliwi wykonanie raportów z udziałem tego modułu.',
        'assignable'=>'Zaznaczenie tego pola umożliwi przydzielenie rekordów w tym module do wybranego użytkownika.',
        'has_tab'=>'Zaznaczenie pola <b>Zakładka nawigacyjna</b> spowoduje wyświetlanie karty nawigacyjnej dla tego modułu.',
        'acl'=>'Zaznaczenie tego pola włączy funkcję Kontroli dostępu w tym module, łącznie z Zabezpieczeniem na poziomie pól.',
        'studio'=>'Zaznaczenie tego pola umożliwia administratorom dostosowanie ten moduł w Studio.',
        'audit'=>'Zaznaczenie tego powoduje włączenie śledzenia historii zmian w tym module. Zmiany w poszczególnych polach będą rejestrowane, aby administratorzy mogli zobaczyć ich historię.',
        'viewfieldsbtn'=>'Kliknij <b>Wyświetl pola</b>, aby zobaczyć pola powiązane z modułem i utworzyć lub edytować własne pola.',
        'viewrelsbtn'=>'Kliknij <b>Wyświetl relacje</b>, aby zobaczyć relacje tego modułu z innymi lub utworzyć nowe.',
        'viewlayoutsbtn'=>'Kliknij <b>Wyświetl widoki</b>, aby zobaczyć widoki modułu i ustawić własny układ pól w tych widokach.',
        'viewmobilelayoutsbtn' => 'Kliknij <b>Wyświetl mobilne widoki</b>, aby zobaczyć mobilne widoki dla modułu i spersonalizować układ pól w widokach.',
        'duplicatebtn'=>'Kliknij <b>Duplikuj</b>, aby skopiować właściwości tego modułu do nowego modułu i wyświetlić go. <br/><br/> Nazwa dla nowego modułu jest generowana automatycznie poprzez dodanie numeru na końcu nazwy modułu pierwotnego.',
        'deletebtn'=>'Kliknij <b>Usuń</b>, aby usunąć ten moduł.',
        'name'=>'To jest <b>Nazwa</b> edytowanego modułu. <br/><br/>Nazwa musi składać się ze znaków alfanumerycznych, zaczynać od litery i nie zawierać spacji. (Przykład: HR_Zarządzanie)',
        'label'=>'To jest <b>Etykieta</b>, która pojawi się w zakładce nawigacyjnej modułu.',
        'savebtn'=>'Kliknij <b>Zapisz</b>, aby zachować dane powiązane z modułem.',
        'type_basic'=>'<b>Podstawowy</b> typ szablonu zawiera domyślne pola, takie jak Nazwa, Przydzielono do, Zespół, Data utworzenia i Opis.',
        'type_company'=>'Szablon <b>Firma</b> zawiera określone pola, takie jak Nazwa firmy, Branża i Adres fakturowania.<br/><br/>Użyj tego szablonu do tworzenia modułów podobnych do modułu Kontrahenci.',
        'type_issue'=>'Szablon <b>Zagadnienie</b> zawiera pola typowe dla Zgłoszeń i Błędów, takie jak Numer, Status, Priorytet i Opis.<br/><br/>Użyj tego szablonu do tworzenia modułów podobnych do modułów Zgłoszeń i Śledzenia błędów.',
        'type_person'=>'Szablon <b>Osoba</b> zawiera pola typowe dla określenia osoby, takie jak Forma grzecznościowa, Stanowisko, Imię i Nazwisko, Adres oraz Numer telefonu.<br/><br/>Użyj tego szablonu do tworzenia modułów podobnych do modułów Kontakty i Namiary.',
        'type_sale'=>'Szablon <b>Sprzedaż</b> zawiera pola właściwe dla szans sprzedażowych, takie jak Źródło pozyskania, Etap, Kwota i Prawdopodobieństwo. <br/><br/>Użyj tego szablonu do tworzenia modułów podobnych do standardowego modułu Szans.',
        'type_file'=>'Szablon <b>Plik</b> zawiera pola typowe dla modułu Dokumentów, takie jak Nazwa pliku, Typ dokumentu i Data publikacji oraz pozwala na dodawanie dowolnych plików do rekordu.<br><br>Użyj tego szablonu do tworzenia modułów podobnych do modułu Dokumentów.',

    ),
    'dropdowns'=>array(
        'default' => 'Wszystkie <b>listy rozwijane</b> aplikacji są dostępne w tym miejscu.<br><br>List można użyć do tworzenia list rozwijanych w dowolnym module.<br><br>Aby wprowadzić zmiany w istniejącej liście, kliknij na jej nazwę.<br><br>Kliknij <b>Dodaj listę rozwijaną</b>, aby utworzyć nową.',
        'editdropdown'=>'List rozwijanych można użyć do pól standardowych i własnych w dowolnym module.<br><br>Wprowadź <b>Nazwę</b> dla listy rozwijanej.<br><br>Jeżeli w aplikacji są zainstalowane pakiety językowe, możesz określić <b>Język</b>, w jakim będą wyświetlane elementy listy.<br><br>W polu <b>Nazwa elementu</b> wprowadź nazwę dla opcji w liście rozwijanej. Nazwa ta nie będzie wyświetlana na liście rozwijanej widocznej dla użytkowników.<br><br>W polu <b>Wyświetlana etykieta</b> wprowadź etykietę, która będzie widoczna dla użytkowników.<br><br>Po wprowadzeniu nazwy elementu i etykiety, kliknij <b>Dodaj</b>, aby dodać nowy element do listy rozwijanej.<br><br>W celu zmiany kolejności elementów listy przeciągnij je i upuść w żądanym miejscu.<br><br>Aby edytować etykietę elementu, kliknij <b>ikonę Edytuj</b> i wprowadź nową etykietę. Aby usunąć element z listy rozwijanej, kliknij <b>ikonę Usuń</b>.<br><br>Aby cofnąć zmiany wprowadzone w etykietach, kliknij <b>Cofnij</b>.  Aby przywrócić cofnięte zmiany, kliknij <b>Przywróć</b>.<br><br>Kliknij <b>Zapisz</b>, aby zapisać listę rozwijaną.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'Wszystkie pola, które można wyświetlać w <b>Panelu podrzędnym</b>, są wyświetlane tutaj.<br><br>Kolumna <b>Domyślne</b> zawiera pola wyświetlane domyślnie w panelu podrzędnym.<br/><br/>Kolumna <b>Ukryte</b> zawiera pola, które można dodać do kolumny Domyślne.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        'savebtn'	=> 'Kliknij <b>Zapisz i zamieść</b>, aby zachować zmiany i aktywować je w module.',
        'historyBtn'=> 'Kliknij <b>Wyświetl historię</b>, aby wyświetlić i przywrócić poprzednio zapisany układ z historii.',
        'historyRestoreDefaultLayout'=> 'Kliknij opcję <b>Przywróć domyślny układ</b>, aby przywrócić widok do oryginalnego układu.',
        'Hidden' 	=> '<b>Ukryte</b> pola nie są wyświetlane w panelu podrzędnym.',
        'Default'	=> '<b>Domyślne</b> pola są wyświetlane w panelu podrzędnym.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'Wszystkie pola, które można wyświetlać w <b>Widoku listy</b>, są wyświetlane tutaj.<br><br>Kolumna <b>Domyślne</b> zawiera pola wyświetlane domyślnie w widoku listy.<br/><br/>Kolumna <b>Dostępne</b> zawiera pola, które użytkownik może wybrać w polu Wyszukiwanie, aby utworzyć niestandardowy widok listy. <br/><br/>Kolumna <b>Ukryte</b> zawiera pola, które można dodać do kolumn Domyślne lub Dostępne.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        'savebtn'	=> 'Kliknij <b>Zapisz i zamieść</b>, aby zachować zmiany i aktywować je w module.',
        'historyBtn'=> 'Kliknij <b>Wyświetl historię</b>, aby zobaczyć historię zmian lub przywrócić poprzednio zachowany układ. <br><br>Kliknięcie<b>Przywróć</b> w obszarze <b>Wyświetl historię</b> przywraca położenie pól z poprzednio zapisanych układów. Aby zmienić etykiety pól, kliknij przycisk edycji znajdujący się obok każdego pola.',
        'historyRestoreDefaultLayout'=> 'Kliknij opcję <b>Przywróć domyślny układ</b>, aby przywrócić widok do oryginalnego układu.<br><br>Opcja <b>Przywróć domyślny układ</b> powoduje tylko przywrócenie rozmieszczenia pól w ramach oryginalnego układu. Aby zmienić etykiety pól, kliknij ikonę Edycja obok danego pola.',
        'Hidden' 	=> 'Pola <b>Ukryte</b> nie są obecnie dostępne dla użytkowników w widokach listy.',
        'Available' => 'Pola <b>Dostępne</b> nie są widoczne domyślnie, ale użytkownicy mogą je dodać do widoków listy.',
        'Default'	=> 'Pola <b>Domyślne</b>, widoczne w widokach listy, nie są edytowalne przez użytkowników.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'Wszystkie pola, które można wyświetlać w <b>Widoku listy</b>, są wyświetlane tutaj.<br><br>Kolumna <b>Domyślne</b> zawiera pola wyświetlane domyślnie w widoku listy.<br/><br/>Kolumna <b>Ukryte</b> zawiera pola, które można dodać do kolumn Domyślne lub Dostępne.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        'savebtn'	=> 'Kliknij <b>Zapisz i zamieść</b>, aby zachować zmiany i aktywować je w module.',
        'historyBtn'=> 'Kliknij <b>Wyświetl historię</b>, aby zobaczyć historię zmian lub przywrócić poprzednio zachowany układ. <br><br>Kliknięcie<b>Przywróć</b> w obszarze <b>Wyświetl historię</b> przywraca położenie pól z poprzednio zapisanych układów. Aby zmienić etykiety pól, kliknij przycisk edycji znajdujący się obok każdego pola.',
        'historyRestoreDefaultLayout'=> 'Kliknij opcję <b>Przywróć domyślny układ</b>, aby przywrócić widok do oryginalnego układu.<br><br>Opcja <b>Przywróć domyślny układ</b> powoduje tylko przywrócenie rozmieszczenia pól w ramach oryginalnego układu. Aby zmienić etykiety pól, kliknij ikonę Edycja obok danego pola.',
        'Hidden' 	=> 'Pola <b>Ukryte</b> nie są obecnie dostępne dla użytkowników w widokach listy.',
        'Default'	=> 'Pola <b>Domyślne</b>, widoczne w widokach listy, nie są edytowalne przez użytkowników.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'Wszystkie pola, które można wyświetlać w formularzu <b>Wyszukiwanie</b>, są wyświetlane tutaj.<br><br>Kolumna <b>Domyślne</b> zawiera pola wyświetlane w formularzu Wyszukiwanie.<br/><br/>Kolumna <b>Ukryte</b> zawiera pola, które można dodać do formularza Wyszukiwanie.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    . '<br/><br/>Ta konfiguracja ma zastosowanie do układu wyszukiwania w wyskakującym okienku tylko w starszych modułach.',
        'savebtn'	=> 'Kliknij <b>Zapisz i zamieść</b>, aby zapisać wszystkie zmiany i uaktywnić je w module.',
        'Hidden' 	=> 'Pola <b>Ukryte</b> nie są wyświetlane w obszarze Wyszukiwania.',
        'historyBtn'=> 'Kliknij <b>Wyświetl historię</b>, aby zobaczyć historię zmian lub przywrócić poprzednio zachowany układ. <br><br>Kliknięcie<b>Przywróć</b> w obszarze <b>Wyświetl historię</b> przywraca położenie pól z poprzednio zapisanych układów. Aby zmienić etykiety pól, kliknij przycisk edycji znajdujący się obok każdego pola.',
        'historyRestoreDefaultLayout'=> 'Kliknij opcję <b>Przywróć domyślny układ</b>, aby przywrócić widok do oryginalnego układu.<br><br>Opcja <b>Przywróć domyślny układ</b> powoduje tylko przywrócenie rozmieszczenia pól w ramach oryginalnego układu. Aby zmienić etykiety pól, kliknij ikonę Edycja obok danego pola.',
        'Default'	=> 'Pola <b>Domyślne</b> są wyświetlane w obszarze Wyszukiwanie.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'Obszar <b>Układ</b> zawiera pola, które są aktualnie wyświetlane w ramach formularza <b>Widok szczegółowy</b>.<br/><br/>Obszar <b>Narzędzia</b> zawiera <b>Kosz</b> oraz pola i elementy układu, które można dodać do układu.<br><br>Zmiany w układzie wprowadza się, przeciągając elementy i upuszczając je pomiędzy obszarami <b>Narzędzia</b> i <b>Układ</b> oraz z samym układzie.<br><br>Aby usunąć pole z układu, przeciągnij pole do <b>Kosza</b>. Pole będzie wówczas dostępne w obszarze Narzędzia do dodania do układu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        'defaultquickcreate'=>'Obszar <b>Układ</b> zawiera pola, które są aktualnie wyświetlane w ramach formularza <b>QuickCreate<b>.<br><br> Formularz QuickCreate pojawia się w panelach podrzędnych dla modułu po kliknięciu przycisku Utwórz.<br/><br/>Obszar <b>Narzędzia</b> zawiera <b>Kosz</b> oraz pola i elementy układu, które można dodać do układu.<br><br>Zmiany w układzie wprowadza się przeciągając elementy i upuszczając je pomiędzy obszarami <b>Narzędzia</b> i <b>Układ</b> oraz w obrębie samego układu.<br><br>Aby usunąć pole z układu, przeciągnij pole do <b>Kosza</b>. Pole będzie wówczas dostępne w obszarze Narzędzia do dodania do układu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'Obszar <b>Układ</b> zawiera pola, które są aktualnie wyświetlane w ramach <b>Widoku edycji</b>.<br/><br/>Obszar <b>Narzędzia</b> zawiera <b>Kosz</b> oraz pola i elementy układu, które można dodać do układu.<br><br>Zmiany w układzie wprowadza się, przeciągając elementy i upuszczając je pomiędzy obszarami <b>Narzędzia</b> i <b>Układ</b> oraz z samym układzie.<br><br>Aby usunąć pole z układu, przeciągnij pole do <b>Kosza</b>. Pole będzie wówczas dostępne w obszarze Narzędzia do dodania do układu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'Obszar <b>Układ</b> zawiera pola, które są aktualnie wyświetlane w ramach formularza <b>Widok rekordów</b>.<br/><br/>Obszar <b>Narzędzia</b> zawiera <b>Kosz</b> oraz pola i elementy układu, które można dodać do układu.<br><br>Zmiany w układzie wprowadza się, przeciągając elementy i upuszczając je pomiędzy obszarami <b>Narzędzia</b> i <b>Układ</b> oraz z samym układzie.<br><br>Aby usunąć pole z układu, przeciągnij pole do <b>Kosza</b>. Pole będzie wówczas dostępne w obszarze Narzędzia do dodania do układu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Określa pole zależne, które może być widoczne lub niewidoczne w zależności od wartości wzoru.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Określa obliczone pole, którego wartość zostanie automatycznie określona na podstawie wzoru.'
    ,
        'saveBtn'	=> 'Kliknij <b>Zapisz</b>, aby zachować zmiany wprowadzone w układzie od ostatniego zapisu.<br><br>Zmiany nie będą wyświetlone w module, dopóki ich nie zastosujesz.',
        'historyBtn'=> 'Kliknij <b>Wyświetl historię</b>, aby zobaczyć historię zmian lub przywrócić poprzednio zachowany układ. <br><br>Kliknięcie<b>Przywróć</b> w obszarze <b>Wyświetl historię</b> przywraca położenie pól z poprzednio zapisanych układów. Aby zmienić etykiety pól, kliknij przycisk edycji znajdujący się obok każdego pola.',
        'historyRestoreDefaultLayout'=> 'Kliknij opcję <b>Przywróć domyślny układ</b>, aby przywrócić widok do oryginalnego układu.<br><br>Opcja <b>Przywróć domyślny układ</b> powoduje tylko przywrócenie rozmieszczenia pól w ramach oryginalnego układu. Aby zmienić etykiety pól, kliknij ikonę Edycja obok danego pola.',
        'publishBtn'=> 'Kliknij <b>Zapisz i zamieść</b>, aby zapisać wszystkie zmiany wprowadzone od ostatniego zapisu i uaktywnić je w module.<br><br>Widok zostanie niezwłocznie wyświetlony w module.',
        'toolbox'	=> 'Opcja <b>Narzędzia</b> zawiera <b>Kosz</b>, dodatkowe elementy układu i zestaw dostępnych pól do dodania do układu.<br/><br/>Elementy układu i pola w Narzędziach można przeciągać i upuszczać do widoku i na odwrót.<br><br>Elementami układu są <b>Panele</b> i <b>Wiersze</b>. Dodanie nowego wiersza lub panelu do układu wprowadza dodatkowe miejsce dla pól.<br/><br/>Przeciągnij i upuść pola w obrębie Narzędzi lub układu na miejsce zajmowane już przez inne pole, aby zmienić pozycję obu pól.<br/><br/>Pole <b>Wypełnienie</b> tworzy pustą przestrzeń w układzie, w miejscu, w którym zostanie umieszczone.',
        'panels'	=> 'Obszar <b>Układ</b> przedstawia podgląd tego, jak układ będzie wyglądał w module, gdy zmiany zostaną zapisane i zastosowane.<br/><br/>Możesz zmienić pozycję pól, wierszy i paneli przez przeciągnięcie ich w pożądane miejsce.<br/><br/>Usuwanie elementów następuje przez przeciągnięcie i upuszczenie ich do <b>Kosza</b> w obszarze Narzędzia. Dodawanie elementów do widoku odbywa się poprzez przeciągnięcie ich z obszaru <b>Narzędzia</b> i upuszczenie w określonym miejscu w układu.',
        'delete'	=> 'Przeciągnij i upuść dowolny element, aby usunąć go z układu',
        'property'	=> 'Edytuj <b>Etykietę</b> wyświetlaną dla tego pola. <br><br><b>Szerokość</b> określa szerokość w pikselach dla modułów bocznych oraz jako procent szerokości tabeli w przypadku modułów zgodnych z poprzednimi wersjami.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>Pola</b> dostępne w tym module są wymienione tutaj wg wartości Nazwa pola.<br><br>Własne pola utworzone w module są wyświetlane nad polami dostępnymi domyślnie w module.<br><br>Aby edytować pole, kliknij <b>Nazwa pola</b>.<br/><br/>Aby utworzyć nowe pole, kliknij <b>Dodaj pole</b>.',
        'mbDefault'=>'<b>Pola</b> dostępne w tym module są wymienione tutaj wg wartości Nazwa pola.<br><br>Aby skonfigurować właściwości dla pola, kliknij jego nazwę.<br><br>Aby utworzyć nowe pole, kliknij <b>Dodaj pole</b>. Etykietę wraz z właściwościami utworzonego pola można edytować później przez kliknięcie nazwy pola.<br><br>Po zamieszczeniu modułu nowe pola utworzone w Kreatorze modułów są traktowane jako standardowe pola w zamieszczonym module w Studio.',
        'addField'	=> 'Wybierz <b>Typ danych</b> dla nowego pola. Typ, który wybierzesz, określa typ znaków wprowadzanych do pola. Na przykład: w polach typu Liczba całkowita można wprowadzać tylko liczby całkowite.<br><br> Określ <b>Nazwę</b> dla pola. Nazwa musi składać się ze znaków alfanumerycznych  i nie zawierać żadnych spacji. Dozwolone są podkreślenia.<br><br> <b>Etykieta wyświetlana</b> to etykieta, która jest wyświetlana się w układach modułu. <b>Etykieta systemowa</b> jest używana do określenia pola w kodzie.<br><br> Zależenie od wybranego typu danych pola, można określić dla pola niektóre lub wszystkie z wymienionych właściwości:<br><br> <b>Tekst pomocy</b> jest wyświetlany tymczasowo, gdy użytkownik wskazuje kursorem pole i może informować użytkownika o żądanym typie danych.<br><br> <b>Tekst komentarza</b> jest widziany tylko w Studio i/lub w Kreatorze Modułów i może opisywać pole dla administratorów.<br><br> <b>Wartość domyślna</b> jest widoczna w polu.  Użytkownicy mogą wprowadzić nową wartość lub pozostawić domyślną.<br><br> Zaznacz pole wyboru <b>Masowa aktualizacja</b>, aby umożliwić masową aktualizację dla pola.<br><br>Wartość <b>Maksymalny rozmiar</b> określa maksymalną liczbę znaków, które można wprowadzić w polu.<br><br> Zaznacz opcję <b>Pole wymagane</b>, aby uzupełnienie danego pola było wymagane. Konieczne jest wprowadzenie wartości w polu, aby można było zapisać rekord zawierający to pole.<br><br> Zaznacz pole wyboru <b>Raportowalne</b>, aby można było użyć pola w filtrach oraz aby dane były wyświetlane w Raportach.<br><br> Zaznacz pole wyboru <b>Historia zmian</b>, aby móc śledzić zmiany na danych polach w rejestrze zmian.<br><br>Wybierz opcję w polu <b>Importowalne</b>, aby zezwolić na import pola, zabronić go lub wymagać.<br><br>Wybierz opcję w polu <b>Scalanie duplikatów</b>, aby włączyć lub wyłączyć funkcję scalania i wyszukiwania duplikatów.<br><br>Dodatkowe właściwości można ustawić dla określonego typu danych.',
        'editField' => 'Właściwości tego pola można spersonalizować.<br><br>Kliknij <b>Duplikuj</b>, aby utworzyć nowe pole z takimi samymi właściwościami.',
        'mbeditField' => 'Opcję <b>Etykieta wyświetlana</b> pola szablonu można zmodyfikować. Innych właściwości pola nie można dostosować.<br><br>Kliknij <b>Duplikuj</b>, aby utworzyć nowe pole z takimi samymi właściwościami.<br><br>Aby usunąć pole szablonu tak, aby nie było widoczne w module, usuń je z odpowiedniego <b>Układu</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Eksportuj zmiany wprowadzone w module Studio, tworząc pakiety, które można wczytać w innej instancji Sugar za pomocą narzędzia <b>Zarządzanie modułami</b>. <br><br>Wprowadź najpierw <b>Nazwę pakietu</b>. W pakiecie można również zamieścić informacje o <b>Autorze</b> i <b>Opis</b>.<br><br>Wybierz moduły zawierające zmiany, które chcesz wyeksportować.<br><br>Kliknij <b>Eksport</b>, aby stworzyć plik .zip pakietu zawierającego zmiany.',
        'exportCustomBtn'=>'Kliknij <b>Eksport</b>, aby utworzyć plik .zip pakietu zawierającego dostosowania, które chcesz wyeksportować.',
        'name'=>'To jest <b>Nazwa</b> pakietu, która będzie wyświetlana podczas instalacji.',
        'author'=>'Wartość <b>Autor</b> wyświetlana podczas instalacji nazwa podmiotu, który stworzył pakiet. Może to być osoba lub firma.',
        'description'=>'To jest <b>Opis</b> pakietu wyświetlany podczas instalacji.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Witamy w obszarze <b>Narzędzia Programisty</b>. <br/><br/>W tym obszarze możesz używać narzędzi do tworzenia standardowych i dostosowywanych modułów i pól oraz zarządzania nimi.',
        'studioBtn'	=> 'Użyj <b>Studio</b>, aby dostosować zainstalowane moduły.',
        'mbBtn'		=> 'Użyj <b>Kreatora Modułów</b>, aby tworzyć moduły.',
        'sugarPortalBtn' => 'Użyj <b>Edytora portalu Sugar</b> do zarządzania portalem Sugar i modyfikowania go.',
        'dropDownEditorBtn' => 'Użyj <b>Edytora list rozwijanych</b>, aby dodać i edytować globalne listy rozwijane.',
        'appBtn' 	=> 'Tryb aplikacji jest stosowany w przypadku dostosowywania różnych właściwości programu, takich jak np. ile raportów TPS jest wyświetlanych na stronie głównej',
        'backBtn'	=> 'Powrót do poprzedniego kroku.',
        'studioHelp'=> 'Użyj <b>Studio</b>, aby określić zakres informacyjny poszczególnych modułów.',
        'studioBCHelp' => ' wskazuje, że moduł jest modułem zgodnym z poprzednimi wersjami',
        'moduleBtn'	=> 'Kliknij, aby edytować ten moduł.',
        'moduleHelp'=> 'Tutaj wyświetlane są składniki, które można dostosować w module.<br><br>Kliknij ikonę, aby wybrać składniki do edycji.',
        'fieldsBtn'	=> 'Utwórz i dostosuj <b>Pola</b> do przechowywania informacji w module.',
        'labelsBtn' => 'Edytuj <b>Etykiety</b>, które są wyświetlane dla pól i innych elementów w module.'	,
        'relationshipsBtn' => 'Dodaj nowe lub wyświetl istniejące <b>Relacje</b> dla modułu.' ,
        'layoutsBtn'=> 'Dostosuj <b>Układy</b> modułu.  Układy to różne widoki modułu zawierającego pola.<br><br>Możesz ustalić, które pola będą widoczne i jak zostaną uszeregowane w każdym z układzie.',
        'subpanelBtn'=> 'Ustal, które pola są wyświetlane w <b>Panelach podrzędnych</b> modułu.',
        'portalBtn' =>'Dostosuj <b>Układy</b> modułu, które są wyświetlane w <b>Portalu Sugar</b>.',
        'layoutsHelp'=> '<b>Układy</b> modułu, które można dostosować, są wyświetlone tutaj.<br><br>W układach wyświetlane s pola i dane w polach.<br><br>Kliknij ikonę, aby wybrać układ do edycji.',
        'subpanelHelp'=> '<b>Panele podrzędne</b> w module, które można dostosować, są widoczne tutaj.<br><br>Kliknij ikonę, aby wybrać moduł do edycji.',
        'newPackage'=>'Kliknij <b>Nowy Pakiet</b>, aby utworzyć nowy pakiet.',
        'exportBtn' => 'Kliknij <b>Eksport dostosowań</b>, aby utworzyć i pobrać pakiet zawierający modyfikacje utworzone w Studio dla określonych modułów.',
        'mbHelp'    => '<b>Kreator Modułów</b> służy do tworzenia pakietów zawierających dostosowane moduły, bazujące na standardowych lub niestandardowych obiektach.',
        'viewBtnEditView' => 'Dostosuj układ <b>Widoku edycji</b> modułu.<br><br>Widok edycji to formularz zawierający pola wprowadzania służce do zapisywania danych wprowadzonych przez użytkownika.',
        'viewBtnDetailView' => 'Dostosuj układ <b>Widoku szczegółowego</b> modułu.<br><br>W Widoku szczegółowym wyświetlane są dane wprowadzone przez użytkownika w polach.',
        'viewBtnDashlet' => 'Dostosuj <b>Dashlety Sugar</b> modułu, łącznie z widokiem listy i wyszukiwaniem dashletów Sugar.<br><br> Dashlet Sugar będzie można dodać stron w module głównym.',
        'viewBtnListView' => 'Dostosuj <b>Widok listy</b> modułu.<br><br>Wynik wyszukiwania pojawi się w widoku listy.',
        'searchBtn' => 'Dostosuj układ <b>Wyszukiwanie</b> modułu.<br><br>Określ, jakie pola mogą zostać użyte do filtrowania rekordów, które pojawią się w widoku listy.',
        'viewBtnQuickCreate' =>  'Dostosuj układ <b>Szybkie tworzenie</b> modułu.<br><br>Formularz Szybkiego tworzenia jest wyświetlany w panelach podrzędnych i w module poczty.',

        'searchHelp'=> 'Formularze <b>Wyszukiwania</b>, które można dostosować są wyświetlane tutaj.<br><br>Formularz zawiera pola służące do filtrowania rekordów.<br><br>Kliknij ikonę, aby wyszukać układ do edycji.',
        'dashletHelp' =>'Układy <b>Dashlet Sugar</b>, które można dostosować, są wyświetlane tutaj.<br><br>Dashlety Sugar można dodawać do stron w module głównym.',
        'DashletListViewBtn' =>'<b>Widok listy dashletów Sugar</b> wyświetla rekordy bazujące na filtrach wyszukiwania dashletów Sugar.',
        'DashletSearchViewBtn' =>'<b>Wyszukiwanie dashletów Sugar</b> filtruje rekordy wyświetlane w widoku listy dashletu Sugar.',
        'popupHelp' =>'Układ <b>Wyskakujące okienka</b>, który można dostosować, będzie wyświetlany tutaj.<br>',
        'PopupListViewBtn' => 'Układ <b>Widok listy wyskakującego okienka</b> służy do wyświetlania listy rekordów podczas wybierania jednego lub kilku rekordów odnoszących się do bieżącego rekordu.',
        'PopupSearchViewBtn' => 'Układ <b>Wyszukiwanie wyskakującego okienka</b> umożliwia użytkownikom wyszukiwanie rekordów do powiązania z bieżącym rekordem i pojawia się powyżej widoku listy wyskakującego okienka w tym samym oknie. Starsze moduły wykorzystują ten układ do wyszukiwania wyskakujących okienek, gdy moduły Sidecar wykorzystują układy Wyszukiwanie jako konfigurację.',
        'BasicSearchBtn' => 'Dostosuj formularz <b>Wyszukiwanie podstawowe</b>, który jest wyświetlany w zakładce Wyszukiwanie podstawowe w obszarze Wyszukiwanie dla modułu.',
        'AdvancedSearchBtn' => 'Dostosuj formularz <b>Wyszukiwanie zaawansowane</b>, który jest wyświetlany w zakładce Wyszukiwanie zaawansowane w obszarze Wyszukiwanie dla modułu.',
        'portalHelp' => 'Dostosuj <b>Portal Sugar</b> i zarządzaj nim.',
        'SPUploadCSS' => 'Załaduj <b>Arkusz stylów</b> dla Portalu Sugar.',
        'SPSync' => '<b>Synchronizuj</b> dostosowywane elementy z instancją Portalu Sugar.',
        'Layouts' => 'Dostosuj <b>Układy</b> modułów Portalu Sugar.',
        'portalLayoutHelp' => 'Moduły Portalu Sugar są wyświetlane w tym obszarze.<br><br>Wybierz moduł, aby móc edytować <b>Układy</b>.',
        'relationshipsHelp' => 'Wszystkie <b>Relacje</b>, które istnieją pomiędzy modułem i innymi wdrożonymi modułami są wyświetlane tutaj.<br><br><b>Nazwa</b> relacji jest generowana automatycznie przez system.<br><br><b>Moduł nadrzędny</b> to moduł, do którego należy relacja. Na przykład, wszystkie właściwości relacji, dla których moduł Kontrahenci jest modułem nadrzędnym, są przechowywane w tabelach bazy danych dla modułu Kontrahenci.<br><br><b>Typ</b> to typ relacji występującej pomiędzy modułem nadrzędnym i <b>Modułem powiązanym</b>. <br><br>Kliknij tytuł kolumny, aby sortować według tej kolumny.<br><br>Kliknij wiersz w tabeli relacji, aby zobaczyć właściwości powiązane z relacją.<br><br>Kliknij <b>Dodaj relację</b> w celu utworzenia nowej relacji.<br><br>Relacje można tworzyć pomiędzy dowolnymi istniejącymi modułami.',
        'relationshipHelp'=>'<b>Relacje</b> można tworzyć pomiędzy modułem i innym wdrożonym modułem.<br><br> Relacje są przedstawione wizualnie jako panele podrzędne i tworzą powiązania pól w rekordach modułu.<br><br>Wybierz jeden z następujących <b>Typów</b> relacji dla modułu:<br><br> <b>Jeden-do-jednego</b> — rekordy w obu modułach będą zawierać powiązane pola.<br><br> <b>Jeden-do-wielu</b> — Rekord modułu nadrzędnego będzie zawierał panel podrzędny, a rekord modułu powiązanego powiązane pole.<br><br> <b>Wiele-do-Wielu</b> — W rekordach obu modułów będą wyświetlane panele podrzędne.<br><br> Wybierz <b>Moduł powiązany</b> dla relacji. <br><br> Jeżeli relacja uwzględnia panele podrzędne, wybierz widok paneli podrzędnych dla właściwych modułów.<br><br> Kliknij <b>Zapisz</b>, aby utworzyć relację.',
        'convertLeadHelp' => "W tym obszarze możesz dodawać moduły do ekranu układu konwersji i modyfikować ustawienia istniejących modułów. <br/><br/>
<b>Szeregowanie:</b><br/>
Kontakty, Kontrahenci i Szanse muszą zachować kolejność. Można zmienić kolejność innych modułów poprzez przeciągnięcie ich wierszy w tabeli.<br/><br/>
<b>Zależność:</b><br/>
Jeśli Szanse są uwzględnione, moduł Kontrahenci musi być wymagany lub usunięty z układu konwersji.<br/><br/>
<b>Moduł:</b> Nazwa modułu.<br/><br/>
<b>Wymagany:</b> Wymagane moduły muszą zostać utworzone lub wybrane zanim namiar zostanie przekształcony.<br/><br/>
<b>Kopiuj dane:</b> Zaznaczenie tej opcji powoduje skopiowanie pól namiaru do pól o tej samej nazwie w nowo utworzonym rekordzie.<br/><br/>
<b>Usuń:</b> Usuń ten moduł z układu konwersji.<br/><br/>        ",
        'editDropDownBtn' => 'Edytuj globalną listę rozwijaną',
        'addDropDownBtn' => 'Dodaj nową globalną listę rozwijaną',
    ),
    'fieldsHelp'=>array(
        'default'=>'<b>Pola</b> są w tym module wymienione po nazwie.<br><br>Szablon modułu zawiera wstępnie zdefiniowany zestaw pól.<br><br>Aby stworzyć nowe pole, kliknij <b>Dodaj pole</b>.<br><br>Aby edytować pole, kliknij <b>Nazwę pola</b>.<br/><br/>Po zamieszczeniu modułu nowe pola utworzone w Kreatorze Modułów oraz pola w szablonie są traktowane jako zwykłe pola w module Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>Relacje</b> utworzone pomiędzy modułami są tutaj.<br><br><b>Nazwa</b> relacji jest generowana przez system.<br><br><b>Moduł nadrzędny </b> to moduł, do którego należy relacja. Właściwości relacji są przechowywane w tabelach bazy danych należących do modułu nadrzędnego.<br><br><b>Typ</b> to typ relacji istniejącej pomiędzy Modułem nadrzędnym i <b>Modułem powiązanym</b>. <br><br>Kliknij tytuł kolumny, aby posortować według tej kolumny. <br><br>Klikając wiersz w tabeli relacji, można przeglądać i edytować właściwości relacji.<br><br>Kliknij <b>Dodaj relację</b>, aby utworzyć nową relację.',
        'addrelbtn'=>'kursor myszy na pomocy , aby dodać relację...',
        'addRelationship'=>'<b>Relacje</b> można tworzyć pomiędzy modułem i innym niestandardowym lub wdrożonym modułem.<br><br> Relacje są przedstawione wizualnie jako panele podrzędne i pola powiązania w rekordach modułu.<br><br>Wybierz jeden z następujących <b>Typów</b> relacji dla modułu:<br><br> <b>Jeden-do-jednego</b> — rekordy obu modułów będą zawierać pola powiązania.<br><br> <b>Jeden-do-wielu</b> — rekord modułu nadrzędnego będzie zawierał panel podrzędny, a rekord modułu podrzędnego pole powiązania.<br><br> <b>Wiele-do-Wielu</b> — w rekordach obu modułów wyświetlane będą panele podrzędne.<br><br> Wybierz <b>Moduł powiązany</b> dla relacji. <br><br> Jeżeli relacja obejmuje panele podrzędne, wybierz widok paneli podrzędnych dla właściwych modułów.<br><br> Kliknij <b>Zapisz</b>, aby utworzyć relację.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Etykiety</b> dla pól i inne tytuły w module można zmieniać.<br><br>Edytuj etykiety, klikając pole, wprowadzając nową etykietę i klikając <b>Zapisz</b>.<br><br>Jeśli dla aplikacji są zainstalowane pakiety językowe, możesz wybrać <b>Język</b> dla etykiet.',
        'saveBtn'=>'Kliknij <b>Zapisz</b>, aby zapisać wszystkie zmiany',
        'publishBtn'=>'Kliknij <b>Zapisz i zamieść</b>, aby zapisać wszystkie zmiany i uaktywnić je.',
    ),
    'portalSync'=>array(
        'default' => 'Wprowadź <b>Adres URL Portalu Sugar</b> dla instancji portalu, która ma być zaktualizowana, i kliknij <b>Dalej</b>.<br><br>Następnie wprowadź prawidłową nazwę użytkownika Sugar oraz hasło i kliknij <b>Rozpocznij synchronizację</b>.<br><br>Dostosowania wprowadzone w <b>Układach</b> portalu Sugar wraz z <b>Arkuszem stylów</b>, jeśli został uprzednio załadowany, zostaną przeniesione do w określonej instancji portalu.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Możesz dostosować wygląd portalu Sugar, korzystając z arkusza stylów.<br><br>Wybierz <b>Arkusz stylów</b> do załadowania.<br><br>Arkusz stylów zostanie ustawiony w Portalu Sugar podczas następnej synchronizacji.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Aby rozpocząć pracę z projektem, kliknij <b>Nowy Pakiet</b> w celu utworzenia nowego pakietu na niestandardowe moduły.<br/><br/>Każdy pakiet może zawierać jeden lub kilka modułów.<br/><br/>Na przykład: możesz utworzyć pakiet zawierający jeden moduł niestandardowy, który jest powiązany ze standardowym modułem Kontrahenci. Możesz też utworzyć pakiet zawierający kilka nowych modułów współpracujących ze sobą jako projekt i będących zależnymi od modułów istniejących w aplikacji.',
            'somepackages'=>'<b>Pakiet</b> zawiera utworzone przez użytkownika moduły, które należą do jednego projektu. Pakiet może zawierać jeden lub więcej niestandardowych <b>modułów</b>, które mogą być powiązane pomiędzy sobą lub z innymi modułami aplikacji.<br/><br/>Po utworzeniu pakietu dla projektu można od razu tworzyć moduły do projektu lub powrócić później do Kreatora modułów, aby zakończyć projekt.<br><br>Gdy projekt jest ukończony, można użyć opcji <b>Zamieść</b> dla pakietu, co spowoduje instalację niestandardowych modułów w aplikacji.',
            'afterSave'=>'Nowy pakiet powinien zawierać co najmniej jeden moduł. Można utworzyć jeden lub więcej modułów niestandardowych dla pakietu.<br/><br/>Kliknij <b>Nowy moduł</b>, aby utworzyć niestandardowy moduł dla tego pakietu.<br/><br/> Po utworzeniu wszystkich modułów możesz opublikować lub zamieścić pakiet, co udostępni go dla danej instancji i/lub instancji użytkowników.<br/><br/> Aby za pomocą jednej czynności zamieścić pakiet w danej instancji Sugar, kliknij <b>Zamieść</b>.<br><br>Kliknij <b>Publikuj</b>, aby zapisać pakiet jako plik .zip. Poz zapisaniu pliku .zip w systemie, użyj narzędzia <b>Zarządzanie modułami</b>, aby załadować i zainstalować pakiet w danej instancji Sugar. <br/><br/>Pakiet można dowolnie dystrybuować do innych użytkowników w celu załadowania i instalacji w ich instancjach Sugar.',
            'create'=>'<b>Pakiet</b> zawiera niestandardowe moduły, które należą do jednego projektu. Pakiet może zawierać jeden lub więcej niestandardowych <b>modułów</b>, które mogą być powiązane pomiędzy sobą lub innymi modułami aplikacji.<br/><br/>Po utworzeniu pakietu można od razu tworzyć moduły dla pakietu lub powrócić do Kreatora modułów później w celu zakończenia projektu.',
            ),
    'main'=>array(
        'welcome'=>'<b>Narzędzia programisty</b> służą do tworzenia standardowych i niestandardowych modułów i pól oraz zarządzania nimi. <br/><br/>Aby zarządzać modułami w aplikacji, kliknij <b>Studio</b>. <br/><br/>Aby utworzyć niestandardowe moduły, kliknij <b>Kreator modułów</b>.',
        'studioWelcome'=>'Wszystkie zainstalowane obecnie moduły, łącznie z obiektami standardowymi i załadowanymi przez moduł, można dostosować w Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Gdy utworzony pakiet zawiera już co najmniej jeden moduł, możesz użyć opcji <b>Zamieść</b> , aby zamieścić moduł w pakiecie w ramach instancji Sugar lub użyć opcji <b>Publikuj</b>, aby opublikować pakiet do zainstalowania w tej lub innej instancji Sugar przy użyciu narzędzia <b>Zarządzanie modułami</b>.<br/><br/>Aby zainstalować pakiet bezpośrednio w tej instancji Sugar, kliknij <b>Zamieść</b>.<br><br>Aby utworzyć plik .zip dla pakietu, który można wczytać i zainstalować w bieżącej instancji Sugar i innych instancjach przy użyciu narzędzia <b>Zarządzanie modułami</b>, kliknij <b>Publikuj</b>.<br/><br/> Możesz tworzyć moduły dla tego pakietu etapami i publikować lub zamieszczać w dowolnym momencie. <br/><br/>Po publikacji lub zamieszczeniu pakietu możesz dalej dokonywać zmian we właściwościach pakietu i dalej dostosowywać moduły.  W takim przypadku ponownie dokonaj publikacji lub zamieszczenia, aby zastosować zmiany." ,
        'editView'=> 'Tutaj możesz edytować istniejące pola. W panelu po lewej stronie możesz usuwać dowolne istniejące pola lub dodawać dostępne pola.',
        'create'=>'Podczas wybierania wartości <b>Typ</b> dla modułu, który chcesz utworzyć, należy pamiętać o typach pól które znajdą się w module. <br/><br/>Każdy szablon modułu zawiera zestaw pól właściwy dla konkretnego typu modułu, określony przez jego nazwę.<br/><br/><b>Podstawowy</b> — zawiera podstawowe pola wyświetlane w standardowych modułach. Te pola to np. Nazwa, Przydzielono do, Zespół, Data utworzenia lub Opis.<br/><br/> <b>Firma</b> — zawiera pola typowe dla określenia organizacji, takie jak Nazwa, Branża i Adres do fakturowania.  Użyj tego szablonu do tworzenia modułów podobnych do standardowego modułu Kontrahenci.<br/><br/> <b>Osoba</b> — zawiera pola służące do określenia osób, takie jak Forma grzecznościowa, Stanowisko, Imię i nazwisko, Adres oraz Numer telefonu.  Użyj tego szablonu do tworzenia modułów podobnych do standardowych modułów Kontakty i Namiary.<br/><br/><b>Problem</b> — Zawiera pola specyficzne dla modułów Zgłoszenia lub Błędy, takie jak Numer, Status, Priorytet lub Opis.  Użyj tego szablonu, do tworzenia modułów podobnych do standardowych modułów Sprawy lub Śledzenie błędów.<br/><br/>Uwaga: po utworzeniu modułu możesz edytować etykiety pól szablonu oraz tworzyć niestandardowe pola dodane do układów modułu.',
        'afterSave'=>'Dostosuj moduł do potrzeb, edytując i tworząc pola, ustanawiając relacje pomiędzy modułami i aranżując rozkład pól w poszczególnych układach.<br/><br/>Aby zobaczyć pola wzorcowe w module i aranżować własne, kliknij <b>Zobacz pola</b>.<br/><br/>Aby utworzyć relacje pomiędzy modułami i zarządzać nimi, zarówno znajdującymi się w aplikacji, jak i utworzonymi w tym samym pakiecie, kliknij <b>Pokaż relacje</b>.<br/><br/>Aby edytować układy, kliknij <b>Zobacz układy</b>. Możesz zmieniać widok szczegółowy, widok edycji oraz widok listy dokładnie tak samo, jak edytuje się standardowe moduły w Studio.<br/><br/> Aby utworzyć moduł o takich samych właściwościach, kliknij <b>Duplikuj</b>.  Możesz dalej dostosowywać powstały w wyniku duplikacji moduł.',
        'viewfields'=>'Pola w module można dowolnie edytować.<br/><br/>Nie można usunąć standardowych pól, ale można wyłączyć je w układach na stronach układu. <br/><br/>Można szybko tworzyć nowe pola o podobnych właściwościach do istniejących pól poprzez kliknięcie nazwy pola, a następnie przycisku <b>Duplikuj</b> w formularzu <b>Właściwości</b>.  Wprowadź nowe właściwości i kliknij <b>Zapisz</b>.<br/><br/>Zalecane jest ustawienie wszystkich właściwości dla pól standardowych i niestandardowych przed opublikowaniem lub zainstalowaniem pakietu zawierającego moduł niestandardowy.',
        'viewrelationships'=>'Możesz tworzyć relacje wiele-do-wielu pomiędzy bieżącym modułem, a innymi modułami w pakiecie i/lub pomiędzy bieżącym modułem, a modułami zainstalowanymi na aplikacji.<br><br> Aby utworzyć relację jeden-do-wielu i jeden-do-jednego, kliknij <b>Powiązanie</b> i <b>Utwórz powiązanie</b> pomiędzy polami w module.',
        'viewlayouts'=>'Możesz kontrolować, które z pól są dostępne do gromadzenia danych w <b>Widoku edycji</b>.  Możesz także kontrolować, jakie dane są wyświetlane w <b>Widoku szczegółowym</b>.  Widoki nie muszą zawierać takich samych danych. <br/><br/>Formularz Szybkie tworzenie jest wyświetlany po kliknięciu przycisku <b>Utwórz</b> w paneli podrzędnym modułu. Domyślnie układ formularza <b>Szybkie tworzenie</b> jest taki sam, jak układ  <b>Widoku edycji</b>. Możesz dostosować formularz Szybkie tworzenie tak, aby zawierał mniej pól i/lub inne pola niż układ Widoku edycji. <br><br>Możesz określić również poziom bezpieczeństwa dla układu, korzystając z <b>Zarządzania rolami</b>.<br><br>',
        'existingModule' =>'Po utworzeniu i dostosowaniu modułu możesz tworzyć następne moduły, <b>Publikować</b> lub <b>Zamieścić</b> pakiet.<br><br>Aby utworzyć moduły o podobnych właściwościach, kliknij <b>Duplikuj</b>, aby utworzyć moduł o tych samych właściwościach co bieżący moduł, lub przejdź do poprzedniego ekranu i kliknij <b>Nowy moduł</b>.<br><br> W momencie gotowości do <b>Publikacji</b> lub <b>Zamieszczenia</b> pakietu zawierającego ten moduł, przejdź do poprzedniego poziomu, aby wykonać te czynności. Możesz zamieścić lub opublikować pakiet zawierający co najmniej jeden moduł.',
        'labels'=> 'Etykiety pól standardowych i niestandardowych można modyfikować. Zmiana etykiet pól nie będzie miała wpływu na wprowadzone do nich dane.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Po lewej stronie są wyświetlone trzy kolumny. Kolumna Domyślne zawiera pola wyświetlane w Widoku listy domyślnie, kolumna Dostępne zawiera pola, które użytkownik może wybrać w celu utworzenia własnego widoku listy, natomiast kolumna Ukryte zawiera pola, które administrator może dodać do kolumn Domyślne lub Dostępne, aby użytkownicy mogli z nich korzystać, ale które obecnie są niedostępne.',
        'savebtn'	=> 'Kliknij <b>Zapisz i zamieść</b>, aby zapisać zmiany i uaktywnić je w module.',
        'Hidden' 	=> 'Ukryte pola nie są obecnie dostępne dla użytkowników w widoku listy.',
        'Available' => 'Dostępne pola nie są widoczne domyślnie, ale mogą zostać włączone przez użytkowników.',
        'Default'	=> 'Domyślne pola są widoczne dla użytkowników, którzy nie utworzyli niestandardowych ustawień widoku listy.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Po lewej stronie są wyświetlone dwie kolumny. Kolumna Domyślne zawiera pola wyświetlane w widoku wyszukiwania, a kolumna Ukryte zawiera pola dostępne dla administratora do dodania do widoku.',
        'savebtn'	=> 'Kliknij <b>Zapisz i zamieść</b>, aby zapisać wszystkie zmiany i uaktywnić je.',
        'Hidden' 	=> 'Ukryte pola nie są wyświetlane w widoku wyszukiwania.',
        'Default'	=> 'Domyślne pola są wyświetlane w widoku wyszukiwania.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Po lewej stronie wyświetlane są dwie kolumny. Kolumna po prawej stronie, oznaczona Bieżący układ lub Podgląd układu to miejsce, w którym można zmienić układ modułu. Kolumna po lewej stronie o nazwie Narzędzia zawiera przydatne elementy i narzędzia do wykorzystania podczas edycji układu. <br/><br/>Jeśli obszar układu jest zatytułowany Bieżący układ, oznacza to, że pracujesz na kopii układu aktualnie wykorzystywanej przez moduł do wyświetlania <br/><br/>Jeśli obszar jest zatytułowany Podgląd układu, oznacza to, że pracujesz na kopii utworzonej wcześniej przez kliknięcie przycisku Zapisz i kopia ta mogła zostać już zmieniona w porównaniu z wersją wyświetlaną użytkownikom tego modułu.',
        'saveBtn'	=> 'Kliknięcie tego przycisku powoduje zapisanie układu w celu zachowania zmian. Po powrocie do tego modułu wyświetlony zostanie ten zmieniony układ. Twój układ nie będzie jednak wyświetlany innym użytkownikom modułu do momentu kliknięcia przycisku Zapisz i publikuj.',
        'publishBtn'=> 'Kliknij ten przycisk, aby zamieścić układ. Oznacza to, że ten układ będzie natychmiast widoczny dla wszystkich użytkowników tego modułu.',
        'toolbox'	=> 'Narzędzia zawieraj różne przydatne funkcje do edycji układów, łącznie z obszarem kosza, zestawem dodatkowych elementów oraz zestawem dostępnych pól. Każdy z tych elementów można przeciągnąć i upuścić do układu.',
        'panels'	=> 'Ten obszar przedstawia podgląd tego, jak układ będzie wyglądał dla użytkowników, gdy zmiany zostaną zastosowane.<br/><br/>Możesz zmienić pozycję pól, wierszy i paneli przez przeciągnięcie ich w pożądane miejsce. Usuwanie elementów następuje przez przeciągnięcie i upuszczenie ich do obszaru kosza w narzędziach. Dodawanie elementów odbywa się poprzez przeciągnięcie ich z narzędzi i upuszczenie w określonym miejscu w układzie.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Po lewej stronie wyświetlane są dwie kolumny. Kolumna po prawej stronie, oznaczona Bieżący układ lub Podgląd układu to miejsce, w którym można zmienić układ modułu. Kolumna po lewej stronie o nazwie Narzędzia zawiera przydatne elementy i narzędzia do wykorzystania podczas edycji układu. <br/><br/>Jeśli obszar układu jest zatytułowany Bieżący układ, oznacza to, że pracujesz na kopii układu aktualnie wykorzystywanej przez moduł do wyświetlania <br/><br/>Jeśli obszar jest zatytułowany Podgląd układu, oznacza to, że pracujesz na kopii utworzonej wcześniej przez kliknięcie przycisku Zapisz i kopia ta mogła zostać już zmieniona w porównaniu z wersją wyświetlaną użytkownikom tego modułu.',
        'dropdownaddbtn'=> 'Kliknięcie tego przycisku powoduje dodanie nowej pozycji do listy rozwijanej.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Zmiany dokonane w Studio w tej instancji można zawrzeć w pakiecie i zainstalować na innej instancji. <br><br>Wprowadź <b>Nazwę pakietu</b>.  W pakiecie możesz określić informacje o <b>Autorze</b> i <b>Opis</b>.<br><br>Wybierz moduły zawierające dostosowania do wyeksportowania.<br><br>Kliknij <b>Eksport</b>, aby utworzyć plik .zip pakietu zawierającego dostosowania. Plik .zip można wczytać w innej instancji za pomocą <b>Zarządzania modułami</b>.',
        'exportCustomBtn'=>'Kliknij <b>Eksport</b>, aby utworzyć plik .zip pakietu zawierającego dostosowania, które chcesz wyeksportować.',
        'name'=>'<b>Nazwa</b> pakietu zostanie wyświetlona w Zarządzaniu modułami po wczytaniu pakietu do instalacji w Studio.',
        'author'=>'<b>Autor</b> to nazwa podmiotu, który utworzył pakiet. Może to być osoba lub firma.<br><br>Autor zostanie wyświetlony w Zarządzaniu modułami po wczytaniu pakietu do instalacji w Studio.
',
        'description'=>'<b>Opis</b> pakietu zostanie wyświetlony w Zarządzaniu modułami po wczytaniu pakietu do instalacji w Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Witamy w obszarze <b>Narzędzia Programisty</b1>. <br/><br/>Narzędzia w tym obszarze służą do tworzenia standardowych i niestandardowych modułów i pól oraz zarządzania nimi.',
        'studioBtn'	=> 'Użyj <b>Studio</b>, aby dostosować zainstalowane moduły poprzez zmianę układu pól, wybierając, które pola są dostępne i tworząc niestandardowe pola danych.',
        'mbBtn'		=> 'Użyj <b>Kreatora Modułów</b>, aby tworzyć moduły.',
        'appBtn' 	=> 'Tryb aplikacji jest stosowany w przypadku dostosowywania różnych właściwości programu, takich jak np. ile raportów TPS jest wyświetlanych na stronie głównej',
        'backBtn'	=> 'Powrót do poprzedniego kroku.',
        'studioHelp'=> 'Użyj <b>Studio</b>, aby dostosować zainstalowane moduły.',
        'moduleBtn'	=> 'Kliknij, aby edytować ten moduł.',
        'moduleHelp'=> 'Wybierz element modułu, który chcesz edytować',
        'fieldsBtn'	=> 'Wybierz informacje przechowywane w module, określając <b>Pola</b> w module.<br/><br/> W tym obszarze możesz także edytować i tworzyć niestandardowe pola.',
        'layoutsBtn'=> 'Dostosuj <b>Układy</b> widoków wyszukiwania, edycji, szczegółów oraz listy.',
        'subpanelBtn'=> 'Edytuj, które informacje są wyświetlane w panelach podrzędnych modułów.',
        'layoutsHelp'=> 'Wybierz <b>Układy do edycji</b>.<br/<br/>Aby zmienić układ zawierający pola danych do wprowadzania danych, kliknij <b>Widok edycji</b>. <br/><br/>Aby zmienić układ, w którym wyświetlane są dane wprowadzone w polach w widoku edycji, kliknij <b>Widok szczegółowy</b>.<br/><br/>Aby zmienić kolumny wyświetlane na domyślnej liście, kliknij <b>Widok listy</b>.<br/><br/>Aby zmienić układ formularzy wyszukiwania podstawowego i zaawansowanego, kliknij <b>Wyszukaj</b>.',
        'subpanelHelp'=> 'Wybierz <b>Panel podrzędny</b> do edycji.',
        'searchHelp' => 'Wybierz układ <b>Wyszukiwania</b> do edycji.',
        'labelsBtn'	=> 'Edytuj <b>Etykiety</b> do wyświetlenia dla wartości w tym module.',
        'newPackage'=>'Kliknij <b>Nowy Pakiet</b>, aby utworzyć nowy pakiet.',
        'mbHelp'    => '<b>Witamy w Kreatorze Modułów.</b><br/><br/><b>Kreator Modułów</b> służy do tworzenia pakietów zawierających niestandardowe moduły na podstawie standardowych lub niestandardowych obiektów. <br/><br/>Aby rozpocząć, kliknij <b>Nowy Pakiet</b> w celu utworzenia nowego pakietu lub wybierz istniejący pakiet do edycji.<br/><br/> <b>Pakiet</b> może być zbiorem niestandardowych modułów, które są częścią jednego projektu. Pakiet może więc zawierać więcej niż jeden moduł własny, który może być powiązany z dowolnym innym modułem aplikacji. <br/><br/>Przykłady: może wystąpić potrzeba utworzenia pakietu zawierającego jeden moduł niestandardowy powiązany ze standardowym modułem Kontrahenci. Może również wystąpić konieczność utworzenia kilku nowych modułów współpracujących ze sobą jako projekt i będących wzajemnie zależnymi od siebie i modułów w aplikacji.',
        'exportBtn' => 'Kliknij <b>Eksport dostosowań</b>, aby utworzyć pakiet zawierający własne modyfikacje utworzone w Studio dla określonego modułu.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Edytor list rozwijanych',

//ASSISTANT
'LBL_AS_SHOW' => 'Pokaż Asystenta w przyszłości.',
'LBL_AS_IGNORE' => 'Nie pokazuj Asystenta w przyszłości.',
'LBL_AS_SAYS' => 'Asystent sugeruje:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Kreator Modułów',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Edytor list rozwijanych',
'LBL_EDIT_DROPDOWN'=>'Edytuj listę rozwijaną',
'LBL_DEVELOPER_TOOLS' => 'Narzędzia programisty',
'LBL_SUGARPORTAL' => 'Edytor portalu Sugar',
'LBL_SYNCPORTAL' => 'Synchronizuj portal',
'LBL_PACKAGE_LIST' => 'Lista pakietów',
'LBL_HOME' => 'Strona główna',
'LBL_NONE'=>'-Brak-',
'LBL_DEPLOYE_COMPLETE'=>'Zamieszczanie ukończone',
'LBL_DEPLOY_FAILED'   =>'Podczas zamieszczania pakietu wystąpił błąd. Twój pakiet mógł zostać zainstalowany niepoprawnie',
'LBL_ADD_FIELDS'=>'Dodaj pola niestandardowe',
'LBL_AVAILABLE_SUBPANELS'=>'Dostępne panele podrzędne',
'LBL_ADVANCED'=>'Zaawansowane',
'LBL_ADVANCED_SEARCH'=>'Wyszukiwanie zaawansowane',
'LBL_BASIC'=>'Podstawowe',
'LBL_BASIC_SEARCH'=>'Wyszukiwanie podstawowe',
'LBL_CURRENT_LAYOUT'=>'Układ',
'LBL_CURRENCY' => 'Waluta',
'LBL_CUSTOM' => 'Niestandardowy',
'LBL_DASHLET'=>'Dashlet Sugar',
'LBL_DASHLETLISTVIEW'=>'Widok listy dashletów Sugar',
'LBL_DASHLETSEARCH'=>'Wyszukiwanie dashletu Sugar',
'LBL_POPUP'=>'Widok wyskakującego okienka',
'LBL_POPUPLIST'=>'Widok listy wyskakujących okienek',
'LBL_POPUPLISTVIEW'=>'Widok listy wyskakujących okienek',
'LBL_POPUPSEARCH'=>'Wyszukiwanie wyskakujących okienek',
'LBL_DASHLETSEARCHVIEW'=>'Wyszukiwanie dashletu Sugar',
'LBL_DISPLAY_HTML'=>'Wyświetl kod HTML',
'LBL_DETAILVIEW'=>'Widok szczegółowy',
'LBL_DROP_HERE' => '[Upuść tutaj]',
'LBL_EDIT'=>'Edytuj',
'LBL_EDIT_LAYOUT'=>'Edytuj układ',
'LBL_EDIT_ROWS'=>'Edytuj wiersze',
'LBL_EDIT_COLUMNS'=>'Edytuj kolumny',
'LBL_EDIT_LABELS'=>'Edytuj etykiety',
'LBL_EDIT_PORTAL'=>'Edytuj portal dla',
'LBL_EDIT_FIELDS'=>'Edytuj pola',
'LBL_EDITVIEW'=>'Widok edycji',
'LBL_FILTER_SEARCH' => "Wyszukiwanie",
'LBL_FILLER'=>'(filtr)',
'LBL_FIELDS'=>'Pola',
'LBL_FAILED_TO_SAVE' => 'Zapisywanie nie powiodło się',
'LBL_FAILED_PUBLISHED' => 'Publikowanie nie powiodło się',
'LBL_HOMEPAGE_PREFIX' => 'Moje',
'LBL_LAYOUT_PREVIEW'=>'Podgląd układu',
'LBL_LAYOUTS'=>'Układy',
'LBL_LISTVIEW'=>'Widok listy',
'LBL_RECORDVIEW'=>'Widok rekordu',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Nowy pakiet',
'LBL_NEW_PANEL'=>'Nowy panel',
'LBL_NEW_ROW'=>'Nowy wiersz',
'LBL_PACKAGE_DELETED'=>'Usunięto pakiet',
'LBL_PUBLISHING' => 'Publikowanie...',
'LBL_PUBLISHED' => 'Opublikowano',
'LBL_SELECT_FILE'=> 'Wybierz plik',
'LBL_SAVE_LAYOUT'=> 'Zapisz układ',
'LBL_SELECT_A_SUBPANEL' => 'Wybierz panel podrzędny',
'LBL_SELECT_SUBPANEL' => 'Wybierz panel podrzędny',
'LBL_SUBPANELS' => 'Panele podrzędne',
'LBL_SUBPANEL' => 'Panel podrzędny',
'LBL_SUBPANEL_TITLE' => 'Tytuł:',
'LBL_SEARCH_FORMS' => 'Wyszukiwanie',
'LBL_STAGING_AREA' => 'Obszar roboczy (przeciągnij i upuść elementy tutaj)',
'LBL_SUGAR_FIELDS_STAGE' => 'Pola Sugar (kliknij element, aby dodać do obszaru roboczego)',
'LBL_SUGAR_BIN_STAGE' => 'Kosz Sugar (kliknij element, aby dodać do obszaru roboczego)',
'LBL_TOOLBOX' => 'Narzędzia',
'LBL_VIEW_SUGAR_FIELDS' => 'Wyświetl pola Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Wyświetl kosz Sugar',
'LBL_QUICKCREATE' => 'Szybkie tworzenie',
'LBL_EDIT_DROPDOWNS' => 'Edytuj globalną listę rozwijaną',
'LBL_ADD_DROPDOWN' => 'Dodaj nową globalną listę rozwijaną',
'LBL_BLANK' => '-brak-',
'LBL_TAB_ORDER' => 'Kolejność zakładek',
'LBL_TAB_PANELS' => 'Włącz zakładki',
'LBL_TAB_PANELS_HELP' => 'Gdy zakładki są włączone, użyj pola rozwijanego typu<br />dla każdej części, aby określić sposób wyświetlania (zakładka czy panel)',
'LBL_TABDEF_TYPE' => 'Wyświetl typ',
'LBL_TABDEF_TYPE_HELP' => 'Wybierz sposób, w jaki powinna być wyświetlana ta część. Ta opcja zadziała jeśli zakładki są w tym widoku włączone.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Zakładka',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Wybierz Panel, aby ten panel był wyświetlany w widoku układu. Wybierz Zakładka, aby ten panel był wyświetlany w osobnej zakładce w ramach widoku. Jeśli dla panelu określona jest zakładka, kolejne panele zaznaczone do wyświetlania jako Panel zostaną wyświetlone w zakładce.<br/>Nowa zakładka zostanie utworzona dla następnego panelu, dla którego wybrano zakładkę. Jeśli zakładka zostanie wybrana dla panelu poniżej pierwszego panelu, to pierwszy panel będzie zakładką.',
'LBL_TABDEF_COLLAPSE' => 'Zwiń',
'LBL_TABDEF_COLLAPSE_HELP' => 'Wybierz, aby ten panel był domyślnie zwinięty.',
'LBL_DROPDOWN_TITLE_NAME' => 'Nazwa',
'LBL_DROPDOWN_LANGUAGE' => 'Język',
'LBL_DROPDOWN_ITEMS' => 'Lista elementów',
'LBL_DROPDOWN_ITEM_NAME' => 'Nazwa elementu',
'LBL_DROPDOWN_ITEM_LABEL' => 'Wyświetlana etykieta',
'LBL_SYNC_TO_DETAILVIEW' => 'Synchronizuj z widokiem szczegółowym',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Wybierz tę opcję, aby zsynchronizować widok edycji z odpowiadającym mu widokiem szczegółowym. Pola i ich położenie z widoku edycji <br>zostaną automatycznie zsynchronizowane z widokiem szczegółowym po kliknięciu Zapisz lub Zapisz i Zamieść w widoku edycji.<br>Zmiany w widoku szczegółowym nie będą możliwe.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Ten widok szczegółowy zostanie zsynchronizowany z odpowiadającym mu widokiem edycji.<br> Pola i ich położenie z widoku szczegółowego odpowiadają polom i ich położeniu z powiązanego widoku edycji.<br> Zmiany w widoku szczegółowym nie mogą zostać zapisane lub zmieszczone w ramach tej strony. Dokonaj zmian w widoku edycji lub wyłącz synchronizację.',
'LBL_COPY_FROM' => 'Kopiuj z',
'LBL_COPY_FROM_EDITVIEW' => 'Kopiuj z widoku edycji',
'LBL_DROPDOWN_BLANK_WARNING' => 'Wartości są wymagane zarówno dla nazwy elementu, jak i wyświetlanej etykiety. W celu dodania pustej wartości kliknij Dodaj bez podawania nazwy elementu ani wyświetlanej etykiety.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Klucz znajduje się już na liście',
'LBL_DROPDOWN_LIST_EMPTY' => 'Lista musi zawierać co najmniej jeden włączony element',
'LBL_NO_SAVE_ACTION' => 'Nie można zapisać czynności dla tego widoku.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: źle sformułowany dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Wskazuje pole mieszane. Pole mieszane to zbiór pojedynczych pól. Na przykład: pole Adres to pole mieszane, które zawiera pola Ulica, Kod pocztowy, Miasto, Województwo oraz Kraj.<br><br>Kliknij dwukrotnie pole mieszane, aby zobaczyć jakie pola zawiera.',
'LBL_COMBO_FIELD_CONTAINS' => 'zawiera:',

'LBL_WIRELESSLAYOUTS'=>'Mobilne układy',
'LBL_WIRELESSEDITVIEW'=>'Mobilny widok edycji',
'LBL_WIRELESSDETAILVIEW'=>'Mobilny widok szczegółowy',
'LBL_WIRELESSLISTVIEW'=>'Mobilny widok listy',
'LBL_WIRELESSSEARCH'=>'Mobilne wyszukiwanie',

'LBL_BTN_ADD_DEPENDENCY'=>'Dodaj zależność',
'LBL_BTN_EDIT_FORMULA'=>'Edytuj formułę',
'LBL_DEPENDENCY' => 'Zależność',
'LBL_DEPENDANT' => 'Zależny',
'LBL_CALCULATED' => 'Uzupełniane automatycznie',
'LBL_READ_ONLY' => 'Tylko do odczytu',
'LBL_FORMULA_BUILDER' => 'Kreator formuły',
'LBL_FORMULA_INVALID' => 'Nieprawidłowa formuła',
'LBL_FORMULA_TYPE' => 'Formuła musi być typu',
'LBL_NO_FIELDS' => 'Nie znaleziono pól',
'LBL_NO_FUNCS' => 'Nie znaleziono funkcji',
'LBL_SEARCH_FUNCS' => 'Wyszukiwanie funkcji...',
'LBL_SEARCH_FIELDS' => 'Wyszukiwanie pól...',
'LBL_FORMULA' => 'Formuła',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Zależny',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Przeciągnij opcje z listy dostępnych opcji po lewej stronie dla zależnej listy rozwijanej do list po prawej stronie, aby udostępnić te opcje w przypadku gdy została wybrana opcja nadrzędna. Jeżeli opcja nadrzędna nie zwiera żadnych elementów, zależna lista rozwijana nie będzie wyświetlana.',
'LBL_AVAILABLE_OPTIONS' => 'Dostępne opcje',
'LBL_PARENT_DROPDOWN' => 'Nadrzędna lista rozwijana',
'LBL_VISIBILITY_EDITOR' => 'Edytor widoczności',
'LBL_ROLLUP' => 'Rozwinięcie',
'LBL_RELATED_FIELD' => 'Pole powiązane',
'LBL_CONFIG_PORTAL_URL'=>'URL niestandardowego obrazu loga. Zalecane wymiary logo to 163 x 18 pikseli.',
'LBL_PORTAL_ROLE_DESC' => 'Nie usuwaj tej roli. Rola klienta Portal samoobsługowy jest rolą generowaną przez system podczas procesu aktywacji portalu Sugar. Użyj kontroli dostępu w tej roli, aby włączyć i/lub wyłączyć Błędy, Zgłoszenia lub Bazę wiedzy w portalu Sugar. Nie modyfikuj innych kontroli dostępu dla tej roli, aby uniknąć nieznanych i nieprzewidzianych zachowań systemu. W razie przypadkowego usunięcia tej roli, odtwórz ją poprzez wyłącznie i włącznie portalu Sugar.',

//RELATIONSHIPS
'LBL_MODULE' => 'Moduł',
'LBL_LHS_MODULE'=>'Moduł nadrzędny',
'LBL_CUSTOM_RELATIONSHIPS' => '* relacje utworzone w Studio lub Kreatorze Modułów',
'LBL_RELATIONSHIPS'=>'Relacje',
'LBL_RELATIONSHIP_EDIT' => 'Edytuj relacje',
'LBL_REL_NAME' => 'Nazwa',
'LBL_REL_LABEL' => 'Etykieta',
'LBL_REL_TYPE' => 'Typ',
'LBL_RHS_MODULE'=>'Moduł powiązany',
'LBL_NO_RELS' => 'Brak relacji',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Warunek opcjonalny' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Kolumna',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Wartość',
'LBL_SUBPANEL_FROM'=>'Panel podrzędny z',
'LBL_RELATIONSHIP_ONLY'=>'Nie zostaną utworzone żadne widoczne elementy dla tej relacji, ponieważ istnieje poprzednia widoczna relacja dla tych dwóch modułów.',
'LBL_ONETOONE' => 'Jeden do jednego',
'LBL_ONETOMANY' => 'Jeden do wielu',
'LBL_MANYTOONE' => 'Wiele do jednego',
'LBL_MANYTOMANY' => 'Wiele do wielu',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Wybierz funkcję lub komponent.',
'LBL_QUESTION_MODULE1' => 'Wybierz moduł.',
'LBL_QUESTION_EDIT' => 'Wybierz moduł do edycji.',
'LBL_QUESTION_LAYOUT' => 'Wybierz układ do edycji.',
'LBL_QUESTION_SUBPANEL' => 'Wybierz panel podrzędny do edycji.',
'LBL_QUESTION_SEARCH' => 'Wybierz układ wyszukiwania do edycji.',
'LBL_QUESTION_MODULE' => 'Wybierz element modułu do edycji.',
'LBL_QUESTION_PACKAGE' => 'Wybierz pakiet do edycji lub utwórz nowy.',
'LBL_QUESTION_EDITOR' => 'Wybierz narzędzie.',
'LBL_QUESTION_DROPDOWN' => 'Wybierz listę rozwijaną, aby edytować lub utworzyć nową listę.',
'LBL_QUESTION_DASHLET' => 'Wybierz widok dashletu do edycji.',
'LBL_QUESTION_POPUP' => 'Wybierz układ wyskakującego okienka do edycji.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Powiąż z',
'LBL_NAME'=>'Nazwa',
'LBL_LABELS'=>'Etykiety',
'LBL_MASS_UPDATE'=>'Masowa aktualizacja',
'LBL_AUDITED'=>'Śledzenie zmian',
'LBL_CUSTOM_MODULE'=>'Moduł',
'LBL_DEFAULT_VALUE'=>'Wartość domyślna',
'LBL_REQUIRED'=>'Wymagane',
'LBL_DATA_TYPE'=>'Typ',
'LBL_HCUSTOM'=>'NIESTANDARDOWY',
'LBL_HDEFAULT'=>'DOMYŚLNE',
'LBL_LANGUAGE'=>'Język:',
'LBL_CUSTOM_FIELDS' => '* pole utworzone w Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Edytuj etykiety',
'LBL_SECTION_PACKAGES' => 'Pakiety',
'LBL_SECTION_PACKAGE' => 'Pakiet',
'LBL_SECTION_MODULES' => 'Moduły',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Listy rozwijane',
'LBL_SECTION_PROPERTIES' => 'Właściwości',
'LBL_SECTION_DROPDOWNED' => 'Edytuj listę rozwijaną',
'LBL_SECTION_HELP' => 'Pomoc',
'LBL_SECTION_ACTION' => 'Akcja',
'LBL_SECTION_MAIN' => 'Główny',
'LBL_SECTION_EDPANELLABEL' => 'Edytuj etykiety paneli',
'LBL_SECTION_FIELDEDITOR' => 'Edytor pól',
'LBL_SECTION_DEPLOY' => 'Zamieść',
'LBL_SECTION_MODULE' => 'Moduł',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Edytuj widoczność',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Domyślne',
'LBL_HIDDEN'=>'Ukryte',
'LBL_AVAILABLE'=>'Dostępne',
'LBL_LISTVIEW_DESCRIPTION'=>'Poniżej wyświetlane są trzy kolumny; Kolumna <b>Domyślne</b> zawiera pola, które są domyślnie wyświetlane w widoku listy. Kolumna <b>Dodatkowe</b> zawiera pola, które użytkownik może wybrać do tworzenia własnego widoku. W kolumnie <b>Dostępne</b> wyświetlane są pola dostępne dla użytkownika jako administratora i które można dodać do kolumn Domyślne i Dodatkowe do użycia przez użytkowników.',
'LBL_LISTVIEW_EDIT'=>'Edytor widoku listy',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Podgląd',
'LBL_MB_RESTORE'=>'Przywróć',
'LBL_MB_DELETE'=>'Usuń',
'LBL_MB_COMPARE'=>'Porównaj',
'LBL_MB_DEFAULT_LAYOUT'=>'Układ domyślny',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Dodaj',
'LBL_BTN_SAVE'=>'Zapisz',
'LBL_BTN_SAVE_CHANGES'=>'Zapisz zmiany',
'LBL_BTN_DONT_SAVE'=>'Porzuć zmiany',
'LBL_BTN_CANCEL'=>'Anuluj',
'LBL_BTN_CLOSE'=>'Zamknij',
'LBL_BTN_SAVEPUBLISH'=>'Zapisz i publikuj',
'LBL_BTN_NEXT'=>'Dalej',
'LBL_BTN_BACK'=>'Wstecz',
'LBL_BTN_CLONE'=>'Duplikuj',
'LBL_BTN_COPY' => 'Kopiuj',
'LBL_BTN_COPY_FROM' => 'Kopiuj z...',
'LBL_BTN_ADDCOLS'=>'Dodaj kolumny',
'LBL_BTN_ADDROWS'=>'Dodaj wiersze',
'LBL_BTN_ADDFIELD'=>'Dodaj pole',
'LBL_BTN_ADDDROPDOWN'=>'Dodaj listę rozwijaną',
'LBL_BTN_SORT_ASCENDING'=>'Sortuj rosnąco',
'LBL_BTN_SORT_DESCENDING'=>'Sortuj malejąco',
'LBL_BTN_EDLABELS'=>'Edytuj etykiety',
'LBL_BTN_UNDO'=>'Cofnij',
'LBL_BTN_REDO'=>'Ponów',
'LBL_BTN_ADDCUSTOMFIELD'=>'Dodaj pole niestandardowe',
'LBL_BTN_EXPORT'=>'Eksportuj dostosowania',
'LBL_BTN_DUPLICATE'=>'Duplikuj',
'LBL_BTN_PUBLISH'=>'Publikuj',
'LBL_BTN_DEPLOY'=>'Zamieść',
'LBL_BTN_EXP'=>'Eksport',
'LBL_BTN_DELETE'=>'Usuń',
'LBL_BTN_VIEW_LAYOUTS'=>'Wyświetl układy',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Wyświetl układy mobilne',
'LBL_BTN_VIEW_FIELDS'=>'Wyświetl pola',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Wyświetl relacje',
'LBL_BTN_ADD_RELATIONSHIP'=>'Dodaj relacje',
'LBL_BTN_RENAME_MODULE' => 'Zmień nazwę modułu',
'LBL_BTN_INSERT'=>'Wstaw',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Błąd: pole już istnieje',
'ERROR_INVALID_KEY_VALUE'=> "Błąd: Nieprawidłowa wartość klucza: [']",
'ERROR_NO_HISTORY' => 'Nie odnaleziono plików historii',
'ERROR_MINIMUM_FIELDS' => 'Układ musi zawierać co najmniej jedno pole',
'ERROR_GENERIC_TITLE' => 'Wystąpił błąd',
'ERROR_REQUIRED_FIELDS' => 'Czy na pewno chcesz kontynuować? W układzie brakuje wymaganych pól:  ',
'ERROR_ARE_YOU_SURE' => 'Czy na pewno chcesz kontynuować?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Następujące pola zawierają obliczone wartości, które nie będą ponownie obliczone w czasie rzeczywistym w widoku edycji SugarCRM Mobile:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Następujące pola zawierają obliczone wartości, które nie będą ponownie obliczane w czasie rzeczywistym w widoku edycji Portalu SugarCRM:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Następujące moduły są wyłączone:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Jeśli chcesz je włączyć w portalu, możesz to zrobić <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">tutaj</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Konfiguracja portalu',
    'LBL_PORTAL_THEME' => 'Motyw portalu',
    'LBL_PORTAL_ENABLE' => 'Włącz',
    'LBL_PORTAL_SITE_URL' => 'Strona Twojego portalu jest dostępna pod adresem:',
    'LBL_PORTAL_APP_NAME' => 'Nazwa aplikacji',
    'LBL_PORTAL_LOGO_URL' => 'URL logo',
    'LBL_PORTAL_LIST_NUMBER' => 'Liczba rekordów do wyświetlania na liście',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Liczba pól do wyświetlania w widoku szczegółowym',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Liczba wyników do wyświetlania w wyszukiwaniu globalnym',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Ustawienia domyślne przypisane dla nowych rejestracji w portalu',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Układy Portalu',
'LBL_SYNCP_WELCOME'=>'Wprowadź URL instancji portalu, którą chcesz zaktualizować.',
'LBL_SP_UPLOADSTYLE'=>'Wybierz arkusz stylów do załadowania z komputera.<br> Arkusz stylów zostanie wprowadzony do Portalu Sugar przy następnej synchronizacji.',
'LBL_SP_UPLOADED'=> 'Załadowano',
'ERROR_SP_UPLOADED'=>'Upewnij się, że dodawany jest arkusz stylów css.',
'LBL_SP_PREVIEW'=>'Oto podgląd jak będzie wyglądał Portal Sugar po wprowadzeniu arkuszu stylów.',
'LBL_PORTALSITE'=>'Adres URL Portalu Sugar:',
'LBL_PORTAL_GO'=>'Idź',
'LBL_UP_STYLE_SHEET'=>'Załaduj arkusz stylów',
'LBL_QUESTION_SUGAR_PORTAL' => 'Wybierz układ Portalu Sugar do edycji.',
'LBL_QUESTION_PORTAL' => 'Wybierz układ portalu do edycji.',
'LBL_SUGAR_PORTAL'=>'Edytor Portalu Sugar',
'LBL_USER_SELECT' => '-- Wybierz --',

//PORTAL PREVIEW
'LBL_CASES'=>'Zgłoszenia',
'LBL_NEWSLETTERS'=>'Newslettery',
'LBL_BUG_TRACKER'=>'Śledzenie błędów',
'LBL_MY_ACCOUNT'=>'Moje konto',
'LBL_LOGOUT'=>'Wyloguj',
'LBL_CREATE_NEW'=>'Utwórz nowy',
'LBL_LOW'=>'Niski',
'LBL_MEDIUM'=>'Średni',
'LBL_HIGH'=>'Wysoki',
'LBL_NUMBER'=>'Numer:',
'LBL_PRIORITY'=>'Priorytet:',
'LBL_SUBJECT'=>'Temat',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Nazwa pakietu:',
'LBL_MODULE_NAME'=>'Nazwa modułu:',
'LBL_MODULE_NAME_SINGULAR' => 'Nazwa modułu (l. pojedyncza):',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Opis:',
'LBL_KEY'=>'Klucz:',
'LBL_ADD_README'=>' Plik readme',
'LBL_MODULES'=>'Moduły:',
'LBL_LAST_MODIFIED'=>'Ostatnia modyfikacja:',
'LBL_NEW_MODULE'=>'Nowy moduł',
'LBL_LABEL'=>'Etykieta (l. mnoga)',
'LBL_LABEL_TITLE'=>'Etykieta',
'LBL_SINGULAR_LABEL' => 'Etykieta (l. pojedyncza)',
'LBL_WIDTH'=>'Szerokość',
'LBL_PACKAGE'=>'Pakiet:',
'LBL_TYPE'=>'Typ:',
'LBL_TEAM_SECURITY'=>'Zabezpieczenia zespołu',
'LBL_ASSIGNABLE'=>'Możliwy do przydzielenia',
'LBL_PERSON'=>'Osoba',
'LBL_COMPANY'=>'Firma',
'LBL_ISSUE'=>'Zgłoszenie',
'LBL_SALE'=>'Sprzedaż',
'LBL_FILE'=>'Plik',
'LBL_NAV_TAB'=>'Zakładka nawigacyjna',
'LBL_CREATE'=>'Utwórz',
'LBL_LIST'=>'Lista',
'LBL_VIEW'=>'Widok',
'LBL_LIST_VIEW'=>'Widok listy',
'LBL_HISTORY'=>'Wyświetl historię',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Przywróć domyślny układ',
'LBL_ACTIVITIES'=>'Panel aktywności',
'LBL_SEARCH'=>'Wyszukiwanie',
'LBL_NEW'=>'Nowy',
'LBL_TYPE_BASIC'=>'podstawowy',
'LBL_TYPE_COMPANY'=>'firma',
'LBL_TYPE_PERSON'=>'osoba',
'LBL_TYPE_ISSUE'=>'zgłoszenie',
'LBL_TYPE_SALE'=>'sprzedaż',
'LBL_TYPE_FILE'=>'plik',
'LBL_RSUB'=>'To jest panel podrzędny, który będzie wyświetlany w Twoim module',
'LBL_MSUB'=>'To jest panel podrzędny, który jest powiązany z Twoim modułem',
'LBL_MB_IMPORTABLE'=>'Zezwalaj na importowanie',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'widoczne',
'LBL_VE_HIDDEN'=>'ukryte',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] został usunięty',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Eksportuj dostosowania',
'LBL_EC_NAME'=>'Nazwa pakietu:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Opis:',
'LBL_EC_KEY'=>'Klucz:',
'LBL_EC_CHECKERROR'=>'Wybierz moduł.',
'LBL_EC_CUSTOMFIELD'=>'dostosowane pola',
'LBL_EC_CUSTOMLAYOUT'=>'dostosowane układy',
'LBL_EC_CUSTOMDROPDOWN' => 'dostosowane listy rozwijane',
'LBL_EC_NOCUSTOM'=>'Żaden moduł nie został dostosowany.',
'LBL_EC_EXPORTBTN'=>'Eksport',
'LBL_MODULE_DEPLOYED' => 'Moduł został zamieszczony.',
'LBL_UNDEFINED' => 'niezdefiniowane',
'LBL_EC_CUSTOMLABEL'=>'dostosowane etykiety',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Nie można przywrócić danych',
'LBL_AJAX_TIME_DEPENDENT' => 'Operacja, której wykonanie zajmuje czas, właśnie trwa. Poczekaj i spróbuj za kilka sekund.',
'LBL_AJAX_LOADING' => 'Ładowanie...',
'LBL_AJAX_DELETING' => 'Usuwanie...',
'LBL_AJAX_BUILDPROGRESS' => 'Budowanie...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Zamieszczanie...',
'LBL_AJAX_FIELD_EXISTS' =>'Istnieje już pole o podanej nazwie. Wprowadź nową nazwę pola.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Czy na pewno chcesz usunąć ten pakiet? Spowoduje to trwałe usunięcie wszystkich plików skojarzonych z tym pakietem.',
'LBL_JS_REMOVE_MODULE' => 'Czy na pewno chcesz usunąć ten moduł? Ta operacja spowoduje trwałe usunięcie plików powiązanych z tym modułem.',
'LBL_JS_DEPLOY_PACKAGE' => 'Wszystkie dostosowania wykonane w Studio zostaną nadpisane, gdy ten moduł zostanie ponownie zamieszczony. Czy na pewno chcesz kontynuować?',

'LBL_DEPLOY_IN_PROGRESS' => 'Zamieszczanie pakietu',
'LBL_JS_VALIDATE_NAME'=>'Nazwa — musi składać się ze znaków alfanumerycznych i podkreśleń, bez spacji i znaków specjalnych oraz rozpoczynać się od litery.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Klucz pakietu już istnieje',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Nazwa pakietu już istnieje',
'LBL_JS_PACKAGE_NAME'=>'Nazwa pakietu — musi składać się ze znaków alfanumerycznych i podkreśleń, bez spacji i znaków specjalnych oraz rozpoczynać się od litery.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Klucz — musi być alfanumeryczny i rozpoczynać się od litery.',
'LBL_JS_VALIDATE_KEY'=>'Klucz — musi składać się ze znaków alfanumerycznych, bez spacji i rozpoczynać się od litery.',
'LBL_JS_VALIDATE_LABEL'=>'Wprowadź etykietę, która będzie użyta jako nazwa tego modułu',
'LBL_JS_VALIDATE_TYPE'=>'Wybierz z listy rozwijalnej typ modułu, który chcesz zbudować',
'LBL_JS_VALIDATE_REL_NAME'=>'Nazwa — musi składać się ze znaków alfanumerycznych, bez spacji',
'LBL_JS_VALIDATE_REL_LABEL'=>'Etykieta — dodaj etykietę, która będzie wyświetlana ponad panelem podrzędnym',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Czy na pewno chcesz usunąć ten wymagany element listy rozwijanej? Usunięcie go może wpłynąć na funkcjonalność aplikacji.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Czy na pewno chcesz usunąć ten element listy rozwijanej? Usunięcie etapów o statusie Zakończone sukcesem lub Zakończone porażką spowoduje nieprawidłowe działanie modułu Prognozy',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Czy na pewno chcesz usunąć status sprzedaży Nowy? Usunięcie tego statusu spowoduje niepoprawne działanie workflow Pozycja pojedyncza przychodu w module Szanse.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Czy na pewno chcesz usunąć status sprzedaży W toku? Usunięcie tego statusu spowoduje niepoprawne działanie workflow Pozycja pojedyncza przychodu w module Szanse.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Czy na pewno chcesz usunąć etap Zakończone sukcesem? Usunięcie tego etapu spowoduje nieprawidłowe działanie modułu Prognozy',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Czy na pewno chcesz usunąć etap Zakończone porażką? Usunięcie tego etapu spowoduje nieprawidłowe działanie modułu Prognozy',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Usunięcie tego pola niestandardowego spowoduje usunięcie zarówno pola niestandardowego, jak i danych powiązanych z polem niestandardowym w bazie danych. Pole nie będzie dłużej wyświetlane w żadnym układzie modułu.'
        . ' Jeśli to pole jest uwzględnione we wzorze obliczania wartości dla jakichkolwiek pól, wzór tej przestanie działać.'
        . '\\n\\nTo pole nie będzie już dostępne do użycia w Raportach; ta zmiana będzie obowiązywać od wylogowania i ponownego zalogowania do aplikacji. Wszelkie raporty zawierające to pole należy zaktualizować, aby można je było uruchomić.'
        . '\\n\\nCzy chcesz kontynuować?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Czy na pewno chcesz usunąć to powiązanie?<br>Uwaga: ta operacja może potrwać wiele minut.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Spowoduje to ustawienie relacji jako trwałej. Czy na pewno chcesz zamieścić tę relację?',
'LBL_CONFIRM_DONT_SAVE' => 'Od ostatniego zapisu zostały wprowadzone zmiany. Czy chcesz zapisać je teraz?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Zapisać zmiany?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Dane mogą zostać ucięte i nie będzie można cofnąć tej operacji. Czy na pewno chcesz kontynuować?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Wybierz odpowiedni typ danych, bazując na typie danych, które będą gromadzone w poszczególnych polach.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Skonfiguruj pole, aby można było przeprowadzać w nim wyszukiwanie pełnotekstowe.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Zwiększanie poziomu istotności to proces zwiększania znaczenia pól rekordu.<br />Pola o większym poziomie istotności będą miały większą wagę przy wyszukiwaniu. Przy przeprowadzaniu wyszukiwania zgodne rekordy z polami o większej wadze będą wyświetlane wyżej w wynikach wyszukiwania.<br />Domyślna wartość wynosi 1,0 i oznacza neutralny poziom istotności. Aby zwiększyć poziom istotności, można zastosować dowolną wartość zmiennoprzecinkową większą od 1. Zmniejszenie poziomu istotności można uzyskać, stosując wartości niższe niż 1. Na przykład wartość 1,35 spowoduje zwiększenie istotności pola o 135%. Użycie wartości 0,60 spowoduje zmniejszenie poziomu istotności.<br />Uwaga: w poprzednich wersjach wymagane było przeprowadzenie ponownego indeksowania dla wyszukiwania pełnotekstowego. Teraz nie jest to konieczne.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Tak</b>: Pole będzie zawarte w imporcie.<br><b>Nie</b>: Pole nie będzie zawarte w imporcie.<br><b>Wymagane</b>: Wartość pola musi być wypełniona w każdym imporcie.',
'LBL_POPHELP_PII'=>'To pole zostanie automatycznie oznaczone do inspekcji i dostępne w widoku Informacji osobowych.<br>Pola Informacji osobowych można również usunąć na stałe, gdy rekord jest powiązany z żądaniem usunięcia danych osobowych.<br>Usunięcie zostaje wykonane za pośrednictwem modułu Ochrona danych osobowych i mogą je wykonać administratorzy lub użytkownicy pełniący rolę menedżera ochrony danych osobowych.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Wprowadź szerokość w pikselach. <br> Załadowany obrazek zostanie wyskalowany do takiej szerokości.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Wprowadź wysokość w pikselach. <br> Załadowany obrazek zostanie wyskalowany do takiej wysokości.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Włączone</b>: to pole będzie wyświetlane w funkcji Scalanie duplikatów, ale nie będzie go można użyć dla warunków filtra w funkcji Znajdź duplikaty.<br><b>Wyłączone</b>: to pole nie będzie wyświetlane w funkcji Scalanie duplikatów i nie będzie dostępne do użycia dla warunków filtra w funkcji Znajdź duplikaty.'
. '<br><b>W filtrze</b>: pole będzie wyświetlane w funkcji Scalanie duplikatów, a także będzie dostępne w funkcji Znajdź duplikaty.<br><b>Tylko filtr</b>: pole nie będzie wyświetlane w funkcji Scalanie duplikatów, ale będzie dostępne w funkcji Znajdź duplikaty.<br><b>Domyślny wybrany filtr</b>: pole będzie użyte dla warunku filtra domyślnie na stronie Znajdź duplikaty i będzie również wyświetlane w funkcji Scalanie duplikatów.'
,
'LBL_POPHELP_CALCULATED'=>"Utwórz formułę do określenia wartości w tym polu.<br>"
   . "Definicje Workflow zawierające czynność, które zostały ustawione do aktualizacji tego pola przestaną wykonywać tę czynność.<br>"
   . "Pola wykorzystujące tę formułę nie będą obliczane w czasie rzeczywistym w "
   . "portal Self-Service Sugar lub "
   . "Układy Mobile EditView.",

'LBL_POPHELP_DEPENDENT'=>"Utwórz formułę, aby ustalić, czy to pole jest widoczne w układach.<br/>"
        . "Pola zależne będą podlegały formule zależności w mobilnym widoku opartym na przeglądarce, <br/>"
        . "ale nie będą podlegały tej formule w natywnych aplikacjach, takich jak Sugar Mobile dla iPhone. <br/>"
        . "Nie będą podlegały tej formule w portalu Sugar Self-Service.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Zaznacz, aby użyć tego pola podczas wyszukiwania rekordów za pomocą funkcji Wyszukiwanie globalne w tym module.',
//Revert Module labels
'LBL_RESET' => 'Resetuj',
'LBL_RESET_MODULE' => 'Przywróć moduł',
'LBL_REMOVE_CUSTOM' => 'Usuń dostosowania',
'LBL_CLEAR_RELATIONSHIPS' => 'Usuń relacje',
'LBL_RESET_LABELS' => 'Przywróć etykiety',
'LBL_RESET_LAYOUTS' => 'Przywróć układy',
'LBL_REMOVE_FIELDS' => 'Usuń pola niestandardowe',
'LBL_CLEAR_EXTENSIONS' => 'Usuń rozszerzenia',

'LBL_HISTORY_TIMESTAMP' => 'Znacznik czasu',
'LBL_HISTORY_TITLE' => 'historia',

'fieldTypes' => array(
                'varchar'=>'Pole tekstowe',
                'int'=>'Liczba całkowita',
                'float'=>'Liczba zmiennoprzecinkowa',
                'bool'=>'Pole wyboru',
                'enum'=>'Lista rozwijana',
                'multienum' => 'Lista wielokrotnego wyboru',
                'date'=>'Data',
                'phone' => 'Telefon',
                'currency' => 'Waluta',
                'html' => 'HTML',
                'radioenum' => 'Przycisk opcji',
                'relate' => 'Powiąż',
                'address' => 'Adres',
                'text' => 'Pole tekstowe',
                'url' => 'Adres URL',
                'iframe' => 'IFrame',
                'image' => 'Obraz',
                'encrypt'=>'Szyfruj',
                'datetimecombo' =>'Data i czas',
                'decimal'=>'Dziesiętne',
),
'labelTypes' => array(
    "" => "Często używane etykiety",
    "all" => "Wszystkie etykiety",
),

'parent' => 'Relacja elastyczna',

'LBL_ILLEGAL_FIELD_VALUE' =>"Klucz listy rozwijalnej nie może zawierać cytatów.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Wybrano ten element do usunięcia z listy rozwijanej. Wszystkie pola listy rozwijanej wykorzystujące tę listę z tym elementem jako wartością nie będą dalej wyświetlać tej wartości, a wartość nie będzie już dostępna do wyboru z pól listy rozwijanej. Czy na pewno chcesz kontynuować?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Wybierz, aby zweryfikować to pole pod kątem wprowadzenia 10-cyfrowej wartości<br>" .
                                 "numeru telefonu z uwzględnieniem kodu kraju 1 oraz <br>" .
                                 "zastosowania amerykańskiego formatu numeru telefonu, gdy rekord<br>" .
                                 "jest zapisywany. Zastosowany zostanie następujący format: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Wszystkie moduły',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (powiązane {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Kopiuj z układu',
);
