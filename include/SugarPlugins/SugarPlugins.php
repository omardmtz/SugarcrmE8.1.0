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
 * Plugin management
 * @api
 */
class SugarPlugins
{
    /**
     * @const URL of the Sugar plugin server
     */
    private const SUGAR_PLUGIN_SERVER = 'https://www.sugarcrm.com/crm/plugin_service.php';

    /**
     * @var nusoap_client
     */
    private $client;

    /**
     * Constructor
     *
     * Initializes the SOAP session
     */
    public function __construct()
    {
        $client = new nusoap_client(self::SUGAR_PLUGIN_SERVER . '?wsdl', true);

        if ($client->getError()) {
            return;
        }

        $this->client = $client;
    }

    /**
     * Returns an array of available plugins to download for this instance
     *
     * @return array
     */
    public function getPluginList()
    {
        if (!$this->client) {
            return [];
        }

        $result = $this->client->call('get_plugin_list', [
            'subscription' => $GLOBALS['license']->settings['license_key'],
            'sugar_version' => $GLOBALS['sugar_version'],
        ]);

        if (empty($result[0]['item'])) {
            return [];
        }

        return $result[0]['item'];
    }

    /**
     * Returns the download token for the given plugin
     *
     * @param  string $plugin_id
     * @return string token
     */
    private function getPluginDownloadToken($plugin_id)
    {
        if (!$this->client) {
            return '';
        }

        $result = $this->client->call('get_plugin_token', [
            'subscription' => $GLOBALS['license']->settings['license_key'],
            'user_id' => $GLOBALS['current_user']->id,
            'raw_name' => $plugin_id,
        ]);

        return $result['token'];
    }

    /**
     * Downloads the plugin from Sugar using an HTTP redirect
     *
     * @param string $plugin_id
     */
    public function downloadPlugin($plugin_id)
    {
        $token = $this->getPluginDownloadToken($plugin_id);
        ob_clean();
        SugarApplication::redirect(self::SUGAR_PLUGIN_SERVER . '?token=' . urlencode($token));
    }
}
