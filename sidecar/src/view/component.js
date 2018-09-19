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

const BeforeEvent = require('core/before-event');
const User = require('core/user');

/**
 * Extends `Backbone.View`. Represents the base view class for layouts, views,
 * and fields.
 *
 * This is an abstract class.
 *
 * @module View/Component
 * @class
 * @mixes Core/BeforeEvent
 */
const Component = Backbone.View.extend({
    /*
     * FIXME SC-5690: This property will be removed when we have mixin
     * support with an ES6-style super method.
     */
    /**
     * Internal flag used to prevent {@link #events} from being delegated
     * prior to {@link #initialize}, in {@link #delegateEvents}. Do not use
     * this flag!
     *
     * @private
     * @type {boolean}
     * @memberOf View/Component
     * @instance
     */
    _isInitialized: false,

    /**
     * Constructor for sidecar components, currently used to define the
     * order of event delegation on {@link #events this component's events},
     * after Backbone changed the order in which events are delegated from
     * 0.9.10 to 1.2.0. Also temporarily defines {@link #options} on the
     * component, as Backbone no longer does this by default.
     *
     * @param {Object} options The `Backbone.View` initialization options.
     * @return {Backbone.View} The created `Backbone.View`.
     * @memberOf View/Component
     * @instance
     */
    constructor: function(options) {
        /**
         * Backbone view options.
         *
         * @deprecated Deprecated since 7.8.0 since this is no longer supported
         *   by Backbone.
         * @type {Object}
         * @memberOf View/Component
         * @name options
         * @instance
         */
        this.options = options || {}; // FIXME SC-5597: delete this

        this._wrapInitialize(options);

        return Backbone.View.apply(this, arguments);
    },

    /**
     * Wraps the initialize method to delegate the events on the element,
     * after it initializes.
     *
     * @param {Object} options The Backbone.View initialization options.
     * @protected
     * @memberOf View/Component
     * @instance
     */
    _wrapInitialize: function(options) {
        this.initialize = _.wrap(this.initialize, _.bind(function (init) {
            init.call(this, options);
            this.initialize = init;
            this._isInitialized = true;
            this.delegateEvents();
        }, this));
    },

    /**
     * Initializes this component.
     *
     * @param {Object} options The `Backbone.View` initialization options.
     * @param {Core/Context} options.context Reference to the context.
     * @param {Object} [options.meta] Component metadata.
     * @param {string} [options.module] Module name.
     * @param {Data/Bean} [options.model] Reference to the model this
     *   component is bound to.
     * @param {Data/BeanCollection} [options.collection] Reference to the
     *   collection this component is bound to.
     * @memberOf View/Component
     * @instance
     */
    initialize: function(options) {
        /**
         * Reference to the context (required).
         * @type {Core/Context}
         * @memberOf View/Component
         * @name context
         * @instance
         */
        this.context = options.context || SUGAR.App.controller && SUGAR.App.controller.context || new Backbone.Model();

        /**
         * Component metadata (optional).
         * @type {Object}
         * @memberOf View/Component
         * @name meta
         * @instance
         */
        this.meta = options.meta;

        /**
         * Module name (optional).
         * @type {string}
         * @memberOf View/Component
         * @name module
         * @instance
         */
        this.module = options.module || this.context.get('module');

        /**
         * Reference to the model this component is bound to.
         * @type {Data/Bean}
         * @memberOf View/Component
         * @name model
         * @instance
         */
        this.model = options.model || this.context.get('model');

        /**
         * Reference to the collection this component is bound to.
         * @type {Data/BeanCollection}
         * @memberOf View/Component
         * @name collection
         * @instance
         */
        this.collection = options.collection || this.context.get('collection');

        // Adds classes to the component based on the metadata.
        if (this.meta && this.meta.css_class) {
            this.$el.addClass(this.meta.css_class);
        }

        this.updateVisibleState(true);

        // Register last state defaults
        User.lastState.register(this);
    },

    /**
     * Renders this component.
     *
     * Override this method to provide custom logic.
     * The default implementation does nothing.
     * See `Backbone.View#render` for details.
     * The convention is for {@link #_render} to always return `this`.
     *
     * @return {View/Component} Instance of this component.
     * @protected
     * @memberOf View/Component
     * @instance
     */
    _render: function() {
        return this;
    },

    /**
     * Renders this component.
     *
     * **IMPORTANT**: Do not override this method.
     * Instead, override {@link View/Component#_render} to provide render logic.
     *
     * @return {View/Component} Instance of this component.
     * @memberOf View/Component
     * @instance
     */
    render: function() {
        if (this.disposed === true) {
            SUGAR.App.logger.error("Unable to render component because it's disposed " + this + '\n');
            return false;
        }
        if (!this.triggerBefore('render'))
            return false;
        this._render();

        this.trigger('render');

        return this;
    },

    /**
     * Proxies the parent method on `Backbone.View`, but only called after
     * {@link #initialize this view instance initializes}.
     *
     * @return {View/Component} Instance of this component.
     * @memberOf View/Component
     * @instance
     */
    delegateEvents: function () {
        if (!this._isInitialized) {
            return this;
        }

        // FIXME SC-5680: We can't call `_super` from View/Component - it
        // would just call this method again. SC-5680 will address fixing
        // the `_super` method.
        return Backbone.View.prototype.delegateEvents.apply(this, arguments);
    },

    /**
     * Sets template option.
     *
     * If the given option already exists it is augmented by the value of the
     * given `option` parameter.
     *
     * See the Handlebars.js documentation for details.
     *
     * @param {string} key Option key.
     * @param {Object} option Option value.
     * @memberOf View/Component
     * @instance
     */
    setTemplateOption: function(key, option) {
        this.options.templateOptions = this.options.templateOptions || {};
        this.options.templateOptions[key] = _.extend({}, this.options.templateOptions[key], option);
    },

    /**
     * Binds data changes to this component.
     *
     * @abstract
     * @memberOf View/Component
     * @instance
     */
    bindDataChange: function() {
        // Override this method to wire up model/collection events
    },

    /**
     * Removes this component's event handlers from model and collection.
     *
     * Performs the opposite of what {@link View/Component#bindDataChange}
     * method does.
     *
     * Override this method to provide custom logic.
     *
     * @memberOf View/Component
     * @instance
     */
    unbindData: function() {
        if (this.model) this.model.off(null, null, this);
        if (this.collection) this.collection.off(null, null, this);
    },

    /**
     * Removes all event callbacks registered within this component
     * and undelegates Backbone events.
     *
     * Override this method to provide custom logic.
     *
     * @memberOf View/Component
     * @instance
     */
    unbind: function() {
        this.off();
        this.offBefore();
        this.undelegateEvents();
        SUGAR.App.events.off(null, null, this);
        SUGAR.App.events.unregister(this);
        if (this.context) this.context.off(null, null, this);
        if (this.layout) this.layout.off(null, null, this);
    },

    /**
     * Fetches data for layout's model or collection.
     *
     * The default implementation does nothing.
     * See {@link View/Layout#loadData} and {@link View/View#loadData} methods.
     *
     * @method
     * @memberOf View/Component
     * @instance
     */
    loadData: _.noop,

    /**
     * Disposes this component.
     *
     * This method:
     *
     * * unbinds this component from model and collection
     * * removes all event callbacks registered within this component
     * * removes this component from the DOM
     *
     * Override this method to provide custom logic:
     * ```
     * const ViewManager = require('view/view-manager');
     * ViewManager.views.MyView = ViewManager.View.extend({
     *      _dispose: function() {
     *          // Perform custom clean-up. For example, clear timeout handlers, etc.
     *          ...
     *          // Call super
     *          ViewManager.View.prototype._dispose.call(this);
     *      }
     * });
     * ```
     * @protected
     * @memberOf View/Component
     * @instance
     */
    _dispose: function() {
        this.unbindData();
        this.unbind();
        this.remove();
        this.model = null;
        this.collection = null;
        this.context = null;
        this.$el = null;
        this.el = null;
    },

    /**
     * Disposes a component.
     *
     * Once the component gets disposed it can not be rendered.
     * Do not override this method. Instead override
     * {@link View/Component#_dispose} method
     * if you need custom disposal logic.
     *
     * @memberOf View/Component
     * @instance
     */
    dispose: function() {
        if (this.disposed === true) return;
        this._dispose();
        this.disposed = true;
    },

    /**
     * Gets a string representation of this component.
     *
     * @return {string} String representation of this component.
     * @memberOf View/Component
     * @instance
     */
    toString: function() {
        return this.cid +
            '-' + (this.$el && this.$el.id ? this.$el.id : '<no-id>') +
            '/' + this.module +
            '/' + this.model +
            '/' + this.collection;
    },

    /**
     * Traverses upwards from the current component to find the first
     * component that matches the name.
     *
     * The default implementation does nothing.
     * See {@link View/Layout#closestComponent},
     * {@link View/View#closestComponent} and
     * {@link View/Field#closestComponent} methods.
     *
     * @param {string} name The name of the component to find.
     * @return {View/Component} The component or `undefined` if not found.
     * @method
     * @memberOf View/Component
     * @instance
     */
    closestComponent: _.noop,

    /**
     * Pass through function to jQuery's show to show view.
     *
     * @return {boolean|undefined} `false` if the BeforeEvent for `show` fails;
     *   `undefined` otherwise.
     * @memberOf View/Component
     * @instance
     */
    show: function() {
        if (!this.isVisible()) {
            if (!this.triggerBefore('show')) {
                return false;
            }

            this._show();
            this.trigger('show');
        }
    },

    /**
     * Pass through function to jQuery's hide to hide view.
     *
     * @return {boolean|undefined} `false` if the BeforeEvent for `hide` fails;
     *   `undefined` otherwise.
     * @memberOf View/Component
     * @instance
     */
    hide: function() {
        if (this.isVisible()) {
            if (!this.triggerBefore('hide')) {
                return false;
            }

            this._hide();
            this.trigger('hide');
        }
    },

    /**
     * Checks if this component is visible on the page.
     *
     * @return {boolean} `true` if this component is visible on the page;
     *   `false` otherwise.
     * @memberOf View/Component
     * @instance
     */
    isVisible: function() {
        return this._isVisible;
    },

    /**
     * Updates this component's visibility state.
     *
     * **Note:** This does not show/hide the component. Please use
     * {@link View/Component#show} and {@link View/Component#hide} to do this.
     *
     * @param {boolean} visible Visibility state of this component.
     * @memberOf View/Component
     * @instance
     */
    updateVisibleState: function(visible) {
        /**
         * Flag to indicate the visible state of the component.
         *
         * @type {boolean}
         * @private
         * @memberOf View/Component
         * @instance
         */
        this._isVisible = !!visible;
    },

    /**
     * Override this method to provide custom show logic.
     *
     * @protected
     * @memberOf View/Component
     * @instance
     */
    _show: function() {
        this.$el.removeClass('hide').show();
        this.updateVisibleState(true);
    },

    /**
     * Override this method to provide custom show logic.
     *
     * @protected
     * @memberOf View/Component
     * @instance
     */
    _hide: function() {
        this.$el.addClass('hide').hide();
        this.updateVisibleState(false);
    },

    /**
     *  Retrieves and invokes parent prototype functions.
     *
     *  Requires a method parameter to function. The method called should be
     *  named the same as the function being called from.
     *
     * Examples:
     *
     * * Good:
     * ```
     * ({
     *     initialize: function(options) {
     *         // extend the base meta with some custom meta
     *         options.meta = _.extend({}, myMeta, options.meta || {});
     *         // Only call parent initialize from initialize
     *         this._super('initialize', [options]);
     *         this.buildFoo(options);
     *     }
     * });
     * ```
     *
     * * Bad:
     * ```
     * ({
     *     initialize: function(options) {
     *         // extend the base meta with some custom meta
     *         options.meta = _.extend({}, myMeta, options.meta || {});
     *         // Calling a function like buildFoo from initialize is incorrect. Should call directly on this
     *         this._super('buildFoo',[options]);
     *     }
     * });
     * ```
     *
     * @param {string} method The name of the method to call (e.g.
     *   `initialize`, `_renderHtml`).
     * @param {Array} [args] Arguments to pass to the parent method.
     * @return {*} The result of invoking the parent method.
     * @protected
     * @memberOf View/Component
     * @instance
     */
    _super: function(method, args) {
        //Must be used to invoke parent methods
        if (!method || !_.isString(method)) {
            return SUGAR.App.logger.error('tried to call _super without specifying a parent method in ' + this.name);
        }

        var parent, thisProto = Object.getPrototypeOf(this);
        args = args || [];

        //_lastSuperClass is used to walk the prototype chain
        this._superStack = this._superStack || {};
        this._superStack[method] = this._superStack[method] || [];
        if (this._superStack[method].length > 0) {
            parent = Object.getPrototypeOf(_.last(this._superStack[method]));
            if (_.contains(this._superStack[method], parent)) {
                return SUGAR.App.logger.error('Loop detected calling ' + method + ' from ' + this.name);
            }
        } else {
            parent = Object.getPrototypeOf(thisProto);
        }

        //First verify that the method exists on the current object
        if (!thisProto[method]) {
            return SUGAR.App.logger.error('Unable to find method ' + method + ' on class ' + this.name);
        }

        //Walk up the chain until we find a parent that implements the method.
        while (!parent.hasOwnProperty(method) && parent !== Component.prototype) {
            parent = Object.getPrototypeOf(parent);
        }

        //Walk up the chain until we find a parent that overrode the method.
        while (thisProto[method] === parent[method] && parent !== Component.prototype) {
            thisProto = parent;
            parent = Object.getPrototypeOf(parent);
        }
        this._superStack[method].push(parent);

        //Verify that we found a valid parent that implements this method
        if (!parent) {
            return SUGAR.App.logger.error('Unable to find parent of component ' + this.name);
        }
        if (!parent[method]) {
            return SUGAR.App.logger.error('Unable to find method ' + method + ' on parent class of ' + this.name);
        }

        //Finally make the parent call
        var ret = parent[method].apply(this, args);

        //Reset the last parent to step down the prototype chain
        this._superStack[method].pop();
        //When we reach the end of the chain, also remove the method name requirement
        if (_.isEmpty(this._superStack[method])) {
            this._superStack[method] = null;
        }

        return ret;
    },

    /**
     * Gets the HTML placeholder for this component.
     *
     * @return {Handlebars.SafeString} HTML placeholder to be used in a
     *   Handlebars template.
     * @memberOf View/Component
     * @instance
     */
    getPlaceholder: function() {
        return new Handlebars.SafeString('<span cid="' + this.cid + '"></span>');
    }
});

//Mix in the beforeEvents
_.extend(Component.prototype, BeforeEvent);

module.exports = Component;
