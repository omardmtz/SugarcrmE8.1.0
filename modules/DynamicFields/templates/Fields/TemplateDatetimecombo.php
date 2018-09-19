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


class TemplateDatetimecombo extends TemplateRange 
{
	var $type = 'datetimecombo';
	var $len = '';
    var $massupdate = 1;
	var $dateStrings = array(
		'-none-' => '',
        'today'=>'now',
        'yesterday'=> '-1 day',
        'tomorrow'=>'+1 day',
        'next week'=> '+1 week',
        'next monday'=>'next monday',
        'next friday'=>'next friday',
        'two weeks'=> '+2 weeks',
        'next month'=> '+1 month',
        'first day of next month'=> 'first day of next month', // must handle this non-GNU date string in SugarBean->populateDefaultValues; if we don't this will evaluate to 1969...
        'three months'=> '+3 months',  //kbrill Bug #17023
        'six months'=> '+6 months',
        'next year'=> '+1 year',
    );
    
    var $hoursStrings = array(
    	'' => '',
    	'01' => '01',	
    	'02' => '02',
    	'03' => '03',
    	'04' => '04',
    	'05' => '05',
    	'06' => '06',
    	'07' => '07',
    	'08' => '08',
    	'09' => '09',
    	'10' => '10',
    	'11' => '11',
    	'12' => '12',
    );
    
    var $hoursStrings24 = array(
    	'' => '',
        '00' => '00',
    	'01' => '01',	
    	'02' => '02',
    	'03' => '03',
    	'04' => '04',
    	'05' => '05',
    	'06' => '06',
    	'07' => '07',
    	'08' => '08',
    	'09' => '09',
    	'10' => '10',
    	'11' => '11',
    	'12' => '12',
    	'13' => '13',	
    	'14' => '14',
    	'15' => '15',
    	'16' => '16',
    	'17' => '17',
    	'18' => '18',
    	'19' => '19',
    	'20' => '20',
    	'21' => '21',
    	'22' => '22',
    	'23' => '23',
    );    
    
    var $minutesStrings = array(
    	'' => '',
    	'00' => '00',	
    	'15' => '15',
    	'30' => '30',
    	'45' => '45',
    );
    
    var $meridiemStrings = array(
    	'' => '',
    	'am' => 'am',
    	'pm' => 'pm',
    );

	function get_db_default($modify=false){
			return '';
	}

    public function get_field_def()
    {
        $def = parent::get_field_def();
        $def['dbType'] = 'datetime';

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
        $defaultDate = $request->getValidInputRequest('defaultDate');
        $defaultTime = $request->getValidInputRequest('defaultTime');
        if ($defaultDate && $defaultTime) {
            $default = $defaultDate . '&' . $defaultTime;
            $hours = substr($defaultTime, 0, 2);
			$minutes = substr($defaultTime, 3, 2);
			$meridiem = substr($defaultTime, 5, 2);
    		if(empty($meridiem)) {
  		      if($hours == '00') {
  		      	 $hours = 12;
  		      	 $meridiem = 'am';
  		      } else if($hours >= 12) {
                 //lets add the PM meridiem, but only subtract 12 if hours is greater than 12
  		      	 if($hours > 12) {
                    $hours -= 12;
                 }
  		      	 $meridiem = 'pm';
  		      } else {
  		      	 $meridiem = 'am';
  		      }
  		      //lets format the string to make sure the leading 0's are added back in for hours and minutes
              $default = $defaultDate . '&' . sprintf('%02d:%02d%s', $hours, $minutes, $meridiem);
    		}
    	}else{
            $default = '';
    	}

        foreach ($this->vardef_map as $vardef => $field) {
            $value = $request->getValidInputRequest($vardef);
            if ($value !== null) {
                // Bug #48826. Some fields are allowed to have special characters and must be decoded from the request
                // Bug 49774, 49775: Strip html tags from 'formula' and 'dependency'.
                if (is_string($value) && in_array($vardef, $this->decode_from_request_fields_map)) {
                    $this->$vardef = strip_tags($value);
                } else {
                    $this->$vardef = $value;
                }

				if($vardef != $field){
					$this->$field = $this->$vardef;
				}
			}
		}
        $this->default = $default;
		$GLOBALS['log']->debug('populate: '.print_r($this,true));
	}
	
}
