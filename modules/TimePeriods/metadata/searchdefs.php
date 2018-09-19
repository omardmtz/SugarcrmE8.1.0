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
  $searchdefs['TimePeriods'] = array(
					'templateMeta' => array(
							'maxColumns' => '3',
  							'maxColumnsBasic' => '4',
                            'widths' => array('label' => '10', 'field' => '30'),
                           ),
                    'layout' => array(
						'basic_search' => array(
						    'name' => array('name' => 'name', 'label' => 'LBL_TP_NAME', 'type' => 'name',),
						    ),
						'advanced_search' => array(
						    'name' => array('name' => 'name', 'label' => 'LBL_TP_NAME', 'type' => 'name',),
						    'parent_id' => array('name' => 'parent_id', 'label' => 'LBL_TP_FISCAL_YEAR',
						        'type' => 'enum',
						        'size' => 1,
						        'function' =>
                                array (
                                  'name' => 'get_fiscal_year_dom',
                                  'params' =>
                                  array (
                                    0 => false,
                                  ),
                                ),
                                'default' => true,),
						    'start_date' => array('name' => 'start_date', 'label' => 'LBL_TP_START_DATE',),
						    'end_date' => array('name' => 'end_date', 'label' => 'LBL_TP_END_DATE',),
						    ),
					),
 			   );
?>
