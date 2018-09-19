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
    'LBL_MODULE_NAME' => 'Arhiviranje e-pošte',
    'LBL_SNIP_SUMMARY' => "Arhiviranje e-pošte usluga je za automatski uvoz koja omogućuje korisnicima da uvezu poruke e-pošte u Sugar tako da ih pošalje s bilo kojeg klijenta ili usluge za poštu na adresu e-pošte sustava Sugar. Svaka instanca Sugar ima vlastitu jedinstvenu adresu e-pošte. Za uvoz poruka e-pošte korisnik ih pošalje na dobivenu adresu e-pošte s pomoću polja ZA, CC i BCC. Usluga arhiviranja e-pošte uvest će poruku e-pošte u instancu Sugar. Usluga uvozi poruku e-pošte zajedno s prilozima, slikama i događajima kalendara te u aplikaciji stvara zapise povezane s postojećim zapisima na temelju podudarajućih adresa e-pošte.
    <br><br>Primjer: kada kao korisnik vidim račun, moći su vidjeti sve poruke e-pošte povezane s računom na temelju adrese e-pošte u zapisu o računu. Također ću moći vidjeti poruke e-pošte u vezi s kontaktima povezanima s računom.
    <br><br>Prihvatite uvjete u nastavku i kliknite na Omogući za početak upotrebe usluge. Moći ćete bilo kada onemogućiti uslugu. Nakon omogućivanja usluge prikazat će se adresa e-pošte koja će se upotrebljavati za uslugu.
    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Nije uspjela uspostava veze s uslugom arhiviranja e-pošte: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Arhiviranje e-pošte',
    'LBL_DISABLE_SNIP' => 'Onemogući',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Jedinstveni ključ aplikacije',
    'LBL_SNIP_USER' => 'Korisnik arhiviranja e-pošte',
    'LBL_SNIP_PWD' => 'Lozinka za arhiviranje e-pošte',
    'LBL_SNIP_SUGAR_URL' => 'URL adresa ove instance Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL adresa usluge arhiviranja e-pošte',
    'LBL_SNIP_USER_DESC' => 'Korisnik arhiviranja e-pošte',
    'LBL_SNIP_KEY_DESC' => 'Ključ OAuth za arhiviranje e-pošte. Upotrebljava se za pristupanje ovoj instanci s ciljem uvoza poruka e-pošte.',
    'LBL_SNIP_STATUS_OK' => 'Omogućeno',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Ova instanca Sugar uspješno je povezana na poslužitelj arhiviranja e-pošte.',
    'LBL_SNIP_STATUS_ERROR' => 'Pogreška',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Ova instanca ima valjanu licencu za poslužitelj arhiviranja e-pošte, ali poslužitelj je vratio sljedeću poruku o pogrešci:',
    'LBL_SNIP_STATUS_FAIL' => 'Registracija na poslužitelju arhiviranja e-pošte nije moguća',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Usluga arhiviranja e-pošte trenutačno nije dostupna. Usluga je izvan funkcije ili povezivanje na ovu instancu Sugar nije uspjelo.',
    'LBL_SNIP_GENERIC_ERROR' => 'Usluga arhiviranja e-pošte trenutačno nije dostupna. Usluga je izvan funkcije ili povezivanje na ovu instancu Sugar nije uspjelo.',

	'LBL_SNIP_STATUS_RESET' => 'Još nije pokrenuto',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problem: %s',
    'LBL_SNIP_NEVER' => "Nikada",
    'LBL_SNIP_STATUS_SUMMARY' => "Status usluge arhiviranja e-pošte:",
    'LBL_SNIP_ACCOUNT' => "Račun",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Posljednje uspješno pokretanje",
    "LBL_SNIP_DESCRIPTION" => "Usluga arhiviranja e-pošte sustav je automatskog arhiviranja e-pošte",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Omogućuje vam da vidite poruke e-pošte poslane vašim kontaktima ili dobivene od njih u sustavu SugarCRM, a da vi ne morate ručno uvoziti i povezivati poruke e-pošte",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Da biste upotrebljavali arhiviranje e-pošte, morate kupiti licencu za instancu SugarCRM",
    "LBL_SNIP_PURCHASE" => "Kliknite ovdje za kupnju",
    'LBL_SNIP_EMAIL' => 'Adresa arhiviranja e-pošte',
    'LBL_SNIP_AGREE' => "Prihvaćam gornje uvjete i <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>ugovor o privatnosti</a>.",
    'LBL_SNIP_PRIVACY' => 'ugovor o privatnosti',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Povezn. pingback nije uspjela',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Poslužitelj arhiviranja e-pošte ne može uspostaviti vezu s vašom instancom Sugar. Pokušajte ponovno ili <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">se obratite korisničkoj podršci</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Omogući arhiviranje e-pošte',
    'LBL_SNIP_BUTTON_DISABLE' => 'Onemogući arhiviranje e-pošte',
    'LBL_SNIP_BUTTON_RETRY' => 'Pokušaj se ponovno povezati',
    'LBL_SNIP_ERROR_DISABLING' => 'Došlo je do pogreške za vrijeme pokušaja uspostavljanja veze s poslužiteljem arhiviranja e-pošte i usluga se ne može onemogućiti',
    'LBL_SNIP_ERROR_ENABLING' => 'Došlo je do pogreške za vrijeme pokušaja uspostavljanja veze s poslužiteljem arhiviranja e-pošte i usluga se ne može omogućiti',
    'LBL_CONTACT_SUPPORT' => 'Pokušajte ponovno ili se obratite korisničkoj podršci SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Obratite se korisničkoj podršci SugarCRM za pomoć.',
    'ERROR_BAD_RESULT' => 'Iz usluge se vratio loš rezultat',
	'ERROR_NO_CURL' => 'Potrebno je proširenje cURL, no nije omogućeno',
	'ERROR_REQUEST_FAILED' => 'Nije moguće uspostaviti kontakt s poslužiteljem',

    'LBL_CANCEL_BUTTON_TITLE' => 'Odustani',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Ovo je status usluge arhiviranja e-pošte u vašoj instanci. Status prikazuje uspješnost veze između poslužitelja arhiviranja e-pošte i vaše instance Sugar.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Ovo je adresa e-pošte arhiviranja e-pošte za slanje s ciljem uvoza poruka e-pošte u Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Ovo je URL adresa poslužitelja arhiviranja e-pošte. Svi zahtjevi, poput omogućavanja i onemogućavanja usluge arhiviranja e-pošte, prenijet će se putem ove URL adrese.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Ovo je URL adresa web-usluga vaše instance Sugar. Poslužitelj arhiviranja e-pošte povezat će se s vašim poslužiteljem putem ove URL adrese.',
);
