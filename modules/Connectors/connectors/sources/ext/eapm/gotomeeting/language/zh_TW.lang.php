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
通過註冊GoToMeeting應用程式從 LogMeIn 取得用戶密鑰。<br>
&nbsp;<br>
註冊的實際步骤：<br>
&nbsp;<br>
<ol>
    <li>登入至您的 LogMeIn 開發人員中心帳號：<a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>點擊我的應用程式</li>
    <li>點擊添加新應用程式</li>
    <li>填寫“添加應用程式”表格上的所有表格：</li>
        <ul>
            <li>應用程式名稱</li>
            <li>說明</li>
            <li>產品 API：選擇GoToMeeting</li>
            <li>應用程式網址：輸入您的實例網址</li>
        </ul>
    <li>點擊“創建應用程式”按鈕</li>
    <li>從應用程式清單中點擊您的應用程式的名稱</li>
    <li>點擊按鍵標籤</li>
    <li>複製消費者密鑰值並在下方輸入</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => '消費者密鑰',
);
