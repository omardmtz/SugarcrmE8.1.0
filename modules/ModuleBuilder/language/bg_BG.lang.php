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
    'LBL_LOADING' => 'Зарежда се ...' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Скриване на опции' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Изтрий' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Разработено от SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Роля',
'help'=>array(
    'package'=>array(
            'create'=>'Въведете <b>Име</b> на пакета. Въвежданото име трябва да е последователност от букви и цифри и не трябва да съдържа интервали. (Пример: HR_Management)<br/><br/> Можете допълнително да въведете информация за <b>Автора</b> и <b>Описание</b> на пакета. <br/><br/>Натиснете <b>Запази</b>, за да създадете пакета.',
            'modify'=>'Секцията съдържа параметрите и възможните действия с <b>Пакета</b> .<br><br>Можете да модифицирате полетата <b>Име</b>, <b>Автор</b> и <b>Описание</b> , както и да разгледате и модифицирате всички модули, съдържащи се в пакета.<br><br>Натиснете <b>Нов модул</b> , за да създадете допълнителен модул в пакета.<br><br>Ако пакетът съдържа поне един модул, можете да <b>Публикувате</b> и <b>Инсталирате</b> пакета, както и да извършите <b>Експорт</b> на направените конфигурации.',
            'name'=>'<b>Име</b> на текущия пакет. <br/><br/>Името трябва да е последователност от букви и цифри, без интервали и започващо с буква. (Пример: HR_Management)',
            'author'=>'<b>Автор</b> визуализиран в процеса на инсталация като име на създателя на пакета.<br><br>Авторът може да бъде физическо лице или компания.',
            'description'=>'<b>Описание</b> на пакета, визуализирано в процеса на инсталация.',
            'publishbtn'=>'Натиснете <b>Публикувай</b> , за да запазите въведенета информация и да създадете .zip файл. Генерираният файл позволява инсталация на пакета.<br><br>Използвайте секцията <b>Инсталиране на модули</b> , за да качите .zip файла в SugarCRM система и да инсталирате пакета.',
            'deploybtn'=>'Натиснете <b>Инсталирай</b> , за да запазите въведената информация и да инсталирате пакета и съдържащите се в него модули в текущата SugarCRM система.',
            'duplicatebtn'=>'Натиснете <b>Дублирай</b> , за да копирате съдържанието на текущия пакет в нов.<br/><br/>Името на новия пакет ще бъде генерирано автоматично чрез добавяне на цифра към името на текущия. Можете да промените името на новия пакет модифицирате полето <b>Име</b> и натиснете  <b>Запази</b>.',
            'exportbtn'=>'Натиснете <b>Експортирай</b> , за да създадете файл с разширение .zip, който съдържа всички данни за пакета.<br><br> Генерираният файл не позволява инсталация на пакета.<br><br>Използвайте секцията <b>Инсталиране на модули</b> , за да импортирате .zip файла в друга Sugar система, така че пакетът там да бъде достъпен в секция Създаване на модули.',
            'deletebtn'=>'Натиснете <b>Изтрий</b> , за да изтриете пакета и всички свързани с него файлове.',
            'savebtn'=>'Натиснете <b>Съхрани</b> , за да запазите въведените данни за текущия пакет.',
            'existing_module'=>'Натиснете <b>Модул</b> , за да редактирате настройките и модифицирате полета, връзки с други модули и подредба на екрани за текущия модул.',
            'new_module'=>'Натиснете <b>Нов модул</b> , за да добавите модул към текущия пакет.',
            'key'=>'Състоящият се от до 5 цифри и букви <b>Ключ</b> ще бъде използван като префикс в имената на всички директории, класове и таблици за модулите в текущия пакет.<br><br>Ключът се използва с цел да се постигне уникалност на имената.',
            'readme'=>'Натиснете <b>Информация за пакета</b> , за да добавите текст относно инсталацията и/или приложението на пакета.<br><br>Информацията за пакета ще бъде достъпна за потребителите в процеса на инсталация.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Въведете <b>Име</b> на модула. Съдържанието на полето <b>Етикет</b> ще се визуализира в табулатора на този модул. <br/><br/>Изберете да се визуализира табулатор за модула като маркирате полето <b>Табулатор</b>.<br/><br/>Check the <b>Team Security</b> checkbox to have a Team selection field within the module records. <br/><br/>След това изберете типа на модула, който желаете да създадете. <br/><br/>Всеки тип съдържа предефинирани полета и подредба на екрани, които можете да използвате като база при създаването на новия модул. <br/><br/>Натиснете <b>Съхрани</b>, за да запазите новия модул.',
        'modify'=>'Можете да промените настройките на модула както и да модифицирате секциите <b>Полета</b>, <b>Връзки с други модули</b> и <b>Подредба на екрани</b>.',
        'importable'=>'Маркирането на полето <b>Импортиране</b> ще разреши импортирането на данни за този модул.<br><br>Електронният помощник за импортиране ще бъде достъпен.  Електронният помощник улеснява импортирането на данни от външни източници в модули на SugarCRM.',
        'team_security'=>'Маркирайте полето <b>Екипна сигурност</b> ако желаете достъпът до записите на създаденият модул да бъде управляван чрез екипи.  <br/><br/>Ако екипната сигурност е разрешена, полето за избор на екип ще бъде добавено към модула',
        'reportable'=>'Отмятането на това квадратче ще позволи изпълнението на отчети спрямо този модул.',
        'assignable'=>'Отмятането на това квадратче ще позволи запис в този модул да бъде присвоен на избран потребител.',
        'has_tab'=>'Маркирайте полето <b>Табулатор</b> ако желаете създаденият от вас модул да има табулатор в системата.',
        'acl'=>'Отмятането на това квадратче ще активира Контрола на достъпа в този модул, включително Сигурността на ниво поле.',
        'studio'=>'Отмятането на това квадратче ще позволи на администраторите да персонализират този модул в рамките на Студиото.',
        'audit'=>'Отмятането на това квадратче ще активира Одит за този модул. Промените в определени полета ще се запишат, така че администраторите да могат да прегледат хронологията на промените.',
        'viewfieldsbtn'=>'Натиснете <b>Полета</b> , за да разгледате дефинираните полета и да създадете нови.',
        'viewrelsbtn'=>'Натиснете <b>Връзки с други модули</b> , за да разгледате дефинираните връзки и да създадете нови.',
        'viewlayoutsbtn'=>'Натиснете <b>Подредба на екрани</b> , за да разгледате дефинираните подредби за модула и модифицирате подредбата на полета в тях.',
        'viewmobilelayoutsbtn' => 'Натиснете <b>Мобилни екрани</b>, за да видите подредбата на мобилните екрани за модула и да персонализирате визуализацията на полетата.',
        'duplicatebtn'=>'Натиснете <b>Дублирай</b> , за да копирате съдържанието на текущия модул в нов. <br/><br/>Името на новия модул ще бъде генерирано автоматично чрез добавяне на цифра към името на текущия.',
        'deletebtn'=>'Натиснете <b>Изтрий</b> , за да изтриете този модул.',
        'name'=>'<b>Име</b> на текущия модул.<br/><br/>Името трябва да е последователност от букви и цифри, без интервали и започващо с буква. (Пример: HR_Management)',
        'label'=>'<b>Етикет</b> е името, което ще бъде визуализирано в табулатора за този модул.',
        'savebtn'=>'Натиснете <b>Съхрани</b> , за да запазите въведените данни за текущия модул.',
        'type_basic'=>'Типът <b>Базов</b> съдържа предефинирани основни полета като Име, Отговорник, Екип, Дата на създаване и Описание.',
        'type_company'=>'Типът <b>Организация</b> съдържа предефинирани полета описващи компании, като Име на компания, Индустрия, Данъчен адрес и Адрес за кореспонденция.<br/><br/>Използвайте този шаблон за създаване на модули подобни на стандартния Организации.',
        'type_issue'=>'Типът <b>Казус</b> съдържа предефинирани полета специфични за описание на проблеми, като Номер, Статус, Приоритет и Описание.<br/><br/>Използвайте този шаблон за създаване на модули подобни на стандартните Казуси и Проблеми.',
        'type_person'=>'Типът <b>Контакт</b> ъдържа предефинирани полета описващи лица за контакти, като Длъжност, Име, Адрес и Телефон.<br/><br/>Използвайте този шаблон за създаване на модули подобни на стандартните Контакти и Потенциални клиенти.',
        'type_sale'=>'Типът <b>Сделка</b> съдържа предефинирани полета специфични за описание на водени търговски преговори, като Източник, Етап на преговори, Сума и Вероятност за успех. <br/><br/>Използвайте този шаблон за създаване на модули подобни на стандартния Възможности.',
        'type_file'=>'Типът <b>Файл</b> съдържа предефинирани полета специфични за описание на документи, като Име на файл, Тип и Дата на публикуване.<br><br>Използвайте този шаблон за създаване на модули подобни на стандартния Документи.',

    ),
    'dropdowns'=>array(
        'default' => 'Всички <b>Падащи менюта</b> за приложенията са изброени тук. <br><br>Падащите менюта могат да се използват за полета от падащите менюта във всеки модул.<br><br>За да направите промени в съществуващо падащо меню, щракнете върху името на падащото меню.<br><br>Щракнете върху <b>Добавяне на падащо меню</b>, за да създадете ново падащо меню.',
        'editdropdown'=>'Списъците на падащите менюта могат да се използват за стандартни или персонализирани полета от падащите менюта във всеки модул.<br><br>Осигурете <b>Име</b> за списъка на падащото меню.<br><br>Ако определени езикови пакети бъдат инсталирани в приложението, можете да изберете <b>езика</b>, който да използвате за елементите на списъка.<br><br>В полето <b>Име на елемента</b> въведете име за опцията в списъка на падащото меню. Това име няма да се появи в списъка на падащото меню, който е видим за потребителите.<br><br>В полето <b>Показване на етикета</b> въведете етикет, който ще бъде видим за потребителите. <br><br>След като въведете името на елемента и етикета се покаже, щракнете върху <b>Добави</b>, за да добавите елемент към списъка на падащото меню.<br><br>За да пренаредите елементите в списъка, дърпайте и пуснете елементите на желаните позиции.<br><br>За да редактирате показването на етикета на даден елемент, щракнете върху <b>Редактиране на икона</b> и въведете нов етикет. За да изтриете елемент от списъка на падащото меню, щракнете върху <b>Изтриване на икона</b>.<br><br>За да отмените промяна, направена в изгледа на етикет, щракнете върху <b>Отмени</b>. За да направите повторно промяна, която е отменена, щракнете върху <b>Направи отново</b>. <br><br>Щракнете върху <b>Запиши</b>, за да запишете списъка на падащото меню.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Щракнете върху <b>Запиши и инсталирай</b>, за да запишете направените от вас промени и да ги направите активни в рамките на модула.',
        'historyBtn'=> 'Щракнете върху <b>Преглед на хронологията</b>, за да видите и възстановите предварително записаната подредба от хронологията.',
        'historyRestoreDefaultLayout'=> 'Щракнете върху <b>Възстанови подредбата по подразбиране</b>, за да възстановите изгледа към първоначалната подредба.',
        'Hidden' 	=> '<b>Скритите</b> полета не се появяват в панела със свързаните записи.',
        'Default'	=> 'Полетата <b>по подразбиране</b> се появяват в панела със свързаните записи.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Щракнете върху <b>Запиши и инсталирай</b>, за да запишете направените от вас промени и да ги направите активни в рамките на модула.',
        'historyBtn'=> 'Натиснете <b>История</b>, за да разгледате и възстановите предишни подредби.<br><br><b>Restore</b> within <b>View History</b> restores the field placement within previously saved layouts. To change field labels, click the Edit icon next to each field.',
        'historyRestoreDefaultLayout'=> 'Щракнете върху <b>Възстанови подредбата по подразбиране</b>, за да възстановите изгледа към първоначалната подредба.<br><br><b>Възстанови подредбата по подразбиране</b> възстановява само разполагането на полето в рамките на първоначалната подредба. За да промените етикетите на полетата, щракнете върху Редактирай иконата до всяко поле.',
        'Hidden' 	=> '<b>Скрити</b> полета, които потребителите не могат да поставят в подредбата на списъка със записи.',
        'Available' => '<b>Налични</b> полета, които не се визуализират по подразбиране, но потребилите могат да добавят към подредбата на списъка със записи.',
        'Default'	=> 'Полета <b>По подразбиране</b> , които се визуализират в списъци със записи, които не са модифицирани от потребителите.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Щракнете върху <b>Запиши и инсталирай</b>, за да запишете направените от вас промени и да ги направите активни в рамките на модула.',
        'historyBtn'=> 'Натиснете <b>История</b>, за да разгледате и възстановите предишни подредби.<br><br><b>Restore</b> within <b>View History</b> restores the field placement within previously saved layouts. To change field labels, click the Edit icon next to each field.',
        'historyRestoreDefaultLayout'=> 'Щракнете върху <b>Възстанови подредбата по подразбиране</b>, за да възстановите изгледа към първоначалната подредба.<br><br><b>Възстанови подредбата по подразбиране</b> възстановява само разполагането на полето в рамките на първоначалната подредба. За да промените етикетите на полетата, щракнете върху Редактирай иконата до всяко поле.',
        'Hidden' 	=> '<b>Скрити</b> полета, които потребителите не могат да поставят в подредбата на списъка със записи.',
        'Default'	=> 'Полета <b>По подразбиране</b> , които се визуализират в списъци със записи, които не са модифицирани от потребителите.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Щракането върху <b>Запиши и инсталирай</b> ще запази всички промени и ще ги направи активни',
        'Hidden' 	=> '<b>Скритите</b> полета не се появяват в Търсенето.',
        'historyBtn'=> 'Натиснете <b>История</b>, за да разгледате и възстановите предишни подредби.<br><br><b>Restore</b> within <b>View History</b> restores the field placement within previously saved layouts. To change field labels, click the Edit icon next to each field.',
        'historyRestoreDefaultLayout'=> 'Щракнете върху <b>Възстанови подредбата по подразбиране</b>, за да възстановите изгледа към първоначалната подредба.<br><br><b>Възстанови подредбата по подразбиране</b> възстановява само разполагането на полето в рамките на първоначалната подредба. За да промените етикетите на полетата, щракнете върху Редактирай иконата до всяко поле.',
        'Default'	=> 'Полетата <b>по подразбиране</b> се появяват в Търсенето.'
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
        'saveBtn'	=> 'Щракнете върху <b>Запиши</b>, за да запазите промените, които сте направили в подредбата от последния път, когато сте я записали. <br><br>Промените няма да се покажат в модула докато не Инсталирате записаните промени.',
        'historyBtn'=> 'Натиснете <b>История</b>, за да разгледате и възстановите предишни подредби.<br><br><b>Restore</b> within <b>View History</b> restores the field placement within previously saved layouts. To change field labels, click the Edit icon next to each field.',
        'historyRestoreDefaultLayout'=> 'Щракнете върху <b>Възстанови подредбата по подразбиране</b>, за да възстановите изгледа към първоначалната подредба.<br><br><b>Възстанови подредбата по подразбиране</b> възстановява само разполагането на полето в рамките на първоначалната подредба. За да промените етикетите на полетата, щракнете върху Редактирай иконата до всяко поле.',
        'publishBtn'=> 'Щракнете върху <b>Запиши и инсталирай</b>, за да запишете всички промени, които сте направили в подредбата от последния път, когато сте я записали и за да направите промените активни в модула. <br><br>Подредбата ще се покаже веднага в модула.',
        'toolbox'	=> '<b>Инструментите</b> съдържат <b>Кошчето</b>, допълнителни елементи за подредба и набора от налични полета за добавяне към подредбата.<br/><br/>Елементите за подредбата и полетата в Инструментите могат да бъдат дърпани и поставени в подредбата, а елементите на подредбата и полетата могат да бъдат дърпани и поставени от подредбата в Инструментите.<br><br>Елементите за подредбата са <b>Панели</b> и <b>Редове</b>. Добавянето на нов ред или нов панел към подредбата предоставя допълнителни места в подредбата за полета.<br/><br/>Дърпайте и поставете някое от полетата от Инструментите или подредбата на заета позиция на поле, за да размените местата на двете полета.<br/><br/>Запълващото <b>поле</b> създава интервал в подредбата, където е поставено.',
        'panels'	=> 'Мястото на <b>подредбата</b> предоставя изглед на вида на подредбата в рамките на модула при инсталиране на промените, направени в подредбата.<br/><br/>Можете да позиционирате отново полета, редове и панели като ги дърпате и поставите на желаното място.<br/><br/>Премахнете елементи чрез дърпане и поставяне в <b>Кошчето</b> в Инструментите или добавете нови елементи и полета като ги дърпате от <b>Инструментите</b> и ги поставите на желаното място в подредбата.',
        'delete'	=> 'Дърпайте и поставете елементите тук, за да ги премахнете от подредбата',
        'property'	=> 'Edit the <b>Label</b> displayed for this field.<br><br><b>Width</b> provide a width value in pixels for Sidecar modules and as a percentage of the table width for backward compatible modules.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>Полетата</b>, които са налични за модула, са посочени тук по Име на полето.<br><br>Потребителските полета, създадени за модула, са разположени над полетата, които са налични за модула по подразбиране.<br><br>За да редактирате дадено поле, щракнете върху <b>Име на полето</b>.<br/><br/>За да създадете ново поле, щракнете върху <b>Добавяне на поле</b>.',
        'mbDefault'=>'<b>Полетата</b>, които са налични за модула, са посочени тук по Име на полето.<br><br>За да конфигурирате свойствата на дадено поле, щракнете върху Името на полето.<br><br>За да създадете ново поле, щракнете върху <b>Добавяне на поле</b>. Етикетът, заедно с другите свойства на новото поле, могат да бъдат редактирани след създаването като щракнете върху Името на полето.<br><br>След инсталирането на модула, новите полета, създадени в Създаването на модули, се разглеждат като стандартни полета в инсталирания модул в Студио.',
        'addField'	=> 'Изберете <b>Вид на данните</b> за новото поле. Видът, който изберете, определя какъв вид знаци могат да бъдат въвеждани в полето. Например, само числа, които са от целочислен тип, могат да бъдат въвеждани в полета, които са от типа данни цели числа. <br><br> Посочете <b>Име</b> за полето. Името трябва да бъде буквено-цифрово и не трябва да съдържа интервали. Подчертаванията са валидни.<br><br><b>Показването на етикета</b> е етикетът, който ще се появи за полетата в подредбите на модула.  <b>Системният етикет</b> се използва за позоваване на полето в кода.<br><br>В зависимост от типа данни, избран за полето, някои или всички от следните свойства могат да бъдат настроени за полето:<br><br> <b>Помощният текст</b> се появява, временно докато потребителят държи маркера над полето и може да се използва да напомняне на потребителя за вида желаните данни за въвеждане.<br><br> <b>Текстът на коментара</b> е видим само в рамките на Създаването на студио и/или модул и може да се използва за описване на полето за администраторите. <br><br> <b>Стойността по подразбиране</b> ще се появи в полето. Потребителите могат да въведат нова стойност в полето или да използват стойността по подразбиране.<br><br> Поставете отметка на <b>Масова актуализация</b> ,за да можете да използвате функцията за Масова актуализация за полето.<br><br>Стойността на<b>Максималния размер</b> определя максималния брой знаци, които могат да бъдат въведени в полето.<br><br> Поставете отметка на <b>Задължително поле</b> ,за да направите полето задължително. Трябва да бъде въведена стойност за полето, за да можете да запазите запис, съдържащ полето.<br><br>Поставете отметка на <b>Отчетимо</b> ,за да позволите полето да бъде използвано за филтри и за показване на данни в Отчети.<br><br>Поставете отметка на<b>Одит</b> ,за да можете да следите промените в полето в Регистъра на промените.<br><br>Изберете опция в полето <b>Подлежи на импортиране</b>, за да разрешите, забраните или изискате полето да бъде импортирано в Помощника за импортиране.<br><br>Изберете опция в полето <b>Сливане на дублирани се записи</b> , за да разрешите или забраните свойствата за Обединяване на дублирани записи и Намиране на дублирани записи. <br><br>За определени видове данни могат да бъдат зададени допълнителни свойства.',
        'editField' => 'Свойствата на това поле могат да бъдат персонализирани.<br><br>Щракнете върху<b>Дублирай</b>, за да създадете ново поле със същите свойства.',
        'mbeditField' => '<b>Показването на етикета</b> на полето на шаблон може да бъде персонализирано. Останалите свойства на полето не могат да бъдат персонализирани.<br><br>Щракнете върху<b>Дублирай</b>, за да създадете ново поле със същите свойства.<br><br>За да премахнете поле на шаблон, така че да не се показва в модула, премахнете полето от съответните <b>Подредби</b>.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Експортирайте персонализациите, направени в Студиото чрез създаване на пакети, които могат да бъдат заредени в друга инсталация на Sugar чрез <b>Зареждане на модул</b>.<br><br> Първо, въведете <b>Име на пакета</b>.  Можете да въведете информация за <b>Автор</b> и <b>Описание</b> и за пакета.<br><br>Изберете модула(ите), който(които) съдържа(т) персонализациите, които желаете да експортирате. Ще се появят само модули, съдържащи персонализации, от които да изберете.<br><br>След това щракнете върху <b>Експортирай</b> за създаване на .zip файл за пакета, съдържащ персонализациите.',
        'exportCustomBtn'=>'Щракнете върху <b>Експортирай</b> за създаване на .zip файл за пакета, съдържащ персонализациите, които искате да експортирате.',
        'name'=>'Това е <b>Името</b> на пакета. Това име ще се покаже по време на инсталацията.',
        'author'=>'<b>Автор</b> визуализиран в процеса на инсталация като име на създателя на пакета. Авторът може да бъде физическо лице или компания.',
        'description'=>'<b>Описание</b> на пакета, визуализирано в процеса на инсталация.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Добре дошли в секцията с <b>Развойни инструменти</b> . <br/><br/>Използвайте инструмените в секцията, за да създавате конфигурирате модули и полета в системата',
        'studioBtn'	=> 'Използвайте инструмента <b>Студио</b> , за да модифицирате инсталирани модули.',
        'mbBtn'		=> 'Изполвайте инструмента <b>Създаване на модули</b> , за да създадете нови модули.',
        'sugarPortalBtn' => 'Използвайте <b>Портал - редактор</b> , за да управлявате и конфигурирате Sugar Portal.',
        'dropDownEditorBtn' => 'Използвайте инструмента <b>Редактор на падащи менюта</b> , за да създадете нови или да редактирате съществуващи падащи менюта за полета в системата.',
        'appBtn' 	=> 'В режима на приложение можете да персонализирате различни свойства на програмата, като например колко TPS отчета се визуализират на началната страница',
        'backBtn'	=> 'Върнете се на предишната стъпка.',
        'studioHelp'=> 'Използвайте <b>Студио</b>, за да определите каква информация и как се визуализира в модулите.',
        'studioBCHelp' => 'показва, че модулът е съвместим с предишни версии на продукта',
        'moduleBtn'	=> 'Щракнете тук, за да редактирате този модул.',
        'moduleHelp'=> 'Компонентите, които можете да персонализирате за модула, се появяват тук.<br><br>Щракнете върху икона, за да изберете компонента за редактиране.',
        'fieldsBtn'	=> 'Създайте и персонализирайте <b>Полета</b> за съхраняване на информация в модула.',
        'labelsBtn' => 'Редактирайте <b>Етикетите</b>, които се визуализират за полетата, както и други заглавия в модула.'	,
        'relationshipsBtn' => 'Добавете нови или прегледайте съществуващите <b>Връзки</b> за модула.' ,
        'layoutsBtn'=> 'Персонализирайте модула <b>Подредби</b>.  Подредбите са различните изгледи на модула, съдържащ полета.<br><br>Можете да определите кои полета да се появяват и как са организирани във всяка подредба.',
        'subpanelBtn'=> 'Определете кои полета да се появяват в <b>Панелите със свързани записи</b> на модула.',
        'portalBtn' =>'Персонализирайте модула <b>Подредби</b>, който се появява в <b>Портала Sugar</b>.',
        'layoutsHelp'=> 'The module <b>Layouts</b> that can be customized appear here.<br><br>The layouts display fields and field data.<br><br>Click an icon to select the layout to edit.',
        'subpanelHelp'=> '<b>Панелите със свързани записи</b> в модула, които могат да бъдат персонализирани, се появяват тук.<br><br>Щракнете върху икона, за да изберете модула за редактиране.',
        'newPackage'=>'Натиснете <b>Нов пакет</b> , за да създадете нов пакет.',
        'exportBtn' => 'Щракнете върху <b>Експортирай персонализациите</b>, за да създадете и изтеглите пакет, който съдържа персонализации, направени в Студио за конкретни модули.',
        'mbHelp'    => 'Използвайте инструмента <b>Създаване на модули</b> , за да създадете пакети от модули, като използвате предефинирани шаблони.',
        'viewBtnEditView' => 'Customize the module&#39;s <b>EditView</b> layout.<br><br>The EditView is the form containing input fields for capturing user-entered data.',
        'viewBtnDetailView' => 'Customize the module&#39;s <b>DetailView</b> layout.<br><br>The DetailView displays user-entered field data.',
        'viewBtnDashlet' => 'Customize the module&#39;s <b>Sugar Dashlet</b>, including the Sugar Dashlet&#39;s ListView and Search.<br><br>The Sugar Dashlet will be available to add to the pages in the Home module.',
        'viewBtnListView' => 'Персонализирайте подредбата на модула <b>Преглед на списъка</b>.<br><br>Резултатите от търсенето се появяват в Преглед на списъка.',
        'searchBtn' => 'Customize the module&#39;s <b>Search</b> layouts.<br><br>Determine what fields can be used to filter records that appear in the ListView.',
        'viewBtnQuickCreate' =>  'Customize the module&#39;s <b>QuickCreate</b> layout.<br><br>The QuickCreate form appears in subpanels and in the Emails module.',

        'searchHelp'=> 'Формите за <b>Търсене</b>, които могат да бъдат персонализирани, се появяват тук.<br><br>Формите за търсене съдържат полета за филтриране на записи.<br><br>Щракнете върху икона, за да изберете подредбата за търсене, която ще редактирате.',
        'dashletHelp' =>'Подредбите на <b>Панелите на Sugar</b>, които могат да бъдат персонализирани, се появяват тук.<br><br>Разделите на Sugar ще бъдат налични за добавяне към страници в модула Начало.',
        'DashletListViewBtn' =>'<b>Преглед на списъка с Панелите на Sugar</b> показва записи на базата на филтри за търсене в Разделите на Sugar.',
        'DashletSearchViewBtn' =>'<b>Търсенето в Панелите на Sugar</b> филтрира записите за списъка за преглед на Разделите на Sugar.',
        'popupHelp' =>'<b>Изскачащите</b> подредби, които могат да бъдат персонализирани, се появяват тук.<br>',
        'PopupListViewBtn' => 'The <b>Popup ListView</b> displays records based on the Popup search views.',
        'PopupSearchViewBtn' => 'The <b>Popup Search</b> views records for the Popup listview.',
        'BasicSearchBtn' => 'Персонализиране на формата за <b>Основно търсене</b>, която се появява в табулатора за Основно търсене в секцията на търсене за модула.',
        'AdvancedSearchBtn' => 'Персонализиране на формата за <b>Разширено търсене</b>, която се появява в табулатора Разширено търсене в Секцията на търсене за модула.',
        'portalHelp' => 'Управление и персонализиране на <b>Портала на Sugar</b>.',
        'SPUploadCSS' => 'Зареждане на <b>Лист за стила</b> в Портала на Sugar.',
        'SPSync' => '<b>Синхронизиране</b> на персонализации в инсталацията на Портала на Sugar.',
        'Layouts' => 'Персонализиране на <b>Подредби</b> в модулите на Портала на Sugar.',
        'portalLayoutHelp' => 'Модулите в Портала на Sugar се появяват в тази секция.<br><br>Изберете модул, за да редактирате <b>Подредбите</b>.',
        'relationshipsHelp' => 'Всички <b>Връзки</b>, които съществуват между модула и други инсталирани модули, се появяват тук.<br><br>Връзката <b>Име</b> е генерираното от системата име за връзката.<br><br> <b>Основният модул</b> е модула, чиято собственост е връзката. Например: всички свойства на връзките, за които Модулът на профилите е основния модул, се съхраняват в Таблиците на базата данни на профилите.<br><br> <b>Типът</b> е типа връзка, който съществува между Първичния модул и <b>Свързания модул</b>. <br><br>Щракнете върху заглавие на колона, за да сортирате по колона. <br><br>Щракнете върху ред в таблицата на връзките, за да видите свойствата, свързани с връзката.<br><br>Щракнете върху <b>Добавяне на връзка</b>, за да създадете нова връзка.<br><br>Връзките могат да бъдат създадени между всеки два инсталирани модула.',
        'relationshipHelp'=>'<b>Relationships</b> can be created between the module and another deployed module.<br><br> Relationships are visually expressed through subpanels and relate fields in the module records.<br><br>Select one of the following relationship <b>Types</b> for the module:<br><br> <b>One-to-One</b> - Both modules&#39; records will contain relate fields.<br><br> <b>One-to-Many</b> - The Primary Module&#39;s record will contain a subpanel, and the Related Module&#39;s record will contain a relate field.<br><br> <b>Many-to-Many</b> - Both modules&#39; records will display subpanels.<br><br> Select the <b>Related Module</b> for the relationship. <br><br>If the relationship type involves subpanels, select the subpanel view for the appropriate modules.<br><br> Click <b>Save</b> to create the relationship.',
        'convertLeadHelp' => "Here you can add modules to the convert layout screen and modify the settings of existing ones.<br/><br/>
<b>Ordering:</b><br/>
Contacts, Accounts, and Opportunities must maintain their order. You can re-order any other module by dragging its row in the table.<br/><br/>
<b>Dependency:</b><br/>
If Opportunities is included, Accounts must either be required or removed from the convert layout.<br/><br/>
<b>Module:</b> The name of the module.<br/><br/>
<b>Required:</b> Required modules must be created or selected before the lead can be converted.<br/><br/>
<b>Copy Data:</b> If checked, fields from the lead will be copied to fields with the same name in the newly created records.<br/><br/>
<b>Delete:</b> Remove this module from the convert layout.<br/><br/>        ",
        'editDropDownBtn' => 'Редактиране на глобално падащо меню',
        'addDropDownBtn' => 'Добавяне на ново глобално падащо меню',
    ),
    'fieldsHelp'=>array(
        'default'=>'Секцията съдържа списък на дефинираните <b>Полета</b> в модула, сортирани по име.<br><br>Типът модул съдържа предефинирани полета.<br><br>За да създадете ново поле, натиснете <b>Добави поле</b>.<br><br>За да редактирате поле, натиснете съответното <b>Име на поле</b>.<br/><br/>Когато модулът бъде инсталиран всички полета създадени тук, заедно с полетата на шаблоните ще бъдат третирани в Студио като стандартни полета.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'Секцията визуализра <b>Връзките</b> на текущия модул с други модули в системата.<br><br><b>Име</b> на връзката се генерира системно.<br><br>При създаването на връзка между два модула единият е <b>Основен модул</b> , а другият <b>Свързан модул</b>. Параметрите на връзката се запазват в базата данни в таблиците на основния модул.<br><br><b>Тип</b> определя типа на връзката, създадена между основния и свързания модули.<br><br>Натиснете заглавието на всяка от колоните, за да извършите сортиране по нея.<br><br>Натиснете определен ред от таблицата с връзки, за да разгледате и редактирате параметрите на съответната връзка.<br><br>Натиснете <b>Добави връзка с друг модул</b> , за да създадете нова връзка.',
        'addrelbtn'=>'поставете мишката върху Помощ за добавяне на връзка..',
        'addRelationship'=>'<b>Relationships</b> can be created between the module and another custom module or a deployed module.<br><br> Relationships are visually expressed through subpanels and relate fields in the module records.<br><br>Select one of the following relationship <b>Types</b> for the module:<br><br> <b>One-to-One</b> - Both modules&#39; records will contain relate fields.<br><br> <b>One-to-Many</b> - The Primary Module&#39;s record will contain a subpanel, and the Related Module&#39;s record will contain a relate field.<br><br> <b>Many-to-Many</b> - Both modules&#39; records will display subpanels.<br><br> Select the <b>Related Module</b> for the relationship. <br><br>If the relationship type involves subpanels, select the subpanel view for the appropriate modules.<br><br> Натиснете <b>Съхрани</b> , за да запазите връзката.',
    ),
    'labelsHelp'=>array(
        'default'=> '<b>Етикетите</b> за полетата и други заглавия в модула могат да бъдат променени.<br><br>Редактирайте етикета като щракнете в полето, въведете нов етикет и щракнете върху <b>Запиши</b>.<br><br>Ако в приложението бъдат инсталирани езикови пакети, можете да изберете <b>Език</b>, който да използвате за етикетите.',
        'saveBtn'=>'Щракнете върху <b>Запиши</b>, за да запишете всички промени.',
        'publishBtn'=>'Щракнете върху <b>Запиши и инсталирай</b>, за да запишете всички промени и да ги направите активни.',
    ),
    'portalSync'=>array(
        'default' => 'Влезте в <b>URL на Портала на Sugar</b> на инсталацията на Портала, за да актуализирате, и щракнете върху <b>Начало</b>.<br><br>След това въведете валиди потребителско име и парола за Sugar и след това щракнете върху <b>Започни синхронизирането</b>.<br><br>Персонализациите, направени на <b>Подредбите</b> на Портала на Sugar, както и <b>Листа за стила</b> ако такъв е бил зареден, ще бъдат прехвърлени към посочената инсталация на портала.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Можете да персонализирате външния вид на Портала на Sugar с помощта на лист със стилове.<br><br>Изберете <b>Лист със стилове</b>, които да заредите.<br><br>Листът със стилове ще бъдат приложен в Портала на Sugar следващия път когато се извършва синхронизиране.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'За да започнете работа по проект, щракнете върху <b>Нов пакет</b>, за да създадете нов пакет, който да съдържа вашия(те) потребителски модул(и).<br/><br/>Всека пакет може да съдържа един или повече модули.<br/><br/>Например, може да искате да създадете пакет, съдържащ един потребителски модул, който е свързан с модула за стандартните Профили. Или, може да искате да създадете пакет, съдържащ няколко нови модула, които работят заедно като проект и които са свързани помежду си и с други модули, които вече се прилагат.',
            'somepackages'=>'<b>Пакетът</b> служи за контейнер за потребителски модули, които всички са част от един проект. Пакетът може да съдържа един или повече потребителски <b>модула</b>, които могат да бъдат свързани помежду си или с други модули в приложението.<br/><br/>След създаването на пакет за вашия проект, можете да създадете модули за пакета веднага, или можете да се върнете към Създаване на модули по-късно, за да завършите проекта.<br><br>Когато проектът е завършен, можете да <b>инсталирате</b> пакета за инсталиране на потребителските модули в рамките на приложението.',
            'afterSave'=>'Your new package should contain at least one module. You can create one or more custom modules for the package.<br/><br/>Click <b>New Module</b> to create a custom module for this package.<br/><br/> After creating at least one module, you can publish or deploy the package to make it available for your instance and/or other users&#39; instances.<br/><br/> To deploy the package in one step within your Sugar instance, click <b>Deploy</b>.<br><br>Click <b>Publish</b> to save the package as a .zip file. After the .zip file is saved to your system, use the <b>Module Loader</b> to upload and install the package within your Sugar instance.  <br/><br/>You can distribute the file to other users to upload and install within their own Sugar instances.',
            'create'=>'<b>Пакетът</b> служи за контейнер за потребителски модули, които всички са част от един проект. Пакетът може да съдържа един или повече потребителски <b>модула</b>, които могат да бъдат свързани помежду си или с други модули в приложението.<br/><br/>След създаването на пакет за вашия проект, можете да създадете модули за пакета веднага, или можете да се върнете към Създаване на модули по-късно за завършване на проекта.',
            ),
    'main'=>array(
        'welcome'=>'Използвайте <b>Инструментите за разработчици</b>, за да създадете и управлявате стандартни и потребителски модули и полета.<br/><br/>За да управлявате модулите в приложението, щракнете върху <b>Студио</b>.<br/><br/> За да създадете потребителски модули, щракнете върху <b>Създаване на модули</b>.',
        'studioWelcome'=>'Всички модули, инсталирани в момента, включително стандартните и заредените с модули шаблони, могат да бъдат персонализирани в рамките на Студио.'
    ),
    'module'=>array(
        'somemodules'=>"Тъй като текущият пакет съдържа поне един модул, можете да <b>инсталирате</b> модулите в пакета в рамките на вашата инсталация на Sugar или да <b>публикувате</b> пакета, за да бъде инсталиран в текущата инсталация на Sugar или друга инсталация с помощта на <b>Зареждане на модули</b>.<br/><br/>За да инсталирате пакета направо в рамките на вашата инсталация на Sugar, щракнете върху <b>Инсталирай</b>.<br><br>За да създадете файл с разширение .zip за пакета, който може да бъде зареден и инсталиран в рамките на текущата инсталация на Sugar и други инсталации с помощта на <b>Зареждане на модули</b>, щракнете върху <b>Публикувай</b>.<br/><br/> Можете да създадете модулите за този пакет на етапи и да публикувате или инсталирате когато сте готови за това.<br/><br/> След публикуване или инсталиране на пакет можете да направите промени в свойствата на пакета и да персонализирате модулите по-нататък.  След това публикувайте или инсталирайте пакета отново, за да приложите промените." ,
        'editView'=> 'Тук можете да редактирате съществуващите полета. Можете да премахнете някои от съществуващите полета или да добавете налични полета в левия панел.',
        'create'=>'When choosing the type of <b>Type</b> of module that you wish to create, keep in mind the types of fields you would like to have within the module. <br/><br/>Each module template contains a set of fields pertaining to the type of module described by the title.<br/><br/><b>Basic</b> - Provides basic fields that appear in standard modules, such as the Name, Assigned to, Team, Date Created and Description fields.<br/><br/> <b>Company</b> - Provides organization-specific fields, such as Company Name, Industry and Billing Address.  Use this template to create modules that are similar to the standard Accounts module.<br/><br/> <b>Person</b> - Provides individual-specific fields, such as Salutation, Title, Name, Address and Phone Number.  Use this template to create modules that are similar to the standard Contacts and Leads modules.<br/><br/><b>Issue</b> - Provides case- and bug-specific fields, such as Number, Status, Priority and Description.  Use this template to create modules that are similar to the standard Cases and Bugs modules.<br/><br/>Note: After you create the module, you can edit the labels of the fields provided by the template, as well as create custom fields to add to the module layouts.',
        'afterSave'=>'Персонализирайте модула, така че да отговаря на вашите нужди като редактирате и създавате полета, създавате връзки с други модули и подреждате полетата в подредбите.<br/><br/>За да разгледате полетата на шаблона и управлявате потребителските полета в рамките на модула, щракнете върху <b>Преглед на полетата</b>.<br/><br/>За да създавате и управлявате връзки между модула и други модули, независимо дали са модули вече в приложението или други потребителски модули в рамките на същия пакет, щракнете върху <b>Преглед на връзки</b>.<br/><br/>За редактиране на подредбите на модула, щракнете върху <b>Преглед на подредбите</b>. Можете да променяте подредбите на Подробен преглед, Преглед на редакцията и Преглед на списъците за модула, точно както бихте направили това за модули, които са вече в приложението в рамките на Студио.<br/><br/>За да създадете модул със същите свойства като текущия модул, щракнете върху <b>Дублирай</b>.  Можете допълнително да персонализирате новия модул.',
        'viewfields'=>'Полетата в модула могат да бъдат персонализирани според вашите нужди.<br/><br/>Не можете да изтриете стандартните полета, но можете да ги премахнете от съответните подредби в рамките на Подредбите на страници.<br/><br/>Можете бързо да създадете нови полета, които имат сходни свойства със съществуващи полета, като щракнете върху <b>Дублирай</b> във формата <b>Свойства</b>.  Въведете нови свойства и след това щракнете върху <b>Запиши</b>.<br/><br/>Препоръчително е да настроите всички свойства за стандартните полета и персонализираните поле преди да публикувате и инсталирате пакета, съдържащ потребителския модул.',
        'viewrelationships'=>'Можете да създадете връзки Много към много между текущия модул и други модули в пакета и/или между текущия модул и вече инсталирани в приложението модули.<br><br>За да създадете Един към много и Един към един връзки, създайте полета <b>Релационно поле</b> и <b>Гъвкаво релационно поле</b> за модулите.',
        'viewlayouts'=>'Можете да контролирате кои полета са налични за улавяне на данни в рамките на <b>Форма за редактиране</b>.  Можете също да контролирате какви данни се визуализират в <b>Подробен преглед</b>.  Изгледите не трябва да съвпадат.<br/><br/> Формата за бързо създаване се показва при щракване върху <b>Създаване</b> в панел със свързани записи на даден модул. По подразбиране подредбата на формата <b>Бързо създаване</b> е същата като подредбата на <b>Форма за редактиране</b> по подразбиране. Можете да персонализирате формата за бързо създаване, така че да съдържа по-малко и/или различни полета, отколкото подредбата на Форма за редактиране.<br><br>Можете да определите защитата на модула с помощта на Персонализиране на подредбата, заедно с <b>Потребителски роли</b>.<br><br>',
        'existingModule' =>'След като създадете и персонализирате този модул, можете да създадете допълнителни модули или да се върнете на пакета за <b>Публикуване</b> или <b>Инсталиране</b> на пакета.<br><br>За да създадете допълнителни модули, щракнете върху <b>Дублирай</b>, за да създадете модул със същите свойства като текущия модул или да се върнете обратно към пакета и щракнете върху <b>Нов модул</b>.<br><br>Ако сте готови за <b>Публикуване</b> или <b>Инсталиране</b> на пакета, съдържащ този модул, върнете се обратно към пакета, за да изпълните тези функции. Можете да публикувате и да инсталирате пакети, съдържащи поне един модул.',
        'labels'=> 'Етикетите на стандартните полета, както и персонализираните полета, могат да бъдат променени. Промяната на етикети на полета няма да се отрази на данните, съхранявани в полетата.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Има три колони, които се показват в ляво. Колоната "по подразбиране" съдържа полетата, които се показват в Списък на записите по подразбиране, колоната "Налични" съдържа полета, които потребителят може да избере да използва за създаване на персонализиран Списък на записите, а колоната "Скрити" съдържа полета, достъпни за вас като администратор, които можете да добавите към колоните по подразбиране или Наличните колони за ползване от потребители, но които в момента са деактивирани.',
        'savebtn'	=> 'Щракването върху <b>Запиши</b> ще запише всички промени и ще ги направи активни.',
        'Hidden' 	=> 'Скритите полета са полета, които не са налични в момента за потребителите за използване в списъците на записите.',
        'Available' => 'Наличните полета са полета, които не са показани по подразбиране, но могат да бъдат активирани от потребителите.',
        'Default'	=> 'Полетата по подразбиране се показват за потребителите, които не са създали персонализирани настройки на списъка на записите.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Има две колони, показани в ляво. Колоната "По подразбиране" съдържа полетата, които ще бъдат показани в изгледа на търсенето, а колоната "Скрити" съдържа полета, налични за вас като администратор за добавяне към списъка.',
        'savebtn'	=> 'Щракането върху <b>Запиши и инсталирай</b> ще запази всички промени и ще ги направи активни.',
        'Hidden' 	=> 'Скритите полета са полета, които няма да бъдат показани в изгледа на търсенето.',
        'Default'	=> 'Полетата по подразбиране ще бъдат показани в изгледа за търсене.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Има две колони, показани в ляво. Дясната колона, означена с етикет Настояща подредба или Визуализация на подредбата, е там, където можете да промените подредбата на модула. Лявата колона, озаглавена Инструменти, съдържа полезни елементи и инструменти за използване при редактиране на подредбата.<br/><br/>Ако полето на подредбата е озаглавено Настояща подредба, работите върху копие на подредбата, което в момента се използва от модула за показване.<br/><br/>Ако е озаглавено Визуализация на подредбата, тогава работите върху копие, създадено по-рано с щракване върху бутона "Запиши", което може вече да е променено в сравнение с версията, видяна от потребителите на този модул.',
        'saveBtn'	=> 'Щракването върху този бутон записва подредбата, така че да можете да запазите вашите промени. Когато се върнете към този модул ще започнете от тази променена подредба. Вашата подредба обаче няма да бъде видяна от потребителите на модула докато не щракнете върху бутона Запиши и Публикувай.',
        'publishBtn'=> 'Щракнете върху този бутон, за да инсталирате подредбата. Това означава, че тази подредба ще бъде незабавно видяна от потребителите на този модул.',
        'toolbox'	=> 'Инструментите съдържат редица полезни свойства за редактиране на подредби, включително секция за изтрито съдържание, набор от допълнителни елементи и набор от налични полета. Всяко едно от тях може да бъде дръпнато и поставено върху подредбата.',
        'panels'	=> 'Това поле показва как ще изглежда вашата подредба пред потребителите на този модул когато бъде инсталиран.<br/><br/>Можете да позиционирате отново елементите, като например полета, редове и панели, като ги дърпате и поставите; да изтриете елементи като ги дърпате и поставите в секцията на изтритата информация в инструментите, или да добавите нови елементи като ги издърпате от инструментите и ги поставите върху подредбата в желаното положение.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Има две колони, показани в ляво. Дясната колона, означена с етикет Настояща подредба или Визуализация на подредбата, е там, където можете да промените подредбата на модула. Лявата колона, озаглавена Инструменти, съдържа полезни елементи и инструменти за използване при редактиране на подредбата.<br/><br/>Ако полето на подредбата е озаглавено Настояща подредба, работите върху копие на подредбата, което в момента се използва от модула за показване.<br/><br/>Ако е озаглавено Визуализация на подредбата, тогава работите върху копие, създадено по-рано с щракване върху бутона "Запиши", което може вече да е променено в сравнение с версията, видяна от потребителите на този модул.',
        'dropdownaddbtn'=> 'Щракването върху този бутон добавя нов елемент към падащото меню.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Персонализации, направени в Студио в рамките на тази инсталация, могат да бъдат опаковани и инсталирани в друга инсталация.<br><br>Въведете <b>Име на пакета</b>. Можете да въведете информация за <b>Автор</b> и <b>Описание</b> на пакета.<br><br>Изберете модула(ите), който(които) съдържа(т) персонализации за експортиране. (Ще се появяват само модули, съдържащи персонализации, от които да изберете.)<br><br>Щракнете върху <b>Експортирай</b> за създаване на .zip файл за пакета, съдържащ персонализациите. .Zip файлът може да бъде зареден в друга инсталация чрез <b>Зареждане на модула</b>.',
        'exportCustomBtn'=>'Щракнете върху <b>Експортирай</b> за създаване на .zip файл за пакета, съдържащ персонализациите, които искате да експортирате.
',
        'name'=>'<b>Името</b> на пакета ще бъде показано в Зареждане на модула след като пакетът бъде зареден за инсталация в Студио.',
        'author'=>'The <b>Author</b> is the name of the entity that created the package. The Author can be either an individual or a company.<br><br>The Author will be displayed in Module Loader after the package is uploaded for installation in Studio.',
        'description'=>'<b>Описанието</b> на пакета ще бъде показано в Зареждане на модула след като пакетът бъде зареден за инсталация в Студио.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Добре дошли в секцията <b>Развойни инструменти</b1>. <br/><br/>Използвайте инструмените в секцията, за да създавате и конфигурирате модули и полета в системата.',
        'studioBtn'	=> 'Използвайте <b>Студио</b>, за да персонализирате инсталирани модули, като промените подредбата на полетата, изберете кои полета са налични и създадете полета за потребителски данни.',
        'mbBtn'		=> 'Изполвайте инструмента <b>Създаване на модули</b> , за да създадете нови модули.',
        'appBtn' 	=> 'Използвайте Режим на приложение, за да персонализирате различни свойства на програмата, като например колко TPS отчети се показват на началната страница',
        'backBtn'	=> 'Върнете се на предишната стъпка.',
        'studioHelp'=> 'Използвайте <b>Студио</b> за персонализиране на инсталираните модули.',
        'moduleBtn'	=> 'Щракнете тук, за да редактирате този модул.',
        'moduleHelp'=> 'Изберете компонента на модула, който желаете да редактирате',
        'fieldsBtn'	=> 'Редактирайте съхраняваната информация в модула чрез контролиране на <b>Полетата</b> в модула.<br/><br/>Тук можете да редактирате и създадете потребителски полета.',
        'layoutsBtn'=> 'Персонализиране на <b>Подредбите</b> на изгледите Редактиране, Подробности и Търсене.',
        'subpanelBtn'=> 'Редактирайте показваната в модулите на панелите със свързани записи информация.',
        'layoutsHelp'=> 'Изберете <b>Подредба за редактиране</b>.<br/<br/>За да промените подредбата, която съдържа полета за данни за въвеждане на данни, щракнете върху <b>Форма за редактиране</b>.<br/><br/>За да промените подредбата, която показва данните, въведени в полетата във Формата за редактиране, щракнете върху <b>Форма за визуализиране на детайли</b>.<br/><br/>За да промените колоните, които се появяват в списъка по подразбиране, щракнете върху <b>Списък на записите</b>.<br/><br/>За да промените подредбите на формите за основно и разширено търсене, щракнете върху <b>Търсене</b>.',
        'subpanelHelp'=> 'Изберете <b>Панел със свързани записи</b>, за да редактирате.',
        'searchHelp' => 'Изберете подредба <b>Търсене</b>, за да редактирате.',
        'labelsBtn'	=> 'Редактирайте <b>етикетите</b> за показване на стойности в този модул.',
        'newPackage'=>'Натиснете <b>Нов пакет</b> , за да създадете нов пакет.',
        'mbHelp'    => '<b>Дошли в Създаване на модул.</b><br/><br/>Използвайте <b>Създаване на модул</b> за да създадете пакети, съдържащи потребителски модули, базирани на стандартни или потребителски обекти.<br/><br/>За да започнете, щракнете върху <b>Нов пакет</b>, за да създадете нов пакет, или изберете пакет за редактиране.<br/><br/> <b>Пакетът</b> служи за контейнер за потребителски модули, които всички са част от един проект. Пакетът може да съдържа един или повече потребителски модула, които могат да бъдат свързани помежду си или с модули в приложението.<br/><br/>Примери: Може да искате да създадете пакет, съдържащ един потребителски модул, който е свързан със стандартния Модул за профили. Или, може да искате да създадете пакет, съдържащ няколко нови модула, които работят заедно като проект и които са свързани помежду си и с модули в приложението.',
        'exportBtn' => 'Щракнете върху <b>Експортиране на персонализации</b> за създаване на пакет, който съдържа персонализации, направени в Студио за специфични модули.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Редактор на падащи менюта',

//ASSISTANT
'LBL_AS_SHOW' => 'Покажи помоник в бъдеще.',
'LBL_AS_IGNORE' => 'Игнориране на помощника в бъдеще.',
'LBL_AS_SAYS' => 'Помощника казва:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Създаване на модули',
'LBL_STUDIO' => 'Студио',
'LBL_DROPDOWNEDITOR' => 'Редактор на падащи менюта',
'LBL_EDIT_DROPDOWN'=>'Редактор на падащи менюта',
'LBL_DEVELOPER_TOOLS' => 'Средства за конфигуриране на програмата',
'LBL_SUGARPORTAL' => 'Портал - редактор',
'LBL_SYNCPORTAL' => 'Сихронизация на Портала',
'LBL_PACKAGE_LIST' => 'Списък с модули',
'LBL_HOME' => 'Начало',
'LBL_NONE'=>'Няма въведени данни',
'LBL_DEPLOYE_COMPLETE'=>'Завърши и публикувай',
'LBL_DEPLOY_FAILED'   =>'Възникна грешка по време на процеса, вашият пакет може да не е инсталиран правилно',
'LBL_ADD_FIELDS'=>'Добавяне на потребителски полета',
'LBL_AVAILABLE_SUBPANELS'=>'Налични панели',
'LBL_ADVANCED'=>'Разширено търсене',
'LBL_ADVANCED_SEARCH'=>'Разширено търсене',
'LBL_BASIC'=>'Свързани с тип базов',
'LBL_BASIC_SEARCH'=>'Основно търсене',
'LBL_CURRENT_LAYOUT'=>'Текуща подредба',
'LBL_CURRENCY' => 'Валута',
'LBL_CUSTOM' => 'Персонализиран',
'LBL_DASHLET'=>'Панели в електронното табло',
'LBL_DASHLETLISTVIEW'=>'Списък на записите в панелите',
'LBL_DASHLETSEARCH'=>'Форма за търсене на панели',
'LBL_POPUP'=>'Изкачащи прозорци',
'LBL_POPUPLIST'=>'Списък със записи на изкачащи прозорци',
'LBL_POPUPLISTVIEW'=>'Списък със записи на изкачащи прозорци',
'LBL_POPUPSEARCH'=>'Форма за търсене в изкачащи прозорци',
'LBL_DASHLETSEARCHVIEW'=>'Форма за търсене на панели',
'LBL_DISPLAY_HTML'=>'Показване на HTML',
'LBL_DETAILVIEW'=>'Форма за визуализиране на детайли',
'LBL_DROP_HERE' => '[Поставете тук]',
'LBL_EDIT'=>'Редактирай',
'LBL_EDIT_LAYOUT'=>'Редактиране на подредби',
'LBL_EDIT_ROWS'=>'Редактиране на редове',
'LBL_EDIT_COLUMNS'=>'Редактиране на колони',
'LBL_EDIT_LABELS'=>'Редактиране на етикети',
'LBL_EDIT_PORTAL'=>'Редактиране на портал за',
'LBL_EDIT_FIELDS'=>'Редактиране на полета',
'LBL_EDITVIEW'=>'Форма за редактиране',
'LBL_FILTER_SEARCH' => "Търси",
'LBL_FILLER'=>'(Филтър)',
'LBL_FIELDS'=>'Полета',
'LBL_FAILED_TO_SAVE' => 'Запазването е неуспешно',
'LBL_FAILED_PUBLISHED' => 'Публикуването е неуспешно',
'LBL_HOMEPAGE_PREFIX' => 'Мои',
'LBL_LAYOUT_PREVIEW'=>'Преглед на подредбата',
'LBL_LAYOUTS'=>'Подредба на екрани',
'LBL_LISTVIEW'=>'Списък на записите',
'LBL_RECORDVIEW'=>'Съдържание на записа',
'LBL_MODULE_TITLE' => 'Студио',
'LBL_NEW_PACKAGE' => 'Нов пакет',
'LBL_NEW_PANEL'=>'Нов панел',
'LBL_NEW_ROW'=>'Нов ред',
'LBL_PACKAGE_DELETED'=>'Модулът е изтрит',
'LBL_PUBLISHING' => 'В процес на публикуване ...',
'LBL_PUBLISHED' => 'Стартирал',
'LBL_SELECT_FILE'=> 'Избиране на файл',
'LBL_SAVE_LAYOUT'=> 'Запазване на подредба',
'LBL_SELECT_A_SUBPANEL' => 'Избиране на панел със свързани записи',
'LBL_SELECT_SUBPANEL' => 'Избери панел със свързани записи',
'LBL_SUBPANELS' => 'Панели със свързани записи',
'LBL_SUBPANEL' => 'Панел със свързани записи',
'LBL_SUBPANEL_TITLE' => 'Длъжност:',
'LBL_SEARCH_FORMS' => 'Търси',
'LBL_STAGING_AREA' => 'Работно пространство (преместете чрез влачене елементите тук)',
'LBL_SUGAR_FIELDS_STAGE' => 'Полета на Sugar (щракнете върху елементите за добавяне към работното пространство)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (щракнете върху елементите, за да ги добавите към работното пространство)',
'LBL_TOOLBOX' => 'Инструменти',
'LBL_VIEW_SUGAR_FIELDS' => 'Преглед на полетата на Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Преглед на Sugar Bin',
'LBL_QUICKCREATE' => 'Кратка форма за създаване',
'LBL_EDIT_DROPDOWNS' => 'Редактиране на глобално падащо меню',
'LBL_ADD_DROPDOWN' => 'Добавяне на ново глобално падащо меню',
'LBL_BLANK' => '-празно-',
'LBL_TAB_ORDER' => 'Етикет на поръчка',
'LBL_TAB_PANELS' => 'Използвай табулатори',
'LBL_TAB_PANELS_HELP' => 'Панели във формата да бъдат визуализирани в отделни табове, а не на една страница с падащо квадратче "тип" <br />',
'LBL_TABDEF_TYPE' => 'Display Type:',
'LBL_TABDEF_TYPE_HELP' => 'Изберете как да бъде визуализирана секцията. Опцията е валидна само в случай, че са разрешени табулатори в този изглед.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Табулатор',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Панел',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Изберете Панел, за да се покаже този панел в изгледа на подредбата. Изберете Табулатор, за да се покаже този панел в отделен табулатор в рамките на подредбата. Когато даден Табулатор е зададен за даден панел, следващите панели, настроени да се показват като Панел, ще се покажат в табулатора.<br/>Нов Табулатор ще бъде стартиран за следващия панел, за който е избран Табулатора. Ако Табулаторът е избран за панел под първия панел, първият панел задължително ще бъде Табулатор.',
'LBL_TABDEF_COLLAPSE' => 'Скрий',
'LBL_TABDEF_COLLAPSE_HELP' => 'Изберете, за да направите статуса по подразбиране на този панел скрит.',
'LBL_DROPDOWN_TITLE_NAME' => 'Име',
'LBL_DROPDOWN_LANGUAGE' => 'Език по подразбиране',
'LBL_DROPDOWN_ITEMS' => 'Dropdown Items',
'LBL_DROPDOWN_ITEM_NAME' => 'Име на падащото меню',
'LBL_DROPDOWN_ITEM_LABEL' => 'Етикет',
'LBL_SYNC_TO_DETAILVIEW' => 'Синхронизирай с формата за визуализиране на детайли',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Маркирайте тази опция, за да синхронизирате формата за редактиране с тази за визуализиране на детайли. При натискане на бутона Съхрани, полетата и тяхната подредба във формата за редактиране<br>ще бъдат синхронизирани и записани автоматично и във формата за визуализиране на детайли.<br>Промени в подредбата за визуализиране на детайли няма да могат да бъдат правени.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'This DetailView is sync&#39;d with the corresponding EditView.<br> Fields and field placement in this DetailView reflect the fields and field placement in the EditView.<br> Changes to the DetailView cannot be saved or deployed within this page. Make changes or un-sync the layouts in the EditView. ',
'LBL_COPY_FROM' => 'Копирай стойност от:',
'LBL_COPY_FROM_EDITVIEW' => 'Копирай от формата за редактиране',
'LBL_DROPDOWN_BLANK_WARNING' => 'Изискват се стойности както за Елемент, така и за Етикета. За да добавите празен елемент, щракнете върху Добави без да въвеждате стойности за Елемент и Етикет.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Ключът съществува в списъка',
'LBL_DROPDOWN_LIST_EMPTY' => 'Списъкът трябва да съдържа поне една разрешена позиция',
'LBL_NO_SAVE_ACTION' => 'Скриптът за запис на този изглед не може да бъде намерен.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: неправилно оформен документ',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Посочва поле на комбинация. Полето на комбинация е сбор от отделни полета. Например "Адрес" е поле на комбинация, което съдържа "Адрес", "Град", "Пощенски код", "Щат" и "Държава".<br><br>Щракнете два пъти върху поле на комбинация, за да видите кои полета съдържа.',
'LBL_COMBO_FIELD_CONTAINS' => 'съдържа:',

'LBL_WIRELESSLAYOUTS'=>'Подредба на мобилни екрани',
'LBL_WIRELESSEDITVIEW'=>'Форма за редактиране',
'LBL_WIRELESSDETAILVIEW'=>'Форма за визуализиране',
'LBL_WIRELESSLISTVIEW'=>'Списък на записите',
'LBL_WIRELESSSEARCH'=>'Търсене',

'LBL_BTN_ADD_DEPENDENCY'=>'Добави зависимостта',
'LBL_BTN_EDIT_FORMULA'=>'Редактирай формулата',
'LBL_DEPENDENCY' => 'Зависимост',
'LBL_DEPENDANT' => 'Визуализира се по формула',
'LBL_CALCULATED' => 'Изчислявано по формула',
'LBL_READ_ONLY' => 'Само за четене',
'LBL_FORMULA_BUILDER' => 'Създаване на формула',
'LBL_FORMULA_INVALID' => 'Невалидна формула',
'LBL_FORMULA_TYPE' => 'Формулата трябва да бъде от тип',
'LBL_NO_FIELDS' => 'Полето не е намерено',
'LBL_NO_FUNCS' => 'Функцията не е намерена',
'LBL_SEARCH_FUNCS' => 'Търсене на функция...',
'LBL_SEARCH_FIELDS' => 'Търсене на поле...',
'LBL_FORMULA' => 'Формула',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Зависимост',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Дърпайте опции от списъка в ляво на наличните опции във визуализираното по формула падащото меню към списъците в дясно, за да направите достъпни тези опции при избиране на основната опция. Ако в основната опция няма елементи при избирането и, визуализираното по формула падащото меню няма да се покаже.',
'LBL_AVAILABLE_OPTIONS' => 'Опции',
'LBL_PARENT_DROPDOWN' => 'Основно падащо меню',
'LBL_VISIBILITY_EDITOR' => 'Редактор на видимостта',
'LBL_ROLLUP' => 'С натрупване',
'LBL_RELATED_FIELD' => 'Свързано поле',
'LBL_CONFIG_PORTAL_URL'=>'URL to custom logo image. Препоръчваните размери са 163 × 18 точки.',
'LBL_PORTAL_ROLE_DESC' => 'Не изтривайте тази потребителска роля. Тази роля на Потребителския портал е системно генерирана в процеса на активиране на Sugar портал. Използвайте контролите в ролята, за да разрешите/забраните модулите Проблеми, Казуси или База от знания в Sugar портал. Не модифицирайте никоя от останалите контроли за тази роля, за да избегнете потенциално некоректно поведение на системата. В случай че случайно изтриете тази роля, създайте я отново като забраните и след това отново разрешите Sugar портал.',

//RELATIONSHIPS
'LBL_MODULE' => 'Модул',
'LBL_LHS_MODULE'=>'Основен модул',
'LBL_CUSTOM_RELATIONSHIPS' => '* връзката с другите модули е създадена чрез "Студио" или "Създаване на нови модули"',
'LBL_RELATIONSHIPS'=>'Връзки с други модули',
'LBL_RELATIONSHIP_EDIT' => 'Редактиране на връзка с друг модул',
'LBL_REL_NAME' => 'Име',
'LBL_REL_LABEL' => 'Етикет',
'LBL_REL_TYPE' => 'Тип',
'LBL_RHS_MODULE'=>'Свързан модул',
'LBL_NO_RELS' => 'Няма дефинирани връзки с други модули',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Състояние по избор' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Колона',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Стойност',
'LBL_SUBPANEL_FROM'=>'Визуализиран панел',
'LBL_RELATIONSHIP_ONLY'=>'За тази връзка няма да бъдат създадени видими елементи, тъй като вече има съществуваща видима връзка между тези два модула.',
'LBL_ONETOONE' => 'Един към един',
'LBL_ONETOMANY' => 'Един към много',
'LBL_MANYTOONE' => 'Много към един',
'LBL_MANYTOMANY' => 'Много към много',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Изберете компонент.',
'LBL_QUESTION_MODULE1' => 'Изберете модул.',
'LBL_QUESTION_EDIT' => 'Изберете модул, който да бъде редактиран.',
'LBL_QUESTION_LAYOUT' => 'Изберете подредба, която да бъде редактирана.',
'LBL_QUESTION_SUBPANEL' => 'Изберете панел със свързани записи, който да бъде редактиран.',
'LBL_QUESTION_SEARCH' => 'Изберете форма за търсене, която да бъде редактирана.',
'LBL_QUESTION_MODULE' => 'Изберете компонент на модула, който да бъде редактиран.',
'LBL_QUESTION_PACKAGE' => 'Изберете пакет, който да редактирате или създайте нов.',
'LBL_QUESTION_EDITOR' => 'Изберете инструмент.',
'LBL_QUESTION_DROPDOWN' => 'Изберете падащо меню за редактиране или създайте ново.',
'LBL_QUESTION_DASHLET' => 'Изберете раздел, който да бъде редактиран.',
'LBL_QUESTION_POPUP' => 'Изберете изкачащ прозорец, който да бъде редактиран.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Свързано с',
'LBL_NAME'=>'Име',
'LBL_LABELS'=>'Етикети',
'LBL_MASS_UPDATE'=>'Масова актуализация',
'LBL_AUDITED'=>'История на промените',
'LBL_CUSTOM_MODULE'=>'Модул',
'LBL_DEFAULT_VALUE'=>'Стойност по подразбиране',
'LBL_REQUIRED'=>'Задължително',
'LBL_DATA_TYPE'=>'Тип',
'LBL_HCUSTOM'=>'ПЕРСОНАЛИЗИРАН',
'LBL_HDEFAULT'=>'ПО ПОДРАЗБИРАНЕ',
'LBL_LANGUAGE'=>'Език:',
'LBL_CUSTOM_FIELDS' => '* поле създадено в Студио',

//SECTION
'LBL_SECTION_EDLABELS' => 'Редактиране на етикети',
'LBL_SECTION_PACKAGES' => 'Модули',
'LBL_SECTION_PACKAGE' => 'Пакет:',
'LBL_SECTION_MODULES' => 'Модули',
'LBL_SECTION_PORTAL' => 'Портал',
'LBL_SECTION_DROPDOWNS' => 'Падащи менюта',
'LBL_SECTION_PROPERTIES' => 'Параметри',
'LBL_SECTION_DROPDOWNED' => 'Редактор на падащи менюта',
'LBL_SECTION_HELP' => 'Помощ',
'LBL_SECTION_ACTION' => 'Действие',
'LBL_SECTION_MAIN' => 'Главен',
'LBL_SECTION_EDPANELLABEL' => 'Редактиране на етикети',
'LBL_SECTION_FIELDEDITOR' => 'Редактор на поле',
'LBL_SECTION_DEPLOY' => 'Публикуване',
'LBL_SECTION_MODULE' => 'Модул',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Редактиране на видимостта',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'По подразбиране',
'LBL_HIDDEN'=>'Скрит',
'LBL_AVAILABLE'=>'Налични',
'LBL_LISTVIEW_DESCRIPTION'=>'Долу са представени три колони. Колоната <b>По подразбиране</b> включва полета, показвани в към момента в списъка със записите.  Колоната <b>Налични</b> съдържа полета, с които потребителите да създават собствена подредба на списъка на записите.  Колоната <b>Скрити</b> съдържа полета видими само от администратори, с възможност за включване към колоната с визуализирани по подразбиране и налични полета.',
'LBL_LISTVIEW_EDIT'=>'Редактор на листа на записите',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Прегледай',
'LBL_MB_RESTORE'=>'Възстанови',
'LBL_MB_DELETE'=>'Изтрий',
'LBL_MB_COMPARE'=>'Сравни',
'LBL_MB_DEFAULT_LAYOUT'=>'Подредба на екрани',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Добави',
'LBL_BTN_SAVE'=>'Съхрани',
'LBL_BTN_SAVE_CHANGES'=>'Съхрани промените',
'LBL_BTN_DONT_SAVE'=>'Игнорирай промените',
'LBL_BTN_CANCEL'=>'Отмени',
'LBL_BTN_CLOSE'=>'Затвори',
'LBL_BTN_SAVEPUBLISH'=>'Съхрани и публикувай',
'LBL_BTN_NEXT'=>'Следваща',
'LBL_BTN_BACK'=>'Върни',
'LBL_BTN_CLONE'=>'Дублирай',
'LBL_BTN_COPY' => 'Копирай',
'LBL_BTN_COPY_FROM' => 'Копиране от ...',
'LBL_BTN_ADDCOLS'=>'Добавяне на колони',
'LBL_BTN_ADDROWS'=>'Добавяне на редове',
'LBL_BTN_ADDFIELD'=>'Добавяне на поле',
'LBL_BTN_ADDDROPDOWN'=>'Добавяне на падащо меню',
'LBL_BTN_SORT_ASCENDING'=>'Във възходящ ред',
'LBL_BTN_SORT_DESCENDING'=>'В нисходящ ред',
'LBL_BTN_EDLABELS'=>'Редактиране на етикети',
'LBL_BTN_UNDO'=>'Върни',
'LBL_BTN_REDO'=>'Повтори',
'LBL_BTN_ADDCUSTOMFIELD'=>'Добавяне на потребителско поле',
'LBL_BTN_EXPORT'=>'Експортиране на настройки',
'LBL_BTN_DUPLICATE'=>'Дублирай',
'LBL_BTN_PUBLISH'=>'Публикувай',
'LBL_BTN_DEPLOY'=>'Инсталирай',
'LBL_BTN_EXP'=>'Експортирай',
'LBL_BTN_DELETE'=>'Изтрий',
'LBL_BTN_VIEW_LAYOUTS'=>'Подредба на екрани',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Мобилни екрани',
'LBL_BTN_VIEW_FIELDS'=>'Полета',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Връзки с други модули',
'LBL_BTN_ADD_RELATIONSHIP'=>'Добави връзка с друг модул',
'LBL_BTN_RENAME_MODULE' => 'Промяна на име на модул',
'LBL_BTN_INSERT'=>'Вмъкни',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Грешка: полетата вече съществуват',
'ERROR_INVALID_KEY_VALUE'=> "Грешка: Невалидна стойност на ключ: [&#39;]",
'ERROR_NO_HISTORY' => 'Не са намерени запазени предишни подредби на модула',
'ERROR_MINIMUM_FIELDS' => 'Тази подредба трябва да съдържа поне едно поле',
'ERROR_GENERIC_TITLE' => 'Грешка',
'ERROR_REQUIRED_FIELDS' => 'Сигурни ли сте, че искате да продължите? Следните задължителни полета липсват в текущата подредба',
'ERROR_ARE_YOU_SURE' => 'Сигурни ли сте, че искате да продължите?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Следните полета се изчисляват по формула и няма да бъдат преизчислени в реално време при отваряне на формата за редактиране в SugarCRM Mobile:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Следните полета се изчисляват по формула и няма да бъдат преизчислени в реално време при отваряне на формата за редактиране в SugarCRM Portal:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Следните модули са забранени:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Ако искате да ги разрешите в портала, можете да го направите <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">тук</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Конфигурация на Портал',
    'LBL_PORTAL_THEME' => 'Портал теми',
    'LBL_PORTAL_ENABLE' => 'Активирай',
    'LBL_PORTAL_SITE_URL' => 'Порталният сайт се намира на адрес:',
    'LBL_PORTAL_APP_NAME' => 'Име на приложението',
    'LBL_PORTAL_LOGO_URL' => 'URL адрес на логото',
    'LBL_PORTAL_LIST_NUMBER' => 'Брой на показвани записи в списъка',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Брой на показвани полета във "Форма за визуализация"',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Брой резултати, които да бъдат показвани пори Глобално търсене',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Отговорник по подразбиране за новите регистрации в портала.',

'LBL_PORTAL'=>'Портал',
'LBL_PORTAL_LAYOUTS'=>'Подредби в Портал',
'LBL_SYNCP_WELCOME'=>'Моля, въведете URL на портала за актуализация.',
'LBL_SP_UPLOADSTYLE'=>'Изберете лист със стилове, които да заредите от вашия компютър.<br>Листът със стилове ще бъде изпълнен в Портала на Sugar следващия път, когато извършвате синхронизиране.',
'LBL_SP_UPLOADED'=> 'Заредени',
'ERROR_SP_UPLOADED'=>'Моля, убедете се, че зарежданият лист със стилове е css.',
'LBL_SP_PREVIEW'=>'Това е преглед на изгледа на Портала на Sugar с използване на листа със стилове.',
'LBL_PORTALSITE'=>'Sugar Portal URL:',
'LBL_PORTAL_GO'=>'Начало',
'LBL_UP_STYLE_SHEET'=>'Заредете Списък със стилове',
'LBL_QUESTION_SUGAR_PORTAL' => 'Изберете подредба на портала на Sugar, която да редактирате.',
'LBL_QUESTION_PORTAL' => 'Изберете подредба на портала, която да редактирате.',
'LBL_SUGAR_PORTAL'=>'Портал - редактор',
'LBL_USER_SELECT' => '-- Изберете --',

//PORTAL PREVIEW
'LBL_CASES'=>'Казуси',
'LBL_NEWSLETTERS'=>'Бюлетини',
'LBL_BUG_TRACKER'=>'Проблеми',
'LBL_MY_ACCOUNT'=>'Персонални настройки',
'LBL_LOGOUT'=>'Изход',
'LBL_CREATE_NEW'=>'Създай нов',
'LBL_LOW'=>'Ниска',
'LBL_MEDIUM'=>'Средна',
'LBL_HIGH'=>'Висока',
'LBL_NUMBER'=>'Номер:',
'LBL_PRIORITY'=>'Степен на важност:',
'LBL_SUBJECT'=>'Относно',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Име:',
'LBL_MODULE_NAME'=>'Име:',
'LBL_MODULE_NAME_SINGULAR' => 'Име на единичен модул:',
'LBL_AUTHOR'=>'Автор:',
'LBL_DESCRIPTION'=>'Описание:',
'LBL_KEY'=>'Код:',
'LBL_ADD_README'=>' Информация',
'LBL_MODULES'=>'Модули:',
'LBL_LAST_MODIFIED'=>'Последна модификация:',
'LBL_NEW_MODULE'=>'Нов модул',
'LBL_LABEL'=>'Етикет в множествено число',
'LBL_LABEL_TITLE'=>'Етикет',
'LBL_SINGULAR_LABEL' => 'Етикет в единствено число',
'LBL_WIDTH'=>'Ширина',
'LBL_PACKAGE'=>'Пакет:',
'LBL_TYPE'=>'Тип:',
'LBL_TEAM_SECURITY'=>'Екипна сигурност',
'LBL_ASSIGNABLE'=>'Отговорници',
'LBL_PERSON'=>'Свързани с тип контакт',
'LBL_COMPANY'=>'Свързани с тип организация',
'LBL_ISSUE'=>'Свързани с тип казус',
'LBL_SALE'=>'Свързани с тип сделка',
'LBL_FILE'=>'Файл',
'LBL_NAV_TAB'=>'Табулатор',
'LBL_CREATE'=>'Създай',
'LBL_LIST'=>'Списък',
'LBL_VIEW'=>'Изглед',
'LBL_LIST_VIEW'=>'Списък на записите',
'LBL_HISTORY'=>'История',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Възстановяване на подредбата по подразбиране',
'LBL_ACTIVITIES'=>'Дейности',
'LBL_SEARCH'=>'Търси',
'LBL_NEW'=>'Нов',
'LBL_TYPE_BASIC'=>'свързани с тип базов',
'LBL_TYPE_COMPANY'=>'свързани с тип организация',
'LBL_TYPE_PERSON'=>'Свързани с тип контакт',
'LBL_TYPE_ISSUE'=>'Свързани с тип казус',
'LBL_TYPE_SALE'=>'Свързани с тип сделка',
'LBL_TYPE_FILE'=>'файл',
'LBL_RSUB'=>'Това е панел със свързани записи, който ще бъдат показан във вашия модул',
'LBL_MSUB'=>'Това е панел със свързани записи, който вашия модул предоставя на свързания модул за показване',
'LBL_MB_IMPORTABLE'=>'Позволи импорт',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'видим',
'LBL_VE_HIDDEN'=>'скрит',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] беше изтрит',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Експортиране на настройки',
'LBL_EC_NAME'=>'Пакет:',
'LBL_EC_AUTHOR'=>'Автор:',
'LBL_EC_DESCRIPTION'=>'Описание:',
'LBL_EC_KEY'=>'Код:',
'LBL_EC_CHECKERROR'=>'Моля, изберете модул.',
'LBL_EC_CUSTOMFIELD'=>'променени полета',
'LBL_EC_CUSTOMLAYOUT'=>'променени подредби на екрани',
'LBL_EC_CUSTOMDROPDOWN' => 'персонализирано падащо меню(та)',
'LBL_EC_NOCUSTOM'=>'Няма персонализирани модули.',
'LBL_EC_EXPORTBTN'=>'Експортирай',
'LBL_MODULE_DEPLOYED' => 'Модулът беше инсталиран.',
'LBL_UNDEFINED' => 'недефиниран',
'LBL_EC_CUSTOMLABEL'=>'модифицирани етикети',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Няма данни, които да бъдат визуализирани',
'LBL_AJAX_TIME_DEPENDENT' => 'A time dependent action is in progress please wait and try again in a few seconds',
'LBL_AJAX_LOADING' => 'Зареждане...',
'LBL_AJAX_DELETING' => 'Изтриване...',
'LBL_AJAX_BUILDPROGRESS' => 'Създаването е в процес на изпълнение...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Инсталиране...',
'LBL_AJAX_FIELD_EXISTS' =>'Въведеното име на поле вече съществува. Моля въведете ново име.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Сигурни ли сте, че искате да изтриете този пакет? Всички файлове асоциирани с пакета ще бъдат премахнати.',
'LBL_JS_REMOVE_MODULE' => 'Сигурни ли сте, че искате да изтриете този модул? Всички файлове асоциирани с модула ще бъдат премахнати.',
'LBL_JS_DEPLOY_PACKAGE' => 'Всички настройки извършени в Студио ще бъдат загубени, когато този модул бъде инсталиран отново. Сигурни ли сте, че искате да продължите?',

'LBL_DEPLOY_IN_PROGRESS' => 'Инсталиране на пакета',
'LBL_JS_VALIDATE_NAME'=>'Име - Трябва да бъде последователност от букви и цифри без интервали и да започва с буква',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'Ключът на пакета вече съществува',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'Съществува пакет с такова име',
'LBL_JS_PACKAGE_NAME'=>'Име на пакета - трябва да започва с буква и може да се състои само от букви, цифри и долни черти. Не могат да се използват интервали или други специални знаци.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Ключ - Трябва да съдържа комбинация от букви и цифри, започваща с буква.',
'LBL_JS_VALIDATE_KEY'=>'Ключ - Трябва да бъде последователност от букви и цифри без интервали и да започва с буква',
'LBL_JS_VALIDATE_LABEL'=>'Моля изберете етикет, който да се използва за заглавие на този модул',
'LBL_JS_VALIDATE_TYPE'=>'От списъка долу изберете типа на модул, който искате да създадете',
'LBL_JS_VALIDATE_REL_NAME'=>'Име - трябва да включва последователност от букви и цифри без интервали между тях',
'LBL_JS_VALIDATE_REL_LABEL'=>'Етикет - добавете етикет за показване над панела със свързани записи',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Сигурни ли сте, че искате да изтриете този задължителен ред от падащия списък? Това може да засегне функционалността на вашето приложение.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Сигурни ли сте, че искате да изтриете този ред от падащия списък? Изтриването на етапите Спечелени или Загубени на етапа на преговори ще наруши функционалността на модул Прогнози',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Сигурни ли сте, че желаете да изтриете статуса Нов за водените търговски преговори. Изтриването на статуса ще доведе до нарушаване на работата на автоматизираните задачи, свързани с Приходни позиции в отделните Възможности.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Сигурни ли сте, че желаете да изтриете статуса В процес на изпълнение за водените търговски преговори. Изтриването на статуса ще доведе до нарушаване на работата на автоматизираните задачи, свързани с Приходни позиции в отделните Възможности.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Сигурни ли сте, че искате да изтриете етапите Спечелени и Загубени на етапа на преговори? Изтриването на тези етапи ще наруши функционалността на модул Прогнози',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Сигурни ли сте, че искате да изтриете етапa на преговори Загубени? Изтриването на този етап ще наруши функционалността на модул Прогнози',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Сигурни ли сте, че искате да изтриете връзката с другия модул?<br>Note: This operation may not complete for several minutes.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Връзката с модула ще бъде записана в базата. Сигурни ли сте, че искате да съхраните тази връзка?',
'LBL_CONFIRM_DONT_SAVE' => 'Има налични промени от момента на последното Ви запазване. Искате ли да запазите?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Запази промените?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Част от данните вероятно ще бъдат отрязани и не могат да бъдат възстановени в последствие. Сигурни ли сте, че искате да продължите?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Изберете типа данни, които ще бъдат попълвани в полето.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Конфигурирайте полето, за да може напълно да се търси текст в него.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Увеличаването е процесът на повишаване на приложимостта на полетата на даден запис.<br />На полетата с по-високо ниво на увеличаване ще бъде дадена по-голяма тежест при извършване на търсенето. При извършване на търсене, съответстващите записи, съдържащи полета с по-голяма тежест, ще се появят по-напред в резултатите от търсенето.<br />Стойността по подразбиране е 1.0, което означава неутрално увеличаване. За прилагане на положително увеличаване се приема всяка плаваща стойност, по-висока от 1. За отрицателно увеличаване използвайте стойности, по-ниски от 1. Например стойност 1.35 ще увеличи положително дадено поле със 135 %. Използването на стойност 0.60 ще приложи отрицателно увеличаване.<br />Имайте предвид, че в предишните версии беше необходимо да се извърши реиндексиране на търсене в целия текст. Това вече не е необходимо.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Да</b>: Полето ще бъде включено в процеса на импортиране.<br><b>Не</b>: Полето няма да бъде включено в процеса на импортиране.<br><b>Задължително</b>: Стойността на полето не трябва да е празно при извършване на импорт на данни.',
'LBL_POPHELP_PII'=>'Това поле ще бъде маркирано автоматично за одит и налично в изгледа на Личните данни.<br>Полетата с лични данни също могат да бъдат премахнати ако записът е свързан със заявка за изтриване поради Поверителност на данните. <br>Изтриването става чрез модула за Поверителност на данните и може да се изпълни от администраторите или потребителите с функция на Мениджър Защита на личните данни.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Въведете число за Ширина, измерена в пиксели.<br>Заредената снимка ще бъде пробразувана до тези размери на Ширината.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Въведете число за Височината, измерена в пиксели.<br>Заредената снимка ще бъде пробразувана до тези размери на Височината.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Маркирайте полето ако желаете то да бъде използвано при Глоблано търсене в настояшия модул.',
//Revert Module labels
'LBL_RESET' => 'Изпълни',
'LBL_RESET_MODULE' => 'Първоначални настройки',
'LBL_REMOVE_CUSTOM' => 'Първоначални настройки',
'LBL_CLEAR_RELATIONSHIPS' => 'Премахване на създадени в Studio връзки с други модули',
'LBL_RESET_LABELS' => 'Връщане на стандартните етикети на полетата',
'LBL_RESET_LAYOUTS' => 'Връщане на стандартната подредба на формите за визуализация',
'LBL_REMOVE_FIELDS' => 'Премахване на полетата създадени в Studio',
'LBL_CLEAR_EXTENSIONS' => 'Премахване на разширенията към модула',

'LBL_HISTORY_TIMESTAMP' => 'Дата и час на запазване',
'LBL_HISTORY_TITLE' => 'история',

'fieldTypes' => array(
                'varchar'=>'Текст',
                'int'=>'Цяло число',
                'float'=>'С плаваща запетая',
                'bool'=>'Отметка',
                'enum'=>'Падащо меню',
                'multienum' => 'Падащо меню - "multiselect"',
                'date'=>'Дата',
                'phone' => 'Телефон',
                'currency' => 'Валута',
                'html' => 'HTML',
                'radioenum' => 'Radio бутон',
                'relate' => 'Релационно поле',
                'address' => 'Адрес',
                'text' => 'Текстов параграф',
                'url' => 'Уеб адрес',
                'iframe' => 'IFrame',
                'image' => 'Изображение',
                'encrypt'=>'Шифровай',
                'datetimecombo' =>'Дата и Час',
                'decimal'=>'Десетично число',
),
'labelTypes' => array(
    "" => "Често използвани етикети",
    "all" => "Всички етикети",
),

'parent' => 'Гъвкаво свързване',

'LBL_ILLEGAL_FIELD_VALUE' =>"Ключът на падащото меню не може да съдържа кавички.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Избирате този елемент за отстраняване от списъка на падащото меню. Всички полета на падащото меню, които използват този списък с този елемент като стойност, повече няма да показват стойността, а стойността повече няма да може да бъде избирана от полетата на падащото меню. Сигурни ли сте, че искате да продължите?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Всички модули',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (related {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Копирай от подредба',
);
