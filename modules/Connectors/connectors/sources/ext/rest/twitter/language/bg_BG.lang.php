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
										Получете API ключ и таен ключ от Twitter като регистрирате Вашата инсталация на Sugar като ново приложение.<br/><br>Стъпки за регистриране на Вашата инсталация:<br/><br/>
										<ol>
											<li>Отидете на сайта за разработчици на Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Влезте с акаунта за Twitter, към който бихте желали да регистрирате приложението.</li>
											<li>Във формуляра за регистрация въведете име за приложението. Това е името, което потребителите ще виждат когато удостоверяват своите акаунти за Twitter от Sugar.</li>
											<li>Въведете описание.</li>
											<li>Въведете URL адреса на уеб сайта на приложението.</li>
											<li>Въведете URL адреса за обратно повикване (може да е всякакъв, тъй като Sugar го пропуска при удостоверяването. Например: Въведете URL адреса на сайта на Sugar).</li>
											<li>Приемете Условията за използване на API на Twitter.</li>
											<li>Щракнете върху "Създайте своето приложение на Twitter".</li>
											<li>В страницата на приложението открийте API ключа и тайния ключ в раздела "API ключове". Въведете API ключа и тайния ключ по-долу.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Потребителско име за Twitter',
    'LBL_ID' => 'Потребителско име за Twitter',
	'company_url' => 'Уеб адрес',
    'oauth_consumer_key' => 'API ключ',
    'oauth_consumer_secret' => 'Таен ключ за API',
);

?>
