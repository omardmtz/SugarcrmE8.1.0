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
  'LBL_MODULE_TITLE' => 'Venda: Ecrã Principal',
  'LBL_SEARCH_FORM_TITLE' => 'Pesquisa de Venda',
  'LBL_VIEW_FORM_TITLE' => 'Ver Venda',
  'LBL_LIST_FORM_TITLE' => 'Lista de Venda',
  'LBL_SALE_NAME' => 'Nome da Venda:',
  'LBL_SALE' => 'Venda:',
  'LBL_NAME' => 'Nome da Venda',
  'LBL_LIST_SALE_NAME' => 'Nome',
  'LBL_LIST_ACCOUNT_NAME' => 'Nome da Entidade',
  'LBL_LIST_AMOUNT' => 'Valor',
  'LBL_LIST_DATE_CLOSED' => 'Fechar',
  'LBL_LIST_SALE_STAGE' => 'Fase da Venda',
  'LBL_ACCOUNT_ID'=>'ID Entidade',
  'LBL_TEAM_ID' =>'ID Equipa',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Venda - Atualização de Moeda',
  'UPDATE_DOLLARAMOUNTS' => 'Actualizar Valores em U.S. Dollar',
  'UPDATE_VERIFY' => 'Verificar Valores',
  'UPDATE_VERIFY_TXT' => 'Verifica se os valores de vendas são números decimais válidos com apenas caracteres numéricos (0-9) e decimais (.)',
  'UPDATE_FIX' => 'Corrigir Valores',
  'UPDATE_FIX_TXT' => 'Tenta corrigir qualquer valor inválido criando um valor com casas decimais a partir do valor atual. Será efetuado o backup de qualquer valor alterado na base de dados. Caso execute este procedimento e obtenha mensagens de erros, não execute novamente antes de restaurar o backup, caso contrário é possível que o backup seja substituído por novos dados inválidos.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Atualiza os valores em dólares norte-americanos para vendas baseados nas taxas de câmbio actuais. Este valor é utilizado para calcular Gráficos e Listas de Valores de Moeda.',
  'UPDATE_CREATE_CURRENCY' => 'Criando Nova Moeda:',
  'UPDATE_VERIFY_FAIL' => 'Falha na Verificação de Registo:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Valor Actual:',
  'UPDATE_VERIFY_FIX' => 'Correcção actual daria',
  'UPDATE_INCLUDE_CLOSE' => 'Incluir Registos Fechados',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Novo Valor:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nova Moeda:',
  'UPDATE_DONE' => 'Concluído',
  'UPDATE_BUG_COUNT' => 'Erros Encontrados e Tentativas de Resolução:',
  'UPDATE_BUGFOUND_COUNT' => 'Erros Encontrados:',
  'UPDATE_COUNT' => 'Registos Actualizados:',
  'UPDATE_RESTORE_COUNT' => 'Valores de Registo Restaurados:',
  'UPDATE_RESTORE' => 'Restaurar Valores',
  'UPDATE_RESTORE_TXT' => 'Restaura valores a partir dos backups criados durante a correcção.',
  'UPDATE_FAIL' => 'Não foi possível actualizar -',
  'UPDATE_NULL_VALUE' => 'Valor é NULO definindo-o para 0 -',
  'UPDATE_MERGE' => 'Fundir Moedas',
  'UPDATE_MERGE_TXT' => 'Fundir múltiplas moedas numa única moeda. Se existirem múltiplos registos para a mesma moeda, irá fundi-los num só. Isto irá fundir igualmente as moedas para todos os outros módulos.',
  'LBL_ACCOUNT_NAME' => 'Nome da Entidade:',
  'LBL_AMOUNT' => 'Valor:',
  'LBL_AMOUNT_USDOLLAR' => 'Valor em USD:',
  'LBL_CURRENCY' => 'Moeda:',
  'LBL_DATE_CLOSED' => 'Data de Fecho Esperada:',
  'LBL_TYPE' => 'Tipo:',
  'LBL_CAMPAIGN' => 'Campanha:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Clientes Potenciais',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projetos',  
  'LBL_NEXT_STEP' => 'Próximo Passo:',
  'LBL_LEAD_SOURCE' => 'Origem do Cliente Potencial:',
  'LBL_SALES_STAGE' => 'Fase da Venda:',
  'LBL_PROBABILITY' => 'Probabilidade (%):',
  'LBL_DESCRIPTION' => 'Descrição:',
  'LBL_DUPLICATE' => 'Venda Possivelmente Duplicada',
  'MSG_DUPLICATE' => 'O registo de Venda que está prestes a criar pode ser um duplicado de um registo de venda já existente. Os registos de Venda contendo nomes semelhantes estão listados abaixo. <br>Clique em Gravar para continuar a criar esta nova Venda ou clique em Cancelar para voltar ao módulo sem criar a venda.',
  'LBL_NEW_FORM_TITLE' => 'Criar Venda',
  'LNK_NEW_SALE' => 'Criar Venda',
  'LNK_SALE_LIST' => 'Venda',
  'ERR_DELETE_RECORD' => 'Um número de registo deve ser especificado para eliminar a venda.',
  'LBL_TOP_SALES' => 'A Minha Maior Venda em Aberto',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Tem a certeza de que pretende remover este contacto da venda?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Tem a certeza de que pretende remover esta venda do projecto?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Atividades',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Histórico',
    'LBL_RAW_AMOUNT'=>'Valor Bruto',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactos',
	'LBL_ASSIGNED_TO_NAME' => 'Utilizador:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Utilizador Atribuído',
  'LBL_MY_CLOSED_SALES' => 'As Minhas Vendas Fechadas',
  'LBL_TOTAL_SALES' => 'Vendas Totais',
  'LBL_CLOSED_WON_SALES' => 'Vendas Ganhas',
  'LBL_ASSIGNED_TO_ID' =>'Atribuído ao ID',
  'LBL_CREATED_ID'=>'ID Criado por',
  'LBL_MODIFIED_ID'=>'ID de Modificado por',
  'LBL_MODIFIED_NAME'=>'Nome de Utilizador Modificado por',
  'LBL_SALE_INFORMATION'=>'Informação de Venda',
  'LBL_CURRENCY_ID'=>'ID Moeda',
  'LBL_CURRENCY_NAME'=>'Nome da Moeda',
  'LBL_CURRENCY_SYMBOL'=>'Símbolo da Moeda',
  'LBL_EDIT_BUTTON' => 'Editar',
  'LBL_REMOVE' => 'Remover',
  'LBL_CURRENCY_RATE' => 'Taxa de Câmbio',

);

