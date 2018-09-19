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
    'LBL_LICENSING_INFO' =>
'<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
新しいGoToMeetingアプリケーションを登録することによってLogMeIn からコンシューマー キーを取得します。<br>
&nbsp;<br>
インスタンス登録手順:<br>
&nbsp;<br>
<ol>
    <li>LogMeIn開発センターアカウントにログイン: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>My Appsをクリック</li>
    <li>Add a new Appをクリック</li>
    <li>次から、Add Appの全てのフィールドに記入:</li>
        <ul>
            <li>App名</li>
            <li>詳細</li>
            <li>プロダクトAPI: GoToMeetingを選択</li>
            <li>アプリケーションURL: インスタンスURLを選択</li>
        </ul>
    <li>Create Appボタンをクリック</li>
    <li>アプリリストから、あなたのアプリ名をクリック</li>
    <li>Keysタブをクリック</li>
    <li>コンシューマキー値をコピーし、以下に入力</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'コンシューマキー',
);
