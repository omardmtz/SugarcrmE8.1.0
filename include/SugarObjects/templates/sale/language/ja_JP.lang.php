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
  'LBL_MODULE_NAME' => '商談',
  'LBL_MODULE_TITLE' => '商談: ホーム',
  'LBL_SEARCH_FORM_TITLE' => '商談検索',
  'LBL_VIEW_FORM_TITLE' => '商談を見る',
  'LBL_LIST_FORM_TITLE' => '商談一覧',
  'LBL_SALE_NAME' => '商談名:',
  'LBL_SALE' => '商談名:',
  'LBL_NAME' => '商談名',
  'LBL_LIST_SALE_NAME' => '名前',
  'LBL_LIST_ACCOUNT_NAME' => '取引先',
  'LBL_LIST_AMOUNT' => '金額',
  'LBL_LIST_DATE_CLOSED' => '完了',
  'LBL_LIST_SALE_STAGE' => '商談ステージ',
  'LBL_ACCOUNT_ID'=>'取引先ID',
  'LBL_TEAM_ID' =>'チームID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => '商談 - 通貨更新',
  'UPDATE_DOLLARAMOUNTS' => 'USドルの金額を更新',
  'UPDATE_VERIFY' => '金額を検証',
  'UPDATE_VERIFY_TXT' => '商談の金額が数字（0-9）と小数点（.）で正しく入力されているかどうかを検証します。',
  'UPDATE_FIX' => '金額を修正',
  'UPDATE_FIX_TXT' => '現在の金額から正しい区切り文字を使って不正な金額を修正します。修正された金額のバックアップはデータベースのamount_backupフィールドに保存されます。これを実行して問題が発生した場合、バックアップから以前の値を戻してください。さもないと、再度実行すると不正な値でバックアップ値が上書きされます。',
  'UPDATE_DOLLARAMOUNTS_TXT' => '商談のUSドルの更新は設定されている通貨レートに基づきます。グラフとリストビューの金額の値に反映されます。',
  'UPDATE_CREATE_CURRENCY' => '通貨作成:',
  'UPDATE_VERIFY_FAIL' => '確認に失敗しました:',
  'UPDATE_VERIFY_CURAMOUNT' => '現在の金額:',
  'UPDATE_VERIFY_FIX' => '修正は',
  'UPDATE_INCLUDE_CLOSE' => '完了したレコードを含む',
  'UPDATE_VERIFY_NEWAMOUNT' => '金額作成:',
  'UPDATE_VERIFY_NEWCURRENCY' => '通貨作成:',
  'UPDATE_DONE' => '完了',
  'UPDATE_BUG_COUNT' => '実行時に不具合が見つかりました:',
  'UPDATE_BUGFOUND_COUNT' => '不具合が見つかりました:',
  'UPDATE_COUNT' => 'レコードが更新されました:',
  'UPDATE_RESTORE_COUNT' => '金額がリストアされました:',
  'UPDATE_RESTORE' => '金額をリストア',
  'UPDATE_RESTORE_TXT' => '修復中にバックアップした内容をリストア',
  'UPDATE_FAIL' => '更新できません -',
  'UPDATE_NULL_VALUE' => '金額は空です。0に設定されました。  -',
  'UPDATE_MERGE' => '通貨を統合',
  'UPDATE_MERGE_TXT' => '複数の通貨を１つにまとめます。同じ通貨のレコードが複数ある場合、それらを１つにします。これは他のモジュールの通貨も統合します。',
  'LBL_ACCOUNT_NAME' => '取引先:',
  'LBL_AMOUNT' => '金額:',
  'LBL_AMOUNT_USDOLLAR' => '金額USD:',
  'LBL_CURRENCY' => '通貨:',
  'LBL_DATE_CLOSED' => '受注予定日:',
  'LBL_TYPE' => 'タイプ:',
  'LBL_CAMPAIGN' => 'キャンペーン:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'リード',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'プロジェクト',  
  'LBL_NEXT_STEP' => '次ステップ:',
  'LBL_LEAD_SOURCE' => 'リードソース:',
  'LBL_SALES_STAGE' => '商談ステージ:',
  'LBL_PROBABILITY' => '確率 (%):',
  'LBL_DESCRIPTION' => '詳細:',
  'LBL_DUPLICATE' => '重複の可能性がある商談',
  'MSG_DUPLICATE' => '作成しようとしている商談は既存の商談と重複する可能性があります。類似の商談は以下に表示されています。保存をクリックして新たに商談を作成するか、キャンセルをクリックして商談を作成せずにモジュールに戻ります。',
  'LBL_NEW_FORM_TITLE' => '商談作成',
  'LNK_NEW_SALE' => '商談作成',
  'LNK_SALE_LIST' => '商談',
  'ERR_DELETE_RECORD' => '商談を削除するにはレコード番号を指定する必要があります。',
  'LBL_TOP_SALES' => '交渉中の私の商談',
  'NTC_REMOVE_OPP_CONFIRMATION' => '本当にこの取引先担当者を商談からはずしてよろしいですか？',
	'SALE_REMOVE_PROJECT_CONFIRM' => '本当にこの商談をプロジェクトからはずしてよろしいですか？',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'活動',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'履歴',
    'LBL_RAW_AMOUNT'=>'金額',


    'LBL_CONTACTS_SUBPANEL_TITLE' => '取引先担当者',
	'LBL_ASSIGNED_TO_NAME' => 'アサイン先:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'アサイン先',
  'LBL_MY_CLOSED_SALES' => '私のクローズ済み商談',
  'LBL_TOTAL_SALES' => '商談総額',
  'LBL_CLOSED_WON_SALES' => '受注済み商談',
  'LBL_ASSIGNED_TO_ID' =>'アサイン先 ID',
  'LBL_CREATED_ID'=>'作成者ID',
  'LBL_MODIFIED_ID'=>'更新者ID',
  'LBL_MODIFIED_NAME'=>'更新者',
  'LBL_SALE_INFORMATION'=>'商談情報',
  'LBL_CURRENCY_ID'=>'通貨ID',
  'LBL_CURRENCY_NAME'=>'通貨名',
  'LBL_CURRENCY_SYMBOL'=>'通貨シンボル',
  'LBL_EDIT_BUTTON' => '編集',
  'LBL_REMOVE' => '削除',
  'LBL_CURRENCY_RATE' => '通貨レート',

);

