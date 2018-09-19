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


class Person extends Basic
{
    public $picture;

    /**
     * Email address relationship
     * @var Link2
     */
    public $email_addresses;

    public function __construct()
    {
        parent::__construct();
        $this->emailAddress = BeanFactory::newBean('EmailAddresses');
    }

    /**
     * Populate email address fields here instead of retrieve() so that they are
     * properly available for logic hooks
     *
     * @see parent::fill_in_relationship_fields()
     */
    public function fill_in_relationship_fields()
    {
        parent::fill_in_relationship_fields();
        $this->emailAddress->handleLegacyRetrieve($this);
    }

    /**
     * This function helps generate the name and full_name member field variables from the salutation, title, first_name and last_name fields.
     * It takes into account the locale format settings as well as ACL settings if supported.
     */
    public function _create_proper_name_field()
    {
        $this->name = $this->full_name = $this->getRecordName();
    }

    /**
     * @inheritDoc
     */
    public function save($check_notify = false)
    {
        // If we are saving due to relationship changes, don't bother trying to
        // update the emails
        if (static::inOperation('saving_related')) {
            // In workflow, it is possible to trigger a relationship save from a
            // relationship save (for example, when creating a contact from an
            // Opportunity when the sales status is Closed Won, but was set that
            // way from an RLI status change). This fixes that.
            if ($this->in_workflow) {
                // If there was a newly created related record, save that relationship
                // here since it will get skipped later on because of the opStage
                // containing 'saving_related'. But set an id so that relationship
                // changes will actually stick
                if (empty($this->id)) {
                    $this->id = create_guid();
                    $this->new_with_id = true;
                }
                $this->save_relationship_changes($this->isUpdate());
            }

            parent::save($check_notify);
            return $this->id;
        }
        $this->add_address_streets('primary_address_street');
        $this->add_address_streets('alt_address_street');
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
            $this->emailAddress->save($this->id, $this->module_dir, $override_email, '', '', '', '', $this->in_workflow);
        }
        $this->_create_proper_name_field();
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function get_summary_text()
    {
        $this->_create_proper_name_field();
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function get_list_view_data()
    {
        global $system_config;
        global $current_user;

        $this->_create_proper_name_field();
        $temp_array = $this->get_list_view_array();

        $temp_array['NAME'] = $this->name;
        $temp_array["ENCODED_NAME"] = $this->full_name;
        $temp_array["FULL_NAME"] = $this->full_name;

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

    /**
     * @inheritDoc
     */
    public function populateRelatedBean(SugarBean $newbean)
    {
        parent::populateRelatedBean($newbean);

        if ($newbean instanceof Company) {
            $newbean->phone_fax = $this->phone_fax;
            $newbean->phone_office = $this->phone_work;
            $newbean->phone_alternate = $this->phone_other;
            $newbean->email1 = $this->email1;
            $this->add_address_streets('primary_address_street');
            $newbean->billing_address_street = $this->primary_address_street;
            $newbean->billing_address_city = $this->primary_address_city;
            $newbean->billing_address_state = $this->primary_address_state;
            $newbean->billing_address_postalcode = $this->primary_address_postalcode;
            $newbean->billing_address_country = $this->primary_address_country;
            $this->add_address_streets('alt_address_street');
            $newbean->shipping_address_street = $this->alt_address_street;
            $newbean->shipping_address_city = $this->alt_address_city;
            $newbean->shipping_address_state = $this->alt_address_state;
            $newbean->shipping_address_postalcode = $this->alt_address_postalcode;
            $newbean->shipping_address_country = $this->alt_address_country;
        }
    }

    /**
     * Retrieve a list of this person's calendar event start and end times ordered by start datetime
     * @return array
     */
    public function getFreeBusySchedule(array $options = array())
    {
        global $timedate;
        $lines = $this->getVCalData($options);

        $utc = new DateTimeZone("UTC");

        $activities = array();
        foreach ($lines as $line) {
            if (preg_match('/^FREEBUSY.*?:([^\/]+)\/([^\/]+)/i', $line, $matches)) {
                $datesArray = array(
                    SugarDateTime::createFromFormat(vCal::UTC_FORMAT, $matches[1], $utc),
                    SugarDateTime::createFromFormat(vCal::UTC_FORMAT, $matches[2], $utc)
                );
                $act = new CalendarActivity($datesArray);
                $startTime = $timedate->asIso($act->start_time);
                $endTime = $timedate->asIso($act->end_time);
                $activities[$startTime] = array(
                    "start" => $startTime,
                    "end" => $endTime,
                );
            }
        }
        ksort($activities); // order by start date
        $freeBusySchedule = array();
        foreach ($activities AS $startDate => $act) {
            $freeBusySchedule[] = $act;
        }

        return $freeBusySchedule;
    }

    /**
     * Gets VCal data
     * @param array $options
     */
    protected function getVCalData(array $options)
    {
        global $timedate, $sugar_config;

        //--- Explicit config can be used to force use of vCal Cache instead of RealTime Search
        $useFreeBusyCache = !empty($sugar_config['freebusy_use_vcal_cache']);

        $vcalBean = BeanFactory::newBean('vCals');
        if (!$useFreeBusyCache && !empty($options['start']) && !empty($options['end'])) {
            $sugarDateTimeStart = $timedate->fromIso($options['start']);
            $sugarDateTimeEnd = $timedate->fromIso($options['end']);
            $vcalData = $vcalBean->get_vcal_freebusy($this, false, $sugarDateTimeStart, $sugarDateTimeEnd);
        } else {
            $vcalData = $vcalBean->get_vcal_freebusy($this, true);
        }

        $vcalData = str_replace("\r\n", "\n", $vcalData);
        $lines = explode("\n", $vcalData);
        return $lines;
    }

    /**
     * Gets a Localization object
     * @return Localization
     */
    protected function getLocaleObject()
    {
        global $locale;
        return $locale;
    }

    /**
     * @inheritDoc
     */
    public function getRecordName()
    {
        return $this->getLocaleObject()->formatName($this);
    }
}
