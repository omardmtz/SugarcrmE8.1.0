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

namespace Sugarcrm\IdentityProvider\App\Controller;

use Assert\AssertionFailedException;

use Jose\Object\JWSInterface;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentToken;
use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentTokenInterface;
use Sugarcrm\IdentityProvider\Authentication\Consent\ConsentChecker;
use Sugarcrm\IdentityProvider\Authentication\Tenant;
use GuzzleHttp\Exception\RequestException;

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\App\Authentication\JoseService;
use Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service;
use Sugarcrm\IdentityProvider\Authentication\Exception\InvalidScopeException;
use Sugarcrm\IdentityProvider\STS\EndpointInterface;

use Sugarcrm\IdentityProvider\Srn\Converter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Class ConsentController
 * @package Sugarcrm\IdentityProvider\App\Controller
 */
class ConsentController
{
    /**
     * @var JoseService
     */
    protected $joseService;

    /**
     * @var OAuth2Service
     */
    protected $oAuth2Service;

    /**
     * @var Session
     */
    protected $sessionService;

    /**
     * ConsentController constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->joseService = $app['JoseService'];
        $this->oAuth2Service = $app['oAuth2Service'];
        $this->sessionService = $app['session'];
    }

    /**
     * Init consent flow
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function consentInitAction(Application $app, Request $request)
    {
        $consentToken = $app->getConsentRestService()->getToken($request->query->get('consent'));
        if ($consentToken->getTenantSRN()) {
            $this->sessionService->set('tenant', $consentToken->getTenantSRN());
        }
        $this->sessionService->set('consent', $consentToken);
        return $app->redirect($app->getUrlGeneratorService()->generate('loginRender'));
    }

    /**
     * consent confirmation action
     * @param Application $app
     * @param Request $request
     * @throws AuthenticationCredentialsNotFoundException
     * @throws \Twig_Error_Loader  When the template cannot be found
     * @throws \Twig_Error_Syntax  When an error occurred during compilation
     * @throws \Twig_Error_Runtime When an error occurred during rendering
     * @return string
     */
    public function consentConfirmationAction(Application $app, Request $request)
    {
        /** @var ConsentToken $consentToken */
        $consentToken = $this->sessionService->get('consent');

        if (is_null($consentToken)) {
            throw new AuthenticationCredentialsNotFoundException('Consent session not found');
        }

        return $app->getTwigService()->render('consent/confirmation.html.twig', [
            'scope' =>  $consentToken->getScope(),
            'client' => $consentToken->getClientId(),
        ]);
    }

    /**
     * check consent in consent token
     * @param Application $app
     * @param ConsentTokenInterface $token
     * @return bool
     */
    protected function checkConsent(Application $app, ConsentTokenInterface $token)
    {
        $tenant = (new Tenant())->fillFromSRN(Converter::fromString($token->getTenantSRN()));
        $consent = $app->getConsentRepository()->findConsentByClientIdAndTenantId(
            $token->getClientId(),
            $tenant->getId()
        );
        $checker = new ConsentChecker($consent, $token);
        return $checker->check();
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function consentFinishAction(Application $app, Request $request)
    {
        /** @var ConsentToken $consentToken */
        /** @var UsernamePasswordToken $userToken */
        list($consentToken, $userToken) = $this->getConsentAndUserToken();

        $this->oAuth2Service->acceptConsentRequest($consentToken, $userToken);
        return $app->redirect($consentToken->getRedirectUrl());
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function consentCancelAction(Application $app, Request $request)
    {
        /** @var ConsentToken $consentToken */
        list($consentToken, ) = $this->getConsentAndUserToken();

        $this->oAuth2Service->rejectConsentRequest($consentToken->getRequestId(), "No consent");
        return $app->redirect($consentToken->getRedirectUrl());
    }

    /**
     * return array of consent and user token
     * @return array
     */
    protected function getConsentAndUserToken()
    {
        /** @var ConsentToken $consentToken */
        $consentToken = $this->sessionService->get('consent');
        /** @var UsernamePasswordToken $userToken */
        $userToken = $this->sessionService->get('authenticatedUser');

        $this->sessionService->remove('tenant');
        $this->sessionService->remove('consent');
        $this->sessionService->remove('authenticatedUser');

        if (is_null($consentToken)) {
            throw new AuthenticationCredentialsNotFoundException('Consent session not found');
        }

        if (is_null($userToken)) {
            throw new AuthenticationCredentialsNotFoundException('User is not authenticated');
        }
        return [$consentToken, $userToken];
    }
}
