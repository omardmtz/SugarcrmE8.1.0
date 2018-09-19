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
    'LBL_LOADING' => 'Laadimine' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Peida suvandid' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Kustuta' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Powered By SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Roll',
'help'=>array(
    'package'=>array(
            'create'=>'Sisestage paketi <b>Nimi</b>. Nimi peab algama tähega ja võib sisaldada ainult tähti, numbreid ja allkriipse. Tühikuid ega muid erimärke kasutada ei tohi. (Näide: HR_Management)<br/><br/> Saate sisestada paketi teabe <b>Autor</b> ja <b>Kirjeldus</b>. <br/><br/>Paketi loomiseks klõpsake nuppu <b>Salvesta</b>.',
            'modify'=>'<b>Paketi</b> atribuudid ja võimalikud toimingud kuvatakse siin.<br><br>Saate muuta paketi puhul suvandeid <b>Nimi</b>, <b>Autor</b> ja <b>Kirjeldus</b> ning ühtlasi vaadata ja kohandada kõiki paketis sisalduvaid mooduleid.<br><br>Paketi uue mooduli loomiseks klõpsake suvandit <b>Uus moodul</b>.<br><br>Kui pakett sisaldab vähemalt ühte moodulit, saate paketi puhul valida <b>Avalda</b> ja <b>Juuruta</b> ja valida ka paketi puhul tehtud kohanduste puhul siuvandi <b>Ekspordi</b>.',
            'name'=>'See on praeguse paketi <b>Nimi</b>. <br/><br/>Nimi peab algama tähega ja võib sisaldada ainult tähti, numbreid ja allkriipse. Tühikuid ega muid erimärke kasutada ei tohi. (Näide: HR_Management)',
            'author'=>'See on <b>Autor</b>, mis kuvatakse installimisel paketi loonud üksuse nimena.<br><br>Autoriks võib olla kas üksikisik või ettevõte.',
            'description'=>'See on installimisel kuvatava paketi <b>Kirjeldus</b>.',
            'publishbtn'=>'Klõpsake suvandit <b>Avalda</b> kõigi sisestatud andmete salvestamiseks ja .zip-faili loomiseks, mis on paketi installitav versioon.<br><br>Kasutage suvandit <b>Moodulilaadur</b> .zip-faili üleslaadimiseks ja paketi installimiseks.',
            'deploybtn'=>'Klõpsake suvandit <b>Juuruta</b> kõigi sisestatud andmete salvestamiseks ja paketi, sh kõikide praeguse eksemplari moodulite installimiseks.',
            'duplicatebtn'=>'Klõpsake suvandit <b>Dubleeri</b> paketi sisu kopeerimiseks uude paketti ja uue paketi kuvamiseks. <br/><br/>Uue paketi puhul luuakse uus nimi automaatselt, lisades uue paketi loomiseks kasutatud paketi nime lõppu numbri. Saate uue paketi ümber nimetada, sisestades uue <b>nime</b> ja klõpsates suvandit <b>Salvesta</b>.',
            'exportbtn'=>'Klõpsake nuppu <b>Ekspordi</b> paketis tehtud kohandusi sisaldava .zip-faili loomiseks.<br><br> Loodud fail pole paketi installitav versioon.<br><br>Kasutage suvandit <b>Moodulilaadur</b> .zip-faili importimiseks ja paketi, sh kohanduste kuvamiseks Moodulilaaduris.',
            'deletebtn'=>'Klõpsake nuppu <b>Kustuta</b> selle paketi ja sellega seotud failide kustutamiseks.',
            'savebtn'=>'Klõpsake nuppu <b>Salvesta</b> kõigi paketiga seotud sisestatud andmete salvestamiseks.',
            'existing_module'=>'Klõpsake ikooni <b>Moodul</b> atribuutide redigeerimiseks ja mooduliga seotud väljade, seoste ja paigutuste kohandamiseks.',
            'new_module'=>'Klõpsake suvandit <b>Uus moodul</b> selle paketi uue mooduli loomiseks.',
            'key'=>'Seda 5-tähelist, kirja <b>klahvi</b> kasutatakse kõikide praeguse paketi moodulite kataloogide, klassi nimede ja andmebaasitabelite eesliitena.<br><br>Klahvi kasutatakse nime kordumatuse saavutamiseks.',
            'readme'=>'Klõpsake sellele paketile <b>Seletuse</b> teksti lisamiseks.<br><br>Seletus on saadaval installimisel.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Sisestage mooduli <b>Nimi</b>. Teie sisestatud <b>Silt</b> kuvatakse navigatsiooni vahekaardil. <br/><br/>Valige navigatsiooni vahekaardi kuvamine mooduli puhul, märkides ruudu <b>Navigatsiooni vahekaart</b>.<br/><br/>Märkige ruut <b>Meeskonna turvalisus</b> välja Meeskonna valik kaasamiseks mooduli kirjetesse. <br/><br/>Seejärel valige mooduli tüüp, mille soovite luua. <br/><br/>Valige malli tüüp. Iga mall sisaldab konkreetset väljade kogumit ja eelmääratletud paigutusi, mida mooduli puhul alusena kasutada. <br/><br/>Mooduli loomiseks klõpsake nuppu <b>Salvesta</b>.',
        'modify'=>'Saate muuta mooduli atribuute või kohandada mooduliga seotud suvandeid <b>Väljad</b>, <b>Seosed</b> ja <b>Paigutused</b>.',
        'importable'=>'Märkeruudu <b>Imporditav</b> valimine võimaldab selle mooduli puhul importimise.<br><br>Link suvandile Impordi viisard kuvatakse mooduli paneelil Otseteed. Suvand Impordi viisard hõlbustab andmete importimist välisallikatest kohandatud moodulisse.',
        'team_security'=>'Märkeruudu <b>Meeskonna turvalisus</b> valimine võimaldab selle mooduli puhul meeskonna turvalisuse. <br/><br/>Kui meeskonna turvalisus on lubatud, kuvatakse väli Meeskonna valik kirjetes moodulis ',
        'reportable'=>'Selle ruudu märkimine võimaldab töödelda selle mooduli aruandeid.',
        'assignable'=>'Selle ruudu märkimine võimaldab määrata selle mooduli kirje valitud kasutajale.',
        'has_tab'=>'Suvandi <b>Navigatsiooni vahekaart</b> esitab mooduli navigatsiooni vahekaardi.',
        'acl'=>'Selle ruudu märkimine võimaldab sellel moodulil Juurdepääsu kontrolli, sh Välja tasemel turvalisuse.',
        'studio'=>'Selle ruudu märkimine võimaldab administraatoritel seda moodulit Studios kohandada.',
        'audit'=>'Selle ruudu märkimine võimaldab selle mooduli puhul suvandi Auditeerimine. Kindlate väljade muutmine talletatakse nii, et administraatorid saavad muudatuste ajalugu vaadata.',
        'viewfieldsbtn'=>'Klõpsake suvandit <b>Vaata välju</b> mooduliga seotud väljade vaatamiseks ja kohandatud väljade redigeerimiseks.',
        'viewrelsbtn'=>'Klõpsake suvandit <b>Vaata seoseid</b> selle mooduliga seotud seoste vaatamiseks ja uute seoste loomiseks.',
        'viewlayoutsbtn'=>'Klõpsake suvandit <b>Vaata paigutusi</b> mooduli paigutuste vaatamiseks ja paigutuste välja korralduse kohandamiseks.',
        'viewmobilelayoutsbtn' => 'Klõpsake suvandit <b>Vaata mobiilseid paigutusi</b> mooduli mobiilsete paigutuste vaatamiseks ja paigutuste välja korralduse kohandamiseks.',
        'duplicatebtn'=>'Klõpsake nuppu <b>Dubleeri</b> mooduli atribuutide kopeerimiseks uude moodulisse ja uue mooduli kuvamiseks. <br/><br/>Uue mooduli puhul luuakse uus nimi automaatselt, lisades uue mooduli loomiseks kasutatud mooduli nime lõppu numbri.',
        'deletebtn'=>'Mooduli kustutamiseks klõpsake nuppu <b>Kustuta</b>.',
        'name'=>'See on praeguse mooduli <b>Nimi</b>.<br/><br/>Nimi peab olema tähtnumbriline ja algama tähega ega tohi sisaldada tühikuid. (Näide: HR_Management)',
        'label'=>'See on mooduli navigatsiooni vahekaardil kuvatav <b>Silt</b>. ',
        'savebtn'=>'Klõpsake nuppu <b>Salvesta</b> kõigi mooduliga seotud sisestatud andmete salvestamiseks.',
        'type_basic'=>'Mall <b>Põhiline</b> pakub põhivälju, nagu välju Nimi, Määratud kasutajale, Meeskond, Loomiskuupäev ja Kirjeldus.',
        'type_company'=>'Malli tüüp <b>Ettevõte</b> pakub organisatsioonile omaseid välju, nagu Ettevõtte nimi, Valdkond ja Arve aadress.<br/><br/>Kasutage seda malli moodulite loomiseks, mis sarnanevad standardse mooduliga Kontod.',
        'type_issue'=>'Malli tüüp <b>Probleem</b> pakub juhtumi- ja veaomaseid välju, nagu Arv, Olek, Tähtsus ja Kirjeldus.<br/><br/>Kasutage seda malli moodulite loomiseks, mis sarnanevad standardsete moodulitega Juhtumid ja Vigade otsija.',
        'type_person'=>'Malli tüüp <b>Isik</b> pakub üksikisikuomaseid välju, nagu Tervitus, Ametinimetus, Nimi, Aadress ja Telefoninumber.<br/><br/>Kasutage seda malli moodulite loomiseks, mis sarnanevad standardsete moodulitega Kontaktid ja Müügivihjed.',
        'type_sale'=>'Malli tüüp <b>Müük</b> pakub võimaluseomaseid välju, nagu Müügivihje allikas, Etapp, Summa ja Tõenäosus. <br/><br/>Kasutage seda malli moodulite loomiseks, mis sarnaneb standardse mooduliga Müügivõimalused.',
        'type_file'=>'Mall <b>Fail</b> pakub dokumendiomaseid konkreetseid välju, nagu Faili nimi, Dokumendi tüüp ja Avaldamiskuupäev.<br><br>Kasutage seda malli moodulite loomiseks, mis sarnaneb standardse mooduliga Dokumendid.',

    ),
    'dropdowns'=>array(
        'default' => 'Kõik rakenduse <b>Ripploendid</b> on loetletud siin.<br><br>Ripploendeid saab kasutada rippväljade puhul mis tahes moodulis.<br><br>Olemasoleva ripploendi muutmiseks klõpsake ripploendi nimel.<br><br>Uue ripploendi loomiseks klõpsake suvandit <b>Lisa ripploend</b>.',
        'editdropdown'=>'Ripploendeid saab kasutada standardsete või kohandatud rippväljade puhul mis tahes moodulis.<br><br>Sisestage ripploendi <b>Nimi</b>.<br><br>Kui rakendusse on installitud keelepakette, saate loendiüksuste puhul kasutamiseks valida suvandi <b>Keel</b>.<br><br>Sisestage suvandi nimi välja <b>Üksuse nimi</b> ripploendisse. Seda nime ei kuvata kasutajatele nähtavas ripploendis.<br><br>Sisestage väljale <b>Kuva silt</b> kasutajatele nähtav silt.<br><br>Pärast üksuse nime ja kuva sildi sisestamist klõpsake üksuse ripploendisse lisamiseks nuppu <b>Lisa</b>.<br><br>Loendi üksuste järjestuse muutmiseks pukseerige üksused soovitud kohtadesse.<br><br>Üksuse kuva sildi redigeerimiseks klõpsake <b>ikooni Redigeeri</b> ja sisestage uus silt. Üksuse kustutamiseks ripploendist klõpsake <b>ikooni Kustuta</b>.<br><br>Kuva sildi osas tehtud muudatuse ennistamiseks klõpsake nuppu <b>Ennista</b>. Ennistatud muudatuse uuesti tegemiseks klõpsake nuppu <b>Tee uuesti</b>.<br><br>Ripploendi salvestamiseks klõpsake nuppu <b>Salvesta</b>.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'Kõik väljad, mida saab kuvada kohas <b>Alampaneel</b>, kuvatakse siin.<br><br>Veerg <b>Vaikimisi</b> sisaldab alampaneelil kuvatavaid välju.<br/><br/>Veerg <b>Peidetud</b> sisaldab välju, mille saab lisada veergu Vaikimisi.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Viitab väljale Sõltuv, mis valemi väärtusest olenevalt võib olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Viitab väljale Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        'savebtn'	=> 'Klõpsake nuppu <b>Salvesta ja juuruta</b> tehtud muudatuste salvestamiseks ja nende moodulis aktiivseks muutmiseks.',
        'historyBtn'=> 'Klõpsake nuppu <b>Vaata ajalugu</b> eelnevalt salvestatud paigutuse vaatamiseks ja taastamiseks ajaloost.',
        'historyRestoreDefaultLayout'=> 'Klõpsake suvandit <b>Taasta vaikepaigutus</b> vaate algpaigutuse taastamiseks.',
        'Hidden' 	=> '<b>Peidetud</b> välju alampaneelil ei kuvata.',
        'Default'	=> '<b>Vaikimisi</b> väljad kuvatakse alampaneelil.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'Kõik väljad, mida saab kuvada suvandis <b>Loendivaade</b>, kuvatakse siin.<br><br>Veerg <b>Vaikimisi</b> sisaldab välju, mis kuvatakse suvandis Loendivaade vaikimisi.<br/><br/>Veerg <b>Saadaval</b> sisaldab välju, mida kasutaja saab suvandis Otsing kohandatud loendivaate loomiseks kasutada. <br/><br/>Veerg <b>Peidetud</b> sisaldab välju, mille saab lisada veergu Vaikimisi või Saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Viitab väljale Sõltuv, mis valemi väärtusest olenevalt võib olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Viitab väljale Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        'savebtn'	=> 'Klõpsake nuppu <b>Salvesta ja juuruta</b> tehtud muudatuste salvestamiseks ja nende moodulis aktiivseks muutmiseks.',
        'historyBtn'=> 'Klõpsake nuppu <b>Vaata ajalugu</b> eelnevalt salvestatud paigutuse vaatamiseks ja taastamiseks ajaloost.<br><br><b>Taasta</b> suvandis <b>Vaata ajalugu</b> taastab välja paigutuse eelnevalt salvestatud paigutustes. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'historyRestoreDefaultLayout'=> 'Klõpsake suvandit <b>Taasta vaikepaigutus</b> vaate algpaigutuse taastamiseks.<br><br><b>Taasta vaikepaigutus</b> taastab välja paigutuse ainult algpaigutuses. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'Hidden' 	=> '<b>Peidetud</b> väljad pole praegu kasutajatele loendivaadetes vaatamiseks saadaval.',
        'Available' => '<b>Saadaval</b> välju ei kuvata vaikimisi, kuid kasutajad saavad neid loendivaadetesse lisada.',
        'Default'	=> '<b>Vaikimisi</b> väljad kuvatakse loendivaadetes, mida kasutajad pole kohandanud.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'Kõik väljad, mida saab kuvada suvandis <b>Loendivaade</b>, kuvatakse siin.<br><br>Veerg <b>Vaikimisi</b> sisaldab välju, mis kuvatakse suvandis Loendivaade vaikimisi.<br/><br/>Veerg <b>Peidetud</b> sisaldab välju, mille saab lisada veergu Vaikimisi või Saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Viitab väljale Sõltuv, mis valemi väärtusest olenevalt võib olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Viitab väljale Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        'savebtn'	=> 'Klõpsake nuppu <b>Salvesta ja juuruta</b> tehtud muudatuste salvestamiseks ja nende moodulis aktiivseks muutmiseks.',
        'historyBtn'=> 'Klõpsake nuppu <b>Vaata ajalugu</b> eelnevalt salvestatud paigutuse vaatamiseks ja taastamiseks ajaloost.<br><br><b>Taasta</b> suvandis <b>Vaata ajalugu</b> taastab välja paigutuse eelnevalt salvestatud paigutustes. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'historyRestoreDefaultLayout'=> 'Klõpsake suvandit <b>Taasta vaikepaigutus</b> vaate algpaigutuse taastamiseks.<br><br><b>Taasta vaikepaigutus</b> taastab välja paigutuse ainult algpaigutuses. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'Hidden' 	=> '<b>Peidetud</b> väljad pole praegu kasutajatele loendivaadetes vaatamiseks saadaval.',
        'Default'	=> '<b>Vaikimisi</b> väljad kuvatakse loendivaadetes, mida kasutajad pole kohandanud.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'Kõik väljad, mida saab kuvada vormis <b>Otsing</b>, kuvatakse siin.<br><br>Veerg <b>Vaikimisi</b> sisaldab vormis Otsing kuvatavaid välju.<br/><br/>Veerg <b>Peidetud</b> sisaldab välju, mis on teile kui administraatorile vormi Otsing lisamiseks saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Viitab väljale Sõltuv, mis valemi väärtusest olenevalt võib olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Viitab väljale Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    . '<br/><br/>See konfiguratsioon kehtib ainult pärandi moodulite hüpikotsingu paigutuse puhul.',
        'savebtn'	=> 'Nupu <b>Salvesta ja juuruta</b> klõpsamine salvestab kõik muudatused ja muudab need aktiivseks',
        'Hidden' 	=> '<b>Peidetud</b> välju vormis Otsing ei kuvata.',
        'historyBtn'=> 'Klõpsake nuppu <b>Vaata ajalugu</b> eelnevalt salvestatud paigutuse vaatamiseks ja taastamiseks ajaloost.<br><br><b>Taasta</b> suvandis <b>Vaata ajalugu</b> taastab välja paigutuse eelnevalt salvestatud paigutustes. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'historyRestoreDefaultLayout'=> 'Klõpsake suvandit <b>Taasta vaikepaigutus</b> vaate algpaigutuse taastamiseks.<br><br><b>Taasta vaikepaigutus</b> taastab välja paigutuse ainult algpaigutuses. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'Default'	=> '<b>Vaikimisi</b> väljad kuvatakse vormis Otsing.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'Ala <b>Paigutus</b> sisaldab välju, mida kuvatakse praegu <b>Detailvaates</b>.<br/><br/><b>Tööriistakast</b> sisaldab suvandit <b>Prügikast</b> ning välju ja paigutuselemente, mida saab paigutusse lisada.<br><br>Muutke paigutust, pukseerides elemente ja välju suvandite <b>Tööriistakast</b> ja <b>Paigutus</b> vahel ning paigutuses endas.<br><br>Välja eemaldamiseks paigutusest lohistage väli kohta <b>Prügikast</b>. Väli on seejärel suvandis Tööriistakast paigutusse lisamiseks saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Viitab väljale Sõltuv, mis valemi väärtusest olenevalt võib olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Viitab väljale Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        'defaultquickcreate'=>'Ala <b>Paigutus</b> sisaldab välju, mida kuvatakse praegu vormis <b>Kiirgeneraator</b>.<br><br>Kiirgeneraatori vorm ilmub mooduli alampaneelides, kui klõpsatakse nuppu Loo.<br/><br/><b>Tööriistakast</b> sisaldab üksust <b>Prügikast</b> ning välja ja paigutuselemente, mida saab paigutusse lisada.<br><br>Muutke paigutust, pukseerides elemente ja välju suvandite <b>Tööriistakast</b> ja <b>Paigutus</b> vahel ning paigutuses endas.<br><br>Välja eemaldamiseks paigutusest lohistage väli üksusesse <b>Prügikast</b>. Väli on seejärel üksuses Tööriistakast paigutusse lisamiseks saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Tähistab välja Sõltuv, mis võib olenevalt valemi väärtusest olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Tähistab välja Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'Ala <b>Paigutus</b> sisaldab välju, mida kuvatakse praegu <b>Redigeerimisvaates</b>.<br/><br/><b>Tööriistakast</b> sisaldab üksust <b>Prügikast</b> ning välju ja paigutuselemente, mida saab paigutusse lisada.<br><br>Muutke paigutust, pukseerides elemente ja välju üksuste <b>Tööriistakast</b> ja <b>Paigutus</b> vahel ning paigutuses endas.<br><br>Välja eemaldamiseks paigutusest lohistage väli üksusesse <b>Prügikast</b>. Väli on seejärel suvandis Tööriistakast paigutusse lisamiseks saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Tähistab välja Sõltuv, mis võib olenevalt valemi väärtusest olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Tähistab välja Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'Ala <b>Paigutus</b> sisaldab välju, mida kuvatakse praegu <b>Kirje vaates</b>.<br/><br/><b>Tööriistakast</b> sisaldab suvandit <b>Prügikast</b> ning välju ja paigutuselemente, mida saab paigutusse lisada.<br><br>Muutke paigutust, pukseerides elemente ja välju suvandite <b>Tööriistakast</b> ja <b>Paigutus</b> vahel ning paigutuses endas.<br><br>Välja eemaldamiseks paigutusest lohistage väli kohta <b>Prügikast</b>. Väli on seejärel suvandis Tööriistakast paigutusse lisamiseks saadaval.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Viitab väljale Sõltuv, mis valemi väärtusest olenevalt võib olla nähtav või mitte.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Viitab väljale Arvutatud, mille väärtus arvutatakse valemi põhjal automaatselt.'
    ,
        'saveBtn'	=> 'Klõpsake nuppu <b>Salvesta</b> paigutuse puhul tehtud muudatuste salvestamiseks selle viimati salvestamisest alates.<br><br>Muudatusi ei kuvata moodulis seni, kuni salvestatud muudatused juurutate.',
        'historyBtn'=> 'Klõpsake nuppu <b>Vaata ajalugu</b> eelnevalt salvestatud paigutuse vaatamiseks ja taastamiseks ajaloost.<br><br><b>Taasta</b> suvandis <b>Vaata ajalugu</b> taastab välja paigutuse eelnevalt salvestatud paigutustes. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'historyRestoreDefaultLayout'=> 'Klõpsake suvandit <b>Taasta vaikepaigutus</b> vaate algpaigutuse taastamiseks.<br><br><b>Taasta vaikepaigutus</b> taastab välja paigutuse ainult algpaigutuses. Välja siltide muutmiseks klõpsake iga välja kõrval olevat ikooni Redigeeri.',
        'publishBtn'=> 'Klõpsake nuppu <b>Salvesta ja juuruta</b> paigutuse puhul tehtud muudatuste salvestamiseks selle viimati salvestamisest alates ja muudatuste moodulis aktiivseks muutmiseks.<br><br>Paigutus kuvatakse moodulis kohe.',
        'toolbox'	=> '<b>Tööriistakast</b> sisaldab suvandit <b>Prügikast</b>, paigutusse lisatavaid täiendavaid paigutuselemente ja saadaval väljade kogumit.<br/><br/>Tööriistakasti paigutuselemente ja välju saab paigutusse pukseerida ning neid saab pukseerida paigutusest Tööriistakasti.<br><br>Paigutuselemendid on <b>Paneelid</b> ja <b>Read</b>. Uue rea või paneeli lisamine paigutusse pakub paigutuses väljade puhul täiendavaid asukohti.<br/><br/>Kahe välja asukoha vahetamiseks pukseerige mis tahes väli Tööriistakasti või paigutus hõivatud välja asukohta.<br/><br/>Väli <b>Täitja</b> loob tühiku paigutusse, kuhu see paigutatakse.',
        'panels'	=> 'Ala <b>Paigutus</b> annab ülevaate sellest, kuidas paigutus moodulis kuvatakse, kui paigutuse osas tehtud muudatused juurutatakse.<br/><br/>Saate väljade, ridade ja paneelide asukohta muuta, pukseerides need soovitud kohta.<br/><br/>Eemaldage elemente, pukseerides need Tööriistakasti suvandisse <b>Prügikast</b> või lisage uusi elemente ja välju, lohistades need <b>Tööriistakastist</b> ja kukutades need paigutuses soovitud kohta.',
        'delete'	=> 'Mis tahes elemendi paigutusest eemaldamiseks pukseerige see siia',
        'property'	=> 'Redigeerige selle välja puhul kuvatavat <b>Silti</b>.<br><br><b>Laius</b> esitab laiuse väärtuse pikslites Sidecari moodulite puhul ja tabeli laiuse protsendina tagasiühilduvate moodulite puhul.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>Väljad</b>, mis on mooduli puhul saadaval, kuvatakse siin suvandi Välja nimi järgi.<br><br>Mooduli puhul loodud kohandatud väljad kuvatakse väljade kohal, mis on mooduli puhul vaikimisi saadaval.<br><br>Välja redigeerimiseks klõpsake nuppu <b>Välja nimi</b>.<br/><br/>Uue välja loomiseks klõpsake nuppu <b>Lisa väli</b>.',
        'mbDefault'=>'<b>Väljad</b>, mis on mooduli puhul saadaval, kuvatakse siin suvandi Välja nimi järgi.<br><br>Välja atribuutide konfigureerimiseks klõpsake suvandit Välja nimi.<br><br>Uue välja loomiseks klõpsake nuppu <b>Lisa väli</b>. Silti koos uue välja muude atribuutidega saab redigeerida pärast loomist, klõpsates suvandit Välja nimi.<br><br>Pärast mooduli juurutamist peetakse Moodulikoosturis loodud uusi välju Studio juurutatud moodulis standardväljadeks.',
        'addField'	=> 'Valige uue välja <b>Andmetüüp</b>. Teie valitav tüüp määratleb, milliseid märke saab välja puhul sisestada. Näiteks andmetüübi Täisarv puhul saab väljadele sisestada vaid täisarve.<br><br> Sisestage välja <b>Nimi</b>. Nimi peab olema tähtnumbriline ega tohi sisaldada tühikuid. Allkriipsud on lubatud.<br><br> <b>Kuva silt</b> on silt, mis kuvatakse väljade puhul mooduli paigutustes. <b>Süsteemi silti</b> kasutatakse koodis olevale väljale viitamiseks.<br><br> Välja puhul valitud andmetüübist olenevalt saab välja puhul seadistada mõne või kõik järgmised atribuudid:<br><br> <b>Abitekst</b> kuvatakse ajutiselt, kui kasutaja välja kohale hõljub ja seda saab kasutada kasutaja viipamiseks soovitud sisendi tüübi puhul.<br><br> <b>Kommentaari tekst</b> on näha ainult Studios ja/või Moodulikoosturis ja seda saab kasutada välja kirjeldamiseks administraatorite puhul.<br><br> <b>Vaikeväärtus</b> kuvatakse väljal. Kasutajad saavad sisestada väljale uue väärtuse või kasutada vaikeväärtust.<br><br> Valige märkeruut <b>Massuuendus</b> funktsiooni Massuuendus kasutamise võimaldamiseks välja puhul.<br><br>Suvandi <b>Maksimaalne suurus</b> väärtus määrab väljale sisestavate märkide maksimumarvu.<br><br> Valige märkeruut <b>Kohustuslik väli</b> välja muutmiseks kohustuslikuks. Välja sisaldava kirje salvestamise võimaldamiseks tuleb sisestada välja väärtus.<br><br> Valige märkeruut <b>Aruandev</b> välja kasutamise võimaldamiseks filtrite puhul ja andmete kuvamiseks aruannetes.<br><br> Valige märkeruut <b>Audit</b> välja osas tehtud muudatuste jälgimise võimaldamiseks muudatuste logis.<br><br>Valige suvand väljalt <b>Imporditav</b>, et lubada, keelata või muuta kohustuslikuks välja importimine Impordi viisardisse.<br><br>Valige suvand väljalt <b>Dubleeritud ühendamine</b> funktsioonide Duplikaatide mestimine ja Duplikaatide otsimine lubamiseks või keelamiseks.<br><br>Teatud andmetüüpide puhul on võimalik seadistada täiendavaid atribuute.',
        'editField' => 'Selle välja atribuute saab kohandada.<br><br>Klõpsake suvandit <b>Klooni</b> samade atribuutidega uue välja loomiseks.',
        'mbeditField' => 'Malli välja suvandit <b>Kuva silt</b> saab kohandada. Muid välja atribuute kohandada ei saa.<br><br>Klõpsake suvandit <b>Klooni</b> samade atribuutidega uue välja loomiseks.<br><br>Malli välja eemaldamiseks nii, et seda moodulis ei kuvataks, eemaldage väli asjakohasest suvandist <b>Paigutused</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Eksportige Studios tehtud kohandused, luues paketid, mille saab üles laadida muusse Sugari eksemplari <b>Moodulilaaduri</b> kaudu.<br><br> Esmalt sisestage <b>Paketi nimi</b>. Saate sisestada paketi puhul ka teabe <b>Autor</b> ja <b>Kirjeldus</b>.<br><br>Valige moodul(id), mis sisaldab/sisaldavad kohandusi, mida soovite eksportida. Teile valimiseks kuvatakse ainult kohandusi sisaldavad moodulid.<br><br>Seejärel klõpsake nuppu <b>Ekspordi</b> .zip-faili loomiseks kohandusi sisaldava paketi puhul.',
        'exportCustomBtn'=>'Klõpsake nuppu <b>Ekspordi</b> .zip-faili loomiseks paketi puhul, mis sisaldab kohandusi, mida soovite eksportida.',
        'name'=>'See on paketi <b>Nimi</b>. See nimi kuvatakse installimisel.',
        'author'=>'See on <b>Autor</b>, mis kuvatakse installimisel paketi loonud üksuse nimena. Autoriks võib olla kas üksikisik või ettevõte.',
        'description'=>'See on installimisel kuvatava paketi <b>Kirjeldus</b>.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Tere tulemast alasse <b>Arendaja tööriistad</b>. <br/><br/>Kasutage selle ala tööriistu standardsete a kohandatud moodulite ja väljade loomiseks ja haldamiseks.',
        'studioBtn'	=> 'Kasutage nuppu <b>Studio</b> juurutatud moodulite kohandamiseks.',
        'mbBtn'		=> 'Kasutage nuppu <b>Moodulikoostur</b> uute moodulite loomiseks.',
        'sugarPortalBtn' => 'Kasutage nuppu <b>Sugar Portali redaktor</b> Sugar Portali haldamiseks ja kohandamiseks.',
        'dropDownEditorBtn' => 'Kasutage nuppu <b>Ripploendi redaktor</b> rippväljade ripploendite haldamiseks ja kohandamiseks.',
        'appBtn' 	=> 'Rakenduse režiim on koht, kus saate kohandada programmi eri atribuute, näiteks seda, mitu TPS-aruannet kodulehel kuvatakse',
        'backBtn'	=> 'Naaske eelmisse etappi.',
        'studioHelp'=> 'Kasutage suvandit <b>Studio</b> määratlemaks, millist teavet ja kuidas moodulites kuvatakse.',
        'studioBCHelp' => ' viitab, et moodul on tagasiühilduv moodul',
        'moduleBtn'	=> 'Mooduli redigeerimiseks klõpsake.',
        'moduleHelp'=> 'Komponendid, mida saate mooduli puhul kohandada, kuvatakse siin.<br><br>Redigeeritava komponendi valimiseks klõpsake ikooni.',
        'fieldsBtn'	=> 'Looge ja kohandage <b>välju</b> teabe salvestamiseks moodulisse.',
        'labelsBtn' => 'Redigeerige <b>silte</b>, mis kuvatakse väljade ja muude pealkirjade puhul moodulis.'	,
        'relationshipsBtn' => 'Lisage mooduli puhul uus või olemasolev <b>Seos</b>.' ,
        'layoutsBtn'=> 'Kohandage moodulit <b>Paigutused</b>. Paigutused on välju sisaldava mooduli eri vaated.<br><br>Saate määrata, millised väljad kuvatakse ja kuidas need igas paigutused korraldatakse.',
        'subpanelBtn'=> 'Märake, millised väljad mooduli suvandis <b>Alampaneelid</b> kuvatakse.',
        'portalBtn' =>'Kohandage moodulit <b>Paigutused</b>, mis kuvatakse <b>Sugar Portalis</b>.',
        'layoutsHelp'=> 'Moodul <b>Paigutused</b>, mida saab kohandada, kuvatakse siin.<br><br>Paigutused kuvavad väljad ja välja andmed.<br><br>Redigeeritava paigutuse valimiseks klõpsake ikooni.',
        'subpanelHelp'=> 'Mooduli <b>Alampaneelid</b>, mida saab kohandada, kuvatakse siin.<br><br>Redigeeritava mooduli valimiseks klõpsake ikooni.',
        'newPackage'=>'Uue paketi loomiseks klõpsake suvandit <b>Uus pakett</b>.',
        'exportBtn' => 'Klõpsake nuppu <b>Ekspordi kohandused</b>, et luua ja alla laadida pakett, mis sisaldab Studios konkreetsete moodulite puhul tehtud kohandusi.',
        'mbHelp'    => 'Kasutage <b>Moodulikoosturit</b> kohandatud mooduleid sisaldavate pakettide loomiseks standardsete või kohandatud objektide põhjal.',
        'viewBtnEditView' => 'Kohandage mooduli <b>Redigeerimisvaade</b> paigutust.<br><br>Redigeerimisvaade on vorm, mis sisaldab sisendvälju kasutaja sisestatud andmete hõivamiseks.',
        'viewBtnDetailView' => 'Kohandage mooduli <b>Detailvaade</b> paigutust.<br><br>Detailvaade kuvab kasutaja sisestatud välja andmed.',
        'viewBtnDashlet' => 'Kohandage moodulit <b>Sugari dashlet</b>, sh Sugari dashleti loendivaadet ja otsingut.<br><br>Sugari dashlet on saadaval lisamiseks lehtedele moodulis Avaleht.',
        'viewBtnListView' => 'Kohandage mooduli <b>Loendivaade</b> paigutust.<br><br>Otsingutulemused kuvatakse loendivaates.',
        'searchBtn' => 'Kohandage mooduli <b>Otsing</b> paigutusi.<br><br>Määrake, milliseid välju saab kasutada loendivaates kuvatavate kirjete filtrimiseks.',
        'viewBtnQuickCreate' =>  'Kohandage mooduli <b>Kiirgeneraator</b> paigutust.<br><br>Vorm Kiirgeneraator kuvatakse alampaneelidel ja moodulis E-kirjad.',

        'searchHelp'=> 'Vormid <b>Otsing</b>, mida saab kohandada, kuvatakse siin.<br><br>Vormid Otsing sisaldavad välju kirjete filtrimiseks.<br><br>Redigeeritava otsingupaigutuse valimiseks klõpsake ikooni.',
        'dashletHelp' =>'<b>Sugari dashleti</b> paigutused, mida saab kohandada, kuvatakse siin.<br><br>Sugari dashlet on saadaval lisamiseks lehtedele moodulis Avaleht.',
        'DashletListViewBtn' =>'<b>Sugari dashleti loendivaade</b> kuvab kirjed Sugari dashleti otsingufiltrite põhjal.',
        'DashletSearchViewBtn' =>'<b>Sugari dashleti otsingu</b> filtrib kirjeid Sugari dashleti loendivaate puhul.',
        'popupHelp' =>'<b>Hüpiku</b> paigutused, mida saab kohandada, kuvatakse siin.<br>',
        'PopupListViewBtn' => '<b>Hüpik loendivaate</b> paigutust kasutatakse kirjete loendi vaatamiseks ühe või enama kirje valimisel praeguse kirjega sidumiseks.',
        'PopupSearchViewBtn' => '<b>Hüpikotsingu</b> paigutus võimaldab kasutajatel otsida kirjeid, mida praeguse kirjega siduda ja kuvatakse hüpik loendivaate kohal samas aknas. Pärandi moodulid kasutavad seda paigutust hüpikotsinguks, samas kui Sidecari moodulid kasutavad otsingupaigutuste konfiguratsiooni.',
        'BasicSearchBtn' => 'Kohandage <b>Põhiotsingu</b> vormi, mis kuvatakse mooduli puhul ala Otsing vahekaardil Põhiotsing.',
        'AdvancedSearchBtn' => 'Kohandage <b>Täpsema otsingu</b> vormi, mis kuvatakse mooduli puhul ala Otsing vahekaardil Põhiotsing.',
        'portalHelp' => 'Hallake ja kohandage <b>Sugar Portalit</b>.',
        'SPUploadCSS' => 'Laadige Sugar Portali puhul üles <b>Laadileht</b>.',
        'SPSync' => '<b>Sünkroonige</b> kohandused Sugar Portali eksemplari.',
        'Layouts' => 'Kohandage Sugar Portali moodulite <b>paigutused</b>.',
        'portalLayoutHelp' => 'Sugar Portali moodulid kuvatakse selles alas.<br><br>Valige moodul <b>paigutuste</b> redigeerimiseks.',
        'relationshipsHelp' => 'Kõik <b>Seosed</b>, mis eksisteerivad mooduli ja muude juurutatud moodulite vahel, kuvatakse siin.<br><br>Seos <b>Nimi</b> on süsteemi loodud seose nimi.<br><br><b>Põhimoodul</b> on seoseid omav moodul. Näiteks kõik seoste atribuudid, mille puhul moodul Kontod on põhimoodul, salvestatakse andmebaasitabelites Kontod.<br><br><b>Tüüp</b> on põhimooduli ja <b>seotud mooduli</b> vahelise seose tüüp.<br><br>Veeru järgi sortimiseks klõpsake veeru pealkirja.<br><br>Seosega seotud atribuutide vaatamiseks klõpsake rida seosetabelis.<br><br>Klõpsake suvandit <b>Lisa seos</b> uue seose loomiseks.<br><br>Seoseid saab luua mis tahes juurutatud moodulite vahel.',
        'relationshipHelp'=>'<b>Seoseid</b> saab luua mooduli ja muu juurutatud mooduli vahele.<br><br> Seoseid väljendatakse visuaalselt alampaneelide kaudu ja need seovad väljad mooduli kirjetes.<br><br>Valige mooduli puhul üks järgmistest seose <b>tüüpidest</b>:<br><br> <b>Üks ühega</b> – mõlemad mooduli kirjed sisaldavad seotud välju.<br><br> <b>Üks mitmega</b> – Põhimooduli kirje sisaldab alampaneeli ja Seotud mooduli kirje sisaldab seotud välja.<br><br> <b>Mitu mitmega</b> – mõlema mooduli kirjed kuvavad alampaneelid.<br><br> Valige seose puhul <b>Seotud moodul</b>. <br><br>Kui seose tüüp hõlmab alampaneele, valige asjakohaste moodulite puhul alampaneeli vaade.<br><br> Seose loomiseks klõpsake nuppu <b>Salvesta</b>.',
        'convertLeadHelp' => "Siin saate lisada mooduleid teisendatud paigutuse ekraanile ja muuta olemasolevate sätteid.<br/><br/>
<b>Järjestus:</b><br/>
Kontaktid, Kontod ja Müügivõimalused peavad oma järjestuse säilitama. Saate mis tahes muu mooduli järjestust muuta, lohistades selle rea tabelisse.<br/><br/>
<b>Sõltuvus:</b><br/>
Müügivõimaluste kaasamisel peavad Kontod olema kas kohustuslikud või tuleb need teisendatud paigutusest eemaldada.<br/><br/>
<b>Moodul:</b> mooduli nimi.<br/><br/>
<b>Kohustuslik:</b> kohustuslikud moodulid tuleb luua või valida enne, kui müügivihje saab teisendada.<br/><br/>
<b>Kopeeri andmed:</b> märgituna kopeeritakse mügivihje väljad äsja loodud kirjete samanimelistele väljadele.<br/><br/>
<b>Kustuta:</b> eemaldage see moodul teisendatud paigutusest.<br/><br/>        ",
        'editDropDownBtn' => 'Redigeeri globaalset ripploendit',
        'addDropDownBtn' => 'Lisa uus globaalne ripploend',
    ),
    'fieldsHelp'=>array(
        'default'=>'Mooduli <b>Väljad</b> loetletakse siin välja nime järgi.<br><br>Mooduli mall hõlmab eelmääratletud väljade kogumit.<br><br>Uue välja loomiseks klõpsake suvandit <b>Lisa väli</b>.<br><br>Välja redigeerimiseks klõpsake suvandit <b>Välja nimi</b>.<br/><br/>Pärast mooduli juurutamist peetakse Moodulikoosturis loodud uusi välju koos malli väljadega Studios standardväljadeks.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'Kõik <b>Seosed</b>, mis on loodud mooduli ja muude moodulite vahel, kuvatakse siin.<br><br>Seos <b>Nimi</b> on süsteemi loodud seose nimi.<br><br><b>Põhimoodul</b> on seoseid omav moodul. Seose atribuudid salvestatakse põhimoodulisse kuuluvatesse andmebaasitabelitesse.<br><br><b>Tüüp</b> on põhimooduli ja <b>seotud mooduli</b> vahelise seose tüüp.<br><br>Veeru järgi sortimiseks klõpsake veeru pealkirja.<br><br>Seosega seotud atribuutide vaatamiseks ja redigeerimiseks klõpsake rida seosetabelis.<br><br>Klõpsake suvandit <b>Lisa seos</b> uue seose loomiseks.',
        'addrelbtn'=>'hiirega spikri kohale lisatud seose puhul.',
        'addRelationship'=>'<b>Seoseid</b> saab luua mooduli ja muu kohandatud või juurutatud mooduli vahele.<br><br> Seoseid väljendatakse visuaalselt alampaneelide kaudu ja need seovad väljad mooduli kirjetes.<br><br>Valige mooduli puhul üks järgmistest seose <b>tüüpidest</b>:<br><br> <b>Üks ühega</b> – mõlemad mooduli kirjed sisaldavad seotud välju.<br><br> <b>Üks mitmega</b> – Põhimooduli kirje sisaldab alampaneeli ja Seotud mooduli kirje sisaldab seotud välja.<br><br> <b>Mitu mitmega</b> – mõlema mooduli kirjed kuvavad alampaneelid.<br><br> Valige seose puhul <b>Seotud moodul</b>. <br><br>Kui seose tüüp hõlmab alampaneele, valige asjakohaste moodulite puhul alampaneeli vaade.<br><br> Seose loomiseks klõpsake nuppu <b>Salvesta</b>.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Silte</b> saab mooduli väljade ja muude pealkirjade puhul muuta.<br><br>Redigeerige silti, klõpsates väljal, sisestades uue sildi ja klõpsates nuppu <b>Salvesta</b>.<br><br>Kui rakendusse on installitud keelepakette, saate siltide puhul kasutamiseks valida suvandi <b>Keel</b>.',
        'saveBtn'=>'Kõigi muudatuste salvestamiseks klõpsake nuppu <b>Salvesta</b>.',
        'publishBtn'=>'Klõpsake nuppu <b>Salvesta ja juuruta</b> kõigi muudatuste salvestamiseks ja nende aktiveerimiseks.',
    ),
    'portalSync'=>array(
        'default' => 'Sisestage värskendatava portaali eksemplari <b>Sugari Portali URL</b> ja klõpsake nuppu <b>Mine</b>.<br><br>Seejärel sisestage kehtiv Sugari kasutajanimi ja parool ning klõpsake suvandit <b>Alusta sünkroonimist</b>.<br><br>Sugar Portali <b>paigutuste</b> osas tehtud kohandused koos <b>Laadilehega</b> selle üleslaadimisel teisaldatakse määratud portaali eksemplari.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Saate kohandada Sugar Portali ilmet laadilehega.<br><br>Valige üleslaaditav <b>Laadileht</b>.<br><br>Laadilehte rakendatakse Sugar Portali puhul järgmisel sünkroonimisel.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Projekti alustamiseks klõpsake suvandit <b>Uus pakett</b> teie kohandatud mooduli(te) majutamiseks uue paketi loomiseks. <br/><br/>Iga pakett võib sisaldada ühte või enamat moodulit.<br/><br/>Näiteks võite soovida luua paketi, mis sisaldaks ühte kohandatud moodulit, mis on seotud standardse mooduliga Kontod. Või võite soovida luua paketi, mis sisaldaks mitut uut moodulit, mis toimiks koos projektina ja mis oleks seotud üksteisega ja muude juba rakenduses olevate moodulitega.',
            'somepackages'=>'<b>Pakett</b> toimib kohandatud moodulite puhul ümbrisena, millest kõik on ühe projekti osa. Pakett võib sisaldada ühte või enamat kohandatud <b>moodulit</b>, mis võivad olla seotud üksteisega või muude rakenduse moodulitega.<br/><br/>Pärast paketi loomist teie projekti puhul saate luua paketi mooduleid kohe või naasta Moodulikoosturisse projekti lõpetamiseks hiljem.<br><br>Kui projekt on valmis, saate rakenduses kohandatud moodulite installimiseks paketi <b>juurutada</b>.',
            'afterSave'=>'Teie uus pakett peaks sisaldama vähemalt ühte moodulit. Saate paketi puhul luua ühe või enam moodulit.<br/><br/>Selle paketi puhul kohandatud mooduli loomiseks klõpsake suvandit <b>Uus moodul</b>.<br/><br/> Pärast vähemalt ühe mooduli loomist saate paketi avaldada või juurutada, et muuta see saadavaks teie eksemplari ja/või muude kasutajate eksemplaride puhul.<br/><br/> Paketi juurutamiseks teie Sugari eksemplaris ühe etapina klõpsake nuppu <b>Juuruta</b>.<br><br>Klõpsake nuppu <b>Avalda</b> paketi salvestamiseks .zip-failina. Kui .zip-fail on teie süsteemi salvestatud, kasutage suvandit <b>Moodulilaadur</b> paketi üleslaadimiseks ja installimiseks teie Sugari eksemplari. <br/><br/>Saate faili levitada muudele kasutajatele nende enda Sugari eksemplaridesse üleslaadimiseks ja installimiseks.',
            'create'=>'<b>Pakett</b> toimib kohandatud moodulite puhul ümbrisena, millest kõik on ühe projekti osa. Pakett võib sisaldada ühte või enamat kohandatud <b>moodulit</b>, mis võivad olla seotud üksteisega või muude rakenduse moodulitega.<br/><br/>Pärast paketi loomist teie projekti puhul saate luua paketi mooduleid kohe või naasta Moodulikoosturisse projekti lõpetamiseks hiljem.',
            ),
    'main'=>array(
        'welcome'=>'Kasutage <b>Arendaja tööriistu</b> standardsete ja kohandatud moodulite ja väljade loomiseks ja haldamiseks. <br/><br/>Rakenduse moodulite haldamiseks klõpsake suvandit <b>Studio</b>. <br/><br/>Kohandatud moodulite loomiseks klõpsake suvandit <b>Moodulikoostur</b>.',
        'studioWelcome'=>'Kõik praegu installitud moodulid, sealhulgas standardsed ja mooduli laaditud objektid on Studios kohandatavad.'
    ),
    'module'=>array(
        'somemodules'=>"Kuna praegune pakett sisaldab vähemalt ühte moodulit, saate <b>juurutada</b> paketi moodulid teie Sugari eksemplaris või <b>avaldada</b> praegusesse Sugari eksemplari või muuse eksemplari installitava paketi, kasutades suvandit <b>Moodulilaadur</b>.<br/><br/>Paketi installimiseks otse teie Sugari rakendusest klõpsake nuppu <b>Juuruta</b>.<br><br>.zip-faili loomiseks paketi puhul, mille saab laadida ja installida praegusesse Sugari eksemplari, kasutades suvandit <b>Moodulilaadur</b>, klõpsake nuppu <b>Avalda</b>.<br/><br/> Saate mooduleid selle paketi puhul luua etappidena ja avaldada või juurutada, kui olete selleks valmis. <br/><br/>Pärast paketi avaldamist või juurutamist saate paketi atribuute muuta ja mooduleid täiendavalt kohandada. Seejärel avaldage või juurutage pakett muudatuste rakendamiseks uuesti." ,
        'editView'=> 'Siin saate redigeerida olemasolevaid välju. Saate eemaldada mis tahes olemasolevad väljad või lisada saadaval välju vasakul paneelil.',
        'create'=>'Loodava mooduli tüübi <b>Tüüp</b> valimisel pidage meeles väljade tüübid, mida moodulisse sooviksite. <br/><br/>Iga mooduli mall sisaldab pealkirja kirjeldatud mooduli tüübiga seotud väljade kogumit.<br/><br/><b>Põhiline</b> – esitab standardmoodulites kuvatavad põhiväljad, nagu Nimi, Määratud kasutajale, Meeskond, Loomiskuupäev ja Kirjeldus.<br/><br/> <b>Ettevõte</b> – pakub organisatsioonile omaseid välju, nagu Ettevõtte nimi, Valdkond ja Arve aadress. Kasutage seda malli moodulite loomiseks, mis sarnanevad standardse mooduliga Kontod.<br/><br/> <b>Isik</b> – pakub üksikisikuomaseid välju, nagu Tervitus, Ametinimetus, Nimi, Aadress ja Telefoninumber. Kasutage seda malli moodulite loomiseks, mis sarnanevad standardsete moodulitega Kontaktid ja Müügivihjed.<br/><br/><b>Probleem</b> – pakub juhtumi- ja veaomaseid välju, nagu Arv, Olek, Tähtsus ja Kirjeldus. Kasutage seda malli moodulite loomiseks, mis sarnanevad standardsete moodulitega Juhtumid ja Vigade otsija.<br/><br/>Märkus: pärast mooduli loomist saate malli pakutavate väljade silte redigeerida ja luua mooduli paigutustesse lisatavaid kohandatud välju.',
        'afterSave'=>'Kohandage moodulit oma vajaduste järgi, redigeerides ja luues välju, luues seoseid muude moodulitega ja korraldades välju paigutustes.<br/><br/>Malli väljade vaatamiseks ja kohandatud väljade haldamiseks moodulis klõpsake suvandit <b>Vaata välju</b>.<br/><br/>Mooduli ja muude moodulite vaheliste seoste loomiseks ja haldamiseks olenemata sellest, kas moodulid on juba rakenduses või muudes sama paketi kohandatud moodulites, klõpsake suvandit <b>Vaata seoseid</b>.<br/><br/>Mooduli paigutuste redigeerimiseks klõpsake suvandit <b>Vaata paigutusi</b>. Saate Detailvaadet, Redigeerimisvaadet ja Loendivaadet mooduli puhul muuta samamoodi, nagu juba Studio rakendustes olevate moodulite puhul.<br/><br/> Praeguse mooduliga samade atribuutidega mooduli loomiseks klõpsake nuppu <b>Dubleeri</b>. Saate uut moodulit täiendavalt kohandada.',
        'viewfields'=>'Moodulis olevaid välju saab kohandada oma vajaduste järgi.<br/><br/>Te ei saa standardseid välju kustutada, kuid saate need lehtede Paigutused asjakohastest paigutustest eemaldada. <br/><br/>Saate olemasolevate väljadega sarnaste atribuutidega uusi välju kiiresti luua, klõpsates nuppu <b>Klooni</b> vormis <b>Atribuudid</b>. Sisestage mis tahes uued atribuudid ja seejärel klõpsake nuppu <b>Salvesta</b>.<br/><br/>Standardsete ja kohandatud väljade kõik atribuudid on soovitatav määärata enne kohandatud moodulit sisaldava paketi avaldamist ja installimist.',
        'viewrelationships'=>'Saate luua mitu mitmega seoseid praeguse mooduli ja muude paketi moodulite ja/või praeguse mooduli ja rakendusse juba installitud moodulite vahel.<br><br> Üks mitmega ja üks ühega seoste loomiseks looge moodulite puhul väljad <b>Seosta</b> ja <b>Seosta paindlikult</b>.',
        'viewlayouts'=>'Saate juhtida, millised väljad on andmete hõivamiseks suvandis <b>Redigeerimisvaade</b> saadaval. Ühtlasi saate juhtida, milliseid andmeid <b>Detailvaates</b> kuvatakse. Vaated ei pea ühtima. <br/><br/>Vorm Kiirloomine kuvatakse suvandi <b>Loo</b> klõpsamisel mooduli alampaneelil. Vaikimisi on vormi <b>Kiirloomine</b> paigutus sama vaikimisi <b>Redigeerimisvaate</b> paigutusega. Saate vormi Kiirloomine kohandada nii, et see sisaldaks vähem ja/või eri välju kui Redigeerimisvaate paigutus. <br><br>Saate määrata mooduli turvalisuse, kasutades paigutuse kohandamist koos <b>Rollihaldusega</b>.<br><br>',
        'existingModule' =>'Pärast selle mooduli loomist ja kohandamist saate luua täiendavaid mooduleid või naasta paketi <b>Avaldamise</b> või <b>Juurutamise</b> juurde.<br><br>Lisamoodulite loomiseks klõpsake nuppu <b>Dubleeri</b> praeguse mooduliga samade atribuutidega mooduli loomiseks või navigeerige tagasi paketti ja klõpsake suvandit <b>Uus moodul</b>.<br><br> Kui olete valmis seda moodulit sisaldavat paketti <b>avaldama</b> või <b>juurutama</b>, navigeerige nende funktsioonide täitmiseks tagasi paketti. Saate avaldada ja juurutada pakette, mis sisaldavad vähemalt ühte moodulit.',
        'labels'=> 'Nii standardsete kui ka kohandatud väljade silte saab muuta.  Välja siltide muutmine ei mõjuta väljadele salvestatud andmeid.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Vasakul kuvatakse kolm veergu. Veerg Vaikimisi sisaldab välju, mis kuvatakse loendivaates vaikimisi; veerg Saadaval sisaldab välju, mida kasutaja saab valida kohandatud loendivaate loomiseks ja veerg Peidetud sisaldab välju, mis on teile kui administraatorile saadaval kas veergu Vaikimisi või Saadaval lisamiseks kasutajate jaoks, kuid mis on praegu keelatud.',
        'savebtn'	=> 'Nupu <b>Salvesta</b> klõpsamine salvestab kõik muudatused ja muudab need aktiivseks.',
        'Hidden' 	=> 'Peidetud väljad on väljad, mis pole praegu kasutajatele loendivaadetes kasutamiseks saadaval.',
        'Available' => 'Saadaolevad väljad on väljad, mida vaikimisi ei kuvata, kuid mida kasutajad saavad lubada.',
        'Default'	=> 'Vaikimisi väljad kuvatakse kasutajatele, kes pole kohandatud loendivaate sätteid loonud.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Vasakul kuvatakse kaks veergu. Veerg Vaikimisi sisaldab välju, mis kuvatakse otsinguvaates ja veerg Peidetud sisaldab välju, mis on teile kui administraatorile saadaval vaatesse lisamiseks.',
        'savebtn'	=> 'Nupu <b>Salvesta ja juuruta</b> klõpsamine salvestab kõik muudatused ja muudab need aktiivseks.',
        'Hidden' 	=> 'Peidetud väljad on väljad, mida otsinguvaates ei kuvata.',
        'Default'	=> 'Vaikimisi väljad kuvatakse otsinguvaates.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Vasakul kuvatakse kaks veergu. Parempoolne veerg sildiga Praegune paigutus või Paigutuse eelvaade on koht, kus saate mooduli paigutust muuta. Vasakpoolne veerg sildiga Tööriistakast sisaldab kasulikke elemente ja tööriistu, mida kasutada paigutuse redigeerimisel. <br/><br/>Kui paigutuse ala pealkiri on Praegune paigutus, töötate paigutuse koopiaga, mida moodul praegu kuvamiseks kasutab.<br/><br/>Kui selle pealkiri on Paigutuse eelvaade, töötate koopiaga, mis loodi varem nupu Salvesta klõpsamisel ja mille versioon võib selles moodulis kasutajatele nähtavast versioonist juba muutunud olla.',
        'saveBtn'	=> 'Selle nupu klõpsamine salvestab paigutuse nii, et saate muudatused säilitada. Sellesse moodulisse naasmisel alustate sellest muudetud paigutusest. Mooduli kasutajad teie paigutust siiski ei näe, kuni klõpsate nuppu Salvesta ja avalda.',
        'publishBtn'=> 'Klõpsake seda nuppu paigutuse juurutamiseks. See tähendab, et selle mooduli kasutajad näevad seda paigutust kohe.',
        'toolbox'	=> 'Tööriistakast sisaldab mitmesuguseid kasulikke funktsioone paigutuste redigeerimiseks, sh prügikasti ala, täiendavate elementide ja saadaolevate väljade kogumi. Neid võib paigutusse pukseerida.',
        'panels'	=> 'Seeala näitab, kuidas selle mooduli kasutajad näevad teie paigutust juurutamisel.<br/><br/>Saate elemente, nagu välju, ridu ja paneele ümber paigutada neid pukseerides, kustutada elemente neid tööriistakasti prügi alale pukseerides või lisada uusi elemente, lohistades need tööriistakastist ja kukutades need paigutuses soovitud kohta.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Vasakul kuvatakse kaks veergu. Parempoolne veerg sildiga Praegune paigutus või Paigutuse eelvaade on koht, kus saate mooduli paigutust muuta. Vasakpoolne veerg sildiga Tööriistakast sisaldab kasulikke elemente ja tööriistu, mida kasutada paigutuse redigeerimisel. <br/><br/>Kui paigutuse ala pealkiri on Praegune paigutus, töötate paigutuse koopiaga, mida moodul praegu kuvamiseks kasutab.<br/><br/>Kui selle pealkiri on Paigutuse eelvaade, töötate koopiaga, mis loodi varem nupu Salvesta klõpsamisel ja mille versioon võib selles moodulis kasutajatele nähtavast versioonist juba muutunud olla.',
        'dropdownaddbtn'=> 'Selle nupu klõpsamine lisab ripploendisse uue üksuse.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Studio selles eksemplaris tehtud kohandusi saab pakkida ja juurutada muus eksemplaris. <br><br>Sisestage <b>Paketi nimi</b>. Saate paketi puhul sisestada teabe <b>Autor</b> ja <b>Kirjeldus</b>.<br><br>Valige moodul(id), mis sisaldab/sisaldavad eksporditavaid kohandusi. (Teile valimiseks kuvatakse ainult kohandusi sisaldavad moodulid.)<br><br>Klõpsake nuppu <b>Ekspordi</b> .zip-faili loomiseks kohandusi sisaldava paketi puhul. Zip-faili saab üles laadida muuse eksemplari suvandi <b>Moodulilaadur</b> kaudu.',
        'exportCustomBtn'=>'Klõpsake nuppu <b>Ekspordi</b> .zip-faili loomiseks paketi puhul, mis sisaldab kohandusi, mida soovite eksportida.
',
        'name'=>'Paketi <b>Nimi</b> kuvatakse Moodulilaaduris pärast paketi Studiosse installimiseks üleslaadimist.',
        'author'=>'<b>Autor</b> on paketi loonud üksuse nimi. Autoriks võib olla kas üksikisik või ettevõte.<br><br>Autor kuvatakse Moodulilaaduris pärast paketi Studiosse installimiseks üleslaadimist.
',
        'description'=>'Paketi <b>Kirjeldus</b> kuvatakse Moodulilaaduris pärast paketi Studiosse installimiseks üleslaadimist.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Tere tulemast alasse <b>Arendaja tööriistad</b1> area. <br/><br/>Kasutage selle ala tööriistu standardsete a kohandatud moodulite ja väljade loomiseks ja haldamiseks.',
        'studioBtn'	=> 'Kasutage suvandit <b>Studio</b> installitud moodulite kohandamiseks, muutes välja korraldust, valides, millised väljad on saadaval ning luues kohandatud andmeväljad.',
        'mbBtn'		=> 'Kasutage nuppu <b>Moodulikoostur</b> uute moodulite loomiseks.',
        'appBtn' 	=> 'Kasutage Rakenduse režiimi programmi eri atribuutide kohandamiseks, näiteks selle, mitu TPS-aruannet kodulehel kuvatakse',
        'backBtn'	=> 'Naaske eelmisse etappi.',
        'studioHelp'=> 'Kasutage nuppu <b>Studio</b> installitud moodulite kohandamiseks.',
        'moduleBtn'	=> 'Mooduli redigeerimiseks klõpsake.',
        'moduleHelp'=> 'Valige mooduli komponent, mida soovite redigeerida',
        'fieldsBtn'	=> 'Redigeerige, millist teavet moodulisse salvestada, juhtides mooduli <b>välju</b>.<br/><br/>Saate kohandatud välju redigeerida ja luua siin.',
        'layoutsBtn'=> 'Kohandage redigeerimis-, detail-, loendi- ja otsinguvaadete <b>paigutusi</b>.',
        'subpanelBtn'=> 'Redigeerige, millist teavet selle mooduli alampaneelidel kuvatakse.',
        'layoutsHelp'=> 'Valige <b>redigeeritav paigutus</b>.<br/<br/>Paigutuse muutmiseks, mis sisaldab andmevälju andmete sisestamiseks klõpsake suvandit <b>Redigeerimisvaade</b>.<br/><br/>Redigeerimisvaate väljadele sisestatud andmeid kuvava paigutuse muutmiseks klõpsake suvandit <b>Detailvaade</b>.<br/><br/>Vaikeloendis kuvatavate veergude muutmiseks klõpsake suvandit <b>Loendivaade</b>.<br/><br/>Põhi- ja laiendatud otsingu muutmiseks paigutustest klõpsake suvandit <b>Otsi</b>.',
        'subpanelHelp'=> 'Valige redigeeritav <b>Alampaneel</b>.',
        'searchHelp' => 'Valige redigeeritav <b>Otsingu</b> paigutus.',
        'labelsBtn'	=> 'Redigeerige <b>Silte</b>, et kuvada selles moodulis väärtusi.',
        'newPackage'=>'Uue paketi loomiseks klõpsake suvandit <b>Uus pakett</b>.',
        'mbHelp'    => '<b>Tere tulemast Moodulikoosturisse.</b><br/><br/>Kasutage <b>Moodulikoosturit</b> kohandatud mooduleid sisaldavate pakettide loomiseks standardsete või kohandatud objektide põhjal. <br/><br/>Alustamiseks klõpsake uue paketi loomiseks suvandit <b>Uus pakett</b> või valige redigeeritav pakett.<br/><br/> <b>Pakett</b> toimib kohandatud moodulite puhul ümbrisena, millest kõik on ühe projekti osa. Pakett võib sisaldada ühte või enamat kohandatud moodulit, mis võivad olla seotud üksteisega või muude rakenduse moodulitega. <br/><br/>Näited: võite soovida luua paketi, mis sisaldaks ühte kohandatud moodulit, mis on seotud standardse mooduliga Kontod. Või võite soovida luua paketi, mis sisaldaks mitut uut moodulit, mis toimiks koos projektina ja mis oleks seotud üksteisega ja rakenduses olevate moodulitega.',
        'exportBtn' => 'Klõpsake nuppu <b>Ekspordi kohandused</b>, et luua pakett, mis sisaldab Studios konkreetsete moodulite puhul tehtud kohandusi.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Ripploendi redaktor',

//ASSISTANT
'LBL_AS_SHOW' => 'Näita assistenti tulevikus.',
'LBL_AS_IGNORE' => 'Ignoreeri assistenti tulevikus.',
'LBL_AS_SAYS' => 'Assistent ütleb:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Moodulikoostur',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Ripploendi redaktor',
'LBL_EDIT_DROPDOWN'=>'Redigeeri ripploendit',
'LBL_DEVELOPER_TOOLS' => 'Arendaja tööriistad',
'LBL_SUGARPORTAL' => 'Sugar Portali redaktor',
'LBL_SYNCPORTAL' => 'Sünkroonimise portaal',
'LBL_PACKAGE_LIST' => 'Pakettide loend',
'LBL_HOME' => 'Avaleht',
'LBL_NONE'=>'-Puudub-',
'LBL_DEPLOYE_COMPLETE'=>'Juurutamine lõpetatud',
'LBL_DEPLOY_FAILED'   =>'Tõrge juurutusprotsessis, teie pakett ei pruugi olla õigesti installitud',
'LBL_ADD_FIELDS'=>'Lisa kohandatud väljad',
'LBL_AVAILABLE_SUBPANELS'=>'Saadaolevad alampaneelid',
'LBL_ADVANCED'=>'Laiendatud',
'LBL_ADVANCED_SEARCH'=>'Laiendatud otsing',
'LBL_BASIC'=>'Põhiline',
'LBL_BASIC_SEARCH'=>'Põhiotsing',
'LBL_CURRENT_LAYOUT'=>'Paigutus',
'LBL_CURRENCY' => 'Valuuta',
'LBL_CUSTOM' => 'Kohanda',
'LBL_DASHLET'=>'Sugari dashlet',
'LBL_DASHLETLISTVIEW'=>'Sugari dashleti loendivaade',
'LBL_DASHLETSEARCH'=>'Sugari dashleti otsing',
'LBL_POPUP'=>'Hüpikakna vaade',
'LBL_POPUPLIST'=>'Hüpikakna loendivaade',
'LBL_POPUPLISTVIEW'=>'Hüpikakna loendivaade',
'LBL_POPUPSEARCH'=>'Hüpikakna otsing',
'LBL_DASHLETSEARCHVIEW'=>'Sugari dashleti otsing',
'LBL_DISPLAY_HTML'=>'Kuva HTML-kood',
'LBL_DETAILVIEW'=>'Detailvaade',
'LBL_DROP_HERE' => '[Aseta siia]',
'LBL_EDIT'=>'Redigeeri',
'LBL_EDIT_LAYOUT'=>'Redigeeri paigutust',
'LBL_EDIT_ROWS'=>'Redigeeri ridu',
'LBL_EDIT_COLUMNS'=>'Redigeeri veerge',
'LBL_EDIT_LABELS'=>'Redigeeri silte',
'LBL_EDIT_PORTAL'=>'Redigeeri portaali',
'LBL_EDIT_FIELDS'=>'Redigeeri välju',
'LBL_EDITVIEW'=>'Redigeerimisvaade',
'LBL_FILTER_SEARCH' => "Otsing",
'LBL_FILLER'=>'(täitja)',
'LBL_FIELDS'=>'Väljad',
'LBL_FAILED_TO_SAVE' => 'Salvestamine ebaõnnestus',
'LBL_FAILED_PUBLISHED' => 'Avaldamine ebaõnnestus',
'LBL_HOMEPAGE_PREFIX' => 'Minu',
'LBL_LAYOUT_PREVIEW'=>'Paigutuse eelvaade',
'LBL_LAYOUTS'=>'Paigutused',
'LBL_LISTVIEW'=>'Loendivaade',
'LBL_RECORDVIEW'=>'Kirjevaade',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Uus pakett',
'LBL_NEW_PANEL'=>'Uus paneel',
'LBL_NEW_ROW'=>'Uus rida',
'LBL_PACKAGE_DELETED'=>'Pakett on kustutatud',
'LBL_PUBLISHING' => 'Avaldamine ...',
'LBL_PUBLISHED' => 'Avaldatud',
'LBL_SELECT_FILE'=> 'Vali fail',
'LBL_SAVE_LAYOUT'=> 'Salvesta paigutus',
'LBL_SELECT_A_SUBPANEL' => 'Vali alampaneel',
'LBL_SELECT_SUBPANEL' => 'Vali alampaneel',
'LBL_SUBPANELS' => 'Alampaneelid',
'LBL_SUBPANEL' => 'Alampaneel',
'LBL_SUBPANEL_TITLE' => 'Pealkiri:',
'LBL_SEARCH_FORMS' => 'Otsi',
'LBL_STAGING_AREA' => 'Ettevalmistusala (pukseerige üksused siia)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugari väljad (klõpsake üksusi nende lisamiseks ettevalmistusalale)',
'LBL_SUGAR_BIN_STAGE' => 'Sugari prügikast (klõpsake üksustel nende lisamiseks ettevalmistusalale)',
'LBL_TOOLBOX' => 'Tööriistakast',
'LBL_VIEW_SUGAR_FIELDS' => 'Vaata Sugari välju',
'LBL_VIEW_SUGAR_BIN' => 'Vaata Sugari prügikasti',
'LBL_QUICKCREATE' => 'Kiirgeneraator',
'LBL_EDIT_DROPDOWNS' => 'Redigeeri globaalset ripploendit',
'LBL_ADD_DROPDOWN' => 'Lisa uus globaalne ripploend',
'LBL_BLANK' => '-tühi-',
'LBL_TAB_ORDER' => 'Vahekaardi järjestus',
'LBL_TAB_PANELS' => 'Luba vahekaardid',
'LBL_TAB_PANELS_HELP' => 'Kui vahekaardid on lubatud, kasutage tüübi rippboksi<br /> igale sektsiooni puhul selle kuvamise (vahekaart või paneel) määramiseks',
'LBL_TABDEF_TYPE' => 'Kuva tüüp',
'LBL_TABDEF_TYPE_HELP' => 'Valige selle sektsiooni kuvamise viis. See suvand kehtib vaid siis, kui olete selles vaates vahekaardid lubanud.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Vahekaart',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Paneel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Valige Paneel selle paneeli kuvamiseks paigutuse vaates. Valige Vahekaart selle paneeli kuvamisekspaigutuse eraldi vahekaardina. Kui paneeli puhul määratud Vahekaart, kuvatakse vahekaardil järgnevad paneelid, mis on seatud paneelina kuvama. <br/>Uus vahekaart käivitatakse järgmise paneeli puhul, mille puhul on valitud Vahekaart. Kui Vahekaart on valitud esimese paneeli all oleva paneeli puhul, on esimene paneel tingimata Vahekaart.',
'LBL_TABDEF_COLLAPSE' => 'Ahenda',
'LBL_TABDEF_COLLAPSE_HELP' => 'Valige selle paneeli vaikeoleku ahendamiseks.',
'LBL_DROPDOWN_TITLE_NAME' => 'Nimi',
'LBL_DROPDOWN_LANGUAGE' => 'Keel',
'LBL_DROPDOWN_ITEMS' => 'Loendiüksused',
'LBL_DROPDOWN_ITEM_NAME' => 'Üksuse nimi',
'LBL_DROPDOWN_ITEM_LABEL' => 'Kuva silt',
'LBL_SYNC_TO_DETAILVIEW' => 'Sünkrooni Detailvaatega',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Valige see suvand selle Redigeerimisvaate paigutuse sünkroonimiseks asjakohase Detailvaate paigutusega. Redigeerimisvaate<br>väljad ja välja paigutus sünkroonitakse ja salvestatakse Detailvaatega automaatselt Redigeerimisvaates nupu Salvesta või Salvesta ja juuruta klõpsamisel. <br>Detailvaates pole võimalik paigutust muuta.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'See Detailvaade sünkroonitakse asjakohase Redigeerimisvaatega.<br> Selle Detailvaate väljad ja välja paigutus peegeldavad välju ja välja paigutust Redigeerimisvaates.<br> Detailvaate muudatusi ei saa sellel lehel salvestada ega juurutada. Muutke paigutusi Redigeerimisvaates või sünkroonige need uuesti. ',
'LBL_COPY_FROM' => 'Kopeeri',
'LBL_COPY_FROM_EDITVIEW' => 'Kopeeri Redigeerimisvaatest',
'LBL_DROPDOWN_BLANK_WARNING' => 'Väärtused on nõutavad nii suvandi Üksuse nimi kui ka kuva silt puhul. Tühja üksuse lisamiseks klõpsake nuppu Lisa suvandite Üksuse nimi ja Kuva silt puhul väärtusi sisestamata.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Võti on juba olemas loendis',
'LBL_DROPDOWN_LIST_EMPTY' => 'Loend peab sisaldama vähemalt ühte lubatud üksust',
'LBL_NO_SAVE_ACTION' => 'Selle vaate salvestamistoimingut ei leitud.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: valesti koostatud dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Tähistab liitvälja. Liitväli on üksikute väljade kogum. Näiteks Aadress on liitväli, mis sisaldab suvandeid Tänava aadress, Linn, Sihtkood, Maakond ja Riik. <br><br>Topeltklõpsake liitvälja, et näha, milliseid välju see sisaldab.',
'LBL_COMBO_FIELD_CONTAINS' => 'sisaldab:',

'LBL_WIRELESSLAYOUTS'=>'Mobiilsed paigutused',
'LBL_WIRELESSEDITVIEW'=>'Mobiilne redigeerimisvaade',
'LBL_WIRELESSDETAILVIEW'=>'Mobiilne detailvaade',
'LBL_WIRELESSLISTVIEW'=>'Mobiilne loendivaade',
'LBL_WIRELESSSEARCH'=>'Mobiilne otsing',

'LBL_BTN_ADD_DEPENDENCY'=>'Lisa sõltuvus',
'LBL_BTN_EDIT_FORMULA'=>'Redigeeri valemit',
'LBL_DEPENDENCY' => 'Sõltuvus',
'LBL_DEPENDANT' => 'Sõltuv',
'LBL_CALCULATED' => 'Arvutatud väärtus',
'LBL_READ_ONLY' => 'Kirjutuskaitstud',
'LBL_FORMULA_BUILDER' => 'Valemikoostur',
'LBL_FORMULA_INVALID' => 'Sobimatu valem',
'LBL_FORMULA_TYPE' => 'Valemi tüüp peab olema ',
'LBL_NO_FIELDS' => 'Välju ei leitud',
'LBL_NO_FUNCS' => 'Funktsioone ei leitud',
'LBL_SEARCH_FUNCS' => 'Otsi funktsioone ...',
'LBL_SEARCH_FIELDS' => 'Otsi välju ...',
'LBL_FORMULA' => 'Valem',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Sõltuv',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Lohistage suvandid sõltuva ripploendi saadaolevatest suvanditest vasakul olevast loendist paremal olevatesse loenditesse, et muuta need suvandid emasuvandi valimisel saadavaks. Kui emasuvandi valimisel selle all ühtegi üksust pole, siis sõltuvat ripploendit ei kuvata.',
'LBL_AVAILABLE_OPTIONS' => 'Saadaolevad suvandid',
'LBL_PARENT_DROPDOWN' => 'Vanemripploend',
'LBL_VISIBILITY_EDITOR' => 'Nähtavuse redaktor',
'LBL_ROLLUP' => 'Kerimine',
'LBL_RELATED_FIELD' => 'Seotud väli',
'LBL_CONFIG_PORTAL_URL'=>'URL logo pildi kohandamiseks. Logo soovitatavad mõõtmed on 163 Ã 18 pikslit.',
'LBL_PORTAL_ROLE_DESC' => 'Ärge kustutage seda rolli. Klientide iseteenindusportaali roll on Sugari portaali aktiveerimisprotsessi käigus süsteemi loodud roll. Kasutage selles rollis olevaid juurdepääsukontrolle, et lubada ja/või keelata Sugari portaali moodulid Vead, Juhtumid või Teadmusbaas. Süsteemi tundmatu ja ettearvamatu käitumise vältimiseks ärge muutke selle rolli puhul muid juurdepääsukontrolle. Selle rolli juhusliku kustutamise korral taastage see, keelates ja lubades Sugari portaali.',

//RELATIONSHIPS
'LBL_MODULE' => 'Moodul',
'LBL_LHS_MODULE'=>'Põhimoodul',
'LBL_CUSTOM_RELATIONSHIPS' => '* seos on loodud Studios',
'LBL_RELATIONSHIPS'=>'Seosed',
'LBL_RELATIONSHIP_EDIT' => 'Redigeeri seost',
'LBL_REL_NAME' => 'Nimi',
'LBL_REL_LABEL' => 'Silt',
'LBL_REL_TYPE' => 'Tüüp',
'LBL_RHS_MODULE'=>'Seotud moodul',
'LBL_NO_RELS' => 'Seosed puuduvad',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Valikuline tingimus' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Veerg',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Väärtus',
'LBL_SUBPANEL_FROM'=>'Alampaneel',
'LBL_RELATIONSHIP_ONLY'=>'Selle seose puhul ei looda nähtavaid elemente, kuna nende kahe mooduli vahel on varasem nähtav seos.',
'LBL_ONETOONE' => 'Üks ühega',
'LBL_ONETOMANY' => 'Üks mitmega',
'LBL_MANYTOONE' => 'Mitu ühega',
'LBL_MANYTOMANY' => 'Mitu mitmega',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Valige funktsioon või komponent.',
'LBL_QUESTION_MODULE1' => 'Valige moodul.',
'LBL_QUESTION_EDIT' => 'Valige redigeeritav moodul.',
'LBL_QUESTION_LAYOUT' => 'Valige redigeeritav paigutus.',
'LBL_QUESTION_SUBPANEL' => 'Valige redigeeritav alampaneel.',
'LBL_QUESTION_SEARCH' => 'Valige redigeeritav otsingu paigutus.',
'LBL_QUESTION_MODULE' => 'Valige redigeeritav mooduli komponent.',
'LBL_QUESTION_PACKAGE' => 'Valige redigeeritav pakett või looge uus pakett.',
'LBL_QUESTION_EDITOR' => 'Valige tööriist.',
'LBL_QUESTION_DROPDOWN' => 'Valige redigeeritav rippmenüü või looge uus rippmenüü.',
'LBL_QUESTION_DASHLET' => 'Valige redigeeritav dashleti paigutus.',
'LBL_QUESTION_POPUP' => 'Valige redigeeritav hüpikakna paigutus.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Seo',
'LBL_NAME'=>'Nimi',
'LBL_LABELS'=>'Sildid',
'LBL_MASS_UPDATE'=>'Massuuendamine',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'Moodul',
'LBL_DEFAULT_VALUE'=>'Vaikeväärtus',
'LBL_REQUIRED'=>'Kohustuslik',
'LBL_DATA_TYPE'=>'Tüüp',
'LBL_HCUSTOM'=>'KOHANDA',
'LBL_HDEFAULT'=>'VAIKIMISI',
'LBL_LANGUAGE'=>'Keel:',
'LBL_CUSTOM_FIELDS' => '* väli on loodud Studios',

//SECTION
'LBL_SECTION_EDLABELS' => 'Redigeeri silte',
'LBL_SECTION_PACKAGES' => 'Paketid',
'LBL_SECTION_PACKAGE' => 'Pakett',
'LBL_SECTION_MODULES' => 'Moodulid',
'LBL_SECTION_PORTAL' => 'Portaal',
'LBL_SECTION_DROPDOWNS' => 'Rippmenüüd',
'LBL_SECTION_PROPERTIES' => 'Atribuudid',
'LBL_SECTION_DROPDOWNED' => 'Redigeeri rippmenüüd',
'LBL_SECTION_HELP' => 'Abi',
'LBL_SECTION_ACTION' => 'Tegevus',
'LBL_SECTION_MAIN' => 'Peamine',
'LBL_SECTION_EDPANELLABEL' => 'Redigeeri paneeli silti',
'LBL_SECTION_FIELDEDITOR' => 'Redigeeri välja',
'LBL_SECTION_DEPLOY' => 'Juuruta',
'LBL_SECTION_MODULE' => 'Moodul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Redigeeri nähtavust',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Vaikimisi',
'LBL_HIDDEN'=>'Peidetud',
'LBL_AVAILABLE'=>'Saadaval',
'LBL_LISTVIEW_DESCRIPTION'=>'Allpool kuvatakse kolm veergu. Veerg <b>Vaikimisi</b> sisaldab välju, mis kuvatakse loendivaates vaikimisi. Veerg <b>Täiendav</b> sisaldab välju, mida kasutaja saab valida kohandatud vaate loomiseks. Veerg <b>Saadaval</b> sisaldab välju, mis on teile kui administraatorile saadaval kas veergu Vaikimisi või Täiendav lisamiseks kasutajate jaoks.',
'LBL_LISTVIEW_EDIT'=>'Loendivaate redaktor',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Eelvaade',
'LBL_MB_RESTORE'=>'Taasta',
'LBL_MB_DELETE'=>'Kustuta',
'LBL_MB_COMPARE'=>'Võrdle',
'LBL_MB_DEFAULT_LAYOUT'=>'Vaikepaigutus',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Lisa',
'LBL_BTN_SAVE'=>'Salvesta',
'LBL_BTN_SAVE_CHANGES'=>'Salvesta muudatused',
'LBL_BTN_DONT_SAVE'=>'Loobu muudatustest',
'LBL_BTN_CANCEL'=>'Tühista',
'LBL_BTN_CLOSE'=>'Sulge',
'LBL_BTN_SAVEPUBLISH'=>'Salvesta ja juuruta',
'LBL_BTN_NEXT'=>'Järgmine',
'LBL_BTN_BACK'=>'Tagasi',
'LBL_BTN_CLONE'=>'Klooni',
'LBL_BTN_COPY' => 'Kopeeri',
'LBL_BTN_COPY_FROM' => 'Kopeeri asukohast …',
'LBL_BTN_ADDCOLS'=>'Lisa veerge',
'LBL_BTN_ADDROWS'=>'Lisa ridu',
'LBL_BTN_ADDFIELD'=>'Lisa väli',
'LBL_BTN_ADDDROPDOWN'=>'Lisa rippmenüü',
'LBL_BTN_SORT_ASCENDING'=>'Sordi tõusvas järjestuses',
'LBL_BTN_SORT_DESCENDING'=>'Sordi laskuvas järjestuses',
'LBL_BTN_EDLABELS'=>'Redigeeri silte',
'LBL_BTN_UNDO'=>'Võta tagasi',
'LBL_BTN_REDO'=>'Tee ümber',
'LBL_BTN_ADDCUSTOMFIELD'=>'Lisa kohandatud väli',
'LBL_BTN_EXPORT'=>'Ekspordi kohandused',
'LBL_BTN_DUPLICATE'=>'Dubleeri',
'LBL_BTN_PUBLISH'=>'Avalda',
'LBL_BTN_DEPLOY'=>'Juuruta',
'LBL_BTN_EXP'=>'Ekspordi',
'LBL_BTN_DELETE'=>'Kustuta',
'LBL_BTN_VIEW_LAYOUTS'=>'Vaata paigutusi',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Vaade mobiilseid paigutusi',
'LBL_BTN_VIEW_FIELDS'=>'Vaata välju',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Vaata seoseid',
'LBL_BTN_ADD_RELATIONSHIP'=>'Lisa seos',
'LBL_BTN_RENAME_MODULE' => 'Muuda mooduli nime',
'LBL_BTN_INSERT'=>'Sisesta',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Tõrge: väli on juba olemas',
'ERROR_INVALID_KEY_VALUE'=> "Tõrge: sobimatu võtmeväärtus: [']",
'ERROR_NO_HISTORY' => 'Ajaloo faile ei leitud',
'ERROR_MINIMUM_FIELDS' => 'Paigutus peab sisaldama vähemalt ühte välja',
'ERROR_GENERIC_TITLE' => 'Ilmnes tõrge',
'ERROR_REQUIRED_FIELDS' => 'Kas soovite kindlasti jätkata? Paigutusest puuduvad järgmised kohustuslikud väljad:  ',
'ERROR_ARE_YOU_SURE' => 'Kas soovite kindlasti jätkata?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Järgmis(t)el välja(de)l on arvutatud väärtused, mida ei arvutata SugarCRM-i Mobile&#39;i redigeerimisvaates reaalajas ümber:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Järgmis(t)el välja(de)l on arvutatud väärtused, mida ei arvutata SugarCRM-i Portali redigeerimisvaates reaalajas ümber:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Keelatud on järgmised moodulid:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Kui soovite neid portaalis lubada, tehke seda <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">siin</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Konfigureeri portaal',
    'LBL_PORTAL_THEME' => 'Teema portaal',
    'LBL_PORTAL_ENABLE' => 'Luba',
    'LBL_PORTAL_SITE_URL' => 'Teie portaali sait on saadaval aadressil:',
    'LBL_PORTAL_APP_NAME' => 'Rakenduse nimi',
    'LBL_PORTAL_LOGO_URL' => 'Logo URL',
    'LBL_PORTAL_LIST_NUMBER' => 'Loendis kuvatavate kirjete arv',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Detailvaates kuvatavate väljade arv',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Globaalses otsingus kuvatavate tulemuste arv',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Uute portaali registreerimiste puhul määratud vaikeväärtus',

'LBL_PORTAL'=>'Portaal',
'LBL_PORTAL_LAYOUTS'=>'Portaali paigutused',
'LBL_SYNCP_WELCOME'=>'Sisestage selle portaali eksemplari URL, mida soovite värskendada.',
'LBL_SP_UPLOADSTYLE'=>'Vali oma arvutist üleslaaditav laadileht.<br> Laadilehte rakendatakse Sugar Portalis järgmisel sünkroonimisel.',
'LBL_SP_UPLOADED'=> 'Üles laaditud',
'ERROR_SP_UPLOADED'=>'Veenduge, et sa laadite üles css laadilehe.',
'LBL_SP_PREVIEW'=>'Siin on eelvaade sellest, milline näeb Sugar Portal välja laadilehe kasutamisel.',
'LBL_PORTALSITE'=>'Sugar Portali URL: ',
'LBL_PORTAL_GO'=>'Mine',
'LBL_UP_STYLE_SHEET'=>'Laadi laadileht üles',
'LBL_QUESTION_SUGAR_PORTAL' => 'Valige redigeeritav Sugar Portali paigutus.',
'LBL_QUESTION_PORTAL' => 'Valige redigeeritav portaali paigutus.',
'LBL_SUGAR_PORTAL'=>'Sugar Portali redaktor',
'LBL_USER_SELECT' => '-- Vali --',

//PORTAL PREVIEW
'LBL_CASES'=>'Juhtumid',
'LBL_NEWSLETTERS'=>'Uudiskirjad',
'LBL_BUG_TRACKER'=>'Vigade otsija',
'LBL_MY_ACCOUNT'=>'Minu konto',
'LBL_LOGOUT'=>'Logi välja',
'LBL_CREATE_NEW'=>'Loo uus',
'LBL_LOW'=>'Madal',
'LBL_MEDIUM'=>'Keskmine',
'LBL_HIGH'=>'Kõrge',
'LBL_NUMBER'=>'Number:',
'LBL_PRIORITY'=>'Tähtsus:',
'LBL_SUBJECT'=>'Teema',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Paketi nimi:',
'LBL_MODULE_NAME'=>'Mooduli nimi:',
'LBL_MODULE_NAME_SINGULAR' => 'Ainsuse mooduli nimi:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Kirjeldus:',
'LBL_KEY'=>'Võti:',
'LBL_ADD_README'=>' Seletus',
'LBL_MODULES'=>'Moodulid:',
'LBL_LAST_MODIFIED'=>'Viimati muudetud:',
'LBL_NEW_MODULE'=>'Uus moodul',
'LBL_LABEL'=>'Mitmuse silt',
'LBL_LABEL_TITLE'=>'Silt',
'LBL_SINGULAR_LABEL' => 'Ainsuse silt',
'LBL_WIDTH'=>'Laius',
'LBL_PACKAGE'=>'Pakett:',
'LBL_TYPE'=>'Tüüp:',
'LBL_TEAM_SECURITY'=>'Meeskonna turvalisus',
'LBL_ASSIGNABLE'=>'Määratav',
'LBL_PERSON'=>'Isik',
'LBL_COMPANY'=>'Ettevõte',
'LBL_ISSUE'=>'Probleem',
'LBL_SALE'=>'Müük',
'LBL_FILE'=>'Fail',
'LBL_NAV_TAB'=>'Navigatsiooni vahekaart',
'LBL_CREATE'=>'Loo',
'LBL_LIST'=>'Loend',
'LBL_VIEW'=>'Vaade',
'LBL_LIST_VIEW'=>'Loendivaade',
'LBL_HISTORY'=>'Vaata ajalugu',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Taasta vaikepaigutus',
'LBL_ACTIVITIES'=>'Tegevuste voog',
'LBL_SEARCH'=>'Otsi',
'LBL_NEW'=>'Uus',
'LBL_TYPE_BASIC'=>'algne',
'LBL_TYPE_COMPANY'=>'ettevõte',
'LBL_TYPE_PERSON'=>'isik',
'LBL_TYPE_ISSUE'=>'probleem',
'LBL_TYPE_SALE'=>'müük',
'LBL_TYPE_FILE'=>'fail',
'LBL_RSUB'=>'See on teie moodulis kuvatav alampaneel',
'LBL_MSUB'=>'See on alampaneel, mida teie moodul seotud moodulile kuvamiseks pakub',
'LBL_MB_IMPORTABLE'=>'Luba import',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'nähtav',
'LBL_VE_HIDDEN'=>'peidetud',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] on kustutatud',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Ekspordi kohandused',
'LBL_EC_NAME'=>'Paketi nimi:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Kirjeldus:',
'LBL_EC_KEY'=>'Võti:',
'LBL_EC_CHECKERROR'=>'Valige moodul.',
'LBL_EC_CUSTOMFIELD'=>'kohandatud väli/väljad',
'LBL_EC_CUSTOMLAYOUT'=>'kohandatud paigutus(ed)',
'LBL_EC_CUSTOMDROPDOWN' => 'kohandatud rippmenüü(d)',
'LBL_EC_NOCUSTOM'=>'Kohandatud pole ühtegi moodulit.',
'LBL_EC_EXPORTBTN'=>'Ekspordi',
'LBL_MODULE_DEPLOYED' => 'Moodul on juurutatud.',
'LBL_UNDEFINED' => 'määratlemata',
'LBL_EC_CUSTOMLABEL'=>'kohandatud silt/sildid',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Andmete toomine nurjus',
'LBL_AJAX_TIME_DEPENDENT' => 'Ajast sõltuv toiming on pooleli. Oodake ja proovige paari sekundi pärast uuesti.',
'LBL_AJAX_LOADING' => 'Laadmine ...',
'LBL_AJAX_DELETING' => 'Kustutamine ...',
'LBL_AJAX_BUILDPROGRESS' => 'Koostamine on pooleli ...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Juurutamine on pooleli ...',
'LBL_AJAX_FIELD_EXISTS' =>'Teie sisestatud välja nimi on juba olemas. Sisestage uus välja nimi.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Kas soovite selle paketi kindlasti eemaldada? See toiming kustutab kõik selle paketiga seotud failid jäädavalt.',
'LBL_JS_REMOVE_MODULE' => 'Kas soovite selle mooduli kindlasti eemaldada? See toiming kustutab kõik selle mooduliga seotud failid jäädavalt.',
'LBL_JS_DEPLOY_PACKAGE' => 'Kõik teie Studios tehtud muudatused kirjutatakse selle mooduli uuesti juurutamisel üle. Kas olete kindel, et soovite jätkata?',

'LBL_DEPLOY_IN_PROGRESS' => 'Paketi juurutamiine',
'LBL_JS_VALIDATE_NAME'=>'Nimi peab algama tähega ja võib sisaldada ainult tähti, numbreid ning allkriipse. Tühikud või muud erimärgid pole lubatud.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Paketi võti on juba olemas',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Paketi nimi on juba olemas',
'LBL_JS_PACKAGE_NAME'=>'Paketi nimi peab algama tähega ja võib sisaldada ainult tähti, numbreid ning allkriipse. Tühikud või muud erimärgid pole lubatud.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Võti peab olema tähtnumbriline ja algama tähega.',
'LBL_JS_VALIDATE_KEY'=>'Kood peab olema tähtnumbriline, algama tähega ega tohi sisaldada tühikuid.',
'LBL_JS_VALIDATE_LABEL'=>'Sisestage silt, mida kasutatakse selle mooduli puhul kuvatava nimena',
'LBL_JS_VALIDATE_TYPE'=>'Valige allolevast loendist mooduli tüüp, mida soovite koostada',
'LBL_JS_VALIDATE_REL_NAME'=>'Nimi peab olema tähtnumbriline ja tühikuteta',
'LBL_JS_VALIDATE_REL_LABEL'=>'Silt – lisage silt, mis kuvatakse alampaneeli kohal',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Kas olete kindel, et soovite selle nõutava ripploendi üksuse kustutada? See võib mõjutada teie rakenduse funktsionaalsust.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Kas olete kindel, et soovite selle ripploendi üksuse kustutada? Etappide Lõpetatud võidetud või Lõpetatud kaotatud kustutamine tekitab mooduli Prognoosimine mittekorralikult töötamise',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Kas olete kindel, et soovite oleku Uus müük kustutada? Selle oleku kustutamine põhjustab mooduli Müügivõimalused töövoo Tuluartikkel mittekorralikult töötamist.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Kas olete kindel, et soovite oleku Pooleli müük kustutada? Selle oleku kustutamine põhjustab mooduli Müügivõimalused töövoo Tuluartikkel mittekorralikult töötamist.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Kas olete kindel, et soovite müügietapi Lõpetatud võidetud kustutada? Selle etapi kustutamine põhjustab mooduli Prognoosimine mittekorralikult töötamist',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Kas olete kindel, et soovite müügietapi Lõpetatud kaotatud kustutada? Selle etapi kustutamine põhjustab mooduli Prognoosimine mittekorralikult töötamist',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Selle kohandatud välja kustutamine kustutab nii kohandatud välja kui ka kõik andmebaasis kohandatud väljaga seotud andmed. Välja ei kuvata enam üheski mooduli paigutuses.'
        . ' Kui väli on kaasatud mis tahes väljade väärtuste arvutamise valemisse, siis see valem enam ei toimi.'
        . '\\n\\nVäli pole enam aruannetes kasutamiseks saadaval; see muudatus hakkab kehtima pärast rakendusest välja ja uuesti sisse logimist. Välja sisaldavaid aruandeid tuleb värskendada, et neid oleks võimalik käivitada.'
        . '\\n\\nKas soovite jätkata?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Kas soovite kindlasti selle seose kustutada?<br>Märkus. See toiming võib võtta mitu minutit aega.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'See muudab selle seose püsivaks. Kas olete kindel, et soovite selle seose juurutada?',
'LBL_CONFIRM_DONT_SAVE' => 'Pärast teie viimati salvestamist on tehtud muudatusi, kas soovite need salvestada?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Kas salvestada muudatused?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Andmed võivad olla kärbitud ja seda ei saa tagasi võtta, kas soovite kindlasti jätkata?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Valige väljale sisestatavate andmete tüübi põhjal sobiv andmetüüp.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Konfigureerige väli täistekstotsitavaks.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Võimendamine on kirje väljade asjakohasuse täiustamise protsess.<br />Kõrgema võimendustasemega väljadele antakse otsimisel suurem kaal. Kui otsing on tehtud, kuvatakse suurema kaaluga välju sisaldavad ühtivad kirjed otsingutulemustes kõrgemal.<br />Vaikeväärtus on 1.0, mis tähistab neutraalset võimendust. Positiivse võimenduse rakendamiseks aktsepteeritakse kõik ujuväärtused, mis on üle 1. Negatiivse võimenduse puhul kasutage väärtusi, mis on alla 1. Näiteks väärtus 1.35 võimendab välja positiivselt 135%. Väärtuse 0.60 kasutamine rakendab negatiivse võimenduse.<br />Pange tähele, et varasemates versioonides oli täistekstotsingu reindekseerimine kohustuslik. See pole enam nõutav.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Jah</b>: väli kaasatakse importimisse.<br><b>Ei</b>: välja ei kaasata importimisse.<br><b>Kohustuslik</b>: välja väärtus tuleb esitada iga impordi puhul.',
'LBL_POPHELP_PII'=>'See väli märgitakse automaatselt auditi jaoks ja on saadaval Isikliku teabe vaates.<br>Isikliku teabe välju saab samuti püsivalt kustutada, kui kirje on seotud andmete privaatsuse mooduli kustutamise taotlusega.<br>Kustutamine tehakse andmete privaatsuse mooduli kaudu ja seda saavad algatada administraatorid või kasutajad andmete privaatsuse haldaja rollis.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Sisesta laiuse number mõõdetuna pikslites.<br> Üleslaaditud pilt mastaabitakse sellele laiusele.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Sisestage kõrguse number mõõdetuna pikslites.<br> Üleslaaditud pilt mastaabitakse sellele kõrgusele.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Lubatud</b>: väli kuvatakse funktsioonis Duplikaatide ühendamine, kuid pole kasutamiseks saadaval filtri tingimuste puhul funktsioonis Duplikaatide otsimine.<br><b>Keelatud</b>: välja ei kuvata funktsioonis Duplikaatide ühendamine ja see pole kasutamiseks saadaval filtri tingimuste puhul funktsioonis Duplikaatide otsimine.'
. '<br><b>Filtris</b>: väli kuvatakse funktsioonis Duplikaatide ühendamine ja on saadaval ka funktsioonis Duplikaatide otsimine.<br><b>Ainult filter</b>: välja ei kuvata funktsioonis Duplikaatide ühendamine, kuid see on saadaval funktsioonis Duplikaatide otsimine.<br><b>Vaikimisi valitud filter</b>: välja kasutatakse vaikimisi filtri tingimuse puhul lehel Duplikaatide otsimine ja kuvatakse ka funktsioonis Duplikaatide ühendamine.'
,
'LBL_POPHELP_CALCULATED'=>"Looge valem, et määrata selles väljas olev väärtus.<br>"
   . "Tegevust sisaldavad töövoo definitsioonid, mis on määratud seda välja värskendama, ei käivita enam seda toimingut.<br>"
   . "Valemeid sisaldavate väljade väärtusi ei arvutata reaalajas "
   . "Sugari iseteenindusportaalis ega "
   . "mobiilsetes redigeerimisvaate paigutustes.",

'LBL_POPHELP_DEPENDENT'=>"Looge valem, et määrata, kas see väli on paigutustes nähtav.<br/>"
        . "Sõltuvad väljad järgivad brauseripõhises mobiilivaates sõltuvusvalemit, <br/>"
        . "kuid ei järgi valemit omarakendustes, näiteks iPhone’ile mõeldud Sugar Mobile’is. <br/>"
        . "Need ei järgi valemit Sugari iseteenindusportaalis.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Valige selle välja kasutamine, kui otsite globaalse otsinguga kirjeid selles moodulis.',
//Revert Module labels
'LBL_RESET' => 'Lähtesta',
'LBL_RESET_MODULE' => 'Lähtesta moodul',
'LBL_REMOVE_CUSTOM' => 'Eemalda kohandamised',
'LBL_CLEAR_RELATIONSHIPS' => 'Tühista seosed',
'LBL_RESET_LABELS' => 'Lähtesta sildid',
'LBL_RESET_LAYOUTS' => 'Lähtesta paigutused',
'LBL_REMOVE_FIELDS' => 'Eemalda kohandatud väljad',
'LBL_CLEAR_EXTENSIONS' => 'Tühjenda laiendid',

'LBL_HISTORY_TIMESTAMP' => 'Ajatempel',
'LBL_HISTORY_TITLE' => 'ajalugu',

'fieldTypes' => array(
                'varchar'=>'Tekstiväli',
                'int'=>'Täisarv',
                'float'=>'Ujuk',
                'bool'=>'Märkeruut',
                'enum'=>'Ripploend',
                'multienum' => 'Mitmikvalik',
                'date'=>'Kuupäev',
                'phone' => 'Telefoninumber',
                'currency' => 'Valuuta',
                'html' => 'HTML',
                'radioenum' => 'Raadio',
                'relate' => 'Seo',
                'address' => 'Aadress',
                'text' => 'Tekstiala',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Pilt',
                'encrypt'=>'Krüpti',
                'datetimecombo' =>'Kuupäev ja kellaaeg',
                'decimal'=>'Kümnendkohtade arv',
),
'labelTypes' => array(
    "" => "Sageli kasutatavad sildid",
    "all" => "Kõik sildid",
),

'parent' => 'Paindlik seostamine',

'LBL_ILLEGAL_FIELD_VALUE' =>"Rippmenüü võti ei saa sisaldada pakkumisi.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Valite selle üksuse ripploendist eemaldamiseks. Seda loendit koos seda üksust väärtusena kasutavad rippväljad ei kuva enam väärtust ja väärtust pole enam võimalik rippväljadelt valida. Kas olete kindel, et soovite jätkata?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Valige selle välja valideerimine 10-kohalise<br>" .
                                 "telefoninumbri sisestamiseks, lubades riigikoodi 1 ja<br>" .
                                 "USA vormingu rakendamise telefoninumbri puhul kirje<br>" .
                                 "salvestamisel. Rakendatakse järgmist vormingut: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Kõik moodulid',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (seotud {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Kopeeri paigutusest',
);
