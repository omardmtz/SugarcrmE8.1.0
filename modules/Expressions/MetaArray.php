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
global $selector_meta_array;
$selector_meta_array = Array(

'normal_trigger' => Array(
				'enum_multi' => true
				,'time_type' => false
				,'select_field' => false
			),
'time_trigger' => Array(
				'enum_multi' => true
				,'time_type' => true
				,'select_field' => false
			),
'alert_filter' => Array(
				'enum_multi' => false
				,'time_type' => false
				,'select_field' => true
			),
'count_trigger' => Array(
				'enum_multi' => false
				,'time_type' => false
				,'select_field' => true
			),									
);
?>