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
 * CurrencyRateUpdateAbstract
 *
 * A class for updating currency rates on specified database table columns
 * when a currency conversion rate is updated by the administrator.
 *
 * Each module that has currency fields must supply a
 * modules/[ModuleName]/jobs/CurrencyRateUpdate.php file that
 * extends this class and defines the tables/columns that should be updated,
 * and manage any special cases as well, such as when rates should not be updated.
 *
 */
abstract class CurrencyRateUpdateAbstract
{
    /**
     * database handle
     * @var DBManager
     */
    protected $db;

    /*
     * if excluded, this module will not update its currencies
     */
    protected $exclude = false;

    /*
     * Rate column definitions, define each column of each table
     *
     * example:
     *
     * array(
     *   'tableFoo'=>array('base_rate'),
     *   'tableBar'=>array('base_rate')
     * ));
     */
    protected $rateColumnDefinitions = array();

    /*
     * automatic updating of usdollar fields
     */
    protected $updateUsDollar = true;

    /*
    * Us Dollar column definitions, define each column of each table
    *
    * format is tablename=>array(amount_field=>amount_usdollar_field)
    *
    * example:
    *
    * array(
    *   'tableFoo'=>array('amount'=>'amount_usdollar','foo'=>'foo_usdollar'),
    *   'tableBar'=>array('foo'=>'foo_usdollar')
    * ));
    */
    protected $usDollarColumnDefinitions = array();

    /**
     * constructor
     *
     * @access public
     */
    public function __construct() {}

    /**
     * run
     *
     * run the job to process the rate fields
     *
     * @access public
     * @param  object    $currencyId
     * @return boolean   true on success
     */
    public function run($currencyId)
    {
        if(empty($currencyId)) {
            return false;
        }
        if($this->exclude) {
            // module excluded, silent exit
            return true;
        }
        if(empty($this->rateColumnDefinitions)) {
            // no definitions, we are done
            return true;
        }
        $this->db = DBManagerFactory::getInstance();
        if(empty($this->db)) {
            $GLOBALS['log']->error(string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate','unable to load database manager')));
            return false;
        }
        $this->doPreUpdateAction();
        $dbTables = $this->db->getTablesArray();
        // loop each defined table and update each rate column according to the currency id
        foreach($this->rateColumnDefinitions as $tableName=>$tableColumns) {
            // make sure table exists
            if(!in_array($tableName,$dbTables)) {
                $GLOBALS['log']->error(string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate','unknown table')));
                return false;
            }
            $columns = $this->db->get_columns($tableName);
            foreach($tableColumns as $columnName) {
                // make sure column exists
                if(empty($columns[$columnName]))
                {
                    $GLOBALS['log']->error(string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate','unknown column')));
                    return false;
                }
                if(empty($columns['currency_id']))
                {
                    $GLOBALS['log']->error(string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate','table must have currency_id column')));
                    return false;
                }
                if(!$result = $this->doCustomUpdateRate($tableName, $columnName, $currencyId)) {
                    // if no custom processing required, we do the standard update
                    $result = $this->updateRate($tableName, $columnName, $currencyId);
                }
                if (empty($result)) {
                    return false;
                }
            }
        }
        if($this->updateUsDollar) {
            if(!$this->processUsDollarColumns($currencyId)) {
                return false;
            }
        }
        $this->doPostUpdateAction();
        return true;
    }

    /**
     * doPreUpdateAction
     *
     * Override this method in your extended class
     * to do custom actions before the update.
     *
     * @access protected
     * @return boolean true if pre update action was done
     */
    protected function doPreUpdateAction()
    {
        return false;
    }

    /**
     * doPostUpdateAction
     *
     * Override this method in your extended class
     * to do custom actions after the update.
     *
     * @access protected
     * @return boolean true if post update action was done
     */
    protected function doPostUpdateAction()
    {
        return false;
    }

    /**
     * doCustomUpdateRate
     *
     * Override this method in your extended class
     * to do custom tests and actions.
     *
     * @access protected
     * @param  string $table
     * @param  string $column
     * @param  string $currencyId
     * @return boolean true if custom processing was done
     */
    protected function doCustomUpdateRate($table, $column, $currencyId)
    {
        return false;
    }

    /**
     * updateRate
     *
     * execute the standard sql query for updating rates.
     * to use a specific query, override doCustomUpdateRate()
     * in your extended class and make your own.
     *
     * @access protected
     * @param  string $table
     * @param  string $column
     * @param  string $currencyId
     * @return Object database result object
     */
    protected function updateRate($table, $column, $currencyId)
    {
        // get the conversion rate
        $rate = $this->db->getOne(sprintf("SELECT conversion_rate FROM currencies WHERE id = '%s'", $currencyId));

        if(empty($rate)) {
            $GLOBALS['log']->error(string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate','unknown currency: ' . $currencyId)));
            return false;
        }

        // setup SQL statement
        $query = sprintf("UPDATE %s SET %s = %s WHERE currency_id = '%s'",
            $table,
            $column,
            $rate,
            $currencyId
        );
        // execute
        return $this->db->query($query, true,string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate',$query)));
    }

    /**
     * processUsDollarColumns
     *
     * automatically update *_usdollar fields for backward compatibility
     * with modules that still use this field. The *_usdollar fields use
     * the base_rate field for the rate calculations.
     *
     * @access protected
     * @param  string    $currencyId
     * @return boolean true on success
     */
    protected function processUsDollarColumns($currencyId)
    {
        // loop through all the tables
        foreach($this->usDollarColumnDefinitions as $tableName=>$tableDefs) {
            $columns = $this->db->get_columns($tableName);
            if(empty($columns)) {
                continue;
            }
            foreach($tableDefs as $amountColumn=>$usDollarColumn) {
                if(empty($columns[$amountColumn]) || empty($columns[$usDollarColumn]) || empty($columns['base_rate'])) {
                    continue;
                }
                if(!$this->doCustomUpdateUsDollarRate($tableName, $usDollarColumn, $amountColumn, $currencyId)) {
                    if(!$this->doUpdateUsDollarRate($tableName, $usDollarColumn, $amountColumn, $currencyId)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * doCustomUpdateUsDollarRate
     *
     * Override this method in your extended class
     * to do custom tests and actions.
     *
     * @access protected
     * @param  string    $tableName
     * @param  string    $usDollarColumn
     * @param  string    $amountColumn
     * @param  string    $currencyId
     * @return boolean true if custom processing was done
     */
    protected function doCustomUpdateUsDollarRate($tableName, $usDollarColumn, $amountColumn, $currencyId)
    {
        return false;
    }


    /**
     * doUpdateUsDollarRate
     *
     * execute the standard sql query for updating rates.
     * to use a specific query, override doCustomUpdateUsDollarRate()
     * in your extended class and make your own.
     *
     * @access protected
     * @param  string    $tableName
     * @param  string    $usDollarColumn
     * @param  string    $amountColumn
     * @param  string    $currencyId
     * @return boolean true on success
     */
    protected function doUpdateUsDollarRate($tableName, $usDollarColumn, $amountColumn, $currencyId)
    {
        // setup SQL statement
        $query = sprintf("UPDATE %s SET %s = %s / base_rate where currency_id = '%s'",
            $tableName,
            $usDollarColumn,
            $amountColumn,
            $currencyId
        );
        // execute
        $result = $this->db->query($query, true, string_format($GLOBALS['app_strings']['ERR_DB_QUERY'],array('CurrencyRateUpdate',$query)));
        if(empty($result)) {
            return false;
        }
        return true;
    }

    /*
     * setters/getters
     */

    /**
     * @access public
     * @param $table
     * @return array
     */
    public function getRateColumnDefinitions($table)
    {
        return $this->rateColumnDefinitions[$table];
    }

    /**
     * @access public
     * @param $table
     * @param $column
     * @return bool
     */
    public function addRateColumnDefinition($table, $column)
    {
        if(!isset($this->rateColumnDefinitions[$table])) {
            $this->rateColumnDefinitions[$table] = array();
        }
        if(in_array($column, $this->rateColumnDefinitions[$table])) {
            return true;
        }
        $this->rateColumnDefinitions[$table][] = $column;
        return true;
    }

    /**
     * @access public
     * @param $table
     * @param $column
     * @return bool
     */
    public function removeRateColumnDefinition($table, $column)
    {
        if(!isset($this->rateColumnDefinitions[$table])) {
            $this->rateColumnDefinitions[$table] = array();
        }
        if(!in_array($column, $this->rateColumnDefinitions[$table])) {
            return true;
        }
        // remove value
        $this->rateColumnDefinitions[$table] = array_diff($this->rateColumnDefinitions[$table], array($column));
        // reindex array
        $this->rateColumnDefinitions[$table] = array_values($this->rateColumnDefinitions[$table]);
        return true;
    }

    /**
     * @access public
     * @return bool
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * @access public
     * @param $exclude
     * @return bool
     */
    public function setExclude($exclude)
    {
        if(!is_bool($exclude)) {
            return false;
        }
        $this->exclude = $exclude;
    }

    /**
     * @access public
     * @param $table
     * @return array
     */
    public function getUsDollarColumnDefinitions($table)
    {
        return $this->usDollarColumnDefinitions[$table];
    }

    /**
     * @access public
     * @param $table
     * @param $amountColumn
     * @param $usDollarColumn
     * @return bool
     */
    public function addUsDollarColumnDefinition($table, $amountColumn, $usDollarColumn)
    {
        if(!isset($this->usDollarColumnDefinitions[$table])) {
            $this->usDollarColumnDefinitions[$table] = array();
        }
        $this->usDollarColumnDefinitions[$table][$amountColumn] = $usDollarColumn;
        return true;
    }

    /**
     * @access public
     * @param $table
     * @param $amountColumn
     * @return bool
     */
    public function removeUsDollarColumnDefinition($table, $amountColumn)
    {
        if(!isset($this->usDollarColumnDefinitions[$table])) {
            return false;
        }
        unset($this->usDollarColumnDefinitions[$table][$amountColumn]);
        return true;
    }
}
