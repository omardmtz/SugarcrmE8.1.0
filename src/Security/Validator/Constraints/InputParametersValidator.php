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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints;

use Sugarcrm\Sugarcrm\Security\InputValidation\Superglobals;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 *
 * Input parameters contraint
 *
 * This constraint is used to validate user input parameters. This
 * constraint is implicitly used by Request input validation to
 * generaically validate request parameters.
 *
 */
class InputParametersValidator extends ConstraintValidator
{
    /**
     * Supported types
     * @var array
     */
    protected $inputTypes = array(
        Superglobals::REQUEST,
        Superglobals::GET,
        Superglobals::POST,
    );

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof InputParameters) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\InputParameters');
        }

        if (!in_array($constraint->inputType, $this->inputTypes)) {
            throw new ConstraintDefinitionException("Unkown input type {$constraint->inputType}");
        }

        if (null === $value) {
            return;
        }

        $this->validateRecursive($constraint, $value);
    }

    /**
     * @param InputParameters $constraint
     * @param mixed $value
     */
    protected function validateRecursive(InputParameters $constraint, $value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $this->validateRecursive($constraint, $v);
            }
            return;
        }

        // generic scalar check
        if (!is_scalar($value)) {
            $this->context->buildViolation($constraint->msgGeneric)
                ->setParameter('%type%', $constraint->inputType)
                ->setInvalidValue($value)
                ->setCode($this->getErrorCode($constraint))
                ->addViolation();
            return;
        }

        // null bytes are never allowed
        if ($this->hasNullBytes($value)) {
            $this->context->buildViolation($constraint->msgNullBytes)
                ->setParameter('%type%', $constraint->inputType)
                ->setInvalidValue($value)
                ->setCode($this->getErrorCode($constraint))
                ->addViolation();
        }
    }

    /**
     * Get error code
     * @param InputParameters $constraint
     * @return integer
     */
    protected function getErrorCode(InputParameters $constraint)
    {
        switch ($constraint->inputType) {
            case Superglobals::REQUEST:
                return InputParameters::ERROR_REQUEST;
            case Superglobals::GET:
                return InputParameters::ERROR_GET;
            case Superglobals::POST:
                return InputParameters::ERROR_POST;
        }
    }

    /**
     * Check for null bytes in given string
     * @param string $value
     * @return boolean
     */
    protected function hasNullBytes($value)
    {
        return strpos($value, chr(0)) === false ? false : true;
    }
}
