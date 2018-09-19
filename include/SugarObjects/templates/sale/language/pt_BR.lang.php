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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'Venda',
  'LBL_MODULE_TITLE' => 'Venda: Tela Principal',
  'LBL_SEARCH_FORM_TITLE' => 'Pesquisa de Venda',
  'LBL_VIEW_FORM_TITLE' => 'Visualizar Venda',
  'LBL_LIST_FORM_TITLE' => 'Lista de Venda',
  'LBL_SALE_NAME' => 'Nome da Venda:',
  'LBL_SALE' => 'Venda:',
  'LBL_NAME' => 'Nome da Venda',
  'LBL_LIST_SALE_NAME' => 'Nome',
  'LBL_LIST_ACCOUNT_NAME' => 'Nome da Entidade',
  'LBL_LIST_AMOUNT' => 'Valor',
  'LBL_LIST_DATE_CLOSED' => 'Fechar',
  'LBL_LIST_SALE_STAGE' => 'Fase de vendas',
  'LBL_ACCOUNT_ID'=>'ID Entidade',
  'LBL_TEAM_ID' =>'ID Equipe',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Venda - Atualização da Moeda',
  'UPDATE_DOLLARAMOUNTS' => 'Atualizar Valores em dólares americanos (USD)',
  'UPDATE_VERIFY' => 'Verificar Valores',
  'UPDATE_VERIFY_TXT' => 'Verifica se os valores de vendas são números decimais válidos com apenas caracteres numéricos (0-9) e decimais (.)',
  'UPDATE_FIX' => 'Corrigir Valores',
  'UPDATE_FIX_TXT' => 'Tenta corrigir qualquer valor inválido criando um valor com casas decimais válidas a partir do valor atual. Será feito backup de qualquer valor alterado no campo do banco de dados amount_backup. Caso execute este procedimento e observe bugs, não execute novamente antes de restaurar o backup, pois o backup pode ser sobrescrito com novos dados inválidos.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Atualiza os valores em dólares americanos para vendas baseadas nas taxas de câmbio atuais. Este valor é utilizado para calcular Gráficos e Listas de Valores de Moeda.',
  'UPDATE_CREATE_CURRENCY' => 'Criando Nova Moeda:',
  'UPDATE_VERIFY_FAIL' => 'Verificação de registro com falha:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Valor atual:',
  'UPDATE_VERIFY_FIX' => 'Executar Correções daria',
  'UPDATE_INCLUDE_CLOSE' => 'Incluir Registos Fechados',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Novo Valor:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nova Moeda:',
  'UPDATE_DONE' => 'Concluído',
  'UPDATE_BUG_COUNT' => 'Encontrados Bugs e Tentativas de Resolver:',
  'UPDATE_BUGFOUND_COUNT' => 'Bugs Encontrados:',
  'UPDATE_COUNT' => 'Registros atualizados:',
  'UPDATE_RESTORE_COUNT' => 'Valores de Registros Restaurados:',
  'UPDATE_RESTORE' => 'Restaurar Valores',
  'UPDATE_RESTORE_TXT' => 'Restaura valores a partir dos backups criados durante a correcção.',
  'UPDATE_FAIL' => 'Não foi possível atualizar - ',
  'UPDATE_NULL_VALUE' => 'Valor é NULO definindo-o para 0 -',
  'UPDATE_MERGE' => 'Fundir Moedas',
  'UPDATE_MERGE_TXT' => 'Fundir múltiplas moedas numa única moeda. Se houver múltiplos registos para a mesma moeda, irá fundi-los num só. Isto irá fundir igualmente as moedas para todos os outros módulos.',
  'LBL_ACCOUNT_NAME' => 'Nome da conta:',
  'LBL_AMOUNT' => 'Valor:',
  'LBL_AMOUNT_USDOLLAR' => 'Valor em USD:',
  'LBL_CURRENCY' => 'Moeda:',
  'LBL_DATE_CLOSED' => 'Data Fechada Prevista:',
  'LBL_TYPE' => 'Tipo:',
  'LBL_CAMPAIGN' => 'Campanha:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projectos',  
  'LBL_NEXT_STEP' => 'Próximo passo:',
  'LBL_LEAD_SOURCE' => 'Origem da Lead:',
  'LBL_SALES_STAGE' => 'Fase de vendas:',
  'LBL_PROBABILITY' => 'Probabilidade (%):',
  'LBL_DESCRIPTION' => 'Descrição:',
  'LBL_DUPLICATE' => 'Venda Possivelmente Duplicada',
  'MSG_DUPLICATE' => 'O registro de Venda que está prestes a criar pode ser um duplicado de um registro de venda já existente. Os registros de Venda contendo nomes semelhantes estão listados abaixo. <br>Clique Gravar para continuar a criar esta nova Venda, ou clique Cancelar para voltar ao módulo sem criar a venda.',
  'LBL_NEW_FORM_TITLE' => 'Criar Venda',
  'LNK_NEW_SALE' => 'Criar Venda',
  'LNK_SALE_LIST' => 'Venda',
  'ERR_DELETE_RECORD' => 'Um número de registro deve ser especificado para excluir a venda.',
  'LBL_TOP_SALES' => 'Minha Maior Venda em Aberto',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Tem certeza que pretende remover este contato da venda?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Tem certeza que pretende remover esta venda do projeto?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Actividades',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Histórico',
    'LBL_RAW_AMOUNT'=>'Valor Bruto',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactos',
	'LBL_ASSIGNED_TO_NAME' => 'Usuário:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuário Designado',
  'LBL_MY_CLOSED_SALES' => 'Minhas Vendas Fechadas',
  'LBL_TOTAL_SALES' => 'Vendas Totais',
  'LBL_CLOSED_WON_SALES' => 'Vendas Fechadas Ganhas',
  'LBL_ASSIGNED_TO_ID' =>'Atribuído a',
  'LBL_CREATED_ID'=>'Criado por ID',
  'LBL_MODIFIED_ID'=>'Modificado por ID',
  'LBL_MODIFIED_NAME'=>'Nome de Utilizador Modificado por',
  'LBL_SALE_INFORMATION'=>'Informação de Venda',
  'LBL_CURRENCY_ID'=>'ID da Moeda',
  'LBL_CURRENCY_NAME'=>'Nome da Moeda',
  'LBL_CURRENCY_SYMBOL'=>'Símbolo da moeda',
  'LBL_EDIT_BUTTON' => 'Editar',
  'LBL_REMOVE' => 'Remover',
  'LBL_CURRENCY_RATE' => 'Taxa de câmbio',

);

