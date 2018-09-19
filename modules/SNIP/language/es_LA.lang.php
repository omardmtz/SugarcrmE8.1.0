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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Archivo de Correo Electrónico',
    'LBL_SNIP_SUMMARY' => "Archivo de correo electrónico es un servicio de importación automática que permite a los usuarios importar correos electrónicos a Sugar enviándolos de cualquier correo de cliente o servicio a una dirección de correo electrónico proporcionado por Sugar. Cada instancia de Sugar tiene su propia dirección de correo electrónico única. Para importar mensajes de correo electrónico, el usuario envía a la dirección de correo electrónico proporcionada usando el Para, CC, CCO.El servicio de Archivo de Correo Electrónico va a importar el correo electrónico en la instancia de Sugar.. El servicio de las importaciones del correo electrónico, junto con los archivos adjuntos, imágenes y citas del calendario, crea los registros dentro de la aplicación que se asocian con los registros existentes basados en la coincidencia de direcciones de correo electrónico.<br />    <br><br>Como usuario, cuando veo una cuenta, podré ver todos los mensajes de correo electrónico que se asocian con la Cuenta de base a la dirección de correo electrónico en el registro de cuenta. También podré ver mensajes de correo electrónico que se asocian con los contactos relacionados con la Cuenta.<br />    <br><br>Acepte los términos y haga clic en Activar para empezar a utilizar el servicio. Usted será capaz de desactivar el servicio en cualquier momento. Una vez que el servicio está habilitado, se mostrará la dirección de correo electrónico que se utilizará para el servicio.<br />    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'No se pudo contactar a servicio de archivado de correo electrónico: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Archivo de Correo Electrónico',
    'LBL_DISABLE_SNIP' => 'Inhabilitar',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Solicitud de clave única',
    'LBL_SNIP_USER' => 'Usuario de Archivo de Correo Electrónico',
    'LBL_SNIP_PWD' => 'Contraseña de Archivo de Correo Electrónico',
    'LBL_SNIP_SUGAR_URL' => 'La URL de la instancia de Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'URL del servicio de Archivo de Correo Electrónico',
    'LBL_SNIP_USER_DESC' => 'Usuario de Archivo de Correo Electrónico',
    'LBL_SNIP_KEY_DESC' => 'Clave de Archivo de Correo Electrónico OAuth. Se utiliza para acceder a esta instancia para la importación de mensajes de correo electrónico.',
    'LBL_SNIP_STATUS_OK' => 'Habilitado',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Esta instancia de Sugar se ha conectado al servidor de Archivo de Correo Electrónico',
    'LBL_SNIP_STATUS_ERROR' => 'Error',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Esta instancia tiene un servidor de licencia de Archivo de Correo Electrónico valido, pero el servidor devolvió el siguiente mensaje de error:',
    'LBL_SNIP_STATUS_FAIL' => 'No se puede registrar con el servidor de Archivo de Correo Electrónico',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'El servicio Archivo de Correo Electrónico no está disponible actualmente. O bien el servicio está inactivo o la conexión con la instancia de Sugar falló.',
    'LBL_SNIP_GENERIC_ERROR' => 'El servicio de Archivo de Correo Electrónico  no está disponible actualmente. O bien el servicio está inactivo o la conexión con la instancia de Sugar falló.',

	'LBL_SNIP_STATUS_RESET' => 'No se ejecutan todavía',
	'LBL_SNIP_STATUS_PROBLEM' => 'Problema: %s',
    'LBL_SNIP_NEVER' => "Nunca",
    'LBL_SNIP_STATUS_SUMMARY' => "Facilidad de Sugar en el estado de archivo:",
    'LBL_SNIP_ACCOUNT' => "Cuenta",
    'LBL_SNIP_STATUS' => "Estado",
    'LBL_SNIP_LAST_SUCCESS' => "Último arranque exitoso",
    "LBL_SNIP_DESCRIPTION" => "Archivo de Correo Electrónico es un sistema de correo electrónico de archivo automático",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Que le permite ver correos electrónicos que fueron enviados hacia o desde sus contactos dentro de SugarCRM, sin tener que importar manualmente y vincular los correos electrónicos",
    "LBL_SNIP_PURCHASE_SUMMARY" => "Para utilizar Archivo de Correo Electrónico, usted debe comprar una licencia para la instancia de SugarCRM",
    "LBL_SNIP_PURCHASE" => "Haga clic aquí para comprar",
    'LBL_SNIP_EMAIL' => 'Dirección de Archivo de Correo Electrónico',
    'LBL_SNIP_AGREE' => "Estoy de acuerdo en los términos anteriores y el <a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">acuerdo de privacidad</a>.",
    'LBL_SNIP_PRIVACY' => 'acuerdo de privacidad',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback fracasó',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'El servidor de Archivo de Correo Electrónico no es capaz de establecer una conexión con la instancia de Sugar. Por favor, inténtelo de nuevo o <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">póngase en contacto con atención al cliente</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Activar Archivo de Correo Electrónico',
    'LBL_SNIP_BUTTON_DISABLE' => 'Desactivar Archivo de Correo Electrónico',
    'LBL_SNIP_BUTTON_RETRY' => 'Intente conectarse de nuevo',
    'LBL_SNIP_ERROR_DISABLING' => 'Se produjo un error al intentar comunicarse con el servidor de Archivo de Correo Electrónico, y el servicio no puede ser desactivado',
    'LBL_SNIP_ERROR_ENABLING' => 'Se produjo un error al intentar comunicarse con el servidor de Archivo de Correo Electrónico, y el servicio no puede ser habilitado',
    'LBL_CONTACT_SUPPORT' => 'Por favor, inténtelo de nuevo o póngase en contacto con el soporte de SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Por favor, póngase en contacto con el soporte de SugarCRM para obtener ayuda.',
    'ERROR_BAD_RESULT' => 'Mal resultado devuelto por el servicio',
	'ERROR_NO_CURL' => 'Se requiere extensiones cURL, pero no se ha habilitado',
	'ERROR_REQUEST_FAILED' => 'No se pudo contactar con el servidor',

    'LBL_CANCEL_BUTTON_TITLE' => 'Cancelar',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Este es el estado del servicio de Archivo de Correo Electrónico en su instancia. El estado refleja que la conexión entre el servidor de Archivo de Correo Electrónico y su instancia de Sugar tiene éxito.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'Este es la dirección de correo electrónico de Archivo de Correo Electrónico para enviar a fin de importar mensajes de correo electrónico en Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Esta es la URL del servidor de Archivo de Correo Electrónico. Todas las solicitudes, como habilitar y deshabilitar el servicio de Archivo de Correo Electrónico, será retransmitido a través de esta URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Esta es la URL de servicios web de la instancia de Sugar. El servidor de Archivo de Correo Electrónico se conectará a su servidor a través de esta URL.',
);
