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
var ErrorMessageItem = function (options) {
	PMSE.Element.call(this, jQuery.extend(true, options , {
		/*width : 200,
		height : 20,*/
		position : "relative"
	}));
	this.message = null;
	this.messageId = null;
	this.messageContainer = null;
	this.parent = null;
	ErrorMessageItem.prototype.initObject.call(this, options);
};

ErrorMessageItem.prototype = new PMSE.Element();

ErrorMessageItem.prototype.type = "ErrorMessageItem";

ErrorMessageItem.prototype.family = "Element";

ErrorMessageItem.prototype.initObject = function (options) {
	var defaults = {
		message : "[no message]",
		messageId : "",
		parent : null
	}
	jQuery.extend(true, defaults, options);
	this.setMessage(defaults.message);
	this.setMessageId(defaults.messageId);
	this.setParent(defaults.parent);
};

ErrorMessageItem.prototype.setParent = function (parent){
	this.parent = parent;
	return this;
};

ErrorMessageItem.prototype.getParent = function (parent){
	return this.parent;
};

ErrorMessageItem.prototype.setMessageId = function (messageId) {
	if ( !(typeof messageId === "string") ) {
		throw new Error("ErrorMessageItem.setMessageId(): not valid, should be a string value");
	}
	this.messageId  = messageId;
	return this;
};

ErrorMessageItem.prototype.getMessageId = function () {
	return this.messageId;
};

ErrorMessageItem.prototype.setMessage = function (message) {
	if ( !(typeof message === "string") ) {
		throw new Error("ErrorMessageItem.setMessage(): not valid, should be a string value");
	}
	this.message  = message;
	if (this.html){
		this.messageContainer.textContent = this.message; 
	}
	return this;
};

ErrorMessageItem.prototype.getMessage = function (){
	return this.message;
};

ErrorMessageItem.prototype.createHTML = function () {
	var messageContainer;
    if (!this.html) {
        this.html = this.createHTMLElement('li');
        this.html.id = this.id;
        this.style.applyStyle();
        this.style.addProperties({
            position: "relative",
            left: this.x,
            top: this.y,
            width: this.width,
            height: this.height,
            zIndex: this.zOrder
        });
        messageContainer = this.createHTMLElement('span');
		messageContainer.className = "messageContainer";
		this.html.appendChild(messageContainer);
		this.messageContainer = messageContainer;
		this.setMessage(this.message);
		this.html.style.height = "auto";
		this.html.style.width = "auto";
		this.html.className = "comment";
		this.html.style.padding = "3px 3px 3px 0px";

    }
    return this.html;
};