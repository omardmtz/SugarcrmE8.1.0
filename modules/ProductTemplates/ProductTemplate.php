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
 * Data access class for the product_template table
 */
class ProductTemplate extends SugarBean {
	// Stored fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $name;
	var $description;
	var $vendor_part_num;
	var $cost_price;
	var $discount_price;
	var $list_price;
	var $list_usdollar;
	var $discount_usdollar;
	var $cost_usdollar;
	var $currency_id;
    var $base_rate;
	var $mft_part_num;
	var $status;
	var $date_available;
	var $weight;
	var $qty_in_stock;
	var $website;
	var $tax_class;
	var $support_name;
	var $support_description;
	var $support_contact;
	var $support_term;
	var $pricing_formula;
	var $pricing_factor;
    var $currency_symbol;
	var $default_currency_symbol;
	var $tax_class_name;


	// These are for related fields
	var $type_name;
	var $type_id;
	var $manufacturer_name;
	var $manufacturer_id;
	var $category_name;
	var $category_id;


	var $parent_node_id;
	var $node_id;
	var $parent_name;
	var $type;
	var $default_tree_type;	//specified in save_branch function
	var $category_tree_table = "category_tree";

	var $table_name = "product_templates";
	var $rel_manufacturers = "manufacturers";
	var $rel_types = "product_types";
	var $rel_categories = "product_categories";
	var $module_dir = 'ProductTemplates';
	var $object_name = "ProductTemplate";

	var $new_schema = true;

	var $importable = true;
	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array(
		"manufacturer_name"
		,"parent_node_id"
		,"parent_name"
		,"node_id"
		,"type"
	);

	public function __construct() {
		parent::__construct();
		$this->disable_row_level_security =true;

		$currency = BeanFactory::newBean('Currencies');
		$this->default_currency_symbol = $currency->getDefaultCurrencySymbol();
	}

	function get_summary_text()
	{
		return "$this->name";
	}

    /**
     * @deprecated
     * @param string $product_template_id
     */
	function clear_note_product_template_relationship($product_template_id)
	{
        $GLOBALS['log']->deprecated('ProductTemplate::clear_note_product_template_relationship() has been deprecated in 7.8');
        $query = sprintf(
            "UPDATE notes SET parent_id='', parent_type='' WHERE parent_id = %s AND deleted = 0",
            $this->db->quoted($product_template_id)
        );
		$this->db->query($query,true,"Error clearing note to product_template relationship: ");
	}

	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields() {
		global $app_list_strings;
		global $locale;
		global $sugar_config;
		// this is for quotes quicksearching a product. json_server does not make app_list_strings available
		// by default. If this code were added to json_server it would increase each call all the time
		if(empty($app_list_strings)) {
			if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '') {
				$current_language = $_SESSION['authenticated_user_language'];
			} else {
				$current_language = $sugar_config['default_language'];
			}
			$GLOBALS['log']->debug('current_language is: '.$current_language);

			//set module and application string arrays based upon selected language
			$app_list_strings = return_app_list_strings_language($current_language);
		}


        $currency = BeanFactory::getBean('Currencies', $this->currency_id);
        if($currency->id != $this->currency_id || $currency->deleted == 1) {
                $this->cost_price = $this->cost_usdollar;
                $this->discount_price = $this->discount_usdollar;
                $this->list_price = $this->list_usdollar;
                $this->currency_id = $currency->id;
                $this->base_rate = $currency->conversion_rate;
        }

 	    if(isset($this->currency_id) && !empty($this->currency_id)) {
	       $currency->retrieve($this->currency_id);
	       if($currency->deleted != 1){
	          $this->currency_symbol = $currency->symbol;
	       }
	    }

		$this->tax_class_name = (!empty($this->tax_class) && !empty($app_list_strings['tax_class_dom'][$this->tax_class])) ? $app_list_strings['tax_class_dom'][$this->tax_class] : "";
	}

    /**
     * @deprecated
     *
     * @param array $fromid
     * @param string $toid
     */
	function update_currency_id($fromid, $toid){
        $GLOBALS['log']->deprecated('ProductTemplate::update_currency_id() has been deprecated in 7.8');
		$idequals = '';

		$currency = BeanFactory::getBean('Currencies', $toid);
		foreach($fromid as $f){
			if(!empty($idequals)){
				$idequals .=' or ';
			}
			$idequals .= "currency_id='$f'";
		}

		if(!empty($idequals)){

			$query = "select cost_price, list_price, discount_price, id from ".$this->table_name."  where (". $idequals. ") and deleted=0 ;";
			$result = $this->db->query($query);
			while($row = $this->db->fetchByAssoc($result)){
				$query = "update ".$this->table_name." set currency_id='".$currency->id."', cost_usdollar='".$currency->convertToDollar($row['cost_price'])."', list_usdollar='".$currency->convertToDollar($row['list_price'])."', discount_usdollar='".$currency->convertToDollar($row['discount_price'])."' where id='".$row['id']."';";
				$this->db->query($query);

			}
		}
	}

	function get_list_view_data(){
		global $app_list_strings;

		$temp_array = parent::get_list_view_data();
		$temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
		$temp_array['STATUS'] = !empty($this->status) ? $app_list_strings['product_template_status_dom'][$this->status] : "";
		$temp_array['TAX_CLASS_NAME'] = !empty($this->tax_class)? $app_list_strings['tax_class_dom'][$this->tax_class] : "";
		$temp_array['PRICING_FORMULA_NAME'] = !empty($this->pricing_formula) ?$app_list_strings['pricing_formula_dom'][$this->pricing_formula]:"";
		$temp_array['ENCODED_NAME'] = $this->name;
		$temp_array['URL'] = $this->website;
		$temp_array['CATEGORY'] = $this->category_id;
		$temp_array['CATEGORY_NAME'] = $this->category_name;
		$temp_array['TYPE_NAME'] =  $this->type_name;
		$temp_array['QTY_IN_STOCK'] = $this->qty_in_stock;

		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
        $where_clauses = Array();
        $the_query_string = $GLOBALS['db']->quote($the_query_string);
        array_push($where_clauses, "name like '$the_query_string%'");
        if (is_numeric($the_query_string)) {
            array_push($where_clauses, "mft_part_num like '%$the_query_string%'");
            array_push($where_clauses, "vendor_part_num like '%$the_query_string%'");
        }

        $the_where = "";
        foreach($where_clauses as $clause)
        {
            if($the_where != "") $the_where .= " or ";
            $the_where .= $clause;
        }


        return $the_where;
    }

    /**
     * This function calculates any requested discount from the various formulas
     */
    public function calculateDiscountPrice()
    {
        if (!empty($this->pricing_formula)
            || !empty($this->cost_price)
            || !empty($this->list_price)
            || !empty($this->discount_price)
            || !empty($this->pricing_factor)) {
            $formula = $this->getPriceFormula($this->pricing_formula);

            if ($formula) {
                $this->discount_price = $formula->calculate_price(
                    $this->cost_price,
                    $this->list_price,
                    $this->discount_price,
                    $this->pricing_factor
                );
            }
        }
    }

    /**
     * Utiltity method to get the Pricing Formual Class
     *
     * @param string $formula
     * @param bool|false $refresh
     * @return bool|Object
     */
    protected function getPriceFormula($formula, $refresh = false)
    {
        if (!isset($GLOBALS['price_formulas']) || $refresh) {
            SugarAutoLoader::load('modules/ProductTemplates/Formulas.php');
            refresh_price_formulas();
        }


        if (!isset($GLOBALS['price_formulas'][$formula])) {
            return false;
        }

        SugarAutoLoader::load($GLOBALS['price_formulas'][$formula]);
        return new $formula;
    }
}
