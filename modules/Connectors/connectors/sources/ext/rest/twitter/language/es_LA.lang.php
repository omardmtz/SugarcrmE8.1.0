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
										Obtenga una clave API y un código secreto de Twitter al registrar su instancia Sugar como una aplicación nueva.<br/><br>Pasos para registrar su instancia:<br/><br/>
										<ol>
											<li>Vaya al sitio de los desarrolladores de Twitter: <a href=\'http://dev.twitter.com/apps/new\' target=\'_blank\'>http://dev.twitter.com/apps/new</a>.</li>
											<li>Inicie sesión con la cuenta de Twitter en la cual le gustaría registrar la aplicación.</li>
											<li>En el formulario de registro, escriba un nombre para la aplicación. Será el nombre que verán los usuarios cuando autentiquen sus cuentas de Twitter desde Sugar.</li>
											<li>Escriba una Descripción.</li>
											<li>Escriba una URL para el sitio web de la aplicación.</li>
											<li>Escriba una URL de Devolución de llamada (podría ser cualquiera ya que Sugar la omite durante la autenticación. Ejemplo: Escriba la URL del sitio Sugar).</li>
											<li>Acepte los Términos de Servicio API de Twitter.</li>
											<li>Haga clic en "Crear su aplicación Twitter".</li>
											<li>Dentro de la página de la aplicación, encuentre la Clave API y el código secreto API en la pestaña "Claves API". Ingrese la clave y el código secreto a continuación.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nombre de Usuario de Twitter',
    'LBL_ID' => 'Nombre de Usuario de Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Clave API',
    'oauth_consumer_secret' => 'Código secreto API',
);

?>
