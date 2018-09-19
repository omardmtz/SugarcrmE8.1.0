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
										Obțineți API Key și API Secret de la Twitter înregistrând instanța Sugar ca aplicație nouă.<br/><br>Pași pentru înregistrarea instanței:<br/><br/>
										<ol>
											<li>Accesați site-ul pentru dezvoltatori Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Conectați-vă folosind contul Twitter cu care doriți să înregistrați aplicația.</li>
											<li>În formularul de înregistrare, introduceți un nume pentru aplicație. Acesta este numele pe care utilizatorii îl vor vedea când se vor autentifica cu conturile lor Twitter din Sugar.</li>
											<li>Introduceți o Descriere.</li>
											<li>Introduceți o adresă URL pentru site-ul web al aplicației.</li>
											<li>Introduceți o adresă URL de apelare inversă (poate fi oricare, deoarece Sugar o omite la autentificare. Exemplu: Introduceți adresa URL a site-ului dvs. Sugar).</li>
											<li>Acceptați Termenii de utilizare pentru API Twitter.</li>
											<li>Faceți clic pe „Creați aplicația dvs. Twitter”.</li>
											<li>În pagina aplicației, găsiți API Key și API Secret în fila „Chei API”. Introduceți Key și Secret mai jos.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Nume utilizator Twitter',
    'LBL_ID' => 'Nume utilizator Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'Cheie API',
    'oauth_consumer_secret' => 'API Secret',
);

?>
