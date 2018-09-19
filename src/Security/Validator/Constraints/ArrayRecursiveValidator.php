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
use Symfony\Component\Validator\Constraints\AllValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 *
 * The supplied constraints are applied to every leaf element
 * in multi-dimensional array.
 *
 */
class ArrayRecursiveValidator extends AllValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ArrayRecursive) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ArrayRecursive');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }

        $context = $this->context;
        $validator = $context->getValidator()->inContext($context);

        $arrayIter = new \RecursiveArrayIterator($value);
        $iterIter = new \RecursiveIteratorIterator($arrayIter);

        foreach ($iterIter as $key => $element) {
            $validator->validate($element, $constraint->constraints);
        }

        $constraint->setFormattedReturnValue($value);
    }
}
