{*
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
*}
<div class='ytheme-gray' id="datepicker" style="z-index: 9999; position:absolute; width:50px;"></div>
<div id='trackercontent_div_{$id}'></div>
{literal}
<script type="text/javascript">
var tracker_dashlet;

function onLoadDoInit() {
{/literal}
tracker_dashlet = new TrackerDashlet();
tracker_dashlet.init('{$id}', {$height});
tracker_dashlet.comboChanged();
{literal}
}

YAHOO.util.Event.onDOMReady(function(){            
var reportLoader = new YAHOO.util.YUILoader({
	require : ["layout","element"],
	loadOptional: true,
    // Bug #48940 Skin always must be blank
    skin: {
        base: 'blank',
        defaultSkin: ''
    },
	onSuccess : onLoadDoInit,
	base : "include/javascript/yui/build/"
});
reportLoader.addModule({
    name: "sugarwidgets",
    type: "js",
{/literal}
    fullpath: "{sugar_getjspath file='include/javascript/sugarwidgets/SugarYUIWidgets.js'}",
{literal}
    varName: "YAHOO.SUGAR",
    requires: ["datatable", "dragdrop", "treeview", "tabview", "button", "autocomplete", "container"]
});
reportLoader.insert();
});
</script>
{/literal}
