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



$module_name = 'OAuthKeys';
$listViewDefs[$module_name] = array(
	'NAME' => array(
		'width' => '32',
		'label' => 'LBL_NAME',
		'default' => true,
        'link' => true,
    ),
	'C_KEY' => array(
		'width' => '40',
		'label' => 'LBL_CONSKEY',
        'default' => true),
    'OAUTH_TYPE' => array(
        'width' => '20',
        'label' => 'LBL_OAUTH_TYPE',
        'default' => true,
    ),
    'CLIENT_TYPE' => array(
        'width' => '20',
        'label' => 'LBL_CLIENT_TYPE',
        'default' => true,
    ),
);
