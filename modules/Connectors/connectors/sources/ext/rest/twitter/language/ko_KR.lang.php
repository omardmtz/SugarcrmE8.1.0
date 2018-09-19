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
										Sugar 인스턴스를 새 애플리케이션으로 등록하여 API Key와 Secret을 얻으십시오.<br/><br>인스턴스 등록 단계:<br/><br/>
										<ol>
											<li>Twitter 개발자 사이트로 갑니다: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>애플리케이션을 등록하고 싶은 Twitter 계정을 사용하여 로그인합니다.</li>
											<li>등록 양식 내에서 애플리케이션의 이름을 입력합니다. 이 이름이 사용자가 Sugar 내에서사용자가 자신의 Twitter 계정을 인증할 때 보게 되는 이름입니다..</li>
											<li>설명을 입력합니다.</li>
											<li>애플리케이션 웹사이트 URL을 입력합니다.</li>
											<li>콜백 URL을 입력합니다 (Sugar가 인증 시 이것을 무시하기 때문에 아무 것이나 입력할 수 있습니다. 예: 사용자의 Sugar 사이트 URL을 입력합니다).</li>
											<li>Twitter API 서비스 조건을 수락합니다.</li>
											<li>"Create your Twitter application"을 클릭합니다.</li>
                                                                                       <li>애플리케이션 페이지의 "API Keys" 탭에서 API Key와 API Secret을 검색합니다. Key와Secret을 아래에 입력합니다.</li>
										</ol>
									</td>
								</tr>
							</table>
 
Context | Request Context
<li>',
    'LBL_NAME' => 'Twitter 사용자 이름',
    'LBL_ID' => 'Twitter 사용자 이름',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API 키',
    'oauth_consumer_secret' => 'API 비밀',
);

?>
