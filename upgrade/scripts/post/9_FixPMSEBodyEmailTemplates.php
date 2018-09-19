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
 * Update PMSE Email Templates body html.
 */
class SugarUpgradeFixPMSEBodyEmailTemplates extends UpgradeScript
{

    public function run()
    {
        // Only run this id source is 7.6.0.0 or 7.6.1.0 and the target is greater than 7.6.1.0
        if ((version_compare($this->from_version, '7.6.0.0', '==')
                || version_compare($this->from_version, '7.6.1.0', '=='))
            && version_compare($this->to_version, '7.6.1.0', '>')
        ) {
            $result = $this->db->query('SELECT id, body_html FROM pmse_emails_templates');

            while ($row = $this->db->fetchByAssoc($result)) {
                $oldHTML = $row['body_html'];
                $newHTML = SugarCleaner::cleanHtml($oldHTML, true);
                $et_id = $row["id"];

                if ($oldHTML !== $newHTML) {
                    $this->db->query("UPDATE pmse_emails_templates SET body_html = '$newHTML' WHERE id = '$et_id'");
                }
            }
        }
    }
}
