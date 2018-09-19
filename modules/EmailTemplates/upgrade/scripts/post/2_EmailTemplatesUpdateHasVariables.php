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

class SugarUpgradeEmailTemplatesUpdateHasVariables extends UpgradeDBScript
{
    public $order = 2200;

    public function run()
    {
        // are we coming from anything before 7.10?
        if (!version_compare($this->from_version, '7.10', '<')) {
            return;
        }

        $this->log('Updating Email Templates has_variables field');

        $sql = "SELECT id, subject, body, body_html FROM email_templates" .
            " WHERE subject LIKE '%$%' OR body LIKE '%$%' OR body_html LIKE '%$%'";

        $conn = $GLOBALS['db']->getConnection();
        $stmt = $conn->executeQuery($sql);
        $idsToUpdate = [];

        while ($row = $stmt->fetch()) {
            $templateData = $row['subject'] . ' ' . $row['body'] . ' ' . $row['body_html'];

            if (EmailTemplate::checkStringHasVariables($templateData)) {
                $idsToUpdate[] = $row['id'];
            }
        }

        if (count($idsToUpdate) > 0) {
            $sql = 'UPDATE email_templates SET has_variables=1 WHERE id IN (?)';
            $this->executeUpdate($sql, [$idsToUpdate], [\Sugarcrm\Sugarcrm\Dbal\Connection::PARAM_STR_ARRAY]);
        }

        $this->log('Done updating Email Templates has_variables field');
    }
}
