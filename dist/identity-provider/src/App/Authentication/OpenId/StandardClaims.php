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

namespace Sugarcrm\IdentityProvider\App\Authentication\OpenId;

use Sugarcrm\IdentityProvider\Authentication\User;

/**
 * OpenId claims converter
 */
class StandardClaims
{
    /**
     * user based claims
     * @var array
     */
    protected $userClaimMapping = [
        'status' => 'status',
        'created_at' => 'create_time',
        'updated_at' => 'modify_time',
    ];

    protected $oidcClaims = [
        'given_name',
        'family_name',
        'middle_name',
        'nickname',
        'email',
        'phone_number',
        'address',
    ];

    /**
     * convert ipd user attributes to openid claims
     * provides only attributes with value
     * @param User $user
     * @return array
     */
    public function getUserClaims(User $user)
    {
        $user = $user->getLocalUser();

        $mappedData = [
            'preferred_username' => $this->getUsername($user),
        ];

        foreach ($this->userClaimMapping as $claimName => $userAttributeName) {
            $mappedData[$claimName] = $user->getAttribute($userAttributeName);
        }
        foreach ($this->oidcClaims as $claimName) {
            $mappedData[$claimName] = $user->getOidcAttribute($claimName);
        }
        return array_filter($mappedData, function ($value) {
            return !is_null($value);
        });
    }

    /**
     * return username
     * @param User $user
     * @return null|string
     */
    protected function getUsername(User $user)
    {
        $username = $user->getUsername();
        return !empty($username) ? $username : $user->getAttribute('identity_value');
    }
}
