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
$viewdefs['KBContents']['base']['layout']['record-dashboard'] = array(
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
                                                'type' => 'kbs-dashlet-usefulness',
                                                'label' => 'LBL_DASHLET_USEFULNESS_NAME',
                                            ),
                                        'context' =>
                                            array(
                                                'module' => 'KBContents',
                                            ),
                                        'width' => 12,
                                    ),
                                ),
                                // TODO: Must be uncommented when RS-838 is done
                                /*array(
                                    array(
                                        'view' =>
                                            array(
                                                'type' => 'related-documents',
                                                'label' => 'LBL_DASHLET_RELATED_DOCUMENTS',
                                            ),
                                        'context' =>
                                            array(
                                                'module' => 'KBContents',
                                                'filter' => array(
                                                    'module' => array(
                                                        'KBContents',
                                                    ),
                                                    'view' => 'record',
                                                ),
                                                'limit' => 5,
                                            ),
                                        'width' => 12,
                                    ),
                                ),*/
                                array(
                                    array(
                                        'view' =>
                                            array(
                                                'type' => 'kbs-dashlet-localizations',
                                                'label' => 'LBL_DASHLET_LOCALIZATIONS_NAME',
                                            ),
                                        'context' =>
                                            array(
                                                'module' => 'KBContents',
                                                'filter' => array(
                                                    'module' => array(
                                                        'KBContents',
                                                    ),
                                                    'view' => 'record',
                                                ),
                                            ),
                                        'width' => 12,
                                    ),
                                ),
                            ),
                        'width' => 12,
                    ),
                ),
        ),
    'name' => 'LBL_KBCONTENTS_RECORD_DASHBOARD',
);
