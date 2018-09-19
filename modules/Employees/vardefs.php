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
global $dictionary;
if(empty($dictionary['User'])){
	include('modules/Users/vardefs.php');
}
$dictionary['Employee']=$dictionary['User'];

// Enable the employee module for full text search
$dictionary['Employee']['full_text_search'] = true;

// Enable the searchable fields for full text search
$dictionary['Employee']['fields']['first_name']['full_text_search'] = array(
    'enabled' => true,
    'searchable' => true,
    'boost' => 1.71,
);
$dictionary['Employee']['fields']['last_name']['full_text_search'] = array(
    'enabled' => true,
    'searchable' => true,
    'boost' => 1.69,
);
$dictionary['Employee']['fields']['email']['full_text_search']['boost'] = 1.67;

$dictionary['Employee']['fields']['date_entered']['full_text_search'] = array(
    'enabled' => true,
    'searchable' => false,
    // Disabled until UX component is available
    //'aggregations' => array(
    //    'date_entered' => array(
    //        'type' => 'DateRange',
    //    ),
    //),
);

$dictionary['Employee']['fields']['date_modified']['full_text_search'] = array(
    'enabled' => true,
    'searchable' => false,
    // Disabled until UX component is available
    //'aggregations' => array(
    //    'date_modified' => array(
    //        'type' => 'DateRange',
    //    ),
    //),
);

$dictionary['Employee']['fields']['modified_user_id']['full_text_search'] = array(
    'enabled' => true,
    'searchable' => false,
    'type' => 'id',
    'aggregations' => array(
        'modified_user_id' => array(
            'type' => 'MyItems',
            'label' => 'LBL_AGG_MODIFIED_BY_ME',
        ),
    ),
);

$dictionary['Employee']['fields']['created_by']['full_text_search'] = array(
    'enabled' => true,
    'searchable' => false,
    'type' => 'id',
    'aggregations' => array(
        'created_by' => array(
            'type' => 'MyItems',
            'label' => 'LBL_AGG_CREATED_BY_ME',
        ),
    ),
);

//users of employees modules are not allowed to change the employee/user status.
$dictionary['Employee']['fields']['status']['massupdate']=false;
$dictionary['Employee']['fields']['is_admin']['massupdate']=false;
//begin bug 48033
$dictionary['Employee']['fields']['UserType']['massupdate']=false;
$dictionary['Employee']['fields']['messenger_type']['massupdate']=false;
$dictionary['Employee']['fields']['email_link_type']['massupdate']=false;
//end bug 48033
$dictionary['Employee']['fields']['email']['required']=true;
$dictionary['Employee']['fields']['email_addresses']['required']=false;
$dictionary['Employee']['fields']['email_addresses_primary']['required']=false;
// bugs 47553 & 49716
$dictionary['Employee']['fields']['status']['studio']=false;
$dictionary['Employee']['fields']['status']['required']=false;

$dictionary['Employee']['fields']['created_by_link']['relationship'] = 'employees_created_by';

$dictionary['Employee']['relationships']['employees_created_by'] = $dictionary['User']['relationships']['users_created_by'];
$dictionary['Employee']['relationships']['employees_created_by']['lhs_module'] = 'Employees';
$dictionary['Employee']['hidden_to_role_assignment'] = true;
