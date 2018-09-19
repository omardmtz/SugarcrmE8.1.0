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
										Отримайте ключ API і секретний ключ від Twitter, зареєструвавши свій екземпляр Sugar як новий додаток.<br/><br>Щоб зареєструвати екземпляр:<br/><br/>
										<ol>
											<li>Перейдіть на сайт для розробників Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Увійдіть у систему за допомогою облікового запису Twitter, з якого потрібно зареєструвати додаток.</li>
											<li>У формі реєстрації введіть назву додатка. Це назва, яку бачитимуть користувачі під час використання облікового запису Twitter для автентифікації в Sugar.</li>
											<li>Введіть опис.</li>
											<li>Введіть URL-адресу веб-сайту додатка.</li>
											<li>Введіть URL-адресу зворотнього виклику (може бути будь-якою, оскільки Sugar не використовує її під час автентифікації. Приклад: введіть свою URL-адресу веб-сайту Sugar).</li>
											<li>Прийміть умови використання API Twitter.</li>
											<li>Клацніть "Створити додаток у Twitter".</li>
											<li>На сторінці додатка знайдіть ключ API і секретний ключ на вкладці "Ключі API". Введіть їх нижче.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Ім’я користувача в Twitter',
    'LBL_ID' => 'Ім’я користувача в Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Ключ API',
    'oauth_consumer_secret' => 'Секретний ключ API',
);

?>
