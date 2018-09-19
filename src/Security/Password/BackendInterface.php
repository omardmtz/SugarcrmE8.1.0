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

namespace Sugarcrm\Sugarcrm\Security\Password;

/**
 *
 * Hash backend interface
 *
 * Each backend can implement its own `verify` logic, however it is encouraged
 * to implement backends which can use `password_verify` directly. This allows
 * switching from one backend to another without the need to reset the user's
 * passwords. Combined with the rehashing functionality the active backend
 * can advise to perform a rehash.
 *
 * SugarCRM will rehash automatically for both regular and portal users unless
 * otherwise configured after every succesful user login.
 *
 * Backends which cannot use `password_verify` will require a user password
 * reset when switching to or from that backend before being able to login.
 *
 */
interface BackendInterface
{
    /**
     * Set algorithm
     * @param string $algo
     */
    public function setAlgo($algo);

    /**
     * Set options
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Verify password against given hash
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public function verify($password, $hash);

    /**
     * Create hash for given password
     * @param string $password
     * @return string
     */
    public function hash($password);

    /**
     * Verify if given has needs to be rehashed to
     * comply with current password settings.
     * @param unknown $hash
     */
    public function needsRehash($hash);
}
