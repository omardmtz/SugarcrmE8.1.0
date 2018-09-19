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

const Acl = require('core/acl');
const Component = require('view/component');
const PluginManager = require('core/plugin-manager');
const Language = require('core/language');
const Template = require('view/template');

/**
 * Base View class. Use {@link View.ViewManager} to create instances of views.
 *
 * @module View/View
 * @class
 * @extends View/Component
 */
const View = Component.extend({
    /**
     * Initializes this view.
     *
     * @param {Object} options Backbone view options.
     * @memberOf View/Field
     * @instance
     */
    initialize: function(options) {
        PluginManager.attach(this, 'view');
        Component.prototype.initialize.call(this, options);

        /**
         * View type.
         * @type {string}
         * @name type
         * @memberOf View/View
         * @instance
         */
        this.type = options.type;

        /**
         * Name of the view.
         * @type {string}
         * @name name
         * @memberOf View/View
         * @instance
         */
        this.name = options.name || this.type;

        /**
         * Name of the action (optional).
         *
         * Used in acl checks for user permissions. By default, set to the view name.
         * @type {string}
         * @memberOf View/View
         * @name action
         * @instance
         */
        this.action = options.meta && options.meta.action ? options.meta.action : this.name;

        this._loadTemplate(options);

        /**
         * Dictionary of field widgets.
         *
         * * keys: field IDs (sfuuid)
         * * values: instances of {@link View/Field}
         * @type {Object}
         * @memberOf View/View
         * @name fields
         * @instance
         */
        this.fields = {};

        /**
         * Fields part of this view that are already managed by another
         * lifecycle handler.
         *
         * @type {Object}
         * @memberOf View/View
         * @name nestedFields
         * @instance
         */
        this.nestedFields = {};

        /**
         * A template to use for view fields if a field does not have a template defined for its parent view.
         * Defaults to `"default"`.
         *
         * For example, if you have a subview and don't want to define subview template for all field types,
         * you may choose to use existing templates like `detail` if your subview is in fact a detail view.
         *
         * @type {string}
         * @memberOf View/View
         * @name fallbackFieldTemplate
         * @instance
         */
        this.fallbackFieldTemplate = this.fallbackFieldTemplate || "detail";

        /**
         * Reference to the parent layout instance.
         * @type {View/Layout}
         * @name layout
         * @memberOf View/View
         * @instance
         */
        this.layout = this.options.layout;

        /**
         * Flag indicating whether a view is primary or not.
         *
         * If the primary view is not rendered due to the access control,
         * a warning message will be displayed.
         *
         * @type {boolean}
         * @name primary
         * @memberOf View/View
         * @instance
         */
        this.primary = options.primary;

        this._setLabels();

        /**
         * The view name that contains the list of fields to use when
         * fetching the model/collection from the server.
         *
         * List, record or detail views might have too many fields defined
         * in the metadata. This property avoids having to list all these
         * fields in the request params.
         *
         * @type {string}
         * @name dataView
         * @memberOf View/View
         * @instance
         */
        if (this.dataView) {
            this.context.set('dataView', this.dataView);
        } else {
            this.context.addFields(this.getFieldNames());
        }

        SUGAR.App.events.on('app:locale:change', function() {
            this._setLabels();
        }, this);
    },

    /**
     * Gets the template falling back using `loadModule` property when
     * specified.
     *
     * @param {string} name The template's name to get.
     * @param {string} [fallbackModule] The module to fall back to if the
     *   template does not exist in this view's module. If undefined, the
     *   template is grabbed in `base`.
     * @return {Function} The desired template.
     * @private
     */
    _getTemplate: function(name, fallbackModule) {
        return Template.getView(name, this.module) || Template.getView(name, fallbackModule);
    },

    /**
     * Sets the appropriate template for this view to {@link #template}.
     * Sets the name of the template to {@link #tplName}.
     *
     * @param {Object} [options] The options that may specify the template to
     *   load.
     * @param {Function} [options.template] The compiled template.
     * @param {string} [options.loadModule] The fallback module to get the
     *   template from.
     * @private
     */
    _loadTemplate: function(options) {
        var template, templateName;
        options = options || {};

        if (options.template) {
            template = options.template;
            templateName = null;
        } else if (this.meta && this.meta.template) {
            template = this._getTemplate(this.meta.template, options.loadModule);
            templateName = this.meta.template;
        } else {
            template = this._getTemplate(this.name, options.loadModule);
            templateName = this.name;
        }

        if (!template) {
            if (this.meta && this.meta.type) {
                template = this._getTemplate(this.meta.type, options.loadModule);
                templateName = this.meta.type;
            } else {
                template = Template.empty;
                templateName = '';
            }
        }

        /**
         * The name of the template that is loaded.
         * This is a public read-only property. This property should not be
         * modified directly.
         *
         * @type {string|null}
         * @memberOf View/View
         * @name tplName
         * @instance
         */
        this.tplName = templateName;

        /**
         * The template for this view.
         *
         * @type {Function}
         * @memberOf View/View
         * @name template
         * @instance
         */
        this.template = template;
    },

    /**
     * Renders a view onto the page.
     *
     * This method uses `ctx` parameter as the context for the view's
     * Handlebars {@link View/View#template} and view's
     * `options.templateOptions` property as template options.
     *
     * If no `ctx` parameter is specified, `this` is passed as the context for
     * the template.
     * If no `options` parameter is specified, `this.options.templateOptions`
     * is used.
     *
     * You can override this method if you have custom rendering logic and
     * don't use Handlebars templating or if you need to pass a different
     * context object for the template.
     *
     * Note the following use of `ViewManager.View.extend` is deprecated in
     * favor of putting these controllers in the sugarcrm/clients/<platform>
     * directory. Using that idiom, the metadata manager will declare these
     * components and take care of namespacing by platform for you
     * (so MyCustomView will be stored internally as MyappMyCustomView).
     * If you do choose to use the following idiom please be forewarned
     * that you will lose any namespacing benefits and possibly encounter
     * naming collisions!
     *
     * Example:
     * ```
     * // Note that using the following technique of defining custom views
     * // directly on the ViewManager.views object can result in naming
     * // collisions unless you ensure your name is unique. See note above.
     * ViewManager.views.CustomView = ViewManager.View.extend({
     *    _renderHtml: function() {
     *       var ctx = {
     *         // Your custom context for this view template
     *       };
     *       ViewManager.View.prototype._renderHtml.call(this, ctx);
     *    }
     * });
     *
     * // Or totally different logic that doesn't use this.template
     * ViewManager.views.AnotherCustomView = ViewManager.View.extend({
     *    _renderHtml: function() {
     *       // Never do this :)
     *       return "&lt;div&gt;Hello, world!&lt;/div&gt;";
     *    }
     * });
     * ```
     *
     * This method uses this view's {@link View/View#template} property to
     * render itself.
     *
     * @param {Core/Context} [ctx] Template context.
     * @param {Object} [options] Template options.
     * ```
     * {
     *    helpers: helpers,
     *    partials: partials,
     *    data: data
     * }
     * // See Handlebars.js documentation for details.
     * ```
     *
     * @protected
     * @memberOf View/View
     * @instance
     */
    _renderHtml: function(ctx, options) {
        if (this.template) {
            try {
                this.$el.html(this.template(ctx || this, options || this.options.templateOptions));
            } catch (e) {
                SUGAR.App.logger.error("Failed to render " + this + "\n" + e);
                SUGAR.App.error.handleRenderError(this, '_renderHtml');
            }
        }
    },

    /**
     * Renders all the fields.
     *
     * @protected
     * @memberOf View/View
     * @instance
     */
    _renderFields: function() {
        var self = this;

        // In terms of performance it is better to search the DOM once for
        // all the fields, than to search the DOM for each field. That's why
        // we cache placeholders locally and pass them to
        // {@link View/Field#_renderField}.
        var fieldElems = {};

        this.$('span[sfuuid]').each(function() {
            var $this = $(this),
                sfId = $this.attr('sfuuid');
            fieldElems[sfId] = $this;
        });

        _.each(this.fields, function(field) {
            self._renderField(field, fieldElems[field.sfId]);
        });
    },

    /**
     * Sets field's view element and invokes render on the given field.
     *
     * @param {View/Field} field The field to render.
     * @param {jQuery} $fieldEl The field placeholder.
     * @protected
     * @memberOf View/View
     * @instance
     */
    _renderField: function(field, $fieldEl) {
        field.setElement($fieldEl || this.$("span[sfuuid='" + field.sfId + "']"));
        try {
            field.render();
        } catch (e) {
            SUGAR.App.logger.error('Failed to render ' + field + ' on ' + this + '\n' + e);
            SUGAR.App.error.handleRenderError(this, '_renderField', field);
        }
    },

    /**
     * Renders a view onto the page.
     *
     * The method first renders this view by calling {@link View/View#_renderHtml}
     * and then for each field invokes {@link View/View#_renderField}.
     *
     * NOTE: Do not override this method, otherwise you will lose ACL check.
     * Consider overriding {@link View/View#_renderHtml} instead.
     *
     * @return {View/View} The instance of this view.
     * @protected
     * @memberOf View/View
     * @instance
     */
    _render: function() {
        if (Acl.hasAccessToModel(this.action, this.model)) {
            this._disposeFields();
            this._renderHtml();
            this._renderFields();

        } else {

            SUGAR.App.logger.info('Current user does not have access to this module view. name: ' + this.name +
                ' module: ' + this.module);
            // See Bug56941.
            // We suppress this warning from being presented to user in situations where we're trying
            // to display a view for a Linked module where the user does not have access.  If you clicked on
            // a Bug and you shouldn't get warnings about Notes, etc, if you didn't have access to those other modules.
            if(this.primary){
                SUGAR.App.error.handleRenderError(this, 'view_render_denied');
            }
        }

        return this;
    },

    _setLabels: function() {
        /**
         * Pluralized i18n-ed module name.
         * @type {string}
         * @memberOf View/View
         * @name modulePlural
         * @instance
         */
        this.modulePlural = Language.getAppListStrings("moduleList")[this.module] || this.module;

        /**
         * Singular i18n-ed module name.
         * @type {string}
         * @memberOf View/View
         * @name moduleSingular
         * @instance
         */
        this.moduleSingular = Language.getAppListStrings("moduleListSingular")[this.module] || this.modulePlural;
    },

    /**
     * Fetches data for view's model or collection.
     *
     * This method calls view's context {@link Core/Context#loadData} method.
     *
     * Override this method to provide custom fetch algorithm.
     * @param {Object} [options] Options that are passed to
     *   collection/model's fetch method.
     * @memberOf View/View
     * @instance
     */
    loadData: function(options) {
        if (Acl.hasAccess('read', this.module)) {
            this.context.loadData(options);
        }
    },

    /**
     * Extracts the field names from the metadata for directly related
     * views/panels.
     *
     * @param {string} [module] Module name. Defaults to the Context module.
     * @return {Array} List of fields used on this view.
     * @memberOf View/View
     * @instance
     */
    getFieldNames: function(module) {
        var fields = [];
        module = module || this.context.get('module');

        if (this.meta && this.meta.panels) {
            fields = _.reduce(_.map(this.meta.panels, function(panel) {
                var nestedFields = _.flatten(_.compact(_.pluck(panel.fields, "fields")));
                return _.pluck(panel.fields, 'name').concat(
                    _.pluck(nestedFields, 'name')).concat(
                    _.flatten(_.compact(_.pluck(panel.fields, 'related_fields'))));
            }), function(memo, field) {
                return memo.concat(field);
            }, []);
        }

        fields = _.compact(_.uniq(fields));

        var fieldMetadata = SUGAR.App.metadata.getModule(module, 'fields');
        if (fieldMetadata) {
            // Filter out all fields that are not actual bean fields
            fields = _.reject(fields, function(name) {
                return _.isUndefined(fieldMetadata[name]);
            });

            // we need to find the relates and add the actual id fields
            var relates = [];
            _.each(fields, function(name) {
                if (fieldMetadata[name].type == 'relate') {
                    relates.push(fieldMetadata[name].id_name);
                }
                else if (fieldMetadata[name].type == 'parent') {
                    relates.push(fieldMetadata[name].id_name);
                    relates.push(fieldMetadata[name].type_name);
                }
                if (_.isArray(fieldMetadata[name].fields)) {
                    relates = relates.concat(fieldMetadata[name].fields);
                }
            });

            fields = _.union(fields, relates);
        }

        return fields;
    },


    /**
     * Gets a hash of fields that are currently displayed on this view.
     *
     * The hash has field names as keys and field definitions as values.
     * @param {string} [module] Module name.
     * @param {Data/Bean} [model] Model to match fields against. Only
     *   fields that correspond with the given model will be returned.
     * @return {Object} The currently displayed fields.
     * @memberOf View/View
     * @instance
     */
    getFields: function(module, model) {
        var fields = {};
        var fieldNames = this.getFieldNames(module);
        _.each(fieldNames, function(name) {
            var field = this.getField(name, model);
            if (field) {
                fields[name] = field.def;
            }
        }, this);
        return fields;
    },

    /**
     * Returns a field by name.
     *
     * @param {string} name Field name.
     * @param {Data/Bean} [model] Model to find the field for.
     * @return {View/Field} Instance of the field widget.
     * @memberOf View/View
     * @instance
     */
    getField: function(name, model) {
        return _.find(_.extend({}, this.fields, this.nestedFields), function(field) {
            return field.name == name && (!model || field.model == model);
        });
    },

    /**
     * @inheritdoc
     * @memberOf View/View
     */
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
     * @memberOf View/View
     */
    _show: function() {
        Component.prototype._show.call(this);
        _.each(_.extend({}, this.fields, this.nestedFields), function(component) {
            component.updateVisibleState(true);
        });
    },

    /**
     * @inheritdoc
     * @memberOf View/View
     */
    _hide: function() {
        Component.prototype._hide.call(this);
        _.each(_.extend({}, this.fields, this.nestedFields), function(component) {
            component.updateVisibleState(true);
        });
    },

    /**
     * Disposes a view.
     *
     * This method disposes view fields and calls the
     * {@link View/Component#_dispose} method of the base class.
     * @protected
     * @memberOf View/View
     * @instance
     */
    _dispose: function() {
        PluginManager.detach(this, 'view');
        this._disposeFields();
        Component.prototype._dispose.call(this);
    },

    /**
     * Disposes all the fields.
     *
     * @protected
     * @memberOf View/View
     * @instance
     */
    _disposeFields: function() {
        _.each(this.fields, function(field) {
            field.dispose();
        });
        this.fields = {};
        this.nestedFields = {};
    },

    /**
     * Gets a string representation of this view.
     *
     * @return {string} String representation of this view.
     * @memberOf View/View
     * @instance
     */
    toString: function() {
        return 'view-' + this.name + '-' + Component.prototype.toString.call(this);
    },

    /**
     * Gets a field's metadata.
     *
     * @param {string} field Field name.
     * @param {boolean} [includeChild=false] If `true`, check if this is a
     *   child field.
     * @return {Object} Field metadata.
     * @memberOf View/View
     * @instance
     */
    getFieldMeta : function(field, includeChild) {
        var fields = _.flatten(_.pluck(this.meta.panels, "fields")),
            ret = _.find(fields, function(def) {
                return def.name === field;
            });

        if (!ret && includeChild) {
            ret = _.find(_.flatten(_.pluck(fields, "fields")), function(def) {
                return def && def.name === field;
            });

            if (ret) {
                ret._isChild = true;
            }
        }

        return ret;
    },

    /**
     * Sets a field's metadata.
     *
     * @param {string} field Field name.
     * @param {Object} meta Field metadata
     * @memberOf View/View
     * @instance
     */
    setFieldMeta : function(field, meta) {
        _.each(this.meta.panels, function(panel) {
            _.each(panel.fields, function(def, i) {
                if (def.name === field) {
                    panel.fields[i] = _.extend(def, meta);
                } else if (_.isArray(def.fields)) {
                    _.each(def.fields, function(childDef, j) {
                        if (childDef.name === field) {
                            def.fields[j] = _.extend(childDef, meta);
                        }
                    });
                }
            });
        });
    }
});

module.exports = View;

