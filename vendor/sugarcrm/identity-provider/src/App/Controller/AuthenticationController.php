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
use Sugarcrm\IdentityProvider\App\Authentication\OpenId\StandardClaims;
use Sugarcrm\IdentityProvider\App\Provider\TenantConfigInitializer;
use Sugarcrm\IdentityProvider\Authentication\User;
use Sugarcrm\IdentityProvider\Srn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Controller check user credentials.
 * Class AuthenticationController
 */
class AuthenticationController
{
    /**
     * Checks credentials.
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate(Application $app, Request $request)
    {
        $data = [
            'user_name' => $request->get('user_name'),
            'password' => $request->get('password'),
            'tenant' => $request->get('tid'),
        ];

        $app->getLogger()->debug('Validation auth user request', [
            'data' => $data,
            'tags' => ['IdM.rest.authentication'],
        ]);
        $constraint = new Assert\Collection([
            'tenant' => [new Assert\NotBlank(['message' => 'Field tid is required.'])],
            'user_name' => [new Assert\NotBlank(['message' => 'Field user_name is required.'])],
            'password' => [new Assert\NotBlank(['message' => 'Field password is required.'])],
        ]);
        $violations = $app->getValidatorService()->validate($data, $constraint);
        if (count($violations) > 0) {
            $errors = array_map(function (ConstraintViolation $violation) {
                return $violation->getMessage();
            }, iterator_to_array($violations));
            $app->getLogger()->debug('Invalid request with errors', [
                'errors' => $errors,
                'tags' => ['IdM.rest.authentication'],
            ]);
            return $this->getUnauthorizedResponse(implode(' ', $errors));
        }
        try {
            $initializer = new TenantConfigInitializer($app);
            $initializer->__invoke($request);

            $token = $app->getUsernamePasswordTokenFactory(
                $data['user_name'],
                $data['password']
            )->createAuthenticationToken();
            $app->getLogger()->info('Authentication token for user:{user_name}', [
                'user_name' => $token->getUsername(),
                'tags' => ['IdM.rest.authentication'],
            ]);
            $token = $app->getAuthManagerService()->authenticate($token);
            $tenantSrn = Srn\Converter::fromString($request->getSession()->get('tenant'));
            /** @var User $localUser */
            $localUser = $token->getUser()->getLocalUser();
            $userIdentity = $localUser->getAttribute('id');
            $userSrn = $app->getSrnManager()->createUserSrn($tenantSrn->getTenantId(), $userIdentity);

            $claims = (new StandardClaims())->getUserClaims($localUser);
            $claims['tid'] = Srn\Converter::toString($tenantSrn);

            $result = [
                'status' => 'success',
                'user' => [
                    'sub' => Srn\Converter::toString($userSrn),
                    'id_ext' => $claims,
                ],
            ];
            return new JsonResponse($result);
        } catch (BadCredentialsException $e) {
            $app->getLogger()->notice('Bad credentials occurred for user:{user_name}', [
                'user_name' => $token->getUsername(),
                'tags' => ['IdM.rest.authentication'],
            ]);
            return $this->getUnauthorizedResponse('Invalid credentials');
        } catch (AuthenticationException $e) {
            $app->getLogger()->warning('Authentication Exception occurred for user:{user_name}', [
                'user_name' => $token->getUsername(),
                'exception' => $e,
                'tags' => ['IdM.rest.authentication'],
            ]);
            return $this->getUnauthorizedResponse($e->getMessage());
        } catch (\Exception $e) {
            $app->getLogger()->error('Exception occurred for user:{user_name}', [
                'user_name' => $data['user_name'],
                'exception' => $e,
                'tags' => ['IdM.rest.authentication'],
            ]);
            return $this->getUnauthorizedResponse('APP ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Create response if user credentials are invalid.
     * @param string $message
     * @return JsonResponse
     */
    protected function getUnauthorizedResponse($message)
    {
        $result = [
            'status' => 'error',
            'error' => $message,
        ];
        $response = new JsonResponse($result, Response::HTTP_UNAUTHORIZED);
        return $response;
    }
}
