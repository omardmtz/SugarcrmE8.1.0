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
 * @codeCoverageIgnore
 */
class PMSEWrapper
{
    /**
     * Executes a Select query but needs a series of parameters, like the conditions and the order criteria.
     * @param string $orderBy
     * @param string $where
     * @param integer $rowOffset
     * @param integer $limit
     * @param integer $max
     * @param array $selectFields
     * @param array $joinTables
     * @return array
     */
    public function getSelectRows(
        $bean,
        $orderBy = "",
        $where = "",
        $rowOffset = 0,
        $limit = -1,
        $max = -1,
        $selectFields = array(),
        $joinTables = array()
    ) {
        $GLOBALS['log']->debug("get_list:  order_by = '$orderBy' and where = '$where' and limit = '$limit'");
//        $orderBy = $this->processOrderBy($orderBy);
        $queryArray = $this->assembleSelectQueryArray($bean, $orderBy, $where, $selectFields, array(), $joinTables,
            false);

        $recordSet = $this->processSelectQuery($bean, $queryArray, $rowOffset, $limit, $max);
        foreach ($recordSet['rowList'] as $key => $item) {
            $recordSet['rowList'][$key] = $this->decodeArray($item);
        }
        return $recordSet;
    }

    /**
     * Assemble the query array based in the parameters that are passed into the method.
     * @param string $orderBy
     * @param string $where
     * @param array $fieldList
     * @param array $params
     * @param array $joinTables
     * @param array $returnArray
     * @return string
     */
    public function assembleSelectQueryArray(
        $bean,
        $orderBy,
        $where = '',
        $fieldList = array(),
        $params = array(),
        $joinTables = array(),
        $returnArray = false
    ) {
        $queryArray = array();
        if (!empty($params['distinct'])) {
            $distinct = ' DISTINCT ';
        } else {
            $distinct = '';
        }
        $orderBy = $bean->process_order_by($orderBy);
        if (!empty($orderBy)) {
            //make call to process the order by clause
            $queryArray['orderBy'] = " ORDER BY " . $orderBy; //$this->processOrderBy($orderBy);
        }
        $queryArray['where'] = $where != '' ? " WHERE " . $where : '';
        $joinedTables = '';
        $lastTableName = '';
        if (!empty($joinTables)) {
            $queryArray['from'] = ' FROM ';
            $joinedTables = '';
            $lastJoinAlias = '';
            foreach ($joinTables as $join) {
                if ($lastTableName == '') {
                    $joinedTables = "(" . $bean->table_name . ' ' . strtoupper($join[0]) . " JOIN " . $join[1] . " ON " . $join[2];
                    $lastTableName = $bean->table_name;
                } else {
                    $joinedTables = "(" . $joinedTables . ' ' . strtoupper($join[0]) . " JOIN " . $join[1] . " ON " . $join[2];
                    $lastTableName = $join[1];
                }
                $joinedTables .= ") ";
            }
            $queryArray['from'] = " FROM " . $joinedTables;
        } else {
            $queryArray['from'] = " FROM $bean->table_name ";
        }
        if ($joinedTables == '') {
            $lastTableName = $bean->table_name . ".";
        } else {
            $lastTableName = '';
        }
        if (empty($fieldList)) {
            $queryArray['select'] = " SELECT $distinct $lastTableName* ";
        } else {
            $queryFieldList = array();
            foreach ($bean->field_defs as $fieldDefinition) {
                foreach ($fieldList as $field) {
                    //                    $fieldName = $field;
                    //                    if (strstr($field, '.')){
                    //                        $fieldArray = explode('.', $field);
                    //                        $fieldName  = $fieldArray[1];
                    //                    }
                    // TODO find a better approach to validate existence of fields
                    if (!in_array($field, $queryFieldList)) {
                        $queryFieldList[] = $field;
                    }
                }
            }
            if (empty($queryFieldList)) {
                $queryFieldList[] = $lastTableName . "." . $bean->getPrimaryFieldName();
            }
            $queryArray['select'] = " SELECT $distinct " . implode(',', $queryFieldList);
        }

        return $queryArray;
    }

    /**
     * This method assembles the query data array into a complete query in order to execute it and return the results.
     * @global array $sugar_config
     * @param string $queryArray
     * @param integer $rowOffset
     * @param integer $limit
     * @param integer $maxPerPage
     * @return array
     */
    public function processSelectQuery($bean, $queryArray = array(), $rowOffset = 0, $limit = -1, $maxPerPage = -1)
    {
        global $sugar_config;
        $db = DBManagerFactory::getInstance('listviews');
        if (!isset($queryArray['orderBy'])) {
            $queryArray['orderBy'] = '';
        }
        $query = $queryArray['select'] . $queryArray['from'] . $queryArray['where'] . $queryArray['orderBy'] . ' ';
        $GLOBALS['log']->debug("processQuery: query is " . $query);
        if ($limit == -1 && $rowOffset == 0) {
            $records = $bean->db->query($query, false);
            $totalRows = $bean->db->getRowCount($records);
        } else {
            $records = $bean->db->limitQuery($query, $rowOffset, $limit, false);
            $totalRows = $bean->getCountRows($queryArray);
        }

        $GLOBALS['log']->debug("processQuery: result is " . print_r($records, true));
        $isFirstTime = true;
        $list = array();
        while (($row = $bean->db->fetchByAssoc($records)) != null) {
            $row = $bean->convertRow($row);
            $list[] = $row;
        }

        $response = array();
        $response['rowList'] = $list;
        $response['totalRows'] = $totalRows;
        $response['currentOffset'] = $rowOffset;
        return $response;
    }

    public function getKeyFields($pattern, $array)
    {
        $keys = array_keys($array);
        return preg_grep($pattern, $keys);
    }

    /**
     * Method to remove bound fields
     * @param string $row
     * @return string
     */
    public function sanitizeKeyFields($row)
    {
        $keyFields = $this->getKeyFields('/_id$/', $row);
        foreach ($keyFields as $key) {
            unset($row[$key]);
        }
        return $row;
    }

    /**
     * Method to remove fields
     * @param string $row
     * @return string
     */
    public function sanitizeFields($row)
    {
        $row = $this->sanitizeKeyFields($row);
        $row = $this->sanitizeBoundFields($row);
        return $row;
    }

    /**
     * Method to remove bound fields
     * @param type $row
     * @return type
     */
    public function sanitizeBoundFields($row)
    {
        $fields = array('bou_element', 'bou_element_type', 'bou_rel_position', 'bou_size_identical', 'bou_uid');
        foreach ($fields as $key) {
            unset($row[$key]);
        }
        return $row;
    }

    /**
     * Executes and HTML-EntityDecode of each attribute of the class, and return those values as an array.
     * @param array $array
     * @return array
     */
    public function decodeArray($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = html_entity_decode($value, ENT_QUOTES);
        }
        return $array;
    }

    /**
     * Create a new record for the current bean, based on the array of fields.
     * @param array $fieldArray
     * @return string
     */
    public function create($bean, $fieldArray)
    {

        foreach ($fieldArray as $key => $field) {
            $bean->$key = $fieldArray[$key];
        }
        return $bean->save();
    }

    /**
     * Method that returns the first character of a case
     * @param type $string
     * @return type
     */
    public function lowerFirstCharCase($string)
    {
        return substr_replace($string, strtolower(substr($string, 0, 1)), 0, 1);
    }

    /**
     * Update the loaded record based in the Fields array that is passed as parameter.
     * @param array $fieldsArray
     * @return boolean
     */
    public function update($bean, $fieldsArray)
    {
        $primaryKeysFields = array();
        $primaryKeysArray = $this->getPrimaryFieldName($bean);
        $primaryKeysArray = is_array($primaryKeysArray) ? $primaryKeysArray : array($primaryKeysArray);
        $beanIsLoaded = true;
        $primaryKeysPresent = true;

        foreach ($primaryKeysArray as $primaryKey) {

            if (!isset($bean->fetched_row[$primaryKey]) && !isset($bean->$primaryKey)) {
                $beanIsLoaded = false;
            }

            if (!isset($fieldsArray[$primaryKey])) {
                $primaryKeysPresent = false;
            } else {
                $primaryKeysFields[$primaryKey] = $fieldsArray[$primaryKey];
            }
        }

        if ($beanIsLoaded && !$primaryKeysPresent) {
            foreach ($fieldsArray as $key => $value) {
                $bean->$key = $value;
            }
        } elseif (!$beanIsLoaded && $primaryKeysPresent) {
            $bean->load($primaryKeysFields);
            foreach ($fieldsArray as $key => $value) {
                if (!in_array($key, $primaryKeysArray)) {
                    $bean->$key = $value;
                }
            }
        } elseif ($beanIsLoaded && $primaryKeysPresent) {
            foreach ($fieldsArray as $key => $value) {
                if (!in_array($key, $primaryKeysArray)) {
                    $bean->$key = $value;
                }
            }
        } else {
            return false;
        }
        if (isset($bean->act_name) || isset($bean->evn_name) || isset($bean->gat_name) || isset($bean->art_name)) {
            $bean->name = $bean->act_name . $bean->evn_name . $bean->gat_name . $bean->art_name;
        }
        return $bean->save();
    }

    /**
     * Get the name of the primary key field or fields.
     * @return array
     */
    public function getPrimaryFieldName($bean)
    {
        $definition = $this->getPrimaryFieldDefinition($bean);
        if ($definition['single']) {
            return $definition['name'];
        } else {
            $definitionArray = array();
            foreach ($definition as $singleDefinition) {
                if (isset($singleDefinition['name'])) {
                    $definitionArray[] = $singleDefinition['name'];
                }
            }
            return $definitionArray;
        }
    }

    /**
     * Get the Primary UID Field that field generally is succeded by the _uid string.
     * @return array
     */
    public function getPrimaryFieldUID($bean)
    {
        $idField = $this->getPrimaryFieldName($bean);
        $newField = str_replace("_id", "_uid", $idField);
        $newFieldDef = $bean->getFieldDefinition($newField);
        $newField = ($newFieldDef != false) ? $newField : '';
        return $newField;
    }

    /**
     * Get the definition of the primary key field or fields.
     * @return boolean
     */
    public function getPrimaryFieldDefinition($bean)
    {
        $defaultIndex = 'id';
        $definition = '';
        $indices = $bean->getIndices();
        foreach ($indices as $index) {
            if ($index['type'] == 'primary') {
                if (count($index['fields']) == 1) {
                    $definition = $bean->getFieldDefinition($index['fields'][0]);
                    $definition['single'] = true;
                } else {
                    if (count($index['fields']) > 1) {
                        $definition = array();
                        foreach ($index['fields'] as $field) {
                            $definition[] = $bean->getFieldDefinition($field);
                        }
                        $definition['single'] = false;
                    } else {
                        $definition = $bean->getFieldDefinition($defaultIndex);
                        $definition['single'] = true;
                    }
                }
            }
        }

        if (empty($definition)) {
            $definition = $bean->getFieldDefinition(0);
            $definition['single'] = true;
        }
        if (empty($definition)) {
            $defs = $bean->field_defs;
            reset($defs);
            $definition = current($defs);
            $definition['single'] = true;
        }
        return $definition;
    }

    /**
     * Delete the current record loaded into the bean.
     * @return boolean
     */
    public function delete($bean)
    {
        // Gets the query to run, if there is one
        $query = $this->getDeleteQuery($bean);

        // If there is a query, run it and return the result
        return $query && $bean->db->query($query, true, "Error marking record deleted: ");
    }

    /**
     * Gets a delete query to run on a process bean
     * @param SugarBean $bean
     * @return string
     */
    protected function getDeleteQuery(\SugarBean $bean)
    {
        $return = '';

        // Used to build the query as well
        $keys = $this->getPrimaryKeysArray($bean);

        // Used to build the query
        $condition = $this->buildConditionFromKeys($bean, $keys);

        // If we actually have a condition, use it
        if ($condition != '') {
            $return = "DELETE FROM $bean->table_name where $condition";
        }

        return $return;
    }

    /**
     * Builds a condition for a query from the primary keys of a bean
     * @param SugarBean $bean
     * @param array $keys The primary keys array
     * @return string
     */
    protected function buildConditionFromKeys(\SugarBean $bean, array $keys)
    {
        // Default the return
        $condition = '';

        // Loop the keys and create  query
        foreach ($keys as $key) {
            // Primary keys shouldn't be empty, so this is a safe check
            if (!empty($bean->$key)) {
                // If the condition already has something added to it, glue it
                // with an AND
                if (!empty($condition)) {
                    $condition .= " AND";
                }

                // Append the actual condition now
                $condition .= " $key = " . $bean->db->quoted($bean->$key);
            }
        }

        return $condition;
    }

    /**
     * Gets the primary keys array in a processable format
     * @param SugarBean $bean
     * @return array
     */
    protected function getPrimaryKeysArray(\SugarBean $bean)
    {
        // needed to obtain the current id defined in the field definition
        $keys = $this->getPrimaryFieldName($bean);

        // Dress up the response
        return is_array($keys) ? $keys : array($keys);
    }
}
