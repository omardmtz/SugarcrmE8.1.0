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
										Ottenere una chiave API e una chiave privata da Twitter registrando la propria istanza Sugar come una nuova applicazione.<br/><br>Operazioni per la registrazione dell\'istanza:<br/><br/>
										<ol>
											<li>Andare al sito degli sviluppatori di Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Effettuare l\'accesso utilizzando l\'account di Twitter sotto il quale si desidera registrare l\'applicazione.</li>
											<li>Nel modulo di registrazione, immettere un nome per l\'applicazione. Si tratta del nome che gli utenti vedranno quando autenticano i rispettivi account di Twitter dall\'interno di Sugar.</li>
											<li>Inserire una descrizione.</li>
											<li>Inserire un URL del sito web dell\'applicazione.</li>
											<li>Inserire un URL Callback (può essere qualsiasi cosa poiché Sugar la bypassa al momento dell\'autenticazione. Esempio: inserire il proprio sito Sugar URL).</li>
											<li>Accettare le Condizioni per l\'utilizzo delle API di Twitter.</li>
											<li>Fare clic su "Crea la tua applicazione Twitter".</li>
											<li>All\'interno della pagina dell\'applicazione, individuare la chiave API e la chiave privata API nella scheda "Chiavi API". Inserire sotto la chiave e la chiave privata.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nome Utente Twitter',
    'LBL_ID' => 'Nome Utente Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Chiave API',
    'oauth_consumer_secret' => 'API Secret',
);

?>
