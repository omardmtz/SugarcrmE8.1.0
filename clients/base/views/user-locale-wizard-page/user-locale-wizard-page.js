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
 * User Locale wizard page for the FirstLoginWizard.
 *
 * @class View.Views.Base.UserLocaleWizardPageView
 * @alias SUGAR.App.view.views.BaseUserLocaleWizardPageView
 * @extends View.Views.Base.UserWizardPageView
 */
({
    extendsFrom: "UserWizardPageView",
    TIME_ZONE_KEY: 'timezone',
    TIME_PREF_KEY: 'timepref',
    DATE_PREF_KEY: 'datepref',
    NAME_FORMAT_KEY: 'default_locale_name_format',

    /**
     * @override
     * @param options
     */
    initialize: function(options) {
        var self = this;
        options.template = app.template.getView('wizard-page');
        this._super('initialize', [options]);
        // Preset the user prefs for formats
        if (this.model) {
            this.model.set(this.TIME_ZONE_KEY, (app.user.getPreference(this.TIME_ZONE_KEY) || ''));
            this.model.set(this.TIME_PREF_KEY, (app.user.getPreference(this.TIME_PREF_KEY) || ''));
            this.model.set(this.DATE_PREF_KEY, (app.user.getPreference(this.DATE_PREF_KEY) || ''));
            this.model.set(this.NAME_FORMAT_KEY, (app.user.getPreference(this.NAME_FORMAT_KEY) || ''));
        }
        this.action = 'edit';
    },

    _render: function(){
        var self = this;
        // Prepare the metadata so we can prefetch select2 locale options
        this._prepareFields(function() {
            if (!self.disposed) {
                self.fieldsToValidate = self._fieldsToValidate(self.meta);
                self._super("_render");
            }
        });
    },
    _prepareFields: function(callback) {
        var self = this;
        // Fixme this doesn't belong in user. See TY-526.
        app.user.loadLocale(function(localeOptions) {
            // Populate each field def of type enum with returned locale options and use user's pref as displayed
            _.each(self.meta.panels[0].fields, function(fieldDef) {
                var opts = localeOptions[fieldDef.name];
                if (opts) {
                    fieldDef.options = opts;
                }
            });
            callback();
        });
    },
    /**
     * Called before we allow user to proceed to next wizard page. Does the validation and locale update.
     * @param {Function} callback The callback to call once HTTP request is completed.
     * @override
     */
    beforeNext: function(callback) {
        this.getField("next_button").setDisabled(true);  //temporarily disable
        this.model.doValidate(this.fieldsToValidate,
            _.bind(function(isValid) {
                var self = this;
                if (isValid) {
                    var payload = this._prepareRequestPayload();
                    app.alert.show('wizardlocale', {
                        level: 'process',
                        title: app.lang.get('LBL_LOADING'),
                        autoClose: false
                    });
                    // 'ut' is, historically, a special flag in user's preferences that is
                    // generally marked truthy upon timezone getting saved. It's also used
                    // to semantically represent "is the user's instance configured"
                    payload['ut'] = true;
                    app.user.updatePreferences(payload, function(err) {
                        app.alert.dismiss('wizardlocale');
                        self.updateButtons();  //re-enable buttons
                        if (err) {
                            app.logger.debug("Wizard locale update failed: " + err);
                            callback(false);
                        } else {
                            callback(true);
                        }
                    });
                } else {
                    callback(false);
                }
            }, this)
        );
    }

})
