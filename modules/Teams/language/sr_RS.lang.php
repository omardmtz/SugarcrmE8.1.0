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
    'ERR_ADD_RECORD' => 'Morate navesti broj zapisa kako bi dodali korisnika ovom timu.',
    'ERR_DUP_NAME' => 'Ime tima već postoji, molim izaberite drugo ime.',
    'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa kako bi obrisali ovaj tim.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Greška.  Izabrani tim <b>({0})</b>je tim koji ste odabrali za brisanje.  Molim vas izaberite drugi tim.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Greška. Ne možete obrisati korisnika čiji privatni tim nije obrisan.',
    'LBL_DESCRIPTION' => 'Opis:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalno vidljivo',
    'LBL_INVITEE' => 'Članovi tima',
    'LBL_LIST_DEPARTMENT' => 'Odeljenje',
    'LBL_LIST_DESCRIPTION' => 'Opis',
    'LBL_LIST_FORM_TITLE' => 'Lista timova',
    'LBL_LIST_NAME' => 'Ime',
    'LBL_FIRST_NAME' => 'Ime:',
    'LBL_LAST_NAME' => 'Prezime:',
    'LBL_LIST_REPORTS_TO' => 'Nadređeni',
    'LBL_LIST_TITLE' => 'Titula',
    'LBL_MODULE_NAME' => 'Timovi',
    'LBL_MODULE_NAME_SINGULAR' => 'Tim',
    'LBL_MODULE_TITLE' => 'Timovi: Početna strana',
    'LBL_NAME' => 'Ime tima:',
    'LBL_NAME_2' => 'Ime Tima(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Ime Osnovnog Tima',
    'LBL_NEW_FORM_TITLE' => 'Novi tim',
    'LBL_PRIVATE' => 'Privatni',
    'LBL_PRIVATE_TEAM_FOR' => 'Privatni tim za:',
    'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje timova',
    'LBL_TEAM_MEMBERS' => 'Članovi tima',
    'LBL_TEAM' => 'Timovi:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Korisnici',
    'LBL_USERS' => 'Korisnici',
    'LBL_REASSIGN_TEAM_TITLE' => 'Ovo su zapisi dodeljeni sledećim tim(ovima): <b>{0}</b><br> Pre nego sto obrišete tim(ove), morate prvo ponovo dodeliti ove zapise novom timu. Izaberite tim koji će biti korišćen kao zamena.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Ponovo Dodeli',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Ponovo Dodeli [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Nastavite ažuriranje uključenih zapisa kako bi koristili novi tim?',
    'LBL_REASSIGN_TABLE_INFO' => 'Ažuriranje tabele {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operacija je uspešno završena.',
    'LNK_LIST_TEAM' => 'Timovi',
    'LNK_LIST_TEAMNOTICE' => 'Poruke tima',
    'LNK_NEW_TEAM' => 'Kreiraj tim',
    'LNK_NEW_TEAM_NOTICE' => 'Kreiraj Timsko obaveštenje',
    'NTC_DELETE_CONFIRMATION' => 'Da li ste sigurni da želite da obrišete ovaj zapis?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Da li ste sigurni da želite da uklonite članstvo ovog korisnika?',
    'LBL_EDITLAYOUT' => 'Izmeni raspored' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Dozvole na bazi tima',
    'LBL_TBA_CONFIGURATION_DESC' => 'Omogućite pristup timu i upravljajte pristupom putem modula.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Omogući dozvole na bazi tima',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Izaberite module koje želite da omogućite',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Ako omogućite dozvole na bazi tima, moći ćete da dodelite određena prava pristupa timovima i korisnicima za pojedinačne module putem Upravljanja ulogama.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Onemogućavanje dozvola na bazi tima za neki modul će vratiti sve podatke povezane sa dozvolama na bazi tima za taj
 modul, uključujuči sve Definicije procesa ili Procese koji koriste tu funkciju. To obuhvata sve Uloge koje koriste
 opciju „Vlasnik i izabrani tim“ za taj modul i sve podatke o dozvolama na bazi tima za zapise u tom modulu.
 Takođe preporučujemo da koristite alatku Brza popravka i ponovno sastavljanje kako biste obrisali keš sistema nakon onemogućavanja dozvola
 na bazi tima za bilo koji modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Upozorenje:</strong> Onemogućavanje dozvola na bazi tima za neki modul će vratiti sve podatke povezane sa
 dozvolama na bazi tima za taj modul, uključujuči sve Definicije procesa ili Procese koji koriste tu funkciju. To 
 obuhvata sve Uloge koje koriste opciju „Vlasnik i izabrani tim“ za taj modul i sve podatke o dozvolama na bazi tima
 za zapise u tom modulu. Takođe preporučujemo da koristite alatku Brza popravka i ponovno sastavljanje kako biste obrisali keš sistema
 nakon onemogućavanja dozvola na bazi tima za bilo koji modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Onemogućavanje dozvola na bazi tima za neki modul će vratiti sve podatke povezane sa dozvolama na bazi tima za taj
 modul, uključujuči sve Definicije procesa ili Procese koji koriste tu funkciju. To obuhvata sve Uloge koje koriste
 opciju „Vlasnik i izabrani tim“ za taj modul i sve podatke o dozvolama na bazi tima za zapise u tom modulu.
 Takođe preporučujemo da koristite alatku Brza popravka i ponovno sastavljanje kako biste obrisali keš sistema nakon onemogućavanja dozvola
 na bazi tima za bilo koji modul. Ako nemate pristup alatki Brza popravka i ponovno sastavljanje, obratite se administratoru
 koji ima pristup meniju za popravke.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Upozorenje:</strong> Onemogućavanje dozvola na bazi tima za neki modul će vratiti sve podatke povezane sa
 dozvolama na bazi tima za taj modul, uključujuči sve Definicije procesa ili Procese koji koriste tu funkciju. To
 obuhvata sve Uloge koje koriste opciju „Vlasnik i izabrani tim“ za taj modul i sve podatke o dozvolama na bazi tima za
 zapise u tom modulu. Takođe preporučujemo da koristite alatku Brza popravka i ponovno sastavljanje kako biste obrisali keš sistema nakon
 onemogućavanja dozvola na bazi tima za bilo koji modul. Ako nemate pristup alatki Brza popravka i ponovno sastavljanje, obratite
 se administratoru koji ima pristup meniju za popravke.
STR
,
);
