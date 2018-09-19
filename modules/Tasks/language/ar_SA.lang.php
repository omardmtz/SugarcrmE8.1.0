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
  'LBL_TASKS_LIST_DASHBOARD' => 'لوحة معلومات قائمة المهام',

  'LBL_MODULE_NAME' => 'المهام',
  'LBL_MODULE_NAME_SINGULAR' => 'المهمة',
  'LBL_TASK' => 'المهام: ',
  'LBL_MODULE_TITLE' => ' المهام: الصفحة الرئيسية',
  'LBL_SEARCH_FORM_TITLE' => ' البحث عن مهمة',
  'LBL_LIST_FORM_TITLE' => ' لائحة المهام',
  'LBL_NEW_FORM_TITLE' => ' إنشاء مهمة',
  'LBL_NEW_FORM_SUBJECT' => 'الموضوع:',
  'LBL_NEW_FORM_DUE_DATE' => 'تاريخ الاستحقاق:',
  'LBL_NEW_FORM_DUE_TIME' => 'وقت الاستحقاق:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'إغلاق',
  'LBL_LIST_SUBJECT' => 'الموضوع',
  'LBL_LIST_CONTACT' => 'جهة الاتصال',
  'LBL_LIST_PRIORITY' => 'الأولوية',
  'LBL_LIST_RELATED_TO' => 'مرتبط بـ',
  'LBL_LIST_DUE_DATE' => 'تاريخ الاستحقاق',
  'LBL_LIST_DUE_TIME' => 'وقت الاستحقاق',
  'LBL_SUBJECT' => 'الموضوع:',
  'LBL_STATUS' => 'الحالة:',
  'LBL_DUE_DATE' => 'تاريخ الاستحقاق:',
  'LBL_DUE_TIME' => 'وقت الاستحقاق:',
  'LBL_PRIORITY' => 'الأولوية:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'تاريخ ووقت الاستحقاق:',
  'LBL_START_DATE_AND_TIME' => 'تاريخ ووقت البدء:',
  'LBL_START_DATE' => 'تاريخ البدء:',
  'LBL_LIST_START_DATE' => 'تاريخ البدء',
  'LBL_START_TIME' => 'وقت البدء:',
  'LBL_LIST_START_TIME' => 'وقت البدء',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => 'بلا',
  'LBL_CONTACT' => 'جهة الاتصال:',
  'LBL_EMAIL_ADDRESS' => 'عنوان البريد الإلكتروني:',
  'LBL_PHONE' => 'الهاتف:',
  'LBL_EMAIL' => 'عنوان البريد الإلكتروني:',
  'LBL_DESCRIPTION_INFORMATION' => 'معلومات الوصف',
  'LBL_DESCRIPTION' => 'الوصف:',
  'LBL_NAME' => 'الاسم:',
  'LBL_CONTACT_NAME' => 'اسم جهة الاتصال ',
  'LBL_LIST_COMPLETE' => 'كامل:',
  'LBL_LIST_STATUS' => 'الحالة',
  'LBL_DATE_DUE_FLAG' => 'لا يوجد تاريخ استحقاق',
  'LBL_DATE_START_FLAG' => 'لا يوجد تاريخ بدء',
  'ERR_DELETE_RECORD' => 'يجب عليك تحديد رقم السجل لحذف "جهة الاتصال".',
  'ERR_INVALID_HOUR' => 'يرجى إدخال ساعة بين 0 و24',
  'LBL_DEFAULT_PRIORITY' => 'متوسط',
  'LBL_LIST_MY_TASKS' => 'المهام المفتوحة الخاصة بي',
  'LNK_NEW_TASK' => 'إنشاء مهمة',
  'LNK_TASK_LIST' => 'عرض المهام',
  'LNK_IMPORT_TASKS' => 'استيراد المهام',
  'LBL_CONTACT_FIRST_NAME'=>'الاسم الأول لجهة الاتصال',
  'LBL_CONTACT_LAST_NAME'=>'الاسم الأخير لجهة الاتصال',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'المستخدم المعين',
  'LBL_ASSIGNED_TO_NAME'=>'تعيين إلى:',
  'LBL_LIST_DATE_MODIFIED' => 'تاريخ التعديل',
  'LBL_CONTACT_ID' => 'معرّف جهة الاتصال:',
  'LBL_PARENT_ID' => 'معرّف الأصل:',
  'LBL_CONTACT_PHONE' => 'هاتف جهة الاتصال:',
  'LBL_PARENT_NAME' => 'النوع الأصل:',
  'LBL_ACTIVITIES_REPORTS' => 'تقرير الأنشطة',
  'LBL_EDITLAYOUT' => 'تعديل المخطط' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'نظرة عامة',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'ملاحظات',
  'LBL_REVENUELINEITEMS' => 'بنود العائدات',
  //For export labels
  'LBL_DATE_DUE' => 'تاريخ الاستحقاق',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'اسم المستخدم المعين',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'معرّف المستخدم المعين',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'تم التعديل بواسطة المعرّف',
  'LBL_EXPORT_CREATED_BY' => 'تم الإنشاء بواسطة المعرّف',
  'LBL_EXPORT_PARENT_TYPE' => 'الوحدة ذات الصلة',
  'LBL_EXPORT_PARENT_ID' => 'المعرّف ذو الصلة',
  'LBL_TASK_CLOSE_SUCCESS' => 'تم إغلاق المهمة بنجاح.',
  'LBL_ASSIGNED_USER' => 'تعيين إلى',

    'LBL_NOTES_SUBPANEL_TITLE' => 'ملاحظات',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'تتألف الوحدة {{plural_module_name}} من إجراءات مرنة، أو عناصر المهام، أو نوع آخر من الأنشطة التي تتطلب الاستكمال. {{module_name}} يمكن أن يتم ربط السجلات بسجل واحد في أغلب الوحدات من خلال حقل الربط المرن كما يمكن ربطها كذلك بـ {{contacts_singular_module}} مفردة. هناك طرق عديدة يمكنك من خلالها إنشاء {{plural_module_name}} في Sugar على سبيل المثال، عبر الوحدة {{plural_module_name}}، والتكرار، والاستيراد {{plural_module_name}}، إلخ. بمجرد إنشاء السجل {{module_name}}، يمكنك عرض المعلومات المتعلقة بـ {{module_name}} عبر {{plural_module_name}} عرض السجل وتعديلها. استنادًا إلى التفاصيل في {{module_name}}، يمكنك أيضًا أن تكون قادرًا على عرض وتعديل معلومات {{module_name}} عبر وحدة التقويم. بعدها، يمكن ربط كل سجل {{module_name}} بسجلات Sugar الأخرى مثل {{accounts_module}}، و {{contacts_module}}، و{{opportunities_module}}، وغير ذلك الكثير.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'تتألف الوحدة {{plural_module_name}} من إجراءات مرنة، أو عناصر المهام، أو أي نوع آخر من الأنشطة التي تتطلب الاستكمال.

- قم بتعديل حقول هذا السجل (السجلات) بواسطة النقر فوق حقل فردي، أو فوق الزر "تحرير".
- اعرض أو قم بتعديل الارتباطات إلى سجلات أخرى في اللوحات الفرعية بواسطة تبديل الجزء الأيسر السفلي إلى "عرض البيانات".
- قم بعمل وعرض تعليقات المستخدم وسجل تغيير السجلات في {{activitystream_singular_module}} بواسطة تبديل الجزء الأيسر السفلي إلى "تدفق النشاط".
- اتبع أو اجعل هذا السجل مفضلًا لديك باستخدام الرموز الموجودة على يمين اسم السجل.
- تتوفر إجراءات إضافية في القائمة المنسدلة "الإجراءات" الموجودة على يمين الزر "تحرير".',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'تتألف الوحدة {{plural_module_name}} من إجراءات مرنة، أو عناصر المهام، أو نوع آخر من الأنشطة التي تتطلب الاستكمال. 

لإنشاء {{module_name}}:
1. قم بتوفير قيم للحقول حسب الرغبة.
 - يجب أن يتم إكمال الحقول المميزة على أنها "مطلوبة" قبل الحفظ.
 - انقر فوق "إظهار المزيد" لعرض حقول إضافية إذا لزم الأمر.
2. انقر فوق "حفظ" لإنهاء السجل الجديد والعودة إلى الصفحة السابقة.',

);
