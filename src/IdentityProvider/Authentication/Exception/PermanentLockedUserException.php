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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception;

use Symfony\Component\Security\Core\Exception\LockedException;

/**
 * Exception is using for permanent locked users
 */
class PermanentLockedUserException extends LockedException
{
    /**
     * ask user to do some action for unlock their account
     * @var string
     */
    protected $waitingErrorMessage;

    /**
     * return message
     * @return string
     */
    public function getWaitingErrorMessage()
    {
        return $this->waitingErrorMessage;
    }

    /**
     * set message
     * @param $message
     */
    public function setWaitingErrorMessage($message)
    {
        $this->waitingErrorMessage = $message;
    }
}
