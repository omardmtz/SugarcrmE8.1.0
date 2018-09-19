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

namespace Sugarcrm\Sugarcrm\Security\Password\Backend;

use Sugarcrm\Sugarcrm\Security\Password\Salt;
use Sugarcrm\Sugarcrm\Security\Password\SaltConsumerInterface;
use Sugarcrm\Sugarcrm\Security\Password\BackendInterface;
use Sugarcrm\Sugarcrm\Security\Password\Exception\RuntimeException;

/**
 *
 * SHA-2 backend
 *
 * This class implements SHA-2 password hashing support using the lower level
 * crypt function. Both SHA-256 and SHA-512 are supported. The following
 * $sugar_config directives are required to be set to make use of SHA-2:
 *
 *  $sugar_config['passwordHash']['backend'] = 'sha2'
 *  $sugar_config['passwordHash']['algo'] = 'CRYPT_SHA256' | 'CRYPT_SHA512'
 *
 *  (*) CRYPT_SHA256 is the default
 *
 * Optionally the SHA-2 rounds can be configured:
 *
 *  $sugar_config['passwordHash']['options']['rounds'] = 5000
 *
 * @see http://php.net/manual/en/function.crypt.php
 *
 */
class Sha2 implements BackendInterface, SaltConsumerInterface
{
    /**
     * @var Salt
     */
    protected $salt;

    /**
     * Selected algorithm
     * @var string
     */
    protected $algo;

    /**
     * Hashing options
     * @var array
     */
    protected $options = array();

    /**
     * Allowed algorithms
     * @var array
     */
    protected $algoList = array(
        'CRYPT_SHA512',
        'CRYPT_SHA256',
    );

    /**
     * Ctor
     */
    public function __construct()
    {
        $this->setAlgo('CRYPT_SHA256');
    }

    /**
     * {@inheritdoc}
     */
    public function setSalt(Salt $salt)
    {
        $this->salt = $salt;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlgo($algo)
    {
        if ($this->isAlgoAvailable($algo)) {
            $this->algo = $algo;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function verify($password, $hash)
    {
        // We can rely directly on password_verify here as it is compatible
        // with any crypt supported hash and it contains safe time attack
        // string comparison functionality. For pre PHP 5.5 the compat library
        // will take care of this as well.
        return password_verify($password, $hash);
    }

    /**
     * {@inheritdoc}
     */
    public function hash($password)
    {
        return crypt($password, $this->generateSalt());
    }

    /**
     * {@inheritdoc}
     */
    public function needsRehash($hash)
    {
        $regex = sprintf(
            '#^\$%d\$rounds=%d\$[A-Za-z0-9+/]{16}\$[./A-Za-z0-9]+$#D',
            $this->getAlgoNumber(),
            $this->getRounds()
        );

        if (preg_match($regex, $hash) === 1) {
            return false;
        }

        return true;
    }

    /**
     * Check if given algorithm is available
     * @param string $algo
     * @return boolean
     */
    protected function isAlgoAvailable($algo)
    {
        if (in_array($algo, $this->algoList)) {

            // Because of https://bugs.php.net/bug.php?id=67827 checking for
            // the constants may cause issues on certain platforms. Lets
            // assume for now the administrator knows what he is doing.

            // return defined($algo) && constant($algo);

            return true;
        }
        return false;
    }

    /**
     * Generate salt
     * @return string
     * @throws RuntimeException
     */
    protected function generateSalt()
    {
        return sprintf('$%d$rounds=%d$%s',
            $this->getAlgoNumber(),
            $this->getRounds(),
            $this->salt->generate(16)
        );
    }

    /**
     * Get algorithm number
     * @return integer
     */
    protected function getAlgoNumber()
    {
        return $this->algo === 'CRYPT_SHA512' ? 6 : 5;
    }

    /**
     * Get rounds setting
     * @return integer
     */
    protected function getRounds()
    {
        return (int) isset($this->options['rounds']) ? $this->options['rounds'] : 5000;
    }
}
