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
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

 $module_name = '<module_name>';

 $viewdefs[$module_name]['QuickCreate'] = array(
    'templateMeta' => array('form' => array('enctype'=>'multipart/form-data',
                                            'hidden'=>array()),

                            'maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
'javascript' =>
	'{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	{sugar_getscript file="modules/Documents/documents.js"}',
),
 'panels' =>array (
  'default' =>
  array (
	array (
      'document_name',
      'assigned_user_name',
	),

    array (
      array('name'=>'uploadfile',
            'customCode' => '{if $fields.id.value!=""}
            				{assign var="type" value="hidden"}
            		 		{else}
            		 		{assign var="type" value="file"}
            		  		{/if}
            		  		<input name="uploadfile" type = {$type} size="30" maxlength="" onchange="setvalue(this);" value="{$fields.filename.value}">{$fields.filename.value}',
            'displayParams'=>array('required'=>true),
            ),
      array('name'=>'team_name','displayParams'=>array('required'=>true)),
	),

    array (
      'active_date',
    ),
    
    array (
       'category_id',
       'subcategory_id',
    ),

    array (
      array('name'=>'description', 'displayParams'=>array('rows'=>10, 'cols'=>120)),
    ),
  ),
)
);

