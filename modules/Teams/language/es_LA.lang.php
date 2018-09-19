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
    'ERR_ADD_RECORD' => 'Debe especificar un número de registro para agregar a este usuario al equipo.',
    'ERR_DUP_NAME' => 'El Nombre de Equipo ya existe, por favor elija otro distinto.',
    'ERR_DELETE_RECORD' => 'Debe especificar un número de registro para eliminar este equipo.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Error.  El equipo seleccionado <b>({0})</b> es un equipo que ha elegido eliminar.  Por favor seleccione otro equipo.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Error. No puede eliminar un usuario cuyo equipo privado no esté eliminado.',
    'LBL_DESCRIPTION' => 'Descripción:',
    'LBL_GLOBAL_TEAM_DESC' => 'Visibilidad Global',
    'LBL_INVITEE' => 'Miembros del Equipo',
    'LBL_LIST_DEPARTMENT' => 'Departmento',
    'LBL_LIST_DESCRIPTION' => 'Descripción',
    'LBL_LIST_FORM_TITLE' => 'Lista de Equipos',
    'LBL_LIST_NAME' => 'Nombre Completo',
    'LBL_FIRST_NAME' => 'Nombre:',
    'LBL_LAST_NAME' => 'Apellido:',
    'LBL_LIST_REPORTS_TO' => 'Informa a',
    'LBL_LIST_TITLE' => 'Título',
    'LBL_MODULE_NAME' => 'Equipos',
    'LBL_MODULE_NAME_SINGULAR' => 'Equipo',
    'LBL_MODULE_TITLE' => 'Equipos: Inicio',
    'LBL_NAME' => 'Nombre de Equipo:',
    'LBL_NAME_2' => 'Nombre de Equipo(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Nombre de Equipo Principal',
    'LBL_NEW_FORM_TITLE' => 'Nuevo Equipo',
    'LBL_PRIVATE' => 'Privado',
    'LBL_PRIVATE_TEAM_FOR' => 'Equipo Privado para:',
    'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de Equipos',
    'LBL_TEAM_MEMBERS' => 'Miembros del Equipo',
    'LBL_TEAM' => 'Equipos:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Usuarios',
    'LBL_USERS' => 'Usuarios',
    'LBL_REASSIGN_TEAM_TITLE' => 'Hay registros asignados a los siguientes equipos: <b>{0}</b><br>Antes de eliminar los equipos, debe primero reasignar estos registros a un nuevo equipo.  Seleccione un equipo para utilizar como reemplazo.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Reasignar',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Reasignar [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => '¿Desea proceder a actualizar los registros afectados para que utilicen el nuevo equipo?',
    'LBL_REASSIGN_TABLE_INFO' => 'Actualizando Tabla {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'La operación se ha completado con éxito.',
    'LNK_LIST_TEAM' => 'Equipos',
    'LNK_LIST_TEAMNOTICE' => 'Notificaciones del Equipo',
    'LNK_NEW_TEAM' => 'Nuevo Equipo',
    'LNK_NEW_TEAM_NOTICE' => 'Crear Notificación del Equipo',
    'NTC_DELETE_CONFIRMATION' => '¿Está seguro de que desea eliminar este registro?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => '¿Está seguro de que desea quitar a este usuario del equipo?',
    'LBL_EDITLAYOUT' => 'Editar Diseño' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Permisos de trabajo en equipo',
    'LBL_TBA_CONFIGURATION_DESC' => 'Permitir el acceso de equipo y administrar el acceso por módulo.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Habilitar permisos de trabajo en equipo',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Seleccionar módulos para permitir',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Habilitar permisos de trabajo en equipo le permitirá asignar derechos de acceso específicos para equipos y usuarios en los módulos individuales, a través de la administración de roles.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Deshabilitar los permisos de trabajo en equipo de un módulo anulará cualquier dato asociado con los permisos de trabajo en equipo de ese módulo, incluyendo las definiciones de proceso o los procesos que utilicen la función. Esto incluye los roles que utilicen la opción "equipo de Propietario y Seleccionado" de dicho módulo y cualquier dato de los permisos de trabajo en equipo de los registros del módulo. También recomendamos que utilice Reparación y reconstrucción Rápida para limpiar la caché del sistema luego de deshabilitar los permisos de trabajo en equipo de cualquier módulo.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Advertencia:</strong> Deshabilitar los permisos de trabajo en equipo de un módulo anulará cualquier dato asociado con los permisos de trabajo en equipo de ese módulo, incluyendo las definiciones de proceso o los procesos que utilicen la función. Esto incluye los roles que utilicen la opción "equipo de Propietario y Seleccionado" de dicho módulo y cualquier dato de los permisos de trabajo en equipo de los registros del módulo. También recomendamos que utilice Reparación y reconstrucción Rápida para limpiar la caché del sistema luego de deshabilitar los permisos de trabajo en equipo de cualquier módulo.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Deshabilitar los permisos de trabajo en equipo de un módulo anulará cualquier dato asociado con los permisos de trabajo en equipo de ese módulo, incluyendo las definiciones de proceso o los procesos que utilicen la función. Esto incluye los roles que utilicen la opción "equipo de Propietario y Seleccionado" de dicho módulo y cualquier dato de los permisos de trabajo en equipo de los registros del módulo. También recomendamos que utilice Reparación Rápida y la herramienta de reconstrucción para limpiar la caché del sistema luego de deshabilitar los permisos de trabajo en equipo de cualquier módulo. Si no tiene acceso para utilizar Reparación y Reconstrucción rápida, contacte a un administrador con acceso al menú Reparar.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Advertencia:</strong> Deshabilitar los permisos de trabajo en equipo de un módulo anulará cualquier dato asociado con los permisos de trabajo en equipo de ese módulo, incluyendo las definiciones de proceso o los procesos que utilicen la función. Esto incluye los roles que utilicen la opción "equipo de Propietario y Seleccionado" de dicho módulo y cualquier dato de los permisos de trabajo en equipo de los registros del módulo. También recomendamos que utilice Reparación Rápida y la herramienta de reconstrucción para limpiar la caché del sistema luego de deshabilitar los permisos de trabajo en equipo de cualquier módulo. Si no tiene acceso para utilizar Reparación y Reconstrucción rápida, contacte a un administrador con acceso al menú Reparar.
STR
,
);
