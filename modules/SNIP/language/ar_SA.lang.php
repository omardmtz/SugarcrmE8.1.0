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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'أرشفة البريد الإلكتروني',
    'LBL_SNIP_SUMMARY' => "أرشفة البريد الإلكتروني هي خدمة استيراد تلقائية تتيح للمستخدمين استيراد رسائل البريد الإلكتروني داخل Sugar من خلال إرسالها من بريد إلكتروني لعميل أو خدمة إلى عنوان بريد إلكتروني متوفر لدى Sugar. كل مثال Sugar له عنوان بريد إلكتروني فريد خاص به. لاستيراد عناوين البريد الإلكتروني، يقوم المستخدم بالإرسال إلى عنوان البريد الإلكتروني المزود باستخدام حقول \"إلى\"، و\"نسخة كربونية\"، و\"نسخة كربونية صماء\". ستقوم خدمة أرشفة البريد الإلكتروني باستيراد البريد الإلكتروني داخل مثال Sugar. تقوم الخدمة باستيراد البريد الإلكتروني، مع أي مرفقات، أو صور، أو أحداث محددة بتوقيتات وإنشاء سجلات داخل التطبيق مرتبطة بالسجلات الموجودة بناءً على عناوين البريد الإلكتروني المتطابقة.
    <br><br>مثال: بوصفك مستخدمًا، عند عرض حساب، سوف تكون قادرًا على مشاهدة جميع عناوين البريد الإلكتروني المرتبطة بالحساب بناءً على عنوان البريد الإلكتروني في سجل الحساب.  ستكون قادرًا أيضًا على استعراض عناوين البريد الإلكتروني المرتبطة بجهات الاتصال المرتبطة بالحساب.
    <br><br>اقبل الشروط التالية وانقر فوق \"تمكين\" لبدء استخدام الخدمة. لديك القدرة على تعطيل الخدمة في أي وقت. بمجرد تفعيل الخدمة، سيظهر عنوان البريد الإلكتروني المراد استخدامه في الخدمة.
    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'فشل الاتصال بخدمة أرشفة البريد الإلكتروني: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'أرشفة البريد الإلكتروني',
    'LBL_DISABLE_SNIP' => 'تعطيل',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'مفتاح التطبيق الفريد',
    'LBL_SNIP_USER' => 'مستخدم أرشفة البريد الإلكتروني',
    'LBL_SNIP_PWD' => 'كلمة مرور أرشفة البريد الإلكتروني',
    'LBL_SNIP_SUGAR_URL' => 'عنوان مثال Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'عنوان خدمة أرشفة البريد الإلكتروني',
    'LBL_SNIP_USER_DESC' => 'مستخدم أرشفة البريد الإلكتروني',
    'LBL_SNIP_KEY_DESC' => 'مفتاح OAuth لأرشفة البريد الإلكتروني. يُستخدم في الدخول إلى هذا المثال لأغراض استيراد البريد الإلكتروني.',
    'LBL_SNIP_STATUS_OK' => 'مُمكَّن',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'تم اتصال هذا المثال لـ Sugar بنجاح مع خادم أرشفة البريد الإلكتروني.',
    'LBL_SNIP_STATUS_ERROR' => 'خطأ',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'المثال له ترخيص خادم أرشفة بريد إلكتروني صالح، ولكن الخادم رد برسالة الخطأ التالية:',
    'LBL_SNIP_STATUS_FAIL' => 'تعذر التسجيل بواسطة خادم أرشفة البريد الإلكتروني',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'خدمة أرشفة البريد الإلكتروني غير متاحة حاليًا.  الخدمة معطلة أو فشل الاتصال بمثال Sugar.',
    'LBL_SNIP_GENERIC_ERROR' => 'خدمة أرشفة البريد الإلكتروني غير متاحة حاليًا.  الخدمة معطلة أو فشل الاتصال بمثال Sugar.',

	'LBL_SNIP_STATUS_RESET' => 'لم يعمل حتى الآن',
	'LBL_SNIP_STATUS_PROBLEM' => 'مشكلة: %s',
    'LBL_SNIP_NEVER' => "مطلقًا",
    'LBL_SNIP_STATUS_SUMMARY' => "حالة خدمة أرشفة البريد الإلكتروني:",
    'LBL_SNIP_ACCOUNT' => "الحساب",
    'LBL_SNIP_STATUS' => "الحالة",
    'LBL_SNIP_LAST_SUCCESS' => "آخر تشغيل ناجح",
    "LBL_SNIP_DESCRIPTION" => "أرشفة البريد الإلكتروني هي نظام أرشفة للبريد الإلكتروني التلقائي",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "فهو يتيح لك استعراض عناوين البريد الإلكتروني التي تم الإرسال إليها أو من خلال جهات الاتصال لديك داخل SugarCRM، دون الحاجة لاستيراد عناوين البريد الإلكتروني وربطها يدويًا",
    "LBL_SNIP_PURCHASE_SUMMARY" => "لاستخدام خدمة أرشفة البريد الإلكتروني، يتعين عليك شراء ترخيص لمثال SugarCRM الخاص بك",
    "LBL_SNIP_PURCHASE" => "انقر هنا للشراء",
    'LBL_SNIP_EMAIL' => 'عنوان أرشفة البريد الإلكتروني',
    'LBL_SNIP_AGREE' => "أوافق على الشروط أعلاه و <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>اتفاقية الخصوصية</a>.",
    'LBL_SNIP_PRIVACY' => 'اتفاقية الخصوصية',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'تعذر اختبار الاتصال',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'تعذر إنشاء اتصال بين خادم أرشفة البريد الإلكتروني ومثال Sugar الخاص بك. يرجى المحاولة مرة أخرى أو <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">الاتصال بدعم العملاء</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'تفعيل أرشفة البريد الإلكتروني',
    'LBL_SNIP_BUTTON_DISABLE' => 'تعطيل أرشفة البريد الإلكتروني',
    'LBL_SNIP_BUTTON_RETRY' => 'حاول الاتصال مجددًا',
    'LBL_SNIP_ERROR_DISABLING' => 'حدث خطأ أثناء محاولة الاتصال بخادم أرشفة البريد الإلكتروني، وتعذر إنشاء الخدمة',
    'LBL_SNIP_ERROR_ENABLING' => 'حدث خطأ أثناء محاولة الاتصال بخادم أرشفة البريد الإلكتروني، وتعذر تفعيل الخدمة',
    'LBL_CONTACT_SUPPORT' => 'يرجى المحاولة مرة أخرى أو الاتصال بدعم SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'يرجى الاتصال بدعم SugarCRM للمساعدة.',
    'ERROR_BAD_RESULT' => 'نتيجة سيئة تم إرجاعها من الخدمة',
	'ERROR_NO_CURL' => 'مطلوب امتدادات cURL، ولكن لم يتم تفعيلها',
	'ERROR_REQUEST_FAILED' => 'تعذر الاتصال بالخادم',

    'LBL_CANCEL_BUTTON_TITLE' => 'إلغاء',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'هذه هي حالة خدمة أرشفة البريد الإلكتروني على المثال الخاص بك. تعكس الحالة ما إذا كان الاتصال بين خادم أرشفة البريد الإلكتروني ومثال Sugar الخاص بك ناجحًا أم لا.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'هذا هو عنوان البريد الإلكتروني لأرشفة البريد الإلكتروني المراد الإرسال إليه لاستيراد عناوين البريد الإلكتروني داخل Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'هذا هو العنوان الخاص بخادم أرشفة البريد الإلكتروني. كل الطلبات، مثل تفعيل وتعطيل خدمة أرشفة البريد الإلكتروني، التي سيتم الاعتماد عليها من خلال هذا العنوان.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'هذا هو عنوان خدمات الموقع لمثال Sugar. سيتصل خادم أرشفة البريد الإلكتروني بخادمك من خلال هذا العنوان.',
);
