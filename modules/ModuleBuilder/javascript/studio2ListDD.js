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

max_default_columns = 6;

 Studio2.ListDD = function(el, sGroup, fromOnly) {
 	if (typeof el == 'number') {
 		el = el + "";
	}
 	if (typeof el == 'string')
 		el = document.getElementById(el);
	if (el != null) {
		var Dom = YAHOO.util.Dom;
		Studio2.ListDD.superclass.constructor.call(this, el, sGroup);
		this.addInvalidHandleType("input");
		this.addInvalidHandleType("a");
		var dEl = this.getDragEl()
		Dom.setStyle(dEl, "borderColor", "#FF0000");
		Dom.setStyle(dEl, "backgroundColor", "#e5e5e5");
		Dom.setStyle(dEl, "opacity", 0.76);
		Dom.setStyle(dEl, "filter", "alpha(opacity=76)");
		this.fromOnly = fromOnly;
	}
};

YAHOO.extend(Studio2.ListDD, YAHOO.util.DDProxy, {
	copyStyles : {'opacity':"", 'border':"", 'height':"", 'filter':"", 'zoom':""},
    startDrag: function(x, y){
		//We need to make sure no inline editors are in use, as drag.drop can break them
		if (typeof (SimpleList) != "undefined") {
			SimpleList.endCurrentDropDownEdit();
		}
		
		var Dom = YAHOO.util.Dom;
		var dragEl = this.getDragEl();
		var clickEl = this.getEl();
		
		this.parentID = clickEl.parentNode.id;
		this.clickContent = clickEl.innerHTML;
		dragEl.innerHTML = clickEl.innerHTML;
		
		Dom.addClass(dragEl, clickEl.className);
		Dom.setStyle(dragEl, "color", Dom.getStyle(clickEl, "color"));
		Dom.setStyle(dragEl, "height", Dom.getStyle(clickEl, "height"));
		Dom.setStyle(dragEl, "border", "1px solid #aaa");
		
		// save the style of the object 
		if (this.clickStyle == null) {
			this.clickStyle = {};
			for (var s in this.copyStyles) {
				this.clickStyle[s] = clickEl.style[s];
			}
			if (typeof(this.clickStyle['border']) == 'undefined' || this.clickStyle['border'] == "") 
				this.clickStyle['border'] = "1px solid";
		}
		
		Dom.setStyle(clickEl, "opacity", 0.5);
		Dom.setStyle(clickEl, "filter", "alpha(opacity=10)");
		Dom.setStyle(clickEl, "border", '2px dashed #cccccc');
        Studio2.setScrollObj(this);
	},
	
	updateTabs: function(){
		studiotabs.moduleTabs = [];
		for (j = 0; j < studiotabs.slotCount; j++) {
		
			var ul = document.getElementById('ul' + j);
			studiotabs.moduleTabs[j] = [];
			items = ul.getElementsByTagName("li");
			for (i = 0; i < items.length; i++) {
				if (items.length == 1) {
					items[i].innerHTML = SUGAR.language.get('ModuleBuilder', 'LBL_DROP_HERE');
				}
				else if (items[i].innerHTML == SUGAR.language.get('ModuleBuilder', 'LBL_DROP_HERE')) {
					items[i].innerHTML = '';
				}
				studiotabs.moduleTabs[ul.id.substr(2, ul.id.length)][studiotabs.subtabModules[items[i].id]] = true;
			}	
		}	
	},
	
	endDrag: function(e){
        Studio2.clearScrollObj();
        ModuleBuilder.state.markAsDirty();
		var clickEl = this.getEl();
		var clickExEl = new YAHOO.util.Element(clickEl);
		dragEl = this.getDragEl();
		dragEl.innerHTML = "";
		clickEl.innerHTML = this.clickContent;
		
		var p = clickEl.parentNode;
		if (p.id == 'trash') {
			p.removeChild(clickEl);
			this.lastNode = false;
			this.updateTabs();
			return;
		}
		
		for(var style in this.clickStyle) {
			if (typeof(this.clickStyle[style]) != 'undefined')
				clickExEl.setStyle(style, this.clickStyle[style]);
			else
				clickExEl.setStyle(style, '');
		}
		
		this.clickStyle = null;
		
		if (this.lastNode) {
			this.lastNode.id = 'addLS' + addListStudioCount;
			studiotabs.subtabModules[this.lastNode.id] = this.lastNode.module;
			yahooSlots[this.lastNode.id] = new Studio2.ListDD(this.lastNode.id, 'subTabs', false);
			addListStudioCount++;
			this.lastNode.style.opacity = 1;
			this.lastNode.style.filter = "alpha(opacity=100)";
		}
		this.lastNode = false;
		this.updateTabs();
		
		dragEl.innerHTML = "";
	},

    onDrag: Studio2.onDrag,
    
	onDragOver: function(e, id){
		var el = document.getElementById(id);
		/**
		 * Start:	Bug_#44445 
		 * Limit number of columns in dashlets on 6!
		 */
		var parent = el.parentNode.parentNode
		if(studiotabs.view == 'dashlet'){
			if(parent.id == 'Default'){
				var cols = el.parentNode.getElementsByTagName("li");
				if(cols.length > max_default_columns){
					return;
				}	
			}	
		}
		/**
		 * End:	Bug_#44445
		 */
		if (this.lastNode) {
			this.lastNode.parentNode.removeChild(this.lastNode);
			this.lastNode = false;
		}
		if (id.substr(0, 7) == 'modSlot') {
			return;
		}
		el = document.getElementById(id);
		dragEl = this.getDragEl();
		
		var mid = YAHOO.util.Dom.getY(el) + (el.clientHeight / 2);
		var el2 = this.getEl();
		var p = el.parentNode;
		if ((this.fromOnly || (el.id != 'trashcan' && el2.parentNode.id != p.id && el2.parentNode.id == this.parentID))) {
			if (typeof(studiotabs.moduleTabs[p.id.substr(2, p.id.length)][studiotabs.subtabModules[el2.id]]) != 'undefined') 
				return;
		}
		
		if (this.fromOnly && el.id != 'trashcan') {
			el2 = el2.cloneNode(true);
			el2.module = studiotabs.subtabModules[el2.id];
			el2.id = 'addListStudio' + addListStudioCount;
			this.lastNode = el2;
			this.lastNode.clickContent = el2.clickContent;
			this.lastNode.clickBorder = el2.clickBorder;
			this.lastNode.clickHeight = el2.clickHeight
		}
		
		if (YAHOO.util.Dom.getY(dragEl) < mid) { // insert on top triggering item
			p.insertBefore(el2, el);
		}
		else { // insert below triggered item
			p.insertBefore(el2, el.nextSibling);
		}
	}
});
