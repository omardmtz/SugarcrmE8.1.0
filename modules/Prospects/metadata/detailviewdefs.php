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
$viewdefs['Prospects']['DetailView'] = array(
'templateMeta' => array('form' => array('buttons' => array('EDIT', 'DUPLICATE', 'DELETE',
                                                     array('customCode' => '<input title="{$MOD.LBL_CONVERT_BUTTON_TITLE}" class="button" onclick="this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.module.value=\'Leads\';this.form.action.value=\'EditView\';" type="submit" name="CONVERT_LEAD_BTN" value="{$MOD.LBL_CONVERT_BUTTON_LABEL}"/>',
                                                         //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                                                         'sugar_html' => array(
                                                             'type' => 'submit',
                                                             'value' => '{$MOD.LBL_CONVERT_BUTTON_LABEL}',
                                                             'htmlOptions' => array(
                                                                 'class' => 'button',
                                                                 'name' => 'CONVERT_LEAD_BTN',
                                                                 'id' => 'convert_target_button',
                                                                 'title' => '{$MOD.LBL_CONVERT_BUTTON_TITLE}',
                                                                 'onclick' => 'this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.module.value=\'Leads\';this.form.action.value=\'EditView\';',
                                                             ),
                                                         )
                                                     ),
                                                     array('customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}"/>',
                                                         //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                                                         'sugar_html' => array(
                                                             'type' => 'submit',
                                                             'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                                                             'htmlOptions' => array(
                                                                 'class' => 'button',
                                                                 'id' => 'manage_subscriptions_button',
                                                                 'name' => 'Manage Subscriptions',
                                                                 'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                                                                 'onclick' => 'this.form.return_module.value=\'Prospects\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\';',
                                                             ),
                                                         )
                                                     ),
                                       ),
                                        'hidden'=>array('<input type="hidden" name="prospect_id" value="{$fields.id.value}">'),
                        				'headerTpl'=>'modules/Prospects/tpls/DetailViewHeader.tpl',
                        ),
                        'maxColumns' => '2',

                        'useTabs' => true,
                        'widths' => array(
                                        array('label' => '10', 'field' => '30'), 
                                        array('label' => '10', 'field' => '30')
                                        ),
                        ),
'panels' =>array (
  'lbl_prospect_information' => array(
  array (
    array (
    'name'=>'full_name',

    'displayParams' => array (
        'enableConnectors' => true,
        'module' => 'Prospects',
        'connectors' => 
        array (
            0 => 'ext_rest_twitter',
        ),
    ), 	    
    ),
  ),

  array (
    'title',
    array (
      'name' => 'phone_work',
      'label' => 'LBL_OFFICE_PHONE',
    ),
  ),
  
  array (
    'department',
    'phone_mobile',
  ),
  
  array (
    array (
        'name' => 'account_name',

	    'displayParams' => array (
	       'enableConnectors' => true,
	       'module' => 'Prospects',
	       'connectors' => 
	       array (
	       ),
	    ), 
    ),  
  	'phone_fax',
  ),
  
  array (
      array (
	      'name' => 'primary_address_street',
	      'label'=> 'LBL_PRIMARY_ADDRESS',
	      'type' => 'address',
	      'displayParams'=>array('key'=>'primary'),
      ),
      
      array (
	      'name' => 'alt_address_street',
	      'label'=> 'LBL_ALTERNATE_ADDRESS',
	      'type' => 'address',
	      'displayParams'=>array('key'=>'alt'),      
      ),
  ),
  
  array (
    'email1',
  ),
  
  array (
    'description',
  ),
  
  ),
  'LBL_MORE_INFORMATION' => array(
    array (
    'email_opt_out',
    'do_not_call',
  ),
    ),
  'LBL_PANEL_ASSIGNMENT' => array(
  array (
      'assigned_user_name',
    array (
      'name' => 'modified_by_name',
      'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}&nbsp;',
      'label' => 'LBL_DATE_MODIFIED',
    ),
  ),
  
  array (

		'team_name',
    array (
      'name' => 'created_by_name',
      'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}&nbsp;',
      'label' => 'LBL_DATE_ENTERED',
    ),
  ),
  ),
  
  
  
  
)


   
);
?>
