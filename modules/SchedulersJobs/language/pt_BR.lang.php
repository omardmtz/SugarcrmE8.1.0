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
    'LBL_MODULE_TITLE' => 'Fila de trabalho: Início',
    'LBL_MODULE_ID' => 'Fila de trabalhos',
    'LBL_TARGET_ACTION' => 'Ação',
    'LBL_FALLIBLE' => 'Falível',
    'LBL_RERUN' => 'Executar novamente',
    'LBL_INTERFACE' => 'Interface',
    'LINK_SCHEDULERSJOBS_LIST' => 'Exibir fila de trabalho',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuração',
    'LBL_CONFIG_PAGE' => 'Configurações de fila de trabalho',
    'LBL_JOB_CANCEL_BUTTON' => 'Cancelar',
    'LBL_JOB_PAUSE_BUTTON' => 'Pausa',
    'LBL_JOB_RESUME_BUTTON' => 'Retomar',
    'LBL_JOB_RERUN_BUTTON' => 'Colocar novamente na fila',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_LIST_ASSIGNED_USER' => 'Solicitado por',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_RESOLUTION' => 'Resolução',
    'LBL_NAME' => 'Nome do trabalho',
    'LBL_EXECUTE_TIME' => 'Tempo de execução',
    'LBL_SCHEDULER_ID' => 'Agendador',
    'LBL_STATUS' => 'Status do trabalho',
    'LBL_RESOLUTION' => 'Resultado',
    'LBL_MESSAGE' => 'Mensagens',
    'LBL_DATA' => 'Data do trabalho',
    'LBL_REQUEUE' => 'Repetir em caso de falha',
    'LBL_RETRY_COUNT' => 'Máximo de tentativas',
    'LBL_FAIL_COUNT' => 'Falhas',
    'LBL_INTERVAL' => 'Intervalo mínimo entre tentativas',
    'LBL_CLIENT' => 'Cliente proprietário',
    'LBL_PERCENT' => 'Percentual concluído',
    'LBL_JOB_GROUP' => 'Grupo de trabalho',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolução na fila',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolução parcial',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolução completa',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Falha na resolução',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Resolução cancelada',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Resolução em execução',
    // Errors
    'ERR_CALL' => "Impossibilitado de chamar função %",
    'ERR_CURL' => "No CURL - não é possível executar tarefas de URL",
    'ERR_FAILED' => "Falha inexperada, verifique logs PHP e sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s em %s on line %d",
    'ERR_NOUSER' => "Nenhum ID de usuário especificado para o trabalho",
    'ERR_NOSUCHUSER' => "ID de usuário não localizado",
    'ERR_JOBTYPE' => "Tipo de trabalho desconhecido: %s",
    'ERR_TIMEOUT' => "Falha forçada no timeout",
    'ERR_JOB_FAILED_VERBOSE' => 'O trabalho %1$s (%2$s) falhou na execução do CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Não é possível carregar o bean com Id: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'Não foi possível encontrar o manipulador para a rota %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'A extensão para essa fila não está instalada',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Alguns campos estão vazios',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Configurações de fila de trabalho',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuração principal',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuração de Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuração de AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuração de Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ajuda das configurações da fila de trabalho',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Seção de configurações principais.</b></p>
<ul>
    <li>Executor:
    <ul>
    <li><i>Padrão</i> - usar apenas um processo para os trabalhadores.</li>
    <li><i>Paralelo</i> - usar alguns processos para os trabalhadores.</li>
    </ul>
    </li>
    <li>Adaptador:
    <ul>
    <li><i>Fila padrão</i> - Esta usará apenas o banco de dados do Sugar, sem qualquer fila de mensagens.</li>
    <li><i>Amazon SQS</i> - O Serviço de fila simples Amazon é um serviço de mensagens
    de fila distribuída desenvolvido pela Amazon.com.
    Ele oferece suporte ao envio programático de mensagens por aplicativos web como uma forma de
    comunicação pela Internet.</li>
    <li><i>RabbitMQ</i> - é um software de mensagens de código aberto (também chamado de middleware orientado a mensagens)
    que implementa o Protocolo avançado para fila de mensagens (AMQP).</li>
    <li><i>Gearman</i> - é um framework de aplicativos de código aberto projetado para distribuir tarefas computacionais
    para diversos computadores, de forma que tarefas mais extensas possam ser realizadas de forma mais rápida.</li>
    <li><i>Imediato</i> - Igual à fila padrão, mas executa a tarefa imediatamente após sua adição.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Ajuda de configuração do Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Seção de configuração do Amazon SQS.</b></p>
<ul>
    <li>ID da chave de acesso: <i>Insira o número de id da chave de acesso do Amazon SQS</i></li>
    <li>Chave de acesso secreta: <i>Insira sua chave de acesso secreta do Amazon SQS</i></li>
    <li>Região: <i>Insira a região do servidor Amazon SQS </i></li>
    <li>Nome da fila: <i>Insira o nome da fila do servidor Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Ajuda de configuração do AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Seção de configuração do AMQP.</b></p>
<ul>
    <li>URL do servidor: <i>Insira a URL do servidor da fila de mensagens.</i></li>
    <li>Log-in: <i>Insira seu log-in do RabbitMQ</i></li>
    <li>Senha: <i>Insira sua senha do RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ajuda de configuração do Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Seção de configuração do Gearman.</b></p>
<ul>
    <li>URL do servidor: <i>Insira a URL do servidor da fila de mensagens.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptador',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Executor',
    'LBL_SERVER_URL' => 'URL do servidor',
    'LBL_LOGIN' => 'Log-in',
    'LBL_ACCESS_KEY' => 'ID da chave de acesso',
    'LBL_REGION' => 'Região',
    'LBL_ACCESS_KEY_SECRET' => 'Chave de acesso secreta',
    'LBL_QUEUE_NAME' => 'Nome do adaptador',
);
