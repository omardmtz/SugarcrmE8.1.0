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
var ListItem = function(settings) {
	DataItem.call(this, settings);
	ListItem.prototype.init.call(this, settings);
};

ListItem.prototype = new DataItem();
ListItem.prototype.constructor = ListItem;
ListItem.prototype.type = "ListItem";

ListItem.prototype.init = function (settings) {
	var defaults = {
		text: "[listitem]"
	};
	jQuery.extend(true, defaults, settings);
	this.setText(defaults.text);
};

ListItem.prototype.setVisible = function (value) {
    if (_.isBoolean(value)) {
        this.visible = value;
        if (this.html) {
            if (value) {
                this.style.addProperties({display: ""});
            } else {
                this.style.addProperties({display: "none"});
            }
        }
    }
    return this;
};

ListItem.prototype.setText = function (text) {
	var finalText;
	if (!(typeof text === 'string' || typeof text === 'function')) {
		throw new Error("setText(): The parameter must be a string or function.");
	}
	this._text = text;
	if (this._htmlItemContent) {
		finalText = this._getFinalText();
		if(isHTMLElement(finalText)) {
			this._htmlItemContent.appendChild(finalText);
		} else {
			this._htmlItemContent.textContent = finalText;
		}
	}
	return this;
};

ListItem.prototype.createHTML = function () {
	if(!this.html) {
		this.html = this.createHTMLElement('li');
		this.html.className = 'adam list-item';
		this._htmlItemContent = this.html;
		this.setText(this._text);
		this._attachListeners();
		this.setVisible(this.visible);
	}
	return this.html;
};