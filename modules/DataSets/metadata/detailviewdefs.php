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
$viewdefs['DataSets']['DetailView'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
    ),
 'panels' =>array (
  'default' =>
  array (

    array (
	  'name',
      array (
        'name' => 'report_name',
        'customCode' => '<a href="index.php?module=ReportMaker&action=DetailView&record={$fields.report_id.value}">{$fields.report_name.value}</a>',
      ),
    ),

    array (
      array('name'=>'query_name', 'type'=>'varchar'),
      'parent_name',
    ),

    array (
      array (
        'name' => 'child_name',
        'customCode' => '{if isset($bean->child_id) && !empty($bean->child_id)}
						 <a href="index.php?module=DataSets&action=DetailView&record={$bean->child_id}">{$bean->child_name}</a>
						 {else}
						 {$bean->child_name}
						 {/if}'
      ),
      'team_name',
    ),

    array (
      'description',
    ),

    array (

      array (
        'name' => 'table_width',
        'fields'=>array('table_width', 'table_width_type'),
      ),
      'font_size',
    ),

    array (
      'exportable',
      'header_text_color',
    ),

    array (
      'header',
      'body_text_color',
    ),

    array (
      'prespace_y',
      'header_back_color',
    ),

    array (
      'use_prev_header',
      'body_back_color',
    ),


  ),
)


);
?>
