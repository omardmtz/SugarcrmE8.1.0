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

class Company extends Basic
{


    /**
 	 * Constructor
 	 */
    public function __construct()
 	{
 		parent::__construct();
 		$this->emailAddress = BeanFactory::newBean('EmailAddresses');
 	}

    /**
     *
     * @see parent::save()
     */
    public function save($check_notify = false)
    {
        if (static::inOperation('saving_related')) {
            parent::save($check_notify);
            return $this;
        }
        $this->add_address_streets('billing_address_street');
        $this->add_address_streets('shipping_address_street');
        $ori_in_workflow = empty($this->in_workflow) ? false : true;
        $this->emailAddress->handleLegacySave($this, $this->module_dir);
        parent::save($check_notify);
        $override_email = array();
        if (!empty($this->email1_set_in_workflow)) {
            $override_email['emailAddress0'] = $this->email1_set_in_workflow;
        }
        if (!empty($this->email2_set_in_workflow)) {
            $override_email['emailAddress1'] = $this->email2_set_in_workflow;
        }
        if (!isset($this->in_workflow)) {
            $this->in_workflow = false;
        }
        if ($ori_in_workflow === false || !empty($override_email)) {
            $this->emailAddress->save($this->id, $this->module_dir, $override_email, '', '', '', '',
                $this->in_workflow);
        }
        return $this;
    }

 	/**
 	 * Populate email address fields here instead of retrieve() so that they are properly available for logic hooks
 	 *
 	 * @see parent::fill_in_relationship_fields()
 	 */
	public function fill_in_relationship_fields()
	{
	    parent::fill_in_relationship_fields();
	    $this->emailAddress->handleLegacyRetrieve($this);
	}

	/**
 	 * @see parent::get_list_view_data()
 	 */
	public function get_list_view_data()
	{
        global $system_config;
		global $current_user;

		$temp_array = $this->get_list_view_array();

		$temp_array['EMAIL'] = $this->emailAddress->getPrimaryAddress($this);

        // Fill in the email1 field only if the user has access to it
        // This is a special case, because getEmailLink() uses email1 field for making the link
        // Otherwise get_list_view_data() shouldn't set any fields except fill the template data
        if ($this->ACLFieldAccess('email1', 'read')) {
            $this->email1 = $temp_array['EMAIL'];
        }

		$temp_array['EMAIL_LINK'] = $current_user->getEmailLink('email1', $this, '', '', 'ListView');

		return $temp_array;
	}
}
