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
 * @class PMSE.Button
 * Handles buttons
 * @extends PMSE.Element
 *
 * @constructor
 * Create a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
PMSE.Button = function(options, parent) {
    PMSE.Element.call(this, options);
    this.parent = null;
    this.caption = null;
    this.action = null;
    this.icon = null;
    this.cssClasses = [];
    PMSE.Button.prototype.initObject.call(this, options, parent);
};

PMSE.Button.prototype = new PMSE.Element();

PMSE.Button.prototype.type = 'PMSE.Button';
PMSE.Button.prototype.family = 'PMSE.Button';

PMSE.Button.prototype.initObject = function(options, parent) {
    var defaults, self = this;
    if (options.isAction) {
        this.loadAction(options, parent);
        this.setCssClasses((options && options.cssClasses) || []);
    } else {
        defaults = {
            caption: null,
            parent: parent || null,
            jtype: 'normal',
            handler: function() {},
            icon: null,
            cssClasses: []
        };
        $.extend(true, defaults, options);
        this.setCaption(defaults.caption)
            .setParent(defaults.parent)
            .setIcon(defaults.icon)
            .setCssClasses(defaults.cssClasses);
        switch (defaults.jtype) {
        case 'reset':
            this.action = new PMSE.Action({
                text: this.caption,
                handler: function() {
                    self.parent.reset();
                },
                cssStyle: this.icon
            });
            break;
        case 'submit':
            this.action = new PMSE.Action({
                text: this.caption,
                handler: function() {
                    self.parent.submit();
                },
                cssStyle: this.icon
            });
            break;
        case 'normal':
            this.action = new PMSE.Action({
                text: this.caption,
                handler: defaults.handler,
                cssStyle: this.icon
            });
            break;
        }
    }
};

PMSE.Button.prototype.setCssClasses = function(cssClasses) {
    var oldClasses = this.cssClasses.join(" "),
        newClasses = cssClasses.join(" ");
    this.cssClasses = cssClasses;
    jQuery(this.html).remove(oldClasses).addClass(newClasses);
    return this;
};

PMSE.Button.prototype.loadAction = function(action, parent) {
    this.action = action;
    this.setCaption(this.action.text);
    this.setIcon(this.action.cssStyle);
    this.setParent(parent);
};

PMSE.Button.prototype.setCaption = function(text) {
    this.caption = text;
    return this;
};

PMSE.Button.prototype.setIcon = function(value) {
    this.icon = value;
    return this;
};

PMSE.Button.prototype.setParent = function(parent) {
    this.parent = parent;
    return this;
};

PMSE.Button.prototype.createHTML = function() {
    var buttonAnchor, iconSpan, labelSpan;

    buttonAnchor = this.createHTMLElement('a');
    buttonAnchor.href = '#';
    buttonAnchor.className = 'adam-button';
    buttonAnchor.id = this.id;


    if (this.icon) {
        iconSpan = this.createHTMLElement('span');
        iconSpan.className = this.icon;
        buttonAnchor.appendChild(iconSpan);
    }

    labelSpan = this.createHTMLElement('span');
    labelSpan.className = 'adam-button-label';
    labelSpan.innerHTML = this.caption;
    buttonAnchor.appendChild(labelSpan);

    this.html = buttonAnchor;
    this.setCssClasses(this.cssClasses);

    return this.html;
};

PMSE.Button.prototype.attachListeners = function() {
    var self = this;
    $(this.html)
        .click(function(e) {
            e.stopPropagation();
            e.preventDefault();
            if (self.action.handler) {
                self.action.handler();
            }
        })
        .mousedown(function(e) {
            e.stopPropagation();
        });
};
