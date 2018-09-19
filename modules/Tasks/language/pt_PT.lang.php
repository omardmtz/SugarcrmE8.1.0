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
  'LBL_TASKS_LIST_DASHBOARD' => 'Dashboard de lista de tarefas',

  'LBL_MODULE_NAME' => 'Tarefas',
  'LBL_MODULE_NAME_SINGULAR' => 'Tarefa',
  'LBL_TASK' => 'Tarefas:',
  'LBL_MODULE_TITLE' => 'Tarefas: Ecrã Principal',
  'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Tarefas',
  'LBL_LIST_FORM_TITLE' => 'Lista de Tarefas',
  'LBL_NEW_FORM_TITLE' => 'Nova Tarefa',
  'LBL_NEW_FORM_SUBJECT' => 'Assunto:',
  'LBL_NEW_FORM_DUE_DATE' => 'Data Limite:',
  'LBL_NEW_FORM_DUE_TIME' => 'Hora Limite:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Fechar',
  'LBL_LIST_SUBJECT' => 'Assunto',
  'LBL_LIST_CONTACT' => 'Contacto',
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
  'LBL_START_DATE_AND_TIME' => 'Data & Hora de Início:',
  'LBL_START_DATE' => 'Data de Início:',
  'LBL_LIST_START_DATE' => 'Data Início:',
  'LBL_START_TIME' => 'Hora de Início:',
  'LBL_LIST_START_TIME' => 'Hora de Início',
  'DATE_FORMAT' => '(aaaa-mm-dd)',
  'LBL_NONE' => 'Nenhum',
  'LBL_CONTACT' => 'Contacto:',
  'LBL_EMAIL_ADDRESS' => 'Endereço de E-mail',
  'LBL_PHONE' => 'Telefone:',
  'LBL_EMAIL' => 'E-mail:',
  'LBL_DESCRIPTION_INFORMATION' => 'Informação da Descrição',
  'LBL_DESCRIPTION' => 'Descrição',
  'LBL_NAME' => 'Nome:',
  'LBL_CONTACT_NAME' => 'Nome do Contacto ',
  'LBL_LIST_COMPLETE' => 'Completar:',
  'LBL_LIST_STATUS' => 'Estado',
  'LBL_DATE_DUE_FLAG' => 'Sem Data Limite',
  'LBL_DATE_START_FLAG' => 'Sem Data de Início',
  'ERR_DELETE_RECORD' => 'Um número de registo deverá ser especificado para eliminar este contacto.',
  'ERR_INVALID_HOUR' => 'Por favor indique uma hora entre as 00:00 e as 24:00',
  'LBL_DEFAULT_PRIORITY' => 'Média',
  'LBL_LIST_MY_TASKS' => 'As Minhas Tarefas em Aberto',
  'LNK_NEW_TASK' => 'Nova Tarefa',
  'LNK_TASK_LIST' => 'Ver Tarefas',
  'LNK_IMPORT_TASKS' => 'Importar Tarefas',
  'LBL_CONTACT_FIRST_NAME'=>'Nome Próprio do Contacto',
  'LBL_CONTACT_LAST_NAME'=>'Último Nome do Contacto',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Utilizador Atribuído',
  'LBL_ASSIGNED_TO_NAME'=>'Atribuído a:',
  'LBL_LIST_DATE_MODIFIED' => 'Data de Modificação',
  'LBL_CONTACT_ID' => 'ID Contacto:',
  'LBL_PARENT_ID' => 'ID Pai:',
  'LBL_CONTACT_PHONE' => 'Telefone de Contacto:',
  'LBL_PARENT_NAME' => 'Tipo de Origem:',
  'LBL_ACTIVITIES_REPORTS' => 'Relatório de Atividades',
  'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Informação da Tarefa',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notas',
  'LBL_REVENUELINEITEMS' => 'Itens de Linha de Receita',
  //For export labels
  'LBL_DATE_DUE' => 'Data Limite',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nome do Utilizador Atribuído',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID de Utilizador Atribuído',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'ID de Modificado Por',
  'LBL_EXPORT_CREATED_BY' => 'ID do Utilizador que Criou',
  'LBL_EXPORT_PARENT_TYPE' => 'Relacionado com o módulo',
  'LBL_EXPORT_PARENT_ID' => 'ID de Relacionado Com',
  'LBL_TASK_CLOSE_SUCCESS' => 'Tarefa fechada com sucesso.',
  'LBL_ASSIGNED_USER' => 'Atribuído a',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notas',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'O módulo {{plural_module_name}} consiste em ações flexíveis, itens a fazer ou outros tipos de atividades que necessitem que sejam completadas. Os Registos de {{module_name}} podem estar relacionados com um registo na maior parte dos módulos através do campo relacionado flexível e também poderá estar relacionado com um único {{contacts_singular_module}}. Existem várias maneiras de criar {{plural_module_name}} no Sugar, tais como através do módulo de {{plural_module_name}}, duplicação, importação de {{plural_module_name}}, etc. Assim que o registo de {{module_name}} for criado, poderá visualizar e editar a informação pertencente à {{module_name}} através da visualização do registo de {{plural_module_name}}. Dependendo dos detalhes de {{module_name}}, poderá também ver e editar a informação de {{module_name}} através do módulo Calendário. Cada registo de {{module_name}} poderá estar relacionado com outros registo no Sugar como por exemplo: {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} e muitos outros.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'O módulo {{plural_module_name}} consiste em ações flexíveis, itens a fazer ou outros tipos de atividades que necessitem que sejam concluídas. 

- Edite os campos deste registo clicando num campo individualmente ou no botão Editar.
- Visualize ou modifique ligações para outros registos nos subpainéis alternando o painel esquerdo inferior para "Visualização de Dados".
- Crie e visualize comentários de utilizador e o histórico de alterações de registos no {{activitystream_singular_module}} alternando o painel esquerdo inferior para "Fluxo de Atividades".
- Siga ou torne favorito este registo utilizando os ícones à direita do nome do registo.
- Ações adicionais estão disponíveis no menu de seleção Ações à direita do botão Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'O módulo {{plural_module_name}} consiste em ações flexíveis, itens a fazer ou outro tipo de atividade que necessite que seja concluída. 

Para criar um {{module_name}}:
1. Forneça valores para os campos conforme pretendido.
 - Os campos marcados como "Obrigatório" deverão ser preenchidos antes de gravar.
 - Clique em "Mostrar Mais" para mostrar campos adicionais se necessário.
2. Clique em "Gravar" para finalizar o novo registo e regressar à página anterior.',

);
