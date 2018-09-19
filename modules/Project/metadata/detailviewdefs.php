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

// $Id$

$viewdefs['Project']['DetailView'] = array(
	'templateMeta' => array( 
		'maxColumns' => '2',
		'widths' => array(	
			array('label' => '10', 'field' => '30'),
			array('label' => '10', 'field' => '30')
		),
		'includes'=> array(
			 array('file'=>'modules/Project/Project.js'),
	 	),
		'form' => array(
	 		'buttons'=> array(
			 	array( 'customCode' =>
                        '{if $bean->aclAccess("edit")}' .
                            '<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" ' .
                            'accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button" type="submit" ' .
                            'name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}"'.
                            'onclick="'.
                            '{if $IS_TEMPLATE}'.
                                'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesDetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'ProjectTemplatesEditView\';'.
                            '{else}'.
                                'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'EditView\'; '.
                            '{/if}"'.
                            '/>' .
                        '{/if}',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'submit',
                        'value' => ' {$APP.LBL_EDIT_BUTTON_LABEL} ',
                        'htmlOptions' => array(
                            'id' => 'edit_button',
                            'class' => 'button',
                            'accessKey' => '{$APP.LBL_EDIT_BUTTON_KEY}',
                            'name' => 'Edit',
                            'onclick' =>
                                '{if $IS_TEMPLATE}'.
                                    'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesDetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'ProjectTemplatesEditView\';'.
                                '{else}'.
                                    'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$id}\'; this.form.action.value=\'EditView\'; '.
                                '{/if}',
                        ),
                        'template' => '{if $bean->aclAccess("edit")}[CONTENT]{/if}'
                    ),
				),
				array( 'customCode' =>
                        '{if $bean->aclAccess("delete")}' .
                            '<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" ' .
                            'accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" type="button" ' .
                            'name="Delete" id="delete_button" value="{$APP.LBL_DELETE_BUTTON_LABEL}"'.
                            'onclick="'.
                            '{if $IS_TEMPLATE}'.
                                'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesListView\'; this.form.action.value=\'Delete\'; if( confirm(\'{$APP.NTC_DELETE_CONFIRMATION}\') )  {ldelim} return true; {rdelim} else {ldelim} return false; {rdelim} '.
                            '{else}'.
                                'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ListView\'; this.form.action.value=\'Delete\'; if( confirm(\'{$APP.NTC_DELETE_CONFIRMATION}\'))  {ldelim} return true; {rdelim} else {ldelim} return false; {rdelim} '.
                            '{/if}"'.
                            '/>' .
                        '{/if}',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'button',
                        'id'    => 'delete_button',
                        'value' => '{$APP.LBL_DELETE_BUTTON_LABEL}',
                        'htmlOptions' => array(
                            'title' => '{$APP.LBL_DELETE_BUTTON_TITLE}',
                            'accessKey' => '{$APP.LBL_DELETE_BUTTON_KEY}',
                            'id'    => 'delete_button',
                            'class' => 'button',
                            'onclick' => 'prep_delete(this.form);' . 
                                '{if $IS_TEMPLATE}'.
                                    'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ProjectTemplatesListView\'; this.form.action.value=\'Delete\'; if (confirm(\'{$APP.NTC_DELETE_CONFIRMATION}\')) {ldelim} return true; {rdelim} else {ldelim} return false; {rdelim}'.
                                '{else}'.
                                    'this.form.return_module.value=\'Project\'; this.form.return_action.value=\'ListView\'; this.form.action.value=\'Delete\'; if (confirm(\'{$APP.NTC_DELETE_CONFIRMATION}\')) {ldelim} return true; {rdelim} else {ldelim} return false; {rdelim}'.
                                '{/if}',
                        ),
                        'template' => '{if $bean->aclAccess("delete")}[CONTENT]{/if}'
                    ),
				),

				array( 'customCode' =>
                        '{if $EDIT_RIGHTS_ONLY}<input title="{$MOD.LBL_VIEW_GANTT_TITLE}" '.
                        'class="button" type="submit" '.
                        'name="EditProjectTasks" id="view_gantt_button" value="  {$MOD.LBL_VIEW_GANTT_TITLE}  " '.
                        'onclick="prep_edit_project_tasks(this.form);" />{/if}',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'submit',
                        'value' => '{$MOD.LBL_VIEW_GANTT_TITLE}',
                        'htmlOptions' => array(
                            'title' => '{$MOD.LBL_VIEW_GANTT_TITLE}',
                            'class' => 'button',
                            'name' => 'EditProjectTasks',
                            'id' => 'view_gantt_button',
                            'onclick' => 'prep_edit_project_tasks(this.form);'
                        ),
                        'template' => '{if $EDIT_RIGHTS_ONLY}[CONTENT]{/if}',
                    )
                ),
				array( 'customCode' =>
                        '<input title="{$SAVE_AS}" ' .
                        'class="button" type="submit" ' .
                        'name="SaveAsTemplate" id="save_as_template_button" value="{$SAVE_AS}"'.
                        'onclick="'.
                        '{if $IS_TEMPLATE}'.
                            'prep_save_as_project(this.form)'.
                        '{else}'.
                            'prep_save_as_template(this.form)'.
                        '{/if}"'.
                        '/>',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'submit',
                        'value' => '{$SAVE_AS}',
                        'htmlOptions' => array(
                            'name' => 'SaveAsTemplate',
                            'class' => 'button',
                            'id'=> 'save_as_template_button',
                            'onclick' =>
                                '{if $IS_TEMPLATE}'.
                                'prep_save_as_project(this.form)'.
                                '{else}'.
                                'prep_save_as_template(this.form)'.
                                '{/if}',

                        ),
                    )
				),
				array( 'customCode' =>
                        '<input title="{$MOD.LBL_EXPORT_TO_MS_PROJECT}" ' .
                        'class="button" type="submit" ' .
                        'name="ExportToProject" id="export_to_ms_project_button" value="{$MOD.LBL_EXPORT_TO_MS_PROJECT}" ' .
                        'onclick="prep_export_to_project(this.form);"/>',
                    //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                    'sugar_html' => array(
                        'type' => 'submit',
                        'value' => '{$MOD.LBL_EXPORT_TO_MS_PROJECT}',
                        'htmlOptions' => array(
                            'title' => '{$MOD.LBL_EXPORT_TO_MS_PROJECT}',
                            'name' => 'ExportToProject',
                            'id'=> 'export_to_ms_project_button',
                            'onclick' => 'prep_export_to_project(this.form);'
                        ),
                    ),
				),
 			),
 		),
	),
	'panels' => array ( 
	  'lbl_project_information' =>
	  array (
			array ( 
				'name', 
				'status', ),
			array (
				array ( 
					'name' => 'estimated_start_date',
					'label' => 'LBL_DATE_START',
  				),
				'priority',
			),
			array (
			    array (
					'name' => 'estimated_end_date',
					'label' => 'LBL_DATE_END',
  				  ),
			  	),
			array (
				'description',
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
	),
);
?>
