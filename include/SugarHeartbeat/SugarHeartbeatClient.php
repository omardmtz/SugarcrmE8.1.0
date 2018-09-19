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

//BWC: nusoap library has been moved to vendor directory in 7+
if(file_exists('vendor/nusoap/nusoap.php')) {
    require_once 'vendor/nusoap/nusoap.php';
} else if(file_exists("include/nusoap/nusoap.php")) {
    require_once "include/nusoap/nusoap.php";
}

/**
 * Class SugarHeartbeatClient
 *
 * SoapClient for Sugar's heartbeat server. Currently we are using nusoap
 * because SoapClient is not a required extension for SugarCRM.
 */
class SugarHeartbeatClient extends nusoap_client
{
    /**
     * We don't use WSDL mode to avoid more traffic to the heartbeat server.
     *
     * @var string Endpoint url
     */
    const DEFAULT_ENDPOINT = 'https://updates.sugarcrm.com/heartbeat/soap.php';

    /**
     * These parameters are already SoapClient compatible when moving away
     * from nusoap in the future.
     *
     * @var array SoapClient options
     */
    protected $defaultOptions = array(
        'connection_timeout' => 15,
        'exceptions' => 0 // unused for nusoap
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $endpoint = $this->getEndpoint();
        $this->setupNuSoap($endpoint);
        $options = $this->getOptions();
        parent::__construct($endpoint, false, false, false, false, false, $options['connection_timeout']);
    }

    /**
     * Setup nuSoap before making any connections based on given endpoint.
     * @param string $endpoint Endpoint
     */
    protected function setupNuSoap($endpoint)
    {
        // validate server cert for SSL connections
        if (strpos($endpoint, 'https://') === 0) {
            $this->setUseCURL(true);
            $this->curl_options = array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            );
        }
    }

    /**
     * Returns endpoint
     * reads $sugar_config['heartbeat']['endpoint']
     * default is SugarHeartbeatClient::DEFAULT_ENDPOINT
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return SugarConfig::getInstance()->get('heartbeat.endpoint', self::DEFAULT_ENDPOINT);
    }

    /**
     * Returns Soap Options
     * reads $sugar_config['heartbeat']['options']
     * default is SugarHeartbeatClient::$defaultOptions
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge($this->defaultOptions, SugarConfig::getInstance()->get('heartbeat.options', array()));
    }

    /**
     * Proxy to sugarPing WSDL method
     *
     * @return mixed
     */
    public function sugarPing()
    {
        return $this->call('sugarPing', array());
    }

    /**
     * Proxy to sugarHome WSDL method
     * Encodes $info
     *
     * @param string $key License key
     * @param array $info
     * @return mixed
     */
    public function sugarHome($key, array $info)
    {
        $data = $this->encode($info);
        return $this->call('sugarHome', array('key' => $key, 'data' => $data));
    }

    /**
     * Serialize + Base64
     * @see SugarHeartbeatClient::sugarHome
     *
     * @param $value
     * @return string
     */
    protected function encode($value)
    {
        return base64_encode(serialize($value));
    }

    /**
     * Base64 decode + Unserialize
     * @see SugarHeartbeatClient::sugarHome
     *
     * @param $value
     * @return mixed
     */
    protected function decode($value)
    {
        return unserialize(base64_decode($value));
    }
}
