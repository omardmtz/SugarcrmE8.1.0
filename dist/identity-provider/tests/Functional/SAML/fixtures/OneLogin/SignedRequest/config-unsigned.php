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
$params = [
    'strict' => false,
    'debug' => true,
    'sp' => array (
        'entityId' => 'http://localhost:8000/saml/metadata',
        'assertionConsumerService' => array (
            'url' => 'http://localhost:8000/saml/acs',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        ),
        'singleLogoutService' => array (
            'url' => 'http://localhost:8000/saml/logout',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        'x509cert' => file_get_contents(__DIR__ . '/../../../../../../app/config/certs/travis.crt'),
        'privateKey' => file_get_contents(__DIR__ . '/../../../../../../app/config/certs/travis.key'),
    ),

    'idp' => array (
        'entityId' => 'http://www.okta.com/exk7y9w6b9H1jG46H0h7',
        'singleSignOnService' => array (
            'url' => 'https://dev-178368.oktapreview.com/app/sugarcrmdev280437_testidp_1/exk7y9w6b9H1jG46H0h7/sso/saml',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        'singleLogoutService' => array (
            'url' => 'https://dev-178368.oktapreview.com/app/sugarcrmdev280437_testidp_1/exk7y9w6b9H1jG46H0h7/slo/saml',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        'x509cert' => '-----BEGIN CERTIFICATE-----
              MIIDpDCCAoygAwIBAgIGAVad+pKSMA0GCSqGSIb3DQEBBQUAMIGSMQswCQYDVQQGEwJVUzETMBEG
              A1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsGA1UECgwET2t0YTEU
              MBIGA1UECwwLU1NPUHJvdmlkZXIxEzARBgNVBAMMCmRldi0xNzgzNjgxHDAaBgkqhkiG9w0BCQEW
              DWluZm9Ab2t0YS5jb20wHhcNMTYwODE4MTQwNjM5WhcNMjYwODE4MTQwNzM5WjCBkjELMAkGA1UE
              BhMCVVMxEzARBgNVBAgMCkNhbGlmb3JuaWExFjAUBgNVBAcMDVNhbiBGcmFuY2lzY28xDTALBgNV
              BAoMBE9rdGExFDASBgNVBAsMC1NTT1Byb3ZpZGVyMRMwEQYDVQQDDApkZXYtMTc4MzY4MRwwGgYJ
              KoZIhvcNAQkBFg1pbmZvQG9rdGEuY29tMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
              mEm1hUEtb/eWv7EwFAX9rj1hgUC1MxopGNIVHxowc36IThleVky+2ACKxeuY+G6M+UAPFgBP/ktF
              E/uwF3Ed9dAcdmzQ3q+Xm0GxlESkCb1AvJQnMh+UAyOlBIEI1KPGRI5y/X9TiCPRvsl57tS2Kw7/
              UnxfElTuv2ShKjt6R9guFx1SPL8RAPpFnk6rW9/Y0GoNWjeblRD6R03vjxQz86quLHzLXdoc3igN
              Hq0nNk/HRnBxRMTCxhdv54Ti7n5LZtaTBSbCkjAxfbbd5N3D/Bq7kJ3EJdxq/OfDEJR9oebaCysH
              BuGkhegZco+kKEeLwJZf0DCH+AAmh8PjXsnB0QIDAQABMA0GCSqGSIb3DQEBBQUAA4IBAQB+IN2b
              dqlGG5PZLuXTT33qkTR7aNTRlN4K+wy5KC0SGtm0IiGIR0rCSMtfHVyOOy1hodAv6DgjJ4Ejt4i9
              rJZXTksDj57kP6cSG6ngJ9KbYHcoJN6PgK5rfWF1imHGuegdDADahxfMrgISeKz9JnkYdG0i2rBo
              7B7CsMknnRWQL1V4deV3Db8qwrrWmJv2LvsrNUzYeh/9JPbLU2CWnp+j0HEH664D0ZFwhzwUX+QN
              0s7jNKhU4VXLkdBe6XcCX5pFYW3H4vKz2LSrCpHmuoidJqs4RaJotoTa4px5uImOn9kbIAqbHHUb
              F2XNRGdksB0l7arTUgTTe+1RsZeshp/L
              -----END CERTIFICATE-----',
    ),

    'security' => [
        'authnRequestsSigned' => false,
        'validateRequestId' => true,
    ],
];
