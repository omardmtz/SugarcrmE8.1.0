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
    'LBL_LOADING' => 'Laster inn' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Skjul Valg' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Slett' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Utviklet av SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Rolle',
'help'=>array(
    'package'=>array(
            'create'=>'Gi et navn på pakken. Navnet du oppgir må bestå av alfanummeriske tegn og kan ikke inneholde mellomrom. (Eksempel: HR_Management)<br /><br />Du kan oppgi Forfatter og Beskrivelse som informasjon på pakken.<br /><br />Trykk Lagre for å opprette pakken.',
            'modify'=>'Egenskapene og de mulige hendelsene for Pakken vises her.<br /><br />Du kan endre Navn, Forfatter og Beskrivelse på pakken, i tillegg til å se på og endre alle modulene i pakken.<br /><br />Klikk Ny Modul for å opprette en modul for pakken.<br /><br />Så lenge pakken inneholder minst én modul, kan du Publisere og Implementere pakken, i tillegg til å Eksportere tilpasningene i pakken.',
            'name'=>'Dette er Navnet på den aktuelle pakken.<br /><br />Navnet du oppgir må bestå av alfanummeriske tegn og kan ikke inneholde mellomrom. (Eksempel: HR_Management)',
            'author'=>'Dette er Forfatteren som vises under installasjonen, som navnet på den enhet som skapte pakken.<br /><br />Forfatteren kan enten være en person eller et firma.',
            'description'=>'Dette er pakkens Beskrivelse som vises under installasjonen.',
            'publishbtn'=>'Klikk Publisér for å lagre alle innlagte data og opprette en .zip-fil som er en installérbar versjon av pakken.<br /><br />Bruk Modullaster for å laste opp .zip-filen og installere pakken.',
            'deploybtn'=>'Klikk Distribuér for å lagre alle innlagte data og installere pakken, inkludert alle moduler, i gjeldende instans.',
            'duplicatebtn'=>'Klikk Duplisér for å kopiere innholdet av pakken inn i en ny pakke og så vise den nye pakken.<br /><br />Et nytt navn vil automatisk bli generert for den nye pakken ved å legge til et siffer etter navnet på pakken som ble brukt som utgangspunkt for den nye. Du kan gi et nytt navn på pakken ved å skrive et nytt Navn og klikke Lagre.',
            'exportbtn'=>'Klikk Eksportér for å lage en .zip-fil som inneholder tilpasningene i pakken.<br /><br />Denne filen er ikke en installérbar versjon av pakken.<br /><br />Bruk Modullaster for å importere .zip-filen og for at pakken, inkludert tilpasninger, skal vises i Modulbyggeren.',
            'deletebtn'=>'Klikk Slett for å slette denne pakken og alle filer relatert til denne pakken.',
            'savebtn'=>'Klikk Lagre for å lagre alle innlagte data relatert til pakken.',
            'existing_module'=>'Klikk Modul-ikonet for å endre egenskapene og tilpasse felt, relasjoner og utseende assosiert med modulen.',
            'new_module'=>'Klikk Ny Modul for å lage en ny modul for denne pakken.',
            'key'=>'Denne 5-tegns, alfanummeriske Nøkkel vil bli brukt som prefiks på alle kataloger, klassenavn og databasetabeller for alle moduler i gjeldende pakke.',
            'readme'=>'Klikk for å legge til Les Meg-tekst for denne pakken.<br /><br />Les Meg-teksten vil være tilgjengelig på tidspunktet for installasjon.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Gi et Navn på modulen. Etiketten du oppgir vil vises i navigasjonsfanen.<br /><br />Velg å vise en navigasjonsfane for modulen ved å krysse av i Navigasjonsfane-sjekkboksen.<br /><br />Kryss av i Gruppe-sikkerhet-sjekkboksen for å ha et Gruppe-valg i modul-oppføringene.<br /><br />Velg så hva slags type modul du ønsker å opprette.',
        'modify'=>'Du kan endre modul-egenskapene eller tilpasse Feltene, Relasjonene og Utseendet relatert til modulen.',
        'importable'=>'Å krysse av <b>Importérbar</b> avmerkingsboksen vil gjøre det mulig å importere for denne modulen. <br> En kobling til importveiviseren vises i Snarveier-panelet i modulen. Importveiviseren forenkler import av data fra eksterne kilder i den egendefinerte modulen.',
        'team_security'=>'Å krysse av <b>Team-sikkerhet</b> avmerkingsboksen vil aktivere Team-sikkerhet for denne modulen.<br/>Hvis Team-sikkerhet er aktivert, vil Teamvalg-feltet vises i postene i modulen',
        'reportable'=>'Ved å krysse av denne boksen tillates det at denne modulen kan ha rapporter kjørende mot den.',
        'assignable'=>'Ved å krysse av denne boksen tillates at en oppføring i denne boksen han tildeles en valgt bruker.',
        'has_tab'=>'Ved å krysse av for<b>Navigasjonsflik</b> så vil en navigasjonsflik bli tilgjengeliggjort for modulen.',
        'acl'=>'Ved å krysse av denne boksen vil Tilgangskontroll aktiveres for denne modulen, inkludert Feltnivå-Sikkerhet.',
        'studio'=>'Ved å krysse av denne boksen så vil administratorer få lov til å skreddersy denne modulen i Studio.',
        'audit'=>'Ved å krysse av denne boksen så åpnes det for Revisjon av denne modulen. Endringer av enkelte felter vil da bli loggført slik at administratorer kan evaluere endringshistorikken.',
        'viewfieldsbtn'=>'Klikk <b>Vis Felter</b> for å se feltene som er assosiert med modulen og for å skape og endre tilpassede felter.',
        'viewrelsbtn'=>'Klikk <b>Vis Relasjoner</b> for å se relasjonene assosiert med denne modulen, og for å skape nye relasjoner.',
        'viewlayoutsbtn'=>'Klikk <b>Vis Oppsett</b> for å se oppsettet for modulen og skreddersy felt-arrangementet i utformingene.',
        'viewmobilelayoutsbtn' => 'Klikk på <b>Vis mobilgrensesnitt</b> å se mobilgrensesnittene for modulen og å tilpasse feltordningen i grensesnittene.',
        'duplicatebtn'=>'Klikk Duplisér for å kopiere innholdet av pakken inn i en ny pakke og så vise den nye pakken.<br /><br />Et nytt navn vil automatisk bli generert for den nye pakken ved å legge til et siffer etter navnet på pakken som ble brukt som utgangspunkt for den nye.',
        'deletebtn'=>'Klikk Slett for å slette denne modulen.',
        'name'=>'Dette er <b>Navnet</b> på gjeldende modul.<br/><br/>Navnet må være alfanummerisk og må starte med en bokstav og ikke inneholde mellomrom. (Eksempel: HR_Ledelse)',
        'label'=>'Dette er <b>Etiketten</b> som vil vises i navigasjonsfanen for modulen.',
        'savebtn'=>'Klikk Lagre for å lagre alle innlagte data relatert til modulen.',
        'type_basic'=>'<b>Standard</b>-malen inneholder grunnleggende felter, slik som Navn, Tildelt til, Team, Dato Opprettet og Beskrivelse.',
        'type_company'=>'<b>Firma</b>-malen inneholder felter typisk for organisasjoner, slik som Firmanavn, Bransje og Fakturaadresse.<br/><br/>Bruk denne malen for å lage moduler som er tilsvarende den standard Konto-modulen.',
        'type_issue'=>'<b>Sak</b>-malen inneholder felter relatert til saksbehandling og feilhåndtering, slik som Saksnummer, Prioritet og Beskrivelse.<br/><br/>Bruk denne malen for å lage moduler som er tilsvarende den standard Saks- og Feilhåndterings-modulen.',
        'type_person'=>'<b>Person</b>-malen inneholder felter relatert til individer, slik som Tittel, Navn, Adresse og Telefonnummer.<br/><br/>Bruk denne malen for å lage moduler som er tilsvarende de standard Kontakt- og Emner-modulene.',
        'type_sale'=>'<b>Salg</b>-maltypen gir salgsspesifikke felter som Emne-kilde, Salgstrinn, Beløp og Sannsynlighet.<br/><br/>Bruk denne malen til å opprette moduler som er lik den standard Muligheter-modulen.',
        'type_file'=>'<b>Fil</ b>-malen inneholder dokumentspesifikke felter som Filnavn, Dokumenttype og Utgivelsesdato. <br> Bruk denne malen til å opprette moduler som er lik den standard Dokument-modulen.',

    ),
    'dropdowns'=>array(
        'default' => 'Alle <b>nedtrekksmenyene</b> for programmet er listet her. <br> Nedtrekksmenyene kan brukes til nedtrekksfelt i en modul. <br> For å gjøre endringer i en eksisterende nedtrekksmeny, klikk på nedtrekksmeny-navnet. <br>Klikk<b> Legg til Nedtrekksmeny </b> for å opprette en ny nedtrekksmeny.',
        'editdropdown'=>'Nedtrekkslister kan brukes til standard eller egendefinerte nedtrekksfelt i en modul. <br> Gi et <b>Navn</b> for nedtrekkslisten. <br> Hvis noen språkpakker er installert i programmet, kan du velge <b> Språket </b> som skal brukes for listeelementene. <br> I <b> navn</b>-feltet, angi et navn for valget i nedtrekkslisten. Dette navnet vises ikke i nedtrekkslisten som er synlig for brukerne. <br> I <b>visningsetikett</b>-feltet, gi en etikett som vil være synlig for brukerne. <br> Etter å ha gitt elementet navn og visningsetiketten, klikk <b> Legg til </b> for å legge til elementet i nedtrekkslisten. <br> For å endre rekkefølgen på elementene i listen, dra og slipp elementer inn i ønskede posisjoner. <br> For å redigere visningsetiketten på et element, klikker du <b> Rediger ikon </b>, og skriver en ny etikett. Slik sletter du et element fra rullegardinlisten: klikk <b> sletteikonet </b>. <br> Vil du angre en endring på en visningsetikett, klikk <b> Angre </b>. For å gjøre om en endring som ble angret, klikk <b> Gjør på nytt</b>. <br> Klikk <b> Lagre </b> for å lagre nedtrekkslisten.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klikk <b> Lagre og Ta i bruk </b> for å lagre endringene du har gjort, og for å gjøre dem aktive i modulen.',
        'historyBtn'=> 'Klikk <b> Vis Historikk</ b> for å vise og gjenopprette et tidligere lagret oppsett fra historien.',
        'historyRestoreDefaultLayout'=> 'Klikk på <b>Gjenopprett standard grensesnitt</b> for å gjenopprette visningen til det opprinnelige grensesnittet.',
        'Hidden' 	=> '<b>Skjulte</b> felter vises ikke i subpanelet.',
        'Default'	=> '<b> Standard </b>-felter vises i underpanelet.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klikk <b> Lagre og Ta i bruk </b> for å lagre endringene du har gjort, og for å gjøre dem aktive i modulen.',
        'historyBtn'=> 'Klikk <b> Vis Historikk</b> for å vise og gjenopprette et tidligere lagret oppsett fra historien.<br /><b>Gjenopprett</b> innenfor <b>Vis historikk</b> gjenoppretter feltplasseringen innen tidligere lagrede oppsett. For å endre feltetiketter, klikker du på Redigér-ikonet ved siden av hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klikk på <b>Gjenopprett standard grensesnitt</b> for å gjenopprette en visning til det opprinnelige grensesnittet.<br><br><b>Gjenopprett standard grensesnitt</b> gjenoppretter bare feltplasseringene i det opprinnelige grensesnittet. For å endre feltetiketter må du klikke på Rediger-ikonet ved siden av hvert felt.',
        'Hidden' 	=> '<b>Skjulte</ b> felt foreløpig ikke tilgjengelige for brukere å se i Listevisning.',
        'Available' => '<b>Tilgjengelige</ b> felt vises ikke som standard, men kan legges til Listevisning av brukerne.',
        'Default'	=> '<b>Standard</b>-felter vises i Listevisninger som ikke er tilpasset av brukerne.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klikk <b> Lagre og Ta i bruk </b> for å lagre endringene du har gjort, og for å gjøre dem aktive i modulen.',
        'historyBtn'=> 'Klikk <b> Vis Historikk</b> for å vise og gjenopprette et tidligere lagret oppsett fra historien.<br /><b>Gjenopprett</b> innenfor <b>Vis historikk</b> gjenoppretter feltplasseringen innen tidligere lagrede oppsett. For å endre feltetiketter, klikker du på Redigér-ikonet ved siden av hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klikk på <b>Gjenopprett standard grensesnitt</b> for å gjenopprette en visning til det opprinnelige grensesnittet.<br><br><b>Gjenopprett standard grensesnitt</b> gjenoppretter bare feltplasseringene i det opprinnelige grensesnittet. For å endre feltetiketter må du klikke på Rediger-ikonet ved siden av hvert felt.',
        'Hidden' 	=> '<b>Skjulte</ b> felt foreløpig ikke tilgjengelige for brukere å se i Listevisning.',
        'Default'	=> '<b>Standard</b>-felter vises i Listevisninger som ikke er tilpasset av brukerne.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Klikk <b>Lagre og Ta i bruk</b> for å lagre endringene du har gjort, og for å gjøre dem aktive',
        'Hidden' 	=> '<b>Skjulte felt vil ikke vises i Søk.</b>',
        'historyBtn'=> 'Klikk <b> Vis Historikk</b> for å vise og gjenopprette et tidligere lagret oppsett fra historien.<br /><b>Gjenopprett</b> innenfor <b>Vis historikk</b> gjenoppretter feltplasseringen innen tidligere lagrede oppsett. For å endre feltetiketter, klikker du på Redigér-ikonet ved siden av hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klikk på <b>Gjenopprett standard grensesnitt</b> for å gjenopprette en visning til det opprinnelige grensesnittet.<br><br><b>Gjenopprett standard grensesnitt</b> gjenoppretter bare feltplasseringene i det opprinnelige grensesnittet. For å endre feltetiketter må du klikke på Rediger-ikonet ved siden av hvert felt.',
        'Default'	=> '<b>Default</b>-felter vises i Søket.'
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
        'saveBtn'	=> 'Klikk Lagre for å lagre endringene du har gjort i oppsettet siden forrige gang du lagret den. <br /><br />Endringene vil ikke bli vist i modulen før du distribuerer de lagrede endringene.',
        'historyBtn'=> 'Klikk <b> Vis Historikk</b> for å vise og gjenopprette et tidligere lagret oppsett fra historien.<br /><b>Gjenopprett</b> innenfor <b>Vis historikk</b> gjenoppretter feltplasseringen innen tidligere lagrede oppsett. For å endre feltetiketter, klikker du på Redigér-ikonet ved siden av hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klikk på <b>Gjenopprett standard grensesnitt</b> for å gjenopprette en visning til det opprinnelige grensesnittet.<br><br><b>Gjenopprett standard grensesnitt</b> gjenoppretter bare feltplasseringene i det opprinnelige grensesnittet. For å endre feltetiketter må du klikke på Rediger-ikonet ved siden av hvert felt.',
        'publishBtn'=> 'Klikk Lagre og Distribuer for å lagre alle endringer du har gjort i oppsettet siden forrige gang du lagret den, og for å gjøre endringene aktive i modulen. <br /><br />Utformingen vil umiddelbart bli vist i modulen.',
        'toolbox'	=> '<b>Verktøykassen</b> inneholder <b>papirkurven</b>, flere elementer og settet med tilgjengelige felt for å legge til grensesnittet. <br/> <br/> Elementer og felt i verktøykassen kan dras og slippes i grensesnittet, og grensesnittelementene og-feltene kan dras og slippes fra grensesnittet til verktøykassen. <br><br>Grensesnittelementene er <b>paneler</b> og <b>rader</b>. Legg til en ny rad eller et nytt panel i grensesnittet for flere steder i grensesnittet for felt. <br/> <br/> Dra og slipp noen av feltene i verktøykassen eller grensesnittet til en okkupert feltposisjon for å bytte plasseringen av de to feltene. <br/> <br/> <b>Filler</b>-feltet lager tomrom i på stedet i grensesnittet det plasseres.',
        'panels'	=> '<b>Grensesnittet</b> gir en oversikt over hvordan grensesnittet vises i modulen når endringene i grensesnittet tas i bruk. <br/> <br/> Du kan flytte felt, rader og paneler ved å dra og slippe dem på ønsket sted. <br/> <br/> Fjern elementer ved å dra og slippe dem i <b>papirkurven</b> i verktøykassen, eller legg til nye elementer og felt ved å dra dem fra <b>verktøykassen</b> og slipp dem på ønsket plassering i grensesnittet.',
        'delete'	=> 'Dra og slipp ethvert element her for å fjerne det fra layouten',
        'property'	=> 'Rediger etiketten for dette feltet. <br />Tab Order styrer i hvilken rekkefølge Tab-tasten skifter mellom felt.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>Feltene</b> som er tilgjengelige for modulen er oppført her etter feltnavn. <br><br>Tilpassede felt som er opprettet for modulen vises over feltene som er tilgjengelige for modulen som standard. <br> <br>For å redigere felt, klikker du på <b>feltnavnet</b>. <br/> <br/> Hvis du vil opprette et nytt felt, klikker du på <b>Legg til felt</b>.',
        'mbDefault'=>'<b>Feltene</b> som er tilgjengelige for modulen er oppført her etter feltnavn. <br><br>For å konfigurere egenskaper for et felt, klikker du på feltnavnet. <br> <br>For å opprette et nytt felt, klikker du på <b>Legg til felt</b>. Etiketten og andre egenskaper for det nye feltet kan redigeres etter opprettelse ved å klikke på feltnavnet. <br><br>Når modulen er tatt i bruk, anses de nye feltene som er opprettet i modulenbyggere som standardfeltene i modulen i Studio.',
        'addField'	=> 'Velg <b>datatype</b> for det nye feltet. Typen du velger avgjør hva slags tegn som kan angis i feltet. Eksempel: bare heltall kan angis i felt som er av heltall-datatypen.<br><br>Angi et <b>navn</b> for feltet. Navnet må være alfanumerisk og kan ikke inneholde mellomrom. Understrek er tillatt.<br><br><b>Visningsetikett</b>er etiketten som vises for feltene i modulgrensesnittene. <b>Systemetikett</b> brukes for å referere til feltet i koden.<br><br>Avhengig av valgt datatype for felte, kan alle eller noen av følgende egenskaper angis for feltet:<br><br><b>Hjelpetekst</b> vises midlertidig om brukeren holder pekeren over feltet, og kan brukes for å be brukeren angi ønsket tekst.<br><br><b>Kommentartekst</b> vises bare i Studio og/eller modulbyggeren, og kan ikke brukes for å beskrive feltet for administratorer.<br><br><b>Standard verdi</b> vises i feltet. Brukere kan angi en ny verdi i feltet eller bruke standardverdien.<br><br>Velg <b>Masseoppdatering</b>-boksen for å kunne bruke masseoppdaterings-funksjonen for feltet.<br><br><b>Maks størrelse</b>-verdien avgjør maks antall tegn som kan angis i feltet.<br><br>Velg <b>Obligatorisk felt</b>-boksen for å gjøre feltet obligatorisk. En verdi må angis for feltet for å kunne lagre en post med feltet.<br><br>Velg <b>Rapporterbar</b>-boksen for at feltet skal kunne brukes for filter og for å vise data i rapporter.<br><br>Velg <b>Revisjon</b>-boksen for å kunne spore endringer i feltet i endringsloggen.<br><br>Velg et alternativ i <b>Importerbar</b>-feltet for å tillate, ikke tillate eller kreve at feltet importeres inn i importeringsveiviseren.<br><br>Velg et alternativ i <b>Duplikat-sammenslåing</b>-fetet for å aktivere eller deaktivere funksjonene Slå sammen duplikater og Finn duplikater.<br><br>Ytterligere egenskaper kan angis for visse datatyper.',
        'editField' => 'Egenskapene for dette feltet kan tilpasses.<br /><br />Klikk Klon å opprette et nytt felt med de samme egenskaper.',
        'mbeditField' => '<b>Visningsetiketten</b> til et malfelt kan tilpasses. De andre feltegenskapene kan ikke tilpasses.<br><br>Klikk på <b>Klone</b> for å opprette et nytt felt med samme egenskaper.<br><br>For å fjerne et malfelt slik at det ikke vises i modulen, fjern feltet fra tilsvarende <b>grensesnitt</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Eksporter tilpasninger fra Studio ved å lage pakker som kan lastes opp til andre Sugar-instanser gjennom <b>modulinnlasteren</b>.<br><br> Først må du angi et <b>pakkenavn</b>.  Du kan også angi informasjon om <b>forfatter</b> og <b>beskrivelse</b> for pakken.<br><br>Velg modulen(e) som inneholder tilpasningene du vil eksportere. Det er bare moduler med tilpasningern som vises.<br><br>Klikk på <b>Eksporter</b> for å opprette en .zip-fil for pakken med tilpasningene.',
        'exportCustomBtn'=>'Klikk Eksportér for å skape en .zip-fil for pakken som inneholder tilpasningene du ønsker å eksportere.',
        'name'=>'Dette er navnet på pakken. Dette navnet vil bli vist under installasjonen.',
        'author'=>'Dette er forfatteren som vises under installasjon som navn på foretaket som opprettet pakken. Forfatteren kan være enten en person eller et selskap.',
        'description'=>'Dette er pakkens Beskrivelse som vises under installasjonen.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Velkommen til Developer Tools området. <br /><br />Bruk verktøyene innenfor dette området for å opprette og administrere standard og tilpassede moduler og felt.',
        'studioBtn'	=> 'Bruk Studio for å tilpasse utplasserte moduler.',
        'mbBtn'		=> 'Bruk Modul Builder til å lage nye moduler.',
        'sugarPortalBtn' => 'Bruk Sugar Portal Editor for å administrere og tilpasse Sugar Portal.',
        'dropDownEditorBtn' => 'Bruk Dropdown Editor for å legge til og redigere de globale rullegardinlistene for rullegardinfelt.',
        'appBtn' 	=> 'Applikasjon modus er der du kan tilpasse forskjellige egenskaper av programmet, for eksempel hvor mange TPS rapporter som skal vises på hjemmesiden',
        'backBtn'	=> 'Gå tilbake til forrige trinn.',
        'studioHelp'=> 'Bruk <b>Studio</b> for å fastslå hvilken og hvordan informasjon vises i modulene.',
        'studioBCHelp' => ' indikerer at modulen er en bakoverkompatibel modus',
        'moduleBtn'	=> 'Klikk for å redigere modulen.',
        'moduleHelp'=> 'Komponentene som du kan tilpasse for modulen vises her.<br><br>Klikk på et ikon for å velge komponenten du vil redigere.',
        'fieldsBtn'	=> 'Opprett og tilpass <b>felt</b> for å lagre informasjon i modulen.',
        'labelsBtn' => 'Rediger <b>etikettene</b> som vises for feltene og andre titler i modulen.'	,
        'relationshipsBtn' => 'Legg til nye eller se eksisterende <b>relasjoner</b> for modulen.' ,
        'layoutsBtn'=> 'Tilpass modulen <b>Grensesnitt</b>. Grensesnittene er de ulike visningene av modulen med felt.<br><br>Du kan bestemme hvilke felt som vises og hvordan de er organisert i hvert grensesnitt.',
        'subpanelBtn'=> 'Fastslå hvilke felt som vises i <b>underpanelen</b> i modulen.',
        'portalBtn' =>'Tilpass modulen <b>Grensesnitt</b> som vises i <b>Sugar Portal</b>.',
        'layoutsHelp'=> 'Modulen <b>Grensesnitt</b> kan tilpasses her.<br><br>Grensesnittets visningsfelt og feltdata.<br><br>Klikk på et ikon for å velge grensesnittet du vil redigere.',
        'subpanelHelp'=> '<b>Underpanelene</b> i modulen kan tilpasses her.<br><br>Klikk på et ikon for å velge modulen du vil redigere.',
        'newPackage'=>'Klikk på <b>Ny pakke</b> for å opprette en ny pakke.',
        'exportBtn' => 'Klikk på <b>Eksporter tilpasninger</b> for å opprette og laste ned en pakke med tilpasninger fra Studio for bestemte moduler.',
        'mbHelp'    => 'Bruk <b>modulbyggeren</b> for å opprette pakker med tilpassede moduler basert på standard- eller tilpassede objekter.',
        'viewBtnEditView' => 'Tilpass modulens <b>EditView</b>-grensesnitt.<br><br>EditView er skjemaet som inneholder inndatafeltene for opptak av brukerangitte dataer.',
        'viewBtnDetailView' => 'Tilpass modulens <b>DetailView</b>-grensesnitt.<br><br>DetailView viser brukerangitte feltdataer.',
        'viewBtnDashlet' => 'Tilpass modulens <b>Sugar Dashlet</b>, inkludert Sugar Dashlets ListView og søk.<br><br>Sugar Dashlet kan legges til sidene i hjemmemodulen.',
        'viewBtnListView' => 'Tilpass modulens <b>ListView</b>-grensesnitt.<br><br>Søkeresultatene vises i ListView.',
        'searchBtn' => 'Tilpass modulens <b>søkegrensesnitt</b>.<br><br>Fastslå hvilke felt som kan brukes for å filtrere postene som vises i ListView.',
        'viewBtnQuickCreate' =>  'Tilpass modulens <b>QuickCreate</b>-grensesnitt.<br><br> QuickCreate-skjemaet vises i underpaneler og i e-postmodulen.',

        'searchHelp'=> '<b>Søkeskjemaene</b> kan tilpasses her.<br><br>Søkeskjemaene inneholder felt for filtrering av poster.<br><br>Klikk på et ikon for å velge søkegrensesnittet du vil redigere.',
        'dashletHelp' =>'<b>Sugar Dashlet</b>-grensesnittet kan tilpasses her.<br><br>Sugar Dashlet kan legges til sidene i hjemmemodulen.',
        'DashletListViewBtn' =>'<b>Sugar Dashlet ListView</b> viser poster basert på Sugar Dashlet-søkefiltrene.',
        'DashletSearchViewBtn' =>'<b>Sugar Dashlet-søk</b> filtrerer postene for Sugar Dashlet-listevisning.',
        'popupHelp' =>'<b>Sprettoppvindu</b>-grensesnitt kan tilpasses her.<br>',
        'PopupListViewBtn' => '<b>Sprettoppvindu ListView</b>-grensesnitt brukes for å se en liste over poster når du velger én eller flere poster som skal relateres til den aktuelle posten.',
        'PopupSearchViewBtn' => 'Med <b>sprettoppsøk</b>-grensesnittet kan brukerne søke etter poster som er relaterte til den aktuelle posten, og vises over sprettopp-listevisningen i det samme vinduet. Eldre moduler bruker dette grensesnittet for sprettoppsøk mens Sidecar-modulene bruker søk-grensesnittkonfigurasjon.',
        'BasicSearchBtn' => 'Tilpass skjemaet for <b>grunnleggende søk</b> som vises i grunnleggende søk-fanen i søkeområdet til modulen.',
        'AdvancedSearchBtn' => 'Tilpass skjemaet for <b>avansert søk</b> som vises i avansert søk-fanen i søkeområdet til modulen.',
        'portalHelp' => 'Administrer og tilpass <b>Sugar Portal</b>.',
        'SPUploadCSS' => 'Last opp et <b>stilark</b> for Sugar Portal.',
        'SPSync' => '<b>Synkroniser</b> tilpasninger til Sugar Portal-instansen.',
        'Layouts' => 'Tilpass <b>grensesnittene</b> til Sugar Portal-modulene.',
        'portalLayoutHelp' => 'Modulene i Sugar Portal vises i dette området.<br><br>Velg en modul for å redigere <b>grensesnittene</b>.',
        'relationshipsHelp' => 'Alle <b>Relasjonene</b> som finnes mellom modulen og andre brukte moduler vises her.<br><br><b>Relasjonsnavnet</b> er det systemgenererte navnet for relasjonen.<br><br><b>Primærmodul</b> er modulen som eier relasjonene.  Eksempel: Alle relasjonsegenskapene hvor kontomodulen er primærmodul lagres i kontodatabasetabellene.<br><br><b>Type</b> er relasjonstypen som finnes mellom primærmodulen og <b>relatert modul</b>.<br><br>Klikk på en kolonnetittel for å sortere etter kolonne.<br><br>Klikk på en rad i relasjonstabellen for å se egenskapene knyttet til relasjonen.<br><br>Klikk på <b>Legg til relasjon</b> for å opprette en ny relasjon.<br><br>Relasjoner kan opprettes mellom hvilke som helst to moduler i bruk.',
        'relationshipHelp'=>'<b>Relasjoner</b> kan opprettes mellom modulen og en modul i bruk.<br><br>Relasjoner uttrykkes visuelt gjennom underpaneler og relaterte felt i modulpostene.<br><br>Velg en av de følgende <b>relasjonstypene</b> for modulen:<br><br><b>En-til-en</b> – begge modulenes poster kommer til å inneholde relaterte felt.<br><br><b>En-til-mange</b> – primærmodulens post kommer til å inneholde et underpanel, og den relaterte modulens post kommer til å inneholde et relatert felt.<br><br><b>Mange-til-mange</b> – begge modulenes poster kommer til å vise underpaneler.<br><br> Velg <b>Relatert modul</b> for relasjonen. <br><br>Hvis relasjonstypen involverer underpaneler, velger du underpanelvisning for de passende modulene.<br><br>Klikk på <b>Lagre</b> for å opprette relasjonen.',
        'convertLeadHelp' => "Her kan du legge til moduler til konverter grensesnittskjermen og modifisere innstillingene for eksisterende moduler.<br/><br/>
        <b>Bestilling:</b><br/>
        Kontakter, kontoer og muligheter må opprettholde rekkefølgen sin. Du kan endre rekkefølgen av andre moduler ved å dra raden i tabellen.<br/><br/>
        <b>Avhengighet:</b><br/>
        Hvis muligheter er inkludert må kontoer enten kreves eller fjernes fra konverter grensesnitt.<br/><br/>
        <b>Modul:</b> Modulnavnet.<br/><br/>
        <b>Obligatorisk:</b> Obligatoriske moduler som må opprettes eller velges før leaden kan konverteres.<br/><br/>
        <b>Kopier data:</b> Hvis valgt, kommer felt fra leadene til å kopieres til feltene med samme navn i de nyopprettede postene.<br/><br/>
        <b>Slett:</b> Fjern denne modulen fra konverter grensesnitt.<br/><br/>        ",
        'editDropDownBtn' => 'Rediger en global rullegardinliste',
        'addDropDownBtn' => 'Legg til en ny global rullegardinliste',
    ),
    'fieldsHelp'=>array(
        'default'=>'<b>Feltene</b> i modulen er oppført her etter feltnavn.<br><br>Modulmalen inneholder et forhåndsdefinert feltsett.<br><br>For å opprette et nytt felt, klikk på <b>Legg til felt</b>.<br><br>For å redigere et felt, klikk på <b>Feltnavn</b>.<br/><br/>Etter modulen er tatt i bruk, kommer de nye feltene du lagde i modulbyggeren til å anses som standardfelt i Studio sammen med malfeltene.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>Relasjonene</b> som er opprettet mellom modulen og andre moduler vises her.<br><br><b>Relasjonsnavnet</b> er det systemgenererte navnet for relasjonen.<br><br><b>Primærmodul</b> er modulen som eier relasjonene. Relasjonsegenskapene lagres i databasetabellene som tilhører primærmodulen.<br><br><b>Type</b> er relasjonstypen som finnes mellom primærmodulen og <b>relatert modul</b>.<br><br>Klikk på en kolonnetittel for å sortere etter kolonne.<br><br>Klikk på en rad i relasjonstabellen for å se og redigere egenskapene knyttet til relasjonen.<br><br>Klikk på <b>Legg til relasjon</b> for å opprette en ny relasjon.',
        'addrelbtn'=>'hold musen over hjelp for å legge til relasjon',
        'addRelationship'=>'<b>Relasjoner</b> kan opprettes mellom modulen og en annen tilpasset modul eller en modul i bruk.<br><br>Relasjoner uttrykkes visuelt gjennom underpaneler og relaterte felt i modulpostene.<br><br>Velg en av de følgende <b>relasjonstypene</b> for modulen:<br><br><b>En-til-en</b> – begge modulenes poster kommer til å inneholde relaterte felt.<br><br><b>En-til-mange</b> – primærmodulens post kommer til å inneholde et underpanel, og den relaterte modulens post kommer til å inneholde et relatert felt.<br><br><b>Mange-til-mange</b> – begge modulenes poster kommer til å vise underpaneler.<br><br> Velg <b>Relatert modul</b> for relasjonen. <br><br>Hvis relasjonstypen involverer underpaneler, velger du underpanelvisning for de passende modulene.<br><br>Klikk på <b>Lagre</b> for å opprette relasjonen.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Etikettene</b> for feltene og andre titler i modulen kan endres.<br><br>Rediger etiketten ved å klikke i feltet, angi en ny etikett og klikk på <b>Lagre</b>.<br><br>Hvis noen språkpakker er installert i programmet, kan du velge <b>Språk</b> for å bruke med etikettene.',
        'saveBtn'=>'Klikk på <b>Lagre</b> for å lagre alle endringene.',
        'publishBtn'=>'Klikk på <b>Lagre og bruk</b> for å lagre alle endringene og gjøre dem aktive.',
    ),
    'portalSync'=>array(
        'default' => 'Angi <b>Sugar Portal URL-en</b> for portalinstansen du vil oppdatere, og klikk på <b>Kjør</b>.<br><br>Angi et gyldig Sugar-brukernavn og -passord og klikk på <b>Begynn synkronisering</b>.<br><br>Tilpasningene utført i Sugar Portal-<b>grensesnittene</b> overføres til den angitte portalinstansen sammen med det eventuelle <b>stilarket</b>.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Du kan tilpasse utseendet for Sugar Portal med et stilark.<br><br>Velg et <b>stilark</b> for å laste det opp.<br><br>Stilarket implementeres i Sugar Portal neste gang den synkroniseres.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'For å komme i gang med et prosjekt, klikk på <b>Ny pakke</b> for å opprette en ny pakke for dine tilpassede moduler. <br/><br/>Pakkene inneholder én eller flere moduler.<br/><br/>Du vil f. eks. kunne opprette en pakke med én tilpasset modul som er relatert til standard kontomodulen. Eller kanskje du vil opprette en pakke med flere nye moduler som jobber sammen som et prosjekt og relateres til hverandre og andre moduler som allerede er i programmet.',
            'somepackages'=>'En <b>pakke</b> fungerer som en beholder for tilpassede moduler – alle er del av ett prosjekt. Pakken kan inneholde én eller flere tilpassede <b>moduler</b> som kan relateres til hverandre og til de andre modulene i programmet.<br/><br/>Etter du har opprettet en pakke for prosjektet, kan du opprette moduler for pakken med en gang, eller gå tilbake til modulbyggeren senere for å fullføre prosjektet.<br><br>Når prosjektet er fullført, kan du <b>bruke</b> pakken for å installere tilpassede moduler i programmet.',
            'afterSave'=>'Den nye pakken må inneholde minst én modul. Du kan opprette én eller flere tilpassede moduler for pakken.<br/><br/>Klikk på <b>Ny modul</b> for å opprette en tilpasset modul for pakken.<br/><br/>Etter du har opprettet minst én modul, kan du publisere eller bruke pakken for å gjøre den tilgjengelig i instansen din og/eller andre brukeres instanser.<br/><br/>For å bruke pakken i Sugar-instansen din, klikker du på <b>Bruk</b>.<br><br>Klikk på <b>Publiser</b> for å lagre pakken som en .zip-fil. Etter .zip-filen er lagret til systemet ditt, bruker du <b>modulinnlasteren</b> for å laste opp og installere pakken i Sugar-instansen din.<br/><br/>Du kan distribuere filen til andre brukere, slik at de kan laste opp og installere den i sine egne Sugar-instanser.',
            'create'=>'En <b>pakke</b> fungerer som en beholder for tilpassede moduler – alle er del av ett prosjekt. Pakken kan inneholde én eller flere tilpassede <b>moduler</b> som kan relateres til hverandre og til de andre modulene i programmet.<br/><br/>Etter du har opprettet en pakke for prosjektet, kan du opprette moduler for pakken med en gang, eller gå tilbake til modulbyggeren senere for å fullføre prosjektet.',
            ),
    'main'=>array(
        'welcome'=>'Bruk <b>utviklerverktøyene</b> for å opprette og administrere standard- og tilpassede moduler og felt.<br/><br/>For å administrere moduler i programmet, klikk på <b>Studio</b>.<br/><br/>For å opprette tilpassede moduler, klikk på <b>modulbygger</b>.',
        'studioWelcome'=>'Alle de nåværende installerte modulene, inkludert standard og modulinnlastede objekter, kan tilpasses i Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Siden den nåværende pakken inneholder minst én modul, kan du <b>bruke</b> modulene i pakken i Sugar-instansen eller <b>publisere</b> pakken slik at den installeres i den nåværende Sugar-instansen eller i en annen instans med <b>modulinnlasteren</b>.<br/><br/>For å installere pakken direkte i Sugar-instansen klikker du på <b>bruk</b>.<br><br>For å opprette en .zip-fil for pakken som kan lastes og installeres i nåværende Sugar-instans og i andre instanser med <b>modulinnlasteren</b> klikker du på <b>publiser</b>.<br/><br/>Du kan bygge modulene for pakken i faser og publisere eller bruke dem når du er klar. <br/><br/>Etter publisering eller bruk av pakken, kan du utføre endringer til pakkeegenskapene og tilpasse modulene ytterligere.  Deretter publiserer eller bruker du pakken på nytt for å påføre endringene." ,
        'editView'=> 'Her kan du redigere de eksisterende feltene. Du kan fjerne eksisterende felt eller legge til tilgjengelige felt i panelet til venstre.',
        'create'=>'Når du velger <b>type</b> for modulen du vil opprette, må du tenke på felttypene du vil ha i modulen. <br/><br/>Hver modulmal består av et sett med felt som tilhører modultypen beskrevet i tittelen.<br/><br/><b>Grunnleggende</b> – tilbyr grunnleggende felt som vises i standardmoduler, som navn, tildelt til, gruppe, dato opprett og beskrivelsesfelt.<br/><br/><b>Bedrift</b> – tilbyr organisasjonsbestemte felt, som bedriftsnavn, industri og fakturaadresse.  Bruk denne malen for å lage moduler som ligner på standard kontomodulen.<br/><br/><b>Person</b> – tilbyr individbestemte felt som åpningsfrase, tittel, navn, adresse og telefonnummer.  Bruk denne malen for å lage moduler som ligner på standard kontakt- og emne-moduler.<br/><br/><b>Utsted</b> – tilbyr sak- og feilbestemte felt, som nummer, status, prioritet og beskrivelse.  Bruk denne malen for å opprette moduler som ligner på standard sak- og feilfølgermoduler.<br/><br/>Merk: Etter du oppretter modulen, kan du redigere feltetikettene laget av malen, i tillegg til å lage tilpassede felt som du kan legge til modulgrensesnittet.',
        'afterSave'=>'Tilpass modulen for å passe behovene dine ved å redigere og opprette felt, etablere relasjoner med andre moduler og å arrangere feltene i grensesnittet.<br/><br/>For å se malfeltene og håndtere tilpassede felt i modulen klikker du på <b>Vis felt</b>.<br/><br/>For å opprette og administrere forhold mellom modulen og andre moduler – både modulene som allerede er i programmet og andre tilpassede moduler i samme pakke – klikk på <b>Vis relasjon</b>.<br/><br/>For å redigere modulgrensesnittet klikker du på <b>Vis grensesnitt</b>. Du kan endre grensesnittet for detaljvisning, rediger-visning og listevisning for modulen, akurat som for modulene du allerede har i Studio.<br/><br/>For å opprette moduler med samme egenskaper som nåværende modul klikker du på <b>Dupliser</b>.  Du kan tilpasse den nye modulen ytterligere.',
        'viewfields'=>'Feltene i modulen kan tilpasses til å passe behovene dine.<br/><br/>Du kan ikke slette standardfeltene, men du kan fjerne dem fra tilsvarende grensesnitt på grensesnittsidene. <br/><br/>Du kan raskt opprette nye felt med lignende egenskaper for eksisterende felt ved å klikke på <b>Klone</b> i <b>Egenskaper</b>-skjemaet.  Angi nye egenskaper og klikk på <b>Lagre</b>.<br/><br/>Det anbefales at du angir alle egenskapene for standardfeltene og de tilpassede feltene før du publiserer og installerer pakken med den tilpassede modulen.',
        'viewrelationships'=>'Du kan opprette mange-til-mange-relasjoner mellom den aktuelle modulen og andre moduler i pakken, og/eller mellom den aktuelle modulen og moduler som allerede er installert i programmet.<br><br>For å opprette en-til-mange- og en-til-en-relasjoner, opprett <b>Relater</b>- og <b>Fleks-relater</b>-felt for modulene.',
        'viewlayouts'=>'Du kan kontrollere hvilke felt som er tilgjengelig for opptak av data i <b>Rediger-visning</b>.  Du kan også kontrollere hvilke data som vises i <b>Detaljvisning</b>.  Visningene trenger ikke å samsvare. <br/><br/>Hurtigopprettelsesskjemaet vises når du klikker på <b>Opprett</b> i en moduls underpanel.  Som standard er grensesnittet for <b>hurtigopprettelsesskjemaet</b> det samme som standardgrensesnittet for <b>Rediger visning</b>. Du kan tilpasse hurtigtilpasningsskjemaet slik at det inneholder færre og/eller andre felt enn grensesnittet for rediger-visning. <br><br>Du kan fastslå modulsikkerheten ved å bruke grensesnittilpassing med <b>Administrasjon av roller</b>.<br><br>',
        'existingModule' =>'Etter du har opprettet og tilpasset modulen, kan du opprette ytterligere moduler eller gå tilbake til pakken for å <b>Publisere</b> eller <b>Bruke</b> pakken.<br><br>For å opprette ytterligere moduler, klikk på <b>Dupliser</b> for å opprette en modul med samme egenskaper som nåværende modul, eller gå tilbake til pakken og klikk på <b>Ny modul</b>.<br><br>Hvis du er klar til å <b>publisere</b> eller <b>bruke</b> pakken med denne modulen, kan du gå tilbake til pakken for å utføre disse funksjonene. Du kan publisere og bruke pakker som inneholder minst én modul.',
        'labels'=> 'Etikettene til standardfeltene og tilpassede felt kan endres. Endring av feltetikettene påvirker ikke dataene lagret i feltene.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Det ligger tre kolonner til venstre. «Standard»-kolonnen inneholder feltene som vises som standard i listevisningen, «tilgjengelig»-kolonnen viser feltene brukeren kan velge å bruke for å opprette en tilpasset listevisning, og «skjult»-kolonnen inneholder felt som er tilgjengelig for deg som administrator, men utilgjengelige for andre brukere, som du kan legge til som standard- eller tilgjengelig-kolonnene for brukere.',
        'savebtn'	=> 'Klikk <b>Lagre </b> for å lagre endringene og gjøre dem aktive.',
        'Hidden' 	=> 'Skjulte felt er felt som ikke er tilgjengelige for brukere for bruk i listevisninger.',
        'Available' => 'Tilgjengelige felt er felt som ikke vises som standard, men som kan aktiveres av brukerne.',
        'Default'	=> 'Standardfeltene vises for brukere som ikke har konfigurert tilpassede innstillinger for listevisning.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Det ligger to kolonner til venstre. «Standard»-kolonnen inneholder feltene som vises som standard i listevisningen, og «skjult»-kolonnen som inneholder felt som du som administrator kan legge til i visningen.',
        'savebtn'	=> 'Klikk <b>Lagre og Ta i bruk</b> for å lagre endringene du har gjort, og for å gjøre dem aktive',
        'Hidden' 	=> '<b>Skjulte felt vil ikke vises i Søk.</b>',
        'Default'	=> '<b>Default</b>-felter vises i Søket.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Det er to kolonner som vises til venstre. Kolonnen til høyre, merket Eksisterende Utseende eller Forhåndsvisning, er der du endrer utseendet på modulen. Kolonnen til venstre, merket Verktøykasse, inneholder nyttige elementer og verktøy som kan benyttes når man endrer utseendet.<br />Om utseende-området er merket Eksisterende Utseende så betyr det at du arbeider med en kopi av utseendet som benyttes av modulen akkurat nå.<br />Om utseende-området er merket Forhåndsvisning så er det en kopi som har blitt endret tidligere og deretter lagret, og som allerede kan ha blitt endret bort fra den versjonen som brukerne av modulen ser nå.',
        'saveBtn'	=> 'Ved å klikke på denne knappen lagres utseendet slik at du kan lagre dine endringer. Når du returnerer til denne modulen så vil du fortsette med dette endrede utseendet. Ditt utseende vil derimot ikke være synlig for brukere av modulen inntil du klikker Lagre og Publisér-knappen.',
        'publishBtn'=> 'Klikk denne knappen for å publisere utseendet. Dette betyr at dette utseende umiddelbart vil bli synlig for brukere av denne modulen.',
        'toolbox'	=> 'Verkrøykassen inneholder et sett av nyttige egenskaper for å endre utseende, inklusive en søppelkasse, et sett av tilleggselementer og et sett tilgjengelige felter. Samtlige kan dras & slippes der du ønsker.',
        'panels'	=> 'Dette området viser hvordan ditt valgte utseende vil se ut for brukere av denne modulen når den har blitt distribuert.<br />Du kan reorganisere elementer slik som felter, rader og paneler ved å dra & slippe dem; slett elementer ved å dra & slippe dem i søppelkassen i verktøykassen, eller skap nye elementer ved å dra dem fra verktøykassen og slipp dem på ønsket sted.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Det er to kolonner som vises til venstre. Kolonnen til høyre, merket Eksisterende Utseende eller Forhåndsvisning, er der du endrer utseendet på modulen. Kolonnen til venstre, merket Verktøykasse, inneholder nyttige elementer og verktøy som kan benyttes når man endrer utseendet.<br />Om utseende-området er merket Eksisterende Utseende så betyr det at du arbeider med en kopi av utseendet som benyttes av modulen akkurat nå.<br />Om utseende-området er merket Forhåndsvisning så er det en kopi som har blitt endret tidligere og deretter lagret, og som allerede kan ha blitt endret bort fra den versjonen som brukerne av modulen ser nå.',
        'dropdownaddbtn'=> 'Ved å klikke på denne knappen legges det et nytt element til nedtrekksmenyen.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Tilpasninger gjort i Studio innenfor dette området kan bli pakket og sendt til andre områder.<br />Gi pakken et pakkenavn. Du kan definere Forfatter og Beskrivelse av pakken.<br />Velg modulen(e) som inneholder tilpasningene som skal eksporteres. (Kun moduler som inneholder tilpasninger vil være mulig å velge)<br />Klikk Eksportér for å skape en .zip-fil for pakken som inneholder tilpasningene. .zip-filen kan bli lastet opp til et annet område ved bruk av Modul-lasteren.',
        'exportCustomBtn'=>'Klikk Eksportér for å skape en .zip-fil for pakken som inneholder tilpasningene du ønsker å eksportere.',
        'name'=>'Navnet på pakken vil vises i Modul-lasteren etter at pakken er lastet opp for å bli installert i Studio.',
        'author'=>'Forfatter er navnet på den enhet som skapte pakken. Forfatteren kan enten være en person eller et firma.<br />Forfatteren vil vises i Modul-lasteren etter at modulen er lastet opp for å bli installert i Studio.',
        'description'=>'Beskrivelsen av pakken vil bli vist i Modul-lasteret etter at pakken er lastet opp for installasjon i Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Velkommen til området for <b>utviklerverktøy</b1>. <br/><br/>Bruk verktøyene i dette området for å opprette og administrere standard og tilpassede moduler og felt.',
        'studioBtn'	=> 'Bruk <b>Studio</b> for å tilpasse installerte moduler ved å endre feltoppsettet, velge hvilke felt som skal være tilgjengelige og å opprette tilpassede datafelt.',
        'mbBtn'		=> 'Bruk Modul Builder til å lage nye moduler.',
        'appBtn' 	=> 'Bruk programmodusen for å tilpasse ulike egenskaper for programmet, f.eks. hvor mange TPS-rapporter som vises på startsiden',
        'backBtn'	=> 'Gå tilbake til forrige trinn.',
        'studioHelp'=> 'Bruk <b>Studio</b> for å tilpasse installerte moduler.',
        'moduleBtn'	=> 'Klikk for å redigere modulen.',
        'moduleHelp'=> 'Velg modulkomponentene du vil redigere',
        'fieldsBtn'	=> 'Rediger hvilken informasjon som lagres i modulen ved å kontrollere <b>feltene</b> i modulen.<br/><br/>Du kan redigere og opprette tilpassede felt her.',
        'layoutsBtn'=> 'Tilpass <b>grensesnittene</b> til visningene Rediger, Detalj, Liste og Søk.',
        'subpanelBtn'=> 'Rediger hvilken informasjon som vises i modulenes underpaneler.',
        'layoutsHelp'=> 'Velg <b>Grensesnitt å redigere</b>.<br/><br/>For å endre grensesnittet som inneholder datafelt for dataangivelse, klikk på <b>Rediger-visning</b>.<br/><br/>For å endre grensesnittet som viser de angitte dataene i feltene i rediger-visningen, klikk på <b>Detaljvisning</b>.<br/><br/>For å endre kolonnene som vises i standardlisten, klikk på <b>Listevisning</b>.<br/><br/>For å endre grunnleggende og avansert søkeskjemagrensesnitt, klikk på <b>Søk</b>.',
        'subpanelHelp'=> 'Velg et <b>underpanel</b> som du vil redigere.',
        'searchHelp' => 'Velg et <b>søkegrensesnitt</b> som du vil redigere.',
        'labelsBtn'	=> 'Rediger <b>etikettene</b> å vise verdier i denne modulen.',
        'newPackage'=>'Klikk på <b>Ny pakke</b> for å opprette en ny pakke.',
        'mbHelp'    => '<b>Velkommen til modulbyggeren.</b><br/><br/>Bruk <b>modulbyggeren</b> til å opprette pakker med tilpassede moduler basert på standard eller tilpassede objekter. <br/><br/>For å begynne, klikk på <b>Ny pakke</b> for å opprette en ny pakke eller å velge en pakke du vil redigere.<br/><br/> En <b>pakke</b> handler som en beholder for tilpassede moduler – alle en del av ett prosjekt. Pakken kan inneholde én eller flere tilpassede moduler som kan relateres til hverandre eller til moduler i programmet. <br/><br/>Eksempler: Du vil kanskje opprette en pakke med én tilpasset modul som er relatert til standard kontomodul. Eller kanskje du vil opprette en pakke med flere nye moduler som jobber sammen som et prosjekt og relateres til hverandre og modulene i programmet.',
        'exportBtn' => 'Klikk på <b>Eksporter tilpasninger</b> for å opprette en pakke som inneholder tilpasninger gjort i Studio for bestemte moduler.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Rediger nedtrekk meny',

//ASSISTANT
'LBL_AS_SHOW' => 'Vis Assistenten i framtiden',
'LBL_AS_IGNORE' => 'Ignorer Assistent.',
'LBL_AS_SAYS' => 'Assistenten Sier:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Modul Bygger',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Dropdown Editor',
'LBL_EDIT_DROPDOWN'=>'Rediger Dropdown',
'LBL_DEVELOPER_TOOLS' => 'Utvikler verktøy',
'LBL_SUGARPORTAL' => 'Sugar Portal Editor',
'LBL_SYNCPORTAL' => 'Synrkonsier portalen',
'LBL_PACKAGE_LIST' => 'Pakkeliste',
'LBL_HOME' => 'Hjem',
'LBL_NONE'=>'-Ingen-',
'LBL_DEPLOYE_COMPLETE'=>'Ferdig distribuert',
'LBL_DEPLOY_FAILED'   =>'En feil har oppstått under distribusjons prosessen, det kan hende at pakken ikke er riktig installert',
'LBL_ADD_FIELDS'=>'Add Custom Fields',
'LBL_AVAILABLE_SUBPANELS'=>'Tilgjengelige underpanel',
'LBL_ADVANCED'=>'Avansert',
'LBL_ADVANCED_SEARCH'=>'Avansert søk',
'LBL_BASIC'=>'Grunnleggende',
'LBL_BASIC_SEARCH'=>'Grunnleggende søk',
'LBL_CURRENT_LAYOUT'=>'Oppsett',
'LBL_CURRENCY' => 'Valuta',
'LBL_CUSTOM' => 'Tilpasset',
'LBL_DASHLET'=>'Sugar-dashlet',
'LBL_DASHLETLISTVIEW'=>'Sugar Dashlet listevisning',
'LBL_DASHLETSEARCH'=>'Sugar Dashlet søk',
'LBL_POPUP'=>'PopupVisning',
'LBL_POPUPLIST'=>'Popup listevisning',
'LBL_POPUPLISTVIEW'=>'Popup listevisning',
'LBL_POPUPSEARCH'=>'Popup søk',
'LBL_DASHLETSEARCHVIEW'=>'Sugar Dashlet Søk',
'LBL_DISPLAY_HTML'=>'Display HTML Code',
'LBL_DETAILVIEW'=>'Detaljvisning',
'LBL_DROP_HERE' => '[Slipp her]',
'LBL_EDIT'=>'Rediger',
'LBL_EDIT_LAYOUT'=>'Rediger oppsett',
'LBL_EDIT_ROWS'=>'Rediger rekker',
'LBL_EDIT_COLUMNS'=>'Rediger Kolonner',
'LBL_EDIT_LABELS'=>'Rediger Merker',
'LBL_EDIT_PORTAL'=>'Rediger portal for',
'LBL_EDIT_FIELDS'=>'Rediger felt',
'LBL_EDITVIEW'=>'Rediger visning',
'LBL_FILTER_SEARCH' => "Søk",
'LBL_FILLER'=>'(fyll)',
'LBL_FIELDS'=>'Felt',
'LBL_FAILED_TO_SAVE' => 'Mislykkes i å lagre',
'LBL_FAILED_PUBLISHED' => 'Mislykkes i å publisere',
'LBL_HOMEPAGE_PREFIX' => 'Min',
'LBL_LAYOUT_PREVIEW'=>'Oppsett forhåndsvisning',
'LBL_LAYOUTS'=>'Oppsett',
'LBL_LISTVIEW'=>'Listevisning',
'LBL_RECORDVIEW'=>'Listevisning',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Ny pakke',
'LBL_NEW_PANEL'=>'Nytt panel',
'LBL_NEW_ROW'=>'Ny rekke',
'LBL_PACKAGE_DELETED'=>'Pakke slettet',
'LBL_PUBLISHING' => 'Publiserer...',
'LBL_PUBLISHED' => 'Publisert',
'LBL_SELECT_FILE'=> 'Velg fil',
'LBL_SAVE_LAYOUT'=> 'Lagre oppsett',
'LBL_SELECT_A_SUBPANEL' => 'Velg et underpanel',
'LBL_SELECT_SUBPANEL' => 'Velg underpanel',
'LBL_SUBPANELS' => 'Underpanel',
'LBL_SUBPANEL' => 'Underpanel',
'LBL_SUBPANEL_TITLE' => 'Tittel:',
'LBL_SEARCH_FORMS' => 'Søk',
'LBL_STAGING_AREA' => 'Staging Area (drag and drop items here)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar Felt (click items to add to staging area)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (click items to add to staging area)',
'LBL_TOOLBOX' => 'Verktøykasse',
'LBL_VIEW_SUGAR_FIELDS' => 'Vis Sugar Felt',
'LBL_VIEW_SUGAR_BIN' => 'Vis Sugar Bin',
'LBL_QUICKCREATE' => 'QuickCreate',
'LBL_EDIT_DROPDOWNS' => 'Rediger en Global Dropdown',
'LBL_ADD_DROPDOWN' => 'Legg til en ny Global Dropdown',
'LBL_BLANK' => '-tom-',
'LBL_TAB_ORDER' => 'Tab Rekkefølge',
'LBL_TAB_PANELS' => 'Vis paneler som faner',
'LBL_TAB_PANELS_HELP' => 'Vis hvert panel som sin egen fane i stedet for å la alle bli vist i et skjermbilde',
'LBL_TABDEF_TYPE' => 'Vis Tpe',
'LBL_TABDEF_TYPE_HELP' => 'Velg hvordan denne seksjonen skal vises. Dette alternativet har bare effekt hvis du har aktivert faner på denne visningen.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Tabulator',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Velg Panel for å ha denne panel-visningen som en del av oppsett-visningen. Velg Faner for at dette panelet vises i en egen fane i oppsettet. Når Fane er spesifisert for et panel, vil etterfølgende paneler som er satt til å vises som Panel vises innenfor fanen.<br />En ny Fane vil bli startet for neste panel der Fane er valgt. Hvis Fane er valgt for et panel under det første panelet, vil det første panelet nødvendigvis være en Fane.',
'LBL_TABDEF_COLLAPSE' => 'Slå sammen',
'LBL_TABDEF_COLLAPSE_HELP' => 'Slå sammen denne seksjonen som standard når den er definert som et panel',
'LBL_DROPDOWN_TITLE_NAME' => 'Dropdown Navn',
'LBL_DROPDOWN_LANGUAGE' => 'Dropdown Språk',
'LBL_DROPDOWN_ITEMS' => 'Dropdown objekter',
'LBL_DROPDOWN_ITEM_NAME' => 'Objektnavn',
'LBL_DROPDOWN_ITEM_LABEL' => 'Vis etikett',
'LBL_SYNC_TO_DETAILVIEW' => 'Synkroniser til DetailView',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Select this option to sync this EditView layout to the corresponding DetailView layout. Fields and field placement in the EditView will be sync$#39;d and saved to the DetailView automatically upon clicking Save or Save & Deploy in the EditView. <br />Layout changes will not be able to be made in the DetailView.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'This DetailView is sync$#39;d with the corresponding EditView.<br />Fields and field placement in this DetailView reflect the fields and field placement in the EditView.<br />Changes to the DetailView cannot be saved or deployed within this page. Make changes or un-sync the layouts in the EditView.',
'LBL_COPY_FROM' => 'Kopier fra',
'LBL_COPY_FROM_EDITVIEW' => 'Kopier fra EditView',
'LBL_DROPDOWN_BLANK_WARNING' => 'Verdier er nødvendige for både Navn og Display Label. For å legge til et tomt element klikker du Legg til uten å angi noen verdier for Navn og Display Label.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Nøkkelen finnes allerede i listen',
'LBL_DROPDOWN_LIST_EMPTY' => 'Listen må inneholde minst et aktivert element',
'LBL_NO_SAVE_ACTION' => 'Kunne ikke finne lagre handlingen for denne visningen.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: dårlig dannet dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Indikerer et kombinasjonsfelt. Et kombinasjonsfelt er en samling av enkeltfelter. For eksempel er "Addresse" et kombinasjonsfelt som inneholder "Gateadresse", "By", "Postnummer", "Stat" og "Land".<br /><br />Dobbeltklikk et kombinasjonsfelt for å se hvilke felt det inneholder.',
'LBL_COMBO_FIELD_CONTAINS' => 'inneholder:',

'LBL_WIRELESSLAYOUTS'=>'Mobiloppsett',
'LBL_WIRELESSEDITVIEW'=>'Mobil redigeringsvisning',
'LBL_WIRELESSDETAILVIEW'=>'Mobil detaljvisning',
'LBL_WIRELESSLISTVIEW'=>'Mobil listevisning',
'LBL_WIRELESSSEARCH'=>'Mobil søk',

'LBL_BTN_ADD_DEPENDENCY'=>'Legg til avhengighet',
'LBL_BTN_EDIT_FORMULA'=>'Rediger formel',
'LBL_DEPENDENCY' => 'Avhengighet',
'LBL_DEPENDANT' => 'Avhengig',
'LBL_CALCULATED' => 'Kalkulert verdi',
'LBL_READ_ONLY' => 'Skrivebeskyttet',
'LBL_FORMULA_BUILDER' => 'Formeloppsett',
'LBL_FORMULA_INVALID' => 'Ugyldig formel',
'LBL_FORMULA_TYPE' => 'Formelen må være av typen',
'LBL_NO_FIELDS' => 'Ingen felt funnet',
'LBL_NO_FUNCS' => 'Ingen fuksjoner funnet',
'LBL_SEARCH_FUNCS' => 'Søk funksjoner...',
'LBL_SEARCH_FIELDS' => 'Søk felter...',
'LBL_FORMULA' => 'Formel',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Avhengig',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Dra opsjoner fra listen til venstre over tilgjengelige opsjoner i den avhengige nedtrekksmenyen, og til listen til høyre for å gjøre disse opsjonene tilgjengelige når en overordnet opsjon er valgt. Hvis det ikke finnes noe under en overordnet opsjon når den overordnede opsjonen er valgt, så vil ikke den avhengige nedtrekksmenyen vises.',
'LBL_AVAILABLE_OPTIONS' => 'Tilgjengelige Valg',
'LBL_PARENT_DROPDOWN' => 'Overordnet Nedtrekksmeny',
'LBL_VISIBILITY_EDITOR' => 'Synlighets-editor',
'LBL_ROLLUP' => 'Rull opp',
'LBL_RELATED_FIELD' => 'Relatert Felt',
'LBL_CONFIG_PORTAL_URL'=>'URL til tilpasset logo. Anbefalt logo-størrelse er 163 × 18 piksler.',
'LBL_PORTAL_ROLE_DESC' => 'Ikke slett denne rollen. Customer Self-Service Portal-rollen er en systemgenerert rolle opprettet under Sugar Portal-aktiveringsprosessen. Bruk Tilgangskontroller innenfor denne rollen for å aktivere og / eller deaktivere Feil, Saker eller Kunnskapsbase-moduler i Sugar Portal. Ikke endre andre tilgangskontroller for denne rollen, for å unngå ukjent og uforutsigbar systematferd. I tilfelle av utilsiktet sletting av denne rollen, gjenskap den ved å deaktivere og aktivere Sugar Portal.',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Primär modul',
'LBL_CUSTOM_RELATIONSHIPS' => '*forhold opprettet i studio eller Modul bygger',
'LBL_RELATIONSHIPS'=>'Forhold',
'LBL_RELATIONSHIP_EDIT' => 'Rediger forhold',
'LBL_REL_NAME' => 'Navn',
'LBL_REL_LABEL' => 'Merke',
'LBL_REL_TYPE' => 'Type',
'LBL_RHS_MODULE'=>'Relaterte moduler',
'LBL_NO_RELS' => 'Ingen forhold',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Valgfri tilstand' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Kolonne',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Verdi',
'LBL_SUBPANEL_FROM'=>'Underpanel fra',
'LBL_RELATIONSHIP_ONLY'=>'Ingen synlige elementer vil bli opprettet for dette forholdet, siden det er et tidligere- eksisterende forhold mellom disse to modulene.',
'LBL_ONETOONE' => 'En til en',
'LBL_ONETOMANY' => 'En til mange',
'LBL_MANYTOONE' => 'Mange til En',
'LBL_MANYTOMANY' => 'Mange til mange',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Velg en funksjon eller en komponent',
'LBL_QUESTION_MODULE1' => 'Velg en modul',
'LBL_QUESTION_EDIT' => 'Velg en modul å redigere',
'LBL_QUESTION_LAYOUT' => 'Velg et oppsett å redigere',
'LBL_QUESTION_SUBPANEL' => 'Velg et underpanel å redigere',
'LBL_QUESTION_SEARCH' => 'Velg et søkegrensesnitt å redigere.',
'LBL_QUESTION_MODULE' => 'Velg en modulkomponent å redigere.',
'LBL_QUESTION_PACKAGE' => 'Velg en pakke å redigere eller lag en ny pakke.',
'LBL_QUESTION_EDITOR' => 'Velg et verktøy',
'LBL_QUESTION_DROPDOWN' => 'Velg en rullegardinmeny å redigere, eller lag en ny.',
'LBL_QUESTION_DASHLET' => 'Velg et dashletoppsett som skal redigeres.',
'LBL_QUESTION_POPUP' => 'Velg en popup visning for redigering',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Sett i forbindelse med',
'LBL_NAME'=>'Name',
'LBL_LABELS'=>'Merker',
'LBL_MASS_UPDATE'=>'Mass Update',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Standardverdi',
'LBL_REQUIRED'=>'Required',
'LBL_DATA_TYPE'=>'Type',
'LBL_HCUSTOM'=>'TILPASSET',
'LBL_HDEFAULT'=>'STANDARD',
'LBL_LANGUAGE'=>'Språk:',
'LBL_CUSTOM_FIELDS' => '* felt opprettet i Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Rediger merker',
'LBL_SECTION_PACKAGES' => 'Pakker',
'LBL_SECTION_PACKAGE' => 'Pakker',
'LBL_SECTION_MODULES' => 'Moduler',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Rullegardinmenyer',
'LBL_SECTION_PROPERTIES' => 'Egenskaper',
'LBL_SECTION_DROPDOWNED' => 'Dropdown Editor',
'LBL_SECTION_HELP' => 'Hjelp',
'LBL_SECTION_ACTION' => 'Handling',
'LBL_SECTION_MAIN' => 'Primær',
'LBL_SECTION_EDPANELLABEL' => 'Rediger Panelmerker',
'LBL_SECTION_FIELDEDITOR' => 'Rediger felt',
'LBL_SECTION_DEPLOY' => 'Bruk',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Rediger synlighet',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Default',
'LBL_HIDDEN'=>'Skjult',
'LBL_AVAILABLE'=>'Tilgjengelig',
'LBL_LISTVIEW_DESCRIPTION'=>'Det er tre kolonner vist under. <b>Standard</b>kolonnen inneholder felt som er vist i en standard listevisning. <b>Tilleggs</b>kolonnen inneholder felt som en bruker kan velge å bruke for å opprette en tilpasset visning. <b>Tilgjengelige</b> kolonnen viser for deg som en administrator, felt som kan legges til som standard eller tilleggskolonner til bruk for brukerne.',
'LBL_LISTVIEW_EDIT'=>'List View Editor',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Forhåndsvisning',
'LBL_MB_RESTORE'=>'Gjenopprett',
'LBL_MB_DELETE'=>'Slett',
'LBL_MB_COMPARE'=>'Sammenlign',
'LBL_MB_DEFAULT_LAYOUT'=>'Standard oppsett',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Legg til',
'LBL_BTN_SAVE'=>'Lagre',
'LBL_BTN_SAVE_CHANGES'=>'Lagre endringer',
'LBL_BTN_DONT_SAVE'=>'Forkast endringene',
'LBL_BTN_CANCEL'=>'Avbryt',
'LBL_BTN_CLOSE'=>'Lukk',
'LBL_BTN_SAVEPUBLISH'=>'Save & Deploy',
'LBL_BTN_NEXT'=>'Neste',
'LBL_BTN_BACK'=>'Tilbake',
'LBL_BTN_CLONE'=>'Klone',
'LBL_BTN_COPY' => 'Kopiér',
'LBL_BTN_COPY_FROM' => 'Kopier fra …',
'LBL_BTN_ADDCOLS'=>'legg til kolonner',
'LBL_BTN_ADDROWS'=>'Leg til rader',
'LBL_BTN_ADDFIELD'=>'legg til felt',
'LBL_BTN_ADDDROPDOWN'=>'Legg til Dropdown',
'LBL_BTN_SORT_ASCENDING'=>'Sorter stigende',
'LBL_BTN_SORT_DESCENDING'=>'Sorter synkende',
'LBL_BTN_EDLABELS'=>'Rediger merker',
'LBL_BTN_UNDO'=>'Åpne',
'LBL_BTN_REDO'=>'Gjenopprett',
'LBL_BTN_ADDCUSTOMFIELD'=>'Legg til tilpasset felt',
'LBL_BTN_EXPORT'=>'Eksporter tilpasninger',
'LBL_BTN_DUPLICATE'=>'Dupliser',
'LBL_BTN_PUBLISH'=>'Publiser',
'LBL_BTN_DEPLOY'=>'Bruk',
'LBL_BTN_EXP'=>'Eksporter',
'LBL_BTN_DELETE'=>'Slett',
'LBL_BTN_VIEW_LAYOUTS'=>'Vis oppsett',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Vis mobilgrensesnitt',
'LBL_BTN_VIEW_FIELDS'=>'Vis felt',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Vis forhold',
'LBL_BTN_ADD_RELATIONSHIP'=>'Ĺegg til forhold',
'LBL_BTN_RENAME_MODULE' => 'Endre Modulnavn',
'LBL_BTN_INSERT'=>'Sett inn',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Feil: Felt eksisterer allerede',
'ERROR_INVALID_KEY_VALUE'=> "Feil: Ugyldig nøkkelverdi: [$#39;]",
'ERROR_NO_HISTORY' => 'Ingen historiefiler funnet',
'ERROR_MINIMUM_FIELDS' => 'Oppsettet må inneholde minst ett felt',
'ERROR_GENERIC_TITLE' => 'Det oppsto en feil',
'ERROR_REQUIRED_FIELDS' => 'Er du sikker på at du vil fortsette? Følgende påkrevde felt mangler i opppsettet.',
'ERROR_ARE_YOU_SURE' => 'Er du sikker på at du vil fortsette?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Følgende felt har beregnete verdier som ikke vil bli beregnet i sanntid i SugarCRM Mobil redigeringsvisning',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Følgende felt har beregnete verdier som ikke vil bli beregnet i sanntid i SugarCRM Mobil redigeringsvisning',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'De(n) følgende modul(er) er deaktivert(e):',
    'LBL_PORTAL_ENABLE_MODULES' => 'Om du ønsker å aktivere dem i portalen, vennligst gjør det <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">her</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Konfigurér Portal',
    'LBL_PORTAL_THEME' => 'Tema for Portal',
    'LBL_PORTAL_ENABLE' => 'Aktivér',
    'LBL_PORTAL_SITE_URL' => 'Din portal er tilgjengelig på:',
    'LBL_PORTAL_APP_NAME' => 'Applikasjonsnavn',
    'LBL_PORTAL_LOGO_URL' => 'URL til logo',
    'LBL_PORTAL_LIST_NUMBER' => 'Antall oppføringer som skal vises i lister',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Antall felter som skal vises i Detaljvisning',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Antall resultater som skal vises i Globalt søl',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Standard Tildelt for nye portal-registreringer',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Portal layout',
'LBL_SYNCP_WELCOME'=>'Angi URL-en til portalinstansen du vil oppdatere.',
'LBL_SP_UPLOADSTYLE'=>'Velg et stilark å laste opp fra datamaskinen.<br>Stilarket implementeres i Sugar-portalen neste gang du synkroniserer.',
'LBL_SP_UPLOADED'=> 'Lastet opp',
'ERROR_SP_UPLOADED'=>'Sørg for at du laster opp et css-stilark.',
'LBL_SP_PREVIEW'=>'Her er en forhåndsvisning på hvordan Sugar Portal vil se ut ved å bruke style sheet.',
'LBL_PORTALSITE'=>'Sugar Portal URL:',
'LBL_PORTAL_GO'=>'kjør',
'LBL_UP_STYLE_SHEET'=>'last opp Style Sheet',
'LBL_QUESTION_SUGAR_PORTAL' => 'Velg en Sugar Portal layout til redigering.',
'LBL_QUESTION_PORTAL' => 'Velg en portal layout for å redigering',
'LBL_SUGAR_PORTAL'=>'Sugar Portal',
'LBL_USER_SELECT' => '-- Velg --',

//PORTAL PREVIEW
'LBL_CASES'=>'Saker',
'LBL_NEWSLETTERS'=>'Nyhetsbrev',
'LBL_BUG_TRACKER'=>'Feilfølging',
'LBL_MY_ACCOUNT'=>'Min konto',
'LBL_LOGOUT'=>'Log ut',
'LBL_CREATE_NEW'=>'Opprett ny',
'LBL_LOW'=>'Lav',
'LBL_MEDIUM'=>'Medium',
'LBL_HIGH'=>'Høy',
'LBL_NUMBER'=>'Nummer:',
'LBL_PRIORITY'=>'Prioritet:',
'LBL_SUBJECT'=>'Emne',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Pakkenavn:',
'LBL_MODULE_NAME'=>'Modulnavn:',
'LBL_MODULE_NAME_SINGULAR' => 'Entall modulnavn:',
'LBL_AUTHOR'=>'Forfatter:',
'LBL_DESCRIPTION'=>'Beskrivelse:',
'LBL_KEY'=>'Nøkkel:',
'LBL_ADD_README'=>'lesmeg',
'LBL_MODULES'=>'Moduler:',
'LBL_LAST_MODIFIED'=>'Sist endret:',
'LBL_NEW_MODULE'=>'Ny modul',
'LBL_LABEL'=>'Merke:',
'LBL_LABEL_TITLE'=>'Merke',
'LBL_SINGULAR_LABEL' => 'Enkel etikett',
'LBL_WIDTH'=>'Bredde',
'LBL_PACKAGE'=>'Pakke:',
'LBL_TYPE'=>'Type:',
'LBL_TEAM_SECURITY'=>'Gruppesikkerhet',
'LBL_ASSIGNABLE'=>'Kan tildeles',
'LBL_PERSON'=>'Person',
'LBL_COMPANY'=>'Selskap',
'LBL_ISSUE'=>'utgave',
'LBL_SALE'=>'Salg',
'LBL_FILE'=>'Fil',
'LBL_NAV_TAB'=>'Navigeringsfane',
'LBL_CREATE'=>'Opprett',
'LBL_LIST'=>'Liste',
'LBL_VIEW'=>'Vis',
'LBL_LIST_VIEW'=>'List View',
'LBL_HISTORY'=>'Historie',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Gjenopprett standard grensesnitt',
'LBL_ACTIVITIES'=>'Aktivitetstrøm',
'LBL_SEARCH'=>'Søk',
'LBL_NEW'=>'Ny',
'LBL_TYPE_BASIC'=>'grunnleggende',
'LBL_TYPE_COMPANY'=>'selskap',
'LBL_TYPE_PERSON'=>'person',
'LBL_TYPE_ISSUE'=>'utgave',
'LBL_TYPE_SALE'=>'salg',
'LBL_TYPE_FILE'=>'fil',
'LBL_RSUB'=>'Dette er underpanelet som vil bli vist i din modul',
'LBL_MSUB'=>'Dette er underpanelet som modulen gir til den relaterte modulen for visning',
'LBL_MB_IMPORTABLE'=>'Tillat import',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'Synlig',
'LBL_VE_HIDDEN'=>'Skjult',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] ble slettet',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Eksporter tilpasninger',
'LBL_EC_NAME'=>'Pakkenavn:',
'LBL_EC_AUTHOR'=>'Forfatterr:',
'LBL_EC_DESCRIPTION'=>'Beskrivelse:',
'LBL_EC_KEY'=>'Nøkkel:',
'LBL_EC_CHECKERROR'=>'Vennligst velg en modul.',
'LBL_EC_CUSTOMFIELD'=>'tilpassede felt',
'LBL_EC_CUSTOMLAYOUT'=>'tilpassede grensesnitt',
'LBL_EC_CUSTOMDROPDOWN' => 'tilpassede rullegardinmenyer',
'LBL_EC_NOCUSTOM'=>'Ingen moduler er tilpasset.',
'LBL_EC_EXPORTBTN'=>'Eksport',
'LBL_MODULE_DEPLOYED' => 'Modulen er tatt i bruk.',
'LBL_UNDEFINED' => 'udefinert',
'LBL_EC_CUSTOMLABEL'=>'tilpasset etikett(er)',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Kunne ikke hente data',
'LBL_AJAX_TIME_DEPENDENT' => 'En tidsavhengig handling er i gang. Vennligst vent og prøv igjen om få sekunder.',
'LBL_AJAX_LOADING' => 'laster...',
'LBL_AJAX_DELETING' => 'Sletter...',
'LBL_AJAX_BUILDPROGRESS' => 'Oppbyggingen er startet …',
'LBL_AJAX_DEPLOYPROGRESS' => 'Tas i bruk …',
'LBL_AJAX_FIELD_EXISTS' =>'Det feltnavnet du skrev eksisterer allerede. Skriv inn et nytt feltnavn.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Er du sikker på at du vil fjerne pakken? Dette vil permanent slette alle filer i tilknytning til denne pakken.',
'LBL_JS_REMOVE_MODULE' => 'Er du sikker på at du vil fjerne denne modulen? Det vil slette alle filer knyttet til denne modulen.',
'LBL_JS_DEPLOY_PACKAGE' => 'Alle tilpasninger som er gjort i Studio vil bli overskrevet når denne modulen blir gjenbrukt. Er du sikker på at du vil fortsette?',

'LBL_DEPLOY_IN_PROGRESS' => 'Tar pakken i bruk',
'LBL_JS_VALIDATE_NAME'=>'Name - Must be alphanumeric with no spaces and starting with a letter',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Pakkenøkkelen finnes allerede',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Pakkenavnet finnes allerede',
'LBL_JS_PACKAGE_NAME'=>'Pakkenavn - Må starte bed en bokstav og kan kun bestå av bokstaver, tall og understrek. Mellomrom eller andre spesialtegn kan ikke brukes.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Nøkkel - Må være alfanumerisk og begynne med en bokstav.',
'LBL_JS_VALIDATE_KEY'=>'Nøkkel - Må være alfanumerisk',
'LBL_JS_VALIDATE_LABEL'=>'Angi en etikett som skal brukes som visningsnavnet til modulen',
'LBL_JS_VALIDATE_TYPE'=>'Velg modultypen du vil bygge fra listen ovenfor',
'LBL_JS_VALIDATE_REL_NAME'=>'Navn – må være alfanumerisk uten mellomrom',
'LBL_JS_VALIDATE_REL_LABEL'=>'Etikett – legg til en etikett som vises over underpanelet',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Er du sikker på at du vil slette dette påkrevde rullegardin listeelement? Dette kan påvirke funksjonaliteten i programmet.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Er du sikker på at du vil slette dette påkrevde rullegardin listeelement? Sletting av Lukket Vunnet eller Lukket Mistet stadiene vil føre til prognosemodulen ikke vil fungere ordentlig',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Er du sikker på at du vil slette den nye salgsstatus? Sletting av denne statusen vil føre til at Salgsmulighet modulen Omsetnings linjeelement arbeidsflyt ikke vil fungere ordentlig.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Er du sikker på at du vil slette I fremdrift salgsstatus? Sletting av denne satus vil føre til at Salgsmuligheter modulen Omsetnings likjeelement ikke vil fungere ordentlig.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Er du sikker på at du vil slette Lukket Vunnet salgsstrinnet? Sletting av dette trinnet vil føre til at Prognose modulen ikke vil fungere ordentlig.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Er du sikker på at du ønsker å slette Lukket Mistet salgstrinnet? Sletting av dette trinnet vil føre til at Prognose modulen ikke vil fungere ordentlig.',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Er du sikker på at du vil slette dette forholdet?',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Dette vil gjøre dette forholdet permanent. Er du sikker på at du vil distribuere dette forholdet?',
'LBL_CONFIRM_DONT_SAVE' => 'Endringer har blitt gjort siden din siste lagring, vil du lagre?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'lagre endringer?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Data kan bli avkortet, og dette kan ikke omgjøres, er du sikker på at du vil fortsette?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Velg riktige data basert på den datatypen som skal legges inn i feltet.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Konfigurer feltet slik at fullt tekstsøk blir mulig.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Øking er prosessen med å forbedre relevansen for posters felt.<br />Felt meg høyere økningsnivå vektlegges mer når søket utføres. Når søk utføres, kommer passende poster som har felt med større vekt til å vises høyere blant søkeresultatene.<br />Standardverdien er 1.0 som står for en nøytral økning. For å gi en positiv økning, kan enhver verdi over 1 godtas. For en negativ økning bruker du verdier under 1. En verdi på 1.35 kommer f.eks. til å positivt øke feltet med 135 %. En verdi på 0.60 gir en negativ økning.<br />Merk at i tidligere versjoner var det nødvendig indeksregulere det fulle tekstsøket på nytt. Dette er ikke lenger nødvendig.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Ja</b>: Feltet vil bli inkludert i en importeringsoperasjon.<br><b>Nei</b>: Feltet vil ikke bli inkludert i en importering.<br><b>Påkrevd</b>: En feltverdi må bli oppgitt i en import.',
'LBL_POPHELP_PII'=>'Dette feltet blir automatisk merket for revisjon og tilgjengelig i personlig info-visning.<br>Personlig informasjon-felt kan også slettes permanent når posten er knyttet til en personvernsletteforespørsel.<br>Sletting gjøres via modulen personvern og kan utføres av administratorer eller brukere i rollen som personvernansvarlig.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Angi et tall for bredde, målt i piksler',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Angi et tall for høyden målt i piksler.<br />Det opplastede bildet vil bli skalert til denne høyden',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Velg for å bruke dette feltet når du søker etter data ved hjelp av Globalt Søk i denne modulen.',
//Revert Module labels
'LBL_RESET' => 'Tilbakestill',
'LBL_RESET_MODULE' => 'Reset modul',
'LBL_REMOVE_CUSTOM' => 'Fjern kundetilpasning',
'LBL_CLEAR_RELATIONSHIPS' => 'Entydig sammenheng',
'LBL_RESET_LABELS' => 'Reset etiketter',
'LBL_RESET_LAYOUTS' => 'Tilbakestill grensesnitt',
'LBL_REMOVE_FIELDS' => 'Fjern egendefinerte felt',
'LBL_CLEAR_EXTENSIONS' => 'Fjern utvidelser',

'LBL_HISTORY_TIMESTAMP' => 'TimeStamp',
'LBL_HISTORY_TITLE' => 'Historie',

'fieldTypes' => array(
                'varchar'=>'Tekstfelt',
                'int'=>'Heltallig',
                'float'=>'Desimal',
                'bool'=>'Avmerkingsrute',
                'enum'=>'DropDown',
                'multienum' => 'Flervalg',
                'date'=>'Dato',
                'phone' => 'Telefonnummer',
                'currency' => 'Valuta',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Sett i forbindelse med',
                'address' => 'Adresse',
                'text' => 'Tekstomårde',
                'url' => 'Link',
                'iframe' => 'Iframe',
                'image' => 'Bilde',
                'encrypt'=>'Kryptere',
                'datetimecombo' =>'Datotid',
                'decimal'=>'Desimal',
),
'labelTypes' => array(
    "" => "Ofte brukte etiketter",
    "all" => "Alle etiketter",
),

'parent' => 'Flex forbindelse',

'LBL_ILLEGAL_FIELD_VALUE' =>"Drop down-tasten kan ikke inneholde sitater.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Du har valgt dette elementet for å fjerne det fra dropdown listen. Alle dropdown-felt som benytter  denne listen med verdien av dette elementet vil ikke lenger vise verdien, og verdien kan ikke lenger velges fra nedtrekksmenyfeltene.",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Alle Moduler',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (relatert {1}-ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Kopier fra grensesnitt',
);
