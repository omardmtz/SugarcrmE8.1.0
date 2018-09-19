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
 * Sidebar toggle field that is used in the RHS layout toggle (show/hide).
 *
 * Some events have been deprecated in 7.2 and removed.
 * List of changes:
 *
 * - `sidebarRendered` has been removed. Now, when the field renders it calls
 *    {@link Layout.Default#isSidePaneVisible} directly to get the current
 *    state.
 *
 * - `toggleSidebar` has been removed. Triggers `sidebar:toggle` instead.
 *
 * - `toggleSidebarArrows` has been removed. Listens to `sidebar:state:changed`
 *    instead.
 *
 * - `openSidebarArrows` has been removed. Listens to `sidebar:state:changed`
 *    instead.
 *
 * - The app event `app:toggle:sidebar` has been removed. Listen to
 *   `sidebar:state:changed` instead.
 *
 * @class View.Fields.Base.SidebartoggleField
 * @alias SUGAR.App.view.fields.BaseSidebartoggleField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'click': 'toggle'
    },

    /**
     * Store the current `open` or `close` state
     *
     * @type {String}
     */
    _state: 'open',

    /**
    * @inheritdoc
    */
    _render: function() {

        /**
        * Sets the appropriate aria-label value depending on current _state property
        *
        * @property {string}
        */
        this.ariaLabel = this._state === 'open' ?
            app.lang.get('LBL_DASHLET_MINIMIZE', this.module) :
            app.lang.get('LBL_DASHLET_MAXIMIZE', this.module);
        this.ariaLabel += ' ' + app.lang.get('LBL_DASHBOARD', this.module);

        /**
        * Sets the appropriate font awesome icon class name depending on current _state property
        *
        * @property {string}
        */
        this.iconClass = 'fa-angle-double-' + (this._state === 'open' ? 'right' : 'left');

        /**
        * Stores tooltips placement value according to the direction of
        * language being used.
        *
        * @property {string}
        */
        this.dataPlacement = app.lang.direction === 'rtl' ? 'right' : 'left';

        this._super('_render');

        return this;
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        var defaultLayout = this.closestComponent('sidebar');
        if (defaultLayout && _.isFunction(defaultLayout.isSidePaneVisible)) {
            this.toggleState(defaultLayout.isSidePaneVisible() ? 'open' : 'close');
            this.listenTo(defaultLayout, 'sidebar:state:changed', this.toggleState);
        }

        //shortcut keys
        app.shortcuts.register({
            id: 'Sidebar:Toggle',
            keys: 't',
            component: this,
            description: 'LBL_SHORTCUT_SIDEBAR_TOGGLE',
            handler: this.toggle
        });
    },

    /**
     * Toggle the `open` or `close` class of the icon.
     *
     * @param {String} [state] The state. Possible values: `open` or `close`.
     */
    toggleState: function(state) {
        if (state !== 'open' && state !== 'close') {
            state = (this._state === 'open') ? 'close' : 'open';
        }
        this._state = state;
        if (!this.disposed) {
            this.render();
        }
    },

    /**
     * Toggle the sidebar.
     *
     * @param {Event} event The `click` event.
     */
    toggle: function(event) {
        var defaultLayout = this.closestComponent('sidebar');
        if (defaultLayout) {
            defaultLayout.trigger('sidebar:toggle');
        }
    }
})
