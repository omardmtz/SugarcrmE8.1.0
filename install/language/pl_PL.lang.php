<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

$mod_strings = array(
	'LBL_BASIC_SEARCH'					=> 'Wyszukiwanie podstawowe',
	'LBL_ADVANCED_SEARCH'				=> 'Wyszukiwanie zaawansowane',
	'LBL_BASIC_TYPE'					=> 'Typ podstawowy',
	'LBL_ADVANCED_TYPE'					=> 'Typ zaawansowany',
	'LBL_SYSOPTS_1'						=> 'Wybierz jedną z poniższych opcji konfiguracji systemu.',
    'LBL_SYSOPTS_2'                     => 'Jaki typ bazy danych będzie używany dla instancji Sugar, która zostanie zainstalowana?',
	'LBL_SYSOPTS_CONFIG'				=> 'Konfiguracja systemu',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Określ typ bazy danych',
    'LBL_SYSOPTS_DB_TITLE'              => 'Typ bazy danych',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Popraw następujące błędy zanim przejdziesz dalej:',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Ustaw następujący folder jako możliwy do zapisu:',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Twoja wersja DB2 (%s) nie jest obsługiwana przez Sugar. Musisz zainstalować wersję, która jest kompatybilna z aplikacją Sugar. Aby sprawdzić obsługiwane wersje DB2, sprawdź Macierz kompatybilności w Notatkach o wydaniu.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Jeśli wybierzesz opcję Oracle, klienta Oracle musi być zainstalowany i skonfigurowany.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
	'ERR_DB_OCI8_CONNECT'				=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
	'ERR_DB_OCI8_VERSION'				=> 'Twoja wersja Oracle (%s) nie jest obsługiwana przez Sugar. Należy zainstalować wersję, która jest kompatybilna z aplikacją Sugar. Aby sprawdzić obsługiwane wersje Oracle, sprawdź Macierz kompatybilności w Notatkach o wydaniu.',
    'LBL_DBCONFIG_ORACLE'               => 'Wprowadź nazwę swojej bazy danych. Będzie to domyślne pole tabeli, które jest przydzielone do Twojego użytkownika (SID z tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Zapytanie dotyczące szansy',
	'LBL_Q1_DESC'						=> 'Szanse wg typu',
	'LBL_Q2_DESC'						=> 'Szanse wg Kontrahentów',
	'LBL_R1'							=> 'Półroczny raport szans sprzedaży',
	'LBL_R1_DESC'						=> 'Szanse w następnym półroczu podzielone na miesiąc i typ',
	'LBL_OPP'							=> 'Zbiór danych szans',
	'LBL_OPP1_DESC'						=> 'Tutaj możesz zmienić wygląd zapytania niestandardowego',
	'LBL_OPP2_DESC'						=> 'To zapytanie zostanie umieszczone poniżej pierwszego zapytania w raporcie',
    'ERR_DB_VERSION_FAILURE'			=> 'Nie można sprawdzić wersji bazy danych.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Wprowadź nazwę użytkownika dla administratora Sugar. ',
	'ERR_ADMIN_PASS_BLANK'				=> 'Wprowadź hasło dla administratora Sugar.',

    'ERR_CHECKSYS'                      => 'Wykryto błędy podczas testu kompatybilności. W celu zapewnienia prawidłowego działania instalacji SugarCRM wykonaj odpowiednie czynności, aby rozwiązać problemy wymienione poniżej, a następnie ponownie wybierz przycisk lub przeprowadź instalację od początku.',
    'ERR_CHECKSYS_CALL_TIME'            => 'Funkcja PHP Allow Call Time Pass Reference jest włączona (zmień ustawienia na Wyłączona w php.ini)',

	'ERR_CHECKSYS_CURL'					=> 'Nie znaleziono: aplikacja Sugar Scheduler będzie działać z ograniczoną funkcjonalnością. Usługa archiwizacji wiadomości e-mail nie będzie działać.',
    'ERR_CHECKSYS_IMAP'					=> 'Nie odnaleziono: Poczta przychodząca i poczta kampanii wymagają bibliotek IMAP. Nie będą one działać.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'Funkcja Magic Quotes GPC nie może być włączona, gdy używasz serwera MS SQL.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Ostrzeżenie:',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> ' (Ustaw to na wartość ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M lub większą w pliku php.ini)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Minimalna wspierana wersja to 4.1.2 — znaleziono: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Nie można zapisać i odczytać zmiennych sesji. Nie można kontynuować instalacji.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Nieprawidłowy katalog',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Ostrzeżenie: Niezapisywalny',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Twoja wersja PHP nie jest obsługiwana przez SugarCRM. Należy zainstalować wersję, która jest kompatybilna z aplikacją Sugar.  Sprawdź Macierz kompatybilności w Notatkach o wydaniu (Release Notes) w celu sprawdzenia, które wersje for PHP są obsługiwane. Twoja wersja to ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Twoja wersja IIS nie jest obsługiwana przez Sugar. Należy zainstalować wersję, która jest kompatybilna z aplikacją. Sprawdź w Macierzy Kompatybilności w Uwagach do wydania, które z wersji IIS są obsługiwane. Twoja wersja to ',
	'ERR_CHECKSYS_FASTCGI'              => 'Wykryliśmy, że nie jest używany kontroler FastCGI do mapowania PHP. Należy zainstalować/skonfigurować wersję, która jest kompatybilna z aplikacją Sugar.  Sprawdź w Macierzy Kompatybilności w Uwagach do wydania, które z wersji są obsługiwane. Przejdź do <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a>, aby poznać szczegóły',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'W celu optymalizacji wydajności z wykorzystaniem IIS/FastCGI sapi ustaw fastcgi.logging na 0 w pliku php.ini.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Zainstalowano nieobsługiwaną wersję PHP: ( wersja',
    'LBL_DB_UNAVAILABLE'                => 'Baza danych niedostępna',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Nie znaleziono funkcji obsługi bazy danych. Upewnij się, że posiadasz niezbędne sterowniki dla jednego z następujących obsługiwanych typów baz danych: MySQL, MS SQLServer, Oracle lub DB2. Może musisz usunąć komentarz rozszerzenia w pliku php.ini lub ponownie skompilować go z odpowiednim binarnym plikiem w zależności od posiadanej wersji PHP.  Więcej informacji na temat włączania obsługi bazy danych można znaleźć w Instrukcji obsługi PHP.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Funkcje powiązane z bibliotekami parsera XML, wymagane przez aplikacje Sugar, nie zostały odnalezione. Może być konieczne usunięcie komentarzy w odpowiednich rozszerzeniach w pliku php.ini lub przekompilowanie przy użyciu odpowiedniego pliku binarnego zależnie od używanej wersji PHP. Więcej informacji można znaleźć w instrukcji PHP.',
    'LBL_CHECKSYS_CSPRNG' => 'Generator liczb losowych',
    'ERR_CHECKSYS_MBSTRING'             => 'Funkcje powiązane z rozszerzeniem PHP Multibyte Strings (mbstring), wymagane przez aplikację Sugar, nie zostały znalezione. <br/><br/>Generalnie moduł mbstring nie jest domyślnie włączony w PHP i należy go włączyć przez dodanie opcji --enable-mbstring, podczas budowania i kompilacji pliku binarnego PHP. Więcej informacji o sposobie włączania obsługi mbstring można znaleźć w instrukcji PHP.',
    'ERR_CHECKSYS_MCRYPT'               => "Moduł mcrypt nie jest załadowany. Więcej informacji na temat ładowania modułu mcrypt można znaleźć w instrukcji języka PHP.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'Opcja session.save_path w pliku konfiguracji PHP (php.ini) nie jest zdefiniowana lub wskazuje na folder, który nie istnieje. Być może wystarczy jedynie ustawić parametr dla opcji save_path setting w pliku php.ini lub sprawdzić, czy ustawiony w niej folder istnieje w systemie.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Opcja session.save_path w pliku konfiguracji PHP (php.ini) wskazuje na folder, dla którego nie są ustawione prawa do zapisu. Wykonaj niezbędne kroki w celu umożliwienia zapisu w tym folderze.  <br>Zależnie od systemu operacyjnego może to wymagać zmian praw dostępu poprzez wykonanie polecenia chmod 766 lub kliknięcia prawym przyciskiem nazwy pliku, przejścia do właściwości i anulowania zaznaczenia opcji tylko do odczytu.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Plik config istnieje, ale nie można wprowadzać w nim zmian. Wykonaj niezbędne kroki w celu umożliwienia zapisu w tym folderze. Zależnie od systemu operacyjnego może to wymagać zmian praw dostępu poprzez wykonanie polecenia chmod 766 lub kliknięcia prawym przyciskiem nazwy pliku, przejścia do właściwości i anulowania zaznaczenia opcji tylko do odczytu.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Plik config override istnieje, ale nie można wprowadzać w nim zmian. Wykonaj niezbędne kroki w celu umożliwienia zapisu w tym folderze. Zależnie od systemu operacyjnego może to wymagać zmian praw dostępu poprzez wykonanie polecenia chmod 766 lub kliknięcia prawym przyciskiem nazwy pliku, przejścia do właściwości i anulowania zaznaczenia opcji tylko do odczytu.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Katalog niestandardowy istnieje, ale nie można w nim zapisywać. Zależnie od systemu operacyjnego może to wymagać zmian praw dostępu (polecenie  chmod 766) lub kliknięcia prawym przyciskiem katalogu i anulowania zaznaczenia opcji tylko do odczytu. Wykonaj niezbędne kroki w celu umożliwienia zapisu w tym folderze.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "Wymienione poniżej pliki i foldery nie umożliwiają zapisu. Zależnie od systemu operacyjnego może to wymagać zmian praw dostępu do plików lub katalogu nadrzędnego (polecenie chmod 755) lub kliknięcia prawym przyciskiem katalogu nadrzędnego i anulowania zaznaczenia opcji tylko do odczytu oraz zastosowania tej zmiany do wszystkich folderów podrzędnych.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Opcja Safe Mode jest włączona (możesz ją wyłączyć w php.ini)',
    'ERR_CHECKSYS_ZLIB'					=> 'Nie znaleziono obsługi dla ZLib: SugarCRM zyskuje na wydajności dzięki kompresji zlib.',
    'ERR_CHECKSYS_ZIP'					=> 'Nie znaleziono obsługi dla ZIP: SugarCRM wymaga wsparcia dla ZIP w celu przetwarzania skompresowanych plików.',
    'ERR_CHECKSYS_BCMATH'				=> 'Nie odnaleziono obsługi BCMath: SugarCRM wymaga obsługi BCMath do arytmetyki liczb dużej precyzji.',
    'ERR_CHECKSYS_HTACCESS'             => 'Test nadpisania dla .htaccess nie powiódł się. To prawdopodobnie oznacza, że nie została ustawiona opcja zezwolenia na nadpisanie w katalogu Sugar.',
    'ERR_CHECKSYS_CSPRNG' => 'Wyjątek CSPRNG',
	'ERR_DB_ADMIN'						=> 'Wprowadzony login lub hasło administratora bazy danych jest niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłową nazwę użytkownika i hasło. (Błąd: ',
    'ERR_DB_ADMIN_MSSQL'                => 'Wprowadzony login lub hasło administratora bazy danych jest niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłową nazwę użytkownika i hasło.',
	'ERR_DB_EXISTS_NOT'					=> 'Określona baza danych nie istnieje.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'Baza danych już istnieje i zawiera dane konfiguracyjne. Aby przeprowadzić instalację w wybranej bazie danych, uruchom ponownie instalację i wybierz opcję: Czy usunąć i odtworzyć istniejące tabele Sugar CRM? W celu aktualizacji użyj Kreatora aktualizacji w konsoli administratora.  Przeczytaj dokumentację aktualizacji umieszczoną <a href="http://www.sugarforge.org/content/downloads/" target="_new">tutaj</a>.',
	'ERR_DB_EXISTS'						=> 'Wybrana nazwa bazy danych już istnieje — nie można utworzyć następnej bazy o tej samej nazwie.',
    'ERR_DB_EXISTS_PROCEED'             => 'Wybrana nazwa bazy danych już istnieje.  Możesz<br>1. kliknąć przycisk powrotu i wybrać inną nazwę dla bazy danych<br>2. kliknąć opcję przejścia dalej i kontynuować, ale wtedy wszystkie istniejące tabele w tej bazie danych zostaną usunięte.  <strong>To oznacza, że wszystkie tabele i dane zostaną bezpowrotnie usunięte.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Pole nazwy hosta nie może być puste.',
	'ERR_DB_INVALID'					=> 'Wybrano nieprawidłowy typ bazy danych.',
	'ERR_DB_LOGIN_FAILURE'				=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Wprowadzony host, login i/lub hasło bazy danych są niepoprawne i połączenie z bazą nie może zostać nawiązane. Wprowadź prawidłowego hosta, nazwę użytkownika oraz hasło',
	'ERR_DB_MYSQL_VERSION'				=> 'Twoja wersja MySQL (%s) nie jest obsługiwana przez aplikację Sugar. Należy zainstalować wersję, która jest kompatybilna z aplikacją.  Sprawdź, które wersje MySQL są obsługiwane, w macierzy kompatybilności w dokumencie Uwagi do wydania.',
	'ERR_DB_NAME'						=> 'Pole nazwy bazy danych nie może być puste.',
	'ERR_DB_NAME2'						=> "Nazwa bazy danych nie może zawierać znaków \\, / ani .",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Nazwa bazy danych nie może zawierać znaków \\, / ani .",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Nazwa bazy danych nie może zaczynać się od cyfry, znaku # lub @ i nie może zawierać spacji ani znaków \", ', *, /, \\, ?, :, <, >, &, ! ani -",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Nazwa bazy danych może zawierać tylko znaki alfanumeryczne i symbole #, _, -, :, ., / lub $",
	'ERR_DB_PASSWORD'					=> 'Wprowadzone hasła administratora serwera bazy danych aplikacji nie są zgodne. Wprowadź ponownie takie same hasła w polach hasła.',
	'ERR_DB_PRIV_USER'					=> 'Wprowadź nazwę użytkownika dla administratora bazy danych. Ta nazwa jest wymagana do zainicjowania połączenia z bazą danych.',
	'ERR_DB_USER_EXISTS'				=> 'Wprowadzona nazwa użytkownika bazy danych Sugar już istnieje. Nie można utworzyć nowego użytkownika o tej samej nazwie. Wprowadź nową nazwę użytkownika.',
	'ERR_DB_USER'						=> 'Wprowadź nazwę użytkownika dla administratora bazy danych Sugar.',
	'ERR_DBCONF_VALIDATION'				=> 'Popraw następujące błędy zanim przejdziesz dalej:',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'Wprowadzone hasła dla użytkownika bazy danych Sugar nie są zgodne. Wprowadź takie same hasła w polach hasła.',
	'ERR_ERROR_GENERAL'					=> 'Pojawiły się następujące błędy:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Nie można usunąć pliku:',
	'ERR_LANG_MISSING_FILE'				=> 'Nie można odnaleźć pliku:',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Nie odnaleziono pliku językowego w katalogu include/language w pakiecie językowym:',
	'ERR_LANG_UPLOAD_1'					=> 'Wystąpił problem z przesłaniem. Spróbuj ponownie.',
	'ERR_LANG_UPLOAD_2'					=> 'Pakiet językowy musi mieć postać archiwum ZIP.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP nie może przenieść pliku tymczasowego do katalogu aktualizacji.',
	'ERR_LICENSE_MISSING'				=> 'Brak wymaganych pól',
	'ERR_LICENSE_NOT_FOUND'				=> 'Nie odnaleziono pliku licencji!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Wybrany katalog dziennika nie jest prawidłowy.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Wybrany katalog dziennika nie umożliwia zapisu.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Katalog dziennika jest wymagany. Możesz określić własny katalog.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Nie można wykonać skryptu bezpośrednio.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Nie można użyć pojedynczego znaku cudzysłowu dla ',
	'ERR_PASSWORD_MISMATCH'				=> 'Hasła wprowadzone dla administratora aplikacji Sugar nie są zgodne. Wprowadź takie same hasła w polach hasła.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Nie można zapisać do pliku <span class=stop>config.php</span>.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Możesz kontynuować instalację, tworząc ręcznie plik config.php i wklejając do niego informacje konfiguracyjne wyświetlone poniżej. Jednakże <strong>konieczne jest </strong>utworzenie pliku config.php przed przejściem do następnego kroku.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Czy utworzono plik config.php?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Uwaga: nie udało się zapisać do pliku config.php. Upewnij się, że plik istnieje.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Nie można zapisać do pliku ',
	'ERR_PERFORM_HTACCESS_2'			=> '.',
	'ERR_PERFORM_HTACCESS_3'			=> 'Jeśli chcesz zabezpieczyć swój plik dziennika przed dostępem za pomocą przeglądarki, utwórz plik .htaccess z następującym wpisem w Twoim katalogu dziennika:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>Nie wykryto połączenia internetowego.</b> Jeśli uzyskasz połączenie, odwiedź <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a>, aby zarejestrować swoją kopię SugarCRM. Dzięki przesłanym nam ogólnym informacjom o tym, jak Twoja firma zamierza korzystać z aplikacji SugarCRM, możemy upewnić, że dostarczamy dostosowaną do twoich potrzeb biznesowych aplikację.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Określony dla danych sesji katalog nie jest prawidłowy.',
	'ERR_SESSION_DIRECTORY'				=> 'Określony dla danych sesji katalog nie umożliwia do zapisu.',
	'ERR_SESSION_PATH'					=> 'Ścieżka sesji jest wymagana. Można określić własną ścieżkę.',
	'ERR_SI_NO_CONFIG'					=> 'Brak pliku config_si.php w katalogu głównym lub nie zdefiniowano zmiennej $sugar_config_si w pliku config.php',
	'ERR_SITE_GUID'						=> 'ID aplikacji jest wymagane. Możesz wprowadzić własny identyfikator.',
    'ERROR_SPRITE_SUPPORT'              => "W chwili obecnej nie udało się odnaleźć biblioteki GD, nie będzie więc można korzystać z funkcjonalności CSS Sprite.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Uwaga: należy zmienić konfigurację PHP tak, aby umożliwić przesłanie plików o wielkości co najmniej 6 MB.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Wielkość plików do przesłania',
	'ERR_URL_BLANK'						=> 'Wprowadź podstawowy adres URL dla tej instancji Sugar.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Nie można zlokalizować rekordów instalacyjnych z',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Przesłany plik jest niezgodny z tym formatem (Professional, Enterprise lub Ultimate edition) aplikacji Sugar: ',
	'ERROR_LICENSE_EXPIRED'				=> "Błąd: Twoja licencja wygasła",
	'ERROR_LICENSE_EXPIRED2'			=> " dni temu. Wejdź na stronę <a href='index.php?action=LicenseSettings&module=Administration'>Zarządzanie licencjami</a>  w panelu administracyjnym i wprowadź nowy klucz licencyjny.  Jeśli nie wprowadzisz nowego klucza w ciągu 30 dni od wygaśnięcia poprzedniego, stracisz możliwość logowania się do aplikacji.",
	'ERROR_MANIFEST_TYPE'				=> 'Plik Manifest musi określać typ pakietu.',
	'ERROR_PACKAGE_TYPE'				=> 'Plik Manifest wskazuje na niesklasyfikowany typ pakietu instalacyjnego',
	'ERROR_VALIDATION_EXPIRED'			=> "Błąd: Twój klucz walidacyjny wygasł",
	'ERROR_VALIDATION_EXPIRED2'			=> " dni temu. Wejdź na stronę <a href='index.php?action=LicenseSettings&module=Administration'>Zarządzanie licencjami</a> w panelu administracyjnym i wprowadź nowy klucz legalizacyjny.  Jeśli nie wprowadzisz nowego klucza w ciągu 30 dni od wygaśnięcia poprzedniego, stracisz możliwość logowania się do aplikacji.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Ładowany plik nie jest kompatybilny z aktualną wersją aplikacji Sugar: ',

	'LBL_BACK'							=> 'Wstecz',
    'LBL_CANCEL'                        => 'Anuluj',
    'LBL_ACCEPT'                        => 'Akceptuję',
	'LBL_CHECKSYS_1'					=> 'Aby instalacja SugarCRM działała poprawnie, upewnij się, że wszystkie punkty kontroli systemu poniżej są oznaczone na zielono. Jeśli któryś z nich oznaczony jest na czerwono, podejmij niezbędne kroki w celu ich naprawienia.<BR><BR> W razie potrzeby uzyskania informacji o tym, w jaki sposób dokonać poprawek, wejdź na stronę <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.',
	'LBL_CHECKSYS_CACHE'				=> 'Zapisywalne podkatalogi pamięci podręcznej',
    'LBL_DROP_DB_CONFIRM'               => 'Wybrana nazwa bazy danych już istnieje.<br>Możesz:<br>1. kliknąć przycisk Anuluj i wybrać inną nazwę bazy  danych<br>2. kliknąć przycisk Akceptuję i kontynuować, ale wtedy wszystkie istniejące tabele w tej bazie zostaną skasowane.  <strong>To oznacza, że wszystkie tabele i dane zostaną usunięte bezpowrotnie.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'Funkcja PHP Allow Call Time Pass Reference jest wyłączona',
    'LBL_CHECKSYS_COMPONENT'			=> 'Komponent',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Komponenty opcjonalne',
	'LBL_CHECKSYS_CONFIG'				=> 'Zapisywalny plik konfiguracyjny SugarCRM (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Zapisywalny plik konfiguracyjny SugarCRM (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'Moduł cURL',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Ustawienie ścieżki zapisu sesji',
	'LBL_CHECKSYS_CUSTOM'				=> 'Zapisywalne własne katalogi',
	'LBL_CHECKSYS_DATA'					=> 'Zapisywalne podkatalogi danych',
	'LBL_CHECKSYS_IMAP'					=> 'Moduł IMAP',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'Moduł MB Strings',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt Module',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (bez limitu)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (Nielimitowany)',
	'LBL_CHECKSYS_MEM'					=> 'Limit pamięci PHP',
	'LBL_CHECKSYS_MODULE'				=> 'Zapisywalne podkatalogi i pliki modułów',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'Wersja MySQL',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Niedostępne',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> 'Lokalizacja pliku konfiguracyjnego PHP (php.ini):',
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (wersja',
	'LBL_CHECKSYS_PHPVER'				=> 'Wersja PHP',
    'LBL_CHECKSYS_IISVER'               => 'Wersja IIS',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Sprawdź ponownie',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'Tryb bezpieczny PHP jest wyłączony',
	'LBL_CHECKSYS_SESSION'				=> 'Ścieżka zapisu sesji umożliwiająca zapis (',
	'LBL_CHECKSYS_STATUS'				=> 'Status',
	'LBL_CHECKSYS_TITLE'				=> 'Akceptacja sprawdzania systemu',
	'LBL_CHECKSYS_VER'					=> 'Znaleziono: ( wersja',
	'LBL_CHECKSYS_XML'					=> 'Parser XML',
	'LBL_CHECKSYS_ZLIB'					=> 'Moduł kompresji ZLIB',
	'LBL_CHECKSYS_ZIP'					=> 'Moduł obsługi ZIP',
    'LBL_CHECKSYS_BCMATH'				=> 'Moduł Arytmetyka liczb dużej precyzji',
    'LBL_CHECKSYS_HTACCESS'				=> 'Zezwól na nadpisanie ustawień dla .htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'Napraw następujące błędy plików i katalogów przed kontynuacją:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Napraw następujące katalogi modułów i podrzędne pliki przed kontynuacją:',
    'LBL_CHECKSYS_UPLOAD'               => 'Katalog Upload z prawami do zapisu',
    'LBL_CLOSE'							=> 'Zamknij',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'utworzony',
	'LBL_CONFIRM_DB_TYPE'				=> 'Typ bazy danych',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Potwierdź poniższe ustawienia. Jeśli chcesz zmienić którąkolwiek z wartości, kliknij Powrót w celu edycji. W innym przypadku kliknij Dalej, aby rozpocząć instalację.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Informacje o licencji',
	'LBL_CONFIRM_NOT'					=> 'nie',
	'LBL_CONFIRM_TITLE'					=> 'Potwierdź ustawienia',
	'LBL_CONFIRM_WILL'					=> 'zostanie',
	'LBL_DBCONF_CREATE_DB'				=> 'Utwórz bazę danych',
	'LBL_DBCONF_CREATE_USER'			=> 'Utwórz użytkownika',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Uwaga: Wszystkie dane w Sugar zostaną usunięte,<br>jeśli to pole zostanie zaznaczone.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Czy usunąć i odtworzyć istniejące tabele Sugar?',
    'LBL_DBCONF_DB_DROP'                => 'Usuń tabele',
    'LBL_DBCONF_DB_NAME'				=> 'Nazwa bazy danych',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Hasło użytkownika bazy danych Sugar',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Powtórz hasło użytkownika bazy danych Sugar',
	'LBL_DBCONF_DB_USER'				=> 'Nazwa użytkownika bazy danych Sugar',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Nazwa użytkownika bazy danych Sugar',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Nazwa użytkownika administratora bazy danych',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Hasło administratora bazy danych',
	'LBL_DBCONF_DEMO_DATA'				=> 'Czy wypełnić bazę danych przykładowymi danymi?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Wybierz dane przykładowe',
	'LBL_DBCONF_HOST_NAME'				=> 'Nazwa hosta',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Instancja hosta',
	'LBL_DBCONF_HOST_PORT'				=> 'Port',
    'LBL_DBCONF_SSL_ENABLED'            => 'Włącz połączenie SSL',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Wprowadź dane konfiguracyjne poniżej. W przypadku braku pewności odnośnie zawartości do wpisania sugerowane jest użycie wartości domyślnych.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Czy użyć tekstu multi-byte text w przykładowych danych?',
    'LBL_DBCONFIG_MSG2'                 => 'Nazwa serwera sieciowego lub maszyny (hosta), na którym umieszczona jest baza danych (np. localhost lub www.mojadomena.com):',
    'LBL_DBCONFIG_MSG3'                 => 'Nazwa bazy danych, która będzie zawierać dane instancji Sugar, którą zamierzasz zainstalować:',
    'LBL_DBCONFIG_B_MSG1'               => 'Wprowadzenie nazwy użytkownika i hasła administratora bazy danych, który może tworzyć nowe bazy i tabele oraz użytkowników z prawem zapisu do tabel, jest niezbędne w celu utworzenia bazy danych aplikacji Sugar.',
    'LBL_DBCONFIG_SECURITY'             => 'Z przyczyn bezpieczeństwa możesz wyznaczyć jednego użytkownika uprawnionego do połączeń z bazą danych Sugar.  Ten użytkownik musi mieć prawa do zapisu, uaktualniania danych, oraz odczytu danych z konkretnej bazy, która zostanie utworzona dla tej instancji. Ten użytkownik może być administratorem bazy określonym powyżej; można też utworzyć nowego użytkownika lub wybrać jednego z już istniejących.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Zrób to dla mnie',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Użyj istniejącego użytkownika',
    'LBL_DBCONFIG_CREATE_DD'            => 'Określ użytkownika do utworzenia',
    'LBL_DBCONFIG_SAME_DD'              => 'Taki sam, jak użytkownik administrator',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Wyszukiwanie pełnotekstowe',
    'LBL_FTS_INSTALLED'                 => 'Zainstalowany',
    'LBL_FTS_INSTALLED_ERR1'            => 'Funkcja wyszukiwania pełnotekstowego nie jest zainstalowana.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Możesz zainstalować, ale nie będzie możliwe korzystanie z wyszukiwania pełnotekstowego. Sprawdź w przewodniku instalacji serwera bazy danych, jak to zrobić, lub skontaktuj się z Administratorem.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Hasło użytkownika z uprawnieniami dostępu do bazy',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Czy konto powyżej jest kontem użytkownika z uprawnieniami dostępu do bazy?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'To konto użytkownika z uprawnieniami musi mieć właściwe uprawnienia do tworzenia bazy danych, tworzenia i usuwania tabel oraz tworzenia użytkowników. To konto będzie używane tylko do wykonywania zadań niezbędnych podczas instalacji. Możesz także użyć użytkownika bazy danych, jak powyżej, jeśli ma on wystarczające uprawnienia.',
	'LBL_DBCONF_PRIV_USER'				=> 'Nazwa użytkownika z uprawnieniami dostępu do bazy',
	'LBL_DBCONF_TITLE'					=> 'Konfiguracja bazy danych',
    'LBL_DBCONF_TITLE_NAME'             => 'Wprowadź nazwę bazy danych',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Wprowadź informacje o użytkowniku bazy danych',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'Po wprowadzeniu zmian możesz kliknąć przycisk Start umieszczony poniżej, aby rozpocząć instalację. <i>Po zakończeniu instalacji, możesz zmienić wartość zmiennej installer_locked\' to \'true.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'Instalator został już raz uruchomiony. Z powodów bezpieczeństwa uniemożliwiono jego ponowne uruchomienie. W przypadku pewności, że chcesz uruchomić go ponownie, przejdź do pliku config.php i znajdź (lub dodaj) zmienną installer_locked i ustaw jej wartość na false. Ten wiersz pliku powinien wyglądać następująco:',
	'LBL_DISABLED_HELP_1'				=> 'Aby uzyskać pomoc podczas instalacji, odwiedź forum wsparcia',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'fora obsługi klienta',
	'LBL_DISABLED_TITLE_2'				=> 'Instalacja SugarCRM została wyłączona',
	'LBL_DISABLED_TITLE'				=> 'Instalacja SugarCRM została wyłączona',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Kodowanie najczęściej używane w Twoich ustawieniach lokalnych',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Ustawienia poczty wychodzącej',
    'LBL_EMAIL_CHARSET_CONF'            => 'Kodowanie dla poczty wychodzącej',
	'LBL_HELP'							=> 'Pomoc',
    'LBL_INSTALL'                       => 'Zainstaluj',
    'LBL_INSTALL_TYPE_TITLE'            => 'Opcje instalacji',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Wybierz typ instalacji',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>Instalacja standardowa</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => '<b>Instalacja niestandardowa</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Klucz jest wymagany do ogólnego funkcjonowania aplikacji, ale nie jest niezbędny do instalacji. Nie musisz wprowadzać klucza w tym momencie, ale należy go wpisać zaraz po zainstalowaniu aplikacji.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Wymaga minimum informacji do instalacji. Zalecane dla nowych użytkowników.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Umożliwia ustawienie dodatkowych opcji podczas instalacji. Większość tych opcji jest również dostępna po instalacji w panelu administracyjnym. Zalecane dla zaawansowanych użytkowników.',
	'LBL_LANG_1'						=> 'Aby używać innego języka, niż domyślny w aplikacji (US-English), możesz w tym momencie przesłać i zainstalować pakiet językowy. Możesz również przesłać i zainstalować pakiet językowy z poziomu aplikacji Sugar. Jeśli chcesz pominąć ten krok, kliknij Dalej.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Zainstaluj',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Usuń',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Odinstaluj',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Prześlij',
	'LBL_LANG_NO_PACKS'					=> 'Brak',
	'LBL_LANG_PACK_INSTALLED'			=> 'Następujące pakiety językowe zostały zainstalowane:',
	'LBL_LANG_PACK_READY'				=> 'Następujące pakiety językowe są gotowe do instalacji:',
	'LBL_LANG_SUCCESS'					=> 'Pakiet językowy został pomyślnie dodany.',
	'LBL_LANG_TITLE'			   		=> 'Pakiet językowy',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'Trwa instalacja Sugar.  Może to potrwać kilka minut.',
	'LBL_LANG_UPLOAD'					=> 'Prześlij pakiet językowy',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Akceptacja licencji',
    'LBL_LICENSE_CHECKING'              => 'Sprawdzenie kompatybilności systemu.',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Sprawdzanie środowiska',
    'LBL_LICENSE_CHKDB_HEADER'          => 'Weryfikowanie danych uwierzytelniających BD, FTS.',
    'LBL_LICENSE_CHECK_PASSED'          => 'System przeszedł test kompatybilności.',
    'LBL_LICENSE_REDIRECT'              => 'Przekierowywanie do',
	'LBL_LICENSE_DIRECTIONS'			=> 'Jeśli masz swoje informacje licencyjne, wprowadź je w pola poniżej.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Wprowadź klucz pobierania',
	'LBL_LICENSE_EXPIRY'				=> 'Data wygaśnięcia',
	'LBL_LICENSE_I_ACCEPT'				=> 'Akceptuję',
	'LBL_LICENSE_NUM_USERS'				=> 'Liczba użytkowników',
	'LBL_LICENSE_PRINTABLE'				=> 'Podgląd wydruku',
    'LBL_PRINT_SUMM'                    => 'Wydrukuj podsumowanie',
	'LBL_LICENSE_TITLE_2'				=> 'Licencja SugarCRM',
	'LBL_LICENSE_TITLE'					=> 'Informacje o licencji',
	'LBL_LICENSE_USERS'					=> 'Użytkownicy licencjonowani',

	'LBL_LOCALE_CURRENCY'				=> 'Ustawienia waluty',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Waluta domyślna',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Symbol waluty',
	'LBL_LOCALE_CURR_ISO'				=> 'Kod waluty (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> 'Separator tysięcy',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Separator dziesiętny',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Przykład',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Liczba po przecinku',
	'LBL_LOCALE_DATEF'					=> 'Domyślny  format daty',
	'LBL_LOCALE_DESC'					=> 'Określone ustawienia lokalne będą zastosowane globalnie dla całej instancji Sugar.',
	'LBL_LOCALE_EXPORT'					=> 'Kodowanie dla importu/eksportu<br> <i>(E-mail, .csv, vCard, PDF, import danych)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Znak podziału w pliku (.csv)',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Ustawienia importu/eksportu',
	'LBL_LOCALE_LANG'					=> 'Domyślny język',
	'LBL_LOCALE_NAMEF'					=> 'Domyślny format imienia i nazwiska',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = Forma grzecznościowa<br />f = Imię<br />l = Nazwisko',
	'LBL_LOCALE_NAME_FIRST'				=> 'Jan',
	'LBL_LOCALE_NAME_LAST'				=> 'Kowalski',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr',
	'LBL_LOCALE_TIMEF'					=> 'Domyślny format czasu',
	'LBL_LOCALE_TITLE'					=> 'Ustawienia lokalne',
    'LBL_CUSTOMIZE_LOCALE'              => 'Ustal własne ustawienia lokalne',
	'LBL_LOCALE_UI'						=> 'Interfejs użytkownika',

	'LBL_ML_ACTION'						=> 'Akcja',
	'LBL_ML_DESCRIPTION'				=> 'Opis',
	'LBL_ML_INSTALLED'					=> 'Data instalacji',
	'LBL_ML_NAME'						=> 'Nazwa',
	'LBL_ML_PUBLISHED'					=> 'Data publikacji',
	'LBL_ML_TYPE'						=> 'Typ',
	'LBL_ML_UNINSTALLABLE'				=> 'Odinstalowywalny',
	'LBL_ML_VERSION'					=> 'Wersja',
	'LBL_MSSQL'							=> 'Serwer SQL',
	'LBL_MSSQL_SQLSRV'				    => 'SQL Server (sterownik Microsoft SQL Server dla PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (rozszerzenie mysqli)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Dalej',
	'LBL_NO'							=> 'Nie',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Hasło administratora ustawień strony',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'tabela kontrolna /',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Tworzenie pliku konfiguracyjnego Sugar',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Tworzenie bazy danych</b>',
	'LBL_PERFORM_CREATE_DB_2'			=> '<b>włączone</b>',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Tworzenie użytkownika i hasła bazy danych...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Tworzenie domyślnych danych Sugar',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Tworzenie użytkownika i hasła dla bazy danych localhost...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Tworzenie tabeli relacji Sugar',
	'LBL_PERFORM_CREATING'				=> 'tworzenie /',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Tworzenie domyślnych raportów',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Tworzenie domyślnych harmonogramów prac',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Wprowadzanie domyślnych ustawień',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Tworzenie domyślnych użytkowników',
	'LBL_PERFORM_DEMO_DATA'				=> 'Wypełnianie tabel bazy danych danymi przykładowymi (to może zająć chwilę)',
	'LBL_PERFORM_DONE'					=> 'wykonano<br>',
	'LBL_PERFORM_DROPPING'				=> 'usuwanie /',
	'LBL_PERFORM_FINISH'				=> 'Zakończ',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Uaktualnianie informacji o licencji',
	'LBL_PERFORM_OUTRO_1'				=> 'Instalacja Sugar',
	'LBL_PERFORM_OUTRO_2'				=> 'jest ukończona!',
	'LBL_PERFORM_OUTRO_3'				=> 'Całkowity czas:',
	'LBL_PERFORM_OUTRO_4'				=> 'sekund.',
	'LBL_PERFORM_OUTRO_5'				=> 'Przybliżone użycie pamięci: ',
	'LBL_PERFORM_OUTRO_6'				=> 'bajtów.',
	'LBL_PERFORM_OUTRO_7'				=> 'Twój system jest zainstalowany i skonfigurowany domyślnie.',
	'LBL_PERFORM_REL_META'				=> 'dane zależności ...',
	'LBL_PERFORM_SUCCESS'				=> 'Sukces!',
	'LBL_PERFORM_TABLES'				=> 'Tworzenie tabel aplikacji, tabel kontrolnych i danych zależności',
	'LBL_PERFORM_TITLE'					=> 'Przeprowadź konfigurację',
	'LBL_PRINT'							=> 'Drukuj',
	'LBL_REG_CONF_1'					=> 'Wypełnij krótki formularz umieszczony poniżej, aby otrzymywać informacje o nowych produktach, szkoleniach, ofertach specjalnych i zaproszeniach na specjalne wydarzenia od SugarCRM. Nie sprzedajemy, nie wynajmujemy, nie udostępniamy ani w żadnej innej formie nie dystrybuujemy informacji pozyskanych tutaj firmom trzecim.',
	'LBL_REG_CONF_2'					=> 'Twoje imię, nazwisko i adres e-mail są jedynymi polami wymaganymi podczas rejestracji. Wszystkie inne pola są nieobowiązkowe, ale pomocne. Nie sprzedajemy, nie wynajmujemy, nie udostępniamy ani w żadnej innej formie nie dystrybuujemy informacji pozyskanych tutaj firmom trzecim.',
	'LBL_REG_CONF_3'					=> 'Dziękujemy za rejestrację. Kliknij przycisk Zakończ, aby zalogować się do SugarCRM. Podczas pierwszego logowania konieczne będzie użycie loginu admin oraz hasła wprowadzonego w kroku 2.',
	'LBL_REG_TITLE'						=> 'Rejestracja',
    'LBL_REG_NO_THANKS'                 => 'Nie, dziękuję',
    'LBL_REG_SKIP_THIS_STEP'            => 'Pomiń ten krok',
	'LBL_REQUIRED'						=> '* Pole wymagane',

    'LBL_SITECFG_ADMIN_Name'            => 'Nazwa administratora aplikacji Sugar',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Wprowadź ponownie hasło administratora Sugar',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Uwaga: spowoduje to nadpisanie hasła administratora wprowadzonego w czasie instalacji.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Hasło administratora Sugar',
	'LBL_SITECFG_APP_ID'				=> 'ID aplikacji',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'W przypadku zaznaczenia tej opcji należy wprowadzić ID aplikacji, aby nadpisać ID wygenerowany automatycznie. ID gwarantuje, że sesje jednej instancji Sugar nie będą używane przez inne. W przypadku istnienia klastera instalacji Sugar, muszą one współdzielić ten sam samo ID.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Wprowadź swój własny ID aplikacji',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'W przypadku zaznaczenia tej opcji należy określić katalog dla dziennika i nadpisać domyślną ścieżkę dla katalogu dziennika Sugar. Bez względu na miejsce przechowywania pliku dziennika dostęp do niego poprzez przeglądarkę będzie ograniczony za pomocą plików .htaccess.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Użyj niestandardowego katalogu dziennika',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'W przypadku zaznaczenia tej opcji należy określić bezpieczny katalog do przechowywania informacji o sesji Sugar. Można to zrobić, aby zapobiec niezabezpieczeniu danych o sesji na serwerach współdzielonych.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Użyj niestandardowego katalogu dla danych o sesji',
	'LBL_SITECFG_DIRECTIONS'			=> 'Wprowadź swoje informacje o konfiguracji projektu poniżej. W przypadku braku pewności co do zawartości, którą należy wpisać, sugerowane jest pozostawienie wartości domyślnych.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Popraw następujące błędy przed kontynuowaniem:</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Katalog dziennika',
	'LBL_SITECFG_SESSION_PATH'			=> 'Ścieżka do katalogu sesji <br>(katalog musi umożliwiać zapisywanie)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Wybierz opcje bezpieczeństwa',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'W przypadku zaznaczenia tej opcji system będzie okresowo sprawdzał, czy nie pojawiły się nowe wersje aplikacji.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Sprawdzać automatycznie czy są nowe wersje?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Konfiguracja aktualizacji Sugar',
	'LBL_SITECFG_TITLE'					=> 'Konfiguracja projektu',
    'LBL_SITECFG_TITLE2'                => 'Znajdź swoją instancję Sugar',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Zabezpieczenia projektu',
	'LBL_SITECFG_URL'					=> 'Adres URL instancji Sugar',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Użyć domyślnych?',
	'LBL_SITECFG_ANONSTATS'             => 'Czy wysyłać anonimowe statystyki?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'W przypadku zaznaczenia tej opcji aplikacja Sugar będzie wysyłać <b>anonimowe</b> statystyki dotyczące Twojej instalacji do SugarCRM Inc. za każdym razem, gdy system będzie sprawdzał, czy dostępne są nowe wersje. Te informacje pozwolą lepiej zrozumieć, jak korzystasz z aplikacji i pomoże wskazać kierunki rozwoju produktu.',
    'LBL_SITECFG_URL_MSG'               => 'Wprowadź adres URL, pod którym będzie można zalogować się do instancji Sugar po zakończeniu instalacji. Adres URL będzie również używany jako bazowy dla innych adresów na stronach aplikacji. Adres URL powinien zawierać nazwę serwera stron, nazwę maszyny lub adres IP.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Wprowadź nazwę dla systemu.  Będzie ona wyświetlana użytkownikom w przeglądarce na pasku tytułu, gdy będą zalogowani do aplikacji.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Po zakończeniu instalacji konieczne będzie użycie danych administratora do zalogowania do aplikacji (domyślny login = admin).  Wprowadź hasło administratora. To hasło można zmienić po pierwszym logowaniu. Możesz również wprowadzić inną nazwę użytkownika administratora oprócz wartości domyślnej.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Wybierz ustawienia sortowania dla systemu. Te ustawienia spowodują utworzenie tabel w języku, którego używasz. Jeśli Twój język nie wymaga specjalnych ustawień, użyj wartości domyślnych.',
    'LBL_SPRITE_SUPPORT'                => 'Wsparcie Sprite',
	'LBL_SYSTEM_CREDS'                  => 'Uwierzytelnianie systemu',
    'LBL_SYSTEM_ENV'                    => 'Środowisko systemu',
	'LBL_START'							=> 'Rozpocznij',
    'LBL_SHOW_PASS'                     => 'Pokaż hasła',
    'LBL_HIDE_PASS'                     => 'Ukryj hasła',
    'LBL_HIDDEN'                        => '<i>(ukryte)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Wybierz swój język</b>',
	'LBL_STEP'							=> 'Krok',
	'LBL_TITLE_WELCOME'					=> 'Witamy w SugarCRM',
	'LBL_WELCOME_1'						=> 'Ten instalator tworzy tabele bazy danych i wprowadza zmienne konfiguracyjne, które są potrzebne do rozpoczęcia. Cały proces powinien zająć ok. 10 minut.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Rozpocząć instalację?',
    'REQUIRED_SYS_COMP' => 'Wymagane komponenty systemowe',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Zanim rozpoczniesz, upewnij się, że posiadasz obsługiwane wersje następujących składników systemu:<br>
<ul>
<li> Baza danych / System zarządzania bazą danych (Przykłady: MySQL, Serwer SQL, Oracle, DB2)</li>
<li> Serwer sieciowy (Apache, IIS)</li>
<li> Serwer Elasticsearch</li>
</ul>
Sprawdź w macierzy kompatybilności w Uwagach do wydania, które komponenty systemu są obsługiwane przez wersję Sugar, którą instalujesz.<br>',
    'REQUIRED_SYS_CHK' => 'Wstępne sprawdzanie systemu',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Gdy rozpoczniesz proces instalacji, system przeprowadzi kontrolę na serwerze sieciowym, na którym znajdują się pliki Sugar w celu określenia, czy system jest skonfigurowany prawidłowo i posiada wszystkie niezbędne 
składniki 
do pomyślnego zakończenia instalacji. <br><br>
System sprawdzi wszystkie następujące elementy:<br>
<ul>
<li><b>Wersja PHP</b> &#8211; musi być kompatybilna 
z aplikacją</li>
<li><b>Zmienne sesji</b> &#8211; muszą działać prawidłowo</li>
<li> <b>Moduł MB Strings</b> &#8211; musi być zainstalowany i włączony w pliku php.ini</li>

<li> <b>Obsługa bazy danych</b> &#8211; musi istnieć dla MySQL, SQL Server, Oracle lub DB2</li>

<li> <b>Plik Config.php</b> &#8211; musi istnieć i mieć odpowiednie uprawnienia, umożliwiające zapis</li>
<li>Następujące pliki muszą mieć uprawnienia do zapisu:<ul><li><b>/custom</li>
<li>/cache</li>
<li>/modules</li>
<li>/upload</b></li></ul></li></ul>
Jeśli kontrola nie powiedzie się, nie będzie można przeprowadzić procesu instalacji. Wyświetlony zostanie komunikat o błędzie wyjaśniający, dlaczego system nie przeszedł 
konkretnego testu. 
Po wprowadzeniu niezbędnych zmian można sprawdzić system ponownie, a następnie kontynuować instalację.<br>',
    'REQUIRED_INSTALLTYPE' => 'Standardowa lub niestandardowa instalacja',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Po przeprowadzeniu testu systemu możesz wybrać standardowy lub niestandardowy tryb instalacji.<br><br>
Zarówno dla instalacji <b>standardowej</b>, jak i <b>niestandardowej</b> należy znać:<br>
<ul>
<li> <b>Typ bazy danych</b>, w której będą znajdować się dane Sugar <ul><li>Kompatybilne 
bazy danych 
to: MySQL, MS SQL Server, Oracle, DB2.<br><br></li></ul></li>
<li> <b>Nazwę serwera sieciowego</b> lub komputera (hosta), na którym znajduje się baza danych
<ul><li>Może to być <i>localhost</i>, jeśli baza danych jest umieszczona na lokalnym komputerze lub na tym samym serwerze co pliki Sugar.<br><br></li></ul></li>
<li><b>Nazwę bazy danych</b>, której zamierzasz użyć do przechowywania danych aplikacji Sugar</li>
<ul>
<li> Możesz mieć już istniejącą bazę danych, której chcesz użyć. Jeśli wprowadzisz nazwę istniejącej bazy, jej tabele zostaną usunięte podczas instalacji, gdy będzie definiowany nowy schemat dla bazy Sugar.</li>
<li> Jeśli jeszcze nie masz bazy danych, nazwa, którą wprowadzisz podczas instalacji będzie użyta do utworzenia nowej bazy.<br><br></li>
</ul>
<li><b>Nazwę i hasło użytkownika administratora serwera bazy danych</b> <ul><li>Administrator bazy powinien móc tworzyć tabele i użytkowników oraz zapisywać w bazie danych.</li><li>Być może konieczne będzie 
skontaktowanie się z administratorem w celu ustalenia, czy baza danych nie znajduje się na lokalnym komputerze i/lub jeśli nie jesteś administratorem bazy danych.<br><br></ul></li></li>
<li> <b>Nazwę i hasło użytkownika bazy danych Sugar</b>
</li>
<ul>
<li> Użytkownik może być jednocześnie administratorem serwera bazy danych lub można określić nazwę 
innego istniejącego użytkownika. </li>
<li> Jeśli chcesz utworzyć nowego użytkownika bazy danych, możesz podać jego login oraz hasło podczas procesu instalacji. 
Użytkownik ten zostanie utworzony w trakcie instalacji. </li>
</ul>
<li> <b>Hosta i port serwera Elasticsearch</b>

</li>
<ul>
<li>Host serwera Elasticsearch jest hostem, na którym uruchomiono mechanizm wyszukiwania. Domyślnie ustawiona jest wartość localhost, co oznacza, że mechanizm wyszukiwania działa na tym samym serwerze co aplikacja Sugar.</li>
<li>Port serwera Elasticsearch jest numerem portu serwera mechanizmu wyszukiwania, z którym Sugar ma się łączyć. Domyślną wartością jest 9200.</li>
</ul>
</ul><p>

Dla instalacji <b>niestandardowej</b> konieczna może być również znajomość następujących informacji:<br>
<ul>
<li> <b>Adres URL używany do dostępu do instancji Sugar</b> po jej zainstalowaniu. Ten adres URL powinien zawierać nazwę serwera sieciowego, nazwę komputera lub adres IP.<br><br>
</li>
<li> [Optional] <b>Ścieżka do katalogu sesji</b>, jeśli chcesz użyć niestandardowego katalogu sesji dla danych aplikacji Sugar, aby zapobiec niezabezpieczeniu danych o sesji na współdzielonych serwerach.<br><br></li>
<li> [Optional] <b>Ścieżkę niestandardowego katalogu dziennika</b>, jeśli chcesz nadpisać domyślny katalog dla dziennika Sugar.<br><br></li>
<li> [Optional] <b>ID Aplikacji</b>, jeśli chcesz nadpisać automatycznie wygenerowany ID, który gwarantuje, że sesje z jednej instancji Sugar nie zostaną użyte w innej.<br><br></li>
<li><b>Kodowanie</b> najczęściej używane w Twoich ustawieniach lokalnych.<br><br></li></ul>
W celu uzyskania bardziej szczegółowych informacji sięgnij do Instrukcji instalacji.                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Przeczytaj poniższe ważne informacje zanim rozpoczniesz instalację. Informacje te pomogą Ci określić gotowość do zainstalowania aplikacji.',


	'LBL_WELCOME_2'						=> 'Dokumentacja instalacji dostępna jest na stronie <a href="http://www.sugarcrm.com/crm/installation" target="_blank"> Sugar Wiki</a>. <BR><BR>Aby skontaktować się inżynierem pomocy technicznej SugarCRM, proszę zalogować się na  <a target="_blank" href="http://support.sugarcrm.com">portalu pomocy SugarCRM </a> i przesłać informacje o zdarzeniu pomocy technicznej.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Wybierz swój język</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Kreator ustawień',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Witamy w SugarCRM',
	'LBL_WELCOME_TITLE'					=> 'Kreator ustawień SugarCRM',
	'LBL_WIZARD_TITLE'					=> 'Kreator ustawień Sugar:',
	'LBL_YES'							=> 'Tak',
    'LBL_YES_MULTI'                     => 'Tak - Multibyte',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Przetwarzaj zadania workflow',
	'LBL_OOTB_REPORTS'		=> 'Wykonaj zaplanowane zadania generowania raportu',
	'LBL_OOTB_IE'			=> 'Sprawdź skrzynki poczty przychodzącej',
	'LBL_OOTB_BOUNCE'		=> 'Uruchom nocny proces odsyłania wiadomości e-mail',
    'LBL_OOTB_CAMPAIGN'		=> 'Uruchom nocną kampanie masowych wiadomości e-mail',
	'LBL_OOTB_PRUNE'		=> 'Oczyść bazę danych 1-go dnia miesiąca',
    'LBL_OOTB_TRACKER'		=> 'Oczyść tabele śledzenia',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Uruchom powiadomienia przypomnień e-mail',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Aktualizuj tabelę tracker_sessions',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Wyczyść kolejkę zadań',


    'LBL_FTS_TABLE_TITLE'     => 'Wprowadź ustawienia wyszukiwania pełnotekstowego',
    'LBL_FTS_HOST'     => 'Host',
    'LBL_FTS_PORT'     => 'Port',
    'LBL_FTS_TYPE'     => 'Typ wyszukiwarki',
    'LBL_FTS_HELP'      => 'Aby włączyć wyszukiwanie pełnotekstowe, wprowadź nazwę hosta i port serwera, na którym znajduje się wyszukiwarka. Aplikacja Sugar oferuje wbudowane wsparcie wyszukiwarek elasticsearch.',
    'LBL_FTS_REQUIRED'    => 'Elastyczne wyszukiwanie jest wymagane.',
    'LBL_FTS_CONN_ERROR'    => 'Połączenie z serwerem wyszukiwarki pełnotekstowej nie powiodło się. Sprawdź ustawienia.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Brak dostępnej wersji serwera wyszukiwarki pełnotekstowej. Sprawdź ustawienia.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Wykryto nieobsługiwaną wersję serwera Elasticsearch. Użyj wersji: %s',

    'LBL_PATCHES_TITLE'     => 'Zainstaluj najnowsze poprawki',
    'LBL_MODULE_TITLE'      => 'Zainstaluj pakiety językowe',
    'LBL_PATCH_1'           => 'Jeśli chcesz pominąć ten krok, kliknij Dalej.',
    'LBL_PATCH_TITLE'       => 'Poprawki systemu',
    'LBL_PATCH_READY'       => 'Następujące poprawki są gotowe do instalacji:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "SugarCRM wykorzystuje sesje PHP do przechowywania ważnych informacji podczas łączenia się z tym serwerem sieciowym. Twoja instalacja PHP nie ma poprawnie skonfigurowanych informacji o sesji.
<br><br>Powszechnym błędem konfiguracji jest to, że dyrektywa <b>session.save_path</b> nie wskazuje na właściwy katalog. <br>
<br> Popraw <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>konfigurację PHP</a> w pliku php.ini znajdującym się poniżej.",
	'LBL_SESSION_ERR_TITLE'				=> 'Błąd konfiguracji sesji PHP',
	'LBL_SYSTEM_NAME'=>'Nazwa systemu',
    'LBL_COLLATION' => 'Ustawienia sortowania',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Określ nazwę systemu dla tej instancji Sugar.',
	'LBL_PATCH_UPLOAD' => 'Wybierz plik poprawki z lokalnego komputera',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'Tryb zgodności z poprzednimi wersjami PHP jest włączony. Wyłącz tryb zend.ze1_compatibility_mode, aby przejść dalej',

    'advanced_password_new_account_email' => array(
        'subject' => 'Informacje o nowym koncie',
        'description' => 'Ten szablon jest używany, gdy administrator systemu wysyła nowe hasło do użytkownika.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Twoja nazwa użytkownika i tymczasowe hasło:</p><p>Nazwa użytkownika: $contact_user_user_name </p><p>Hasło: $contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url</a></p><br><p>Po zalogowaniu przy użyciu powyższego hasła może być wymagana zmiana hasła na Twoje własne.</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Twoja nazwa użytkownika i tymczasowe hasło:
Nazwa użytkownika: $contact_user_user_name
Hasło: $contact_user_user_hash

$config_site_url

Po zalogowaniu przy użyciu powyższego hasła może być wymagana zmiana hasła na Twoje własne.',
        'name' => 'Hasło e-mail generowane przez system',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Resetuj hasło do swojego konta',
        'description' => "Ten szablon jest używany do wysłania użytkownikowi linku do zresetowania hasła do konta użytkownika.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Dnia $contact_user_pwd_last_changed wysłano prośbę o możliwość zresetowania hasła do konta. </p><p>Kliknij link poniżej, aby zresetować hasło:</p><p> <a href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Dnia $contact_user_pwd_last_changed wysłano prośbę o możliwość zresetowania hasła do konta.

Kliknij link poniżej, aby zresetować hasło:

$contact_user_link_guid',
        'name' => 'Nie pamiętam hasła e-mail',
        ),
);
