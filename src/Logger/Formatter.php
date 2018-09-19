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

namespace Sugarcrm\Sugarcrm\Logger;

use Monolog\Formatter\LineFormatter;

/**
 * SugarLogger-compatible log formatter
 */
class Formatter extends LineFormatter
{
    /**
     * Constructor
     *
     * @param string $dateFormat Date format
     */
    public function __construct($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        global $current_user;

        if (!empty($current_user->id)) {
            $userId = $current_user->id;
        } else {
            $userId = '-none-';
        }

        return strftime($this->dateFormat)
            . ' '
            . '[' . getmypid() . ']'
            . '[' . $userId . ']'
            . '[' . strtoupper($record['level_name']) . ']'
            . ' '
            . $this->stringify($record['message'])
            . "\n";
    }

    /**
     * {@inheritdoc}
     */
    protected function replaceNewlines($str)
    {
        if ($this->allowInlineLineBreaks) {
            return $str;
        }

        return str_replace(array("\r", "\n"), array('\r', '\n'), $str);
    }
}
