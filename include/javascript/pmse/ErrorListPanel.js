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
var ErrorListPanel = function (options) {
    ListContainer.call(this, options);
    this.onClickItem = null;
    this.title = null;
    this.parent = null;
    this.titleContainer = null;
    this.selectedItem = null;
    this.classItemSelected = null;
    ErrorListPanel.prototype.initObject.call(this, options);
};

ErrorListPanel.prototype = new ListContainer();

ErrorListPanel.prototype.type = 'ErrorListPanel';

ErrorListPanel.prototype.family = 'ErrorListPanel';

ErrorListPanel.prototype.initObject = function (options) {
	var defaults = {
		onClickItem : null,
		title : "[Untitle]",
		parent : null,
		classItemSelected : "selected"
	}

	jQuery.extend(true, defaults , options);

	this.setOnClickItem(defaults.onClickItem);
	this.setTitle(defaults.title);
	this.setParent(defaults.parent);
	this.setClassItemSelected(defaults.classItemSelected);
};

ErrorListPanel.prototype.setClassItemSelected = function(className) {
	if ( !(typeof className === "string") ) {
		throw  new Error ("ErrorListPanel.setClassItemSelected:the value is invalid ");
	}
	this.classItemSelected = className;
	return this;
};
ErrorListPanel.prototype.getClassItemSelected = function() {
	return this.classItemSelected;
};

ErrorListPanel.prototype.setParent = function (parent) {
	this.parent = parent;
	return this;
};

ErrorListPanel.prototype.getParent = function () {
	return this.parent;
};

ErrorListPanel.prototype.setTitle = function (title) {
	if ( !(typeof title === "string") ) {
		throw  new Error ("ErrorListPanel.setTitle():the value is invalid ");
	}
	this.title = title;
	if ( this.html ) {
		this.titleContainer.textContent = title;
	}
	return this;
};

ErrorListPanel.prototype.getTitle = function () {
	return this.title;
};

ErrorListPanel.prototype.setOnClickItem = function (handler) {
	var i;
	if ( !(typeof handler === 'function' || handler === null) ) {
		throw new Error ("ErrorListPanel.setInconHandler(): the value is invalid");
	}
	this.onClickItem = handler;
	if (this.items.length){
		for ( i = 0 ; i < this.items.length ; i+=1 ) {
			this.items[i].onClick = this.onClickItem;
		}
	}
	return this;
};

ErrorListPanel.prototype.createHTML = function () {
    var titleContainer; 
    if (!this.html) {
	    ListContainer.prototype.createHTML.call(this);
	    titleContainer = this.createHTMLElement('h4');
	    titleContainer.className = "dashlet-title adam-error-color";
		this.html.appendChild(titleContainer);
	    this.titleContainer = titleContainer; 
	    jQuery(this.body).remove();
	    body = this.createHTMLElement('div');
	    body.className = 'j-container';
	    this.html.appendChild(body);
	    this.body = body;
	    this.setBodyHeight(this.bodyHeight);
	    this.paintItems();	    
	    this.setTitle(this.title);
	    this.customStyles();
    }
    return this.html;
};
ErrorListPanel.prototype.customStyles = function () {
	if (this.html){
		this.body.style.listStyle = "none";
		this.titleContainer.style.margin = "0px";
		this.titleContainer.style.padding = "6px 5px 6px 10px";
		this.titleContainer.style.fontWeight = 500;
		this.titleContainer.style.background = "#f6f6f6";
		this.titleContainer.style.borderBottom = "1px solid #ddd";
	    this.html.style.width = "auto";
	    this.html.style.background = "white";
	    this.html.style.height = "auto";
	    this.html.style.border = "1px solid #ddd";
	    jQuery(this.html).css("borderRadius", "3px");
	    jQuery(this.titleContainer).css("borderRadius", "3px 3px 0px 0px");
	}
	return this;
};

ErrorListPanel.prototype.paintItems = function () {
	var i; 
	if ( this.html ) {
		for ( i = 0 ; i < this.items.length ; i+=1 ) {
			this.body.appendChild(this.items[i].getHTML());
		}
	}
	return this;
};

ErrorListPanel.prototype.addItem = function (item) {
	var newItem;
	if ( item instanceof ErrorListItem ) {
		newItem = item;
	} else if ( typeof  item === "object" ) {
		newItem = new ErrorListItem(item);
	} else {
		throw new Error ("ErrorListPanel.addItem(): the value is invalid");
	}
	newItem.setParent(this);
	newItem.onClick  = this.onClickItem;
	this.items.push(newItem);
	if ( this.html ) {
		this.body.appendChild(newItem.getHTML());
	}
	return this;
};

ErrorListPanel.prototype.getContainerMessageById = function (id) {
	var item, i;
	for ( i = 0 ; i < this.items.length ; i+=1 ) {
		if (this.items[i].getErrorId() === id){
			item = this.items[i];
		}
	}
	if ( item ) {
		return item;					
	} else {
		null;
	}
};

ErrorListPanel.prototype.addNewMessage = function (containerId, message, messageId) {
	var item;
	item = this.getContainerMessageById(containerId);
	if (item){
		item.addItem({message:message, messageId: messageId});
	}
	return this;
};

ErrorListPanel.prototype.removeMessage = function (containerId, messageId) {
	var item, messageItem, index;
	item = this.getContainerMessageById(containerId);
	if ( item ) {
		messageItem = item.getItemByMessageId(messageId);
		if ( messageItem ) {
			index  = item.items.indexOf(messageItem);
			item.removeItem(index);
		}
	}
	return this;
};

ErrorListPanel.prototype.removeItemById = function (id) {
	var items = this.getItems(), i, index, item;

	if ( !(typeof id ===  "string") ) {
		throw new Error("ErrorListPanel.removeItemById(): the value is invalid");
	}

	for  ( i = 0 ; i < items.length; i+=1 ) {
		if (items[i].getErrorId() === id ) {
			index = i;
			break;
		}
	}
	if ( index !== undefined ) {
		item = this.getItem(index);
		this.removeItem(index);
		return item;
	} else {
		return null;
	}
};

ErrorListPanel.prototype.appendTo = function (tagId) {
	var tag = tagId || "";
	if (jQuery(tag).length) {
		jQuery(tag).append(this.getHTML());	
	}
	return this;
};

ErrorListPanel.prototype.getItemById = function (id) {
	var items = this.getItems(), i, index, item;

	if ( !(typeof id ===  "string") ) {
		throw new Error("ErrorListPanel.removeItemById(): the value is invalid");
	}

	for  ( i = 0 ; i < items.length; i+=1 ) {
		if (items[i].getErrorId() === id ) {
			index = i;
			break;
		}
	}
	if ( index !== undefined ) {
		item = this.getItem(index);
		return item;
	} else {
		return null;
	}
};

ErrorListPanel.prototype.setSelectedItem = function (item) {
	if (item instanceof ErrorListItem) {
		this.selectedItem = item;
	}
	return this;
};

ErrorListPanel.prototype.getSelectedItem = function () {
	return this.selectedItem;
};

ErrorListPanel.prototype.getAllErros = function () {
	var count = 0;
	for ( i = 0 ; i < this.items.length ; i+=1 ) {
		count = count + this.items[i].getItems().length;
	}
	return count;
};

ErrorListPanel.prototype.resizeWidthTitleItems = function () {
	var i;
	if ( this.html ) {
		for ( i = 0 ; i < this.items.length ; i+=1 ) {
			this.getItem(i).resizeWidthTitle();
		}
	}
	return this;
};