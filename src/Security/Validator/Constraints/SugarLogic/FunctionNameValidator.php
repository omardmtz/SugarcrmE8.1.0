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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints\SugarLogic;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 *
 * SugarLogic function name validator
 *
 */
class FunctionNameValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof FunctionName) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\ComponentName');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string)$value;

        // check for invalid characters. Regex pulled from expressions.js
        if (!preg_match('/^[\w\-]*$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter(
                    '%msg%',
                    'must only use word characters and -'
                )
                ->setInvalidValue($value)
                ->setCode(FunctionName::ERROR_INVALID_FUNCTION_NAME)
                ->addViolation();

            return;
        }
    }
}
