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
* Description:
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
* Reserved. Contributor(s): contact@synolia.com - www.synolia.com
* *******************************************************************************/


$connector_strings = array (
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1">
								<tr><td valign="top" width="35%" class="dataLabel">
									注册您的 Sugar 实例为新的应用程序，从 Twitter 获取 API 密钥和密码。<br/><br>注册实例的步骤：<br/><br/>
										<ol>
											<li>进入 Twitter 开发者站点： <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>。</li>
											<li>使用您将用以注册应用程序的Twitter 账户登录。</li>
											<li>在注册表格内，输入应用程序的名称。这是用户从 Sugar 内验证 Twitter 账户时将看到的名称。</li>
											<li>输入一段说明。</li>
											<li>输入程序网站的网址。</li>
											<li>输入回访的网址（可为任意内容，因为 Sugar 在验证过程中会忽视此信息。例如: 输入您的 Sugar 站点网址）。</li>
											<li>接受 Twitter 的 API 服务条款。</li>
											<li>单击“创建您的 Twitter 应用程序”。</li>
											<li>在应用程序页面的“API 密码”选项下找到 API 密钥和 API 密码。并在下方输入密钥和密码。</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter 用户名',
    'LBL_ID' => 'Twitter 用户名',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API 密钥',
    'oauth_consumer_secret' => 'API 密码',
);

?>
