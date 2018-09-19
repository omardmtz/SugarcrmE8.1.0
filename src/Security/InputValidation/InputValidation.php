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

use Sugarcrm\Sugarcrm\Security\Validator\Validator;
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintBuilder;
use Sugarcrm\Sugarcrm\Security\InputValidation\Sanitizer\Sanitizer;
use Sugarcrm\Sugarcrm\Security\InputValidation\Exception\InputValidationException;
use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;

/**
 *
 * Input validation service
 *
 * This service uses the 'input_validation' log channel.
 *
 */
class InputValidation
{
    /**
     * @var Request
     */
    private static $service;

    /**
     * Service class, dont instantiate.
     */
    private function __construct()
    {
    }

    /**
     * Initialize the service, should only be called once from entrypoint
     * @return Request
     */
    public static function initService()
    {
        if (self::$service) {
            throw new InputValidationException('Service already initialized');
        }

        // Create instance using raw request parameters. Make sure the service
        // is initialized before any other logic alters the superglobals.
        self::$service = $request = self::create($_GET, $_POST);

        $sugarConfig = \SugarConfig::getInstance();

        // Configure softFail mode - disabled by default
        $softFail = $sugarConfig->get('validation.soft_fail', false);
        $request->setSoftFail($softFail);

        // Enable compatibility mode - enabled by default
        if ($sugarConfig->get('validation.compat_mode', true)) {
            $request->enableCompatMode();
        }

        return self::$service;
    }

    /**
     * Get service
     * @return Request
     */
    public static function getService()
    {
        if (empty(self::$service)) {
            self::initService();
        }
        return self::$service;
    }

    /**
     * Create new Request validator service object. Use
     * `InputValidation::getService()` unless you know what you are doing.
     *
     * @param array $get Raw GET parameters
     * @param array $post Raw POST parameters
     * @return Request
     */
    public static function create(array $get, array $post)
    {
        $logger = LoggerFactory::getLogger('input_validation');
        $validator = Validator::getService();
        $superglobals = new Superglobals($get, $post, $logger);
        $constraintBuilder = new ConstraintBuilder();
        $request = new Request($superglobals, $validator, $constraintBuilder, $logger);

        // attach sanitizer (may disappear)
        $request->setSanitizer(new Sanitizer());

        return $request;
    }
}
