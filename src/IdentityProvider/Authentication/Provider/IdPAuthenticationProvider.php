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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;

use Sugarcrm\IdentityProvider\Authentication\UserMapping\MappingInterface;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\User\SugarOIDCUserChecker;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\UserProvider\SugarOIDCUserProvider;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token\IdpUsernamePasswordToken;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\OAuth2\Client\Provider\IdmProvider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\ProviderNotFoundException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @internal
 * Class IdPAuthenticationProvider
 * Provides remote authenticate on Identity Provider.
 */
class IdPAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var IdmProvider
     */
    protected $oAuthProvider = null;

    /**
     * @var SugarOIDCUserProvider
     */
    protected $userProvider;

    /**
     * @var SugarOIDCUserChecker
     */
    protected $userChecker;

    /**
     * @var MappingInterface
     */
    protected $userMapping;

    /**
     * @var string
     */
    protected $providerKey;

    /**
     * OIDCAuthenticationProvider constructor.
     * @param AbstractProvider $oAuthProvider
     * @param UserProviderInterface $userProvider
     * @param UserCheckerInterface $userChecker
     * @param MappingInterface $userMapping
     * @param string $providerKey
     */
    public function __construct(
        AbstractProvider $oAuthProvider,
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        MappingInterface $userMapping,
        $providerKey
    ) {
        $this->oAuthProvider = $oAuthProvider;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->userMapping = $userMapping;
        $this->providerKey = $providerKey;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            throw new ProviderNotFoundException();
        }
        try {
            $authData = $this->oAuthProvider->remoteIdpAuthenticate($token->getUsername(), $token->getCredentials(), $token->getTenant());

            if (empty($authData['status']) || $authData['status'] !== 'success' || empty($authData['user']['sub'])) {
                throw new AuthenticationException('IdP authentication failed');
            }

            $user = $this->userProvider->loadUserBySrn($authData['user']['sub']);

            // TODO change one attribute to separate attributes!!!
            // TODO don't use oidc_data and oidc_identify for update existed sugar user
            $userData = [];
            if (!empty($authData['user']['id_ext'])) {
                $userData = $this->userMapping->map($authData['user']['id_ext']);
            }
            $user->setAttribute('oidc_data', $userData);
            $user->setAttribute('oidc_identify', $this->userMapping->mapIdentity($authData['user']));

            $this->userChecker->checkPostAuth($user);

            $authenticatedToken = new UsernamePasswordToken(
                $user,
                $token->getCredentials(),
                $token->getProviderKey(),
                $token->getRoles()
            );
            $authenticatedToken->setAttributes($token->getAttributes());

            return $authenticatedToken;
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof IdpUsernamePasswordToken && $this->providerKey === $token->getProviderKey();
    }
}
