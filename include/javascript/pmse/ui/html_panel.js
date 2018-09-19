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
var HtmlPanel = function (options) {
    PMSE.Panel.call(this, options);
    this.source = this;
    this.scroll = null;
    this.parent = null;
    HtmlPanel.prototype.initObject.call(this, options);
};

HtmlPanel.prototype = new PMSE.Panel();

HtmlPanel.prototype.type = "HtmlPanel";

HtmlPanel.prototype.initObject = function (options) {
    var defaults = {
        source: null,
        scroll: true
    };
    $.extend(true, defaults, options);
    this.setSource(defaults.source)
        .setScroll(defaults.scroll);
};

HtmlPanel.prototype.setSource = function (source) {
    this.source = source;
    return this;
};

HtmlPanel.prototype.setScroll = function (value) {
    this.scroll = value;
    return this;
};

HtmlPanel.prototype.createHTML = function () {
    var HPDiv,
        scrollMode;
    PMSE.Panel.prototype.createHTML.call(this);
    if (this.source) {
        scrollMode = (this.scroll) ? 'auto' : 'none';
        HPDiv = this.createHTMLElement('div');
        HPDiv.id = this.id;
        HPDiv.innerHTML = this.source;
        HPDiv.style.overflow = scrollMode;
        HPDiv.style.height = (this.height - 2) + 'px';
        this.body.appendChild(HPDiv);
        this.body.style.bottom = '8px';
    }
    this.attachListeners();
    return this.html;
};

HtmlPanel.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};
HtmlPanel.prototype.attachListeners = function () {
    $(this.body).on('mousedown', function (e) {
        e.stopPropagation();
    });
};