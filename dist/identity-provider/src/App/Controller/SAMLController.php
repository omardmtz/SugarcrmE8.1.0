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

use Sugarcrm\IdentityProvider\App\Application;
use Sugarcrm\IdentityProvider\App\Authentication\AuthProviderManagerBuilder;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\ConsumeLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\AcsToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\IdpLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\InitiateToken;
use Sugarcrm\IdentityProvider\Authentication\Token\SAML\InitiateLogoutToken;
use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Srn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SAMLController
{
    /**
     * @param Application $app Silex application instance.
     * @param Request $request
     * @return string
     */
    public function loginEndPointAction(Application $app, Request $request)
    {
        return $app->getTwigService()->render('saml/status.html.twig', [
            'user_name' => $request->get('user_name'),
            'samlLogout' => true,
            'IdPSessionIndex' => $request->get('IdPSessionIndex'),
            'user_attributes' => $request->get('user_attributes'),
        ]);
    }

    /**
     * @param Application $app Silex application instance.
     * @param Request $request
     * @return string
     */
    public function renderFormAction(Application $app, Request $request)
    {
        return $this->renderLoginForm($app);
    }

    /**
     * @param Application $app Silex application instance.
     * @param Request $request
     * @return string
     */
    public function initAction(Application $app, Request $request)
    {
        $messages = [];

        try {
            $initToken = new InitiateToken();
            if ($request->get('RelayState')) {
                $relayState = $request->get('RelayState');
            } else {
                $relayState =
                    $app->getUrlGeneratorService()->generate('samlLoginEndPoint', [], UrlGenerator::ABSOLUTE_URL);
            }

            $initToken->setAttribute('returnTo', $relayState);
            $token = $app->getAuthManagerService()->authenticate($initToken);
            $url = $token->getAttribute('url');
            if (!empty($url)) {
                return RedirectResponse::create($url);
            } else {
                $messages[] = 'Cannot initiate SAML request';
            }
        } catch (AuthenticationException $e) {
            $messages[] = $e->getMessage();
        }

        return $this->renderLoginForm($app, [
            'messages' => $messages,
        ]);
    }

    /**
     * @param Application $app Silex application instance.
     * @param Request $request
     * @return string
     */
    public function acsAction(Application $app, Request $request)
    {
        $messages = [];

        /** @var Session $sessionService */
        $sessionService = $app['session'];

        try {
            $acsToken = new AcsToken($request->get('SAMLResponse'));
            $token = $app->getAuthManagerService()->authenticate($acsToken);
            if ($token->isAuthenticated()) {
                $tenantSrn = Srn\Converter::fromString($sessionService->get('tenant'));
                $userIdentity = $token->getUser()->getLocalUser()->getAttribute('id');
                $userSrn = $app->getSrnManager()->createUserSrn($tenantSrn->getTenantId(), $userIdentity);
                $token->setAttribute('srn', Srn\Converter::toString($userSrn));
                if ($sessionService->get('consent')) {
                    $sessionService->set('authenticatedUser', $token);
                    return $app->redirect($app->getUrlGeneratorService()->generate('consentConfirmation'));
                }

                $urlQuery = [
                    'user_name' => $token->getUsername(),
                    'IdPSessionIndex' => $token->getAttribute('IdPSessionIndex'),
                    'user_attributes' => $token->getUser()->getAttribute('attributes'),
                ];

                $url = $request->get('RelayState');
                if (!empty($url)) {
                    return RedirectResponse::create($this->extendUrl($url, $urlQuery));
                }

                return RedirectResponse::create($app->getUrlGeneratorService()->generate(
                    'samlLoginEndPoint',
                    $urlQuery
                ));
            }
        } catch (AuthenticationException $e) {
            $messages[] = $e->getMessage();
        }

        return $this->renderLoginForm($app, [
            'messages' => $messages,
        ]);
    }

    /**
     * Logout init action for SAML.
     * @param Application $app
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\Response|static
     */
    public function logoutInitAction(Application $app, Request $request)
    {
        $messages = [];

        try {
            $logoutToken = new InitiateLogoutToken();
            $logoutToken->setAttributes(
                [
                    'sessionIndex' => $request->get('sessionIndex'),
                    'returnTo' => $app->getUrlGeneratorService()
                                      ->generate('samlLogoutEndPoint', [], UrlGenerator::ABSOLUTE_URL),
                ]
            );
            $nameId = $request->get('nameId');
            if ($nameId) {
                $user = new User();
                $user->setAttribute('email', $nameId);
                $logoutToken->setAttribute('user', $user);
            }
            $resultToken = $app->getAuthManagerService()->authenticate($logoutToken);
            switch ($resultToken->getAttribute('method')) {
                case Request::METHOD_POST:
                    return $app->getTwigService()->render('saml/selfSubmitForm.html.twig', [
                        'url' => $resultToken->getAttribute('url'),
                        'method' => $resultToken->getAttribute('method'),
                        'params' => $resultToken->getAttribute('parameters'),
                    ]);
                default:
                    return RedirectResponse::create($resultToken->getAttribute('url'));
            }
        } catch (AuthenticationException $e) {
            $messages[] = $e->getMessage();
        }

        return $this->renderLoginForm($app, [
            'messages' => $messages,
        ]);
    }

    /**
     * SAML logout action handler.
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|static
     * @throws \HttpInvalidParamException
     */
    public function logoutAction(Application $app, Request $request)
    {
        $requestRelayState = $request->get('RelayState');
        if ($request->get('SAMLResponse')) {
            $logoutToken = new ConsumeLogoutToken($request->get('SAMLResponse'));
        } elseif ($request->get('SAMLRequest')) {
            $logoutToken = new IdpLogoutToken($request->get('SAMLRequest'));
            if ($requestRelayState) {
                $logoutToken->setAttribute('RelayState', $requestRelayState);
            }
        } else {
            $messages = ['Invalid SAML logout data'];
            return $this->renderLoginForm($app, ['messages' => $messages]);
        }
        $logoutToken->setAuthenticated(true);

        $resultToken = $app->getAuthManagerService()->authenticate($logoutToken);
        if (!$resultToken->isAuthenticated()) {
            $url = $resultToken->hasAttribute('url') ? $resultToken->getAttribute('url') : $requestRelayState;
            $parameters = $resultToken->hasAttribute('parameters') ? $resultToken->getAttribute('parameters') : [];
            if (!empty($url)) {
                return RedirectResponse::create($this->extendUrl($url, $parameters));
            }
            return RedirectResponse::create($app->getUrlGeneratorService()->generate('samlLogoutEndPoint'));
        }

        $messages = ['Invalid SAML logout data'];
        return $this->renderLoginForm($app, ['messages' => $messages]);
    }

    /**
     * Default SAML logout endpoint page.
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function logoutEndPointAction(Application $app, Request $request)
    {
        return $app->getTwigService()->render('saml/logout.html.twig');
    }

    /**
     * Return SAML metadata
     *
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    public function metadataAction(Application $app, Request $request)
    {
        try {
            $validateError = true;
            if (!empty($app['config']['saml'])) {
                $settings = $this->getSamlSettings($app['config']['saml']);
                $metadata = $settings->getSPMetadata();
                $validateError = (bool) $settings->validateMetadata($metadata);
            }
        } catch (\Exception $e) {
            $validateError = true;
        }

        if (!empty($validateError)) {
            return $app->redirect($app->getUrlGeneratorService()->generate('samlRender'));
        }

        $response = new Response($metadata);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'metadata.xml'
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    /**
     * create OneLogin_Saml2_Settings
     * @param array $config
     * @return \OneLogin_Saml2_Settings
     */
    protected function getSamlSettings(array $config)
    {
        return new \OneLogin_Saml2_Settings($config);
    }

    /**
     * Parses URL to add params into URL query
     * @param string $url
     * @param array $params
     * @return string
     */
    protected function extendUrl($url, array $params)
    {
        if (empty($params)) {
            return $url;
        }

        $urlParts = parse_url($url);

        $newUrl =
            $urlParts['scheme'] . '://'
            . $urlParts['host']
            . (!empty($urlParts['port']) ? ':' . $urlParts['port'] : '')
            . (!empty($urlParts['path']) ? $urlParts['path'] : '')
            . '?' . (!empty($urlParts['query']) ? $urlParts['query'] . '&' : '')
            . http_build_query($params);

        return $newUrl;
    }

    /**
     * @param Application $app
     * @param array $params
     * @return string
     */
    protected function renderLoginForm(Application $app, array $params = [])
    {
        if (empty($app['config']['saml'])) {
            return null;
        }
        return $app->getTwigService()->render(
            'saml/form.html.twig',
            array_merge(
                ['issuer' => $app['config']['saml']['idp']['entityId']],
                $params
            )
        );
    }
}
