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
$viewdefs['Leads']['DetailView'] = array (
	'templateMeta' => array (
		'form' => array (
			'buttons' => array (
				'EDIT',
				'DUPLICATE',
				'DELETE',
				array (
					'customCode' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">{/if}',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'button',
                        'value' => '{$MOD.LBL_CONVERTLEAD}',
                        'htmlOptions' => array(
                            'title' => '{$MOD.LBL_CONVERTLEAD_TITLE}',
                            'accessKey' => '{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}',
                            'class' => 'button',
                            'onClick' => 'document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'',
                            'name' => 'convert',
                            'id' => 'convert_lead_button',
                        ),
                        'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
                    ),
				),
				'FIND_DUPLICATES',
				array (
					'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'submit',
                        'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                        'htmlOptions' => array(
                            'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                            'class' => 'button',
                            'id' => 'manage_subscriptions_button',
                            'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';',
                            'name' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                        )
                    )
				),
				
			),
			'headerTpl'=>'modules/Leads/tpls/DetailViewHeader.tpl',
		),
		'maxColumns' => '2',

		'useTabs' => true,
		'widths' => array (
			array (
				'label' => '10',
				'field' => '30'
			),
			array (
				'label' => '10',
				'field' => '30'
			)
		),
		 'includes'=> array(
                            array('file'=>'modules/Leads/Lead.js'),
                         ),		
	),
	'panels' => array (

	'LBL_CONTACT_INFORMATION' =>
	array (
		array (
			array (
				'name' => 'full_name',
				'label' => 'LBL_NAME',

	            'displayParams' => array (
	                'enableConnectors' => true,
	                'module' => 'Leads',
	                'connectors' => 
	                array (
	                  0 => 'ext_rest_twitter',
	                ),
	            ), 			
			),
			'phone_work',
		),

		array (
			'title',
		    'phone_mobile',   
		),			
        
		array (
			'department',
			'phone_fax'
		),					

	    array (
            array (
              'name' => 'account_name',

			    'displayParams' => array (
			       'enableConnectors' => true,
			       'module' => 'Leads',
			       'connectors' => 
			       array (
			       ),
			    ), 
            ),
			'website'
	    ),		
		
		array (
			array (
				'name' => 'primary_address_street',
				'label' => 'LBL_PRIMARY_ADDRESS',
				'type' => 'address',
				'displayParams' => array (
					'key' => 'primary'
				),
				
			),

			array (
				'name' => 'alt_address_street',
				'label' => 'LBL_ALTERNATE_ADDRESS',
				'type' => 'address',
				'displayParams' => array (
					'key' => 'alt'
				),
				
			),
			
		),

		array (
			'email',
		),			
		
		array (
			'description',
		),		
	
	),
	
	'LBL_PANEL_ADVANCED' =>
	array (
	
		array (
			'status',
		    'lead_source'	
		),

		array (
			'status_description',
			'lead_source_description',
		),	
	
		array (
			'opportunity_amount',
			'refered_by',
		),	
		
		array (
			array (
				'name' => 'campaign_name',
				'label' => 'LBL_CAMPAIGN',
				
			),
		    'do_not_call'
		)
		
	),
	
	'LBL_PANEL_ASSIGNMENT' =>
	array(
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
