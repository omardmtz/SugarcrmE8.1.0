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
Obtenir una clau secreta dels consumidors i Google mitjançant el registre de la instància de Sugar com una nova aplicació.
<br/><br>Passos per a registrar la instància:
<br/><br/>
<ol>
<li>Vagi al web de desenvolupament de Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Iniciï sessió amb el compte de Google en el qual vol registrar l\'aplicació.</li>
<li>Creï un projecte nou</li>
<li>Indiqui un Nom de projecte i faci clic a Crear.</li>
<li>Un cop creat el projecte, activi Google Drive i Google Contacts API</li>
<li>A la secció API i autenticació > Credencials, creï un ID de client nou</li>
<li>Seleccioni Aplicació Web i faci clic a Configurar</li>
<li>Escrigui un nom de producte i faci clic a Guardar</li>
<li>A la secció URL de redirecció autoritzades escrigui la següent url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Faci clic a crear ID de client</li>
<li>Copiï l\'ID de client i el secret als quadres següents</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID de client',
    'oauth2_client_secret' => 'Secret de client',
);
