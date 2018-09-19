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
$subpanel_layout = array(
	'top_buttons' => array(
       array('widget_class' => 'SubPanelTopCreateButton'),
	   array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Documents','field_to_name_array'=>array('document_revision_id'=>'REL_ATTRIBUTE_document_revision_id')),
	),

	'where' => '',
	
	

    'list_fields'=> array(
      'document_name'=> array(
	    	'name' => 'document_name',
	 		'vname' => 'LBL_LIST_DOCUMENT_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '40%',
	   ),
       'is_template'=>array(
 	    	'name' => 'is_template',
	 	    'vname' => 'LBL_LIST_IS_TEMPLATE',
		    'width' => '15%',
		    'widget_type'=>'checkbox',
		),
       'template_type'=>array(
 	    	'name' => 'template_types',
	 	    'vname' => 'LBL_LIST_TEMPLATE_TYPE',
		    'width' => '20%',
		),		
       'latest_revision'=>array(
 	    	'name' => 'latest_revision',
	 	    'vname' => 'LBL_LATEST_REVISION',
		    'width' => '15%',
            'sortable' => false
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Documents',
			'width' => '5%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Documents',
			'width' => '5%',
		),		
		'document_revision_id'=>array(
			'usage'=>'query_only'
		),
	),
);
?>