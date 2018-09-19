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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'מכירה',
  'LBL_MODULE_TITLE' => 'מכירה: דף הבית',
  'LBL_SEARCH_FORM_TITLE' => 'חפש מכירה',
  'LBL_VIEW_FORM_TITLE' => 'צפה המכירה',
  'LBL_LIST_FORM_TITLE' => 'רשימת מכירות',
  'LBL_SALE_NAME' => 'שם מכירה:',
  'LBL_SALE' => 'מכירה:',
  'LBL_NAME' => 'שם מכירה',
  'LBL_LIST_SALE_NAME' => 'שם',
  'LBL_LIST_ACCOUNT_NAME' => 'שם חשבון',
  'LBL_LIST_AMOUNT' => 'סכום',
  'LBL_LIST_DATE_CLOSED' => 'סגור',
  'LBL_LIST_SALE_STAGE' => 'שלב במכירות',
  'LBL_ACCOUNT_ID'=>'חשבון ID',
  'LBL_TEAM_ID' =>'צוות ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'מכירה - עדכון מטבע',
  'UPDATE_DOLLARAMOUNTS' => 'עדכן סכומים בשקלים חדשים',
  'UPDATE_VERIFY' => 'וודא סכומים',
  'UPDATE_VERIFY_TXT' => 'וודא שהסכומים במכירה מכילים ספרות בלבד(0-9) וחלקי שקל מופרשים ב(.)',
  'UPDATE_FIX' => 'סכומים קבועים',
  'UPDATE_FIX_TXT' => 'ניסיונות לתקן כל סכומים לא חוקיים ביצירת ערך עשרוני חוקי מהסכום הנוכחי. כל סכום שנערך מגובה בשדה מסד הנתונים amount_backup. במידה ואתה מפעיל את זה ונתקל בבעיות, אל תפעיל בשנית מבלי לשחזר את הגיבוי, מכיוון שהוא עלול לכתוב נתונים לא חוקיים חדשים על הגיבוי.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'עדכן את סכון המכירה בדולרים בהתבסס על שער החליפין שהוזן למערכת. ערך זה בשימושם של גרפים שמציגים שער שקל ושער דולר על בסיס שער החליפין שהוזן למערכת.',
  'UPDATE_CREATE_CURRENCY' => 'צור מטבע חדש:',
  'UPDATE_VERIFY_FAIL' => 'אימות הרשומה נכשל:',
  'UPDATE_VERIFY_CURAMOUNT' => 'הסכום הנוכחי:',
  'UPDATE_VERIFY_FIX' => 'הרצת תיקון תסתיים ב',
  'UPDATE_INCLUDE_CLOSE' => 'כלול רשומות סגורות',
  'UPDATE_VERIFY_NEWAMOUNT' => 'סכום חדש:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'מטבע חדש:',
  'UPDATE_DONE' => 'בוצע',
  'UPDATE_BUG_COUNT' => 'נמצא באג מנסה להתגבר עליו:',
  'UPDATE_BUGFOUND_COUNT' => 'באגים נמצאו:',
  'UPDATE_COUNT' => 'רשומות שעודכנו:',
  'UPDATE_RESTORE_COUNT' => 'סכומים ברשומה שאוחזרו:',
  'UPDATE_RESTORE' => 'אחזר סכומים',
  'UPDATE_RESTORE_TXT' => 'אחזר ערכים לסכומים מגבוי שבוצע תוך כדי תיקון.',
  'UPDATE_FAIL' => 'לא מצליח לעדכן -',
  'UPDATE_NULL_VALUE' => 'הסכון איננו תקף מחזיר אותו ל 0 -',
  'UPDATE_MERGE' => 'אחד מטבעות',
  'UPDATE_MERGE_TXT' => 'אחד מטבעות כפולות למטבע אחד. אם ישנן רשומות כפולות לאותו מטבע, תאחד אותם לאחד. האיחוד ישפיע על מטבעות במודולים אחרים.',
  'LBL_ACCOUNT_NAME' => 'שם חשבון:',
  'LBL_AMOUNT' => 'סכום:',
  'LBL_AMOUNT_USDOLLAR' => 'סכום ב USD:',
  'LBL_CURRENCY' => 'מטבע:',
  'LBL_DATE_CLOSED' => 'תאריך סגירה צפוי:',
  'LBL_TYPE' => 'סוג:',
  'LBL_CAMPAIGN' => 'קמפיין:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'לידים',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'פרויקטים',  
  'LBL_NEXT_STEP' => 'השלב הבא:',
  'LBL_LEAD_SOURCE' => 'מקור הליד:',
  'LBL_SALES_STAGE' => 'שלב במכירות:',
  'LBL_PROBABILITY' => 'הסתברות (%):',
  'LBL_DESCRIPTION' => 'תיאור:',
  'LBL_DUPLICATE' => 'ככל הנראה מכירה כפולה',
  'MSG_DUPLICATE' => 'המכירה שאתה עומד לעשות היא מכירה כפולה של מכירה שכבר קיימת. ישנה מכירה בשם זהה והיא רשומה מטה.<br>הקש על שמירה כדי לשמור המכירה או ביטול וחזרה למודול ללא שינויים.',
  'LBL_NEW_FORM_TITLE' => 'צור מכירה',
  'LNK_NEW_SALE' => 'צור מכירה',
  'LNK_SALE_LIST' => 'מכירה',
  'ERR_DELETE_RECORD' => 'למחיקת המכירה הזו עליך לציין מספר רשומה.',
  'LBL_TOP_SALES' => 'המכיאות העליונות שלי',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'אתה בטוח שברצונך למחוק איש קשר זה ממכירה זו?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'אתה טוח שברצונך למחוק מכירה זו מהפרוייקט?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'פעילויות',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'הסטוריה',
    'LBL_RAW_AMOUNT'=>'סכום גלמי',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'אנשי קשר',
	'LBL_ASSIGNED_TO_NAME' => 'משתמש:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'משתמש שהוקצה',
  'LBL_MY_CLOSED_SALES' => 'המכירות הסגורות שלי',
  'LBL_TOTAL_SALES' => 'סך-בכל מכירות',
  'LBL_CLOSED_WON_SALES' => 'מכירות שנסגרו בהצלחה',
  'LBL_ASSIGNED_TO_ID' =>'הוקצה עבור ID',
  'LBL_CREATED_ID'=>'נוצר על ידי ID',
  'LBL_MODIFIED_ID'=>'שונה על ידי ID',
  'LBL_MODIFIED_NAME'=>'שונה על ידי משתמש ששמו',
  'LBL_SALE_INFORMATION'=>'מידע כל המכירה',
  'LBL_CURRENCY_ID'=>'מטבע ID',
  'LBL_CURRENCY_NAME'=>'שם מטבע',
  'LBL_CURRENCY_SYMBOL'=>'סימול מטבע',
  'LBL_EDIT_BUTTON' => 'ערוך',
  'LBL_REMOVE' => 'הסר',
  'LBL_CURRENCY_RATE' => 'שער חליפין',

);

