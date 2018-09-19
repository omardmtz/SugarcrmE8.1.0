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

 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['DataSet'] = array ('column_fields' => Array("id"
		,"name"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"created_by"
		,"team_id"
		,"description"
		,"parent_id"
		,"query_id"
		,"list_order_y"
		,"table_width"
		,"font_size"
		,"header"
		,"exportable"
		,"output_default"
		,"report_id"
		,"prespace_y"
		,"table_width_type"
		,"body_text_color"
		,"header_text_color"
		,"use_prev_header"
		,"header_back_color"
		,"body_back_color"
		,"custom_layout"
		),
        'list_fields' =>  array('id', 'name', 'output_default', 'list_order_y', 'visible', 'exportable','query_id', 'report_id'),
        'required_fields' => array("name"=>1, 'list_order_y'=>1, 'query_id'=>1,),
);
?>