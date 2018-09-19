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

$viewdefs['pmse_Emails_Templates']['base']['layout']['compose-varbook-list'] = array(
    'type' => 'multi-selection-list',
    'components' => array(
        array(
            'view' => 'compose-varbook-list',
        ),
        array(
            'view' => 'compose-varbook-list-bottom',
        ),
    ),
);
