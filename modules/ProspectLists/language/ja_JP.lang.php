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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'ターゲットリスト一覧のダッシュ ボード',

  'LBL_MODULE_NAME' => 'ターゲットリスト',
  'LBL_MODULE_NAME_SINGULAR' => 'ターゲットリスト',
  'LBL_MODULE_ID'   => 'ターゲットリスト',
  'LBL_MODULE_TITLE' => 'ターゲットリスト: ホーム',
  'LBL_SEARCH_FORM_TITLE' => 'ターゲットリスト検索',
  'LBL_LIST_FORM_TITLE' => 'ターゲットリスト一覧',
  'LBL_PROSPECT_LIST_NAME' => '名前',
  'LBL_NAME' => '名前',
  'LBL_ENTRIES' => '合計数',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'ターゲットリスト',
  'LBL_LIST_ENTRIES' => 'エントリ',
  'LBL_LIST_DESCRIPTION' => '詳細',
  'LBL_LIST_TYPE_NO' => 'タイプ',
  'LBL_LIST_END_DATE' => '終了日',
  'LBL_DATE_ENTERED' => '作成日',
  'LBL_MARKETING_ID' => 'マーケティングID',
  'LBL_DATE_MODIFIED' => '更新日',
  'LBL_MODIFIED' => '更新者',
  'LBL_CREATED' => '作成者',
  'LBL_TEAM' => 'チーム',
  'LBL_ASSIGNED_TO' => 'アサイン先',
  'LBL_DESCRIPTION' => '詳細',
  'LNK_NEW_CAMPAIGN' => 'キャンペーン作成',
  'LNK_CAMPAIGN_LIST' => 'キャンペーン一覧',
  'LNK_NEW_PROSPECT_LIST' => 'ターゲットリスト作成',
  'LNK_PROSPECT_LIST_LIST' => 'ターゲットリスト一覧',
  'LBL_MODIFIED_BY' => '更新者',
  'LBL_CREATED_BY' => '作成者',
  'LBL_DATE_CREATED' => '作成日',
  'LBL_DATE_LAST_MODIFIED' => '更新日',
  'LNK_NEW_PROSPECT' => 'ターゲット作成',
  'LNK_PROSPECT_LIST' => 'ターゲット一覧',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'ターゲットリスト',
  'LBL_CONTACTS_SUBPANEL_TITLE' => '取引先担当者',
  'LBL_LEADS_SUBPANEL_TITLE' => 'リード',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'ターゲット',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => '取引先',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'キャンペーン',
  'LBL_COPY_PREFIX' =>'複製',
  'LBL_USERS_SUBPANEL_TITLE' =>'ユーザ',
  'LBL_TYPE' => 'タイプ',
  'LBL_LIST_TYPE' => 'タイプ',
  'LBL_LIST_TYPE_LIST_NAME'=>'タイプ',
  'LBL_NEW_FORM_TITLE'=>'ターゲットリスト作成',
  'LBL_MARKETING_NAME'=>'マーケティング名',
  'LBL_MARKETING_MESSAGE'=>'Eメールマーケティングメッセージ',
  'LBL_DOMAIN_NAME'=>'ドメイン名',
  'LBL_DOMAIN'=>'Eメールはありません',
  'LBL_LIST_PROSPECTLIST_NAME'=>'名前',
	'LBL_MORE_DETAIL' => '詳細情報' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} はあなたがマスマーケティング {{campaigns_singular_module}} に含めるか、除外したい個人または組織の集合体で構成されています。 {{plural_module_name}} はターゲットの任意の数の任意の組み合わせを含めることができます {{contacts_module}}、{{leads_module}}、ユーザー、 {{accounts_module}} など。ターゲットは、年齢グループ、地理的位置、あるいは支出の習慣などの所定の基準のセットに応じて {{module_name}} に分類することができます。 {{plural_module_name}} マスメールマーケティングで使用されている {{campaigns_module}} は、その {{campaigns_module}} モジュールで構成することができます。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} はマスマーケティング{{campaigns_singular_module}} に含めたいか含めたくない個人や組織で構成されています。

- 個々のフィールドまたは「編集」ボタンをクリックして、このレコードのフィールドを編集します。
- 左下のペインに「データビュー」をトグルすることによって、{{campaigns_singular_module}} を含むサブパネル内の他のレコードへのリンクを閲覧・変更します。
- 左下ペインで「アクティビティストリーム」を切り替えることにより、{{activitystream_singular_module}} 内のレコードの変更履歴やユーザーコメントを作成したり編集したりしてください。
- レコード名の右にあるアイコンを使用して、このレコードをフォローするかお気に入りにしてください。
- 追加のアクションは、「編集」ボタンの右にあるドロップダウンの「操作」メニューにあります。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} はマスマーケティング{{campaigns_singular_module}} にあなたが含めたいか、除外したい個人や組織の集合で構成されています。

{{module_name}} を作成するには：
1. 必要に応じてフィールドの値を指定します。
 - 「必須」フィールドは保存前に入力完了してください。
 - 必要に応じて、追加のフィールドを展開する「更に表示」をクリックします。
2. 新しいレコードを確定し、前のページに戻るには「保存」をクリックします。
3. 保存後、対象のレコードビューで利用可能なサブパネルを使って {{campaigns_singular_module}} 受領者を追加します。',
);
