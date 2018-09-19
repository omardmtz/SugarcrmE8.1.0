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

$viewdefs['Project']['QuickCreate'] = array(
					'templateMeta' => array('maxColumns' => '2', 
                        'widths' => array(
                                        array('label' => '10', 'field' => '30'), 
                                        array('label' => '10', 'field' => '30')
                                        ),
                       ),
'panels' =>

array (
  
  array (
    'name',
    'status'
  ),
  
  array (
    'estimated_start_date',
    'estimated_end_date'
  ),
  
  array('priority',),
  array('assigned_user_name',
	  'team_name',
  ),
  array (
    array (
      'name' => 'description',
    ),
  ),

),

);
?>
