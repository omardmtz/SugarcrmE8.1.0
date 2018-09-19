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

class SugarLicensing
{

    protected $_server = "https://authenticate.sugarcrm.com";

    /**
     * @var resource
     */
    protected $_curl;

    /**
     * Constructor Method with Curl URL
     *
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Destructor Method
     * This will clear out the curl connect if it's still alive.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Start the Curl Connection Process and create the curl object so it's ready for a connection
     *
     * @return void;
     */
    public function connect()
    {
        if ($this->isConnected()) {
            // we are still connected return nothing;
            return;
        }

        $curl = curl_init();

        // Tell curl to use HTTP POST
        curl_setopt($curl, CURLOPT_POST, true);

        // Tell curl not to return headers, but do return the response
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $this->_curl = $curl;
    }

    /**
     * Test if curl is setup and ready for a connection
     *
     * @return bool
     */
    public function isConnected()
    {
        return is_resource($this->_curl);
    }

    /**
     * Disconnect from curl if an object already exists
     *
     * @return void
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            // ok we have a curl so kill the connection
            curl_close($this->_curl);
        }
    }

    /**
     * Make a request to the end point on the licensing server
     *
     * @param $endpoint
     * @param $payload
     * @return array
     */
    public function request($endpoint, $payload)
    {
        // make sure that the first char is a "/"
        if (substr($endpoint, 0, 1) != "/") {
            $endpoint = "/" . $endpoint;
        }

        curl_setopt($this->_curl, CURLOPT_URL, $this->_server . $endpoint);

        if(is_array($payload)) {
            $payload = json_encode($payload);
        }

        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $payload);

        $response = $this->_reqeust();

        return json_decode($response, true);
    }

    /**
     * Run the curl exec
     *
     * @return mixed
     */
    private function _reqeust()
    {
        $results = curl_exec($this->_curl);
        
        if($results === FALSE)
        {
            $GLOBALS['log']->fatal("Sugar Licensing encountered an error: " . curl_error($this->_curl));
        }

        return $results;
    }
}
