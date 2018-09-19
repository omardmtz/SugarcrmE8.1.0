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
/**
 * @class View.Views.Base.Tags.CreateView
 * @alias SUGAR.App.view.views.TagsCreateView
 * @extends View.Views.Base.CreateView
 */
({
    extendsFrom: 'CreateView',

    saveAndCreateAnotherButtonName: 'save_create_button',

    /**
     * Add event listener for the save and create another button
     * @override
     * @param options
     */
    initialize: function(options) {
        this._super("initialize", [options]);

        this.alerts = _.extend({}, this.alerts, {
            showMessageFromServerError: function(error) {
                if (!this instanceof app.view.View) {
                    app.logger.error('This method should be invoked by Function.prototype.call(),' +
                        'passing in as argument an instance of this view.');
                    return;
                }
                var name = 'server-error';
                this._viewAlerts.push(name);
                app.alert.show(name, {
                    level: 'warning',
                    messages: error.message ? error.message : 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: true,
                    autoCloseDelay: 9000
                });
            }
        });
    },

    /**
     * Create new record
     * @param callback
     */
    createRecordWaterfall: function(callback) {
        var success = _.bind(function() {
            var acls = this.model.get('_acl');
            if (!_.isEmpty(acls) && acls.access === 'no' && acls.view === 'no') {
                //This happens when the user creates a record he won't have access to.
                //In this case the POST request returns a 200 code with empty response and acls set to no.
                this.alerts.showSuccessButDeniedAccess.call(this);
                callback(false);
            } else {
                this._dismissAllAlerts();
                app.alert.show('create-success', {
                    level: 'success',
                    messages: this.buildSuccessMessage(this.model),
                    autoClose: true,
                    autoCloseDelay: 10000,
                    onLinkClick: function() {
                        app.alert.dismiss('create-success');
                    }
                });
                callback(false);
            }
        }, this);
        var error = _.bind(function(model, error) {
            if (error.status == 412 && !error.request.metadataRetry) {
                this.handleMetadataSyncError(error);
            } else {
                if (error.code === 'duplicate_tag') {
                    this.alerts.showMessageFromServerError.call(this, error);
                } else if (error.status == 403) {
                    this.alerts.showNoAccessError.call(this);
                } else {
                    this.alerts.showServerError.call(this);
                }
                callback(true);
            }
        }, this);

        this.saveModel(success, error);
    },

    /**
     * Save and reload drawer to allow another save
     */
    saveAndCreateAnother: function() {
        this.initiateSave(_.bind(function() {
            //reload the drawer
            if (app.drawer) {
                app.drawer.load({
                    layout: 'create',
                    context: {
                        create: true
                    }
                });

                //Change the context on the cancel button
                app.drawer.getActiveDrawerLayout().context.on('button:' + this.cancelButtonName + ':click', this.multiSaveCancel, this);
            }
        }, this));
    },

    /**
     * When cancelling, re-render the Tags listview to show updates from previous save
     */
    multiSaveCancel: function() {
        if (app.drawer) {
            var route = app.router.buildRoute('Tags');
            app.router.navigate(route, {trigger: true});
            app.drawer.close(app.drawer.context);
        }
    }
})
