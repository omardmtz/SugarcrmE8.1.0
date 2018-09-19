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
$viewdefs['Quotes']['base']['view']['quote-data-grand-totals-footer'] = array(
    'panels' => array(
        array(
            'name' => 'panel_quote_data_grand_totals_footer',
            'label' => 'LBL_QUOTE_DATA_GRAND_TOTALS_FOOTER',
            'fields' => array(
                array(
                    'name' => 'new_sub',
                    'type' => 'currency',
                ),
                array(
                    'name' => 'tax',
                    'type' => 'currency',
                    'related_fields' => array(
                        'taxrate_value',
                    ),
                ),
                array(
                    'name' => 'shipping',
                    'type' => 'quote-footer-currency',
                    'css_class' => 'quote-footer-currency',
                    'default' => '0.00',
                ),
                array(
                    'name' => 'total',
                    'label' => 'LBL_LIST_GRAND_TOTAL',
                    'type' => 'currency',
                    'css_class' => 'grand-total',
                ),
            ),
        ),
    ),
);
