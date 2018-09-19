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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'ワークフロー:',
  'LBL_MODULE_NAME_SINGULAR' => 'ワークフロー定義',
  'LBL_MODULE_ID' => 'ワークフロー',  
  'LBL_MODULE_TITLE' => 'ワークフロー: ホーム',
  'LBL_SEARCH_FORM_TITLE' => 'ワークフロー検索',
  'LBL_LIST_FORM_TITLE' => 'ワークフロー一覧',
  'LBL_NEW_FORM_TITLE' => 'ワークフロー作成',
  'LBL_LIST_NAME' => '名前',
  'LBL_LIST_TYPE' => '実行のタイミング',
  'LBL_LIST_BASE_MODULE' => '対象モジュール',
  'LBL_LIST_STATUS' => 'ステータス',
  'LBL_NAME' => '名前:',
  'LBL_DESCRIPTION' => '詳細:',
  'LBL_TYPE' => '実行のタイミング:',
  'LBL_STATUS' => 'ステータス:',
  'LBL_BASE_MODULE' => '対象モジュール:',
  'LBL_LIST_ORDER' => '処理の順番:',
  'LBL_FROM_NAME' => '送信者名:',
  'LBL_FROM_ADDRESS' => '送信者アドレス:',  
  'LNK_NEW_WORKFLOW' => 'ワークフロー作成',
  'LNK_WORKFLOW' => 'ワークフロー', 
  
  
  'LBL_ALERT_TEMPLATES' => '通知用Eメールテンプレート',
  'LBL_CREATE_ALERT_TEMPLATE' => '通知用Eメールテンプレート:',
  'LBL_SUBJECT' => '件名:',
  
  'LBL_RECORD_TYPE' => '適用レコード:',
 'LBL_RELATED_MODULE'=> '関連モジュール:',
  
  
  'LBL_PROCESS_LIST' => 'ワークフロー実行順',
	'LNK_ALERT_TEMPLATES' => '通知用Eメールテンプレート',
	'LNK_PROCESS_VIEW' => 'ワークフロー実行順',
  'LBL_PROCESS_SELECT' => 'モジュールを選択:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> '注意: このワークフローオブジェクトが機能するためにはトリガーを作成する必要があります。',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> '注意: 通知を送信するには管理メニュー＞Eメールの設定で通知をオンにしてください。',
  'LBL_FIRE_ORDER' => '処理の順番:',
  'LBL_RECIPIENTS' => '受信者',
  'LBL_INVITEES' => '参加者',
  'LBL_INVITEE_NOTICE' => '要注意: これを作成するには少なくとも１人の参加者を選択してください。',
  'NTC_REMOVE_ALERT' => '本当にこのワークフローをはずしてよいですか？',
  'LBL_EDIT_ALT_TEXT' => '代替テキスト',
  'LBL_INSERT' => '挿入',
  'LBL_SELECT_OPTION' => 'オプションを選択してください。',
  'LBL_SELECT_VALUE' => '値を選択してください。',
  'LBL_SELECT_MODULE' => '関連モジュールを選択してください。',
  'LBL_SELECT_FILTER' => '関連モジュールを絞り込むフィールドを選択してください。',
  'LBL_LIST_UP' => '上へ',
  'LBL_LIST_DN' => '下へ',
  'LBL_SET' => '*',
  'LBL_AS' => 'フィールドに次の値を保存：',
  'LBL_SHOW' => '表示',
  'LBL_HIDE' => '非表示',
  'LBL_SPECIFIC_FIELD' => '個別フィールド',
  'LBL_ANY_FIELD' => '任意のフィールド',
  'LBL_LINK_RECORD'=>'レコードにリンク',
  'LBL_INVITE_LINK'=>'会議/電話招待リンク',
  'LBL_PLEASE_SELECT'=>'選択してください',
  'LBL_BODY'=>'本文:',
  'LBL__S'=>'&amp;#39;s',
  'LBL_ALERT_SUBJECT'=>'ワークフロー通知',
  'LBL_ACTION_ERROR'=>'このアクションは実行されません。すべてのフィールドと値が正当になるようにアクションを編集してください。',
  'LBL_ACTION_ERRORS'=>'注意: １つ以上のアクションがエラーを含んでいます。',
  'LBL_ALERT_ERROR'=>'この通知は実行されません。すべての設定が正当になるように通知を編集してください。',
  'LBL_ALERT_ERRORS'=>'注意: １つ以上の通知にエラーが含まれています。',
  'LBL_TRIGGER_ERROR'=>'注意: このトリガーは不正な値が含まれるため、実行されません。',
  'LBL_TRIGGER_ERRORS'=>'注意: １つ以上のトリガーにエラーが含まれています。',
  'LBL_UP' => '上へ' /*for 508 compliance fix*/,
  'LBL_DOWN' => '下へ' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'レイアウト編集' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'ワークフロー'),
  'LBL_EMAILTEMPLATES_TYPE' => 'タイプ',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Note:</strong> The Sugar Workflow and Workflow Management functionality will be removed in a future release of Sugar. Sugar Enterprise edition customers should begin to use the functionality provided by Sugar Advanced Workflow. Click <a href="http://www.sugarcrm.com/wf-eol" target="_blank">here</a> for more information.',
);

