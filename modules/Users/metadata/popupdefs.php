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

$popupMeta = array(
	'moduleMain' => 'User',
	'varName' => 'USER',
	'orderBy' => 'user_name',
	'whereClauses' => array(
		'first_name' => 'users.first_name',
		'last_name' => 'users.last_name',
		'user_name' => 'users.user_name',
		'is_group' => 'users.is_group',
	),
	'whereStatement'=> " users.status = 'Active' and users.portal_only= '0'",
	'searchInputs' => array(
		'first_name',
		'last_name',
		'user_name',
		'is_group',
	),
);
