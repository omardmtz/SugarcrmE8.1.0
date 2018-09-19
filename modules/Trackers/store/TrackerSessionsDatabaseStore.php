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


class TrackerSessionsDatabaseStore implements Store
{
    public function flush($monitor)
    {
        $db = DBManagerFactory::getInstance();

        $values = array();
        $metrics = $monitor->getMetrics();
        foreach ($metrics as $name => $metric) {
            if (isset($monitor->$name)) {
                $values[$name] = $db->quoteType($metrics[$name]->_type, $monitor->$name);
            }
        }

        if (empty($values)) {
            return;
        }

        if ($monitor->new === true) {
            if ($db->supports("auto_increment_sequence")) {
                $values['id'] = $db->getAutoIncrementSQL($monitor->table_name, 'id');
            }

            $this->cleanSessions($monitor);

            $query = "INSERT INTO
                      $monitor->table_name (" . implode(",", array_keys($values)) . ")
                      VALUES (" . implode(",", $values) . ')';
            $db->query($query);
        } else {
            // Update only on session close
            if (empty($values['date_end'])) {
                return;
            }
            $query = "UPDATE $monitor->table_name SET";

            $set = array();
            foreach ($values as $key => $value) {
                $set[] = " $key = $value ";
            }
            $query .= implode(",", $set);
            $query .= "WHERE session_id = '{$monitor->session_id}'";

            $GLOBALS['db']->query($query);
        }
    }

    private function cleanSessions($monitor)
    {
        $db = DBManagerFactory::getInstance();
        $query = "SELECT id, date_start, seconds
                    FROM $monitor->table_name
                    WHERE user_id = '" . $db->quote($monitor->getValue('user_id')) . "'
                    AND active = 1 AND deleted = 0";
        $result = $db->query($query);

        $dateEnd = TimeDate::getInstance()->nowDb();

        while ($row = $db->fetchByAssoc($result)) {
            $query = "UPDATE $monitor->table_name SET ";

            if (empty($row['seconds'])) {
                $query .= "date_end = '" . $dateEnd . "',
                    seconds = '" . (strtotime($dateEnd) - strtotime($row['date_start'])) . "', ";
            }

            $query .= "active = 0
                WHERE id = '{$row['id']}'";

            $db->query($query);
        }
    }
}
