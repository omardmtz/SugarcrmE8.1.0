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
/*********************************************************************************

 * Description: Holds import setting for ACT! files
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/


class ImportMapAct extends ImportMapOther
{
    /**
     * String identifier for this import
     */
    public $name = 'act';
	/**
     * Field delimiter
     */
    public $delimiter = ',';
    /**
     * Field enclosure
     */
    public $enclosure = '"';
	/**
     * Do we have a header?
     */
    public $has_header = true;

    /**
     * Gets the default mapping for a module
     *
     * @param  string $module
     * @return array field mappings
     */
	public function getMapping(
        $module
        )
    {
        $return_array = parent::getMapping($module);
        switch ($module) {
        case 'Contacts':
        case 'Leads':
            return $return_array + array(
                "Web Site"=>"website",
                "Company"=>"account_name",
                "Name Suffix"=>"salutation",
                "Address 1"=>"primary_address_street",
                "Address 2"=>"primary_address_street_2",
                "Address 3"=>"primary_address_street_3",
                "City"=>"primary_address_city",
                "State"=>"primary_address_state",
                "Zip"=>"primary_address_postalcode",
                "Country"=>"primary_address_country",
                "Phone"=>"phone_work",
                "Phone Ext-"=>"phone_work_ext",
                "Mobile Phone"=>"phone_mobile",
                "Alt Phone"=>"phone_other",
                "Fax"=>"phone_fax",
                "E-mail Login"=>"email1",
                "E-mail"=>"email1",
                "Assistant"=>"assistant",
                "Asst. Phone"=>"assistant_phone",
                "Home Address 1"=>"alt_address_street",
                "Home Address 2"=>"alt_address_street_2",
                "Home Address 3"=>"alt_address_street_3",
                "Home Zip"=>"alt_address_postalcode",
                "Home Country"=>"alt_address_country",
                "Home Phone"=>"phone_home",
                );
            break;
        case 'Accounts':
            return $return_array + array(
                "Revenue"=>"annual_revenue",
                "Number of Employees"=>"employees",
                "Address 1"=>"billing_address_street",
                "City"=>"billing_address_city",
                "State"=>"billing_address_state",
                "Zip Code"=>"billing_address_postalcode",
                "Country"=>"billing_address_country",
                "Phone"=>"phone_office",
                "Fax Phone"=>"phone_fax",
                "Ticker Symbol"=>"ticker_symbol",
                );
            break;
        default:
            return $return_array;
        }
    }
}


?>
