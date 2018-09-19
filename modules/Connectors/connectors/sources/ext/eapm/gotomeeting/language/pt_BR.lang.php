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
    'LBL_LICENSING_INFO' =>
'<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Registre um novo aplicativo GoToMeeting para receber uma chave de cliente do LogMeIn.<br>
&nbsp;<br>
Etapas para registrar suas instância:<br>
&nbsp;<br>
<ol>
    <li>Faça login na sua conta da Central do Desenvolvedor do LogMeIn: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Clique em Meus aplicativos</li>
    <li>Clique em Adicionar um novo aplicativo</li>
    <li>Preencha todos os campos no formulário Adicionar aplicativo:</li>
        <ul>
            <li>Nome do aplicativo</li>
            <li>Descrição</li>
            <li>API do produto: selecione GoToMeeting</li>
            <li>URL do aplicativo: insira o URL da sua instância</li>
        </ul>
    <li>Clique no botão Criar aplicativo</li>
    <li>Na lista de aplicativos, clique no nome do seu aplicativo</li>
    <li>Clique na guia Chaves</li>
    <li>Copie o valor de Chave do consumidor e insira-o abaixo</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Chave do consumidor',
);
