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
 * @class View.Fields.Base.TextareaField
 * @alias SUGAR.App.view.fields.BaseTextareaField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     */
    fieldTag : 'textarea',

    /**
     * Default settings used when none are supplied through metadata.
     *
     * Supported settings:
     * - {Number} max_display_chars The maximum number of characters to be
     *   displayed before truncating the field.
     * - {Boolean} collapsed Defines whether or not the textarea detail view
     *   should be collapsed on initial render.
     *
     *     // ...
     *     'settings' => array(
     *         'max_display_chars' => 50,
     *         'collapsed' => false
     *         //...
     *     ),
     *     //...
     *
     * @protected
     * @type {Object}
     */
    _defaultSettings: {
        max_display_chars: 450,
        collapsed: true
    },

    /**
     * State variable that keeps track of whether or not the textarea field
     * is collapsed in detail view.
     *
     * @type {Boolean}
     */
    collapsed: undefined,

    /**
     * Settings after applying metadata settings on top of
     * {@link View.Fields.BaseTextareaField#_defaultSettings default settings}.
     *
     * @protected
     */
    _settings: {},

    /**
     * @inheritdoc
     */
    events: {
        'click [data-action=toggle]': 'toggleCollapsed'
    },

    /**
     * @inheritdoc
     *
     * Initializes settings on the field by calling
     * {@link View.Fields.BaseTextareaField#_initSettings _initSettings}.
     * Also sets {@link View.Fields.BaseTextareaField#collapsed collapsed}
     * to the value in `this._settings.collapsed` (either default or metadata).
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._initSettings();
        this.collapsed = this._settings.collapsed;
    },

    /**
     * Initialize settings, default settings are used when none are supplied
     * through metadata.
     *
     * @return {View.Fields.BaseTextareaField} Instance of this field.
     * @protected
     */
    _initSettings: function() {
        this._settings = _.extend({},
            this._defaultSettings,
            this.def && this.def.settings || {}
        );
        return this;
    },

    /**
     * @inheritdoc
     *
     * Prevents editing the textarea field in a list view.
     *
     * @param {String} name The mode to set the field to.
     */
    setMode: function(name) {
        // FIXME: This will be updated pending changes to fields in sidecar,
        // see SC-2608, SC-2776.
        // FIXME: Check on 'merge-duplicates' to identify editable fields
        // see SC-3325
        var isList = (this.tplName === 'list') && _.contains(['edit', 'disabled'], name),
            mode = isList && this.view.name !== 'merge-duplicates' ? this.tplName : name;
        this._super('setMode', [mode]);
    },

    /**
     * @inheritdoc
     *
     * Formatter that always returns the value set on the textarea field. Sets
     * a `short` value for a truncated representation, if the lenght of the
     * value on the field exceeds that of `max_display_chars`. The return value
     * can either be a string, or an object such as {long: 'abc'} or
     * {long: 'abc', short: 'ab'}, for example.
     *
     * @param {String} value The value set on the textarea field.
     * @return {String|Object} The value set on the textarea field.
     */
    format: function(value) {
        // If the tplName is 'edit' then value needs to be a string. Otherwise 
        // send back the object containing `value.long` and, if necessary,
        // `value.short`.
        if (this.tplName !== 'edit') {
            var max = this._settings.max_display_chars;
            value = {long: value};

            if (value.long && value.long.length > max) {
                value.short = value.long.substr(0, max).trim();
            }
        }

        return value;
    },

    /**
     * Toggles the field between displaying the truncated `short` or `long`
     * value for the field, and toggles the label for the 'more/less' link.
     */
    toggleCollapsed: function() {
        this.collapsed = !this.collapsed;
        this.render();
    },

    /**
     * Overrides default implementation so that whitespaces won't be stripped.
     */
    unformat: function(value) {
        return value;
    }
})
