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
 * Handles loading the theme picker popup
 */
YAHOO.util.Event.onDOMReady(function()
{
	// open print dialog if we requested the print view
    if ( location.href.indexOf('print=true') > -1 )
        setTimeout("window.print();",  1000);
});
