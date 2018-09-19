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

({
    extendsFrom: 'HeaderpaneView',
    events:{
        'click [name=log_pmse_button]': 'getLogPmse',
        'click [name=log_clear_button]': 'logClearClick',
        'click [name=log_cron_button]': 'getLogCron'
    },
    initialize: function(options) {
        this._super('initialize', [options]);
        this.getLogPmse();
        this.context.on('list:cancelCase:fire', this.cancelCases, this);
        //this.context.on('configLog:fire', this.getLogConfig, this);
    },

    logClearClick: function () {
        var self = this;
        app.alert.show('clear_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_PMSE_WARNING_CLEAR', this.module),
            onConfirm: function () {
                app.alert.show('data:sync:process', {
                    level: 'process',
                    title: app.lang.get('LBL_LOADING'),
                    autoClose: false
                });
                self.clearLog();
            },
            onCancel: $.noop

        });
    },

    clearLog: function () {
        var self = this;
        var pmseInboxUrl = app.api.buildURL(this.module + '/clearLog/pmse');
        app.api.call('update', pmseInboxUrl, {}, {
            success: function () {
                self.getLog();
            }
        });

    },

    getLogPmse: function() {
        app.alert.show('data:sync:process', {
            level: 'process',
            title: app.lang.get('LBL_LOADING'),
            autoClose: false});
        var self = this;
        var pmseInboxUrl = app.api.buildURL(this.module + '/getLog');
        app.api.call('READ', pmseInboxUrl, {},{
            success: function(data)
            {
                self.getLog(data)
            }
        });
    },

    getLogCron : function() {
        app.alert.show('data:sync:process', {level: 'process', title: 'Loading', autoclose: false});
        var self = this;
        var pmseInboxUrl = app.api.buildURL(this.module + '/getLog/cron');
        app.api.call('READ', pmseInboxUrl, {},{
            success: function(data)
            {
                $('#logPmseId').html('Cron Log');
                self.getLog(data)
            }
        });
    },

    getLog: function(data) {
        $("textarea").val(data);
        app.alert.dismiss('data:sync:process');
    }
})
