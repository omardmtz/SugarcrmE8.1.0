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
    'LBL_MODULE_NAME' => 'תור משרות',
    'LBL_MODULE_NAME_SINGULAR' => 'תור משרות',
    'LBL_MODULE_TITLE' => 'תור משרות: בית',
    'LBL_MODULE_ID' => 'תור משרות',
    'LBL_TARGET_ACTION' => 'פעולה',
    'LBL_FALLIBLE' => 'עלול לטעות',
    'LBL_RERUN' => 'הרץ שוב',
    'LBL_INTERFACE' => 'ממשק',
    'LINK_SCHEDULERSJOBS_LIST' => 'הצג תור משרות',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'תצורה',
    'LBL_CONFIG_PAGE' => 'הגדרות תור משרות',
    'LBL_JOB_CANCEL_BUTTON' => 'בטל',
    'LBL_JOB_PAUSE_BUTTON' => 'עצור',
    'LBL_JOB_RESUME_BUTTON' => 'המשך',
    'LBL_JOB_RERUN_BUTTON' => 'הכנס שוב לתור',
    'LBL_LIST_NAME' => 'שם',
    'LBL_LIST_ASSIGNED_USER' => 'מבוקש על ידי',
    'LBL_LIST_STATUS' => 'מצב',
    'LBL_LIST_RESOLUTION' => 'החלטה',
    'LBL_NAME' => 'שם הגוב',
    'LBL_EXECUTE_TIME' => 'בצע זמן',
    'LBL_SCHEDULER_ID' => 'תזמן:',
    'LBL_STATUS' => 'סטאטוס:',
    'LBL_RESOLUTION' => 'תוצאה',
    'LBL_MESSAGE' => 'הודעות',
    'LBL_DATA' => 'נתוני הגוב',
    'LBL_REQUEUE' => 'נסה שנית בעת כישלון',
    'LBL_RETRY_COUNT' => 'מקסימום ניסיונות',
    'LBL_FAIL_COUNT' => 'כשלונות',
    'LBL_INTERVAL' => 'מינימום זמן המתנה בין ניסיונות',
    'LBL_CLIENT' => 'לקוח שבבעלותו',
    'LBL_PERCENT' => 'שליחה מחדש הושלמה',
    'LBL_JOB_GROUP' => 'עבודת קבוצה',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'החלטה בתור',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'החלטה חלקית',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'החלטה הסתיימה',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'כישלון החלטה',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'החלטה בוטלה',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'החלטה רצה',
    // Errors
    'ERR_CALL' => "לא מצליח לקורא לפונקציה: %s",
    'ERR_CURL' => "אין CURL - לא ניתן להריץ משרות URL",
    'ERR_FAILED' => "כישלון לא צפוי בדוק יומני PHP או היומן של שוגר",
    'ERR_PHP' => "%s [%d]: %s בתוך %s בשורה %d",
    'ERR_NOUSER' => "לא סופק זהוי משתמש לגוב המבוקש",
    'ERR_NOSUCHUSER' => "מזהה משתמש %s לא נמצא",
    'ERR_JOBTYPE' => "סוג משרה לא ידוע: %s",
    'ERR_TIMEOUT' => "הכרח כישלון בעת שיהות",
    'ERR_JOB_FAILED_VERBOSE' => 'משרה %1$s (%2$s) נכשלה בהרצת CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'לא ניתן לטעון bean עם מזהה: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'לא ניתן למצוא מטפל עבור נתיב %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'לא מותקנת הרחבה עבור תור זה',
    'ERR_CONFIG_EMPTY_FIELDS' => 'חלק מהשדות ריקים',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'הגדרות תור משרות',
    'LBL_CONFIG_MAIN_SECTION' => 'תצורה ראשית',
    'LBL_CONFIG_GEARMAN_SECTION' => 'תצורת Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'תצורת AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'תצורת Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'עזרה בתצורת תור משרות',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>אזור תצורה ראשית.</b></p>
<ul>
<li>מריץ:
<ul>
<li><i>רגיל</i> - השתמש בתהליך אחד בלבד עבור עובדים.</li>
<li><i>מקביל</i> - השתמש במספר תהליכים עבור עובדים.</li>
</ul>
</li>
<li>מתאם:
<ul>
<li><i>תור ברירת מחדל</i> - זה ישתמש במסד הנתונים של Sugar בלבד ללא כל תור  הודעות.</li>
<li><i>Amazon SQS</i> - שירות התור  הפשוט של Amazon הוא שירות שליחת הודעות תור מאת Amazon.com. הוא תומך בשליחה תכנותית של הודעות דרך יישומי שירות אינטרנט כדרך לתקשר באינטרנט.</li>
<li><i>RabbitMQ</i> - היא תוכנת תייוך הודעות קוד פתוח (לפעמים נקראת חומרת אמצע מונחית-הודעות) שמיישמת את פרוטוקול תור ההודעות המתקדם (AMQP).</li>
<li><i>Gearman</i> - מסגרת יישום קוד פתוח שנועדה לחלק משימות מחשב מתאימות למחשבים רבים, כך שיהיה ניתן לבצע משימות גדולות מהר יותר.</li>
<li><i>מיידי</i> - בדומה לתור ברירת המחדל אך מבצע משימה באופן מיידי לאחר ההוספה.</li>
</ul>
</li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'עזרה בתצורת Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>אזור תצורה של Amazon SQS
</b></p>
<ul>
<li>מזהה מפתח גישה: <i>הזן את מספר המזהה של מפתח הגישה שלך עבור Amazon SQS</i></li>
<li>מפתח גישה סודי: <i>הזן את מפתח הגישה הסודי שלך עבור Amazon SQS</i></li>
<li>אזור: <i>הזן את האזור של שרת Amazon SQS שלך</i></li>
<li>שם תור: <i>הזן את שם התור של שרת Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'עזרה בתצורת AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>אזור תצורת AMQP.</b></p>
<ul>
<li>URL שרת: <i>הזן את כתובת ה-URL של שרת תור ההודעות שלך.</i></li>
<li>כניסה: <i>הזן את פרטי הכניסה שלך עבור RabbitMQ</i></li>
<li>סיסמה: <i>הזן את הסיסמה שלך עבור RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'עזרה בתצורת Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>אזור תצורת Gearman.
</b></p>
<ul>
<li>URL שרת: <i>הזן את כתובת ה-URL של שרת תור ההודעות שלך.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'מתאם',
    'LBL_CONFIG_QUEUE_MANAGER' => 'מריץ',
    'LBL_SERVER_URL' => 'URL שרת',
    'LBL_LOGIN' => 'כניסה',
    'LBL_ACCESS_KEY' => 'מזהה מפתח גישה',
    'LBL_REGION' => 'אזור',
    'LBL_ACCESS_KEY_SECRET' => 'מפתח גישה סודי',
    'LBL_QUEUE_NAME' => 'שם מתאם',
);
