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
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */


class pmse_BpmFlow extends pmse_BpmFlow_sugar {


	public function __construct(){
		parent::__construct();
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':
				return true;
		}
		return false;
	}

    public function getACLCategory()
    {
        return 'pmse_Inbox';
    }

	/**
	 * @inheritDoc
	 */
	public function ACLAccess($view, $context = null)
	{
		switch ($view) {
			case 'list':
				if (is_array($context)
					&& isset($context['source'])
					&& $context['source'] === 'filter_api') {
					return false;
				}
				break;
			case 'edit':
			case 'view':
				if (is_array($context)
					&& isset($context['source'])
					&& $context['source'] === 'module_api') {
					return false;
				}
				break;
		}
		return parent::ACLAccess($view, $context);
	}

    /**
     * {@inheritDoc}
     */
    public function populateFromRow(array $row, $convert = false)
    {
        // Done here since assignment of this field could happen from anywhere
        // and assigned_user_id on some DBs is char whereas on others it is varchar
        if (isset($row['cas_user_id'])) {
            $row['cas_user_id'] = $this->db->fromConvert($row['cas_user_id'], 'id');
        }

        return parent::populateFromRow($row, $convert);
    }

    /**
    * @inheritDoc
    */
    public function save($check_notify = false)
    {
        // Because cas_user_id is sometimes set from assigned_user_id, and because
        // assigned_user_id varies in type based on DB, we need to make sure that
        // we always have a clean, consistent value for cas_user_id.
        if (isset($this->cas_user_id)) {
            $this->cas_user_id = $this->db->fromConvert($this->cas_user_id, 'id');
        }

        return parent::save($check_notify);
    }
}
