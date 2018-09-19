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

$viewdefs['Home']['base']['layout']['about'] = array(
    'css_class' => 'row-fluid',
    'components' => array(
        array(
            'layout' => array(
                'css_class' => 'main-pane span12',
                'components' => array(
                    array(
                        'view' => 'about-headerpane',
                    ),
                    array(
                        'layout' => array(
                            'type' => 'fluid',
                            'components' => array(
                                array(
                                    'view' => array(
                                        'type' => 'about-copyright',
                                        'span' => 12,
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'layout' => array(
                            'type' => 'fluid',
                            'components' => array(
                                array(
                                    'view' => array(
                                        'type' => 'about-resources',
                                        'span' => 6,
                                    ),
                                ),
                                array(
                                    'view' => array(
                                        'type' => 'about-source-code',
                                        'span' => 6,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
