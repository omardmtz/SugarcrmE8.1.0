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
 * @class PMSE.Form
 * Handles form panels
 * @extends PMSE.Panel
 *
 * @constructor
 * Creates a new instance of the object
 * @param {Object} options
 */
var NotePanel = function (options) {
    PMSE.Panel.call(this, options);

    /**
     * Defines if the form has a proxy
     * @type {Boolean}
     */
    this.proxyEnabled = null;

    /**
     * Defines the form's url
     * @type {String}
     */
    this.url = null;

    /**
     * Defines the form's proxy object
     * @type {PMSE.Proxy}
     */
    this.proxy = null;
    /**
     * Defines the form loading state
     * @type {Boolean}
     */
    this.loaded = false;
    /**
     * Defines the form's data
     * @type {Object}
     */
    this.data = null;
    /**
     * Defines the callback functions
     * @type {Object}
     */
    this.callback = {};
    /**
     * Defines the dirty form state
     * @type {Boolean}
     */
    this.dirty = false;

    this.buttons = [];

    this.footerAlign = null;

    this.labelWidth = null;

    this.footerHeight = null;

    this.headerHeight = null;

    this.closeContainerOnSubmit = null;

    this.parent = null;

    this.app = null;

    NotePanel.prototype.initObject.call(this, options);
};

NotePanel.prototype = new PMSE.Panel();

/**
 * Defines the object's type
 * @type {String}
 */
NotePanel.prototype.type = 'NotePanel';

/**
 * Initializes the object with the default values
 */
NotePanel.prototype.initObject = function (options) {
    var defaults = {
        url: null,
        data: null,
        proxyEnabled: true,
        callback: {},
        buttons: [],
        footerAlign: 'center',
        labelWidth: '30%',
        footerHeight: 10,
        headerHeight: 0,
        closeContainerOnSubmit: false,
        logType: 'message',
        caseId: null,
        caseIndex: null

    };
    $.extend(true, defaults, options);
    this.setUrl(defaults.url)
        .setCallback(defaults.callback);
    this.caseId = defaults.caseId;
    this.caseIndex = defaults.caseIndex;
//        .setLabelWidth(defaults.labelWidth)
//        .setFooterAlign(defaults.footerAlign);
//        .setLogType(defaults.logType);
    if(App){
        this.app = App;
    } else {
        this.app = parent.SUGAR.App;
    }
};

/**
 * Sets the form's url
 * @param {String} url
 * @return {*}
 */
NotePanel.prototype.setUrl = function (url) {
    this.url = url;
    return this;
};

/**
 * Sets the Proxy Enabled property
 * @param {Boolean} value
 * @return {*}
 */
//NotePanel.prototype.setProxyEnabled = function (value) {
//    this.proxyEnabled = value;
//    return this;
//};

/**
 * Defines the proxy object
 * @param {PMSE.Proxy} proxy
 * @return {*}
 */
//NotePanel.prototype.setProxy = function (proxy) {
//    if (proxy && proxy.family && proxy.family === 'PMSE.Proxy') {
//        this.proxy = proxy;
//        this.url = proxy.url;
//        this.proxyEnabled = true;
//    } else {
//        if (this.proxyEnabled) {
//            if (proxy) {
//                if (!proxy.url) {
//                    proxy.url = this.url;
//                }
//                this.proxy = new PMSE.Proxy(proxy);
//            } else {
//                if (this.url) {
//                    this.proxy = new PMSE.Proxy({url: this.url});
//                }
//            }
//        }
//    }
//    return this;
//};

/**
 * Defines the form's data object
 * @param {Object} data
 * @return {*}
 */
//NotePanel.prototype.setData = function (data) {
//    this.data = data;
//    if (this.loaded) {
//        this.applyData();
//    }
//    return this;
//};

/**
 * Sets the form's callback object
 * @param {Object} cb
 * @return {*}
 */
NotePanel.prototype.setCallback = function (cb) {
    this.callback = cb;
    return this;
};

//NotePanel.prototype.setFooterAlign = function (position) {
//    this.footerAlign = position;
//    return this;
//};
//
//NotePanel.prototype.setLabelWidth = function (width) {
//    this.labelWidth = width;
//    return this;
//};

//NotePanel.prototype.setFooterHeight = function (width) {
//    this.footerHeight = width;
//    return this;
//};
//
//NotePanel.prototype.setHeaderHeight = function (height) {
//    this.headerHeight = height;
//    return this;
//};

//NotePanel.prototype.setCloseContainerOnSubmit = function (value) {
//    this.closeContainerOnSubmit = value;
//    return this;
//};
//NotePanel.prototype.setLogType = function (type) {
//    this.logType = type;
//    return this;
//};
/**
 * Loads the form
 */
NotePanel.prototype.load = function () {
    if (!this.loaded) {
        if (this.proxy) {
            this.data = this.proxy.getData();
        }
        if (this.callback.loaded) {
            this.callback.loaded(this.data, this.proxy !== null);
        }
        //this.applyData();
        this.attachListeners();
        this.loaded = true;
    }
};

/**
 * Add Fields Items
 * @param {(Object|PMSE.Field)}item
 */
NotePanel.prototype.addLog = function (options) {
    var html,
        newItem,
        buttonAnchor,
        self = this;
    newItem = new LogField(options);

    newItem.setParent(this);
    newItem.logId = options.logId;
    html = newItem.createHTML();

    buttonAnchor = this.createHTMLElement('a');
    buttonAnchor.id = 'deleteNoteBtn';
    buttonAnchor.innerHTML = 'Delete';

    newItem.durationSection.appendChild(buttonAnchor);
    newItem.deleteControl = buttonAnchor;

    $(buttonAnchor).click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        self.app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});

        url = self.app.api.buildURL('pmse_Inbox/delete_notes/'+newItem.logId, null, null);
        self.app.api.call('delete', url, {}, {
            success: function (response) {
                $(newItem.html).remove();
                self.app.alert.dismiss('upload');
            }
        });
    });

    this.body.appendChild(html);
    this.items.push(newItem);

    if (options.callback) options.callback.success();

    return this;
};

/**
 * Returns the data
 * @return {Object}
 */
NotePanel.prototype.getData = function () {
    var i, result = {};
    for (i = 0; i < this.items.length; i += 1) {
        $.extend(result, this.items[i].getObjectValue());
    }
    return result;
};

/**
 * Sets the dirty form property
 * @param {Boolean} value
 * @return {*}
 // */
NotePanel.prototype.setDirty = function (value) {
    this.dirty = value;
    return this;
};



NotePanel.prototype.attachListeners = function () {
    var i, root = this, proxy, data, self = this;

    if(App){ _App = App; } else { _App = parent.SUGAR.App; }

    for (i = 0; i < this.items.length; i += 1) {
        this.items[i].attachListeners();
    }
//    for (i = 0; i < this.buttons.length; i += 1) {
//        this.buttons[i].attachListeners();
//    }
    //$(this.footer).draggable( "option", "disabled", true);
    $(this.body).mousedown(function (e) {
        e.stopPropagation();
    });

    $(this.addNoteBtn).click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (root.items[0].value && root.items[0].value.trim()!=='') {
            self.app.alert.show('upload', {level: 'process', title: 'LBL_SAVING', autoclose: false});
        var pictureUrl = self.app.api.buildFileURL({
            module: 'Users',
            id: self.app.user.id,
            field: 'picture'
        });
        var f = new Date();
            data = {
                not_content: root.items[0].value,
                cas_id: root.caseId,
                cas_index:root.caseIndex,
                not_user_id: 1
            };
            url = self.app.api.buildURL('pmse_Inbox/save_notes/', null, null);
            attributes = {
                data: data
            };

            self.app.api.call('create', url, attributes, {
                success: function (result) {
                    var newLog = {
                        name: 'log' ,
                        label: root.items[0].value,
                        user: self.app.user.attributes.full_name,
                        picture : pictureUrl,
                        duration : '<strong> ' + _App.date(result.date_entered).fromNow() + ' </strong>',
                        startDate: _App.date(result.date_entered).formatUser(),
                        deleteBtn : true,
                        logId  : result.id
                    };
                    root.addLog(newLog);
                    root.items[0].setValue('');
                    self.app.alert.dismiss('upload');
                }
            });
        }
        else{
            document.getElementById("notesTextArea").className="control-group error";
            setTimeout(function(){
                document.getElementById("notesTextArea").className="";
            }, 1000);
        }
    });

};



NotePanel.prototype.setHeight = function (height) {
    var bodyHeight;
    PMSE.Panel.prototype.setHeight.call(this, height);
    bodyHeight = this.height - this.footerHeight - this.headerHeight;
    this.setBodyHeight(bodyHeight);
    return this;
};

NotePanel.prototype.createHTML = function () {
    var i, footerHeight, html, buttonAnchor, labelSpan;
    PMSE.Panel.prototype.createHTML.call(this);
    this.footer.style.textAlign = this.footerAlign;
    for (i = 0; i < this.items.length; i += 1) {
        this.items[i].setParent(this);
        html = this.items[i].getHTML();

        buttonAnchor = this.createHTMLElement('a');
        buttonAnchor.href = '#';
        buttonAnchor.className = 'adam-button btn btn-primary';
        buttonAnchor.id = 'noteBtn';

        labelSpan = this.createHTMLElement('span');
        labelSpan.className = 'adam-button-label';
        labelSpan.innerHTML = 'Add Note';
        buttonAnchor.appendChild(labelSpan);

        html.appendChild(buttonAnchor);
        this.addNoteBtn = buttonAnchor;

        html.removeChild(html.firstChild);

        this.body.appendChild(html);

    }


    this.body.style.bottom = '8px';
    //this.footer.style.height = this.footerHeight + 'px';
    return this.html;
};

NotePanel.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};

NotePanel.prototype.getLogField = function (id) {
    var field = null, i;
    for (i = 0; i < this.items.length; i += 1) {
        if (this.items[i].id === id) {
            field = this.items[i];
            return field;
        }
    }
    return field;
};