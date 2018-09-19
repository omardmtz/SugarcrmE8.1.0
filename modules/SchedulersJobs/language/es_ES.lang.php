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
    'LINK_SCHEDULERSJOBS_LIST' => 'Ver cola de trabajos',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Configuración',
    'LBL_CONFIG_PAGE' => 'Configuracion de la cola de trabajo',
    'LBL_JOB_CANCEL_BUTTON' => 'Cancelar',
    'LBL_JOB_PAUSE_BUTTON' => 'Pausa',
    'LBL_JOB_RESUME_BUTTON' => 'Reanudar',
    'LBL_JOB_RERUN_BUTTON' => 'Volver a la cola',
    'LBL_LIST_NAME' => 'Nombre',
    'LBL_LIST_ASSIGNED_USER' => 'Solicitado por',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_RESOLUTION' => 'Resolución',
    'LBL_NAME' => 'Nombre de tarea',
    'LBL_EXECUTE_TIME' => 'Hora de Ejecución',
    'LBL_SCHEDULER_ID' => 'Planificador',
    'LBL_STATUS' => 'Estado de tarea',
    'LBL_RESOLUTION' => 'Resolución',
    'LBL_MESSAGE' => 'Mensajes',
    'LBL_DATA' => 'Fecha de tarea',
    'LBL_REQUEUE' => 'Vuelver a intentar en caso de fallo',
    'LBL_RETRY_COUNT' => 'Intentos máximos',
    'LBL_FAIL_COUNT' => 'Fallos',
    'LBL_INTERVAL' => 'Intervalo mínimo entre intentos',
    'LBL_CLIENT' => 'Cliente propietario',
    'LBL_PERCENT' => 'Porcentaje completado',
    'LBL_JOB_GROUP' => 'Grupo de tarea',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Resolución en cola',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Resolución parcial',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Resolución completa',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Error de resolución',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Resolución cancelada',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Resolución en ejecución',
    // Errors
    'ERR_CALL' => "No se puede llamar a la función: %s",
    'ERR_CURL' => "No CURL - no se puede ejecutar trabajos de URL",
    'ERR_FAILED' => "Error inesperado, consulte los registros de PHP y sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s en %s on line %d",
    'ERR_NOUSER' => "No hay ID de usuario especificado para el trabajo",
    'ERR_NOSUCHUSER' => "ID %s de usuario no encontrado",
    'ERR_JOBTYPE' => "Tipo de tarea desconocido: %s",
    'ERR_TIMEOUT' => "Fracaso forzoso por tiempo de espera",
    'ERR_JOB_FAILED_VERBOSE' => 'Tarea %1$s (%2$s) falló en la ejecución del CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'No se puede cargar bean con id.: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'No se puede encontrar controlador para la ruta %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'La extensión para esta cola no está instalada',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Algunos de los campos están vacíos',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'Configuracion de la cola de trabajo',
    'LBL_CONFIG_MAIN_SECTION' => 'Configuración principal',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Configuración Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'Configuración AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Configuración Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'Ayuda de configuración de colas de trabajo',
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
<li><i>Cola por defecto</i>: esto usará solo la base de datos de Sugar sin ninguna cola de mensaje.</li>
<li><i>Amazon SQS</i>: Amazon Simple Queue Service es un servicio de mensajería de colas distribuidas presentada por Amazon.com.
Admite el envío programático de mensajes mediante aplicaciones del servicio web como método para comunicarse por Internet.</li>
<li><i>RabbitMQ</i>: es un software de negociación de mensajes de código abierto (a veces llamado un middleware orientado a mensajería) que implementa el Advanced Message Queuing Protocol (AMQP).</li>
<li><i>Gearman</i>: es un marco de aplicación de código abierto diseñado para distribuir las tareas de ordenador apropiadas a varios ordenadores, así que las tareas grandes puedan realizarse con mayor rapidez.</li>
<li><i>Inmediato</i>: es igual a la cola por defecto pero ejecuta las tareas inmediatamente después de la adición.</li>
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
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>Sección de configuración de AMQP.</b></p>
<ul>
    <li>URL del servidor: <i>escriba la URL del servidor de la cola de mensajes.</i></li>
    <li>Inicio de sesión: <i>escriba el inicio de sesión de RabbitMQ</i></li>
    <li>Contraseña: <i>escriba la contraseña para RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Ayuda de configuración Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Sección de configuración de Gearman.</b></p>
<ul>
    <li>URL del servidor: <i>escriba la URL del servidor de la cola del mensaje.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Adaptador',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Ejecutor',
    'LBL_SERVER_URL' => 'URL del Servidor',
    'LBL_LOGIN' => 'Inicio de Sesión',
    'LBL_ACCESS_KEY' => 'Id. de clave de acceso',
    'LBL_REGION' => 'Región',
    'LBL_ACCESS_KEY_SECRET' => 'Clave de acceso secreta',
    'LBL_QUEUE_NAME' => 'Nombre del adaptador',
);
