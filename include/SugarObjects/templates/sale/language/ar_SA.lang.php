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
  'LBL_MODULE_NAME' => 'المبيعات',
  'LBL_MODULE_TITLE' => 'المبيعات: الصفحة الرئيسية',
  'LBL_SEARCH_FORM_TITLE' => 'البحث في المبيعات',
  'LBL_VIEW_FORM_TITLE' => 'عرض المبيعات',
  'LBL_LIST_FORM_TITLE' => 'قائمة المبيعات',
  'LBL_SALE_NAME' => 'اسم المبيعات:',
  'LBL_SALE' => 'المبيعات:',
  'LBL_NAME' => 'اسم المبيعات',
  'LBL_LIST_SALE_NAME' => 'الاسم',
  'LBL_LIST_ACCOUNT_NAME' => 'اسم الحساب',
  'LBL_LIST_AMOUNT' => 'المبلغ',
  'LBL_LIST_DATE_CLOSED' => 'إغلاق',
  'LBL_LIST_SALE_STAGE' => 'مرحلة المبيعات',
  'LBL_ACCOUNT_ID'=>'معرّف الحساب',
  'LBL_TEAM_ID' =>'معرّف الفريق',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'المبيعات - تحديث العملة',
  'UPDATE_DOLLARAMOUNTS' => 'تحديث المبالغ بالدولار الأمريكي',
  'UPDATE_VERIFY' => 'التحقق من صحة المبالغ',
  'UPDATE_VERIFY_TXT' => 'يؤكد أن قيم المبالغ المالية في المبيعات أرقام عشرية صحيحة مع أحرف رقمية (0-9) وعلامات عشرية (.) فقط',
  'UPDATE_FIX' => 'تصحيح المبالغ',
  'UPDATE_FIX_TXT' => 'يحاول تصحيح أي مبالغ غير صحيحة من خلال إنشاء قيم عشرية صحيحة من المبلغ الحالي. أي مبلغ معدل يكون منسوخًا احتياطيًا في حقل قاعدة بيانات amount_backup. في حالة تشغيل هذا وملاحظة وجود أخطاء، لا تعِد تشغيله بدون الاستعادة من النسخ الاحتياطي، حيث إن ذلك قد يؤدي إلى استبدال البيانات غير الصحيحة بالنَّسخ الاحتياطي.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'تحديث المبالغ بالدولار الأمريكي لمبالغ المبيعات اعتمادًا على معدلات العملات المحددة حاليًا. تستخدم هذه القيمة لحساب الرسومات وقائمة عرض مبالغ العملة.',
  'UPDATE_CREATE_CURRENCY' => 'إنشاء عملة جديدة:',
  'UPDATE_VERIFY_FAIL' => 'فشل التحقق من السجل:',
  'UPDATE_VERIFY_CURAMOUNT' => 'المبلغ الحالي:',
  'UPDATE_VERIFY_FIX' => 'تشغيل التصحيح سيؤدي إلى',
  'UPDATE_INCLUDE_CLOSE' => 'تضمين السجلات المغلقة',
  'UPDATE_VERIFY_NEWAMOUNT' => 'مبلغ جديد:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'عملة جديدة:',
  'UPDATE_DONE' => 'تم',
  'UPDATE_BUG_COUNT' => 'الأخطاء التي تم العثور عليها ومحاولة إصلاحها:',
  'UPDATE_BUGFOUND_COUNT' => 'الأخطاء التي تم العثور عليها:',
  'UPDATE_COUNT' => 'السجلات التي تم تحديثها:',
  'UPDATE_RESTORE_COUNT' => 'تسجيل المبالغ التي تمت استعادتها:',
  'UPDATE_RESTORE' => 'استعادة المبالغ',
  'UPDATE_RESTORE_TXT' => 'يستعيد قيم المبالغ من النُّسخ الاحتياطية التي تم إنشاؤها أثناء التصحيح.',
  'UPDATE_FAIL' => 'تعذر التحديث - ',
  'UPDATE_NULL_VALUE' => 'القيمة فارغة يتم ضبطها على 0 -',
  'UPDATE_MERGE' => 'دمج العملات',
  'UPDATE_MERGE_TXT' => 'دمج العملات المتعددة في عملة واحدة. عند وجود سجلات عملات متعددة لنفس العملة، يمكنك دمجها معًا. هذا سوف يدمج أيضًا العملات لجميع الوحدات الأخرى.',
  'LBL_ACCOUNT_NAME' => 'اسم الحساب:',
  'LBL_AMOUNT' => 'المبلغ:',
  'LBL_AMOUNT_USDOLLAR' => 'المبلغ بالدولار الأمريكي:',
  'LBL_CURRENCY' => 'العملة:',
  'LBL_DATE_CLOSED' => 'تاريخ الإغلاق المتوقع:',
  'LBL_TYPE' => 'النوع:',
  'LBL_CAMPAIGN' => 'الحملة:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'العملاء المتوقعون',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'المشروعات',  
  'LBL_NEXT_STEP' => 'الخطوة التالية:',
  'LBL_LEAD_SOURCE' => 'مصدر العميل المتوقع:',
  'LBL_SALES_STAGE' => 'مرحلة المبيعات:',
  'LBL_PROBABILITY' => 'الاحتمالية (%):',
  'LBL_DESCRIPTION' => 'الوصف:',
  'LBL_DUPLICATE' => 'إمكانية تكرار المبيعات',
  'MSG_DUPLICATE' => 'سجل المبيعات الذي توشك على إنشائه قد يكون نسخة مكررة من سجل مبيعات موجود بالفعل. سجلات المبيعات التي تحتوي على أسماء متشابهة مدرجة أدناه.<br>انقر فوق "حفظ" للاستمرار في إنشاء هذه المبيعات الجديدة، أو انقر فوق "إلغاء" للعودة إلى الوحدة بدون إنشاء المبيعات.',
  'LBL_NEW_FORM_TITLE' => 'إنشاء مبيعات',
  'LNK_NEW_SALE' => 'إنشاء مبيعات',
  'LNK_SALE_LIST' => 'المبيعات',
  'ERR_DELETE_RECORD' => 'يجب تحديد رقم السجل لحذف المبيعات.',
  'LBL_TOP_SALES' => 'أعلى مبيعات مفتوحة خاصة بي',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'هل تريد بالتأكيد إزالة جهة الاتصال هذه من المبيعات؟',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'هل تريد بالتأكيد إزالة هذه المبيعات من المشروع؟',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'الأنشطة',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'السجل',
    'LBL_RAW_AMOUNT'=>'صافي المبلغ',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'جهات الاتصال',
	'LBL_ASSIGNED_TO_NAME' => 'المستخدم:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'المستخدم المعين',
  'LBL_MY_CLOSED_SALES' => 'المبيعات المغلقة الخاصة بي',
  'LBL_TOTAL_SALES' => 'إجمالي المبيعات',
  'LBL_CLOSED_WON_SALES' => 'المبيعات المغلقة التي تم الفوز بها',
  'LBL_ASSIGNED_TO_ID' =>'معين إلى معرّف',
  'LBL_CREATED_ID'=>'تم الإنشاء بواسطة المعرّف',
  'LBL_MODIFIED_ID'=>'تم التعديل بواسطة المعرّف',
  'LBL_MODIFIED_NAME'=>'تم التعديل بواسطة اسم المستخدم',
  'LBL_SALE_INFORMATION'=>'معلومات المبيعات',
  'LBL_CURRENCY_ID'=>'معرّف العملة',
  'LBL_CURRENCY_NAME'=>'اسم العملة',
  'LBL_CURRENCY_SYMBOL'=>'رمز العملة',
  'LBL_EDIT_BUTTON' => 'تحرير',
  'LBL_REMOVE' => 'إزالة',
  'LBL_CURRENCY_RATE' => 'سعر العملة',

);

