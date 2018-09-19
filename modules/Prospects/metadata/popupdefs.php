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

$popupMeta = array('moduleMain' => 'Prospect',
						'varName' => 'PROSPECT',
						'orderBy' => 'prospects.last_name, prospects.first_name',
						'whereClauses' => 
							array('first_name' => 'prospects.first_name',
									'last_name' => 'prospects.last_name'),
						'searchInputs' =>
							array('first_name', 'last_name'),
						'selectDoms' =>
							array('LIST_OPTIONS' => 
											array('dom' => 'prospect_list_type_dom', 'searchInput' => 'list_type'),
								  ),
						'create' =>
							array('formBase' => 'ProspectFormBase.php',
									'formBaseClass' => 'ProspectFormBase',
									'getFormBodyParams' => array('','','ProspectSave'),
									'createButton' => 'LNK_NEW_PROSPECT'
								  )
						);


?>
 
 