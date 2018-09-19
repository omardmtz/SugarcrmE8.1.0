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
    'LBL_MODULE_NAME' => 'Cola de trabajos',
    'LBL_MODULE_NAME_SINGULAR' => 'Cola de trabajos',
    'LBL_MODULE_TITLE' => 'Cola de trabajo: Inicio',
    'LBL_MODULE_ID' => 'Cola de trabajos',
    'LBL_TARGET_ACTION' => 'Acción',
    'LBL_FALLIBLE' => 'Falible',
    'LBL_RERUN' => 'Volver a ejecutar',
    'LBL_INTERFACE' => 'Interfaz',
    'LINK_SCHEDULERSJOBS_LIST' => 'Ver Cola de Trabajos',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuración',
    'LBL_CONFIG_PAGE' => 'Configuración de Colas de Trabajo',
    'LBL_JOB_CANCEL_BUTTON' => 'Cancelar',
    'LBL_JOB_PAUSE_BUTTON' => 'Pausa',
    'LBL_JOB_RESUME_BUTTON' => 'Continuar',
    'LBL_JOB_RERUN_BUTTON' => 'Volver a la cola',
    'LBL_LIST_NAME' => 'Nombre',
    'LBL_LIST_ASSIGNED_USER' => 'Solicitado por',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_RESOLUTION' => 'Resolución',
    'LBL_NAME' => 'Nombre de tarea',
    'LBL_EXECUTE_TIME' => 'Hora de Ejecución',
    'LBL_SCHEDULER_ID' => 'Planificador',
    'LBL_STATUS' => 'Estado de tarea',
    'LBL_RESOLUTION' => 'Resultado',
    'LBL_MESSAGE' => 'Mensajes',
    'LBL_DATA' => 'Fecha de tarea',
    'LBL_REQUEUE' => 'Voelver a intentar en caso de fallo',
    'LBL_RETRY_COUNT' => 'Intentos máximos',
    'LBL_FAIL_COUNT' => 'Fallos',
    'LBL_INTERVAL' => 'Intervalo mínimo entre intentos',
    'LBL_CLIENT' => 'Ser propietario de cliente',
    'LBL_PERCENT' => 'Porcentaje completado',
    'LBL_JOB_GROUP' => 'Grupo de tarea',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolución en cola',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolución parcial',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolución completa',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Error de resolución',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Resolución Cancelada',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Resolución en ejecución',
    // Errors
    'ERR_CALL' => "No se puede llamar a la función: %s",
    'ERR_CURL' => "No CURL - no se pueden ejecutar trabajos de URL",
    'ERR_FAILED' => "Error inesperado, por favor, consulte los registros de PHP y sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s en %s on line %d",
    'ERR_NOUSER' => "No se ha especificado un ID de usuario para el trabajo",
    'ERR_NOSUCHUSER' => "No se ha encontrado un ID %s de usuario",
    'ERR_JOBTYPE' => "Tipo de tarea desconocido: %s",
    'ERR_TIMEOUT' => "Falla forzosa por tiempo de espera agotado",
    'ERR_JOB_FAILED_VERBOSE' => 'La tarea %1$s (%2$s) ha fallado en la ejecución del CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'No se puede cargar bean con id.: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'No se puede encontrar el controlador para la ruta %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'La extensión para esta cola no está instalada',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Algunos de los campos están vacíos',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Configuración de Colas de Trabajo',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuración principal',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuración de Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuración de AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuración de Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ayuda de Configuración de Colas de Trabajo',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>Sección de configuración principal.</b></p>
<ul>
<li>Ejecutor:
<ul>
<li><i>Estándar</i>: usa solo un proceso para los trabajadores.</li>
<li><i>Paralelo</i>: usa algunos procesos para los trabajadores.</li>
</ul>
</li>
<li>Adaptador:
<ul>
<li><i>Cola predeterminada</i>: esto usará solo la base de datos de Sugar sin ninguna cola de mensaje.</li>
<li><i>Amazon SQS</i>: Amazon Simple Queue Service es un servicio de mensajería de colas distribuidas presentado por Amazon.com.
Admite el envío programático de mensajes mediante aplicaciones del servicio web como método para comunicarse por Internet.</li>
<li><i>RabbitMQ</i>: es un software de negociación de mensajes de código abierto (a veces llamado un middleware orientado a mensajería) que implementa el Advanced Message Queuing Protocol (AMQP).</li>
<li><i>Gearman</i>: es una herramienta de aplicación de código abierto diseñado para distribuir tareas de computadoras apropiadas a múltiples computadoras, de manera que se puedan realizar trabajoss grandes con mayor rapidez.</li>
<li><i>Inmediato</i>: es igual a la cola predeterminada pero ejecuta las tareas inmediatamente después de la adición.</li>
</ul>
</li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Ayuda de configuración de Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Sección de configuración de Amazon SQS.</b></p>
<ul>
<li>Id. de clave de acceso: <i>escriba su número de id. de la clave de acceso de Amazon SQS</i></li>
<li>Clave de acceso secreta: <i>escriba su clave de acceso secreta de Amazon SQS</i></li>
<li>Región: <i>escriba la región del servidor Amazon SQS</i>
</li>
<li>Nombre de la cola: <i>ingrese el nombre de cola del servidor Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'Ayuda de configuración de AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Sección de configuración de AMQP.</b></p> <ul><li>URL del servidor: <i>Introduzca el URL de su servidor de mensajes en cola.</i></li>     <li>Login: <i>ingrese su login para RabbitMQ</i></li>     <li>Contraseña: <i>Introduzca su contraseña para RabbitMQ</i></li></ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ayuda para la Configuración de Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Sección de configuración de Gearman.</b></p>
<ul>
    <li>URL del servidor: <i>escriba el URL del servidor de la cola del mensaje.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptador',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Ejecutor',
    'LBL_SERVER_URL' => 'URL del Servidor',
    'LBL_LOGIN' => 'Iniciar Sesión',
    'LBL_ACCESS_KEY' => 'ID de clave de acceso',
    'LBL_REGION' => 'Región',
    'LBL_ACCESS_KEY_SECRET' => 'Clave de Acceso Secreta',
    'LBL_QUEUE_NAME' => 'Nombre del Adaptador',
);
