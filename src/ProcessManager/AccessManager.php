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

namespace Sugarcrm\Sugarcrm\ProcessManager;

require_once 'data/BeanFactory.php';
require_once 'include/api/SugarApiException.php';
require_once 'modules/pmse_Inbox/engine/PMSELogger.php';

/**
 * Class implements the ACL functionality used in ProcessManager
 */
class AccessManager
{
    /**
     * The singleton instance
     * @var AccessManager
     */
    protected static $instance = null;

    /**
     * Retrieves the singleton instance
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new AccessManager();
        }
        return static::$instance;
    }

    /**
     * This method checks ACL access in custom APIs
     * @param $api
     * @param $args
     * @throws SugarApiExceptionNotAuthorized
     */
    public function verifyAccess($api, $args)
    {
        $route = $api->getRequest()->getRoute();
        if (isset($route['acl'])) {
            $acl = $route['acl'];
            $seed = \BeanFactory::newBean($args['module']);
            if (!$seed->ACLAccess($acl)) {
                $sugarApiExceptionNotAuthorized = new \SugarApiExceptionNotAuthorized(
                    'No access to view/edit records for module: ' . $args['module']
                );
                \PMSELogger::getInstance()->alert($sugarApiExceptionNotAuthorized->getMessage());
                throw $sugarApiExceptionNotAuthorized;
            }
        }
    }

    /**
     * This method check if the user have admin or developer access to this API
     * @param $api
     * @param $args
     * @throws SugarApiExceptionNotAuthorized
     */
    public function verifyUserAccess($api, $args)
    {
        global $current_user;
        $route = $api->getRequest()->getRoute();
        $user = $current_user;
        if (isset($route['acl']) && $route['acl'] == 'adminOrDev') {
            if (!($user->isAdmin() || $user->isDeveloperForAnyModule())) {
                $sugarApiExceptionNotAuthorized = new \SugarApiExceptionNotAuthorized(
                    'No access to view/edit records for module: ' . $args['module']
                );
                \PMSELogger::getInstance()->alert($sugarApiExceptionNotAuthorized->getMessage());
                throw $sugarApiExceptionNotAuthorized;
            }
        }
    }
}
