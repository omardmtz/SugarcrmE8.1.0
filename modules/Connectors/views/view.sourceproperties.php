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

class ViewSourceProperties extends ViewList {
    public function display()
    {
        global $sugar_config;

        $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');

        // Default needed variables
        $required_fields = array();
        $properties = array();
        $hasTestingEnabled = false;
        $noConnector = true;

        // This will be an empty array if the connector isn't found
        $connector_language = ConnectorUtils::getConnectorStrings($source_id);

        // Now get to work on the connector
        $source = SourceFactory::getSource($source_id);
        if ($source !== null) {
            // Set our no connector flag to false
            $noConnector = false;

            // Get the connector config properties
            $properties = $source->getProperties();

            // Get the required fields for the connector, if there are any
            $fields = $source->getRequiredConfigFields();
            foreach ($fields as $field_id) {
                $label = isset($connector_language[$field_id]) ? $connector_language[$field_id] : $field_id;
                $required_fields[$field_id] = $label;
            }

            // treat string as a template (the string resource plugin is unavailable in the current Smarty version)
            if (isset($connector_language['LBL_LICENSING_INFO'])) {
                $siteUrl = rtrim($sugar_config['site_url'], '/');
                $connector_language['LBL_LICENSING_INFO'] = str_replace(
                    '{$SITE_URL}',
                    $siteUrl,
                    $connector_language['LBL_LICENSING_INFO']
                );
            }

            $hasTestingEnabled = $source->hasTestingEnabled();
        }

        $this->ss->assign('no_connector', $noConnector);
        $this->ss->assign('required_properties', $required_fields);
        $this->ss->assign('source_id', $source_id);
        $this->ss->assign('properties', $properties);
        $this->ss->assign('mod', $GLOBALS['mod_strings']);
        $this->ss->assign('app', $GLOBALS['app_strings']);
        $this->ss->assign('connector_language', $connector_language);
        $this->ss->assign('hasTestingEnabled', $hasTestingEnabled);

        echo $this->ss->fetch($this->getCustomFilePathIfExists('modules/Connectors/tpls/source_properties.tpl'));
    }
}
