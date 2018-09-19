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

namespace Sugarcrm\Sugarcrm\Security\Csrf;

use Sugarcrm\Sugarcrm\Logger\LoggerTransition;
use Sugarcrm\Sugarcrm\Session\SessionStorage;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Psr\Log\LoggerInterface;

/**
 *
 * CSRF Authenticator
 *
 * The current implementation supports per session based form.
 *
 * For form based authentication, SugarController determines its modify
 * actions. When SugarApplication detects a modify action, the CSRF
 * authentication logic in SugarController will be called for all POSTS.
 *
 * @see \SugarApplication
 * @see \SugarController
 *
 *
 * The following configuration parameters are available:
 *
 *  $sugar_config['csrf']['soft_fail_form'] = true/false
 *
 *      Enable soft failure mode for form based CSRF authentication. This
 *      config setting disables CSRF authentication and is only present to
 *      mitigate any upgrade issues where customizations are missing the
 *      necessary CSRF token injection. Using this settings is NOT recommended
 *      in a production environment. When soft failure is enabled sugar log
 *      will still report missing CSRF tokens or mismatches.
 *
 *  $sugar_config['csrf']['token_size']
 *      The size of the tokens being generated, defaults to 32.
 *
 */
class CsrfAuthenticator
{
    /**
     * Token id for form based CSRF
     * @var string
     */
    const FORM_TOKEN_ID = 'session_form';

    /**
     * Input field name holding the token for forms
     * @var string
     */
    const FORM_TOKEN_FIELD = 'csrf_token';

    /**
     * @var CsrfAuthenticator
     */
    protected static $instance;

    /**
     * @var CsrfTokenManagerInterface
     */
    protected $manager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Disable form CSRF validation
     * @var boolean
     */
    protected $softFailForm = false;

    /**
     * Ctor
     * @param CsrfTokenManagerInterface $manager
     * @param LoggerInterface $logger
     * @param \SugarConfig $config
     */
    public function __construct(CsrfTokenManagerInterface $manager, LoggerInterface $logger, \SugarConfig $config)
    {
        $this->manager = $manager;
        $this->logger = $logger;

        // set config options
        $this->softFailForm = (bool) $config->get('csrf.soft_fail_form', false);
    }

    /**
     * Get instance
     * @return CsrfAuthenticator
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {

            $config = \SugarConfig::getInstance();

            // setup token generator
            $tokenGenerator = new CsrfTokenGenerator();
            $tokenGenerator->setSize($config->get('csrf.token_size', 32));

            // setup token storage using sessions
            $tokenStorage = new CsrfTokenStorage(SessionStorage::getInstance());

            $manager = new CsrfTokenManager($tokenGenerator, $tokenStorage);
            $logger = new LoggerTransition(\LoggerManager::getLogger());

            $class = \SugarAutoLoader::customClass('Sugarcrm\Sugarcrm\Security\Csrf\CsrfAuthenticator');
            static::$instance = new $class($manager, $logger, $config);
        }
        return static::$instance;
    }

    /**
     * Get session based form token
     * @return string
     */
    public function getFormToken()
    {
        return $this->getToken(self::FORM_TOKEN_ID);
    }

    /**
     * Validate session based form token
     * @param array $post
     * @return boolean
     */
    public function isFormTokenValid(array $post)
    {
        // handle missing token
        if (empty($post[self::FORM_TOKEN_FIELD])) {
            $this->logger->critical("CSRF: attack vector detected, missing form token field");

            // return valid on soft failures, log a convenience message
            if ($this->softFailForm) {
                $this->logger->critical("CSRF: attack vector *NOT* mitigated, soft failure mode enabled");
                return true;
            }

            return false;
        }

        $token = $post[self::FORM_TOKEN_FIELD];

        // handle token mismatch
        if (!$this->isTokenValid(self::FORM_TOKEN_ID, $token)) {
            $this->logger->critical("CSRF: attack vector detected, invalid form token detected");

            // return valid on soft failures, log a convenience message
            if ($this->softFailForm) {
                $this->logger->critical("CSRF: attack vector *NOT* mitigated, soft failure mode enabled");
                return true;
            }

            return false;
        }

        $this->logger->debug("CSRF: Valid form token '$token'");
        return true;
    }

    /**
     * Generate a token for a given id
     * @param string $tokenId
     * @param boolean $refresh
     * @return string
     */
    protected function getToken($tokenId, $refresh = false)
    {
        if ($refresh) {
            $token = $this->manager->refreshToken($tokenId)->getValue();
        } else {
            $token = $this->manager->getToken($tokenId)->getValue();
        }

        $this->logger->debug("CSRF: generated token '$token' for '$tokenId'");
        return $token;
    }

    /**
     * Validate token for given id
     * @param string $tokenId
     * @param string $value
     * @return boolean
     */
    protected function isTokenValid($tokenId, $value)
    {
        return $this->manager->isTokenValid(new CsrfToken($tokenId, $value));
    }
}

