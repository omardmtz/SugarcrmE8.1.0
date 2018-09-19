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
Obter uma chave de API e Segredo do Google registrando sua instância do Sugar como um novo aplicativo.
<br/><br>Etapas para registrar sua instância:
<br/><br/>
<ol>
<li>Vá para o site do Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Entre usando a conta do Google com a qual você deseja registrar o aplicativo.</li>
<li>Crie um novo projeto</li>
<li>Insira o nome do projeto e clique em Criar.</li>
<li>Após o projeto ser criado, habilite a API do Google Drive e do Google Contacts</li>
<li>Na seção APIs e Autenticação > Credenciais, crie um novo id do cliente</li>
<li>Selecione Aplicativo da Web e clique em Configurar tela de consentimento</li>
<li>Insira um nome para o produto e clique em Salvar</li>
<li>Na seção URIs de redirecionamento autorizados, insira a seguinte url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Clique em criar id do cliente</li>
<li>Copie o id do cliente e o segredo do cliente nas caixas abaixo</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID do cliente',
    'oauth2_client_secret' => 'Segredo do cliente',
);
