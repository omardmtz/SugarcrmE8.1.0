<?php

namespace Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest;

interface ConsentTokenServiceInterface
{
    /**
     * @param $identifier
     * @return ConsentTokenInterface
     */
    public function getToken($identifier);
}
