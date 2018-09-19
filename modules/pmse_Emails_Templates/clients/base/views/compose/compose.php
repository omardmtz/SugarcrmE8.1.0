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

$viewdefs['pmse_Emails_Templates']['base']['view']['compose'] = array(
    'template' => 'record',
    'buttons' => array(
        array(
            'type'      => 'button',
            'name'      => 'cancel_button',
            'label'     => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
        ),
        array(
            'type'    => 'actiondropdown',
            'name'    => 'main_dropdown',
            'primary' => true,
            'buttons' => array(
                array(
                    'type'   => 'rowaction',
                    'name'   => 'save_button',
                    'label'  => 'LBL_SAVE_BUTTON_LABEL',
                ),
                array(
                    'type'  => 'rowaction',
                    'name'  => 'save_buttonExit',
                    'label' => 'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL',
                ),
            ),
        ),
        array(
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
        ),
    ),
    'panels'  => array(
        array(
            'name'         => 'panel_body',
            'label'        => 'LBL_PANEL_2',
            'columns'      => 1,
            'labels'       => true,
            'labelsOnTop'  => false,
            'placeholders' => true,
            'fields'       => array(
                array(
                    'name' => 'base_module',
                    'type' => 'readonly',
                    'label' => 'LBL_BASE_MODULE',
                    'span' => 12,
                ),
                array(
                    'name'            => 'name',
                    'label'           => 'LBL_NAME',
                    'span'            => 12,
                ),
                array(
                    'name'            => 'description',
                    'label'           => 'LBL_DESCRIPTION',
                    'span'            => 12,
                ),
                array(
                    'name'            => 'subject',
                    'type'            => 'subject',
                    'label'           => 'LBL_SUBJECT',
                    'span'            => 12,
                    'cell_css_class'  => 'btn-fit',
                    'required'        => true,
                    'label_css_class' => 'end-fieldgroup',
                ),
                array(
                    'name'          => 'body_html',
                    'type'          => 'pmse_htmleditable_tinymce',
                    'dismiss_label' => true,
                    'span'          => 12,
                    'tinyConfig'    => array(
                        'height' => '400',
                        'plugins' => 'code,textcolor,link',
                        'toolbar' => 'code | bold italic underline strikethrough | bullist numlist | ' .
                            'alignleft aligncenter alignright alignjustify | forecolor backcolor | ' .
                            'fontsizeselect formatselect | link | sugarfieldbutton sugarlinkbutton',
                    ),
                ),
            ),
        ),

    ),
);
