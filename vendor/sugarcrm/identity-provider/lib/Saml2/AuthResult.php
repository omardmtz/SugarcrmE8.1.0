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

namespace Sugarcrm\IdentityProvider\Saml2;

/**
 * Class for store information about authorization response or request parameters that passed to service.
 */
class AuthResult
{
    /**
     * Destination url to send result.
     * @var string
     */
    protected $url;

    /**
     * HTTP method.
     * @var string
     */
    protected $method;

    /**
     * Additional attributes to store.
     * @var array
     */
    protected $attributes;

    /**
     * AuthResult constructor.
     * @param $url
     * @param $method
     * @param array $attributes
     */
    public function __construct($url, $method, $attributes = [])
    {
        $this->url = $url;
        $this->method = $method;
        $this->attributes = $attributes;
    }

    /**
     * Gets url to send result
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets HTTP method.
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets additional attributes to send.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
