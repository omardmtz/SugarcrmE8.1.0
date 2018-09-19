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

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 *
 * JSON validator
 *
 * Validate JSON data.
 * This validator will report an error when the data is not well formed JSON
 *
 */
class JSONValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof JSON) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\JSON');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if ($constraint->htmlDecode) {
            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        }

        // validate json_decode operation
        $decoded = json_decode($value, $constraint->assoc);
        if ($decoded === null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%msg%', 'json_decode error')
                ->setInvalidValue($value)
                ->setCode(JSON::ERROR_JSON_DECODE)
                ->addViolation();
            return;
        }

        $constraint->setFormattedReturnValue($decoded);
    }
}
