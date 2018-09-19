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
var RestProxy = function (options) {
    PMSE.Proxy.call(this, options);
    this.restClient = null;
    this.getMethod = null;
    this.sendMethod = null;
    this.uid = null;
    RestProxy.prototype.initObject.call(this, options);
};

RestProxy.prototype = new PMSE.Proxy();

RestProxy.prototype.type = 'RestProxy';

RestProxy.prototype.initObject = function (options) {
    var defaults = {
        restClient: null,
        sendMethod: 'PUT',
        getMethod: 'GET',
        uid: null
    };
    $.extend(true, defaults, options);
    this.setUid(defaults.uid)
        .setRestClient(defaults.restClient)
        .setSendMethod(defaults.sendMethod)
        .setGetMethod(defaults.getMethod);
};

RestProxy.prototype.setUid = function (id) {
    this.uid = id;
    return this;
};


RestProxy.prototype.setRestClient = function (restClient) {
    this.restClient = restClient;
    return this;
};

RestProxy.prototype.setSendMethod = function (method) {
    this.sendMethod = method;
    return this;
};

RestProxy.prototype.setGetMethod = function (method) {
    this.getMethod = method;
    return this;
};

RestProxy.prototype.getData = function (params) {
    var operation, self = this, resp;
    if (this.restClient) {
        operation = this.getOperation(this.getMethod);
        this.restClient.consume({
            operation: operation,
            url: this.url,
            id: this.uid,
            data: params,
            success: function (xhr, response) {
                status = response.success;
                if (response.success) {
                    resp = response;
                }
            }
        });
    }
    return resp;
};

RestProxy.prototype.sendData = function (data, callback) {
    var operation, self = this, send;
    if (this.restClient) {
        operation = this.getOperation(this.sendMethod);
        send = {
            operation: operation,
            url: this.url,
            id: this.uid,
            data: data
        };
        if (callback) {
            if (callback.success) {
                send.success = callback.success;
            }
            if (callback.failure) {
                send.failure = callback.failure;
            }
        }
        this.restClient.consume(send);
    }
};

RestProxy.prototype.getOperation = function (method) {
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
