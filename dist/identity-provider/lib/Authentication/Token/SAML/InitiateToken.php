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
 * Class InitiateToken
 *
 * Token which used to initiate SAML authentication process.
 *
 * @package Sugarcrm\IdentityProvider\Authentication\Token
 */
class InitiateToken extends AbstractToken implements ActionTokenInterface
{
    /**
     * @inheritDoc
     */
    public function getCredentials()
    {
        // there is no any credentials when we initiating SAML authentication.
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getAction()
    {
        return ActionTokenInterface::LOGIN_ACTION;
    }
}
