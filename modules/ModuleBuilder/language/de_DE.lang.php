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
    'LBL_LOADING' => 'Laden. Bitte warten...' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Optionen ausblenden' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Löschen' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Powered By SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Rolle',
'help'=>array(
    'package'=>array(
            'create'=>'Bitte dem Paket einen <b>Name geben>/b>. Der Name muss alphanumerisch sein und darf keine Leerzeichen haben (z. B. HR_Management).<br/><br/>Sie können einen <b>Autor</b> und eine <b>Beschreibung</b> für dieses Paket definieren.<br/><br/><b>Speichern</b> auswählen, um das Paket zu erstellen.',
            'modify'=>'</b>Die Eigenschaften und möglichen Aktionen für dieses <b>Paket</b> erscheinen hier.<br><br>Sie können <b>Name, Autor</b> und <b>Beschreibung</b> für das Paket ändern, und alle Module im Paket anzeigen bzw. ändern.<br><br><b>Neues Modul</b> auswählen, um ein neues Modul für das Paket zu erstellen.<br><br>Wenn das Paket mindestens ein Modul enthält, kann es <b>publiziert</b> oder <b>angewendet</b> werden bzw. die daran vorgenommenen Anpassungen können <b>exportiert</b> werden.',
            'name'=>'Dies ist der <b>Name</b> des aktuellen Pakets. <br><br/>Der Name muss alphanumerisch sein, muss mit einem Buchstaben beginnen und darf keine Leerzeichen oder Sonderzeichen enthalten. ( z. B. HR_Management)',
            'author'=>'Dies ist der <b>Autor</b> des Pakets, der während der Installation angezeigt wird.<br><br>Der Autor kann entweder einen Person oder eine Firma sein.',
            'description'=>'Dies ist die <b>Beschreibung</b> des Pakets, die bei der Installation angezeigt wird.',
            'publishbtn'=>'<b>Publizieren</b> speichert alle Daten und generiert eine .zip-Datei als installierbare Version des Pakets. <br><br>Verwenden Sie den <b>Modul-Lader</b>, um die .zip-Datei hochzuladen und das Paket zu installieren.',
            'deploybtn'=>'<b>Anwenden</b> speichert alle Daten und installiert das Paket inklusive aller Module in der aktuellen Instanz.',
            'duplicatebtn'=>'Klicken Sie auf <b>Duplizieren</b>, um den Inhalt des Pakets in ein neues Paket zu kopieren, und dieses daraufhin anzuzeigen. <br/><br/>Für das neue Paket wird automatisch durch Hinzufügen einer Zahl am Ende des Namens ein neuer Name generiert. Sie können dem Paket auch selbst benennen, indem Sie einen neuen <b>Namen</b> eingeben und dann auf <b>Speichern</b> klicken.',
            'exportbtn'=>'Klicken Sie auf <b>Exportieren</b>, um eine .zip-Datei zu erstellen, welche die benutzerdefinierten Anpassungen des Pakets enthält. <br><br>Diese Datei ist keine installierbare Version des Pakets. <br><br>Verwenden Sie den <b>Modul-Lader</b>, um diese Datei zu importieren und das Paket einschließlich der Anpassungen im Modul-Ersteller anzuzeigen.',
            'deletebtn'=>'Klicken Sie auf <b>Löschen</b>, um dieses Paket und alle damit zusammenhängenden Dateien zu löschen.',
            'savebtn'=>'Klicken Sie auf<b>Speichern</b>, um alle im Zusammenhang mit dem Paket eingegebenen Daten zu speichern.',
            'existing_module'=>'Klicken Sie auf das Symbol <b>Modul</b>, um die Eigenschaften zu bearbeiten und Felder, Beziehungen und Layouts, die mit diesem Modul zugeordnet sind, anzupassen.',
            'new_module'=>'Klicken Sie auf<b>Neues Modul</b>, um für dieses Paket ein neues Modul zu erstellen.',
            'key'=>'Dieser 5-stellige, alphanumerische <b>Schlüssel</b> wird als Präfix für alle Verzeichnisse, Klassennamen und Datenbank-Tabellen für alle Module im aktuellen Paket verwendet werden. <br><br>Das Ziel davon ist es, eindeutige Tabellennamen zu erreichen.',
            'readme'=>'Klicken Sie hier, um diesem Paket einen <b>Liesmich (Readme)</b>-Text hinzuzufügen.<br><br>Dieser Text wird zum Zeitpunkt der Installation zur Verfügung stehen.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Geben Sie dem Modul einen <b>Namen</b>. Die <b>Bezeichnung</b>, das sie ihm geben, wird in der Registerkarte "Navigation" erscheinen. <br/><br/>Legen Sie fest, dass für dieses Modul eine Registerkarte angezeigt wird, indem Sie das Kästchen <b>Registerkarte "Navigation"</b> ankreuzen.<br/><br/>Kreuzen Sie auch das Kästchen <b>Team-Sicherheit</b> an, um innerhalb des Datensatzes des Moduls ein Feld zur Auswahl des Teams zu haben. <br/><br/>Wählen Sie dann den Modultyp, den Sie erstellen möchten. <br/><br/>Wählen Sie einen Vorlagentyp. Jede Vorlage enthält eine Reihe spezifischer Felder sowie vordefinierte Layouts, die Sie als Basis für Ihr Modul verwenden können. <br/><br/>Klicken Sie auf <b>Speichern</b>, um das Modul zu erstellen.',
        'modify'=>'Sie können die Eigenschaften des Moduls verändern oder die<b>Felder</b>, <b>Beziehungen</b> und <b>Layouts</b>, die mit dem Modul zusammenhängen, anpassen.',
        'importable'=>'Wenn Sie das Kästchen <b>Importierbar</b> ankreuzen,  wird das Importieren für dieses Modul aktiviert.<br><br>In den Schnellzugriffen des Moduls wird ein Link zum Import-Assistenten erscheinen. Dieser ermöglicht das Importieren von Daten aus externen Quellen in das benutzerdefinierte Modul.',
        'team_security'=>'Checking the <b>Team Security</b> checkbox will enable team security for this module.  <br/><br/>If team security is enabled, the Team selection field will appear within the records in the module',
        'reportable'=>'Wenn Sie dieses Kästchen ankreuzen, können zu diesem Modul Berichte erstellt werden.',
        'assignable'=>'Wenn Sie dieses Kästchen ankreuzen, kann ein Datensatz dieses Moduls einem bestimmten Benutzer zugewiesen werden.',
        'has_tab'=>'Wenn sie <b>Registerkarte "Navigation"</b> ankreuzen, wird eine solche Registerkarte für dieses Modul erstellt.',
        'acl'=>'Durch das Ankreuzen dieses Kästchens werden die Zugriffskontrollen für dieses Modul, einschließlich der Sicherheitsfunktionen auf Feld-Ebene, aktiviert.',
        'studio'=>'Wenn Sie dieses Kästchen ankreuzen, können Administratoren dieses Modul in Studio anpassen.',
        'audit'=>'Das Ankreuzen dieses Kästchens ermöglicht den Audit dieses Moduls. Änderungen in bestimmten Feldern werden erfasst, damit Administratoren die Änderungshistorie einsehen können.',
        'viewfieldsbtn'=>'Klicken Sie auf <b>Felder anzeigen</b>, um sich die Felder, die zu diesem Modul gehören, anzusehen und um benutzerdefinierte Felder zu erstellen bzw. zu bearbeiten.',
        'viewrelsbtn'=>'Klicken Sie auf <b>Beziehungen anzeigen</b>, um die Beziehungen, die diesem Modul zugeordnetet sind, anzuzeigen bzw. neue Beziehungen zu erstellen.',
        'viewlayoutsbtn'=>'Klicken Sie auf <b>Layouts anzeigen</b> um die Layouts für das Modul anzuzeigen und die Anordnung der Felder innerhalb der Layouts anzupassen.',
        'viewmobilelayoutsbtn' => 'Klicken Sie auf <b>Mobile Layouts anzeigen</b>, um die mobilen Layouts für das Modul anzuzeigen und die Feldanordnung in den Layouts anzupassen.',
        'duplicatebtn'=>'Klicken Sie auf <b>Duplizieren</b>, um die Eigenschaften des Moduls in ein neues Modul zu kopieren und das neue Modul anzuzeigen. <br/><br/>Für das neue Modul wird automatisch durch Hinzufügen einer Zahl am Endes des Namens ein neuer Name generiert.',
        'deletebtn'=>'Klicken Sie auf <b>Löschen</b>, um dieses Modul zu löschen.',
        'name'=>'Dies ist der <b>Name</b> des aktuellen Moduls. <br/><br/>Der Name muss alphanumerisch sein, muss mit einem Buchstaben beginnen und darf keine Leerzeichen oder Sonderzeichen enthalten. ( z. B. HR_Management)',
        'label'=>'Dies ist die <b>Beschreibung</b> des Moduls, die in der Registerkarte "Navigation" angezeigt wird. ',
        'savebtn'=>'Klicken Sie auf <b>Speichern</b>, um alle eingegebenen Daten mit Bezug auf das Modul zu speichern.',
        'type_basic'=>'Der <b>Basis</b>-Vorlagentyp enthält grundlegende Felder wie Name, Zugewiesen an, Team, Erstellungsdatum und Beschreibungsfelder.',
        'type_company'=>'Der <b>Firmen</b>-Vorlagentyp enthält organisationsspezifische Felder wie Firmenname, Branche und Rechnungsadresse. <br/><br/>Verwenden Sie diese Vorlage, um Module zu erstellen, die dem Standard-Firmenmodul ähneln.',
        'type_issue'=>'Der <b>Problem</b>-Vorlagentyp enthält Ticket- und Fehler-spezifische Felder, wie Nummer, Status, Priorität und Beschreibung. <br/><br/>Verwenden Sie diese Vorlage zum Erstellen von Modulen, die den Standard-Ticket- und Fehler-Tracker-Modulen ähneln.',
        'type_person'=>'Der Vorlagentyp <b>Person</b> enthält personenspezifische Felder wie Anrede, Titel, Name, Anschrift und Telefonnummer. <br/><br/>Verwenden Sie diese Vorlage, um Module zu erstellen, die den Standard-Kontakt- und Interessenten-Modulen ähneln.',
        'type_sale'=>'Der Vorlagentyp <b>Verkauf</b> enthält Felder mit Bezug auf die Verkaufschancen, wie Quelle, Stadium, Betrag und Wahrscheinlichkeit. <br/><br/> Verwenden Sie diese Vorlage, um Module zu erstellen, die dem Standard-Verkaufschancen-Modul ähneln.',
        'type_file'=>'Die <b>Datei</b>-Vorlage enthält dokumentspezifische Felder wie Dateinamen, Dokumenttyp und Veröffentlichungsdatum. <br><br>Verwenden Sie diese Vorlage, um Module zu erstellen, die dem Standard-Dokument-Modul ähneln.',

    ),
    'dropdowns'=>array(
        'default' => 'Alle <b>Dropdown-Listen</b> der Anwendung sind hier aufgelistet. <br><br>Die Dropdown-Listen können als Auswahlfelder in jedem beliebigen Modul verwendet werden. <br><br>Um Änderungen an einer bereits bestehenden Dropdown-Liste vorzunehmen, klicken Sie auf den Dropdown-Namen. <br><br>Klicken Sie auf <b>Dropdown hinzufügen</b>, um eine neue Dropdown-Liste zu erstellen.',
        'editdropdown'=>'Dropdown-Listen können als Standard- oder benutzerdefinierte Auswahlfelder in jedem beliebigen Modul verwendet werden. <br><br>Geben Sie der Dropdown-Liste einen <b>Namen</b>. <br><br>Wenn in der Anwendung Sprachpakete installiert sind, können Sie die <b>Sprache</b> für die Listenelemente auswählen. <br><br>Im Feld <b>Element-Name</b> geben Sie einen Namen für die Option in der Dropdown-Liste ein. Dieser Name erscheint nicht in der Dropdown-Liste, die für Benutzer sichtbar ist. <br><br>Geben Sie im Feld <b>Display-Beschreibung</b>  eine Bezeichnung ein, die für den Benutzer sichtbar ist. <br><br>Nachdem Sie den Element-Namen und die Bezeichnung eingegeben haben, klicken Sie auf <b>Hinzufügen</b>, um das Element der Dropdown-Liste hinzuzufügen. <br><br>Um die Elemente in der Liste neu anzuordnen, verschieben Sie die Elemente in die gewünschten Positionen. <br><br>Um die Display-Bezeichnung eines Elements zu bearbeiten, klicken Sie auf das Symbol für <b>Bearbeiten</b> und geben Sie eine neue Bezeichnung ein. Um ein Element aus der Dropdown-Liste zu löschen, klicken Sie auf das Symbol für <b>Löschen</b>. <br><br>Um eine Änderung einer Display-Bezeichnung rückgängig zu machen, klicken Sie auf <b>Rückgängig machen</b>.  Um eine rückgängig gemachte Änderung daraufhin wiederherzustellen, klicken Sie auf <b>Wiederherstellen</b>. <br><br>Klicken Sie auf <b>Speichern</b>, um die Dropdown-Liste zu speichern.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klicken Sie auf <b>Speichern & anwenden</b>, um die vorgenommenen Änderungen zu speichern und sie innerhalb des Moduls zu aktivieren.',
        'historyBtn'=> 'Klicken Sie auf <b>Verlauf anzeigen</b>, um ein zuvor gespeicherten Layout anzuzeigen und wiederherzustellen.',
        'historyRestoreDefaultLayout'=> 'Klicken Sie auf <b>Standard-Layout wiederherstellen</b>, um ein Layout wieder in seine ursprüngliche Form zurückzusetzen.',
        'Hidden' 	=> '<b>Ausgeblendete</b>Felder werden im Sub-Panel nicht angezeigt.',
        'Default'	=> '<b>Standard-</b>Felder werden im Sub-Panel angezeigt.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klicken Sie auf <b>Speichern & anwenden</b>, um die vorgenommenen Änderungen zu speichern und sie innerhalb des Moduls zu aktivieren.',
        'historyBtn'=> 'Klicken Sie auf <b>Verlauf anzeigen</b>, um ein zuvor gespeichertes Layout anzuzeigen bzw. wiederherzustellen. Die Funktion <br><br><b>Wiederherstellen</b> unter <b>Verlauf anzeigen</b> stellt die Positionierung der Felder innerhalb von zuvor gespeicherten Layouts wieder her. Um die Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'historyRestoreDefaultLayout'=> 'Klicken Sie auf <b>Standard-Layout wiederherstellen</b>, um ein Layout wieder in seine ursprüngliche Form zurückzusetzen. <br><br><b>Standard-Layout wiederherstellen</b> setzt es nur auf die Platzierung im Originallayout zurück. Um Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'Hidden' 	=> '<b>Verborgene</b>Felder, die dem Benutzer momentan in der Listenansicht nicht zur Ansicht zur Verfügung stehen.',
        'Available' => '<b>Verfügbare</b> Felder werden standardmäßig nicht angezeigt, sondern können der Listenansicht von den Benutzern selbst hinzugefügt werden.',
        'Default'	=> '<b>Standard-</b>Felder werden in den Listenansichten angezeigt, die nicht von Benutzern angepasst werden.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Klicken Sie auf <b>Speichern & anwenden</b>, um die vorgenommenen Änderungen zu speichern und sie innerhalb des Moduls zu aktivieren.',
        'historyBtn'=> 'Klicken Sie auf <b>Verlauf anzeigen</b>, um ein zuvor gespeichertes Layout anzuzeigen bzw. wiederherzustellen. Die Funktion <br><br><b>Wiederherstellen</b> unter <b>Verlauf anzeigen</b> stellt die Positionierung der Felder innerhalb von zuvor gespeicherten Layouts wieder her. Um die Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'historyRestoreDefaultLayout'=> 'Klicken Sie auf <b>Standard-Layout wiederherstellen</b>, um ein Layout wieder in seine ursprüngliche Form zurückzusetzen. <br><br><b>Standard-Layout wiederherstellen</b> setzt es nur auf die Platzierung im Originallayout zurück. Um Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'Hidden' 	=> '<b>Verborgene</b>Felder, die dem Benutzer momentan in der Listenansicht nicht zur Ansicht zur Verfügung stehen.',
        'Default'	=> '<b>Standard-</b>Felder werden in den Listenansichten angezeigt, die nicht von Benutzern angepasst werden.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Durch Klicken auf <b>Speichern & anwenden </b> werden alle Änderungen gespeichert und aktiviert',
        'Hidden' 	=> '<b>Verborgene</b>Felder werden bei einer Suche nicht angezeigt.',
        'historyBtn'=> 'Klicken Sie auf <b>Verlauf anzeigen</b>, um ein zuvor gespeichertes Layout anzuzeigen bzw. wiederherzustellen. Die Funktion <br><br><b>Wiederherstellen</b> unter <b>Verlauf anzeigen</b> stellt die Positionierung der Felder innerhalb von zuvor gespeicherten Layouts wieder her. Um die Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'historyRestoreDefaultLayout'=> 'Klicken Sie auf <b>Standard-Layout wiederherstellen</b>, um ein Layout wieder in seine ursprüngliche Form zurückzusetzen. <br><br><b>Standard-Layout wiederherstellen</b> setzt es nur auf die Platzierung im Originallayout zurück. Um Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'Default'	=> '<b>Standard-</b>Felder werden bei einer Suche angezeigt.'
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
        'saveBtn'	=> 'Klicken Sie auf <b>Speichern</b>, um die Änderungen zu bewahren, die Sie am Layout vorgenommen haben, seit Sie es zum letzten Mal gespeichert haben. <br><br>Die Änderungen werden im Modul erst dann angezeigt, wenn Sie alle gespeicherten Änderungen auch tatsächlich bereitstellt haben.',
        'historyBtn'=> 'Klicken Sie auf <b>Verlauf anzeigen</b>, um ein zuvor gespeichertes Layout anzuzeigen bzw. wiederherzustellen. Die Funktion <br><br><b>Wiederherstellen</b> unter <b>Verlauf anzeigen</b> stellt die Positionierung der Felder innerhalb von zuvor gespeicherten Layouts wieder her. Um die Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'historyRestoreDefaultLayout'=> 'Klicken Sie auf <b>Standard-Layout wiederherstellen</b>, um ein Layout wieder in seine ursprüngliche Form zurückzusetzen. <br><br><b>Standard-Layout wiederherstellen</b> setzt es nur auf die Platzierung im Originallayout zurück. Um Feldbezeichnungen zu ändern, klicken Sie auf das Symbol "Bearbeiten" neben dem jeweiligen Feld.',
        'publishBtn'=> 'Klicken Sie auf <b>Speichern & anwenden</b>, um alle Änderungen zu speichern, die Sie am Layout vorgenommen haben, seit Sie es zum letzten Mal gespeichert haben, und um die Änderungen im Modul zu aktivieren. <br><br>Das Layout wird dann sofort im Modul angezeigt.',
        'toolbox'	=> 'Die <b>Toolbox</b> enthält den <b>Papierkorb</b>, zusätzliche Layout-Elemente und alle verfügbaren Felder, die dem Layout hinzugefügt werden können. <br/><br/>Die Felder in der Toolbox sowie die Layout-Elemente können dem Layout durch Ziehen und Ablegen hinzugefügt werden und die Felder und Layout-Elemente können auch in die Toolbox verschoben werden. <br><br>Die Layout-Elemente bestehen aus <b>Bereichen</b> und <b>Zeilen</b>. Durch das Hinzufügen neuer solcher Abschnitte, entstehen im Layout neue Bereiche für Felder. <br/><br/>Sie können die Felder je nach Wunsch in die Toolbox oder in ein Layout auf ein bereits belegtes Feld verschieben, um die Positionen der beiden Felder zu tauschen. <br/><br/>Das <b>Füller</b>-Feld erzeugt Leerstellen in dem Layout, in dem es eingesetzt wird.',
        'panels'	=> 'Der <b>Layout</b>-Bereich bietet eine Voransicht des Layouts, wie es innerhalb des Moduls aussehen wird, wenn die Änderungen an dem Layout angewendet werden. <br/><br/> Sie können Felder, Zeilen und Bereiche durch Ziehen und Ablegen in die gewünschte Position bringen. <br/><br/>Entfernen Sie Elemente, indem Sie sie in den <b>Papierkorb</b> oder die Toolbox verschieben, oder fügen Sie neue Elemente und Felder hinzu, indem Sie sie aus der <b>Toolbox</b> heraus ziehen und an der gewünschten Position im Layout ablegen.',
        'delete'	=> 'Verschieben Sie Elemente hierhin, um sie aus dem Layout zu entfernen',
        'property'	=> 'Edit The label displayed for this field. <br/><b>Tab Order</b> controls in what order the tab key switches between fields.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Die für das Modul zur Verfügung stehenden <b>Felder</b> werden hier mit ihren Feldnamen aufgeführt. <br><br>Benutzerdefinierte Felder, die für das Modul erstellt werden, erscheinen über den Feldern, die für das Modul standardmäßig zur Verfügung stehen. <br> <br>Um ein Feld zu bearbeiten, klicken Sie auf den <b>Feldnamen</b>. <br/><br/>Um ein neues Feld zu erstellen, klicken Sie auf <b>Feld hinzufügen</b>.',
        'mbDefault'=>'Die für das Modul zur Verfügung stehenden <b>Felder</b> werden hier mit ihren Feldnamen aufgeführt.<br><br>Um die Eigenschaften eines Feldes zu konfigurieren, klicken Sie auf den Feldnamen. <br><br>Um ein neues Feld zu erstellen, klicken Sie auf <b>Feld hinzufügen</b>. Die Bezeichnung und die anderen Eigenschaften des neuen Feldes können nach der Erstellung bearbeitet werden, indem Sie auf den Feldnamen klicken. <br><br>Nach der Bereitstellung des Moduls, gelten die neuen, mit dem Module-Ersteller angelegten Felder, als Standard-Felder in dem in Studio bereitgestellten Modul.',
        'addField'	=> 'Wählen Sie einen <b>Datentyp</b> für das neue Feld. Dieser Typ bestimmt, welche Art von Zeichen in dem Feld eingegeben werden können. Beispielsweise können in den Datentyp "Integer" nur ganze Zahlen in die Felder eingegeben werden. <br><br>Geben Sie dem Feld einen <b>Namen</b>. Der Name muss alphanumerisch sein und darf keine Leerzeichen enthalten. Unterstriche dürfen verwendet werden. <br><br>Die <b>Display-Bezeichnung</b> ist die Bezeichnung, die für die Felder in den Modul-Layouts angezeigt wird. Die <b>System-Bezeichnung</b> wird verwendet, um auf das Feld im Code zu verweisen. <br><br>Je nach dem Datentyp können einige oder alle der folgenden Eigenschaften für das Feld festgelegt werden: Der <br><br><b>Hilfetext</b> wird vorübergehend angezeigt, wenn ein Benutzer mit dem Mauszeiger über das Feld geht, und er kann verwendet werden, um den Benutzer zur Eingabe des gewünschten Typs aufzufordern. <br><br>Der<b>Kommentartext</b> ist nur in Studio bzw. im Modul-Ersteller zu sehen und kann verwendet werden, um das Feld für Administratoren zu beschreiben. <br><br>Der <b>Standardwert</b> wird in dem Feld angezeigt. Benutzer können einen neuen Wert in das Feld eingeben oder den Standardwert verwenden. <br><br>Kreuzen Sie das Feld <b>Massen-Aktualisierung</b> an, um die Massen-Aktualisierungsfunktion in dem Feld anwenden zu können. <br><br>Der Wert <b>Max. Größe</b> bestimmt die maximale Anzahl von Zeichen, die in das Feld eingegeben werden können. <br><br>Kreuzen Sie das <b>Pflichtfeld</b>-Kästchen an, um das Feld verpflichtend zu machen. Für das Feld muss ein Wert angegeben werden, um einen Datensatz mit dem Feld speichern können. <br><br>Kreuzen Sie das Kästchen <b>Berichtbar</b> an, damit das Feld als Filter und zum Anzeigen von Daten in Berichten verwendet werden kann. <br> <br>Kreuzen Sie das <b>Audit</b>-Kästchen an, um vorgenommene Änderungen an dem Feld im Änderungsprotokoll nachverfolgen zu können. <br><br>Wählen  Sie eine Option im Feld <b>Importierbar</b>, um einen Import über den Import-Assistenten zuzulassen, nicht zuzulassen oder verpflichtend zu machen. <br><br>Wählen Sie eine der Optionen im Feld <b>Duplikate-Zusammenführung</b>, um die Funktionen "Duplikate-Zusammenführung" und "Duplikat-Suche" zu aktivieren oder zu deaktivieren. <br><br>Für bestimmte Datentypen können zusätzliche Eigenschaften festgelegt werden.',
        'editField' => 'Die Eigenschaften dieses Feldes können angepasst werden. <br><br>Klicken Sie auf <b>Klonen</b>, um ein neues Feld mit denselben Eigenschaften zu erstellen.',
        'mbeditField' => 'Die <b>Display-Beschreibung</b> eines Vorlagenfelds kann angepasst werden. Die anderen Eigenschaften des Feldes können nicht angepasst werden. <br><br>Klicken Sie auf <b>Klinen</b>, um ein neues Feld mit denselben Eigenschaften zu erstellen. <br><br>Um ein Vorlagenfeld zu entfernen, damit es nicht mehr im Modul angezeigt wird, entfernen Sie das Feld aus den entsprechenden <b>Layouts</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Exportieren Sie in Studio vorgenommene Anpassungen, indem Sie Pakete erstellen, die mittels des <b>Modul-Laders</b> in eine andere Instanz von Sugar hochgeladen werden können. <br><br>Geben Sie dem Paket zuerst einen <b>Paketnamen</b>. Sie können Informationen bezüglich des <b>Autors</b> und eine <b>Beschreibung</b> des Pakets eingeben. <br><br>Wählen Sie das (die) Modul(e) aus, welche die Anpassungen enthalten, die Sie exportieren möchten. Für Ihre Auswahl werden Ihnen nur Module mit Anpassungen angezeigt. <br><br>Klicken Sie dann auf <b>Exportieren</b>, um eine .zip-Datei für das Paket mit den Anpassungen zu erstellen.',
        'exportCustomBtn'=>'Klicken Sie auf <b>Exportieren</b>, um eine .zip-Datei für das Paket mit den Anpassungen, die Sie exportieren möchten, zu erstellen.',
        'name'=>'Dies ist der <b>Name</b> des Pakets. Dieser Name wird während der Installation angezeigt werden.',
        'author'=>'Dies ist der Name des <b>Autors</b>, der während der Installation als Name des Erstellers des Pakets angezeigt wird. Der Autor kann entweder eine Einzelperson oder eine Firma sein.',
        'description'=>'Dies ist die <b>Beschreibung</b> des Pakets, die bei der Installation angezeigt wird.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Herzlich willkommen im Bereich <b>Entwickler-Tools</b>. <br/><br/>Verwenden Sie die Tools in diesem Bereich für die Erstellung und Verwaltung von Standard- und benutzerdefinierten Modulen und Feldern.',
        'studioBtn'	=> 'Verwenden Sie <b>Studio</b>, um die bereitgestellten Module anzupassen.',
        'mbBtn'		=> 'Verwenden Sie den <b>Module-Ersteller</b>, um neue Module zu erstellen.',
        'sugarPortalBtn' => 'Verwenden Sie den<b>Sugar-Portal-Editor</b>, um das Sugar-Portal zu verwalten und zu bearbeiten.',
        'dropDownEditorBtn' => 'Verwenden Sie den <b>Dropdown-Editor</b>, um Dropdown-Feldern globale Auswahllisten hinzuzufügen und zu bearbeiten.',
        'appBtn' 	=> 'Im Anwendungsmodus können Sie verschiedene Eigenschaften des Programms anpassen, z. B. wie viele TPS-Berichte auf der Startseite angezeigt werden',
        'backBtn'	=> 'Zurückkehren zum vorherigen Schritt.',
        'studioHelp'=> 'Verwenden Sie <b>Studio</b>, um zu bestimmen, welche Informationen in den Modulen angezeigt werden, und wie.',
        'studioBCHelp' => 'Zeigt an, ob das Modul rückwärtskompatibel ist',
        'moduleBtn'	=> 'Hier klicken, um dieses Modul zu bearbeiten.',
        'moduleHelp'=> 'Hier werden die Komponenten angezeigt, die Sie für das Modul anpassen können. <br><br>Klicken Sie auf ein Symbol, um die Komponente auszuwählen, die Sie bearbeiten möchten.',
        'fieldsBtn'	=> 'Erstellen und bearbeiten Sie <b>Felder</b> zum Speichern von Informationen im Modul.',
        'labelsBtn' => 'Bearbeiten Sie die <b>Bezeichnungen</b>, unter denen die Felder und andere Titel im Modul angezeigt werden.'	,
        'relationshipsBtn' => 'Fügen Sie dem Modul neue <b>Beziehungen</b>hinzu oder bearbeiten Sie bereits vorhandene.' ,
        'layoutsBtn'=> 'Passen Sie die Modul-<b>Layouts</b> an. Layouts sind die verschiedenen Ansichten der Felder, die Module enthalten. <br><br>Sie können bestimmen, welche Felder angezeigt werden und wie sie in den einzelnen Layouts organisiert werden.',
        'subpanelBtn'=> 'Bestimmen Sie, welche Felder in den <b>Sub-Panels</b> des Moduls angezeigt werden.',
        'portalBtn' =>'Passen Sie die Modul-<b>Layouts</b> an, die auf dem <b>Sugar-Portal</b>angezeigt werden.',
        'layoutsHelp'=> 'Die Modul-<b>Layouts</b>, die benutzerdefiniert angepasst werden können, werden hier angezeigt.<br><br>In den Layouts werden Felder und Felddaten wiedergegeben.<br><br>Klicken Sie das Symbol des Layouts an, das Sie bearbeiten möchten.',
        'subpanelHelp'=> 'Hier werden die <b>Sub-Panels</b> des Moduls angezeigt, die angepasst werden können. <br><br>Klicken Sie das Symbol des Moduls an, das Sie bearbeiten möchten.',
        'newPackage'=>'Klicken Sie auf <b>Neues Paket</b>, um ein neues Paket erstellen.',
        'exportBtn' => 'Klicken Sie auf <b>Anpassungen exportieren </b>, um ein Paket zu erstellen und herunterzuladen, das in Studio vorgenommene Anpassungen für bestimmte Module enthält.',
        'mbHelp'    => 'Verwenden Sie den <b>Module-Ersteller</b> zum Erstellen von Paketen mit benutzerdefinierten Modulen, basierend auf Standard- oder benutzerdefinierten Objekten.',
        'viewBtnEditView' => 'Passen Sie die <b>Bearbeitungsansicht</b> des Moduls an.<br><br>Diese enthält Eingabefelder für die Erfassung von Daten, die vom Benutzer eingegeben werden.',
        'viewBtnDetailView' => 'Passen Sie die <b>Detailansicht</b> des Moduls an.<br><br>Diese zeigt Felddaten an, die vom Benutzer eingegeben werden.',
        'viewBtnDashlet' => 'Passen Sie das <b>Sugar-Dashlet</b> des Moduls an, inkl. dessen Listenansicht und Suche.<br><br>Dieses kann daraufhin den Seiten im Modul "Startseite" hinzugefügt werden.',
        'viewBtnListView' => 'Passen Sie die <b>Listenansicht</b> des Moduls an.<br><br>Die Listenansicht dient zur Darstellung der Suchergebnisse.',
        'searchBtn' => 'Passen Sie die <b>Suche-Layouts</b> des Moduls an.<br><br>Bestimmen Sie, welche Felder für das Filtern von Datensätzen für die Anzeige in der Listenansicht herangezogen werden können.',
        'viewBtnQuickCreate' =>  'Passen Sie das <b>Schnellerstellungs-Layout</b> des Moduls an.<br><br>Das Formular zur Schnellerstellung wird in Sub-Panels und im E-Mails-Modul angezeigt.',

        'searchHelp'=> 'Hier werden die <b>Suchformulare</b> angezeigt, die angepasst werden können. <br><br>Suchformulare enthalten Felder zum Filtern von Datensätzen. <br> <br>Klicken Sie auf das Symbol für die Suche, deren Layout Sie bearbeiten möchten.',
        'dashletHelp' =>'Hier erscheinen die <b>Sugar-Dashlet</b>-Layouts, die angepasst werden können. <br><br>Mit dem Sugar-Dashlet können dem Startseitenmodul Seiten hinzugefügt werden.',
        'DashletListViewBtn' =>'Die <b>Suger-Dashlet-Listenansicht</b> zeigt Datensätze an, die auf den Sugar-Dashlet Suchfiltern basieren.',
        'DashletSearchViewBtn' =>'Die <b>Sugar-Dashlet-Suche</b> filtert die Datensätze für die Sugar-Dashlet-Listenansicht.',
        'popupHelp' =>'Die <b>Pop-up</b>-Layouts, die angepasst werden können, werden hier angezeigt. <br>',
        'PopupListViewBtn' => 'Die <b>Pop-up-Listenansicht</b> zeigt bestimmte Datensätze an, wenn ein oder mehrere Datensätze ausgewählt werden, die mit dem aktuellen Datensatz verbunden sind.',
        'PopupSearchViewBtn' => 'Das Layout <b>Pop-up-Suche</b> ermöglicht es Benutzern, nach Datensätzen zu suchen, die mit einem aktuellen Datensatz verbunden sind; dieses wird über der Pop-up-Listenansicht im gleichen Fenster angezeigt. Ältere Module verwenden dieses Layout für die Pop-up-Suche, während Sidecar-Module die Suche-Layout-Konfiguration verwenden.',
        'BasicSearchBtn' => 'Passen Sie das Formular für die <b>einfache Suche</b>-an, das in der entsprechenden Registerkarte im Suchbereich für das Modul angezeigt wird.',
        'AdvancedSearchBtn' => 'Passen Sie das Formular für die <b>erweiterte Suche</b> an, das in der entsprechenden Registerkarte im Suchbereich für das Modul angezeigt wird.',
        'portalHelp' => 'Verwalten und bearbeiten Sie das <b>Sugar-Portal</b>.',
        'SPUploadCSS' => 'Laden Sie eine <b>Stylesheet</b> für das Sugar-Portal hoch.',
        'SPSync' => '<b>Synchronisieren</b> Sie die Anpassungen der Sugar-Portal-Instanz.',
        'Layouts' => 'Anpassen des <b>Layouts</b> der Sugar-Portal-Module.',
        'portalLayoutHelp' => 'In diesem Bereich werden die Module innerhalb des Sugar-Portals angezeigt. <br><br>Wählen Sie ein Modul zum Bearbeiten des <b>Layouts</b>.',
        'relationshipsHelp' => 'Alle <b>Beziehungen</b> zwischen dem Modul und anderen bereitgestellten Modulen werden hier angezeigt.<br><br>Die Beziehung trägt einen durch das System  für die Beziehung generierten <b>Namen</b>.<br><br>. Das Modul, das für die Beziehung zuständig ist, das <b>primäre Modul</b>.  So werden z. B. alle Eigenschaften der Beziehungen, für die das Firmenmodul das primäre Modul ist, in den Datenbanktabellen für Firmen gespeichert.<br><br>Der <b>Typ</b> ist die Art der Beziehung, die zwischen dem primären Modul und dem <b>verwandten Modul</b>besteht.<br><br>Klicken Sie die Kopfzeile einer Spalte an, um nach der Spalte zu sortieren.<br><br>Klicken Sie auf eine Zeile in der Beziehungstabelle, um sich die zu der Beziehung gehörenden Eigenschaften anzusehen.<br><br>Klicken Sie auf <b>Beziehung hinzufügen</b>, um eine neue Beziehung zu erstellen.<br><br>Beziehungen können zwischen zwei beliebigen, bereitgestellten Modulen erstellt werden.',
        'relationshipHelp'=>'<b>Beziehungen</b> können zwischen dem Modul und einem anderen bereitgestellten Modul erstellt werden.<br><br> Sie werden durch Sub-Panels und Verbindungsfelder in den Modul-Datensätzen dargestellt.<br><br>Wählen Sie einen der folgenden <b>Beziehungstypen</b> für das Modul:<br><br> <b>Eins-zu-eins</b> - Die Datensätze beider Module enthalten Verbindungsfelder.<br><br> <b>Eins-zu-viele</b> - Der Primärmodul-Datensatz enthält ein Sub-Panel und der Datensatz "Verbundenes Modul" ein Verbindungsfeld.<br><br> <b>Viele-zu-viele</b> - Die Datensätze beider Module enthalten ein Verbindungsfeld.<br><br> Wählen Sie das <b>verbundene Modul</b> für die Beziehung aus. <br><br>Wenn der Beziehungstyp Sub-Panels enthält, wählen Sie die Sub-Panel-Ansicht für die entsprechenden Module.<br><br> Klicken Sie auf <b>Speichern</b>, um die Beziehung zu erstellen.',
        'convertLeadHelp' => "Hier können Sie dem Layout-Konvertierungsbildschirm Module hinzufügen und die Einstellungen der bestehenden Module ändern.<br/><br/>
<b>Reihenfolge:</b><br/>
Die Reihenfolge von Kontakte, Firmen und Verkaufschancen bleibt immer unverändert. Alle anderen Module können durch Verschieben der entsprechenden Zeile in der Tabelle neu angeordnet werden.<br/><br/>
<b>Ahängigkeit:</b><br/>
Wenn das Verkaufschancen-Modul enthalten ist, muss Konten entweder verpflichtend gemacht oder aus dem Konvertierungs-Layout entfernt werden.<br/><br/>
<b>Modul:</b> Name des Moduls.<br/><br/>
<b>Erforderlich:</b> Erforderliche Module müssen erstellt oder gewählt werden, bevor ein Interessent konvertiert werden kann.<br/><br/>
<b>Daten kopieren:</b> Wenn diese Option aktiviert ist, werden Felder von diesem Interessenten in die Felder mit denselben Namen in den neu erstellten Datensätzen kopiert.<br/><br/>
<b>Löschen:</b> Entfernt dieses Modul aus dem Konvertierungs-Layout.<br/><br/>        ",
        'editDropDownBtn' => 'Globale Dropdown-Liste bearbeiten',
        'addDropDownBtn' => 'Neue globale Dropdown-Liste hinzufügen',
    ),
    'fieldsHelp'=>array(
        'default'=>'Die <b>Felder</b> im Modul werden hier mit den Feldnamen aufgeführt. <br><br>Die Modul-Vorlage enthält einen vordefinierten Satz an Feldern. <br> <br>Um ein neues Feld zu erstellen, klicken Sie auf <b>Feld hinzufügen</b>. <br><br>Um ein Feld zu bearbeiten, klicken Sie auf den <b>Feldnamen</b>. <br/> <br/> Nach der Bereitstellung des Moduls werden die neuen, im Modul-Ersteller angelegten Felder zusammen mit den Vorlagen-Feldern in Studio als Standard-Felder angesehen.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>Beziehungen</b> zwischen dem Modul und anderen Modulen erscheinen hier.<br><br>Der <b>Name</b> für diese Beziehung wird vom System generiert.<br><br>Das <b>Primärmodul</b> ist der Besitzer der Beziehung. Die Eigenschaften davon werden also in den Datenbanktabellen des Primärmoduls gespeichert.<br><br>Der <b>Typ</b> ist die Art der Beziehung zwischen dem Primärmodul und dem <b>verbundenen Modul</b>.<br><br>Klicken Sie auf einen Spaltennamen, um die Spalte entsprechend zu sortieren.<br><br>Klicken Sie auf eine Zeile in der Beziehungstabelle, um die mit der Beziehung verbundenen Eigenschaften anzuzeigen und zu bearbeiten.<br><br>Klicken Sie auf <b>Beziehung hinzufügen</b>, um eine neue Beziehung hinzuzufügen.',
        'addrelbtn'=>'Mauszeiger-Hilfe, um eine Beziehung hinzuzufügen..',
        'addRelationship'=>'<b>Beziehungen</b> können zwischen dem Modul und einem anderen bereitgestellten Modul erstellt werden.<br><br> Sie werden durch Sub-Panels und Verbindungsfelder in den Modul-Datensätzen dargestellt.<br><br>Wählen Sie einen der folgenden <b>Beziehungstypen</b> für das Modul:<br><br> <b>Eins-zu-eins</b> - Die Datensätze beider Module enthalten Verbindungsfelder.<br><br> <b>Eins-zu-viele</b> - Der Primärmodul-Datensatz enthält ein Sub-Panel und der Datensatz "Verbundenes Modul" ein Verbindungsfeld.<br><br> <b>Viele-zu-viele</b> - Die Datensätze beider Module enthalten ein Verbindungsfeld.<br><br> Wählen Sie das <b>verbundene Modul</b> für die Beziehung aus. <br><br>Wenn der Beziehungstyp Sub-Panels enthält, wählen Sie die Sub-Panel-Ansicht für die entsprechenden Module.<br><br> Klicken Sie auf <b>Speichern</b>, um die Beziehung zu erstellen.',
    ),
    'labelsHelp'=>array(
        'default'=> 'Die <b>Bezeichnungen</b> für die Felder und andere Kopfzeilen im Modul können geändert werden. <br><br>Bearbeiten Sie eine Bezeichnung, indem Sie auf das Feld klicken, eine neue Bezeichnung eingeben und auf <b>Speichern</b>klicken. <br> <br> Wenn in der Anwendung Sprachpakete installiert wurden, können Sie die <b>Sprache</b> auswählen, die Sie für die Bezeichnung verwenden möchten.',
        'saveBtn'=>'Klicken Sie auf <b>Speichern</b>, um alle Änderungen zu speichern.',
        'publishBtn'=>'Klicken Sie auf <b>Speichern & anwenden</b>, um alle Änderungen zu speichern und zu aktivieren.',
    ),
    'portalSync'=>array(
        'default' => 'Geben Sie die <b>Sugar-Portal-URL</b> der Portal-Instanz ein, die aktualisiert werden soll und klicken Sie auf <b>Start</b>. <br><br>Geben Sie dann einen gültigen Sugar-Benutzernamen mit Passwort ein und klicken Sie auf <b>Synchronisierung starten</b>. Die Anpassungen, die an den <br><br>Sugar-Portal-<b>Layouts</b> vorgenommen wurden, werden dann zusammen mit der <b>Stylesheet</b>, falls eine hochgeladen wurde, auf die angegebene Portal-Instanz übertragen.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Sie können das Aussehen des Sugar-Portals mit Stylesheets anpassen. <br><br>Wählen Sie eine <b>Stylesheet</b> zum Upload aus. <br> <br> Bei der nächtsten Synchronisierung wird diese dann im Sugar-Portal eingesetzt.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Um ein Projekt zu beginnen, klicken Sie auf <b>Neues Paket</b>, um ein neues Paket für Ihre benutzerdefinierten Module zu erstellen. <br/> <br/> Jedes Paket kann ein oder mehrere Module enthalten. <br/> <br/> Sie möchten z. B. ein Paket erstellen, das ein benutzerdefiniertes Modul enthält, das mit dem Standard-Firmenmodul verbunden ist. Oder vielleicht möchten Sie ein Paket mit mehreren neuen Modulen erstellen, die als Projekt zusammenarbeiten und die miteinander und mit anderen Modulen, die bereits Teil der Anwendung sind, verbunden sind.',
            'somepackages'=>'Ein <b>Paket</b> fungiert als Behälter für benutzerdefinierte Module, die alle Teil eines Projekts sind. Das Paket kann ein oder mehrere benutzerdefinierte <b>Module</b> enthalten, die miteinander oder mit anderen Modulen in der Anwendung verbunden sind. <br/><br/>Nach dem Erstellen eines Pakets für Ihr Projekt können Sie entweder sofort Module für das Projekt erstellen oder zu einem späteren Zeitpunkt zum Module-Ersteller zurückkehren, um das Projekt fertigzustellen. <br><br>, Wenn das Projekt fertiggestellt ist, können Sie das Paket <b>anwenden (bereitstellen)</b>, um die benutzerdefinierten Module innerhalb der Anwendung zu installieren.',
            'afterSave'=>'Ihr neues Paket muss mindestens ein Modul enthalten. Sie können ein oder mehrere benutzerdefinierte Module für das Paket erstellen.<br/><br/>Klicken Sie auf <b>Neues Modul</b>, um ein benutzerdefiniertes Modul für dieses Paket zu erstellen.<br/><br/> Nach der Erstellung von mindestens einem Modul können Sie diese(s) veröffentlichen oder anwenden, um es/sie für Ihre Instanz oder die Instanzen anderer Benutzer verfügbar zu machen.<br/><br/> Um eine Instanz mit nur einem Schritt in Ihrer Sugar-Instanz bereitzustellen, klicken Sie auf <b>Anwenden</b>.<br><br>Klicken Sie auf <b>Veröffentlichen</b>, um das Paket als .zip-Datei zu speichern. Nachdem die .zip-Datei in Ihrem System gespeichert wurde, verwenden Sie den <b>Modul-Lader</b>, um das Paket innnerhalb Ihrer Sugar-Instanz hochzuladen und zu installieren. <br/><br/>Sie können diese Datei an andere Benutzer verteilen, um Module so in ihren eigenen Sugar-Instanzen hochzuladen und zu installieren.',
            'create'=>'Ein <b>Paket</b> fungiert als Behälter für benutzerdefinierte Module, die alle Teil eines Projekts sind. Das Paket kann ein oder mehrere benutzerdefinierte <b>Module</b> enthalten, die miteinander oder mit anderen Modulen in der Anwendung verbunden sind. <br/><br/>Nach dem Erstellen eines Pakets für Ihr Projekt können Sie entweder sofort Module für das Paket erstellen oder zu einem späteren Zeitpunkt zum Modul-Ersteller zurückkehren, um das Projekt fertigzustellen.',
            ),
    'main'=>array(
        'welcome'=>'Verwenden Sie die <b>Entwickler-Tools</b> zum Erstellen und Verwalten von Standard- und benutzerdefinierten Modulen und Feldern. <br/><br/> Klicken Sie zum Verwalten der Module in der Anwendung auf <b>Studio</b>. <br/><br/>Klicken Sie zum Erstellen benutzerdefinierter Module auf <b>Modul-Ersteller</b>.',
        'studioWelcome'=>'Alle derzeit installierten Module, einschließlich Standardobjekte und über Module geladene Objekte können in Studio angepasst werden.'
    ),
    'module'=>array(
        'somemodules'=>"Da das aktuelle Paket mindestens ein Modul enthält, können Sie  die Module im Paket innerhalb der Sugar-Instanz <b>Bereitstellen</b> oder <b>Veröffentlichen</b>, sodass das Paket in der aktuellen Sugar-Instanz oder einer anderen Instanz mithilfe des <b>Modul-Laders</b> installiert werden kann. <br/> <br/> Um das Paket direkt in der Sugar-Instanz zu installieren, klicken Sie auf <b>Bereitstellen</b>. <br><br>Um für das Paket eine Zip-Datei zu erstellen, die mithilfe des<b>Modul-Laders</b> in die aktuelle oder eine andere Sugar-Instanz geladen werden kann, klicken Sie auf <b>Veröffentlichen</b>. <br/> <br/> Sie können die Module für dieses Paket in Etappen erstellen und diese dann veröffentlichen oder bereitstellen, wenn Sie dazu bereit sind. <br/><br/>Nach der Veröffentlichung oder Bereitstellung eines Pakets können Sie Änderungen an den Eigenschaften des Pakets vornehmen und die Module noch weiter anpassen. Daraufhin müssen Sie das Paket erneut veröffentlichen oder erneut bereitstellen, damit die Änderungen übernommen werden." ,
        'editView'=> 'Hier können Sie bestehende Felder bearbeiten. Sie können diese Felder entfernen oder in dem Bereich auf der linken Seite zur Verfügung stehende Felder hinzufügen.',
        'create'=>'Wenn Sie den <b>Typ</b> für das Modul auswählen, das Sie erstellen möchten, denken Sie an die Feldtypen, die Sie innerhalb des Moduls haben möchten. <br/> <br/> Jede Modul-Vorlage enthält eine Reihe von Feldern, die zu dem im Title beschriebenen Modultyp gehören. <br/> <br/>Der Typ <b>Basismodul (grundlegendes Modul)</b> enthält Basisfelder in Standard-Modulen, z. B. Name, Zugewiesen an, Team, Erstellungsdatum und Beschreibungsfelder. <br/> <br/> Der Typ <b>Firma</b> enthält organisationsspezifische Bereiche, wie Firmenname, Branche und Rechnungsadresse. Verwenden Sie diese Vorlage, um Module zu erstellen, die dem Standard-Firmenmodul ähneln. <br/><br/>Der Typ <b>Person</b> enthält spezielle Felder wie Anrede, Titel, Name, Anschrift und Telefonnummer. Verwenden Sie diese Vorlage, um Module zu erstellen, die dem Standard-Kontakt- und Interessentenmodulen ähneln. <br/> <br/> Der Typ <b>Problem</b> enthält Ticket- und Fehler-spezifische Felder, wie Nummer, Status, Priorität und Beschreibung.  Verwenden Sie diese Vorlage zum Erstellen von Modulen, die den Standard-Ticket- und Bugtracker-Modulen ähneln. <br/> <br/>Hinweis: Nachdem Sie das Modul erstellt haben,  können Sie die Beschriftungen der von der Vorlage bereitgestellten Felder bearbeiten und benutzerdefinierte Felder erstellen und den Modul-Layouts hinzufügen.',
        'afterSave'=>'Passen Sie das Modul an Ihren Bedürfnisse an, indem Sie Felder bearbeiten und neu erstellen, Beziehungen zu anderen Modulen herstellen und die Anordnung der Felder innerhalb der Layouts anpassen. <br/><br/>Um sich die Vorlagenfelder anzusehen und benutzerdefinierte Felder innerhalb des Layouts zu verwalten, klicken Sie auf <b>Felder anzeigen</b>. <br/><br/>Für die Herstellung und Verwaltung von Beziehungen zwischen dem Modul und anderen Modulen, egal, ob zu bereits existierenden Modulen oder anderen benutzerdefinierten Module im gleichen Paket, klicken Sie auf<b>Beziehungen anzeigen</b>. <br/><br/>Um die Modul-Layouts zu bearbeiten, klicken Sie auf <b>Layouts anzeigen</b>. Sie können die Detailansicht, die Bearbeitungsansicht und die Listenansicht der Layouts für das Modul genauso verändern wie für Module, die innerhalb von Studio bereits Teil der Anwendung sind. <br/><br/>Klicken Sie zum Erstellen eines Moduls mit den gleichen Eigenschaften wie das aktuelle Modul auf <b>Duplizieren</b>. Darüber hinaus können Sie das neue Modul auch noch weiter anpassen.',
        'viewfields'=>'Die Felder im Modul können an Ihre Bedürfnisse angepasst werden. <br/><br/>Standardfelder können nicht gelöscht gewerden, aber Sie können sie aus den entsprechenden Layouts innerhalb der Layout-Seiten entfernen. <br/><br/>Sie können schnell neue Felder mit ähnlichen Eigenschaften erstellen, indem Sie auf <b>Klonen</b> unter <b>Eigenschaften</b> klicken.  Geben Sie neue Eigenschaften ein, und klicken Sie dann auf <b>Speichern</b>. <br/><br/>Wir empfehlen es Ihnen, alle Eigenschaften für die Standardfelder und benutzerdefinierten Felder festzulegen, bevor Sie das Paket mit dem benutzerdefinierte Modul veröffentlichen und installieren.',
        'viewrelationships'=>'Sie können "Viele-zu-viele"-Beziehungen zwischen dem aktuellen Modul und anderen Modulen im Paket herstellen und/oder zwischen dem aktuellen Modul und Modulen, die bereits in der Anwendung installiert sind. <br><br>Um "Viele-zu-viele"- oder "Eins-zu-eins"-Beziehungen zu erstellen, erstellen Sie Felder vom Typ <b>Verknüpfen</b> und <b>Flexbeziehung</b> für die Module.',
        'viewlayouts'=>'Sie können steuern, welche Felder für die Datenerfassung in der <b>Bearbeitungsansicht</b> verfügbar sind. Sie können auch steuern, welche Daten in der <b>Detailansicht</b> angezeigt werden.  Die Ansichten müssen nicht übereinstimmen. <br/><br/> Das Schnellerstellungsformular wird angezeigt, wenn in einem Sub-Panel des Moduls <b>Erstellen</b> angeklickt wird. Standardmäßig ist das Layout des Formulars für <b>Schnellerfassung</b> identisch mit dem Layout der <b>Bearbeitungsansicht</b>. Sie können das Schnellerfassungsformular anpassen, sodass es weniger bzw. andere Felder enthält als das Layout der Bearbeitungsansicht. <br><br>Sie können die Modul-Sicherheit mittlels Layout-Anpassung  bestimmen, sowie auch die <b>Rollenverwaltung</b>. <br> <br>',
        'existingModule' =>'Nach dem Erstellen und Anpassen dieses Moduls können Sie zusätzliche Module erstellen oder zurückgehen, um das Paket zu <b>veröffentlichen</b> oder <b>anzuwenden</b>. <br><br>Um zusätzliche Module zu erstellen, klicken Sie auf <b>Duplizieren</b>, um ein Modul mit den gleichen Eigenschaften zu erstellen, oder navigieren zurück zum Paket und klicken Sie auf <b>Neues Modul</b>. <br> <br> Wenn Sie bereit sind zum <b>Veröffentlichen</b> oder <b>Anwenden</b> des Pakets, das dieses Modul enthält, navigieren Sie zurück zum Paket, um diese Funktionen auszuführen. Sie können nur Pakete veröffentlichen und anwenden, die mindestens ein Modul enthalten.',
        'labels'=> 'Die Beschriftungen der Standardfelder sowie der benutzerdefinierte Felder können geändert werden.  Änderungen an den Feldbezeichnungen haben keinen Einfluss auf die Daten, die in den Feldern gespeichert sind.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Auf der linken Seite werden drei Spalten angezeigt. Die "Standard"-Spalte enthält die Felder, die standardmäßig in einer Listenansicht angezeigt werden, die "Verfügbar"-Spalte enthält Felder, die ein Benutzer zum Erstellen einer benutzerdefinierten Listenansicht auswählen kann, und die "Verborgen"-Spalte enthält Felder, die im Augenblick deaktiviert sind und Ihnen als Administator zur Verfügung stehen, um diese entweder über die Standard- oder die Verfügbar-Spalte den Benutzern zur Verfügung zu stellen.',
        'savebtn'	=> 'Durch Klicken auf <b>Speichern</b> werden alle Änderungen gespeichert und aktiviert.',
        'Hidden' 	=> 'Verborgene Felder sind Felder, die den Benutzern zu diesem Zeitpunkt in den Listenansichten nicht zur Verfügung stehen.',
        'Available' => 'Verfügbare Felder sind Felder, die standardmäßig nicht angezeigt werden, aber vom Benutzer aktiviert werden können.',
        'Default'	=> 'Standardfelder werden für Benutzer angezeigt, die keine benutzerdefinierten Listenansichtseinstellungen erstellt haben.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Auf der linken Seite werden zwei Spalten angezeigt. Die Spalte "Standard" enthält die Felder, die in der Suche-Ansicht angezeigt werden, und die "Verborgen"-Spalte enthält Felder, die Sie als Administrator der Ansicht hinzufügen können.',
        'savebtn'	=> 'Durch Klicken auf <b>Speichern & anwenden</b> werden alle Änderungen gespeichert und aktiviert.',
        'Hidden' 	=> 'Verborgene Felder sind Felder, die in der Suche-Ansicht nicht angezeigt werden.',
        'Default'	=> 'Standardfelder werden in der Suche-Ansicht angezeigt.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Auf der linken Seite werden zwei Spalten angezeigt. In der rechten Spalte, mit der Bezeichnung "Aktuelles Layout" oder "Layout-Vorschau", können Sie das Modul-Layout verändern. Die linke Spalte, mit der Bezeichnung "Toolbox", enthält nützliche Elemente und Werkzeuge für die Bearbeitung des Layouts. <br/><br/>Wenn der Layoutbereich die Bezeichung "Aktuelles Layout" trägt, bedeutet dies, dass Sie an einer Kopie des Layouts arbeiten, das im Augenblick durch das Modul für die Anzeige verwendet wird. <br/><br/>Wenn es die Bezeichnung "Layout-Vorschau" trägt, arbeiten Sie an einer Kopie, die zuvor durch Klicken auf die Schaltfläche "Speichern" erstellt wurde, und die sich möglicherweise bereits von der Version unterscheidet, die den Benutzern dieses Moduls derzeit angezeigt wird.',
        'saveBtn'	=> 'Ein Klick auf diese Schaltfläche speichert das Layout, um Ihre Änderungen zu bewahren. Wenn Sie zu diesem Modul zurückkehren, fangen Sie wieder bei diesem geänderten Layout an. Das Layout wird den Benutzern des Moduls jedoch erst angezeigt, nachdem Sie auf "Speichern und veröffentlichen" geklickt haben.',
        'publishBtn'=> 'Klicken Sie auf diese Schaltfläche, um das Layout anzuwenden. Das bedeutet, dass die Benutzer dieses Moduls dieses Layout dann sofort sehen können.',
        'toolbox'	=> 'Die Toolbox enthält eine Vielzahl an nützlichen Funktionen für die Bearbeitung von Layouts, einschließlich eines Papierkorbs, einer Reihe zusätzlicher Elemente und einer Reihe von verfügbaren Feldern. Diese können durch Ziehen und Ablegen in das Layout gebracht werden.',
        'panels'	=> 'Dieser Bereich zeigt, wie das Layout dieses Moduls für die Benutzer nach der Bereitstellung aussehen wird. <br/><br/> Sie können Elemente wie Felder, Zeilen und Bereiche durch Ziehen und Ablegen neu positionieren, diese in den Papierkorb in der Toolbox verschieben und löschen oder neue Elemente hinzufügen, indem Sie diese aus der Toolbox ziehen und im Layout an der gewünschten Position ablegen.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Auf der linken Seite werden zwei Spalten angezeigt. In der rechten Spalte, mit der Bezeichnung "Aktuelles Layout" oder "Layout-Vorschau", können Sie das Modul-Layout verändern. Die linke Spalte, mit der Bezeichnung "Toolbox", enthält nützliche Elemente und Werkzeuge für die Bearbeitung des Layouts. <br/><br/>Wenn der Layoutbereich die Bezeichung "Aktuelles Layout" trägt, bedeutet dies, dass Sie an einer Kopie des Layouts arbeiten, das im Augenblick durch das Modul für die Anzeige verwendet wird. <br/><br/>Wenn es die Bezeichnung "Layout-Vorschau" trägt, arbeiten Sie an einer Kopie, die zuvor durch Klicken auf die Schaltfläche "Speichern" erstellt wurde, und die sich möglicherweise bereits von der Version unterscheidet, die den Benutzern dieses Moduls derzeit angezeigt wird.',
        'dropdownaddbtn'=> 'Durch Anklicken dieser Schaltfläche fügen sie der Dropdown-Liste ein neues Element zu.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Anpassungen, die innerhalb dieser Instanz in Studio vorgenommen wurden, können verpackt und in einer anderen Instanz bereitgestellt werden. <br><br>Geben Sie dem Paket einen <b>Paketnamen</b>. Sie können auch Informationen zum <b>Autor</b> und eine <b>Beschreibung</b> hinzufügen. <br><br>Wählen Sie das (die) Modul(e) mit den Anpassungen aus, die Sie exportieren möchten. (Es werden Ihnen nur Module mit Anpassungen zur Auswahl angezeigt.) <br><br>Klicken Sie auf <b>Exportieren</b>, um eine .zip-Datei für das Paket mit den Anpassungen zu erstellen.  Diese Datei kann in einer anderen Instanz mit dem <b>Modul-Lader</b> hochgeladen werden.',
        'exportCustomBtn'=>'Klicken Sie auf <b>Exportieren</b>, um eine .zip-Datei für das Paket zu erstellen, welche die zu exportierenden Anpassungen enthält.
',
        'name'=>'Der <b>Name</b> des Pakets wird im Modul-Lader angezeigt, nachdem das Paket für die Installation in Studio hochgeladen wurde.',
        'author'=>'Der <b>Autor</b> ist der Name des Erstellers des Pakets. Dabei kann es sich um eine Einzelperson oder eine Firma handeln.<br><br>Der Autor wird im Modul-Lader angezeigt, nachdem das Paket für die Installation in Studio hochgeladen wurde.
',
        'description'=>'Die <b>Beschreibung</b> des Pakets wird im Modul-Lader angezeigt, nachdem das Paket für die Installation in Studio hochgeladen wurde.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Willkommen im <b>Entwickler-Tools</b>-Bereich. <br/> <br/> Verwenden Sie die Tools in diesem Bereich für die Erstellung und Verwaltung von Standard- und benutzerdefinierten Modulen und Feldern.',
        'studioBtn'	=> 'Verwenden Sie <b>Studio</b> zum Anpassen von installierten Modulen durch Änderungen an der Feld-Anordnung, für die Auswahl der zur Verfügung gestellten Felder und die Erstellung benutzerdefinierter Datenfelder.',
        'mbBtn'		=> 'Verwenden Sie den <b>Module-Ersteller</b>, um neue Module zu erstellen.',
        'appBtn' 	=> 'Verwenden Sie den Anwendungsmodus, um die verschiedenen Eigenschaften des Programms anzupassen, z. B., wie viele TPS-Berichte auf der Startseite angezeigt werden',
        'backBtn'	=> 'Zurückkehren zum vorherigen Schritt.',
        'studioHelp'=> 'Verwenden Sie <b>Studio</b>, um installierte Module anpassen.',
        'moduleBtn'	=> 'Hier klicken, um dieses Modul zu bearbeiten.',
        'moduleHelp'=> 'Wählen Sie die Modul-Komponente, die Sie bearbeiten möchten',
        'fieldsBtn'	=> 'Bestimmen Sie, welche Informationen im Modul gespeichert werden, indem Sie die <b>Felder</b> im Modul anpassen. <br/><br/>Sie können hier benutzerdefinierte Felder bearbeiten und erstellen.',
        'layoutsBtn'=> 'Bearbeiten Sie die <b>Layouts</b> für die Ansichten für Bearbeiten, Details, Listen und Suchen.',
        'subpanelBtn'=> 'Bestimmen Sie, welche Informationen in den Sub-Panels dieses Moduls gezeigt werden.',
        'layoutsHelp'=> 'Wählen Sie ein <b>Layout zur Bearbeitung</b>aus. <br/<br/>Um das Layout, in dem sich Datenfelder zur Eingabe von Daten befinden, zu ändern, klicken Sie auf <b>Bearbeitungsansicht</b>. <br/><br/>Um das Layout zu ändern, das die in dieser Ansicht eingegebenen Daten anzeigt, klicken Sie auf <b>Detailansicht</b>. <br/><br/>Um Veränderungen an den in der Standard-Liste angezeigten Spalten vorzunehmen, klicken Sie auf <b>Listenansicht</b>. <br/><br/>Um das Layout der einfachen und erweiterten Suche zu ändern, klicken Sie auf <b>Suche</b>.',
        'subpanelHelp'=> 'Wählen Sie ein <b>Sub-Panel</b> zur Bearbeitung aus.',
        'searchHelp' => 'Wählen Sie ein <b>Suche</b>-Layout zur Bearbeitung aus.',
        'labelsBtn'	=> 'Bearbeiten Sie die <b>Bezeichnungen</b>, um die Werte in diesem Modul anzuzeigen.',
        'newPackage'=>'Klicken Sie auf <b>Neues Paket</b>, um ein neues Paket erstellen.',
        'mbHelp'    => '<b>Willkommen beim Modul-Ersteller.</b><br/><br/>Verwenden Sie den <b>Modul-Ersteller</b> zum Erstellen von Paketen mit benutzerdefinierten Modulen, basierend auf Standard- oder benutzerdefinierten Objekten. <br/><br/>Klicken Sie zuerst auf <b>Neues Paket</b>, um ein neues Paket zu erstellen oder wählen Sie ein Paket zur Bearbeitung aus.<br/><br/> Ein <b>Paket</b> fungiert als Behälter für benutzerdefinierte Module, die Teil eines Projekts sind. Das Das Paket kann ein oder mehrere benutzerdefinierte Module enthalten, die miteinander oder mit Modulen in der Anwendung verknüpft sind. <br/><br/>Beispiele: Sie möchten ein Paket mit einem benutzerdefinierten Modul erstellen, das mit dem Standard-Firmenmodul verknüpft ist. Oder vielleicht möchten Sie ein Paket mit mehreren neuen Modulen erstellen, die wie in einem Projekt zusammenarbeiten, und die miteinander und auch mit den Modulen in der Anwendung verknüpft sind.',
        'exportBtn' => 'Klicken Sie auf <b>Anpassungen exportieren</b>, um ein Paket zu erstellen, das die in Studio vorgenommenen Anpassungen für bestimmte Module enthält.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Dropdown-Listen bearbeiten',

//ASSISTANT
'LBL_AS_SHOW' => 'Assistent in Zukunft anzeigen.',
'LBL_AS_IGNORE' => 'Assistent in Zukunft ignorieren.',
'LBL_AS_SAYS' => 'Assistent sagt:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Modul-Ersteller',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Dropdown-Listen bearbeiten',
'LBL_EDIT_DROPDOWN'=>'Dropdown-Liste bearbeiten',
'LBL_DEVELOPER_TOOLS' => 'Entwickler-Tools',
'LBL_SUGARPORTAL' => 'Sugar-Portal-Editor',
'LBL_SYNCPORTAL' => 'Portal synchronisieren',
'LBL_PACKAGE_LIST' => 'Paketliste',
'LBL_HOME' => 'Startseite',
'LBL_NONE'=>'-Keine-',
'LBL_DEPLOYE_COMPLETE'=>'Bereitstellung fertiggestellt',
'LBL_DEPLOY_FAILED'   =>'Es trat ein Fehler während der Bereitstellung auf. Ihr Paket wurde eventuell nicht richtig installiert',
'LBL_ADD_FIELDS'=>'Benutzerdefinierte Felder hinzufügen',
'LBL_AVAILABLE_SUBPANELS'=>'Verfügbare Sub-Panels',
'LBL_ADVANCED'=>'Erweitert',
'LBL_ADVANCED_SEARCH'=>'Erweiterte Suche',
'LBL_BASIC'=>'Einfach',
'LBL_BASIC_SEARCH'=>'Einfache Suche',
'LBL_CURRENT_LAYOUT'=>'Aktuelles Layout',
'LBL_CURRENCY' => 'Währung',
'LBL_CUSTOM' => 'Benutzerdefiniert',
'LBL_DASHLET'=>'Sugar-Dashlet',
'LBL_DASHLETLISTVIEW'=>'Sugar-Dashlet-Listenansicht',
'LBL_DASHLETSEARCH'=>'Sugar-Dashlet-Suche',
'LBL_POPUP'=>'Pop-up-Ansicht',
'LBL_POPUPLIST'=>'Pop-up-Listenansicht',
'LBL_POPUPLISTVIEW'=>'Pop-up-Listenansicht',
'LBL_POPUPSEARCH'=>'Pop-up-Suche',
'LBL_DASHLETSEARCHVIEW'=>'Sugar-Dashlet-Suche',
'LBL_DISPLAY_HTML'=>'HTML Code zeigen',
'LBL_DETAILVIEW'=>'Detailansicht',
'LBL_DROP_HERE' => '[Hierher ziehen]',
'LBL_EDIT'=>'Bearbeiten',
'LBL_EDIT_LAYOUT'=>'Layout bearbeiten',
'LBL_EDIT_ROWS'=>'Zeilen bearbeiten',
'LBL_EDIT_COLUMNS'=>'Spalten bearbeiten',
'LBL_EDIT_LABELS'=>'Bezeichnungen bearbeiten',
'LBL_EDIT_PORTAL'=>'Portal bearbeiten für',
'LBL_EDIT_FIELDS'=>'Felder bearbeiten',
'LBL_EDITVIEW'=>'Bearbeitungsansicht',
'LBL_FILTER_SEARCH' => "Suchen",
'LBL_FILLER'=>'(Füller)',
'LBL_FIELDS'=>'Felder',
'LBL_FAILED_TO_SAVE' => 'Fehler beim Speichern',
'LBL_FAILED_PUBLISHED' => 'Fehler beim Veröffentlichen',
'LBL_HOMEPAGE_PREFIX' => 'Mein',
'LBL_LAYOUT_PREVIEW'=>'Layout-Vorschau',
'LBL_LAYOUTS'=>'Layouts',
'LBL_LISTVIEW'=>'Listenansicht',
'LBL_RECORDVIEW'=>'Datensatz-Ansicht',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Neues Paket',
'LBL_NEW_PANEL'=>'Neuer Bereich',
'LBL_NEW_ROW'=>'Neue Zeile',
'LBL_PACKAGE_DELETED'=>'Paket gelöscht',
'LBL_PUBLISHING' => 'Veröffentlichen ...',
'LBL_PUBLISHED' => 'Veröffentlicht',
'LBL_SELECT_FILE'=> 'Datei auswählen',
'LBL_SAVE_LAYOUT'=> 'Layout speichern',
'LBL_SELECT_A_SUBPANEL' => 'Ein Subpanel auswählen',
'LBL_SELECT_SUBPANEL' => 'Subpanel auswählen',
'LBL_SUBPANELS' => 'Sub-Panels',
'LBL_SUBPANEL' => 'Sub-Panel',
'LBL_SUBPANEL_TITLE' => 'Name:',
'LBL_SEARCH_FORMS' => 'Suchen',
'LBL_STAGING_AREA' => 'Arbeitsbereich (Elemente hierher ziehen)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar Felder (klicken Sie auf das Element um es zum Arbeitsbereich hinzuzufügen)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (klicken Sie auf das Element um es zum Arbeitsbereich hinzuzufügen)',
'LBL_TOOLBOX' => 'Toolbox',
'LBL_VIEW_SUGAR_FIELDS' => 'Sugar Felder anzeigen',
'LBL_VIEW_SUGAR_BIN' => 'Sugar Bin anzeigen',
'LBL_QUICKCREATE' => 'Schnellerstellung',
'LBL_EDIT_DROPDOWNS' => 'Globale Dropdown-Liste bearbeiten',
'LBL_ADD_DROPDOWN' => 'Neue globale Dropdown-Liste hinzufügen',
'LBL_BLANK' => '-leer-',
'LBL_TAB_ORDER' => 'Registerkarten-Reihenfolge',
'LBL_TAB_PANELS' => 'Anzeige der Bereiche als Tabs',
'LBL_TAB_PANELS_HELP' => 'Wenn Registerkarten aktiviert sind, verwenden Sie die Dropdown-Liste "Typ" <br/>für die einzelnen Abschnitte, um zu bestimmen, wie diese dargestellt werden sollen (als Registerkarte oder Bereich)',
'LBL_TABDEF_TYPE' => 'Anzeigetyp',
'LBL_TABDEF_TYPE_HELP' => 'Wählen Sie, wie dieser Abschnitt angezeigt werden soll. Diese Option hat nur dann einen Effekt, wenn Registerkarten in dieser Ansicht aktiviert sind.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Registerkarte',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Bereich',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Wählen Sie "Bereich", um diesen Abschnitt in der Ansicht dieses Layouts anzuzeigen. Wählen Sie "Registerkarte", um diesen Abschnitt in einer eigenen Registerkarte im Layout anzuzeigen. Wenn "Registerkarte" ausgewählt wird, werden die folgenden als Bereiche konfigurierten Abschnitte innerhalb dieser Registerkarte dargestellt. <br/>Für den nächsten Abschnitt, der als Registerkarte konfiguriert wird, wird eine neue Registerkarte eingerichtet. Wenn für einen Abschnitt nach dem ersten Abschnitt die Option "Registerkarte" ausgewählt wird, wird der erste Abschnitt auf jeden Fall als Registerkarte dargestellt.',
'LBL_TABDEF_COLLAPSE' => 'Einklappen',
'LBL_TABDEF_COLLAPSE_HELP' => 'Wählen, um den Bereich standardmäßig eingeklappt anzuzeigen.',
'LBL_DROPDOWN_TITLE_NAME' => 'Name',
'LBL_DROPDOWN_LANGUAGE' => 'Sprache',
'LBL_DROPDOWN_ITEMS' => 'Listen-Elemente',
'LBL_DROPDOWN_ITEM_NAME' => 'Elementname',
'LBL_DROPDOWN_ITEM_LABEL' => 'Anzeigebezeichnung',
'LBL_SYNC_TO_DETAILVIEW' => 'Mit Detailansicht synchronisieren',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Diese Option auswählen, um die Felder von der Bearbeitungsansicht in die Detailansicht zu kopieren. Die Felder und deren Position in der Bearbeitungsansicht werden<br> daraufhin synchronisiert und automatisch in der Detailansicht gespeichert, wenn auf "Speichern" bzw. "Speichern % Anwenden" in der Bearbeitungsansicht geklickt wird. <br>In der Detailansicht können keine Änderungen am Layout vorgenommen werden.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Die Detailansicht wird mit der Bearbeitungsansicht synchronisiert.<br>Felder und Layout in der Detailansicht sind identisch mit der Bearbeitungsansicht.<br>Änderungen in der Detailansicht können nicht von dieser Seite aus gespeichert oder angewendet werden. Verwenden Sie die Bearbeitungsansicht für Änderungen bzw. Aufhebung der Synchronisierung in der Bearbeitungsansicht. ',
'LBL_COPY_FROM' => 'Kopieren von',
'LBL_COPY_FROM_EDITVIEW' => 'Kopieren von der Bearbeitungsansicht',
'LBL_DROPDOWN_BLANK_WARNING' => 'Werte für Feldname und Elementname sind Pflichtwerte. Um ein leeres Element hinzuzufügen, "Hinzufügen" wählen, ohne einen Elementnamen oder Feldnamen einzugeben.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Dieser Schlüssel wird in dieser Liste bereits verwendet',
'LBL_DROPDOWN_LIST_EMPTY' => 'Die Liste muss mindestens einen aktivierten Artikel enthalten',
'LBL_NO_SAVE_ACTION' => 'Die Speicher-Aktion konnte für diese Ansicht nicht gefunden werden.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: schlecht zsammengestelltes Dokument',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Ein Kombinationsfeld. Ein Kombinationsfeld ist eine Zusammenstellung von einzelnen Feldern. Zum Beispiel: "Adresse" ist eine Kombination aus "Straße", "Stadt", "PLZ", "Bundesland" und "Land".<br><br>Doppelklicken Sie auf ein Kombinationsfeld, um zu sehen, aus welchen Feldern es besteht.',
'LBL_COMBO_FIELD_CONTAINS' => 'enthält:',

'LBL_WIRELESSLAYOUTS'=>'Mobile Layouts',
'LBL_WIRELESSEDITVIEW'=>'Mobile Bearbeitungsansicht',
'LBL_WIRELESSDETAILVIEW'=>'Mobile Detailansicht',
'LBL_WIRELESSLISTVIEW'=>'Mobile Listenansicht',
'LBL_WIRELESSSEARCH'=>'Mobile Suche',

'LBL_BTN_ADD_DEPENDENCY'=>'Abhängigkeit hinzufügen',
'LBL_BTN_EDIT_FORMULA'=>'Formel bearbeiten',
'LBL_DEPENDENCY' => 'Abhängigkeit',
'LBL_DEPENDANT' => 'Abhängig',
'LBL_CALCULATED' => 'Berechneter Wert',
'LBL_READ_ONLY' => 'Schreibgeschützt',
'LBL_FORMULA_BUILDER' => 'Formel-Assistent',
'LBL_FORMULA_INVALID' => 'Ungültige Formel',
'LBL_FORMULA_TYPE' => 'Die Formel muss folgender Typ sein: ',
'LBL_NO_FIELDS' => 'Keine Felder gefunden',
'LBL_NO_FUNCS' => 'Keine Funktionen gefunden',
'LBL_SEARCH_FUNCS' => 'Suchfunktionen...',
'LBL_SEARCH_FIELDS' => 'Suchfelder...',
'LBL_FORMULA' => 'Formel',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Abhängig',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Verschieben Sie Optionen von der Liste links neben den verfügbaren Optionen in die Abhängigkeits-Dropdown-Liste rechts, um diese Optionen verfügbar zu machen, wenn die übergeordnete Option ausgewählt wird. Wenn es keine untergeordnete Elemente gibt, wird diese Dropdown-Liste nicht angezeigt.',
'LBL_AVAILABLE_OPTIONS' => 'Verfügbare Optionen',
'LBL_PARENT_DROPDOWN' => 'Übergeordnete Dropdown-Liste',
'LBL_VISIBILITY_EDITOR' => 'Sichtbarkeits-Editor',
'LBL_ROLLUP' => 'Rollup',
'LBL_RELATED_FIELD' => 'Verknüptes Feld',
'LBL_CONFIG_PORTAL_URL'=>'URL zu einem benutzerdefinierten Logo. Die empfohlene Auflösung ist 163 x 18 Pixel.',
'LBL_PORTAL_ROLE_DESC' => 'Löschen Sie diese Rolle nicht. Die Kunden-Selbstbedienungsportal-Rolle ist eine vom System beim Sugar-Portal-Aktivierungsprozess generierte Rolle. Verwenden Sie Zugriffskontrollen in dieser Rolle, um Fehlermeldungen, Tickets und Wissensdatenbank-Module im Portal zu aktivieren oder deaktivieren. Ändern Sie keine anderen Kontrollen für diese Rolle, um ein unvorhersehbares Systemverhalten zu vermeiden. Falls diese Rolle zufälig gelöscht wurde, können Sie sie durch Deaktivieren und erneutes Aktivieren von Sugar-Portal erneut erstellen.',

//RELATIONSHIPS
'LBL_MODULE' => 'Modul',
'LBL_LHS_MODULE'=>'Primäres Modul',
'LBL_CUSTOM_RELATIONSHIPS' => '* Beziehung erstellt in Studio',
'LBL_RELATIONSHIPS'=>'Beziehungen',
'LBL_RELATIONSHIP_EDIT' => 'Beziehung bearbeiten',
'LBL_REL_NAME' => 'Name',
'LBL_REL_LABEL' => 'Bezeichnung',
'LBL_REL_TYPE' => 'Typ',
'LBL_RHS_MODULE'=>'Verknüpfte Module',
'LBL_NO_RELS' => 'Keine Beziehungen',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Optionale Bedingung' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Spalte',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Wert',
'LBL_SUBPANEL_FROM'=>'Sub-Panel von',
'LBL_RELATIONSHIP_ONLY'=>'Für diese Beziehung werden keine sichtbaren Elemente erstellt, da es bereits eine sichtbare Beziehung zwischen diesen Modulen gibt.',
'LBL_ONETOONE' => 'Eins zu Eins',
'LBL_ONETOMANY' => 'Eins zu Viele',
'LBL_MANYTOONE' => 'Viele zu Einem',
'LBL_MANYTOMANY' => 'Viele zu Viele',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Wählen Sie eine Funktion oder Komponente aus.',
'LBL_QUESTION_MODULE1' => 'Wählen Sie ein Modul aus.',
'LBL_QUESTION_EDIT' => 'Wählen Sie ein Modul zum Bearbeiten aus.',
'LBL_QUESTION_LAYOUT' => 'Wählen Sie ein Layout zum Bearbeiten aus.',
'LBL_QUESTION_SUBPANEL' => 'Wählen Sie ein Sub-Panel zum Bearbeiten aus.',
'LBL_QUESTION_SEARCH' => 'Wählen Sie ein Suche-Layout zum Bearbeiten aus.',
'LBL_QUESTION_MODULE' => 'Wählen Sie eine Modulkomponente zum Bearbeiten aus.',
'LBL_QUESTION_PACKAGE' => 'Wählen Sie ein Paket zum Bearbeiten aus, oder erstellen ein neues.',
'LBL_QUESTION_EDITOR' => 'Wählen Sie ein Werkzeug aus.',
'LBL_QUESTION_DROPDOWN' => 'Wählen Sie eine Dropdown-Liste zum Bearbeiten aus, oder erstellen Sie eine neue.',
'LBL_QUESTION_DASHLET' => 'Wählen Sie ein Dashlet-Layout zum Bearbeiten.',
'LBL_QUESTION_POPUP' => 'Wählen Sie ein Pop-up-Layout zum Bearbeiten.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Verbinden mit',
'LBL_NAME'=>'Name',
'LBL_LABELS'=>'Bezeichnungen',
'LBL_MASS_UPDATE'=>'Massenänderung',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'Modul',
'LBL_DEFAULT_VALUE'=>'Standardwert',
'LBL_REQUIRED'=>'Erforderlich',
'LBL_DATA_TYPE'=>'Typ',
'LBL_HCUSTOM'=>'Benutzerdefiniert',
'LBL_HDEFAULT'=>'Standard',
'LBL_LANGUAGE'=>'Sprache:',
'LBL_CUSTOM_FIELDS' => '* Benutzerdefinierte Felder',

//SECTION
'LBL_SECTION_EDLABELS' => 'Bezeichnungen bearbeiten',
'LBL_SECTION_PACKAGES' => 'Pakete',
'LBL_SECTION_PACKAGE' => 'Paket',
'LBL_SECTION_MODULES' => 'Module',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Dropdown-Listen',
'LBL_SECTION_PROPERTIES' => 'Eigenschaften',
'LBL_SECTION_DROPDOWNED' => 'Dropdown-Liste bearbeiten',
'LBL_SECTION_HELP' => 'Hilfe',
'LBL_SECTION_ACTION' => 'Aktion',
'LBL_SECTION_MAIN' => 'Wichtig',
'LBL_SECTION_EDPANELLABEL' => 'Bereichsbezeichung bearbeiten',
'LBL_SECTION_FIELDEDITOR' => 'Feld bearbeiten',
'LBL_SECTION_DEPLOY' => 'Anwenden',
'LBL_SECTION_MODULE' => 'Modul',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Sichtbarkeit bearbeiten',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Standard',
'LBL_HIDDEN'=>'Ausgeblendet',
'LBL_AVAILABLE'=>'Verfügbar',
'LBL_LISTVIEW_DESCRIPTION'=>'Unten werden drei Spalten angezeigt. Die Spalte <b></b> Spalte enthält die Felder, die in der Listenansicht standardmäßig angezeigt werden, die Spalte <b>Zusätzlich</b> enthält jene Felder, die der Benutzer wählen kann, um eine eigene Ansicht zu erstellen, und die Spalte <b>Verfügbar</b> enthält jene Felder, die für Sie als Admin verfügbar sind, um sie entweder zur Spalte "Standard" oder zur Spalte "Verfügbar" hinzuzufügen.',
'LBL_LISTVIEW_EDIT'=>'Listenansicht-Editor',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Vorschau',
'LBL_MB_RESTORE'=>'Wiederherstellen',
'LBL_MB_DELETE'=>'Löschen',
'LBL_MB_COMPARE'=>'Vergleichen',
'LBL_MB_DEFAULT_LAYOUT'=>'Standard-Layout',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Hinzufügen',
'LBL_BTN_SAVE'=>'Speichern',
'LBL_BTN_SAVE_CHANGES'=>'Änderungen speichern',
'LBL_BTN_DONT_SAVE'=>'Änderungen verwerfen',
'LBL_BTN_CANCEL'=>'Abbrechen',
'LBL_BTN_CLOSE'=>'Schließen',
'LBL_BTN_SAVEPUBLISH'=>'Speichern & Anwenden',
'LBL_BTN_NEXT'=>'Weiter',
'LBL_BTN_BACK'=>'Zurück',
'LBL_BTN_CLONE'=>'Klonen',
'LBL_BTN_COPY' => 'Kopieren',
'LBL_BTN_COPY_FROM' => 'Kopieren von...',
'LBL_BTN_ADDCOLS'=>'Spalten hinzufügen',
'LBL_BTN_ADDROWS'=>'Zeilen hinzufügen',
'LBL_BTN_ADDFIELD'=>'Feld hinzufügen',
'LBL_BTN_ADDDROPDOWN'=>'Dropdown-Liste hinzufügen',
'LBL_BTN_SORT_ASCENDING'=>'Aufsteigend sortieren',
'LBL_BTN_SORT_DESCENDING'=>'Absteigend sortieren',
'LBL_BTN_EDLABELS'=>'Bezeichnungen bearbeiten',
'LBL_BTN_UNDO'=>'Rückgängig',
'LBL_BTN_REDO'=>'Wiederholen',
'LBL_BTN_ADDCUSTOMFIELD'=>'Benutzerdefiniertes Feld hinzufügen',
'LBL_BTN_EXPORT'=>'Anpassungen exportieren',
'LBL_BTN_DUPLICATE'=>'Duplizieren',
'LBL_BTN_PUBLISH'=>'Veröffentlichen',
'LBL_BTN_DEPLOY'=>'Anwenden',
'LBL_BTN_EXP'=>'Exportieren',
'LBL_BTN_DELETE'=>'Löschen',
'LBL_BTN_VIEW_LAYOUTS'=>'Layouts anzeigen',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Mobile Layouts anzeigen',
'LBL_BTN_VIEW_FIELDS'=>'Felder anzeigen',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Beziehungen anzeigen',
'LBL_BTN_ADD_RELATIONSHIP'=>'Beziehung hinzufügen',
'LBL_BTN_RENAME_MODULE' => 'Modulname ändern',
'LBL_BTN_INSERT'=>'Einfügen',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Fehler: Feld bereits vorhanden',
'ERROR_INVALID_KEY_VALUE'=> "Fehler: Ungültiger Schlüsselwert: [']",
'ERROR_NO_HISTORY' => 'Kein Verlauf gefunden',
'ERROR_MINIMUM_FIELDS' => 'Dieses Layout muss mindestens einen Feld enthalten',
'ERROR_GENERIC_TITLE' => 'Ein Fehler ist aufgetreten',
'ERROR_REQUIRED_FIELDS' => 'Möchten Sie fortfahren? Es fehlen folgende Pflichtfelder im Layout:  ',
'ERROR_ARE_YOU_SURE' => 'Möchten Sie fortfahren?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Die folgenden Felder sind berechnete Werte und werden nicht in der mobilen Bearbeitungsansicht von SugarCRM erneut berechnet:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Die folgenden Felder sind berechnete Werte und werden nicht in der Bearbeitungsansicht des SugarCRM-Portals erneut berechnet:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Die folgenden Module sind deaktiviert:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Falls Sie diese für das Portal aktivieren möchten, klicken Sie <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">hier</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Portal konfigurieren',
    'LBL_PORTAL_THEME' => 'Design-Portal',
    'LBL_PORTAL_ENABLE' => 'Aktivieren',
    'LBL_PORTAL_SITE_URL' => 'Ihre Portal-Seite ist verfügbar unter:',
    'LBL_PORTAL_APP_NAME' => 'Anwendungsname',
    'LBL_PORTAL_LOGO_URL' => 'Logo-URL',
    'LBL_PORTAL_LIST_NUMBER' => 'Anzahl der in der Liste dargestellten Datensätze',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Anzahl der in der Detailansicht angezeigen Felder',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Anzahl der dargestellten Ergebnisse bei der globalen Suche',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Standard zugewiesener Benutzer für neue Portal-Registrierungen',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Portal-Layouts',
'LBL_SYNCP_WELCOME'=>'Bitte die URL für Ihre Portal-Instanz eintragen, die Sie aktualisieren möchten.',
'LBL_SP_UPLOADSTYLE'=>'Bitte eine Stylesheet von Ihrem Computer auswählen.<br> Diese wird bei der nächsten Synchronisierung im Sugar-Portal implementiert.',
'LBL_SP_UPLOADED'=> 'Hochgeladen',
'ERROR_SP_UPLOADED'=>'Bitte sicherstellen, dass Sie eine css-Stylesheet hochladen.',
'LBL_SP_PREVIEW'=>'Hier ist eine Vorschau, wie Ihre Stylesheet aussehen wird.',
'LBL_PORTALSITE'=>'Sugar-Portal-URL: ',
'LBL_PORTAL_GO'=>'Start',
'LBL_UP_STYLE_SHEET'=>'Stylesheet hochladen',
'LBL_QUESTION_SUGAR_PORTAL' => 'Wählen Sie ein Sugar-Portal-Layout zum Bearbeiten.',
'LBL_QUESTION_PORTAL' => 'Wählen Sie ein Portal-Layout zum Bearbeiten.',
'LBL_SUGAR_PORTAL'=>'Sugar-Portal-Editor',
'LBL_USER_SELECT' => '--Auswählen--',

//PORTAL PREVIEW
'LBL_CASES'=>'Tickets',
'LBL_NEWSLETTERS'=>'Newsletter',
'LBL_BUG_TRACKER'=>'Fehlerverfolgung',
'LBL_MY_ACCOUNT'=>'Mein Konto',
'LBL_LOGOUT'=>'Abmelden',
'LBL_CREATE_NEW'=>'Neu erstellen',
'LBL_LOW'=>'Niedrig',
'LBL_MEDIUM'=>'Mittel',
'LBL_HIGH'=>'Hoch',
'LBL_NUMBER'=>'Nummer:',
'LBL_PRIORITY'=>'Priorität:',
'LBL_SUBJECT'=>'Betreff',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Paketname:',
'LBL_MODULE_NAME'=>'Modulname:',
'LBL_MODULE_NAME_SINGULAR' => 'Eindeutiger Modulname:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Beschreibung:',
'LBL_KEY'=>'Schlüssel:',
'LBL_ADD_README'=>'Liesmich',
'LBL_MODULES'=>'Module:',
'LBL_LAST_MODIFIED'=>'Geändert am:',
'LBL_NEW_MODULE'=>'Neues Modul',
'LBL_LABEL'=>'Plural-Bezeichnung',
'LBL_LABEL_TITLE'=>'Bezeichnung',
'LBL_SINGULAR_LABEL' => 'Singular-Bezeichnung',
'LBL_WIDTH'=>'Breite',
'LBL_PACKAGE'=>'Paket:',
'LBL_TYPE'=>'Typ:',
'LBL_TEAM_SECURITY'=>'Team-Sicherheit',
'LBL_ASSIGNABLE'=>'Zuweisbar',
'LBL_PERSON'=>'Person',
'LBL_COMPANY'=>'Firma',
'LBL_ISSUE'=>'Problem',
'LBL_SALE'=>'Verkauf',
'LBL_FILE'=>'Datei',
'LBL_NAV_TAB'=>'Navigations-Registerkarte',
'LBL_CREATE'=>'Erstellen',
'LBL_LIST'=>'Liste',
'LBL_VIEW'=>'Ansicht',
'LBL_LIST_VIEW'=>'Listenansicht',
'LBL_HISTORY'=>'Verlauf ansehen',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Standardlayout wiederherstellen',
'LBL_ACTIVITIES'=>'Aktivitäten-Stream',
'LBL_SEARCH'=>'Suchen',
'LBL_NEW'=>'Neu',
'LBL_TYPE_BASIC'=>'Basis',
'LBL_TYPE_COMPANY'=>'Firma',
'LBL_TYPE_PERSON'=>'Person',
'LBL_TYPE_ISSUE'=>'Problem',
'LBL_TYPE_SALE'=>'Vertrieb',
'LBL_TYPE_FILE'=>'Datei',
'LBL_RSUB'=>'Das ist das Sub-Panel, das in Ihrem Modul angezeigt wird',
'LBL_MSUB'=>'Dies ist das Sub-Panel, das von Ihrem Modul im verknüpften Modul angezeigt wird',
'LBL_MB_IMPORTABLE'=>'Import zulassen',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'sichtbar',
'LBL_VE_HIDDEN'=>'versteckt',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] wurde gelöscht',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Anpassungen exportieren',
'LBL_EC_NAME'=>'Paketname:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Beschreibung:',
'LBL_EC_KEY'=>'Schlüssel:',
'LBL_EC_CHECKERROR'=>'Wählen Sie ein Modul aus.',
'LBL_EC_CUSTOMFIELD'=>'Angepasste(s) Feld(er)',
'LBL_EC_CUSTOMLAYOUT'=>'Angepasste(s) Layout(s)',
'LBL_EC_CUSTOMDROPDOWN' => 'Angepasste(s) Dropdown(s)',
'LBL_EC_NOCUSTOM'=>'Kein Modul wurde angepasst.',
'LBL_EC_EXPORTBTN'=>'Exportieren',
'LBL_MODULE_DEPLOYED' => 'Das Modul wurde angewendet.',
'LBL_UNDEFINED' => 'undefiniert',
'LBL_EC_CUSTOMLABEL'=>'Benutzerdefinierte Bezeichnung(en)',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Daten nicht erfolgreich empfangen',
'LBL_AJAX_TIME_DEPENDENT' => 'Eine zeitabhängige Aktion wird gerade durchgeführt. Bitte warten und es in ein paar Sekunden erneut versuchen.',
'LBL_AJAX_LOADING' => 'Laden...',
'LBL_AJAX_DELETING' => 'Löschvorgang läuft...',
'LBL_AJAX_BUILDPROGRESS' => 'Aufbau läuft...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Bereitstellung läuft...',
'LBL_AJAX_FIELD_EXISTS' =>'Der von Ihnen eingegebene Feldname existiert bereits. Bitte geben Sie einen neuen Feldnamen ein.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Sind Sie sicher, dass Sie dieses Paket löschen möchten? Dadurch werden alle mit diesem Paket verbundenen Dateien permanent gelöscht.',
'LBL_JS_REMOVE_MODULE' => 'Sind Sie sicher, dass Sie dieses Modul löschen möchten? Dadurch werden alle mit diesem Modul verbundenen Dateien permanent gelöscht.',
'LBL_JS_DEPLOY_PACKAGE' => 'Alle Änderungen in Studio werden überschrieben, wenn diese Modul wieder angewendet wird. Möchten Sie dennoch fortfahren?',

'LBL_DEPLOY_IN_PROGRESS' => 'Paket wird bereitsgestellt',
'LBL_JS_VALIDATE_NAME'=>'Name – Muss mit einem Buchstaben beginnen und darf nur aus Buchstaben, Zahlen und Unterstrichen bestehen. Keine Leerzeichen oder Sonderzeichen sind erlaubt.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Paketschlüssel existiert bereits',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Paketname existiert bereits',
'LBL_JS_PACKAGE_NAME'=>'Paketname – Muss mit einem Buchstaben beginnen und darf nur aus Buchstaben, Zahlen und Unterstrichen bestehen. Keine Leerzeichen oder Sonderzeichen sind erlaubt.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Schlüssel - müssen alphanumerisch sein und mit einem Buchstaben beginnen.',
'LBL_JS_VALIDATE_KEY'=>'Schlüssel - Muss alphanumerisch ohne Leerzeichen sein und mit einem Buchstaben beginnen.',
'LBL_JS_VALIDATE_LABEL'=>'Bitte geben Sie eine Bezeichnung ein, die als Anzeigename für dieses Modul verwendet wird',
'LBL_JS_VALIDATE_TYPE'=>'Bitte wählen Sie den Modultyp, den Sie erstellen möchten, aus der Liste oben aus.',
'LBL_JS_VALIDATE_REL_NAME'=>'Name – Muss alphanumerisch ohne Leerzeichen sein',
'LBL_JS_VALIDATE_REL_LABEL'=>'Bezeichnung – Bitte wählen Sie eine Bezeichnung, die oberhalb des Sub-Panels angezeigt werden soll',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Sind Sie sicher, dass Sie diese benötigte Dropdown-Liste entfernen möchten? Die Funktionalität Ihres Systems könnte dadurch beeinträchtigt werden.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Sind Sie sicher, dass Sie diese Auswahl entfernen möchten? Das Löschen der Phasen "Gewonnen" oder "Verloren" beeinträchtigt die Prognosen-Funktionalität',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Das Löschen des Wertes "Neu" führt dazu, dass die Umsatzposten nicht mehr richtig funktionieren. Möchten Sie dennoch fortfahren?',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Das Löschen des Wertes "In Bearbeitung" führt dazu, dass die Umsatzposten nicht mehr richtig funktionieren. Möchten Sie dennoch fortfahren.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Sind Sie sicher, dass Sie diese Auswahl entfernen möchten? Das Löschen der Phase "Gewonnen/Geschlossen" beeinträchtigt die Prognosen-Funktionalität',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Sind Sie sicher, dass Sie diese Auswahl entfernen möchten? Das Löschen der Phase "Verloren/Geschlossen" beeinträchtigt die Prognosen-Funktionalität',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Sind Sie sicher, dass Sie diese Beziehung löschen möchten?<br>Hinweis: Dieser Vorgang kann einige Minuten dauern.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Dies macht diese Verknüpfung permanent. Sind Sie sicher, dass Sie die Beziehung anwenden möchten?',
'LBL_CONFIRM_DONT_SAVE' => 'Seit dem letzten Speichern wurden Änderungen vorgenommen. Möchten Sie jetzt speichern?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Änderungen speichern?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Daten könnten abgeschnitten werden und der Vorgang kann nicht rückgängig gemacht werden. Möchten Sie dennoch fortfahren?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Wählen Sie den geeigneten Datentyp, basierend auf der Art der Daten, die in dieses Feld eingegeben werden sollen.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Konfigurieren Sie den Bereich so, dass er nach vollständigen Texten durchsucht werden kann.',
'LBL_POPHELP_FTS_FIELD_BOOST' => '„Boosting“ oder Verstärkung, ist der Prozess der Stärkung der Relevanz der Felder eines Datensatzes.<br />Feldern mit höherer Verstärkung wird bei einer Suche größeres Gewicht gegeben. Wenn eine Suche durchgeführt wird, erscheinen die übereinstimmenden Datensätze mit Feldern mit einem größeren Gewicht höher auf der Liste der Suchergebnisse.<br /> Der Standardwert ist 1.0 und steht für eine neutrale Verstärkung. Um einen positiven Schub zu erreichen, können alle Float-Werte höher als 1 benutzt werden. Verwenden Sie für eine negative Verstärkung Werte niedriger als 1. Zum Beispiel verstärlt ein Wert von 1,35 ein Feld um 135 %. Mit einem Wert von 0,60 ensteht dagegen ein negativer Impuls.<br /> Beachten Sie bitte, dass man in früheren Versionen eine Neuindizierte Volltextsuche durchführen musste. Dies ist nicht mehr erforderlich.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Ja</b>: Das Feld wird beim Importieren inkludiert.<br><b>Nein</b>: Das Feld wird bei Importen nicht berücksichtigt.<br><b>Erforderlich</b>: Bei jedem Import MUSS ein Wert für dieses Feld vorhanden sein.',
'LBL_POPHELP_PII'=>'Dieses Feld wird automatisch für ein Audit markiert und ist in der Ansicht „Persönliche Info“ verfügbar.<br>Persönliche Informationsfelder können auch dauerhaft gelöscht werden, wenn der Datensatz mit einem Datenschutz-Löschantrag in Verbindung steht.<br>Die Löschung erfolgt über das Datenschutz-Modul und kann von Administratoren oder Benutzern in der Rolle Datenschutz-Manager ausgeführt werden.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Geben Sie einen Wert für die Breite in Pixel ein.<br> Das hochgeladene Bild wird auf diese Breite angepasst.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Geben Sie einen Wert für die Höhe in Pixel ein.<br> Das hochgeladene Bild wird auf diese Breite angepasst.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Markieren, um dieses Feld bei der globalen Suche in diesem Modul miteinzubeziehen.',
//Revert Module labels
'LBL_RESET' => 'Zurücksetzen',
'LBL_RESET_MODULE' => 'Modul zurücksetzen',
'LBL_REMOVE_CUSTOM' => 'Benutzeranpassungen entfernen',
'LBL_CLEAR_RELATIONSHIPS' => 'Beziehungen löschen',
'LBL_RESET_LABELS' => 'Bezeichnungen zurücksetzen',
'LBL_RESET_LAYOUTS' => 'Layouts zurücksetzen',
'LBL_REMOVE_FIELDS' => 'Benutzerdefinierte Felder entfernen',
'LBL_CLEAR_EXTENSIONS' => 'Erweiterungen löschen',

'LBL_HISTORY_TIMESTAMP' => 'Zeitstempel',
'LBL_HISTORY_TITLE' => 'Verlauf',

'fieldTypes' => array(
                'varchar'=>'Textfeld',
                'int'=>'Ganzzahl',
                'float'=>'Fließkommalzahl',
                'bool'=>'Kontrollkästchen',
                'enum'=>'Dropdown-Liste',
                'multienum' => 'Mehrfachauswahl',
                'date'=>'Datum',
                'phone' => 'Telefonnummer',
                'currency' => 'Währung',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Verknüpfung',
                'address' => 'Adresse',
                'text' => 'Textfläche',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Bild',
                'encrypt'=>'Verschlüsselt',
                'datetimecombo' =>'Datum/Zeit',
                'decimal'=>'Dezimalzahl',
),
'labelTypes' => array(
    "" => "Häufig verwendete Bezeichnungen",
    "all" => "Alle Bezeichnungen",
),

'parent' => 'Flexible Verknüpfung',

'LBL_ILLEGAL_FIELD_VALUE' =>"Der Dropdown-Schlüssel darf keine Anführungszeichen enthalten.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Sie haben einen Eintrag zum Löschen aus der Dropdown-Liste selektiert. Alle Dropdown-Listenfelder, welche diese Liste mit diesem Element als Wert enthält, können diesen Wert nicht mehr anzeigen und sind daher nicht mehr verfügbar. Möchten Sie dennoch fortfahren?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Alle Module',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (verwandte {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Von Layout kopieren',
);
