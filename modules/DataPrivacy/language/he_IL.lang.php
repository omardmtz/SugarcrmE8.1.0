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
    'LBL_MODULE_NAME' => 'פרטיות נתונים',
    'LBL_MODULE_NAME_SINGULAR' => 'פרטיות נתונים',
    'LBL_NUMBER' => 'מספר',
    'LBL_TYPE' => 'סוג',
    'LBL_SOURCE' => 'מקור',
    'LBL_REQUESTED_BY' => 'התבקש על ידי',
    'LBL_DATE_OPENED' => 'תאריך פתיחה',
    'LBL_DATE_DUE' => 'תאריך סיום',
    'LBL_DATE_CLOSED' => 'תאריך סגירה',
    'LBL_BUSINESS_PURPOSE' => 'הסכמה למטרות עסקיות עבור',
    'LBL_LIST_NUMBER' => 'מספר',
    'LBL_LIST_SUBJECT' => 'נושא',
    'LBL_LIST_PRIORITY' => 'עדיפות',
    'LBL_LIST_STATUS' => 'סטטוס',
    'LBL_LIST_TYPE' => 'סוג',
    'LBL_LIST_SOURCE' => 'מקור',
    'LBL_LIST_REQUESTED_BY' => 'התבקש על ידי',
    'LBL_LIST_DATE_DUE' => 'תאריך סיום',
    'LBL_LIST_DATE_CLOSED' => 'תאריך סגירה',
    'LBL_LIST_DATE_MODIFIED' => 'תאריך שינוי',
    'LBL_LIST_MODIFIED_BY_NAME' => 'שונה על ידי',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'משתמש שהוקצה',
    'LBL_SHOW_MORE' => 'הצג פעילויות נוספות של פרטיות נתונים',
    'LNK_DATAPRIVACY_LIST' => 'הצג פעילויות של פרטיות נתונים',
    'LNK_NEW_DATAPRIVACY' => 'צור פעילות של פרטיות נתונים',
    'LBL_LEADS_SUBPANEL_TITLE' => 'לידים',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'אנשי קשר',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'מטרות',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'חשבונות',
    'LBL_LISTVIEW_FILTER_ALL' => 'כל הפעילויות של פרטיות נתונים',
    'LBL_ASSIGNED_TO_ME' => 'פעילויות פרטיות הנתונים שלי',
    'LBL_SEARCH_AND_SELECT' => 'חפש ובחר פעילויות של פרטיות נתונים',
    'TPL_SEARCH_AND_ADD' => 'חפש והוסף פעילויות של פרטיות נתונים',
    'LBL_WARNING_ERASE_CONFIRM' => 'אתה עומד למחוק לצמיתות {0} שדה (שדות). אין אפשרות לשחזר נתונים אלה אחרי השלמת המחיקה. האם אתה בטוח שברצונך להמשיך?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'יש {0} שדה (שדות) שסומנו למחיקה. האישור יבטל את המחיקה, יישמר את כל הנתונים ויסמן בקשה זו כ&#39;נדחה&#39;. האם אתה בטוח שברצונך להמשיך?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'אתה עומד לסמן בקשה זו כ&#39;הושלם&#39;. פעולה זו תגדיר לצמיתות את הסטטוס כ&#39;הושלם&#39; ולא תוכל לפתוח אותה שוב. האם אתה בטוח שברצונך להמשיך?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'אתה עומד לסמן בקשה זו כ&#39;נדחה&#39;. פעולה זו תגדיר לצמיתות את הסטטוס כ&#39;נדחה&#39; ולא תוכל לפתוח אותה שוב. האם אתה בטוח שברצונך להמשיך?',
    'LBL_RECORD_SAVED_SUCCESS' => 'יצרת בהצלחה את הפעילות לפרטיות הנתונים <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'דחה',
    'LBL_COMPLETE_BUTTON_LABEL' => 'השלם',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'מחק והשלם',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'מחק את השדות שנבחרו דרך לוחות המשנה',
    'LBL_COUNT_FIELDS_MARKED' => 'שדות שסומנו למחיקה',
    'LBL_NO_RECORDS_MARKED' => 'לא סומנו שדות או רשומות למחיקה.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'לוח מחוונים עבור רשומת פרטיות נתונים',

    // list view
    'LBL_HELP_RECORDS' => 'המודול &#39;פרטיות נתונים&#39; עוקב אחר פעילויות הקשורות לפרטיות, כולל בקשות הסכמה ונושא, כדי לתמוך בהליכי הפרטיות של החברה שלך. צור רשומות של נתוני פרטיות הקשורות לרשומה בודדת (לדוגמה, לאיש קשר) כדי לעקוב אחרי ההסכמה או לפעול לגבי בקשת פרטיות.',
    // record view
    'LBL_HELP_RECORD' => 'המודול &#39;פרטיות נתונים&#39; עוקב אחר פעילויות הקשורות לפרטיות, כולל בקשות הסכמה ונושא, כדי לתמוך בהליכי הפרטיות של החברה שלך. צור רשומות של נתוני פרטיות הקשורות לרשומה בודדת (לדוגמה, לאיש קשר) כדי לעקוב אחרי ההסכמה או לפעול לגבי בקשת פרטיות. לאחר שהפעולה הנדרשת הושלמה, משתמשים בעלי תפקיד &#39;מנהל פרטיות נתונים&#39; יכולים ללחוץ על &#39;השלם&#39; או &#39;דחה&#39; כדי לעדכן את הסטטוס.

לבקשות מחיקה, בחר ב"סמן למחיקה" עבור כל אחת מהרשומות הבודדות המפורטות בלוחות המשנה למטה. לאחר שכל השדות הרצויים נבחרו, לחיצה על "מחק והשלם" תסיר לצמיתות את ערכי השדות ותסמן את רשומת פרטיות הנתונים כ&#39;הושלם&#39;.',
);
