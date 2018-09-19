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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$module_name = '<module_name>';
$_object_name = '<_object_name>';
$viewdefs[$module_name]['QuickCreate'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
    'javascript' => '{$PROBABILITY_SCRIPT}',
),
 'panels' =>array (
  'lbl_sale_information' =>array (
  	array(
		'name',
		array('name'=>'assigned_user_name','displayParams'=>array('required'=>true)),
	),
	
    array(
		'amount',
		array('name'=>'team_name','displayParams'=>array('required'=>true)),
	),
	
	array($_object_name.'_type', 'date_closed'),
	
    array('lead_source',array('name'=>'sales_stage', 'displayParams'=>array('required'=>true))),

    array (
      'next_step',
      'probability'
    ),
    
    array (
      'description',''
    ),
  ),
)

);
