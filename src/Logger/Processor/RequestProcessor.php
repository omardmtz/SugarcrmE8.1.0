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

namespace Sugarcrm\Sugarcrm\Logger\Processor;

/**
 * Appends the call backtrace to the message
 */
class RequestProcessor
{
    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $variables = [];

        if (isset($_SESSION['oauth2']['user_id'])) {
            $variables['User ID'] = $_SESSION['oauth2']['user_id'];
        } elseif (isset($GLOBALS['current_user'])) {
            $variables['User ID'] = $GLOBALS['current_user']->id;
        }

        if (isset($_SESSION['oauth2']['client_id'])) {
            $variables['Client ID'] = $_SESSION['oauth2']['client_id'];
        }

        if (isset($_SESSION['platform'])) {
            $variables['Platform'] = $_SESSION['platform'];
        }

        foreach ($variables as $name => $value) {
            $record['message'] .= sprintf('; %s=%s', $name, $value);
        }

        return $record;
    }
}
