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

namespace Sugarcrm\IdentityProvider\Saml2\Request;

use OneLogin_Saml2_Settings;
use Sugarcrm\IdentityProvider\Authentication\Exception\ConfigurationException;

/**
 * Class to support POST binding request.
 *
 * Class LogoutPostRequest
 * @package Sugarcrm\IdentityProvider\Saml2
 */
class LogoutPostRequest extends \OneLogin_Saml2_LogoutRequest
{
    /**
     * @inheritdoc
     */
    public function __construct(
        OneLogin_Saml2_Settings $settings,
        $request = null,
        $nameId = null,
        $sessionIndex = null
    ) {
        parent::__construct($settings, $request, $nameId, $sessionIndex);

        $spData = $settings->getSPData();
        $securityData = $settings->getSecurityData();
        if (!empty($securityData['logoutRequestSigned']) && !empty($securityData['signatureAlgorithm'])) {
            if (empty($spData['privateKey']) || empty($spData['x509cert'])) {
                throw new ConfigurationException('Private key and x509cert should be defined');
            }
            $this->_logoutRequest = \OneLogin_Saml2_Utils::addSign(
                $this->_logoutRequest,
                $spData['privateKey'],
                $spData['x509cert'],
                $securityData['signatureAlgorithm']
            );
        }
    }
}
