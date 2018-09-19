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

namespace Sugarcrm\Sugarcrm\Elasticsearch;

use Sugarcrm\Sugarcrm\Logger\LoggerTransition as BaseLogger;
use Psr\Log\LogLevel;
use Elastica\Request;
use Elastica\Response;
use Elastica\Connection;
use Elastica\JSON;
use SugarXHprof;

/**
 *
 * Logger specially for Elastic search.
 *
 */
class Logger extends BaseLogger
{
    /**
     * XHProf
     * @var SugarXHprof
     */
    protected $xhProf;

    /**
     * Set xhProf tracker
     * @param SugarXHProf $xhProf
     */
    public function setXhProf(SugarXHProf $xhProf)
    {
        $this->xhProf = $xhProf;
    }

    /**
     * Handle request logging on success.
     * @param \Elastica\Request $request
     * @param \Elastica\Response $response
     */
    public function onRequestSuccess(Request $request, Response $response)
    {
        // This is needed in either case
        $info = $response->getTransferInfo();

        // Sometimes no exceptions are thrown, log failure here in this case.
        if (!$response->isOk()) {
            $msg = sprintf(
                "Elasticsearch response failure: code %s [%s] %s",
                $response->getStatus(),
                $request->getMethod(),
                $info['url']
            );
            $this->log(LogLevel::CRITICAL, $msg);
        }

        // Dump full request/response in debug mode
        if ($this->logger->wouldLog(LogLevel::DEBUG)) {

            $msg = sprintf(
                "Elasticsearch request debug: [%s] %s %s",
                $request->getMethod(),
                $info['url'],
                $this->encodeData($request->getData())
            );
            $this->log(LogLevel::DEBUG, $msg);

            $msg = sprintf(
                "Elasticsearch response debug: %s",
                $this->encodeData($response->getData())
            );
            $this->log(LogLevel::DEBUG, $msg);
        }

        // xhprof tracking
        if (null !== $this->xhProf) {
            $this->xhProf->trackElasticQuery(array(
                $request->getMethod(),
                $info['url'],
            ), $request->getData(), $response->getQueryTime());
        }
    }

    /**
     * Check if the exception is from a request of index deletion
     * @param \Exception $e
     * @return bool
     */
    public function isDeleteMissingIndexRequest(\Exception $e)
    {
        if ($e instanceof \Elastica\Exception\ResponseException) {
            $method = $e->getRequest()->getMethod();
            $expMsg = $e->getMessage();

            // Method expected to be "DELETE" and contains msg as 'no such index'
            // example: "no such index [index: ee8562926e4403e4d990035a1b1e407d_shared]"
            if ($method === Request::DELETE && strpos($expMsg, "no such index") !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Handle request logging on failure.
     * @param \Elastica\Connection
     * @param \Exception $e
     * @param  string $path   request path
     * @param  string $method request method
     * @param  array  $data   request data
     */
    public function onRequestFailure(Connection $connection, \Exception $e, $path, $method, $data)
    {
        // If the exception is from index deletion, no critical message is logged.
        if ($this->isDeleteMissingIndexRequest($e)) {
            $msg = sprintf(
                "Elasticsearch request failure (non-critical): [%s] %s",
                $e->getRequest()->getMethod(),
                $e->getMessage()
            );
            $this->log(LogLevel::DEBUG, $msg);
            return;
        }

        $this->log(LogLevel::CRITICAL, "Elasticsearch request failure: " . $e->getMessage());

        // Additional debug logging
        if ($this->logger->wouldLog(LogLevel::DEBUG)) {

            // Request details
            $msg = sprintf(
                "Elasticsearch request failure details: [%s] %s:%s %s %s",
                $method,
                $connection->getHost(),
                $connection->getPort(),
                $path,
                $this->encodeData($data)
            );
            $this->log(LogLevel::DEBUG, $msg);
        }
    }

    /**
     * Helper method mimicing how \Elastica\Http formats its data.
     * Unfortunatily the raw value being send to the backend is not readily
     * available for log consumption.
     *
     * @param array|string $data
     * @return string
     */
    protected function encodeData($data)
    {
        if (is_array($data)) {
            $data = str_replace('\/', '/', JSON::stringify($data));
        }
        return $data;
    }
}
