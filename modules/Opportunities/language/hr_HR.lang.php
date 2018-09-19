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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Nadzorna ploča za popis prilika',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Nadzorna ploča za zapise o prilikama',

    'LBL_MODULE_NAME' => 'Prilike',
    'LBL_MODULE_NAME_SINGULAR' => 'Prilika',
    'LBL_MODULE_TITLE' => 'Prilike: Početna stranica',
    'LBL_SEARCH_FORM_TITLE' => 'Pretraživanje prilika',
    'LBL_VIEW_FORM_TITLE' => 'Prikaz prilika',
    'LBL_LIST_FORM_TITLE' => 'Popis prilika',
    'LBL_OPPORTUNITY_NAME' => 'Naziv prilike:',
    'LBL_OPPORTUNITY' => 'Prilika:',
    'LBL_NAME' => 'Naziv prilike',
    'LBL_INVITEE' => 'Kontakti',
    'LBL_CURRENCIES' => 'Valute',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Naziv',
    'LBL_LIST_ACCOUNT_NAME' => 'Naziv računa',
    'LBL_LIST_DATE_CLOSED' => 'Očekivani datum zatvaranja',
    'LBL_LIST_AMOUNT' => 'Vjerojatno',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Pretvoreni iznos',
    'LBL_ACCOUNT_ID' => 'ID računa',
    'LBL_CURRENCY_RATE' => 'Valutni tečaj',
    'LBL_CURRENCY_ID' => 'ID valute',
    'LBL_CURRENCY_NAME' => 'Naziv valute',
    'LBL_CURRENCY_SYMBOL' => 'Simbol valute',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Prilika – ažuriranje valute',
    'UPDATE_DOLLARAMOUNTS' => 'Ažuriraj iznose u američkim dolarima',
    'UPDATE_VERIFY' => 'Provjeri iznose',
    'UPDATE_VERIFY_TXT' => 'Provjerava jesu li vrijednosti iznosa u prilikama valjani decimalni brojevi koji sadrže samo brojčane znakove (0 – 9) i decimalne znakove (,)',
    'UPDATE_FIX' => 'Popravi iznose',
    'UPDATE_FIX_TXT' => 'Pokušava popraviti nevaljane iznose stvaranjem valjanog decimalnog broja od trenutačnog iznosa. Izmijenjeni iznos sigurnosno se kopira u polje baze podataka „amount_backup”. Ako pokrenete ovu funkciju i primijetite pogreške, nemojte je ponovno pokrenuti prije vraćanja iz sigurnosne kopije jer to može prebrisati sigurnosnu kopiju novim nevaljanim podacima.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Ažurirajte iznose u američkim dolarima za prilike na temelju trenutačno postavljenih valutnih tečajeva. Ta se vrijednost upotrebljava za izračun iznosa valute u grafikonima i prikazu popisa.',
    'UPDATE_CREATE_CURRENCY' => 'Stvaranje nove valute:',
    'UPDATE_VERIFY_FAIL' => 'Neuspjela provjera zapisa:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Trenutačni iznos:',
    'UPDATE_VERIFY_FIX' => 'Pokretanje Popravljanja dalo bi',
    'UPDATE_INCLUDE_CLOSE' => 'Uključi zatvorene zapise',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Novi iznos:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nova valuta:',
    'UPDATE_DONE' => 'Dovršeno',
    'UPDATE_BUG_COUNT' => 'Broj pogrešaka koje su pronađene i koje se pokušalo riješiti:',
    'UPDATE_BUGFOUND_COUNT' => 'Pronađeno pogrešaka:',
    'UPDATE_COUNT' => 'Ažurirano zapisa:',
    'UPDATE_RESTORE_COUNT' => 'Vraćeno iznosa zapisa:',
    'UPDATE_RESTORE' => 'Vrati iznose',
    'UPDATE_RESTORE_TXT' => 'Vraća vrijednosti iznosa iz sigurnosnih kopija stvorenih tijekom popravljanja.',
    'UPDATE_FAIL' => 'Nije moguće ažurirati - ',
    'UPDATE_NULL_VALUE' => 'Iznos ima vrijednost NULL kad je postavljen na 0 -',
    'UPDATE_MERGE' => 'Spoji valute',
    'UPDATE_MERGE_TXT' => 'Spojite više valuta u jednu. Ako postoji više zapisa s valutama za jednu valutu, spojite ih. To će također spojiti valute za sve ostale module.',
    'LBL_ACCOUNT_NAME' => 'Naziv računa:',
    'LBL_CURRENCY' => 'Valuta:',
    'LBL_DATE_CLOSED' => 'Očekivani datum zatvaranja:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Vremenska oznaka očekivanog datuma zatvaranja',
    'LBL_TYPE' => 'Vrsta:',
    'LBL_CAMPAIGN' => 'Kampanja:',
    'LBL_NEXT_STEP' => 'Sljedeći korak:',
    'LBL_LEAD_SOURCE' => 'Izvor poten. klijenta',
    'LBL_SALES_STAGE' => 'Faza prodaje',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Vjerojatnost (%)',
    'LBL_DESCRIPTION' => 'Opis',
    'LBL_DUPLICATE' => 'Mogući duplikat prilike',
    'MSG_DUPLICATE' => 'Zapis o prilici koji ćete stvoriti možda je duplikat već postojećeg zapisa o prilici. Zapisi o prilikama koji sadrže slična imena navedeni su u nastavku.<br>Kliknite na Spremi za nastavak stvaranja ove nove prilike ili kliknite na Odustani za povratak na modul bez stvaranja prilike.',
    'LBL_NEW_FORM_TITLE' => 'Stvori priliku',
    'LNK_NEW_OPPORTUNITY' => 'Stvori priliku',
    'LNK_CREATE' => 'Stvori ponudu',
    'LNK_OPPORTUNITY_LIST' => 'Prikaži prilike',
    'ERR_DELETE_RECORD' => 'Broj zapisa mora biti naveden za brisanje prilike.',
    'LBL_TOP_OPPORTUNITIES' => 'Moje najbolje otvorene prilike',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Jeste li sigurni da želite ukloniti ovaj kontakt iz prilike?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Jeste li sigurni da želite ukloniti ovu priliku iz projekta?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Prilike',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Aktivnosti',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Povijest',
    'LBL_RAW_AMOUNT' => 'Bruto iznos',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Pot. klij.',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakti',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokumenti',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekti',
    'LBL_ASSIGNED_TO_NAME' => 'Dodijeljeno:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Dodijeljeni korisnik',
    'LBL_LIST_SALES_STAGE' => 'Faza prodaje',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Moje zatvorene prilike',
    'LBL_TOTAL_OPPORTUNITIES' => 'Ukupne prilike',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Prilike zatvorene kao uspjele',
    'LBL_ASSIGNED_TO_ID' => 'Dodijeljeni korisnik:',
    'LBL_CREATED_ID' => 'Stvorio ID',
    'LBL_MODIFIED_ID' => 'Izmijenio ID',
    'LBL_MODIFIED_NAME' => 'Izmijenilo korisničko ime',
    'LBL_CREATED_USER' => 'Stvoreni korisnik',
    'LBL_MODIFIED_USER' => 'Izmijenjeni korisnik',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Prilika kampanje',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projekti',
    'LABEL_PANEL_ASSIGNMENT' => 'Zadatak',
    'LNK_IMPORT_OPPORTUNITIES' => 'Uvezi prilike',
    'LBL_EDITLAYOUT' => 'Uredi izgled' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID kampanje',
    'LBL_OPPORTUNITY_TYPE' => 'Vrsta prilike',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Ime dodijeljenog korisnika',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID dodijeljenog korisnika',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Izmijenio ID',
    'LBL_EXPORT_CREATED_BY' => 'Stvorio ID',
    'LBL_EXPORT_NAME' => 'Naziv',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'E-pošta povezanih kontakata',
    'LBL_FILENAME' => 'Prilog',
    'LBL_PRIMARY_QUOTE_ID' => 'Glavna ponuda',
    'LBL_CONTRACTS' => 'Ugovori',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Ugovori',
    'LBL_PRODUCTS' => 'Prodane stavke',
    'LBL_RLI' => 'Stavke prihoda',
    'LNK_OPPORTUNITY_REPORTS' => 'Prikaz izvješća o prilikama',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Ponude',
    'LBL_TEAM_ID' => 'ID tima',
    'LBL_TIMEPERIODS' => 'Vremenska razdoblja',
    'LBL_TIMEPERIOD_ID' => 'ID vremenskog razdoblja',
    'LBL_COMMITTED' => 'Potvrđeno',
    'LBL_FORECAST' => 'Uključi u predviđanje',
    'LBL_COMMIT_STAGE' => 'Faza potvrđivanja',
    'LBL_COMMIT_STAGE_FORECAST' => 'Predviđanje',
    'LBL_WORKSHEET' => 'Radni list',

    'TPL_RLI_CREATE' => 'Prilika mora imati povezanu stavku prihoda.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Stvorite stavku prihoda.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Prodane stavke',
    'LBL_RLI_SUBPANEL_TITLE' => 'Stavke prihoda',

    'LBL_TOTAL_RLIS' => '# od ukupnih stavki prihoda',
    'LBL_CLOSED_RLIS' => '# od zatvorenih stavki prihoda',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Ne možete izbrisati prilike koje sadrže zatvorene stavke prihoda',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Jedan ili više odabranih zapisa sadrži zatvorene stavke prihoda i ne može se izbrisati.',
    'LBL_INCLUDED_RLIS' => '# od uključenih stavki prihoda',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Ponude',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Hijerarhija prilika',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Postavite polje Očekivani datum zatvaranja u zapisima rezultirajućih prilika kao najraniji ili najkasniji datum zatvaranja postojećih stavki prihoda',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Prodajni kanal ukupno iznosi ',

    'LBL_OPPORTUNITY_ROLE'=>'Uloga u prilici',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Napomene',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Klikom na Potvrdi izbrisat ćete SVE podatke o predviđanjima i promijeniti svoj prikaz prilika. Ako to ne želite učiniti, kliknite na Odustani za povratak na prethodne postavke.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Klikom na Potvrdi izbrisat ćete SVE podatke o predviđanjima i promijeniti svoj prikaz prilika. '
        .'Onemogućit će se i SVE definicije procesa s modulom meta stavki prihoda. '
        .'Ako to ne želite učiniti, kliknite na Odustani za povratak na prethodne postavke.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Ako su sve stavke prihoda zatvorene i barem je jedna uspjela,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'faza prodaje prilike postavljena je na „Zatvoreno kao uspjelo”.',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Ako su sve stavke prihoda u fazi prodaje „Zatvoreno kao neuspjelo”,',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'faza prodaje prilike postavljena je na „Zatvoreno kao neuspjelo”.',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Ako su neke stavke prihoda još otvorene,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'prilika će se označiti najmanje naprednom fazom prodaje.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Nakon što započnete ovu promjenu, bilješke za sažimanje stavke prihoda ugradit će se u pozadinu. Kada bilješke budu dovršene i dostupne, poslat će se obavijest na adresu e-pošte na vašem korisničkom profilu. Ako je vaša instanca postavljena na {{forecasts_module}}, Sugar će vam također poslati obavijest kad vaši zapisi o modulu {{module_name}} budu sinkronizirani s modulom {{forecasts_module}} i dostupni za novi modul {{forecasts_module}}. Imajte na umu da instanca mora biti konfigurirana za slanje e-pošte putem Admin > Postavke e-pošte da bi se obavijesti poslale.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Nakon što započnete ovu promjenu, zapisi stavki prihoda bit će stvoreni za svaki postojeći modul {{module_name}} u pozadini. Kada stavke prihoda budu dovršene i dostupne, poslat će se obavijest na adresu e-pošte na vašem korisničkom profilu. Imajte na umu da instanca mora biti konfigurirana za slanje e-pošte putem Admin > Postavke e-pošte da bi se obavijesti poslale.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Modul {{plural_module_name}} omogućuje praćenje pojedinačnih prodaja od početka do kraja. Svaki zapis o modulu {{module_name}} predstavlja potencijalnu prodaju i uključuje važne podatke o prodaji, kao i podatke povezane s ostalim važnim zapisima kao što su moduli {{quotes_module}}, {{contacts_module}} itd. Modul {{module_name}} obično prolazi kroz nekoliko faza prodaje dok ne dobije oznaku „Zatvoreno kao uspjelo” ili „Zatvoreno kao neuspjelo”. Modul {{plural_module_name}} može se dodatno iskoristiti upotrebom Sugarova modula {{forecasts_singular_module}}ing u svrhu razumijevanja i predviđanja trendova prodaje, kao i usmjeravanja poslovanja prema postizanju prodajnih kvota.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Modul {{plural_module_name}} omogućuje praćenje pojedinačnih prodaja i stavki koje pripadaju tim prodajama od početka do kraja. Svaki zapis o modulu {{module_name}} predstavlja potencijalnu prodaju i uključuje važne podatke o prodaji, kao i podatke povezane s ostalim važnim zapisima kao što su moduli {{quotes_module}}, {{contacts_module}} itd.

- Uredite polja ovog zapisa tako da kliknete na pojedino polje ili gumb Uredi.
- Pogledajte ili izmijenite poveznice na ostale zapise u podpločama tako da prebacite donje lijevo okno na „Prikaz podataka”.
- Pišite i pregledavajte komentare korisnika i bilježite povijest promjena u modulu {{activitystream_singular_module}} tako da prebacite donje lijevo okno na „Pregled aktivnosti”. 
- Slijedite ili označite ovaj zapis kao omiljen s pomoću ikona koje se nalaze desno od naziva zapisa.
- Dodatne radnje dostupne su u padajućem izborniku Radnje koji se nalazi desno od gumba Uredi.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Modul {{plural_module_name}} omogućuje praćenje pojedinačnih prodaja i stavki koje pripadaju tim prodajama od početka do kraja. Svaki zapis o modulu {{module_name}} predstavlja potencijalnu prodaju i uključuje važne podatke o prodaji, kao i podatke povezane s ostalim važnim zapisima kao što su moduli {{quotes_module}}, {{contacts_module}} itd.

Stvaranje modula {{module_name}}:
1. Unesite vrijednosti polja po želji.
 - Polja označena „Obavezno” moraju se ispuniti prije spremanja.
 - Kliknite na „Prikaži više” da biste otkrili dodatna polja ako je potrebno.
2. Kliknite na „Spremi” da biste završili novi zapis i vratili se na prethodnu stranicu.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sinkroniziraj na Marketo&reg;',
    'LBL_MKTO_ID' => 'ID pot. klijenta Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => '10 najboljih prodajnih prilika',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Prikazuje deset najboljih prilika u mjehuričastom grafikonu.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Moje prilike',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Prilike mojeg tima",
);
