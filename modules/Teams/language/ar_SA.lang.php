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
    'ERR_ADD_RECORD' => 'يجب عليك تخصيص رقم للسجل لإضافة مستخدم إلى هذا الفريق.',
    'ERR_DUP_NAME' => 'اسم الفريق موجود بالفعل، يرجى اختيار اسم آخر.',
    'ERR_DELETE_RECORD' => 'يجب عليك تحديد رقم للسجل لحذف هذا الفريق.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'خطأ.  الفريق المحدد <b>({0})</b> عبارة عن الفريق الذي اخترت أن تقوم بحذفه.  يرجى تحديد فريق آخر.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'خطأ.  لا يمكنك حذف مستخدم لم يتم حذف الفريق الخاص به.',
    'LBL_DESCRIPTION' => 'الوصف:',
    'LBL_GLOBAL_TEAM_DESC' => 'ظاهر بشكل عام',
    'LBL_INVITEE' => 'أعضاء الفريق',
    'LBL_LIST_DEPARTMENT' => 'القسم',
    'LBL_LIST_DESCRIPTION' => 'الوصف',
    'LBL_LIST_FORM_TITLE' => 'لائحة الفريق',
    'LBL_LIST_NAME' => 'الاسم',
    'LBL_FIRST_NAME' => 'الاسم الأول:',
    'LBL_LAST_NAME' => 'الاسم الأخير:',
    'LBL_LIST_REPORTS_TO' => 'تقارير إلى',
    'LBL_LIST_TITLE' => 'العنوان',
    'LBL_MODULE_NAME' => 'الفرق',
    'LBL_MODULE_NAME_SINGULAR' => 'الفريق',
    'LBL_MODULE_TITLE' => 'الفرق: الصفحة الرئيسية',
    'LBL_NAME' => 'اسم الفريق:',
    'LBL_NAME_2' => 'اسم الفريق (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'اسم الفريق الأساسي',
    'LBL_NEW_FORM_TITLE' => 'الفريق الجديد',
    'LBL_PRIVATE' => 'خاص',
    'LBL_PRIVATE_TEAM_FOR' => 'فريق خاص لـ: ',
    'LBL_SEARCH_FORM_TITLE' => 'البحث عن الفريق',
    'LBL_TEAM_MEMBERS' => 'أعضاء الفريق',
    'LBL_TEAM' => 'الفرق:',
    'LBL_USERS_SUBPANEL_TITLE' => 'المستخدمون',
    'LBL_USERS' => 'المستخدمون',
    'LBL_REASSIGN_TEAM_TITLE' => 'توجد سجلات معينة للفريق (الفرق) التالي: <b>{0}</b><br>قبل حذف الفريق (الفرق)، يجب عليك أولاً إعادة تعيين هذه السجلات لفريق جديد.  حدد فريقًا لكي يتم استخدامه كبديل.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'إعادة تعيين',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'إعادة تعيين',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'هل تريد الاستمرار في تحديث السجلات المتأثرة لكي تستخدم فريقًا جديدًا؟',
    'LBL_REASSIGN_TABLE_INFO' => 'تحديث الجدول {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'اكتملت العملية بنجاح.',
    'LNK_LIST_TEAM' => 'الفرق',
    'LNK_LIST_TEAMNOTICE' => 'إشعارات الفريق',
    'LNK_NEW_TEAM' => 'إنشاء فريق',
    'LNK_NEW_TEAM_NOTICE' => 'إشعار إنشاء الفريق',
    'NTC_DELETE_CONFIRMATION' => 'هل تريد بالتأكيد حذف هذا السجل؟',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'هل تريد بالتأكيد حذف عضوية هذا المستخدم؟',
    'LBL_EDITLAYOUT' => 'تعديل المخطط' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'الإذون المستندة إلى الفريق',
    'LBL_TBA_CONFIGURATION_DESC' => 'قم بتمكين وصول الفريق، وإدارة الوصول بواسطة الوحدة.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'تمكين الإذون المستندة إلى الفريق',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'حدد الوحدات لتمكين',
    'LBL_TBA_CONFIGURATION_TITLE' => 'تمكين الإذون المستندة إلى الفريق يتيح لك تخصيص حقوق الوصول الخاصة بالفرق والمستخدمين للوحدات الفردية، من خلال "إدارة الأدوار".',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
سيؤدي تعطيل الإذون التي تستند إلى الفريق لوحدة ما إلى إرجاع أية بيانات مرتبطة بالإذون الخاصة بالفريق لتلك الوحدة، بما في ذلك، أي تعريفات عملية أو عمليات تستخدم الميزة. ويتضمن أي أدوار باستخدام خيار "المالك والفريق المحدد" لتلك الوحدة، وأي بيانات إذون خاصة بالفريق في هذه الوحدة. كما نوصي باستخدام أداة الإصلاح السريع وإعادة الإنشاء لمسح ذاكرة النظام المؤقتة بعد تعطيل الإذون الخاصة بالفريق لأي وحدة.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>تحذير:</strong> سيؤدي تعطيل الإذون التي تستند إلى الفريق لوحدة ما إلى إرجاع أية بيانات مرتبطة الإذون الخاصة بالفريق لتلك الوحدة، بما في ذلك، أي تعريفات عملية أو عمليات تستخدم الميزة. ويتضمن أي أدوار باستخدام خيار "المالك والفريق المحدد" لتلك الوحدة، وأي بيانات إذون خاصة بالفريق في هذه الوحدة. كما نوصي باستخدام أداة الإصلاح السريع وإعادة الإنشاء لمسح ذاكرة النظام المؤقتة بعد تعطيل الإذون الخاصة بالفريق لأي وحدة.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
سيؤدي تعطيل الإذون التي تستند إلى الفريق لوحدة ما إلى إرجاع أية بيانات مرتبطة بالإذون الخاصة بالفريق لتلك الوحدة، بما في ذلك، أي تعريفات عملية أو عمليات تستخدم الميزة. ويتضمن أي أدوار باستخدام خيار "المالك والفريق المحدد" لتلك الوحدة، وأي بيانات إذون خاصة بالفريق في هذه الوحدة. كما نوصي باستخدام أداة الإصلاح السريع وإعادة الإنشاء لمسح ذاكرة النظام المؤقتة بعد تعطيل الإذون الخاصة بالفريق لأي وحدة. إذا كنت لا تملك حق الوصول لاستخدام الإصلاح السريع وإعادة الإنشاء، فاتصل بالمسؤول الذي يملك حق الوصول إلى قائمة "إصلاح".
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>تحذير:</strong> سيؤدي تعطيل الإذون التي تستند إلى الفريق لوحدة ما إلى إرجاع أية بيانات مرتبطة بالإذون الخاصة بالفريق لتلك الوحدة، بما في ذلك، أي تعريفات عملية أو عمليات تستخدم الميزة. ويتضمن أي أدوار باستخدام خيار "المالك والفريق المحدد" لتلك الوحدة، وأي بيانات إذون خاصة بالفريق في هذه الوحدة. كما نوصي باستخدام أداة الإصلاح السريع وإعادة الإنشاء لمسح ذاكرة النظام المؤقتة بعد تعطيل الإذون الخاصة بالفريق لأي وحدة. إذا كنت لا تملك حق الوصول لاستخدام الإصلاح السريع وإعادة الإنشاء، فاتصل بالمسؤول الذي يملك حق الوصول إلى قائمة "إصلاح".
STR
,
);
