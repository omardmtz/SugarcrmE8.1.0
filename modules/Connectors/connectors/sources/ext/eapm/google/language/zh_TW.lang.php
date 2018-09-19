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
註冊您的 Sugar 實例為新的應用程式，從 Google 獲取 API 金鑰和密碼。
<br/><br>註冊實例的步驟：
<br/><br/>
<ol>
<li>前往 Google 開發者站點：
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>使用您將用於註冊應用程式的 Google 帳戶登入。</li>
<li>建立新專案</li>
<li>輸入專案名稱，按一下「建立」</li>
<li>建立專案之後，啟用 Google Drive 和 Google Contacts API</li>
<li>在 APIs & Auth > 認證部分建立新的用戶端 ID </li>
<li>選取「網路應用程式」並按一下「配置」同意螢幕</li>
<li>輸入產品名稱並按一下「儲存」</li>
<li>在授權的重新導向 URL 下方輸入下列 URL： {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>按一下創建用戶端 ID</li>
<li>將用戶端 ID 和密碼複製到下方方塊內。</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => '用戶端 ID',
    'oauth2_client_secret' => '用戶端密碼',
);
