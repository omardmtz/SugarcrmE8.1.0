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
'LBL_OOTB_WORKFLOW'		=> 'معالجة مهام سير العمل',
'LBL_OOTB_REPORTS'		=> 'تشغيل المهام المجدولة لإنشاء التقارير',
'LBL_OOTB_IE'			=> 'مراجعة صناديق البريد الداخلية',
'LBL_OOTB_BOUNCE'		=> 'تشغيل رسائل البريد الإلكتروني المرتدة الخاصة بحملات العمليات الليلية',
'LBL_OOTB_CAMPAIGN'		=> 'تشغيل حملات البريد الإلكتروني الجماعية ليلاً',
'LBL_OOTB_PRUNE'		=> 'تنقيح قواعد البيانات في الأول من كل شهر',
'LBL_OOTB_TRACKER'		=> 'تنقيح جداول المتعقب',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'تنقيح لوائح السجلات القديمة',
'LBL_OOTB_REMOVE_TMP_FILES' => 'إزالة الملفات المؤقتة',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'إزالة ملفات الأدوات التشخيصية',
'LBL_OOTB_REMOVE_PDF_FILES' => 'إزالة ملفات PDF المؤقتة',
'LBL_UPDATE_TRACKER_SESSIONS' => 'تحديث جدول tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'تشغيل إعلامات تذكير البريد الإلكتروني',
'LBL_OOTB_CLEANUP_QUEUE' => 'مسح قوائم انتظار الوظائف',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'إنشاء فترات زمنية في المستقبل',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'تحديث مقالات KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'انشر المقالات المعتمدة ومقالات KB منتهية الصلاحية.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'وظيفة Advanced Workflow المجدولة',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'إعادة إنشاء بيانات أمان الفريق غير المتوافقة',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'الفاصل الزمني:',
'LBL_LIST_LIST_ORDER' => 'خدمات الجدولة:',
'LBL_LIST_NAME' => 'الجدولة:',
'LBL_LIST_RANGE' => 'النطاق:',
'LBL_LIST_REMOVE' => 'إزالة:',
'LBL_LIST_STATUS' => 'الحالة:',
'LBL_LIST_TITLE' => 'لائحة الجدولة:',
'LBL_LIST_EXECUTE_TIME' => 'ستعمل في:',
// human readable:
'LBL_SUN'		=> 'الأحد',
'LBL_MON'		=> 'الاثنين',
'LBL_TUE'		=> 'الثلاثاء',
'LBL_WED'		=> 'الأربعاء',
'LBL_THU'		=> 'الخميس',
'LBL_FRI'		=> 'الجمعة',
'LBL_SAT'		=> 'السبت',
'LBL_ALL'		=> 'كل يوم',
'LBL_EVERY_DAY'	=> 'كل يوم',
'LBL_AT_THE'	=> 'في',
'LBL_EVERY'		=> 'كل',
'LBL_FROM'		=> 'من',
'LBL_ON_THE'	=> 'في',
'LBL_RANGE'		=> 'إلى',
'LBL_AT' 		=> 'عند',
'LBL_IN'		=> 'في',
'LBL_AND'		=> 'و',
'LBL_MINUTES'	=> 'دقائق',
'LBL_HOUR'		=> 'ساعات',
'LBL_HOUR_SING'	=> 'ساعة',
'LBL_MONTH'		=> 'شهر',
'LBL_OFTEN'		=> 'كلما أمكن.',
'LBL_MIN_MARK'	=> 'علامة دقيقة',


// crontabs
'LBL_MINS' => 'دق',
'LBL_HOURS' => 'ساعة',
'LBL_DAY_OF_MONTH' => 'التاريخ',
'LBL_MONTHS' => 'شهر',
'LBL_DAY_OF_WEEK' => 'يوم',
'LBL_CRONTAB_EXAMPLES' => 'يستخدم ما سبق مجموعة رموز كرون تاب.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'تعمل مواصفات كرون استنادًا إلى المنطقة الزمنية للخادم (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). يرجى تحديد زمن تنفيذ الجدولة وفقًا لذلك.',
// Labels
'LBL_ALWAYS' => 'دومًا',
'LBL_CATCH_UP' => 'التنفيذ في حالة الفقدان',
'LBL_CATCH_UP_WARNING' => 'قم بإلغاء التحديد إذا كانت هذه الوظيفة ستستغرق وقتًا أكبر من لحظة حتى تعمل.',
'LBL_DATE_TIME_END' => 'نهاية الوقت والتاريخ',
'LBL_DATE_TIME_START' => 'بدء الوقت والتاريخ',
'LBL_INTERVAL' => 'الفاصل الزمني',
'LBL_JOB' => 'الوظيفة',
'LBL_JOB_URL' => 'عنوان URL للوظيفة',
'LBL_LAST_RUN' => 'آخر تشغيل ناجح',
'LBL_MODULE_NAME' => 'جدولة Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'جدولة Sugar',
'LBL_MODULE_TITLE' => 'خدمات الجدولة',
'LBL_NAME' => 'اسم الوظيفة',
'LBL_NEVER' => 'مطلقًا',
'LBL_NEW_FORM_TITLE' => 'جدولة جديدة',
'LBL_PERENNIAL' => 'دائمة',
'LBL_SEARCH_FORM_TITLE' => 'البحث عن الجدولة',
'LBL_SCHEDULER' => 'الجدولة:',
'LBL_STATUS' => 'الحالة',
'LBL_TIME_FROM' => 'نشط من',
'LBL_TIME_TO' => 'نشط إلى',
'LBL_WARN_CURL_TITLE' => 'تحذير cURL:',
'LBL_WARN_CURL' => 'تحذير:',
'LBL_WARN_NO_CURL' => 'هذا النظام لا يحتوي على مكتبات cURL ممكنة/مجمعة داخل وحدة PHP (--with-curl=/path/to/curl_library).  يرجى الاتصال بالمسؤول لحل هذه المشكلة.  بدون تشغيل cURL، لا يمكن للجدولة ربط وظائفها.',
'LBL_BASIC_OPTIONS' => 'الإعداد الأساسي',
'LBL_ADV_OPTIONS'		=> 'خيارات متقدمة',
'LBL_TOGGLE_ADV' => 'إظهار الخيارات المتقدمة',
'LBL_TOGGLE_BASIC' => 'عرض الخيارات الأساسية',
// Links
'LNK_LIST_SCHEDULER' => 'خدمات الجدولة',
'LNK_NEW_SCHEDULER' => 'إنشاء جدولة',
'LNK_LIST_SCHEDULED' => 'وظائف مجدولة',
// Messages
'SOCK_GREETING' => "هذه هي واجهة خدمة جدولة SugarCRM. <br />[ الأوامر المتاحة للبرنامج الخفي: ابدأ|إعادة التشغيل|إيقاف التشغيل|الحالة ]<br />للإنهاء، اكتب &#39;إنهاء&#39;.  لإيقاف تشغيل الخدمة &#39;إيقاف التشغيل&#39;.",
'ERR_DELETE_RECORD' => 'يجب عليك تحديد رقم السجل لحذف الجدولة.',
'ERR_CRON_SYNTAX' => 'بناء جملة كرون غير صالح',
'NTC_DELETE_CONFIRMATION' => 'هل تريد بالتأكيد حذف هذا السجل؟',
'NTC_STATUS' => 'قم بتعيين الحالة على "غير نشط" لإزالة هذه الجدولة من القوائم المنسدلة للجدولة',
'NTC_LIST_ORDER' => 'قم بتعيين الترتيب الذي ستظهر به هذه الجدولة في القوائم المنسدلة للجدولة',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'لإعداد جدولة Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'لإعداد كرون تاب',
'LBL_CRON_LINUX_DESC' => 'ملاحظة: لتشغيل خدمات جدولة Sugar، قم بإضافة السطر التالي إلى ملف كرون تاب:',
'LBL_CRON_WINDOWS_DESC' => 'ملاحظة: لتشغيل خدمات جدولة Sugar، قم بإنشاء ملف دفعات لتشغيله باستخدام "المهام المجدولة بـ Windows". ينبغي أن يتضمن ملف الدفعات الأوامر التالية:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'سجل الوظائف',
'LBL_EXECUTE_TIME'			=> 'زمن التنفيذ',

//jobstrings
'LBL_REFRESHJOBS' => 'تحديث الوظائف',
'LBL_POLLMONITOREDINBOXES' => 'التحقق من حسابات البريد الداخلية',
'LBL_PERFORMFULLFTSINDEX' => 'نظام فهرسة البحث عن كامل النص',
'LBL_SUGARJOBREMOVEPDFFILES' => 'إزالة ملفات PDF المؤقتة',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'انشر المقالات المعتمدة ومقالات KB منتهية الصلاحية.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'مجدول قائمة انتظار Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'إزالة ملفات الأدوات التشخيصية',
'LBL_SUGARJOBREMOVETMPFILES' => 'إزالة الملفات المؤقتة',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'إعادة إنشاء بيانات أمان الفريق غير المتوافقة',

'LBL_RUNMASSEMAILCAMPAIGN' => 'تشغيل حملات البريد الإلكتروني الجماعية ليلاً',
'LBL_ASYNCMASSUPDATE' => 'عمل تحديثات عامة متزامنة',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'تشغيل رسائل البريد الإلكتروني المرتدة الخاصة بحملات العمليات الليلية',
'LBL_PRUNEDATABASE' => 'تنقيح قواعد البيانات في الأول من كل شهر',
'LBL_TRIMTRACKER' => 'تنقيح جداول المتعقب',
'LBL_PROCESSWORKFLOW' => 'معالجة مهام سير العمل',
'LBL_PROCESSQUEUE' => 'تشغيل المهام المجدولة لإنشاء التقارير',
'LBL_UPDATETRACKERSESSIONS' => 'تحديث جداول جلسة المتعقب',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'إنشاء فترات زمنية في المستقبل',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'تشغيل إرسال تذكيرات البريد الإلكتروني',
'LBL_CLEANJOBQUEUE' => 'تنظيف قوائم انتظار الوظائف',
'LBL_CLEANOLDRECORDLISTS' => 'تنظيف لوائح السجلات القديمة',
'LBL_PMSEENGINECRON' => 'مجدول Advanced Workflow',
);

