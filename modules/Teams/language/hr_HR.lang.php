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
    'ERR_ADD_RECORD' => 'Morate navesti broj zapisa da biste dodali korisnika ovom timu.',
    'ERR_DUP_NAME' => 'Naziv tima već je postojao, izaberite drugi.',
    'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da biste izbrisali ovaj tim.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Pogreška. Odabrani je tim <b>({0})</b> onaj koji ste izabrali izbrisati. Odaberite drugi tim.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Pogreška. Ne možete izbrisati korisnika čiji privatni tim nije izbrisan.',
    'LBL_DESCRIPTION' => 'Opis:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalno vidljivo',
    'LBL_INVITEE' => 'Članovi tima',
    'LBL_LIST_DEPARTMENT' => 'Odjel',
    'LBL_LIST_DESCRIPTION' => 'Opis',
    'LBL_LIST_FORM_TITLE' => 'Popis timova',
    'LBL_LIST_NAME' => 'Naziv',
    'LBL_FIRST_NAME' => 'Ime:',
    'LBL_LAST_NAME' => 'Prezime:',
    'LBL_LIST_REPORTS_TO' => 'Izvješća za',
    'LBL_LIST_TITLE' => 'Naslov',
    'LBL_MODULE_NAME' => 'Timovi',
    'LBL_MODULE_NAME_SINGULAR' => 'Tim',
    'LBL_MODULE_TITLE' => 'Timovi: Početna',
    'LBL_NAME' => 'Naziv tima:',
    'LBL_NAME_2' => 'Naziv tima (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Naziv primarnog tima',
    'LBL_NEW_FORM_TITLE' => 'Novi tim',
    'LBL_PRIVATE' => 'Privatno',
    'LBL_PRIVATE_TEAM_FOR' => 'Privatni tim za: ',
    'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje timova',
    'LBL_TEAM_MEMBERS' => 'Članovi tima',
    'LBL_TEAM' => 'Timovi:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Korisnici',
    'LBL_USERS' => 'Korisnici',
    'LBL_REASSIGN_TEAM_TITLE' => 'Nema zapisa dodijeljenih sljedećem timu (ili timovima): <b>{0}</b><br>Prije brisanja tim(ov)a morate preraspodijeliti te zapise novom timu. Odaberite tim koji ćete upotrijebiti kao zamjenu.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Preraspodijeli',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Preraspodijeli',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Nastaviti s ažuriranjem zahvaćenih zapisa za upotrebu novog tima?',
    'LBL_REASSIGN_TABLE_INFO' => 'Ažuriranje tablice {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Radnja je uspješno izvršena.',
    'LNK_LIST_TEAM' => 'Timovi',
    'LNK_LIST_TEAMNOTICE' => 'Obavijesti za tim',
    'LNK_NEW_TEAM' => 'Stvori tim',
    'LNK_NEW_TEAM_NOTICE' => 'Stvori obavijest za tim',
    'NTC_DELETE_CONFIRMATION' => 'Jeste li sigurni da želite izbrisati ovaj zapis?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Jeste li sigurni da želite ukloniti članstvo ovog korisnika?',
    'LBL_EDITLAYOUT' => 'Uredi izgled' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Dozvole na temelju tima',
    'LBL_TBA_CONFIGURATION_DESC' => 'Omogućite pristup timu i upravljajte pristupom po modulu.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Omogućite dozvole na temelju tima',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Odaberite module koje želite omogućiti',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Omogućavanje dozvola na temelju tima omogućit će vam dodjeljivanje određenih prava pristupa timovima i korisnicima za pojedinačne module putem upravljanja ulogama.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Onemogućavanje dozvola na temelju tima za modul vratit će podatke povezane s dozvolama na temelju tima za taj
 modul, uključujući definicije procesa ili procese koji upotrebljavaju značajku. To uključuje uloge koje upotrebljavaju
 mogućnost „Vlasnik i odabrani tim” za taj modul i podatke o dozvolama na temelju tima za zapise u tom modulu.
 Također preporučujemo da upotrebljavate alat Brzi popravak i ponovna izgradnja da biste izbrisali predmemoriju
 svojeg sustava nakon onemogućavanja dozvola na temelju tima za bilo koji modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Upozorenje:</strong> onemogućavanje dozvola na temelju tima za modul vratit će podatke povezane s
 dozvolama na temelju tima za taj modul, uključujući definicije procesa ili procese koji upotrebljavaju značajku. To
 uključuje uloge koje upotrebljavaju mogućnost „Vlasnik i odabrani tim” za taj modul i podatke o dozvolama na
 temelju tima za zapise u tom modulu. Također preporučujemo da upotrebljavate alat Brzi popravak i ponovna
 izgradnja da biste izbrisali predmemoriju svojeg sustava nakon onemogućavanja dozvola na temelju tima za bilo
 koji modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Onemogućavanje dozvola na temelju tima za modul vratit će podatke povezane s dozvolama na temelju tima za taj
 modul, uključujući definicije procesa ili procese koji upotrebljavaju značajku. To uključuje uloge koje upotrebljavaju
 mogućnost „Vlasnik i odabrani tim” za taj modul i podatke o dozvolama na temelju tima za zapise u tom modulu.
 Također preporučujemo da upotrebljavate alat Brzi popravak i ponovna izgradnja da biste izbrisali predmemoriju
 svojeg sustava nakon onemogućavanja dozvola na temelju tima za bilo koji modul. Ako nemate pristup upotrebi alata
 Brzi popravak i ponovna izgradnja, obratite se administratoru s pristupom izborniku za popravak.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Upozorenje:</strong> onemogućavanje dozvola na temelju tima za modul vratit će podatke povezane s
 dozvolama na temelju tima za taj modul, uključujući definicije procesa ili procese koji upotrebljavaju značajku. To
 uključuje uloge koje upotrebljavaju mogućnost „Vlasnik i odabrani tim” za taj modul i podatke o dozvolama na
 temelju tima za zapise u tom modulu. Također preporučujemo da upotrebljavate alat Brzi popravak i ponovna
 izgradnja da biste izbrisali predmemoriju svojeg sustava nakon onemogućavanja dozvola na temelju tima za bilo
 koji modul. Ako nemate pristup upotrebi alata Brzi popravak i ponovna izgradnja, obratite se administratoru s
 pristupom izborniku za popravak.
STR
,
);
