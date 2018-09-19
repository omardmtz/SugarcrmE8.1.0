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

$viewdefs['Documents']['base']['layout']['tabbed-layout'] = array(
    'components' => array(
        array(
            'view' => 'activitystream',
            'label' => 'Activity Stream',
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Document Revisions',
            'context' => array(
                'link' => 'revisions',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Contracts',
            'context' => array(
                'link' => 'contracts',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Accounts',
            'context' => array(
                'link' => 'accounts',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Contacts',
            'context' => array(
                'link' => 'contacts',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Opportunities',
            'context' => array(
                'link' => 'opportunities',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Cases',
            'context' => array(
                'link' => 'cases',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Bugs',
            'context' => array(
                'link' => 'bugs',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Quotes',
            'context' => array(
                'link' => 'quotes',
            ),
        ),
        array(
            'layout' => 'list-cluster',
            'label' => 'Products',
            'context' => array(
                'link' => 'products',
            ),
        ),
    ),
);
