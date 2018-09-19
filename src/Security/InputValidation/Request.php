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

use Sugarcrm\Sugarcrm\Security\Validator\Constraints as Assert;
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintReturnValueInterface;
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintBuilder;
use Sugarcrm\Sugarcrm\Security\InputValidation\Exception\ViolationException;
use Sugarcrm\Sugarcrm\Security\InputValidation\Exception\SuperglobalException;
use Sugarcrm\Sugarcrm\Security\InputValidation\Sanitizer\SanitizerInterface;
use Sugarcrm\Sugarcrm\Security\InputValidation\Sanitizer\ConstraintSanitizerInterface;
use Sugarcrm\Sugarcrm\Security\InputValidation\Exception\RequestException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as AssertBasic;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 *
 * Request validator
 *
 * The request service object is available by using the InputValidation
 * service factory `InputValidation::getService()`.
 *
 */
class Request implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Superglobals
     */
    protected $superglobals;

    /**
     * @var SanitizerInterface|null
     */
    protected $sanitizer;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ConstraintBuilder
     */
    protected $constraintBuilder;

    /**
     * @var ContextualValidatorInterface
     */
    protected $context;

    /**
     * When softFail mode is enabled, request validation violations are only
     * reported through logger (warning) without throwing exceptions.
     * @var boolean
     */
    protected $softFail = false;

    /**
     * Supported input type mapping
     * @var array
     */
    protected $inputTypes = array(
        Superglobals::GET => array('get' => 'getGet', 'has' => 'hasGet'),
        Superglobals::POST => array('get' => 'getPost', 'has' => 'hasPost'),
        Superglobals::REQUEST => array('get' => 'getRequest', 'has' => 'hasRequest'),
    );

    /**
     * Ctor
     * @param Superglobals $superglobals
     * @param ValidatorInterface $validator
     * @param ConstraintBuilder $constraintBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        Superglobals $superglobals,
        ValidatorInterface $validator,
        ConstraintBuilder $constraintBuilder,
        LoggerInterface $logger
    ) {
        $this->superglobals = $superglobals;
        $this->validator = $validator;
        $this->constraintBuilder = $constraintBuilder;
        $this->setLogger($logger);
    }

    /**
     * Set optional generic sanitizer
     * @param SanitizerInterface $sanitizer
     */
    public function setSanitizer(SanitizerInterface $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    /**
     * Enable superglobal compatibility mode
     * @throws RequestException
     */
    public function enableCompatMode()
    {
        if ($this->superglobals->getCompatMode()) {
            throw new RequestException('Compatibility mode already enabled');
        }
        $this->superglobals->enableCompatMode();
    }

    /**
     * Set softFail mode
     * @param boolean $toggle
     */
    public function setSoftFail($toggle)
    {
        $this->softFail = (bool) $toggle;
    }

    /**
     * Get softFail mode
     * @return bool
     */
    public function getSoftFail()
    {
        return $this->softFail;
    }

    /**
     * Get validated input from $_GET
     * @param string $key
     * @param string|array $constraints ConstraintBuilder compat constraints
     * @param mixed $default Return value if input param does not exist
     * @return mixed
     */
    public function getValidInputGet($key, $constraints = null, $default = null)
    {
        return $this->getValidInput(Superglobals::GET, $key, $constraints, $default);
    }

    /**
     * Get validated input from $_POST
     * @param string $key
     * @param string|array $constraints ConstraintBuilder compat constraints
     * @param mixed $default Return value if input param does not exist
     * @return mixed
     */
    public function getValidInputPost($key, $constraints = null, $default = null)
    {
        return $this->getValidInput(Superglobals::POST, $key, $constraints, $default);
    }

    /**
     * Get validated input from $_REQUEST
     * @param string $key
     * @param string|array $constraints ConstraintBuilder compat constraints
     * @param mixed $default Return value if input param does not exist
     * @return mixed
     */
    public function getValidInputRequest($key, $constraints = null, $default = null)
    {
        return $this->getValidInput(Superglobals::REQUEST, $key, $constraints, $default);
    }

    /**
     * Get validated input value from SuperGlobals
     *
     * @param string $type GET|POST|REQUEST
     * @param string $key The input parameter you are looking for
     * @param string|array $constraints ConstraintBuilder compat constraints
     * @param mixed $default Return value if input param does not exist
     * @return mixed
     */
    public function getValidInput($type, $key, $constraints = null, $default = null)
    {
        // Validate superglobals type
        $this->validateSuperglobalsType($type);

        // Build constraints
        $constraints = $this->buildConstraints($type, $constraints);

        // Get raw value from superglobals
        $value = $this->getSuperglobalValue($type, $key, $default);

        // Generic sanitizing
        if ($this->sanitizer) {
            $value = $this->sanitizer->sanitize($value);
        }

        // Validate in a new context
        $this->context = $this->validator->startContext();
        $value = $this->validateConstraints($value, $constraints);
        $this->handleViolations($type, $key);

        return $value;
    }

    /**
     * Get last context violations
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
        return $this->context->getViolations();
    }

    /**
     * Validate constraints against given value
     * @param mixed $value The value to be validated
     * @param Constraint[] $constraints The constrait definition(s)
     * @return mixed
     */
    protected function validateConstraints($value, array $constraints)
    {
        foreach ($constraints as $constraint) {

            // update value using constraint sanitizer
            $value = $this->applyConstraintSanitizer($constraint, $value);

            // perform validation
            $this->context->validate($value, $constraint);

            // update value if constraint supplies a formatted return value
            if ($constraint instanceof ConstraintReturnValueInterface) {

                // if any violations exist we cannot continue
                if (count($this->context->getViolations()) !== 0) {
                    break;
                }

                $value = $constraint->getFormattedReturnValue();

            }
        }
        return $value;
    }

    /**
     * Verify if superglobals type is valid
     * @param string $type
     * @throws SuperglobalException
     */
    protected function validateSuperglobalsType($type)
    {
        if (!array_key_exists($type, $this->inputTypes)) {
            throw new SuperglobalException("Invalid superglobal [$type] requested");
        }
    }

    /**
     * Handle violations on current context
     * @param string $type GET|POST|REQUEST
     * @param string $key
     * @throws ViolationException
     */
    protected function handleViolations($type, $key)
    {
        $violations = $this->context->getViolations();
        if (count($violations) !== 0) {

            $this->logViolations($type, $key, $violations);

            if (!$this->softFail) {
                throw new ViolationException(
                    sprintf('Violation for %s -> %s', $type, $key),
                    $violations
                );
            }
        }
    }

    /**
     * Apply constraint sanitizer for given value
     * @param Constraint $constraints
     * @param mixed $value
     * @return mixed
     */
    protected function applyConstraintSanitizer(Constraint $constraint, $value)
    {
        if ($constraint instanceof ConstraintSanitizerInterface) {
            $value = $constraint->sanitize($value);
        }
        return $value;
    }

    /**
     * Get raw value from superglobals
     * @param string $type
     * @param string $key
     * @param mixed $default
     * @return mixed
     *
     */
    protected function getSuperglobalValue($type, $key, $default = null)
    {
        $get = $this->inputTypes[$type]['get'];
        return $this->superglobals->$get($key, $default);
    }

    /**
     * Log violations
     * @param string $type GET|POST|REQUEST
     * @param string $key The input parameter you are looking for
     * @param ConstraintViolationListInterface $violations
     */
    protected function logViolations($type, $key, ConstraintViolationListInterface $violations)
    {
        foreach ($violations as $violation) {

            /* @var $violation ConstraintViolationInterface */
            $message = sprintf(
                'InputValidation: [%s] %s -> %s',
                $type,
                $key,
                $violation->getMessage()
            );

            if ($this->softFail) {
                $this->logger->warning($message);
            } else {
                $this->logger->critical($message);
            }
        }
    }

    /**
     * Build constraints based on passed in definition
     * @param string $type GET|POST|REQUEST
     * @param string|array $constraints ConstraintBuilder compat constraints
     * @return Constraint[]
     */
    protected function buildConstraints($type, $constraints)
    {
        // Build actual constraints
        $constraints = $this->constraintBuilder->build($constraints);

        // If no constraint explicitly specified, make sure that the value is a string
        if (empty($constraints)) {
            $constraints = array(
                new AssertBasic\Type(array(
                    'type' => 'scalar',
                )),
            );
        }

        // Attach generic input validation
        $inputConstraint = new Assert\InputParameters(array(
            'inputType' => $type,
        ));

        array_unshift($constraints, $inputConstraint);

        return $constraints;
    }
}
