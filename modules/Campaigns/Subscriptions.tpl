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

<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='modules/Connectors/tpls/tabs.css'}"/>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td colspan='100'><h2>{$title}</h2></td></tr>
<tr><td colspan='100'>
{$description}
</td></tr><tr><td><br></td></tr><tr><td colspan='100'>

<form name="ConfigureSubs" method="POST"  method="POST" action="index.php">
{sugar_csrf_form_token}

    <form enctype="multipart/form-data" id="SubsForm" name="SubsForm" method="POST" action="index.php">
{sugar_csrf_form_token}
    <input type="hidden" name="module" value="Campaigns">
    <input type="hidden" name="action" value="Subscriptions">
    <input type="hidden" name="enabled_subs" value="">
    <input type="hidden" name="disabled_subs" value="">
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}">
    <input type="hidden" name="return_id" value="{$RETURN_ID}">
    <input type="hidden" name="module_tab" value="{$smarty.request.module_tab}">
    <input type="hidden" name="orig_disabled_values" id="orig_disabled_values" value="{$disabled_subs_string}">
    <input type="hidden" name="orig_enabled_values" id="orig_enabled_values" value="{$enabled_subs_string}">
    <input type="hidden" name="record" value="{$RECORD}">
    <input type="hidden" name="subs_action" value="process">

    {sugar_action_menu buttons=$BUTTONS}

    <div class='add_table' style='margin-bottom:5px'>
        <table id="ConfigureSubs" class="themeSettings edit view" style='margin-bottom:0px;' border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><span><b>{$MOD.LBL_ALREADY_SUBSCRIBED_HEADER}</b></span></td>
                <td><span><b>{$MOD.LBL_UNSUBSCRIBED_HEADER}</b>{sugar_help text=$MOD.LBL_UNSUBSCRIBED_HEADER_EXPL }
                </span></td>
            </tr>
            <tr>
                <td width='1%'>
                    <div id="enabled_div" class="enabled_tab_workarea">
                        <ul id="enabled_ul" class="module_draglist">
                            {foreach from=$enabled_subs key=dirname item=name}
                            <li id="{$dirname}" class="noBullet2">{$name}</li>
                            {/foreach}
                        </ul>
                    </div>
                </td>
                <td>
                    <div id="disabled_div" class="disabled_tab_workarea">
                        <ul id="disabled_ul" class="module_draglist">
                            {foreach from=$disabled_subs key=dirname item=name}
                            <li id="{$dirname}" class="noBullet2">{$name}</li>
                            {/foreach}
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {sugar_action_menu buttons=$BUTTONS}
</form>


<script type="text/javascript">
{literal}

var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var DDM = YAHOO.util.DragDropMgr;

function save() {
    var enabled_display_vals = '';
    var disabled_display_vals = '';

    //Get the enabled div elements
    var elements = document.getElementById('enabled_div');
    //Get the li elements
 	var enabled_list = YAHOO.util.Dom.getElementsByClassName('noBullet2', 'li', elements);
    for(var li in enabled_list) {
        if(typeof enabled_list[li] != 'function') {
            enabled_display_vals += ',' + enabled_list[li].getAttribute('id');
        }
    }
    document.ConfigureSubs.enabled_subs.value = enabled_display_vals != '' ? enabled_display_vals.substr(1,enabled_display_vals.length) : '';

    var elements = document.getElementById('disabled_div');
    //Get the li elements
    var disabled_list = YAHOO.util.Dom.getElementsByClassName('noBullet2', 'li', elements);
    for(var li in disabled_list) {
        if(typeof disabled_list[li] != 'function') {
            disabled_display_vals += ',' + disabled_list[li].getAttribute('id');
        }
    }

    document.ConfigureSubs.disabled_subs.value = disabled_display_vals != '' ? disabled_display_vals.substr(1,disabled_display_vals.length) : '';
}


(function() {

YAHOO.example.DDApp = {
init: function() {
{/literal}
    new YAHOO.util.DDTarget("enabled_ul");
    new YAHOO.util.DDTarget("disabled_ul");

    {foreach from=$enabled_subs key=module item=moduleDisplay}
    {if $module != $currentTheme}new YAHOO.example.DDList("{$module}");{/if}
    {/foreach}

    {foreach from=$disabled_subs key=module item=moduleDisplay}
         new YAHOO.example.DDList("{$module}");
    {/foreach}
{literal}
}
};

YAHOO.example.DDList = function(id, sGroup, config) {
    YAHOO.example.DDList.superclass.constructor.call(this, id, sGroup, config);
    var el = this.getDragEl();
    Dom.setStyle(el, "opacity", 0.67);
    this.goingUp = false;
    this.lastY = 0;
};


YAHOO.extend(YAHOO.example.DDList, YAHOO.util.DDProxy, {

    startDrag: function(x, y) {

        // make the proxy look like the source element
        var dragEl = this.getDragEl();
        var clickEl = this.getEl();
        Dom.setStyle(clickEl, "visibility", "hidden");

        dragEl.innerHTML = clickEl.innerHTML;

        Dom.setStyle(dragEl, "color", Dom.getStyle(clickEl, "color"));
        Dom.setStyle(dragEl, "backgroundColor", Dom.getStyle(clickEl, "backgroundColor"));
        Dom.setStyle(dragEl, "border", "2px solid gray");
    },

    endDrag: function(e) {

        var srcEl = this.getEl();
        var proxy = this.getDragEl();

        // Show the proxy element and animate it to the src element's location
        Dom.setStyle(proxy, "visibility", "");
        var a = new YAHOO.util.Motion(
            proxy, {
                points: {
                    to: Dom.getXY(srcEl)
                }
            },
            0.2,
            YAHOO.util.Easing.easeOut
        )
        var proxyid = proxy.id;
        var thisid = this.id;

        // Hide the proxy and show the source element when finished with the animation
        a.onComplete.subscribe(function() {
                Dom.setStyle(proxyid, "visibility", "hidden");
                Dom.setStyle(thisid, "visibility", "");
            });
        a.animate();
    },

    onDragDrop: function(e, id) {

        // If there is one drop interaction, the li was dropped either on the list,
        // or it was dropped on the current location of the source element.
        if (DDM.interactionInfo.drop.length === 1) {

            // The position of the cursor at the time of the drop (YAHOO.util.Point)
            var pt = DDM.interactionInfo.point;

            // The region occupied by the source element at the time of the drop
            var region = DDM.interactionInfo.sourceRegion;

            // Check to see if we are over the source element's location.  We will
            // append to the bottom of the list once we are sure it was a drop in
            // the negative space (the area of the list without any list items)
            if (!region.intersect(pt)) {
                var destEl = Dom.get(id);
                var destDD = DDM.getDDById(id);
                destEl.appendChild(this.getEl());
                destDD.isEmpty = false;
                DDM.refreshCache();
            }

        }
    },

    onDrag: function(e) {

        // Keep track of the direction of the drag for use during onDragOver
        var y = Event.getPageY(e);

        if (y < this.lastY) {
            this.goingUp = true;
        } else if (y > this.lastY) {
            this.goingUp = false;
        }

        this.lastY = y;
    },

    onDragOver: function(e, id) {

        var srcEl = this.getEl();
        var destEl = Dom.get(id);

        // We are only concerned with list items, we ignore the dragover
        // notifications for the list.
        if (destEl.nodeName.toLowerCase() == "li") {
            var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;

            if (this.goingUp) {
                p.insertBefore(srcEl, destEl); // insert above
            } else {
                p.insertBefore(srcEl, destEl.nextSibling); // insert below
            }

            DDM.refreshCache();
        }
    }
});

Event.onDOMReady(YAHOO.example.DDApp.init, YAHOO.example.DDApp, true);

})();
{/literal}
</script>
