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
/*global $ */
var PropertiesGrid = function (selector) {
    this.element = null;
    PropertiesGrid.prototype.init.call(this, selector);
};

PropertiesGrid.prototype.type = 'propertiesGrid';

PropertiesGrid.prototype.init = function (selector) {
    this.element = $(selector);
    return this;
};

PropertiesGrid.prototype.load = function (setup) {
    this.element.progrid(setup);
    return this;
};

PropertiesGrid.prototype.clear = function () {
    this.element.empty();
    return this;
};

PropertiesGrid.prototype.forceFocusOut = function () {
    try {
        this.element.find('input, select').trigger('focusout');
    } catch (e) {}
};

PropertiesGrid.prototype.setWidth = function (width) {
    this.element.progrid('setWidth', width);
    return this;
};
