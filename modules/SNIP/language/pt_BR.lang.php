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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Arquivamento de e-mail',
    'LBL_SNIP_SUMMARY' => "O Arquivamento de e-mail é um serviço de importação automática que permite aos usuários importar e-mails no Sugar, enviando-os de qualquer cliente ou serviço de e-mail para um endereço de e-mail fornecido pelo Sugar. Cada instância do Sugar tem seu próprio e-mail exclusivo. Para importar e-mails, o usuário envia para o endereço de e-mail fornecido usando os campos TO, CC, BCC. O serviço de Arquivamento de e-mail importará o e-mail para a instância do Sugar. O serviço  importa o e-mail, juntamente com anexos, imagens e eventos de calendário, e cria registros dentro do aplicativo que estão associados com os registros existentes com base nos endereços de e-mail correspondentes.
<br><br>Exemplo: Como um usuário, quando visualizo uma conta, poderei visualizar todos os e-mails associados à conta com base no endereço de e-mail no registro da conta. Também poderei visualizar e-mails que estão associados com os contatos relacionados à conta.
<br><br>Aceite os termos abaixo e clique em Ativar para começar a usar o serviço. Você pode desabilitar o serviço a qualquer momento. Quando o serviço estiver ativado, o endereço de e-mail para usar o serviço será exibido.
<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Falha ao entrar em contato com o serviço de arquivamento de e-mail:%s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Arquivamento de e-mail',
    'LBL_DISABLE_SNIP' => 'Desativar',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Chave única do aplicativo',
    'LBL_SNIP_USER' => 'Usuário do arquivamento de e-mail',
    'LBL_SNIP_PWD' => 'Senha do arquivamento de e-mail',
    'LBL_SNIP_SUGAR_URL' => 'URL desta instância do Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL do serviço arquivamento de e-mail',
    'LBL_SNIP_USER_DESC' => 'Usuário do arquivamento de e-mail',
    'LBL_SNIP_KEY_DESC' => 'Chave OAuth do arquivamento de e-mail. Usada para acessar a instância para fins de importação de e-mails.',
    'LBL_SNIP_STATUS_OK' => 'Ativo',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Esta instância do Sugar está conectada com sucesso ao servidor de arquivamento de e-mail.',
    'LBL_SNIP_STATUS_ERROR' => 'Erro:',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Esta instância tem um licença válida de servidor de arquivamento de e-mail, mas o servidor retornou a seguinte mensagem de erro:',
    'LBL_SNIP_STATUS_FAIL' => 'Não é possível registrar-se com o servidor de arquivamento de e-mail',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'O serviço de arquivamento de e-mail está indisponível no momento. Ou o serviço está inoperante ou a conexão a essa instância do Sugar falhou.',
    'LBL_SNIP_GENERIC_ERROR' => 'O serviço de arquivamento de e-mail está indisponível no momento. Ou o serviço está inoperante ou a conexão a essa instância do Sugar falhou.',

	'LBL_SNIP_STATUS_RESET' => 'Ainda não foi executado',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problema: %s',
    'LBL_SNIP_NEVER' => "Nunca",
    'LBL_SNIP_STATUS_SUMMARY' => "Status do serviço de arquivamento de e-mail:",
    'LBL_SNIP_ACCOUNT' => "Conta",
    'LBL_SNIP_STATUS' => "Status",
    'LBL_SNIP_LAST_SUCCESS' => "Última execução bem-sucedida",
    "LBL_SNIP_DESCRIPTION" => "O serviço de arquivamento de e-mail é um sistema de arquivamento automático de e-mail",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Ele permite que você visualize e-mails que foram enviados para ou pelos seus contatos dentro do SugarCRM, sem precisar importar e vincular os e-mails manualmente",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Para usar o arquivamento de e-mail, você deve adquirir uma licença para sua instância do SugarCRM",
    "LBL_SNIP_PURCHASE" => "Clique aqui para comprar",
    'LBL_SNIP_EMAIL' => 'Endereço do arquivamento de e-mail',
    'LBL_SNIP_AGREE' => "Eu concordo com os termos acima e o <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>contrato de privacidade</a>.",
    'LBL_SNIP_PRIVACY' => 'contrato de privacidade.',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Falha de Pingback',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'O servidor do arquivamento de e-mail está impossibilitado de estabelecer uma conexão com a instância do Sugar. Tente novamente ou <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">entre em contato com o suporte ao cliente</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Ativar arquivamento de e-mail',
    'LBL_SNIP_BUTTON_DISABLE' => 'Desativar arquivamento de e-mail',
    'LBL_SNIP_BUTTON_RETRY' => 'Tente conectar outra vez',
    'LBL_SNIP_ERROR_DISABLING' => 'Ocorreu um erro durante a tentativa de comunicação com o servidor de arquivamento de e-mail, e não foi possível desativar o serviço',
    'LBL_SNIP_ERROR_ENABLING' => 'Ocorreu um erro durante a tentativa de comunicação com o servidor de arquivamento de e-mail, e não foi possível ativar o serviço',
    'LBL_CONTACT_SUPPORT' => 'Tente novamente ou entre em contato com o Suporte do SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Entre em contato com o Suporte do SugarCRM para obter assistência.',
    'ERROR_BAD_RESULT' => 'Resultado de falha retornado pelo serviço',
	'ERROR_NO_CURL' => 'Extensões cURL são necessárias, mas não foram habilitadas',
	'ERROR_REQUEST_FAILED' => 'Não foi possível contatar o servidor',

    'LBL_CANCEL_BUTTON_TITLE' => 'Cancelar',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Este é o status do serviço de arquivamento de e-mail na sua instância. O status reflete se a conexão entre o servidor de arquivamento de e-mail e sua instância do Sugar foi feita com sucesso.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Este é o endereço de envio do arquivamento de e-mail para importação de e-mails no Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Esta é a URL do servidor de arquivamento de e-mail. Todos os pedidos, como habilitar e desabilitar o serviço arquivamento de e-mail, serão retransmitidos por meio desta URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Esta é a URL dos serviços da Web da sua instância do Sugar. O servidor de arquivamento de e-mail vai se conectar ao seu servidor por meio desta URL.',
);
