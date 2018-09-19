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

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Definiciones de Flujo de Actividad',
  'LBL_MODULE_NAME_SINGULAR' => 'Definición de Flujo de Actividad',
  'LBL_MODULE_ID' => 'Flujo de Actividad',  
  'LBL_MODULE_TITLE' => 'Flujo de Actividad: Inicio',
  'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de Flujo de Actividad',
  'LBL_LIST_FORM_TITLE' => 'Lista de Flujo de Actividad',
  'LBL_NEW_FORM_TITLE' => 'Crear Definición de Flujo de Actividad',
  'LBL_LIST_NAME' => 'Nombre',
  'LBL_LIST_TYPE' => 'La Ejecución Tiene lugar:',
  'LBL_LIST_BASE_MODULE' => 'Módulo Objetivo:',
  'LBL_LIST_STATUS' => 'Estado',
  'LBL_NAME' => 'Nombre:',
  'LBL_DESCRIPTION' => 'Descripción:',
  'LBL_TYPE' => 'La Ejecución Tiene lugar:',
  'LBL_STATUS' => 'Estado:',
  'LBL_BASE_MODULE' => 'Módulo Objetivo:',
  'LBL_LIST_ORDER' => 'Órden de Proceso:',
  'LBL_FROM_NAME' => 'Nombre del Remitente:',
  'LBL_FROM_ADDRESS' => 'Dirección del Remitente:',  
  'LNK_NEW_WORKFLOW' => 'Crear Definición de Flujo de Actividad',
  'LNK_WORKFLOW' => 'Listar Definiciones de Flujo de Actividad', 
  
  
  'LBL_ALERT_TEMPLATES' => 'Plantillas de Alertas',
  'LBL_CREATE_ALERT_TEMPLATE' => 'Crear una plantilla de alerta:',
  'LBL_SUBJECT' => 'Asunto:',
  
  'LBL_RECORD_TYPE' => 'Aplica a:',
 'LBL_RELATED_MODULE'=> 'Módulo Relacionado:',
  
  
  'LBL_PROCESS_LIST' => 'Secuencia de Flujo de Actividad',
	'LNK_ALERT_TEMPLATES' => 'Plantillas de Emails de Alerta',
	'LNK_PROCESS_VIEW' => 'Secuencia de Flujo de Actividad',
  'LBL_PROCESS_SELECT' => 'Por favor, seleccione un módulo:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> 'Advertencia: Debe crear un disparador para que el objeto del Flujo de Actividad funcione',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> 'Advertencia: Para enviar alertas, proporcione la información sobre el Servidor SMTP en Admin > Configuración de Correo Electrónico.',
  'LBL_FIRE_ORDER' => 'Orden de Proceso:',
  'LBL_RECIPIENTS' => 'Destinatarios',
  'LBL_INVITEES' => 'Asistentes',
  'LBL_INVITEE_NOTICE' => 'Atención, debe seleccionar al menos un asistente para crear esto. ',
  'NTC_REMOVE_ALERT' => '¿Está seguro de que quiere eliminar este Flujo de Actividad?',
  'LBL_EDIT_ALT_TEXT' => 'Texto Alt',
  'LBL_INSERT' => 'Insertar',
  'LBL_SELECT_OPTION' => 'Por favor, seleccione una opción.',
  'LBL_SELECT_VALUE' => 'Debe seleccionar un valor.',
  'LBL_SELECT_MODULE' => 'Por favor, seleccione un módulo relacionado.',
  'LBL_SELECT_FILTER' => 'Debe seleccionar un campo con el que filtrar el módulo relacionado.',
  'LBL_LIST_UP' => 'ar',
  'LBL_LIST_DN' => 'ab',
  'LBL_SET' => 'Establecer',
  'LBL_AS' => 'como',
  'LBL_SHOW' => 'Mostrar',
  'LBL_HIDE' => 'Ocultar',
  'LBL_SPECIFIC_FIELD' => 'campo especifico',
  'LBL_ANY_FIELD' => 'cualquier campo',
  'LBL_LINK_RECORD'=>'Vincular con Registro',
  'LBL_INVITE_LINK'=>'Enlace de Invitación a Reunión/Llamada',
  'LBL_PLEASE_SELECT'=>'Por favor, Seleccione',
  'LBL_BODY'=>'Cuerpo:',
  'LBL__S'=>'s',
  'LBL_ALERT_SUBJECT'=>'ALERTA DE FLUJO DE ACTIVIDAD',
  'LBL_ACTION_ERROR'=>'Esta acción no puede ser ejecutada. Edite la acción para que todos los campos y valores sean válidos',
  'LBL_ACTION_ERRORS'=>'Aviso: Una o más de las siguientes acciones contiene errores.',
  'LBL_ALERT_ERROR'=>'Esta alerta no puede ser ejecutada. Edite la alerta para que todas sus opciones de configuración sean válidas.',
  'LBL_ALERT_ERRORS'=>'Aviso: Una o más de las siguientes alertas contiene errores.',
  'LBL_TRIGGER_ERROR'=>'Aviso: Este disparador contiene valores no válidos y no se disparará.',
  'LBL_TRIGGER_ERRORS'=>'Aviso: Uno o más de los siguientes disparadores contiene errores.',
  'LBL_UP' => 'Arriba' /*for 508 compliance fix*/,
  'LBL_DOWN' => 'Abajo' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'Editar diseño' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'Flujo de Actividad'),
  'LBL_EMAILTEMPLATES_TYPE' => 'Tipo',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Nota:</strong> Las funciones de Flujo de Trabajo de Sugar y Administación de Flujo de trabajo se eliminarán en versiones futuras de Sugar. Los clientes de la edición Sugar Enterprise deberían comenzar a utilizar las funciones brindadas por Flujo de Trabajo Avanzado de Sugar. Haga clic <a href="http://www.sugarcrm.com/wf-eol" target="_blank">aquí</a> para más información.',
);

