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
$searchdefs['Holidays'] = array(
            'templateMeta' => array(
                    'maxColumns' => '2',  'maxColumnsBasic' => '2', 
                    'widths' => array('label' => '10', 'field' => '30'),                 
                   ),
            'layout' => array(                      
                'basic_search' => array(
                    'holiday_date',
                    
                 
                ),
                'advanced_search' => array(
                    'holiday_date',
                    'description',
                ),                                              
            ),
);
    
?>
