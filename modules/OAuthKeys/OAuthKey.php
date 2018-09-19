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

require_once 'vendor/Zend/Oauth/Provider.php';

/**
 * OAuth customer key
 */
class OAuthKey extends Basic
{
	public $module_dir = 'OAuthKeys';
	public $object_name = 'OAuthKey';
	public $table_name = 'oauth_consumer';
	public $c_key;
	public $c_secret;
	public $name;
	public $disable_row_level_security = true;

	static public $keys_cache = array();

	/**
	 * Get record by consumer key
	 * @param string $key
     * @param string $oauth_type Either "oauth1" or "oauth2", defaults to "oauth1"
	 * @return OAuthKey|false
	 */
	public function getByKey($key,$oauth_type="oauth1")
	{
	    $this->retrieve_by_string_fields(array("c_key" => $key,"oauth_type"=>$oauth_type));
	    if(empty($this->id)) return false;
	    // need this to decrypt the key
        $this->check_date_relationships_load();
	    return $this;
	}

	/**
	 * Fetch customer key by id
	 * @param string $key
     * @param string $oauth_type Either "oauth1" or "oauth2", defaults to "oauth1"
	 * @return OAuthKey|false
	 */
	public static function fetchKey($key,$oauth_type="oauth1")
	{
	    if(isset(self::$keys_cache[$key])&&self::$keys_cache[$key]->oauth_type==$oauth_type) {
	        return self::$keys_cache[$key];
	    }
	    $k = new self();
	    if($k->getByKey($key,$oauth_type)) {
	        self::$keys_cache[$key] = $k;
	        BeanFactory::registerBean($k);
	        return $k;
	    }
	    return false;
	}

	/**
	 * Delete the key
	 * also removed all tokens
	 */
	public function mark_deleted($id)
	{
        $query = "DELETE FROM {$this->table_name} WHERE id = ? ";
        $qoat = "DELETE FROM oauth_tokens WHERE consumer = ? ";

        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($id));
        $conn->executeQuery($qoat, array($id));
	}

}
