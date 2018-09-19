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

 $viewdefs[$module_name]['EditView'] = array(
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
      array(
      		'name'=>'uploadfile',
            'displayParams' => array('onchangeSetFileNameTo' => 'document_name'),
      ),

	),

    array (
       'category_id',
       'subcategory_id',
    ),

    array (
      'assigned_user_name',
      array('name'=>'team_name','displayParams'=>array('required'=>true)),
    ),

    array (
      'active_date',
      'exp_date',
    ),

	array('status_id'),
    array (

      array('name'=>'description'),

    ),
  ),
)
);

