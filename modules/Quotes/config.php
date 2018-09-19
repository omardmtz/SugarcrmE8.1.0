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
//include any group of products in these stages for the standard pdf template
//Does not need to be translated this is just for the keys
$in_total_group_stages =  $GLOBALS['app_list_strings']['in_total_group_stages'];
$pdf_group_subtotal = true;

if (SugarAutoLoader::existing('custom/modules/Quotes/config.php'))
{
	include_once('custom/modules/Quotes/config.php');
}
