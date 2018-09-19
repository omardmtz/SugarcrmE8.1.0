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

namespace Sugarcrm\IdentityProvider\Authentication\Provider;

use Sugarcrm\IdentityProvider\Authentication\Token\MixedUsernamePasswordToken;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\ProviderNotFoundException;

/**
 * Supports a several type of authentication at once.
 * Goes through list of tokens and try to authenticate this tokens.
 *
 * Class MixedAuthenticationProvider
 * @package Sugarcrm\IdentityProvider\App\Provider
 */
class MixedAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var AuthenticationProviderInterface[] | null
     */
    protected $providers = [];

    /**
     * @var string
     */
    protected $providerKey;

    /**
     * MixedAuthenticationProvider constructor.
     * @param array $providers
     * @param string $providerKey
     */
    public function __construct(array $providers, $providerKey)
    {
        $this->providers = $providers;
        $this->providerKey = $providerKey;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(TokenInterface $token)
    {
        $lastException = null;
        $tokens = $token->getTokens();
        foreach ($tokens as $authToken) {
            foreach ($this->providers as $provider) {
                if (!$provider->supports($authToken)) {
                    continue;
                }

                try {
                    return $provider->authenticate($authToken);
                } catch (\Exception $e) {
                    $lastException = $e;
                }
            }
        }

        if ($lastException) {
            throw $lastException;
        }

        throw new ProviderNotFoundException(
            sprintf('No Authentication Provider found for token of class "%s".', get_class($token))
        );
    }

    /**
     * @inheritdoc
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof MixedUsernamePasswordToken && $this->providerKey == $token->getProviderKey();
    }
}
