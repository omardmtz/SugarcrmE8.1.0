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
$viewdefs['Emails']['base']['view']['preview'] = array(
    'panels' => array(
        array(
            'name' => 'panel_header',
            'header' => true,
            'fields' => array(
                array(
                    'name' => 'picture',
                    'type' => 'avatar',
                    'size' => 'large',
                    'label' => '',
                    'readonly' => true,
                ),
            ),
        ),
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'name',
                    'readonly' => true,
                    'related_fields' => array(
                        'state',
                    ),
                ),
                array(
                    'name' => 'from_collection',
                    'type' => 'from',
                    'label' => 'LBL_FROM',
                    'readonly' => true,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                ),
                array(
                    'name' => 'date_sent',
                    'label' => 'LBL_DATE',
                    'readonly' => true,
                ),
                array(
                    'name' => 'to_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_TO_ADDRS',
                    'readonly' => true,
                    'max_num' => -1,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                    'span' => 12,
                ),
                array(
                    'name' => 'description_html',
                    'dismiss_label' => true,
                    'readonly' => true,
                    'span' => 12,
                    'related_fields' => array(
                        'description',
                    ),
                ),
                array(
                    'name' => 'attachments_collection',
                    'type' => 'email-attachments',
                    'label' => 'LBL_ATTACHMENTS',
                    'readonly' => true,
                    'span' => 12,
                    'max_num' => -1,
                    'fields' => array(
                        'name',
                        'filename',
                        'file_size',
                        'file_source',
                        'file_mime_type',
                        'file_ext',
                        'upload_id',
                    ),
                ),
                'team_name',
            ),
        ),
        array(
            'name' => 'panel_hidden',
            'label' => 'LBL_RECORD_SHOWMORE',
            'hide' => true,
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'cc_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_CC',
                    'readonly' => true,
                    'max_num' => -1,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                ),
                array(
                    'name' => 'bcc_collection',
                    'type' => 'email-recipients',
                    'label' => 'LBL_BCC',
                    'readonly' => true,
                    'max_num' => -1,
                    'fields' => array(
                        'email_address_id',
                        'email_address',
                        'parent_type',
                        'parent_id',
                        'parent_name',
                        'invalid_email',
                        'opt_out',
                    ),
                ),
                'assigned_user_name',
                'parent_name',
                array(
                    'name' => 'tag',
                    'span' => 12,
                ),
            ),
        ),
    ),
);
