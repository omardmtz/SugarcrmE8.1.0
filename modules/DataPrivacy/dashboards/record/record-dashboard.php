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
return array(
    'metadata' => array(
        'components' => array(
            array(
                'rows' => array(
                    array(
                        array(
                            'view' => array(
                                'type' => 'marked-for-erasure-dashlet',
                                'label' => 'LBL_MARKED_FOR_ERASURE_TITLE',
                                'custom_toolbar' => array (
                                    'buttons' => array (
                                        array (
                                            'dropdown_buttons' => array (
                                                array(
                                                    "type" => "dashletaction",
                                                    "action" => "editClicked",
                                                    "label" => "LBL_DASHLET_CONFIG_EDIT_LABEL",
                                                ),
                                                array (
                                                    'type' => 'dashletaction',
                                                    'action' => 'removeClicked',
                                                    'label' => 'LBL_DASHLET_REMOVE_LABEL',
                                                ),
                                            ),
                                        ),
                                    ),
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
    'name' => 'LBL_DATA_PRIVACY_RECORD_DASHBOARD',
);
