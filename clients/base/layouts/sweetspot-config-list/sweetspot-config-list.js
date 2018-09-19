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
 * @class View.Layouts.Base.SweetspotConfigListLayout
 * @alias SUGAR.App.view.layouts.BaseSweetspotConfigListLayout
 * @extends View.Layout
 */
({
    className: 'columns',

    // FIXME: Change this to 'UnsavedChanges' when SC-4167 gets merged. It won't
    // work until then, because 'Editable' can only be attached to a view.
    plugins: ['Editable'],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._initRows();
        this._bindEvents();
    },

    /**
     * Initializes this layout by adding
     * {@link View.Views.Base.SweetspotConfigListRowView rows} of configured
     * hotkeys if they exist in user preferences.
     *
     * @protected
     * @return {undefined} Returns `undefined` if there are no configured
     *   hotkeys.
     */
    _initRows: function() {
        var data = app.user.getPreference('sweetspot');
        data = data && data.hotkeys;
        if (_.isEmpty(data)) {
            // Always add an empty row if we don't have anything configured.
            this.addRow();
            return;
        }

        _.each(data, function(row) {
            _.each(row.keyword, function(word) {
                this._initRow(row, word);
            }, this);
        }, this);
    },

    /**
     * Adds a {@link View.Views.Base.SweetspotConfigListRowView row view} to the
     * layout, and sets the `keyword` and `action` attributes on the model of
     * the added row component.
     *
     * @param {Object} row The object containing row attributes.
     * @param {string} keyword The `keyword` attribute of the row.
     * @param {string} action The `action` attribute of the row.
     */
    _initRow: function(row, keyword, action) {
        action = action || row.action;
        keyword = keyword || row.keyword;

        var rowComponent = this.addRow();
        rowComponent.model.set('action', action);
        rowComponent.model.set('keyword', keyword);
    },

    /**
     * Binds the events that this layout uses.
     *
     * @protected
     */
    _bindEvents: function() {
        // Config data events
        this.context.on('sweetspot:ask:configs', this.generateConfig, this);

        // Config list row events
        this.context.on('sweetspot:config:addRow', this.addRow, this);
        this.context.on('sweetspot:config:removeRow', this.removeRow, this);
    },

    /**
     * @override
     */
    _placeComponent: function(component) {
        this.$('[data-sweetspot=actions]').append(component.el);
    },

    /**
     * Adds a {@link View.Views.Base.SweetspotConfigListRowView row view} to the
     * layout.
     *
     * @param {View.View} component The component that triggered this event.
     */
    addRow: function(component) {
        var def = _.extend(
                {view: 'sweetspot-config-list-row'},
                app.metadata.getView(null, 'sweetspot-config-list-row')
            );
        var rowComponent = this.createComponentFromDef(def, this.context, this.module);

        if (component) {
            // Add the row after the row where the user clicked the '+' sign.
            component.$el.after(rowComponent.el);
        } else {
            this.addComponent(rowComponent, def);
        }
        rowComponent.render();
        return rowComponent;
    },

    /**
     * Removes and disposes this row view from the
     * {@link View.Views.Base.SweetspotConfigListLayout list layout}
     *
     * @param {View.View} component The component that triggered this event.
     */
    removeRow: function(component) {
        this.collection.remove(component.model);
        component.dispose();
        this.removeComponent(component);

        if (this.$('[data-sweetspot=actions]').children().length === 0) {
            this.addRow();
        }
    },

    /**
     * Generates an object that the
     * {@link View.Layouts.Base.SweetspotConfigLayout config layout} uses to
     * save configurations to the user preferences.
     */
    generateConfig: function() {
        var data = this.collection.toJSON();
        data = this._formatData(data);

        this.context.trigger('sweetspot:receive:configs', data);
    },

    /**
     * Formatter method that sanitizes and prepares the data to be used by
     * {@link View.Layouts.Base.SweetspotConfigLayout#saveConfig}. Also allows
     * for multiple hotkeys to be associated with a single action.
     *
     * @protected
     * @param {Array} data The unsanitized configuration data.
     * @return {Array} The formatted data.
     */
    _formatData: function(data) {
        var result = this._sanitizeConfig(data);
        result = this._joinKeywordConfigs(result);
        result = this._formatForUserPrefs(result);

        return result;
    },

    /**
     * This is a helper function that takes in the sanitized configuration data
     * and analyzes if there are actions being assigned to multiple keywords.
     *
     * If there are actions with more than one keyword, the corresponding
     * keywords are joined together in an array. For example:
     *
     *     [{action: '#Bugs', keyword: 'b1'}, {action: '#Bugs', keyword: 'b2'}]
     *
     * would be transformed to:
     *
     *     [{action: '#Bugs', keyword: ['b1', 'b2']}]
     *
     * By default, this function transforms the keyword attribute to an array.
     * For example:
     *
     *    [{action: '#Bugs', keyword: 'b1'}]
     *
     * would be transformed to:
     *
     *    [{action: '#Bugs', keyword: ['b1']}]
     *
     * @private
     * @param {Array} data The sanitized configuration data.
     * @return {Array} The configuration data, with single/multiple keywords per
     *   action stored in an array.
     */
    _joinKeywordConfigs: function(data) {
        var result = {};

        _.each(data, function(obj) {
            result[obj.action] = result[obj.action] || obj;
            var keyword = _.isArray(obj.keyword) ? obj.keyword : [obj.keyword];
            result[obj.action].keyword = _.union(result[obj.action].keyword, keyword);
        });
        return _.toArray(result);
    },

    /**
     * Sanitizes the configuration data by removing empty/falsy values.
     *
     * @protected
     * @param {Array} data The unsanitized configuration data.
     * @return {Array} The sanitized configuration data.
     */
    _sanitizeConfig: function(data) {
        data = _.reject(data, function(row) {
            return !row.keyword || !row.action;
        });

        return data;
    },

    /**
     * This method prepares the attributes payload for the call to
     * {@link Core.User#updatePreferences}.
     *
     * @protected
     * @param {Array} data The unprepared configuration data.
     * @return {Object} The prepared configuration data.
     */
    _formatForUserPrefs: function(data) {
        return {hotkeys: data};
    },

    /**
     * Compare with the user preferences and return true if the collection
     * contains changes.
     *
     * This method is called by {@link app.plugins.Editable}.
     *
     * @return {boolean} `true` if current collection contains unsaved changes,
     *   `false` otherwise.
     */
    hasUnsavedChanges: function() {
        var prefs = app.user.getPreference('sweetspot');
        var oldConfig = prefs && prefs.hotkeys;
        var newConfig = this.collection.toJSON();
        var isChanged = !_.isEqual(oldConfig, newConfig);

        return isChanged;
    }
})
