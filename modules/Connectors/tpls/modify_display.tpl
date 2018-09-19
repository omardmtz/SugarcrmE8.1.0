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
<script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='modules/Connectors/Connector.js'}"></script>
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>


{literal}

<script language="javascript">

var _sourceArray = new Array();

var SourceTabs = {

    init : function() {
         _tabView = new YAHOO.widget.TabView();

    	{/literal}
    		 {counter assign=source_count start=0 print=0}
	        {foreach name=connectors from=$SOURCES key=name item=source}
	            {counter assign=source_count}
		{literal}
		       	tab = new YAHOO.widget.Tab({
			        label: '{/literal}{$source.name}{literal} ',
			        dataSrc: {/literal}'index.php?module=Connectors&action=DisplayProperties&source_id={$source.id}'{literal},
			        cacheData: true,
			        {/literal}
			        {if $source_count == 1}
			        active: true
			        {else}
			        active: false
			        {/if}
			        {literal}
			    });
			    _tabView.addTab(tab);
			    tab.addListener('contentChange', SourceTabs.tabContentChanged);
			    _sourceArray[{/literal}{$source_count}{literal}-1] = '{/literal}{$source.id}{literal}';
		{/literal}
	       {/foreach}
		  {literal}
  		_tabView.appendTo('container');
  		//_tabView.addListener('contentChange', SourceTabs.tabContentChanged);
 		_tabView.addListener('beforeActiveTabChange', SourceTabs.tabIndexChanged);
    },

    tabContentChanged: function(info) {
    	tab = _tabView.get('activeTab');
        SUGAR.util.evalScript(tab.get('content'));
    },

    tabIndexChanged : function(info){

    },

    fitContainer: function() {
		_tabView = SourceTabs.getTabView();
		content_div = _tabView.getElementsByClassName('yui-content', 'div')[0];
		content_div.style.overflow='auto';
		content_div.style.height='405px';
    },

    getTabView : function() {
        return _tabView;
    }
}
YAHOO.util.Event.onDOMReady(SourceTabs.init);
</script>
{/literal}
<form name="ModifyDisplay" method="POST">
{sugar_csrf_form_token}
<input type="hidden" name="modify" value="true">
<input type="hidden" name="module" value="Connectors">
<input type="hidden" name="action" value="SaveModifyDisplay">

{counter assign=source_count start=0 print=0}
{foreach name=connectors from=$SOURCES key=name item=source}
{counter assign=source_count}
<input type="hidden" name="source{$source_count}" value="{$source.id}">
{/foreach}
<input type="hidden" name="display_values" value="">
<input type="hidden" name="display_sources" value="">
<input type="hidden" name="reset_to_default" value="">

<table border="0" class="actionsContainer">
<tr><td>
<input id="connectors_top_save" title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" onclick="calculateValues();" type="submit" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
<input id="connectors_top_cancel" title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="document.ModifyDisplay.action.value='ConnectorSettings'; document.ModifyDisplay.module.value='Connectors';" type="submit" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
</td></tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr><td>
<div >
<div id="container" style="height: 465px">
</div>
</div>
</td></tr>
</table>
<table border="0" class="actionsContainer">
<tr><td>
<input id="connectors_bottom_save" title="{$APP.LBL_SAVE_BUTTON_LABEL}" class="button" onclick="calculateValues();" type="submit" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
<input id="connectors_bottom_cancel" title="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="button" onclick="document.ModifyDisplay.action.value='ConnectorSettings'; document.ModifyDisplay.module.value='Connectors';" type="submit" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
</td></tr>
</table>
</form>

<script type="text/javascript">
{literal}
YAHOO.util.Event.onDOMReady(SourceTabs.fitContainer);
{/literal}
</script>


<script type="text/javascript">
{literal}
function calculateValues() {
    tabview = SourceTabs.getTabView();
    display_vals = ''
    source_vals = '';
    sources = new Array();
    //Get the source divs
    elements = tabview.getElementsByClassName('sources_table', 'table');
    for(el in elements) {
        if(typeof elements[el] == 'function') {
           continue;
        }

        source_vals += ',' + elements[el].id;

    }

    //Get the enabled div elements
    elements = tabview.getElementsByClassName('enabled_module_workarea', 'div');
    for(el in elements) {
        if(typeof elements[el] == 'function') {
           continue;
        }

        //Get the li elements
 		enabled_list = YAHOO.util.Dom.getElementsByClassName('noBullet2', 'li', elements[el]);
        for(li in enabled_list) {
            if(typeof enabled_list[li] != 'function') {
                display_vals += ',' + enabled_list[li].getAttribute('id');
            }
        }
    }

    document.ModifyDisplay.display_values.value = display_vals != '' ? display_vals.substr(1,display_vals.length) : '';
    document.ModifyDisplay.display_sources.value = source_vals != '' ? source_vals.substr(1, source_vals.length) : '';
}

YAHOO.util.Event.onDOMReady(SourceTabs.fitContainer);
{/literal}
</script>
