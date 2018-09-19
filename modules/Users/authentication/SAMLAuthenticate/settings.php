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
 * @deprecated Will be removed in 7.11. IDM-46
 * @deprecated Please use new idM Mango library Glue \IdMSAMLAuthenticate
 */

$settings                           = new OneLogin_Saml_Settings();

// when using Service Provider Initiated SSO (starting at index.php), this URL asks the IdP to authenticate the user.
$settings->idpSingleSignOnUrl       = isset($GLOBALS['sugar_config']['SAML_loginurl']) ? $GLOBALS['sugar_config']['SAML_loginurl'] : '';
$settings->idpSingleLogOutUrl       = isset($GLOBALS['sugar_config']['SAML_SLO']) ? $GLOBALS['sugar_config']['SAML_SLO'] : '';

// the certificate for the users account in the IdP
$settings->idpPublicCertificate          = isset($GLOBALS['sugar_config']['SAML_X509Cert']) ? $GLOBALS['sugar_config']['SAML_X509Cert'] : '';

// no dataOnly when showing the login page in the main window (no popup)
// $returnQueryVars is set by the caller
if (!empty($returnQueryVars) && !empty($returnQueryVars['platform']) && $returnQueryVars['platform'] == 'base'
        && !empty($GLOBALS['sugar_config']['SAML_SAME_WINDOW'])) {
        $returnPath = '/index.php?module=Users&action=Authenticate';
} else {
        $returnPath = '/index.php?module=Users&action=Authenticate&dataOnly=1';
}

// The URL where to the SAML Response/SAML Assertion will be posted
$settings->spReturnUrl = htmlspecialchars(
    rtrim($GLOBALS['sugar_config']['site_url'], '/')
    . $returnPath
);

// Name of this application
$settings->spIssuer                         = isset($GLOBALS['sugar_config']['SAML_issuer']) ? $GLOBALS['sugar_config']['SAML_issuer'] : 'php-saml';

// Tells the IdP to return the email address of the current user as ID
$settings->requestedNameIdFormat = OneLogin_Saml_Settings::NAMEID_EMAIL_ADDRESS;

// Should new users be provisioned?
// True by default.
$settings->provisionUsers = isset($GLOBALS['sugar_config']['SAML_provisionUser']) ? $GLOBALS['sugar_config']['SAML_provisionUser'] : true;

// Available settings other than above:
// id - way of matching users: user_name, id. If not set, matched by email.
// saml2_settings['create'] - list in format of field => attribute for creating users
// saml2_settings['update'] - list in format of field => attribute for updating user data
// saml2_settings['check']['user_name'] - attribute that specifies where the username is stored in the data
// useXML - use XML instead of attribute names in saml2_settings
// customCreateFunction - custom user creation function
