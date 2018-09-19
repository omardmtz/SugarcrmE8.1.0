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

namespace Sugarcrm\IdentityProvider\Srn;

/**
 * SugarCRM Resource Name object definition
 * For example:
 * Tenant object: srn:cloud:idp:eu:1234567890:tenant:1234567890
 * User object: srn:cloud:idp:eu:1234567890:user:e9b578dc-b5ae-41b6-a680-195cfc018f30
 *
 * Generated from protobuf message <code>sugarcrm.idp.v1alpha.Srn</code>
 */
class Srn
{
    /**
     * Cloud partition name - 'cloud'
     */
    private $partition = '';
    /**
     * Service name - 'idp', 'reports' etc.
     */
    private $service = '';
    /**
     * Geographic region name - 'US', 'EU', 'US-WEST'
     */
    private $region = '';
    /**
     * Tenant ID. 10 digit number - 1234567890
     *
     * Generated from protobuf field <code>string tenant_id = 4;</code>
     */
    private $tenant_id = '';
    /**
     * The resource to work with - 'tenant', 'user:userId' etc.
     */
    private $resource = [];

    /**
     * Cloud partition name - 'cloud'
     *
     * @return string
     */
    public function getPartition()
    {
        return $this->partition;
    }

    /**
     * Cloud partition name - 'cloud'
     *
     * @param string $var
     * @return $this
     */
    public function setPartition($var)
    {
        $this->partition = $var;

        return $this;
    }

    /**
     * Service name - 'idp', 'reports' etc.
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Service name - 'idp', 'reports' etc.
     *
     * @param string $var
     * @return $this
     */
    public function setService($var)
    {
        $this->service = $var;

        return $this;
    }

    /**
     * Geographic region name - 'US', 'EU', 'US-WEST'
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Geographic region name - 'US', 'EU', 'US-WEST'
     *
     * @param string $var
     * @return $this
     */
    public function setRegion($var)
    {
        $this->region = $var;

        return $this;
    }

    /**
     * Tenant ID. 10 digit number - 1234567890
     *
     * @return string
     */
    public function getTenantId()
    {
        return $this->tenant_id;
    }

    /**
     * Tenant ID. 10 digit number - 1234567890
     *
     * @param string $var
     * @return $this
     */
    public function setTenantId($var)
    {
        $this->tenant_id = $var;

        return $this;
    }

    /**
     * Type of the resource to work with - 'tenant', 'user:userId' etc.
     *
     * @return array
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * The resource to work with - 'tenant', 'user:userId' etc.
     *
     * @param array $var
     * @return $this
     */
    public function setResource(array $var)
    {
        $this->resource = $var;

        return $this;
    }
}
