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
    // dashboard dashlets
    _renderHtml: function() {
        this._super('_renderHtml');

        // define event listeners
        app.events.on('preview:close', _.bind(function() {
            this.toggleSidebar(false);
        }, this));
        app.events.on('app:dashletPreview:close', _.bind(function() {
            this.toggleSidebar(false);
        }, this));
        app.events.on('app:dashletPreview:open', _.bind(function() {
            this.toggleSidebar(true);
        }, this));

        this.$('.dashlet-example').on('click.styleguide', _.bind(function(event) {
            var button = this.$(event.currentTarget);
            var dashlet;
            var module;
            var metadata;
            if (button.hasClass('active')) {
                this.toggleSidebar(false);
                return;
            }
            this.$('.dashlet-example').removeClass('active');
            button.addClass('active');
            app.events.trigger('app:dashletPreview:open');
            dashlet = button.data('dashlet');
            module = button.data('module') || 'Styleguide';
            metadata = app.metadata.getView(module, dashlet).dashlets[0];
            metadata.type = dashlet;
            metadata.component = dashlet;
            this.previewDashlet(metadata);
        }, this));
    },

    _dispose: function() {
        this.$('.dashlet-example').off('click.styleguide');
        this._super('_dispose');
    },

    toggleSidebar: function(state) {
        var defaultLayout = this.layout.getComponent('sidebar');
        if (defaultLayout) {
            defaultLayout.trigger('sidebar:toggle', state);
        }
        if (!state) {
            this.$('.dashlet-example').removeClass('active');
        }
    },

    /**
     * Load dashlet preview by passing preview metadata
     *
     * @param {Object} metadata Preview metadata.
     */
    previewDashlet: function(metadata) {
        var layout = this.layout.getComponent('sidebar');
        var previewLayout;
        var previousComponent;
        var index;
        var contextDef;
        var component;

        while (layout) {
            if (layout.getComponent('preview-pane')) {
                previewLayout = layout.getComponent('preview-pane').getComponent('dashlet-preview');
                break;
            }
            layout = layout.layout;
        }

        if (!previewLayout) {
            return;
        }

        previewLayout.showPreviewPanel();

        // If there is no preview property, use the config property
        if (!metadata.preview) {
            metadata.preview = metadata.config;
        }
        previousComponent = _.last(previewLayout._components);

        if (previousComponent.name !== 'dashlet-preview' && previousComponent.name !== 'preview-header') {
            index = previewLayout._components.length - 1;
            previewLayout._components[index].dispose();
            previewLayout.removeComponent(index);
        }

        component = {
            label: app.lang.get(metadata.label, metadata.preview.module),
            type: metadata.type,
            preview: true
        };

        if (metadata.preview.module || metadata.preview.link) {
            contextDef = {
                skipFetch: false,
                forceNew: true,
                module: metadata.preview.module,
                link: metadata.preview.link
            };
        } else if (metadata.module) {
            contextDef = {
                module: metadata.module
            };
        }

        component.view = _.extend({module: metadata.module}, metadata.preview, component);
        if (contextDef) {
            component.context = contextDef;
        }

        previewLayout.initComponents([{
            layout: {
                type: 'dashlet',
                label: app.lang.get(metadata.preview.label || metadata.label, metadata.preview.module),
                preview: true,
                components: [
                    component
                ]
            }
        }], this.context);

        previewLayout.loadData();
        previewLayout.render();
    }
})
