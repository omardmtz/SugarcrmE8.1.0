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
										Sugar oluşumunuzu yeni bir uygulama olarak kaydederek Twitter\'dan Müşteri Anahtarı ve Şifre alın.<br/><br>Oluşumunuzu kaydetmek için gereken adımlar:<br/><br/>
										<ol>
											<li>Twitter Geliştiricileri sayfasına gidin: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Uygulamanızı kaydetmek istediğiniz Twitter hesabınızla giriş yapın.</li>
											<li>Kayıt formuna uygulama ismini girin. Bu ad kullanıcıların Twitter hesaplarını Sugar içinde doğrularken görecekleri adlardır.</li>
											<li>Bir açıklama girin.</li>
											<li>Bir Uygulama Web Sitesi URL\'si girin.</li>
											<li>Bir Geri Dönüş (Callback) URL adresi girin (Sugar bu adresi doğrulamada kullanmayacağı için herhangi bir adres olabilir. Örneğin: Sugar sitenizin URL adresini girin).</li>
											<li>Twitter API Hizmet Şartlarını kabul edin.</li>
											<li>"Twitter uygulamanızı oluşturun" düğmesine tıklayın.</li>
											<li>Uygulama sayfasında "API Anahtarları" sekmesi altında API Anahtarını ve API şifresini bulun. Anahtarı ve Şifreyi aşağıya girin.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter Kullanıcı Adı',
    'LBL_ID' => 'Twitter Kullanıcı Adı',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API Anahtarı',
    'oauth_consumer_secret' => 'API Şifresi',
);

?>
