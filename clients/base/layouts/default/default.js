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
 * @class View.Layouts.Base.DefaultLayout
 * @alias SUGAR.App.view.layouts.BaseDefaultLayout
 * @extends View.Layout
 */
/**
 * Some events have been deprecated in 7.2 and removed.
 * List of changes:
 *
 * - `toggleSidebar` has been migrated to `sidebar:toggle`. It allows one param
 *    to indicate the state. {@link Layout.Default#toggleSidePane}
 *
 * - `openSidebar` has been removed. You can open the sidebar by triggering
 *    `sidebar:toggle` and passing `true`. Note that you can also close the
 *    sidebar by triggering `sidebar:toggle` and passing `false`.
 *
 * - `toggleSidebarArrows` has been removed. Trigger `sidebar:state:changed`
 *    with the value `open` or `close` instead.
 *
 * - `openSidebarArrows` has been removed. Trigger `sidebar:state:changed` with
 *    the value `open` instead.
 */
({
    className: 'row-fluid',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        /**
         * Name of the last state. This can be overridden in metadata, please
         * refer to the example.
         *
         * Example:
         *
         *     array(
         *          'default_hide' => '1',
         *          'hide_key' => 'hide-merge',
         *     ),
         *
         * @cfg {String}
         */
        this.HIDE_KEY = 'hide';

        /**
         * Default value for hiding the sidepane. `1` is hidden, `0` is show.
         * This is because the code which retrieves data from local storage
         * checks the value of the data and will return undefined if the result
         * resolves to a boolean false.
         *
         * Since an undefined hide value means "use the default" and int 0 means
         * show, but they both resolve to false, this causes complications. As a
         * result, we have to use a string.
         *
         * Using a string `0` or `1` is superior to something like "yes" and
         * "no" because we can use parseInt instead of an if/else setup.
         *
         * This setting can be overridden in metadata, please refer to the
         * example.
         *
         * Example:
         *
         *     array(
         *          'default_hide' => '1',
         *          'hide_key' => 'hide-merge',
         *     ),
         *
         * @property {String}
         * @protected
         */
        this._defaultHide = '0';

        /**
         * Key for storing the last state. This key is used in localstorage of the
         * browser. It is generated using `HIDE_KEY`
         *
         * Example:
         *
         *     state:Accounts:default:hide_last_state_key
         *
         *
         * @property {String}
         * @protected
         */
        this._hideLastStateKey = null;

        this._super('initialize', [options]);
        if (!_.isUndefined(this.meta.default_hide)) {
            this._defaultHide = this.meta.default_hide;
        }
        if (!_.isUndefined(this.meta.hide_key)) {
            this.HIDE_KEY = this.meta.hide_key;
        }

        this.on('sidebar:toggle', this.toggleSidePane, this);

        this.meta.last_state = this.meta.last_state || { id: 'default' };

        this._hideLastStateKey = app.user.lastState.key(this.HIDE_KEY, this);

        //Update the panel to be open or closed depending on how user left it last
        this._toggleVisibility(this.isSidePaneVisible());
    },

    /**
     * Check whether the side pane is currently visible.
     *
     * @return {Boolean} `true` if visible, `false` otherwise.
     */
    isSidePaneVisible: function() {
        var hideLastState = app.user.lastState.get(this._hideLastStateKey);
        var hidden = hideLastState || this._defaultHide;
        return !parseInt(hidden, 10);
    },

    /**
     * Toggle sidebar and save the current state.
     *
     * Both the hidden and show state is stored. In the default configuration,
     * the side pane is `visible`.
     * In the non-default case, the hidden state is represented by `0`, and the
     * show state is represented by `1`.
     *
     * @param {Boolean} [visible] Pass `true` to show the sidepane, `false` to
     *  hide it, otherwise will toggle the current state.
     */
    toggleSidePane: function(visible) {
        var isVisible = this.isSidePaneVisible();

        visible = _.isUndefined(visible) ? !isVisible : visible;

        if (isVisible === visible) {
            return;
        }

        app.user.lastState.set(
            this._hideLastStateKey,
            visible ? '0' : '1'
        );

        this._toggleVisibility(visible);
    },

    /**
     * Toggle visibility of the side pane.
     *
     * Toggling visibility can affect the content width in the same way as a
     * window resize. Thus we will trigger window `resize` so that any content
     * listening for a window `resize` can readjust themselves.
     *
     * @param {Boolean} visible `true` to show the side pane, `false` otherwise.
     * @private
     */
    _toggleVisibility: function(visible) {
        this.$('.main-pane').toggleClass('span12', !visible).toggleClass('span8', visible);

        this.$('.side').toggleClass('side-collapsed', !visible);

        $(window).trigger('resize');

        this.trigger('sidebar:state:changed', visible ? 'open' : 'close');
    },

    /**
     * Get the width of either the main or side pane depending upon where the
     * component resides.
     *
     * @param {View.Component} component The component.
     * @return {Number} The component width.
     */
    getPaneWidth: function(component) {
        if (!this.$el) {
            return 0;
        }
        var paneSelectors = ['.main-pane', '.side'];
        var pane = _.find(paneSelectors, function(selector) {
                var $pane = this.$(selector).get(0);
                return $pane && $.contains($pane, component.el);
            }, this);

        return this.$(pane).width() || 0;
    }
})
