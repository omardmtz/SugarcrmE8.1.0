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
    'LBL_LOADING' => 'Nahrávání' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Skrýt více možností' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Smazat' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Vytvořeno společností SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Role',
'help'=>array(
    'package'=>array(
            'create'=>'Můžete poskytnout Tvůrce a Popis balíčku.',
            'modify'=>'Vlastnosti a možné akce pro balíček objeví zde.<br /><br />Můžete upravit název, autor a popis balíčku, stejně jako prohlížet a upravovat všechny moduly obsažené v balíčku.<br /><br />Klepněte na tlačítko Nový modul pro vytvoření modulu pro balíček.<br /><br />Pokud balíček obsahuje alespoň jeden modul, můžete Publikovat a nasadit balíček, stejně jako Exportovat přizpůsobení provedené v balíčku.',
            'name'=>'Určit jméno tohoto balíčku začínající písmenem a bez mezer (HR_Management)',
            'author'=>'Toto je <b>Autor</b>, který se zobrazí během instalace jako název entity, jež vytvořila balíček.<br><br>Autorem může být buď jednotlivec, nebo společnost.',
            'description'=>'Toto je popis balíčku, který se zobrazí během instalace.',
            'publishbtn'=>'Kliknutím na položku <b>Publikovat</b> uložíte všechna zadaná data a vytvoříte soubor .zip, což je instalovatelná verze balíčku.<br><br>Pomocí nástroje <b>Module Loader</b> lze nahrát soubor .zip a nainstalovat balíček.',
            'deploybtn'=>'Klepnutím na položku <b>Nasadit</b> uložíte všechna zadaná data a nainstalujete balíček, včetně všech modulů, do aktuální instance.',
            'duplicatebtn'=>'Kliknutím na tlačítko <b>Duplikovat</b> zkopírujete obsah balíčku do nového balíčku a zobrazíte nový balíček. <br/><br/>Pro nový balíček bude automaticky vygenerován nový název tak, že se na konec názvu balíčku použitého k vytvoření nového balíčku připojí číslo. Nový balíček můžete přejmenovat zadáním nového <b>Názvu</b> a klepnutím na položku <b>Uložit</b>.',
            'exportbtn'=>'Kliknutím na položku <b>Exportovat</b> vytvoříte soubor .zip obsahující vlastní úpravy provedené v balíčku.<br><br> Vygenerovaný soubor není instalovatelnou verzí balíčku.<br><br>K importu souboru .zip použijte nástroj <b>Module Loader</b> a balíček, včetně vlastních úprav, se zobrazí v nástroji Module Builder.',
            'deletebtn'=>'Klepněte na tlačítko Odstranit pro odstraníte tohoto balíčku a všech souborů, které se k němu vztahují.',
            'savebtn'=>'Klepnutím na tlačítko Uložit uložíte všechny zadané údaje vztahující se k balíčku.',
            'existing_module'=>'Kliknutím na ikonu <b>Modul</b> můžete upravit vlastnosti a přizpůsobit pole, vztahy a rozvržení, jež jsou přiřazeny k modulu.',
            'new_module'=>'Klepněte na tlačítko Nový modul pro vytvoření nového modulu tohoto balíčku.',
            'key'=>'Tento 5místný alfanumerický <b>Klíč</b> se použijte jako předpona všech adresářů, názvů tříd a databázových tabulek pro všechny moduly v aktuálním balíčku.<br><br>Klíč se používá ve snaze dosáhnout jedinečnosti názvu tabulky.',
            'readme'=>'Kliknutím přidáte text <b>Readme</b> pro tento balíček.<br><br>Readme bude k dispozici v době instalace.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Zadejte <b>Název</b> pro modul. Zadaný <b>Popisek</b> se zobrazí v navigačním panelu. <br/><br/>Zobrazení navigačního panelu pro modul zvolte zaškrtnutím políčka <b>Navigační panel</b>.<br/><br/>Po zaškrtnutí políčka <b>Zabezpečení týmu</b> budete mít pole pro výběr týmu v záznamech modulu. <br/><br/>Poté zvolte typ modulu, který chcete vytvořit. <br/><br/>Vyberte typ šablony. Každá šablona obsahuje specifickou sadu polí, stejně jako předdefinovaná rozvržení, jež lze použít jako základ pro váš modul. <br/><br/>Klepnutím na volbu <b>Uložit</b> modul vytvoříte.',
        'modify'=>'Můžete změnit vlastnosti modulu nebo upravit pole, vztahy a rozvržení vztahující se k modulu.',
        'importable'=>'Zaškrtnutím políčka <b>Importovatelné</b> povolíte import tohoto modulu.<br><br>Odkaz na Průvodce importem se zobrazí v panelu záložek v modulu. Průvodce importem usnadňuje import dat z externích zdrojů do vlastního modulu.',
        'team_security'=>'Zaškrtnutím políčka <b>Zabezpečení týmu</b> povolí zabezpečení týmu pro tento modul.  <br/><br/>Pokud je povoleno zabezpečení týmu, zobrazí se pole výběru týmu v záznamech v modulu ',
        'reportable'=>'Zaškrtnutí tohoto políčka umožní spouštění sestav proti tomuto modulu.',
        'assignable'=>'Zaškrtnutí tohoto políčka umožní přiřazení záznamu v tomto modulu vybranému uživateli.',
        'has_tab'=>'Zaškrtnutí položky <b>Navigační panel</b> poskytne navigační panel pro modul.',
        'acl'=>'Zaškrtnutí tohoto políčka na tomto modulu aktivuje ovládací prvky přístupu, včetně zabezpečení na úrovni polí.',
        'studio'=>'Zaškrtnutí tohoto políčka umožní správcům upravovat modul pomocí nástroje Studio.',
        'audit'=>'Zaškrtnutí tohoto políčka na tomto modulu aktivuje auditování. Změny určitých polí budou zaznamenány, aby mohli správci kontrolovat historii změn.',
        'viewfieldsbtn'=>'Kliknutím na položku <b>Zobrazit pole</b> zobrazíte pole přiřazená k modulu a budete moci vytvářet a upravovat vlastní pole.',
        'viewrelsbtn'=>'Kliknutím na položku <b>Zobrazit vztahy</b> zobrazíte vztahy přiřazené k modulu a budete moci vytvářet nové vztahy.',
        'viewlayoutsbtn'=>'Kliknutím na položku <b>Zobrazit rozložení</b> zobrazíte rozvržení pro modul a budete moci upravovat uspořádání polí v rozvrženích.',
        'viewmobilelayoutsbtn' => 'Kliknutím na položku <b>Zobrazit mobilní rozvržení</b> zobrazíte mobilní rozvržení pro modul a budete moci upravovat uspořádání polí v rozvrženích.',
        'duplicatebtn'=>'Kliknutím na položku <b>Duplikovat</b> zkopírujete vlastnosti modulu do nového modulu a zobrazíte nový modul. <br/><br/>Pro nový modul se automaticky vygeneruje nový název tak, že se na konec názvu modulu použitého k vytvoření nového modulu připojí číslo.',
        'deletebtn'=>'Klepněte na tlačítko Odstranit pro odstranění modul.',
        'name'=>'Určit jméno tohoto balíčku začínající písmenem a bez mezer (HR_Management)',
        'label'=>'Toto je <b>Popisek</b>, který se zobrazí v navigačním panelu pro modul. ',
        'savebtn'=>'Klepnutím na tlačítko Uložit uložíte všechny zadané údaje vztahující se k modulu.',
        'type_basic'=>'Šablona Základní typ poskytuje základní pole, jako je Název, Přiděleno týmu, Datum vytvoření a Popis polí.',
        'type_company'=>'Typ šablony <b>Společnost</b> poskytuje pole specifická pro organizaci, jako je Název společnosti, Odvětví a Fakturační adresa.<br/><br/>Pomocí této šablony lze vytvářet moduly, které jsou podobné standardnímu modulu Účty.',
        'type_issue'=>'Typ šablony <b>Problém</b> poskytuje pole specifická pro případy a problémy, jako je Počet, Stav, Priorita a Popis.<br/><br/>>Pomocí této šablony lze vytvářet moduly, které jsou podobné standardním modulům Případy a Sledování chyb.',
        'type_person'=>'Typ šablony <b>Osoba</b> poskytuje pole specifická pro osoby, jako je Oslovení, Titul, Jméno, Adresa a Telefonní číslo.<br/><br/>Pomocí této šablony lze vytvářet moduly, které jsou podobné standardním modulům Kontakty a Zájemci.',
        'type_sale'=>'Typ šablony <b>Prodej</b> poskytuje pole specifická pro příležitost, jako je Zdroj příležitosti, Fáze, Množství a Pravděpodobnost. <br/><br/>Pomocí této šablony lze vytvářet moduly, které jsou podobné standardnímu modulu Příležitost.',
        'type_file'=>'Šablona <b>Soubor</b> poskytuje pole specifická pro dokument, jako je Název souboru, Typ dokumentu a Datum publikování.<br><br>Pomocí této šablony lze vytvářet moduly, které jsou podobné standardnímu modulu Dokumenty.',

    ),
    'dropdowns'=>array(
        'default' => 'Zde jsou vypsány všechny <b>Rozevírací nabídky</b> pro aplikaci.<br><br>Rozevírací nabídky lze použít pro rozevírací pole v libovolném modulu.<br><br>Chcete-li provést změny v existující rozevírací nabídce, klikněte na název rozevírací nabídky.<br><br>Kliknutím na položku <b>Přidat rozevírací nabídku</b> můžete vytvořit novou rozevírací nabídku.',
        'editdropdown'=>'Rozevírací seznamy lze použít pro standardní nebo vlastní rozevírací pole v libovolném modulu.<br><br>Zadejte <b>Název</b> pro rozevírací seznam.<br><br>Pokud jsou v aplikaci nainstalovány nějaké jazykové balíčky, můžete vybráním položky <b>Jazyk</b> zobrazit položky seznamu.<br><br>Do pole <b>Název položky</b> zadejte název možnosti v rozevíracím seznamu. Tento název se nezobrazí v rozevíracím seznamu, který je viditelný pro uživatele.<br><br>Do pole <b>Zobrazený popisek</b> zadejte popisek, který se bude zobrazovat uživatelům.<br><br>Po zadání názvu položky a zobrazovaného popisku kliknutím na volbu <b>Přidat</b> přidejte položku do rozevíracího seznamu.<br><br>Chcete-li změnit pořadí položek v seznamu, přetáhněte položky na požadované pozice.<br><br>Chcete-li zobrazit zobrazovaný popisek, klikněte na položku <b>Upravit ikonu</b> a zadejte nový popisek. Chcete-li odstranit položku z rozevíracího seznamu, klikněte na položku <b>Odstranit ikonu</b>.<br><br>Chcete-li vzít zpět změnu provedenou v zobrazovaném popisku, klikněte na volbu <b>Zpět</b>.  Chcete-li vrátit změnu, kterou jste vzali zpět, klikněte na položku <b>Vrátit</b>.<br><br>Kliknutím na položku <b>Uložit</b> uložte rozevírací seznam.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Kliknutím na položku <b>Uložit a nasadit</b> uložíte provedené změny a aktivujete je v modulu.',
        'historyBtn'=> 'Po klepnutí na tlačítko <b>Zobrazit historii</b> se zobrazí a obnoví dříve uložené rozvržení z historie.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na položku <b>Obnovit výchozí rozvržení</b> obnovíte zobrazení do původního rozvržení.',
        'Hidden' 	=> 'Skryté položky nejsou vidět ze subpanelů',
        'Default'	=> 'Standardní pole jsou vidět ze subpanelů',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Kliknutím na položku <b>Uložit a nasadit</b> uložíte provedené změny a aktivujete je v modulu.',
        'historyBtn'=> 'Kliknutím na tlačítko <b>Zobrazit historii</b> zobrazíte a obnovíte dříve uložená rozvržení z historie.<br><br>Položka <b>Obnovit</b> v nabídce <b>Zobrazit historii</b> obnoví umístění polí v dříve uložených rozvrženích. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na položku <b>Obnovit výchozí rozvržení</b> obnovíte zobrazení do původního rozvržení.<br><br>Volba <b>Obnovit výchozí rozvržení</b> pouze obnoví umístění polí v původním rozvržení. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'Hidden' 	=> 'Skrytá pole nejsou v současné době k dispozici pro uživatele v ListViews.',
        'Available' => 'Dostupná pole nejsou zobrazena ve výchozím nastavení, ale mohou být přidána do ListViews uživatelů.',
        'Default'	=> 'Výchozí pole se zobrazí v ListViews, která nejsou přizpůsobena uživateli.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Kliknutím na položku <b>Uložit a nasadit</b> uložíte provedené změny a aktivujete je v modulu.',
        'historyBtn'=> 'Kliknutím na tlačítko <b>Zobrazit historii</b> zobrazíte a obnovíte dříve uložená rozvržení z historie.<br><br>Položka <b>Obnovit</b> v nabídce <b>Zobrazit historii</b> obnoví umístění polí v dříve uložených rozvrženích. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na položku <b>Obnovit výchozí rozvržení</b> obnovíte zobrazení do původního rozvržení.<br><br>Volba <b>Obnovit výchozí rozvržení</b> pouze obnoví umístění polí v původním rozvržení. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'Hidden' 	=> 'Skrytá pole nejsou v současné době k dispozici pro uživatele v ListViews.',
        'Default'	=> 'Výchozí pole se zobrazí v ListViews, která nejsou přizpůsobena uživateli.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Klepnutím na tlačítko Uložit uložíte všechny zadané údaje vztahující se k modulu.',
        'Hidden' 	=> 'Skryté položky se neukáží v Hledání.',
        'historyBtn'=> 'Kliknutím na tlačítko <b>Zobrazit historii</b> zobrazíte a obnovíte dříve uložená rozvržení z historie.<br><br>Položka <b>Obnovit</b> v nabídce <b>Zobrazit historii</b> obnoví umístění polí v dříve uložených rozvrženích. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na položku <b>Obnovit výchozí rozvržení</b> obnovíte zobrazení do původního rozvržení.<br><br>Volba <b>Obnovit výchozí rozvržení</b> pouze obnoví umístění polí v původním rozvržení. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'Default'	=> 'Výchozí pole se zobrazí v poli Hledat.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'The <b>Layout</b> area contains the fields that are currently displayed within the <b>DetailView</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'defaultquickcreate'=>'The <b>Layout</b> area contains the fields that are currently displayed within the <b>QuickCreate</b> form.<br><br>The QuickCreate form appears in the subpanels for the module when the Create button is clicked.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'The <b>Layout</b> area contains the fields that are currently displayed within the <b>EditView</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'The <b>Layout</b> area contains the fields that are currently displayed within the <b>Record View</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'saveBtn'	=> 'Klepnutím na tlačítko Uložit uložíte všechny neuložené změny.<br />Tyto změny se neukazují dokud se nevypropagují do modulu.',
        'historyBtn'=> 'Kliknutím na tlačítko <b>Zobrazit historii</b> zobrazíte a obnovíte dříve uložená rozvržení z historie.<br><br>Položka <b>Obnovit</b> v nabídce <b>Zobrazit historii</b> obnoví umístění polí v dříve uložených rozvrženích. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'historyRestoreDefaultLayout'=> 'Kliknutím na položku <b>Obnovit výchozí rozvržení</b> obnovíte zobrazení do původního rozvržení.<br><br>Volba <b>Obnovit výchozí rozvržení</b> pouze obnoví umístění polí v původním rozvržení. Chcete-li změnit popisky polí, klikněte na ikonu Upravit vedle jednotlivých polí.',
        'publishBtn'=> 'Kliknutím na položku <b>Uložit a nasadit</b> uložíte všechny změny, které jste v rozvržení provedli od posledního uložení, a aktivujete změny v modulu.<br><br>Rozvržení se v modulu ihned zobrazí.',
        'toolbox'	=> '<b>Panel nástrojů</b> obsahuje <b>Koš</b>, další prvky rozvržení a sadu dostupných polí, která lze přidat do rozvržení.<br/><br/>Prvky a pole rozvržení v Panelu nástrojů lze přetáhnout přímo do rozvržení – a stejně tak lze přetahovat prvky a pole rozvržení na Panel nástrojů.<br><br>Prvky rozvržení jsou <b>Panely</b> a <b>Řady</b>. Přidání nové řady nebo nového panelu do rozvržení poskytuje v rozvržení další umístění pro pole.<br/><br/>Pokud přetáhnete libovolná pole v Panelu nástrojů nebo rozvržení na obsazenou pozici, pozice těchto dvou polí se prohodí.<br/><br/>Pole <b>Výplň</b> vytvoří v místě umístění prázdné místo v rozvržení.',
        'panels'	=> 'Oblast <b>Rozvržení</b> nabízí pohled na to, jak bude rozvržení vypadat v rámci modulu po nasazení změn provedených v rozvržení.<br/><br/>Polohu polí, řad a panelů lze měnit tím, že je přetáhnete na požadované místo.<br/><br/>Prvky lze odstraňovat tím, že je přetáhnete do <b>Koše</b> v Panelu nástrojů. Chcete-li přidat nové prvky a pole, stačí je přetáhnout z <b>Panelu nástrojů</b> a umístit je na požadované místo v rozvržení.',
        'delete'	=> 'Sem přetáhněte jakýkoli prvek, který chcete odstranit z rozvržení',
        'property'	=> 'Upravte <b>Popisek</b> zobrazený v tomto poli.<br><br><b>Šířka</b> udává hodnotu šířky v pixelech pro moduly Sidecar a jako procentní hodnotu šířky tabulky pro zpětně kompatibilní moduly.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>Pole</b>, jež jsou k dispozici pro modul, jsou vypsána zde v pořadí podle Názvu pole.<br><br>Vlastní pole vytvořená pro modul se zobrazují nad poli, která jsou pro modul k dispozici ve výchozím nastavení.<br><br>Chcete-li pole upravit, klikněte na <b>Název pole</b>.<br/><br/>Chcete-li přidat nové pole, klikněte na položku <b>Přidat pole</b>.',
        'mbDefault'=>'<b>Pole</b>, jež jsou k dispozici pro modul, jsou vypsány zde v pořadí podle Názvu pole.<br><br>Chcete-li konfigurovat vlastnosti pro pole, klikněte na Název pole.<br><br>Chcete-li přidat nové pole, klikněte na položku <b>Přidat pole</b>. Popisek i jiné vlastnosti nového pole lze upravit po vytvoření kliknutím na Název pole.<br><br>Po nasazení modulu se nová pole vytvoření v nástroji Module Builder považují za standardní pole v nasazeném modulu v nástroji Studio.',
        'addField'	=> 'Vyberte položku <b>Typ dat</b> pro nové pole. Vybraný typ určuje, jaký druh znaků lze do pole zadat. Například do pole s typem dat Celočíselné je možné zadávat pouze celá čísla.<br><br> Zadejte <b>Název</b> pro pole. Název musí být alfanumerická hodnota a nesmí obsahovat mezery. Podtržítka jsou v pořádku.<br><br> Položka <b>Zobrazený popisek</b> je popisek, který se bude zobrazovat pro pole v rozvržení modulů. <b>Systémový popisek</b> slouží k identifikaci pole v kódu.<br><br> V závislosti na vybraném typu dat pro pole je možné pro pole nastavit některé nebo všechny následující vlastnosti:<br><br> <b>Text nápovědy</b> se zobrazí dočasně, když uživatel najede kurzorem na pole, a může uživatele upozornit na požadovaný typ vstupu.<br><br> <b>Text komentáře</b> se zobrazuje jen v nástrojích Studio a/nebo Module Builder a lze jej použít k popisu pole pro správce.<br><br> V poli se zobrazí <b>Výchozí hodnota</b>. Uživatelé mohou do pole zadat novou hodnotu, nebo mohou použít výchozí hodnotu.<br><br> Pomocí zaškrtávacího pole <b>Hromadná aktualizace</b> můžete pro pole použít funkci hromadné aktualizace.<br><br>Hodnota pole <b>Maximální velikost</b> určuje maximální počet znaků, které lze do pole zadat.<br><br> Zaškrtnutím políčka <b>Povinné pole</b> učiníte z pole povinné pole. Aby bylo možné uložit záznam obsahující toto pole, je třena tomuto poli zadat hodnotu.<br><br> Zaškrtnutím políčka <b>Reportovatelné</b> poli povolíte, aby bylo použito pro filtrování a zobrazování dat v Sestavách.<br><br> Po zaškrtnutí políčka <b>Audit</b> budete moci sledovat změny pole v Protokolu změn.<br><br>Vybráním volby poli <b>Importovatelné</b> povolíte, zakážete nebo vyžádáte import pole v nástroji Průvodce importem.<br><br>Vybráním volby v poli <b>Sloučení duplicitních položek</b> povolíte nebo zakážete funkce Sloučit duplicitní položky a Najít duplicitní položky.<br><br>Pro určité typy dat lze nastavit i další vlastnosti.',
        'editField' => 'Vlastnosti tohoto pole lze upravovat.<br><br>Kliknutím na položku <b>Klonovat</b> vytvoříte nové pole se stejnými vlastnostmi.',
        'mbeditField' => 'Položku <b>Zobrazený popisek</b> šablony lze upravit. Ostatní vlastnosti pole upravovat nelze.<br><br>Kliknutím na položku <b>Klonovat</b> vytvoříte nové pole se stejnými vlastnostmi.<br><br>Chcete-li odstranit pole šablony, aby se nezobrazovalo v modulu, odstraňte pole z příslušných <b>Rozvržení</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Exportujte vlastní úpravy provedené v nástroji Studio vytvořením balíčků, které lze uložit do jiné instance aplikace Sugar prostřednictvím nástroje <b>Module Loader</b>. <br><br>Za prvé zadejte <b>Název balíčku</b>. Pro balíček můžete zadat také informace <b>Autor</b> a <b>Popis</b>. <br><br>Vyberte moduly obsahující vlastní úpravy, které chcete exportovat. K výběru se objeví pouze moduly obsahující vlastní úpravy. <br><br>Klepnutím na tlačítko <b>Exportovat</b> vytvoříte soubor .zip pro balíček obsahující vlastní úpravy.',
        'exportCustomBtn'=>'Kliknutím na položku <b>Exportovat</b> vytvoříte soubor .zip pro balíček obsahující vlastní úpravy, které chcete exportovat.',
        'name'=>'Toto je <b>Název</b> balíčku. Tento název se zobrazí během instalace.',
        'author'=>'Toto je <b>Autor</b> zobrazený během instalace jako název subjektu, který balíček vytvořil. Autorem může být buď jedinec, nebo společnost.',
        'description'=>'Toto je popis balíčku, který se zobrazí během instalace.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Vítejte v oblasti <b>Nástroje pro vývojáře</b>. <br/><br/>Pomocí nástrojů v této části můžete vytvářet a spravovat standardní i vlastní moduly a pole.',
        'studioBtn'	=> 'K úpravě nasazených modulů použijte <b>Studio</b>.',
        'mbBtn'		=> 'Použijte Module Builder pro vytvoření nového modulu',
        'sugarPortalBtn' => 'Ke správě a úpravě portálu Sugar Portal použijte nástroj <b>Sugar Portal Editor</b>.',
        'dropDownEditorBtn' => 'K přidání a úpravě globálních rozevíracích nabídek pro rozevírací pole použijte nástroj <b>Dropdown Editor</b>.',
        'appBtn' 	=> 'V aplikačním režimu můžete upravovat různé vlastnosti programu, například kolik sestav TPS se zobrazuje na domovské stránce',
        'backBtn'	=> 'Návrat k předchozímu kroku.',
        'studioHelp'=> 'Pomocí nástroje <b>Studio</b> můžete určit, jaké informace se zobrazují v modulech a jakým způsobem.',
        'studioBCHelp' => ' značí, že modul je zpětně kompatibilní',
        'moduleBtn'	=> 'Klikněte pro editaci tohoto modulu.',
        'moduleHelp'=> 'Zde jsou zobrazeny komponenty, které můžete upravit pro tento modul.<br><br>Kliknutím na ikonu vyberte komponentu k úpravě.',
        'fieldsBtn'	=> 'Vytvořením a úpravou <b>Polí</b> můžete uložit informace v tomto modulu.',
        'labelsBtn' => 'Upravte zobrazované <b>Popisky</b> pro pole a jiné názvy v modulu.'	,
        'relationshipsBtn' => 'Přidejte nové nebo zobrazte existující <b>Vztahy</b> pro modul.' ,
        'layoutsBtn'=> 'Upravte <b>Rozvržení</b> modulu. Rozvržení jsou odlišná zobrazení modulu obsahujícího pole.<br><br>Můžete určit, která pole se zobrazí a jak budou organizována v jednotlivých rozvrženích.',
        'subpanelBtn'=> 'Určuje, která pole se zobrazí v <b>Dílčích panelech</b> v modulu.',
        'portalBtn' =>'Upravte modul <b>Rozvržení</b>, který se zobrazuje v portálu <b>Sugar Portal</b>.',
        'layoutsHelp'=> 'Zde se zobrazuje modul <b>Rozvržení</b>, který lze upravit.<br><br>V rozvrženích jsou zobrazena pole a data polí.<br><br>Kliknutím vyberte rozvržení k úpravě.',
        'subpanelHelp'=> 'Zde jsou zobrazeny <b>Podpanely</b> v modulu, který lze upravit. <br><br>Klepnutím na ikonu vyberte modul, který chcete upravit.',
        'newPackage'=>'Klepněte na tlačítko Nový balíček pro vytvoření nového balíčku.',
        'exportBtn' => 'Klepnutím na položku <b>Exportovat vlastní úpravy</b> vytvoříte a stáhnete balíček obsahující vlastní úpravy vytvoření v nástroji Studio pro konkrétní moduly.',
        'mbHelp'    => 'Pomocí nástroje <b>Module Builder</b> můžete vytvářet balíčky obsahující vlastní moduly založené na standardních nebo vlastních objektech.',
        'viewBtnEditView' => 'Upravte rozvržení <b>EditView</b> modulu.<br><br>EditView je formulář obsahující vstupní pole pro sběr dat zadaných uživatelem.',
        'viewBtnDetailView' => 'Upravte rozvržení <b>DetailView</b> modulu.<br><br>DetailView zobrazuje data v polích zadaných uživatlem.',
        'viewBtnDashlet' => 'Upravte <b>Sugar Dashlet</b> modulu, včetně rozvržení ListView a Search pro Sugar Dashlet.<br><br>Sugar Dashlet bude k dispozici pro přidání stránek do modulu Domů.',
        'viewBtnListView' => 'Upravte rozvržení <b>ListView</b> modulu.<br><br>V rozvržení ListView se zobrazují výsledky vyhledávání.',
        'searchBtn' => 'Upravte rozvržení <b>Hledání</b> modulu.<br><br>Určete, jaká pole lze použít k filtrování záznamů zobrazených v rozvržení ListView.',
        'viewBtnQuickCreate' =>  'Upravte rozvržení <b>QuickCreate</b> modulu.<br><br>Formulář QuickCreate se zobrazuje v podpanelech a v modulu Emails.',

        'searchHelp'=> 'Zde se zobrazují formuláře <b>Hledání</b>, které lze upravit.<br><br>Formuláře hledání obsahují pole pro filtrování záznamů.<br><br>Kliknutím na ikonu vyberte rozvržení hledání, které chcete upravit.',
        'dashletHelp' =>'Zde se zobrazují rozvržení <b>Sugar Dashlet</b>, která lze upravit.<br><br>Sugar Dashlet bude k dispozici pro přidání na stránky v modulu Domů.',
        'DashletListViewBtn' =>'<b>Sugar Dashlet ListView</b> zobrazuje záznamy na základě filtrů hledání Sugar Dashlet.',
        'DashletSearchViewBtn' =>'<b>Sugar Dashlet Search</b> filtruje záznamy pro Sugar Dashlet Listview.',
        'popupHelp' =>'Zde se zobrazuji rozvržení <b>Popup</b>, která lze upravit.<br>',
        'PopupListViewBtn' => 'Rozvržení <b>Popup ListView</b> slouží k zobrazení seznamu záznamů, když vybíráte jeden nebo více záznamu pro to, aby souvisely s aktuálním záznamem.',
        'PopupSearchViewBtn' => 'Rozvržení <b>Popup Search</b> umožňuje uživatelům hledat záznamy, které mají souviset s aktuálním záznamem, a zobrazuje se nad vysakovacím zobrazením seznamu ve stejném okně. Moduly Legacy toto rozvržení využívají pro vyskakovací vyhledávání, zatímco moduly Sidecar využívají konfiguraci rozvržení Hledání.',
        'BasicSearchBtn' => 'Upravte formulář <b>Základní hledání</b>, který se zobrazuje na kartě Základní hledání v oblasti Hledání pro modul.',
        'AdvancedSearchBtn' => 'Upravte formulář <b>Rozšíření hledání</b>, který se zobrazuje na kartě Rozšíření hledání v oblasti Hledání pro modul.',
        'portalHelp' => 'Spravujte a upravujte <b>Sugar Portal</b>.',
        'SPUploadCSS' => 'Uložte <b>Šablonu stylů</b> pro Sugar Portal.',
        'SPSync' => 'Vlastní nastavení <b>Synchronizace</b> instance Sugar Portal.',
        'Layouts' => 'Upravte <b>Rozvržení</b> modulů portálu Sugar Portal.',
        'portalLayoutHelp' => 'V této oblasti se zobrazují moduly na portálu Sugar Portal.<br><br>Vybráním modulu upravte <b>Rozvržení</b>.',
        'relationshipsHelp' => 'Zde se zobrazují všechny <b>Vztahy</b>, které existují mezi modulem a ostatními nasazenými moduly.<br><br>Vztah <b>Název</b> je systémem generovaný název pro vztah.<br><br><b>Primární modul</b> je modul, který vlastní vztah. Například všechny vlastnosti vztahu, pro který je modul Účty primárním modulem, jsou uloženy v tabulkách databáze Účty.<br><br><b>Typ</b> je typ vztahu existujícího mezi Primárním modulem a <b>Souvisejícím modulem</b>.<br><br>Klepnutím na název sloupce sloupec seřadíte.<br><br>Klepnutím na řádek v tabulce vztahů zobrazíte vlastnosti přiřazené ke vztahu.<br><br>Klepnutím na položku <b>Přidat vztah</b> vytvoříte nový vztah.<br><br>Vztahy lze vytvářet mezi libovolnými dvěma nasazenými moduly.',
        'relationshipHelp'=>'<b>Vztahy</b> lze vytvářet mezi modulem a jiným nasazeným modulem.<br><br> Vztahy jsou vizuálně vyjádřeny pomocí podpanelů a polí souvislosti v záznamech modulů.<br><br>Vyberte jeden z následujících <b>Typů</b> vztahů pro modul:<br><br> <b>1: 1</b> – Záznamy obou modulů budou obsahovat pole souvislosti.<br><br> <b>1: N</b> – Záznam Primárního modulu bude obsahovat podpanel a záznam Souvisejícího modulu bude obsahovat pole souvislosti.<br><br> <b>N: N</b> – Záznamy obou modulů budou zobrazovat podpanely.<br><br> Vyberte <b>Související modul</b> pro vztah. <br><br>Pokud typ vztahu zahrnuje podpanely, vyberte zobrazení podpanelů pro příslušné moduly.<br><br> Klepnutím na položku <b>Uložit</b> vytvořte vztah.',
        'convertLeadHelp' => "Zde lze přidávat moduly na obrazovku rozvržení převodu a upravovat nastavení existujících modulů.<br/><br/>
        <b>Řazení:</b><br/>
        Kontakty, Účty a Příležitosti musí obsahovat pořadí. Pořadí jakéhokoli jiného modulu můžete změnit přetažením jeho řádku v tabulce.<br/><br/>
        <b>Závislost:</b><br/>
        Pokud jsou zahrnuty Příležitosti, je třeba v rozvržení převodu buď požadovat Účty, nebo Účty odstranit.<br/><br/>
        <b>Modul:</b> Název modulu.<br/><br/>
        <b>Povinné:</b> Povinné moduly je třeba vytvořit nebo vybrat dříve, než je možné převést zájemce.<br/><br/>
        <b>Kopírovat data:</b> Pokud je položka zaškrtnuta, pole od zájemce budou zkopírována do polí se stejným názvem v nově vytvořených záznamech.<br/><br/>
        <b>Odstranit:</b> Odebrat tento modul z rozvržení převodu.<br/><br/>        ",
        'editDropDownBtn' => 'Upravit globální rozevírací nabídku',
        'addDropDownBtn' => 'Přidat novou globální rozevírací nabídku',
    ),
    'fieldsHelp'=>array(
        'default'=>'<b>Pole</b> v modulu jsou uvedena podle Názvu pole.<br><br>Šablona modulu zahrnuje předem určenou sadu polí.<br><br>Chcete-li vytvořit nové pole, klepněte na položku <b>Přidat pole</b>.<br><br>Chcete-li upravit pole, klepněte na položku <b>Název pole</b>.<br/><br/>Po nasazení modulu se nová pole vytvořená v nástroji Module Builder, společně s poli šablon, považují za standardní pole v nástroji Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'Zde se zobrazují <b>Vztahy</b>, které byly vytvořeny mezi modulem a ostatními moduly.<br><br>Vztah <b>Název</b> je systémem generovaný název pro vztah.<br><br><b>Primární modul</b> je modul, který vlastní vztah. Vlastnosti vztahu jsou uloženy v tabulkách databáze patřící primárnímu modulu.<br><br><b>Typ</b> je typ vztahu existujícího mezi Primárním modulem a <b>Souvisejícím modulem</b>.<br><br>Klepnutím na název sloupce sloupec seřadíte.<br><br>Klepnutím na řádek v tabulce vztahů zobrazíte vlastnosti přiřazené ke vztahu.<br><br>Klepnutím na položku <b>Přidat vztah</b> vytvoříte nový vztah.',
        'addrelbtn'=>'myš nad nápovědu pro přidání vztahu..',
        'addRelationship'=>'<b>Vztahy</b> lze vytvářet mezi modulem a jiným nasazeným modulem.<br><br> Vztahy jsou vizuálně vyjádřeny pomocí podpanelů a polí souvislosti v záznamech modulů.<br><br>Vyberte jeden z následujících <b>Typů</b> vztahů pro modul:<br><br> <b>1: 1</b> – Záznamy obou modulů budou obsahovat pole souvislosti.<br><br> <b>1: N</b> – Záznam Primárního modulu bude obsahovat podpanel a záznam Souvisejícího modulu bude obsahovat pole souvislosti.<br><br> <b>N: N</b> – Záznamy obou modulů budou zobrazovat podpanely.<br><br> Vyberte <b>Související modul</b> pro vztah. <br><br>Pokud typ vztahu zahrnuje podpanely, vyberte zobrazení podpanelů pro příslušné moduly.<br><br> Klepnutím na položku <b>Uložit</b> vytvořte vztah.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Popisky</b> pro pole a jiné názvy v modulu lze změnit.<br><br>Popisek upravíte klepnutím do pole, zadáním nového popisku a klepnutím na položku <b>Uložit</b>.<br><br>Pokud jsou v aplikaci nainstalovány jakékoli jazykové balíčky, můžete zvolit <b>Jazyk</b>, který bude použit pro popisky.',
        'saveBtn'=>'Klepnutím na tlačítko Uložit uložíte všechny změny.',
        'publishBtn'=>'Klepnutím na položku <b>Uložit a nasadit</b> uložíte všechny změny a aktivujete je.',
    ),
    'portalSync'=>array(
        'default' => 'Zadejte <b>Adresu URL portálu Sugar Portal</b> instance portálu, kterou chcete aktualizovat, a klepněte na tlačítko <b>Spustit</b>.<br><br>Poté zadejte platné uživatelské jméno a heslo účtu Sugar a následně klepněte na volbu <b>Zahájit synchronizaci</b>.<br><br>Vlastní úpravy v <b>Rozvrženích</b> portálu Sugar Portal, společně s <b>Šablonou stylů</b> (pokud byla nahrána), budou přeneseny na určenou instanci portálu.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Vzhled portálu Sugar Portal můžete upravit pomocí šablony stylů.<br><br>Zvolte <b>Šablonu stylů</b>, kterou chcete nahrát.<br><br>Šablona stylů se implementuje na portál Sugar Portal po provedení další synchronizace.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Chcete-li zahájit práci na projektu, klepnutím na položku <b>Nový balíček</b> vytvořte nový balíček, který bude obsahovat vaše vlastní moduly. <br/><br/>Každý balíček může obsahovat jeden nebo více modulů.<br/><br/>Například můžete chtít vytvořit balíček obsahující modul Účty. Nebo můžete chtít vytvořit balíček obsahující několik nových modulů, které navzájem spolupracují jako projekt a které souvisí navzájem i s jinými moduly, jež jsou již součástí aplikace.',
            'somepackages'=>'<b>Balíček</b> se chová jako nádoba na vlastní moduly, které jsou všechny součástí jednoho projektu. Balíček může obsahovat jeden nebo více vlastních <b>modulů</b>, jež mohou souviset mezi sebou navzájem nebo s jinými moduly v aplikaci.<br/><br/>Po vytvoření balíčku pro váš projekt můžete buď rovnou vytvářet moduly pro balíček, nebo se můžete vrátit do nástroje Module Builder později a teprve poté projekt dokončit.<br><br>Po dokončení projektu můžete <b>Nasadit</b> balíček, čímž dojde k instalaci vlastních modulu v rámci aplikace.',
            'afterSave'=>'Nový balíček by měl obsahovat alespoň jeden modul. Pro balíček můžete vytvořit jeden nebo více vlastních modulů.<br/><br/>Klepnutím na položku <b>Nový modul</b> vytvoříte vlastní modul pro tento balíček.<br/><br/> Po vytvoření alespoň jednoho modulu můžete publikovat nebo nasadit balíček, aby byl k dispozici pro vaši instanci a/nebo instance jiných uživatelů.<br/><br/> Chcete-li balíček nasadit v rámci Instance Sugar jedním krokem, klepněte na položku <b>Nasadit</b>.<br><br>Klepnutím na položku <b>Publikovat</b> uložíte balíček jako soubor .zip. Jakmile je soubor .zip uložen do systému, použijte nástroj <b>Module Loader</b> k načtení a instalaci balíčku do vaší instance Sugar.  <br/><br/>Soubor můžete distribuovat ostatním uživatelům, aby ho mohli využít k nahrání a instalaci do vlastních instancí Sugar.',
            'create'=>'<b>Balíček</b> se chová jako nádoba na vlastní moduly, které jsou všechny součástí jednoho projektu. Balíček může obsahovat jeden nebo více vlastních <b>modulů</b>, jež mohou souviset mezi sebou navzájem nebo s jinými moduly v aplikaci.<br/><br/>Po vytvoření balíčku pro váš projekt můžete buď rovnou vytvářet moduly pro balíček, nebo se můžete vrátit do nástroje Module Builder později a teprve poté projekt dokončit.',
            ),
    'main'=>array(
        'welcome'=>'Pomocí <b>Nástrojů pro vývojáře</b> můžete vytvořit a spravovat standardní a vlastní moduly a pole. <br/><br/>Chcete-li spravovat moduly v aplikaci, klepněte na položku To manage modules in the <b>Studio</b>. <br/><br/>Chcete-li vytvořit vlastní moduly, klepněte na volbu <b>Module Builder</b>.',
        'studioWelcome'=>'Všechny aktuálně nainstalované moduly, včetně standardních objektů a objektů nahraných moduly, lez upravovat v nástroji Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Jelikož aktuální balíček obsahuje alespoň jeden modul, můžete <b>Nasadit</b> moduly v balíčku do instance Sugar, nebo můžete <b>Publikovat</b> balíček, který se má instalovat do aktuální instance Sugar či jiné instance pomocí nástroje <b>Module Loader</b>.<br/><br/>Chcete-li nainstalovat balíček přímo do instance Sugar, klepněte na volbu <b>Nasadit</b>.<br><br>Chcete-li vytvořit soubor .zip pro balíček, který lze nahrát a nainstalovat do aktuální instance Sugar a jiných instancí pomocí nástroje <b>Module Loader</b>, klepněte na položku <b>Publikovat</b>.<br/><br/> Moduly pro tento balíček můžete sestavovat ve vlnách a publikovat či nasazovat je můžete ve chvíli, kdy jste na to připraveni. <br/><br/>Po publikování nebo nasazení balíčku můžete provést změny ve vlastnostech balíčku a dále tak modul upravovat.  Změny se poté projeví, jakmile balíček znovu publikujete či nasadíte." ,
        'editView'=> 'Zde můžete upravovat existující pole. Můžete odebrat libovolné z existujících polí nebo přidávat dostupná pole do levého panelu.',
        'create'=>'Při volbě <b>Typu</b> modulu, který chcete vytvořit, mějte na paměti typy polí, které budete chtít v modulu mít. <br/><br/>Každá šablona modulu obsahuje sadu polí vztahujících se k typu modulu popsanému názvem.<br/><br/><b>Základní</b> – Poskytuje základní pole, která se zobrazují ve standardních modulech, například jde o pole Název, Přiřazeno pro, Tým, Datum vytvoření a Popis.<br/><br/> <b>Společnost</b> – Poskytuje pole specifická pro organizaci, jako je Název společnosti, Odvětví a Fakturační adresa. Pomocí této šablony lze vytvářet moduly, které jsou podobné standardnímu modulu Účty.<br/><br/> <b>Person</b> – Poskytuje pole specifická pro osoby, jako je Oslovení, Titul, Jméno, Adresa a Telefonní číslo. Pomocí této šablony lze vytvářet moduly, které jsou podobné standardním modulům Kontakty a Zájemci.<br/><br/><b>Problém</b> – Poskytuje pole specifická pro případy a problémy, jako je Počet, Stav, Priorita a Popis. Pomocí této šablony lze vytvářet moduly, které jsou podobné standardním modulům Případy a Sledování chyb.<br/><br/>Poznámka: Po vytvoření modulu můžete upravit popisky polí v šabloně, stejně jako vytvořit vlastní pole, která chcete přidat do rozvržení modulů.',
        'afterSave'=>'Upravte modul tak, aby vyhovoval vašim potřebám. To provedete upravením a vytvořením polí, navázáním vztahů s ostatními moduly a uspořádáním polí v rozvrženích.<br/><br/>Chcete-li zobrazit pole šablony a spravovat vlastní pole v modulu, klepněte na položku <b>Zobrazit pole</b>.<br/><br/>Chcete-li vytvořit a spravovat vztahy mezi modulem a ostatními moduly – ať už moduly již existujícími v aplikace nebo jinými vlastními moduly ve stejném balíčku – klepněte na položku <b>Zobrazit vztahy</b>.<br/><br/>Chcete-li upravit rozvržení modulů, klepněte na položku <b>Zobrazit rozvržení</b>. Rozvržení Zobrazení podrobností, Zobrazení úprav a Zobrazení seznamu můžete pro modul změnit stejně, jako byste prováděli změnu pro moduly, které již existují v aplikaci v nástroji Studio.<br/><br/> Chcete-li vytvořit modul se stejnými vlastnostmi, jaké má aktuální modul, klepněte na položku <b>Duplikovat</b>.  Máte možnost dále upravovat nový modul.',
        'viewfields'=>'Pole v modulu lze upravovat, aby vyhovovala vašim potřebám.<br/><br/>Standardní pole nelze mazat, ale můžete je odebrat z příslušných rozvržení na stránkách Rozvržení. <br/><br/>Můžete rychle vytvářet nová pole s podobnými vlastnostmi, jako mají existující pole, klepnutím na položku <b>Klonovat</b> ve formulář <b>Vlastnosti</b>. Zadejte všechny nové vlastnosti a poté klepněte na volbu <b>Uložit</b>.<br/><br/>Doporučuje se nastavit všechny vlastnosti pro standardní pole a vlastní pole dříve, než budete publikovat a instalovat balíček obsahující vlastní modul.',
        'viewrelationships'=>'Mezi aktuálním modulem a ostatními moduly a/nebo mezi aktuálním modulem a moduly již nainstalovanými v aplikaci můžete vytvářet vztahy N:N.<br><br> Chcete-li vytvořit vztahy 1:N a 1:1 vytvořte pro moduly pole <b>Souvislost</b> a <b>Univerzální souvislost</b>.',
        'viewlayouts'=>'Můžete určovat, která pole budou dostupná pro sběr dat v <b>Zobrazení úprav</b>.  Rovněž lze určovat, jaká data se budou zobrazovat v <b>Zobrazení podrobností</b>. Zobrazení se nemusí shodovat. <br/><br/>Formulář Rychlé vytvoření se zobrazí, když se na podpanelu modulu klepne na položku <b>Vytvořit</b>. Standardně je rozvržení formuláře <b>Rychlé vytvoření</b> stejné jako výchozí rozvržení <b>Zobrazení úprav</b>. Formulář Rychlé vytvoření lze upravit, aby obsahoval méně polí a/nebo jiná pole než rozvržení Zobrazení úprav. <br><br>Můžete určit zabezpečení modulu pomocí úpravy rozvržení a pomocí funkce <b>Správa rolí</b>.<br><br>',
        'existingModule' =>'Po vytvoření a úpravě tohoto modulu můžete vytvořit buď další moduly, nebo se vrátit k balíčku, abyste jej mohli <b>Publikovat</b> nebo <b>Nasadit</b>.<br><br>Chcete-li vytvořit další moduly, klepnutím na položku <b>Duplikovat</b> vytvořte modul se stejnými vlastnostmi, jaké má aktuální modul, nebo přejděte zpět k balíčku a klepněte na volbu <b>Nový modul</b>.<br><br> Pokud jste připraveni <b>Publikovat</b> nebo <b>Nasadit</b> balíček obsahující modul, přejděte zpět k balíčku, kde můžete tyto funkce provést. Publikovat a nasazovat lze balíčky obsahující alespoň jeden modul.',
        'labels'=> 'Popisky standardních polí i vlastních polí lze měnit. Změna popisků polí neovlivní data uložená v polích.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Vlevo jsou zobrazeny tři sloupce. Sloupec „Výchozí“ obsahuje pole, jež jsou standardně zobrazena v zobrazení seznamu, sloupec „K dispozici“ obsahuje pole, která si může uživatel vybrat pro vytváření vlastního zobrazení seznamu, a sloupec „Skryté“ obsahuje pole, jež jsou vám dostupná jako správci pro přidávání do výchozího sloupce nebo do sloupce K dispozici, aby je mohli využívat uživatelé, ale zároveň jde o pole, jež jsou aktuálně zakázána.',
        'savebtn'	=> 'Klepnutím na tlačítko <b>Uložit</b> uložíte všechny změny a učiníte je aktivními.',
        'Hidden' 	=> 'Skrytá pole jsou pole, která nejsou momentálně k dispozici uživatelům pro použití v zobrazeních seznamu.',
        'Available' => 'Dostupná pole jsou pole, která nejsou ve výchozím nastavení zobrazena, ale uživatelé je mohou povolit.',
        'Default'	=> 'Výchozí pole se zobrazí uživatelům, kteří nevytvořili vlastní nastavení zobrazení seznamu.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Vlevo jsou zobrazeny dva sloupce. Sloupec „Výchozí“, který se zobrazí v zobrazení hledání, a sloupce „Skryté“ obsahující, která můžete jako správce přidávat do zobrazení.',
        'savebtn'	=> 'Klepnutím na položku <b>Uložit a nasadit</b> uložíte všechny změny a aktivujete je.',
        'Hidden' 	=> 'Skryté položky se neukází v Hledání.',
        'Default'	=> 'Výchozí pole se zobrazí v poli Hledat.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Vlevo jsou zobrazeny dva sloupce. Pravé sloupec, označený jako Aktuální rozvržení nebo Náhled rozvržení, slouží ke změně rozvržení modulu. Levý sloupec, označený jako Panel nástrojů, obsahuje užitečné prvky a nástroje, které lze použít k úpravě rozvržení. <br/><br/>Pokud je oblast rozvržení označena jako Aktuální rozvržení, pracujete na kopii rozvržení, které aktuálně využívá modul k zobrazení.<br/><br/>Pokud je označena jako Náhled rozvržení, pracujete na kopii vytvořené dříve klepnutím na tlačítko Uložit, která se již mohla změnit od verze, která se zobrazuje uživatelům tohoto modulu.',
        'saveBtn'	=> 'Klepnutím na toto tlačítko uložíte rozvržení, aby se zachovaly vámi provedené změny. Když se k tomuto modulu vrátíte, budete začínat od tohoto změněného rozvržení. Vaše rozvržení se však nebude zobrazovat uživatelům modulu, dokud neklepnete na tlačítko Uložit a publikovat.',
        'publishBtn'=> 'Klepnutím na toto tlačítko nasadíte rozvržení. To znamená, že se toto rozvržení okamžitě zobrazí uživatelům tohoto modulu.	',
        'toolbox'	=> 'Panel nástrojů obsahuje celou řadu užitečných funkcí pro úpravu rozvržení, včetně oblasti koše, sady dalších prvků a sady dostupných polí. Kteroukoli z těchto položek můžete přetáhnout na rozvržení.',
        'panels'	=> 'Tato oblast ukazuje uživatelům tohoto modulu, jak bude rozvržení vypadat po nasazení.<br/><br/>Pomocí přetahování můžete upravovat polohu prvků, jako jsou pole, řádky a panely. Prvky lze odstraňovat tak, že je přetáhnete do oblasti koše na panelu nástrojů, nebo je možné přidávat nové prvky přetažením z panelu nástrojů na požadované místo v rozvržení.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Vlevo jsou zobrazeny dva sloupce. Pravé sloupec, označený jako Aktuální rozvržení nebo Náhled rozvržení, slouží ke změně rozvržení modulu. Levý sloupec, označený jako Panel nástrojů, obsahuje užitečné prvky a nástroje, které lze použít k úpravě rozvržení. <br/><br/>Pokud je oblast rozvržení označena jako Aktuální rozvržení, pracujete na kopii rozvržení, které aktuálně využívá modul k zobrazení.<br/><br/>Pokud je označena jako Náhled rozvržení, pracujete na kopii vytvořené dříve klepnutím na tlačítko Uložit, která se již mohla změnit od verze, která se zobrazuje uživatelům tohoto modulu.',
        'dropdownaddbtn'=> 'Klepnutím na toto tlačítko přidáte novou položku do rozevírací nabídky.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Vlastní úpravy provedené v nástroji Studio v rámci této instance lze zabalit a nasadit do jiné instance.  <br><br>Zadejte <b>Název balíčku</b>. Pro balíček můžete zadat informace do polí <b>Autor</b> a <b>Popis</b>.<br><br>Vyberte moduly obsahující vlastní úpravy k exportu. (K výběru se zobrazí pouze moduly obsahující vlastní úpravy.)<br><br>Klepnutím na položku <b>Exportovat</b> vytvoříte soubor .zip pro balíček obsahující vlastní úpravy. Soubor .zip lze nahrát do jiné instance pomocí nástroje <b>Module Loader</b>.',
        'exportCustomBtn'=>'Kliknutím na položku <b>Exportovat</b> vytvoříte soubor .zip pro balíček obsahující vlastní úpravy, které chcete exportovat.
',
        'name'=>'<b>Název</b> balíčku se zobrazí v nástroji Module Loader po nahrání balíčku pro instalaci do nástroje Studio.',
        'author'=>'<b>Autor</b> je název entity, která balíček vytvořila. Autorem může být buď jednotlivec nebo společnost.<br><br>Autor se zobrazí v nástroji Module Loader po nahrání balíčku pro instalaci do nástroje Studio.
',
        'description'=>'<b>Popis</b> balíčku se zobrazí v nástroji Module Loader po nahrání balíčku pro instalaci do nástroje Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Vítejte v oblasti <b>Nástroje pro vývojáře</b1>. <br/><br/>Pomocí nástrojů v této části můžete vytvářet a spravovat standardní i vlastní moduly a pole.',
        'studioBtn'	=> 'Pomocí nástroje <b>Studio</b> lze upravovat nainstalované moduly změnou rozmístění polí, vybráním dostupných polí a vytvoření vlastních datových polí.',
        'mbBtn'		=> 'Použijte Module Builder pro vytvoření nového modulu',
        'appBtn' 	=> 'Režim Aplikace použijte k úpravě různých vlastností programu, například počtu sestav TPS, které se zobrazí na domovské obrazovce',
        'backBtn'	=> 'Návrat k předchozímu kroku.',
        'studioHelp'=> 'Nástroj <b>Studio</b> použijte k úpravě nainstalovaných modulů.',
        'moduleBtn'	=> 'Klikněte pro editaci tohoto modulu.',
        'moduleHelp'=> 'Vyberte komponentu modulu, kterou chcete upravit',
        'fieldsBtn'	=> 'Upravte, jaké informace jsou uloženi v modulu, pomocí <b>Polí</b> v modulu.<br/><br/>Zde můžete upravovat a vytvářet vlastní pole.',
        'layoutsBtn'=> 'Upravte <b>Rozvržení</b> zobrazení úprav, podrobností, seznamů a hledání.',
        'subpanelBtn'=> 'Upravte, jaké informace se zobrazují v podpanelech modulů.',
        'layoutsHelp'=> 'Vyberte <b>Rozvržení k úpravě</b>.<br/<br/>Chcete-li změnit rozvržení, které obsahuje datová pole pro zadávání dat, klepněte na položku <b>Zobrazení úprav</b>.<br/><br/>Chcete-li změnit rozvržení, které zobrazuje data zadaná do polí v Zobrazení úprav, klepněte na položku <b>Zobrazení podrobností</b>.<br/><br/>Chcete-li změnit sloupce, které se zobrazují ve výchozím seznamu, klepněte na položku <b>Zobrazení seznamu</b>.<br/><br/>Chcete-li změnit rozvržení formulářů pro Základní a Rozšíření hledání, klepněte na volbu <b>Hledání</b>.',
        'subpanelHelp'=> 'Vyberte <b>Podpanel</b> k úpravě.',
        'searchHelp' => 'Vyberte rozvržení <b>Hledání</b> k úpravě.',
        'labelsBtn'	=> 'Chcete-li zobrazit hodnoty v tomto modulu, upravte <b>popisky</b>.',
        'newPackage'=>'Klepněte na tlačítko Nový balíček pro vytvoření nového balíčku.',
        'mbHelp'    => '<b>Vítejte v nástroji Module Builder.</b><br/><br/>Pomocí nástroje <b>Module Builder</b> lze vytvářet balíčky obsahující vlastní moduly založené na standardních nebo vlastních objektech. <br/><br/>Chcete-li začít, klepnutím na položku <b>Nový balíček</b> vytvořte nový balíček, nebo vyberte balíček, který chcete upravit.<br/><br/> <b>Balíček</b> se chová jako nádoba na vlastní moduly, které jsou všechny součástí jednoho projektu. Balíček může obsahovat jeden nebo více vlastních modulů, jež mohou souviset mezi sebou navzájem nebo s jinými moduly v aplikaci. <br/><br/>Příklady: Můžete chtít vytvořit balíček obsahující jeden vlastní modul, který souvisí se standardním modulem Účty. Nebo můžete chtít vytvořit balíček obsahující několik nových modulů, které spolupracují jako projekt a které souvisí mezi sebou navzájem i s moduly v aplikaci.',
        'exportBtn' => 'Klepnutím na volbu <b>Exportovat vlastní úpravy</b> vytvoříte balíček obsahující vlastní úpravy provedené v nástroji Studio pro specifické moduly.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor "dropdownu"',

//ASSISTANT
'LBL_AS_SHOW' => 'V budoucnu zobrazit pomocníka .',
'LBL_AS_IGNORE' => 'V budoucnu ignorovat pomocníka.',
'LBL_AS_SAYS' => 'Pomocník říká:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Stavitel modulu',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor "dropdownu"',
'LBL_EDIT_DROPDOWN'=>'Upravit dropdown',
'LBL_DEVELOPER_TOOLS' => 'Studio',
'LBL_SUGARPORTAL' => 'Editor Sugar portálu',
'LBL_SYNCPORTAL' => 'Synchronizace portálu',
'LBL_PACKAGE_LIST' => 'Seznam balíků',
'LBL_HOME' => 'Domů',
'LBL_NONE'=>'-žádný-',
'LBL_DEPLOYE_COMPLETE'=>'Nasadit kompletní',
'LBL_DEPLOY_FAILED'   =>'Došlo k chybě při nasazování procesu, může váš balíček není správně nainstalován',
'LBL_ADD_FIELDS'=>'Přidat příslušné pole',
'LBL_AVAILABLE_SUBPANELS'=>'Dostupné sub-panely',
'LBL_ADVANCED'=>'Pokročilé',
'LBL_ADVANCED_SEARCH'=>'Rozšiřené',
'LBL_BASIC'=>'Základní',
'LBL_BASIC_SEARCH'=>'Základní',
'LBL_CURRENT_LAYOUT'=>'Rozvržení',
'LBL_CURRENCY' => 'Měna:',
'LBL_CUSTOM' => 'Uživatelský',
'LBL_DASHLET'=>'Sugar budík',
'LBL_DASHLETLISTVIEW'=>'Zobrazení seznamu Sugar budíků',
'LBL_DASHLETSEARCH'=>'Hledání Sugar budíků',
'LBL_POPUP'=>'Překryvné zobrazení',
'LBL_POPUPLIST'=>'Překryvný seznam',
'LBL_POPUPLISTVIEW'=>'Překryvný seznam',
'LBL_POPUPSEARCH'=>'Překryvné vyhledávání',
'LBL_DASHLETSEARCHVIEW'=>'Hledání Sugar budíků',
'LBL_DISPLAY_HTML'=>'Zobrazit HTML kód',
'LBL_DETAILVIEW'=>'Detailní zobrazení',
'LBL_DROP_HERE' => '[Vlož sem]',
'LBL_EDIT'=>'Editace',
'LBL_EDIT_LAYOUT'=>'Úprava rozvržení',
'LBL_EDIT_ROWS'=>'Úprava řádků',
'LBL_EDIT_COLUMNS'=>'Úprava sloupců',
'LBL_EDIT_LABELS'=>'Úprava popisek',
'LBL_EDIT_PORTAL'=>'Upravit portál pro',
'LBL_EDIT_FIELDS'=>'Upravit pole',
'LBL_EDITVIEW'=>'Zobrazení úprav',
'LBL_FILTER_SEARCH' => "Hledat",
'LBL_FILLER'=>'(výplň)',
'LBL_FIELDS'=>'Pole',
'LBL_FAILED_TO_SAVE' => 'Uložení se nezdařilo',
'LBL_FAILED_PUBLISHED' => 'Publikování selhalo',
'LBL_HOMEPAGE_PREFIX' => 'Moje',
'LBL_LAYOUT_PREVIEW'=>'Náhled nákresu',
'LBL_LAYOUTS'=>'Rozložení',
'LBL_LISTVIEW'=>'Zobrazení seznamu',
'LBL_RECORDVIEW'=>'Zobrazení záznamu',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Nový balík',
'LBL_NEW_PANEL'=>'Nový panel',
'LBL_NEW_ROW'=>'Nový řádek',
'LBL_PACKAGE_DELETED'=>'Balík smazán',
'LBL_PUBLISHING' => 'Publikuji...',
'LBL_PUBLISHED' => 'Publikováno',
'LBL_SELECT_FILE'=> 'Vybrat soubor',
'LBL_SAVE_LAYOUT'=> 'Uložit rozvržení',
'LBL_SELECT_A_SUBPANEL' => 'Vybrat Subpanel',
'LBL_SELECT_SUBPANEL' => 'Vybrat Subpanel',
'LBL_SUBPANELS' => 'Pod-panely',
'LBL_SUBPANEL' => 'Pod-panel',
'LBL_SUBPANEL_TITLE' => 'Titul:',
'LBL_SEARCH_FORMS' => 'Hledat',
'LBL_STAGING_AREA' => 'Staging Area (přetáhněte sem položky)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar Pole (klikněte na pole pro přidání do staging area)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (klikněte na položku pro přidání do stagging area)',
'LBL_TOOLBOX' => 'Nástroje',
'LBL_VIEW_SUGAR_FIELDS' => 'Zobrazit Sugar Fields',
'LBL_VIEW_SUGAR_BIN' => 'Zobrazit Sugar Bin',
'LBL_QUICKCREATE' => 'Rychlé vytvoření',
'LBL_EDIT_DROPDOWNS' => 'Upravit globální "dropdown"',
'LBL_ADD_DROPDOWN' => 'Přidat nový "dropdown"',
'LBL_BLANK' => '-prázdný-',
'LBL_TAB_ORDER' => 'Pořadí záložek',
'LBL_TAB_PANELS' => 'Zobrazit panely jako tabulky záložky',
'LBL_TAB_PANELS_HELP' => 'Zobrazit každý panel jako jeho vlastní kartu místo toho, mít je všechny na jedné obrazovce',
'LBL_TABDEF_TYPE' => 'Typ zobrazení:',
'LBL_TABDEF_TYPE_HELP' => 'Vyberte prosím jek má být tato sekce zobrazena. Toto nastavení je možné pouze tehdy když jsou aktivní záložky.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Záložka',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Vyberte Panel z rozvržení. Vyberte Záložku.',
'LBL_TABDEF_COLLAPSE' => 'Svinout',
'LBL_TABDEF_COLLAPSE_HELP' => 'Vyberte pro výhozí stav pro tento panel.',
'LBL_DROPDOWN_TITLE_NAME' => 'Název',
'LBL_DROPDOWN_LANGUAGE' => 'Jazyk',
'LBL_DROPDOWN_ITEMS' => 'Seznam položek',
'LBL_DROPDOWN_ITEM_NAME' => 'Jméno položky',
'LBL_DROPDOWN_ITEM_LABEL' => 'Zobrazit štítek',
'LBL_SYNC_TO_DETAILVIEW' => 'Synchronizovat do detailního pohledu',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Zvolte tuto možnost pro synchronizaci rozvržení EditView (změnovém pohledu) na odpovídající rozvržení DetailView (detailního pohledu). Rozvržení Polí a Položek v EditView bude synchronizováno a uloženo automaticky do DetailView po kliknutí na tlačítko Uložit nebo Uložit & Rozmístni v EditView.<br />Změny v rozvržení nebudou moci být provedeny v DetailView (detailním pohledu).',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Tento DetailView (detailní pohled) je synchronizován s odpovídající rozvržení EditView (změnovém pohledu). <br />Rozvržení Polí a Položek v rozvržení DetailView (detailního pohledu) reflektuje rozvržení Polí a Položek v rozvržení EditView (změnovém pohledu).<br />Změny v rozvržení DetailView (detailního pohledu) nemohou být uloženy',
'LBL_COPY_FROM' => 'Kopírovat hodnoty z:',
'LBL_COPY_FROM_EDITVIEW' => 'Zkopíruj z EditView (změnového pohledu)',
'LBL_DROPDOWN_BLANK_WARNING' => 'Hodnoty jsou povinné pro Jméno položky i Štítek zobraz. Pro přidání prázdné položky, klikněte Přidat bez vložení hodnoty pro Jméno položky i Štítek zobraz.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Klíč v seznamu již existuje',
'LBL_DROPDOWN_LIST_EMPTY' => 'List musí obsahuvat nejméně jednu umožněnou položku',
'LBL_NO_SAVE_ACTION' => 'Akce na uložení nebyla nalezena v tomto pohledu',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: nesprávně zformulovaný dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Označuje kombinační pole. Kombinační pole je kolekce individuálních polí. Například "Adresa" je kombinační pole obsahující pole "Ulice", "Město", "PSČ", "Region" a "Stát".<br><br>Dvojklikem na kombinační pole zobrazíte položky, které obsahuje.',
'LBL_COMBO_FIELD_CONTAINS' => 'Obsah:',

'LBL_WIRELESSLAYOUTS'=>'Rozvržení pro mobil',
'LBL_WIRELESSEDITVIEW'=>'Zobrazení úprav pro mobil',
'LBL_WIRELESSDETAILVIEW'=>'Zobrazení detailu pro mobil',
'LBL_WIRELESSLISTVIEW'=>'Zobrazení seznamu pro mobil',
'LBL_WIRELESSSEARCH'=>'Zobrazení vyhledávání pro mobil',

'LBL_BTN_ADD_DEPENDENCY'=>'Přidat vazbu',
'LBL_BTN_EDIT_FORMULA'=>'Editovat výraz',
'LBL_DEPENDENCY' => 'Vazba',
'LBL_DEPENDANT' => 'V závislosti',
'LBL_CALCULATED' => 'Vypočtená hodnota',
'LBL_READ_ONLY' => 'Pouze pro čtení',
'LBL_FORMULA_BUILDER' => 'Tvořič výrazů',
'LBL_FORMULA_INVALID' => 'Chybné pravidlo',
'LBL_FORMULA_TYPE' => 'Pravidlo musí být typu',
'LBL_NO_FIELDS' => 'Nenalezeny žádné položky',
'LBL_NO_FUNCS' => 'Nenalezeny žádné funkce',
'LBL_SEARCH_FUNCS' => 'Vyhledávání funkcí...',
'LBL_SEARCH_FIELDS' => 'vyhledávání polí...',
'LBL_FORMULA' => 'Výraz',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Závisející',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Přetáhněte možnosti ze seznamu na levé straně dostupných možností, které chcete zobrazit v závislém číselníku, když je zvolena rodičovská hodnota. Pokud není vybrána ani jedna hodnota, bude závislý číselník skryt.',
'LBL_AVAILABLE_OPTIONS' => 'Dostupné možnosti',
'LBL_PARENT_DROPDOWN' => 'Nadřazený dropdown',
'LBL_VISIBILITY_EDITOR' => 'Viditelnost editoru',
'LBL_ROLLUP' => 'Kumulativní',
'LBL_RELATED_FIELD' => 'Související pole',
'LBL_CONFIG_PORTAL_URL'=>'URL pro vlastní obrázek loga. Doporučené rozměry jsou 163 × 18 pixelů.',
'LBL_PORTAL_ROLE_DESC' => 'Tuto roli neodstraňujte. Role samoobslužného portálu zákazníka je role vygenerovaná systémem v průběhu aktivace portálu Sugar. Pomocí ovládacích prvků přístupu v rámci této role můžete v portálu Sugar povolit nebo zakázat moduly Chyby, Případy nebo Znalostní báze. Neupravujte žádné jiné ovládací prvky přístupu pro tuto roli, aby se zabránilo neznámému a nepředvídatelnému chování systému. V případě náhodného vymazání této role ji znovuvytvořte vypnutím a zapnutím portálu Sugar.',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Hlavní modul',
'LBL_CUSTOM_RELATIONSHIPS' => '* vztah vytvořený ve Studiu',
'LBL_RELATIONSHIPS'=>'Vztahy',
'LBL_RELATIONSHIP_EDIT' => 'Upravit vztahy',
'LBL_REL_NAME' => 'Název',
'LBL_REL_LABEL' => 'Název',
'LBL_REL_TYPE' => 'Typ',
'LBL_RHS_MODULE'=>'Připojený modul',
'LBL_NO_RELS' => 'Žádné vztahy',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Volitelná podmínka' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Sloupec',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Hodnota',
'LBL_SUBPANEL_FROM'=>'Pod-panel formulář',
'LBL_RELATIONSHIP_ONLY'=>'Žádné viditelné prvky nebudou vytvořeny pro tento vztah',
'LBL_ONETOONE' => 'Jeden na jednoho',
'LBL_ONETOMANY' => 'Jeden na více',
'LBL_MANYTOONE' => 'Více na jednoho',
'LBL_MANYTOMANY' => 'Více na více',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Vyberte funkci nebo komponentu.',
'LBL_QUESTION_MODULE1' => 'Vyberte modul.',
'LBL_QUESTION_EDIT' => 'Vyberte modul pro úpravu.',
'LBL_QUESTION_LAYOUT' => 'Vyberve rozvržení pro úpravu.',
'LBL_QUESTION_SUBPANEL' => 'Vyberte pod-panel pro úpravu.',
'LBL_QUESTION_SEARCH' => 'Vyberve rozvržení hledání pro úpravu.',
'LBL_QUESTION_MODULE' => 'Vyberte komponentu modulu pro úpravu.',
'LBL_QUESTION_PACKAGE' => 'Vyberte balík pro úpravu nebo vytvořte nový balík.',
'LBL_QUESTION_EDITOR' => 'Zvolte nástroj.',
'LBL_QUESTION_DROPDOWN' => 'Vyberte "dropdown" pro úpravo nebo vytvořte nový "dropdown".',
'LBL_QUESTION_DASHLET' => 'Vyberte rozvržení budíku pro úpravu.',
'LBL_QUESTION_POPUP' => 'Vyberte rozvržení popup pro úpravu.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Týkající se',
'LBL_NAME'=>'Název',
'LBL_LABELS'=>'Štítky',
'LBL_MASS_UPDATE'=>'Hromadná aktualizace',
'LBL_AUDITED'=>'Auditovat?',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Výchozí hodnota',
'LBL_REQUIRED'=>'Požadovaná položka',
'LBL_DATA_TYPE'=>'Typ',
'LBL_HCUSTOM'=>'VLATNÍ',
'LBL_HDEFAULT'=>'ZÁKLADNÍ',
'LBL_LANGUAGE'=>'Jazyk skupin záložek',
'LBL_CUSTOM_FIELDS' => '* pole vytvořené ve Studiu',

//SECTION
'LBL_SECTION_EDLABELS' => 'Úprava popisek',
'LBL_SECTION_PACKAGES' => 'Balíky',
'LBL_SECTION_PACKAGE' => 'Balíky',
'LBL_SECTION_MODULES' => 'Moduly',
'LBL_SECTION_PORTAL' => 'Portál',
'LBL_SECTION_DROPDOWNS' => '"Dropdowny"',
'LBL_SECTION_PROPERTIES' => 'Vlastnosti',
'LBL_SECTION_DROPDOWNED' => 'Upravit dropdown',
'LBL_SECTION_HELP' => 'Nápověda',
'LBL_SECTION_ACTION' => 'Akce',
'LBL_SECTION_MAIN' => 'Hlavní',
'LBL_SECTION_EDPANELLABEL' => 'Upravit štítek panelu',
'LBL_SECTION_FIELDEDITOR' => 'Upravit pole',
'LBL_SECTION_DEPLOY' => 'Použít',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Upravit viditelnost',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Standardní',
'LBL_HIDDEN'=>'Skryté',
'LBL_AVAILABLE'=>'Dostupné',
'LBL_LISTVIEW_DESCRIPTION'=>'Dole jsou zobrazeny tři sloupce. První sloupec obsahuje pole, která jsou zobrazena v seznamu defaultně. Sloupec další obsahuje položky, které si uživatel může vybrat pro vytvoření vlastního nastavení, a sloupec dostupné je pro vás jako správce, abyste z něj mohl přidat do prvního sloupce nebo do sloupce další.',
'LBL_LISTVIEW_EDIT'=>'Úprava pohledu seznam',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Náhled',
'LBL_MB_RESTORE'=>'Obnova',
'LBL_MB_DELETE'=>'Smazat',
'LBL_MB_COMPARE'=>'Porovnat',
'LBL_MB_DEFAULT_LAYOUT'=>'Základní rozvržení',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Vlož [Alt+C]',
'LBL_BTN_SAVE'=>'Uložit',
'LBL_BTN_SAVE_CHANGES'=>'Uložit změny',
'LBL_BTN_DONT_SAVE'=>'Zahodit změny',
'LBL_BTN_CANCEL'=>'Zrušit',
'LBL_BTN_CLOSE'=>'Zavřít',
'LBL_BTN_SAVEPUBLISH'=>'Uložit a publikovat',
'LBL_BTN_NEXT'=>'Další',
'LBL_BTN_BACK'=>'Zpět',
'LBL_BTN_CLONE'=>'Duplikovat',
'LBL_BTN_COPY' => 'Kopírovat',
'LBL_BTN_COPY_FROM' => 'Kopírovat z',
'LBL_BTN_ADDCOLS'=>'Přidat sloupce',
'LBL_BTN_ADDROWS'=>'Přidat řádky',
'LBL_BTN_ADDFIELD'=>'Přidat pole',
'LBL_BTN_ADDDROPDOWN'=>'Přidat "dropdown"',
'LBL_BTN_SORT_ASCENDING'=>'Seřadit vzestupně',
'LBL_BTN_SORT_DESCENDING'=>'Seřadit sestupně',
'LBL_BTN_EDLABELS'=>'Úprava popisek',
'LBL_BTN_UNDO'=>'Zpět',
'LBL_BTN_REDO'=>'Znovu',
'LBL_BTN_ADDCUSTOMFIELD'=>'Přidat vlastní pole',
'LBL_BTN_EXPORT'=>'Exportovat vlastní úpravy',
'LBL_BTN_DUPLICATE'=>'Duplikovat',
'LBL_BTN_PUBLISH'=>'Publikovat',
'LBL_BTN_DEPLOY'=>'Použít',
'LBL_BTN_EXP'=>'Exportovat',
'LBL_BTN_DELETE'=>'Smazat',
'LBL_BTN_VIEW_LAYOUTS'=>'Zobrazit rozvržení',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Zobrazit mobilní layout',
'LBL_BTN_VIEW_FIELDS'=>'Zobrazit pole',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Zobrazit vztahy',
'LBL_BTN_ADD_RELATIONSHIP'=>'Přidat vztah',
'LBL_BTN_RENAME_MODULE' => 'Přejmenovat modul',
'LBL_BTN_INSERT'=>'Vložit',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Pole už existuje',
'ERROR_INVALID_KEY_VALUE'=> "Chyba.Špatná hodnota: [$#39;]",
'ERROR_NO_HISTORY' => 'Žádná historie soubory nalezeny',
'ERROR_MINIMUM_FIELDS' => 'Rozvržení musí obsahovat alespoň jedno pole',
'ERROR_GENERIC_TITLE' => 'Došlo k chybě',
'ERROR_REQUIRED_FIELDS' => 'Jste si jisti, že chcete pokračovat? Následující povinná pole rozvržení chybí:',
'ERROR_ARE_YOU_SURE' => 'Jsi si jist(a), že chcete pokračovat?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Následujíc položka(y) obsahuje vypočtenou hodnotu, která nebude přepočítána při zobrazení v editačním pohledu v SugarCRM Mobile.',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Následujíc položka(y) obsahuje vypočtenou hodnotu, která nebude přepočítána při zobrazení v editačním pohledu v SugarCRM Portal.',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Následující moduly jsou vypnuté:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Pokud byste chtěliaktivaci v rámci portálu povolte ji zde.',
    'LBL_PORTAL_CONFIGURE' => 'Konfigurovat portál',
    'LBL_PORTAL_THEME' => 'Motiv portálu',
    'LBL_PORTAL_ENABLE' => 'povoleno',
    'LBL_PORTAL_SITE_URL' => 'Váš portál je k dispozici na adrese:',
    'LBL_PORTAL_APP_NAME' => 'Jméno aplikace',
    'LBL_PORTAL_LOGO_URL' => 'Adresa URL loga',
    'LBL_PORTAL_LIST_NUMBER' => 'Počet záznamů pro zobrazení na seznamu',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Počet polí pro detailního zobrazení',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Počet výsledků pro Global Search',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Výchozí přiřazení pro nové portálové registrace',

'LBL_PORTAL'=>'Portál',
'LBL_PORTAL_LAYOUTS'=>'Rozložení pro portál',
'LBL_SYNCP_WELCOME'=>'Prosím, zadejte adresu URL portálu instance, kterou chcete aktualizovat.',
'LBL_SP_UPLOADSTYLE'=>'Vyberte soubor stylu z vašeho počítače.<br>Styl bude implementován do Sugaru při přístím provedení synchronizace.',
'LBL_SP_UPLOADED'=> 'Nahráno na server',
'ERROR_SP_UPLOADED'=>'Prosím, ujistěte se, že jste nahráváte CSS styl.',
'LBL_SP_PREVIEW'=>'Zde je náhled jak bude portál Sugaru vypadat při použití stylu.',
'LBL_PORTALSITE'=>'URL Sugar portálu:',
'LBL_PORTAL_GO'=>'Spustit',
'LBL_UP_STYLE_SHEET'=>'Nahrát soubor stylu',
'LBL_QUESTION_SUGAR_PORTAL' => 'Vyberte rozvržení Sugar portálu pro úpravu.',
'LBL_QUESTION_PORTAL' => 'Vyberve rozvržení portálu pro úpravu.',
'LBL_SUGAR_PORTAL'=>'Editor Sugar portálu',
'LBL_USER_SELECT' => 'Vybrat uživatele',

//PORTAL PREVIEW
'LBL_CASES'=>'Případy',
'LBL_NEWSLETTERS'=>'Zpravodaje',
'LBL_BUG_TRACKER'=>'"Bug Tracker"',
'LBL_MY_ACCOUNT'=>'Můj účet',
'LBL_LOGOUT'=>'Odhlášení',
'LBL_CREATE_NEW'=>'Vytvoř nový',
'LBL_LOW'=>'Nízká',
'LBL_MEDIUM'=>'Střední',
'LBL_HIGH'=>'Vysoká',
'LBL_NUMBER'=>'Číslo:',
'LBL_PRIORITY'=>'Priorita:',
'LBL_SUBJECT'=>'Předmět',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Jméno balíku:',
'LBL_MODULE_NAME'=>'Jméno modulu:',
'LBL_MODULE_NAME_SINGULAR' => 'Název modulu',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Popis:',
'LBL_KEY'=>'Klíč:',
'LBL_ADD_README'=>'Readme soubor',
'LBL_MODULES'=>'Moduly:',
'LBL_LAST_MODIFIED'=>'Poslední změna:',
'LBL_NEW_MODULE'=>'Nový modul',
'LBL_LABEL'=>'Název v množném čísle',
'LBL_LABEL_TITLE'=>'Název',
'LBL_SINGULAR_LABEL' => 'Název v jednotém čísle',
'LBL_WIDTH'=>'Šířka',
'LBL_PACKAGE'=>'Balík:',
'LBL_TYPE'=>'Typ:',
'LBL_TEAM_SECURITY'=>'Zabezpečení',
'LBL_ASSIGNABLE'=>'Přiřaditelné',
'LBL_PERSON'=>'Osoba',
'LBL_COMPANY'=>'Společnost',
'LBL_ISSUE'=>'Problém',
'LBL_SALE'=>'Sleva',
'LBL_FILE'=>'Soubor',
'LBL_NAV_TAB'=>'Záložka navigace',
'LBL_CREATE'=>'Přidat',
'LBL_LIST'=>'Celk. cena',
'LBL_VIEW'=>'Zobrazit',
'LBL_LIST_VIEW'=>'Zobrazení seznamu',
'LBL_HISTORY'=>'Zobrazit historii',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Obnovit výchozí rozvržení',
'LBL_ACTIVITIES'=>'Aktivity',
'LBL_SEARCH'=>'Hledat',
'LBL_NEW'=>'Nový',
'LBL_TYPE_BASIC'=>'základní',
'LBL_TYPE_COMPANY'=>'společnost',
'LBL_TYPE_PERSON'=>'osoba',
'LBL_TYPE_ISSUE'=>'problém',
'LBL_TYPE_SALE'=>'sleva',
'LBL_TYPE_FILE'=>'soubor',
'LBL_RSUB'=>'Toto je sub-panel, který se zobrazí ve vašem modulu',
'LBL_MSUB'=>'Toto je subpanel, že váš modul poskytuje související modul pro zobrazení',
'LBL_MB_IMPORTABLE'=>'Povolit importy',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'viditelný',
'LBL_VE_HIDDEN'=>'schovaný',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] byl smazán',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Exportovat vlastní úpravy',
'LBL_EC_NAME'=>'Jméno balíku:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Popis:',
'LBL_EC_KEY'=>'Klíč:',
'LBL_EC_CHECKERROR'=>'Prosím vyberte modul.',
'LBL_EC_CUSTOMFIELD'=>'vlastní pole',
'LBL_EC_CUSTOMLAYOUT'=>'upravená rozvržení',
'LBL_EC_CUSTOMDROPDOWN' => 'Upravený dropdown',
'LBL_EC_NOCUSTOM'=>'Žádné moduly nebyly upraveny.',
'LBL_EC_EXPORTBTN'=>'Exportovat',
'LBL_MODULE_DEPLOYED' => 'Modul byl nasazen.',
'LBL_UNDEFINED' => 'nedefinován',
'LBL_EC_CUSTOMLABEL'=>'Přizpůsobit popisek',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Nepodařilo se získat údaje',
'LBL_AJAX_TIME_DEPENDENT' => 'Probíhajá časově závisle akce. Prosím, vyčkejte a zkuste to znovu za několik sekund.',
'LBL_AJAX_LOADING' => 'Nahrávám...',
'LBL_AJAX_DELETING' => 'Mažu...',
'LBL_AJAX_BUILDPROGRESS' => 'Buduji...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Provádím nasazení...',
'LBL_AJAX_FIELD_EXISTS' =>'Jméno pole které jste zadali už existuje. Prosím zadejte jiné jméno pole.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Jste si jisti, že chcete odstranit tento balíček? Budou odstraněny všechny soubory spojené s tímto balíčkem.',
'LBL_JS_REMOVE_MODULE' => 'Jste si jisti, že chcete odstranit tento modul? Budou odstraněny všechny soubory spojené s tímto modul.',
'LBL_JS_DEPLOY_PACKAGE' => 'Žádné úpravy, které jste provedli ve studiu nebudou přepsány, pokud tento modul je znovu-nasazený. Jste si jisti, že chcete pokračovat?',

'LBL_DEPLOY_IN_PROGRESS' => 'Nasazuji balík',
'LBL_JS_VALIDATE_NAME'=>'Název - Musí být alfanumerické bez mezer a začínající písmenem',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Klíč balíčku již existuje',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Jméno balíčku již existuje',
'LBL_JS_PACKAGE_NAME'=>'Název balíčku – musí začínat písmenem a může obsahovat pouze písmena, číslice a podtržítka. Nesmí být použity mezery nebo jiné speciální znaky.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Klíč - Musí být alfanumerický a začínat písmenem',
'LBL_JS_VALIDATE_KEY'=>'Klíč - musí být alfanumerické',
'LBL_JS_VALIDATE_LABEL'=>'Prosím, zadejte popisek, který bude použit jako zobrazovaný název pro tento modul',
'LBL_JS_VALIDATE_TYPE'=>'Prosím, vyberte typ modulu, který chcete stavět z výše uvedeného seznamu',
'LBL_JS_VALIDATE_REL_NAME'=>'Název - Musí být alfanumerické bez mezer',
'LBL_JS_VALIDATE_REL_LABEL'=>'Label - přidejte název, který se zobrazí nad pod-panelem',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Přejete si skutečně smazat toto požadované vybírací pole? Můžete tím ovlivnit funkcionalitu vaší aplikace.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Přejete si skutečně smazat toto vybírací pole? Smazáním fází Uzavřeno - vyhráno a Uzavřeno - prohráno přestane modul Předpovědí fungovat správně.',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Jste si jisti, že chcete smazat nový stav prodeje? Odstranění tohoto stavu způsobí, že workflow Revenue Line Item modulu Příležitosti modul nebude fungovat správně.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Jste si jistí, že chcete smazat prodejní fázi "Probíhá"? Smazání způsobí, že nebude workflow nad Řádkami tržby modulu Obchody pracovat správně.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Přejete si skutečně smazat prodejní fázi Uzavřeno - vyhráno? Smazáním této fáze přestane modul Předpovědí fungovat správně.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Přejete si skutečně smazat prodejní fázi Uzavřeno - prohráno? Smazáním této fáze přestane modul Předpovědí fungovat správně.',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Jste si jisti, že chcete smazat tento vztah?',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Toto vytvoří tento vztah trvalím. Jste si jisti, že chcete nasadit tento vztah?',
'LBL_CONFIRM_DONT_SAVE' => 'Byly provedeny změny od posledního uložení, chcete je uložit?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Uložit změny?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Data mohou být zkrácena a toto nelze vrátit zpět, jste si jisti, že chcete pokračovat?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Zvolte odpovídající typ dat na základě typu dat, které budou vloženy do pole.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Konfigurujte pole, aby bylo fulltextově prohledávatelné.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Posilování je proces vylepšování relevance polí záznamu.<br />Pole s vyšší úrovní posílení budou mít při vyhledávání větší váhu. Při vyhledávání se výše v seznamu výsledků zobrazí odpovídající záznamy obsahující pole s vyšší váhou.<br />Výchozí hodnota je 1,0, což značí neutrální posílení. Chcete-li aplikovat kladné posílení, je přijímána jakýkoli hodnota s plovoucí desetinnou čárkou vyšší než 1. V případě záporného posílení používejte hodnoty menší než 1. Například hodnota 1,35 kladně posílá pole na 135 %. Použití hodnoty 0,60 způsobí záporné posílení.<br />Nezapomeňte, že v předchozích verzích bylo nezbytné provést opětovnou indexaci fulltextového hledání. To již nyní není potřeba.',
'LBL_POPHELP_IMPORTABLE'=>'Ano: Pole bude zahrnuto do operace importu.<br><br />Ne.: Pole nebudou zahrnuta do operace importu.<br><br />Požadováno: Hodnota pole musí být v každé operaci importu.',
'LBL_POPHELP_PII'=>'Toto pole bude automaticky označeno pro audit a bude k dispozici v zobrazení osobních informací.<br>Pole osobních informací lze také trvale vymazat, pokud se záznamu týká požadavek na vymazání údajů na základě ochrany osobních údajů.<br>Vymazání se provádí pomocí modulu Ochrana osobních údajů a mohou ho provádět správci nebo uživatelé v roli Správce ochrany osobních údajů.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Zadejte šířku v pixelech.<br><br />Nahraný obrázek bude změněn do tohoto rozměru.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Zadejte výšku v pixelech.<br><br />Nahraný obrázek bude změněn do tohoto rozměru.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Enabled</b>: The field will appear in the Merge Duplicates feature, but will not be available to use for the filter conditions in the Find Duplicates feature.<br><b>Disabled</b>: The field will not appear in the Merge Duplicates feature, and will not be available to use for the filter conditions in the Find Duplicates feature.'
. '<br><b>In Filter</b>: The field will appear in the Merge Duplicates feature, and will also be available in the Find Duplicates feature.<br><b>Filter Only</b>: The field will not appear in the Merge Duplicates feature, but will be available in the Find Duplicates feature.<br><b>Default Selected Filter</b>: The field will be used for a filter condition by default in the Find Duplicates page, and will also appear in the Merge Duplicates feature.'
,
'LBL_POPHELP_CALCULATED'=>"Create a formula to determine the value in this field.<br>"
   . "Workflow definitions containing an action that are set to update this field will no longer execute the action.<br>"
   . "Fields using formulas will not be calculated in real-time in "
   . "the Sugar Self-Service Portal or "
   . "Mobile EditView layouts.",

'LBL_POPHELP_DEPENDENT'=>"Create a formula to determine whether this field is visible in layouts.<br/>"
        . "Dependent fields will follow the dependency formula in the browser-based mobile view, <br/>"
        . "but will not follow the formula in the native applications, such as Sugar Mobile for iPhone. <br/>"
        . "They will not follow the formula in the Sugar Self-Service Portal.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Vyberte k použití toto pole, pokud chcete vyhledávat záznamy pomocí globálního vyhledávání v tomto modulu.',
//Revert Module labels
'LBL_RESET' => 'Resetovat',
'LBL_RESET_MODULE' => 'Zrušit modul',
'LBL_REMOVE_CUSTOM' => 'Smazat úpravy',
'LBL_CLEAR_RELATIONSHIPS' => 'Vymazat vztahy',
'LBL_RESET_LABELS' => 'Zrušit štítky',
'LBL_RESET_LAYOUTS' => 'Vynulovat rozvržení',
'LBL_REMOVE_FIELDS' => 'Odebrat vlastní pole',
'LBL_CLEAR_EXTENSIONS' => 'Vyčistit rozšíření',

'LBL_HISTORY_TIMESTAMP' => 'Časová známka',
'LBL_HISTORY_TITLE' => 'historie',

'fieldTypes' => array(
                'varchar'=>'Textové pole',
                'int'=>'integer',
                'float'=>'Plovoucí',
                'bool'=>'"Checkbox"',
                'enum'=>'"Dropdown"',
                'multienum' => '"MultiSelect"',
                'date'=>'Datum:',
                'phone' => 'Telefon',
                'currency' => 'Měna:',
                'html' => 'HTML',
                'radioenum' => 'Rádio',
                'relate' => 'Vazba',
                'address' => 'Adresa',
                'text' => 'Oblast textu',
                'url' => 'Adresa URL',
                'iframe' => 'Rám',
                'image' => 'Obrázek',
                'encrypt'=>'Šifrovat',
                'datetimecombo' =>'Datum a čas',
                'decimal'=>'Desetinný',
),
'labelTypes' => array(
    "" => "Často používané popisky",
    "all" => "všechny popisky",
),

'parent' => 'Flexibilní vztah',

'LBL_ILLEGAL_FIELD_VALUE' =>"\"Dropdown\" nemůže obsahovat uvozovky.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Označujete položku pro odstranění z \"dropdown\" seznamu. Jakékoli \"dropdown\" pole užívající tento seznam s touto polokou jako hodnotu již nebude zobrazeno. Hodnota již nebude moci být vybrána z \"dropdownu\". Jste si jisti, že chcete pokračovat?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'všechny moduly',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (související ID {1})',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Kopírovat z layoutu',
);
