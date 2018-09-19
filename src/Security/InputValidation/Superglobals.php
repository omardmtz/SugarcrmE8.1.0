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

namespace Sugarcrm\Sugarcrm\Security\InputValidation;

use Sugarcrm\Sugarcrm\Security\InputValidation\Exception\SuperglobalException;
use Psr\Log\LoggerInterface;

/**
 *
 * This class is used by the Request class to represent the different
 * superglobals which are considered raw user input. Do not instantiate
 * this class directly but make use of InputValidation instead.
 *
 * The following superglobals are supported:
 *
 *  $_GET
 *  $_POST
 *  $_REQUEST (*)
 *
 * (*) Note that PHP populates the $_REQUEST superglobal automatically based
 * on the request_order directive in php.ini. The default advized value is GP
 * meaning that $_POST will overwrite already existing $_GET parameters.
 *
 * We take measure into our own hands which will enforce GP request_order
 * regardless of the php.ini setting when accessing $_REQUEST parameters
 * using this class.
 *
 * Compatibility Mode:
 *
 * This object has a compatibility layer to leverage the ability to set/access
 * superglobals from SugarCRM's logic. There are parts in our legacy code which
 * rely on this functionality. To be able to transition and refactor properly
 * this object has a compatibility layer which is enabled by default for now.
 * In the future this layer will be removed and it is no longer considered
 * good practice to add/change addition input superglobals with arbitrary
 * values. New development should not rely on this and pass values in a proper
 * way between different part of the applications.
 *
 */
class Superglobals
{
    const GET = 'GET';
    const POST = 'POST';
    const REQUEST = 'REQUEST';

    /**
     * Raw $_GET values
     * @var array
     */
    private $rawGet = array();

    /**
     * Raw $_POST values
     * @var array
     */
    private $rawPost = array();

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Sanitized $_GET values
     * @var array
     */
    private $sanitizedGet = array();

    /**
     * Sanitized $_POST values
     * @var array
     */
    private $sanitizedPost = array();

    /**
     * Sanitized $_REQUEST values
     * @var array
     */
    private $sanitizedRequest = array();

    /**
     * Enable/disable compatibility layer.
     * @var boolean
     */
    private $compat = false;

    /**
     * Ctor
     * @param array $rawGet Initial (raw) key value pairs from $_GET
     * @param array $rawPost Initial (raw) key value pairs from $_POST
     * @param LoggerInterface $logger
     */
    public function __construct(array $rawGet, array $rawPost, LoggerInterface $logger)
    {
        $this->rawGet = $rawGet;
        $this->rawPost = $rawPost;
        $this->logger = $logger;
    }

    /**
     * Enable compatibility mode
     *
     * This method should never be called more than once. This object is a
     * backend object controlled through `Request` which is instantiated using
     * the InputValidation factor which has the proper control in case this
     * is called more than once.
     */
    public function enableCompatMode()
    {
        $this->compat = true;

        // sanitize superglobals using legacy methods
        clean_special_arguments();
        clean_incoming_data();

        // remember the sanitized superglobal values
        $this->sanitizedGet = $_GET;
        $this->sanitizedPost = $_POST;
        $this->sanitizedRequest = $_REQUEST;
    }

    /**
     * Get compatibility mode flag
     * @return boolean
     */
    public function getCompatMode()
    {
        return $this->compat;
    }

    /**
     * Set $_GET value
     * @param string $key
     * @param mixed $value
     */
    public function setRawGet($key, $value)
    {
        $this->rawGet[$key] = $value;
    }

    /**
     * Set $_POST value
     * @param string $key
     * @param mixed $value
     */
    public function setRawPost($key, $value)
    {
        $this->rawPost[$key] = $value;
    }

    /**
     * Check if given raw $_GET parameter is available
     * @param string $key
     * @return boolean
     */
    public function hasRawGet($key)
    {
        return isset($this->rawGet[$key]);
    }

    /**
     * Check if given raw $_POST parameter is available
     * @param string $key
     * @return boolean
     */
    public function hasRawPost($key)
    {
        return isset($this->rawPost[$key]);
    }

    /**
     * Check if given raw $_REQUEST parameter is available
     * @param string $key
     * @return boolean
     */
    public function hasRawRequest($key)
    {
        return $this->hasRawPost($key) ? true : $this->hasRawGet($key);
    }

    /**
     * Check if given $_GET parameter is available
     * @param string $key
     * @return boolean
     */
    public function hasGet($key)
    {
        return $this->compat ? isset($_GET[$key]) : $this->hasRawGet($key);
    }

    /**
     * Check if given $_POST parameter is available
     * @param string $key
     * @return boolean
     */
    public function hasPost($key)
    {
        return $this->compat ? isset($_POST[$key]) : $this->hasRawPost($key);
    }

    /**
     * Check if given $_REQUEST parameter is available
     * @param string $key
     * @return boolean
     */
    public function hasRequest($key)
    {
        return $this->compat ? isset($_REQUEST[$key]) : $this->hasRawRequest($key);
    }

    /**
     * Get raw $_GET value
     * @param string $key Key of the $_GET parameter
     * @param mixed $default Default value to return if key not found
     * @return mixed
     */
    public function getRawGet($key, $default = null)
    {
        return $this->hasRawGet($key) ? $this->rawGet[$key] : $default;
    }

    /**
     * Get raw $_POST value
     * @param string $key Key of the $_POST parameter
     * @param mixed $default Default value to return if key not found
     * @return mixed
     */
    public function getRawPost($key, $default = null)
    {
        return $this->hasRawPost($key) ? $this->rawPost[$key] : $default;
    }

    /**
     * Get raw $_REQUEST value
     * @param string $key Key of the $_REQUEST parameter
     * @param mixed $default Default value to return if key not found
     * @return mixed
     */
    public function getRawRequest($key, $default = null)
    {
        return $this->hasRawPost($key) ? $this->getRawPost($key) : $this->getRawGet($key, $default);
    }

    /**
     * Get $_GET value
     * @param string $key Key of the $_GET parameter
     * @param mixed $default Default value to return if key not found
     * @return mixed
     */
    public function getGet($key, $default = null)
    {
        if ($this->compat) {
            return $this->getCompatValue(self::GET, $key, $default);
        }
        return $this->getRawGet($key, $default);
    }

    /**
     * Get $_POST value
     * @param string $key Key of the $_POST parameter
     * @param mixed $default Default value to return if key not found
     * @return mixed
     */
    public function getPost($key, $default = null)
    {
        if ($this->compat) {
            return $this->getCompatValue(self::POST, $key, $default);
        }
        return $this->getRawPost($key, $default);
    }

    /**
     * Get $_REQUEST value
     * @param string $key Key of the $_REQUEST parameter
     * @param mixed $default Default value to return if key not found
     * @return mixed
     */
    public function getRequest($key, $default = null)
    {
        if ($this->compat) {
            return $this->getCompatValue(self::REQUEST, $key, $default);
        }
        return $this->getRawRequest($key, $default);
    }

    /**
     * Get super global value using compatibility approach
     * @param GET|POST|REQUEST $type Superglobal type
     * @param string $key Key of the super global to search for
     * @param mixed $default Default value to return if key not found
     * @return mixed
     * @throws SuperglobalException
     */
    protected function getCompatValue($type, $key, $default = null)
    {
        /*
         * Dynamically define our reference vars
         */
        switch ($type) {
            case self::GET:
                $superglobal = empty($_GET) ? array() : $_GET;
                $raw = $this->rawGet;
                $sanitized = $this->sanitizedGet;
                break;

            case self::POST:
                $superglobal = empty($_POST) ? array() : $_POST;
                $raw = $this->rawPost;
                $sanitized = $this->sanitizedPost;
                break;

            case self::REQUEST:
                $superglobal = empty($_REQUEST) ? array() : $_REQUEST;
                $raw = array_merge($this->rawGet, $this->rawPost);
                $sanitized = $this->sanitizedRequest;
                break;

            default:
                throw new SuperglobalException('Invalid type for getCompatValue specified: ' . $type);
        }

        /*
         * If the superglobal does not exist, just return the default. In case
         * we do have a sanitized value for it, log it as this means that the
         * original superglobal has been unset in our logic.
         */
        if (!isset($superglobal[$key])) {
            if (isset($sanitized[$key])) {
                $this->logger->debug(sprintf(
                    'Superglobals: access on unset superglobal which existed at the initial request [%s] %s',
                    $type,
                    $key
                ));
            }
            return $default;
        }

        /*
         * If no sanitized value exists then this superglobal has been set in
         * our logic and was not part of the original request. In this case we
         * return the actual superglobal value.
         */
        if (!isset($sanitized[$key])) {
            $this->logger->debug(sprintf(
                'Superglobals: access superglobal which was set outside of the initial request [%s] %s ',
                $type,
                $key
            ));
            return $superglobal[$key];
        }

        /*
         * If the requested superglobal key has the same value as the sanitized
         * value, we are dealing with an unaltered version. We therefor return
         * the non-santized (raw) value as this is what InputValidation is
         * expecting. We don't log anything as this is the expected behavior.
         */
        if ($superglobal[$key] === $sanitized[$key]) {
            return $raw[$key];
        }

        /*
         * We return the actual superglobal value at this point as its value
         * has been changed by our logic.
         */
        $this->logger->debug(sprintf(
            'Superglobals: access superglobal which was altered after the initial request [%s] %s',
            $type,
            $key
        ));
        return $superglobal[$key];
    }
}
