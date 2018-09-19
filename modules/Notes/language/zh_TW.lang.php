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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => '筆記清單儀表板',

    'ERR_DELETE_RECORD' => '您必須指定記錄編號才能刪除「帳戶」。',
    'LBL_ACCOUNT_ID' => '帳戶 ID：',
    'LBL_CASE_ID' => '實例 ID：',
    'LBL_CLOSE' => '關閉：',
    'LBL_COLON' => '：',
    'LBL_CONTACT_ID' => '連絡人 ID：',
    'LBL_CONTACT_NAME' => '連絡人：',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '附註',
    'LBL_DESCRIPTION' => '描述',
    'LBL_EMAIL_ADDRESS' => '電子郵件地址：',
    'LBL_EMAIL_ATTACHMENT' => '電子郵件附件',
    'LBL_EMAIL_ATTACHMENT_FOR' => '電子郵件附件用於',
    'LBL_FILE_MIME_TYPE' => 'Mime 類型',
    'LBL_FILE_EXTENSION' => '副檔名',
    'LBL_FILE_SOURCE' => '檔案來源',
    'LBL_FILE_SIZE' => '檔案大小',
    'LBL_FILE_URL' => '檔案 URL',
    'LBL_FILENAME' => '附件：',
    'LBL_LEAD_ID' => '潛在客戶 ID：',
    'LBL_LIST_CONTACT_NAME' => '連絡人',
    'LBL_LIST_DATE_MODIFIED' => '上次修改',
    'LBL_LIST_FILENAME' => '附件',
    'LBL_LIST_FORM_TITLE' => '附註清單',
    'LBL_LIST_RELATED_TO' => '關聯至',
    'LBL_LIST_SUBJECT' => '主旨',
    'LBL_LIST_STATUS' => '狀態',
    'LBL_LIST_CONTACT' => '連絡人',
    'LBL_MODULE_NAME' => '筆記',
    'LBL_MODULE_NAME_SINGULAR' => '附註',
    'LBL_MODULE_TITLE' => '附註：首頁',
    'LBL_NEW_FORM_TITLE' => '建立附註或新增附件',
    'LBL_NEW_FORM_BTN' => '新增附註',
    'LBL_NOTE_STATUS' => '附註',
    'LBL_NOTE_SUBJECT' => '主題：',
    'LBL_NOTES_SUBPANEL_TITLE' => '附註與附件',
    'LBL_NOTE' => '注意：',
    'LBL_OPPORTUNITY_ID' => '商機 ID：',
    'LBL_PARENT_ID' => '父代 ID：',
    'LBL_PARENT_TYPE' => '父代類型',
    'LBL_EMAIL_TYPE' => '電子郵件類型',
    'LBL_EMAIL_ID' => '電子郵件ID',
    'LBL_PHONE' => '電話：',
    'LBL_PORTAL_FLAG' => '在「入口網站」中顯示？',
    'LBL_EMBED_FLAG' => '內嵌至電子郵件？',
    'LBL_PRODUCT_ID' => '報價明細項目 ID：',
    'LBL_QUOTE_ID' => '報價 ID：',
    'LBL_RELATED_TO' => '關聯至︰',
    'LBL_SEARCH_FORM_TITLE' => '附註搜尋',
    'LBL_STATUS' => '狀態',
    'LBL_SUBJECT' => '主題：',
    'LNK_IMPORT_NOTES' => '匯入筆記',
    'LNK_NEW_NOTE' => '建立筆記或附件',
    'LNK_NOTE_LIST' => '檢視附註',
    'LBL_MEMBER_OF' => '成員：',
    'LBL_LIST_ASSIGNED_TO_NAME' => '指派的使用者',
    'LBL_OC_FILE_NOTICE' => '請登入伺服器以檢視檔案',
    'LBL_REMOVING_ATTACHMENT' => '正在移除附件...',
    'ERR_REMOVING_ATTACHMENT' => '移除附件失敗...',
    'LBL_CREATED_BY' => '建立人',
    'LBL_MODIFIED_BY' => '修改人',
    'LBL_SEND_ANYWAYS' => '確定要傳送/儲存沒有主旨的電子郵件嗎？',
    'LBL_LIST_EDIT_BUTTON' => '編輯',
    'LBL_ACTIVITIES_REPORTS' => '活動報表',
    'LBL_PANEL_DETAILS' => '詳細資料',
    'LBL_NOTE_INFORMATION' => '概觀',
    'LBL_MY_NOTES_DASHLETNAME' => '我的附註',
    'LBL_EDITLAYOUT' => '編輯配置' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => '名字',
    'LBL_LAST_NAME' => '姓氏',
    'LBL_EXPORT_PARENT_TYPE' => '關聯至模組',
    'LBL_EXPORT_PARENT_ID' => '關聯至 ID',
    'LBL_DATE_ENTERED' => '建立日期',
    'LBL_DATE_MODIFIED' => '修改日期',
    'LBL_DELETED' => '已刪除',
    'LBL_REVENUELINEITEMS' => '營收項目',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模組由包含相關記錄有關的文字或附件之單個 {{plural_module_name}} 組成。{{module_name}} 記錄可透過彈性關聯欄位關聯至大多數模組的一個記錄，亦可關聯至單個 {{contacts_singular_module}}。{{plural_module_name}} 可保留記錄相關一般文字，或者甚至保留記錄有關附件。在 Sugar 中建立 {{plural_module_name}} 的方式多種多樣，比如透過 {{plural_module_name}} 模組、匯入 {{plural_module_name}}，透過「歷史」子面板等。建立 {{module_name}} 記錄后，即可透過 {{plural_module_name}} 記錄檢視來檢視和編輯 {{module_name}} 相關資訊。各 {{module_name}} 記錄之後可關聯其他 Sugar 記錄，比如 {{accounts_module}}、{{contacts_module}}、{{opportunities_module}} 等。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 模組由包含相關記錄有關的文字或附件之單個 {{plural_module_name}} 組成。

- 透過按一下單個欄位或「編輯」按鈕，編輯此記錄的欄位。
- 透過切換左下角窗格至「資料檢視」，檢視或修改子面板其他記錄的連結。
- 透過切換左下角窗格至「活動流」，在 {{activitystream_singular_module}} 中執行和檢視使用者註解和記錄變更歷史。
- 使用記錄名稱右側的圖示追蹤此記錄或將此記錄新增至我的最愛。
- 「編輯」按鈕右側的下拉式「動作」功能表提供其他動作選項。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '建立 {{module_name}}：
1. 按需提供欄位值。
 - 標記為「必填」的欄位在儲存前必須先填寫完整。
 - 按一下「顯示更多」以顯示更多欄位（若需）。
2. 按一下「儲存」以完成新記錄並返回至上一頁。',
);
