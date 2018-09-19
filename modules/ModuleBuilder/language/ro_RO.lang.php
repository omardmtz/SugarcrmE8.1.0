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
    'LBL_LOADING' => 'Incarcare...Va rugam asteptati' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Ascunde optiuni' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Ștergere' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Creat de SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Rol',
'help'=>array(
    'package'=>array(
            'create'=>'Furnizaţi un Nume pentru pachetul. Numele pe care îl introduceţi trebuie să fie alfanumeric şi nu conţina spaţii. (Exemplu: HR_Management)<br /><br />Puteţi furniza informaţii Autor şi Descriere pentru pachet.<br /><br />Faceţi clic pe Salvare pentru a crea pachet.',
            'modify'=>'Proprietăţile şi acţiunile posibile pentru pachet apar aici.<br /><br />Puteţi modifica Numele, Autorul  şi Descrierea pachetului, precum şi  vizualiza şi personaliza toate modulele conţinute în pachet.<br /><br />Faceţi clic pe New Modulul pentru a crea un modul pentru acest pachet.<br /><br />Dacă pachetul conţine cel puţin un modul, aveţi posibilitatea să publicaţi şi Implementaţi pachet, precum şi Exporta personalizările făcute în pachet.',
            'name'=>'Specifica Nume',
            'author'=>'autor<br /><br />Aceasta este autorul care este afişat în timpul instalării ca numele entităţii care a creat pachetul.<br /><br />Autorul ar putea fi un individ sau o companie.',
            'description'=>'Acest şablon este folosit pentru a trimite utilizatorului un link să faceţi clic pentru a reseta parola pentru contul de utilizator.',
            'publishbtn'=>'publishbtn<br />Faceţi clic pe Publicare pentru a salva toate datele introduse şi pentru a crea un fişier zip care este o versiune a pachetului instalabil..<br /><br />Utilizaţi Modulul Loader pentru a încărca fişierul. zip şi instala pachetul.',
            'deploybtn'=>'implementati  BTN<br />Faceţi clic pe Implementaţi pentru a salva toate datele introduse şi să instaleze pachetul, inclusiv toate modulele, în instanţă curenta.',
            'duplicatebtn'=>'duplicatebtn<br /><br />Faceţi clic pe Duplicare pentru a copia conţinutul pachetului într-un pachet nou şi pentru a afişa noul pachet.<br /><br />Pentru noul pachet, un nou nume va fi generate automat prin adăugarea unui număr la sfârşitul numelui de pachetul folosit pentru a crea unul nou. Aveţi posibilitatea să redenumiţi noul pachet prin introducerea unui nume nou şi faceţi clic pe Salvare.',
            'exportbtn'=>'exportbtn<br />Faceţi clic pe Export pentru a crea un fişier zip care conţine personalizările făcute în pachet..<br /><br />Fişierul generat nu este o versiune a pachetului instalabil.<br /><br />Utilizaţi Modulul Loader pentru a importa fişierul zip şi. să aibă pachetul, inclusiv personalizările, a în Modulul Builder.',
            'deletebtn'=>'Faceți clic pe <b>Ștergere</b> pentru a șterge acest pachet și toate fișierele conexe.',
            'savebtn'=>'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
            'existing_module'=>'Faceţi clic pe pictograma Modulul pentru a edita proprietăţile şi personaliza câmpurile, relaţiile şi aspecte asociate cu modulul.',
            'new_module'=>'Faceţi clic pe Modul Nou pentru a crea un nou modul pentru acest pachet.',
            'key'=>'Acest 5 litere, alfanumerice Key vor fi folosite pentru a prefixa toate directoarele, nume de clase şi tabele de baze de date pentru toate modulele în pachetul actual.<br /><br />Cheia este utilizata într-un efort de a obţine  unicitatea numelui tabelului.',
            'readme'=>'Clic pentru a adăuga txt Readme pentru acest pachet.<br />Readme va fi disponibila la momentul instalării.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Furnizaţi un Nume pentru pachetul. Numele pe care îl introduceţi trebuie să fie alfanumeric şi nu conţina spaţii. (Exemplu: HR_Management)<br /><br />Puteţi furniza informaţii Autor şi Descriere pentru pachet.<br /><br />Faceţi clic pe Salvare pentru a crea pachet.',
        'modify'=>'Proprietăţile şi acţiunile posibile pentru pachet apar aici.<br /><br />Puteţi modifica Numele, Autorul  şi Descrierea pachetului, precum şi  vizualiza şi personaliza toate modulele conţinute în pachet.<br /><br />Faceţi clic pe New Modulul pentru a crea un modul pentru acest pachet.<br /><br />Dacă pachetul conţine cel puţin un modul, aveţi posibilitatea să publicaţi şi Implementaţi pachet, precum şi Exporta personalizările făcute în pachet.',
        'importable'=>'Verificati caseta de selectare, Importable va permite importul pentru acest modul.<br /><br />Un link către Expertul Import va apărea în panoul Comenzi rapide în modul. Expertul Import facilitează importul de date din surse externe în modul personalizat.',
        'team_security'=>'Verificati Team Security, caseta de selectare va permite echipei de securitate acest modul<br />În cazul în care echipa de securitate este activată, câmpul de selecţie Echipa va apărea în înregistrările din modul.',
        'reportable'=>'Verificarea acestei casete va permite acest modul de a avea rapoarte  împotriva ei.',
        'assignable'=>'Verificarea acestei casete va permite o înregistrare în acest modul pentru a fi atribuite la un utilizator selectat.',
        'has_tab'=>'Verificarea Tab de navigare va oferi o filă de navigare pentru modul',
        'acl'=>'Verificarea acestei casete va permite efectuarea de controale de acces la acest modul, inclusiv securitate la nivel de câmp.',
        'studio'=>'Verificarea acestei casete va permite administratorilor să personalizeze acest modul în cadrul Studio.',
        'audit'=>'Verificarea această casetă va permite Audit pentru acest modul. Modificările aduse anumite domenii vor fi înregistrate astfel încât administratorii pot revizui istoria schimbărilor.',
        'viewfieldsbtn'=>'Faceți clic pe Vizualizare Domenii pentru a vedea câmpurile asociate cu modulul și pentru a crea și edita câmpuri personalizate.',
        'viewrelsbtn'=>'Faceți clic pe Vizualizare Relații pentru a vedea relațiile asociate cu acest modul și pentru a crea noi relatii.',
        'viewlayoutsbtn'=>'Faceți clic pe Vizualizare Layouts pentru a vedea aspectele de modul și de a personaliza dispunerea domeniului în aspecte',
        'viewmobilelayoutsbtn' => 'Faceţi clic pe <b>Vizualizate aspecte mobile</b> pentru a vizualiza aspectele mobile pentru modul şi pentru a personaliza aranjarea câmpurilor în aspecte.',
        'duplicatebtn'=>'duplicatebtn<br /><br />Faceţi clic pe Duplicare pentru a copia conţinutul pachetului într-un pachet nou şi pentru a afişa noul pachet.<br /><br />Pentru noul pachet, un nou nume va fi generate automat prin adăugarea unui număr la sfârşitul numelui de pachetul folosit pentru a crea unul nou. Aveţi posibilitatea să redenumiţi noul pachet prin introducerea unui nume nou şi faceţi clic pe Salvare.',
        'deletebtn'=>'Fă clic pe <b>Ștergere</b> pentru a șterge acest modul.',
        'name'=>'Specifica Nume',
        'label'=>'Aceasta este eticheta care va apărea în fila de navigare pentru modulul.',
        'savebtn'=>'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
        'type_basic'=>'Tipul de șablon de bază prevede domenii de bază, cum ar fi Nume, atribuite, pe echipe, data crearii si campuri Descriere.',
        'type_company'=>'Tipul de model <b>Companie</b>oferă câmpuri specifice organizaţiilor, cum ar fi Denumirea companiei, Industria şi Adresa de facturare.<br/><br/>Utilizaţi acest model pentru a crea module care sunt similare cu modulul standard Conturi.',
        'type_issue'=>'Tipul de model <b>Problemă</b> oferă câmpuri specifice pentru cazuri şi erori, cum ar fi Numărul, Starea, Prioritatea şi Descrierea.<br/><br/>Utilizaţi acest model pentru a crea module care sunt similare cu modulele standard Cazuri şi Tracker erori.',
        'type_person'=>'Tipul de model <b>Persoană</b> oferă câmpuri specifice indivizilor, cum ar fi Titulatura, Titlul, Numele, Adresa şi Numărul de telefon.<br/><br/>Utilizaţi acest model pentru a crea module care sunt similare cu modulele standard Contacte şi Clienţi potenţiali.',
        'type_sale'=>'Tipul de model <b>Vânzare</b> oferă câmpuri specifice pentru oportunităţi, cum ar fi Sursă client potenţial, Stadiu, Sumă şi Rentabilitate. <br/><br/>Utilizaţi acest model pentru a crea module care sunt similare cu modulul standard Oportunităţi.',
        'type_file'=>'Modelul <b>Fişier</b> oferă câmpuri specifice pentru documente, cum ar fi Nume fişier, Tip document şi Data publicării.<br><br>Utilizaţi acest model pentru a crea module similare cu modulul standard Documente.',

    ),
    'dropdowns'=>array(
        'default' => 'Initial',
        'editdropdown'=>'Listele verticale pot fi utilizate pentru câmpurile verticale standard sau personalizate în orice modul.<br><br>Atribuiţi un <b>Nume</b> pentru lista derulantă.<br><br>Dacă există vreun pachet lingvistic instalat în aplicaţie, puteţi selecta <b>Limba</b> de utilizat pentru elementele din listă.<br><br>În câmpul <b>Nume element</b>, atribuiţi un nume pentru opţiunea din lista verticală.  Acest nume nu va apărea în lista verticală care este vizibilă utilizatorilor.<br><br>În câmpul <b>Afişare etichetă</b>, atribuiţi o etichetă care va fi vizibilă utilizatorilor.<br><br>După ce atribuiţi numele elementului şi eticheta de afişare, faceţi clic pe <b>Adăugare</b> pentru a adăuga elementul în lista verticală.<br><br>Pentru a reordona elementele din listă, glisaţi şi fixaţi elementele în poziţiile dorite.<br><br>Pentru a edita eticheta de afişare a unui element, faceţi clic pe <b>Editare pictogramă</b> şi introduceţi o etichetă nouă. Pentru a şterge un element din lista verticală, faceţi clic pe <b>Ştergere pictogramă</b>.<br><br>Pentru a anula o modificare făcută la eticheta de afişare, faceţi clic pe <b>Anulare</b>.  Pentru a reface o modificare anulată, faceţi clic pe <b>Refacere</b>.<br><br>Faceţi clic pe <b>Salvare</b> pentru a salva lista verticală.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
        'historyBtn'=> 'Faceţi clic pe <b>Vizualizare istoric</b> pentru a vizualiza şi restaura un aspect salvat anterior din istoric.',
        'historyRestoreDefaultLayout'=> 'Faceţi clic pe <b>Restaurare aspect implicit</b> pentru a restaura o vizualizare la aspectul său iniţial.',
        'Hidden' 	=> 'Ascuns',
        'Default'	=> 'Initial',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
        'historyBtn'=> 'Faceţi clic pe <b>Vizualizare istoric</b> pentru a vizualiza şi restaura un aspect salvat anterior din istoric.<br><br><b>Restaurare</b> din <b>Vizualizare istoric</b> restaurează amplasarea câmpurilor din aspectele salvate anterior. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'historyRestoreDefaultLayout'=> 'Faceţi clic pe <b>Restaurare aspect implicit</b> pentru a restaura o vizualizare la aspectul său iniţial.<br><br><b>Restaurare aspect implicit</b> restaurează doar amplasarea câmpurilor în aspectul iniţial. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'Hidden' 	=> 'Ascuns',
        'Available' => 'In stoc',
        'Default'	=> 'Initial'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
        'historyBtn'=> 'Faceţi clic pe <b>Vizualizare istoric</b> pentru a vizualiza şi restaura un aspect salvat anterior din istoric.<br><br><b>Restaurare</b> din <b>Vizualizare istoric</b> restaurează amplasarea câmpurilor din aspectele salvate anterior. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'historyRestoreDefaultLayout'=> 'Faceţi clic pe <b>Restaurare aspect implicit</b> pentru a restaura o vizualizare la aspectul său iniţial.<br><br><b>Restaurare aspect implicit</b> restaurează doar amplasarea câmpurilor în aspectul iniţial. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'Hidden' 	=> 'Ascuns',
        'Default'	=> 'Initial'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
        'Hidden' 	=> 'Ascuns',
        'historyBtn'=> 'Faceţi clic pe <b>Vizualizare istoric</b> pentru a vizualiza şi restaura un aspect salvat anterior din istoric.<br><br><b>Restaurare</b> din <b>Vizualizare istoric</b> restaurează amplasarea câmpurilor din aspectele salvate anterior. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'historyRestoreDefaultLayout'=> 'Faceţi clic pe <b>Restaurare aspect implicit</b> pentru a restaura o vizualizare la aspectul său iniţial.<br><br><b>Restaurare aspect implicit</b> restaurează doar amplasarea câmpurilor în aspectul iniţial. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'Default'	=> 'Initial'
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
        'saveBtn'	=> 'Ckic pt a salva toate modificarile',
        'historyBtn'=> 'Faceţi clic pe <b>Vizualizare istoric</b> pentru a vizualiza şi restaura un aspect salvat anterior din istoric.<br><br><b>Restaurare</b> din <b>Vizualizare istoric</b> restaurează amplasarea câmpurilor din aspectele salvate anterior. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'historyRestoreDefaultLayout'=> 'Faceţi clic pe <b>Restaurare aspect implicit</b> pentru a restaura o vizualizare la aspectul său iniţial.<br><br><b>Restaurare aspect implicit</b> restaurează doar amplasarea câmpurilor în aspectul iniţial. Pentru a modifica etichetele câmpurilor, faceţi clic pe Editare pictogramă din dreptul fiecărui câmp.',
        'publishBtn'=> 'Faceţi clic pe <b>Salvare şi implementare</b> pentru a salva toate modificările pe care le-aţi făcut la aspect de când l-aţi salvat ultima oară şi pentru a activa modificările în modul.<br><br>Aspectul va fi afişat imediat în modul.',
        'toolbox'	=> '<b>Caseta de instrumente</b> conţine <b>Coşul de reciclare</b>, elemente suplimentare de aspect şi setul de câmpuri disponibile de adăugat la aspect.<br/><br/>Elementele şi câmpurile aspectului din Caseta de instrumente pot fi glisate şi fixate în aspect, iar elementele şi câmpurile aspectului pot fi glisate şi fixate din aspect în Caseta de instrumente.<br><br>Elementele de aspect sunt <b>Panouri</b> şi <b>Rânduri</b>. Adăugarea unui rând nou sau a unui panou nou la aspect oferă locaţii suplimentare în aspect pentru câmpuri.<br/><br/>Glisaţi şi fixaţi oricare câmp în Caseta de instrumente sau orice aspect pe o poziţie de câmp ocupată pentru a schimba locaţiile celor două câmpuri.<br/><br/>Câmpul <b>Filler</b> creează un spaţiu gol în aspect, acolo unde este plasat.',
        'panels'	=> 'Această zonă arată cum va arăta aspect pentru utilizatorii de acest modul atunci când este depolyed.<br /><br />Puteţi repoziţiona elemente, cum ar fi câmpurile, rânduri şi panouri prin glisare şi fixare a acestora; şterge elemente prin glisarea şi fixarea lor pe zona de gunoi din caseta de instrumente, sau adăuga elemente noi, trăgându-le de la set de instrumente şi fixarea acestora pe aspectul înpoziţia dorită.',
        'delete'	=> 'Glisaţi şi fixaţi orice element aici pentru a-l elimina din aspect',
        'property'	=> 'Editaţi <b>Eticheta</b> afişată pentru acest câmp.<br><br><b>Lăţime</b> oferă o valoare a lăţimii în pixeli pentru modulele Sidecar şi un procent din lăţimea tabelului pentru modulele compatibile invers.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Initial',
        'mbDefault'=>'<b>Câmpurile</b> care sunt disponibile pentru modul sunt listate aici după Nume câmp.<br><br>Pentru a configura proprietăţile unui câmp, faceţi clic pe Nume câmp.<br><br>Pentru a crea un câmp nou, faceţi clic pe <b>Adăugare câmp</b>. Eticheta împreună cu celelalte proprietăţi ale câmpului nou pot fi editate după creare, făcând clic pe Nume câmp.<br><br>După implementarea modulului, câmpurile nou create în Creator de module sunt considerate câmpuri standard în modulul implementat în Studio.',
        'addField'	=> 'Selectaţi <b>Tip date</b> pentru câmpul nou. Tipul pe care îl selectaţi determină ce fel de caractere pot fi introduse în câmp. De exemplu, doar cifre care sunt numere întregi pot fi introduse în câmpuri care au tipul de date Număr întreg.<br><br> Atribuiţi un <b>Nume</b> pentru câmp.  Numele trebuie să fie alfanumeric şi nu trebuie să conţină spaţii. Caracterele de subliniere sunt valide.<br><br> <b>Etichetă de afişare</b> este eticheta care va apărea pentru câmpuri în aspectele modulului.  <b>Etichetă sistem</b> este utilizată pentru a face referire la câmpul din cod.<br><br> În funcţie de tipul de date selectat pentru câmp, unele sau toate dintre proprietăţile următoare pot fi setate pentru câmp:<br><br> <b>Text de ajutor</b> apare temporar în timp ce un utilizator trece cu mouse-ul peste câmp şi poate fi utilizat pentru a comunica utilizatorului tipul de date dorite.<br><br> <b>Text de comentarii</b> este afişat doar în Studio şi/sau în Creatorul de module şi poate fi utilizat pentru a descrie câmpul pentru administratori.<br><br> <b>Valoare implicită</b> va apărea în câmp.  Utilizatorii pot introduce o valoare nouă în câmp sau pot utiliza valoarea implicită.<br><br> Selectaţi caseta <b>Actualizare în masă</b> pentru a putea utiliza funcţia Actualizare în masă pentru câmp.<br><br>Valoarea <b>Dimensiune maximă</b> determină numărul maxim de caractere ce pot fi introduse în câmp.<br><br> Selectaţi caseta <b>Câmp obligatoriu</b> pentru a seta câmpul drept obligatoriu. Trebuie să se atribuie o valoare pentru câmp, pentru a putea salva o înregistrare ce conţine câmpul.<br><br> Selectaţi caseta <b>Raportabil</b> pentru a permite utilizarea câmpului pentru filtre şi pentru afişarea datelor în Rapoarte.<br><br> Selectaţi caseta <b>Audit</b> pentru a putea urmări modificările pentru câmp în Jurnal modificări.<br><br>Selectaţi o opţiune din câmpul <b>Importabil</b> pentru a permite, a interzice sau a solicita importarea câmpului în Asistentul de import.<br><br>Selectaţi o opţiune din câmpul <b>Îmbinare duplicate</b> pentru a activa sau a dezactiva funcţiile Îmbinare duplicate şi Găsire duplicate.<br><br>Se pot seta proprietăţi suplimentare pentru anumite tipuri de date.',
        'editField' => 'Proprietăţile acestui câmp pot fi personalizate.<br><br>Faceţi clic pe <b>Clonare</b> pentru a crea un câmp nou cu aceleaşi proprietăţi.',
        'mbeditField' => '<b>Eticheta de afişare</b> a unui câmp model poate fi personalizată. Celelalte proprietăţi ale câmpului nu pot fi personalizate.<br><br>Faceţi clic pe <b>Clonare</b> pentru a crea un câmp nou cu aceleaşi proprietăţi.<br><br>Pentru a elimina un câmp model astfel încât să nu se afişeze în modul, eliminaţi câmpul din <b>Aspectele</b> corespunzătoare.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Personalizările făcute în Studio în acest exemplu, pot fi ambalate şi implementate într-o altă instanţă.<br /><br />Furnizaţi o numele pachetului. Puteţi furniza Autor şi informaţii de descriere pentru pachet.<br /><br />Selectaţi modul (e) care conţin personalizări de a exporta. (Numai modulele care conţin Personalizarea va apărea pentru tine de a selecta.)<br /><br />Faceţi clic pe Export pentru a crea un fişier. Zip pentru pachetul care conţine particularizări.Fişier. Zip poate fi încărcat într-un alt exemplu, prin Loade Modulul',
        'exportCustomBtn'=>'Faceţi clic pe Export pentru a crea un fişier. Zip pentru pachetul care conţine particularizări pe care doresc să exporte.',
        'name'=>'Specifica Nume',
        'author'=>'autor<br /><br />Aceasta este autorul care este afişat în timpul instalării ca numele entităţii care a creat pachetul.<br /><br />Autorul ar putea fi un individ sau o companie.',
        'description'=>'Acest şablon este folosit pentru a trimite utilizatorului un link să faceţi clic pentru a reseta parola pentru contul de utilizator.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bun venit la zona <b>Instrumente pentru dezvoltatori</b>. <br/><br/>Utilizaţi instrumentele din această zonă pentru a crea şi administra câmpuri şi module standard şi personalizate.',
        'studioBtn'	=> 'Utilizaţi <b>Studio</b> pentru a personaliza modulele implementate.',
        'mbBtn'		=> 'Utilizaţi <b>Creator de module</b> pentru a crea module noi.',
        'sugarPortalBtn' => 'Utilizaţi <b>Editor Portal Sugar</b> pentru a administra şi personaliza Portalul Sugar.',
        'dropDownEditorBtn' => 'Utilizaţi <b>Editor liste verticale</b> pentru a adăuga şi edita listele verticale globale pentru câmpurile verticale.',
        'appBtn' 	=> 'Modul Aplicaţie este locul în care puteţi personaliza diverse proprietăţi ale programului, cum ar fi câte rapoarte TPS să se afişeze pe pagina de pornire',
        'backBtn'	=> 'Intoarcete la pasul anterior',
        'studioHelp'=> 'Utilizaţi <b>Studio</b> pentru a determina ce informaţii sunt afişate în module şi cum sunt afişate acestea.',
        'studioBCHelp' => ' indică faptul că modulul este un modul compatibil invers',
        'moduleBtn'	=> 'Faceţi clic pentru a edita acest modul.',
        'moduleHelp'=> 'Componentele pe care le puteţi personaliza pentru modul apar aici.<br><br>Faceţi clic pe o pictogramă pentru a selecta componenta de editat.',
        'fieldsBtn'	=> 'Creaţi şi personalizaţi <b>Câmpuri</b> pentru a stoca informaţiile în modul.',
        'labelsBtn' => 'Editaţi <b>Etichetele</b> care se afişează pentru câmpuri şi alte titluri din modul.'	,
        'relationshipsBtn' => 'Adăugaţi <b>Relaţii</b> noi sau vizualizaţi-le pe cele existente pentru modul.' ,
        'layoutsBtn'=> 'Personalizaţi modulul <b>Aspecte</b>.  Aspectele sunt vizualizări diferite ale modulului care conţine câmpurile.<br><br>Puteţi determina ce câmpuri apar şi cum sunt organizate în fiecare aspect.',
        'subpanelBtn'=> 'Determinaţi ce câmpuri apar în <b>Subpanouri</b> în modul.',
        'portalBtn' =>'Personalizaţi modulul <b>Aspecte</b> care apare în <b>Portalul Sugar</b>.',
        'layoutsHelp'=> 'Modulul <b>Aspecte</b> care poate fi personalizat apare aici.<br><br>Aspectele afişează câmpuri şi date de câmpuri.<br><br>Faceţi clic pe o pictogramă pentru a selecta aspectul de editat.',
        'subpanelHelp'=> '<b>Subpanourile</b> din modulul care poate fi personalizat apar aici.<br><br>Faceţi clic pe o pictogramă pentru a selecta modulul de editat.',
        'newPackage'=>'Faceţi clic pe <b>Pachet nou</b> pentru a crea un pachet nou.',
        'exportBtn' => 'Faceţi clic pe <b>Export personalizări</b> pentru a crea şi descărca un pachet care conţine personalizările efectuate în Studio pentru anumite module.',
        'mbHelp'    => 'Utilizaţi <b>Creator de module</b> pentru a crea pachete care conţin module personalizate bazate pe obiecte standard sau personalizate.',
        'viewBtnEditView' => 'Personalizaţi aspectul <b>Editare vizualizare</b> al modulului.<br><br>Editare vizualizare este formularul care conţine câmpuri de introducere pentru a captura datele introduse de utilizator.',
        'viewBtnDetailView' => 'Personalizaţi aspectul <b>Detaliu vizualizare</b> al modulului.<br><br>Detaliu vizualizare afişează datele câmpului introduse de utilizator.',
        'viewBtnDashlet' => 'Personalizaţi <b>Tabloul Sugar</b> al modulului, inclusiv Listă vizualizare şi Căutare din Tabloul Sugar.<br><br>Tabloul Sugar va fi disponibil pentru a adăuga pagini în modulul Pagină de pornire.',
        'viewBtnListView' => 'Personalizaţi aspectul <b>Listă vizualizare</b> al modulului.<br><br>Rezultatele de Căutare apar în Listă vizualizare.',
        'searchBtn' => 'Personalizaţi aspectele <b>Căutare</b> ale modulului.<br><br>Determinaţi ce câmpuri pot fi utilizate pentru a filtra înregistrările care apar în Listă vizualizare.',
        'viewBtnQuickCreate' =>  'Personalizaţi aspectul <b>Creare rapidă</b> al modulului.<br><br>Formularul Creare rapidă apare în subpanouri şi în modulul E-mailuri.',

        'searchHelp'=> 'Formularele <b>Căutare</b> ce pot fi personalizate apar aici.<br><br>Formularele de căutare conţin câmpuri pentru filtrarea înregistrărilor.<br><br>Faceţi clic pe o pictogramă pentru a căuta aspectul de căutare de editat.',
        'dashletHelp' =>'Aspectele pentru <b>Tabloul Sugar</b> ce pot fi personalizate apar aici.<br><br>Tabloul Sugar va fi disponbiil pentru a adăuga pagini în modulul Pagină de pornire.',
        'DashletListViewBtn' =>'<b>Listă vizualizare Tablou Sugar</b> afişează înregistrările pe baza filtrelor de căutare din Tabloul Sugar.',
        'DashletSearchViewBtn' =>'<b>Căutare Tablou Sugar</b> filtrează înregistrările pentru lista de vizualizare Tablou Sugar.',
        'popupHelp' =>'Aspectele <b>Pop-up</b> ce pot fi personalizate apar aici.<br>',
        'PopupListViewBtn' => 'Aspectul <b>Listă vizualizare pop-up</b> este utilizat pentru a vizualiza o listă de înregistrări atunci când se selectează una sau mai multe înregistrări pentru a face legătura cu înregistrarea curentă.',
        'PopupSearchViewBtn' => 'Aspectul <b>Căutare pop-up</b> le permite utilizatorilor să caute înregistrări pentru a face legătura cu o înregistrare curentă şi apare deasupra listei de vizualizare pop-up în aceeaşi fereastră. Modulele moştenite utilizează acest aspect pentru căutarea pop-up, în timp ce modulele Sidecar utilizează configurarea Căutare aspecte.',
        'BasicSearchBtn' => 'Personalizaţi formularul <b>Căutare de bază</b> care apare în fila Căutare de bază din zona de Căutare a modulului.',
        'AdvancedSearchBtn' => 'Personalizaţi formularul <b>Avansat</b> care apare în fila Căutare avansată din zona Căutare a modulului.',
        'portalHelp' => 'Administraţi şi personalizaţi <b>Portalul Sugar</b>.',
        'SPUploadCSS' => 'Încărcaţi o <b>Foaie de stil</b> pentru Portalul Sugar.',
        'SPSync' => '<b>Sincronizaţi</b> personalizările în instanţa Portalului Sugar.',
        'Layouts' => 'Personalizaţi <b>Aspectele</b> pentru modulele Portalului Sugar.',
        'portalLayoutHelp' => 'Modulele din Portalul Sugar apar în această zonă.<br><br>Selectaţi un modul pentru a edita <b>Aspectele</b>.',
        'relationshipsHelp' => 'Toatee <b>Relaţiile</b> care există între modul şi alte module implementate apar aici.<br><br>Relaţia <b>Nume</b> este numele generat de sistem pentru relaţie.<br><br><b>Modulul principal</b> este modulul care deţine relaţiile.  De exemplu, toate proprietăţile relaţiilor pentru care modulul Conturi este modulul principal sunt stocate în tabelele bazelor de date Conturi.<br><br><b>Tipul</b> este tipul de relaţie care există între Modulul principal şi <b>Modulul conex</b>.<br><br>Faceţi clic pe titlul unei coloane pentru a sorta după coloană.<br><br>Faceţi clic pe un rând din tabelul relaţiei pentru a vizualiza proprietăţile asociate cu relaţia.<br><br>Faceţi clic pe <b>Adăugare relaţie</b> pentru a crea o relaţie nouă.<br><br>Relaţiile pot fi create între oricare două module implementate.',
        'relationshipHelp'=>'<b>Relaţiile</b> pot fi create între modul şi un alt modul implementat.<br><br> Relaţiile sunt exprimate vizual prin subpanouri şi câmpuri conexe din înregistrările modulelor.<br><br>Selectaţi una dintre următoarele relaţii <b>Tipuri</b> pentru modul:<br><br> <b>Unu la unu</b> - Înregistările ambelor module vor conţine câmpuri conexe.<br><br> <b>Unu la mai multe</b> - Înregistrarea Modulului principal va conţine un subpanou şi înregistrarea Modulului conex va conţine un câmp conex.<br><br> <b>Multe la multe</b> - Înregistrările ambelor module vor afişa subpanouri.<br><br> Selectaţi <b>Modulul conex</b> pentru relaţie. <br><br>Dacă tipul relaţiei presupune subpanouri, selectaţi vizualizarea subpanou pentru modulele corespunzătoare.<br><br> Faceţi clic pe <b>Salvare</b> pentru a crea relaţia.',
        'convertLeadHelp' => "Aici puteţi adăuga module pentru a converti ecranul aspectelor şi pentru a modifica setările celor existente.<br/><br/>
        <b>Ordonare:</b><br/>
        Contacte, Conturi şi Oprtunităţi trebuie să îşi păstreze ordinea. Puteţi reordona orice alt modul glisând rândul acestuia în tabel.<br/><br/>
        <b>Dependenţă:</b><br/>
        Dacă Oportunităţile sunt incluse, Conturile trebuie să fie obligatorii sau eliminate din aspectul convertit.<br/><br/>
        <b>Modul:</b> Numele modulului.<br/><br/>
        <b>Obligatoriu:</b> Modulele obligatorii trebuie să fie create sau selectate înainte de a putea converti clientul potenţial.<br/><br/>
        <b>Copiere date:</b> Dacă sunt bifate, câmpurile pentru clientul potenţial vor fi copiate în câmpuri cu acelaşi nume în înregistrările nou create.<br/><br/>
        <b>Ştergere:</b> Eliminaţi acest modul din aspectul convertit.<br/><br/>
        ",
        'editDropDownBtn' => 'Editaţi o listă verticală globală',
        'addDropDownBtn' => 'Adăugaţi o nouă listă verticală globală',
    ),
    'fieldsHelp'=>array(
        'default'=>'Initial',
    ),
    'relationshipsHelp'=>array(
        'default'=>'Initial',
        'addrelbtn'=>'mouse peste ajutor pentru a adăuga o relaţie..',
        'addRelationship'=>'<b>Relaţiile</b> pot fi create între modul şi alt modul personalizat sau un modul implementat.<br><br> Relaţiile sunt exprimate vizual prin subpanouri şi câmpuri conexe din înregistrările modulului.<br><br>Selectaţi una dintre următoarele relaţii <b>Tipuri</b> pentru modul:<br><br> <b>Unu la unu</b> - Înregistările ambelor module vor conţine câmpuri conexe.<br><br> <b>Unu la mai multe</b> - Înregistrarea Modulului principal va conţine un subpanou şi înregistrarea Modulului conex va conţine un câmp conex.<br><br> <b>Multe la multe</b> - Înregistrările ambelor module vor afişa subpanouri.<br><br> Selectaţi <b>Modulul conex</b> pentru relaţie. <br><br>Dacă tipul relaţiei presupune subpanouri, selectaţi vizualizarea subpanou pentru modulele corespunzătoare.<br><br> Faceţi clic pe <b>Salvare</b> pentru a crea relaţia.',
    ),
    'labelsHelp'=>array(
        'default'=> 'Initial',
        'saveBtn'=>'Ckic pt a salva toate modificarile',
        'publishBtn'=>'Faceţi clic pe <b>Salvare şi implementare</b> pentru a salva toate modificările şi a le activa.',
    ),
    'portalSync'=>array(
        'default' => 'Initial',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Initial',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Pentru a începe un proiect, faceţi clic pe <b>Pachet nou</b> pentru a crea un pachet nou care să adăpostească modulul (modulele) dvs. personalizat(e). <br/><br/>Fiecare pachet poate conţine unul sau mai multe module.<br/><br/>De exemplu, poate vreţi să creaţi un pachet care să conţină un modul personalizat care este conex modulului Conturi standard. Sau poate doriţi să creaţi un pachet care conţine mai multe module noi care funcţionează împreună ca un proiect şi care sunt legate între ele, precum şi cu alte module ce există deja în aplicaţie.',
            'somepackages'=>'Un <b>pachet</b> acţionează ca un container pentru modulele personalizate, toate făcând dintr-un proiect. Pachetul poate conţine unul sau mai multe <b>module</b> personalizate care pot fi legate între ele sau legate de alte module din aplicaţie. <br/> <br/> După crearea unui pachet pentru proiect, puteţi crea module pentru pachet imediat sau puteţi reveni la Creatorul de module ulterior, pentru a finaliza proiectul. <br><br>Atunci când proiectul este finalizat, puteţi <b>Implementa</b> pachetul pentru a instala modulele personalizate în aplicaţie.',
            'afterSave'=>'Pachetul dvs. nou trebuie să conţină cel puţin un modul. Puteţi crea unul sau mai multe module personalizate pentru pachet.<br/><br/>Faceţi clic pe <b>Modul nou</b> pentru a crea un modul personalizat pentru acest pachet.<br/><br/> După ce creaţi cel puţin un modul, puteţi publica sau implementa pachetul pentru a-l face disponibil pentru instanţa dvs. şi/sau pentru instanţele altor utilizatori.<br/><br/> Pentru a implementa pachetul într-un pas din instanţa dvs. Sugar, faceţi clic pe <b>Implementare</b>.<br><br>Faceţi clic pe <b>Publicare</b> pentru a salva pachetul ca fişier .zip. După salvarea fişierul .zip în sistemul dvs., utilizaţi <b>Încărcătorul de module</b> pentru a încărca şi instala pachetul în instanţa dvs. Sugar.  <br/><br/>Puteţi distribui fişierul altor utilizatori penru a-l încărca şi instala în propriile lor instanţe Sugar.',
            'create'=>'Furnizaţi un Nume pentru pachetul. Numele pe care îl introduceţi trebuie să fie alfanumeric şi nu conţina spaţii. (Exemplu: HR_Management)<br /><br />Puteţi furniza informaţii Autor şi Descriere pentru pachet.<br /><br />Faceţi clic pe Salvare pentru a crea pachet.',
            ),
    'main'=>array(
        'welcome'=>'Utilizaţi <b>Instrumentele pentru dezvoltatori</b> pentru a crea şi administra câmpuri şi module standard şi personalizate. <br/><br/>Pentru a administra modulele în aplicaţie, faceţi clic pe <b>Studio</b>. <br/><br/>Pentru a crea module personalizate, faceţi clic pe <b>Creator de module</b>.',
        'studioWelcome'=>'Toate modulele instalate momentan, inclusiv obiectele pe bază de module standard şi personalizate pot fi personalizate în Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Deoarece pachetul curent conţine cel puţin un modul, puteţi <b>Implementa</b> modulele în pachetul din instanţa dvs. Sugar sau puteţi <b>Publica</b> pachetul ce urmează să fie instalat în instanţa Sugar curentă sau în altă instanţă folosind <b>Încărcătorul de module</b>.<br/><br/>Pentru a instala pachetul direct în instanţa dvs. Sugar, faceţi clic pe <b>Implementare</b>.<br><br>Pentru a crea un fişier .zip pentru pachet care poate fi încărcat şi instalat în instanţa Sugar curentă şi în alte instanţe folosind <b>Încărcătorul de module</b>, faceţi clic pe <b>Publicare</b>.<br/><br/> Puteţi construi modulele pentru acest pachet în etape şi puteţi publica sau implementa când sunteţi gata să o faceţi. <br/><br/>După publicarea sau implementarea unui pachet, puteţi face modificări asupra proprietăţilor pachetului şi puteţi personaliza suplimentar modulele.  Apoi republicaţi sau reimplementaţi pachetul pentru a aplica modificările." ,
        'editView'=> 'Aici puteţi edita câmpurile existente. Puteţi elimina oricare dintre câmpurile existente sau puteţi adăuga câmpuri disponibile în panoul din stânga.',
        'create'=>'Furnizaţi un Nume pentru pachetul. Numele pe care îl introduceţi trebuie să fie alfanumeric şi nu conţina spaţii. (Exemplu: HR_Management)<br /><br />Puteţi furniza informaţii Autor şi Descriere pentru pachet.<br /><br />Faceţi clic pe Salvare pentru a crea pachet.',
        'afterSave'=>'Personalizaţi modulul pentru a se potrivi nevoilor dvs., editând şi creând câmpuri, stabilind relaţii cu alte module şi aranjând câmpurile din aspecte.<br/><br/>Pentru a vedea câmpurile model şi a administra câmpurile personalizate din modul, faceţi clic pe <b>Vizualizare câmpuri</b>.<br/><br/>Pentru a crea şi a administra relaţiile dintre modul şi alte module, indiferent că este vorba de module existente deja în aplicaţie sau de alte module din acelaşi pachet, faceţi clic pe <b>Vizualizare relaţii</b>.<br/><br/>Pentru a edita aspectele modulului, faceţi clic pe <b>Vizualizare aspecte</b>. Puteţi modifica aspectele Vizualizare detalii, Vizualizare editare şi Vizualizare listă pentru modul exact la fel ca în cazul modulelor deja existente în aplicaţia din Studio.<br/><br/> Pentru a crea un modul cu aceleaşi proprietăţi ca şi modulul curent, faceţi clic pe <b>Dublare</b>.  Puteţi personaliza suplimentar noul modul.',
        'viewfields'=>'Câmpurile din modul pot fi personalizate pentru a se potrivi nevoilor dvs.<br/><br/>Nu puteţi şterge câmpurile standard, dar le puteţi elimina din aspectele corespunzătoare din paginile Aspecte. <br/><br/>Puteţi crea rapid câmpuri noi care au proprietăţi similare cu câmpurile existente, făcând clic pe <b>Clonare</b> în formularul <b>Proprietăţi</b>.  Introduceţi proprietăţile noi şi apoi faceţi clic pe <b>Salvare</b>.<br/><br/>Se recomandă să setaţi toate proprietăţile pentru câmpurile standard şi câmpurile personalizate înainte de a publica şi instala pachetul care conţine modulul personalizat.',
        'viewrelationships'=>'Puteţi crea relaţii de tip multe-la-multe între modulul curent şi alte module din pachet şi/sau între modulul curent şi modulele deja instalate în aplicaţie.<br><br> Pentru a crea relaţii de tip una-la-multe şi una-la-una, creaţi câmpurile <b>Asociere</b> şi <b>Asociere flexibilă</b> pentru module.',
        'viewlayouts'=>'Puteţi controla ce câmpuri sunt disponibile pentru capturarea datelor în <b>Vizualizare editare</b>.  Puteţi controla, de asemenea, ce date se afişează în <b>Vizualizare detalii</b>.  Vizualizările nu trebuie să se potrivească. <br/><br/>Formularul Creare rapidă este afişat când se face clic pe <b>Creare</b> în subpanoul unui modul. În mod implicit, aspectul formularului <b>Creare rapidă</b> este identic cu cel al aspectului implicit <b>Vizualizare editare</b>. Puteţi personaliza formularul Creare rapidă astfel încât să conţină câmpuri mai puţine şi/sau diferite decât aspectul Vizualizare editare. <br><br>Puteţi determina securitatea modulului folosind personalizarea aspectului împreună cu <b>Management roluri</b>.<br><br>',
        'existingModule' =>'După ce creaţi şi personalizaţi acest modul, puteţi crea module suplimentare sau puteţi reveni la pachet pentru comanda de <b>Publicare</b> sau <b>Implementare</b> a pachetului.<br><br>Pentru a crea module suplimentare, faceţi clic pe <b>Dublare</b> pentru a crea un modul cu aceleaşi proprietăţi ca şi modulul curent sau navigaţi înapoi la pachet şi faceţi clic pe <b>Modul nou</b>.<br><br> Dacă sunteţi gata să executaţi comanda <b>Publicare</b> sau <b>Implementare</b> a pachetului care conţine acest modul, navigaţi înapoi la pachet pentru a efectua aceste funcţii. Puteţi publica şi implementa pachete care conţin cel puţin un modul.',
        'labels'=> 'Etichetele câmpurilor standard, precum şi ale câmpurilor personalizate pot fi modificate.  Modificarea etichetelor câmpurilor nu va afecta datele stocate în câmpuri.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Există trei coloane afişate în partea stângă. Coloana „Implicite” conţine câmpuri care sunt afişate într-o listă în mod implicit, coloana „Disponibile” conţine câmpuri pe care un utilizator le poate alege pentru a crea o listă personalizată şi coloana „Ascunse” conţine câmpuri disponibile pentru dvs. în calitate de administrator, pe care le puteţi adăuga la coloanele implicite sau disponibile pentru a putea fi utilizate de utilizator, acestea fiind momentan dezactivate.',
        'savebtn'	=> 'Făcând clic pe <b>Salvare</b>, toate mdoificările vor fi salvate şi activate.',
        'Hidden' 	=> 'Ascuns',
        'Available' => 'In stoc',
        'Default'	=> 'Initial'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Proprietăţile şi acţiunile posibile pentru pachet apar aici.<br /><br />Puteţi modifica Numele, Autorul  şi Descrierea pachetului, precum şi  vizualiza şi personaliza toate modulele conţinute în pachet.<br /><br />Faceţi clic pe New Modulul pentru a crea un modul pentru acest pachet.<br /><br />Dacă pachetul conţine cel puţin un modul, aveţi posibilitatea să publicaţi şi Implementaţi pachet, precum şi Exporta personalizările făcute în pachet.',
        'savebtn'	=> 'Faceţi clic pe Salvare pentru a salva toate datele introduse în legătură cu pachetul.',
        'Hidden' 	=> 'Ascuns',
        'Default'	=> 'Initial'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Există două coloane afişate la stânga.Coloana din dreapta-mana, etichetate curent Examinare aspect sau aspect, este locul unde vă schimbaţi aspectul modulul.Coloana din stânga, intitulat Toolbox, conţine elemente utile şi instrumente pentru utilizarea la editarea aspect.<br /><br />În cazul în care zona de aspect este intitulat structura actuală, atunci sunt de lucru pe o copie a aspectului utilizat în prezent de modul de afişare.<br /><br />În cazul în care este intitulat Examinare aspect atunci sunt de lucru pe o copie a creat mai devreme de un clic pe butonul Save, care ar fi fost deja schimbat de la versiunea văzut de utilizatorii acestui modul.',
        'saveBtn'	=> 'Un clic pe acest buton salvează aspectul, astfel încât să vă puteţi păstra modificările. Când reveniţi la acest modul, veţi porni de la acest aspect modificat. Totuşi, aspectul nu va fi văzut de utilizatorii modulului până când nu faceţi clic pe butonul Salvare şi Publicare.',
        'publishBtn'=> 'Faceţi clic pe acest buton pentru a implementa aspectul. Aceasta înseamnă că acest aspect va fi văzut imediat de utilizatorii acestui modul.',
        'toolbox'	=> 'Caseta de instrumente conţine o varietate de funcţii utile pentru editarea aspectelor, inclusiv o zonă pentru gunoi, un set de elemente suplimentare şi un set de câmpuri disponibile. Oricare dintre acestea pot fi glisate şi fixate pe aspect.',
        'panels'	=> 'Această zonă arată cum va arăta aspect pentru utilizatorii de acest modul atunci când este depolyed.<br /><br />Puteţi repoziţiona elemente, cum ar fi câmpurile, rânduri şi panouri prin glisare şi fixare a acestora; şterge elemente prin glisarea şi fixarea lor pe zona de gunoi din caseta de instrumente, sau adăuga elemente noi, trăgându-le de la set de instrumente şi fixarea acestora pe aspectul înpoziţia dorită.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Există două coloane afişate la stânga.Coloana din dreapta-mana, etichetate curent Examinare aspect sau aspect, este locul unde vă schimbaţi aspectul modulul.Coloana din stânga, intitulat Toolbox, conţine elemente utile şi instrumente pentru utilizarea la editarea aspect.<br /><br />În cazul în care zona de aspect este intitulat structura actuală, atunci sunt de lucru pe o copie a aspectului utilizat în prezent de modul de afişare.<br /><br />În cazul în care este intitulat Examinare aspect atunci sunt de lucru pe o copie a creat mai devreme de un clic pe butonul Save, care ar fi fost deja schimbat de la versiunea văzut de utilizatorii acestui modul.',
        'dropdownaddbtn'=> 'Apasand acest buton se adaugă un element nou la verticală.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Personalizările făcute în Studio în acest exemplu, pot fi ambalate şi implementate într-o altă instanţă.<br /><br />Furnizaţi o numele pachetului. Puteţi furniza Autor şi informaţii de descriere pentru pachet.<br /><br />Selectaţi modul (e) care conţin personalizări de a exporta. (Numai modulele care conţin Personalizarea va apărea pentru tine de a selecta.)<br /><br />Faceţi clic pe Export pentru a crea un fişier. Zip pentru pachetul care conţine particularizări.Fişier. Zip poate fi încărcat într-un alt exemplu, prin Loade Modulul',
        'exportCustomBtn'=>'Faceţi clic pe Export pentru a crea un fişier. Zip pentru pachetul care conţine particularizări pe care doresc să exporte.',
        'name'=>'Specifica Nume',
        'author'=>'autor<br /><br />Aceasta este autorul care este afişat în timpul instalării ca numele entităţii care a creat pachetul.<br /><br />Autorul ar putea fi un individ sau o companie.',
        'description'=>'Acest şablon este folosit pentru a trimite utilizatorului un link să faceţi clic pentru a reseta parola pentru contul de utilizator.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bun venit la zona <b>Instrumente pentru dezvoltatori</b1>. <br/><br/>Utilizaţi instrumentele din această zonă pentru a crea şi gestiona câmpurile şi modulele standard şi personalizate.',
        'studioBtn'	=> 'Utilizaţi <b>Studio</b> pentru a personaliza modulele instalate schimbând aranjarea câmpurilor, selectând ce câmpuri sunt disponibile şi creând câmpuri de date personalizate.',
        'mbBtn'		=> 'Utilizaţi <b>Creator de module</b> pentru a crea module noi.',
        'appBtn' 	=> 'Utilizaţi modul Aplicaţie pentru a personaliza diverse proprietăţi ale programului, cum ar fi câte rapoarte TPS sunt afişate pe pagina de pornire',
        'backBtn'	=> 'Intoarcete la pasul anterior',
        'studioHelp'=> 'Utilizaţi <b>Studio</b> pentru a personaliza modulele instalate.',
        'moduleBtn'	=> 'Faceţi clic pentru a edita acest modul.',
        'moduleHelp'=> 'Selectaţi componenta modulului pe care doriţi să o editaţi',
        'fieldsBtn'	=> 'Editaţi ce informaţii sunt stocate în modul controlând <b>Câmpuri</b> în modul.<br/><br/>Puteţi edita şi crea câmpuri personalizate aici.',
        'layoutsBtn'=> 'Personalizaţi <b>Aspecte</b> în vizualizările Editare, Detalii, Listă şi căutare.',
        'subpanelBtn'=> 'Editaţi ce informaţii sunt afişate în subpanourile acestui modul.',
        'layoutsHelp'=> 'Selectaţi un <b>Aspect de editat</b>.<br/<br/>Pentru a modifica aspectul care conţine câmpuri de date pentru introducerea datelor, faceţi clic pe <b>Vizualizare editare</b>.<br/><br/>Pentru a modifica aspectul care afişează datele introduse în câmpuri în Vizualizare editare, faceţi clic pe <b>Vizualizare editare</b>.<br/><br/>Pentru a modifica coloanele care apar în lista implicită, faceţi clic pe <b>Vizualizare listă</b>.<br/><br/>Pentru a modifica aspectele formularelor de căutare de bază şi avansate, faceţi clic pe <b>Căutare</b>.',
        'subpanelHelp'=> 'Selectaţi un <b>Subpanou</b> de editat.',
        'searchHelp' => 'Selectţi un aspect <b>Căutare</b> de editat.',
        'labelsBtn'	=> 'Editaţi <b>Etichetele</b> de afişat pentru valorile din acest modul.',
        'newPackage'=>'Faceţi clic pe <b>Pachet nou</b> pentru a crea un pachet nou.',
        'mbHelp'    => '<b>Bun venit la Creatorul de module.</b><br/><br/>Utilizaţi <b>Creatorul de module</b> pentru a crea pachete ce conţin module personalizate pe bază de obiecte standard sau personalizate. <br/><br/>Pentru a începe, faceţi clic pe <b>Pachet nou</b> pentru a crea un pachet nou sau selectaţi un pachet de editat.<br/><br/> Un <b>pachet</b> acţionează ca un container pentru module personalizate, toate făcând parte dintr-un proiect. Pachetul poate conţine unul sau mai multe module personalizate care pot fi asociate între ele sau cu module din aplicaţie. <br/><br/>Exemple: Poate doriţi să creaţi un pachet care să conţină un modul personalizat care este asociat modulului standard Conturi. Sau poate doriţi să creaţi un pachet care să conţină mai multe module noi care funcţionează împreună ca un pachet şi care sunt asociate între ele şi cu module din aplicaţie.',
        'exportBtn' => 'Faceţi clic pe <b>Export personalizări</b> pentru a crea un pachet care conţine personalizări efectuate în Studio pentru anumite module.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor liste verticale',

//ASSISTANT
'LBL_AS_SHOW' => 'Arata asistent in viitor',
'LBL_AS_IGNORE' => 'Ignora asistentul in viitor',
'LBL_AS_SAYS' => 'Asistentul Spune:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Creator de module',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor liste verticale',
'LBL_EDIT_DROPDOWN'=>'Renunta la editare',
'LBL_DEVELOPER_TOOLS' => 'Uneltele dezvoltatorului',
'LBL_SUGARPORTAL' => 'Editorul de portal Sugar',
'LBL_SYNCPORTAL' => 'Sincronizeaza Portal',
'LBL_PACKAGE_LIST' => 'Lista Colete',
'LBL_HOME' => 'Acasa',
'LBL_NONE'=>'Niciunul',
'LBL_DEPLOYE_COMPLETE'=>'Implementare completa',
'LBL_DEPLOY_FAILED'   =>'O eroare a apărut în timpul procesului de implementare, pachetul dvs. nu poate fi instalat corect',
'LBL_ADD_FIELDS'=>'Adăugaţi câmpuri personalizate',
'LBL_AVAILABLE_SUBPANELS'=>'Subpanouri Disponibile',
'LBL_ADVANCED'=>'Avansat',
'LBL_ADVANCED_SEARCH'=>'Cautare Avansata',
'LBL_BASIC'=>'Elementar',
'LBL_BASIC_SEARCH'=>'Cautare Elementara',
'LBL_CURRENT_LAYOUT'=>'Plan general',
'LBL_CURRENCY' => 'Moneda',
'LBL_CUSTOM' => 'Personalizat',
'LBL_DASHLET'=>'Tablou Sugar',
'LBL_DASHLETLISTVIEW'=>'Vedere Lista Sugar Dashlet',
'LBL_DASHLETSEARCH'=>'Cautare Sugar Dashlet',
'LBL_POPUP'=>'Vezi pop-up',
'LBL_POPUPLIST'=>'Vedere Lista pop-up',
'LBL_POPUPLISTVIEW'=>'Vedere Lista pop-up',
'LBL_POPUPSEARCH'=>'Cautare Pop-up',
'LBL_DASHLETSEARCHVIEW'=>'Cautare Sugar Dashlet',
'LBL_DISPLAY_HTML'=>'Arata codul HTML',
'LBL_DETAILVIEW'=>'Vedere Detaliata',
'LBL_DROP_HERE' => '[Lasa sa cada aici]',
'LBL_EDIT'=>'Editeaza',
'LBL_EDIT_LAYOUT'=>'Editeaza Plan General',
'LBL_EDIT_ROWS'=>'Editeaza Randuri',
'LBL_EDIT_COLUMNS'=>'Editeaza Coloane',
'LBL_EDIT_LABELS'=>'Editeaza Etichete',
'LBL_EDIT_PORTAL'=>'Editeaza Portal Pentru',
'LBL_EDIT_FIELDS'=>'Editeaza Campuri',
'LBL_EDITVIEW'=>'Editeaza Vedere',
'LBL_FILTER_SEARCH' => "Căutare",
'LBL_FILLER'=>'(umplere)',
'LBL_FIELDS'=>'Campuri',
'LBL_FAILED_TO_SAVE' => 'A esuat in a Salva',
'LBL_FAILED_PUBLISHED' => 'A esuat in a Publica',
'LBL_HOMEPAGE_PREFIX' => 'Al meu',
'LBL_LAYOUT_PREVIEW'=>'Vedere Plan General',
'LBL_LAYOUTS'=>'Planuri Generale',
'LBL_LISTVIEW'=>'Vedere Lista',
'LBL_RECORDVIEW'=>'Vizualizare înregistrare',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Colet Nou',
'LBL_NEW_PANEL'=>'Panou Nou',
'LBL_NEW_ROW'=>'Rand Nou',
'LBL_PACKAGE_DELETED'=>'Colet Sters',
'LBL_PUBLISHING' => 'In curs de Publicare ...',
'LBL_PUBLISHED' => 'Publicat',
'LBL_SELECT_FILE'=> 'Selecteaza fisier',
'LBL_SAVE_LAYOUT'=> 'Salveaza Plan General',
'LBL_SELECT_A_SUBPANEL' => 'Selecteaza un Subpanou',
'LBL_SELECT_SUBPANEL' => 'Selecteaza Subpanou',
'LBL_SUBPANELS' => 'Subpanouri',
'LBL_SUBPANEL' => 'Subpanou',
'LBL_SUBPANEL_TITLE' => 'Titlu:',
'LBL_SEARCH_FORMS' => 'Cauta',
'LBL_STAGING_AREA' => 'Spaţiul de aşteptare (glisaţi şi fixaţi elementele aici)',
'LBL_SUGAR_FIELDS_STAGE' => 'Domenii Sugar (faceţi clic pe elemente pentru a adăuga la zona de aşteptare)',
'LBL_SUGAR_BIN_STAGE' => 'Lada de gunoi Sugar (faceţi clic pe elemente pentru a adăuga la zona de aşteptare)',
'LBL_TOOLBOX' => 'Cutie cu instrumente',
'LBL_VIEW_SUGAR_FIELDS' => 'Vezi Campuri Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Vezi lada cu gunoi Sugar',
'LBL_QUICKCREATE' => 'Creare Rapida',
'LBL_EDIT_DROPDOWNS' => 'Editeaza Abandon Global',
'LBL_ADD_DROPDOWN' => 'Adauga un nou Abandon Global',
'LBL_BLANK' => '-alb-',
'LBL_TAB_ORDER' => 'Ordinea Filelor',
'LBL_TAB_PANELS' => 'Arata panourile ca si file',
'LBL_TAB_PANELS_HELP' => 'Afişează fiecare panou ca fila proprie, in loc de a avea pe toate acestea intr-un singur ecran',
'LBL_TABDEF_TYPE' => 'Tip vedere:',
'LBL_TABDEF_TYPE_HELP' => 'Selectaţi modul în care această secţiune trebuie să fie afişata. Această opţiune are efect numai dacă aţi activat file pe acest punct de vedere.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Filă',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panou',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Selectaţi Panou pt a avea aceast afişaj al panoului din punctul de vedere al aspectului. Selectaţi fila de a avea acest panou afişat într-o filă separată în cadrul aspect. Când Tab este specificat pentru un panou, panouri ulterioare stabilite pentru a afişa în Panoul va fi afişat în fila.<br />O nouă filă va fi pornit pentru a panoului următor pentru care Tab este selectat. Dacă Tab este selectat pentru un panou de sub panou în primul rând, primul panou va fi necesar un Tab.',
'LBL_TABDEF_COLLAPSE' => 'Restrângere',
'LBL_TABDEF_COLLAPSE_HELP' => 'Selectaţi pentru a face starea implicită a acestui panou prăbuşit.',
'LBL_DROPDOWN_TITLE_NAME' => 'Nume',
'LBL_DROPDOWN_LANGUAGE' => 'Limba',
'LBL_DROPDOWN_ITEMS' => 'Listeaza elemente',
'LBL_DROPDOWN_ITEM_NAME' => 'Numele elementului',
'LBL_DROPDOWN_ITEM_LABEL' => 'Arata Eticheta',
'LBL_SYNC_TO_DETAILVIEW' => 'Sincronizare la DetailsView',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Selectati aceasta optiune pt a sincroniza EditView cu DetailView.Campurile din EitView vor fi sincronizate si salvate automat in DetailView, facand clic pe $#39;$#39;Salvati$#39;$#39; sau $#39;$#39;Salvati si Implementati$#39;$#39; in EditView.<br />Nu dse pot face modificari in DetailView.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Acest DetailView este in sincronizare cu EditView-ul potrivit<br />Domeniile de plasament din DetailView reflecta domeniile din EditView.<br />Modificări la DetailsView nu pot fi salvate sau desfăşurate în cadrul acestei pagini. Faceti modificarile in EditView.',
'LBL_COPY_FROM' => 'Copiere de la',
'LBL_COPY_FROM_EDITVIEW' => 'Copiati de la EditView',
'LBL_DROPDOWN_BLANK_WARNING' => 'Valorile sunt necesare atât pentru Numele Elementului cat şi pt Label Display. Pentru a adăuga un element necompletat, faceţi clic pe Adăugare fără a introduce nici o valoare pentru numele elementului şi Label Display.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Cheia există deja în listă',
'LBL_DROPDOWN_LIST_EMPTY' => 'Lista trebuie să conţină cel puţin un elemente activat',
'LBL_NO_SAVE_ACTION' => 'Nu s-a putut găsi acţiunea de salvare pentru această vizualizare.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: document format incorect',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Indică un câmp mixt. Un câmp mixt reprezintă un ansamblu de câmpuri individuale. De exemplu, "Adresă" este un câmp mixt care conţine "Stradă", "Oraş", "Cod poştal","Stat" şi"Ţară".<br><br>Faceţi dublu clic pe un câmp mixt pentru a vedea ce câmpuri conţine.',
'LBL_COMBO_FIELD_CONTAINS' => 'conţine',

'LBL_WIRELESSLAYOUTS'=>'Planuri Mobile',
'LBL_WIRELESSEDITVIEW'=>'EditareVizualizare Mobila',
'LBL_WIRELESSDETAILVIEW'=>'VizualizareDetalii Mobila',
'LBL_WIRELESSLISTVIEW'=>'Vizualizare tip Lista Mobila',
'LBL_WIRELESSSEARCH'=>'Cautare Mobila',

'LBL_BTN_ADD_DEPENDENCY'=>'Adauga Dependenta',
'LBL_BTN_EDIT_FORMULA'=>'Modifica Formula',
'LBL_DEPENDENCY' => 'Dependenta',
'LBL_DEPENDANT' => 'In functie',
'LBL_CALCULATED' => 'Calculat',
'LBL_READ_ONLY' => 'Citeste doar',
'LBL_FORMULA_BUILDER' => 'Constructor de Formule',
'LBL_FORMULA_INVALID' => 'Formula Invalida',
'LBL_FORMULA_TYPE' => 'Formula trebuie să fie  tip',
'LBL_NO_FIELDS' => 'Nu s-au gasit campuri',
'LBL_NO_FUNCS' => 'Nu s-au gasit functii',
'LBL_SEARCH_FUNCS' => 'Cauta Functii...',
'LBL_SEARCH_FIELDS' => 'Cauta Campuri...',
'LBL_FORMULA' => 'Formulă',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Dependent',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Trageţi opţiuni din lista de pe partea stângă a opţiunilor disponibile în meniul vertical dependent la listele pe dreptul de a face aceste opţiuni disponibile atunci când este selectată opţiunea mamă. Dacă nu sunt produse în conformitate cu o opţiune de părinte, atunci când este selectată opţiunea mamă, vertical depinde nu va fi afişat.',
'LBL_AVAILABLE_OPTIONS' => 'Optiuni Valabile',
'LBL_PARENT_DROPDOWN' => 'Listă verticală părinte',
'LBL_VISIBILITY_EDITOR' => 'Vizibilitate Editor',
'LBL_ROLLUP' => 'Ruleaza in sus',
'LBL_RELATED_FIELD' => 'legate de Câmp',
'LBL_CONFIG_PORTAL_URL'=>'URL-ul la imaginea logo personalizata. Dimensiunile recomandate sunt 163 × logo 18 pixeli.',
'LBL_PORTAL_ROLE_DESC' => 'Nu șterge acest rol. Clienți Self-Service Rolul Portal este un rol de sistem generat ,creat în timpul procesului de activare Sugar Portal. Utilizați controale de acces în acest rol, pentru a permite și / sau dezactiva Bugs, carcase sau module bazei de cunoștințe în Portal de zahăr. Să nu modifice orice alte controale de acces pentru acest rol, pentru a evita comportamentul sistemului necunoscute și imprevizibile. În caz de ștergerea accidentală a acestui rol, recreate dezactivarea și activarea Sugar Portal',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Modul Primar',
'LBL_CUSTOM_RELATIONSHIPS' => '* relatie creata in Studio',
'LBL_RELATIONSHIPS'=>'Relatii',
'LBL_RELATIONSHIP_EDIT' => 'Modifica Relatie',
'LBL_REL_NAME' => 'Nume',
'LBL_REL_LABEL' => 'Eticheta',
'LBL_REL_TYPE' => 'Tip',
'LBL_RHS_MODULE'=>'Modul inrudit',
'LBL_NO_RELS' => 'Nu Sunt Relatii',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Conditie optionala' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Coloana',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Valoare',
'LBL_SUBPANEL_FROM'=>'Formular subpanou',
'LBL_RELATIONSHIP_ONLY'=>'Nu vor fi create elemente vizibile pentru aceasta relatie deoarece exista o relatie anterioara vizibila intre aceste doua module.',
'LBL_ONETOONE' => 'Una la Una',
'LBL_ONETOMANY' => 'Una la Mai Multe',
'LBL_MANYTOONE' => 'Mai multe la Una',
'LBL_MANYTOMANY' => 'Mai Multe la Mai Multe',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Alege o functie sau componenta.',
'LBL_QUESTION_MODULE1' => 'Alege un modul.',
'LBL_QUESTION_EDIT' => 'Alege un modul pentru modificare.',
'LBL_QUESTION_LAYOUT' => 'Selectati o schita pentru a fi editata.',
'LBL_QUESTION_SUBPANEL' => 'Selectati un subpanou pentru a fi editat.',
'LBL_QUESTION_SEARCH' => 'Selectati schita de cautare pentru a fi editata.',
'LBL_QUESTION_MODULE' => 'Alege o componenta a modului pentru modificare.',
'LBL_QUESTION_PACKAGE' => 'Alegeti un pachet pentru modificare sau creati un nou pachet',
'LBL_QUESTION_EDITOR' => 'Alege un instrument',
'LBL_QUESTION_DROPDOWN' => 'Selectati o lista derulanta pentru a fi editata, sau creati o noua lista derulanta.',
'LBL_QUESTION_DASHLET' => 'Selectati o schita de dashlet pentru a fi editata.',
'LBL_QUESTION_POPUP' => 'Selectionati o schita de popup pentru a fi editata.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Inrudit Cu',
'LBL_NAME'=>'Nume',
'LBL_LABELS'=>'Etichete',
'LBL_MASS_UPDATE'=>'Actualizare In Masa',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Valoare implicita',
'LBL_REQUIRED'=>'Necesar',
'LBL_DATA_TYPE'=>'Tip',
'LBL_HCUSTOM'=>'PERSONALIZAT',
'LBL_HDEFAULT'=>'IMPLICIT',
'LBL_LANGUAGE'=>'Limba',
'LBL_CUSTOM_FIELDS' => 'Camp creat in Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Editeaza Etichete',
'LBL_SECTION_PACKAGES' => 'Pachete',
'LBL_SECTION_PACKAGE' => 'Pachet',
'LBL_SECTION_MODULES' => 'Module',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'ListeCoboratoare',
'LBL_SECTION_PROPERTIES' => 'Proprietati',
'LBL_SECTION_DROPDOWNED' => 'Editeaza ListeCoboratorare',
'LBL_SECTION_HELP' => 'Ajutor',
'LBL_SECTION_ACTION' => 'Actiune',
'LBL_SECTION_MAIN' => 'Principal',
'LBL_SECTION_EDPANELLABEL' => 'Editeaza Eticheta Panoului',
'LBL_SECTION_FIELDEDITOR' => 'Modificare Domeniu',
'LBL_SECTION_DEPLOY' => 'Desfasoara',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Modifica Vizibiliate',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Initial',
'LBL_HIDDEN'=>'Ascuns',
'LBL_AVAILABLE'=>'Disponibil',
'LBL_LISTVIEW_DESCRIPTION'=>'Sunt trei columne afisate mai jos. Coloana Implicita contine campauri care sunt afisate in mod  implicit intr-o vizualizare tip lista. Coloanele Aditionale contin campuri pe care utilizatorul le poate folosi pentru a crea o vizualizare particularizata. Coloanele Disponibile afiseaza campuri disponibile pentru dumneavoastra in calitate de administrator',
'LBL_LISTVIEW_EDIT'=>'Editor Vizualizare tip Lista',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Previzualizare',
'LBL_MB_RESTORE'=>'Restaureaza',
'LBL_MB_DELETE'=>'Ștergere',
'LBL_MB_COMPARE'=>'Compara',
'LBL_MB_DEFAULT_LAYOUT'=>'Structura Implicita',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Adauga',
'LBL_BTN_SAVE'=>'Salveaza',
'LBL_BTN_SAVE_CHANGES'=>'Salvare modificari',
'LBL_BTN_DONT_SAVE'=>'Anuleaza Modificarile',
'LBL_BTN_CANCEL'=>'Anulare',
'LBL_BTN_CLOSE'=>'Inchide',
'LBL_BTN_SAVEPUBLISH'=>'Salveaza si Lanseaza',
'LBL_BTN_NEXT'=>'Urmatorul',
'LBL_BTN_BACK'=>'Inapoi',
'LBL_BTN_CLONE'=>'Cloneaza',
'LBL_BTN_COPY' => 'Copiere',
'LBL_BTN_COPY_FROM' => 'Copiere din',
'LBL_BTN_ADDCOLS'=>'Adauga Coloane',
'LBL_BTN_ADDROWS'=>'Adauga Randuri',
'LBL_BTN_ADDFIELD'=>'Adauga domeniu',
'LBL_BTN_ADDDROPDOWN'=>'Adauga Lista Derulanta',
'LBL_BTN_SORT_ASCENDING'=>'Sorteaza Crescator',
'LBL_BTN_SORT_DESCENDING'=>'Sorteaza Descrescator',
'LBL_BTN_EDLABELS'=>'Modifica Etichete',
'LBL_BTN_UNDO'=>'Anulare',
'LBL_BTN_REDO'=>'Refacere',
'LBL_BTN_ADDCUSTOMFIELD'=>'Adauga Domeniu Personalizat',
'LBL_BTN_EXPORT'=>'Exporta Particularizari',
'LBL_BTN_DUPLICATE'=>'Duplicat',
'LBL_BTN_PUBLISH'=>'Publica',
'LBL_BTN_DEPLOY'=>'Desfasoara',
'LBL_BTN_EXP'=>'Exporta',
'LBL_BTN_DELETE'=>'Ștergere',
'LBL_BTN_VIEW_LAYOUTS'=>'Vizualizeaza Structuri',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Vizualizare planuri generale mobile',
'LBL_BTN_VIEW_FIELDS'=>'Vizualizeaza Domenii',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Vizualizeaza Relatiile',
'LBL_BTN_ADD_RELATIONSHIP'=>'Adauga Relatie',
'LBL_BTN_RENAME_MODULE' => 'Schimba numele modulului',
'LBL_BTN_INSERT'=>'Introdu',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Eroare: Campul Exista Deja',
'ERROR_INVALID_KEY_VALUE'=> "Eroare: Valoare Cheie Invalida: [*]",
'ERROR_NO_HISTORY' => 'Nu s-au gasit fisiere istoric',
'ERROR_MINIMUM_FIELDS' => 'Structura trebuie sa contina cel putin un domeniu',
'ERROR_GENERIC_TITLE' => 'A aparut o eroare',
'ERROR_REQUIRED_FIELDS' => 'Sigur vreti sa continuati? Urmatoarele domenii necesare lipsesc din structura:',
'ERROR_ARE_YOU_SURE' => 'Sunteti sigur ca doriti sa continuati?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Urmatoarele campuri au valori calculate care nu vor fi recalculate in timp real in cadrul Vizualizarii Sugar CRM Editare Mobila:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Urmatoarele campuri au valori calculate care nu vor fi recalculate in timp real in cadrul Vizualizarii Sugar CRM Editare Mobila:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Urmatoarele module sunt dezactivate',
    'LBL_PORTAL_ENABLE_MODULES' => 'Dacă doriți să le activați în portal, vă rugăm să  le activați  aici.',
    'LBL_PORTAL_CONFIGURE' => 'Configurare Portal',
    'LBL_PORTAL_THEME' => 'Tema Portal',
    'LBL_PORTAL_ENABLE' => 'Activeaza',
    'LBL_PORTAL_SITE_URL' => 'Portalul este disponibil la adresa:',
    'LBL_PORTAL_APP_NAME' => 'Nume aplicaţie',
    'LBL_PORTAL_LOGO_URL' => 'URL siglă',
    'LBL_PORTAL_LIST_NUMBER' => 'Numarul de inregistrari afisate pe lista',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Numărul de câmpuri pentru a afișate pe Detail View',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Numărul de rezultate pentru a afișa pe Căutare globală',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Implicit alocat pentru noile înmatriculări portal',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Intrare Aspecte',
'LBL_SYNCP_WELCOME'=>'Va rugam introduceti URL-ul portalului pe care doriti sa-l actualizati.',
'LBL_SP_UPLOADSTYLE'=>'Selectati un stil de formular pentru a fi incarcat de pe computerul dumneavoastra.<br />Stilul de formular va fi implementat in Portalul Sugar urmatoarea data cand veti executa o sincronizare.',
'LBL_SP_UPLOADED'=> 'Incarcat',
'ERROR_SP_UPLOADED'=>'Asigurati-va ca incarcati un stil de formular de tip css.',
'LBL_SP_PREVIEW'=>'Aceasta este o previzualizare a felului in care Portalul Sugar va arata in cazul utilizarii stilului de formular.',
'LBL_PORTALSITE'=>'URL-ul portalului Sugar',
'LBL_PORTAL_GO'=>'Avanseaza',
'LBL_UP_STYLE_SHEET'=>'Incarcati Stilul de Formular',
'LBL_QUESTION_SUGAR_PORTAL' => 'Selectati o schita de Portal Sugar pentru editare.',
'LBL_QUESTION_PORTAL' => 'Selectati o schita de portal pentru editare.',
'LBL_SUGAR_PORTAL'=>'Editorul de Portal Sugar',
'LBL_USER_SELECT' => 'Alege utilizatorii',

//PORTAL PREVIEW
'LBL_CASES'=>'Cazuri',
'LBL_NEWSLETTERS'=>'Informari',
'LBL_BUG_TRACKER'=>'Tracker erori',
'LBL_MY_ACCOUNT'=>'Contul Meu',
'LBL_LOGOUT'=>'Delogare',
'LBL_CREATE_NEW'=>'Creează nou',
'LBL_LOW'=>'Scazut',
'LBL_MEDIUM'=>'Mediu',
'LBL_HIGH'=>'Ridicat',
'LBL_NUMBER'=>'Numar',
'LBL_PRIORITY'=>'Prioritate',
'LBL_SUBJECT'=>'Subiect',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Nume Pachet:',
'LBL_MODULE_NAME'=>'Nume Modul:',
'LBL_MODULE_NAME_SINGULAR' => 'Modul singular Nume:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Descriere:',
'LBL_KEY'=>'Cheie:',
'LBL_ADD_README'=>'Citeste-ma',
'LBL_MODULES'=>'Module',
'LBL_LAST_MODIFIED'=>'Ultima modificare',
'LBL_NEW_MODULE'=>'Modul nou',
'LBL_LABEL'=>'Eticheta',
'LBL_LABEL_TITLE'=>'Eticheta',
'LBL_SINGULAR_LABEL' => 'Etichetă unică',
'LBL_WIDTH'=>'Latime',
'LBL_PACKAGE'=>'Pachet:',
'LBL_TYPE'=>'Tip:',
'LBL_TEAM_SECURITY'=>'Securitatea Echipei',
'LBL_ASSIGNABLE'=>'Alocabil',
'LBL_PERSON'=>'Persoana',
'LBL_COMPANY'=>'Companie',
'LBL_ISSUE'=>'Problema',
'LBL_SALE'=>'Vanzare',
'LBL_FILE'=>'Fisiier',
'LBL_NAV_TAB'=>'Tab de Navigatie',
'LBL_CREATE'=>'Creează',
'LBL_LIST'=>'Lista',
'LBL_VIEW'=>'Vizualizare',
'LBL_LIST_VIEW'=>'Vizualizare tip Lista',
'LBL_HISTORY'=>'Vizualizare Istoric',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Restaurare aspect implicit',
'LBL_ACTIVITIES'=>'Activitati',
'LBL_SEARCH'=>'Cauta',
'LBL_NEW'=>'Nou',
'LBL_TYPE_BASIC'=>'bazic',
'LBL_TYPE_COMPANY'=>'companie',
'LBL_TYPE_PERSON'=>'persoana',
'LBL_TYPE_ISSUE'=>'problema',
'LBL_TYPE_SALE'=>'vanzare',
'LBL_TYPE_FILE'=>'fisier',
'LBL_RSUB'=>'Acesta este subpanoul care va fi afisat in modulul dumneavoastra',
'LBL_MSUB'=>'Acesta este subpanoul pe care modulul dumneavoastra il asigura modulului inrudit pentru afisare',
'LBL_MB_IMPORTABLE'=>'Permitere importuri',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'vizibil',
'LBL_VE_HIDDEN'=>'ascuns',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] a fost șters',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Exporta Particularizari',
'LBL_EC_NAME'=>'Nume Pachet:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Descriere:',
'LBL_EC_KEY'=>'Cheie:',
'LBL_EC_CHECKERROR'=>'Selectati un modul',
'LBL_EC_CUSTOMFIELD'=>'domenii personalizate',
'LBL_EC_CUSTOMLAYOUT'=>'schite particularizate',
'LBL_EC_CUSTOMDROPDOWN' => 'listă/e derulantă/e personalizată/e',
'LBL_EC_NOCUSTOM'=>'Nici un modul nu a fost personalizat.',
'LBL_EC_EXPORTBTN'=>'Exporta',
'LBL_MODULE_DEPLOYED' => 'Modulul a fost desfasurat.',
'LBL_UNDEFINED' => 'nedefinit',
'LBL_EC_CUSTOMLABEL'=>'etichetă/e personalizată/e',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Esuare in extragerea datelor',
'LBL_AJAX_TIME_DEPENDENT' => 'O actiune de durata este in progres. Asteptati si incercati din nou in cateva secunde.',
'LBL_AJAX_LOADING' => 'Incarcare...',
'LBL_AJAX_DELETING' => 'In curs de stergere...',
'LBL_AJAX_BUILDPROGRESS' => 'Construire In Progres...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Desfasurare in Progres...',
'LBL_AJAX_FIELD_EXISTS' =>'Numele domeniului pe care l-ati introdus exista deja. Introduceti alt nume de domeniu.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Sigur doriti sa scoateti acest pachet? Aceasta va sterge definitiv toate fisierele asociate cu acest pachet.',
'LBL_JS_REMOVE_MODULE' => 'Sigur doriti sa scoateti acest modul? Aceasta va sterge definitiv toate fisierele asociate cu acest modul.',
'LBL_JS_DEPLOY_PACKAGE' => 'Orice particularizari pe care le-ati efectuat in Studio vor fi suprascrise atunci cand modulul va fi re-desfasurat. Sunteti sigur ca doriti sa continuati?',

'LBL_DEPLOY_IN_PROGRESS' => 'Pachet in Desfasurare',
'LBL_JS_VALIDATE_NAME'=>'Nume - Trebuie sa fie alfanumeric, fara spatii si incepand cu o litera.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Cheia pachetului există deja',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Numele Pachetului există deja',
'LBL_JS_PACKAGE_NAME'=>'Nume pachet - trebuie să înceapă cu o literă şi poate consta numai din litere, cifre şi linii de subliniere. Nu se pot folosi spații sau alte caractere speciale.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Cheie - Trebuie să fie alfanumerică şi să înceapă cu o literă.',
'LBL_JS_VALIDATE_KEY'=>'Cheie - Trebuie sa fie alfanumerica',
'LBL_JS_VALIDATE_LABEL'=>'Introduceti o eticheta care va fi folosita ca Nume Afisat pentru acest modul.',
'LBL_JS_VALIDATE_TYPE'=>'Selectati tipul de modul pe care doriti sa-l construiti, din lista de mai sus',
'LBL_JS_VALIDATE_REL_NAME'=>'Nume - Trebuie sa fie alfanumeric, fara spatii',
'LBL_JS_VALIDATE_REL_LABEL'=>'Eticheta - va rugam sa adaugati o eticheta care va fi afisata deasupra subpanoului',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Sigur doriţi ştergerea acestui element din lista derulantă? Acest lucru poate afecta funcţionalitatea aplicaţiei.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Sigur doriţi să ştergeţi acest element din lista derulantă? Ştergerea etapelor Closed Won (Afacere încheiată cu succes) sau Closed Lost (Afacere încheiată în pierdere) va duce la funcţionarea necorespunzătoare a modulului Previzionare',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Sigur doriţi să ştergeţi starea Vânzări noi? Ştergerea acestei stări va duce la funcţionarea necorespunzătoare a fluxului de lucru Element de venit din modulul Oportunităţi.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Sigur doriţi să ştergeţi starea Vânzări în desfăşurare? Ştergerea acestei stări va duce la funcţionarea necorespunzătoare a fluxului de lucru Element de venit din modulul Oportunităţi.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Sigur doriţi ştergerea etapei de vânzări Closed Won (Afacere încheiată cu succes)? Ştergerea acestei etape va duce la funcţionarea necorespunzătoare a modulului Previzionare',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Sigur doriţi ştergerea etapei de vânzări Closed Lost (Afacere încheiată în pierdere)? Ştergerea acestei etape va duce la funcţionarea necorespunzătoare a modulului Previzionare',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Sigur stergeti aceasta relatie?',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Aceasta va determina ca relatia sa devina permanenta. Sunteti sigur ca doriti sa desfasurati aceasta  relatie?',
'LBL_CONFIRM_DONT_SAVE' => 'S-au facut modificari de cand ati salvat ultima oara, doriti sa salvati?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Salvati Modificari?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Datele pot fi trunchiate si proecesul nu este reversibil, sunteti sigur ca doriti sa continuati?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Selectati tipul de date potrivit in functie de tipul de date care vor fi introduse in camp.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Configuraţi câmpul pentru a oferi posibilitatea de căutare completă a textelor.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Amplificarea este procesul de îmbunătăţire a relevanţei câmpurilor unei înregistrări.<br />Câmpurile cu un nivel mai mare de amplificare vor avea o importanţă mai mare când se efectuează căutarea. Când se efectuează o căutare, înregistrările care se potrivesc şi care conţin câmpuri cu o importanţă mai mare vor apărea mai sus în rezultatele de căutare.<br />Valoarea implicită este 1.0, ceea ce înseamnă o amplificare neutră. Pentru a aplica o amplificare pozitivă, se acceptă orice valoare flotantă mai mare ca 1. Pentru o amplificare negativă, utilizaţi valori mai mici ca 1. De exemplu, o valoare de 1,35 va amplifica pozitiv un câmp cu 135%. Utilizarea unei valori de 0,60 va aplica o amplificare negativă.<br />Reţineţi că în versiunile anterioare era necesară efectuarea unei reindexări complete a căutărilor. Acest lucru nu mai este necesar.',
'LBL_POPHELP_IMPORTABLE'=>'Da: Campul va fi inclus intr-o operatiune de import.<br />No: Campul nu va fi inclus intr-un import.<br />Necesar: O valoare pentru acest camp trebuie asigurata in orice import.',
'LBL_POPHELP_PII'=>'Acest câmp va fi marcat automat pentru audit și va fi disponibil în vizualizarea Informații personale.<br>Câmpurile cu informații personale pot fi, de asemenea, șterse definitiv, atunci când înregistrarea este legată de solicitarea de ștergere pentru Confidențialitatea datelor.<br>Ștergerea se realizează prin modulul Confidențialitate date și poate fi executată de către administratorii sau utilizatorii cu rol de Manager de confidențialitate a datelor.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Introduceti un numar pentru Latime, masurat in pixeli.<br />Imaginea incarcata va fi scalata la aceasta Latime.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Introduceti un numar pentru Inaltime, masurat in pixeli.<br />Imaginea incarcata va fi scalata la aceasta Inaltime.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Selectaţi pentru a utiliza acest câmp, atunci când căutaţi pentru înregistrările utilizând  Cautare Globala pe acest modul.',
//Revert Module labels
'LBL_RESET' => 'Reseteaza',
'LBL_RESET_MODULE' => 'Reseteaza Modulul',
'LBL_REMOVE_CUSTOM' => 'Inlatura Particularizarile',
'LBL_CLEAR_RELATIONSHIPS' => 'Inlatura Relatiile',
'LBL_RESET_LABELS' => 'Reseteaza Etichetele',
'LBL_RESET_LAYOUTS' => 'Resetare aspecte',
'LBL_REMOVE_FIELDS' => 'Inlatura Campurile Particularizate',
'LBL_CLEAR_EXTENSIONS' => 'Inlatura Extensiile',

'LBL_HISTORY_TIMESTAMP' => 'Secventa de Timp',
'LBL_HISTORY_TITLE' => 'istoric',

'fieldTypes' => array(
                'varchar'=>'CampText',
                'int'=>'Integrala',
                'float'=>'Baliza',
                'bool'=>'Casuta',
                'enum'=>'Derulant',
                'multienum' => 'SelectionareMultipla',
                'date'=>'Data',
                'phone' => 'Telefon',
                'currency' => 'Moneda',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Relationat',
                'address' => 'Adresa',
                'text' => 'SuprafataText',
                'url' => 'URL',
                'iframe' => 'ICadru',
                'image' => 'Imagine',
                'encrypt'=>'Criptare',
                'datetimecombo' =>'Datatimp',
                'decimal'=>'Zecimal',
),
'labelTypes' => array(
    "" => "Format Implicit e-mail",
    "all" => "Toate care au legatura",
),

'parent' => 'Relationare Flexibila',

'LBL_ILLEGAL_FIELD_VALUE' =>"Tasta derulantă nu poate conține oferte.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Ati selectionat acest item pentru a fi inlaturat din lista derulanta. Orice camp derulant care utilizeaza acest item ca pe o valoare, nu va mai afisa valoarea si aceasta nu va mai putea fi selectionata din campurile derulante. Sunteti sigur ca doriti sa continuati?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Toate modulele',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (relaţionat {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Copiere din plan general',
);
