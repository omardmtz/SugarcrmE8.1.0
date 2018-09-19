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

const Events = require('core/events');
const Context = require('core/context');
const ViewManager = require('view/view-manager');

/**
 * The controller manages the loading and unloading of layouts.
 *
 * ## Extending controller
 *
 * Applications may choose to extend the controller to provide a custom
 * implementation. Your custom controller class name should be the capitalized
 * {@link Config#appId|appID} for your application followed by the word
 * "Controller".
 *
 * Example:
 * ```
 * SUGAR.App.PortalController = SUGAR.App.controller.extend({
 *     loadView: function(params) {
 *        // Custom implementation of loadView
 *        // Should call super method:
 *        SUGAR.App.Controller.prototype.loadView.call(this, params);
 *     }
 * });
 * ```
 *
 * @module Core/Controller
 */

/**
 * @alias module:Core/Controller
 */
module.exports = Backbone.View.extend({
    /**
     * Instantiates the application's main context and binds global events.
     *
     * @memberOf module:Core/Controller
     * @instance
     */
    initialize: function() {
        /**
         * The primary context of the app. This context is associated with the
         * root layout.
         *
         * @type {Core/Context}
         * @memberOf module:Core/Controller
         * @alias context
         * @instance
         */
        this.context = new Context();

        Events.on('app:sync:complete', function() {
            SUGAR.App.api.setStateProperty('loadingAfterSync', true);
            _.each(SUGAR.App.additionalComponents, function(component) {
                if (component && _.isFunction(component._setLabels)) {
                    component._setLabels();
                }
                component.render();
            });
            SUGAR.App.router.reset();
            SUGAR.App.api.clearStateProperty('loadingAfterSync');
        });

        Events.on('app:login:success', function() {
            SUGAR.App.sync();
        });
    },

    /**
     * Loads a layout.
     *
     * Sets the context with the given params, creates the layout based on
     * metadata, loads the data and renders it. It also disposes the previous
     * displayed layout. This method is called by the router when the route is
     * changed.
     *
     * @memberOf module:Core/Controller
     * @param {Object} params Properties to set to the context and used to
     *   determine the layout to load.
     * @param {string} params.layout The layout name. It will be used to grab
     *   the corresponding metadata.
     * @param {string} params.module The module the layout belongs to.
     * @param {boolean} [params.skipFetch=false] If `true`, do not fetch the
     *   data.
     * @instance
     */
    loadView: function(params) {
        var oldLayout = this.layout;

        //FIXME SC-5124 will trigger 'app:view:load', and add a deprecation warning for 'app:view:change'.
        if (!SUGAR.App.triggerBefore('app:view:change') || !SUGAR.App.triggerBefore('app:view:load')) {
            return;
        }

        // Reset context and initialize it with new params
        this.context.clear({silent: true});
        this.context.set(params);

        // Prepare model and collection
        this.context.prepare();
        // Create an instance of the layout and bind it to the data instance
        this.layout = ViewManager.createLayout({
            name: params.layout,
            module: params.module,
            context: this.context
        });

        if (oldLayout) {
            // Take out the previous layout element from the content container,
            // and then keep it in the document fragment
            // in order to destroy jQuery plugin safe.
            var oldLayoutEl = document.createDocumentFragment();
            oldLayoutEl.appendChild(oldLayout.el);
        }

        // Render the layout to the main element
        // Since the previous element is already gone,
        // .append is better way because .html requires
        // additional cost for .empty().
        SUGAR.App.$contentEl.append(this.layout.$el);

        //initialize subcomponents in the layout
        this.layout.initComponents();

        // Fetch the data, the layout will be rendered when fetch completes
        if (!params || (params && !params.skipFetch)) {
            this.layout.loadData();
        }

        // Render the layout with empty data
        this.layout.render();

        if (oldLayout) {
            oldLayout.dispose();
        }

        SUGAR.App.trigger('app:view:change', params.layout, params);
    },

    /**
     * Creates, renders, and registers within the app additional components.
     *
     * @memberOf module:Core/Controller
     * @param {Object} components The components to load. They will
     *  be created using metadata view definitions and rendered on the page.
     *  The components objects are cached in the the global `SUGAR.App` variable
     *  under the `additionalComponents` property.
     * @instance
     */
    loadAdditionalComponents: function(components) {
        if (!_.isEmpty(SUGAR.App.additionalComponents)) {
            SUGAR.App.logger.error('`Controller.loadAdditionalComponents` has already been called. ' +
                'It can not be called twice.');
            return;
        }

        SUGAR.App.additionalComponents = {};
        _.each(components, function(component, name) {
            if (component.target) {
                var $el = this.$(component.target);
                if (!$el.get(0)) {
                    SUGAR.App.logger.error('Unable to place additional component "' + name + '": the target specified ' +
                        'does not exist.');
                    return;
                }

                if (component.layout) {
                    SUGAR.App.additionalComponents[name] = ViewManager.createLayout({
                        context: this.context,
                        type: component.layout,
                        el: $el,
                    });
                    SUGAR.App.additionalComponents[name].initComponents();
                } else {
                    SUGAR.App.additionalComponents[name] = ViewManager.createView({
                        type: component.view || name,
                        context: this.context,
                        el: $el,
                    });
                }
                SUGAR.App.additionalComponents[name].render();
            } else {
                SUGAR.App.logger.error('Unable to place additional component "' + name + '": no target specified.');
            }
        }, this);
    }
});
