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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints\Sql;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 *
 * SQL 'ORDER BY' validator
 *
 */
class OrderByValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof OrderBy) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\OrderBy');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        // validate using regex:
        $regex = '/^((?=[0-9_]*[a-z])[a-z0-9_]+)(\.(?=[0-9_]*[a-z])[a-z0-9_]+)?$/i';
        if (!preg_match($regex, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->setCode(OrderBy::ERROR_ILLEGAL_FORMAT)
                ->setParameter('%msg%', 'illegal format')
                ->addViolation();
            return;
        }
    }
}
