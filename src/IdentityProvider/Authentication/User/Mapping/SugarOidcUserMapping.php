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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User\Mapping;

use Sugarcrm\IdentityProvider\Authentication\UserMapping\MappingInterface;
use Sugarcrm\IdentityProvider\Authentication\User as IdmUser;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Srn\Converter;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class SugarOidcUserMapping implements MappingInterface
{
    const OIDC_USER_STATUS_ACTIVE = 0;
    const OIDC_USER_STATUS_INACTIVE = 1;

    protected $userMapping = [
        'user_name' => 'preferred_username',
        'first_name' => 'given_name',
        'last_name' => 'family_name',
        'phone_work' => 'phone_number',
        'email' => 'email',
    ];

    protected $addressMapping = [
        'address_street' => 'street_address',
        'address_city' => 'locality',
        'address_state' => 'region',
        'address_country' => 'country',
        'address_postalcode' => 'postal_code',
    ];

    /**
     * Map OIDC response to sugar user fields
     * @param array $response
     * @return array
     */
    public function map($response)
    {
        if (empty($response) || !is_array($response)) {
            return [];
        }

        $userData = ['status' => $this->getUserStatus($response)];

        foreach ($this->userMapping as $mangoKey => $oidcKey) {
            $userData[$mangoKey] = $this->getAttribute($response, $oidcKey);
        }
        foreach ($this->addressMapping as $mangoKey => $oidcKey) {
            $userData[$mangoKey] = $this->getAddressAttribute($response, $oidcKey);
        }
        return array_filter($userData, function ($value) {
            return !is_null($value);
        });
    }

    /**
     * @inheritDoc
     * @throws UsernameNotFoundException
     */
    public function mapIdentity($response)
    {
        if (!is_array($response) || empty($response['sub'])) {
            throw new UsernameNotFoundException('User not found in response');
        }

        return [
            'field' => 'id',
            'value' => $this->getUserIdFromSrn($response['sub']),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getIdentityValue(IdmUser $user)
    {
        return $this->getUserIdFromSrn($user->getSrn());
    }

    /**
     * get user id from srn
     * @param string $srn
     * @return string
     * @throws UsernameNotFoundException
     */
    protected function getUserIdFromSrn($srn)
    {
        $userSrn = Converter::fromString($srn);
        $userResource = $userSrn->getResource();
        if (empty($userResource) || $userResource[0] != 'user' || empty($userResource[1])) {
            throw new UsernameNotFoundException('User not found in SRN');
        }
        return $userResource[1];
    }

    /**
     * get user attribute
     * @param array $response
     * @param string $name
     * @return mixed
     */
    protected function getAttribute(array $response, $name)
    {
        return isset($response[$name]) ? $response[$name] : null;
    }

    /**
     * get address value from token ID extension
     * @param array $response
     * @param string $name
     * @return null
     */
    protected function getAddressAttribute(array $response, $name)
    {
        return !empty($response['address'][$name]) ? $response['address'][$name] : null;
    }

    /**
     * return user status
     * @param array $response
     * @return null|string
     */
    protected function getUserStatus(array $response)
    {
        $status = $this->getAttribute($response, 'status');
        if (is_null($status)) {
            return null;
        }
        return (int) $status == self::OIDC_USER_STATUS_ACTIVE
            ? User::USER_STATUS_ACTIVE
            : User::USER_STATUS_INACTIVE;
    }
}
