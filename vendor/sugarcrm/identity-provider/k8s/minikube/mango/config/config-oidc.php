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

$sugar_config['site_url'] = 'http://behat-tests-mango-oidc.idm-ns';
$sugar_config['verify_client_ip'] = false;
$sugar_config['passwordsetting']['SystemGeneratedPasswordON'] = '0';

$sugar_config['idm_mode']['clientId'] = 'mangoOIDCClientId';
$sugar_config['idm_mode']['clientSecret'] = 'mangoOIDCClientSecret';
$sugar_config['idm_mode']['stsUrl'] = 'http://sts.sugarcrm.local'; // or just http://sts.namespace
$sugar_config['idm_mode']['idpUrl'] = 'http://login.sugarcrm.local'; // or just http://idp.namespace
$sugar_config['idm_mode']['stsKeySetId'] = 'mangoOIDCKeySet';
$sugar_config['idm_mode']['http_client']['retry_count'] = 0;
$sugar_config['idm_mode']['http_client']['delay_strategy'] = 'linear';
$sugar_config['idm_mode']['tid'] = 'srn:cloud:idp:eu:0000000001:tenant';
$sugar_config['idm_mode']['crmOAuthScope'] = 'https://apis.sugarcrm.com/auth/crm';
$sugar_config['idm_mode']['requestedOAuthScopes'] = [
    'offline',
    'https://apis.sugarcrm.com/auth/crm',
    'profile',
    'email',
    'address',
    'phone',
];
$sugar_config['idm_mode']['cloudConsoleUrl'] = 'http://console.sugarcrm.local';
$sugar_config['idm_mode']['cloudConsoleRoutes']['passwordManagement'] = 'password-management';
$sugar_config['idm_mode']['cloudConsoleRoutes']['userCreate'] = 'user-create';
$sugar_config['idm_mode']['cloudConsoleRoutes']['userProfile'] = 'user-profile';
$sugar_config['idm_mode']['cloudConsoleRoutes']['forgotPassword'] = 'forgot-password';
