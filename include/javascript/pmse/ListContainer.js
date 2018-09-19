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
// jscs:disable
var PMSE = PMSE || {};
var ListContainer = function (options) {
	PMSE.Container.call(this, options);
	ListContainer.prototype.initObject.call(this, options);
};

ListContainer.prototype = new PMSE.Container();

ListContainer.prototype.type = 'ListContainer';

ListContainer.prototype.family = 'ListContainer';

ListContainer.prototype.initObject = function (options) {
};

ListContainer.prototype.setItems = function (items) {
	var i;
	this.clearItems();
	if (!(jQuery.isArray(items))) {
		throw new Error("ListContainer.setItems(): the value is invalid, should be a type array");
	}
	for ( i = 0 ; i < items.length ; i+=1 ) {
		this.addItem(items[i]);
	}
    return this;
};

ListContainer.prototype.addItem = function (item) {
	var newItem;
	if ( item instanceof ErrorMessageItem ) {
		newItem = item;
	} else if ( typeof  item === "object" ) {
		newItem = new ErrorMessageItem(item);
	} else {
		throw new Error ("ListContainer.addItem(): the value is invalid");
	}
	this.items.push(newItem);
	if ( this.html ) {
		this.messagecontainer.appendChild(newItem.getHTML());
	}
	return this;
};

ListContainer.prototype.clearItems = function () {
	var i, length = this.items.length;
	for ( i = 0 ; i < length ; i+=1 ) {
		this.removeItem(0)
	}	
	return this;
};

ListContainer.prototype.removeItem = function (index) {
	var item = this.items.splice(index,1)[0];  
	if ( item.html ) {
		jQuery(item.getHTML()).remove();
	}
	return this;
};


ListContainer.prototype.paintItems = function () {
	var i; 
	if ( this.messagecontainer ) {
		for ( i = 0 ; i < this.items.length ; i+=1 ) {
			this.body.appendChild(this.items[i].getHTML());
		}
	}
	return this;
};

ListContainer.prototype.createHTML = function () {
	if(!this.html){
		PMSE.Container.prototype.createHTML.call(this);
		this.html.style.position = "relative"
	}
	return this.html;
};

ListContainer.prototype.getItems = function () {
	return this.items;
};

ListContainer.prototype.getItem = function (index) {
	if (index >= 0 && index < this.items.length ) {
		return this.items[index];
	} else {
		throw new Error("ListContainer.getItem():the index does not exist");
	}
};