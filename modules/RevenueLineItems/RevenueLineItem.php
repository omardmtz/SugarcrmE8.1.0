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
class RevenueLineItem extends SugarBean
{
    const STATUS_CONVERTED_TO_QUOTE = 'Converted to Quote';

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
    public $best_case;
    public $likely_case;
    public $worst_case;
    public $base_rate;
    public $probability;
    public $date_closed;
    public $date_closed_timestamp;
    public $commit_stage;
    public $product_type;

    /**
     * @public String      The Current Sales Stage
     */
    public $sales_stage;

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
    public $opportunity_id;
    public $opportunity_name;
    public $contact_name;
    public $contact_id;
    public $related_product_id;
    public $contracts;
    public $product_index;

    public $table_name = "revenue_line_items";
    public $rel_manufacturers = "manufacturers";
    public $rel_types = "product_types";
    public $rel_products = "product_product";
    public $rel_categories = "product_categories";

    public $object_name = "RevenueLineItem";
    public $module_dir = 'RevenueLineItems';
    public $new_schema = true;
    public $importable = false;

    public $experts;

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array('quote_id', 'quote_name', 'related_product_id');
    

    /**
     * Default Constructor
     */
    public function __construct()
    {
        parent::__construct();

        global $current_user;
        if (!empty($current_user)) {
            $this->team_id = $current_user->default_team; //default_team is a team id
        } else {
            $this->team_id = 1; // make the item globally accessible
        }

        $currency = BeanFactory::newBean('Currencies');
        $this->default_currency_symbol = $currency->getDefaultCurrencySymbol();
    }

    /**
     * Get summary text
     */
    public function get_summary_text()
    {
        return "$this->name";
    }

    /**
     * Bean specific logic for when SugarFieldCurrency_id::save() is called to make sure we can update the base_rate
     *
     * @return bool
     */
    public function updateCurrencyBaseRate()
    {
        return !in_array($this->sales_stage, $this->getClosedStages());
    }

    /**
     * Utility Method to make sure the best/worst case values are set
     */
    protected function setBestWorstFromLikely()
    {
        if ($this->ACLFieldAccess('best_case', 'write') &&
            empty($this->best_case) &&
            (string) $this->best_case !== '0'
        ) {
            $this->best_case = $this->likely_case;
        }
        if ($this->ACLFieldAccess('worst_case', 'write') &&
            empty($this->worst_case) &&
            (string) $this->worst_case !== '0'
        ) {
            $this->worst_case = $this->likely_case;
        }
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

    /**
     * {@inheritdoc}
     */
    public function save($check_notify = false)
    {
        //If an opportunity_id value is provided, lookup the Account information (if available)
        if (!empty($this->opportunity_id)) {
            $this->setAccountIdForOpportunity($this->opportunity_id);
        }

        $this->setBestWorstFromLikely();

        $this->checkQuantity();

        $this->setDiscountPrice();

        if ($this->probability === '') {
            $this->mapProbabilityFromSalesStage();
        }
        
        $this->mapFieldsFromProductTemplate();
        $this->mapFieldsFromOpportunity();

        $id = parent::save($check_notify);
        // this only happens when ent is built out
        $this->saveProductWorksheet();

        return $id;
    }

    /**
     * Set the discount_price
     */
    protected function setDiscountPrice()
    {
        if (
            !is_numeric($this->discount_price) &&
            empty($this->product_template_id) &&
            is_numeric($this->likely_case)
        ) {
            $quantity = floatval($this->quantity);

            if (empty($quantity)) {
                $quantity = 1;
            }

            $this->discount_price = SugarMath::init($this->likely_case)->div($quantity)->result();
        }
    }

    
    /**
     * Override the current SugarBean functionality to make sure that when this method is called that it will also
     * take care of any draft worksheets by rolling-up the data
     *
     * @param string $id            The ID of the record we want to delete
     */
    public function mark_deleted($id)
    {
        parent::mark_deleted($id);
           
        // this only happens when ent is built out
        $this->saveProductWorksheet();
    }


    /**
     * map fields if opportunity id is set
     */
    protected function mapFieldsFromOpportunity()
    {
        if (!empty($this->opportunity_id) && empty($this->product_type)) {
            $opp = BeanFactory::getBean('Opportunities', $this->opportunity_id);
            $this->product_type = $opp->opportunity_type;
        }
    }

    /**
     * Handling mapping the probability from the sales stage.
     */
    protected function mapProbabilityFromSalesStage()
    {
        global $app_list_strings;
        if (!empty($this->sales_stage)) {
            $prob_arr = $app_list_strings['sales_probability_dom'];
            if (isset($prob_arr[$this->sales_stage])) {
                $this->probability = $prob_arr[$this->sales_stage];
            }
        }
    }

    /**
     * Save the updated product to the worksheet, this will create one if one does not exist
     * this will also update one if a draft version exists
     *
     * @return bool         True if the worksheet was saved/updated, false otherwise
     */
    protected function saveProductWorksheet()
    {
        $settings = Forecast::getSettings();
        if ($settings['is_setup'] && $settings['forecast_by'] === $this->module_name) {
            // save the a draft of each product
            /* @var $worksheet ForecastWorksheet */
            $worksheet = BeanFactory::newBean('ForecastWorksheets');
            $worksheet->saveRelatedProduct($this);
            return true;
        }

        return false;
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

    /**
     * Handle the mapping of the fields from the product template to the product
     */
    protected function mapFieldsFromProductTemplate()
    {
        if (!empty($this->product_template_id)
            && $this->fetched_row['product_template_id'] != $this->product_template_id
        ) {
            /* @var $pt ProductTemplate */
            $pt = BeanFactory::getBean('ProductTemplates', $this->product_template_id);

            $this->category_id = $pt->category_id;
            $this->mft_part_num = $pt->mft_part_num;
            $this->list_price = SugarCurrency::convertAmount($pt->list_price, $pt->currency_id, $this->currency_id);
            $this->cost_price = SugarCurrency::convertAmount($pt->cost_price, $pt->currency_id, $this->currency_id);
            $this->discount_price = SugarCurrency::convertAmount($pt->discount_price, $pt->currency_id, $this->currency_id); // discount_price = unit price on the front end...
            $this->list_usdollar = $pt->list_usdollar;
            $this->cost_usdollar = $pt->cost_usdollar;
            $this->discount_usdollar = $pt->discount_usdollar;
            $this->tax_class = $pt->tax_class;
            $this->weight = $pt->weight;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function bean_implements($interface)
    {
        // if we are installing, we want to return false, really hacky, but OOB default on ENT and ULT is to not
        // have RevenueLineItems ACLed
        if (isset($GLOBALS['installing']) && $GLOBALS['installing'] === true) {
            return false;
        }

        // if we are using opportunities with RLI's we should return true, otherwise return false
        $settings = Opportunity::getSettings();
        if (isset($settings['opps_view_by']) && $settings['opps_view_by'] === 'RevenueLineItems') {
            switch ($interface) {
                case 'ACL':
                    return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     * @deprecated
     */
    public function listviewACLHelper()
    {
        $GLOBALS['log']->deprecated('RevenueLineItem::listviewACLHelper() has been deprecated in 7.8');
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
     * Converts (copies) RLI to Products (QuotedLineItem)
     * @return Product
     */
    public function convertToQuotedLineItem()
    {
        /* @var $product Product */
        $product = BeanFactory::newBean('Products');
        $product->id = create_guid();
        $product->new_with_id = true;
        foreach ($this->getFieldDefinitions() as $field) {
            if ($field['name'] == 'id') {
                // if it's the ID field, associate it back to the product on the relationship field
                $product->revenuelineitem_id = $this->{$field['name']};
            } else {
                $product->{$field['name']} = $this->{$field['name']};
            }
        }
        // use product name if available
        if (!empty($this->product_template_id)) {
            $pt = BeanFactory::getBean('ProductTemplates', $this->product_template_id);
            if (!empty($pt) && !empty($pt->name)) {
                $product->name = $pt->name;
            }
        }
        // if discount_price (unit_price) is not set, use likely_case
        if (strlen($this->discount_price) == 0) {
            $product->discount_price = $this->likely_case;
        }

        return $product;
    }

    /**
     * Test if this rli can be converted to a quote.
     *
     * @return bool|string  Returns true if it can be converted, otherwise it returns
     *                      a string with the reason it couldn't be converted.
     */
    public function canConvertToQuote()
    {
        $mod_strings = return_module_language($GLOBALS['current_language'], $this->module_dir);
        if (empty($this->product_template_id) && !empty($this->category_id)) {
            return $mod_strings['LBL_CONVERT_INVALID_RLI_PRODUCT'];
        } elseif (!empty($this->quote_id)) {
            return $mod_strings['LBL_CONVERT_INVALID_RLI_ALREADYQUOTED'];
        }

        return true;
    }

    /**
     * getClosedStages
     *
     * Return an array of closed stage names from the admin bean.
     *
     * @access protected
     * @return array array of closed stage values
     */
    public function getClosedStages()
    {
        $settings = Forecast::getSettings();

        // get all possible closed stages
        $stages = array_merge(
            (array)$settings['sales_stage_won'],
            (array)$settings['sales_stage_lost']
        );
        // db quote values
        foreach($stages as $stage_key => $stage_value) {
            $stages[$stage_key] = $this->db->quote($stage_value);
        }
        return $stages;
    }

}
