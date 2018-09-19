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

//Pull the precompile header and footer from the node precompile implementation for handlebars
var _header = "(function() {\n  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};\n",
    _footer = '})();',
    _templates = {},
    _sources = {};

/**
 * Manages {@link http://handlebarsjs.com|Handlebars} templates.
 *
 * @module View/Template
 */

/**
 * @alias module:View/Template
 */
const TemplateManager = {
    /**
     * Loads templates from local storage and populates the `Handlebars.templates` collection.
     */
    init: function() {
        _templates = SUGAR.App.config.cacheMeta ? SUGAR.App.cache.get('templates') || {} : {};
        var src = '';
        _.each(_templates, function(t) {
            src += t;
        });

        try {
            eval(_header + src + _footer);
        }
        catch (e) {
            SUGAR.App.logger.error('Failed to eval templates retrieved from local storage:\n' + e);
            // TODO: Trigger app:error event
        }
    },

    /**
     * Used to register a template src without compiling the template yet.
     * The template will be compiled the first time it is used (requested
     * with `getView`/`getLayout`/`getField`).
     *
     * @param {Array} tpl The template as `[key, compiled]` pair.
     * @param {string} src The template content (.hbs file).
     * @param {boolean} [force=false] `true` to force recompilation on it.
     * @private
     */
    _add: function(tpl, src, force) {
        var key = tpl[0],
            loaded = this.get(key, false);
        if (loaded && !force) {
            return;
        }
        //If we have already loaded this template but with a different source, we need to mark it for recompilation
        if (loaded && force && _sources[key] != src) {
            _templates[key] = Handlebars.templates[key] = null;
        }
        _sources[key] = src;
    },

    /**
     * Caches a template and compiles it if necessary.
     *
     * @param {Array} tpl The first item is the template key. The second item
     *   is optional and is the precompiled template function.
     * @param {string} src Template source code.
     * @param {boolean} [force=false] Flag indicating if the template must
     *   be re-compiled.
     * @return {Function} The compiled template.
     * @private
     */
    _compile: function(tpl, src, force) {
        Handlebars.templates = Handlebars.templates || {};
        _templates[tpl[0]] = Handlebars.templates[tpl[0]] = (force || !tpl[1]) ? this.compile(tpl[0], src) : tpl[1];
        return _templates[tpl[0]];
    },

    /**
     * Compiles a template.
     *
     * This method caches the precompiled version of the template
     * and returns the compiled template. The template can be accessed
     * directly via `Handlebars.templates[key]`.
     *
     * @param {string} key Identifier of the template to be compiled.
     * @param {string} src The actual template source to be compiled.
     * @return {Function} The compiled template.
     */
    compile: function(key, src) {
        try {
            _templates[key] = "templates['" + key + "'] = template(" + Handlebars.precompile(src) + ");\n";
            eval(_header + _templates[key] + _footer); // jshint ignore:line
        } catch (e) {
            // Invalid templates will cause a JS error when they either pre-compile or compile.
            SUGAR.App.logger.error("Failed to compile or eval template " + key + ".\n" + e);
        }
        return this.get(key, false) || this.empty;
    },

    /**
     * Retrieves a compiled Handlebars template.
     *
     * @param {string} key Identifier of the template to be retrieved.
     * @param {boolean} [compile=true] Force the template to compile if we
     *   have uncompiled source.
     * @return {Function} The compiled template.
     */
    get: function(key, compile) {
        //Undefined should default to true for compiled (not passed means compile)
        compile = _.isUndefined(compile) || compile;
        if (compile && !Handlebars.templates[key] && _sources[key]) {
            this._compile([key], _sources[key]);
        }
        return Handlebars.templates ? Handlebars.templates[key] : null;
    },

    // Convenience private method
    _getView: function(name, module, compile) {
        var key = name + (module ? ('.' + module) : '');
        return [key, this.get(key, compile)];
    },

    /**
     * Gets the compiled template for a view.
     *
     * @param {string} name View name.
     * @param {string} [module] Module name.
     * @return {Function} The compiled template.
     */
    getView: function(name, module) {
        return this._getView(name, module, true)[1];
    },

    // Convenience private method
    _getField: function(type, view, module, fallbackTemplate, skipFallbacks, compile) {
       var foundTemplate,
           prefix = "f." + type + ".",
           key = prefix + (module ? (module + ".") : "") + view;

        module += ".";

       // get the module specific one first, then try the base one for this view
       foundTemplate = this.get(prefix + module + view, compile) || this.get(prefix + view, compile);
        //skipfallbacks indicates we should only check for the requested field,
       if (!foundTemplate && !skipFallbacks)
       {
           foundTemplate = this.get(prefix + module + fallbackTemplate, compile) || this.get(prefix + fallbackTemplate, compile);
           // If we got nothing for the requested fallback, use base as the last ditch fallback
           if (!foundTemplate) {
               foundTemplate = this.get('f.base.' + view, compile) || this.get('f.base.' + fallbackTemplate, compile);
           }
       }
       return [key, foundTemplate];
   },

    /**
     * Gets the compiled template for a field.
     *
     * @param {string} type Field type.
     * @param {string} view View name.
     * @param {string} module The module the field is from.
     * @param {boolean} [fallbackTemplate=true] Template name to fall back
     *   to if the template for `view` is not found.
     * @return {Function} The compiled template.
     */
    getField: function(type, view, module, fallbackTemplate) {
        return this._getField(type, view, module, fallbackTemplate, false, true)[1];
    },

    // Convenience private method
    _getLayout: function(name, moduleName, compile) {
        var key = 'l.' + (moduleName ? (moduleName + '.') : '') + name;
        return [key, this.get(key, compile)];
    },

    /**
     * Gets the compiled template for a layout.
     *
     * @param {string} [name] Layout name.
     * @param {string} [moduleName] Module name.
     * @return {Function} The compiled template.
     */
    getLayout: function(name, moduleName) {
        return this._getLayout(name, moduleName, true)[1];
    },

    /**
     * Compiles a view template and puts it into local storage.
     *
     * @param {string} name View name.
     * @param {string} module Module name.
     * @param {string} src Template source code.
     * @param {boolean} [force=false] Flag indicating if the template must
     *   be re-compiled.
     * @return {Function} The compiled template.
     */
    setView: function(name, module, src, force) {
        return this._add(this._getView(name, module, false), src, force);
    },

    /**
     * Compiles a field template and puts it into local storage.
     *
     * @param {string} type Field type.
     * @param {string} view View name.
     * @param {string} module The module the field is from.
     * @param {string} src Template source code.
     * @param {boolean} [force=false] Flag indicating if the template must
     *   be re-compiled.
     * @return {Function} The compiled template.
     */
    setField: function(type, view, module, src, force) {
        // Don't fall back to default template (false flag)
        return this._add(this._getField(type, view, module, null, true, false), src, force);
    },

    /**
     * Compiles a layout template and puts it into local storage.
     *
     * @param {string} name Layout name.
     * @param {string} [moduleName] Module Name.
     * @param {string} src Template source code.
     * @param {boolean} [force=false] Flag indicating if the template must
     *   be re-compiled.
     * @return {Function} The compiled template.
     */
    setLayout: function(name, moduleName, src, force) {
        return this._add(this._getLayout(name, moduleName, false), src, force);
    },

    /**
     * Registers view, layout, and field templates from metadata payload
     * for later "lazy" on-demand compilation.
     *
     * The metadata must contain the following sections:
     *
     * ```
     * {
     *    // This should now be deprecated
     *    "view_templates": {
     *       "detail": HB template source,
     *       "list": HB template source,
     *       // etc.
     *    },
     *
     *    "sugarFields": {
     *        "text": {
     *            "templates": {
     *               "default": HB template source,
     *               "detail": HB template source,
     *               "edit": ...,
     *               "list": ...
     *            }
     *        },
     *        "bool": {
     *           // templates for boolean field
     *        },
     *        // etc.
     *    }
     *
     *    "views": {
     *      "text": {
     *          "templates" {
     *              "view": HB template source...
     *              "view2": HB template source..
     *          }.
     *    }
     * }
     * ```
     *
     * @param {Object} metadata Metadata payload.
     * @param {boolean} [force=false] Flag indicating if the cache is
     *   ignored and the templates are to be recompiled.
     */
    set: function(metadata, force) {
        if (metadata.views) {
            _.each(metadata.views, function(view, name) {
                if (name != '_hash') {
                    _.each(view.templates, function(src, key) {
                        key = name == key ? key : name + '.' + key;
                        this.setView(key, null, src, force);
                    }, this);
                }
            }, this);
        }

        if (metadata.fields) {
            _.each(metadata.fields, function(field, type) {
                if (type != '_hash') {
                    _.each(field.templates, function(src, view) {
                        this.setField(type, view, null, src, force);
                    }, this);
                }
            }, this);
        }

        if (metadata.layouts) {
            _.each(metadata.layouts, function(layout, type) {
                if (type != '_hash') {
                    _.each(layout.templates, function(src, key) {
                        key = type == key ? key : type + '.' + key;
                        this.setLayout(key, null, src, force);
                    }, this);
                }
            }, this);
        }

    },

    /**
     * A precompiled empty template function.
     *
     * @return {string} The empty string.
     */
    empty: function() {
        return '';
    }
};

module.exports = TemplateManager;
