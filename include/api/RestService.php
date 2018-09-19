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

use Sugarcrm\Sugarcrm\Logger\Factory as LoggerFactory;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Security\Context;
use Sugarcrm\Sugarcrm\Security\Subject\User;
use Sugarcrm\Sugarcrm\Security\Subject\ApiClient\Rest as RestApiClient;

/** @noinspection PhpInconsistentReturnPointsInspection */
class RestService extends ServiceBase
{
    /**
     * X-Header containging the clients metadata hash
     */
    const HEADER_META_HASH = "X_METADATA_HASH";
    const USER_META_HASH = 'X_USERPREF_HASH';
    const DOWNLOAD_COOKIE = 'download_token';

    public $user;
    /**
     * The request headers
     * @var array
     */

    public $request_headers = array();

    public $platform = 'base';

    /**
     * The response headers that will be sent
     * @var RestResponse
     */
    protected $response = null;

    /**
     * The minimum version accepted
     * @var string
     */
    protected $min_version = '10';

    /**
     * The maximum version accepted
     * @var string
     */
    protected $max_version = '11.1';

    /**
     * An array of api settings
     * @var array
     */
    public $api_settings = array();

    /**
     * The acl action attempting to be run
     * @var string
     */
    public $action = 'view';

    /**
     * Request object
     * @var RestRequest
     */
    protected $request;

    /**
     * Get request object
     * @return RestRequest
     */
    public function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = new RestRequest($_SERVER, $_REQUEST);
        }
        return $this->request;
    }

    /**
     * Headers that have special meaning for API and should be imported into args
     * @var array
     */
    public $special_headers = array("X_TIMESTAMP");

    /**
     * Get response object
     * @return RestResponse
     */
    public function getResponse()
    {
        if (!isset($this->response)) {
            $this->response = new RestResponse($_SERVER);
        }
        return $this->response;
    }

    /**
     * Creates the RestService object and reads in the metadata for the API
     */
    public function __construct()
    {
        $apiSettings = array();
        require 'include/api/metadata.php';
        if (file_exists('custom/include/api/metadata.php')) {
            // Don't use requireWithCustom because we need the data out of it
            require 'custom/include/api/metadata.php';
        }

        $this->min_version = $apiSettings['minVersion'];
        $this->max_version = $apiSettings['maxVersion'];
        $this->api_settings = $apiSettings;

        $this->setLogger(LoggerFactory::getLogger('rest'));
    }

    /**
     * This function executes the current request and outputs the response directly.
     */
    public function execute()
    {
        $this->response = $this->getResponse();
        try {
            $this->request = $this->getRequest();
            $this->request_headers = $this->request->getRequestHeaders();

            // invalid if the request version is out of supported version range
            if (!$this->checkVersionSupport($this->getVersion(), $this->min_version, $this->max_version)) {
                throw new SugarApiExceptionIncorrectVersion(
                    "Please change your requested API version to value " .
                    "between {$this->min_version} and {$this->max_version}."
                );
            }

            $authenticateUser = $this->authenticateUser();

            $isLoggedIn = $authenticateUser['isLoggedIn'];
            $loginException = $authenticateUser['exception'];

            $context = Container::getInstance()->get(Context::class);
            $subject = new RestApiClient();

            // Figure out the platform
            if ($isLoggedIn) {
                if ( isset($_SESSION['platform']) ) {
                    $this->platform = $_SESSION['platform'];
                }

                $subject = new User($GLOBALS['current_user'], $subject);
            } else {
                // Since we don't have a session we have to allow the user to specify their platform
                // However, since the results from the same URL will be different with
                // no variation in the oauth_token header we need to only take it as a GET request.
                if ( !empty($_GET['platform']) ) {
                    $this->platform = basename($_GET['platform']);
                }
            }

            $context->activateSubject($subject);
            $context->setAttribute('platform', $this->platform);

            $this->validatePlatform($this->platform);
            $this->request->setPlatform($this->platform);

            $GLOBALS['logic_hook']->call_custom_logic('', 'before_routing', array("api" => $this, "request" => $this->request));

            $route = $this->findRoute($this->request);

            if ($route == false) {
                throw new SugarApiExceptionNoMethod('Could not find any route that accepted a path like: '.$this->request->rawPath);
            }

            $this->request->setRoute($route);
            $GLOBALS['logic_hook']->call_custom_logic('', 'after_routing', array("api" => $this, "request" => $this->request));
            // Get it back in case hook changed it
            $route = $this->request->getRoute();

            if (empty($route['keepSession'])) {
                $this->releaseSession();
            }

            // Get the request args early for use in current user api
            $argArray = $this->getRequestArgs($route);
            if ( !$isLoggedIn && !empty($route['allowDownloadCookie'])) {
                $isLoggedIn = $this->authenticateUserForDownload();
            }

            // Make sure the system is ready for them
            // This section of code is a portion of the code referred
            // to as Critical Control Software under the End User
            // License Agreement.  Neither the Company nor the Users
            // may modify any portion of the Critical Control Software.
            $systemStatus = apiCheckSystemStatus();
            if ($systemStatus !== true
                && $systemStatus['level'] != 'warning'
                && !($systemStatus['level'] == 'maintenance' && isset($this->user) && $this->user->isAdmin())
                && empty($route['ignoreSystemStatusError'])) {
                // The system is unhappy and the route isn't flagged to let them through
                $e = new SugarApiExceptionMaintenance($systemStatus['message'], null, null, 0, $systemStatus['level']);
                $e->setExtraData("url", $systemStatus['url']);
                throw $e;
            }
            //END REQUIRED CODE DO NOT MODIFY

            if ( !isset($route['noLoginRequired']) || $route['noLoginRequired'] == false ) {
                if (!$isLoggedIn) {
                    if (!$loginException) {
                        $this->needLogin();
                    } else {
                        throw $loginException;
                    }
                } else if (empty($route['ignoreMetaHash'])) {
                    // Check metadata hash state and return an error to force a
                    // resync so that the new metadata gets picked up if it is
                    // out of date
                    if (!$this->isMetadataCurrent()) {
                        // Mismatch in hashes... Return error so the client will
                        // resync its metadata and try again.
                        throw new SugarApiExceptionInvalidHash();
                    }
                }
            }

            if ($isLoggedIn) {
                // This is needed to load in the app_strings and the app_list_strings and the such
                $this->loadUserEnvironment();
            } else {
                $this->loadGuestEnvironment();
            }

            $headers = array();
            foreach ($this->special_headers as $header) {
                if(isset($this->request_headers[$header])) {
                    $headers[$header] = $this->request_headers[$header];
                }
            }
            if(!empty($headers)) {
                $argArray['_headers'] = $headers;
            }

            $this->request->setArgs($argArray)->setRoute($route);
            $GLOBALS['logic_hook']->call_custom_logic('', 'before_api_call', array("api" => $this, "request" => $this->request));
            // Get it back in case hook changed it
            $route = $this->request->getRoute();
            $argArray = $this->request->getArgs();

            // Trying to fetch correct module while API use search
            $module = $route['className'];

            if (isset($argArray['module'])) {
                $module = $argArray['module'];
            } elseif (isset($argArray['module_list'])) {
                $module = $argArray['module_list'];
            }

            SugarMetric_Manager::getInstance()->setTransactionName('rest_' . $module . '_' . $route['method']);

            $apiClass = $this->loadApiClass($route);
            $apiMethod = $route['method'];

            $this->response->setContent($apiClass->$apiMethod($this,$argArray));

            $this->respond($route, $argArray);
        } catch ( Exception $e ) {
            $this->handleException($e);
        }

        if (isset($context, $subject)) {
            $context->deactivateSubject($subject);
        }

        // last chance for hooks to mess with the response
        $GLOBALS['logic_hook']->call_custom_logic('', "before_respond", $this->response);
        $this->response->send();
    }

    /**
     * checks if version is within min,max versions
     * @param string $version version to check
     * @param string $minVersion
     * @param string $maxVersion
     *
     * @return boolean TRUE if $minVersion <= $version <= $maxVersion
     */
    protected function checkVersionSupport($version, $minVersion, $maxVersion)
    {
        return (version_compare($minVersion, $version, '<=')
        && version_compare($version, $maxVersion, '<='));
    }

    /**
     * to get site url string
     *
     * @return url string
     */
    protected function getSiteUrl()
    {
        return SugarConfig::getInstance()->get('site_url');
    }

    /**
     * Gets the leading portion of the URI for a resource
     *
     * @param array|string $resource The resource to fetch a URI for as an array
     *                               of path parts or as a string
     * @return string The path to the resource
     */
    public function getResourceURI($resource, $options = array())
    {
        $this->setResourceURIBase($options);

        // Empty resources are simply the URI for the current request
        if (empty($resource)) {
            $siteUrl = $this->getSiteUrl();
            return $siteUrl . (empty($this->request)?$_SERVER['REQUEST_URI']:$this->request->getRequestURI());
        }

        if (is_string($resource)) {
            // split string into path parts
            return $this->getResourceURI(explode('/', $resource));
        } elseif (is_array($resource)) {
            $req = $this->getRequest();
            // Hacky - we're not supposed to mess with this normally, but we need it to set up route
            $req->path = $resource;
            // Logic here is, if we find a GET route for this resource then it
            // should be valid. In most cases, where there is a POST|PUT|DELETE
            // route that does not have a GET, we're not going to be handing that
            // URI out anyway, so this is a safe validation assumption.
            $req->setMethod('GET');
            $route = $this->findRoute($req);
            if ($route != false) {
                $url = $this->resourceURIBase;
                if (isset($options['relative']) && $options['relative'] == false) {
                    $url = $req->getResourceURIBase($this->getUrlVersion());
                }
                return $url . implode('/', $resource);
            }
        }

        return '';
    }

    /**
     * get version from RestRequest, get the request version from Request obj
     * @return string|null
     */
    public function getVersion()
    {
        return $this->getRequest()->getVersion();
    }

    /**
     * get Url version from RestRequest, get the request version from Request obj
     * @return string|null
     */
    public function getUrlVersion()
    {
        return $this->getRequest()->getUrlVersion();
    }

    /**
     * For cases in which HTML is the requested response type but json is the
     * intended body content, this returns an array of status code and message.
     * This will also be used by the exception handler when dispatching exceptions
     * under the same requested response type conditions.
     *
     * @param  string $message
     * @param  int    $code
     * @return array
     */
    public function getHXRReturnArray($message, $code = 200)
    {
        return array(
            'xhr' => array(
                'status' => $code,
                'responseText' => $message,
                // "code" and "message" are deprecated keys left for backward compatibility
                'code' => $code,
                'message' => $message,
            ),
        );
    }

    /**
     * Attempts to find the route for this request, API version and request method
     *
     * @param  RestRequest $req REST request data
     * @return mixed
     */
    public function findRoute(RestRequest $req)
    {
        // Load service dictionary
        $this->dict = $this->loadServiceDictionary('ServiceDictionaryRest');

        return $this->dict->lookupRoute($req->path, $this->getVersion(), $req->method, $req->platform);
    }

    /**
     * Handles exception responses
     *
     * @param Exception $exception
     */
    protected function handleException(Exception $exception)
    {
        $GLOBALS['logic_hook']->call_custom_logic('', "handle_exception", $exception);
        if ( is_a($exception,"SugarApiException") ) {
            $httpError = $exception->getHttpCode();
            $errorLabel = $exception->getErrorLabel();
            $message = $exception->getMessage();
        } elseif ( is_a($exception,"OAuth2ServerException") ) {
            //The OAuth2 Server uses a slightly different exception API
            $httpError = $exception->getHttpCode();
            $errorLabel = $exception->getMessage();
            $message = $exception->getDescription();
        } else {
            $httpError = 500;
            $errorLabel = 'unknown_error';
            $message = $exception->getMessage();
        }
        if (!empty($exception->extraData)) {
            $data = $exception->extraData;
        }
        $this->response->setStatus($httpError);

        $GLOBALS['log']->error('An exception happened: ( '.$httpError.': '.$errorLabel.')'.$message);
        // For edge cases when an HTML response is needed as a wrapper to JSON
        if (isset($_REQUEST['format']) && $_REQUEST['format'] == 'sugar-html-json') {
            $this->response->setType(RestResponse::JSON_HTML, true);
        } else {
            // Send proper headers
            $this->response->setType(RestResponse::JSON, true);
        }

        $this->response->setHeader("Cache-Control", "no-store");

        $replyData = array(
            'error'=>$errorLabel,
        );
        if ($errorLabel === 'metadata_out_of_date') {
            $mM = $this->getMetadataManager();
            // In case of a `metadata_out_of_date` error, return the current
            // valid metadata hash so the client knows if it is worth
            // re-syncing.
            $replyData['metadata_hash'] = $mM->getMetadataHash();
            $replyData['user_hash'] = $this->user->getUserMDHash();
        }
        if ( !empty($message) ) {
            $replyData['error_message'] = $message;
        }
        if(!empty($data)) {
            $replyData = array_merge($replyData, $data);
        }

        $this->response->setContent($replyData);
    }

    /**
     * Handles authentication of the current user
     *
     * @param string $platform The platform type for this request
     * @returns bool Was the login successful
     * @throws SugarApiExceptionRequestTooLarge gets thrown on file uploads if the request failed
     */
    protected function authenticateUser()
    {
        $valid = false;

        $token = $this->grabToken();

        $platform = !empty($_REQUEST['platform']) ? $_REQUEST['platform'] : 'base';
        if ( !empty($token) ) {
            try {
                $oauthServer = \SugarOAuth2Server::getOAuth2Server($platform);
                $oauthServer->verifyAccessToken($token);
                if (isset($_SESSION['authenticated_user_id'])) {
                    $authController = AuthenticationController::getInstance();
                    // This will return false if anything is wrong with the session
                    // (mismatched IP, mismatched unique_key, etc)
                    $valid = $authController->apiSessionAuthenticate();

                    if ($valid) {
                        $valid = $this->userAfterAuthenticate($_SESSION['authenticated_user_id'], $oauthServer);
                    }
                    if (!$valid) {
                        // Need to populate the exception here so later code
                        // has it and can send the correct status back to the client
                        $e = new SugarApiExceptionInvalidGrant();
                    }
                }
            } catch ( OAuth2AuthenticateException $e ) {
                // This was failing if users were passing an oauth token up to a public url.
                $valid = false;
            } catch ( SugarApiException $e ) {
                // If we get an exception during this we'll assume authentication failed
                $valid = false;
            }
        }

        if (!$valid) {
            // If token is invalid, clear the session for bwc
            // It looks like a big upload can cause no auth error,
            // so we do it here instead of the catch block above
            $_SESSION = array();
            $exception = (isset($e)) ? $e : false;

            return array('isLoggedIn' => false, 'exception' => $exception);
        }

        return array('isLoggedIn' => true, 'exception' => false);
    }

    /**
     * Looks in all the various nooks and crannies and attempts to find an authentication header
     *
     * @returns string The oauth token
     */
    protected function grabToken()
    {
        // Bug 61887 - initial portal load dies with undefined variable error
        // Initialize the return var in case all conditionals fail
        $sessionId = '';

        $allowGet = (bool) SugarConfig::getInstance()->get('allow_oauth_via_get', false);

        if ( isset($_SERVER['HTTP_OAUTH_TOKEN']) ) {
            // Passing a session id claiming to be an oauth token
            $sessionId = $_SERVER['HTTP_OAUTH_TOKEN'];
        } elseif ( isset($_POST['oauth_token']) ) {
            $sessionId = $_POST['oauth_token'];
        } elseif ($allowGet && !empty($_GET['oauth_token'])) {
            $sessionId = $_GET['oauth_token'];
        } elseif ( isset($_POST['OAuth-Token']) ) {
            $sessionId = $_POST['OAuth-Token'];
        } elseif ($allowGet && !empty($_GET['OAuth-Token'])) {
            $sessionId = $_GET['OAuth-Token'];
        } elseif ( function_exists('apache_request_headers') ) {
            // Some PHP implementations don't populate custom headers by default
            // So we have to go for a hunt
            $headers = apache_request_headers();
            foreach ($headers as $key => $value) {
                // Check for oAuth 2.0 header
                if ($token = $this->getOAuth2AccessToken($key, $value)) {
                    $sessionId = $token;
                    break;
                }
                $check = strtolower($key);
                if ( $check == 'oauth_token' || $check == 'oauth-token') {
                    $sessionId = $value;
                    break;
                }
            }
        }

        return $sessionId;
    }

    /**
     * Check oAuth 2.0 header
     * @param $header
     * @param $value
     * @return string
     */
    protected function getOAuth2AccessToken($header, $value)
    {
        $token = false;
        $platform = !empty($_REQUEST['platform']) ? $_REQUEST['platform'] : 'base';
        $config = SugarConfig::getInstance()->get('idm_mode', false);
        $preCheck = is_array($config) && $platform == 'opi' && $header == 'Authorization';

        if ($preCheck && preg_match('~^Bearer (.*)$~i', $value, $matches)) {
            $token = $matches[1];
        }
        return $token;
    }

    /**
     * Handles authentication of the current user from the download token
     *
     * @param string $token The download autentication token.
     * @param string $platform the platform for the download
     * @returns bool Was the login successful
     */
    protected function authenticateUserForDownload()
    {
        $valid = false;

        // Find the token
        if (!isset($_GET['platform'])) {
            return false;
        }

        $platform = $_GET['platform'];

        if (isset($_GET[self::DOWNLOAD_COOKIE])) {
            $token = $_GET[self::DOWNLOAD_COOKIE];
        } else if (isset($_COOKIE[self::DOWNLOAD_COOKIE.'_'.$platform])) {
            $token = $_COOKIE[self::DOWNLOAD_COOKIE.'_'.$platform];
        }

        if (!empty($token)) {
            $oauthServer = SugarOAuth2Server::getOAuth2Server();
            $oauthServer->setPlatform($platform);

            $tokenData = $oauthServer->verifyDownloadToken($token);

            $GLOBALS['current_user'] = BeanFactory::getBean('Users',$tokenData['user_id']);
            $valid = $this->userAfterAuthenticate($tokenData['user_id'], $oauthServer, true);
        }

        return $valid;
    }

    /**
     * Sets up a user after successful authentication and session setup
     *
     * @returns bool Was the login successful
     */
    protected function userAfterAuthenticate($userId, $oauthServer, $forDownload = false)
    {
        $valid = false;

        if(!empty($GLOBALS['current_user'])) {
            $valid = true;
            $GLOBALS['logic_hook']->call_custom_logic('', 'after_load_user');
        }

        if ($GLOBALS['current_user']->status == 'Inactive'
            || $GLOBALS['current_user']->deleted == true) {
            $valid = false;
        }

        if ($valid) {
            if (!$forDownload) {
                SugarApplication::trackSession();
            }

            // Setup visibility where needed
            $oauthServer->setupVisibility();

            LogicHook::initialize()->call_custom_logic('', 'after_session_start');

            $this->user = $GLOBALS['current_user'];
        }

        return $valid;
    }

    /**
     * Sets the proper Content-Type header for the response based on either a
     * 'format' request arg or an Accept header.
     *
     * @TODO Handle Accept header parsing to determine content type
     * @access protected
     * @param array $args The request arguments
     */
    protected function setContentTypeHeader($args)
    {
        if (isset($args['format']) && $args['format'] == 'sugar-html-json') {
        } else {
            // @TODO: Handle other response types here
        }
    }

    /**
     * Sets the response type for the client
     *
     * @TODO Handle proper content disposition based on response content type
     * @TODO gzip, and possibly XML based output
     * @param array $args The request arguments
     */
    protected function setResponseType($args)
    {
        //Removed platform checking; we should honor special format requests for all platforms
        if (isset($args['format']) && $args['format'] == 'sugar-html-json') {
            $this->response->setType(RestResponse::JSON_HTML);
        } else {
            $this->response->setType(RestResponse::JSON);
        }
    }

    /**
     * Set a response header
     * @param  string $header
     * @param  string $info
     * @return bool
     */
    public function setHeader($header, $info)
    {
        if (empty($this->response)) {
            return false;
        }

        return $this->response->setHeader($header, $info);
    }

    /**
     * Check if the response headers have a header set
     * @param  string $header
     * @return bool
     */
    public function hasHeader($header)
    {
        if (empty($this->response)) {
            return false;
        }

        return $this->response->hasHeader($header);
    }

    /**
     * Send the response headers
     * @return bool
     */
    public function sendHeaders()
    {
        if (empty($this->response)) {
            return false;
        }

        return $this->response->sendHeaders();
    }

    /**
     * Sets the leading portion of any request URI for this API instance
     *
     * @access protected
     */
    protected function setResourceURIBase($options = array())
    {
        // Only do this if it hasn't been done already
        if (empty($this->resourceURIBase)) {
            // Default the base part of the request URI
            $apiBase = 'api/rest.php/';

            // Check rewritten URLs AND request uri vs script name
            if (isset($_REQUEST['__sugar_url']) && strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) === false) {
                // This is a forwarded rewritten URL
                $apiBase = 'rest/';
            }

            // using version to get right url base
            $apiBase .= $this->getUrlVersion();

            // This is for our URI return value
            $siteUrl = '';
            if (isset($options['relative']) && $options['relative'] == false) {
                $siteUrl = $this->getSiteUrl();
            }

            // Get the file uri base
            $this->resourceURIBase = $siteUrl . $apiBase . '/';
        }
    }

    /**
     * Handles the response
     *
     * @param array $route  The route for this request
     * @param array  $args   The request arguments
     *
     * @return void
     */
    protected function respond($route, $args)
    {
        $method = $this->request->getMethod();
        if ($method == 'GET' && empty($route['noEtag'])) {
            //Only cache the response in the browser if the Api opts in
            $cacheAge = empty($route['cacheEtag']) ? 0 : null;
            $this->response->generateETagHeader(null, $cacheAge);
        }

        //leaving this logic split out in case more actions on rawreply need added in the future
        if (!empty($route['rawReply'])) {
            if ($method == 'POST') {
                $this->response->setPostHeaders();
            }
        } else {
            $this->setResponseType($args);
        }
    }

    /**
     * Generate suitable ETag for content
     *
     * This function generates the necessary cache headers for using ETags with dynamic content. You
     * simply have to generate the ETag, pass it in, and the function handles the rest.
     *
     * @param  string $etag ETag to use for this content.
     * @param int $cache_age age in seconds for Cache-control max-age header
     * @return bool   Did we have a match?
     */
    public function generateETagHeader($etag, $cache_age = null)
    {
        if (empty($this->response)) {
            return false;
        }

        return $this->response->generateETagHeader($etag, $cache_age);
    }

    /**
     * Set response to be read from file
     */
    public function fileResponse($filename)
    {
        if (empty($this->response)) {
            return false;
        }
        $this->response->setType(RestResponse::FILE)->setFilename($filename);
        $this->response->setHeader("Pragma", "public");
        $this->response->setHeader("Cache-Control", "max-age=1, post-check=0, pre-check=0");
        $this->response->setHeader("X-Content-Type-Options", "nosniff");
    }

    /**
     * Inject response object
     * @param RestResponse $resp
     */
    public function setResponse(RestResponse $resp)
    {
        $this->response = $resp;

        return $this;
    }

    /**
     * Inject request object
     * @param RestResponse $resp
     */
    public function setRequest(RestRequest $req)
    {
        $this->request = $req;

        return $this;
    }

    /**
     * Gets the full collection of arguments from the request
     *
     * @param  array $route The route description for this request
     * @return array
     */
    protected function getRequestArgs($route)
    {
        // This loads the path variables in, so that on the /Accounts/abcd, $module is set to Accounts, and $id is set to abcd
        $pathVars = $this->request->getPathVars($route);

        $getVars = $this->request->getQueryVars();
        if (!empty($getVars)) {
            // This has some get arguments, let's parse those in
            if (!empty($route['jsonParams'])) {
                foreach ($route['jsonParams'] as $fieldName) {
                    if (!empty($getVars[$fieldName])
                        && is_string($getVars[$fieldName])
                        && isset($getVars[$fieldName]{0})
                        && ($getVars[$fieldName]{0} == '{'
                            || $getVars[$fieldName]{0} == '[')) {
                        // This may be JSON data
                        $jsonData = @json_decode($getVars[$fieldName],true,32);
                        if (json_last_error() !== 0) {
                            // Bad JSON data, throw an exception instead of trying to process it
                            throw new SugarApiExceptionInvalidParameter();
                        }
                        // Need to dig through this array and make sure all of the elements in here are safe
                        $getVars[$fieldName] = $jsonData;
                    }
                }
            }
        }

        $postVars = array();
        if ( isset($route['rawPostContents']) && $route['rawPostContents'] ) {
            // This route wants the raw post contents
            // We just ignore it here, the function itself has to know how to deal with the raw post contents
            // this will mostly be used for binary file uploads.
        } else if ( !empty($_POST) ) {
            // They have normal post arguments
            $postVars = $_POST;
        } else {
            $postContents = $this->request->getPostContents();
            if ( !empty($postContents) ) {
                // BR-2916 Bulk API doesn't support requests containing body
                // handling content body which has already been json decoded
                if (is_array($postContents)) {
                    $postVars = $postContents;
                }
                else {
                    // This looks like the post contents are JSON
                    // Note: If we want to support rest based XML, we will need to change this

                    $postVars = @json_decode($postContents, true, 32);
                    if (json_last_error() !== 0) {
                        // Bad JSON data, throw an exception instead of trying to process it
                        throw new SugarApiExceptionInvalidParameter();
                    }
                }
            }
        }

        // I know this looks a little weird, overriding post vars with get vars, but
        // in the case of REST, get vars are fairly uncommon and pretty explicit, where
        // the posted document is probably the output of a generated form.
        return array_merge($postVars,$getVars,$pathVars);
    }

    /**
     * Verifies state of the metadata so the API can determine if there needs to
     * be an invalid metadata response issued
     *
     * @return boolean
     */
    protected function isMetadataCurrent()
    {
        // Default expectation is that metadata is current. This also covers the
        // case of the metadata hash headers not being sent, which would always
        // assume that the metadata is current.
        $return = true;

        // If the metadata hash header was sent in the request, use it to compare
        // the current metadata hash to see if the current hash is valid
        if (isset($this->request_headers[self::HEADER_META_HASH])) {
            $mm = $this->getMetadataManager();
            $return = $mm->isMetadataHashValid($this->request_headers[self::HEADER_META_HASH]);
        }

        // If the user metadata hash header was sent, use it to compare against
        // the current user's preferences change state
        //
        // Only check user metadata if system metadata has passed
        if ($return && isset($this->request_headers[self::USER_META_HASH])) {
            // Metadata manager may have already been set. If not though, get it
            if (empty($mm)) {
                $mm = $this->getMetadataManager();
            }

            $return = !$mm->hasUserMetadataChanged($this->user, $this->request_headers[self::USER_META_HASH]);
        }

        return $return;
    }

    /**
     * Gets the metadata manager for this user and platform
     *
     * @return MetaDataManager
     */
    protected function getMetadataManager()
    {
        return MetaDataManager::getManager(array($this->platform));
    }
}
