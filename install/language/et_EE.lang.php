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
	'LBL_BASIC_SEARCH'					=> 'Põhiotsing',
	'LBL_ADVANCED_SEARCH'				=> 'Laiendatud otsing',
	'LBL_BASIC_TYPE'					=> 'Põhitüüp',
	'LBL_ADVANCED_TYPE'					=> 'Täpsem tüüp',
	'LBL_SYSOPTS_1'						=> 'Valige mõni järgmistest süsteemi konfiguratsiooni valikutest allpool.',
    'LBL_SYSOPTS_2'                     => 'Millist tüüpi andmebaasi kasutatakse installitava Sugari eksemplari puhul?',
	'LBL_SYSOPTS_CONFIG'				=> 'Süsteemi konfiguratsioon',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Täpsusta andmebaasi tüüpi',
    'LBL_SYSOPTS_DB_TITLE'              => 'Andmebaasi tüüp',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Parandage enne jätkamist järgmised vead.',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Muutke järgmine kataloog kirjutatavaks.',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Sugar ei toeta teie DB2 (%s) versiooni. Peate installima versiooni, mis ühildub Sugari rakendusega. Vaadake toetatud DB2 versioonide osas väljalaskemärkustes olevat ühilduvusmaatriksit.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Suvandi Oracle valimisel peab teil olema installitud ja konfigureeritud Oracle’i klient.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
	'ERR_DB_OCI8_CONNECT'				=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
	'ERR_DB_OCI8_VERSION'				=> 'Sugar ei toeta teie Oracle’i (%s) versiooni. Peate installima versiooni, mis ühildub Sugari rakendusega. Vaadake toetatud Oracle’i versioonide osas väljalaskemärkustes olevat ühilduvusmaatriksit.',
    'LBL_DBCONFIG_ORACLE'               => 'Esitage andmebaasi nimi. See on vaikimisi tabeli ruum, mis teie kasutajale määratakse (SID kohast tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Müügivõimaluse päring',
	'LBL_Q1_DESC'						=> 'Müügivõimalused tüübi järgi',
	'LBL_Q2_DESC'						=> 'Müügivõimalused konto järgi',
	'LBL_R1'							=> '6 kuu pooleli müügitehingu aruanne',
	'LBL_R1_DESC'						=> 'Järgmise 6 kuu müügivõimalused kuu ja tüübi järgi',
	'LBL_OPP'							=> 'Müügivõimaluse andmemäärang ',
	'LBL_OPP1_DESC'						=> 'Siin saate muuta kohandatud päringu väljanägemist ja toimimist',
	'LBL_OPP2_DESC'						=> 'See päring paigutatakse aruande esimese päringu alla',
    'ERR_DB_VERSION_FAILURE'			=> 'Andmebaasi versiooni pole võimalik kontrollida.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Sisestage Sugari adminkasutaja kasutajanimi. ',
	'ERR_ADMIN_PASS_BLANK'				=> 'Sisestage Sugari adminkasutaja salasõna. ',

    'ERR_CHECKSYS'                      => 'Ühilduvuse kontrolli käigus leiti vigu. SugarCRM-i installimise õigesti toimimiseks rakendage järgmiselt loetletud probleemide osas asjakohaseid meetmeid ja klõpsake uuesti kontrollimise nuppu või proovige uuesti installida.',
    'ERR_CHECKSYS_CALL_TIME'            => 'Suvand Luba kõneaja viite vahelejätmine on seatud suvandile Sees (peaks olema seatud failis php.ini suvandile Väljas)',

	'ERR_CHECKSYS_CURL'					=> 'Ei leitud: Sugari planeerija töötab piiratud funktsioonidega. Meilide arhiveerimise teenus ei tööta.',
    'ERR_CHECKSYS_IMAP'					=> 'Ei leitud: Sissetulev meil ja kampaaniad (meil) nõuavad IMAP-i andmekogusid. Kumbki pole toimiv.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'MS SQL-serveri kasutamisel ei saa Magic-päringud GPC olla sisse lülitatud.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Hoiatus:',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> ' (Seadke see ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'oma php.ini failis suvandile M või suuremale)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Minmaalne versioon 4.1.2 – leitud: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Seansi muutujate kirjutamine ja lugemine ebaõnnestus. Installimist ei saa jätkata.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Kehtetu kataloog',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Hoiatus: ei ole kirjutatav',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Sugar ei toeta teie PHP versiooni. Peate installima versiooni, mis ühildub Sugari rakendusega. Vaadake toetatud PHP versioonide osas väljalaskemärkustes olevat ühilduvusmaatriksit. Teie versioon on ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Sugar ei toeta teie IIS-i versiooni. Peate installima versiooni, mis ühildub Sugari rakendusega. Vaadake toetatud IIS-i versioonide osas väljalaskemärkustes olevat ühilduvusmaatriksit. Teie versioon on ',
	'ERR_CHECKSYS_FASTCGI'              => 'Tuvastasime, et te ei kasuta PHP puhul FastCGI draivi vastendamist. Peate installima/konfigureerima versiooni, mis ühildub Sugari rakendusega. Vaadake toetatud versioonide osas väljalaskemärkustes olevat ühilduvusmaatriksit. Lisateabe saamiseks vt <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a> ',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'IIS-i/FastCGI sapi kasutamisel optimaalse kogemuse saamiseks seadke fastcgi.logging oma php.ini failis väärtusele 0.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Installitud mittetoetatud PHP versioon: (vers',
    'LBL_DB_UNAVAILABLE'                => 'Andmebaas pole saadaval',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Andmebaasituge ei leitud. Veenduge, et teil oleks olemas vajalikud draiverid ühe jaoks järgnevatest toetatud andmebaasi tüüpidest: MySQL, MS SQLServer, Oracle või DB2. Olenevalt teie PHP versioonist võib olla tarvis muuta laienduse kommentaar koodiks php.ini failis või õige kahendfailiga uuesti kompileerida. Lisateavet andmebaasitoe aktiivseks muutmise kohta leiate oma PHP juhendist.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Sugari rakenduseks vajaliku XML parseri teegiga seotud funktsioone ei leitud. Olenevalt teie PHP versioonist võib teil olla vaja php.ini failis laiend lahti kommenteerida või rekompileerida õige kahendfailiga. Lisateabe saamiseks vaadake PHP juhendit.',
    'LBL_CHECKSYS_CSPRNG' => 'Juhusliku arvu generaator',
    'ERR_CHECKSYS_MBSTRING'             => 'Sugari rakenduseks vajaliku mitmebaidiste stringide PHP laiendiga (mbstring) seotud funktsioone ei leitud. <br/><br/>Üldjuhul pole moodul mbstring PHP-s vaikimisi lubatud ja tuleb aktiveerida suvandiga --enable-mbstring when the PHP-kahendfaili loomisel. Lisateabe saamiseks mbstring toe lubamise kohta vaadake PHP juhendit.',
    'ERR_CHECKSYS_MCRYPT'               => "Mcrypt-moodul pole laaditud. Lisateabe saamiseks mcrypt mooduli laadimise kohta vaadake PHP juhendit.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'Säte session.save_path teie php konfiguratsioonifailis (php.ini) pole seadistatud või on seatud kaustale, mida pole olemas. Teil võib olla vaja seadistada säte save_path failis php.ini või kontrollida, kas kausta sätted on suvandis save_path olemas.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Säte session.save_path teie php konfiguratsioonifailis (php.ini) on seatud kaustale, mis pole kirjutatav. Tehke kausta kirjutatavaks muutmiseks vajalikud toimingud. <br>Teie operatsioonisüsteemist olenevalt võib selleks olla vaja lubasid muuta, käivitades chmodi 766 või paremklõpsates faili nimel, et pääseda atribuutide juurde ja eemaldada kirjutuskaitstuse suvandi märge.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Konfiguratsioonifail on olemas, kuid pole kirjutatav. Tehke faili kirjutatavaks muutmiseks vajalikud toimingud. Teie operatsioonisüsteemist olenevalt võib selleks olla vaja lubasid muuta, käivitades chmodi 766 või paremklõpsates faili nimel, et pääseda atribuutide juurde ja eemaldada kirjutuskaitstuse suvandi märge.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Konfiguratsiooni alistamise fail on olemas, kuid pole kirjutatav. Tehke faili kirjutatavaks muutmiseks vajalikud toimingud. Teie operatsioonisüsteemist olenevalt võib selleks olla vaja lubasid muuta, käivitades chmodi 766 või paremklõpsates faili nimel, et pääseda atribuutide juurde ja eemaldada kirjutuskaitstuse suvandi märge.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Kohandatud kataloog on olemas, kuid see pole kirjutatav. Teie operatsioonisüsteemist olenevalt võib teil olla vaja selle lubasid muuta (chmod 766) või sellel paremklõpsata ja eemaldada kirjutuskaitstuse suvandi märge. Tehke faili kirjutatavaks muutmiseks vajalikud toimingud.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "Allpool loetletud failid või kataloogid pole kirjutatavad või on puudu ja neid ei saa luua. Teie operatsioonisüsteemist olenevalt võib teil selle parandamiseks olla vaja failide või emakataloogi (chmod 755) lubasid muuta või emakataloogil paremklõpsata ja eemaldada kirjutuskaitstuse suvandi märge ja rakendada see kõigile alamkataloogidele.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Turvarežiim on sisse lülitatud (võite soovida selle failis php.ini keelata)',
    'ERR_CHECKSYS_ZLIB'					=> 'ZLib-tuge ei leitud: SugarCRM-i tohutu jõudlus tuleneb zlib-tihendusest.',
    'ERR_CHECKSYS_ZIP'					=> 'ZIP-tuge ei leitud: SugarCRM vajab tihendatud failide töötlemiseks ZIP-tuge.',
    'ERR_CHECKSYS_BCMATH'				=> 'BCMATH-tuge ei leitud: SugarCRM vajab arbitraarse täpsuse arvutamiseks BCMATH-tuge.',
    'ERR_CHECKSYS_HTACCESS'             => 'Katse .htaccess ümberkirjutuste puhul ebaõnnestus. See tähendab üldjuhul, et teil pole Sugari kataloogi puhul alistamise lubamine seadistatud.',
    'ERR_CHECKSYS_CSPRNG' => 'CSPRNG-i erand',
	'ERR_DB_ADMIN'						=> 'Esitatud andmebaasi administraatori kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv kasutajanimi ja salasõna. (Tõrge: ',
    'ERR_DB_ADMIN_MSSQL'                => 'Esitatud andmebaasi administraatori kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv kasutajanimi ja salasõna.',
	'ERR_DB_EXISTS_NOT'					=> 'Täpsustatud andmebaasi ei eksisteeri.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'Konfiguratsiooniandmetega andmebaas on juba olemas. Valitud andmebaasiga installi käivitamiseks taaskäivitage install ja valige suvand: „Kas kukutada ja luua olemasolevad SugarCRM-i tabelid uuesti?” Täiendamiseks kasutade administaatori konsoolis olevat täiendamisviisardit. Lugege täiendamisdokumente, mis asuvad aadressil <a href="http://www.sugarforge.org/content/downloads/" target="_new">here</a>.',
	'ERR_DB_EXISTS'						=> 'Esitatud andmebaasi nimi on juba olemas – teist samanimelist ei saa luua.',
    'ERR_DB_EXISTS_PROCEED'             => 'Esitatud andmebaasi nimi on juba olemas. Saate:<br>1. Vajutada tagasinuppui ja valida uue andmebaasi nime <br>2. Klõpsata edasi ja jätkata, kuid kõik selles andmebaasis olevad tabelid selles andmebaasis kukutatakse. <strong>See tähendab, et teie tabelid ja andmed pühitakse minema.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Hosti nimi ei saa jääda tühjaks.',
	'ERR_DB_INVALID'					=> 'Valitud on kehtetu andmebaasi tüüp.',
	'ERR_DB_LOGIN_FAILURE'				=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Esitatud andmebaasi host, kasutajanimi ja/või salasõna on kehtetu ja andmebaasiga ei õnnestu ühendust luua. Sisestage kehtiv host, kasutajanimi ja salasõna',
	'ERR_DB_MYSQL_VERSION'				=> 'Sugar ei toeta teie MySQL-i versiooni (%s). Peate installima versiooni, mis ühildub Sugari rakendusega. Vaadake toetatud MySQL-i versioonide osas väljalaskemärkustes olevat ühilduvusmaatriksit.',
	'ERR_DB_NAME'						=> 'Andmebaasi nimi ei saa jääda tühjaks.',
	'ERR_DB_NAME2'						=> "Andmebaasi nimi ei saa sisaldada märke \\, / või .",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Andmebaasi nimi ei saa sisaldada märke \\, / või .",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Andmebaasi nimi ei saa alata numbriga, märgiga # või @ ega saa sisaldada tühikut ega märke \", ', *, /, \\, ?, :, <, >, &, ! või -",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Andmebaasi nimi võib sisaldada ainult tähtnumbrilisi märke ja sümboleid #, _, -, :, ., / või $",
	'ERR_DB_PASSWORD'					=> 'Sugari andmebaasi administraatorile esitatud paroolid ei kattu. Sisestage samad paroolid parooli väljadele uuesti.',
	'ERR_DB_PRIV_USER'					=> 'Esitage andmebaasi administaatori kasutajanimi. Kasutaja on nõutav esialgseks ühenduseks andmebaasiga.',
	'ERR_DB_USER_EXISTS'				=> 'Sugari andmebaasi kasutajanimi on juba olemas – teist samanimelist ei saa luua. Sisestage uus kasutajanimi.',
	'ERR_DB_USER'						=> 'Sisestage Sugari andmebaasi administraatori kasutajanimi.',
	'ERR_DBCONF_VALIDATION'				=> 'Parandage enne jätkamist järgmised vead.',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'Sugari andmebaasi puhul esitatud paroolid ei kattu. Sisestage samad paroolid parooli väljadele uuesti.',
	'ERR_ERROR_GENERAL'					=> 'Avaldusid järgmised vead:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Ei saa kustutada faili:',
	'ERR_LANG_MISSING_FILE'				=> 'Ei leia faili:',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Keelepaketi faili ei leitud:',
	'ERR_LANG_UPLOAD_1'					=> 'Üleslaadimisel ilmnesid vead. Palun proovige uuesti.',
	'ERR_LANG_UPLOAD_2'					=> 'Keelepaketid peavad olema ZIP-arhiivis.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP ei suutnud teisaldada ajutist faili täiendamiskataloogi.',
	'ERR_LICENSE_MISSING'				=> 'Puuduvad nõutud väljad',
	'ERR_LICENSE_NOT_FOUND'				=> 'Litsentsifaili ei leitud!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Esitatud logi kataloog on kehtetu.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Esitatud logi kataloog ei ole kirjutatav kataloog.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Logi kataloog on nõutav, kui soovite luua enda oma.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Skripti pole võimalik otse töödelda.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Üksisjutumärki ei saa kasutada ',
	'ERR_PASSWORD_MISMATCH'				=> 'Sugari adminkasutajale esitatud paroolid ei kattu. Sisestage samad paroolid parooli väljadele uuesti.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Faili <span class=stop>config.php</span> ei saa kirjutada.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Saate seda installimist jätkata, luues faili config.php käsitsi ja kleepides alloleva konfiguratsiooniteabe faili config.php. Siiski <strong>peate </strong>looma faili config.php enne järgmise sammuga jätkamist.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Kss teil oli meeles luua fail config.php?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Hoiatus: faili config.php ei saa kirjutada. Veenduge, et see on olemas.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Faili ',
	'ERR_PERFORM_HTACCESS_2'			=> ' ei saa kirjutada.',
	'ERR_PERFORM_HTACCESS_3'			=> 'Kui soovite kaitsta logifailile juurdepääsu brauseri kaudu, looge logikataloogi fail .htaccess järgmise reaga:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>Internetiühendust ei õnnestunud tuvastada.</b> Kui teil on ühendus, külastage lehte <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> SugarCRM-i registreerimiseks. Teavitades meid veidi oma ettevõtte plaanidest SugarCRM-i kasutamise osas, saame tagada, et pakume alati teie ärivajadustele asjakohast rakendust.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Esitatud seansi kataloog pole kehtiv kataloog.',
	'ERR_SESSION_DIRECTORY'				=> 'Esitatud seansi kataloog ei ole kirjutatav kataloog.',
	'ERR_SESSION_PATH'					=> 'Seansi tee on nõutav, kui soovite määrata enda oma.',
	'ERR_SI_NO_CONFIG'					=> 'Te ei kaasanud dokumendi juurde config_si.php või ei määratlenud suvandit $sugar_config_si failis config.php',
	'ERR_SITE_GUID'						=> 'Rakenduse ID on nõutav, kui soovite määrata enda oma.',
    'ERROR_SPRITE_SUPPORT'              => "Hetkel pole võimalik GD teeki leida, mistõttu ei saa te CSS-spraidi funktsiooni kasutada.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Hoiatus: vähemalt 6 MB failide üleslaadimise võimaldamiseks tuleks teie PHP konfiguratsiooni muuta.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Üleslaaditava faili suurus',
	'ERR_URL_BLANK'						=> 'Esitage Sugari eksemplari põhi-URL.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Ei leitud järgmise installikirjet:',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Üleslaaditud fail ei ühildu selle Sugari versiooniga (Professional, Enterprise või Ultimate edition): ',
	'ERROR_LICENSE_EXPIRED'				=> "Viga: teie litsents aegus ",
	'ERROR_LICENSE_EXPIRED2'			=> " päev(a) tagasi. Uue litsentsimisvõtme sisestamiseks minge ekraanil Admin ossa <a href='index.php?action=LicenseSettings&module=Administration'>'Litsentsihaldus</a>. Kui te litsentsivõtme aegumisel 30 päeva jooksul uut litsentsivõtit ei sisesta, ei saa te enam sellesse rakendusse sisse logida.",
	'ERROR_MANIFEST_TYPE'				=> 'Manifestifail peab määratlema paketi tüübi.',
	'ERROR_PACKAGE_TYPE'				=> 'Manifestifail määratleb tuvastamatu paketi tüübi',
	'ERROR_VALIDATION_EXPIRED'			=> "Viga: teie valideerimisvõti aegus ",
	'ERROR_VALIDATION_EXPIRED2'			=> " päev(a) tagasi. Uue litsentsimisvõtme sisestamiseks minge ekraanil Admin ossa <a href='index.php?action=LicenseSettings&module=Administration'>'Litsentsihaldus</a>. Kui te litsentsivõtme aegumisel 30 päeva jooksul uut valideerimisvõtit ei sisesta, ei saa te enam sellesse rakendusse sisse logida.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Üleslaaditud fail ei ühildu selle Sugari versiooniga: ',

	'LBL_BACK'							=> 'Tagasi',
    'LBL_CANCEL'                        => 'Tühista',
    'LBL_ACCEPT'                        => 'Nõustun',
	'LBL_CHECKSYS_1'					=> 'SugarCRM-i installi õigesti toimimiseks tagage, et kõik järgmiselt loetletud süsteemi kontrolli üksused on rohelised. Kui mõni neist on punane, tehke nende parandamiseks vajalikke toiminguid.<BR><BR> Abi saamiseks nende süsteemikontrollide osas külastage lehte <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.',
	'LBL_CHECKSYS_CACHE'				=> 'Kirjutatava vahemälu alamkataloogid',
    'LBL_DROP_DB_CONFIRM'               => 'Esitatud andmebaasi nimi on juba olemas. <br>Saate:<br>1. Vajutada nuppu Tühista ja valida uue andmebaasi nime <br>2. Klõpsata nuppu Nõustun ja jätkata. Kõik selles andmebaasis olevad tabelid kukutatakse. <strong>See tähendab, et teie tabelid ja olemasolevad andmed pühitakse minema.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'PHP suvand Luba kõneaja viite vahelejätmine on seatud suvandile Väljas',
    'LBL_CHECKSYS_COMPONENT'			=> 'Komponent',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Valikulised komponendid',
	'LBL_CHECKSYS_CONFIG'				=> 'Kirjutatav SugarCRM-i konfiguratsioonifail (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Kirjutatav SugarCRM-i konfiguratsioonifail (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'cURL-moodul',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Seansi salvestamise tee säte',
	'LBL_CHECKSYS_CUSTOM'				=> 'Kirjutatav kohandatav kataloog',
	'LBL_CHECKSYS_DATA'					=> 'Kirjutatavad andmete alamkataloogid',
	'LBL_CHECKSYS_IMAP'					=> 'IMAP-moodul',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic-päringud GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'MB stringide moodul',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt-moodul',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (piirang puudub)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (piiramatu)',
	'LBL_CHECKSYS_MEM'					=> 'PHP mälu piirang',
	'LBL_CHECKSYS_MODULE'				=> 'Kirjutatavate moodulite alamkataloogid ja failid',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'MySQL-i versioon',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Pole saadaval',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> 'Teie PHP konfiguratsioonifaili (php.ini) asukoht:',
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (ver',
	'LBL_CHECKSYS_PHPVER'				=> 'PHP versioon',
    'LBL_CHECKSYS_IISVER'               => 'IIS-i versioon',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Ülekontroll',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'PHP turvarežiim välja lülitatud',
	'LBL_CHECKSYS_SESSION'				=> 'Kirjutatava seansi salvestamise tee (',
	'LBL_CHECKSYS_STATUS'				=> 'Olek',
	'LBL_CHECKSYS_TITLE'				=> 'Süsteemikontrolliga nõusolek',
	'LBL_CHECKSYS_VER'					=> 'Leitud: ( ver',
	'LBL_CHECKSYS_XML'					=> 'XML-sõelumine',
	'LBL_CHECKSYS_ZLIB'					=> 'ZLIB tihendusmoodul',
	'LBL_CHECKSYS_ZIP'					=> 'ZIP-i käsitlemismoodul',
    'LBL_CHECKSYS_BCMATH'				=> 'Arbitraarse täpsuse arvutamise moodul',
    'LBL_CHECKSYS_HTACCESS'				=> 'Alistamise lubamise seadistus faili .htaccess puhul',
    'LBL_CHECKSYS_FIX_FILES'            => 'Enne jätkamist parandage järgmised failid või kataloogid:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Enne jätkamist parandage järgmised mooduli kataloogid ja nende all olevad failid:',
    'LBL_CHECKSYS_UPLOAD'               => 'Kirjutatav üleslaadimise kataloog',
    'LBL_CLOSE'							=> 'Sulge',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'on loodud',
	'LBL_CONFIRM_DB_TYPE'				=> 'Andmebaasi tüüp',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Kinnitage allolevad sätted. Kui soovite mõnda väärtust muuta, klõpsake redigeerimseks nuppu Tagasi. Installimise alustamiseks klõpsake nuppu Edasi.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Litsentsi info',
	'LBL_CONFIRM_NOT'					=> 'pole',
	'LBL_CONFIRM_TITLE'					=> 'Kinnita sätted',
	'LBL_CONFIRM_WILL'					=> 'on',
	'LBL_DBCONF_CREATE_DB'				=> 'Loo andmebaas',
	'LBL_DBCONF_CREATE_USER'			=> 'Loo kasutaja',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Hoiatus: kõik Sugari andmed kustutatakse, <br>kui see ruut on märgitud.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Kas kukutada ja luua olemasolevad SugarCRM-i tabelid uuesti?',
    'LBL_DBCONF_DB_DROP'                => 'Kukuta tabelid',
    'LBL_DBCONF_DB_NAME'				=> 'Andmebaasi nimi',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Sugari andmebaasi kasutaja parool',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Sisestage Sugari andmebaasi kasutaja parool uuesti',
	'LBL_DBCONF_DB_USER'				=> 'Sugari andmebaasi kasutajanimi',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Sugari andmebaasi kasutajanimi',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Andmebaasi administraatori kasutajanimi',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Andmebaasi admini parool',
	'LBL_DBCONF_DEMO_DATA'				=> 'Kas asustada andmebaas demoandmetega?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Valige demoandmed',
	'LBL_DBCONF_HOST_NAME'				=> 'Hosti nimi',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Hosti eksemplar',
	'LBL_DBCONF_HOST_PORT'				=> 'Port',
    'LBL_DBCONF_SSL_ENABLED'            => 'Luba SSL-ühendus',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Sisestage alljärgnevalt oma andmebaasi konfiguratsiooniteave. Kui te pole kindel, mida täita, soovitame kasutada vaikeväärtusi.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Kas kasutada demoandmetes mitmebaidist teksti?',
    'LBL_DBCONFIG_MSG2'                 => 'Veebiserveri või arvuti (host) nimi, kus andmebaas asub (nt kohalik host või www.mydomain.com):',
    'LBL_DBCONFIG_MSG3'                 => 'Istallitava Sugari eksemplari andmeid sisaldava andmebaasi nimi:',
    'LBL_DBCONFIG_B_MSG1'               => 'Sugari andmebaasi seadistamiseks on vajalik selle andmebaasi administraatori kasutajanimi ja parool, kes saab luua andmebaasi tabeleid ja kasutajaid ning kes saab andmebaasi kirjutada.',
    'LBL_DBCONFIG_SECURITY'             => 'Turvalisuse eesmärgil saate Sugari andmebaasiga ühendumiseks määrata eksklusiivse kasutaja. See kasutaja peab suutma kirjutada, värskendada ja otsida andmeid selle eksemplari puhul loodavast Sugari andmebaasist. Selliseks kasutajaks võib olla eelnevalt määratletud andmebaasi administraator või saate esitada uue või olemasoleva andmebaasi kasutaja teabe.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Tee seda minu puhul',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Paku olemasolevat kasutajat',
    'LBL_DBCONFIG_CREATE_DD'            => 'Määratle kasutaja loomiseks',
    'LBL_DBCONFIG_SAME_DD'              => 'Sama kui adminkasutaja',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Täistekstotsing',
    'LBL_FTS_INSTALLED'                 => 'Installitud',
    'LBL_FTS_INSTALLED_ERR1'            => 'Täistekstotsingu võimalust pole installitud.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Saate endiselt installida, kuid ei saa kasutada täistekstotsingu funktsiooni. Selle kohta juhendi saamiseks vaadake oma andmebaasi serveri installijuhendit või võtke ühendust oma administraatoriga.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Privilegeeritud andmebaasikasutaja parool',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Kas ülaltoodud andmebaasikonto on privilegeeritud kasutaja?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'Sellel privilegeeritud andmebaasi kasutajal peavad olema andmebaasi loomiseks, tabelite kukutamiseks/loomiseks ja kasutaja loomiseks asjakohased õigused. Seda privilegeeritud andmebaasi kasutajat kasutatakse ainult nende toimingute vajaduse korral tegemiseks installiprotsessi jooksul. Võite kasutada ka sama andmebaasi kasutajat mis ülal toodud, kui sellel kasutajal on piisavad õigused.',
	'LBL_DBCONF_PRIV_USER'				=> 'Privilegeeritud andmebaasikasutaja parool',
	'LBL_DBCONF_TITLE'					=> 'Andmebaasi konfiguratsioon',
    'LBL_DBCONF_TITLE_NAME'             => 'Esita andmebaasi nimi',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Esita andmebaasi kasutaja teave',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'Pärast selle muudatuse tegemist võite installimise alustamiseks klõpsata allolevat nuppu Käivita. Pärast installimise lõpetamist soovite seada suvandi installer_locked väärtusele true.',
	'LBL_DISABLED_DESCRIPTION'			=> 'Installer on juba korra käivitatud. Turvameetmena on selle teist korda käivitamine keelatud. Kui olete täiesti kindel, et soovite selle uuesti käivitada, minge faili config.php ja leidke (või lisage) muutuja nimega installer_locked ja seadke see väärtusele false. Rida peaks välja nägema järgmine:',
	'LBL_DISABLED_HELP_1'				=> 'Installiabi saamiseks külastage SugarCRM-i',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'toe foorumid',
	'LBL_DISABLED_TITLE_2'				=> 'SugarCRM-i install on keelatud',
	'LBL_DISABLED_TITLE'				=> 'SugarCRM-i install on keelatud',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Teie asukohas sagedaimini kasutatav märgistik',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Väljamineva meili sätted',
    'LBL_EMAIL_CHARSET_CONF'            => 'Väljamineva meili märgistik ',
	'LBL_HELP'							=> 'Abi',
    'LBL_INSTALL'                       => 'Installi',
    'LBL_INSTALL_TYPE_TITLE'            => 'Installimissuvandid',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Vali installi tüüp',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>Tüüpiline installimine</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>Kohandatud Installimine</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Võti on nõutav üldiseks rakenduse toimimiseks, kuid mitte installimiseks. Te ei pea sel ajal võtit sisestama, kuid peate esitama võtme pärast rakenduse installimist.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Installimiseks on vaja miinimumteavet. Soovitatav uutele kasutajatele.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Pakub installimisel seadistatavaid lisasuvandeid. Enamik neist suvanditest on saadaval ka pärast installimist admini ekraanidel. Soovitatav edasijõudnud kasutajatele.',
	'LBL_LANG_1'						=> 'Sugaris muu kui vaikekeele kasutamiseks (US-English) saate sel etapil üles laadida ja installida keelepaketi. Saate keelepakette üles laadida ja installida ka Sugari rakendusest. Kui soovite selle etapi vahele jätta, klõpsake nuppu Edasi.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Installi',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Eemalda',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Desinstalli',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Laadi üles',
	'LBL_LANG_NO_PACKS'					=> 'ühtegi',
	'LBL_LANG_PACK_INSTALLED'			=> 'Installitud on järgmised keelepaketid: ',
	'LBL_LANG_PACK_READY'				=> 'Järgmised keelepaketid on installimiseks valmis: ',
	'LBL_LANG_SUCCESS'					=> 'Keelepakett laaditi edukalt.',
	'LBL_LANG_TITLE'			   		=> 'Keelepakett',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'Sugari installimine. See võib võtta aega mõned minutid.',
	'LBL_LANG_UPLOAD'					=> 'Laadi üles keelepakett',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Litsentsiga nõusolek',
    'LBL_LICENSE_CHECKING'              => 'Süsteemi ühilduvuse kontrollimine.',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Keskkonna kontrollimine',
    'LBL_LICENSE_CHKDB_HEADER'          => 'Andmebaasi mandaatide kontrollimine.',
    'LBL_LICENSE_CHECK_PASSED'          => 'Süsteem jättis ühilduvuskontrolli vahele.',
    'LBL_LICENSE_REDIRECT'              => 'Suunamine',
	'LBL_LICENSE_DIRECTIONS'			=> 'Kui teil on litsentsiteavet, sisestage see allolevatele väljadele.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Sisesta allalaadimisvõti',
	'LBL_LICENSE_EXPIRY'				=> 'Aegumiskuupäev',
	'LBL_LICENSE_I_ACCEPT'				=> 'Nõustun',
	'LBL_LICENSE_NUM_USERS'				=> 'Kasutajate arv',
	'LBL_LICENSE_PRINTABLE'				=> ' Prindivaade ',
    'LBL_PRINT_SUMM'                    => 'Prindi kokkuvõte',
	'LBL_LICENSE_TITLE_2'				=> 'SugarCRM-i litsents',
	'LBL_LICENSE_TITLE'					=> 'Litsentsiteave',
	'LBL_LICENSE_USERS'					=> 'Litsentsitud kasutajad',

	'LBL_LOCALE_CURRENCY'				=> 'Valuuta sätted',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Vaikevaluuta',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Valuuta sümbol',
	'LBL_LOCALE_CURR_ISO'				=> 'Valuuta kood (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> '1000 eraldaja',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Kümnendiku eraldaja',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Näide',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Tüvenumbrid',
	'LBL_LOCALE_DATEF'					=> 'Kuupäeva vaikevorming',
	'LBL_LOCALE_DESC'					=> 'Määratletud asukoha sätteid kajastatakse Sugari eksemplaris globaalselt.',
	'LBL_LOCALE_EXPORT'					=> 'Märgistik impordi/ekspordi puhul<br> <i>(meil, .csv, vCard, PDF, andmeimport)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Expordi (.csv) eraldaja',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Impordi/ekspordi sätted',
	'LBL_LOCALE_LANG'					=> 'Vaikekeel',
	'LBL_LOCALE_NAMEF'					=> 'Nime vaikevorming',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = tervitus<br />f = eesnimi<br />l = perekonnanimi',
	'LBL_LOCALE_NAME_FIRST'				=> 'David',
	'LBL_LOCALE_NAME_LAST'				=> 'Livingstone',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr',
	'LBL_LOCALE_TIMEF'					=> 'Aja vaikevorming',
	'LBL_LOCALE_TITLE'					=> 'Asukoha sätted',
    'LBL_CUSTOMIZE_LOCALE'              => 'Kohanda asukoha sätteid',
	'LBL_LOCALE_UI'						=> 'Kasutajaliides',

	'LBL_ML_ACTION'						=> 'Tegevus',
	'LBL_ML_DESCRIPTION'				=> 'Kirjeldus',
	'LBL_ML_INSTALLED'					=> 'Installi kuupäev',
	'LBL_ML_NAME'						=> 'Nimi',
	'LBL_ML_PUBLISHED'					=> 'Avaldamiskuupäev',
	'LBL_ML_TYPE'						=> 'Tüüp',
	'LBL_ML_UNINSTALLABLE'				=> 'Mitteinstallitav',
	'LBL_ML_VERSION'					=> 'Versioon',
	'LBL_MSSQL'							=> 'SQL-server',
	'LBL_MSSQL_SQLSRV'				    => 'SQL-server (Microsoft SQL-serveri draiver PHP puhul)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (mysqli laiend)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Edasi',
	'LBL_NO'							=> 'Ei',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Saidi admini parooli seadistamine',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'auditi tabel / ',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Sugari konfiguratsioonifaili loomine',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Andmebaasi loomine</b> ',
	'LBL_PERFORM_CREATE_DB_2'			=> ' <b>sees</b> ',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Andmebaasi kasutajanime ja parooli loomine',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Sugari vaikeandmete loomine',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Andmebaasi kasutajanime ja parooli loomine kohaliku hosti puhul',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Sugari seosetabelite loomine',
	'LBL_PERFORM_CREATING'				=> 'loomine /',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Vaikearuannete loomine',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Planeerija vaiketööde loomine',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Vaikesätete sisestamine',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Vaikekasutajate loomine',
	'LBL_PERFORM_DEMO_DATA'				=> 'Andmebaasi tabelite asustamine demoandmetega (see võib võtta veidi aega)',
	'LBL_PERFORM_DONE'					=> 'tehtud<br>',
	'LBL_PERFORM_DROPPING'				=> 'kukutamine / ',
	'LBL_PERFORM_FINISH'				=> 'Lõpeta',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Litsentsiteabe uuendamine',
	'LBL_PERFORM_OUTRO_1'				=> 'Sugari seadistamine',
	'LBL_PERFORM_OUTRO_2'				=> 'on lõpetatud!',
	'LBL_PERFORM_OUTRO_3'				=> 'Aeg kokku:',
	'LBL_PERFORM_OUTRO_4'				=> 'sekundit.',
	'LBL_PERFORM_OUTRO_5'				=> 'Hinnanguline mälukasutus:',
	'LBL_PERFORM_OUTRO_6'				=> ' baiti.',
	'LBL_PERFORM_OUTRO_7'				=> 'Teie süsteem on nüüd installitud ja kasutamiseks konfigureeritud.',
	'LBL_PERFORM_REL_META'				=> 'seose meta ... ',
	'LBL_PERFORM_SUCCESS'				=> 'Õnnestus!',
	'LBL_PERFORM_TABLES'				=> 'Sugari rakenduse tabelite, audititabelite ja seoste metaandmete loomine',
	'LBL_PERFORM_TITLE'					=> 'Seadista',
	'LBL_PRINT'							=> 'Prindi',
	'LBL_REG_CONF_1'					=> 'Täitke allolev lühivorm SugarCRM-ilt tooteteavituste, koolitusuudiste, eripakkumiste ja eriürituste kutsete saamiseks. Me ei müü, rendi, jaga ega levita muul moel siin toodud teavet kolmandatele osapooltele.',
	'LBL_REG_CONF_2'					=> 'Teie nimi ja meiliaadress on ainukesed registreerimiseks nõutavad väljad. Kõik muud väljad on valikulised, kuid väga kasulikud. Me ei müü, rendi, jaga ega levita muul moel siin toodud teavet kolmandatele osapooltele.',
	'LBL_REG_CONF_3'					=> 'Täname registreerumise eest. SugarCRM-i sisselogimiseks klõpsake nuppu Lõpeta. Esmakordselt sisse logimisel peate kasutama kasutajanime admin ja 2. etapis sisestatud parooli.',
	'LBL_REG_TITLE'						=> 'Registreerimine',
    'LBL_REG_NO_THANKS'                 => 'Tänan ei',
    'LBL_REG_SKIP_THIS_STEP'            => 'Jäta see samm vahele',
	'LBL_REQUIRED'						=> '* Kohustuslik väli',

    'LBL_SITECFG_ADMIN_Name'            => 'Sugari rakenduse admini nimi',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Sisestage Sugari admini kasutaja parool uuesti',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Hoiatus: see alistab mis tahes varasema installi admini parooli.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Sugari admini kasutaja parool',
	'LBL_SITECFG_APP_ID'				=> 'Rakenduse ID',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Valimisel peate automaatselt loodud ID alistamiseks esitama rakenduse ID. ID tagab, et ühe Sugari eksemplari seansse ei kasutaks muud eksemplarid. Kui teil on Sugari installide klaster, peab neil kõigil olema sama rakenduse ID.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Esita oma rakenduse ID',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Valimisel peate Sugari logi puhul vaikekataloogi alistamiseks määratlema logikataloogi. Logifaili asukohast olenemata on sellele juurdepääs veebibrauseri kaudu piiratud .htaccess ümbersuunamisega.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Kasuta kohandatud logikataloogi',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Valimisel peate Sugari seansi teabe salvestamiseks esitama turvalise kausta. Seda saab teha seansi andmete ründealdiduse takistamiseks jagatud serverites.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Kasuta Sugari puhul kohandatud seansi kataloogi',
	'LBL_SITECFG_DIRECTIONS'			=> 'Sisestage allapoole oma saidi konfiguratsiooniteave. Kui te pole väljades kindel, soovitame kasutada vaikeväärtusi.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Parandage enne jätkamist järgmised vead.</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Logi kataloog',
	'LBL_SITECFG_SESSION_PATH'			=> 'Tee seansi kataloogi<br>(peab olema kirjutatav)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Vali turvasuvandid',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'Valimisel kontrollib süsteem regulaarselt rakenduse värskendatud versioone.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Kas kontrollida värskendusi automaatselt?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Sugari värskenduste konfiguratsioon',
	'LBL_SITECFG_TITLE'					=> 'Saidi konfiguratsioon',
    'LBL_SITECFG_TITLE2'                => 'Tuvasta administraatorkasutaja',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Saidi turvalisus',
	'LBL_SITECFG_URL'					=> 'Sugari eksemplari URL',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Kas kasutada vaikeväärtusi?',
	'LBL_SITECFG_ANONSTATS'             => 'Kas saata anonüümse kasutaja statistika?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'Valimisel saadab Sugar <b>anonüümse</b> statistika teie installi kohta ettevõttele SugarCRM Inc. iga kord, kui teie süsteem uute versioonide saadavust kontrollib. See teave aitab meil paremini mõista, kuidas rakendust kasutatakse ja juhendada tootetäienduste tegemisel.',
    'LBL_SITECFG_URL_MSG'               => 'Sisestage URL, mida kasutatakse Sugari eksemplari juurde pääsemiseks pärast installimist. URL-i kasutatakse ka alusena Sugari rakenduse lehtede URL-ide puhul. URL peaks sisaldama veebiserveri või arvuti nime või IP-aadressi.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Sisestage süsteemi nimi. See nimi kuvatakse brauseri tiitliribal, kui kasutajad Sugari rakendust külastavad.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Pärast installimist peate Sugari eksemplari sisselogimiseks kasutama Sugar admini kasutajat (vaikimisi kasutajanimi = admin). Sisestage selle administraatorist kasutaja parool. Seda parooli saab pärast esmakordset sisselogimist muuta. Võite lisaks esitatud vaikeväärtusele sisestada ka muu administraatori kasutajanime.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Valige süsteemi põimimise (sortimise) sätted. Need sätted loovad tabelid konkreetse teie kasutatava keelega. Kui teie keele puhul pole erisätteid vaja, kasutage vaikeväärtust.',
    'LBL_SPRITE_SUPPORT'                => 'Sprite tugi',
	'LBL_SYSTEM_CREDS'                  => 'Süsteemi mandaadid',
    'LBL_SYSTEM_ENV'                    => 'Süsteemi keskkond',
	'LBL_START'							=> 'Käivita',
    'LBL_SHOW_PASS'                     => 'Näita paroole',
    'LBL_HIDE_PASS'                     => 'Peida paroolid',
    'LBL_HIDDEN'                        => '<i>(peidetud)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Vali oma keel</b>',
	'LBL_STEP'							=> 'Samm',
	'LBL_TITLE_WELCOME'					=> 'Tere tulemast SugarCRM-i ',
	'LBL_WELCOME_1'						=> 'See installer loob SugarCRM-i andmebaasitabelid ja seadistab konfiguratsioonimuutjad, mille peate käivitama. Kogu protsessiks peaks kuluma umbes kümme minutit.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Kas olete installimiseks valmis?',
    'REQUIRED_SYS_COMP' => 'Nõutavad süsteemikomponendid',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Enne alustamist veenduge, et teil on toetatud versioonid  järgmiste süsteemikomponentide puhul.<br>
<ul>
<li> Andmebaas/andmebaasi haldussüsteem (näited: MySQL, SQL-server, Oracle, DB2)</li>
<li> Veebiserver (Apache, IIS)</li>
<li> Elasticsearch</li>
</ul>
Installitava Sugari versiooni ühilduvate süsteemikomponentide osas vaadake väljalaskemärkustes olevat ühilduvusmaatriksit.<br>',
    'REQUIRED_SYS_CHK' => 'Algne süsteemi kontroll',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Installimisprotsessi alustamisel tehakse süsteemi kontroll veebiserveris, kus asuvad Sugari failid, tagamaks, et süsteem on õigesti konfigureeritud ja sel on installimise lõpetamiseks kõik vajalikud komponendid. <br><br>
Süsteem kontrollib kõike järgmist.<br>
<ul>
<li><b>PHP versioon</b> &#8211; peab rakendusega ühilduma</li>
<li><b>Seansi muutujad</b> &#8211; peavad õigesti töötama</li>
<li> <b>MB stringid</b> &#8211; peavad olema installitud ja failis php.ini lubatud</li>

<li> <b>Andmebaasi tugi</b> &#8211; peab olema MySQL-i, SQL-serveri, Oracle\'i või DB2 puhul olemas</li>

<li> <b>Config.php</b> &#8211; peab olemas olema ja sel peavad olema asjakohased load selle kirjutatavaks muutmiseks</li>
<li>Järgmised Sugari failid peavad olema kirjutatavad:<ul><li><b>/kohandatud</li>
<li>/vahemälu</li>
<li>/moodulid</li>
<li>/üleslaadimine</b></li></ul></li></ul>
Kontrolli nurjumisel ei saa te installimist jätkata. Kuvatakse tõrketeade, milles selgitatakse, miks teie süsteem kontrolli ei läbinud.
Pärast mis tahes muudatuste tegemist saate installimise jätkamiseks süsteemi kontrolli uuesti läbida.<br>',
    'REQUIRED_INSTALLTYPE' => 'Tüüpiline või kohandatud installimine',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Pärast süsteemi kontrollimist saate valida kas tüüpilise või kohandatud installimise.<br><br>Nii <b>tüüpilise</b> kui ka <b>kohandatud</b> installimise puhul peate teadma järgmist.<br>
<ul>
<li> <b>Andmebaasi tüüp</b>, mis majutab Sugari andmeid <ul><li>Ühilduva andmebaasi tüübid: MySQL, MS SQL-server, Oracle, DB2.<br><br></li></ul></li>
<li> <b>Veebiserveri nimi</b> või arvuti (host), kus andmebaas asub
<ul><li>Selleks võib olla <i>localhost</i>, kui andmebaas asub teie kohalikus arvutis või samas veebiserveris või arvutis kui teie Sugari failid.<br><br></li></ul></li>
<li><b>Andmebaasi nimi</b>, mida sooviksite kasutada Sugari andmete majutamiseks</li>
<ul>
<li> Teil võib andmebaas, mida kasutada soovite, juba olemas olla. Olemasoleva andmebaasi nime esitamisel kukutatakse andmebaasis olevad tabelid installimisel Sugari andmebaasi skeemi määratlemisel.</li>
<li> Kui teil veel andmebaasi pole, kasutatakse esitatud nime uue, installimisel eksemplari puhul loodava andmebaasi puhul.<br><br></li>
</ul>
<li><b>Andmebaasi administraatori kasutajanimi ja parool</b> <ul><li>Andmebaasi administraator peaks suutma luua tabeleid ja kasutajaid ning andmebaasi kirjutada.</li><li>Selle teabe saamiseks võib teil olla vaja võtta ühendust oma andmebaasi administraatoriga, kui andmebaas ei asu teie kohalikus arvutis ja/või kui teie pole andmebaasi administraator.<br><br></ul></li></li>
<li> <b>Sugari andmebaasi kasutajanimi ja parool</b>
</li>
<ul>
<li> Kasutaja võib olla andmebaasi administraator või võite esitada muu olemasoleva andmebaasi kasutaja nime. </li>
<li> Kui sooviksite selleks luua uue andmebaasi kasutaja, saate esitada uue kasutajanime ja parooli installimisprotsessi käigus ja kasutaja luuakse installimisel. </li>
</ul>
<li> <b>Elasticsearchi host ja port</b>
</li>
<ul>
<li> Elasticsearchi host on host, mis otsiongumootorit käitab. Selleks on vaikimisi localhost eeldusel, et käitate otsingumootorit samas serveris kui Sugarit.</li>
<li> Elasticsearchi port on pordi number, millega Sugar otsingumootori ühendab. Selleks on vaikimisi 9200, mis on elasticsearchi vaikeväärtus. </li>
</ul>
</ul><p>

<b>Kohandatud</b> seadistuse puhul võite soovida teada ka järgmist.<br>
<ul>
<li> <b>URL, mida kasutatakse Sugari eksemplari juurde pääsemiseks</b> pärast selle installimist.
See URL peaks sisaldama veebiserveri või arvuti nime või IP-aadressi.<br><br></li>
<li> [Optional] <b>Tee seansi kataloogini</b>, kui soovite kasutada kohandatud seansi kataloogi Sugari teabe puhul seansi andmete ründealdiduse takistamiseks jagatud serverites.<br><br></li>
<li> [Optional] <b>Tee kohandatud logi kataloogini</b>, kui soovite alistada Sugari logi vaikekataloogi.<br><br></li>
<li> [Optional] <b>Rakenduse ID</b>, kui soovite alistada automaatselt loodud ID, mis tagab, et ühe Sugari eksemplari seansse ei kasutaks muud eksemplarid.<br><br></li>
<li><b>Teie asukohas sagedaimini kasutatav märgistik</b>.<br><br></li></ul>
Lisateabe saamiseks vaadake installijuhendit.                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Enne installimisega jätkamist lugege järgmist tähtsat teavet. Teave aitab teil selgeks teha, kas olete rakenduse installimiseks valmis või mitte.',


	'LBL_WELCOME_2'						=> 'Installidokumendid leiate aadressilt <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.  <BR><BR> SugarCRM-i tehniliselt toel installimisabi küsimiseks logige sisse <a target="_blank" href="http://support.sugarcrm.com">SugarCRM-i tugiteenuste portaali/a> ja esitage tugiteenuse juhtum.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Vali oma keel</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Seadista viisard',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Tere tulemast Sugar CRM-i',
	'LBL_WELCOME_TITLE'					=> 'SugarCRM-i seadistusviisard',
	'LBL_WIZARD_TITLE'					=> 'Sugari seadistusviisard: ',
	'LBL_YES'							=> 'Jah',
    'LBL_YES_MULTI'                     => 'Jah – mitmebaidine',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Töötle töövoo ülesandeid',
	'LBL_OOTB_REPORTS'		=> 'Käivita aruande loomise plaanitud ülesanded',
	'LBL_OOTB_IE'			=> 'Vaata sissetulevaid postkaste',
	'LBL_OOTB_BOUNCE'		=> 'Käivita igaõhtused protsessi tagastatud kampaaniameilid',
    'LBL_OOTB_CAMPAIGN'		=> 'Käivita igaõhtused massmeili kampaaniad',
	'LBL_OOTB_PRUNE'		=> 'Kärbi andmebaasi kuu 1. kuupäeval',
    'LBL_OOTB_TRACKER'		=> 'Kärbi otsija tabeleid',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Käivita meili meeldetuletuse teavitused',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Uuenda tabelit tracker_sessions',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Puhasta tööde järjekord',


    'LBL_FTS_TABLE_TITLE'     => 'Paku täistekstotsingu sätteid',
    'LBL_FTS_HOST'     => 'Host',
    'LBL_FTS_PORT'     => 'Port',
    'LBL_FTS_TYPE'     => 'Otsingumootori tüüp',
    'LBL_FTS_HELP'      => 'Täistekstotsingu võimaldamiseks sisestage host ja port kohta, kus otsingumootorit majutatakse. Sugar hõlmab integreeritud elasticsearch-mootori tuge.',
    'LBL_FTS_REQUIRED'    => 'Elastic Search on nõutav.',
    'LBL_FTS_CONN_ERROR'    => 'Täistekstiotsingu serveriga ei saa ühendust luua, kontrollige oma sätteid.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Ühtegi täistekstiotsingu serveri versiooni pole saadaval, kontrollige oma sätteid.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Tuvastati toetuseta Elastic otsing. Kasutage versioone: %s',

    'LBL_PATCHES_TITLE'     => 'Installi uusimad paigad',
    'LBL_MODULE_TITLE'      => 'Installi keelepaketid',
    'LBL_PATCH_1'           => 'Kui soovite selle sammu vahele jätta, klõpsake Edasi.',
    'LBL_PATCH_TITLE'       => 'Süsteemi paik',
    'LBL_PATCH_READY'       => 'Järgmine paik/järgmised paigad on installimiseks valmis:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "SugarCRM toetub selle veebiserveriga ühendatuna PHP seansside olulise teabe salvestamisele. Teie PHP installimise seansi teave pole õigesti konfigureeritud.
<br><br>Levinud valesti konfigureerimise põhjuseks on see, et  direktiiv <b>'session.save_path'</b> ei osuta kehtivale kataloogile. <br>
<br> Parandage oma <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>PHP konfiguratsioon</a> siin allpool toodud failis php.ini.",
	'LBL_SESSION_ERR_TITLE'				=> 'PHP seansi konfiguratsiooni viga',
	'LBL_SYSTEM_NAME'=>'Süsteemi nimi',
    'LBL_COLLATION' => 'Põimimissätted',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Esitage Sugari eksemplari süsteemi nimi.',
	'LBL_PATCH_UPLOAD' => 'Valige paiga fail kohalikust arvutist',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'PHP tagasiühilduvuse režiim on sisse lülitatud. Jätkamiseks seadke zend.ze1_compatibility_mode suvandile Väljas',

    'advanced_password_new_account_email' => array(
        'subject' => 'Uue konto teave',
        'description' => 'See mall on kasutusel, kui süsteemiadministraator saadab kasutajale uue parooli.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Siin on teie konto kasutajanimi ja ajutine parool:</p><p>Kasutajanimi: $contact_user_user_name </p><p>Parool: $contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url</a></p><br><p>Pärast ülaltoodud parooliga sisselogimist võidakse teilt nõuda uue parooli määramist.</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Siin on teie konto kasutajanimi ja ajutine parool:
Kasutajanimi: $contact_user_user_name
Parool: $contact_user_user_hash

$config_site_url

Pärast ülaltoodud parooliga sisselogimist võidakse teilt nõuda uue parooli määramist.',
        'name' => 'Süsteemi loodud parooli meil',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Lähtesta oma konto parool',
        'description' => "Seda malli kasutatakse lingi saatmiseks kasutajale, mida klõpsata kasutaja konto parooli lähtestamiseks.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Nõudsite hiljuti, et $contact_user_pwd_last_changed saaks teie konto parooli lähtestada. </p><p>Parooli lähtestamiseks klõpsake allolevat linki:</p><p> <a href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Nõudsite hiljuti, et $contact_user_pwd_last_changed saaks teie parooli lähtestada.

Parooli lähtestamiseks klõpsake allolevat linki:

$contact_user_link_guid',
        'name' => 'Unustatud parooli meil',
        ),
);
