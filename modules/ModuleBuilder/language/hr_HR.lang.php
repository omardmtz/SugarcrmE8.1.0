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
    'LBL_LOADING' => 'Učitavanje' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Sakrij mogućnosti' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Izbriši' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Omogućuje SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Uloga',
'help'=>array(
    'package'=>array(
            'create'=>'Navedite <b>naziv</b> za paket. Naziv mora započinjati slovom i može se sastojati samo od slova, brojeva i podcrta. Ne smiju se upotrijebiti razmaci ili ostali posebni znakovi. (Primjer: HR_upravljanje)<br/><br/> Možete navesti informacije o <b>autoru</b> i <b>opisu</b> paketa. <br/><br/>Kliknite na <b>Spremi</b> da biste stvorili paket.',
            'modify'=>'Svojstva i moguće radnje za <b>paket</b> prikazane su ovdje.<br><br>Možete izmijeniti <b>naziv</b>, <b>autora</b> i <b>opis</b> paketa, kao i vidjeti i prilagoditi sve module sadržane u paketu.<br><br>Kliknite na <b>Novi modul</b> da biste stvorili modul za paket.<br><br>Ako paket sadrži barem jedan modul, možete <b>objaviti</b> i <b>implementirati</b> paket, kao i <b>izvesti</b> prilagodbe napravljene u paketu.',
            'name'=>'Ovo je <b>naziv</b> trenutačnog paketa. <br/><br/>Naziv mora započinjati slovom i može se sastojati samo od slova, brojeva i podcrta. Ne smiju se upotrijebiti razmaci ili ostali posebni znakovi. (Primjer: HR_upravljanje)',
            'author'=>'Ovo je <b>autor</b> koji se prikazuje tijekom instalacije kao naziv entiteta koji je stvorio paket.<br><br>Autor može biti pojedinac ili tvrtka.',
            'description'=>'Ovo je <b>opis</b> paketa koji se prikazuje tijekom instalacije.',
            'publishbtn'=>'Kliknite na <b>Objavi</b> da biste spremili sve unesene podatke i stvorili .zip datoteku koja je verzija paketa koja se može instalirati.<br><br>Upotrijebite <b>Učitavač modula</b> da biste učitali .tip datoteku i instalirali paket.',
            'deploybtn'=>'Kliknite na <b>Implementiraj</b> da biste spremili sve unesene podatke i instalirali paket, uključujući sve module, u trenutačnoj instanci.',
            'duplicatebtn'=>'Kliknite na <b>Dupliciraj</b> da biste kopirali sadržaj paketa u novi paket i prikazali novi paket. <br/><br/>Za novi paket automatski će se generirati novi naziv dodavanjem broja na kraj naziva paketa koji se upotrijebio za stvaranje novog. Možete preimenovati novi paket tako da unesete novi <b>naziv</b> i kliknete na <b>Spremi</b>.',
            'exportbtn'=>'Kliknite na <b>Izvezi</b> da biste stvorili .zip datoteku koja sadrži prilagodbe napravljene u paketu.<br><br> Generirana datoteka nije verzija paketa koja se može instalirati.<br><br>Upotrijebite <b>Učitavač modula</b> za uvoz .zip datoteke i omogućavanje prikaza paketa i prilagodbi u Učitavaču modula.',
            'deletebtn'=>'Kliknite na <b>Izbriši</b> da biste izbrisali paket i sve datoteke povezane s tim paketom.',
            'savebtn'=>'Kliknite na <b>Spremi</b> da biste spremili sve unesene podatke povezane s paketom.',
            'existing_module'=>'Kliknite na ikonu <b>Modul</b> da biste uredili svojstva i prilagodili polja, odnose i izglede povezane s modulom.',
            'new_module'=>'Kliknite na <b>Novi modul</b> da biste stvorili novi modul za ovaj paket.',
            'key'=>'Ovaj peteroznamenkasti alfanumerički <b>ključ</b> upotrebljavat će se za dodavanje prefiksa svim direktorijima, nazivima klase i tablicama baze podataka za sve module u trenutačnom paketu.<br><br>Ključ se upotrebljava u svrhu postizanja jedinstvenosti naziva tablica.',
            'readme'=>'Kliknite da biste dodali tekst <b>„PročitajMe”</b> za ovaj paket.<br><br>„PročitajMe” će biti dostupan u vrijeme instalacije.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Navedite <b>naziv</b> za modul. <b>Oznaka</b> koju navedete prikazivat će se u navigacijskoj kartici. <br/><br/>Izaberite prikaz navigacijske kartice za modul tako da označite potvrdni okvir <b>„Navigacijska kartica”</b>.<br/><br/>Označite potvrdni okvir <b>„Sigurnost tima”</b> da biste imali polje za odabir tima u zapisima modula. <br/><br/>Zatim izaberite vrstu modula koju želite stvoriti. <br/><br/>Odaberite vrstu predloška. Svaki predložak sadrži određeni skup polja, kao i prethodno određene izglede, koje možete upotrijebiti kao temelj za svoj modul. <br/><br/>Kliknite na <b>Spremi</b> da biste stvorili modul.',
        'modify'=>'Možete promijeniti svojstva modula ili prilagoditi <b>polja</b>, <b>odnose</b> i <b>izglede</b> povezane s modulom.',
        'importable'=>'Označivanje potvrdnog okvira <b>„Moguće uvesti”</b> omogućit će uvoz za ovaj modul.<br><br>Poveznica na čarobnjak za uvoz prikazat će se na ploči s prečacima u modulu.  Čarobnjak za uvoz olakšava uvoz podataka iz vanjskih izvora u prilagođeni modul.',
        'team_security'=>'Označivanjem potvrdnog okvira <b>„Sigurnost tima”</b> omogućit će se sigurnost tima za ovaj modul.  <br/><br/>Ako je sigurnost tima omogućena, prikazat će se polje za odabir tima u zapisima modula ',
        'reportable'=>'Označavanje ovog okvira omogućit će pokretanje izvješća u ovom modulu.',
        'assignable'=>'Označavanje ovog okvira omogućit će dodjeljivanje zapisa u ovom modulu odabranom korisniku.',
        'has_tab'=>'Označavanje okvira <b>„Navigacijska kartica”</b> pružit će navigacijsku karticu za modul.',
        'acl'=>'Označavanje ovog okvira omogućit će kontrole pristupa na ovom modulu, uključujući sigurnost razine polja.',
        'studio'=>'Označavanje ovog okvira omogućit će administratorima prilagođavanje ovog modula u Studiju.',
        'audit'=>'Označavanje ovog okvira omogućit će nadzor za ovaj modul. Promjene u određenim poljima bit će zabilježene tako da administratori mogu pregledati povijest promjena.',
        'viewfieldsbtn'=>'Kliknite na <b>Prikaži polja</b> da biste vidjeli polja povezana s modulom te stvorili i uredili prilagođena polja.',
        'viewrelsbtn'=>'Kliknite na <b>Prikaži odnose</b> da biste vidjeli odnose povezane s ovim modulom i stvorili nove odnose.',
        'viewlayoutsbtn'=>'Kliknite na <b>Prikaži izglede</b> da biste vidjeli izglede za modul i prilagodili raspored polja u izgledima.',
        'viewmobilelayoutsbtn' => 'Kliknite na <b>Prikaži mobilne izglede</b> da biste vidjeli mobilne izglede za modul i prilagodili raspored polja u izgledima.',
        'duplicatebtn'=>'Kliknite na <b>Dupliciraj</b> da biste kopirali svojstva modula u novi modul i prikazali novi modul. <br/><br/>Naziv novog modula automatski će se generirati dodavanjem broja na kraj naziva modula upotrijebljenog za stvaranje novog.',
        'deletebtn'=>'Kliknite na <b>Izbriši</b> da biste izbrisali ovaj modul.',
        'name'=>'Ovo je <b>naziv</b> trenutačnog modula.<br/><br/>Naziv mora biti alfanumerički i mora započinjati slovom te ne smije sadržavati razmake. (Primjer: HR_upravljanje)',
        'label'=>'Ovo je <b>oznaka</b> koja će se prikazivati u navigacijskoj kartici za modul. ',
        'savebtn'=>'Kliknite na <b>Spremi</b> da biste spremili sve unesene podatke povezane s modulom.',
        'type_basic'=>'<b>Osnovna</b> vrsta predloška pruža osnovna polja, poput polja Naziv, Dodijeljeno, Tim, Datum stvaranja i Opis.',
        'type_company'=>'Vrsta predloška <b>Tvrtka</b> pruža polja povezana s organizacijom, poput polja Naziv tvrtke, Industrija i Adresa za naplatu.<br/><br/>Upotrijebite ovaj predložak za stvaranje modula koji su slični standardnom modulu računa.',
        'type_issue'=>'Vrsta predloška <b>Problem</b> pruža polja povezana sa slučajevima i pogreškama, poput polja Broj, Status, Prioritet i Opis.<br/><br/>Upotrijebite ovaj modul za stvaranje modula koji su slični standardnim modulima slučajeva i pratitelja pogrešaka.',
        'type_person'=>'Vrsta predloška <b>Osoba</b> pruža polja povezana s pojedincima, poput polja Oslovljavanje, Titula, Ime, Adresa i Telefonski broj.<br/><br/>Upotrijebite ovaj predložak za stvaranje modula koji su slični standardnim modulima kontakata i potencijalnih klijenata.',
        'type_sale'=>'Vrsta predloška <b>Prodaja</b> pruža polja povezana s prilikama, poput polja Izvor potencijalnog klijenta, Faza, Iznos i Vjerojatnost. <br/><br/>Upotrijebite ovaj predložak za stvaranje modula koji su slični standardnom modulu prilika.',
        'type_file'=>'Predložak <b>Datoteka</b> pruža polja povezana s dokumentima, poput polja Naziv datoteke, Vrsta dokumenta i Datum izdavanja.<br><br>Upotrijebite ovaj predložak da biste stvorili module koji su slični standardnom modulu dokumenata.',

    ),
    'dropdowns'=>array(
        'default' => 'Svi <b>padajući izbornici</b> za aplikaciju navedeni su ovdje.<br><br>Padajući izbornici mogu se upotrebljavati za padajuća polja u bilo kojem modulu.<br><br>Za promjene postojećeg padajućeg izbornika kliknite na naziv padajućeg izbornika.<br><br>Kliknite <b>Dodaj padajući izbornik</b> da biste stvorili novi padajući izbornik.',
        'editdropdown'=>'Padajući popisi mogu se upotrebljavati za standardna ili prilagođena polja u bilo kojem modulu.<br><br>Navedite <b>naziv</b> za padajući popis.<br><br>Ako su u aplikaciji instalirani jezični paketi, možete odabrati <b>jezik</b> koji će se upotrebljavati za stavke na popisu.<br><br>U polju <b>Naziv stavke</b> navedite naziv za mogućnost na padajućem popisu. Taj se naziv neće prikazivati na padajućem popisu vidljivom korisnicima.<br><br>U polju <b>Oznaka za prikaz</b> navedite oznaku koja će biti vidljiva korisnicima.<br><br>Nakon navođenja naziva stavke i oznake za prikaz kliknite na <b>Dodaj</b> da biste dodali stavku na padajući popis.<br><br>Da biste promijenili raspored stavki na popisu, povucite i ispustite stavke na željene položaje.<br><br>Da biste uredili oznaku za prikaz stavke, kliknite na <b>Uredi ikonu</b> i unesite novu oznaku. Da biste izbrisali stavku s padajućeg popisa, kliknite na <b>Izbriši ikonu</b>.<br><br>Da biste poništili promjenu oznake za prikaz, kliknite na <b>Poništi</b>. Da biste vratili poništenu promjenu, kliknite na <b>Vrati</b>.<br><br>Kliknite na <b>Spremi</b> da biste spremili padajući popis.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'Sva polja koja se mogu prikazati na <b>podploči</b> prikazuju se ovdje.<br><br><b>Zadani</b> stupac sadrži polja prikazana na podploči.<br/><br/><b>Skriveni</b> stupac sadrži polja koja se mogu dodati zadanom stupcu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        'savebtn'	=> 'Kliknite na <b>Spremi i implementiraj</b> da biste spremili promjene i da biste ih aktivirali unutar modula.',
        'historyBtn'=> 'Kliknite na <b>Prikaži povijest</b> da biste vidjeli i vratili prethodno spremljeni izgled iz povijesti.',
        'historyRestoreDefaultLayout'=> 'Kliknite na <b>Vrati zadani izgled</b> da biste vratili prikaz na njegov izvorni izgled.',
        'Hidden' 	=> '<b>Skrivena</b> polja ne prikazuju se na podploči.',
        'Default'	=> '<b>Zadana</b> polja prikazuju se na podploči.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'Sva polja koja se mogu prikazivati u <b>prikazu popisa</b> prikazuju se ovdje.<br><br><b>Zadani</b> stupac sadrži polja koja se zadano prikazuju u prikazu popisa.<br/><br/><b>Dostupni</b> stupac sadrži polja koja korisnik može odabrati u pretraživanju za stvaranje prilagođenog prikaza popisa. <br/><br/><b>Skriveni</b> stupac sadrži polja koja se mogu dodati u zadani ili dostupni stupac.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        'savebtn'	=> 'Kliknite na <b>Spremi i implementiraj</b> da biste spremili promjene i da biste ih aktivirali unutar modula.',
        'historyBtn'=> 'Kliknite na <b>Prikaži povijest</b> da biste vidjeli i vratili prethodno spremljeni izgled iz povijesti.<br><br><b>Vrati</b> unutar <b>Prikaza povijesti</b> vraća smještaj polja unutar prethodno spremljenih izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'historyRestoreDefaultLayout'=> 'Kliknite na <b>Vrati zadani izgled</b> da biste vratili prikaz na njegov izvorni izgled.<br><br><b>Vrati zadani izgled</b> vraća smještaj polja samo unutar izvornog izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'Hidden' 	=> '<b>Skrivena</b> polja trenutačno nisu dostupna da ih korisnici vide u prikazima popisa.',
        'Available' => '<b>Dostupna</b> polja zadano se ne prikazuju, no korisnici ih mogu dodati u prikaze popisa.',
        'Default'	=> '<b>Zadana</b> polja prikazuju se u prikazima popisa koje ne prilagođavaju korisnici.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'Sva polja koja se mogu prikazati na <b>prikazu popisa</b> prikazuju se ovdje.<br><br><b>Zadani</b> stupac sadrži polja koja su zadano prikazana u prikazu popisa.<br/><br/><b>Skriveni</b> stupac sadrži polja koja se mogu dodati zadanom ili dostupnom stupcu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        'savebtn'	=> 'Kliknite na <b>Spremi i implementiraj</b> da biste spremili promjene i da biste ih aktivirali unutar modula.',
        'historyBtn'=> 'Kliknite na <b>Prikaži povijest</b> da biste vidjeli i vratili prethodno spremljeni izgled iz povijesti.<br><br><b>Vrati</b> unutar <b>Prikaza povijesti</b> vraća smještaj polja unutar prethodno spremljenih izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'historyRestoreDefaultLayout'=> 'Kliknite na <b>Vrati zadani izgled</b> da biste vratili prikaz na njegov izvorni izgled.<br><br><b>Vrati zadani izgled</b> vraća smještaj polja samo unutar izvornog izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'Hidden' 	=> '<b>Skrivena</b> polja trenutačno nisu dostupna da ih korisnici vide u prikazima popisa.',
        'Default'	=> '<b>Zadana</b> polja prikazuju se u prikazima popisa koje ne prilagođavaju korisnici.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'Sva polja koja se mogu prikazati u obrascu za <b>pretraživanje</b> prikazuju se ovdje.<br><br><b>Zadani</b> stupac sadrži polja koja će se prikazivati u obrascu za pretraživanje.<br/><br/><b>Skriveni</b> stupac sadrži dostupna polja koja vi kao administrator možete dodati u obrazac za pretraživanje.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    . '<br/><br/>Ova se konfiguracija primjenjuje na izgled skočnog pretraživanja samo u modulima nasljeđa.',
        'savebtn'	=> 'Klikom na <b>Spremi i implementiraj</b> spremit će se i aktivirati sve promjene',
        'Hidden' 	=> '<b>Skrivena</b> polja ne prikazuju se u pretraživanju.',
        'historyBtn'=> 'Kliknite na <b>Prikaži povijest</b> da biste vidjeli i vratili prethodno spremljeni izgled iz povijesti.<br><br><b>Vrati</b> unutar <b>Prikaza povijesti</b> vraća smještaj polja unutar prethodno spremljenih izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'historyRestoreDefaultLayout'=> 'Kliknite na <b>Vrati zadani izgled</b> da biste vratili prikaz na njegov izvorni izgled.<br><br><b>Vrati zadani izgled</b> vraća smještaj polja samo unutar izvornog izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'Default'	=> '<b>Zadana</b> polja prikazuju se u pretraživanju.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'Područje <b>izgleda</b> sadrži polja koja se trenutačno prikazuju unutar <b>Prikaza detalja</b>.<br/><br/><b>Alatni okvir</b> sadrži <b>Koš za smeće</b> te polja i elemente izgleda koji se mogu dodati izgledu.<br><br>Promijenite izgled tako da povučete i ispustite elemente i polja između <b>alatnog okvira</b> i <b>izgleda</b> te unutar samog izgleda.<br><br>Da biste uklonili polje iz izgleda, dovucite polje u <b>koš za smeće</b>. Polje će zatim biti dostupno u alatnom okviru za dodavanje izgledu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        'defaultquickcreate'=>'Područje <b>izgleda</b> sadrži polja koja se trenutačno prikazuju unutar <b>obrasca za</b>brzo stvaranje.<br><br>Obrazac za brzo stvaranje prikazuje se nakon klika na Stvori u podploči modula.<br/><br/><b>Alatni okvir</b> sadrži <b>Koš za smeće</b> te polja i elemente izgleda koji se mogu dodati izgledu.<br><br>Promijenite izgled tako da povučete i ispustite elemente i polja između <b>alatnog okvira</b> i <b>izgleda</b> te unutar samog izgleda.<br><br>Da biste uklonili polje iz izgleda, dovucite polje u <b>koš za smeće</b>. Polje će zatim biti dostupno u alatnom okviru za dodavanje izgledu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'Područje <b>izgleda</b> sadrži polja koja se trenutačno prikazuju unutar <b>Prikaza za uređivanje</b>.<br/><br/><b>Alatni okvir</b> sadrži <b>Koš za smeće</b> te polja i elemente izgleda koji se mogu dodati izgledu.<br><br>Promijenite izgled tako da povučete i ispustite elemente i polja između <b>alatnog okvira</b> i <b>izgleda</b> te unutar samog izgleda.<br><br>Da biste uklonili polje iz izgleda, dovucite polje u <b>koš za smeće</b>. Polje će zatim biti dostupno u alatnom okviru za dodavanje izgledu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'Područje <b>izgleda</b> sadrži polja koja se trenutačno prikazuju unutar <b>Prikaza zapisa</b>.<br/><br/><b>Alatni okvir</b> sadrži <b>Koš za smeće</b> te polja i elemente izgleda koji se mogu dodati izgledu.<br><br>Promijenite izgled tako da povučete i ispustite elemente i polja između <b>alatnog okvira</b> i <b>izgleda</b> te unutar samog izgleda.<br><br>Da biste uklonili polje iz izgleda, dovucite polje u <b>koš za smeće</b>. Polje će zatim biti dostupno u alatnom okviru za dodavanje izgledu.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označava zavisno polje koje može ili ne mora biti vidljivo na temelju vrijednosti formule.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označava izračunato polje čija će se vrijednost automatski odrediti na temelju formule.'
    ,
        'saveBtn'	=> 'Kliknite na <b>Spremi</b> da biste sačuvali promjene koje ste napravili u izgledu od posljednjeg puta kada ste ga spremili.<br><br>Promjene se neće prikazivati u modulu dok ne implementirate spremljene promjene.',
        'historyBtn'=> 'Kliknite na <b>Prikaži povijest</b> da biste vidjeli i vratili prethodno spremljeni izgled iz povijesti.<br><br><b>Vrati</b> unutar <b>Prikaza povijesti</b> vraća smještaj polja unutar prethodno spremljenih izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'historyRestoreDefaultLayout'=> 'Kliknite na <b>Vrati zadani izgled</b> da biste vratili prikaz na njegov izvorni izgled.<br><br><b>Vrati zadani izgled</b> vraća smještaj polja samo unutar izvornog izgleda. Da biste promijenili oznake polja, kliknite na ikonu za uređivanje pokraj svakog polja.',
        'publishBtn'=> 'Kliknite na <b>Spremi i implementiraj</b> da biste spremili sve promjene koje ste napravili u izgledu od posljednjeg puta kad ste ga spremili te da biste aktivirali promjene u modulu.<br><br>Izgled će se odmah prikazati u modulu.',
        'toolbox'	=> '<b>Alatni okvir</b> sadrži <b>Koš za smeće</b>, dodatne elemente izgleda i skup dostupnih polja za dodavanje izgledu.<br/><br/>Elementi izgleda i polja u alatnom okviru mogu se povući i ispustiti u izgled, a elementi izgleda i polja mogu se povući i ispustiti iz izgleda u alatni okvir.<br><br>Elementi izgleda jesu <b>ploče</b> i <b>reci</b>. Dodavanje novog retka ili nove ploče u izgled pruža dodatne lokacije u izgledu za polja.<br/><br/>Povucite i ispustite bilo koje od polja u alatnom okviru ili izgledu na zauzeti položaj polja da biste zamijenili lokacije dvaju polja.<br/><br/>Polje <b>ispunjač</b> stvara prazan prostor u izgledu tamo gdje se postavi.',
        'panels'	=> 'Područje <b>izgleda</b> pruža prikaz toga kako će se izgled prikazivati u modulu nakon implementiranja promjena napravljenih u izgledu.<br/><br/>Možete promijeniti položaj polja, redaka i ploča tako da ih povučete i ispustite na željenu lokaciju.<br/><br/>Uklonite elemente tako da ih povučete i ispustite u <b>koš za smeće</b> u alatnom okviru ili dodajte nove elemente tako da ih povučete iz <b>alatnog okvira</b> i ispustite na željenu lokaciju u izgledu.',
        'delete'	=> 'Povucite i ispustite bilo koji element ovdje da biste ga uklonili iz izgleda',
        'property'	=> 'Uredite <b>oznaku</b> koja se prikazuje za ovo polje.<br><br><b>Širina</b> daje vrijednost širine u pikselima za module Sidecar i kao postotak širine tablice za module kompatibilne s ranijim verzijama.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>Polja</b> dostupna za modul navedena su ovdje prema nazivu polja.<br><br>Prilagođena polja stvorena za modul prikazuju se iznad polja koja su zadano dostupna za modul.<br><br>Da biste uredili polje, kliknite na <b>Naziv polja</b>.<br/><br/>Da biste stvorili novo polje, kliknite na <b>Dodaj polje</b>.',
        'mbDefault'=>'<b>Polja</b> dostupna za modul navedena su ovdje prema nazivu polja.<br><br>Da biste konfigurirali svojstva za polje, kliknite na naziv polja.<br><br>Da biste stvorili novo polje, kliknite na <b>Dodaj polje</b>. Oznaka, kao i ostala svojstva novog polja, može se uređivati nakon stvaranja klikom na naziv polja.<br><br>Nakon implementiranja modula, nova polja stvorena u Sastavljaču modula smatraju se standardnim poljima u implementiranom modulu u Studiju.',
        'addField'	=> 'Odaberite <b>vrstu podataka</b> za novo polje. Vrsta koju odaberete određuje kakvi se znakovi mogu unijeti za polje. Na primjer, samo cijeli brojevi mogu se unositi u polja koja su vrste podataka Cijeli broj.<br><br>Navedite <b>naziv</b> za polje. Naziv mora biti alfanumerički i ne smije sadržavati razmake. Podvlake su dozvoljene.<br><br><b>Oznaka za prikaz</b> oznaka je koja će se prikazivati za polja u izgledima modula. <b>Oznaka sustava</b> odnosi se na polje u kodu.<br><br>Ovisno o vrsti podataka odabranih za polje, neka ili sva sljedeća svojstva mogu biti postavljena za polje:<br><br><b>Tekst za pomoć</b>pojavljuje se privremeno kada korisnik drži pokazivač iznad polja i može se upotrebljavati za upit namijenjen korisniku u vezi s izborom željene vrste unosa.<br><br><b>Tekst komentara</b> prikazuje se samo u Studiju i/ili Sastavljaču modula, a može se upotrebljavati za opis polja administratorima.<br><br><b>Zadana vrijednost</b> prikazivat će se u polju. Korisnici mogu unijeti novu vrijednost u polje ili upotrijebiti zadanu vrijednost.<br><br>Označite potvrdni okvir <b>Masovno ažuriranje</b> da biste mogli upotrebljavati značajku masovnog ažuriranja za polje.<br><br>Vrijednost <b>Maksimalna veličina</b> određuje najveći broj znakova koji se mogu unijeti u polje.<br><br> Označite potvrdni okvir <b>Obavezno polje</b> da biste polje učinili obaveznim. Vrijednost mora biti navedena za polje da bi se zapis koji sadrži polje mogao spremiti.<br><br> Označite potvrdni okvir <b>Izvjestivo</b> da biste omogućili upotrebu polja za filtre i za prikaz podataka u izvješćima.<br><br> Označite potvrdni okvir <b>Nadzor</b> da biste mogli pratiti promjene u polju u zapisniku promjena.<br><br>Odaberite mogućnost u polju <b>Za uvoz</b> da biste dozvolili, zabranili ili zatražili uvoz polja u čarobnjak za uvoz.<br><br>Odaberite mogućnost u polju <b>Spajanje duplikata</b> da biste omogućili ili onemogućili značajke spajanja i traženja duplikata.<br><br>Dodatna svojstva mogu se postaviti za određene vrste podataka.',
        'editField' => 'Svojstva ovog polja mogu se prilagoditi.<br><br>Kliknite na <b>Kloniraj</b> da biste stvorili novo polje s jednakim svojstvima.',
        'mbeditField' => '<b>Oznaka za prikaz</b> polja za predložak može se prilagoditi. Ostala svojstva polja ne mogu se prilagoditi.<br><br>Kliknite na <b>Kloniraj</b> da biste stvorili novo polje s jednakim svojstvima.<br><br>Da biste uklonili polje za predložak tako da se ne prikazuje u modulu, uklonite polje iz odgovarajućih <b>izgleda</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Izvezite prilagodbe napravljene u Studiju tako da stvorite pakete koji se mogu učitati u drugu instancu Sugar putem <b>Učitavača modula</b>.<br><br> Prvo navedite <b>naziv paketa</b>. Možete navesti i informacije o <b>autoru</b> i <b>opisu</b> paketa.<br><br>Odaberite modul(e) koji sadrži/e prilagodbe koje želite izvesti. Za odabir će se pojaviti samo moduli koji sadrže prilagodbe.<br><br>Zatim kliknite na <b>Izvezi</b> da biste stvorili .zip datoteku za paket koji sadrži prilagodbe.',
        'exportCustomBtn'=>'Kliknite na <b>Izvezi</b> da biste stvorili .zip datoteku za paket koji sadrži prilagodbe koje želite izvesti.',
        'name'=>'Ovo je <b>naziv</b> paketa. Taj će se naziv prikazivati tijekom instalacije.',
        'author'=>'Ovo je <b>autor</b> koji se prikazuje tijekom instalacije kao naziv entiteta koji je stvorio paket. Autor može biti pojedinac ili tvrtka.',
        'description'=>'Ovo je <b>opis</b> paketa koji se prikazuje tijekom instalacije.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Dobro došli u područje <b>razvojnih alata</b>. <br/><br/>Upotrijebite alate iz ovog područja da biste stvorili standardne i prilagođene module i polja te upravljali njima.',
        'studioBtn'	=> 'Upotrijebite <b>Studio</b> da biste prilagodili implementirane module.',
        'mbBtn'		=> 'Upotrijebite <b>Sastavljač modula</b> da biste stvorili nove module.',
        'sugarPortalBtn' => 'Upotrijebite <b>Uređivač Sugar Portala</b> da biste upravljali portalom Sugar i prilagodili ga.',
        'dropDownEditorBtn' => 'Upotrijebite <b>Padajući uređivač</b> da biste dodali i uredili globalne padajuće izbornike za padajuća polja.',
        'appBtn' 	=> 'U aplikacijskom načinu rada možete prilagoditi različita svojstva programa, poput toga koliko se TPS izvješća prikazuje na početnoj stranici',
        'backBtn'	=> 'Vratite se na prethodni korak.',
        'studioHelp'=> 'Upotrijebite <b>Studio</b> da biste odredili koje informacije se prikazuju u modulima i kako.',
        'studioBCHelp' => ' označava da se radi o modulu kompatibilnom s ranijim verzijama',
        'moduleBtn'	=> 'Kliknite za uređivanje ovog modula.',
        'moduleHelp'=> 'Komponente koje možete prilagoditi za modul prikazuju se ovdje.<br><br>Kliknite na ikonu da biste odabrali komponentu za uređivanje.',
        'fieldsBtn'	=> 'Stvorite i prilagodite <b>polja</b> za pohranu informacija u modulu.',
        'labelsBtn' => 'Uredite <b>oznake</b> koje se prikazuju za polja i ostale naslove u modulu.'	,
        'relationshipsBtn' => 'Dodajte novi ili pogledajte postojeće <b>odnose</b> za modul.' ,
        'layoutsBtn'=> 'Prilagodite modul <b>izgleda</b>. Izgledi su različiti prikazi modula koji sadrži polja.<br><br>Možete odrediti koja se polja prikazuju i kako su organizirana u svakom izgledu.',
        'subpanelBtn'=> 'Odredite koja se polja prikazuju u <b>podpločama</b> u modulu.',
        'portalBtn' =>'Prilagodite modul <b>izgleda</b> koji se prikazuje u <b>Sugar Portalu</b>.',
        'layoutsHelp'=> 'Modul <b>izgleda</b> koji se može prilagođavati prikazuje se ovdje.<br><br>Izgledi prikazuju polja i podatke o poljima.<br><br>Kliknite na ikonu da biste odabrali izgled za uređivanje.',
        'subpanelHelp'=> '<b>Podploče</b> u modulu koje se mogu prilagođavati prikazuju se ovdje.<br><br>Kliknite na ikonu da biste odabrali modul za uređivanje.',
        'newPackage'=>'Kliknite na <b>Novi paket</b> da biste stvorili novi paket.',
        'exportBtn' => 'Kliknite na <b>Izvezi prilagodbe</b> da biste stvorili i preuzeli paket koji sadrži prilagodbe izrađene u Studiju za određene module.',
        'mbHelp'    => 'Upotrijebite <b>Sastavljač modula</b> za stvaranje paketa koji sadrže prilagođene module utemeljene na standardnim ili prilagođenim objektima.',
        'viewBtnEditView' => 'Prilagodite izgled <b>prikaza za uređivanje</b> modula.<br><br>Prikaz za uređivanje obrazac je koji sadrži polja za unos za snimanje podataka koje unose korisnici.',
        'viewBtnDetailView' => 'Prilagodite izgled <b>prikaza detalja</b> modula.<br><br>Prikaz detalja prikazuje podatke o polju koje su unijeli korisnici.',
        'viewBtnDashlet' => 'Prilagodite <b>Sugar Dashlet</b> modula, uključujući prikaz popisa i pretraživanje Sugar Dashleta.<br><br>Sugar Dashlet bit će dostupan za dodavanje stranicama u početnom modulu.',
        'viewBtnListView' => 'Prilagodite izgled <b>prikaza popisa</b> modula.<br><br>Rezultati pretraživanja prikazuju se u prikazu popisa.',
        'searchBtn' => 'Prilagodite izglede <b>pretraživanja</b> modula.<br><br>Odredite polja koja se mogu upotrebljavati za filtriranje zapisa koji se prikazuju u prikazu popisa.',
        'viewBtnQuickCreate' =>  'Prilagodite izgled <b>brzog stvaranja</b> modula.<br><br>Obrazac brzog stvaranja prikazuje se na podpločama i u modulu e-pošte.',

        'searchHelp'=> 'Obrasci za <b>pretraživanje</b> koji se mogu prilagoditi pojavljuju se ovdje.<br><br>Obrasci za pretraživanje sadrže polja za filtriranje zapisa.<br><br>Kliknite na ikonu da biste odabrali izgled pretraživanja za uređivanje.',
        'dashletHelp' =>'Izgledi <b>Sugar Dashleta</b> koji se mogu prilagoditi prikazuju se ovdje.<br><br>Sugar Dashlet bit će dostupan za dodavanje stranicama u početnom modulu.',
        'DashletListViewBtn' =>'<b>Prikaz popisa Sugar Dashlet</b> prikazuje zapise na temelju filtara pretraživanja Sugar Dashleta.',
        'DashletSearchViewBtn' =>'<b>Pretraživanje Sugar Dashleta</b> filtrira zapise za prikaz popisa Sugar Dashlet.',
        'popupHelp' =>'Izgledi <b>skočnih prozora</b> koji se mogu prilagoditi prikazuju se ovdje.<br>',
        'PopupListViewBtn' => 'Izgled <b>skočnog prikaza popisa</b> upotrebljava se za prikaz popisa zapisa kada odredite odnos jednog ili više zapisa s trenutačnim zapisom.',
        'PopupSearchViewBtn' => 'Izgled <b>skočnog pretraživanja</b> omogućuje korisnicima pretraživanje zapisa u odnosu s trenutačnim zapisom i pojavljuje se iznad skočnog prikaza popisa u istom prozoru. Moduli nasljeđa upotrebljavaju ovaj izgled za skočno pretraživanje, a moduli Sidecar upotrebljavaju konfiguraciju izgleda pretraživanja.',
        'BasicSearchBtn' => 'Prilagodite obrazac za <b>osnovno pretraživanje</b> koji se prikazuje na kartici Osnovno pretraživanje u području za pretraživanje za modul.',
        'AdvancedSearchBtn' => 'Prilagodite obrazac za <b>napredno pretraživanje</b> koji se pojavljuje na kartici Napredno pretraživanje u području za pretraživanje za modul.',
        'portalHelp' => 'Upravljajte <b>Sugar Portalom</b> i prilagodite ga.',
        'SPUploadCSS' => 'Učitajte <b>list stila</b> za Sugar Portal.',
        'SPSync' => '<b>Sinkronizirajte</b> prilagodbe s instancom Sugar Portal.',
        'Layouts' => 'Prilagodite <b>izglede</b> modula Sugar Portala.',
        'portalLayoutHelp' => 'Moduli u Sugar Portalu prikazuju se u ovom području.<br><br>Odaberite modul da biste uredili <b>izglede</b>.',
        'relationshipsHelp' => 'Svi <b>odnosi</b> koji postoje između modula i drugih implementiranih modula prikazuju se ovdje.<br><br>Odnos <b>Naziv</b> naziv je koji sustav generira za odnos.<br><br><b>Primarni modul</b> modul je u čijem su vlasništvu odnosi. Na primjer, sva svojstva odnosa za koje je modul računa primarni modul pohranjena su u tablicama baze podataka za račune.<br><br><b>Vrsta</b> je vrsta odnosa koji postoji između primarnog modula i <b>povezanog modula</b>.<br><br>Kliknite na naslov stupca da biste razvrstali prema stupcu.<br><br>Kliknite na redak u tablici odnosa da biste vidjeli svojstva povezana s odnosom.<br><br>Kliknite na <b>Dodaj odnos</b> da biste stvorili novi odnos.<br><br>Odnosi se mogu stvoriti između bilo koja dva implementirana modula.',
        'relationshipHelp'=>'<b>Odnosi</b> se mogu stvoriti između modula i drugog implementiranog modula.<br><br>Odnosi su vizualno predstavljeni putem podploča i polja za povezivanje u zapisima modula.<br><br>Odaberite jednu od sljedećih <b>vrsta</b> odnosa za modul:<br><br><b>Jedan na jedan</b> – zapisi obaju modula sadržavat će polja za povezivanje.<br><br> <b>Jedan na mnogo</b> – zapis primarnog modula sadržavat će podploču, a zapis povezanog modula sadržavat će polje za povezivanje.<br><br> <b>Mnogo na mnogo</b> – zapisi obaju modula prikazivat će podploče.<br><br> Odaberite <b>povezani modul</b> za odnos. <br><br>Ako vrsta odnosa uključuje podploče, odaberite prikaz podploča za odgovarajuće module.<br><br> Kliknite na <b>Spremi</b> da biste stvorili odnos.',
        'convertLeadHelp' => "Ovdje možete dodavati module na zaslon za pretvorbu izgleda i izmijeniti postavke postojećih.<br/><br/>
        <b>Stvaranje redoslijeda:</b><br/>
        Kontakti, Računi i Prilike moraju zadržati svoj redoslijed. Možete promijeniti redoslijed bilo kojeg drugog modula tako da njegov redak povučete u tablicu.<br/><br/>
        <b>Zavisnost:</b><br/>
        Ako je uključen modul prilika,  modul računa mora biti obavezan ili uklonjen iz izgleda pretvorbe.<br/><br/>
        <b>Modul:</b> naziv modula.<br/><br/>
        <b>Obavezno:</b> obavezni moduli moraju biti stvoreni ili odabrani prije nego što je moguća pretvorba potencijalnog klijenta.<br/><br/>
        <b>Kopiraj podatke:</b> ako je označeno, polja iz potencijalnih klijenata kopirat će se u polja s istim imenom kao i novostvoreni zapisi.<br/><br/>
        <b>Izbriši:</b> uklonite ovaj modul iz tablice pretvorbe.<br/><br/>
        ",
        'editDropDownBtn' => 'Uredi globalni padajući izbornik',
        'addDropDownBtn' => 'Dodaj novi globalni padajući izbornik',
    ),
    'fieldsHelp'=>array(
        'default'=>'<b>Polja</b> u modulu navedena su ovdje prema nazivu polja.<br><br>Predložak modula uključuje prethodno određeni skup polja.<br><br>Da biste stvorili novo polje, kliknite na <b>Dodaj polje</b>.<br><br>Da biste uredili polje, kliknite na <b>Naziv polja</b>.<br/><br/>Nakon implementiranja modula nova se polja stvorena u Sastavljaču modula, kao i polja za predložak, smatraju standardnim poljima u Studiju.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>Odnosi</b> koji su stvoreni između modula i drugih modula prikazuju se ovdje.<br><br>Odnos <b>Naziv</b> naziv je koji sustav generira za odnos.<br><br><b>Primarni modul</b> modul je u čijem su vlasništvu odnosi. Svojstva odnosa pohranjena su u tablicama baze podataka koje pripadaju primarnom modulu.<br><br><b>Vrsta</b> je vrsta odnosa koji postoji između primarnog modula i <b>povezanog modula</b>.<br><br>Kliknite na naslov stupca da biste razvrstali prema stupcu.<br><br>Kliknite na redak u tablici odnosa da biste vidjeli i uredili svojstva povezana s odnosom.<br><br>Kliknite na <b>Dodaj odnos</b> da biste stvorili novi odnos.',
        'addrelbtn'=>'prijeđite mišem iznad pomoći za dodavanje odnosa..',
        'addRelationship'=>'<b>Odnosi</b> se mogu stvoriti između modula i drugog prilagođenog modula ili implementiranog modula.<br><br>Odnosi su vizualno predstavljeni putem podploča i polja za povezivanje u zapisima modula.<br><br>Odaberite jednu od sljedećih <b>vrsta</b> odnosa za modul:<br><br><b>Jedan na jedan</b> – zapisi obaju modula sadržavat će polja za povezivanje.<br><br> <b>Jedan na mnogo</b> – zapis primarnog modula sadržavat će podploču, a zapis povezanog modula sadržavat će polje za povezivanje.<br><br> <b>Mnogo na mnogo</b> – zapisi obaju modula prikazivat će podploče.<br><br> Odaberite <b>povezani modul</b> za odnos. <br><br>Ako vrsta odnosa uključuje podploče, odaberite prikaz podploča za odgovarajuće module.<br><br> Kliknite na <b>Spremi</b> da biste stvorili odnos.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Oznake</b> za polja i drugi naslovi u modulu mogu se promijeniti.<br><br>Uredite oznaku tako da kliknete unutar polja, unesete novu oznaku i kliknete na <b>Spremi</b>.<br><br>Ako su u aplikaciji instalirani jezični paketi, možete odabrati <b>jezik</b> koji ćete upotrebljavati za oznake.',
        'saveBtn'=>'Kliknite na <b>Spremi</b> da biste spremili sve promjene.',
        'publishBtn'=>'Kliknite na <b>Spremi i implementiraj</b> da biste spremili i aktivirali sve promjene.',
    ),
    'portalSync'=>array(
        'default' => 'Unesite <b>URL adresu Sugar Portala</b> instance portala za ažuriranje i kliknite na <b>Idi</b>.<br><br>Zatim unesite valjano korisničko ime i lozinku Sugar te kliknite na <b>Započni sinkronizaciju</b>.<br><br>Prilagodbe napravljene u <b>izgledima</b> Sugar Portala, zajedno s <b>listom stila</b> ako je on učitan, bit će prenesene u određenu instancu portala.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Možete prilagoditi izgled Sugar Portala tako da upotrijebite listu stila.<br><br>Odaberite <b>listu stila</b> koju želite učitati.<br><br>Lista stila bit će implementirana u Sugar Portal pri sljedećoj sinkronizaciji.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Da biste započeli s projektom, kliknite na <b>Novi paket</b> za stvaranje novog paketa u kojem će biti pohranjeni vaš(i) prilagođeni modul(i). <br/><br/>Svaki paket može sadržavati jedan ili više modula.<br/><br/>Na primjer, možda želite stvoriti paket koji sadrži jedan prilagođeni modul koji je povezan sa standardnim modulom računa. Možda pak želite stvoriti paket koji sadržava nekoliko novih modula koji rade zajedno kao projekt i koji su povezani jedni s drugima i s ostalim modulima koji su već u aplikaciji.',
            'somepackages'=>'<b>Paket</b> služi kao spremnik za prilagođene module, od kojih su svi dio jednog projekta. Paket može sadržavati jedan ili više prilagođenih <b>modula</b> koji mogu biti povezani jedni s drugima i s ostalim modulima u aplikaciji.<br/><br/>Nakon stvaranja paketa za svoj projekt, možete odmah stvoriti module za paket ili se kasnije možete vratiti u Sastavljač modula da biste dovršili projekt.<br><br>Nakon dovršetka projekta možete <b>implementirati</b> paket da biste instalirali prilagođene module u aplikaciji.',
            'afterSave'=>'Vaš novi paket mora sadržavati barem jedan modul. Možete stvoriti jedan ili više prilagođenih modula za paket.<br/><br/>Kliknite na <b>Novi modul</b> da biste stvorili prilagođeni modul za ovaj paket.<br/><br/> Nakon što stvorite barem jedan modul, možete objaviti ili implementirati paket da bi on bio dostupan za vašu instancu i/ili instance ostalih korisnika.<br/><br/> Da biste jednim korakom implementirali paket u instanci Sugar, kliknite na <b>Implementiraj</b>.<br><br>Kliknite na <b>Objavi</b> da biste spremili paket kao .zip datoteku. Nakon što spremite .zip datoteku u svoj sustav, upotrijebite <b>Učitavač modula</b> da biste učitali i instalirali paket u svojoj instanci Sugar. <br/><br/>Možete raspodijeliti datoteku ostalim korisnicima da bi je oni učitali i instalirali u vlastitim instancama Sugar.',
            'create'=>'<b>Paket</b> služi kao spremnik za prilagođene module, od kojih su svi dio jednog projekta. Paket može sadržavati jedan ili više prilagođenih <b>modula</b> koji mogu biti povezani jedni s drugima i s ostalim modulima u aplikaciji.<br/><br/>Nakon stvaranja paketa za svoj projekt, možete odmah stvoriti module za paket ili se kasnije možete vratiti u Sastavljač modula da biste dovršili projekt.',
            ),
    'main'=>array(
        'welcome'=>'Upotrijebite <b>razvojne alate</b> da biste stvorili standardne i prilagođene module i polja i upravljali njima. <br/><br/>Da biste upravljali modulima u aplikaciji, kliknite na <b>Studio</b>. <br/><br/>Da biste stvorili prilagođene module, kliknite na <b>Sastavljač modula</b>.',
        'studioWelcome'=>'Svi trenutačno instalirani moduli, uključujući standardne objekte i one u koje su učitani moduli, mogu se prilagoditi u Studiju.'
    ),
    'module'=>array(
        'somemodules'=>"Budući da trenutačni paket sadrži barem jedan modul, možete <b>implementirati</b> module u paketu u svojoj instanci Sugar ili <b>objaviti</b> paket koji treba instalirati u trenutačnoj instanci Sugar ili drugoj instanci s pomoću <b>Učitavača modula</b>.<br/><br/>Da biste izravno instalirali paket u svojoj instanci Sugar, kliknite na <b>Implementiraj</b>.<br><br>Da biste stvorili .zip datoteku za paket koji se može učitati i instalirati u trenutačnoj instanci Sugar i ostalim instancama s pomoću <b>Učitavača modula</b>, kliknite na <b>Objavi</b>.<br/><br/>Možete izgraditi modele za ovaj paket u fazama i objaviti ili implementirati kad ste spremni. <br/><br/>Nakon objavljivanja ili implementiranja paketa možete mijenjati svojstva paketa i dodatno prilagoditi module. Zatim ponovno objavite ili ponovno implementirajte paket da biste primijenili promjene." ,
        'editView'=> 'Ovdje možete uređivati postojeća polja. Možete ukloniti bilo koje od postojećih polja ili dodati dostupna polja na lijevoj ploči.',
        'create'=>'Pri odabiru <b>vrste</b> modula koji želite stvoriti imajte na umu vrste polja koje želite imati u modulu. <br/><br/>Svaki predložak modula sadrži skup polja koja pripadaju vrsti modula opisanog naslovom.<br/><br/><b>Osnovni</b> – pruža osnovna polja koja se pojavljuju u standardnim modulima, poput polja Ime, Dodijeljeno, Tim, Datum stvaranja i Opis.<br/><br/> <b>Tvrtka</b> – pruža polja povezana s organizacijom, poput polja Naziv tvrtke, Industrija i Adresa za naplatu. Upotrijebite ovaj predložak za stvaranje modula koji su slični standardnom modulu računa.<br/><br/> <b>Osoba</b> – pruža polja povezana s pojedincima, poput polja Oslovljavanje, Titula, Ime, Adresa i Telefonski broj. Upotrijebite ovaj predložak za stvaranje modula koji su slični standardnim modulima kontakata i potencijalnih klijenata.<br/><br/><b>Problem</b> – pruža polja povezana sa slučajevima i pogreškama, poput polja Broj, Status, Prioritet i Opis. Upotrijebite ovaj modul za stvaranje modula koji su slični standardnim modulima slučajeva i pratitelja pogrešaka.<br/><br/>Napomena: nakon što stvorite modul, možete uređivati oznake polja navedene u predlošku, kao i stvarati prilagođena polja za dodavanje izgledima modula.',
        'afterSave'=>'Prilagodite modul da odgovara vašim potrebama tako da uređujete i stvarate polja, uspostavljate odnose s drugim modulima i raspoređujete polja u izgledima.<br/><br/>Da biste pogledali polja predložaka i upravljali prilagođenim poljima u modulu, kliknite na <b>Prikaži polja</b>.<br/><br/>Da biste stvorili odnose između modula i ostalih modula te upravljali njima, bez obzira na to radi li se o modulima koji su već u aplikaciji ili o ostalim prilagođenim modulima u istom paketu, kliknite na <b>Prikaži odnose</b>.<br/><br/>Da biste uredili izglede modula, kliknite na <b>Prikaži izglede</b>. Možete promijeniti izglede za prikaz detalja, prikaz za uređivanje i prikaz popisa za modul kao što biste učinili i za module koji su već u aplikaciji u Studiju.<br/><br/> Da biste stvorili modul s jednakim svojstvima kao i trenutačni modul, kliknite na <b>Dupliciraj</b>. Možete dodatno prilagoditi novi modul.',
        'viewfields'=>'Polja u modulu mogu se prilagoditi da odgovaraju vašim potrebama.<br/><br/>Ne možete izbrisati standardna polja, no možete ih ukloniti iz odgovarajućih izgleda na stranicama izgleda. <br/><br/>Možete brzo stvoriti nova polja koja imaju slična svojstva kao i postojeća polja tako da kliknete na <b>Kloniraj</b> u obrascu sa <b>svojstvima</b>. Unesite nova svojstva i kliknite na <b>Spremi</b>.<br/><br/>Preporučljivo je da postavite sva svojstva za standardna polja i prilagođena polja prije nego što objavite i instalirate paket koji sadrži prilagođeni modul.',
        'viewrelationships'=>'Možete stvoriti odnose „mnogo na mnogo” između trenutačnog modula i ostalih modula u paketu i/ili između trenutačnog modula i modula koji su već instalirani u aplikaciji.<br><br> Da biste stvorili odnose „jedan na mnogo” i „jedan na jedan”, stvorite polje za <b>povezivanje</b> i polje za <b>fleksibilno povezivanje</b> za module.',
        'viewlayouts'=>'Možete upravljati time koja su polja dostupna za snimanje podataka u <b>prikazu za uređivanje</b>. Također možete upravljati i time koji se podaci prikazuju u <b>prikazu detalja</b>. Prikazi se ne trebaju podudarati. <br/><br/>Obrazac za brzo stvaranje prikazuje se nakon klika na <b>Stvori</b> u podploči modula. Izgled obrasca za <b>brzo stvaranje</b> zadano je jednak zadanom izgledu <b>prikaza za uređivanje</b>. Možete prilagoditi obrazac za brzo stvaranje tako da sadrži manje i/ili različita polja nego izgled prikaza za uređivanje. <br><br>Možete odrediti sigurnost modula s pomoću prilagodbe izgleda zajedno s <b>upravljanjem ulogama</b>.<br><br>',
        'existingModule' =>'Nakon stvaranja i prilagođavanja ovog modula možete stvoriti dodatne module ili se vratiti na paket da biste ga <b>objavili</b> ili <b>implementirali</b>.<br><br>Da biste stvorili dodatne module, kliknite na <b>Dupliciraj</b> za stvaranje modula s istim svojstvima kao i trenutačni modul ili se vratite na paket i kliknite na <b>Novi modul</b>.<br><br> Ako ste spremni <b>objaviti</b> ili <b>implementirati</b> paket koji sadrži ovaj modul, vratite se na paket da izvršite te funkcije. Možete objaviti i implementirati pakete koji sadrže barem jedan modul.',
        'labels'=> 'Oznake standardnih polja, kao i prilagođenih polja, mogu se mijenjati. Promjena oznaka polja neće utjecati na podatke pohranjene u poljima.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Tri stupca prikazana su s lijeve strane. „Zadani” stupac sadrži polja koja su zadano prikazana u prikazu popisa, „dostupni” stupac sadrži polja koja korisnik može izabrati za upotrebu pri stvaranju prilagođenog prikaza popisa, a „skriveni” stupac sadrži polja koja vi kao administrator možete korisnicima dodati u zadani ili dostupni stupac za upotrebu, no koji su trenutačno onemogućeni.',
        'savebtn'	=> 'Klikom na <b>Spremi</b> spremit će se i aktivirati sve promjene.',
        'Hidden' 	=> 'Skrivena polja polja su koja trenutačno nisu dostupna korisnicima za upotrebu u prikazima popisa.',
        'Available' => 'Dostupna polja polja su koja se ne prikazuju zadano, no korisnici ih mogu omogućiti.',
        'Default'	=> 'Zadana polja prikazuju se korisnicima koji nisu stvorili postavke prilagođenog prikaza popisa.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Dva stupca prikazana su s lijeve strane. „Zadani” stupac sadrži polja koja će se prikazivati u prikazu za pretraživanje, a „skriveni” stupac sadrži polja koja vi kao administrator možete dodati u prikaz.',
        'savebtn'	=> 'Klikom na <b>Spremi i implementiraj</b> spremit će se i aktivirati sve promjene.',
        'Hidden' 	=> 'Skrivena polja ona su koja se neće prikazivati u prikazu za pretraživanje.',
        'Default'	=> 'Zadana polja bit će prikazana u prikazu za pretraživanje.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Dva stupca prikazana su s lijeve strane. U desnom stupcu, s oznakom „Trenutačni izgled” ili „Pregled izgleda”, mijenjate izgled modula. Lijevi stupac pod nazivom „Alatni okvir” sadrži korisne elemente i alate za upotrebu pri uređivanju izgleda. <br/><br/>Ako je naslov područja izgleda „Trenutačni izgled”, to znači da radite na kopiji izgleda koju modul trenutačno upotrebljava za prikaz.<br/><br/>Ako je naslov „Pregled izgleda”, to znači da radite na kopiji koja je stvorena prije klikom na gumb „Spremi”, a koja je već možda drugačija od verzije koju su vidjeli korisnici ovog modula.',
        'saveBtn'	=> 'Klikom na ovaj gumb sprema se izgled tako da možete sačuvati svoje promjene. Kada se vratite na ovaj modul, počet ćete od ovog promijenjenog izgleda. Međutim, vaš izgled neće vidjeti korisnici modula dok ne kliknete na gumb „Spremi i objavi”.',
        'publishBtn'=> 'Kliknite na ovaj gumb da biste implementirali izgled. To znači da će taj izgled odmah vidjeti korisnici ovog modula.',
        'toolbox'	=> 'Alatni okvir sadrži različite i korisne značajke za uređivanje izgleda, uključujući prostor za otpad, skup dodatnih elemenata i skup dostupnih polja. Bilo koji od njih može se povući i ispustiti na izgled.',
        'panels'	=> 'Ovo područje prikazuje kako će vaš izgled izgledati korisnicima ovog modula nakon implementiranja.<br/><br/>Možete promijeniti položaj elementima kao što su polja, retci i ploče tako da ih povučete i ispustite; izbrišite elemente tako da ih povučete i ispustite na prostor za otpad u alatnom okviru ili dodajte nove elemente tako da ih povučete iz alatnog okvira i ispustite na izgled na željeni položaj.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Dva stupca prikazana su s lijeve strane. U desnom stupcu, s oznakom „Trenutačni izgled” ili „Pregled izgleda”, mijenjate izgled modula. Lijevi stupac pod nazivom „Alatni okvir” sadrži korisne elemente i alate za upotrebu pri uređivanju izgleda. <br/><br/>Ako je naslov područja izgleda „Trenutačni izgled”, to znači da radite na kopiji izgleda koju modul trenutačno upotrebljava za prikaz.<br/><br/>Ako je naslov „Pregled izgleda”, to znači da radite na kopiji koja je stvorena prije klikom na gumb „Spremi”, a koja je već možda drugačija od verzije koju su vidjeli korisnici ovog modula.',
        'dropdownaddbtn'=> 'Klikom na ovaj gumb dodaje se nova stavka u padajućem izborniku.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Prilagodbe napravljene u Studiju u ovoj instanci mogu se pakirati i implementirati u drugoj instanci. <br><br>Navedite <b>naziv paketa</b>. Možete navesti informacije o <b>autoru</b> i <b>opisu</b> paketa.<br><br>Odaberite modul(e) koji sadrži/e prilagodbe za izvoz. (Samo moduli koji sadrže prilagodbe prikazat će vam se za odabir.)<br><br>Kliknite na <b>Izvezi</b> da biste stvorili .zip datoteku za paket koji sadrži prilagodbe. .zip datoteka može se učitati u drugu instancu putem <b>Učitavača modula</b>.',
        'exportCustomBtn'=>'Kliknite na <b>Izvezi</b> da biste stvorili .zip datoteku za paket koji sadrži prilagodbe koje želite izvesti.
',
        'name'=>'<b>Naziv</b> paketa prikazat će se u Učitavaču modula nakon što se paket učita za instalaciju u Studiju.',
        'author'=>'<b>Autor</b> je ime entiteta koji je stvorio paket. Autor može biti pojedinac ili tvrtka.<br><br>Autor će se prikazati u Učitavaču modula nakon što se paket učita za instalaciju u Studiju.
',
        'description'=>'<b>Opis</b> paketa prikazat će se u Učitavaču modula nakon što se paket učita za instalaciju u Studiju.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Dobro došli u područje <b>razvojnih alata</b1>. <br/><br/>Upotrijebite alate iz ovog područja da biste stvorili standardne i prilagođene module i polja te upravljali njima.',
        'studioBtn'	=> 'Upotrijebite <b>Studio</b> da biste prilagodili instalirane module tako da promijenite raspored polja, odaberete koja su dostupna polja i stvorite prilagođena polja podataka.',
        'mbBtn'		=> 'Upotrijebite <b>Sastavljač modula</b> da biste stvorili nove module.',
        'appBtn' 	=> 'Upotrijebite aplikacijski način rada da biste prilagodili različita svojstva programa, poput toga koliko se TPS izvješća prikazuje na početnoj stranici',
        'backBtn'	=> 'Vratite se na prethodni korak.',
        'studioHelp'=> 'Upotrijebite <b>Studio</b> da biste prilagodili instalirane module.',
        'moduleBtn'	=> 'Kliknite za uređivanje ovog modula.',
        'moduleHelp'=> 'Odaberite komponentu modula koju želite urediti',
        'fieldsBtn'	=> 'Uredite informacije koje se pohranjuju u modulu tako da upravljate <b>poljima</b> u modulu.<br/><br/>Ovdje možete urediti i stvoriti prilagođena polja.',
        'layoutsBtn'=> 'Prilagodite <b>izglede</b> prikaza za uređivanje, prikaza detalja, prikaza popisa i prikaza za pretraživanje.',
        'subpanelBtn'=> 'Uredite informacije koje se prikazuju u podpločama ovoga modula.',
        'layoutsHelp'=> 'Odaberite <b>izgled za uređivanje</b>.<br/<br/>Da biste promijenili izgled koji sadrži polja podataka za unos podataka, kliknite na <b>Prikaz za uređivanje</b>.<br/><br/>Da biste promijenili izgled koji prikazuje podatke unesene u polja u prikazu za uređivanje, kliknite na <b>Prikaz detalja</b>.<br/><br/>Da biste promijenili stupce koji se prikazuju na zadanom popisu, kliknite na <b>Prikaz popisa</b>.<br/><br/>Da biste promijenili izglede obrazaca za osnovno i napredno pretraživanje, kliknite na <b>Pretraživanje</b>.',
        'subpanelHelp'=> 'Odaberite <b>podploču</b> za uređivanje.',
        'searchHelp' => 'Odaberite izgled za <b>pretraživanje</b> za uređivanje.',
        'labelsBtn'	=> 'Uredite <b>oznake</b> za prikaz vrijednosti u ovom modulu.',
        'newPackage'=>'Kliknite na <b>Novi paket</b> da biste stvorili novi paket.',
        'mbHelp'    => '<b>Dobro došli u Sastavljač modula.</b><br/><br/>Upotrijebite <b>Sastavljač modula</b> za stvaranje paketa koji sadrže prilagođene module na temelju standardnih ili prilagođenih objekata. <br/><br/>Da biste započeli, kliknite na <b>Novi paket</b> za stvaranje novog paketa ili odaberite paket za uređivanje.<br/><br/> <b>Paket</b> služi kao spremnik za prilagođene module, od kojih su svi dio jednog projekta. Paket može sadržavati jedan ili više prilagođenih modula koji mogu biti povezani jedni s drugima ili s modulima u aplikaciji. <br/><br/>Primjeri: možda želite stvoriti paket koji sadrži jedan prilagođeni modul povezan sa standardnim modulom računa; ili želite stvoriti paket koji sadrži nekoliko novih modula koji rade zajedno kao projekt i koji su povezani jedni s drugima i s modulima u aplikaciji.',
        'exportBtn' => 'Kliknite na <b>Prilagodbe izvoza</b> da biste stvorili paket koji sadrži prilagodbe napravljene u Studiju za određene module.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Padajući uređivač',

//ASSISTANT
'LBL_AS_SHOW' => 'Ubuduće prikazuj Pomoćnika.',
'LBL_AS_IGNORE' => 'Ubuduće ignoriraj Pomoćnika.',
'LBL_AS_SAYS' => 'Pomoćnik kaže:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Sastavljač modula',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Padajući uređivač',
'LBL_EDIT_DROPDOWN'=>'Uredi padajući izbornik',
'LBL_DEVELOPER_TOOLS' => 'Razvojni alati',
'LBL_SUGARPORTAL' => 'Uređivač Sugar Portala',
'LBL_SYNCPORTAL' => 'Sinkroniziraj portal',
'LBL_PACKAGE_LIST' => 'Popis paketa',
'LBL_HOME' => 'Početna',
'LBL_NONE'=>'-Nema-',
'LBL_DEPLOYE_COMPLETE'=>'Implementiranje dovršeno',
'LBL_DEPLOY_FAILED'   =>'Došlo je do pogreške prilikom postupka implementiranja; paket možda nije ispravno instaliran',
'LBL_ADD_FIELDS'=>'Dodaj prilagođena polja',
'LBL_AVAILABLE_SUBPANELS'=>'Dostupne podploče',
'LBL_ADVANCED'=>'Napredno',
'LBL_ADVANCED_SEARCH'=>'Napredno pretraživanje',
'LBL_BASIC'=>'Osnovno',
'LBL_BASIC_SEARCH'=>'Osnovno pretraživanje',
'LBL_CURRENT_LAYOUT'=>'Izgled',
'LBL_CURRENCY' => 'Valuta',
'LBL_CUSTOM' => 'Prilagođeno',
'LBL_DASHLET'=>'Sugar Dashlet',
'LBL_DASHLETLISTVIEW'=>'Prikaz popisa Sugar Dashleta',
'LBL_DASHLETSEARCH'=>'Pretraživanje Sugar Dashleta',
'LBL_POPUP'=>'Prikaz skoč. proz.',
'LBL_POPUPLIST'=>'Prikaz popisa skoč. prozora',
'LBL_POPUPLISTVIEW'=>'Prikaz popisa skoč. prozora',
'LBL_POPUPSEARCH'=>'Pretraživ. skoč. prozora',
'LBL_DASHLETSEARCHVIEW'=>'Pretraživanje Sugar Dashleta',
'LBL_DISPLAY_HTML'=>'Prikaži HTML kod',
'LBL_DETAILVIEW'=>'Prikaz detalja',
'LBL_DROP_HERE' => '[Ispustite ovdje]',
'LBL_EDIT'=>'Uredi',
'LBL_EDIT_LAYOUT'=>'Uredi izgled',
'LBL_EDIT_ROWS'=>'Uredi retke',
'LBL_EDIT_COLUMNS'=>'Uredi stupce',
'LBL_EDIT_LABELS'=>'Uredi oznake',
'LBL_EDIT_PORTAL'=>'Uredi portal za ',
'LBL_EDIT_FIELDS'=>'Uredi polja',
'LBL_EDITVIEW'=>'PrikazZaUređiv.',
'LBL_FILTER_SEARCH' => "Pretraži",
'LBL_FILLER'=>'(ispunjač)',
'LBL_FIELDS'=>'Polja',
'LBL_FAILED_TO_SAVE' => 'Spremanje nije uspjelo',
'LBL_FAILED_PUBLISHED' => 'Objavljivanje nije uspjelo',
'LBL_HOMEPAGE_PREFIX' => 'Moja',
'LBL_LAYOUT_PREVIEW'=>'Pregled izgleda',
'LBL_LAYOUTS'=>'Izgledi',
'LBL_LISTVIEW'=>'Prikaz popisa',
'LBL_RECORDVIEW'=>'Prikaz zapisa',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Novi paket',
'LBL_NEW_PANEL'=>'Nova ploča',
'LBL_NEW_ROW'=>'Novi redak',
'LBL_PACKAGE_DELETED'=>'Paket izbrisan',
'LBL_PUBLISHING' => 'Objavljivanje...',
'LBL_PUBLISHED' => 'Objavljeno',
'LBL_SELECT_FILE'=> 'Odaberi datoteku',
'LBL_SAVE_LAYOUT'=> 'Spremi izgled',
'LBL_SELECT_A_SUBPANEL' => 'Odaberite podploču',
'LBL_SELECT_SUBPANEL' => 'Odaberi podploču',
'LBL_SUBPANELS' => 'Podploče',
'LBL_SUBPANEL' => 'Podploča',
'LBL_SUBPANEL_TITLE' => 'Naslov:',
'LBL_SEARCH_FORMS' => 'Pretraživanje',
'LBL_STAGING_AREA' => 'Područje pripreme (povucite i ispustite stavke ovdje)',
'LBL_SUGAR_FIELDS_STAGE' => 'Polja Sugar (kliknite na stavke koje želite dodati području pripreme)',
'LBL_SUGAR_BIN_STAGE' => 'Koš Sugar (kliknite na stavke da biste ih dodali u područje pripreme)',
'LBL_TOOLBOX' => 'Alatni okvir',
'LBL_VIEW_SUGAR_FIELDS' => 'Prikaži polja Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Prikaži koš Sugar',
'LBL_QUICKCREATE' => 'Brzo stvaranje',
'LBL_EDIT_DROPDOWNS' => 'Uredi globalni padajući izbornik',
'LBL_ADD_DROPDOWN' => 'Dodaj novi globalni padajući izbornik',
'LBL_BLANK' => '-prazno-',
'LBL_TAB_ORDER' => 'Redoslijed kartica',
'LBL_TAB_PANELS' => 'Omogući kartice',
'LBL_TAB_PANELS_HELP' => 'Kada su kartice omogućene, upotrijebite padajući okvir „vrsta”<br />za svaki odjeljak da biste odredili kako će se prikazivati (kartica ili ploča)',
'LBL_TABDEF_TYPE' => 'Vrsta prikaza',
'LBL_TABDEF_TYPE_HELP' => 'Odaberite kako se ovaj odjeljak treba prikazivati. Ova mogućnost ima učinak ako ste omogućili kartice u ovom prikazu.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Kartica',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Ploča',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Odaberite ploču za prikaz ove ploče u prikazu izgleda. Odaberite karticu za prikaz ove ploče u odvojenoj kartici u izgledu. Kada je kartica određena za ploču, daljnje ploče postavljene na prikaz ploča bit će prikazane u kartici. <br/>Otvorit će se nova kartica za sljedeću ploču za koju je odabrana kartica. Ako je odabrana kartica za ploču ispod prve ploče, prva će ploča nužno biti kartica.',
'LBL_TABDEF_COLLAPSE' => 'Sažmi',
'LBL_TABDEF_COLLAPSE_HELP' => 'Odaberite da biste zadano stanje ove ploče učinili sažetim.',
'LBL_DROPDOWN_TITLE_NAME' => 'Naziv',
'LBL_DROPDOWN_LANGUAGE' => 'Jezik',
'LBL_DROPDOWN_ITEMS' => 'Stavke popisa',
'LBL_DROPDOWN_ITEM_NAME' => 'Naziv stavke',
'LBL_DROPDOWN_ITEM_LABEL' => 'Oznaka prikaza',
'LBL_SYNC_TO_DETAILVIEW' => 'Sinkroniziraj na prikaz detalja',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Odaberite ovu opciju da biste sinkronizirali ovaj izgled prikaza za uređivanje s odgovarajućim izgledom prikaza detalja. Polja i raspored polja u prikazu za uređivanje<br>automatski će se sinkronizirati i spremiti u prikaz detalja nakon što kliknete na Spremi ili Spremi i implementiraj u prikazu za uređivanje. <br>Promjene izgleda neće se moći napraviti u prikazu detalja.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Ovaj prikaz detalja sinkroniziran je s odgovarajućim prikazom za uređivanje.<br> Polja i raspored polja u ovom prikazu detalja utječu na polja i raspored polja u prikazu za uređivanje.<br> Promjene u prikazu detalja ne mogu se spremiti ili implementirati na ovoj stranici. Napravite promjene ili poništite sinkronizaciju izgleda u prikazu za uređivanje. ',
'LBL_COPY_FROM' => 'Kopiraj iz',
'LBL_COPY_FROM_EDITVIEW' => 'Kopiraj iz prikaza za uređivanje',
'LBL_DROPDOWN_BLANK_WARNING' => 'Potrebne su vrijednosti za naziv stavke i oznaku prikaza. Da biste dodali praznu stavku, kliknite na Dodaj, a da ne unesete vrijednosti za naziv stavke i oznaku prikaza.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Ključ već postoji na popisu',
'LBL_DROPDOWN_LIST_EMPTY' => 'Popis mora sadržavati barem jednu omogućenu stavku',
'LBL_NO_SAVE_ACTION' => 'Nije moguće pronaći radnju spremanja za ovaj prikaz.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: loše oblikovan dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Označava kombinirano polje. Kombinirano polje skup je pojedinačnih polja. Na primjer, „Adresa” je kombinirano polje koje sadrži „Adresu ulice”, „Grad”, „Poštanski broj”, „Državu” i „Zemlju”.<br><br>Dvaput kliknite na kombinirano polje da biste vidjeli koja polja sadrži.',
'LBL_COMBO_FIELD_CONTAINS' => 'sadrži:',

'LBL_WIRELESSLAYOUTS'=>'Mobilni izgledi',
'LBL_WIRELESSEDITVIEW'=>'Mobilni prikaz za uređivanje',
'LBL_WIRELESSDETAILVIEW'=>'Mobilni prikaz detalja',
'LBL_WIRELESSLISTVIEW'=>'Mobilni prikaz popisa',
'LBL_WIRELESSSEARCH'=>'Mobilno pretraživanje',

'LBL_BTN_ADD_DEPENDENCY'=>'Dodaj zavisnost',
'LBL_BTN_EDIT_FORMULA'=>'Uredi formulu',
'LBL_DEPENDENCY' => 'Zavisnost',
'LBL_DEPENDANT' => 'Zavisno',
'LBL_CALCULATED' => 'Izračunata vrijednost',
'LBL_READ_ONLY' => 'Samo za čitanje',
'LBL_FORMULA_BUILDER' => 'Sastavljač formula',
'LBL_FORMULA_INVALID' => 'Neispravna formula',
'LBL_FORMULA_TYPE' => 'Formula mora biti vrste ',
'LBL_NO_FIELDS' => 'Nema pronađenih polja',
'LBL_NO_FUNCS' => 'Nema pronađenih funkcija',
'LBL_SEARCH_FUNCS' => 'Pretraživanje funkcija...',
'LBL_SEARCH_FIELDS' => 'Pretraživanje polja...',
'LBL_FORMULA' => 'Formula',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Zavisno',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Povucite mogućnosti s popisa dostupnih mogućnosti u zavisnom padajućem izborniku s lijeve strane na popise s desne strane da biste učinili te mogućnosti dostupnima kad je odabrana nadređena opcija. Ako nema stavki u nadređenoj opciji, kada se ona odabere zavisni padajući izbornik neće se prikazati.',
'LBL_AVAILABLE_OPTIONS' => 'Dostupne mogućnosti',
'LBL_PARENT_DROPDOWN' => 'Nadređeni padajući izbornik',
'LBL_VISIBILITY_EDITOR' => 'Uređivač vidljivosti',
'LBL_ROLLUP' => 'Skup. vrij.',
'LBL_RELATED_FIELD' => 'Povezano polje',
'LBL_CONFIG_PORTAL_URL'=>'URL adresa slike prilagođenog logotipa. Preporučene dimenzije logotipa: 163 x 18 piksela.',
'LBL_PORTAL_ROLE_DESC' => 'Nemojte izbrisati ovu ulogu. Uloga klijenta za samouslužni portal uloga je koju generira sustav stvorena tijekom postupka aktivacije Sugar Portala. Upotrijebite kontrole pristupa u ovoj ulozi da biste omogućili i/ili onemogućili module pogrešaka, slučajeva ili baze znanja na Sugar Portalu. Nemojte mijenjati ostale kontrole pristupa za ovu ulogu da biste izbjegli nepoznato i nepredvidljivo ponašanje sustava. U slučaju slučajnog brisanja ove uloge, ponovno je stvorite tako da isključite i uključite Sugar Portal.',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Primarni modul',
'LBL_CUSTOM_RELATIONSHIPS' => '* odnos stvoren u Studiju',
'LBL_RELATIONSHIPS'=>'Odnosi',
'LBL_RELATIONSHIP_EDIT' => 'Uredi odnos',
'LBL_REL_NAME' => 'Naziv',
'LBL_REL_LABEL' => 'Oznaka',
'LBL_REL_TYPE' => 'Vrsta',
'LBL_RHS_MODULE'=>'Povezani modul',
'LBL_NO_RELS' => 'Nema odnosa',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Neobavezni uvjet' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Stupac',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Vrijednost',
'LBL_SUBPANEL_FROM'=>'Podploča iz',
'LBL_RELATIONSHIP_ONLY'=>'Za ovaj odnos neće biti stvoreni vidljivi elementi zbog prethodno postojećeg vidljivog odnosa između tih dvaju modula.',
'LBL_ONETOONE' => 'Jedan na jedan',
'LBL_ONETOMANY' => 'Jedan na mnogo',
'LBL_MANYTOONE' => 'Mnogo na jedan',
'LBL_MANYTOMANY' => 'Mnogo na mnogo',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Odaberite funkciju ili komponentu.',
'LBL_QUESTION_MODULE1' => 'Odaberite modul.',
'LBL_QUESTION_EDIT' => 'Odaberite modul za uređivanje.',
'LBL_QUESTION_LAYOUT' => 'Odaberite izgled za uređivanje.',
'LBL_QUESTION_SUBPANEL' => 'Odaberite podploču za uređivanje.',
'LBL_QUESTION_SEARCH' => 'Odaberite izgled pretraživanja za uređivanje.',
'LBL_QUESTION_MODULE' => 'Odaberite komponentu modula za uređivanje.',
'LBL_QUESTION_PACKAGE' => 'Odaberite paket za uređivanje ili stvorite novi paket.',
'LBL_QUESTION_EDITOR' => 'Odaberite alat.',
'LBL_QUESTION_DROPDOWN' => 'Odaberite padajući izbornik za uređivanje ili stvorite novi padajući izbornik.',
'LBL_QUESTION_DASHLET' => 'Odaberite izgled dashleta za uređivanje.',
'LBL_QUESTION_POPUP' => 'Odaberite izgled skočnog prozora za uređivanje.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Povezivanje s',
'LBL_NAME'=>'Naziv',
'LBL_LABELS'=>'Oznake',
'LBL_MASS_UPDATE'=>'Masovno ažuriranje',
'LBL_AUDITED'=>'Nadzor',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Zadana vrijednost',
'LBL_REQUIRED'=>'Obavezno',
'LBL_DATA_TYPE'=>'Vrsta',
'LBL_HCUSTOM'=>'PRILAGOĐENO',
'LBL_HDEFAULT'=>'ZADANO',
'LBL_LANGUAGE'=>'Jezik:',
'LBL_CUSTOM_FIELDS' => '* polje stvoreno u Studiju',

//SECTION
'LBL_SECTION_EDLABELS' => 'Uredi oznake',
'LBL_SECTION_PACKAGES' => 'Paketi',
'LBL_SECTION_PACKAGE' => 'Paket',
'LBL_SECTION_MODULES' => 'Moduli',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Padajući izbornici',
'LBL_SECTION_PROPERTIES' => 'Svojstva',
'LBL_SECTION_DROPDOWNED' => 'Uredi padajući izbornik',
'LBL_SECTION_HELP' => 'Pomoć',
'LBL_SECTION_ACTION' => 'Radnja',
'LBL_SECTION_MAIN' => 'Glavni',
'LBL_SECTION_EDPANELLABEL' => 'Uređivanje oznake ploče',
'LBL_SECTION_FIELDEDITOR' => 'Uređivanje polja',
'LBL_SECTION_DEPLOY' => 'Implementiraj',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Uredi vidljivost',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Zadano',
'LBL_HIDDEN'=>'Skriveno',
'LBL_AVAILABLE'=>'Dostupno',
'LBL_LISTVIEW_DESCRIPTION'=>'U nastavku su prikazana tri stupca. <b>Zadani</b> stupac sadrži polja koja su zadano prikazana u prikazu popisa. <b>Dodatni</b> stupac sadrži polja koja korisnik može izabrati za upotrebu pri stvaranju prilagođenog prikaza. <b>Dostupni</b> stupac prikazuje polja koja vi kao administrator možete korisnicima dodati u zadani ili dostupni stupac za upotrebu.',
'LBL_LISTVIEW_EDIT'=>'Uređivač prikaza popisa',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Pregled',
'LBL_MB_RESTORE'=>'Vrati',
'LBL_MB_DELETE'=>'Izbriši',
'LBL_MB_COMPARE'=>'Usporedi',
'LBL_MB_DEFAULT_LAYOUT'=>'Zadani izgled',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Dodaj',
'LBL_BTN_SAVE'=>'Spremi',
'LBL_BTN_SAVE_CHANGES'=>'Spremi promjene',
'LBL_BTN_DONT_SAVE'=>'Odbaci promjene',
'LBL_BTN_CANCEL'=>'Odustani',
'LBL_BTN_CLOSE'=>'Zatvori',
'LBL_BTN_SAVEPUBLISH'=>'Spremi i implementiraj',
'LBL_BTN_NEXT'=>'Sljedeće',
'LBL_BTN_BACK'=>'Natrag',
'LBL_BTN_CLONE'=>'Kloniraj',
'LBL_BTN_COPY' => 'Kopiraj',
'LBL_BTN_COPY_FROM' => 'Kopiraj iz…',
'LBL_BTN_ADDCOLS'=>'Dodaj stupce',
'LBL_BTN_ADDROWS'=>'Dodaj retke',
'LBL_BTN_ADDFIELD'=>'Dodaj polje',
'LBL_BTN_ADDDROPDOWN'=>'Dodaj padajući izbornik',
'LBL_BTN_SORT_ASCENDING'=>'Razvrstaj uzlazno',
'LBL_BTN_SORT_DESCENDING'=>'Razvrstaj silazno',
'LBL_BTN_EDLABELS'=>'Uredi oznake',
'LBL_BTN_UNDO'=>'Poništi',
'LBL_BTN_REDO'=>'Vrati',
'LBL_BTN_ADDCUSTOMFIELD'=>'Dodaj prilagođeno polje',
'LBL_BTN_EXPORT'=>'Izvezi prilagodbe',
'LBL_BTN_DUPLICATE'=>'Dupliciraj',
'LBL_BTN_PUBLISH'=>'Objavi',
'LBL_BTN_DEPLOY'=>'Implementiraj',
'LBL_BTN_EXP'=>'Izvezi',
'LBL_BTN_DELETE'=>'Izbriši',
'LBL_BTN_VIEW_LAYOUTS'=>'Prikaži izglede',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Prikaži mobilne izglede',
'LBL_BTN_VIEW_FIELDS'=>'Prikaži polja',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Prikaži odnose',
'LBL_BTN_ADD_RELATIONSHIP'=>'Dodaj odnos',
'LBL_BTN_RENAME_MODULE' => 'Promijeni naziv modula',
'LBL_BTN_INSERT'=>'Umetni',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Pogreška: polje već postoji',
'ERROR_INVALID_KEY_VALUE'=> "Pogreška: neispravna vrijednost ključa: [']",
'ERROR_NO_HISTORY' => 'Nisu pronađene datoteke povijesti',
'ERROR_MINIMUM_FIELDS' => 'Izgled mora sadržavati barem jedno polje',
'ERROR_GENERIC_TITLE' => 'Došlo je do pogreške',
'ERROR_REQUIRED_FIELDS' => 'Jeste li sigurni da želite nastaviti? Sljedeća obavezna polja nedostaju u izgledu:  ',
'ERROR_ARE_YOU_SURE' => 'Jeste li sigurni da želite nastaviti?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Sljedeće polje (ili polja) ima izračunate vrijednosti koje neće biti ponovno izračunate u stvarnom vremenu u prikazu za uređivanje aplikacije SugarCRM Mobile:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Sljedeće polje (ili polja) ima izračunate vrijednosti koje neće biti ponovno izračunate u stvarnom vremenu u prikazu za uređivanje aplikacije SugarCRM Portal:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Sljedeći je modul (ili moduli) onemogućen:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Ako želite ih omogućiti na portalu, omogućite ih <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">ovdje</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Konfiguriraj portal',
    'LBL_PORTAL_THEME' => 'Portal teme',
    'LBL_PORTAL_ENABLE' => 'Omogući',
    'LBL_PORTAL_SITE_URL' => 'Web-mjesto portala dostupno je na:',
    'LBL_PORTAL_APP_NAME' => 'Naziv aplikacije',
    'LBL_PORTAL_LOGO_URL' => 'URL logotipa',
    'LBL_PORTAL_LIST_NUMBER' => 'Broj zapisa za prikaz na popisu',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Broj polja za prikaz na prikazu detalja',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Broj rezultata za prikaz u globalnom pretraživanju',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Zadana vrijednost za nove registracije na portalu',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Izgledi portala',
'LBL_SYNCP_WELCOME'=>'Unesite URL adresu instance portala koju želite ažurirati.',
'LBL_SP_UPLOADSTYLE'=>'Odaberite list stila za učitavanje s računala.<br> List stila bit će implementiran u Sugar Portalu pri sljedećoj sinkronizaciji.',
'LBL_SP_UPLOADED'=> 'Učitano',
'ERROR_SP_UPLOADED'=>'Provjerite učitavate li list stila css.',
'LBL_SP_PREVIEW'=>'Slijedi pregled izgleda Sugar Portala s listom stila.',
'LBL_PORTALSITE'=>'URL adresa Sugar Portala: ',
'LBL_PORTAL_GO'=>'Idi',
'LBL_UP_STYLE_SHEET'=>'Učitaj list stila',
'LBL_QUESTION_SUGAR_PORTAL' => 'Odaberite izgled Sugar Portala za uređivanje.',
'LBL_QUESTION_PORTAL' => 'Odaberite izgled portala za uređivanje.',
'LBL_SUGAR_PORTAL'=>'Uređivač Sugar Portala',
'LBL_USER_SELECT' => '-- Odaberite --',

//PORTAL PREVIEW
'LBL_CASES'=>'Slučajevi',
'LBL_NEWSLETTERS'=>'Bilteni',
'LBL_BUG_TRACKER'=>'Pratitelj pogrešaka',
'LBL_MY_ACCOUNT'=>'Moj račun',
'LBL_LOGOUT'=>'Odjava',
'LBL_CREATE_NEW'=>'Stvori novo',
'LBL_LOW'=>'Niski',
'LBL_MEDIUM'=>'Srednji',
'LBL_HIGH'=>'Visoki',
'LBL_NUMBER'=>'Broj:',
'LBL_PRIORITY'=>'Prioritet:',
'LBL_SUBJECT'=>'Predmet',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Naziv paketa:',
'LBL_MODULE_NAME'=>'Naziv modula:',
'LBL_MODULE_NAME_SINGULAR' => 'Naziv modula u jednini:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Opis:',
'LBL_KEY'=>'Ključ:',
'LBL_ADD_README'=>' PročitajMe',
'LBL_MODULES'=>'Moduli:',
'LBL_LAST_MODIFIED'=>'Posljednja izmjena:',
'LBL_NEW_MODULE'=>'Novi modul',
'LBL_LABEL'=>'Oznaka u množini',
'LBL_LABEL_TITLE'=>'Oznaka',
'LBL_SINGULAR_LABEL' => 'Oznaka u jednini',
'LBL_WIDTH'=>'Širina',
'LBL_PACKAGE'=>'Paket:',
'LBL_TYPE'=>'Vrsta:',
'LBL_TEAM_SECURITY'=>'Sigurnost tima',
'LBL_ASSIGNABLE'=>'Dodjeljivo',
'LBL_PERSON'=>'Osoba',
'LBL_COMPANY'=>'Tvrtka',
'LBL_ISSUE'=>'Problem',
'LBL_SALE'=>'Prodaja',
'LBL_FILE'=>'Datoteka',
'LBL_NAV_TAB'=>'Navigacijska kartica',
'LBL_CREATE'=>'Stvori',
'LBL_LIST'=>'Popis',
'LBL_VIEW'=>'Prikaži',
'LBL_LIST_VIEW'=>'Prikaz popisa',
'LBL_HISTORY'=>'Prikaži povijest',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Vrati zadani izgled',
'LBL_ACTIVITIES'=>'Pregled aktivnosti',
'LBL_SEARCH'=>'Pretraži',
'LBL_NEW'=>'Novo',
'LBL_TYPE_BASIC'=>'osnovno',
'LBL_TYPE_COMPANY'=>'tvrtka',
'LBL_TYPE_PERSON'=>'osoba',
'LBL_TYPE_ISSUE'=>'problem',
'LBL_TYPE_SALE'=>'prodaja',
'LBL_TYPE_FILE'=>'datoteka',
'LBL_RSUB'=>'Ovo je podploča koja će se prikazivati u vašem modulu',
'LBL_MSUB'=>'Ovo je podploča koju vaš modul pruža povezanom modulu za prikaz',
'LBL_MB_IMPORTABLE'=>'Dozvoli uvoze',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'vidljivo',
'LBL_VE_HIDDEN'=>'skriveno',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] je izbrisan',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Izvezi prilagodbe',
'LBL_EC_NAME'=>'Naziv paketa:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Opis:',
'LBL_EC_KEY'=>'Ključ:',
'LBL_EC_CHECKERROR'=>'Odaberite modul.',
'LBL_EC_CUSTOMFIELD'=>'prilagođeno/a polje/a',
'LBL_EC_CUSTOMLAYOUT'=>'prilagođeni izgled/i',
'LBL_EC_CUSTOMDROPDOWN' => 'prilagođeni padajući izbornik/ci',
'LBL_EC_NOCUSTOM'=>'Nijedan modul nije prilagođen.',
'LBL_EC_EXPORTBTN'=>'Izvezi',
'LBL_MODULE_DEPLOYED' => 'Modul je implementiran.',
'LBL_UNDEFINED' => 'nedefinirano',
'LBL_EC_CUSTOMLABEL'=>'prilagođena/e oznaka/e',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Dohvaćanje podataka nije uspjelo',
'LBL_AJAX_TIME_DEPENDENT' => 'U tijeku je vremenski ograničena radnja. Pričekajte i pokušajte ponovno za nekoliko sekundi.',
'LBL_AJAX_LOADING' => 'Učitavanje...',
'LBL_AJAX_DELETING' => 'Brisanje...',
'LBL_AJAX_BUILDPROGRESS' => 'Gradnja u tijeku...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Implementacija u tijeku...',
'LBL_AJAX_FIELD_EXISTS' =>'Naziv polja koji ste unijeli već postoji. Unesite novi naziv polja.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Jeste li sigurni da želite ukloniti ovaj paket? To će trajno izbrisati sve datoteke povezane s tim paketom.',
'LBL_JS_REMOVE_MODULE' => 'Jeste li sigurni da želite ukloniti ovaj modul? To će trajno izbrisati sve datoteke povezane s tim modulom.',
'LBL_JS_DEPLOY_PACKAGE' => 'Sve prilagodbe koje ste napravili u Studiju bit će prebrisane pri ponovnom implementiranju ovog modula. Jeste li sigurni da želite nastaviti?',

'LBL_DEPLOY_IN_PROGRESS' => 'Implementiranje paketa',
'LBL_JS_VALIDATE_NAME'=>'Naziv – mora započinjati slovom i smije se sastojati samo od slova, brojeva i podvlaka. Ne smiju se upotrijebiti razmaci ni ostali posebni znakovi.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Ključ za paket već postoji',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Naziv paketa već postoji',
'LBL_JS_PACKAGE_NAME'=>'Naziv paketa – mora započinjati slovom i smije se sastojati samo od slova, brojeva i podvlaka. Ne smiju se upotrijebiti razmaci ni ostali posebni znakovi.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Ključ – mora biti alfanumerički i započinjati slovom.',
'LBL_JS_VALIDATE_KEY'=>'Ključ – mora biti alfanumerički, započinjati slovom i ne smije sadržavati razmake.',
'LBL_JS_VALIDATE_LABEL'=>'Unesite oznaku koja će se upotrebljavati kao naziv za prikaz za ovaj modul',
'LBL_JS_VALIDATE_TYPE'=>'Odaberite vrstu modula koju želite izgraditi s gornjeg popisa',
'LBL_JS_VALIDATE_REL_NAME'=>'Naziv – mora biti alfanumerički bez razmaka',
'LBL_JS_VALIDATE_REL_LABEL'=>'Oznaka – dodajte oznaku koja će se prikazivati iznad podploče',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Jeste li sigurni da želite izbrisati ovu obaveznu stavku padajućeg popisa? To može utjecati na funkcionalnost vaše aplikacije.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Jeste li sigurni da želite izbrisati ovu stavku padajućeg popisa? Brisanje faza Zatvoreno kao uspjelo ili Zatvoreno kao neuspjelo uzrokovat će neispravan rad modula predviđanja',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Jeste li sigurni da želite izbrisati status prodaje Novo? Brisanje ovog statusa uzrokovat će neispravan rad tijeka rada stavke prihoda modula prilika.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Jeste li sigurni da želite izbrisati status prodaje U tijeku? Brisanje ovog statusa uzrokovat će neispravan rad tijeka rada stavke prihoda modula prilika.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Jeste li sigurni da želite izbrisati fazu prodaje Zatvoreno kao uspjelo? Brisanje ove faze uzrokovat će neispravan rad modula predviđanja',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Jeste li sigurni da želite izbrisati fazu prodaje Zatvoreno kao neuspjelo? Brisanje ove faze uzrokovat će neispravan rad modula predviđanja',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Brisanjem ovog prilagođenog polja izbrisat će se prilagođeno polje i svi podaci povezani s prilagođenim poljem u bazi podataka. Polje se više neće pojavljivati u izgledima modula.'
        . ' Ako je polje uključeno u formulu za izračunavanje vrijednosti za polja, formula više neće raditi.'
        . '\\n\\nPolje više neće biti dostupno za upotrebu u izvješćima; ova će se promjena primijeniti nakon odjave i ponovne prijave u aplikaciju. Izvješća koja sadrže polje morat će se ažurirati da bi mogla raditi.'
        . '\\n\\nŽelite li nastaviti?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Jeste li sigurni da želite izbrisati ovaj odnos?<br>Napomena: Ovaj postupak može potrajati nekoliko minuta.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Ovo će odnos učiniti trajnim. Jeste li sigurni da želite implementirati ovaj odnos?',
'LBL_CONFIRM_DONT_SAVE' => 'Napravljene su promjene od posljednjeg spremanja, želite li spremiti?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Spremiti promjene?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Podaci će se možda skratiti i to se ne može poništiti, jeste li sigurni da želite nastaviti?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Odaberite odgovarajuću vrstu podataka na temelju vrste podataka koji će biti uneseni u polje.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Konfigurirajte polje da se u njemu može pretraživati cijeli tekst.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Pojačavanje je postupak povećanja važnosti polja zapisa.<br />Polja s većom razinom pojačavanja dobit će veću težinu nakon pretraživanja. Nakon pretraživanja podudarajući zapisi koji sadrže polja s većom težinom pojavit će se više u rezultatima pretraživanja.<br />Zadana je vrijednost 1,0, što znači neutralno pojačavanje. Da biste primijenili pozitivno pojačavanje, prihvaća se bilo koji realan broj veći od 1. Za negativno pojačavanje upotrijebite vrijednost manju od 1. Na primjer, vrijednost 1,35 pozitivno će pojačati polje za 135 %. Upotrebom vrijednosti 0,60 primijenit će se negativno pojačavanje.<br />Imajte na umu da je u prethodnim verzijama bilo potrebno izvršiti potpuno ponovno indeksiranje pretraživanja cijelog teksta. To više nije potrebno.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Da</b>: polje će biti uključeno u uvoz.<br><b>Ne</b>: polje neće biti uključeno u uvoz.<br><b>Obavezno</b>: vrijednost za polje mora se navesti u svim uvozima.',
'LBL_POPHELP_PII'=>'Ovo polje će automatski biti označeno za nadzor i dostupno u prikazu osobnih podataka.<br>Polja osobnih podataka mogu se trajno izbrisati kada je zapis vezan uz zahtjev za brisanje osobnih podataka.<br>Brisanje se vrši putem modula za zaštitu osobnih podataka, a mogu ga izvršiti administratori ili korisnici u ulozi Voditelja zaštite osobnih podataka.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Unesite broj za širinu u pikselima.<br> Učitana slika bit će umanjena na tu širinu.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Unesite broj za visinu u pikselima.<br> Učitana slika bit će umanjena na tu visinu.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Omogućeno</b>: polje će se prikazati u značajki Spajanje duplikata, no neće biti dostupno za upotrebu za uvjete filtra u značajki Traženje duplikata.<br><b>Onemogućeno</b>: polje se neće prikazati u značajki Spajanje duplikata i neće biti dostupno za upotrebu za uvjete filtra u značajki Traženje duplikata.'
. '<br><b>U filtru</b>: polje će se prikazati u značajki Spajanje duplikata i bit će dostupno za upotrebu u značajki Traženje duplikata.<br><b>Samo filtar</b>: polje se neće prikazati u značajki Spajanje duplikata, no bit će dostupno za upotrebu u značajki Traženje duplikata.<br><b>Zadani odabrani filtar</b>: polje će se zadano upotrijebiti za uvjet filtra na stranici traženja duplikata te će se prikazati u značajki Spajanje duplikata.'
,
'LBL_POPHELP_CALCULATED'=>"Stvori formulu za određivanje vrijednosti u ovom polju.<br>"
   . "Definicije tijeka rada koje sadrže radnje postavljene za ažuriranje ovog polja više neće izvršavati radnju.<br>"
   . "Polja koja koriste formule neće se računati u stvarnom vremenu u "
   . "samouslužnom portalu Sugara ili "
   . "U izgledu mobilnog prikaza za uređivanje.",

'LBL_POPHELP_DEPENDENT'=>"Stvori formulu koja će odrediti hoće li ovo polje biti prikazano u izgledima.<br/>"
        . "Zavisna polja pratit će formulu zavsinosti u mobilnom prikazu temeljenom na pregledniku, <br/>"
        . "ali neće pratiti formulu u izravnim aplikacijama kao što je Sugar Mobile za iPhone. <br/>"
        . "Neće pratiti formulu u samouslužnom portalu Sugara.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Odaberite upotrebu ovog polja za pretraživanje zapisa pomoću globalnog pretraživanja na ovom modulu.',
//Revert Module labels
'LBL_RESET' => 'Pon. post.',
'LBL_RESET_MODULE' => 'Ponovno postavi modul',
'LBL_REMOVE_CUSTOM' => 'Ukloni prilagodbe',
'LBL_CLEAR_RELATIONSHIPS' => 'Ukloni odnose',
'LBL_RESET_LABELS' => 'Ponovno postavi oznake',
'LBL_RESET_LAYOUTS' => 'Ponovno postavi izglede',
'LBL_REMOVE_FIELDS' => 'Ukloni prilagođena polja',
'LBL_CLEAR_EXTENSIONS' => 'Ukloni proširenja',

'LBL_HISTORY_TIMESTAMP' => 'VremenskaOznaka',
'LBL_HISTORY_TITLE' => ' povijest',

'fieldTypes' => array(
                'varchar'=>'TekstnoPolje',
                'int'=>'Cijeli broj',
                'float'=>'Realan broj',
                'bool'=>'Potvrdni okvir',
                'enum'=>'PadajućiIzbor.',
                'multienum' => 'VišestrukiOdabir',
                'date'=>'Datum',
                'phone' => 'Telefon',
                'currency' => 'Valuta',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Povezivanje',
                'address' => 'Adresa',
                'text' => 'TekstnoPodručje',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Slika',
                'encrypt'=>'Šifriranje',
                'datetimecombo' =>'Datum/vrijeme',
                'decimal'=>'Decimalno',
),
'labelTypes' => array(
    "" => "Često korištene oznake",
    "all" => "Sve oznake",
),

'parent' => 'Fleksibilno poveziv.',

'LBL_ILLEGAL_FIELD_VALUE' =>"Padajući ključ ne smije sadržavati navodnike.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Odabirete ovu stavku za uklanjanje s padajućeg popisa. Sva padajuća polja koja upotrebljavaju ovaj popis s tom stavkom kao vrijednosti više neće prikazivati vrijednost, a vrijednost više neće biti moguće izabrati iz padajućih polja. Jeste li sigurni da želite nastaviti?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Odaberite da biste provjerili valjanost ovog polja za unos deseteroznamenkastog<br>" .
                                 "telefonskog broja, s dopuštenjem za pozivni broj zemlje 1 i<br>" .
                                 "i da biste primijenili format SAD-a na telefonski broj pri spremanju<br>" .
                                 "zapisa. Bit će primijenjen sljedeći format: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Svi moduli',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (ID: povezano {1})',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Kopiranje iz izgleda',
);
