<?php

namespace Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest;

interface ConsentTokenInterface
{
    /**
     * Return tenant srn
     * @return string
     */
    public function getTenantSRN();


    /**
     * Return STS client id
     * @return string
     */
    public function getClientId();

    /**
     * return STS client allowed scopes
     * @return array
     */
    public function getScope();

    /**
     * return consent request id
     * @return string
     */
    public function getRequestId();
}
