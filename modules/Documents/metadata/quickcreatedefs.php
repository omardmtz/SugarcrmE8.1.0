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
$viewdefs['Documents']['QuickCreate'] = array(
    'templateMeta' => array('form' => array('enctype'=>'multipart/form-data',
                                            'hidden'=>array('<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
                                                            '<input type="hidden" name="parent_id" value="{$smarty.request.parent_id}">',
                                                            '<input type="hidden" name="parent_type" value="{$smarty.request.parent_type}">',)),
                                            
                            'maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
                            'includes' => 
                              array (
                                array('file' => 'include/javascript/popup_parent_helper.js'),
                                array('file' => 'modules/Documents/documents.js'),
                              ),
),
 'panels' =>array (
  'default' => 
  array (
    
    array (
      'doc_type',
      'status_id',
    ),
    array (
      array('name'=>'filename', 
            'displayParams'=>array('required'=>true, 'onchangeSetFileNameTo' => 'document_name'),
            ),
    ),
    
    array (
      'document_name',
       array('name'=>'revision',
            'customCode' => '<input name="revision" type="text" value="{$fields.revision.value}" {$DISABLED}>'
           ),
    ),    
    
    array (
       array('name'=>'active_date','displayParams'=>array('required'=>true)),
       'category_id',
    ),
    
    array (
      array('name'=>'assigned_user_name',),
      array('name'=>'team_name','displayParams'=>array('required'=>true)),
    ),

    array (
      array('name'=>'description', 'displayParams'=>array('rows'=>10, 'cols'=>120)),
    ),
  ),
)

);
?>
