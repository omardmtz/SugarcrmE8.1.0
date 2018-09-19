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
 * Update config.php settings
 */
class SugarUpgradeConfigSettings extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // Fill the missing settings.
        $defaultSettings = get_sugar_config_defaults();
        foreach ($defaultSettings as $key => $defaultValue) {

            if (!array_key_exists($key, $this->upgrader->config)) {
                $this->log("Setting $key does not exist. Setting the default value.");
                $this->upgrader->config[$key] = $defaultValue;
            }

        }

        $this->upgrader->config['sugar_version'] = $this->to_version;

	    if(!isset($this->upgrader->config['logger'])){
		    $this->upgrader->config['logger'] =array (
				'level'=>'fatal',
				'file' =>
				array (
						'ext' => '.log',
						'name' => 'sugarcrm',
						'dateFormat' => '%c',
						'maxSize' => '10MB',
						'maxLogs' => 10,
						'suffix' => '', // bug51583, change default suffix to blank for backwards comptability
				),
		    );
	    }

	    if (!isset($this->upgrader->config['lead_conv_activity_opt'])) {
	        $this->upgrader->config['lead_conv_activity_opt'] = 'copy';
	    }


        // We no longer have multiple themes support.

        // We removed the ability for the user to choose his preferred theme.
        // In the future, we'll add this feature back, in the new Sidecar Themes
        // format.
        // Backward compatibilty modules look and feel must be in accordance to
        // Sidecar modules, thus there is only one possible theme: `RacerX`
        $this->upgrader->config['default_theme'] = 'RacerX';

        $this->removeMassActionsDefaultSettings();
        $this->fixConfigSettings($defaultSettings);
    }

    /**
     * Overwrites some existing config keys for specific versions
     *
     * @param array $config Default Sugar config
     */
    private function fixConfigSettings($config)
    {
        $data = array(
            '7.8.0.0' => array(
                'snip_url',
            ),
        );
        
        foreach ($data as $version => $config_keys) {
            if (version_compare($this->from_version, $version, '<')) {
                foreach ($config_keys as $key) {
                    if (isset($config[$key])) {
                        $this->upgrader->config[$key] = $config[$key];
                    }
                }
            }
        }
    }

    /**
     * Removes system's mass actions settings if they are same as pre - 7.6.0
     * default settings.
     */
    private function removeMassActionsDefaultSettings()
    {
        if (version_compare($this->from_version, '7.6.0', '>') || empty($this->upgrader->config['mass_actions'])) {
            return;
        }
        $this->log('Checking mass config action values...');
        $settings = array(
            'mass_update_chunk_size' => 20,
            'mass_delete_chunk_size' => 20,
            'mass_link_chunk_size' => 20,
        );
        foreach ($settings as $setting => $previousDefaultValue) {
            if (!empty($this->upgrader->config['mass_actions'][$setting]) &&
                $this->upgrader->config['mass_actions'][$setting] === $previousDefaultValue
            ) {
                unset($this->upgrader->config['mass_actions'][$setting]);
            }
        }
    }
}
