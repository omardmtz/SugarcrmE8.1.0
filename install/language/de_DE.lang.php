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
	'LBL_BASIC_SEARCH'					=> 'Einfache Suche',
	'LBL_ADVANCED_SEARCH'				=> 'Erweiterte Suche',
	'LBL_BASIC_TYPE'					=> 'Grundlegender Typ',
	'LBL_ADVANCED_TYPE'					=> 'Erweiterter Typ',
	'LBL_SYSOPTS_1'						=> 'Bitte wählen Sie aus den Systemeinstellungen unten aus.',
    'LBL_SYSOPTS_2'                     => 'Welcher Datenbanktyp soll für die zu installierende Sugar-Instanz verwendet werden?',
	'LBL_SYSOPTS_CONFIG'				=> 'Systemkonfiguration',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Geben Sie den Datenbanktyp an.',
    'LBL_SYSOPTS_DB_TITLE'              => 'Datenbanktyp',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Bitte beheben Sie die folgenden Fehler vor dem Fortfahren.',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Bitte machen Sie die folgenden Verzeichnisse schreibbar:',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Ihre DB2-Version (%s) wird nicht von SugarCRM unterstützt. Bitte eine gültige Version installieren, gemäß den Versionshinweisen.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Sie müssen einen Oracle-Client installiert und konfiguriert haben, wenn Sie "Oracle" wählen.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
	'ERR_DB_OCI8_CONNECT'				=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
	'ERR_DB_OCI8_VERSION'				=> 'Ihre Oracle-Version (%s) wird von Sugar nicht unterstützt. Bitte installieren Sie eine Version, die mit Sugar kompatibel ist. Angaben hierzu finden Sie in den Angaben bezüglich der Kompatibilität in den Versionshinweisen.',
    'LBL_DBCONFIG_ORACLE'               => 'Bitte einen Datenbanknamen angeben. Diese wird daraufhin Ihrem Benutzer zugewiesen (SID von tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Abfrage Verkaufschance',
	'LBL_Q1_DESC'						=> 'Verkaufschancen nach Typ',
	'LBL_Q2_DESC'						=> 'Verkaufschancen nach Firma',
	'LBL_R1'							=> '6 Monate-Pipeline-Bericht',
	'LBL_R1_DESC'						=> 'Verkaufschancen der nächsten 6 Monate aufgeschlüsselt nach Monat und Typ',
	'LBL_OPP'							=> 'Datensatz Verkaufschancen ',
	'LBL_OPP1_DESC'						=> 'Hier können Sie das Design der benutzerdefinierten Anfrage anpassen',
	'LBL_OPP2_DESC'						=> 'Diese Abfrage kommt unter der erste Abfrage in den Bericht',
    'ERR_DB_VERSION_FAILURE'			=> 'Die Datenbankversion kann nicht ermittelt werden.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Geben Sie den Usernamen des Sugar-Administrators an.',
	'ERR_ADMIN_PASS_BLANK'				=> 'Geben Sie ein Passwort für den Sugar-Admin-Benutzer ein. ',

    'ERR_CHECKSYS'                      => 'Während der Kompatibilitätsprüfung sind Fehler aufgetreten. Bitte beheben Sie die unten angegebenen Probleme, um eine ordnungsgemäße Installation sicherzustellen oder versuchen Sie daraufhin, Sugar erneut zu installieren.',
    'ERR_CHECKSYS_CALL_TIME'            => 'Allow Call Time Pass Reference ist aktiviert (sollte in php.ini deaktiviert sein)',

	'ERR_CHECKSYS_CURL'					=> 'Nicht gefunden: Der Sugar-Zeitplaner wird mit limitierter Funktionalität ausgeführt. Der E-Mail-Archivierungsdienst wird nicht ausgeführt.',
    'ERR_CHECKSYS_IMAP'					=> 'Eingebende E-Mails und Kampangnen benötigen die IMAP-Bibliotheken. Die beiden genannten Module können sonst nicht funktionieren.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'Wenn Sie einen MS SQK-Server benutzen, kann Magic Quotes GPC nicht aktiviert werden.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Warnung:',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> '(Setzen Sie es auf',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M oder höher in Ihrer php.ini-Datei)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Mindest-Version 4.1.2 - Gefunden:',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Sitzungsvariablen können nicht gelesen & geschrieben werden. Kann die Installation nicht fortsetzen.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Kein gültiges Verzeichnis',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Warnung: Keine Schreibrechte',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Ihre PHP-Version wird von Sugar nicht unterstützt. Sie müssen eine kompatible Version installieren. Bitte überprüfen Sie dazu die Kompatibilitätsmatrix in den Versionshinweisen. Ihre Version ist ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Ihre IIS-Version wird von Sugar nicht unterstützt. Bitte installieren Sie eine kompatible Version gem. der Übersicht in den Versionshinweisen. Ihre Version ist ',
	'ERR_CHECKSYS_FASTCGI'              => 'Sie benutzen kein FastCGI Handler Mapping für PHP. Bitte installieren oder konfigurieren Sie eine kompatible Version gem. der Übersicht in den Versionshinweisen. Mehr Details finden Sie unter <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a> ',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'Für optimale Leistung oder Ergebnisse mit IIS/FastCGI sapi sollte fastcgi.logging in der php.ini auf 0 gesetzt sein.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Nicht unterstützte PHP-Version installiert: (Ver.',
    'LBL_DB_UNAVAILABLE'                => 'Die Datenbank ist nicht verfügbar',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Keine Datenbankunterstützung gefunden. Bitte stellen Sie sicher, dass Sie über die nötigen Treiber für die folgenden unterstützten Datenbanktypen verfügen: MySQL, MS SQLServer, Oracle oder DB2. Möglicherweise müssen Sie die entsprechende Erweiterung, je nach PHP-Version, in der php.ini-Datei auskommentieren oder mit der richtigen Binärdatei neu kompilieren. In Ihrem PHP-Handbuch finden Sie mehr Informationen zur Aktivierung der Datenbankunterstützung.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Die Funktionen verbunden mit XML Parser-Bibliotheken, die von SugarCRM gebraucht werden, wurden nicht gefunden. Sie können versuchen, die Kommentare in php.ini zu löschen, oder mit der richtigen Binary-Datei wieder zu kompilieren, abhängig von Ihrer PHP-Version. Nähere Informationen hierzu finden Sie in Ihrem PHP-Handbuch.',
    'LBL_CHECKSYS_CSPRNG' => 'Zufallszahlengenerator',
    'ERR_CHECKSYS_MBSTRING'             => 'Die Multibyte Strings PHP-Erweiterung (mbstring) scheint zu fehlen. <br/><br/>Dieses Modul ist normalerweise nicht aktiviert und muss mit --enable-mbstring aktiviert werden. In Ihrem PHP-Handbuch finden Sie mehr Informationen hierzu.',
    'ERR_CHECKSYS_MCRYPT'               => "Mcrypt module isn't loaded. Please refer to your PHP Manual for more information on how to load mcrypt module.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'Die Einstellung session.save_path in Ihrer PHP-Konfigurationsdatei (php.ini) ist nicht konfiguriert oder der dort angegebene Ordner existiert nicht. Bitte konfigurieren Sie die Einstellung und stellen sicher, dass der angegebene Ordner existiert.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Die Einstellung session.save_path in Ihrer PHP-Konfigurationsdatei (php.ini) verweist auf einen Ordner, der nicht beschreibbar ist. Bitte machen Sie den angegebenen Ordner beschreibbar. <br>Je nach Betriebssystem ändern Sie die Berechtigung mit chmod 766 oder durch Rechtsklick auf den Ordner.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Das Konfigurationsdatei existiert zwar, ist aber nicht schreibbar. Bitte machen Sie die Datei schreibbar. Je nach Betriebssystem ändern Sie die Berechtigung mit chmod 766 oder durch Rechtsklick auf die Datei.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Die Konfigurationsdatei config_override. php existiert zwar, ist nicht beschreibbar. Je nach Betriebssystem ändern Sie die Berechtigung mit chmod 766 oder durch Rechtsklick auf die Datei.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Das benutzerdefinierte Verzeichnis existiert zwar, ist aber nicht beschreibbar. Bitte machen Sie den angegebenen Ordner schreibbar. Je nach Betriebssystem ändern Sie die Berechtigung mit chmod 766 oder durch Rechtsklick auf den Ordner. Führen Sie die entsprechenden Schritte durch, um die Datei schreibbar zu machen.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "Die unten aufgeführten Dateien oder Verzeichnisse sind nicht beschreibbar und können nicht erstellt werden. Bitte machen Sie den angegebenen Ordner bzw. die Dateien. Je nach Betriebssystem ändern Sie die Berechtigung mit chmod 755 oder durch Rechtsklick auf den Ordner, Aufhebung der Markierung \"schreibgeschützt\" und Anwenden auf alle Unterordner.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Safe Mode ist aktiviert (u.U. in php.ini deaktivieren)',
    'ERR_CHECKSYS_ZLIB'					=> 'Nicht gefunden: SugarCRM erfährt enorme Leistungssteigerungen durch zlib-Kompression.',
    'ERR_CHECKSYS_ZIP'					=> 'ZIP-Support nicht gefunden. SugarCRM benötigt ZIP, um Dateien zu komprimieren.',
    'ERR_CHECKSYS_BCMATH'				=> 'BCMATH-Support nicht gefunden: SugarCRM benötigt BCMATH-Support für das Modul "Arbitrary Precision Math".',
    'ERR_CHECKSYS_HTACCESS'             => 'Test auf .htaccess rewrites fehlgeschlagen. Dies bedeutet normalerweise, dass das Sugar-Verzeichnis nicht mit AllowOverride angelegt wurde.',
    'ERR_CHECKSYS_CSPRNG' => 'CSPRNG-Ausnahme',
	'ERR_DB_ADMIN'						=> 'Der Datenbank-Admin-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. (Fehler: ',
    'ERR_DB_ADMIN_MSSQL'                => 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie einen gültigen Benutzernamen / Passwort ein.',
	'ERR_DB_EXISTS_NOT'					=> 'Die angegebene Datenbank existiert nicht.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'Die Datenbank existiert bereits mit Konfigurationsdaten. Um eine Installation mit der gewünschten Datenbank durchzuführen, starten Sie die Installation neu und wählen Sie: "Verwerfen und Neuerstellen existierender SugarCRM Tabellen?" Für das Upgrade benutzen Sie den Upgrade-Assistent im Admin-Bereich. Bitte lesen Sie die Dokumentation zum Update <a href="http://www.sugarforge.org/content/downloads/" target="_new">hier</a>.',
	'ERR_DB_EXISTS'						=> 'Eine Datenbank mit diesem Namen existiert bereits - es kann keine zweite mit dem gleichen Namen erstellt werden.',
    'ERR_DB_EXISTS_PROCEED'             => 'Der Datenbankname existiert bereits. Sie können<br>1. die Schaltfläche "Zurück" klicken und einen neuen Namen angeben oder <br>2. die Schaltfläche "Weiter" klicken und fortfahren. <strong>Dadurch werden alle Tabellen und Daten gelöscht.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Der Hostname darf nicht leer sein.',
	'ERR_DB_INVALID'					=> 'Ungültiger Datenbanktyp ausgewählt',
	'ERR_DB_LOGIN_FAILURE'				=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Der Datenbank-Benutzername und/oder das Passwort ist falsch - es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte geben Sie gültige Werte für Host, Benutzernamen und Passwort ein',
	'ERR_DB_MYSQL_VERSION'				=> 'Ihre MySQL Version (%s) wird nicht von SugarCRM unterstützt. Bitte eine gültige Version installieren, gemäß den Angaben zur Kompatibilität in den Versionshinweisen.',
	'ERR_DB_NAME'						=> 'Der Name der Datenbank darf nicht leer sein.',
	'ERR_DB_NAME2'						=> "Der Datenbankname darf keines der Zeichen '\\', '/', oder '.' enthalten",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Der Datenbankname darf keines der Zeichen '\\', '/', oder '.' enthalten",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Der Datenbankname kann nicht mit einer Zahl, '#' oder '@' beginnen und darf keine Leerzeichen, '\"', \"'\", '*', '/', '\\', '?', ':', '<', '>', '&', '!', oder '-' enthalten",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Der Datenbankname darf nur alphanumerische Zeichen und die Symbole '#', '_', '-', ':', '.', '/' oder '$' enthalten",
	'ERR_DB_PASSWORD'					=> 'Die Passwörter stimmen nicht überein. Bitte geben Sie dasselbe Passwort in beide Felder ein.',
	'ERR_DB_PRIV_USER'					=> 'Bitte geben Sie einen Benutzernamen für den Datenbankadministrator an. Dieser wird für das erstmalige Verbinden zur Datenbank benötigt.',
	'ERR_DB_USER_EXISTS'				=> 'Der Benutzername für den Sugar-Datenbankbenutzer existiert bereits -- es kann keine zweiter mit dem gleichen Namen erstellt werden. Bitte geben Sie einen neuen Benutzernamen ein.',
	'ERR_DB_USER'						=> 'Einen Benutzernamen für den Sugar-Datenbank-Administrator eingeben.',
	'ERR_DBCONF_VALIDATION'				=> 'Bitte beheben Sie die folgenden Fehler vor dem Fortfahren.',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'Die Passwörter für die Sugar-Datenbank stimmen nicht überein. Bitte geben Sie dieselben Passwörter in die beiden Felder ein.',
	'ERR_ERROR_GENERAL'					=> 'Die folgenden Fehler sind aufgetreten:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Die Datei kann nicht gelöscht werden: ',
	'ERR_LANG_MISSING_FILE'				=> 'Die Datei kann nicht gefunden werden: ',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Kein Sprachpaket in include/langauge gefunden: ',
	'ERR_LANG_UPLOAD_1'					=> 'Beim Upload ist ein Problem aufgetreten. Bitte nochmals versuchen.',
	'ERR_LANG_UPLOAD_2'					=> 'Sprachpakete müssen ZIP-Archive sein.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP konnte die tempor äre Datei nicht in das Upgrade-Verzeichnis verschieben.',
	'ERR_LICENSE_MISSING'				=> 'Pflichtfelder fehlen',
	'ERR_LICENSE_NOT_FOUND'				=> 'Lizenzdatei nicht gefunden!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Das angegebene Protokoll-Verzeichnis ist kein gültiges Verzeichnis.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Das angegebene Protokoll-Verzeichnis ist nicht beschreibbar.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Das Protokoll-Verzeichnis muss angegeben werden, falls Sie ein eigenes festlegen möchten.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Script konnte nicht direkt verarbeitet werden.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Hochkomma kann nicht verwendet werden als',
	'ERR_PASSWORD_MISMATCH'				=> 'Die Passwörter stimmen nicht überein. Bitte geben Sie dasselbe Passwort in beide Felder ein.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Die Datei <span class=stop>config.php </span> ist nicht beschreibbar.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Sie können mit der Installation fortfahren, indem Sie die config.php manuell erstellen und untenstehende Information einfügen. Allerdings <strong>müssen</strong> Sie die config.php erstellen, bevor Sie weitermachen können.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Haben Sie die config.php-Datei erstellt?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Warnung: Es konnte nicht in die config.php Datei geschrieben werden. Stellen Sie sicher, dass diese existiert.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Die Datei',
	'ERR_PERFORM_HTACCESS_2'			=> 'ist nicht beschreibbar.',
	'ERR_PERFORM_HTACCESS_3'			=> 'Falls Sie die Protokolldatei vor dem Zugriff via Browser schützen möchten, erstellen Sie eine Datei ".htaccess" im Protokollverzeichnis, mit der Zeile:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>Die Internetverbindung wurde nicht erkannt.</b>Falls Sie eine Verbindung haben, besuchen Sie bitte <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a>, um sich bei SugarCRM zu registrieren. Indem Sie uns erzählen, wie Sie planen, Sugar in Ihrer Firma einzusetzen, können wir sicherstellen, dass wir Ihnen immer die richtige Anwendung für Ihre Bedürfnisse liefern.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Das angegebene Sitzungsverzeichnis ist kein gültiges Verzeichnis.',
	'ERR_SESSION_DIRECTORY'				=> 'Das angegebene Sitzungsverzeichnis ist nicht beschreibbar.',
	'ERR_SESSION_PATH'					=> 'Der Sitzungspfad wird benötigt, wenn Sie Ihren eigenen verwenden möchten.',
	'ERR_SI_NO_CONFIG'					=> 'Entweder haben Sie config_si.php nicht im Root-Verzeichnis inkludiert oder Sie haben $sugar_config_si nicht in der Datei config.php definiert',
	'ERR_SITE_GUID'						=> 'Die Anwendungs-ID wird benötigt, wenn Sie eine eigene verwenden möchten.',
    'ERROR_SPRITE_SUPPORT'              => "Die GD-Bibliothek kann nicht ermittelt werden, daher kann die Funktion CSS Sprite nicht verwendet werden.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Warnung: Ihre PHP-Einstellungen sollten geändert werden, um Datei-Uploads von mindestend 6 MB zuzulassen.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Upload-Dateigröße',
	'ERR_URL_BLANK'						=> 'Geben Sie die Basis-URL für die Sugar-Instanz an.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Installations-Datensatz nicht gefunden für',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Die hochgeladene Datei ist nicht kompatibel mit dieser Sugar-Edition (Professional, Enterprise oder Ultimate): ',
	'ERROR_LICENSE_EXPIRED'				=> "Fehler: Ihre Lizenz ist vor",
	'ERROR_LICENSE_EXPIRED2'			=> " Tag(en). Bitte gehen Sie in die <a href='index.php?action=LicenseSettings&module=Administration'>Lizenzverwaltung</a> im Admin-Bereich, um Ihren neuen Lizenzschlüssel einzugeben. Wenn Sie nicht innerhalb von 30 Tagen nach Ablauf einen neuen Lizenzschlüssel eingeben, können Sie sich nicht mehr bei dieser Anwendung anmelden.",
	'ERROR_MANIFEST_TYPE'				=> 'Die Manifest-Datei muss den Typ der Anwendung spezifizieren.',
	'ERROR_PACKAGE_TYPE'				=> 'Die Manifest-Datei enthält einen unbekannten Anwendungstyp',
	'ERROR_VALIDATION_EXPIRED'			=> "Fehler: Ihr Validierungsschlüssel ist vor ",
	'ERROR_VALIDATION_EXPIRED2'			=> " Tag(en). <a href='index.php?action=LicenseSettings&module=Administration'>Lizenzverwaltung</a> im Admin-Bereich, um Ihren neuen Validierungsschlüssel einzugeben. Wenn Sie nicht innerhalb von 30 Tagen nach Ablauf einen neuen Validierungsschlüssel eingeben, können Sie sich nicht mehr bei dieser Anwendung anmelden.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Die geladene Datei ist nicht mit dieser Sugar-Version kompatibel: ',

	'LBL_BACK'							=> 'Zurück',
    'LBL_CANCEL'                        => 'Abbrechen',
    'LBL_ACCEPT'                        => 'Ich akzeptiere',
	'LBL_CHECKSYS_1'					=> 'Damit Ihre SugarCRM-Installation korrekt funktioniert, müssen alle Systemchecks unten grün sein. Wenn irgendeiner rot ist, unternehmen Sie bitte geeignete Schritte um das Problem zu beheben.<BR><BR>Besuchen Sie bitte das <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a> für Hilfe zu diesen Systemchecks.',
	'LBL_CHECKSYS_CACHE'				=> 'Beschreibbarer Cache Unterverzeichnisse',
    'LBL_DROP_DB_CONFIRM'               => 'Die angegebene Datenbak existiert bereits. <br>Sie können<br>1. die Schaltfläche "Zurück" klicken und einen neuen Datenbanknamen angeben oder <br>2. die Schaltfläche "Weiter" klicken und fortfahren. <strong>Dadurch werden alle bestehenden Tabellen und Daten gelöscht.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'PHP Allow Call Time Pass Reference ist deaktiviert',
    'LBL_CHECKSYS_COMPONENT'			=> 'Komponente',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Optionale Komponenten',
	'LBL_CHECKSYS_CONFIG'				=> 'Beschreibbare SugarCRM-Konfigurationsdatei (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Beschreibbare SugarCRM-Konfigurationsdatei (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'cURL-Modul',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Sitzungs-Speicherpfad-Einstellung',
	'LBL_CHECKSYS_CUSTOM'				=> 'Beschreibbares benutzerdefiniertes Verzeichnis',
	'LBL_CHECKSYS_DATA'					=> 'Beschreibbare Daten Unterverzeichnisse',
	'LBL_CHECKSYS_IMAP'					=> 'IMAP-Modul',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'MB Strings-Modul',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt Module',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (Kein Limit)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (unbeschränkt)',
	'LBL_CHECKSYS_MEM'					=> 'PHP-Speicherlimit >=',
	'LBL_CHECKSYS_MODULE'				=> 'Beschreibbare Module Unterverzeichnisse und Dateien',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'MySQL-Version',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Nicht verfügbar',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> 'Speicherort Ihrer php-Konfigurationsdatei (php.ini):',
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (Ver.',
	'LBL_CHECKSYS_PHPVER'				=> 'PHP-Version',
    'LBL_CHECKSYS_IISVER'               => 'IIS-Version',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Überprüfung wiederholen',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'PHP Safe Mode deaktiviert',
	'LBL_CHECKSYS_SESSION'				=> 'Beschreibbarer Sitzungs-Speicherpfad (',
	'LBL_CHECKSYS_STATUS'				=> 'Status',
	'LBL_CHECKSYS_TITLE'				=> 'Systemcheck-Akzeptanz',
	'LBL_CHECKSYS_VER'					=> 'Gefunden: (Ver. ',
	'LBL_CHECKSYS_XML'					=> 'XML-Parsing',
	'LBL_CHECKSYS_ZLIB'					=> 'ZLIB-Kompressionsmodul',
	'LBL_CHECKSYS_ZIP'					=> 'ZIP-Handling-Modul',
    'LBL_CHECKSYS_BCMATH'				=> 'Arbitrary Precision Math-Modul',
    'LBL_CHECKSYS_HTACCESS'				=> 'AllowOverride-Einstellung für .htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'Bitte reparieren Sie die folgenden Dateien oder Verzeichnisse, bevor Sie fortfahren:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Bitte reparieren Sie die folgenden Modulverzeichnisse und die darin befindlichen Dateien, bevor Sie fortfahren:',
    'LBL_CHECKSYS_UPLOAD'               => 'Upload-Verzeichnis beschreibbar',
    'LBL_CLOSE'							=> 'Schließen',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'erstellt',
	'LBL_CONFIRM_DB_TYPE'				=> 'Datenbanktyp',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Bitte bestätigen Sie die Einstellungen unten. Um diese zu ändern, klicken Sie auf "Zurück". Andernfalls klicken Sie auf "Weiter", um die Installation zu starten.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Lizenzinformation',
	'LBL_CONFIRM_NOT'					=> 'nicht',
	'LBL_CONFIRM_TITLE'					=> 'Einstellungen bestätigen',
	'LBL_CONFIRM_WILL'					=> 'wird',
	'LBL_DBCONF_CREATE_DB'				=> 'Datenbank erstellen',
	'LBL_DBCONF_CREATE_USER'			=> 'Benutzer anlegen [Alt+N]',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Vorsicht: Wenn dieses Kästchen angekreuzt ist<br>, werden alle Sugar-Daten gelöscht.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Existierende Sugar-Tabellen löschen und neu erstellen?',
    'LBL_DBCONF_DB_DROP'                => 'Tabellen löschen',
    'LBL_DBCONF_DB_NAME'				=> 'Name der Datenbank',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Sugar-Datenbank-Benutzerpasswort',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Suga-Datenbank-Benutzerpasswort bestätigen',
	'LBL_DBCONF_DB_USER'				=> 'Sugar-Datenbank-Benutzername',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Sugar-Datenbank-Benutzername',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Benutzername des Datenbank-Administrators',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Passwort des Datenbank-Administrators',
	'LBL_DBCONF_DEMO_DATA'				=> 'Datenbank mit Demodaten füllen?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Demodaten',
	'LBL_DBCONF_HOST_NAME'				=> 'Hostname',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Host-Instanz',
	'LBL_DBCONF_HOST_PORT'				=> 'Port',
    'LBL_DBCONF_SSL_ENABLED'            => 'SSL-Verbindung aktivieren',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Bitte geben Sie Ihre Datenbank-Konfigurationsinformation unten ein. Falls Sie unsicher sind, was Sie eingeben sollen, lassen Sie die Standardeinstellungen stehen.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Multi-byte-Text in den Demodaten verwenden?',
    'LBL_DBCONFIG_MSG2'                 => 'Name des Webservers oder der Maschine (Hosts), auf dem sich die Datenbank befindet (z. B. localhost or www.mydomain.com):',
    'LBL_DBCONFIG_MSG3'                 => 'Name der Datenbank für die Daten der zu installierenden Sugar-Instanz:',
    'LBL_DBCONFIG_B_MSG1'               => 'Benutzername und Passwort eines Datenbank-Administrators, welcher Datenbank-Tabellen und Benutzer erstellen und in die Datenbank schreiben kann, sind notwendig, um die Sugar-Datenbank einzurichten.',
    'LBL_DBCONFIG_SECURITY'             => 'Aus Sicherheitsgründen können Sie einen exklusiven Datenbank-Benutzer festlegen, um mit der Sugar-Datenbank zu kommunizieren. Dieser Benutzer muss in der Datenbank dieser Instanz lesen, schreiben und aktualisieren können. Es kann sich dabei entweder um den Datenbank-Administrator handeln, wie oben angegeben, oder Sie geben Informationen zu einem neuen oder bereits existierenden anderen Benutzer an.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Machen Sie es für mich',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Bestehenden Benutzer angeben',
    'LBL_DBCONFIG_CREATE_DD'            => 'Den zu erstellenden Benutzer definieren',
    'LBL_DBCONFIG_SAME_DD'              => 'Gleich wie Admin-Benutzer',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Volltextsuche',
    'LBL_FTS_INSTALLED'                 => 'Installiert',
    'LBL_FTS_INSTALLED_ERR1'            => 'Die Volltextsuche-Funktionalität ist nicht installiert.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Sie können mit der Installation fortfahren; allerdings wird die Volltextsuche nicht funktionieren. Bitte sehen Sie weitere Informationen im Installationshandbuch oder kontaktieren Sie Ihren Administrator.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Passwort des privilegierten Benutzers',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Ist obiges Datenbank-Konto ein privilegierter Benutzer?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'Dieser privilegierte Datenbank-Benutzer muss die Berechtigung haben, Datenbank, Tabellen und Benutzer anzulegen und zu löschen. Dieser Benutzer wird nur dazu verwendet, diese Aufgaben während der Installation durchzuführen. Sie können den gleichen Datenbank-Benutzer wie oben verwenden, falls dieser über ausreichende Berechtigungen verfügt.',
	'LBL_DBCONF_PRIV_USER'				=> 'Privilegierter Datenbank-Benutzername',
	'LBL_DBCONF_TITLE'					=> 'Datenbank-Konfiguration',
    'LBL_DBCONF_TITLE_NAME'             => 'Datenbanknamen eingeben',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Datenbank-Benutzer-Information eingeben',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'Nachdem diese Änderung durchgeführt wurde, können Sie auf "Start" klicken, um mit der Installation zu beginnen. <i>Wenn die Installation beendet ist, sollten Sie den Wert von "installer_locked" auf "true" setzen.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'Die Installationsroutine ist bereits einmal gelaufen. Aus Sicherheitsgründen kann sie kein zweites Mal aufgerufen werden. Falls Sie sicher sind, dass Sie den Installer ein zweites Mal ausführen möchten, müssen Sie die Variable "installer_locked" in der Datei "config.php" auf "false" setzen (oder hinzufügen). Die Zeile sollte so aussehen:',
	'LBL_DISABLED_HELP_1'				=> 'Für Hilfe zur Installation besuchen Sie die SugarCRM-',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'Support-Forums',
	'LBL_DISABLED_TITLE_2'				=> 'SugarCRM-Installation wurde deaktiviert',
	'LBL_DISABLED_TITLE'				=> 'SugarCRM-Installation ist deaktiviert',
	'LBL_EMAIL_CHARSET_DESC'			=> 'In Ihrer Region am häufigsten verwendeter Zeichensatz',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Ausgehende E-Mail-Einstellungen',
    'LBL_EMAIL_CHARSET_CONF'            => 'Zeichensatz für ausgehende E-Mails',
	'LBL_HELP'							=> 'Hilfe',
    'LBL_INSTALL'                       => 'Installieren',
    'LBL_INSTALL_TYPE_TITLE'            => 'Installationsoptionen',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Wählen Sie einen Installationstyp aus',
    'LBL_INSTALL_TYPE_TYPICAL'          => '<b>Typische Installation</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>Benutzerdefinierte Installation</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Der Schlüssel ist für die allgemeine Funktion der Anwendung notwendig, aber nicht für die Installation. Sie müssen daher diesen Schlüssel nicht jetzt eingeben, können dies aber später tun.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Benötigt nur wenige Angaben für die Installation. Wird für neue Benutzer empfohlen.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Ermöglicht die Eingabe von erweiterten Optionen während der Installation. Die meisten dieser Einstellungen sind allerdings auch innerhalb des Programms im Admin-Bereich verfügbar. Empfohlen für erfahrene Benutzer.',
	'LBL_LANG_1'						=> 'Um eine andere Sprache als Englisch zu verwenden, können Sie jetzt ein Sprachpaket hochladen und installieren. Sie können dies aber auch innerhalb der Anwendung tun (empfohlen). Wenn Sie diesen Schritt überspringen möchten, klicken Sie auf "Weiter".',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Installieren',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Entfernen',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Deinstallieren',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Hochladen',
	'LBL_LANG_NO_PACKS'					=> 'nichts',
	'LBL_LANG_PACK_INSTALLED'			=> 'Die folgenden Sprachpakete wurden installiert:',
	'LBL_LANG_PACK_READY'				=> 'Die folgenden Sprachpakete sind bereit für die Installation:',
	'LBL_LANG_SUCCESS'					=> 'Das Sprachpaket wurde erfolgreich hochgeladen.',
	'LBL_LANG_TITLE'			   		=> 'Sprachpaket',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'Ermöglicht die Auswahl von erweiterten Optionen während der Installation. Die meisten dieser Einstellungen sind allerdings auch innerhalb des Programms im Admin-Bereich verfügbar. Empfohlen für erfahrene Benutzer.',
	'LBL_LANG_UPLOAD'					=> 'Sprachpaket hochladen',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Lizenz-Akzeptanz',
    'LBL_LICENSE_CHECKING'              => 'Sytemkompatabilität wird geprüft',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Systemumgebung wird geprüft',
    'LBL_LICENSE_CHKDB_HEADER'          => 'DB, FTS-Anmeldedaten werden überprüft.',
    'LBL_LICENSE_CHECK_PASSED'          => 'Das System hat den Kompatibilitätstest bestanden.',
    'LBL_LICENSE_REDIRECT'              => 'Weiterleiten in ',
	'LBL_LICENSE_DIRECTIONS'			=> 'Falls Sie Ihre Lizenzinformationen zur Hand haben, geben Sie sie bitte in den folgenden Feldern ein.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Download-Schlüssel eingeben',
	'LBL_LICENSE_EXPIRY'				=> 'Ablaufdatum',
	'LBL_LICENSE_I_ACCEPT'				=> 'Ich akzeptiere',
	'LBL_LICENSE_NUM_USERS'				=> 'Anzahl der Benutzer',
	'LBL_LICENSE_PRINTABLE'				=> 'Druckansicht',
    'LBL_PRINT_SUMM'                    => 'Zusammenfassung drucken',
	'LBL_LICENSE_TITLE_2'				=> 'SugarCRM-Lizenz',
	'LBL_LICENSE_TITLE'					=> 'Lizenzinformation',
	'LBL_LICENSE_USERS'					=> 'Lizenzierte Benutzer',

	'LBL_LOCALE_CURRENCY'				=> 'Währungseinstellungen',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Standardwährung',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Währungssymbol',
	'LBL_LOCALE_CURR_ISO'				=> 'Währungssymbol (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> '1000er-Trennzeichen',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Dezimal-Trennzeichen',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Beispiel',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Dezimalstellen',
	'LBL_LOCALE_DATEF'					=> 'Standard-Datumsformat',
	'LBL_LOCALE_DESC'					=> 'Die angegebenen Regionseinstellungen werden in der Sugar-Instanz global verwendet.',
	'LBL_LOCALE_EXPORT'					=> 'Import/Export Zeichensatz<br> <i>(E-Mail, .csv, vCard, PDF, Datenimport)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Export(.csv)-Trennzeichen',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Import/Export-Einstellungen',
	'LBL_LOCALE_LANG'					=> 'Standardsprache',
	'LBL_LOCALE_NAMEF'					=> 'Standard-Namensformat',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = Anrede<br />f = Vorname<br />l = Nachname',
	'LBL_LOCALE_NAME_FIRST'				=> 'Hans',
	'LBL_LOCALE_NAME_LAST'				=> 'Muster',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr.',
	'LBL_LOCALE_TIMEF'					=> 'Standard-Zeitformat',
	'LBL_LOCALE_TITLE'					=> 'Lokale Einstellungen',
    'LBL_CUSTOMIZE_LOCALE'              => 'Lokale Einstellungen anpassen',
	'LBL_LOCALE_UI'						=> 'Benutzeroberfläche',

	'LBL_ML_ACTION'						=> 'Aktion',
	'LBL_ML_DESCRIPTION'				=> 'Beschriftung',
	'LBL_ML_INSTALLED'					=> 'Installiert am',
	'LBL_ML_NAME'						=> 'Name',
	'LBL_ML_PUBLISHED'					=> 'Veröffentlicht am',
	'LBL_ML_TYPE'						=> 'Typ',
	'LBL_ML_UNINSTALLABLE'				=> 'Deinstallierbar',
	'LBL_ML_VERSION'					=> 'Version',
	'LBL_MSSQL'							=> 'SQL-Server',
	'LBL_MSSQL_SQLSRV'				    => 'SQL-Server (Microsoft SQL Server-Treiber für PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (mysqli-Erweiterung)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Weiter',
	'LBL_NO'							=> 'Nein',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Site-Administrator-Passwort wird konfiguriert',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'Audit-Tabelle /',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Die Sugar-Konfigurationsdatei wird erstellt',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Die Datenbank wird erstellt</b> ',
	'LBL_PERFORM_CREATE_DB_2'			=> '<b>auf</b>',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Datenbank-Benutzername und Passwort werden erstellt...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Standard-Sugar-Daten werden erstellt',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Datenbank-Benutzername und Passwort für localhost werden erstellt...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Sugar-Beziehungstabellen werden erstellt',
	'LBL_PERFORM_CREATING'				=> 'wird erstellt / ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Standardberichte erstellen',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Standard-Zeitplaner-Aufgaben werden erstellt',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Standard-Einstellungen werden eingefügt',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Standard-Benutzer werden erstellt',
	'LBL_PERFORM_DEMO_DATA'				=> 'Die Datenbank wird mit Demo-Daten gefüllt (dies kann einige Zeit dauern)',
	'LBL_PERFORM_DONE'					=> 'fertig<br>',
	'LBL_PERFORM_DROPPING'				=> 'wird gelöscht /',
	'LBL_PERFORM_FINISH'				=> 'Fertigstellen',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Lizenzinformationen aktualisieren',
	'LBL_PERFORM_OUTRO_1'				=> 'Die Installation von Sugar',
	'LBL_PERFORM_OUTRO_2'				=> ' wurde erfolgreich abgeschlossen!',
	'LBL_PERFORM_OUTRO_3'				=> 'Gesamtzeit:',
	'LBL_PERFORM_OUTRO_4'				=> 'Sekunden.',
	'LBL_PERFORM_OUTRO_5'				=> 'Ungefährer Speicherverbrauch:',
	'LBL_PERFORM_OUTRO_6'				=> 'Bytes.',
	'LBL_PERFORM_OUTRO_7'				=> 'Ihr System ist jetzt fertig installiert und konfiguriert.',
	'LBL_PERFORM_REL_META'				=> 'Meta-Beziehung... ',
	'LBL_PERFORM_SUCCESS'				=> 'Erfolgreich!',
	'LBL_PERFORM_TABLES'				=> 'Sugar-Anwendungstabellen, Audit-Tabellen und Beziehungs-Metadaten werden erstellt',
	'LBL_PERFORM_TITLE'					=> 'Setup durchführen',
	'LBL_PRINT'							=> 'Drucken',
	'LBL_REG_CONF_1'					=> 'Bitte füllen Sie das kurze Formular unten aus, um Produktankündigungen, Schulungsneuigkeiten, spezielle Angebote und Einladungen von SugarCRM zu erhalten. Ihre Daten werden von uns nur zu eigenen Zwecken verwendet und niemals an Dritte weitergegeben.',
	'LBL_REG_CONF_2'					=> 'Ihr Name und Ihre E-Mail-Adresse sind die einzigen Felder, die für die Registrierung benötigt werden. Alle anderen Felder sind optional, aber sehr hilfreich. Wir werden die Informationen, die wir hier sammeln, selbstverständlich nicht an Dritte weiterleiten.',
	'LBL_REG_CONF_3'					=> 'Vielen Dank für die Registrierung. Klicken Sie auf die Schaltfläche "Fertigstellen", um sich bei SugarCRM anzumelden. Das erste Mal müssen Sie sich mit dem Benutzernamen "admin" anmelden und das Passwort von Schritt 2 eingeben.',
	'LBL_REG_TITLE'						=> 'Registrierung',
    'LBL_REG_NO_THANKS'                 => 'Nein, danke',
    'LBL_REG_SKIP_THIS_STEP'            => 'Diesen Schritt überspringen',
	'LBL_REQUIRED'						=> '* Pflichtfeld',

    'LBL_SITECFG_ADMIN_Name'            => 'Sugar-Admin-Name',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Sugar-Admin-Passwort erneut eingeben',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Vorsicht: Dies überschreibt das Admin-Passwort aus allen früheren Installationen.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Sugar-Admin-Passwort',
	'LBL_SITECFG_APP_ID'				=> 'Anwendungs-ID',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Wenn ausgewählt, müssen Sie eine Anwendungs-ID angeben, um die automatisch generierte ID zu überschreiben. Das verhindert, dass Sitzungen einer Sugar-Instanz von einer anderen Instanz verwendet werden. Falls Sie einen Cluster von Sugar-Installationen haben, müssen alle die gleiche Anwendungs-ID verwenden.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Geben Sie Ihre eigene Anwendungs-ID ein',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Wenn ausgewählt, müssen Sie ein Protokoll-Verzeichnis zum Überschreiben des Standardverzeichnisses angeben. Egal, wo sich die Protokolldatei befindet, der Zugriff zum Verzeichnis via Browser wird mit Hilfe eines .htaccess-Redirect verhindert.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Eigenes Protokoll-Verzeichnis verwenden',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Geben Sie ein sicheres Verzeichnis für die Speicherung der Sitzungsinformationen an, um zu verhindern, dass Sitzungsdaten auf einem gemeinsamen Server gefährdet werden.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Eigenes Sitzungs-Verzeichnis für Sugar verwenden',
	'LBL_SITECFG_DIRECTIONS'			=> 'Bitte geben Sie Ihre Site-Konfigurationsinformationen ein. Falls Sie unsicher sind, empfehlen wir, dass Sie die Standardwerte verwenden.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Bitte beheben Sie die folgenden Fehler, bevor Sie fortfahren:</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Protokoll-Verzeichnis',
	'LBL_SITECFG_SESSION_PATH'			=> 'Pfad zum Sitzungs-Verzeichnis<br>(muss beschreibbar sein)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Wählen Sie die Sicherheitsoptionen',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'Falls aktiviert, wird das System regelmäßig nach aktuellen Updates suchen.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Automatisch nach Updates suchen?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Sugar-Update-Konfiguration',
	'LBL_SITECFG_TITLE'					=> 'Site-Konfiguration',
    'LBL_SITECFG_TITLE2'                => 'Administrations-Benutzer identifizieren',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Site-Sicherheit',
	'LBL_SITECFG_URL'					=> 'URL der Sugar-Instanz',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Standardwerte verwenden?',
	'LBL_SITECFG_ANONSTATS'             => 'Anonyme Benutzerstatistiken senden?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'Falls aktiviert, wird das System bei jeder Suche nach Updates <b>anonyme</b> Statistiken über Ihr System an SugarCRM Inc. senden. Diese Information hilft uns, besser zu verstehen, wie die Anwendung verwendet wird, was widerum zu Produktverbesserungen führt.',
    'LBL_SITECFG_URL_MSG'               => 'Geben Sie die URL ein, die nach der Installation verwendet wird, um Ihre Sugar-Instanz aufzurufen. Diese URL wird auch als Basis für die URLs auf den Sugar-Anwendungsseiten verwendet. Die URL muss den Webserver, den Maschinennamen oder die IP-Adresse enthalten.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Geben Sie einen Namen für Ihr System ein. Dieser Name wird in der Titelleiste des Browsers angezeigt, wenn auf Sugar zugegriffen wird.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Nach der Installation benötigen Sie den Sugar-Admin-Benutzer (Benutzername = admin), um sich beim System anzumelden. Geben Sie ein Passwort für diesen Administrator-Benutzer ein. Dieses kann nach dem ersten Anmelden jederzeit geändert werden. Sie können auch einen beliebigen anderen Admin-Benutzernamen verwenden.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Kollation (Sortierung) für das System auswählen. Diese Einstellung erstellt alle Tabellen mit der ausgewählte Sprache. Falls Ihre Sprache keine besondere Einstellungen benötigt, dann bitte die Defaulteinstellungen verwenden.',
    'LBL_SPRITE_SUPPORT'                => 'Unterstützung für Sprite',
	'LBL_SYSTEM_CREDS'                  => 'Systemanmeldeinformationen',
    'LBL_SYSTEM_ENV'                    => 'Systemumgebung',
	'LBL_START'							=> 'Start',
    'LBL_SHOW_PASS'                     => 'Passwörter anzeigen',
    'LBL_HIDE_PASS'                     => 'Passwörter ausblenden',
    'LBL_HIDDEN'                        => '<i>(versteckt)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Wählen Sie Ihre Sprache</b>',
	'LBL_STEP'							=> 'Schritt',
	'LBL_TITLE_WELCOME'					=> 'Willkommen bei SugarCRM',
	'LBL_WELCOME_1'						=> 'Die Installationsroutine erstellt die SugarCRM-Datenbanktabellen und konfiguriert die Konfigurationsvariablen, die Sie zum Start benötigen. Der ganze Vorgang sollte ca. 10 Minuten dauern.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Sind Sie bereit für die Installation?',
    'REQUIRED_SYS_COMP' => 'Benötigte Systemkomponenten',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Bevor Sie beginnen, stellen Sie bitte sicher, dass Sie unterstützte Versionen folgender Systemkomponenten installiert haben:<br>
<ul>
<li> Datenbank/Datenbank-Management-System (Beispiele: MySQL, SQL Server, Oracle, DB2)</li>
<li> Web Server (Apache, IIS)</li>
<li> Elasticsearch</li>
</ul>
In den Versionshinweisen finden Sie Angaben über kompatible Systemkomponenten für die jeweils von Ihnen installierte Sugar-Version.<br>',
    'REQUIRED_SYS_CHK' => 'Anfängliche System-Überprüfung',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Wenn Sie die Installation starten, wird eine Systemüberprüfung auf dem Webserver, auf dem sich die Sugar-Dateien befinden, durchgeführt, um festzustellen, ob das System korrekt konfiguriert ist, und über alle erforderlichen Komponenten verfügt,  um die Installation erfolgreich durchzuführen. <br><br>
Das System überprüft folgendes:<br>
<ul>
<li><b>Die PHP-Version</b> &#8211; muss mit der Anwendung kompatibel sein.</li>
<li><b>Die Sitzungsvariablen</b> &#8211; müssen richtig funktionieren.</li>
<li> <b>Die MB-Strings</b> &#8211; müssen installiert und in php.ini aktivert sein</li>

<li> <b>Datenbank-Support</b> &#8211; muss für MySQL, SQL Server, Oracle oder DB2 existieren</li>

<li> <b>Config.php</b> &#8211; muss existieren und die korrekten Zugriffsrechte haben</li>
<li>Die folgende Sugar-Dateien müssen Schreibrechte haben:<ul><li><b>/custom</li>
<li>/cache</li>
<li>/modules</li>
<li>/upload</b></li></ul></li></ul>
Wenn die Überprüfung fehlschlägt, kann die Installation nicht fortgesetzt werden. Eine Fehlermeldung wird angezeigt, die erklärt, warum Ihr System die Überprüfung nicht bestanden hat.
Nehmen Sie die erforderlichen Änderungen vor, und führen Sie die System-Überprüfung daraufhin erneut durch, um mit der Installation fortzufahren.<br>',
    'REQUIRED_INSTALLTYPE' => 'Typische oder benutzerdefinierte Installation',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Wählen Sie nach der Systemüberprüfung entweder
                      die typische oder die benutzerdefinierte Installation.<br><br>
                      Sowohl für die <b>typische</b> als auch die <b>benutzerdefinierte</b> Installation benötigen Sie folgende Informationen:<br>
                      <ul>
                      <li> <b>Datenbank-Typ</b> für die mit Sugar-Daten <ul><li>kompatible Datenbank
                      Typen: MySQL, MS SQL Server, Oracle, DB2.<br><br></li></ul></li>
                      <li> <b>Name des Webservers</b> oder der Maschine (Host), auf dem bzw. der die Datenbank gespeichert ist
                      <ul><li>Dabei kann es sich um <i>localhost</i> handeln, wenn sich die Datenbank auf demselben Computer bzw. Webserver oder Maschine befindet, wie die Sugar-Dateien.<br><br></li></ul></li>
                      <li><b>Name der Datenbank</b>, in der Sie die Sugar-Daten speichern möchten</li>
                        <ul>
                          <li> Möglicherweise möchten Sie eine bestehende Datenbank verwenden. Wenn Sie den 
                          Namen einer bestehenden Datenbank eingeben, werden die Tabellen in dieser Datenbank
                          bei der Installation entfernt, wenn das Schema für die Sugar-Datenbank definiert wird.</li>
                          <li> Wenn noch keine Datenbank vorhanden ist, wird der Name für die Erstellung
                          einer neuen Datenbank für die Instanz verwendet.<br><br></li>
                        </ul>
                      <li><b>Benutzername und Passwort für Datenbank-Administrator</b> <ul><li>Der Datenbank-Administrator muss in der Lage sein, Tabellen und Benutzer zu erstellen und in die Datenbank zu schreiben.</li><li>Möglicherweise müssen Sie
                      Ihren Datenbank-Administrator kontaktieren, wenn sich die Datenbank
                      nicht auf Ihrem lokalen Computer befindet bzw. Sie nicht der Datenbank-Administrator sind.<br><br></ul></li></li>
                      <li> <b>Benutzername und Passwort für Sugar-Datenbank</b>
                      </li>
                        <ul>
                          <li> Der Benutzer kann der Datenbank-Administrator sein, oder Sie können den Namen eines anderen, bestehenden
                          Datenbank-Benutzers angeben. </li>
                          <li> Wenn Sie dazu eine neue Datenbank erstellen möchten, können Sie
                          bei der Installation einen neuen Benutzernamen und ein Passwort angeben,
                          und dieser Benutzer wird bei der Installation erstellt. </li>
                        </ul>
                    <li> <b>Elasticsearch-Host und Port</b>
                      </li>
                        <ul>
                          <li> Der Elasticsearch-Host ist jener Host, auf dem die Suchmaschine läuft. Die Standardeinstellung ist localhost, unter der Annahme, dass die Suchmaschine auf demselben Server wie Sugar läuft.</li>
                          <li> Der Elasticsearch-Port ist die Portnummer für die Verbindung von Sugar zur Suchmaschine. Die Standardeinstellung ist 9200. </li>
                        </ul>
                        </ul><p>

                      Für die <b>benutzerdefinierte</b> Einrichtung benötigen Sie folgende Informationen:<br>
                      <ul>
                      <li> <b>URL für den Zugriff auf die Sugar-Instanz</b> nach der Installation.
                      Diese URL muss den Webserver, den Maschinennamen oder die IP-Adresse enthalten.<br><br></li>
                                  <li> [Optional] <b>Pfad zum Sitzungsverzeichnis</b> Wenn Sie ein benutzerdefiniertes Sitzungsverzeichnis für
                                  die Sugar-Informationen festlegen möchten, um zu vermeiden, dass Sitzungsdaten
                                  auf gemeinsamen Servern gespeichert werden.<br><br></li>
                                  <li> [Optional] <b>Pfad zu einem benutzerdefinierten Verzeichnis für Protokolle</b> Wenn Sie das Standardverzeichnis für das Sugar-Protokoll ändern möchten.<br><br></li>
                                  <li> [Optional] <b>Anwendungs-ID</b> Wenn Sie die automatisch generierte ID
                                  ändern möchten, die verwendet wird, damit eine Sugar-Instanz nicht von anderen Instanzen verwendet wird.<br><br></li>
                                  <li><b>Zeichensatz</b> für Ihre Lokale.<br><br></li></ul>
                                  Siehe das Installationshandbuch für detailliertere Informationen.
                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Bitte lesen Sie folgende Informationen, bevor Sie mit der Installation fortfahren. Die Informationen werden Ihnen dabei helfen, zu bestimmen, ob Sie die notwendigen Voraussetzungen haben, um SugarCRM jetzt zu installieren.',


	'LBL_WELCOME_2'						=> 'Die Installationsdokumente finden Sie unter <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.  <BR><BR> Um einen SugarCRM Support Techniker zu kontaktieren, der bei der Installation hilft, loggen Sie sich bitte unter  <a target="_blank" href="http://support.sugarcrm.com">SugarCRM Support Portal</a> ein und senden einen Supportfall.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Wählen Sie Ihre Sprache</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Setup-Assistent',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Willkommen bei SugarCRM',
	'LBL_WELCOME_TITLE'					=> 'SugarCRM-Setup-Assistent',
	'LBL_WIZARD_TITLE'					=> 'Sugar-Setup-Assistent: ',
	'LBL_YES'							=> 'Ja',
    'LBL_YES_MULTI'                     => 'Ja - Multibyte',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Workflow-Aufgaben verarbeiten',
	'LBL_OOTB_REPORTS'		=> 'Berichte-Aufgaben verarbeiten',
	'LBL_OOTB_IE'			=> 'Eingehende E-Mail-Konten prüfen',
	'LBL_OOTB_BOUNCE'		=> 'Unzustellbare Kampagnen-E-Mails verarbeiten (Nacht)',
    'LBL_OOTB_CAMPAIGN'		=> 'Kampagnen-Massenmails versenden (Nacht)',
	'LBL_OOTB_PRUNE'		=> 'Datenbank am 1. des Monats säubern',
    'LBL_OOTB_TRACKER'		=> 'Tracker-Tabellen säubern',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'E-Mail-Erinnerungsbenachrichtigungen ausführen',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'tracker_sessions-Tabelle aktualisieren',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Aufgaben-Warteschlange bereinigen',


    'LBL_FTS_TABLE_TITLE'     => 'Volltext-Sucheinstellungen angeben',
    'LBL_FTS_HOST'     => 'Host',
    'LBL_FTS_PORT'     => 'Port',
    'LBL_FTS_TYPE'     => 'Suchmaschinentyp',
    'LBL_FTS_HELP'      => 'Um die Volltextsuche zu aktivieren, wählen Sie bitte den Suchmaschinentyp sowie dessen Host und Port aus. Die Unterstützung für elasticsearch ist als Standard in SugarCRM eingebaut.',
    'LBL_FTS_REQUIRED'    => 'Elastic Search ist erforderlich.',
    'LBL_FTS_CONN_ERROR'    => 'Es kann keine Verbindung zum Volltextsuche-Server aufgebaut werden, überprüfen Sie bitte Ihre Einstellungen.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Es ist keine Volltextsuche-Serverversion verfügbar, überprüfen Sie bitte Ihre Einstellungen.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Nicht unterstützte Version von Elastic Search erkannt. Bitte verwenden Sie die Versionen: %s',

    'LBL_PATCHES_TITLE'     => 'Neueste Patches installieren',
    'LBL_MODULE_TITLE'      => 'Sprachpakete herunterladen & installieren',
    'LBL_PATCH_1'           => 'Sprachpakete können auch später innerhalb der Anwendung installiert werden.',
    'LBL_PATCH_TITLE'       => 'System-Patch',
    'LBL_PATCH_READY'       => 'Die folgenden Patches können jetzt installiert werden:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "SugarCRM ist darauf angewiesen, dass in PHP-Sitzungen wichtige Informationen gespeichert werden. Ihre PHP-Installation ist dafür nicht korrekt konfiguriert.<br><br>Ein häufiger Fehler ist, dass die Direktive <b>'session.save_path'</b> sich nicht auf ein gültiges Verzeichnis bezieht.<br><br>Bitte korrigieren Sie Ihre <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>PHP-Konfiguration</a> in der Datei php.ini hier unten.",
	'LBL_SESSION_ERR_TITLE'				=> 'Konfigurationsfehler bei PHP-Sitzung',
	'LBL_SYSTEM_NAME'=>'Systemname',
    'LBL_COLLATION' => 'Kollationseinstellungen',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Geben Sie einen Systemnamen für die Sugar-Instanz ein.',
	'LBL_PATCH_UPLOAD' => 'Patch von Ihrem lokalen System hochladen',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'Der PHP-Rückwärts-Kompatibilitätsmodus ist eingeschaltet. Deaktivieren Sie den Modus "zend.ze1_compatibility_mode", um fortzufahren',

    'advanced_password_new_account_email' => array(
        'subject' => 'Neue Firmeninformation',
        'description' => 'Diese Vorlage wird verwendet, wenn der Systemadministrator ein Passwort an den Benutzer schickt.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Dies sind Ihr Benutzername und ihr temporäres Passwort:</p><p>Benutzername: $contact_user_user_name<br/><br />Passwort: $contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url<br /><br/>Wenn Sie sich angemeldet haben, müssen Sie ein neues Passwort definieren.</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Hier sind Ihr Benutzername und temporäres Kennwort:
Benutzername: $contact_user_user_name
Passwort: $contact_user_user_hash

$config_site_url

Nach der ersten Anmeldung mit dem temporären Passwort müssen SIe das Passwort möglicherweise ändern.',
        'name' => 'Systemgenerierte Passwort-E-Mail',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Kontopasswort zurücksetzen',
        'description' => "Diese Vorlage wird verwendet, um einem Benutzer einen Link zum Zurücksetzen des Kontopassworts zu senden.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Sie haben am $contact_user_pwd_last_changed auf das Zurücksetzen Ihres Passwort angesucht. </p><p>Klicken Sie auf folgenden Link, um Ihr Passwort zurückzusetzen:</p><p> <a href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Sie haben am $contact_user_pwd_last_changed auf das Zurücksetzen Ihres Passworts angesucht.

 Klicken Sie auf folgenden Link, um Ihr Passwort zurückzusetzen:

 $contact_user_link_guid',
        'name' => 'E-Mail für vergessenes Passwort',
        ),
);
