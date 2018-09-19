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

class EmailMarketing extends SugarBean
{
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $name;
	var $from_addr;
	var $from_name;
	var $reply_to_name;
	var $reply_to_addr;
	var $date_start;
	var $time_start;
	var $template_id;
	var $campaign_id;
	var $all_prospect_lists;
	var $status;
	var $inbound_email_id;

	var $table_name = 'email_marketing';
	var $object_name = 'EmailMarketing';
	var $module_dir = 'EmailMarketing';

	var $new_schema = true;


	public function __construct()
	{
		parent::__construct();

		$this->disable_row_level_security=true;


	}

    public function retrieve($id = '-1', $encode = true, $deleted = true)
    {
	    parent::retrieve($id,$encode,$deleted);

        global $timedate;
        $date_start_array=explode(" ",trim($this->date_start));
        if (count($date_start_array)==2) {
			$this->time_start = $date_start_array[1];
        	$this->date_start = $date_start_array[0];
        }
        return $this;
	}

	function get_summary_text()
	{
		return $this->name;
	}

	function get_list_view_data(){

		$temp_array = $this->get_list_view_array();

		$id = $temp_array['ID'];
		$template_id = $temp_array['TEMPLATE_ID'];

		//mode is set by schedule.php from campaigns module.
		if (!isset($this->mode) or empty($this->mode) or $this->mode!='test') {
			$this->mode='rest';
		}

		if ($temp_array['ALL_PROSPECT_LISTS']==1) {
			$query="SELECT name from prospect_lists ";
			$query.=" INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = prospect_lists.id";
			$query.=" WHERE plc.campaign_id='{$temp_array['CAMPAIGN_ID']}'";
			$query.=" AND prospect_lists.deleted=0";
			$query.=" AND plc.deleted=0";
			if ($this->mode=='test') {
				$query.=" AND prospect_lists.list_type='test'";
			} else {
				$query.=" AND prospect_lists.list_type!='test'";
			}
		} else {
			$query="SELECT name from prospect_lists ";
			$query.=" INNER JOIN email_marketing_prospect_lists empl ON empl.prospect_list_id = prospect_lists.id";
			$query.=" WHERE empl.email_marketing_id='{$id}'";
			$query.=" AND prospect_lists.deleted=0";
			$query.=" AND empl.deleted=0";
			if ($this->mode=='test') {
				$query.=" AND prospect_lists.list_type='test'";
			} else {
				$query.=" AND prospect_lists.list_type!='test'";
			}
		}
		$res = $this->db->query($query);
		while (($row = $this->db->fetchByAssoc($res)) != null) {
			if (!empty($temp_array['PROSPECT_LIST_NAME'])) {
				$temp_array['PROSPECT_LIST_NAME'].="<BR>";
			}
			$temp_array['PROSPECT_LIST_NAME'].=$row['name'];
		}
		return $temp_array;
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}

	function get_all_prospect_lists() {

		$query="select prospect_lists.* from prospect_lists ";
		$query.=" left join prospect_list_campaigns on prospect_list_campaigns.prospect_list_id=prospect_lists.id";
		$query.=" where prospect_list_campaigns.deleted=0";
		$query.=" and prospect_list_campaigns.campaign_id='$this->campaign_id'";
		$query.=" and prospect_lists.deleted=0";
		$query.=" and prospect_lists.list_type not like 'exempt%'";

		return $query;
	}
}
