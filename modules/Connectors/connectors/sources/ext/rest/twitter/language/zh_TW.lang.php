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
								<tr>
									<td valign="top" width="35%" class="dataLabel">
										將 Sugar 實例註冊為新的應用程式，以從 Twitter 獲取「API 金鑰」和「機密」<br/><br>註冊實例的步驟如下：<br/><br/>
										<ol>
											<li>前往「Twitter 開發人員」網站：<a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>。</li>
											<li>使用您將用於註冊應用程式的 Twitter 帳戶登入。</li>
											<li>在註冊表格內輸入應用程式的名稱。這是使用者從 Sugar 內部驗證 Twitter 帳戶時會看到的名稱。</li>
											<li>輸入「描述」。</li>
											<li>輸入「應用程式網站 URL」。</li>
											<li>輸入「回呼 URL」（可以為任意內容，因為 Sugar 在驗證過程中會略過此資訊。例如：輸入您的 Sugar 網站 URL）。</li>
											<li>接受「Twitter API 服務條款」。</li>
											<li>按一下「建立您的 Twitter 應用程式」。</li>
											<li>在應用程式頁面內，在「API 金鑰」索引標籤下找到「API 金鑰」和「API 機密」。在下方輸入「金鑰」和「機密」。</li>
										</ol>
									</td>
								</tr>
							</table><td valign="top" width="35%" class="dataLabel">',
    'LBL_NAME' => 'Twitter 使用者名稱',
    'LBL_ID' => 'Twitter 使用者名稱',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API 金鑰',
    'oauth_consumer_secret' => 'API 機密',
);

?>
