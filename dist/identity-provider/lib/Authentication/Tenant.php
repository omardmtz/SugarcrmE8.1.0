<?php

namespace Sugarcrm\IdentityProvider\Authentication;

use Sugarcrm\IdentityProvider\Srn\Srn;

/**
 * Tenant entity
 */
class Tenant
{
    /**
     * Tenant status
     * @var integer
     */
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var boolean
     */
    protected $deleted;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Tenant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return Tenant
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @param string $domainName
     *
     * @return Tenant
     */
    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return Tenant
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool|integer $deleted
     *
     * @return Tenant
     */
    public function setDeleted($deleted)
    {
        $this->deleted = (bool) $deleted;

        return $this;
    }

    /**
     * parse tenant srn "srn:cloud:idp:eu:1234567890:tenant:1234567890" and fill object fields
     * @param Srn $srn
     *
     * @throws \RuntimeException
     * @return Tenant
     */
    public function fillFromSRN(Srn $srn)
    {
        return $this->setRegion($srn->getRegion())
            ->setId($srn->getTenantId());
    }
}
