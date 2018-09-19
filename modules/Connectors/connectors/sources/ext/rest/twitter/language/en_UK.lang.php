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
										Obtain an API Key and Secret from Twitter by registering your Sugar instance as a new application.<br/><br>Steps to register your instance:<br/><br/>
										<ol>
											<li>Go to the Twitter Developers site: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a>.</li>
											<li>Sign in using the Twitter account under which you would like to register the application.</li>
											<li>Within the registration form, enter a name for the application. This is the name users will see when they authenticate their Twitter accounts from within Sugar.</li>
											<li>Enter a Description.</li>
											<li>Enter an Application Website URL.</li>
											<li>Enter a Callback URL (could be anything since Sugar bypasses this on authentication. Example: Enter your Sugar site URL).</li>
											<li>Accept the Twitter API Terms of Service.</li>
											<li>Click "Create your Twitter application".</li>
											<li>Within the application page, find the API Key and API Secret under the "API Keys" tab. Enter the Key and Secret below.</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'Twitter Username',
    'LBL_ID' => 'Twitter Username',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'API Key',
    'oauth_consumer_secret' => 'API Secret',
);

?>
