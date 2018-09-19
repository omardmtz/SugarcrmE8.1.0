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
 * Handles changing empty string duration_minutes values to null prior to the
 * rebuild call so that the meetings table can be altered appropriately.
 */
class SugarUpgradeNullifyEmptyDurationMinutes extends UpgradeScript
{
    /**
     * Order is very important here. This must run before the Rebuild upgrader,
     * which runs at 2100.
     * @var integer
     */
    public $order = 2090;

    /**
     * Marked as all since this needs to support DB and Shadow upgrades
     * @var integer
     */
    public $type = self::UPGRADE_ALL;

    /**
     * The name of the column we will be working on
     * @var string
     */
    protected $columnName = 'duration_minutes';

    /**
     * The name of the table we will be working on
     * @var string
     */
    protected $tableName = 'meetings';

    /**
     * Mapping of integer column types that are the same as int
     * @var array
     */
    protected $numericColTypes = array(
        // MySQL and MSSQL integer type keyword
        'int' => 1,
        // IBMDB2 integer type keyword
        'integer' => 1,
        // Oracle integer type keyword
        'number' => 1,
    );

    public function run()
    {
        // Only run this if the criteria was met
        if ($this->needsUpdating()) {
            $this->setEmptyValuesToNull();
        }
    }

    /**
     * Determines whether the upgrader needs to be run
     * @return boolean
     */
    protected function needsUpdating()
    {
        // Grab the database column type for duration_minutes
        $colType = $this->getColumnType();

        // If it is INT or if it is empty, we have nothing to do
        if ($this->columnTypeIsInt($colType)) {
            return false;
        }

        // At this point, our column type will be known to not be int, so if the
        // vardef is int, we need to update
        $fieldType = $this->getFielddefType();

        if ($fieldType === 'int') {
            return true;
        }

        // If the field type was other than INT, be it NULL or ENUM or whatever,
        // we should not proceed
        return false;
    }

    /**
     * Determines if the column type specified in $colType is an integer type
     * @param string $colType
     * @return boolean
     */
    protected function columnTypeIsInt($colType)
    {
        // An empty colType, same as an int colType, means nothing to do
        if (empty($colType)) {
            return true;
        }

        // Normalize the colType
        $colType = strtolower($colType);

        // If the colType exists, then it is an int type field
        return !empty($this->numericColTypes[$colType]);
    }

    /**
     * Gets the database column type for the field we are trying to upgrade, if
     * it exists
     * @return string
     */
    protected function getColumnType()
    {
        $columns = $this->getColumns();
        return isset($columns[$this->columnName]['type']) ? $columns[$this->columnName]['type'] : '';
    }

    /**
     * Gets the columns for the table
     * @return array
     */
    protected function getColumns()
    {
        return $this->db->get_columns($this->tableName);
    }

    /**
     * Gets the field type from the vardef for the bean, if it exists
     * @return string
     */
    protected function getFielddefType()
    {
        $defs = $this->getFielddefsFromBean();

        // Now get the def for the this field if it exists
        if (!isset($defs[$this->columnName])) {
            return null;
        }

        $def = $defs[$this->columnName];

        // If the dbType is set, as it is as of 7.6.1...
        if (isset($def['dbType'])) {
            return $def['dbType'];
        }

        // Otherwise send back the type if it is found
        if (isset($def['type'])) {
            return $def['type'];
        }

        return null;
    }

    /**
     * Gets the field def array from the bean
     * @return array
     */
    protected function getFielddefsFromBean()
    {
        // Start with the Meetings bean so we can get the vardef
        $bean = BeanFactory::newBean('Meetings');
        return $bean->field_defs;
    }

    /**
     * Sets all empty string duration_minutes columns to null to allow the alter
     * routine in the next step to alter the table correctly. The net affect of
     * this change is minimal since an empty string and a null value are treated
     * the same across the application.
     */
    protected function setEmptyValuesToNull()
    {
        // Get our emptry string for comparison
        $empty = $this->db->quoted('');

        // Build the sql that will handle the reset
        $sql = "UPDATE {$this->tableName}
                SET {$this->columnName} = NULL
                WHERE {$this->columnName} = $empty";

        // Now capture the result of what just happened. Success means there might
        // be affected rows, failure means there was an error.
        if ($result = $this->db->query($sql)) {
            $rows = $this->db->getAffectedRowCount($result);
            $msg = "Meetings {$this->columnName} column reset from empty string to null. Affected records: $rows";
        } else {
            $err = $this->db->lastError();
            $msg = "Meetings {$this->columnName} column reset failed: $err";
        }

        // Log it an carry on
        $this->log($msg);
    }
}
