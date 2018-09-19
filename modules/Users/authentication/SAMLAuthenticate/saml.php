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

define('XMLSECLIBS_DIR', 'vendor/onelogin/php-saml/extlib/xmlseclibs/');
require_once XMLSECLIBS_DIR . 'xmlseclibs.php';

define('ONELOGIN_SAML_DIR', 'vendor/onelogin/php-saml/lib/Saml/');
require_once ONELOGIN_SAML_DIR . 'AuthRequest.php';
require_once ONELOGIN_SAML_DIR . 'Response.php';
require_once ONELOGIN_SAML_DIR . 'Settings.php';
require_once ONELOGIN_SAML_DIR . 'XmlSec.php';
