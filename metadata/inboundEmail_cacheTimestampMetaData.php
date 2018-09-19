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
$dictionary['InboundEmail_cacheTimestamp'] = array ('table' => 'inbound_email_cache_ts',
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'varchar',
			'len'	=> 255,
			'required' => true,
			'reportable' => false,
		),
		'ie_timestamp' => array(
			'name'	=> 'ie_timestamp',
			'type'	=> 'uint',
			'len'	=> 16,
			'required'	=> true,
		),
	),
	'indices' => array (
		array(
			'name' => 'ie_cachetimestamppk',
			'type' =>'primary',
			'fields' => array(
				'id'
			)
		),
	), /* end indices */
	'relationships' => array (
	), /* end relationships */
);

?>
