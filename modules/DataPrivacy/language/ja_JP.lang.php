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
    'LBL_MODULE_NAME' => 'データプライバシー',
    'LBL_MODULE_NAME_SINGULAR' => 'データプライバシー',
    'LBL_NUMBER' => '番号',
    'LBL_TYPE' => 'タイプ',
    'LBL_SOURCE' => 'ソース',
    'LBL_REQUESTED_BY' => '要求者',
    'LBL_DATE_OPENED' => '開かれた日付',
    'LBL_DATE_DUE' => '期限日',
    'LBL_DATE_CLOSED' => '閉じられた日付',
    'LBL_BUSINESS_PURPOSE' => '以下のことに同意したビジネス目的:',
    'LBL_LIST_NUMBER' => '番号',
    'LBL_LIST_SUBJECT' => '件名',
    'LBL_LIST_PRIORITY' => '優先度',
    'LBL_LIST_STATUS' => 'ステータス',
    'LBL_LIST_TYPE' => 'タイプ',
    'LBL_LIST_SOURCE' => 'ソース',
    'LBL_LIST_REQUESTED_BY' => '要求者',
    'LBL_LIST_DATE_DUE' => '期限日',
    'LBL_LIST_DATE_CLOSED' => '閉じられた日付',
    'LBL_LIST_DATE_MODIFIED' => '更新日',
    'LBL_LIST_MODIFIED_BY_NAME' => '更新者',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'アサインされたユーザー',
    'LBL_SHOW_MORE' => 'その他のデータプライバシーアクティビティを表示',
    'LNK_DATAPRIVACY_LIST' => 'データプライバシーアクティビティを表示',
    'LNK_NEW_DATAPRIVACY' => 'データ プライバシーアクティビティを作成',
    'LBL_LEADS_SUBPANEL_TITLE' => 'リード',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '取引先担当者',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'ターゲット',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => '取引先',
    'LBL_LISTVIEW_FILTER_ALL' => 'すべてのデータプライバシーアクティビティ',
    'LBL_ASSIGNED_TO_ME' => '私のデータプライバシーアクティビティ',
    'LBL_SEARCH_AND_SELECT' => 'データプライバシーアクティビティを検索して選択',
    'TPL_SEARCH_AND_ADD' => 'データプライバシーアクティビティを検索して追加',
    'LBL_WARNING_ERASE_CONFIRM' => '{0}フィールドを永続的に消去しようとしています。消去が完了した後、このデータを回復するオプションはありません。本当に続行してよいですか？',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => '{0} フィールドに消去とマークされています。確定すると、消去が中止され、すべてのデータが保持され、この要求が拒否とマークされます。本当に続行してよいですか？',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'この要求を完了とマークしようとしています。これによってステータスが永続的に完了と設定され、再度開けなくなります。本当に続行してよいですか？',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'この要求を拒否とマークしようとしています。これによってステータスが永続的に拒否と設定され、再度開けなくなります。本当に続行してよいですか？',
    'LBL_RECORD_SAVED_SUCCESS' => 'データプライバシーアクティビティ<a href="#{{buildRoute model=this}}">{{name}}</a>の作成に成功しました。', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => '拒否',
    'LBL_COMPLETE_BUTTON_LABEL' => '完了',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => '消去して完了',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'サブパネルで選択されたフィールドを消去',
    'LBL_COUNT_FIELDS_MARKED' => '消去するようにマークされたフィールド',
    'LBL_NO_RECORDS_MARKED' => '消去するようにマークされたフィールドまたはレコードがありません。',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'データプライバシーレコードダッシュ ボード',

    // list view
    'LBL_HELP_RECORDS' => 'データプライバシーモジュールは、組織のプライバシー手続きをサポートするために、同意および件名要求を含めてプライバシーアクティビティを追跡します。プライバシー要求に対する同意を追跡したりアクションを取ったりするために、個々のレコード (取引先担当者など) に関連するデータプライバシーレコードを作成してください。',
    // record view
    'LBL_HELP_RECORD' => 'データプライバシーモジュールは、組織のプライバシー手続きをサポートするために、同意および件名要求を含めてプライバシーアクティビティを追跡します。プライバシー要求に対する同意を追跡したりアクションを取ったりするために、個々のレコード (取引先担当者など) に関連するデータプライバシーレコードを作成してください。必要なアクションが完了したら、データプライバシーマネージャー役割のユーザは「完了」または「拒否」をクリックしてステータスを更新できます。

消去要求の場合は、下のサブパネル内でリストされた個々のレコードごとに「消去するようにマーク」を選択してください。目的のフィールドがすべて選択されたら、「消去して完了」をクリックすると、フィールドの値が永続的に削除されて、データプライバシーレコードが完了とマークされます。',
);
