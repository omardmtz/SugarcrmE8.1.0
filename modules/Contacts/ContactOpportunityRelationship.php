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

// Contact is used to store customer information.
class ContactOpportunityRelationship extends SugarBean {
	// Stored fields
	var $id;
	var $contact_id;
	var $contact_role;
	var $opportunity_id;

	// Related fields
	var $contact_name;
	var $opportunity_name;

	var $table_name = "opportunities_contacts";
	var $object_name = "ContactOpportunityRelationship";
	var $column_fields = Array("id"
		,"contact_id"
		,"opportunity_id"
		,"contact_role"
		,'date_modified'
		);

	var $new_schema = true;
	
	var $additional_column_fields = Array();
		var $field_defs = array (
       'id'=>array('name' =>'id', 'type' =>'char', 'len'=>'36', 'default'=>'')
      , 'contact_id'=>array('name' =>'contact_id', 'type' =>'char', 'len'=>'36', )
      , 'opportunity_id'=>array('name' =>'opportunity_id', 'type' =>'char', 'len'=>'36',)
      , 'contact_role'=>array('name' =>'contact_role', 'type' =>'char', 'len'=>'50')
      , 'date_modified'=>array ('name' => 'date_modified','type' => 'datetime')
      , 'deleted'=>array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
      );


	public function __construct() {
		$this->db = DBManagerFactory::getInstance();
        $this->dbManager = DBManagerFactory::getInstance();

		$this->disable_row_level_security =true;

		}

	function fill_in_additional_detail_fields()
	{
		global $locale;
		if(isset($this->contact_id) && $this->contact_id != "")
		{
                $query = sprintf(
                    'SELECT first_name, last_name FROM contacts WHERE id = %s AND deleted = 0',
                    $this->db->quoted($this->contact_id)
                );
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
                $this->contact_name = $locale->formatName('Contacts', $row);
			}
		}

		if(isset($this->opportunity_id) && $this->opportunity_id != "")
		{
                $query = sprintf(
                    'SELECT name FROM opportunities WHERE id = %s AND deleted = 0',
                    $this->db->quoted($this->opportunity_id)
                );
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");
			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->opportunity_name = $row['name'];
			}
		}

	}
}



?>
