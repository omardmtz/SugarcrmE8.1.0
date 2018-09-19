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
  'LBL_MODULE_NAME' => 'Regras de negócios de processos',
  'LBL_MODULE_TITLE' => 'Regras de negócios de processos',
  'LBL_MODULE_NAME_SINGULAR' => 'Regra de negócios de processo',

  'LBL_RST_UID' => 'ID da regra de negócio',
  'LBL_RST_TYPE' => 'Tipo de regra de negócios',
  'LBL_RST_DEFINITION' => 'Definição de regra de negócio',
  'LBL_RST_EDITABLE' => 'Regra de negócio editável',
  'LBL_RST_SOURCE' => 'Origem da regra de negócio',
  'LBL_RST_SOURCE_DEFINITION' => 'Definição de origem da regra de negócio',
  'LBL_RST_MODULE' => 'Módulo de destino',
  'LBL_RST_FILENAME' => 'Nome do arquivo da regra de negócio',
  'LBL_RST_CREATE_DATE' => 'Data de criação da regra de negócio',
  'LBL_RST_UPDATE_DATE' => 'Data da atualização da regra de negócio',

    'LNK_LIST' => 'Visualizar regras de negócios de processo',
    'LNK_NEW_PMSE_BUSINESS_RULES' => 'Criar Regra de Negócios de Processos',
    'LNK_IMPORT_PMSE_BUSINESS_RULES' => 'Importar Regras de Negócios de Processos',

    'LBL_PMSE_TITLE_BUSINESS_RULES_BUILDER' => 'Construtor de regras de negócio',

    'LBL_PMSE_LABEL_DESIGN' => 'Design',
    'LBL_PMSE_LABEL_EXPORT' => 'Exportar',
    'LBL_PMSE_LABEL_DELETE' => 'Excluir',

    'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL' => 'Salvar e Sair',
    'LBL_PMSE_SAVE_DESIGN_BUTTON_LABEL' => 'Salvar e Projetar',
    'LBL_PMSE_IMPORT_BUTTON_LABEL' => 'Importar',

    'LBL_PMSE_MY_BUSINESS_RULES' => 'Minhas regras de negócio de processo',
    'LBL_PMSE_ALL_BUSINESS_RULES' => 'Todas as regras de negócio de processo',

    'LBL_PMSE_BUSINESS_RULES_SINGLE_HIT' => 'Ocorrência única de regras de negócio de processo',

    'LBL_PMSE_BUSINESS_RULES_IMPORT_TEXT' => 'Crie automaticamente um novo registro de regra de negócio de processo importando um arquivo *.pbr de seu sistema de arquivos.',
    'LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS' => 'A regra de negócio de processo foi importada com êxito no sistema.',
    'LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING' => 'Selecione um arquivo *.pbr válido.',

    'LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE' => 'Tipo de dados sem suporte.',
    'LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE' => 'Defina primeiro o tipo de coluna.',
    'LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE' => 'A conclusão de "Retorno" está vazia',
    'LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR' => 'expressão ou operador ausente',
    'LBL_PMSE_MESSAGE_LABEL_DELETE_ROW' => 'Deseja mesmo excluir esse conjunto de regras?',
    'LBL_PMSE_MESSAGE_LABEL_MIN_ROWS' => 'A tabela de decisão deve ter pelo menos uma linha',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS' => 'A tabela de decisão deve ter pelo menos uma coluna de condição',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS' => 'A tabela de decisão deve ter pelo menos uma coluna de conclusão',
    'LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE' => 'Valores associados a essa variável serão removidos. Deseja continuar?',
    'LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE' => 'Deseja realmente remover essa variável?',

    'LBL_PMSE_LABEL_CONDITIONS' => 'Condições',
    'LBL_PMSE_LABEL_RETURN' => 'Retornar',
    'LBL_PMSE_LABEL_CONCLUSIONS' => 'Conclusões',
    'LBL_PMSE_LABEL_CHANGE_FIELD' => 'Alterar campo',
    'LBL_PMSE_LABEL_RETURN_VALUE' => 'Devolver valor',

    'LBL_PMSE_TOOLTIP_ADD_CONDITION' => 'Adicionar condição',
    'LBL_PMSE_TOOLTIP_ADD_CONCLUSION' => 'Adicionar conclusão',
    'LBL_PMSE_TOOLTIP_ADD_ROW' => 'Adicionar linha',
    'LBL_PMSE_TOOLTIP_REMOVE_COLUMN' => 'Remover coluna',
    'LBL_PMSE_TOOLTIP_REMOVE_CONDITION' => 'Remover condição',
    'LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION' => 'Remover conclusão',
    'LBL_PMSE_TOOLTIP_REMOVE_COL_DATA' => 'Remover dados da coluna',

    'LBL_PMSE_DROP_DOWN_CHECKED' => 'Sim',
    'LBL_PMSE_DROP_DOWN_UNCHECKED' => 'Não',
    'LBL_PMSE_IMPORT_BUSINESS_RULES_FAILURE' => 'Falha ao criar a regra de negócio do processo a partir do arquivo',

    'LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES' => 'Esta regra de negócio é inválida, já que ela usa campos inválidos ou campos que não foram encontrados em sua instância do SugarCRM. Corrija os erros abaixo e salve a regra de negócios.',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT' => 'Esta regra de negócio está sendo usada em uma definição de processo. Ainda deseja editar esta regra de negócio?',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE' => "Não é possível excluir essa regra de negócio, porque ela está sendo usada no momento em uma definição de processo.",
);
