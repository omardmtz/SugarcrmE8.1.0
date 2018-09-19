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
 * Request class for bulk requests
 */
class BulkRestRequest extends RestRequest
{
    /**
     * Construct request from request data
     * @param array $request
     */
    public function __construct($request)
    {
        $svars = $_SERVER;
        $rvars = array();

        $rvars['__sugar_url'] = parse_url($request['url'], PHP_URL_PATH);
        if(!empty($request['headers'])) {
            foreach($request['headers'] as $hname => $hval) {
                $svars['HTTP_'.str_replace("-", "_", strtoupper($hname))] = $hval;
            }
        }
        if(!empty($request['method'])) {
            $svars['REQUEST_METHOD'] = $request['method'];
        } else {
            $svars['REQUEST_METHOD'] = 'GET';
        }

        if(isset($request['data'])) {
            $this->postContents =  $request['data'];
        }
        $svars['QUERY_STRING'] = parse_url($request['url'], PHP_URL_QUERY);

        parent::__construct($svars, $rvars);
    }
}

