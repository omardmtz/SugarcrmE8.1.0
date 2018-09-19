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
 
$viewdefs['ContractTypes']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
    ),
    
    'panels' => array (
    	'default'=> array(
	         array(array('name'=>'name',
	                     'label'=>'LBL_NAME', 
	                     'displayParams'=>array('required'=>true, 'size'=>60)
	               )
	         ),
	         array(array('name'=>'list_order',
	                     'label'=>'LBL_LIST_ORDER',
	                     'displayParams'=>array('size'=>4)
	               ),
	         ),
         ),
    )


);
?>
