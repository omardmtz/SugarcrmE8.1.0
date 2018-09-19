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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'لوحة معلومات قائمة قوائم الهدف',

  'LBL_MODULE_NAME' => 'قوائم هدف',
  'LBL_MODULE_NAME_SINGULAR' => 'قائمة هدف',
  'LBL_MODULE_ID'   => 'قوائم هدف',
  'LBL_MODULE_TITLE' => 'قوائم هدف: الصفحة الرئيسية',
  'LBL_SEARCH_FORM_TITLE' => 'بحث في قوائم هدف',
  'LBL_LIST_FORM_TITLE' => 'قوائم هدف',
  'LBL_PROSPECT_LIST_NAME' => 'قائمة هدف:',
  'LBL_NAME' => 'الاسم',
  'LBL_ENTRIES' => 'إجمالي الإدخالات',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'قائمة هدف',
  'LBL_LIST_ENTRIES' => 'الأهداف في القائمة',
  'LBL_LIST_DESCRIPTION' => 'الوصف',
  'LBL_LIST_TYPE_NO' => 'النوع',
  'LBL_LIST_END_DATE' => 'تاريخ الانتهاء',
  'LBL_DATE_ENTERED' => 'تاريخ الإنشاء',
  'LBL_MARKETING_ID' => 'معرّف التسويق',
  'LBL_DATE_MODIFIED' => 'تاريخ التعديل',
  'LBL_MODIFIED' => 'تم التعديل بواسطة',
  'LBL_CREATED' => 'تم الإنشاء بواسطة',
  'LBL_TEAM' => 'الفريق',
  'LBL_ASSIGNED_TO' => 'تعيين إلى',
  'LBL_DESCRIPTION' => 'الوصف',
  'LNK_NEW_CAMPAIGN' => 'إنشاء حملة',
  'LNK_CAMPAIGN_LIST' => 'الحملات',
  'LNK_NEW_PROSPECT_LIST' => 'إنشاء قائمة هدف',
  'LNK_PROSPECT_LIST_LIST' => 'عرض قوائم هدف',
  'LBL_MODIFIED_BY' => 'تم التعديل بواسطة',
  'LBL_CREATED_BY' => 'تم الإنشاء بواسطة',
  'LBL_DATE_CREATED' => 'تاريخ الإنشاء',
  'LBL_DATE_LAST_MODIFIED' => 'تاريخ التعديل',
  'LNK_NEW_PROSPECT' => 'إنشاء هدف',
  'LNK_PROSPECT_LIST' => 'الأهداف',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'قوائم هدف',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'جهات الاتصال',
  'LBL_LEADS_SUBPANEL_TITLE' => 'العملاء المتوقعون',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'الأهداف',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'الحسابات',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'الحملات',
  'LBL_COPY_PREFIX' =>'نسخة من',
  'LBL_USERS_SUBPANEL_TITLE' =>'المستخدمون',
  'LBL_TYPE' => 'النوع',
  'LBL_LIST_TYPE' => 'النوع',
  'LBL_LIST_TYPE_LIST_NAME'=>'النوع',
  'LBL_NEW_FORM_TITLE'=>'قائمة هدف جديدة',
  'LBL_MARKETING_NAME'=>'الاسم التسويقي',
  'LBL_MARKETING_MESSAGE'=>'الرسالة التسويقية عبر البريد الإلكتروني',
  'LBL_DOMAIN_NAME'=>'اسم المجال',
  'LBL_DOMAIN'=>'لا توجد رسائل بريد إلكتروني للمجال',
  'LBL_LIST_PROSPECTLIST_NAME'=>'الاسم',
	'LBL_MORE_DETAIL' => 'مزيد من التفاصيل' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'يتألف {{module_name}} من مجموعة من الأفراد أو المؤسسات التي تريد تضمينها أو استبعادها من التسويق الجماعي {{campaigns_singular_module}}. {{plural_module_name}} يمكن أن يحتوي على أي عدد وأي تركيبة من الأهداف، {{contacts_module}}، {{leads_module}}، والمستخدمين، و {{accounts_module}}. يمكن تجميع الأهداف في {{module_name}} وفقًا لمجموعة من المعايير المحددة سابقًا مثل المجموعة العمرية، أو الموقع الجغرافي، أو عادات الإنفاق. {{plural_module_name}} تُستخدم في رسائل البريد الإلكتروني التسويقية الجماعية {{campaigns_module}} التي يمكن تهيئتها في الوحدة {{campaigns_module}}.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'يتألف {{module_name}} من مجموعة من الأفراد أو المؤسسات التي تريد تضمينها أو استبعادها من التسويق الجماعي {{campaigns_singular_module}}.

- قم بتعديل هذا السجل&#39;الحقول بواسطة النقر فوق حقل لأحد الأفراد أو الزر "تحرير".
- قم بعرض الارتباطات إلى سجلات أخرى في اللوحات الفرعية أو تعديلها، بما في ذلك مستلمو{{campaigns_singular_module}} بواسطة تبديل اللوحة اليسرى السفلية إلى "عرض البيانات".
- قم بإعداد تعليقات المستخدم وعرضها وسجل تغيير السجلات في {{activitystream_singular_module}} بواسطة تبديل اللوحة اليسرى السفلية إلى "تدفق الأنشطة".
- اتبع هذا السجل أو اجعله هو المفضل بالنسبة لك باستخدام الرموز الموجودة على يمين اسم السجل.
- تتوفر الإجراءات الإضافية في قائمة "الإجراءات" المنسدلة الموجودة على يسار الزر "تحرير".',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'يتألف {{module_name}} من مجموعة من الأفراد أو المؤسسات التي تريد تضمينها أو استبعادها من التسويق الجماعي {{campaigns_singular_module}}.

لإنشاء {{module_name}}:
1. قم بتوفير قيم للحقول حسب الرغبة.
 - يجب أن يتم إكمال الحقول المميزة على أنها "مطلوبة" قبل الحفظ.
 - انقر فوق "إظهار المزيد" لعرض حقول إضافية إذا لزم الأمر.
2. انقر فوق "حفظ" لإنهاء السجل الجديد والعودة إلى الصفحة السابقة.
3. بعد الحفظ، استخدم اللوحات الفرعية المتاحة في عرض سجل الأهداف لإضافة مستلمي {{campaigns_singular_module}}.',
);
