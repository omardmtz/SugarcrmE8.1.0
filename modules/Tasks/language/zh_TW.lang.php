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
  'LBL_TASKS_LIST_DASHBOARD' => '任務清單儀表板',

  'LBL_MODULE_NAME' => '工作',
  'LBL_MODULE_NAME_SINGULAR' => '工作',
  'LBL_TASK' => '工作：',
  'LBL_MODULE_TITLE' => ' 工作：首頁',
  'LBL_SEARCH_FORM_TITLE' => ' 工作搜尋',
  'LBL_LIST_FORM_TITLE' => ' 工作清單',
  'LBL_NEW_FORM_TITLE' => ' 建立工作',
  'LBL_NEW_FORM_SUBJECT' => '主題：',
  'LBL_NEW_FORM_DUE_DATE' => '到期日：',
  'LBL_NEW_FORM_DUE_TIME' => '到期時間：',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => '關閉',
  'LBL_LIST_SUBJECT' => '主題',
  'LBL_LIST_CONTACT' => '連絡人',
  'LBL_LIST_PRIORITY' => '優先順序',
  'LBL_LIST_RELATED_TO' => '關聯至',
  'LBL_LIST_DUE_DATE' => '期限',
  'LBL_LIST_DUE_TIME' => '到期時間',
  'LBL_SUBJECT' => '主題：',
  'LBL_STATUS' => '狀態：',
  'LBL_DUE_DATE' => '到期日：',
  'LBL_DUE_TIME' => '到期時間：',
  'LBL_PRIORITY' => '優先順序：',
  'LBL_COLON' => '：',
  'LBL_DUE_DATE_AND_TIME' => '到期日和時間：',
  'LBL_START_DATE_AND_TIME' => '開始日期和時間：',
  'LBL_START_DATE' => '開始日期：',
  'LBL_LIST_START_DATE' => '開始日期',
  'LBL_START_TIME' => '開始時間：',
  'LBL_LIST_START_TIME' => '開始時間',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => '無',
  'LBL_CONTACT' => '連絡人：',
  'LBL_EMAIL_ADDRESS' => '電子郵件地址：',
  'LBL_PHONE' => '電話：',
  'LBL_EMAIL' => '電子郵件地址：',
  'LBL_DESCRIPTION_INFORMATION' => '描述資訊',
  'LBL_DESCRIPTION' => '描述：',
  'LBL_NAME' => '名稱：',
  'LBL_CONTACT_NAME' => '連絡人姓名',
  'LBL_LIST_COMPLETE' => '完整：',
  'LBL_LIST_STATUS' => '狀態',
  'LBL_DATE_DUE_FLAG' => '無到期日',
  'LBL_DATE_START_FLAG' => '無開始日期',
  'ERR_DELETE_RECORD' => '您必須指定記錄編號才能刪除「連絡人」。',
  'ERR_INVALID_HOUR' => '請輸入介於 0 至 24 之間的小時數',
  'LBL_DEFAULT_PRIORITY' => '媒體',
  'LBL_LIST_MY_TASKS' => '我的開放工作',
  'LNK_NEW_TASK' => '建立工作',
  'LNK_TASK_LIST' => '檢視工作',
  'LNK_IMPORT_TASKS' => '匯入工作',
  'LBL_CONTACT_FIRST_NAME'=>'連絡人名字',
  'LBL_CONTACT_LAST_NAME'=>'連絡人姓氏',
  'LBL_LIST_ASSIGNED_TO_NAME' => '指派的使用者',
  'LBL_ASSIGNED_TO_NAME'=>'指派至：',
  'LBL_LIST_DATE_MODIFIED' => '修改日期',
  'LBL_CONTACT_ID' => '連絡人 ID：',
  'LBL_PARENT_ID' => '父代 ID：',
  'LBL_CONTACT_PHONE' => '連絡人電話：',
  'LBL_PARENT_NAME' => '父代類型：',
  'LBL_ACTIVITIES_REPORTS' => '活動報表',
  'LBL_EDITLAYOUT' => '編輯配置' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => '概觀',
  'LBL_HISTORY_SUBPANEL_TITLE' => '附註',
  'LBL_REVENUELINEITEMS' => '營收項目',
  //For export labels
  'LBL_DATE_DUE' => '到期日',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => '指派的使用者名稱',
  'LBL_EXPORT_ASSIGNED_USER_ID' => '指派的使用者 ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => '按 ID 修改',
  'LBL_EXPORT_CREATED_BY' => '按 ID 建立',
  'LBL_EXPORT_PARENT_TYPE' => '關聯至模組',
  'LBL_EXPORT_PARENT_ID' => '關聯至 ID',
  'LBL_TASK_CLOSE_SUCCESS' => '工作成功結束。',
  'LBL_ASSIGNED_USER' => '已指派至',

    'LBL_NOTES_SUBPANEL_TITLE' => '附註',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模組包含彈性行爲、待辦項目或其他需完成的活動類型。{{module_name}} 記錄可透過彈性關聯欄位關聯至大多數模組中的一個記錄，還可關聯至單個 {{contacts_singular_module}}。在 Sugar 中建立 {{plural_module_name}} 的方式多種多樣，比如透過 {{plural_module_name}} 模組、複製、匯入 {{plural_module_name}} 等。建立 {{module_name}} 記錄后 ，即可透過 {{plural_module_name}} 記錄檢視來檢視和編輯 {{module_name}} 相關資訊。視乎 {{module_name}} 的詳細資料而定，您還可透過「行事曆」模組檢視和編輯 {{module_name}} 資訊。各 {{module_name}} 記錄之後可關聯至其他 Sugar 記錄，比如 {{accounts_module}}、{{contacts_module}}、{{opportunities_module}} 等等。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 模組包含彈性動作、待辦項目或其他需完成的活動類型。

－透過按一下單個欄位或「編輯」按鈕，編輯此記錄的欄位。
－透過切換左下角窗格至「資料檢視」，檢視或修改子面板其他記錄的連結。
－透過切換左下角窗格至「活動流」，在 {{activitystream_singular_module}} 中執行和檢視使用者註解和記錄變更歷史。
－使用記錄名稱右側的圖示追蹤此記錄或將此記錄新增至我的最愛。
－編輯」按鈕右側的下拉式「動作」功能表提供其他動作選項。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} 模組包含彈性動作、待辦項目或其他需完成的活動類型。

建立 {{module_name}}：
1. 按需提供欄位值。
 - 標記為「必填」的欄位在儲存前必須先填寫完整。
 - 按一下「顯示更多」以顯示更多欄位（若需）。
2. 按一下「儲存」以完成新記錄並返回至上一頁。',

);
