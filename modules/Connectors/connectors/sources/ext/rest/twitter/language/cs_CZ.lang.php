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
										Získejte zaregistrováním své instance Sugar jako nové aplikace klíč rozhraní API a tajný klíč rozhraní API ze služby Twitter.<br/><br>Kroky registrace vaší instance:<br/><br/>
										<ol>
											<li>Přejděte na stránky pro vývojáře služby Twitter:<a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Přihlaste se pomocí účtu služby Twitter, pod kterým chcete aplikaci zaregistrovat.</li>
											<li>Zadejte do registračního formuláře název aplikace. Jedná se o název, který uvidí uživatelé, když provedou ověření svého účtu služby Twitter z aplikace Sugar.</li>
											<li>Zadejte popis.</li>
											<li>Zadejte adresu URL webu aplikace.</li>
											<li>Zadejte adresu URL zpětného volání (může být jakákoli, protože aplikace Sugar ji během ověřování obchází. Příklad: zadejte adresu URL vaší stránky Sugar).</li>
											<li>Přijměte podmínky rozhraní API služby Twitter.</li>
											<li>Klikněte na položku „Create your Twitter application“ (Vytvořit svou aplikaci služby Twitter).</li>
											<li>Na stránce aplikace najděte klíč rozhraní API a tajný klíč rozhraní API pod záložkou „API Keys“ (Klíče rozhraní API). Klíč a tajný klíč zadejte níže.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Uživatelské jméno pro Twitter',
    'LBL_ID' => 'Uživatelské jméno pro Twitter',
	'company_url' => 'Adresa URL',
    'oauth_consumer_key' => 'Klíč rozhraní API',
    'oauth_consumer_secret' => 'Tajný klíč rozhraní API',
);

?>
