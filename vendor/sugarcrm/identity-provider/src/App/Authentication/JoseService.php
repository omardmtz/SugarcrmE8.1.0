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

use Assert\AssertionFailedException;
use Jose\Factory\CheckerManagerFactory;
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
use Jose\Loader;
use Jose\Object\JWSInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class JoseService
 * @package Sugarcrm\IdentityProvider\App\Authentication
 */
class JoseService
{
    /**
     * Token expiration interval in seconds
     */
    const TOKEN_EXPIRE_INTERVAL = 600;
    /**
     * Decode and verify JWT token string. Returns object with token info.
     *
     * @param $challenge
     * @param array $publicKey
     * @return \Jose\Object\JWSInterface
     *
     * @throws \InvalidArgumentException
     * @throws AssertionFailedException
     */
    public function decodeJWT($challenge, array $publicKey)
    {
        $jwkPublicKey = JWKFactory::createFromValues($publicKey);
        $jwtLoader = new Loader();
        $recipientIndex = [];
        $consentToken = $jwtLoader->loadAndVerifySignatureUsingKey(
            $challenge,
            $jwkPublicKey,
            ['RS256', 'RS384', 'RS512', 'HS256', 'HS384', 'HS512'],
            $recipientIndex
        );
        $checker = CheckerManagerFactory::createClaimCheckerManager(['iat', 'nbf', 'exp'], ['crit']);
        $checker->checkJWS($consentToken, 0);
        return $consentToken;
    }

    /**
     * Create JWT token string based on consentToken and user parameters.
     *
     * @param JWSInterface $consentToken
     * @param array $privateKey
     * @param TokenInterface $authenticatedUser
     * @return string
     *
     * @throws \InvalidArgumentException
     * @throws AssertionFailedException
     */
    public function createJWT(JWSInterface $consentToken, array $privateKey, TokenInterface $authenticatedUser)
    {
        if (!$authenticatedUser->hasAttribute('srn')) {
            throw new \InvalidArgumentException('Srn not found for user');
        }

        $currentTime = time();
        $tokenSignature = $consentToken->getSignature(0);
        $claims = [
            'iat' => $currentTime,
            'exp' => $currentTime + static::TOKEN_EXPIRE_INTERVAL,
            'aud' => $consentToken->getClaim('aud'),
            'jti' => $consentToken->getClaim('jti'),
            'sub' => $authenticatedUser->getAttribute('srn'),
            'scp' => $consentToken->getClaim('scp'),
            'id_ext' => [],
            'at_ext' => [],
        ];

        $jwkPrivateKey = JWKFactory::createFromValues($privateKey);
        $resultToken = JWSFactory::createJWSToCompactJSON(
            $claims,
            $jwkPrivateKey,
            $tokenSignature->getProtectedHeaders()
        );

        return $resultToken;
    }
}
