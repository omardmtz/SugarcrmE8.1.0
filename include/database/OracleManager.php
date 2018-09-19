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

/*********************************************************************************

* Description: This file handles the Data base functionality for the application using oracle.
* It acts as the DB abstraction layer for the application. It depends on helper classes
* which generate the necessary SQL. This sql is then passed to PEAR DB classes.
* The helper class is chosen in DBManagerFactory, which is driven by 'db_type' in 'dbconfig' under config.php.
*
* All the functions in this class will work with any bean which implements the meta interface.
* The passed bean is passed to helper class which uses these functions to generate correct sql.
* Please see DBManager file for details
*
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/


/**
 * Oracle driver
 */
class OracleManager extends DBManager
{
    /**
     * @see DBManager::$dbType
     */
    public $dbType = 'oci8';
    public $dbName = 'Oracle';
    public $variant = 'oci8';
    public $label = 'LBL_ORACLE';

	/**
     * contains the last result set returned from query()
     */
    protected $_lastResult;

    protected $capabilities = array(
        "affected_rows" => true,
        "case_sensitive" => true,
        "fulltext" => true,
        "auto_increment_sequence" => true,
        'limit_subquery' => true,
        "recursive_query" => true,
        "case_insensitive" => true,
    );

    protected $maxNameLengths = array(
        'table' => 30,
        'column' => 30,
        'index' => 30,
        'alias' => 30
    );

    protected $type_map = array(
        'int'      => 'number',
        'double'   => 'number(38,10)',
        'float'    => 'number(30,6)',
        'uint'     => 'number(15)',
        'ulong'    => 'number(38)',
        'long'     => 'number(38)',
        'short'    => 'number(3)',
        'varchar'  => 'varchar2',
        'text'     => 'clob',
        'longtext' => 'clob',
        'date'     => 'date',
        'enum'     => 'varchar2(255)',
        'relate'   => 'varchar2',
        'multienum'=> 'clob',
        'html'     => 'clob',
        'longhtml' => 'clob',
        'datetime' => 'date',
        'datetimecombo' => 'date',
        'time'     => 'date',
        'bool'     => 'number(1)',
        'tinyint'  => 'number(3)',
        'char'     => 'char',
        'id'       => 'varchar2(36)',
        'blob'     => 'blob',
        'longblob' => 'blob',
        'currency' => 'number(26,6)',
        'decimal'  => 'number(20,2)',
        'decimal2' => 'number(30,6)',
        'url'      => 'varchar2',
        'encrypt'  => 'varchar2(255)',
        'file'     => 'varchar2(255)',
        'decimal_tpl' => 'number(%d, %d)',
        'smallint' => 'number(5)',
            );

    /**
     * Integer fields' min and max values
     * @var array
     */
    protected $type_range = array(
        'int'      => array('min_value'=>-99999999999999999999999999999999999999, 'max_value'=>99999999999999999999999999999999999999),
        'uint'     => array('min_value'=>-999999999999999, 'max_value'=>999999999999999), // number(15)
        'ulong'    => array('min_value'=>-99999999999999999999999999999999999999, 'max_value'=>99999999999999999999999999999999999999),
        'long'     => array('min_value'=>-99999999999999999999999999999999999999, 'max_value'=>99999999999999999999999999999999999999),
        'short'    => array('min_value'=>-999, 'max_value'=>999),// number(3)
        'tinyint'  => array('min_value'=>-999, 'max_value'=>999), // number(3)
    );

	/**
     * List of known sequences
     * @var array
     */
    protected static $sequences = null;

    /**
     * DB configuration options
     * @var array
     */
    protected $configOptions;

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others. In the case of Oracle, empty strings are converted to NULL for
     * comparison, and when using string comparison operations on a NULL, Oracle
     * throws a syntax error.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getEmptyStringSQL($field)
    {
        return $this->getIsNullSQL($field);
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others. In the case of Oracle, empty strings are converted to NULL for
     * comparison, and when using string comparison operations on a NULL, Oracle
     * throws a syntax error.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getNotEmptyStringSQL($field)
    {
        return $this->getIsNotNullSQL($field);
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others. In the case of Oracle, empty strings are converted to NULL for
     * comparison, and when using string comparison operations on a NULL, Oracle
     * throws a syntax error.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getEmptyFieldSQL($field)
    {
        return $this->getIsNullSQL($field);
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others. In the case of Oracle, empty strings are converted to NULL for
     * comparison, and when using string comparison operations on a NULL, Oracle
     * throws a syntax error.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getNotEmptyFieldSQL($field)
    {
        return $this->getIsNotNullSQL($field);
    }

    /**
     * Sets where properties for empty conditions on the SugarQuery object. Since
     * Oracle does things a little differently with empty strings we need to
     * define this here to keep Oracle happy.
     * @param SugarQuery_Builder_Where $where SugarQuery where object
     * @param string $field The field to compare
     * @param SugarBean $bean SugarBean
     * @return SugarQuery_Builder_Where
     */
    public function setEmptyWhere(SugarQuery_Builder_Where $where, $field, $bean = false)
    {
        $where->isNull($field, $bean);
        return $where;
    }

    /**
     * Sets where properties for not empty conditions on the SugarQuery object. Since
     * Oracle does things a little differently with empty strings we need to
     * define this here to keep Oracle happy.
     * @param SugarQuery_Builder_Where $where SugarQuery where object
     * @param string $field The field to compare
     * @param SugarBean $bean SugarBean
     * @return SugarQuery_Builder_Where
     */
    public function setNotEmptyWhere(SugarQuery_Builder_Where $where, $field, $bean = false)
    {
        $where->notNull($field, $bean);
        return $where;
    }

    /**
     * Builds the SQL commands that repair a table structure
     *
     * @param string $tablename Table name
     * @param array  $fielddefs Field definitions, in vardef format
     * @param array  $indices   Index definitions, in vardef format
     * @param bool   $execute   optional, true if we want the queries executed instead of returned
     * @param string $engine    optional, MySQL engine
     *
     * @return string
     * {@inheritDoc}
     * @see    DBManager::repairTableParams()
     */
    public function repairTableParams($tablename, $fielddefs, array $indices, $execute = true, $engine = null)
    {
        //Modules with names close to 30 characters may have index names over 30 characters, we need to clean them
        foreach ($indices as $key => $value) {
            $indices[$key]['name'] = $this->getValidDBName($value['name'], true, 'index');
        }

        return parent::repairTableParams($tablename,$fielddefs,$indices,$execute,$engine);
    }
    /**
     * @see DBManager::version()
     */
    public function version()
    {
        return $this->getOne("SELECT version FROM product_component_version WHERE product like '%Oracle%'");
    }

    /**
     * @see DBManager::checkError()
     */
    public function checkError($msg = '', $dieOnError = false, $stmt = null)
    {
        if (parent::checkError($msg, $dieOnError))
            return true;

        if(empty($stmt)) return false;

        $err = oci_error($stmt);
        if ($err){
            $error = $err['code']."-".$err['message'];
            $this->registerError($msg, $error, $dieOnError);
            return true;
        }
        return false;
    }

	/**
     * Parses and runs queries
     *
     * @param  string   $sql               SQL Statement to execute
     * @param  bool     $dieOnError        True if we want to call die if the query returns errors
     * @param  string   $msg               Message to log if error occurs
     * @param  bool     $suppress          Flag to suppress all error output unless in debug logging mode.
     * @param  bool     $keepResult		   True if we want to push this result into the $lastResult var.
     * @return resource result set
     */
    public function query($sql, $dieOnError = false, $msg = '', $suppress = false, $keepResult = false)
    {
        if(is_array($sql)) {
            return $this->queryArray($sql, $dieOnError, $msg, $suppress);
        }
        parent::countQuery($sql);
        $GLOBALS['log']->info('Query: ' . $sql);
        $this->checkConnection();
        $this->query_time = microtime(true);
        $db = $this->getDatabase();
        $result = false;

        $stmt = $suppress?@oci_parse($db, $sql):oci_parse($db, $sql);
		if(!$this->checkError("$msg Parse Failed: $sql", $dieOnError)) {

            $freeStmt = false;
            if (!$keepResult && oci_statement_type($stmt) != 'SELECT' && oci_statement_type($stmt) != 'UPDATE'){ // getAffectedRowCount using UPDATE returned cursor
                // free statement if not SELECT or UPDATE
                $freeStmt = true;
            }

			$exec_result = $suppress?@oci_execute($stmt):oci_execute($stmt);
	        $this->query_time = microtime(true) - $this->query_time;
	        $GLOBALS['log']->info('Query Execution Time: '.$this->query_time);
		    $this->dump_slow_queries($sql);
			if($exec_result) {
			    $result = $stmt;
			}

            if ($freeStmt){
                $this->freeDbResult($stmt);
                if($exec_result) {
                    $result = true;
                }
            }

            $this->lastQuery = $sql;
            if($keepResult) {
                $this->lastResult = $result;
            }

            if($this->checkError($msg.' Query Failed: ' . $sql, $dieOnError, $stmt)) {
                // free statement
                $this->freeDbResult($stmt);
                return false;
            }
		}

        return $result;
    }

    /**
     * @see DBManager::checkQuery()
     *
     * @param  string $sql         query to be run
     * @param  bool   $object_name optional, object to look up indices in
     * @return bool   true if an index is found false otherwise
     */
    protected function checkQuery($sql, $object_name = false)
    {
        $name = (empty($GLOBALS['current_user']) || empty($GLOBALS['current_user']->user_name))
            ? 'generic' : $GLOBALS['current_user']->user_name;
        $id = 'sugar' .$name;
        $sql = "EXPLAIN PLAN SET statement_id='" . $id . "' FOR " . $sql ;

        $this->query($sql);

        $result = $this->query("SELECT * FROM plan_table WHERE statement_id='$id' AND object_type='TABLE' AND options='FULL'");
        $badQuery = array();
        $minCost = (!empty($GLOBALS['sugar_config']['check_query_cost']))?$GLOBALS['sugar_config']['check_query_cost']:10;
        while ($row = $this->fetchByAssoc($result)) {
            if ($row['cost'] < $minCost)
                continue;

            $table = $row['object_name'];
            $badQuery[$table] = '';
            if($row['options'] == 'FULL')
                $badQuery[$table]  .=  ' Full Table Scan[cost:' . $row['cost'] . ' cpu:' . $row['cpu_cost'] . ' io:'
                    . $row['io_cost'] . '];';
        }
        if (!empty($badQuery)) {
            foreach ($badQuery as $table=>$data ) {
                if(!empty($data)){
                    $warning = ' Table:' . $table . ' Data:' . $data;
                    if(!empty($GLOBALS['sugar_config']['check_query_log'])){
                        $GLOBALS['log']->fatal($sql);
                        $GLOBALS['log']->fatal('CHECK QUERY:' .$warning);
                    }else{
                        $GLOBALS['log']->warn('CHECK QUERY:' .$warning);
                    }
                }
            }
        }
        $this->query("DELETE FROM plan_table WHERE statement_id='$id'");
    }

    /**
     * Runs a limit query: one where we specify where to start getting records and how many to get
     *
     * @param  string   $sql
     * @param  int      $start
     * @param  int      $count
     * @param  boolean  $dieOnError
     * @param  string   $msg
     * @param  bool     $execute    optional, false if we just want to return the query
     * @return resource query result
     */
    public function limitQuery($sql, $start, $count, $dieOnError = false, $msg = '', $execute = true)
    {
        $start = (int)$start;
        $count = (int)$count;

        $matches = array();
        $start = (int)$start;
        $count = (int)$count;
        preg_match('/^(.*SELECT)(.*?FROM.*WHERE)(.*)$/is',$sql, $matches);
        $GLOBALS['log']->debug('Limit Query:' . $sql. ' Start: ' .$start . ' count: ' . $count);
        if ($start ==0 && !empty($matches[3])) {
            $sql = 'SELECT /*+ FIRST_ROWS('. $count . ') */ * FROM (' . $matches[1]. $matches[2]. $matches[3] . ') MSI WHERE ROWNUM <= '.$count;
            if(!empty($GLOBALS['sugar_config']['check_query'])){
            	$this->checkQuery($sql);
         	}
         	if($execute) {
                return $this->query( $sql, $dieOnError, $msg);
         	} else {
         	    return $sql;
         	}
        }

        $start++; //count is 1 based.

        if($count != 1)
            $next = $start + $count -1;
        else
            $next=$start;

        if (!empty($matches[2])) {
            $sql = "SELECT /*+ FIRST_ROWS($count) */ * FROM (SELECT  MSI.*, ROWNUM as orc_row FROM (".$sql. ') MSI  WHERE ROWNUM <= '. $next . ') WHERE  orc_row >= ' . $start;
            if (!empty($GLOBALS['sugar_config']['check_query']))
                $this->checkQuery($sql);

         	if($execute) {
                return $this->query( $sql, $dieOnError, $msg);
         	} else {
         	    return $sql;
         	}
        }
        if (!empty($GLOBALS['sugar_config']['check_query']))
            $this->checkQuery($sql);

        $query = "SELECT * FROM (SELECT MSI.*, ROWNUM AS orc_row FROM ($sql) MSI where ROWNUM <= $next) WHERE orc_row >= $start";
        if ($execute)
            return $this->query($query, $dieOnError, $msg);

        return $query;
    }

    /**
     * @see DBManager::getFieldsArray()
     */
	public function getFieldsArray($result, $make_lower_case = false)
	{
		$field_array = array();

        if(! isset($result) || empty($result))
            return 0;

        $i = 1;
        $count = oci_num_fields($result);
        $count_tag = $count + 1;
        while ($i < $count_tag) {
            $meta = oci_field_name($result, $i);
            if (!$meta)
                return 0;
            if($make_lower_case==true)
                $meta = strtolower($meta);
            $field_array[] = $meta;

            $i++;
        }

        return $field_array;
    }

    /**
     * Get number of rows affected by last operation
     * @see DBManager::getAffectedRowCount()
     */
	public function getAffectedRowCount($result)
    {
        return oci_num_rows($result);
    }

    /**
     * Fetches the next row from the result set
     *
     * @param  resource $result result set
     * @return array
     */
    protected function ociFetchRow($result)
    {
        $row = oci_fetch_array($result, OCI_ASSOC|OCI_RETURN_NULLS|OCI_RETURN_LOBS);
        if (!$row) {
            // end of cursor, free this cursor
            $this->freeDbResult($result);
            return false;
        }
        if (!$this->checkError("Fetch error", false, $result)) {
            // make the column keys as lower case
            $row = array_change_key_case($row, CASE_LOWER);
        }
        else {
            $this->freeDbResult($result);
            return false;
        }

        return $row;
    }

	/**
	 * @see DBManager::fetchRow()
	 */
	public function fetchRow($result)
	{
		if (empty($result))	return false;

        return $this->ociFetchRow($result);
    }

    /**
     * @see DBManager::getTablesArray()
     */
    public function getTablesArray()
    {
        $GLOBALS['log']->debug('ORACLE fetching table list');

        if($this->getDatabase()) {
            $tables = array();
            $owner = strtoupper($this->configOptions['db_schema_name']);
            $query = 'SELECT TABLE_NAME FROM ALL_TABLES WHERE OWNER = ' . $this->quoted($owner);
            $r = $this->query($query);
            if (is_resource($r)) {
                while ($a = $this->fetchByAssoc($r))
                    $tables[] = strtolower($a['table_name']);

                return $tables;
            }
        }

        return false; // no database available
    }

    /**
     * {@inheritDoc}
     */
    public function tableExists($tableName)
    {
        $GLOBALS['log']->info("tableExists: $tableName");

        if ($this->getDatabase()){
            $query = 'SELECT TABLE_NAME
FROM ALL_TABLES
WHERE OWNER = ?
    AND TABLE_NAME = ?';

            $result = $this->getConnection()
                ->executeQuery($query, array(
                    strtoupper($this->configOptions['db_schema_name']),
                    strtoupper($tableName),
                ))->fetchColumn();

            return !empty($result);
        }

        return false;
    }

    /**
     * Get tables like expression
     * @param $like string
     * @return array
     */
    public function tablesLike($like)
    {
        if ($this->getDatabase()) {
            $tables = array();
            $owner = strtoupper($this->configOptions['db_schema_name']);
            $like = strtoupper($like);
            $sql = 'SELECT TABLE_NAME FROM ALL_TABLES'
                . ' WHERE OWNER = ' . $this->quoted($owner)
                . ' AND TABLE_NAME LIKE ' . $this->quoted($like);
            $r = $this->query($sql);
            if (!empty($r)) {
                while ($a = $this->fetchByAssoc($r)) {
                    $row = array_values($a);
					$tables[]=$row[0];
                }
                return $tables;
            }
        }
        return false;
    }

    /**
     * @see DBManager::quote()
     */
    public function quote($string)
    {
        if(is_array($string)) {
            return $this->arrayQuote($string);
        }
        return str_replace("'", "''", $this->quoteInternal($string));
    }

	/**
     * @see DBManager::connect()
     */
    public function connect(array $configOptions = null, $dieOnError = false)
    {
        global $sugar_config;

        if(!$configOptions)
			$configOptions = $sugar_config['dbconfig'];

        if (empty($configOptions['db_schema_name'])) {
            $configOptions['db_schema_name'] = $configOptions['db_user_name'];
        }

		$this->configOptions = $configOptions;
		if(!empty($configOptions['charset'])) {
		    $charset = $configOptions['charset'];
		} else {
		    $charset = $this->getOption('charset');
		}
		if(empty($charset)) {
		    $charset = "AL32UTF8";
		}
		if($this->getOption('persistent'))
		{
            $this->database = oci_pconnect($configOptions['db_user_name'], $configOptions['db_password'],$configOptions['db_name'], $charset);
            $err = oci_error();
            if ($err != false) {
	            $GLOBALS['log']->debug("oci_error:".var_export($err, true));
            }
		}

        if(!$this->database){
                $this->database = oci_connect($configOptions['db_user_name'],$configOptions['db_password'],$configOptions['db_name'], $charset);
                if (!$this->database) {
                	$err = oci_error();
                	if ($err != false) {
			            $GLOBALS['log']->debug("oci_error:".var_export($err, true));
                	}
                	$GLOBALS['log']->fatal("Could not connect to server ".$configOptions['db_name']." as ".$configOptions['db_user_name'].".");
                	if($dieOnError) {
                        if(isset($GLOBALS['app_strings']['ERR_NO_DB'])) {
                            sugar_die($GLOBALS['app_strings']['ERR_NO_DB']);
                        } else {
                            sugar_die("Could not connect to the database. Please refer to sugarcrm.log for details.");
                        }
                    } else {
                	    return false;
                	}
                }
                if($this->database && $this->getOption('persistent')){
                    $_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$sugar_config['dbconfigoption']['persistent'] to false in your config.php file</B>";
                }
        }
        //set oracle date format to be yyyy-mm-dd
        //settings for function based index.
            /* cn: This alters CREATE TABLE statements to explicitly create char-length varchar2() columns
             * at create time vs. byte-length columns.  the other option is to switch to nvarchar2()
             * which has char-length semantics by default.
             */
             $session_query = "alter session set
                nls_date_format = 'YYYY-MM-DD hh24:mi:ss'
                QUERY_REWRITE_INTEGRITY = TRUSTED
                QUERY_REWRITE_ENABLED = TRUE
                NLS_LENGTH_SEMANTICS=CHAR ";

        if (strcasecmp($configOptions['db_schema_name'], $configOptions['db_user_name']) != 0) {
            $session_query .= ' CURRENT_SCHEMA = ' . strtoupper($configOptions['db_schema_name']);
        }

            $collation = $this->getOption('collation');
            if(!empty($collation)) {
                $session_query .= "
            	NLS_COMP=LINGUISTIC
				NLS_SORT=$collation";
            } else if($this->getOption('enable_ci')) {
            	$session_query .= "
            	NLS_COMP=LINGUISTIC
				NLS_SORT=BINARY_CI";
            }
            $this->query($session_query);

		if(!$this->checkError('Could Not Connect', $dieOnError))
			$GLOBALS['log']->info("connected to db");

        $GLOBALS['log']->info("Connect:".$this->database);

        return true;
	}

    /**
     * Disconnects from the database
     *
     * Also handles any cleanup needed
     */
    public function disconnect()
    {
    	$GLOBALS['log']->debug('Calling Oracle::disconnect()');
        if(!empty($this->database)){
            $this->freeResult();
            oci_close($this->database);
            $this->database = null;
        }

        parent::disconnect();
    }

    /**
     * @see DBManager::freeDbResult()
     */
    protected function freeDbResult($dbResult)
    {
        if(is_resource($dbResult)) {
            oci_free_statement($dbResult);
        }
    }

	protected $date_formats = array(
        '%Y-%m-%d' => 'YYYY-MM-DD',
        '%Y-%m' => 'YYYY-MM',
        '%Y' => 'YYYY',
        '%v' => 'IW',
    );

	 /**
     * @see DBManager::convert()
     */
    public function convert($string, $type, array $additional_parameters = array())
    {
        if (!empty($additional_parameters)) {
            $additional_parameters_string = ','.implode(',',$additional_parameters);
        } else {
            $additional_parameters_string = '';
        }
        $all_parameters = $additional_parameters;
        if(is_array($string)) {
            $all_parameters = array_merge($string, $all_parameters);
        } elseif (!is_null($string)) {
            array_unshift($all_parameters, $string);
        }

        switch (strtolower($type)) {
            case 'date':
                return "to_date($string, 'YYYY-MM-DD')";
            case 'time':
                return "to_date($string, 'HH24:MI:SS')";
            case 'datetime':
            case 'datetimecombo':
                return "to_date($string, 'YYYY-MM-DD HH24:MI:SS'$additional_parameters_string)";
            case 'today':
                return "sysdate";
            case 'left':
                return "LTRIM($string$additional_parameters_string)";
            case 'date_format':
                if(!empty($additional_parameters[0]) && $additional_parameters[0][0] == "'") {
                    $additional_parameters[0] = trim($additional_parameters[0], "'");
                }
                if(!empty($additional_parameters) && isset($this->date_formats[$additional_parameters[0]])) {
                    $format = $this->date_formats[$additional_parameters[0]];
                    return "TO_CHAR($string, '$format')";
                } else {
                   return "TO_CHAR($string, 'YYYY-MM-DD')";
                }
            case 'time_format':
                if(empty($additional_parameters_string)) {
                    $additional_parameters_string = ",'HH24:MI:SS'";
                }
                return "TO_CHAR($string".$additional_parameters_string.")";
            case 'ifnull':
                if(empty($additional_parameters_string)) {
                    $additional_parameters_string = ",''";
                }
                return "NVL($string$additional_parameters_string)";
            case 'concat':
                return implode("||",$all_parameters);
            case 'text2char':
                return "to_char($string)";
            case 'quarter':
                return "TO_CHAR($string, 'Q')";
            case "length":
                return "LENGTH($string)";
            case 'month':
                return "TO_CHAR($string, 'MM')";
            case 'add_date':
                switch(strtolower($additional_parameters[1])) {
                    case 'quarter':
                        $additional_parameters[0] .= "*3";
                        // break missing intentionally
                    case 'month':
                        return "ADD_MONTHS($string, {$additional_parameters[0]})";
                    case 'week':
                        $additional_parameters[0] .= "*7";
                        // break missing intentionally
                    case 'day':
                        return "($string + $additional_parameters[0])";
                    case 'year':
                        return "ADD_MONTHS($string, {$additional_parameters[0]}*12)";
                }
                break;
            case 'add_time':
                return "$string + {$additional_parameters[0]}/24 + {$additional_parameters[1]}/1440";
            case 'add_tz_offset' :
                $getUserUTCOffset = $GLOBALS['timedate']->getUserUTCOffset();
                $operation = $getUserUTCOffset < 0 ? '-' : '+';
                return $string . ' ' . $operation . ' ' . abs($getUserUTCOffset) . '/1440';
            // Must implement AVG like this, because Oracle throws
            // ORA-24347: Warning of a NULL column in an aggregate function
            // when using count() with an aggregate function that is working on a field that has NULL values
            case 'avg':
                $avg = "
                    decode(
                        sum(nvl2($string, 1, 0)),
                        0,
                        0,
                        sum(nvl($string, 0)) / sum(nvl2($string, 1, 0))
                    )
                ";
                return $avg;
            case 'substr':
                return "substr($string, " . implode(', ', $additional_parameters) . ')';
            case 'round':
                return "round($string, " . implode(', ', $additional_parameters) . ')';
        }

        return $string;
    }

    /**
     * @see DBManager::fromConvert()
     */
    public function fromConvert($string, $type)
    {
        // YYYY-MM-DD HH:MM:SS
        switch($type) {
            case 'char': return rtrim($string, ' ');
            case 'date': return substr($string, 0, 10);
            case 'time': return substr($string, 11);
		}
		return $string;
    }

    protected function isNullable($vardef)
    {
        if(!empty($vardef['type']) && $this->isTextType($this->getFieldType($vardef))) {
            return false;
        }
		return parent::isNullable($vardef);
    }

    /**
     * @see DBManager::createTableSQLParams()
	 */
	public function createTableSQLParams($tablename, $fieldDefs, $indices)
    {
        $columns = $this->columnSQLRep($fieldDefs, false, $tablename);
        if(empty($columns))
 			return false;

        return "CREATE TABLE $tablename ($columns)";
	}

    /**
     * Does this type represent text (i.e., non-varchar) value?
     * @param string $type
     */
    public function isTextType($type)
    {
        $type = strtolower($type);
        $ctype = $this->getColumnType($type);
        return $type == 'clob' || $ctype == 'clob' || $type == 'blob' || $ctype == 'blob';
    }

    /**
     * Does this type represent blob value?
     *
     * @param string $type
     * @return bool
     */
    public function isBlobType($type)
    {
        $type = strtolower($type);
        return $type === 'blob' || $this->getColumnType($type) == 'blob';
    }

    /**
     * (non-PHPdoc)
     * @see DBManager::orderByEnum()
     */
    public function orderByEnum($order_by, $values, $order_dir)
    {
		$i = 0;
        $order_by_arr = array();
        $returnValue = '';
        foreach ($values as $key => $value) {
			array_push($order_by_arr, $this->quoted($key).", $i");
			$i++;
		}
        if (count($order_by_arr) > 0) {
            $returnValue = "DECODE($order_by, " . implode(',', $order_by_arr) . ", $i) $order_dir\n";
        }

        return $returnValue;
    }

    public function renameColumnSQL($tablename, $column, $newname)
    {
        return "ALTER TABLE $tablename RENAME COLUMN $column TO $newname";
    }

    /**
     * {@inheritDoc}
     */
    public function massageValue($val, $fieldDef, $forPrepared = false)
    {
        $type = $this->getFieldType($fieldDef);
        $ctype = $this->getColumnType($type);

        if (!$forPrepared) {
            if ($ctype == 'clob') {
                return "EMPTY_CLOB()";
            }
            if ($ctype == 'blob') {
                return "EMPTY_BLOB()";
            }
        }

        if($type == "date" && !empty($val)) {
            $val = explode(" ", $val); // make sure that we do not pass the time portion
            return parent::massageValue($val[0], $fieldDef, $forPrepared);            // get the date portion
        }

        return parent::massageValue($val, $fieldDef, $forPrepared);
    }

    /**
     * Generates set of queries to change column type via temporary column.
     * @param string $tablename Name of the table we are working with
     * @param array $oldColumn Old column metadata
     * @param array $newColumn New column metadata
     * @param bool $ignoreRequired
     * @return array
     */
    protected function alterVarchar2ToNumber($tablename, $oldColumn, $newColumn, $ignoreRequired)
    {
        $sql = array();
        $newColumn['name'] = 'tmp_' . mt_rand();

        $columnSQL = $this->changeOneColumnSQL($tablename, $newColumn, 'ADD', $ignoreRequired);
        $sql[] = "ALTER TABLE $tablename ADD $columnSQL";

        $sql[] = "UPDATE $tablename SET {$newColumn['name']} = {$oldColumn['name']}";

        $sql[] = "ALTER TABLE $tablename DROP COLUMN {$oldColumn['name']}";

        $sql[] = $this->renameColumnSQL($tablename, $newColumn['name'], $oldColumn['name']);
        return $sql;
    }

	/**
     * @see DBManager::oneColumnSQLRep()
     */
    protected function oneColumnSQLRep($fieldDef, $ignoreRequired = false, $table = '', $return_as_array = false)
    {
		//Bug 25814
		if(isset($fieldDef['name'])){
        	if(stristr($this->getFieldType($fieldDef), 'decimal') && isset($fieldDef['len'])){
				$fieldDef['len'] = min($fieldDef['len'],38);
			}
		}
        $type = $this->getFieldType($fieldDef);
        if ($this->isTextType($type) && isset($fieldDef['len'])) {
            unset($fieldDef['len']);
        }
		return parent::oneColumnSQLRep($fieldDef, $ignoreRequired, $table, $return_as_array);
	}

    /**
     * {@inheritdoc}
     */
    public function getDefaultFromDefinition($fieldDef)
    {
        $type = $this->getFieldType($fieldDef);
        if ($this->isTextType($type) && isset($fieldDef['default'])) {
            return " DEFAULT rawtohex({$this->quoted($fieldDef['default'])})";
        }
        return parent::getDefaultFromDefinition($fieldDef);
    }

	/**
	 * returns true if the field is nullable
	 *
	 * @param  string $tableName
	 * @param  string $fieldName
	 * @return bool
	 */
	protected function _isNullableDb($tableName, $fieldName)
	{
        $owner = strtoupper($this->configOptions['db_schema_name']);
        $tableName = strtoupper($tableName);
        $fieldName = strtoupper($fieldName);

        $query = 'SELECT NULLABLE FROM ALL_TAB_COLUMNS'
            . ' WHERE OWNER = ' . $this->quoted($owner)
            . ' AND TABLE_NAME = ' . $this->quoted($tableName)
            . ' AND COLUMN_NAME = ' . $this->quoted($fieldName);

        return strcmp($this->getOne($query), 'Y') == 0;
	}

	/**
	 * Split column type into components
	 * type proper, length and scale
	 * @param string $type
	 * @return array
	 */
	protected function splitType($type)
	{
	    $res = array('type' => $type);
	    if(preg_match('|(\w+)\((\d+),?(\d+)?\)|', $type, $match)) {
	        $res['type'] = $match[1];
	        $res['len'] = $match[2];
	        $res['type_len'] = $res['len'];
	        // have length
	        if(!empty($match[3])) {
	            $res['scale'] = $match[3];
	            $res['type_len'] = $res['len'].",".$res['scale'];
	        }
	    }
	    return $res;
	}

    /**
     * Condition for number type in oracle if it don't have precision and scale
     *
     * Oracle does not allow to shrink column sizes or decrease precision
     * if Precision and Scale of original col = 0 because number stored in database as it is
     *
     * @inheritdoc
     */
    public function compareVarDefs($fielddef1, $fielddef2, $ignoreName = false)
    {
        if (isset($fielddef1['type']) && isset($fielddef1['len']) && $fielddef1['type'] == 'number') {
            list($dblen, $dbprec) = $this->parseLenPrecision($fielddef1);
            if ($dblen == 38 && empty($dbprec) && $this->isNumericType($this->getFieldType($fielddef2))) {
                return true;
            }
        }
        return parent::compareVarDefs($fielddef1, $fielddef2, $ignoreName);
    }

	/**
	 * Generate modify statement for one column
	 * @param string $tablename
	 * @param array $fieldDef Vardef definition for field
	 * @param string $action
	 * @param bool $ignoreRequired
	 */
	protected function changeOneColumnSQL($tablename, $fieldDef, $action, $ignoreRequired = false)
	{
	    switch($action) {
	    	case 'DROP':
	    		return $fieldDef['name'];
	    		break;
	    	case 'ADD':
	    	    $colArray = $this->oneColumnSQLRep($fieldDef, $ignoreRequired, $tablename, true);
	    	    return "{$colArray['name']} {$colArray['colType']} {$colArray['default']} {$colArray['required']} {$colArray['auto_increment']}";
	    	case 'MODIFY':
	    		$colArray = $this->oneColumnSQLRep($fieldDef, $ignoreRequired, $tablename, true);
	    		$isNullable = $this->_isNullableDb($tablename,$colArray['name']);
	    		$nowCol = $this->describeField($fieldDef['name'], $tablename);
	    		if($colArray['colType'] == 'blob' || $colArray['colType'] == 'clob') {
	    			// Bug 42467: prevent Oracle from modifying *LOB fields
	    			if(empty($nowCol['type']) || $colArray['colType'] != $nowCol['type']) {
                        // we can't change type from lob, sorry
                        return '';
	    			}
	    			$colArray['colType'] = ''; // we don't change type, so omit it
	    		}

	    		$colData = $this->splitType($colArray['colType']);
	    		// Oracle does not allow to shrink column sizes or decrease precision
	    		// unless the column is empty
	    		if(!empty($colArray['colType']) && !empty($nowCol['type']) && $nowCol['type'] == $colData['type']
	    		        && !empty($colData['len'])              // if we don't define length, OK
	    		        && $nowCol['len'] != $colData['len']    // if it's the same length as it was, OK
	    		        && $nowCol['len'] != $colData['type_len'] // if it's the same length counting precision, OK
                ) {
	    		    // Precision/length handling
	    		    if(empty($nowCol['len'])) {
	    		        // if we had no length, strip it
	    		        $colArray['colType'] = $colData['type'];
	    		    } else {
    	    		    // We can increase length but not decrease it
                        $len2 = explode(",", $nowCol['len']);
                        $length = $len2[0];
                        if(!empty($len2[1])) { // case of 20,2
                            $scale = $len2[1];
                        } else {
                            $scale = 0;
                            $colData['scale'] = 0;
                        }
                        if($colData['len'] < $length) {
                            // we're attempting to decrease length, not allowed
                            $colData['len'] = $length;
                        }
                        if($colData['scale'] < $scale) {
                            // don't allow to reduce scale
                            $colData['scale'] = $scale;
                        }
                        if($colData['scale'] != 0) {
                            $colArray['colType']="{$colData['type']}({$colData['len']},{$colData['scale']})";
                        } else {
                            $colArray['colType']="{$colData['type']}({$colData['len']})";
                        }
	    		    }
	    		}


	            if(isset($nowCol['default']) && !isset($fieldDef['default'])) {
                    // removing default is allowed by changing to "DEFAULT NULL"
                    $colArray['default'] = "DEFAULT NULL";
                }
	    		if ( !$ignoreRequired && ( $isNullable == ( $colArray['required'] == 'NULL' ) ) )
	    			$colArray['required'] = '';

                // If we try to change column from/to completely different types (e.g. from varchar2 to number)
                // and the column affected has some data, let's do it via a separate method, otherwise we get ORA-01439.
                $precisionPattern = '/\([^)]*\)/';
                $oldType = !empty($nowCol['type']) ? $nowCol['type'] : '';
                // delete precision if exists - types with different precision are the same.
                $oldType = preg_replace($precisionPattern, '', $oldType);
                $newType = preg_replace($precisionPattern, '', $colData['type']);

                $alterMethod = 'alter' . ucfirst($oldType) . 'To' . ucfirst($newType);
                if (method_exists($this, $alterMethod)) {
                    return $this->$alterMethod($tablename, $nowCol, $fieldDef, $ignoreRequired);
                }

	    		return "{$colArray['name']} {$colArray['colType']} {$colArray['default']} {$colArray['required']} {$colArray['auto_increment']}";
	    }
        return '';
	}

	/**
     * @see DBManager::changeColumnSQL()
     *
     * Oracle's ALTER TABLE syntax is a bit different from the other rdbmss
     */
    protected function changeColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired = false)
    {
        $tablename = strtoupper($tablename);
        $action = strtoupper($action);

        $columns = "";
        if ($this->isFieldArray($fieldDefs)) {
            /**
             *jc: if we are dropping columns we do not need the
             * column definition data provided with the oneColumnSQLRep
             * method. instead we only need the column names.
             */
        	$addColumns = array();
			foreach($fieldDefs as $def) {
			    $col = $this->changeOneColumnSQL($tablename, $def, $action, $ignoreRequired);
			    if(!empty($col)) {
			        $addColumns[] = $col;
			    }
			}
            if(!empty($addColumns)) {
                     $columns = "(" . implode(",", $addColumns) . ")";
              } else {
                $columns = '';
          }
        } else {
            $columns = $this->changeOneColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired);

            if ($action == 'DROP') {
                $action = 'DROP COLUMN';
            }
        }

        if (is_array($columns)) {
            return $columns;
        }

        return ($columns == '' || empty($columns))
            ? ""
            : "ALTER TABLE $tablename $action $columns";
    }

	/**
     * @see DBManager::dropTableNameSQL()
     */
    public function dropTableNameSQL($name)
    {
		return parent::dropTableNameSQL(strtoupper($name));
    }

    /**
     * Truncate table
     * @param  $name
     * @return string
     */
    public function truncateTableSQL($name)
    {
        return "TRUNCATE TABLE $name";
    }

    /**
     * Fixes an Oracle index name
     *
     * Oracle has a strict limit on the size of object names (30 characters). errors will
     * occur if this is not checked. indexes should follow the naming convention as follows
     *
     *   idx_[table name]_[column_](_[column2] ...)
     *
     * and columns should be abbreviated by the first three letters or the following abbreviation
     * chart
     *
     * 		u = assigned user
     *		t = assigned team
     * 		d = deleted
     * 		n = name
     *
     * @param  string $name index name
     * @return string
     *
     * @deprecated
     */
    protected function fixIndexName($name)
    {
    	$result = $this->query(
            "SELECT COUNT(*) CNT
                FROM USER_INDEXES
                WHERE INDEX_NAME = '$name'
                    OR INDEX_NAME = '".strtoupper($name)."'");
		$row = $this->fetchByAssoc($result);
        $this->freeDbResult($result);
		return ($row['cnt'] > 1) ? $name . (intval($row['cnt']) + 1) : $name;
    }

    /**
     * Generates an index name for the repair table
     *
     * If the last character is not an 'r', make it that; else make it '1'
     *
     * @param  string $index_name
     * @return string
     */
	protected function repair_index_name($index_name)
    {
		$last_char='r';
		if (substr($index_name,strlen($index_name) -1,1) =='r')
			$last_char='1';

		return substr($index_name,0,strlen($index_name)-1). $last_char;
	}

    /**
     * @see DBManager::getAutoIncrement()
     */
    public function getAutoIncrement($table, $field_name)
    {
	    $currval = $this->getOne("SELECT max($field_name) currval FROM $table");
        if (!empty($currval))
            return $currval + 1 ;

        return "";
    }

	/**
     * @see DBManager::getAutoIncrementSQL()
     */
    public function getAutoIncrementSQL($table, $field_name)
    {
        return $this->_getSequenceName($table, $field_name, true) . '.nextval';
    }

    /**
     * @see DBManager::setAutoIncrement()
     */
    protected function setAutoIncrement($table, $field_name)
    {
        $this->deleteAutoIncrement($table, $field_name);
        $this->query(
            'CREATE SEQUENCE ' . $this->_getSequenceName($table, $field_name, true) .
            ' START WITH 0 increment by 1 nocache nomaxvalue minvalue 0'
        );
		$this->query(
            'SELECT ' . $this->_getSequenceName($table, $field_name, true) .
                '.NEXTVAL FROM DUAL');

        return "";
    }

    /**
     * Sets the next auto-increment value of a column to a specific value.
     *
     * @param  string $table tablename
     * @param  string $field_name
     */
    public function setAutoIncrementStart($table, $field_name, $start_value)
    {
    	$sequence_name = $this->_getSequenceName($table, $field_name, true);
    	$result = $this->query("SELECT {$sequence_name}.NEXTVAL currval FROM DUAL");
    	$row = $this->fetchByAssoc($result);
        // free statement
        $this->freeDbResult($result);
    	$current = $row['currval'];
    	$change = $start_value - $current - 1;
    	$this->query("ALTER SEQUENCE {$sequence_name} INCREMENT BY $change");
        $this->query("SELECT {$sequence_name}.NEXTVAL FROM DUAL");
        $this->query("ALTER SEQUENCE {$sequence_name} INCREMENT BY 1");

    	return true;
    }

	/**
     * @see DBManager::deleteAutoIncrement()
     */
    public function deleteAutoIncrement($table, $field_name)
    {
	  	$sequence_name = $this->_getSequenceName($table, $field_name, true);
	  	if ($this->_findSequence($sequence_name)) {
            $this->query('DROP SEQUENCE ' .$sequence_name);
        }
    }

    /** {@inheritDoc} */
    protected function get_index_data($table_name = null, $index_name = null)
    {
        $filterByTable = $table_name !== null;
        $filterByIndex = $index_name !== null;

        $columns = array();
        if (!$filterByTable) {
            $columns[] = 'i.table_name';
        }

        if (!$filterByIndex) {
            $columns[] = 'i.index_name';
        }

        $columns[] = 'i.index_type';
        $columns[] = 'c.constraint_type';
        $columns[] = 'ic.column_name';
        $columns[] = 'die.column_expression';

        $owner = strtoupper($this->configOptions['db_schema_name']);

        $query = 'SELECT ' . implode(', ', $columns) . '
FROM all_indexes i
INNER JOIN all_ind_columns ic
    ON ic.index_name = i.index_name
        AND ic.index_owner = i.owner
            AND ic.table_name = i.table_name
                AND ic.table_owner = i.table_owner
LEFT JOIN dba_ind_expressions die
    ON die.index_owner = i.owner
    AND die.index_name = i.index_name
    AND die.table_owner = i.table_owner
    AND die.table_name = i.table_name
    AND die.column_position = ic.column_position
LEFT JOIN all_constraints c
    ON c.index_name = i.index_name
        AND c.owner = i.owner';

        $query_owner = strtoupper($owner);
        $where = array(
            'i.table_owner = ?',
        );
        $params = array($query_owner);

        if ($filterByTable) {
            $where[] = 'i.table_name = ?';
            $params[] = strtoupper($table_name);
        }

        if ($filterByIndex) {
            $where[] = 'i.index_name = ?';
            $params[] = strtoupper($this->getValidDBName($index_name, true, 'index'));
        }

        $where[] = 'i.index_type IN (\'NORMAL\', \'FUNCTION-BASED NORMAL\')';
        $query .= ' WHERE ' . implode(' AND ', $where);

        $order = array();
        if (!$filterByTable) {
            $order[] = 'i.table_name';
        }

        if (!$filterByIndex) {
            $order[] = 'i.index_name';
        }

        $order[] = 'ic.column_position';
        $query .= ' ORDER BY ' . implode(', ', $order);

        $stmt = $this
            ->getConnection()
            ->executeQuery($query, $params);

        $data = array();
        while (($row = $stmt->fetch())) {
            if (!$filterByTable) {
                $table_name = strtolower($row['table_name']);
            }

            if (!$filterByIndex) {
                $index_name = strtolower($row['index_name']);
            }

            if ($row['constraint_type'] == 'P') {
                $type = 'primary';
            } elseif ($row['constraint_type'] == 'U') {
                $type = 'unique';
            } else {
                $type = 'index';
            }

            $data[$table_name][$index_name]['name'] = $index_name;
            $data[$table_name][$index_name]['type'] = $type;

            if ($row['index_type'] == 'FUNCTION-BASED NORMAL' && $row['column_expression']) {
                // oracle returns expressions with fields wrapped with '"'
                // we have to get rid of them to match the vardef index definitions
                $data[$table_name][$index_name]['fields'][] = strtolower(
                    preg_replace('/"(\w+)"/', '$1', $row['column_expression'])
                );
            } else {
                $data[$table_name][$index_name]['fields'][] = strtolower($row['column_name']);
            }
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function get_columns($tablename)
    {
        // Sanity check for getting columns
        if (empty($tablename)) {
            $this->log->error(__METHOD__ . ' called with an empty tablename argument');
            return array();
        }        

        $columns = array(
            'column_name',
            'data_type',
            'data_precision',
            'data_scale',
            'char_length',
            'data_default',
            'nullable'
        );

        $query = "SELECT "
            . implode(',', $columns)
            . ' FROM ALL_TAB_COLUMNS '
            . ' WHERE OWNER = ?'
            . ' AND TABLE_NAME = ?';

        $stmt = $this->getConnection()
            ->executeQuery($query, array(
                strtoupper($this->configOptions['db_schema_name']),
                strtoupper($tablename),
            ));

        $columns = array();
        while (($row = $stmt->fetch())) {
            $name = strtolower($row['column_name']);
            $columns[$name]['name']=$name;
            $columns[$name]['type']=strtolower($row['data_type']);
            if ( $columns[$name]['type'] == 'number' ) {
                $columns[$name]['len']=
                    ( !empty($row['data_precision']) ? $row['data_precision'] : '38');
                if ( !empty($row['data_scale']) )
                    $columns[$name]['len'].=','.$row['data_scale'];
            }
            elseif ( in_array($columns[$name]['type']
                ,array('date','clob','blob')) ) {
                // do nothing
            }
            else
                $columns[$name]['len']=strtolower($row['char_length']);
            if ( !empty($row['data_default']) ) {
                $matches = array();
                if ( preg_match("/^'(.*)'$/i",$row['data_default'],$matches) )
                    $columns[$name]['default'] = $matches[1];
            }

            $sequence_name = $this->_getSequenceName($tablename, $row['column_name'], true);
            if ($this->_findSequence($sequence_name))
                $columns[$name]['auto_increment'] = '1';
            elseif ( $row['nullable'] == 'N' )
                $columns[$name]['required'] = 'true';
        }
        return $columns;
    }

    /**
     * Returns true if the sequence name given is found
     *
     * @param  string $name
     * @return bool   true if the sequence is found, false otherwise
     * TODO: check if some caching here makes sense, keeping in mind bug 43148
     */
    protected function _findSequence($name)
    {
        $db_user_name = strtoupper(isset($this->configOptions['db_user_name'])?$this->configOptions['db_user_name']:'');

        $uname = strtoupper($name);
        $row = $this->fetchOne(
                "SELECT SEQUENCE_NAME FROM ALL_SEQUENCES WHERE SEQUENCE_OWNER='$db_user_name' AND SEQUENCE_NAME = '$uname'");
        return !empty($row);
    }

	/**
     * @see DBManager::add_drop_constraint()
     */
    public function add_drop_constraint($table, $definition, $drop = false)
    {
        $type         = $definition['type'];
        $fields       = is_array($definition['fields'])?implode(',',$definition['fields']):$definition['fields'];
        $name         = $this->getValidDBName($definition['name'], true, 'index');
        $sql          = '';

        /**
         * Oracle requires indices to be defined as ALTER TABLE statements except for PRIMARY KEY
         * and UNIQUE (which can defined inline with the CREATE TABLE)
         */
        switch ($type){
        // generic indices
        case 'index':
        case 'alternate_key':
        case 'clustered':
            if ($drop)
                $sql = "DROP INDEX {$name}";
            else
                $sql = "CREATE INDEX {$name} ON {$table} ({$fields})";
            break;
        // constraints as indices
        case 'unique':
            if ($drop)
                $sql = "ALTER TABLE {$table} DROP UNIQUE ({$fields})";
            else
                $sql = "ALTER TABLE {$table} ADD CONSTRAINT {$name} UNIQUE ({$fields})";
            break;
        case 'primary':
            if ($drop)
                $sql = "ALTER TABLE {$table} DROP PRIMARY KEY CASCADE";
            else
                $sql = "ALTER TABLE {$table} ADD CONSTRAINT {$name} PRIMARY KEY ({$fields})";
            break;
        case 'foreign':
            if ($drop)
                $sql = "ALTER TABLE {$table} DROP FOREIGN KEY ({$fields})";
            else
                $sql = "ALTER TABLE {$table} ADD CONSTRAINT {$name} FOREIGN KEY ({$fields}) REFERENCES {$definition['foreignTable']}({$definition['foreignField']})";
            break;
        case 'fulltext':
                if($drop) {
                    $sql = "DROP INDEX {$name}";
                } else {
                    $indextype=$definition['indextype'];
                    $parameters="";
                    //add parameters attribute if oracle version of 10 or more.
                    $ver = $this->version();
                    $tok = strtok($ver, '.');
                    if ($tok !== false && $tok > 9) {
                        $parameters = isset($definition['parameters'])
                            ? "parameters ('". $definition['parameters']. "')" : "";
                    }
                   $sql = "CREATE INDEX {$name} ON $table($fields) INDEXTYPE IS $indextype $parameters";
                }
                break;
        }
        return $sql;
	}

    /**
     * @see DBManager::renameIndexDefs()
     */
    public function renameIndexDefs($old_definition, $new_definition, $table_name)
    {
        $old_definition['name'] = $this->getValidDBName($old_definition['name'], true, 'index');
        $new_definition['name'] = $this->getValidDBName($new_definition['name'], true, 'index');
        return "ALTER INDEX {$old_definition['name']} RENAME TO {$new_definition['name']}";
    }

    /**
     * @see DBManager::massageFieldDef()
     */
    public function massageFieldDef(&$fieldDef, $tablename)
    {
        parent::massageFieldDef($fieldDef,$tablename);

        if (!empty($fieldDef['len'])) {
            return;
        }

        $columnType = $this->getColumnType($fieldDef['dbType']);

        $parts = $this->getTypeParts($columnType);

        if (isset($parts['len'])) {
            $len = $parts['len'];

            if (isset($parts['scale'])) {
                $len .= ',' . $parts['scale'];
            }

            $fieldDef['len'] = $len;
        }
    }

    /**
     * Generate an Oracle SEQUENCE name. If the length of the sequence names exceeds a certain amount
     * we will use an md5 of the field name to shorten.
     *
     * @param string $table
     * @param string $field_name
     * @param boolean $upper_case
     * @return string
     */
    protected function _getSequenceName($table, $field_name, $upper_case = true)
    {
        $sequence_name = $this->getValidDBName($table. '_' .$field_name . '_seq', true, 'index');
        if($upper_case)
            $sequence_name = strtoupper($sequence_name);
        return $sequence_name;
    }

    /**
     * {@inheritDoc}
     */
    public function emptyValue($type, $forPrepared = false)
    {
        $ctype = $this->getColumnType($type);
       	if($ctype == "datetime") {
   			return $forPrepared?"1970-01-01 00:00:00":$this->convert($this->quoted("1970-01-01 00:00:00"), "datetime");
   		}
   		if($ctype == "date") {
   			return $forPrepared?"1970-01-01":$this->convert($this->quoted("1970-01-01"), "date");
   		}
   		if($ctype == "time") {
   			return $forPrepared?"00:00:00":$this->convert($this->quoted("00:00:00"), "time");
   		}
        if($ctype == "clob") {
            return $forPrepared ? '' : 'EMPTY_CLOB()';
        }
        if($ctype == "blob") {
            return $forPrepared ? '' : 'EMPTY_BLOB()';
        }
        return parent::emptyValue($type, $forPrepared);
    }

    /**
     * (non-PHPdoc)
     * @see DBManager::lastDbError()
     */
    public function lastDbError()
    {
        $err = oci_error($this->database);
        if(is_array($err)) {
            return sprintf("Oracle ERROR %d: %s in %d of [%s]", $err['code'], $err['message'], $err['offset'], $err['sqltext']);
        }
        return false;
    }

    protected $oracle_privs = array(
        "CREATE TABLE" => "CREATE TABLE",
        "DROP TABLE" => "DROP ANY TABLE",
        "INSERT" => "INSERT ANY TABLE",
        "UPDATE" => "UPDATE ANY TABLE",
        "SELECT" => "SELECT ANY TABLE",
        "DELETE" => "DELETE ANY TABLE",
        "ADD COLUMN" => "ALTER ANY TABLE",
        "CHANGE COLUMN" => "ALTER ANY TABLE",
        "DROP COLUMN" => "ALTER ANY TABLE",
    );

    protected $is_express;

    /**
     * Check if we're running Oracle Express edition
     * @return bool
     */
    protected function isExpress()
    {
        if(!is_null($this->is_express)) return $this->is_express;
        $express = $this->getOne('SELECT BANNER AS B FROM V$VERSION WHERE BANNER LIKE \'%Express%\'');
        $this->is_express = !empty($express);
        return $this->is_express;
    }

    /**
     * Check if connecting user has certain privilege
     * @param string $privilege
     */
    public function checkPrivilege($privilege)
    {
        if($this->isExpress()) {
            return parent::checkPrivilege($privilege);
        }
        if(!isset($this->oracle_privs[$privilege])) {
            return parent::checkPrivilege($privilege);
        }

        $oracle_priv = $this->oracle_privs[$privilege];
        $res = $this->getOne("SELECT PRIVILEGE p FROM SESSION_PRIVS WHERE PRIVILEGE = '$oracle_priv'", false);
        return !empty($res);
    }

    public function getDbInfo()
    {
        return array(
            "Server version" => @oci_server_version($this->database),
            "Express" => $this->isExpress(),
        );
    }

    public function validateQuery($query)
    {
        $stmt = @oci_parse($this->database, $query);
        if(!$stmt) {
            return false;
        }
        if(@oci_statement_type($stmt) != "SELECT") {
            // free statement
            $this->freeDbResult($stmt);
            return false;
        }
        $valid = false;
        // try query, but don't generate result set and do not commit
        $res = @oci_execute($stmt, OCI_DESCRIBE_ONLY|OCI_DEFAULT);
        if(!empty($res)) {
            // check that we got good metadata
            $name = @oci_field_name($stmt, 1);
            if(!empty($name)) {
                $valid = true;
            }
        }

        // free stmt
        $this->freeDbResult($stmt);
        // just in case, rollback all changes
        @oci_rollback($this->database);
        return $valid;
    }

    /**
     * Quote Oracle search term
     * @param string $term
     * @return string
     */
    protected function quoteTerm($term)
    {
        $term = str_replace("*", "%", $term); // Oracle's wildcard is %
        return '{'.$term.'}';
    }

    /**
     * Generate fulltext query from set of terms
     * @param string $fields Field to search against
     * @param array $terms Search terms that may be or not be in the result
     * @param array $must_terms Search terms that have to be in the result
     * @param array $exclude_terms Search terms that have to be not in the result
     */
    public function getFulltextQuery($field, $terms, $must_terms = array(), $exclude_terms = array(), $label = 1)
    {
        $condition = $or_condition = $not_condition = array();
        foreach($must_terms as $term) {
            $condition[] = $this->quoteTerm($term);
        }

        foreach($terms as $term) {
            $or_condition[] = $this->quoteTerm($term);
        }

        if(!empty($or_condition)) {
            $condition[] = "(".join(" | ", $or_condition).")";
        }

        foreach($exclude_terms as $term) {
            $not_condition[] = " ~".$this->quoteTerm($term);
        }
        $condition = $this->quoted(join(" & ",$condition).join('', $not_condition));
        return "CONTAINS($field, $condition, $label) > 0";
    }

    /**
     * (non-PHPdoc)
     * @see DBManager::getScriptName()
     */
    public function getScriptName()
    {
        return "oracle";
    }

    /**
     * Execute data manipulation statement, then roll it back
     * @param  $type Statement type
     * @param  $table Table name
     * @param  $query Query to validate
     * @return string|bool String will be not empty if there's any
     */
    protected function verifyGenericQueryRollback($type, $table, $query)
    {
        $this->log->debug("verifying $type statement");
        $stmt = oci_parse($this->database, $query);
        if(!$stmt) {
            return 'Cannot parse statement';
        }
        if(oci_statement_type($stmt) != "SELECT") {
            // free statement
            $this->freeDbResult($stmt);
            return 'Wrong statement type';
        }
        // try query, but don't generate result set and do not commit
        $res = oci_execute($stmt, OCI_DESCRIBE_ONLY|OCI_DEFAULT);
        // just in case, rollback all changes
        $error = $this->lastError();

        // free the statement
        $this->freeDbResult($stmt);

        oci_rollback($this->database);
        if(empty($res)) {
            return 'Query failed to execute';
        }
        return $error;
    }

    /**
     * Tests an INSERT INTO query
     * @param string table The table name to get DDL
     * @param string query The query to test.
     * @return string Non-empty if error found
     */
    public function verifyInsertInto($table, $query)
    {
        return $this->verifyGenericQueryRollback("INSERT", $table, $query);
    }

    /**
     * Tests an UPDATE query
     * @param string table The table name to get DDL
     * @param string query The query to test.
     * @return string Non-empty if error found
     */
    public function verifyUpdate($table, $query)
    {
        return $this->verifyGenericQueryRollback("UPDATE", $table, $query);
    }

    /**
     * Tests an DELETE FROM query
     * @param string table The table name to get DDL
     * @param string query The query to test.
     * @return string Non-empty if error found
     */
    public function verifyDeleteFrom($table, $query)
    {
        return $this->verifyGenericQueryRollback("DELETE", $table, $query);
    }

    /**
     * Check if certain database exists
     * @param string $dbname
     */
    public function dbExists($dbname)
    {
        // We don't check DB in Oracle, admin creates it
        return true;
    }

    /**
     * Check if certain database exists
     * @param string $dbname
     */
    public function userExists($dbname)
    {
        // We don't check DB in Oracle, admin creates it
        return true;
    }

    /**
     * Create DB user
     * @param string $database_name
     * @param string $host_name
     * @param string $user
     * @param string $password
     */
    public function createDbUser($database_name, $host_name, $user, $password)
    {
        // We don't create users in Oracle, admin does that
        return true;
    }

    /**
     * Create a database
     * @param string $dbname
     */
    public function createDatabase($dbname)
    {
        // We don't create DBs in Oracle, admin does that
        return true;
    }

    /**
     * Drop a database
     * @param string $dbname
     */
    public function dropDatabase($dbname)
    {
        // // We don't create DBs in Oracle, admin does that
        return true;
    }

    /**
     * Check if this driver can be used
     * @return bool
     */
    public function valid()
    {
        return function_exists("ocilogon");
    }

    public function full_text_indexing_installed()
    {
        return true;
    }

    /**
     * Check if this DB name is valid
     *
     * @param string $name
     * @return bool
     */
    public function isDatabaseNameValid($name)
    {
        // No funny chars
        return preg_match('/[\#\"\'\*\\?\\<\>\ \&\!\(\)\[\]\{\}\;\,\`\~\|\\\\]+/', $name)==0;
    }

    /**
     * Check DB version
     * @see DBManager::canInstall()
     */
    public function canInstall()
    {
        $version = $this->version();
        if(empty($version)) {
            return array('ERR_DB_VERSION_FAILURE');
        }
        if (version_compare($version, '9', '<'))
        {
            return array('ERR_DB_OCI8_VERSION', $version);
        }
        return true;
    }

    public function installConfig()
    {
        return array(
        	'LBL_DBCONFIG_ORACLE' =>  array(
                "setup_db_database_name" => array("label" => 'LBL_DBCONF_DB_NAME', "required" => true),
                "setup_db_host_name" => false,
                'setup_db_create_sugarsales_user' => false,
            ),
			'LBL_DBCONFIG_B_MSG1' => array(
				"setup_db_admin_user_name" => array("label" => 'LBL_DBCONF_DB_ADMIN_USER', "required" => true),
				"setup_db_admin_password" => array("label" => 'LBL_DBCONF_DB_ADMIN_PASSWORD', "type" => "password"),
			),
        );
    }


    /**
     * Generates the a recursive SQL query or equivalent stored procedure implementation.
     * The DBManager's default implementation is based on SQL-99's recursive common table expressions.
     * Databases supporting recursive CTEs only need to set the recursive_query capability to true
     * @param string    $tablename       table name
     * @param string    $key             primary key field name
     * @param string    $parent_key      foreign key field name self referencing the table
     * @param string    $fields          list of fields that should be returned
     * @param bool      $lineage         find the lineage, if false, find the children
     * @param string    $startWith       identifies starting element(s) as in a where clause
     * @param string    $level           when not null returns a field named as level which indicates the level/dept from the starting point
     * @return string               Recursive SQL query or equivalent representation.
     */
    public function getRecursiveSelectSQL($tablename, $key, $parent_key, $fields, $lineage = false, $startWith = null, $level = null, $whereClause = null)
    {
        if($lineage) {
            $connectBy = "CONNECT BY $key = PRIOR $parent_key";  // Search up the tree to get lineage
        } else {
            $connectBy = "CONNECT BY $parent_key = PRIOR $key";  // Search down the tree to find children
        }

        if(!empty($startWith)) {
            $startWith = 'START WITH ' . $startWith;
        } else {
            $startWith = '';
        }

        if(!empty($level)) {
            $fields = "$fields, LEVEL as $level";
        }

        // cleanup WHERE clause
        if (empty($whereClause)) {
			 $whereClause = '';
		}
		else {
			$whereClause = ltrim($whereClause);
			if (strtoupper(substr($whereClause, 1, 5)) == 'WHERE' ) {   // remove WHERE
				$whereClause = substr($whereClause, 6);
            }
            if (strtoupper(substr($whereClause, 1, 4)) != 'AND ' ) {  // Add AND
                $whereClause = "AND $whereClause";
            }
            $whereClause .= ' ';  // make sure there is a trailing blank
		}

        return "SELECT $fields FROM $tablename $startWith $whereClause $connectBy $whereClause";
    }


    /*
     * Returns a DB specific FROM clause which can be used to select against functions.
     * Note that depending on the database that this may also be an empty string.
     * @return string
     */
    public function getFromDummyTable()
    {
        return "from dual";
    }

    /**
     * Returns a DB specific piece of SQL which will generate GUID (UUID)
     * This string can be used in dynamic SQL to do multiple inserts with a single query.
     * I.e. generate a unique Sugar id in a sub select of an insert statement.
     * @return string
     */
    public function getGuidSQL()
    {
        $guidStart = create_guid_section(3);
      	return "'$guidStart-' || sys_guid()";
    }

    /**
     * @inheritdoc
     */
    protected function massageIndexDefs($fieldDefs, $indices)
    {
        $indices = parent::massageIndexDefs($fieldDefs, $indices);
        return array_merge($indices, $this->generateCaseInsensitiveIndices($fieldDefs, $indices));
    }

    /**
     * Generate indices to support case-insensitive search as oracle
     * does not support case insensitive collation
     *
     * @param $fieldDefs
     * @param $indices
     * @return array
     */
    protected function generateCaseInsensitiveIndices($fieldDefs, $indices)
    {
        $result = array();

        foreach ($indices as $key => $index) {
            // skip if it's primary or unique index as they can't be function-based
            if ($index['type'] != 'index') {
                continue;
            }

            if (!is_array($index['fields'])) {
                $index['fields'] = array($index['fields']);
            }

            $wrappedFields = array();
            $hasWrappedFields = false;
            foreach ($index['fields'] as $field) {
                $fieldDef = isset($fieldDefs[$field]) ? $fieldDefs[$field] : false;

                // skip indices with non-db fields
                if ($fieldDef && isset($fieldDef['source']) && $fieldDef['source'] == 'non-db') {
                    continue 2;
                }

                if ($fieldDef
                    && !in_array($fieldDef['type'], array('id', 'enum'))
                    && $this->getTypeClass($this->getFieldType($fieldDef)) == 'string'
                ) {
                    $wrappedFields[] = 'UPPER(' . $field . ')';
                    $hasWrappedFields = true;
                } else {
                    $wrappedFields[] = $field;
                }
            }

            if ($hasWrappedFields) {
                $index['name'] = $index['name'] . '_ci';
                $index['fields'] = $wrappedFields;
                $result[] = $index;
            }
        }

        return $result;
    }
}
