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
 * Move custom tcpdf fonts from custom/include/tcpdf to custom/vendor/tcpdf.
 */
class SugarUpgradeMovePDFCustomFonts extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
    	if (!version_compare($this->from_version, '7.0.0', '<')) {
            return;
        }

        if (!is_dir('custom/include/tcpdf') || is_dir('custom/vendor/tcpdf')) {
            return;
        }

        if (!is_dir('custom/vendor')) {
            mkdir('custom/vendor');
        }

       $this->log('Renaming custom/include/tcpdf');
       rename('custom/include/tcpdf', 'custom/vendor/tcpdf');
       $this->log('Renamed custom/include/tcpdf to custom/vendor/tcpdf');
    }
}
