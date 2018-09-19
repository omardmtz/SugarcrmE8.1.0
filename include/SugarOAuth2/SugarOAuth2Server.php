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

use Sugarcrm\Sugarcrm\Util\Uuid;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;

/**
 * Sugar OAuth2.0 server, is a wrapper around the php-oauth2 library
 * @api
 */
class SugarOAuth2Server extends OAuth2
{
    // Maximum length of the session after which new login if required
    // and refresh tokens are not allowed
    const CONFIG_MAX_SESSION = 'max_session_lifetime';

    /**
     * @var SugarOAuth2Server
     */
    protected static $currentOAuth2Server = null;

    /**
     * This function will return the OAuth2Server class, it will check
     * the custom/ directory so users can customize the authorization
     * types and storage
     *
     * @param string $platform
     *
     * @return SugarOAuth2Server
     */
    public static function getOAuth2Server($platform = null)
    {
        if (!isset(static::$currentOAuth2Server)) {
            $idpConfig = new Config(\SugarConfig::getInstance());
            $isIDMModeEnabled = $idpConfig->isIDMModeEnabled() && $platform != SugarOAuth2ServerOIDC::PORTAL_PLATFORM;
            $oidcPostfix = $isIDMModeEnabled ? 'OIDC' : '';
            SugarAutoLoader::requireWithCustom('include/SugarOAuth2/SugarOAuth2Storage'.$oidcPostfix.'.php');
            $oauthStorageName = SugarAutoLoader::customClass('SugarOAuth2Storage'.$oidcPostfix);
            $oauthStorage = new $oauthStorageName();

            SugarAutoLoader::requireWithCustom('include/SugarOAuth2/SugarOAuth2Server'.$oidcPostfix.'.php');
            $oauthServerName = SugarAutoLoader::customClass('SugarOAuth2Server'.$oidcPostfix);
            $config = $idpConfig->get('oauth2', []);
            static::$currentOAuth2Server = new $oauthServerName($oauthStorage, $config);
            if ($isIDMModeEnabled) {
                static::$currentOAuth2Server->setPlatform($platform);
            }
        }

        return static::$currentOAuth2Server;
    }

    protected function createAccessToken($client_id, $user_id, $scope = null)
    {
        $timeLimit = $this->getVariable(self::CONFIG_MAX_SESSION);
        // If we have session time limit, then:
        // 1. We limit time for initial refresh token to session length
        // 2. We inherit this time limit for subsequent refresh tokens
        if ($timeLimit) {
            // enforce session length limits
            if ($this->oldRefreshToken) {
                // inherit expiration from the old token
                $tokenSeed = BeanFactory::newBean('OAuthTokens');
                $token = $tokenSeed->load($this->oldRefreshToken, 'oauth2');
                $this->setVariable(self::CONFIG_REFRESH_LIFETIME, $token->expire_ts - time());
            } else {
                $this->setVariable(self::CONFIG_REFRESH_LIFETIME, $timeLimit);
            }
        }
        return parent::createAccessToken($client_id, $user_id, $scope);
    }

    /**
     * Sets up visibility where needed
     */
    public function setupVisibility()
    {
        $this->storage->setupVisibility();
    }

    /**
     * Sets the platform for the storage handler
     *
     * @param string $platform
     */
    public function setPlatform($platform)
    {
        $this->storage->setPlatform($platform);
    }

    /**
     * Generates an unique access token.
     *
     * Implementing classes may want to override this function to implement
     * other access token generation schemes.
     *
     * @return
     * An unique access token.
     *
     * @ingroup oauth2_section_4
     * @see OAuth2::genAuthCode()
     */
    protected function genAccessToken()
    {
        return Uuid::uuid4();
    }

    /**
     * This starts output buffering so the returned data is actual data instead
     * of raw JSON-encoded stuff.
     * @see OAuth2::grantAccessToken()
     */
    public function grantAccessToken(array $inputData = NULL, array $authHeaders = NULL)
    {
        // grantAccessToken directly echo's (BAD), but it's a 3rd party library, so what are you going to do?
        $authData = parent::grantAccessToken($inputData, $authHeaders);

        $token = $this->storage->refreshToken;
        $downloadToken = $token->download_token;

        $authData['refresh_expires_in'] = $token->expire_ts-time();
        $authData['download_token'] = $token->download_token;

        if (!empty($_SESSION['oauth2']['client_id']) && !empty($token->id)) {
            $_SESSION['oauth2']['refresh_token'] = $token->id;
            // PHP parser barfs on $this->storage::TOKEN_CHECK_TIME
            $storage = $this->storage;
            $_SESSION['oauth2']['token_check_time'] = time() + $storage::TOKEN_CHECK_TIME;
        }



        return $authData;
    }

    /**
     * This function verifies download tokens, these are limited use tokens
     * that will only be used if the specified API allows it
     * @param $token The download token
     * @throws OAuth2AuthenticateException
     */
    public function verifyDownloadToken($token)
    {
        // Flag this so the storage system uses a different method to get the access token
        $this->storage->isDownloadToken = true;
        return $this->verifyAccessToken($token);
    }

    /**
     * Gets an access token via sudo
     *
     * Will modify the session to add in additional information about
     * who requested the sudo
     *
     * @param string $userName The user name (or email address for portal sudo)
     * @param string $clientId The client id for the access token
     * @param string $platform Which platform to log this user in as
     *
     * @return string The token
     */
    public function getSudoToken($userName, $clientId, $platform)
    {
        $sudoUserId = $GLOBALS['current_user']->id;

        $this->setPlatform($platform);

        $user = null;
        try {
            $user = $this->storage->loadUserFromName($userName);
        } catch (\SugarApiExceptionNeedLogin $e) {
        }

        if ($user == null) {
            throw new SugarApiExceptionNotFound();
        }

        $token = $this->createAccessToken($clientId, $user->id);
        $_SESSION['sudo_for'] = $sudoUserId;

        // It's a bit silly to create and then destroy a refresh token,
        // But the oauth2 library doesn't let us pass enough through to skip that part.
        $this->storage->unsetRefreshToken($token['refresh_token']);
        unset($token['refresh_token']);

        return $token;
    }

    /**
     * Destroy refresh token
     * @param string $token
     */
    public function unsetRefreshToken($token)
    {
        $this->storage->unsetRefreshToken($token);
    }
}
