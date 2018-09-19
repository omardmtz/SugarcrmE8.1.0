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

 // $Id: RelationshipHandler.php 45763 2009-04-01 19:16:18Z majed $






class RelationshipHandler
{

	var $db;							//Database link by reference

	var $base_module;					//name of module
	var $base_bean;						//actual object
	var $base_vardef_field;				//base's vardef field name of relationship with rel1

	var $rel1_module;					//name of related module
	var $rel1_bean;						//actual related object
	var $rel1_relationship_name;		//Relationship name between base and rel1
	var $rel1_vardef_field;				//rel1's vardef field name of relationship with rel2
	var $rel1_vardef_field_base;		//rel1's vardef field name of relationship with base

	var $rel2_module;					//name of related related module
	var $rel2_bean;						//actual related related object
	var $rel2_relationship_name;		//Relationship name between rel1 and rel2
	var $rel2_vardef_field;				//rel2's vardef field name of relationship with rel1


	var $base_array;					//Info array
	var $rel1_array;					//Info array
	var $rel2_array;					//Info array
	/*

	info arrays contain:

		'slabel' ->		singular module name in correct language
		'plabel' ->  	plural module name in correct language



	*/

    /**
     * @var Relationship Relationship object
     */
    protected $relationship;

///////////////////////////Setup and populate functions//////////////////////////////

    public function __construct($db, $base_module = '')
    {
        $this->db = $db;
        $this->base_module = $base_module;
        $this->relationship = new Relationship();
    }

	function set_rel_vardef_fields($base_vardef_field, $rel1_vardef_field=""){

		$this->base_vardef_field = $base_vardef_field;
		$this->rel1_vardef_field = $rel1_vardef_field;

	//end function set_rel_vardef_fields
	}


	function set_rel_relationship_names($build_rel2=false){

		$this->rel1_relationship_name = $this->base_bean->field_defs[$this->base_vardef_field]['relationship'];

	if($build_rel2==true){
		$this->rel2_relationship_name = $this->rel1_bean->field_defs[$this->rel1_vardef_field]['relationship'];
	}

	//end function set_rel_relationship_names
	}




///////////////////////////////END Setup and populate functions/////////////////////


	/*
	set the build_rel2 to true if you want the rel2 info array as well
	This function will build all the relationship info it can based on values set in the setup functions
	When you use the info arrays (rel1_array) or (rel2_array), make sure you always check for empty values
	*/
	function build_info($build_rel2=false){
		if($this->base_bean == null){
			$this->base_bean = BeanFactory::newBean($this->base_module);
		}

		if(empty($this->rel1_bean)){
			$this->build_rel1_info();
			$this->rel1_module = $this->rel1_bean->module_dir;
		}

		if($build_rel2==true && $this->rel2_bean==""){
			$this->build_rel2_info();
			$this->rel2_module = $this->rel2_bean->module_dir;
		}

		//translate the module titles to the proper language
		$this->build_module_labels($build_rel2);

	//end function build_info
	}

    function build_rel1_info()
    {
        $bean = BeanFactory::newBean($this->base_module);
        $rel_attribute1_name = $bean->field_defs[strtolower($this->base_vardef_field)]['relationship'];
        $rel_module1 = $this->relationship->get_other_module($rel_attribute1_name, $this->base_module);
        $this->rel1_bean = BeanFactory::newBean($rel_module1);
    }

    function build_rel2_info()
    {
        // Both sides of the if condition may need this
        if (empty($this->rel1_bean)) {
            $this->build_rel1_info();
        }

        if (empty($this->rel1_vardef_field)) {
            $this->rel2_bean = $this->rel1_bean;
        } else {
            $rel_attribute2_name = $this->rel1_bean->field_defs[strtolower($this->rel1_vardef_field)]['relationship'];
            $rel_module2 = $this->relationship->get_other_module($rel_attribute2_name, $this->rel1_bean->module_dir);
            $this->rel2_bean = BeanFactory::newBean($rel_module2);
        }
    }

	/*
	Translates the module names to their singular and plural label and puts them in
	the info arrays.  Does it for base, rel1, and rel2 if specified
	*/

	function build_module_labels($build_rel2=false){
		global $app_list_strings;

		///Base Module Labels
		if(!empty($app_list_strings['moduleList'][$this->base_bean->module_dir])){
			$this->base_array['plabel'] = $app_list_strings['moduleList'][$this->base_bean->module_dir];
		} else {
			$this->base_array['plabel'] = $this->base_bean->module_dir;
		}
		if(!empty($app_list_strings['moduleListSingular'][$this->base_bean->module_dir])){
			$this->base_array['slabel'] = $app_list_strings['moduleListSingular'][$this->base_bean->module_dir];
		} else {
			$this->base_array['slabel'] = $this->base_bean->object_name;
		}

		///Rel1 Module Labels
		if(!empty($app_list_strings['moduleList'][$this->rel1_bean->module_dir])){
			$this->rel1_array['plabel'] = $app_list_strings['moduleList'][$this->rel1_bean->module_dir];
		} else {
			$this->rel1_array['plabel'] = $this->rel1_bean->module_dir;
		}

		if(!empty($app_list_strings['moduleListSingular'][$this->rel1_bean->module_dir])){
			$this->rel1_array['slabel'] = $app_list_strings['moduleListSingular'][$this->rel1_bean->module_dir];
		} else {
			$this->rel1_array['slabel'] = $this->rel1_bean->object_name;
		}


		//Rel2 Module Labels
		if($build_rel2==true){

			if(!empty($app_list_strings['moduleList'][$this->rel2_bean->module_dir])){
				$this->rel2_array['plabel'] = $app_list_strings['moduleList'][$this->rel2_bean->module_dir];
			} else {
				$this->rel2_array['plabel'] = $this->rel2_bean->module_dir;
			}
			if(!empty($app_list_strings['moduleListSingular'][$this->rel2_bean->module_dir])){
				$this->rel2_array['slabel'] = $app_list_strings['moduleListSingular'][$this->rel2_bean->module_dir];
			} else {
				$this->rel2_array['slabel'] = $this->rel2_bean->module_dir;
			}
		//end if build_rel2 is true
		}

	//end function buld_module_lables
	}





	function build_related_list($type="base"){
		//type can be base, rel1

		$target_list = "";

		if($type=="base"){
			$target_list = $this->base_bean->get_linked_beans($this->base_vardef_field, $this->rel1_bean->object_name);
		//Possibility exists that this is a new relationship, so capture via relationship fields
			if(empty($target_list)){
				$target_list = search_filter_rel_info($this->base_bean, $this->rel1_bean->module_dir, $this->base_vardef_field);
			//end if the target list is empty
			}
		}

		if($type=="rel1"){
			$target_list = $this->rel1_bean->get_linked_beans($this->rel1_vardef_field, $this->rel2_bean->object_name);

			//Possibility exists that this is a new relationship, so capture via relationship fields
			if(empty($target_list)){
				$target_list = search_filter_rel_info($this->rel1_bean, $this->rel2_bean->module_dir, $this->rel1_vardef_field);
			//end if the target list is empty
			}
		}

		return $target_list;

	//end function build_related_list
	}




///////BEGIN Functions to find relationships/////////////////////////////////

	protected function getRoleColumns($defs)
	{
		if (!empty($defs['relationship_role_columns']) && is_array($defs['relationship_role_columns'])) {
	        return $defs['relationship_role_columns'];
	    }
	    if (!empty($defs['relationship_role_column']) && !empty($defs["relationship_role_column_value"])) {
	        return array($defs['relationship_role_column'] => $defs["relationship_role_column_value"]);
	    }
	    return array();

	}

function get_relationship_information(& $target_bean, $get_upstream_rel_field_name = false){

	$target_module_name = $target_bean->module_dir;
	$current_module_name = $this->base_module;

	//Look for downstream connection
    $rel_array = $this->relationship->retrieve_by_sides($current_module_name, $target_module_name, $this->db);
    $flip_sides = false;
    if (empty($rel_array)) {
        $rel_array = $this->relationship->retrieve_by_sides($target_module_name, $current_module_name, $this->db);
        $flip_sides = true;
    }
    //No relationship found, abort
    if (empty($rel_array)) {
        return;
    }


	//Does a downstream relationship exist
	if($rel_array!=null){
		if($rel_array['relationship_type']=="many-to-many"){
            $join_key = $flip_sides ? 'join_key_rhs' : 'join_key_lhs';
            $target_bean->{$rel_array[$join_key]} = $this->base_bean->id;
			foreach ($this->getRoleColumns($rel_array) as $column => $value) {
			    $target_bean->$column = $value;
			}
		//end if many-to-many
		}

		if($rel_array['relationship_type']=="one-to-many"){
			if (!empty($rel_array['join_key_rhs'])) {
			    $join_key = $flip_sides ? 'join_key_rhs' : 'join_key_lhs';
                $target_bean->{$rel_array[$join_key]} = $this->base_bean->id;
			}
			else {
                $rel_key = $flip_sides ? 'lhs_key' : 'rhs_key';
                $target_bean->{$rel_array[$rel_key]} = $this->base_bean->id;
			}
			foreach ($this->getRoleColumns($rel_array) as $column => $value) {
			    $target_bean->$column = $value;
			}
		//end if one-to-many
		}

		return;
	//end if downstream relationship exists
	}



	//Look for upstream connection
    $rel_array = $this->relationship->retrieve_by_sides($target_module_name, $current_module_name, $this->db);

	//Does an upstream relationship exist
	if($rel_array!=null){
		if($rel_array['relationship_type']=="many-to-many"){
            $join_key = $flip_sides ? 'join_key_lhs' : 'join_key_rhs';
            $target_bean->{$rel_array[$join_key]} = $this->base_bean->id;
			if(!empty($rel_array['relationship_role_column'])){
                $target_bean->{$rel_array['relationship_role_column']} = $rel_array['relationship_role_column_value'];
			}
		//end if many-to-many
		}

		if($rel_array['relationship_type']=="one-to-many"){
            $rel_key = $flip_sides ? 'lhs_key' : 'rhs_key';
            $this->{$rel_array[$rel_key]} = $this->base_bean->id;
			if(!empty($rel_array['relationship_role_column'])){
                $this->{$rel_array['relationship_role_column']} = $rel_array['relationship_role_column_value'];
			}
		//end if one-to-many
		}


		///Fill additional id field if necessary
		if(($id_name = $this->traverse_rel_meta($current_module_name, $target_bean, $rel_array['relationship_name'])) != null){
			$target_bean->$id_name = $this->base_bean->id;
            if($get_upstream_rel_field_name) {
                $target_bean->new_rel_relname = $id_name;
                $target_bean->new_rel_id = $this->base_bean->id;
            }
		}

	//end if an upstream relationship exists
	}

//end function get_relationship_information
}

function traverse_rel_meta($base_module, & $target_bean, $target_rel_name){
	$id_name = null;

	//returns name of variable to store id in
	//if none exists, then returns null
	foreach($target_bean->field_defs as $field_array){

		if(!empty($field_array['relationship']) && $field_array['relationship']==$target_rel_name){

			$id_name = $this->get_id_name($target_bean, $field_array['name']);
			return $id_name;
		//end if rel name match
		}

	//end foreach field def
	}

	return null;

//end function traverse_rel_meta
}


function get_id_name(& $target_bean, $field_name){

	foreach($target_bean->relationship_fields as $target_id => $rel_name){

		if($rel_name == $field_name){
			//relationship id found
			return $target_id;
		//end if match
		}
	//end foreach
	}

	return null;
//end function get_id_name
}

///////////////////////////END functions to find relationships //////////////////////


function process_by_rel_bean($rel1_module){

    $this->rel1_relationship_name = $this->relationship->retrieve_by_modules(
        $this->base_module,
        $rel1_module,
        $this->db
    );
	$this->rel1_module = $rel1_module;
	$this->rel1_bean = BeanFactory::newBean($this->rel1_module);

//end function process_by_rel_bean
}


function get_rel1_vardef_field_base($field_defs){
	foreach($field_defs as $field_array){

		if(!empty($field_array['relationship']) && $field_array['relationship']==$this->rel1_relationship_name){

			$this->rel1_vardef_field_base = $field_array['name'];
		//end if rel name match
		}

	//end foreach field def
	}

	return null;


//end get_rel1_vardef_field_base
}


function get_farthest_reach(){

	if($this->rel1_vardef_field!=""){
		//the farthest reach is rel2
		$this->build_info(true);
		return $this->rel2_bean;
	}

	//the farthest reach is rel1
	$this->build_info(false);
	return $this->rel1_bean;

//end function get_farthest_reach
}

//end class RelationshipHandler
}

