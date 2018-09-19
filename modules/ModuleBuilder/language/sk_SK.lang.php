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
    'LBL_LOADING' => 'Načítava sa' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Skryť možnosti' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Vymazať' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'S podporou SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Rola',
'help'=>array(
    'package'=>array(
            'create'=>'Zadajte <b>názov</b> balíka. Názov musí začínať písmenom a môže obsahovať iba písmená, čísla a znaky podčiarknutia. Nesmú byť použité žiadne medzery ani iné špeciálne znaky. (Príklad: HR_Management) <br/> <br/> Môžete uviesť <b>Autora</b> a <b>Popis</b> balíka. <br/> <br/>Ak chcete vytvoriť balík, kliknite na tlačidlo <b>Uložiť</b>.',
            'modify'=>'Tu sa zobrazujú vlastnosti a možné akcie pre <b>Balík</b>. <br><br>Môžete upraviť položky <b>Názov</b>, <b>Autor</b> a <b>Popis</b> balíka, ako aj zobraziť a upraviť všetky moduly obsiahnuté v balíku. <br> <br>Kliknite na možnosť <b>Nový modul</b>, ak chcete vytvoriť modul pre balík. <br><br>Ak balík obsahuje aspoň jeden modul, môžete balík <b>Zverejniť</b> a <b>Použiť</b>, ako aj <b>Exportovať</b> vlastné úpravy vykonané v balíku.',
            'name'=>'Toto je <b>názov</b> aktuálneho balíka. <br/> <br/>Názov musí začínať písmenom a môže obsahovať iba písmená, čísla a znaky podčiarknutia. Nesmú byť použité žiadne medzery ani iné špeciálne znaky. (Príklad: HR_Management)',
            'author'=>'Toto je <b>Autor</b>, ktorý sa zobrazí počas inštalácie ako meno subjektu, ktorý vytvoril balík. <br><br>Autor môže byť jednotlivec alebo spoločnosť.',
            'description'=>'Toto je <b>Popis</b> balíka, ktorý sa zobrazí počas inštalácie.',
            'publishbtn'=>'Kliknite na možnosť <b>Zverejniť</b> a uložte tak všetky zadané údaje a vytvorte súbor .zip, ktorý predstavuje inštalovateľnú verziu balíka. <br><br>Použite funkciu <b>Načítanie modulov</b> na nahratie súboru .zip a inštaláciu balíka.',
            'deploybtn'=>'Kliknite na možnosť <b>Použiť</b> a uložte tak všetky zadané údaje a nainštalujte v aktuálnej inštancii balík vrátane všetkých modulov.',
            'duplicatebtn'=>'Kliknutím na možnosť <b>Duplikovať</b> skopírujte obsah balíka do nového balíka a zobrazte nový balík. <br/> <br/>Pre nový balík sa automaticky vygeneruje nový názov pridaním čísla na koniec názvu balíka použitého na vytvorenie nového. Nový balík môžete premenovať zadaním nového <b>Názvu</b> a kliknutím na položku <b>Uložiť</b>.',
            'exportbtn'=>'Kliknutím na možnosť <b>Exportovať</b> vytvoríte súbor .zip obsahujúci vlastné nastavenia balíka. <br><br>Vygenerovaný súbor nie je inštalovateľnou verziou balíka. <br> <br>Použite funkciu <b>Načítanie modulov</b> na import súboru .zip a zobrazenie balíka vrátane vlastných nastavení v časti Tvorca modulov.',
            'deletebtn'=>'Kliknutím na možnosť <b>Vymazať</b> odstráňte tento balík a všetky súbory súvisiace s týmto balíkom.',
            'savebtn'=>'Kliknutím na možnosť <b>Uložiť</b> uložte všetky údaje súvisiace s balíkom.',
            'existing_module'=>'Kliknutím na ikonu <b>Modul</b> upravte vlastnosti a upravte polia, vzťahy a rozloženia priradené k modulu.',
            'new_module'=>'Kliknutím na možnosť <b>Nový modul</b> vytvorte nový modul pre tento balík.',
            'key'=>'Tento 5-miestny alfanumerický <b>Kľúč</b> bude použitý ako predpona pre všetky adresáre, názvy tried a tabuľky databáz pre všetky moduly v súčasnom balíku.<br><br>Kľúč sa používa s cieľom dosiahnuť jedinečnosť názvov tabuliek.',
            'readme'=>'Kliknite tu a pridajte text <b>Readme</b> pre tento balík. <br><br>Text Readme bude k dispozícii počas inštalácie.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Zadajte <b>Názov</b> pre modul. <b>Označenie</b>, ktoré uvediete, sa zobrazí v karte navigácie. <br/> Začiarknutím poľa <b>Karta navigácie</b> <br/>vyberte možnosť zobraziť kartu navigácie pre modul. <br/> <br/> Začiarknutím poľa <b>Zabezpečenie tímu</b> pridajte pole s výberom tímu do záznamov modulu. <br/> <br/> Potom vyberte typ modulu, ktorý by ste chceli vytvoriť. <br/> <br/> Vyberte typ šablóny. Každá šablóna obsahuje špecifický súbor polí, ako aj preddefinovaných rozložení, ktoré možno použiť ako základ pre váš modul. <br/> <br/> Kliknutím na možnosť<b>Uložiť</b> vytvorte modul.',
        'modify'=>'Môžete zmeniť vlastnosti modulu alebo prispôsobiť položky <b>Polia</b>, <b>Vzťahy</b> a <b>Rozloženia</b> súvisiace s modulom.',
        'importable'=>'Začiarknutím poľa <b>Importovateľné</b> povolíte import pre tento modul. <br><br>Prepojenie na Sprievodcu importom sa zobrazí v paneli odkazov v module.  Sprievodca importom umožňuje import dát z externých zdrojov do vlastného modulu.',
        'team_security'=>'Začiarknutím poľa <b>Zabezpečenie tímu</b> aktivujete zabezpečenie tímu pre tento modul.  <br/> <br/>Ak je aktivované zabezpečenie tímu, pole s výberom tímu sa zobrazí v záznamoch v module ',
        'reportable'=>'Začiarknutím tohto poľa povolíte vytváranie hlásení pre tento modul.',
        'assignable'=>'Začiarknutím tohto poľa povolíte priradenie záznamu v tomto module vybratému používateľovi.',
        'has_tab'=>'Začiarknutím poľa <b>Karta navigácie</b> aktivujete kartu navigácie pre tento modul.',
        'acl'=>'Začiarknutím tohto poľa aktivujete ovládacie prvky prístupu pre tento modul vrátane zabezpečenia na úrovni polí.',
        'studio'=>'Začiarknutím tohto poľa umožníte administrátorom upravovať tento modul v nástroji Studio.',
        'audit'=>'Začiarknutím tohto poľa povolíte Audit pre tento modul. Zmeny určitých polí sa zaznamenajú, takže administrátori budú môcť prezerať históriu zmien.',
        'viewfieldsbtn'=>'Kliknutím na možnosť <b>Zobraziť polia</b> zobrazíte polia súvisiace s modulom a vytvoríte a upravíte vlastné polia.',
        'viewrelsbtn'=>'Kliknutím na možnosť <b>Zobraziť vzťahy</b> zobrazíte vzťahy súvisiaca s týmto modulom a vytvoríte nové vzťahy.',
        'viewlayoutsbtn'=>'Kliknutím na možnosť <b>Rozloženia zobrazenia</b> zobrazíte rozloženia pre tento modul a prispôsobíte usporiadanie polí v rámci rozloženia.',
        'viewmobilelayoutsbtn' => 'Kliknutím na možnosť <b>Rozloženia mobilného zobrazenia</b> zobrazíte mobilné rozloženia pre tento modul a prispôsobíte usporiadanie polí v rámci rozloženia.',
        'duplicatebtn'=>'Kliknutím na tlačidlo <b>Duplikovať</b> skopírujte vlastnosti modulu do nového modulu a zobrazte nový modul. <br/> <br/>Pre nový modul sa automaticky vygeneruje nový názov pridaním čísla na koniec názvu modulu použitého na vytvorenie nového.',
        'deletebtn'=>'Kliknutím na možnosť <b>Vymazať</b> odstránite tento modul.',
        'name'=>'Toto je <b>Názov</b> aktuálneho modulu. <br/> <br/>Názov musí byť alfanumerický, musí začínať písmenom a neobsahovať žiadne medzery. (Príklad: HR_Management)',
        'label'=>'Toto je <b>Označenie</b>, ktoré sa zobrazí na karte navigácie pre tento modul. ',
        'savebtn'=>'Kliknutím na možnosť <b>Uložiť</b> uložte všetky údaje súvisiace s modulom.',
        'type_basic'=>'<b>Základný</b> typ šablóny obsahuje základné polia, ako je názov, priradené komu, tím, dátum vytvorenia a pole s popisom.',
        'type_company'=>'Typ šablóny <b>Spoločnosť</b> obsahuje polia, ktoré sú pre danú organizáciu špecifické, napríklad názov spoločnosti, odvetvie a fakturačnú adresu. <br/> <br/>Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardnému modulu Účty.',
        'type_issue'=>'Typ šablóny <b>Problém</b> obsahuje špecifické polia pre udalosti a chyby, napríklad počet, stav, prioritu a popis. <br/> <br/>Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardným modulom Prípady a Stopovač chýb.',
        'type_person'=>'Typ šablóny <b>Osoba</b> obsahuje polia špecifické pre jednotlivcov, napríklad oslovenie, titul, meno, adresu a telefónne číslo. <br/> <br/>Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardným modulom Kontakty a Záujemcovia.',
        'type_sale'=>'Typ šablóny <b>Predaj</b> obsahuje polia špecifické pre príležitosť, ako napr. zdroj príležitosti, etapa, suma a pravdepodobnosť. <br/> <br/>Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardnému modulu Príležitosti.',
        'type_file'=>'Šablóna <b>Súbor</b> obsahuje špecifické polia pre dokument, ako napr. názov súboru, typ dokumentu a dátum zverejnenia. <br><br>Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardnému modulu Dokumenty.',

    ),
    'dropdowns'=>array(
        'default' => 'Všetky <b>Rozbaľovacie ponuky</b> pre aplikácie sú uvedené tu. <br><br>Rozbaľovacie ponuky možno použiť pre rozbaľovacie polia v akomkoľvek module. <br> <br>Ak chcete vykonať zmeny v existujúcej rozbaľovacej ponuke, kliknite na názov rozbaľovacej ponuky. <br><br>Kliknutím na možnosť <b>Pridať rozbaľovaciu ponuku</b> vytvoríte nové rozbaľovacie ponuky.',
        'editdropdown'=>'Rozbaľovacie zoznamy možno použiť pre štandardné alebo vlastné rozbaľovacie polia v akomkoľvek module. <br><br>Uveďte <b>Názov</b> rozbaľovacieho zoznamu. <br> <br>Ak sú v aplikácii nainštalované jazykové balíky, môžete vybrať aj <b>Jazyk</b> pre položky zoznamu. <br><br>V poli <b>Názov položky</b> zadajte názov pre možnosť v rozbaľovacom zozname. Tento názov sa nezobrazí v rozbaľovacom zozname, ktorý je viditeľný pre používateľov. <br><br>V poli <b>Zobrazené označenie</b> uveďte označenie, ktoré sa zobrazí používateľom. <br> <br>Po zadaní názvu položky a zobrazení označenia kliknite na možnosť<b>Pridať</b> a pridajte položku do rozbaľovacieho zoznamu. <br><br>Ak chcete zmeniť poradie položiek v zozname, pretiahnite položky do požadovanej pozície. <br> <br>Ak chcete upraviť zobrazené označenie položky, kliknite na <b>ikonu Upraviť</b> a zadajte nové označenie. Ak chcete položku z rozbaľovacieho zoznamu odstrániť, kliknite <b>na ikonu Vymazať</b>. <br><br>Ak chcete vrátiť zmeny vykonané v zobrazenom označení, kliknite na tlačidlo <b>Späť</b>.  Ak chcete zopakovať zmenu, ktorá bola vrátená späť, kliknite na tlačidlo <b>Vrátiť</b>. <br><br>Kliknutím na možnosť <b>Uložiť</b> uložte rozbaľovací zoznam.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'Všetky polia, ktoré môžu byť zobrazené v časti <b>Podpanel</b>, sa zobrazia tu.<br><br>Stĺpec <b>Predvolené</b> obsahuje polia, ktoré sú zobrazené v časti Podpanel. <br/> <br/>Stĺpec <b>Skryté</b> obsahuje polia, ktoré možno pridať do stĺpca Predvolené.'
    . '<br/> <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_dependent.png"/> Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca. <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        'savebtn'	=> 'Kliknutím na možnosť <b>Uložiť a použiť</b> uložte vykonané zmeny, aby sa prejavili v danom module.',
        'historyBtn'=> 'Kliknutím na možnosť <b>Zobraziť históriu</b> zobrazte a obnovte predtým uložené rozloženie z histórie.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na možnosť <b>Obnoviť predvolené rozloženie</b> obnovte zobrazenie na pôvodné rozloženie.',
        'Hidden' 	=> '<b>Skryté</b> polia sa v podpaneli nezobrazujú.',
        'Default'	=> '<b>Predvolené</b> polia sa v podpaneli zobrazia.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'Všetky polia, ktoré môžu byť zobrazené v <b>Náhľade zoznamu</b>, sa zobrazia tu. <br><br>Stĺpec <b>Predvolené</b> obsahuje polia, ktoré sa predvolene zobrazujú v Náhľade zoznamu. <br/> <br/>Stĺpec <b>Dostupné</b> obsahuje polia, z ktorých si môže používateľ vybrať pri vyhľadávaní na vytvorenie vlastného náhľadu zoznamu. <br/> <br/>Stĺpec <b>Skryté</b> obsahuje polia, ktoré možno pridať do stĺpca Predvolené alebo Dostupné.'
    . '<br/> <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_dependent.png"/> Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca. <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        'savebtn'	=> 'Kliknutím na možnosť <b>Uložiť a použiť</b> uložte vykonané zmeny, aby sa prejavili v danom module.',
        'historyBtn'=> 'Kliknutím na možnosť <b>Zobraziť históriu</b> zobrazte a obnovte predtým uložené rozloženie z histórie. <br><br>Funkcia <b>Obnoviť</b> v časti <b>Zobraziť históriu</b> obnoví umiestnenie poľa v rámci predtým uložených rozložení. Ak chcete zmeniť označenia poľa, kliknite na ikonu Upraviť vedľa daného poľa.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na možnosť <b>Obnoviť predvolené rozloženie</b> obnovte zobrazenie na pôvodné rozloženie. <br><br>Funkcia <b>Obnoviť predvolené rozloženie</b> obnoví iba umiestnenie poľa v rámci pôvodného rozloženia. Ak chcete zmeniť označenia polí, kliknite na ikonu Editovať vedľa daného poľa.',
        'Hidden' 	=> '<b>Skryté</b> polia momentálne nie sú dostupné pre používateľov v náhľadoch zoznamu.',
        'Available' => '<b>Dostupné</b> polia sa nezobrazia v predvolenom nastavení, ale používatelia ich môžu pridať do náhľadov zoznamu.',
        'Default'	=> '<b>Predvolené</b> polia sa zobrazia v náhľadoch zoznamu, ktoré používatelia neprispôsobili podľa vlastných nastavení.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'Všetky polia, ktoré môžu byť zobrazené v časti <b>Náhľad zoznamu</b>, sa zobrazia tu.<br><br>Stĺpec <b>Predvolené</b> obsahuje polia, ktoré sú zobrazené v náhľade zoznamu. <br/> <br/> Stĺpec <b>Skryté</b> obsahuje polia, ktoré možno pridať do stĺpca Predvolené alebo Dostupné.'
    . '<br/> <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_dependent.png"/> Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca. <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        'savebtn'	=> 'Kliknutím na možnosť <b>Uložiť a použiť</b> uložte vykonané zmeny, aby sa prejavili v danom module.',
        'historyBtn'=> 'Kliknutím na možnosť <b>Zobraziť históriu</b> zobrazte a obnovte predtým uložené rozloženie z histórie. <br><br>Funkcia <b>Obnoviť</b> v časti <b>Zobraziť históriu</b> obnoví umiestnenie poľa v rámci predtým uložených rozložení. Ak chcete zmeniť označenia poľa, kliknite na ikonu Upraviť vedľa daného poľa.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na možnosť <b>Obnoviť predvolené rozloženie</b> obnovte zobrazenie na pôvodné rozloženie. <br><br>Funkcia <b>Obnoviť predvolené rozloženie</b> obnoví iba umiestnenie poľa v rámci pôvodného rozloženia. Ak chcete zmeniť označenia polí, kliknite na ikonu Editovať vedľa daného poľa.',
        'Hidden' 	=> '<b>Skryté</b> polia momentálne nie sú dostupné pre používateľov v náhľadoch zoznamu.',
        'Default'	=> '<b>Predvolené</b> polia sa zobrazia v náhľadoch zoznamu, ktoré používatelia neprispôsobili podľa vlastných nastavení.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'Všetky polia, ktoré môžu byť zobrazené v časti <b>Vyhľadávanie</b>, sa zobrazia tu.<br><br>Stĺpec <b>Predvolené</b> obsahuje polia, ktoré sú zobrazené v časti Vyhľadávanie. <br/> <br/>Stĺpec <b>Skryté</b> obsahuje polia, ktoré administrátor môže pridať do formulára Vyhľadávanie.'
    . '<br/> <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_dependent.png"/> Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca. <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    . '<br/> <br/> Táto konfigurácia sa týka len rozloženia kontextového vyhľadávania v starších moduloch.',
        'savebtn'	=> 'Kliknutím na možnosť <b>Uložiť a použiť</b> uložíte všetky zmeny a zmeny sa prejavia',
        'Hidden' 	=> '<b>Skryté</b> polia sa vo vyhľadávaní nezobrazujú.',
        'historyBtn'=> 'Kliknutím na možnosť <b>Zobraziť históriu</b> zobrazte a obnovte predtým uložené rozloženie z histórie. <br><br>Funkcia <b>Obnoviť</b> v časti <b>Zobraziť históriu</b> obnoví umiestnenie poľa v rámci predtým uložených rozložení. Ak chcete zmeniť označenia poľa, kliknite na ikonu Upraviť vedľa daného poľa.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na možnosť <b>Obnoviť predvolené rozloženie</b> obnovte zobrazenie na pôvodné rozloženie. <br><br>Funkcia <b>Obnoviť predvolené rozloženie</b> obnoví iba umiestnenie poľa v rámci pôvodného rozloženia. Ak chcete zmeniť označenia polí, kliknite na ikonu Editovať vedľa daného poľa.',
        'Default'	=> '<b>Predvolené</b> polia sa vo vyhľadávaní zobrazia.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'Oblasť <b>Rozloženie</b> obsahuje polia, ktoré sa momentálne zobrazujú v časti <b>Zobrazenie podrobností</b>. <br/> <br/> Časť <b>Nástroje</b> obsahuje <b>Kôš</b> a polia a prvky rozloženia, ktoré do rozloženia možno pridať. <br><br>Vykonajte zmeny v rozložení pretiahnutím prvkov a polí medzi časťami <b>Nástroje</b> a <b>Rozloženie</b> a v rámci samotného rozloženia. <br> <br>Ak chcete pole z rozloženia odstrániť, pretiahnite pole do priečinka <b>Kôš</b>. Pole potom bude k dispozícii v časti Nástroje na prípadne pridanie do rozloženia.'
    . '<br/> <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_dependent.png"/> Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca. <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        'defaultquickcreate'=>'Oblasť <b>Rozloženie</b> obsahuje polia, ktoré sa momentálne zobrazujú vo formulári <b>Rýchle vytvorenie</b>.<br><br>Keď kliknete na tlačidlo Vytvoriť, formulár Rýchle vytvorenie sa zobrazí v podpaneloch modulu.<br/><br/> Časť <b>Nástroje</b> obsahuje <b>Kôš</b> a polia a prvky rozloženia, ktoré do rozloženia možno pridať.<br><br>Vykonajte zmeny v rozložení pretiahnutím prvkov a polí medzi časťami <b>Nástroje</b> a <b>Rozloženie</b> a v rámci samotného rozloženia.<br><br>Ak chcete pole z rozloženia odstrániť, pretiahnite pole do priečinka <b>Kôš</b>. Pole potom bude k dispozícii v časti Nástroje na pridanie do rozloženia.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'Oblasť <b>Rozloženie</b> obsahuje polia, ktoré sa momentálne zobrazujú v časti <b>Zobrazenie na úpravy</b>.<br/><br/>Časť <b>Nástroje</b> obsahuje <b>Kôš</b> a polia a prvky rozloženia, ktoré do rozloženia možno pridať.<br><br>Vykonajte zmeny v rozložení pretiahnutím prvkov a polí medzi časťami <b>Nástroje</b> a <b>Rozloženie</b> a v rámci samotného rozloženia.<br><br>Ak chcete pole z rozloženia odstrániť, pretiahnite pole do priečinka <b>Kôš</b>. Pole potom bude k dispozícii v časti Nástroje na pridanie do rozloženia.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'Oblasť <b>Rozloženie</b> obsahuje polia, ktoré sa momentálne zobrazujú v časti <b>Zobrazenie záznamu</b>. <br/> <br/> Časť <b>Nástroje</b> obsahuje <b>Kôš</b> a polia a prvky rozloženia, ktoré do rozloženia možno pridať. <br><br>Vykonajte zmeny v rozložení pretiahnutím prvkov a polí medzi časťami <b>Nástroje</b> a <b>Rozloženie</b> a v rámci samotného rozloženia. <br> <br>Ak chcete pole z rozloženia odstrániť, pretiahnite pole do priečinka <b>Kôš</b>. Pole potom bude k dispozícii v časti Nástroje na prípadne pridanie do rozloženia.'
    . '<br/> <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_dependent.png"/> Označuje závislé pole, ktoré môže alebo nemusí byť viditeľné v závislosti od hodnoty vzorca. <br/> <!--not_in_theme!--> <img src="themes/default/images/SugarLogic/icon_calculated.png" /> Označuje vypočítavané pole, ktorého hodnota sa automaticky určí na základe vzorca.'
    ,
        'saveBtn'	=> 'Kliknutím na možnosť <b>Uložiť</b> zachováte zmeny, ktoré ste vykonali v rozložení od posledného uloženia. <br><br>Zmeny sa v module nezobrazia, pokiaľ uložené zmeny neaktivujete pomocou možnosti Použiť.',
        'historyBtn'=> 'Kliknutím na možnosť <b>Zobraziť históriu</b> zobrazte a obnovte predtým uložené rozloženie z histórie. <br><br>Funkcia <b>Obnoviť</b> v časti <b>Zobraziť históriu</b> obnoví umiestnenie poľa v rámci predtým uložených rozložení. Ak chcete zmeniť označenia poľa, kliknite na ikonu Upraviť vedľa daného poľa.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na možnosť <b>Obnoviť predvolené rozloženie</b> obnovte zobrazenie na pôvodné rozloženie. <br><br>Funkcia <b>Obnoviť predvolené rozloženie</b> obnoví iba umiestnenie poľa v rámci pôvodného rozloženia. Ak chcete zmeniť označenia polí, kliknite na ikonu Editovať vedľa daného poľa.',
        'publishBtn'=> 'Kliknutím na možnosť <b>Uložiť a použiť</b> uložte všetky zmeny vykonané v rozložení od posledného uloženia a aktivujte ich, aby sa v module prejavili. <br><br>Rozloženie sa okamžite zobrazí v module.',
        'toolbox'	=> 'Časť <b>Nástroje</b> obsahuje <b>Kôš</b>, ďalšie prvky rozloženia a súbor dostupných polí, ktoré možno pridať do rozloženia. <br/> <br/> Prvky rozloženia a polia v časti Nástroje môžete pretiahnuť do rozloženia alebo naopak z rozloženia do časti Nástroje. <br><br>Prvky rozloženia delíme na <b>Panely</b> a <b>Riadky</b>. Pridaním nového riadku alebo nového panela do rozloženia získate v rozložení ďalšie umiestnenia pre polia. <br/> <br/> Pretiahnite akékoľvek polia v časti Nástroje alebo rozloženie na pozíciu obsadeného poľa a vymeňte tak umiestnenia týchto dvoch polí. <br/> <br/>Pole <b>Výplň</b> vytvára v rozložení na mieste svojho umiestnenia prázdnu medzeru.',
        'panels'	=> '<b>Rozloženie</b> poskytuje náhľad toho, ako bude rozloženie v module vyzerať, keď sa prejavia zmeny. <br/><br/>Môžete zmeniť umiestnenie polí, riadkov a panelov potiahnutím a vložením na požadované miesto. <br/><br/>Odoberte prvky potiahnutím a vložením do <b>Koša</b> v časti Nástroje alebo pridajte nové prvky alebo polia ich pretiahnutím z časti <b>Nástroje</b> a vložením na požadované miesto v rozmiestnení.',
        'delete'	=> 'Tu pretiahnite akýkoľvek prvok, aby ste ho odstránili z rozloženia',
        'property'	=> 'Upravte <b>Označenie</b> zobrazené pre toto pole. <br><br>Položka <b>Šírka</b> obsahuje hodnotu šírky v pixeloch pre moduly Sidecar a ako percento šírky tabuľky pre spätne kompatibilné moduly.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Tu sú uvedené <b>Polia</b>, ktoré sú k dispozícii pre daný modul, zoradené podľa názvu poľa. <br><br>Vlastné polia vytvorené pre modul sa zobrazia nad poľami, ktoré sú pre modul predvolene k dispozícii. <br> <br>Ak chcete pole upraviť, kliknite na <b>Názov poľa</b>. <br/> <br/> Ak chcete vytvoriť nové pole, kliknite na tlačidlo <b>Pridať pole</b>.',
        'mbDefault'=>'Tu sú uvedené <b>Polia</b>, ktoré sú k dispozícii pre daný modul, zoradené podľa názvu poľa. <br><br>Ak chcete upraviť vlastnosti poľa, kliknite na názov poľa. <br> <br>Ak chcete vytvoriť nové pole, kliknite na možnosť <b>Pridať pole</b>. Označenie spolu s inými vlastnosťami nového poľa možno upraviť po vytvorení kliknutím na názov poľa. <br><br>Po použití modulu sú nové polia vytvorené v nástroji Tvorca modulov považované za štandardné polia v použitom module v nástroji Studio.',
        'addField'	=> 'Vyberte položku <b>Typ dát</b> pre nové pole. Vybraný typ určuje, aký druh znakov možno do poľa zadať. Napríklad do poľa s typom dát Celočíselné je možné zadávať iba celá čísla.<br><br> Zadajte <b>Názov</b> pre pole. Názov musí byť alfanumerická hodnota a nesmie obsahovať medzery. Znaky podčiarknutia sú povolené.<br><br> Položka <b>Zobrazené označenie</b> je označenie, ktorý sa bude zobrazovať pre pole v rozložení modulu. <b>Systémové označenie</b> slúži na identifikáciu poľa v kóde.<br><br> V závislosti od vybratého typu dát pre pole je možné pre pole nastaviť niektoré alebo všetky následujúce vlastnosti:<br><br> <b>Text pomocníka</b> sa zobrazí dočasne, keď používateľ prejde kurzorom na pole, a môže používateľa upozorniť na požadovaný typ vstupu.<br><br> <b>Text komentárov</b> sa zobrazuje len v nástrojoch Studio a/alebo Tvorca modulov a možno ho použiť na popis poľa pre administrátorov.<br><br> V poli sa zobrazí <b>Predvolená hodnota</b>. Používatelia môžu do poľa zadať novú hodnotu alebo použiť predvolenú hodnotu.<br><br> Pomocou začiarkavacieho poľa <b>Hromadná aktualizácia</b> môžete pre pole použiť funkciu hromadnej aktualizácie.<br><br>Hodnota poľa <b>Maximálna veľkosť</b> určuje maximálny počet znakov, ktoré možno do poľa zadať.<br><br> Začiarknutím políčka <b>Povinné pole</b> nastavíte pole ako povinné. Aby bolo možné uložiť záznam obsahujúci toto pole, je potrebné do tohto poľa zadať hodnotu.<br><br> Začiarknutím poľa <b>Reportovateľné</b> povolíte, aby bolo použité na filtrovanie a zobrazovanie dát v Hláseniach.<br><br> Po začiarknutí poľa <b>Audit</b> budete môcť sledovať zmeny poľa v Denníku zmien.<br><br> Začiarknutím poľa <b>Importovateľné</b> povolíte, zakážete nebo vyžiadate import poľa v nástroji Sprievodca importom.<br><br>Vybratím možnosti v poli <b>Zlúčenie duplicitných položiek</b> povolíte alebo zakážete funkcie Zlúčenie duplicitných položiek a Nájsť duplicitné položky.<br><br>Pre niektoré typy dát možno nastaviť aj ďalšie vlastnosti.',
        'editField' => 'Vlastnosti tohto poľa možno upraviť. <br><br>Kliknutím na možnosť <b>Klonovať</b> vytvorte nové pole s rovnakými vlastnosťami.',
        'mbeditField' => '<b>Zobrazené označenie</b> poľa šablóny môžno upraviť. Iné vlastnosti poľa nemožno upraviť. <br><br>Kliknutím na možnosť <b>Klonovať</b> vytvorte nové pole s rovnakými vlastnosťami. <br><br>Ak chcete pole šablóny odstrániť, aby sa v module nezobrazovalo, odoberte toto pole z príslušných <b>Rozložení</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Exportujte prispôsobenia vytvorené v nástroji Studio vytvorením balíkov, ktoré možno nahrať do inej inštancie Sugar prostredníctvom nástroja <b>Načítanie modulov</b>.<br><br>Najprv uveďte <b>Názov  balíka</b>. Môžete uviesť aj <b>Autora</b> a <b>Popis</b> balíka.<br><br>Vyberte moduly, ktoré obsahujú úpravy, ktoré chcete exportovať. Na výber sa zobrazia iba moduly, ktoré obsahujú úpravy. <br><br>Potom kliknutím na možnosť <b>Exportovať</b> vytvorte súbor .zip pre balík, ktorý obsahuje úpravy.',
        'exportCustomBtn'=>'Kliknutím na možnosť <b>Exportovať</b> vytvorte súbor .zip pre balík, ktorý obsahuje úpravy a ktorý chcete exportovať.',
        'name'=>'Toto je <b>Názov</b> balíka, ktorý sa zobrazí počas inštalácie.',
        'author'=>'Toto je <b>Autor</b>, ktorý sa zobrazí počas inštalácie ako meno subjektu, ktorý vytvoril balík. Autor môže byť jednotlivec alebo spoločnosť.',
        'description'=>'Toto je <b>Popis</b> balíka, ktorý sa zobrazí počas inštalácie.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Vitajte v časti pre <b>Vývojárske nástroje</b>.<br/><br/>Využite tieto nástroje v rámci tejto časti na vytvorenie a spravovanie štandardných a upravených modulov a polí.',
        'studioBtn'	=> 'Využite nástroj <b>Studio</b> na upravenie použitých modulov.',
        'mbBtn'		=> 'Využite nástroj <b>Tvorca modulov</b> na vytvorenie nových modulov.',
        'sugarPortalBtn' => 'Využite nástroj <b>Editor Sugar portálu</b> na spravovanie a upravovanie Sugar portálu.',
        'dropDownEditorBtn' => 'Využite nástroj <b>Editor rozbaľovacích ponúk</b> na pridanie a úpravu globálnych rozbaľovacích ponúk pre rozbaľovacie polia.',
        'appBtn' 	=> 'V aplikačnom režime môžete upravovať rôzne vlastnosti programu, ako napríklad: koľko hlásení TPS bude zobrazených na domovskej stránke',
        'backBtn'	=> 'Vrátiť sa na predchádzajúci krok.',
        'studioHelp'=> 'Využite nástroj <b>Studio</b> na určenie typu informácií a spôsobu ich zobrazenia v moduloch.',
        'studioBCHelp' => ' označuje, že ide o spätne kompatibilný modul',
        'moduleBtn'	=> 'Kliknite tu, ak chcete tento modul upraviť.',
        'moduleHelp'=> 'Komponenty, ktoré upravujete pre tento modul, sa zobrazia tu. <br><br>Kliknite na ikonu a vyberte komponent, ktorý chcete upraviť.',
        'fieldsBtn'	=> 'Vytvorením a úpravou <b>Polí</b> môžete uložiť informácie v tomto module.',
        'labelsBtn' => 'Upravte <b>Označenia</b>, ktoré sa zobrazujú pre polia a iné názvy v module.'	,
        'relationshipsBtn' => 'Pridajte nové alebo zobrazte existujúce <b>Vzťahy</b> pre modul.' ,
        'layoutsBtn'=> 'Upravte <b>Rozloženia</b> modulu. Rozloženia sú rôzne zobrazenia modulu obsahujúce polia. <br><br>Môžete určiť, ktoré polia sa zobrazia a ako budú v každom rozložení usporiadané.',
        'subpanelBtn'=> 'Určte, ktoré polia sa zobrazia v <b>Podpaneloch</b> modulu.',
        'portalBtn' =>'Upravte <b>Rozloženie</b> modulu, ktoré sa zobrazujú v <b>Sugar portáli</b>.',
        'layoutsHelp'=> 'Tu sa zobrazujú <b>Rozloženia</b> modulu, ktoré možno upravovať.<br><br>V rozloženiach sa zobrazujú polia a dáta v poliach.<br><br>Kliknutím na ikonu vyberte rozloženie, ktoré chcete upraviť.',
        'subpanelHelp'=> 'Tu sa zobrazujú <b>Podpanely</b> modulu, ktoré možno upravovať.<br><br>Kliknutím na ikonu vyberte modul, ktorý chcete upraviť.',
        'newPackage'=>'Kliknutím na možnosť <b>Nový balík</b> vytvorte nový balík.',
        'exportBtn' => 'Kliknutím na možnosť <b>Exportovať prispôsobenia</b> vytvorte a prevezmite balík obsahujúci úpravy vykonané v nástroji Studio pre konkrétny modul.',
        'mbHelp'    => 'Využite nástroj <b>Tvorca modulov</b> na vytváranie balíkov, ktoré obsahujú vlastné moduly vychádzajúce zo štandardných alebo upravených objektov.',
        'viewBtnEditView' => 'Upravujte rozloženie náhľadu <b>Zobrazenie na úpravy</b> v module.<br><br>Náhľad Zobrazenie na úpravy je formulár obsahujúci vstupné polia na zber údajov vložených používateľmi.',
        'viewBtnDetailView' => 'Upravujte rozloženie náhľadu <b>Zobrazenie podrobností</b> v module.<br><br>Náhľad Zobrazenie podrobností obsahuje údaje zadané do polí používateľmi.',
        'viewBtnDashlet' => 'Upravujte komponent <b>Sugar dashlet</b> v module vrátane náhľadu zoznamu a stránky na vyhľadávanie pre Sugar dashlet.<br><br>Komponent Sugar dashlet bude k dispozícii na pridanie na stránky v module Domov.',
        'viewBtnListView' => 'Upravujte rozloženie <b>Náhľadu zoznamu</b> v module.<br><br>Výsledky vyhľadávania sa zobrazia v Náhľade zoznamu.',
        'searchBtn' => 'Upravujte rozloženie stránky <b>Vyhľadávania</b> v module.<br><br>Určte, ktoré polia môžu byť použité na filtrovanie záznamov, ktoré sa zobrazujú v Náhľade zoznamu.',
        'viewBtnQuickCreate' =>  'Upravujte rozloženie režimu <b>Rýchle vytvorenie</b> v module.<br><br>Formulár Rýchle vytvorenie sa zobrazuje v podpaneloch a v module E-maily.',

        'searchHelp'=> 'Tu sa zobrazia formuláre <b>Vyhľadávania</b>, ktoré možno prispôsobiť. <br><br>Formuláre vyhľadávania obsahujú polia na filtrovanie záznamov. <br><br>Kliknite na ikonu a vyberte rozloženie vyhľadávanie, ktoré chcete upraviť.',
        'dashletHelp' =>'Tu sa zobrazia rozloženia komponentu <b>Sugar dashlet</b>, ktoré možno prispôsobiť.<br><br>Komponent Sugar dashlet bude k dispozícii na pridanie na stránky v module Domov.',
        'DashletListViewBtn' =>'V <b>Sugar dashlete Náhľad zoznamu</b> sa zobrazia záznamy na základe Sugar dashletu Filtre vyhľadávania.',
        'DashletSearchViewBtn' =>'<b>Sugar dashlet Vyhľadávanie</b> filtruje záznamy pre Sugar dashlet Náhľad zoznamu.',
        'popupHelp' =>'Tu sa zobrazia <b>kontextové</b> rozloženia, ktoré možno prispôsobiť. <br>',
        'PopupListViewBtn' => 'Rozloženie <b>Kontextový náhľad zoznamu</b> obsahuje zoznam záznamov, keď vyberiete jeden alebo viac záznamov súvisiacich s aktuálnym záznamom.',
        'PopupSearchViewBtn' => 'Rozloženie <b>Kontextové vyhľadávanie</b> umožňuje používateľom vyhľadávať záznamy, ktoré súvisia s aktuálnym záznamom, a zobrazí sa nad kontextovým náhľadom zoznamu v tom istom okne. V starších moduloch sa toto rozloženie používa na kontextové vyhľadávanie, zatiaľ čo v moduloch Sidecar sa používa konfigurácia rozloženia vyhľadávania.',
        'BasicSearchBtn' => 'Upravujte <b>Základné vyhľadávanie</b>, ktoré sa zobrazuje v karte základného vyhľadávania v oblasti vyhľadávania pre tento modul.',
        'AdvancedSearchBtn' => 'Upravujte <b>Rozšírené vyhľadávanie</b>, ktoré sa zobrazuje v karte rozšíreného vyhľadávania v oblasti vyhľadávania pre tento modul.',
        'portalHelp' => 'Spravujte a upravujte <b>Sugar portál</b>.',
        'SPUploadCSS' => 'Nahrajte <b>Šablónu štýlov</b> pre Sugar portál.',
        'SPSync' => '<b>Synchronizujte</b> úpravy inštancie Sugar portálu.',
        'Layouts' => 'Upravujte <b>Rozloženia</b> modulov Sugar portálu.',
        'portalLayoutHelp' => 'Tu sa zobrazujú moduly v Sugar portáli.<br><br>Vyberte modul, v ktorom chcete vykonať úpravy <b>Rozloženiach</b>.',
        'relationshipsHelp' => 'Všetky <b>Vzťahy</b>, ktoré existujú medzi modulom a inými aktivovanými modulmi, sa zobrazia tu. <br><br>Vzťah <b>Názov</b> predstavuje systémom vygenerovaný názov pre vzťah. <br> <br><b>Hlavný modul</b> je modul, ktorý vlastní vzťahy. Napríklad všetky vlastnosti vzťahov, pre ktoré je modul Účty hlavným modulom, sú uložené v tabuľkách databázy Účty. <br><br><b>Typ</b> je typ vzťahu medzi hlavným modulom a <b>Súvisiacim modulom</b>. <br> <br>Kliknite na názov stĺpca, ak chcete vykonať zoradenie podľa stĺpcov. <br><br>Kliknite na riadok v tabuľke vzťahu, ak chcete zobraziť vlastnosti súvisiace so vzťahom. <br> <br>Kliknutím na tlačidlo <b>Pridať vzťah</b> a vytvoríte nový vzťah. <br><br>Je možné vytvárať vzťahy medzi ľubovoľnými dvoma aktivovanými modulmi.',
        'relationshipHelp'=>'<b>Vzťahy</b> možno vytvárať medzi modulom a inými aktivovanými modulmi. <br><br>Vzťahy sú vizuálne vyjadrené prostredníctvom podpanelov a polí súvislostí v záznamoch modulu. <br> <br>Vyberte jeden z nasledujúcich <b>Typov</b> vzťahov pre modul: <br><br><b>1: 1</b> – záznamy z oboch modulov budú obsahovať pole súvislostí. <br> <br><b>1: N</b> – záznam hlavného modulu bude obsahovať podpanel a záznam súvisiaceho modulu bude obsahovať pole súvislostí. <br><br><b>N: N</b> – záznamy oboch modulov budú obsahovať podpanely. <br> <br>Vyberte <b>Súvisiaci modul</b> pre vzťah. <br><br>Ak typ vzťahu zahŕňa podpanely, vyberte zobrazenie podpanelov pre príslušné moduly. <br> <br>Kliknutím na možnosť <b>Uložiť</b> vytvoríte vzťah.',
        'convertLeadHelp' => "Tu môžete pridávať moduly na obrazovku rozloženia prevodu a upravovať nastavenia existujúcich modulov.<br/><br/>
<b>Poradie:</b><br/>
Kontakty, Účty a Príležitosti musia obsahovať svoje poradie. Poradie akéhokoľvek iného modulu môžete zmeniť pretiahnutím jeho riadku v tabuľke.<br/><br/>
<b>Závislosť:</b><br/>
Ak sú zahrnuté Príležitosti, je treba v rozložení prevodu buď požadovať Účty, alebo Účty odstrániť.<br/><br/>
<b>Modul:</b> Názov modulu.<br/><br/>
<b>Povinné:</b> Povinné moduly je treba vytvoriť nebo vybrať skôr, než je možné previesť záujemcu.<br/><br/>
 <b>Kopírovať dáta:</b> Ak je táto položka začiarknutá, polia od záujemcu sa skopírujú do polí s rovnakým názvom v novo vytvorených záznamoch.<br/><br/>
<b>Vymazať:</b> Odstrániť tento modul z rozloženia prevodu.<br/><br/>        ",
        'editDropDownBtn' => 'Upraviť globálne rozbaľovacie zoznamy',
        'addDropDownBtn' => 'Pridať nový rozbaľovací zoznam',
    ),
    'fieldsHelp'=>array(
        'default'=>'Tu sú uvedené <b>Polia</b> v module podľa názvu poľa. <br><br>Šablóna modulu obsahuje preddefinovaný súbor polí. <br> <br>Ak chcete vytvoriť nové pole, kliknite na tlačidlo <b>Pridať pole</b>. <br><br>Ak chcete pole upraviť, kliknite na <b>Názov poľa</b>. <br/> <br/> Po použití modulu sú nové polia vytvorené v nástroji Tvorca modulov spolu s poľami šablóny považované za štandardné polia v nástroji Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>Vzťahy</b>, ktoré boli vytvorené medzi modulom a inými modulmi, sa zobrazia tu. <br><br>Vzťah <b>Názov</b> predstavuje systémom vygenerovaný názov pre vzťah. <br> <br><b>Hlavný modul</b> je modul, ktorý vlastní vzťahy. Vlastnosti vzťahov sa ukladajú do tabuliek databázy prislúchajúcich k hlavnému modulu. <br><br><b>Typ</b> je typ vzťahu medzi hlavným modulom a <b>Súvisiacim modulom</b>. <br> <br>Kliknite na názov stĺpca, ak chcete vykonať zoradenie podľa stĺpcov. <br><br>Kliknite na riadok v tabuľke vzťahu, ak chcete zobraziť a upraviť vlastnosti súvisiace so vzťahom. <br> <br>Kliknutím na tlačidlo <b>Pridať vzťah</b> a vytvoríte nový vzťah.',
        'addrelbtn'=>'myš nad pomocníkom pre pridanie vzťahu...',
        'addRelationship'=>'<b>Vzťahy</b> možno vytvárať medzi modulom a iným vlastným modulom alebo aktivovaným modulom. <br><br>Vzťahy sú vizuálne vyjadrené prostredníctvom podpanelov a polí súvislostí v záznamoch modulu. <br> <br>Vyberte jeden z nasledujúcich <b>Typov</b> vzťahov pre modul: <br><br><b>1: 1</b> – záznamy z oboch modulov budú obsahovať pole súvislostí. <br> <br><b>1: N</b> – záznam hlavného modulu bude obsahovať podpanel a záznam súvisiaceho modulu bude obsahovať pole súvislostí. <br><br><b>N: N</b> – záznamy oboch modulov budú obsahovať podpanely. <br> <br>Vyberte <b>Súvisiaci modul</b> pre vzťah. <br><br>Ak typ vzťahu zahŕňa podpanely, vyberte zobrazenie podpanelov pre príslušné moduly. <br> <br>Kliknutím na možnosť <b>Uložiť</b> vytvoríte vzťah.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Označenia</b> polí a ďalšie názvy v module je možné zmeniť. <br><br>Kliknutím do poľa a vložením nového označenia upravte označenie a potom kliknite na možnosť <b>Uložiť</b>. <br> <br>Ak sú v aplikácii nainštalované jazykové balíky, môžete vybrať aj <b>Jazyk</b>, ktorý sa bude pre označenia používať.',
        'saveBtn'=>'Kliknutím na možnosť <b>Uložiť</b> uložte všetky zmeny.',
        'publishBtn'=>'Kliknutím na možnosť <b>Uložiť a použiť</b> uložíte všetky zmeny a zmeny sa prejavia.',
    ),
    'portalSync'=>array(
        'default' => 'Zadajte adresu <b>URL Sugar portálu</b> pre danú inštanciu portálu a vykonajte aktualizáciu. Potom kliknite na položku <b>Spustiť</b>. <br><br>Zadajte platné meno používateľa a heslo Sugar a potom kliknite na možnosť <b>Spustiť synchronizáciu</b>. <br> <br>Prispôsobenia vykonané v <b>Rozloženiach</b> Sugar portálu spolu so <b>Šablónou štýlu</b>, ak bola nejaká nahratá, budú prevedené do uvedenej inštancie portálu.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Vzhľad Sugar portálu môžete prispôsobiť pomocou šablóny štýlu. <br><br>Vyberte <b>Šablónu štýlu</b> a spustite nahrávanie. <br> <br>Šablóna štýlu sa pri ďalšej synchronizácii implementuje do Sugar portálu.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Ak chcete začať pracovať na projekte, kliknite na možnosť <b>Nový balík</b> a vytvorte nový balík pre vlastné moduly. <br/> <br/> Každý balík môže obsahovať jeden alebo viac modulov. <br/> <br/> Napríklad: možno budete chcieť vytvoriť balík obsahujúci jeden vlastný modul, ktorý súvisí so štandardným modulom Účty. Alebo budete chcieť vytvoriť balík obsahujúci niekoľko nových modulov, ktoré pracujú spoločne ako projekt a ktoré súvisia navzájom medzi sebou, ako aj s ďalšími modulmi v aplikácii.',
            'somepackages'=>'<b>Balík</b> sa chová ako kontajner pre vlastné moduly, ktoré sú súčasťou jedného projektu. Balík môže obsahovať jeden alebo viac vlastných <b>modulov</b>, ktoré môžu súvisieť navzájom alebo s inými modulmi v aplikácii. <br/> <br/>Po vytvorení balíka pre svoj projekt môžete hneď vytvoriť aj moduly pre balík alebo sa môžete vrátiť do nástroja Tvorca modulov neskôr a projekt dokončiť. <br><br>Po dokončení projektu je možné <b>aktivovať</b> balík a nainštalovať vlastné moduly v aplikácii.',
            'afterSave'=>'Nový balík by mal obsahovať aspoň jeden modul. Môžete vytvoriť jeden alebo viac vlastných modulov pre balík. <br/> <br/> Kliknutím na možnosť <b>Nový modul</b> vytvorte vlastný modul pre tento balík. <br/> <br/> Po vytvorení aspoň jedného modulu môžete zverejniť alebo aktivovať balík a sprístupniť ho pre inštanciu a/alebo inštancie ostatných používateľov . <br/> <br/> Ak chcete balík aktivovať v jednom kroku v rámci inštancie Sugar, kliknite na možnosť <b>Použiť</b>. <br><br>Kliknite na možnosť <b>Zverejniť</b> a uložte balík vo formáte súboru .zip. Po uložení súboru .zip do systému nahrajte a nainštalujte balík v inštancii Sugar pomocou nástroja <b>Načítanie modulov</b>.  <br/> <br/> Súbor môžete distribuovať ostatným používateľom na nahratie a inštaláciu v ich vlastných inštanciách Sugar.',
            'create'=>'<b>Balík</b> sa chová ako kontajner pre vlastné moduly, ktoré sú súčasťou jedného projektu. Balík môže obsahovať jeden alebo viac vlastných <b>modulov</b>, ktoré môžu súvisieť navzájom alebo s inými modulmi v aplikácii. <br/> <br/>Po vytvorení balíka pre svoj projekt môžete hneď vytvoriť aj moduly pre balík alebo sa môžete vrátiť do nástroja Tvorca modulov neskôr a projekt dokončiť.',
            ),
    'main'=>array(
        'welcome'=>'Využívajte <b>Vývojárske nástroje</b> na vytváranie a spravovanie štandardných a vlastných polí a modulov. <br/> <br/> Ak chcete spravovať moduly v aplikácii, kliknite na tlačidlo <b>Studio</b>. <br/> <br/> Ak chcete vytvoriť vlastné moduly, kliknite na možnosť <b>Tvorca modulov</b>.',
        'studioWelcome'=>'Všetky aktuálne nainštalované moduly vrátane štandardných objektov a objektov nahratých v moduloch sa dajú v nástroji Studio upraviť.'
    ),
    'module'=>array(
        'somemodules'=>"Keďže aktuálny balík obsahuje aspoň jeden modul, môžete <b>Použiť</b> moduly v balíku v rámci inštancie Sugar alebo <b>Zverejniť</b> balík na inštaláciu v aktuálnej inštancii Sugar alebo inej inštancii pomocou nástroja <b>Načítanie modulov</b>. <br/> <br/> Ak chcete nainštalovať balík priamo v inštancii Sugar, kliknite na možnosť <b>Použiť</b>. <br><br>Ak chcete vytvoriť súbor .zip pre balík, ktorý možno nahrať a inštalovať do aktuálnej inštancie Sugar a iných inštancií pomocou nástroja <b>Načítanie modulov</b>, kliknite na možnosť <b>Zverejniť</b>. <br/> <br/> Moduly pre tento balík môžete vytvárať v etapách a zverejňovať alebo aktivovať ich, keď ste pripravení. <br/> <br/> Po zverejnení alebo aktivovaní balíka môžete vykonať zmeny vlastností balíka a moduly ďalej upravovať. Potom stačí znova zverejniť alebo aktivovať balík, aby sa zmeny prejavili." ,
        'editView'=> 'Tu môžete upravovať existujúce polia. Môžete odstrániť ktorékoľvek existujúce polia alebo pridať dostupné polia v ľavom paneli.',
        'create'=>'Pri výbere <b>Typu</b> modulu, ktorý chcete vytvoriť, majte na pamäti typy polí, ktoré chcete zakomponovať do modulu. <br/> <br/> Každá šablóna modulu obsahuje súbor polí týkajúci sa typu modulu opísaného v názve. <br/> <br/> <b>Základné</b> – obsahuje základné polia, ktoré sa zobrazia v štandardných moduloch, ako napríklad názov, priradené komu, tím, dátum vytvorenia a popis polí. <br/> <br/> <b>Spoločnosť</b> – obsahuje polia, ktoré sú pre danú organizáciu špecifické, napríklad názov spoločnosti, odvetvie a fakturačnú adresu.  Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardnému modulu Účty. <br/> <br/> <b>Osoba</b> – obsahuje polia špecifické pre jednotlivcov, napríklad oslovenie, titul, meno, adresu a telefónne číslo.  Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardným modulom Kontakty a Záujemcovia. <br/> <br/> <b>Problém</b> – obsahuje špecifické polia pre udalosti a chyby, napríklad počet, stav, prioritu a popis. Použite túto šablónu na vytvorenie modulov, ktoré sú podobné štandardným modulom Prípady a Stopovač chýb. <br/> <br/> Poznámka: Po vytvorení modulu môžete upraviť označenia polí uvedených v šablóne, ako aj vytvoriť vlastné polia na pridanie do rozložení modulu.',
        'afterSave'=>'Prispôsobte modul, aby vyhovoval vašim potrebám: upravujte a vytvárajte polia, vytvárajte vzťahy s inými modulmi a usporiadajte polia v rozloženiach. <br/> <br/> Ak chcete zobraziť polia šablóny a spravovať vlastné polia modulu, kliknite na možnosť <b>Zobraziť polia</b>. <br/> <br/> Ak chcete vytvoriť a spravovať vzťahy medzi modulom a ostatnými modulmi, či už modulmi v aplikácii alebo inými vlastnými modulmi v rovnakom balíku, kliknite na možnosť <b>Zobraziť vzťahy</b>. <br/> <br/> Ak chcete upraviť rozloženia modulu, kliknite na položku <b>Rozloženia zobrazenia</b>. Môžete zmeniť zobrazenie podrobností, zobrazenie na úpravy a náhľad zoznamu pre modul, ako aj pre už dostupné moduly v aplikácii v rámci nástroja Studio. <br/> <br/> Ak chcete vytvoriť modul s rovnakými vlastnosťami ako aktuálny modul, kliknite na možnosť <b>Duplikovať</b>. Nový modul môžete ďalej prispôsobiť.',
        'viewfields'=>'Polia v module možno prispôsobiť, aby vyhovovali vašim potrebám. <br/> <br/> Nemôžete vymazať štandardné polia, ale môžete ich odstrániť z príslušných rozložení v rámci stránok. <br/> <br/>Môžete rýchlo vytvoriť nové polia, ktoré majú podobné vlastnosti ako existujúce polia, stačí kliknúť na možnosť <b>Klonovať</b> vo formulári <b>Vlastnosti</b>.  Zadajte akékoľvek nové vlastnosti a potom kliknite na tlačidlo <b>Uložiť</b>. <br/> <br/>Odporúča sa, aby ste nastavili všetky vlastnosti pre štandardné polia a vlastné polia pred zverejnením a inštaláciou balíka obsahujúceho vlastný modul.',
        'viewrelationships'=>'Medzi aktuálnym modulom a ostatnými modulmi a/alebo medzi aktuálnym modulom a modulmi už nainštalovanými v aplikácii môžete vytvárať vzťahy N: N.<br><br> Ak chcete vytvoriť vzťahy 1: N a 1: 1, vytvorte pre moduly polia typu <b>Vytvoriť súvislosť</b> a <b>Univerzálna súvislosť</b>.',
        'viewlayouts'=>'Môžete určiť, ktoré polia sú k dispozícii na zber údajov v rámci <b>Zobrazenia na úpravy</b>.  Môžete takisto určiť, aké údaje sa zobrazia v <b>Zobrazení podrobností</b>.  Zobrazenia sa nemusia zhodovať. <br/> <br/> Keď kliknete na možnosť <b>Vytvoriť</b> v subpaneli modulu, zobrazí sa formulár rýchleho vytvorenia. V predvolenom nastavení je rozloženie formulára <b>Rýchleho vytvorenia</b> rovnaké ako predvolené rozloženie <b>Zobrazenia na úpravy</b>. Formulár Rýchleho vytvorenia môžete prispôsobiť tak, aby obsahoval menej a/alebo iné polia ako rozloženie Zobrazenia na úpravy. <br><br>Môžete určiť zabezpečenie modulu pomocou možnosti prispôsobenia rozloženia, ako aj <b>Správy rol</b>. <br> <br>',
        'existingModule' =>'Po vytvorení a prispôsobení tohto modulu môžete vytvoriť ďalšie moduly alebo sa vrátiť do balíka a balík <b>Zverejniť</b> alebo <b>Použiť</b>.<br><br>Ak chcete vytvoriť ďalšie moduly, kliknite na tlačidlo <b>Duplikovať</b> a vytvorte modul s rovnakými vlastnosťami ako aktuálny modul alebo prejdite späť do balíka a kliknite na tlačidlo <b>Nový modul</b>.<br><br>Ak už chcete <b>Zverejniť</b> alebo <b>Použiť</b> balík obsahujúci tento modul, prejdite späť do balíka a vykonajte tieto operácie. Zverejniť a aktivovať môžete len balíky obsahujúce aspoň jeden modul.',
        'labels'=> 'Označenia štandardných polí, ako aj vlastných polí je možné zmeniť.  Zmena označenia polí neovplyvní údaje uložené v poliach.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'K dispozícii sú tri stĺpce zobrazené naľavo. Stĺpec Predvolené obsahuje polia predvolene zobrazené v náhľade zoznamu, stĺpec Dostupné obsahuje polia, ktoré si používateľ môže zvoliť na vytvorenie vlastného náhľadu zoznamu a stĺpec Skryté obsahuje polia dostupné pre vás ako administrátora buď na pridanie do predvolených nastavení alebo stĺpca Dostupné na použitie pre používateľov, pričom momentálne sú tieto polia blokované.',
        'savebtn'	=> 'Kliknutím na možnosť <b>Uložiť</b> uložíte všetky zmeny a zmeny sa prejavia.',
        'Hidden' 	=> 'Skryté polia sú polia, ktoré momentálne nie sú k dispozícii pre používateľov na použitie v náhľadoch zoznamu.',
        'Available' => 'Dostupné polia sú polia, ktoré nie sú v predvolenom nastavení, ale používatelia ich môžu povoliť.',
        'Default'	=> 'Predvolené polia sa zobrazujú používateľom, ktorí nedefinovali vlastné nastavenia náhľadu zoznamu.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'K dispozícii sú dva stĺpce zobrazené vľavo. Stĺpec "Predvolené" obsahuje polia, ktoré sa zobrazia v zobrazení vyhľadávania a stĺpec "Skryté" obsahuje polia, ktoré sú k dispozícii pre vás ako administrátora na pridanie do zobrazenia.',
        'savebtn'	=> 'Kliknutím na možnosť <b>Uložiť a použiť</b> uložíte všetky zmeny a zmeny sa prejavia.',
        'Hidden' 	=> 'Skryté polia sú polia, ktoré sa nezobrazia v zobrazení vyhľadávania.',
        'Default'	=> 'Predvolené polia sa zobrazia v zobrazení vyhľadávania.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'K dispozícii sú dva stĺpce zobrazené vľavo. Stĺpec napravo označený ako Aktuálne rozloženie alebo Náhľad rozloženia je stĺpec, v ktorom môžete zmeniť rozloženie modulu. Ľavý stĺpec označený ako Nástroje obsahuje užitočné prvky a nástroje na použitie pri úprave rozloženia. <br/> <br/> Ak sa časť rozloženia nazýva Aktuálne rozloženie, znamená to, že pracujete na kópii rozloženie, ktoré sa momentálne používa v module na zobrazenie. <br/> <br/> Ak sa časť rozloženia nazýva Náhľad rozloženia, znamená to, že pracujete na kópii vytvorenej pred kliknutím na tlačidlo Uložiť, ktorá sa už mohla zmeniť od verzie, ktorá sa zobrazuje používateľom tohto modulu.',
        'saveBtn'	=> 'Kliknutím na toto tlačidlo sa uloží rozloženie, aby ste mohli zachovať zmeny. Po návrate do tohto modulu začnete v tomto zmenenom rozložení. Vaše rozloženia však nebude viditeľné pre používateľov daného modulu, pokým nekliknite na tlačidlo Uložiť a Zverejniť.',
        'publishBtn'=> 'Kliknutím na toto tlačidlo rozloženie použijete. Rozloženie bude okamžite viditeľné pre používateľov tohto modulu.',
        'toolbox'	=> 'Nástroje obsahujú celý rad užitočných funkcií pre úpravy rozloženia vrátane priečinka koša, súboru ďalších prvkov a dostupných polí. Ktorékoľvek z nich možno pretiahnuť a vložiť do rozloženia.',
        'panels'	=> 'Táto oblasť zobrazuje, ako sa bude vaše rozloženie zobrazovať používateľom tohto modulu, keď ho aktivujete. <br/> <br/> Prvky, ako sú napríklad polia, riadky a panely, môžete premiestniť pretiahnutím, odstrániť pretiahnutím a vložením do priečinka Kôš v časti Nástroje alebo pridať nové ich pretiahnutím z časti Nástroje a vložením do rozloženia v požadovanej polohe.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'K dispozícii sú dva stĺpce zobrazené vľavo. Stĺpec napravo označený ako Aktuálne rozloženie alebo Náhľad rozloženia je stĺpec, v ktorom môžete zmeniť rozloženie modulu. Ľavý stĺpec označený ako Nástroje obsahuje užitočné prvky a nástroje na použitie pri úprave rozloženia. <br/> <br/> Ak sa časť rozloženia nazýva Aktuálne rozloženie, znamená to, že pracujete na kópii rozloženie, ktoré sa momentálne používa v module na zobrazenie. <br/> <br/> Ak sa časť rozloženia nazýva Náhľad rozloženia, znamená to, že pracujete na kópii vytvorenej pred kliknutím na tlačidlo Uložiť, ktorá sa už mohla zmeniť od verzie, ktorá sa zobrazuje používateľom tohto modulu.',
        'dropdownaddbtn'=> 'Kliknutím na toto tlačidlo pridáte novú položku do rozbaľovacej ponuky.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Prispôsobenia v vykonané v nástroji Studio v tejto inštancii môžu byť zabalené a aktivované v inej inštancii.  <br><br>Uveďte <b>Názov balíka</b>. Môžete uviesť aj <b>Autora</b> a <b>Popis</b> balíka. <br><br>Vyberte moduly, ktoré obsahujú prispôsobenia, ktoré chcete exportovať. (Na výber sa vám zobrazia iba moduly obsahujúce prispôsobenia.) <br><br>Kliknite na tlačidlo <b>Exportovať</b> a vytvorte súbor .zip pre balík obsahujúci prispôsobenia. Súbor .zip môže byť nahratý do inej inštancie pomocou nástroja <b>Načítanie modulov</b>.',
        'exportCustomBtn'=>'Kliknutím na možnosť <b>Exportovať</b> vytvorte súbor .zip pre balík, ktorý obsahuje úpravy a ktorý chcete exportovať.
',
        'name'=>'<b>Názov</b> balíka sa zobrazí v nástroji Načítanie modulov po nahratí balíka na inštaláciu v nástroji Studio.',
        'author'=>'<b>Autor</b> je názov subjektu, ktorý vytvoril balíček. Autorom môže byť jednotlivec alebo spoločnosť. <br><br>Autor sa zobrazí v nástroji Načítanie modulov po nahratí balíka na inštaláciu v nástroji Studio.
',
        'description'=>'<b>Popis</b> balíka sa zobrazí v nástroji Načítanie modulov po nahratí balíka na inštaláciu v nástroji Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Vitajte v časti pre <b>Vývojárske nástroje</b1>.<br/><br/>Využite tieto nástroje v rámci tejto časti na vytvorenie a spravovanie štandardných a upravených modulov a polí.',
        'studioBtn'	=> 'Využite nástroj <b>Studio</b> na prispôsobenie nainštalovaných modulov zmenou usporiadania polí, výberom polí, ktoré sú k dispozícii, a vytváraním vlastných polí s údajmi.',
        'mbBtn'		=> 'Využite nástroj <b>Tvorca modulov</b> na vytvorenie nových modulov.',
        'appBtn' 	=> 'V aplikačnom režime môžete upravovať rôzne vlastnosti programu, ako napríklad: koľko hlásení TPS bude zobrazených na domovskej stránke',
        'backBtn'	=> 'Vrátiť sa na predchádzajúci krok.',
        'studioHelp'=> 'Využite nástroj <b>Studio</b> na upravenie nainštalovaných modulov.',
        'moduleBtn'	=> 'Kliknite tu, ak chcete tento modul upraviť.',
        'moduleHelp'=> 'Vyberte komponent modulu, ktorý chcete upraviť',
        'fieldsBtn'	=> 'Nastavte, aké informácie sa ukladajú v module spravovaním <b>Polí</b> v module. <br/> <br/>Tu môžete aj upravovať a vytvárať vlastné polia.',
        'layoutsBtn'=> 'Prispôsobte <b>Rozloženia</b> v zobrazení úprav, podrobností, zoznamu a vyhľadávania.',
        'subpanelBtn'=> 'Nastavte, aké informácie sa zobrazia v podpaneloch modulov.',
        'layoutsHelp'=> 'Vyberte <b>Rozloženie na upravenie</b>. <br/<br/>Ak chcete zmeniť rozloženie, ktoré obsahuje polia na zadávanie údajov, kliknite na položku <b>Zobrazenie na úpravy</b>. <br/> <br/> Ak chcete zmeniť rozloženie, v ktorom sa zobrazujú údaje zadané do polí v Zobrazení na úpravy, kliknite na možnosť <b>Zobrazenie podrobností</b>. <br/> <br/>Ak chcete zmeniť stĺpce, ktoré sa zobrazujú v predvolenom zozname, kliknite na položku <b>Náhľad zoznamu</b>. <br/> <br/>Ak chcete zmeniť rozloženie základného a pokročilého vyhľadávania, kliknite na tlačidlo <b>Vyhľadávanie</b>.',
        'subpanelHelp'=> 'Vyberte <b>Podpanel</b>, ktorý chcete upraviť.',
        'searchHelp' => 'Vyberte rozloženie <b>Vyhľadávania</b>, ktoré chcete upraviť.',
        'labelsBtn'	=> 'Upravte <b>Označenia</b> a zobrazte hodnoty v tomto module.',
        'newPackage'=>'Kliknutím na možnosť <b>Nový balík</b> vytvorte nový balík.',
        'mbHelp'    => '<b>Vitajte v nástroji Tvorca modulov.</b> <br/> <br/> Pomocou nástroja <b>Tvorca modulov</b> môžete vytvoriť balíky obsahujúce vlastné moduly, ktoré sú založené na štandardných alebo vlastných objektov. <br/> <br/> Ak chcete začať, kliknite na možnosť <b>Nový balík</b> a vytvorte nový balík alebo vyberte balík na úpravu.<br/> <br/> <b>Balík</b> sa chová ako kontajner pre vlastné moduly, ktoré sú súčasťou jedného projektu. Balík môže obsahovať jeden alebo viac vlastných modulov, ktoré môžu súvisieť navzájom alebo s inými modulmi v aplikácii. <br/> <br/> Príklady: Možno budete chcieť vytvoriť balík obsahujúci jeden vlastný modul, ktorý súvisí so štandardným modulom Účty. Alebo budete chcieť vytvoriť balík obsahujúci niekoľko nových modulov, ktoré fungujú spoločne ako projekt a ktoré súvisia navzájom medzi sebou a s inými modulmi v aplikácii.',
        'exportBtn' => 'Kliknutím na možnosť <b>Exportovať prispôsobenia</b> vytvorte balík obsahujúci úpravy vykonané v nástroji Studio pre konkrétny modul.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor rozbaľovacieho zoznamu',

//ASSISTANT
'LBL_AS_SHOW' => 'V budúcnosti zobraziť pomocníka.',
'LBL_AS_IGNORE' => 'V budúcnosti ignorovať pomocníka.',
'LBL_AS_SAYS' => 'Pomocník hovorí:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Tvorca modulov',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor rozbaľovacieho zoznamu',
'LBL_EDIT_DROPDOWN'=>'Úprava rozbaľovacieho zoznamu',
'LBL_DEVELOPER_TOOLS' => 'Vývojárske nástroje',
'LBL_SUGARPORTAL' => 'Editor Sugar portálu',
'LBL_SYNCPORTAL' => 'Synchronizácia portálu',
'LBL_PACKAGE_LIST' => 'Zoznam balíkov',
'LBL_HOME' => 'Domov',
'LBL_NONE'=>'--žiadny--',
'LBL_DEPLOYE_COMPLETE'=>'Použitie bolo dokončené',
'LBL_DEPLOY_FAILED'   =>'Počas procesu použitia sa vyskytla chyba, váš balík nebol správne nainštalovaný',
'LBL_ADD_FIELDS'=>'Pridať vlastné polia',
'LBL_AVAILABLE_SUBPANELS'=>'Dostupné podpanely',
'LBL_ADVANCED'=>'Pokročilé',
'LBL_ADVANCED_SEARCH'=>'Pokročilé vyhľadávanie',
'LBL_BASIC'=>'Základné',
'LBL_BASIC_SEARCH'=>'Základné vyhľadávanie',
'LBL_CURRENT_LAYOUT'=>'Rozloženie',
'LBL_CURRENCY' => 'Mena',
'LBL_CUSTOM' => 'Vlastné',
'LBL_DASHLET'=>'Sugar Dashlet',
'LBL_DASHLETLISTVIEW'=>'Sugar dashlet Náhľad zoznamu',
'LBL_DASHLETSEARCH'=>'Sugar dashlet Vyhľadávanie',
'LBL_POPUP'=>'Kontextové zobrazenie',
'LBL_POPUPLIST'=>'Kontextový náhľad zoznamu',
'LBL_POPUPLISTVIEW'=>'Kontextový náhľad zoznamu',
'LBL_POPUPSEARCH'=>'Kontextová vyhľadávanie',
'LBL_DASHLETSEARCHVIEW'=>'Sugar dashlet Vyhľadávanie',
'LBL_DISPLAY_HTML'=>'Zobraziť HTML kód',
'LBL_DETAILVIEW'=>'Zobrazenie podrobností',
'LBL_DROP_HERE' => '[Vložiť tu]',
'LBL_EDIT'=>'Upraviť',
'LBL_EDIT_LAYOUT'=>'Upraviť rozloženie',
'LBL_EDIT_ROWS'=>'Upraviť riadky',
'LBL_EDIT_COLUMNS'=>'Upraviť stĺpce',
'LBL_EDIT_LABELS'=>'Upraviť označenia',
'LBL_EDIT_PORTAL'=>'Upraviť portál pre',
'LBL_EDIT_FIELDS'=>'Upraviť  polia',
'LBL_EDITVIEW'=>'Zobrazenie na úpravy',
'LBL_FILTER_SEARCH' => "Vyhľadávanie",
'LBL_FILLER'=>'(výplň)',
'LBL_FIELDS'=>'Polia',
'LBL_FAILED_TO_SAVE' => 'Neúspešný pokus o uloženie',
'LBL_FAILED_PUBLISHED' => 'Neúspešný pokus o zverejnenie',
'LBL_HOMEPAGE_PREFIX' => 'Moje',
'LBL_LAYOUT_PREVIEW'=>'Náhľad rozloženia',
'LBL_LAYOUTS'=>'Rozloženia',
'LBL_LISTVIEW'=>'Náhľad zoznamu',
'LBL_RECORDVIEW'=>'Zobrazenie záznamu',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Nový balík',
'LBL_NEW_PANEL'=>'Nový panel',
'LBL_NEW_ROW'=>'Nový riadok',
'LBL_PACKAGE_DELETED'=>'Balík vymazaný',
'LBL_PUBLISHING' => 'Prebieha zverejnenie...',
'LBL_PUBLISHED' => 'Zverejnené',
'LBL_SELECT_FILE'=> 'Vybrať súbor',
'LBL_SAVE_LAYOUT'=> 'Uložiť rozloženie',
'LBL_SELECT_A_SUBPANEL' => 'Vybrať podpanel',
'LBL_SELECT_SUBPANEL' => 'Vybrať podpanel',
'LBL_SUBPANELS' => 'Podpanely',
'LBL_SUBPANEL' => 'Podpanel',
'LBL_SUBPANEL_TITLE' => 'Názov:',
'LBL_SEARCH_FORMS' => 'Vyhľadávanie',
'LBL_STAGING_AREA' => 'Príprava (pretiahnite a vložte položky tu)',
'LBL_SUGAR_FIELDS_STAGE' => 'Polia aplikácie Sugar (kliknite na položky a pridajte ich do priečinka na prípravu)',
'LBL_SUGAR_BIN_STAGE' => 'Kôš aplikácie Sugar (kliknite na položky a pridajte ich do priečinka na prípravu)',
'LBL_TOOLBOX' => 'Nástroje',
'LBL_VIEW_SUGAR_FIELDS' => 'Zobraziť polia aplikácie Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Zobraziť kôš aplikácie Sugar',
'LBL_QUICKCREATE' => 'Rýchle vytvorenie',
'LBL_EDIT_DROPDOWNS' => 'Upraviť globálne rozbaľovacie zoznamy',
'LBL_ADD_DROPDOWN' => 'Pridať nový rozbaľovací zoznam',
'LBL_BLANK' => '-čistý-',
'LBL_TAB_ORDER' => 'Poradie záložiek',
'LBL_TAB_PANELS' => 'Povoliť záložky',
'LBL_TAB_PANELS_HELP' => 'Po zapnutí záložiek používajte "typ" rozbaľovacieho poľa<br />pre každú sekciu a definujte, ako bude zobrazená (záložka alebo panel)',
'LBL_TABDEF_TYPE' => 'Typ zobrazenia',
'LBL_TABDEF_TYPE_HELP' => 'Vyberte, ako by sa mala táto sekcia zobraziť. Táto možnosť sa prejaví len, ak ste povolili záložky v tomto zobrazení.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Záložka',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Vyberte panel, aby bol tento panel zobrazený v rozložení. Vyberte záložku, aby sa tento panel zobrazil v samostatnej záložke v rámci rozloženia. Keď je pre panel vybratá záložka, ďalšie panely nastavené na zobrazenie ako panel sa zobrazia v rámci záložky.<br/>Nová záložka sa spustí pre ďalší panel, pre ktorý bola vybratá záložka. Ak je pre panel vybratá záložka pod prvým panelom, prvý panel bude automaticky záložkou.',
'LBL_TABDEF_COLLAPSE' => 'Zbaliť',
'LBL_TABDEF_COLLAPSE_HELP' => 'Vyberte túto možnosť, aby ste nastavili východiskový stav tohto panelu ako zbalený.',
'LBL_DROPDOWN_TITLE_NAME' => 'Názov',
'LBL_DROPDOWN_LANGUAGE' => 'Jazyk',
'LBL_DROPDOWN_ITEMS' => 'Zoznam položiek',
'LBL_DROPDOWN_ITEM_NAME' => 'Názov položky',
'LBL_DROPDOWN_ITEM_LABEL' => 'Označenie zobrazenia',
'LBL_SYNC_TO_DETAILVIEW' => 'Synchronizovať so Zobrazením podrobností',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Výberom tejto možnosti dôjde k synchronizácii rozloženia tohto Zobrazenia na úpravy, aby zodpovedalo rozloženiu Zobrazenia podrobností. Polia a umiestnenie polí v Zobrazení na úpravy<br>budú automaticky synchronizované a uložené do Zobrazenia podrobností po kliknutí na možnosť Uložiť alebo Uložiť a použiť v Zobrazení na úpravy. <br>Zmeny rozloženia nebude možné vykonávať v Zobrazení podrobností.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Toto Zobrazenie podrobností je synchronizované s príslušným Zobrazením na úpravy.<br> Polia a umiestnenie polí v tomto Zobrazení podrobností odráža polia a umiestnenie polí v Zobrazení na úpravy.<br> Zmeny v Zobrazení podrobností nemôžu byť uložené alebo použité na tejto stránke. Vykonajte zmeny alebo zrušte synchronizáciu rozložení v Zobrazení na úpravy. ',
'LBL_COPY_FROM' => 'Kopírovať z',
'LBL_COPY_FROM_EDITVIEW' => 'Kopírovať zo Zobrazenia na úpravy',
'LBL_DROPDOWN_BLANK_WARNING' => 'Vyžadujú sa hodnoty pre názov položky a zobrazené označenie. Ak chcete pridať prázdnu položku, kliknite na tlačidlo Pridať bez zadania hodnoty pre názov položky a zobrazeného označenia.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Kľúč už v zozname existuje',
'LBL_DROPDOWN_LIST_EMPTY' => 'Zoznam musí obsahovať aspoň jednu povolenú položku',
'LBL_NO_SAVE_ACTION' => 'Nepodarilo sa nájsť akciu na uloženie pre toto zobrazenie.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: zle formátovaný dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Označuje kombinačné pole. Kombinačné pole je súbor individuálnych polí. Napríklad "Adresa" je kombinačné pole obsahujúce pole "Ulica", "Mesto", "PSČ", "Región" a "Štát".<br><br>Dvojitým kliknutím na kombinačné pole zobrazíte polia, ktoré obsahuje.',
'LBL_COMBO_FIELD_CONTAINS' => 'obsahuje:',

'LBL_WIRELESSLAYOUTS'=>'Rozloženia pre mobilné telefóny',
'LBL_WIRELESSEDITVIEW'=>'Zobrazenie na úpravy pre mobilné telefóny',
'LBL_WIRELESSDETAILVIEW'=>'Zobrazenie podrobností pre mobilné telefóny',
'LBL_WIRELESSLISTVIEW'=>'Náhľad zoznamu pre mobilné telefóny',
'LBL_WIRELESSSEARCH'=>'Mobilné vyhľadávanie',

'LBL_BTN_ADD_DEPENDENCY'=>'Pridať závislosť',
'LBL_BTN_EDIT_FORMULA'=>'Upraviť vzorec',
'LBL_DEPENDENCY' => 'Závislosť',
'LBL_DEPENDANT' => 'Závislý',
'LBL_CALCULATED' => 'Vypočítaná hodnota',
'LBL_READ_ONLY' => 'Len na čítanie',
'LBL_FORMULA_BUILDER' => 'Tvorca vzorcov',
'LBL_FORMULA_INVALID' => 'Neplatný vzorec',
'LBL_FORMULA_TYPE' => 'Vzorec musí byť typu',
'LBL_NO_FIELDS' => 'Nepodarilo sa nájsť žiadne polia',
'LBL_NO_FUNCS' => 'Nepodarilo sa nájsť žiadne funkcie',
'LBL_SEARCH_FUNCS' => 'Funkcie vyhľadávania...',
'LBL_SEARCH_FIELDS' => 'Polia vyhľadávania...',
'LBL_FORMULA' => 'Vzorec',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Závislý',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Presuňte možnosti zo zoznamu na ľavej strane dostupných možností v závislom rozbaľovacom zozname do zoznamov na pravej strane, aby tieto možnosti boli k dispozícii, keď je zvolená nadradená hodnota. Ak v nadradenej hodnote nie sú žiadne položky, keď je nadradená hodnota zvolená, závislá rozbaľovacia ponuka sa nezobrazí.',
'LBL_AVAILABLE_OPTIONS' => 'Dostupné možnosti',
'LBL_PARENT_DROPDOWN' => 'Nadradená rozbaľovacia ponuka',
'LBL_VISIBILITY_EDITOR' => 'Editor viditeľnosti',
'LBL_ROLLUP' => 'Kumulatívne',
'LBL_RELATED_FIELD' => 'Súvisiace pole',
'LBL_CONFIG_PORTAL_URL'=>'URL k vlastnému obrázku loga. Odporúčané rozmery loga sú 163 x 18 pixelov.',
'LBL_PORTAL_ROLE_DESC' => 'Nemažte túto rolu. Rola Samoobslužný portál je systémom generovaná rola vytvorená počas aktivačného procesu v Sugar portáli. Použite ovládacie prvky prístupu v rámci tejto role a povoľte alebo blokujte moduly Chyby, Prípady a Báza znalostí v Sugar portáli. Neupravujte žiadne iné prístupové práva pre túto rolu – vyhnete sa tak neznámemu a nepredvídateľnému správaniu systému. V prípade neúmyselného vymazania tejto role ju obnovte zakázaním a následným povolením Sugar portálu.',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Primárny modul',
'LBL_CUSTOM_RELATIONSHIPS' => '* vzťah vytvorený v nástroji Studio',
'LBL_RELATIONSHIPS'=>'Vzťahy',
'LBL_RELATIONSHIP_EDIT' => 'Upraviť vzťahy',
'LBL_REL_NAME' => 'Názov',
'LBL_REL_LABEL' => 'Označenie',
'LBL_REL_TYPE' => 'Typ',
'LBL_RHS_MODULE'=>'Súvisiaci modul',
'LBL_NO_RELS' => 'Žiadne vzťahy',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Nepovinná podmienka' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Stĺpec',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Hodnota',
'LBL_SUBPANEL_FROM'=>'Podpanel z',
'LBL_RELATIONSHIP_ONLY'=>'Pre tento vzťah nebudú vytvorené žiadne viditeľné prvky, nakoľko už existuje viditeľný vzťah medzi týmito dvoma modulmi.',
'LBL_ONETOONE' => '1:1',
'LBL_ONETOMANY' => '1:N',
'LBL_MANYTOONE' => 'N:1',
'LBL_MANYTOMANY' => 'N:N',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Vyberte funkciu alebo komponent.',
'LBL_QUESTION_MODULE1' => 'Vyberte model.',
'LBL_QUESTION_EDIT' => 'Vyberte modul, ktorý chcete upraviť.',
'LBL_QUESTION_LAYOUT' => 'Vyberte rozloženie, ktoré chcete upraviť.',
'LBL_QUESTION_SUBPANEL' => 'Vyberte podpanel, ktorý chcete upraviť.',
'LBL_QUESTION_SEARCH' => 'Vyberte rozloženia vyhľadávania, ktoré chcete upraviť.',
'LBL_QUESTION_MODULE' => 'Vyberte komponent modulu, ktorý chcete upraviť.',
'LBL_QUESTION_PACKAGE' => 'Vyberte balík, ktorý chcete upraviť, alebo vytvorte nový balík.',
'LBL_QUESTION_EDITOR' => 'Vyberte nástroj.',
'LBL_QUESTION_DROPDOWN' => 'Vyberte rozbaľovaciu ponuku, ktorú chcete upraviť, alebo vytvorte novú rozbaľovaciu ponuku.',
'LBL_QUESTION_DASHLET' => 'Vyberte rozloženie dashlet, ktoré chcete upraviť.',
'LBL_QUESTION_POPUP' => 'Vyberte kontextové rozloženie, ktoré chcete upraviť.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Prepojiť s',
'LBL_NAME'=>'Názov',
'LBL_LABELS'=>'Označenia',
'LBL_MASS_UPDATE'=>'Hromadná aktualizácia',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Prednastavená hodnota',
'LBL_REQUIRED'=>'Povinná položka',
'LBL_DATA_TYPE'=>'Typ',
'LBL_HCUSTOM'=>'VLASTNÉ',
'LBL_HDEFAULT'=>'PREDVOLENÉ',
'LBL_LANGUAGE'=>'Jazyk:',
'LBL_CUSTOM_FIELDS' => '* pole vytvorené v nástroji Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Upraviť označenia',
'LBL_SECTION_PACKAGES' => 'Balíky',
'LBL_SECTION_PACKAGE' => 'Balík',
'LBL_SECTION_MODULES' => 'Moduly',
'LBL_SECTION_PORTAL' => 'Portál',
'LBL_SECTION_DROPDOWNS' => 'Rozbaľovacie zoznamy',
'LBL_SECTION_PROPERTIES' => 'Vlastnosti',
'LBL_SECTION_DROPDOWNED' => 'Úprava rozbaľovacieho zoznamu',
'LBL_SECTION_HELP' => 'Pomoc',
'LBL_SECTION_ACTION' => 'Akcia',
'LBL_SECTION_MAIN' => 'Hlavné',
'LBL_SECTION_EDPANELLABEL' => 'Upraviť označenie panela',
'LBL_SECTION_FIELDEDITOR' => 'Upraviť pole',
'LBL_SECTION_DEPLOY' => 'Použiť',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Upraviť viditeľnosť',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Predvolené',
'LBL_HIDDEN'=>'Skryté',
'LBL_AVAILABLE'=>'Dostupné',
'LBL_LISTVIEW_DESCRIPTION'=>'K dispozícii sú tri stĺpce zobrazené nižšie. Stĺpec <b>Predvolené</b> obsahuje polia predvolene zobrazené v náhľade zoznamu. Stĺpec <b>Ďalšie</b> obsahuje polia, ktoré si používateľ môže zvoliť pri vytváraní vlastného zobrazenia. Stĺpec <b>Dostupné</b> obsahuje polia dostupné pre vás ako administrátora buď na pridanie do stĺpca Predvolené alebo Ďalšie na použitie pre používateľov.',
'LBL_LISTVIEW_EDIT'=>'Editor náhľadu zoznamu',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Náhľad',
'LBL_MB_RESTORE'=>'Obnoviť',
'LBL_MB_DELETE'=>'Vymazať',
'LBL_MB_COMPARE'=>'Porovnať',
'LBL_MB_DEFAULT_LAYOUT'=>'Predvolené rozloženie',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Pridať',
'LBL_BTN_SAVE'=>'Uložiť',
'LBL_BTN_SAVE_CHANGES'=>'Uložiť zmeny',
'LBL_BTN_DONT_SAVE'=>'Zrušiť zmeny',
'LBL_BTN_CANCEL'=>'Zrušiť',
'LBL_BTN_CLOSE'=>'Zavrieť',
'LBL_BTN_SAVEPUBLISH'=>'Uložiť a použiť',
'LBL_BTN_NEXT'=>'Ďalej',
'LBL_BTN_BACK'=>'Späť',
'LBL_BTN_CLONE'=>'Klonovať',
'LBL_BTN_COPY' => 'Kopírovať',
'LBL_BTN_COPY_FROM' => 'Kopírovať z...',
'LBL_BTN_ADDCOLS'=>'Pridať stĺpce',
'LBL_BTN_ADDROWS'=>'Pridať riadky',
'LBL_BTN_ADDFIELD'=>'Pridať pole',
'LBL_BTN_ADDDROPDOWN'=>'Pridať rozbaľovací zoznam',
'LBL_BTN_SORT_ASCENDING'=>'Triediť vzostupne',
'LBL_BTN_SORT_DESCENDING'=>'Triediť zostupne',
'LBL_BTN_EDLABELS'=>'Upraviť označenia',
'LBL_BTN_UNDO'=>'Späť',
'LBL_BTN_REDO'=>'Vrátiť',
'LBL_BTN_ADDCUSTOMFIELD'=>'Pridať vlastné pole',
'LBL_BTN_EXPORT'=>'Exportovať prispôsobenia',
'LBL_BTN_DUPLICATE'=>'Duplikovať',
'LBL_BTN_PUBLISH'=>'Zverejniť',
'LBL_BTN_DEPLOY'=>'Použiť',
'LBL_BTN_EXP'=>'Exportovať',
'LBL_BTN_DELETE'=>'Vymazať',
'LBL_BTN_VIEW_LAYOUTS'=>'Rozloženia zobrazenia',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Zobraziť rozloženia pre mobilné telefóny',
'LBL_BTN_VIEW_FIELDS'=>'Zobraziť polia',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Zobraziť vzťahy',
'LBL_BTN_ADD_RELATIONSHIP'=>'Pridať vzťah',
'LBL_BTN_RENAME_MODULE' => 'Zmeniť názov modulu',
'LBL_BTN_INSERT'=>'Vložiť',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Chyba: Pole už existuje',
'ERROR_INVALID_KEY_VALUE'=> "Chyba: Nesprávna hodnota kľúča: [']",
'ERROR_NO_HISTORY' => 'Nepodarilo sa nájsť žiadne polia histórie',
'ERROR_MINIMUM_FIELDS' => 'Rozloženie musí obsahovať aspoň jedno pole',
'ERROR_GENERIC_TITLE' => 'Vyskytla sa chyba',
'ERROR_REQUIRED_FIELDS' => 'Naozaj chcete pokračovať? Následujúce povinné polia v rozložení chýbajú:  ',
'ERROR_ARE_YOU_SURE' => 'Naozaj chcete pokračovať?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Nasledujúce polia obsahujú vypočítané hodnoty, ktoré nebudú prepočítané v reálnom čase v Zobrazení aplikácie SugarCRM na úpravy pre mobilné telefóny:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Nasledujúce polia obsahujú vypočítané hodnoty, ktoré nebudú prepočítané v reálnom čase v Zobrazení na úpravy SugarCRM portálu:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Nasledujúce moduly sú blokované:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Ak ich chcete v portáli povoliť, povolte ich <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">tu</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Nastaviť portál',
    'LBL_PORTAL_THEME' => 'Téma portálu',
    'LBL_PORTAL_ENABLE' => 'Povoliť',
    'LBL_PORTAL_SITE_URL' => 'Stránka vášho portálu je k dispozícii na adrese:',
    'LBL_PORTAL_APP_NAME' => 'Názov aplikácie',
    'LBL_PORTAL_LOGO_URL' => 'Adresa URL loga',
    'LBL_PORTAL_LIST_NUMBER' => 'Počet záznamov na zobrazenie v zozname',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Počet polí na zobrazenie v Zobrazení podrobností',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Počet výsledkov na zobrazenie v globálnom vyhľadávaní',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Priradené predvolené nastavenia pre nové registrácie na portáli',

'LBL_PORTAL'=>'Portál',
'LBL_PORTAL_LAYOUTS'=>'Rozloženia portálu',
'LBL_SYNCP_WELCOME'=>'Zadajte adresu URL inštancie portálu, ktorú chcete aktualizovať.',
'LBL_SP_UPLOADSTYLE'=>'Vyberte šablónu štýlu z počítača, ktorú chcete nahrať.<br>Šablóna štýlu sa pri ďalšej synchronizácii implementuje do Sugar portálu.',
'LBL_SP_UPLOADED'=> 'Nahraté',
'ERROR_SP_UPLOADED'=>'Uistite sa, že nahrávate šablónu štýlu css.',
'LBL_SP_PREVIEW'=>'Tu je náhľad Sugar portálu pri použití šablóny štýlu.',
'LBL_PORTALSITE'=>'Adresa URL Sugar portálu: ',
'LBL_PORTAL_GO'=>'Spustiť',
'LBL_UP_STYLE_SHEET'=>'Nahrať šablónu štýlu',
'LBL_QUESTION_SUGAR_PORTAL' => 'Vyberte rozloženie Sugar portálu, ktoré chcete upraviť.',
'LBL_QUESTION_PORTAL' => 'Vyberte rozloženie portálu, ktoré chcete upraviť.',
'LBL_SUGAR_PORTAL'=>'Editor Sugar portálu',
'LBL_USER_SELECT' => '-- Vybrať --',

//PORTAL PREVIEW
'LBL_CASES'=>'Prípady',
'LBL_NEWSLETTERS'=>'Informačný bulletin',
'LBL_BUG_TRACKER'=>'Stopovač chýb',
'LBL_MY_ACCOUNT'=>'Môj účet',
'LBL_LOGOUT'=>'Odhlásenie',
'LBL_CREATE_NEW'=>'Vytvoriť nový',
'LBL_LOW'=>'Nízka',
'LBL_MEDIUM'=>'Stredná',
'LBL_HIGH'=>'Vysoká',
'LBL_NUMBER'=>'Číslo:',
'LBL_PRIORITY'=>'Priorita:',
'LBL_SUBJECT'=>'Predmet',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Názov balíka:',
'LBL_MODULE_NAME'=>'Názov modulu:',
'LBL_MODULE_NAME_SINGULAR' => 'Jednoznačný názov modulu:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Popis:',
'LBL_KEY'=>'Kľúč:',
'LBL_ADD_README'=>'Readme súbor',
'LBL_MODULES'=>'Moduly:',
'LBL_LAST_MODIFIED'=>'Naposledy zmenené:',
'LBL_NEW_MODULE'=>'Nový modul',
'LBL_LABEL'=>'Označenie v množnom čísle',
'LBL_LABEL_TITLE'=>'Označenie',
'LBL_SINGULAR_LABEL' => 'Jednoznačné označenie',
'LBL_WIDTH'=>'Šírka',
'LBL_PACKAGE'=>'Balík:',
'LBL_TYPE'=>'Typ:',
'LBL_TEAM_SECURITY'=>'Zabezpečenie tímu',
'LBL_ASSIGNABLE'=>'Priraditeľné',
'LBL_PERSON'=>'Osoba',
'LBL_COMPANY'=>'Spoločnosť',
'LBL_ISSUE'=>'Problém',
'LBL_SALE'=>'Predaj',
'LBL_FILE'=>'Súbor',
'LBL_NAV_TAB'=>'Karta navigácie',
'LBL_CREATE'=>'Vytvoriť',
'LBL_LIST'=>'Zoznam',
'LBL_VIEW'=>'Zobraziť',
'LBL_LIST_VIEW'=>'Náhľad zoznamu',
'LBL_HISTORY'=>'Zobraziť históriu',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Obnoviť predvolené rozloženie',
'LBL_ACTIVITIES'=>'Aktivity',
'LBL_SEARCH'=>'Vyhľadávanie',
'LBL_NEW'=>'Nové',
'LBL_TYPE_BASIC'=>'základné',
'LBL_TYPE_COMPANY'=>'spoločnosť',
'LBL_TYPE_PERSON'=>'osoba',
'LBL_TYPE_ISSUE'=>'problém',
'LBL_TYPE_SALE'=>'predaj',
'LBL_TYPE_FILE'=>'súbor',
'LBL_RSUB'=>'Toto je podpanel, ktorý sa bude zobrazovať vo vašom module',
'LBL_MSUB'=>'Toto je podpanel, ktorý váš modul poskytuje súvisiacemu modulu na zobrazenie',
'LBL_MB_IMPORTABLE'=>'Povoliť importy',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'viditeľné',
'LBL_VE_HIDDEN'=>'skryté',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] bol vymazaný',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Exportovať prispôsobenia',
'LBL_EC_NAME'=>'Názov balíka:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Popis:',
'LBL_EC_KEY'=>'Kľúč:',
'LBL_EC_CHECKERROR'=>'Vyberte modul.',
'LBL_EC_CUSTOMFIELD'=>'upravené polia',
'LBL_EC_CUSTOMLAYOUT'=>'upravené rozloženia',
'LBL_EC_CUSTOMDROPDOWN' => 'upravené rozbaľovacie ponuky',
'LBL_EC_NOCUSTOM'=>'Žiadne moduly neboli prispôsobené.',
'LBL_EC_EXPORTBTN'=>'Exportovať',
'LBL_MODULE_DEPLOYED' => 'Modul bol použitý.',
'LBL_UNDEFINED' => 'nedefinované',
'LBL_EC_CUSTOMLABEL'=>'upravené označenia',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Získavanie dát zlyhalo',
'LBL_AJAX_TIME_DEPENDENT' => 'Prebieha časovo závislá akcia. Čakajte a skúste to znova o pár sekúnd.',
'LBL_AJAX_LOADING' => 'Nahrávanie...',
'LBL_AJAX_DELETING' => 'Vymazávanie...',
'LBL_AJAX_BUILDPROGRESS' => 'Prebieha výstavba...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Prebieha aktivovanie...',
'LBL_AJAX_FIELD_EXISTS' =>'Názov poľa, ktorý ste zadali, už existuje. Zadajte nový názov poľa.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Naozaj chcete odstrániť tento balík? Touto operáciou sa natrvalo vymažú všetky súbory súvisiace s týmto balíkom.',
'LBL_JS_REMOVE_MODULE' => 'Naozaj chcete odstrániť tento modul? Touto operáciou sa natrvalo vymažú všetky súbory súvisiace s týmto modulom.',
'LBL_JS_DEPLOY_PACKAGE' => 'Všetky prispôsobenia, ktoré ste vykonali v nástroji Studio, sa prepíšu, keď znova použijete tento modul. Naozaj chcete pokračovať?',

'LBL_DEPLOY_IN_PROGRESS' => 'Použitie balíka',
'LBL_JS_VALIDATE_NAME'=>'Názov – musí začínať písmenom a môže obsahovať len písmená, čísla a znaky podčiarknutia. Nesmú sa používať medzery alebo iné špeciálne znaky.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Kľúč balíka už existuje',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Názov balíka už existuje',
'LBL_JS_PACKAGE_NAME'=>'Názov balíka – musí začínať písmenom a môže obsahovať len písmená, čísla a znaky podčiarknutia. Nesmú sa používať medzery alebo iné špeciálne znaky.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Kľúč – musí byť alfanumerický a začínať písmenom.',
'LBL_JS_VALIDATE_KEY'=>'Kľúč – musí byť alfanumerický, začínať písmenom a neobsahovať medzery.',
'LBL_JS_VALIDATE_LABEL'=>'Zadajte označenie, ktoré bude použité ako názov zobrazenia pre tento modul',
'LBL_JS_VALIDATE_TYPE'=>'Zo zoznamu uvedeného predtým vyberte typ modulu, ktorý si prajete vytvoriť',
'LBL_JS_VALIDATE_REL_NAME'=>'Názov – musí byť alfanumerický bez medzier',
'LBL_JS_VALIDATE_REL_LABEL'=>'Označenie – vložte označenie, ktoré bude zobrazené nad podpanelom',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Naozaj chcete vymazať túto povinnú položku rozbaľovacieho zoznamu? Môže to mať vplyv na funkčnosť aplikácie.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Naozaj chcete vymazať túto položku rozbaľovacieho zoznamu? Odstránenie fáz Uzatvorené/získané alebo Uzatvorené/nezískané spôsobí, že modul prognóz nebude fungovať správne',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Naozaj chcete vymazať stav nového predaja? Odstránenie tohto stavu spôsobí, že pracovný postup položiek krivky výnosu v module Príležitosti nebude fungovať správne.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Naozaj chcete vymazať stav prebiehajúceho predaja? Odstránenie tohto stavu spôsobí, že pracovný postup položiek krivky výnosu v module Príležitosti nebude fungovať správne.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Naozaj chcete vymazať fázu predaja Uzatvorené/získané? Odstránenie tohto stavu spôsobí, že modul prognóz nebude fungovať správne',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Naozaj chcete vymazať fázu predaja Uzatvorené/nezískané? Odstránenie tohto stavu spôsobí, že modul prognóz nebude fungovať správne',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Vymazaním tohto vlastného poľa odstránite vlastné pole a všetky údaje súvisiace s vlastným poľom v databáze. Pole sa už nebude zobrazovať v žiadnom z rozložení modulu.'
        . ' Ak je pole zahrnuté vo vzorci na výpočet hodnôt pre všetky polia, vzorec prestane fungovať.'
        . '\\n\\nPole už bude k dispozícii na použitie v hláseniach, táto zmena sa prejaví po odhlásení a opätovnom prihlásení do aplikácie. Všetky hlásenia, ktoré obsahujú pole, bude treba aktualizovať, aby ich bolo možné spustiť.'
        . '\\n\\nChcete pokračovať?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Naozaj chcete vymazať tento vzťah?<br>Poznámka: Táto operácia môže trvať niekoľko minút.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Vzťah sa tak stane trvalým. Naozaj chcete použiť tento vzťah?',
'LBL_CONFIRM_DONT_SAVE' => 'Od posledného uloženia boli vykonané zmeny. Chcete ich uložiť?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Uložiť zmeny?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Údaje sa môžu skrátiť. Tento krok sa nedá vrátiť späť. Naozaj chcete pokračovať?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Zvoľte správny typ dát podľa typu dát, ktoré budú vložené do poľa.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Nakonfigurujte pole tak, aby bolo možné celotextové vyhľadávanie.',
'LBL_POPHELP_FTS_FIELD_BOOST' => '"Boosting" (posilnenie) je proces vylepšovania relevancie polí záznamu.<br />Pole s vyššou úrovňou posilnenia bude mať pri vyhľadávaní väčšiu váhu. Pri vyhľadávaní sa vyššie v zozname výsledkov zobrazia zodpovedajúce záznamy obsahujúce polia s vyššou váhou.<br />Predvolená hodnota je 1,0, čo znamená neutrálne posilnenie. Ak chcete aplikovať kladné posilnenie, je povolená akákoľvek hodnota s pohyblivou desatinnou čiarkou vyššia ako 1. V prípade záporného posilnenia používajte hodnoty menšie ako 1. Napríklad hodnota 1,35 kladne posilní pole na 135 %. Použitie hodnoty 0,60 vedie k zápornému posilneniu.<br />Nezabudnite, že v predchádzajúcich verziách bolo nevyhnutné vykonať opätovnú indexáciu celotextového vyhľadávania. Táto požiadavka už neplatí.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Áno</b>: Pole bude zahrnuté v importe.<br><b>Nie</b>: Pole nebude zahrnuté v importe.<br><b>Požiadavka</b>: Hodnota pre pole musí byť zadaná v každom importe.',
'LBL_POPHELP_PII'=>'Toto pole sa automaticky označí na účely auditu a je k dispozícii v zobrazení osobných údajov.<br>Polia osobných údajov môžu byť tiež natrvalo vymazané, keď sa záznam týka žiadosti o vymazanie osobných údajov.<br>Vymazanie sa vykonáva prostredníctvom modulu ochrany osobných údajov a môže byť vykonané administrátormi alebo používateľmi s rolou Správca ochrany údajov.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Vložte hodnotu pre šírku nameranú v pixeloch.<br>Šírka nahratého obrázka bude zmenená na túto šírku.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Vložte číslo pre výšku nameranú v pixeloch.<br>Výška nahratého obrázka bude zmenená na túto výšku.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Povolené</b>: pole sa zobrazí vo funkcii Zlúčenie duplikátov, ale nebude k dispozícii na použitie pre podmienky filtrovania vo funkcii Vyhľadávanie duplikátov. <br><b>Blokované</b>: pole sa nezobrazí vo funkcii Zlúčenie duplikátov a nebude k dispozícii ani na použitie pre podmienky filtrovania vo funkcii Vyhľadávanie duplikátov.'
. '<br><b>Vo filtri</b>: pole sa zobrazí vo funkcii Zlúčenie duplikátov a bude k dispozícii aj vo funkcii Vyhľadávanie duplikátov. <br><b>Iba filter</b>: pole sa nezobrazí vo funkcii Zlúčenie duplikátov, ale bude k dispozícii vo funkcii Vyhľadávanie duplikátov. <br><b>Predvolene vybratý filter</b>: pole sa predvolene použije pre podmienky filtrovania na stránke vyhľadávania duplikátov a zobrazí sa aj vo funkcii Zlúčenie duplikátov.'
,
'LBL_POPHELP_CALCULATED'=>"Vytvorte vzorec na určenie hodnoty v tomto poli.<br>"
   . "Definície pracovného postupu, ktoré obsahujú akciu nastavenú na aktualizáciu tohto poľa, už túto akciu nevykonajú.<br>"
   . "Polia používajúce vzorce sa nebudú vypočítavať v reálnom čase v "
   . "samoobslužnom portáli aplikácie Sugar ani "
   . "v rozloženiach Zobrazenie na úpravy pre mobilné telefóny.",

'LBL_POPHELP_DEPENDENT'=>"Vytvorte vzorec na určenie toho, či je toto pole viditeľné v rozloženiach.<br/>"
        . "Závislé polia budú dodržiavať vzorec závislosti pre zobrazenie v prehliadači v mobilných zariadeniach, <br/>"
        . "ale nebudú sa riadiť vzorcami v natívnych aplikáciách, ako je napríklad Sugar Mobile pre iPhone. <br/>"
        . "Nebudú dodržiavať vzorec v samoobslužnom portáli aplikácie Sugar.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Vyberte na použitie tohto poľa pri vyhľadávaní záznamov pomocou globálneho vyhľadávania v tomto module.',
//Revert Module labels
'LBL_RESET' => 'Obnoviť',
'LBL_RESET_MODULE' => 'Obnoviť modul',
'LBL_REMOVE_CUSTOM' => 'Odstrániť prispôsobenia',
'LBL_CLEAR_RELATIONSHIPS' => 'Vymazať vzťahy',
'LBL_RESET_LABELS' => 'Obnoviť etikety',
'LBL_RESET_LAYOUTS' => 'Obnoviť rozloženia',
'LBL_REMOVE_FIELDS' => 'Odstrániť vlastné polia',
'LBL_CLEAR_EXTENSIONS' => 'Odstrániť rozšírenia',

'LBL_HISTORY_TIMESTAMP' => 'Časová značka',
'LBL_HISTORY_TITLE' => ' história',

'fieldTypes' => array(
                'varchar'=>'Textové pole',
                'int'=>'Celé číslo',
                'float'=>'Číslo s pohyblivou desatinnou čiarkou',
                'bool'=>'Začiarkavacie pole',
                'enum'=>'Rozbaľovací zoznam',
                'multienum' => 'Viacvýberový',
                'date'=>'Dátum',
                'phone' => 'Telefón',
                'currency' => 'Mena',
                'html' => 'HTML',
                'radioenum' => 'Rádio',
                'relate' => 'Väzba',
                'address' => 'Adresa',
                'text' => 'Oblasť textu',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Obrázok',
                'encrypt'=>'Šifrovať',
                'datetimecombo' =>'Dátum a čas',
                'decimal'=>'Desatinný',
),
'labelTypes' => array(
    "" => "Často používané označenia",
    "all" => "Všetky označenia",
),

'parent' => 'Flexibilný vzťah',

'LBL_ILLEGAL_FIELD_VALUE' =>"Rozbaľovací kľúč nemôže obsahovať úvodzovky.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Označili ste túto položku ako položku na odstránenie z rozbaľovacieho zoznamu. V žiadnom z rozbaľovacích polí, v ktorom sa používa tento zoznam s touto položkou ako hodnotou, sa nebude viac táto hodnota zobrazovať a túto hodnotu takisto nebude možné vybrať z rozbaľovacích polí. Naozaj chcete pokračovať?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Vyberte túto možnosť, ak chcete potvrdiť platnosť tohto poľa pre zadanie 10-miestneho<br>" .
                                 "telefónneho čísla s kapacitou pre kód krajiny 1 a <br>" .
                                 "použiť pri ukladaní záznamu pre telefónne číslo formát USA.<br>" .
                                 "Použije sa nasledujúci formát: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Všetky moduly',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (súvisiace {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Kopírovať z rozloženia',
);
