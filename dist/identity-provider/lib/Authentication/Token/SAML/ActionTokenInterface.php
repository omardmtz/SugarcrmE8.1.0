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

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Provides token specific behaviour for action.
 *
 * Interface ActionTokenInterface
 * @package Sugarcrm\IdentityProvider\Authentication\Token\SAML
 */
interface ActionTokenInterface extends TokenInterface
{
    const LOGIN_ACTION = 'login';

    const LOGOUT_ACTION = 'logout';

    /**
     * Token action such as LOGIN_ACTION or LOGOUT_ACTION
     * @return string
     */
    public function getAction();
}
