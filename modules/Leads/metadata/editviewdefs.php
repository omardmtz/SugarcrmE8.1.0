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
$viewdefs['Leads']['EditView'] = array(
    'templateMeta' => array('form' => array('hidden'=>array('<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
                                            				'<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
                                            				'<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
                                            				'<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">'),
                                            'buttons' => array(
															'SAVE',
															'CANCEL',
											)                				
                            ),
                            'maxColumns' => '2', 
                            'useTabs' => true,
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                           ),
 'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>',
),
 'panels' =>array (
  'LBL_CONTACT_INFORMATION' => 
  array (
        
    array (
      
      array (
        'name' => 'first_name',
        'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}' 
      . '&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
      ),

    ),
    
    array (
      'last_name',
      'phone_work',
    ),    
    
    array (
          'title',
          'phone_mobile'        
    ),

	array (
		'department',
		'phone_fax'
	),	    
    
    array (
	      array('name'=>'account_name', 'type'=>'varchar', 'validateDependency'=>false,'customCode' => '<input name="account_name" id="EditView_account_name" {if ($fields.converted.value == 1)}disabled="true"{/if} size="30" maxlength="255" type="text" value="{$fields.account_name.value}">'),
		 'website',
    ),    	
	
    array (
      array (
	      'name' => 'primary_address_street',
          'hideLabel' => true,      
	      'type' => 'address',
	      'displayParams'=>array('key'=>'primary', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),
      ),
      
      array (
	      'name' => 'alt_address_street',
	      'hideLabel'=>true,
	      'type' => 'address',
	      'displayParams'=>array('key'=>'alt', 'copy'=>'primary', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),      
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
  array(
  
    array(
      'status',
      'lead_source', 
    ),

    array (
      array('name'=>'status_description'),
      array('name'=>'lead_source_description'),
    ),  
  
    array(
       'opportunity_amount',
       'refered_by'
    ),       
	
	array (
	    'campaign_name',
	    'do_not_call',
	), 
    
  ),
  
  'LBL_PANEL_ASSIGNMENT' => 
  array (
        array (
            array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
		  array (
		    'name'=>'team_name', 'displayParams'=>array('display'=>true),
		  ),
        ),
  ),  
),


);
?>