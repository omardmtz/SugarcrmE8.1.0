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

namespace Sugarcrm\IdentityProvider\App\Authentication\ConsentRequest;

use Sugarcrm\IdentityProvider\App\Authentication\OAuth2Service;

class ConsentRestService implements ConsentTokenServiceInterface
{
    /**
     * @var OAuth2Service
     */
    protected $oAuth2Service;

    /**
     * ConsentRequestParser constructor.
     * @param OAuth2Service $oAuth2Service
     */
    public function __construct(OAuth2Service $oAuth2Service)
    {
        $this->oAuth2Service = $oAuth2Service;
    }

    /**
     * Return consent Pay Load.
     * @param string $identifier
     * @return mixed
     */
    public function getToken($identifier)
    {
        return (new ConsentToken())->fillByConsentRequestData(
            $this->oAuth2Service->getConsentRequestData($identifier)
        );
    }
}
