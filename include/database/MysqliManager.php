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

* Description: This file handles the Data base functionality for the application.
* It acts as the DB abstraction layer for the application. It depends on helper classes
* which generate the necessary SQL. This sql is then passed to PEAR DB classes.
* The helper class is chosen in DBManagerFactory, which is driven by 'db_type' in 'dbconfig' under config.php.
*
* All the functions in this class will work with any bean which implements the meta interface.
* The passed bean is passed to helper class which uses these functions to generate correct sql.
*
* The meta interface has the following functions:
* getTableName()                Returns table name of the object.
* getFieldDefinitions()         Returns a collection of field definitions in order.
* getFieldDefintion(name)       Return field definition for the field.
* getFieldValue(name)           Returns the value of the field identified by name.
*                               If the field is not set, the function will return boolean FALSE.
* getPrimaryFieldDefinition()   Returns the field definition for primary key
*
* The field definition is an array with the following keys:
*
* name      This represents name of the field. This is a required field.
* type      This represents type of the field. This is a required field and valid values are:
*           �   int
*           �   long
*           �   varchar
*           �   text
*           �   date
*           �   datetime
*           �   double
*           �   float
*           �   uint
*           �   ulong
*           �   time
*           �   short
*           �   enum
* length    This is used only when the type is varchar and denotes the length of the string.
*           The max value is 255.
* enumvals  This is a list of valid values for an enum separated by "|".
*           It is used only if the type is �enum�;
* required  This field dictates whether it is a required value.
*           The default value is �FALSE�.
* isPrimary This field identifies the primary key of the table.
*           If none of the fields have this flag set to �TRUE�,
*           the first field definition is assume to be the primary key.
*           Default value for this field is �FALSE�.
* default   This field sets the default value for the field definition.
*
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/


/**
 * MySQL manager implementation for mysqli extension
 */
class MysqliManager extends MysqlManager
{
	/**
	 * @see DBManager::$dbType
	 */
	public $dbType = 'mysql';
	public $variant = 'mysqli';
	public $priority = 10;
	public $label = 'LBL_MYSQLI';

    /**
     * Array of options used for mysqli::real_connect()
     * @var array
     */
    protected $connectOptions = array();

    /**
     * Connection status flag
     * @var bool
     */
    protected $connected = false;

    /**
     * Create DB Driver
     */
	public function __construct()
	{
        $this->capabilities["recursive_query"] = true;
        $this->capabilities["ssl"] = true;
        parent::__construct();
	}

	/**
	 * @see DBManager::$backendFunctions
	 */
	protected $backendFunctions = array(
		'free_result'        => 'mysqli_free_result',
		'close'              => 'mysqli_close',
		'row_count'          => 'mysqli_num_rows',
		'affected_row_count' => 'mysqli_affected_rows',
		);

    public function query($sql, $dieOnError = false, $msg = '', $suppress = false, $keepResult = false)
    {
        $result = $this->queryMulti($sql, $dieOnError, $msg, $suppress, $keepResult, false);

        return $result;
    }



    /**
     * @see MysqlManager::query()
     */
    protected function queryMulti($sql, $dieOnError = false, $msg = '', $suppress = false, $keepResult = false, $multiquery = true)
    {
        if(is_array($sql)) {
            return $this->queryArray($sql, $dieOnError, $msg, $suppress);    //queryArray does not support any return sets
        }

        parent::countQuery($sql);
        $this->log->info('Query:' . $sql);
        $this->checkConnection();
        $this->query_time = microtime(true);
        $this->lastsql = $sql;

        if ($multiquery) {
            $query_result = $suppress?@mysqli_multi_query($this->database,$sql):mysqli_multi_query($this->database,$sql);
            $result = mysqli_use_result($this->database);

            // Clear any remaining recordsets
            while (mysqli_more_results($this->database) && mysqli_next_result($this->database)) {
                $tmp_result = mysqli_use_result($this->database);
                mysqli_free_result($tmp_result);
            }
        }

        else
            $result = $suppress?@mysqli_query($this->database,$sql):mysqli_query($this->database,$sql);

        $this->query_time = microtime(true) - $this->query_time;
        $this->log->info('Query Execution Time:'.$this->query_time);

        // slow query logging
        $this->dump_slow_queries($sql);

		if($keepResult) {
			$this->lastResult = $result;
        }

        if ($this->database && mysqli_errno($this->database) == 2006 && $this->retryCount < 1) {
            $this->log->fatal('mysqli has gone away, retrying');
            $this->retryCount++;
            $this->disconnect();
            $this->connect();
            return $this->query($sql, $dieOnError, $msg, $suppress, $keepResult);
        } else {
            $this->retryCount = 0;
        }

        $this->checkError($msg.' Query Failed: ' . $sql, $dieOnError);
        return $result;
    }

    /**
	 * Returns the number of rows affected by the last query
	 *
	 * @return int
	 */
	public function getAffectedRowCount($result)
	{
		return mysqli_affected_rows($this->getDatabase());
	}

	/**
	 * Returns the number of rows returned by the result
	 *
	 * This function can't be reliably implemented on most DB, do not use it.
	 * @abstract
	 * @deprecated
	 * @param  resource $result
	 * @return int
	 */
	public function getRowCount($result)
	{
	    return mysqli_num_rows($result);
	}


    /**
     * Disconnects from the database
     *
     * Also handles any cleanup needed
     */
    public function disconnect()
    {
        $this->log->debug('Calling MySQLi::disconnect()');

        if (!empty($this->database)) {
            $this->freeResult();
            mysqli_close($this->database);
            $this->database = null;
        }

        $this->connected = false;

        parent::disconnect();
    }

	/**
	 * @see DBManager::freeDbResult()
	 */
	protected function freeDbResult($dbResult)
	{
		if(is_resource($dbResult))
			mysqli_free_result($dbResult);
	}

	/**
	 * @see DBManager::getFieldsArray()
	 */
	public function getFieldsArray($result, $make_lower_case = false)
	{
		$field_array = array();

		if (!isset($result) || empty($result))
			return 0;

		$i = 0;
		while ($i < mysqli_num_fields($result)) {
			$meta = mysqli_fetch_field_direct($result, $i);
			if (!$meta)
				return 0;

			if($make_lower_case == true)
				$meta->name = strtolower($meta->name);

			$field_array[] = $meta->name;

			$i++;
		}

		return $field_array;
	}

	/**
	 * @see DBManager::fetchRow()
	 */
	public function fetchRow($result)
	{
		if (empty($result))	return false;

		$row = mysqli_fetch_assoc($result);
		if($row == null) $row = false; //Make sure MySQLi driver results are consistent with other database drivers
		return $row;
	}

	/**
	 * @see DBManager::quote()
	 */
	public function quote($string)
	{
		return mysqli_real_escape_string($this->getDatabase(),$this->quoteInternal($string));
	}

    /**
     * {@inheritdoc}
     */
    public function connect(array $configOptions = null, $dieOnError = false)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->initDatabase();
        $this->setupConnectOptions($configOptions);

        if (!$this->connected) {
            try {
              $this->connected = mysqli_real_connect(
                  $this->database,
                  $this->connectOptions['db_host_name'],
                  $this->connectOptions['db_user_name'],
                  $this->connectOptions['db_password'],
                  $this->connectOptions['db_name'],
                  $this->connectOptions['db_port'],
                  $this->connectOptions['db_socket'],
                  $this->connectOptions['db_client_flags']
              );
            } catch (mysqli_sql_exception $e) {
                $message = "Could not connect to DB server with options ".
                $this->connectOptions['db_host_name']." as ".
                $this->connectOptions['db_user_name'].". port " .
                $this->connectOptions['db_port'].": ".$e->getMessage();

                $this->registerError('', $message, $dieOnError);
                return false;
            }
        }
        
        if (!empty($this->connectOptions['db_name'])) {
            try {
                $this->selectDb($this->connectOptions['db_name']);
            } catch (mysqli_sql_exception $e) {
                $this->registerError('', "Unable to select database ".$this->connectOptions['db_name'].": ".$e->getMessage(), $dieOnError);
                return false;
            }
        }

        $this->setCharset();

        if ($this->checkError('Could Not Connect', $dieOnError)) {
            $this->log->info("connected to db");
        }

        mysqli_report(MYSQLI_REPORT_OFF);
        return true;
    }

    /**
     * Setup a database with mysqli::init()
     */
    protected function initDatabase()
    {
        if (empty($this->database)) {            
            $this->database = mysqli_init();
        }
    }

    /**
     * Setup a database connection params
     *
     * configOptions must include
     * db_host_name - server ip
     * db_user_name - database user name
     * db_password - database password
     *
     * @param array   $configOptions
     */
    protected function setupConnectOptions(array $configOptions = null)
    {
        if (is_null($configOptions)) {            
            $this->connectOptions = SugarConfig::getInstance()->get('dbconfig');
        } else {
            $this->connectOptions = $configOptions;
        }

        if (empty($this->connectOptions['db_port'])) { // '' case
            $this->connectOptions['db_port'] = null;
        }
        $pos = strpos($this->connectOptions['db_host_name'],':');
        if ($pos !== false) {
            $dbHostName = $this->connectOptions['db_host_name'];
            //mysqli connector has a separate parameter for port.. We need to separate it out from the host name
            $this->connectOptions['db_host_name'] = substr($dbHostName, 0, $pos);
            $this->connectOptions['db_port'] = substr($dbHostName, $pos+1);
        }

        if (ini_get('mysqli.allow_persistent') && $this->getOption('persistent')) {
            $this->connectOptions['db_host_name'] = "p:" . $this->connectOptions['db_host_name'];
        }

        if (!isset($this->connectOptions['db_name'])) {
            $this->connectOptions['db_name'] = '';
        }

        if (!isset($this->connectOptions['db_socket'])) {
            $this->connectOptions['db_socket'] = null;
        }

        if (!isset($this->connectOptions['db_client_flags'])) {
            $this->connectOptions['db_client_flags'] = 0;
        }

        if ($this->getOption('ssl')) {
            $this->setupSSL();
        }
    }

    /**
     * Setup a SSL connection params
     *
     * If SSL options are provided use them with mysqli::ssl_set() or just set client flags to MYSQLI_CLIENT_SSL
     *
     * @param array   $configOptions
     */
    protected function setupSSL() 
    {
        $sslOptions = $this->getOption('ssl_options');

        if (isset($sslOptions['ssl_ca']) && $sslOptions['ssl_ca']) {
            mysqli_ssl_set($this->database,
                isset($sslOptions['ssl_key']) ? $sslOptions['ssl_key'] : null,
                isset($sslOptions['ssl_cert']) ? $sslOptions['ssl_cert'] : null,
                isset($sslOptions['ssl_ca'])  ? $sslOptions['ssl_ca'] : null,
                isset($sslOptions['ssl_capath']) ? $sslOptions['ssl_capath'] : null,
                isset($sslOptions['ssl_cipher']) ? $sslOptions['ssl_cipher'] : null
            );
        } else {
            $this->connectOptions['db_client_flags'] = $this->connectOptions['db_client_flags'] | MYSQLI_CLIENT_SSL;
        }
    }

    /**
     * Setup character set and collation
     */
    protected function setCharset()
    {
        // cn: using direct calls to prevent this from spamming the Logs
        mysqli_query($this->getDatabase(),"SET CHARACTER SET utf8");
        $names = "SET NAMES 'utf8'";
        $collation = $this->getOption('collation');
        if (!empty($collation)) {
            $names .= " COLLATE " . $this->quoted($collation);
        }
        mysqli_query($this->getDatabase(),$names);
    }

	/**
	 * (non-PHPdoc)
	 * @see MysqlManager::lastDbError()
	 */
	public function lastDbError()
	{
		if($this->database) {
		    if(mysqli_errno($this->database)) {
			    return "MySQL error ".mysqli_errno($this->database).": ".mysqli_error($this->database);
		    }
		} else {
			$err =  mysqli_connect_error();
			if($err) {
			    return $err;
			}
		}

		return false;
	}

	public function getDbInfo()
	{
        $charsets = $this->getCharsetInfo();
        $charset_str = array();
        foreach($charsets as $name => $value) {
            $charset_str[] = "$name = $value";
        }
        $return = array(
            'MySQLi Version' => 'info is not present',
            'MySQLi Host Info' => 'info is not present',
            'MySQLi Server Info' => 'info is not present',
            'MySQLi Client Encoding' => 'info is not present',
            'MySQL Character Set Settings' => implode(', ', $charset_str),
        );
        if (function_exists('mysqli_get_client_info')) {
            $return['MySQLi Version'] = @mysqli_get_client_info();
        }
        if (function_exists('mysqli_get_host_info')) {
            $return['MySQLi Host Info'] = @mysqli_get_host_info($this->database);
        }
        if (function_exists('mysqli_get_server_info')) {
            $return['MySQLi Server Info'] = @mysqli_get_server_info($this->database);
        }
        if (function_exists('mysqli_client_encoding')) {
            $return['MySQLi Client Encoding'] = @mysqli_client_encoding($this->database);
        }
        return $return;
	}

	/**
	 * Select database
	 * @param string $dbname
	 */
	protected function selectDb($dbname)
	{
		return mysqli_select_db($this->getDatabase(), $dbname);
	}

	/**
	 * Check if this driver can be used
	 * @return bool
	 */
	public function valid()
	{
        return function_exists('mysqli_connect');
	}


    /**
     * Create or updates the stored procedures for the recursive query capabilities
     * @return resource
     */
    public function createRecursiveQuerySPs()
    {

        $dropRecursiveQuerySPs_statement = "DROP PROCEDURE IF EXISTS _hierarchy";
        $this->query($dropRecursiveQuerySPs_statement);

        $createRecursiveQuerySPs_statement = "
            CREATE PROCEDURE _hierarchy( p_tablename              VARCHAR(100)
                                       , p_key_column             VARCHAR(100)
                                       , p_parent_key_column      VARCHAR(100)
                                       , p_mode                   VARCHAR(100)
                                       , p_startWith              VARCHAR(250)
                                       , p_level                  VARCHAR(100)    -- not used
                                       , p_fields                 VARCHAR(250)
                                       , p_where_clause           VARCHAR(250)
                                       )
            root:BEGIN

               DECLARE _level             INT;
               DECLARE _last_row_count    INT;

               CREATE TEMPORARY TABLE IF NOT EXISTS _hierarchy_return_set (
                      _id          VARCHAR(100)
                    , _parent_id   VARCHAR(100)
                    , _level       INT
                    , INDEX(_id, _level)
                    , INDEX(_parent_id, _level)
               );

               CREATE TEMPORARY TABLE  IF NOT EXISTS _hierarchy_current_set (
                      _id          VARCHAR(100)
                    , _parent_id   VARCHAR(100)
                    , _level       INT
               );

               SET _level := 1;
               TRUNCATE TABLE _hierarchy_return_set;
               TRUNCATE TABLE _hierarchy_current_set;

               -- cleanup WHERE clause
               IF LENGTH(TRIM(p_where_clause)) = 0 THEN
                  SET p_where_clause := NULL;
               END IF;
               IF p_where_clause IS NOT NULL THEN
                  SET p_where_clause := LTRIM(p_where_clause);
                  IF UPPER(SUBSTR(p_where_clause, 1, 5)) = 'WHERE' THEN  -- remove WHERE
                     SET p_where_clause := LTRIM(SUBSTR(p_where_clause, 6));
                  END IF;
                  IF UPPER(SUBSTR(p_where_clause, 1, 4)) <> 'AND ' THEN -- Add AND
                     SET p_where_clause := CONCAT('AND ', p_where_clause);
                  END IF;
               END IF;

               -- Get StartWith records
               SET @_sql = CONCAT( 'INSERT INTO  _hierarchy_current_set( _id, _parent_id, _level ) '
                                 ,'     SELECT  ', p_key_column, ', ', p_parent_key_column, ', ', _level
                                 ,'       FROM  ', p_tablename
                                 ,'      WHERE  ', p_startWith, ' '
                                 , IFNULL( p_where_clause, '' )
                                );
               PREPARE stmt FROM @_sql;
               EXECUTE stmt;
               SET _last_row_count = ROW_COUNT();


               -- Create the statement to get the next set of data
               IF p_mode = 'D' THEN -- Down the tree

                  SET @_sql = CONCAT( 'INSERT INTO  _hierarchy_current_set'
                                     ,'            ( _id, _parent_id, _level )'
                                     ,'    SELECT  ', p_key_column, ', ', p_parent_key_column, ', ', ' @_curr_level'
                                     ,'      FROM  ', p_tableName, ' t, _hierarchy_return_set hrs '
                                     ,'     WHERE  t.', p_parent_key_column, ' = hrs._id '                -- The Parent - Child equijoin
                                     ,'       AND  hrs._level = @_last_level  '
                                     , IFNULL( p_where_clause, '' )
                                     ,';'
                                    );
                  -- SELECT 'Down Tree Insert: ', @_sql;

               ELSEIF p_mode = 'U' THEN
                  SET @_sql = CONCAT( 'INSERT INTO  _hierarchy_current_set'
                                     ,'            ( _id, _parent_id, _level )'
                                     ,'    SELECT  ', p_key_column, ', ', p_parent_key_column, ', ', ' @_curr_level'
                                     ,'      FROM  ', p_tableName, ' t, _hierarchy_return_set hrs '
                                     ,'     WHERE  t.', p_key_column, ' = hrs._parent_id '                -- The Parent - Child equijoin
                                     ,'       AND  hrs._level = @_last_level   '
                                     , IFNULL( p_where_clause, '' )
                                     ,';'
                                    );

                  -- SELECT 'Up Tree Insert: ', @_sql;

               ELSE  -- Unknown mode, abort
                  LEAVE root;
               END IF;

               PREPARE next_recs_stmt FROM @_sql;

               -- loop recursively finding parents/children
               WHILE  ( _last_row_count > 0)
               DO
                  SET _level = _level+1;

                  INSERT INTO _hierarchy_return_set
                       SELECT *
                         FROM _hierarchy_current_set;

                  TRUNCATE TABLE _hierarchy_current_set;

                  SET @_last_level := _level-1;
                  SET @_curr_level := _level;

                  EXECUTE next_recs_stmt;
                  SET _last_row_count := ROW_COUNT();

               END WHILE;

               INSERT INTO _hierarchy_return_set
                    SELECT *
                      FROM _hierarchy_current_set;

            END;
        ";
        $this->query($createRecursiveQuerySPs_statement);
        return true;
    }


    public function preInstall()
    {
        $this->createRecursiveQuerySPs();
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
        $mode = ($lineage) ? 'U' : 'D';
        // First execute the stored procedure to load the _hierarchy_return_set with the hierarchy data

        $startWith = is_null($startWith) ? '' : $this->quote($startWith);
        $level = is_null($level) ? '' : $level;
        $whereClause = is_null($whereClause) ? '' : $this->quote($whereClause);
        $sql_sp = "CALL _hierarchy('$tablename', '$key', '$parent_key', '$mode', '{$startWith}', '$level', '$fields', '{$whereClause}')";
        $result = $this->queryMulti($sql_sp, false, false, false, true);

        // Now build the sql to return that allows the caller to execute sql in a way to simulate the CTE of the other dbs,
        // i.e. return sql that is a combination of the callers sql and a join against the temp hierarchy table
        $sql = "SELECT $fields FROM _hierarchy_return_set hrs INNER JOIN $tablename t ON hrs._id = t." ."$key";
        $sql = "$sql ORDER BY hrs._level";  // try and mimic other DB return orders for consistency. breaks unit test otherwise
        return $sql;
    }
}
