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

use Sugarcrm\Sugarcrm\Security\Validator\ConstraintReturnValueInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints as Assert;
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintReturnValueTrait;

/**
 *
 * @see ArrayRecursiveValidator
 *
 */
class ArrayRecursive extends All implements ConstraintReturnValueInterface
{
    use ConstraintReturnValueTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        // If no constraints are explicitly defined we assume string constraint
        if (is_array($options) && empty($options['constraints'])) {
            $options['constraints'] = new Assert\Type(array('type' => 'scalar'));
        }

        parent::__construct($options);
    }
}
