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

class SugarWidgetFieldTime extends SugarWidgetFieldDateTime
{
        function displayList($layout_def)
        {
                global $timedate;
                // i guess qualifier and column_function are the same..
                if (! empty($layout_def['column_function']))
                 {
                        $func_name = 'displayList'.$layout_def['column_function'];
                        if ( method_exists($this,$func_name))
                        {
                                return $this->$func_name($layout_def)." \n";
                        }
                }
                
                // Get the date context of the time, important for DST
                $layout_def_date = $layout_def;
                $layout_def_date['name'] = str_replace('time', 'date', $layout_def_date['name']);
                $date = $this->displayListPlain($layout_def_date);
                
                $content = $this->displayListPlain($layout_def);
                
                if(!empty($date)) { // able to get the date context of the time            	
                	$td = explode(' ', $timedate->to_display_date_time($date . ' ' . $content));
	                return $td[1];
                }
                else { // assume there is no time context
                 	return $timedate->to_display_time($content);
                }
        }
}

?>
