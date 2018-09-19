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


class LoggerApi extends SugarApi
{

    public function registerApiRest()
    {
        return array(
            'logPost' => array(
                'reqType' => 'POST',
                'path' => array('logger'),
                'pathVars' => array(),
                'method' => 'logMessage',
                'shortHelp' => 'Writes a message out to the log prefaced by a channel name',
                'longHelp' => 'include/api/help/logger_help.html',
            ),
        );
    }

    /**
     * Logs a message on the server, based on supplied arguments.
     *
     * @param ServiceBase $api The service object.
     * @param array $args The request arguments.
     * @return array Status.
     */
    public function logMessage(ServiceBase $api, array $args)
    {
        if (empty($args['message'])) {
            return array('status' => false);
        }

        $log = LoggerManager::getLogger();

        $level = empty($args['level']) ? 'debug' : $args['level'];
        $message = $args['message'];
        $channel = empty($args['channel']) ? 'LoggerApi' : $args['channel'];

        $log->$level("{$channel} - {$message}");

        return array('status' => true);
    }
}
