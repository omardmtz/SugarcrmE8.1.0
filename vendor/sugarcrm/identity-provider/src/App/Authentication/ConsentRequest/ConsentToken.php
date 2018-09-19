<?php

namespace Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest;

use Jose\Object\JWSInterface;

class ConsentToken implements ConsentTokenInterface
{
    /**
     * @var string
     */
    protected $tenantSrn;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var JWSInterface
     */
    protected $jwsToken;

    /**
     * array of client scope
     * @var array
     */
    protected $scope = [];

    /**
     * consent request id
     * @var string
     */
    protected $requestId;

    /**
     * consent redirect url
     * @var  string
     */
    protected $redirectUrl;

    /**
     * @inheritDoc
     */
    public function getTenantSRN()
    {
        return $this->tenantSrn;
    }

    /**
     * @inheritDoc
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return JWSInterface
     */
    public function getJwsToken()
    {
        return $this->jwsToken;
    }

    /**
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * fill fields by oauth2 consent data
     * @param array $data
     * @return ConsentToken
     */
    public function fillByConsentRequestData(array $data)
    {
        $this->requestId = $data['id'];
        $this->scope = $data['requestedScopes'];
        $this->clientId = $data['clientId'];
        $this->redirectUrl = $data['redirectUrl'];

        $queryParams = [];
        parse_str(parse_url($this->redirectUrl, PHP_URL_QUERY), $queryParams);

        if (!empty($queryParams['login_hint'])) {
            $this->tenantSrn = $queryParams['login_hint'];
        }

        return $this;
    }
}
