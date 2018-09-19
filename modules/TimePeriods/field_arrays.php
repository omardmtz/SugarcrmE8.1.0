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
$fields_array['TimePeriod'] = array ('column_fields' =>Array("id"
		,"name"
		,"start_date"
		,"end_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"parent_id"
		,"is_fiscal_year"
		),
        'list_fields' =>  Array('id', 'name', 'start_date', 'end_date', 'parent_id', 'fiscal_year','is_fiscal_year','fiscal_year_checked'),
    'required_fields' =>   array("name"=>1, "status"=>2, "date_start"=>1, "date_end"=>2),
);
?>