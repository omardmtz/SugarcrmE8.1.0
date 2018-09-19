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

namespace Sugarcrm\IdentityProvider\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Mixed token which main contain different UsernamePassword tokens
 * Class MixedUsernamePasswordToken
 * @package Sugarcrm\IdentityProvider\Authentication\Token
 */
class MixedUsernamePasswordToken extends UsernamePasswordToken
{
    /**
     * Authentication chain storage
     * @var array
     */
    protected $mixedTokens = [];

    /**
     * Add token to authentication chain
     * @param UsernamePasswordToken $token
     */
    public function addToken(UsernamePasswordToken $token)
    {
        $this->mixedTokens[] = $token;
    }

    /**
     * Gets all tokens from authentication chain
     * @return array
     */
    public function getTokens()
    {
        return $this->mixedTokens;
    }
}
