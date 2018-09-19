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
'LBL_OOTB_WORKFLOW'		=> 'Procesar Tareas de Workflow',
'LBL_OOTB_REPORTS'		=> 'Ejecutar Tareas Programadas de Generación de Informes',
'LBL_OOTB_IE'			=> 'Comprobar Bandejas de Entrada',
'LBL_OOTB_BOUNCE'		=> 'Ejecutar Proceso Nocturno de Emails de Campaña Rebotados',
'LBL_OOTB_CAMPAIGN'		=> 'Ejecutar Proceso Nocturno de Campañas de Email Masivo',
'LBL_OOTB_PRUNE'		=> 'Truncar Base de datos al Inicio del Mes',
'LBL_OOTB_TRACKER'		=> 'Truncar Tablas de Monitorización',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Recortar las Listas de Récords Antiguos',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Eliminar archivos temporales',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Eliminar archivos de la herramienta de diagnósticos',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Eliminar archivos PDF temporales',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Actualizar Tabla tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Ejecutar las notificaciones de aviso por correo electrónico',
'LBL_OOTB_CLEANUP_QUEUE' => 'Limpiar Cola de Trabajos',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Nuevos Períodos de Tiempo Futuros',
'LBL_OOTB_HEARTBEAT' => 'Control Automático de Usuarios de Sugar',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Actualizar artículos de KBContent.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Publicar artículos aprobados y Hacer expirar artículos KB.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Trabajo programado de Advance Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Reconstruir los datos de seguridad desregularizados del equipo',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Intervalo:',
'LBL_LIST_LIST_ORDER' => 'Planificadores:',
'LBL_LIST_NAME' => 'Planificador:',
'LBL_LIST_RANGE' => 'Rango:',
'LBL_LIST_REMOVE' => 'Quitar:',
'LBL_LIST_STATUS' => 'Estado:',
'LBL_LIST_TITLE' => 'Lista de Planificación:',
'LBL_LIST_EXECUTE_TIME' => 'Será ejecutado en:',
// human readable:
'LBL_SUN'		=> 'Domingo',
'LBL_MON'		=> 'Lunes',
'LBL_TUE'		=> 'Martes',
'LBL_WED'		=> 'Miércoles',
'LBL_THU'		=> 'Jueves',
'LBL_FRI'		=> 'Viernes',
'LBL_SAT'		=> 'Sábado',
'LBL_ALL'		=> 'Todos los días',
'LBL_EVERY_DAY'	=> 'Todos los días',
'LBL_AT_THE'	=> 'El',
'LBL_EVERY'		=> 'Cada',
'LBL_FROM'		=> 'Desde',
'LBL_ON_THE'	=> 'En el',
'LBL_RANGE'		=> 'a',
'LBL_AT' 		=> 'en',
'LBL_IN'		=> 'en',
'LBL_AND'		=> 'y',
'LBL_MINUTES'	=> 'minutos',
'LBL_HOUR'		=> 'horas',
'LBL_HOUR_SING'	=> 'hora',
'LBL_MONTH'		=> 'mes',
'LBL_OFTEN'		=> 'Tan a menudo como sea posible.',
'LBL_MIN_MARK'	=> 'marca por minuto',


// crontabs
'LBL_MINS' => 'min.',
'LBL_HOURS' => 'h.',
'LBL_DAY_OF_MONTH' => 'fecha',
'LBL_MONTHS' => 'me',
'LBL_DAY_OF_WEEK' => 'día',
'LBL_CRONTAB_EXAMPLES' => 'Lo arriba mostrado utiliza notación estándar de crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Las especificaciones del cron para que se ejecute es sobre la base de la zona horaria del servidor (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Especifique la hora de ejecución del planificador en consecuencia.',
// Labels
'LBL_ALWAYS' => 'Siempre',
'LBL_CATCH_UP' => 'Ejecutar Si Falla',
'LBL_CATCH_UP_WARNING' => 'Desmarque si la ejecución de esta tarea puede durar más de un momento.',
'LBL_DATE_TIME_END' => 'Fecha y Hora de Finalización',
'LBL_DATE_TIME_START' => 'Fecha y Hora de Inicio',
'LBL_INTERVAL' => 'Intervalo',
'LBL_JOB' => 'Tarea',
'LBL_JOB_URL' => 'URL de la tarea',
'LBL_LAST_RUN' => 'Última Ejecución Exitosa',
'LBL_MODULE_NAME' => 'Planificador Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Planificador Sugar',
'LBL_MODULE_TITLE' => 'Planificadores',
'LBL_NAME' => 'Nombre de Tarea',
'LBL_NEVER' => 'Nunca',
'LBL_NEW_FORM_TITLE' => 'Nueva Planificación',
'LBL_PERENNIAL' => 'continuo',
'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de Planificación',
'LBL_SCHEDULER' => 'Planificador:',
'LBL_STATUS' => 'Estado',
'LBL_TIME_FROM' => 'Activo Desde',
'LBL_TIME_TO' => 'Activo Hasta',
'LBL_WARN_CURL_TITLE' => 'Aviso cURL:',
'LBL_WARN_CURL' => 'Aviso:',
'LBL_WARN_NO_CURL' => 'Este sistema no tiene las librerías cURL habilitadas/compiladas en el módulo de PHP (--with-curl=/ruta/a/libreria_curl).  Contacte con su administrador para resolver el problema.  Sin la funcionalidad que provee cURL, el Planificador no puede utilizar hilos con sus tareas.',
'LBL_BASIC_OPTIONS' => 'Configuración Básica',
'LBL_ADV_OPTIONS'		=> 'Opciones Avanzadas',
'LBL_TOGGLE_ADV' => 'Mostrar Opciones Avanzadas',
'LBL_TOGGLE_BASIC' => 'Mostrar Opciones Básicas',
// Links
'LNK_LIST_SCHEDULER' => 'Planificadores',
'LNK_NEW_SCHEDULER' => 'Nuevo Planificador',
'LNK_LIST_SCHEDULED' => 'Tareas Planificadas',
// Messages
'SOCK_GREETING' => "Este es el interfaz de usuario para el Servicio de Planificación de SugarCRM. <br />[ Comandos de demonio disponibles: start|restart|shutdown|status ]<br />Para salir, teclee &#39;quit&#39;.  Para detener el servicio &#39;shutdown&#39;.",
'ERR_DELETE_RECORD' => 'Debe especificar un número de registro para eliminar la planificación.',
'ERR_CRON_SYNTAX' => 'Sintaxis de Cron inválida',
'NTC_DELETE_CONFIRMATION' => '¿Está seguro de que desea eliminar este registro?',
'NTC_STATUS' => 'Establezca el estado a Inactivo para quitar esta planificación de las listas desplegables de selección de Planificador',
'NTC_LIST_ORDER' => 'Establezca el orden en que esta planificación aparecerá en las listas desplegables de selección de Planificador',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Para configurar el Planificador de Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Para configurar Crontab',
'LBL_CRON_LINUX_DESC' => 'Nota: Para ejecutar los Planificadores de Sugar, añada la siguiente línea a su archivo crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Nota: Para ejecutar los planificadores de Sugar, cree un archivo de proceso por lotes a ejecutar utilizando las Tareas Programadas de Windows. El archivo de proceso por lotes debería contener los siguientes comandos:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Registro de Tareas',
'LBL_EXECUTE_TIME'			=> 'Tiempo de Ejecución',

//jobstrings
'LBL_REFRESHJOBS' => 'Actualizar Trabajos',
'LBL_POLLMONITOREDINBOXES' => 'Comprobar Cuentas de Correo Entrante',
'LBL_PERFORMFULLFTSINDEX' => 'Búsqueda de texto completo Sistema de Índice',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Eliminar archivos PDF temporales',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Publicar artículos aprobados y Hacer expirar artículos KB.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Programador de cola de Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Eliminar archivos de la herramienta de diagnósticos',
'LBL_SUGARJOBREMOVETMPFILES' => 'Eliminar archivos temporales',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Reconstruir los datos de seguridad desregularizados del equipo',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Ejecutar Campañas de Correo Masivo Nocturnas',
'LBL_ASYNCMASSUPDATE' => 'Realizar Actualizaciones Asíncronas Masivas',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Ejecutar Proceso Nocturno de Correos Rebotados en Campañas',
'LBL_PRUNEDATABASE' => 'Truncar Base de Datos el 1º de cada Mes',
'LBL_TRIMTRACKER' => 'Truncar Tablas de Monitorización',
'LBL_PROCESSWORKFLOW' => 'Procesar Tareas de Workflow',
'LBL_PROCESSQUEUE' => 'Ejecutar Tareas Planificadas de Generación de Informes',
'LBL_UPDATETRACKERSESSIONS' => 'Actualizar Tablas de Sesión de Monitorización',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Nuevos Períodos de Tiempo Futuros',
'LBL_SUGARJOBHEARTBEAT' => 'Control Automático de Usuarios de Sugar',
'LBL_SENDEMAILREMINDERS'=> 'Ejecutar envío de recordatorios por correo electrónico',
'LBL_CLEANJOBQUEUE' => 'Liberador de la Cola de Trabajos',
'LBL_CLEANOLDRECORDLISTS' => 'Limpiar las Listas de Registros Antiguos',
'LBL_PMSEENGINECRON' => 'Programador Advanced Workflow',
);

