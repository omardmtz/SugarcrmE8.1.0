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

/**
 *
 * @see OrderDirectionValidator
 *
 */
class OrderDirection extends Constraint
{
    const ERROR_ILLEGAL_FORMAT = 1;

    protected static $errorNames = array(
        self::ERROR_ILLEGAL_FORMAT => 'ERROR_ILLEGAL_FORMAT',
    );

    public $message = 'Order direction violation: expecting ASC or DESC';
}
