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

use InvalidArgumentException;

use Sugarcrm\IdentityProvider\Authentication\Exception\ConfigurationException;
use Sugarcrm\IdentityProvider\Authentication\Exception\SAMLRequestException;
use Sugarcrm\IdentityProvider\Authentication\Exception\SAMLResponseException;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ConsumeLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\AcsToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ActionTokenInterface;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\IdpLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\InitiateLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\InitiateToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ResultToken;
use Sugarcrm\IdentityProvider\Saml2\Builder\ResponseBuilder;
use Sugarcrm\IdentityProvider\Authentication\UserMapping\MappingInterface;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

/**
 * Class SAMLAuthenticationProvider
 *
 * Class that can be used to authenticate via SAML against different IdPs.
 *
 * @package Sugarcrm\IdentityProvider\Authentication\Provider
 */
class SAMLAuthenticationProvider implements AuthenticationProviderInterface
{
    const REQUEST_ID_KEY = 'requestId';

    /**
     * @var SAMLAuthServiceProvider
     */
    protected $authServiceProvider;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * @var UserCheckerInterface
     */
    protected $userChecker;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var MappingInterface
     */
    protected $mapper;

    /**
     * List of handlers that can be used to handle tokens.
     * Actually, they correspond to steps of SAML authentication flow.
     *
     * @var array
     */
    protected $handlers = [
        InitiateToken::class => 'initiateLogin',
        AcsToken::class => 'consume',
        InitiateLogoutToken::class => 'initiateLogout',
        ConsumeLogoutToken::class => 'consumeLogout',
        IdpLogoutToken::class => 'idpLogout',
    ];

    /**
     * SAMLAuthenticationProvider constructor.
     *
     * @param array $settings
     * @param UserProviderInterface $userProvider
     * @param UserCheckerInterface $userChecker
     * @param SessionInterface $session
     * @param MappingInterface $mapper
     */
    public function __construct(
        array $settings,
        UserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        SessionInterface $session,
        MappingInterface $mapper
    ) {
        $this->authServiceProvider = new SAML\AuthServiceProvider($settings);
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->session = $session;
        $this->mapper = $mapper;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(TokenInterface $token)
    {
        $handlerMethod = null;
        foreach ($this->handlers as $tokenClass => $handler) {
            if ($token instanceof $tokenClass) {
                $handlerMethod = $handler;
                break;
            }
        }
        if (!$handlerMethod) {
            throw new AuthenticationServiceException('There is no authentication handler for ' . get_class($token));
        }

        try {
            return $this->{$handlerMethod}($token);
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * Starts SAML logout process.
     *
     * @param InitiateLogoutToken $token
     * @return InitiateLogoutToken
     */
    protected function initiateLogout(InitiateLogoutToken $token)
    {
        $returnTo = $token->hasAttribute('returnTo') ? $token->getAttribute('returnTo') : null;
        $sessionIndex = $token->hasAttribute('sessionIndex') ? $token->getAttribute('sessionIndex') : null;
        $nameId = $token->hasAttribute('user') ? $this->mapper->getIdentityValue($token->getAttribute('user')) : null;
        $parameters = [];
        try {
            $result = $this->authServiceProvider
                                ->getAuthService($token)
                                ->logout($returnTo, $parameters, $nameId, $sessionIndex);
        } catch (\InvalidArgumentException $e) {
            throw new ConfigurationException($e->getMessage(), $e->getCode());
        } catch (\OneLogin_Saml2_Error $e) {
            throw new SAMLRequestException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }

        $resultToken = new ResultToken($token->getCredentials(), $token->getAttributes());
        $resultToken->setAttribute('url', $result->getUrl());
        $resultToken->setAttribute('method', $result->getMethod());
        $resultToken->setAttribute('parameters', $result->getAttributes());
        return $resultToken;
    }

    /**
     * Starts SAML authentication process.
     *
     * @param InitiateToken $token
     * @return InitiateToken
     *
     * @throws InvalidArgumentException When there is no authentication service configured for identity provider.
     */
    protected function initiateLogin(InitiateToken $token)
    {
        /**
         * $returnTo URL where to redirect user after authentication. It will be stored in RelayState attribute.
         * $parameters Extra parameters to be added to the GET request.
         * $forceAuthentication When true the SAML AuthNRequest will set ForceAuthn to 'true'.
         * $isPassive When true the SAML AuthNRequest will set Ispassive attribute to 'true'.
         * $stay True if we want to return the url string without actual redirect, False to perform redirect
         */
        $returnTo = $token->hasAttribute('returnTo') ? $token->getAttribute('returnTo') : null;
        $parameters = [];
        $forceAuthentication = [];
        $isPassive = false;
        $stay = true;

        try {
            $authService = $this->authServiceProvider->getAuthService($token);
            $result = $authService->login($returnTo, $parameters, $forceAuthentication, $isPassive, $stay);
        } catch (\InvalidArgumentException $e) {
            throw new ConfigurationException($e->getMessage(), $e->getCode());
        } catch (\OneLogin_Saml2_Error $e) {
            throw new SAMLRequestException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new AuthenticationException($e->getMessage(), $e->getCode());
        }
        $resultToken = new ResultToken($token->getCredentials(), $token->getAttributes());
        $resultToken->setAttribute('url', $result->getUrl());
        $resultToken->setAttribute('method', $result->getMethod());
        $resultToken->setAttribute('parameters', $result->getAttributes());
        if ($authService->isRequestIdValidationNeeded()) {
            $this->session->set(self::REQUEST_ID_KEY, $authService->getLastRequestID());
        }
        return $resultToken;
    }

    /**
     * Process Logout Response from Identity Provider.
     *
     * @param ConsumeLogoutToken $token
     * @return ConsumeLogoutToken
     *
     * @throws AuthenticationException When SAML Response is invalid.
     */
    protected function consumeLogout(ConsumeLogoutToken $token)
    {
        try {
            $authService = $this->authServiceProvider->getAuthService($token);
        } catch (\InvalidArgumentException $e) {
            throw new ConfigurationException($e->getMessage(), $e->getCode());
        }

        try {
            $authService->processServiceSLO($token->getCredentials());
        } catch (\OneLogin_Saml2_Error $e) {
            throw new SAMLResponseException($e->getMessage());
        }

        $resultToken = new ResultToken($token->getCredentials(), $token->getAttributes());
        $resultToken->setAuthenticated(false);
        return $resultToken;
    }

    /**
     * Process Logout Request from Identity Provider.
     *
     * @param IdpLogoutToken $token
     * @return IdpLogoutToken
     *
     * @throws ConfigurationException When any auth service configuration error
     * @throws SAMLRequestException When any errors with SAML request
     */
    protected function idpLogout(IdpLogoutToken $token)
    {
        try {
            $authService = $this->authServiceProvider->getAuthService($token);
        } catch (\InvalidArgumentException $e) {
            throw new ConfigurationException($e->getMessage(), $e->getCode());
        }

        try {
            $relayState = $token->hasAttribute('RelayState') ? $token->getAttribute('RelayState') : null;
            $result = $authService->processIdpSLO($token->getCredentials(), $relayState);
        } catch (\OneLogin_Saml2_Error $e) {
            throw new SAMLRequestException($e->getMessage());
        }
        $resultToken = new ResultToken($token->getCredentials(), $token->getAttributes());
        $resultToken->setAttribute('url', $result->getUrl());
        $resultToken->setAttribute('method', $result->getMethod());
        $resultToken->setAttribute('parameters', $result->getAttributes());
        $resultToken->setAuthenticated(false);
        return $resultToken;
    }

    /**
     * Process ACS response send from Identity Provider.
     *
     * @param AcsToken $token
     * @return AcsToken
     *
     * @throws AuthenticationException When SAML Response is invalid.
     * @throws InvalidArgumentException When there is no authentication service configured for identity provider.
     * @throws Exception When SAML Response could not be processed.
     * @throws \OneLogin_Saml2_Error If any settings parameter is invalid.
     */
    protected function consume(AcsToken $token)
    {
        $requestId = null;
        try {
            $authService = $this->authServiceProvider->getAuthService($token);
        } catch (\InvalidArgumentException $e) {
            throw new ConfigurationException($e->getMessage(), $e->getCode());
        }
        $response = $this->buildLoginResponse($token, $authService);
        if ($authService->isRequestIdValidationNeeded()) {
            $requestId = $this->session->remove(self::REQUEST_ID_KEY);
        }

        if (!$response) {
            throw new SAMLResponseException('Invalid SAML Response');
        }

        if (!$response->isValid($requestId)) {
            throw new SAMLResponseException($response->getError());
        }

        $resultToken = new ResultToken($token->getCredentials(), $token->getAttributes());

        $user = $this->userProvider->loadUserByUsername($response->getNameId());
        $identityMap = $this->mapper->mapIdentity($response);

        $user->setAttribute('provision', $authService->isUserProvisionNeeded());
        $user->setAttribute('identityField', $identityMap['field']);
        $user->setAttribute('identityValue', $identityMap['value']);
        $user->setAttribute('attributes', $this->mapper->map($response));

        // This is done for bwc-support of Sugar ability to use custom user-provision function.
        // @todo: Remove it for standalone IdM service. See BR-5065
        $user->setAttribute('XMLResponse', $response->getXMLDocument());

        $this->userChecker->checkPostAuth($user);

        $resultToken->setUser($user);

        $resultToken->setAttribute('IdPSessionIndex', $response->getSessionIndex());
        $resultToken->setAuthenticated(true);

        return $resultToken;
    }

    /**
     * @inheritDoc
     */
    public function supports(TokenInterface $token)
    {
        foreach (array_keys($this->handlers) as $tokenClass) {
            if (($token instanceof $tokenClass) && ($token instanceof ActionTokenInterface)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param AcsToken $token
     * @param $authService
     * @return \OneLogin_Saml2_Response
     */
    protected function buildLoginResponse(AcsToken $token, $authService)
    {
        return (new ResponseBuilder($authService))->buildLoginResponse($token->getCredentials());
    }
}
