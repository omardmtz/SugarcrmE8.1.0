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
										Obteniu una clau API i un codi secret API al Twitter mitjançant el registre de la instància de Sugar com una nova sol•licitud.<br/><br>Passos per registrar la instància:<br/><br/>
										<ol>
											<li>Aneu al lloc de desenvolupadors de Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Inicieu sessió amb el compte de Twitter amb què voldrieu registrar l\'aplicació.</li>
											<li>Al formulari de registre, escriviu un nom per a l\'aplicació. Aquest és el nom que veuran els usuaris quan s\'autentiquen els seus comptes de Twitter des de Sugar.</li>
											<li>Escriviu una descripció.</li>
											<li>Introduïu una URL del lloc web d\'aplicacions.</li>
											<li>Escriviu una URL per que us truquin (pot ser qualsevol cosa, ja que el Sugar passa per alt l\'autenticació. Per exemple: Introduïu l\'URL del vostre lloc de Sugar).</li>
											<li>Accepteu els Termes de Servei API de Twitter.</li>
											<li>Feu clic a "Crea l\'aplicació de Twitter".</li>
											<li>Dins de la pàgina de l\'aplicació, busqueu la clau API i el codi secret API. Introduïu la clau i el codi secret a continuació.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nom d\'usuari de Twitter',
    'LBL_ID' => 'Nom d\'usuari de Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Clau API',
    'oauth_consumer_secret' => 'Codi secret API',
);

?>
