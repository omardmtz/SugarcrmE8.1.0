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
	'LBL_BASIC_SEARCH'					=> 'Osnovno pretraživanje',
	'LBL_ADVANCED_SEARCH'				=> 'Napredno pretraživanje',
	'LBL_BASIC_TYPE'					=> 'Osnovna vrsta',
	'LBL_ADVANCED_TYPE'					=> 'Napredna vrsta',
	'LBL_SYSOPTS_1'						=> 'Odaberite iz sljedećih mogućnosti konfiguracije sustava u nastavku.',
    'LBL_SYSOPTS_2'                     => 'Koja će se vrsta baze podataka upotrebljavati za instancu Sugar koju ćete sada instalirati?',
	'LBL_SYSOPTS_CONFIG'				=> 'Konfiguracija sustava',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Odredite vrstu baze podataka',
    'LBL_SYSOPTS_DB_TITLE'              => 'Vrsta baze podataka',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Prije nego što nastavite, popravite sljedeće pogreške:',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Omogućite zapisivanje u sljedećem direktoriju:',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Sugar ne podržava vašu verziju baze podataka DB2 (%s). Morate instalirati verziju koja je kompatibilna s aplikacijom Sugar. Podržane verzije baze podataka DB2 potražite u matrici kompatibilnosti u Bilješkama o izdanju.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Ako odaberete Oracle, morate imati instaliranu i konfiguriranu klijentsku bazu podataka Oracle.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
	'ERR_DB_OCI8_CONNECT'				=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
	'ERR_DB_OCI8_VERSION'				=> 'Sugar ne podržava vašu verziju baze podataka Oracle (%s). Morate instalirati verziju koja je kompatibilna s aplikacijom Sugar. Podržane verzije baze podataka Oracle potražite u matrici kompatibilnosti u Bilješkama o izdanju.',
    'LBL_DBCONFIG_ORACLE'               => 'Navedite naziv svoje baze podataka. To će biti zadani prostor tablice dodijeljen vašem korisniku (SID iz tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Upit o prilici ',
	'LBL_Q1_DESC'						=> 'Prilike po vrsti',
	'LBL_Q2_DESC'						=> 'Prilike po računu',
	'LBL_R1'							=> 'Šestomjesečno izvješće o prodajnom kanalu',
	'LBL_R1_DESC'						=> 'Prilike za sljedećih šest mjeseci razvrstane po mjesecu i vrsti',
	'LBL_OPP'							=> 'Skup podataka za prilike ',
	'LBL_OPP1_DESC'						=> 'Ovdje možete promijeniti izgled i dojam prilagođenog upita',
	'LBL_OPP2_DESC'						=> 'Ovaj upit bit će grupiran ispod prvog upita u izvješću',
    'ERR_DB_VERSION_FAILURE'			=> 'Nije moguće provjeriti verziju baze podataka.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Navedite korisničko ime za korisnika administratora Sugar. ',
	'ERR_ADMIN_PASS_BLANK'				=> 'Navedite lozinku za korisnika administratora Sugar. ',

    'ERR_CHECKSYS'                      => 'Tijekom provjere kompatibilnosti otkrivene su pogreške. Da bi vaša instalacija aplikacije SugarCRM radila ispravno, poduzmite odgovarajuće korake za rješavanje problema navedene u nastavku i pritisnite gumb za ponovnu provjeru ili pokušajte ponovno instalirati.',
    'ERR_CHECKSYS_CALL_TIME'            => 'Uključena je funkcija Allow Call Time Pass Reference (u php.ini mora biti postavljena na Isključeno)',

	'ERR_CHECKSYS_CURL'					=> 'Nije pronađeno: planer Sugar radit će s ograničenom funkcionalnošću. Usluga Arhiviranje e-pošte neće raditi.',
    'ERR_CHECKSYS_IMAP'					=> 'Nije pronađeno: dolazna e-pošta i kampanje (e-pošta) zahtijevaju biblioteke IMAP. Nijedno neće raditi.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'Funkcija Magic Quotes GPC ne može biti uključena za vrijeme upotrebe poslužitelja MS SQL.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Upozorenje: ',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> ' (postavite ovo na ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M ili veće u svojoj datoteci php.ini)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Minimalna verzija 4.1.2 – pronađeno: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Pisanje i čitanje varijabli sesije nije uspjelo. Nije moguće nastaviti s instalacijom.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Direktorij nije valjan',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Upozorenje: ne može se pisati',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Sugar ne podržava vašu verziju PHP-a. Morate instalirati verziju koja je kompatibilna s aplikacijom Sugar. Podržane verzije PHP-a potražite u matrici kompatibilnosti u Bilješkama o izdanju. Vaša je verzija ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Sugar ne podržava vašu verziju IIS-a. Morate instalirati verziju koja je kompatibilna s aplikacijom Sugar. Podržane verzije IIS-a potražite u matrici kompatibilnosti u Bilješkama o izdanju. Vaša je verzija ',
	'ERR_CHECKSYS_FASTCGI'              => 'Otkriveno je da ne upotrebljavate mapiranje rukovatelja FastCGI za PHP. Morate instalirati/konfigurirati verziju koja je kompatibilna s aplikacijom Sugar. Podržane verzije potražite u matrici kompatibilnosti u Bilješkama o izdanju. Pogledajte <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a> za detalje ',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'Za optimalno iskustvo upotrebe sučelja IIS/FastCGI sapi postavite fastcgi.logging na 0 u svojoj datoteci php.ini.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'Instalirana nepodržana verzija PHP-a: ( ver.',
    'LBL_DB_UNAVAILABLE'                => 'Baza podataka nije dostupna',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Podrška za bazu podataka nije pronađena. Provjerite imate li potrebne upravljačke programe za jednu od navedenih podržanih vrsta baza podataka: MySQL, MS SQLServer, Oracle ili DB2. Možda ćete morati ukloniti komentar s proširenja u datoteci php.ini ili je prevesti s ispravnom binarnom datotekom ovisno o vašoj verziji PHP-a. Više informacija o tome kako uključiti Podršku za bazu podataka potražite u svojem priručniku za PHP.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Funkcije povezane s bibliotekama za raščlanjivanje XML koje su potrebne za aplikaciju Sugar nisu pronađene. Možda ćete morati ukloniti komentar s proširenja u datoteci php.ini ili je prevesti s ispravnom binarnom datotekom ovisno o vašoj verziji PHP-a. Više informacija potražite u svojem priručniku za PHP.',
    'LBL_CHECKSYS_CSPRNG' => 'Generator slučajnih brojeva',
    'ERR_CHECKSYS_MBSTRING'             => 'Funkcije povezane s proširenjem Multibyte Strings PHP (mbstring) koje su potrebne za aplikaciju Sugar nisu pronađene. <br/><br/>Modul mbstring inače nije zadano uključen u PHP-u i mora ga se aktivirati s pomoću opcije --enable-mbstring nakon ugradnje binarne datoteke PHP. Više informacija o tome kako uključiti podršku za mbstring potražite u svojem priručniku za PHP.',
    'ERR_CHECKSYS_MCRYPT'               => "Modul mcrypt nije učitan. Više informacija o tome kako učitati modul mcrypt potražite u svojem priručniku za PHP.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'Postavka session.save_path u vašoj datoteci za konfiguraciju php-a (php.ini) nije postavljena ili je postavljena u mapu koja ne postoji. Možda ćete morati postaviti postavku save_path u php.ini ili provjeriti postoji li mapa postavljena u save_path.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Postavka session.save_path u vašoj datoteci za konfiguraciju php-a (php.ini) postavljena je u mapu u koju se ne može pisati. Poduzmite potrebne korake da biste omogućili pisanje u mapi. <br>Ovisno o vašem operacijskom sustavu, možda ćete morati promijeniti dopuštenja tako da pokrenete chmod 766 ili desnom tipkom miša kliknuti na naziv datoteke da biste pristupili svojstvima i poništili opciju „samo za čitanje”.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Konfiguracijska datoteka postoji, ali se u nju ne može pisati. Poduzmite potrebne korake da biste omogućili pisanje u njoj. Ovisno o vašem operacijskom sustavu, možda ćete morati promijeniti dopuštenja tako da pokrenete chmod 766 ili desnom tipkom miša kliknuti na naziv datoteke da biste pristupili svojstvima i poništili opciju „samo za čitanje”.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Zamjenska konfiguracijska datoteka postoji, ali se u nju ne može pisati. Poduzmite potrebne korake da biste omogućili pisanje u njoj. Ovisno o vašem operacijskom sustavu, možda ćete morati promijeniti dopuštenja tako da pokrenete chmod 766 ili desnom tipkom miša kliknuti na naziv datoteke da biste pristupili svojstvima i poništili opciju „samo za čitanje”.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Prilagođeni direktorij postoji, ali se u njemu ne može pisati. Ovisno o vašem operacijskom sustavu, možda ćete morati promijeniti dopuštenja u njemu (chmod 766) ili desnom tipkom miša kliknuti na njega i poništiti opciju „samo za čitanje”. Poduzmite potrebne korake da biste omogućili pisanje u datoteci.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "U datoteke ili direktorije navedene u nastavku ne može se pisati ili nedostaju i ne mogu se stvoriti. Ovisno o vašem operacijskom sustavu, da biste to ispravili, možda ćete morati promijeniti dopuštenja u datotekama ili nadređenom direktoriju (chmod 755) ili desnom tipkom miša kliknuti na nadređeni direktorij, poništiti opciju „samo za čitanje” i primijeniti je na sve podmape.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Uključen je sigurni način (možda ga želite isključiti u datoteci php.ini)',
    'ERR_CHECKSYS_ZLIB'					=> 'Podrška za ZLib nije pronađena: SugarCRM ubire velike prednosti rada s pomoću kompresije zlib.',
    'ERR_CHECKSYS_ZIP'					=> 'Podrška za ZIP nije pronađena: SugarCRM treba podršku za ZIP da bi mogao obraditi komprimirane datoteke.',
    'ERR_CHECKSYS_BCMATH'				=> 'Podrška za BCMATH nije pronađena: SugarCRM treba podršku za BCMATH za matematiku proizvoljne točnosti.',
    'ERR_CHECKSYS_HTACCESS'             => 'Test za ponovno pisanje u mapu .htaccess nije uspio. To obično znači da nemate postavljenu opciju „AllowOverride” za direktorij Sugar.',
    'ERR_CHECKSYS_CSPRNG' => 'Iznimka za CSPRNG',
	'ERR_DB_ADMIN'						=> 'Navedeno korisničko ime i/ili lozinka administratora baze podataka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano korisničko ime i lozinku. (Pogreška: ',
    'ERR_DB_ADMIN_MSSQL'                => 'Navedeno korisničko ime i/ili lozinka administratora baze podataka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano korisničko ime i lozinku.',
	'ERR_DB_EXISTS_NOT'					=> 'Navedena baza podataka ne postoji.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'Baza podataka već postoji s konfiguracijskim podacima. Da biste pokrenuli instalaciju s izabranom bazom podataka, ponovno pokrenite instalaciju i izaberite: „Odbaciti i ponovno stvoriti postojeće tablice SugarCRM?” Za nadogradnju upotrijebite čarobnjak za nadogradnju na administratorskoj konzoli. Pročitajte dokumentaciju o nadogradnji koja se nalazi <a href="http://www.sugarforge.org/content/downloads/" target="_new">ovdje</a>.',
	'ERR_DB_EXISTS'						=> 'Navedeni naziv baze podataka već postoji -- nije moguće stvoriti još jednu s istim nazivom.',
    'ERR_DB_EXISTS_PROCEED'             => 'Navedeni naziv baze podataka već postoji. Možete <br>1. pritisnuti tipku za povratak i izabrati novi naziv baze podataka <br>2. kliknuti „sljedeće” i nastaviti, ali sve postojeće tablice u toj bazi podataka bit će odbačene. <strong>To znači da ćete izgubiti tablicu i podatke.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Polje za naziv glavnog računala ne može biti prazno.',
	'ERR_DB_INVALID'					=> 'Odabrana je neispravna vrsta baze podataka.',
	'ERR_DB_LOGIN_FAILURE'				=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Navedeno glavno računalo baze podataka, korisničko ime i/ili lozinka nisu valjani te se ne može uspostaviti veza s bazom podataka. Unesite valjano glavno računalo, korisničko ime i lozinku',
	'ERR_DB_MYSQL_VERSION'				=> 'Sugar ne podržava vašu verziju baze podataka MySQL (%s). Morate instalirati verziju koja je kompatibilna s aplikacijom Sugar. Podržane verzije baze podataka MySQL potražite u matrici kompatibilnosti u Bilješkama o izdanju.',
	'ERR_DB_NAME'						=> 'Polje za naziv baze podataka ne može biti prazno.',
	'ERR_DB_NAME2'						=> "Naziv baze podataka ne može sadržavati znakove „\\”, „/” ili „.”",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Naziv baze podataka ne može sadržavati znakove „\\”, „/” ili „.”",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Naziv baze podataka ne može započinjati brojem ili znakovima „#” ili „@” te ne može sadržavati razmak ili znakove „\"”, „'”, „*”, „/”, „\\”, „?”, „:”, „<”, „>”, „&”, „!” ili „-”",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Naziv baze podataka može se sastojati samo od alfanumeričkih znakova i simbola „#”, „_”, „-”, „:”, „.”, „/” ili „$”",
	'ERR_DB_PASSWORD'					=> 'Lozinke navedene za administratora baze podataka Sugar ne podudaraju se. Ponovno unesite iste lozinke u polja za lozinke.',
	'ERR_DB_PRIV_USER'					=> 'Navedite korisničko ime administratora baze podataka. Potreban je korisnik za početno povezivanje s bazom podataka.',
	'ERR_DB_USER_EXISTS'				=> 'Korisničko ime za korisnika baze podataka Sugar već postoji -- nije moguće stvoriti još jednog s istim imenom. Unesite novo korisničko ime.',
	'ERR_DB_USER'						=> 'Unesite korisničko ime za administratora baze podataka Sugar.',
	'ERR_DBCONF_VALIDATION'				=> 'Prije nego što nastavite, popravite sljedeće pogreške:',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'Lozinke navedene za bazu podataka Sugar ne podudaraju se. Ponovno unesite iste lozinke u polja za lozinke.',
	'ERR_ERROR_GENERAL'					=> 'Pronađene su sljedeće pogreške:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Nije moguće izbrisati datoteku: ',
	'ERR_LANG_MISSING_FILE'				=> 'Nije moguće pronaći datoteku: ',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Nije pronađen nijedan jezični paket u mapi include/language: ',
	'ERR_LANG_UPLOAD_1'					=> 'Došlo je do problema pri učitavanju. Pokušajte ponovno.',
	'ERR_LANG_UPLOAD_2'					=> 'Jezični paketi moraju biti .zip arhivske datoteke.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP nije mogao premjestiti privremenu datoteku u direktorij za nadogradnju.',
	'ERR_LICENSE_MISSING'				=> 'Nedostaju obavezna polja',
	'ERR_LICENSE_NOT_FOUND'				=> 'Licencna datoteka nije pronađena!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Navedeni direktorij za zapisnike nije valjan direktorij.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'U navedeni direktorij za zapisnike nije moguće pisati.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Direktorij za zapisnik obavezan je ako želite odrediti svoj vlastiti.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Nije moguće izravno obraditi skriptu.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Nije moguće upotrijebiti jednostruki navodnik za ',
	'ERR_PASSWORD_MISMATCH'				=> 'Lozinke navedene za korisnika administratora baze podataka Sugar ne podudaraju se. Ponovno unesite iste lozinke u polja za lozinke.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Nije moguće pisati u datoteku <span class=stop>config.php</span>.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Možete nastaviti instalaciju tako da ručno stvorite datoteku config.php i zalijepite informacije o konfiguraciji u nastavku u datoteku config.php. Međutim, <strong>morate </strong>stvoriti datoteku config.php prije nastavka na sljedeći korak.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Jeste li se sjetili stvoriti datoteku config.php?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Upozorenje: nije moguće pisati u datoteku config.php. Provjerite postoji li.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Nije moguće pisati u ',
	'ERR_PERFORM_HTACCESS_2'			=> ' datoteku.',
	'ERR_PERFORM_HTACCESS_3'			=> 'Ako želite zaštititi svoju datoteku zapisnika da joj se ne može pristupiti putem preglednika, stvorite datoteku .htaccess u svojem direktoriju za zapisnike s retkom:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>Nismo mogli otkriti internetsku vezu.</b> Kada uspostavite vezu, posjetite stranicu <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> da biste se registrirali u SugarCRM. Ako nam ukratko opišete kako vaša tvrtka planira upotrebljavati SugarCRM, možemo jamčiti da ćemo uvijek pružati pravu aplikaciju za vaše poslovne potrebe.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Navedeni direktorij za sesiju nije valjani direktorij.',
	'ERR_SESSION_DIRECTORY'				=> 'U navedeni direktorij za sesiju nije moguće pisati.',
	'ERR_SESSION_PATH'					=> 'Put do sesije obavezan je ako želite odrediti svoj vlastiti.',
	'ERR_SI_NO_CONFIG'					=> 'Niste uključili config_si.php u korijen dokumenta ili niste definirali $sugar_config_si u config.php',
	'ERR_SITE_GUID'						=> 'ID aplikacije obavezan je ako želite odrediti svoj vlastiti.',
    'ERROR_SPRITE_SUPPORT'              => "Trenutačno ne možemo locirati biblioteku GD, stoga nećete moći normalno upotrebljavati CSS Sprite.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Upozorenje: trebate promijeniti svoju konfiguraciju za PHP da biste omogućili učitavanje datoteka od najmanje 6 MB.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Veličina datoteke za učitavanje',
	'ERR_URL_BLANK'						=> 'Navedite osnovni URL za instancu Sugar.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Nije moguće locirati zapis o instalaciji za',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Učitana datoteka nije kompatibilna s ovim izdanjem (Professional, Enterprise ili Ultimate izdanje) Sugara: ',
	'ERROR_LICENSE_EXPIRED'				=> "Pogreška: vaša je licenca istekla prije ",
	'ERROR_LICENSE_EXPIRED2'			=> " dan/a. Idite na stranicu <a href='index.php?action=LicenseSettings&module=Administration'>'„Upravljanje licencama”</a> na administratorskom zaslonu da biste unijeli svoj novi licencni ključ. Ako ne unesete novi licencni ključ unutar 30 dana od isteka licencnog ključa, više se nećete moći prijaviti u ovu aplikaciju.",
	'ERROR_MANIFEST_TYPE'				=> 'Datoteka manifesta mora odrediti vrstu paketa.',
	'ERROR_PACKAGE_TYPE'				=> 'Datoteka manifesta određuje neprepoznatu vrstu paketa',
	'ERROR_VALIDATION_EXPIRED'			=> "Pogreška: vaš ključ za provjeru valjanosti istekao je prije ",
	'ERROR_VALIDATION_EXPIRED2'			=> " dan/a. Idite na stranicu <a href='index.php?action=LicenseSettings&module=Administration'>„Upravljanje licencama”</a> na administratorskom zaslonu da biste unijeli svoj novi ključ za provjeru valjanosti. Ako ne unesete novi ključ za provjeru valjanosti unutar 30 dana od isteka ključa za provjeru valjanosti, više se nećete moći prijaviti u ovu aplikaciju.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Učitana datoteka nije kompatibilna s ovom verzijom aplikacije Sugar: ',

	'LBL_BACK'							=> 'Natrag',
    'LBL_CANCEL'                        => 'Odustani',
    'LBL_ACCEPT'                        => 'Prihvaćam',
	'LBL_CHECKSYS_1'					=> 'Da bi vaša instalacija aplikacije SugarCRM radila ispravno, provjerite jesu li sve stavke provjere sustava navedene u nastavku označene zelenom bojom. Ako su neke označene crvenom bojom, poduzmite potrebne korake da biste ih popravili.<BR><BR>Za pomoć pri tim provjerama sustava posjetite <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.',
	'LBL_CHECKSYS_CACHE'				=> 'Predmemorirani poddirektoriji u koje se može pisati',
    'LBL_DROP_DB_CONFIRM'               => 'Navedeni naziv baze podataka već postoji.<br>Možete: <br>1. kliknuti na gumb Odustani i izabrati novi naziv baze podataka ili <br>2. kliknuti na gumb Prihvaćam i nastaviti. Sve postojeće tablice u bazi podataka bit će odbačene. <strong>To znači da ćete izgubiti sve tablice i postojeće podatke.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'Isključena je funkcija Allow Call Time Pass Reference PHP-a',
    'LBL_CHECKSYS_COMPONENT'			=> 'Komponenta',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Neobavezne komponente',
	'LBL_CHECKSYS_CONFIG'				=> 'Konfiguracijska datoteka SugarCRM za pisanje (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Konfiguracijska datoteka SugarCRM za pisanje (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'Modul cURL',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Postavka puta spremanja sesije',
	'LBL_CHECKSYS_CUSTOM'				=> 'Prilagođeni direktorij za pisanje',
	'LBL_CHECKSYS_DATA'					=> 'Poddirektoriji podataka za pisanje',
	'LBL_CHECKSYS_IMAP'					=> 'Modul IMAP',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'Modul MB Strings',
    'LBL_CHECKSYS_MCRYPT'               => 'Modul MCrypt',
	'LBL_CHECKSYS_MEM_OK'				=> 'U redu (bez ograničenja)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'U redu (neograničeno)',
	'LBL_CHECKSYS_MEM'					=> 'Memorijsko ograničenje PHP-a',
	'LBL_CHECKSYS_MODULE'				=> 'Poddirektoriji i datoteke modula za pisanje',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'Verzija MySQL-a',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Nije dostupno',
	'LBL_CHECKSYS_OK'					=> 'U redu',
	'LBL_CHECKSYS_PHP_INI'				=> 'Lokacija vaše datoteke za konfiguraciju PHP-a (php.ini):',
	'LBL_CHECKSYS_PHP_OK'				=> 'U redu (ver. ',
	'LBL_CHECKSYS_PHPVER'				=> 'Verzija PHP-a',
    'LBL_CHECKSYS_IISVER'               => 'Verzija IIS-a',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Ponovno provjeri',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'Isključen je sigurni način PHP-a',
	'LBL_CHECKSYS_SESSION'				=> 'Put za spremanje sesije za pisanje (',
	'LBL_CHECKSYS_STATUS'				=> 'Status',
	'LBL_CHECKSYS_TITLE'				=> 'Prihvaćanje provjere sustava',
	'LBL_CHECKSYS_VER'					=> 'Pronađeno: (ver. ',
	'LBL_CHECKSYS_XML'					=> 'Raščlanjivanje XML-a',
	'LBL_CHECKSYS_ZLIB'					=> 'Modul sažimanja ZLIB',
	'LBL_CHECKSYS_ZIP'					=> 'Modul upravljanja ZIP',
    'LBL_CHECKSYS_BCMATH'				=> 'Modul matematike proizvoljne točnosti',
    'LBL_CHECKSYS_HTACCESS'				=> 'Postavljanje AllowOverride za .htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'Prije nego što nastavite, popravite sljedeće datoteke ili direktorije:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Prije nego što nastavite, popravite sljedeće direktorije modula i datoteke pod njima:',
    'LBL_CHECKSYS_UPLOAD'               => 'Direktorij za učitavanje u koji se može pisati',
    'LBL_CLOSE'							=> 'Zatvori',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'stvoreno',
	'LBL_CONFIRM_DB_TYPE'				=> 'Vrsta baze podataka',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Potvrdite postavke u nastavku. Ako želite promijeniti neku od vrijednosti, kliknite na gumb „Natrag” za uređivanje. U suprotnom kliknite na „Sljedeće” za početak instalacije.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Informacije o licenci',
	'LBL_CONFIRM_NOT'					=> 'neće',
	'LBL_CONFIRM_TITLE'					=> 'Potvrdi postavke',
	'LBL_CONFIRM_WILL'					=> 'biti',
	'LBL_DBCONF_CREATE_DB'				=> 'Stvori bazu podataka',
	'LBL_DBCONF_CREATE_USER'			=> 'Stvori korisnika',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Oprez: svi podaci Sugar bit će izbrisani<br>ako označite ovaj potvrdni okvir.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Odbaciti i ponovno stvoriti postojeće tablice Sugar?',
    'LBL_DBCONF_DB_DROP'                => 'Odbaci tablice',
    'LBL_DBCONF_DB_NAME'				=> 'Naziv baze podataka',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Korisnička lozinka za bazu podataka Sugar',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Ponovno unesite korisničku lozinku za bazu podataka Sugar',
	'LBL_DBCONF_DB_USER'				=> 'Korisničko ime za bazu podataka Sugar',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Korisničko ime za bazu podataka Sugar',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Korisničko ime za administratora baze podataka',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Lozinka za administratora baze podataka',
	'LBL_DBCONF_DEMO_DATA'				=> 'Popuniti bazu podataka demo podacima?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Odabir demo podataka',
	'LBL_DBCONF_HOST_NAME'				=> 'Naziv glavnog računala',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Instanca glavnog računala',
	'LBL_DBCONF_HOST_PORT'				=> 'Ulaz',
    'LBL_DBCONF_SSL_ENABLED'            => 'Omogući vezu SSL',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'U nastavku unesite informacije o konfiguraciji baze podataka. Ako niste sigurni što ispuniti, predlažemo da upotrijebite zadane vrijednosti.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Upotrijebiti višebajtni tekst u demo podacima?',
    'LBL_DBCONFIG_MSG2'                 => 'Naziv web-poslužitelja ili uređaja (glavno računalo) na kojem se nalazi baza podataka (npr. localhost ili www.mydomain.com):',
    'LBL_DBCONFIG_MSG3'                 => 'Naziv baze podataka koja će sadržavati podatke za instancu Sugar koju ćete sada instalirati:',
    'LBL_DBCONFIG_B_MSG1'               => 'Korisničko ime i lozinka za administratora baze podataka koji može stvoriti tablice i korisnike baze podataka i koji može pisati u bazu podataka potrebni su za postavljanje baze podataka Sugar.',
    'LBL_DBCONFIG_SECURITY'             => 'Iz sigurnosnih razloga možete navesti ekskluzivnog korisnika baze podataka za povezivanje s bazom podataka Sugar. Taj korisnik mora moći upisivati, ažurirati i dohvaćati podatke u bazi podataka Sugar koja će biti stvorena za ovu instancu. Taj korisnik može biti administrator baze podataka naveden iznad ili možete navesti informacije o novom ili postojećem korisniku baze podataka.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Učini to za mene',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Navedi postojećeg korisnika',
    'LBL_DBCONFIG_CREATE_DD'            => 'Definiraj korisnika za stvaranje',
    'LBL_DBCONFIG_SAME_DD'              => 'Isti kao i korisnik administrator',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Pretraživanje cijelog teksta',
    'LBL_FTS_INSTALLED'                 => 'Instalirano',
    'LBL_FTS_INSTALLED_ERR1'            => 'Mogućnost pretraživanja cijelog teksta nije instalirana.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Možete instalirati funkciju pretraživanja cijelog teksta, no nećete je moći upotrebljavati. Potražite upute u vodiču za instalaciju poslužitelja baze podataka ili se obratite svojem administratoru.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Lozinka ovlaštenog korisnika baze podataka',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Gore naveden račun za bazu podataka ovlašteni je korisnik?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'Ovaj ovlašteni korisnik baze podataka mora imati valjana dopuštenja za stvaranje baze podataka, odbacivanje/stvaranje tablica i stvaranje korisnika. Ovaj ovlašteni korisnik baze podataka samo će izvršavati ove zadatke prema potrebi tijekom postupka instalacije. Možete i upotrijebiti istog korisnika baze podataka kao i iznad ako taj korisnik ima dovoljno ovlaštenja.',
	'LBL_DBCONF_PRIV_USER'				=> 'Ime ovlaštenog korisnika baze podataka',
	'LBL_DBCONF_TITLE'					=> 'Konfiguracija baze podataka',
    'LBL_DBCONF_TITLE_NAME'             => 'Navedite naziv baze podataka',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Navedite informacije o korisniku baze podataka',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'Nakon ovih promjena možete kliknuti na tipku Početak u nastavku za početak instalacije. <i>Nakon dovršetka instalacije promijenite vrijednost za „installer_locked” u „točno”.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'Instalacijski program već je jednom pokrenut. Radi sigurnosti onemogućeno je pokretanje po drugi put. Ako ste sasvim sigurni da ga želite ponovno pokrenuti, idite u datoteku config.php i pronađite (ili dodajte) varijablu pod nazivom „installer_locked” i postavite je na „pogrešno”. Redak bi trebao izgledati ovako:',
	'LBL_DISABLED_HELP_1'				=> 'Za pomoć pri instalaciji posjetite forume za podršku',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'SugarCRM',
	'LBL_DISABLED_TITLE_2'				=> 'Onemogućena je instalacija aplikacije SugarCRM',
	'LBL_DISABLED_TITLE'				=> 'Onemogućena instalacija aplikacije SugarCRM',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Skup znakova najčešće upotrebljavan u vašoj regionalnoj shemi',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Postavke odlazne e-pošte',
    'LBL_EMAIL_CHARSET_CONF'            => 'Skup znakova za odlaznu e-poštu ',
	'LBL_HELP'							=> 'Pomoć',
    'LBL_INSTALL'                       => 'Instaliraj',
    'LBL_INSTALL_TYPE_TITLE'            => 'Mogućnosti instalacije',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Izaberite vrstu instalacije',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>Uobičajena instalacija</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>Prilagođena instalacija</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Ključ je potreban za općenitu funkcionalnost aplikacije, no nije potreban za instalaciju. Ne trebate sada unositi ključ, no trebat ćete ga navesti nakon instalacije aplikacije.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Zahtijeva minimalno informacija za instalaciju. Preporučeno za nove korisnike.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Pruža dodatne mogućnosti za postavljanje tijekom instalacije. Većina tih mogućnosti također je dostupna nakon instalacije na zaslonima administratora. Preporučeno za napredne korisnike.',
	'LBL_LANG_1'						=> 'Da biste u aplikaciji Sugar upotrebljavali jezik drugačiji od zadanog (US-English), možete sada učitati i instalirati jezični paket. Moći ćete učitati i instalirati jezične pakete i iz aplikacije Sugar. Ako želite preskočiti ovaj korak, kliknite Sljedeće.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Instaliraj',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Ukloni',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Deinstaliraj',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Učitaj',
	'LBL_LANG_NO_PACKS'					=> 'nema',
	'LBL_LANG_PACK_INSTALLED'			=> 'Instalirani su sljedeći jezični paketi: ',
	'LBL_LANG_PACK_READY'				=> 'Sljedeći jezični paketi spremni su za instalaciju: ',
	'LBL_LANG_SUCCESS'					=> 'Jezični paket uspješno je učitan.',
	'LBL_LANG_TITLE'			   		=> 'Jezični paket',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'U tijeku je instalacija aplikacije Sugar. To može potrajati nekoliko minuta.',
	'LBL_LANG_UPLOAD'					=> 'Učitaj jezični paket',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Prihvaćanje licence',
    'LBL_LICENSE_CHECKING'              => 'Provjera kompatibilnosti sustava.',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Provjera okruženja',
    'LBL_LICENSE_CHKDB_HEADER'          => 'Provjera vjerodajnica za bazu pod. i pretr. cijelog teksta.',
    'LBL_LICENSE_CHECK_PASSED'          => 'Sustav je prošao provjeru kompatibilnosti.',
    'LBL_LICENSE_REDIRECT'              => 'Preusmjeravanje u ',
	'LBL_LICENSE_DIRECTIONS'			=> 'Ako imate svoje informacije o licenci, unesite ih u polja u nastavku.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Unesite ključ za preuzimanje',
	'LBL_LICENSE_EXPIRY'				=> 'Datum isteka',
	'LBL_LICENSE_I_ACCEPT'				=> 'Prihvaćam',
	'LBL_LICENSE_NUM_USERS'				=> 'Broj korisnika',
	'LBL_LICENSE_PRINTABLE'				=> ' Prikaz za ispis ',
    'LBL_PRINT_SUMM'                    => 'Ispiši sažetak',
	'LBL_LICENSE_TITLE_2'				=> 'Licenca za SugarCRM',
	'LBL_LICENSE_TITLE'					=> 'Informacije o licenci',
	'LBL_LICENSE_USERS'					=> 'Licencirani korisnici',

	'LBL_LOCALE_CURRENCY'				=> 'Postavke valute',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Zadana valuta',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Simbol valute',
	'LBL_LOCALE_CURR_ISO'				=> 'Šifra valute (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> 'Razdjelnik tisućica',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Decimalni razdjelnik',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Primjer',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Značajne znamenke',
	'LBL_LOCALE_DATEF'					=> 'Zadani oblik datuma',
	'LBL_LOCALE_DESC'					=> 'Navedene regionalne postavke primjenjivat će se u cijeloj instanci Sugar.',
	'LBL_LOCALE_EXPORT'					=> 'Skup znakova za uvoz/izvoz<br> <i>(e-pošta, .csv, vCard, PDF, uvoz podataka)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Graničnik za izvoz (.csv)',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Postavke za uvoz/izvoz',
	'LBL_LOCALE_LANG'					=> 'Zadani jezik',
	'LBL_LOCALE_NAMEF'					=> 'Zadani oblik naziva',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = oslovljavanje<br />f = ime<br />l = prezime',
	'LBL_LOCALE_NAME_FIRST'				=> 'Ivan',
	'LBL_LOCALE_NAME_LAST'				=> 'Horvat',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'dr.',
	'LBL_LOCALE_TIMEF'					=> 'Zadani oblik vremena',
	'LBL_LOCALE_TITLE'					=> 'Regionalne postavke',
    'LBL_CUSTOMIZE_LOCALE'              => 'Prilagodi regionalne postavke',
	'LBL_LOCALE_UI'						=> 'Korisničko sučelje',

	'LBL_ML_ACTION'						=> 'Radnja',
	'LBL_ML_DESCRIPTION'				=> 'Opis',
	'LBL_ML_INSTALLED'					=> 'Datum instalacije',
	'LBL_ML_NAME'						=> 'Naziv',
	'LBL_ML_PUBLISHED'					=> 'Datum objave',
	'LBL_ML_TYPE'						=> 'Vrsta',
	'LBL_ML_UNINSTALLABLE'				=> 'Ne može se instalirati',
	'LBL_ML_VERSION'					=> 'Verzija',
	'LBL_MSSQL'							=> 'SQL poslužitelj',
	'LBL_MSSQL_SQLSRV'				    => 'SQL poslužitelj (Microsoft SQL Server upravljački program za PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (proširenje mysqli)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Sljedeće',
	'LBL_NO'							=> 'Ne',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Postavljanje lozinke administratora web-mjesta',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'tablica nadzora / ',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Stvaranje konfiguracijske datoteke za Sugar',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Stvaranje baze podataka</b> ',
	'LBL_PERFORM_CREATE_DB_2'			=> ' <b>na</b> ',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Stvaranje korisničkog imena i lozinke za bazu podataka...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Stvaranje zadanih podataka za Sugar',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Stvaranje korisničkog imena i lozinke baze podataka za lokalno glavno računalo...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Stvaranje tablica odnosa za Sugar',
	'LBL_PERFORM_CREATING'				=> 'stvaranje / ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Stvaranje zadanih izvješća',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Stvaranje zadanih poslova za planer',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Umetanje zadanih postavki',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Stvaranje zadanih korisnika',
	'LBL_PERFORM_DEMO_DATA'				=> 'Popunjavanje tablica baze podataka demo podacima (ovo bi moglo potrajati)',
	'LBL_PERFORM_DONE'					=> 'dovršeno<br>',
	'LBL_PERFORM_DROPPING'				=> 'odbacivanje / ',
	'LBL_PERFORM_FINISH'				=> 'Završi',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Ažuriranje informacija o licenci',
	'LBL_PERFORM_OUTRO_1'				=> 'Postavljanje aplikacije Sugar ',
	'LBL_PERFORM_OUTRO_2'				=> ' sada je dovršeno!',
	'LBL_PERFORM_OUTRO_3'				=> 'Ukupno vrijeme: ',
	'LBL_PERFORM_OUTRO_4'				=> ' s.',
	'LBL_PERFORM_OUTRO_5'				=> 'Približno memorije iskorišteno: ',
	'LBL_PERFORM_OUTRO_6'				=> ' B.',
	'LBL_PERFORM_OUTRO_7'				=> 'Vaš je sustav sada instaliran i konfiguriran za upotrebu.',
	'LBL_PERFORM_REL_META'				=> 'metapodaci o odnosu... ',
	'LBL_PERFORM_SUCCESS'				=> 'Uspjeh!',
	'LBL_PERFORM_TABLES'				=> 'Stvaranje tablica, tablica za nadzor i metapodataka o odnosu za aplikaciju Sugar',
	'LBL_PERFORM_TITLE'					=> 'Izvrši postavljanje',
	'LBL_PRINT'							=> 'Ispiši',
	'LBL_REG_CONF_1'					=> 'Ispunite kratki obrazac u nastavku da biste primali najave proizvoda, vijesti o edukaciji, posebne ponude i pozivnice na događaje od tvrtke SugarCRM. Ne prodajemo, iznajmljujemo, dijelimo ili drugačije distribuiramo podatke prikupljene ovdje trećim stranama.',
	'LBL_REG_CONF_2'					=> 'Vaše ime i adresa e-pošte jedina su polja potrebna za registraciju. Sva su ostala polja neobavezna, no nama su od velike pomoći. Ne prodajemo, iznajmljujemo, dijelimo ili drugačije distribuiramo podatke prikupljene ovdje trećim stranama.',
	'LBL_REG_CONF_3'					=> 'Hvala vam na registraciji. Kliknite na gumb Završi da biste se prijavili u aplikaciju SugarCRM. Prvi ćete se put trebati prijaviti s pomoću korisničkog imena „admin” i lozinke koju ste unijeli u drugom koraku.',
	'LBL_REG_TITLE'						=> 'Registracija',
    'LBL_REG_NO_THANKS'                 => 'Ne, hvala',
    'LBL_REG_SKIP_THIS_STEP'            => 'Preskoči ovaj korak',
	'LBL_REQUIRED'						=> '* Obavezno polje',

    'LBL_SITECFG_ADMIN_Name'            => 'Ime administratora aplikacije Sugar',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Ponovno unesite lozinku korisnika administratora aplikacije Sugar',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Oprez: ovo će zamijeniti lozinku administratora svih prethodnih instalacija.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Lozinka korisnika administratora aplikacije Sugar',
	'LBL_SITECFG_APP_ID'				=> 'ID aplikacije',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Ako odaberete ovo, morate navesti ID aplikacije da biste zamijenili automatski stvoren ID. ID osigurava da sesije jedne instance Sugar ne upotrebljavaju druge instance. Ako imate klaster instalacija aplikacije Sugar, sve moraju imati isti ID aplikacije.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Navedite svoj vlastiti ID aplikacije',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Ako odaberete ovo, morate navesti direktorij za zapisnike koji će zamijeniti zadani direktorij za zapisnik Sugar. Bez obzira na to gdje se datoteka zapisnika nalazi, pristup putem web-preglednika bit će ograničen preusmjeravanjem .htaccess.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Upotrijebi zadani direktorij za zapisnike',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Ako odaberete ovo, morate navesti sigurnu mapu za pohranu informacija o sesiji Sugar. Time se može spriječiti ugrožavanje podataka o sesiji na dijeljenom poslužitelju.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Upotrijebi zadani direktorij za sesiju za Sugar',
	'LBL_SITECFG_DIRECTIONS'			=> 'Unesite informacije o konfiguraciji web-mjesta u nastavku. Ako niste sigurni za neko od polja, predlažemo da upotrijebite zadane vrijednosti.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Prije nego što nastavite, popravite sljedeće pogreške:</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Direktorij za zapisnike',
	'LBL_SITECFG_SESSION_PATH'			=> 'Put do direktorija za sesiju<br>(mora biti omogućeno pisanje)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Odaberite sigurnosne mogućnosti',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'Ako odaberete ovo, sustav će povremeno provjeriti ima li ažuriranih verzija aplikacije.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Automatski provjeravati ima li ažuriranja?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Konfiguracija ažuriranja Sugar',
	'LBL_SITECFG_TITLE'					=> 'Konfiguracija web-mjesta',
    'LBL_SITECFG_TITLE2'                => 'Identifikacija korisnika administratora',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Sigurnost web-mjesta',
	'LBL_SITECFG_URL'					=> 'URL adresa instance Sugar',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Upotrijebiti zadano?',
	'LBL_SITECFG_ANONSTATS'             => 'Poslati anonimne statističke podatke o upotrebi?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'Ako odaberete ovo, Sugar će slati <b>anonimne</b> statističke podatke o vašoj instalaciji tvrtki SugarCRM Inc. svaki put kada vaš sustav provjeri ima li novih verzija. Te informacije pomoći će nam da bolje razumijemo kako se aplikacija upotrebljava i dati nam smjernice za poboljšanje proizvoda.',
    'LBL_SITECFG_URL_MSG'               => 'Unesite URL adresu koja će se upotrebljavati za pristup instanci Sugar nakon instalacije. URL adresa također će se upotrebljavati kao osnova za URL adrese stranica aplikacije Sugar. URL adresa treba uključivati naziv web-poslužitelja ili uređaja ili IP adresu.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Unesite naziv za svoj sustav. On će se prikazivati na naslovnoj traci poslužitelja kad korisnici posjete aplikaciju Sugar.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Nakon instalacije trebat ćete upotrijebiti korisnika administratora za Sugar (zadano korisničko ime = admin) za prijavu na instancu Sugar. Unesite lozinku za korisnika administratora. Ta se lozinka može promijeniti nakon početne prijave. Osim navedene zadane vrijednosti možete unijeti i drugo korisničko ime za administratora za upotrebu.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Odaberite postavke razvrstavanja (sortiranja) za svoj sustav. Te će postavke stvoriti tablice s određenim jezikom koji upotrebljavate. U slučaju da vaš jezik ne zahtijeva posebne postavke, upotrijebite zadanu vrijednost.',
    'LBL_SPRITE_SUPPORT'                => 'Podrška za Sprite',
	'LBL_SYSTEM_CREDS'                  => 'Vjerodajnice sustava',
    'LBL_SYSTEM_ENV'                    => 'Okruženje sustava',
	'LBL_START'							=> 'Početak',
    'LBL_SHOW_PASS'                     => 'Prikaži lozinke',
    'LBL_HIDE_PASS'                     => 'Sakrij lozinke',
    'LBL_HIDDEN'                        => '<i>(skriveno)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Izaberite svoj jezik</b>',
	'LBL_STEP'							=> 'Korak',
	'LBL_TITLE_WELCOME'					=> 'Dobro došli u SugarCRM ',
	'LBL_WELCOME_1'						=> 'Ovaj instalacijski program stvara tablice baze podataka SugarCRM i postavlja konfiguracijske varijable koje vam trebaju za početak. Cijeli postupak trebao bi trajati približno deset minuta.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Jeste li spremni za instalaciju?',
    'REQUIRED_SYS_COMP' => 'Obvezne komponente sustava',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Prije nego što počnete, provjerite imate li podržane verzije sljedećih komponenti
                      sustava:<br>
                      <ul>
                      <li> Baza podataka / Sustav za upravljanje bazom podataka (primjeri: MySQL, SQL Server, Oracle, DB2)</li>
                      <li> Web-poslužitelj (Apache, IIS)</li>
                      <li> Elasticsearch</li>
                      </ul>
                      Kompatibilne komponente sustava za verziju aplikacije Sugar koju instalirate
                      potražite u matrici kompatibilnosti u Bilješkama o izdanju.<br>',
    'REQUIRED_SYS_CHK' => 'Početna provjera sustava',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Kad započnete postupak instalacije, izvršit će se provjera sustava na web-poslužitelju na kojem se nalaze datoteke Sugar u svrhu
                      provjere ispravne konfiguracije sustava i svih potrebnih komponenti
                      uspješnog dovršetka instalacije. <br><br>
                      Sustav provjerava sve navedeno:<br>
                      <ul>
                      <li><b>verzija PHP-a</b> &#8211; mora biti kompatibilna
                      s aplikacijom</li>
                                        <li><b>varijable sesije</b> &#8211; moraju ispravno raditi</li>
                                            <li> <b>MB Strings</b> &#8211; mora biti instaliran i omogućen u datoteci php.ini</li>

                      <li> <b>podrška za bazu podataka</b> &#8211; mora postojati za MySQL, SQL
                      Server, Oracle ili DB2</li>

                      <li> <b>Config.php</b> &#8211; mora postojati i mora imati odgovarajuća
                                  dopuštenja za zapisivanje</li>
					  <li>U sljedeće datoteke Sugar mora biti moguće zapisivati:<ul><li><b>/prilagođeno</li>
<li>/predmemorija</li>
<li>/moduli</li>
<li>/prijenos</b></li></ul></li></ul>
                                  Ako provjera ne uspije, nećete moći nastaviti s instalacijom. Pojavit će se poruka o pogrešci s objašnjenjem zašto vaš sustav nije prošao provjeru.
                                  Nakon što napravite potrebne promjene, možete ponovno izvršiti
                                  provjeru sustava da biste nastavili s instalacijom.<br>',
    'REQUIRED_INSTALLTYPE' => 'Uobičajena ili prilagođena instalacija',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Nakon provjere sustava možete izabrati
                      uobičajenu ili prilagođenu instalaciju.<br><br>
                       I za <b>uobičajenu</b> i za <b>prilagođenu</b> instalaciju trebat ćete znati sljedeće:<br>
                      <ul>
                      <li> <b>vrstu baze podataka</b> u koju će se pohranjivati podaci Sugar <ul><li>Kompatibilne vrste
                      baza podataka: MySQL, MS SQL Server, Oracle, DB2.<br><br></li></ul></li>
                      <li> <b>Naziv web-poslužitelja</b> ili uređaja (glavno računalo) na kojemu se nalazi baza podataka
                      <ul><li>To može biti <i>lokalno glavno računalo</i> ako je baza podataka na vašem lokalnom računalu ili je na istom web-poslužitelju ili uređaju kao i vaše datoteke Sugar.<br><br></li></ul></li>
                      <li><b>Naziv baze podataka</b> koju želite upotrijebiti za pohranu podataka Sugar</li>
                        <ul>
                          <li> Možda već imate postojeću bazu podataka koju želite upotrijebiti. Ako
                          navedete naziv postojeće baze podataka, tablice u bazi podataka bit
                          će odbačene tijekom instalacije kad se odredi shema za bazu podataka Sugar.</li>
                          <li> Ako još nemate bazu podataka, naziv koji navedete upotrijebit će se za
                          novu bazu podataka stvorenu za instancu tijekom instalacije.<br><br></li>
                        </ul>
                      <li><b>Korisničko ime i lozinku administratora baze podataka</b> <ul><li>Administrator baze podataka mora moći stvarati tablice i korisnike i pisati u bazu podataka.</li><li>Možda ćete se morati
                      obratiti svojem administratoru baze podataka u vezi s ovim informacijama ako se baza
                      podataka ne nalazi na vašem lokalnom računalu i/ili vi niste administrator baze podataka.<br><br></ul></li></li>
                      <li> <b>Korisničko ime i lozinku baze podataka Sugar</b>
                      </li>
                        <ul>
                          <li> Korisnik može biti administrator baze podataka ili možete navesti ime
                          drugog postojećeg korisnika baze podataka. </li>
                          <li> Ako u tu svrhu želite stvoriti novog korisnika baze podataka, moći ćete
                          tijekom postupka instalacije navesti novo korisničko ime i lozinku,
                          a korisnik će biti stvoren za vrijeme instalacije. </li>
                        </ul>
                    <li> <b>Glavno računalo i ulaz za Elasticsearch</b>
                      </li>
                        <ul>
                          <li> Glavno računalo za Elasticsearch glavno je računalo na kojemu radi tražilica. Zadano je lokalno glavno računalo pod pretpostavkom da tražilicu upotrebljavate na istom poslužitelju kao i Sugar.</li>
                          <li> Ulaz za Elasticsearch broj je ulaza za povezivanje aplikacije Sugar na tražilicu. Zadana je vrijednost 9200, što je zadana vrijednost tražilice elasticsearch. </li>
                        </ul>
                        </ul><p>

                      Za <b>prilagođeno</b> postavljanje možda ćete također morati znati sljedeće:<br>
                      <ul>
                      <li> <b>URL adresu koja će se upotrebljavati za pristup instanci Sugar</b> nakon instalacije.
                      Ta URL adresa treba uključivati naziv web-poslužitelja ili uređaja ili IP adresu.<br><br></li>
                                  <li> [Optional] <b>Put do direktorija za sesiju</b> ako želite upotrebljavati prilagođeni
                                  direktorij za sesiju za informacije Sugar da biste spriječili ugrožavanje podataka
                                  na dijeljenim poslužiteljima.<br><br></li>
                                  <li> [Optional] <b>Put do prilagođenog direktorija za zapisnike</b> ako želite zamijeniti zadani direktorij za zapisnik Sugar.<br><br></li>
                                  <li> [Optional] <b>ID aplikacije</b> ako želite zamijeniti automatski stvoren
                                  ID koji sprječava da ostale instance ne upotrebljavaju sesije jedne instance Sugar.<br><br></li>
                                  <li><b>Skup znakova</b> koji se najčešće upotrebljava u vašoj regionalnoj shemi.<br><br></li></ul>
                                  Detaljnije informacije potražite u vodiču za instalaciju.
                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Pročitajte sljedeće važne informacije prije nastavka instalacije. Informacije će vam pomoći odrediti jeste li spremni sada instalirati aplikaciju.',


	'LBL_WELCOME_2'						=> 'Dokumentaciju za instalaciju potražite na <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>. <BR><BR> Da biste se obratili inženjeru SugarCRM podrške za pomoć pri instalaciji, prijavite se na <a target="_blank" href="http://support.sugarcrm.com">Portal podrške za SugarCRM</a> i prijavite slučaj za podršku.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Izaberite svoj jezik</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Čarobnjak za postavljanje',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Dobro došli u SugarCRM ',
	'LBL_WELCOME_TITLE'					=> 'Čarobnjak za postavljanje SugarCRM',
	'LBL_WIZARD_TITLE'					=> 'Čarobnjak za postavljanje Sugar: ',
	'LBL_YES'							=> 'Da',
    'LBL_YES_MULTI'                     => 'Da – višebajtno',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Obrada zadataka iz tijeka rada',
	'LBL_OOTB_REPORTS'		=> 'Pokreni generiranje izvješća o zakazanim zadacima',
	'LBL_OOTB_IE'			=> 'Provjeri dolazne poštanske sandučiće',
	'LBL_OOTB_BOUNCE'		=> 'Pokreni noćnu obradu vraćenih poruka e-pošte iz kampanje',
    'LBL_OOTB_CAMPAIGN'		=> 'Pokreni noćno slanje masovne e-pošte kampanje',
	'LBL_OOTB_PRUNE'		=> 'Pročisti bazu podataka prvog dana u mjesecu',
    'LBL_OOTB_TRACKER'		=> 'Pročisti tablice pratitelja',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Pokreni obavijesti podsjetnika putem e-pošte',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Ažuriraj tablicu tracker_sessions',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Očisti red čekanja poslova',


    'LBL_FTS_TABLE_TITLE'     => 'Pružanje postavki pretraživanja cijelog teksta',
    'LBL_FTS_HOST'     => 'Glavno računalo',
    'LBL_FTS_PORT'     => 'Ulaz',
    'LBL_FTS_TYPE'     => 'Vrsta tražilice',
    'LBL_FTS_HELP'      => 'Da biste omogućili pretraživanje cijelog teksta, unesite glavno računalo i ulaz tamo gdje je glavno računalo tražilice. Sugar uključuje ugrađenu podršku za tražilicu elasticsearch.',
    'LBL_FTS_REQUIRED'    => 'Potrebna je tražilica Elastic Search.',
    'LBL_FTS_CONN_ERROR'    => 'Nije moguće povezivanje s poslužiteljem za pretraživanje cijelog teksta, provjerite svoje postavke.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Nema dostupne verzije poslužitelja za pretraživanje cijelog teksta, provjerite svoje postavke.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Otkrivena je nepodržana verzija tražilice Elastic search. Upotrijebite verzije: %s',

    'LBL_PATCHES_TITLE'     => 'Instalacija najnovijih popravaka',
    'LBL_MODULE_TITLE'      => 'Instalacija jezičnih paketa',
    'LBL_PATCH_1'           => 'Ako želite preskočiti ovaj korak, kliknite na Sljedeće.',
    'LBL_PATCH_TITLE'       => 'Popravak sustava',
    'LBL_PATCH_READY'       => 'Sljedeći popravci spremni su za instalaciju:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "SugarCRM oslanja se na sesije PHP-a za pohranu važnih informacija tijekom povezanosti s ovim web-poslužiteljem. Vaša PHP instalacija nema ispravno konfigurirane informacije o sesiji.
											<br><br>Česta je pogrešna konfiguracija da smjernica <b>'session.save_path'</b> ne ukazuje na valjani direktorij.  <br>
											<br> Ispravite svoju <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>PHP konfiguraciju</a> u datoteci php.ini koja se nalazi ovdje u nastavku.",
	'LBL_SESSION_ERR_TITLE'				=> 'Pogreška konfiguracije sesija PHP-a',
	'LBL_SYSTEM_NAME'=>'Naziv sustava',
    'LBL_COLLATION' => 'Postavke razvrstavanja',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Navedite naziv sustava za instancu Sugar.',
	'LBL_PATCH_UPLOAD' => 'Odaberite datoteku popravaka s lokalnog računala',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'Uključen je način rada kompatibilnosti s ranijim verzijama PHP-a. Za nastavak postavite zend.ze1_compatibility_mode na Isključeno',

    'advanced_password_new_account_email' => array(
        'subject' => 'Informacije o novom računu',
        'description' => 'Ovaj predložak upotrebljava se kad administrator sustava korisniku pošalje novu lozinku.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>U nastavku slijede vaše korisničko ime i privremena lozinka za račun:</p><p>Korisničko ime : $contact_user_user_name </p><p>Lozinka : $contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url</a></p><br><p>Nakon što se prijavite s pomoću navedene lozinke, možda ćete morati ponovno postaviti lozinku na neku po vašem izboru.</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
U nastavku slijede vaše korisničko ime i privremena lozinka za račun:
Korisničko ime : $contact_user_user_name
Lozinka : $contact_user_user_hash

$config_site_url

Nakon što se prijavite s pomoću navedene lozinke, možda ćete morati ponovno postaviti lozinku na neku po vašem izboru.',
        'name' => 'E-pošta s lozinkom koju generira sustav',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Ponovno postavite svoju lozinku za račun',
        'description' => "Ovaj se predložak upotrebljava za slanje poveznice za ponovno postavljanje lozinke korisničkog računa korisniku.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Nedavno ste na $contact_user_pwd_last_changed zatražili ponovno postavljanje lozinke za račun. </p><p>Kliknite na poveznicu u nastavku da biste ponovno postavili lozinku:</p><p> <a href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Nedavno ste na $contact_user_pwd_last_changed zatražili ponovno postavljanje lozinke za račun.

Kliknite na poveznicu u nastavku da biste ponovno postavili lozinku:

$contact_user_link_guid',
        'name' => 'Poruka e-pošte o zaboravljenoj lozinki',
        ),
);
