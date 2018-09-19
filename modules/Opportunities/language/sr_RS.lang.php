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
    // Dashboard Names
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Kontrolna tabla liste prodajnih prilika',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Kontrolna tabla zapisa prodajnih prilika',

    'LBL_MODULE_NAME' => 'Prodajne prilike',
    'LBL_MODULE_NAME_SINGULAR' => 'Prilika',
    'LBL_MODULE_TITLE' => 'Prodajne prilike: Početna strana',
    'LBL_SEARCH_FORM_TITLE' => 'Pretraga prodajnih prilika',
    'LBL_VIEW_FORM_TITLE' => 'Pregled prodajne prilike',
    'LBL_LIST_FORM_TITLE' => 'Lista prodajnih prilika',
    'LBL_OPPORTUNITY_NAME' => 'Ime prodajne prilike:',
    'LBL_OPPORTUNITY' => 'Prodajna prilika',
    'LBL_NAME' => 'Ime prodajne prilike',
    'LBL_INVITEE' => 'Kontakti',
    'LBL_CURRENCIES' => 'Valute',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Ime',
    'LBL_LIST_ACCOUNT_NAME' => 'Naziv kompanije',
    'LBL_LIST_DATE_CLOSED' => 'Zatvori',
    'LBL_LIST_AMOUNT' => 'Verovatno',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Ukupan Iznos',
    'LBL_ACCOUNT_ID' => 'ID broj kompanije:',
    'LBL_CURRENCY_RATE' => 'Kursna Lista',
    'LBL_CURRENCY_ID' => 'ID broj valute',
    'LBL_CURRENCY_NAME' => 'Ime valute',
    'LBL_CURRENCY_SYMBOL' => 'Simbol valute',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LISTA_PRODAJE_FAZA',
    'db_name' => 'LBL_IME',
    'db_amount' => 'LBL_LISTA_KOLIČINA',
    'db_date_closed' => 'LBL_LISTA_DATUM_ZATVORENO',
//END DON'T CONVERT
    'UPDATE' => 'Prodajne prilike - Ažuriranje valute',
    'UPDATE_DOLLARAMOUNTS' => 'Ažuriraj iznose Američkih dolara',
    'UPDATE_VERIFY' => 'Proveri iznose',
    'UPDATE_VERIFY_TXT' => 'Proverava da li je vrednost prodajne prilike ispravan decimalni broj koji sadrži samo numeričke karaktere (0-9) i decimale (.)',
    'UPDATE_FIX' => 'Ispravi iznose',
    'UPDATE_FIX_TXT' => 'Pokušava da ispravi pogrešne iznose, kreirajući ispravan decimalni broj od unesene količine. Svaki izmenjeni broj je sačuvan u bazi amount_backup. Ako se prilikom ove funkcije dogodi greška, ne pokušavajte ponovo da pokrenete bez povraćaja podataka iz rezervne pošto bi mogli da napravite novu rezervnu kopiju u koju bi se upisali novi nevažeći podaci.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Ažuriraj iznos Američkih dolara za prodajne prilike koje su zasnovane na tekućem kursu valute. Ova vrednost se koristi za proračunavanje grafika i pregleda kursne liste.',
    'UPDATE_CREATE_CURRENCY' => 'Kreiranje nove valute:',
    'UPDATE_VERIFY_FAIL' => 'Neuspela verifikacija zapisa:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Trenutni iznos:',
    'UPDATE_VERIFY_FIX' => 'Pokretanje popravke daće',
    'UPDATE_INCLUDE_CLOSE' => 'Uključi zatvorene zapise',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Novi iznos:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nova valuta:',
    'UPDATE_DONE' => 'Završeno',
    'UPDATE_BUG_COUNT' => 'Defekti koji su pronađeni i za koje će se tražiti rešenje:',
    'UPDATE_BUGFOUND_COUNT' => 'Nađeni defekti:',
    'UPDATE_COUNT' => 'Ažurirani zapisi:',
    'UPDATE_RESTORE_COUNT' => 'Obnovljeni iznosi zapisa:',
    'UPDATE_RESTORE' => 'Obnovi iznose',
    'UPDATE_RESTORE_TXT' => 'Rekonstruše vrednost iz rezervne kopije koja je kreirana tokom popravke.',
    'UPDATE_FAIL' => 'Ne mogu da ažuriram -',
    'UPDATE_NULL_VALUE' => 'Vrednost je NULL i biće postavljena na 0 -',
    'UPDATE_MERGE' => 'Spajanje valuta',
    'UPDATE_MERGE_TXT' => 'Svedi više valuta na jednu valutu. Ako postoji više zapisa valute za istu valutu, spoji zapise u jedan. Ovo će takođe svesti valute za sve ostale module.',
    'LBL_ACCOUNT_NAME' => 'Naziv kompanije:',
    'LBL_CURRENCY' => 'Valuta:',
    'LBL_DATE_CLOSED' => 'Očekivani datum zatvaranja:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Očekivani Datum Zatvaranja Vremenskog pečata',
    'LBL_TYPE' => 'Tip:',
    'LBL_CAMPAIGN' => 'Kampanja:',
    'LBL_NEXT_STEP' => 'Sledeći korak:',
    'LBL_LEAD_SOURCE' => 'Izvor informacije o potencijalnom klijentu:',
    'LBL_SALES_STAGE' => 'Faza prodaje:',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Verovatnoća (%):',
    'LBL_DESCRIPTION' => 'Opis:',
    'LBL_DUPLICATE' => 'Moguće duple prodajne prilike',
    'MSG_DUPLICATE' => 'Prodajna prilika koju želite da kreirate možda je duplikat već postojeće. Zapisi prodajnih prilika koji sadrže slična imena izlistani su ispod.<br>Kliknite Sačuvaj da bi nastavili kreiranje nove prodajne prilike, ili kliknite Otkaži da bi se vratili u modul bez kreiranja prodajne prilike.',
    'LBL_NEW_FORM_TITLE' => 'Kreiraj prodajnu priliku',
    'LNK_NEW_OPPORTUNITY' => 'Kreiraj prodajnu priliku',
    'LNK_CREATE' => 'Napravi ponudu',
    'LNK_OPPORTUNITY_LIST' => 'Pregled prodajnih prilika',
    'ERR_DELETE_RECORD' => 'Morate navesti broj zapisa da bi obrisali prodajnu priliku.',
    'LBL_TOP_OPPORTUNITIES' => 'Moje najbolje aktuelne prodajne prilike',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Da li ste sigurni da želite da uklonite ovaj kontakt iz prodajne prilike?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Da li ste sigurni da želite da uklonite ovu prodajnu priliku iz projekta?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Prodajne prilike',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Aktivnosti',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Istorija',
    'LBL_RAW_AMOUNT' => 'Neobrađen iznos',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Potencijalni klijenti',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakti',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokumenta',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekti',
    'LBL_ASSIGNED_TO_NAME' => 'Dodeljeno',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodeljeni korisnik',
    'LBL_LIST_SALES_STAGE' => 'Faza prodaje',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Moje zatvorene prodajne prilike',
    'LBL_TOTAL_OPPORTUNITIES' => 'Ukupno prodajnih prilika',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Zatvorene dobijene prodajne prilike',
    'LBL_ASSIGNED_TO_ID' => 'Dodeljeni korisnik:',
    'LBL_CREATED_ID' => 'Kreirano prema ID-u',
    'LBL_MODIFIED_ID' => 'ID broj korisnika koji je promenio',
    'LBL_MODIFIED_NAME' => 'Modifikovano prema korisničkom imenu',
    'LBL_CREATED_USER' => 'Kreirao',
    'LBL_MODIFIED_USER' => 'Promenio',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Kampanje',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projekti',
    'LABEL_PANEL_ASSIGNMENT' => 'Zadatak',
    'LNK_IMPORT_OPPORTUNITIES' => 'Uvezi prodajne prilike',
    'LBL_EDITLAYOUT' => 'Izmeni raspored' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID kampanje',
    'LBL_OPPORTUNITY_TYPE' => 'Tip prodajne prilike',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Ime dodeljenog korisnika',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID broj dodeljenog korisnika',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'ID korisnika koji je promenio',
    'LBL_EXPORT_CREATED_BY' => 'ID broj osobe koja je kreirala',
    'LBL_EXPORT_NAME' => 'Naziv',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Email adrese srodnih kontakata',
    'LBL_FILENAME' => 'Prilog',
    'LBL_PRIMARY_QUOTE_ID' => 'Primarni Citat',
    'LBL_CONTRACTS' => 'Ugovori',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Ugovori',
    'LBL_PRODUCTS' => 'Proizvodi',
    'LBL_RLI' => 'Stavke prihoda',
    'LNK_OPPORTUNITY_REPORTS' => 'Pregledaj izveštaje prodajnih prilika',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Ponude',
    'LBL_TEAM_ID' => 'ID broj tima',
    'LBL_TIMEPERIODS' => 'VremenskiPeriodi',
    'LBL_TIMEPERIOD_ID' => 'ID broj VremenskihPerioda',
    'LBL_COMMITTED' => 'Dodeljeno',
    'LBL_FORECAST' => 'Uključi u Prognozu',
    'LBL_COMMIT_STAGE' => 'Faza Izvršenja',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prognoza',
    'LBL_WORKSHEET' => 'Tabela',

    'TPL_RLI_CREATE' => 'Prodajna prilika mora da ima povezanu stavku prihoda. <a href="javascript:void(0);" id="createRLI">Napravite stavku prihoda</a>.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Napravi stavku prihoda.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Proizvodi',
    'LBL_RLI_SUBPANEL_TITLE' => 'Stavke prihoda',

    'LBL_TOTAL_RLIS' => 'Ukupno stavki prihoda',
    'LBL_CLOSED_RLIS' => 'Ukupno zatvorenih stavki prihoda',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Nije moguće obirsati prodajne prilike koje sadrže stavke prihoda.',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Jedan ili više izabranih zapisa sadrži zatvorene stavke prihoda i nije ih moguće obrisati.',
    'LBL_INCLUDED_RLIS' => '# od priloženih stavki linije prihoda',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Ponude',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Hijerarhija prodajnih prilika',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Vrednosti su sračunate od stavki Prihoda pa do Prodajnih prilika',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Levak prodaje ukupno je',

    'LBL_OPPORTUNITY_ROLE'=>'Uloga prodajne prilike',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Beleške',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Klikom na Potvrdi, biće obrisane svi podaci Prognoze i biće promenjen prikaz Prodajnih prilika. Ukoliko ovo nije ono što nameravate, kliknite na Poništi kako bi se vratili na prethodna podešavanja.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Ako kliknete na dugme „Potvrdi“, izbrisaćete SVE podatke o Prognozi i izmeniti prikaz Mogućnosti. '
        .'Takođe će biti onemogućene sve definicije procesa sa ciljnim modulom za stavke linije prihoda. '
        .'Ukoliko niste to želeli, kliknite na „Otkaži“ da biste se vratili na prethodne postavke.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Ukoliko su Prihodi zatvoreni i samo je jedan dobitan,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'faza Prodajnih prilika će biti podešena na ,,Zatvoreno dobitna".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Ukoliko su predmeti linije Prihoda u ,,Zatvoreno izgubljeno" prodajnoj fazi,',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'faza prodajne prilike je podešena na ,,Zatvoreno izgubljeno".',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Ukoliko su predmeti linije Prihoda otvoreni i dalje,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'prodajna prilika će biti označena sa najmanje naprednom fazom prodaje.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Kada pokrenete ovu promenu, zbirne beleške o stavci prihoda će biti ugrađena u pozadinu. Kada se beleške formiraju i kada budu dostupne, biće poslato obaveštenje na e-mail adresu sa Vašeg profila. Ako je Vaša instanca postavljena za {{forecasts_module}}, Sugar će Vam takođe poslati obaveštenje kada su Vaši {{module_name}} zapisi sinhronizovani sa {{forecasts_module}} modulom za i kada su dostupni za nov {{forecasts_module}}. Obratite pažnju da Vaša instanca mora da bude konfigurisana da pošalje e-mail putem Admin > E-mail postavke kako bi se obaveštenja poslala.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Kada pokrenete ovu promenu, zapisi o stavci prihoda će biti kreirani za svaki postojeći {{module_name}} u pozadini. Kada se stavke prihoda formiraju i kada budu dostupne, biće poslato obaveštenje na e-mail adresu sa Vašeg profila. Obratite pažnju da Vaša instanca mora da bude konfigurisana da pošalje e-mail putem Admin > E-mail postavke kako bi se obaveštenja poslala.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} omogućava praćenje pojedinačnih prodaja od početka do kraja. Svaki zapis o modulu {{module_name}} predstavlja potencijalnu prodaju i uključuje važne podatke o prodaji, kao i podatke povezane sa ostalim važnim zapisima kao što su moduli {{quotes_module}}, {{contacts_module}} itd. Modul {{module_name}} obično prolazi kroz nekoliko faza prodaje dok ne dobije oznaku „Zatvoreno kao uspelo” ili „Zatvoreno kao neuspelo”. Modul {{plural_module_name}} može dodatno da se iskoristi upotrebom Sugar-ovog modula {{forecasts_singular_module}}ing u svrhu razumevanja i predviđanja trendova prodaje, kao i usmeravanja poslovanja prema postizanju prodajnih kvota.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} omogućava praćenje pojedinačnih prodaja i stavki koje pripadaju tim prodajama od početka do kraja. Svaki zapis o modulu {{module_name}} predstavlja potencijalnu prodaju i uključuje važne podatke o prodaji, kao i podatke povezane sa drugim važnim zapisima kao što su moduli {{quotes_module}}, {{contacts_module}} itd.

- Uredite polja ovog zapisa tako što ćete kliknuti na pojedinačno polje ili dugme Uredi.
- Pogledajte ili izmenite veze sa drugim zapisima u podtablama tako što ćete prebaciti donje levo okno na „Prikaz podataka”.
- Pišite i pregledajte komentare korisnika i beležite istoriju promena u modulu {{activitystream_singular_module}} tako što ćete prebaciti donje levo okno na „Pregled aktivnosti”. 
- Pratite ili označite ovaj zapis kao omiljen pomoću ikona koje se nalaze desno od naziva zapisa.
- Dodatne radnje dostupne su u padajućem meniju Radnje koji se nalazi desno od dugmeta Uredi.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Modul {{plural_module_name}} omogućava praćenje pojedinačnih prodaja i stavki koje pripadaju tim prodajama od početka do kraja. Svaki zapis o modulu {{module_name}} predstavlja potencijalnu prodaju i uključuje važne podatke o prodaji, kao i podatke povezane sa drugim važnim zapisima kao što su moduli {{quotes_module}}, {{contacts_module}} itd.

Da biste kreirali modul {{module_name}}:
1. Unesite vrednosti polja po želji.
 - Polja označena „Obavezno” moraju da se popune pre čuvanja.
 - Kliknite na „Prikaži više” da biste otkrili dodatna polja ako je potrebno.
2. Kliknite na „Sačuvaj” da biste završili novi zapis i vratili se na prethodnu stranicu.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sinhronizuj sa Marketo&reg;',
    'LBL_MKTO_ID' => 'Marketo Glavni ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Top 10 mogućnosti prodaje',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Prikazuje deset najboljih prodajnih prilika kružnim grafikonom.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Moje prodajne prilike',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Prodajne prilike moga tima",
);
