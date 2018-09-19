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


$viewdefs['pmse_Emails_Templates']['base']['view']['compose-sugarlinks-list'] = array(
    'template' => 'flex-list',
    'selection' => array(
        'type' => 'single',
        'actions' => array(),
        'label' => 'LBL_LINK_SELECT',
    ),
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'name' => 'text',
                    'label' => 'LBL_MODULE',
                    'sortable' => false,
                    'enabled' => true,
                    'default' => true,
                ),
                array(
                    'name' => 'relatedTo',
                    'label' => 'LBL_RELATIONSHIP',
                    'sortable' => false,
                    'enabled' => true,
                    'default' => true,
                ),
            ),
        ),
    ),
);

