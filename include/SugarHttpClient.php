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
 * Very basic HTTP client
 * @api
 * Used in various places of the code and can be mocked out.
 * Presently does only one op - POST to url.
 * If you need more complex stuff, use Zend_Http_Client
 */
class SugarHttpClient
{
    /**
     * @var string
     */
    protected $last_error = '';

    /**
     * sends POST request to REST service via CURL
     * @param string $url URL to call
     * @param string $postArgs POST args
     * @param array $curlOpts cURL options
     * @return string|boolean
     */
    public function callRest($url, $postArgs, array $curlOpts = array())
    {
        // cURL extension is required
        if (!function_exists("curl_init")) {
            $this->last_error = 'ERROR_NO_CURL';
            $GLOBALS['log']->fatal("REST call failed - no cURL!");
            return false;
        }

        $curl = curl_init($url);

        // cURL post options
        $postOpts = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postArgs,
        );

        // Merge defaults, override and post options together
        $curlOpts = $postOpts + $this->getCurlOpts($curlOpts);
        curl_setopt_array($curl, $curlOpts);

        // Perform cURL call
        $GLOBALS['log']->debug("HTTP client call: $url -> " . var_export($postArgs, true));
        $response = curl_exec($curl);

        // Handle error
        if ($response === false) {
            $this->last_error = 'ERROR_REQUEST_FAILED';
            $curl_errno = curl_errno($curl);
            $curl_error = curl_error($curl);
            $GLOBALS['log']->error("HTTP client: cURL call failed for '$url': error $curl_errno: $curl_error");
            return false;
        }

        // Close
        $GLOBALS['log']->debug("HTTP client response: $response");
        curl_close($curl);
        return $response;
    }

    /**
     * Returns code of last error that happened to the client
     * @return string
     */
    public function getLastError()
    {
        return $this->last_error;
    }

    /**
     * Get list of cURL options based on historical defaults. Note that for
     * secure connections it is strongly advised to use the proper SSL flags
     * as if none are set, an insecure default approach is used.
     *
     * @param array $opts List op cURL options to add or override the defauls
     * @return array
     */
    protected function getCurlOpts(array $opts = array())
    {
        $default = array(
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        );
        return $opts + $default;
    }
}
