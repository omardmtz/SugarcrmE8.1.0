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
/**
 * Local XML source
 * @api
 */
abstract class loc_xml extends source
{
 	public function __parse($file)
 	{
 		$contents = file_get_contents($file);
 		libxml_disable_entity_loader(true);
 		return simplexml_load_string($contents);
 	}
}
