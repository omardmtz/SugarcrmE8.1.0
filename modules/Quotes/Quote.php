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
global $beanFiles;

require_once($beanFiles['Contact']);
require_once($beanFiles['Task']);
require_once($beanFiles['Note']);
require_once($beanFiles['Call']);
require_once($beanFiles['Lead']);
require_once($beanFiles['Email']);
require_once($beanFiles['Product']);
require_once($beanFiles['ProductBundle']);


// Quote is used to store customer quote information.
class Quote extends SugarBean
{
    /**
     * Standard Out Of The Box Quoted Closed Status
     *
     * @var array
     */
    public $closed_statuses = array('Closed Accepted', 'Closed Dead', 'Closed Lost');

    // Stored fields
    public $id;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $assigned_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $description;
    public $name;
    public $quote_type;
    public $purchase_order_num;
    public $quote_num;
    public $subtotal;
    public $deal_tot;
    public $new_sub;
    public $subtotal_usdollar;
    public $tax;
    public $tax_usdollar;
    public $shipping;
    public $shipping_usdollar;
    public $total;
    public $total_usdollar;
    public $date_quote_expected_closed;
    public $original_po_date;
    public $payment_terms;
    public $date_quote_closed;
    public $quote_stage;
    public $calc_grand_total;
    public $show_line_nums;
    public $team_id;
    public $team_name;

    public $billing_address_street;
    public $billing_address_city;
    public $billing_address_state;
    public $billing_address_country;
    public $billing_address_postalcode;
    public $shipping_address_street;
    public $shipping_address_city;
    public $shipping_address_state;
    public $shipping_address_country;
    public $shipping_address_postalcode;

    // These are related
    public $opportunity_id;
    public $opportunity_name;
    public $task_id;
    public $note_id;
    public $meeting_id;
    public $call_id;
    public $email_id;
    public $assigned_user_name;
    public $taxrate_name;
    public $taxrate_value;
    public $taxrate_id;
    public $shipper_id;
    public $shipper_name;
    public $currency_id;
    public $base_rate;
    public $currency_name;
    public $billing_account_name;
    public $billing_account_id;
    public $billing_contact_id;
    public $billing_contact_name;
    public $shipping_account_name;
    public $shipping_account_id;
    public $shipping_contact_id;
    public $shipping_contact_name;

    public $order_stage;

    public $table_name = "quotes";
    public $rel_account_table = "quotes_accounts";
    public $rel_contact_table = "quotes_contacts";
    public $rel_opportunity_table = "quotes_opportunities";
    public $contact_table = "contacts";
    public $account_table = "accounts";
    public $user_table = "users";
    public $opportunity_table = "opportunities";
    public $taxrate_table = "taxrates";
    public $module_dir = 'Quotes';
    public $rel_product_bundles = 'product_bundle_quote';

    public $object_name = "Quote";

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array(
        'taxrate_name',
        'taxrate_value',
        'taxrate_id',
        'assigned_user_name',
        'assigned_user_id',
        'task_id',
        'note_id',
        'meeting_id',
        'call_id',
        'email_id',
        'opportunity_name',
        'opportunity_id',
        'shipping_contact_id',
        'shipping_account_id',
        'billing_contact_id',
        'billing_account_id',
        'shipper_id',
        'shipper_name'
    );

    public $relationship_fields = array(
        'shipping_account_id' => 'shipping_accounts',
        'billing_account_id' => 'billing_accounts',
        'shipping_contact_id' => 'shipping_contacts',
        'billing_contact_id' => 'billing_contacts',
        'opportunity_id' => 'opportunities',
        'task_id' => 'tasks',
        'note_id' => 'notes',
        'meeting_id' => 'meetings',
        'call_id' => 'calls',
        'email_id' => 'emails',
        'bug_id' => 'bugs',
        'case_id' => 'cases',
        'contact_id' => 'contacts',
        'member_id' => 'members',
        'quote_id' => 'quotes',
    );
    public $new_schema = true;


    /**
     * sole constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Delete all the associated Product Bundles from a Quote, This ensures that no orphaned records are left behind,
     * when deleting a record.
     *
     * @param string $id The ID of the record that is being marked as deleted
     */
    public function mark_deleted($id)
    {
        // make sure we have the bean loaded
        if ($this->id !== $id) {
            $this->retrieve($id);
        }
        // load up all the product bundles and delete them
        $this->load_relationship('product_bundles');
        $bundles = $this->product_bundles->getBeans();
        /* @var $bundle ProductBundle */
        foreach ($bundles as $bundle) {
            $bundle->mark_deleted($bundle->id);
        }

        parent::mark_deleted($id);
    }

    /**
     * returns bean name
     *
     * @return string Bean name
     */
    public function get_summary_text()
    {
        return $this->name;
    }

    public function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    /**
     * @deprecated old model code that will be removed. Currently returns false to stop corrupting data.
     */
    public function fill_in_additional_detail_fields()
    {
        return false;
    }

    public function set_contact()
    {
        global $locale;

        $query = "SELECT con.salutation, con.first_name, con.last_name, con.assigned_user_id contact_name_owner, ";
        $query .= "con.id, c_q.contact_role from $this->contact_table  con, $this->rel_contact_table  c_q ";
        $query .= "where con.id = c_q.contact_id and c_q.quote_id = ? and c_q.deleted=0 and con.deleted=0";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id));

        // Get the id and the name.
        $this->shipping_contact_name = '';
        $this->shipping_contact_id = '';
        $this->shipping_contact_name_owner = '';
        $this->billing_contact_name = '';
        $this->billing_contact_id = '';
        $this->billing_contact_name_owner = '';
        $this->billing_contact_name_mod = '';
        $this->shipping_contact_name_mod = '';

        while ($row = $stmt->fetch()) {
            if ($row != null && $row['contact_role'] == 'Ship To') {
                $this->shipping_contact_name = $locale->formatName('Contacts', $row);
                $this->shipping_contact_id = stripslashes($row['id']);
                $this->shipping_contact_name_owner = stripslashes($row['contact_name_owner']);
                $this->shipping_contact_name_mod = 'Contacts';
            } elseif ($row != null && $row['contact_role'] == 'Bill To') {
                $this->billing_contact_name = $locale->formatName('Contacts', $row);
                $this->billing_contact_id = stripslashes($row['id']);
                $this->billing_contact_name_owner = stripslashes($row['contact_name_owner']);
                $this->billing_contact_name_mod = 'Contacts';
            }
        }

        $GLOBALS['log']->debug("shipping contact name is $this->shipping_contact_name");
        $GLOBALS['log']->debug("billing contact name is $this->billing_contact_name");
    }

    public function set_account()
    {
        $query = "SELECT acc.name, acc.id,acc.assigned_user_id account_name_owner, a_o.account_role ";
        $query .= "from $this->account_table  acc, $this->rel_account_table  a_o ";
        $query .= "where acc.id = a_o.account_id and a_o.quote_id = ? and a_o.deleted=0 and acc.deleted=0";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id));

        // Get the id and the name.
        $this->shipping_account_name = '';
        $this->shipping_account_id = '';
        $this->shipping_account_name_owner = '';
        $this->billing_account_name = '';
        $this->billing_account_id = '';
        $this->billing_account_name_owner = '';
        $this->billing_account_mod = '';
        $this->shipping_account_mod = '';

        while ($row = $stmt->fetch()) {
            if ($row != null && $row['account_role'] == 'Ship To') {
                $this->shipping_account_name = stripslashes($row['name']);
                $this->shipping_account_id = stripslashes($row['id']);
                $this->shipping_account_name_owner = stripslashes($row['account_name_owner']);
                $this->shipping_account_mod = 'Accounts';
            } elseif ($row != null && $row['account_role'] == 'Bill To') {
                $this->billing_account_name = stripslashes($row['name']);
                $this->billing_account_id = stripslashes($row['id']);
                $this->billing_account_name_owner = stripslashes($row['account_name_owner']);
                $this->billing_account_mod = 'Accounts';
            }
        }

        $GLOBALS['log']->debug("billing account name is $this->billing_account_name");
        $GLOBALS['log']->debug("shipping account name is $this->shipping_account_name");
    }

    public function calculate_total()
    {
        $this->total = $this->subtotal + $this->shipping + $this->tax - $this->deal_tot;
    }

    public function set_taxrate_info()
    {
        $query = "SELECT tr.id, tr.name, tr.value ";
        $query .= "from $this->taxrate_table  tr, $this->table_name  q ";
        $query .= "where tr.id = q.taxrate_id and q.id = ? and tr.deleted=0 and q.deleted=0 and tr.status = 'Active'";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id));

        // Get the id and the name.
        $row = $stmt->fetch();

        if ($row != null) {
            $this->taxrate_name = stripslashes($row['name']);
            $this->taxrate_value = stripslashes($row['value']);
            $this->taxrate_id = stripslashes($row['id']);
        } else {
            $this->taxrate_name = '';
            $this->taxrate_value = '';
            $this->taxrate_id = '';
        }
    }

    /** Sets the associated shipper's name
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
     */
    public function set_shipper()
    {
        $query = "SELECT s1.name from shippers s1, $this->table_name q1 ";
        $query .= "where s1.id = q1.shipper_id and q1.id = ? and q1.deleted=0 and s1.deleted=0";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id));

        // Get the id and the name.
        $row = $stmt->fetch();

        if ($row != null) {
            $this->shipper_name = $row['name'];
        } else {
            $this->shipper_name = '';
        }
    }

    /** Sets the associated shipper's name
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
     */
    public function set_opportunity()
    {
        // First, get the list of IDs.
        $query = "SELECT opp.id, opp.name, opp.assigned_user_id opportunity_name_owner ";
        $query .= "from $this->opportunity_table  opp, $this->rel_opportunity_table  a_o ";
        $query .= "where opp.id = a_o.opportunity_id and a_o.quote_id = ? and a_o.deleted=0 and opp.deleted=0";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->id));

        // Get the id and the name.
        $row = $stmt->fetch();

        if ($row != null) {
            $this->opportunity_name = stripslashes($row['name']);
            $this->opportunity_id = stripslashes($row['id']);
            $this->opportunity_name_owner = stripslashes($row['opportunity_name_owner']);
            $this->opportunity_name_mod = 'Opportunities';
        } else {
            $this->opportunity_name = '';
            $this->opportunity_id = '';
            $this->opportunity_name_owner = '';
            $this->opportunity_name_mod = '';
        }
    }

    /**
     * builds a generic search based on the query string using or
     * do not include any $this-> because this is called on without having the class instantiated
     */
    public function build_generic_where_clause($the_query_string)
    {
        $where_clauses = array();
        $the_query_string = $GLOBALS['db']->quoted($the_query_string);
        array_push($where_clauses, "quotes.name like " . $this->db->quoted($the_query_string. "%"));

        $the_where = "";
        foreach ($where_clauses as $clause) {
            if ($the_where != "") {
                $the_where .= " or ";
            }
            $the_where .= $clause;
        }

        return $the_where;
    }

    public function get_list_view_data()
    {

        global $current_language, $current_user, $mod_strings, $app_list_strings, $sugar_config;
        $app_strings = return_application_language($current_language);

        $temp_array = $this->get_list_view_array();
        if (isset($this->assigned_name)) {
            $temp_array['TEAM_NAME'] = $this->assigned_name;
        }
        if (isset($this->team_name)) {
            $temp_array['TEAM_NAME'] = $this->team_name;
        }

        $temp_array["ENCODED_NAME"] = $this->name;
        $temp_array["QUOTE_NUM"] = format_number_display($this->quote_num);
        return $temp_array;
    }

    public function update_currency_id($fromid, $toid)
    {


        $idequals = '';
        $currency = BeanFactory::getBean('Currencies', $toid);

        foreach ($fromid as $f) {
            if (!empty($idequals)) {
                $idequals .= ' OR ';
            }
            $idequals .= " currency_id = " . $this->db->quoted($f);
        }

        if (!empty($idequals)) {
            $query = "SELECT tax, total, subtotal,shipping, id FROM " . $this->table_name . "  WHERE (" . $idequals . ") AND deleted=0 ;";
            $result = $this->db->query($query);

            while ($row = $this->db->fetchByAssoc($result)) {
                $query = "update " . $this->table_name . " set currency_id = " . $this->db->quoted($currency->id) .
                    ", tax_usdollar='" . $currency->convertToDollar(
                        $row['tax']
                    ) . "', subtotal_usdollar='" . $currency->convertToDollar(
                        $row['subtotal']
                    ) . "', total_usdollar='" . $currency->convertToDollar(
                        $row['total']
                    ) . "', shipping_usdollar='" . $currency->convertToDollar(
                        $row['shipping']
                    ) . "' where id=" . $this->db->quoted($row['id']) . ";";
                $this->db->query($query);
            }
        }
    }

    public function format_specific_fields($fieldsDef)
    {
        global $disable_num_format;
        global $current_user;

        if ((!empty($disable_num_format) && $disable_num_format) || empty($current_user)) {
            return;
        }

        $this->number_formatting_done = true;
    }

    public function save($check_notify = false)
    {
        // CL Fix for 14365.  Have a default quote_type value
        if (!isset($this->quote_type) || empty($this->quote_type)) {
            $this->quote_type = 'Quotes';
        }

        return parent::save($check_notify);
    }

    public function set_notification_body($xtpl, $quote)
    {
        $xtpl->assign("QUOTE_SUBJECT", $quote->name);
        $xtpl->assign("QUOTE_STATUS", $quote->quote_stage);
        $xtpl->assign("QUOTE_CLOSEDATE", $quote->date_quote_expected_closed);
        $xtpl->assign("QUOTE_DESCRIPTION", $quote->description);

        return $xtpl;
    }

    public function get_product_bundles()
    {
        $query = new SugarQuery();
        $prodBundlesBean = BeanFactory::newBean('ProductBundles');
        $query->select('*');
        $query->from($prodBundlesBean);
        $join = $query->joinSubpanel($this, 'product_bundles', array('joinType' => 'INNER'));
        $query->orderBy($join->relationshipTableAlias . '.bundle_index', 'ASC');

        $prodBundles = $prodBundlesBean->fetchFromQuery($query);

        return array_values($prodBundles);
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    public function listviewACLHelper()
    {
        global $current_user;

        $array_assign = parent::listviewACLHelper();
        $is_owner = false;

        if (!empty($this->shipping_account_name)) {
            if (!empty($this->shipping_account_name_owner)) {
                $is_owner = $current_user->id == $this->shipping_account_name_owner;
            }
        }
        if (ACLController::checkAccess('Accounts', 'view', $is_owner)) {
            $array_assign['SHIPPING_ACCOUNT'] = 'a';
        } else {
            $array_assign['SHIPPING_ACCOUNT'] = 'span';
        }

        $is_owner = false;

        if (!empty($this->billing_account_name)) {
            if (!empty($this->billing_account_name_owner)) {
                $is_owner = $current_user->id == $this->billing_account_name_owner;
            }
        }
        if (ACLController::checkAccess('Accounts', 'view', $is_owner)) {
            $array_assign['BILLING_ACCOUNT'] = 'a';
        } else {
            $array_assign['BILLING_ACCOUNT'] = 'span';
        }

        $is_owner = false;

        if (!empty($this->shipping_contact_name)) {
            if (!empty($this->shipping_contact_name_owner)) {
                $is_owner = $current_user->id == $this->shipping_contact_name_owner;
            }
        }

        if (ACLController::checkAccess('Contacts', 'view', $is_owner)) {
            $array_assign['SHIPPING_CONTACT'] = 'a';
        } else {
            $array_assign['SHIPPING_CONTACT'] = 'span';
        }

        $is_owner = false;

        if (!empty($this->billing_contact_name)) {
            if (!empty($this->billing_contact_name_owner)) {
                $is_owner = $current_user->id == $this->billing_contact_name_owner;
            }
        }
        if (ACLController::checkAccess('Contacts', 'view', $is_owner)) {
            $array_assign['BILLING_CONTACT'] = 'a';
        } else {
            $array_assign['BILLING_CONTACT'] = 'span';
        }

        $is_owner = false;

        if (!empty($this->opportunity_name)) {
            if (!empty($this->opportunity_name_owner)) {
                $is_owner = $current_user->id == $this->opportunity_name_owner;
            }
        }

        if (ACLController::checkAccess('Opportunities', 'view', $is_owner)) {
            $array_assign['OPPORTUNITY'] = 'a';
        } else {
            $array_assign['OPPORTUNITY'] = 'span';
        }

        return $array_assign;
    }

    /**
     * Static helper function for getting releated account info.
     */
    public function get_account_detail($quote_id)
    {
        if (empty($quote_id)) {
            return array();
        }
        $ret_array = array();
        $db = DBManagerFactory::getInstance();
        $query = "SELECT acc.id, acc.name, acc.assigned_user_id "
            . "FROM accounts acc, quotes_accounts a_q "
            . "WHERE acc.id=a_q.account_id"
            . " AND a_q.quote_id='$quote_id'"
            . " AND a_q.account_role='Bill To'"
            . " AND a_q.deleted=0"
            . " AND acc.deleted=0";
        $result = $db->query($query, true, "Error filling in opportunity account details: ");
        $row = $db->fetchByAssoc($result);
        if ($row != null) {
            $ret_array['name'] = $row['name'];
            $ret_array['id'] = $row['id'];
            $ret_array['assigned_user_id'] = $row['assigned_user_id'];
        }
        return $ret_array;
    }

    /**
     * returns the export-appropriate file name
     *
     * @param string type LBL_PROPOSAL or LBL_INVOICE
     * @return string
     */
    public function getExportFilename($type)
    {
        global $mod_strings;
        global $locale;

        $filename = preg_replace("#[^A-Z0-9\-_\.]#i", "_", $this->shipping_account_name);

        if (!empty($this->quote_num)) {
            $filename .= "_{$this->quote_num}";
        }

        return $locale->translateCharset($mod_strings[$type] . "_{$filename}.pdf", 'UTF-8', $this->getExportCharset());
    }

    /**
     * get the related opportunities
     *
     * @return array related opportunities
     */
    public function getRelatedOpportunities()
    {
        $results = array();
        $query = "select * from quotes_opportunities where quote_id = ? and deleted = 0";
        $stmt = $this->db->getConnection()->executeQuery($query, array($this->id));
        while ($row = $stmt->fetch()) {
            $results[] = $row;
        }
        return $results;
    }

    /**
     * get the related opportunity count
     *
     * @return int number of related opportunities
     */
    public function getRelatedOpportunityCount()
    {
        $query = "select count(id) from quotes_opportunities where quote_id = ? and deleted = 0";
        $conn = $this->db->getConnection();
        $count = $conn->executeQuery($query, array($this->id))->fetchColumn();

        return $count;
    }

    /**
     * Is the current quote in a closed stage?
     *
     * @return bool
     */
    public function isClosed()
    {
        return in_array($this->quote_stage, $this->closed_statuses, true);
    }

    /**
     * Bean specific logic for when SugarFieldCurrency_id::save() is called to make sure we can update the base_rate
     *
     * @return bool
     */
    public function updateCurrencyBaseRate()
    {
        // if the quote_stage changed, we should still update it, unless it's a change from closed to closed
        if(isset($this->fetched_row['quote_stage']) && $this->fetched_row['quote_stage'] != $this->quote_stage) {
            return !(in_array($this->fetched_row['quote_stage'], $this->closed_statuses, true) && $this->isClosed());
        }

        return !$this->isClosed();
    }

    /**
     * This defines the supporting modules which have metadata needed by Quotes to be fully
     * functional on the Mobile application
     *
     * @return array
     */
    public static function getMobileSupportingModules()
    {
        $modules = parent::getMobileSupportingModules();
        return array_merge($modules, array(
            'Products',
            'Manufacturers',
            'ProductTypes',
            'ProductCategories',
            'ProductTemplates',
            'ProductBundles',
            'ProductBundleNotes',
            'Manufacturers',
            'TaxRates',
            'Shippers',
        ));
    }
}
