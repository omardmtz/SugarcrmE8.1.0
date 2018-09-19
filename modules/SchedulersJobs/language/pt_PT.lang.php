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
    'LBL_MODULE_NAME' => 'Fila de trabalhos',
    'LBL_MODULE_NAME_SINGULAR' => 'Fila de trabalhos',
    'LBL_MODULE_TITLE' => 'Fila de trabalhos: Início',
    'LBL_MODULE_ID' => 'Fila de trabalhos',
    'LBL_TARGET_ACTION' => 'Ação',
    'LBL_FALLIBLE' => 'Falível',
    'LBL_RERUN' => 'Voltar a executar',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'Ver fila de trabalhos',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuração',
    'LBL_CONFIG_PAGE' => 'Definições de fila de trabalhos',
    'LBL_JOB_CANCEL_BUTTON' => 'Cancelar',
    'LBL_JOB_PAUSE_BUTTON' => 'Pausa',
    'LBL_JOB_RESUME_BUTTON' => 'Retomar',
    'LBL_JOB_RERUN_BUTTON' => 'Voltar a colocar na fila',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_LIST_ASSIGNED_USER' => 'Solicitado por',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_RESOLUTION' => 'Resolução',
    'LBL_NAME' => 'Nome da Tarefa',
    'LBL_EXECUTE_TIME' => 'Tempo de Execução',
    'LBL_SCHEDULER_ID' => 'Calendarizador',
    'LBL_STATUS' => 'Estado da Tarefa',
    'LBL_RESOLUTION' => 'Resultado',
    'LBL_MESSAGE' => 'Mensagens',
    'LBL_DATA' => 'Dados da Tarefa',
    'LBL_REQUEUE' => 'Repetir em caso de falha',
    'LBL_RETRY_COUNT' => 'Número máximo de tentativas de repetição',
    'LBL_FAIL_COUNT' => 'Falhas',
    'LBL_INTERVAL' => 'Intervalo de tempo entre tentativas',
    'LBL_CLIENT' => 'Cliente Proprietário',
    'LBL_PERCENT' => 'Percentagem concluído',
    'LBL_JOB_GROUP' => 'Grupo de Trabalho',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolução em fila de espera',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolução parcial',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolução concluída',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Erro na resolução',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Resolução cancelada',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Resoluçãoem execução',
    // Errors
    'ERR_CALL' => "Não foi possível chamar a função: %s",
    'ERR_CURL' => "Não existe o CURL - não é possível correr tarefas URL",
    'ERR_FAILED' => "Falha inesperada, por favor verificar os registos do PHP e o sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s em %s na linha %d",
    'ERR_NOUSER' => "Não foi fornecido um ID de Utilizador para esta tarefa",
    'ERR_NOSUCHUSER' => "ID de Utilizador %s não encontrado",
    'ERR_JOBTYPE' => "Tipo de tarefa desconhecido: %s",
    'ERR_TIMEOUT' => "Falha forçada em timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'Tarefa %1$s (%2$s) falhou ao correr o CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Não é possível carregar bean dom o ID: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Não é possível encontrar processador para a rota %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'A extensão desta fila não está instalada',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Alguns campos estão vazios',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Definições de Fila de Trabalhos',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuração principal',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuração de Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuração de AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuração de Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ajuda de configuração de fila de trabalhos',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Secção de configuração principal.</b></p>
<ul>
    <li>Processador (Runner):
    <ul>
    <li><i>Standard</i> - utiliza apenas um processo para funcionários.</li>
    <li><i>Paralelo</i> - utiliza alguns processos para funcionários.</li>
    </ul>
    </li>
    <li>Adaptador:
    <ul>
    <li><i>Fila padrão</i> - Utiliza apenas a Base de Dados de Sugar sem qualquer fila de mensagens.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service é um serviço de mensagens distribuídas por fila, introduzido pela Amazon.com.
   Suporta o envio programático de mensagens através de aplicações de serviços Web, como forma de comunicar pela Internet.</li>
    <li><i>RabbitMQ</i> -é um software mediador de mensagens em open source (por vezes designado middleware orientado por mensagens) que implementa o Advanced Message Queuing Protocol (AMQP).</li>
    <li><i>Gearman</i> - é uma arquitetura de aplicações em open source concebida para distribuir tarefas de computador adequadas por vários computadores, de modo a que tarefas de grande dimensão possam ser concluídas mais rapidamente.</li>
    <li><i>Imediato</i> - Como a fila padrão, mas executa a tarefa de imediato após ser adicionada.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Ajuda de configuração de Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Secção de configuração de Amazon SQS.</b></p>
<ul>
    <li>ID de chave de acesso: <i>Insira o seu número de ID chave da chave de acesso de Amazon SQS</i></li>
    <li>Chave de acesso de segredo: <i>Insira a sua chave de acesso de segredo de Amazon SQS</i></li>
    <li>Região: <i>Insira a região do servidor de Amazon SQS</i></li>
    <li>Nome da fila: <i>Insira o nome da fila do servidor de Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Ajuda de configuração de AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Secção de configuração de AMQP.</b></p>
<ul>
    <li>URL de servidor: <i>Insira o URL do servidor de fila de mensagens.</i></li>
    <li>Início de sessão: <i>Insira os seus dados de início de sessão em RabbitMQ</i></li>
    <li>Palavra-passe: <i>Insira a sua palavra-passe de RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ajuda de configuração de Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Secção de configuração de Gearman.</b></p>
<ul>
    <li>URL de servidor: <i>Insira o URL do servidor de fila de mensagens.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptador',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Processador',
    'LBL_SERVER_URL' => 'URL do servidor',
    'LBL_LOGIN' => 'Início de sessão',
    'LBL_ACCESS_KEY' => 'ID de chave de acesso',
    'LBL_REGION' => 'Região',
    'LBL_ACCESS_KEY_SECRET' => 'Chave de acesso de segredo',
    'LBL_QUEUE_NAME' => 'Nome de adaptador',
);
