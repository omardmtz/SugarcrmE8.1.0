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

/**
 * Relationship table linking emails with 1 or more SugarBeans
 */
$dictionary['email_cache'] = array(
	'table' => 'email_cache',
	'fields' => array(
		'ie_id' => array(
			'name'		=> 'ie_id',
			'type'		=> 'id',
		),
		'mbox' => array(
			'name'		=> 'mbox',
			'type'		=> 'varchar',
			'len'		=> 60,
			'required'	=> true,
		),
		'subject' => array(
			'name'		=> 'subject',
			'type'		=> 'varchar',
			'len'		=> 255,
			'required'	=> false,
		),
		'fromaddr' => array(
			'name'		=> 'fromaddr',
			'type'		=> 'varchar',
			'len'		=> 100,
			'required'	=> false,
		),
		'toaddr' => array(
			'name'		=> 'toaddr',
			'type'		=> 'varchar',
			'len'		=> 255,
			'required'	=> false,
		),
		'senddate' => array(
			'name'		=> 'senddate',
			'type'		=> 'datetime',
			'required'	=> false,
		),
		'message_id' => array(
			'name'		=> 'message_id',
			'type'		=> 'varchar',
			'len'		=> 255,
			'required'	=> false,
		),
		'mailsize' => array(
			'name'		=> 'mailsize',
			'type'		=> 'uint',
			'len'		=> 16,
			'required'	=> true,
		),
		'imap_uid' => array(
			'name'		=> 'imap_uid',
			'type'		=> 'uint',
			'len'		=> 32,
			'required'	=> true,
		),
		'msgno' => array(
			'name'		=> 'msgno',
			'type'		=> 'uint',
			'len'		=> 32,
			'required'	=> false,
		),
		'recent' => array(
			'name'		=> 'recent',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
		),
		'flagged' => array(
			'name'		=> 'flagged',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
		),
		'answered' => array(
			'name'		=> 'answered',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
		),
		'deleted' => array(
			'name'		=> 'deleted',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> false,
		),
		'seen' => array(
			'name'		=> 'seen',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
		),
		'draft' => array(
			'name'		=> 'draft',
			'type'		=> 'tinyint',
			'len'		=> 1,
			'required'	=> true,
		),
	),
	'indices' => array(
		array(
			'name'			=> 'idx_ie_id',
			'type'			=> 'index',
			'fields'		=> array(
				'ie_id',
			),
		),
		array(
			'name'			=> 'idx_mail_date',
			'type'			=> 'index',
			'fields'		=> array(
				'ie_id',
				'mbox',
				'senddate',
			)
		),
		array(
			'name'			=> 'idx_mail_from',
			'type'			=> 'index',
			'fields'		=> array(
				'ie_id',
				'mbox',
				'fromaddr',
			)
		),
		array(
			'name'			=> 'idx_mail_subj',
			'type'			=> 'index',
			'fields'		=> array(
				'subject',
			)
		),
		array(
			'name'			=> 'idx_mail_to',
			'type'			=> 'index',
			'fields'		=> array(
				'toaddr',
			)
		),

	),
);