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

// Product is used to store customer information.
class Product extends SugarBean
{
    CONST STATUS_CONVERTED_TO_QUOTE = 'Converted to Quote';

    const STATUS_QUOTED = 'Quotes';

    // Stored fields
    public $id;
    public $deleted;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $name;
    public $product_template_id;
    public $description;
    public $vendor_part_num;
    public $cost_price;
    public $discount_price;
    public $list_price;
    public $list_usdollar;
    public $discount_usdollar;
    public $cost_usdollar;
    public $deal_calc;
    public $deal_calc_usdollar;
    public $discount_amount_usdollar;
    public $currency_id;
    public $mft_part_num;
    public $status;
    public $date_purchased;
    public $weight;
    public $quantity;
    public $website;
    public $tax_class;
    public $support_name;
    public $support_description;
    public $support_contact;
    public $support_term;
    public $date_support_expires;
    public $date_support_starts;
    public $pricing_formula;
    public $pricing_factor;
    public $team_id;
    public $serial_number;
    public $asset_number;
    public $book_value;
    public $book_value_usdollar;
    public $book_value_date;
    public $currency_symbol;
    public $currency_name;
    public $default_currency_symbol;
    public $discount_amount;
    public $best_case = 0;
    public $likely_case = 0;
    public $worst_case = 0;
    public $base_rate;
    public $probability;
    public $date_closed;
    public $date_closed_timestamp;
    public $commit_stage;
    public $opportunity_id;
    public $product_type;

    // These are for related fields
    public $assigned_user_id;
    public $assigned_user_name;
    public $type_name;
    public $type_id;
    public $quote_id;
    public $quote_name;
    public $manufacturer_name;
    public $manufacturer_id;
    public $category_name;
    public $category_id;
    public $account_name;
    public $account_id;
    public $contact_name;
    public $contact_id;
    public $related_product_id;
    public $contracts;
    public $product_index;
    public $revenuelineitem_id;


    /**
     * Don't update the quote on save.
     *
     * @var bool
     */
    public $ignoreQuoteSave = false;

    public $table_name = "products";
    public $rel_manufacturers = "manufacturers";
    public $rel_types = "product_types";
    public $rel_products = "product_product";
    public $rel_categories = "product_categories";

    public $object_name = "Product";
    public $module_dir = 'Products';
    public $new_schema = true;
    public $importable = true;

    public $experts;

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array('quote_id', 'quote_name', 'related_product_id');

    public $relationship_fields = array('account_id'=> 'account_link', 'related_product_id' => 'related_products');


    // This is the list of fields that are copied over from product template.
    public $template_fields = array(
        'mft_part_num',
        'vendor_part_num',
        'website',
        'tax_class',
        'manufacturer_id',
        'type_id',
        'category_id',
        'team_id',
        'weight',
        'support_name',
        'support_term',
        'support_description',
        'support_contact',
        'description',
        'cost_price',
        'discount_price',
        'list_price',
    );


    public function __construct()
    {

        parent::__construct();

        $this->team_id = 1; // make the item globally accessible

        $currency = BeanFactory::newBean('Currencies');
        $this->default_currency_symbol = $currency->getDefaultCurrencySymbol();
    }


    public function get_summary_text()
    {
        return "$this->name";
    }


    /**
     * @deprecated
     */
    public function fill_in_additional_list_fields()
    {
        $GLOBALS['log']->deprecated('Product::fill_in_additional_list_fields() has been deprecated in 7.8');
        $this->fill_in_additional_detail_fields();
    }

    /**
     * @deprecated
     *
     * get_list_view_data
     * Returns a list view of the associated Products.  This view is used in the Subpanel
     * listings.
     */
    public function get_list_view_data()
    {
        $GLOBALS['log']->deprecated('Product::get_list_view_data() has been deprecated in 7.8');
        global $current_language, $app_strings, $app_list_strings, $current_user, $timedate, $locale;
        $product_mod_strings = return_module_language($current_language,"Products");
        require('modules/Products/config.php');
        //$this->format_all_fields();

        if ($this->date_purchased == '0000-00-00') {
            $the_date_purchased = '';
        } else {
            $the_date_purchased = $this->date_purchased;
            $db_date_purchased = $timedate->to_db_date($this->date_purchased, false);

        }
        $the_date_support_expires = $this->date_support_expires;
        $db_date_support_expires = $timedate->to_db_date($this->date_support_expires, false);

        $expired = $timedate->asDbDate($timedate->getNow()->get($support_expired));
        $coming_due = $timedate->asDbDate($timedate->getNow()->get($support_coming_due));

        if (!empty($the_date_support_expires) && $db_date_support_expires < $expired) {
            $the_date_support_expires = "<strong><font color='$support_expired_color'>$the_date_support_expires</font></strong>";
        }
        if (!empty($the_date_support_expires) && $db_date_support_expires < $coming_due) {
            $the_date_support_expires = "<strong><font color='$support_coming_due_color'>$the_date_support_expires</font></strong>";
        }
        if ($this->date_support_expires == '0000-00-00') {
            $the_date_support_expires = '';
        }

        $temp_array = $this->get_list_view_array();
        $temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
        if (!empty($this->status)) {
            $temp_array['STATUS'] = $app_list_strings['product_status_dom'][$this->status];
        }
        $temp_array['ENCODED_NAME'] = $this->name;
        $temp_array['DATE_SUPPORT_EXPIRES'] = $the_date_support_expires;
        $temp_array['DATE_PURCHASED'] = $the_date_purchased;


        $params['currency_id'] = $this->currency_id;
        $temp_array['LIST_PRICE'] = $this->list_price;
        $temp_array['DISCOUNT_PRICE'] = $this->discount_price;
        $temp_array['COST_PRICE'] = $this->cost_price;
        if (isset($this->discount_select) && $this->discount_select) {
            $temp_array['DISCOUNT_AMOUNT'] = $this->discount_amount . "%";
        } else {
            $temp_array['DISCOUNT_AMOUNT'] = $this->discount_amount;
        }

        $temp_array['ACCOUNT_NAME'] = empty($this->account_name) ? '' : $this->account_name;
        $temp_array['CONTACT_NAME'] = empty($this->contact_name) ? '' : $this->contact_name;
        return $temp_array;
    }

    /**
     * @deprecated
     *
     * This is only used by BWC Modules, not sidecar modules
     *
     * builds a generic search based on the query string using or
     * do not include any $this-> because this is called on without having the class instantiated
     */
    public function build_generic_where_clause($the_query_string)
    {
        $GLOBALS['log']->deprecated('Product::build_generic_where_clause() has been deprecated in 7.8');
        $where_clauses = array();
        $the_query_string = $GLOBALS['db']->quote($the_query_string);
        array_push($where_clauses, "name like '$the_query_string%'");
        if (is_numeric($the_query_string)) {
            array_push($where_clauses, "mft_part_num like '%$the_query_string%'");
            array_push($where_clauses, "vendor_part_num like '%$the_query_string%'");
        }

        $the_where = "";
        foreach ($where_clauses as $clause) {
            if ($the_where != "") {
                $the_where .= " or ";
            }
            $the_where .= $clause;
        }


        return $the_where;
    }

    /**
     * Utility method for checking the quantity
     */
    protected function checkQuantity()
    {
        if ($this->quantity === '' || is_null($this->quantity)) {
            $this->quantity = 0;
        }
    }

    public function save($check_notify = false)
    {
        //If an opportunity_id value is provided, lookup the Account information (if available)
        if (!empty($this->opportunity_id)) {
            $this->setAccountIdForOpportunity($this->opportunity_id);
        }

        $this->populateFromTemplate();

        // RPS - begin - decimals cant be null in sql server
        if ($this->cost_price == '') {
            $this->cost_price = 0;
        }
        if ($this->discount_price == '') {
            $this->discount_price = 0;
        }
        if ($this->list_price == '') {
            $this->list_price = 0;
        }
        if ($this->weight == '') {
            $this->weight = 0;
        }
        if ($this->book_value == '') {
            $this->book_value = 0;
        }
        if ($this->discount_amount == '') {
            $this->discount_amount = 0;
        }
        if ($this->deal_calc == '') {
            $this->deal_calc = 0;
        }

        $this->checkQuantity();

        $this->calculateDiscountPrice();

        $id = parent::save($check_notify);

        return $id;
	}

    /**
     * @deprecated
     *
     * product_type is no longer on Products, it was part of the RLI module
     *
     * map fields if opportunity id is set
     */
    protected function mapFieldsFromOpportunity()
    {
        $GLOBALS['log']->deprecated('Product::mapFieldsFromOpportunity() has been deprecated in 7.8');
        if(!empty($this->opportunity_id) && empty($this->product_type)) {
            $opp = BeanFactory::getBean('Opportunities', $this->opportunity_id);
            $this->product_type = $opp->opportunity_type;
        }
    }

    /**
     * Handle Converting DateClosed to a Timestamp
     *
     * This was replace by SugarLogic in the vardefs
     *
     * @deprecated
     */
    protected function convertDateClosedToTimestamp()
    {
        $GLOBALS['log']->deprecated('Product::convertDateClosedToTimestamp() has been deprecated in 7.8');
        $timedate = TimeDate::getInstance();
        if ($timedate->check_matching_format($this->date_closed, TimeDate::DB_DATE_FORMAT)) {
            $date_close_db = $this->date_closed;
        } else {
            $date_close_db = $timedate->to_db_date($this->date_closed);
        }

        if (!empty($date_close_db)) {
            $date_close_datetime = $timedate->fromDbDate($date_close_db);
            $this->date_closed_timestamp = $date_close_datetime->getTimestamp();
        }
    }

    /**
     * Sets the account_id value for instance given an opportunityId argument of the Opportunity id
     *
     * @param $opportunityId String value of the Opportunity id
     * @return bool true if account_id was set; false otherwise
     */
    protected function setAccountIdForOpportunity($opportunityId)
    {
        $opp = BeanFactory::getBean('Opportunities', $opportunityId);
        if ($opp->load_relationship('accounts')) {
            $accounts = $opp->accounts->get();
            if (!empty($accounts)) {
                // get the first row
                $this->account_id = array_shift($accounts);
                return true;
            }
        }
        return false;
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    /**
     * @deprecated
     *
     * This is only used for BWC modules, so need to keep it around
     *
     * @return array
     */
    public function listviewACLHelper()
    {
        $GLOBALS['log']->deprecated('Product::listviewACLHelper() has been deprecated in 7.8');
        $array_assign = parent::listviewACLHelper();

        $is_owner = false;
        if (!empty($this->contact_name)) {

            if (!empty($this->contact_name_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->contact_name_owner;
            }
        }
        if (ACLController::checkAccess('Contacts', 'view', $is_owner)) {
            $array_assign['CONTACT'] = 'a';
        } else {
            $array_assign['CONTACT'] = 'span';
        }
        $is_owner = false;
        if (!empty($this->account_name)) {

            if (!empty($this->account_name_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->account_name_owner;
            }
        }
        if (ACLController::checkAccess('Accounts', 'view', $is_owner)) {
            $array_assign['ACCOUNT'] = 'a';
        } else {
            $array_assign['ACCOUNT'] = 'span';
        }
        $is_owner = false;
        if (!empty($this->quote_name)) {

            if (!empty($this->quote_name_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->quote_name_owner;
            }
        }
        if (ACLController::checkAccess('Quotes', 'view', $is_owner)) {
            $array_assign['QUOTE'] = 'a';
        } else {
            $array_assign['QUOTE'] = 'span';
        }

        return $array_assign;
    }

    /**
     * Converts (copies) a Products (QuotedLineItem) to a Revenue Line Item
     * @return RevenueLineItem
     */
    public function convertToRevenueLineItem()
    {
        /* @var $rli RevenueLineItem */
        $rli = BeanFactory::newBean('RevenueLineItems');
        $rli->id = create_guid();
        $rli->new_with_id = true;
        $rli->fetched_row = array();

        foreach ($this->getFieldDefinitions() as $field) {
            if ($field['name'] != 'id' && isset($this->fetched_row[$field['name']])) {
                $rli->{$field['name']} = $this->fetched_row[$field['name']];
                // set the fetched row, so we prevent the product_template from fetching again
                // when the re-save happens because of the relationships
                $rli->fetched_row[$field['name']] = $this->fetched_row[$field['name']];
            }
        }

        if ($this->discount_select == 1) {
            // we have a percentage discount, but we don't allow the use of percentages on
            // the RevenueLineItem module yet, so we need to set discount_select to 0
            // and calculate out the correct discount_amount.
            $rli->discount_select = 0;
            $rli->discount_amount = SugarMath::init()->
                exp('(?*?)*(?/100)', array($this->discount_price, $this->quantity, $this->discount_amount))->
                result();
        }


        // since we don't have a likely_case on products,
        if ($rli->likely_case == '0.00' || empty($rli->likely_case)) {
            //undo bad math from quotes.
            $rli->likely_case = $this->total_amount;
        }

        $this->revenuelineitem_id = $rli->id;
        $this->ignoreQuoteSave = true;
        $this->save();

        return $rli;
    }

    /**
     * This function loads in values from the product's template
     */
    protected function populateFromTemplate()
    {
        if (!isset($this->product_template_id)) {
            // No template to choose from
            return false;
        }
        if (isset($this->fetched_row['product_template_id'])
            && $this->product_template_id == $this->fetched_row['product_template_id']) {
            // Templates are the same, don't do anything
            return false;
        }

        $template = BeanFactory::getBean('ProductTemplates', $this->product_template_id);
        
        foreach ($this->template_fields as $template_field) {
            // do not copy from template if field is:  Not empty, or has an int value equal to zero, or a string value equal to '0' or '0.0'
            if (!empty($this->$template_field)
                || (isset($this->$template_field)
                    && ($this->$template_field === 0
                        || $this->$template_field === '0'
                        || $this->$template_field === '0.0'
                        )
                    )
            ) {
                continue;
            }
            if (isset($template->$template_field)) {
                $this->$template_field = $template->$template_field;
            }
        }

        return true;
    }

    /**
     * This function calculates any requested discount from the various formulas
     */
    protected function calculateDiscountPrice()
    {
        if (!empty($this->pricing_formula)
            || !empty($this->cost_price)
            || !empty($this->list_price)
            || !empty($this->discount_price)
            || !empty($this->pricing_factor)) {

            $formula = $this->getPriceFormula($this->pricing_formula);

            if ($formula) {
                $this->discount_price = $formula->calculate_price($this->cost_price,$this->list_price,$this->discount_price,$this->pricing_factor);
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

    /**
     * Bean specific logic for when SugarFieldCurrency_id::save() is called to make sure we can update the base_rate
     *
     * @return bool
     */
    public function updateCurrencyBaseRate()
    {
        // if we are in the quote save, ignore this as it's a new record
        // and it's not linked yet, so we need to keep the base_rate set from the quote.
        if ($this->ignoreQuoteSave) {
            return false;
        }
        // need to go though product bundles
        $this->load_relationship('product_bundles');
        // grab the first and only one
        $bundles = $this->product_bundles->getBeans();
        $bundle = array_pop($bundles);

        // make sure we have a bundle
        if (empty($bundle)) {
            return true;
        }

        // load the bundle -> quotes relationship
        $bundle->load_relationship('quotes');

        // get the beans
        $quotes = $bundle->quotes->getBeans();
        $quote = array_pop($quotes);

        if (empty($quote)) {
            return true;
        }

        // if the quote is not closed, we should update the base rate
        return !$quote->isClosed();

    }
}
