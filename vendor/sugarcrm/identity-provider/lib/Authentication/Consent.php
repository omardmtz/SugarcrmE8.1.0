<?php

namespace Sugarcrm\IdentityProvider\Authentication;

/**
 * Consent entity
 */
class Consent
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $tenantId;

    /**
     * @var array
     */
    protected $scopes;

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return Consent
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTenantId()
    {
        return $this->tenantId;
    }

    /**
     * @param string $tenantId
     *
     * @return Consent
     */
    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    /**
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param array|string|json $scopes
     *
     * @return Consent
     */
    public function setScopes($scopes)
    {
        $decodedScopes = null;
        if (is_string($scopes)) {
            $decodedScopes = json_decode($scopes);
        }

        if ($decodedScopes) {
            $this->scopes = $decodedScopes;
        } elseif (is_array($scopes)) {
            $this->scopes = $scopes;
        } else {
            $this->scopes = explode(' ', $scopes);
        }

        return $this;
    }
}
