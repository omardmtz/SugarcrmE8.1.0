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
var PMSE = PMSE || {};
/**
 * @class PMSE.Tooltip
 * Handle tool tip messages
 * @extends PMSE.Element
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {Object} parent
 */
PMSE.Tooltip = function(options, parent) {
    PMSE.Element.call(this, options);
    this.icon =  null;
    this.css = null;
    this.message = null;
    this.parent = null;
    this.messageObject = null;
    this.hoverParent = null;
    this.hoverClass = null;
    PMSE.Tooltip.prototype.initObject.call(this, options, parent);
};

PMSE.Tooltip.prototype = new PMSE.Element();

PMSE.Tooltip.prototype.type = 'PMSE.Tooltip';

PMSE.Tooltip.prototype.family = 'PMSE.Tooltip';

PMSE.Tooltip.prototype.initObject = function(options, parent) {
    var defaults = {
        message: null,
        icon: 'adam-tooltip-icon-default',
        css: '',
        parent: parent || null,
        hoverParent: true,
        hoverClass: 'hovered'
    };
    $.extend(true, defaults, options);
    this.setIcon(defaults.icon)
        .setMessage(defaults.message)
        .setParent(defaults.parent)
        .setCss(defaults.css)
        .setHoverClass(defaults.hoverClass)
        .setHoverParent(defaults.hoverParent);
};

PMSE.Tooltip.prototype.setIcon = function(icon) {
    this.icon = icon;
    return this;
};

PMSE.Tooltip.prototype.setMessage = function(msg) {
    this.message = msg;
    return this;
};

PMSE.Tooltip.prototype.setParent = function(parent) {
    this.parent = parent;
    return this;
};


PMSE.Tooltip.prototype.setCss = function(value) {
    this.css = value;
    return this;
};

PMSE.Tooltip.prototype.setHoverParent = function(value) {
    this.hoverParent = value;
    return this;
};

PMSE.Tooltip.prototype.setHoverClass = function(css) {
    this.hoverClass = css;
    return this;
};

PMSE.Tooltip.prototype.createHTML = function() {
    var msgDiv, iconSpan, tooltipAnchor;

    tooltipAnchor = this.createHTMLElement('a');
    tooltipAnchor.href = '#';
    tooltipAnchor.className = 'adam-tooltip ' + this.css;

    iconSpan = this.createHTMLElement('span');
    iconSpan.className = this.icon;

    // msgDiv = this.createHTMLElement('div');
    // msgDiv.className = 'adam-tooltip-message-off';
    // msgDiv.innerHTML = this.message;

    //this.messageObject = msgDiv;

    tooltipAnchor.appendChild(iconSpan);
    //tooltipAnchor.appendChild(msgDiv);

    this.html = tooltipAnchor;

    this.attachListeners();
    return this.html;
};

PMSE.Tooltip.prototype.attachListeners = function() {
    var self = this;
    $(this.html).click(function(e) {
            e.preventDefault();
        })
        .mouseover(function(e) {
            e.stopPropagation();
            //console.log(e);
            self.show(e.pageX, e.pageY);
        })
        .mouseout(function(e) {
            e.stopPropagation();
            self.hide();
        });
};

PMSE.Tooltip.prototype.show = function(x, y) {
    var msgDiv;

    if (!this.messageObject) {
        msgDiv = this.createHTMLElement('div');
        msgDiv.className = 'adam-tooltip-message';
        msgDiv.innerHTML = this.message;
        msgDiv.style.position = 'absolute';
        msgDiv.style.top = (y + 10) + 'px';
        msgDiv.style.left = (x + 10) + 'px';
        msgDiv.style.zIndex = 1034;

        this.messageObject = msgDiv;
    }

    document.body.appendChild(this.messageObject);
    if (this.hoverParent && this.parent) {
        $(this.parent.html).addClass(this.hoverClass);
    }
};

PMSE.Tooltip.prototype.hide = function() {
    document.body.removeChild(this.messageObject);
    this.messageObject = null;
    if (this.hoverParent && this.parent) {
        $(this.parent.html).removeClass(this.hoverClass);
    }
};
