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


class SugarFieldTime extends SugarFieldBase {

    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        // Create Smarty variables for the Calendar picker widget
        if(!isset($displayParams['showMinutesDropdown'])) {
           $displayParams['showMinutesDropdown'] = false;
        }

        if(!isset($displayParams['showHoursDropdown'])) {
           $displayParams['showHoursDropdown'] = false;
        }

        if(!isset($displayParams['showFormats'])) {
           $displayParams['showFormats'] = false;
        }

        global $timedate;

        $displayParams['timeFormat'] = $timedate->get_user_time_format();
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch('include/SugarFields/Fields/Time/EditView.tpl');
    }

    function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    	// Create Smarty variables for the Calendar picker widget
        if(!isset($displayParams['showMinutesDropdown'])) {
           $displayParams['showMinutesDropdown'] = false;
        }

        if(!isset($displayParams['showHoursDropdown'])) {
           $displayParams['showHoursDropdown'] = false;
        }

        if(!isset($displayParams['showFormats'])) {
           $displayParams['showFormats'] = false;
        }

        global $timedate;

        $displayParams['timeFormat'] = $timedate->get_user_time_format();
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch('include/SugarFields/Fields/Time/SearchView.tpl');
    }

    public function save($bean, $params, $field, $properties, $prefix = '')
    {
        if ( !isset($inputData[$prefix.$field]) ) {
            $bean->$field = '';
            return;
        }
        $bean->$field = $this->convertFieldForDB($inputData[$prefix.$field]);
    }

    /**
     * Convert a field for a DB
     * @param string $value time
     * @return string
     */
    public function convertFieldForDB($value)
    {
        $timedate = TimeDate::getInstance();
        return $timedate->to_db_time($value, false);
    }
}
