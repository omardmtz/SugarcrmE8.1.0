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

$viewdefs['Products']['base']['filter']['default'] = array(
    'default_filter' => 'all_records',
    'fields' => array(
        'name' => array(),
        'contact_name' => array(),
        'status' => array(),
        'type_name' => array(),
        'category_name' => array(),
        'manufacturer_name' => array(),
        'mft_part_num' => array(),
        'vendor_part_num' => array(),
        'tax_class'=> array(),
        'support_term'=> array(),
        'date_entered' => array(),
        'date_modified' => array(),
        'tag' => array(),
        '$favorite' => array(
            'predefined_filter' => true,
            'vname' => 'LBL_FAVORITES_FILTER',
        ),
    ),
);
