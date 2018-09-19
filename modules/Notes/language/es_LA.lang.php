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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Tablero de Lista de Notas',

    'ERR_DELETE_RECORD' => 'Debe especificar un número de registro para eliminar la cuenta.',
    'LBL_ACCOUNT_ID' => 'ID de la Cuenta:',
    'LBL_CASE_ID' => 'ID del Caso:',
    'LBL_CLOSE' => 'Cerrar:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID de Contacto:',
    'LBL_CONTACT_NAME' => 'Contacto:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notas',
    'LBL_DESCRIPTION' => 'Nota',
    'LBL_EMAIL_ADDRESS' => 'Dirección de Correo Electrónico:',
    'LBL_EMAIL_ATTACHMENT' => 'Archivo Adjunto de Correo Electrónico:',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Archivo Adjunto de Correo Electrónico Para',
    'LBL_FILE_MIME_TYPE' => 'Tipo MIME',
    'LBL_FILE_EXTENSION' => 'Extensión del Archivo',
    'LBL_FILE_SOURCE' => 'Fuente del Archivo',
    'LBL_FILE_SIZE' => 'Tamaño del Archivo',
    'LBL_FILE_URL' => 'URL de Archivo',
    'LBL_FILENAME' => 'Archivo adjunto:',
    'LBL_LEAD_ID' => 'ID del Cliente Potencial:',
    'LBL_LIST_CONTACT_NAME' => 'Contacto',
    'LBL_LIST_DATE_MODIFIED' => 'Última Modificación',
    'LBL_LIST_FILENAME' => 'Archivo adjunto',
    'LBL_LIST_FORM_TITLE' => 'Lista de Notas',
    'LBL_LIST_RELATED_TO' => 'Relacionado con',
    'LBL_LIST_SUBJECT' => 'Asunto',
    'LBL_LIST_STATUS' => 'Estado',
    'LBL_LIST_CONTACT' => 'Contacto',
    'LBL_MODULE_NAME' => 'Notas',
    'LBL_MODULE_NAME_SINGULAR' => 'Nota',
    'LBL_MODULE_TITLE' => 'Notas: Inicio',
    'LBL_NEW_FORM_TITLE' => 'Nota Nueva o Añadir Archivo Adjunto',
    'LBL_NEW_FORM_BTN' => 'Agregar una Nota',
    'LBL_NOTE_STATUS' => 'Nota',
    'LBL_NOTE_SUBJECT' => 'Asunto:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notas y Archivos Adjuntos',
    'LBL_NOTE' => 'Nota:',
    'LBL_OPPORTUNITY_ID' => 'ID de la Oportunidad:',
    'LBL_PARENT_ID' => 'ID del registro principal:',
    'LBL_PARENT_TYPE' => 'Tipo de Registro Padre',
    'LBL_EMAIL_TYPE' => 'Tipo de Correo Electrónico',
    'LBL_EMAIL_ID' => 'ID de Correo Electrónico',
    'LBL_PHONE' => 'Teléfono:',
    'LBL_PORTAL_FLAG' => '¿Mostrar en el Portal?',
    'LBL_EMBED_FLAG' => '¿Incluir en Correo Electrónico?',
    'LBL_PRODUCT_ID' => 'ID del Producto:',
    'LBL_QUOTE_ID' => 'ID de la Cotización:',
    'LBL_RELATED_TO' => 'Relacionado con:',
    'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de Notas',
    'LBL_STATUS' => 'Estado',
    'LBL_SUBJECT' => 'Asunto:',
    'LNK_IMPORT_NOTES' => 'Importar Notas',
    'LNK_NEW_NOTE' => 'Crear nota o archivo adjunto',
    'LNK_NOTE_LIST' => 'Ver Notas',
    'LBL_MEMBER_OF' => 'Miembro de:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuario Asignado',
    'LBL_OC_FILE_NOTICE' => 'Por favor, inicie la sesión en el servidor para ver el archivo',
    'LBL_REMOVING_ATTACHMENT' => 'Eliminar archivo adjunto...',
    'ERR_REMOVING_ATTACHMENT' => 'Error al eliminar archivo adjunto...',
    'LBL_CREATED_BY' => 'Creado por',
    'LBL_MODIFIED_BY' => 'Modificado Por',
    'LBL_SEND_ANYWAYS' => 'Este correo no tiene asunto. ¿Enviar/guardar de todas formas?',
    'LBL_LIST_EDIT_BUTTON' => 'Editar',
    'LBL_ACTIVITIES_REPORTS' => 'Informe de Actividades',
    'LBL_PANEL_DETAILS' => 'Detalles',
    'LBL_NOTE_INFORMATION' => 'Reseña General',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mis Notas',
    'LBL_EDITLAYOUT' => 'Editar Diseño' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Nombre',
    'LBL_LAST_NAME' => 'Apellido',
    'LBL_EXPORT_PARENT_TYPE' => 'Relacionado con el Módulo',
    'LBL_EXPORT_PARENT_ID' => 'Relacionado con el ID',
    'LBL_DATE_ENTERED' => 'Fecha de creación',
    'LBL_DATE_MODIFIED' => 'Fecha de modificación',
    'LBL_DELETED' => 'Eliminada',
    'LBL_REVENUELINEITEMS' => 'Artículos de Línea de Ganancia',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Ver Artículos',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'El módulo {{plural_module_name}} consiste en un individuo {{plural_module_name}} que contiene texto o un archivo adjunto pertinente al registro relacionado.


-Edite los campos de este registro, haga clic en un campo individual o en el botón Modificar. 
- Vea o modifique enlaces a otros registros en los subpaneles moviendo el panel inferior izquierdo para "Vista de datos". 
- Haga y vea los comentarios de los usuarios y el cambio en el registro de la historia{{activitystream_singular_module}} moviendo el panel inferior izquierdo para "Últimas acciones". 
- Siga o ponga como favorito este registro utilizando los iconos a la derecha del nombre del registro. 
- Las acciones adicionales se encuentran disponibles en el menú Acciones desplegables a la derecha del botón Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Para crear un {{module_name}}:
1. Proporcione valores para los campos según se desee. 
  - Los campos marcados como "Obligatorio" deben ser completados antes de guardar. 
  - Haga clic en "Mostrar más" para exponer campos adicionales si es necesario. 
2. Haga clic en "Guardar" para finalizar el nuevo registro y volver a la página anterior.',
);
