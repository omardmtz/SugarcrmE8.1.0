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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'עבד משימות וורקפלו',
'LBL_OOTB_REPORTS'		=> 'הרץ יצור דוחות מתוזמן',
'LBL_OOTB_IE'			=> 'בוק תיבות דואר נכנס',
'LBL_OOTB_BOUNCE'		=> 'אבד משימות ריצת דואר יוצא ליליות',
'LBL_OOTB_CAMPAIGN'		=> 'הרץ קמפיין מייל לילי',
'LBL_OOTB_PRUNE'		=> 'קצץ מסד נתונים בראשון לכל חודש',
'LBL_OOTB_TRACKER'		=> 'קצץ טבלאות גששים',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'חתוך רשימות רשומות ישנות',
'LBL_OOTB_REMOVE_TMP_FILES' => 'הסר קבצים זמניים',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'הסר קבצי כלי אבחון',
'LBL_OOTB_REMOVE_PDF_FILES' => 'הסר קבצי PDF זמניים',
'LBL_UPDATE_TRACKER_SESSIONS' => 'עדכן טבלת משימות לגששים',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'הפעל הודעות תזכורת בדוא"ל',
'LBL_OOTB_CLEANUP_QUEUE' => 'נקה תור משרות',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'צור תקופות זמן עתידיות',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'עדכן מאמרים של KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'פרסם מאמרים מאושרים והעבר לפג תוקף מאמרי KB.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'בנה מחדש נתוני אבטחת צוות לא מנורמל',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'מרווחים:',
'LBL_LIST_LIST_ORDER' => 'תיזמונים:',
'LBL_LIST_NAME' => 'תזמן:',
'LBL_LIST_RANGE' => 'טווח:',
'LBL_LIST_REMOVE' => 'הסר:',
'LBL_LIST_STATUS' => 'מצב:',
'LBL_LIST_TITLE' => 'רשימת תיזמונים:',
'LBL_LIST_EXECUTE_TIME' => 'ירוץ ב:',
// human readable:
'LBL_SUN'		=> 'ראשון',
'LBL_MON'		=> 'שני',
'LBL_TUE'		=> 'שלישי',
'LBL_WED'		=> 'רביעי',
'LBL_THU'		=> 'חמישי',
'LBL_FRI'		=> 'שישי',
'LBL_SAT'		=> 'שבת',
'LBL_ALL'		=> 'כל יום',
'LBL_EVERY_DAY'	=> 'כל יום',
'LBL_AT_THE'	=> 'ב',
'LBL_EVERY'		=> 'כל',
'LBL_FROM'		=> 'מ',
'LBL_ON_THE'	=> 'על',
'LBL_RANGE'		=> 'אל',
'LBL_AT' 		=> 'ב',
'LBL_IN'		=> 'בתוך',
'LBL_AND'		=> 'וגם',
'LBL_MINUTES'	=> 'דקות',
'LBL_HOUR'		=> 'שעות',
'LBL_HOUR_SING'	=> 'שעה',
'LBL_MONTH'		=> 'חודש',
'LBL_OFTEN'		=> 'ברגע שיתאפשר.',
'LBL_MIN_MARK'	=> 'יפעל במשך דקות',


// crontabs
'LBL_MINS' => 'דקות',
'LBL_HOURS' => 'שעות',
'LBL_DAY_OF_MONTH' => 'תאריך',
'LBL_MONTHS' => 'חודש',
'LBL_DAY_OF_WEEK' => 'יום',
'LBL_CRONTAB_EXAMPLES' => 'הנ"ל משתמש בסימון crontab רגיל.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'מפרטי ה-cron מופעלים לפי אזור הזמן של השרת (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). אנא ציין את זמן הביצוע של יומן האירועים בהתאמה.',
// Labels
'LBL_ALWAYS' => 'תמיד',
'LBL_CATCH_UP' => 'יבוצע עם פיספס',
'LBL_CATCH_UP_WARNING' => 'בטל סימון אם גוב זה יארך יותר מרגע אחד.',
'LBL_DATE_TIME_END' => 'מסתיים בתאריך ובשעה',
'LBL_DATE_TIME_START' => 'מתחיל בתאריך ובשעה',
'LBL_INTERVAL' => 'מרווח',
'LBL_JOB' => 'גוב',
'LBL_JOB_URL' => 'URL של משרה',
'LBL_LAST_RUN' => 'ריצה מוצלחת אחרונה',
'LBL_MODULE_NAME' => 'שוגר בתמשן של',
'LBL_MODULE_NAME_SINGULAR' => 'מתזמן שוגר',
'LBL_MODULE_TITLE' => 'מתזמנים',
'LBL_NAME' => 'שם הגוב',
'LBL_NEVER' => 'מעולם',
'LBL_NEW_FORM_TITLE' => 'תיזמון חדש',
'LBL_PERENNIAL' => 'תמידי',
'LBL_SEARCH_FORM_TITLE' => 'חפש תיזמון',
'LBL_SCHEDULER' => 'תזמן:',
'LBL_STATUS' => 'מצב',
'LBL_TIME_FROM' => 'פעיל מאז',
'LBL_TIME_TO' => 'פעיל אל',
'LBL_WARN_CURL_TITLE' => 'cURL הזהרת:',
'LBL_WARN_CURL' => 'אזהרה:',
'LBL_WARN_NO_CURL' => 'במערכות זו לא מופעלות/מחוברות ספריות cURL לתוך המודול PHP (--with-curl=/path/to/curl_library). אנא צור קשר עם מנהל המערכת שלך כדי לפתור בעיה זו. ללא פונקציונליות של cURL, יומן האירועים לא יכול לארגן את משרותיו.',
'LBL_BASIC_OPTIONS' => 'מבנה בסיסי',
'LBL_ADV_OPTIONS'		=> 'אפשרויות מתקדמות',
'LBL_TOGGLE_ADV' => 'הצג אפשרויות מתקדמות',
'LBL_TOGGLE_BASIC' => 'הצג אפשרויות בסיסיות',
// Links
'LNK_LIST_SCHEDULER' => 'מתזמנים',
'LNK_NEW_SCHEDULER' => 'צור תיזמון',
'LNK_LIST_SCHEDULED' => 'עבודות מתוזמנות',
// Messages
'SOCK_GREETING' => "This is the interface for SugarCRM Schedulers Service. <br />[ Available daemon commands: start|restart|shutdown|status ]<br />To quit, type &#39;quit&#39;.  To shutdown the service &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'למחיקת תיזמון עליך לספק מספר רשומה.',
'ERR_CRON_SYNTAX' => 'לא תקין Cron סינטקסט',
'NTC_DELETE_CONFIRMATION' => 'אתה בטוח בשברצונך למחוק רשומה זו?',
'NTC_STATUS' => 'הפוך סטאטוס של המשימה לביצוע ללא זמין על מנת למחוק את המשימה מהתפריט הנגלל',
'NTC_LIST_ORDER' => 'קבע את הסדר שבו תופיע משימה זו בתפריט הנגלל',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'לכוון את המתשמן של חלונות',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'לכוון תיזמון Crontab',
'LBL_CRON_LINUX_DESC' => 'Note: In order to run Sugar Schedulers, add the following line to the crontab file:',
'LBL_CRON_WINDOWS_DESC' => 'Note: In order to run the Sugar schedulers, create a batch file to run using Windows Scheduled Tasks. The batch file should include the following commands:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'יומן גובים',
'LBL_EXECUTE_TIME'			=> 'זמן ביצוע',

//jobstrings
'LBL_REFRESHJOBS' => 'רענן גובים',
'LBL_POLLMONITOREDINBOXES' => 'בדוק חשבונות דואר נכנס',
'LBL_PERFORMFULLFTSINDEX' => 'מערכת מפתח לחיפוש טקסט מלא',
'LBL_SUGARJOBREMOVEPDFFILES' => 'הסר קבצי PDF זמניים',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'פרסם מאמרים שאושרו והעבר מאמרי KB לקטגוריית &#39;פג תוקף&#39;.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'מתזמן תורי Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'הסר קבצים של כלי האבחון',
'LBL_SUGARJOBREMOVETMPFILES' => 'הסר קבצים זמניים',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'בנה מחדש נתוני אבטחת צוות לא מנורמל',

'LBL_RUNMASSEMAILCAMPAIGN' => 'הרץ קמפיין דואר אלקטרוני לילי',
'LBL_ASYNCMASSUPDATE' => 'בצע עדכון אסינכרוני מסיבי',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'הרץ תהליך שידור דואר להודעות שחזרו כל לילה',
'LBL_PRUNEDATABASE' => 'קצץ מסד נתונים בראשון לכל חודש',
'LBL_TRIMTRACKER' => 'קצץ טבלאות גששים',
'LBL_PROCESSWORKFLOW' => 'עבד משימות ליליות לוורפלו',
'LBL_PROCESSQUEUE' => 'הרץ מחולל דוחות למשימות מתוזמנות',
'LBL_UPDATETRACKERSESSIONS' => 'עדכן טבלאות גששים',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'צור תקופות זמן עתידיות',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'הפעל שליחת תזכורות בדוא"ל',
'LBL_CLEANJOBQUEUE' => 'ניקוי תור משרות',
'LBL_CLEANOLDRECORDLISTS' => 'נקה רשימת רשומות ישנה',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

