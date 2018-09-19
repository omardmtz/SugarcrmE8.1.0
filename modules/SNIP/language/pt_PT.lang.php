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
    'LBL_MODULE_NAME' => 'Arquivo de Email',
    'LBL_SNIP_SUMMARY' => "O Arquivo de E-mail é um serviço automático de importação de e-mails que permite aos utilizadores importar e-mails para o Sugar enviando-os de qualquer cliente ou serviço de correio para um endereço de email fornecido pelo Sugar. Cada instância do Sugar tem uma caixa de correio Sugar EASe única. Para importar e-mails, um utilizador envia para o endereço de e-mail do Sugar EASe utilizando os campos PARA, CC ou BCC. O serviço Arquivo de E-mail irá importar o e-mail para a instância do Sugar. O serviço importa o e-mail, juntamente com qualquer anexos, imagens e eventos de Calendário, e cria registos dentro da aplicação que estão associados com registos já existentes, baseando-se em endereços de e-mail correspondentes.
<br><br>Exemplo: Como um utilizador, quando vejo uma Conta, poderei ver todos os e-mails que estão associados à Conta, baseando-se no endereço de e-mail do registo da Conta. Poderei também ver e-mails que estão associados aos Contactos relacionados com a Conta.
<br><br>Aceite os termos em baixo e clique em Activar para começar a utilizar o serviço. Poderá desactivar o serviço em qualquer altura. Quando o serviço estiver activo, será mostrado o endereço de e-mail a utilizar com o serviço.
<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Falha ao contactar o serviço Arquivo de E-mail: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Sugar EASe',
    'LBL_DISABLE_SNIP' => 'Desactivar',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Chave Única da Aplicação',
    'LBL_SNIP_USER' => 'Utilizador de Arquivo de E-mail',
    'LBL_SNIP_PWD' => 'Palavra-passe de Arquivo de E-mail',
    'LBL_SNIP_SUGAR_URL' => 'O URL desta instância Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL do serviço de Arquivo de E-mail',
    'LBL_SNIP_USER_DESC' => 'Utilizador de Arquivo de E-mail',
    'LBL_SNIP_KEY_DESC' => 'Email de Arquivo da chave OAuth. Usado para aceder a esta instância para fins de importação de emails.',
    'LBL_SNIP_STATUS_OK' => 'Activo',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Esta instância do Sugar foi ligada com sucesso ao servidor de Arquivo de Email.',
    'LBL_SNIP_STATUS_ERROR' => 'Erro',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Esta instância tem uma licença válida do servidor de Arquivo de E-mail, mas o servidor devolveu a seguinte mensagem de erro:',
    'LBL_SNIP_STATUS_FAIL' => 'Não é possível registar no servidor de Arquivo de E-mail',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'O serviço de Arquivo de E-mail está atualmente indisponível. Ou o serviço está em baixo ou a ligação para esta instância de Sugar falhou.',
    'LBL_SNIP_GENERIC_ERROR' => 'O serviço de Arquivo de E-mail está atualmente indisponível. Ou o serviço está em baixo ou a ligação para esta instância de Sugar falhou.',

	'LBL_SNIP_STATUS_RESET' => 'Ainda não foi executado',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problema: %s',
    'LBL_SNIP_NEVER' => "Nunca",
    'LBL_SNIP_STATUS_SUMMARY' => "Estado do serviço de Arquivo de E-mail:",
    'LBL_SNIP_ACCOUNT' => "Conta",
    'LBL_SNIP_STATUS' => "Estado",
    'LBL_SNIP_LAST_SUCCESS' => "Última execução com sucesso",
    "LBL_SNIP_DESCRIPTION" => "O serviço de Arquivo de E-mail é um sistema de arquivo de e-mails automático",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Permite-lhe ver emails que foram enviados de ou para os seus contactos dentro do SugarCRM, sem ter que importar manualmente e ligar os emails",
    "LBL_SNIP_PURCHASE_SUMMARY" => "De forma a usar o Arquivo de E-mail, terá que comprar a licença para a sua instância SugarCRM",
    "LBL_SNIP_PURCHASE" => "Clique aqui para comprar",
    'LBL_SNIP_EMAIL' => 'Endereço de Arquivo de E-mail',
    'LBL_SNIP_AGREE' => "Concordo com os termos acima e o <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>acordo de privacidade</a>.",
    'LBL_SNIP_PRIVACY' => 'acordo de privacidade',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Falhou o pingback',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'O servidor de Arquivo de E-mail é incapaz de estabelecer uma ligação à instância do Sugar. Tente de novo ou <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">contacte o suporte ao cliente</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Activar o Arquivo de E-mail',
    'LBL_SNIP_BUTTON_DISABLE' => 'Desactivar o Arquivo de E-mail',
    'LBL_SNIP_BUTTON_RETRY' => 'Tentar a Ligação Novamente',
    'LBL_SNIP_ERROR_DISABLING' => 'Ocorreu um erro ao tentar comunicar com o servidor de Arquivo de E-mail e não foi possível desactivar o serviço',
    'LBL_SNIP_ERROR_ENABLING' => 'Erro - O Servidor Sugar EASe não pode ser contactado, portanto o serviço não foi habilitado',
    'LBL_CONTACT_SUPPORT' => 'Tente novamente ou contacte o Suporte do SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Contacte o Suporte do SugarCRM para obter assistência.',
    'ERROR_BAD_RESULT' => 'Mão resultado devolvido pelo serviço',
	'ERROR_NO_CURL' => 'A extensão cURL é necessária',
	'ERROR_REQUEST_FAILED' => 'Não foi possível contactar o servidor',

    'LBL_CANCEL_BUTTON_TITLE' => 'Cancelar',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Este é o estado do serviço de Arquivo de E-mail na sua instância. O estado reflecte se a comunicação entre o servidor de Arquivo de E-mail e a sua instância Sugar é bem sucedida.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Este é o endereço de e-mail de Arquivo de E-mail para onde será efetuado o envio, de modo a importar e-mails para o Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Este é o URL do servidor de Arquivo de E-mail. Todos os pedidos, como activar e desactivar o serviço de Arquivo de E-mail, serão enviados através deste URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Este é o URL de webservices da sua instância Sugar. O Servidor de Arquivo de E-mail irá ligar-se ao seu servidor através deste URL.',
);
