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
 * PHP Serialized validator
 *
 * Validate PHP serialized data. This validator will report a violation when
 * objects are detected inside a PHP serialized string. Additionally the
 * unserialize operation is validate as well and the unserialized form is
 * set on the constraint as formatted value.
 *
 */
class PhpSerializedValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PhpSerialized) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\PhpSerialized');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if ($constraint->base64Encoded) {
            $value = base64_decode($value, true);
            if ($value === false) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%msg%', 'invalid base64 encoding')
                    ->setInvalidValue($value)
                    ->setCode(PhpSerialized::ERROR_BASE64_DECODE)
                    ->addViolation();
                return;
            }
        }

        if ($constraint->htmlEncoded) {
            $value = htmlspecialchars_decode($value, ENT_QUOTES);
        }

        // detect any objects
        preg_match('/[oc]:[^:]*\d+:/i', $value, $matches);
        if (count($matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%msg%', 'object(s) not allowed')
                ->setInvalidValue($value)
                ->setCode(PhpSerialized::ERROR_OBJECT_NOT_ALLOWED)
                ->addViolation();
            return;
        }

        // detect any references
        preg_match('/r:[^:]*\d+;/i', $value, $matches);
        if (count($matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%msg%', 'reference(s) not allowed')
                ->setInvalidValue($value)
                ->setCode(PhpSerialized::ERROR_REFERENCE_NOT_ALLOWED)
                ->addViolation();
            return;
        }

        // validate unserialize operation
        $unserialized = @unserialize($value);
        if ($unserialized === false && $value !== 'b:0;') {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%msg%', 'unserialize error')
                ->setInvalidValue($value)
                ->setCode(PhpSerialized::ERROR_UNSERIALIZE)
                ->addViolation();
            return;
        }

        $constraint->setFormattedReturnValue($unserialized);
    }
}
