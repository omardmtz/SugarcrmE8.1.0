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

// $Id: sugar_connection_event_listener.js 
// TODO remove this file

SUGAR_callsInProgress = 0;

YAHOO.util.Connect.completeEvent.subscribe(function(event, data){
	SUGAR_callsInProgress--;
	if (data[0].conn && data[0].conn.responseText && SUGAR.util.isLoginPage(data[0].conn.responseText))
		return false;
});

YAHOO.util.Connect.startEvent.subscribe(function(event, data)
{
	SUGAR_callsInProgress++;
});
