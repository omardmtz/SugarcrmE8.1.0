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
    'LBL_MODULE_NAME' => 'قائمة انتظار المهمة',
    'LBL_MODULE_NAME_SINGULAR' => 'قائمة انتظار المهمة',
    'LBL_MODULE_TITLE' => 'قائمة انتظار المهمة: الرئيسية',
    'LBL_MODULE_ID' => 'قائمة انتظار المهمة',
    'LBL_TARGET_ACTION' => 'الإجراء',
    'LBL_FALLIBLE' => 'عرضة للخطأ',
    'LBL_RERUN' => 'إعادة تشغيل',
    'LBL_INTERFACE' => 'الواجهة',
    'LINK_SCHEDULERSJOBS_LIST' => 'عرض قائمة انتظار المهمة',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'التكوين',
    'LBL_CONFIG_PAGE' => 'إعدادات قائمة انتظار المهام',
    'LBL_JOB_CANCEL_BUTTON' => 'إلغاء',
    'LBL_JOB_PAUSE_BUTTON' => 'إيقاف مؤقت',
    'LBL_JOB_RESUME_BUTTON' => 'استئناف',
    'LBL_JOB_RERUN_BUTTON' => 'قائمة انتظار مجددًا',
    'LBL_LIST_NAME' => 'الاسم',
    'LBL_LIST_ASSIGNED_USER' => 'مطلوب بواسطة',
    'LBL_LIST_STATUS' => 'الحالة',
    'LBL_LIST_RESOLUTION' => 'الحل',
    'LBL_NAME' => 'اسم الوظيفة',
    'LBL_EXECUTE_TIME' => 'زمن التنفيذ',
    'LBL_SCHEDULER_ID' => 'المجدول',
    'LBL_STATUS' => 'حالة الوظيفة',
    'LBL_RESOLUTION' => 'النتيجة',
    'LBL_MESSAGE' => 'الرسائل',
    'LBL_DATA' => 'بيانات الوظيفة',
    'LBL_REQUEUE' => 'إعادة المحاولة عند الفشل',
    'LBL_RETRY_COUNT' => 'الحد الأقصى لعدد إعادة المحاولات',
    'LBL_FAIL_COUNT' => 'مرات الفشل',
    'LBL_INTERVAL' => 'الحد الأدنى للفاصل الزمني بين المحاولات',
    'LBL_CLIENT' => 'العميل المالك',
    'LBL_PERCENT' => 'نسبة الاكتمال',
    'LBL_JOB_GROUP' => 'مجموعة الوظائف',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'الحل بقائمة الانتظار',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'الحل الجزئي',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'الحل كامل',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'فشل الحل',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'تم إلغاء الحل',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'الحل قيد التشغيل',
    // Errors
    'ERR_CALL' => "لا يمكن استدعاء الوظيفة: %s",
    'ERR_CURL' => "بدون CURL - لا يمكن تشغيل وظائف URL",
    'ERR_FAILED' => "حدث عطل غير متوقع، يرجى التحقق من سجلات PHP وsugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s في %s في السطر %d",
    'ERR_NOUSER' => "لم يتم تحديد معرّف مستخدم للوظيفة",
    'ERR_NOSUCHUSER' => "معرّف المستخدم %s غير موجود",
    'ERR_JOBTYPE' => "نوع الوظيفة غير معروف: %s",
    'ERR_TIMEOUT' => "فشل مفروض في المهلة",
    'ERR_JOB_FAILED_VERBOSE' => 'فشلت الوظيفة %1$s (%2$s) في تشغيل CRON.',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'يتعذر تحميل تطبيق باستخدام المعرف: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'يتعذر العثور على المعالج للتوجيه %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'امتداد قائمة الانتظار غير مثبت',
    'ERR_CONFIG_EMPTY_FIELDS' => 'بعض الحقول فارغة',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'إعدادات قائمة انتظار المهام',
    'LBL_CONFIG_MAIN_SECTION' => 'التكوين الأساسي',
    'LBL_CONFIG_GEARMAN_SECTION' => 'تكوين Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'تكوين AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'تكوين Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'تعليمات تكوين قائمة انتظار المهمة',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>قسم التكوين الأساسي.</b></p>
<ul>
    <li>المشغل:
    <ul>
    <li><i>قياسي</i> - استخدم عملية واحدة فقط للعمال.</li>
    <li><i>متوازي</i> - استخدم عمليات قليلة للعمال.</li>
    </ul>
    </li>
    <li>المهايئ:
    <ul>
    <li><i>قائمة الانتظار الافتراضية</i> - سيستخدم قاعدة بيانات Sugar فقط دون أي قائمة انتظار رسالة.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service عبارة عن خدمة رسائل قائمة انتظار موزعة بواسطة Amazon.com.
    إنها تدعم إرسال مبرمج للرسائل عبر تطبيقات خدمة الويب كطريقة للتواصل عبر الإنترنت.</li>
    <li><i>RabbitMQ</i> - عبارة عن برنامج سمسمرة رسائل مفتوحة المصدر (أحيانًا تسمى البرامج الوسيطة الموجهة للرسائل) التي تنفذ بروتوكول قائمة انتظار الرسائل المتقدم (AMQP).</li>
    <li><i>Gearman</i> - عبارة عن إطار عمل تطبيق مفتوح المصدر مصمم لتوزيع مهام الكمبيوتر المناسبة على العديد من أجهزة الكمبيوتر، حتى يمكن تنفيذ مهام أكبر بسرعة أكبر.</li>
    <li><i>فوري</i> - مثل قائمة الانتظار الافتراضية، ولكنه ينفذ المهمة في الحال بعد الإضافة.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'تعليمات تكوين Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>قسم تكوين Amazon SQS.</b></p>
<ul>
    <li>معرف رمز الوصول: <i>أدخل رقم معرف رمز الوصول لـ Amazon SQS</i></li>
    <li>رمز الوصول السري: <i>أدخل رمز الوصول السري لـ Amazon SQS</i></li>
    <li>المنطقة: <i>أدخل منطقة خادم Amazon SQS</i></li>
    <li>اسم قائمة الانتظار: <i>أدخل اسم قائمة انتظار خادم Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'تعليمات تكوين AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>قسم تكوين AMQP.</b></p>
<ul>
    <li>عنوان URL للخادم: <i>أدخل عنوان URL لخادم قائمة انتظار الرسالة.</i></li>
    <li>تسجيل الدخول: <i>أدخل تسجيل دخولك لـ RabbitMQ</i></li>
    <li>كلمة المرور: <i>أدخل كلمة مرور RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'تعليمات تكوين Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>قسم تكوين Gearman.</b></p>
<ul>
    <li>عنوان URL للخادم: <i>أدخل عنوان URL لخادم قائمة انتظار الرسالة.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'المهايئ',
    'LBL_CONFIG_QUEUE_MANAGER' => 'المشغل',
    'LBL_SERVER_URL' => 'عنوان URL للخادم',
    'LBL_LOGIN' => 'تسجيل الدخول',
    'LBL_ACCESS_KEY' => 'معرف رمز الوصول',
    'LBL_REGION' => 'المنطقة',
    'LBL_ACCESS_KEY_SECRET' => 'رمز الوصول السري',
    'LBL_QUEUE_NAME' => 'اسم المهايئ',
);
