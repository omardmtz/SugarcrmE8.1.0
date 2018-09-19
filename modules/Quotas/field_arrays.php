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
$fields_array['Quota'] = array ('column_fields' => Array("id"
		,"user_id"
		,"timeperiod_id"
		,"quota_type"
		,"amount"
		,"amount_base_currency"
		,"currency_id"
        ,"committed"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"created_by"
		),
        'list_fields' =>  Array('id'),
    'required_fields' =>  array("user_id"=>1, "amount"=>2, "currency_id"=>3),
);
?>