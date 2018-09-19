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
    plugins: ['JSTree', 'NestedSetCollection'],

    events: {
        'keyup [data-name=search]': '_keyHandler',
        'click [data-role=icon-remove]': function() {
            this.trigger('search:clear');
        }
    },

    /**
     * Default settings.
     *
     * @property {Object} _defaultSettings
     * @property {boolean} _defaultSettings.showMenu Display menu or not.
     * @property {number} _defaultSettings.liHeight Height (pixels) of row.
     */
    _defaultSettings: {
        showMenu: true,
        liHeight: 37
    },

    /**
     * Aggregated settings.
     * @property {Object} _settings
     */
    _settings: null,

    /**
     * List of overridden callbacks.
     * @property {Object} _callbacks
     */
    _callbacks: null,

    /**
     * @inheritdoc
     *
     * Add listener for 'search:clear' and 'click:add_node_button' events.
     * Init settings.
     * Init callbacks.
     */
    initialize: function(options) {
        this.on('search:clear', function() {
            var el = this.$el.find('input[data-name=search]');
            el.val('');
            this._toggleIconRemove(!_.isEmpty(el.val()));
            this.searchNodeHandler(el.val());
        }, this);
        this._super('initialize', [options]);
        this._initSettings();
        this._initCallbacks();
        this.layout.on('click:add_node_button', this.addNodeHandler, this);
    },

    /**
     * @inheritdoc
     *
     * @example Call _renderTree function with the following parameters.
     * <pre><code>
     * this._renderTree($('.tree-block'), this._settings, {
     *      onToggle: this.jstreeToggle,
     *      onSelect: this.jstreeSelect
     * });
     * </code></pre>
     */
    _renderHtml: function(ctx, options) {
        this._super('_renderHtml', [ctx, options]);
        this._renderTree($('.tree-block'), this._settings, this._callbacks);
    },

    /**
     * Initialize _settings object.
     * @return {Object}
     * @private
     */
    _initSettings: function() {
        this._settings = {
            settings: _.extend({},
                this._defaultSettings,
                this.context.get('treeoptions') || {},
                this.def && this.def.settings || {}
            )
        };
        return this;
    },

    /**
     * Initialize _callbacks object.
     * @return {Object}
     * @private
     */
    _initCallbacks: function() {
        this._callbacks = _.extend({},
            this.context.get('treecallbacks') || {},
            this.def && this.def.callbacks || {}
        );
        return this;
    },

    /**
     * Handle submit in search field.
     * @param {Event} event
     * @return {boolean}
     * @private
     */
    _keyHandler: function(event) {
        this._toggleIconRemove(!_.isEmpty($(event.currentTarget).val()));
        if (event.keyCode != 13) return false;
        this.searchNodeHandler(event);
    },

    /**
     * Append or remove an icon to the search input so the user can clear the search easily.
     * @param {boolean} addIt TRUE if you want to add it, FALSE to remove
     */
    _toggleIconRemove: function(addIt) {
        if (addIt && !this.$('i[data-role=icon-remove]')[0]) {
            this.$el.find('div[data-container=filter-view-search]').append('<i class="fa fa-times add-on" data-role="icon-remove"></i>');
        } else if (!addIt) {
            this.$('i[data-role=icon-remove]').remove();
        }
    },

    /**
     * Custom add handler.
     */
    addNodeHandler: function() {
        this.addNode(app.lang.get('LBL_DEFAULT_TITLE', this.module), 'last', false, true, false);
    },

    /**
     * Custom search handler.
     * @param {Event} event DOM event.
     */
    searchNodeHandler: function(event) {
        this.searchNode($(event.currentTarget).val());
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.off('search:clear');
        this._super('_dispose');
    }
})
