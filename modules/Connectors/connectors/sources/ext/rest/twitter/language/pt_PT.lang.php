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
										Obtenha uma Chave API Key e um segredo do Twitter, registando a sua instância Sugar como nova aplicação.<br/><br>Passos para registar a sua instância:<br/><br/>
										<ol>
											<li>Aceda ao site Twitter Developers: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Inicie sessão utilizando a conta Twitter com a qual pretende registar a aplicação.</li>
											<li>No formulário de registo, introduza um nome para a aplicação. Este é o nome que os utilizadores irão ver quando autenticarem as suas contas Twitter no Sugar.</li>
											<li>Introduza uma Descrição.</li>
											<li>Introduza um URL de Website da Aplicação.</li>
											<li>Introduza um Callback URL (pode ser qualquer coisa, dado que o Sugar contorna esta autenticação. Exemplo: Introduza o URL do seu site Sugar).</li>
											<li>Aceite os Termos de Serviço do Twitter API.</li>
											<li>Clique em "Criar a sua aplicação de Twitter".</li>
											<li>Na página da aplicação, encontre a Chave API e o Segredo API no seprador "Chaves API". Introduza a Chave e o Segredo API abaixo.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nome de Utilizador Twitter',
    'LBL_ID' => 'Nome de Utilizador Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Chave da API',
    'oauth_consumer_secret' => 'Segredo da API',
);

?>
