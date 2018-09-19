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

$mod_strings = array(
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Painel de lista de notas',

    'ERR_DELETE_RECORD' => 'Um número de registro deve ser especificado para excluir a Conta.',
    'LBL_ACCOUNT_ID' => 'ID da Conta',
    'LBL_CASE_ID' => 'ID da Ocorrência',
    'LBL_CLOSE' => 'Fechar',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID do Contato',
    'LBL_CONTACT_NAME' => 'Contato',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notas ou Anexos',
    'LBL_DESCRIPTION' => 'Descrição',
    'LBL_EMAIL_ADDRESS' => 'E-mail',
    'LBL_EMAIL_ATTACHMENT' => 'Anexo do E-mail',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Anexo de e-mail para',
    'LBL_FILE_MIME_TYPE' => 'Tipo de Mime',
    'LBL_FILE_EXTENSION' => 'Extensão do arquivo',
    'LBL_FILE_SOURCE' => 'Origem do arquivo',
    'LBL_FILE_SIZE' => 'Tamanho do arquivo',
    'LBL_FILE_URL' => 'URL do Arquivo',
    'LBL_FILENAME' => 'Anexo:',
    'LBL_LEAD_ID' => 'ID do Potencial:',
    'LBL_LIST_CONTACT_NAME' => 'Contato',
    'LBL_LIST_DATE_MODIFIED' => 'Última modificação',
    'LBL_LIST_FILENAME' => 'Anexo',
    'LBL_LIST_FORM_TITLE' => 'Lista de Notas',
    'LBL_LIST_RELATED_TO' => 'Referente a',
    'LBL_LIST_SUBJECT' => 'Assunto',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_CONTACT' => 'Contato',
    'LBL_MODULE_NAME' => 'Notas ou anexos',
    'LBL_MODULE_NAME_SINGULAR' => 'Nota',
    'LBL_MODULE_TITLE' => 'Notas: Tela Principal',
    'LBL_NEW_FORM_TITLE' => 'Criar Nota ou Adicionar Anexo',
    'LBL_NEW_FORM_BTN' => 'Adicionar uma Nota',
    'LBL_NOTE_STATUS' => 'Nota',
    'LBL_NOTE_SUBJECT' => 'Assunto',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notas e Anexos',
    'LBL_NOTE' => 'Nota:',
    'LBL_OPPORTUNITY_ID' => 'ID da Oportunidade:',
    'LBL_PARENT_ID' => 'ID pai:',
    'LBL_PARENT_TYPE' => 'Tipo de Referência',
    'LBL_EMAIL_TYPE' => 'Tipo de e-mail',
    'LBL_EMAIL_ID' => 'ID de e-mail',
    'LBL_PHONE' => 'Telefone',
    'LBL_PORTAL_FLAG' => 'Exibir no Portal?',
    'LBL_EMBED_FLAG' => 'Embutido no E-mail?',
    'LBL_PRODUCT_ID' => 'ID de Item de linha cotado:',
    'LBL_QUOTE_ID' => 'ID da Cotação:',
    'LBL_RELATED_TO' => 'Referente a:',
    'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Notas',
    'LBL_STATUS' => 'Estado',
    'LBL_SUBJECT' => 'Assunto',
    'LNK_IMPORT_NOTES' => 'Importar notas',
    'LNK_NEW_NOTE' => 'Criar nota ou anexo',
    'LNK_NOTE_LIST' => 'Ver notas',
    'LBL_MEMBER_OF' => 'Membro de:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuário Designado',
    'LBL_OC_FILE_NOTICE' => 'Faça log-in no servidor para ver o arquivo',
    'LBL_REMOVING_ATTACHMENT' => 'Removendo anexo...',
    'ERR_REMOVING_ATTACHMENT' => 'Falha ao remover anexo...',
    'LBL_CREATED_BY' => 'Criado por',
    'LBL_MODIFIED_BY' => 'Modificado Por',
    'LBL_SEND_ANYWAYS' => 'Este e-mail está sem assunto. Deseja enviar/salvar mesmo assim?',
    'LBL_LIST_EDIT_BUTTON' => 'Editar',
    'LBL_ACTIVITIES_REPORTS' => 'Relatório de Atividades',
    'LBL_PANEL_DETAILS' => 'Detalhes',
    'LBL_NOTE_INFORMATION' => 'Visão Geral',
    'LBL_MY_NOTES_DASHLETNAME' => 'Minhas Notas',
    'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Primeiro Nome',
    'LBL_LAST_NAME' => 'Sobrenome',
    'LBL_EXPORT_PARENT_TYPE' => 'Relacionado ao Módulo',
    'LBL_EXPORT_PARENT_ID' => 'Relacionado ao ID',
    'LBL_DATE_ENTERED' => 'Data de criação',
    'LBL_DATE_MODIFIED' => 'Data de Modificação',
    'LBL_DELETED' => 'Excluído',
    'LBL_REVENUELINEITEMS' => 'Itens da linha de receita',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'O módulo {{plural_module_name}} consiste em um {{plural_module_name}} individuais que contêm texto ou um anexo pertinente ao registro relacionado. Os registros {{module_name}} podem estar relacionados a um registro na maioria dos módulos via o flex campo relacionado e também pode estar relacionado a um único {{contacts_singular_module}}. O {{plural_module_name}} pode conter texto genérico sobre um registro ou mesmo um anexo relacionado ao registro. Existem várias maneiras para criar {{plural_module_name}} no Sugar, como via módulo {{plural_module_name}}, importando {{plural_module_name}}, via subpainéis de Histórico, etc. Uma Vez que o registro do {{module_name}} é criado, você pode visualizar e editar as informações relativas à {{module_name}} via visualização do {{plural_module_name}}. Cada registro do {{module_name}} pode então relacionar com outros registros do Sugar, tais como {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} e muitos outros.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'O módulo {{plural_module_name}} consiste em {{plural_module_name}} individuais que contêm texto ou um anexo pertinente ao registro relacionado. 

- Edite os campos do registro clicando em um campo individual ou no campo Editar.
- Visualize ou modifique links a outros registros nos subpainéis alternando o painel esquerdo inferior para "Visualização de dados".
- Faça e veja comentários de usuários e histórico de alteração de registro no {{activitystream_singular_module}} alternando o painel esquerdo inferior para "Cadeia de atividades".
- Siga ou marque esse registro como favorito usando os ícones à direita do nome do registro.
- Ações adicionais estão disponíveis no menu suspenso Ações à direita do botão Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Para criar um {{module_name}}: 
1. Forneça valores para os campos conforme desejado. 
- Os campos marcados como "obrigatórios" devem ser preenchido antes de salvar. 
- Clique em "Mostrar mais" para expor campos adicionais, se necessário. 
2. Clique em "Salvar" para finalizar o novo registro e retornar à página anterior.',
);
