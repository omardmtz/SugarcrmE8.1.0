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
* $Id: Link.php
* Description:  Represents a relationship from a single bean's perspective.
* Does not actively do work but is used by sugarbean to manipulate relationships.
* Work is deferred to the relationship classes.
 *
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

/**
 * Represents a relationship from a single beans perspective.
 * @api
 */
class Link2 {

    /**
     * @var SugarRelationship relationship
     */
    protected $relationship; //relationship object this link is tied to.
    protected $focus;  //SugarBean this link uses as the context for its calls.
    protected $def;  //Field def for this link
    protected $name;  //Field name for this link
    protected $beans;  //beans on the other side of the link
    protected $rows;   //any additional fields on the relationship
    protected $loaded; //true if this link has been loaded from the database
    protected $relationship_fields = array();

    /**
     * @param  $linkName String name of a link field in the module's vardefs
     * @param  $bean SugarBean focus bean for this link (one half of a relationship)
     * @param  $linkDef Array Optional vardef for the link in case it can't be found in the passed in bean for the global dictionary
     * @return void
     *
     */
    function __construct($linkName, $bean, $linkDef = false){
        $this->focus = $bean;
        //Try to load the link vardef from the beans field defs. Otherwise start searching
        if (empty($bean->field_defs) || empty($bean->field_defs[$linkName]) || empty($bean->field_defs[$linkName]['relationship']))
        {
            if (empty($linkDef))
            {
                //Assume $linkName is really relationship_name, and find the link name with the vardef manager
                $this->def = VardefManager::getLinkFieldForRelationship($bean->module_dir, $bean->object_name, $linkName);
            }
            else {
                $this->def = $linkDef;
            }
            //Check if multiple links were found for a given relationship
            if (is_array($this->def) && !isset($this->def['name']))
            {
                //More than one link found, we need to figure out if we are currently on the LHS or RHS
                //default to lhs for now
                if (isset($this->def[0]['side']) && $this->def[0]['side'] == 'left')
                {
                    $this->def = $this->def[0];
                }
                else if (isset($this->def[1]['side']) && $this->def[1]['side'] == 'left')
                {
                    $this->def = $this->def[1];
                }
                else {
                    $this->def = $this->def[0];
                }
            }
            if (empty($this->def['name']))
            {
                $GLOBALS['log']->fatal("failed to find link for $linkName");
                return;
            }

            $this->name = $this->def['name'];
        }
        else {
            //Linkdef was found in the bean (this is the normal expectation)
            $this->def = $bean->field_defs[$linkName];
            $this->name = $linkName;
        }
        //Instantiate the relationship for this link.
        $this->relationship = SugarRelationshipFactory::getInstance()->getRelationship($this->def['relationship']);

        // Fix to restore functionality from Link.php that needs to be rewritten but for now this will do.
        $this->relationship_fields = (!empty($this->def['rel_fields']))?$this->def['rel_fields']: array();

        if (!$this->loadedSuccesfully())
        {
            global $app_strings;
            $GLOBALS['log']->error(string_format($app_strings['ERR_DATABSE_RELATIONSHIP_QUERY'], array($this->name, $this->def['relationship'])));
        }
        //Following behavior is tied to a property(ignore_role) value in the vardef. It alters the values of 2 properties, ignore_role_filter and add_distinct.
        //the property values can be altered again before any requests are made.
        if (!empty($this->def) && is_array($this->def)) {
            if ( isset($this->def['ignore_role']) ) {
                if ($this->def['ignore_role']) {
                    $this->ignore_role_filter=true;
                    $this->add_distinct=true;
                }
            }
            if (!empty($this->def['primary_only'])) {
                $this->relationship->primaryOnly = true;
            }
        }
    }

    /**
     * Returns false if no relationship was found for this link
     * @return bool
     */
    public function loadedSuccesfully()
    {
        return !empty($this->relationship);
    }

    /**
     *  Forces the link to load the relationship rows.
     * Will be called internally when the $rows property is accessed or get() is called
     * @return void
     */
    public function load($params = array())
    {
        $data = $this->query($params);
        $this->rows = $data['rows'];
        $this->beans = null;
        $this->loaded = true;
    }

    /**
     * Resets the loaded flag on this link so that it must reload the next time it is used
     */
    public function resetLoaded() {
        $this->loaded = false;
    }

    /**
     *  Perform a query on this relationship.
     *
     * @param array $params An array that can contain the following parameters:<br/>
     * <ul><li><b>where:</b> An array with 3 key/value pairs.
     *  lhs_field: The name of the field to check search.
     *  operator: The operator to use in the search.
     *  rhs_value: The value to search for.<br/>
     *  Example:<pre>
     *  'where' => array(
             'lhs_field' => 'source',
             'operator' => '=',
             'rhs_value' => 'external'
         )</pre>
     *  </li>
     * <li><b>limit:</b> The maximum number of rows</li>
     * <li><b>offset:</b> Offset to pass to the database query when loading.</li>
     * <li><b>order_by:</b> field to order the result set by</li>
     * <li><b>deleted:</b> If deleted is set to 1, only deleted records related to the current record will be returned.</li></ul>
     * @return string|array query used to load this relationship
     */
    public function query($params = array())
    {
        return $this->relationship->load($this, $params);
    }

    /**
     * @return array ids of records related through this link
     */
    public function get($role = false) {
        if (!$this->loaded)
            $this->load();

        return array_keys($this->rows);
    }

    /**
     * @deprecated
     *
     * @return string name of table for the relationship of this link
     */
    public function getRelatedTableName() {
        return BeanFactory::getDefinition(
            $this->getRelatedModuleName()
        )->getTableName();
    }

    /**
     * @return string the name of the module on the other side of this link
     */
    public function getRelatedModuleName() {
        if (!$this->relationship) return false;

        if ($this->getSide() == REL_LHS) {
            return $this->relationship->getRHSModule();
        } else {
            return $this->relationship->getLHSModule();
        }
    }

    /**
     * @return string the name of the link field used on the other side of the rel
     */
    public function getRelatedModuleLinkName() {
        if (!$this->relationship) return false;

        if ($this->getSide() == REL_LHS) {
            return $this->relationship->getRHSLink();
        } else {
            return $this->relationship->getLHSLink();
        }
    }

    /**
     *
     * @return string "many" if multiple records can be related through this link
     * or "one" if at most, one record can be related.
     */
    public function getType()
    {
        if (isset($this->def['link_type'])) {
            return $this->def['link_type'];
        }

        return $this->relationship->getType($this->getSide());
    }

    /**
     * @return SugarBean The parent Bean this link references
     */
    public function getFocus()
    {
        return $this->focus;
    }

    /**
     * @deprecated
     * @return list of fields that exist only on the relationship
     */
    public function getRelatedFields(){
        return $this->relationship_fields;
    }

    /**
     * @param $name
     * @return The value for the relationship field $name
     */
    public function getRelatedField($name){
        if (!empty($this->relationship_fields) && !empty($this->relationship_fields[$name]))
            return $this->relationship_fields[$name];
        else
            return null; //For now return null. Later try the relationship object directly.
    }

    /**
     * @return SugarRelationship the relationship object this link references
     */
    public function getRelationshipObject() {
       return $this->relationship;
    }

    /**
     * @return string "LHS" or "RHS" depending on the side of the relationship this link represents
     */
    public function getSide() {
        $moduleName = ($this->focus->module_name) == 'Employees' ? 'Users' : $this->focus->module_name;
        //First try the relationship
        if ($this->relationship->getLHSLink() == $this->name &&
            ($this->relationship->getLHSModule() == $moduleName)
        ){
            return REL_LHS;
        }

        if ($this->relationship->getRHSLink() == $this->name &&
            ($this->relationship->getRHSModule() == $moduleName)
        ){
            return REL_RHS;
        }
        //Next try the vardef
        if (!empty($this->def['side']))
        {
            if ((strtolower($this->def['side']) == 'left' || $this->def['side'] == REL_LHS)
                //Some relationships make have left in the vardef erroneously if generated by module builder
                && (empty($this->relationship->def['join_key_lhs']) || $this->name != $this->relationship->def['join_key_lhs']))
            {
                return REL_LHS ;
            }
            else {
                return REL_RHS;
            }
        }
        //Next try using the id_name and relationship join keys
        else if (!empty($this->def['id_name']))
        {
            if (isset($this->relationship->def['join_key_lhs']) && $this->def['id_name'] == $this->relationship->def['join_key_lhs'])
                return REL_RHS;
            else if (isset($this->relationship->def['join_key_rhs']) && $this->def['id_name'] == $this->relationship->def['join_key_rhs'])
                return REL_LHS;
        }

        // Try to guess it by module name
        if (($this->relationship->getLHSLink() != $this->name)
            && ($this->relationship->getRHSLink() != $this->name)
            && ($this->relationship->getLHSModule() != $this->relationship->getRHSModule())
        ) {
            if ($this->relationship->getLHSModule() == $moduleName) {
                return REL_LHS;
            } elseif ($this->relationship->getRHSModule() == $moduleName) {
                return REL_RHS;
            }
        }

        $GLOBALS['log']->error("Unable to get proper side for link {$this->name}");

        // make sure there is a return
        return REL_TYPE_UNDEFINED;
    }


    /**
     * @return String name of link field that maps to this relationship on the other side
     */
    public function getLinkForOtherSide()
    {
        if ($this->getSide() == REL_LHS) {
            return $this->relationship->getRHSLink();
        } else {
            return $this->relationship->getLHSLink();
        }
    }

    /**
     * @return bool true if LHSModule == RHSModule
     */
    protected function is_self_relationship() {
        return $this->relationship->isSelfReferencing();
    }

    /**
     * @return bool true if this link represents a relationship where the parent could be one of multiple modules. (ex. Activities parent)
     */
    public function isParentRelationship(){
        return $this->relationship->isParentRelationship();
    }

    /**
     * @param $params array of parameters. Possible parameters include:
     * 'join_table_link_alias': alias the relationship join table in the query (for M2M relationships),
     * 'join_table_alias': alias for the final table to be joined against (usually a module main table)
     * @param bool $return_array if true the query is returned as a array broken up into
     * select, join, where, type, rel_key, and joined_tables
     * @return string/array join query for this link
     */
    function getJoin($params, $return_array =false)
    {
        return $this->relationship->getJoin($this, $params, $return_array);
    }

    /**
     * Build a Relationship Join with a SugarQuery Object
     * @param SugarQuery $sugar_query
     * @return SugarQuery
     */
    function buildJoinSugarQuery($sugar_query, $options = array())
    {
        return $this->relationship->buildJoinSugarQuery($this, $sugar_query, $options);
    }

    /**
     * @param array $params optional parameters. Possible Values;
     * 'return_as_array': returns the query broken into
     * @return String/Array query to grab just ids for this relationship
     *
     * @deprecated Use Link2::query() instead
     */
    function getQuery($params = array())
    {
        return $this->relationship->getQuery($this, $params);
    }

    /**
     * This function is similair getJoin except for M2m relationships it won't join agaist the final table.
     * Its used to retrieve the ID of the related beans only
     * @param $params array of parameters. Possible parameters include:
     * 'return_as_array': returns the query broken into
     * @param bool $return_array same as passing 'return_as_array' into parameters
     * @return string/array query to use when joining for subpanels
     */
    public function getSubpanelQuery($params = array(), $return_array = false)
    {
        if (!empty($this->def['ignore_role']))
            $params['ignore_role'] = true;
        return $this->relationship->getSubpanelQuery($this, $params, $return_array);
    }

    /**
     * Use with caution as if you have a large list of beans in the relationship,
     * it can cause the app to timeout or run out of memory.
     *
     * @param array $params An array that can contain the following parameters:<br/>
     * <ul><li><b>where:</b> An array with 3 key/value pairs.
     *  lhs_field: The name of the field to check search.
     *  operator: The operator to use in the search.
     *  rhs_value: The value to search for.<br/>
     *  Example:<pre>
     *  'where' => array(
             'lhs_field' => 'source',
             'operator' => '=',
             'rhs_value' => 'external'
         )</pre>
     *  </li>
     * <li><b>limit:</b> The maximum number of beans to load.</li>
     * <li><b>offset:</b> Offset to pass to the database query when loading.</li>
     * <li><b>order_by:</b> field to order the result set by</li>
     * <li><b>deleted:</b> If deleted is set to 1, only deleted records related to the current record will be returned.</li></ul>
     * @param array $retrieveParams Array of options to send to retrieveBean
     * @return array of SugarBeans related through this link.
     */
    public function getBeans($params = array(), $retrieveParams = array())
    {
        //Some depricated code attempts to pass in the old format to getBeans with a large number of useless paramters.
        //reset the parameters if they are not in the new array format.
    	if (!is_array($params))
            $params = array();

        if (!$this->loaded && empty($params)) {
            $this->load();
        }

        $rows = $this->rows;
        //If params is set, we are doing a query rather than a complete load of the relationship
        if (!empty($params)) {
            $data = $this->query($params);
            $rows = $data['rows'];
        }

        $result = array();
        if(!$this->beansAreLoaded() || !empty($params))
        {
            $rel_module = $this->getRelatedModuleName();

            //If there are any relationship fields, we need to figure out the mapping from the relationship fields to the
            //fields in the related module
            $relBean = BeanFactory::getDefinition($rel_module);
            if ($relBean) {
                $relationshipFields = $this->getRelationshipFields($relBean);
            }

            //now load from the rows
            foreach ($rows as $id => $vals)
            {
                if (empty($this->beans[$id]))
                {
                    $tmpBean = BeanFactory::retrieveBean($rel_module, $id, $retrieveParams);
                    if ($tmpBean) {
                        $result[$id] = $tmpBean;
                    }
                } else {
                    $result[$id] = $this->beans[$id];
                }

                if (!empty($result[$id]) && !empty($relationshipFields)) {
                    $this->populateRelationshipOnlyFields($relationshipFields, $vals, $result[$id]);
                }
            }

            //If we did a complete load, cache the result in $this->beans
            if (empty($params)) {
                $this->beans = $result;
                foreach($result as $id => $bean)
                {
                    if (!isset($this->rows[$id]))
                        $this->rows[$id] = array("id" => $id);
                }
            }
        }
        else {
            $result = $this->beans;
        }

        return $result;
    }

    /***
     * refresh relationship data to a related Bean
     * @param $relBean SugarBean
     * @return void
     */
    public function refreshRelationshipFields(SugarBean $relBean){

        if(empty($relBean) || empty($relBean->id)){
            return;
        }

        // load relationship data
        $data = $this->query(array(
            'where' => array(
                'lhs_field' => 'id',
                'operator' => '=',
                'rhs_value' => $relBean->id,
            )
        ));

        $rows = $data['rows'];

        if (isset($rows[$relBean->id])) {
            $relationshipFields = $this->getRelationshipFields($relBean);
            $this->populateRelationshipOnlyFields($relationshipFields, $rows[$relBean->id], $relBean);
            $this->resetLoaded();
        }
    }

    /***
     * helper method, to populate relationship data to a related Bean
     * @param $relationshipFields RelationshipFields
     * @param $relData array of relationship data
     * @param $relBean SugarBean, relative Bean
     * @return void
     */
    protected function populateRelationshipOnlyFields(array $relationshipFields, array $relData, SugarBean $relBean)
    {
        if (!empty($relationshipFields) &&
            !empty($relData) &&
            !empty($relBean)) {

            foreach($relationshipFields as $rfName => $field) {
                if (isset($relData[$rfName]))
                {
                    if (!empty($relBean)) {
                        $relBean->$field = $relData[$rfName];
                    }
                }
            }
        }
    }

    /***
     * helper method, to get relationship fields for a related Bean
     * @param $relBean SugarBean, relative Bean
     * @return $relationshipFields array()
     */
    protected function getRelationshipFields(SugarBean $relBean){
        $relationshipFields = array();
        if($relBean !== false)
        {
            // Deprecated: This format of relationship fields will be removed
            // please use the rname_link format instead
            $relationshipFields = $this->getRelationshipFieldMapping($relBean);
            if (!empty($this->def['rel_fields']))
            {
                //Find the field in the related module that maps to this
                foreach($this->def['rel_fields'] as $rfName => $rfDef)
                {
                    //This is pretty badly designed, but there is no mapping stored for fields in the relationship table
                    //to the fields to be populated in the related record.
                    foreach($relBean->field_defs as $f => $d)
                    {
                        if (!empty($d['relationship_fields'][$rfName])){
                            $relationshipFields[$rfName] = $d['relationship_fields'][$rfName];
                            break;
                        }
                    }
                }
            }

            if ($relBean && is_array($relBean->field_defs)) {
                foreach ($relBean->field_defs as $fieldName => $def) {
                    if (empty($def['rname_link'])) {
                        continue;
                    }
                    $relationshipFields[$def['rname_link']] = $fieldName;
                }
            }
        }

        return $relationshipFields;
    }


    /***
     * If there are any relationship fields, we need to figure out the mapping from the relationship fields to the
     * fields in the module vardefs
     */
    public function getRelationshipFieldMapping(SugarBean $seed = null){
        if ($seed == null)
            $seed = $this->focus;
        $relationshipFields = array();
        if (!empty($this->def['rel_fields']))
        {
            //Find the field in the related module that maps to this
            foreach($this->def['rel_fields'] as $rfName => $rfDef)
            {
                //This is pretty badly designed, but there is no mapping stored for fields in the relationship table
                //to the fields to be populated in the related record.
                foreach($seed->field_defs as $f => $d)
                {
                    if (!empty($d['relationship_fields'][$rfName])){
                        $relationshipFields[$rfName] = $d['relationship_fields'][$rfName];
                        break;
                    }
                }
            }
        }
        return $relationshipFields;
    }

    /**
     * @return bool true if this link has initialized its related beans.
     */
    public function beansAreLoaded() {
        return is_array($this->beans);
    }

    /**
     * use this function to create link between 2 objects
     * 1:1 will be treated like 1 to many.
     *
     * the function also allows for setting of values for additional field in the table being
     * updated to save the relationship, in case of many-to-many relationships this would be the join table.
     *
     * @param string|SugarBean|string[]|SugarBean[] $rel_keys array of ids or SugarBean objects. If you have the bean in memory, pass it in.
     * @param array $additional_values the values should be passed as key value pairs with column name as the key name and column value as key value.
     *
     * @return boolean|array          Return true if all relationships were added.  Return an array with the failed keys if any of them failed.
     */
    function add($rel_keys,$additional_values=array()) {
        if (!is_array($rel_keys))
            $rel_keys = array($rel_keys);

        $failures = array();

        foreach($rel_keys as $key)
        {
            //We must use beans for LogicHooks and other business logic to fire correctly
            if (!($key instanceof SugarBean)) {
                $key = $this->getRelatedBean($key);
                if (!($key instanceof SugarBean)) {
                    $GLOBALS['log']->error("Unable to load related bean by id");
                    return false;
                }
            }

            //If there are any relationship fields set on the related object, we should try to use them
            //$addition_values should override what we find in the record though
            $relationshipFields = $this->getRelationshipFieldMapping($key);
            foreach($relationshipFields as $rfName => $field) {
                if (!empty($key->$field) && !isset($additional_values[$rfName])){
                    $additional_values[$rfName] = $key->$field;
                }
            }

            if(empty($key->id) || empty($this->focus->id))
                return false;

            $lhs = $this->getSide() == REL_LHS ? $this->focus : $key;
            $rhs = $this->getSide() == REL_LHS ? $key : $this->focus;

            $success = $this->relationship->add($lhs, $rhs, $additional_values);

            if($success == false) {
                $failures[] = $key->id;
            }
        }
        if(!empty($failures)) {
            return $failures;
        }

        return true;
    }



    /**
     * Marks the relationship delted for this given record pair.
     * @param $id id of the Parent/Focus SugarBean
     * @param string $related_id id or SugarBean to unrelate. Pass a SugarBean if you have it.
     * @return boolean          true if delete was successful or false if it was not
     */
    function delete($id, $related_id='') {
        if (empty($this->focus->id))
            $this->focus = BeanFactory::getBean($this->focus->module_name, $id);
        if (!empty($related_id))
        {
            if (!($related_id instanceof SugarBean)) {
                $related_id = $this->getRelatedBean($related_id);
            }
            if ($this->getSide() == REL_LHS) {
                return $this->relationship->remove($this->focus, $related_id);
            }
            else {
                return $this->relationship->remove($related_id, $this->focus);
            }
        }
        else
        {
            return $this->relationship->removeAll($this);
        }
    }

    /**
     * Returns a SugarBean with the given ID from the related module.
     * @param bool $id id of related record to retrieve
     * @return SugarBean
     */
    protected function getRelatedBean($id = false)
    {
        $params = array('encode' => true, 'deleted' => true);
        // Set disable_row_level_security to true, so we can load the related bean
        // even when the bean doesn't belong to the users teams (for flav=pro and above)
        $params['disable_row_level_security'] = true;
        return BeanFactory::getBean($this->getRelatedModuleName(), $id, $params);
    }

    public function &__get($name)
    {
        switch($name)
        {
            case "relationship_type":
                return $this->relationship->type;
            case "_relationship":
                return $this->relationship;
            case "beans":
                if (!is_array($this->beans))
                    $this->getBeans();
                return $this->beans;
            case "rows":
                if (!is_array($this->rows))
                    $this->load();
                return $this->rows;
        }
        return $this->$name;
    }

    public function __set($name, $val)
    {
        if($name == "beans")
            $this->beans = $val;

    }

    /**
     * Add a bean object to the list of beans currently loaded to this relationship.
     * This for the most part should not need to be called except by the relatipnship implementation classes.
     * @param SugarBean $bean
     * @return void
     *
     * @deprecated
     */
    public function addBean($bean)
    {
        $this->resetLoaded();
    }

    /**
     * Remove a bean object from the list of beans currently loaded to this relationship.
     * This for the most part should not need to be called except by the relatipnship implementation classes.
     *
     * @param SugarBean $bean
     * @return void
     *
     * @deprecated
     */
    public function removeBean($bean)
    {
        $this->resetLoaded();
    }

    /**
     * Directly queries the databse for set of values. The relationship classes and not link should handle this.
     * @deprecated
     * @param $table_name string relationship table
     * @param $join_key_values array of key=>values to identify this relationship by
     * @return bool true if the given join key set exists in the relationship table
     */
    public function relationship_exists($table_name, $join_key_values) {

        //find the key values for the table.
        $dup_keys=$this->_get_alternate_key_fields($table_name);
        if (empty($dup_keys)) {
            $GLOBALS['log']->debug("No alternate key define, skipping duplicate check..");
            return false;
        }

        $delimiter='';
        $this->_duplicate_where=' WHERE ';
        foreach ($dup_keys as $field) {
            //look for key in  $join_key_values, if found add to filter criteria else abort duplicate checking.
            if (isset($join_key_values[$field])) {

                $this->_duplicate_where .= $delimiter.' '.$field." = ". $this->_db->quoted($join_key_values[$field]);
                $delimiter = ' AND ';
            } else {
                $GLOBALS['log']->error('Duplicate checking aborted, Please supply a value for this column '.$field);
                return false;
            }
        }
        //add deleted check.
        $this->_duplicate_where .= $delimiter.' deleted=0';

        $query='SELECT id FROM '.$table_name.$this->_duplicate_where;

        $GLOBALS['log']->debug("relationship_exists query(".$query.')');

        $row = $this->_db->fetchOne($query, true);

        if ($row == null) {
            return false;
        }
        else {
            $this->_duplicate_key=$row['id'];
            return true;
        }
    }

    //Below are functions not used directly and exist for backwards compatibility with customizations, will be removed in a later version

    /* returns array of keys for duplicate checking, first check for an index of type alternate_key, if not found searches for
     * primary key.
     *
     */
    public function _get_alternate_key_fields($table_name) {
        $indices = Link::get_link_table_definition($table_name, null, 'indices');
        if (!empty($indices)) {
            foreach ($indices as $index) {
                if ( isset($index['type']) && $index['type'] == 'alternate_key' ) {
                    return $index['fields'];
                }
            }
        }
        //bug 32623, when the relationship is built in old version, there is no alternate_key. we have to use join_key_lhs and join_key_lhs.
        $relDef = $this->relationship->def;
        if (!empty($relDef['join_key_lhs']) && !empty($relDef['join_key_rhs']))
            return array($relDef['join_key_lhs'], $relDef['join_key_rhs']);

        return array();
    }

    /**
     * @deprecated
     * Gets the vardef for the relationship of this link.
     */
    public function _get_link_table_definition($table_name, $def_name) {

        if (isset($this->relationship->def[$def_name]))
            return $this->relationship->def[$def_name];

        return null;
    }

    /**
     * @deprecated
     * Return the name of the role field for the passed many to many table.
     * if there is no role filed : return false
     * @param $table_name name of relationship table to inspect
     * @return bool|string
     */
    public function _get_link_table_role_field($table_name) {
        $varDefs = $this->_get_link_table_definition($table_name, 'fields');
        $role_field = false;
        if(!empty($varDefs)){
            $role_field = '';
            foreach($varDefs as $v){
                if(strpos($v['name'], '_role') !== false){
                    $role_field = $v['name'];
                }
            }
        }
        return $role_field;
    }

    /**
     * @deprecated
     *
     * @return boolean returns true if this link is LHS
     */
    public function _get_bean_position()
    {
        return $this->getSide() == REL_LHS;
    }
}
