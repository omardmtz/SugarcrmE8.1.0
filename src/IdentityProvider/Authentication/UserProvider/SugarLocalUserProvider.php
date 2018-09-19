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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\UserProvider;

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\InactiveUserException;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Exception\InvalidUserException;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SugarLocalUserProvider implements UserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->getUser($username);
    }

    /**
     * Get user by field value.
     *
     * @param string $value
     * @param string $field
     * @return User
     */
    public function loadUserByField($value, $field)
    {
        return $this->getUser($value, $field);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!($user instanceof User)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->getUser($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }

    /**
     * Search and return mango base user.
     * Search can be performed by any User field; 'user_name' by default.
     *
     * @param string $nameIdentifier Value to search by.
     * @param string $field Field name to search by.
     * @return User
     */
    protected function getUser($nameIdentifier, $field = 'user_name')
    {
        global $current_user;

        /** @var \User $sugarUser */
        $sugarUser = $this->createUserBean();

        $isUserSet = false;
        if (empty($current_user) || !$current_user->id) {
            // make sure we use correct user datetime preferences
            $timedate = \TimeDate::getInstance();
            $timedate->setUser($sugarUser);
            $isUserSet = true;
        }

        if ($field == 'email') {
            $sugarUser->retrieve_by_email_address($nameIdentifier);
        } else {
            $query = $this->getSugarQuery();
            $query->select(['id']);
            $query->from($sugarUser);
            $query->where()->equals($field, $nameIdentifier);
            $sugarUserId = $query->getOne();
            $sugarUser->retrieve($sugarUserId, true, false);
        }

        if ($isUserSet) {
            // clear our temporary user just in case
            $timedate->setUser(null);
        }

        if (!$sugarUser->id) {
            throw new UsernameNotFoundException('User was not found by provided name identifier');
        }

        if ($sugarUser->status != User::USER_STATUS_ACTIVE) {
            throw new InactiveUserException('Inactive user');
        }

        if (!empty($sugarUser->is_group) || !empty($sugarUser->portal_only)) {
            throw new InvalidUserException('Portal or group user can not log in.');
        }

        $sugarUser->emailAddress->handleLegacyRetrieve($sugarUser);
        $user = new User($nameIdentifier, $sugarUser->user_hash);
        $user->setSugarUser($sugarUser);

        return $user;
    }

    /**
     * Create Sugar User bean.
     *
     * @param string $username
     * @param array $additionalFields
     * @return \User
     */
    public function createUser($username, array $additionalFields = [])
    {
        $sugarUser = $this->createUserBean();
        $sugarUser->populateFromRow(array_merge($additionalFields, ['user_name' => $username]));

        $sugarUser->new_with_id = isset($additionalFields['id']);

        $sugarUser->save();

        if (isset($additionalFields['email'])) {
            $sugarUser->emailAddress->addAddress($additionalFields['email'], true);
            $sugarUser->emailAddress->save($sugarUser->id, $sugarUser->module_dir);
        }

        return $sugarUser;
    }

    /**
     * Instantiate Sugar User bean.
     *
     * @return \User|\SugarBean
     */
    protected function createUserBean()
    {
        return \BeanFactory::getBean('Users');
    }

    /**
     * Get Sugar Query.
     *
     * @return \SugarQuery
     */
    protected function getSugarQuery()
    {
        return new \SugarQuery();
    }
}
