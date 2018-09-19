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
										Receba um segredo e uma chave de API do Twitter registrando sua instância do Sugar como um novo aplicativo.<br/><br>Etapas para registrar sua instância:<br/><br/>
										<ol>
											<li>Acesse o site do Twitter Developers: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Faça login na conta do Twitter com a qual você deseja registrar o aplicativo.</li>
											<li>No formulário de registro, insira um nome para o aplicativo. Esse é o nome que os usuários verão quando autenticarem suas contas do Twitter no Sugar.</li>
											<li>Insira uma descrição.</li>
											<li>Insira o URL do site do aplicativo.</li>
											<li>Insira um URL de retorno de chamada (Pode ser qualquer coisa, já que o Sugar ignora essa informação na autenticação. Exemplo: Insira o URL do seu site do Sugar).</li>
											<li>Aceite os termos de serviço da API do Twitter.</li>
											<li>Clique em "Create your Twitter application" (criar seu aplicativo do Twitter).</li>
											<li>Na página do aplicativo, encontre o segredo e a chave de API na guia "API Keys" (chaves de API). Insira a chave e o segredo abaixo.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nome de usuário do Twitter',
    'LBL_ID' => 'Nome de usuário do Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Chave de API',
    'oauth_consumer_secret' => 'Segredo de API',
);

?>
