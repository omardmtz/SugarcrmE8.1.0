<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

$mod_strings = array(
	'LBL_BASIC_SEARCH'					=> 'البحث الأساسي',
	'LBL_ADVANCED_SEARCH'				=> 'بحث متقدم',
	'LBL_BASIC_TYPE'					=> 'نوع أساسي',
	'LBL_ADVANCED_TYPE'					=> 'نوع متقدم',
	'LBL_SYSOPTS_1'						=> 'حدد من خيارات تهيئة النظام التالية أدناه.',
    'LBL_SYSOPTS_2'                     => 'ما نوع قاعدة البيانات المراد استخدامها لمثال Sugar الذي توشك أن تقوم بتثبيته؟',
	'LBL_SYSOPTS_CONFIG'				=> 'تهيئة النظام',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> 'حدد نوع قاعدة البيانات',
    'LBL_SYSOPTS_DB_TITLE'              => 'نوع قاعدة البيانات',
	'LBL_SYSOPTS_ERRS_TITLE'			=> 'يُرجى إصلاح الأخطاء التالية قبل المتابعة:',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => 'يُرجى جعل المسار التالي قابلاً للكتابة عليه:',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
    'ERR_DB_IBM_DB2_CONNECT'			=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
    'ERR_DB_IBM_DB2_VERSION'			=> 'إصدار DB2 الخاص بك (%s) غير مدعوم من Sugar.  تحتاج لتثبيت إصدار يتوافق مع تطبيق Sugar.  يُرجى الرجوع إلى مصفوفة التوافقية في ملاحظات بدء التطبيق لإصدارات DB2 المدعومة.',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> 'يتعين أن يكون لديك عميل Oracle مثبت وتمت تهيئته في حالة اختيارك نظام Oracle.',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
	'ERR_DB_OCI8_CONNECT'				=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
	'ERR_DB_OCI8_VERSION'				=> 'إصدار Oracle الخاص بك (%s) غير مدعوم من Sugar.  تحتاج لتثبيت إصدار يتوافق مع تطبيق Sugar.  يُرجى الرجوع إلى مصفوفة التوافق في ملاحظات بدء التطبيق لإصدارات Oracle المدعومة.',
    'LBL_DBCONFIG_ORACLE'               => 'يُرجى إدخال اسم قاعدة البيانات الخاصة بك.  وسيكون ذلك مساحة الجدول الافتراضية المحددة لمستخدمك ((SID from tnsnames.ora).',
	// seed Ent Reports
	'LBL_Q'								=> 'استعلام الفرصة ',
	'LBL_Q1_DESC'						=> 'الفرص حسب النوع',
	'LBL_Q2_DESC'						=> 'الفرص حسب الحساب',
	'LBL_R1'							=> 'تقرير مبيعات خطوط العائدات عن 6 أشهر',
	'LBL_R1_DESC'						=> 'الفرص المتاحة خلال الـ 6 شهور القادمة موزعة حسب الشهر والنوع',
	'LBL_OPP'							=> 'ضبط بيانات الفرص ',
	'LBL_OPP1_DESC'						=> 'وهذا هو المكان حيث يمكنك تغيير مظهر وإحساس الاستعلام المعتاد',
	'LBL_OPP2_DESC'						=> 'سيتكدس هذا الاستعلام أسفل الاستعلام الأول في التقرير',
    'ERR_DB_VERSION_FAILURE'			=> 'تعذر فحص إصدار قاعدة البيانات.',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => 'أدخل اسم المستخدم بالنسبة لمستخدم مسؤول Sugar . ',
	'ERR_ADMIN_PASS_BLANK'				=> 'أدخل كلمة المرور بالنسبة لمستخدم مسؤول Sugar . ',

    'ERR_CHECKSYS'                      => 'تم اكتشاف أخطاء أثناء فحص التوافقية.  لكى يعمل تثبيت SugarCRM بكفاءة، يُرجى اتخاذ الخطوات المناسبة لمواجهة المسائل المذكورة أدناه إما بالضغط على زر إعادة الفحص، وإما بإعادة محاولة التثبيت مرة أخرى.',
    'ERR_CHECKSYS_CALL_TIME'            => 'السماح بتشغيل وضع "مرجعية مرور وقت الاتصال (ويجب ضبطها على وضع إيقاف التشغيل في php.ini)',

	'ERR_CHECKSYS_CURL'					=> 'غير موجود: سيتم تشغيل أداة جدولة Sugar بوظائف محدودة. لن يتم تشغيل خدمة أرشفة البريد الإلكتروني.',
    'ERR_CHECKSYS_IMAP'					=> 'لم يتم العثور عليه: تتطلب InboundEmail وCampaigns (Email) مكتبات IMAP. ولن يعمل أي منها.',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'لا يمكن تشغيل Magic Quotes GPC على وضع "تشغيل" عند استخدام خادم MS SQL.',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> 'تحذير: ',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> ' (ضبط هذا على ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'متوسط أو أكبر في ملف php.ini)',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> 'أقل إصدار 4.1.2 - تم العثور عليه: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'فشل كتابة وقراءة متغيرات الجلسة.  تعذرت متابعة التثبيت.',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> 'مسار غير صالح',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> 'تحذير: غير قابل للكتابة عليه',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'إصدار PHP الخاص بك غير مدعوم من Sugar.  تحتاج لتثبيت إصدار يتوافق مع تطبيق Sugar.  يُرجى الرجوع إلى "مصفوفة التوافق" في ملاحظات الإصدار الخاصة بإصدارات PHP المعتمدة. الإصدار الخاص بك ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'إصدار IIS الخاص بك غير مدعوم من Sugar.  تحتاج لتثبيت إصدار يتوافق مع تطبيق Sugar.  يُرجى الرجوع إلى مصفوفة التوافقية في ملاحظات بدء التطبيق لإصدارات IIS المدعومة. الإصدار الخاص بك ',
	'ERR_CHECKSYS_FASTCGI'              => 'اكتشفنا أنك لا تستخدم خريطة المتعامل مع FastCGI بالنسبة لـ PHP. تحتاج إلى تثبيت/تهيئة إصدار متوافق مع برنامج Sugar.  يُرجى الرجوع إلى مصفوفة التوافقية في ملاحظات بدء التطبيق للإصدارات المدعومة. يُرجى مراجعة <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a> لمزيد من التفاصيل ',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => 'للحصول على أفضل تجربة باستخدام IIS/FastCGI sapi، اضبط دخول fastcgi.logging على 0 في ملف php.ini الخاص بك.',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'إصدار PHP مثبت غير مدعوم: (إصدار',
    'LBL_DB_UNAVAILABLE'                => 'قاعدة البيانات غير متاحة',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => 'لم يتم العثور على دعم قاعدة البيانات. الرجاء التأكد من أنك تملك ملفات التعريف الضرورية لأحد انواع قواعد البيانات المدعومة التالية: MySQL أو MS SQLServer أو Oracle أو DB2. قد تحتاج إلى إلغاء تعليق الامتداد في ملف php.ini أو إعادة تكوينه بالملف الثنائي الصحيح، بحسب إصدار PHP الخاص بك. الرجاء الرجوع إلى دليل PHP لمزيد من المعلومات حول كيفية تمكين دعم قاعدة البيانات.',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => 'لم يتم العثور على الوظائف المرتبطة بمكتبات XML Parser Libraries الضرورية لتطبيق Sugar.  قد تحتاج إلى عدم التعليق على الامتداد في ملف php.ini، أو إعادة تجميع مع ملف ثنائي صحيح، وفقًا لإصدار PHP الخاص بك.  يُرجى مراجعة دليل PHP للحصول على مزيد من المعلومات.',
    'LBL_CHECKSYS_CSPRNG' => 'منشئ الأرقام العشوائية',
    'ERR_CHECKSYS_MBSTRING'             => 'لم يتم العثور على الوظائف المرتبطة بامتداد Multibyte Strings PHP (mbstring) الضرورية لتطبيق Sugar. <br/><br/>وبشكل عام، لم يتم تفعيل وحدة mbstring افتراضيًا في PHP ويجب تفعيلها باستخدام تفعيل mbstring عند إنشاء ثنائي PHP. يُرجى الرجوع لدليل PHP للحصول على مزيد من المعلومات عن كيفية تفعيل دعم mbstring.',
    'ERR_CHECKSYS_MCRYPT'               => "Mcrypt module isn't loaded. Please refer to your PHP Manual for more information on how to load mcrypt module.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'لم يتم تعيين إعداد save_path في ملف تهيئة php الخاص بك (php.ini) أو ضبطه على مجلد غير موجود. قد تحتاج إلى ضبط إعداد save_path في php.ini أو التحقق من وجود المجلد المعين في save_path.',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'تم تعيين إعداد save_path في ملف تهيئة php الخاص بك (php.ini) أو ضبطه على مجلد غير قابل للكتابة عليه.  يُرجى اتخاذ الخطوات اللازمة لجعل المجلد قابلاً للكتابة عليه.  <br>وفقًا لنظام التشغيل الخاص بك، قد يحتاج منك هذا تغيير الأذونات بتشغيل chmod 766، أو النقر بزر الماوس الأيمن على اسم الملف للدخول على خصائص وإزالة علامة اختيار خيار للقراءة فقط.',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => 'يوجد ملف التهيئة ولكنه غير قابل للكتابة عليه.  يُرجى اتخاذ الخطوات اللازمة لجعل الملف قابلاً للكتابة عليه.  وفقًا لنظام التشغيل الخاص بك، قد يحتاج منك هذا تغيير الأذونات بتشغيل chmod 766، أو النقر بزر الماوس الأيمن على اسم الملف للدخول على خصائص وإزالة علامة اختيار خيار للقراءة فقط.',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => 'تتجاوز التهيئة للملف الموجود ولكن ملف التهيئة غير قابل للكتابة عليه.  يُرجى اتخاذ الخطوات اللازمة لجعل الملف قابلاً للكتابة عليه.  وفقًا لنظام التشغيل الخاص بك، قد يحتاج منك هذا تغيير الأذونات بتشغيل chmod 766، أو النقر بزر الماوس الأيمن على اسم الملف للدخول على خصائص وإزالة علامة اختيار خيار للقراءة فقط.',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => 'يوجد دليل مخصص ولكنه غير قابل للكتابة عليه.  قد يتعين عليك تغيير الأذونات عليه (chmod 766) أو النقر بزر الماوس الأيمن عليه وإلغاء اختيار خيار للقراءة فقط، وفقًا لنظام التشغيل الخاص بك.  يُرجى اتخاذ الخطوات اللازمة لجعل الملف قابلاً للكتابة عليه.',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "الملفات أو الأدلة المذكورة أدناه غير قابلة للكتابة عليها أو مفقودة ويتعذر إنشاؤها.  وفقًا لنظام التشغيل الخاص بك، قد يحتاج تصحيح هذا تغيير الأذونات على الملفات أو الدليل الرئيسي (chmod 755)، أو النقر بزر الماوس الأيمن على الدليل الرئيسي وإلغاء اختيار خيار \"للقراءة فقط\" وتطبيقه على جميع المجلدات الفرعية.",
	'ERR_CHECKSYS_SAFE_MODE'			=> 'وضع الأمان قيد التشغيل "On" (قد ترغب في تعطيل في ملف php.ini)',
    'ERR_CHECKSYS_ZLIB'					=> 'لم يتم العثور على دعم ZLib: يجني SugarCRM مزايا هائلة في الأداء باستخدام ضغط zlib.',
    'ERR_CHECKSYS_ZIP'					=> 'لم يتم العثور على دعم ZIP: يحتاج SugarCRM لدعم ZIP لتنفيذ الملفات المضغوطة.',
    'ERR_CHECKSYS_BCMATH'				=> 'لم يتم العثور على دعم BCMATH: يحتاج SugarCRM لدعم BCMATH بالنسبة لدقة العمليات الرياضية الإجبارية.',
    'ERR_CHECKSYS_HTACCESS'             => 'فشل اختبار إعادة الكتابة على htaccess . وهذا يعني أحيانًا أنه ليس لديك إعداد AllowOverride (السماح بالتجاوز) بالنسبة لدليل Sugar.',
    'ERR_CHECKSYS_CSPRNG' => 'استثناء CSPRNG',
	'ERR_DB_ADMIN'						=> 'اسم المستخدم و/أو كلمة مرور المسؤول لقاعدة البيانات المتاحة غير صالحة، وتعذر تأسيس الاتصال بقاعدة البيانات.  يُرجى إدخال اسم مستخدم وكلمة مرور صالحة.  (خطأ: ',
    'ERR_DB_ADMIN_MSSQL'                => 'اسم المستخدم و/أو كلمة مرور المسؤول لقاعدة البيانات المتاحة غير صالحة، وتعذر تأسيس الاتصال بقاعدة البيانات.  يُرجى إدخال اسم مستخدم وكلمة مرور صالحة.',
	'ERR_DB_EXISTS_NOT'					=> 'قاعدة البيانات المحددة غير موجودة.',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'قاعدة البيانات موجودة بالفعل مزودة ببيانات التهيئة.  لتشغيل عملية تثبيت باستخدام قاعدة البيانات المختارة، يُرجى إعادة تشغيل عملية التثبيت واختيار: "هل تريد إسقاط جداول SugarCRM الموجودة وإعادة إنشائها؟"  للتحديث، استخدم نافذة تحديث في وحدة تحكم المسؤول.  يُرجى قراءة مستند التحديث الموجود <a href="http://www.sugarforge.org/content/downloads/" target="_new">هنا</a>.',
	'ERR_DB_EXISTS'						=> 'اسم قاعدة البيانات المدخل موجود بالفعل -- ويتعذر إنشاء آخر بالاسم نفسه.',
    'ERR_DB_EXISTS_PROCEED'             => 'اسم قاعدة البيانات المدخل موجود بالفعل.  يمكنك الضغط على <br>1.  الزر السابق وتحديد اسم قاعدة بيانات جديد <br>2.  انقر التالي واستمر ولكن سيتم إسقاط جميع الجداول الموجودة في قاعدة البيانات هذه.  <strong>وهذا يعني أنه سيتم التخلص من جداولك وبياناتك.</strong>',
	'ERR_DB_HOSTNAME'					=> 'لا يمكن ترك اسم المضيف فارغًا.',
	'ERR_DB_INVALID'					=> 'تم اختيار نوع قاعدة بيانات غير صالح.',
	'ERR_DB_LOGIN_FAILURE'				=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'مضيف قاعدة البيانات، واسم المستخدم، و/أو كلمة المرور المقدمة غير صالحة، وتعذر إنشاء اتصال مع قاعدة البيانات.  يُرجى إدخال مضيف، واسم المستخدم، وكلمة مرور صالحة',
	'ERR_DB_MYSQL_VERSION'				=> 'إصدار MySQL الخاص بك (%s) غير مدعوم من Sugar.  تحتاج لتثبيت إصدار يتوافق مع تطبيق Sugar.  يُرجى الرجوع إلى مصفوفة التوافقية في ملاحظات بدء التطبيق لإصدارات MySQL مدعومة.',
	'ERR_DB_NAME'						=> 'لا يمكن ترك اسم قاعدة البيانات فارغًا.',
	'ERR_DB_NAME2'						=> "لا يمكن احتواء اسم قاعدة البيانات على '\\'، أو '/'، أو '.'",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "لا يمكن احتواء اسم قاعدة البيانات على '\\'، أو '/'، أو '.'",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "لا يمكن بدء اسم قاعدة البيانات برقم، أو '#'، أو '@' ولا يمكن أن يحوي مسافة، أو '\"'، أو \"'\"، أو '*'، أو '/'، أو '\\'، '?'، أو ':'، أو '<'، أو '>'، أو '&'، أو '!'، أو '-'",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "اسم قاعدة البيانات يمكن أن يتألف فقط من حروف أبجدية رقمية ورموز '#'، أو '_'، أو '-'، أو ':'، أو '.'، أو '/'، أو '$'",
	'ERR_DB_PASSWORD'					=> 'عدم تطابق كلمات المرور المتوفرة لمسؤول قاعدة بيانات Sugar.  يُرجى إعادة إدخال كلمات المرور نفسها في حقول كلمة المرور.',
	'ERR_DB_PRIV_USER'					=> 'أدخل اسم مستخدم مسؤول قاعدة البيانات.  المستخدم مطلوب للاتصال المبدئي بقاعدة البيانات.',
	'ERR_DB_USER_EXISTS'				=> 'اسم مستخدم قاعدة بيانات Sugar موجود بالفعل -- ويتعذر إنشاء آخر بالاسم نفسه. يُرجى إدخال اسم مستخدم جديد.',
	'ERR_DB_USER'						=> 'أدخل اسم مستخدم لمسؤول قاعدة بيانات Sugar.',
	'ERR_DBCONF_VALIDATION'				=> 'يُرجى إصلاح الأخطاء التالية قبل المتابعة:',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => 'عدم تطابق كلمات المرور المتوفرة لمستخدم قاعدة بيانات Sugar. يُرجى إعادة إدخال كلمات المرور نفسها في حقول كلمة المرور.',
	'ERR_ERROR_GENERAL'					=> 'ظهرت الأخطاء التالية:',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'تعذر حذف ملف: ',
	'ERR_LANG_MISSING_FILE'				=> 'تعذر العثور على ملف: ',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'لم يتم العثور على ملف حزمة اللغة في "احتواء/لغة" داخل: ',
	'ERR_LANG_UPLOAD_1'					=> 'هناك مشكلة مع نظام التحميل لديك.  يُرجى المحاولة مرة أخرى.',
	'ERR_LANG_UPLOAD_2'					=> 'يجب أن تكون حزم اللغات مؤرشفة على شكل ملفات ZIP.',
	'ERR_LANG_UPLOAD_3'					=> 'لا يمكن لـ PHP نقل ملف temp للدليل المحدث.',
	'ERR_LICENSE_MISSING'				=> 'حقول مطلوبة مفقودة',
	'ERR_LICENSE_NOT_FOUND'				=> 'لم يتم العثور على ملف الترخيص!',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> 'دليل السجل المرفق ليس دليلاً صالحًا.',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> 'دليل السجل المرفق غير قابل للكتابة عليه.',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'مطلوب دليل السجل إذا كنت ترغب في تحديد الدليل الخاص بك.',
	'ERR_NO_DIRECT_SCRIPT'				=> 'تعذر تشغيل النصوص مباشرةً.',
	'ERR_NO_SINGLE_QUOTE'				=> 'تعذر استخدام علامة اقتباس وحيدة من أجل ',
	'ERR_PASSWORD_MISMATCH'				=> 'عدم تطابق كلمات المرور المتوفرة لمستخدم مسؤول Sugar.  يُرجى إعادة إدخال كلمات المرور نفسها في حقول كلمة المرور.',
	'ERR_PERFORM_CONFIG_PHP_1'			=> 'تعذرت الكتابة على ملف <span class=stop>config.php</span> .',
	'ERR_PERFORM_CONFIG_PHP_2'			=> 'يمكنك متابعة هذا التثبيت بإنشاء ملف config.php يدويًا ولصق معلومات التهيئة أدناه داخل ملف config.php.  ومع ذلك، <strong>يتعين عليك </strong>إنشاء ملف config.php قبل متابعة الخطوة التالية.',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'هل تذكرت إنشاء ملف config.php؟',
	'ERR_PERFORM_CONFIG_PHP_4'			=> 'تحذير: تعذرت الكتابة على ملف config.php.  يُرجى التأكيد على وجوده.',
	'ERR_PERFORM_HTACCESS_1'			=> 'تعذرت الكتابة إلى ',
	'ERR_PERFORM_HTACCESS_2'			=> ' ملف.',
	'ERR_PERFORM_HTACCESS_3'			=> 'إذا كنت ترغب في تأمين ملف الدخول الخاص بك المسموح بالدخول إليه من خلال المتصفح، فأنشئ ملف htaccess في دليل الدخول باستخدام الخط:',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>لم نكتشف أي اتصال بالإنترنت.</b> عند وجود اتصال، يُرجى زيارة الموقع <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a> للتسجيل في SugarCRM. بالسماح لنا بمعرفة القليل عن كيف تخطط شركتك لاستخدام SugarCRM، يمكننا ضمان تسليمنا التطبيق الصحيح دائمًا لتلبية احتياجات عملك.',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> 'دليل الجلسة المرفق ليس دليلاً صالحًا.',
	'ERR_SESSION_DIRECTORY'				=> 'دليل الجلسة المرفق غير قابل للكتابة عليه.',
	'ERR_SESSION_PATH'					=> 'مطلوب مسار للجلسة إذا كنت ترغب في تحديد الدليل الخاص بك.',
	'ERR_SI_NO_CONFIG'					=> 'لم تحوِ ملف config_si.php في أصل المستند، أو لم تقم بتعريف $sugar_config_si في config.php',
	'ERR_SITE_GUID'						=> 'مطلوب معرف التطبيق إذا كنت ترغب في تحديد الدليل الخاص بك.',
    'ERROR_SPRITE_SUPPORT'              => "لا نقدر حاليًا على تحديد موقع مكتبة GD، ونتيجة لذلك يتعذر علينا استخدام وظيفة CSS Sprite.",
	'ERR_UPLOAD_MAX_FILESIZE'			=> 'تحذير: يتعين تغيير تهيئة PHP الخاصة بك للسماح لتحميل الملفات التي تبلغ مساحتها 6 ميجابايت على الأقل.',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => 'حجم ملف التحميل',
	'ERR_URL_BLANK'						=> 'أدخل عنوان القاعدة بالنسبة لمثال Sugar.',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'تعذر تحديد موقع سجل التثبيت لـ',
    'ERROR_FLAVOR_INCOMPATIBLE'         => 'الملف الذي تم تحميله غير متوافق مع هذه الإصدارات (Professional أو Enterprise أو Ultimate edition) من Sugar: ',
	'ERROR_LICENSE_EXPIRED'				=> "خطأ: انتهت صلاحية الترخيص الخاصة بك",
	'ERROR_LICENSE_EXPIRED2'			=> " منذ يوم (أيام).   يُرجى الذهاب إلى <a href='index.php?action=LicenseSettings&module=Administration'>'\"إدارة التراخيص\"</a> في شاشة المسؤول لإدخال مفتاح الترخيص الجديد الخاص بك.  إذا لم تقم بإدخال مفتاح الترخيص الجديد الخاص بك في غضون 30 يومًا من تاريخ صلاحية مفتاح الترخيص الخاص بك، فلن تكون قادرًا بأي حال من الأحوال على تسجيل الدخول لهذا التطبيق.",
	'ERROR_MANIFEST_TYPE'				=> 'يجب أن يحدد ملف البيان Manifest نوع الحزمة.',
	'ERROR_PACKAGE_TYPE'				=> 'يحدد ملف البيان Manifest نوع حزمة غير معروف',
	'ERROR_VALIDATION_EXPIRED'			=> "خطأ: انتهت صلاحية مفتاح التحقق الخاص بك",
	'ERROR_VALIDATION_EXPIRED2'			=> " منذ يوم (أيام).   يُرجى الذهاب إلى <a href='index.php?action=LicenseSettings&module=Administration'>'\"إدارة التراخيص\"</a> في شاشة الإدارة لإدخال مفتاح التحقق الجديد الخاص بك.  إذا لم تقم بإدخال مفتاح التحقق الجديد الخاص بك في غضون 30 يومًا من تاريخ صلاحية مفتاح الصلاحية الخاص بك، فلن تكون قادرًا بأي حال من الأحوال على تسجيل الدخول لهذا التطبيق.",
	'ERROR_VERSION_INCOMPATIBLE'		=> 'الملف الذي تم تحميله غير متوافق مع هذا الإصدار من Sugar:',

	'LBL_BACK'							=> 'للخلف',
    'LBL_CANCEL'                        => 'إلغاء',
    'LBL_ACCEPT'                        => 'قبول',
	'LBL_CHECKSYS_1'					=> 'لكي يعمل تثبيت SugarCRM الخاص بك بكفاءة، يُرجى التأكد من أن كل عناصر التحقق من النظام باللون الأخضر. إذا كان أي منها باللون الأحمر، فيُرجى اتخاذ الخطوات اللازمة لإصلاحها.<BR><BR> للمساعدة في عمليات فحص النظام، يُرجى زيارة موقع <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>.',
	'LBL_CHECKSYS_CACHE'				=> 'أدلة Cache Sub الفرعية القابلة للكتابة عليها',
    'LBL_DROP_DB_CONFIRM'               => 'اسم قاعدة البيانات المرفق موجود بالفعل.<br>يمكنك:<br>1.  النقر فوق زر إلغاء وتحديد اسم قاعدة بيانات جديد، أو <br>2.  انقر فوق "قبول" للمتابعة.  سيتم إسقاط كل الجداول الموجودة في قاعدة البيانات. <strong>وهذا يعني أنه سيتم التخلص من جداولك وبياناتك الموجودة سابقًا.</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'يتيح PHP إيقاف تشغيل Call Time Pass Reference',
    'LBL_CHECKSYS_COMPONENT'			=> 'مكون',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'مكونات اختيارية',
	'LBL_CHECKSYS_CONFIG'				=> 'ملف تهيئة SugarCRM قابل للكتابة عليه (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> 'ملف تهيئة SugarCRM قابل للكتابة عليه (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'وحدة cURL',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => 'إعداد جلسة Save Path',
	'LBL_CHECKSYS_CUSTOM'				=> 'دليل مخصص قابل للكتابة عليه',
	'LBL_CHECKSYS_DATA'					=> 'أدلة فرعية قابلة للكتابة عليها',
	'LBL_CHECKSYS_IMAP'					=> 'وحدة IMAP',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'وحدة MB Strings',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt Module',
	'LBL_CHECKSYS_MEM_OK'				=> 'موافق (بلا حدود)',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'موافق (بلا حدود)',
	'LBL_CHECKSYS_MEM'					=> 'حد ذاكرة PHP',
	'LBL_CHECKSYS_MODULE'				=> 'أدلة وملفات فرعية لوحدات قابلة للكتابة عليها',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'إصدار MySQL',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> 'غير متاح',
	'LBL_CHECKSYS_OK'					=> 'موافق',
	'LBL_CHECKSYS_PHP_INI'				=> 'موقع ملف تهيئة PHP الخاص بك (php.ini):',
	'LBL_CHECKSYS_PHP_OK'				=> 'موافق (إص ',
	'LBL_CHECKSYS_PHPVER'				=> 'إصدار PHP',
    'LBL_CHECKSYS_IISVER'               => 'إصدار IIS',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> 'إعادة الفحص',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'إيقاف تشغيل وضع أمان PHP',
	'LBL_CHECKSYS_SESSION'				=> 'مسار جلسة الحفظ قابل للكتابة عليه (',
	'LBL_CHECKSYS_STATUS'				=> 'الحالة',
	'LBL_CHECKSYS_TITLE'				=> 'قبول فحص النظام',
	'LBL_CHECKSYS_VER'					=> 'تم العثور على: (إصدار ',
	'LBL_CHECKSYS_XML'					=> 'توزيع XML',
	'LBL_CHECKSYS_ZLIB'					=> 'وحدة ضغط ZLIB',
	'LBL_CHECKSYS_ZIP'					=> 'وحدة التعامل مع ZIP',
    'LBL_CHECKSYS_BCMATH'				=> 'وحدة دقة العمليات الرياضية الإجبارية',
    'LBL_CHECKSYS_HTACCESS'				=> 'إعداد السماح بالتجاوز بالنسبة لـ htaccess',
    'LBL_CHECKSYS_FIX_FILES'            => 'يُرجى إصلاح الملفات أو الأدلة التالية قبل المتابعة:',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => 'يُرجى إصلاح أدلة الوحدة التالية والملفات التي تقع تحتها قبل المتابعة:',
    'LBL_CHECKSYS_UPLOAD'               => 'دليل تحميل قابل للكتابة عليه',
    'LBL_CLOSE'							=> 'إغلاق',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> 'تم إنشاؤها',
	'LBL_CONFIRM_DB_TYPE'				=> 'نوع قاعدة البيانات',
	'LBL_CONFIRM_DIRECTIONS'			=> 'يُرجى تأكيد الإعدادات أدناه.  إذا كنت ترغب في تغيير أي من القيم، فانقر فوق "العودة" للتعديل.  وإلا، فانقر فوق "التالي" لبدء التثبيت.',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'بيانات الترخيص',
	'LBL_CONFIRM_NOT'					=> 'بلا',
	'LBL_CONFIRM_TITLE'					=> 'تأكيد الإعدادات',
	'LBL_CONFIRM_WILL'					=> 'سوف',
	'LBL_DBCONF_CREATE_DB'				=> 'إنشاء قاعدة بيانات',
	'LBL_DBCONF_CREATE_USER'			=> 'إنشاء مستخدم',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> 'تنبيه: يتم مسح كل بيانات Sugar <br>في حالة وضع علامة اختيار على هذا المربع.',
	'LBL_DBCONF_DB_DROP_CREATE'			=> 'أسقط وأعد إنشاء جداول Sugar الموجودة؟',
    'LBL_DBCONF_DB_DROP'                => 'أسقط جداول',
    'LBL_DBCONF_DB_NAME'				=> 'اسم قاعدة البيانات',
	'LBL_DBCONF_DB_PASSWORD'			=> 'كلمة مرور مستخدم قاعدة بيانات Sugar',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'أعد إدخال كلمة مرور مستخدم قاعدة بيانات Sugar',
	'LBL_DBCONF_DB_USER'				=> 'اسم مستخدم قاعدة بيانات Sugar',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'اسم مستخدم قاعدة بيانات Sugar',
    'LBL_DBCONF_DB_ADMIN_USER'          => 'اسم مستخدم مسؤول قاعدة البيانات',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => 'كلمة مرور مسؤول قاعدة البيانات',
	'LBL_DBCONF_DEMO_DATA'				=> 'قم بنشر قاعدة البيانات ببيانات تجريبية؟',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => 'حدد بيانات تجريبية',
	'LBL_DBCONF_HOST_NAME'				=> 'اسم المضيف',
	'LBL_DBCONF_HOST_INSTANCE'			=> 'مثال لمضيف',
	'LBL_DBCONF_HOST_PORT'				=> 'المنفذ',
    'LBL_DBCONF_SSL_ENABLED'            => 'تمكين اتصال SSL',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'يُرجى إدخال بيانات تهيئة قاعدة البيانات الخاصة بك أدناه. إذا كنت غير متأكد مما أدخلته، فإننا نقترح استخدام القيم الافتراضية.',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'استخدم نصوصًا متعددة البايت في البيانات التجريبية؟',
    'LBL_DBCONFIG_MSG2'                 => 'اسم خادم الموقع أو الماكينة (المضيف) حيث تقع قاعدة البيانات (مثل المضيف المحلي أو www.mydomain.com):',
    'LBL_DBCONFIG_MSG3'                 => 'اسم قاعدة البيانات الذي يتضمن بيانات مثال Sugar المراد تثبيته:',
    'LBL_DBCONFIG_B_MSG1'               => 'يعتبر اسم المستخدم وكلمة المرور لمسؤول قاعدة بيانات يمكنه إنشاء جداول قاعدة بيانات ومستخدمين ويمكنه الكتابة لقاعدة بيانات ضروريًا من أجل إعداد قاعدة بيانات Sugar.',
    'LBL_DBCONFIG_SECURITY'             => 'بالنسبة لأغراض الأمان، يمكنك تحديد مستخدم قاعدة بيانات حصرية للاتصال بقاعدة بيانات Sugar.  يجب أن يكون هذا المستخدم قادرًا على كتابة البيانات، وتحديثها، واستعادتها على قاعدة البيانات Sugar التي سيتم إنشاؤها لهذا المثال.  يمكن أن يكون هذا المستخدم مسؤولاً لقاعدة البيانات كما هو محدد أعلاه، أو يمكنك إدخال بيانات مستخدم جديدة أو موجودة لقاعدة بيانات.',
    'LBL_DBCONFIG_AUTO_DD'              => 'افعله من أجلي',
    'LBL_DBCONFIG_PROVIDE_DD'           => 'أدخل مستخدمًا موجودًا',
    'LBL_DBCONFIG_CREATE_DD'            => 'قم بتعريف مستخدم لإنشاء',
    'LBL_DBCONFIG_SAME_DD'              => 'تمامًا كالمستخدم المسؤول',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => 'بحث عن النص بالكامل',
    'LBL_FTS_INSTALLED'                 => 'تم التثبيت',
    'LBL_FTS_INSTALLED_ERR1'            => 'لم يتم تثبيت قدرة البحث عن نص بالكامل.',
    'LBL_FTS_INSTALLED_ERR2'            => 'لا يزال يمكنك التثبيت ولكنك لن تكون قادرًا على استخدام وظيفة البحث عن نص كامل.  يُرجى الرجوع إلى دليل تثبيت خادم قاعدة بياناتك في كيفية القيام بذلك، أو الاتصال بالمسؤول.',
	'LBL_DBCONF_PRIV_PASS'				=> 'كلمة مرور قاعدة البيانات الممنوحة',
	'LBL_DBCONF_PRIV_USER_2'			=> 'هل حساب قاعدة البيانات أعلاه امتياز ممنوح للمستخدم؟',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'يجب أن يكون لدى مستخدم قاعدة البيانات الذي له هذا الامتياز الأذونات المناسبة لإنشاء قاعدة بيانات، وإسقاط/إنشاء جداول، وإنشاء مستخدم.  هذا المستخدم لقاعدة البيانات سيتم استخدامه فقط للقيام بتلك المهام عند الحاجة أثناء عملية التثبيت.  قد تستخدم أيضًا مستخدم قاعدة البيانات نفسه البيانات كما هو موضح أعلاه حيث يمتلك المستخدم الامتيازات الكافية.',
	'LBL_DBCONF_PRIV_USER'				=> 'اسم مستخدم قاعدة البيانات صاحب الامتيازات',
	'LBL_DBCONF_TITLE'					=> 'تهيئة قاعدة البيانات',
    'LBL_DBCONF_TITLE_NAME'             => 'اسم قاعدة البيانات المُدخل',
    'LBL_DBCONF_TITLE_USER_INFO'        => 'بيانات مستخدم قاعدة البيانات المُدخل',
	'LBL_DISABLED_DESCRIPTION_2'		=> 'بعد القيام بهذا التعديل، قد تنقر زر "البدء" أدناه لبدء التثبيت.  <i>بعد الانتهاء من التثبيت، ستحتاج إلى تغيير قيمة \'installer_locked\' إلى \'صحيح\'.</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'يقوم هذا المثبت بالعمل مرة واحدة.  وكإجراء أمان، تم تعطيله عن التشغيل لمرة ثانية.  إذا كنت متأكدًا من تشغيله مرة أخرى، فيُرجى الذهاب إلى ملف config.php الخاص بك وتحديد موقع (أو إضافة) متغير يُسمى \'installer_locked\\ وضبطه على \'خطأ\'.  يتعين أن يبدو الخط هكذا:',
	'LBL_DISABLED_HELP_1'				=> 'لمزيد من المساعدة بخصوص التثبيت، يُرجى زيارة SugarCRM',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> 'منتديات الدعم',
	'LBL_DISABLED_TITLE_2'				=> 'تم تعطيل تثبيت SugarCRM',
	'LBL_DISABLED_TITLE'				=> 'تم تعطيل تثبيت SugarCRM',
	'LBL_EMAIL_CHARSET_DESC'			=> 'استخدام تعيين الحروف الأكثر شيوعًا في منطقتك',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'إعدادات البريد الإلكتروني الصادر',
    'LBL_EMAIL_CHARSET_CONF'            => 'تعيين الحروف للبريد الإلكتروني الصادر ',
	'LBL_HELP'							=> 'تعليمات',
    'LBL_INSTALL'                       => 'تثبيت',
    'LBL_INSTALL_TYPE_TITLE'            => 'خيارات التثبيت',
    'LBL_INSTALL_TYPE_SUBTITLE'         => 'اختر نوع التثبيت',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>التثبيت الأمثل</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>تثبيت مخصص</b>',
    'LBL_INSTALL_TYPE_MSG1'             => 'المفتاح مطلوب لوظيفية التطبيق العامة، ولكنه غير ضروري للتثبيت. لست في حاجة لإدخال المفتاح في هذا التوقيت، ولكنك ستحتاج إلى إدخال المفتاح بعد تثبيت التطبيق.',
    'LBL_INSTALL_TYPE_MSG2'             => 'يتطلب الحد الأدنى من البيانات للتثبيت. يُوصى به للمستخدمين الجدد.',
    'LBL_INSTALL_TYPE_MSG3'             => 'يتيح خيارات إضافية للتعيين أثناء التثبيت. أغلب تلك الخيارات متوفرة أيضًا بعد التثبيت في شاشات المسؤول. يُوصى به للمستخدمين المتقدمين.',
	'LBL_LANG_1'						=> 'لاستخدام لغة في Sugar خلاف اللغة الافتراضية (اللغة الإنجليزية للولايات المتحدة)، يمكنك تحميل وتثبيت حزمة اللغة في هذا التوقيت. ستكون لك القدرة على تحميل وتثبيت حزم اللغات داخل تطبيق Sugar أيضًا.  إذا كنت ترغب في تجاوز تلك الخطوة، فانقر "التالي".',
	'LBL_LANG_BUTTON_COMMIT'			=> 'تثبيت',
	'LBL_LANG_BUTTON_REMOVE'			=> 'إزالة',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'إلغاء التثبيت',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'تحميل',
	'LBL_LANG_NO_PACKS'					=> 'بلا',
	'LBL_LANG_PACK_INSTALLED'			=> 'تم تثبيت حزم اللغات التالية: ',
	'LBL_LANG_PACK_READY'				=> 'حزم اللغات التالية جاهزة للتثبيت: ',
	'LBL_LANG_SUCCESS'					=> 'تم تحميل حزمة اللغات بنجاح.',
	'LBL_LANG_TITLE'			   		=> 'حزمة اللغة',
    'LBL_LAUNCHING_SILENT_INSTALL'     => 'تثبيت Sugar الآن.  قد يستغرق هذا بضع دقائق.',
	'LBL_LANG_UPLOAD'					=> 'تحميل حزمة لغة',
	'LBL_LICENSE_ACCEPTANCE'			=> 'قبول الترخيص',
    'LBL_LICENSE_CHECKING'              => 'فحص توافقية النظام.',
    'LBL_LICENSE_CHKENV_HEADER'         => 'فحص البيئة',
    'LBL_LICENSE_CHKDB_HEADER'          => 'التحقق من DB، بيانات اعتماد FTS.',
    'LBL_LICENSE_CHECK_PASSED'          => 'اجتياز النظام لفحص التوافقية.',
    'LBL_LICENSE_REDIRECT'              => 'إعادة توجيهه في ',
	'LBL_LICENSE_DIRECTIONS'			=> 'إذا كانت لديك بيانات ترخيصك، فيُرجى إدخالها في الحقول أدناه.',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'أدخل مفتاح التنزيل',
	'LBL_LICENSE_EXPIRY'				=> 'تاريخ انتهاء الصلاحية',
	'LBL_LICENSE_I_ACCEPT'				=> 'قبول',
	'LBL_LICENSE_NUM_USERS'				=> 'عدد المستخدمين',
	'LBL_LICENSE_PRINTABLE'				=> ' عرض مطبوع ',
    'LBL_PRINT_SUMM'                    => 'طباعة الموجز',
	'LBL_LICENSE_TITLE_2'				=> 'ترخيص SugarCRM',
	'LBL_LICENSE_TITLE'					=> 'بيانات الترخيص',
	'LBL_LICENSE_USERS'					=> 'مستخدمون مرخص لهم',

	'LBL_LOCALE_CURRENCY'				=> 'إعدادات العملة',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'العملة الافتراضية',
	'LBL_LOCALE_CURR_SYMBOL'			=> 'رمز العملة',
	'LBL_LOCALE_CURR_ISO'				=> 'رمز العملة (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> 'فاصل الآلاف',
	'LBL_LOCALE_CURR_DECIMAL'			=> 'فاصل عشري',
	'LBL_LOCALE_CURR_EXAMPLE'			=> 'مثال',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> 'أرقام مميزة',
	'LBL_LOCALE_DATEF'					=> 'تنسيق التاريخ الافتراضي',
	'LBL_LOCALE_DESC'					=> 'ستنعكس إعدادات الموقع المحدد عالميًا داخل مثال Sugar.',
	'LBL_LOCALE_EXPORT'					=> 'ضبط حروف للتصدير/الاستيراد<br> <i>(والبريد الإلكتروني، و.csv، وvCard، وPDF، و استيراد البيانات)</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'محدد الاستيراد (.csv)',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'إعدادات الاستيراد/التصدير',
	'LBL_LOCALE_LANG'					=> 'اللغة الافتراضية',
	'LBL_LOCALE_NAMEF'					=> 'تنسيق الاسم الافتراضي',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = التحية<br />f = الاسم الأول<br />l = الاسم الأخير',
	'LBL_LOCALE_NAME_FIRST'				=> 'دافيد',
	'LBL_LOCALE_NAME_LAST'				=> 'ليفنجستون',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'دكتور',
	'LBL_LOCALE_TIMEF'					=> 'تنسيق الوقت الافتراضي',
	'LBL_LOCALE_TITLE'					=> 'الإعدادات المحلية',
    'LBL_CUSTOMIZE_LOCALE'              => 'تخصيص الإعدادات المحلية',
	'LBL_LOCALE_UI'						=> 'واجهة المستخدم',

	'LBL_ML_ACTION'						=> 'الإجراء',
	'LBL_ML_DESCRIPTION'				=> 'الوصف',
	'LBL_ML_INSTALLED'					=> 'تاريخ التثبيت',
	'LBL_ML_NAME'						=> 'الاسم',
	'LBL_ML_PUBLISHED'					=> 'تاريخ النشر',
	'LBL_ML_TYPE'						=> 'النوع',
	'LBL_ML_UNINSTALLABLE'				=> 'غير قابل لإلغاء التثبيت',
	'LBL_ML_VERSION'					=> 'الإصدار',
	'LBL_MSSQL'							=> 'خادم SQL',
	'LBL_MSSQL_SQLSRV'				    => 'خادم SQL (Microsoft SQL Server Driver خاص بـ PHP)',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL (mysqli امتداد)',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> 'التالي',
	'LBL_NO'							=> 'لا',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'كلمة مرور مسؤول موقع الإعداد',
	'LBL_PERFORM_AUDIT_TABLE'			=> 'جدول المراقبة / ',
	'LBL_PERFORM_CONFIG_PHP'			=> 'إنشاء ملف تهيئة Sugar',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>إنشاء قاعدة البيانات</b> ',
	'LBL_PERFORM_CREATE_DB_2'			=> ' <b>على</b> ',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'جارٍ إنشاء اسم مستخدم وكلمة مرور قاعدة البيانات...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'إنشاء بيانات Sugar الافتراضية',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'جارٍ إنشاء اسم مستخدم وكلمة مرور قاعدة البيانات للمضيف المحلي...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'إنشاء جداول العلاقات في Sugar',
	'LBL_PERFORM_CREATING'				=> 'إنشاء / ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'إنشاء التقارير الافتراضية',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'إنشاء الوظائف الزمنية الافتراضية',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'إدراج الإعدادات الافتراضية',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'إنشاء مستخدمين افتراضيين',
	'LBL_PERFORM_DEMO_DATA'				=> 'نشر جداول قاعدة البيانات ببيانات تجريبية (قد يستغرق هذا بعض الوقت)',
	'LBL_PERFORM_DONE'					=> 'تم<br>',
	'LBL_PERFORM_DROPPING'				=> 'إسقاط / ',
	'LBL_PERFORM_FINISH'				=> 'إنهاء',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'تحديث بيانات الترخيص',
	'LBL_PERFORM_OUTRO_1'				=> 'إعداد Sugar ',
	'LBL_PERFORM_OUTRO_2'				=> ' مكتمل الآن!',
	'LBL_PERFORM_OUTRO_3'				=> 'الوقت الكلي: ',
	'LBL_PERFORM_OUTRO_4'				=> ' ثوانٍ.',
	'LBL_PERFORM_OUTRO_5'				=> 'تم استخدام الذاكرة تقريبًا: ',
	'LBL_PERFORM_OUTRO_6'				=> ' بايت.',
	'LBL_PERFORM_OUTRO_7'				=> 'تم تثبيت نظامك الآن وتهيئته للاستخدام.',
	'LBL_PERFORM_REL_META'				=> 'علاقة ميتا... ',
	'LBL_PERFORM_SUCCESS'				=> 'بنجاح!',
	'LBL_PERFORM_TABLES'				=> 'إنشاء جداول تطبيق Sugar، وجداول المراقبة، وبيانات تعريف العلاقات',
	'LBL_PERFORM_TITLE'					=> 'القيام بالإعداد',
	'LBL_PRINT'							=> 'طباعة',
	'LBL_REG_CONF_1'					=> 'يُرجى استكمال النموذج القصير أدناه لتلقي إخطارات المنتج، وأخبار التدريب، والعروض الخاصة، ودعوات الأحداث الخاصة من SugarCRM. لا نبيع المعلومات التي تم حصرها هنا لأطراف ثالثة ، ولا نقوم بتأجيرها، ولا نشاركها أو نوزعها.',
	'LBL_REG_CONF_2'					=> 'تعتبر حقول اسمك وعنوان البريد الإلكتروني الخاصة بك هي الحقول المطلوبة فقط للتسجيل. كل الحقول الأخرى اختيارية، ولكنها مفيدة جدًا. لا نبيع، ولا نقوم بتأجير، ولا نشارك أو نوزع المعلومات التي تم حصرها هنا لأطراف ثالثة.',
	'LBL_REG_CONF_3'					=> 'نشكرك على التسجيل. انقر فوق زر "إنهاء" لتسجيل الدخول على SugarCRM. ستحتاج إلى تسجيل الدخول في المرة الأولى باستخدام اسم المستخدم "المسؤول" وكلمة المرور التي أدخلتها في الخطوة رقم 2.',
	'LBL_REG_TITLE'						=> 'التسجيل',
    'LBL_REG_NO_THANKS'                 => 'لا شكرًا',
    'LBL_REG_SKIP_THIS_STEP'            => 'تجاوز هذه الخطوة',
	'LBL_REQUIRED'						=> '*حقل مطلوب',

    'LBL_SITECFG_ADMIN_Name'            => 'اسم مسؤول تطبيق Sugar ',
	'LBL_SITECFG_ADMIN_PASS_2'			=> 'أعد إدخال كلمة مرور مستخدم مسؤول Sugar',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> 'تنبيه: بهذا سيتم تجاوز كلمة مرور المسؤول لأي تثبيت سابق.',
	'LBL_SITECFG_ADMIN_PASS'			=> 'كلمة مرور مستخدم مسؤول Sugar',
	'LBL_SITECFG_APP_ID'				=> 'معرف التطبيق',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> 'في حالة تحديده، يتعين عليك تقديم معرف التطبيق لتجاوز المعرف المتولد تلقائيًا. يضمن المعرف عدم استخدام جلسات مثال Sugar من خلال أمثلة أخرى.  إذا كانت لديك مجموعة من تثبيتات Sugar، فيجب أن تتشارك جميعها معرف التطبيق نفسه.',
	'LBL_SITECFG_CUSTOM_ID'				=> 'قم بتقديم معرف التطبيق الخاص بك.',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'في حالة تحديده، يتعين عليك تحديد دليل سجل لتجاوز الدليل الافتراضي لسجل Sugar. بصرف النظر عن مكان ملف السجل، فإن الدخول إليه من خلال متصفح الشبكات سيتم تقييده بواسطة إعادة توجيه htaccess.',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'استخدم دليل سجل مخصصًا',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'في حالة تحديده، يتعين عليك توفير مجلد آمن لتخزين معلومات الجلسة. ويمكن القيام بذلك لمنع اختراق بيانات الجلسة من خلال الخوادم المشتركة.',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'استخدم دليل جلسة مخصصًا لـ Sugar',
	'LBL_SITECFG_DIRECTIONS'			=> 'يُرجى إدخال بيانات تهيئة قاعدة الموقع الخاص بك أدناه. إذا كنت غير متأكد من الحقول، فإننا نقترح استخدام القيم الافتراضية.',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>يُرجى إصلاح الأخطاء التالية قبل المتابعة:</b>',
	'LBL_SITECFG_LOG_DIR'				=> 'دليل السجل',
	'LBL_SITECFG_SESSION_PATH'			=> 'يجب أن يكون دليل الجلسة<br>(قابلاً للكتابة عليه)',
	'LBL_SITECFG_SITE_SECURITY'			=> 'حدد خيارات الأمان',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'في حالة تحديده، سيقوم النظام بالتحقق من وجود إصدارات محدثة للتطبيق بانتظام.',
	'LBL_SITECFG_SUGAR_UP'				=> 'هل يتم التحقق من وجود تحديثات بانتظام؟',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'تهيئة تحديثات Sugar',
	'LBL_SITECFG_TITLE'					=> 'تهيئة الموقع',
    'LBL_SITECFG_TITLE2'                => 'تعريف مستخدم الإدارة',
    'LBL_SITECFG_SECURITY_TITLE'        => 'أمان الموقع',
	'LBL_SITECFG_URL'					=> 'عنوان مثال Sugar',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'هل تستخدم القيم الافتراضية؟',
	'LBL_SITECFG_ANONSTATS'             => 'هل يتم إرسال إحصائيات استخدام مجهولة؟',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'في حالة اختيارها، سيقوم Sugar بإرسال إحصائيات <b>مجهولة</b> عن عملية التثبيت الخاصة بك إلى شركة SugarCRM كل مرة تحقق فيها نظامك من وجود إصدارات جديدة. ستساعدنا هذه المعلومات على الفهم الأفضل عن طريقة استخدام التطبيق وتوجيه التحسينات للمنتج.',
    'LBL_SITECFG_URL_MSG'               => 'أدخل العنوان المستخدم للدخول على مثال Sugar بعد التثبيت. سيتم استخدام العنوان أيضًا بوصفه قاعدة للعناوين في صفحات تطبيق Sugar. يجب أن يتضمن العنوان خادم الشبكة، أو اسم الجهاز، أو عنوان IP.',
    'LBL_SITECFG_SYS_NAME_MSG'          => 'أدخل اسم النظام الخاص بك.  سيتم عرض هذا الاسم في شريط عنوان المتصفح عند زيارة المستخدمين لتطبيق Sugar.',
    'LBL_SITECFG_PASSWORD_MSG'          => 'بعد إتمام التثبيت، ستحتاج إلى استخدام مستخدم مسؤول Sugar (اسم المستخدم الافتراضي = مسؤول) لتسجيل الدخول على مثال Sugar.  أدخل كلمة مرور لهذا المستخدم المسؤول. يمكن تغيير كلمة المرور هذه بعد تسجيل الدخول المبدئي.  ربما تقوم بإدخال اسم مستخدم لمسؤول آخر بجانب القيمة الافتراضية المتاحة.',
    'LBL_SITECFG_COLLATION_MSG'         => 'حدد إعدادات التنظيم (فرز) لنظامك. ستعمل هذه الإعدادات على إنشاء جداول بلغة محددة تستخدمها. في حالة عدم احتياج لغتك لإعدادات معينة، يُرجى استخدام القيمة الافتراضية.',
    'LBL_SPRITE_SUPPORT'                => 'دعم روحي',
	'LBL_SYSTEM_CREDS'                  => 'بيانات اعتماد النظام',
    'LBL_SYSTEM_ENV'                    => 'بيئة النظام',
	'LBL_START'							=> 'البدء',
    'LBL_SHOW_PASS'                     => 'عرض كلمات المرور',
    'LBL_HIDE_PASS'                     => 'إخفاء كلمات المرور',
    'LBL_HIDDEN'                        => '<i>(مخفي)</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>اختر لغتك</b>',
	'LBL_STEP'							=> 'خطوة',
	'LBL_TITLE_WELCOME'					=> 'أهلاً بك مع SugarCRM ',
	'LBL_WELCOME_1'						=> 'يقوم هذا المثبت بإنشاء جداول قاعدة بيانات SugarCRM وتعيين متغيرات التهيئة التي تحتاجها للبدء. يتعين أن تستغرق العملية بأكملها عشر دقائق تقريبًا.',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => 'هل أنت مستعد للتثبيت؟',
    'REQUIRED_SYS_COMP' => 'مكونات نظام مطلوبة',
    'REQUIRED_SYS_COMP_MSG' =>
                    'قبل أن تبدأ، يُرجى التأكد من توفر الإصدارات المدعومة من الأنظمة التالية لديك
.........................المكونات:<br>
                      <ul>
                      <li> قاعدة البيانات/نظام إدارة قواعد البيانات (أمثلة: MySQL، SQL Server، Oracle، DB2)</li>
                      <li> خادم ويب (Apache، IIS)</li>
                      <li> Elasticsearch</li>
                      </ul>
                      راجع مصفوفة التوافق في ملاحظات الإصدار لـ
                      مكونات النظام المتوافق لإصدار Sugar الذي تقوم بتثبيته.<br>',
    'REQUIRED_SYS_CHK' => 'فحص مبدئي للنظام',
    'REQUIRED_SYS_CHK_MSG' =>
                    'عندما تبدأ عملية التثبيت، يتم التحقق من النظام على خادم ويب في المكان الذي توجد به ملفات Sugar وذلك
                      للتأكد من تهيئة النظام بطريقة صحيحة وتوفر جميع المكونات الضرورية به
                      لإتمام التثبيت بنجاح. <br><br>
                      يتحقق النظام مما يلي:<br>
                      <ul>
                      <li><b>إصدار PHP</b> – يجب أن يكون متوافقًا
                      مع التطبيق</li>
                                        <li><b>متغيرات الجلسة</b> – يجب أن تعمل بطريقة صحيحة</li>
                                            <li> <b>سلاسل MB</b> – يجب أن تكون مثبتة وممكنة في php.ini</li>

                      <li> <b>دعم قاعدة البيانات</b> – يجب أن تتوفر لـ MySQL, SQL
                      Server، أو Oracle، أو DB2</li>

                      <li> <b>Config.php</b> – يجب أن يتواجد وأن تتوفر له
                                  الأذونات المناسبة لكي تجعله قابلاً للكتابة</li>
       <li>ملفاتSugar التالية يجب أن تكون قابلة للكتابة: :<ul><li><b>/مخصص</li>
<li>/ذاكرة التخزين المؤقتة</li>
<li>/الوحدات</li>
<li>/تحميل</b></li></ul></li></ul>
                                  في حالة فشل التحقق، فلن تتمكن من متابعة التثبيت. سيتم عرض رسالة خطأ توضح تشير إلى عدم تمكن النظام من
                                  اجتياز عملية التحقق.
                                  بعد إجراء أي تغييرات ضرورية، يمكنك التحقق من النظام 
                                  تحقق مرة أخرى لمتابعة التثبيت.<br>',
    'REQUIRED_INSTALLTYPE' => 'تثبيت أمثل أو مخصص',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "بعد تنفيذ عملية التحقق من النظام، يمكنك اختيار إما
                      التثبيت النموذجي أو المخصص.<br><br>
                      بالنسبة لعمليات التثبيت <b>النموذجية</b> والمخصصة <b></b>، ستحتاج إلى معرفة ما يلي:<br>
                      <ul>
                      <li> <b>نوع قاعدة البيانات</b> التي ستستضيف بيانات Sugar <ul><li>أنواع قواعد البيانات
                      المتوافقة: MySQL، MS SQL Server، Oracle، DB2.<br><br></li></ul></li>
                      <li> <b>اسم خادم ويب</b> أو الكمبيوتر (المضيف) الذي توجد عليها قاعد البيانات
                      <ul><li>يمكن أن يكون ذلك  <i>مضيفًا محليًا</i> إذا كانت قاعدة البيانات موجودة على الكمبيوتر المحلي لديك، أو على نفس خادم ويب أو الكمبيوتر كملفات Sugar.<br><br></li></ul></li>
                      <li><b>اسم قاعدة البيانات</b> التي ستفضل استخدامها لاستضافة بيانات Sugar</li>
                        <ul>
                          <li> قد يكون لديك بالفعل قاعدة بيانات حالية تفضل أن تستخدمها. إذا
                          قمت بتقديم اسم قاعدة بيانات حالية، فإن الجداول الموجودة في قاعدة البيانات
                          سيتم إسقاطها أثناء عملية التثبيت عند تحديد مخطط قاعدة بيانات Sugar.</li>
                          <li> إذا لم يكن لديك بالفعل قاعدة بيانات، فإن الاسم الذي تقدمه سيتم استخدامه لـ
                          قاعدة البيانات الجديدة التي يتم إنشاءها للمثيل أثناء التثبيت.<br><br></li>
                        </ul>
                      <li><b>اسم مستخدم وقاعدة بيانات مسؤول قاعدة البيانات</b> <ul><li>يجب أن يكون مسؤول قاعدة البيانات قادرًا على إنشاء جداول ومستخدمين والكتابة إلى قاعدة البيانات.</li><li>قد يتطل الأمر منك
                      الاتصال بمسؤول قاعدة البيانات الخاصة بك فيما يتعلق بهذه المعلومات إذا لم تكن قاعدة البيانات
                      موجودة على الكمبيوتر المحلي و/أو إذا لم تكن أنت مسؤول قاعدة البيانات.<br><br></ul></li></li>
                      <li> <b>اسم مستخدم وكلمة مرور قاعدة بيانات Sugar</b>
                      </li>
                        <ul>
                          <li> يمكن أن يكون المستخدم مسؤول قاعدة البيانات، أو يمكنك توفير اسم
                          مستخدم قاعدة بيانات آخر. </li>
                          <li> إذا كنت تفضل إنشاء مستخدم قاعدة بيانات جديدة لهذا الغرض، فستتمكن من
                          توفير اسم مستخدم وكلمة مرور جديدين أثناء عملية التثبيت
                          وسيتم إنشاء المستخدم أثناء عملية التثبيت. </li>
                        </ul>
                    <li> <b>مضيف عملية البحث المرن والمنفذ الخاص بها</b>
                      </li>
                        <ul>
                          <li> مضيف البحث المرن هو المضيف الذي يعمل عليه محرك البحث. يُفترض أن يتم ذلك على المضيف المحلي على فرض قيامك بتشغيل محرك البحث على نفس الخادم مثل Sugar.</li>
                          <li> منفذ البحث المرن هو رقم المنفذ لـ Sugar للاتصال بمحرك البحث. يُفترض أن يكون ذلك هو الرقم 9200، وهو الرقم الافتراضي للبحث المرن. </li>
                        </ul>
                        </ul><p>

                      للضبط <b>المخصص</b> فقد تحتاج أيضًا إلى معرفة ما يلي:<br>
                      <ul>
                      <li> <b>عنوان URL الذي سيتم استخدامه للوصول إلى مثيل Sugar</b> بعد تثبيته.
                      يجب أن يتضمن عنوان URL هذا خادم الويب، أو اسم الجهاز، أو عنوان IP.<br><br></li>
                                  <li> [اختياري] <b>مسار إلى دليل الجلسة</b> إذا كنتت ترغب في استخدام جلسة
                                  مخصصة لمعلومات Sugar وذلك لكي تمنع اختراق
                                  بيانات الجلسة من على الخوادم المشتركة.<br><br></li>
                                  <li> [اختياري] <b>مسار إلى دليل سجل مخصص</b> إذا كنت ترغب في تجاوز الدليل الافتراضي لسجل Sugar.<br><br></li>
                                  <li> [اختياري] <b>معرف التطبيقات</b> إذا كنت ترغب في تجاوز المعرّف
                                  المُنشأ تلقائيًا الذي يضمن عد استخدام جلسات مثيل Sugar بواسطة مثيلات أخرى.<br><br></li>
                                  <li><b>مجموعة الحروف</b> يتم استخدامها بشكل أكثر شيوعًا في منطقتك.<br><br></li></ul>
                                  لمزيد من المعلومات التفصيلية، الرجاء الرجوع إلى دليل التثبيت.
                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => 'يُرجى قراءة المعلومات الهامة التالية قبل متابعة عملية التثبيت.  ستساعدك المعلومات في تحديد ما إذا كنت مستعدًا أم لا لتثبيت التطبيق في هذا الوقت.',


	'LBL_WELCOME_2'						=> 'للحصول على مستندات التثبيت، تفضل بزيارة <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>. <BR><BR> للاتصال بمهندس دعم SugarCRM للحصول على تعليمات التثبيت، يرجى تسجيل الدخول إلى <a target="_blank" href="http://support.sugarcrm.com">بوابة دعم SugarCRM</a> وإرسال حالة دعم.',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>اختر لغتك</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'نافذة الإعداد',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'أهلاً بك مع SugarCRM ',
	'LBL_WELCOME_TITLE'					=> 'نافذة إعداد SugarCRM',
	'LBL_WIZARD_TITLE'					=> 'نافذة إعداد Sugar: ',
	'LBL_YES'							=> 'نعم',
    'LBL_YES_MULTI'                     => 'نعم - بايت متعدد',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> 'معالجة مهام سير العمل',
	'LBL_OOTB_REPORTS'		=> 'تشغيل المهام المجدولة لإنشاء التقارير',
	'LBL_OOTB_IE'			=> 'مراجعة صناديق البريد الداخلية',
	'LBL_OOTB_BOUNCE'		=> 'تشغيل رسائل البريد الإلكتروني المرتدة الخاصة بحملات العمليات الليلية',
    'LBL_OOTB_CAMPAIGN'		=> 'تشغيل حملات البريد الإلكتروني الجماعية ليلاً',
	'LBL_OOTB_PRUNE'		=> 'تنقيح قواعد البيانات في الأول من كل شهر',
    'LBL_OOTB_TRACKER'		=> 'تنقيح جداول المتعقب',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'تشغيل إعلامات تذكير البريد الإلكتروني',
    'LBL_UPDATE_TRACKER_SESSIONS' => 'تحديث جدول tracker_sessions',
    'LBL_OOTB_CLEANUP_QUEUE' => 'مسح قوائم انتظار الوظائف',


    'LBL_FTS_TABLE_TITLE'     => 'توفير إعدادات بحث نص كامل',
    'LBL_FTS_HOST'     => 'مضيف',
    'LBL_FTS_PORT'     => 'المنفذ',
    'LBL_FTS_TYPE'     => 'نوع محرك البحث',
    'LBL_FTS_HELP'      => 'لتمكين البحث بنص كامل، أدخل المضيف والمنفذ حيث تتم استضافة محرك البحث. يحتوي Sugar على دعم ذاتي لمحركات البحث المرنة.',
    'LBL_FTS_REQUIRED'    => 'مطلوب البحث المرن.',
    'LBL_FTS_CONN_ERROR'    => 'تعذر الاتصال بخادم بحث نصي كامل، يُرجى التحقق من إعداداتك.',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => 'إصدار خادم بحث النص الكامل غير متوفر، يرجى التحقق من الإعدادات.',
    'LBL_FTS_UNSUPPORTED_VERSION'    => 'تم اكتشاف إصدار غير مدعوم للبحث المرن. يرجى استخدام الإصدارات: %s',

    'LBL_PATCHES_TITLE'     => 'تثبيت أحدث الملفات',
    'LBL_MODULE_TITLE'      => 'تثبيت حزم اللغة',
    'LBL_PATCH_1'           => 'إذا كنت ترغب في تجاوز تلك الخطوة، فانقر "التالي".',
    'LBL_PATCH_TITLE'       => 'ملف النظام',
    'LBL_PATCH_READY'       => 'الملف (الملفات) التالي جاهز للتثبيت:',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "تعتمد SugarCRM على جلسات PHP لتخزين المعلومات الهامة أثناء الاتصال بخادم الشبكة هذا.  لم تتم تهيئة معلومات الجلسة بشكل صحيح لتثبيت PHP.
											<br><br>من أكثر عمليات التهيئة الخاطئة شيوعًا أن تجد أن توجيه <b>'session.save_path'</b> لا يشير إلى دليل صالح.  <br>
           <br> الرجاء تصحيح <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>تهيئة PHP</a> في ملف php.ini الموجود هنا.",
	'LBL_SESSION_ERR_TITLE'				=> 'خطأ في تهيئة جلسات PHP',
	'LBL_SYSTEM_NAME'=>'اسم النظام',
    'LBL_COLLATION' => 'إعدادات الفرز',
	'LBL_REQUIRED_SYSTEM_NAME'=>'أدخل اسم نظام بالنسبة لمثال Sugar.',
	'LBL_PATCH_UPLOAD' => 'اختر ملف مجموعة من جهازك',
	'LBL_BACKWARD_COMPATIBILITY_ON' => 'تم تشغيل وضع توافق الإصدارات السابقة لـ PHP. ضبط zend.ze1_compatibility_mode على وضع إيقاف التشغيل للمتابعة',

    'advanced_password_new_account_email' => array(
        'subject' => 'بيانات حساب جديد',
        'description' => 'يُستخدم هذا القالب عند إرسال مسؤول النظام لكلمة مرور جديدة لمستخدم.',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>إليك اسم المستخدم وكلمة المرور المؤقتة لحسابك:</p><p>اسم المستخدم: $contact_user_user_name </p><p>كلمة المرور: $contact_user_user_hash </p><br><p>$config_site_url</p><br><p>بعد تسجيلك الدخول باستخدام كلمة المرور الموضحة أعلاه، ربما يُطلب منك إعادة تعيين كلمة المرور إلى أخرى حسب اختيارك.</p>   </td>         </tr><tr><td colspan=\\"2\\"></td>         </tr> </tbody></table> </div>',
        'txt_body' =>
'
ها هو اسم المستخدم وكلمة المرور المؤقتة الخاصة بحسابك:
اسم المستخدم: $contact_user_user_name
كلمة المرور : $contact_user_user_hash

$config_site_url

بعد قيامك بتسجيل الدخول باستخدام كلمة المرور الموضحة أعلاه، ربما يُطلب منك إعادة تعيين كلمة المرور إلى كلمة مرور أخرى حسب اختيارك.',
        'name' => 'بريد إلكتروني بكلمة مرور أنشأها النظام',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => 'أعد تعيين كلمة المرور لحسابك',
        'description' => "يُستخدم هذا القالب لإرسال رابط للمستخدم للنقر فوقه لإعادة تعيين كلمة مرور حساب المستخدم.",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>لقد طلبت مؤخرًا $contact_user_pwd_last_changed القدرة على إعادة تعيين كلمة المرور الخاصة بحسابك. </p><p>انقر فوق الرابط أدناه لإعادة تعيين كلمة المرور الخاصة بك:</p><p> $contact_user_link_guid </p>  </td>         </tr><tr><td colspan=\\"2\\"></td>         </tr> </tbody></table> </div>',
        'txt_body' =>
'
لقد طلبت مؤخرًا على $contact_user_pwd_last_changed أن تكون قادرًا على إعادة تعيين كلمة المرور الخاصة بحسابك.

انقر فوق الرابط أدناه لإعادة تعيين كلمة المرور الخاصة بك:

$contact_user_link_guid',
        'name' => 'نسيان كلمة المرور لبريدك الإلكتروني',
        ),
);
