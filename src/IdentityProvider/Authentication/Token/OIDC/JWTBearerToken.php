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

namespace Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Token\OIDC;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Token to support urn:ietf:params:oauth:grant-type:jwt-bearer grant type
 */
class JWTBearerToken extends AbstractToken
{
    const EXPIRE_INTERVAL = 300;

    const DEFAULT_SIGNATURE_ALGORITHM = 'RS256';

    /**
     * Sugar User identity field
     * @var string
     */
    protected $identity;

    /**
     * Tenant SRN
     * @var string
     */
    protected $tenant;

    /**
     * JWTBearerToken constructor.
     * @param string $identity
     * @param string $tenant Tenant SRN
     * @param array $roles
     */
    public function __construct($identity, $tenant, $roles = array())
    {
        $this->identity = $identity;
        $this->tenant = $tenant;
        parent::__construct($roles);
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $privateKeyInfo = $this->getAttribute('privateKey');
        $jwkPrivateKey = JWKFactory::createFromValues($privateKeyInfo);
        $currentTime = $this->getAttribute('iat');
        $claims = [
            'iat' => $currentTime,
            'exp' => $currentTime + static::EXPIRE_INTERVAL,
            'aud' => $this->getAttribute('aud'),
            'sub' => $this->getIdentity(),
            'iss' => $this->getAttribute('iss'),
            'tid' => $this->tenant,
        ];
        return JWSFactory::createJWSToCompactJSON(
            $claims,
            $jwkPrivateKey,
            [
                'kid' => $this->getAttribute('kid'),
                'alg' => isset($privateKeyInfo['alg']) ? $privateKeyInfo['alg'] : static::DEFAULT_SIGNATURE_ALGORITHM,
            ]
        );
    }
}
