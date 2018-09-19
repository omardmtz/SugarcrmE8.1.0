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

// $Id: default.php 52533 2009-11-18 01:32:20Z clee $
$subpanel_layout = array(
	'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateRevisionButton'),
	),

	'where' => '',


	'list_fields' => array(
		  'filename' => 
		  array (
		    'vname' => 'LBL_REV_LIST_FILENAME',
		    'widget_class' => 'SubPanelDetailViewLink',
		    'width' => '15%',
		    'default' => true,
		  ),
		  'revision' => 
		  array (
		    'vname' => 'LBL_REV_LIST_REVISION',
		    'width' => '5%',
		    'default' => true,
		  ),
		  'created_by_name' => 
		  array (
		    'vname' => 'LBL_REV_LIST_CREATED',
		    'width' => '25%',
		    'default' => true,
		  ),
		  'date_entered' => 
		  array (
		    'vname' => 'LBL_REV_LIST_ENTERED',
		    'width' => '10%',
		    'default' => true,
		  ),
		  'change_log' => 
		  array (
		    'vname' => 'LBL_REV_LIST_LOG',
		    'width' => '35%',
		    'default' => true,
		  ),
		  'del_button' => 
		  array (
		    'vname' => 'LBL_DELETE_BUTTON',
		    'widget_class' => 'SubPanelRemoveButton',
		    'width' => '5%',
		    'default' => true,
		  ),
		  'document_id' => 
		  array (
		    'usage' => 'query_only',
		  ),
          'doc_type' =>
		  array (
		    'usage' => 'query_only',
		  ),
	),
);
?>