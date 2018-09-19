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

$viewdefs['Tasks']['base']['layout']['list-dashboard'] = array(
    'metadata' =>
        array(
            'components' =>
                array(
                    array(
                        'rows' =>
                            array(
                                array(
                                    array(
                                        'view' =>
                                            array(
                                                'type' => 'dashablelist',
                                                'label' => 'TPL_DASHLET_MY_MODULE',
                                                'display_columns' =>
                                                    array(
                                                        'full_name',
                                                        'email',
                                                        'phone_work',
                                                        'status',
                                                    ),
                                            ),
                                        'context' =>
                                            array(
                                                'module' => 'Leads',
                                            ),
                                        'width' => 12,
                                    ),
                                ),
                                array(
                                    array(
                                        'view' =>
                                            array(
                                                'type' => 'dashablelist',
                                                'label' => 'TPL_DASHLET_MY_MODULE',
                                                'display_columns' =>
                                                    array(
                                                        'bug_number',
                                                        'name',
                                                        'status',
                                                    ),
                                            ),
                                        'context' =>
                                            array(
                                                'module' => 'Bugs',
                                            ),
                                        'width' => 12,
                                    ),
                                ),
                            ),
                        'width' => 12,
                    ),
                ),
        ),
    'name' => 'LBL_TASKS_LIST_DASHBOARD',
);
