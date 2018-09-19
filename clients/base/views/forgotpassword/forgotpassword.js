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
 * Forgot password form view.
 *
 * @class View.Views.Base.ForgotpasswordView
 * @alias SUGAR.App.view.views.BaseForgotpasswordView
 * @extends View.View
 */
({
    plugins: ['ErrorDecoration'],

    events: {
        'click [name=cancel_button]': 'cancel',
        'click [name=forgotPassword_button]': 'forgotPassword',
        'change select[name=country]': 'render'
    },

    /**
     * Get the fields metadata from panels and declare a Bean with the metadata
     * attached.
     *
     * {@link View.Views.Base.LoginView}
     *
     * @param {Object} meta The view's metadata supplied.
     * @private
     */
    _declareModel: function(meta) {
        meta = meta || {};

        var fields = {};
        _.each(_.flatten(_.pluck(meta.panels, "fields")), function(field) {
            fields[field.name] = field;
        });
        /**
         * Fields metadata needs to be converted to this format for App.data.declareModel
         *  {
          *     "first_name": { "name": "first_name", ... },
          *     "last_name": { "name": "last_name", ... },
          *      ...
          * }
         */
        app.data.declareModel('Forgotpassword', {fields: fields});
    },

    /**
     * @override
     * @param options
     */
    initialize: function(options) {
        // Declare a Bean so we can process field validation
        this._declareModel(options.meta);

        // Reprepare the context because it was initially prepared without metadata
        options.context.prepare(true);

        app.view.View.prototype.initialize.call(this, options);
        this._showResult = false;
    },

    /**
     * @override
     * @private
     */
    _render: function() {
        if (!(app.config && app.config.forgotpasswordON === true)) {
            return;
        }
        if (app.config.idmModeEnabled === true) {
            window.location.href = app.config.cloudConsoleForgotPasswordUrl;
        }
        this.logoUrl = app.metadata.getLogoUrl();
        app.view.View.prototype._render.call(this);

        return this;
    },

    /**
     * Basic cancel button
     */
    cancel: function() {
        app.router.goBack();
    },

    /**
     * Handles forgot password request
     */
    forgotPassword: function() {
        var self = this;

        self.model.doValidate(null, function(isValid) {
            if (isValid) {

                // a robot has reached into the honey pot. do not submit
                if (app.config.honeypot_on && app.config.honeypot_on === true &&
                    (self.$('input[name="first_name"]').val() || self.model.get('first_name'))) return;

                app.$contentEl.hide();
                app.alert.show('forgotPassword', {level: 'process', title: app.lang.get('LBL_LOADING'), autoClose: false});

                var emails = self.model.get('email');
                var params = {
                    username: self.model.get('username')
                };

                if (emails && emails[0] && emails[0].email_address) {
                    params.email =  emails[0].email_address;
                }

                var url = app.api.buildURL('password/request','',{},params);
                app.api.call('READ', url,{},{
                    success: function(response){
                        // result flags
                        self._showSuccess = true;
                        self._showResult = true;
                        self.resultLabel = "LBL_PASSWORD_REQUEST_SENT";
                        self.model.clear();
                        if (!self.disposed) {
                            self.render();
                        }
                    },
                    error: function(err){
                        // result flags
                        self._showSuccess = false;
                        self._showResult = true;

                            self.resultLabel = err.message || 'LBL_PASSWORD_REQUEST_ERROR';

                        if (!self.disposed) {
                            self.render();
                        }
                    },
                    complete: function() {
                        app.alert.dismiss('forgotPassword');
                        app.$contentEl.show();
                    }
                })
            }
        }, self);
    },

    /**
     * Really basic metadata for the Back button displayed on password reset
     */
    _backButton: [
        {
            name: 'cancel_button',
            type: 'button',
            label: 'LBL_BACK',
            value: 'forgotPassword',
            primary: false
        }
    ]
})
