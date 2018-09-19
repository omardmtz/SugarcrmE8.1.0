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
    'LBL_MODULE_NAME' => 'Duomenų privatumas',
    'LBL_MODULE_NAME_SINGULAR' => 'Duomenų privatumas',
    'LBL_NUMBER' => 'Numeris',
    'LBL_TYPE' => 'Tipas',
    'LBL_SOURCE' => 'Šaltinis',
    'LBL_REQUESTED_BY' => 'Prašo',
    'LBL_DATE_OPENED' => 'Atidarymo data',
    'LBL_DATE_DUE' => 'Terminas',
    'LBL_DATE_CLOSED' => 'Uždarymo data',
    'LBL_BUSINESS_PURPOSE' => 'Verslo tikslais sutikote su',
    'LBL_LIST_NUMBER' => 'Numeris',
    'LBL_LIST_SUBJECT' => 'Tema',
    'LBL_LIST_PRIORITY' => 'Pirmenybė',
    'LBL_LIST_STATUS' => 'Būsena',
    'LBL_LIST_TYPE' => 'Tipas',
    'LBL_LIST_SOURCE' => 'Šaltinis',
    'LBL_LIST_REQUESTED_BY' => 'Prašo',
    'LBL_LIST_DATE_DUE' => 'Terminas',
    'LBL_LIST_DATE_CLOSED' => 'Uždarymo data',
    'LBL_LIST_DATE_MODIFIED' => 'Keitimo data',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Keitė',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Priskirtas vartotojas',
    'LBL_SHOW_MORE' => 'Rodyti daugiau duomenų privatumo veiklų',
    'LNK_DATAPRIVACY_LIST' => 'Peržiūrėti daugiau duomenų privatumo veiklų',
    'LNK_NEW_DATAPRIVACY' => 'Kurti duomenų privatumo veiklą',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Galimi klientai',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktai',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Adresatai',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Paskyros',
    'LBL_LISTVIEW_FILTER_ALL' => 'Visos duomenų privatumo veiklos',
    'LBL_ASSIGNED_TO_ME' => 'Mano duomenų privatumo veiklos',
    'LBL_SEARCH_AND_SELECT' => 'Ieškoti ir pasirinkti duomenų privatumo veiklas',
    'TPL_SEARCH_AND_ADD' => 'Ieškoti ir pridėti duomenų privatumo veiklas',
    'LBL_WARNING_ERASE_CONFIRM' => 'Ketinate visam laikui ištrinti {0} lauką (-us). Ištrynę, šių duomenų atkurti nebegalėsite. Ar tikrai norite tęsti?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Ištrinti pažymėtas (-i) {0} laukas (-ai). Patvirtinę atšauksite trynimą, išsaugosite duomenis ir pažymėsite šią užklausą kaip atmestą. Ar tikrai norite tęsti?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Ketinate pažymėti šią užklausą kaip baigtą. Tada visam laikui nustatoma būsena į „Baigta“ ir vėliau užklausos atidaryti nebebus galima. Ar tikrai norite tęsti?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Ketinate pažymėti šią užklausą kaip atmestą. Tada visam laikui nustatoma būsena į „Atmesta“ ir vėliau atidaryti užklausos nebebus galima. Ar tikrai norite tęsti?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Sėkmingai sukūrėte duomenų privatumo veiklą <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Atmesti',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Užbaigti',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Ištrinti ir užbaigti',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Ištrinti per antrinius skydelius pasirinktus laukus',
    'LBL_COUNT_FIELDS_MARKED' => 'Ištrinti pažymėti laukai',
    'LBL_NO_RECORDS_MARKED' => 'Nėra pažymėtų ištrinti laukų arba įrašų.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Duomenų privatumo įrašų ataskaitų sritis',

    // list view
    'LBL_HELP_RECORDS' => 'Duomenų privatumo modulis seka privatumo veiklas, įskaitant sutikimo ir temų užklausas, kad būtų palaikomos jūsų organizacijos privatumo procedūros. Kurkite duomenų privatumo įrašus, susietus su asmens įrašu (pvz., kontaktu), kad galėtumėte sekti sutikimą arba imtis veiksmų dėl privatumo užklausos.',
    // record view
    'LBL_HELP_RECORD' => 'Duomenų privatumo modulis seka privatumo veiklas, įskaitant sutikimo ir temų užklausas, kad būtų palaikomos jūsų organizacijos privatumo procedūros. Kurkite duomenų privatumo įrašus, susietus su asmens įrašu (pvz., kontaktu), kad galėtumėte sekti sutikimą arba imtis veiksmų dėl privatumo užklausos. Kai užbaigiamas reikiamas veiksmas, duomenų privatumo vadovo vaidmenį turintys vartotojai gali spustelėti „Užbaigti“ arba „Atmesti“ ir atnaujinti būseną.

Dėl ištrynimo užklausų kiekvienam antriniame skydelyje (žemiau) pateiktam asmens įrašui pasirinkite „Žymėti ištrinti“. Pažymėję visus norimus laukus, spustelėkite „Ištrinti ir užbaigti“ ir visam laikui pašalinkite laukų vertes, o duomenų privatumo įrašą pažymėkite kaip užbaigtą.',
);
