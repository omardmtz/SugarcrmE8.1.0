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

// $Id: studiodd.js 18703 2006-12-15 09:42:43Z majed $
/*Portions Copyright (c) 2006 Yahoo! Inc. All rights reserved. */

/**
 * @class a ygDDFramed implementation like ygDDMy, but the content channels are
 * not restricted to one column, and we drag a miniature representation of the
 * content channel rather than a frame of the channel.
 *
 * @extends YAHOO.util.DDProxy
 * @constructor
 * @param {String} id the id of the linked element
 * @param {String} sGroup the group of related DragDrop objects
 */


function ygDDSlot(id, sGroup) {
	
	if (id) {
		this.init(id, sGroup);
		this.initFrame();
	}

	// Change the style of the frame to be a miniature representation of a
	// content channel
		var s = this.getDragEl().style;
	s.borderColor = "transparent";
	s.backgroundColor = "#f6f5e5";
	s.opacity = 0.76;
	s.filter = "alpha(opacity=76)";

	// Specify that we do not want to resize the drag frame... we want to keep
	// the drag frame the size of our miniature content channel image
	this.resizeFrame = true;
        if(id == 's_field_delete'){
            this.isValidHandle = false;
        }
}

ygDDSlot.prototype = new YAHOO.util.DDProxy();
ygDDSlot.prototype.handleDelete = function(cur, curb){
     var parentID = (typeof(cur.parentID) == 'undefined')?cur.id.substr(4,cur.id.length):cur.parentID ;
     if(parentID.indexOf('field') == 0){
         return false;
     }
     var myfieldcount = field_count_MSI;
     addNewField('dyn_field_' + field_count_MSI, 'delete', '&nbsp;', '&nbsp;', 'deleted', 0, 'studio_fields')
     yahooSlots["dyn_field_" + myfieldcount] = new ygDDSlot("dyn_field_" + myfieldcount, "studio");
     ygDDSlot.prototype.handleSwap(cur, curb, document.getElementById("dyn_field_" + myfieldcount), document.getElementById("dyn_field_" + myfieldcount+ 'b'), true);
}

ygDDSlot.prototype.undo = function(transaction){
        ygDDSlot.prototype.handleSwap(document.getElementById(transaction['el']),document.getElementById(transaction['elb']), document.getElementById(transaction['cur']), document.getElementById(transaction['curb']), false);
}
ygDDSlot.prototype.redo = function(transaction){
        ygDDSlot.prototype.handleSwap(document.getElementById(transaction['el']),document.getElementById(transaction['elb']), document.getElementById(transaction['cur']), document.getElementById(transaction['curb']), false);
}

ygDDSlot.prototype.handleSwap = function(cur, curb,el, elb, record ){
    if(record){
    		if(curb){
        	jstransaction.record('studioSwap', {'cur': cur.id, 'curb': curb.id, 'el':el.id, 'elb':elb.id});
        }else{
        	jstransaction.record('studioSwap', {'cur': cur.id, 'curb': null, 'el':el.id, 'elb':null});
        }
    }
    var parentID1 = (typeof(el.parentID) == 'undefined')?el.id.substr(4,el.id.length):el.parentID ;
    var parentID2 = (typeof(cur.parentID) == 'undefined')?cur.id.substr(4,cur.id.length):cur.parentID ;
    var slot1 = YAHOO.util.DDM.getElement("slot_" + parentID1);
    var slot2 = YAHOO.util.DDM.getElement("slot_" + parentID2);

    var temp = slot1.value;
	slot1.value = slot2.value;
  	slot2.value = temp;
  	
	YAHOO.util.DDM.swapNode(cur, el);
	if(curb){
		YAHOO.util.DDM.swapNode(curb, elb);
	}
	//swap ids also or else form swaps don't work properly since the actual div is swapped
	cur.parentID = parentID1;
	el.parentID = parentID2;
	if(parentID1.indexOf('field') == 0){
		if(curb)curb.style.display = 'none';
		setMouseOverForField(cur, true);
	}else{
		if(curb)curb.style.display = 'inline';
		setMouseOverForField(cur, false);
	}
	if(parentID2.indexOf('field') == 0){
		if(elb)elb.style.display = 'none';
		setMouseOverForField(el, true);
	}else{
		if(elb)elb.style.display = 'inline';
		setMouseOverForField(el, false);
	}
}
ygDDSlot.prototype.onDragDrop = function(e, id) {
   
	var cur = this.getEl();	
	
	var curb;
    if ("string" == typeof id) {
        curb = YAHOO.util.DDM.getElement(cur.id + "b");
    } else {
        curb = YAHOO.util.DDM.getBestMatch(cur.id + "b").getEl();
    } 
	 if(id == 's_field_delete'){
	     id = ygDDSlot.prototype.handleDelete(cur, curb);
	     if(!id)return false;
	 }
    
    
    var el;
    if ("string" == typeof id) {
        el = YAHOO.util.DDM.getElement(id);
    } else {
        el = YAHOO.util.DDM.getBestMatch(id).getEl();
    }
    
  	 
  	 
  
    id2 = el.id + "b";
    if ("string" == typeof id) {
        elb =YAHOO.util.DDM.getElement(id2);
    } else {
        elb =YAHOO.util.DDM.getBestMatch(id2).getEl();
    } 
    
	ygDDSlot.prototype.handleSwap(cur, curb, el, elb, true)
	var dragEl = this.getDragEl();
	dragEl.innerHTML = '';
};

ygDDSlot.prototype.startDrag = function(x, y) {

	var dragEl = this.getDragEl();
	var clickEl = this.getEl();
	dragEl.innerHTML = clickEl.innerHTML;
	dragEl.className = clickEl.className;
	dragEl.style.color = clickEl.style.color;
	dragEl.style.border = "2px solid #aaa";
	
	// save the style of the object 
	this.clickContent = clickEl.innerHTML;
	this.clickBorder = clickEl.style.border;
	this.clickHeight = clickEl.style.height;
	clickElRegion = YAHOO.util.Dom.getRegion(clickEl);
	dragEl.style.height = 	clickEl.style.height;

	dragEl.style.width = 	(clickElRegion.right - clickElRegion.left) + 'px';
	clickEl.style.height = (clickElRegion.bottom - clickElRegion.top) + 'px';
	
	clickEl.style.border = '2px dashed #cccccc';
	clickEl.style.opacity = .5;
	clickEl.style.filter = "alpha(opacity=10)";

};

ygDDSlot.prototype.endDrag = function(e) {
	// disable moving the linked element
	var clickEl = this.getEl();

	if(this.clickHeight) 
	    clickEl.style.height = this.clickHeight;
	else 
		clickEl.style.height = '';
	
	if(this.clickBorder) 
	    clickEl.style.border = this.clickBorder;
	else 
		clickEl.style.border = '';
	clickEl.style.opacity = 1;
	clickEl.style.filter = "alpha(opacity=100)";
		
};
jstransaction.register('studioSwap',ygDDSlot.prototype.undo, ygDDSlot.prototype.redo);
