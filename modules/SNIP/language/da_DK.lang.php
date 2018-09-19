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
    'LBL_MODULE_NAME' => 'E-mail arkivering',
    'LBL_SNIP_SUMMARY' => "E-mail arkivering er en automatisk import service, der giver brugerne mulighed for at importere e-mails til Sugar ved at sende dem fra enhver mail-klient eller service til en fra Sugar angivet e-mail-adresse. Hver Sugar instans har sin egen unikke e-mail-adresse. For at importere e-mails, sender brugeren mails til den medfølgende e-mail-adresse ved hjælp af Til, Cc og Bcc felterne. E-mail arkiveringsservicen importerer så modtagne e-mails til Sugar instansen. Servicen importerer e-mails, sammen med eventuelle vedhæftede filer, billeder og kalenderoplysninger og opretter poster i applikationen, der er forbundet med eksisterende poster baseret på matchende e-mail adresser.
<br><br>Eksempel: Som bruger, når jeg får vist en konto, være i stand til at se alle de e-mails, der er forbundet med kontoen baseret på e-mail-adressen i konto posten. Jeg vil også kunne se e-mails, der er forbundet med kontaktpersoner relateret til kontoen.
<br><br>Accepter vilkårene nedenfor og klik på Aktiver for at begynde at bruge servicen. Du kan deaktivere tjenesten til enhver tid. Når tjenesten er aktiveret, vises e-mailadressen der bruges af servicen.
<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Kunne ikke kontakte e-mail arkiveringsservicen: %s<br>',
	'LBL_CONFIGURE_SNIP' => 'E-mail arkivering',
    'LBL_DISABLE_SNIP' => 'Deaktivér',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Applikations unik nøgle',
    'LBL_SNIP_USER' => 'E-mail arkiveringsbruger',
    'LBL_SNIP_PWD' => 'E-mail arkiveringspassword',
    'LBL_SNIP_SUGAR_URL' => 'Sugar instans URL',
	'LBL_SNIP_CALLBACK_URL' => 'E-mail arkiverings URL',
    'LBL_SNIP_USER_DESC' => 'E-mail arkiverings bruger',
    'LBL_SNIP_KEY_DESC' => 'E-mail arkiverings OAuth key. Bruges til at få adgang til denne instans i forbindelse med import af e-mails.',
    'LBL_SNIP_STATUS_OK' => 'Aktiveret',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Sugar instansen er forbundet til e-mail arkiverings serveren.',
    'LBL_SNIP_STATUS_ERROR' => 'Fejl',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Denne instans har en gyldig e-mail arkiverings serverlicens, men serveren returnerede følgende fejlmeddelelse:',
    'LBL_SNIP_STATUS_FAIL' => 'Kan ikke registrere på e-mail arkivering server',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'E-mail arkiverings servicen er ikke tilgængelig i øjeblikket. Enten er servicen nede eller forbindelsen til Sugar instansen er fejlet.',
    'LBL_SNIP_GENERIC_ERROR' => 'E-mail arkiverings servicen er ikke tilgængelig i øjeblikket. Enten er servicen nede eller forbindelsen til Sugar instansen er fejlet.',

	'LBL_SNIP_STATUS_RESET' => 'Ikke kørt endnu',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problem: %s',
    'LBL_SNIP_NEVER' => "Aldrig",
    'LBL_SNIP_STATUS_SUMMARY' => "E-mail arkiverings service status:",
    'LBL_SNIP_ACCOUNT' => "Virksomhed",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Sidste succesfulde kørsel",
    "LBL_SNIP_DESCRIPTION" => "E-mail arkiverings servicen er et autoamtisk arkiveringssystem.",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Den tillader dig at se e-mails som er blevet sendt til eller fra dine kontakter i SugarCRM uden at du manuelt skal importere eller linke e-mails",
    "LBL_SNIP_PURCHASE_SUMMARY" => "For at kunne bruge e-mail arkivering skal du købe en licens til din SugarCRM instans",
    "LBL_SNIP_PURCHASE" => "Klik her for at købe",
    'LBL_SNIP_EMAIL' => 'E-mail arkiverings adresse',
    'LBL_SNIP_AGREE' => "Jeg accepterer ovenstående betingelser og <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>fortrolighedspolitik</a>.",
    'LBL_SNIP_PRIVACY' => 'fortrolighedspolitik',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback fejlede',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'E-mail arkiveringsserveren kan ikke få forbindelse til din Sugar instans. Venligst forsøg igen eller <br /> <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">kontakt kunde support</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Aktivér e-mail arkivering',
    'LBL_SNIP_BUTTON_DISABLE' => 'Deaktivér e-mail arkivering',
    'LBL_SNIP_BUTTON_RETRY' => 'Forsøg at få forbindelse igen',
    'LBL_SNIP_ERROR_DISABLING' => 'Der opstod en fejl under forsøg på at kommunikere med e-mail arkiveringsserveren, og servicen kunne ikke deaktiveres',
    'LBL_SNIP_ERROR_ENABLING' => 'Der opstod en fejl under forsøg på at kommunikere med e-mail arkiveringsserveren, og servicen kunne ikke aktiveres',
    'LBL_CONTACT_SUPPORT' => 'Venligst prøv igen eller kontakt SugarCRM support.',
    'LBL_SNIP_SUPPORT' => 'Venligst kontakt SugarCRM support for at få hjælp.',
    'ERROR_BAD_RESULT' => 'Servicen returnerede en fejl',
	'ERROR_NO_CURL' => 'cURL udvidelser er påkrævet, men er ikke aktiveret.',
	'ERROR_REQUEST_FAILED' => 'Kunne ikke få forbindelse til serveren',

    'LBL_CANCEL_BUTTON_TITLE' => 'Annullér',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Dette er status på e-mail arkiveringsservicen på din instans. Status afspejler, om forbindelsen mellem e-mail arkiveringsserveren og din Sugar instans er ok.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Dette er e-mail arkiverings e-mail adressen som skal benyttes for at importere e-mail i Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Dette er e-mail arkiveringsserverens URL. Alle forspørgsler såsom aktivering og deaktivering af e-mai arkiverings servicen vil blive sendt gennem denne URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Dette er din Sugar instans&#39; webservices URL. E-mail arkiveringsserveren tilslutter sig til din server via denne URL.',
);
