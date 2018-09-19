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
$viewdefs['ProjectTask']['DetailView'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
                            'includes'=> array(
                                         array('file'=>'modules/ProjectTask/ProjectTask.js'),
                                         	),
                            'form' => array(
										'buttons' => array( 'EDIT',

				                            				array( 'customCode' => '{if $bean->aclAccess("edit")}<input type="submit" name="EditTaskInGrid" value=" {$MOD.LBL_EDIT_TASK_IN_GRID_TITLE} " '.
																					'title="{$MOD.LBL_EDIT_TASK_IN_GRID_TITLE}"  '.
																					'class="button" onclick="this.form.record.value=\'{$fields.project_id.value}\';prep_edit_task_in_grid(this.form);" />{/if}',
                                                                //Bug#51778: The custom code will be replaced with sugar_html. customCode will be deplicated.
                                                                'sugar_html' => array(
                                                                    'type' => 'submit',
                                                                    'value' => ' {$MOD.LBL_EDIT_TASK_IN_GRID_TITLE} ',
                                                                    'htmlOptions' => array(
                                                                        'title' => '{$MOD.LBL_EDIT_TASK_IN_GRID_TITLE}',
                                                                        'class' => 'button',
                                                                        'name' => 'EditTaskInGrid',
                                                                        'onclick' => 'this.form.record.value=\'{$fields.project_id.value}\';prep_edit_task_in_grid(this.form);'
                                                                    ),
                                                                    'template' => '{if $bean->aclAccess("edit")}[CONTENT]{/if}'
                                                                ),

															),
														),
										'hideAudit' => true,
											),

    ),
 'panels' =>array (
  'default' =>
  array (

    array (
      'name',

      array (
        'name' => 'project_task_id',
        'label' => 'LBL_TASK_ID',
      ),
    ),    

    array (
      'date_start',
      'date_finish',
    ),
	array (
		array (
		        'name' => 'assigned_user_name',
		        'label' => 'LBL_ASSIGNED_USER_ID',
		      ),
		array (

			'name' => 'team_name',
		),
	),    


    array (
      array (
        'name' => 'duration',
        'customCode' => '{$fields.duration.value}&nbsp;{$fields.duration_unit.value}',
        'label' => 'LBL_DURATION',
      ),
    ),

    array (
		'status',
		'priority',
    ),    
    
    array (
      'percent_complete',
      array (
        'name' => 'milestone_flag',
        'label' => 'LBL_MILESTONE_FLAG',
      ),
    ),    


    array (
      array(
      	'name' => 'resource_id',
      	'customCode' => '{$resource}',
      	'label' => 'LBL_RESOURCE',
      ),
    ),

    array (

      array (
        'name' => 'project_name',
        'customCode' => '<a href="index.php?module=Project&action=DetailView&record={$fields.project_id.value}">{$fields.project_name.value}&nbsp;</a>',
        'label' => 'LBL_PARENT_ID',
      ),

      array(
      	'name' => 'actual_duration',
      	'customCode' => '{$fields.actual_duration.value}&nbsp;{$fields.duration_unit.value}',
      	'label' => 'LBL_ACTUAL_DURATION',
      ),
    ),
    
    array (

      'task_number',
      'order_number',
    ),

    array (
      'estimated_effort',
	  'utilization',      
    ),            

    array (

      array (
        'name' => 'description',
      ),
    ),

  ),
)


);
?>
