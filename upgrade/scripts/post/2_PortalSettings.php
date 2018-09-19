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
 * Migrates portal settings.
 */
class SugarUpgradePortalSettings extends UpgradeScript
{
    public $order = 2170;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (!$this->toFlavor('ent')) {
            return;
        }

        // Grabs the portal config setting.
        $query = "SELECT value FROM config WHERE category='portal' AND name='on'";
        $portalEnabled = (bool) $this->db->getOne($query);

        // Remove `portal_on` with platform equals to NULL or platform equals to empty string
        $query = "DELETE FROM config WHERE category='portal' AND name='on' AND (platform IS NULL OR platform='')";
        $this->db->query($query);

        // Clean up quotes from older config values
        if (version_compare($this->from_version, '7.7', '<')) {
            $adminSettings = Administration::getSettings();
            //FIXME: TY-839 category should be `support`, platform should be `portal`
            $portalConfig = $adminSettings->getConfigForModule('portal', 'support', true);
            foreach ($portalConfig as $name => $value) {
                if (!is_string($value)) {
                    continue;
                }
                // Using trim to only remove quotes at both ends of the string.
                $cleanVal = trim($value, "\"'");
                // Only save if the trimming did something.
                if ($cleanVal !== $value) {
                    $adminSettings->saveSetting('portal', $name, $cleanVal, 'support');
                }
            }
        }

        // only run this when coming from a version lower than 7.1.5
        if (version_compare($this->from_version, '7.1.5', '>=')) {
            return;
        }

        global $mod_strings;

        // Update portal setting name `displayModules` to `tab`
        $this->updatePortalTabsSetting();

        // Set portal setting `logLevel` to `ERROR`
        $fieldKey = 'logLevel';
        $fieldValue = 'ERROR';
        $admin = new Administration();
        if (!$admin->saveSetting('portal', $fieldKey, $fieldValue, 'support')) {
            $error = sprintf($this->mod_strings['ERROR_UW_PORTAL_CONFIG_DB'], 'portal', $fieldKey, $fieldValue);
            return $this->fail($error);
        }

        // Remove `fieldsToDisplay` (# of fields displayed in detail view - not used anymore in 7.0)
        $query = "DELETE FROM config WHERE category='portal' AND name='fieldsToDisplay' AND platform='support'";
        $this->db->query($query);

        // Enables portal if it is set to true.
        // FIXME: TY-839 category should be `support`, platform should be `portal`
        $admin->saveSetting('portal', 'on', $portalEnabled, 'support');

        // Sets up portal.
        if ($portalEnabled) {
            $parser = new ParserModifyPortalConfig();
            $parser->setUpPortal();
        }
    }

    /**
     * Migrates portal tab settings previously stored as:
     * `category` = 'portal', `platform` = 'support', `name` = 'displayModules'
     * to:
     * `category` = 'MySettings', `platform` = 'portal', `name` = 'tab'
     */
    public function updatePortalTabsSetting()
    {
        $admin = Administration::getSettings();
        $portalConfig = $admin->getConfigForModule('portal', 'support', true);

        if (empty($portalConfig['displayModules'])) {
            return;
        }

        // If Home does not exist we push Home in front of the array
        if (!in_array('Home', $portalConfig['displayModules'])) {
            array_unshift($portalConfig['displayModules'], 'Home');
        }

        if ($admin->saveSetting('MySettings', 'tab', $portalConfig['displayModules'], 'portal')) {
            // Remove old config setting `displayModules`
            $query = "DELETE FROM config WHERE category='portal' AND platform='support' AND name='displayModules'";
            $this->db->query($query);
        } else {
            $log = 'Error upgrading portal config var displayModules, ';
            $log .= 'orig: ' . $portalConfig['displayModules'] . ', ';
            $log .= 'json:' . json_encode($portalConfig['displayModules']);
            $this->log($log);
        }
    }
}
