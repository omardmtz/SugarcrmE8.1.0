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

require_once 'modules/OAuthTokens/OAuthToken.php';
/**
 * Sugar OAuth provider implementation
 * @api
 */
class SugarOAuthServer
{
    /**
     * OAuth token
     * @var OAuthToken
     */
    protected $token;

    /**
     * Check if everything is OK
     * @throws OAuthException
     */
    protected function check()
    {
        if(!function_exists('mhash') && !function_exists('hash_hmac')) {
            // define exception class
            throw new OAuthException("MHash extension required for OAuth support");
        }
    }

    /**
     * Is this functionality enabled?
     */
    public static function enabled()
    {
        return function_exists('mhash') || function_exists('hash_hmac');
    }

    /**
     * Find consumer by key
     * @param $provider
     */
    public function lookupConsumer($provider)
    {
        // check $provider->consumer_key
        // on unknown: Zend_Oauth_Provider::CONSUMER_KEY_UNKNOWN
        // on bad key: Zend_Oauth_Provider::CONSUMER_KEY_REFUSED
        $GLOBALS['log']->debug("OAUTH: lookupConsumer, key={$provider->consumer_key}");
        $consumer = OAuthKey::fetchKey($provider->consumer_key);
        if(!$consumer) {
            return Zend_Oauth_Provider::CONSUMER_KEY_UNKNOWN;
        }
        $provider->consumer_secret = $consumer->c_secret;
        $this->consumer = $consumer;
        return Zend_Oauth_Provider::OK;
    }

    /**
     * Check timestamps & nonces
     * @param OAuthProvider $provider
     */
    public function timestampNonceChecker($provider)
    {
        // FIXME: add ts/nonce verification
        if(empty($provider->nonce)) {
            return Zend_Oauth_Provider::BAD_NONCE;
        }
        if(empty($provider->timestamp)) {
            return Zend_Oauth_Provider::BAD_TIMESTAMP;
        }
        return OAuthToken::checkNonce($provider->consumer_key, $provider->nonce, $provider->timestamp);
    }

    /**
     * Vefiry incoming token
     * @param OAuthProvider $provider
     */
    public function tokenHandler($provider)
    {
        $GLOBALS['log']->debug("OAUTH: tokenHandler, token={$provider->token}, verify={$provider->verifier}");

        $token = OAuthToken::load($provider->token);
        if(empty($token)) {
            return Zend_Oauth_Provider::TOKEN_REJECTED;
        }
        if($token->consumer != $this->consumer->id) {
            return Zend_Oauth_Provider::TOKEN_REJECTED;
        }
        $GLOBALS['log']->debug("OAUTH: tokenHandler, found token=".var_export($token->id, true));
        if($token->tstate == OAuthToken::REQUEST) {
            if(!empty($token->verify) && $provider->verifier == $token->verify) {
                $provider->token_secret = $token->secret;
                $this->token = $token;
                return Zend_Oauth_Provider::OK;
            } else {
                return Zend_Oauth_Provider::TOKEN_USED;
            }
        }
        if($token->tstate == OAuthToken::ACCESS) {
            $provider->token_secret = $token->secret;
            $this->token = $token;
            return Zend_Oauth_Provider::OK;
        }
        return Zend_Oauth_Provider::TOKEN_REJECTED;
    }

    /**
     * Decode POST/GET via from_html()
     * @return array decoded data
     */
    protected function decodePostGet()
    {
        $data = $_GET;
        $data = array_merge($data, $_POST);
        foreach($data as $k => $v) {
            $data[$k] = from_html($v);
        }
        return $data;
    }

    /**
     * Create OAuth provider
     *
     * Checks current request for OAuth valitidy
     * @param bool $add_rest add REST endpoint as request path
     */
    public function __construct($req_path = '')
    {
        $GLOBALS['log']->debug("OAUTH: __construct($req_path): ".var_export($_REQUEST, true));
        $this->check();
        $this->provider = new Zend_Oauth_Provider();
        try {
		    $this->provider->setConsumerHandler(array($this,'lookupConsumer'));
		    $this->provider->setTimestampNonceHandler(array($this,'timestampNonceChecker'));
		    $this->provider->setTokenHandler(array($this,'tokenHandler'));
	        if(!empty($req_path)) {
		        $this->provider->setRequestTokenPath($req_path);  // No token needed for this end point
	        }
	    	$this->provider->checkOAuthRequest(null, $this->decodePostGet());
	    	if(mt_rand() % 10 == 0) {
	    	    // cleanup 1 in 10 times
	    	    OAuthToken::cleanup();
	    	}
        } catch(Exception $e) {
            $GLOBALS['log']->debug($this->reportProblem($e));
            throw $e;
        }
    }

    /**
     * Generate request token string
     * @return string
     */
    public function requestToken()
    {
        $GLOBALS['log']->debug("OAUTH: requestToken");
        $token = OAuthToken::generate();
        $token->setConsumer($this->consumer);
        $params = $this->provider->getOAuthParams();
        if(!empty($params['oauth_callback']) && $params['oauth_callback'] != 'oob') {
            $token->setCallbackURL($params['oauth_callback']);
        }
        $token->save();
        return $token->queryString();
    }

    /**
     * Generate access token string - must have validated request token
     * @return string
     */
    public function accessToken()
    {
        $GLOBALS['log']->debug("OAUTH: accessToken");
        if(empty($this->token) || $this->token->tstate != OAuthToken::REQUEST) {
            return null;
        }
        $this->token->invalidate();
        $token = OAuthToken::generate();
        $token->setState(OAuthToken::ACCESS);
        $token->setConsumer($this->consumer);
        // transfer user data from request token
        $token->copyAuthData($this->token);
        $token->save();
        return $token->queryString();
    }

    /**
     * Return authorization URL
     * @return string
     */
    public function authUrl()
    {
        return urlencode(rtrim($GLOBALS['sugar_config']['site_url'],'/')."/index.php?module=OAuthTokens&action=authorize");
    }

    /**
     * Fetch current token if it is authorized
     * @return OAuthToken|null
     */
    public function authorizedToken()
    {
        if($this->token->tstate == OAuthToken::ACCESS) {
            return $this->token;
        }
        return null;
    }

    /**
     * Fetch authorization data from current token
     * @return mixed Authorization data or null if none
     */
    public function authorization()
    {
        if($this->token->tstate == OAuthToken::ACCESS) {
            return $this->token->authdata;
        }
        return null;
    }

    /**
     * Report OAuth problem as string
     */
    public function reportProblem(Exception $e)
    {
        return $this->provider->reportProblem($e);
    }
}

if (!class_exists('OAuthException', false)) {
    // we will use this in case oauth extension is not loaded
    class OAuthException extends Exception {}
}
