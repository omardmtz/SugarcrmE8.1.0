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
/*global jCore, $

 */
var JCoreMenuItem;
JCoreMenuItem = function (options) {
    this.id = null;
    this.type = null;
    this.name = null;
    this.label = null;
    this.icon = null;
    this.style = null;
    this.children = [];
    this.parent = null;
    this.value = null;
    JCoreMenuItem.prototype.initObject.call(this, options);
};

JCoreMenuItem.prototype.initObject = function (options) {
    var defaults = {
        id: jCore.Utils.generateUniqueId(),
        children: [],
        parent: null,
        type: "menuItem",
        value: false
    };
    $.extend(true, defaults, options);
    this.setType(defaults.type)
        .setName(defaults.name)
        .setLabel(defaults.label)
        .setIcon(defaults.icon)
        .setStyle(defaults.style)
        .setChildren(defaults.children)
        .setParent(defaults.parent)
        .setValue(defaults.value);
};
JCoreMenuItem.prototype.getId = function () {
    return this.id;
};
JCoreMenuItem.prototype.setType = function (type) {
    this.type = type;
    return this;
};
JCoreMenuItem.prototype.getType = function () {
    return this.type;
};
JCoreMenuItem.prototype.setName = function (name) {
    this.name = name;
    return this;
};
JCoreMenuItem.prototype.getName = function () {
    return this.name;
};
JCoreMenuItem.prototype.setLabel = function (label) {
    this.label = label;
    return this;
};
JCoreMenuItem.prototype.getLabel = function () {
    return this.label;
};
JCoreMenuItem.prototype.setIcon = function (icon) {
    this.icon = icon;
    return this;
};
JCoreMenuItem.prototype.getIcon = function () {
    return this.icon;
};
JCoreMenuItem.prototype.setStyle = function (style) {
    this.style = style;
    return this;
};
JCoreMenuItem.prototype.getStyle = function () {
    return this.style;
};
JCoreMenuItem.prototype.setChildren = function (children) {
    var i,
        self,
        subItem;

    for (i = 0; i < children.length; i += 1) {
        self = children[i];
        self.parent = this;
        subItem = new JCoreMenuItem(self);
        this.children.push(subItem);
    }
    return this;
};
JCoreMenuItem.prototype.getChildren = function () {
    return this.children;
};
JCoreMenuItem.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};
JCoreMenuItem.prototype.getParent = function () {
    return this.parent;
};
JCoreMenuItem.prototype.setValue = function (value) {
    this.value = value;
    return this;
};
JCoreMenuItem.prototype.getValue = function () {
    return this.value;
};

var JCoreContextMenu;
JCoreContextMenu = {
    element : null,
    items : [],
    callbacks : null,
    html : null,
    id: null
};

JCoreContextMenu.init = function (options) {
    this.items = [];
    var defaults = {
        element: null,
        items: [],
        callbacks: null
    };
    $.extend(true, defaults, options);
    this.setElement(defaults.element)
        .setCallbacks(defaults.callbacks);
    if (defaults.items && defaults.element) {
        this.fillItems(defaults.items);
        this.createHTML();
    }
};
JCoreContextMenu.setElement = function (element) {
    this.element = element;
    return this;
};
JCoreContextMenu.setItems = function (items) {
    this.items = items;
    return this;
};
JCoreContextMenu.setCallbacks = function (callbacks) {
    this.callbacks = callbacks;
    return this;
};
JCoreContextMenu.fillItems = function (items) {
    var itemMenu,
        i;
    for (i = 0; i < items.length; i += 1) {
        itemMenu = new JCoreMenuItem(items[i]);
        this.items.push(itemMenu);
    }
};

JCoreContextMenu.createHTML = function () {
};

JCoreContextMenu.show = function (x, y) {
};

JCoreContextMenu.destroy = function () {
};
