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

namespace Sugarcrm\IdentityProvider\App\Authentication\Adapter;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SamlAdapter extends AbstractAdapter
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * modify IPD-API config to SAML library
     * @param $config
     * @return array
     */
    public function getConfig($config)
    {
        $config = $this->decode($config);
        if (empty($config)) {
            return [];
        }
        return [
            'strict' => false,
            'debug' => true,
            'sp' => [
                'entityId' => !empty($config['sp_entity_id']) ? $config['sp_entity_id'] : 'php-saml',
                'assertionConsumerService' => [
                    'url' => $this->urlGenerator->generate('samlAcs', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_POST,
                ],
                'singleLogoutService' => [
                    'url' => $this->urlGenerator->generate('samlLogout', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'NameIDFormat' => \OneLogin_Saml2_Constants::NAMEID_EMAIL_ADDRESS,
                'x509cert' => !empty($config['request_signing_cert']) ? $config['request_signing_cert'] : '',
                'privateKey' => !empty($config['request_signing_pkey']) ? $config['request_signing_pkey'] : '',
                'provisionUser' => isset($config['provision_user']) ? $config['provision_user'] : true,
            ],

            'idp' => [
                'entityId' => !empty($config['idp_entity_id']) ? $config['idp_entity_id'] : $config['idp_sso_url'],
                'singleSignOnService' => [
                    'url' => $config['idp_sso_url'],
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'singleLogoutService' => [
                    'url' => $config['idp_slo_url'],
                    'binding' => \OneLogin_Saml2_Constants::BINDING_HTTP_REDIRECT,
                ],
                'x509cert' => !empty($config['x509_cert']) ? $config['x509_cert'] : '',
            ],

            'security' => [
                'authnRequestsSigned' => isset($config['sign_authn_request']) ? $config['sign_authn_request'] : false,
                'logoutRequestSigned' => isset($config['sign_logout_request']) ? $config['sign_logout_request'] : false,
                'logoutResponseSigned' => isset($config['sign_logout_response'])
                    ? $config['sign_logout_response'] : false,
                'signatureAlgorithm' => $config['request_signing_method'] == 'RSA_SHA512'
                    ? \XMLSecurityKey::RSA_SHA512 : \XMLSecurityKey::RSA_SHA256,
                'validateRequestId' => isset($config['validate_request_id']) ? $config['validate_request_id'] : false,
            ],
            'user_mapping' => $this->decode($config['attribute_mapping']),
        ];
    }
}
