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
 $searchFields['Administration'] = 
    array (
        'user_name' => array(
            'query_type'=>'default',
			'operator' => 'subquery',
			'subquery' => 'SELECT users.id FROM users WHERE users.deleted=0 and users.user_name LIKE',
			'db_field'=>array('user_id')),
    );
?>
