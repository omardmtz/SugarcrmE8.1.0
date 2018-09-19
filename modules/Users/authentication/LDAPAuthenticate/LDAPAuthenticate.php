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

 // $Id: LDAPAuthenticate.php 16292 2006-08-22 20:57:23Z awu $

/**
 * This file is used to control the authentication process. 
 * It will call on the user authenticate and controll redirection 
 * based on the users validation
 * @deprecated Will be removed in 7.11. IDM-46
 * @deprecated Please use new idM Mango library Glue \IdMLDAPAuthenticate
 */
class LDAPAuthenticate extends SugarAuthenticate {
	var $userAuthenticateClass = 'LDAPAuthenticateUser';
	var $authenticationDir = 'LDAPAuthenticate';
}
