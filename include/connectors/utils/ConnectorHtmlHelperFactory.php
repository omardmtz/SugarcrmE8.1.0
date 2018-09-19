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
 * Connector's HTML helper factory
 * @api
 */
class ConnectorHtmlHelperFactory
{
    const CONNECTOR_HTML_HELPER_MAIN = 'include/connectors/utils/ConnectorHtmlHelper.php';
    const CONNECTOR_HTML_HELPER_CUSTOM = 'custom/include/connectors/utils/ConnectorHtmlHelper.php';

    /**
     * Return instance of HTML helper class
     *
     * @return ConnectorHtmlHelper
     */
    public static function build()
    {
        if (file_exists(self::CONNECTOR_HTML_HELPER_CUSTOM))
        {
            require_once(self::CONNECTOR_HTML_HELPER_CUSTOM);
        }
        else
        {
            require_once(self::CONNECTOR_HTML_HELPER_MAIN);
        }
        return new ConnectorHtmlHelper();
    }
}