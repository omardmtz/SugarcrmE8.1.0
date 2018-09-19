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

namespace Sugarcrm\Sugarcrm\Logger\Formatter;

use Sugarcrm\Sugarcrm\Logger\Formatter;

/**
 * Formatter for backward compatibility with legacy error levels
 *
 * In case if the message contains legacy level name, it restores the original message
 * and replaces the PSR level with the legacy one
 */
class BackwardCompatibleFormatter extends Formatter
{
    /**
     * {@inheritdoc}
     *
     * @see SugarPsrLogger::log()
     */
    public function format(array $record)
    {
        $record['message'] = preg_replace_callback('/^\[LEVEL:([^\]]+)\] (.*)/', function ($matches) use (&$record) {
            $record['level_name'] = $matches[1];
            return $matches[2];
        }, $record['message']);

        return parent::format($record);
    }
}
