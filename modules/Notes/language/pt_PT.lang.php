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
    'LBL_NOTES_LIST_DASHBOARD' => 'Dashboard da Lista de Notas',

    'ERR_DELETE_RECORD' => 'Um número de registo deverá ser especificado para eliminar a Conta.',
    'LBL_ACCOUNT_ID' => 'ID da Conta:',
    'LBL_CASE_ID' => 'ID da Ocorrência',
    'LBL_CLOSE' => 'Fechar:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID do Contacto',
    'LBL_CONTACT_NAME' => 'Contacto',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notas ou Anexos',
    'LBL_DESCRIPTION' => 'Descrição',
    'LBL_EMAIL_ADDRESS' => 'E-mail',
    'LBL_EMAIL_ATTACHMENT' => 'Anexo do E-mail',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Anexo de E-mail para',
    'LBL_FILE_MIME_TYPE' => 'Tipo Mime',
    'LBL_FILE_EXTENSION' => 'Extensão de Ficheiro',
    'LBL_FILE_SOURCE' => 'Origem do Ficheiro',
    'LBL_FILE_SIZE' => 'Tamanho do ficheiro',
    'LBL_FILE_URL' => 'URL do Ficheiro',
    'LBL_FILENAME' => 'Anexo:',
    'LBL_LEAD_ID' => 'ID do Cliente Potencial:',
    'LBL_LIST_CONTACT_NAME' => 'Contacto',
    'LBL_LIST_DATE_MODIFIED' => 'Última Modificação',
    'LBL_LIST_FILENAME' => 'Anexo',
    'LBL_LIST_FORM_TITLE' => 'Lista de Notas ou Anexos',
    'LBL_LIST_RELATED_TO' => 'Referente a',
    'LBL_LIST_SUBJECT' => 'Assunto',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_CONTACT' => 'Contacto',
    'LBL_MODULE_NAME' => 'Notas',
    'LBL_MODULE_NAME_SINGULAR' => 'Nota',
    'LBL_MODULE_TITLE' => 'Notas: Ecrã Principal',
    'LBL_NEW_FORM_TITLE' => 'Criar Nota ou Adicionar Anexo',
    'LBL_NEW_FORM_BTN' => 'Adicionar uma nota',
    'LBL_NOTE_STATUS' => 'Nota ou Anexos',
    'LBL_NOTE_SUBJECT' => 'Assunto',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Anexos',
    'LBL_NOTE' => 'Nota:',
    'LBL_OPPORTUNITY_ID' => 'ID da Oportunidade:',
    'LBL_PARENT_ID' => 'ID Pai:',
    'LBL_PARENT_TYPE' => 'Tipo de Referência',
    'LBL_EMAIL_TYPE' => 'Tipo de e-mail',
    'LBL_EMAIL_ID' => 'ID de e-mail',
    'LBL_PHONE' => 'Telefone',
    'LBL_PORTAL_FLAG' => 'Exibir no Portal?',
    'LBL_EMBED_FLAG' => 'Embutido no E-mail?',
    'LBL_PRODUCT_ID' => 'ID do Item de Linha Cotado:',
    'LBL_QUOTE_ID' => 'ID da Cotação:',
    'LBL_RELATED_TO' => 'Referente a:',
    'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Notas ou Anexos',
    'LBL_STATUS' => 'Estado',
    'LBL_SUBJECT' => 'Assunto',
    'LNK_IMPORT_NOTES' => 'Importar Notas ou Anexos',
    'LNK_NEW_NOTE' => 'Nova Nota ou Anexo',
    'LNK_NOTE_LIST' => 'Notas ou Anexos',
    'LBL_MEMBER_OF' => 'Membro de',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Utilizador Atribuído',
    'LBL_OC_FILE_NOTICE' => 'Por favor aceda ao servidor para ver o ficheiro',
    'LBL_REMOVING_ATTACHMENT' => 'A remover anexo...',
    'ERR_REMOVING_ATTACHMENT' => 'Falha ao remover anexo...',
    'LBL_CREATED_BY' => 'Criado por',
    'LBL_MODIFIED_BY' => 'Modificado Por',
    'LBL_SEND_ANYWAYS' => 'Este e-mail está sem assunto. Enviar / Gravar mesmo assim?',
    'LBL_LIST_EDIT_BUTTON' => 'Editar',
    'LBL_ACTIVITIES_REPORTS' => 'Relatório de Atividades',
    'LBL_PANEL_DETAILS' => 'Detalhes',
    'LBL_NOTE_INFORMATION' => 'Informação da Nota',
    'LBL_MY_NOTES_DASHLETNAME' => 'As Minhas Notas',
    'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Nome Próprio',
    'LBL_LAST_NAME' => 'Último Nome',
    'LBL_EXPORT_PARENT_TYPE' => 'Relacionado com o módulo',
    'LBL_EXPORT_PARENT_ID' => 'ID do objecto relacionado',
    'LBL_DATE_ENTERED' => 'Data da Criação',
    'LBL_DATE_MODIFIED' => 'Data da Modificação',
    'LBL_DELETED' => 'Eliminado',
    'LBL_REVENUELINEITEMS' => 'Itens de Linha de Receita',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'O módulo {{plural_module_name}} consiste em {{plural_module_name}} individuais que contêm texto ou um anexo pertinente ao registo relacionado. Os registos de {{module_name}} podem estar relacionados com um registo da maior parte dos módulos via campo relacionado flexível e também relacionados com um único {{contacts_singular_module}}. {{plural_module_name}} podem conter texto genérico sobre um registo ou mesmo um anexo relacionado com o registo. Existem várias maneiras para criar {{plural_module_name}} no Sugar, através do módulo de {{plural_module_name}}, importar {{plural_module_name}}, através de subpainéis Histórico, etc. Assim que o registo de {{module_name}} for criado, poderá visualizar e editar a informação pertencente a {{module_name}} através de uma visualização de registo de {{plural_module_name}}. Cada registo de {{module_name}} poderá estar relacionado com outros registos do Sugar, como {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} e muitos outros.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'O módulo de {{plural_module_name}} consiste em {{plural_module_name}} individuais que contêm texto ou um anexo pertinente ao registo relacionado.

-Edite os campos deste registo clicando num campo individualmente ou no botão Editar.
- Visualize ou modifique ligações para outros registos nos subpainéis alternando o painel esquerdo inferior para "Visualização de Dados".
- Crie e visualize comentários de utilizador e o histórico de alterações de registos no {{activitystream_singular_module}} alternando o painel esquerdo inferior para "Fluxo de Atividades".
- Siga ou torne favorito este registo utilizando os ícones à direita do nome do registo.
- Ações adicionais estão disponíveis no menu de seleção Ações à direita do botão Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Para criar um {{module_name}}:
1. Forneça valores para os campos conforme pretendido.
 - Os campos marcados como "Obrigatório" deverão ser preenchidos antes de gravar.
 - Clique em "Mostrar Mais" para mostrar campos adicionais se necessário.
2. Clique em "Gravar" para finalizar o novo registo e regressar à página anterior.',
);
