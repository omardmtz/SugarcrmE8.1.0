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

namespace Sugarcrm\IdentityProvider\App\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Psr\Log\LoggerInterface;

class BearerAuthentication
{
    const SCOPE_DELIMITER = ' ';

    /**
     * @var OAuth2Service
     */
    protected $oAuth2Service;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $requiredScope;

    public function __construct(oAuth2Service $oAuth2Service, $requiredScope, LoggerInterface $logger)
    {
        $this->oAuth2Service = $oAuth2Service;
        $this->requiredScope = $requiredScope;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticateClient(Request $request)
    {
        try {
            $accessToken = $this->getToken($request);
            $result = $this->oAuth2Service->introspectToken($accessToken);
            $this->checkIsClientAllowed($result);
        } catch (AuthenticationException $exception) {
            $this->logger->warning('Authentication Exception occurred on client Authentication', [
                'exception' => $exception,
                'tags' => ['IdM.Bearer.authentication'],
            ]);
            return $this->getUnauthorizedResponse();
        }
    }

    /**
     * Return Bearer in authentication header if it present.
     *
     * @param Request $request
     * @return string
     * @throws AuthenticationException
     */
    protected function getToken(Request $request)
    {
        if (preg_match('~^Bearer (.*)$~i', $request->headers->get('Authorization'), $matches)) {
            $token = $matches[1];
        } else {
            throw new AuthenticationException('Empty Authorization token received');
        }
        return $token;
    }

    /**
     * Check result of introspection
     * @param array $result
     * @throws AuthenticationException
     */
    protected function checkIsClientAllowed(array $result)
    {
        if (!array_key_exists('scope', $result)) {
            throw new AuthenticationException('Field scope in result not exists');
        }

        if (!in_array($this->requiredScope, explode(self::SCOPE_DELIMITER, $result['scope']))) {
            throw new AuthenticationException('Invalid scope');
        }
    }

    /**
     * Create response if user credentials are invalid.
     * @return JsonResponse
     */
    protected function getUnauthorizedResponse()
    {
        $result = [
            'status' => 'error',
            'error' => 'The request could not be authorized',
        ];
        $response = new JsonResponse($result, Response::HTTP_UNAUTHORIZED);
        return $response;
    }
}
