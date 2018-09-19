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

/***CONFIGURATOR***/
$sugar_config['passwordsetting']['onespecial'] = '0';
$sugar_config['passwordsetting']['systexpirationtype'] = '1';
$sugar_config['authenticationClass'] = 'SAMLAuthenticate';
$sugar_config['SAML_loginurl'] = 'http://saml-server/simplesaml/saml2/idp/SSOService.php';
$sugar_config['SAML_SLO'] = 'http://saml-server/simplesaml/saml2/idp/SingleLogoutService.php';
$sugar_config['SAML_idp_entityId'] = 'http://saml-server/simplesaml/saml2/idp/metadata.php';
$sugar_config['SAML_X509Cert'] = '-----BEGIN CERTIFICATE-----
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
-----END CERTIFICATE-----';
$sugar_config['SAML_provisionUser'] = true;
$sugar_config['SAML_request_signing_method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
$sugar_config['SAML_SAME_WINDOW'] = false;
$sugar_config['SAML_sign_authn'] = false;
$sugar_config['SAML_sign_logout_request'] = false;
$sugar_config['SAML_sign_logout_response'] = false;
$sugar_config['SAML_issuer'] = 'logoutFlowWithRedirectBinding';
$sugar_config['SAML']['strict'] = true;
$sugar_config['site_url'] = 'http://behat-tests-mango-saml-base';
$sugar_config['verify_client_ip'] = false;
/***CONFIGURATOR***/
