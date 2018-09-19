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
										Sugarインスタンスを新しいアプリケーションとして登録することでTwitterからAPI KeyとSecretを取得しましょう：<br/><br>インスタンスの登録手順：<br/><br/>
										<ol>
											<li>Twitter Developersサイトを表示します。 <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>アプリケーションを登録したいTwitter アカウントでサインインします。</li>
											<li>登録フォーム内に、アプリケーションの名称を入力します。ユーザーがSugarからTwitterアカウントを認証すると、この名称が表示されます。</li>
											<li>[Description（説明）]を入力します。</li>
											<li>[Application Website URL（アプリケーションウェブサイトURL）]を入力します。</li>
											<li>[Callback URL（コールバックURL）
]（どのURLでも問題ありません。Sugarは承認時ここをバイパスします。例えば、SugarウェブサイトのURLを入れます）を入力します。</li>
											<li>Twitter API Terms of Service（TwitterのAPIサービス規約）に同意します。</li>
											<li>[Create your Twitter application（Twitterアプリケーションの作成）]をクリックします。</li>
											<li>アプリケーションページ内の[API Keys（APIキー）]タブでAPIキーおよびAPIの秘密を調べます。API KeyとSecretを下に入力します。</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'ツイッターユーザー名',
    'LBL_ID' => 'ツイッターユーザー名',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'コンシューマキー',
    'oauth_consumer_secret' => 'コンシューマシークレット',
);

?>
