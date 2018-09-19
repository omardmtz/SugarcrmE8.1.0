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

$mod_strings = array (
  // Dashboard Names
  'LBL_TASKS_LIST_DASHBOARD' => 'Tablero de Lista de Tareas',

  'LBL_MODULE_NAME' => 'Tareas',
  'LBL_MODULE_NAME_SINGULAR' => 'Tarea',
  'LBL_TASK' => 'Tareas:',
  'LBL_MODULE_TITLE' => 'Tareas: Inicio',
  'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de Tareas',
  'LBL_LIST_FORM_TITLE' => 'Lista de Tareas',
  'LBL_NEW_FORM_TITLE' => 'Nueva Tarea',
  'LBL_NEW_FORM_SUBJECT' => 'Asunto:',
  'LBL_NEW_FORM_DUE_DATE' => 'Fecha de Vencimiento:',
  'LBL_NEW_FORM_DUE_TIME' => 'Hora de Vencimiento:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Cerrar',
  'LBL_LIST_SUBJECT' => 'Asunto',
  'LBL_LIST_CONTACT' => 'Contacto',
  'LBL_LIST_PRIORITY' => 'Prioridad',
  'LBL_LIST_RELATED_TO' => 'Relacionada con',
  'LBL_LIST_DUE_DATE' => 'Fecha de vencimiento',
  'LBL_LIST_DUE_TIME' => 'Hora de Vencimiento',
  'LBL_SUBJECT' => 'Asunto:',
  'LBL_STATUS' => 'Estado:',
  'LBL_DUE_DATE' => 'Fecha de vencimiento:',
  'LBL_DUE_TIME' => 'Hora de vencimiento:',
  'LBL_PRIORITY' => 'Prioridad:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Fecha y hora de vencimiento:',
  'LBL_START_DATE_AND_TIME' => 'Fecha y hora de inicio:',
  'LBL_START_DATE' => 'Fecha de inicio:',
  'LBL_LIST_START_DATE' => 'Fecha de inicio',
  'LBL_START_TIME' => 'Hora de inicio:',
  'LBL_LIST_START_TIME' => 'Hora de inicio',
  'DATE_FORMAT' => '(aaaa-mm-dd)',
  'LBL_NONE' => 'Ninguno',
  'LBL_CONTACT' => 'Contacto:',
  'LBL_EMAIL_ADDRESS' => 'Dirección de Correo:',
  'LBL_PHONE' => 'Teléfono:',
  'LBL_EMAIL' => 'Correo Electrónico:',
  'LBL_DESCRIPTION_INFORMATION' => 'Información adicional',
  'LBL_DESCRIPTION' => 'Descripción:',
  'LBL_NAME' => 'Nombre:',
  'LBL_CONTACT_NAME' => 'Nombre de Contacto ',
  'LBL_LIST_COMPLETE' => 'Completo:',
  'LBL_LIST_STATUS' => 'Estado',
  'LBL_DATE_DUE_FLAG' => 'Sin fecha de vencimiento',
  'LBL_DATE_START_FLAG' => 'Sin fecha de inicio',
  'ERR_DELETE_RECORD' => 'Debe especificar un número de registro para eliminar el Contacto.',
  'ERR_INVALID_HOUR' => 'Por favor, introduzca una hora entre 0 y 24',
  'LBL_DEFAULT_PRIORITY' => 'Media',
  'LBL_LIST_MY_TASKS' => 'Mis Tareas Abiertas',
  'LNK_NEW_TASK' => 'Crear tarea',
  'LNK_TASK_LIST' => 'Ver tareas',
  'LNK_IMPORT_TASKS' => 'Importar tareas',
  'LBL_CONTACT_FIRST_NAME'=>'Nombre del Contacto',
  'LBL_CONTACT_LAST_NAME'=>'Apellido del Contacto',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuario Asignado',
  'LBL_ASSIGNED_TO_NAME'=>'Asignado a:',
  'LBL_LIST_DATE_MODIFIED' => 'Fecha de Modificación',
  'LBL_CONTACT_ID' => 'ID de Contacto:',
  'LBL_PARENT_ID' => 'ID del registro principal:',
  'LBL_CONTACT_PHONE' => 'Teléfono de Contacto:',
  'LBL_PARENT_NAME' => 'Tipo de Registro Principal:',
  'LBL_ACTIVITIES_REPORTS' => 'Informe de Actividades',
  'LBL_EDITLAYOUT' => 'Editar diseño' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Reseña General',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notas',
  'LBL_REVENUELINEITEMS' => 'Artículos de Línea de Ganancia',
  //For export labels
  'LBL_DATE_DUE' => 'Fecha de vencimiento',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nombre de Usuario Asignado',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID de Usuario Asignado',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificado por ID',
  'LBL_EXPORT_CREATED_BY' => 'Creado por ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Relacionado con el módulo',
  'LBL_EXPORT_PARENT_ID' => 'Relacionado con el ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Tarea cerrada exitosamente',
  'LBL_ASSIGNED_USER' => 'Asignado a',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notas',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'El módulo {{plural_module_name}} consta de acciones flexibles, tareas u otro tipo de actividad que debe ser finalizada. Los registros {{module_name}} puede relacionarse con un registro en la mayoría de los módulos a través del campo flexible y también se pueden relacionar con un solo {{contacts_singular_module}}. Hay varias formas de crear {{plural_module_name}} en Sugar tales como a través del módulo {{plural_module_name}}, mediante duplicación, importar {{plural_module_name}}, etc. Una vez que el registro {{module_name}} se crea, puede ver y editar la información relativa al módulo {{module_name}} vía la vista del registro {{plural_module_name}}. Dependiendo de los detalles del módulo {{module_name}}, también puede ver y editar la información {{module_name}} vía el módulo de Calendario. Cada registro{{module_name}} puede entonces relacionarse con otros registros de Sugar así como {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, y muchos otros.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'El módulo {{plural_module_name}} consta de acciones flexibles, tareas, u otro tipo de actividades que requieren finalización.
- Edite los campos de este registro, haga clic en un campo individual o en el botón Editar. 
- Vea o modifique enlaces a otros registros en los subpaneles al deslizar el panel inferior izquierdo hacia "Vista de datos". 
- Haga y vea comentarios de los usuarios y registre el historial de cambios en el {{activitystream_singular_module}} al desplazar el panel inferior izquierdo hacia "Últimas acciones". 
- Siga o marque como favorito este registro utilizando los iconos a la derecha del nombre del registro. 
- Las acciones adicionales se encuentran disponibles en el menú Acciones desplegables a la derecha del botón Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'El módulo {{plural_module_name}} consta de acciones flexibles, tareas, u otro tipo de actividad que requiera finalización.

Para crear un {{module_name}}:
1. Proporcione valores para los campos según se desee. 
  - Los campos marcados como "Obligatorios" deben ser completado antes de guardar. 
  - Haga clic en "Mostrar más" para exponer campos adicionales si es necesario. 
2. Haga clic en "Guardar" para finalizar el nuevo registro y volver a la página anterior.',

);
