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
$viewdefs['Opportunities']['DetailView'] = array(
    'templateMeta' => array('form' => array('buttons'=>array('EDIT', 'DUPLICATE', 'DELETE', 'FIND_DUPLICATES',)),
       						'maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
                           ),
    'panels' => array(                           
        'default' => array (
	        array('name',
	              'account_name', 
	        ),
	        
	        array(
	        	array('name'=>'amount','label' => '{$MOD.LBL_AMOUNT} ({$CURRENCY})'),
	        	'date_closed',
	        ),

            array(
                'best_case',
                'worst_case',
            ),

	        array (
	        	'sales_stage',
	        	'opportunity_type'
	        ),
	        
	        array(
	        	'probability',
	        	'lead_source',
	            
	            
	        ),  
	        
	        array (
	        	'next_step',
	            'campaign_name'
	        ),
	        array(
	            array(
	               'name'=>'description',
	               'nl2br'=>true
	            )
	        )
        ),
        
        'LBL_PANEL_ASSIGNMENT' => array(
	        array (
	          array (
	            'name' => 'assigned_user_name',
	            'label' => 'LBL_ASSIGNED_TO',
	          ),
	          array (
	            'name' => 'date_modified',
	            'label' => 'LBL_DATE_MODIFIED',
	            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
	          ),
	        ),
	        array (
			  'team_name',
	          array (
	            'name' => 'date_entered',
	            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
	          ),
	        ),	     
        ),

    )
);
?>