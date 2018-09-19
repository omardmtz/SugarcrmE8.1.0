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

namespace Sugarcrm\IdentityProvider\Authentication\User;

use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\EmptyFieldException;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\EmptyIdentifierException;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidIdentifier\IdentifierInvalidFormatException;
use Sugarcrm\IdentityProvider\Authentication\UserProvider\LocalUserProvider;
use Sugarcrm\IdentityProvider\Authentication\Provider\Providers;
use Etechnika\IdnaConvert\IdnaConvert;

/**
 * Class SAMLUserChecker
 *
 * This class performs post authentication checking for SAML user.
 *
 * @package Sugarcrm\IdentityProvider\Authentication\User
 */
class SAMLUserChecker extends UserChecker
{
    /**
     * @var LocalUserProvider
     */
    protected $localUserProvider;

    /**
     * SAML provider configuration.
     * @var array
     */
    protected $config;

    public function __construct(LocalUserProvider $localUserProvider, array $config)
    {
        $this->localUserProvider = $localUserProvider;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {
        $value = $user->getAttribute('identityValue');
        $field = $user->getAttribute('identityField');
        $this->validateIdentifier($field, $value);

        try {
            $localUser = $this->localUserProvider->loadUserByFieldAndProvider($value, Providers::SAML);
        } catch (UsernameNotFoundException $e) {
            if (empty($this->config['sp']['provisionUser'])) {
                throw $e;
            }
            $localUser = $this->localUserProvider->createUser(
                $value,
                Providers::SAML,
                $user->getAttribute('attributes')
            );
        }
        $user->setLocalUser($localUser);

        parent::checkPostAuth($user);
    }

    /**
     * Validation Identifier
     *
     * @param string $field
     * @param string $nameIdentifier
     * @throws EmptyFieldException
     * @throws EmptyIdentifierException
     * @throws IdentifierInvalidFormatException
     */
    protected function validateIdentifier($field, $nameIdentifier)
    {
        if ('' == $field) {
            throw new EmptyFieldException('Empty field name of identifier');
        }
        if ('' == $nameIdentifier) {
            throw new EmptyIdentifierException('Empty identifier');
        }
        if ('email' == $field && !filter_var(IdnaConvert::encodeString($nameIdentifier), FILTER_VALIDATE_EMAIL)) {
            throw new IdentifierInvalidFormatException('Invalid format of nameIdentifier email expected');
        }
    }
}
