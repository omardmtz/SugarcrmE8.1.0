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

global $mod_strings;

$popupMeta = array('moduleMain' => 'Contact',
						'varName' => 'CONTACT',
						'orderBy' => 'contacts.first_name, contacts.last_name',
						'whereClauses' => 
							array('first_name' => 'contacts.first_name', 
									'last_name' => 'contacts.last_name',
									'account_name' => 'accounts.name',
									'account_id' => 'accounts.id'),
						'searchInputs' =>
							array('first_name', 'last_name', 'account_name'),
						'create' =>
							array('formBase' => 'ContactFormBase.php',
									'formBaseClass' => 'ContactFormBase',
									'getFormBodyParams' => array('','','ContactSave'),
									'createButton' => 'LNK_NEW_CONTACT'
								  ),
						'templateForm' => 'modules/Contacts/Email_picker.html',
						);
?>
