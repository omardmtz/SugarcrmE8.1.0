<?php

namespace Sugarcrm\IdentityProvider\Authentication\Consent;

use Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest\ConsentTokenInterface;
use Sugarcrm\IdentityProvider\Authentication\Consent;

class ConsentChecker
{
    /**
     * @var Consent
     */
    protected $consent;

    /**
     * @var ConsentTokenInterface
     */
    protected $token;

    /**
     * @param Consent $consent
     * @param ConsentTokenInterface $token
     */
    public function __construct(Consent $consent, ConsentTokenInterface $token)
    {
        $this->consent = $consent;
        $this->token = $token;
    }

    /**
     * check token consent
     * @return bool
     */
    public function check()
    {
        foreach ($this->consent->getScopes() as $consentScope) {
            foreach ($this->token->getScope() as $tokenScope) {
                if ($tokenScope == $consentScope) {
                    return true;
                }
            }
        }
        return false;
    }
}
