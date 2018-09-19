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

class SugarUpgradeTimePeriodsUpdateTimeStamps extends UpgradeScript
{
    public $order = 3030;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        $this->log('Updating TimePeriod TimeStamp fields');
        $sql = "select id, start_date, end_date from timeperiods";
        $results = $this->db->query($sql);

        $dt = TimeDate::getInstance();
        $dt->setAlwaysDb(true);

        $updateSql = "UPDATE timeperiods SET start_date_timestamp = '%d', end_date_timestamp = '%d' where id = '%s'";
        while ($row = $this->db->fetchRow($results)) {
            $this->db->query(
                sprintf(
                    $updateSql,
                    strtotime(substr($row['start_date'], 0, 10) . ' 00:00:00'),
                    strtotime(substr($row['end_date'], 0, 10) . ' 23:59:59'),
                    $row['id']
                )
            );
        }

        $dt->setAlwaysDb(false);

        $this->log('Done Updating TimePeriod TimeStamp fields');
    }
}
