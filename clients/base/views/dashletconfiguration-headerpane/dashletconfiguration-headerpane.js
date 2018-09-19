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
 * @class View.Views.Base.DashletconfigurationHeaderpaneView
 * @alias SUGAR.App.view.views.BaseDashletconfigurationHeaderpaneView
 * @extends View.View
 */
({
    plugins: ['Editable', 'ErrorDecoration'],

    events: {
        "click a[name=cancel_button]": "close",
        "click a[name=save_button]":   "save"
    },

    /**
     * Store the translated i18n label.
     * @type {String} Translated dashlet's title label.
     * @private
     */
    _translatedLabel: null,

    /**
     * @inheritdoc
     * Binds the listener for the before `save` event.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.before('save', function(model) {
            return this.layout.triggerBefore('dashletconfig:save', model);
        }, this);

        //shortcut keys
        app.shortcuts.register({
            id: 'Dashlet:Config:Cancel',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CLOSE_DRAWER',
            callOnFocus: true,
            handler: function() {
                var $cancelButton = this.$('a[name=cancel_button]');
                if ($cancelButton.is(':visible') && !$cancelButton.hasClass('disabled')) {
                    $cancelButton.click();
                }
            }
        });
        app.shortcuts.register({
            id: 'Dashlet:Config:Save',
            keys: ['mod+s','mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_SAVE',
            callOnFocus: true,
            handler: function() {
                var $saveButton = this.$('a[name=save_button]');
                if ($saveButton.is(':visible') && !$saveButton.hasClass('disabled')) {
                    $saveButton.click();
                }
            }
        });
    },

    /**
     * @inheritdoc
     * Compare with the previous attributes and translated dashlet's label
     * in order to warn unsaved changes.
     *
     * @return {Boolean} true if the dashlet setting contains changes.
     */
    hasUnsavedChanges: function() {
        var previousAttributes = _.extend(this.model.previousAttributes(), {
            label: this._translatedLabel
        });
        return !_.isEmpty(this.model.changedAttributes(previousAttributes));
    },

    /**
     * Triggers a `save` event before `app.drawer.close()` is called, in case
     * any processing needs to be done on the model before it is saved.
     *
     * @return {Boolean} `false` if the `dashletconfig:save` event returns false.
     */
    save: function() {
        if (this.triggerBefore('save', this.model) === false) {
            return false;
        }

        var fields = {};
        _.each(this.meta.panels[0].fields, function(field) {
            fields[field.name] = field;
        });

        this.model.doValidate(fields, _.bind(function(isValid) {
            if (isValid) {
                app.drawer.close(this.model);
            }
        }, this));
    },

    close: function() {
        app.drawer.close();
    },

    /**
     * @inheritdoc
     *
     * Translate model label before render using model attributes.
     */
    _renderHtml: function() {
        var label;
        this.model = this.context.get('model');
        label = app.lang.get(
            this.model.get('label'),
            this.model.get('module') || this.module,
            this.model.attributes
        );
        this._translatedLabel = label;
        this.model.set('label', label, {silent: true});
        app.view.View.prototype._renderHtml.call(this);
    }
})
