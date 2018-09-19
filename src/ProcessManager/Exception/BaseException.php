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

namespace Sugarcrm\Sugarcrm\ProcessManager\Exception;

/**
 * Class BaseException
 * @package ProcessManager
 */
class BaseException extends \Exception
{

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->logMessage();
    }

    public function logMessage()
    {
        // Since we need to log our exceptions, let's get the logger

        $logMessage = get_class($this) . ' : ' . $this->message;

        // Log it
        \PMSELogger::getInstance()->alert($logMessage);
    }
}
