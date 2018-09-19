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
										Получите ключ API и секретный ключ от Twitter, зарегистрировав свой экземпляр Sugar как новое приложение.<br/><br>Шаги для регистрации экземпляра:<br/><br/>
										<ol>
											<li>Перейдите на сайт для разработчиков Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Войдите в систему с учетной записью Twitter, с помощью которой необходимо зарегистрировать приложение.</li>
											<li>В форме регистрации введите название приложения. Это название, которое будут видеть пользователи при использовании учетной записи Twitter для аутентификации в Sugar.</li>
											<li>Введите описание.</li>
											<li>Введите URL-адрес веб-сайта приложения.</li>
											<li>Введите URL-адрес обратного вызова (может быть любым, так как Sugar не использует его при аутентификации. Пример: введите свой URL-адрес веб-сайта Sugar).</li>
											<li>Примите условия использования API Twitter.</li>
											<li>Щелкните "Создать приложение в Twitter".</li>
											<li>На странице приложения найдите ключ API и секретный ключ на вкладке "Ключи API". Введите их ниже.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Имя пользователя в Twitter',
    'LBL_ID' => 'Имя пользователя в Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Ключ пользователя',
    'oauth_consumer_secret' => 'Секретный ключ',
);

?>
