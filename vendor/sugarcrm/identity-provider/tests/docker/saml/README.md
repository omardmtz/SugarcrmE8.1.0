# Docker SP for test SAML 2.0 Identity Provider (IdP)

Built with [SimpleSAMLphp](https://simplesamlphp.org). Based on official PHP7 Apache [images](https://hub.docker.com/_/php/).

## Usage

### Build container
```sh
docker build --tag "identity-provider/samlserver:<version>" .
```

### Push container to SugarCRM docker registry
```sh
docker tag identity-provider/samlserver:<version> registry.sugarcrm.net/identity-provider/samlserver:latest
docker push registry.sugarcrm.net/identity-provider/samlserver:latest
```

#### Run from local build
```sh
docker run --name=saml-idp \
-p 8080:80 \
-p 8443:443 \
-e SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE=<path to acs endpoint> \
-e SIMPLESAMLPHP_SP_SINGLE_LOGOUT_SERVICE=<path to slo endpoint> \
-d identity-provider/samlserver:<version>
```

#### Run from SugarCRM registry build
```sh
docker run --name=saml-idp \
-p 8080:80 \
-p 8443:443 \
-e SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE=<path to acs endpoint> \
-e SIMPLESAMLPHP_SP_SINGLE_LOGOUT_SERVICE=<path to slo endpoint> \
-d registry.sugarcrm.net/identity-provider/samlserver:latest
```

There are two static users configured in the IdP with the following data:

| UID | Username | Password  | Group  | Email             |
|-----|----------|-----------|--------|-------------------|
| 1   | user1    | user1pass | group1 | user1@example.com |
| 2   | user2    | user2pass | group2 | user2@example.com |
| 3   | user3    | user3pass | group1 | user3@example.com |
| 4   | user4    | user4pass | group2 | user4@example.com |
| 5   | user5    | user5pass | group1 | user5@example.com |


You can access the SimpleSAMLphp web interface of the IdP under `http://localhost:8080/simplesaml`. The admin password is `secret`.


## Identity Provider (IdP) sample config
```php
$params['saml'] = [
    'strict' => true,
    'debug' => true,
    'sp' => [
        'entityId' => 'loginFlowWithSignedResponseAndEncryptedAssertion',
        'assertionConsumerService' => array (
            'url' => '<path to acs endpoint>',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        'singleLogoutService' => array (
            'url' => '<path to slo endpoint>',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        ),
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        'x509cert' => file_get_contents(__DIR__ . '/certs/travis.crt'),
        'privateKey' => file_get_contents(__DIR__ . '/certs/travis.key'),
    ],

    'idp' => [
        'entityId' => 'http://localhost:8080/simplesaml/saml2/idp/metadata.php',
        'singleSignOnService' => array (
            'url' => 'http://localhost:8080/simplesaml/saml2/idp/SSOService.php',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        'singleLogoutService' => array (
            'url' => 'http://localhost:8080/simplesaml/saml2/idp/SingleLogoutService.php',
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        'x509cert' => '-----BEGIN CERTIFICATE-----
MIIDXTCCAkWgAwIBAgIJALmVVuDWu4NYMA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
BAYTAkFVMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBX
aWRnaXRzIFB0eSBMdGQwHhcNMTYxMjMxMTQzNDQ3WhcNNDgwNjI1MTQzNDQ3WjBF
MQswCQYDVQQGEwJBVTETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50
ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEAzUCFozgNb1h1M0jzNRSCjhOBnR+uVbVpaWfXYIR+AhWDdEe5ryY+Cgav
Og8bfLybyzFdehlYdDRgkedEB/GjG8aJw06l0qF4jDOAw0kEygWCu2mcH7XOxRt+
YAH3TVHa/Hu1W3WjzkobqqqLQ8gkKWWM27fOgAZ6GieaJBN6VBSMMcPey3HWLBmc
+TYJmv1dbaO2jHhKh8pfKw0W12VM8P1PIO8gv4Phu/uuJYieBWKixBEyy0lHjyix
YFCR12xdh4CA47q958ZRGnnDUGFVE1QhgRacJCOZ9bd5t9mr8KLaVBYTCJo5ERE8
jymab5dPqe5qKfJsCZiqWglbjUo9twIDAQABo1AwTjAdBgNVHQ4EFgQUxpuwcs/C
YQOyui+r1G+3KxBNhxkwHwYDVR0jBBgwFoAUxpuwcs/CYQOyui+r1G+3KxBNhxkw
DAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAAiWUKs/2x/viNCKi3Y6b
lEuCtAGhzOOZ9EjrvJ8+COH3Rag3tVBWrcBZ3/uhhPq5gy9lqw4OkvEws99/5jFs
X1FJ6MKBgqfuy7yh5s1YfM0ANHYczMmYpZeAcQf2CGAaVfwTTfSlzNLsF2lW/ly7
yapFzlYSJLGoVE+OHEu8g5SlNACUEfkXw+5Eghh+KzlIN7R6Q7r2ixWNFBC/jWf7
NKUfJyX8qIG5md1YUeT6GBW9Bm2/1/RiO24JTaYlfLdKK9TYb8sG5B+OLab2DImG
99CJ25RkAcSobWNF5zD0O6lgOo3cEdB/ksCq3hmtlC/DlLZ/D8CJ+7VuZnS1rR2n
aQ==
-----END CERTIFICATE-----',
    ],
    'security' => [
        'authnRequestsSigned' => true,
        'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
    ],
];
```