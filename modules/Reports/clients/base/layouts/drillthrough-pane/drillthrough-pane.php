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

$viewdefs['Reports']['base']['layout']['drillthrough-pane'] = array(
    'name' => 'drillthrough-pane',
    'css_class' => 'dashboard drillthrough-pane',
    'components' => array(
        array(
            'view' => array(
                'name' => 'drillthrough-pane-headerpane',
                'template' => 'headerpane',
                'fields' => array(
                    array(
                        'name' => 'title',
                        'type' => 'text',
                    ),
                ),
                'buttons' => array(
                    array(
                        'type' => 'button',
                        'icon' => 'fa-refresh',
                        'css_class' => 'btn',
                        'tooltip' => 'LBL_REFRESH_LIST_AND_CHART',
                        'events' => array(
                            'click' => 'click:refresh_list_chart',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
