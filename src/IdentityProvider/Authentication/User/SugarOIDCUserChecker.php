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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\UserProvider\SugarLocalUserProvider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

class SugarOIDCUserChecker extends UserChecker
{
    /**
     * Predefined user attributes.
     * @var array
     */
    protected $fixedUserAttributes = [
        'employee_status' => User::USER_EMPLOYEE_STATUS_ACTIVE,
        'status' => User::USER_STATUS_ACTIVE,
        'is_admin' => 0,
        'external_auth_only' => 1,
        'system_generated_password' => 0,
    ];

    /**
     * @var SugarLocalUserProvider
     */
    protected $localUserProvider;

    /**
     * @param SugarLocalUserProvider $localUserProvider
     */
    public function __construct(SugarLocalUserProvider $localUserProvider)
    {
        $this->localUserProvider = $localUserProvider;
    }

    public function checkPostAuth(UserInterface $user)
    {
        $this->loadSugarUser($user);
        parent::checkPostAuth($user);
    }

    /**
     * Find or create Sugar User.
     *
     * @param User $user
     * @throws \Exception
     */
    protected function loadSugarUser(User $user)
    {
        $userAttributes = $user->getAttribute('oidc_data');
        $identify = $user->getAttribute('oidc_identify');

        try {
            $sugarUser = $this->localUserProvider
                ->loadUserByField($identify['value'], $identify['field'])
                ->getSugarUser();
            $this->setUserData($sugarUser, $userAttributes);
        } catch (UsernameNotFoundException $e) {
            $userAttributes = array_merge(
                [$identify['field'] => $identify['value']],
                $this->fixedUserAttributes,
                $userAttributes
            );
            $sugarUser = $this->localUserProvider->createUser($userAttributes['user_name'], $userAttributes);
        }
        $user->setSugarUser($sugarUser);
    }

    /**
     * Compare user data and set changes
     * PopulateFromRow couldn't be used because all non-oidc fields are set to empty
     * @param \User $sugarUser
     * @param array $attributes
     */
    protected function setUserData(\User $sugarUser, array $attributes)
    {
        $isDataChanged = false;
        $email = null;
        if (isset($attributes['email'])) {
            $primaryEmail = $sugarUser->emailAddress->getPrimaryAddress($sugarUser);
            if (strcasecmp($primaryEmail, $attributes['email']) !== 0) {
                $email = $attributes['email'];
            }
            unset($attributes['email']);
        }
        foreach ($attributes as $name => $value) {
            if (isset($sugarUser->$name) && strcasecmp($sugarUser->$name, $value) !== 0) {
                $sugarUser->$name = $value;
                $isDataChanged = true;
            }
        }
        if ($isDataChanged) {
            $sugarUser->save();
        }
        if ($email) {
            $sugarUser->emailAddress->addAddress($email, true);
            $sugarUser->emailAddress->save($sugarUser->id, $sugarUser->module_dir);
        }
    }
}
