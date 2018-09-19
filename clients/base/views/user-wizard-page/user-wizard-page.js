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
 * User Profile wizard page for the FirstLoginWizard.
 *
 * @class View.Views.Base.UserWizardPageView
 * @alias SUGAR.App.view.views.BaseUserWizardPageView
 * @extends View.Views.Base.WizardPageView
 */
({
    extendsFrom: "WizardPageView",

    /**
     * Always show the page at start.
     *
     * @inheritdoc
     */
    showPage: true,

    /**
     * is IDM mode enabled?
     * @var boolean
     */
    isIDMModeEnabled: false,

    /**
     * @override
     * @param options
     */
    initialize: function(options) {
        //Load the default wizard page template, if you want to.
        options.template = app.template.getView('wizard-page');
        this._super('initialize', [options]);
        this.fieldsToValidate = this._fieldsToValidate(options.meta);
        this.action = 'edit';
        this.isIDMModeEnabled = App.metadata.getConfig().idmModeEnabled || false;
    },
    /**
     * @override
     * @return {boolean}
     */
    isPageComplete: function(){
        return this.areAllRequiredFieldsNonEmpty;
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this._super('bindDataChange');
        this.listenTo(this.model, 'sync', this.render);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        if (!this.model.dataFetched) {
            return this;
        }
        this._super('_render');
    },

    /**
     * @inheritdoc
     */
    _renderField: function(field, $fieldEl) {
        this._super('_renderField', [field, $fieldEl]);
        if (this.isIDMModeEnabled && field.def.idm_mode_disabled) {
            field.setDisabled(true);
        }
    },

    /**
     * Prepares HTTP payload
     * @return {Object} Payload with fields we want to update
     * @protected
     */
    _prepareRequestPayload: function() {
        var payload = {},
            self = this,
            fields = _.keys(this.fieldsToValidate);
        _.each(fields, function(key) {
            payload[key] = self.model.get(key);
        });
        return payload;
    },
    /**
     * Called before we allow user to proceed to next wizard page. Does the validation and profile update.
     * @param {Function} callback The callback to call once HTTP request is completed.
     * @override
     */
    beforeNext: function(callback) {
        var self = this;
        this.getField("next_button").setDisabled(true); // temporarily disable
        this.model.doValidate(this.fieldsToValidate,
            _.bind(function(isValid) {
                var self = this;
                if (isValid) {
                    var payload = self._prepareRequestPayload();
                    app.alert.show('wizardprofile', {level: 'process', title: app.lang.get('LBL_LOADING'), autoClose: false});
                    app.user.updateProfile(payload, function(err) {
                        app.alert.dismiss('wizardprofile');
                        self.updateButtons(); //re-enable buttons
                        if (err) {
                            app.logger.debug("Wizard profile update failed: " + err);
                            callback(false);
                        } else {
                            callback(true);
                        }
                    });
                } else {
                    callback(false);
                }
            }, self)
        );
    }

})
