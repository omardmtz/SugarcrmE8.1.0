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
  'LBL_TASKS_LIST_DASHBOARD' => 'タスク一覧のダッシュボード',

  'LBL_MODULE_NAME' => 'タスク',
  'LBL_MODULE_NAME_SINGULAR' => 'タスク',
  'LBL_TASK' => 'タスク:',
  'LBL_MODULE_TITLE' => 'タスク: ホーム',
  'LBL_SEARCH_FORM_TITLE' => 'タスク検索',
  'LBL_LIST_FORM_TITLE' => 'タスク一覧',
  'LBL_NEW_FORM_TITLE' => 'タスク作成',
  'LBL_NEW_FORM_SUBJECT' => '件名:',
  'LBL_NEW_FORM_DUE_DATE' => '期限日:',
  'LBL_NEW_FORM_DUE_TIME' => '期限時間:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => '完了',
  'LBL_LIST_SUBJECT' => '件名',
  'LBL_LIST_CONTACT' => '取引先担当者',
  'LBL_LIST_PRIORITY' => '優先度',
  'LBL_LIST_RELATED_TO' => '関連先',
  'LBL_LIST_DUE_DATE' => '期限日',
  'LBL_LIST_DUE_TIME' => '期限時間',
  'LBL_SUBJECT' => '件名:',
  'LBL_STATUS' => 'ステータス:',
  'LBL_DUE_DATE' => '期限日:',
  'LBL_DUE_TIME' => '期限時間:',
  'LBL_PRIORITY' => '優先度:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => '期限日時:',
  'LBL_START_DATE_AND_TIME' => '開始日時:',
  'LBL_START_DATE' => '開始日:',
  'LBL_LIST_START_DATE' => '実施日',
  'LBL_START_TIME' => '開始時間:',
  'LBL_LIST_START_TIME' => '開始時間',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => 'なし',
  'LBL_CONTACT' => '取引先担当者:',
  'LBL_EMAIL_ADDRESS' => 'Eメールアドレス:',
  'LBL_PHONE' => '電話:',
  'LBL_EMAIL' => 'Eメール:',
  'LBL_DESCRIPTION_INFORMATION' => '詳細情報',
  'LBL_DESCRIPTION' => '詳細:',
  'LBL_NAME' => '名前:',
  'LBL_CONTACT_NAME' => '取引先担当者名',
  'LBL_LIST_COMPLETE' => '完了:',
  'LBL_LIST_STATUS' => 'ステータス',
  'LBL_DATE_DUE_FLAG' => '期限日なし',
  'LBL_DATE_START_FLAG' => '開始日なし',
  'ERR_DELETE_RECORD' => '取引先担当者を削除するにはレコード番号を指定してください。',
  'ERR_INVALID_HOUR' => '時間の項目には0から24までの値を入力してください。',
  'LBL_DEFAULT_PRIORITY' => '中',
  'LBL_LIST_MY_TASKS' => '私の未完了タスク',
  'LNK_NEW_TASK' => 'タスク作成',
  'LNK_TASK_LIST' => 'タスク一覧',
  'LNK_IMPORT_TASKS' => 'タスクのインポート',
  'LBL_CONTACT_FIRST_NAME'=>'取引先担当者の名',
  'LBL_CONTACT_LAST_NAME'=>'取引先担当者の姓',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'アサイン先',
  'LBL_ASSIGNED_TO_NAME'=>'アサイン先:',
  'LBL_LIST_DATE_MODIFIED' => '更新日',
  'LBL_CONTACT_ID' => '取引先担当者ID:',
  'LBL_PARENT_ID' => '親ID:',
  'LBL_CONTACT_PHONE' => '取引先担当者電話:',
  'LBL_PARENT_NAME' => '親タイプ:',
  'LBL_ACTIVITIES_REPORTS' => 'アクティビティレポート',
  'LBL_EDITLAYOUT' => 'レイアウト編集' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'タスクの概要',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'メモ',
  'LBL_REVENUELINEITEMS' => '商談品目',
  //For export labels
  'LBL_DATE_DUE' => '期限日',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'アサイン先',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'アサイン先ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => '更新者ID',
  'LBL_EXPORT_CREATED_BY' => '作成者ID',
  'LBL_EXPORT_PARENT_TYPE' => 'モジュールに関連',
  'LBL_EXPORT_PARENT_ID' => 'IDに関連',
  'LBL_TASK_CLOSE_SUCCESS' => 'タスクのクローズに成功しました。',
  'LBL_ASSIGNED_USER' => 'アサイン先',

    'LBL_NOTES_SUBPANEL_TITLE' => 'メモ',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} モジュールは柔軟なアクションや、to-doアイテムやその他の完了を必要とするアクティビティのタイプにより構成されております。 {{module_name}} レコードはフレックス関連フィールドを通してほとんどのモジュールと関連付けすることができますし、単一の{{contacts_singular_module}} に関連付けすることもできます。Sugarで{{plural_module_name}} を作成するには様々な方法があり、{{plural_module_name}} モジュール経由にて, 複製により, または{{plural_module_name}} をインポートすることによりそうできます。 {{module_name}} レコードが作成されたら、{{plural_module_name}} レコードの表示を経由して {{module_name}} に関連する情報を表示したり、編集することができます。 {{module_name}} の詳細に応じて、またカレンダーモジュールを介して {{module_name}} の情報を表示したり、編集することができます。各 {{module_name}} レコードは、そのような{{accounts_module}}、{{contacts_module}}、{{opportunities_module}}、およびその他多くの他のSugarのレコードに関連付けできます。',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'このレコードビューは、個々のレコードだけでなく、それにリンクされたレコードのいくつかの詳細についての十分な情報を提供しています。 - 個々のフィールドまたは「編集」ボタンをクリックして、このレコードのフィールドを編集します。 - 左下のペインに「データビュー」をトグルしてサブパネル内の他のレコードへのリンクを表示または変更してください。 - 左下のペインに「アクティビティストリーム」をトグルすることによってレコードの変更履歴を表示したりユーザーコメントを作成してください。 - レコード名の右にあるアイコンを使用して、このレコードをフォローするかお気に入りにしてください。 - 追加のアクションは、「編集」ボタンの右にあるドロップダウンの「操作」メニューにあります。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} モジュールは柔軟なアクション、to-doアイテムやその他の完了を必要とするアクティビティのタイプにより構成されています。

{{module_name}} を作成するには：
1. 必要に応じてフィールドの値を指定します。
- 「必須」フィールドは保存前に入力完了してください。
- 必要に応じて、追加のフィールドを展開する「更に表示」をクリックします。 
2. 新しいレコードを確定し、前のページに戻るには「保存」をクリックします。',

);
