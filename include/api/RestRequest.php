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
 * REST request representation
 * @api
 */
class RestRequest
{
    /**
     * request version pattern, for getting the API version
     */
    const VERSION_PATTERN = '/^\d\d(\.\d{1,2})?$/';
    const PATH_VERSION_PATTERN = '/^v\d\d(_\d{1,2})?$/';

    /**
     * request version pattern in Accept header
     * sample accept header: application/vnd.sugarcrm.core+json; version=11.3
     */
    const ACCEPT_HEADER_VERSION_PATTERN =
        '/^application\/vnd\.sugarcrm\.core(\+?(xml|json))?\s*\;\s*version=([^;,\s]+)/';

    /**
     * The request headers
     * @var array
     */
    protected $request_headers = array();

    /**
     * REST platform
     * @var string
     */
    public $platform = 'base';
    /**
     * Server variables
     * @var array
     */
    public $server;
    /**
     * Request variables
     * @var array
     */
    public $request;
    /**
     * Raw path string
     * @var string
     */
    public $rawPath;

    /**
     * requested response type
     * @var string
     */
    protected $requestedResponseType;

    /**
     * version in request URL
     * @var
     */
    protected $urlVersion;

    /**
     * version in accept header
     * @var
     */
    protected $headerVersion;

    /**
     * requested API version
     * @var
     */
    protected $version;

    /**
     * Parsed path components
     * @var array
     */
    public $path;

    /**
     * Request method
     * @var string
     */
    public $method;

    /**
     * REST route chosen by the controller
     * @var array
     */
    public $route;

    /**
     * Arguments for REST method call
     * @var array
     */
    public $args;

    /**
     * post body
     * @var string
     */
    protected $postContents = null;

    /**
     * Get the route
     * @return array
     */
    public function getRoute()
    {
    	return $this->route;
    }

    /**
     * Get the args
     * @return array
     */
    public function getArgs()
    {
    	return $this->args;
    }

    /**
     * Get platform
     * @return string
     */
    public function getPlatform()
    {
    	return $this->platform;
    }

    /**
     * get API version for this request
     * @return string
     * @throws SugarApiExceptionIncorrectVersion
     */
    public function getVersion()
    {
        if (!empty($this->version)) {
            return $this->version;
        }

        if (empty($this->headerVersion) && empty($this->urlVersion)) {
            // invalid if version is neither in Accept Header nor URL
            throw new SugarApiExceptionIncorrectVersion(
                "No version provided in either Accept Header or URL!"
            );
        }

        // invalid if both header and url have version
        if (!empty($this->headerVersion) && !empty($this->urlVersion)) {
            throw new SugarApiExceptionIncorrectVersion(
                "Version must be specified in either Accept Header or URL, not both."
            );
        }

        // validate version string
        if (!empty($this->headerVersion) && !$this->isValidHeaderVersionString($this->headerVersion)) {
            throw new SugarApiExceptionIncorrectVersion(
                'Invalid Accept Header version format: ' . $this->headerVersion . '.'
            );
        }

        if (!empty($this->urlVersion) && !$this->isValidUrlVersionString($this->urlVersion)) {
            throw new SugarApiExceptionIncorrectVersion(
                'Invalid url version format: ' . $this->urlVersion . '.'
            );
        }

        // setup version
        if (!empty($this->urlVersion)) {
            $this->version = str_replace('_', '.', substr($this->urlVersion, 1));
        } elseif (!empty($this->headerVersion)) {
            $this->version = $this->headerVersion;
        }

        return $this->version;
    }

    /**
     * verify the version string make sure it is 2-digit major version
     * optionally followed by  '.' and 1 or 2 digit minor version
     * @param string $versionString
     * @return bool
     */
    protected function isValidHeaderVersionString($versionString)
    {
        if (is_string($versionString) && preg_match(self::VERSION_PATTERN, $versionString)) {
            return true;
        }
        return false;
    }

    /**
     * verify the URL version string make sure it is 'v' followed by
     * 2-digit major version optionally followed by  '_' and 1 or 2 digit minor version
     * @param string $versionString
     * @return bool
     */
    protected function isValidUrlVersionString($versionString)
    {
        if (is_string($versionString) && preg_match(self::PATH_VERSION_PATTERN, $versionString)) {
            return true;
        }
        return false;
    }

    /**
     * get API version for this request in URL version format
     * @return string
     * @throws SugarApiExceptionIncorrectVersion
     */
    public function getUrlVersion()
    {
        if (empty($this->urlVersion)) {
            $this->urlVersion = 'v' . str_replace('.', '_', $this->getVersion());
        }
        return $this->urlVersion;
    }

    /**
     * Get path components
     * @return array
     */
    public function getPath()
    {
    	return $this->path;
    }

    /**
     * Get HTTP method
     * @return string
     */
    public function getMethod()
    {
    	return $this->method;
    }

    /**
     * Get POST contents
     * @return string
     */
    public function getPostContents()
    {
        if(is_null($this->postContents)) {
            $this->postContents = file_get_contents('php://input');
        }
        return $this->postContents;
    }

    /**
     * Set the route
     * @param array $route
     * @return RestRequest
     */
    public function setRoute($route)
    {
    	$this->route = $route;
    	return $this;
    }

    /**
     * Set the args
     * @param array $args
     * @return RestRequest
     */
    public function setArgs($args)
    {
    	$this->args = $args;
    	return $this;
    }

    /**
     * Set HTTP method
     * @param string $method
     * @return RestRequest
     */
    public function setMethod($method)
    {
    	$this->method = $method;
    	return $this;
    }

    /**
     * Create request
     * @param array $server Server environment ($_SERVER)
     * @param array $request Request array ($_REQUEST)
     */
    public function __construct($server, $request)
    {
        $this->server = $server;
        $this->request = $request;
        $this->parseRequestHeaders();
        $this->rawPath = $this->getRawPath();
        $this->parsePath($this->rawPath);
        $this->method = isset($server['REQUEST_METHOD'])?$server['REQUEST_METHOD']:'GET';
    }

    /**
     * Gets the raw path of the request
     *
     * @return string
     */
    public function getRawPath() {
        if ( !empty($this->request['__sugar_url']) ) {
            $rawPath = $this->request['__sugar_url'];
        } else if ( !empty($this->server['PATH_INFO']) ) {
            $rawPath = $this->server['PATH_INFO'];
        } else {
            $rawPath = '/';
        }

        return $rawPath;
    }

    /**
     * Set the Request headers in an array
     * @return array
     */
    public function getRequestHeaders() {
        return $this->request_headers;
    }

    /**
     * parse and set the Request headers in an array
     * @return array
     */
    protected function parseRequestHeaders()
    {
        $headers = array();
        foreach ($this->server as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $key = str_replace('HTTP_', '', $key);
            $headers[$key] = $value;
        }
        $this->request_headers = $headers;
        if (!empty($this->request_headers['ACCEPT'])) {
            $this->parseAcceptHeader($this->request_headers['ACCEPT']);
        }

    }

    /**
     * Parses the request uri or request path as well as fetching the API request
     * version
     *
     * @param string $rawPath
     * @return array
     */
    public function parsePath($rawPath)
    {
        $pathBits = explode('/',trim($rawPath,'/'));
        $versionBit = $pathBits[0];

        // API version supports format: v{xx_yy}, MAJOR_MINOR
        if (preg_match(self::PATH_VERSION_PATTERN, $versionBit)) {
            $this->urlVersion = $versionBit;

            // shift out the version part of the request URI
            array_shift($pathBits);
        }

        $this->path = $pathBits;
        return $this;
    }

    /**
     * parse accept header to set request API version and request response type
     * valid formats:
     * application/vnd.sugarcrm.mobile+json; version=11
     * application/vnd.sugarcrm.base; version=99            // requested format is optional
     * application/vnd.sugarcrm.base+xml; version=99
     * application/vnd.sugarcrm.base+xml; version=12.2
     *
     * invalid format:
     * application/vnd.sugarcrm.mobile+json; version=11.123   // minor version is 3 digits
     * application/vnd.sugarcrm.base; version=111           // major version is not 2-digit
     *
     * @param string $acceptData, value of accept data
     * @return string/null, the version string
     */

    protected function parseAcceptHeader($acceptData)
    {
        $acceptItems = explode(',', $acceptData);
        foreach ($acceptItems as $item) {
            $matches = array();
            if (preg_match(self::ACCEPT_HEADER_VERSION_PATTERN, trim($item), $matches)) {
                // requested response type
                $this->requestedResponseType = $matches[2];

                // requested API version
                $this->headerVersion = $matches[3];
            }
        }

        return null;
    }

    /**
     * Set platform
     * @param string $platform
     * @return RestRequest
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * Maps the route path with the request path to set variables from the request
     *
     * @param array $path The request path
     * @param array $route The route for this request
     * @return array
     */
    public function getPathVars($route)
    {
        $outputVars = array();
        if (empty($route['pathVars'])) {
            return $outputVars;
        }
        foreach ($route['pathVars'] as $i => $varName) {
            if (!empty($varName) && !empty($this->path[$i])) {
                $outputVars[$varName] = $this->path[$i];
            }
        }
        return $outputVars;
    }

    /**
     * Get query vars
     * @return array
     */
    public function getQueryVars()
    {
        $vars = array();
        if(!empty($this->server['QUERY_STRING'])) {
            parse_str($this->server['QUERY_STRING'], $vars);
        }
        return $vars;
    }

    /**
     * to get resource URL base,
     * the leading portion of the URI for building request URIs with in the API
     *
     * @param $version
     * @return string
     *
     */
    public function getResourceURIBase($version)
    {
        if (empty($version)) {
            throw new SugarApiExceptionIncorrectVersion("missing version!");
        }

        // Default the base part of the request URI
        $apiBase = '/api/rest.php/';

        // Check rewritten URLs AND request uri vs script name
        if (isset($this->request['__sugar_url'])
            && (empty($this->server['REQUEST_URI'])
                || empty($this->server['SCRIPT_NAME'])
                || strpos($this->server['REQUEST_URI'], $this->server['SCRIPT_NAME']) === false)) {
            // This is a forwarded rewritten URL
            $apiBase = '/rest/';
        }

        // Get our version
        $apiBase .= $version;

        // This is for our URI return value
        $siteUrl = SugarConfig::getInstance()->get('site_url');

        // Get the file uri bas
        return $siteUrl . $apiBase . '/';
    }

    /**
     * Get request URI for current request
     * @return string
     */
    public function getRequestURI()
    {
        if(empty($this->server['REQUEST_URI'])) return '';
        return $this->server['REQUEST_URI'];
    }

    /**
     * Gets a header value from the request
     *
     * @param string $header The header to get the value of
     * @return string|null The string value of the header or null if not set
     */
    public function getHeader($header)
    {
        if ($this->hasHeader($header)) {
            return $this->request_headers[$header];
        }

        return null;
    }

    /**
     * Checks to see if a header is set in the request
     *
     * @param string $header The header to check existence of
     * @return boolean
     */
    public function hasHeader($header)
    {
        return isset($this->request_headers[$header]);
    }
}
