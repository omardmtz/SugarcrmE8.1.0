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
'LBL_OOTB_WORKFLOW'		=> 'Processar Tarefas de Workflow',
'LBL_OOTB_REPORTS'		=> 'Executar tarefas criadas de execução de relatórios',
'LBL_OOTB_IE'			=> 'Verificar Caixa de Entrada de E-mails',
'LBL_OOTB_BOUNCE'		=> 'Executar toda a noite o Processamento de E-mails Retornados de Campanhas',
'LBL_OOTB_CAMPAIGN'		=> 'Executar toda a noite o Envio Massivo de E-mail de Campanha',
'LBL_OOTB_PRUNE'		=> 'Remover Base de Dados no Primeiro Dia do Mês',
'LBL_OOTB_TRACKER'		=> 'Remover Tabelas Tracker',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Desbastar Listas de Registos Antigos',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Remover ficheiros temporários',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Remover ficheiros da ferramenta de diagnósticos',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Remover ficheiros PDF temporários',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Atualizar tabela tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Executar as notificações do lembrete por E-mail',
'LBL_OOTB_CLEANUP_QUEUE' => 'Limpar a Fila de Trabalhos',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Criar Períodos de Tempo Futuros',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Atualizar artigos KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publicar artigos aprovados e Artigos KB expirados.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Tarefa calendarizada do Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Reconstruir a Equipa de Segurança de Dados não normalizados',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervalo:',
'LBL_LIST_LIST_ORDER' => 'Calendarizadores:',
'LBL_LIST_NAME' => 'Calendarizador:',
'LBL_LIST_RANGE' => 'Intervalo:',
'LBL_LIST_REMOVE' => 'Remover:',
'LBL_LIST_STATUS' => 'Estado:',
'LBL_LIST_TITLE' => 'Lista de Tarefas Calendarizadas:',
'LBL_LIST_EXECUTE_TIME' => 'Horário de Execução:',
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
'LBL_EVERY'		=> 'Todos(as) ',
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
'LBL_MINS' => 'mínimo',
'LBL_HOURS' => 'hrs',
'LBL_DAY_OF_MONTH' => 'data',
'LBL_MONTHS' => 'meses',
'LBL_DAY_OF_WEEK' => 'dia',
'LBL_CRONTAB_EXAMPLES' => 'A lista acima usa notações crontab padrão.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'As especificações de cron são executadas com base na zona horária (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Por favor especifique a execução do calendarizador tendo isto em conta.',
// Labels
'LBL_ALWAYS' => 'Sempre',
'LBL_CATCH_UP' => 'Executar Se Falhar',
'LBL_CATCH_UP_WARNING' => 'Desmarque se esta Tarefa levar mais do que um momento para se executada.',
'LBL_DATE_TIME_END' => 'Data e Hora de Fim',
'LBL_DATE_TIME_START' => 'Data e Hora de Início',
'LBL_INTERVAL' => 'Intervalo',
'LBL_JOB' => 'Tarefa',
'LBL_JOB_URL' => 'URL da Tarefa',
'LBL_LAST_RUN' => 'Última Execução com Sucesso',
'LBL_MODULE_NAME' => 'Calendarizador Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Calendarizador Sugar',
'LBL_MODULE_TITLE' => 'Calendarizadores',
'LBL_NAME' => 'Nome da Tarefa',
'LBL_NEVER' => 'Nunca',
'LBL_NEW_FORM_TITLE' => 'Nova Tarefa Calendarizada',
'LBL_PERENNIAL' => 'perpétua',
'LBL_SEARCH_FORM_TITLE' => 'Pesquisar Calendarizador',
'LBL_SCHEDULER' => 'Calendarizador:',
'LBL_STATUS' => 'Estado',
'LBL_TIME_FROM' => 'Ativo Desde',
'LBL_TIME_TO' => 'Ativo Até',
'LBL_WARN_CURL_TITLE' => 'Aviso cURL:',
'LBL_WARN_CURL' => 'Aviso:',
'LBL_WARN_NO_CURL' => 'Este sistema não possui as bibliotecas cURL habilitadas/compiladas no módulo PHP (--with-curl=/path/to/curl_library).  Por favor contacte o seu administrador de sistemas para resolver esta questão.  Sem a funcionalidade cURL, o Calendarizador não pode executar as suas tarefas.',
'LBL_BASIC_OPTIONS' => 'Configuração Básica',
'LBL_ADV_OPTIONS'		=> 'Opções Avançadas',
'LBL_TOGGLE_ADV' => 'Mostrar Opções Avançadas',
'LBL_TOGGLE_BASIC' => 'Mostrar Opções Básicas',
// Links
'LNK_LIST_SCHEDULER' => 'Calendarizador',
'LNK_NEW_SCHEDULER' => 'Nova Tarefa Calendarizada',
'LNK_LIST_SCHEDULED' => 'Tarefas Calendarizadas',
// Messages
'SOCK_GREETING' => "\nEsta é a interface para o Serviço de Calendarização do SugarCRM. \n[ Comandos daemon disponíveis: start|restart|shutdown|status ]\nPara sair, escreva 'quit'. Para encerrar o serviço 'shutdown'.\n",
'ERR_DELETE_RECORD' => 'Um número de registo deve ser especificado para eliminar a calendarização.',
'ERR_CRON_SYNTAX' => 'Sintaxe Cron inválida',
'NTC_DELETE_CONFIRMATION' => 'Tem a certeza de que pretende eliminar este registo?',
'NTC_STATUS' => 'Configure o estado como Inativo para excluir esta Calendarização das listas de valores possíveis do Calendarizador',
'NTC_LIST_ORDER' => 'Configure a ordem pela qual esta Calendarização aparecerá nas listas de valores possíveis do campo Calendarizador',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Para configurar o Calendarizador do Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Para configurar o Crontab',
'LBL_CRON_LINUX_DESC' => 'Nota: Para executar os Calendarizadores Sugar, adicione a linha seguinte ao ficheiro crontab: ',
'LBL_CRON_WINDOWS_DESC' => 'Nota: Para executar os calendarizadores Sugar, crie um ficheiro batch para executar com as Tarefas Agendadas do Windows. O ficheiro batch deve incluir os seguintes comandos: ',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Tarefas Activas',
'LBL_EXECUTE_TIME'			=> 'Tempo de Execução',

//jobstrings
'LBL_REFRESHJOBS' => 'Actualizar Tarefas',
'LBL_POLLMONITOREDINBOXES' => 'Verificar Contas de Entrada de E-mail',
'LBL_PERFORMFULLFTSINDEX' => 'Sistema de Indexação de Pesquisa de Texto Completo',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Remover ficheiros PDF temporários',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publicar artigos aprovados e Artigos KB expirados.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Calendarizador da fila do Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Remover ficheiros da ferramenta de diagnósticos',
'LBL_SUGARJOBREMOVETMPFILES' => 'Remover ficheiros temporários',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Reconstruir a Equipa de Segurança de Dados não normalizados',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Executar toda a noite campanhas de e-mail massivo',
'LBL_ASYNCMASSUPDATE' => 'Realizar actualizações em massa de forma assíncrona',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Executar toda a noite o Processamento de E-mails Retornados de Campanhas',
'LBL_PRUNEDATABASE' => 'Remover Base de Dados no Primeiro Dia do Mês',
'LBL_TRIMTRACKER' => 'Remover Tabelas Tracker',
'LBL_PROCESSWORKFLOW' => 'Processar Tarefas de Workflow',
'LBL_PROCESSQUEUE' => 'Executar tarefas criadas de execução de relatórios',
'LBL_UPDATETRACKERSESSIONS' => 'Actualizar Tabelas de Sessões de Trackers',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Criar Períodos de Tempo Futuros',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Executar Envio de lembretes por E-mail',
'LBL_CLEANJOBQUEUE' => 'Limpar a Fila de Trabalhos',
'LBL_CLEANOLDRECORDLISTS' => 'Limpar Listas de Registos Antigos',
'LBL_PMSEENGINECRON' => 'Calendarizador do Advanced Workflow',
);

