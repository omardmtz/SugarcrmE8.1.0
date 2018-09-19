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

// $Id: default.php 13782 2006-06-06 17:58:55Z majed $
$subpanel_layout = array(
	'top_buttons' => array(
       array('widget_class' => 'SubPanelTopCreateButton'),
	   array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Documents','field_to_name_array'=>array('document_revision_id'=>'REL_ATTRIBUTE_document_revision_id')),
	),

	'where' => '',
	
	

    'list_fields'=> array(
 	   'object_image'=>array(
            'vname' => 'LBL_OBJECT_IMAGE',
            'widget_class' => 'SubPanelIcon',
            'width' => '2%',
            'image2'=>'attachment',
            'image2_url_field'=> array(
                'id_field' => 'id',
                'filename_field' => 'filename',
            ),
            'attachment_image_only'=>true,
	   ),
       'document_name'=> array(
	    	'name' => 'document_name',
	 		'vname' => 'LBL_LIST_DOCUMENT_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '20%',
	   ),
       'filename'=>array(
 	    	'name' => 'filename',
	 	    'vname' => 'LBL_LIST_FILENAME',
		    'width' => '20%',
            'module' => 'Documents',
            'sortable'=>false,
            'displayParams' => array(
                'module' => 'Documents',
            ),
		),
		'document_revision_id' => array(
	       'name' => 'document_revision_id',
	       'usage' => 'query_only',
	   ),
       'category_id'=>array(
 	    	'name' => 'category_id',
	 	    'vname' => 'LBL_LIST_CATEGORY',
		    'width' => '20%',
		),		
       'doc_type'=>array(
 	    	'name' => 'doc_type',
	 	    'vname' => 'LBL_LIST_DOC_TYPE',
		    'width' => '10%',
		),
       'status_id'=>array(
 	    	'name' => 'status_id',
	 	    'vname' => 'LBL_LIST_STATUS',
		    'width' => '10%',
		),
       'active_date'=>array(
 	    	'name' => 'active_date',
	 	    'vname' => 'LBL_LIST_ACTIVE_DATE',
		    'width' => '10%',
		),
        'get_latest'=>array(
			'widget_class' => 'SubPanelGetLatestButton',
		 	'module' => 'Documents',
			'width' => '5%',
		),
        'load_signed'=>array(
            'widget_class' => 'SubPanelLoadSignedButton',
            'module' => 'Documents',
            'width' => '5%',
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
	),
);
?>