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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Painel de lista de oportunidades',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Painel de registro de oportunidades',

    'LBL_MODULE_NAME' => 'Oportunidades',
    'LBL_MODULE_NAME_SINGULAR' => 'Oportunidade',
    'LBL_MODULE_TITLE' => 'Oportunidades: Tela Principal',
    'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Oportunidades',
    'LBL_VIEW_FORM_TITLE' => 'Visualizar Oportunidades',
    'LBL_LIST_FORM_TITLE' => 'Lista de Oportunidades',
    'LBL_OPPORTUNITY_NAME' => 'Nome da Oportunidade',
    'LBL_OPPORTUNITY' => 'Oportunidade:',
    'LBL_NAME' => 'Nome da oportunidade',
    'LBL_INVITEE' => 'Contatos',
    'LBL_CURRENCIES' => 'Moedas',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Oportunidade',
    'LBL_LIST_ACCOUNT_NAME' => 'Nome da Conta',
    'LBL_LIST_DATE_CLOSED' => 'Data prevista de fechamento',
    'LBL_LIST_AMOUNT' => 'Provável',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Valor Convertido',
    'LBL_ACCOUNT_ID' => 'ID da Conta',
    'LBL_CURRENCY_RATE' => 'Taxa da Moeda',
    'LBL_CURRENCY_ID' => 'ID da Moeda',
    'LBL_CURRENCY_NAME' => 'Nome da Moeda',
    'LBL_CURRENCY_SYMBOL' => 'Símbolo da moeda',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Oportunidade - Atualizar Moeda',
    'UPDATE_DOLLARAMOUNTS' => 'Atualizar Valores em dólares americanos (USD)',
    'UPDATE_VERIFY' => 'Verificar Valores',
    'UPDATE_VERIFY_TXT' => 'Verifica se os valores nas Oportunidades são números decimais válidos com apenas caracteres numéricos (0-9) e ponto decimal (.)',
    'UPDATE_FIX' => 'Corrigir Valores',
    'UPDATE_FIX_TXT' => 'Tenta corrigir qualquer valor inválido criando um valor com casas decimais válidas a partir do valor atual. Será feito backup de qualquer valor alterado no campo do banco de dados amount_backup. Caso execute este procedimento e observe bugs, não execute novamente antes de restaurar o backup, pois o backup pode ser sobrescrito com novos dados inválidos.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Atualiza os valores em dólares americanos para Oportunidades com base na taxa de cotação atual. Este valor será utilizado para calcular os Gráficos e Visualização de Lista com Valores de Moeda.',
    'UPDATE_CREATE_CURRENCY' => 'Criando Nova Moeda:',
    'UPDATE_VERIFY_FAIL' => 'Verificação de registro com falha:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Valor atual:',
    'UPDATE_VERIFY_FIX' => 'Executar Correções daria',
    'UPDATE_INCLUDE_CLOSE' => 'Incluir Registros Fechados',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Novo Valor:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nova Moeda:',
    'UPDATE_DONE' => 'Completo',
    'UPDATE_BUG_COUNT' => 'Encontrados Bugs e Tentativas de Resolver:',
    'UPDATE_BUGFOUND_COUNT' => 'Bugs Encontrados:',
    'UPDATE_COUNT' => 'Registros atualizados:',
    'UPDATE_RESTORE_COUNT' => 'Valores de Registros Restaurados:',
    'UPDATE_RESTORE' => 'Restaurar Valores',
    'UPDATE_RESTORE_TXT' => 'Restaurar valores a partir do backup criado durante a Resolução.',
    'UPDATE_FAIL' => 'Não foi possível atualizar - ',
    'UPDATE_NULL_VALUE' => 'Valor é NULO definindo como 0 -',
    'UPDATE_MERGE' => 'Fundir Moedas',
    'UPDATE_MERGE_TXT' => 'Fundir múltiplas Moedas em uma Moeda única. Caso seja informado de que existem múltiplos Registros de moedas para a mesma Moeda, você pode optar por fundi-los. Isso também fundirá Moedas para todos os outros módulos.',
    'LBL_ACCOUNT_NAME' => 'Nome da conta:',
    'LBL_CURRENCY' => 'Moeda:',
    'LBL_DATE_CLOSED' => 'Data Fechada Prevista:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Data prevista de fechamento',
    'LBL_TYPE' => 'Tipo',
    'LBL_CAMPAIGN' => 'Campanha:',
    'LBL_NEXT_STEP' => 'Próximo passo:',
    'LBL_LEAD_SOURCE' => 'Origem do potencial',
    'LBL_SALES_STAGE' => 'Fase de vendas',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Probabilidade (%)',
    'LBL_DESCRIPTION' => 'Descrição',
    'LBL_DUPLICATE' => 'Possível Oportunidade Duplicada',
    'MSG_DUPLICATE' => 'O registro de Oportunidade prestes a ser criado pode ser duplicado de um registro de Oportunidade que já existe. Os registros de Oportunidade que contêm nomes semelhantes estão listados abaixo.<br>Clique em Salvar para continuar com a criação desta Oportunidade ou clique em Cancelar para voltar ao módulo sem criar a Oportunidade.',
    'LBL_NEW_FORM_TITLE' => 'Criar oportunidade',
    'LNK_NEW_OPPORTUNITY' => 'Criar oportunidade',
    'LNK_CREATE' => 'Criar Negociação',
    'LNK_OPPORTUNITY_LIST' => 'Ver Oportunidades',
    'ERR_DELETE_RECORD' => 'Um número de Registro deve ser especificado para excluir a Oportunidade.',
    'LBL_TOP_OPPORTUNITIES' => 'Minhas Melhores Oportunidades',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Tem certeza de que deseja excluir o contato desta Oportunidade?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Tem certeza de que deseja remover essa Oportunidade deste projeto?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Oportunidades',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Atividades',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Histórico',
    'LBL_RAW_AMOUNT' => 'Valor Bruto',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Potenciais',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contatos',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documentos',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projetos',
    'LBL_ASSIGNED_TO_NAME' => 'Atribuído a',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuário Designado',
    'LBL_LIST_SALES_STAGE' => 'Fase de vendas',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Minhas Oportunidades Fechadas',
    'LBL_TOTAL_OPPORTUNITIES' => 'Oportunidades Totais',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Oportunidades Fechadas Ganhas',
    'LBL_ASSIGNED_TO_ID' => 'Usuário atribuído:',
    'LBL_CREATED_ID' => 'Criado por ID',
    'LBL_MODIFIED_ID' => 'Modificado por ID',
    'LBL_MODIFIED_NAME' => 'Modificado pelo Nome do Usuário',
    'LBL_CREATED_USER' => 'Usuário Criado',
    'LBL_MODIFIED_USER' => 'Usuário Modificado',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Oportunidade de Campanha',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projetos',
    'LABEL_PANEL_ASSIGNMENT' => 'Atribuição',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importar Oportunidades',
    'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID Campanha',
    'LBL_OPPORTUNITY_TYPE' => 'Tipo de oportunidade',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nome de usuário atribuído',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID de usuário atribuído',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificado Por ID',
    'LBL_EXPORT_CREATED_BY' => 'Criado pelo ID',
    'LBL_EXPORT_NAME' => 'Nome',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'E-mails de contatos relacionados',
    'LBL_FILENAME' => 'Anexo',
    'LBL_PRIMARY_QUOTE_ID' => 'Cotação Principal',
    'LBL_CONTRACTS' => 'Contratos',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Contratos',
    'LBL_PRODUCTS' => 'Itens de linha de cotação',
    'LBL_RLI' => 'Itens da linha de receita',
    'LNK_OPPORTUNITY_REPORTS' => 'Ver Relatórios de Oportunidades',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Cotações',
    'LBL_TEAM_ID' => 'ID da Equipe',
    'LBL_TIMEPERIODS' => 'Períodos',
    'LBL_TIMEPERIOD_ID' => 'ID do Período de Tempo',
    'LBL_COMMITTED' => 'Comprometido',
    'LBL_FORECAST' => 'Incluir na Previsão',
    'LBL_COMMIT_STAGE' => 'Fase de comprometimento',
    'LBL_COMMIT_STAGE_FORECAST' => 'Previsão',
    'LBL_WORKSHEET' => 'Planilha',

    'TPL_RLI_CREATE' => 'Uma Oportunidade deve ter um Item da linha de receita associada.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Criar Item da linha de receita.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Itens de linha de cotação',
    'LBL_RLI_SUBPANEL_TITLE' => 'Itens da linha de receita',

    'LBL_TOTAL_RLIS' => 'Nº total de Itens da linha de receita',
    'LBL_CLOSED_RLIS' => 'Nº de itens da linha de receita fechados',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Você não pode excluir Oportunidades que contêm Itens da linha de receitas fechados',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Um ou mais dos registros selecionados contém itens da linha de receita e não podem ser excluídos.',
    'LBL_INCLUDED_RLIS' => 'Nº de itens da linha de receita incluídos',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Cotações',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Hierarquia da Oportunidade',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Configure o campo Data de Fechamento Esperada nos registros de Oportunidade resultantes para que sejam as primeiras ou últimas datas de fechamento dos Itens da linha de receita existentes',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'O total de pipeline é ',

    'LBL_OPPORTUNITY_ROLE'=>'Função de Oportunidade',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Anotaçãoes:',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Ao clicar em Confirmar, você estará apagando TODOS os dados de Previsões e alterando sua Visualização de Oportunidades. Se isso não é o que você quer fazer, clique em Cancelar para retornar às configurações anteriores.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'A clicar em confirmar, você vai apagar TODOS os dados de previsões e alterar a exibição de oportunidades. '
        .'Além disso, TODAS as definições de processo com um módulo de alvo dos itens de linha de receita serão desabilitadas. '
        .'Se isso não é o que pretendia, clique em cancelar para retornar às configurações anteriores.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Se todos os Itens da linha de receita estiverem fechados e pelo menos um tiver ganhado,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'a Fase de vendas da Oportunidade é configurado como "Fechadas Ganhas".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Se todos os Itens da linha de receita estiverem na Fase de vendas "Fechadas Perdidas",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'a Fase de vendas da Oportunidade é configurada como "Fechadas Perdidas".',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Se algum Item da linha de receita ainda estiver aberto,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'a Oportunidade será marcada com a Fase de Vendas menos avançada.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Depois de iniciar esta alteração, as notas de resumo do Item da linha de receita serão criadas em segundo plano. Quando as notas estiverem concluídas e disponíveis, uma notificação será enviada para o endereço de e-mail em seu perfil de usuário. Se sua instância estiver configurada para {{forecasts_module}}, o Sugar também enviará uma notificação quando seus registros de {{module_name}} forem sincronizados com o módulo {{forecasts_module}} e estiverem disponíveis para o novo {{forecasts_module}}. Observe que sua instância deve ser configurada pra enviar um e-mail por meio do Administrador > Configurações de E-mail para que a notificação seja enviada.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Depois de iniciar esta alteração, os registros do Item da linha de receita serão criados para cada {{module_name}} existente em segundo plano. Quando os Itens de Linha de Receita estiverem concluídos e disponíveis, uma notificação será enviado ao endereço de e-mail em seu perfil de usuário. Observe que sua instância deve ser configurada pra enviar um e-mail por meio do Administrador > Configurações de E-mail para que a notificação seja enviada.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'O módulo {{plural_module_name}} permite acompanhar vendas individuais, do início ao fim. Cada registo {{module_name}} representa uma venda potencial e inclui dados de vendas relevantes, além de estar relacionado com outros registos importantes, como {{quotes_module}}, {{contacts_module}}, etc. Um {{module_name}} vai normalmente progredir através de vários Estágios de Vendas até ficar marcado como "Ganho" ou "Perdido". O módulo {{plural_module_name}} pode ser ainda mais aproveitado usando o módulo {{forecasts_singular_module}} do Sugar, para entender e prever as tendências de vendas, bem como focar o trabalho para alcançar as quotas de vendas.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'O módulo {{plural_module_name}} permite acompanhar vendas individuais e os itens de linha que pertencem a essas vendas, do início ao fim. Cada registo do módulo {{module_name}} representa uma venda potencial e inclui dados de vendas relevantes, além de estar relacionado com outros registos importantes, como {{quotes_module}}, {{contacts_module}}, etc.

- Edite os campos deste registo clicando em um campo individual ou no botão Editar.
- Visualize ou modifique ligações para outros registos nos subpainéis, alternando o painel inferior esquerdo para "Visualização de Dados".
- Crie e visualize comentários de utilizador e o histórico de alterações de registos em {{activitystream_singular_module}}, alternando o painel esquerdo inferior para "Fluxo de Atividades".
- Siga ou torne favorito este registo utilizando os ícones à direita do nome do registo.
- Ações adicionais estão disponíveis no menu pendente Ações à direita do botão Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'O módulo {{plural_module_name}} permite acompanhar vendas individuais e os itens de linha que pertencem a essas vendas, do início ao fim. Cada registo do módulo {{module_name}} representa uma venda potencial e inclui dados de vendas relevantes, além de estar relacionado com outros registos importantes, como {{quotes_module}}, {{contacts_module}}, etc.

Para criar um {{module_name}}:
1. Forneça valores para os campos conforme pretendido.
 - Os campos marcados como "Obrigatório" deverão ser preenchidos antes de gravar.
 - Clique em "Mostrar Mais" para mostrar campos adicionais se necessário.
2. Clique em "Gravar" para finalizar o novo registo e voltar à página anterior.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sincronizar com Marketo®',
    'LBL_MKTO_ID' => 'ID do Potencial de Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'As Dez Principais Oportunidades de Vendas',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Exibe as Dez Principais Oportunidades em um gráfico de bolhas.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Minhas Oportunidades',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Oportunidades da minha Equipe",
);
