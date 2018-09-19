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

namespace Sugarcrm\Sugarcrm\Security\Validator;

use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\Constraint;

/**
 *
 * ConstraintValidator Factory
 *
 */
class ConstraintValidatorFactory implements ConstraintValidatorFactoryInterface
{
    /**
     * @var ConstraintValidator[]
     */
    protected $validators = array();

    /**
     * {@inheritdoc}
     */
    public function getInstance(Constraint $constraint)
    {
        $className = $constraint->validatedBy();

        if (!isset($this->validators[$className])) {
            $this->validators[$className] = new $className();
        }

        return $this->validators[$className];
    }

    /**
     * Clear validators cache
     */
    public function clearValidatorsCache()
    {
        $this->validators = [];
    }
}
