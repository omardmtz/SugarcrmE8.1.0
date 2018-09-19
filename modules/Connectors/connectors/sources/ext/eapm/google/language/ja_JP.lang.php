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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Sugar インスタンスを新しいアプリケーションとして Google に登録して、API キーと 秘密を取得します。<br/><br>インスタンスを登録する手順:
<br/><br/>
<ol>
<li>Google Developers サイトにアクセスします。
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>アプリケーションを登録するために使用したい Google アカウントでサインインします。</li>
<li>新しいプロジェクトの作成</li>
<li>プロジェクト名を入力して作成をクリックします</li>
<li>プロジェクトが作成された後に、Google ドライブおよび Google 連絡先 API を有効化します</li>
<li>API ＆ 承認 > 認証情報セクションで新しいクライアント ID を作成します</li>
<li>ウェブ アプリケーションを選択して許諾画面の設定をクリックします</li>
<li>製品名を入力して保存をクリックします</li>
<li>承認済みリダイレクト URI セクションの下に次の URL を入力します。 {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>クライアント ID の作成をクリックします</li>
<li>クライアント ID とクライアントの秘密を次のボックスにコピーします</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'クライアント ID',
    'oauth2_client_secret' => 'クライアントの秘密',
);
