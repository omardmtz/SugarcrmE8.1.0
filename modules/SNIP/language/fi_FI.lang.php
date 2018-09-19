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
    'LBL_MODULE_NAME' => 'Sähköpostin arkistointi',
    'LBL_SNIP_SUMMARY' => "<p>Sähköpostiarkistointi on automaattinen tuontipalvelu, joka antaa käyttäjien tuota sähköposteja Sugariin. Tämä tapahtuu lähettämällä sähköpostit mistä tahansa sähköpostiohjelmasta Sugarin antamaan osoitteeseen. Jokaisella Sugar-instanssilla on oma osoitteensa.</p><p>Tuodakseen sähköposteja, käyttäjä lähettää viestin edellä mainittuun osoitteeseen käyttämällä <i>Vastaanottaja-</i>, <i>Kopio- </i> ja <i>Piilokopio</i>kenttiä. Sähköpostin arkistointipalvelu tuo sähköpostit Sugariin. Palvelu tuo sähköpostin sekä sen liitteet, kuvat ja kalenteritapahtumat, ja luo Sugariin tietueita jotka assosioidaan olemassa oleviin tietueissiin joissa on samoja sähköpostiosoitteita.</p><p>Esimerkki: käyttäjänä, kun katson asiakastietuetta, näen kaikki asiakkaaseen assosioidut sähköpostit. Assosiointi tapahtuu asiakastietueen <i>sähköposti</i>-kentän avulla. Näen myös kaikki sähköpostit, jotka assosioituvat asiakastietueeseen liittyviin kontakteihin.</p><p>Hyväksy alla olevat käyttöehdot ja klikkaa <i>Ota käyttöön</i> aloittaaksesi palvelun käytön. Voit poistaa palvelun käytöstä milloin vain. Kun palvelu on käytöstä, sen sähköpostiosoite tulee näytille.</p>",
	'LBL_REGISTER_SNIP_FAIL' => 'Virhe yhdistäessä sähköpostin arkistointipalveluun: %s.',
	'LBL_CONFIGURE_SNIP' => 'Sähköpostin arkistointi',
    'LBL_DISABLE_SNIP' => 'Poista käytöstä',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Sovelluksen uniikki avain',
    'LBL_SNIP_USER' => 'Sähköpostin arkistoinnin käyttäjä',
    'LBL_SNIP_PWD' => 'Sähköpostin arkistoinnin salasana',
    'LBL_SNIP_SUGAR_URL' => 'Tämän Sugar-instanssin URL',
	'LBL_SNIP_CALLBACK_URL' => 'Sähköpostiarkistointipalvelun URL',
    'LBL_SNIP_USER_DESC' => 'Sähköpostiarkistoinnin käyttäjä',
    'LBL_SNIP_KEY_DESC' => 'Sähköpostiarkistoinnin OAuth-avain. Käytetään instanssiin yhdistämiseen sähköpostin tuontia varten.',
    'LBL_SNIP_STATUS_OK' => 'Käytössä',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Tämän Sugar-instanssin yhteys sähköpostiarkistointipalvelimeen on hyvä.',
    'LBL_SNIP_STATUS_ERROR' => 'Virhe',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Tällä instanssilla on validi sähköpostiarkistointipalvelin, mutta palvelin palautti seuraavan virheen:',
    'LBL_SNIP_STATUS_FAIL' => 'Ei voitu rekisteröidä sähköpostiarkistointipalvelimeen',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Sähköpostien arkistointipalvelu ei ole tällä hetkellä saatavilla. Palvelu on joko alhaalla tai yhteys tähän Sugar-instanssiin epäonnistui.',
    'LBL_SNIP_GENERIC_ERROR' => 'Sähköpostien arkistointipalvelu ei ole tällä hetkellä saatavilla. Palvelu on joko alhaalla tai yhteys tähän Sugar-instanssiin epäonnistui.',

	'LBL_SNIP_STATUS_RESET' => 'Ei suoritettu vielä',
	'LBL_SNIP_STATUS_PROBLEM' => 'Ongelma: %s',
    'LBL_SNIP_NEVER' => "Ei koskaan",
    'LBL_SNIP_STATUS_SUMMARY' => "Sähköpostien arkistointipalvelun status:",
    'LBL_SNIP_ACCOUNT' => "Jäsen",
    'LBL_SNIP_STATUS' => "Tila",
    'LBL_SNIP_LAST_SUCCESS' => "Edellinen onnistunut suoritus",
    "LBL_SNIP_DESCRIPTION" => "Sähköpostiarkistointipalvelu on automaattinen sähköpostien arkistointipalvelu",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Se sallii sinun nähdä sähköposteja jotka lähetettiin tai vastaanotettiin SugarCRM-järjestelmässä oleville kontakteille, ilman, että sinun tarvitsee manuaalisesti tuoda ja linkittää sähköposteja.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Jotta voit käyttää sähköpostin arkistointia, sinun pitää ostaa lisenssi SugarCRM-instanssillesi",
    "LBL_SNIP_PURCHASE" => "Klikkaa tästä ostaaksesi",
    'LBL_SNIP_EMAIL' => 'Sähköpostiarkistoinnin osoite',
    'LBL_SNIP_AGREE' => "Hyväksyn yllä olevat ehdot ja <a href=&#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html&#39; target=&#39;_blank&#39;>tietosuojasopimuksen</a>.",
    'LBL_SNIP_PRIVACY' => 'tietosuojasopimus',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback epäonnistui',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'Sähköpostiarkistoinnin palvelin ei pysty yhdistämään Sugar-instansiisi. Yritä uudelleen tai ota yhteyttä <a href=&#39;http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=&#39; target=&#39;_blank&#39;>asiakaspalveluun</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Ota käyttöön sähköpostiarkistointi',
    'LBL_SNIP_BUTTON_DISABLE' => 'Poista sähköpostiarkistointi käytöstä',
    'LBL_SNIP_BUTTON_RETRY' => 'Yritä yhdistää uudelleen',
    'LBL_SNIP_ERROR_DISABLING' => 'Virhe tapahtui yritettäessä kommunikoida sähköpostiarkistoinnin palvelimen kanssa. Arkistointipalvelua ei voitu ottaa käyttöön.',
    'LBL_SNIP_ERROR_ENABLING' => 'Virhe tapahtui yritettäessä kommunikoida sähköpostiarkistoinnin palvelimen kanssa. Arkistointipalvelua ei voitu poistaa käytöstä.',
    'LBL_CONTACT_SUPPORT' => 'Yritä uudelleen, tai ota yhteyttä SugarCRM-tukeen.',
    'LBL_SNIP_SUPPORT' => 'Ota yhteyttä SugarCRM-tukeen apua varten.',
    'ERROR_BAD_RESULT' => 'Palvelu palautti huonon tulosarvon',
	'ERROR_NO_CURL' => 'cURL-lisäosat vaaditaan, mutta ne eivät ole päällä',
	'ERROR_REQUEST_FAILED' => 'Ei voitu yhdistää palvelimeen',

    'LBL_CANCEL_BUTTON_TITLE' => 'Peruuta',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Tässä näkyy Sugar-instanssisi sähköpostiarkistointipalvelun status. Tämä näyttää, kuinka hyvässä tilassa sähköpostiarkistoinnin palvelimen ja Sugar-instanssisi välinen yhteys on.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Tämä on sähköpostiarkistoinnin sähköpostiosoite, johon lähetetään viestejä jotka tuodaan Sugariin.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Tämä on sähköpostiarkistoinnin palvelimen URL. Kaikki arkistointiin kohdistuvat pyynnöt, kuten arkistoinnin käyttöön- ja käytöstä poisotto, lähetetään tähän osoitteeseen.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Tämä on Sugar-instanssisi <i>webservices</i>-URL. Sähköpostiarkistoinnin palvelin otta yhteyttä Sugar-palvelimeesi tämän URL:n kautta.',
);
