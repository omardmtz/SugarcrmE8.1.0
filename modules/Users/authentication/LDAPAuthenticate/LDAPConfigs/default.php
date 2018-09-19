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

 // $Id: default.php 16292 2006-08-22 20:57:23Z awu $

/**
 * @deprecated Will be removed in 7.11. IDM-46
 * @deprecated Please use new idM Mango library Glue \IdMSAMLAuthenticate
 */

$GLOBALS['ldapConfig'] = array(
'users'=>
		array(
			'fields'=>
						array(
							"givenName"=>'first_name',
							"sn"=>'last_name',
							"mail"=>'email1',
							"telephoneNumber"=>'phone_work',
							"facsimileTelephoneNumber"=>'phone_fax',
							"mobile"=>'phone_mobile',
							"street"=>'address_street',
							"l"=>'address_city',
							"st"=>'address_state',
							"postalCode"=>'address_postalcode',
							"c"=>'address_country'
							
							
							
						)
		),
'system'=>
		array('overwriteSugarUserInfo'=>true,),
			


);


?>
