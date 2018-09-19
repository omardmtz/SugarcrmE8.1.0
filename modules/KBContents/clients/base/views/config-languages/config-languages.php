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
$viewdefs['KBContents']['base']['view']['config-languages'] = array(
    'label' => 'LBL_ADMIN_LABEL_LANGUAGES',
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'languages',
                    'type' => 'languages',
                    'searchBarThreshold' => 5,
                    'label' => 'LBL_EDIT_LANGUAGES',
                    'default' => false,
                    'enabled' => true,
                    'view' => 'edit',
                    'span' => 6
                ),
            ),
        ),
    ),
);
