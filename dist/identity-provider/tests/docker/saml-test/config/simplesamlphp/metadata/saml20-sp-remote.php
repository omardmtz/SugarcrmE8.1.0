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

/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

require_once 'endpoints.php';

$idpX509Certificate = 'MIIDdTCCAl2gAwIBAgIJAMoIJg5+hQELMA0GCSqGSIb3DQEBCwUAMFExCzAJBgNV
BAYTAkJZMQ4wDAYDVQQIDAVNaW5zazEOMAwGA1UEBwwFTWluc2sxETAPBgNVBAoM
CFN1Z2FyQ1JNMQ8wDQYDVQQDDAZBbmRyZXcwHhcNMTYwODIzMTEyMDU0WhcNMTcw
ODIzMTEyMDU0WjBRMQswCQYDVQQGEwJCWTEOMAwGA1UECAwFTWluc2sxDjAMBgNV
BAcMBU1pbnNrMREwDwYDVQQKDAhTdWdhckNSTTEPMA0GA1UEAwwGQW5kcmV3MIIB
IjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvhAn1TlTyUsn6JUvSnAYcT/5
8hLRKz0J1SbU0xZ/vSQZrVYLlAbzF69AlS+/bfa5DUnIWC7Wte+JXSbzQ2P9Tx/m
bnSVUtJiRUdsPoj3bUFQbhGaT+LbPf8TcEMGpsc8JsAftSksC4wS1MuBqlpD4eib
jUF8kjbB6i2c34zrqWX1mCJCFSae9YEocH9YW79dfYjcjK1T2N5tV0LVWgiU/V1g
tFx98v/ibFBPO75MOH3gRmFE1a9fX0uD/w0bDlV1HE0F0+1hCNrbCaw/4uex5SWh
OoaNTS0kueH3AcXtY1ju4WBlmloIenRJVQh/WgKSteKTvzLwrRkuxt061wHzVwID
AQABo1AwTjAdBgNVHQ4EFgQUM+DmVDOGTb/l6F8EWgc6gdJYjYgwHwYDVR0jBBgw
FoAUM+DmVDOGTb/l6F8EWgc6gdJYjYgwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0B
AQsFAAOCAQEAeVdxprEsLUT3ZHYwox1XzKwDk3UPu67jVtjrjYXLr/qBqWVbDrAd
VRimX9fI6pr9MFBzgVbMfZ6VkEwgfNgJnrKja4db/4fk6qapPLf0FmOhDRjCweU6
Tz4pQ/QMeNBWbeK6Ekjqyz5mCxrbDmwNb/tuD6MzEJMtpwNXNcU1X68rI6wY9Cdk
8BWYNT3MQvf/NjoR6Feqk/qdgmEESZM/QLhj6vb2LxdfHniBGBMFpuwnnzScNHc5
NzYr9XzUJXcfVRsrKaKa1nLl21zeVKqNqf8SwFbJV9a6/UYRnnS84hkawIP6WOLH
DHdqN4yQ/I7etFrMgWCeypFLCSN+46GHZw==';

/**
 * This config should raise error on SP.
 */
$name = 'loginErrorFlowWithoutAnySigned';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => $endpoints[$name]['slo'],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'saml20.sign.response' => false,
    'saml20.sign.assertion' => false,
    'sign.logout' => false,
    'certData' => $idpX509Certificate,
];

/**
 * This config provides "authn" signed response.
 */
$name = 'loginFlowWithSignedResponse';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => $endpoints[$name]['slo'],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'saml20.sign.response' => true,
    'saml20.sign.assertion' => false,
    'certData' => $idpX509Certificate,
];

/**
 * This config provides "authn" signed response and assertion.
 */
$name = 'loginFlowWithSignedResponseAndAssertion';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => $endpoints[$name]['slo'],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'saml20.sign.response' => true,
    'saml20.sign.assertion' => true,
    'validate.authnrequest' => true,
    'certData' => $idpX509Certificate,
];

/**
 * This config provides "authn" signed response and encrypted assertion.
 */
$name = 'loginFlowWithSignedResponseAndEncryptedAssertion';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => $endpoints[$name]['slo'],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'saml20.sign.response' => true,
    'validate.authnrequest' => true,
    'assertion.encryption' => true,
    'certData' => $idpX509Certificate,
];

/**
 * This config provides not signed logout response with redirect.
 */
$name = 'logoutFlowWithRedirectBinding';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => [
        [
            'isDefault' => true,
            'Location' => $endpoints[$name]['slo'],
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ],
    ],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'sign.logout' => false,
    'certData' => $idpX509Certificate,
];

$name = 'samlSameWindowRedirect';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => [
        [
            'isDefault' => true,
            'Location' => $endpoints[$name]['slo'],
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ],
    ],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'sign.logout' => false,
    'certData' => $idpX509Certificate,
];

// Actually the same as 'samlSameWindowRedirect'.
// It's here just for creating a separate configuration end-point for container.
$name = 'samlSameWindowRedirectNoUserProvision';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => [
        [
            'isDefault' => true,
            'Location' => $endpoints[$name]['slo'],
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ],
    ],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'sign.logout' => false,
    'certData' => $idpX509Certificate,
];

/**
 * This config provides signed logout response with post.
 */
$name = 'logoutFlowWithPostBindingSignedResponse';
$metadata[$name] = [
    'AssertionConsumerService' => $endpoints[$name]['acs'],
    'SingleLogoutService' => [
        [
            'isDefault' => true,
            'Location' => $endpoints[$name]['slo'],
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        ],
    ],
    'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri',
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    'userid.attribute' => 'email',
    'sign.logout' => true,
    'certData' => $idpX509Certificate,
];
