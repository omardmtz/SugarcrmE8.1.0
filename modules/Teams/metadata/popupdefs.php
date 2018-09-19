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

$popupMeta = array('moduleMain' => 'Team',
						'varName' => 'TEAM',
						'orderBy' => 'teams.private, teams.name',
						'whereClauses' => 
							array('name' => 'teams.name', 'private' => 'teams.private'),
                        'whereStatement'=> "( teams.associated_user_id IS NULL OR teams.associated_user_id NOT IN ( SELECT id FROM users WHERE status = 'Inactive' OR portal_only = '1' ))",
						'searchInputs' =>
							array('name')
						);


?>
 
 