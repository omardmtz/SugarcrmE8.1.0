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

namespace Sugarcrm\IdentityProvider\Authentication\UserProvider;

use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Rhumsaa\Uuid\Uuid;

/**
 * Class UserProvider.
 * Class to load user entity from local database.
 */
class LocalUserProvider implements UserProviderInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var string
     */
    private $tenantId;

    /**
     * UserProvider constructor.
     *
     * @param Connection $db
     * @param string $tenantId
     */
    public function __construct(Connection $db, $tenantId)
    {
        $this->db = $db;
        $this->tenantId = $tenantId;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $row = $this->getUserData($username, Providers::LOCAL);
        if (!$row) {
            throw new UsernameNotFoundException();
        }

        return new User($row['identity_value'], $row['password_hash'], $row);
    }

    /**
     * Find and load User by identity-value and provider code.
     *
     * @param string $value identity-value to to search User against
     * @param string $provider code of the provider user originates from
     * @return User
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByFieldAndProvider($value, $provider)
    {
        $row = $this->getUserData($value, $provider);
        if (!$row) {
            throw new UsernameNotFoundException();
        }

        return new User($row['identity_value'], $row['password_hash'], $row);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!($user instanceof User)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $userData = $this->getUserData($user->getUsername(), Providers::LOCAL);

        return new User($userData['identity_value'], $userData['password_hash'], $userData);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }

    /**
     * Create User inside local database.
     *
     * @param string $value identity-value for a User in provider
     * @param string $provider code of the provider user originates from
     * @param array $data all additional User attributes
     * @return User
     *
     * @throws DBALException if SQL-insert was incorrect.
     */
    public function createUser($value, $provider, $data = [])
    {
        $newUserId = (string)Uuid::uuid4();
        $oidcAttributesKeys = array_flip(User::OIDC_ATTRIBUTES);
        $userData = [
            'id' => $newUserId,
            'create_time' => date("Y-m-d H:i:s"),
            'modify_time' => date("Y-m-d H:i:s"),
            'status' => User::STATUS_ACTIVE,
            'tenant_id' => $this->tenantId,
            'attributes' => json_encode(array_intersect_key($data, $oidcAttributesKeys)),
            'custom_attributes' => json_encode(array_diff_key($data, $oidcAttributesKeys)),
        ];
        $userProviderData = [
            'tenant_id' => $this->tenantId,
            'user_id' => $newUserId,
            'provider_code' => $provider,
            'identity_value' => (string)$value,
        ];
        try {
            $this->db->transactional(function ($connection) use ($userData, $userProviderData) {
                $connection->insert('users', $userData);
                $connection->insert('user_providers', $userProviderData);
            });
        } catch (DBALException $e) {
            throw $e;
        }

        return new User($value, null, $userData);
    }

    /**
     * Returns user attributes from database.
     *
     * @param string $value identity-value to to search User against
     * @param string $providerCode code of the provider user came from
     * @return array|null
     */
    protected function getUserData($value, $providerCode)
    {
        $qb = $this->db->createQueryBuilder()
            ->select(
                'users.id,
                 user_providers.identity_value,
                 users.password_hash,
                 users.status,
                 users.create_time,
                 users.modify_time,
                 users.attributes,
                 users.custom_attributes'
            )
            ->from('users')
            ->innerJoin(
                'users',
                'user_providers',
                'user_providers',
                'user_providers.user_id = users.id'
            )
            ->andWhere('users.tenant_id = :tenant_id')
            ->andWhere('users.status = :user_status')
            ->andWhere('user_providers.identity_value = :value')
            ->andWhere('user_providers.provider_code = :provider')
            ->setMaxResults(1)
            ->setParameters([
                ':value' => (string)$value,
                ':tenant_id' => $this->tenantId,
                ':provider' => (string)$providerCode,
                ':user_status' => User::STATUS_ACTIVE,
            ]);

        $row = $qb->execute()->fetch(\PDO::FETCH_ASSOC);

        if (empty($row)) {
            return null;
        }

        $row['attributes'] = json_decode($row['attributes'], true);
        $row['custom_attributes'] = json_decode($row['custom_attributes'], true);
        return $row;
    }
}
