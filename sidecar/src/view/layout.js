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

const Component = require('view/component');
const PluginManager = require('core/plugin-manager');
const Template = require('view/template');
const ViewManager = require('view/view-manager');

/**
 * The Layout Object is a definition of views and their placement on a certain
 * 'page'.
 *
 * Use {@link module:Sidecar/View/ViewManager Sidecar/View/ViewManager} to
 * create instances of layouts.
 *
 * ### A Quick Guide for Creating a Layout Definition
 *
 * Creating Layouts is easy. All it takes is adding the appropriate metadata
 * file. Let's create a layout called **`SampleLayout`**.
 *
 * #### The Layout File and Directory Structure
 * Layouts are located in the **`modules/MODULE/metadata/layouts`** folder. Add
 * a file called **`SampleLayout.php`** in the folder and it should be picked
 * up in the next metadata sync call.
 *
 * #### The Metadata
 * ```
 * $viewdefs['MODULE']['PLATFORM (portal / mobile / base)']['layout']['samplelayout'] = array(
 *     'type' => 'columns',
 *     'components' => array(
 *         0 => array(
 *             'layout' => array(
 *             'type' => 'column',
 *             'components' => array(
 *                 array(
 *                     'view' => 'list',
 *                 ),
 *                 array(
 *                     'view' => 'list',
 *                     'context' => array(
 *                         'module' => 'Leads',
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 * );
 * ```
 *
 * As you can see we are defining a column style layout with two subcomponents: A normal list view
 * of the MODULE, and also a list view of Leads.
 *
 * #### Accessing the New Layout
 * The last step is to add a route in the Router to display the new layout. // TODO: Custom routes?
 *
 * @module View/Layout
 * @class
 * @extends View/Component
 */
const Layout = Component.extend({
    initialize: function(options) {
        PluginManager.attach(this, 'layout');
        Component.prototype.initialize.call(this, options);

        this._components = [];

        this.meta = this.meta || {};

        this.type = this.meta.type || this.options.type;

        /**
         * Layout Name.
         * @type {string}
         * @memberOf View/Layout
         * @name name
         * @instance
         */
        this.name = this.meta.name || this.options.name || this.type || "";

        /**
         * Layout Label.
         * @type {string}
         * @memberOf View/Layout
         * @name label
         * @instance
         */
        if (this.meta.label) {
            this.label = this.meta.label;
        } else if (this.options.def && this.options.def.label) {
            this.label = this.options.def.label;
        } else if (this.options.label) {
            this.label = this.options.label;
        } else {
            this.label = '';
        }

        /**
         * Reference to the parent layout instance.
         * @type {View/Layout}
         * @memberOf View/Layout
         * @name layout
         * @instance
         */
        this.layout = this.options.layout;

        this._loadTemplate(options);

        if (this.template) {
            // Need to do .append() rather than .html() because we create new components
            // in templates. By performing .html(), we remove that component from the document.
            this.$el.append(this.template(this, options));
            this.placeComponentsFromTemplate();
        }

        if (this.meta.lazy_loaded) {
            /**
             * Holds the components metadata in case if it is a lazy loaded
             * layout. This property should be used to to initialize
             * components manually.
             *
             * @type {Array}
             * @protected
             * @memberOf View/Layout
             * @name _componentsMeta
             * @instance
             */
            this._componentsMeta = this.meta.components;
            this.meta.components = [];
        }

        SUGAR.App.events.on('app:locale:change', function() {
            this.render();
        }, this);
    },

    /**
     * Sets the appropriate template for this layout to {@link #template}.
     *
     * Gets the template from this layout's module, or falls back to the
     * module specified in the `loadModule` meta property, or falls back to
     * base.
     *
     * @param {Object} [options] A hash of options.
     * @param {string} [options.loadModule] The fallback module to get the
     *   template from.
     * @private
     * @memberOf View/Layout
     * @instance
     */
    _loadTemplate: function (options) {
        options = options || {};
        if (this.meta.template) {
            this.template = Template.getLayout(this.meta.template, this.module) ||
                Template.getLayout(this.meta.template, options.loadModule);
            return;
        }

        var tpl = Template.getLayout(this.name, this.module) ||
            Template.getLayout(this.type, this.module);
        if (!tpl) {
            if (options.loadModule) {
                tpl = Template.getLayout(this.name, options.loadModule) ||
                    Template.getLayout(this.type, options.loadModule);
            } else {
                tpl = Template.getLayout(this.name) ||
                    Template.getLayout(this.type);
            }
        }

        this.template = tpl;
    },

    /**
     * Move components created in templates to their appropriate placeholders.
     *
     * @memberOf View/Layout
     * @instance
     */
    placeComponentsFromTemplate: function() {
        var componentElems = {};

        if (_.isEmpty(this._components)) {
            return;
        }

        this.$('span[cid]').each(function() {
            var $this = $(this),
                cid = $this.attr('cid');

            componentElems[cid] = $this;
        });

        _.each(this._components, function(component) {
            var $placeholder = componentElems[component.cid];
            if ($placeholder) {
                $placeholder.replaceWith(component.el);
            }
        }, this);
    },

    /**
     * Creates subcomponents in the layout and appends them to the layout's
     * {@link #_components}. It recursively initializes all components
     * within the specified subcomponents.
     *
     * Calls {@link View/Layout#_addComponentsFromDef} to help add components.
     * Triggers `init` on this layout once all subcomponent initializes.
     *
     * Immediate subcomponent will default to the passed in context and
     * module.
     *
     * Subsequent subcomponents will default to their parents' context and
     * module.
     *
     * @param {Array} [components=this.meta.components] The definitions of
     *   the subcomponents.
     * @param {Object} [context] Context to pass to the new components.
     * @param {string} [module] Module to create the components from.
     * @return {Array} initialized components
     * @memberOf View/Layout
     * @instance
     */
    initComponents: function(components, context, module) {
        if (this.disposed) {
            return;
        }

        var newSubComponents, initialLength;
        components = components || this.meta.components;

        initialLength = this._components.length;
        this._addComponentsFromDef(components, context, module);
        newSubComponents = this._components.slice(initialLength);

        _.each(newSubComponents, function(comp) {
            if (_.isFunction(comp.initComponents)) {
                comp.initComponents();
            }
        });

        this.trigger('init');

        return newSubComponents;
    },

    /**
     * Creates a component based on the given metadata, context and module.
     *
     * @param {Object} def Metadata defining this component.
     * @param {Core/Context} context Default context to pass to the new
     *   component. (Will be overridden by the context in 'def' param, if
     *   defined).
     * @param {string} module The module to pass to the component.
     *   (Will be overridden by the context's module in 'def' param, if
     *   defined).
     * @return {View/Layout|View/View} The created component.
     * @memberOf View/Layout
     * @instance
     */
    createComponentFromDef: function(def, context, module) {
        context = context || this.context;
        module = module || this.module;

        // Switch context if necessary
        if (def.context) {
            if (def.context instanceof SUGAR.App.Context) {
                context = def.context;
            } else {
                context = context.getChildContext(def.context);
                context.prepare();
            }

            module = context.get('module');
        }

        // Layouts/Views can either by referenced by name or have metadata defined inline
        if (def.view) {
            if (_.isString(def.view)) {
                return ViewManager.createView({
                    context: context,
                    name: def.view,
                    def: def,
                    module: module,
                    loadModule: def.loadModule,
                    primary: def.primary,
                    layout: this
                });
            } else if (_.isObject(def.view)) {
                //Inline definition of a sublayout
                return ViewManager.createView({
                    context: context,
                    module: module,
                    loadModule: def.loadModule,
                    meta: def.view,
                    type: def.view.type,
                    name: def.view.name,
                    primary: def.view.primary,
                    layout: this
                });
            }
        } else if (def.layout) {
            if (_.isString(def.layout)) {
                return ViewManager.createLayout({
                    context: context,
                    name: def.layout,
                    def: def,
                    module: module,
                    loadModule: def.loadModule,
                    layout: this
                });
            } else if (_.isObject(def.layout)) {
                //Inline definition of a sublayout
                return ViewManager.createLayout({
                    context: context,
                    module: module,
                    loadModule: def.loadModule,
                    meta: def.layout,
                    type: def.layout.type,
                    name: def.layout.name,
                    def: def,
                    layout: this
                });
            }
        } else {
            SUGAR.App.logger.warn('Invalid layout definition:\n' + def.layout);
        }
    },

    /**
     * Creates and adds components to the current layout.
     *
     * Calls {@link View/Layout#addComponent} to help add components.
     * Calls {@link View/Layout#createComponentFromDef} to help create
     * components.
     *
     * @param {Array} [components=[]] The components to be created.
     * @param {Object} [context] Context to pass to the new components.
     * @param {string} [module] Module to create the components from.
     * @protected
     * @memberOf View/Layout
     * @instance
     */
    _addComponentsFromDef: function(components, context, module) {
        components = components || [];
        _.each(components, function(def) {
            // If component has already been initialized by the template, do
            // not initialize again.
            if (def.initializedInTemplate) {
                delete def.initializedInTemplate;
                return;
            }

            if (def.view || def.layout) {
                this.addComponent(this.createComponentFromDef(def, context, module), def);
            }
        }, this);
    },

    /**
     * Adds a component to this layout.
     *
     * @param {View/Layout|View/View} component Component (view or layout) to add.
     * @param {Object} def Metadata definition.
     * @memberOf View/Layout
     * @instance
     */
    addComponent: function(component, def) {
        if (!component.layout) component.layout = this;
        this._components.push(component);
        this._placeComponent(component, def); // Some implementations of placeComponent require a def
    },

    /**
     * Places layout component in the DOM.
     *
     * Default implementation just appends all the components to itself.
     * Override this method to support custom placement of components.
     *
     * @param {View/View|View/Layout} component View or layout component.
     * @protected
     * @memberOf View/Layout
     * @instance
     */
    _placeComponent: function(component) {
        this.$el.append(component.el);
    },

    /**
     * Removes a component from this layout.
     *
     * @param {View/Layout|View/View|number} component The layout or view to
     *   remove. If given as a number, remove the component at that index.
     * @memberOf View/Layout
     * @instance
     */
    removeComponent: function(component) {
        var i = _.isNumber(component) ? component : this._components.indexOf(component);

        if (i > -1) {
            var removed = this._components.splice(i, 1);
            removed[0].layout = null;
        }
    },

    /**
     * Gets a component by name.
     *
     * @param {string} name Component name.
     * @return {View/View|View/Layout} Component with the given name.
     * @memberOf View/Layout
     * @instance
     */
    getComponent: function (name) {
        return _.find(this._components, function(component) {
            return component.name === name;
        });
    },

    /**
     * Renders all the components.
     *
     * @return {View/Layout} The instance of this layout.
     * @protected
     * @memberOf View/Layout
     * @instance
     */
    _render: function() {
        if (this._components && this._components.length > 0) {
            //default layout will pass render container divs and pass down to all its views.
            _.each(this._components, function(component) {
                component.render();
            }, this);
        } else {
            SUGAR.App.logger.info("Can't render anything because the layout has no components: " + this.toString() +
                '\nEither supply metadata or override Layout.render method');
        }
        return this;
    },

    /**
     * Fetches data for layout's model or collection.
     *
     * The default implementation first calls the {@link Core/Context#loadData}
     * method for the layout's context and then iterates through the components
     * and calls their {@link View/Component#loadData} method, setting their
     * contexts' `fields` property beforehand.
     *
     * Override this method to provide a custom fetch algorithm.
     *
     * @param {Object} [options] Options that are passed to
     *   collection/model's fetch method.
     * @memberOf View/Layout
     * @instance
     */
    loadData: function (options) {
        if (this.disposed) {
            return;
        }

        this.context.loadData(options);

        _.each(this._components, function(component) {
            component.loadData(options);
        }, this);
    },

    /**
     * Gets a list of all fields used on this layout and its sub layouts/views.
     *
     * @param {string} [module=this.module] Module name.
     * @return {Array} The list of fields used by this layout.
     * @memberOf View/Layout
     * @instance
     */
    getFieldNames: function(module) {
        var fields = [];
        module = module || this.module;
        _.each(this._components, function(component) {
            if (component.module == module) {
                fields = _.union(fields, component.getFieldNames(null, true));
            }
        }, this);

        return fields;
    },

    /**
     * Gets a hash of fields that are currently displayed on this layout.
     *
     * The hash has field names as keys and field definitions as values.
     * @param {string} [module] Module name.
     * @return {Object} The currently displayed fields.
     * @memberOf View/Layout
     * @instance
     */
    getFields: function(module) {
        var fields = {};
        _.each(this._components, function(component) {
            _.extend(fields, component.getFields(module));
        });
        return fields;
    },

    // FIXME: this needs to be documented!
    closestComponent: function(name) {
        if (!this.layout) {
            return;
        }
        if (this.layout.name === name) {
            return this.layout;
        }
        return this.layout.closestComponent(name);
    },


    /**
     * @inheritdoc
     * @memberOf View/Layout
     * @instance
     */
    _show: function() {
        Component.prototype._show.call(this);
        _.each(this._components, function(component) {
            component.updateVisibleState(true);
        });
    },

    /**
     * @inheritdoc
     * @memberOf View/Layout
     * @instance
     */
    _hide: function() {
        Component.prototype._hide.call(this);
        _.each(this._components, function(component) {
            component.updateVisibleState(true);
        });
    },

    /**
     * Disposes a layout.
     *
     * Disposes each of this layout's components and calls the
     * {@link View/Component#_dispose} method of the base class.
     *
     * @protected
     * @memberOf View/Layout
     * @instance
     */
    _dispose: function() {
        PluginManager.detach(this, 'layout');
        this._disposeComponents();
        Component.prototype._dispose.call(this);
    },

    /**
     * Disposes the layout's components and empties the `_components`
     * property.
     *
     * @private
     * @memberOf View/Layout
     * @instance
     */
    _disposeComponents: function() {
        _.each(this._components, function(component) {
            component.dispose();
        });
        this._components = [];
    },

    /**
     * Gets a string representation of this layout.
     *
     * @return {string} String representation of this layout.
     * @memberOf View/Layout
     * @instance
     */
    toString: function() {
        return "layout-" + (this.options.type || this.options.name) + "-" +
            Component.prototype.toString.call(this);
    }
});

module.exports = Layout;
