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
* Class SugarUpgradeDropPDFHeaderLogo
*
* Drop header_logo_url from pdfmanager table
*/
class SugarUpgradeDropPDFHeaderLogo extends UpgradeScript
{
    public $order = 7450;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.2';

    public function run()
    {
        if (!version_compare($this->from_version, '6.7.5', '==')) {
            // only need to run this upgrading from 6.7.5
            return;
        }

        // drop header_logo_url from pdfmanager table
        $sql = 'ALTER TABLE pdfmanager DROP COLUMN header_logo_url';
        if ($this->db->query($sql)) {
            $this->log('Removed header_logo_url from pdfmanager table');
        } else {
            $this->log('Failed to remove header_logo_url from pdfmanager table');
        }
    }
}

