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
$viewdefs['Documents']['EditView'] = array(
    'templateMeta' => array('form' => array('enctype'=>'multipart/form-data',
                                            'hidden'=>array('<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">',
                                            				'<input type="hidden" name="contract_id" value="{$smarty.request.contract_id}">'),
                            			    ),
                            'maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
{sugar_getscript file="modules/Documents/documents.js"}',
),
 'panels' =>array (
  'lbl_document_information' =>
  array (
    array (
      'doc_type',
    ),
    array (
      array(
      		'name' => 'filename',
            'displayParams' => array('onchangeSetFileNameTo' => 'document_name'),
      ),
      array (
            'name' => 'status_id',
            'label' => 'LBL_DOC_STATUS',
      ),
    ),

    array (
      'document_name',

      array('name'=>'revision',
            'customCode' => '<input name="revision" type="text" value="{$fields.revision.value}" {$DISABLED}>'
           ),

    ),

    array (
	    array (
	      'name' => 'template_type',
	      'label' => 'LBL_DET_TEMPLATE_TYPE',
	    ),
    	array (
	      'name' => 'is_template',
	      'label' => 'LBL_DET_IS_TEMPLATE',
	    ),
    ),

    array (
      array('name'=>'active_date'),
       'category_id',

    ),

    array (
      'exp_date',
      'subcategory_id',
    ),

    array (
      array('name'=>'description'),
    ),

    array (
      array('name'=>'related_doc_name',
            'customCode' => '<input name="related_document_name" type="text" size="30" maxlength="255" value="{$RELATED_DOCUMENT_NAME}" readonly>' .
            		        '<input name="related_doc_id" type="hidden" value="{$fields.related_doc_id.value}"/>&nbsp;' .
            		        '<input title="{$APP.LBL_SELECT_BUTTON_TITLE}" type="{$RELATED_DOCUMENT_BUTTON_AVAILABILITY}" class="button" value="{$APP.LBL_SELECT_BUTTON_LABEL}" name="btn2" onclick=\'open_popup("Documents", 600, 400, "", true, false, {$encoded_document_popup_request_data}, "single", true);\'/>'),
      array('name'=>'related_doc_rev_number',
            'customCode' => '<select name="related_doc_rev_id" id="related_doc_rev_id" {$RELATED_DOCUMENT_REVISION_DISABLED}>{$RELATED_DOCUMENT_REVISION_OPTIONS}</select>',
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
        array('name'=>'team_name','displayParams'=>array('required'=>true)),
    ),
  ),
)


);
