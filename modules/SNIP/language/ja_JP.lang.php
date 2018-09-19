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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Eメールアーカイブ',
    'LBL_SNIP_SUMMARY' => "Eメールアーカイブは、Sugarが提供するEメールアドレスにEメールを送信することによって、メールクライアントやサービスからSugarに自動的にEメールをインポートすることが可能なサービスです。それぞれのSugarインスタンスはユニークなEメールアドレスを持ちます。Eメールをインポートするには、TO, CC, BCCフィールドに提供されたEメールアドレスを追加してEメールを送信します。EメールアーカイブサービスはSugarインスタンスにEメールをインポートします。サービスはアプリケーションにEメール、添付ファイル、画像、カレンダーイベントをインポートし、Eメールアドレスがマッチする既存のレコードに関連付けます。<br><br>例: ユーザが取引先を閲覧する場合、取引先レコードのEメールアドレスに基づいて関連付けたEメールをすべて閲覧することができます。取引先に関連付けられている取引先担当者のEメールもすべて閲覧することができます。<br><br>サービスの使用を開始するには以下の規約に同意し、サービスを有効にしてください。サービスはいつでも無効にできます。サービスが有効になるとサービスで使用するメールアドレスが表示されます。<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Eメールアーカイブサービスへのコンタクトに失敗しました: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Eメールアーカイブ',
    'LBL_DISABLE_SNIP' => '無効',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'アプリケーションユニークキー',
    'LBL_SNIP_USER' => 'Eメールアーカイブユーザ',
    'LBL_SNIP_PWD' => 'Eメールアーカイブパスワード',
    'LBL_SNIP_SUGAR_URL' => 'SugarインスタンスURL',
	'LBL_SNIP_CALLBACK_URL' => 'EメールアーカイブサービスURL',
    'LBL_SNIP_USER_DESC' => 'Eメールアーカイブユーザ',
    'LBL_SNIP_KEY_DESC' => 'Eメールアーカイブ OAuthキー。このインスタンスにEメールをインポートするために使用されます。',
    'LBL_SNIP_STATUS_OK' => '有効',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Eメールアーカイブサーバへの接続に成功しました。',
    'LBL_SNIP_STATUS_ERROR' => 'エラー',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'インスタンスは有効なEメールアーカイブライセンスを保持していますが、サーバは以下のエラーを返しました。',
    'LBL_SNIP_STATUS_FAIL' => 'Eメールアーカイブサーバに登録できません。',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Eメールアーカイブサービスは現在利用できません。サービスがダウンしているかSugarインスタンスへの接続に失敗しています。',
    'LBL_SNIP_GENERIC_ERROR' => 'Eメールアーカイブサービスは現在利用できません。サービスがダウンしているかSugarインスタンスへの接続に失敗しています。',

	'LBL_SNIP_STATUS_RESET' => 'まだ実行されていません',
	'LBL_SNIP_STATUS_PROBLEM' => '問題: %s',
    'LBL_SNIP_NEVER' => "なし",
    'LBL_SNIP_STATUS_SUMMARY' => "Eメールアーカイブサービスステータス",
    'LBL_SNIP_ACCOUNT' => "取引先",
    'LBL_SNIP_STATUS' => "ステータス",
    'LBL_SNIP_LAST_SUCCESS' => "最後に成功した実行",
    "LBL_SNIP_DESCRIPTION" => "Eメールアーカイブサービスは自動的にEメールをシステムに保存しています。",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "手動でインポートやリンクを使用することなしにSugarCRMの取引先担当者へ送信または取引先担当者から受信したEメールを閲覧することができます。",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Eメールアーカイブを使用するには、SugarCRMインスタンス用のライセンスを購入する必要があります。",
    "LBL_SNIP_PURCHASE" => "購入するにはここをクリック",
    'LBL_SNIP_EMAIL' => 'Eメールアーカイブアドレス',
    'LBL_SNIP_AGREE' => "上記の規約と <a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">プライバシー契約</a>に同意します。",
    'LBL_SNIP_PRIVACY' => 'プライバシー契約',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback失敗',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'EメールアーカイブサーバはSugarインスタンスとの接続を確立できません。再度実施するかカスタマーサポートに連絡してください。',

    'LBL_SNIP_BUTTON_ENABLE' => 'Eメールアーカイブを有効',
    'LBL_SNIP_BUTTON_DISABLE' => 'Eメールアーカイブを無効',
    'LBL_SNIP_BUTTON_RETRY' => '再接続',
    'LBL_SNIP_ERROR_DISABLING' => 'メールアーカイブサーバに接続中にエラーが発生し、サービスを無効にすることができませんでした。',
    'LBL_SNIP_ERROR_ENABLING' => 'Eメールアーカイブサーバに接続中にエラーが発生し、サービスを有効にすることができませんでした。',
    'LBL_CONTACT_SUPPORT' => '再度実施するかカスタマーサポートに連絡してください。',
    'LBL_SNIP_SUPPORT' => 'カスタマーサポートに連絡してください。',
    'ERROR_BAD_RESULT' => 'サービスからBad resultが返されました',
	'ERROR_NO_CURL' => 'cURLエクステンションが必要ですが有効になっていません',
	'ERROR_REQUEST_FAILED' => 'サーバにコンタクトできません',

    'LBL_CANCEL_BUTTON_TITLE' => 'キャンセル',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'このインスタンスのEメールアーカイブサービスのステータスです。ステータスにはEメールアーカイブサーバとSugarインスタンスとの間の接続が成功しているかが反映されています。',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Sugarに送信したEメールをインポートするEメールアーカイブのEメールアドレスです。',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'EメールアーカイブサーバのURLです。Eメールアーカイブサービスの有効化、無効化等のリクエストはすべて本URLで実施されます。',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'SugarインスタンスのウェブサービスのURLです。EメールアーカイブサーバはこのURLに接続します。',
);
