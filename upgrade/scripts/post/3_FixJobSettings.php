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
 * @see BR-1868
 */
class SugarUpgradeFixJobSettings extends UpgradeScript
{
    public $order = 3100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (isset($this->upgrader->config['jobs']['timeout']) && $this->upgrader->config['jobs']['timeout'] == 86400) {
            $this->upgrader->config['jobs']['timeout'] = 3600;
        }

        if (isset($this->upgrader->config['cron']['max_cron_runtime'])
            && (
                $this->upgrader->config['cron']['max_cron_runtime'] == 60 ||
                $this->upgrader->config['cron']['max_cron_runtime'] == 30
            )) {
            $this->upgrader->config['cron']['max_cron_runtime'] = 1800;
        }

        if (!isset($this->upgrader->config['cron']['enforce_runtime'])) {
            $this->upgrader->config['cron']['enforce_runtime'] = false;
        }

    }
}
