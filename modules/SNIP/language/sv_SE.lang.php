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
    'LBL_MODULE_NAME' => 'Email Arkivering',
    'LBL_SNIP_SUMMARY' => "E-postarkivering är en automatisk importerande tjänst som tillåter användare att importera e-post till Sugar genom att skicka dem från alla e-postklient eller tjänst till en Sugar-tillhandahållen e-postadress. Varje Sugar instans har sin egen unika e-postadress. Om du vill importera e-post, skickar en användare till den angivna e-postadressen med hjälp av Till, Kopia, BCC fält. Den e-postarkivering tjänsten kommer importera e-post till Sugar instans. Tjänsten importerar e-post, tillsammans med eventuella bilagor, bilder och kalenderhändelser och skapar register i applikationen som är associerade med befintliga poster baserade på matchande e-postadresser.<br /><br />Exempel: Som användare, när jag läser ett Konto, kommer jag att kunna se alla e-postmeddelanden som är associerade med kontot baserat på e-postadressen i Konto protkoll. Jag kommer också att kunna se e-postmeddelanden som är förknippade med kontakter i samband med kontot.<br /><br />Acceptera villkoren nedan och klicka på Aktivera för att börja använda tjänsten. Du kommer att kunna inaktivera tjänsten när som helst. När tjänsten är aktiverad, kommer e-postadressen som ska användas för tjänsten visas.",
	'LBL_REGISTER_SNIP_FAIL' => 'Kunde inte ansluta till Emailarkiverings-tjänsten: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Email Arkivering',
    'LBL_DISABLE_SNIP' => 'Inaktivera',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Applikation Unik Nyckel',
    'LBL_SNIP_USER' => 'Maila arkiverande användare',
    'LBL_SNIP_PWD' => 'Maila arkiveringslösenord',
    'LBL_SNIP_SUGAR_URL' => 'Den här Sugarinstansens URL',
	'LBL_SNIP_CALLBACK_URL' => 'URL för emailarkiveringstjänst',
    'LBL_SNIP_USER_DESC' => 'Emailarkiverings-användare',
    'LBL_SNIP_KEY_DESC' => 'OAuth-nyckel för emailarkivering. Används för att komma åt den här instansen för att importera email.',
    'LBL_SNIP_STATUS_OK' => 'Aktivera',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Den här Sugarinstansen är nu ansluten till emailarkiverings-servern.',
    'LBL_SNIP_STATUS_ERROR' => 'Fel',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Instansen har en giltig emailarkiverings-serverlicens, men servern returnerar följande felmeddelande:',
    'LBL_SNIP_STATUS_FAIL' => 'Kan inte registrera med emailarkiverings-servern',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Emailarkiveringstjänsten är för tillfället otillgänglig. Antingen är tjänsten nere eller så misslyckades anslutningen till Sugarinstansen.',
    'LBL_SNIP_GENERIC_ERROR' => 'Emailarkiveringtjänsten är för tillfället otillgänglig. Antingen är tjänsten nere eller så misslyckades anslutningen till Sugarinstansen.',

	'LBL_SNIP_STATUS_RESET' => 'Inte körd än',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problem: %s',
    'LBL_SNIP_NEVER' => "Aldrig",
    'LBL_SNIP_STATUS_SUMMARY' => "Status för emailarkiveringstjänst:",
    'LBL_SNIP_ACCOUNT' => "Konto",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Senaste lyckade körningen",
    "LBL_SNIP_DESCRIPTION" => "Emailarkiveringstjänsten är ett automatiskt system för att arkivera email",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Det ger dig möjlighet att se mail som skickats till eller från dina kontakter inom SugarCRM, utan att du behöver manuellt importera eller länka mailen",
    "LBL_SNIP_PURCHASE_SUMMARY" => "För att använda mailarkivering måste du köpa en licens till din SugarCRM-instans",
    "LBL_SNIP_PURCHASE" => "Klicka här för att köpa",
    'LBL_SNIP_EMAIL' => 'Emailarkiveringsadress',
    'LBL_SNIP_AGREE' => "Jag accepterar ovanstående villkor och the <a href=&#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html&#39; target=&#39;_blank&#39;>privacy agreement</a>.",
    'LBL_SNIP_PRIVACY' => 'integritetsavtal',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback misslyckades',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Emailarkiveringstjänsten kan inte ansluta till din Sugarinstans. Försök igen eller <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">kontakta användarsupport</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Aktivera emailarkivering',
    'LBL_SNIP_BUTTON_DISABLE' => 'Inaktivera emailarkivering',
    'LBL_SNIP_BUTTON_RETRY' => 'Försök Ansluta Igen',
    'LBL_SNIP_ERROR_DISABLING' => 'Ett fel uppstod vid kommunikation med emailarkiveringsservern, och tjänsten kunde inte inaktiveras',
    'LBL_SNIP_ERROR_ENABLING' => 'Ett fel uppstod under försöket att kommunicera med emailarkiveringsservern, tjänsten kunde inte aktiveras',
    'LBL_CONTACT_SUPPORT' => 'Vänligen försök igen eller kontakta SugarCRM Support',
    'LBL_SNIP_SUPPORT' => 'Vänligen kontakta SugarCRM Support för hjälp.',
    'ERROR_BAD_RESULT' => 'Ogiltigt resultat togs emot från servicen',
	'ERROR_NO_CURL' => 'cURL extensions begärs, men har inte aktiverats',
	'ERROR_REQUEST_FAILED' => 'Kunde inte kontakta servern',

    'LBL_CANCEL_BUTTON_TITLE' => 'Avbryt',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Här visas statusen för emailarkiverisgtjänsten i din instans. Statusen visar om anslutningen mellan emailarkiveringsservern och din Sugarinstans fungerar.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Det här är emailarkiveringsadressen du ska skicka till för att importera email till Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Det här är URL:en till emailarkiveringsservern. Alla begäran, som aktivering och inaktivering av emailarkiveringstjänsten, kommer att förmedlas genom den här URLen.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Det här är webbtjänst-URL:en för din Sugarinstans. Emailarkiveringsservern kommer ansluta till din server genom den här URL:en.',
);
