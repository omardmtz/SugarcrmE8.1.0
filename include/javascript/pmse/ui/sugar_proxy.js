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
var SugarProxy = function (options) {
    PMSE.Proxy.call(this, options);
    this.uid = null;
    this.getMethod = null;
    this.sendMethod = null;
    SugarProxy.prototype.initObject.call(this, options);
};

SugarProxy.prototype = new PMSE.Proxy();

SugarProxy.prototype.type = 'SugarProxy';

SugarProxy.prototype.initObject = function (options) {
    var defaults = {
        sendMethod: 'PUT',
        getMethod: 'GET',
        createMethod: 'POST',
        uid: null
    };
    $.extend(true, defaults, options);
    this.setUid(defaults.uid)
        .setSendMethod(defaults.sendMethod)
        .setGetMethod(defaults.getMethod)
        .setCreateMethod(defaults.createMethod);
};

SugarProxy.prototype.setUid = function (id) {
    this.uid = id;
    return this;
};


SugarProxy.prototype.setSendMethod = function (method) {
    this.sendMethod = method;
    return this;
};

SugarProxy.prototype.setGetMethod = function (method) {
    this.getMethod = method;
    return this;
};
SugarProxy.prototype.setCreateMethod = function (method) {
    this.createMethod = method;
    return this;
};

SugarProxy.prototype.getData = function (params, callback) {
    var operation, self = this, url;

    operation = this.getOperation(this.getMethod);
    if (operation === 'read' && params) {
        url = App.api.buildURL(this.url, null, null, params);
    } else {
        url = App.api.buildURL(this.url, null, null);
    }
    App.api.call(operation, url, {}, {
        success: function (response) {
            if (callback && callback.success) {
                callback.success.call(self, response);
            }
        },
        error: function (sugarHttpError) {
            if(callback && typeof callback.error === 'function') {
                callback.error.call(self, sugarHttpError);
            }
        }
    });
};

SugarProxy.prototype.sendData = function (data, callback) {

    var operation, self = this, send, url;

    operation = this.getOperation(this.sendMethod);
    url = App.api.buildURL(this.url, null, null);
    attributes = {
        data: data
    };

    App.api.call(operation, url, attributes, {
        success: function (response) {

            if (callback && callback.success) {
                callback.success.call(self, response);
            }
        }
    });
};
SugarProxy.prototype.createData = function (data, callback) {

    var operation, self = this, send, url;

    operation = this.getOperation(this.createMethod);
    url = App.api.buildURL(this.url, null, null);
    attributes = {
        data: data
    };

    App.api.call(operation, url, attributes, {
        success: function (response) {
            if (callback && callback.success) {
                callback.success.call(self, response);
            }
        }
    });
};
SugarProxy.prototype.removeData = function (params, callback) {
    var operation, self = this, url;
    operation = 'remove';
    if (operation === 'remove' && params) {
        url = App.api.buildURL(this.url, null, null, params);
    } else {
        url = App.api.buildURL(this.url, null, null);
    }
    App.api.call('delete', url, {}, {
        success: function (response) {
//            console.log('getData');
//            console.log(response);
            if (callback && callback.success) {
                callback.success.call(self, response);
            }
        }
    });
};

SugarProxy.prototype.getOperation = function (method) {
    var out;
    switch (method) {
        case 'GET':
            out = 'read';
            break;
        case 'POST':
            out = 'create';
            break;
        case 'PUT':
            out = 'update';
            break;
        case 'DELETE':
            out = 'delete';
            break;
    }
    return out;
};
