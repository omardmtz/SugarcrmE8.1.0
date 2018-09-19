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
$viewdefs['base']['view']['dashletselect'] = array(
    'template' => 'filtered-list',
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'label' => 'LBL_DASHLET_CONFIGURE_TITLE',
                    'name' => 'title',
                    'type' => 'text',
                    'link' => true,
                    'events' => array(
                        'click a' => 'dashletlist:select-and-edit',
                    ),
                    'filter' => 'startsWith',
                    'sortable' => true,
                ),
                array(
                    'label' => 'LBL_DESCRIPTION',
                    'name' => 'description',
                    'type' => 'text',
                    'filter' => 'contains',
                    'sortable' => true,
                ),
                array(
                    'type' => 'rowaction',
                    'tooltip' => 'LBL_PREVIEW',
                    'event' => 'dashletlist:preview:fire',
                    'css_class' => 'btn',
                    'icon' => 'fa-eye',
                    'width' => '7%',
                    'sortable' => false,
                ),
            ),
        ),
    ),
);
