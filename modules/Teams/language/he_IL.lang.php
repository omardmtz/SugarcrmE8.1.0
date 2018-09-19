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
    'ERR_ADD_RECORD' => 'עליך לציין מספר רישום כדי להוסיף משתמש לצוות זה.',
    'ERR_DUP_NAME' => 'שם צוות כבר בשימוש, אנא בחר שם אחר.',
    'ERR_DELETE_RECORD' => 'עליך לציין מספר רישום כדי למחוק צוות זה.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'שגיאה. הצוות שנבחר <b>({0})</b> הוא צוות שבחרת למחוק. אנא בחר צוות אחר.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'שגיאה. אין באפשרותך למחוק משתמש שהצוות הפרטי שלו נמחק.',
    'LBL_DESCRIPTION' => 'תיאור:',
    'LBL_GLOBAL_TEAM_DESC' => 'זמין לכולם',
    'LBL_INVITEE' => 'חברי הצוות',
    'LBL_LIST_DEPARTMENT' => 'מחלקה',
    'LBL_LIST_DESCRIPTION' => 'תיאור',
    'LBL_LIST_FORM_TITLE' => 'רשימת צוותים',
    'LBL_LIST_NAME' => 'שם',
    'LBL_FIRST_NAME' => 'שם פרטי:',
    'LBL_LAST_NAME' => 'שם משפחה:',
    'LBL_LIST_REPORTS_TO' => 'מדווח אל',
    'LBL_LIST_TITLE' => 'תואר',
    'LBL_MODULE_NAME' => 'קבוצות',
    'LBL_MODULE_NAME_SINGULAR' => 'צוות',
    'LBL_MODULE_TITLE' => 'צוותים: דף ראשי',
    'LBL_NAME' => 'שם הצוות:',
    'LBL_NAME_2' => 'שם הצוות(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'שם צוות ראשי',
    'LBL_NEW_FORM_TITLE' => 'צוות חדדש',
    'LBL_PRIVATE' => 'פרטי',
    'LBL_PRIVATE_TEAM_FOR' => 'צוות פרטי עבור:',
    'LBL_SEARCH_FORM_TITLE' => 'חיפוש צוות',
    'LBL_TEAM_MEMBERS' => 'חברי הצוות',
    'LBL_TEAM' => 'צוותים:',
    'LBL_USERS_SUBPANEL_TITLE' => 'משתמשים',
    'LBL_USERS' => 'משתמשים',
    'LBL_REASSIGN_TEAM_TITLE' => 'קיימים רישומים המוקצים לצוות(ים) הבא(ים): <b>{0}</b><br>לפני מחיקת הצוות(ים), עליך תחילה להקצות מחדש רישומים אלה לצוות חדש. בחר צוות שישמש כצוות מחליף.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'הקצה',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'הקצה [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'להמשיך כדי לעדכן את הרישומים שהושפעו על מנת להשתמש בצוות החדש?',
    'LBL_REASSIGN_TABLE_INFO' => 'טבלאות שעודכנו {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'הפעולה הושלמה בהצלחה.',
    'LNK_LIST_TEAM' => 'קבוצות',
    'LNK_LIST_TEAMNOTICE' => 'הודעות לצוות',
    'LNK_NEW_TEAM' => 'צור צוות',
    'LNK_NEW_TEAM_NOTICE' => 'צור הודעה לצוות',
    'NTC_DELETE_CONFIRMATION' => 'אתה בטוח בשברצונך למחוק רשומה זו?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Are you sure you want to remove this user\\&#39;s membership?',
    'LBL_EDITLAYOUT' => 'ערוך תצורה' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'הרשאות מבוססות צוות',
    'LBL_TBA_CONFIGURATION_DESC' => 'אפשר גישת צוות ונהל את הגישה על ידי מודול.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'אפשר הרשאות מבוססות צוות',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'בחר את המודולים שיש לאפשר',
    'LBL_TBA_CONFIGURATION_TITLE' => 'הפעלת הרשאות מבוססות צוות תאפשר לך להקצות הרשאות גישה ספציפיות למודולים נפרדים עבור צוותים ומשתמשים דרך &#39;ניהול תפקידים&#39;.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
השבתת הרשאות מבוססות צוות עבור מודול תחזיר את כל הנתונים המשויכים להרשאות מבוססות צוות עבור מודול זה למצבם הקודם, כולל הגדרות תהליכים או תהליכים המשתמשים בתכונה זו. נכללים בכך תפקידים המשתמשים באפשרות "בעלים וצוות נבחר" עבור מודול זה וכל הנתונים של ההרשאות מבוססות צוות עבור רשומות במודול זה. מומלץ גם להשתמש בכלי 'תיקון ובנייה מחדש מהירים' כדי לנקות את מטמון המערכת לאחר השבתת הרשאות מבוססות צוות עבור מודול כלשהו.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>אזהרה:</strong> השבתת הרשאות מבוססות צוות עבור מודול תחזיר את כל הנתונים המשויכים להרשאות מבוססות צוות עבור מודול זה למצבם הקודם, כולל הגדרות תהליכים או תהליכים המשתמשים בתכונה זו. נכללים בכך תפקידים המשתמשים באפשרות "בעלים וצוות נבחר" עבור מודול זה וכל הנתונים של ההרשאות מבוססות צוות עבור רשומות במודול זה. מומלץ גם להשתמש בכלי 'תיקון ובנייה מחדש מהירים' כדי לנקות את מטמון המערכת לאחר השבתת הרשאות מבוססות צוות עבור מודול כלשהו.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
השבתת הרשאות מבוססות צוות עבור מודול תחזיר את כל הנתונים המשויכים להרשאות מבוססות צוות עבור מודול זה למצבם הקודם, כולל הגדרות תהליכים או תהליכים המשתמשים בתכונה זו. נכללים בכך תפקידים המשתמשים באפשרות "בעלים וצוות נבחר" עבור מודול זה וכל הנתונים של ההרשאות מבוססות צוות עבור רשומות במודול זה. מומלץ גם להשתמש בכלי 'תיקון ובנייה מחדש מהירים' כדי לנקות את מטמון המערכת לאחר השבתת הרשאות מבוססות צוות עבור מודול כלשהו. אם אין לך גישה לכלי 'תיקון ובנייה מחדש מהירים', פנה למנהל מערכת בעל גישה לתפריט 'תיקון'.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>אזהרה:</strong> השבתת הרשאות מבוססות צוות עבור מודול תחזיר את כל הנתונים המשויכים להרשאות מבוססות צוות עבור מודול זה למצבם הקודם, כולל הגדרות תהליכים או תהליכים המשתמשים בתכונה זו. נכללים בכך תפקידים המשתמשים באפשרות "בעלים וצוות נבחר" עבור מודול זה וכל הנתונים של ההרשאות מבוססות צוות עבור רשומות במודול זה. מומלץ גם להשתמש בכלי 'תיקון ובנייה מחדש מהירים' כדי לנקות את מטמון המערכת לאחר השבתת הרשאות מבוססות צוות עבור מודול כלשהו. אם אין לך גישה לכלי 'תיקון ובנייה מחדש מהירים', פנה למנהל מערכת בעל גישה לתפריט 'תיקון'.
STR
,
);
