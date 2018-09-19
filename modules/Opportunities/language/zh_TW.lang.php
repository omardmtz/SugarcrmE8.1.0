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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => '機會清單儀表板',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => '機會記錄儀表板',

    'LBL_MODULE_NAME' => '商機',
    'LBL_MODULE_NAME_SINGULAR' => '商機',
    'LBL_MODULE_TITLE' => '商機：首頁',
    'LBL_SEARCH_FORM_TITLE' => '商機搜尋',
    'LBL_VIEW_FORM_TITLE' => '商機檢視',
    'LBL_LIST_FORM_TITLE' => '商機清單',
    'LBL_OPPORTUNITY_NAME' => '商機名稱：',
    'LBL_OPPORTUNITY' => '商機：',
    'LBL_NAME' => '商機名稱',
    'LBL_INVITEE' => '連絡人',
    'LBL_CURRENCIES' => '貨幣：',
    'LBL_LIST_OPPORTUNITY_NAME' => '名稱',
    'LBL_LIST_ACCOUNT_NAME' => '帳戶名稱',
    'LBL_LIST_DATE_CLOSED' => '預計結束日期',
    'LBL_LIST_AMOUNT' => '可能',
    'LBL_LIST_AMOUNT_USDOLLAR' => '已轉換金額',
    'LBL_ACCOUNT_ID' => '帳戶 ID',
    'LBL_CURRENCY_RATE' => '貨幣匯率',
    'LBL_CURRENCY_ID' => '貨幣 ID',
    'LBL_CURRENCY_NAME' => '貨幣名稱',
    'LBL_CURRENCY_SYMBOL' => '貨幣符號',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => '商機－貨幣更新',
    'UPDATE_DOLLARAMOUNTS' => '更新美元金額',
    'UPDATE_VERIFY' => '驗證金額',
    'UPDATE_VERIFY_TXT' => '驗證「商機」中的金額數值是否為有效的十進位數字，僅包含數字字元 (0-9) 以及小數點 (.)',
    'UPDATE_FIX' => '修正金額',
    'UPDATE_FIX_TXT' => '透過在目前金額中建立有效小數點嘗試修正無效金額。所有修改后的金額在 amount_backup 資料庫欄位中備份。如果執行此資料庫並發現錯誤，則切勿在未透過備份還原的情況下重新執行此資料庫，因為新的無效資料可能會覆寫備份。',
    'UPDATE_DOLLARAMOUNTS_TXT' => '依據目前設定的「貨幣」匯率更新「商機」的美元金額。此數值用於計算「圖表和清單檢視貨幣金額」。',
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
    'UPDATE_MERGE_TXT' => '將多個「貨幣」合併為單個「貨幣」。如果同一「貨幣」存在多個「貨幣」記錄，則可同時合併這些記錄。此動作還會合併所有其他模組的「貨幣」。',
    'LBL_ACCOUNT_NAME' => '帳戶名稱：',
    'LBL_CURRENCY' => '貨幣：',
    'LBL_DATE_CLOSED' => '預計結束日期：',
    'LBL_DATE_CLOSED_TIMESTAMP' => '預計結束日期時間戳記',
    'LBL_TYPE' => '類型：',
    'LBL_CAMPAIGN' => '推廣活動：',
    'LBL_NEXT_STEP' => '下一步：',
    'LBL_LEAD_SOURCE' => '潛在客戶來源',
    'LBL_SALES_STAGE' => '銷售階段',
    'LBL_SALES_STATUS' => '狀態',
    'LBL_PROBABILITY' => '可能性 (%)',
    'LBL_DESCRIPTION' => '描述',
    'LBL_DUPLICATE' => '可能重複的商機',
    'MSG_DUPLICATE' => '您將建立的「商機」記錄可能是已存在「商機」記錄的重複。以下列出了包含類似名稱的「商機」記錄。<br>按一下「儲存」繼續建立此新「商機」，或者按一下「取消」返回模組，而不建立「商機」。',
    'LBL_NEW_FORM_TITLE' => '建立商機',
    'LNK_NEW_OPPORTUNITY' => '建立商機',
    'LNK_CREATE' => '建立交易',
    'LNK_OPPORTUNITY_LIST' => '檢視商機',
    'ERR_DELETE_RECORD' => '必須指定記錄編號才能刪除「商機」。',
    'LBL_TOP_OPPORTUNITIES' => '我的主要開放商機',
    'NTC_REMOVE_OPP_CONFIRMATION' => '確定要從「商機」中移除此「連絡人」嗎？',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => '確定要從專案中移除此「商機」嗎？',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '商機',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => '活動',
    'LBL_HISTORY_SUBPANEL_TITLE' => '歷史',
    'LBL_RAW_AMOUNT' => '原始金額',
    'LBL_LEADS_SUBPANEL_TITLE' => '潛在客戶',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '連絡人',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => '文件',
    'LBL_PROJECTS_SUBPANEL_TITLE' => '專案',
    'LBL_ASSIGNED_TO_NAME' => '指派至：',
    'LBL_LIST_ASSIGNED_TO_NAME' => '指派的使用者',
    'LBL_LIST_SALES_STAGE' => '銷售階段',
    'LBL_MY_CLOSED_OPPORTUNITIES' => '我的已結束商機',
    'LBL_TOTAL_OPPORTUNITIES' => '總商機',
    'LBL_CLOSED_WON_OPPORTUNITIES' => '結束並贏得客戶的商機',
    'LBL_ASSIGNED_TO_ID' => '指派的使用者：',
    'LBL_CREATED_ID' => '按 ID 建立',
    'LBL_MODIFIED_ID' => '按 ID 修改',
    'LBL_MODIFIED_NAME' => '按使用者名稱修改',
    'LBL_CREATED_USER' => '已建立使用者',
    'LBL_MODIFIED_USER' => '已修改使用者',
    'LBL_CAMPAIGN_OPPORTUNITY' => '推廣活動商機',
    'LBL_PROJECT_SUBPANEL_TITLE' => '專案',
    'LABEL_PANEL_ASSIGNMENT' => '指派',
    'LNK_IMPORT_OPPORTUNITIES' => '匯入商機',
    'LBL_EDITLAYOUT' => '編輯配置' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => '推廣活動 ID',
    'LBL_OPPORTUNITY_TYPE' => '商機類型',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => '指派的使用者名稱',
    'LBL_EXPORT_ASSIGNED_USER_ID' => '指派的使用者 ID',
    'LBL_EXPORT_MODIFIED_USER_ID' => '按 ID 修改',
    'LBL_EXPORT_CREATED_BY' => '按 ID 建立',
    'LBL_EXPORT_NAME' => '名稱',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => '關聯連絡人電子郵件',
    'LBL_FILENAME' => '附件',
    'LBL_PRIMARY_QUOTE_ID' => '主報價',
    'LBL_CONTRACTS' => '合約',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => '合約',
    'LBL_PRODUCTS' => '報價項目',
    'LBL_RLI' => '營收項目',
    'LNK_OPPORTUNITY_REPORTS' => '檢視商機報表',
    'LBL_QUOTES_SUBPANEL_TITLE' => '報價',
    'LBL_TEAM_ID' => '小組 ID',
    'LBL_TIMEPERIODS' => '時間週期',
    'LBL_TIMEPERIOD_ID' => '時間週期 ID',
    'LBL_COMMITTED' => '已提交',
    'LBL_FORECAST' => '包括於預測中',
    'LBL_COMMIT_STAGE' => '提交階段',
    'LBL_COMMIT_STAGE_FORECAST' => '預測',
    'LBL_WORKSHEET' => '工作表',

    'TPL_RLI_CREATE' => '「商機」必須擁有一個關聯「營收項目」。',
    'TPL_RLI_CREATE_LINK_TEXT' => '建立「營收項目」。',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => '報價項目',
    'LBL_RLI_SUBPANEL_TITLE' => '營收項目',

    'LBL_TOTAL_RLIS' => '「總營收項目」中的 #',
    'LBL_CLOSED_RLIS' => '「總營收項目」中的 #',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => '您無法刪除包含結束「營收項目」的「商機」',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => '一個或多個所選記錄包含結束「營收項目」，無法刪除。',
    'LBL_INCLUDED_RLIS' => '「所包含營收項目」中的 #',

    'LBL_QUOTE_SUBPANEL_TITLE' => '報價',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => '商機階層',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => '將結果「商機」記錄的「預計結束日期」欄位設為現有「營收項目」的最早或最晚結束日期',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => '案源總數為',

    'LBL_OPPORTUNITY_ROLE'=>'商機角色',
    'LBL_NOTES_SUBPANEL_TITLE' => '附註',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => '按一下「確認」，將會清除「所有預測」資料並變更「商機檢視」。如果您不無需執行此動作，則按一下取消返回之前的設定。',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        '按一下確認，您將清除所有預測資料，並更改您的商機檢視表。'
        .'同時還將停用所有附帶營收項目目標模組的流程定義。'
        .'如果您不打算這樣做，按一下取消返回上一設定。',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => '如果所有「營收項目」已結束而且已贏得至少一個，',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => '則「商機銷售階段」設為「結束並贏得客戶」。',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => '如果所有「營收項目」處於「結束但客戶流失」銷售階段，',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => '則「商機銷售階段」設為「結束但客戶流失」。',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => '如果有「營收項目」仍然開放，',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => '則「商機」會標記為最低級的「銷售階段」。',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => '在您開始此變更后，「營收項目」摘要附註會在後台進行建立。在附註完成且可用后，會向您使用者設定檔上的電子郵件地址傳送通知。如果實例為 {{forecasts_module}} 而設定，則在 {{module_name}} 記錄同步至 {{forecasts_module}} 模組且新 {{forecasts_module}} 可用該記錄時，Sugar 還會向您發送通知。請注意，實例必須設定為透過「管理」>「電子郵件設定」傳送電子郵件，以便能夠傳送通知。',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => '在您開始此變更后，會在後台建立各現有 {{module_name}} 的「營收項目」記錄。在「營收項目」完成且可用后，會向您使用者設定檔上的電子郵件地址傳送通知。請注意，實例必須設定為透過「管理」>「電子郵件設定」傳送電子郵件，以便能夠傳送通知。',
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模組允許您全程追蹤個人銷售。每個 {{module_name}} 紀錄都代表一次未来的銷售，包括相關銷售數據，且和 {{quotes_module}}、{{contacts_module}} 等其他重要紀錄相關。{{module_name}} 通常經過幾個銷售階段實現進步，直到被標記為“談成结束”或“丢單结束”。甚至可以使用 Sugar 的 {{forecasts_singular_module}}ing 模組進一步調節{{plural_module_name}}，以便理解和預測銷售趨勢，以及集中精力達成銷售定額。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 模組允許您全程追蹤個人銷售和這些銷售的明細項目。每個 {{module_name}} 紀錄都代表一次未来的銷售，包括相關銷售數據，且和 {{quotes_module}}、{{contacts_module}} 等其他重要紀錄相關。

- 通過點擊單個字段或“編輯”按鈕來編輯此紀錄的字段。
- 通過切換左下角窗格至“數據視圖”來查看或修改子面板中其他紀錄的連結。
- 通過切換左下角窗格至“活動流”在{{activitystream_singular_module}} 中撰寫和查看用戶評論和紀錄更改歷史。
- 使用紀錄名稱右側的圖標關注此紀錄或將此紀錄新增至收藏夾。
- “編輯”按鈕右側的下拉“操作”菜單提供其他操作選項。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} 模組允許您全程追蹤個人銷售和這些銷售的明細項目。每個 {{module_name}} 紀錄都代表一次未來的銷售，包括相關銷售數據，且和 {{quotes_module}}、{{contacts_module}} 等其他重要紀錄相關。

若要創建 {{module_name}}：
1. 按需提供字段值。
 - 標記為“必填”的字段在保存前必須先填寫完整。
 - 如有需要，點擊“顯示更多”以顯示其他字段。
2. 點擊“保存”以完成新紀錄，並返回至上一頁。',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => '同步至 Marketo&reg;',
    'LBL_MKTO_ID' => 'Marketo 潛在客戶 ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => '前 10 個銷售商機',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => '在泡泡圖中顯示前十個商機。',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => '我的商機',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "我的小組的商機",
);
