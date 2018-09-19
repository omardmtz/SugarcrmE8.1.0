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
(function(app) {

    /**
     * This file handles the alerts for the sidecar sync events
     *
     * Sidecar provides 5 events on which we will display/dismiss alerts:
     *
     *  - app:sync indicates the beginning of app.sync()
     *  - app:sync:complete indicates app.sync() has finished without errors
     *  - app:sync:error indicates app.sync() has finished with errors
     *  - data:sync:start indicates we are synchronizing a Bean or BeanCollection (fetch/save/destroy)
     *  - data:sync:complete indicates the Bean or BeanCollection sync has finished successfully or not
     */

    /**
     * On 'app:sync' we display a simple 'LBL_LOADING' process alert
     */
    app.events.on('app:sync', function() {
        app.alert.show('app:sync', {level: 'process', title: app.lang.get('LBL_LOADING')});
    });

    /**
     * On 'app:sync:complete' and 'app:sync:error' we dismiss the alert
     */
    app.events.on('app:sync:complete app:sync:error', function() {
        app.alert.dismiss('app:sync');
    });


    /**
     * Override Context.loadData to attach showAlerts flag if it's the primary context.
     * While loading data of the primary context  we will display a processing message.
     *
     * @param options
     */
    var _contextProto = _.clone(app.Context.prototype);
    app.Context.prototype.loadData = function(options) {
        if (!this.parent) {
            options = options || {};
            options.showAlerts = true;
        }
        _contextProto.loadData.call(this, options);
    };

    /**
     * By default, on 'data:sync:start' we DON'T display a process alert
     *
     * You can pass options.showAlerts = true to your requests to enable the alert messages.
     *
     *      var bean = app.data.createBean('Accounts')
     *      bean.fetch({
     *          showAlerts: true
     *      });
     *
     * You can also override the alert options (including the title and messages) by passing an object 'showAlerts'
     * such as:
     *
     *      var bean = app.data.createBean('Accounts')
     *      bean.save(null, {
     *          showAlerts: {
     *              'process' : {
     *                  'level' : 'warning',
     *                  'title' : 'Saving...',
     *                  'messages' : 'This request takes a few minutes'
     *              },
     *              'success' : {
     *                  'messages' : 'Enjoy the data. '
     *              }
     *          }
     *      });
     *
     *  You may want to display only the success alert
     *
     *      bean.save(null, {
     *          showAlerts: {
     *              'process' : false,
     *              'success' : {
     *                  'read' : {
     *                      'messages' : 'Enjoy the data. '
     *                  }
     *              }
     *          }
     *      });
     */
    var processAlert = {
        _count: 0,

        dismiss: function() {
            this._count--;

            // Dismiss only if it's the last one
            if (this._count < 1) {
                this._count = 0;
                app.alert.dismiss('data:sync:process');
            }
        },

        show: function(options) {
            this._count++;
            app.alert.show('data:sync:process', options);
        }
    } ;

    app.events.on('data:sync:start', function(method, model, options) {

        options = options || {};

        // By default we don't display the alert
        if (!options.showAlerts) {
            return;
        }

        // The user can have disabled only the process alert
        if (options.showAlerts.process === false) {
            return;
        }

        // From here we are sure we want to show the process alert
        var alertOpts = {
            level: 'process'
        };

        // Pull labels for each method
        if (method === 'read') {
            alertOpts.title = app.lang.get('LBL_LOADING');
        }
        else if (method === 'delete') {
            // options.relate means we are breaking a relationship between two records, not actually deleting a record
            alertOpts.title = options.relate ?
                app.lang.get('LBL_UNLINKING') : app.lang.get('LBL_DELETING');
        }
        else {
            alertOpts.title = app.lang.get('LBL_SAVING');
        }

        // Check for an alert options object attach to options that would override
        if (_.isObject(options.showAlerts.process)) {
            _.extend(alertOpts, options.showAlerts.process);
        }

        // Show alert
        processAlert.show(alertOpts);
    });

    // Not to be confused with the event fired for data:sync:complete.
    var syncCompleteHandler = function(type, messages, method, model, options) {

        options = options || {};

        // Preconstruct the alert options.
        var alertOpts = {
            level: type,
            messages: messages
        };
        alertOpts.autoClose = (alertOpts.level === 'error') ? false : true;

        // By default we don't display the alert
        if (!options.showAlerts) return;

        // Error module will display proper message
        if (method === 'read') return;

        // The user can have disabled only this particular type of alert.
        if (options.showAlerts[type] === false) return;

        // Check for an alert options object attach to options
        if (_.isObject(options.showAlerts[type])) {
            _.extend(alertOpts, options.showAlerts[type]);
        }

        app.alert.show('data:sync:' + type, alertOpts);
    };

    app.events.on('data:sync:success', function(method, model, options) {
        var messages;

        if (method === 'delete') {
            // options.relate means we are breaking a relationship between two records, not actually deleting a record
            messages = options.relate ? 'LBL_UNLINKED' : 'LBL_DELETED';
        }
        else {
            messages = 'LBL_SAVED';
        }

        syncCompleteHandler('success', messages, method, model, options);
    });

    app.events.on('data:sync:error', function(method, model, options, error) {
        var suppressErrorMessageFor = [409, 412];

        if (!error || (!error.handled && _.indexOf(suppressErrorMessageFor, error.status) === -1)) {
            syncCompleteHandler('error', 'ERR_GENERIC_SERVER_ERROR', method, model, options);
        }
    });

    app.events.on('data:sync:complete', function(method, model, options) {
        // As we display alerts we have have to check if there is a process
        // alert to dismiss prior to display the success one (as many requests
        // can be fired at the same time we make sure not to dismiss another
        // process alert!)
        if (options.showAlerts && options.showAlerts.process !== false) {
            processAlert.dismiss();
        }
    });

})(SUGAR.App);
