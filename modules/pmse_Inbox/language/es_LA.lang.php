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
    'LBL_MODULE_NAME' => 'Procesos',
    'LBL_MODULE_TITLE' => 'Procesos',
    'LBL_MODULE_NAME_SINGULAR' => 'Proceso',
    'LNK_LIST' => 'Ver procesos',
    'LNK_PMSE_INBOX_PROCESS_MANAGEMENT' => 'Gestión de Procesos',
    'LNK_PMSE_INBOX_UNATTENDED_PROCESSES' => 'Procesos Abandonados',

    'LBL_CAS_ID' => 'Número de Proceso',
    'LBL_PMSE_HISTORY_LOG_NOTFOUND_USER' => "Desconocido (según Id de usuario:'%s')",
    'LBL_PMSE_HISTORY_LOG_TASK_HAS_BEEN' => "la tarea ha sido",
    'LBL_PMSE_HISTORY_LOG_TASK_WAS' => "la tarea fue ",
    'LBL_PMSE_HISTORY_LOG_EDITED' => "editada",
    'LBL_PMSE_HISTORY_LOG_CREATED' => "creada",
    'LBL_PMSE_HISTORY_LOG_ROUTED' => "ruteada",
    'LBL_PMSE_HISTORY_LOG_DONE_UNKNOWN' => "Realizada una tarea desconocida",
    'LBL_PMSE_HISTORY_LOG_CREATED_CASE' => "creó proceso #%s ",
    'LBL_PMSE_HISTORY_LOG_DERIVATED_CASE' => "se asignó proceso #%s ",
    'LBL_PMSE_HISTORY_LOG_CURRENTLY_HAS_CASE' => "actualmente tiene proceso #%s ",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME' => "'%s'",
    'LBL_PMSE_HISTORY_LOG_ACTION_PERFORMED'  => ". La acción realizada fue: <span style=\"font-weight: bold\">[%s]</span>",
    'LBL_PMSE_HISTORY_LOG_ACTION_STILL_ASSIGNED' => " La tarea sigue estando asignada",
    'LBL_PMSE_HISTORY_LOG_MODULE_ACTION'  => " en el %s registro %s",
    'LBL_PMSE_HISTORY_LOG_WITH_EVENT'  => " con el evento %s",
    'LBL_PMSE_HISTORY_LOG_WITH_GATEWAY'  => ". La Puerta de enlace %s fue evaluada y ruteada a la siguiente tarea ",
    'LBL_PMSE_HISTORY_LOG_NOT_REGISTERED_ACTION'  => "acción no registrada",
    'LBL_PMSE_HISTORY_LOG_NO_YET_STARTED' => '(aún sin comenzar)',
    'LBL_PMSE_HISTORY_LOG_FLOW' => 'se le asignó la continuación de la tarea',

    'LBL_PMSE_HISTORY_LOG_START_EVENT' => "%s un registro de %s, que ha hecho que Advanced Workflow dispare un Proceso #%s",
    'LBL_PMSE_HISTORY_LOG_GATEWAY'  => "El %s %s %s se ha evaluado y dirigido a la siguiente tarea",
    'LBL_PMSE_HISTORY_LOG_EVENT'  => "El %s evento %s fue %s",
    'LBL_PMSE_HISTORY_LOG_END_EVENT'  => "Fin",
    'LBL_PMSE_HISTORY_LOG_CREATED'  => "creada",
    'LBL_PMSE_HISTORY_LOG_MODIFIED'  => "modificado",
    'LBL_PMSE_HISTORY_LOG_STARTED'  => "iniciado",
    'LBL_PMSE_HISTORY_LOG_PROCESSED'  => "procesado",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_SELF_SERVICE'  => "La actividad %s en el registro %s está disponible para el Autoservicio",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY'  => "%s la actividad %s en el registro %s",
    'LBL_PMSE_HISTORY_LOG_ASSIGNED'  => "se ha asignado",
    'LBL_PMSE_HISTORY_LOG_ROUTED'  => "ruteada",
    'LBL_PMSE_HISTORY_LOG_ACTION'  => "El %s de acción %s fue procesado en el registro %s",
    'LBL_PMSE_HISTORY_LOG_ASSIGN_USER_ACTION'  => "fue asignado a proceso #%s %s el registro %s por el %s de acción %s",
    'LBL_PMSE_HISTORY_LOG_ON'  => "en",
    'LBL_PMSE_HISTORY_LOG_AND'  => "y",

    'LBL_PMSE_LABEL_APPROVE' => 'Aprobar',
    'LBL_PMSE_LABEL_REJECT' => 'Rechazar',
    'LBL_PMSE_LABEL_ROUTE' => 'Ruta',
    'LBL_PMSE_LABEL_CLAIM' => 'Reclamar',
    'LBL_PMSE_LABEL_STATUS' => 'Estado',
    'LBL_PMSE_LABEL_REASSIGN' => 'Seleccionar Nuevo Usuario de Proceso',
    'LBL_PMSE_LABEL_CHANGE_OWNER' => 'Cambio Asignado al Usuario',
    'LBL_PMSE_LABEL_EXECUTE' => 'Ejecutar',
    'LBL_PMSE_LABEL_CANCEL' => 'Cancelar',
    'LBL_PMSE_LABEL_HISTORY' => 'Historial',
    'LBL_PMSE_LABEL_NOTES' => 'Mostrar notas',
    'LBL_PMSE_LABEL_ADD_NOTES' => 'Añadir Notas',

    'LBL_PMSE_FORM_OPTION_SELECT' => 'Seleccionar...',
    'LBL_PMSE_FORM_LABEL_USER' => 'Usuario',
    'LBL_PMSE_FORM_LABEL_TYPE' => 'Tipo',
    'LBL_PMSE_FORM_LABEL_NOTE' => 'Nota',

    'LBL_PMSE_BUTTON_SAVE' => 'Guardar',
    'LBL_PMSE_BUTTON_CLOSE' => 'Cerrar',
    'LBL_PMSE_BUTTON_CANCEL' => 'Cancelar',
    'LBL_PMSE_BUTTON_REFRESH' => 'Actualizar',
    'LBL_PMSE_BUTTON_CLEAR' => 'Borrar',
    'LBL_PMSE_WARNING_CLEAR' => '¿Está seguro de que desea borrar los datos del registro? Está acción no se puede deshacer.',

    'LBL_PMSE_FORM_TOOLTIP_SELECT_USER' => 'Asigna este proceso al usuario.',
    'LBL_PMSE_FORM_TOOLTIP_CHANGE_USER' => 'Actualiza el campo "Asignado a" a este usuario en el registro.',

    'LBL_PMSE_ALERT_REASSIGN_UNSAVED_FORM' => 'En el formulario actual hay cambios que no se han guardado. Estos cambios se descartarán si cambia la asignación de la tarea actual. ¿Desea continuar?',
    'LBL_PMSE_ALERT_REASSIGN_SUCCESS' => 'El proceso se ha reasignado correctamente',

    'LBL_PMSE_LABEL_CURRENT_ACTIVITY' => 'Actividad Actual',
    'LBL_PMSE_LABEL_ACTIVITY_DELEGATE_DATE' => 'Fecha de Delegación de Actividad',
    'LBL_PMSE_LABEL_ACTIVITY_START_DATE' => 'Fecha de Inicio',
    'LBL_PMSE_LABEL_EXPECTED_TIME' => 'Tiempo Esperado',
    'LBL_PMSE_LABEL_DUE_DATE' => 'Fecha de Vencimiento',
    'LBL_PMSE_LABEL_CURRENT' => 'Actual',
    'LBL_PMSE_LABEL_OVERDUE' => 'Vencida',
    'LBL_PMSE_LABEL_PROCESS' => 'Proceso',
    'LBL_PMSE_LABEL_PROCESS_AUTHOR' => 'Advanced Workflow',
    'LBL_PMSE_LABEL_UNASSIGNED' => 'Sin asignar',

    'LBL_RECORD_NAME'  => "Nombre del Registro",
    'LBL_PROCESS_NAME'  => "Nombre del Proceso",
    'LBL_PROCESS_DEFINITION_NAME'  => "Nombre de Definición de Proceso",
    'LBL_OWNER' => 'Asignada a',
    'LBL_ACTIVITY_OWNER'=>'Usuario de proceso',
    'LBL_PROCESS_OWNER'=>'Propietario de proceso',
    'LBL_STATUS_COMPLETED' => 'Procesos Completados',
    'LBL_STATUS_TERMINATED' => 'Procesos Terminados',
    'LBL_STATUS_IN_PROGRESS' => 'Procesos en curso',
    'LBL_STATUS_CANCELLED' => 'Procesos Cancelados',
    'LBL_STATUS_ERROR' => 'Error de Procesos',

    'LBL_PMSE_TITLE_PROCESSESS_LIST'  => 'Gestión de Procesos',
    'LBL_PMSE_TITLE_UNATTENDED_CASES' => 'Procesos Abandonados',
    'LBL_PMSE_TITLE_REASSIGN' => 'Cambio Asignado al Usuario',
    'LBL_PMSE_TITLE_AD_HOC' => 'Seleccionar Nuevo Usuario de Proceso',
    'LBL_PMSE_TITLE_ACTIVITY_TO_REASSIGN' => "Seleccionar Nuevo Usuario de Proceso",
    'LBL_PMSE_TITLE_HISTORY' => 'Historial del Proceso',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR' => 'Proceso #%s: Estado Actual',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR_OBJ' => 'Proceso #{{id}}: Estado Actual',
    'LBL_PMSE_TITLE_LOG_VIEWER' => 'Visor de registros de Advanced Workflow',
    'LBL_PMSE_TITLE_PROCESS_NOTES' => 'Process Notes',

    'LBL_PMSE_MY_PROCESSES' => 'Mis Procesos',
    'LBL_PMSE_SELF_SERVICE_PROCESSES' => 'Procesos de Autoservicio',

    'LBL_PMSE_ACTIVITY_STREAM_APPROVE'=>"&0 on <strong>%s</strong> Aprobado ",
    'LBL_PMSE_ACTIVITY_STREAM_REJECT'=>"&0 on <strong>%s</strong> Rechazado ",
    'LBL_PMSE_ACTIVITY_STREAM_ROUTE'=>'&0 on <strong>%s</strong> Ruteado ',
    'LBL_PMSE_ACTIVITY_STREAM_CLAIM'=>"&0 on <strong>%s</strong> Reclamado ",
    'LBL_PMSE_ACTIVITY_STREAM_REASSIGN'=>"&0 on <strong>%s</strong> asignado al usuario &1 ",
    'LBL_PMSE_CANCEL_MESSAGE' => "¿Está seguro de que desea cancelar el Proceso Número #{}?",
    'LBL_ASSIGNED_USER'=>"Usuario Asignado",
    'LBL_PMSE_SETTING_NUMBER_CYCLES' => "Error en el Numero de Ciclos",
    'LBL_PMSE_SHOW_PROCESS' => 'Mostrar Proceso',
    'LBL_PMSE_FILTER' => 'Filtro',

    'LBL_PA_PROCESS_APPROVE_QUESTION' => '¿Está seguro de que desea aprobar este proceso?',
    'LBL_PA_PROCESS_REJECT_QUESTION' => '¿Está seguro de que desea rechazar este proceso?',
    'LBL_PA_PROCESS_ROUTE_QUESTION' => '¿Está seguro de que desea rutear este proceso?',
    'LBL_PA_PROCESS_APPROVED_SUCCESS' => 'El proceso se ha aprobado con éxito',
    'LBL_PA_PROCESS_REJECTED_SUCCESS' => 'Process has been rejected con éxito',
    'LBL_PA_PROCESS_ROUTED_SUCCESS' => 'El proceso se ruteó con éxito',
    'LBL_PA_PROCESS_CLOSED' => 'El proceso que está intentando ver está cerrado.',
    'LBL_PA_PROCESS_UNAVAILABLE' => 'El proceso que intenta ver no está disponible en este momento.',

    'LBL_PMSE_ASSIGN_USER' => 'Asignar Usuario',
    'LBL_PMSE_ASSIGN_USER_APPLIED' => 'Asignar Usuario Aplicado',

    'LBL_PMSE_LABEL_PREVIEW' => 'Previsualización del Diseño de Proceso',
);

