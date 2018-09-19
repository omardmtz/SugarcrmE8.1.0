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

namespace Sugarcrm\IdentityProvider\Authentication\Token\SAML;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Token which is used to consume SAML Response from IdP.
 */
class IdpLogoutToken extends AbstractToken implements ActionTokenInterface
{
    /**
     * SAML Request as plain base64 encoded string.
     * In SAML it is user's credentials for authentication.
     *
     * @var string
     */
    protected $samlRequest;

    /**
     * @inheritDoc
     */
    public function __construct($samlRequest, array $roles = [])
    {
        $this->samlRequest = $samlRequest;
        parent::__construct($roles);
    }

    /**
     * SAMLRequest is a user's credentials for authentication.
     */
    public function getCredentials()
    {
        return $this->samlRequest;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return ActionTokenInterface::LOGOUT_ACTION;
    }
}
