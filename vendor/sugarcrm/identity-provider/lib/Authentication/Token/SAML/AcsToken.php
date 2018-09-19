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
 * Class AcsToken
 *
 * Token which is used to consume SAML Response from IdP.
 *
 * @package Sugarcrm\IdentityProvider\Authentication\Token
 */
class AcsToken extends AbstractToken implements ActionTokenInterface
{
    /**
     * SAML Response as plain base64 encoded string.
     *
     * @var string
     */
    private $samlResponse;

    /**
     * @inheritDoc
     */
    public function __construct($samlResponse, array $roles = [])
    {
        $this->samlResponse = $samlResponse;
        parent::__construct($roles);
    }

    /**
     * @inheritDoc
     */
    public function getCredentials()
    {
        return $this->samlResponse;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return ActionTokenInterface::LOGIN_ACTION;
    }
}
