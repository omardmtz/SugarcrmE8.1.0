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
$mod_strings = array(
    'LBL_MODULE_NAME' => 'Privatësia e të dhënave',
    'LBL_MODULE_NAME_SINGULAR' => 'Privatësia e të dhënave',
    'LBL_NUMBER' => 'Numri',
    'LBL_TYPE' => 'Lloji',
    'LBL_SOURCE' => 'Burimi',
    'LBL_REQUESTED_BY' => 'Kërkuar nga',
    'LBL_DATE_OPENED' => 'Data e hapjes',
    'LBL_DATE_DUE' => 'Data e caktuar',
    'LBL_DATE_CLOSED' => 'Data e mbylljes',
    'LBL_BUSINESS_PURPOSE' => 'Qëllimet e biznesit u miratuan për',
    'LBL_LIST_NUMBER' => 'Numri',
    'LBL_LIST_SUBJECT' => 'Subjekti',
    'LBL_LIST_PRIORITY' => 'Priorieti',
    'LBL_LIST_STATUS' => 'Statusi',
    'LBL_LIST_TYPE' => 'Lloji',
    'LBL_LIST_SOURCE' => 'Burimi',
    'LBL_LIST_REQUESTED_BY' => 'Kërkuar nga',
    'LBL_LIST_DATE_DUE' => 'Data e caktuar',
    'LBL_LIST_DATE_CLOSED' => 'Data e mbylljes',
    'LBL_LIST_DATE_MODIFIED' => 'Data e modifikimit',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Modifikuar nga',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Përdoruesi i caktuar',
    'LBL_SHOW_MORE' => 'Shfaq aktivitetet e tjera të privatësisë së të dhënave',
    'LNK_DATAPRIVACY_LIST' => 'Shiko aktivitetet e privatësisë së të dhënave',
    'LNK_NEW_DATAPRIVACY' => 'Krijo aktivitetin e privatësisë së të dhënave',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Kandidatët',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktet',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Objektivat',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Llogaritë',
    'LBL_LISTVIEW_FILTER_ALL' => 'Të gjitha aktivitetet e privatësisë së të dhënave',
    'LBL_ASSIGNED_TO_ME' => 'Aktivitetet e mia të privatësisë së të dhënave',
    'LBL_SEARCH_AND_SELECT' => 'Kërko dhe zgjidh aktivitetet e privatësisë së të dhënave',
    'TPL_SEARCH_AND_ADD' => 'Kërko dhe shto aktivitetet e privatësisë së të dhënave',
    'LBL_WARNING_ERASE_CONFIRM' => 'Je gati për të fshirë përgjithmonë {0} fusha. Pasi fshirja të ketë përfunduar, nuk ka opsion për të rikuperuar të dhënat. Je i sigurt që dëshiron të vazhdosh?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Ke shënuar {0} fusha për fshirje. Konfirmimi do të anulojë fshirjen, do të ruajë të gjitha të dhënat, si dhe do ta shënojë kërkesën si të refuzuar. Je i sigurt që dëshiron të vazhdosh?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Je gati për ta shënuar kërkesën si të përfunduar. Kështu, statusi do të caktohet si "I përfunduar" dhe nuk mund të rihapet. Je i sigurt që dëshiron të vazhdosh?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Je gati për ta shënuar kërkesën si të refuzuar. Kështu, statusi do të caktohet si "I përfunduar" dhe nuk mund të rihapet. Je i sigurt që dëshiron të vazhdosh?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Ke krijuar me sukses aktivitetin e privatësisë së të dhënave <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Refuzo',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Përfundo',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Fshi dhe përfundo',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Fshi fushat e zgjedhura përmes nënpaneleve',
    'LBL_COUNT_FIELDS_MARKED' => 'Fushat e shënuara për fshirje',
    'LBL_NO_RECORDS_MARKED' => 'Nuk ka fusha ose të dhëna të shënuara për fshirje.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Paneli i regjistrit të privatësisë së të dhënave',

    // list view
    'LBL_HELP_RECORDS' => 'Moduli i të privatësisë së të dhënave monitoron aktivitetet e privatësisë, duke përfshirë miratimet dhe kërkesat e subjektit, për të ndihmuar procedurat e privatësisë së organizatës tënde. Krijo regjistrin e privatësisë së të dhënave në lidhje me regjistrin e një individi (p.sh. një kontakt) për të monitoruar miratimin ose për të ndërmarrë veprime në lidhje me kërkesën për privatësi.',
    // record view
    'LBL_HELP_RECORD' => 'Moduli i privatësisë së të dhënave monitoron aktivitetet e privatësisë, duke përfshirë kërkesat e miratimit dhe kërkesat e subjektit që mbështesin procedurat e privatësisë së organizatës tënde. Krijo regjistrime të privatësisë së të dhënave që lidhen me regjistrimin e një individi (si p.sh. një kontakt) për të mund të monitoruar pëlqimin ose për të ndërmarrë veprime në kërkesën për privatësi. Pasi veprimi i kërkuar të ketë përfunduar, përdoruesit në rolin e menaxherit të të dhënave personale mund të klikojnë "Përfundo" ose "Refuzo" për të përditësuar statusin.

Në rastin e kërkesave për fshirje, zgjidh "Shëno për fshirje" për çdo regjistrim të individit të renditur në nënpanelin e mëposhtëm. Pas zgjedhjes së të gjitha fushave të dëshiruara, duke klikuar "Fshi dhe përfundo" do të fshish përgjithmonë vlerat e fushave dhe regjistri i privatësisë së të dhënave do të shënohet si i përfunduar.',
);
