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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $genericAssocFieldsArray;
global $moduleAssocFieldsArray;

$genericAssocFieldsArray = array('assigned_user_id' =>
                                array('table_name' => 'users',
                                    'select_field_name' => 'user_name',
                                    'select_field_join'  => 'id',
                                ),
                                'team_id' =>
                                    array('table_name' => 'teams',
                                    'select_field_name' => 'name',
                                    'select_field_join'  => 'id',
                                  ),
                                  'account_id' =>
                                  array('table_name' => 'accounts',
                                    'select_field_name' => 'name',
                                    'select_field_join'  => 'id',
                                  ), 
                                  'contact_id' =>
                                  array('table_name' => 'contacts',
                                    'select_field_name' => 
                                    		array('first_name',
                                    			  'last_name',
                                    		),
                                    'select_field_join'  => 'id',
                                  ),
                                  'fixed_in_release' =>
                                  array('table_name' => 'releases',
                                    'select_field_name' => 'name',
                                    'select_field_join'  => 'id',
                                  ), 
                                  'found_in_release' =>
                                  array('table_name' => 'releases',
                                    'select_field_name' => 'name',
                                    'select_field_join'  => 'id',
                                  ),                                   
                            );
$moduleAssocFieldsArray = array(
    'Account' => array(
        'parent_id' => array(
            'table_name' => 'accounts',
            'select_field_name' => 'name',
            'select_field_join' => 'id',
        ),
    ),
    'KBContent' => array(
        'kbarticle_id' => array(
            'table_name' => 'kbarticles',
            'select_field_name' => 'name',
            'select_field_join' => 'id',
        ),
        'category_id' => array(
            'table_name' => 'categories',
            'select_field_name' => 'name',
            'select_field_join' => 'id',
        ),
        'kbscase_id' => array(
            'table_name' => 'cases',
            'select_field_name' => 'name',
            'select_field_join' => 'id',
        ),
        'kbsapprover_id' => array(
            'table_name' => 'users',
            'select_field_name' => 'user_name',
            'select_field_join' => 'id',
        ),
    ),
);

?>