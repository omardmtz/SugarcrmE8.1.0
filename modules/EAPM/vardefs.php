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
$dictionary['EAPM'] = array(
	'table'=>'eapm',
	'audited'=>false,
	'fields'=>array (
  'password' =>
  array (
    'required' => true,
    'name' => 'password',
    'vname' => 'LBL_PASSWORD',
    'type' => 'encrypt',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => false,
    'len' => '255',
    'size' => '20',
    'write_only' => true,
  ),
  'url' =>
  array (
    'required' => true,
    'name' => 'url',
    'vname' => 'LBL_URL',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => '255',
    'size' => '20',
  ),
  'application' =>
  array (
    'required' => true,
    'name' => 'application',
    'vname' => 'LBL_APPLICATION',
    'type' => 'enum',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'function' => 'getEAPMExternalApiDropDown',
    'studio' => 'visible',
    'default' => 'webex',
  ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '255',
    'unified_search' => true,
    'importable' => 'required',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'size' => '20',
  ),
	  'api_data' =>
	  array (
	    'name' => 'api_data',
	    'vname' => 'LBL_API_DATA',
	    'type' => 'text',
	    'comment' => 'Any API data that the external API may wish to store on a per-user basis',
	    'rows' => 6,
	    'cols' => 80,
	  ),
	  'consumer_key' => array(
	  	'name' => 'consumer_key',
	    'type' => 'varchar',
	    'vname' => 'LBL_API_CONSKEY',
//        'required' => true,
        'importable' => 'required',
        'massupdate' => 0,
        'audited' => false,
        'reportable' => false,
        'studio' => 'hidden',
	  ),
	  'consumer_secret' => array(
	  	'name' => 'consumer_secret',
	    'type' => 'varchar',
	    'vname' => 'LBL_API_CONSSECRET',
//        'required' => true,
        'importable' => 'required',
        'massupdate' => 0,
        'audited' => false,
        'reportable' => false,
        'studio' => 'hidden',
	  ),
	  'oauth_token' => array(
	  	'name' => 'oauth_token',
	    'type' => 'varchar',
	    'vname' => 'LBL_API_OAUTHTOKEN',
        'importable' => false,
        'massupdate' => 0,
        'audited' => false,
        'reportable' => false,
    	'required' => false,
        'studio' => 'hidden',
	  ),
	  'oauth_secret' => array(
	  	'name' => 'oauth_secret',
	    'type' => 'varchar',
	    'vname' => 'LBL_API_OAUTHSECRET',
        'importable' => false,
        'massupdate' => 0,
        'audited' => false,
        'reportable' => false,
    	'required' => false,
        'studio' => 'hidden',
	  ),
	  'validated' => array(
        'required' => false,
        'name' => 'validated',
        'vname' => 'LBL_VALIDATED',
        'type' => 'bool',
	    'default' => false,
	  ),
      'note' => array(
          'name' => 'note',
          'vname' => 'LBL_NOTE',
          'required' => false,
          'reportable' => false,
          'importable' => false,
          'massupdate' => false,
          'studio' => 'hidden',
          'type' => 'varchar',
          'source' => 'non-db',
      ),

),
	'relationships'=>array (
    ),
    'indices' => array(
        array(
                'name' => 'idx_app_active',
                'type' => 'index',
                'fields'=> array('assigned_user_id', 'application', 'validated'),
        ),
        array('name' => 'idx_eapm_name', 'type' => 'index', 'fields' => array('name')),
),
	'optimistic_locking'=>true,
    'visibility' => array('OwnerOrAdminVisibility' => true),
);
if (!class_exists('VardefManager')){
}
VardefManager::createVardef('EAPM','EAPM', array('basic','assignable'));
