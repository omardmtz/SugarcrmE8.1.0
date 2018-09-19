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
Obtenga una Clave API y una clave Secreta desde Google registrando su instancia de Sugar como aplicación nueva.
<br/><br>Pasos para registrar su instancia
<br/><br/>
<ol>
<li>Vaya al sitio de Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Inicie sesión utilizando la cuenta de Google bajo la cual desea registrar la aplicación.</li>
<li>Cree un proyecto nuevo</li>
<li>Introduzca un Nombre de Proyecto y haga clic en crear.</li>Una vez que el proyecto haya sido creado, habilite las API de Google Drive y Google Contacts</li>
<li>Bajo la Sección APIs & Auth > Credenciales cree un nuedo id de cliente </li>
<li>Selección Aplicación Web y haga clic en Configurar pantalla de consentimiento</li>
<li>Ingrese un nombre de producto y haga clic en Guardar</li>
<li>En la sección URIs de redirección Autorizadas, ingrese la siguiente url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Haga clic en crear id de cliente</li>
<li>Copie el id de cliente y código secreto en los recuadros a continuación</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID de cliente',
    'oauth2_client_secret' => 'Código secreto de cliente',
);
