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
$viewdefs ['Notes'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
         
        array (
          'label' => '10',
          'field' => '30',
        ),
         
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'lbl_note_information' => 
      array (
         
        array (
           'contact_name',
           
          array (
            'name' => 'parent_name',
            'customLabel' => '{sugar_translate label=\'LBL_MODULE_NAME\' module=$fields.parent_type.value}',
          ),
        ),
         
        array (
          array (
            'name' => 'name',
            'label' => 'LBL_SUBJECT',
          ),
        ),
         
        array (
           
          array (
            'name' => 'filename',
          ),          
        ),
         
        array (
           
          array (
            'name' => 'description',
            'label' => 'LBL_NOTE_STATUS',
          ),
        ),
      ),

        'LBL_PANEL_ASSIGNMENT' => array(
	        array (
	          'assigned_user_name',
	          array (
	            'name' => 'date_modified',
	            'label' => 'LBL_DATE_MODIFIED',
	            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
	          )
	        ),
	        array (
			  'team_name',
			  array (
	            'name' => 'date_entered',
	            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
	          )
	        ),
        ),      
    ),
  ),
);
?>
