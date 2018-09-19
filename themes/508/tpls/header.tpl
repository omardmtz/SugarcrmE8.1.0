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
{include file="_head.tpl" theme_template=true}
<body class="yui-skin-sam">
    <a name="top"></a>
	{include file="_dcmenu.tpl" theme_template=true}

	<div class="clear"></div>
    <div class="clear"></div>

{literal}
<iframe id='ajaxUI-history-iframe' src='index.php?entryPoint=getImage&imageName=blank.png'  title='empty'  style='display:none'></iframe>
<input id='ajaxUI-history-field' type='hidden'>
<script type='text/javascript'>
if (SUGAR.ajaxUI && !SUGAR.ajaxUI.hist_loaded)
{
	YAHOO.util.History.register('ajaxUILoc', "", SUGAR.ajaxUI.go);
	{/literal}{if $smarty.request.module != "ModuleBuilder"}{* Module builder will init YUI history on its own *}
	YAHOO.util.History.initialize("ajaxUI-history-field", "ajaxUI-history-iframe");
	{/if}{literal}
}


function keyboardShortcuts() {
var $dialog = $('<div id="shortcuts_dialog" class="open"></div>')
.html("{/literal}{$APP.LBL_KEYBOARD_SHORTCUTS_HELP}{literal}")
.dialog({
	autoOpen: false,
	title: '{/literal}{$APP.LBL_KEYBOARD_SHORTCUTS_HELP_TITLE}{literal}',
	width: 300,
	position: {
	    my: 'right top',
	    at: 'left top',
	    of: $("#shortcuts")
    },
    close: function(e) {
        $(this).dialog("destroy").remove();
    },
    open: function(e) {
        SUGAR.util.buildAccessKeyLabels();
    }
});

$dialog.dialog('open');
$(".ui-dialog").appendTo("#content");

}
</script>
{/literal}

<div id='shortcuts' class='accessKeyHelp' style="float: right; margin-right: 10px;" onclick="javascript: keyboardShortcuts();">{$APP.LBL_KEYBOARD_SHORTCUTS_HELP_TITLE}</div>
<div id="main">
    <div id="content">

        <table style="width:100%" id="contentTable"><tr><td>
