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

namespace Sugarcrm\IdentityProvider\Authentication\UserMapping;

use Sugarcrm\IdentityProvider\Authentication\User;

/**
 * Interface MappingInterface.
 * Used for maintaining mapping of User attributes from third-party IdPs to App.
 *
 * @package Sugarcrm\IdentityProvider\Authentication\UserMapping
 */
interface MappingInterface
{
    /**
     * Map IdP's data to App User attributes.
     * The result contains 'name_id' as a main user identifier and other attributes.
     *
     * @param mixed $response
     * @return array
     */
    public function map($response);

    /**
     * Get pair ['field' => 'id-field', 'value' => 'id-value'] that can be used to search for User.
     *
     * @param mixed $response
     * @return array
     */
    public function mapIdentity($response);

    /**
     * Get SP identity value
     *
     * @param \Sugarcrm\IdentityProvider\Authentication\User
     *
     * @return string
     */
    public function getIdentityValue(User $user);
}
