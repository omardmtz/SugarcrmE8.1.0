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
 * Response class for bulk requests
 * Aggregates multiple responses on send()
 */
class BulkRestResponse extends RestResponse
{
    /**
     * Current request name
     * @var string
     */
    protected $reqName;

    /**
     * Request results
     * @var array
     */
    protected $results = array();

    /**
     * Set request name
     * @param string $name
     */
    public function setRequest($name)
    {
        $this->reqName = $name;
        return $this;
    }

    /**
     * Get accumulated responses
     * @return array
     */
    public function getResponses()
    {
        return $this->results;
    }

    /**
     * Map of fields to record: RestResponse => JSON
     * @var array
     */
    protected $fieldMap = array(
        'body' => 'contents',
        'headers' => 'headers',
        'code' => 'status',
        'statusText' => 'status_text',
    );

    /**
     * Instead of sending, record the request data
     * @see RestResponse::send()
     */
    public function send()
    {
        switch($this->type) {
            case self::FILE:
                if(!file_exists($this->filename)) {
                    $this->body = '';
                    $this->headers = array();
                    $this->code = 404;
                } else {
                    $this->setHeader("Content-Length", filesize($this->filename));
                    $this->body = file_get_contents($this->filename);
                }
                break;
            case self::JSON:
            case self::JSON_HTML:
                // keep as-is
                break;
            default:
                 $this->body = $this->processContent();
        }
        if(empty($this->code)) {
            $this->code = 200;
        }

        $this->statusText = static::responseCodeAsText($this->code);

        foreach($this->fieldMap as $prop => $data) {
            if (isset($this->$prop)) {
                $this->results[$this->reqName][$data] = $this->$prop;
                if(is_array($this->$prop)) {
                    $this->$prop = array();
                } else {
                    $this->$prop = null;
                }
            }
        }
        // reset type for next one
        $this->type = self::RAW;
    }
}
