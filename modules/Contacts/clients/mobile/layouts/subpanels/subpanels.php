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
$viewdefs['Contacts']['mobile']['layout']['subpanels'] = array(
    'components' => array(
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_CALLS_SUBPANEL_TITLE',
            'linkable' => false,
            'context' => array(
                'link' => 'calls',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_MEETINGS_SUBPANEL_TITLE',
            'linkable' => false,
            'context' => array(
                'link' => 'meetings',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_TASKS_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'all_tasks',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_NOTES_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'notes',
            ),
        ),
        array(
            'layout' => "subpanel",
            'label' => 'LBL_LEADS_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'leads',
            ),
        ),
        array(
            'layout' => "subpanel",
            'label' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'opportunities',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_EMAILS_SUBPANEL_TITLE',
            'linkable' => false,
            'unlinkable' => false,
            'context' => array(
                'link' => 'archived_emails',
            ),
        ),
        array(
            'layout' => "subpanel",
            'label' => 'LBL_CASES_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'cases',
            ),
        ),
        array(
            'layout' => "subpanel",
            'label' => 'LBL_DIRECT_REPORTS_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'direct_reports',
            ),
        ),
        array(
            'layout' => "subpanel",
            'label' => 'LBL_NOTES_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'notes',
            ),
        ),
        array(
            'layout' => "subpanel",
            'label' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'documents',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_QUOTES_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'quotes',
            ),
        ),
        array (
            'layout' => 'subpanel',
            'label' => 'LBL_PRODUCTS_SUBPANEL_TITLE',
            'context' => array (
                'link' => 'products',
            ),
        ),
    ),
);
