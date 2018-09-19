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

/**
 * Class OutboundEmailConfigurationApi
 * @deprecated This API is no longer needed.
 */
class OutboundEmailConfigurationApi extends ModuleApi
{
    /**
     * {@inheritdoc}
     *
     * Logs a deprecation warning.
     *
     * @deprecated This class is no longer used and is not recommended.
     */
    public function __construct()
    {
        parent::__construct();
        LoggerManager::getLogger()->deprecated(
            'OutboundEmailConfigurationApi and all of its endpoints have been deprecated.'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated All /OutboundEmailConfiguration endpoints have been deprecated.
     */
    public function registerApiRest() {
        LoggerManager::getLogger()->deprecated(
            'OutboundEmailConfigurationApi and all of its endpoints have been deprecated.'
        );

        $api = array(
            "outboundEmailConfigurationList" => array(
                "reqType"   => "GET",
                "path"      => array("OutboundEmailConfiguration", "list"),
                "pathVars"  => array("", ""),
                "method"    => "listConfigurations",
                "shortHelp" => "A list of outbound email configurations",
                'longHelp'  => 'modules/OutboundEmailConfiguration/clients/base/api/help/outbound_email_configuration_list_get_help.html',
            ),
        );

        return $api;
    }

    /**
     * @deprecated GET /OutboundEmailConfiguration/list has been deprecated and will not be available after v11. Use GET
     * /Emails/enum/outbound_email_id instead.
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function listConfigurations(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'GET /OutboundEmailConfiguration/list has been deprecated and will not be available after v11. Use GET ' .
            '/Emails/enum/outbound_email_id instead.'
        );

        $list = array();

        $configs = OutboundEmailConfigurationPeer::listValidMailConfigurations($GLOBALS["current_user"]);

        foreach ($configs as $config) {
            $inboxId    = $config->getInboxId();
            $configType = $config->getConfigType();

            $list[] = array(
                "id"      => (is_null($inboxId)) ? $config->getConfigId() : $inboxId,
                "display" => $config->getDisplayName(),
                "type"    => $configType,
                "default" => ($configType == "system"),
            );
        }

        return $list;
    }
}
