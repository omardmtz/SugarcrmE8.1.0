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
Obtain a API Key and Secret from Google by registering your Sugar instance as a new application.
<br/> <br>Steps to register your instance:
<br/><br/> <ol>
<li>Go to the Google Developers site: <a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Sign In using the Google account under which you would like to register the application.</li>
<li>Create a new project</li>
<li>Enter a Project Name and click create.</li>
<li>Once the project has been created enable the Google Drive and Google Contacts API</li>
<li>Under the APIs & Auth > Credentials section create a new client id </li>
<li>Select Web Application and click Configure consent screen</li>
<li>Enter in a product name and click Save</li>
<li>Under the Authorised redirect URIs section enter the following url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Click create client id</li>
<li>Copy the client id and client secret into the boxes below</li> 
</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Client ID',
    'oauth2_client_secret' => 'Client secret',
);
