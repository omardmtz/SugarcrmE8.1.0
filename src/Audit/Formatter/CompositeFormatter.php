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
namespace Sugarcrm\Sugarcrm\Audit\Formatter;

use Sugarcrm\Sugarcrm\Audit\Formatter;

/**
 * Class CompositeFormatter
 * Registry class for Audit Row Data Formatters
 * @package Sugarcrm\Sugarcrm\Audit\Formatter
 */
final class CompositeFormatter implements Formatter
{
    /**
     * @var Formatter[]
     */
    private $formatters = array();

    public function __construct(Formatter ...$formatters)
    {
        $this->formatters = $formatters;
    }

    /**
     * Itterates through all known formatters and runs them across the provided rows
     * @param array $rows
     */
    public function formatRows(array &$rows)
    {
        foreach ($this->formatters as $formatter) {
            $formatter->formatRows($rows);
        }
    }
}
