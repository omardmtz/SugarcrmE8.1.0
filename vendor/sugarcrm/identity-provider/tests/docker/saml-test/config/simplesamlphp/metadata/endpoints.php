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

$endpoints = [
    'logoutFlowWithRedirectBinding' => [
        'acs' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Logout',
    ],

    'samlSameWindowRedirect' => [
        'acs' => 'http://behat-tests-mango-saml-same-window/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-same-window/index.php?module=Users&action=Logout',
    ],

    'samlSameWindowRedirectNoUserProvision' => [
        'acs' =>
            'http://behat-tests-mango-saml-same-window-no-user-provision/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-same-window-no-user-provision/index.php?module=Users&action=Logout',
    ],

    'loginErrorFlowWithoutAnySigned' => [
        'acs' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Logout',
    ],
    'loginFlowWithSignedResponse' => [
        'acs' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Logout',
    ],
    'loginFlowWithSignedResponseAndAssertion' => [
        'acs' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Logout',
    ],
    'loginFlowWithSignedResponseAndEncryptedAssertion' => [
        'acs' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Logout',
    ],
    'logoutFlowWithPostBindingSignedResponse' => [
        'acs' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Authenticate',
        'slo' => 'http://behat-tests-mango-saml-base/index.php?module=Users&action=Logout',
    ],
];