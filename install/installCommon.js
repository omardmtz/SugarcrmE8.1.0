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
// $Id: installCommon.js 13581 2006-05-27 20:00:56Z chris $

function showHelp(step)
{
  url = 'http://www.sugarcrm.com/forums/';
  name = 'helpWindowPopup';
  window.open(url,name);
}

function setFocus() {
	focus = document.getElementById('button_next2');
	focus.focus();
}
