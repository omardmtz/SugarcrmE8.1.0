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
    'LBL_NOTES_LIST_DASHBOARD' => 'メモリストのダッシュ ボード',

    'ERR_DELETE_RECORD' => 'あなたがアカウントを削除するには、レコード番号を指定する必要があります。',
    'LBL_ACCOUNT_ID' => '取引先ID:',
    'LBL_CASE_ID' => 'ケースID',
    'LBL_CLOSE' => '完了:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => '取引先担当者ID:',
    'LBL_CONTACT_NAME' => '取引先担当者:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'メモ',
    'LBL_DESCRIPTION' => '詳細',
    'LBL_EMAIL_ADDRESS' => 'Eメールアドレス:',
    'LBL_EMAIL_ATTACHMENT' => 'Eメール添付',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Eメール添付',
    'LBL_FILE_MIME_TYPE' => 'Mimeタイプ',
    'LBL_FILE_EXTENSION' => 'ファイル拡張子',
    'LBL_FILE_SOURCE' => 'ファイル ソース',
    'LBL_FILE_SIZE' => 'ファイルのサイズ',
    'LBL_FILE_URL' => 'ファイルURL',
    'LBL_FILENAME' => '添付ファイル:',
    'LBL_LEAD_ID' => 'リードID:',
    'LBL_LIST_CONTACT_NAME' => '取引先担当者',
    'LBL_LIST_DATE_MODIFIED' => '最終更新日',
    'LBL_LIST_FILENAME' => '添付ファイル',
    'LBL_LIST_FORM_TITLE' => 'メモ一覧',
    'LBL_LIST_RELATED_TO' => '関連先',
    'LBL_LIST_SUBJECT' => '件名',
    'LBL_LIST_STATUS' => 'ステータス',
    'LBL_LIST_CONTACT' => '取引先担当者',
    'LBL_MODULE_NAME' => 'メモ',
    'LBL_MODULE_NAME_SINGULAR' => 'メモ',
    'LBL_MODULE_TITLE' => 'メモ: ホーム',
    'LBL_NEW_FORM_TITLE' => 'メモ作成',
    'LBL_NEW_FORM_BTN' => 'メモを追加',
    'LBL_NOTE_STATUS' => 'メモ',
    'LBL_NOTE_SUBJECT' => 'メモ:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'ノート＆添付ファイル',
    'LBL_NOTE' => 'メモ:',
    'LBL_OPPORTUNITY_ID' => '商談ID:',
    'LBL_PARENT_ID' => '親ID:',
    'LBL_PARENT_TYPE' => '親タイプ',
    'LBL_EMAIL_TYPE' => 'Eメールのタイプ',
    'LBL_EMAIL_ID' => 'Eメール ID',
    'LBL_PHONE' => '電話:',
    'LBL_PORTAL_FLAG' => 'ポータルで表示？',
    'LBL_EMBED_FLAG' => 'Eメールに埋め込み？',
    'LBL_PRODUCT_ID' => '商品ID:',
    'LBL_QUOTE_ID' => '見積ID:',
    'LBL_RELATED_TO' => '関連先:',
    'LBL_SEARCH_FORM_TITLE' => 'メモ検索',
    'LBL_STATUS' => 'ステータス',
    'LBL_SUBJECT' => '件名:',
    'LNK_IMPORT_NOTES' => 'メモのインポート',
    'LNK_NEW_NOTE' => 'メモ作成',
    'LNK_NOTE_LIST' => 'メモ一覧',
    'LBL_MEMBER_OF' => '親会社:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'アサイン先',
    'LBL_OC_FILE_NOTICE' => 'サーバにログインし、ファイルを参照してください。',
    'LBL_REMOVING_ATTACHMENT' => '添付ファイルを削除中...',
    'ERR_REMOVING_ATTACHMENT' => '添付ファイルの削除に失敗...',
    'LBL_CREATED_BY' => '作成者',
    'LBL_MODIFIED_BY' => '更新者',
    'LBL_SEND_ANYWAYS' => 'このEメールには件名がありません。送信/保存しますか？',
    'LBL_LIST_EDIT_BUTTON' => '編集',
    'LBL_ACTIVITIES_REPORTS' => 'アクティビティレポート',
    'LBL_PANEL_DETAILS' => '詳細',
    'LBL_NOTE_INFORMATION' => 'メモの概要',
    'LBL_MY_NOTES_DASHLETNAME' => '私のメモ',
    'LBL_EDITLAYOUT' => 'レイアウト編集' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => '名',
    'LBL_LAST_NAME' => '姓',
    'LBL_EXPORT_PARENT_TYPE' => 'モジュールに関連',
    'LBL_EXPORT_PARENT_ID' => 'IDに関連',
    'LBL_DATE_ENTERED' => '作成日',
    'LBL_DATE_MODIFIED' => '更新日',
    'LBL_DELETED' => '削除済み',
    'LBL_REVENUELINEITEMS' => '商談品目',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}}モジュールは、テキストや関連するレコードに該当する添付ファイルが含まれている個々の{{plural_module_name}}で構成されています。{{module_name}}レコードは、フレックス関連フィールドを経由して、ほとんどのモジュールの1つのレコードに関連付けることができ、また、単一の{{contacts_singular_module}}に関連させることもできます。{{plural_module_name}}レコードまたはレコードに関連しても添付ファイルに関する一般的なテキストを保持することができます。  {{plural_module_name}}
をSugarで作成する様々な方法があります。例えば{{plural_module_name}}モジュールを介して, {{plural_module_name}}をインポートすることにより,あるいは 履歴のサブパネル経由でなどです。{{module_name}}レコードが作成されたら、{{plural_module_name}}レコードの表示を経由して{{module_name}}に関連する情報を表示したり、編集することができます。各{{module_name}}レコードは{{accounts_module}}、{{contacts_module}}、{{opportunities_module}}など、またその他多くの他のSugarのレコードに関するものになります。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}}モジュールは、テキストや関連するレコードに該当する添付ファイルが含まれている個々の{{plural_module_name}}で構成されています。 - 個々のフィールドまたは「編集」ボタンをクリックして、このレコードのフィールドを編集します。 - 左下のペインに「データビュー」をトグルしてサブパネル内の他のレコードへのリンクを表示または変更してください。 - 左下ペインに「アクティビティストリーム」を切り替えることにより、{{activitystream_singular_module}}内のレコード変更履歴やユーザーのコメントを閲覧もしくは変更してください。 - レコード名の右にあるアイコンを使用して、このレコードをフォローするかお気に入りにしてください。 - 追加のアクションは、「編集」ボタンの右にあるドロップダウンの「操作」メニューにあります。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} を作成するには：
1. 必要に応じてフィールドの値を指定します。
- 「必須」フィールドは保存前に入力完了してください。
- 必要に応じて、追加のフィールドを展開する「更に表示」をクリックします。
2. 新しいレコードを確定し、前のページに戻るには「保存」をクリックします。',
);
