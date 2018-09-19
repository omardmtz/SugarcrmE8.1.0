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
    'ERR_ADD_RECORD' => '您必須指定記錄編號才能將使用者新增至該小組。',
    'ERR_DUP_NAME' => '「小組名稱」已存在，請選取另一個名稱。',
    'ERR_DELETE_RECORD' => '您必須指定記錄編號才能刪除該小組。',
    'ERR_INVALID_TEAM_REASSIGNMENT' => '錯誤。所選小組 <b>({0})</b> 是您已選擇刪除的小組。請選取另一個小組。',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => '錯誤。您無法刪除私人小組未刪除的使用者。',
    'LBL_DESCRIPTION' => '描述：',
    'LBL_GLOBAL_TEAM_DESC' => '全域可見',
    'LBL_INVITEE' => '小組成員',
    'LBL_LIST_DEPARTMENT' => '部門',
    'LBL_LIST_DESCRIPTION' => '描述',
    'LBL_LIST_FORM_TITLE' => '小組清單',
    'LBL_LIST_NAME' => '名稱',
    'LBL_FIRST_NAME' => '名字：',
    'LBL_LAST_NAME' => '姓氏：',
    'LBL_LIST_REPORTS_TO' => '報表發送對象',
    'LBL_LIST_TITLE' => '標題',
    'LBL_MODULE_NAME' => '小組',
    'LBL_MODULE_NAME_SINGULAR' => '小組',
    'LBL_MODULE_TITLE' => '小組：首頁',
    'LBL_NAME' => '小組名稱：',
    'LBL_NAME_2' => '小組名稱 (2)：',
    'LBL_PRIMARY_TEAM_NAME' => '主要小組名稱',
    'LBL_NEW_FORM_TITLE' => '新小組',
    'LBL_PRIVATE' => '私人',
    'LBL_PRIVATE_TEAM_FOR' => '私人小組：',
    'LBL_SEARCH_FORM_TITLE' => '私人小組：',
    'LBL_TEAM_MEMBERS' => '小組成員',
    'LBL_TEAM' => '小組：',
    'LBL_USERS_SUBPANEL_TITLE' => '使用者',
    'LBL_USERS' => '使用者',
    'LBL_REASSIGN_TEAM_TITLE' => '有記錄指派至以下小組：<b>{0}</b><br>刪除小組前，必須先將這些記錄重新指派至新小組。選取替換小組。',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => '重新指派',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => '重新指派',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => '開始更新受影響的記錄以使用新小組嗎？',
    'LBL_REASSIGN_TABLE_INFO' => '正在更新表格 {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => '作業已成功完成。',
    'LNK_LIST_TEAM' => '小組',
    'LNK_LIST_TEAMNOTICE' => '小組通知',
    'LNK_NEW_TEAM' => '建立小組',
    'LNK_NEW_TEAM_NOTICE' => '建立小組通知',
    'NTC_DELETE_CONFIRMATION' => '確定要刪除此記錄嗎？',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => '確定要移除該使用者\\的成員資格嗎？',
    'LBL_EDITLAYOUT' => '編輯配置' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => '小組許可',
    'LBL_TBA_CONFIGURATION_DESC' => '啟用團隊存取，並按照模組管理存取。',
    'LBL_TBA_CONFIGURATION_LABEL' => '啟用小組許可',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => '選取要啟用的模組',
    'LBL_TBA_CONFIGURATION_TITLE' => '啟用小組許可之後，您可透過角色管理向單個模組的小組和使用者指派特定存取權限。',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
停用一個模組的小組許可將會恢復該模組小組許可的所有相關資料，包括任何流程定義或使用該功能的流程。這包括所有在該模組使用「擁有者與選定團隊」選項的任何角色，以及該模組中所有用於記錄的小組許可資料。我們還建議您在停用任何模組的小組許可之後，使用快速修復和重建工具來清理您的系統快取。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>警告︰</strong>停用一個模組的小組許可將會恢復該模組小組許可的所有相關資料，包括任何流程定義或使用該功能的流程。這包括所有在該模組使用「擁有者與選定團隊」選項的任何角色，以及該模組中所有用於記錄的小組許可資料。我們還建議您在停用任何模組的小組許可之後，使用快速修復和重建工具來清理您的系統快取。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
停用一個模組的小組許可將會恢復該模組小組許可的所有相關資料，包括任何流程定義或使用該功能的流程。這包括所有在該模組使用「擁有者與選定團隊」選項的任何角色，以及該模組中所有用於記錄的小組許可資料。我們還建議您在停用任何模組的小組許可之後，使用快速修復和重建工具來清理您的系統快取。如果您無法取得快速修復或重建工具，請連絡擁有修復功能表存取權限的管理員。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>警告：</strong>停用一個模組的小組許可將會恢復該模組小組許可的所有相關資料，包括任何流程定義或使用該功能的流程。這包括所有在該模組使用「擁有者與選定團隊」選項的任何角色，以及該模組中所有用於記錄的小組許可資料。我們還建議您在停用任何模組的小組許可之後，使用快速修復和重建工具來清理您的系統快取。如果您無法取得快速修復或重建工具，請連絡擁有修復功能表存取權限的管理員。
STR
,
);
