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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Obtenga una clave de API y un código secreto de Google mediante el registro de la instancia de Sugar como una nueva aplicación.
<br/><br>Pasos para registrar su instancia:
<br/><br/>
<ol>
<li>Vaya al sitio de desarrolladores de Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Inicie sesión con la cuenta de Google que desea utilizar para registrar la aplicación.</li>
<li>Cree un proyecto nuevo</li>
<li>Introduzca un nombre de proyecto y haga clic en crear.</li>
<li>Cuando el proyecto se haya creado, habilite API de Google Drive y Google Contacts</li>
<li>En API y Autorizaciones > Sección de credenciales cree un id nuevo de cliente</li>
<li>Seleccione la aplicación web y haga clic en Configurar pantalla de concepto</li>
<li>Introduzca un nombre de producto y haga clic en Guardar</li>
<li>En la sección de Redirección de URI autorizada introduzca la siguiente url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Haga clic en crear id de cliente</li>
<li>Copie el id de cliente y el código secreto de cliente en las siguientes casillas</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID de cliente',
    'oauth2_client_secret' => 'Código secreto de cliente',
);
