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
通过将 Sugar 实例注册为新应用程序，从 Google 获取 API Key 和密钥。
<br/><br>注册实例的步骤：
<br/><br/>
<ol>
<li>前往 Google 开发者网站：
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>使用您想用其注册应用程序的 Google 账户登录。</li>
<li>创建新项目</li>
<li>输入项目名称并点击创建。</li>
<li>待项目创建完毕后，启用 Google Drive 和 Google Contacts API</li>
<li>在“API 与验证” > “证书” 部分中创建新客户 ID </li>
<li>选择”网络应用程序“并点击”配置“屏幕</li>
<li>输入产品名称，然后点击”保存“</li>
<li>在授权重定向 URL 部分中输入以下 url：{$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>点击“创建客户端 id”</li>
<li>将客户端 ID 和客户端密钥复制到下面的方框中</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => '客户端 ID',
    'oauth2_client_secret' => '客户端密钥',
);
