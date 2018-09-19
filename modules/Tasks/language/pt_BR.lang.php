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

$mod_strings = array (
  // Dashboard Names
  'LBL_TASKS_LIST_DASHBOARD' => 'Painel da lista de tarefas',

  'LBL_MODULE_NAME' => 'Tarefas',
  'LBL_MODULE_NAME_SINGULAR' => 'Tarefa',
  'LBL_TASK' => 'Tarefas:',
  'LBL_MODULE_TITLE' => 'Tarefas: Tela Principal',
  'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Tarefas',
  'LBL_LIST_FORM_TITLE' => 'Lista de Tarefas',
  'LBL_NEW_FORM_TITLE' => ' Criar Tarefa',
  'LBL_NEW_FORM_SUBJECT' => 'Assunto:',
  'LBL_NEW_FORM_DUE_DATE' => 'Data Limite:',
  'LBL_NEW_FORM_DUE_TIME' => 'Hora Limite:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Fechar',
  'LBL_LIST_SUBJECT' => 'Assunto',
  'LBL_LIST_CONTACT' => 'Contato',
  'LBL_LIST_PRIORITY' => 'Prioridade',
  'LBL_LIST_RELATED_TO' => 'Referente a',
  'LBL_LIST_DUE_DATE' => 'Data Limite',
  'LBL_LIST_DUE_TIME' => 'Hora Limite',
  'LBL_SUBJECT' => 'Assunto:',
  'LBL_STATUS' => 'Estado:',
  'LBL_DUE_DATE' => 'Data Limite:',
  'LBL_DUE_TIME' => 'Hora Limite:',
  'LBL_PRIORITY' => 'Prioridade:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Data e Hora Limites:',
  'LBL_START_DATE_AND_TIME' => 'Data e Hora de Iní­cio:',
  'LBL_START_DATE' => 'Data de Início:',
  'LBL_LIST_START_DATE' => 'Data Início:',
  'LBL_START_TIME' => 'Hora Inicial:',
  'LBL_LIST_START_TIME' => 'Hora Inicial:',
  'DATE_FORMAT' => '(aaaa-mm-dd)',
  'LBL_NONE' => 'Nenhum',
  'LBL_CONTACT' => 'Contato:',
  'LBL_EMAIL_ADDRESS' => 'Endereço de E-mail',
  'LBL_PHONE' => 'Telefone:',
  'LBL_EMAIL' => 'E-mail:',
  'LBL_DESCRIPTION_INFORMATION' => 'Informações de descrição',
  'LBL_DESCRIPTION' => 'Descrição',
  'LBL_NAME' => 'Nome:',
  'LBL_CONTACT_NAME' => 'Nome do Contato:',
  'LBL_LIST_COMPLETE' => 'Completar:',
  'LBL_LIST_STATUS' => 'Estado',
  'LBL_DATE_DUE_FLAG' => 'Sem Data Limite',
  'LBL_DATE_START_FLAG' => 'Sem Data de Início',
  'ERR_DELETE_RECORD' => 'Um número de registro deverá ser especificado para excluir este contato.',
  'ERR_INVALID_HOUR' => 'Indique uma hora entre às 00:00 e às 24:00',
  'LBL_DEFAULT_PRIORITY' => 'Média',
  'LBL_LIST_MY_TASKS' => 'As Minhas Tarefas em Aberto',
  'LNK_NEW_TASK' => 'Criar Tarefa',
  'LNK_TASK_LIST' => 'Visualizar Tarefas',
  'LNK_IMPORT_TASKS' => 'Importar Tarefas',
  'LBL_CONTACT_FIRST_NAME'=>'Primeiro Nome do Contato',
  'LBL_CONTACT_LAST_NAME'=>'Último Nome do Contato',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuário Designado',
  'LBL_ASSIGNED_TO_NAME'=>'Atribuído a:',
  'LBL_LIST_DATE_MODIFIED' => 'Data de Modificação',
  'LBL_CONTACT_ID' => 'ID Contato:',
  'LBL_PARENT_ID' => 'ID pai:',
  'LBL_CONTACT_PHONE' => 'Telefone de Contato:',
  'LBL_PARENT_NAME' => 'Tipo de pai:',
  'LBL_ACTIVITIES_REPORTS' => 'Relatório de Atividades',
  'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Visão Geral',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notas ou Anexos',
  'LBL_REVENUELINEITEMS' => 'Itens da linha de receita',
  //For export labels
  'LBL_DATE_DUE' => 'Data Limite',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nome de usuário atribuído',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID de usuário atribuído',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificado Por ID',
  'LBL_EXPORT_CREATED_BY' => 'Criado pelo ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Relacionado ao Módulo',
  'LBL_EXPORT_PARENT_ID' => 'Relacionado ao ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Tarefa fechada com sucesso.',
  'LBL_ASSIGNED_USER' => 'Atribuído a',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Anotaçãoes:',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'O módulo {{plural_module_name}} é composto por ações flexíveis, itens pendentes ou outro tipo de atividade que requer conclusão. Os registros {{module_name}} podem ser relacionados a um registro na maioria dos módulos pelo campo flexionado relacionado e também podem estar relacionados a um único {{contacts_singular_module}}.
Há várias formas de criar {{plural_module_name}} no Sugar como pelo módulo {{plural_module_name}}, duplicação, importação {{plural_module_name}}, etc. Uma vez que o registro {{module_name}} é criado, você pode visualizar e editar as informações relativas ao {{module_name}} pela visualização do registro {{plural_module_name}}. Dependendo dos detalhes no {{module_name}}, você também pode visualizar e editar as informações do {{module_name}} pelo módulo Calendário. Cada registro {{module_name}} pode se relacionar com outros registros Sugar como {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} e muitos outros.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'O módulo {{plural_module_name}} é composto por ações flexíveis, itens pendentes ou outro tipo de atividade que requer conclusão. 

- Edite os campos do registro clicando em um campo individual ou no botão Editar. 
- Exiba ou modifique os links a outros registros nos subpainéis alternando no painel esquerdo inferior para "Visualização de dados". 
- Faça e veja comentários de usuários e histórico de alterações de registro no {{activitystream_singular_module}} alternando no painel esquerdo inferior para "Cadeia de Atividades". 
- Siga ou marque este registro como favorito usando os ícones à direita do nome do registro. 
- Ações adicionais estão disponíveis no menu suspenso Ações à direita do botão Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'O módulo {{plural_module_name}} é composto por ações flexíveis, itens pendentes ou outro tipo de atividade que requer conclusão. 

Para criar um {{module_name}}:
 1. Forneça valores para os campos conforme desejado. 
- Os campos marcados como "obrigatórios" devem ser preenchidos antes de salvar. 
- Clique em "Visualizar mais" para expor campos adicionais, se necessário. 
2. Clique em "Salvar" para finalizar o novo registro e voltar para a página anterior.',

);
