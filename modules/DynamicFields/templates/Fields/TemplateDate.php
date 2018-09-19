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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;


class TemplateDate extends TemplateRange
{
	var $type = 'date';
	var $len = '';
	var $dateStrings;
    var $massupdate = 1;

function __construct() {
	parent::__construct();
	global $app_strings;
	$this->dateStrings = array(
			$app_strings['LBL_NONE']=>'',
            $app_strings['LBL_YESTERDAY']=> '-1 day',
            $app_strings['LBL_TODAY']=>'now',
            $app_strings['LBL_TOMORROW']=>'+1 day',
            $app_strings['LBL_NEXT_WEEK']=> '+1 week',
            $app_strings['LBL_NEXT_MONDAY']=>'next monday',
            $app_strings['LBL_NEXT_FRIDAY']=>'next friday',
            $app_strings['LBL_TWO_WEEKS']=> '+2 weeks',
            $app_strings['LBL_NEXT_MONTH']=> '+1 month',
            $app_strings['LBL_FIRST_DAY_OF_NEXT_MONTH']=> 'first day of next month', // must handle this non-GNU date string in SugarBean->populateDefaultValues; if we don't this will evaluate to 1969...
            $app_strings['LBL_THREE_MONTHS']=> '+3 months',  //kbrill Bug #17023
            $app_strings['LBL_SIXMONTHS']=> '+6 months',
            $app_strings['LBL_NEXT_YEAR']=> '+1 year',
        );
}


function get_db_default($modify=false){
		return '';
}

//BEGIN BACKWARDS COMPATIBILITY
function get_xtpl_edit(){
		global $timedate;
		$name = $this->name;
		$returnXTPL = array();
		if(!empty($this->help)){
		    $returnXTPL[strtoupper($this->name . '_help')] = translate($this->help, $this->bean->module_dir);
		}
		$returnXTPL['USER_DATEFORMAT'] = $timedate->get_user_date_format();
		$returnXTPL['CALENDAR_DATEFORMAT'] = $timedate->get_cal_date_format();
		if(isset($this->bean->$name)){
			$returnXTPL[strtoupper($this->name)] = $this->bean->$name;
		}else{
		    if(empty($this->bean->id) && !empty($this->default_value) && !empty($this->dateStrings[$this->default_value])){
		        $returnXTPL[strtoupper($this->name)] = $timedate->asUserDate($timedate->getNow(true)->modify($this->dateStrings[$this->default_value]), false);
		    }
		}
		return $returnXTPL;
	}

    public function get_field_def()
    {
        $def = parent::get_field_def();

        if (!empty($def['default'])) {
            $def['display_default'] = $def['default'];
        }

        unset($def['default']);
        return $def;
    }

    public function populateFromPost(Request $request = null)
    {
        if (!$request) {
            $request = InputValidation::getService();
        }

        parent::populateFromPost($request);
        // Handle empty massupdate checkboxes
        $this->massupdate = (bool) $request->getValidInputRequest('massupdate');
    }
}
