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
    'LBL_LOADING' => 'Indlæser ...' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Skjul valgmuligheder' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Slet' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Powered By SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Rolle:',
'help'=>array(
    'package'=>array(
            'create'=>'Giv et <b>navn</b> til pakken. Det navn, du indtaster, skal være alfanumerisk og må ikke indeholde mellemrum. (Eksempel: HR_Management)<br /><br />Du kan angive <b>forfatter</b> og <b>beskrivelsesinformation</b> for pakken.<br /><br />Klik på <b>Gem</b> for at oprette pakken.',
            'modify'=>'Egenskaber og muligheder for <b>pakken</b> vises her.<br /><br />Du kan ændre <br>navn</b>, <b>forfatter</b> og <b>beskrivelse</b> af pakken, samt se og tilpasse alle moduler, der er indeholdt i pakken.<br /><br />Klik på <b>Nyt modul</b> for at oprette et modul til pakken.<br /><br />Hvis pakken indeholder mindst et modul, kan du <b>udgive</b> og <b>installere</b> pakken, samt <b>eksporterer</b> tilpasningerne i pakken.',
            'name'=>'Dette er <b>navnet</b> på den nuværende pakke.<br /><br />Det navn, du indtaster, skal være alfanumerisk og starte med et bogstav og ikke indeholde mellemrum. (Eksempel: HR_Management)',
            'author'=>'Dette er <b>forfatteren</b>, der vises under installationen, som navnet på den/dem, der skabte pakken. Forfatteren kan enten være en enkeltperson eller en virksomhed.',
            'description'=>'Dette er <b>beskrivelsen</b> af pakken, der vises under installationen.',
            'publishbtn'=>'Klik på <b>Udgiv</b> for at gemme alle indtastede data og skabe en zip-fil, der er en installerbar version af pakken.<br /><br />Brug <b>Modulindlæser</b> til at uploade zip-filen og installere pakken.',
            'deploybtn'=>'Klik på <b>Installer</b> for at gemme alle indtastede data installere pakken, herunder alle moduler i den aktuelle Sugar løsning.',
            'duplicatebtn'=>'Klik på <b>Dupliker</b> for at kopiere indholdet af pakken over i en ny pakke og for at vise den nye pakke.<br/><br/>For den nye pakke vil et nyt navn blive genereret automatisk ved at tilføje et tal i slutningen af navnet på den pakke, der bruges til at oprette den nye. Du kan omdøbe den nye pakke ved at indtaste et nyt <b>navn</b> og klikke på <b>Gem</b>.',
            'exportbtn'=>'Klik på <b>Eksporter</b> for at oprette en zip-fil, der indeholder tilpasninger foretaget i pakken.<br><br>Den genererede fil er ikke en installerbar version af pakken.<br><br>Brug <b>Modulindlæser</b> til at importere zip-filen og få pakken, herunder tilpasninger, vist i Modulgeneratoren.',
            'deletebtn'=>'Klik på <b>Slet</b> for at slette denne pakke og alle filer relateret til denne pakke.',
            'savebtn'=>'Klik på <b>Gem</b> for at gemme alle indtastede data i forbindelse med pakken.',
            'existing_module'=>'Klik på <b>Modul</b> ikonet for at redigere egenskaber og tilpas felter, relationer og layout i forbindelse med modulet.',
            'new_module'=>'Klik på <b>Nyt modul</b> for at oprette et nyt modul for denne pakke.',
            'key'=>'Denne 5-tegns alfanumeriske <b>nøgle</b> vil blive brugt til prefix på alle mapper, gruppenavne og databasetabeller for alle moduler i den nuværende pakke.<br /><br />Nøglen bliver brugt til at sikre unikke tabelnavne.',
            'readme'=>'Klik for at tilføje <b>readme</b> tekst til denne pakke.<br><br>Readme teksten vil være tilgængelig på installationstidspunktet.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Giv et <b>navn</b> til modulet. <b>Etiketten</b>, som du giver, vil blive vist i navigationsfanen.<br/><br/>Vælg at vise en navigationsfane for modulet ved at afkrydse <b>Navigationsfane</b> afkrydsningsfeltet.<br/><br/>Afkryds <b>Team sikkerheds afkrydsningsfeltet</b> for at have et Team valgfelt inde i modulets poster.<br/><br/>Vælg derefter den type af modul du gerne vil oprette.<br/><br/>Vælg en skabelontype. Hver skabelon indeholder et bestemt sæt af felter, samt foruddefinerede layouts, der kan bruges som grundlag for dit modul.<br/><br/>Klik på <b>Gem</b> for at oprette modulet.',
        'modify'=>'Du kan ændre modulegenskaberne eller tilpasse <b>felter</b>, <b>relationer</b> og <b>layout</b> i ved modulet.',
        'importable'=>'Afkrydsning af <b>Importerbar</b> i afkrydsningsfeltet vil gøre det muligt at importere til dette modul.<br><br>Et link til Importeringsguiden vil vises i genvejepanelet i modulet. Importeringsguiden gør det lettere at importere data fra eksterne kilder ind i det brugerdefinerede modul.',
        'team_security'=>'Afkrydsning af <b>Team sikkerhed</b> i afkrydsningsfeltet vil aktivere team sikkerthed for dette modul.<br/><br/>Hvis Team sikkerhed er aktiveret, vil team valgfeltet være tilstede i posterne i modulet ',
        'reportable'=>'Afkrydsning af dette felt vil tillade dette modul at få rapporter kørt imod det.',
        'assignable'=>'Afkrydsning af dette felt vil muliggøre en post i dette modul at blive tilknyttet en udvalgt bruger.',
        'has_tab'=>'Afkrydsning af <b>Navigationsfane</b> vil give en navigationsfane for modulet.',
        'acl'=>'Afkrydsning af dette felt vil aktivere adgangskontrol på dette modul, herunder feltniveausikkerhed.',
        'studio'=>'Afkrydsning af dette felt vil tillade administratorer at tilrette dette modul inde i Studio.',
        'audit'=>'Afkrydsning af dette felt vil aktivere revision til dette modul. Ændringer i visse områder vil blive registreret, så administratorer kan gennemgå ændringer i historik.',
        'viewfieldsbtn'=>'Klik på <b>vis felter</b> for at se de felter, der er forbundet med modulet, og for at oprette og redigere brugerdefinerede felter.',
        'viewrelsbtn'=>'Klik på <b>Vis relationer</b> for at se de tilknyttede relationer til dette modul og skabe nye relationer.',
        'viewlayoutsbtn'=>'Klik på <b>Vis layout</b> for at se layout for modulet, og for at tilpasse feltopsætningen inden for layoutet.',
        'viewmobilelayoutsbtn' => 'Klik på <b>View Mobile Layouts</b> for at se det mobile layout til modulet og for at tilpasse feltindretning inden for disse layouts.',
        'duplicatebtn'=>'Klik på <b>Dupliker</b> for at kopiere egenskaberne fra modulet over i et nyt modul og for at vise det nye module.<br/><br/>For det nye modul vil et nyt navn blive genereret automatisk ved at tilføje et tal i slutningen af navnet på det modul, der bruges til at oprette den nye.',
        'deletebtn'=>'Klik på <b>Slet</b> for at slette dette modul.',
        'name'=>'Dette er <b>navnet</b> på det aktuelle modul.<br/><br/>Navnet skal være alfanumerisk, skal begynde med et bogstav og må ikke indeholde mellemrum. (Eksempel: HR_Management)',
        'label'=>'Dette er den <b>etiket</b>, der vil blive vist i navigationsfanen for modulet.',
        'savebtn'=>'Klik på <b>Gem</b> for at gemme alle indtastede data i forbindelse med modulet.',
        'type_basic'=>'<b>Standard</b> skabelontypen indeholder standard felter såsom navn, tildelt til, team, oprettet og beskrivelsesfelter.',
        'type_company'=>'<b>Firma</b> skabelontypen giver organisationsspecifikke felter, såsom firmanavn, industri og faktureringsadresse.<br /><br />Brug denne skabelon til at oprette moduler, der ligner standard virksomhedsmodulet.',
        'type_issue'=>'<b>Sager</b> skabelonentypen giver sags- og fejl-specifikke felter, såsom som nummer, status, prioritet og beskrivelse.<br/><br/>Brug denne skabelon til at oprette moduler, der ligner standard Sagsmodulet.',
        'type_person'=>'<b>Kontakt</b> skabelontypen giver kontaktspecifikke felter, såsom hilsen, titel, navn, adresse og telefonnummer.<br /><br />Brug denne skabelon til at oprette moduler, der ligner standard Kontaktmodulet.',
        'type_sale'=>'<b>Salg</b> skabelontypen giver salgsmulighedsspecifikke felter, såsom kundeemnekilde, salgsfase, beløb og sandsynlighed.<br/><br/>Brug denne skabelon til at oprette moduler, der ligner standard Salgsmuligheder-modulet.',
        'type_file'=>'<b>Fil</b> skabelontypen giver dokumentspecifikke felter, såsom filnavn, dokumenttype og udgivelsesdato.<br /><br />Brug denne skabelon til at oprette moduler, der ligner standard Dokument-modulet.',

    ),
    'dropdowns'=>array(
        'default' => 'Alle applikationens <b>Rullelister</b> er vist her.<br><br>Rullelisterne kan bruges til rullelistefelter i alle moduler.<br><br>For foretage ændringer i en eksisterende rulleliste, skal du klikke på rullelistenavnet.<br><br>Klik på <b>Tilføj rulleliste</b> for at oprette en ny rulleliste.',
        'editdropdown'=>'Rullelister kan bruges til standard eller brugerdefinerede rullelistefelter i alle moduler.<br><br>Give et <b>navn</b> til rullelisten.<br><br>Hvis en sprogpakke er installeret i applikationen, kan du vælge  <b>sprog</b>, der bruges til elementerne på rullelisten.<br><br> I<b>Elementnavnsfeltet</b> angives et navn for muligheden i rullelisten. Dette navn vil ikke blive vist på rullelisten, der er synlig for brugerne.<br><br>I <b>Etiket</b> feltet, angives en etiket, der vil være synlig for brugerne.<br><br>Efter angivelsen af elementnavnet og etiketten, klik på <b>Tilføj</b> for at tilføje elementet til rullelisten.<br><br>For at rearrangere elementer på listen, kan du trække og slippe elementer i de ønskede positioner.<br><br>For at redigere etiketten for et element, skal du klikke på <b>Rediger ikonet</b> og indtaste en ny etiket. For at slette et element fra rullelisten, skal du klikke på ikonet <b>Slet ikon</b>.<br><br>Hvis du vil fortryde en ændring til en etiket, skal du klikke på <b>Fortryd</b>. For genskabe en ændring, som var fortrudt, skal du klikke <b>Genskab</b>.<br><br>Klik på <b>Gem</b> for at gemme rullelisten.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klik på <b>Gem og implementer</b> for at gemme ændringerne du har lavet og for at gøre dem aktive i modulet.',
        'historyBtn'=> 'Klik på <b>Vis historik</b> for at få vist og gendanne et tidligere gemt layout fra historikken.',
        'historyRestoreDefaultLayout'=> 'Klik på <b>Gendan standard layout</b> for at gendanne en visning til sit oprindelige layout.',
        'Hidden' 	=> '<b>Skjulte</b> felter vises ikke i underpaneler.',
        'Default'	=> '<b>Standard</b> felter vises i underpanelet.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klik på <b>Gem og implementer</b> for at gemme ændringerne du har lavet og for at gøre dem aktive i modulet.',
        'historyBtn'=> 'Klik på <b>Vis historik</b> for at få vist og gendanne et tidligere gemt layout fra historikken.<br><br><b>Gendan</b> i <b>Vis historik</b> gendanner feltets placering i tidligere gemte layouts. For at ændre feltetiketter Klik på Redigér ikon ved siden af hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klik på <b>Gendan standard layout</b> for at gendanne en visning til sit oprindelige layout. <br><br><b>Gendan standard layout</b> gendanner kun feltets placering inden for det oprindelige layout. For at ændre feltetiketter, klik på ikonet Rediger ved siden af hvert felt.',
        'Hidden' 	=> '<b>Skjulte</b> felter som i øjeblikket ikke er tilgængelig for brugerne listevisninger.',
        'Available' => '<b>Tilgængelige</b> felter er ikke vist som standard, men kan tilføjes til listevisninger af brugerne.',
        'Default'	=> '<b>Standard</b> felter, der vises i listevisninger, som ikke er tilpasset af brugerne.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klik på <b>Gem og implementer</b> for at gemme ændringerne du har lavet og for at gøre dem aktive i modulet.',
        'historyBtn'=> 'Klik på <b>Vis historik</b> for at få vist og gendanne et tidligere gemt layout fra historikken.<br><br><b>Gendan</b> i <b>Vis historik</b> gendanner feltets placering i tidligere gemte layouts. For at ændre feltetiketter Klik på Redigér ikon ved siden af hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klik på <b>Gendan standard layout</b> for at gendanne en visning til sit oprindelige layout. <br><br><b>Gendan standard layout</b> gendanner kun feltets placering inden for det oprindelige layout. For at ændre feltetiketter, klik på ikonet Rediger ved siden af hvert felt.',
        'Hidden' 	=> '<b>Skjulte</b> felter som i øjeblikket ikke er tilgængelig for brugerne listevisninger.',
        'Default'	=> '<b>Standard</b> felter, der vises i listevisninger, som ikke er tilpasset af brugerne.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Klik på <b>Gem og implementer</b> vil gemme alle ændringerne og gøre dem aktive.',
        'Hidden' 	=> '<b>Skjulte</b> felter vises ikke i søgningen.',
        'historyBtn'=> 'Klik på <b>Vis historik</b> for at få vist og gendanne et tidligere gemt layout fra historikken.<br><br><b>Gendan</b> i <b>Vis historik</b> gendanner feltets placering i tidligere gemte layouts. For at ændre feltetiketter Klik på Redigér ikon ved siden af hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klik på <b>Gendan standard layout</b> for at gendanne en visning til sit oprindelige layout. <br><br><b>Gendan standard layout</b> gendanner kun feltets placering inden for det oprindelige layout. For at ændre feltetiketter, klik på ikonet Rediger ved siden af hvert felt.',
        'Default'	=> '<b>Standard</b> felter vises i søgningen.'
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
        'saveBtn'	=> 'Klik på <b>Gem</b> for at bevare de ændringer, du har foretaget ved layoutet siden sidste gang du gemte det.<br /><br />Ændringerne vil ikke blive vist i modulet, indtil du implementerer gemte ændringer.',
        'historyBtn'=> 'Klik på <b>Vis historik</b> for at få vist og gendanne et tidligere gemt layout fra historikken.<br><br><b>Gendan</b> i <b>Vis historik</b> gendanner feltets placering i tidligere gemte layouts. For at ændre feltetiketter Klik på Redigér ikon ved siden af hvert felt.',
        'historyRestoreDefaultLayout'=> 'Klik på <b>Gendan standard layout</b> for at gendanne en visning til sit oprindelige layout. <br><br><b>Gendan standard layout</b> gendanner kun feltets placering inden for det oprindelige layout. For at ændre feltetiketter, klik på ikonet Rediger ved siden af hvert felt.',
        'publishBtn'=> 'Klik på <b>Gem og Installer</b> for at gemme alle ændringer, du har foretaget ved layout, siden sidste gang du gemte det, og for at gøre ændringer aktive i modulet.<br /><br />Layoutet vil straks blive vist i modulet.',
        'toolbox'	=> '<b>Værktøjskassen</b> indeholder <b>Papirkurven</b>, yderligere layout elementer og et sæt af tilgængelige felter. der kan føjes til layoutet.<br/><br/>Layout elementerne og felterne i Værktøjskassen kan trækkes og slippes ind i layoutet, og layoutelementer og felter kan trækkes og slippes fra layoutet ind i Værktøjskassen.<br><br>Layoutelementerne er <b>Paneler</b> og <b>Rækker</b>. Tilføjelse af en ny række eller et nyt panel til layoutet giver ekstra steder i layout til felter.<br/><br/>Træk og slip et af felterne i Værktøjskassen eller layoutet over på en besat feltposition for at udveksle placeringen af de to felter.<br/><br/>Feltet <b>Fyld</b> skaber mellemrum i layoutet, hvor det er placeret.',
        'panels'	=> 'The <b>Layout</b> området giver et billede af, hvordan layoutet vil blive vist inde i modulet, når ændringerne i layoutet er indsat.<br/><br/>Du kan flytte felter, rækker og paneler ved at trække og slippe dem ind i den ønskede placering.<br/><br/>Fjern elementer ved at trække og slippe dem i <b>Papirkurven </b> i værktøjskassen, eller tilføje nye elementer og felter ved at trække dem fra <b>Værktøjskassen</b> og slippe dem i den ønskede placering i layoutet.',
        'delete'	=> 'Træk og slip ethvert element her for at fjerne det fra layoutet',
        'property'	=> 'Rediger etiketten, der vises for dette felt.<br /><b>Tab-rækkefølge</b> bestemmer i hvilken rækkefølge tabulatortasten skifter mellem felterne.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Felterne, der er tilgængelige for modulet, er vist her med feltnavn.<br /><br />Brugerdefinerede felter, oprettet til modulet, vises over de felter, der er tilgængelige for modulet som standard.<br /><br />Hvis du vil redigere et felt, skal du klikke på <b>feltavnet</b>.<br /><br />Hvis du vil oprette et nyt felt, skal du klikke på <b>Tilføj felt</b>.',
        'mbDefault'=>'De <b>Felter</b> der er tilgængelige for modulerne er opført her ved feltnavn.<br><br>For at konfigurere egenskaberne for et felt, skal du klikke på feltets navn.<br><br>For at oprette et felt klik på <b>Add Field</b>. Etiketten sammen med de andre egenskaber ved det nye felt kan redigeres efter oprettelsen ved at klikke på feltets navn.<br><br>Efter modulet bliver anvendt, bliver de nye felter, der er oprettet i modulopbygningen betragtet som standardfelter i anvendte moduler i Studio.',
        'addField'	=> 'Vælg en <b>Datatype</b> til det nye felt. Den type du vælger bestemmer, hvilke tegn kan indtastes i feltet. For eksempel kun tal, der er heltal, kan indtastes i felter, der er af heltal datatypen.<br><br> Angive et a <b>Navn</b> for feltet.  Navnet skal være alfanumerisk og må ikke indeholde mellemrum. Understregninger er gyldige.<br><br> <b>Display etiketten</b> er etiketten, der vil ses for felterne i modulet layout. <b>System etiketten</b> bruges til at henvise til feltet i koden.<br><br> Afhængig af datatypen for det valgte felt, kan nogle eller alle af de følgende egenskaber indstilles for feltet:<br><br> <b>Hjælp tekst</b> Vises midlertidigt, mens en bruger går hen over feltet og kan bruges til at bede brugeren om den type input, denne ønsker.<br><br> <b>Kommentar tekst</b> er kun set i Studio &/eller modul Builder, og kan bruges til at beskrive feltet for administratoren.<br><br> <b>Standard værdi</b> vil blive vist i feltet. Brugere kan indtaste en ny værdi i feltet eller bruge standardværdien.<br><br> Vælg <b>Mass Update</b> afkrydsningsfeltet for at kunne bruge Mass Update-funktionen for feltet.<br><br><b>Maks. størrelse</b> værdi bestemmer det maksimale antal tegn, der kan indtastes i feltet.<br><br> Vælg <b>Skal udfyldes</b> afkrydsningsfeltet for at gøre feltet påkrævet. En værdi skal gives for feltet for at kunne gemme en post indeholdende feltet.<br><br> Vælg <b>Anmeldelsespligtigt</b> afkrydsningsfelt for at tillade at området kan bruges som filter og til visning af data i posterne.<br><br> Vælg <b>Audit</b> afkrydsningsfelt for at være i stand til at spore ændringer i feltet i Change Log.<br><br>Vælg en option i <b>Kan importeres</b> for at tillade, forbyde eller kræve, at feltet kan importeres i import wizarden.<br><br>Vælg en option i <b>Dublér flet</b> fetlet for at aktivere eller deaktivere flet dubletter og find dubletter og funktioner.<br><br>yderligere egenskaber kan indstilles for bestemte datatyper.',
        'editField' => 'Egenskaberne for dette felt kan tilpasses.<br /><br />Klik <b>Klon</b> for at oprette et nyt felt med de samme egenskaber.',
        'mbeditField' => '<b>Display etiket</b> for et skabelonfelt kan tilpasses. De øvrige egenskaber for feltet kan ikke tilpasses. <br><br> Klik på <b>Klon</b> for at oprette et nyt felt med de samme egenskaber. <br><br> For at fjerne et skabelonfelt, så det ikke vises i modulet, skal du fjerne feltet fra det passende <b>layout</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Eksport tilpasninger foretaget i Studio ved at skabe pakker, der kan uploades til et andet Sugar eksempel gennem <b>Module Loader</b>.<br><br>  Først, angiv et <b>Pakkenavn</b>.  Du kan også angive <b>Forfatter</b> og <b>Beskrivelse</b> for pakken.<br><br>Vælg modul(er), der indeholder de tilpasninger, du ønsker at eksportere. Kun moduler, der indeholder tilpasninger ses for valget.<br><br>Klik så på <b>Eksport</b> for at oprette en .zip fil for pakken, der indeholder tilpasninger.',
        'exportCustomBtn'=>'Klik på <b>Eksporter</b> for at oprette en zip-fil for pakken, der indeholder de tilpasninger, som du ønsker at eksportere.',
        'name'=>'Dette er <b>navnet</b> på pakken. Dette navn vil blive vist under installationen.',
        'author'=>'Dette er <b>forfatteren</b>, der vises under installationen, som navnet på den/dem, der skabte pakken. Forfatteren kan enten være en enkeltperson eller en virksomhed.',
        'description'=>'Dette er <b>beskrivelsen</b> af pakken, der vises under installationen.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Velkommen til <b>Udviklerværktøjsområdet</b>.<br /><br />Brug værktøjerne i dette område til at oprette og håndtere standard og tilpassede moduler og felter.',
        'studioBtn'	=> 'Brug <b>Studio</b> til at tilpasse implementerede moduler.',
        'mbBtn'		=> 'Brug <b>modulgeneratoren</b> til at skabe nye moduler.',
        'sugarPortalBtn' => 'Brug <b>Sugar Portal-editor</b> til at styre og tilpasse Sugar Portal.',
        'dropDownEditorBtn' => 'Brug <b>rullelisteeditor</b> til at tilføje og redigere globale rullelister i rullelistefelter.',
        'appBtn' 	=> 'Applikationstilstand er hvor du kan tilpasse forskellige egenskaber i programmet, såsom hvor mange TPS rapporter, der vises på forsiden',
        'backBtn'	=> 'Tilbage til det forrige trin.',
        'studioHelp'=> 'Brug <b>Studio</b> til at bestemme hvad og hvordan oplysninger vises i modulerne.',
        'studioBCHelp' => ' indikererat modulet er et bagud kompatibelt modul',
        'moduleBtn'	=> 'Klik for at redigere dette modul.',
        'moduleHelp'=> 'De komponenter, som du kan tilpasse for modulet, vises her.<br /><br />Klik på et ikon for at vælge det komponent, der skal redigeres.',
        'fieldsBtn'	=> 'Opret og tilpas <b>felter</b> for at lagre oplysninger i modulet.',
        'labelsBtn' => 'Redigere <b>etiketterne</b> som vises for felterne og andre titler i modulet.'	,
        'relationshipsBtn' => 'Tilføje nye eller se eksisterende <b>relationer</b> for modulet.' ,
        'layoutsBtn'=> 'Tilpas modulet <b>Layouts</b>. Layouts er de forskellige visninger af modulet, der indeholder felter. <br><br> Du kan bestemme hvilke felter, der vises, og hvordan de er organiseret i de enkelte layout.',
        'subpanelBtn'=> 'Bestem hvilke felter der vises i <b>underpanelerne</b> i modulet.',
        'portalBtn' =>'Tilpas modul-<b>layouts</b>, der vises i <b>SugarPortalen</b>.',
        'layoutsHelp'=> 'Modulet <b>Layout</b>, der kan tilpasses, vises her. <br><br> Layouts viser felter og feltdata. <br><br> Klik på et ikon for at vælge det layout, der skal redigeres.',
        'subpanelHelp'=> 'Klik på et ikon for at vælge modulet der skal redigeres.',
        'newPackage'=>'Klik på <b>Ny pakke</b> for at oprette en ny pakke.',
        'exportBtn' => 'Klik på <b>Eksporter tilpasninger</b> for at oprette og downloade en pakke, der indeholder tilpasninger lavet i Studio for specifikke moduler.',
        'mbHelp'    => 'Brug <b>Modulgeneratoren</b> til at oprette pakker indeholdende brugerdefinerede moduler baseret på standard eller brugerdefinerede objekter.',
        'viewBtnEditView' => 'Tilpas modulets <b>EditView</b>Layout.<br><br> EditView er formen, der indeholder input felterne for at opfange brugerens indtastede data.',
        'viewBtnDetailView' => 'Tilpas modulets <b>Vis detaljer</b> layout. <br><br> Detaljeret visning viser brugerindtastede feltdata.',
        'viewBtnDashlet' => 'Tilpas modulets <b>Sugar dashlet</b>, herunder Sugar dashlets listevisning og Søg. <br><br> Sugar dashlet vil være til rådighed for at tilføje siderne i Home-modulet.',
        'viewBtnListView' => 'Tilpas modulets <b>Listevisning</b> layout.<br><br>Søgeresultater vises i listevisning.',
        'searchBtn' => 'Tilpas modulets <b>Søg</b> layouts. <br><br> Bestem hvilke felter kan bruges til at filtrere poster, der ses i listevisningen.',
        'viewBtnQuickCreate' =>  'Tilpas modulets <b>Hurtig opret</b> layout. <br><br> Hurtig opret formen vises i underpaneler og i e-mail-modulet.',

        'searchHelp'=> '<b>Søg</b> former kan tilpasses vises her. <br><br> Søgeformer indeholder felter til filtrering af poster. <br><br>Klik på et ikon for at vælge det søge-layout, der skal redigeres.',
        'dashletHelp' =>'<b>Sugar Dashlet</b> layouts, der kan tilpasses, vises her.<br /><br />Sugar Dashlet&#39;en vil kunnet tilføjes siderne i forside-modulet.',
        'DashletListViewBtn' =>'<b>Sugar Dashlet listevisningen</b> viser poster baseret på Sugar Dashlet søgefiltrer.',
        'DashletSearchViewBtn' =>'<b>Sugar Dashlet søgning</b> filtrer poster for Sukker Dashlet listevisning.',
        'popupHelp' =>'<b>Popup</b> layouts, der kan tilpasses, vises her.',
        'PopupListViewBtn' => '<b>Popup listevisningen</b> viser poster baseret på Popup søgevisningerne.',
        'PopupSearchViewBtn' => '<b>Popup søgning</b> viser poster for Popup listevisningen.',
        'BasicSearchBtn' => 'Tilpas <b>standard søgeformular</b>, der vises i den standard søgefanen i søgeområdet for modulet.',
        'AdvancedSearchBtn' => 'Tilpas <b>avanceret søgeformular</b>, der vises i den avancerede søgefanen i søgeområdet for modulet.',
        'portalHelp' => 'Administrer og tilpas <b>Sugar Portal</>.',
        'SPUploadCSS' => 'Indlæs et <b>Style Sheet</b> for Sugar Portalen.',
        'SPSync' => '<b>Synkroniser</b> tilpasninger for Sugar Portal løsningen.',
        'Layouts' => 'Tilpas layouts for Sugar Portal modulerne.',
        'portalLayoutHelp' => 'Modulerne i Sugar Portal vises i dette område. <br><br>Vælg et modul for at redigere<b>Layout</b>.',
        'relationshipsHelp' => 'Alle <b>Relationer</b>, der findes mellem modulet og andre anvendte moduler vises her. <br><br> Relationen <b>Navn</b> er det system-genererede navn for relationen. <br><br>Det<b>Primære modul</b> er det modul, der ejer relationer. For eksempel er alle egenskaber for de relationer, for hvilke modulet Konti er den primære modul gemt i Konti databasetabeller. <br><br><b>Typen</b> er den type relation, der eksisterer mellem det primær modul og <b>Relateret modul</b>.<br><br> Klik på en kolonneoverskrift for at sortere efter kolonnen <br><br> Klik på en række i relationstabellen for at se de egenskaber, der er knyttet til forholdet. <br><br> Klik på <b>Tilføj relation</b> for at oprette en ny relation. <br><br> Relationer kan oprettes mellem to anvendte moduler.',
        'relationshipHelp'=>'<b>Relationer</b> kan skabes mellem modulet og et andet anvendt modul. <br><br> Relationer bliver visuelt udtrykt gennem underpaneler og relaterede felter i modulets poster. <br><br> Vælg en af følgende relationer <b>Typer</b> for modulet: <br><br> <b>En-til-En</b> - Begge modulers poster vil indeholde relaterede felter. <br><br> <b> En-til-Mange</b> -. det primære moduls post vil indeholde et underpanel, og det relaterede moduls post vil indeholde et relateret felt <br><br> <b>Mange-til-mange</b> - Begge modulers poster viser underpaneler. <br><br> Vælg <b>Relateret modul</b> for relationen. <br><br> Hvis relationstypen involverer underpaneler vælges underpanelets visning for de relevante moduler. <br><br> Klik på <b>Gem</b> for at oprette relationen.',
        'convertLeadHelp' => "Her kan du tilføje moduler til den konverterede layout skærm og ændre indstillingerne for de eksisterende. <br/><br/>
         <b>Bestilling: </b><br/>
         Kontakter, konti og muligheder skal opretholde deres rækkefølge. Du kan genbestille ethvert andet modul ved at trække dens række i tabellen. <br/><br/>
         <b>Afhængighed: </b><br/>
         Hvis Muligheder er inkluderet, skal konti enten være påkrævet eller fjernes fra det konverterede layout. <br/><br/>
         <b>Modul: </b> Navnet på modulet <br/><br/>
         <b>Påkrævet: </b> Krævede moduler skal oprettes eller vælges før føringen kan konverteres  <br/><br/>
         <b>Kopiér data: </b> Hvis markeret, vil felter fra den ledende blive kopieret til felter med samme navn i de nyoprettede poster <br/><br/>
         <b> Slet: </b> Fjern dette modul fra det konverterede layout <br/><br/>
        ",
        'editDropDownBtn' => 'Redigere en global rullelistning',
        'addDropDownBtn' => 'Tilføj en ny global rullelistning',
    ),
    'fieldsHelp'=>array(
        'default'=>'<b>Felterne</b> i modulet er vist her ved feltnavn. <br><br> Modulskabelonen indeholder et forudbestemt sæt af felter. <br><br> For at oprette et nyt felt, klik på <b>Tilføj felt</b>. <br><br> For at redigere et felt, klik på <b>Feltnavn</b>. <br/><br/> Efter modulet bliver anvendt, bliver de nye felter skabt i ModuleBuilder, sammen med skabelonens felter, betragtes som standardfelter i Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>Relationer</b>, der er blevet skabt mellem modulet og andre moduler vises her. <br><br>Relationen <b>Navn</b> er det system-genererede navn for relationen. <br><br> Det <b> Primære modul</b> er det modul, der ejer relationerne. Relationens egenskaber gemmes i databasetabeller, der tilhører det primære modul.<br><br><b>Typen</b> er den type relation, der eksisterer mellem det primære modul og <b>Relateret modul</b>. <br><br> Klik på en kolonneoverskrift for at sortere efter kolonne. <br><br> Klik på en række i relationstabellen for at få vist og redigere egenskaberne forbundet med relationen. <br><br> Klik på <b>Tilføj relation</b> for at oprette en nyt relation.',
        'addrelbtn'=>'mouse-over hjælp for at tilføje relation ..',
        'addRelationship'=>'<b>Relationer</b> kan oprettes mellem modulet og et andet brugerdefineret modul eller et anvendt modul. <br><br>Relationer bliver visuelt udtryk gennem underpaneler og relaterede felter i modulposterne. <br><br> Vælg et af følgende relationer <b>Typer</b> for modulet: <br><br> <b>En-til-En</b> - begge modulers poster vil indeholde relaterede felter <br><br><b>En-til-Mange</b> - det primære moduls post vil indeholde et underpanel, og det relaterede moduls post vil indeholde et relateret felt. <br><br> <b>Mange-til-Mange</b> - begge modulers poster viser underpaneler <br><br> Vælg <b>Relateret modul</b> for relationen. <br><br> Hvis relationstypen involverer underpaneler, vælg underpanelvisningen for de relevante moduler. <br><br> Klik på <b>Gem</b> for at oprette relationen.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Etiketterne</b> for felterne og andre titler i modulet kan ændres. <br><br> Redigér etiketten ved at klikke på området, indtaste en ny etiket og klikke på <b>Gem</b>. <br><br> Hvis der er sprogpakker installeret i programmet, kan du vælge hvilket <b>Sprog</b>, der skal bruges til etiketterne.',
        'saveBtn'=>'Klik på <b>Gem</b> for at gemme alle ændringer.',
        'publishBtn'=>'Klik på <b>Gem og installer</b> for at gemme alle ændringer og gøre dem aktive.',
    ),
    'portalSync'=>array(
        'default' => 'Indtast <b>Sugar Portal URL</b> fra portaleksemplet for at opdatere, og klik på <b> Kør</b>. <br><br> Indtast derefter et gyldigt Sugar brugernavn og en adgangskode, og klik på <b>Begynd sync</b>. <br><br> Tilpasningerne foretaget i Sugar Portal <b>Layouts</b>, sammen med <b>Style Sheet</b>, hvis en er blevet uploadet, vil blive overført til specificeret portaleksempel.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Du kan tilpasse udseendet for Sugar Portalen ved hjælp af et typografiark. <br><br>Vælg et <b>Typografiark</b> til at uploade. <br><br> Typografiark vil blive gennemført i Sugar Portalen næste gang en synkronisering udføres.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'For at komme i gang med et projekt, skal du klikke på <b>Ny pakke</b> for at oprette en ny pakke til at huse dit brugerdefinerede modul(er). <br/><br/> Hver pakke kan indeholde et eller flere moduler. <br/><br/> For eksempel kan du ønsker at oprette en pakke, der indeholder et brugerdefineret modul, der er relateret til standard Regnskabsmodul. Eller, du ønsker at oprette en pakke, der indeholder en række nye moduler, der arbejder sammen som et projekt, og som er relateret til hinanden og til andre moduler allerede i applikationen.',
            'somepackages'=>'En <b>Pakke</b> fungerer som en beholder for brugerdefinerede moduler, som alle er en del af et projekt. Pakken kan indeholde en eller flere brugerdefinerede <b>moduler</b>, der kan relateres til hinanden eller til andre moduler i programmet. <br/><br/> Når du har oprettet en pakke til dit projekt, kan du oprette moduler til pakken med det samme, eller du kan vende tilbage til ModulBuilder på et senere tidspunkt for at fuldføre projektet. <br><br> Når projektet er afsluttet, kan du <b>Anvende</b> pakken for at installere de brugerdefinerede moduler i programmet.',
            'afterSave'=>'Din nye pakke skal indeholde mindst ét modul. Du kan oprette en eller flere tilpassede moduler til pakken. <br/><br/>Klik på<b>Nyt modul</b> for at oprette et brugerdefineret modul til denne pakke. <br/><br/> Efter at have oprettet mindst et modul, kan du publicere eller installere pakken for at gøre den tilgængelig for dit eksempel og/eller andre brugeres eksempler. <br/><br/> For at anvende pakken i et trin i dit Sugar eksempel, skal du klikke på <b>Anvend</b>. <br><br> Klik på <b>Udgiv</b> for at gemme pakken som en .zip-fil. Efter .zip filen er gemt i dit system, skal du bruge <b>Module Loader</b> for at uploade og installere pakken i dit Sugar eksempel. <br/><br/> Du kan distribuere filen til andre brugere, der kan uploade og installere i deres egne Sugar eksepler.',
            'create'=>'En <b>Pakke</b> fungerer som en beholder for brugerdefinerede moduler, som alle er en del af et projekt. Pakken kan indeholde en eller flere brugerdefinerede <b>moduler</b>, der kan relateres til hinanden eller til andre moduler i programmet. <br/><br/> Når du har oprettet en pakke til dit projekt, kan du oprette moduler til pakken med det samme, eller du kan vende tilbage til ModulBuilder på et senere tidspunkt for at fuldføre projektet.',
            ),
    'main'=>array(
        'welcome'=>'Brug <b>Ydviklerværktøjer</b> for at oprette og administrere standard og brugerdefinerede moduler og felter. <br/><br/> For at administrere moduler i programmet, skal du klikke på <b>Studio</b>. <br/><br/> For at oprette brugerdefinerede moduler, skal du klikke på <b>ModulBuilder</b>.',
        'studioWelcome'=>'Alle de allerede installerede moduler, herunder standard og modulindlæst objekter, kan tilpasses i Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Da den nuværende pakke indeholder mindst et modul, kan du <b>Anvende</b> modulerne i pakken i dit Sugar eksempel eller <b>Udgiv</b> pakken til installation i det aktuelle Sugar eksempel eller et andet eksempel ved hjælp af <b>ModuleLoader </b>. <br/><br/> For at installere pakken direkte i dit Sugar eksempel, skal du klikke på <b>Anvend</b>. <br><br> For at oprette en .zip-fil til pakken, der kan indlæses og installeres inde i det nuværende Sugar eksempel og andre eksempler ved hjælp af <b>Module Loader</b>, klik på <b>Udgiv</b>. <br/><br/> Du kan opbygge modulerne for denne pakke i etaper, og udgive eller implementere når du er klar til at gøre det. <br/><br/> Efter udgivelse eller anvendelse af en pakke, kan du foretage ændringer til pakkens egenskaber og tilpasse modulerne yderligere. Derefter genudgives eller re-installeres pakken for at anvende ændringerne." ,
        'editView'=> 'Her kan du redigere de eksisterende felter. Du kan fjerne nogen af de eksisterende felter eller tilføje tilgængelige felter i venstre panel.',
        'create'=>'Når du vælger type fra <b>Type</b> af modul, som du ønsker at oprette, husk på de typer af felter, du gerne vil have inde i modulet. <br/><br/> Hver modul-skabelon indeholder et sæt områder vedrørende den type modul, der beskrives ved titlen <br/><br/> <b>Basic</b> -. Giver grundlæggende felter, der vises i standardmoduler, såsom navn, som tildeles, team, oprettelsesdato og beskrivelsesfelter <br/><br/> <b>Virksomhed</b> -. Giver organisationsspecifikke felter, såsom firmanavn, Industri og faktureringsadresse. Brug denne skabelon til at oprette moduler, der ligner standard Konti modulet <br/><br/> <b>Person</b> -. Giver individuelle-specifikke områder, såsom hilsen, titel, navn, adresse og telefonnummer. Brug denne skabelon til at oprette moduler, der ligner standard kontaktpersoner og ledermoduler <br/><br/> <b>Problem</b> - Giver sags- og bug-specifikke områder, såsom nummer, status, prioritet og beskrivelse. Brug denne skabelon til at oprette moduler, der ligner standard Cases og Bug Tracker moduler <br/><br/>. Bemærk: Når du har oprettet modulet, kan du redigere etiketterne på de områder, som skabelonen tilbyder, samt oprette brugerdefinerede felter for at føje til modulets layouts.',
        'afterSave'=>'Tilpas modulet til dine behov ved at redigere og skabe områder, oprettelse af relationer med andre moduler og arrangere felterne inden for layouts. <br/><br/> For at se skabelonens felter og administrere brugerdefinerede felter i modulet, skal du klikke på <b>Se Felter</b>. <br/><br/> For at oprette og administrere relationer mellem modulet og andre moduler, uanset om moduler allerede er i anvendelse eller andre brugerdefinerede moduler inden for samme pakke, skal du klikke på <b> Vis relationer</b>. <br/><br/> For at redigere modul layout, skal du klikke på <b>Vis layout</b>. Du kan ændre Detail View, Edit View og Visning af liste layouts for modulet, ligesom du ville gøre for moduler der allerede er i applikationen inde i Studio. <br/><br/> For at oprette et modul med de samme egenskaber som det aktuelle modul, klik på <b>Dublér</b>. Du kan tilpasse det nye modul yderligere.',
        'viewfields'=>'Felterne i modulet kan tilpasses dine behov. <br/><br/> Du kan ikke slette standard felter, men du kan fjerne dem fra de relevante layouts inden for Layoutets sider. <br/><br/> Du kan hurtigt oprette nye felter, der har lignende egenskaber som eksisterende felter ved at klikke på <b>Klon</b> i <b>Egenskaber</b> form. Indtast eventuelle nye egenskaber, og klik derefter på <b>Gem</b>. <br/><br/> Det anbefales, at du indstiller alle egenskaberne for standardfelterne og de brugerdefinerede felter, før du udgiver og installerer pakken, der indeholder det brugerdefinerede modul.',
        'viewrelationships'=>'Du kan oprette mange-til-mange relationer mellem det aktuelle modul og andre moduler i pakken, og/eller mellem det aktuelle modul og moduler, der allerede er installeret i applikationen. <br><br> For at oprette en-til-mange og én -til-én relationer, opret <b>Relatér</b> og <b>Flex relatér</b> felterne for modulerne.',
        'viewlayouts'=>'Du kan kontrollere, hvilke felter er tilgængelige til at opfange data inden for <b>Redigér visning</b>. Du kan også kontrollere hvilke data displays i <b>Detaljeret visning</b>. Visningerne behøver ikke at matche. <br/><br/> Hurtig opretttelsesformer vises, når <b>Opret</b> klikkes i et underpanel modul. Som standard er <b>Hurtig Opret</b> form layout det samme som standard <b>Redigér visning</b> layout. Du kan tilpasse Hurtig Opret formen, således at den indeholder færre og/eller forskellige områder end redigeringsvisningslayoutet. <br><br> Du kan bestemme modulets sikkerhed ved brug Layout tilpasning sammen med <b>Role Management</b>. <br><br>',
        'existingModule' =>'Efter at have skabt og tilpasset dette modul, kan du oprette ekstra moduler eller vende tilbage til pakken med <b>Udgiv</b> eller <b>Anvend</b>.<br><br> For at oprette ekstra moduler, klik på <b>Dublér</b> for at oprette et modul med de samme egenskaber som det aktuelle modul eller navigere tilbage til pakken, og klik på <b> Nyt modul</b>. <br><br> Hvis du er klar til at <b>Udgive</b> eller <b>Anvende</b> pakken, der indeholder dette modul, navigér tilbage til pakken for at udføre disse funktioner. Du kan udgive og anvende pakker der indeholder mindst et modul.',
        'labels'=> 'Etiketterne på standard felterne samt brugerdefinerede felter kan ændres. Ændring af feltetiketter vil ikke påvirke data gemt i felterne.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Der er tre kolonner, der vises til venstre. Kolonnen "Standard" indeholder de felter, der vises i en listevisning som standard, kolonnen "Tilgængelig" indeholder felter, som en bruger kan vælge til at bruge til at oprette en brugerdefineret listevisning, og kolonnen "Skjult" indeholder felter, der er til rådighed for dig som admin til enten at tilføje til standard eller ledige kolonner til brug for brugerne, men i øjeblikket er deaktiveret.',
        'savebtn'	=> 'Klik på <b>Gem</b> vil gemme alle ændringerne og gøre dem aktive.',
        'Hidden' 	=> '<b>Skjulte</b> felter er felter, som i øjeblikket ikke er tilgængelig for brugerne listevisninger.',
        'Available' => 'Tilgængelige felter er felter, der ikke bliver vist som standard, men kan aktiveres af brugerne.',
        'Default'	=> '<b>Standard</b> felter vises til brugere, der ikke har oprettet brugerdefinerede listevisningsindstillinger.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Der er to kolonner, der vises til venstre. Kolonnen "Standard" indeholder de felter, som vil blive vist i søgningens visning, og kolonnen "Skjult", der indeholder felter til rådighed for dig som admin til at føje til visningen.',
        'savebtn'	=> 'Klik på <b>Gem og implementer</b>vil gemme alle ændringerne du har lavet og gøre dem aktive.',
        'Hidden' 	=> 'Skjulte felter er felter, som ikke vises i søgninger.',
        'Default'	=> 'Standard felter vises i søgninger.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Der er to kolonner, der vises til venstre. Kolonnen til højre, mærket Aktuelle layout eller Layout Eksempel, er stedet, hvor du ændrer modul layout. Venstre Kolonne med titlen Værktøjskasse indeholder nyttige elementer og værktøjer til brug ved redigering af layoutet. <br/><br/> Hvis layout området har titlen Aktuelle layout, arbejder du på en kopi af layoutet, der i øjeblikket anvendes af modulet til visning. <br/><br/> Hvis den har titlen Layout preview arbejder du på en kopi, der er lavet tidligere ved et klik på knappen Gem, som måske allerede er ændret fra den version, der ses af brugere af dette modul.',
        'saveBtn'	=> 'Hvis du klikker på denne knap gemmer du layoutet, så du kan bevare dine ændringer. Når du vender tilbage til dette modul vil du begynde fra dette ændrede layout. Dit layout vil dog ikke blive set af brugere af modulet, indtil du klikker på Gem og knappen Udgiv.',
        'publishBtn'=> 'Klik på denne knap for at installere layoutet. Det betyder, at dette layout omgående vil blive set af brugere af dette modul.',
        'toolbox'	=> 'Værktøjskassen indeholder en række nyttige funktioner til redigering af layouts, herunder en papirkurv, et sæt af yderligere elementer og et sæt af tilgængelige felter. Enhver af disse kan trækkes og slippes på layoutet.',
        'panels'	=> 'Dette område viser, hvordan dit layout vil se ud for brugere af dette modul, når det bliver anvendt <br/><br/> Du kan flytte rundt på elementerne som felter, rækker og paneler ved at trække og slippe dem; slet elementer ved at trække og slippe dem på papirkurven i værktøjskassen, eller tilføje nye elementer ved at trække dem fra værktøjskassen og slippe dem i layoutet i den ønskede position.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Der er to kolonner, der vises til venstre. Kolonnen til højre, mærket Aktuelle layout eller Layout Eksempel, er stedet, hvor du ændrer modul layout. Venstre Kolonne med titlen Værktøjskasse indeholder nyttige elementer og værktøjer til brug ved redigering af layoutet. <br/><br/> Hvis layout området har titlen Aktuelle layout, arbejder du på en kopi af layoutet, der i øjeblikket anvendes af modulet til visning. <br/><br/> Hvis den har titlen Layout preview arbejder du på en kopi, der er lavet tidligere ved et klik på knappen Gem, som måske allerede er ændret fra den version, der ses af brugere af dette modul.',
        'dropdownaddbtn'=> 'Hvis du klikker på denne knap føjes en ny post til rullelisten.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Tilpasninger foretaget i Studio inde i dette eksempel kan pakkes og anvendes i et andet eksempel. <br><br> Angiv et <b>Pakkenavn</b>. Du kan angive <b>Author</b> og <b>Beskrivelse</b> som information til pakken. <br><br> Vælg modul(er), der indeholder tilpasninger til at eksportere. (Kun moduler indeholder tilpasninger vil blive vist for dig sommuligt valg.) <br><br> Klik på <b>Eksportér</b> for at oprette en .zip-fil med pakken, der indeholder tilpasninger. .zip-Filen kan uploades i et andet eksempel gennem <b>Module Loader</b>.',
        'exportCustomBtn'=>'Klik på <b>Eksporter</b> for at oprette en zip-fil for pakken, der indeholder de tilpasninger, som du ønsker at eksportere.',
        'name'=>'<b>Navnet</b> på pakken vil blive vist i modulindlæseren efter af pakken er indlæst til installation i Studio.',
        'author'=>'<b>Author</b> er navnet på den enhed, der har oprettet pakken. Author kan være enten en enkeltperson eller en virksomhed. <br><br> Author vil blive vist i ModuleLoader efter pakken er uploadet til installation i Studio.
',
        'description'=>'<b>Beskrivelsen</b> af pakken vil blive vist i modulindlæseren efter af pakken er indlæst til installation i Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Velkommen til <b>Udviklerværktøjer</b1> området. <br/><br/> Brug værktøjerne inden for dette område for at oprette og administrere standard og brugerdefinerede moduler og felter.',
        'studioBtn'	=> 'Brug <b>Studio</b> for at tilpasse installerede moduler ved at ændre feltets arrangement ved at vælge hvilke felter, der er tilgængelige og skabe brugerdefinerede datafelter.',
        'mbBtn'		=> 'Brug <b>modulgeneratoren</b> til at skabe nye moduler.',
        'appBtn' 	=> 'Brug applikationstilstand til at tilpasse forskellige egenskaber i programmet, såsom hvor mange TPS rapporter, der vises på hjemmesiden',
        'backBtn'	=> 'Tilbage til det forrige trin.',
        'studioHelp'=> '<b>Brug Studio til at tilpasse installerede moduler.</b>',
        'moduleBtn'	=> 'Klik for at redigere dette modul.',
        'moduleHelp'=> '<b>Vælg det modulkomponent, som du ønsker at redigere</b>',
        'fieldsBtn'	=> '<b>Du kan redigere og oprette brugerdefinerede felter her.</b>',
        'layoutsBtn'=> '<b>Tilpas layoutet for rediger-, detalje-, liste- og søgningsvisning.</b>',
        'subpanelBtn'=> '<b>Rediger hvilke oplysninger, der vises i dette moduls underpaneler.</b>',
        'layoutsHelp'=> 'Vælg et <b>Layout til redigering</b>. <br/<br/> For at ændre layoutet, der indeholder datafelter til indtastning af data, skal du klikke på <b>Redigér visning</b>. <br/> <br/> Hvis du vil ændre layoutet, der viser de indtastede data i felterne i Edit View klik på <b> Detaljeret visning</b>. <br/><br/> For at ændre kolonnerne, der vises i standard, klik på <b>Listevisning</b>. <br/><br/> For at ændre Basis og Avanceret søgning for layouts, klik på <b>Søg</b>.',
        'subpanelHelp'=> '<b>Vælg en underpanel der skal redigeres.</b>',
        'searchHelp' => '<b>Vælg et søgningslayout der skal redigeres.</b>',
        'labelsBtn'	=> 'Rediger <b>etiketterne</b> for at vise værdier i dette modul.',
        'newPackage'=>'Klik på <b>Ny pakke</b> for at oprette en ny pakke.',
        'mbHelp'    => '<b>Velkommen til Modul Builder.</b> <br/><br/> Brug <b>Modul Builder</b> for at oprette pakker indeholdende brugerdefinerede moduler baseret på standard eller brugerdefinerede objekter. <br/><br/> Til at begynde med, skal du klikke på <b>Ny pakke</b> for at oprette en ny pakke, eller vælg en pakke til at redigere. <br/><br/> En <b>Pakke</b> fungerer som en beholder for brugerdefinerede moduler, som alle er en del af et projekt. Pakken kan indeholde en eller flere brugerdefinerede moduler, der kan relateres til hinanden eller til moduler i applikationen. <br/><br/> Eksempler: Du vil måske oprette en pakke, der indeholder et brugerdefineret modul, der er relateret til standard Konti-modulet. Eller, du ønsker måske at oprette en pakke, der indeholder en række nye moduler, der arbejder sammen som et projekt, og som er relateret til hinanden og til moduler i programmet.',
        'exportBtn' => 'Klik på <b>Eksportér tilpasninger</b> for at oprette en pakke med tilpasninger foretaget i Studio til specifikke moduler.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Rullelisteeditor',

//ASSISTANT
'LBL_AS_SHOW' => 'Vis assistent fremover.',
'LBL_AS_IGNORE' => 'Ignorer assistent fremover.',
'LBL_AS_SAYS' => 'Assistenten siger:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Modulgenerator',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Rullelisteeditor',
'LBL_EDIT_DROPDOWN'=>'Rediger rulleliste',
'LBL_DEVELOPER_TOOLS' => 'Udviklerværktøjer',
'LBL_SUGARPORTAL' => 'Sugar Portal-editor',
'LBL_SYNCPORTAL' => 'Synkroniser portal',
'LBL_PACKAGE_LIST' => 'Pakkeliste',
'LBL_HOME' => 'Startside',
'LBL_NONE'=>'- Ingen -',
'LBL_DEPLOYE_COMPLETE'=>'Implementering færdig',
'LBL_DEPLOY_FAILED'   =>'En fejl er fremkommende under installeringsprocessen, din programpakker er muligvis ikke installeret korret.',
'LBL_ADD_FIELDS'=>'Tilføj brugerdefinerede felter',
'LBL_AVAILABLE_SUBPANELS'=>'Tilgængelige underpaneler',
'LBL_ADVANCED'=>'Avanceret',
'LBL_ADVANCED_SEARCH'=>'Avanceret søgning',
'LBL_BASIC'=>'Grundlæggende',
'LBL_BASIC_SEARCH'=>'Grundlæggende søgning',
'LBL_CURRENT_LAYOUT'=>'Layout',
'LBL_CURRENCY' => 'Valuta',
'LBL_CUSTOM' => 'Brugerdefineret',
'LBL_DASHLET'=>'Sugar-dashlet',
'LBL_DASHLETLISTVIEW'=>'Listevisning af Sugar-dashlet',
'LBL_DASHLETSEARCH'=>'Søg efter Sugar-dashlet',
'LBL_POPUP'=>'Popup visning',
'LBL_POPUPLIST'=>'Popup listevisning',
'LBL_POPUPLISTVIEW'=>'Popup listevisning',
'LBL_POPUPSEARCH'=>'Popup søgning',
'LBL_DASHLETSEARCHVIEW'=>'Søg efter Sugar-dashlet',
'LBL_DISPLAY_HTML'=>'Vis HTML-kode',
'LBL_DETAILVIEW'=>'Detaljevisning',
'LBL_DROP_HERE' => '[Slip her]',
'LBL_EDIT'=>'Rediger',
'LBL_EDIT_LAYOUT'=>'Rediger layout',
'LBL_EDIT_ROWS'=>'Rediger rækker',
'LBL_EDIT_COLUMNS'=>'Rediger kolonner',
'LBL_EDIT_LABELS'=>'Rediger etiketter',
'LBL_EDIT_PORTAL'=>'Rediger portal for',
'LBL_EDIT_FIELDS'=>'Rediger felter',
'LBL_EDITVIEW'=>'Rediger visning',
'LBL_FILTER_SEARCH' => "Søg",
'LBL_FILLER'=>'"udfylder"',
'LBL_FIELDS'=>'Felter',
'LBL_FAILED_TO_SAVE' => 'Det lykkedes ikke at gemme',
'LBL_FAILED_PUBLISHED' => 'Det lykkedes ikke at udgive',
'LBL_HOMEPAGE_PREFIX' => 'Min',
'LBL_LAYOUT_PREVIEW'=>'Eksempel på layout',
'LBL_LAYOUTS'=>'Layout',
'LBL_LISTVIEW'=>'Listevisning',
'LBL_RECORDVIEW'=>'Postvisning',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Ny pakke',
'LBL_NEW_PANEL'=>'Nyt panel',
'LBL_NEW_ROW'=>'Ny række',
'LBL_PACKAGE_DELETED'=>'Pakken blev slettet',
'LBL_PUBLISHING' => 'Udgiver ...',
'LBL_PUBLISHED' => 'Udgivet',
'LBL_SELECT_FILE'=> 'Vælg fil',
'LBL_SAVE_LAYOUT'=> 'Gem layout',
'LBL_SELECT_A_SUBPANEL' => 'Vælg et underpanel',
'LBL_SELECT_SUBPANEL' => 'Vælg underpanel',
'LBL_SUBPANELS' => 'Underpaneler',
'LBL_SUBPANEL' => 'Underpanel',
'LBL_SUBPANEL_TITLE' => 'Titel:',
'LBL_SEARCH_FORMS' => 'Søg',
'LBL_STAGING_AREA' => 'Mellemstation "træk og slip poster her"',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar-felter "klik på poster for at føje dem til mellemstation"',
'LBL_SUGAR_BIN_STAGE' => 'Sugar-papirkurv "klik på poster for at føje dem til mellemstation"',
'LBL_TOOLBOX' => 'Værktøjskasse',
'LBL_VIEW_SUGAR_FIELDS' => 'Vis Sugar-felter',
'LBL_VIEW_SUGAR_BIN' => 'Vis Sugar-papirkurv',
'LBL_QUICKCREATE' => 'Hurtig oprettelse',
'LBL_EDIT_DROPDOWNS' => 'Rediger en global rulleliste',
'LBL_ADD_DROPDOWN' => 'Tilføj en ny global rulleliste',
'LBL_BLANK' => '- tom -',
'LBL_TAB_ORDER' => 'Tabulatorrækkefølge:',
'LBL_TAB_PANELS' => 'Vis paneler som faner',
'LBL_TAB_PANELS_HELP' => 'Når tabulatorerne er aktiveret, skal dropdownboksen "type" bruges <br/> for hver sektion for at definere, hvordan det vil blive vist (tab eller panel)',
'LBL_TABDEF_TYPE' => 'Visningstype',
'LBL_TABDEF_TYPE_HELP' => 'Vælg, hvordan dette afsnit skal vises. Denne indstilling har kun effekt, hvis du har aktiveret faner på i dette view.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Faneblad',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Vælg Panel for at få dette panel display med i visningen af ​​layoutet. Vælg Faneblad for at få dette panel vist i et separat faneblad i layoutet. Når Faneblad er angivet for et panel, vil efterfølgende paneler indstillet til at vises som Panel blive tilføjet fanen.<br />En ny fane vil blive oprettet til næste panel, som har Faneblad valgt. Hvis Faneblad er valgt for et panel under det første panel, vil det første panel nødvendigvis være en fane.',
'LBL_TABDEF_COLLAPSE' => 'Fold sammen',
'LBL_TABDEF_COLLAPSE_HELP' => 'Vælg for at gøre panelets standard tilstand sammenfoldet',
'LBL_DROPDOWN_TITLE_NAME' => 'Navn',
'LBL_DROPDOWN_LANGUAGE' => 'Sprog',
'LBL_DROPDOWN_ITEMS' => 'Listeposter',
'LBL_DROPDOWN_ITEM_NAME' => 'Postnavn',
'LBL_DROPDOWN_ITEM_LABEL' => 'Vist etiket',
'LBL_SYNC_TO_DETAILVIEW' => 'Synkroniser til detaljevisning',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Vælg denne mulighed for at synkronisere redigeringsvisningslayout med det tilsvarende detaljevisningslayout. Felter og feltplacering i redigeringsvisning <br>vil blive synkroniseret med og gemt til detaljevisning ved at klikke på Gem eller Gem & Implementer i redigeringsvisning.<br>Layout ændringer finde sted i detaljevisning.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Detaljevisningslayoutet bliver synkroniseret med det tilsvarende redigeringsvisningslayout.<br>Felter og feltplacering i dette detaljevisningslayoutet afspejler felterne og feltplaceringen i redigeringsvisningslayoutet.<br>Ændringer i detaljevisningslayoutet kan ikke gemmes eller sættes ind i denne side. Foretage ændringer eller fjern synkroniseringen for layouts i redigeringsvisning. ',
'LBL_COPY_FROM' => 'Kopiér fra',
'LBL_COPY_FROM_EDITVIEW' => 'Kopier fra redigeringsvisning',
'LBL_DROPDOWN_BLANK_WARNING' => 'Værdierne er påkrævet for både postnavn og vist etiket. For at tilføje en tom post, skal du klikke Tilføj uden at indtaste nogen værdier for postnavn og vist etiket.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Nøgle findes allerede i listen',
'LBL_DROPDOWN_LIST_EMPTY' => 'The list must contain at least one enabled item',
'LBL_NO_SAVE_ACTION' => 'Kunne ikke finde Gem handling for denne visning.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: forkert udformet dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '**Angiver et kombineret felt. Et kombination felt er en samling af de enkelte felter. For eksempel, "Adresse" er et kombination felt, der indeholder "Adresse", "By", "Postnummer", "Område" og "Land".<br><br>Dobbelt klik et kombinations felt for at se, hvilke felter der indeholder.',
'LBL_COMBO_FIELD_CONTAINS' => 'indeholder:',

'LBL_WIRELESSLAYOUTS'=>'Mobillayout',
'LBL_WIRELESSEDITVIEW'=>'Mobil Rediger visning',
'LBL_WIRELESSDETAILVIEW'=>'Mobil Detaljevisning',
'LBL_WIRELESSLISTVIEW'=>'Mobil Listevisning',
'LBL_WIRELESSSEARCH'=>'Mobil Søg',

'LBL_BTN_ADD_DEPENDENCY'=>'Tilføj afhængighed',
'LBL_BTN_EDIT_FORMULA'=>'Ret formel',
'LBL_DEPENDENCY' => 'Afhængighed',
'LBL_DEPENDANT' => 'Afhængig',
'LBL_CALCULATED' => 'Udregnet værdi',
'LBL_READ_ONLY' => 'Skrivebeskyttet',
'LBL_FORMULA_BUILDER' => 'Formelgenerator',
'LBL_FORMULA_INVALID' => 'Ugyldig formel',
'LBL_FORMULA_TYPE' => 'Formularen skal være af typen',
'LBL_NO_FIELDS' => 'Ingen felter fundet',
'LBL_NO_FUNCS' => 'Ingen funktioner fundet',
'LBL_SEARCH_FUNCS' => 'Søgefunktioner...',
'LBL_SEARCH_FIELDS' => 'Søg i felter...',
'LBL_FORMULA' => 'Formel',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Afhængig',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Træk indstillinger fra listen til venstre over under tilgængelige indstillinger i den tilhørende rullegardinsmenu til listerne til højre for at aktivere disse muligheder, når den overordnede valgmulighed er valgt. Hvis ingen emner er tilgængelige når den overordnede valgmulighed er valgt vil den tilhørende rullegardinsmenu ikke blive vist.',
'LBL_AVAILABLE_OPTIONS' => 'Tilgængelige valgmuligheder',
'LBL_PARENT_DROPDOWN' => 'Overordnet rullegardinsmenu',
'LBL_VISIBILITY_EDITOR' => 'Visibilitets editor',
'LBL_ROLLUP' => 'Opløft',
'LBL_RELATED_FIELD' => 'Relateret felt',
'LBL_CONFIG_PORTAL_URL'=>'Link til tilpasset logo. De anbefalede logo dimensioner er 163 × 18 pixels.',
'LBL_PORTAL_ROLE_DESC' => 'Slet ikke denne rolle. Customer Self-Service Portal Role er et system-genereret rolle, der oprettes under Sugar Portal aktiveringsprocessen. Brug Adgangskontrollen i denne rolle for at aktivere og / eller deaktivere Bugs, sager eller Knowledge Base-moduler i Sugar Portal. Du må ikke ændre andre adgangskontroller for denne rolle for at undgå ukendt og uforudsigelig systemadfærd. I tilfælde af utilsigtet sletning af denne rolle, genskabes den ved at deaktivere og aktivere Sugar Portal.',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Primært modul',
'LBL_CUSTOM_RELATIONSHIPS' => '* relation oprettet i Studio',
'LBL_RELATIONSHIPS'=>'Relationer',
'LBL_RELATIONSHIP_EDIT' => 'Rediger relation',
'LBL_REL_NAME' => 'Navn',
'LBL_REL_LABEL' => 'Etiket',
'LBL_REL_TYPE' => 'Type',
'LBL_RHS_MODULE'=>'Relateret modul',
'LBL_NO_RELS' => 'Ingen relationer',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Valgfri betingelse' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Kolonne',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Værdi',
'LBL_SUBPANEL_FROM'=>'Underpanel fra',
'LBL_RELATIONSHIP_ONLY'=>'Ingen synlige elementer oprettes til denne relation, da der er en allerede eksisterende synlig relation mellem disse to moduler.',
'LBL_ONETOONE' => 'En-til-en',
'LBL_ONETOMANY' => 'En-til-mange',
'LBL_MANYTOONE' => 'Mange-til-en',
'LBL_MANYTOMANY' => 'Mange-til-mange',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Vælg en funktion eller komponent.',
'LBL_QUESTION_MODULE1' => 'Vælg et modul.',
'LBL_QUESTION_EDIT' => 'Vælg et modul, der skal redigeres.',
'LBL_QUESTION_LAYOUT' => 'Vælg et layout, der skal redigeres.',
'LBL_QUESTION_SUBPANEL' => 'Vælg et underpanel, der skal redigeres.',
'LBL_QUESTION_SEARCH' => 'Vælg et søgelayout, der skal redigeres.',
'LBL_QUESTION_MODULE' => 'Vælg en modulkomponent, der skal redigeres.',
'LBL_QUESTION_PACKAGE' => 'Vælg en pakke, der skal redigeres, eller opret en ny pakke.',
'LBL_QUESTION_EDITOR' => 'Vælg et værktøj.',
'LBL_QUESTION_DROPDOWN' => 'Vælg en rulleliste, der skal redigeres, eller opret en ny rulleliste.',
'LBL_QUESTION_DASHLET' => 'Vælg et dashletlayout, der skal redigeres.',
'LBL_QUESTION_POPUP' => 'Vælg et popup layout at redigere',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Relater til',
'LBL_NAME'=>'Navn',
'LBL_LABELS'=>'Etiketter',
'LBL_MASS_UPDATE'=>'Masseopdatering',
'LBL_AUDITED'=>'Revision',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Standardværdi',
'LBL_REQUIRED'=>'Obligatorisk',
'LBL_DATA_TYPE'=>'Type',
'LBL_HCUSTOM'=>'BRUGERDEFINERET',
'LBL_HDEFAULT'=>'STANDARD',
'LBL_LANGUAGE'=>'Sprog:',
'LBL_CUSTOM_FIELDS' => '* felt oprettet i Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Rediger etiketter',
'LBL_SECTION_PACKAGES' => 'Pakker',
'LBL_SECTION_PACKAGE' => 'Pakke',
'LBL_SECTION_MODULES' => 'Moduler',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Rullelister',
'LBL_SECTION_PROPERTIES' => 'Egenskaber',
'LBL_SECTION_DROPDOWNED' => 'Rediger rulleliste',
'LBL_SECTION_HELP' => 'Hjælp',
'LBL_SECTION_ACTION' => 'Handling',
'LBL_SECTION_MAIN' => 'Primær',
'LBL_SECTION_EDPANELLABEL' => 'Rediger paneletiket',
'LBL_SECTION_FIELDEDITOR' => 'Rediger felt',
'LBL_SECTION_DEPLOY' => 'Installer',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Rediger synlighed',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Standard',
'LBL_HIDDEN'=>'Skjult',
'LBL_AVAILABLE'=>'Tilgængelige',
'LBL_LISTVIEW_DESCRIPTION'=>'Der vises tre kolonner nedenfor. Kolonnen <b>Standard</b> indeholder felter, der som standard vises i en listevisning. Kolonnen <b>Yderligere</b> indeholder felter, som en bruger kan vælge at bruge til at oprette en brugerdefineret visning. Kolonnen <b>Tilgængelige</b> viser felter, der er tilgængelige for dig som administrator, så du kan føje dem til kolonnerne Standard eller Yderligere til brug for brugerne.',
'LBL_LISTVIEW_EDIT'=>'Listevisningseditor',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Eksempel',
'LBL_MB_RESTORE'=>'Gendan',
'LBL_MB_DELETE'=>'Slet',
'LBL_MB_COMPARE'=>'Sammenlign',
'LBL_MB_DEFAULT_LAYOUT'=>'Standardlayout',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Tilføj',
'LBL_BTN_SAVE'=>'Gem',
'LBL_BTN_SAVE_CHANGES'=>'Gem ændringer',
'LBL_BTN_DONT_SAVE'=>'Fjern ændringer',
'LBL_BTN_CANCEL'=>'Annuller',
'LBL_BTN_CLOSE'=>'Luk',
'LBL_BTN_SAVEPUBLISH'=>'Gem og installer',
'LBL_BTN_NEXT'=>'Næste',
'LBL_BTN_BACK'=>'Tilbage',
'LBL_BTN_CLONE'=>'Klon',
'LBL_BTN_COPY' => 'Kopiér',
'LBL_BTN_COPY_FROM' => 'Copiér fra…',
'LBL_BTN_ADDCOLS'=>'Tilføj kolonner',
'LBL_BTN_ADDROWS'=>'Tilføj rækker',
'LBL_BTN_ADDFIELD'=>'Tilføj felt',
'LBL_BTN_ADDDROPDOWN'=>'Tilføj rulleliste',
'LBL_BTN_SORT_ASCENDING'=>'Sortér stigende',
'LBL_BTN_SORT_DESCENDING'=>'Sortér faldende',
'LBL_BTN_EDLABELS'=>'Rediger etiketter',
'LBL_BTN_UNDO'=>'Fortryd',
'LBL_BTN_REDO'=>'Annuller fortryd',
'LBL_BTN_ADDCUSTOMFIELD'=>'Tilføj brugerdefineret felt',
'LBL_BTN_EXPORT'=>'Eksportér tilpasninger',
'LBL_BTN_DUPLICATE'=>'Dupliker',
'LBL_BTN_PUBLISH'=>'Udgiv',
'LBL_BTN_DEPLOY'=>'Installer',
'LBL_BTN_EXP'=>'Eksportér',
'LBL_BTN_DELETE'=>'Slet',
'LBL_BTN_VIEW_LAYOUTS'=>'Vis layout',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Vis mobile layouts',
'LBL_BTN_VIEW_FIELDS'=>'Vis felter',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Vis relationer',
'LBL_BTN_ADD_RELATIONSHIP'=>'Tilføj relation',
'LBL_BTN_RENAME_MODULE' => 'Skift modulnavn',
'LBL_BTN_INSERT'=>'Indsæt',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Fejl: Feltet findes allerede',
'ERROR_INVALID_KEY_VALUE'=> "Fejl: Ugyldig nøgleværdi: [ ']",
'ERROR_NO_HISTORY' => 'Ingen historikfiler blev fundet',
'ERROR_MINIMUM_FIELDS' => 'Dette layout skal indeholde mindst ét felt',
'ERROR_GENERIC_TITLE' => 'En fejl er opstået',
'ERROR_REQUIRED_FIELDS' => 'Er du sikker på at du vil forsætte? Følgende påkrævende felter mangler i layoutet:',
'ERROR_ARE_YOU_SURE' => 'Er du sikker på at du vil forsætte?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'De(t) følgende felt(er) har beregnede værdier, som ikke vil blive genberegnet i realtid i SugarCRM Mobil redigeringsvisning:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'De(t) følgende felt(er) har beregnede værdier, som ikke vil blive genberegnet i realtid i SugarCRM Portal redigeringsvisning:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Den følgende modul (er) er deaktiveret:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Hvis du ønsker at sætte dem i portalen bedes du aktivere dem her.<a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">her</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Konfigurer portal',
    'LBL_PORTAL_THEME' => 'Tema portal',
    'LBL_PORTAL_ENABLE' => 'Aktivér',
    'LBL_PORTAL_SITE_URL' => 'Din portal er tilgængelig på:',
    'LBL_PORTAL_APP_NAME' => 'Applikationsnavn',
    'LBL_PORTAL_LOGO_URL' => 'Logo URL',
    'LBL_PORTAL_LIST_NUMBER' => 'Antal af poster der skal vises på en liste',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Antal af felter der skal vises i detalje visning',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'ANtal af resultater der skal vise ved global søgning',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Standard tildelt for nye portal registreringer',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Portal layouts',
'LBL_SYNCP_WELCOME'=>'Angiv URL&#39;en til den forekomst af portalen, du vil opdatere.',
'LBL_SP_UPLOADSTYLE'=>'Vælg et typografiark, du vil uploade fra computeren.<br> Typografiarket implementeres på Sugar Portal, næste gang du udfører en synkronisering.',
'LBL_SP_UPLOADED'=> 'Uploadet',
'ERROR_SP_UPLOADED'=>'Sørg for, at du uploader et css-typografiark.',
'LBL_SP_PREVIEW'=>'Her er et eksempel på, hvordan Sugar Portal vil se ud med typografiarket.',
'LBL_PORTALSITE'=>'Sugar Portal-URL:',
'LBL_PORTAL_GO'=>'Start',
'LBL_UP_STYLE_SHEET'=>'Upload typografiark',
'LBL_QUESTION_SUGAR_PORTAL' => 'Vælg et Sugar Portal-layout, der skal redigeres.',
'LBL_QUESTION_PORTAL' => 'Vælg et portallayout, der skal redigeres.',
'LBL_SUGAR_PORTAL'=>'Sugar Portal-editor',
'LBL_USER_SELECT' => 'Vælg brugere',

//PORTAL PREVIEW
'LBL_CASES'=>'Sager',
'LBL_NEWSLETTERS'=>'Nyhedsbreve',
'LBL_BUG_TRACKER'=>'Fejlsporing',
'LBL_MY_ACCOUNT'=>'Min konto',
'LBL_LOGOUT'=>'Log af',
'LBL_CREATE_NEW'=>'Opret ny',
'LBL_LOW'=>'Lav',
'LBL_MEDIUM'=>'Mellem',
'LBL_HIGH'=>'Høj',
'LBL_NUMBER'=>'Nummer:',
'LBL_PRIORITY'=>'Prioritet:',
'LBL_SUBJECT'=>'Emne',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Pakkenavn:',
'LBL_MODULE_NAME'=>'Modulnavn:',
'LBL_MODULE_NAME_SINGULAR' => 'Unik modul navn:',
'LBL_AUTHOR'=>'Forfatter:',
'LBL_DESCRIPTION'=>'Beskrivelse:',
'LBL_KEY'=>'Nøgle:',
'LBL_ADD_README'=>'Vigtigt',
'LBL_MODULES'=>'Moduler:',
'LBL_LAST_MODIFIED'=>'Sidst ændret:',
'LBL_NEW_MODULE'=>'Nyt modul',
'LBL_LABEL'=>'Flertal Label',
'LBL_LABEL_TITLE'=>'Etiket',
'LBL_SINGULAR_LABEL' => 'Ental etiket',
'LBL_WIDTH'=>'Bredde',
'LBL_PACKAGE'=>'Pakke:',
'LBL_TYPE'=>'Type:',
'LBL_TEAM_SECURITY'=>'Teamsikkerhed',
'LBL_ASSIGNABLE'=>'Kan tildeles',
'LBL_PERSON'=>'Person',
'LBL_COMPANY'=>'Firma',
'LBL_ISSUE'=>'Problem',
'LBL_SALE'=>'Salg',
'LBL_FILE'=>'Fil',
'LBL_NAV_TAB'=>'Navigationsfane',
'LBL_CREATE'=>'Opret',
'LBL_LIST'=>'Liste',
'LBL_VIEW'=>'Vis',
'LBL_LIST_VIEW'=>'Listevisning',
'LBL_HISTORY'=>'Vis historik',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Gendan standard layout',
'LBL_ACTIVITIES'=>'Aktiviteter',
'LBL_SEARCH'=>'Søg',
'LBL_NEW'=>'Ny',
'LBL_TYPE_BASIC'=>'grundlæggende',
'LBL_TYPE_COMPANY'=>'firma',
'LBL_TYPE_PERSON'=>'person',
'LBL_TYPE_ISSUE'=>'problem',
'LBL_TYPE_SALE'=>'salg',
'LBL_TYPE_FILE'=>'fil',
'LBL_RSUB'=>'Dette underpanel bliver vist i dit modul',
'LBL_MSUB'=>'Dette underpanel leveres af dit modul til visning i det relaterede modul',
'LBL_MB_IMPORTABLE'=>'Tillad import',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'synlig',
'LBL_VE_HIDDEN'=>'skjult',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] blev slettet',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Eksportér tilpasninger',
'LBL_EC_NAME'=>'Pakkenavn:',
'LBL_EC_AUTHOR'=>'Forfatter:',
'LBL_EC_DESCRIPTION'=>'Beskrivelse:',
'LBL_EC_KEY'=>'Nøgle:',
'LBL_EC_CHECKERROR'=>'Vælg et modul.',
'LBL_EC_CUSTOMFIELD'=>'tilpassede felter',
'LBL_EC_CUSTOMLAYOUT'=>'tilpassede layout',
'LBL_EC_CUSTOMDROPDOWN' => 'tilpasset dropdown(s)',
'LBL_EC_NOCUSTOM'=>'Ingen moduler er tilpasset.',
'LBL_EC_EXPORTBTN'=>'Eksportér',
'LBL_MODULE_DEPLOYED' => 'Modulet er blevet installeret.',
'LBL_UNDEFINED' => 'udefineret',
'LBL_EC_CUSTOMLABEL'=>'tilpassede etikette(r)',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Det lykkedes ikke at hente data',
'LBL_AJAX_TIME_DEPENDENT' => 'En tidsafhængig handling er startet. Vent, og prøv igen om et par sekunder.',
'LBL_AJAX_LOADING' => 'Indlæser...',
'LBL_AJAX_DELETING' => 'Sletter...',
'LBL_AJAX_BUILDPROGRESS' => 'Opbygningen er startet...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Installationen er startet...',
'LBL_AJAX_FIELD_EXISTS' =>'Det angivne feltnavn findes allerede. Angiv et nyt feltnavn.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Er du sikker på, at du vil fjerne denne pakke? Derved bliver alle de filer, der er knyttet til denne pakke, slettet permanent.',
'LBL_JS_REMOVE_MODULE' => 'Er du sikker på, at du vil fjerne dette modul? Derved bliver alle de filer, der er knyttet til dette modul, slettet permanent.',
'LBL_JS_DEPLOY_PACKAGE' => 'Enhver tilretning, som du laver i Studio, vil blive overskrevet, når dette modul er gen-implementeret. Er du sikker på, at du ønsker at fortsætte?',

'LBL_DEPLOY_IN_PROGRESS' => 'Installerer pakke',
'LBL_JS_VALIDATE_NAME'=>'Navn - skal være alfanumerisk og uden mellemrum og starte med et bogstav',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Pakkenøgle findes allerede',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Pakkens navn eksisterer allerede',
'LBL_JS_PACKAGE_NAME'=>'Pakke navn - skal starte med et bogstav og må kun bestå af bogstaver, tal og understregningstegn. Der må ikke bruges mellemrum eller andre specialtegn.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Nøgle - Skal være alfanumerisk og begynde med et bogstav.',
'LBL_JS_VALIDATE_KEY'=>'Nøgle - skal være alfanumerisk og uden mellemrum og starte med et bogstav',
'LBL_JS_VALIDATE_LABEL'=>'Angiv en etiket, der skal bruges som vist navn til dette modul',
'LBL_JS_VALIDATE_TYPE'=>'Vælg den type modul, du vil bygge, fra listen ovenfor',
'LBL_JS_VALIDATE_REL_NAME'=>'Navn - skal være alfanumerisk og uden mellemrum',
'LBL_JS_VALIDATE_REL_LABEL'=>'Etiket - tilføj en etiket, der skal vises over underpanelet',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Er du sikker på du ønsker at slette dette krævede dropdown liste element? Dette kan påvirke funktionaliteten af ​​din ansøgning.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Er du sikker på du ønsker at slette dette rullemenu element? Sletning af Lukket Vundet eller Lukket Tabt faser vil medføre at prognosemodulet ikke fungerer korrekt',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Er du sikker på du ønsker at slette den nye salgs-status? Sletning af denne status vil forårsage Salgsmulighed modulens omsætningspost til ikke at fungerede korrekt.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Er du sikker på du ønsker at slette In Progress salget status? Sletning af denne status vil forårsage Salgsmulighed modulens omsætningspost til ikke at fungerede korrekt.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Er du sikker på du ønsker at slette Lukket Vundet salgsfase? Sletning af denne fase vil medføre at prognosemodulet ikke fungerer korrekt',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Er du sikker på du ønsker at slette Lukket Tabt salgsfase? Sletning af denne fase vil medføre at prognosemodulet ikke fungerer korrekt',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Er du sikker på, at du vil slette denne relation?',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Derved bliver denne relation permanent. Er du sikker på, at du vil installere denne relation?',
'LBL_CONFIRM_DONT_SAVE' => 'Der er foretaget ændringer, siden du sidst gemte. Vil du gemme?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Vil du gemme ændringer?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Data kan blive afkortet, og dette kan ikke fortrydes. Er du sikker på, at du ønsker at fortsætte?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Vælg den relevante datatype på basis af den datatype, der skal angives i feltet.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Konfigurér feltet, så der kan søges på al tekst.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Boosting er processen med at forbedre relevansen for en posts felter.<br/>Felter med et højere boost-niveau vil få større vægt, når søgningen er udført. Når en søgning er udført, vil matchende poster, der indeholder felter med en større vægt blive vist højere i søgeresultaterne.<br/>Standardværdien er 1,0 som står for et neutralt løft. Hvis du vil anvende et positivt løft vil enhver værdi højere end 1 blive accepteret. For et negativ boost bruges værdier lavere end 1. For eksempel En værdi på 01:35 vil positivt styrke et felt med 135 %. Med en værdi på 0,60 anvendes et negativt løft.<br/>Bemærk at i tidligere versioner var det nødvendigt at udføre en fuldtekstsøgning reindeds. Dette er ikke længere nødvendigt.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Ja</b>: Feltet medtages i en importhandling.<br><b>Nej</b>: Feltet medtages ikke i en import.<br><b>Obligatorisk</b>: En værdi for feltet skal angives ved alle importer.',
'LBL_POPHELP_PII'=>'Feltet krydses automatisk af med henblik på revision, og er tilgængeligt i visning af personlige oplysninger.<br>Felter under personlige oplysninger kan også slettes permanent, når posten er relateret til en anmodning om sletning af Data Privacy.<br>Sletning sker via Data Privacy modulet, og kan udføres af administratorer eller brugere som har Data Privacy Manager stilling.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Indtast et tal for bredden, målt i pixel.<br><br />Det uploadede billede vil blive skaleret til denne bredde.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Indtast et tal for højden, målt i pixel.<br><br />Det uploadede billede vil blive skaleret til denne højde.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Vælg at bruge dette felt, når du søger efter poster ved hjælp af Global Søgning i dette modul.',
//Revert Module labels
'LBL_RESET' => 'Nulstil',
'LBL_RESET_MODULE' => 'Nulstil modul',
'LBL_REMOVE_CUSTOM' => 'Fjern tilpasninger',
'LBL_CLEAR_RELATIONSHIPS' => 'Ryd relationerne',
'LBL_RESET_LABELS' => 'Nulstil etiketter',
'LBL_RESET_LAYOUTS' => 'Nulstil layouts',
'LBL_REMOVE_FIELDS' => 'Fjern brugerdefinerede felter',
'LBL_CLEAR_EXTENSIONS' => 'Nulstil tilføjelser',

'LBL_HISTORY_TIMESTAMP' => 'Tidsstempel',
'LBL_HISTORY_TITLE' => 'Historik',

'fieldTypes' => array(
                'varchar'=>'Tekstfelt',
                'int'=>'Heltal',
                'float'=>'Decimal',
                'bool'=>'Afkrydsningsfelt',
                'enum'=>'Rulleliste',
                'multienum' => 'MultiSelect',
                'date'=>'Dato',
                'phone' => 'Telefon',
                'currency' => 'Valuta',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Relater',
                'address' => 'Adresse',
                'text' => 'Tekstområde',
                'url' => 'Link',
                'iframe' => 'IFrame',
                'image' => 'Billede',
                'encrypt'=>'Krypter',
                'datetimecombo' =>'Dato/klokkeslæt',
                'decimal'=>'Decimal',
),
'labelTypes' => array(
    "" => "Ofte brugte etiketter",
    "all" => "Alle etiketter",
),

'parent' => 'Fleks. relater',

'LBL_ILLEGAL_FIELD_VALUE' =>"Dropdown nøglen kan ikke indeholde anførselstegn.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Du har valgt dette element til fjernes fra dropdown listen. Ethvert dropdown felt, som bruger denne liste med dette element som en værdi, vil ikke længere vise værdien, og og værdien vil ikke længere kunne vælges fra dropdown felter. Er du sikker på, at du ønsker at fortsætte?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Alle moduler',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (relateret {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Copiér fra layout',
);
