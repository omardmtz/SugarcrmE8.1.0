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
* Description:
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
* Reserved. Contributor(s): contact@synolia.com - www.synolia.com
* *******************************************************************************/


$connector_strings = array (
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1">
								<tr>
									<td valign="top" width="35%" class="dataLabel">
										Obtenga una clave y un número secreto en Twitter registrando la instancia Sugar como una nueva solicitud.<br/><br>Pasos para registrar la instancia:<br/><br/>
										<ol>
											<li>Vaya al sitio de desarrolladores de Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Inicie sesión con la cuenta de Twitter que desea utilizar para registrar la aplicación.</li>
											<li>En el formulario de registro, escriba un nombre para la aplicación. Este será el nombre que los usuarios verán cuando autentiquen sus cuentas de Twitter desde Sugar.</li>
											<li>Introduzca una descripción.</li>
											<li>Introduzca una URL del sitio web de aplicaciones.</li>
											<li>Introduzca una URL para devolver la llamada (puede ser cualquier cosa, puesto que Sugar omite esto al realizar la autenticación. Ejemplo: Introduzca la URL de su sitio de Sugar).</li>
											<li>Acepte las condiciones de servicio de la API de Twitter.</li>
											<li>Haga clic en "Crear su aplicación de Twitter".</li>
											<li>Dentro de la página de la aplicación, busque la clave API y el código secreto API en la pestaña "Claves API". Introduzca la clave y el código secreto a continuación.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nombre de Usuario Twitter',
    'LBL_ID' => 'Nombre de Usuario Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Clave API',
    'oauth_consumer_secret' => 'Código secreto API',
);

?>
