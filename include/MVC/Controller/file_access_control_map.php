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
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$file_access_control_map = array(
	'modules' => array(
		'administration' => array(
			'actions' => array(
				'backups',
				'updater',
			),
			'links'	=> array(
				'update',
				'backup_management',
				'upgrade_wizard',
				'moduleBuilder',
			),
		),
		'upgradewizard' => array(
				'actions' => array(
					'index',
				),
		),
		'modulebuilder' => array(
				'actions' => array(
					'index' => array('params' => array('type' => array('mb'))),
				),
		),
	)
);
?>