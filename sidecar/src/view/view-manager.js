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

const Utils = require('utils/utils');

/**
 * View manager is used to create views, layouts, and fields based on optional
 * metadata inputs.
 *
 * The view manager's factory methods (`createView`, `createLayout`, and
 * `createField`) first check `views`, `layouts`, and `fields` hashes
 * respectively for custom class declaration before falling back the base class.
 *
 * Note the following is deprecated in favor of putting these controllers in the
 * `sugarcrm/clients/<platform>` directory, or using one of the appropriate
 * factory methods like `createView`, `createField`, or `createLayout`. Using
 * either of these idioms, your components will be internally namespaced by
 * platform for you. If you do choose to use the following idiom of defining
 * your controller directly on `ViewManager.view.<type>`, please be forewarned
 * that you will lose any automatic namespacing benefits and possibly encounter
 * naming collisions if your controller names are not unique. If you must
 * define directly, you may choose to prefix your controller name by your
 * application or platform e.g. `MyappMyCustom<Type>` where 'Myapp' is the
 * platform prefix.
 *
 * Put declarations of your custom views, layouts, fields in the corresponding
 * hash (see note above; this is deprecated):
 * ```
 * const ViewManager = require('view/view-manager');
 * ViewManager.views.MyappMyCustomView = ViewManager.View.extend({
 *  // Put your custom logic here
 * });
 *
 * ViewManager.layouts.MyappMyCustomLayout = ViewManager.Layout.extend({
 *  // Put your custom logic here
 * });
 *
 * ViewManager.view.fields.MyappMyCustomField = ViewManager.Field.extend({
 *  // Put your custom logic here
 * });
 * ```
 *
 * @module View/ViewManager
 */
// Ever incrementing field ID
var _sfId = 0;

/**
 * @alias module:View/ViewManager
 */
const ViewManager = {
    /**
     * Resets class declarations of custom components.
     */
    reset: function() {
        _.each(this.layouts, function(layout, name) {
            delete this.layouts[name];
        }, this);
        _.each(this.views, function(view, name) {
            delete this.views[name];
        }, this);
        _.each(this.fields, function(field, name) {
            delete this.fields[name];
        }, this);
    },

    /**
     * Gets the ID of the most recently created field.
     *
     * @return {number} ID of the last created field.
     */
    getFieldId: function() {
        return _sfId;
    },

    /**
     * Hash of view classes.
     */
    views: {},

    /**
     * Hash of layout classes.
     */
    layouts: {},

    /**
     * Hash of field classes.
     */
    fields: {},

    /**
     * Creates an instance of a component and binds data changes to it.
     *
     * @param {string} type Component type (`layout`, `view`, `field`).
     * @param {string} name Component name.
     * @param {Object} params Parameters to pass to the Component's class
     *   constructor.
     * @param {string} params.type The component type.
     * @param {string} params.module The component's module.
     * @param {string} [params.loadModule=params.module] The module to
     *   create the component from.
     * @return {View/Component} New instance of a component.
     * @private
     */
    _createComponent: function(type, name, params) {
        var Klass = this.declareComponent(type, params.type || name, params.loadModule || params.module,
            params.controller, false, this._getPlatform(params));
        var component = new Klass(params);
        component.trigger("init");
        component.bindDataChange();
        return component;
    },

    /**
     * Creates an instance of a view.
     *
     * Examples:
     *
     * Create a list view. The view manager will use metadata for the view
     * named 'list' defined in Contacts module.
     * The controller's current context will be set on the new view instance.
     *
     * ```
     * var listView = ViewManager.createView({
     *     type: 'list',
     *     module: 'Contacts'
     * });
     * ```
     *
     * Create a custom view class. Note the following is deprecated in favor
     * of putting these controllers in the `sugarcrm/clients/<platform>`
     * directory, or using one of the appropriate factory methods like
     * `createView`, `createField`, or `createLayout`. Using that idiom, the
     * metadata manager will declare these components and take care of
     * namespacing by platform for you. If you do choose to use the
     * following idiom please be forewarned that you will lose any
     * namespacing benefits and possibly encounter naming collisions!
     *
     * ```
     * // Declare your custom view class.
     * // might cause collisions if another MyCustomView!
     * ViewManager.views.MyCustomView = ViewManager.View.extend({
     *     // Put your custom logic here
     * });
     * // if you must define directly on ViewManager.views, you may instead
     * // prefer to do:
     * ViewManager.views.<YOUR_PLATFORM>MyCustomView = ViewManager.View.extend({
     *     // Put your custom logic here
     * });
     *
     * var myCustomView = ViewManager.createView({
     *     type: 'myCustom'
     * });
     * ```
     *
     * Create a view with custom metadata payload.
     *
     * ```
     * var view = ViewManager.createView({
     *     type: 'detail',
     *         meta: { ... your custom metadata ... }
     * });
     * ```
     *
     * Look at {@link View/View}, particularly
     * {@link View/View#_loadTemplate} for more information on how the
     * `meta.template` property can be used.
     *
     * @param {Object} params View parameters.
     * @param {string} params.type The view identifier (`default`, `base`,
     *   etc.). Matches the controller to be used.
     * @param {string} [params.name=params.type] View name that
     *   distinguishes between multiple instances of the same view type. This
     *   matches the metadata to read from {@link Core.MetadataManager} and
     *   it is the easier way to reuse view types with different
     *   configurations.
     * @param {Object} [params.context=SUGAR.App.controller.context] Context to
     *   associate the newly created view with.
     * @param {string} [params.module] Module name.
     * @param {string} [params.loadModule] The module that should be
     *   considered the base.
     * @param {Object} [params.meta] Custom metadata.
     * @return {View/View} New instance of view.
     */
    createView: function(params) {
        // context is always defined on the controller
        params.context = params.context || SUGAR.App.controller.context;
        params.module = params.module || params.context.get('module');
        // name defines which metadata to load
        params.name = params.name || params.type;
        params.meta = params.meta || SUGAR.App.metadata.getView(params.module, params.name, params.loadModule);

        if (params.def && params.def.xmeta) {
            params.meta = _.extend({}, params.meta, params.def.xmeta);
        }

        // type defines which controller to use
        var meta = params.meta || {};
        params.type = params.type || meta.type || params.name;

        return this._createComponent('view', params.type, params);
    },

    /**
     * Creates an instance of a layout.
     *
     * Parameters define creation rules as well as layout properties.
     * The factory needs either layout name or type. Also, the layout type
     * must be specified. The layout type is retrieved either from the
     * `params` hash or layout metadata.
     *
     * Examples:
     *
     * Create a list layout. The view manager will use metadata for the
     * `list` layout defined in the Contacts module.
     * The controller's current context will be set on the new layout
     * instance.
     *
     * ```
     * var listLayout = ViewManager.createLayout({
     *     type: 'list',
     *     module: 'Contacts'
     * });
     * ```
     *
     * Create a custom layout class. Note that following is deprecated in
     * favor of using the `createLayout` factory or placing controller in
     * `sugarcrm/clients/<platform>/layouts` in which case the metadata
     * manager will take care of namespacing your controller by platform
     * name for you (e.g. MyCustomLayout becomes
     * `ViewManager.layouts.MyappMyCustomLayout`).
     *
     * ```
     * // Declare your custom layout class.
     * // might cause collisions if already a MyCustomLayout!
     * ViewManager.layouts.MyCustomLayout = ViewManager.Layout.extend({
     *     // Put your custom logic here
     * });
     * // if you must define directly on ViewManager.layouts,
     * // you may instead prefer to do:
     * ViewManager.layouts.<YOUR_PLATFORM>MyCustomLayout = ViewManager.Layout.extend({
     *     // Put your custom logic here
     * });
     *
     * var myCustomLayout = ViewManager.createLayout({
     *     type: 'myCustom'
     * });
     * ```
     *
     * Create a layout with custom metadata payload.
     *
     * ```
     * var layout = ViewManager.createLayout({
     *     type: 'detail',
     *     meta: { ... your custom metadata ... }
     * });
     * ```
     *
     * @param {Object} params layout parameters.
     * @param {string} [params.type] Layout identifier (`default`, `base`,
     *   etc.).
     * @param {string} [params.name=params.type] Layout name that
     *   distinguishes between multiple instances of the same layout type.
     * @param {Object} [params.context=SUGAR.App.controller.context]
     *   Context to associate the newly created layout with.
     * @param {string} params.module Module name.
     * @param {string} [params.loadModule] The module to load the Layout
     *   from. Defaults to `params.module` or the context's module, in that
     *   order.
     * @param {Object} [params.meta] Custom metadata.
     * @return {View/Layout} New instance of the layout.
     */
    createLayout: function(params) {
        params.context = params.context || SUGAR.App.controller.context;
        params.module = params.module || params.context.get('module');
        // name defines which metadata to load
        params.name = params.name || params.type;
        params.meta = params.meta || SUGAR.App.metadata.getLayout(params.module, params.name, params.loadModule);

        if (params.def && params.def.xmeta) {
            params.meta = _.extend({}, params.meta, params.def.xmeta);
        }

        // type defines which controller to use
        var meta = params.meta || {};
        params.type = params.type || meta.type || params.name;

        return this._createComponent('layout', params.type, params);
    },

    /**
     * Creates an instance of a field and registers it with the parent view
     * (`params.view`).
     *
     * The parameters define creation rules as well as field properties.
     *
     * For example,
     *
     * ```
     * var params = {
     *     view: new Backbone.View,
     *     def: {
     *         type: 'text',
     *         name: 'first_name',
     *         label: 'LBL_FIRST_NAME'
     *     },
     *     context: optional context,
     *     model: optional model,
     *     meta: optional custom metadata,
     *     viewName: optional
     * }
     * ```
     *
     * The view manager queries the metadata manager for field type specific
     * metadata (templates and JS controller) unless custom metadata is
     * passed in the `params` hash.
     *
     * Note the following is deprecated in favor of placing custom field
     * controllers in `sugarcrm/clients/<platform>/fields` or using the
     * `createField` factory.
     *
     * To create instances of custom fields, first declare its class in the
     * `ViewManager.fields` hash:
     *
     * ```
     * // might cause collision if MyCustomField already exists!
     * ViewManager.fields.MyCustomField = ViewManager.Field.extend({
     *     // Put your custom logic here
     * });
     * // if you must define directly on ViewManager.fields
     * // you may instead prefer to do:
     * ViewManager.fields.<YOUR_PLATFORM>MyCustomField = ViewManager.Field.extend({ ...
     *
     * var myCustomField = ViewManager.createField({
     *     view: someView,
     *     def: {
     *         type: 'myCustom',
     *         name: 'my_custom'
     *     }
     * });
     * ```
     *
     * @param {Object} params Field parameters.
     * @param {Backbone.View} params.view Backbone View object.
     * @param {Object} params.def Field definition.
     * @param {Object} [params.context=`SUGAR.App.controller.context`] The
     *   context.
     * @param {Object} [params.model] The model to use. If not specified,
     *   the model which is set on the context is used.
     * @param {string} [params.viewName=params.view.name] View name to
     *   determine the field template.
     * @param {boolean} [params.nested] Set to `true` if the field is nested.
     *   This means it already has a life cycle handler, and should not be
     *   added to the view's list of fields.
     * @return {View/Field} A new instance of field.
     */
    createField: function(params) {
        var type = params.viewDefs ? params.viewDefs.type : params.def.type;
        params.context = params.context || params.view.context || SUGAR.App.controller.context;
        params.model = params.model || params.context.get("model");
        params.module = params.module || (params.model && params.model.module ? params.model.module : params.context.get('module')) || "";
        params.sfId = ++_sfId;

        var field = this._createComponent("field", type, params);
        if (!params.nested) {
            // Register new field within its parent view.
            params.view.fields[field.sfId] = field;
        } else {
            // We still keep a reference of the nested field in the parent view
            // to be able to retrieve it using View/View#getField.
            params.view.nestedFields[field.sfId] = field;
        }

        return field;
    },

    /**
     * Returns the platform from the given params, falling back to
     * `SUGAR.App.config.platform` or else 'base'.
     *
     * @param {Object} params Parameters.
     * @param {string} [params.platform] The platform (`base`, `portal`, etc.).
     * @return {string} The platform.
     * @private
     */
    _getPlatform: function(params) {
        return params.platform || (SUGAR.App.config && SUGAR.App.config.platform ? SUGAR.App.config.platform : 'base');
    },

    /**
     * Gets a controller of type field, layout, or view.
     *
     * @param {Object} params Parameters for the controller.
     * @param {string} params.type The controller type.
     * @param {string} params.name the filename of the controller
     *   (e.g. 'flex-list', 'record', etc.).
     * @param {string} [params.platform] The platform, e.g. 'portal'.
     *   Will first attempt to fall back to `SUGAR.App.config.platform`, then
     *   'base'.
     * @param {string} [params.module] The module name.
     * @return {Object|null} The controller or `null` if not found.
     * @private
     */
    _getController: function(params) {
        var c = this._getBaseComponent(params.type, params.name, params.module, params.platform);
        //Check to see if we have the module specific class; if so return that
        if (c.cache[c.moduleBasedClassName]) {
            return c.cache[c.moduleBasedClassName];
        }
        return c.baseClass;
    },

    /**
     * Checks if a component has a certain plugin.
     *
     * @param {Object} params Set of parameters passed to function.
     * @param {string} params.type Type of component to check.
     * @param {string} params.name Name of component to check.
     * @param {string} params.plugin Name of plugin to check.
     * @param {string} [params.module=''] Name of module to check for custom
     *   components in.
     * @return {boolean|null} `true` if the specified component exists and
     *   has that plugin, `false` if the component does not exist or lacks
     *   that plugin, and `null` if incorrect arguments were passed.
     */
    componentHasPlugin: function(params) {
        var controller;
        if (!params.type || !params.name || !params.plugin) {
            SUGAR.App.logger.error("componentHasPlugin requires type, name, and plugin parameters");
            return null;
        }
        controller = this._getController(params);
        return controller && controller.prototype &&
            _.contains(controller.prototype.plugins, params.plugin);
    },

    /**
     * Retrieves class declaration for a component or creates a new
     * component class.
     *
     * This method creates a subclass of the base class if controller
     * parameter is not null and such subclass hasn't been created yet.
     * Otherwise, the method tries to retrieve the most appropriate class by
     * searching in the following order:
     *
     * - Custom class name: `<module><component-name><component-type>`.
     * For example, for Contacts module one could have:
     * `ContactsDetailLayout`, `ContactsFluidLayout`, `ContactsListView`.
     *
     * - Class name: `<component-name><component-type>`.
     * For example: `ListLayout`, `ColumnsLayout`, `DetailView`, `IntField`.
     *
     * - Custom base class: `<capitalized-appId><component-type>`
     * For example, if `SUGAR.App.config.appId == 'portal'`, custom base classes
     * would be:
     * `PortalLayout`, `PortalView`, `PortalField`.
     *
     * Declarations of such classes must be in the `ViewManager` namespace.
     * There are use cases when an app has some common component code.
     * In such cases, using custom base classes is beneficial. For example,
     * any app may need to override validation error handling for fields:
     *
     * ```
     * // Assuming SUGAR.App.config.appId === 'portal':
     * ViewManager.PortalField = ViewManager.Field.extend({
     *     initialize: function(options) {
     *         // Call super
     *         ViewManager.Field.prototype.initialize.call(this, options);
     *         // Custom initialization code...
     *     },
     *
     *     handleValidationError: function(errors) {
     *         // Custom validation logic
     *     }
     * });
     * ```
     *
     * The above declaration will make all field controllers extend
     * `ViewManager.PortalField` instead of `ViewManager.Field`.
     *
     * - Base class: `<component-type>` - `Layout`, `View`, `Field`.
     *
     * Note 1. Although the view manager supports module specific fields
     * like `ContactsIntField`, the server does not provide such
     * customization.
     *
     * Note 2. The layouts is a special case because their class name is
     * built both from layout name and layout type. One could have
     * `ListLayout` or `ColumnsLayout` including their module specific
     * counterparts like `ContactsListView` and `ContactsColumnsLayout`.
     * The "named" class name is checked first.
     *
     * @param {string} type Lower-cased component type: `layout`, `view`, or
     *   `field`.
     * @param {string} name Lower-cased component name. For example, 'list'
     *   (layout or view), or 'bool' (field).
     * @param {string} [module] Module name.
     * @param {string} [controller] Controller source code string.
     * @param {boolean} [overwrite] Will overwrite if duplicate
     *   custom class or layout is cached. Note that if no controller is
     *   passed, overwrite is ignored since we can't create a meaningful
     *   component without a controller.
     * @param {string} [platform] The platform e.g. 'base', 'portal', etc.
     * @return {Function} Component class.
     */
    declareComponent: function(type, name, module, controller, overwrite, platform) {
        overwrite = !!(controller && overwrite);
        var c = this._getBaseComponent(type, name, module, platform, overwrite);
        if (overwrite && c.cache[c.moduleBasedClassName]) {
            delete c.cache[c.moduleBasedClassName];
        }

        return c.cache[c.moduleBasedClassName] ||
            Utils.extendClass(c.cache, c.baseClass, c.moduleBasedClassName, controller, c.platformNamespace) ||
            c.baseClass;
    },

    /**
     * Internal helper function for getting a component (controller). Do not
     * call directly and instead use `declareComponent`, etc.
     * depending on your needs.
     * @param {string} type Lower-cased component type: `layout`, `view`, or
     *   `field`.
     * @param {string} name Lower-cased component name. For example, `list`
     *   (layout or view), or `bool` (field).
     * @param {string} [module] Module name.
     * @param {string} [platform] The platform e.g. 'base', 'portal', etc.
     * @param {boolean} [overwrite=true] When `true`, custom controller
     *   overrides will be ignored and only components that exactly match
     *   the name will be returned. The base class returned is `base`.
     * @return {Object} The base component information.
     * @return {Object} return.cache The collection of controllers of the
     *   given component type.
     * @return {string} return.platformNamespace The platform prefix.
     * @return {string} return.moduleBasedClassName The prefixed class name.
     * @return {Object} return.baseClass The class for the base component.
     * @private
     */
    _getBaseComponent: function(type, name, module, platform, overwrite) {
        platform = this._getPlatform({platform: platform});
        // The type e.g. View, Field, Layout
        var ucType = Utils.capitalize(type);

        // The platform e.g. Base, Portal, etc.
        var platformNamespace = Utils.capitalize(platform);

        // The component name and type concatenated e.g. ListView
        var className = Utils.capitalizeHyphenated(name) + ucType;

        // The combination of platform, optional module, and className e.g. BaseAccountsListView
        var moduleBasedClassName = platformNamespace + (module || "") + className,
            customModuleBasedClassName = platformNamespace + (module || "") + "Custom" + className;
        var cache = this[type + 's'];
            // App id and type fallback
        var customBaseClassName = Utils.capitalize(SUGAR.App.config.appId) + ucType;
        // Components are now namespaced by <platform> so we must prefix className to find in cache
        // if we don't find platform-specific, than we next look in Base<className> and so on
        var baseClass = cache[platformNamespace + "Custom" + className] ||
            cache[platformNamespace + className] ||
            cache["BaseCustom" + className] ||
            cache["Base" + className] ||
            // For backwards compatibility, if they define ViewManager.views.MyView we should still find
            cache[className] ||
            cache["BaseBase" + ucType] ||
            this['Custom' + customBaseClassName] ||
            this[customBaseClassName] ||
            this[ucType];
        // Override to use the custom class instead of the standard one if it exists.
        if (cache[customModuleBasedClassName] && !overwrite) {
            moduleBasedClassName = customModuleBasedClassName;
        }
        return {
            cache: cache,
            platformNamespace: platformNamespace,
            moduleBasedClassName: moduleBasedClassName,
            baseClass: baseClass
        };
    }
};

module.exports = ViewManager;
