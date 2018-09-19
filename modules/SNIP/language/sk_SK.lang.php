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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Archivácia e-mailov',
    'LBL_SNIP_SUMMARY' => "Archivovanie emailov je automatická importovacia služba, ktorý umožňuje užívateľom importovať emaily do SugarCRM poslaním z hocijakého mailového klienta do emailovej adresy poskytovanej SugarCRM. Každý prípad v SugarCRM má svoju vlastnú unikátnu emailovú adresu. Na importovanie emailov, používateľ odošle email do poskytnutej emailovej adresy pomocou TO, CC, BCC. Emailová archivovacia služba importuje email do SugarCRM. Služba importuje email, so všetkými prílohami, obrázkami a udalostiami v kalendári, a vytvorí záznamy vnútri aplikácie, ktoré sú spojené s existujúcimi záznamami na základe zhodných emailových adries.<br /><br />Príklad: Ako užívateľ, keď si pozriem Účet, budem vidieť všetky emaily, ktoré súvisia s Účtom na základe emailových adries v zázname Účtu. Budem schopný vidieť všetky emaily, ktoré sú spojené s Kontaktmi súvisia s Účtom. <br /><br />Súhlaste s podmienkami nižšie a kliknite Aktivovať pre začatie používania služby. Budete môcť hocikedy vypnúť službu. Akonáhle je služba aktivovaná, emailová adresa na používanie služby bude zobrazená..",
	'LBL_REGISTER_SNIP_FAIL' => 'Nepodarilo sa kontaktovať archivácie e-mailov servis:% s!',
	'LBL_CONFIGURE_SNIP' => 'Archivácia e-mailov',
    'LBL_DISABLE_SNIP' => 'Zakázané',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Aplikácia Unikátneho kľúča',
    'LBL_SNIP_USER' => 'Archivácia Užívateľského emailu',
    'LBL_SNIP_PWD' => 'Archivácia hesla emailu',
    'LBL_SNIP_SUGAR_URL' => 'URL tohto Sugar prípadu',
	'LBL_SNIP_CALLBACK_URL' => 'Archivácia emailu URL služby',
    'LBL_SNIP_USER_DESC' => 'Archivácia užívateľského emailu',
    'LBL_SNIP_KEY_DESC' => 'Archivácia email OAuth kľúča. Používa sa pre acessing túto inštanciu pre účely importu e-mailov.',
    'LBL_SNIP_STATUS_OK' => 'Povolené',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Tento Sugar prípad je úspečne pripojený na Email archivovací server.',
    'LBL_SNIP_STATUS_ERROR' => 'Chyba:',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Tento prípad má plarnú email archivovaciu licenciu, napriek tomu server vrátil nasledujúcu chybnú správu:',
    'LBL_SNIP_STATUS_FAIL' => 'Nemôže sa zaregistrovať s email archivačným servrom.',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Email archivačná služba nie je momentálne dostupná. Buď je služba pokazená alebo zlyhalo pripojenie k Sugar prípadu.',
    'LBL_SNIP_GENERIC_ERROR' => 'Email archivačná služba nie je momentálne dostupná. Buď je služba pokazená alebo zlyhalo pripojenie k Sugar prípadu.',

	'LBL_SNIP_STATUS_RESET' => 'Nemožno spustiť ešte',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problem: %s',
    'LBL_SNIP_NEVER' => "nikdy",
    'LBL_SNIP_STATUS_SUMMARY' => "Stav archivácie služny email",
    'LBL_SNIP_ACCOUNT' => "Účet",
    'LBL_SNIP_STATUS' => "Stav",
    'LBL_SNIP_LAST_SUCCESS' => "Posledný úspešný krok",
    "LBL_SNIP_DESCRIPTION" => "Email archivačná služba je automatický archivačný systém.",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Umožňuje vám vidieť emaily, ktoré boli odoslané alebo prijaté vašimi kontaktmi vnútri SugarCRM bez toho, aby ste museli manuálne importovať a spájať emaily.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Ak chcete používať email archiváciu, musíte si zakúpiť licenciu pre váš SugarCRM.",
    "LBL_SNIP_PURCHASE" => "Kliknite tu pre zakúpenie",
    'LBL_SNIP_EMAIL' => 'Archivácia emailovej adresy',
    'LBL_SNIP_AGREE' => "Súhlasím s vyššie uvedenými podmienkami a <a href=&#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html&#39; target=&#39;_blank&#39;>vyhlasením o ochrane súkromia</a>.",
    'LBL_SNIP_PRIVACY' => 'súkromná dohoda',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback zlyhalo',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Email Archivácia server nie je schopný nadviazať spojenie s vaším Sugar inštancie. Skúste to prosím znovu alebo kontaktujte zákaznícku podporu.',

    'LBL_SNIP_BUTTON_ENABLE' => 'povoliť archiváciu emailov',
    'LBL_SNIP_BUTTON_DISABLE' => 'zakázat archiváciu emailov',
    'LBL_SNIP_BUTTON_RETRY' => 'Skúste sa znova pripojiť',
    'LBL_SNIP_ERROR_DISABLING' => 'Došlo k chybe pri pokuse o komunikáciu s archiváciu e-mailu servera, a služba by mohla byť zakázaná',
    'LBL_SNIP_ERROR_ENABLING' => 'Došlo k chybe pri pokuse o komunikáciu s archiváciu e-mailu servera, a služba by mohla byť povolený',
    'LBL_CONTACT_SUPPORT' => 'Prosím kontaktujte ešte raz podporu SugarCRM',
    'LBL_SNIP_SUPPORT' => 'Prosím, kontaktujte SugarCRM podporu spoločnosti.',
    'ERROR_BAD_RESULT' => 'Zlý výsledok sa vrátil zo služby.',
	'ERROR_NO_CURL' => 'cURL je vyžadovaná prípona, ale nebola povolená',
	'ERROR_REQUEST_FAILED' => 'Nedá sa kontaktovať server',

    'LBL_CANCEL_BUTTON_TITLE' => 'Zrušiť',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'To je stav archivácie e-mailov služby na inštanciu. Stav odráža, či spojenie medzi archivácie e-mailov servera a cukru inštancie je úspešný.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'To je archivácia e-mailov e-mailovú adresu poslať, aby sa importovať e-maily do Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Toto je URL v e-maile Archivácia servera. Všetky žiadosti, ako je zapnutie a vypnutie na archiváciu e-mailov služby, bude odovzdaná prostredníctvom tejto adresy URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Toto je web služby URL vášho Sugar inštancie. Email Archivácia server sa pripojí k serveru pomocou tejto adresy URL.',
);
