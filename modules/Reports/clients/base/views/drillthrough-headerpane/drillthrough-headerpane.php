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

$viewdefs['Reports']['base']['view']['drillthrough-headerpane'] = array(
    'template' => 'headerpane',
    'fields' => array(
        array(
            'name' => 'title',
            'type' => 'label',
        ),
        array(
            'name' => 'drillthrough-collection-count',
            'type' => 'drillthrough-collection-count',
        ),
        array(
            'name' => 'drillthrough-labels',
            'type' => 'drillthrough-labels',
        ),
    ),
    'buttons' => array(
        array(
            'name' => 'close',
            'type' => 'button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'events' => array(
                'click' => 'drillthrough:closedrawer:fire',
            ),
            'css_class' => 'btn-invisible btn-link',
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
);
