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

// $Id: registered_layout_defs.php 22867 2007-05-16 22:21:10Z majed $


/**
 * Retrieves an array of all the layout_defs defined in the app.
 */

function get_layout_defs()
{
    //TODO add global memory cache support here.  If there is an in memory cache, leverage it.
	global $layout_defs;
	return $layout_defs;
}

?>