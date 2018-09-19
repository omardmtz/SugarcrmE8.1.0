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
Ottenere una chiave API Key e Secret da Google registrando la propria istanza Sugar come nuova applicazione.
<br/><br>Passaggi per registrare la propria istanza:
<br/><br/>
<ol>
<li>Andare al sito degli Sviluppatori Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Accedere usando l\'account Google nel quale si desidera registrare l\'applicazione.</li>
<li>Crea un nuovo progetto</li>
<li>Inserisci un Nome progetto e fai clic su crea.</li>
<li>Dopo aver creato il progetto, attivare gli API Google Drive e Google Contacts</li>
<li>Alla sezione Credenziali API & Auth > creare un nuovo id cliente </li>
<li>Selezionare Applicazione Web, quindi fare clic sulla schermata Configura consenso</li>
<li>Inserire un nome di prodotto, quindi fare clic su Salva</li>
<li>Nella sezione URI di redirect autorizzati inserire il seguente url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Fare clic su crea id cliente</li>
<li>Copiare l\'id cliente e client secret nelle seguenti caselle</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID Cliente',
    'oauth2_client_secret' => 'Client secret',
);
