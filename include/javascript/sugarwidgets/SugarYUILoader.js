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
 * @author dwheeler
 */
//Load up the YUI loader and go!
SUGAR.yui = {
	loader : new YAHOO.util.YUILoader({
        // Bug #48940 Skin always must be blank
        skin: {
            base: 'blank',
            defaultSkin: ''
        }
    })
} 

SUGAR.yui.loader.addModule({
	name:'sugarwidgets',
	type:'js', 
	path:'SugarYUIWidgets.js', 
	requires:['yahoo', 'layout', 'dragdrop', 'treeview', 'json', 'datatable', 'container', 'button', 'tabview'], 
	varname: YAHOO.SUGAR
});
