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
 * Layout for tabbing between filterable components.
 * Mostly to toggle between Activity Stream and list views
 *
 * @class View.Layouts.Base.TogglepanelLayout
 * @alias SUGAR.App.view.layouts.BaseTogglepanelLayout
 * @extends View.Layout
 */
({
    events: {
        'click .toggle-actions .btn': 'toggleView'
    },

    /**
     * @override
     * @param {Object} opts
     */
    initialize: function (opts) {
        this.componentsList = {}; //components that can be toggled
        this.toggles = []; //toggle buttons to display to user
        this.processToggles(opts);
        this._super('initialize', [opts]);
    },

    /**
     * @inheritdoc
     */
    loadData: function(options) {
        var allComponents = this._components;
        this._components = this.getNonToggleComponents();
        this._super('loadData', [options]);
        this._components = allComponents;
    },

    /**
     * @override
     * @private
     */
    _render: function() {
        // Only render components that cannot be toggled.
        var allComponents = this._components;
        this._components = this.getNonToggleComponents();
        this._super('_render');
        this._components = allComponents;

        // get the last viewed layout
        this.toggleViewLastStateKey = app.user.lastState.key('toggle-view', this);
        var lastViewed = app.user.lastState.get(this.toggleViewLastStateKey);

        // show the first toggle if the last viewed state isn't set in the metadata
        if (_.isUndefined(lastViewed) || this.isToggleButtonDisabled(lastViewed)) {
            var enabledToggles = _.filter(this.toggles, function(toggle) {
                return !toggle.disabled;
            });
            if (enabledToggles.length > 0) {
                lastViewed = _.first(enabledToggles).toggle;
            }
        }

        if (lastViewed) {
            this.showComponent(lastViewed, true);//SP-1766-don't double render!
            // Toggle the appropriate button and layout for initial render
            this.$('[data-view="' + lastViewed + '"]')
                .button('toggle')
                .attr('aria-pressed', true);
        }
    },

    /**
     * Get components that cannot be toggled.
     */
    getNonToggleComponents: function() {
        return _.filter(this._components, function(component) {
            return !_.contains(this.componentsList, component);
        }, this);
    },

    /**
     * Checks whether the toggle button is disabled
     * @param {string} name  The name of the button to check
     * @return {boolean}
     */
    isToggleButtonDisabled: function (name) {
        var disabled = false,
            toggleButton;

        toggleButton = _.find(this.toggles, function (toggle) {
            return toggle.toggle === name;
        });

        if (toggleButton) {
            disabled = toggleButton.disabled;
        }
        return disabled;
    },

    /**
     * Get components from the metadata and declare toggles.
     *
     * @param {Object} options The Backbone.View initialization options.
     */
    processToggles: function(options) {
        var temp = {};

        //Go through components and figure out which toggles we should add
        _.each(options.meta.components, function (component) {
            var toggle;
            if (component.view) {
                toggle = component.view;
            } else if (component.layout) {
                toggle = (_.isString(component.layout)) ? component.layout : component.layout.type;
            }

            var availableToggle = _.find(options.meta.availableToggles, function (curr) {
                return curr.name === toggle;
            }, this);
            if (toggle && availableToggle) {
                var disabled = !!availableToggle.disabled;
                temp[toggle] = {toggle: toggle, title: availableToggle.label, 'class': availableToggle.icon, disabled: disabled};
            }
        }, this);

        // Sort the toggles by the order in the availableToggles list
        _.each(options.meta.availableToggles, function(toggle) {
            if (temp[toggle.name]) {
                this.toggles.push(temp[toggle.name]);
            }
        }, this);
    },

    /**
     * @override
     * @private
     * @param {Component} component
     * @param {Object} def
     */
    _placeComponent: function (component, def) {
        var toggleAvailable = _.isObject(_.find(this.options.meta.availableToggles, function (curr) {
            return curr.name === component.name;
        }));

        if (toggleAvailable) {
            this.componentsList[component.name] = component;
        }

        this.$('.main-content').append(component.el);
    },

    /**
     * Show a toggle
     * @param {Event} e
     */
    toggleView: function (e) {
        var $el = this.$(e.currentTarget);
        // Only toggle if we click on an inactive button
        if (!$el.hasClass("active")) {
            var data = $el.data();
            app.user.lastState.set(this.toggleViewLastStateKey, data.view);
            this.showComponent(data.view);
            this._toggleAria($el);
        }
    },

    /**
     * Sets all button accessibility 'aria-pressed' attributes to false
     * then sets the active button 'aria-pressed' attribute to true.
     *
     * @private
     */
    _toggleAria: function(btn) {
        this.$el.find('.btn').attr('aria-pressed', false);
        btn.attr('aria-pressed', true);
    },

    /**
     * Show and render a given component. Hide all others.
     * @param {string} name
     * @param {boolean} silent
     */
    showComponent: function (name, silent) {
        if (!name) return;

        _.each(this.componentsList, function (comp) {
            if (comp.name === name) {
                comp.show();

                // Should only render if the component has never rendered before.
                if (!comp._skipRenderWhenToggled) {
                    comp.render();
                    comp._skipRenderWhenToggled = true;
                }
            } else {
                comp.hide();
            }
        }, this);

        //Need to respect silent param if true as it prevents double rendering:
        //SP-1766-Filter for sidecar modules causes two requests to list view
        this.trigger('filterpanel:change', name, silent);
    },

    /**
     * @override
     * @private
     */
    _dispose: function () {
        _.each(this.componentsList, function (component) {
            if (component) {
                component.dispose();
            }
        });
        this.componentsList = {};
        app.view.Layout.prototype._dispose.call(this);
    }
})
