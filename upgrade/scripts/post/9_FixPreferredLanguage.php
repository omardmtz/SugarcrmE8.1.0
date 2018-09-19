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
 * Upgrade script to set preferred_language in the database.
 */
class SugarUpgradeFixPreferredLanguage extends UpgradeScript
{
    public $order = 9600;
    public $type = self::UPGRADE_CUSTOM;
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $this->fixPreferredLanguage();
        }
    }
    // Use current_language for preferred_language in contacts table if the value is not set before
    public function fixPreferredLanguage()
    {
        global $current_language;
        $db = DBManagerFactory::getInstance();
        $sql = sprintf(
            "UPDATE contacts SET preferred_language = %s WHERE preferred_language is NULL OR preferred_language = ''",
            $db->quoted($current_language)
        );
        $db->query($sql);
    }
}
