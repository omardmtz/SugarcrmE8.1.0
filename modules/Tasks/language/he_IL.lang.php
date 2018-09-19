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

$mod_strings = array (
  // Dashboard Names
  'LBL_TASKS_LIST_DASHBOARD' => 'לוח מחוונים של רשימת משימות',

  'LBL_MODULE_NAME' => 'משימות',
  'LBL_MODULE_NAME_SINGULAR' => 'משימה',
  'LBL_TASK' => 'משימות:',
  'LBL_MODULE_TITLE' => 'משימות: דף ראשי',
  'LBL_SEARCH_FORM_TITLE' => 'חפש משימה',
  'LBL_LIST_FORM_TITLE' => 'רשימת משימות',
  'LBL_NEW_FORM_TITLE' => 'צור משימה',
  'LBL_NEW_FORM_SUBJECT' => 'נושא:',
  'LBL_NEW_FORM_DUE_DATE' => 'תאריך תפוגה:',
  'LBL_NEW_FORM_DUE_TIME' => 'שעת תפוגה:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'סגור',
  'LBL_LIST_SUBJECT' => 'נושא',
  'LBL_LIST_CONTACT' => 'איש קשר',
  'LBL_LIST_PRIORITY' => 'עדיפות',
  'LBL_LIST_RELATED_TO' => 'קשור אל',
  'LBL_LIST_DUE_DATE' => 'תאריך תפוגה',
  'LBL_LIST_DUE_TIME' => 'שעת תפוגה',
  'LBL_SUBJECT' => 'נושא:',
  'LBL_STATUS' => 'מצב:',
  'LBL_DUE_DATE' => 'תאריך תפוגה:',
  'LBL_DUE_TIME' => 'שעת תפוגה:',
  'LBL_PRIORITY' => 'עדיפות:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'תאריך ושעת תפוגה:',
  'LBL_START_DATE_AND_TIME' => 'תאריך ושעת התחלה:',
  'LBL_START_DATE' => 'תאריך התחלה:',
  'LBL_LIST_START_DATE' => 'תאריך התחלה',
  'LBL_START_TIME' => 'שעת התחלה:',
  'LBL_LIST_START_TIME' => 'שעת התחלה',
  'DATE_FORMAT' => '(dd-mm-yyyy)',
  'LBL_NONE' => 'אין',
  'LBL_CONTACT' => 'איש קשר:',
  'LBL_EMAIL_ADDRESS' => 'כתובת דואר אלקטרוני:',
  'LBL_PHONE' => 'טלפון:',
  'LBL_EMAIL' => 'כתובת דואר אלקטרוני:',
  'LBL_DESCRIPTION_INFORMATION' => 'תיאור המידע',
  'LBL_DESCRIPTION' => 'תיאור:',
  'LBL_NAME' => 'שם:',
  'LBL_CONTACT_NAME' => 'שם איש קשר',
  'LBL_LIST_COMPLETE' => 'הושלם:',
  'LBL_LIST_STATUS' => 'מצב',
  'LBL_DATE_DUE_FLAG' => 'אין תאריך תפוגה',
  'LBL_DATE_START_FLAG' => 'אין תאריך התחלה',
  'ERR_DELETE_RECORD' => 'למחיקת איש הקשר אנא ציין מספר רשומה.',
  'ERR_INVALID_HOUR' => 'אנא הזן שעה שבין 0 ל 24',
  'LBL_DEFAULT_PRIORITY' => 'בינוני',
  'LBL_LIST_MY_TASKS' => 'המשימות הפתוחות שלי',
  'LNK_NEW_TASK' => 'צור משימה',
  'LNK_TASK_LIST' => 'הצג משימות',
  'LNK_IMPORT_TASKS' => 'ייבא משימות',
  'LBL_CONTACT_FIRST_NAME'=>'שם פרטי של איש הקשר',
  'LBL_CONTACT_LAST_NAME'=>'שם משפחה של איש הקשר',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'הוקצה למשתמש',
  'LBL_ASSIGNED_TO_NAME'=>'הוקצה עבור:',
  'LBL_LIST_DATE_MODIFIED' => 'שונה בתאריך',
  'LBL_CONTACT_ID' => 'איש קשר זהות:',
  'LBL_PARENT_ID' => 'מזהה אב:',
  'LBL_CONTACT_PHONE' => 'טלפון של איש השקשר:',
  'LBL_PARENT_NAME' => 'סוג אב:',
  'LBL_ACTIVITIES_REPORTS' => 'דוח פעיליות',
  'LBL_EDITLAYOUT' => 'ערוך תצורה' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'סקירת משימות',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'הערות',
  'LBL_REVENUELINEITEMS' => 'שורות פרטי הכנסה',
  //For export labels
  'LBL_DATE_DUE' => 'תאריך תפוגה',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'הוקצה למשתמש ששמו',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'הוקצה למשתמש ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'נערך על ידי מזהה',
  'LBL_EXPORT_CREATED_BY' => 'נוצר על ידי ID',
  'LBL_EXPORT_PARENT_TYPE' => 'קשור למודול',
  'LBL_EXPORT_PARENT_ID' => 'זהות הורה:',
  'LBL_TASK_CLOSE_SUCCESS' => 'המסימה נסגרה בהצלחה',
  'LBL_ASSIGNED_USER' => 'הוקצה עבור',

    'LBL_NOTES_SUBPANEL_TITLE' => 'הערות',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'המודול {{plural_module_name}} מכיל פעולות גמישות, פריטים לביצוע, או סוג אחר של פעילות הדורשת השלמה. ניתן לקשר רישומים של {{module_name}} לרישום אחד ברוב המודולים באמצעות שדה הקישור הגמיש וניתן גם לקשר אותם ל{{contacts_singular_module}} אחד. קיימות מספר דרכים שבהן תוכל ליצור {{plural_module_name}} ב-Sugar כמו על ידי המודול {{plural_module_name}}, שכפול, ייבוא {{plural_module_name}}, וכו&#39;. ברגע שנוצר הרישום של {{module_name}}, תוכל להציג ולערוך מידע השייך ל{{module_name}} באמצעות תצוגת הרישומים של {{plural_module_name}}. תלוי בפרטי ה{{module_name}}, ייתכן ותוכל גם להציג ולערוך את המידע של {{module_name}} דרך מודול לוח השנה. כל רישום של {{module_name}} יכול לאחר מכן להתקשר לרישומים אחרים של Sugar כמו {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} ורבים אחרים.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

- Edit this record&#39;s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'המודול {{plural_module_name}} מורכב מפעולות גמישות, פריטי רשימת פעולות או סוג אחר של פעילות שצריך לעשות.

כדי ליצור {{module_name}}:
1. ספק ערכים לשדות לפי רצונך.
 - יש להשלים את השדות המסומנים כ"נדרשים" לפני השמירה.
 - לחץ על "הצג עוד" כדי להציג שדות אחרים לפי הצורך.
2. לחץ על "שמור" כדי להשלים את הרישום החדש ולחזור לדף הקודם.',

);
