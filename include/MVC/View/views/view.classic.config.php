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

 $view_config = array(
	'req_params' =>
		array(
		    'print' => array('param_value' => true,
		                     'config' => array(
		                                  'show_header' => true,
		                                  'show_footer' => false,
		                                  'view_print'  => true,
		                                  'show_title' => false,
                                          'show_subpanels' => false,
                                          'show_javascript' => true,
                                          'show_search' => false,)
                       ),
			'to_pdf' => array('param_value' => true,
							   'config' => array(
		 										'show_all' => false
		 										),
		 				),
		 	'to_csv' => array('param_value' => true,
							   'config' => array(
		 										'show_all' => false
		 										),
		 				),
		),
 );
