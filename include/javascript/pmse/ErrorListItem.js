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
var ErrorListItem = function (options) {
    ListContainer.call(this, options);
    this.messagecontainer = null ;
    this.iconContainer = null;
    this.titleContainer = null;
    this.errorType = null;
    this.errorId = null;
    this.title = null;
    this.onClick = null;
    this.parent = null;

	this.listOfTypes =  
		{	
			AdamGatewayEVENTBASED : "adam-tree-icon-gateway-exclusive",  
			AdamGatewayINCLUSIVE : "adam-tree-icon-gateway-exclusive", 
			AdamEventSTARTLeads : "adam-tree-icon-start-leads",
			AdamActivityUSERTASK : "adam-tree-icon-user-task",
			AdamEventSTARTOpportunities : "adam-tree-icon-start-opportunities",
			AdamEventSTARTDocuments : "adam-tree-icon-start-documents",
			AdamEventSTART : "adam-tree-icon-start",
			AdamGatewayEXCLUSIVE : "adam-tree-icon-gateway-exclusive",
			AdamGatewayPARALLEL : "adam-tree-icon-gateway-parallel",
			AdamEventINTERMEDIATETIMER  : "adam-tree-icon-intermediate-timer",
			AdamEventENDEMPTY  :"adam-tree-icon-end",
			AdamEventINTERMEDIATEMESSAGE  : "adam-tree-icon-intermediate-message",
			textannotation : "adam-tree-icon-textannotation ",
			AdamEventSTARTMESSAGE : "adam-tree-icon-start",
			AdamActivitySCRIPTTASK : "adam-tree-icon-user-task"
		};
    ErrorListItem.prototype.initObject.call(this, options);
};

ErrorListItem.prototype = new ListContainer();
ErrorListItem.prototype.type = 'ErrorListItem';

ErrorListItem.prototype.family = 'ErrorListItem';

ErrorListItem.prototype.initObject = function (options) {
	var defaults = {
		errorType : "",
		errorId : "",
		title : "[untitle]",
		onClick : null,
		parent : null
	};
	jQuery.extend(true, defaults, options);

	this.setErrorType(defaults.errorType);
	this.setErrorId(defaults.errorId);
	this.setTitle(defaults.title);
	this.setOnClick(defaults.onClick);
	this.setParent(defaults.parent);
};

ErrorListItem.prototype.setParent = function (parent) {
	this.parent = parent;
	return this;
};

ErrorListItem.prototype.getParent = function () {
	return this.parent;
};

ErrorListItem.prototype.setOnClick = function (handler) {
	if ( !(typeof handler === 'function' || handler === null) ) {
		throw new Error ("ErrorListItem.setInconHandler(): the value is invalid");
	}
	this.onClick = handler;
	return this;
};

ErrorListItem.prototype.attachListeners = function () {
    var that = this, item;
    jQuery(this.html).click(function(e){
    	if (typeof that.onClick === 'function' ) {
    		if ( that.parent ) {
				that.onClick(that.parent, that, that.errorType, that.errorId);	
    		} else {
	    		that.onClick(that, that.errorType, that.errorId);				
    		}
    			that.select();
    	}
    });
    return this;
};

ErrorListItem.prototype.setSelect = function (value) {
	if ( !(typeof value === "boolean") ) {
		throw new Error("ErrorListItem.select(): error in parameter");
	}
	this.selected = value;
	if ( this.html ) {
		if ( this.selected ) {
			this.select();
		} else {
			this.deselect();
		}
	}
	return this;
};

ErrorListItem.prototype.select = function () {
	if (this.html){
		if (this.parent){
			item = this.parent.getSelectedItem();
			if(item && typeof(item.deselect) != 'undefined'){
				item.deselect();
			}
			this.parent.setSelectedItem(this);
		}
		jQuery(this.getHTML()).css("background","#f3f8fe");		
	}
	return this;
};

ErrorListItem.prototype.deselect = function () {
	if (this.html){
		jQuery(this.getHTML()).css("background","inherit")	
	}
	return this;
};

ErrorListItem.prototype.setTitle = function (title) {
	if (!(typeof title === "string")) {
		throw new Error ("ErrorListItem.setTitle(): the value is invalid");					
	}
	this.title = title;
	if (this.html){
		this.titleContainer.textContent = this.title;
		this.resizeWidthTitle();
	}
	return this;	
};

ErrorListItem.prototype.resizeWidthTitle = function () {
	var auxWidth1, auxWidth2;
	if ( this.html ) {
		auxWidth1 = jQuery(this.titleContainer).outerWidth();
		this.titleContainer.style.width = "auto";
		auxWidth2 = jQuery(this.titleContainer).outerWidth();
		if ( auxWidth2 > auxWidth1 ) {
			this.titleContainer.title = this.title;
		} else {
			this.titleContainer.title = "";
		}
		this.titleContainer.style.width = "80%";
	}
	return this;
};

ErrorListItem.prototype.getTitle  = function () {
	return this.title;
};

ErrorListItem.prototype.setErrorId = function (id) {
	if (!(typeof id === "string")) {
		throw new Error ("ErrorListItem.addItem(): the value is invalid");					
	}
	this.errorId  = id;
	return this;
};

ErrorListItem.prototype.getErrorId = function () {
	return this.errorId;
};

ErrorListItem.prototype.createHTML = function () {
    var messagecontainer, iconContainer, titleContainer; 
    if (!this.html) {
	    ListContainer.prototype.createHTML.call(this);
	    messagecontainer = this.createHTMLElement('ul');
	    messagecontainer.className = "messagecontainer comments ";
	    messagecontainer.style.margin = "0 0 9px 25px";
	    iconContainer = this.createHTMLElement('i');
	    iconContainer.className = "iconContainer";
	    //iconContainer.textContent = "[x]"
	    titleContainer = this.createHTMLElement('span');
	    titleContainer.className = "titleContainer adam-error-color";
	    this.body.appendChild(iconContainer);
	    this.body.appendChild(titleContainer);
	    this.body.appendChild(messagecontainer);
	    this.messagecontainer = messagecontainer;
	    this.iconContainer = iconContainer;
	    this.titleContainer = titleContainer;
	    this.paintItems();
		this.setErrorType(this.errorType);
		this.setTitle(this.title);
		this.html.style.height = "auto";
		this.attachListeners();

		$(this.html).addClass('activitystream-posts-comments-container');
		this.html.style.padding = "8px";
		this.html.style.width = "auto";
		this.html.style.height = "auto";
		this.titleContainer.style.paddingLeft = "10px";
		this.fixedStyles();
    }
    return this.html;
};

ErrorListItem.prototype.fixedStyles = function () {
	if (this.html) {
		jQuery(this.titleContainer).css({
			"width": "80%",
			"text-overflow": "ellipsis",
			"white-space": "nowrap",
			"overflow": "hidden",
			"display": "inline-block",
			"cursor" : "pointer"
		});
	}
	return this;
}

ErrorListItem.prototype.paintItems = function () {
	var i; 
	if ( this.messagecontainer ) {
		for ( i = 0 ; i < this.items.length ; i+=1 ) {
			this.messagecontainer.appendChild(this.items[i].getHTML());
		}
	}
	return this;
};

ErrorListItem.prototype.setErrorType = function (errorType){
	if ( !(typeof errorType === "string") ) {
		throw new Error ("ErrorListItem.setErrorType(): not valid, should be a string value");
	}

	this.errorType = errorType;
	if ( this.html ) {
		jQuery(this.html).removeClass();			
		jQuery(this.html).addClass("error-"+errorType);
		this.iconContainer.className = this.listOfTypes[errorType];
	}
	return this;
};
ErrorListItem.prototype.addItem = function (item) {
	var newItem;
	if ( item instanceof ErrorMessageItem ) {
		newItem = item;
	} else if ( typeof  item === "object" ) {
		newItem = new ErrorMessageItem(item);
	} else {
		throw new Error ("ErrorListItem.addItem(): the value is invalid");
	}
	newItem.setParent(this);
	this.items.push(newItem);
	if ( this.html ) {
		this.messagecontainer.appendChild(newItem.getHTML());
	}
	return this;
};
ErrorListItem.prototype.getItemByMessageId = function (messageId){
	var i, item;
	for ( i = 0 ; i < this.items.length ; i+=1 ) {
		if (this.items[i].getMessageId() === messageId){
			item = this.items[i];
		}
	}
	if ( item ) {
		return item;					
	} else {
		null;
	}
};