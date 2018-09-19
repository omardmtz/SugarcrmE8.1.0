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
    'LBL_MODULE_NAME' => 'Archivování e-mailů',
    'LBL_SNIP_SUMMARY' => "Emailová archivace je automatická importovací služba, která umožňuje uživatelům archivovat emaily do Sugaru jejich odesláním z email. klienta nebo služby do Sugarem poskytované email. adresy. Každá instance má svojí unikátní email. adresu. Pro import emailů musí uživatelé poslat email na SugarCRM email. adresu za použití TO, CC, BCC. Archivační služba naimportuje email do SugarCRM. Služba importuje emaily s přílohami.<br />    <br><br>Příklad: Jako uživatel, když mám otevřen záznam Společnosti, mohu vidět emaily spárované a archivované na základě email. adresy společnosti pod tímto záznamem. Také mohu vidět emaily, které jsou přiřazeny ke kontaktu, který je navázán na tuto společnosti.<br />    <br><br>Proveďte souhlas s podmínkami a klikněte na Povolit, aby jste mohl začít využívat tuto službu. Poté můžete kdykoli služby zakázat. Pokud je služba zapnuta, email. adresa k použití pro tuto službu je zobrazena.<br />    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Problém se spojením s archivační službou: %s!',
	'LBL_CONFIGURE_SNIP' => 'Archivování e-mailů',
    'LBL_DISABLE_SNIP' => 'Vypnout',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Aplikační unikátní klíč',
    'LBL_SNIP_USER' => 'Uživatel archivačního mailboxu',
    'LBL_SNIP_PWD' => 'Heslo k archivačního mailboxu',
    'LBL_SNIP_SUGAR_URL' => 'URL této Sugar instance',
	'LBL_SNIP_CALLBACK_URL' => 'URL služby archivace mailů',
    'LBL_SNIP_USER_DESC' => 'Uživatel archivačního mailboxu',
    'LBL_SNIP_KEY_DESC' => 'Archivační OAuth klíč. Používáný pro přístup k této k instanci za účelem importování emailů.',
    'LBL_SNIP_STATUS_OK' => 'Aktivováno',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Tato Sugar instance se úspěšně spojila s archivačním serverem.',
    'LBL_SNIP_STATUS_ERROR' => 'Chyba',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Tato Sugar instance má platnou licenci k archivačnímu serveru, ale server navrátil tuto chybu:',
    'LBL_SNIP_STATUS_FAIL' => 'Problém s registrací k archivačnímu serveru',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Archivační server je aktualně nedostupný. Služba je pozastavena nebo spojení k této Sugar Instanci zklamalo.',
    'LBL_SNIP_GENERIC_ERROR' => 'Archivační server je aktualně nedostupný. Služba je pozastavena nebo spojení k této Sugar Instanci zklamalo.',

	'LBL_SNIP_STATUS_RESET' => 'Nikdy nespuštěno',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problém: %s',
    'LBL_SNIP_NEVER' => "Nikdy",
    'LBL_SNIP_STATUS_SUMMARY' => "Stav služby archivačního serveru:",
    'LBL_SNIP_ACCOUNT' => "Účet",
    'LBL_SNIP_STATUS' => "Stav",
    'LBL_SNIP_LAST_SUCCESS' => "Poslední úspěšné spuštění",
    "LBL_SNIP_DESCRIPTION" => "Služba archivačního serveru je automatický archivační systém",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "To vám umožní zobrazit emaily, které byly odeslány do nebo z kontaktů uvnitř SugarCRM, aniž byste museli ručně importovat emaily.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Aby jste mohl používat email. archivaci, musíte si pro Váš SugarCRM objednat licenci.",
    "LBL_SNIP_PURCHASE" => "Klikněte zde k objednání",
    'LBL_SNIP_EMAIL' => 'Archivovaná email. adresa',
    'LBL_SNIP_AGREE' => "Souhlasím s podmínkami a <a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">ochraně osobních údajů</a>.",
    'LBL_SNIP_PRIVACY' => 'ochrana osobních údajů',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Příkaz pingback se nezdařil',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Archivovací server není schopen navázat spojení se SugarCRM. Zkuste to prosím znovu nebo <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">kontaktujte podporu pro zákazníky</ a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Povolit archivování emailů',
    'LBL_SNIP_BUTTON_DISABLE' => 'Zakázat archivování emailů',
    'LBL_SNIP_BUTTON_RETRY' => 'Zkusit znovu připojení',
    'LBL_SNIP_ERROR_DISABLING' => 'Došlo k chybě při pokusu o komunikaci se serverem, který se stará o archivaci e-mailů, a služba nemůže být zakázána',
    'LBL_SNIP_ERROR_ENABLING' => 'Došlo k chybě při pokusu o komunikaci se serverem, který se stará o archivaci e-mailů, a služba nemůže být povolena',
    'LBL_CONTACT_SUPPORT' => 'Prosíme zkuste to znovu nebo kontaktujte podporu společnosti SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Prosímte kontaktujte podporu společnosti SugarCRM pro asistenci.',
    'ERROR_BAD_RESULT' => 'Chybné výsledky vrácené ze služby',
	'ERROR_NO_CURL' => 'cURL extensions je vyžadována, ale není povolena',
	'ERROR_REQUEST_FAILED' => 'Nelze kontaktovat server',

    'LBL_CANCEL_BUTTON_TITLE' => 'Zrušit',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Toto je stav archivační služby. Stav ukazuje, jestli je spojení mezi archivační službou a Vaším Sugarem v pořádku.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Toto je e-mailová adresa pro archivaci e-mailů, na kterou se odesílají e-mailové zprávy, které chcete importovat do aplikace Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Toto je URL serveru email. archivace. Všechny požadavky, například povolení a zakázání archivační služby, budou realizovány přes tuto URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Toto je URL webové služby Vaší Sugar instance. Server s email. archivací bude připojen k Vašemu serveru prostřednictvím této URL.',
);
