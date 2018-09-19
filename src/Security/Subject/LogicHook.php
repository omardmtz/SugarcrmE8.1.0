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

namespace Sugarcrm\Sugarcrm\Security\Subject;

use Sugarcrm\Sugarcrm\Security\Subject;

/**
 * A logic hook making changes
 */
final class LogicHook implements Subject
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $method;

    /**
     * Constructor
     *
     * @param string $class
     * @param string $method
     */
    public function __construct($class, $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            '_type' => 'logic-hook',
            'class' => $this->class,
            'method' => $this->method,
        ];
    }
}
