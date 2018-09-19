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

use  Sugarcrm\Sugarcrm\Util\Arrays\ArrayFunctions\ArrayFunctions;


if (!defined('SUGARCRM_SECONDS_PER_DAY')) {
    define('SUGARCRM_SECONDS_PER_DAY', 60 * 60 * 24);
}

if (!defined('CONTRACT_BUILT_IN_WORKFLOW_ID')) {
    define('CONTRACT_BUILT_IN_WORKFLOW_ID', 'hardcode-work-flow-ae89-contractbf59');
}

class Contract extends SugarBean
{
    public $id;
    public $name;
    public $reference_code;
    public $status;
    public $account_id;
    public $account_name;
    public $opportunity_id;
    public $opportunity_name;
    public $team_id;
    public $team_name;
    public $team_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_name;
    public $description;
    public $start_date;
    public $end_date;
    public $currency_id;
    public $base_rate;
    public $currency_name;
    public $total_contract_value;
    public $total_contract_value_usdollar;
    public $company_signed_date;
    public $customer_signed_date;
    public $contract_term;
    public $expiration_notice;
    public $time_to_expiry;
    public $date_entered;
    public $date_modified;
    public $deleted;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_link;
    public $contacts;
    public $notes;
    public $products;
    public $quote_id;
    public $type;
    public $type_options;
    public $contract_types;

    public $rel_opportunity_table = 'contracts_opportunities';
    public $rel_quote_table = 'contracts_quotes';
    public $table_name = 'contracts';
    public $object_name = 'Contract';
    public $user_preferences;

    public $encodeFields = array();
    public $relationship_fields = array(
        'opportunity_id' => 'opportunities',
        'note_id' => 'notes',
        'quote_id' => 'quotes',
    );
    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array('revision');

    public $importable = true;

    public $new_schema = true;
    public $module_dir = 'Contracts';


    public function __construct()
    {
        parent::__construct();
        $this->setupCustomFields('Contract'); //parameter is module name
        //#14107 jchi 09/02/2008
        //$this->disable_row_level_security = false;
    }

    function save($check_notify = false)
    {
        /** @var TimeDate $timedate */
        global $timedate;

        $isCronWorkFlow = !empty($_SESSION["workflow_cron"]) && $_SESSION["workflow_cron"] == "Yes";
        $isCronWorkFlow = $isCronWorkFlow && !empty($_SESSION["workflow_id_cron"]) && ArrayFunctions::in_array_access(
            CONTRACT_BUILT_IN_WORKFLOW_ID,
            $_SESSION["workflow_id_cron"]
        );

        //decimals cant be null in sql server
        if ($this->expiration_notice) {
            /*
             * Convert expiration notice date to DB format.
             * to_db returns empty string for dates in DB format
             */
            $expNotice = $timedate->to_db($this->expiration_notice) ?: $this->expiration_notice;

            // The workflow scheduler will be created when expiration notice date will change.
            if ($isCronWorkFlow) {
                $this->special_notification = true;
                $check_notify = true;
            } elseif ($expNotice != $this->fetched_row['expiration_notice']) {
                require_once("include/workflow/time_utils.php");
                $time_array['time_int'] = '0';
                $time_array['time_int_type'] = 'datetime';
                $time_array['target_field'] = 'expiration_notice';
                check_for_schedule($this, CONTRACT_BUILT_IN_WORKFLOW_ID, $time_array);
            }
        }

        if ($this->total_contract_value == '') {
            $this->total_contract_value = 0;
        }

        if ($this->total_contract_value_usdollar == '') {
            $this->total_contract_value_usdollar = 0;
        }

        // contracts_opportunities is many to many but for some reason the UI implements it as if it's one to many
        // that is, for a contract, only one opp can be linked to it
        // workaround here to delete the current entry so when it's inserted later we have only the new record
        if (!empty($this->rel_fields_before_value['opportunity_id']) &&
            $this->rel_fields_before_value['opportunity_id'] != $this->opportunity_id) {
            $query = 'delete from ' . $this->rel_opportunity_table . " where contract_id = '" . $this->id . "'";
            $this->db->query($query);
        }

        $this->setCalculatedValues(false);
        $return_id = parent::save($check_notify);
        
        if ($this->expiration_notice && $isCronWorkFlow) {
            $this->special_notification = false;
        }

        return $return_id;
    }

    function get_summary_text()
    {
        return $this->name;
    }

    function is_authenticated()
    {
        return $this->authenticated;
    }

    function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    function _set_related_opportunity_info()
    {
        // only need to populate opp if it's empty
        if (empty($this->opportunity_id)) {
            $contracts_table = $this->table_name;
            $opportunity_link_table = $this->rel_opportunity_table;
            $query = "SELECT opportunities.id, opportunities.name "
                . "FROM opportunities "
                . "INNER JOIN $opportunity_link_table "
                . "ON opportunities.id=$opportunity_link_table.opportunity_id "
                . "INNER JOIN $contracts_table "
                . "ON $contracts_table.id=$opportunity_link_table.contract_id "
                . "WHERE contracts.id='$this->id' AND contracts.deleted=0 AND opportunities.deleted=0 AND $opportunity_link_table.deleted=0 ";
            $result = $this->db->query($query, true, 'Error retrieving opportunity info for contract: ');
            $row = $this->db->fetchByAssoc($result);

            if (!empty($row)) {
                $this->opportunity_id = stripslashes($row['id']);
                $this->opportunity_name = stripslashes($row['name']);
            }
        }
    }

    function _set_related_account_info()
    {
        $contracts_table = $this->table_name;

        if (!empty($this->account_id)) {
            $query = "SELECT accounts.name "
                . "FROM accounts "
                . "INNER JOIN $contracts_table "
                . "ON accounts.id=$contracts_table.account_id "
                . "WHERE $contracts_table.id='$this->id' AND $contracts_table.deleted=0 AND accounts.deleted=0 ";
            $name = $this->db->getOne($query, true, 'Error retrieving account info for contract: ');

            if (!empty($name)) {
                $this->account_name = stripslashes($name);
            }
        }
    }

    /**
     * Set contract term based on start and end dates
     *
     * @param bool $isFromDb Whether the bean is fetched from database
     */
    protected function _set_contract_term($isFromDb)
    {
        $start_date_timestamp = $this->dateToTimestamp($this->start_date, $isFromDb);
        $end_date_timestamp = $this->dateToTimestamp($this->end_date, $isFromDb);
        $this->contract_term = '';
        if (!empty($start_date_timestamp) && !empty($end_date_timestamp)) {
            $this->contract_term = floor(($end_date_timestamp - $start_date_timestamp) / constant('SUGARCRM_SECONDS_PER_DAY'));
        }
    }

    /**
     * Set contract time to expiry based on end date
     *
     * @param bool $isFromDb Whether the bean is fetched from database
     */
    protected function _set_time_to_expiry($isFromDb)
    {
        $end_date_timestamp = $this->dateToTimestamp($this->end_date, $isFromDb);
        $now = time();
        $this->time_to_expiry = '';
        if (!empty($end_date_timestamp)) {
            $this->time_to_expiry = ($end_date_timestamp - $now) / constant('SUGARCRM_SECONDS_PER_DAY');
        }
    }

    /**
     * Convert date to timestamp
     *
     * @param string $date   Date string representation
     * @param bool $isFromDb Whether the bean is fetched from database
     *
     * @return int
     */
    protected function dateToTimestamp($date, $isFromDb)
    {
        global $timedate;

        if (!$date) {
            return 0;
        }

        if ($isFromDb) {
            $date = $timedate->to_db_date($date, false);
        }
        $timestamp = strtotime($date);

        return $timestamp;
    }

    function fill_in_additional_detail_fields()
    {
        parent::fill_in_additional_detail_fields();
        $this->setCalculatedValues(true);

        $types = get_bean_select_array(true, 'ContractType', 'name', 'deleted=0', 'list_order');
        $this->type_options = get_select_options_with_id($types, $this->type);
        $currency = BeanFactory::newBean('Currencies');

        if (isset($this->currency_id) && !empty($this->currency_id)) {
            $currency->retrieve($this->currency_id);

            if ($currency->deleted != 1) {
                $this->currency_name = $currency->iso4217 . ' ' . $currency->symbol;
            } else {
                $this->currency_name = $currency->getDefaultISO4217() . ' ' . $currency->getDefaultCurrencySymbol();
            }
        } else {
            $this->currency_name = $currency->getDefaultISO4217() . ' ' . $currency->getDefaultCurrencySymbol();
        }
    }

    /**
     * Set the non-generic calculated value
     *
     * @param bool $isFromDb Whether the bean is fetched from database
     */
    protected function setCalculatedValues($isFromDb)
    {
        $this->_set_related_account_info();
        $this->_set_related_opportunity_info();
        $this->_set_contract_term($isFromDb);
        $this->_set_time_to_expiry($isFromDb);
    }

    public function list_view_parse_additional_sections(&$list_form)
    {
        return $list_form;
    }

    function get_list_view_data()
    {
        $fields = $this->get_list_view_array();

        //$fields['TOTAL_CONTRACT_VALUE']= format_number($fields['TOTAL_CONTRACT_VALUE']);
        $this->contract_types = getContractTypesDropDown();
        $fields['TYPE'] = isset($this->contract_types[$fields['TYPE']]) ? $this->contract_types[$fields['TYPE']] : $fields['TYPE'];
        return $fields;
    }

    function mark_relationships_deleted($id)
    {
        // Do nothing, this call is here to avoid default delete processing
        // since Delete.php handles deletion of children records.
    }

    function bean_implements($interface)
    {
        $ret_val = false;

        switch ($interface) {
            case 'ACL':
                $ret_val = true;
                break;
        }

        return $ret_val;
    }

    function listviewACLHelper()
    {
        global $current_user;
        $array_assign = parent::listviewACLHelper();
        $is_owner = false;

        if (!empty($this->account_id)) {
            if (!empty($this->account_id_owner)) {
                global $current_user;
                $is_owner = ($current_user->id == $this->account_id_owner);
            }
        }

        if (!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess(
                'Accounts',
                'view',
                $is_owner
            )
        ) {
            $array_assign['ACCOUNT'] = 'a';
        } else {
            $array_assign['ACCOUNT'] = 'span';
        }

        if (!empty($this->opportunity_name)) {
            if (!empty($this->opportunity_name_owner)) {
                global $current_user;
                $is_owner = $current_user->id == $this->opportunity_name_owner;
            }
        }

        if (!ACLController::moduleSupportsACL('Opportunities') || ACLController::checkAccess(
                'Opportunities',
                'view',
                $is_owner
            )
        ) {
            $array_assign['OPPORTUNITY'] = 'a';
        } else {
            $array_assign['OPPORTUNITY'] = 'span';
        }

        return $array_assign;
    }

    /**
     * contracts_documents
     * This method is called by the Subpanel code (see the subpaneldefs.php of this module).
     * We named it contracts_documents so that the return_relationship hidden form value set in the Subpanel
     * widget code may be used to look up the contracts_documents relationship as defined in the
     * linked_documentsMetaData.php file.  The query in this method is customized so as to
     * do a JOIN on the document_revisions table to retrieve the latest document revision for
     * a particular document.
     *
     * @return $query String SQL query to retrieve the documents to display in the subpanel
     */
    function get_contract_documents()
    {
        $this->load_relationship('contracts_documents');
        $query_array = $this->contracts_documents->getQuery(array('return_as_array' => true));
        $query = <<<KGB
            SELECT documents.*,
				documents.document_revision_id AS latest_revision_id,
				for_latest_revision.revision AS latest_revision_name,
				for_selected_revision.revision AS selected_revision_name,
				linked_documents.document_revision_id AS selected_revision_id,
				contracts.status AS contract_status,
				for_selected_revision.filename AS selected_revision_filename,
				linked_documents.id AS linked_id,
				contracts.name AS contract_name

KGB;

        $query .= $query_array['from'];
        $query .= <<<CIA
			LEFT JOIN documents ON documents.id = linked_documents.document_id
			LEFT JOIN document_revisions for_latest_revision
				ON for_latest_revision.id = documents.document_revision_id
			INNER JOIN contracts 
				ON contracts.id = linked_documents.parent_id
			LEFT JOIN document_revisions for_selected_revision 
				ON for_selected_revision.id = linked_documents.document_revision_id

CIA;
        $query .= $query_array['where'];
        return $query;
    }

    function set_notification_body($xtpl, $contract)
    {
        $xtpl->assign("CONTRACT_NAME", $contract->name);
        $xtpl->assign("CONTRACT_END_DATE", $contract->end_date);
        return $xtpl;
    }

}

/*
 * this function will return the array oof contract types for the contract types dropdown.
 * if searchID value is provided it will attempt to match and return the label of the matching key in the array
 * of contract types
 *
 * @param string $searchID value of id to search contract type array keys for
 * @param returns array of contract types or label value of matching searchID if found
 */
function getContractTypesDropDown($searchID='')
{
    static $contractTypes = null;
    if (!$contractTypes) {
        $seedContractTypes = BeanFactory::newBean('ContractTypes');
        $contractTypes = $seedContractTypes->get_contractTypes(true);
    }

    //if $search_id is a string and a key in the contract type array, then return the contract type value
    if(!empty($searchID) && is_string($searchID) && !empty($contractTypes[$searchID])) {
        return $contractTypes[$searchID];
    }
    return $contractTypes;
}

?>
