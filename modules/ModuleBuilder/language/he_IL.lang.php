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
    'LBL_LOADING' => 'טוען ...' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'הסתר אפשרויות' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'מחק' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'מופעל על ידי SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'תפקיד',
'help'=>array(
    'package'=>array(
            'create'=>'Provide a <b>Name</b> for the package.  The name you enter must be alphanumeric and contain no spaces. (Example: HR_Management)<br/><br/> You can provide <b>Author</b> and <b>Description</b> information for package. <br/><br/>Click <b>Save</b> to create the package.',
            'modify'=>'הערכים והפעולות האפשריות עבור <b>החבילה</b> מופיעים כאן.<br><br>באפשרותך לשנות את <b>השם</b>, <b>המחבר</b> ואת <b>התיאור</b> של החבילה, וכמו כן להציג להתאים אישית את כל המודולים שמוכלים בתוך החבילה.<br><br>לחץ על <b>מודול חדש</b> כדי ליצור מודול עבור החבילה.<br><br>אם החבילה מכילה לפחות מודול אחד, באפשרותך <b>לפרסם</b> <b>ולפרוש</b> את החבילה, וכמו כן <b>לייצא</b> את ההתאמות האישיות שנערכו בחבילה.',
            'name'=>'This is the <b>Name</b> of the current package. <br/><br/>The name you enter must be alphanumeric, start with a letter and contain no spaces. (Example: HR_Management)',
            'author'=>'זה <b>המחבר</b> שמופיע במהלך ההתקנה בתור שם הישות שיצרה את החבילה.<br><br>המחבר יכול להיות אדם או חברה.',
            'description'=>'זה <b>התיאור</b> של החבילה שמופיע במהלך ההתקנה.',
            'publishbtn'=>'לחץ על <b>פרסום</b> כדי לשמור את כל הנתונים שהוזנו וליצור קובץ .zip שמהווה גרסת התקנה של החבילה.<br><br>השתמש ב<b>טוען המודולים</b> כדי להעלות או הקובץ .zip ולהתקין את החבילה.',
            'deploybtn'=>'לחץ על <b>פרישה</b> כדי לשמור את כל הנתונים שהוזנו ולהתקין את החבילה, כולל כל המודולים, במופע הנוכחי.',
            'duplicatebtn'=>'לחץ על <b>שכפול</b> כדי להעתיק את התכנים של החבילה לתוך חבילה חדשה ולהציג את החבילה החדשה. <br/><br/>עבור החבילה החדשה, שם חדש ייווצר אוטומטית על ידי צירוף מספר לסוף שמה של חבילה שמשמש ליצירת החבילה החדשה. תוכל לשנות את השם של החבילה החדשה על ידי הזנת <b>שם</b> חדש ולחיצה על <b>שמירה</b>.',
            'exportbtn'=>'לחץ על <b>ייצוא</b> כדי ליצור קובץ .zip שמכיל את ההתאמות האישיות שנערכו בחבילה.<br><br> הקבצים שנוצרים אינם גרסת התקנה של החבילה.<br><br>השתמש ב<b>טוען המודולים</b> כדי לייבא את הקובץ .zip ולגרום להופעת החבילה, כולל ההתאמות האישיות, בבונה המודולים.',
            'deletebtn'=>'לחץ על <b>מחיקה</b> כדי למחוק חבילה זאת ואת כל הקבצים הקשורים אליה.',
            'savebtn'=>'לחץ על <b>שמירה</b> כדי לשמור את כל הנתונים שהוזנו הקשורים לחבילה.',
            'existing_module'=>'לחץ על הסמל <b>מודול</b> כדי לערוך את הערכים ולהתאים אישית שדות, מערכות יחסים ותצורות המשויכים למודול.',
            'new_module'=>'לחץ על <b>מודול חדש</b> כדי ליצור מודול חדש עבור חבילה זאת.',
            'key'=>'<b>מפתח</b> זה בעל חמש אותיות וספרות ישמש בתור התחילית של כל הספריות, שמות הקלאסים וטבלאות מסד הנתונים של כל המודולים בחבילה הנוכחית.<br><br>המפתח נועד להשיג אחידות בשמות הטבלאות.',
            'readme'=>'לחץ על הוספת טקסט <b>קרא אותי</b> לחבילה זאת.<br><br>הטקסט יהיה זמין בעת ההתקנה.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'הזן <b>שם</b> למודול. <b>התווית</b> שתזין תופיע בלשונית הניווט.<br/><br/>בחר להציג לשונית ניווט עבור המודול על ידי סימון תיבת הסימון של <b>לשונית ניווט</b>.<br/><br/>סמן את תיבת הסימון של <b>אבטחת צוות</b> כדי להציג שדה בחירת צוות בתוך רישומי המודול. <br/><br/>לאחר מכן בחר את סוג המודול שברצונך ליצור. <br/><br/>בחר סוג תבנית. כל תבנית מכילה סט ספציפי של שדות, וכמו כן תצורות מוגדרות מראש, שבהם ניתן להשתמש כבסיס למודול שלך. <br/><br/>לחץ על <b>שמירה</b> כדי ליצור את המודול.',
        'modify'=>'תוכל לשנות את ערכי המודול או להתאים אישית <b>שדות</b>, <b>מערכות יחסים</b> ו<b>תצורות</b> הקשורים למודול.',
        'importable'=>'סימון תיבת הסימון <b>ניתן לייבוא</b> יפעיל ייבוא עבור מודול זה.<br><br>קישור לאשף הייבוא יופיע בפאנל קיצורי הדרך במודול. אשף הייבוא מסייע בייבוא נתונים ממקורות חיצוניים לתוך המודול המותאם אישית.',
        'team_security'=>'Checking the <b>Team Security</b> checkbox will enable team security for this module.  <br/><br/>If team security is enabled, the Team selection field will appear within the records in the module',
        'reportable'=>'סימון תיבה זו יאפשר הרצת דו"חות כנגד מודול זה.',
        'assignable'=>'סימון תיבה זו יאפשר להקצות רישום במודול זה למשתמש נבחר.',
        'has_tab'=>'סימון <b>לשונית ניווט</b> יוסיף לשונית ניווט למודול.',
        'acl'=>'סימון תיבה זו יאפשר בקרי גישה במודול זה, כולל אבטחה ברמת שדה.',
        'studio'=>'סימון תיבה זו יאפשר למנהלי מערכת להתאים אישית מודול זה בתוך Studio.',
        'audit'=>'סימון תיבה זו יאפשר ביקורות עבור מודול זה. שינויים לשדות מסוימים יתועדו כך שמנהלי מערכת יוכלו לבדוק את היסטוריית השינויים.',
        'viewfieldsbtn'=>'לחץ על <b>הצג שדות</b> כדי להציג את השדות שמשויכים למודול וליצור ולערוך שדות מותאמים אישית.',
        'viewrelsbtn'=>'לחץ על <b>הצג מערכות יחסים</b> כדי להציג את מערכות היחסים שמשויכות למודול זה וכדי ליצור מערכות יחסים חדשות.',
        'viewlayoutsbtn'=>'לחץ על <b>הצג תצורות</b> כדי לצפות בתצורות עבור המודול וכדי להתאים אישית את סידור השדות בתוך התצורות.',
        'viewmobilelayoutsbtn' => 'לחץ על <b>הצג תצורות סלולריות</b> כדי לצפות בתצורות הסלולריות של המודול וכדי להתאים אישית את סידור השדות בתוך התצורות.',
        'duplicatebtn'=>'לחץ על <b>שכפל</b> כדי להעתיק את הערכים של המודול למודול חדש וכדי להציג את המודול החדש. <br/><br/>עבור המודול החדש, המערכת תיצור שם חדש אוטומטית על ידי צירוף מספר לסוף שם המודול אשר משמש ליצירת החדש.',
        'deletebtn'=>'לחץ על <b>מחיקה</b> כדי למחוק מודול זה.',
        'name'=>'זה ה<b>שם</b> של המודול הנוכחי.<br/><br/>השם חייב להכיל אותיות וספרות ולהתחיל באות, ולא להכיל רווחים. (לדוגמה: HR_Management)',
        'label'=>'This is the <b>Label</b> that will appear in the navigation tab for the module.',
        'savebtn'=>'לחץ על <b>שמירה</b> כדי לשמור את כל הנתונים שהוזנו אשר קשורים למודול.',
        'type_basic'=>'סוג התבנית <b>בסיסי</b> מספק שדות בסיסיים, כמו שם, מוקצה אל, צוות, תאריך יצירה ותיאור.',
        'type_company'=>'סוג התבנית <b>חברה</b> נותן שדות ספציפיים לארגון, כמו שם חברה, תעשייה וכתובת לחיוב.<br/><br/>השתמש בתבנית זו כדי ליצור מודולים שדומים למודול הרגיל חשבונות.',
        'type_issue'=>'סוג התבנית <b>בעיה</b> נותן שדות ספציפיים למקרה ולבאג, כמו מספר, מצב, עדיפות ותיאור.<br/><br/>השתמש בתבנית זו כדי ליצור מודולים שדומים למודולים הרגילים מקרים ומעקב באגים.',
        'type_person'=>'סוג התבנית <b>אדם</b> נותן שדות ספציפיים לאנשים, כמו ברכות, תואר, שם, כתובת ומספר טלפון.<br/><br/>השתמש בתבנית זו כדי ליצור מודולים שדומים למודולים הרגילים אנשי קשר ולידים.',
        'type_sale'=>'סוג התבנית <b>מכירה</b> נותן שדות ספציפיים להזדמנות, כמו מקור ליד, שלב, סכום ורווחיות. <br/><br/>השתמש בתבנית זו כדי ליצור מודולים שדומים למודול הרגיל הזדמנויות.',
        'type_file'=>'התבנית <b>קובץ</b> נותנת שדות ספציפיים למסמך, כמו שם קובץ, סוג מסמך, ותאריך פרסום.<br><br>השתמש בתבנית זו כדי ליצור מודולים שדומים למודול הרגיל מסמכים.',

    ),
    'dropdowns'=>array(
        'default' => 'כל <b>רשימות הגלילה</b> של היישום רשומות כאן.<br><br>ניתן להשתמש ברשימות הגלילה עבור שדות גלילה בכל מודול.<br><br>כדי לערוך שינויים לגלילה קיימת, לחץ על שם הגלילה.<br><br>לחץ על <b>הוסף גלילה</b> כדי ליצור גלילה חדשה.',
        'editdropdown'=>'ניתן להשתמש ברשימות גלילה עבור שדות גלילה רגילים או מותאמים אישית בכל מודול.<br><br>הזן <b>שם</b> לרשימה הנגללת.<br><br>במידה וחבילות שפה מותקנות ביישום, תוכל לבחור ב<b>שפה</b> כדי להשתמש בהן עבור פריטי הרשימה.<br><br>בשדה <b>שם פריט</b>, הזן שם לאפשרות ברשימה הנגללת. שם זה לא יופיע ברשימ הנגללת שגלויה למשתמשים. <br><br>בשדה <b>תווית תצוגה</b>, הזן תווית שתהיה גלויה למשתמשים.<br><br>לאחר הזנת שם הפריט ותווית התצוגה, לחץ על <b>הוספה</b> כדי להוסיף את הפריט לרשימה הנגללת.<br><br>כדי לסדר מחדש את הפריטים ברשימה, גרור ושחרר פריטים למקומות הרצויים.<br><br>כדי לערוך את תווית הצוגה של פריט, לחץ על <b>סמל העריכה</b> והזן תווית חדשה. כדי למחוק פריט מהרשימה הנגללת, לחץ על <b>סמל המחיקה</b>.<br><br>כדי לבטל שינוי שנערך בתווית תצוגה, לחץ על <b>בטל</b>. כדי לבצע שוב שינוי שבוטל, לחץ על <b>בצע שוב</b>.<br><br>לחץ על <b>שמירה</b> כדי לשמור את הרשימה הנגללת.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'לחץ על <b>שמירה ופרסום</b> כדי לשמור שינויים שביצעת ולהפוך אותם לפעילים בתוך המודול.',
        'historyBtn'=> 'לחץ על <b>הצג היסטוריה</b> כדי להציג ולשחזר תצורה שנשמרה קודם לכן מההיסטוריה.',
        'historyRestoreDefaultLayout'=> 'לחץ על <b>שחזר תצורה ברירת מחדל</b> כדי לשחזר תצוגה לתצורה המקורית שלה.',
        'Hidden' 	=> 'שדות <b>מוסתרים</b> לא מופיעים בפאנל-משנה.',
        'Default'	=> 'שדות <b>ברירת מחדל</b> מופיעים בפאנל-משנה.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'לחץ על <b>שמירה ופרסום</b> כדי לשמור שינויים שביצעת ולהפוך אותם לפעילים בתוך המודול.',
        'historyBtn'=> 'Click <b>View History</b> to view and restore a previously saved layout from the history.',
        'historyRestoreDefaultLayout'=> 'לחץ על <b>שחזר תצורה ברירת מחדל</b> כדי לשחזר תצוגה לתצורה המקורית שלה.<br><br><b>שחזר תצורה ברירת מחדל</b> משחזר רק את מיקום השדות בתוך התצורה המקורית. כדי לשנות את התוויות של השדות, לחץ על סמל העריכה שמופיע ליד כל שדה.',
        'Hidden' 	=> 'שדות <b>מוסתרים</b> לא זמינים כרגע לצפייה של משתמשים בתצוגות רשימה.',
        'Available' => 'שדות <b>זמינים</b> לא מוצגים לפי ברירת מחדל, אך משתמשים יכולים להוסיף אותם לתצוגות רשימה.',
        'Default'	=> 'שדות <b>ברירת מחדל</b> שמופיעים בתצוגות רשימה אינם מותאמים אישית על ידי משתמשים.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'לחץ על <b>שמירה ופרסום</b> כדי לשמור שינויים שביצעת ולהפוך אותם לפעילים בתוך המודול.',
        'historyBtn'=> 'Click <b>View History</b> to view and restore a previously saved layout from the history.',
        'historyRestoreDefaultLayout'=> 'לחץ על <b>שחזר תצורה ברירת מחדל</b> כדי לשחזר תצוגה לתצורה המקורית שלה.<br><br><b>שחזר תצורה ברירת מחדל</b> משחזר רק את מיקום השדות בתוך התצורה המקורית. כדי לשנות את התוויות של השדות, לחץ על סמל העריכה שמופיע ליד כל שדה.',
        'Hidden' 	=> 'שדות <b>מוסתרים</b> לא זמינים כרגע לצפייה של משתמשים בתצוגות רשימה.',
        'Default'	=> 'שדות <b>ברירת מחדל</b> שמופיעים בתצוגות רשימה אינם מותאמים אישית על ידי משתמשים.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'לחיצה על <b>שמירה ופרישה</b> תשמור את כל השינויים ותהפוך אותם לפעילים',
        'Hidden' 	=> 'שדות <b>מוסתרים</b> לא מופיעים בחיפוש.',
        'historyBtn'=> 'Click <b>View History</b> to view and restore a previously saved layout from the history.',
        'historyRestoreDefaultLayout'=> 'לחץ על <b>שחזר תצורה ברירת מחדל</b> כדי לשחזר תצוגה לתצורה המקורית שלה.<br><br><b>שחזר תצורה ברירת מחדל</b> משחזר רק את מיקום השדות בתוך התצורה המקורית. כדי לשנות את התוויות של השדות, לחץ על סמל העריכה שמופיע ליד כל שדה.',
        'Default'	=> 'שדות <b>ברירת מחדל</b> מופיעים בחיפוש.'
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
        'saveBtn'	=> 'לחץ על <b>שמירה</b> כדי לשמור את השינויים שערכת בתצורה מאז הפעם האחרונה ששמרת אותה.<br><br>השינויים לא יופיעו במודול עד שתפרוש את השינויים שנשמרו.',
        'historyBtn'=> 'Click <b>View History</b> to view and restore a previously saved layout from the history.',
        'historyRestoreDefaultLayout'=> 'לחץ על <b>שחזר תצורה ברירת מחדל</b> כדי לשחזר תצוגה לתצורה המקורית שלה.<br><br><b>שחזר תצורה ברירת מחדל</b> משחזר רק את מיקום השדות בתוך התצורה המקורית. כדי לשנות את התוויות של השדות, לחץ על סמל העריכה שמופיע ליד כל שדה.',
        'publishBtn'=> 'לחץ על <b>שמירה ופרישה</b> כדי לשמור את כל השינויים שערכת בתצורה מאז הפעם האחרונה ששמרת אותה, וכדי להפוך את השינויים לפעילים במודול.<br><br>התצורה תופיע באופן מיידי במודול.',
        'toolbox'	=> '<b>תיבת הכלים</b> מכילה את <b>סל המחזור</b>, אלמנטים נוספים של תצורה ואת סט השדות הזמינים להוספה לתצורה.<br/><br/>ניתן לגרור את האלמנטים והשדות של התצורה בתיבת הכלים ולשחרר אותם בתוך התצורה, וניתן לגרור את האלמנטים והשדות של התצורה ולשחרר אותם מתוך התצורה לתוך תיבת הכלים.<br><br>האלמנטים של התצורה הם <b>פאנלים</b>ו<b>שורות</b>. הוספת שורה חדשה או פאנל חדש לתצורה תעלה מקומות נוספים בתצורה עבור שדות.<br/><br/>גרור ושחרר כל אחד מהשדות בתיבת הכלים או בתצורה לתוך מיקום שדה תפוס כדי להחליף את המיקומים של שני השדות.<br/><br/> השדה <b>ממלא</b> יוצר חלל ריק בתצורה שבה הוא ממוקם.',
        'panels'	=> 'האזור <b>תצורה</b> נותן תצוגה של הופעת התצורה בתוך המודול לאחר פרישת השינויים שנערכו בתצורה.<br/><br/>באפשרותך לשנות את המיקום של שדות, שורות ופאנלים על ידי גרירה ושחרור במקום הרצוי.<br/><br/>הסר אלמנטים על ידי גרירה ושחרור בתוך <b>סל המחזור</b> בתיבת הכלים, או הוסף אלמנטים ושדות חדשים על ידי גרירתם מ<b>תיבת הכלים</b> ושחרורם במקום הרצוי בתצורה.',
        'delete'	=> 'גרור ושחרר כל אלמנט כאן כדי להסיר אותו מהתצורה',
        'property'	=> 'Edit The label displayed for this field. <br/><b>Tab Order</b> controls in what order the tab key switches between fields.',
    ),
    'fieldsEditor'=>array(
        'default'	=> '<b>השדות</b> שזמינים למודול רשומים כאן לפי שם שדה.<br><br>שדות בהתאמה אישית שנוצרו למודול מופיעים מעל השדות שזמינים למודול כברירת מחדל.<br><br>כדי לערוך שדה, לחץ על <b>שם שדה</b>.<br/><br/>כדי ליצור שדה חדש, לחץ על <b>הוספת שדה</b>.',
        'mbDefault'=>'<b>השדות</b> שזמינים למודול רשומים כאן לפי שם שדה.<br><br>כדי להגדיר את הערכים של שדה, לחץ על שם השדה.<br><br>כדי ליצור שדה חדש, לחץ על <b>הוספת שדה</b>. בנוסף לערכים האחרים של השדה החדש לאחר יצירתו ניתן לערוך גם את התווית בלחיצה על שם השדה. <br><br>לאחר פרישת המודול, השדות החדשים שנוצרו בבונה המודולים יתקבלו כשדות רגילים במודול הפרוש ב-Studio.',
        'addField'	=> 'בחר <b>סוג נתונים</b> עבור השדה החדש. הסוג שאתה בוחר קובע איזה סוג תווים יוזנו בשדה. לדוגמה, רק מספרים שהם שלמים וחיוביים יכולים להיות מוזנים בתוך שדות שסוג הנתונים שלהם הוא integer.<br><br> הזן <b>שם</b> לשדה. השם חייב להכיל אותיות וספרות ולא רווחים. קו תחתון הוא תו חוקי.<br><br> <b>תווית הצוגה</b> היא התווית שתופיע עבור השדות בתצורות המודול. <b>תווית המערכת</b> נועדה להתייחס לשדה בקוד.<br><br>תלוי בסוג הנתונים שנבחר עבור השדה, ניתן להגדיר חלק מהערכים הבאים או את כולם עבור השדה: <br><br> <b>טקסט עזרה</b> מופיע באופן זמני כאשר משתמש מרחף מעל השדה ויכול לשמש כדי לבקש מהמשתמש את סוג הקלט הרצוי.<br><br> <b>טקסט תגובה</b> מופיע רק בתוך Studio ו/או בונה המודולים, ויכול לשמש כדי לתאר את השדה למנהלי מערכת.<br><br> <b>ערך ברירת מחדל</b> יופיע בשדה.
משתמשים יכולים להזין ערך חדש בשדה או להשתמש בערך ברירת מחדל.<br><br> סמן את תיבת הסימון של <b>עדכון המוני</b> על מנת שתוכל להשתמש בתכונה עדכון המוני עבור השדה.<br><br> ערך <b>הגודל המרבי</b> קובע את מספר התווים המרבי שניתן להזין בשדה. <br><br> סמן את תיבת הסימון של <b>שדה נדרש</b> על מנת להפוך את השדה לשדה חובה. יש להזין ערך עבור השדה כדי לשמור רישום שמכיל אותו.<br><br> סמן את תיבת הסימן של <b>ניתן לדיווח</b> על מנת לאפשר לשדה שימוש במסננים ועבור הצגת נתונים בדו"חות.<br><br> סמן את תיבת הסימון של <b>ביקורת</b> כדי שתוכל לעקוב אחר שינויים בשדה בתיעוד השינויים.<br><br>בחר אפשרות בשדה <b>ניתן לייבוא</b> כדי לאפשר, לדחות או לדרוש את הייבוא של השדה לתוך אשף הייבוא.<br><br>בחר אפשרות בשדה <b>שכפל מיזוג</b> כדי להפעיל או להשבית את התכונות מזג שכפולים ומצא שכפולים.<br><br>ניתן להגדיר ערכים נוספים עבור סוגי נתונים מסוימים.',
        'editField' => 'ניתן להתאים אישית את הערכים של שדה זה.<br><br> לחץ על <b>שכפל</b> כדי ליצור שדה חדש עם אותם ערכים.',
        'mbeditField' => 'ניתן להתאים אישית את <b>תווית הייצוג</b> של שדה תבנית. לא ניתן להתאים אישית את הערכים האחרים של השדה.<br><br> לחץ על <b>שכפל</b> כדי ליצור שדה חדש עם אותם ערכים.<br><br>כדי להסיר שדה תבנית כך שלא תופיע במודול, הסר את השדה מה<b>תצורות</b> המתאימות.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'התאמות אישית של ייצוא שנערכו ב-Studio על ידי יצירת חבילות שניתן להעלות אותן למופע אחר של Sugar באמצעות <b>טוען המודולים</b>.<br><br>תחילה, הזן <b>שם חבילה</b>. תוכל להזין גם פרטי <b>מחבר</b> ו<b>תיאור</b> עבור החבילה.<br><br>בחר במודול או במודולים שמכילים את ההתאמות האישיות שברצונך לייצא. רק מודולים שמכילים התאמות אישיות יופיעו לבחירתך.<br><br>לאחר מכן לחץ על <b>ייצוא</b> כדי ליצור קובץ .zip עבור החבילה שמכיל את ההתאמות האישיות.',
        'exportCustomBtn'=>'לחץ על <b>ייצוא</b> כדי ליצור קובץ .zip עבור החבילה שמכילה את ההתאמות האישיות שברצונך לייצא.',
        'name'=>'זה ה<b>שם</b> של החבילה. שם זה יופיע במהלך ההתקנה.',
        'author'=>'זה ה<b>מחבר</b> שמופיע במהלך ההתקנה בתור השם של הישות שיצרה את החבילה. המחבר יכול להיות אדם או חברה.',
        'description'=>'זה <b>התיאור</b> של החבילה שמופיע במהלך ההתקנה.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'ברוך הבא לאזור <b>כלי מפתחים</b>.<br/><br/>השתמש בכלים בתוך אזור זה כדי ליצור ולנהל מודולים ושדות רגילים ומותאמים אישית.',
        'studioBtn'	=> 'השתמש ב<b>Studio</b> כדי להתאים אישית מודולים פרושים.',
        'mbBtn'		=> 'השתמש ב<b>בונה המודולים</b> כדי ליצור מודולים חדשים.',
        'sugarPortalBtn' => 'השתמש ב<b>עורך שער Sugar</b> כדי לנהל ולהתאים אישית את השער של Sugar.',
        'dropDownEditorBtn' => 'השתמש ב<b>עורך גלילה</b> כדי להוסיף ולערוך גלילות גלובליות עבור שדות גלילה.',
        'appBtn' 	=> 'מצב יישום הוא מצב שבו תוכל להתאים אישית ערכים שונים של התוכנה, כמו כמה דו"חות TPS מופיעים בדף הבית',
        'backBtn'	=> 'חזרה לשלב הקודם.',
        'studioHelp'=> 'השתמש ב-<b>Studio</b> כדי לקבוע איזה מידע יופיע במודולים ואת צורת הופעתו.',
        'studioBCHelp' => 'indicates the module is a backward compatible module',
        'moduleBtn'	=> 'לחץ כדי לערוך מודול זה.',
        'moduleHelp'=> 'הרכיבים שבאפשרותך להתאים אישית עבור המודול מופיעים כאן.<br><br>לחץ על סמל כדי לבחור את הרכיב לעריכה.',
        'fieldsBtn'	=> 'צור והתאם <b>שדות</b> באופן אישי כדי לאחסן מידע במודול.',
        'labelsBtn' => 'ערוך את <b>התוויות</b> שמופיעות עבור השדות וכותרות אחרות במודול.'	,
        'relationshipsBtn' => 'הוסף <b>מערכות יחסים</b> קיימות למודול.' ,
        'layoutsBtn'=> 'התאם באופן אישי את <b>התצורות</b> של המודול. התצורות הן התצוגות השונות של המודול שמכילות שדות.<br><br>באפשרותך לקבוע אילו שדות יופיעו ואת הסדר שלהם בכל תצורה.',
        'subpanelBtn'=> 'קבע אילו שדות מופיעים ב<b>פאנלי-משנה</b> במודול.',
        'portalBtn' =>'התאם באופן אישי את <b>התצורות</b> של המודול שמופיעות ב<b>שער Sugar</b>.',
        'layoutsHelp'=> 'המודול <b>תצורות</b> שניתן להתאימו אישית מופיע כאן.<br><br>התצורות מציגות שדות ונתוני שדות.<br><br>לחץ על סמל כדי לבחור תצורה לעריכה.',
        'subpanelHelp'=> '<b>פאנלי המשנה</b> במודול שניתן להתאימם אישית מופיעים כאן.<br><br>לחץ על סמל כדי לבחור מודול לעריכה.',
        'newPackage'=>'לחץ על <b>חבילה חדשה</b> כדי ליצור חבילה חדשה.',
        'exportBtn' => 'לחץ על <b>ייצוא התאמות אישיות</b> כדי ליצור ולהוריד חבילה שמכילה התאמות אישיות שנערכו ב-Studio עבור מודולים ספציפיים.',
        'mbHelp'    => 'השתמש ב<b>בונה המודולים</b> כדי ליצור חבילות שמכילות מודולים מותאמים אישית לפי אובייקטים רגילים או מותאמים אישית.',
        'viewBtnEditView' => 'Customize the module&#39;s <b>EditView</b> layout.<br><br>The EditView is the form containing input fields for capturing user-entered data.',
        'viewBtnDetailView' => 'Customize the module&#39;s <b>DetailView</b> layout.<br><br>The DetailView displays user-entered field data.',
        'viewBtnDashlet' => 'Customize the module&#39;s <b>Sugar Dashlet</b>, including the Sugar Dashlet&#39;s ListView and Search.<br><br>The Sugar Dashlet will be available to add to the pages in the Home module.',
        'viewBtnListView' => 'Customize the module&#39;s <b>ListView</b> layout.<br><br>The Search results appear in the ListView.',
        'searchBtn' => 'Customize the module&#39;s <b>Search</b> layouts.<br><br>Determine what fields can be used to filter records that appear in the ListView.',
        'viewBtnQuickCreate' =>  'Customize the module&#39;s <b>QuickCreate</b> layout.<br><br>The QuickCreate form appears in subpanels and in the Emails module.',

        'searchHelp'=> 'טפסי ה<b>חיפוש</b> שניתן להתאימם אישית מופיעים כאן.<br><br>טפסי חיפוש מכילים שדות לסינון רישומים.<br><br>לחץ על סמל כדי לבחור תצורת חיפוש לעריכה.',
        'dashletHelp' =>'התצורות של <b>חלונית Sugar</b> שניתן להתאימן אישית מופיעות כאן.<br><br> חלונית Sugar תהיה זמינה להוספה לדפים במודול &#39;בית&#39;.',
        'DashletListViewBtn' =>'<b>תצוגת רשימה של חלונית Sugar</b> מציגה רישומים לפי מסנני החיפוש של חלונית Sugar.',
        'DashletSearchViewBtn' =>'<b>חיפוש חלונית Sugar</b> מסנן רישומים עבור תצוגת רשימה של חלונית Sugar.',
        'popupHelp' =>'התצורות <b>הקופוצות</b> שניתן להתאימן אישית מופיעות כאן.<br>',
        'PopupListViewBtn' => 'The <b>Popup ListView</b> displays records based on the Popup search views.',
        'PopupSearchViewBtn' => 'The <b>Popup Search</b> views records for the Popup listview.',
        'BasicSearchBtn' => 'התאם אישית את טופס <b>החיפוש הבסיסי</b> שמופיע בלשונית החיפוש הבסיסי באזור החיפוש עבור המודול.',
        'AdvancedSearchBtn' => 'התאם אישית את טופס <b>החיפוש המתקדם</b> שמופיע בלשונית החיפוש המתקדם באזור החיפוש עבור המודול.',
        'portalHelp' => 'נהל והתאם אישית את <b>שער Sugar</b>.',
        'SPUploadCSS' => 'העלה <b>גיליון עיצוב</b> עבור השער של Sugar.',
        'SPSync' => '<b>סנכרון</b> התאמות אישיות למופע שער Sugar.',
        'Layouts' => 'התאם אישית את <b>התצורות</b> של המודולים של שער Sugar.',
        'portalLayoutHelp' => 'המודולים בתוך שער Sugar מופיעים באזור זה.<br><br>בחר מודול כדי לערוך את <b>התצורות</b>.',
        'relationshipsHelp' => 'כל <b>מערכות היחסים</b> שקיימות בין המודול לבין המודולים האחרים שנפרשו מופיעות כאן.<br><br>מערכת היחסים <b>שם</b> היא השם שיצרה המערכת עבור מערכת היחסים.<br><br> <b>מודול עיקרי</b> הוא המודול שמערכות היחסים בבעלותו. לדוגמה, כל הערכים של מערכות היחסים שעבורן מודול החשבונות הוא המודול העיקרי מאוחסנים בטבלאות מסד הנתונים של חשבונות.<br><br> <b>סוג</b> הוא הסוג של מערכת היחסים שקיימת בין המודול העיקרי לבין <b>המודול הקשור</b>.<br><br>לחץ על כותרת עמודה כדי למיין לפי עמודה.<br><br>לחץ על שורה בטבלת מערכת היחסים כדי לצפות בערכים שמשויכים למערכת היחסים.<br><br> לחץ על <b>הוספת מערכת יחסים</b> כדי ליצור מערכת יחסים חדשה.<br><br>ניתן ליצור מערכות יחסים חדשות בין כל שני מודולים פרושים.',
        'relationshipHelp'=>'<b>Relationships</b> can be created between the module and another deployed module.<br><br> Relationships are visually expressed through subpanels and relate fields in the module records.<br><br>Select one of the following relationship <b>Types</b> for the module:<br><br> <b>One-to-One</b> - Both modules&#39; records will contain relate fields.<br><br> <b>One-to-Many</b> - The Primary Module&#39;s record will contain a subpanel, and the Related Module&#39;s record will contain a relate field.<br><br> <b>Many-to-Many</b> - Both modules&#39; records will display subpanels.<br><br> Select the <b>Related Module</b> for the relationship. <br><br>If the relationship type involves subpanels, select the subpanel view for the appropriate modules.<br><br> Click <b>Save</b> to create the relationship.',
        'convertLeadHelp' => "Here you can add modules to the convert layout screen and modify the layouts of existing ones.<br/><br />		You can re-order the modules by dragging their rows in the table.<br/><br/><br />		<br />		<b>Module:</b> The name of the module.<br/><br/><br />		<b>Required:</b> Required modules must be created or selected before the lead can be converted.<br/><br/><br />		<b>Copy Data:</b> If checked, fields from the lead will be copied to fields with the same name in the newly created records.<br/><br/><br />		<b>Allow Selection:</b> Modules with a relate field in Contacts can be selected rather than created during the convert lead process.<br/><br/><br />		<b>Edit:</b> Modify the convert layout for this module.<br/><br/><br />		<b>Delete:</b> Remove this module from the convert layout.<br/><br/>",
        'editDropDownBtn' => 'ערוך גלילה גלובלית',
        'addDropDownBtn' => 'הוסף גלילה גלובלית חדשה',
    ),
    'fieldsHelp'=>array(
        'default'=>'ה<b>שדות</b> במודול רשומים כאן לפי שם שדה.<br><br>תבנית המודול כוללת סט קבוע מראש של שדות.<br><br>כדי ליצור שדה חדש, לחץ על <b>הוספת שדה</b>.<br><br>כדי לערוך שדה, לחץ על <b>שם שדה.</b>.<br/><br/>לאחר שהמודול נפרש, השדות הודשים שנוצרים בבונה המודולים, יחד עם שדות התבנית, מתקבלים כשדות רגילים ב-Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'<b>מערכות יחסים</b> שנוצרו בין המודול לבין מודולים אחרים מופיעות כאן.<br><br>ה<b>שם</b> של מערכת היחסים הוא שם שיצרה המערכת עבור מערכת היחסים.<br><br><b>מודול עיקרי</b> הוא המודול שמערכות היחסים בבעלותו. הערכים של מערכת היחסים מאוחסנים בטבלאות מסד הנתונים שמשתייכות למודול העיקרי.<br><br> <b>סוג</b> הוא הסוג של מערכת היחסים שקיימת בין המודול העיקרי לבין <b>המודול הקשור</b>.<br><br>לחץ על כותרת עמודה כדי למיין לפי העמודה.<br><br>לחץ על שורה בטבלת מערכת היחסים כדי להציג ולערוך את הערכים שמשויכים למערכת היחסים.<br><br>לחץ על <b>הוסף מערכת יחסים</b> כדי ליצור מערכת יחסים חדשה.',
        'addrelbtn'=>'עבר מעל עזרה כדי להוסיף מערכת יחסים..',
        'addRelationship'=>'<b>Relationships</b> can be created between the module and another custom module or a deployed module.<br><br> Relationships are visually expressed through subpanels and relate fields in the module records.<br><br>Select one of the following relationship <b>Types</b> for the module:<br><br> <b>One-to-One</b> - Both modules&#39; records will contain relate fields.<br><br> <b>One-to-Many</b> - The Primary Module&#39;s record will contain a subpanel, and the Related Module&#39;s record will contain a relate field.<br><br> <b>Many-to-Many</b> - Both modules&#39; records will display subpanels.<br><br> Select the <b>Related Module</b> for the relationship. <br><br>If the relationship type involves subpanels, select the subpanel view for the appropriate modules.<br><br> Click <b>Save</b> to create the relationship.',
    ),
    'labelsHelp'=>array(
        'default'=> 'ניתן לשנות את ה<b>תוויות</b> עבור השדות ועבור כותרות אחרות במודול.<br><br>ערוך את התווית בלחיצה בתוך השדה, הזנת תווית חדשה ולחיצה על <b>שמירה</b>.<br><br>במידה ומותקנות חבילות שפה ביישום שלך, תוכל לבחור את <b>השפה</b> לשימוש בתוויות.',
        'saveBtn'=>'לחץ על <b>שמירה</b> כדי לשמור את כל השינויים.',
        'publishBtn'=>'לחץ על <b>שמירה ופריסה</b> כדי לשמור את כל השינויים ולהפוך אותם לפעילים.',
    ),
    'portalSync'=>array(
        'default' => 'הזן את <b>כתובת ה-URL לשער Sugar</b> של מופע השער כדי לעדכן, ולחץ על <b>עבור</b>.<br><br>לאחר מכן הזן שם משתמש וסיסמה תקינים של Sugar ולחץ על <b>התחל סנכרון</b>.<br><br>ההתאמות האישיות שנערכו ל<b>תצורות</b> של שער Sugar, ביחד עם <b>גיליון העיצוב</b> במידה והועלה אחד, יועברו למופע השער שצוין.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'באפשרותך להתאים אישית את המראה של שער Sugar באמצעות גיליון עיצוב.<br><br>בחר <b>גיליון עיצוב</b> להעלאה.<br><br>גיליון העיצוב יוטמע בשער Sugar בפעם הבא שיבוצע סנכרון.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'כדי להתחיל לעבוד בפרויקט, לחץ על <b>חבילה חדשה</b> כדי ליצור חבילה חדשה שבה תאחסן את המודול או המודולים שהתאמת אישית. <br/><br/>כל חבילה יכולה להכיל מודול אחד או יותר.<br/><br/>לדוגמה, ייתכן ותרצה ליצור חבילה שמכילה מודול מותאם אישית אחד שקשור למודול חשבונות הרגיל. או, אולי תרצה ליצור חבילה שמכילה מספר מודולים חדשים שעובדים ביחד כפרויקט ואשר קשורים זה לזה ולמודולים אחרים שכבר ביישום.',
            'somepackages'=>'<b>חבילה</b> פועלת כמו מכולה עבור מודולים מותאמים אישית, אשר כולם חלק מאותו פרויקט. החבילה יכולה להכיל <b>מודולים</b> מותאמים אישית שניתן לקשר אותם זה לזה או למודולים אחרים ביישום.<br/><br/>לאחר יצירת חבילה עבור הפרויקט שלך, תוכל ליצור מודולים עבור החבילה באופן מיידי, או שתוכל לחזור לבונה המודולים בשלב מאוחר יותר כדי לסיים את הפרויקט.<br><br>כאשר הפרויקט הסתיים, תוכל <b>לפרוש</b> את החבילה כדי להתקין את המודולים המותאמים אישית בתוך היישום.',
            'afterSave'=>'Your new package should contain at least one module. You can create one or more custom modules for the package.<br/><br/>Click <b>New Module</b> to create a custom module for this package.<br/><br/> After creating at least one module, you can publish or deploy the package to make it available for your instance and/or other users&#39; instances.<br/><br/> To deploy the package in one step within your Sugar instance, click <b>Deploy</b>.<br><br>Click <b>Publish</b> to save the package as a .zip file. After the .zip file is saved to your system, use the <b>Module Loader</b> to upload and install the package within your Sugar instance.  <br/><br/>You can distribute the file to other users to upload and install within their own Sugar instances.',
            'create'=>'<b>חבילה</b> פועלת כמו מכולה עבור מודולים מותאמים אישית, אשר כולם חלק מאותו פרויקט. החבילה יכולה להכיל <b>מודולים</b> מותאמים אישית שניתן לקשר אותם זה לזה או למודולים אחרים ביישום.<br/><br/>לאחר יצירת חבילה עבור הפרויקט שלך, תוכל ליצור מודולים עבור החבילה באופן מיידי, או שתוכל לחזור לבונה המודולים בשלב מאוחר יותר כדי לסיים את הפרויקט.',
            ),
    'main'=>array(
        'welcome'=>'השתמש ב<b>כלי מפתחים</b> כדי ליצור ולנהל מודולים ושדות רגילים ומותאמים אישית. <br/><br/>כדי לנהל מודולים ביישום, לחץ על <b>Studio</b>.<br/><br/>כדי ליצור מודולים מותאמים אישית, לחץ על <b>בונה המודולים</b>.',
        'studioWelcome'=>'כל המודולים שמותקנים כרגע, כולל אובייקטים רגילים ובטעינת-מודול, ניתנים להתאמה אישית בתוך Studio.'
    ),
    'module'=>array(
        'somemodules'=>"מכיוון שהחבילה הנוכחית שלך מכילה לפחות מודול אחד, באפשרותך <b>לפרוש</b> את המודולים בחבילה בתוך המופע Sugar שלך או <b>לפרסם</b> את החבילה כדי שתותקן במופע Sugar הנוכחי או במופע אחר באמצעות <b>טוען המודולים</b>.<br/><br/>כדי להתקין את החבילה ישירות מתוך המופע Sugar שלך, לחץ על <b>פרישה</b>.<br><br>כדי ליצור קובץ .zip עבור החבילה שיהיה ניתן לטעון ולהתקין אותו בתוך המופע הנוכחי של Sugar ובמופעים אחרים באמצעות </b>טוען המודולים</b>, לחץ על<b>פרסם</b>.<br/><br/>באפשרות לבנות את המודולים עבור חבילה זו בשלבים, ולפרסם או לפרש כאשר אתה מוכן לעשות זאת. <br/><br/>לאחר פרסום או פרישת חבילה, תוכל לבצע שינויים לערכי החבילה ולהתאים אישית ערכים נוספים של המודולים. לאחר מכן, פרסם מחדש או פרוש מחדש את החבילה כדי להחיל את השינויים." ,
        'editView'=> 'כאן באפשרות לערוך את השדות הקיימים. תוכל להסיר כל אחד מהשדות הקיימים או להוסיף שדות זמינים בפאנל השמאלי.',
        'create'=>'בעת בחירת הסוג של <b>סוג</b> המודול שברצונך ליצור, זכור את סוגי השדות שברצונך שיופיעו בתוך המודול. <br/><br/>כל תבנית מודול מכילה סט שדות ששייכים לסוג המודול שתואר על ידי הכותרת.<br/><br/><b>בסיסי</b> - נותן שדות בסיס שמופיעים במודולים רגילים, כמו שם, הוקצה אל, צוות, תאריך יצירה ותיאור.<br/><br/> <b>חברה</b> - נותן שדות ספציפיים לארגון, כמו שם חברה, תעשייה וכתובת לחיוב. השתמש בתבנית זו כדי ליצור מודולים שדומים למודול הרגיל חשבונות. <br/><br/> <b>אדם</b> - נותן שדות ספציפיים לאנשים, כמו ברכות, תואר, שם, כתובת ומספר טלפון. השתמש בתבנית זו כדי ליצור מודולים שדומים למודולים הרגילים אנשי קשר ולידים.<br/><br/><b>בעיה</b> - נותן שדות ספציפיים למקרה ולבאגים, כמו מספר, מצב, עדיפות ותיאור. השתמש בתבנית זו כדי ליצור מודולים שדומים למודולים הרגילים מקרים ומעקב באגים. <br/><br/>הערה: לאחר שיצרת את המודול, תוכל לערוך את התוויות של השדות שניתנו על ידי התבנית, וכמו כן ליצור שדות מותאמים אישית כדי להוסיפם לתצורות המודול.',
        'afterSave'=>'התאם אישית את המודול כך שיתאים לצרכים שלך על ידי עריכת ויצירת שדות, הקמת מערכות יחסים עם מודולים אחרים וסידור השדות בתוך התצורות. <br/><br/>כדי להציג את השדות של התבנית ולנהל שדות מותאמים אישית בתוך המודול, לחץ על <b>הצג שדות</b>.<br/><br/>כדי ליצור ולנהל מערכות יחסים בין המודול לבין מודולים אחרים, בין אם הם מודולים שכבר ביישום או מודלים אחרים ומותאמים אישית בתוך אותה חבילה, לחץ על <b>הצג מערכות יחסים</b>.<br/><br/>כדי לערוך את תצורות המודול, לחץ על <b>הצג תצורות</b>. באפשרותך לשנות את התצורות של תצוגה מפורטת, תצוגת עריכה ותצוגת רשימה עבור המודול כמו שהיית משנה עבור מודולים שכבר ביישום בתוך Studio.<br/><br/> כדי ליצור מודול עם אותם ערכים כמו המודול הנוכחי, לחץ על <b>שכפל</b>. תוכל לערוך התאמות אישיות נוספות למודול החדש.',
        'viewfields'=>'ניתן להתאים אישית את השדות במודול כך שיתאימו לצרכים שלך.<br/><br/>אין באפשרותך למחוק שדות רגילים, אך תוכל להסיר אותם מהתצורות המתאימות בתוך דפי התצורה. <br/><br/>באפשרותך ליצור במהרה שדות חדשים בעלי ערכים דומים לשדות קיימים על ידי לחיצה על <b>שכפול</b> בטופס <b>ערכים</b>. הזן ערכים חדשים ולאחר מכן לחץ על <b>שמירה</b>. <br/><br/>מומלץ שתגדיר את כל הערכים של השדות הרגילים והשדות בהתאמה אישית לפני שתפרסם ותתקין את החבילה שמכילה את המודול בהתאמה אישית.',
        'viewrelationships'=>'באפשרותך ליצור מערכות יחסים של רבים-אל-רבים בין המודול הנוכחי לבין מודולים אחרים בחבילה, ו/או בין המודול הנוכחי לבין מודולים שכבר מותקנים ביישום.<br><br>כדי ליצור מערכות יחסים של אחד-אל-רבים ואחד-אל-אחד, צור שדות <b>קשר</b> ו<b>קשר גמיש</b> עבור המודולים.',
        'viewlayouts'=>'באפשרותך לקבוע אילו שדות זמינים לקליטת נתונים בתוך <b>תצוגת עריכה</b>. באפשרותך גם לקבוע אילו נתונים מופיעים בתוך <b>תצוגה מפורטת</b>. התצוגות לא חייבות להתאים זו לזו. <br/><br/>הטופס יצירה מהיר מופיע כאשר לוחצים על <b>יצירה</b> בפאנל-משנה של מודול. כברירת מחדל, התצורה של הטופס <b>יצירה מהירה</b> דומה לתצורת ברירת מחדל של <b>תצוגת עריכה</b>. באפשרותך להתאים אישית את הטופס יצירה מהירה כך שיכיל פחות שדות ו/או שדות שונים מהתצורה של תצוגת עריכה.<br><br>באפשרותך להגדיר את אבטחת המודול באמצעות התאמה אישית של התצורה יחד עם <b>ניהול תפקידים</b>.<br><br>',
        'existingModule' =>'לאחר שיצרת והתאמת אישית את המודול הזה, תוכל ליצור מודולים נוספים או לחזור לחבילה כדי <b>לפרסם</b> או <b>לפרוש</b> את החבילה.<br><br>כדי ליצור מודולים נוספים, לחץ על <b>שכפל</b> כדי ליצור מודול עם אותם ערכים כמו המודול הנוכחי, או נווט חזרה אל החבילה, ולחץ על <b>מודול חדש</b>.<br><br> אם אתה מוכן <b>לפרסם</b> או <b>לפרוש</b> את החבילה שמכילה מודול זה, נווט בחזרה אל החבילה כדי לבצע פעולות אלה. באפשרותך לפרסם ולפרוש חבילות שמכילות לפחות מודול אחד.',
        'labels'=> 'ניתן לשנות את התוויות של השדות הרגילים וכמו כן את התוויות של השדות בהתאמה אישית. שינוי תוויות שדה לא ישפיע על הנתונים שמאוחסנים בשדות.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'שלוש עמודות מופיעות בצד שמאל. העמודה "ברירת מחדל" מכילה את השדות שמופיעים בתצוגת רשימה כברירת מחדל, העמודה "זמינים" מכילה את השדות שמשתמש יכול לבחר להשתמש בהם ליצירת תצוגת רשימה מותאמת אישית, והעמודה "מוסתרים" מכילה שדות שזמינים לך כמנהל מערכת להוספה לעמודות ברירת מחדל או זמינים לשימוש המשתמשים, אשר כרגע מושבתים.',
        'savebtn'	=> 'לחיצה על <b>שמירה</b> תשמור את כל השינויים ותהפוך אותם לפעילים.',
        'Hidden' 	=> 'שדות מוסתרים הם שדות שכרגע לא זמינים לשימוש המשתמשים בתצוגות רשימה.',
        'Available' => 'שדות זמינים הם שדות שלא מוצגים כברירת מחדל, אך משתמשים יכולים להפעיל אותם.',
        'Default'	=> 'שדות ברירת מחדל מוצגים למשתמשים שלא יצרו הגדרות מותאמות אישית לתצוגת רשימה.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'מופיעות שתי עמודות בצד שמאל. העמודה "ברירת מחדל" מכילה את השדות שיופיעו בתצוגת החיפוש, והעמודה "מוסתרים" מכילה את השדות שזמינים לך כמנהל מערכת להוספה לתצוגה.',
        'savebtn'	=> 'לחיצה על <b>שמירה ופרישה</b> תשמור את כל השינויים ותהפוך אותם לפעילים.',
        'Hidden' 	=> 'שדות מוסתרים הם שדות שלא יופיעו בתצוגת החיפוש.',
        'Default'	=> 'שדות ברירת מחדל יופיעו בתצוגת החיפוש.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'מופיעות שתי עמודות בצד שמאל. העמודה הימנית, ששמה תצורה נוכחית או תצוגה מקדימה של תצורה, היא העמודה שבה אתה משנה את תצורת המודול. העמודה השמאלית, ששמה תיבת כלים, מכילה אלמנטים וכלים מועילים לשימוש בעת עריכת התצורה. <br/><br/>אם אזור התצורה נקרא תצורה נוכחית אז אתה עובד על עותק של התצורה שכרגע משמש את המודול לתצוגה.<br/><br/>אם הוא נקרא תצוגה מקדימה של תצורה אז אתה עובד על עותק שנוצר קודם לכן בלחיצה על כפתור השמירה, שייתכן והשתנה כבר מהגרסה שנראתה לאחרונה על ידי המשתמשים של מודול זה.',
        'saveBtn'	=> 'לחיצה על כפתור זה שומרת את התצורה כך שתוכל לשמר את השינויים שלך. כאשר אתה חוזר למודול זה, אתה תתחיל בתצורה זו שהשתנתה. עם זאת, משתמשים אחרים במודול לא יראו את התצורה שלך עד שתלחץ על הכפתור שמירה ופרסום.',
        'publishBtn'=> 'לחץ על כפתור זה כדי לפרוש את התצורה. זה יגרום לכך שתצורה זאת תהיה גלויה באופן מיידי למשתמשים במודול זה.',
        'toolbox'	=> 'תיבת הכלים מכילה מגוון תכונות שימושיות לעריכת תצורות, כולל אזור אשפה, סט של אלמנטים נוספים וסט של שדות זמינים. ניתן לגרור כל אחד מבין אלה ולשחרר אותו בתצורה.',
        'panels'	=> 'אזור זה מראה את תצוגת התצורה שלך למשתמשים במודול זה לאחר פרישתה.<br/><br/>באפשרותך לשנות את המיקום של אלמנטים כמו שדות, שורות ופאנלים על ידי גרירה ושחרור; מחק אלמנטים על ידי גרירתם ושחרורם באזור האשפה בתיבת הכלים, או הוסף אלמנטים חדשים בכך שתגרור אותם מתיבת הכלים ותשחרר אותם על התצורה במקום הרצוי.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'מופיעות שתי עמודות בצד שמאל. העמודה הימנית, ששמה תצורה נוכחית או תצוגה מקדימה של תצורה, היא העמודה שבה אתה משנה את תצורת המודול. העמודה השמאלית, ששמה תיבת כלים, מכילה אלמנטים וכלים מועילים לשימוש בעת עריכת התצורה. <br/><br/>אם אזור התצורה נקרא תצורה נוכחית אז אתה עובד על עותק של התצורה שכרגע משמש את המודול לתצוגה.<br/><br/>אם הוא נקרא תצוגה מקדימה של תצורה אז אתה עובד על עותק שנוצר קודם לכן בלחיצה על כפתור השמירה, שייתכן והשתנה כבר מהגרסה שנראתה לאחרונה על ידי המשתמשים של מודול זה.',
        'dropdownaddbtn'=> 'לחיצה על כפתור זה מוסיפה פריט חדש לתפריט הגלילה.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'ניתן לחבר כחבילה התאמות אישיות שנערכו ב-Studio בתוך מופע זה ולפרוש אותן במופע אחר. <br><br>הזן <b>שם חבילה</b>. באפשרותך להזין פרטי <b>מחבר</b> ו<b>תיאור</b> עבור החבילה.<br><br>בחר במודול או במודולים שמכילים את ההתאמות האישיות לייצוא. (רק מודולים שמכילים התאמות אישיות יופיעו לבחירתך.)<br><br>לחץ על <b>ייצוא</b> כדי ליצור קובץ ,zip עבור החבילה שמכילה את ההתאמות האישיות. ניתן להעלות את הקובץ .zip במופע אחר באמצעות </b>טוען המודולים</b>.',
        'exportCustomBtn'=>'Click <b>Export</b> to create a .zip file for the package containing the customizations that you wish to export.',
        'name'=>'<b>שם</b> החבילה יופיע בטוען המודולים לאחר שהחבילה הועלתה להתקנה ב-Studio.',
        'author'=>'The <b>Author</b> is the name of the entity that created the package. The Author can be either an individual or a company.<br><br>The Author will be displayed in Module Loader after the package is uploaded for installation in Studio.',
        'description'=>'<b>תיאור</b> החבילה יופיע בטוען המודולים לאחר שהחבילה הועלתה להתקנה ב-Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'ברוך הבא לאזור <b>כלי מפתחים</b1>.<br/><br/>השתמש בכלים בתוך אזור זה כדי ליצור ולנהל מודולים ושדות רגילים ומותאמים אישית.',
        'studioBtn'	=> 'השתמש ב<b>Studio</b> כדי להתאים אישית מודולים שהותקנו על ידי שינוי סידור השדות, בחירת השדות הזמינים ויצירת שדות נתונים מותאמים אישית.',
        'mbBtn'		=> 'השתמש ב<b>בונה המודולים</b> כדי ליצור מודולים חדשים.',
        'appBtn' 	=> 'השתמש במצב יישום כדי להתאים אישית ערכים שונים של התוכנה, כמו כמה דו"חות TPS מופיעים בדף הבית',
        'backBtn'	=> 'חזרה לשלב הקודם.',
        'studioHelp'=> 'השתמש ב<b>Studio</b> כדי להתאים אישית את המודולים המותקנים.',
        'moduleBtn'	=> 'לחץ כדי לערוך מודול זה.',
        'moduleHelp'=> 'בחר את רכיב המודול שברצונך לערוך',
        'fieldsBtn'	=> 'ערוך את המידע שמאוחסן במודול על ידי בקרת <b>שדות</b> במודול.<br/><br/>כאן תוכל לערוך וליצור שדות מותאמים אישית.',
        'layoutsBtn'=> 'התאם אישית את <b>התצורות</b> של התצוגות עריכה, פירוט ורשימה.',
        'subpanelBtn'=> 'ערוך את המידע שמופיע בפאנלים משנה אלה של המודול.',
        'layoutsHelp'=> 'בחר <b>תצורה לעריכה</b>.<br/<br/>כדי לשנות את התצורה שמכילה את שדות הנתונים להזנת נתונים, לחץ על <b>ערוך תצוגה</b>.<br/><br/>כדי לשנות את התצורה שמציגה את הנתונים שהוזנו בתוך השדות בעריכת תצוגה, לחץ על <b>תצוגה מפורטת</b>.<br/><br/>כדי לשנות את העמודות שמופיעות ברשימת ברירת מחדל, לחץ על <b>תצוגת רשימה</b>.<br/><br/>כדי לשנות את התצורות הבסיסיות והמתקדמות של טופס החיפוש, לחץ על <b>חיפוש</b>.',
        'subpanelHelp'=> 'בחר <b>פאנל-משנה</b> לעריכה.',
        'searchHelp' => 'בחר תצורת <b>חיפוש</b> לעריכה.',
        'labelsBtn'	=> 'ערוך את ה<b>תוויות</b> שיש להציג עבור ערכים במודול זה.',
        'newPackage'=>'לחץ על <b>חבילה חדשה</b> כדי ליצור חבילה חדשה.',
        'mbHelp'    => '<b> ברוך הבא לבונה המודולים. </b><br/><br/>השתמש ב<b>בונה המודולים</b> כדי ליצור חבילות שמכילות מודולים מותאמים אישית לפי אובייקטים רגילים או מותאמים אישית. <br/><br/>כדי להתחיל, לחץ על <b>חבילה חדשה</b> כדי ליצור חבילה חדשה, או בחר חבילה כדי לערוך אותה.<br/><br/> <b>חבילה</b> פועלת כמו מכולה עבור מודולים מותאמים אישית, אשר כולם חלק מאותו פרויקט. החבילה יכולה להכיל מודולים מותאמים אישית שניתן לקשר אותם זה לזה או למודולים אחרים ביישום. <br/><br/>דוגמאות: ייתכן ותרצה ליצור חבילה שמכילה מודול אחד מותאם אישית שקשור למודול חשבונות הרגיל. או, אולי תרצה ליצור חבילה שמכילה מספר מודולים חדשים שעובד ביחד כפרויקט ואשר קשורים זה לזה ולמודולים אחרים ביישום.',
        'exportBtn' => 'לחץ על <b>ייצוא התאמות אישיות</b> כדי ליצור חבילה שמכילה התאמות אישיות שנעשו ב-Studio עבור מודולים ספציפיים.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'עורך תפריט גלילה',

//ASSISTANT
'LBL_AS_SHOW' => 'הצג את הסייען בעתיד.',
'LBL_AS_IGNORE' => 'התעלם מהסייען בעתיד.',
'LBL_AS_SAYS' => 'הסייען אומר:',

//STUDIO2
'LBL_MODULEBUILDER'=>'בונה מודולים',
'LBL_STUDIO' => 'סטודיו',
'LBL_DROPDOWNEDITOR' => 'עורך תפריט גלילה',
'LBL_EDIT_DROPDOWN'=>'ערוך גלילה',
'LBL_DEVELOPER_TOOLS' => 'כלי מפתחים',
'LBL_SUGARPORTAL' => 'עורך שער Sugar',
'LBL_SYNCPORTAL' => 'סינכרון שער',
'LBL_PACKAGE_LIST' => 'רשימת חבילות',
'LBL_HOME' => 'בית',
'LBL_NONE'=>'-אין-',
'LBL_DEPLOYE_COMPLETE'=>'פריסה הסתיימה',
'LBL_DEPLOY_FAILED'   =>'An error has occured during deploy process, your package may not have installed correctly',
'LBL_ADD_FIELDS'=>'Add Custom Fields',
'LBL_AVAILABLE_SUBPANELS'=>'פאנלי-משנה זמינים',
'LBL_ADVANCED'=>'מתקדם',
'LBL_ADVANCED_SEARCH'=>'חיפוש מתקדם',
'LBL_BASIC'=>'בסיסי',
'LBL_BASIC_SEARCH'=>'חיפוש בסיסי',
'LBL_CURRENT_LAYOUT'=>'תצורה',
'LBL_CURRENCY' => 'Currency',
'LBL_CUSTOM' => 'מותאם',
'LBL_DASHLET'=>'חלונית Sugar',
'LBL_DASHLETLISTVIEW'=>'תצוגת רשימה של חלונית Sugar',
'LBL_DASHLETSEARCH'=>'חיפוש בחלונית Sugar',
'LBL_POPUP'=>'PopupView',
'LBL_POPUPLIST'=>'Popup ListView',
'LBL_POPUPLISTVIEW'=>'Popup ListView',
'LBL_POPUPSEARCH'=>'חיפוש Popup',
'LBL_DASHLETSEARCHVIEW'=>'חיפוש בחלונית Sugar',
'LBL_DISPLAY_HTML'=>'Display HTML Code',
'LBL_DETAILVIEW'=>'DetailView',
'LBL_DROP_HERE' => '[Drop Here]',
'LBL_EDIT'=>'ערוך',
'LBL_EDIT_LAYOUT'=>'Edit Layout',
'LBL_EDIT_ROWS'=>'Edit Rows',
'LBL_EDIT_COLUMNS'=>'Edit Columns',
'LBL_EDIT_LABELS'=>'Edit Labels',
'LBL_EDIT_PORTAL'=>'Edit Portal for',
'LBL_EDIT_FIELDS'=>'ערוך שדות',
'LBL_EDITVIEW'=>'EditView',
'LBL_FILTER_SEARCH' => "חיפוש",
'LBL_FILLER'=>'(מסנן)',
'LBL_FIELDS'=>'Fields',
'LBL_FAILED_TO_SAVE' => 'Failed To Save',
'LBL_FAILED_PUBLISHED' => 'Failed to Publish',
'LBL_HOMEPAGE_PREFIX' => 'שלי',
'LBL_LAYOUT_PREVIEW'=>'תצוגה מקדימה של תצורה',
'LBL_LAYOUTS'=>'תצורות',
'LBL_LISTVIEW'=>'ListView',
'LBL_RECORDVIEW'=>'צפה ברשומות',
'LBL_MODULE_TITLE' => 'סטודיו',
'LBL_NEW_PACKAGE' => 'חבילה חדשה',
'LBL_NEW_PANEL'=>'פאנל חדש',
'LBL_NEW_ROW'=>'שורה חדשה',
'LBL_PACKAGE_DELETED'=>'חבילה נמחקה',
'LBL_PUBLISHING' => 'Publishing ...',
'LBL_PUBLISHED' => 'Published',
'LBL_SELECT_FILE'=> 'Select File',
'LBL_SAVE_LAYOUT'=> 'Save Layout',
'LBL_SELECT_A_SUBPANEL' => 'Select a Subpanel',
'LBL_SELECT_SUBPANEL' => 'Select Subpanel',
'LBL_SUBPANELS' => 'פאנלי-משנה',
'LBL_SUBPANEL' => 'פאנל-משנה',
'LBL_SUBPANEL_TITLE' => 'כותרת',
'LBL_SEARCH_FORMS' => 'Search',
'LBL_STAGING_AREA' => 'Staging Area (drag and drop items here)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar Fields (click items to add to staging area)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (click items to add to staging area)',
'LBL_TOOLBOX' => 'Toolbox',
'LBL_VIEW_SUGAR_FIELDS' => 'View Sugar Fields',
'LBL_VIEW_SUGAR_BIN' => 'View Sugar Bin',
'LBL_QUICKCREATE' => 'QuickCreate',
'LBL_EDIT_DROPDOWNS' => 'ערוך גלילה גלובלית',
'LBL_ADD_DROPDOWN' => 'הוסף גלילה גלובלית חדשה',
'LBL_BLANK' => '-ריק-',
'LBL_TAB_ORDER' => 'סדר לשוניות',
'LBL_TAB_PANELS' => 'Display panels as tabs',
'LBL_TAB_PANELS_HELP' => 'Display each panel as its own tab instead of having them all appear on one screen',
'LBL_TABDEF_TYPE' => 'סוג תצוגה:',
'LBL_TABDEF_TYPE_HELP' => 'בחר כיצד אזור זה יוצג. אפשרות זו תכנס לתוקף רק במידה והפעלת לשוניות בתצוגה זאת.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'לשונית',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'פאנל',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'בחר &#39;פאנל&#39; כדי שפאנל זה יופיע בתצוגה של התצורה. בחר &#39;לשונית&#39; כדי שפאנל זה יופיע בתוך לשונית נפרדת בתוך התצורה. כאשר &#39;לשונית&#39; נבחרה עבור פאנל, פאנלים עוקבים שהוגדרו להופיע בתור &#39;פאנל&#39; יופיעו בתוך הלשונית. <br/>לשונית חדשה תתחיל עבור הפאנל הבא ש&#39;לשונית&#39; נבחרת בו. במידה ו&#39;לשונית&#39; נבחרת עבור פאנל מתחת לפאנל הראשון, הפאנל הראשון יהיה בהכרח &#39;לשונית&#39;.',
'LBL_TABDEF_COLLAPSE' => 'כיווץ',
'LBL_TABDEF_COLLAPSE_HELP' => 'בחר כדי להפוך את מצב ברירת המחדל של פאנל זה למכווץ.',
'LBL_DROPDOWN_TITLE_NAME' => 'שם',
'LBL_DROPDOWN_LANGUAGE' => 'שפה',
'LBL_DROPDOWN_ITEMS' => 'פריטי רשימה',
'LBL_DROPDOWN_ITEM_NAME' => 'שם פריט',
'LBL_DROPDOWN_ITEM_LABEL' => 'תווית תצוגה',
'LBL_SYNC_TO_DETAILVIEW' => 'סנכרון ל-DetailView',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Select this option to sync this EditView layout to the corresponding DetailView layout. Fields and field placement in the EditView<br>will be sync&#39;d and saved to the DetailView automatically upon clicking Save or Save & Deploy in the EditView. <br>Layout changes will not be able to be made in the DetailView.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'This DetailView is sync&#39;d with the corresponding EditView.<br> Fields and field placement in this DetailView reflect the fields and field placement in the EditView.<br> Changes to the DetailView cannot be saved or deployed within this page. Make changes or un-sync the layouts in the EditView.',
'LBL_COPY_FROM' => 'העתק מ',
'LBL_COPY_FROM_EDITVIEW' => 'העתק מ-EditView',
'LBL_DROPDOWN_BLANK_WARNING' => 'ערכים נדרשים עבור שם הפריט ותווית התצוגה. כדי להוסיף פריט ריק, לחץ על הוספה מבלי להזין ערכים עבור שם הפריט ותווית התצוגה.',
'LBL_DROPDOWN_KEY_EXISTS' => 'מפתח כבר קיים ברשימה',
'LBL_DROPDOWN_LIST_EMPTY' => 'הרשימה תריכה לכלול לפחות פריט אחד מאופשר',
'LBL_NO_SAVE_ACTION' => 'לא ניתן למצוא פעולת שמירה במסך זה',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: מסמך ערוך מצורה גרועה',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => 'לחץ Doubleclick על שדה קומבינציה כדי לראות אילו שדות הוא מכיל',
'LBL_COMBO_FIELD_CONTAINS' => 'מכיל',

'LBL_WIRELESSLAYOUTS'=>'תצורות סלולריות',
'LBL_WIRELESSEDITVIEW'=>'EditView סלולרית',
'LBL_WIRELESSDETAILVIEW'=>'DetailView סלולרית',
'LBL_WIRELESSLISTVIEW'=>'ListView סלולרית',
'LBL_WIRELESSSEARCH'=>'חיפוש סלולרי',

'LBL_BTN_ADD_DEPENDENCY'=>'הוסף תלוית',
'LBL_BTN_EDIT_FORMULA'=>'ערוך נוסחה',
'LBL_DEPENDENCY' => 'תלות',
'LBL_DEPENDANT' => 'תלוי',
'LBL_CALCULATED' => 'ערך מחושב',
'LBL_READ_ONLY' => 'קריאה בלבד',
'LBL_FORMULA_BUILDER' => 'בונה נוסחה',
'LBL_FORMULA_INVALID' => 'נוסחה לא חוקית',
'LBL_FORMULA_TYPE' => 'The formula must be of type',
'LBL_NO_FIELDS' => 'לא נמצאו שדות',
'LBL_NO_FUNCS' => 'לא נמצאו פונקציות',
'LBL_SEARCH_FUNCS' => 'מחפש פונקציות...',
'LBL_SEARCH_FIELDS' => 'מחפש שדות...',
'LBL_FORMULA' => 'נוסחה',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'תלוי',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'גרור אפשרויות מהרשימה שמשמאל לאפשרויות שזמינות בגלילה התלויה אל הרשימות שמימין כדי להפוך אפשרויות אלה לזמינות כאשר אפשרות האב נבחרת. אם אין פריטים תחת אפשרות האב, כאשר אפשרות האב נבחרת, הגלילה התלויה לא תופיע.',
'LBL_AVAILABLE_OPTIONS' => 'אפשרויות זמינות',
'LBL_PARENT_DROPDOWN' => 'גלילת אב',
'LBL_VISIBILITY_EDITOR' => 'עורך נראות',
'LBL_ROLLUP' => 'Rollup',
'LBL_RELATED_FIELD' => 'שדה קשור',
'LBL_CONFIG_PORTAL_URL'=>'URL לתמונת לוגו מותאמת. גודל לוגו מומלץ הוא 163 × 18 פיקסלים',
'LBL_PORTAL_ROLE_DESC' => 'אל תמחק תפקיד זה. תפקיד שער שירות-עצמי של הלקוח הוא תפקיד שיצרה המערכת במהלך תהליך ההפעלה של שער Sugar. השתמש בבקרי גישה בתוך תפקיד זה כדי להפעיל ו/או להשבית באגים, מקרים או מודולים של בסיס ידע בשער Sugar. אל תשנה בקרי גישה אחרים של תפקיד זה כדי להימנע מהתנהגות מערכת בלתי ידועה ובלתי צפויה. במקרה של מחיקה מקרית של תפקיד זה, צור אותו מחדש על ידי השבתה והפעלה מחדש של שער Sugar.',

//RELATIONSHIPS
'LBL_MODULE' => 'מודול',
'LBL_LHS_MODULE'=>'מודול עיקרי',
'LBL_CUSTOM_RELATIONSHIPS' => '* מערכת יחסים נוצרה ב-Studio',
'LBL_RELATIONSHIPS'=>'מערכות יחסים',
'LBL_RELATIONSHIP_EDIT' => 'ערוך מערכת יחסים',
'LBL_REL_NAME' => 'שם',
'LBL_REL_LABEL' => 'Label',
'LBL_REL_TYPE' => 'Type',
'LBL_RHS_MODULE'=>'מודול קשור',
'LBL_NO_RELS' => 'אין מערכות יחסים',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'תנאי אופציונלי' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'עמודה',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'ערך',
'LBL_SUBPANEL_FROM'=>'פאנל משנה מ',
'LBL_RELATIONSHIP_ONLY'=>'לא ייווצרו אלמנטים גלויים עבור מערכת יחסים זו מכיוון שקיימת מערכת יחסים גלויה קודמת בין שני מודולים אלה.',
'LBL_ONETOONE' => 'אחד לאחד',
'LBL_ONETOMANY' => 'אחד לרבים',
'LBL_MANYTOONE' => 'רבים לאחד',
'LBL_MANYTOMANY' => 'רבים לרבים',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'בחר פונקציה או רכיב.',
'LBL_QUESTION_MODULE1' => 'בחר מודול.',
'LBL_QUESTION_EDIT' => 'בחר מודול לעריכה.',
'LBL_QUESTION_LAYOUT' => 'בחר תצורה לעריכה.',
'LBL_QUESTION_SUBPANEL' => 'בחר פאנל משנה לעריכה.',
'LBL_QUESTION_SEARCH' => 'בחר תצורת חיפוש לעריכה.',
'LBL_QUESTION_MODULE' => 'בחר רכיב מודול לעריכה.',
'LBL_QUESTION_PACKAGE' => 'בחר חבילה לעריכה, או צור חבילה חדשה.',
'LBL_QUESTION_EDITOR' => 'בחר כלי.',
'LBL_QUESTION_DROPDOWN' => 'בחר תפריט גלילה לעריכה, או צור תפריט גלילה חדש.',
'LBL_QUESTION_DASHLET' => 'בחר תצורת חלונית לעריכה.',
'LBL_QUESTION_POPUP' => 'בחר תצורת popup לעריכה.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'קשר אל',
'LBL_NAME'=>'שם',
'LBL_LABELS'=>'תוויות',
'LBL_MASS_UPDATE'=>'Mass Update',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'מודול',
'LBL_DEFAULT_VALUE'=>'Default Value',
'LBL_REQUIRED'=>'Required',
'LBL_DATA_TYPE'=>'Type',
'LBL_HCUSTOM'=>'מותאם אישית',
'LBL_HDEFAULT'=>'ברירת מחדל',
'LBL_LANGUAGE'=>'Language:',
'LBL_CUSTOM_FIELDS' => '* שדות שנוצרו ב-Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Edit Labels',
'LBL_SECTION_PACKAGES' => 'חבילות',
'LBL_SECTION_PACKAGE' => 'חבילה',
'LBL_SECTION_MODULES' => 'מודולים',
'LBL_SECTION_PORTAL' => 'שער',
'LBL_SECTION_DROPDOWNS' => 'תפריטי גלילה',
'LBL_SECTION_PROPERTIES' => 'ערכים',
'LBL_SECTION_DROPDOWNED' => 'ערוך גלילה',
'LBL_SECTION_HELP' => 'Help',
'LBL_SECTION_ACTION' => 'Action',
'LBL_SECTION_MAIN' => 'ראשי',
'LBL_SECTION_EDPANELLABEL' => 'ערוך תווית פאנל',
'LBL_SECTION_FIELDEDITOR' => 'ערוך שדה',
'LBL_SECTION_DEPLOY' => 'פרוס',
'LBL_SECTION_MODULE' => 'מודול',
'LBL_SECTION_VISIBILITY_EDITOR'=>'ערוך נראות',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'ברירת מחדל',
'LBL_HIDDEN'=>'Hidden',
'LBL_AVAILABLE'=>'זמין',
'LBL_LISTVIEW_DESCRIPTION'=>'מופיעות למטה שלוש עמודות. העמודה <b>ברירת מחדל</b> מכילה שדות שמופיעים בתצוגת רשימה כברירת מחדל. העמודה <b>נוספים</b> מכילה שדות שמשתמש יכול לבחור להשתמש בהם ליצירת תצוגה מותאמת אישית. העמודה <b>זמינים</b> מציגה שדות הזמינים לך כמנהל מערכת להוספה לעמודות ברירת מחדל או נוספים לשימוש על ידי משתמשים.',
'LBL_LISTVIEW_EDIT'=>'עורך תצוגת רשימה',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Preview',
'LBL_MB_RESTORE'=>'שחזר',
'LBL_MB_DELETE'=>'מחק',
'LBL_MB_COMPARE'=>'השווה',
'LBL_MB_DEFAULT_LAYOUT'=>'תצורה ברירת מחדל',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Add',
'LBL_BTN_SAVE'=>'שמירה',
'LBL_BTN_SAVE_CHANGES'=>'שמור שינויים',
'LBL_BTN_DONT_SAVE'=>'בטל שינויים',
'LBL_BTN_CANCEL'=>'Cancel',
'LBL_BTN_CLOSE'=>'Close',
'LBL_BTN_SAVEPUBLISH'=>'שמירה ופריסה',
'LBL_BTN_NEXT'=>'הבא',
'LBL_BTN_BACK'=>'חזרה',
'LBL_BTN_CLONE'=>'כפיל',
'LBL_BTN_COPY' => 'העתק',
'LBL_BTN_COPY_FROM' => 'העתק מ...',
'LBL_BTN_ADDCOLS'=>'הוסף עמודות',
'LBL_BTN_ADDROWS'=>'הוסף שורות',
'LBL_BTN_ADDFIELD'=>'Add Field',
'LBL_BTN_ADDDROPDOWN'=>'הוסף תפריט נגלל',
'LBL_BTN_SORT_ASCENDING'=>'מיין בסדר עולה',
'LBL_BTN_SORT_DESCENDING'=>'מיין בסדר יורד',
'LBL_BTN_EDLABELS'=>'Edit Labels',
'LBL_BTN_UNDO'=>'בטל',
'LBL_BTN_REDO'=>'בצע שנית',
'LBL_BTN_ADDCUSTOMFIELD'=>'הוסף שדה מותאם אישית',
'LBL_BTN_EXPORT'=>'ייצא התאמות אישיות',
'LBL_BTN_DUPLICATE'=>'שכפל',
'LBL_BTN_PUBLISH'=>'Publish',
'LBL_BTN_DEPLOY'=>'פרוס',
'LBL_BTN_EXP'=>'Export',
'LBL_BTN_DELETE'=>'מחק',
'LBL_BTN_VIEW_LAYOUTS'=>'הצג תצורות',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'צפה בפריסות דף מובייל',
'LBL_BTN_VIEW_FIELDS'=>'הצג שדות',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'הצג מערכות יחסים',
'LBL_BTN_ADD_RELATIONSHIP'=>'הוסף מערכת יחסים',
'LBL_BTN_RENAME_MODULE' => 'שנה שם מודול',
'LBL_BTN_INSERT'=>'הכנס',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'שגיאה: שדה כבר קיים',
'ERROR_INVALID_KEY_VALUE'=> "Error: Invalid Key Value: [&#39;]",
'ERROR_NO_HISTORY' => 'לא נמצאו קבצי היסטוריה',
'ERROR_MINIMUM_FIELDS' => 'התצורה חייבת להכיל לפחות שדה אחד',
'ERROR_GENERIC_TITLE' => 'An error has occured',
'ERROR_REQUIRED_FIELDS' => 'Are you sure you wish to continue? The following required fields are missing from the layout:',
'ERROR_ARE_YOU_SURE' => 'האם אתה בטוח שברצונך להמשיך?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'לשדות הבאים יש ערכים מחושבים שלא יחושבו שוב בזמן אמת בתצוגת עריכה סלולרית של SugarCRM:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'לשדות הבאים יש ערכים מחושבים שלא יחושבו שוב בזמן אמת בתצוגת עריכה של שער SugarCRM:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'המודולים הבאים לא פעילים',
    'LBL_PORTAL_ENABLE_MODULES' => 'אם ברצונך לאפשר אותם בפורטל אנא אפשר אותם  <br /><a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">כאן</a>.',
    'LBL_PORTAL_CONFIGURE' => 'הגדר פורטל',
    'LBL_PORTAL_THEME' => 'פורטל נושא',
    'LBL_PORTAL_ENABLE' => 'אפשר',
    'LBL_PORTAL_SITE_URL' => 'אתר הפורטל לך זמין ב',
    'LBL_PORTAL_APP_NAME' => 'שם אפליקציה',
    'LBL_PORTAL_LOGO_URL' => 'לוגו URL',
    'LBL_PORTAL_LIST_NUMBER' => 'מספר רשומות שיוצגו ברשימה',
    'LBL_PORTAL_DETAIL_NUMBER' => 'מספר שדות שיוצגו בצפיה בפרטים',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'מספר תוצאות שיוצגו בחיפוש גלובלי',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'ברירת מחדל מוקצה עבור הרשמה חדשה לפורטל',

'LBL_PORTAL'=>'שער',
'LBL_PORTAL_LAYOUTS'=>'תצורות שער',
'LBL_SYNCP_WELCOME'=>'אנא הזן את כתובת ה-URL של מופע השער שברצונך לעדכן.',
'LBL_SP_UPLOADSTYLE'=>'בחר גיליון עיצוב להעלאה מהמחשב שלך.<br> גיליון העיצוב יוטמע בשער Sugar בפעם הבאה שתבצע סנכרון.',
'LBL_SP_UPLOADED'=> 'הועלה',
'ERROR_SP_UPLOADED'=>'אנא ודא כי אתה מעלה גיליון עיצוב css.',
'LBL_SP_PREVIEW'=>'להלן תצוגה מקדימה שמציגה כיצד ייראה השער של Sugar עם שימוש בגיליון העיצוב.',
'LBL_PORTALSITE'=>'Sugar Portal URL:',
'LBL_PORTAL_GO'=>'עבור',
'LBL_UP_STYLE_SHEET'=>'העלה גיליון עיצוב',
'LBL_QUESTION_SUGAR_PORTAL' => 'בחר תצורת שער Sugar לעריכה.',
'LBL_QUESTION_PORTAL' => 'בחר תצורת שער לעריכה.',
'LBL_SUGAR_PORTAL'=>'עורך שער Sugar',
'LBL_USER_SELECT' => '--בחר--',

//PORTAL PREVIEW
'LBL_CASES'=>'Cases',
'LBL_NEWSLETTERS'=>'ניוזלטרים',
'LBL_BUG_TRACKER'=>'מעקב באגים',
'LBL_MY_ACCOUNT'=>'החשבון שלי',
'LBL_LOGOUT'=>'יציאה',
'LBL_CREATE_NEW'=>'צור חדש',
'LBL_LOW'=>'נמוך',
'LBL_MEDIUM'=>'Medium',
'LBL_HIGH'=>'גבוה',
'LBL_NUMBER'=>'מספר:',
'LBL_PRIORITY'=>'Priority:',
'LBL_SUBJECT'=>'Subject',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'שם חבילה:',
'LBL_MODULE_NAME'=>'שם מודול:',
'LBL_MODULE_NAME_SINGULAR' => 'שם מודול יחיד',
'LBL_AUTHOR'=>'מחבר:',
'LBL_DESCRIPTION'=>'Description:',
'LBL_KEY'=>'מפתח:',
'LBL_ADD_README'=>'Readme',
'LBL_MODULES'=>'מודולים:',
'LBL_LAST_MODIFIED'=>'השתנה לאחרונה:',
'LBL_NEW_MODULE'=>'מודול חדש',
'LBL_LABEL'=>'תווית רבים',
'LBL_LABEL_TITLE'=>'Label',
'LBL_SINGULAR_LABEL' => 'תווית יחיד',
'LBL_WIDTH'=>'רוחב',
'LBL_PACKAGE'=>'חבילה:',
'LBL_TYPE'=>'Type:',
'LBL_TEAM_SECURITY'=>'אבטחת צוות',
'LBL_ASSIGNABLE'=>'ניתן להקצאה',
'LBL_PERSON'=>'אדם',
'LBL_COMPANY'=>'חברה',
'LBL_ISSUE'=>'בעיה',
'LBL_SALE'=>'מכירה',
'LBL_FILE'=>'קובץ',
'LBL_NAV_TAB'=>'לשונית ניווט',
'LBL_CREATE'=>'צור',
'LBL_LIST'=>'List',
'LBL_VIEW'=>'View',
'LBL_LIST_VIEW'=>'List View',
'LBL_HISTORY'=>'View History',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'שחזר תצורה ברירת מחדל',
'LBL_ACTIVITIES'=>'Activities',
'LBL_SEARCH'=>'Search',
'LBL_NEW'=>'חדש',
'LBL_TYPE_BASIC'=>'בסיסי',
'LBL_TYPE_COMPANY'=>'חברה',
'LBL_TYPE_PERSON'=>'אדם',
'LBL_TYPE_ISSUE'=>'בעיה',
'LBL_TYPE_SALE'=>'מכירה',
'LBL_TYPE_FILE'=>'קובץ',
'LBL_RSUB'=>'זה פאנל המשנה שיופיע במודול שלך',
'LBL_MSUB'=>'זה פאנל המשנה שהמודל שלך מעביר למודול הקשור להצגה',
'LBL_MB_IMPORTABLE'=>'אפשר פעולות ייבוא',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'גלוי',
'LBL_VE_HIDDEN'=>'מוסתר',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] נמחקה',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'ייצא התאמות אישיות',
'LBL_EC_NAME'=>'שם חבילה:',
'LBL_EC_AUTHOR'=>'מחבר:',
'LBL_EC_DESCRIPTION'=>'Description:',
'LBL_EC_KEY'=>'מפתח:',
'LBL_EC_CHECKERROR'=>'אנא בחר מודול.',
'LBL_EC_CUSTOMFIELD'=>'שדה או שדות מותאמים אישית',
'LBL_EC_CUSTOMLAYOUT'=>'תצורה או תצורות מותאמות אישית',
'LBL_EC_CUSTOMDROPDOWN' => 'רשימות נפתחות מותאמות אישית',
'LBL_EC_NOCUSTOM'=>'אין מודולים שהותאמו אישית.',
'LBL_EC_EXPORTBTN'=>'Export',
'LBL_MODULE_DEPLOYED' => 'המודול נפרש.',
'LBL_UNDEFINED' => 'לא הוגדר',
'LBL_EC_CUSTOMLABEL'=>'תוויות מותאמית',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'כישלון באחזור נתונים',
'LBL_AJAX_TIME_DEPENDENT' => 'פעולה תלוית-זמן בתהליך. אנא המתן ונסה שוב בעוד מספר שניות.',
'LBL_AJAX_LOADING' => 'טוען...',
'LBL_AJAX_DELETING' => 'מוחק...',
'LBL_AJAX_BUILDPROGRESS' => 'בנייה בתהליך...',
'LBL_AJAX_DEPLOYPROGRESS' => 'פרישה בתהליך...',
'LBL_AJAX_FIELD_EXISTS' =>'שם השדה שהזנת כבר קיים. אנא הזן שם שדה חדש.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'האם אתה בטוח שברצונך להסיר חבילה זו? זה ימחק את כל הקבצים שמשויכים לחבילה זאת לצמיתות.',
'LBL_JS_REMOVE_MODULE' => 'האם אתה בטוח שברצונך להסיר מודול זה? זה ימחק את כל הקבצים שמשויכים למודול זה לצמיתות.',
'LBL_JS_DEPLOY_PACKAGE' => 'התאמות אישיות שביצעת ב-Studio יוחלפו כאשר מודול זה נפרש מחדש. האם אתה בטוח שברצונך להמשיך?',

'LBL_DEPLOY_IN_PROGRESS' => 'פורש חבילה',
'LBL_JS_VALIDATE_NAME'=>'Name - Must be alphanumeric with no spaces and starting with a letter',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'מפתח חבילה כבר קיים',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'שם חבילה כבר קיים',
'LBL_JS_PACKAGE_NAME'=>'שם החבילה - חייב להתחיל באות ויכול לכלול רק אותיות, מספרים וקווים תחתונים. לא ניתן להשתמש ברווחים או בתווים מיוחדים אחרים.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'מפתח - צריך להיות אלפאבתי-נומרי ולהתחיל עם אות',
'LBL_JS_VALIDATE_KEY'=>'Key - Must be alphanumeric',
'LBL_JS_VALIDATE_LABEL'=>'אנא הזן תווית שתשמש בתור שם התצוגה עבור מודול זה',
'LBL_JS_VALIDATE_TYPE'=>'אנא בחר את סוג המודול שברצונך לבנות מהרשימה למעלה',
'LBL_JS_VALIDATE_REL_NAME'=>'שם - חייב להכיל אותיות וספרות ללא רווחים',
'LBL_JS_VALIDATE_REL_LABEL'=>'תווית - אנא הוסף תווית שתופיע מעל לפאנל משנה',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'אתה בטוח שברצונך למחוק את התיבה הנפתחת הדרושה הזו? זה עשוי להשפיע על אופן התפקיד של האפליקציה שלך',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'אתה בטוח שברצונך למחוק את התיבה הנפתחת הדרושה הזו? מחיקת שלבי הנסגר בהצלחה או נסגר בכשלון יגרום למודול תחזית לעבוד בצורה לקויה',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'אתה בטוח שברצונך למחוק את סטוט מכירה חדשה? מחיקת שלבי הנסגר בהצלחה או נסגר בכשלון יגרום למודול תחזית לעבוד בצורה לקויה',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'אתה בטוח שברצונך למחוק את סטטוס התקדמות מכירה? מחיקת שלבי הנסגר בהצלחה או נסגר בכשלון יגרום למודול תחזית לעבוד בצורה לקויה',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'אתה בטוח שברצונך למחוק את שלב המכירות שנסגרו בהצלחה? מחיקת שלבי הנסגר בהצלחה או נסגר בכשלון יגרום למודול תחזית לעבוד בצורה לקויה',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'אתה בטוח שברצונך למחוק את שלב המכירות שנסגרו בכשלון? מחיקת שלבי הנסגר בהצלחה או נסגר בכשלון יגרום למודול תחזית לעבוד בצורה לקויה',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Are you sure you wish to delete this relationship?',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'זה יהפוך את מערכת היחסים לקבועה. האם אתה בטוח שברצונך לפרוש מערכת יחסים זו?',
'LBL_CONFIRM_DONT_SAVE' => 'שינויים נעשו מאז השמירה האחרונה שלך, האם ברצונך לשמור?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'לשמור שינויים?',
'LBL_CONFIRM_LOWER_LENGTH' => 'ייתכן והנתונים יוקטנו ולא ניתן לבטל פעולה זאת, האם אתה בטוח שברצונך להמשיך?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'בחר את סוג הנתונים המתאים לפי סוג הנתונים שיוזנו בשדה.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'הגדר את השדה כך שיאפשר חיפוש טקסט מלא בתוכו.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'חיזוק הוא התהליך של שיפור הרלוונטיות של שדות רישום.<br />שדות עם רמת חיזוק גבוהה יותר יקבלו משקל רב יותר כאשר מבוצע החיפוש. כאשר מבוצע חיפוש, רישומים תואמים המכילים שדות עם משקל רב יותר יופיעו במקום גבוה יותר בתוצאות החיפוש.<br />ערך ברירת המחדל הוא 1.0 ומייצג חיזוק ניטראלי. כדי להחיל חיזוק חיובי, כל ערך גבוה מ-1 יתקבל. עבור חיזוק שלילי השתמש בערך נמוך מ-1. לדוגמה ערך של 1.35 יחזק שדה חיובית ב-135%. שימוש בערך של 0.60 יחיל חיזוק שלילי.<br />שים לב שבגרסאות קודמות נדרש לבצע מפתוח חוזר של חיפוש טקסט מלא. זה כבר לא נדרש.',
'LBL_POPHELP_IMPORTABLE'=>'<b>כן</b>: השדה ייכלל בפעולת ייבוא.<br><b>לא</b>: השדה לא ייכלל בייבוא.<br><b>חובה</b>: ערך עבור השדה חייב להינתן בכל ייבוא.',
'LBL_POPHELP_PII'=>'שדה זה יסומן באופן אוטומטי לביקורת ויהיה זמין בתצוגת &#39;מידע אישי&#39;.<br>ניתן למחוק לצמיתות שדות &#39;מידע אישי&#39; כשהרשומה קשורה לבקשת מחיקה של &#39;פרטיות נתונים&#39;.<br>המחיקה תתבצע דרך המודול &#39;פרטיות נתונים&#39; ועל ידי מנהלי מערכת או משתמשים בעלי התפקיד &#39;מנהל פרטיות נתונים&#39;.',
'LBL_POPHELP_IMAGE_WIDTH'=>'הזן מספר עבור רוחב בפיקסלים.<br> ממדי התמונה שהועלתה ישתנו לרוחב זה.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'הזן מספר עבור גובה בפיקסלים.<br> ממדי התמונה שהועלתה ישתנו לגובה זה.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'בחר כדי להשתמש בשדה זה בעת חיפוש עבור רישומים באמצעות החיפוש הגלובלי במודול זה.',
//Revert Module labels
'LBL_RESET' => 'איפוס',
'LBL_RESET_MODULE' => 'אפס מודול',
'LBL_REMOVE_CUSTOM' => 'הסר התאמות אישיות',
'LBL_CLEAR_RELATIONSHIPS' => 'נקה מערכות יחסים',
'LBL_RESET_LABELS' => 'אפס תוויות',
'LBL_RESET_LAYOUTS' => 'אפס תצורות',
'LBL_REMOVE_FIELDS' => 'הסר שדות מותאמים אישית',
'LBL_CLEAR_EXTENSIONS' => 'נקה הרחבות',

'LBL_HISTORY_TIMESTAMP' => 'TimeStamp',
'LBL_HISTORY_TITLE' => 'history',

'fieldTypes' => array(
                'varchar'=>'TextField',
                'int'=>'מספר שלם וחיובי',
                'float'=>'צף',
                'bool'=>'תיבת סימון',
                'enum'=>'תפריט גלילה',
                'multienum' => 'MultiSelect',
                'date'=>'תאריך',
                'phone' => 'טלפון',
                'currency' => 'מטבע:',
                'html' => 'HTML',
                'radioenum' => 'רדיו',
                'relate' => 'קשר',
                'address' => 'כתובת',
                'text' => 'TextArea',
                'url' => 'כתובת URL',
                'iframe' => 'IFrame',
                'image' => 'תמונה',
                'encrypt'=>'הצפן',
                'datetimecombo' =>'Datetime',
                'decimal'=>'עשרונים',
),
'labelTypes' => array(
    "" => "תוויות נפוצות",
    "all" => "כל התוויות",
),

'parent' => 'קשר Flex',

'LBL_ILLEGAL_FIELD_VALUE' =>"מפתח גלילה לא יכול להכיל מרכאות.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"אתה בוחר פריט זה להסרה מהרשימה הנגללת. כל שדות גלילה המשתמשים ברשימה זו עם פריט זה בתור ערך לא יציגו עוד את הערך, ולא יהיה ניתן לבחור את הערך משדות הגלילה. האם אתה בטוח שברצונך להמשיך?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'כל המודולים',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (ביחס {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'העתק מפריסת דף',
);
