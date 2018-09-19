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
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Notes'),
	),

	'where' => '',



	'list_fields' => array(
		'object_image'=>array(
			'vname' => 'LBL_OBJECT_IMAGE',
			'widget_class' => 'SubPanelIcon',
 		 	'width' => '2%',
 		 	'image2'=>'attachment',
 		 	'image2_url_field'=> array(
				'id_field' => 'id',
				'filename_field' => 'filename',
			),
		),
        'name'=>array(
 			'vname' => 'LBL_LIST_SUBJECT',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '50%',
		),
		'contact_name'=>array(
			'module' => 'Contacts',
			'vname' => 'LBL_LIST_CONTACT_NAME',
		    'width' => '20%',
            'target_record_key' => 'contact_id',
            'target_module' => 'Contacts',
            'widget_class' => 'SubPanelDetailViewLink',
		),
		'date_modified'=>array(
		 	'vname' => 'LBL_LIST_DATE_MODIFIED',
			'width' => '10%',
		),
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Notes',
			'width' => '5%',
		),
        'remove_button'=>array(
            'vname' => 'LBL_REMOVE',
             'widget_class' => 'SubPanelRemoveButton',
             'width' => '2%',
        ),
		'file_url'=>array(
			'usage'=>'query_only'
			),
		'filename'=>array(
			'usage'=>'query_only'
			),
	),
);

?>