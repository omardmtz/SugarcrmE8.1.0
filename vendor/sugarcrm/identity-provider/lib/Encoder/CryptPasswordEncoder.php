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

namespace Sugarcrm\IdentityProvider\Encoder;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Encoder implementing php's crypt() hashing.
 */
class CryptPasswordEncoder extends BasePasswordEncoder
{
    private $algorithm;
    private $iterations;

    /**
     * Allowed algorithms
     * @var array
     */
    private $algoList = [
        'CRYPT_SHA512',
        'CRYPT_SHA256',
    ];

    const DEFAULT_ITERATIONS = 5000;

    /**
     * Constructor.
     *
     * @param string $algorithm          The digest algorithm to use
     * @param int    $iterations         The number of iterations to use to stretch the password hash
     */
    public function __construct($algorithm = 'CRYPT_SHA512', $iterations = self::DEFAULT_ITERATIONS)
    {
        $this->algorithm = $algorithm;
        $this->iterations = $iterations;
    }

    public function encodePassword($raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        if (!in_array($this->algorithm, $this->algoList, true)) {
            throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }

        return crypt($raw, $this->getSalt($salt));
    }

    /**
     * Constructs proper salt including algo number and rounds
     *
     * @param string $salt
     *
     * @return string
     */
    protected function getSalt($salt)
    {
        return sprintf('$%d$rounds=%d$%s',
            $this->getAlgoNumber(),
            $this->iterations,
            $salt
        );
    }

    /**
     * Get algorithm number
     *
     * @return integer
     */
    protected function getAlgoNumber()
    {
        return $this->algorithm === 'CRYPT_SHA512' ? 6 : 5;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $encoded An encoded password
     * @param string $raw     A raw password
     * @param string $salt    Salt parameter is ignored for SHA-2 as it's stored directly in the hash
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return !$this->isPasswordTooLong($raw) && password_verify($raw, $encoded);
    }
}
