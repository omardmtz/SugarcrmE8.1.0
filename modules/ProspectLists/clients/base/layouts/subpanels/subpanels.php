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
$viewdefs['ProspectLists']['base']['layout']['subpanels'] = array(
    'components' => array(
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_PROSPECTS_SUBPANEL_TITLE',
            'override_paneltop_view' => 'panel-top-for-prospectlists',
            'context' => array(
                'link' => 'prospects',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_CONTACTS_SUBPANEL_TITLE',
            'override_paneltop_view' => 'panel-top-for-prospectlists',
            'context' => array(
                'link' => 'contacts',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_LEADS_SUBPANEL_TITLE',
            'override_paneltop_view' => 'panel-top-for-prospectlists',
            'context' => array(
                'link' => 'leads',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_USERS_SUBPANEL_TITLE',
            'override_paneltop_view' => 'panel-top-for-prospectlists',
            'context' => array(
                'link' => 'users',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_ACCOUNTS_SUBPANEL_TITLE',
            'override_subpanel_list_view' => 'subpanel-for-prospectlists',
            'override_paneltop_view' => 'panel-top-for-prospectlists',
            'context' => array(
                'link' => 'accounts',
            ),
        ),
        array(
            'layout' => 'subpanel',
            'label' => 'LBL_CAMPAIGNS_SUBPANEL_TITLE',
            'context' => array(
                'link' => 'campaigns',
            ),
        ),
    ),
);
