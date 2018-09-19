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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'Processar Tarefas de fluxo de trabalho',
'LBL_OOTB_REPORTS'		=> 'Executar tarefas criadas de execução de relatórios',
'LBL_OOTB_IE'			=> 'Verificar Caixa de entrada de e-mails',
'LBL_OOTB_BOUNCE'		=> 'Executar e-mails de retorno de campanha de processo noturno',
'LBL_OOTB_CAMPAIGN'		=> 'Executar envio noturno de campanhas de massa por e-mail',
'LBL_OOTB_PRUNE'		=> 'Remover Apagados da Base de Dados no primeiro dia do Mês',
'LBL_OOTB_TRACKER'		=> 'Remover apagados das tabelas de trackers',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Lista de Registros antigos de Prune',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Remover arquivos temporários',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Remover arquivos da ferramenta de diagnóstico',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Remover arquivos PDF temporários',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Atualizar tabela tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Executar as notificações do lembrete por e-mail',
'LBL_OOTB_CLEANUP_QUEUE' => 'Limpar trabalhos na fila',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Criar Períodos futuros',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat (Movimentação)',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Atualizar artigos KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publicar artigos aprovados e expirar artigos KB.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Tarefas agendadas do Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Reconstruir os dados de segurança não normalizados da equipe',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervalo:',
'LBL_LIST_LIST_ORDER' => 'Agendadores:',
'LBL_LIST_NAME' => 'Agendador:',
'LBL_LIST_RANGE' => 'Intervalo:',
'LBL_LIST_REMOVE' => 'Remover:',
'LBL_LIST_STATUS' => 'Estado:',
'LBL_LIST_TITLE' => 'Lista de Tarefas agendadas:',
'LBL_LIST_EXECUTE_TIME' => 'Será executada em:',
// human readable:
'LBL_SUN'		=> 'Domingo',
'LBL_MON'		=> 'Segunda',
'LBL_TUE'		=> 'Terça',
'LBL_WED'		=> 'Quarta',
'LBL_THU'		=> 'Quinta',
'LBL_FRI'		=> 'Sexta',
'LBL_SAT'		=> 'Sábado',
'LBL_ALL'		=> 'Todos os Dias',
'LBL_EVERY_DAY'	=> 'Todos os dias',
'LBL_AT_THE'	=> 'às',
'LBL_EVERY'		=> 'Todo(a)',
'LBL_FROM'		=> 'De',
'LBL_ON_THE'	=> 'No(a)',
'LBL_RANGE'		=> 'a',
'LBL_AT' 		=> 'às',
'LBL_IN'		=> 'em',
'LBL_AND'		=> 'e',
'LBL_MINUTES'	=> 'minutos',
'LBL_HOUR'		=> 'horas',
'LBL_HOUR_SING'	=> 'hora',
'LBL_MONTH'		=> 'mês',
'LBL_OFTEN'		=> 'Tão frequente quanto possível.',
'LBL_MIN_MARK'	=> 'marca de minuto',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'hrs',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'meses',
'LBL_DAY_OF_WEEK' => 'dia',
'LBL_CRONTAB_EXAMPLES' => 'A lista acima usa notações crontab padrão.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'As especificações de tempo têm como base o fuso horário do servidor (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Especifique a hora da execução do agendador de acordo.',
// Labels
'LBL_ALWAYS' => 'Sempre',
'LBL_CATCH_UP' => 'Executar Se Falhar',
'LBL_CATCH_UP_WARNING' => 'Desmarque se esta Tarefa levar mais do que um momento para se executada.',
'LBL_DATE_TIME_END' => 'Data e hora de fim',
'LBL_DATE_TIME_START' => 'Data e hora de início',
'LBL_INTERVAL' => 'Intervalo',
'LBL_JOB' => 'Tarefa',
'LBL_JOB_URL' => 'URL do trabalho',
'LBL_LAST_RUN' => 'Última execução com sucesso',
'LBL_MODULE_NAME' => 'Agendador Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Agendador Sugar',
'LBL_MODULE_TITLE' => 'Agendadores',
'LBL_NAME' => 'Nome do trabalho',
'LBL_NEVER' => 'Nunca',
'LBL_NEW_FORM_TITLE' => 'Nova Tarefa agendada',
'LBL_PERENNIAL' => 'perpétua',
'LBL_SEARCH_FORM_TITLE' => 'Pesquisa de agendador',
'LBL_SCHEDULER' => 'Agendador:',
'LBL_STATUS' => 'Estado',
'LBL_TIME_FROM' => 'Ativo desde',
'LBL_TIME_TO' => 'Ativo até',
'LBL_WARN_CURL_TITLE' => 'Aviso cURL:',
'LBL_WARN_CURL' => 'Aviso:',
'LBL_WARN_NO_CURL' => 'Este sistema não possui as bibliotecas cURL habilitadas/compiladas no módulo PHP (--with-curl=/path/to/curl_library). Entre em contato com seu administrador de sistemas para resolver esta questão. Sem a funcionalidade cURL, o agendador não pode executar as tarefas.',
'LBL_BASIC_OPTIONS' => 'Configuração básica',
'LBL_ADV_OPTIONS'		=> 'Opções avançadas',
'LBL_TOGGLE_ADV' => 'Mostrar opções avançadas',
'LBL_TOGGLE_BASIC' => 'Mostrar opções básicas',
// Links
'LNK_LIST_SCHEDULER' => 'Agendadores',
'LNK_NEW_SCHEDULER' => 'Criar agendador',
'LNK_LIST_SCHEDULED' => 'Tarefas agendadas',
// Messages
'SOCK_GREETING' => "
Esta é a interface para o Serviço de agendadores do SugarCRM. 
[ Comandos daemon disponíveis:
start|restart|shutdown|status ]
Para sair, digite 'quit'. Para desligar o serviço, 'shutdown'.
",
'ERR_DELETE_RECORD' => 'Um número de registro deve ser especificado para excluir o agendamento.',
'ERR_CRON_SYNTAX' => 'Sintaxe Cron inválida',
'NTC_DELETE_CONFIRMATION' => 'Tem certeza de que deseja excluir este registro?',
'NTC_STATUS' => 'Configure o status como Inativo para excluir este agendamento das listas suspensas do agendador',
'NTC_LIST_ORDER' => 'Configure a ordem pela qual este agendamento aparecerá nas listas suspensas do agendador',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Para configurar o agendador do Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Para configurar o Crontab',
'LBL_CRON_LINUX_DESC' => 'Nota: Para executar o agendador do Sugar, adicione esta linha ao arquivo crontab: ',
'LBL_CRON_WINDOWS_DESC' => 'Nota: Para executar o agendador do Sugar, crie um arquivo em lote para ser executado usando as Tarefas agendadas do Windows. O arquivo em lote deve incluir os seguintes comandos: ',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Registro de trabalho',
'LBL_EXECUTE_TIME'			=> 'Tempo de execução',

//jobstrings
'LBL_REFRESHJOBS' => 'Atualizar trabalhos',
'LBL_POLLMONITOREDINBOXES' => 'Verificar contas de entrada de e-mail',
'LBL_PERFORMFULLFTSINDEX' => 'Sistema de índice de pesquisa de texto completo',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Remover arquivos PDF temporários',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publicar artigos aprovados e expirar artigos KB.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Agendador de fila Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Remover arquivos da ferramenta de diagnóstico',
'LBL_SUGARJOBREMOVETMPFILES' => 'Remover arquivos temporários',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Reconstruir os dados de segurança não normalizados da equipe',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Executar envio noturno de campanhas de massa por e-mail',
'LBL_ASYNCMASSUPDATE' => 'Realizar atualizações em massa assíncronos',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Executar e-mails de retorno de campanha de processo noturno',
'LBL_PRUNEDATABASE' => 'Remover Apagados da Base de Dados no primeiro dia do Mês',
'LBL_TRIMTRACKER' => 'Remover Apagados das Tabelas de Trackers',
'LBL_PROCESSWORKFLOW' => 'Processar Tarefas de fluxo de trabalho',
'LBL_PROCESSQUEUE' => 'Executar tarefas criadas de execução de relatórios',
'LBL_UPDATETRACKERSESSIONS' => 'Atualizar tabelas de sessões de rastreadores',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Criar Períodos futuros',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat (Movimentação)',
'LBL_SENDEMAILREMINDERS'=> 'Executar envio de lembretes por e-mail',
'LBL_CLEANJOBQUEUE' => 'Limpar fila de trabalho',
'LBL_CLEANOLDRECORDLISTS' => 'Limpar listas antigas de registros',
'LBL_PMSEENGINECRON' => 'Agendador do Advanced Workflow',
);

