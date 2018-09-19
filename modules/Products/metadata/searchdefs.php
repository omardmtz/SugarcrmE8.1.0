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

  $searchdefs['Products'] = array(
					'templateMeta' => array(
							'maxColumns' => '3', 
                            'maxColumnsBasic' => '4', 
                            'widths' => array('label' => '10', 'field' => '30'),                 
                           ),
                    'layout' => array(  					
						'basic_search' => array(
						 	'name',
						 	

		      array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
							),
						'advanced_search' => array(
							'name', 
							'tax_class',
							'status',
							'manufacturer_id',
							'category_id',
							array('name' => 'contact_name', 'label' =>'LBL_CONTACT_NAME', 'type' => 'name'),
							'mft_part_num',
							'type_id',
							'support_term',
							'website',	
							

		      array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
						),
					),
 			   );
?>
