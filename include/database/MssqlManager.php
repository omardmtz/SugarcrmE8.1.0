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
* $Id: MssqlManager.php 56825 2010-06-04 00:09:04Z smalyshev $
* Description: This file handles the Data base functionality for the application.
* It acts as the DB abstraction layer for the application. It depends on helper classes
* which generate the necessary SQL. This sql is then passed to PEAR DB classes.
* The helper class is chosen in DBManagerFactory, which is driven by 'db_type' in 'dbconfig' under config.php.
*
* All the functions in this class will work with any bean which implements the meta interface.
* The passed bean is passed to helper class which uses these functions to generate correct sql.
*
* The meta interface has the following functions:
* getTableName()	        	Returns table name of the object.
* getFieldDefinitions()	    	Returns a collection of field definitions in order.
* getFieldDefintion(name)		Return field definition for the field.
* getFieldValue(name)	    	Returns the value of the field identified by name.
*                           	If the field is not set, the function will return boolean FALSE.
* getPrimaryFieldDefinition()	Returns the field definition for primary key
*
* The field definition is an array with the following keys:
*
* name 		This represents name of the field. This is a required field.
* type 		This represents type of the field. This is a required field and valid values are:
*      		int
*      		long
*      		varchar
*      		text
*      		date
*      		datetime
*      		double
*      		float
*      		uint
*      		ulong
*      		time
*      		short
*      		enum
* length	This is used only when the type is varchar and denotes the length of the string.
*  			The max value is 255.
* enumvals  This is a list of valid values for an enum separated by "|".
*			It is used only if the type is ?enum?;
* required	This field dictates whether it is a required value.
*			The default value is ?FALSE?.
* isPrimary	This field identifies the primary key of the table.
*			If none of the fields have this flag set to ?TRUE?,
*			the first field definition is assume to be the primary key.
*			Default value for this field is ?FALSE?.
* default	This field sets the default value for the field definition.
*
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

/**
 * SQL Server (mssql) manager
 *
 * @deprecated Use SqlsrvManager instead
 */
abstract class MssqlManager extends DBManager
{
    /**
     * @see DBManager::$dbType
     */
    public $dbType = 'mssql';
    public $dbName = 'MsSQL';
    public $variant = 'mssql';
    public $label = 'LBL_MSSQL';

    protected $capabilities = array(
        "affected_rows" => true,
        "select_rows" => true,
        'fulltext' => true,
        "fix:expandDatabase" => true, // Support expandDatabase fix
        "create_user" => true,
        "create_db" => true,
        "recursive_query" => true,
    );

    /**
     * Maximum length of identifiers
     */
    protected $maxNameLengths = array(
        'table' => 128,
        'column' => 128,
        'index' => 128,
        'alias' => 128
    );

    protected $type_map = array(
            'int'      => 'int',
            'double'   => 'float',
            'float'    => 'float',
            'uint'     => 'int',
            'ulong'    => 'int',
            'long'     => 'bigint',
            'short'    => 'smallint',
            'varchar'  => 'varchar',
            'text'     => 'text',
            'longtext' => 'text',
            'date'     => 'datetime',
            'enum'     => 'varchar',
            'relate'   => 'varchar',
            'multienum'=> 'text',
            'html'     => 'text',
			'longhtml' => 'text',
    		'datetime' => 'datetime',
            'datetimecombo' => 'datetime',
            'time'     => 'datetime',
            'bool'     => 'bit',
            'tinyint'  => 'tinyint',
            'char'     => 'char',
            'blob'     => 'image',
            'longblob' => 'image',
            'currency' => 'decimal(26,6)',
            'decimal'  => 'decimal',
            'decimal2' => 'decimal',
            'id'       => 'varchar(36)',
            'url'      => 'varchar',
            'encrypt'  => 'varchar',
            'file'     => 'varchar',
	        'decimal_tpl' => 'decimal(%d, %d)',
            );

    protected $connectOptions = null;

	/**
     * @see DBManager::version()
     */
    public function version()
    {
        return $this->getOne("SELECT @@VERSION as version");
	}

    /**
     * This function take in the sql for a union query, the start and offset,
     * and wraps it around an "mssql friendly" limit query
     *
     * @param  string $sql
     * @param  int    $start record to start at
     * @param  int    $count number of records to retrieve
     * @return string SQL statement
     */
    private function handleUnionLimitQuery($sql, $start, $count)
    {
        //set the start to 0, no negs
        if ($start < 0)
            $start=0;

        $GLOBALS['log']->debug(print_r(func_get_args(),true));

        $this->lastsql = $sql;

        //make array of order by's.  substring approach was proving too inconsistent
        $orderByArray = preg_split('/order by/i', $sql);

        //count the number of array elements
        $unionOrderByCount = count($orderByArray);
        $arr_count = 0;

        if ($unionOrderByCount > 1) {
            //we really want the last order by, so reconstruct string
            //adding a 1 to count, as we dont wish to process the last element
            $unionsql = '';
            while ($unionOrderByCount>$arr_count+1) {
                $unionsql .= $orderByArray[$arr_count];
                $arr_count = $arr_count+1;
                //add an "order by" string back if we are coming into loop again
                //remember they were taken out when array was created
                if ($unionOrderByCount>$arr_count+1) {
                    $unionsql .= "order by";
                }
            }
            //grab the last order by element, set both order by's'
            $unionOrderBy = $orderByArray[$arr_count];
            $rowNumOrderBy = $unionOrderBy;

            //if last element contains a "select", then this is part of the union query,
            //and there is no order by to use
            if (stripos($unionOrderBy, "select")) {
                $unionsql = $sql;
                //with no guidance on what to use for required order by in rownumber function,
                //resort to using name column.
                $rowNumOrderBy = 'id';
                $unionOrderBy = "";
            }
        }
        else {
            //there are no order by elements, so just pass back string
            $unionsql = $sql;
            //with no guidance on what to use for required order by in rownumber function,
            //resort to using name column.
            $rowNumOrderBy = 'id';
            $unionOrderBy = '';
        }
        //Unions need the column name being sorted on to match across all queries in Union statement
        //so we do not want to strip the alias like in other queries.  Just add the "order by" string and
        //pass column name as is
        if ($unionOrderBy != '') {
            $unionOrderBy = ' order by ' . $unionOrderBy;
        }

        //Bug 56560, use top query in conjunction with rownumber() function
        //to create limit query when paging is needed. Otherwise,
        //it shows duplicates when paging on activities subpanel.
        //If not for paging, no need to use rownumber() function
        if ($count == 1)
        {
            $limitUnionSQL = "SELECT TOP $count * FROM (" .$unionsql .") as top_count ".$unionOrderBy;
        }
        else
        {
            $limitUnionSQL = "SELECT TOP $count * FROM( select ROW_NUMBER() OVER ( order by "
            .$rowNumOrderBy.") AS row_number, * FROM ("
            .$unionsql .") As numbered) "
            . "As top_count_limit WHERE row_number > $start "
            .$unionOrderBy;
        }

        return $limitUnionSQL;
    }

    /**
     * Checks the query for UNION.
     * If UNION(s) in main query and sub queries not exists then this's union query.
     * If UNION(s) in sub queries and not exists in main query then this's not union query.
     * If UNION(s) in sub queries and in main query then this's union query.
     *
     * @param string $sql
     * @return boolean
     */
    protected function isUnionQuery($sql)
    {
        // replace string literals with empty string, eg field1='Union''s Test' => field1='',
        // then any remaining word 'union' must be keyword
        $sql = preg_replace("/'[^']+'/", "''", str_replace("''", '', $sql));
        $unionPattern = "/(\)|\s)UNION(\(|\s)/i";
        if (preg_match($unionPattern, $sql)) {
            if (preg_match_all('/\(\s*(select[^)]+)\)/i', $sql, $matches)) {
                $isUnionInSub = false;
                $sqlMain = $sql;
                foreach ($matches[0] as $query) {
                    if (preg_match($unionPattern, $query)) {
                        $isUnionInSub = true;
                    }
                    $sqlMain = str_ireplace($query, '', $sqlMain);
                }
                return !$isUnionInSub || preg_match($unionPattern, $sqlMain);
            }
            return true;
        }
        return false;
    }

	/**
	 * FIXME: verify and thoroughly test this code, these regexps look fishy
     * @see DBManager::limitQuery()
     */
    public function limitQuery($sql, $start, $count, $dieOnError = false, $msg = '', $execute = true)
    {
        $start = (int)$start;
        $count = (int)$count;

        // remove comments from the query in order to simplify further parsing
        $sql = preg_replace('/\/\*(\*(?!\/)|[^*])*\*\//', ' ', $sql);

        $newSQL = $sql;
        $distinctSQLARRAY = array();
        if ($this->isUnionQuery($sql))
            $newSQL = $this->handleUnionLimitQuery($sql,$start,$count);
        else {
            if ($start < 0)
                $start = 0;
            $GLOBALS['log']->debug(print_r(func_get_args(),true));
            $this->lastsql = $sql;
            $matches = array();
            preg_match('/^(.*?SELECT\s+?)(.*?FROM.*WHERE)(.*?)$/si', $sql, $matches);
            if (!empty($matches[3])) {
                if ($start == 0) {
                    $match_two = strtolower($matches[2]);
                    if (!strpos($match_two, "distinct")> 0 && strpos($match_two, "distinct") !==0) {
                        $orderByMatch = array();
                        preg_match('/^(.*)(ORDER BY)(.*)$/is',$matches[3], $orderByMatch);
                        if (!empty($orderByMatch[3])) {
                            $selectPart = array();
                            preg_match('/^(.*)(\bFROM\s+.*)$/isU', $matches[2], $selectPart);
                            $newSQL = "SELECT TOP $count * FROM
                                (
                                    " . $matches[1] . $selectPart[1] . ", ROW_NUMBER()
                                    OVER (ORDER BY " . $this->returnOrderBy($sql, $orderByMatch[3]) . ") AS row_number
                                    " . $selectPart[2] . $orderByMatch[1]. "
                                ) AS a
                                WHERE row_number > $start";
                        }
                        else {
                            $newSQL = $matches[1] . " TOP $count " . $matches[2] . $matches[3];
                        }
                    }
                    else {
                        $distinct_o = strpos($match_two, "distinct");
                        $up_to_distinct_str = substr($match_two, 0, $distinct_o);
                        //check to see if the distinct is within a function, if so, then proceed as normal
                        if (strpos($up_to_distinct_str,"(")) {
                            //proceed as normal
                            $newSQL = $matches[1] . " TOP $count " . $matches[2] . $matches[3];
                        }
                        else {
                            //if distinct is not within a function, then parse
                            //string contains distinct clause, "TOP needs to come after Distinct"
                            //get position of distinct
                            $match_zero = strtolower($matches[0]);
                            $distinct_pos = strpos($match_zero , "distinct");
                            //get position of where
                            $where_pos = strpos($match_zero, "where");
                            //parse through string
                            $beg = substr($matches[0], 0, $distinct_pos+9 );
                            $mid = substr($matches[0], strlen($beg), ($where_pos+5) - (strlen($beg)));
                            $end = substr($matches[0], strlen($beg) + strlen($mid) );
                            //repopulate matches array
                            $matches[1] = $beg; $matches[2] = $mid; $matches[3] = $end;

                            $newSQL = $matches[1] . " TOP $count " . $matches[2] . $matches[3];
                        }
                    }
                } else {
                    $orderByMatch = array();
                    preg_match('/^(.*)(ORDER BY)(.*)$/is',$matches[3], $orderByMatch);

                    //if there is a distinct clause, parse sql string as we will have to insert the rownumber
                    //for paging, AFTER the distinct clause
                    $grpByStr = '';
                    $distinctPos = stripos($matches[2], 'distinct');
                    $fromPos = stripos($matches[2], 'from');

                    if ($distinctPos !== false && $fromPos !== false && $fromPos < $distinctPos) { // distinct is a part of sub-query!
                        $hasDistinct = false;
                    } else {
                        $hasDistinct = ($distinctPos === 0) ? true : $distinctPos;
                    }

                    if ($hasDistinct) {
                        $matches_sql = strtolower($matches[0]);
                        //remove reference to distinct and select keywords, as we will use a group by instead
                        //we need to use group by because we are introducing rownumber column which would make every row unique

                        //take out the select and distinct from string so we can reuse in group by
                        $dist_str = ' distinct ';
                        $distinct_pos = strpos($matches_sql, $dist_str);
                        $matches_sql = substr($matches_sql,$distinct_pos+ strlen($dist_str));
                        //get the position of where and from for further processing
                        $from_pos = strpos($matches_sql , " from ");
                        $where_pos = strpos($matches_sql, "where");
                        //split the sql into a string before and after the from clause
                        //we will use the columns being selected to construct the group by clause
                        if ($from_pos>0 ) {
                            $distinctSQLARRAY[0] = substr($matches_sql,0, $from_pos+1);
                            $distinctSQLARRAY[1] = substr($matches_sql,$from_pos+1);
                            //get position of order by (if it exists) so we can strip it from the string
                            $ob_pos = strpos($distinctSQLARRAY[1], "order by");
                            if ($ob_pos) {
                                $distinctSQLARRAY[1] = substr($distinctSQLARRAY[1],0,$ob_pos);
                            }

                            // strip off last closing parentheses from the where clause
                            $distinctSQLARRAY[1] = preg_replace('/\)\s$/',' ',$distinctSQLARRAY[1]);
                        }

                        //place group by string into array
                        $grpByArr = explode(',', $distinctSQLARRAY[0]);
                        $first = true;
                        //remove the aliases for each group by element, sql server doesnt like these in group by.
                        foreach ($grpByArr as $gb) {
                            $gb = trim($gb);

                            //clean out the extra stuff added if we are concatenating first_name and last_name together
                            //this way both fields are added in correctly to the group by
                            $gb = str_replace("isnull(","",$gb);
                            $gb = str_replace("'') + ' ' + ","",$gb);

                            //remove outer reference if they exist
                            if (strpos($gb,"'")!==false){
                                continue;
                            }
                            //if there is a space, then an alias exists, remove alias
                            if (strpos($gb,' ')){
                                $gb = substr( $gb, 0,strpos($gb,' '));
                            }

                            //if resulting string is not empty then add to new group by string
                            if (!empty($gb)) {
                                if ($first) {
                                    $grpByStr .= " $gb";
                                    $first = false;
                                } else {
                                    $grpByStr .= ", $gb";
                                }
                            }
                        }
                    }

                    if (!empty($orderByMatch[3])) {
                        //if there is a distinct clause, form query with rownumber after distinct
                        if ($hasDistinct) {
                            $newSQL = "SELECT TOP $count * FROM
                                        (
                                            SELECT ROW_NUMBER()
                                                OVER (ORDER BY ".$this->returnOrderBy($sql, $orderByMatch[3]).") AS row_number,
                                                count(*) counter, " . $distinctSQLARRAY[0] . "
                                                " . $distinctSQLARRAY[1] . "
                                                group by " . $grpByStr . "
                                        ) AS a
                                        WHERE row_number > $start";
                        }
                        else {
                        $newSQL = "SELECT TOP $count * FROM
                                    (
                                        " . $matches[1] . " ROW_NUMBER()
                                        OVER (ORDER BY " . $this->returnOrderBy($sql, $orderByMatch[3]) . ") AS row_number,
                                        " . $matches[2] . $orderByMatch[1]. "
                                    ) AS a
                                    WHERE row_number > $start";
                        }
                    }else{
                        //bug: 22231 Records in campaigns' subpanel may not come from
                        //table of $_REQUEST['module']. Get it directly from query
                        $upperQuery = strtoupper($matches[2]);
                        if (!strpos($upperQuery,"JOIN")){
                            $from_pos = strpos($upperQuery , "FROM") + 4;
                            $where_pos = strpos($upperQuery, "WHERE");
                            $tablename = trim(substr($upperQuery,$from_pos, $where_pos - $from_pos));
                        }else{
                            // FIXME: this looks really bad. Probably source for tons of bug
                            // needs to be removed
                            $moduleName = $this->request->getValidInputRequest('module', 'Assert\Mvc\ModuleName');
                            $tablename = $this->getTableNameFromModuleName($moduleName, $sql);
                            $tablename = $this->quote($tablename);
                        }
                        //if there is a distinct clause, form query with rownumber after distinct
                        if ($hasDistinct) {
                             $newSQL = "SELECT TOP $count * FROM
                                            (
                            SELECT ROW_NUMBER() OVER (ORDER BY ".$tablename.".id) AS row_number, count(*) counter, " . $distinctSQLARRAY[0] . "
                                                        " . $distinctSQLARRAY[1] . "
                                                    group by " . $grpByStr . "
                                            )
                                            AS a
                                            WHERE row_number > $start";
                        }
                        else {
                             $newSQL = "SELECT TOP $count * FROM
                                           (
                                  " . $matches[1] . " ROW_NUMBER() OVER (ORDER BY ".$tablename.".id) AS row_number, " . $matches[2] . $matches[3]. "
                                           )
                                           AS a
                                           WHERE row_number > $start";
                        }
                    }
                }
            }
        }

        $GLOBALS['log']->debug('Limit Query: ' . $newSQL);
        if($execute) {
            $result =  $this->query($newSQL, $dieOnError, $msg);
            $this->dump_slow_queries($newSQL);
            return $result;
        } else {
            return $newSQL;
        }
    }


    /**
     * Searches for begginning and ending characters.  It places contents into
     * an array and replaces contents in original string.  This is used to account for use of
     * nested functions while aliasing column names
     *
     * @param  string $p_sql     SQL statement
     * @param  string $strip_beg Beginning character
     * @param  string $strip_end Ending character
     * @param  string $patt      Optional, pattern to
     */
    private function removePatternFromSQL($p_sql, $strip_beg, $strip_end, $patt = 'patt')
    {
        //strip all single quotes out
        $count = substr_count ( $p_sql, $strip_beg);
        $increment = 1;
        if ($strip_beg != $strip_end)
            $increment = 2;

        $i=0;
        $offset = 0;
        $strip_array = array();
        while ($i<$count && $offset<strlen($p_sql)) {
            if ($offset > strlen($p_sql))
            {
				break;
            }

            $beg_sin = strpos($p_sql, $strip_beg, $offset);
            if (!$beg_sin)
            {
                break;
            }
            $sec_sin = strpos($p_sql, $strip_end, $beg_sin+1);
            $strip_array[$patt.$i] = substr($p_sql, $beg_sin, $sec_sin - $beg_sin +1);
            if ($increment > 1) {
                //we are in here because beginning and end patterns are not identical, so search for nesting
                $exists = strpos($strip_array[$patt.$i], $strip_beg );
                if ($exists>=0) {
                    $nested_pos = (strrpos($strip_array[$patt.$i], $strip_beg ));
                    $strip_array[$patt.$i] = substr($p_sql,$nested_pos+$beg_sin,$sec_sin - ($nested_pos+$beg_sin)+1);
                    $p_sql = substr($p_sql, 0, $nested_pos+$beg_sin) . " ##". $patt.$i."## " . substr($p_sql, $sec_sin+1);
                    $i = $i + 1;
                    continue;
                }
            }
            $p_sql = substr($p_sql, 0, $beg_sin) . " ##". $patt.$i."## " . substr($p_sql, $sec_sin+1);
            //move the marker up
            $offset = $sec_sin+1;

            $i = $i + 1;
        }
        $strip_array['sql_string'] = $p_sql;

        return $strip_array;
    }

    /**
     * adds a pattern
     *
     * @param  string $token
     * @param  array  $pattern_array
     * @return string
     */
	private function addPatternToSQL($token, array $pattern_array)
    {
        //strip all single quotes out
        $pattern_array = array_reverse($pattern_array);

        foreach ($pattern_array as $key => $replace) {
            $token = str_replace( "##".$key."##", $replace,$token);
        }

        return $token;
    }

    /**
     * gets an alias from the sql statement
     *
     * @param  string $sql
     * @param  string $alias
     * @return string
     */
	private function getAliasFromSQL($sql, $alias)
    {
        $matches = array();
        preg_match('/SELECT(.*?)FROM/isU', $sql, $matches);
        //parse all single and double  quotes out of array
        $sin_array = $this->removePatternFromSQL($matches[1], "'", "'", "sin_");
        $new_sql = array_pop($sin_array);
        $dub_array = $this->removePatternFromSQL($new_sql, "\"", "\"","dub_");
        $new_sql = array_pop($dub_array);

        //search for parenthesis
        $paren_array = $this->removePatternFromSQL($new_sql, "(", ")", "par_");
        $new_sql = array_pop($paren_array);

        //all functions should be removed now, so split the array on commas
        $mstr_sql_array = explode(",", $new_sql);
        foreach($mstr_sql_array as $token ) {
            if (strpos($token, $alias)) {
                //found token, add back comments
                $token = $this->addPatternToSQL($token, $paren_array);
                $token = $this->addPatternToSQL($token, $dub_array);
                $token = $this->addPatternToSQL($token, $sin_array);

                //log and break out of this function
                return $token;
            }
        }
        return null;
    }


    /**
     * Finds the alias of the order by column, and then return the preceding column name
     *
     * @param  string $sql
     * @param  string $orderMatch
     * @return string
     */
    private function findColumnByAlias($sql, $orderMatch)
    {
        //change case to lowercase
        $sql = strtolower($sql);
        $patt = '/\s+'.trim($orderMatch).'\s*(,|from)/';

        //check for the alias, it should contain comma, may contain space, \n, or \t
        $matches = array();
        preg_match($patt, $sql, $matches, PREG_OFFSET_CAPTURE);
        $found_in_sql = isset($matches[0][1]) ? $matches[0][1] : false;


        //set default for found variable
        $found = $found_in_sql;

        //if still no match found, then we need to parse through the string
        if (!$found_in_sql){
            //get count of how many times the match exists in string
            $found_count = substr_count($sql, $orderMatch);
            $i = 0;
            $first_ = 0;
            $len = strlen($orderMatch);
            //loop through string as many times as there is a match
            while ($found_count > $i) {
                //get the first match
                $found_in_sql = strpos($sql, $orderMatch,$first_);
                //make sure there was a match
                if($found_in_sql){
                    //grab the next 2 individual characters
                    $str_plusone = substr($sql,$found_in_sql + $len,1);
                    $str_plustwo = substr($sql,$found_in_sql + $len+1,1);
                    //if one of those characters is a comma, then we have our alias
                    if ($str_plusone === "," || $str_plustwo === ","){
                        //keep track of this position
                        $found = $found_in_sql;
                    }
                }
                //set the offset and increase the iteration counter
                $first_ = $found_in_sql+$len;
                $i = $i+1;
            }
        }
        //return $found, defaults have been set, so if no match was found it will be a negative number
        return $found;
    }


    /**
     * Return the order by string to use in case the column has been aliased
     *
     * @param  string $sql
     * @param  string $orig_order_match
     * @return string
     */
    private function returnOrderBy($sql, $orig_order_match)
    {
        $sql = strtolower($sql);
        $orig_order_match = trim($orig_order_match);
        if (strpos($orig_order_match, ',') !== false) {
            $parts = explode(',', $orig_order_match);
            foreach ($parts as &$part) {
                $part = $this->returnOrderBy($sql, $part);
            }
            return implode(',', $parts);
        }

        if (strpos($orig_order_match, ".") != 0)
            //this has a tablename defined, pass in the order match
            return $orig_order_match;

        // If there is no ordering direction (ASC/DESC), use ASC by default
        if (strpos($orig_order_match, " ") === false) {
        	$orig_order_match .= " ASC";
        }

        //grab first space in order by
        $firstSpace = strpos($orig_order_match, " ");

        //split order by into column name and ascending/descending
        $orderMatch = " " . strtolower(substr($orig_order_match, 0, $firstSpace));
        $asc_desc = substr($orig_order_match, $firstSpace + 1);

        //look for column name as an alias in sql string
        $found_in_sql = $this->findColumnByAlias($sql, $orderMatch);

        if (!$found_in_sql) {
            //check if this column needs the tablename prefixed to it
            $orderMatch = ".".trim($orderMatch);
            $colMatchPos = strpos($sql, $orderMatch);
            if ($colMatchPos !== false) {
                //grab sub string up to column name
                $containsColStr = substr($sql,0, $colMatchPos);
                //get position of first space, so we can grab table name
                $lastSpacePos = strrpos($containsColStr, " ");
                //use positions of column name, space before name, and length of column to find the correct column name
                $col_name = substr($sql, $lastSpacePos, $colMatchPos-$lastSpacePos+strlen($orderMatch));
				//bug 25485. When sorting by a custom field in Account List and then pressing NEXT >, system gives an error
				$containsCommaPos = strpos($col_name, ",");
				if($containsCommaPos !== false) {
					$col_name = substr($col_name, $containsCommaPos+1);
				}
                //add the "asc/desc" order back
                $col_name = $col_name. " ". $asc_desc;

                //return column name
                return $col_name;
            }
            //break out of here, log this
            $GLOBALS['log']->debug("No match was found for order by, pass string back untouched as: $orig_order_match");
            return $orig_order_match;
        }
        else {
            //if found, then parse and return
            //grab string up to the aliased column
            $GLOBALS['log']->debug("order by found, process sql string");

            $psql = (trim($this->getAliasFromSQL($sql, $orderMatch )));
            if (empty($psql))
                $psql = trim(substr($sql, 0, $found_in_sql));

            //grab the last comma before the alias
            $comma_pos = strrpos($psql, " ");
            //substring between the comma and the alias to find the joined_table alias and column name
            $col_name = substr($psql,0, $comma_pos);

            //make sure the string does not have an end parenthesis
            //and is not part of a function (i.e. "ISNULL(leads.last_name,'') as name"  )
            //this is especially true for unified search from home screen

            // However, we want to skip the parsing when the cast function is used
            // e.g. cast(contacts_cstm.account_type_c as varchar(8000))

            $alias_beg_pos = 0;
            if (strpos($psql, " as ") && substr($col_name, 0, 4) !== "cast") {
                $alias_beg_pos = strpos($psql, " as ");
            }

            if ($alias_beg_pos > 0) {
                $col_name = substr($psql,0, $alias_beg_pos );
            }
            //add the "asc/desc" order back
            $col_name = $col_name. " ". $asc_desc;

            //pass in new order by
            $GLOBALS['log']->debug("order by being returned is " . $col_name);
            return $col_name;
        }
    }

    /**
     * Take in a string of the module and retrieve the correspondent table name
     *
     * @param  string $module_str module name
     * @param  string $sql        SQL statement
     * @return string table name
     */
    private function getTableNameFromModuleName($module_str, $sql)
    {
        $GLOBALS['log']->debug("Module being processed is " . $module_str);
        //get the right module files
        //the module string exists in bean list, then process bean for correct table name
        //note that we exempt the reports module from this, as queries from reporting module should be parsed for
        //correct table name.
        $module_bean = BeanFactory::newBean($module_str);
        if (($module_str != 'Reports' && $module_str != 'SavedReport') && !empty($module_bean)){
            //get table name from bean
            $tbl_name = $module_bean->table_name;
            //make sure table name is not just a blank space, or empty
            $tbl_name = trim($tbl_name);

            if(empty($tbl_name)){
                $GLOBALS['log']->debug("Could not find table name for module $module_str. ");
                $tbl_name = $module_str;
            }
        }
        else {
            //since the module does NOT exist in beanlist, then we have to parse the string
            //and grab the table name from the passed in sql
            $GLOBALS['log']->debug("Could not find table name from module in request, retrieve from passed in sql");
            $tbl_name = $module_str;
            $sql = strtolower($sql);

            // Bug #45625 : Getting Multi-part identifier (reports.id) could not be bound error when navigating to next page in reprots in mssql
            // there is cases when sql string is multiline string and it we cannot find " from " string in it
            $sql = str_replace(array("\n", "\r"), " ", $sql);

            //look for the location of the "from" in sql string
            $fromLoc = strpos($sql," from " );
            if ($fromLoc>0){
                //found from, substring from the " FROM " string in sql to end
                $tableEnd = substr($sql, $fromLoc+6);
                //We know that tablename will be next parameter after from, so
                //grab the next space after table name.
                // MFH BUG #14009: Also check to see if there are any carriage returns before the next space so that we don't grab any arbitrary joins or other tables.
                $carriage_ret = strpos($tableEnd,"\n");
                $next_space = strpos($tableEnd," " );
                if ($carriage_ret < $next_space)
                    $next_space = $carriage_ret;
                if ($next_space > 0) {
                    $tbl_name= substr($tableEnd,0, $next_space);
                    if(empty($tbl_name)){
                        $GLOBALS['log']->debug("Could not find table name sql either, return $module_str. ");
                        $tbl_name = $module_str;
                    }
                }

                //grab the table, to see if it is aliased
                $aliasTableEnd = trim(substr($tableEnd, $next_space));
                $alias_space = strpos ($aliasTableEnd, " " );
                if ($alias_space > 0){
                    $alias_tbl_name= substr($aliasTableEnd,0, $alias_space);
                    strtolower($alias_tbl_name);
                    if(empty($alias_tbl_name)
                        || $alias_tbl_name == "where"
                        || $alias_tbl_name == "inner"
                        || $alias_tbl_name == "left"
                        || $alias_tbl_name == "join"
                        || $alias_tbl_name == "outer"
                        || $alias_tbl_name == "right") {
                        //not aliased, do nothing
                    }
                    elseif ($alias_tbl_name == "as") {
                            //the next word is the table name
                            $aliasTableEnd = trim(substr($aliasTableEnd, $alias_space));
                            $alias_space = strpos ($aliasTableEnd, " " );
                            if ($alias_space > 0) {
                                $alias_tbl_name= trim(substr($aliasTableEnd,0, $alias_space));
                                if (!empty($alias_tbl_name))
                                    $tbl_name = $alias_tbl_name;
                            }
                    }
                    else {
                        //this is table alias
                        $tbl_name = $alias_tbl_name;
                    }
                }
            }
        }
        //return table name
        $GLOBALS['log']->debug("Table name for module $module_str is: ".$tbl_name);
        return $tbl_name;
    }

    /**
     * @see DBManager::getAffectedRowCount()
     * 
     * Returns the number of rows affected by the last query
	 * See also affected_rows capability, will return 0 unless the DB supports it
     * @param resource $result query result resource
     * @return int
     */
    public function getAffectedRowCount($result)
    {
        return $this->getOne("SELECT @@ROWCOUNT");
    }

    /**
     * @see DBManager::quote()
     */
    public function quote($string)
    {
        if(is_array($string)) {
            return $this->arrayQuote($string);
        }
        return str_replace("'","''", $this->quoteInternal($string));
    }

    /**
     * @see DBManager::tableExists()
     */
    public function tableExists($tableName)
    {
        $GLOBALS['log']->info("tableExists: $tableName");

        if ($this->getDatabase() && !empty($this->connectOptions['db_name'])) {
            $query = 'SELECT TABLE_NAME
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_NAME = ?
    AND TABLE_TYPE = ?';

            $result = $this->getConnection()
                ->executeQuery($query, array(
                    $tableName,
                    'BASE TABLE',
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
            $r = $this->query('SELECT TABLE_NAME tn FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE=\'BASE TABLE\' AND TABLE_NAME LIKE '.$this->quoted($like));
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
     * @see DBManager::getTablesArray()
     */
    public function getTablesArray()
    {
        $GLOBALS['log']->debug('MSSQL fetching table list');

        if($this->getDatabase()) {
            $tables = array();
            $r = $this->query('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES');
            if (is_resource($r)) {
                while ($a = $this->fetchByAssoc($r))
                    $tables[] = $a['TABLE_NAME'];

                return $tables;
            }
        }

        return false; // no database available
    }


    /**
     * This call is meant to be used during install, when Full Text Search is enabled
     * Indexing would always occur after a fresh sql server install, so this code creates
     * a catalog and table with full text index.
     */
    public function full_text_indexing_setup()
    {
        $GLOBALS['log']->debug('MSSQL about to wakeup FTS');

        if($this->getDatabase()) {
                //create wakeup catalog
                $FTSqry[] = "if not exists(  select * from sys.fulltext_catalogs where name ='wakeup_catalog' )
                CREATE FULLTEXT CATALOG wakeup_catalog
                ";

                //drop wakeup table if it exists
                $FTSqry[] = "IF EXISTS(SELECT 'fts_wakeup' FROM sysobjects WHERE name = 'fts_wakeup' AND xtype='U')
                    DROP TABLE fts_wakeup
                ";
                //create wakeup table
                $FTSqry[] = "CREATE TABLE fts_wakeup(
                    id varchar(36) NOT NULL CONSTRAINT pk_fts_wakeup_id PRIMARY KEY CLUSTERED (id ASC ),
                    body text NULL,
                    kb_index int IDENTITY(1,1) NOT NULL CONSTRAINT wakeup_fts_unique_idx UNIQUE NONCLUSTERED
                )
                ";
                //create full text index
                 $FTSqry[] = "CREATE FULLTEXT INDEX ON fts_wakeup
                (
                    body
                    Language 0X0
                )
                KEY INDEX wakeup_fts_unique_idx ON wakeup_catalog
                WITH CHANGE_TRACKING AUTO
                ";

                //insert dummy data
                $FTSqry[] = "INSERT INTO fts_wakeup (id ,body)
                VALUES ('".create_guid()."', 'SugarCRM Rocks' )";


                //create queries to stop and restart indexing
                $FTSqry[] = 'ALTER FULLTEXT INDEX ON fts_wakeup STOP POPULATION';
                $FTSqry[] = 'ALTER FULLTEXT INDEX ON fts_wakeup DISABLE';
                $FTSqry[] = 'ALTER FULLTEXT INDEX ON fts_wakeup ENABLE';
                $FTSqry[] = 'ALTER FULLTEXT INDEX ON fts_wakeup SET CHANGE_TRACKING MANUAL';
                $FTSqry[] = 'ALTER FULLTEXT INDEX ON fts_wakeup START FULL POPULATION';
                $FTSqry[] = 'ALTER FULLTEXT INDEX ON fts_wakeup SET CHANGE_TRACKING AUTO';

                foreach($FTSqry as $q){
                    sleep(3);
                    $this->query($q);
                }
                $this->create_default_full_text_catalog();
        }

        return false; // no database available
    }

    protected $date_formats = array(
        '%Y-%m-%d' => 10,
        '%Y-%m' => 7,
        '%Y' => 4,
        '%v' => array(
            'format' => 'isoww',
            'function' => 'datepart',
        ),
    );

    /**
     * @see DBManager::convert()
     */
    public function convert($string, $type, array $additional_parameters = array())
    {
        // convert the parameters array into a comma delimited string
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
            case 'today':
                return "GETDATE()";
            case 'left':
                return "LEFT($string$additional_parameters_string)";
            case 'date_format':
                if(!empty($additional_parameters[0]) && $additional_parameters[0][0] == "'") {
                    $additional_parameters[0] = trim($additional_parameters[0], "'");
                }
                if(!empty($additional_parameters) && isset($this->date_formats[$additional_parameters[0]])) {
                    $parameters = $this->date_formats[$additional_parameters[0]];
                    if (is_array($parameters) && isset($parameters['format']) && isset($parameters['function'])) {
                        return "{$parameters['function']}({$parameters['format']}, $string)";
                    } else {
                        return "LEFT(CONVERT(varchar($parameters)," . $string . ",120),$parameters)";
                    }
                } else {
                   return "LEFT(CONVERT(varchar(10),". $string . ",120),10)";
                }
            case 'ifnull':
                if(empty($additional_parameters_string)) {
                    $additional_parameters_string = ",''";
                }
                return "ISNULL($string$additional_parameters_string)";
            case 'concat':
                return implode("+",$all_parameters);
            case 'text2char':
                return "CAST($string AS varchar(8000))";
            case 'quarter':
                return "DATENAME(quarter, $string)";
            case "length":
                return "LEN($string)";
            case 'month':
                return "MONTH($string)";
            case 'add_date':
                return "DATEADD({$additional_parameters[1]},{$additional_parameters[0]},$string)";
            case 'add_time':
                return "DATEADD(hh, {$additional_parameters[0]}, DATEADD(mi, {$additional_parameters[1]}, $string))";
            case 'add_tz_offset' :
                $getUserUTCOffset = $GLOBALS['timedate']->getUserUTCOffset();
                $operation = $getUserUTCOffset < 0 ? '-' : '+';
                return 'DATEADD(minute, ' . $operation . abs($getUserUTCOffset) . ', ' . $string. ')';
            case 'avg':
                return "avg($string)";
            case 'substr':
                return "substring($string, " . implode(', ', $additional_parameters) . ')';
            case 'round':
                return "round($string, " . implode(', ', $additional_parameters) . ')';
        }

        return "$string";
    }

    /**
     * @see DBManager::fromConvert()
     */
    public function fromConvert($string, $type)
    {
        switch($type) {
            case 'char': return rtrim($string, ' ');
            case 'datetimecombo':
            case 'datetime': return substr($string, 0,19);
            case 'date': return substr($string, 0, 10);
            case 'time': return substr($string, 11, 8);
		}
		return $string;
    }

    /**
     * @see DBManager::createTableSQLParams()
     */
	public function createTableSQLParams($tablename, $fieldDefs, $indices)
    {
        if (empty($tablename) || empty($fieldDefs))
            return '';

        $columns = $this->columnSQLRep($fieldDefs, false, $tablename);
        if (empty($columns))
            return '';

        return "CREATE TABLE $tablename ($columns)";
    }

    /**
     * Does this type represent text (i.e., non-varchar) value?
     * @param string $type
     */
    public function isTextType($type)
    {
        $type = strtolower($type);
        if(!isset($this->type_map[$type])) return false;
        return in_array($this->type_map[$type], array('ntext','text','image', 'nvarchar(max)'));
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
        return $this->getColumnType($type) === 'image';
    }

    /** {@inheritDoc} */
    public function emptyValue($type, $forPrepared = false)
    {
        $ctype = $this->getColumnType($type);

        if ($ctype == "datetime") {
            return $forPrepared
                ? "1970-01-01 00:00:00"
                : $this->convert($this->quoted("1970-01-01 00:00:00"), "datetime");
        }

        if ($ctype == "date") {
            return $forPrepared
                ? "1970-01-01"
                : $this->convert($this->quoted("1970-01-01"), "datetime");
        }

        if ($ctype == "time") {
            return $forPrepared
                ? "00:00:00"
                : $this->convert($this->quoted("00:00:00"), "time");
        }

        return parent::emptyValue($type, $forPrepared);
    }

    public function renameColumnSQL($tablename, $column, $newname)
    {
        return "SP_RENAME '$tablename.$column', '$newname', 'COLUMN'";
    }

    /**
     * Check identity of table.
     *
     * @param string $tableName Name of table.
     * @return array Array if identity found Or false if not found.
     */
    protected function checkIdentity($tableName)
    {
        $sql = "SELECT
                    c.name AS column_name,
                    CASE c.system_type_id
                        WHEN 127 THEN 'bigint'
                        WHEN 56 THEN 'int'
                        WHEN 52 THEN 'smallint'
                        WHEN 48 THEN 'tinyint'
                    END AS 'data_type',
                    IDENT_CURRENT(SCHEMA_NAME(t.schema_id)  + '.' + t.name) AS current_identity_value
                FROM sys.columns AS c
                INNER JOIN sys.tables AS t ON t.[object_id] = c.[object_id]
                WHERE c.is_identity = 1 AND t.name = '" . $tableName . "'\n";

        return $this->fetchOne($sql);
    }



    /**
     * @see DBManager::changeColumnSQL()
     *
     * MSSQL uses a different syntax than MySQL for table altering that is
     * not quite as simplistic to implement...
     */
    protected function changeColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired = false)
    {
        $sql = $sql2 = '';
        $constraints = $this->get_field_default_constraint_name($tablename);
        $columns = array();

        if(!$this->isFieldArray($fieldDefs)) {
            $fieldDefs = array($fieldDefs);
        }

        foreach ($fieldDefs as $def) {
            //if the column is being modified drop the default value
            //constraint if it exists.
            if (!empty($constraints[$def['name']])) {
                $sql .= ' ALTER TABLE ' . $tablename . ' DROP CONSTRAINT ' . $constraints[$def['name']] . "\n";
            }
            //check to see if we need to drop related indexes before the alter
            $indices = $this->get_indices($tablename);

            foreach ( $indices as $index ) {
                if ( in_array($def['name'],$index['fields']) ) {
                    $sql  .= ' ' . $this->add_drop_constraint($tablename,$index,true) . "\n";
                    $sql2 .= ' ' . $this->add_drop_constraint($tablename,$index,false) . "\n";
                }
            }

            switch($action) {
                case 'add':
                    if(!empty($def['auto_increment']) && false !== $this->checkIdentity($tablename)) {
                        // error we can't add identity to table where identity already exists.
                        // so remove auto_increment from this column.
                        LoggerManager::getLogger()->error("Can't add identity to table $tablename where identity already exists.");
                        unset($def['auto_increment']);
                    }
                    $columns[] = (count($columns) == 0 ? 'ADD ' : '')
                        . $this->oneColumnSQLRep($def, $ignoreRequired, $tablename, false);
                    break;

                case 'drop':
                    $columns[] = (count($columns) == 0 ? 'DROP COLUMN ' : 'COLUMN ') . $def['name'];
                    break;

                case 'modify':

                    $identity = $this->checkIdentity($tablename);

                    // if was identity then we need to drop this column, create a new column and copy data.
                    if(empty($def['auto_increment']) && false !== $identity && $identity['column_name'] == $def['name']) {
                        $tmpColumnName = $def['name'] . '_temp';
                        // mssql not provide batches via one statement, so we must use some hack with reuse db in one statement.
                        $modifyDef =  $this->oneColumnSQLRep($def, $ignoreRequired, $tablename, true);
                        $sqlVarIndex = mt_rand(0, PHP_INT_MAX);
                        $sql .="
                            DECLARE @useDbSql_$sqlVarIndex varchar(100);
                            DECLARE @sql_$sqlVarIndex nvarchar(4000);

                            SET @useDbSql_$sqlVarIndex = 'USE " . $this->connectOptions['db_name'] . "; ';\n";

                        // create a temporary column
                        $tmpColumnDef = array_merge($def, array(
                            'name' => $tmpColumnName,
                            'isnull' => true,
                        ));
                        unset($tmpColumnDef['default']);
                        $sql .="SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''ALTER TABLE $tablename ADD " . str_replace("'", "''''", $this->oneColumnSQLRep($tmpColumnDef, $ignoreRequired, $tablename, false)) . "''';
                            EXEC (@sql_$sqlVarIndex);\n";

                        // copy data to temporary column
                        $sql .="SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''UPDATE $tablename SET $tmpColumnName = " . $def['name'] . "''';
                            EXEC (@sql_$sqlVarIndex);\n";

                        // drop origin column
                        $sql .="SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''ALTER TABLE $tablename DROP COLUMN " . $def['name'] . "''';
                            EXEC (@sql_$sqlVarIndex);\n";

                        // create a new origin column
                        $sql .="SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''ALTER TABLE $tablename
                                        ADD " . str_replace("'", "''''", $this->oneColumnSQLRep(array_merge($def, array('isnull' => true)), $ignoreRequired, $tablename, false)) . "''';
                            EXEC (@sql_$sqlVarIndex);\n";

                        // copy data into origin column from temporary column
                        $sql .="SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''UPDATE $tablename SET " . $def['name'] . " = $tmpColumnName''';
                            EXEC (@sql_$sqlVarIndex);\n";

                        // change null flags on origin column after copy data
                        $sql .="SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''ALTER TABLE $tablename ALTER COLUMN " . $modifyDef['name'] . ' ' . $modifyDef['colType'] . ' ' .
                            $modifyDef['required'];

                        // drop temporary column
                        $sql .= "''';
                            EXEC (@sql_$sqlVarIndex);

                            SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''ALTER TABLE $tablename DROP COLUMN $tmpColumnName''';
                            EXEC (@sql_$sqlVarIndex);

                            -- this code cause a waning message, so not use sp_rename
                            -- EXEC SP_RENAME N'$tablename.$tmpColumnName', N'{$def['name']}', N'COLUMN'
                        ";
                        break;
                    }

                    // if we want to leave identity unchanged
                    if((empty($def['auto_increment']) && false === $identity) || (!empty($def['auto_increment']) && false !== $identity)) {
                        if(!empty($def['auto_increment']) && false !== $identity && $identity['column_name'] != $def['name']) {
                            // error we can't add identity to table where identity already exists.
                            // so remove auto_increment from this column.
                            LoggerManager::getLogger()->error("Can't add identity to table $tablename where identity already exists.");
                            unset($def['auto_increment']);
                        }
                        $modifyDef =  $this->oneColumnSQLRep($def, $ignoreRequired, $tablename, true);
                        $modifySql = 'ALTER COLUMN ' . $modifyDef['name'] . ' ' . $modifyDef['colType'] . ' ' .
                            $modifyDef['required'] . "\n";

                        // we can add default value only for non-identical columns.
                        if (empty($def['auto_increment']) && !empty($modifyDef['default'])) {
                            $modifySql .= ' ALTER TABLE ' . $tablename .  ' ADD  ' . $modifyDef['default'] . ' FOR ' . $modifyDef['name'] . "\n";
                        }
                        $columns[] = $modifySql;

                        break;
                    }
                    $sqlVarIndex = rand(0, PHP_INT_MAX);
                    $tempTableName = $tablename . '_' . $sqlVarIndex;
                    $sql .="
                            DECLARE @useDbSql_$sqlVarIndex varchar(100);
                            DECLARE @sql_$sqlVarIndex nvarchar(4000);

                            SET @useDbSql_$sqlVarIndex = 'USE " . $this->connectOptions['db_name'] . "; ';\n\n";

                    // copy data into temporary table
                    $sql .= "SELECT * INTO tempdb.dbo.$tempTableName FROM $tablename;\n\n";


                    // truncate table
                    $sql .= "SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''" . $this->truncateTableSQL($tablename) . "''';
                             EXEC(@sql_$sqlVarIndex);\n";

                    // drop column without identity
                    $sql .= "SET @sql_$sqlVarIndex = @useDbSql_$sqlVarIndex + 'EXEC sp_executesql N''ALTER TABLE $tablename DROP COLUMN " . $def['name'] . "''';
                            EXEC(@sql_$sqlVarIndex);\n";
                    // create column with identity
                    $sql .= "ALTER TABLE $tablename ADD " . $this->oneColumnSQLRep($def, $ignoreRequired, $tablename, false) . "\n";
                    // prepare fields for coping
                    $sql .= "DECLARE ColumnNamesCursor_$sqlVarIndex CURSOR FOR SELECT COLUMN_NAME AS ColumnName
                                FROM INFORMATION_SCHEMA.COLUMNS c
                                JOIN sys.columns sc ON  c.TABLE_NAME = OBJECT_NAME(sc.object_id) AND c.COLUMN_NAME = sc.Name
                                WHERE c.TABLE_NAME = '$tablename'

                            OPEN ColumnNamesCursor_$sqlVarIndex
                            DECLARE @ColumnNamesInline_$sqlVarIndex NVARCHAR(1000)
                            DECLARE @ColumnName_$sqlVarIndex NVARCHAR(1000)

                            SET @ColumnNamesInline_$sqlVarIndex = N'';

                            FETCH NEXT FROM ColumnNamesCursor_$sqlVarIndex INTO @ColumnName_$sqlVarIndex

                            WHILE @@FETCH_STATUS = 0
                            BEGIN
                                IF (@ColumnNamesInline_$sqlVarIndex <> N'')
                                BEGIN
                                    SET @ColumnNamesInline_$sqlVarIndex = N'' + @ColumnNamesInline_$sqlVarIndex + N',';
                                END
                                SET @ColumnNamesInline_$sqlVarIndex = N'' + @ColumnNamesInline_$sqlVarIndex + N'[' + @ColumnName_$sqlVarIndex + N']';
                                FETCH NEXT FROM ColumnNamesCursor_$sqlVarIndex INTO @ColumnName_$sqlVarIndex
                            END
                            CLOSE ColumnNamesCursor_$sqlVarIndex
                            DEALLOCATE ColumnNamesCursor_$sqlVarIndex\n";
                    // turn off check identity when insert
                    $sql .= "SET IDENTITY_INSERT $tablename ON\n";
                    // copy data from temporary table
                    $sql .= "DECLARE @sqlInsert_$sqlVarIndex NVARCHAR(max)
                             SET @sqlInsert_$sqlVarIndex = N'INSERT INTO $tablename (' + @ColumnNamesInline_$sqlVarIndex + N') SELECT ' + @ColumnNamesInline_$sqlVarIndex + N' FROM tempdb.dbo.$tempTableName'
                             EXEC sp_executesql @sqlInsert_$sqlVarIndex\n";
                    // turn on check identity when insert
                    $sql .= "SET IDENTITY_INSERT $tablename OFF\n";
                    // drop temporary table
                    $sql .= 'DROP TABLE tempdb.dbo.' . $tempTableName . "\n";
                    break;

                default:
                    // nothing to do.
                    break;
            }
        }

        if(count($columns)) {
            $sql .= " ALTER TABLE $tablename " . implode(", ", $columns) . " \n";
        }
        $sql .= $sql2;

        return $sql;
    }

    protected function setAutoIncrement($table, $field_name)
    {
		return "identity(1,1)";
	}

    /**
     * @see DBManager::setAutoIncrementStart()
     */
    public function setAutoIncrementStart($table, $field_name, $start_value)
    {
        if($start_value > 1)
            $start_value -= 1;
		$this->query("DBCC CHECKIDENT ('$table', RESEED, $start_value) WITH NO_INFOMSGS");
        return true;
    }

	/**
     * @see DBManager::getAutoIncrement()
     */
    public function getAutoIncrement($table, $field_name)
    {
		$result = $this->getOne("select IDENT_CURRENT('$table') + IDENT_INCR ( '$table' ) as 'Auto_increment'");
        return $result;
    }

    /** {@inheritDoc} */
    protected function get_index_data($table_name = null, $index_name = null)
    {
        $filterByTable = $table_name !== null;
        $filterByIndex = $index_name !== null;

        $columns = array();
        if (!$filterByTable) {
            $columns[] = 't.name AS table_name';
        }

        if (!$filterByIndex) {
            $columns[] = 'i.name AS index_name';
        }

        $columns[] = 'i.is_unique';
        $columns[] = 'i.is_primary_key';
        $columns[] = 'c.name AS column_name';

        $query = 'SELECT ' . implode(', ', $columns) . '
FROM sys.tables AS t
INNER JOIN sys.indexes AS i
    ON i.object_id = t.object_id
INNER JOIN sys.index_columns AS ic
    ON ic.object_id = t.object_id
        AND ic.index_id = i.index_id
INNER JOIN sys.columns c
    ON c.object_id = t.object_id
        AND c.column_id = ic.column_id';

        $where = $params = array();
        if ($filterByTable) {
            $where[] = 't.name = ?';
            $params[] = $table_name;
        }

        if ($filterByIndex) {
            $where[] = 'i.name = ?';
            $params[] = strtoupper($this->getValidDBName($index_name, true, 'index'));
        }

        if ($where) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        $order = array();
        if (!$filterByTable) {
            $order[] = 't.name';
        }

        if (!$filterByIndex) {
            $order[] = 'i.name';
        }

        $order[] = 'ic.key_ordinal';
        $query .= ' ORDER BY ' . implode(', ', $order);

        $stmt = $this
            ->getConnection()
            ->executeQuery($query, $params);

        $data = array();
        while (($row = $stmt->fetch())) {
            if (!$filterByTable) {
                $table_name = $row['table_name'];
            }

            if (!$filterByIndex) {
                $index_name = $row['index_name'];
            }

            if ($row['is_primary_key']) {
                $type = 'primary';
            } elseif ($row['is_unique']) {
                $type = 'unique';
            } else {
                $type = 'index';
            }

            $data[$table_name][$index_name]['name'] = $index_name;
            $data[$table_name][$index_name]['type'] = $type;
            $data[$table_name][$index_name]['fields'][] = $row['column_name'];
        }

        return $data;
    }

    /**
     * @see DBManager::get_columns()
     */
    public function get_columns($tablename)
    {
        // Sanity check for getting columns
        if (empty($tablename)) {
            $this->log->error(__METHOD__ . ' called with an empty tablename argument');
            return array();
        }        

        //find all unique indexes and primary keys.
        $result = $this->query("sp_columns $tablename");

        $columns = array();
        while (($row=$this->fetchByAssoc($result)) !=null) {
            $column_name = strtolower($row['COLUMN_NAME']);
            $columns[$column_name]['name']=$column_name;
            $columns[$column_name]['type']=strtolower($row['TYPE_NAME']);
            if ( $row['TYPE_NAME'] == 'decimal' ) {
                $columns[$column_name]['len']=strtolower($row['PRECISION']);
                $columns[$column_name]['len'].=','.strtolower($row['SCALE']);
            }
			elseif ( in_array($row['TYPE_NAME'],array('nchar','nvarchar')) )
				$columns[$column_name]['len']=strtolower($row['PRECISION']);
            elseif ( !in_array($row['TYPE_NAME'],array('datetime','text')) )
                $columns[$column_name]['len']=strtolower($row['LENGTH']);
            if ( stristr($row['TYPE_NAME'],'identity') ) {
                $columns[$column_name]['auto_increment'] = '1';
                $columns[$column_name]['type']=str_replace(' identity','',strtolower($row['TYPE_NAME']));
            }
            if (strtolower($row['TYPE_NAME']) == 'ntext') {
                $columns[$column_name]['type'] = 'nvarchar';
                $columns[$column_name]['len'] = 'max';
            }

            if (!empty($row['IS_NULLABLE']) && $row['IS_NULLABLE'] == 'NO' && (empty($row['KEY']) || !stristr($row['KEY'],'PRI')))
                $columns[strtolower($row['COLUMN_NAME'])]['required'] = 'true';

            $column_def = 1;
            if ( strtolower($tablename) == 'relationships' ) {
                $column_def = $this->getOne("select cdefault from syscolumns where id = object_id('relationships') and name = '$column_name'");
            }
            if ( $column_def != 0 && ($row['COLUMN_DEF'] != null)) {	// NOTE Not using !empty as an empty string may be a viable default value.
                $matches = array();
                $row['COLUMN_DEF'] = html_entity_decode($row['COLUMN_DEF'],ENT_QUOTES);
                if ( preg_match('/\([\(|\'](.*)[\)|\']\)/i',$row['COLUMN_DEF'],$matches) )
                    $columns[$column_name]['default'] = $matches[1];
                elseif ( preg_match('/\(N\'(.*)\'\)/i',$row['COLUMN_DEF'],$matches) )
                    $columns[$column_name]['default'] = $matches[1];
                else
                    $columns[$column_name]['default'] = $row['COLUMN_DEF'];
            }
        }
        return $columns;
    }


    /**
     * Get FTS catalog name for current DB
     */
    protected function ftsCatalogName()
    {
        if(isset($this->connectOptions['db_name'])) {
            return $this->connectOptions['db_name']."_fts_catalog";
        }
        return 'sugar_fts_catalog';
    }

    /**
     * @see DBManager::add_drop_constraint()
     */
    public function add_drop_constraint($table, $definition, $drop = false)
    {
        $type         = $definition['type'];
        $fields       = is_array($definition['fields'])?implode(',',$definition['fields']):$definition['fields'];
        $name         = $definition['name'];
        $sql          = '';

        switch ($type){
        // generic indices
        case 'index':
        case 'alternate_key':
            if ($drop)
                $sql = "DROP INDEX {$name} ON {$table}";
            else
                $sql = "CREATE INDEX {$name} ON {$table} ({$fields})";
            break;
        case 'clustered':
            if ($drop)
                $sql = "DROP INDEX {$name} ON {$table}";
            else
                $sql = "CREATE CLUSTERED INDEX $name ON $table ($fields)";
            break;
            // constraints as indices
        case 'unique':
            if ($drop)
                $sql = "ALTER TABLE {$table} DROP CONSTRAINT $name";
            else
                $sql = "ALTER TABLE {$table} ADD CONSTRAINT {$name} UNIQUE ({$fields})";
            break;
        case 'primary':
            if ($drop)
                $sql = "ALTER TABLE {$table} DROP CONSTRAINT {$name}";
            else
                $sql = "ALTER TABLE {$table} ADD CONSTRAINT {$name} PRIMARY KEY ({$fields})";
            break;
        case 'foreign':
            if ($drop)
                $sql = "ALTER TABLE {$table} DROP FOREIGN KEY ({$fields})";
            else
                $sql = "ALTER TABLE {$table} ADD CONSTRAINT {$name}  FOREIGN KEY ({$fields}) REFERENCES {$definition['foreignTable']}({$definition['foreignFields']})";
            break;
        case 'fulltext':
            if ($this->full_text_indexing_enabled() && $drop) {
                $sql = "DROP FULLTEXT INDEX ON {$table}";
            } elseif ($this->full_text_indexing_enabled()) {
                $catalog_name=$this->ftsCatalogName();
                if ( isset($definition['catalog_name']) && $definition['catalog_name'] != 'default')
                    $catalog_name = $definition['catalog_name'];

                $language = "Language 1033";
                if (isset($definition['language']) && !empty($definition['language']))
                    $language = "Language " . $definition['language'];

                $key_index = $definition['key_index'];

                $change_tracking = "auto";
                if (isset($definition['change_tracking']) && !empty($definition['change_tracking']))
                    $change_tracking = $definition['change_tracking'];

                $sql = " CREATE FULLTEXT INDEX ON $table ($fields $language) KEY INDEX $key_index ON $catalog_name WITH CHANGE_TRACKING $change_tracking" ;
            }
            break;
        }
        return $sql;
    }

    /**
     * Returns true if Full Text Search is installed
     *
     * @return bool
     */
    public function full_text_indexing_installed()
    {
        $ftsChckRes = $this->getOne("SELECT FULLTEXTSERVICEPROPERTY('IsFulltextInstalled') as fts");
        return !empty($ftsChckRes);
    }

    /**
     * @see DBManager::full_text_indexing_enabled()
     */
    protected function full_text_indexing_enabled($dbname = null)
    {
        // check to see if we already have install setting in session
    	if(!isset($_SESSION['IsFulltextInstalled']))
            $_SESSION['IsFulltextInstalled'] = $this->full_text_indexing_installed();

        // check to see if FTS Indexing service is installed
        if(empty($_SESSION['IsFulltextInstalled']))
            return false;

        // grab the dbname if it was not passed through
		if (empty($dbname)) {
			global $sugar_config;
			$dbname = $sugar_config['dbconfig']['db_name'];
		}
        //we already know that Indexing service is installed, now check
        //to see if it is enabled
		$res = $this->getOne("SELECT DATABASEPROPERTY('$dbname', 'IsFulltextEnabled') ftext");
        return !empty($res);
	}

    /**
     * Creates default full text catalog
     */
	protected function create_default_full_text_catalog()
    {
		if ($this->full_text_indexing_enabled()) {
		    $catalog = $this->ftsCatalogName();
            $GLOBALS['log']->debug("Creating the default catalog for full-text indexing, $catalog");

            //drop catalog if exists.
			$ret = $this->query("
                if not exists(
                    select *
                        from sys.fulltext_catalogs
                        where name ='$catalog'
                        )
                CREATE FULLTEXT CATALOG $catalog");

			if (empty($ret)) {
				$GLOBALS['log']->error("Error creating default full-text catalog, $catalog");
			}
		}
	}

    /**
     * Function returns name of the constraint automatically generated by sql-server.
     * We request this for default, primary key, required
     *
     * @param  string $table
     * @param  string $column
     * @return string
     */
	protected function get_field_default_constraint_name($table, $column = null)
    {
        $query = <<<EOQ
select s.name, o.name, c.name dtrt, d.name ctrt
    from sys.default_constraints as d
        join sys.objects as o
            on o.object_id = d.parent_object_id
        join sys.columns as c
            on c.object_id = o.object_id and c.column_id = d.parent_column_id
        join sys.schemas as s
            on s.schema_id = o.schema_id
    where o.name = '$table'
EOQ;
        if ( !empty($column) )
            $query .= " and c.name = '$column'";
        $res = $this->query($query);
        if ( !empty($column) ) {
            $row = $this->fetchByAssoc($res);
            if (!empty($row))
                return $row['ctrt'];
        }
        else {
            $returnResult = array();
            while ( $row = $this->fetchByAssoc($res) )
                $returnResult[$row['dtrt']] = $row['ctrt'];
            $results[$table] = $returnResult;
            return $returnResult;
        }

        return null;
	}

    /**
     * @see DBManager::massageFieldDef()
     */
    public function massageFieldDef(&$fieldDef, $tablename)
    {
        parent::massageFieldDef($fieldDef,$tablename);

        if ($fieldDef['type'] == 'int')
            $fieldDef['len'] = '4';

        if ($fieldDef['type'] == 'bit') {
            $fieldDef['len'] = '1';
        }

        if(empty($fieldDef['len']))
        {
            switch($fieldDef['type']) {
                case 'bool'     : $fieldDef['len'] = '1'; break;
                case 'smallint' : $fieldDef['len'] = '2'; break;
                case 'float'    : $fieldDef['len'] = '8'; break;
                case 'varchar'  :
                case 'nvarchar' :
                                  $fieldDef['len'] = $this->isTextType($fieldDef['dbType']) ? 'max' : '255';
                                  break;
                case 'image'    : $fieldDef['len'] = '2147483647'; break;
                case 'ntext'    : $fieldDef['len'] = '2147483646'; break;   // Note: this is from legacy code, don't know if this is correct
            }
        }
        if($fieldDef['type'] == 'decimal'
           && empty($fieldDef['precision'])
           && !strpos($fieldDef['len'], ','))
        {
             $fieldDef['len'] .= ',0'; // Adding 0 precision if it is not specified
        }

        if(empty($fieldDef['default'])
            && in_array($fieldDef['type'],array('bit','bool')))
        {
            $fieldDef['default'] = '0';
        }
		if (isset($fieldDef['required']) && $fieldDef['required'] && !isset($fieldDef['default']) )
			$fieldDef['default'] = '';
    }

    /**
     * @see DBManager::oneColumnSQLRep()
     */
    protected function oneColumnSQLRep($fieldDef, $ignoreRequired = false, $table = '', $return_as_array = false)
    {
        if (!empty($fieldDef['len'])) {
            // Variable-length can be a value from 1 through 8,000 or 4,000 for (n).
            // Max indicates that the maximum storage size is 2^31-1 bytes.
            // The storage size is the actual length of data entered + 2 bytes.
            // @link: http://msdn.microsoft.com/en-us/library/ff848814.aspx
            $colType = $this->getColumnType($this->getFieldType($fieldDef));
            if ($parts = $this->getTypeParts($colType)) {
                $colType = $parts['baseType'];
            }
            switch (strtolower($colType)) {
                case 'char':
                case 'binary':
                    if (8000 < $fieldDef['len']) {
                        $fieldDef['len'] = 8000;
                    }
                    break;
                case 'varchar':
                case 'varbinary':
                    if (8000 < $fieldDef['len']) {
                        $fieldDef['len'] = 'max';
                    }
                    break;
                case 'nchar':
                    if (4000 < $fieldDef['len']) {
                        $fieldDef['len'] = 4000;
                    }
                    break;
                case 'nvarchar':
                    if (4000 < $fieldDef['len']) {
                        $fieldDef['len'] = 'max';
                    }
                    break;
            }
        }

        //Bug 25814
		if(isset($fieldDef['name'])){
		    $colType = $this->getFieldType($fieldDef);
        	if(stristr($this->getFieldType($fieldDef), 'decimal') && isset($fieldDef['len'])){
				$fieldDef['len'] = min($fieldDef['len'],38);
			}
		    //bug: 39690 float(8) is interpreted as real and this generates a diff when doing repair
			if(stristr($colType, 'float') && isset($fieldDef['len']) && $fieldDef['len'] == 8){
				unset($fieldDef['len']);
			}
		}

		// always return as array for post-processing
		$ref = parent::oneColumnSQLRep($fieldDef, $ignoreRequired, $table, true);

		// Bug 24307 - Don't add precision for float fields.
		if ( stristr($ref['colType'],'float') )
			$ref['colType'] = preg_replace('/(,\d+)/','',$ref['colType']);

        if ( $return_as_array )
            return $ref;
        else
            return "{$ref['name']} {$ref['colType']} {$ref['default']} {$ref['required']} {$ref['auto_increment']}";
	}

    /**
     * Saves changes to module's audit table
     *
     * @param object $bean    Sugarbean instance
     * @param array  $changes changes
     */
    public function save_audit_records(SugarBean $bean, $changes)
	{
		//Bug 25078 fixed by Martin Hu: sqlserver haven't 'date' type, trim extra "00:00:00"
		if($changes['data_type'] == 'date'){
			$changes['before'] = str_replace(' 00:00:00','',$changes['before']);
		}
		parent::save_audit_records($bean,$changes);
	}

    /**
     * (non-PHPdoc)
     * @see DBManager::getDbInfo()
     */
    public function getDbInfo()
    {
        return array("version" => $this->version());
    }

    /**
     * (non-PHPdoc)
     * @see DBManager::validateQuery()
     */
    public function validateQuery($query)
    {
        if(!$this->isSelect($query)) {
            return false;
        }
        $this->query("SET SHOWPLAN_TEXT ON");
        $res = $this->getOne($query);
        $this->query("SET SHOWPLAN_TEXT OFF");
        return !empty($res);
    }

    /**
     * This is a utility function to prepend the "N" character in front of SQL values that are
     * surrounded by single quotes.
     *
     * @param  $sql string SQL statement
     * @return string SQL statement with single quote values prepended with "N" character for nvarchar columns
     */
    protected function _appendN($sql)
    {
        // If there are no single quotes, don't bother, will just assume there is no character data
        if (strpos($sql, "'") === false)
            return $sql;

        // Flag if there are odd number of single quotes, just continue without trying to append N
        if ((substr_count($sql, "'") & 1)) {
            $GLOBALS['log']->error("SQL statement[" . $sql . "] has odd number of single quotes.");
            return $sql;
        }

        //The only location of three subsequent ' will be at the beginning or end of a value.
        $sql = preg_replace('/(?<!\')(\'{3})(?!\')/', "'<@#@#@PAIR@#@#@>", $sql);

        // Remove any remaining '' and do not parse... replace later (hopefully we don't even have any)
        $pairs        = array();
        $regexp       = '/(\'{2})/';
        $pair_matches = array();
        preg_match_all($regexp, $sql, $pair_matches);
        if ($pair_matches) {
            foreach (array_unique($pair_matches[0]) as $key=>$value) {
                $pairs['<@PAIR-'.$key.'@>'] = $value;
            }
            if (!empty($pairs)) {
                $sql = str_replace($pairs, array_keys($pairs), $sql);
            }
        }

        $regexp  = "/(N?'.+?')/is";
        $matches = array();
        preg_match_all($regexp, $sql, $matches);
        $replace = array();
        if (!empty($matches)) {
            foreach ($matches[0] as $value) {
                // We are assuming that all nvarchar columns are no more than 200 characters in length
                // One problem we face is the image column type in reports which cannot accept nvarchar data
                if (!empty($value) && !is_numeric(trim(str_replace(array("'", ","), "", $value))) && !preg_match('/^\'[\,]\'$/', $value)) {
                    $replace[$value] = 'N' . trim($value, "N");
                }
            }
        }

        if (!empty($replace))
            $sql = str_replace(array_keys($replace), $replace, $sql);

        if (!empty($pairs))
            $sql = str_replace(array_keys($pairs), $pairs, $sql);

        if(strpos($sql, "<@#@#@PAIR@#@#@>"))
            $sql = str_replace(array('<@#@#@PAIR@#@#@>'), array("''"), $sql);

        return $sql;
    }

    /**
     * Quote SQL Server search term
     * @param string $term
     * @return string
     */
    protected function quoteTerm($term)
    {
        $term = str_replace("%", "*", $term); // Mssql wildcard is *
        return '"'.$term.'"';
    }

    /**
     * Generate fulltext query from set of terms
     * @param string $fields Field to search against
     * @param array $terms Search terms that may be or not be in the result
     * @param array $must_terms Search terms that have to be in the result
     * @param array $exclude_terms Search terms that have to be not in the result
     */
    public function getFulltextQuery($field, $terms, $must_terms = array(), $exclude_terms = array())
    {
        $condition = $or_condition = array();
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
            $condition[] = " NOT ".$this->quoteTerm($term);
        }
        $condition = $this->quoted(join(" AND ",$condition));
        return "CONTAINS($field, $condition)";
    }

    /**
     * Check if certain database exists
     * @param string $dbname
     */
    public function dbExists($dbname)
    {
        $db = $this->getOne("SELECT name FROM master..sysdatabases WHERE name = N".$this->quoted($dbname));
        return !empty($db);
    }

    /**
     * Check if certain DB user exists
     * @param string $username
     */
    public function userExists($username)
    {
        $this->selectDb("master");
        $user = $this->getOne("select count(*) from sys.sql_logins where name =".$this->quoted($username));
        // FIXME: go back to the original DB
        return !empty($user);
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
        $qpassword = $this->quote($password);
        $this->selectDb($database_name);
        $this->query("CREATE LOGIN $user WITH PASSWORD = '$qpassword'", true);
        $this->query("CREATE USER $user FOR LOGIN $user", true);
        $this->query("EXEC sp_addRoleMember 'db_ddladmin ', '$user'", true);
        $this->query("EXEC sp_addRoleMember 'db_datareader','$user'", true);
        $this->query("EXEC sp_addRoleMember 'db_datawriter','$user'", true);
    }

    /**
     * Create a database
     * @param string $dbname
     */
    public function createDatabase($dbname)
    {
        return $this->query("CREATE DATABASE $dbname", true);
    }

    /**
     * Drop a database
     * @param string $dbname
     */
    public function dropDatabase($dbname)
    {
        return $this->query("DROP DATABASE $dbname", true);
    }

    /**
     * Check if this DB name is valid
     *
     * @param string $name
     * @return bool
     */
    public function isDatabaseNameValid($name)
    {
        // No funny chars, does not begin with number
        return preg_match('/^[0-9#@]+|[\"\'\*\/\\?\:\\<\>\-\ \&\!\(\)\[\]\{\}\;\,\.\`\~\|\\\\]+/', $name)==0;
    }

    public function installConfig()
    {
        return array(
        	'LBL_DBCONFIG_MSG3' =>  array(
                "setup_db_database_name" => array("label" => 'LBL_DBCONF_DB_NAME', "required" => true),
            ),
            'LBL_DBCONFIG_MSG2' =>  array(
                "setup_db_host_name" => array("label" => 'LBL_DBCONF_HOST_NAME', "required" => true),
                "setup_db_host_instance" => array("label" => 'LBL_DBCONF_HOST_INSTANCE'),
            ),
            'LBL_DBCONF_TITLE_USER_INFO' => array(),
            'LBL_DBCONFIG_B_MSG1' => array(
                "setup_db_admin_user_name" => array("label" => 'LBL_DBCONF_DB_ADMIN_USER', "required" => true),
                "setup_db_admin_password" => array("label" => 'LBL_DBCONF_DB_ADMIN_PASSWORD', "type" => "password"),
            )
        );
    }

    /**
     * Returns a DB specific FROM clause which can be used to select against functions.
     * Note that depending on the database that this may also be an empty string.
     * @return string
     */
    public function getFromDummyTable()
    {
        return '';
    }

    /**
     * Returns a DB specific piece of SQL which will generate GUID (UUID)
     * This string can be used in dynamic SQL to do multiple inserts with a single query.
     * I.e. generate a unique Sugar id in a sub select of an insert statement.
     * @return string
     */

	public function getGuidSQL()
    {
      	return 'NEWID()';
    }

    /**
     * Truncate table
     *
     * @param  $name
     * @return string
     */
    public function truncateTableSQL($name)
    {
        return "TRUNCATE TABLE $name";
    }

    /**
     * {@inheritdoc}
     */
    public function sqlLikeString($str, $wildcard = '%', $appendWildcard = true)
    {
        $str = str_replace(array('['), array('[[]'), $str);
        return parent::sqlLikeString($str, $wildcard, $appendWildcard);
    }
}
