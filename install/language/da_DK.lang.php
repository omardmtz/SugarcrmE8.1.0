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
	'LBL_BASIC_SEARCH'					=> 'Grundlæggende søgning',
	'LBL_ADVANCED_SEARCH'				=> 'Avanceret søgning',
	'LBL_BASIC_TYPE'					=> 'Grundlæggende type',
	'LBL_ADVANCED_TYPE'					=> 'Advanceret type',
	'LBL_SYSOPTS_1'						=> 'Vælg fra systemkonfigurationsindstillingerne nedenfor.',
    'LBL_SYSOPTS_2'                     => 'Hvilken type database skal bruges til den Sugar-forekomst, du vil installere?',
	'LBL_SYSOPTS_CONFIG'				=> 'Systemkonfiguration',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'Angiv databasetype',
    'LBL_SYSOPTS_DB_TITLE'              => 'Databasetype',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'Ret følgende fejl, før du fortsætter:',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'Gør følgende mappe skrivbar:',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Din version af DB2 (%s) er ikke understøttet af Sugar. Du skal installere en version, der er kompatibel med Sugar. Se venligst kompatibilitets matrixen i udgivelsesnoterne for understøttede DB2-versioner.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'Du skal have en Oracle-klient installeret og konfigureret, hvis du vælger Oracle.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
	'ERR_DB_OCI8_CONNECT'				=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
	'ERR_DB_OCI8_VERSION'				=> 'Din version af Oracle understøttes ikke af Sugar. Du skal installere en version, der er kompatibel med Sugar-programmet. Se kompatibilitetsmatrixen "Compatibility Matrix" i produktbemærkningerne "Release Notes" for understøttede Oracle-versioner.',
    'LBL_DBCONFIG_ORACLE'               => 'Angiv venligst navnet på din database. Dette vil være standard tablespace, der er tildelt til din brugerkonto (SID fra tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'Salgsmulighedsforespørgsel',
	'LBL_Q1_DESC'						=> 'Salgsmuligheder efter type',
	'LBL_Q2_DESC'						=> 'Salgsmuligheder efter virksomheder',
	'LBL_R1'							=> '6-måneders salgspipelinerapport',
	'LBL_R1_DESC'						=> 'Salgsmuligheder over de næste 6 måneder opdelt efter måned og type',
	'LBL_OPP'							=> 'Salgsmulighedsdatasæt',
	'LBL_OPP1_DESC'						=> 'Her kan du ændre udseendet af den brugerdefinerede forespørgsel',
	'LBL_OPP2_DESC'						=> 'Denne forespørgsel anbringes under den første forespørgsel i rapporten',
    'ERR_DB_VERSION_FAILURE'			=> 'Kan ikke kontrollere database version.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'Angiv brugernavnet for Sugar-administratorbrugeren.',
	'ERR_ADMIN_PASS_BLANK'				=> 'Angiv adgangskoden til Sugar-administratorbrugeren.',

    'ERR_CHECKSYS'                      => 'Der er fundet fejl under kompatibilitetskontrolen. For at din SugarCRM-installation kan fungere korrekt, skal du sørge for at løse problemerne nedenfor og enten trykke på knappen Tjek igen eller prøve at udføre installationen igen.',
    'ERR_CHECKSYS_CALL_TIME'            => 'Tillad overførsel af opkaldstid som reference er aktiveret "Til" "den skal være deaktiveret "Fra" i php.ini"',

	'ERR_CHECKSYS_CURL'					=> 'Ikke fundet: Sugar Scheduler vil køre med begrænset funktionalitet. E-mail-arkiveringsfunktionen vil ikke køre.',
    'ERR_CHECKSYS_IMAP'					=> 'Blev ikke fundet: InboundEmail og Kampagner "e-mail" kræver IMAP-bibliotekerne. Ingen af dem fungerer.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> '"Magic Quotes GPC kan ikke slås ""Til"", når du bruger MS SQL Server."',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'Advarsel:',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> '"Angiv denne til',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M eller mere i filen php.ini"',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'Mindste version 4.1.2 - blev fundet:',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'Det lykkedes ikke at skrive og læse sessionsvariabler. Installationen kan ikke fortsætte.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'Ikke en gyldig mappe',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'Advarsel: Ikke skrivbar',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Din version af PHP understøttes ikke af Sugar. Du skal installere en version, der er kompatibel med Sugar-programmet. Se kompatibilitetsmatrixen "Compatibility Matrix" i produktbemærkningerne "Release Notes" for understøttede PHP-versioner. Din version er',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Din version af IIS understøttes ikke af Sugar. Du skal installere en version, der er kompatibel med Sugar-programmet. Se kompatibilitetsmatrixen "Compatibility Matrix" i produktbemærkningerne "Release Notes" for understøttede IIS-versioner. Din version er',
	'ERR_CHECKSYS_FASTCGI'              => '"Vi har registeret, at du ikke bruger en FastCGI-handlertilknytning til PHP. Du skal installere eller konfigurere en version, der er kompatibel med Sugar-programmet. Se kompatibilitetsmatrixen "Compatibility Matrix" i produktbemærkningerne "Release Notes" for understøttede versioner. Se <a href=""http://www.iis.net/php/"" target=""_blank"">http://www.iis.net/php/</a> for at få detaljerede oplysninger "',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'Du kan få den optimale oplevelse med IIS/FastCGI sapi ved at angive fastcgi.logging til 0 i filen php.ini.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'En ikke-understøttet PHP-version er installeret: "ver.',
    'LBL_DB_UNAVAILABLE'                => 'Databasen er ikke tilgængelig',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'Database supoort ikke fundet. Sørg venligst for at du har de nødvendige drivere til en af følgende understøttede datatyper: MySQL, MS SQLServer, Oracle eller DB2. Det kan være nødvendigt at afkommentere udvidelsen i php.ini filen eller at genoversætte med den rette binære fil, afhængigt af hvilken version af PHP du anvender. Se venligst din PHP manual for yderligere information om hvordan database support aktiveres.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'Funktioner knyttet til XML-parserbiblioteker, som skal bruges af Sugar-programmet, blev ikke fundet. Du skal måske fjerne kommentaren fra udvidelsen i filen php.ini eller genkompilere med den korrekte binære fil afhængigt af din version af PHP. Du kan finde flere oplysninger i PHP-vejledningen.',
    'LBL_CHECKSYS_CSPRNG' => 'Tilfældigt talgenerator',
    'ERR_CHECKSYS_MBSTRING'             => 'Funktioner knyttet til udvidelsen Multibyte Strings PHP "mbstring", som skal bruges af Sugar-programmet, blev ikke fundet. <br/><br/>Normalt er mbstring-modulet ikke aktiveret som standard i PHP og skal aktiveres med -aktivér-mbstring, når den binære PHP-fil bygges. Du kan finde flere oplysninger om, hvordan du aktiverer mbstring-understøttelse, i PHP-vejledningen.',
    'ERR_CHECKSYS_MCRYPT'               => "Mcrypt module isn't loaded. Please refer to your PHP Manual for more information on how to load mcrypt module.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'Indstillingen session.save_path i din php-konfigurationsfil "php.ini" er ikke angivet eller er angivet til en mappe, som ikke findes. Du skal måske angive indstillingen save_path i php.ini eller kontrollere, at mappesættene i save_path findes.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'Indstillingen session.save_path i din php-konfigurationsfil "php.ini" er angivet til en ikke-skrivbar mappe. Sørg for at gøre mappen skrivbar. <br>Afhængigt af operativsystemet skal du måske ændre tilladelserne ved at køre chmod 766 eller højreklikke på filnavnet for at få adgang til egenskaberne og fjerne markeringen af indstillingen Skrivebeskyttet.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'Filen config findes, men den er ikke skrivbar. Sørg for at gøre filen skrivbar. Afhængigt af operativsystemet skal du måske ændre tilladelserne ved at køre chmod 766 eller højreklikke på filnavnet for at få adgang til egenskaberne og fjerne markeringen af indstillingen Skrivebeskyttet.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'Konfigurationstilsidesætningsfil eksisterer, men er ikke skrivbar. Gør venligst filen skrivbar. Afhængigt af dit operativsystem, kan det kræve, at du ændrer tilladelserne ved at køre chmod 766, eller at højreklikke på filnavnet for at få adgang til egenskaber og fjerne markeringen af ​​skrivebeskyttet tilstand.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'Den brugerdefinerede mappe findes, men den er ikke skrivbar. Afhængigt af operativsystemet skal du måske ændre tilladelserne til den "chmod 766" eller højreklikke på den og fjerne markeringen af indstillingen Skrivebeskyttet. Sørg for at gøre filen skrivbar.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "Filerne eller mapperne nedenfor er ikke skrivbare eller mangler og kan ikke oprettes. Afhængigt af operativsystemet skal du måske ændre tilladelserne til filerne eller den overordnede mappe \"chmod 766\" eller højreklikke på den overordnede mappe og fjerne markeringen af indstillingen Skrivebeskyttet og anvende den til alle undermapper.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Sikker tilstand er aktiveret "Til" "du vil måske deaktivere den i php.ini"',
    'ERR_CHECKSYS_ZLIB'					=> 'Blev ikke fundet: SugarCRM opnår enorme fordele mht. ydeevne med zlib-komprimering.',
    'ERR_CHECKSYS_ZIP'					=> 'ZIP-understøttelse ikke fundet: SugarCRM behøver ZIP-understøttelse fra at behandle komprimerede filer.',
    'ERR_CHECKSYS_BCMATH'				=> 'BCMATH support ikke fundet: SugarCRM har behov for BCMATH support til vilkårlig præcision matematik.',
    'ERR_CHECKSYS_HTACCESS'             => 'Test for. htaccess omskrivning mislykkedes. Dette betyder normalt, at du ikke har AllowOverride sat op for Sugar bibliotek.',
    'ERR_CHECKSYS_CSPRNG' => 'CSPRNG-undtagelse',
	'ERR_DB_ADMIN'						=> 'Den angivne databaseadministrators brugernavn og/eller adgangskode er ugyldige, og der kan ikke oprettes forbindelse til databasen. Angiv et gyldigt brugernavn og en gyldig adgangskode: "Fejl:',
    'ERR_DB_ADMIN_MSSQL'                => 'Den angivne databaseadministrators brugernavn og/eller adgangskode er ugyldige, og der kan ikke oprettes forbindelse til databasen. Angiv et gyldigt brugernavn og en gyldig adgangskode:',
	'ERR_DB_EXISTS_NOT'					=> 'Den angivne database findes ikke.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> '"Databasen findes allerede med config-data. Hvis du vil køre en installation med den valgte database, skal du køre installationen igen og vælge: ""Vil du droppe og oprette eksisterende SugarCRM-tabeller igen?"" Hvis du vil opgradere, skal du bruge guiden Opgradering i konsollen Administration. Læs opgraderingsdokumentationen, som du finder <a href=""http://www.sugarforge.org/content/downloads/"" target=""_new"">her</a>."',
	'ERR_DB_EXISTS'						=> 'Det angivne databasenavn findes allerede - der kan ikke oprettes en ny med samme navn.',
    'ERR_DB_EXISTS_PROCEED'             => 'Det angivne databasenavn findes allerede. Du kan<br>1. trykke på knappen Tilbage og vælge et nyt databasenavn <br>2. klikke på Næste og fortsætte, men så droppes alle eksisterende tabeller i denne database. <strong>Det betyder, at dine tabeller og data forsvinder.</strong>',
	'ERR_DB_HOSTNAME'					=> 'Værtsnavnet må ikke være tomt.',
	'ERR_DB_INVALID'					=> 'En ugyldig databasetype er valgt.',
	'ERR_DB_LOGIN_FAILURE'				=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'Database vært, brugernavn og / eller adgangskode er ugyldig. En forbindelse til databasen kunne ikke etableres. Angiv gyldig host, brugernavn og adgangskode',
	'ERR_DB_MYSQL_VERSION'				=> 'Din MySQL version (%s) er ikke understøttet af Sugar. Du skal installere en version, der er kompatibel med Sugar. Se venligst kompatibilitets matrixen i udgivelsesnoterne for understøttede MySQL versioner.',
	'ERR_DB_NAME'						=> 'Databasenavnet må ikke være tomt.',
	'ERR_DB_NAME2'						=> "Databasens navn kan ikke indeholde en '\\', '/', eller '.'",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "Databasens navn kan ikke indeholde en '\\', '/', eller '.'",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "Databasens navn kan ikke begynde med et tal, '#', eller '@ \"og kan ikke indeholde mellemrum,' '\", \"'\", \"*\", '/', '\\', '?', '<', '>','&', eller '-'",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "Databasenavn kan kun bestå af alfanumeriske tegn og symbolerne '#', '_', ':', '.', '/' eller '$'",
	'ERR_DB_PASSWORD'					=> 'De adgangskoder, der er angivet for Sugar-databaseadministratoren, stemmer ikke overens. Angiv de samme adgangskoder igen i adgangskodefelterne.',
	'ERR_DB_PRIV_USER'					=> 'Angiv et databaseadministratorbrugernavn. Brugeren skal oprette forbindelse til databasen første gang.',
	'ERR_DB_USER_EXISTS'				=> 'Brugernavnet for Sugar-databasebrugeren findes allerede - der kan ikke oprettes et nyt med samme navn. Angiv et nyt brugernavn.',
	'ERR_DB_USER'						=> 'Angiv et brugernavn til Sugar-databaseadministratoren.',
	'ERR_DBCONF_VALIDATION'				=> 'Ret følgende fejl, før du fortsætter:',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'De adgangskoder, der er angivet for Sugar-databasebrugeren, stemmer ikke overens. Angiv de samme adgangskoder igen i adgangskodefelterne.',
	'ERR_ERROR_GENERAL'					=> 'Der er registreret følgende fejl:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'Filen kan ikke slettes:',
	'ERR_LANG_MISSING_FILE'				=> 'Filen blev ikke fundet:',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'Ingen sprogpakkefil blev fundet i medtag/sprog i:',
	'ERR_LANG_UPLOAD_1'					=> 'Der var problemer med din upload. Prøv igen.',
	'ERR_LANG_UPLOAD_2'					=> 'Sprogpakker skal være ZIP-arkiver.',
	'ERR_LANG_UPLOAD_3'					=> 'PHP kunne ikke flytte temp-filen til opgraderingsmappen.',
	'ERR_LICENSE_MISSING'				=> 'Obligatoriske felter mangler',
	'ERR_LICENSE_NOT_FOUND'				=> 'Licensfilen blev ikke fundet!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'Den angivne logmappe er ikke en gyldig mappe.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'Den angivne logmappe er ikke en skrivbar mappe.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'Logmappen er obligatorisk, hvis du vil angive din egen.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'Scriptet kan ikke behandles direkte.',
	'ERR_NO_SINGLE_QUOTE'				=> 'Enkelt anførselstegn kan ikke bruges til',
	'ERR_PASSWORD_MISMATCH'				=> 'De adgangskoder, der er angivet for Sugar-administratorbrugeren, stemmer ikke overens. Angiv de samme adgangskoder igen i adgangskodefelterne.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'Der kan ikke skrives til filen <span class=stop>config.php</span>.',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'Du kan fortsætte denne installation ved manuelt at oprette filen config.php og indsætte konfigurationsoplysningerne nedenfor i filen config.php. Du <strong>skal </strong>dog oprette filen config.php, før du fortsætter til næste trin.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'Huskede du at oprette filen config.php?',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'Advarsel: Der kunne ikke skrives til filen config.php. Kontrollér, at den findes.',
	'ERR_PERFORM_HTACCESS_1'			=> 'Der kan ikke skrives til',
	'ERR_PERFORM_HTACCESS_2'			=> 'filen.',
	'ERR_PERFORM_HTACCESS_3'			=> 'Hvis du vil sikre din logfil mod at kunne åbnes via en browser, skal du oprette en .htaccess-fil i logmappen med linjen:',
	'ERR_PERFORM_NO_TCPIP'				=> '"<b>Vi kunne ikke registrere en internetforbindelse.</b> Når du har oprettet forbindelse, skal du gå til <a href=""http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register"">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> for at blive registreret hos SugarCRM. Hvis du fortæller os lidt om, hvordan dit firma planlægger at bruge SugarCRM, kan vi sikre dig, at vi altid vil levere det rigtige program i forhold til dine forretningsbehov."',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'Den angivne sessionsmappe er ikke en gyldig mappe.',
	'ERR_SESSION_DIRECTORY'				=> 'Den angivne sessionsmappe er ikke en skrivbar mappe.',
	'ERR_SESSION_PATH'					=> 'Sessionsstien er obligatorisk, hvis du vil angive din egen.',
	'ERR_SI_NO_CONFIG'					=> 'Du har ikke medtaget config_si.php i dokumentroden, eller du har ikke defineret $sugar_config_si i config.php',
	'ERR_SITE_GUID'						=> 'Program-id er obligatorisk, hvis du vil angive din egen.',
    'ERROR_SPRITE_SUPPORT'              => "I øjeblikket er vi ikke i stand til at finde GD-biblioteket, pga. dette vil du ikke være i stand til at bruge CSS Sprite funktionaliteten.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'Advarsel: Din PHP-konfiguration skal ændres, hvis du vil tillade upload af filer på mindst 6 MB.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'Størrelse på uploadfil',
	'ERR_URL_BLANK'						=> 'Angiv basis-URL til Sugar-eksemplet.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'Kunne ikke finde installationsposten til',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'Den uploadede fil er ikke kompatibel med denne Sugar-version (Professional, Enterprise eller Ultimate edition): ',
	'ERROR_LICENSE_EXPIRED'				=> "Fejl: Din licens er udløbet",
	'ERROR_LICENSE_EXPIRED2'			=> " dag(e) siden. Gå til <a href=\"index.php?action=LicenseSettings&module=Administration\">\"\"Licensadministration\"\"</a> på skærmen Administration for at angive den nye licensnøgle. Hvis du ikke angiver en ny licensnøgle, inden licensnøglen udløber om 30 dage, kan du ikke længere logge på dette program.",
	'ERROR_MANIFEST_TYPE'				=> 'Manifestfilen skal angive pakketypen.',
	'ERROR_PACKAGE_TYPE'				=> 'Manifestfilen angiver en ikke-genkendt pakketype.',
	'ERROR_VALIDATION_EXPIRED'			=> "Fejl: Din valideringsnøgle er udløbet",
	'ERROR_VALIDATION_EXPIRED2'			=> " dag(e) siden. Gå til <a href=\"index.php?action=LicenseSettings&module=Administration\">\"\"Licensadministration\"\"</a> på skærmen Administration for at angive den nye valideringsnøgle. Hvis du ikke angiver en ny valideringsnøgle, inden valideringsnøglen udløber om 30 dage, kan du ikke længere logge på dette program.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'Den uploadede fil er ikke kompatibel med denne version af Sugar:',

	'LBL_BACK'							=> 'Tilbage',
    'LBL_CANCEL'                        => 'Annullér',
    'LBL_ACCEPT'                        => 'Jeg accepterer',
	'LBL_CHECKSYS_1'					=> '"For at din SugarCRM-installation kan fungere korrekt, skal du sikre, at alle systemkontrolposterne nedenfor er markeret med grønt. Hvis nogen af dem er røde, skal du sørge for at rette dem.<BR><BR> Du kan få hjælp til disse systemkontroller på <a href=""http://www.sugarcrm.com/crm/installation"" target=""_blank"">Sugar Wiki</a>."',
	'LBL_CHECKSYS_CACHE'				=> 'Skrivbare cacheundermapper',
    'LBL_DROP_DB_CONFIRM'               => 'Det angivne databasenavn findes allerede.<br>Du kan gøre ét af følgende:<br>1. Klik på knappen Annullér, og vælg et nyt databasenavn, eller <br>2. Klik på knappen Acceptér, og fortsæt. Alle eksisterende tabeller i databasen droppes. <strong>Det betyder, at alle tabellerne og de allerede eksisterende data forsvinder.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'PHP - Tillad overførsel af opkaldstid som reference er deaktiveret',
    'LBL_CHECKSYS_COMPONENT'			=> 'Komponent',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'Valgfri komponenter',
	'LBL_CHECKSYS_CONFIG'				=> 'Skrivbar SugarCRM-konfigurationsfil "config.php"',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'Skrivbar SugarCRM konfigurations fil (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'cURL-modul',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'Indstillingen Sessionslagringssti',
	'LBL_CHECKSYS_CUSTOM'				=> 'Skrivbar brugerdefineret mappe',
	'LBL_CHECKSYS_DATA'					=> 'Skrivbare dataundermapper',
	'LBL_CHECKSYS_IMAP'					=> 'IMAP-modul',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'Modulet MB-strenge',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt Module',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK "ingen grænse"',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK "ubegrænset"',
	'LBL_CHECKSYS_MEM'					=> 'PHP-hukommelsesgrænse',
	'LBL_CHECKSYS_MODULE'				=> 'Skrivbare modulundermapper og -filer',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'MySQL-version',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'Ikke tilgængelig',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> 'Din php-konfigurationsfil findes på (php.ini):',
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (ver ',
	'LBL_CHECKSYS_PHPVER'				=> 'PHP-version',
    'LBL_CHECKSYS_IISVER'               => 'IIS-version',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'Kontrollér igen',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'PHP - Sikker tilstand er deaktiveret',
	'LBL_CHECKSYS_SESSION'				=> 'Skrivbar sessionslagringssti (',
	'LBL_CHECKSYS_STATUS'				=> 'Status',
	'LBL_CHECKSYS_TITLE'				=> 'Accept af systemkontrol',
	'LBL_CHECKSYS_VER'					=> 'Fundet: (ver ',
	'LBL_CHECKSYS_XML'					=> 'XML-parsing',
	'LBL_CHECKSYS_ZLIB'					=> 'ZLIB-kompressionsmodul',
	'LBL_CHECKSYS_ZIP'					=> 'ZIP håndteringsmodul',
    'LBL_CHECKSYS_BCMATH'				=> 'Vilkårlig Precision Matematik Module',
    'LBL_CHECKSYS_HTACCESS'				=> 'AllowOverride opsætning for. htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'Ret følgende filer eller mapper, før du fortsætter:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'Ret følgende modulmapper og filerne under dem, før du fortsætter:',
    'LBL_CHECKSYS_UPLOAD'               => 'Skrivbar upload bibliotek',
    'LBL_CLOSE'							=> 'Luk',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'oprettes',
	'LBL_CONFIRM_DB_TYPE'				=> 'Databasetype',
	'LBL_CONFIRM_DIRECTIONS'			=> 'Bekræft indstillingerne nedenfor. Hvis du vil ændre nogen af værdierne, skal du klikke på "Tilbage" for at redigere dem. Ellers skal du klikke på "Næste" for at starte installationen.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'Licensoplysninger',
	'LBL_CONFIRM_NOT'					=> 'ikke',
	'LBL_CONFIRM_TITLE'					=> 'Bekræft indstillinger',
	'LBL_CONFIRM_WILL'					=> 'vil',
	'LBL_DBCONF_CREATE_DB'				=> 'Opret database',
	'LBL_DBCONF_CREATE_USER'			=> 'Opret bruger',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'Vigtigt! Alle Sugar-data slettes,<br>hvis dette felt er markeret.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'Vil du droppe og oprette eksisterende Sugar-tabeller igen?',
    'LBL_DBCONF_DB_DROP'                => 'Drop tabeller',
    'LBL_DBCONF_DB_NAME'				=> 'Databasenavn',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Sugar-databasebrugerens adgangskode',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'Angiv Sugar-databasebrugerens adgangskode',
	'LBL_DBCONF_DB_USER'				=> 'Sugar-databasens brugernavn',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Sugar-databasens brugernavn',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'Databaseadministratorens brugernavn',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'Databaseadministratorens adgangskode',
	'LBL_DBCONF_DEMO_DATA'				=> 'Vil du udfylde databasen med demodata?',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'Vælg demodata',
	'LBL_DBCONF_HOST_NAME'				=> 'Værtsnavn',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'Host instans',
	'LBL_DBCONF_HOST_PORT'				=> 'Port',
    'LBL_DBCONF_SSL_ENABLED'            => 'Aktiver SSL-forbindelse',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'Angiv dine databasekonfigurationsoplysninger nedenfor. Hvis du ikke er sikker på, hvad du skal udfylde, foreslår vi, at du bruger standardværdierne.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'Vil du bruge tekst med flere byte i demodata?',
    'LBL_DBCONFIG_MSG2'                 => 'Navnet på den webserver eller computer "vært", som databasen findes på (såsom lokalværten eller www.mydomain.com ):',
    'LBL_DBCONFIG_MSG3'                 => 'Navnet på den database, der skal indeholde dataene til den Sugar-forekomst, du er i færd med at installere:',
    'LBL_DBCONFIG_B_MSG1'               => 'Brugernavnet og adgangskoden på en databaseadministrator, der kan oprette databasetabeller og brugere, og som kan skrive til databasen, skal bruges til at konfigurere Sugar-databasen.',
    'LBL_DBCONFIG_SECURITY'             => 'Af hensyn til sikkerheden kan du angive en databasebruger med udelt adgang, der kan oprette forbindelse til Sugar-databasen. Denne bruger skal kunne skrive, opdatere og hente data i den Sugar-database, der oprettes til denne forekomst. Denne bruger kan være den databaseadministrator, der er angivet ovenfor, eller du kan angive oplysninger om en ny eller eksisterende databasebruger.',
    'LBL_DBCONFIG_AUTO_DD'              => 'Gør det for mig',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'Angiv eksisterende bruger',
    'LBL_DBCONFIG_CREATE_DD'            => 'Definér den bruger, der skal oprettes',
    'LBL_DBCONFIG_SAME_DD'              => 'Samme som administratorbruger',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'Fuldtekstsøgning',
    'LBL_FTS_INSTALLED'                 => 'Installeret',
    'LBL_FTS_INSTALLED_ERR1'            => 'Fuldtekstsøgning er ikke installeret.',
    'LBL_FTS_INSTALLED_ERR2'            => 'Du kan stadig installere, men vil ikke være i stand til at bruge fuldtekstsøgefunktionaliteten. Se din database server installationsguide for hvordan du gør dette, eller kontakt din administrator.',
	'LBL_DBCONF_PRIV_PASS'				=> 'Adgangskode for databasebruger med administratorrettigheder',
	'LBL_DBCONF_PRIV_USER_2'			=> 'Databasekontoen ovenfor er en bruger med administratorrettigheder?',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'Denne databasebruger med administratorrettigheder skal have de korrekte tilladelser til at oprette en database, droppe/oprette tabeller og oprette en bruger. Denne databasebruger med administratorrettigheder skal kun bruges til at udføre disse opgaver efter behov under installationen. Du kan også bruge den samme databasebruger som ovenfor, hvis denne bruger har tilstrækkelige rettigheder.',
	'LBL_DBCONF_PRIV_USER'				=> 'Navn på databasebruger med administratorrettigheder',
	'LBL_DBCONF_TITLE'					=> 'Databasekonfiguration',
    'LBL_DBCONF_TITLE_NAME'             => 'Angiv databasenavn',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'Angiv oplysninger om databasebruger',
	'LBL_DISABLED_DESCRIPTION_2'		=> '"Når du har foretaget denne ændring, kan du klikke på knappen "Start" nedenfor for at starte installationen. <i>Når installationen er fuldført, kan du ændre værdien for \'installer_locked\'; til \'true\'.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'Installationsprogrammer har allerede været kørt én gang. Som en sikkerhedsforanstaltning har det været deaktiveret, så det ikke køres en gang mere. Hvis du er helt sikker på, at du vil køre det igen, skal du gå til filen config.php og finde (eller tilføje) en variabel med navnet \'installer_locked\'; og angive den til \'false\'. Linjen skal se sådan ud:',
	'LBL_DISABLED_HELP_1'				=> 'Du kan få hjælp til installationen på SugarCRM',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'supportfora',
	'LBL_DISABLED_TITLE_2'				=> 'Installationen af SugarCRM er blevet deaktiveret',
	'LBL_DISABLED_TITLE'				=> 'Installationen af SugarCRM er deaktiveret',
	'LBL_EMAIL_CHARSET_DESC'			=> 'Det mest almindeligt brugte tegnsæt i din landestandard',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'Indstillinger for udgående e-mail',
    'LBL_EMAIL_CHARSET_CONF'            => 'Tegnsæt for udgående e-mail',
	'LBL_HELP'							=> 'Hjælp',
    'LBL_INSTALL'                       => 'Installer',
    'LBL_INSTALL_TYPE_TITLE'            => 'Installationsindstillinger',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'Vælg installationstype',
    'LBL_INSTALL_TYPE_TYPICAL'          => '<b>Standardinstallation</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => '<b>Specialinstallation</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'Nøglen er obligatorisk til almindelige programfunktioner, men ikke til installation. Du behøver ikke at angive nøglen nu, men du skal angive nøglen, når du har installeret programmet.',
    'LBL_INSTALL_TYPE_MSG2'             => 'Kræver minimumoplysninger til installationen. Anbefales til nye brugere.',
    'LBL_INSTALL_TYPE_MSG3'             => 'Indeholder yderligere indstillinger, der skal angives under installationen. De fleste af disse indstillinger er også tilgængelige på administratorskærmene efter installationen. Anbefales til avancerede brugere.',
	'LBL_LANG_1'						=> 'Hvis du vil bruge et andet sprog i Sugar end standardsproget "engelsk "USA"", kan du uploade og installere sprogpakken nu. Du kan også uploade og installere sprogpakker fra Sugar-programmet. Hvis du vil springe over dette trin, skal du klikke på Næste.',
	'LBL_LANG_BUTTON_COMMIT'			=> 'Installer',
	'LBL_LANG_BUTTON_REMOVE'			=> 'Fjern',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'Afinstaller',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'Upload',
	'LBL_LANG_NO_PACKS'					=> 'ingen',
	'LBL_LANG_PACK_INSTALLED'			=> 'Følgende sprogpakker er blevet installeret:',
	'LBL_LANG_PACK_READY'				=> 'Følgende sprogpakker er klar til at blive installeret:',
	'LBL_LANG_SUCCESS'					=> 'Sprogpakken blev uploadet.',
	'LBL_LANG_TITLE'			   		=> 'Sprogpakke',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'Installerer Sugar nu. Det kan tage op til et par minutter.',
	'LBL_LANG_UPLOAD'					=> 'Upload en sprogpakke',
	'LBL_LICENSE_ACCEPTANCE'			=> 'Accept af licens',
    'LBL_LICENSE_CHECKING'              => 'Kontrollerer systemet for kompatibilitet.',
    'LBL_LICENSE_CHKENV_HEADER'         => 'Kontrollerer miljøet',
    'LBL_LICENSE_CHKDB_HEADER'          => 'Kontrollerer DB, FTS-loginoplysninger.',
    'LBL_LICENSE_CHECK_PASSED'          => 'Systemet bestod kompatibilitetskontrollen.',
    'LBL_LICENSE_REDIRECT'              => 'Omdirigerer i',
	'LBL_LICENSE_DIRECTIONS'			=> 'Hvis du har licensoplysningerne, skal du angive dem i felterne nedenfor.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'Angiv downloadnøgle',
	'LBL_LICENSE_EXPIRY'				=> 'Udløbsdato',
	'LBL_LICENSE_I_ACCEPT'				=> 'Jeg accepterer',
	'LBL_LICENSE_NUM_USERS'				=> 'Antal brugere',
	'LBL_LICENSE_PRINTABLE'				=> 'Visning, der kan udskrives',
    'LBL_PRINT_SUMM'                    => 'Udskriv oversigt',
	'LBL_LICENSE_TITLE_2'				=> 'SugarCRM-licens',
	'LBL_LICENSE_TITLE'					=> 'Licensoplysninger',
	'LBL_LICENSE_USERS'					=> 'Brugere med licens',

	'LBL_LOCALE_CURRENCY'				=> 'Valutaindstillinger',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'Standardvaluta',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'Valutasymbol',
	'LBL_LOCALE_CURR_ISO'				=> 'Valutakode (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> 'Tusindtalsseparator',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'Decimalseparator',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'Eksempel',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'Betydende cifre',
	'LBL_LOCALE_DATEF'					=> 'Standarddatoformat',
	'LBL_LOCALE_DESC'					=> 'De angivne indstillinger for landestandard afspejles globalt i Sugar-forekomsten.',
	'LBL_LOCALE_EXPORT'					=> 'Tegnsæt til import og eksport<br> <i>(e-mail, .csv, vCard, PDF, dataimport)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'Eksportér afgrænsningstegn (.csv)',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'Import- og eksportindstillinger',
	'LBL_LOCALE_LANG'					=> 'Standardsprog',
	'LBL_LOCALE_NAMEF'					=> 'Standardnavneformat',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = tiltaleform<br />f = fornavn<br />l = efternavn',
	'LBL_LOCALE_NAME_FIRST'				=> 'John',
	'LBL_LOCALE_NAME_LAST'				=> 'Doe',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Mr.',
	'LBL_LOCALE_TIMEF'					=> 'Standardklokkeslætsformat',
	'LBL_LOCALE_TITLE'					=> 'Indstillinger for landestandard',
    'LBL_CUSTOMIZE_LOCALE'              => 'Tilpas indstillinger for landestandard',
	'LBL_LOCALE_UI'						=> 'Brugergrænseflade',

	'LBL_ML_ACTION'						=> 'Handling',
	'LBL_ML_DESCRIPTION'				=> 'Beskrivelse',
	'LBL_ML_INSTALLED'					=> 'Installeret den',
	'LBL_ML_NAME'						=> 'Navn',
	'LBL_ML_PUBLISHED'					=> 'Udgivet den',
	'LBL_ML_TYPE'						=> 'Type',
	'LBL_ML_UNINSTALLABLE'				=> 'Ikke-installerbar',
	'LBL_ML_VERSION'					=> 'Version',
	'LBL_MSSQL'							=> 'SQL Server',
	'LBL_MSSQL_SQLSRV'				    => 'SQL Server (Microsoft SQL Server Driver for PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (mysqli extension)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'Næste',
	'LBL_NO'							=> 'Nej',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'Angiver adgangskode for webstedsadministrator',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'revisionstabel/',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Opretter Sugar-konfigurationsfil',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>Opretter databasen</b>',
	'LBL_PERFORM_CREATE_DB_2'			=> '<b>til</b>',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'Opretter databasebrugernavnet og -adgangskoden...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'Opretter Sugar-standarddata',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'Opretter databasebrugernavnet og -adgangskoden til lokal vært...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Opretter Sugar-relationstabeller',
	'LBL_PERFORM_CREATING'				=> 'opretter/',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'Opretter standardrapporter',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'Opretter standardplanlæggerjob',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'Indsætter standardindstillinger',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'Opretter standardbrugere',
	'LBL_PERFORM_DEMO_DATA'				=> 'Udfylder databasetabellerne med demodata "det kan tage lidt tid"',
	'LBL_PERFORM_DONE'					=> 'udført<br>',
	'LBL_PERFORM_DROPPING'				=> 'dropper /',
	'LBL_PERFORM_FINISH'				=> 'Udfør',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'Opdaterer licensoplysninger',
	'LBL_PERFORM_OUTRO_1'				=> 'Konfigurationen af Sugar',
	'LBL_PERFORM_OUTRO_2'				=> 'er nu fuldført!',
	'LBL_PERFORM_OUTRO_3'				=> 'Tid i alt:',
	'LBL_PERFORM_OUTRO_4'				=> 'sekunder.',
	'LBL_PERFORM_OUTRO_5'				=> 'Omtrentlig anvendt hukommelse:',
	'LBL_PERFORM_OUTRO_6'				=> 'byte.',
	'LBL_PERFORM_OUTRO_7'				=> 'Dit system er nu installeret og konfigureret til brug.',
	'LBL_PERFORM_REL_META'				=> 'relationsmeta ...',
	'LBL_PERFORM_SUCCESS'				=> 'Handlingen lykkedes!',
	'LBL_PERFORM_TABLES'				=> 'Opretter Sugar-programtabeller, revisionstabeller og relationsmetadata',
	'LBL_PERFORM_TITLE'					=> 'Udfør konfiguration',
	'LBL_PRINT'							=> 'Udskriv',
	'LBL_REG_CONF_1'					=> 'Udfyld den korte formular nedenfor, hvis du vil modtage produktannonceringer, uddannelsesnyheder, særtilbud og invitationer til særarrangementer fra SugarCRM. Vi hverken sælger, udlejer, deler eller på anden vis distribuerer de oplysninger, vi indsamler her, til tredjepart.',
	'LBL_REG_CONF_2'					=> 'Dit navn og din e-mail-adresse er de eneste obligatoriske felter i forbindelse med registreringen. Alle andre felter er valgfri, men meget nyttige. Vi hverken sælger, udlejer, deler eller på anden vis distribuerer de oplysninger, vi indsamler her, til tredjepart.',
	'LBL_REG_CONF_3'					=> 'Tak for din registrering. Klik på knappen Udfør for at logge på SugarCRM. Du skal logge på første gang med brugernavnet "administrator" og den adgangskode, du angav i trin 2.',
	'LBL_REG_TITLE'						=> 'Registrering',
    'LBL_REG_NO_THANKS'                 => 'Nej tak',
    'LBL_REG_SKIP_THIS_STEP'            => 'Spring over dette trin',
	'LBL_REQUIRED'						=> '* Obligatorisk felt',

    'LBL_SITECFG_ADMIN_Name'            => 'Navn på Sugar-programadministrator',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'Angiv adgangskode for Sugar-administratorbruger igen',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'Vigtigt! Dette tilsidesætter administratoradgangskoden for alle tidligere installationer.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Adgangskode for Sugar-administratorbruger',
	'LBL_SITECFG_APP_ID'				=> 'Program-id',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'Hvis dette felt er markeret, skal du angive et program-id for at tilsidesætte det automatisk genererede id. Med id-et sikrer du, at sessioner af ét Sugar-eksempel ikke bruges af andre forekomster. Hvis du har en klynge af Sugar-installationer, skal de alle dele det samme program-id.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'Angiv dit eget program-id',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Hvis dette felt er markeret, skal du angive en logmappe for at tilsidesætte standardmappen til Sugar-logfilen. Uanset hvor logfilen er placeret, er adgangen til den via en webbrowser begrænset via en .htaccess-omdirigering.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'Brug en brugerdefineret logmappe',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Hvis dette felt er markeret, skal du angive en sikker mappe til lagring af Sugar-sessionsoplysninger. Dette kan gøres for at forhindre sårbare sessionsdata på delte servere.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Brug en brugerdefineret sessionsmappe til Sugar',
	'LBL_SITECFG_DIRECTIONS'			=> 'Angiv dine webstedskonfigurationsoplysninger nedenfor. Hvis du ikke er sikker på felterne, foreslår vi, at du bruger standardværdierne.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>Ret følgende fejl, før du fortsætter:</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'Logmappe',
	'LBL_SITECFG_SESSION_PATH'			=> 'Sti til sessionsmappe<br>(skal være skrivbar)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'Vælg sikkerhedsindstillinger',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'Hvis dette felt er markeret, tjekker systemet jævnligt, om der er opdaterede versioner af programmet.',
	'LBL_SITECFG_SUGAR_UP'				=> 'Søg automatisk efter opdateringer?',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Konfiguration af Sugar-opdateringer',
	'LBL_SITECFG_TITLE'					=> 'Webstedskonfiguration',
    'LBL_SITECFG_TITLE2'                => 'Identificer din administrationsbruger',
    'LBL_SITECFG_SECURITY_TITLE'        => 'Webstedssikkerhed',
	'LBL_SITECFG_URL'					=> 'URL til Sugar-eksempel',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'Vil du bruge standarder?',
	'LBL_SITECFG_ANONSTATS'             => 'Send statistik over anonym brug?',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'Hvis dette felt er markeret, sender Sugar <b>anonyme</b> statistikker over din installation til SugarCRM Inc., hver gang systemet tjekker, om der er nye versioner. Disse oplysninger hjælper os, så vi bedre kan forstå, hvordan programmet bruges, og vejlede om forbedringer til produktet.',
    'LBL_SITECFG_URL_MSG'               => 'Angiv den URL, der skal bruges til at få adgang til Sugar-forekomsten efter installationen. URL&\'en bruges også som basis for URL-erne på siderne i Sugar-programmet. URL-en skal indeholde navnet eller IP-adressen på webserveren eller computeren.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'Angiv et navn på systemet. Dette navn vises i browserens titellinje, når brugerne besøger Sugar-programmet.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'Efter installationen skal du bruge Sugar-administratorbrugeren (brugernavn = administrator) til at logge på Sugar-forekomsten. Angiv en adgangskode for denne administratorbruger. Denne adgangskode kan ændres, efter at du har logget på første gang.',
    'LBL_SITECFG_COLLATION_MSG'         => 'Vælg sorterings indstillinger for dit system. Denne indstilling vil oprette tabeller med det specifikke sprog, du anvender. Hvis dit sprog ikke kræver specielle indstillinger, så benyt standard værdien.',
    'LBL_SPRITE_SUPPORT'                => 'Sprite support',
	'LBL_SYSTEM_CREDS'                  => 'Systemlegitimationsoplysninger',
    'LBL_SYSTEM_ENV'                    => 'Systemmiljø',
	'LBL_START'							=> 'Start',
    'LBL_SHOW_PASS'                     => 'Vis adgangskoder',
    'LBL_HIDE_PASS'                     => 'Skjul adgangskoder',
    'LBL_HIDDEN'                        => '<i>(skjult)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>Vælg sprog</b>',
	'LBL_STEP'							=> 'Trin',
	'LBL_TITLE_WELCOME'					=> 'Velkommen til SugarCRM',
	'LBL_WELCOME_1'						=> 'Dette installationsprogram opretter SugarCRM-databasetabellerne og angiver de konfigurationsvariabler, du skal bruge for at starte. Hele processen tager cirka ti minutter.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'Er du klar til at installere?',
    'REQUIRED_SYS_COMP' => 'Obligatoriske systemkomponenter',
    'REQUIRED_SYS_COMP_MSG' =>
                    'Før du starter, skal du sikre dig, at du har de understøttede versioner af følgende systemkomponenter:<br> 
<ul> 
<li> Database/databaseadministrationssystem (eksempler: MySQL, SQL Server, Oracle, DB2)</li> 
<li> Webserver (Apache, IIS)</li>
<li> Elasticsearch</li>
</ul> 
Se kompatibilitetsmatrixen i produktbemærkningerne Release Notes for kompatible komponenter til den Sugar-version, du vil installere.<br>',
    'REQUIRED_SYS_CHK' => 'Indledende systemkontrol',
    'REQUIRED_SYS_CHK_MSG' =>
                    'Når du starter installationsprocessen, udføres der en systemkontrol på den webserver, hvor Sugar-filerne er placeret, for at sikre, 
at systemet er korrekt konfigureret og har alle de nødvendige komponenter 
til at fuldføre installationen. <br><br> 
Systemet tjekker alle følgende elementer:<br> 
<ul> 
<li><b>PHP-version</b> &#8211;skal være kompatibel med programmet</li> 
<li><b>Sessionsvariabler</b> &#8211; skal fungere korrekt med</li> 
<li><b>MB-strenge</b> &#8211; skal være installeret og aktiveret i php.ini</li> 

<li> <b>Databaseunderstøttelse</b> &#8211; skal foreligge for MySQL, SQL Server, Oracle eller DB2</li> 

<li> <b>Config.php</b> &#8211; skal foreligge og have de korrekte 
tilladelser, så den kan gøres skrivbar</li> 
<li>Følgende Sugar-filer skal være skrivbare:<ul><li>
<b>/custom</li> 
<li>/cache</li> 
<li>/moduler</li>
<li>/opload</b></li></ul><li></ul>
Hvis resultatet af kontrollen viser fejl, kan du ikke fortsætte med installationen. En fejlmeddelelse vises og fortæller, hvorfor systemet 
ikke bestod kontrollen. 
Når du har foretaget alle nødvendige ændringer, kan du køre systemkontrollen igen for at fortsætte installationen.<br>',
    'REQUIRED_INSTALLTYPE' => 'Standardinstallation eller specialinstallation',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "Når systemkontrollen er udført, kan du vælge enten Standardinstallation eller Specialinstallation.<br><br> 
Til både <b>Standardinstallation</b> og <b>Specialinstallation</b> skal du kende følgende:<br> 
<ul> 
Den <li> <b>databasetype</b>, der skal rumme Sugar-dataene <ul><li>Kompatible database
typer: MySQL, MS SQL Server og Oracle, DB2.<br><br></li></ul></li> <li> <b>Navnet på den webserver</b> eller computer \"vært\", som databasen er placeret på 
<ul><li>Det kan være <i>lokal vært</i>, hvis databasen findes på din lokale computer eller findes på samme webserver eller computer som dine Sugar-filer.<br><br></li></ul></li> 
<li><b>Navnet på den database</b>, som skal rumme Sugar-dataene</li> 
<ul> 
<li> Du har måske allerede en eksisterende database, som du vil bruge. Hvis 
du angiver navnet på en eksisterende database, droppes tabellerne i databasen under installationen, når skemaet til Sugar-databasen defineres.</li> 
<li> Hvis du ikke allerede har en database, bruges det navn, du angiver, til 
den nye database, der oprettes til eksemplet under installationen.<br><br></li> </ul> 
<li><b>Brugernavn og adgangskode for databaseadministratoren</b> <ul><li>Databaseadministratoren skal kunne oprette tabeller og brugere og skrive til databasen.</li><li>Du skal måske kontakte din databaseadministrator for at få disse oplysninger, hvis databasen 
ikke er placeret på din lokale computer, og/eller hvis du ikke er databaseadministratoren.<br><br></ul></li></li> 
<li> <b>Brugernavn og adgangskode til Sugar-databasen</b> </li> 
<ul> 
<li> Brugeren kan være databaseadministratoren, eller du kan angive navnet på en anden eksisterende databasebruger. </li> 
<li> Hvis du vil oprette en ny databasebruger til dette formål, kan du 
angive et nyt brugernavn og en ny adgangskode under installationsprocessen, 
og brugeren oprettes under installationen. </li> 
</ul>
<li> <b>Elasticsearch vært og port</b>
</li>
<ul>
<li> Elasticsearch vært er vært søgemaskinen, der kører. Denne er som standard til lokalværten, idet den antager at du kører søgemaskinen på samme server som Sugar.</li>
<li> Elasticsearch porten er portnummeret for Sugar til at oprette forbindelse til søgemaskinen. Dette er som standard 9200, hvilket er elasticsearch's standard. </li>
</ul>
</ul><p>

Til <b>specialinstallationen</b> skal du måske også kende følgende:<br> 
<ul> 
<li> <b>Den URL, der skal bruges til at få adgang til Sugar-eksemplet</b>, når den er installeret. 
Denne URL skal indeholde navnet eller IP-adressen på webserveren eller computeren.<br><br></li> 
<li> [Optional] <b>Stien til sessionsmappen</b>, hvis du vil bruge en brugerdefineret 
sessionsmappe til Sugar-oplysninger for at forhindre 
sårbare sessionsdata på delte servere.<br><br></li> 
<li> [Optional] <b>Stien til en brugerdefineret logmappe</b>, hvis du vil tilsidesætte standardmappen til Sugar-logfilen.<br><br></li> 
<li> [Optional] <b>Program-id</b>, hvis du vil tilsidesætte det autogenererede 
id, der sikrer, at sessioner af et Sugar-eksempel ikke bruges af andre forekomster.<br><br></li> 
<li><b>Det mest almindeligt brugte tegnsæt</b> i din landestandard.<br><br></li>
</ul> Du kan finde flere oplysninger i installationsvejledningen..                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'Læs følgende vigtige oplysninger, før du fortsætter med installationen. Oplysningerne hjælper dig med at bestemme, om du er klar til at installere programmet nu.',


	'LBL_WELCOME_2'						=> 'Gå til <a href="http://www.sugarcrm.com/crm/installation" target="_blank"> Sugar Wiki</a> for installationsdokumewntation.  <BR><BR>For at kontakte en SugarCRM supportmesarbejder for hjælp til installationen, log ind på <a target="_blank" href="http://support.sugarcrm.com"> SugarCRM support-portal</a> og indsend en support-case.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>Vælg sprog</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'Guiden Installation',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'Velkommen til SugarCRM',
	'LBL_WELCOME_TITLE'					=> 'Guiden Installation af SugarCRM',
	'LBL_WIZARD_TITLE'					=> 'Guiden Installation af Sugar:',
	'LBL_YES'							=> 'Ja',
    'LBL_YES_MULTI'                     => 'Ja - multibyte',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'Behandling af arbejdsgangopgaver',
	'LBL_OOTB_REPORTS'		=> 'Kør planlagte opgaver til rapportgenerering',
	'LBL_OOTB_IE'			=> 'Tjek indgående postkasser',
	'LBL_OOTB_BOUNCE'		=> 'Kør hver nat proces med afviste kampagne-e-mails',
    'LBL_OOTB_CAMPAIGN'		=> 'Kør hver nat kampagner med masse-e-mails',
	'LBL_OOTB_PRUNE'		=> 'Beskær databasen den 1. i måneden',
    'LBL_OOTB_TRACKER'		=> 'Beskær sporingstabeller',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Kør e-mail-påmindelsesbeskeder',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'Opdater sporingssessionstabel',
    'LBL_OOTB_CLEANUP_QUEUE' => 'Rens job kø',


    'LBL_FTS_TABLE_TITLE'     => 'Indtast fultekstsøgningsindstillinger',
    'LBL_FTS_HOST'     => 'Host',
    'LBL_FTS_PORT'     => 'Port',
    'LBL_FTS_TYPE'     => 'Søgemaskinetype',
    'LBL_FTS_HELP'      => 'Hvis du vil aktivere fuldtekstsøgning, skal du vælge søgemaskine og indtaste host og port for søgemaskinen. Sugar indeholder indbygget understøttelse af elasticsearch søgemaskinen.',
    'LBL_FTS_REQUIRED'    => 'Elastic Search er påkrævet.',
    'LBL_FTS_CONN_ERROR'    => 'Kan ikke etablere forbindelse til fuldtekstsøgningsserveren, kontroller venligst dine indstillinger.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'Der er ingen fuldtekstsøgningsserverversion tilgængelig, kontroller venligst dine indstillinger.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'Der blev fundet en ikke-understøttet version af elastisk søgning. Brug venligst versioner: %s',

    'LBL_PATCHES_TITLE'     => 'Installér de nyeste programrettelser',
    'LBL_MODULE_TITLE'      => 'Installér sprogpakker',
    'LBL_PATCH_1'           => 'Hvis du vil springe over dette trin, skal du klikke på Næste.',
    'LBL_PATCH_TITLE'       => 'Systemrettelse',
    'LBL_PATCH_READY'       => 'Følgende programrettelse"r" er klar til at blive installeret:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "SugarCRM bruger PHP-sessioner til at gemme vigtige oplysninger, når der er oprettet forbindelse til denne webserver. Sessionsoplysningerne er ikke korrekt konfigureret i din PHP-installation. 
<br><br>En almindelig forkert konfiguration er, at direktivet <b>'session.save_path';</b> ikke peger på en gyldig mappe. <br> 
<br> Ret din <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>PHP-konfiguration</a> i filen php.ini, som er placeret her nedenfor.",
	'LBL_SESSION_ERR_TITLE'				=> 'Fejl under konfiguration af PHP-sessioner',
	'LBL_SYSTEM_NAME'=>'Systemnavn',
    'LBL_COLLATION' => 'Sortering indstillinger',
	'LBL_REQUIRED_SYSTEM_NAME'=>'Angiv et systemnavn til Sugar-forekomsten.',
	'LBL_PATCH_UPLOAD' => 'Vælg en rettelsesfil fra den lokale computer',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'Tilstanden Php-bagudkompatibilitet er slået til. Angiv zend.ze1_compatibility_mode til Fra for at fortsætte',

    'advanced_password_new_account_email' => array(
        'subject' => 'Ny kontoinformation',
        'description' => 'Denne skabelon bruges når systemadministratoren sender en nyt adgangskode til en bruger.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p> Her er din kontos brugernavn og midlertidige adgangskode:</p><p>Brugernavn : 
$contact_user_user_name </p><p>Adgangskode : 
$contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url</a></p><br><p>Efter du er logged på ved brug af ovenstående adgangskode, kan systemet kræve at du laver en selvvalgt adgangskode.</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Her er dit brugernavn og midlertidige adgangskode: 
Brugernavn: $contact_user_user_name 
Adgangskode: $contact_user_user_hash 

$config_site_url 

Når du logger på ved hjælp af ovenstående adgangskode, kan du blive bedt om at ændre adgangskoden til en du selv vælger.',
        'name' => 'Systemgenereret adgangskode e-mail',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'Nulstil din adgangskode',
        'description' => "Denne skabelon bruges til at sende en bruger et link til at klikke på for at nulstille brugerens adgangskode.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>Du har for nylig anmodet om på $contact_user_pwd_last_changed at få lov til at nulstille din adgangskode. </p><p>Klik på nedenstående link for at nulstille din adgangskode:
href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
Du har for nylig anmodet om på $contact_user_pwd_last_changed at få lov til at nulstille din adgangskode. 

Klik på linket nedenfor for at nulstille din adgangskode: 

$contact_user_link_guid',
        'name' => 'Glemt adgangskode e-mail',
        ),
);
