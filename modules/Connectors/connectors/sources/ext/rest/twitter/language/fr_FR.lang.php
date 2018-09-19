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
										Obtenez une clé API et un Secret de Twitter en enregistrant votre instance de Sugar comme une nouvelle application.<br/><br>Étapes pour enregistrer votre instance :<br/><br/>
										<ol>
											<li>Allez au site des développeurs de Twitter : <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Connectez-vous en utilisant le compte Twitter sous lequel vous souhaitez enregistrer l\'application.</li>
											<li>Dans le formulaire d\'enregistrement, saisissez un nom pour l\'application. C\'est le nom que les utilisateurs verront lors de l\'authentification de leurs comptes Twitter depuis Sugar.</li>
											<li>Saisissez une description.</li>
											<li>Saisissez un URL de site web de l\'application.</li>
											<li>Saisissez un URL de rappel (ça peut être n\'importe quel URL, étant donné que Sugar contourne ceci lors de l\'authentification. Par exemple : Saisissez l\'URL de votre site Sugar).</li>
											<li>Acceptez les conditions de service de l\'API Twitter.</li>
											<li>Cliquez sur "Créer votre application Twitter".</li>
											<li>Dans la page de l\'application, vous trouverez la clé API et le Secret API dans l\'onglet « Clés de l\'API ». Saisissez la clé et le secret ci-dessous.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nom d\'utilisateur Twitter',
    'LBL_ID' => 'Nom d\'utilisateur Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Clé API',
    'oauth_consumer_secret' => 'Code Secret API',
);

?>
