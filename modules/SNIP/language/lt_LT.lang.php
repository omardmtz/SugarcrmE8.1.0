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
    'LBL_MODULE_NAME' => 'El. pašto archyvavimas',
    'LBL_SNIP_SUMMARY' => "El. pašto archyvavimas yra paslauga, kuri laiškus siųstus iš bet kokio pašto kliento į spec. nurodytą el. pašo adresą, sukelia į SugarCRM programą. Vartotojui el. laiške tereikia nurodyti TO, CC, BCC lauke tą spec. el. pašo adresą ir laiškas bus įkeltas į SugarCRM. <br /><br />Patvirtinkite, kad sutinkate žemiau su naudojimo sąlygomis ir spauskite Įjungti, kad pradėti naudotis šia paslauga.",
	'LBL_REGISTER_SNIP_FAIL' => 'Nepavyko susisiekti su el. pašto archyvavimo servisu: %s!',
	'LBL_CONFIGURE_SNIP' => 'El. pašto archyvavimas',
    'LBL_DISABLE_SNIP' => 'Išjungti',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Aplikacijos unikalus raktas',
    'LBL_SNIP_USER' => 'El. pašto archyvavimo vartotojas',
    'LBL_SNIP_PWD' => 'El. pašto archyvavimo slaptažodis',
    'LBL_SNIP_SUGAR_URL' => 'Šio Sugar versijos adresas',
	'LBL_SNIP_CALLBACK_URL' => 'El. pašto archyvavimo serviso adresas',
    'LBL_SNIP_USER_DESC' => 'El. pašto archyvavimo vartotojas',
    'LBL_SNIP_KEY_DESC' => 'El. pašto archyvavimo OAuth raktas. Raktas naudojamas laiškų importavimui.',
    'LBL_SNIP_STATUS_OK' => 'Įjungtas',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Ši Sugar versija sėkmingai prisijungė prie el. pašto archyvavimo serverio.',
    'LBL_SNIP_STATUS_ERROR' => 'Klaida',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Jūs turite teisingą el. pašto archyvavimo serverio licenciją, tačiau serveris grąžino šią klaidą:',
    'LBL_SNIP_STATUS_FAIL' => 'Nepavyko užsiregistruoti per el. pašto archyvavimo serverį',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'El. pašto archyvavimo servisas šiuo metu nepasiekiamas. Arba servisas išjungtas arba prisijungimo prie Sugar sistemos nepavyko.',
    'LBL_SNIP_GENERIC_ERROR' => 'El. pašto archyvavimo servisas šiuo metu nepasiekiamas. Arba servisas išjungtas arba prisijungimo prie Sugar sistemos nepavyko.',

	'LBL_SNIP_STATUS_RESET' => 'Dar nevykdytas',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problema: %s',
    'LBL_SNIP_NEVER' => "Niekada",
    'LBL_SNIP_STATUS_SUMMARY' => "El. pašto archyvavimo serviso statusas:",
    'LBL_SNIP_ACCOUNT' => "Klientas",
    'LBL_SNIP_STATUS' => "Statusas",
    'LBL_SNIP_LAST_SUCCESS' => "Paskutinį kartą sėkmingai įvykdytas",
    "LBL_SNIP_DESCRIPTION" => "El. pašto archyvavimo servisas yra automatinė archyvavimo sistema",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Tai suteiks Jums galimybę skaityti ir tuos el. laiškus kurie buvo išsiųsti ir gauti ne per SugarCRM sistemą.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Kad galėtumėte naudotis el. pašto archyvavimo paslauga, Jūs turite nusipirkti licenciją šiai Sugar versijai.",
    "LBL_SNIP_PURCHASE" => "Spauskite čia, kad nusipirkti",
    'LBL_SNIP_EMAIL' => 'El. pašto archyvavimo adresas',
    'LBL_SNIP_AGREE' => "Aš sutinku su viršuje išdėstytomis sąlygomis ir konfidencialumo sutarimu.",
    'LBL_SNIP_PRIVACY' => 'konfidencialumo sutarimas',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Ping patikrinimas nepavyko',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'El. pašto archyvavimo serveris nesugebėjo susisiekti su Jūsų SugarCRM sistema. Prašome bandyti dar kartą.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Įjungti el. pašo archyvavimą',
    'LBL_SNIP_BUTTON_DISABLE' => 'Išjungti el. pašo archyvavimą',
    'LBL_SNIP_BUTTON_RETRY' => 'Bandykite jungtis vėl',
    'LBL_SNIP_ERROR_DISABLING' => 'Įvyko klaida jungiantis prie el. pašto archyvavimo serverio ir serviso nepavyko išjungti',
    'LBL_SNIP_ERROR_ENABLING' => 'Įvyko klaida jungiantis prie el. pašto archyvavimo serverio ir serviso nepavyko įjungti',
    'LBL_CONTACT_SUPPORT' => 'Prašome bandyti vėl arba susisiekite su SugarCRM palaikymu.',
    'LBL_SNIP_SUPPORT' => 'Prašome susisiekti su SugarCRM palaikymu.',
    'ERROR_BAD_RESULT' => 'Gauti blogi rezultatai iš serviso',
	'ERROR_NO_CURL' => 'Reikalinga cURL biblioteka. Šiuo metu ji nėra įjungta.',
	'ERROR_REQUEST_FAILED' => 'Nepavyko susisiekti su serveriu',

    'LBL_CANCEL_BUTTON_TITLE' => 'Atšaukti',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Tai el. pašto archyvavimo serverio statusas. Statusas pasako, ar jungtis tarp SugarCRM ir el. pašto archyvavimo serverio buvo sėkmingas.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Prašome siųsti į šį el. adresą, kad laiškai įsikelti į SugarCRM sistemą.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Tai el. pašto archyvavimo serverio nuoroda. Visi kreipiniai į el. pašto archyvavimo servisą pereis per šią nuorodą.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Tai Jūsų SugarCRM webservices adresas. El. pašto archyvavimo serveris jungsis būtent per šią internetinę nuorodą.',
);
