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

const Bean = require('data/bean');
const BeanCollection = require('data/bean-collection');
const DataManager = require('data/data-manager');

/**
 * Helper function to determine if {@link Core/Context#loadData} can be called
 * on this context.
 *
 * @return {boolean} `true` if {@link Core/Context#loadData} can be called.
 *   `false` otherwise.
 * @private
 */
function shouldFetch() {
    return (this.get('fetch') === void 0 || this.get('fetch')) &&
        !this.isDataFetched() && !this.get('create');
}

/**
 * The Context object is a state variable to hold the current application state.
 * It extends `Backbone.Model`.
 *
 * The context contains various states of the current {@link View/View View} or
 * {@link View/Layout Layout} -- this includes the current model and collection,
 * as well as the current module focused and also possibly the url hash that was
 * matched.
 *
 * ### Instantiate a context.
 *
 * ```
 * const Context = require('core/context');
 * let myContext = new Context();
 * ```
 *
 * ### Creating a child context or retrieving an existing one.
 * Assuming `myContext` is a context instance.
 *
 * ```
 * let childContext = myContext.getChildContext({cid: <contextId>});
 * ```
 *
 * If <contextId> does not match any child context, a new child context will be
 * created.
 *
 * ### Retrieving Data from the Context
 * A context is a Backbone Model, so its data is stored in the attributes:
 *
 * ```
 * var module = myContext.get('module'); // module = "Contacts"
 * ```
 *
 * @module Core/Context
 * @class
 */
const Context = Backbone.Model.extend({
    /**
     * Calls Backbone's `initialize` and initializes this context's properties.
     *
     * @param {Object} [attributes] The initial hash of attributes.
     * @memberOf Core/Context
     * @instance
     */
    initialize: function(attributes) {
        Backbone.Model.prototype.initialize.call(this, attributes);
        this.id = this.cid;
        this.parent = null;
        this.children = [];
        this._fetchCalled = false;
    },

    /**
     * Recursively clears this context and its children.
     *
     * Unbinds the event listeners, clears the attributes, aborts the request
     * in progress if any, and resets the load flag (calls
     * {@link Core/Context#resetLoadFlag}).
     *
     * @param {Object} [options] Standard `Backbone.Model` options.
     * @memberOf Core/Context
     * @instance
     */
    clear: function(options) {
        var collection = this.get('collection');

        if (collection) {
            collection.abortFetchRequest();
        }

        _.each(this.children, function(child) {
            child.clear(options);
        });

        this.children = [];
        this.parent = null;

        // Remove event listeners attached to models and collections in the
        // context before clearing them.
        _.each(this.attributes, function(value) {
            if (value && (value.off === Backbone.Events.off)) {
                value.off();
                value.stopListening();
                if (_.isFunction(value.dispose)) {
                    value.dispose();
                }
            }
        }, this);

        this.off();
        Backbone.Model.prototype.clear.call(this, options);

        this.resetLoadFlag();
    },

    /**
     * Resets `load-data` state for this context and its child contexts.
     *
     * The {@link Core/Context#loadData} method sets an internal boolean flag
     * to prevent multiple identical requests to the server.
     * This method resets this flag.
     *
     * @param {Object} [options] A hash of options.
     * @param {boolean} [options.recursive = true] `true` to reset the child
     * contexts too.
     * @param {boolean} [options.resetModel = true] `true` to reset the flag on
     * the model.
     * @param {boolean} [options.resetCollection = true] `true` to reset the
     * flag on the collection.
     * @memberOf Core/Context
     * @instance
     */
    resetLoadFlag: function (options) {
        options = options || {};
        var recursive = _.isUndefined(options.recursive) ? true : options.recursive;
        var resetModel = _.isUndefined(options.resetModel) ? true : options.resetModel;
        var resetCollection = _.isUndefined(options.resetCollection) ? true : options.resetCollection;

        this._fetchCalled = false;

        if (this.get('model') && resetModel) {
            this.get('model').dataFetched = false;
        }

        if (this.get('collection') && resetCollection) {
            this.get('collection').dataFetched = false;
        }

        if (recursive) {
            _.each(this.children, function(child) {
                child.resetLoadFlag();
            });
        }
    },

    /**
     * Checks if this context is used for a create view.
     *
     * @return {boolean} `true` if this context has the `create` flag set.
     * @memberOf Core/Context
     * @instance
     */
    isCreate: function() {
        return this.get("create") === true;
    },

    /**
     * Gets an existing related context or creates a new one.
     *
     * @param {Object} [def] Child context definition.
     * @param {string} [def.cid] The id of the context to retrieve. This takes
     *   precedence over the passed name, the link and the module.
     * @param {string} [def.name] The name of the context to retrieve. This
     *   takes precedence over passed the link and the module.
     * @param {string} [def.link] The link name to retrieve a context from. This
     *   takes precedence over the passed module name.
     * @param {string} [def.module] The module name to retrieve a context from.
     *   The first child context matching the module attribute will be returned.
     * @param {boolean} [def.forceNew] `true` to force the creation a new
     *   context, without trying to retrieve an existing one.
     * @return {Core/Context} The child context.
     * @memberOf Core/Context
     * @instance
     */
    getChildContext: function(def) {
        def = def || {};
        var context;
        var force = def.forceNew || false;

        delete def.forceNew;

        // Re-use a child context if it already exists
        // We search by either link name or module name
        // Consider refactoring the way we store children: hash v.s. array
        var name = def.cid || def.name || def.link || def.module;
        if (name && !force) {
            context = _.find(this.children, function(child) {
                return ((child.cid == name) || (child.get("link") == name) || (child.get("module") == name));
            });
        }

        if (!context) {
            def = _.extend({ fetch: this.get('fetch') }, def);
            context = new Context(def);
            this.children.push(context);
            context.parent = this;
        }

        if (def.link) {
            var parentModel = this.get("model");
            context.set({
                parentModel: parentModel,
                parentModule: parentModel ? parentModel.module : null
            });
        } else if(!def.module){
            context.set({module:this.get("module")});
        }

        this.trigger("context:child:add", context);

        return context;
    },

    /**
     * Prepares instances of model and collection and sets them to this context.
     *
     * The method also create related contexts, based on the created bean fields:
     * It creates child context for each `link` of each `collection` field
     * present on the bean.
     * This method does nothing if this context already contains an instance of
     * a model or a collection.
     *
     * @param {boolean} [force] `true` to force the creation of the model and
     *   collection.
     * @param {boolean} prepareRelated `true` to always prepare related contexts.
     * @return {Core/Context} Returns this context instance.
     * @memberOf Core/Context
     * @instance
     */
    prepare: function (force, prepareRelated) {
        var link;
        if (force || (!this.get('model') && !this.get('collection'))) {
            var modelId = this.get('modelId');
            var create = this.get('create');
            link = this.get('link');

            this.set(link ?
                this._prepareRelated(link, modelId, create) :
                this._prepare(modelId, create)
            );
        }

        if ((force || !this._relatedCollectionsPopulated) && (!link || prepareRelated)) {
            this._populateRelatedContexts();
        }

        return this;
    },

    /**
     * Sets the `fetch` attribute recursively on the context and its children.
     *
     * A context with `fetch` set to `false` won't load the data.
     *
     * @param {boolean} fetch `true` to recursively set `fetch` to `true`
     *   in this context and its children.
     * @param {Object} [options] A hash of options.
     * @param {boolean} [options.recursive] `true` to recursively set the
     *   `fetch` boolean on the children.
     * @memberOf Core/Context
     * @instance
     */
    setFetch: function (fetch, options) {
        options = options || {};
        this.set('fetch', fetch);
        var recursive = options.recursive === void 0 ? true : options.recursive;
        if (recursive) {
            _.each(this.children, (child) => { child.setFetch(fetch); });
        }
    },

    /**
     * Prepares instances of model and collection.
     *
     * This method assumes that the module name (`module`) is set on the context.
     * If not, instances of standard `Backbone.Model` and `Backbone.Collection`
     * are created.
     *
     * @param {string} modelId Bean ID.
     * @param {boolean} create Create flag.
     * @return {Object} State to set on this context.
     * @private
     * @memberOf Core/Context
     * @instance
     */
    _prepare: function(modelId, create) {
        var model, collection,
            module = this.get("module"),
            mixed = this.get("mixed"),
            models;

        if (modelId) {
            model = DataManager.createBean(module, { id: modelId });
            models = [model];
        } else if (create === true) {
            model = DataManager.createBean(module);
            models = [model];
        } else {
            model = DataManager.createBean(module);
        }

        collection = mixed === true ?
            DataManager.createMixedBeanCollection(models) :
            DataManager.createBeanCollection(module, models);

        return {
            collection: collection,
            model: model
        };
    },

    /**
     * Prepares instances of related model and collection.
     *
     * This method assumes that either a parent model (`parentModel`) or
     * parent model ID (`parentModelId`) and parent model module name
     * (`parentModule`) are set on this context.
     *
     * @param {string} link Relationship link name.
     * @param {string} modelId Related bean ID.
     * @param {boolean} create Create flag.
     * @return {Object} State to set on this context.
     * @private
     * @memberOf Core/Context
     * @instance
     */
    _prepareRelated: function(link, modelId, create) {
        var model, collection,
            parentModel = this.get("parentModel");

        parentModel = parentModel || DataManager.createBean(this.get("parentModule"), { id: this.get("parentModelId") });
        if (modelId) {
            model = DataManager.createRelatedBean(parentModel, modelId, link);
            collection = DataManager.createRelatedCollection(parentModel, link, [model]);
        } else if (create === true) {
            model = DataManager.createRelatedBean(parentModel, null, link);
            collection = DataManager.createRelatedCollection(parentModel, link, [model]);
        } else {
            model = DataManager.createRelatedBean(parentModel, null, link);
            collection = DataManager.createRelatedCollection(parentModel, link);
        }

        if (!this.has("parentModule")) {
            this.set({ "parentModule": parentModel.module }, { silent: true });
        }

        if (!this.has("module")) {
            this.set({ "module": model.module }, { silent: true });
        }

        return {
            parentModel: parentModel,
            collection: collection,
            model: model
        };
    },

    /**
     * Sets the `fields` attribute on this context by extending the current
     * `fields` attribute with the passed-in `fieldsArray`.
     *
     * @param {string[]} fieldsArray The list of field names.
     * @return {Core/Context} Instance of this model.
     * @memberOf Core/Context
     * @instance
     */
    addFields: function(fieldsArray) {
       if (!fieldsArray) {
           return;
       }
       var fields = _.union(fieldsArray, this.get('fields') || []);
       return this.set('fields', fields);
    },

    /**
     * Loads data (calls fetch on either model or collection).
     *
     * This method sets an internal boolean flag to prevent consecutive fetch
     * operations. Call {@link Core/Context#resetLoadFlag} to reset the
     * context's state.
     *
     * @param {Object} [options] A hash of options passed to
     *   collection/model's fetch method.
     * @param {boolean} [options.fetch] `true` to always fetch the data.
     * @memberOf Core/Context
     * @instance
     */
    loadData: function(options) {
        options = options || {};
        if (!options.forceFetch && !shouldFetch.call(this)) {
            return;
        }

        delete options.forceFetch;

        var objectToFetch,
            modelId = this.get("modelId"),
            module = this.get("module"),
            defaultOrdering = (SUGAR.App.config.orderByDefaults && module) ? SUGAR.App.config.orderByDefaults[module] : null;

        objectToFetch = modelId ? this.get("model") : this.get("collection");

        // If we have an orderByDefaults in the config, and this is a bean collection,
        // try to use ordering from there (only if orderBy is not already set.)
        if (defaultOrdering &&
            objectToFetch instanceof BeanCollection &&
            !objectToFetch.orderBy)
        {
            objectToFetch.orderBy = defaultOrdering;
        }

        // TODO: Figure out what to do when models are not
        // instances of Bean or BeanCollection. No way to fetch.
        if (objectToFetch && (objectToFetch instanceof Bean ||
            objectToFetch instanceof BeanCollection)) {

            if (this.get('dataView') && _.isString(this.get('dataView'))) {
                objectToFetch.setOption('view', this.get('dataView'));
            }

            if (this.get('fields')) {
                objectToFetch.setOption('fields', this.get('fields'));
            }

            if (this.get('limit')) {
                objectToFetch.setOption('limit', this.get('limit'));
            }

            if (this.get('module_list')) {
                objectToFetch.setOption('module_list', this.get('module_list'));
            }

            // Track models that user is actively viewing
            if (this.get('viewed')) {
                objectToFetch.setOption('viewed', this.get('viewed'));
            }

            options.context = this;

            if (this.get("skipFetch") !== true) {
                objectToFetch.fetch(options);
            }

            this._fetchCalled = true;
        } else {
            SUGAR.App.logger.warn("Skipping fetch because model is not Bean, Bean Collection, or it is not defined, module: " + this.get("module"));
        }
    },

    /**
     * Creates child context for each `link` of each `collection` field
     * present on the bean.
     *
     * @private
     * @memberOf Core/Context
     * @instance
     */
    _populateRelatedContexts: function () {
        if (!this.get('collection')) {
            return;
        }

        this.get('collection').each(function(bean) {
            var collectionFields = bean.fieldsOfType('collection');
            if (!_.isEmpty(collectionFields)) {
                _.each(collectionFields, function(field) {
                    var links = field.links;
                    if (_.isString(links)) {
                        links = [links];
                    }

                    var linkCollections = {};
                    _.each(links, function(link) {
                        var rc = this.getChildContext({link: link});
                        rc.prepare();
                    }, this);
                }, this);
            }
        }, this);

        this._relatedCollectionsPopulated = true;
    },

    _shouldFetch: function() {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Context#_shouldFetch is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Context#_shouldFetch is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return shouldFetch.call(this);
    },

    /**
     * Refreshes the context's data and refetches the new data if
     * the `skipFetch` attribute is `true`.
     *
     * @param {Object} [options] Options for {@link Core/Context#loadData} and
     *   the `reload` event.
     * @fires reload
     * @memberOf Core/Context
     * @instance
     */
    reloadData: function(options) {
        options = options || {};

        this.resetLoadFlag(options);
        this.loadData(options);

        /**
         * Triggered before and after the context is reloaded.
         * @param {Core/Context} this The context instance where the event is
         *   triggered.
         * @param {Object} [options] The options passed during
         *   {@link Core/Context#reloadData} call.
         * @memberOf Core/Context
         * @event reload
         */
        this.trigger('reload', this, options);
    },

    /**
     * Checks if data has been successfully loaded.
     *
     * @return {boolean} `true` if data has been fetched, `false` otherwise.
     * @memberOf Core/Context
     * @instance
     */
    isDataFetched: function() {
        var objectToFetch = this.get('modelId') ? this.get('model') : this.get('collection');
        return this._fetchCalled || (objectToFetch && !!objectToFetch.dataFetched);
    },
});

module.exports = Context;
