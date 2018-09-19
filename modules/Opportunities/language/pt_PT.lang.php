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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Dashboard de Lista de Oportunidades',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Dashboard de Registo de Oportunidades',

    'LBL_MODULE_NAME' => 'Oportunidades',
    'LBL_MODULE_NAME_SINGULAR' => 'Oportunidade',
    'LBL_MODULE_TITLE' => 'Oportunidades: Ecrã Principal',
    'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Oportunidades',
    'LBL_VIEW_FORM_TITLE' => 'Visualizar Oportunidades',
    'LBL_LIST_FORM_TITLE' => 'Lista de Oportunidades',
    'LBL_OPPORTUNITY_NAME' => 'Nome da Oportunidade',
    'LBL_OPPORTUNITY' => 'Oportunidade:',
    'LBL_NAME' => 'Nome da Oportunidade',
    'LBL_INVITEE' => 'Contactos',
    'LBL_CURRENCIES' => 'Moedas',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Oportunidade',
    'LBL_LIST_ACCOUNT_NAME' => 'Nome da Entidade',
    'LBL_LIST_DATE_CLOSED' => 'Data Prevista',
    'LBL_LIST_AMOUNT' => 'Provável',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Valor Total',
    'LBL_ACCOUNT_ID' => 'ID da Entidade',
    'LBL_CURRENCY_RATE' => 'Taxa da Moeda',
    'LBL_CURRENCY_ID' => 'ID da Moeda',
    'LBL_CURRENCY_NAME' => 'Nome da Moeda',
    'LBL_CURRENCY_SYMBOL' => 'Símbolo da Moeda',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Oportunidade - Actualizar Moeda',
    'UPDATE_DOLLARAMOUNTS' => 'Actualizar Valores em U.S. Dollar',
    'UPDATE_VERIFY' => 'Verificar Valores',
    'UPDATE_VERIFY_TXT' => 'Verifica se os valores nas oportunidades são válidos com apenas dados numéricos (0-9) e ponto decimal (.)',
    'UPDATE_FIX' => 'Corrigir Valores',
    'UPDATE_FIX_TXT' => 'Tenta corrigir qualquer valor inválido criando um valor com casas decimais a partir do valor atual. Será efetuado o backup de qualquer valor alterado na base de dados. Caso execute este procedimento e obtenha mensagens de erros, não execute novamente antes de restaurar o backup, caso contrário é possível que o backup seja substituído por novos dados inválidos.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Actualiza os valores em U.S. Dollar nas oportunidades baseado na taxa de cotação actual. Este valor será utilizado para calcular os Gráficos e Listas com Valores de Cotações.',
    'UPDATE_CREATE_CURRENCY' => 'A criar Nova Moeda',
    'UPDATE_VERIFY_FAIL' => 'Verificação de Registos Falhou',
    'UPDATE_VERIFY_CURAMOUNT' => 'Valor Actual',
    'UPDATE_VERIFY_FIX' => 'Executar Correcções pode trazer',
    'UPDATE_INCLUDE_CLOSE' => 'Incluir Registos Fechados',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Novo Valor:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nova Moeda',
    'UPDATE_DONE' => 'Completo',
    'UPDATE_BUG_COUNT' => 'Erros Encontrados e Tentativas de Resolução:',
    'UPDATE_BUGFOUND_COUNT' => 'Erros Encontrados:',
    'UPDATE_COUNT' => 'Registos Actualizados',
    'UPDATE_RESTORE_COUNT' => 'Valores de Registos Restaurados',
    'UPDATE_RESTORE' => 'Restaurar Valores',
    'UPDATE_RESTORE_TXT' => 'Restaurar valores a partir do backup criado durante a Resolução.',
    'UPDATE_FAIL' => 'Impossível actualizar -',
    'UPDATE_NULL_VALUE' => 'Valor é NULO definindo como 0 -',
    'UPDATE_MERGE' => 'Fundir Moedas',
    'UPDATE_MERGE_TXT' => 'Fundir múltiplas Moedas numa única Moeda. Se existirem múltiplos registos para a mesma Moeda, irá fundi-los num só. Isto irá fundir igualmente as Moedas para todos os outros módulos.',
    'LBL_ACCOUNT_NAME' => 'Nome da Conta:',
    'LBL_CURRENCY' => 'Moeda',
    'LBL_DATE_CLOSED' => 'Data Prevista:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Carimbo da Data de Fecho Expectável',
    'LBL_TYPE' => 'Tipo',
    'LBL_CAMPAIGN' => 'Campanha:',
    'LBL_NEXT_STEP' => 'Próximo Passo',
    'LBL_LEAD_SOURCE' => 'Origem do Cliente Potencial',
    'LBL_SALES_STAGE' => 'Fase da Venda',
    'LBL_SALES_STATUS' => 'Estado',
    'LBL_PROBABILITY' => 'Probabilidade (%)',
    'LBL_DESCRIPTION' => 'Descrição',
    'LBL_DUPLICATE' => 'Possível Oportunidade Duplicada',
    'MSG_DUPLICATE' => 'O registo de Oportunidade que está prestes a criar pode ser um duplicado de um registo de Oportunidade que já existe. Os registos de Oportunidade que contêm nomes semelhantes estão listados abaixo.<br>Clique em Gravar para continuar a criar esta nova Oportunidade ou clique em Cancelar para regressar ao módulo sem criar a Oportunidade.',
    'LBL_NEW_FORM_TITLE' => 'Nova Oportunidade',
    'LNK_NEW_OPPORTUNITY' => 'Nova Oportunidade',
    'LNK_CREATE' => 'Criar Acordo',
    'LNK_OPPORTUNITY_LIST' => 'Ver Oportunidades',
    'ERR_DELETE_RECORD' => 'Um número de registo deve ser especificado para eliminar a oportunidade.',
    'LBL_TOP_OPPORTUNITIES' => 'As Minhas Melhores Oportunidades',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Tem certeza de que pretende eliminar este contacto desta oportunidade?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Tem certeza que deseja remover essa oportunidade deste projecto?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Oportunidades',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Atividades',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Histórico',
    'LBL_RAW_AMOUNT' => 'Valor Bruto',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Clientes Potenciais',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactos',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documentos',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projetos',
    'LBL_ASSIGNED_TO_NAME' => 'Atribuído a',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Utilizador Atribuído',
    'LBL_LIST_SALES_STAGE' => 'Fase da Venda',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'As Minhas Oportunidades Fechadas',
    'LBL_TOTAL_OPPORTUNITIES' => 'Oportunidades Totais',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Oportunidades Ganhas',
    'LBL_ASSIGNED_TO_ID' => 'Utilizador Atribuído:',
    'LBL_CREATED_ID' => 'ID Criado Por',
    'LBL_MODIFIED_ID' => 'ID de Modificado por',
    'LBL_MODIFIED_NAME' => 'Modificado pelo Nome do Utilizador',
    'LBL_CREATED_USER' => 'Utilizador Criado',
    'LBL_MODIFIED_USER' => 'Utilizador Modificado',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Oportunidade da Campanha',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projetos',
    'LABEL_PANEL_ASSIGNMENT' => 'Atribuição',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importar Oportunidades',
    'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID da Campanha',
    'LBL_OPPORTUNITY_TYPE' => 'Tipo de Oportunidade',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nome do Utilizador Atribuído',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID de Utilizador Atribuído',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'ID de Modificado Por',
    'LBL_EXPORT_CREATED_BY' => 'ID do Utilizador que Criou',
    'LBL_EXPORT_NAME' => 'Nome',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Emails dos Contactos Relacionados',
    'LBL_FILENAME' => 'Anexo',
    'LBL_PRIMARY_QUOTE_ID' => 'Cotação Primária',
    'LBL_CONTRACTS' => 'Contratos',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Contratos',
    'LBL_PRODUCTS' => 'Produtos',
    'LBL_RLI' => 'Itens de Linha de Receita',
    'LNK_OPPORTUNITY_REPORTS' => 'Relatório de Oportunidades',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Cotações',
    'LBL_TEAM_ID' => 'ID da Equipa',
    'LBL_TIMEPERIODS' => 'Período de tempo',
    'LBL_TIMEPERIOD_ID' => 'ID de Período de Tempo',
    'LBL_COMMITTED' => 'Inserida',
    'LBL_FORECAST' => 'Incluir na Previsão',
    'LBL_COMMIT_STAGE' => 'Etapa de Commit',
    'LBL_COMMIT_STAGE_FORECAST' => 'Previsão',
    'LBL_WORKSHEET' => 'Folha de Cálculo',

    'TPL_RLI_CREATE' => 'Uma Oportunidade deverá estar associada a um Item de Linha de Receita.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Criar um Item de Linha de Receita.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Itens de Linha Cotados',
    'LBL_RLI_SUBPANEL_TITLE' => 'Itens de Linha de Receita',

    'LBL_TOTAL_RLIS' => '# do Total de Itens de Linha de Receita',
    'LBL_CLOSED_RLIS' => '# de Itens de Linha de Receita Fechados',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Não pode apagar Oportunidades que contenham Itens de Linha de Receita fechados',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Um ou mais dos registos selecionados contêm Itens de Linha de Receita fechados e não podem ser eliminados.',
    'LBL_INCLUDED_RLIS' => 'N. º de Itens de Linha de Receita Incluídos',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Cotações',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Hierarquia da Oportunidade',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Definir o campo Data Expectavel de Fecho nos registos de Oportunidades resultantes de forma a que sejam os mais recentes nas linhas de Lucro.',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'O Total do Pipeline é ',

    'LBL_OPPORTUNITY_ROLE'=>'Função de Oportunidade',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notas',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Ao clicar em Confirmar, irá eliminar TODOS os dados da Previsão e irá alterar a Visualização das Oportunidades. Se não é isto que pretende, clique em cancelar para voltar às definições anteriores.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Ao clicar em Confirmar, vai eliminar TODOS os dados de Previsões e alterar a Visualização de Oportunidades. '
        .'Além disso, TODAS as definições de Processo com um módulo de alvo dos Itens de Linha de Receita serão desativadas. '
        .'Se isto não é o que pretendia, clique em cancelar para regressar às definições anteriores.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Se todos os Itens de Linha de Receita estão fechados e pelo menos um foi ganho,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'a fase de venda da oportunidade é definida como "Ganha".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Se todos os Itens de Linha de Receita estiverem na Fase de Venda "Perdida",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'a Fase de Venda da Oportunidade é definida como "Perdida".',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Se algum item de linha Receita ainda estão abertas,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'a Oportunidade será marcada com a Fase de vendas menos avançada.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Ao iniciar esta alteração, as notas de somatório das linhas de lucro serão construídas em segundo plano. Quando as notas estiverem completadas e disponíveis, será enviada uma notificação para o endereço de e-mail do perfil de utilizador. Se a sua instância estiver configurada para o módulo {{forecasts_module}} o Sugar irá também enviar uma notificação quando o registo {{module_name}} estiver sincronizado com o módulo {{forecasts_module}} e disponível para um novo {{forecasts_module}}. Tome em atenção de que a sua instancia deverá ser configurada para poder enviar e-mail em Admin > Definições de E-mail.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Ao iniciar esta alteração, as notas das linhas de lucro serão criadas para cada {{module_name}} existente em segundo plano. Quando as notas estiverem completadas e disponíveis, será enviada uma notificação para o endereço de e-mail do perfil de utilizador. Tome em atenção de que a sua instancia deverá ser configurada para poder enviar email em Admin -> Definições de E-mail.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'O módulo {{plural_module_name}} permite-lhe acompanhar vendas individuais, do início ao fim. Cada registo {{module_name}} representa uma venda potencial e inclui dados de vendas relevantes, além de estar relacionado com outros registos importantes, como {{quotes_module}}, {{contacts_module}}, etc. Um {{module_name}} vai normalmente progredir através de vários Estágios de Vendas até ficar marcado como "Ganho" ou "Perdido". O módulo {{plural_module_name}} pode ser ainda mais aproveitado usando o módulo {{forecasts_singular_module}} do Sugar, para entender e prever as tendências de vendas, bem como focar o trabalho para alcançar as quotas de vendas.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'O módulo {{plural_module_name}} permite-lhe acompanhar vendas individuais e os itens de linha que pertencem a essas vendas, do início ao fim. Cada registo do módulo {{module_name}} representa uma venda potencial e inclui dados de vendas relevantes, além de estar relacionado com outros registos importantes, como {{quotes_module}}, {{contacts_module}}, etc.

- Edite os campos deste registo clicando num campo individual ou no botão Editar.
- Visualize ou modifique ligações para outros registos nos subpainéis, alternando o painel inferior esquerdo para "Visualização de Dados".
- Crie e visualize comentários de utilizador e o histórico de alterações de registos em {{activitystream_singular_module}}, alternando o painel esquerdo inferior para "Fluxo de Atividades".
- Siga ou torne favorito este registo utilizando os ícones à direita do nome do registo.
- Ações adicionais estão disponíveis no menu pendente Ações à direita do botão Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'O módulo {{plural_module_name}} permite-lhe acompanhar vendas individuais e os itens de linha que pertencem a essas vendas, do início ao fim. Cada registo do módulo {{module_name}} representa uma venda potencial e inclui dados de vendas relevantes, além de estar relacionado com outros registos importantes, como {{quotes_module}}, {{contacts_module}}, etc.

Para criar um {{module_name}}:
1. Forneça valores para os campos conforme pretendido.
 - Os campos marcados como "Obrigatório" deverão ser preenchidos antes de gravar.
 - Clique em "Mostrar Mais" para mostrar campos adicionais se necessário.
2. Clique em "Gravar" para finalizar o novo registo e regressar à página anterior.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sincronizar para o Marketo&reg;',
    'LBL_MKTO_ID' => 'ID do Cliente Potencial da Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Top 10 Oportunidades de Venda',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Mostrar as dez melhores Oportunidades num gráfico de bolhas.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'As Minhas Oportunidades',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "As Oportunidades da minha Equipa",
);
