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
	'LBL_BASIC_SEARCH'					=> 'Základné vyhľadávanie',
	'LBL_ADVANCED_SEARCH'				=> 'Pokročilé vyhľadávanie',
	'LBL_BASIC_TYPE'					=> 'Základný typ',
	'LBL_ADVANCED_TYPE'					=> 'Pokročilý typ',
	'LBL_SYSOPTS_1'						=> 'Vyberte z nasledujúcich možností konfigurácie systému uvedených ďalej.',
    'LBL_SYSOPTS_2'                     => 'Aký typ databázy bude použitý pre inštaláciu Sugar inštancie ?',
	'LBL_SYSOPTS_CONFIG'				=> 'Konfigurácia systému',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Určenie typu databázy',
    'LBL_SYSOPTS_DB_TITLE'              => 'Typ databázy',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Pred tým, ako budete pokračovať, opravte nasledujúce chyby:',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Upravte atribút nasledujúceho adresára na zapisovateľný:',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Vaša verzia produktu DB2 (%s) nie je podporovaná aplikáciou Sugar. Budete musieť nainštalovať verziu, ktorá je kompatibilná s aplikáciou Sugar. Pozrite si maticu kompatibility v poznámkach k vydaniu pre podporované verzie DB2.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Ak ste zvolili Oracle, musíte mať nainštalovaného a nakonfigurovaného klienta Oracle.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
	'ERR_DB_OCI8_CONNECT'				=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
	'ERR_DB_OCI8_VERSION'				=> 'Sugar nepodporuje Vašu verziu Oracle  (%s). Musíte nainštalovať verziu, ktorá je kompatibilná s aplikáciou Sugar. Pozrite si maticu kompatibility v poznámkach k vydaniu pre podporované verzie Oracle.',
    'LBL_DBCONFIG_ORACLE'               => 'Uveďte názov vašej databázy. Bude to predvolený tabuľkový priestor, ktorý je priradený k vášmu používateľovi ((SID z tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Dotaz na príležitosti',
	'LBL_Q1_DESC'						=> 'Príležitosti podľa typu',
	'LBL_Q2_DESC'						=> 'Príležitosti podľa účtu',
	'LBL_R1'							=> '6 mesačné hlásenie o pipeline obchodu',
	'LBL_R1_DESC'						=> 'Príležitosti za nasledujúcich 6 mesiacov rozdelené podľa mesiacov a typu',
	'LBL_OPP'							=> 'Súbor údajov príležitostí',
	'LBL_OPP1_DESC'						=> 'Tu môžete zmeniť vzhľad a charakter vlastnej otázky',
	'LBL_OPP2_DESC'						=> 'Táto otázka sa bude ukladať pod prvú otázku v hlásení',
    'ERR_DB_VERSION_FAILURE'			=> 'Nemožno skontrolovať verziu databázy.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Zadajte meno používateľa pre používateľa aplikácie Sugar v role administrátora.',
	'ERR_ADMIN_PASS_BLANK'				=> 'Zadajte heslo pre používateľa aplikácie Sugar v role administrátora.',

    'ERR_CHECKSYS'                      => 'Boli nájdené chyby pri kontrole kompatibility. Aby vaša SugarCRM inštalácia správne fungovala, postupujte podľa krokov na riešenie problémov uvedených ďalej a buď stlačte tlačidlo opätovnej kontroly, alebo skúste inštaláciu znova.',
    'ERR_CHECKSYS_CALL_TIME'            => 'Položka Povoliť referenciu uplynutia času volania je zapnutá (mala by byť vypnutá v súbore php.ini)',

	'ERR_CHECKSYS_CURL'					=> 'Nenašlo sa: Plánovač Sugar sa spustí s obmedzenou funkčnosťou. Služba Archivovanie e-mailov nebude fungovať.',
    'ERR_CHECKSYS_IMAP'					=> 'Nepodarilo sa nájsť: Prichádzajúce emaily a kampane (e-mail) vyžadujú IMAP knižnice. Ani jedna možnosť nebude funkčná.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'Magic Quotes GPC nemôžu byť zapnuté, pokiaľ používate server MS SQL.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Upozornenie:',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> '(Nastavte toto na',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M alebo väčšie v súbore php.ini)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Minimálna Verzia 4.1.2 - Nájdené: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Nepodarilo sa písať a čítať premenné relácie. Nemožno pokračovať v inštalácii.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Neplatný adresár',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Upozornenie: nedá sa zapisovať',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Vaša verzia PHP nie je podporovaná aplikáciou Sugar. Musíte nainštalovať verziu, ktorá je kompatibilná s aplikáciou Sugar. Postupujte podľa matice kompatibility v poznámkach k vydaniu pre podporované verzie PHP. Vaša verzia je ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Vaša verzia IIS nie je podporovaná aplikáciou Sugar. Musíte nainštalovať verziu, ktorá je kompatibilná s aplikáciou Sugar. Postupujte podľa matice kompatibility v poznámkach k vydaniu pre podporované verzie PHP. Vaša verzia je ',
	'ERR_CHECKSYS_FASTCGI'              => 'Zistili sme, že nepoužívate FastCGI na mapovanie PHP. Potrebujete nainštalovať/konfigurovať verziu, ktorá je kompatibilná s aplikáciou Sugar. Pozrite si maticu kompatibility v poznámkach k vydaniu pre podporované verzie. Prejdite na stránku <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a>, kde nájdete ďalšie podrobnosti ',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'Pre optimálne využitie pomocou IIS / FastCGI SAPI nastavte fastcgi.logging na 0 v súbore php.ini.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Nainštalovaná PHP verzia nie je podporovaná: (ver',
    'LBL_DB_UNAVAILABLE'                => 'Databáza nie je k dispozícii',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Podpora databázy sa nenašla. Uistite sa, že máte potrebné ovládače pre jeden z nasledujúcich podporovaných typov databázy: MySQL, MS SQLServer, Oracle alebo DB2. V závislosti od vašej verzie PHP možno budete musieť povoliť rozšírenie v súbore php.ini alebo ho prekompilovať správnym binárnym súborom. Informácie o povolení podpory databázy nájdete v príručke k PHP.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Funkcie spojené s XML Parser knižnicami, ktoré sú potrebné v aplikácii Sugar, sa nepodarilo nájsť. Možno bude potrebné odstrániť komentáre rozšírení v súbore php.ini alebo prekompilovať so správnym binárnym súborom v závislosti od vašej verzie PHP. Ďalšie informácie nájdete v príručke k PHP.',
    'LBL_CHECKSYS_CSPRNG' => 'Generátor náhodných čísel',
    'ERR_CHECKSYS_MBSTRING'             => 'Funkcie spojené s rozšírením Multibyte Strings PHP (mbstring), ktoré sú potrebné v aplikácii Sugar, sa nepodarilo nájsť.<br/><br/>Všeobecne platí, že je modul mbstring nie je povolený v predvolenom nastavení v PHP a musí byť aktivovaný pomocou --enable-mbstring, keď je vytvorený binárny PHP. Ďalšie informácie o povolení podpory mbstring nájdete v príručke k PHP.',
    'ERR_CHECKSYS_MCRYPT'               => "Modul Mcrypt nie je načítaný. Ďalšie informácie o načítaní podpory modulu Mcrypt nájdete v príručke k PHP.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'Nastavenia session.save_path v konfiguračnom súbore php (php.ini) nie sú nastavené alebo sú nastavené na priečinok, ktorý neexistuje. Možno budete musieť nastaviť save_path nastavenia v súbore php.ini alebo overiť, či zložky priečinka v save_path existujú.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Nastavenia session.save_path v konfiguračnom súbore php (php.ini) sú nastavené na priečinok, ktorý nie je zapisovateľný. Vykonajte potrebné opatrenia, aby bol priečinok zapisovateľný.<br>V závislosti od vášho operačného systému možno bude potrebné zmeniť oprávnenia spustením chmod 766 alebo kliknúť pravým tlačidlom myši na názov súboru a získať tak prístup k vlastnostiam a zrušiť v nich režim Len na čítanie.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Konfiguračný súbor existuje, ale nie je zapisovateľný. Vykonajte potrebné opatrenia, aby bol súbor zapisovateľný. V závislosti od vášho operačného systému možno bude potrebné zmeniť oprávnenia spustením chmod 766 alebo kliknúť pravým tlačidlom myši na názov súboru a získať tak prístup k vlastnostiam a zrušiť v nich režim Len na čítanie.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Konfiguračný súbor override existuje, ale nie je zapisovateľný. Vykonajte potrebné opatrenia, aby bol súbor zapisovateľný. V závislosti od vášho operačného systému možno bude potrebné zmeniť oprávnenia spustením chmod 766 alebo kliknúť pravým tlačidlom myši na názov súboru a získať tak prístup k vlastnostiam a zrušiť v nich režim Len na čítanie.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Vlastný adresár existuje, ale nie je zapisovateľný. V závislosti od vášho operačného systému možno bude potrebné zmeniť oprávnenia pre adresár (chmod 766) alebo kliknúť na adresár pravým tlačidlom myši a zrušiť režim Len na čítanie. Vykonajte potrebné opatrenia, aby bol súbor zapisovateľný.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "Súbory alebo adresáre uvedené nižšie nie sú zapisovateľné alebo chýbajú a nemôžu byť vytvorené. V závislosti od vášho operačného systému možno bude potrebné zmeniť oprávnenia pre súbory alebo nadradený adresár (chmod 755) alebo kliknúť pravým tlačidlom na nadradený adresár a zrušiť možnosť \"Len na čítanie\" a použiť ju na všetky podpriečinky.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Núdzový režim je zapnutý (možno ho budete chcieť zakázať v súbore php.ini)',
    'ERR_CHECKSYS_ZLIB'					=> 'ZLib podpora nebola nájdená: SugarCRM má obrovské výkonnostné výhody vďaka zlib kompresii.',
    'ERR_CHECKSYS_ZIP'					=> 'ZIP podpora nebola nájdená: SugarCRM potrebuje ZIP podporu, aby bolo možné spracovať komprimované súbory.',
    'ERR_CHECKSYS_BCMATH'				=> 'BCMATH podpora nenájdená: SugarCRM potrebuje BCMATH podporu pre výpočty ľubovoľnej presnosti.',
    'ERR_CHECKSYS_HTACCESS'             => 'Test .htaccess zápisov zlyhal. Toto väčšinou znamená, že nemáte nastavené práva AllowOverride pre adresár Sugar.',
    'ERR_CHECKSYS_CSPRNG' => 'Výnimka generátora CSPRNG',
	'ERR_DB_ADMIN'						=> 'Zadané používateľské meno administrátora databázy a/alebo heslo je neplatné a pripojenie k databáze nemožno vytvoriť. Zadajte platné používateľské meno a heslo. (Chyba: ',
    'ERR_DB_ADMIN_MSSQL'                => 'Zadané používateľské meno administrátora databázy a/alebo heslo je neplatné a pripojenie k databáze nemožno vytvoriť. Zadajte platné používateľské meno a heslo.',
	'ERR_DB_EXISTS_NOT'					=> 'Uvedená databáza neexistuje.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'Databáza s konfiguračnými údajmi už existuje. Ak chcete spustiť inštaláciu s vybratou databázou, znovu spustite inštaláciu a zvoľte: "Vymazať a znovu vytvoriť existujúce SugarCRM tabuľky" Ak chcete vykonať inováciu, použite Sprievodcu inováciou v Konzole pre správu. Prečítajte si dokumentáciu o inovácii dostupnú <a href="http://www.sugarforge.org/content/downloads/" target="_new">tu</a>.',
	'ERR_DB_EXISTS'						=> 'Zadaný názov databázy už existuje, nie je možné vytvoriť ďalšiu s rovnakým názvom.',
    'ERR_DB_EXISTS_PROCEED'             => 'Zadaný názov databázy už existuje. Môžete<br>1. kliknúť tlačidlo Späť a zvoliť nový názov databázy<br>2. kliknúť na tlačidlo Ďalej a pokračovať, ale všetky existujúce tabuľky tejto databázy budú zrušené. <strong>To znamená, že vaše tabuľky a údaje budú vymazané.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Názov hostiteľa nemôže byť prázdny.',
	'ERR_DB_INVALID'					=> 'Vybratý typ databázy je neplatný.',
	'ERR_DB_LOGIN_FAILURE'				=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Zadaný hostiteľ, meno používateľa a/alebo heslo k databáze je nesprávne. Spojenie s databázou zlyhalo. Zadajte platného hostiteľa, meno používateľa a heslo',
	'ERR_DB_MYSQL_VERSION'				=> 'Vaša verzia MySQL (%s) nie je podporovaná aplikáciou Sugar. Musíte nainštalovať verziu, ktorá je kompatibilná s aplikáciou Sugar. Pozrite si maticu kompatibility v poznámkach k vydaniu pre podporované verzie MySQL.',
	'ERR_DB_NAME'						=> 'Názov databázy nemôže byť prázdny.',
	'ERR_DB_NAME2'						=> "Názov databázy nemôže obsahovať znak '\\', '/', alebo '.'",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Názov databázy nemôže obsahovať znak '\\', '/', alebo '.'",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Názov databázy nemôže začínať číslom, '#' alebo '@' a nemôže obsahovať medzeru, '\"', \"'\", '*', '/', '\\', '?', ':', '<', '>', '&', '!', or '-'",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Názov databázy môže obsahovať len alfanumerické znaky a symboly '#', '_', '-', ':', '.', '/' a '$'",
	'ERR_DB_PASSWORD'					=> 'Heslá uvedené pre administrátora databázy Sugar sa nezhodujú. Zadajte znova rovnaké heslá v poli pre heslo.',
	'ERR_DB_PRIV_USER'					=> 'Zadajte používateľské meno administrátora databázy. Používateľ je povinná položka pre prvé pripojenie k databáze.',
	'ERR_DB_USER_EXISTS'				=> 'Meno používateľa databázy Sugar už existuje – nie je možné vytvoriť ďalšieho používateľa s rovnakým menom. Zadajte nové meno používateľa.',
	'ERR_DB_USER'						=> 'Zadajte používateľské meno pre administrátora databázy Sugar.',
	'ERR_DBCONF_VALIDATION'				=> 'Pred tým, ako budete pokračovať, opravte nasledujúce chyby:',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'Heslá zadané pre používateľa databázy Sugar sa nezhodujú. Zadajte znova rovnaké heslá v poli pre heslo.',
	'ERR_ERROR_GENERAL'					=> 'Došlo k nasledujúcim chybám:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Nemožno zmazať súbor:',
	'ERR_LANG_MISSING_FILE'				=> 'Nemožno nájsť súbor:',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Žiadny súbor s jazykovým balíkom nebol nájdený v include/language: ',
	'ERR_LANG_UPLOAD_1'					=> 'Problém s načítaním. Skúste to znova.',
	'ERR_LANG_UPLOAD_2'					=> 'Jazykové balíky musia byť ZIP archívy.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP nemôže presunúť dočasný súbor do adresára upgrade.',
	'ERR_LICENSE_MISSING'				=> 'Chýbajú povinné údaje',
	'ERR_LICENSE_NOT_FOUND'				=> 'Súbor licencie nebol nájdený!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Uvedený adresár pre denníky nie je platný adresár.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Uvedený adresár pre denníky nie je zapisovateľný adresár.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Ak chcete špecifikovať svoje vlastné umiestnenie, vyžaduje sa adresár pre denníky.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Nemožno spracovať skript priamo.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Nedá sa použiť apostrof pre',
	'ERR_PASSWORD_MISMATCH'				=> 'Heslá zadané pre používateľa aplikácie Sugar v role administrátora sa nezhodujú. Zadajte znova rovnaké heslá v poli pre heslo.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Zápis do súboru <span class=stop>config.php</span> nie je možný.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Môžete pokračovať v inštalácii ručným vytvorením súboru config.php a vložením informácií o konfigurácii nižšie do súboru config.php. <strong>Musíte</strong> však vytvoriť súbor config.php pred tým, ako budete pokračovať k ďalšiemu kroku.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Nezabudli vytvoriť súbor config.php?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Upozornenie: Do súboru config.php nemožno zapisovať. Uistite sa, že existuje.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Nemožno zapisovať do',
	'ERR_PERFORM_HTACCESS_2'			=> ' súboru.',
	'ERR_PERFORM_HTACCESS_3'			=> 'Ak chcete zabezpečiť prístupnosť svojho ​​súboru denníka prostredníctvom prehliadača, vytvorte súbor .htaccess vo vašom adresári pre denníky s nasledujúcim riadkom:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>Nepodarilo sa zistiť pripojenie k internetu.</b> Keď máte pripojenie, navštívte stránku <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> a zaregistrujte sa do aplikácie SugarCRM. Ak nás informujete o tom, ako vaša spoločnosť plánuje využiť aplikáciu SugarCRM, môžeme zaistiť, aby sme vždy dodávali správnu aplikáciu pre vaše obchodné potreby.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Adresár pre informácie o reláciách nie je platný adresár.',
	'ERR_SESSION_DIRECTORY'				=> 'Uvedený adresár pre informácie o reláciách nie je zapisovateľný adresára.',
	'ERR_SESSION_PATH'					=> 'Cesta k reláciám je povinná položka, ak chcete určiť vlastné umiestnenie.',
	'ERR_SI_NO_CONFIG'					=> 'Nezahrnuli ste config_si.php v koreňovom priečinku dokumentov alebo ste nedefinovali $sugar_config_si v config.php',
	'ERR_SITE_GUID'						=> 'ID aplikácie je povinná položka, ak chcete určiť vlastné umiestnenie.',
    'ERROR_SPRITE_SUPPORT'              => "Momentálne nedokážeme nájsť knižnicu GD, a preto nebudete môcť používať funkcie CSS Sprite.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Upozornenie: Je potrebné zmeniť vašu PHP konfiguráciu, aby bolo možné nahrávať súbory s veľkosťou aspoň 6 MB. ',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Nahrať veľkosť súboru',
	'ERR_URL_BLANK'						=> 'Uveďte základné URL pre inštanciu Sugar.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Nepodarilo sa nájsť záznam inštalácie',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Nahratý súbor nie je kompatibilný s týmto typom (vydanie Professional, Enterprise alebo Ultimate) aplikácie Sugar: ',
	'ERROR_LICENSE_EXPIRED'				=> "Chyba: Platnosť vašej licencie vypršala ",
	'ERROR_LICENSE_EXPIRED2'			=> " dní dozadu. Prejdite do časti <a href='index.php?action=LicenseSettings&module=Administration'>'\"Správa licencie\"</a> na obrazovke administrácie a zadajte svoj nový licenčný kľúč. Ak nezadáte nový licenčný kľúč v priebehu 30 dní od dňa vypršania platnosti licenčného kľúča, nebudete sa môcť prihlásiť do aplikácie.",
	'ERROR_MANIFEST_TYPE'				=> 'Súbor manifestu musí špecifikovať typ balíka.',
	'ERROR_PACKAGE_TYPE'				=> 'Súbor manifestu našiel nerozpoznaný typ balíka',
	'ERROR_VALIDATION_EXPIRED'			=> "Chyba: Platnosť vášho overovacieho kľúča vypršala ",
	'ERROR_VALIDATION_EXPIRED2'			=> " dní dozadu. Prejdite do časti <a href='index.php?action=LicenseSettings&module=Administration'>'\"Správa licencie\"</a> na obrazovke administrácie a zadajte svoj nový licenčný kľúč. Ak nezadáte nový licenčný kľúč v priebehu 30 dní od dňa vypršania platnosti licenčného kľúča, nebudete sa môcť prihlásiť do aplikácie.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Nahratý súbor nie je kompatibilný s touto verziou aplikácie Sugar: ',

	'LBL_BACK'							=> 'Späť',
    'LBL_CANCEL'                        => 'Zrušiť',
    'LBL_ACCEPT'                        => 'Súhlasím',
	'LBL_CHECKSYS_1'					=> 'Aby sa vaša SugarCRM inštalácia správne fungovala, uistite sa, že všetky položky kontroly systému uvedené ďalej sú zelené. Ak sú niektoré červené, vykonajte potrebné opatrenia, aby ste ich opravili.<BR><BR> Pomoc v súvislosti s týmito položkami systému nájdete na stránke <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.',
	'LBL_CHECKSYS_CACHE'				=> 'Zapisovateľná vyrovnácia pamäť podadresárov',
    'LBL_DROP_DB_CONFIRM'               => 'Zadaný názov databázy už existuje.<br>Môžete buď:<br>1. Kliknúť na tlačidlo Zrušiť a vybrať nový názov databázy, alebo<br>2. Kliknúť na tlačidlo Súhlasím a pokračovať. Všetky existujúce tabuľky v databáze budú vylúčené. <strong>To znamená, že všetky tabuľky a už existujúce údaje budú vymazané.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'Položka PHP Povoliť referenciu odovzdania času volania je vypnutá',
    'LBL_CHECKSYS_COMPONENT'			=> 'zložka',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Voliteľné súčasti',
	'LBL_CHECKSYS_CONFIG'				=> 'Zapisovateľný konfiguračný súbor SugarCRM (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Zapisovateľný konfiguračný súbor SugarCRM (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'cURL modul',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Nastavenie cesty ukladania relácie',
	'LBL_CHECKSYS_CUSTOM'				=> 'Zapisovateľný vlastný adresár',
	'LBL_CHECKSYS_DATA'					=> 'Zapisovateľné podadresáre údajov',
	'LBL_CHECKSYS_IMAP'					=> 'IMAP modul',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'Modul MB Strings',
    'LBL_CHECKSYS_MCRYPT'               => 'Modul MCrypt',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (žiadne obmedzenie)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (žiadne obmedzenie)',
	'LBL_CHECKSYS_MEM'					=> 'PHP pamäťový limit',
	'LBL_CHECKSYS_MODULE'				=> 'Zapisovateľné moduly podadresárov a súborov',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'MySQL verzia',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Nedostupné',
	'LBL_CHECKSYS_OK'					=> 'Ok',
	'LBL_CHECKSYS_PHP_INI'				=> 'Umiestnenie konfiguračného súboru PHP (php.ini):',
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (ver',
	'LBL_CHECKSYS_PHPVER'				=> 'PHP Verzia',
    'LBL_CHECKSYS_IISVER'               => 'IIS Verzia',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Znovu skontrolovať',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'Vypnutý bezpečnostný režim PHP',
	'LBL_CHECKSYS_SESSION'				=> 'Cesta pre uloženie zapisovateľnej relácie (',
	'LBL_CHECKSYS_STATUS'				=> 'Stav',
	'LBL_CHECKSYS_TITLE'				=> 'Prijatie systémovej kontroly',
	'LBL_CHECKSYS_VER'					=> 'Nájdené: (ver',
	'LBL_CHECKSYS_XML'					=> 'Analýza súborov XML',
	'LBL_CHECKSYS_ZLIB'					=> 'Modul kompresie ZLIB',
	'LBL_CHECKSYS_ZIP'					=> 'Manipulácia s modulom ZIP',
    'LBL_CHECKSYS_BCMATH'				=> 'Modul Výpočty v ľubovoľnej presnosti',
    'LBL_CHECKSYS_HTACCESS'				=> 'Nastavenia AllowOverride pre .htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'Pred tým, ako budete pokračovať, opravte nasledujúce súbory alebo adresáre:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Pred tým, ako budete pokračovať, opravte nasledujúce adresáre modulu a súbory pod nimi:',
    'LBL_CHECKSYS_UPLOAD'               => 'Zapisovateľný adresár Upload',
    'LBL_CLOSE'							=> 'Zavrieť',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'byť vytvorený',
	'LBL_CONFIRM_DB_TYPE'				=> 'Typ databázy',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Potvrďte nastavenia nižšie. Ak chcete zmeniť niektoré z hodnôt, kliknite na tlačidlo "Späť" a vykonajte úpravu. V opačnom prípade kliknite na tlačidlo "Ďalej" a spustite inštaláciu.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Informácie o licencii',
	'LBL_CONFIRM_NOT'					=> 'nie',
	'LBL_CONFIRM_TITLE'					=> 'Potvrdiť nastavenia',
	'LBL_CONFIRM_WILL'					=> 'bude',
	'LBL_DBCONF_CREATE_DB'				=> 'Vytvoriť databázu',
	'LBL_DBCONF_CREATE_USER'			=> 'Vytvoriť používateľa',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Upozornenie: Všetky údaje aplikácie Sugar budú vymazané,<br>ak začiarknete toto pole.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Vymazať a znovu vytvoriť existujúce tabuľky Sugar?',
    'LBL_DBCONF_DB_DROP'                => 'Vymazať tabuľky',
    'LBL_DBCONF_DB_NAME'				=> 'Názov databázy',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Heslo používateľa do databázy Sugar',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Znova zadajte heslo používateľa do databázy Sugar',
	'LBL_DBCONF_DB_USER'				=> 'Používateľ databázy Sugar',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Používateľ databázy Sugar',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Používateľské meno administrátora databázy',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Heslo administrátora databázy',
	'LBL_DBCONF_DEMO_DATA'				=> 'Nahrať demo dáta do databázy?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Vyberte demo dáta',
	'LBL_DBCONF_HOST_NAME'				=> 'Hostiteľské meno',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Inštancia hostiteľa',
	'LBL_DBCONF_HOST_PORT'				=> 'Port',
    'LBL_DBCONF_SSL_ENABLED'            => 'Povoliť pripojenie SSL',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Zadajte nižšie informácie o konfigurácii vašej databázy. Ak si nie ste istí, čo je treba vyplniť, odporúčame použiť predvolené hodnoty.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Použiť multibajtový text v demo dátach?',
    'LBL_DBCONFIG_MSG2'                 => 'Názov webového servera alebo stroja (hostiteľ), na ktorom je umiestnená databáza (napr. localhost alebo www.mydomain.com):',
    'LBL_DBCONFIG_MSG3'                 => 'Názov databázy, ktorá bude obsahovať údaje pre inštanciu Sugar, ktorú sa chystáte inštalovať:',
    'LBL_DBCONFIG_B_MSG1'               => 'Meno používateľa a heslo administrátora databázy, ktorý môže vytvárať databázové tabuľky a používateľov a ktorý môže zapisovať do databázy, je nevyhnutné na nastavenie databázy Sugar.',
    'LBL_DBCONFIG_SECURITY'             => 'Z bezpečnostných dôvodov môžete určiť výhradného používateľa databázy na pripojenie k databáze Sugar. Tento používateľ musí mať možnosť zapisovať, aktualizovať a získavať údaje o databáze Sugar, ktorá bude vytvorená pre túto inštanciu. Tento používateľ môže byť administrátor databázy uvedený vyššie alebo môžete poskytnúť nové alebo existujúce informácie o používateľovi databázy.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Urob to za mňa',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Zadajte existujúceho používateľa',
    'LBL_DBCONFIG_CREATE_DD'            => 'Definujte používateľa, ktorého treba vytvoriť',
    'LBL_DBCONFIG_SAME_DD'              => 'Rovnaké ako používateľ v roli administrátora',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Celotextové vyhľadávanie',
    'LBL_FTS_INSTALLED'                 => '
Nainštalované',
    'LBL_FTS_INSTALLED_ERR1'            => 'Funkcia celotextového vyhľadávania nie je nainštalovaná.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Stále môžete vykonať inštaláciu, ale nebudete môcť používať fulltextové vyhľadávanie. Pozrite si inštalačnú príručku pre databázový server, kde nájdete informácie o tom, ako postupovať, alebo sa obráťte na administrátora.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Heslo oprávneného používateľa databázy ',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Databázový účet uvedený predtým je oprávnený používateľ?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'Tento oprávnený používateľ databázy musí mať príslušné oprávnenia na vytváranie databázy, mazanie/vytváranie tabuliek a vytváranie používateľov. Tento oprávnený používateľ databázy sa bude počas procesu inštalácie používať iba na vykonanie týchto úloh podľa potreby. Môžete takisto použiť rovnakého používateľa databázy, ako je uvedené vyššie, v prípade, že takýto používateľ má dostatočné oprávnenia.',
	'LBL_DBCONF_PRIV_USER'				=> 'Meno oprávneného používateľa databázy',
	'LBL_DBCONF_TITLE'					=> 'Konfigurácia databázy',
    'LBL_DBCONF_TITLE_NAME'             => 'Uveďte názov databázy',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Zadajte informácie o používateľovi databázy',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'Po vykonaní tejto zmeny môžete kliknúť na možnosť "Štart" uvedenú ďalej a spustiť inštaláciu. <i>Po dokončení inštalácie zmeňte hodnotu pre "installer_locked" na možnosť "true".</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'Inštalačný program už je raz spustený. Z dôvodu bezpečnostných opatrení je zakázané inštaláciu spustiť znova. Ak naozaj chcete inštaláciu spustiť znova, prejdite do súboru config.php a nájdite (alebo pridajte) premennú s názvom "installer_locked" a nastavte ju na možnosť "false". Riadok by mal vyzerať takto:',
	'LBL_DISABLED_HELP_1'				=> 'Pomocníka pre inštaláciu nájdete na stránke aplikácie SugarCRM',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'podpora fóra',
	'LBL_DISABLED_TITLE_2'				=> 'Inštalácia SugarCRM bola zakázaná',
	'LBL_DISABLED_TITLE'				=> 'Inštalácia SugarCRM zakázaná',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Najpoužívanejší súbor znakov vo vašom prostredí',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Nastavenia odchádzajúcich e-mailov',
    'LBL_EMAIL_CHARSET_CONF'            => 'Súbor znakov pre odchádzajúce e-maily ',
	'LBL_HELP'							=> 'Pomoc',
    'LBL_INSTALL'                       => 'Inštalovať',
    'LBL_INSTALL_TYPE_TITLE'            => 'Možnosti inštalácie',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Vyberte typ inštalácie',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>Typická inštalácia</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>Vlastná inštalácia</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Kľúč je potrebný pre použitie základných funkcií, ale to nie je nutné pri inštalácii. Nemusíte zadávať kľúč v čase inštalácie, stačí ho zadať po inštalácii aplikácie.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Vyžaduje minimálne informácie o inštalácii. Odporúča sa pre nových používateľov.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Poskytuje ďalšie možnosti nastavenia počas inštalácie. Väčšina z týchto možností je k dispozícii aj po inštalácii na administrátorských obrazovkách. Odporúča sa pre pokročilých používateľov.',
	'LBL_LANG_1'						=> 'Ak chcete použiť v aplikácii Sugar jazyk iný, než je predvolený jazyk (US-English), môžete nahrať a nainštalovať jazykový balík. Nahrať a nainštalovať jazykové balíky budete môcť aj priamo v aplikácii Sugar. Ak chcete tento krok preskočiť, kliknite na tlačidlo Ďalej.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Inštalovať',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Odstrániť',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Odinštalovať',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Nahrať',
	'LBL_LANG_NO_PACKS'					=> 'žiadne',
	'LBL_LANG_PACK_INSTALLED'			=> 'Nasledujúce jazykové balíky boli nainštalované:',
	'LBL_LANG_PACK_READY'				=> 'Nasledujúce jazykové balíky sú pripravené na inštaláciu:',
	'LBL_LANG_SUCCESS'					=> 'Jazykový balík bol úspešne nahratý.',
	'LBL_LANG_TITLE'			   		=> 'Jazykový balík',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'Inštalovať aplikáciu Sugar teraz. Môže to trvať až niekoľko minút.',
	'LBL_LANG_UPLOAD'					=> 'Nahrať jazykový balík',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Prijatie licencie',
    'LBL_LICENSE_CHECKING'              => 'Kontrola kompatibility systému.',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Kontrola prostredia',
    'LBL_LICENSE_CHKDB_HEADER'          => 'Overenie poverení DB, FTS.',
    'LBL_LICENSE_CHECK_PASSED'          => 'Kompatibilita systému bola úspešne overená.',
    'LBL_LICENSE_REDIRECT'              => 'Presmerovanie v ',
	'LBL_LICENSE_DIRECTIONS'			=> 'Ak máte informácie o licencii, zadajte ich do polí uvedených nižšie.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Zadajte kľúč na stiahnutie',
	'LBL_LICENSE_EXPIRY'				=> 'Dátum vypršania platnosti',
	'LBL_LICENSE_I_ACCEPT'				=> 'Súhlasím',
	'LBL_LICENSE_NUM_USERS'				=> 'Počet používateľov',
	'LBL_LICENSE_PRINTABLE'				=> ' Zobrazenie pre tlač ',
    'LBL_PRINT_SUMM'                    => 'Vytlačiť súhrn',
	'LBL_LICENSE_TITLE_2'				=> 'Licencia SugarCRM',
	'LBL_LICENSE_TITLE'					=> 'Informácie o licencii',
	'LBL_LICENSE_USERS'					=> 'Licencovaní používatelia',

	'LBL_LOCALE_CURRENCY'				=> 'Nastavenie meny',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Predvolená mena',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Symbol meny',
	'LBL_LOCALE_CURR_ISO'				=> 'Kód meny (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> 'Oddeľovač tisícov',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Oddeľovač desatinných miest',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Príklad',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Relevantné číslice',
	'LBL_LOCALE_DATEF'					=> 'Predvolený formát dátumu',
	'LBL_LOCALE_DESC'					=> 'Zadané miestne nastavenia sa prejavia globálne v rámci inštancie Sugar.',
	'LBL_LOCALE_EXPORT'					=> 'Súprava znakov pre import/Export <br><i>(E-mail, .csv, vCard, PDF, import dát)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Oddeľovač Export (. csv)',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Nastavenia importu/exportu',
	'LBL_LOCALE_LANG'					=> 'Predvolený jazyk',
	'LBL_LOCALE_NAMEF'					=> 'Predvolený formát názvu',
	'LBL_LOCALE_NAMEF_DESC'				=> 's=oslovenie<br />f=krstné meno<br />l=priezvisko',
	'LBL_LOCALE_NAME_FIRST'				=> 'David',
	'LBL_LOCALE_NAME_LAST'				=> 'Livingstone',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr.',
	'LBL_LOCALE_TIMEF'					=> 'Predvolený formát času',
	'LBL_LOCALE_TITLE'					=> 'Nastavenia lokality',
    'LBL_CUSTOMIZE_LOCALE'              => 'Vlastné nastavenia lokality',
	'LBL_LOCALE_UI'						=> 'Používateľské rozhranie',

	'LBL_ML_ACTION'						=> 'Akcia',
	'LBL_ML_DESCRIPTION'				=> 'Popis',
	'LBL_ML_INSTALLED'					=> 'Dátum inštalácie',
	'LBL_ML_NAME'						=> 'Meno',
	'LBL_ML_PUBLISHED'					=> 'Dátum zverejnenia',
	'LBL_ML_TYPE'						=> 'Typ',
	'LBL_ML_UNINSTALLABLE'				=> 'Neinštalovateľné',
	'LBL_ML_VERSION'					=> 'Verzia',
	'LBL_MSSQL'							=> 'Server SQL',
	'LBL_MSSQL_SQLSRV'				    => 'Server SQL (Microsoft SQL Server ovládač pre PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (rozšírenie mysqli)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Ďalej',
	'LBL_NO'							=> 'Nie',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Nastavuje sa heslo administrátora stránky',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'tabuľka auditu/',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Vytvára sa konfiguračný súbor pre aplikáciu SugarCRM',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Vytvára sa databáza</b>',
	'LBL_PERFORM_CREATE_DB_2'			=> '<b>do</b>',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Vytvára sa meno a heslo používateľa databázy...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Vytvárajú sa východiskové údaje aplikácie Sugar',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Vytvára sa meno používateľa a heslo pre databázu určené pre lokálneho hostiteľa...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Vytvárajú sa vzťahové tabuľky aplikácie Sugar',
	'LBL_PERFORM_CREATING'				=> 'vytváranie/',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Vytvárajú sa východiskové hlásenia',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Vytvárajú sa východiskové úlohy plánovača',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Vloženie predvolených nastavení',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Vytvárajú sa predvolení používatelia',
	'LBL_PERFORM_DEMO_DATA'				=> 'Vyplnenie databázových tabuliek s demo dátami (môže to chvíľu trvať)',
	'LBL_PERFORM_DONE'					=> 'hotovo<br>',
	'LBL_PERFORM_DROPPING'				=> 'vymazáva sa/',
	'LBL_PERFORM_FINISH'				=> 'Dokončiť',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Aktualizácia informácií o licencii',
	'LBL_PERFORM_OUTRO_1'				=> 'Nastavenie aplikácie Sugar ',
	'LBL_PERFORM_OUTRO_2'				=> 'je teraz dokončené!',
	'LBL_PERFORM_OUTRO_3'				=> 'Celkový čas:',
	'LBL_PERFORM_OUTRO_4'				=> 'sekundy',
	'LBL_PERFORM_OUTRO_5'				=> 'Približná použitá kapacita pamäte: ',
	'LBL_PERFORM_OUTRO_6'				=> ' byty.',
	'LBL_PERFORM_OUTRO_7'				=> 'Váš systém je teraz nainštalovaný a nakonfigurovaný na použitie.',
	'LBL_PERFORM_REL_META'				=> 'meta vzťah...',
	'LBL_PERFORM_SUCCESS'				=> 'Úspešne dokončené!',
	'LBL_PERFORM_TABLES'				=> 'Vytváranie tabuliek aplikácie Sugar, tabuliek auditu a metadát vzťahov',
	'LBL_PERFORM_TITLE'					=> 'Vykonať nastavenie',
	'LBL_PRINT'							=> 'Tlač',
	'LBL_REG_CONF_1'					=> 'Vyplňte krátky formulár uvedený ďalej a dostanete od tímu aplikácie od SugarCRM oznámenia o produktoch, správy o školeniach, špeciálne ponuky a pozvánky na špeciálne udalosti. Informácie zhromaždené tu nepredávame, neprenajímame, nezdieľame ani inak nedistribuujeme tretím stranám.',
	'LBL_REG_CONF_2'					=> 'Vaše meno a e-mailová adresa sú jediné povinné polia na registráciu. Všetky ostatné polia sú voliteľné, ale veľmi užitočné. Informácie zhromaždené tu nepredávame, neprenajímame, nezdieľame ani inak nedistribuujeme tretím stranám.',
	'LBL_REG_CONF_3'					=> 'Ďakujeme za registráciu. Kliknite na tlačidlo Dokončiť a prihláste sa do aplikácie SugarCRM. Prvýkrát sa budete musieť prihlásiť pomocou mena používateľa "admin" a hesla, ktoré ste zadali v kroku 2.',
	'LBL_REG_TITLE'						=> 'Registrácia',
    'LBL_REG_NO_THANKS'                 => 'Nie, ďakujem',
    'LBL_REG_SKIP_THIS_STEP'            => 'Preskočiť tento krok',
	'LBL_REQUIRED'						=> '* Povinné pole',

    'LBL_SITECFG_ADMIN_Name'            => 'Meno administrátora aplikácie Sugar',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Zadajte znovu heslo administrátora aplikácie Sugar',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Upozornenie: Tým sa prepíše heslo administrátora všetkých predchádzajúcich inštalácií.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Heslo administrátora aplikácie Sugar',
	'LBL_SITECFG_APP_ID'				=> 'ID aplikácie',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Ak je táto možnosť vybratá, je nutné zadať ID aplikácie a prepísať tak automaticky generované ID. ID zaisťuje, že relácie jednej inštancie Sugar nie sú používané inými inštanciami. Ak máte klaster inštalácií aplikácie Sugar, všetky musia mať rovnaké ID aplikácie.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Zadajte vlastne ID aplikácie',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Ak je táto možnosť vybratá, je nutné vybrať adresár denníkov a prepísať predvolený adresár pre denník Sugar. Bez ohľadu na to, kde sa nachádza súbor denníka, prístup k nemu cez webový prehliadač bude obmedzený pomocou presmerovania .htaccess.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Použiť vlastný adresár denníkov',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Ak je táto možnosť vybratá, musíte poskytnúť zabezpečený priečinok na ukladanie informácií o reláciách aplikácie Sugar. To možno vykonať, aby sa zabránilo nedostatočnému zabezpečeniu dát relácie na zdieľaných serveroch.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Použiť vlastný adresár relácií pre aplikáciu Sugar',
	'LBL_SITECFG_DIRECTIONS'			=> 'Zadajte svoje informácie o konfigurácii stránky ďalej. Ak si nie ste istí, čo vyplniť do polí, odporúčame použiť predvolené hodnoty.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Pred tým, ako budete pokračovať, opravte tieto chyby:</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Adresár s denníkmi',
	'LBL_SITECFG_SESSION_PATH'			=> 'Cesta k adresáru s denníkmi<br>(musí byť zapisovateľný)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Vyberte možnosti zabezpečenia',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'Ak je táto možnosť začiarknutá, systém bude pravidelne kontrolovať dostupnosť aktualizovaných verzií aplikácie.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Automaticky kontrolovať dostupnosť aktualizácií?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Konfigurácia aktualizácií aplikácie Sugar',
	'LBL_SITECFG_TITLE'					=> 'Konfigurácia stránky',
    'LBL_SITECFG_TITLE2'                => 'Identifikácia administrátora',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Bezpečnosť stránky',
	'LBL_SITECFG_URL'					=> 'URL inštancie Sugar',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Použiť predvolené nastavenia?',
	'LBL_SITECFG_ANONSTATS'             => 'Poslať anonymnú štatistiku používania?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'Ak je táto možnosť začiarknutá, aplikácia Sugar bude posielať <b>anonymné</b> štatistiky o vašej inštalácii do aplikácie SugarCRM Inc. zakaždým, keď váš systém kontroluje nové verzie. Táto informácia nám pomôže lepšie pochopiť, ako sa aplikácia používa, a identifikovať potenciál na zlepšenia produktu.',
    'LBL_SITECFG_URL_MSG'               => 'Zadajte adresu URL, ktorá sa po inštalácii použije na získanie prístupu k inštancii Sugar. Táto adresa URL sa bude takisto používať ako východisko pre adresy URL na stránkach aplikácie Sugar. URL by mala zahŕňať webový server alebo názov zariadenia a IP adresu.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Zadajte názov svojho systému. Tento názov sa bude zobrazovať v riadku prehliadača titulu, keď používateľ navštívi aplikáciu Sugar.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Po inštalácii budete musieť na prihlásenie do inštancie Sugar použiť administrátora Sugar (predvolené meno používateľa = admin). Zadajte heslo tohto administrátora. Toto heslo sa dá po prvom prihlásení zmeniť. Okrem predvolenej hodnoty môžete takisto zadať iné meno používateľa v role administrátora.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Vyberte nastavenia radenia (triedenia) pre váš systém. Tieto nastavenia budú vytvárať tabuľky s konkrétnym jazykom, ktorý používate. V prípade, že váš jazyk nevyžaduje špeciálne nastavenia, použite predvolenú hodnotu.',
    'LBL_SPRITE_SUPPORT'                => 'Podpora obrázkov',
	'LBL_SYSTEM_CREDS'                  => 'Systémové poverenia',
    'LBL_SYSTEM_ENV'                    => 'Systémové prostredie',
	'LBL_START'							=> 'Začiatok',
    'LBL_SHOW_PASS'                     => 'Zobraziť heslo',
    'LBL_HIDE_PASS'                     => 'Skryť heslo',
    'LBL_HIDDEN'                        => '<i>(skrytý)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Vyberte svoj ​​jazyk</b>',
	'LBL_STEP'							=> 'Krok',
	'LBL_TITLE_WELCOME'					=> 'Vitajte v aplikácii SugarCRM',
	'LBL_WELCOME_1'						=> 'Tento inštalačný program vytvorí SugarCRM databázové tabuľky a nastaví premenné konfigurácie, ktoré sú potrebné na spustenie. Celý proces by mal trvať asi desať minút.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Ste pripravení na inštaláciu?',
    'REQUIRED_SYS_COMP' => 'Potrebné systémové komponenty',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Pred tým, ako začnete, sa uistite, že vlastníte podporované verzie nasledujúcich systémových komponentov:<br>
<ul>
<li> Databáza/Systém správy databázy (Príklady: MySQL, SQL Server, Oracle, DB2)</li>
<li> Webový server (Apache, IIS)</li>
<li> Elasticsearch</li>
</ul>
Pozrite si maticu kompatibility v poznámkach k vydaniu, kde nájdete kompatibilné
systémové komponenty pre verziu aplikácie Sugar, ktorú inštalujete.<br>',
    'REQUIRED_SYS_CHK' => 'Úvodná kontrola systému',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Po spustení procesu inštalácie sa vykoná kontrola systému na webovom serveri, na ktorom sú umiestnené súbory aplikácie Sugar, aby sa zabezpečilo, že systém je správne nakonfigurovaný a má všetky potrebné komponenty na úspešné dokončenie inštalácie. <br><br>
Systém skontroluje všetky nasledujúce body:<br>
<ul>
<li><b>verzia PHP</b> &#8211; musí byť kompatibilná s aplikáciou</li>
<li><b>Premenné relácií</b> &#8211; musia správne fungovať</li>
<li> <b>MB Strings</b> &#8211; musia byť nainštalované a povolené v súbore php.ini</li>

<li> <b>Podpora databázy</b> &#8211; musí byť k dispozícii pre MySQL, SQL
Server, Oracle alebo DB2</li>

<li> <b>Config.php</b> &#8211; musí byť k dispozícii a musí mať dostatočné povolenia, aby bol zapisovateľný</li>
<li>Nasledujúce súbory aplikácie Sugar musia byť zapisovateľné:<ul><li><b>/custom</li>
<li>/cache</li>
<li>/modules</li>
<li>/upload</b></li></ul></li></ul>
Ak sa kontrolu nepodarí vykonať, nebudete môcť pokračovať s inštaláciou. Zobrazí sa chybové hlásenie s vysvetlením, prečo sa vášmu systému nepodarilo vykonať kontrolu. Po vykonaní potrebných zmien môžete kontrolu systému spustiť znova a pokračovať s inštaláciou.<br>',
    'REQUIRED_INSTALLTYPE' => 'Typická nebo vlastná inštalácia',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Po dokončení kontroly systému môžete zvoliť typickú alebo vlastnú inštaláciu.<br><br>
V prípade <b>typickej</b> a <b>vlastnej</b> inštalácie by ste mali poznať:<br>
<ul>
<li> <b>typ databázy</b>, v ktorej budú uložené dáta aplikácie Sugar data <ul><li>Typy kompatibilných databáz: MySQL, MS SQL Server, Oracle, DB2.<br><br></li></ul></li>
<li> <b>Názov webového servera</b> alebo zariadenia (hostiteľa), na ktorom je databáza umiestnená
<ul><li>Môže to byť <i>lokálny hostiteľ</i>, ak je databáza na vašom lokálnom počítači alebo na rovnakom webovom servere alebo zariadení ako súbory aplikácie Sugar.<br><br></li></ul></li>
<li><b>Názov databázy</b>, ktorú chcete používať na ukladanie dát aplikácie Sugar</li>
<ul>
<li> Možno už máte existujúcu databázu, ktorú chcete používať. Ak zadáte názov existujúcej databázy, tabuľky v databáze sa budú počas inštalácie vymazávať, keď sa bude definovať schéma pre databázu Sugar.</li>
<li> Ak nemáte žiadnu databázu, zadaný názov sa použije pre novú databázu vytvorenú pre inštanciu počas inštalácie.<br><br></li>
</ul>
<li><b>Meno a heslo administrátora databázy</b> <ul><li>Administrátor databázy by mal byť schopný vytvárať tabuľky a používateľov a zapisovať do databázy.</li><li>Ak databáza nie je umiestnená na vašom lokálnom počítačia vy nie ste administrátorom databázy, budete musieť administrátora kontaktovať, aby vám tieto informácie poskytol.<br><br></ul></li></li>
<li> <b>Meno a heslo používateľa databázy Sugar</b>
</li>
<ul>
<li> Používateľ môže byť administrátor databázy alebo môžete zadať meno iného existujúceho používateľa databázy. </li>
<li> Ak chcete na tento účel vytvoriť novú databázu, budete môcť zadať nové meno používateľa a heslo počas inštalácie a používateľ bude vytvorený počas inštalácie. </li>
</ul>
<li> <b>Hostiteľ a port Elasticsearch</b>
</li>
<ul>
<li> Hostiteľ Elasticsearch je hostiteľ, na ktorom je spustený vyhľadávací nástroj. Predvolene sa nastaví lokálny hostiteľ, ak je vyhľadávací nástroj spustený na rovnakom serveri ako aplikácia Sugar.</li>
<li> Port Elasticsearch je číslo portu, cez ktorý sa aplikácia Sugar pripojí k vyhľadávaciemu nástroju. Predvolene je to port 9200, teda predvolené nastavenie elasticsearch. </li>
</ul>
</ul><p>

Pri <b>vlastnom</b> nastavení musíte mať aj nasledujúce informácie:<br>
<ul>
<li> <b>Adresa URL, ktorá sa po inštalácii použije na získanie prístupu k inštancii Sugar</b>.
Táto adresa URL by mala zahŕňať webový server alebo názov zariadenia a IP adresu.<br><br></li>
<li> [Voliteľne] <b>Cesta k adresáru s informáciami o reláciách</b>, pokiaľ chcete používať vlastný adresár relácií pre informácie o aplikácie Sugar, aby sa zabránilo nedostatočnému zabezpečeniu dát relácie na zdieľaných serveroch.<br><br></li>
<li> [Voliteľne] <b>Cesta k vlastnému adresáru denníkov</b>, pokiaľ chcete prepísať predvolený adresár pre denníky Sugar.<br><br></li>
<li> [Voliteľne] <b>ID aplikácie</b>, pokiaľ chcete prepísať automaticky generované ID, čím sa zabezpečí, že relácie jednej inštancie Sugar nie sú používané inými inštanciami.<br><br></li>
<li><b>Súbor znakov</b>, ktorý sa najčastejšie používa na vašej lokalite.<br><br></li></ul>
Pozrite si inštalačnú príručku, kde nájdete ďalšie informácie.",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Pred tým, ako budete pokračovať s inštaláciou, si prečítajte nasledujúce dôležité informácie. Informácie vám pomôžu určiť, či už ste alebo nie ste pripravení na inštaláciu aplikácie.',


	'LBL_WELCOME_2'						=> 'Dokumentáciu k inštalácii nájdete na stránke <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.  <BR><BR> Ak chcete kontaktovať technika podpory SugarCRM so žiadosťou o pomoc s inštaláciou, prihláste sa na <a target="_blank" href="http://support.sugarcrm.com">SugarCRM Support Portal</a> a odošlite prípad pre podporu.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Vyberte svoj ​​jazyk</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Sprievodca nastavením',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Vitajte v aplikácii SugarCRM',
	'LBL_WELCOME_TITLE'					=> 'Sprievodca nastavením SugarCRM',
	'LBL_WIZARD_TITLE'					=> 'Sprievodca nastavením Sugar:',
	'LBL_YES'							=> 'Áno',
    'LBL_YES_MULTI'                     => 'Áno – multibajtové',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Procesné úlohy pracovného postupu',
	'LBL_OOTB_REPORTS'		=> 'Spustiť vytváranie hlásení o naplánovaných úlohách',
	'LBL_OOTB_IE'			=> 'Skontrolujte poštové schránky s prichádzajúcou poštou ',
	'LBL_OOTB_BOUNCE'		=> 'Spustiť e-maily kampane v nočnom režime',
    'LBL_OOTB_CAMPAIGN'		=> 'Spustiť nočné hromadné e-mailové kampane',
	'LBL_OOTB_PRUNE'		=> 'Zredukovať databázu 1. deň v mesiaci',
    'LBL_OOTB_TRACKER'		=> 'Zredukovať tabuľky stopára',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Spustiť pripomienky prostredníctvom e-mailu',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Aktualizovať tabuľku tracker_sessions',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Čisté poradie úloh',


    'LBL_FTS_TABLE_TITLE'     => 'Zadajte nastavenia celotextového vyhľadávania',
    'LBL_FTS_HOST'     => 'Hostiteľ',
    'LBL_FTS_PORT'     => 'Port',
    'LBL_FTS_TYPE'     => 'Typ vyhľadávača',
    'LBL_FTS_HELP'      => 'Ak chcete povoliť celotextové vyhľadávanie, zadajte hostiteľa a port, na ktorom je umiestnený vyhľadávač. Služba Sugar už má zabudovanú podporu pre nástroj elasticsearch.',
    'LBL_FTS_REQUIRED'    => 'Vyžaduje sa Elastic Search.',
    'LBL_FTS_CONN_ERROR'    => 'Nepodarilo sa pripojiť k serveru s fulltextovým vyhľadávaním, prosím, overte svoje nastavenia.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Nie je dostupná žiadna verzia servera s fulltextovým vyhľadávaním, prosím, overte svoje nastavenia.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Bola zistená nepodporovaná verzia Elastic search. Použite verziu: %s',

    'LBL_PATCHES_TITLE'     => 'Nainštalujte najnovšie opravy',
    'LBL_MODULE_TITLE'      => 'Inštalácia jazykových balíkov',
    'LBL_PATCH_1'           => 'Ak chcete tento krok preskočiť, kliknite na tlačidlo Ďalej.',
    'LBL_PATCH_TITLE'       => 'Oprava systému',
    'LBL_PATCH_READY'       => 'Nasledujúce opravy sú pripravené na inštaláciu:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "Pri pripojení k tomuto webovému serveru sa aplikácia SugarCRM spolieha pri ukladaní dôležitých informácií  na relácie PHP. Vaša PHP inštalácia nemá informácie o reláciách správne nakonfigurované.
<br><br>Bežne nesprávna konfigurácia nastane, keď direktíva <b>session.save_path</b> nesmeruje k platnému adresáru.<br><br>Opravte<a target=_new href='http://us2.php.net/manual/en/ref.session.php'> PHP konfiguráciu</a> v súbore php.ini umiestnenom tu nižšie.",
	'LBL_SESSION_ERR_TITLE'				=> 'Chyba konfigurácie PHP relácií',
	'LBL_SYSTEM_NAME'=>'Názov systému',
    'LBL_COLLATION' => 'Nastavenie zoradenia',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Uveďte názov systému pre inštanciu Sugar.',
	'LBL_PATCH_UPLOAD' => 'Vyberte súbor opravy z lokálneho počítača',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'Režim spätnej kompatibility PHP je zapnutý. Nastavte zend.ze1_compatibility_mode na možnosť Off (Vyp.) a pokračujte',

    'advanced_password_new_account_email' => array(
        'subject' => 'Nové informácie o účte',
        'description' => 'Táto šablóna sa používa, keď administrátor systému pošle nové heslo používateľovi.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Tu je vaše meno používateľa pre účet a dočasné heslo:</p><p>Meno používateľa: $contact_user_user_name </p><p>Heslo: $contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url</a></p><br><p>Po prihlásení pomocou tohto hesla sa môže zobraziť výzva, aby ste nastavili svoje vlastné heslo.</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'Tu je vaše meno používateľa pre účet a dočasné heslo:
Meno používateľa: $contact_user_user_name
Heslo: $contact_user_user_hash

$config_site_url

Po prihlásení pomocou tohto hesla sa môže zobraziť výzva, aby ste nastavili svoje vlastné heslo.',
        'name' => 'Systémom generovaný e-mail s heslom',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Obnoviť heslo k účtu',
        'description' => "Táto šablóna sa používa na odosielanie prepojenia používateľovi, na ktoré musí kliknúť a obnoviť heslo používateľa účtu.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Nedávno ste odoslali žiadosť na $contact_user_pwd_last_changed, aby ste mohli obnoviť svoje heslo k účtu. </p><p>Kliknite na prepojenie nižšie a obnovte svoje heslo:</p><p> <a href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'Nedávno ste odoslali žiadosť na $contact_user_pwd_last_changed, aby ste mohli obnoviť svoje heslo k účtu.

Kliknite na prepojenie nižšie a obnovte svoje heslo:

$contact_user_link_guid',
        'name' => 'E-mail v prípade zabudnutého hesla',
        ),
);
