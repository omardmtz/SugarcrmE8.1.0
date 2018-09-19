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
 * @class View.Layouts.Base.PreviewLayout
 * @alias SUGAR.App.view.layouts.BasePreviewLayout
 * @extends View.Layout
 */
({
    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this._bindPreviewEvents();

        /**
         * Creates a child context, which will carry the previewed model.
         *
         * @type {Core.Context}
         */
        this.context = this.context.getChildContext({});
    },

    /**
     * Binds the events that this preview layout uses.
     *
     * @private
     */
    _bindPreviewEvents: function() {
        app.events.on('preview:render', this._toggle, this);
        app.events.on('preview:open', this.showPreviewPanel, this);
        app.events.on('preview:close', this.hidePreviewPanel, this);
        app.events.on('preview:pagination:hide', this.hidePagination, this);
    },

    /**
     * Checks if {@link #$el this layout element} is in the active drawer, if
     * there is an instance of
     * {@link View.Layouts.Base.DrawerLayout app.drawer}. In case no drawers are
     * defined, this function returns `true`.
     *
     * @return {boolean} `true` if
     *   {@link View.Layouts.Base.DrawerLayout app.drawer} is not defined, or if
     *   this layout is in the active drawer, `false` otherwise.
     */
    _isActive: function() {
        if (_.isEmpty(app.drawer)) {
            return true;
        }

        return app.drawer.isActive(this.$el);
    },

    /**
     * Initializes the preview panel if the given model is different from the
     * model on the {@link #context}. Shows the preview panel if this
     * layout is {@link #_hidden hidden}, hides it otherwise.
     *
     * @private
     * @param {Data.Bean} model The {@link Data.Bean model} being previewed.
     * @param {Data.BeanCollection} collection The
     *   {@link Data.BeanCollection collection} of preview models.
     */
    _toggle: function(model, collection) {
        if (!this._isActive()) {
            return;
        }

        var isSameModel = model === this.context.get('model');

        if (isSameModel) {
            if (this._hidden) {
                this.showPreviewPanel();
                app.events.trigger('list:preview:decorate', model, this);
            } else {
                this.hidePreviewPanel();
            }
        } else {
            this._initPreviewPanel(model, collection);
        }
    },

    /**
     * Initializes the preview layout components using the correct module.
     *
     * @private
     * @param {Data.Bean} model The {@link Data.Bean model} being previewed.
     * @param {Data.BeanCollection} collection The
     *   {@link Data.BeanCollection collection} of preview models.
     */
    _initPreviewPanel: function(model, collection) {
        if (!this._isActive()) {
            return;
        }

        var attrs = {
            model: model,
            collection: collection,
            module: model.module,
            modelId: model.id
        };

        // If `this._components` is empty, its the first time we are
        // initializing the preview panel. Otherwise, if the modules are
        // different, we need to reinitialize the preview panel with the new
        // metadata from that module.
        var hasComponents = !_.isEmpty(this._components);
        var modelChanged = this.context.get('module') !== model.module;

        if (!hasComponents || modelChanged) {
            this._disposeComponents();
            this.context.set(attrs);
            this.initComponents(this._componentsMeta, this.context, model.module);
            if (hasComponents) {
                // In case we already have components, reload the
                // data to remove previous load data (e.g. fetchCalled, etc)
                this.context.reloadData({resetCollection: false});
            } else {
                this.context.loadData();
            }
            this.render();
        } else {
            this.context.set(attrs);
            this.context.reloadData({resetCollection: false});
        }

        this.showPreviewPanel();
        app.events.trigger('list:preview:decorate', model, this);
    },

    /**
     * Shows the preview panel, if it is part of the active drawer or if there
     * is no drawer open.
     */
    showPreviewPanel: function() {
        if (!this._isActive()) {
            return;
        }

        var layout = this.$el.parents('.sidebar-content');
        layout.find('.side-pane').removeClass('active');
        layout.find('.dashboard-pane').hide();
        layout.find('.preview-pane').addClass('active');

        var defaultLayout = this.closestComponent('sidebar');
        if (defaultLayout) {
            defaultLayout.trigger('sidebar:toggle', true);
        }
        this._hidden = false;
    },

    /**
     * Hides the preview panel, if it is part of the active drawer or if there
     * is no drawer open.
     */
    hidePreviewPanel: function() {
        if (!this._isActive()) {
            return;
        }

        var layout = this.$el.parents('.sidebar-content');
        layout.find('.side-pane').addClass('active');
        layout.find('.dashboard-pane').show();
        layout.find('.preview-pane').removeClass('active');
        app.events.trigger('list:preview:decorate', false);
        this._hidden = true;
    },

    hidePagination: function() {
        if (!this._isActive()) {
            return;
        }

        this.hideNextPrevious = true;
        this.trigger('preview:pagination:update');
    }
})
