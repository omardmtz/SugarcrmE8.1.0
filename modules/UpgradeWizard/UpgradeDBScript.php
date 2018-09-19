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
 * Base class for upgrade scripts that need to perform database changes.
 */
abstract class UpgradeDBScript extends UpgradeScript
{
    public $type = self::UPGRADE_DB;

    /**
     * Executes a query and logs the number of affected rows or the error.
     *
     * @param string $sql The query to execute.
     * @param array $params Optional. The parameters to pass into the query to be escaped.
     * @param array $types Options. The parameter types. See the Doctrine DBAL documentation for more information.
     * @return integer The number of affected rows.
     */
    protected function executeUpdate($sql, array $params = array(), array $types = array())
    {
        $rows = 0;
        try {
            $rows = $this->db->getConnection()->executeUpdate($sql, $params, $types);
            $this->log("Number of affected rows: {$rows}");
        } catch (\Doctrine\DBAL\DBALException $error) {
            $this->log("Error: {$error}");
        }

        return $rows;
    }
}
