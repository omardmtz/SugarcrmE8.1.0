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
    'LBL_MODULE_NAME' => 'Arhiviranje e-poruka',
    'LBL_SNIP_SUMMARY' => "Arhiviranje email poruka je automastki servis za uvoz koji korisnicima omogućava da uvezu email poruke u Sugar slanjem istih sa bilo kog mail klijenta ili servisa na email adresu koju obezbeđuje Sugar. Svaka Sugar instanca ima jedinstvenu email adresu. Da bi uvezao email poruke, korisnik šalje na datu email adresu koristeći polja TO, CC, BCC. Servis za arhiviranje email poruka će email poruke uvesti na Sugar instancu. Servis uvozi email, zajedno sa svim zakačenim fajlovima, slikama i kalendarskim događajima i napraviti zapise u okviru aplikacije koji su povezani sa postojećim zapisima po osnovu poklapajućih email adresa.<br /><br /><br /><br />Primer: Kao korisnik, kada pregledam kompaniju, u mogućnosti sam da vidim sve email poruke koje su pvezane sa kompanijom na osnovu email adrese u zapisu kompanije. Takođe ću videti i email poruke koje su povezane sa kontaktima vezanim za kompaniju.<br /><br /><br /><br />Prihvatite uslove date ispod i kliknite na \"Omogući\" kako biste počeli da koristite servis. Bićete u mogućnosti da ga isključite u bilo kom momentu. Kada je omogućen, biće prikazana email adresa koju će servis koristiti.",
	'LBL_REGISTER_SNIP_FAIL' => 'Neuspešno kotaktiranje servisa za arhiviranje email poruka: %s!',
	'LBL_CONFIGURE_SNIP' => 'Arhiviranje Email poruka',
    'LBL_DISABLE_SNIP' => 'Onemogući',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Jedinstveni ključ aplikacije',
    'LBL_SNIP_USER' => 'Korisnik arhiviranja email poruka',
    'LBL_SNIP_PWD' => 'Šifra za arhiviranje email poruka',
    'LBL_SNIP_SUGAR_URL' => 'URL Sugar instance',
	'LBL_SNIP_CALLBACK_URL' => 'URL servisa za arhiviranje email poruka',
    'LBL_SNIP_USER_DESC' => 'Korisnik arhiviranja email poruka',
    'LBL_SNIP_KEY_DESC' => 'OAuth ključ za arhiviranje email poruka. Koristi se za pristup instanci u svrhu uvoza email poruka.',
    'LBL_SNIP_STATUS_OK' => 'Omogućeno',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Ova Sugar instanca je uspešno konektovana na server za arhiviranje email poruka',
    'LBL_SNIP_STATUS_ERROR' => 'Greška',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Ova instanca ima validnu licencu za arhiviranje email poruka, ali je server vratio sledeću poruku o grešci:',
    'LBL_SNIP_STATUS_FAIL' => 'Nemoguće je registrovati se na server za arhiviranje email poruka',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Servis za arhiviranje email poruka je trenutno nedostupan. Ili je server pao ili je konekcija na ovu Sugar instancu neuspela.',
    'LBL_SNIP_GENERIC_ERROR' => 'Servis za arhiviranje email poruka je trenutno nedostupan. Server je pao ili je konekcija sa ovom Sugar instancom neuspela.',

	'LBL_SNIP_STATUS_RESET' => 'Još uvek nije pokrenuta',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problem: %s',
    'LBL_SNIP_NEVER' => "Nikad",
    'LBL_SNIP_STATUS_SUMMARY' => "Status servisa za arhiviranje email poruka:",
    'LBL_SNIP_ACCOUNT' => "Kompanija",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Poslednje uspešno pokretanje.",
    "LBL_SNIP_DESCRIPTION" => "Servis za arhiviranje email poruka je sistem za automatsko arhiviranje email poruka.",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Omogućuje vam da vidiite email poruke koje su Vam slali kontakti unutar SugarCRM aplikacije, bez potrebe da ručno uvozite i povezujete poruke.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Kako biste koristili arhiviranje email poruka, morate kupiti licencu za Vašu SugarCRM instancu",
    "LBL_SNIP_PURCHASE" => "Klikni ovde da kupiš",
    'LBL_SNIP_EMAIL' => 'Adresa za arhiviranje email poruka',
    'LBL_SNIP_AGREE' => "Slažem se sa gore navedenim uslovima i <a href=$#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html$#39; target=$#39;_blank$#39;>sporazumom o privatnosti</a>.",
    'LBL_SNIP_PRIVACY' => 'sporazum o privatnosti',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Kontaktiranje neuspelo',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Server za arhiviranje email poruka nije u mogućnosti da uspostavi konekciju sa Vašom Sugar instancom. Molimo pokušajte kasnije ili kontaktirajte korisničku podršku.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Omogući arhiviranje email poruka',
    'LBL_SNIP_BUTTON_DISABLE' => 'Isključi arhiviranje email poruka',
    'LBL_SNIP_BUTTON_RETRY' => 'Pokušaj da se konektuješ',
    'LBL_SNIP_ERROR_DISABLING' => 'Došlo je do greške pri pokušaju komunikacije sa servierom za arhiviranje email poruka i servis nije isključen',
    'LBL_SNIP_ERROR_ENABLING' => 'Došlo je do greške pri pokušaju komunikacije sa servierom za arhiviranje email poruka i servis nije omogućen',
    'LBL_CONTACT_SUPPORT' => 'Molimo pokušajte ponovo ili kontaktirajte SugarCRM podršku',
    'LBL_SNIP_SUPPORT' => 'Molimo kontaktirajte SugarCRM podršku.',
    'ERROR_BAD_RESULT' => 'Servis je vratio loš rezultat',
	'ERROR_NO_CURL' => 'cURL ekstenzija je neophodna, a nije omogućena',
	'ERROR_REQUEST_FAILED' => 'Nije moguće kontaktirati server',

    'LBL_CANCEL_BUTTON_TITLE' => 'Otkaži',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Ovo je status servisa za arhiviranje email poruka na Vašoj instanci. Status zavisi od toga da li je konekcija servera za arhiviranje email poruka i Vaše Sugar instance bila uspešna.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Ovo je email adresa za arhiviranje emailova na koju je potrebno lsati kako bi se uvezli mailovi u Sugar',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Ovo je URL servera za arhiviranje email poruka. Svi zahtevi, uključujući omogućavanje i isključivanje servisa, biće prosleđeni kroz ovaj URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Ovo je URL web servisa Vaše Sugar instance. Server za arhiviranje email poruka će se povezati na Vaš server kroz ovaj URL.',
);
