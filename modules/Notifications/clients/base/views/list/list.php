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

$viewdefs['Notifications']['base']['view']['list'] = array(
    'favorites' => false,
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'severity',
                    'type' => 'severity',
                    'default' => true,
                    'enabled' => true,
                    'css_class' => 'full-width',
                ),
                array(
                    'name' => 'name',
                    'default' => true,
                    'enabled' => true,
                    'link' => true,
                ),
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_LIST_RELATED_TO',
                    'id' => 'PARENT_ID',
                    'link' => true,
                    'default' => true,
                    'enabled' => true,
                    'sortable' => false,
                ),
                array (
                    'name' => 'assigned_user_name',
                    'sortable' => false,
                    'enabled' => true,
                    'default' => false,
                ),
                array(
                    'name' => 'date_entered',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_modified',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'is_read',
                    'type' => 'read',
                    'default' => true,
                    'enabled' => true,
                    'css_class' => 'full-width',
                ),
            ),
        ),
    ),
    'orderBy' => array(
        'field' => 'date_entered',
        'direction' => 'desc',
    ),
);
