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

 * Description:
 ********************************************************************************/

// DataSet_Layout is used to store customer information.
class DataSet_Layout extends SugarBean {
    // Stored fields
    var $id;
    var $deleted;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;

    var $layout_type;
    var $parent_id;
    var $parent_value;
    var $list_order_x;
    var $list_order_z;
    var $row_header_id;
    var $hide_column = "0";

    var $table_name = "dataset_layouts";
    var $module_dir = 'DataSets';
    var $object_name = "DataSet_Layout";
    var $rel_attribute_table = "dataset_attributes";
    var $rel_datasets_table = "data_sets";
    var $disable_custom_fields = true;
    var $new_schema = true;

    var $column_fields = Array("id"
        ,"date_entered"
        ,"date_modified"
        ,"modified_user_id"
        ,"created_by"
        ,"layout_type"
        ,"parent_id"
        ,"parent_value"
        ,"list_order_x"
        ,"list_order_z"
        ,"row_header_id"
        ,"hide_column"
        );


    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = Array();

    // This is the list of fields that are in the lists.
    var $list_fields = array();
    // This is the list of fields that are required
    var $required_fields =  array("parent_id"=>1);


//Controller Array for list_order stuff
    var $controller_def = Array(
        "list_x" => "Y"
        ,"list_y" => "N"
        ,"parent_var" => "parent_id"
        ,"start_var" => "list_order_x"
        ,"start_axis" => "x"
        );


    public function __construct() {
        global $dictionary;
        if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
            require('metadata/dataset_layoutsMetaData.php');
        }
        parent::__construct();

        $this->disable_row_level_security =true;

    }



    function get_summary_text()
    {
        return "$this->layout_type";
    }

    public function save_relationship_changes($is_update, $exclude = array())
    {
    }

    function mark_relationships_deleted($id)
    {
    }

    function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    function fill_in_additional_detail_fields()
    {

    }

    function get_list_view_data(){

    }
    /**
        builds a generic search based on the query string using or
        do not include any $this-> because this is called on without having the class instantiated
    */
    function build_generic_where_clause ($the_query_string) {
    $where_clauses = Array();
    $the_query_string = addslashes($the_query_string);
    array_push($where_clauses, "name like '$the_query_string%'");


    $the_where = "";
    foreach($where_clauses as $clause)
    {
        if($the_where != "") $the_where .= " or ";
        $the_where .= $clause;
    }


    return $the_where;
}


	function construct($parent_id, $layout_type, $list_order_x, $display_type, $parent_value){

	//used when enabling custom layout on dataset
		$this->parent_id = $parent_id;
		$this->layout_type = $layout_type;
		//it could be false if coming from the add_columns_to_layout function in custom query
		if($list_order_x!==false){
			$this->list_order_x = $list_order_x;
		}
		$this->display_type = $display_type;
		$this->parent_value = $parent_value;
		$this->save();
	//end function construct
	}

	function get_attribute_id($attribute_type, $layout_id=""){
		if($layout_id=="") $layout_id = $this->id;
			$query = "	SELECT ".$this->rel_attribute_table.".id
						FROM ".$this->rel_attribute_table."
						WHERE ".$this->rel_attribute_table.".parent_id = '".$layout_id."'
						AND ".$this->rel_attribute_table.".attribute_type = '$attribute_type'
						AND ".$this->rel_attribute_table.".deleted=0
						AND ".$this->rel_attribute_table.".deleted=0
						";

		$result = $this->db->query($query,true," Error getting attribute ID: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null){
			return $row['id'];
		}else{
			return false;
		}
	//end function get_attribute_id
	}

	function clear_all_layout($data_set_id){
		//Select all layout records
        $qb = $this->db->getConnection()->createQueryBuilder();
        $qb->select('id')
            ->from($this->table_name)
            ->where($qb->expr()->eq('parent_id', $qb->createPositionalParameter($data_set_id)));

        $stmt = $qb->execute();

			// Print out the calculation column info
        while ($beanId = $stmt->fetchColumn()) {
				//Mark all attributes deleted
            BeanFactory::deleteBean('DataSet_Attribute', $beanId);

				//Remove the layout records
            $this->mark_deleted($beanId);

			//end while
			}
		//end if rows exist
		//}

	//end function mark_all_layout
	}

	function get_layout_array($data_set_id, $hide_columns=false){


		//if this is the final report then hide_columns should be set to true
		if($hide_columns==true){
			$hide_columns_where = "AND (".$this->table_name.".hide_column='0' OR ".$this->table_name.".hide_column='off' OR ".$this->table_name.".hide_column IS NULL OR ".$this->table_name.".hide_column = '')";
		} else {
			$hide_columns_where = "";
		}

		$layout_array = array();

		//gets custom_layout column_array
		//Select all layout records for this data set
		$query = 	"SELECT $this->table_name.* from $this->table_name
					 where $this->table_name.parent_id='$data_set_id'
					 AND $this->table_name.deleted='0'
					 ".$hide_columns_where."
					 ORDER BY list_order_x
					 ";

		$result = $this->db->query($query,true," Error retrieving layout records for this data set: ");

			while (($row = $this->db->fetchByAssoc($result)) != null) {
				//Get head attribute information
				$head_attribute_id = $this->get_attribute_id("Head", $row['id']);
				$head_att_object = BeanFactory::newBean('DataSet_Attribute');
				if(!empty($head_attribute_id) && $head_attribute_id!=""){
					$head_att_object->retrieve($head_attribute_id);
////////////////Head Specific Information
                    $layout_array[$row['parent_value']]['head']['font_size'] 	= $head_att_object->font_size;
                    $layout_array[$row['parent_value']]['head']['font_color'] 	= $head_att_object->font_color;
                    $layout_array[$row['parent_value']]['head']['bg_color'] 	= $head_att_object->bg_color;
                    if($head_att_object->wrap=="0"){
                        $wrap = "nowrap";
                    } else {
                        $wrap = "wrap";
                    }

                    $layout_array[$row['parent_value']]['head']['wrap'] 		= $wrap;
                    $layout_array[$row['parent_value']]['head']['style'] 		= $head_att_object->style;

                //end if header attribute exists
                }



                //Get body attribute information
                $body_attribute_id = $this->get_attribute_id("Body", $row['id']);
                $body_att_object = BeanFactory::newBean('DataSet_Attribute');
                if(!empty($body_attribute_id) && $body_attribute_id!=""){
                    $body_att_object->retrieve($body_attribute_id);

////////////////Body Specific Information
                    $layout_array[$row['parent_value']]['body']['font_size'] 	= $body_att_object->font_size;
                    $layout_array[$row['parent_value']]['body']['font_color'] 	= $body_att_object->font_color;
                    $layout_array[$row['parent_value']]['body']['bg_color'] 	= $body_att_object->bg_color;
                    if($body_att_object->wrap=="0"){
                        $wrap = "nowrap";
                    } else {
                        $wrap = "wrap";
                    }

                    $layout_array[$row['parent_value']]['body']['wrap'] 		= $wrap;
                    $layout_array[$row['parent_value']]['body']['style'] 		= $body_att_object->style;
                    $layout_array[$row['parent_value']]['body']['format_type'] 		= $body_att_object->format_type;

                //end if body attribute exists
                }


//////////////////Column Display Name

                //check for scalar name
                if(!empty($head_att_object->display_type) && $head_att_object->display_type=="Scalar"){
                    $scalar_object = new ScalarFormat();
                    $display_name = $scalar_object->format_scalar($head_att_object->format_type, "", $row['parent_value']);
                } else {
                //normal display name type

                    if(!empty($head_att_object->display_name)){
                        $display_name = $head_att_object->display_name;
                    } else {
                        $display_name = $row['parent_value'];
                        if($display_name =="") $display_name = "&nbsp;";
                    //end if to use standard display or custom
                    }

                //end if scalar vs. normal
                }

//////////////////Column Width
                if(!empty($body_att_object->cell_size)){
                    $column_width = $body_att_object->cell_size."".$body_att_object->size_type;
                } else {
                    $column_width = "";
                }




///Build Array////////////////////////
                //Display Name
                $layout_array[$row['parent_value']]['display_name'] = $display_name;
                //Default Name
                $layout_array[$row['parent_value']]['default_name'] = $row['parent_value'];
                //Column Width
                $layout_array[$row['parent_value']]['column_width'] = $column_width;



            //end while
            }
        //end if rows exist
        //}

        return $layout_array;

//end function get_layout_array
}


    function get_att_object($type){
        $attribute_id = $this->get_attribute_id($type);
        $attribute_object = BeanFactory::getBean('DataSet_Attribute', $attribute_id);
        return $attribute_object;
    //end function get_att_object
    }

//end class datasets_Layout
}
