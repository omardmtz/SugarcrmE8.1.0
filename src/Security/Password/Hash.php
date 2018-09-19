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

namespace Sugarcrm\Sugarcrm\Security\Password;

/**
 *
 * Password hashing main class
 *
 */
class Hash
{
    const DEFAULT_BACKEND = 'native';

    /**
     * @var Hash
     */
    protected static $instance;

    /**
     * Password hash backend
     * @var BackendInterface
     */
    protected $backend;

    /**
     * Allow md5 legacy password support for password verification.
     * This setting allows to verify users using an old md5 hash but
     * will not result in new hashes to be created through md5.
     *
     * Its not recommended to use this legacy approach as plain md5
     * hashes are not secure. Also note that this validation is
     * sensitive to time based attacks.
     *
     * @var boolean
     */
    protected $allowLegacy = false;

    /**
     * Enable/disable backend rehash check
     * @var boolean
     */
    protected $rehash = true;

    /**
     * Ctor
     * @param BackendInterface $hash
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
    }

    /**
     * Overload method for hash backend
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array(array($this->backend, $method), $arguments);
    }

    /**
     * Get instance based on \SugarConfig settings
     *
     *  $sugar_config['passwordHash']['allowLegacy'] = true/false
     *      Whether or not to allow legacy md5 database hashes,
     *      defaults to false
     *
     *  $sugar_config['passwordHash']['rehash'] = true/false
     *      Enable or disable the rehash capability for the selected
     *      backend, defaults to true
     *
     *  $sugar_config['passwordHash']['backend'] = 'native' | 'sha2'
     *      Select the backend, defaults to 'native'
     *
     *  $sugar_config['passwordHash']['algo']
     *      Hash algorithm to be used, see selected backend
     *
     *  $sugar_config['passwordHash']['options']
     *      Backend configuration options, see selected backend
     *
     * @return Hash
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {

            $config = \SugarConfig::getInstance();

            $backend = $config->get('passwordHash.backend', self::DEFAULT_BACKEND);
            $algo = $config->get('passwordHash.algo', null);
            $options = $config->get('passwordHash.options', array());

            self::$instance = $instance = new self(self::getHashBackend($backend, $algo, $options));

            $instance->setAllowLegacy($config->get('passwordHash.allowLegacy', false));
            $instance->setRehash($config->get('passwordHash.rehash', true));
        }
        return self::$instance;
    }

    /**
     * Create password hash backend object. Use self::getInstance unless
     * you know what you are doing.
     *
     * @param string $class Backend short class name
     * @param string $algo
     * @param array $options
     * @return BackendInterface
     */
    public static function getHashBackend($class, $algo = null, array $options = array())
    {
        $class = \SugarAutoLoader::customClass('\\Sugarcrm\\Sugarcrm\\Security\\Password\\Backend\\' . ucfirst($class));

        /* @var $backend BackendInterface */
        $backend = new $class();
        $backend->setOptions($options);

        if ($algo !== null) {
            $backend->setAlgo($algo);
        }

        // inject salt generator if required
        if ($backend instanceof SaltConsumerInterface) {
            $backend->setSalt(new Salt());
        }

        return $backend;
    }

    /**
     * Set allow legacy flag
     * @param boolean $toggle
     */
    public function setAllowLegacy($toggle)
    {
        $this->allowLegacy = (bool) $toggle;
    }

    /**
     * Set rehash flag
     * @param boolean $toggle
     */
    public function setRehash($toggle)
    {
        $this->rehash = (bool) $toggle;
    }

    /**
     * Validate given password against hash
     * @param string $password
     * @return boolean
     */
    public function verify($password, $hash)
    {
        return $this->verifyMd5(md5($password), $hash);
    }

    /**
     * Validate given md5 encoded password against hash
     * @param string $password MD5 encoded password
     * @return boolean
     */
    public function verifyMd5($password, $hash)
    {
        if (empty($password) || empty($hash)) {
            return false;
        }

        // using lower case encoded password
        $password = strtolower($password);
        if ($this->allowLegacy && $this->isLegacyHash($hash)) {
            return $this->verifyLegacy($password, $hash);
        }

        return $this->backend->verify($password, $hash);
    }

    /**
     * Create hash for given password
     * @param unknown $password
     */
    public function hash($password)
    {
        // Passwords are always hased first using md5 before passing to the
        // backend. This is for historical reasons and more in specific to
        // be able to support the SOAP api which can take directly md5 hashed
        // passwords.

        return $this->backend->hash(md5($password));
    }

    /**
     * Verify if given hash needs rehashing. If rehash support is not
     * enabled this will always return false regardless of the backend.
     *
     * @param string $hash
     * @return boolean
     */
    public function needsRehash($hash)
    {
        return $this->rehash ? $this->backend->needsRehash($hash) : false;
    }

    /**
     * Check if given hash is a legacy md5 hash
     * @param string $hash
     * @return boolean
     */
    protected function isLegacyHash($hash)
    {
        return $hash[0] !== '$' && strlen($hash) === 32;
    }

    /**
     * Legacy password validation, assuming the password is
     * already md5 encoded.
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    protected function verifyLegacy($password, $hash)
    {
        return strtolower($password) === $hash;
    }
}
