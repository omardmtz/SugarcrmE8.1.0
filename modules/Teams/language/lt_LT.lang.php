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
    'ERR_ADD_RECORD' => 'Turite nurodyti įrašo numerį, jei norite įtraukti vartotoją į šią komandą.',
    'ERR_DUP_NAME' => 'Komanda su tokiu pavadinimu jau yra, prašome pasirinkti kitą pavadinimą.',
    'ERR_DELETE_RECORD' => 'Prašome nurodyti įrašą, jei norite ištrinti šią komandą.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Klaida. Pasirinkta komanda  <b>({0})</b> yra skirta ištrynimui. Prašome pasirinkti kitą komandą.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Klaida. Jūs negalite ištrinti vartotojo, kol jo asmeninė komanda nebus pašalinta.',
    'LBL_DESCRIPTION' => 'Aprašymas:',
    'LBL_GLOBAL_TEAM_DESC' => 'Viešai matomas',
    'LBL_INVITEE' => 'Komandos nariai',
    'LBL_LIST_DEPARTMENT' => 'Skyrius',
    'LBL_LIST_DESCRIPTION' => 'Aprašymas',
    'LBL_LIST_FORM_TITLE' => 'Komandų sąrašas',
    'LBL_LIST_NAME' => 'Pavadinimas',
    'LBL_FIRST_NAME' => 'Vardas:',
    'LBL_LAST_NAME' => 'Pavardė:',
    'LBL_LIST_REPORTS_TO' => 'Kam pavaldus',
    'LBL_LIST_TITLE' => 'Pareigos',
    'LBL_MODULE_NAME' => 'Komandos',
    'LBL_MODULE_NAME_SINGULAR' => 'Komanda',
    'LBL_MODULE_TITLE' => 'Komandos: Pradžia',
    'LBL_NAME' => 'Komandos pavadinimas:',
    'LBL_NAME_2' => 'Komandos pavadinimas(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Pagrindinis komandos pavadinimas',
    'LBL_NEW_FORM_TITLE' => 'Nauja komanda',
    'LBL_PRIVATE' => 'Asmeninis',
    'LBL_PRIVATE_TEAM_FOR' => 'Asmeninė komanda:',
    'LBL_SEARCH_FORM_TITLE' => 'Komandos paieška',
    'LBL_TEAM_MEMBERS' => 'Komandos nariai',
    'LBL_TEAM' => 'Komandos:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Vartotojai',
    'LBL_USERS' => 'Vartotojai',
    'LBL_REASSIGN_TEAM_TITLE' => 'Šios komandos turi įrašų: <b>{0}</b><br>Prieš ištrinant komandas, reikia įrašus paskirti naujai komandai.  Prašome pasirinkti komandą, kuriai priskirsite.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Priskirti',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Priskirti [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Priskirti įrašus naujai komandai?',
    'LBL_REASSIGN_TABLE_INFO' => 'Atnaujinama lentelė {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operacija sėkmingai atlikta.',
    'LNK_LIST_TEAM' => 'Komandos',
    'LNK_LIST_TEAMNOTICE' => 'Komandiniai perspėjimai',
    'LNK_NEW_TEAM' => 'Sukurti komandą',
    'LNK_NEW_TEAM_NOTICE' => 'Create Team Notice',
    'NTC_DELETE_CONFIRMATION' => 'Ar Jūs tikrai norite ištrinti šį įrašą?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Ar Jūs tikrai norite pašalinti šį narį iš komandos?',
    'LBL_EDITLAYOUT' => 'Redaguoti išdėstymą' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Komandinės teisės',
    'LBL_TBA_CONFIGURATION_DESC' => 'Įgalinti komandinę prieigą ir ją valdyti pagal modulį.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Suteikti komandinių teisių',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Pasirinkti modulius, kuriuos reikia įgalinti',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Suteikę komandinių teisių, galėsite naudodami vaidmenų valdymo funkciją komandoms ir vartotojams priskirti konkrečių prieigos prie atskirų modulių teisių.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Išjungus komandines modulio teises, bus atkurti visi duomenys, susiję su to
modulio komandinėmis teisėmis, įskaitant šią funkciją naudojančius procesų apibrėžimus ir procesus. Tai apima visus vaidmenis, naudojančius
to modulio parinktį „Savininkas ir pasirinkta komanda“ ir visus to modulio įrašų komandinių teisių duomenis.
Taip pat rekomenduojame panaikinus bet kokio modulio komandines teises naudoti greito taisymo ir atstatymo įrankį bei
išvalyti savo sistemos podėlį.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Įspėjimas.</strong> Išjungus komandines modulio teises, bus atkurti visi duomenys, susiję su to modulio komandinėmis teisėmis, įskaitant šią funkciją naudojančius procesų apibrėžimus ir procesus. Tai apima visus vaidmenis, naudojančius
to modulio parinktį „Savininkas ir pasirinkta komanda“ ir visus to modulio įrašų komandinių teisių duomenis. Taip pat rekomenduojame panaikinus bet kokio modulio komandines teises naudoti greito taisymo ir atstatymo įrankį bei išvalyti savo sistemos podėlį.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Išjungus komandines modulio teises, bus atkurti visi duomenys, susiję su to
modulio komandinėmis teisėmis, įskaitant šią funkciją naudojančius procesų apibrėžimus ir procesus. Tai apima visus vaidmenis, naudojančius
to modulio parinktį „Savininkas ir pasirinkta komanda“ ir visus to modulio įrašų komandinių teisių duomenis.
Taip pat rekomenduojame panaikinus bet kokio modulio komandines teises naudoti greito taisymo ir atstatymo įrankį bei
išvalyti savo sistemos podėlį. Jei neturite prieigos prie greito taisymo ir atstatymo įrankio, kreipkitės į administratorių, turintį
prieigą prie meniu Taisymas.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Įspėjimas.</strong> Išjungus komandines modulio teises, bus atkurti visi duomenys, susiję su to modulio komandinėmis teisėmis, įskaitant šią funkciją naudojančius procesų apibrėžimus ir procesus. Tai apima visus vaidmenis, naudojančius to modulio parinktį „Savininkas ir pasirinkta komanda“ ir visus to modulio įrašų komandinių teisių duomenis. Taip pat rekomenduojame panaikinus bet kokio modulio komandines teises naudoti greito taisymo ir atstatymo įrankį bei išvalyti savo sistemos podėlį. Jei neturite prieigos prie greito taisymo ir atstatymo įrankio, kreipkitės į administratorių, turintį prieigą prie meniu Taisymas.
STR
,
);
