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

namespace Sugarcrm\Sugarcrm\Security\Validator\Constraints\Bean;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 *
 * Bean module name validator
 *
 */
class ModuleNameValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ModuleName) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\ModuleName');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!$this->isValidModule($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%module%', $value)
                ->setInvalidValue($value)
                ->setCode(ModuleName::ERROR_UNKNOWN_MODULE)
                ->addViolation();
        }
    }

    /**
     * Check if module exists
     * @param string $value
     * @return boolean
     */
    protected function isValidModule($module)
    {
        return \BeanFactory::getBeanClass($module) ? true : false;
    }
}
