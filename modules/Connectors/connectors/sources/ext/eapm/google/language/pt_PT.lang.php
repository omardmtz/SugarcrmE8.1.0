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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Obtenha uma Chave de API e um Segredo do Google registando a sua instância de Sugar como nova aplicação.
<br/><br>Passos para registar a sua instância:
<br/><br/>
<ol>
<li>Dirija-se ao site Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Inicie sessão com a conta Google na qual pretende registar a aplicação.</li>
<li>Crie um novo projeto</li>
<li>Insira um Nome de Projeto e clique em Criar.</li>
<li>Depois da criação do projeto, ative o Google Drive e o Google Contacts API</li>
<li>Na secção APIs e Autorização > Credenciais crie um novo ID de cliente </li>
<li>Selecione Aplicação da Web e cliquem em Configurar ecrã conscent</li>
<li>Insira um nome de produto e clique em Guardar</li>
<li>Na secção de URIs de redirecionamento autorizado, insiera o seguinte url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Clique em criar ID de cliente</li>
<li>Copie o ID de cliente e o segredo do cliente nas caixas abaixo</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID de cliente',
    'oauth2_client_secret' => 'Segredo do cliente',
);
