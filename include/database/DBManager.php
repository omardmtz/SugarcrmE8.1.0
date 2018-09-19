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

use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;
use Sugarcrm\Sugarcrm\Util\Uuid;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Base database driver implementation.
 *
 * This class handles the Data base functionality for the application.
 * It acts as the DB abstraction layer for the application. It depends on
 * helper classes which generate the necessary SQL.
 * The helper class is chosen in DBManagerFactory, which is driven by `db_type`
 * in `dbconfig` under `config.php`.
 *
 * All the functions in this class will work with any bean which implements the
 * meta interface.
 * The passed bean is passed to helper class which uses these functions to
 * generate correct sql.
 *
 * FIXME move this to an interface instead of having this documented here
 * The meta interface has the following functions:
 * getTableName()                Returns table name of the object.
 * getFieldDefinitions()         Returns a collection of field definitions in order.
 * getFieldDefinition(name)      Return field definition for the field.
 * getFieldValue(name)           Returns the value of the field identified by name.
 *                               If the field is not set, the function will return boolean FALSE.
 * getPrimaryFieldDefinition()   Returns the field definition for primary key
 *
 * The field definition is an array with the following keys:
 *
 * name      This represents name of the field. This is a required field.
 * type      This represents type of the field. This is a required field and valid values are:
 *   - int
 *   - long
 *   - varchar
 *   - text
 *   - date
 *   - datetime
 *   - double
 *   - float
 *   - uint
 *   - ulong
 *   - time
 *   - short
 *   - enum
 * length    This is used only when the type is varchar and denotes the length of the string.
 *           The max value is 255.
 * enumvals  This is a list of valid values for an enum separated by "|".
 *           It is used only if the type is `enum`;
 * required  This field dictates whether it is a required value.
 *           The default value is `FALSE`.
 * isPrimary This field identifies the primary key of the table.
 *           If none of the fields have this flag set to `TRUE`,
 *           the first field definition is assume to be the primary key.
 *           Default value for this field is `FALSE`.
 * default   This field sets the default value for the field definition.
 * @api
 */
abstract class DBManager
{
    /**
     * @var string
     */
    public $dbType;

    /**
     * @var string
     */
    public $variant;

	/**
	 * Actual database link, used in concrete classes
	 * @var resource
	 */
	public $database = null;

	/**
	 * Indicates whether we should die when we get an error from the DB
	 */
	protected $dieOnError = false;

	/**
	 * Indicates whether we should html encode the results from a query by default
	 */
	protected $encode = true;

	/**
	 * Records the execution time of the last query
	 */
	public $query_time = 0;

	/**
	 * Last error message from the DB backend
	 */
	protected $last_error = false;

	/**
	 * Registry of available result sets
	 */
	protected $lastResult;

	/**
	 * Current query count
	 */
	private static $queryCount = 0;

	/**
	 * Query threshold limit
	 */
	private static $queryLimit = 0;

	/**
	 * Array of prepared statements and their correspoding parsed tokens
	 */
	protected $preparedTokens = array();

	/**
	 * TimeDate instance
	 * @var TimeDate
	 */
	protected $timedate;

	/**
	 * PHP Logger
	 * @var Logger
	 */
	protected $log;

    /**
     * @var Request
     */
    protected $request;

	/**
	 * Table descriptions
	 * @var array
	 */
	protected static $table_descriptions = array();

	/**
	 * Index descriptions
	 * @var array
	 */
	protected static $index_descriptions = array();

	/**
	 * Maximum length of identifiers
	 * @abstract
	 * @var array
	 */
	protected $maxNameLengths = array(
		'table' => 64,
		'column' => 64,
		'index' => 64,
		'alias' => 64
	);

	/**
	 * DB driver priority
	 * Higher priority drivers override lower priority ones
	 * @var int
	 */
	public $priority = 0;

	/**
	 * Driver name label, for install
	 * @absrtact
	 * @var string
	 */
	public $label = '';

	/**
	 * Type names map
	 * @abstract
	 * @var array
	 */
	protected $type_map = array();

    /**
     * Type min:max value
     * @abstract
     * @var array
     */
    protected $type_range = array();

	/**
	 * Type classification into:
	 * - int
	 * - bigint
	 * - bool
	 * - float
	 * - date
	 * - time
	 * @var array
	 */
	protected $type_class = array(
			'int'      => 'int',
			'integer'  => 'int',
			'double'   => 'float',
			'float'    => 'float',
			'uint'     => 'int',
			'ulong'    => 'bigint',
			'long'     => 'bigint',
			'bigint'   => 'bigint',
			'short'    => 'int',
			'date'     => 'date',
			'datetime' => 'date',
			'datetimecombo' => 'date',
			'time'     => 'time',
			'bool'     => 'bool',
			'tinyint'  => 'int',
			'currency' => 'float',
			'decimal'  => 'float',
			'decimal2' => 'float',
	        'decimal_tpl' => 'float',
	);

	/**
	 * Capabilities this DB supports. Supported list:
	 * affected_rows	Can report query affected rows for UPDATE/DELETE
	 * 					implement getAffectedRowCount()
	 * select_rows		Can report row count for SELECT
	 * 					implement getRowCount()
	 * case_sensitive	Supports case-sensitive text columns
	 * fulltext			Supports fulltext search indexes
	 * inline_keys		Supports defining keys together with the table
	 * auto_increment_sequence Autoincrement support implemented as sequence
	 * limit_subquery   Supports LIMIT clauses in subqueries
	 * create_user		Can create users for Sugar
	 * create_db		Can create databases
	 * collation		Supports setting collations
	 * disable_keys     Supports temporarily disabling keys (for upgrades, etc.)
     * recursive_query  Supports recursive queries
     *
     * order_stability  Supports stable order on SELECTs without the need for
     *                  unique column usage in the `ORDER BY` clause when no
     *                  huge insert/delete operations are happening on the
     *                  SELECTed tables.
	 *
	 * @abstract
	 * Special cases:
	 * fix:expandDatabase - needs expandDatabase fix, see expandDatabase.php
	 * TODO: verify if we need these cases
	 */
	protected $capabilities = array();

	/**
	 * Database options
	 * @var array
	 */
	protected $options = array();


    /**
     * Default performance profile
     * @var array
     */
    protected $defaultPerfProfile = array();


    /**
     * Doctrine connection
     *
     * @var Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getEmptyStringSQL($field)
    {
        $empty = $this->quoted('');
        return " $field = $empty ";
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getNotEmptyStringSQL($field)
    {
        $empty = $this->quoted('');
        return " $field != $empty ";
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getIsNullSQL($field)
    {
        return " $field IS NULL ";
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getIsNotNullSQL($field)
    {
        return " $field IS NOT NULL ";
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getEmptyFieldSQL($field)
    {
        return '(' . $this->getEmptyStringSQL($field) . ' OR ' . $this->getIsNullSQL($field) . ')';
    }

    /**
     * Gets a string comparison SQL snippet for use in hard coded queries. This
     * is done this way because some DBs handle empty strings differently than
     * others.
     * @param string $field The full column name (and alias) to use in the comparison
     * @return string
     */
    public function getNotEmptyFieldSQL($field)
    {
        return '(' . $this->getNotEmptyStringSQL($field) . ' AND ' . $this->getIsNotNullSQL($field) . ')';
    }

    /**
     * Sets where properties for empty conditions on the SugarQuery object
     * @param SugarQuery_Builder_Where $where SugarQuery where object
     * @param string $field The field to compare
     * @param SugarBean $bean SugarBean
     * @return SugarQuery_Builder_Where
     */
    public function setEmptyWhere(SugarQuery_Builder_Where $where, $field, $bean = false)
    {
        $where->queryOr()
              ->equals($field, '', $bean)
              ->isNull($field, $bean);
        return $where;
    }

    /**
     * Sets where properties for not empty conditions on the SugarQuery object
     * @param SugarQuery_Builder_Where $where SugarQuery where object
     * @param string $field The field to compare
     * @param SugarBean $bean SugarBean
     * @return SugarQuery_Builder_Where
     */
    public function setNotEmptyWhere(SugarQuery_Builder_Where $where, $field, $bean = false)
    {
        $where->queryAnd()
              ->notEquals($field, '', $bean)
              ->notNull($field, $bean);
        return $where;
    }

    /**
     * Getter default performance profile
     * @param string $name Profile name
     * @return array
     */
    public function getDefaultPerfProfile($name)
    {
        if (isset($this->defaultPerfProfile[$name])) {
            return $this->defaultPerfProfile[$name];
        } else {
            return array();
        }
    }

    /**
     * Do we encode HTML?
	 * @return bool $encode
	 */
	public function getEncode()
	{
		return $this->encode;
	}

	/**
	 * Set HTML encoding flag
	 * @param boolean $encode
	 */
	public function setEncode($encode)
	{
		$this->encode = $encode;
	}

	/**
     * Create DB Driver
     */
	public function __construct()
	{
		$this->timedate = TimeDate::getInstance();
		$this->log = $GLOBALS['log'];
		$this->helper = $this; // compatibility
		if(defined('ENTRY_POINT_TYPE') && constant('ENTRY_POINT_TYPE') == 'api') {
		    $this->encode = false;
		}
        $this->request = InputValidation::getService();
	}

    /**
     * Wrapper for those trying to access the private and protected class members directly
     * @param string $p var name
     * @return mixed
     */
	public function __get($p)
	{
		$this->log->deprecated('Call to DBManager::$'.$p.' is deprecated');
		return $this->$p;
	}

	/**
	 * Returns the current database handle
	 * @return resource
	 */
	public function getDatabase()
	{
		$this->checkConnection();
		return $this->database;
	}

    /**
     * Returns the Doctrine connection with the same connection resource
     *
     * @return \Sugarcrm\Sugarcrm\Dbal\Connection
     * @throws Exception
     */
    public function getConnection()
    {
        if (!$this->conn) {
            $this->conn = DBManagerFactory::createConnection($this);
        }

        return $this->conn;
    }

	/**
	 * Checks for error happening in the database
	 *
	 * @param  string $msg        message to prepend to the error message
	 * @param  bool   $dieOnError true if we want to die immediately on error
	 * @return bool True if there was an error
	 */
	public function checkError($msg = '', $dieOnError = false)
	{
		if (empty($this->database)) {
			$this->registerError($msg, "Database Is Not Connected", $dieOnError);
			return true;
		}

		$dberror = $this->lastDbError();
		if($dberror === false) {
    		$this->last_error = false;
	    	return false;
		}
		$this->registerError($msg, $dberror, $dieOnError);
        return true;
	}

	/**
	 * Register database error
	 * If die-on-error flag is set, logs the message and dies,
	 * otherwise sets last_error to the message
	 * @param string $userMessage Message from function user
	 * @param string $message Message from SQL driver
	 * @param bool $dieOnError
	 */
	public function registerError($userMessage, $message, $dieOnError = false)
	{
		if(!empty($message)) {
			if(!empty($userMessage)) {
				$message = "$userMessage: $message";
			}
			if(empty($message)) {
			    $message = "Database error";
			}
			$this->log->fatal($message);
			if ($dieOnError || $this->dieOnError) {
				if(isset($GLOBALS['app_strings']['ERR_DB_FAIL'])) {
					sugar_die($GLOBALS['app_strings']['ERR_DB_FAIL']);
				} else {
					sugar_die("Database error. Please check sugarcrm.log for details.");
				}
			} else {
				$this->last_error = $message;
			}
		}
	}

	/**
	 * Return DB error message for the last query executed
	 * @return string Last error message
	 */
	public function lastError()
	{
		return $this->last_error;
	}

    /**
     * This method is called by every method that runs a query.
     * If slow query dumping is turned on and the query time is beyond
     * the time limit, we will log the query. This function may do
     * additional reporting or log in a different area in the future.
     *
     * @param  string  $query query to log
     * @return boolean true if the query was logged, false otherwise
     */
    public function dump_slow_queries($query)
    {
        global $sugar_config;

        if (!empty($sugar_config['xhprof_config'])) {
            SugarXHprof::getInstance()->trackSQL($query, $this->query_time);
        }

        $do_the_dump = isset($sugar_config['dump_slow_queries'])
            ? $sugar_config['dump_slow_queries'] : false;
        $slow_query_time_msec = isset($sugar_config['slow_query_time_msec'])
            ? $sugar_config['slow_query_time_msec'] : 5000;

        if ($do_the_dump) {
            if ($slow_query_time_msec < ($this->query_time * 1000)) {
                // Then log both the query and the query time
                $this->log->fatal('Slow Query (time:'.$this->query_time."\n".$query);
                $this->track_slow_queries($query);
                return true;
            }
        }
        return false;
    }

    /**
     * Tracks slow queries in the tracker database table. This is implicitly
     * called from DBManager::dump_slow_queries.
     *
     * @param string $query  value of query to track
     */
    public function track_slow_queries($query)
    {
        $trackerManager = TrackerManager::getInstance();
        if ($trackerManager->isPaused()) {
            return;
        }

        if ($monitor = $trackerManager->getMonitor('tracker_queries')) {
            $monitor->setValue('date_modified', $this->timedate->nowDb());
            $monitor->setValue('text', $query);
            $monitor->setValue('sec_total', $this->query_time);

            //Save the monitor to cache (do not flush)
            $trackerManager->saveMonitor($monitor, false);
        }
    }

	/**
	 * Service method for addDistinctClause, replaces subquery with JOIN
	 * @param array $matches
	 * @return string
	 */
	protected function replaceTeamClause($matches)
	{
	    $part = $matches[0];
	    $search = array();
	    $replace = array();

	    $table = $matches[2];
	    $search[] =  'INNER JOIN (select tst.team_set_id from team_sets_teams tst';
	    $replace[] =  ' INNER JOIN team_sets_teams tst ON tst.team_set_id = ' . $table . '.team_set_id';
	    $search[] = 'group by tst.team_set_id) ' . $table . '_tf on ' . $table . '_tf.team_set_id  = ' . $table .'.team_set_id';
	    $replace[] = '';

	    $part= str_replace($search, $replace, $part);
	    return $part;
	}

    /**
     * addDistinctClause
     * This method takes a SQL statement and checks if the disable_count_query setting is enabled
     * before altering it.  The alteration modifies the way the team security queries are made by
     * changing it from a subselect to a distinct clause; hence the name of the method.
     *
     * @param string $sql value of SQL statement to alter
     * @deprecated
     */
	protected function addDistinctClause(&$sql)
	{
	    preg_match('|^\W*(\w+)|i', $sql, $firstword);
	    if(empty($firstword[1]) || strtolower($firstword[1]) != 'select') {
	        // take first word of the query, if it's not SELECT - ignore it
	        return;
	    }
		if(!empty($GLOBALS['sugar_config']['disable_count_query']) && (stripos($sql, 'count(*)') === false && stripos($sql, ' JOIN (select tst.team_set_id from') !==false)){
			if(stripos( $sql, 'UNION ALL') !== false){
				$parts = explode('UNION ALL', $sql);
			}else{
				$parts = array($sql);
			}
			$newSql = '';
			foreach($parts as $p=>$part){
			    $newpart = preg_replace_callback('/INNER JOIN \((select tst\.team_set_id[^\)]*)\)\s*(\w*)_tf on \w*_tf\.team_set_id  = \w*\.team_set_id/i',
			        array($this, "replaceTeamClause"), $part);
			    $selects = array();
			    preg_match_all('/SELECT\s+(.*?)\s+FROM\s+/is', $newpart, $selects, PREG_OFFSET_CAPTURE);
			    if(!empty($selects[1])) {
			        $offset = 0;
			        do {
    			        foreach($selects[1] as $match) {
                            if(stripos($match[0], 'distinct') !== false) continue; /* already have distinct */
                            if(preg_match('/(avg|sum|min|max|count)\(.*\)/i', $match[0])) {
                                /* bug #61011 - don't rewrite queries with aggregates */
                                break 2;
                            }
                            $newpart = substr($newpart, 0, $match[1]+$offset)."DISTINCT ".substr($newpart, $match[1]+$offset);
                            $offset += strlen("DISTINCT "); // adjust following offsets since we've added stuff
    			        }
			            $part = $newpart;
			        } while(false);
			    }

				if( $p < count($parts) - 1 )$part .= 'UNION ALL';
				$newSql .= $part;

			}
			if(!empty($newSql))$sql = $newSql;
		}
	}

   /**
	* Scans order by to ensure that any field being ordered by is.
	*
	* It will throw a warning error to the log file - fatal if slow query logging is enabled
	*
	* @param  string $sql         query to be run
	* @param  bool   $object_name optional, object to look up indices in
	* @return bool   true if an index is found false otherwise
	*/
    protected function checkQuery($sql, $object_name = false)
    {
        preg_match_all("'.* FROM ([^ ]*).* ORDER BY (.*)'is", $sql, $match);

        if (empty($match[1][0])) {
            return false;
        }

        $indices = false;

        if (!empty($object_name) && !empty($GLOBALS['dictionary'][$object_name])) {
            $indices = $GLOBALS['dictionary'][$object_name]['indices'];
        }

        $table = $match[1][0];

        if (empty($indices)) {
            foreach ($GLOBALS['dictionary'] as $current) {
                if ($current['table'] == $table) {
                    if (isset($current['indices'])) {
                        $indices = $current['indices'];
                    }

                    break;
                }
            }
        }

        if (empty($indices)) {
            $this->log->warn('CHECK QUERY: Could not find index definitions for table ' . $table);
            return false;
        }

        if (empty($match[2][0])) {
            return false;
        }

        $orderBys = explode(' ', $match[2][0]);

        foreach ($orderBys as $orderBy) {
            $orderBy = trim($orderBy);

            if (empty($orderBy)) {
                continue;
            }

            $orderBy = strtolower($orderBy);

            if ($orderBy == 'asc' || $orderBy == 'desc') {
                continue;
            }

            $orderBy = str_replace(array($table . '.', ','), '', $orderBy);

            foreach ($indices as $index) {
                if (empty($index['db']) || $index['db'] == $this->dbType) {
                    foreach ($index['fields'] as $field) {
                        if ($field == $orderBy) {
                            return true;
                        }
                    }
                }
            }

            $warning = 'Missing Index For Order By Table: ' . $table . ' Order By:' . $orderBy ;

            if (!empty($GLOBALS['sugar_config']['dump_slow_queries'])) {
                $this->log->fatal('CHECK QUERY:' .$warning);
            } else {
                $this->log->warn('CHECK QUERY:' .$warning);
            }
        }

        return false;
    }

	/**
	 * Returns the time the last query took to execute
	 *
	 * @return int
	 */
	public function getQueryTime()
	{
		return $this->query_time;
	}

	/**
	 * Checks the current connection; if it is not connected then reconnect
	 */
	public function checkConnection()
	{
		$this->last_error = '';
		if (!isset($this->database))
			$this->connect();
	}

	/**
	 * Sets the dieOnError value
	 *
	 * @param bool $value
	 */
	public function setDieOnError($value)
	{
		$this->dieOnError = $value;
	}

    /**
     * Implements a generic insert for any bean.
     *
     * @param SugarBean $bean SugarBean instance
     * @return bool
     */
	public function insert(SugarBean $bean)
	{
        return $this->insertParams(
            $bean->getTableName(),
            $bean->getFieldDefinitions(),
            get_object_vars($bean)
        );
	}

	/**
	 * Replaces specific characters with their HTML entity values
	 * @param string $string String to check/replace
	 * @return string
	 *
	 */
	public function encodeHTML($string)
	{
		if (empty($string) || !$this->encode) {
			return $string;
		}
		/** Not using ENT_HTML401|ENT_SUBSTITUTE since they are 5.4+ only */
		return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
	}


	/**
	 * Replaces specific HTML entity values with the true characters
	 * @param string $string String to check/replace
	 * @param bool $encode Default true
	 * @return string
	 */
	public function decodeHTML($string)
	{
		if (!is_string($string) || !$this->encode) {
			return $string;
		}
		return htmlspecialchars_decode($string, ENT_QUOTES);
	}
	/**
	 * Insert data into table by parameter definition
	 * @param string $table Table name
	 * @param array $field_defs Definitions in vardef-like format
	 * @param array $data Key/value to insert
     * @return bool
     */
    public function insertParams($table, $field_defs, $data)
	{
        $values = $expressions = array();
        foreach ($field_defs as $field => $fieldDef) {
			if (isset($fieldDef['source']) && $fieldDef['source'] != 'db')  continue;
			//custom fields handle their save separately
            if (!empty($fieldDef['custom_type'])) {
                continue;
            }

			//handle auto increment values here - we may have to do something like nextval for oracle
			if (!empty($fieldDef['auto_increment'])) {
				$auto = $this->getAutoIncrementSQL($table, $fieldDef['name']);
				if(!empty($auto)) {
                    $expressions[$field] = $auto;
				}
			} else {
                if (!array_key_exists($field, $data)) {
                    continue;
                }

                if ($data[$field] === '' && $this->isNullable($fieldDef)) {
                    $values[$field] = null;
                } else {
                    $values[$field] = $this->decodeHTML($data[$field]);
                }
            }
        }

        $builder = $this->getConnection()->createQueryBuilder();
        $builder->insert($table);

        foreach ($values as $field => $value) {
            $builder->setValue(
                $field,
                $this->bindValue($builder, $value, $field_defs[$field])
            );
        }

        foreach ($expressions as $field => $expression) {
            $builder->setValue($field, $expression);
        }

        $builder->execute();

        return true;
	}

    /**
     * Implements a generic update for any bean
     *
     * @param SugarBean $bean SugarBean instance
     * @return bool
     */
    public function update(SugarBean $bean)
    {
        $dataFields = array();
        $dataValues = array();
        $primaryField = $bean->getPrimaryFieldDefinition();
        $fields = $bean->getFieldDefinitions();
        // get column names and values
        foreach ($fields as $field => $fieldDef) {
            // Do not write out the id field on the update statement.
            // We are not allowed to change ids.
            if ($fieldDef['name'] == $primaryField['name']) {
                continue;
            }

            $dataValues[$field] = $bean->$field ?? null;
            $dataFields[$field] = $fieldDef;
        }

        // prevent updates from overwriting `date_entered` unless it's allowed
        if (!$bean->update_date_entered) {
            unset($dataFields['date_entered']);
        }

        // build where clause
        $where_data = $this->updateWhereArray($bean);
        if (isset($fields['deleted'])) {
            $where_data['deleted'] = "0";
        }
        foreach ($where_data as $field => $value) {
            $dataFields[$field] = $fields[$field];
        }

        return $this->updateParams($bean->getTableName(), $dataFields, $dataValues, $where_data);
	}

	/**
	 * Implements a generic retrieve for a collection of beans.
	 *
	 * These beans will be joined in the sql by the key attribute of field defs.
	 * Currently, this function does support outer joins.
	 *
	 * @param  array $beans Sugarbean instance(s)
	 * @param  array $cols  columns to be returned with the keys as names of bean as identified by
	 * get_class of bean. Values of this array is the array of fieldDefs to be returned for a bean.
	 * If an empty array is passed, all columns are selected.
	 * @param  array $where  values with the keys as names of bean as identified by get_class of bean
	 * Each value at the first level is an array of values for that bean identified by name of fields.
	 * If we want to pass multiple values for a name, pass it as an array
	 * If where is not passed, all the rows will be returned.
	 * @return resource
	 */
	public function retrieveView(array $beans, array $cols = array(), array $where = array())
	{
		$sql = $this->retrieveViewSQL($beans, $cols, $where);
		$msg = "Error retriving values from View Collection:";
		return $this->query($sql,true,$msg);
	}

	/**
	 * Implements creation of a db table for a bean.
	 *
	 * NOTE: does not handle out-of-table constraints, use createConstraintSQL for that
	 * @param SugarBean $bean  Sugarbean instance
	 */
	public function createTable(SugarBean $bean)
	{
		$sql = $this->createTableSQL($bean);
		$tablename = $bean->getTableName();
		$msg = "Error creating table: $tablename:";
		$this->query($sql,true,$msg);
		if(!$this->supports("inline_keys")) {
		// handle constraints and indices
			$indicesArr = $this->createConstraintSql($bean);
			if (count($indicesArr) > 0)
				foreach ($indicesArr as $indexSql)
					$this->query($indexSql, true, $msg);
		}
	}

	/**
	 * returns SQL to create constraints or indices
	 *
	 * @param  SugarBean $bean SugarBean instance
	 * @return array list of SQL statements
	 */
	protected function createConstraintSql(SugarBean $bean)
	{
        $indices = $this->massageIndexDefs($bean->getFieldDefinitions(), $bean->getIndices());
        return $this->getConstraintSql($indices, $bean->getTableName());
	}

	/**
	 * Implements creation of a db table
	 *
	 * @param string $tablename
	 * @param array  $fieldDefs  Field definitions, in vardef format
	 * @param array  $indices    Index definitions, in vardef format
	 * @param string $engine    Engine parameter, used for MySQL engine so far
     * @todo: refactor engine param to be more generic
     * @return bool success value
     */
	public function createTableParams($tablename, $fieldDefs, $indices, $engine = null)
	{
        $indices = $this->massageIndexDefs($fieldDefs, $indices);
		if (!empty($fieldDefs)) {
			$sql = $this->createTableSQLParams($tablename, $fieldDefs, $indices, $engine);
			$res = true;
			if ($sql) {
				$msg = "Error creating table: $tablename";
				$res = ($res and $this->query($sql,true,$msg));
			}
			if(!$this->supports("inline_keys")) {
				// handle constraints and indices
				$indicesArr = $this->getConstraintSql($indices, $tablename);
				if (count($indicesArr) > 0)
					foreach ($indicesArr as $indexSql)
						$res = ($res and $this->query($indexSql, true, "Error creating indexes"));
			}
			return $res;
		}
		return false;
	}

	/**
	 * Implements repair of a db table for a bean.
	 *
	 * @param  SugarBean $bean    SugarBean instance
	 * @param  bool   $execute true if we want the action to take place, false if we just want the sql returned
	 * @return string SQL statement or empty string, depending upon $execute
	 */
	public function repairTable(SugarBean $bean, $execute = true)
	{
		$indices   = $bean->getIndices();
		$fielddefs = $bean->getFieldDefinitions();
		$tablename = $bean->getTableName();

		//Clean the indexes to prevent duplicate definitions
		$new_index = array();
		foreach($indices as $ind_def){
			$new_index[$ind_def['name']] = $ind_def;
		}
		//jc: added this for beans that do not actually have a table, namely
		//ForecastOpportunities
		if($tablename == 'does_not_exist' || $tablename == '')
			return '';

		global $dictionary;
		$engine=null;
		if (isset($dictionary[$bean->getObjectName()]['engine']) && !empty($dictionary[$bean->getObjectName()]['engine']) )
			$engine = $dictionary[$bean->getObjectName()]['engine'];

		return $this->repairTableParams($tablename, $fielddefs,$new_index,$execute,$engine);
	}

	/**
	 * Can this field be null?
	 * Auto-increment and ID fields can not be null
	 * @param array $vardef
     * @return bool
     */
    protected function isNullable($vardef)
    {
        if (isset($vardef['isnull']) && (strtolower($vardef['isnull']) == 'false' || $vardef['isnull'] === false)
            && !empty($vardef['required'])) {
                /* required + is_null=false => not null */
            return false;
        }
        if ((isset($vardef['type']) && $vardef['type'] == 'bool')
            || (isset($vardef['dbType']) && $vardef['dbType'] == 'bool')) {
            return false;
        }
        if (empty($vardef['auto_increment'])
            && (empty($vardef['type']) || $vardef['type'] != 'id' || empty($vardef['required']))
            && (empty($vardef['dbType']) || $vardef['dbType'] != 'id' || empty($vardef['required']))
            && (empty($vardef['name']) || ($vardef['name'] != 'id' && $vardef['name'] != 'deleted'))
        ) {
            return true;
        }
        return false;
    }


	/**
	 * Builds the SQL commands that repair a table structure
	 *
     * @param  string $tableName Table name
	 * @param  array  $fielddefs Field definitions, in vardef format
	 * @param  array  $indices   Index definitions, in vardef format
	 * @param  bool   $execute   optional, true if we want the queries executed instead of returned
	 * @param  string $engine    optional, MySQL engine
     * @todo: refactor engine param to be more generic
     * @return string
     */
    public function repairTableParams($tableName, $fielddefs, array $indices, $execute = true, $engine = null)
    {
        global  $sugar_config;
        //jc: had a bug when running the repair if the tablename is blank the repair will
        //fail when it tries to create a repair table
        if ($tableName == '' || empty($fielddefs))
            return '';

        //if the table does not exist create it and we are done
        $sql = "/* Table : $tableName */\n";
        if (!$this->tableExists($tableName)) {
            $createtablesql = $this->createTableSQLParams($tableName,$fielddefs,$indices,$engine);
            if($execute && $createtablesql){
                $this->createTableParams($tableName,$fielddefs,$indices,$engine);
            }

            $sql .= "/* MISSING TABLE: {$tableName} */\n";
            $sql .= $createtablesql . "\n";
            return $sql;
        }

        $sql = $this->repairTableColumns($tableName, $fielddefs, $execute);
        if (empty($this->options['skip_index_rebuild'])) {
            $sql .= $this->repairTableIndices($tableName, $fielddefs, $indices, $execute);
        }

        return $sql;
    }

    /**
     * Parse length & precision into 2 numbers
     * @param array $def Vardef-like data
     * @return array(length, precision)
     */
    protected function parseLenPrecision($def)
    {
        if (strpos($def['len'], ",") !== false) {
            return explode(",", $def['len']);
        }
        if (isset($def['precision'])) {
            return array($def['len'], $def['precision']);
        }
        return array($def['len'], null);
    }

    /**
     * Supplies the SQL commands that repair a table structure
     *
     * @param  string $tableName
     * @param  array  $fielddefs Field definitions, in vardef format
     * @param  bool   $execute   optional, true if we want the queries executed instead of returned
     *
     * @return string
     */
    protected function repairTableColumns($tableName, $fielddefs, $execute)
    {
        $compareFieldDefs = $this->get_columns($tableName);
        $sql = "/*COLUMNS*/\n";
        $take_action = false;

        // do column comparisons
        foreach ($fielddefs as $name => $value) {
            if (isset($value['source']) && $value['source'] != 'db')
                continue;

            // Bug #42406. Skipping broken vardef without type or name
            if (isset($value['name']) == false || $value['name'] == false)
            {
                $sql .= "/* NAME IS MISSING IN VARDEF $tableName::$name */\n";
                continue;
            }
            else if (isset($value['type']) == false || $value['type'] == false)
            {
                $sql .= "/* TYPE IS MISSING IN VARDEF $tableName::$name */\n";
                continue;
            }

            $name = strtolower($value['name']);
            // add or fix the field defs per what the DB is expected to give us back
            $this->massageFieldDef($value,$tableName);

            $ignorerequired=false;

            //Do not track requiredness in the DB, auto_increment, ID,
            // and deleted fields are always required in the DB, so don't force those
            if ($this->isNullable($value)) {
                $value['required'] = false;
            }
            //Should match the conditions in DBManager::oneColumnSQLRep for DB required fields, type='id' fields will sometimes
            //come into this function as 'type' = 'char', 'dbType' = 'id' without required set in $value. Assume they are correct and leave them alone.
            else if (($name == 'id' || $value['type'] == 'id' || (isset($value['dbType']) && $value['dbType'] == 'id'))
                && (!isset($value['required']) && isset($compareFieldDefs[$name]['required'])) || (!empty($value['auto_increment']) && isset($compareFieldDefs[$name]['required'])))
            {
                $value['required'] = $compareFieldDefs[$name]['required'];
            }

            if ( !isset($compareFieldDefs[$name]) ) {
                // ok we need this field lets create it
                $sql .=	"/*MISSING IN DATABASE - $name -  ROW*/\n";
                $sql .= $this->addColumnSQL($tableName, $value) .  "\n";
                if ($execute) {
                    $this->addColumn($tableName, $value);
                }
                $take_action = true;
            } elseif ( !$this->compareVarDefs($compareFieldDefs[$name],$value)) {
                //fields are different lets alter it
                $sql .=	"/*MISMATCH WITH DATABASE - $name -  ROW ";
                foreach($compareFieldDefs[$name] as $rKey => $rValue) {
                    $sql .=	"[$rKey] => '$rValue'  ";
                }
                $sql .=	"*/\n";
                $sql .=	"/* VARDEF - $name -  ROW";
                foreach($value as $rKey => $rValue) {
                    if(is_array($rValue)) {
                        // no newlines
                        $rValue = str_replace("\n", " ", print_r($rValue, true));
                    }
                    $sql .=	"[$rKey] => '$rValue'  ";
                }
                $sql .=	"*/\n";

                //jc: oracle will complain if you try to execute a statement that sets a column to (not) null
                //when it is already (not) null
                if ( isset($value['isnull']) && isset($compareFieldDefs[$name]['isnull']) &&
                    $value['isnull'] === $compareFieldDefs[$name]['isnull']) {
                    unset($value['required']);
                    $ignorerequired=true;
                }

                //dwheeler: Once a column has been defined as null, we cannot try to force it back to !null
                if ((isset($value['required']) && ($value['required'] === true || $value['required'] == 'true' || $value['required'] === 1))
                    && (empty($compareFieldDefs[$name]['required']) || $compareFieldDefs[$name]['required'] != 'true'))
                {
                    $ignorerequired = true;
                }

                // BR-1787: we can not decrease the length of the column
                if (!empty($value['len']) && !empty($compareFieldDefs[$name]['len'])) {
                    list($dblen, $dbprec) = $this->parseLenPrecision($compareFieldDefs[$name]);
                    list($vlen, $vprec) = $this->parseLenPrecision($value);

                    if (isset($dbprec)) {
                        // already have precision - match both separately
                        if ($vprec < $dbprec) {
                            $vprec = $dbprec;
                        }
                    } else {
                        // did not have precision - length-precision should be no less than old length
                        if (isset($vprec)) {
                            $dblen += $vprec;
                        }
                    }
                    if ($vlen < $dblen) {
                        $vlen = $dblen;
                    }
                    if (isset($vprec)) {
                        $value['precision'] = $vprec;
                        $value['len'] = "$vlen,$vprec";
                    } else {
                        $value['len'] = $vlen;
                    }
                }

                $altersql = $this->alterColumnSQL($tableName, $value, $ignorerequired);
                if(is_array($altersql)) {
                    $altersql = join("\n", $altersql);
                }
                $sql .= $altersql .  "\n";
                if($execute){
                    $this->alterColumn($tableName, $value, $ignorerequired);
                }
                $take_action = true;
            }
        }
        return ($take_action === true) ? $sql : '';
    }

    /**
     * Supplies the SQL commands that repair a table Indices
     *
     * @param  string $tableName
     * @param  array $fieldDefs field definitions of the table
     * @param  array $indices Index definitions, in vardef format
     * @param  bool $execute optional, true if we want the queries executed instead of returned
     * @return string
     */
    private function repairTableIndices($tableName, $fieldDefs, $indices, $execute)
    {
        $schemaIndices = $this->get_indices($tableName);
        return $this->alterTableIndices($tableName, $fieldDefs, $indices, $schemaIndices, $execute);
    }

    /**
     * Supplies the SQL commands that alters table to match the definition
     *
     * @param string $tableName Table name
     * @param array $fieldDefs Field definitions from vardefs
     * @param array $indices Index definitions from vardefs
     * @param array $compareIndices Index definitions obtained from database
     * @param bool $execute Whether we want the queries executed instead of returned
     * @return string
     */
    public function alterTableIndices($tableName, $fieldDefs, $indices, $compareIndices, $execute)
    {
        $indices = $this->massageIndexDefs($fieldDefs, $indices);
        $take_action = false;
        $tableDefs = $this->get_columns($tableName);
        $sql = "/* INDEXES */\n";
        $correctedIndexes = array();

        $compareIndices_ci = array();

        // ****************************************
        // do indices comparisons case-insensitive
        // ****************************************

        //First change the DB indices to lower case
        foreach($compareIndices as $k => $value){
            $value['name'] = strtolower($value['name']);
            $value['type'] = strtolower($value['type']);
            if (isset($value['fields'])) {
                foreach($value['fields'] as $index=>$fieldName) {
                    $value['fields'][$index]=strtolower($fieldName);
                }
            }
            $compareIndices_ci[strtolower($k)] = $value;
        }
        $compareIndices = $compareIndices_ci;
        $compareIndices_ci = array();
        //Then change the $indices to lower case
        foreach($indices as $k => $value){
            $value['name'] = strtolower($value['name']);
            $value['type'] = strtolower($value['type']);
            if (isset($value['fields'])) {
                foreach($value['fields'] as $index=>$fieldName) {
                    $value['fields'][$index]=strtolower($fieldName);
                }
            }
            $compareIndices_ci[strtolower($k)] = $value;
        }
        $indices = $compareIndices_ci;
        unset($compareIndices_ci);

        foreach ($indices as $value) {
            if (isset($value['source']) && $value['source'] != 'db') {
                continue;
            }

            $validDBName = $this->getValidDBName($value['name'], true, 'index', true);
            if (isset($compareIndices[$validDBName])) {
                $value['name'] = $validDBName;
            }
            $name = strtolower($value['name']);

            //Don't attempt to fix the same index twice in one pass;
            if (isset($correctedIndexes[$name]))
                continue;

            //database helpers do not know how to handle full text indices
            if ($value['type']=='fulltext')
                continue;

            if ( in_array($value['type'],array('alternate_key','foreign')) )
                $value['type'] = 'index';

            // Filter the fields, remove non-indexable ones
            $value['fields'] = $this->filterIndexFields($tableDefs, $value['fields']);
            if(empty($value['fields'])) {
                // if we have no fields, ignore this index
                continue;
            }

            if ( !isset($compareIndices[$name]) ) {
                //First check if an index exists that doesn't match our name, if so, try to rename it
                $found = false;
                foreach ($compareIndices as $ex_name => $ex_value) {
                    if($this->compareVarDefs($ex_value, $value, true)) {
                        $found = $ex_name;
                        break;
                    }
                }
                if ($found === false) {
                    // ok we need this field lets create it
                    $sql .=	 "/*MISSING INDEX IN DATABASE - $name - {$value['type']}  ROW */\n";
                    $sql .= $this->addIndexes($tableName,array($value), $execute) .  "\n";
                    $take_action = true;
                    $correctedIndexes[$name] = true;
                }
            } elseif ( !$this->compareVarDefs($compareIndices[$name],$value) ) {
                // fields are different lets alter it
                $sql .=	"/*INDEX MISMATCH WITH DATABASE - $name -  ROW ";
                foreach ($compareIndices[$name] as $n1 => $t1) {
                    $sql .=	 "<$n1>";
                    if ( $n1 == 'fields' )
                        foreach($t1 as $rKey => $rValue)
                            $sql .= "[$rKey] => '$rValue'  ";
                    else
                        $sql .= " $t1 ";
                }
                $sql .=	"*/\n";
                $sql .=	"/* VARDEF - $name -  ROW";
                foreach ($value as $n1 => $t1) {
                    $sql .=	"<$n1>";
                    if ( $n1 == 'fields' )
                        foreach ($t1 as $rKey => $rValue)
                            $sql .=	"[$rKey] => '$rValue'  ";
                    else
                        $sql .= " $t1 ";
                }
                $sql .=	"*/\n";
                $sql .= $this->modifyIndexes($tableName,array($value), $execute) .  "\n";
                $take_action = true;
                $correctedIndexes[$name] = true;
            }
        }

        return ($take_action === true) ? $sql : '';
    }

    /**
     * Compares two vardefs
     *
     * @param  array  $fielddef1 This is from the database
     * @param  array  $fielddef2 This is from the vardef
     * @param bool $ignoreName Ignore name-only differences?
     * @return bool   true if they match, false if they don't
     */
    public function compareVarDefs($fielddef1, $fielddef2, $ignoreName = false)
    {
        foreach ($fielddef1 as $key => $value) {
            if ($key == 'name' &&
                (strtolower($fielddef1[$key]) == strtolower($fielddef2[$key]) ||
                    $ignoreName) ) {
                continue;
            }
            if (isset($fielddef2[$key])) {
                if ($fielddef1[$key] == $fielddef2[$key]) {
                    continue;
                }

                if ($key === 'default' && // ignore vardef default value = '' and db value = 0.0
                    isset($fielddef1['type']) && $this->isNumericType($fielddef1['type']) &&
                    floatval($fielddef1[$key]) == floatval($fielddef2[$key])) {
                    continue;
                }

                if ($key === 'auto_increment') { // check loose true value
                    if (isTruthy($fielddef1[$key]) && isTruthy($fielddef2[$key])) {
                        continue;
                    }
                }
            }
            //Ignore len if its not set in the vardef
            if ($key == 'len' && empty($fielddef2[$key])) {
                continue;
            }
            // if the length in db is greather than the vardef, ignore it
            if ($key == 'len') {
                list($dblen, $dbprec) = $this->parseLenPrecision($fielddef1);
                list($vlen, $vprec) = $this->parseLenPrecision($fielddef2);
                if ($dblen >= $vlen && ((is_null($dbprec) && is_null($vprec)) || $dbprec >= $vprec)) {
                    continue;
                }
            }
            return false;
        }

        return true;
    }

	/**
	 * Compare a field in two tables
	 * @deprecated
	 * @param  string $name   field name
	 * @param  string $table1
	 * @param  string $table2
	 * @return array  array with keys 'msg','table1','table2'
	 */
	public function compareFieldInTables($name, $table1, $table2)
	{
		$row1 = $this->describeField($name, $table1);
		$row2 = $this->describeField($name, $table2);
		$returnArray = array(
			'table1' => $row1,
			'table2' => $row2,
			'msg'    => 'error',
			);

		$ignore_filter = array('Key'=>1);
		if ($row1) {
			if (!$row2) {
				// Exists on table1 but not table2
				$returnArray['msg'] = 'not_exists_table2';
			}
			else {
				if (sizeof($row1) != sizeof($row2)) {
					$returnArray['msg'] = 'no_match';
				}
				else {
					$returnArray['msg'] = 'match';
					foreach($row1 as $key => $value){
						//ignore keys when checking we will check them when we do the index check
						if( !isset($ignore_filter[$key]) && (!isset($row2[$key]) || $row1[$key] !== $row2[$key])){
							$returnArray['msg'] = 'no_match';
						}
					}
				}
			}
		}
		else {
			$returnArray['msg'] = 'not_exists_table1';
		}

		return $returnArray;
	}

	/**
	 * Creates an index identified by name on the given fields.
	 *
	 * @param SugarBean $bean      SugarBean instance
	 * @param array  $fieldDefs Field definitions, in vardef format
	 * @param string $name      index name
	 * @param bool   $unique    optional, true if we want to create an unique index
     * @return bool query result
     */
	public function createIndex(SugarBean $bean, $fieldDefs, $name, $unique = true)
	{
		$sql = $this->createIndexSQL($bean, $fieldDefs, $name, $unique);
		$tablename = $bean->getTableName();
		$msg = "Error creating index $name on table: $tablename:";
		return $this->query($sql,true,$msg);
	}

	/**
	 * Filter indexed fields, remove non-indexable ones
	 * @param array $tableDefs Table field definitions
	 * @param array $fields Fields to filter
	 */
	public function filterIndexFields($tableDefs, $fields)
	{
	    if(!is_array($fields)) {
	        $fields = array($fields);
	    }
	    foreach($fields as $k => $field) {
            if(empty($tableDefs[$field]) || empty($tableDefs[$field]['type'])) {
                // if we don't know this field, ignore it - we may add it as a part of bulk SQL update
                // we're assuming that if somebody adds field they won't lead us astray
                continue;
            }
            if($this->isTextType($tableDefs[$field]['type'])) {
                unset($fields[$k]);
            }
	    }
	    return $fields;
	}

	/**
	 * returns a SQL query that creates the indices as defined in metadata
	 * @param  array  $indices Assoc array with index definitions from vardefs
	 * @param  string $table Focus table
	 * @return array  Array of SQL queries to generate indices
	 */
	public function getConstraintSql($indices, $table)
	{
		if (!$this->isFieldArray($indices))
			$indices = array($indices);

		$columns = array();

		foreach ($indices as $index) {
			if(!empty($index['db']) && $index['db'] != $this->dbType) {
				continue;
			}
			if (isset($index['source']) && $index['source'] != 'db') {
			    continue;
			}

			$sql = $this->add_drop_constraint($table, $index);

			if(!empty($sql)) {
				$columns[] = $sql;
			}
		}

		return $columns;
	}

	/**
	 * Adds a new indexes
	 *
	 * @param  string $tablename
	 * @param  array  $indexes   indexes to add
	 * @param  bool   $execute   true if we want to execute the returned sql statement
	 * @return string SQL statement
	 */
	public function addIndexes($tablename, $indexes, $execute = true)
	{
		$alters = $this->getConstraintSql($indexes, $tablename);
		if ($execute) {
			foreach($alters as $sql) {
				$this->query($sql, true, "Error adding index: ");
			}
		}
		if(!empty($alters)) {
			$sql = join(";\n", $alters).";\n";
		} else {
			$sql = '';
		}
		return $sql;
	}

	/**
	 * Drops indexes
	 *
	 * @param  string $tablename
	 * @param  array  $indexes   indexes to drop
	 * @param  bool   $execute   true if we want to execute the returned sql statement
	 * @return string SQL statement
	 */
	public function dropIndexes($tablename, $indexes, $execute = true)
	{
		$sqls = array();
		foreach ($indexes as $index) {
			$name =$index['name'];
			$sqls[$name] = $this->add_drop_constraint($tablename,$index,true);
		}
		if (!empty($sqls) && $execute) {
			foreach($sqls as $name => $sql) {
				unset(self::$index_descriptions[$tablename][$name]);
				$this->query($sql);
			}
		}
		if(!empty($sqls)) {
			return join(";\n",$sqls).";";
		} else {
			return '';
		}
	}

	/**
	 * Modifies indexes
	 *
	 * @param  string $tablename
	 * @param  array  $indexes   indexes to modify
	 * @param  bool   $execute   true if we want to execute the returned sql statement
	 * @return string SQL statement
	 */
	public function modifyIndexes($tablename, $indexes, $execute = true)
	{
		return $this->dropIndexes($tablename, $indexes, $execute)."\n".
			$this->addIndexes($tablename, $indexes, $execute);
	}

	/**
	 * Adds a column to table identified by field def.
	 *
	 * @param string $tablename
	 * @param array  $fieldDefs
     * @return bool query result
     */
	public function addColumn($tablename, $fieldDefs)
	{
		$sql = $this->addColumnSQL($tablename, $fieldDefs);
		if ($this->isFieldArray($fieldDefs)){
			$columns = array();
			foreach ($fieldDefs as $fieldDef)
				$columns[] = $fieldDef['name'];
			$columns = implode(",", $columns);
		}
		else {
			$columns = $fieldDefs['name'];
		}
		$msg = "Error adding column(s) $columns on table: $tablename:";
		return $this->query($sql,true,$msg);
	}

	/**
	 * Alters old column identified by oldFieldDef to new fieldDef.
	 *
	 * @param string $tablename
	 * @param array  $newFieldDef
	 * @param bool   $ignoreRequired optional, true if we are ignoring this being a required field
     * @return bool query result
     */
	public function alterColumn($tablename, $newFieldDef, $ignoreRequired = false)
	{
		$sql = $this->alterColumnSQL($tablename, $newFieldDef,$ignoreRequired);
		if ($this->isFieldArray($newFieldDef)){
			$columns = array();
			foreach ($newFieldDef as $fieldDef) {
				$columns[] = $fieldDef['name'];
			}
			$columns = implode(",", $columns);
		}
		else {
			$columns = $newFieldDef['name'];
		}

		$msg = "Error altering column(s) $columns on table: $tablename:";
		$res = $this->query($sql,true,$msg);
		if($res) {
			$this->getTableDescription($tablename, true); // reload table description after altering
		}
		return $res;
	}

	/**
	 * Drops the table associated with a bean
	 *
	 * @param SugarBean $bean SugarBean instance
     * @return bool query result
	 */
	public function dropTable(SugarBean $bean)
	{
		return $this->dropTableName($bean->getTableName());
	}

	/**
	 * Drops the table by name
	 *
	 * @param string $name Table name
     * @return bool query result
	 */
	public function dropTableName($name)
	{
		$sql = $this->dropTableNameSQL($name);
		return $this->query($sql,true,"Error dropping table $name:");
	}

    /**
     * Deletes a column identified by fieldDef.
     *
     * @param SugarBean $bean   SugarBean containing the field
     * @param array  $fieldDefs Vardef definition of the field
     * @return bool query result
     */
	public function deleteColumn(SugarBean $bean, $fieldDefs)
	{
		$tablename = $bean->getTableName();
		$sql = $this->dropColumnSQL($tablename, $fieldDefs);
		$msg = "Error deleting column(s) on table: $tablename:";
		return $this->query($sql,true,$msg);
	}

	/**
	 * Decode string and quote it
	 * @param unknown_type $string
	 * @return string
	 */
	protected function quotedDecode($string)
	{
	    if($this->encode) {
	        return $this->quoted($this->decodeHTML($string));
	    } else {
	        return $this->quoted($string);
	    }
	}

    /**
     * Generate a set of Insert statements based on the bean given
     *
     * @deprecated
     *
     * @param  SugarBean $bean         the bean from which table we will generate insert stmts
     * @param  string $select_query the query which will give us the set of objects we want to place into our insert statement
     * @param  int    $start        the first row to query
     * @param  int    $count        the number of rows to query
     * @param  string $table        the table to query from
     * @param bool $is_related_query
     * @return string SQL insert statement
     */
	public function generateInsertSQL(SugarBean $bean, $select_query, $start, $count = -1, $table, $is_related_query = false)
	{
		$this->log->info('call to DBManager::generateInsertSQL() is deprecated');
		global $sugar_config;

		$rows_found = 0;
		$count_query = $bean->create_list_count_query($select_query);
		if(!empty($count_query))
		{
			// We have a count query.  Run it and get the results.
			$result = $this->query($count_query, true, "Error running count query for $this->object_name List: ");
			$assoc = $this->fetchByAssoc($result);

			// free resource
			$this->freeDbResult($result);
			if(!empty($assoc['c']))
			{
				$rows_found = $assoc['c'];
			}
		}
		if($count == -1){
			$count 	= $sugar_config['list_max_entries_per_page'];
		}
		$next_offset = $start + $count;

		$result = $this->limitQuery($select_query, $start, $count);
		// get basic insert
		$sql = "INSERT INTO ".$table;
		$custom_sql = "INSERT INTO ".$table."_cstm";

		// get field definitions
		$fields = $bean->getFieldDefinitions();
		$custom_fields = array();

		if($bean->hasCustomFields()){
			foreach ($fields as $fieldDef){
				if($fieldDef['source'] == 'custom_fields'){
					$custom_fields[$fieldDef['name']] = $fieldDef['name'];
				}
			}
			if(!empty($custom_fields)){
				$custom_fields['id_c'] = 'id_c';
				$id_field = array('name' => 'id_c', 'custom_type' => 'id',);
				$fields[] = $id_field;
			}
		}

		// get column names and values
		$row_array = array();
		$columns = array();
		$cstm_row_array = array();
		$cstm_columns = array();
		$built_columns = false;
		while(($row = $this->fetchByAssoc($result)) != null)
		{
			$values = array();
			$cstm_values = array();
			if(!$is_related_query){
				foreach ($fields as $fieldDef)
				{
					if(isset($fieldDef['source']) && $fieldDef['source'] != 'db' && $fieldDef['source'] != 'custom_fields') continue;
					$val = $row[$fieldDef['name']];

					//handle auto increment values here only need to do this on insert not create
					if ($fieldDef['name'] == 'deleted'){
							$values['deleted'] = $val;
							if(!$built_columns){
							$columns[] = 'deleted';
						}
					}
					else
					{
						$type = $fieldDef['type'];
						if(!empty($fieldDef['custom_type'])){
							$type = $fieldDef['custom_type'];
						}
						// need to do some thing about types of values
						if($this->dbType == 'mysql' && $val == '' && ($type == 'datetime' ||  $type == 'date' || $type == 'int' || $type == 'currency' || $type == 'decimal')){
							if(!empty($custom_fields[$fieldDef['name']]))
								$cstm_values[$fieldDef['name']] = 'null';
							else
								$values[$fieldDef['name']] = 'null';
						}else{
							if(isset($type) && $type=='int') {
								if(!empty($custom_fields[$fieldDef['name']]))
									$cstm_values[$fieldDef['name']] = $this->quote($this->decodeHTML($val));
								else
									$values[$fieldDef['name']] = $this->quote($this->decodeHTML($val));
							} else {
								if(!empty($custom_fields[$fieldDef['name']]))
									$cstm_values[$fieldDef['name']] = $this->quotedDecode($val);
								else
									$values[$fieldDef['name']] = $this->quotedDecode($val);
							}
						}
						if(!$built_columns){
							if(!empty($custom_fields[$fieldDef['name']]))
								$cstm_columns[] = $fieldDef['name'];
							else
								$columns[] = $fieldDef['name'];
						}
					}

				}
			} else {
			foreach ($row as $key=>$val)
			{
					if($key != 'orc_row'){
						$values[$key] = "'$val'";
						if(!$built_columns){
							$columns[] = $key;
						}
					}
			}
			}
			$built_columns = true;
			if(!empty($values)){
				$row_array[] = $values;
			}
			if(!empty($cstm_values) && !empty($cstm_values['id_c']) && (strlen($cstm_values['id_c']) > 7)){
				$cstm_row_array[] = $cstm_values;
			}
		}

		// get the entire sql
		$sql .= "(".implode(",", $columns).") ";
		$sql .= "VALUES";
		for($i = 0; $i < count($row_array); $i++){
			$sql .= " (".implode(",", $row_array[$i]).")";
			if($i < (count($row_array) - 1)){
				$sql .= ", ";
			}
		}
		//custom
		// get the entire sql
		$custom_sql .= "(".implode(",", $cstm_columns).") ";
		$custom_sql .= "VALUES";

		for($i = 0; $i < count($cstm_row_array); $i++){
			$custom_sql .= " (".implode(",", $cstm_row_array[$i]).")";
			if($i < (count($cstm_row_array) - 1)){
				$custom_sql .= ", ";
			}
		}
        return array(
            'data' => $sql,
            'cstm_sql' => $custom_sql,
            'total_count' => $rows_found,
            'next_offset' => $next_offset,
        );
	}

	/**
	 * @deprecated
	 * Disconnects all instances
	 */
	public function disconnectAll()
	{
		DBManagerFactory::disconnectAll();
	}

	/**
	 * This function sets the query threshold limit
	 *
	 * @param int $limit value of query threshold limit
	 */
	public static function setQueryLimit($limit)
	{
		//reset the queryCount
		self::$queryCount = 0;
		self::$queryLimit = $limit;
	}

	/**
	 * Returns the static queryCount value
	 *
	 * @return int value of the queryCount static variable
	 */
	public static function getQueryCount()
	{
		return self::$queryCount;
	}

    /**
     * This function takes a user input string and returns a string that contains wild card(s)
     * that can be used in the db query.
     *
     * @param string $str String to be searched.
     * @param string $wildcard (optional) Wildcard character, defaults to '%'.
     * @param bool $appendWildcard (optional) Appends the wildcard to the end of $str,
     *   defaults to true.
     * @return string Returns a string to be searched in db query.
     */
    public function sqlLikeString($str, $wildcard = '%', $appendWildcard = true)
    {
        // If we have a valid wildcard character in config, use it, or use $wildcard by default.
        // The config wildcard exists because there may be a case where a Sugar user would want
        // to use a non-standard character (e.g. '@') as a wildcard character for search.
        if (!empty($GLOBALS['sugar_config']['search_wildcard_char']) &&
            is_string($GLOBALS['sugar_config']['search_wildcard_char']) &&
            strlen($GLOBALS['sugar_config']['search_wildcard_char']) === 1
        ) {
            $likeChar = $GLOBALS['sugar_config']['search_wildcard_char'];
        } else {
            $likeChar = $wildcard;
        }

        // Add wildcard at the beginning of the search string.
        if (!empty($GLOBALS['sugar_config']['search_wildcard_infront']) &&
            substr(ltrim($str), 0, 1) !== $wildcard) {
            $str = $likeChar . $str;
        }

        // Add wildcard at the end of search string (default).
        if ($appendWildcard && substr(rtrim($str), -1) !== $wildcard) {
            $str .= $likeChar;
        }

        // Replace all instances of $likeChar with $wildcard.
        $str = str_replace($likeChar, $wildcard, $str);
        return $str;
    }

	/**
	 * Resets the queryCount value to 0
	 *
	 */
	public static function resetQueryCount()
	{
		self::$queryCount = 0;
	}

    /**
     * @param int $amount
     */
    public static function increaseQueryLimit($amount = 1)
    {
        // For admin user, $queryLimit is 0 meaning infinite.
        if (self::$queryLimit != 0) {
            self::$queryLimit += $amount;
        }
    }

    /**
     * This function increments the global $sql_queries variable
     *
     * @param string $sql The query that was just run
     */
    public function countQuery($sql = '')
    {
        global $current_user;
        //Need to use a static flag to prevent possible loops
        static $in_count_query;

        if ($in_count_query) {
            return;
        }
        $in_count_query = true;
        if (self::$queryLimit != 0 && ++self::$queryCount > self::$queryLimit
            && (empty($GLOBALS['current_user']) || !$current_user->isDeveloperForAnyModule())
        ) {
            if ($sql) {
                $GLOBALS['log']->fatal("Last query before failure:\n" . $sql);
            }
            $resourceManager = ResourceManager::getInstance();
            $resourceManager->notifyObservers('ERR_QUERY_LIMIT');
        }
        $in_count_query = false;
    }

	/**
	 * Pre-process string for quoting
	 * @internal
	 * @param string $string
     * @return string
     */
	protected function quoteInternal($string)
	{
		return $this->decodeHTML($string);
	}

	/**
	 * Return string properly quoted with ''
	 * @param string $string
	 * @return string
	 */
	public function quoted($string)
	{
		return "'".$this->quote($string)."'";
	}

	/**
     * Quote value according to type
     * Numerics aren't quoted
     * Dates are converted and quoted
     * Rest is just quoted
     * @param string $type
     * @param string $value
     * @return string Quoted value
     */
    public function quoteType($type, $value)
	{
	    if($type == 'date') {
	        return $this->convert($this->quoted($value), "date");
	    }
	    if($type == 'time') {
	        return $this->convert($this->quoted($value), "time");
	    }
        if(isset($this->type_class[$type]) &&  $this->type_class[$type] == "date") {
            return $this->convert($this->quoted($value), "datetime");
        }
        if($this->isNumericType($type)) {
            return 0+$value; // ensure it's numeric
        }

        return $this->quoted($value);
	}

	/**
	 * Get type class for certain type
	 * @param string $type
	 * @return string
	 */
	public function getTypeClass($type)
	{
	    if(isset($this->type_class[$type])) {
	        return $this->type_class[$type];
	    }
	    return 'string';
	}

    /**
     * Quote the strings of the passed in array
     *
     * The array must only contain strings
     *
     * @param array $array
     * @return array Quoted strings
     */
	public function arrayQuote(array &$array)
	{
		foreach($array as &$val) {
			$val = $this->quote($val);
		}
		return $array;
	}

    /**
     * Frees out previous results
     *
     * @param resource|bool $result optional, pass if you want to free a single result instead of all results
     */
	protected function freeResult($result = false)
	{
		if($result) {
			$this->freeDbResult($result);
		}
		if($this->lastResult) {
			$this->freeDbResult($this->lastResult);
			$this->lastResult = null;
		}
	}

	/**
	 * @abstract
	 * Check if query has LIMIT clause
	 * Relevant for now only for Mysql
	 * @param string $sql
	 * @return bool
	 */
	protected function hasLimit($sql)
	{
	    return false;
	}

	/**
	 * Runs a query and returns a single row containing single value
	 *
	 * @param  string   $sql        SQL Statement to execute
	 * @param  bool     $dieOnError True if we want to call die if the query returns errors
	 * @param  string   $msg        Message to log if error occurs
	 * @param  bool     $encode   	encode the result
	 * @return array    single value from the query
	 */
	public function getOne($sql, $dieOnError = false, $msg = '', $encode = true)
	{
		$this->log->info("Get One: |$sql|");
		if(!$this->hasLimit($sql)) {
		    $queryresult = $this->limitQuery($sql, 0, 1, $dieOnError, $msg);
		} else {
		    // support old code that passes LIMIT to sql
		    // works only for mysql, so do not rely on this
		    $queryresult = $this->query($sql, $dieOnError, $msg);
		}
		$this->checkError($msg.' Get One Failed:' . $sql, $dieOnError);
		if (!$queryresult) return false;
		$row = $this->fetchByAssoc($queryresult, $encode);

		$this->freeDbResult($queryresult);
		if(!empty($row)) {
			return array_shift($row);
		}
		return false;
	}

	/**
	 * Runs a query and returns a single row
	 *
	 * @param  string   $sql        SQL Statement to execute
	 * @param  bool     $dieOnError True if we want to call die if the query returns errors
	 * @param  string   $msg        Message to log if error occurs
	 * @param  bool     $encode   	encode the result
	 * @return array    single row from the query
	 */
	public function fetchOne($sql, $dieOnError = false, $msg = '', $encode = true)
	{
		$this->log->info("Fetch One: |$sql|");
		$this->checkConnection();
		$queryresult = $this->query($sql, $dieOnError, $msg);
		$this->checkError($msg.' Fetch One Failed:' . $sql, $dieOnError);

		if (!$queryresult) return false;

		$row = $this->fetchByAssoc($queryresult, $encode);
		$this->freeResult($queryresult);

		if ( !$row ) return false;

		return $row;
	}

	/**
	 * Runs a limit offset, 1 query and returns a single row
	 *
	 * @param  string   $sql        SQL Statement to execute
	 * @param  bool     $dieOnError True if we want to call die if the query returns errors
	 * @param  string   $msg        Message to log if error occurs
	 * @param  bool     $encode     encode result
	 * @return array    single row from the query
	 */
	public function fetchOneOffset($sql, $offset, $dieOnError = false, $msg = '', $encode = true)
	{
		$this->log->info("fetch OneOffset: |$sql|");
		$this->checkConnection();

		if(!$this->hasLimit($sql)) {
			$queryresult = $this->limitQuery($sql, $offset, 1, $dieOnError, $msg);
		} else {
			$queryresult = $this->query($sql, $dieOnError, $msg, $suppress);
		}
		$this->checkError($msg.' fetch OneOffset Failed:' . $sql, $dieOnError);
		if (!$queryresult) {
			return false;
		}
		$row = $this->fetchByAssoc($queryresult, $encode);
		$this->freeDbResult($queryresult);

		if (!$row) {
			return false;
		}

		return $row;
	}

    /**
     * Returns the number of rows affected by the last query
     * @abstract
	 * See also affected_rows capability, will return 0 unless the DB supports it
     * @param resource $result query result resource
     * @return int
     */
	public function getAffectedRowCount($result)
	{
		return 0;
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
	    return 0;
	}

	/**
     * Get table description
     * @param string $tablename
     * @param bool $reload true means load from DB, false allows using cache
     * @return array Vardef-format table description
     *
     */
	public function getTableDescription($tablename, $reload = false)
	{
		if($reload || empty(self::$table_descriptions[$tablename])) {
			self::$table_descriptions[$tablename] = $this->get_columns($tablename);
		}
		return self::$table_descriptions[$tablename];
	}

	/**
	 * Returns the field description for a given field in table
	 *
	 * @param  string $name
	 * @param  string $tablename
	 * @return array
	 */
	protected function describeField($name, $tablename)
	{
		$table = $this->getTableDescription($tablename);
		if(!empty($table) && isset($table[$name]))
			return 	$table[$name];

		$table = $this->getTableDescription($tablename, true);

		if(isset($table[$name]))
		return $table[$name];

		return array();
	}

    /**
     * Returns the min and max number that the field can store. False if not supported
     * @param array $fieldDef
     * @return array | boolean eg array('min_value'=>-2147483648, 'max_value'=>2147483647) for int field
     */
    public function getFieldRange($fieldDef)
    {
        $type = $this->getFieldType($fieldDef);

        if ($type && isset($this->type_range[$type])) {
            return $this->type_range[$type];
        }

        return false;
    }

	/**
	 * Returns the index description for a given index in table
	 *
	 * @param  string $name
	 * @param  string $tablename
	 * @return array
	 */
	protected function describeIndex($name, $tablename)
	{
		if(isset(self::$index_descriptions[$tablename]) && isset(self::$index_descriptions[$tablename]) && isset(self::$index_descriptions[$tablename][$name])){
			return 	self::$index_descriptions[$tablename][$name];
		}

		self::$index_descriptions[$tablename] = $this->get_indices($tablename);

		if(isset(self::$index_descriptions[$tablename][$name])){
			return 	self::$index_descriptions[$tablename][$name];
		}

		return array();
	}

    /**
     * Truncates a string to a given length
     *
     * @param string $string
     * @param int    $len    length to trim to
     * @return string
     *
     */
	public function truncate($string, $len)
	{
		if ( is_numeric($len) && $len > 0)
		{
			$string = mb_substr($string,0,(int) $len, "UTF-8");
		}
		return $string;
	}

    /**
     * Returns the database string needed for concatinating multiple database strings together
     *
     * @param string $table table name of the database fields to concat
     * @param array $fields fields in the table to concat together
     * @param string $space Separator between strings, default is single space
     * @return string
     */
	public function concat($table, array $fields, $space = ' ')
	{
		if(empty($fields)) return '';
		$elems = array();
		$space = $this->quoted($space);
		foreach ( $fields as $field ) {
			if(!empty($elems)) $elems[] = $space;
			$elems[] = $this->convert("$table.$field", 'IFNULL', array("''"));
		}
		$first = array_shift($elems);
		return "LTRIM(RTRIM(".$this->convert($first, 'CONCAT', $elems)."))";
	}

/********************** SQL FUNCTIONS ****************************/
    /**
     * Generates sql for create table statement for a bean.
     *
     * NOTE: does not handle out-of-table constraints, use createConstraintSQL for that
     * @param SugarBean $bean SugarBean instance
     * @return string SQL Create Table statement
     */
	public function createTableSQL(SugarBean $bean)
	{
		$tablename = $bean->getTableName();
		$fieldDefs = $bean->getFieldDefinitions();
		$indices = $bean->getIndices();
		return $this->createTableSQLParams($tablename, $fieldDefs, $indices);
	}

    /**
     * Update data in table by parameter definition
     *
     * @param string $table Table name
     * @param array $field_defs Definitions in vardef-like format
     * @param array $data Key/value for update
     * @param array $where Key/value for where
     * @return bool
     */
    public function updateParams($table, $field_defs, $data, array $where = array())
    {
        $values = array();
        foreach ($field_defs as $fieldDef) {
            $field = $fieldDef['name'];
            if (!array_key_exists($field, $data)) {
                continue;
            }
            if (isset($fieldDef['source']) && $fieldDef['source'] != 'db') {
                continue;
            }

            // If the field is an auto_increment field, then we shouldn't be setting it. This was added
            // specially for Bugs and Cases which have a number associated with them.
            if (!empty($fieldDef['auto_increment'])) {
                continue;
            }

            // custom fields handle their save separately
            if (!empty($field_map['custom_type'])) {
                continue;
            }

            // no need to clear deleted since we only update not deleted records anyway
            if ($field == 'deleted' && empty($data['deleted'])) {
                continue;
            }

            $fieldType = $this->getFieldType($fieldDef);
            $val = $this->decodeHTML($data[$field]);

            //Required fields should never be null (but they can be empty values)
            if ($val === '' && $this->isNullable($fieldDef)) {
                $val = null;
            }

            // we should care about auto_increment in update query
            if (!empty($fieldDef['auto_increment'])) {
                continue;
            } elseif ($val === null && !$this->isNullable($fieldDef)) {
                $values[$field] = $this->emptyValue($fieldType, true);
            } else {
                $values[$field] = $val;
            }
        }

        $builder = $this->getConnection()->createQueryBuilder();
        $builder->update($table);

        foreach ($values as $field => $value) {
            $builder->set(
                $field,
                $this->bindValue($builder, $value, $field_defs[$field])
            );
        }

        if (count($where) > 0) {
            $predicates = array();
            foreach ($where as $field => $value) {
                $predicates[] = $field . ' = ' . $this->bindValue($builder, $value, $field_defs[$field]);
            }

            call_user_func_array(array($builder, 'where'), $predicates);
        }

        $builder->execute();

        return true;
    }

    /**
     * Binds value to the query and returns the query fragment representing the placeholder
     *
     * @param QueryBuilder $builder Query builder
     * @param mixed $value The value to be bound
     * @param array $fieldDef Field definition
     * @return string
     */
    public function bindValue(QueryBuilder $builder, $value, array $fieldDef)
    {
        return $this->convert(
            $builder->createPositionalParameter(
                $this->massageValue($value, $fieldDef, true),
                $this->getParamType($fieldDef)
            ),
            $this->getFieldType($fieldDef)
        );
    }

    /**
     * Returns the binding type for the given field
     *
     * @param array $fieldDef Field definition
     * @return int|null
     */
    protected function getParamType(array $fieldDef)
    {
        $type = $this->getFieldType($fieldDef);
        if ($this->isBlobType($type)) {
            return \PDO::PARAM_LOB;
        }
        $typeClass = $this->getTypeClass($type);
        if ($typeClass == 'bool') {
            return \PDO::PARAM_BOOL;
        } elseif ($typeClass == 'int') {
            return \PDO::PARAM_INT;
        }
        return \PDO::PARAM_STR;
    }

	/**
	 * This method returns a where array so that it has id entry if
	 * where is not an array or is empty
	 *
	 * @param  SugarBean $bean SugarBean instance
	 * @param  array  $where Optional, where conditions in an array
	 * @return array
	 */
	protected function updateWhereArray(SugarBean $bean, array $where = array())
	{
		if (count($where) == 0) {
			$fieldDef = $bean->getPrimaryFieldDefinition();
			$primaryColumn = $fieldDef['name'];

			$val = $bean->getFieldValue($fieldDef['name']);
			if ($val != FALSE){
				$where[$primaryColumn] = $val;
			}
		}

		return $where;
	}

	/**
	 * Returns a where clause without the 'where' key word
	 *
	 * The clause returned does not have an 'and' at the beginning and the columns
	 * are joined by 'and'.
	 *
	 * @param  string $table table name
	 * @param  array  $whereArray Optional, where conditions in an array
	 * @return string
	 */
    protected function getColumnWhereClause($table, array $whereArray = array())
	{
		$where = array();
		foreach ($whereArray as $name => $val) {
			$op = "=";
			if (is_array($val)) {
				$op = "IN";
				$temp = array();
				foreach ($val as $tval){
                    $temp[] = $tval;
				}
				$val = implode(",", $temp);
				$val = "($val)";
			}

			$where[] = " $table.$name $op $val";
		}

		if (!empty($where))
			return implode(" AND ", $where);

		return '';
	}

    /**
     * Called in SearchForm and QuickSearch and overriden in OracleManager to 
     * support case insensitive search.
     *
     * @param  string $name column name
     * @param  string $value search string
     * @return string
     */
    public function getLikeSQL($name, $value)
    {
        if ($this->supports('case_insensitive')) {
            $name = 'UPPER(' . $name . ')';
            $value = strtoupper($value);
        }

        return $name . ' LIKE ' . $this->quoted($value);
    }

	/**
	 * This method returns a complete where clause built from the
	 * where values specified.
	 *
	 * @param  SugarBean $bean SugarBean that describes the table
	 * @param  array  $whereArray Optional, where conditions in an array
	 * @return string
	 */
    protected function getWhereClause(SugarBean $bean, array $whereArray = array())
    {
        $where = $this->getColumnWhereClause(
            $bean->getTableName(),
            $whereArray
        );

	    return " WHERE $where";
	}

    /**
     * Helper function for massageValue used to abstract logic for empty values.
     *
     * @param array $fieldDef Field definition.
     * @param bool $forPrepared Whether used in prepared statements or not.
     * @return mixed
     */
    protected function massageEmptyValue($fieldDef, $forPrepared)
    {
        // Required fields are not supposed to have NULLs in database
        if (!$this->isNullable($fieldDef)) {
            return $this->emptyValue($this->getFieldType($fieldDef), $forPrepared);
        } else {
            return $forPrepared ? null : "NULL";
        }
    }

    /**
     * Outputs a correct string for the sql statement according to value.
     *
     * @param mixed $val Value to massage.
     * @param array $fieldDef Field definition.
     * @param bool $forPrepared Whether used in prepared statements or not.
     *
     * @return mixed
     */
    public function massageValue($val, $fieldDef, $forPrepared = false)
    {
        $type = $this->getFieldType($fieldDef);

        if (isset($this->type_class[$type])) {
            // handle some known types
            switch ($this->type_class[$type]) {
                case 'bool':
                    return ($val === '' || is_null($val))
                            ? $this->massageEmptyValue($fieldDef, $forPrepared) : intval($val);
                case 'int':
                    return ($val === '' || is_null($val))
                            ? $this->massageEmptyValue($fieldDef, $forPrepared) : intval($val);
                case 'bigint':
                    $val = (float)$val;
                    return ($val === false || is_null($val))
                            ? $this->massageEmptyValue($fieldDef, $forPrepared) : $val;
                case 'float':
                    return ($val === '' || is_null($val))
                            ? $this->massageEmptyValue($fieldDef, $forPrepared) : floatval($val);
                case 'time':
                case 'date':
                    // empty date can't be '', so convert it to either NULL or empty date value
                    if ($val === '' || is_null($val)) {
                        return $this->massageEmptyValue($fieldDef, $forPrepared);
                    }
                    break;
            }
        } elseif (!empty($val) && !empty($fieldDef['len']) && strlen($val) > $fieldDef['len']) {
            $val = $this->truncate($val, $fieldDef['len']);
        }

        if (is_null($val)) {
            return $this->massageEmptyValue($fieldDef, $forPrepared);
        }

        if ($type == "datetimecombo") {
            $type = "datetime";
        }

        return $forPrepared ? $val : $this->convert($this->quoted($val), $type);
    }

	/**
	 * Massages the field defintions to fill in anything else the DB backend may add
	 *
	 * @param  array  $fieldDef
	 * @param  string $tablename
	 * @return array
	 */
	public function massageFieldDef(&$fieldDef, $tablename)
	{
		if ( !isset($fieldDef['dbType']) ) {
			if ( isset($fieldDef['dbtype']) )
				$fieldDef['dbType'] = $fieldDef['dbtype'];
			else
				$fieldDef['dbType'] = $fieldDef['type'];
		}
		$type = $this->getColumnType($fieldDef['dbType'],$fieldDef['name'],$tablename);
		$matches = array();
        // len can be a number or a string like 'max', for example, nvarchar(max)
        preg_match_all('/(\w+)(?:\(([0-9]+,?[0-9]*|\w+)\)|)/i', $type, $matches);
		if ( isset($matches[1][0]) )
			$fieldDef['type'] = $matches[1][0];
		if ( isset($matches[2][0]) && empty($fieldDef['len']) )
			$fieldDef['len'] = $matches[2][0];
		if ( !empty($fieldDef['precision']) && is_numeric($fieldDef['precision']) && !strstr($fieldDef['len'],',') )
			$fieldDef['len'] .= ",{$fieldDef['precision']}";
		if (!empty($fieldDef['required']) || ($fieldDef['name'] == 'id' && !isset($fieldDef['required'])) ) {
			$fieldDef['required'] = 'true';
		}
        if ($fieldDef['type'] === 'bool') {
            $fieldDef['required'] = 'true';
        }
	}

	/**
	 * Take an SQL statement and produce a list of fields used in that select
	 * @param string $selectStatement
	 * @return array
	 */
	public function getSelectFieldsFromQuery($selectStatement)
	{
		$selectStatement = trim($selectStatement);
		if (strtoupper(substr($selectStatement, 0, 6)) == "SELECT")
			$selectStatement = trim(substr($selectStatement, 6));

		//Due to sql functions existing in many selects, we can't use php explode
		$fields = array();
		$level = 0;
		$selectField = "";
		$strLen = strlen($selectStatement);
		for($i = 0; $i < $strLen; $i++)
		{
			$char = $selectStatement[$i];

			if ($char == "," && $level == 0)
			{
				$field = $this->getFieldNameFromSelect(trim($selectField));
				$fields[$field] = $selectField;
				$selectField = "";
			}
			else if ($char == "("){
				$level++;
				$selectField .= $char;
			}
			else if($char == ")"){
				$level--;
				$selectField .= $char;


			}else{
				$selectField .= $char;
			}

		}
		$fields[$this->getFieldNameFromSelect($selectField)] = $selectField;
		return $fields;
	}

	/**
	 * returns the field name used in a select
	 * @param string $string SELECT query
     * @return string
     */
	protected function getFieldNameFromSelect($string)
	{
		if(strncasecmp($string, "DISTINCT ", 9) == 0) {
			$string = substr($string, 9);
		}
		if (stripos($string, " as ") !== false)
			//"as" used for an alias
			return trim(substr($string, strripos($string, " as ") + 4));
		else if (strrpos($string, " ") != 0)
			//Space used as a delimiter for an alias
			return trim(substr($string, strrpos($string, " ")));
		else if (strpos($string, ".") !== false)
			//No alias, but a table.field format was used
			return substr($string, strpos($string, ".") + 1);
		else
			//Give up and assume the whole thing is the field name
			return $string;
	}

    /**
     * This method implements a generic sql for a collection of beans.
     *
     * Currently, this function does not support outer joins.
     *
     * @param array $beans Array of values returned by get_class method as the keys and a bean as
     *      the value for that key. These beans will be joined in the sql by the key
     *      attribute of field defs.
     * @param  array $cols Optional, columns to be returned with the keys as names of bean
     *      as identified by get_class of bean. Values of this array is the array of fieldDefs
     *      to be returned for a bean. If an empty array is passed, all columns are selected.
     * @param  array $whereClause Optional, values with the keys as names of bean as identified
     *      by get_class of bean. Each value at the first level is an array of values for that
     *      bean identified by name of fields. If we want to pass multiple values for a name,
     *      pass it as an array. If where is not passed, all the rows will be returned.
     *
     * @return string SQL Select Statement
     */
	public function retrieveViewSQL(array $beans, array $cols = array(), array $whereClause = array())
	{
		$relations = array(); // stores relations between tables as they are discovered
		$where = $select = array();
		foreach ($beans as $beanID => $bean) {
			$tableName = $bean->getTableName();
			$beanTables[$beanID] = $tableName;

			$table = $beanID;
			$tables[$table] = $tableName;
			$aliases[$tableName][] = $table;

			// build part of select for this table
			if (is_array($cols[$beanID]))
				foreach ($cols[$beanID] as $def) $select[] = $table.".".$def['name'];

			// build part of where clause
			if (is_array($whereClause[$beanID])){
				$where[] = $this->getColumnWhereClause($table, $whereClause[$beanID]);
			}
			// initialize so that it can be used properly in form clause generation
			$table_used_in_from[$table] = false;

			$indices = $bean->getIndices();
			foreach ($indices as $index){
				if ($index['type'] == 'foreign') {
					$relationship[$table][] = array('foreignTable'=> $index['foreignTable']
												,'foreignColumn'=>$index['foreignField']
												,'localColumn'=> $index['fields']
												);
				}
			}
			$where[] = " $table.deleted = 0";
		}

		// join these clauses
		$select = !empty($select) ? implode(",", $select) : "*";
		$where = implode(" AND ", $where);

		// generate the from clause. Use relations array to generate outer joins
		// all the rest of the tables will be used as a simple from
		// relations table define relations between table1 and table2 through column on table 1
		// table2 is assumed to joining through primary key called id
		$separator = "";
		$from = ''; $table_used_in_from = array();
		foreach ($relations as $table1 => $rightsidearray){
			if ($table_used_in_from[$table1]) continue; // table has been joined

			$from .= $separator." ".$table1;
			$table_used_in_from[$table1] = true;
			foreach ($rightsidearray as $tablearray){
				$table2 = $tablearray['foreignTable']; // get foreign table
				$tableAlias = $aliases[$table2]; // get a list of aliases for this table
				foreach ($tableAlias as $table2) {
					//choose first alias that does not match
					// we are doing this because of self joins.
					// in case of self joins, the same table will have many aliases.
					if ($table2 != $table1) break;
				}

				$col = $tablearray['foreingColumn'];
				$name = $tablearray['localColumn'];
				$from .= " LEFT JOIN $table on ($table1.$name = $table2.$col)";
				$table_used_in_from[$table2] = true;
			}
			$separator = ",";
		}

		return "SELECT $select FROM $from WHERE $where";
	}

	/**
	 * Generates SQL for create index statement for a bean.
	 *
	 * @param  SugarBean $bean SugarBean instance
	 * @param  array  $fields fields used in the index
	 * @param  string $name index name
	 * @param  bool   $unique Optional, set to true if this is an unique index
	 * @return string SQL Select Statement
	 */
	public function createIndexSQL(SugarBean $bean, array $fields, $name, $unique = true)
	{
		$unique = ($unique) ? "unique" : "";
		$tablename = $bean->getTableName();
		$columns = array();
		// get column names
		foreach ($fields as $fieldDef)
			$columns[] = $fieldDef['name'];

		if (empty($columns))
			return "";

		$columns = implode(",", $columns);

		return "CREATE $unique INDEX $name ON $tablename ($columns)";
	}

	/**
	 * Returns the type of the variable in the field
	 *
	 * @param  array $fieldDef Vardef-format field def
	 * @return string
	 */
	public function getFieldType($fieldDef)
	{
		// get the type for db type. if that is not set,
		// get it from type. This is done so that
		// we do not have change a lot of existing code
		// and add dbtype where type is being used for some special
		// purposes like referring to foreign table etc.
		if(!empty($fieldDef['dbType']))
			return  $fieldDef['dbType'];
		if(!empty($fieldDef['dbtype']))
			return  $fieldDef['dbtype'];
		if (!empty($fieldDef['type']))
			return  $fieldDef['type'];
		if (!empty($fieldDef['Type']))
			return  $fieldDef['Type'];
		if (!empty($fieldDef['data_type']))
			return  $fieldDef['data_type'];

		return null;
	}

    /**
     * retrieves the different components from the passed column type as it is used in the type mapping and vardefs
     * type format: <baseType>[(<len>[,<scale>])]
     * @param string $type Column type
     * @return array|bool array containing the different components of the passed in type or false in case the type contains illegal characters
     */
    public function getTypeParts($type)
    {
        if(preg_match('#(?P<type>\w+)\s*(?P<arg>\((?P<len>\w+)\s*(,\s*(?P<scale>\d+))*\))*#', $type, $matches))
        {
            $return = array();  // Not returning matches array as such as we don't want to expose the regex make up on the interface
            $return['baseType'] = $matches['type'];
            if( isset($matches['arg'])) {
                $return['arg'] = $matches['arg'];
            }
            if( isset($matches['len'])) {
                $return['len'] = $matches['len'];
            }
            if( isset($matches['scale'])) {
                $return['scale'] = $matches['scale'];
            }
            return $return;
        } else {
            return false;
        }
    }

    /**
     * Get default value for database from field definition.
     * @param array $fieldDef
     * @return string
     */
    protected function getDefaultFromDefinition($fieldDef)
    {
        $default = '';
        if (!empty($fieldDef['no_default'])) {
            // nothing to do
        } elseif ($this->getFieldType($fieldDef) == 'bool') {
            if (isset($fieldDef['default'])) {
                $value = (int) isTruthy($fieldDef['default']);
            } else {
                $value = 0;
            }
            $default = " DEFAULT " . $value;
        } elseif (isset($fieldDef['default'])) {
            $default = " DEFAULT " . $this->massageValue($fieldDef['default'], $fieldDef);
        }
        return $default;
    }

	/**
	 * Returns the defintion for a single column
	 *
	 * @param  array  $fieldDef Vardef-format field def
	 * @param  bool   $ignoreRequired  Optional, true if we should ignore this being a required field
	 * @param  string $table           Optional, table name
	 * @param  bool   $return_as_array Optional, true if we should return the result as an array instead of sql
	 * @return string or array if $return_as_array is true
	 */
	protected function oneColumnSQLRep($fieldDef, $ignoreRequired = false, $table = '', $return_as_array = false)
	{
		$name = $fieldDef['name'];
		$type = $this->getFieldType($fieldDef);
        $colType = $this->getColumnType($type);

        if($parts = $this->getTypeParts($colType))
        {
            $colBaseType = $parts['baseType'];
            $defLen = 255;
            if ($type == 'char') {
                $defLen = 254;
            }
            $defLen =  isset($parts['len']) ? $parts['len'] : $defLen; // Use the mappings length (precision) as default if it exists
        }

        if(!empty($fieldDef['len'])) {
            if (in_array($colBaseType, array( 'nvarchar', 'nchar', 'varchar', 'varchar2', 'char',
                                          'clob', 'blob', 'text', 'binary', 'varbinary'))) {
          	    $colType = "$colBaseType({$fieldDef['len']})";
            } elseif (($colBaseType == 'decimal' || $colBaseType == 'float' || $colBaseType == 'number')) {
                  if(!empty($fieldDef['precision']) && is_numeric($fieldDef['precision']))
                      if(strpos($fieldDef['len'],',') === false){
                          $colType = $colBaseType . "(".$fieldDef['len'].",".$fieldDef['precision'].")";
                      }else{
                          $colType = $colBaseType . "(".$fieldDef['len'].")";
                      }
                  else
                          $colType = $colBaseType . "(".$fieldDef['len'].")";
              }
        } else {
            if (in_array($colBaseType, array( 'nvarchar', 'nchar', 'varchar', 'varchar2', 'char'))) {
                $colType = "$colBaseType($defLen)";
            }
        }

        $default = $this->getDefaultFromDefinition($fieldDef);

		$auto_increment = '';
		if(!empty($fieldDef['auto_increment']) && $fieldDef['auto_increment'])
			$auto_increment = $this->setAutoIncrement($table , $fieldDef['name']);

		$required = 'NULL';  // MySQL defaults to NULL, SQL Server defaults to NOT NULL -- must specify
		//Starting in 6.0, only ID and auto_increment fields will be NOT NULL in the DB.
		if ((empty($fieldDef['isnull']) || strtolower($fieldDef['isnull']) == 'false') &&
			(!empty($auto_increment) || $name == 'id' || ($fieldDef['type'] == 'id' && !empty($fieldDef['required'])))) {
			$required =  "NOT NULL";
		}
		// If the field is marked both required & isnull=>false - alwqys make it not null
		// Use this to ensure primary key fields never defined as null
		if(isset($fieldDef['isnull']) && (strtolower($fieldDef['isnull']) == 'false' || $fieldDef['isnull'] === false)
			&& !empty($fieldDef['required'])) {
			$required =  "NOT NULL";
		}
		if ($ignoreRequired)
			$required = "";

		if ( $return_as_array ) {
			return array(
				'name' => $name,
				'colType' => $colType,
                'colBaseType' => $colBaseType,  // Adding base type for easier processing in derived classes
				'default' => $default,
				'required' => $required,
				'auto_increment' => $auto_increment,
				'full' => "$name $colType $default $required $auto_increment",
				);
		} else {
			return "$name $colType $default $required $auto_increment";
		}
	}

	/**
	 * Returns SQL defintions for all columns in a table
	 *
	 * @param  array  $fieldDefs  Vardef-format field def
	 * @param  bool   $ignoreRequired Optional, true if we should ignor this being a required field
	 * @param  string $tablename      Optional, table name
	 * @return string SQL column definitions
	 */
	protected function columnSQLRep($fieldDefs, $ignoreRequired = false, $tablename)
	{
		$columns = array();

		if ($this->isFieldArray($fieldDefs)) {
			foreach ($fieldDefs as $fieldDef) {
				if(!isset($fieldDef['source']) || $fieldDef['source'] == 'db') {
					$columns[] = $this->oneColumnSQLRep($fieldDef,false, $tablename);
				}
			}
			$columns = implode(",", $columns);
		}
		else {
			$columns = $this->oneColumnSQLRep($fieldDefs,$ignoreRequired, $tablename);
		}

		return $columns;
	}

	/**
	 * Returns the next value for an auto increment
	 * @abstract
	 * @param  string $table Table name
	 * @param  string $field_name Field name
	 * @return string
	 */
	public function getAutoIncrement($table, $field_name)
	{
		return "";
	}

	/**
	 * Returns the sql for the next value in a sequence
	 * @abstract
	 * @param  string $table  Table name
	 * @param  string $field_name  Field name
	 * @return string
	 */
	public function getAutoIncrementSQL($table, $field_name)
	{
		return "";
	}

	/**
	 * Either creates an auto increment through queries or returns sql for auto increment
	 * that can be appended to the end of column defination (mysql)
	 * @abstract
	 * @param  string $table Table name
	 * @param  string $field_name Field name
	 * @return string
	 */
	protected function setAutoIncrement($table, $field_name)
	{
		$this->deleteAutoIncrement($table, $field_name);
		return "";
	}

    /**
     * Sets the next auto-increment value of a column to a specific value.
     * @abstract
     * @param  string $table Table name
     * @param  string $field_name Field name
     * @param  int $start_value  Starting autoincrement value
     * @return string
     *
     */
	public function setAutoIncrementStart($table, $field_name, $start_value)
	{
		return "";
	}

	/**
	 * Deletes an auto increment
	 * @abstract
	 * @param string $table tablename
	 * @param string $field_name
	 */
	public function deleteAutoIncrement($table, $field_name)
	{
		return;
	}

	/**
	 * This method generates sql for adding a column to table identified by field def.
	 *
	 * @param  string $tablename
	 * @param  array  $fieldDefs
	 * @return string SQL statement
	 */
	public function addColumnSQL($tablename, $fieldDefs)
	{
	    return $this->changeColumnSQL($tablename, $fieldDefs, 'add');
	}

	/**
	 * This method genrates sql for altering old column identified by oldFieldDef to new fieldDef.
	 *
	 * @param  string $tablename
	 * @param  array  $newFieldDefs
	 * @param  bool  $ignorerequired Optional, true if we should ignor this being a required field
	 * @return string|array SQL statement(s)
	 */
	public function alterColumnSQL($tablename, $newFieldDefs, $ignorerequired = false)
	{
		return $this->changeColumnSQL($tablename, $newFieldDefs, 'modify', $ignorerequired);
	}

	/**
	 * Generates SQL for dropping a table.
	 *
	 * @param  SugarBean $bean Sugarbean instance
	 * @return string SQL statement
	 */
	public function dropTableSQL(SugarBean $bean)
	{
		return $this->dropTableNameSQL($bean->getTableName());
	}

	/**
	 * Generates SQL for dropping a table.
	 *
	 * @param  string $name table name
	 * @return string SQL statement
	 */
	public function dropTableNameSQL($name)
	{
		return "DROP TABLE ".$name;
	}

	/**
	 * Generates SQL for truncating a table.
	 * @param  string $name  table name
	 * @return string
	 */
	public function truncateTableSQL($name)
	{
		return "TRUNCATE $name";
	}

	/**
	 * This method generates sql that deletes a column identified by fieldDef.
	 *
	 * @param  SugarBean $bean      Sugarbean instance
	 * @param  array  $fieldDefs
	 * @return string SQL statement
	 */
	public function deleteColumnSQL(SugarBean $bean, $fieldDefs)
	{
		return $this->dropColumnSQL($bean->getTableName(), $fieldDefs);
	}

	/**
	 * This method generates sql that drops a column identified by fieldDef.
	 * Designed to work like the other addColumnSQL() and alterColumnSQL() functions
	 *
	 * @param  string $tablename
	 * @param  array  $fieldDefs
	 * @return string SQL statement
	 */
	public function dropColumnSQL($tablename, $fieldDefs)
	{
		return $this->changeColumnSQL($tablename, $fieldDefs, 'drop');
	}

    /**
     * Return a version of $proposed that can be used as a column name in any of our supported databases
     * Practically this means no longer than 25 characters as the smallest identifier length for our supported DBs is 30 chars for Oracle plus we add on at least four characters in some places (for indicies for example)
     * @param string|array $name Proposed name for the column
     * @param bool|string $ensureUnique Ensure the name is unique
     * @param string $type Name type (table, column)
     * @param bool $force Force new name
     * @return string|array Valid column name trimmed to right length and with invalid characters removed
     */
	public function getValidDBName($name, $ensureUnique = false, $type = 'column', $force = false)
	{
		if(is_array($name)) {
			$result = array();
			foreach($name as $field) {
				$result[] = $this->getValidDBName($field, $ensureUnique, $type);
			}
			return $result;
		} else {
		    if(strchr($name, ".")) {
		        // this is a compound name with dots, handle separately
		        $parts = explode(".", $name);
		        if(count($parts) > 2) {
		            // some weird name, cut to table.name
		            array_splice($parts, 0, count($parts)-2);
		        }
		        $parts = $this->getValidDBName($parts, $ensureUnique, $type, $force);
                return join(".", $parts);
		    }
			// first strip any invalid characters - all but word chars (which is alphanumeric and _)
			$name = preg_replace( '/[^\w]+/i', '', $name ) ;
			$len = strlen( $name ) ;
			$maxLen = empty($this->maxNameLengths[$type]) ? $this->maxNameLengths[$type]['column'] : $this->maxNameLengths[$type];
			if ($len <= $maxLen && !$force) {
				return strtolower($name);
			}
			if ($ensureUnique) {
				$md5str = md5($name);
				$tail = substr ( $name, -11) ;
				$temp = substr($md5str , strlen($md5str)-4 );
				$result = substr( $name, 0, 10) . $temp . $tail ;
			} else {
				$result = substr( $name, 0, 11) . substr( $name, 11 - $maxLen);
			}

			return strtolower( $result ) ;
		}
	}

	/**
	 * Returns the valid type for a column given the type in fieldDef
	 *
	 * @param  string $type field type
	 * @return string valid type for the given field
	 */
	public function getColumnType($type)
	{
		return isset($this->type_map[$type])?$this->type_map[$type]:$type;
	}

	/**
	 * Checks to see if passed array is truely an array of defitions
	 *
	 * Such an array may have type as a key but it will point to an array
	 * for a true array of definitions an to a col type for a definition only
	 *
	 * @param  mixed $defArray
	 * @return bool
	 */
	public function isFieldArray($defArray)
	{
		if ( !is_array($defArray) )
			return false;

		if ( isset($defArray['type']) ){
			// type key exists. May be an array of defs or a simple definition
			return is_array($defArray['type']); // type is not an array => definition else array
		}

		// type does not exist. Must be array of definitions
		return true;
	}

	/**
	 * returns true if the type can be mapped to a valid column type
	 *
	 * @param  string $type
	 * @return bool
	 */
	protected function validColumnType($type)
	{
		$type = $this->getColumnType($type);
		return !empty($type);
	}

	/**
	 * Generate query for audit table
	 * @param SugarBean $bean SugarBean that was changed
	 * @param array $changes List of changes, contains 'before' and 'after'
     * @param string $event_id Audit event id
     * @return string  Audit table INSERT query
     * @deprecated Use SugarBean::auditSQL()
     */
    protected function auditSQL(SugarBean $bean, $changes, $event_id)
	{
        return $bean->auditSQL($bean, $changes, $event_id);
	}

    /**
     * Saves changes to module's audit table
     *
     * @param SugarBean $bean Sugarbean instance that was changed
     * @param array $changes List of changes, contains 'before' and 'after'
     * @return bool query result
     * @deprecated Use SugarBean::saveAuditRecords()
     */
    public function save_audit_records(SugarBean $bean, $changes)
	{
        return $bean->saveAuditRecords($bean, $changes, Uuid::uuid1());
	}

    /**
     * Finds fields whose value has changed.
     * The before and after values are stored in the bean.
     * Uses $bean->fetched_row && $bean->fetched_rel_row to compare
     *
     * @param SugarBean $bean Sugarbean instance that was changed
     * @param array|null $options Array of optional arguments
     *                   field_filter => Array of filter names to be inspected (NULL means all fields)
     *                   for => Who are we getting the changes for, options are audit (default) and activity
     *                   excludeType => Types of fields to exclude
     * @return array
     */
    public function getDataChanges(SugarBean &$bean, array $options = [])
    {
        $persistedState = is_array($bean->fetched_row)
            ? array_merge($bean->fetched_row, $bean->fetched_rel_row)
            : [];

        return $this->getStateChanges($bean, $persistedState, $options);
    }

    public function getStateChanges(SugarBean $bean, array $prevState, array $options = [])
    {
        $changed_values=array();

        $fields = $bean->field_defs;

        if (!empty($options['for']) && $options['for'] == 'activity') {
            $excludeType = array('datetime');
            if (isset($options['excludeType'])) {
                $excludeType = $options['excludeType'];
            }
            $fields = $bean->getActivityEnabledFieldDefinitions($excludeType);
        } elseif (!empty($options['for']) && $options['for'] == 'audit') {
            $fields = $bean->getAuditEnabledFieldDefinitions();
        }

        if (isset($options['field_filter']) && is_array($options['field_filter'])) {
            $fields = array_intersect_key($fields, array_flip($options['field_filter']));
        }

        // remove fields which are not present in the previous state
        if (!empty($prevState)) {
            $fields = array_intersect_key($fields, $prevState);
        }

        // remove fields which do not present in the current state
        $fields = array_intersect_key($fields, (array) $bean);

        if (is_array($fields) and count($fields) > 0) {
            foreach ($fields as $field => $vardefs) {
                $before_value = $prevState[$field] ?? null;
                $after_value = $bean->$field;
                $field_type = $this->getFieldType($vardefs);

                //Because of bug #25078(sqlserver haven't 'date' type, trim extra "00:00:00" when insert into *_cstm table).
                // so when we read the audit datetime field from sqlserver, we have to replace the extra "00:00:00" again.
                if (!empty($before_value) && !empty($field_type) && $field_type == 'date') {
                    $before_value = $this->fromConvert($before_value, $field_type);
                }

                //email field contains an array so loop through and grab the addresses marked as primary for comparison
                if (!empty($field_type) && $field_type == 'email') {
                    if (!empty($bean->emailAddress) && !empty($bean->emailAddress->hasFetched)) {
                        $after_value = $bean->emailAddress->addresses;
                    }
                    if ($this->didEmailAddressesChange($before_value, $after_value)) {
                        $changed_values[$field] = array(
                            'field_name' => $field,
                            'data_type' => $field_type,
                            'before' => $before_value,
                            'after' => $after_value,
                        );
                    }

                    continue;
                }

                // if we have a type of currency, we need to convert the value into the base for the system.
                if (!empty($field_type) && $field_type === 'currency') {
                    if (empty($before_value)) {
                        //further processing expects a string, so change empty array into blank string
                        $before_value = '';
                    } else {
                        $before_value = SugarCurrency::convertAmountToBase($before_value, $bean->currency_id);
                    }
                    if (empty($after_value)) {
                        //further processing expects a string, so change empty array into blank string
                        $after_value = '';
                    } else {
                        $after_value = SugarCurrency::convertAmountToBase($after_value, $bean->currency_id);
                    }
                }

                //if the type and values match, do nothing.
                if (!($this->_emptyValue($before_value,$field_type) && $this->_emptyValue($after_value,$field_type))) {
                    $change = false;

                    $check_before = is_object($before_value)?$before_value:trim($before_value);
                    $check_after = is_object($after_value)?$after_value:trim($after_value);
                    if ($check_before !== $check_after) {
                        // Bug #42475: Don't directly compare numeric values, instead do the subtract and see if the comparison comes out to be "close enough", it is necessary for floating point numbers.
                        // Manual merge of fix 95727f2eed44852f1b6bce9a9eccbe065fe6249f from DBHelper
                        // This fix also fixes Bug #44624 in a more generic way and therefore eliminates the need for fix 0a55125b281c4bee87eb347709af462715f33d2d in DBHelper
                        if ($this->isNumericType($field_type) && !$this->isBooleanType($field_type)) {
                            if (is_string($before_value)) {
                                $before_value = trim($before_value);
                            }

                            if (is_string($after_value)) {
                                $after_value = trim($after_value);
                            }

							$before_value = empty($before_value)? 0 : $before_value;
							$after_value = empty($after_value)? 0 : $after_value;

                            $numerator = abs(2*($before_value - $after_value));
                            $denominator = abs($before_value + $after_value);
                            // detect whether to use absolute or relative error. use absolute if denominator is zero to avoid division by zero
                            $error = ($denominator == 0) ? $numerator : $numerator / $denominator;
                            if ($error >= 0.0000000001) {    // Smaller than 10E-10
                                $change = true;
                            }
                        }
                        else if ($this->isBooleanType($field_type)) {
                            if ($this->_getBooleanValue($before_value) != $this->_getBooleanValue($after_value)) {
                                $change = true;
                            }
                        }
                        else {
                            $change = true;
                        }
                        if ($change) {
                            $changed_values[$field]=array('field_name'=>$field,
                                'data_type'=>$field_type,
                                'before'=>$before_value,
                                'after'=>$after_value);
                        }
                    }
                }
            }
        }
        return $changed_values;
    }

    private function didEmailAddressesChange($before_value, $after_value)
    {
        if (!is_array($before_value) || !(is_array($after_value))) {
            return $before_value !== $after_value;
        }

        $before_addresses = array_column($before_value, 'email_address_id');
        sort($before_addresses);
        $after_addresses = array_column($after_value, 'email_address_id');
        sort($after_addresses);

        return ($before_addresses != $after_addresses);
    }

    /**
     * Uses the audit enabled fields array to find fields whose value has changed.
     * The before and after values are stored in the bean.
     * Uses $bean->fetched_row && $bean->fetched_rel_row to compare
     *
     * @param SugarBean $bean Sugarbean instance that was changed
     * @return array
     * @deprecated Use SugarBean::getAuditDataChanges()
     */
    public function getAuditDataChanges(SugarBean $bean)
    {
        $audit_fields = $bean->getAuditEnabledFieldDefinitions();
        return $this->getDataChanges($bean, array('field_filter'=>array_keys($audit_fields)));
    }

	/**
	 * Setup FT indexing
	 * @abstract
	 */
	public function full_text_indexing_setup()
	{
		// Most DBs have nothing to setup, so provide default empty function
	}

	/**
	 * Quotes a string for storing in the database
	 * @deprecated
	 * Return value will be not surrounded by quotes
	 *
	 * @param  string $string
	 * @return string
	 */
	public function escape_quote($string)
	{
		return $this->quote($string);
	}

	/**
	 * Quotes a string for storing in the database
	 * @deprecated
	 * Return value will be not surrounded by quotes
	 *
	 * @param  string $string
	 * @return string
	 */
	public function quoteFormEmail($string)
	{
		return $this->quote($string);
	}

    /**
     * Renames an index using fields definition
     *
     * @param  array  $old_definition
     * @param  array  $new_definition
     * @param  string $table_name
     * @return string SQL statement
     */
	public function renameIndexDefs($old_definition, $new_definition, $table_name)
	{
        return array(
            $this->add_drop_constraint($table_name, $old_definition, true),
            $this->add_drop_constraint($table_name, $new_definition, false),
        );
	}

	/**
	 * Check if type is boolean
	 * @param string $type
     * @return bool
     */
	public function isBooleanType($type)
	{
		return 'bool' == $type;
	}

	/**
	 * Get truth value for boolean type
	 * Allows 'off' to mean false, along with all 'empty' values
	 * @param mixed $val
     * @return bool
	 */
	protected function _getBooleanValue($val)
	{
		//need to put the === sign here otherwise true == 'non empty string'
		if (empty($val) or $val==='off')
			return false;

		return true;
	}

	/**
	 * Check if type is a number
	 * @param string $type
     * @return bool
	 */
    public function isNumericType($type)
    {
        if (isset($this->type_class[$type])) {
            $dataType = $this->type_class[$type];
            if ($dataType == 'int' || $dataType == 'float' || $dataType == 'bigint' || $dataType == 'bool') {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the value is empty value for this type
     * @param mixed $val Value
     * @param string $type Type (one of vardef types)
     * @return bool true if the value if empty
     */
	protected function _emptyValue($val, $type)
	{
		if (empty($val))
			return true;

		// Use raw empty value
		if($this->emptyValue($type, true) == $val) {
			return true;
		}
		switch ($type) {
			case 'decimal':
			case 'decimal2':
			case 'int':
			case 'double':
			case 'float':
			case 'uint':
			case 'ulong':
			case 'long':
			case 'short':
				return ($val == 0);
			case 'date':
				if ($val == '0000-00-00')
					return true;
				if ($val == 'NULL')
					return true;
				return false;
		}

		return false;
	}

	/**
     * @abstract
	 * Does this type represent text (i.e., non-varchar) value?
	 * @param string $type
     * @return bool
	 */
	public function isTextType($type)
	{
		return false;
	}

    /**
     * Does this type represent blob value?
     *
     * @param string $type
     * @return bool
     */
    public function isBlobType($type)
    {
        return false;
    }

	/**
	 * Check if this DB supports certain capability
	 * See $this->capabilities for the list
	 * @param string $cap
     * @return bool
	 */
	public function supports($cap)
	{
		return !empty($this->capabilities[$cap]);
	}

	/**
	 * Create ORDER BY clause for ENUM type field
	 * @param string $order_by Field name
	 * @param array $values Possible enum value
	 * @param string $order_dir Order direction, ASC or DESC
     * @return string
     */
	public function orderByEnum($order_by, $values, $order_dir)
	{
		$i = 0;
		$order_by_arr = array();
        $returnValue = '';
		foreach ($values as $key => $value) {
			if($key == '') {
				$order_by_arr[] = "WHEN ($order_by='' OR $order_by IS NULL) THEN $i";
			} else {
				$order_by_arr[] = "WHEN $order_by=".$this->quoted($key)." THEN $i";
			}
			$i++;

        }

        if (count($order_by_arr) > 0){
            $returnValue = "CASE ".implode("\n", $order_by_arr)." ELSE $i END $order_dir\n";
        }

        return $returnValue;
	}

    /**
     * Return representation of an empty value depending on type.
     * The value is fully quoted, converted, etc.
     *
     * @param string $type Type of value.
     * @param bool $forPrepared Whether used in prepared statements or not.
     * @return mixed Empty value.
     */
    public function emptyValue($type, $forPrepared = false)
    {
        if (isset($this->type_class[$type])) {
            switch ($this->type_class[$type]) {
                case 'bool':
                case 'int':
                case 'float':
                case 'bigint':
                    return 0;
                case 'date':
                    return $forPrepared ? null : "NULL";
            }
        }

        return $forPrepared ? "" : "''";
    }

	/**
	 * List of available collation settings
     * @abstract
	 * @return string
	 */
	public function getDefaultCollation()
	{
		return null;
	}

	/**
	 * List of available collation settings
     * @abstract
	 * @return array
	 */
	public function getCollationList()
	{
		return null;
	}

	/**
	 * Returns the number of columns in a table
	 *
	 * @param  string $table_name
	 * @return int
	 */
	public function number_of_columns($table_name)
	{
		$table = $this->getTableDescription($table_name);
		return count($table);
	}

	/**
	 * Return limit query based on given query
	 * @param string $sql
	 * @param int $start
	 * @param int $count
	 * @param bool $dieOnError
	 * @param string $msg
     * @return resource|bool query result
     * @see DBManager::limitQuery()
	 */
	public function limitQuerySql($sql, $start, $count, $dieOnError=false, $msg='')
	{
		return $this->limitQuery($sql,$start,$count,$dieOnError,$msg,false);
	}

	/**
	 * Return current time in format fit for insertion into DB (with quotes)
	 * @return string
	 */
	public function now()
	{
		return $this->convert($this->quoted(TimeDate::getInstance()->nowDb()), "datetime");
	}

	/**
	 * Check if connecting user has certain privilege
	 * @param string $privilege
     * @return bool Privilege allowed?
     */
	public function checkPrivilege($privilege)
	{
		switch($privilege) {
			case "CREATE TABLE":
				$this->query("CREATE TABLE temp (id varchar(36))");
				break;
			case "DROP TABLE":
				$sql = $this->dropTableNameSQL("temp");
				$this->query($sql);
				break;
			case "INSERT":
				$this->query("INSERT INTO temp (id) VALUES ('abcdef0123456789abcdef0123456789abcd')");
				break;
			case "UPDATE":
				$this->query("UPDATE temp SET id = '100000000000000000000000000000000000' WHERE id = 'abcdef0123456789abcdef0123456789abcd'");
				break;
			case 'SELECT':
				return $this->getOne('SELECT id FROM temp WHERE id=\'100000000000000000000000000000000000\'', false);
			case 'DELETE':
				$this->query("DELETE FROM temp WHERE id = '100000000000000000000000000000000000'");
				break;
			case "ADD COLUMN":
				$test = array("test" => array("name" => "test", "type" => "varchar", "len" => 50));
				$sql = 	$this->changeColumnSQL("temp", $test, "add");
				$this->query($sql);
				break;
			case "CHANGE COLUMN":
				$test = array("test" => array("name" => "test", "type" => "varchar", "len" => 100));
				$sql = 	$this->changeColumnSQL("temp", $test, "modify");
				$this->query($sql);
				break;
			case "DROP COLUMN":
				$test = array("test" => array("name" => "test", "type" => "varchar", "len" => 100));
				$sql = 	$this->changeColumnSQL("temp", $test, "drop");
				$this->query($sql);
				break;
			default:
				return false;
		}
		if($this->checkError("Checking privileges")) {
			return false;
		}
		return true;
	}

	/**
	 * Check if the query is a select query
	 * @param string $query
     * @return bool  Is query SELECT?
     */
	protected function isSelect($query)
	{
		$query = trim($query);
		$select_check = strpos(strtolower($query), strtolower("SELECT"));
		//Checks to see if there is union select which is valid
		$select_check2 = strpos(strtolower($query), strtolower("(SELECT"));
		if($select_check==0 || $select_check2==0){
			//Returning false means query is ok!
			return true;
		}
		return false;
	}

	/**
	 * Parse fulltext search query with mysql syntax:
	 *  terms quoted by ""
	 *  + means the term must be included
	 *  - means the term must be excluded
	 *  * or % at the end means wildcard
	 * @param string $query
	 * @return array of 3 elements - query terms, mandatory terms and excluded terms
	 */
	public function parseFulltextQuery($query)
	{
		/* split on space or comma, double quotes with \ for escape */
		if(strpbrk($query, " ,")) {
			// ("([^"]*?)"|[^" ,]+)((, )+)?
			// '/([^" ,]+|".*?[^\\\\]")(,|\s)\s*/'
			if(!preg_match_all('/("([^"]*?)"|[^"\s,]+)((,\s)+)?/', $query, $m)) {
				return false;
			}
			$qterms = $m[1];
		} else {
			$qterms = array($query);
		}
		$terms = $must_terms = $not_terms = array();
		foreach($qterms as $item) {
			if($item[0] == '"') {
				$item = trim($item, '"');
			}
			if($item[0] == '+') {
                if (strlen($item) > 1) {
                    $must_terms[] = substr($item, 1);
                }
                continue;
			}
			if($item[0] == '-') {
                if (strlen($item) > 1) {
				    $not_terms[] = substr($item, 1);
                }
                continue;
			}
			$terms[] = $item;
		}
		return array($terms, $must_terms, $not_terms);
	}

    // Methods to check respective queries
	protected $standardQueries = array(
		'ALTER TABLE' => 'verifyAlterTable',
		'DROP TABLE' => 'verifyDropTable',
		'CREATE TABLE' => 'verifyCreateTable',
		'INSERT INTO' => 'verifyInsertInto',
		'UPDATE' => 'verifyUpdate',
		'DELETE FROM' => 'verifyDeleteFrom',
	);


    /**
     * Extract table name from a query
     * @param string $query SQL query
     * @return string
     */
	protected function extractTableName($query)
	{
        $query = preg_replace('/[^A-Za-z0-9_\s]/', "", $query);
        $query = trim(str_replace(array_keys($this->standardQueries), '', $query));

        $firstSpc = strpos($query, " ");
        $end = ($firstSpc > 0) ? $firstSpc : strlen($query);
        $table = substr($query, 0, $end);

        return $table;
	}

    /**
     * Verify SQl statement using per-DB verification function
     * provided the function exists
     * @param string $query Query to verify
     * @param array $skipTables List of blacklisted tables that aren't checked
     * @return string
     */
	public function verifySQLStatement($query, $skipTables)
	{
		$query = trim($query);
		foreach($this->standardQueries as $qstart => $check) {
			if(strncasecmp($qstart, $query, strlen($qstart)) == 0) {
				if(is_callable(array($this, $check))) {
					$table = $this->extractTableName($query);
					if(!in_array($table, $skipTables)) {
						return call_user_func(array($this, $check), $table, $query);
					} else {
						$this->log->debug("Skipping table $table as blacklisted");
					}
				} else {
					$this->log->debug("No verification for $qstart on {$this->dbType}");
				}
				break;
			}
		}
		return "";
	}

	/**
	 * Tests an CREATE TABLE query
	 * @param string $table The table name to get DDL
	 * @param string $query The query to test.
	 * @return string Non-empty if error found
	 */
	protected function verifyCreateTable($table, $query)
	{
		$this->log->debug('verifying CREATE statement...');

		// rewrite DDL with _temp name
		$this->log->debug('testing query: ['.$query.']');
		$tempname = $table."__uw_temp";
		$tempTableQuery = str_replace("CREATE TABLE {$table}", "CREATE TABLE $tempname", $query);

		if(strpos($tempTableQuery, '__uw_temp') === false) {
			return 'Could not use a temp table to test query!';
		}

		$this->query($tempTableQuery, false, "Preflight Failed for: {$query}");

		$error = $this->lastError(); // empty on no-errors
		if(!empty($error)) {
			return $error;
		}

		// check if table exists
		$this->log->debug('testing for table: '.$table);
		if(!$this->tableExists($tempname)) {
			return "Failed to create temp table!";
		}

		$this->dropTableName($tempname);
		return '';
	}

	/**
	 * Execute multiple queries one after another
	 * @param array $sqls Queries
	 * @param bool $dieOnError Die on error, passed to query()
	 * @param string $msg Error message, passed to query()
	 * @param bool $suppress Supress errors, passed to query()
	 * @return resource|bool result set or success/failure bool
	 */
	public function queryArray(array $sqls, $dieOnError = false, $msg = '', $suppress = false)
	{
		$last = true;
		foreach($sqls as $sql) {
			if(!($last = $this->query($sql, $dieOnError, $msg, $suppress))) {
				break;
			}
		}
		return $last;
	}

    /**
     * Fetches the next row in the query result into an associative array
     *
     * @param  resource $result
     * @param  bool $encode Need to HTML-encode the result?
     * @param  bool $freeResult need to free Result or Statement reference
     * @return array    returns false if there are no more rows available to fetch
     */
    public function fetchByAssoc($result, $encode = true, $freeResult = false)
    {
        if (empty($result))	return false;

        if(is_int($encode) && func_num_args() == 3) {
            // old API: $result, $rowNum, $encode
            $GLOBALS['log']->deprecated("Using row number in fetchByAssoc is not portable and no longer supported. Please fix your code.");
            $encode = func_get_arg(2);
            $freeResult = false;
        }

        $row = $this->fetchRow($result);
        if ($freeResult) {
            // free DB result reference
            $this->freeDbResult($result);
        }

        if (!empty($row) && $encode && $this->encode) {
            return array_map(array($this, "encodeHTML"), $row);
        } else {
           return $row;
        }
    }

	/**
	 * Get DB driver name used for install/upgrade scripts
	 * @return string
	 */
	public function getScriptName()
	{
		// Usually the same name as dbType
		return $this->dbType;
	}

	/**
	 * Set database options
	 * Options are usually db-dependant and derive from $config['dbconfigoption']
	 * @param array $options
	 * @return DBManager
	 */
	public function setOptions($options)
	{
	    $this->options = $options;
	    return $this;
	}

	/**
	 * Get DB options
	 * @return array
	 */
	public function getOptions()
	{
	    return $this->options;
	}

	/**
	 * Get DB option by name
	 * @param string $option Option name
	 * @return mixed Option value or null if doesn't exist
	 */
	public function getOption($option)
	{
	    if(isset($this->options[$option])) {
	        return $this->options[$option];
	    }
	    return null;
	}

    /**
     * Set DB option
     *
     * @param string $option Option name
     * @param mixed $value Option value
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

	/**
	 * Commits pending changes to the database when the driver is setup to support transactions.
	 * Note that the default implementation is applicable for transaction-less or auto commit scenarios.
	 * @abstract
	 * @return bool true if commit succeeded, false if it failed
	 */
	public function commit()
	{
		$this->log->info("DBManager.commit() stub");
		return true;
	}

	/**
	 * Rollsback pending changes to the database when the driver is setup to support transactions.
	 * Note that the default implementation is applicable for transaction-less or auto commit scenarios.
	 * Since rollbacks cannot be done, this implementation always returns false.
	 * @abstract
	 * @return bool true if rollback succeeded, false if it failed
	 */
	public function rollback()
	{
		$this->log->info("DBManager.rollback() stub");
		return false;
	}

	/**
	 * Check if this DB name is valid
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isDatabaseNameValid($name)
	{
		// Generic case - no slashes, no dots
		return preg_match('#[/.\\\\]#', $name)==0;
	}

    /**
     * Generates the a recursive SQL query or equivalent stored procedure implementation.
     * The DBManager's default implementation is based on SQL-99's recursive common table expressions.
     * Databases supporting recursive CTEs (such as SQL server) only need to set the recursive_query capability to true
     * @param string    $tablename       table name
     * @param string    $key             primary key field name
     * @param string    $parent_key      foreign key field name self referencing the table
     * @param string    $fields          list of fields that should be returned. The pseudocolumn "level" may be in the list
     * @param bool      $lineage         find the lineage, if false, find the children
     * @param string    $startWith       identifies starting element(s) as in a where clause
     * @param string    $max_level       when not null is the maximum number of levels to traverse in the the tree
	 * @return string               Recursive SQL query or equivalent representation.
     */
    public function getRecursiveSelectSQL($tablename, $key, $parent_key, $fields, $lineage = false, $startWith = null, $level = null, $whereClause = null)
    {

        if($lineage) {
            $connectWhere = "e.$key = sg.$parent_key";  // Search up the tree to get lineage
        } else {
            $connectWhere = "sg.$key = e.$parent_key";  // Search down the tree to find children
        }

        if(!empty($startWith)) {
            $startWith = 'WHERE ' . $startWith;
        } else {
            $startWith = '';
        }

        // cleanup WHERE clause
        if (empty($whereClause)) {
			 $whereClause = '';
		}
		else {
			$whereClause = ltrim($whereClause);
			if (strtoupper(substr($whereClause, 0, 5)) == 'WHERE' ) {   // remove WHERE
				$whereClause = substr($whereClause, 6);
            }
            if (strtoupper(substr($whereClause, 0, 4)) != 'AND ' ) {  // Add AND
                $whereClause = "AND $whereClause";
            }
            $whereClause .= ' ';  // make sure there is a trailing blank
		}

        // compose level clause of query if Level is in the fieldList passed
		$tokens = explode(',', $fields);
		$fieldsTop = "";
		$fieldsBottom = "";
		$delimiter = "";
		foreach ($tokens as $token) {
			if (trim($token) == '_level') {
	            $fieldsTop = $fieldsTop . $delimiter . " 1 as _level";
		        $fieldsBottom = $fieldsBottom . $delimiter . " sg._level + 1 as _level";
                $delimiter = ",";
			}
			else {
	            $fieldsTop = $fieldsTop . $delimiter . $token;
		        $fieldsBottom = $fieldsBottom . $delimiter . "e.$token";
                $delimiter = ",";
			}
		}

        $sql = "WITH search_graph AS (
                   SELECT $fieldsTop
                   FROM $tablename e
                   $startWith $whereClause
                 UNION ALL
                   SELECT $fieldsBottom
                   FROM $tablename e, search_graph sg
                   WHERE $connectWhere $whereClause
                )
                SELECT * FROM search_graph";

        return $sql;
    }

    /**
     * Get the list of reserved words
     * @return array
     */
    public function getReservedWords()
    {
        return self::$reserved_words;
    }

    /**
     * Check if the word is reserved word
     * @param string $word
     * @return boolean
     */
    public function isReservedWord($word)
    {
        return !empty(self::$reserved_words[strtoupper($word)]);
    }

	/**
	 * Check special requirements for DB installation.
	 * @abstract
	 * If everything is OK, return true.
	 * If something's wrong, return array of error code and parameters
	 * @return mixed
	 */
	public function canInstall()
	{
		return true;
	}

	/**
	 * @abstract
     * Code run on new database before installing
	 */
	public function preInstall()
	{
	}

	/**
     * @abstract
	 * Code run on new database after installing
	 */
	public function postInstall()
	{
	}

	/**
	 * Disable keys on the table
	 * @abstract
	 * @param string $tableName
	 */
	public function disableKeys($tableName)
	{
	}

	/**
	 * Re-enable keys on the table
	 * @abstract
	 * @param string $tableName
	 */
	public function enableKeys($tableName)
	{
	}

    /**
    * Updates all tables to match the specified collation
    * @abstract
    * @param string $collation Collation to set
    */
    public function setCollation($collation)
    {
    }

	/**
	 * Quote string in DB-specific manner
	 * @param string $string
	 * @return string
	 */
	abstract public function quote($string);

	/**
	 * Use when you need to convert a database string to a different value; this function does it in a
	 * database-backend aware way
	 * Supported conversions:
	 *      today		return current date
	 *      left		Take substring from the left
	 *      date_format	Format date as string, supports %Y-%m-%d, %Y-%m, %Y
     *      time_format Format time as string
     *      date        Convert date string to datetime value
     *      time        Convert time string to datetime value
	 *      datetime	Convert datetime string to datetime value
	 *      ifnull		If var is null, use default value
	 *      concat		Concatenate strings
	 *      quarter		Quarter number of the date
	 *      length		Length of string
	 *      month		Month number of the date
	 *      add_date	Add specified interval to a date
     *      add_time    Add time interval to a date
     *      text2char   Convert text field to varchar
	 *
	 * @param string $string database string to convert
	 * @param string $type type of conversion to do
	 * @param array  $additional_parameters optional, additional parameters to pass to the db function
	 * @return string
	 */
	abstract public function convert($string, $type, array $additional_parameters = array());

	/**
	 * Converts from Database data to app data
	 *
	 * Supported types
	 * - date
	 * - time
	 * - datetime
     * - datetimecombo
     * - timestamp
	 *
	 * @param string $string database string to convert
	 * @param string $type type of conversion to do
	 * @return string
	 */
	abstract public function fromConvert($string, $type);

    /**
     * Parses and runs queries
     *
     * @param  string   $sql        SQL Statement to execute
     * @param  bool     $dieOnError True if we want to call die if the query returns errors
     * @param  string   $msg        Message to log if error occurs
     * @param  bool     $suppress   Flag to suppress all error output unless in debug logging mode.
     * @param  bool     $keepResult Keep query result in the object?
     * @return resource|bool result set or success/failure bool
     */
	abstract public function query($sql, $dieOnError = false, $msg = '', $suppress = false, $keepResult = false);

    /**
     * Runs a limit query: one where we specify where to start getting records and how many to get
     *
     * @param  string   $sql     SELECT query
     * @param  int      $start   Starting row
     * @param  int      $count   How many rows
     * @param  boolean  $dieOnError  True if we want to call die if the query returns errors
     * @param  string   $msg     Message to log if error occurs
     * @param  bool     $execute Execute or return SQL?
     * @return resource query result
     */
	abstract function limitQuery($sql, $start, $count, $dieOnError = false, $msg = '', $execute = true);


	/**
	 * Free Database result
	 * @param resource $dbResult
	 */
	abstract protected function freeDbResult($dbResult);

	/**
	 * Rename column in the DB
	 * @param string $tablename
	 * @param string $column
	 * @param string $newname
	 */
	abstract function renameColumnSQL($tablename, $column, $newname);

    /**
     * Returns definitions of all indices for current schema.
     *
     * @return array
     */
    public function get_schema_indices()
    {
        return $this->get_index_data();
    }

	/**
	 * Returns definitions of all indies for passed table.
	 *
	 * return will is a multi-dimensional array that
	 * categorizes the index definition by types, unique, primary and index.
	 * <code>
	 * <?php
	 * array(                                                              O
	 *       'index1'=> array (
	 *           'name'   => 'index1',
	 *           'type'   => 'primary',
	 *           'fields' => array('field1','field2')
	 *           )
	 *       )
	 * ?>
	 * </code>
	 * This format is similar to how indicies are defined in vardef file.
	 *
     * @param string $table_name Table name
	 * @return array
	 */
    public function get_indices($table_name)
    {
        $data = $this->get_index_data($table_name);
        if (isset($data[$table_name])) {
            return $data[$table_name];
        }

        return array();
    }

    /**
     * Returns definitions of the given index.
     *
     * @param string $table_name Table name
     * @param string $index_name Index name
     *
     * @return array
     */
    public function get_index($table_name, $index_name)
    {
        $data = $this->get_index_data($table_name, $index_name);
        if (isset($data[$table_name][$index_name])) {
            return $data[$table_name][$index_name];
        }

        return array();
    }

    /**
     * Returns information of all indices matching the given criteria.
     *
     * @param string $table_name Table name
     * @param string $index_name Index name
     *
     * @return array
     */
    abstract protected function get_index_data($table_name = null, $index_name = null);

	/**
	 * Returns definitions of all indies for passed table.
	 *
	 * return will is a multi-dimensional array that
	 * categorizes the index definition by types, unique, primary and index.
	 * <code>
	 * <?php
	 * array(
	 *       'field1'=> array (
	 *           'name'   => 'field1',
	 *           'type'   => 'varchar',
	 *           'len' => '200'
	 *           )
	 *       )
	 * ?>
	 * </code>
	 * This format is similar to how indicies are defined in vardef file.
	 *
	 * @param  string $tablename
	 * @return array
	 */
	abstract public function get_columns($tablename);

	/**
	 * Generates alter constraint statement given a table name and vardef definition.
	 *
	 * Supports both adding and droping a constraint.
	 *
	 * @param  string $table      tablename
	 * @param  array  $definition field definition
	 * @param  bool   $drop       true if we are dropping the constraint, false if we are adding it
	 * @return string SQL statement
	 */
	abstract public function add_drop_constraint($table, $definition, $drop = false);

	/**
	 * Returns the description of fields based on the result
	 *
	 * @param  resource $result
	 * @param  boolean  $make_lower_case
	 * @return array field array
	 */
	abstract public function getFieldsArray($result, $make_lower_case = false);

	/**
	 * Returns an array of tables for this database
	 *
	 * @return	array|false 	an array of with table names, false if no tables found
	 */
	abstract public function getTablesArray();

	/**
	 * Return's the version of the database
	 *
	 * @return string
	 */
	abstract public function version();

	/**
	 * to check if a table with the name $tableName exists
	 * and returns true if it does or false otherwise
	 *
	 * @param  string $tableName
	 * @return bool
	 */
	abstract public function tableExists($tableName);


	/**
	 * Fetches the next row in the query result into an associative array
	 *
	 * @param  resource $result
	 * @return array    returns false if there are no more rows available to fetch
	 */
	abstract public function fetchRow($result);

	/**
	 * Connects to the database backend
	 *
	 * Takes in the database settings and opens a database connection based on those
	 * will open either a persistent or non-persistent connection.
	 * If a persistent connection is desired but not available it will defualt to non-persistent
	 *
	 * configOptions must include
	 * db_host_name - server ip
	 * db_user_name - database user name
	 * db_password - database password
	 *
	 * @param array   $configOptions
	 * @param boolean $dieOnError
	 */
	abstract public function connect(array $configOptions = null, $dieOnError = false);

	/**
	 * Generates sql for create table statement for a bean.
	 *
	 * @param  string $tablename
	 * @param  array  $fieldDefs
	 * @param  array  $indices
	 * @return string SQL Create Table statement
	 */
	abstract public function createTableSQLParams($tablename, $fieldDefs, $indices);

	/**
	 * Generates the SQL for changing columns
	 *
	 * @param string $tablename
	 * @param array  $fieldDefs
	 * @param string $action
	 * @param bool   $ignoreRequired Optional, true if we should ignor this being a required field
	 * @return string|array
	 */
	abstract protected function changeColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired = false);

	/**
	 * Disconnects from the database
	 *
	 * Also handles any cleanup needed
	 */
    public function disconnect()
    {
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }

        // dependency injection container holds a reference to the underlying DBAL connection,
        // so it should be re-instantiated after re-connection
        Container::resetInstance();
    }

	/**
	 * Get last database error
	 * This function should return last error as reported by DB driver
	 * and should return false if no error condition happened
	 * @return string|false Error message or false if no error happened
	 */
	abstract public function lastDbError();

    /**
     * Check if this query is valid
     * Validates only SELECT queries
     * @param string $query
     * @return bool
     */
	abstract public function validateQuery($query);

	/**
	 * Check if this driver can be used
	 * @return bool
	 */
	abstract public function valid();

	/**
	 * Check if certain database exists
	 * @param string $dbname
	 */
	abstract public function dbExists($dbname);

	/**
	 * Get tables like expression
	 * @param string $like Expression describing tables
	 * @return array
	 */
	abstract public function tablesLike($like);

	/**
	 * Create a database
	 * @param string $dbname
	 */
	abstract public function createDatabase($dbname);

	/**
	 * Drop a database
	 * @param string $dbname
	 */
	abstract public function dropDatabase($dbname);

	/**
	 * Get database configuration information (DB-dependent)
	 * @return array|null
	 */
	abstract public function getDbInfo();

	/**
	 * Check if certain DB user exists
	 * @param string $username
	 */
	abstract public function userExists($username);

	/**
	 * Create DB user
	 * @param string $database_name
	 * @param string $host_name
	 * @param string $user
	 * @param string $password
	 */
	abstract public function createDbUser($database_name, $host_name, $user, $password);

	/**
	 * Check if the database supports fulltext indexing
	 * Note that database driver can be capable of supporting FT (see supports('fulltext))
	 * but particular instance can still have it disabled
	 * @return bool
	 */
	abstract public function full_text_indexing_installed();

	/**
	 * Generate fulltext query from set of terms
	 * @param string $field Field to search against
	 * @param array $terms Search terms that may be or not be in the result
	 * @param array $must_terms Search terms that have to be in the result
	 * @param array $exclude_terms Search terms that have to be not in the result
	 */
	abstract public function getFulltextQuery($field, $terms, $must_terms = array(), $exclude_terms = array());

	/**
	 * Get install configuration for this DB
	 * @return array
	 */
	abstract public function installConfig();

    /**
     * Returns a DB specific FROM clause which can be used to select against functions.
     * Note that depending on the database that this may also be an empty string.
     * @abstract
     * @return string
     */
    abstract public function getFromDummyTable();

    /**
     * Returns a DB specific piece of SQL which will generate GUID (UUID)
     * This string can be used in dynamic SQL to do multiple inserts with a single query.
     * I.e. generate a unique Sugar id in a sub select of an insert statement.
     * @abstract
     * @return string
     */
	abstract public function getGuidSQL();

	/**
	 * List of SQL reserved words
	 * Column can not be named as one of these
	 * Sources:
	 * http://msdn.microsoft.com/en-us/library/aa238507(SQL.80).aspx
	 * http://dev.mysql.com/doc/refman/5.0/en/reserved-words.html
	 * @var array
	 */
    public static $reserved_words = array('ACCESS' => true, 'ACCESSIBLE' => true, 'ADD' => true,
        'AFTER' => true, 'ALL' => true, 'ALLOCATE' => true, 'ALLOW' => true, 'ALTER' => true,
        'ANALYZE' => true, 'AND' => true, 'ANY' => true, 'AS' => true, 'ASC' => true,
        'ASENSITIVE' => true, 'ASSOCIATE' => true, 'ASUTIME' => true, 'AT' => true, 'AUDIT' => true,
        'AUTHORIZATION' => true, 'AUX' => true, 'AUXILIARY' => true, 'BACKUP' => true,
        'BEFORE' => true, 'BEGIN' => true, 'BETWEEN' => true, 'BFILE' => true, 'BIG' => true,
        'BIGINT' => true, 'BINARY' => true, 'BINARY_INTEGER' => true, 'BIND' => true,
        'BINLOG' => true, 'BLOB' => true, 'BOTH' => true, 'BREAK' => true, 'BROWSE' => true,
        'BUFFERPOOL' => true, 'BULK' => true, 'BY' => true, 'CALC' => true, 'CALL' => true,
        'CAPTURE' => true, 'CASCADE' => true, 'CASCADED' => true, 'CASE' => true, 'CAST' => true,
        'CCSID' => true, 'CEILING' => true, 'CERT' => true, 'CHANGE' => true, 'CHAR' => true,
        'CHARACTER' => true, 'CHECK' => true, 'CHECKPOINT' => true, 'CLOB' => true, 'CLONE' => true,
        'CLOSE' => true, 'CLUSTER' => true, 'CLUSTERED' => true, 'COALESCE' => true,
        'COLLATE' => true, 'COLLECTION' => true, 'COLLID' => true, 'COLUMN' => true,
        'COMMENT' => true, 'COMMIT' => true, 'COMPRESS' => true, 'COMPUTE' => true, 'CONCAT' => true,
        'CONDITION' => true, 'CONNECT' => true, 'CONNECTION' => true, 'CONSTRAINT' => true,
        'CONTAINS' => true, 'CONTAINSTABLE' => true, 'CONTENT' => true, 'CONTINUE' => true,
        'CONVERT' => true, 'CREATE' => true, 'CROSS' => true, 'CTYPE' => true, 'CURRENT' => true,
        'CURRENT_DATE' => true, 'CURRENT_TIME' => true, 'CURRENT_TIMESTAMP' => true,
        'CURRENT_USER' => true, 'CURRVAL' => true, 'CURSOR' => true, 'DATA' => true,
        'DATABASE' => true, 'DATABASES' => true, 'DATE' => true, 'DAY' => true, 'DAYS' => true,
        'DAY_HOUR' => true, 'DAY_MICROSECOND' => true, 'DAY_MINUTE' => true, 'DAY_SECOND' => true,
        'DBCC' => true, 'DBINFO' => true, 'DEALLOCATE' => true, 'DEC' => true, 'DECIMAL' => true,
        'DECLARE' => true, 'DEFAULT' => true, 'DELAYED' => true, 'DELETE' => true, 'DENY' => true,
        'DESC' => true, 'DESCRIBE' => true, 'DESCRIPTOR' => true, 'DETERMINISTIC' => true,
        'DISABLE' => true, 'DISALLOW' => true, 'DISK' => true, 'DISTINCT' => true,
        'DISTINCTROW' => true, 'DISTRIBUTED' => true, 'DIV' => true, 'DO' => true,
        'DOCUMENT' => true, 'DOUBLE' => true, 'DOWN' => true, 'DROP' => true, 'DSSIZE' => true,
        'DUAL' => true, 'DUMMY' => true, 'DUMP' => true, 'DYNAMIC' => true, 'EACH' => true,
        'EDITPROC' => true, 'ELSE' => true, 'ELSEIF' => true, 'ENCLOSED' => true, 'ENCODING' => true,
        'ENCRYPTION' => true, 'END' => true, 'ENDING' => true, 'ERASE' => true, 'ERRLVL' => true,
        'ESCAPE' => true, 'ESCAPED' => true, 'EVEN' => true, 'EXCEPT' => true, 'EXCEPTION' => true,
        'EXCLUSIVE' => true, 'EXEC' => true, 'EXECUTE' => true, 'EXISTS' => true, 'EXIT' => true,
        'EXPLAIN' => true, 'EXTERNAL' => true, 'FALSE' => true, 'FENCED' => true, 'FETCH' => true,
        'FIELDPROC' => true, 'FILE' => true, 'FILLFACTOR' => true, 'FINAL' => true, 'FIRST' => true,
        'FLOAT' => true, 'FLOAT4' => true, 'FLOAT8' => true, 'FLOOR' => true, 'FOR' => true,
        'FORCE' => true, 'FOREIGN' => true, 'FOUND' => true, 'FREE' => true, 'FREETEXT' => true,
        'FREETEXTTABLE' => true, 'FROM' => true, 'FULL' => true, 'FULLTEXT' => true,
        'FUNCTION' => true, 'GENERATED' => true, 'GET' => true, 'GLOBAL' => true, 'GO' => true,
        'GOTO' => true, 'GRANT' => true, 'GROUP' => true, 'GTIDS' => true, 'HALF' => true,
        'HANDLER' => true, 'HAVING' => true, 'HIGH' => true, 'HIGH_PRIORITY' => true, 'HOLD' => true,
        'HOLDLOCK' => true, 'HOUR' => true, 'HOURS' => true, 'HOUR_MICROSECOND' => true,
        'HOUR_MINUTE' => true, 'HOUR_SECOND' => true, 'IDENTIFIED' => true, 'IDENTITY' => true,
        'IDENTITYCOL' => true, 'IDENTITY_INSERT' => true, 'IF' => true, 'IGNORE' => true,
        'IMMEDIATE' => true, 'IN' => true, 'INCLUSIVE' => true, 'INCREMENT' => true, 'INDEX' => true,
        'INFILE' => true, 'INHERIT' => true, 'INITIAL' => true, 'INNER' => true, 'INOUT' => true,
        'INSENSITIVE' => true, 'INSERT' => true, 'INT' => true, 'INT1' => true, 'INT2' => true,
        'INT3' => true, 'INT4' => true, 'INT8' => true, 'INTEGER' => true, 'INTERSECT' => true,
        'INTERVAL' => true, 'INTO' => true, 'IO' => true, 'IS' => true, 'ISOBID' => true,
        'ITERATE' => true, 'JAR' => true, 'JOIN' => true, 'KEEP' => true, 'KEY' => true,
        'KEYS' => true, 'KILL' => true, 'LABEL' => true, 'LANGUAGE' => true, 'LAST' => true,
        'LC' => true, 'LEADING' => true, 'LEAVE' => true, 'LEFT' => true, 'LEVEL' => true,
        'LIKE' => true, 'LIMIT' => true, 'LINEAR' => true, 'LINENO' => true, 'LINES' => true,
        'LOAD' => true, 'LOCAL' => true, 'LOCALE' => true, 'LOCALTIME' => true,
        'LOCALTIMESTAMP' => true, 'LOCATOR' => true, 'LOCATORS' => true, 'LOCK' => true,
        'LOCKMAX' => true, 'LOCKSIZE' => true, 'LONG' => true, 'LONGBLOB' => true,
        'LONGTEXT' => true, 'LOOP' => true, 'LOW' => true, 'LOW_PRIORITY' => true,
        'MAINTAINED' => true, 'MASTER' => true, 'MATCH' => true, 'MATERIALIZED' => true,
        'MAXEXTENTS' => true, 'MAXVALUE' => true, 'MEDIUMBLOB' => true, 'MEDIUMINT' => true,
        'MEDIUMTEXT' => true, 'MICROSECOND' => true, 'MICROSECONDS' => true, 'MIDDLEINT' => true,
        'MINUS' => true, 'MINUTE' => true, 'MINUTES' => true, 'MINUTE_MICROSECOND' => true,
        'MINUTE_SECOND' => true, 'MLSLABEL' => true, 'MOD' => true, 'MODE' => true,
        'MODIFIES' => true, 'MODIFY' => true, 'MONTH' => true, 'MONTHS' => true, 'NATIONAL' => true,
        'NATURAL' => true, 'NCHAR' => true, 'NCLOB' => true, 'NEXT' => true, 'NEXTVAL' => true,
        'NO' => true, 'NOAUDIT' => true, 'NOCHECK' => true, 'NOCOMPRESS' => true,
        'NONBLOCKING' => true, 'NONCLUSTERED' => true, 'NONE' => true, 'NOT' => true,
        'NOWAIT' => true, 'NO_WRITE_TO_BINLOG' => true, 'NULL' => true, 'NULLIF' => true,
        'NULLS' => true, 'NUMBER' => true, 'NUMERIC' => true, 'NUMPARTS' => true,
        'NVARCHAR2' => true, 'OBID' => true, 'OF' => true, 'OFF' => true, 'OFFLINE' => true,
        'OFFSETS' => true, 'OLD' => true, 'ON' => true, 'ONLINE' => true, 'OPEN' => true,
        'OPENCONNECTOR' => true, 'OPENQUERY' => true, 'OPENROWSET' => true, 'OPENXML' => true,
        'OPTIMIZATION' => true, 'OPTIMIZE' => true, 'OPTION' => true, 'OPTIONALLY' => true,
        'OR' => true, 'ORDER' => true, 'ORGANIZATION' => true, 'OUT' => true, 'OUTER' => true,
        'OUTFILE' => true, 'OVER' => true, 'PACKAGE' => true, 'PADDED' => true, 'PARAMETER' => true,
        'PART' => true, 'PARTITION' => true, 'PARTITIONED' => true, 'PARTITIONING' => true,
        'PATH' => true, 'PCTFREE' => true, 'PERCENT' => true, 'PERIOD' => true, 'PIECESIZE' => true,
        'PLAN' => true, 'PLS_INTEGER' => true, 'PRECISION' => true, 'PREPARE' => true,
        'PREVVAL' => true, 'PRIMARY' => true, 'PRINT' => true, 'PRIOR' => true, 'PRIORITY' => true,
        'PRIQTY' => true, 'PRIVILEGES' => true, 'PROC' => true, 'PROCEDURE' => true,
        'PROGRAM' => true, 'PSID' => true, 'PUBLIC' => true, 'PURGE' => true, 'QUERY' => true,
        'QUERYNO' => true, 'RAISERROR' => true, 'RANGE' => true, 'RAW' => true, 'READ' => true,
        'READS' => true, 'READTEXT' => true, 'REAL' => true, 'RECONFIGURE' => true,
        'REFERENCES' => true, 'REFRESH' => true, 'REGEXP' => true, 'RELEASE' => true,
        'RENAME' => true, 'REPEAT' => true, 'REPLACE' => true, 'REPLICATION' => true,
        'REQUIRE' => true, 'RESIGNAL' => true, 'RESOURCE' => true, 'RESTORE' => true,
        'RESTRICT' => true, 'RESULT' => true, 'RETURN' => true, 'RETURNS' => true, 'REVOKE' => true,
        'RIGHT' => true, 'RLIKE' => true, 'ROLE' => true, 'ROLLBACK' => true, 'ROUND' => true,
        'ROW' => true, 'ROWCOUNT' => true, 'ROWGUIDCOL' => true, 'ROWID' => true, 'ROWNUM' => true,
        'ROWS' => true, 'ROWSET' => true, 'RULE' => true, 'RUN' => true, 'SAVE' => true,
        'SAVEPOINT' => true, 'SCHEMA' => true, 'SCHEMAS' => true, 'SCRATCHPAD' => true,
        'SECOND' => true, 'SECONDS' => true, 'SECOND_MICROSECOND' => true, 'SECQTY' => true,
        'SECURITY' => true, 'SELECT' => true, 'SENSITIVE' => true, 'SEPARATOR' => true,
        'SEQUENCE' => true, 'SERVER' => true, 'SESSION' => true, 'SESSION_USER' => true,
        'SET' => true, 'SETUSER' => true, 'SHARE' => true, 'SHOW' => true, 'SHUTDOWN' => true,
        'SIGNAL' => true, 'SIMPLE' => true, 'SIZE' => true, 'SMALL' => true, 'SMALLINT' => true,
        'SOME' => true, 'SONAME' => true, 'SOURCE' => true, 'SPATIAL' => true, 'SPECIFIC' => true,
        'SQL' => true, 'SQLEXCEPTION' => true, 'SQLSTATE' => true, 'SQLWARNING' => true,
        'SQL_BIG_RESULT' => true, 'SQL_CALC_FOUND_ROWS' => true, 'SQL_SMALL_RESULT' => true,
        'SSL' => true, 'STANDARD' => true, 'START' => true, 'STARTING' => true, 'STATEMENT' => true,
        'STATIC' => true, 'STATISTICS' => true, 'STAY' => true, 'STOGROUP' => true, 'STORES' => true,
        'STRAIGHT' => true, 'STRAIGHT_JOIN' => true, 'STYLE' => true, 'SUCCESSFUL' => true,
        'SUMMARY' => true, 'SYNONYM' => true, 'SYSDATE' => true, 'SYSTEM_USER' => true,
        'SYSTIMESTAMP' => true, 'TABLE' => true, 'TABLESPACE' => true, 'TERMINATED' => true,
        'TEXTSIZE' => true, 'THEN' => true, 'TIME' => true, 'TIMESTAMP' => true, 'TINYBLOB' => true,
        'TINYINT' => true, 'TINYTEXT' => true, 'TO' => true, 'TOP' => true, 'TRAILING' => true,
        'TRAN' => true, 'TRANSACTION' => true, 'TRIGGER' => true, 'TRUE' => true, 'TRUNCATE' => true,
        'TSEQUAL' => true, 'TYPE' => true, 'UID' => true, 'UNDO' => true, 'UNION' => true,
        'UNIQUE' => true, 'UNLOCK' => true, 'UNSIGNED' => true, 'UNTIL' => true, 'UP' => true,
        'UPDATE' => true, 'UPDATETEXT' => true, 'UPGRADE' => true, 'UROWID' => true, 'USAGE' => true,
        'USE' => true, 'USER' => true, 'USING' => true, 'UTC' => true, 'UTC_DATE' => true,
        'UTC_TIME' => true, 'UTC_TIMESTAMP' => true, 'VALIDATE' => true, 'VALIDPROC' => true,
        'VALUE' => true, 'VALUES' => true, 'VARBINARY' => true, 'VARCHAR' => true,
        'VARCHAR2' => true, 'VARCHARACTER' => true, 'VARIABLE' => true, 'VARIANT' => true,
        'VARYING' => true, 'VCAT' => true, 'VERIFY' => true, 'VIEW' => true, 'VOLATILE' => true,
        'VOLUMES' => true, 'WAITFOR' => true, 'WHEN' => true, 'WHENEVER' => true, 'WHERE' => true,
        'WHILE' => true, 'WITH' => true, 'WLM' => true, 'WRITE' => true, 'WRITETEXT' => true,
        'XMLCAST' => true, 'XMLEXISTS' => true, 'XMLNAMESPACES' => true, 'XMLTYPE' => true,
        'XOR' => true, 'YEAR' => true, 'YEARS' => true, 'YEAR_MONTH' => true, 'ZEROFILL' => true,
        'ZEROFILLADD' => true, 'ZONE' => true);

    /**
     * Adapts indices for a concrete database
     *
     * @param $fieldDefs
     * @param $indices
     * @return array
     */
    protected function massageIndexDefs($fieldDefs, $indices)
    {
        if (!$this->isFieldArray($indices)) {
            $indices = array($indices);
        }

        return $indices;
    }
}
