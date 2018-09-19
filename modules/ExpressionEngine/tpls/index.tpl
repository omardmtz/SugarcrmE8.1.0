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
<script src="{sugar_getjspath file='include/javascript/sugarwidgets/SugarYUILoader.js'}"></script>
{literal}
<script type="text/javascript">
var loader = new YAHOO.util.YUILoader({
    require : ["sugarwidgets"],
    loadOptional: true,
    skin: { base: 'blank', defaultSkin: '' },
	onSuccess: function(){console.log("loaded")},
    allowRollup: true,
    base: "include/javascript/yui/build/"
});
loader.addModule({
    name :"sugarwidgets",
    type : "js",
{/literal}
    fullpath: "{sugar_getjspath file='include/javascript/sugarwidgets/SugarYUIWidgets.js'}",
{literal}
    varName: "YAHOO.SUGAR",
    requires: ["datatable", "dragdrop", "treeview", "tabview"]
});
loader.insert();
var DDEditorWindow = false;
showEditor = function() {
    if (!DDEditorWindow)
        DDEditorWindow = new YAHOO.SUGAR.AsyncPanel('DDEditorWindow', {
            width: 256,
            draggable: true,
            close: true,
            constraintoviewport: true,
            fixedcenter: false,
            script: true,
            modal: true
        });
    var win = DDEditorWindow;
    win.setHeader("Dropdown Editor");
    win.setBody("loading...");
    win.render(document.body);
    win.params = {
        module:"ExpressionEngine",
        action:"editDepDropdown",
        loadExt:false,
        embed: true,
        view_module:"Accounts",
        field: 'sub_industry_c',
        package:"",
        to_pdf:1
    };
    win.load('index.php?' + SUGAR.util.paramsToUrl(win.params), null, function()
    {
        DDEditorWindow.center();
        SUGAR.util.evalScript(DDEditorWindow.body.innerHTML);
    });
    win.show();
    win.center();
}
</script>
{/literal}
<input class="button" type="button" onclick="showEditor()" value="Show"/>
<div id="editorDiv"></div>