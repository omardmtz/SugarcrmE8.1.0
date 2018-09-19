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

/* example data creation config, copy to createdefs.php */
$createdef['example@example.com']['Contacts'] = array(
        'fields' => array(
                'email1' => '{from_addr}',
                'last_name' => '{from_name}',
                'description' => 'created from {subject}',
                'lead_source' => 'Email',
        ),
);
