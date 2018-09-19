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
 * REST response class
 */
class RestResponse extends Zend_Http_Response
{
    // Response encodings
    const RAW = 0;
    const JSON = 1;
    const JSON_HTML = 2;
    const FILE = 3;

    /**
     * Response type
     * @var int
     */
    protected $type = self::RAW;

    /**
     * Data from $_SERVER
     * @var array
     */
    protected $server_data = array();

    /**
     * Filename to read response from
     * @var string
     */
    protected $filename;

    /**
     * Flag for sending body or not
     * @var bool
     */
    protected $shouldSendBody;

    /**
     * Create HTTP response
     * @param array $server _SERVER array from the request
     */
    public function __construct($server)
    {
        $this->code = 200;
        $this->shouldSendBody = true;
        if(!empty($server['SERVER_PROTOCOL'])) {
            list($http, $version) = explode('/', $server['SERVER_PROTOCOL']);
            $this->version = $version;
        } else {
            $this->version = '1.1';
        }
        $this->server_data = $server;

        // Add in some extra sugar-specific HTTP codes
        self::$messages[433] = "Client Out Of Date";
    }

    /**
     * Set a response header
     * @param string $header
     * @param string $info
     * @return RestResponse
     */
    public function setHeader($header, $info)
    {
        // Disabled for now because of Content-type hacks
        // TODO: check if they are required
        //$header = ucwords(strtolower($header));
        $this->headers[$header] = $info;
        return $this;
    }

    /**
     * Check if the response headers have a header set
     * @param string $header
     * @return bool
     */
    public function hasHeader($header)
    {
        //$header = ucwords(strtolower($header));
        return array_key_exists($header, $this->headers);
    }

   /**
     * Get a specific header as string, or null if it is not set
     *
     * @param string$header
     * @return string|null
     */
    public function getHeader($header)
    {
        //$header = ucwords(strtolower($header));
        if (! is_string($header) || ! isset($this->headers[$header])) {
            return null;
        }

        return $this->headers[$header];
    }

    /**
     * Set response content
     * @param string $data
     * @return RestResponse
     */
    public function setContent($data)
    {
        $this->body = $data;
        return $this;
    }

    /**
     * Set the response type
     * @param int $type
     * @param bool $resetContentType Reset content type?
     * @return RestResponse
     */
    public function setType($type, $resetContentType = false)
    {
        $this->type = $type;
        if($resetContentType) {
            $this->setContentTypeByType();
        }
        return $this;
    }

    /**
     * Set HTTP status
     * @param int $code
     * @return RestResponse
     */
    public function setStatus($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Set content type according to response type
     * @return RestResponse
     */
    protected function setContentTypeByType()
    {
        if($this->type == self::JSON_HTML) {
            $this->setHeader("Content-Type", "text/html");
        }
        if($this->type == self::JSON) {
            $this->setHeader("Content-Type", "application/json");
        }
        return $this;
    }

    /**
     * Returns content to be sent to the client
     * @return string
     */
    public function processContent()
    {
        switch($this->type) {
            case self::JSON:
                $response = json_encode($this->body, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP);
                break;
            case self::JSON_HTML:
                $response = htmlspecialchars(json_encode($this->body), ENT_QUOTES, "UTF-8");
                break;
            case self::FILE:
                // special case
                return '';
            case self::RAW:
            default: /* we assume if we don't know the type, it's raw */
                $response = $this->body;
                break;
        }

        if(!$this->hasHeader("Content-Type")) {
            $this->setContentTypeByType();
        }

        if (!$this->hasHeader("Content-Length") && ini_get('zlib.output_compression') == 0) {
            // Files will overwrite this in $this->sendFile();
            $this->setHeader('Content-Length', strlen($response));
        }

        return $response;
    }

    /**
     * Send out a header
     * Overridable for tests
     * @param string $h
     */
    protected function sendHeader($h)
    {
        return header($h);
    }

    /**
     * Check if headers were sent
     * Overridable for tests
     * @return boolean
     */
    protected function headersSent()
    {
        return headers_sent();
    }

    /**
     * Send the response headers
     * @return bool
     */
    public function sendHeaders()
    {
        if($this->headersSent()) {
    		return false;
    	}
    	if($this->code != 200) {
    	    $text = self::responseCodeAsText($this->code, $this->version != '1.0');
    	    $this->sendHeader("HTTP/{$this->version} {$this->code} {$text}");
    	    $this->headers['Status'] = "{$this->code} {$text}";
    	}
    	foreach($this->headers as $header => $info) {
    		$this->sendHeader("{$header}: {$info}");
    	}
    	return true;
    }

    /**
     * Generate suitable ETag for content
     *
     * This function generates the necessary cache headers for using ETags with dynamic content. You
     * simply have to generate the ETag, pass it in, and the function handles the rest.
     *
     * @param string $etag ETag to use for this content.
     * @param int $cache_age Age in seconds of the cache-control max-age header
     * @return bool Did we have a match?
     */
    public function generateETagHeader($etag = null, $cache_age = null)
    {
        $cache_age = is_null($cache_age) ? SugarConfig::getInstance()->get('rest_response_etag_cache_age', 10) : $cache_age;

        if (is_null($etag)) {
            if (is_array($this->body)) {
                $etag = md5(json_encode($this->body));
            } else {
                $etag = md5($this->body);
            }
        }

        //Override cache control to ensure the etag is respected by the browser
        $this->setHeader('Cache-Control', "max-age={$cache_age}, private");
        $this->setHeader('Expires', "");
        $this->setHeader('Pragma', "");

        if (isset($this->server_data["HTTP_IF_NONE_MATCH"]) && $etag == $this->server_data["HTTP_IF_NONE_MATCH"]) {
            // Same data, clean it up and return 304
            $this->body = '';
            $this->code = 304;
            $this->type = self::RAW;
            $this->shouldSendBody = false;
            // disable gzip so that apache won't add compression header to response body
            @ini_set('zlib.output_compression', 'Off');
            return true;
        }

        $this->setHeader('ETag', $etag);
        return false;
    }

    /**
     * Set POST response headers
     *
     * Sets headers to prevent caching of the content
     *
     * @return RestResponse
     */
    public function setPostHeaders()
    {
    	$this->setHeader('Cache-Control', 'no-cache, must-revalidate');
    	$this->setHeader('Pragma', 'no-cache');
    	$this->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
    	return $this;
    }

    /**
     * Send out the file
     * @param string $file
     */
    protected function sendFile($file)
    {
        if(!file_exists($file)) {
            $this->body = '';
            $this->headers = array();
            $this->code = 404;
            $this->type = self::RAW;
            $this->sendHeaders();
            return;
        }
        $this->setHeader("Content-Length", filesize($file));
        $this->sendHeaders();
        set_time_limit(0);
        if(function_exists('zend_send_file')) {
        	zend_send_file($file);
        } else {
        	readfile($file);
        }
    }

    /**
     * Set filename to read response from
     * @param string $filename
     * @return RestResponse
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Send the response out
     */
    public function send()
    {
        if($this->type == self::FILE) {
            $this->sendFile($this->filename);
            return;
        }
        $response = $this->processContent();
        $this->sendHeaders();
        if ($this->shouldSendBody) {
            echo $response;
        }
    }

    /**
     * Get response type
     * @return number
     */
    public function getType()
    {
        return $this->type;
    }

}

