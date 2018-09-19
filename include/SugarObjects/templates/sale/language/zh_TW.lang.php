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
  'LBL_MODULE_NAME' => '銷售',
  'LBL_MODULE_TITLE' => '銷售：首頁',
  'LBL_SEARCH_FORM_TITLE' => '銷售搜尋',
  'LBL_VIEW_FORM_TITLE' => '銷售檢視',
  'LBL_LIST_FORM_TITLE' => '銷售清單',
  'LBL_SALE_NAME' => '銷售名稱：',
  'LBL_SALE' => '銷售：',
  'LBL_NAME' => '銷售名稱',
  'LBL_LIST_SALE_NAME' => '名稱',
  'LBL_LIST_ACCOUNT_NAME' => '帳戶名稱',
  'LBL_LIST_AMOUNT' => '金額',
  'LBL_LIST_DATE_CLOSED' => '關閉',
  'LBL_LIST_SALE_STAGE' => '銷售階段',
  'LBL_ACCOUNT_ID'=>'帳戶 ID',
  'LBL_TEAM_ID' =>'小組 ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => '銷售—貨幣更新',
  'UPDATE_DOLLARAMOUNTS' => '更新美元金額',
  'UPDATE_VERIFY' => '驗證金額',
  'UPDATE_VERIFY_TXT' => '驗證銷售中的金額數值是否為有效的十進位數字，僅包含數字字元 (0-9) 以及小數點 (.)',
  'UPDATE_FIX' => '修正金額',
  'UPDATE_FIX_TXT' => '透過在目前金額中建立有效小數點嘗試修正無效金額。所有修改后的金額在 amount_backup 資料庫欄位中備份。如果執行此資料庫並發現錯誤，則切勿在未透過備份還原的情況下重新執行此資料庫，因為新的無效資料可能會覆寫備份。',
  'UPDATE_DOLLARAMOUNTS_TXT' => '依據目前設定的貨幣匯率更新銷售的美元金額。此數值用於計算「圖表和清單檢視貨幣金額」。',
  'UPDATE_CREATE_CURRENCY' => '正在建立新貨幣：',
  'UPDATE_VERIFY_FAIL' => '記錄失敗驗證：',
  'UPDATE_VERIFY_CURAMOUNT' => '目前金額：',
  'UPDATE_VERIFY_FIX' => '「執行修正」將',
  'UPDATE_INCLUDE_CLOSE' => '包括「結束的記錄」',
  'UPDATE_VERIFY_NEWAMOUNT' => '新金額：',
  'UPDATE_VERIFY_NEWCURRENCY' => '新貨幣：',
  'UPDATE_DONE' => '已完成',
  'UPDATE_BUG_COUNT' => '已找到並嘗試解決的錯誤：',
  'UPDATE_BUGFOUND_COUNT' => '已找到的錯誤：',
  'UPDATE_COUNT' => '已更新的記錄：',
  'UPDATE_RESTORE_COUNT' => '已還原的記錄金額：',
  'UPDATE_RESTORE' => '還原金額',
  'UPDATE_RESTORE_TXT' => '透過在修正過程中建立的備份還原金額數值。',
  'UPDATE_FAIL' => '無法更新－',
  'UPDATE_NULL_VALUE' => '金額為空，將其設為 0－',
  'UPDATE_MERGE' => '合併貨幣',
  'UPDATE_MERGE_TXT' => '將多個貨幣合併為單個貨幣。如果同一貨幣存在多個貨幣記錄，則可同時合併這些記錄。此動作還會合併所有其他模組的貨幣。',
  'LBL_ACCOUNT_NAME' => '帳戶名稱：',
  'LBL_AMOUNT' => '金額：',
  'LBL_AMOUNT_USDOLLAR' => '金額（美元）：',
  'LBL_CURRENCY' => '貨幣：',
  'LBL_DATE_CLOSED' => '預計結束日期：',
  'LBL_TYPE' => '類型：',
  'LBL_CAMPAIGN' => '推廣活動：',
  'LBL_LEADS_SUBPANEL_TITLE' => '潛在客戶',
  'LBL_PROJECTS_SUBPANEL_TITLE' => '專案',  
  'LBL_NEXT_STEP' => '下一步：',
  'LBL_LEAD_SOURCE' => '潛在客戶來源：',
  'LBL_SALES_STAGE' => '銷售階段：',
  'LBL_PROBABILITY' => '可能性 (%)：',
  'LBL_DESCRIPTION' => '描述：',
  'LBL_DUPLICATE' => '可能重複的銷售',
  'MSG_DUPLICATE' => '您要建立的「銷售」記錄可能與已存在的銷售記錄重複。下面列出了包含類似名稱的「銷售」記錄。<br>按一下「儲存」繼續建立此新「銷售」，或按一下「取消」返回模組而不建立銷售。',
  'LBL_NEW_FORM_TITLE' => '建立銷售',
  'LNK_NEW_SALE' => '建立銷售',
  'LNK_SALE_LIST' => '銷售',
  'ERR_DELETE_RECORD' => '必須指定記錄編號才能刪除銷售。',
  'LBL_TOP_SALES' => '我的主要開放銷售',
  'NTC_REMOVE_OPP_CONFIRMATION' => '確定要從銷售中移除此連絡人嗎？',
	'SALE_REMOVE_PROJECT_CONFIRM' => '確定要從專案中移除此銷售嗎？',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'活動',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'歷史',
    'LBL_RAW_AMOUNT'=>'原始金額',


    'LBL_CONTACTS_SUBPANEL_TITLE' => '連絡人',
	'LBL_ASSIGNED_TO_NAME' => '使用者：',
	'LBL_LIST_ASSIGNED_TO_NAME' => '指派的使用者',
  'LBL_MY_CLOSED_SALES' => '我的結束銷售',
  'LBL_TOTAL_SALES' => '總銷售',
  'LBL_CLOSED_WON_SALES' => '結束並贏得客戶的銷售',
  'LBL_ASSIGNED_TO_ID' =>'已指派至 ID',
  'LBL_CREATED_ID'=>'按 ID 建立',
  'LBL_MODIFIED_ID'=>'按 ID 修改',
  'LBL_MODIFIED_NAME'=>'按使用者名稱修改',
  'LBL_SALE_INFORMATION'=>'銷售資訊',
  'LBL_CURRENCY_ID'=>'貨幣 ID',
  'LBL_CURRENCY_NAME'=>'貨幣名稱',
  'LBL_CURRENCY_SYMBOL'=>'貨幣符號',
  'LBL_EDIT_BUTTON' => '編輯',
  'LBL_REMOVE' => '移除',
  'LBL_CURRENCY_RATE' => '貨幣匯率',

);

