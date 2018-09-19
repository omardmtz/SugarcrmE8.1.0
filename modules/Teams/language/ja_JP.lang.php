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
    'ERR_ADD_RECORD' => 'このチームにユーザを追加するにはレコード番号を指定してください。',
    'ERR_DUP_NAME' => 'チーム名が既に存在します。別の名前を選択してください。',
    'ERR_DELETE_RECORD' => 'このチームを削除するにはレコード番号を指定する必要があります。',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'エラー。選択されたチーム<b>({0})</b>は削除するチームです。他のチームを選択してください。',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'エラー。プライベートチームが削除されていないユーザを削除することはできません。',
    'LBL_DESCRIPTION' => '詳細:',
    'LBL_GLOBAL_TEAM_DESC' => 'グローバル表示',
    'LBL_INVITEE' => 'チームメンバー',
    'LBL_LIST_DEPARTMENT' => '部署',
    'LBL_LIST_DESCRIPTION' => '詳細',
    'LBL_LIST_FORM_TITLE' => 'チーム一覧',
    'LBL_LIST_NAME' => '名前',
    'LBL_FIRST_NAME' => '名:',
    'LBL_LAST_NAME' => '姓:',
    'LBL_LIST_REPORTS_TO' => '上司',
    'LBL_LIST_TITLE' => '職位',
    'LBL_MODULE_NAME' => 'チーム',
    'LBL_MODULE_NAME_SINGULAR' => 'チーム',
    'LBL_MODULE_TITLE' => 'チーム: ホーム',
    'LBL_NAME' => 'チーム名:',
    'LBL_NAME_2' => 'チーム名（2）:',
    'LBL_PRIMARY_TEAM_NAME' => '主たるチーム名',
    'LBL_NEW_FORM_TITLE' => 'チーム作成',
    'LBL_PRIVATE' => 'プライベート',
    'LBL_PRIVATE_TEAM_FOR' => 'プライベートチーム:',
    'LBL_SEARCH_FORM_TITLE' => 'チーム検索',
    'LBL_TEAM_MEMBERS' => 'チームメンバー',
    'LBL_TEAM' => 'チーム:',
    'LBL_USERS_SUBPANEL_TITLE' => 'ユーザ',
    'LBL_USERS' => 'ユーザ',
    'LBL_REASSIGN_TEAM_TITLE' => '次のチームにアサインされたレコードがあります。: <b>{0}</b><br>チームを削除する前に、これらのレコードを他のチームにアサインする必要があります。代替チームを選択してください。',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => '再アサイン',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => '再アサイン [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => '新しいチームにレコードをアサインしてよいですか？',
    'LBL_REASSIGN_TABLE_INFO' => '{0} テーブルを更新中',
    'LBL_REASSIGN_TEAM_COMPLETED' => '処理が正常に終了しました。',
    'LNK_LIST_TEAM' => 'チーム',
    'LNK_LIST_TEAMNOTICE' => 'チームへの連絡一覧',
    'LNK_NEW_TEAM' => 'チーム作成',
    'LNK_NEW_TEAM_NOTICE' => 'チームへの連絡作成',
    'NTC_DELETE_CONFIRMATION' => '本当にこのレコードを削除してよいですか？',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => '本当にこの従業員をメンバーからはずしてよいですか？',
    'LBL_EDITLAYOUT' => 'レイアウト編集' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'チームベースのパーミッション',
    'LBL_TBA_CONFIGURATION_DESC' => 'チームアクセスを有効化してモジュール別にアクセスを管理します。',
    'LBL_TBA_CONFIGURATION_LABEL' => 'チーム ベースのパーミッションの有効化',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => '有効化するモジュールの選択',
    'LBL_TBA_CONFIGURATION_TITLE' => 'チームベースのパーミッションを有効化すると、役割管理を使用して個別のモジュールのチームやユーザーに特定のアクセス権限を割り当てることができます。',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
モジュールのチームベースのパーミッションを無効にすると、そのモジュールのチームベースのパーミッションに関連付けられているデータ (例: この機能を使用したプロセスの定義やプロセス) が元に戻されます。これには、そのモジュールの「所有者および選択したチーム」オプションを使用したロールや、そのモジュールのレコードに関連するチームベースのパーミッションデータが含まれます。
モジュールのチームベースのパーミッションを無効化した後には、クイックリペア＆再構築ツールを使用してシステムのキャッシュをクリアにすることを推奨します。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>警告:</strong> モジュールのチームベースのパーミッションを無効にすると、そのモジュールのチームベースのパーミッションに関連付けられているデータ (例: この機能を使用したプロセスの定義やプロセス) が元に戻されます。これには、そのモジュールの「所有者および選択したチーム」オプションを使用したロールや、そのモジュールのレコードに関連するチームベースのパーミッションデータが含まれます。
モジュールのチームベースのパーミッションを無効化した後には、クイックリペア＆再構築ツールを使用してシステムのキャッシュをクリアにすることを推奨します。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
モジュールのチームベースのパーミッションを無効にすると、そのモジュールのチームベースのパーミッションに関連付けられているデータ (例: この機能を使用したプロセスの定義やプロセス) が元に戻されます。これには、そのモジュールの「所有者および選択したチーム」オプションを使用したロールや、そのモジュールのレコードに関連するチームベースのパーミッションデータが含まれます。モジュールのチームベースのパーミッションを無効化した後には、クイックリペア＆再構築ツールを使用してシステムのキャッシュをクリアにすることを推奨します。クイックリペア＆再構築を使用するアクセス権がない場合には、リペアメニューにアクセス可能な管理者にお問い合わせください。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>警告:</strong> モジュールのチームベースのパーミッションを無効にすると、そのモジュールのチームベースのパーミッションに関連付けられているデータ (例: この機能を使用したプロセスの定義やプロセス) が元に戻されます。これには、そのモジュールの「所有者および選択したチーム」オプションを使用したロールや、そのモジュールのレコードに関連するチームベースのパーミッションデータが含まれます。モジュールのチームベースのパーミッションを無効化した後には、クイックリペア＆再構築ツールを使用してシステムのキャッシュをクリアにすることを推奨します。クイックリペア＆再構築を使用するアクセス権がない場合には、リペアメニューにアクセス可能な管理者にお問い合わせください。
STR
,
);
