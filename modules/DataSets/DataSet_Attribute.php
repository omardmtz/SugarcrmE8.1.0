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



// DataSet Attribute is used to store attribute information for a particular data format.
class DataSet_Attribute extends SugarBean {
    // Stored fields
    var $id;
    var $deleted;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;

    var $parent_id;			//dataset_layout id
    var $bg_color;			//bg color
    var $cell_size;				//width in column		//height in row
    var $size_type;			//Size Type
    var $style;				//bold, italic
    var $wrap;				//wrap or no wrap text
    var $font_color;
    var $font_size = 0;
    var $format_type;		//Text, Currency, Datastamp
    var $attribute_type;	//Head or Body
    var $display_name;		//Header only
    var $display_type = "Normal";	//Header only	Default, Name, Scalar

    //for the name of the parent if an interlocked data set
    var $parent_name;
    //for the name of the child if an interlocked data set
    var $child_name;

    //for related fields
    var $query_name;
    var $report_name;

    var $table_name = "dataset_attributes";
    var $module_dir = 'DataSets';
    var $object_name = "DataSet_Attribute";
    var $rel_layout_table = "dataset_layouts";
    var $rel_datasets_table = "data_sets";
    var $disable_custom_fields = true;

    var $new_schema = true;

    var $column_fields = Array("id"
        ,"date_entered"
        ,"date_modified"
        ,"modified_user_id"
        ,"created_by"
        ,"parent_id"
        ,"bg_color"
        ,"cell_size"
        ,"size_type"
        ,"style"
        ,"wrap"
        ,"font_color"
        ,"font_size"
        ,"format_type"
        ,"format"
        ,"attribute_type"
        ,"display_name"
        ,"display_type"
        );


    // This is used to retrieve related fields from form posts.
    var $additional_column_fields = Array();

    // This is the list of fields that are in the lists.
    var $list_fields = array();
    // This is the list of fields that are required
    var $required_fields =  array();



    public function __construct() {
        global $dictionary;
        if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
            require('metadata/dataset_attributesMetaData.php');
        }
        parent::__construct();

        $this->disable_row_level_security =true;

    }



    function get_summary_text()
    {
        return "$this->display_name";
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
        global $app_strings, $mod_strings;
        global $app_list_strings;

        global $current_user;

        if(empty($this->exportable)) $this->exportable="0";

        $temp_array = parent::get_list_view_data();
        $temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
        $temp_array['OUTPUT_DEFAULT'] = $app_list_strings['dataset_output_default_dom'][$this->output_default];
        $temp_array['LIST_ORDER_Y'] = $this->list_order_y;
        $temp_array['EXPORTABLE'] = $this->exportable;
        $temp_array['HEADER'] = $this->header;
        $temp_array['QUERY_NAME'] = $this->query_name;
        $temp_array['REPORT_NAME'] = $this->report_name;

        return $temp_array;
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

    //end function get_list_view_data
    }


//end class datasets
}

?>
