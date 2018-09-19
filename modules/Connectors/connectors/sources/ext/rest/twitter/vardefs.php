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
* Description:
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
* Reserved. Contributor(s): contact@synolia.com - www.synolia.com
* *******************************************************************************/

$dictionary['ext_rest_twitter'] = array(

  'comment' => 'vardefs for twitter connector',
  'fields' => array (
    'id' =>
	  array (
	    'name' => 'id',
	    'vname' => 'LBL_ID',
	    'type' => 'id',
	    'comment' => 'Unique identifier',
	  	'hidden' => true,
	),
    'name'=> array(
	    'name' => 'name',
	    'vname' => 'LBL_NAME',
	    'type' => 'varchar',
		'hover' => true,
	    'comment' => 'The username on Twitter',
    ),
  )
);
?>
