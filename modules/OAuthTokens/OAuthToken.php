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
 * OAuth token
 */
class OAuthToken extends SugarBean
{
	public $module_dir = 'OAuthTokens';
	public $object_name = 'OAuthToken';
	public $table_name = 'oauth_tokens';
	public $disable_row_level_security = true;

	public $token;
    public $secret;
    public $tstate;
    public $token_ts;
    public $verify;
    public $consumer;
    public $assigned_user_id;
    public $consumer_obj;
    public $callback_url;
    // authdata is not preserved so far since we don't have any useful data yet
    // so it's an extension point for the future
    public $authdata;

    const REQUEST = 1;
    const ACCESS = 2;
    const INVALID = 3;

    public function __construct($token='', $secret='')
	{
	    parent::__construct();
        $this->token = $token;
        $this->secret = $secret;
        $this->setState(self::REQUEST);
        if(!empty($GLOBALS['current_user']->id)) {
            $this->addVisibilityStrategy('OwnerVisibility');
        }
	}

	/**
	 * Set token state
	 * @param int $s
	 * @return OAuthToken
	 */
	public function setState($s)
	{
	    $this->tstate = $s;
	    return $this;
	}

	/**
	 * Associate the token with the consumer key
	 * @param OAuthKey $consumer
	 * @return OAuthToken
	 */
	public function setConsumer($consumer)
	{
	    $this->consumer = $consumer->id;
	    $this->consumer_obj = $consumer;
	    return $this;
	}

	/**
	 * Set callback URL for request token
	 * @param string $url
	 * @return OAuthToken
	 */
    public function setCallbackURL($url)
    {
        $this->callback_url = $url;
        return $this;
    }

    /**
	 * Generate random token
	 * @return string
	 */
	protected static function randomValue()
	{
        $provider = new Zend_Oauth_Provider();
        $token = $provider->generateToken(6);
        return bin2hex($token);
	}

	/**
	 * Generate random token/secret pair and create token
	 * @return OAuthToken
	 */
    static function generate()
    {
        $t = self::randomValue();
        $s = self::randomValue();
        return new self($t, $s);
    }

    public function save($check_notify = false)
    {
        $this->token_ts = time();
        if(!isset($this->id)) {
            $this->new_with_id = true;
            $this->id = $this->token;
        }
        parent::save($check_notify);
    }

    /**
     * Load token by ID
     * @param string $token
     * @param string $oauth_type Either "oauth1" or "oauth2", defaults to "oauth1"
	 * @return OAuthToken
     */
    static function load($token,$oauth_type="oauth1")
	{
	    $ltoken = new self();
	    $ltoken->retrieve($token);
        if(empty($ltoken->id)) return null;
        $ltoken->token = $ltoken->id;
        if(!empty($ltoken->consumer)) {
            $ltoken->consumer_obj = BeanFactory::getBean('OAuthKeys',$ltoken->consumer);
            if(empty($ltoken->consumer_obj->id) || $ltoken->consumer_obj->oauth_type != $oauth_type ) {
                return null;
            }
        }
        return $ltoken;
	}

	/**
	 * Invalidate token
	 */
	public function invalidate()
	{
	    $this->setState(self::INVALID);
	    $this->verify = false;
	    return $this->save();
	}

	/**
	 * Create a new authorized token for specific user
	 * This bypasses normal OAuth process and creates a ready-made access token
	 * @param OAuthKey $consumer
	 * @param User $user
	 * @return OAuthToken
	 */
	public static function createAuthorized($consumer, $user)
	{
	    $token = self::generate();
	    $token->setConsumer($consumer);
	    $token->setState(self::ACCESS);
	    $token->assigned_user_id = $user->id;
        $token->save();
        return $token;
	}

	/**
	 * Authorize request token
	 * @param mixed $authdata
	 * @return string Validation token
	 */
	public function authorize($authdata)
	{
	    if($this->tstate != self::REQUEST) {
	        return false;
	    }
	    $this->verify = self::randomValue();
	    $this->authdata = $authdata;
	    if(isset($authdata['user'])) {
	        $this->assigned_user_id = $authdata['user'];
	    }
	    $this->save();
	    return $this->verify;
	}

	/**
	 * Copy auth data between tokens
	 * @param OAuthToken $token
	 * @return OAuthToken
	 */
	public function copyAuthData(OAuthToken $token)
	{
	    $this->authdata = $token->authdata;
	    $this->assigned_user_id = $token->assigned_user_id;
	    return $this;
	}

	/**
	 * Get query string for the token
	 */
	public function queryString()
	{
	    return "oauth_token={$this->token}&oauth_token_secret={$this->secret}";
	}

	/**
	 * Clean up stale tokens
	 */
    static public function cleanup()
	{
	    global $db;
	    $cleanup_start = microtime(true);
        $sugarConfig = SugarConfig::getInstance();
        // delete invalidated/request tokens older than oauth_token_life config value
        $db->query("DELETE FROM oauth_tokens WHERE tstate IN (".self::INVALID.",".self::REQUEST.") AND token_ts < ".(time()-$sugarConfig->get('oauth_token_life',60*60*24)));
        // delete expired access tokens
        $db->query("DELETE FROM oauth_tokens WHERE tstate = ".self::ACCESS." AND expire_ts <> -1 AND expire_ts < ".(time()-$sugarConfig->get('oauth_token_expiry',0)));

	    $GLOBALS['log']->info(sprintf("OAuthToken::cleanup() Cleaning up old tokens took: %.03f ms",microtime(true)-$cleanup_start));

	}

    /**
     * Clean up extra tokens for a user
     * @param int $limit How many user tokens should be allowed
     */
    public function cleanupOldUserTokens($limit = 1)
    {
        global $db;

        $ids = array($db->quote($this->id));

        if ($limit > 1) {
            // Find request tokens that are under the limit but still not this token
            $query = "SELECT id FROM oauth_tokens WHERE "
                . " tstate = " . self::ACCESS
                . " AND id <> " . $db->quoted($this->id)
                . " AND platform = " . $db->quoted($this->platform)
                . " AND assigned_user_id = " . $db->quoted($this->assigned_user_id);
            if (!empty($this->contact_id)) {
                $query .= " AND contact_id = " . $db->quoted($this->contact_id);
            }
            $query .= " ORDER BY expire_ts DESC";
            $ret = $db->limitQuery($query, 0, $limit - 1, true);

            while ($row = $db->fetchByAssoc($ret)) {
                $ids[] = $db->quote($row['id']);
            }
        }

        // delete request tokens from this user on this platform that aren't for this user
        $query = "DELETE FROM oauth_tokens WHERE "
            ." tstate = ".self::ACCESS
            ." AND id NOT IN ('".implode("', '",$ids)."') "
            ." AND platform = '".$db->quote($this->platform)."' "
            ." AND assigned_user_id = '".$db->quote($this->assigned_user_id)."' ";

            if (!empty($this->contact_id)) {
                $query .= " AND contact_id = '".$db->quote($this->contact_id)."' ";
            }
            
        $db->query($query);
    }

	/**
	 * Check if the nonce is valid
	 * @param string $key
	 * @param string $nonce
	 * @param string $ts
	 */
	public static function checkNonce($key, $nonce, $ts)
	{
	    global $db;

	    $res = $db->query(sprintf("SELECT * FROM oauth_nonce WHERE conskey='%s' AND nonce_ts > %d", $db->quote($key), $ts));
	    if($res && $db->fetchByAssoc($res)) {
	        // we have later ts
	        return Zend_Oauth_Provider::BAD_TIMESTAMP;
	    }

	    $res = $db->query(sprintf("SELECT * FROM oauth_nonce WHERE conskey='%s' AND nonce='%s' AND nonce_ts = %d", $db->quote($key), $db->quote($nonce), $ts));
	    if($res && $db->fetchByAssoc($res)) {
	        // Already seen this one
	        return Zend_Oauth_Provider::BAD_NONCE;
        }
        $db->query(sprintf("DELETE FROM oauth_nonce WHERE conskey='%s' AND nonce_ts < %d", $db->quote($key), $ts));
        $db->query(sprintf("INSERT INTO oauth_nonce(conskey, nonce, nonce_ts) VALUES('%s', '%s', %d)", $db->quote($key), $db->quote($nonce), $ts));
	    return Zend_Oauth_Provider::OK;
	}

	/**
	 * Delete token by ID
	 * @param string id
	 * @see SugarBean::mark_deleted($id)
	 */
	public function mark_deleted($id)
	{
        $query = "DELETE FROM {$this->table_name} WHERE id = ? ";
        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($id));
	}

	/**
	 * Delete tokens by consumer ID
	 * @param string $user
	 */
	public static function deleteByConsumer($consumer_id)
	{
	   global $db;
	   $db->query("DELETE FROM oauth_tokens WHERE consumer='".$db->quote($consumer_id) ."'");
	}

	/**
	 * Delete tokens by user ID
	 * @param string $user
	 */
	public static function deleteByUser($user_id)
	{
	   global $db;
	   $db->query("DELETE FROM oauth_tokens WHERE assigned_user_id='".$db->quote($user_id) ."'");
	}


}

function displayDateFromTs($focus, $field, $value, $view='ListView')
{
    $field = strtoupper($field);
    if(!isset($focus[$field])) return '';
    // No date, don't return anything
    if($focus[$field]==-1) return '';
    global $timedate;
    return $timedate->asUser($timedate->fromTimestamp($focus[$field]));
}
