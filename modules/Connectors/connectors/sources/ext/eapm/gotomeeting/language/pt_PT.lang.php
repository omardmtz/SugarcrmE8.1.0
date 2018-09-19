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
Obtenha uma Consumer Key de LogMeIn registando-se numa nova aplicação GoToMeeting.<br>
&nbsp;<br>
Passos para registar a sua instância:<br>
&nbsp;<br>
<ol>
    <li>Inicie a sessão na sua conta LogMeIn Developer Center: <a href=\'https://goto-developer.logmein.com/\' target=\'_blank\'>https://goto-developer.logmein.com/</a></li>
    <li>Clique em As Minhas Aplicações</li>
    <li>Clique em Adicionar uma nova Aplicação</li>
    <li>Preencha todos os campos no formulário Adicionar Aplicação:</li>
        <ul>
            <li>Nome da Aplicação</li>
            <li>Descrição</li>
            <li>API do Produto: Select GoToMeeting</li>
            <li>URL da Aplicação: Introduza o URL da sua instância</li>
        </ul>
    <li>Clique no botão Criar Aplicação</li>
    <li>Na lista de aplicações, clique no nome da sua aplicação</li>
    <li>Clique no separador Keys</li>
    <li>Copie o valor da Consumer Key e introduza-o abaixo</li>
</ol>
</td></tr></table>',
    'oauth_consumer_key' => 'Consumer Key',
);
