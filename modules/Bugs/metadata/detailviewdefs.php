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
$viewdefs['Bugs']['DetailView'] = array(
'templateMeta' => array('form' => array('buttons'=>array('EDIT', 'DUPLICATE', 'DELETE', 'FIND_DUPLICATES',)),
                        'maxColumns' => '2',
                        'widths' => array(
                                        array('label' => '10', 'field' => '30'),
                                        array('label' => '10', 'field' => '30')
                                        ),
                        ),

'panels' =>array (
  'lbl_bug_information'=>array(
	  array (
	    'bug_number',
	    'priority',
	  ),

	  array (
	    array (
	      'name' => 'name',
	      'label' => 'LBL_SUBJECT',
	    ),
	    'status',
	  ),

	  array (
	    'type',
	    'source',
	  ),

	  array (
	    'product_category',
	    'resolution',
	  ),

	  array (
	    array (
	      'name' => 'found_in_release',
	      'label' => 'LBL_FOUND_IN_RELEASE',
	    ),
	    'fixed_in_release',
	  ),

	  array (
	    'description',
	  ),

	  array (
	    'work_log',
	  ),

	  array (
	     array('name'=>'portal_viewable',
			   'label' => 'LBL_SHOW_IN_PORTAL',
		       'hideIf' => 'empty($PORTAL_ENABLED)',
	         ),
	  ),
  ),

      'LBL_PANEL_ASSIGNMENT' =>
      array (

        array (

          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),

          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),

        array (
          'team_name',

          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),

        ),
      ),
)
);
?>