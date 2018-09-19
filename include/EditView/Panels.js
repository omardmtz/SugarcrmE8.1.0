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

function initPanel(id, state) {
    panelId = 'detailpanel_' + id;
    expandPanel(id);
    if(state == 'collapsed') {
        collapsePanel(id);
    }
}

function expandPanel(id) {
    var panelId = 'detailpanel_' + id;
    document.getElementById(panelId).className = document.getElementById(panelId).className.replace(/(expanded|collapsed)/ig, '') + ' expanded';
}

function collapsePanel(id) {
    var panelId = 'detailpanel_' + id;
    document.getElementById(panelId).className = document.getElementById(panelId).className.replace(/(expanded|collapsed)/ig, '') + ' collapsed';
}

function setCollapseState(mod, panel, isCollapsed) {
    var sugar_panel_collase = Get_Cookie("sugar_panel_collase");
    if(sugar_panel_collase == null) {
        sugar_panel_collase = {};
    } else {
        sugar_panel_collase = YAHOO.lang.JSON.parse(sugar_panel_collase);
    }
    sugar_panel_collase[mod] = sugar_panel_collase[mod] || {};
    sugar_panel_collase[mod][panel] = isCollapsed;

    Set_Cookie('sugar_panel_collase', YAHOO.lang.JSON.stringify(sugar_panel_collase),30,'/','','');
}