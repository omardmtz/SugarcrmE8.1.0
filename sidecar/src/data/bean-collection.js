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
const PluginManager = require('core/plugin-manager');

/**
 * Base bean collection class. It extends `Backbone.Collection`.
 *
 * A bean collection is a collection of beans. To instantiate a bean collection,
 * you need to use {@link Data/DataManager#createBeanCollection}.
 *
 * Example of usage:
 *
 * The following snippet will create a collection of bean which belongs to the
 * module 'Accounts':
 *
 * ```
 * const DataManager = require('data/data-manager');
 * let accounts = DataManager.createBeanCollection('Accounts');
 * accounts.add({name: 'account1', industry: 'Banking'});
 * ```
 *
 * **Filtering and searching**
 *
 * The collection's {@link Data/BeanCollection#fetch} method supports filter and
 * search options. For example, to search favorite accounts that have the string
 * `"Acme"` in their name:
 * ```
 * const DataManager = require('data/data-manager');
 * let accounts = DataManager.createBeanCollection('Accounts');
 * accounts.fetch({
 *     favorites: true,
 *     query: "Acme"
 * });
 * ```
 *
 * @module Data/BeanCollection
 * @class
 */
const BeanCollection = Backbone.Collection.extend({
    /**
     * The request object that is currently syncing against the server.
     *
     * This object is needed to determine if a fetch request should be
     * aborted for the collection (e.g. if a new fetch request returns a
     * response prior to a previous fetch request).
     *
     * @private
     * @type {SUGAR.HttpRequest}
     * @memberOf Data/BeanCollection
     */
    _activeFetchRequest: null,

    /**
     * The default model of a bean collection is a {@link Data/Bean}.
     *
     * @readonly
     * @type {Data/Bean}
     * @memberOf Data/BeanCollection
     * @instance
     */
    model: Bean,

    /**
     * Prepares related bean collections and attach collection plugins.
     *
     * @param {Object[]|Data/Bean[]} models Initial array of models.
     * @param {Object} [options] A hash of options.
     * @param {Object} [options.link] A link specification.
     * @memberOf Data/BeanCollection
     * @instance
     */
    constructor: function(models, options) {
        PluginManager.attach(this, 'collection');
        if (options && options.link) {
            /**
             * Reference to a relationship.
             *
             * @type {Object}
             * @memberOf Data/BeanCollection
             * @instance
             * @name link
             */
            this.link = options.link;
            delete options.link;
        }
        Backbone.Collection.prototype.constructor.call(this, models, options);
    },

    /**
     * Sets the given options persistently on the bean collection. They will be
     * used by {@link Data/BeanCollection#fetch}.
     *
     * @param {Object[]|Data/Bean[]} models Initial array of models.
     * @param {Object} options Backbone collection options.
     * @memberOf Data/BeanCollection
     * @instance
     */
    initialize: function(models, options) {
        /**
         * List of persistent fetch options.
         *
         * @type {Object}
         * @private
         */
        this._persistentOptions = {};

        /**
         * Object keeping track of new models added to the collection
         * that are to be created and linked to their parent bean.
         * It contains an array of attribute hashes.
         *
         * @type {Array}
         */
        this._create = [];

        /**
         * Object keeping track of removed models from the collection that
         * are to be unlinked from their parent bean. It contains an array
         * of ids.
         *
         * @type {Array}
         */
        this._delete = [];

        /**
         * Object keeping track of added models to the collection that are
         * to be linked to their parent bean. It contains an array of ids.
         *
         * @type {Array}
         */
        this._add = [];

        this.setOption(options);
        this._bindCollectionEvents();

        Backbone.Collection.prototype.initialize.call(this, models, options);

        /**
         * Readonly property for the total records in server.
         *
         * Use {@link Data/BeanCollection#fetchTotal} to get the current total.
         *
         * @type {number}
         * @readOnly
         * @memberOf Data/BeanCollection
         * @name total
         * @instance
         */
        this.total = null;
    },

    /**
     * Bind collection events to activeRequest.
     *
     * @private
     * @memberOf Data/BeanCollection
     * @instance
     */
    _bindCollectionEvents: function() {
        this.on('data:sync:complete', function() {
            var activeFetchRequest = this.getFetchRequest();
            if (activeFetchRequest) {
                this._activeFetchRequest = null;
            }
        }, this);

        this.on('remove', this._decrementTotal, this);
        if (!this.link) {
            return;
        }

        this.on('sync', this.resetDelta, this);
    },

    /**
     * Decrements the {@link Data/BeanCollection#total collection total}.
     *
     * @protected
     * @memberOf Data/BeanCollection
     * @instance
     */
    _decrementTotal: function() {
        if (this.total) {
            this.total--;
        }
    },

    /**
     * Keeps track of the added models.
     *
     * @param {Object|Object[]|Data/Bean|Data/Bean[]} models The models to add.
     * @param {Object} options A hash of options.
     * @return {Data/Bean|Data/Bean[]} The added models.
     * @memberOf Data/BeanCollection
     * @instance
     */
    add: function (models, options) {
        models = Backbone.Collection.prototype.add.call(this, models, options);

        if (_.isUndefined(models) || !this.link || (options && options.method)) {
            return models;
        }

        models = _.isArray(models) ? models : [models];

        _.each(models, (model) => {
            if (!model) {
                return;
            }

            if (model.isNew()) {
                this._create.push(model);
            } else {
                const toDelete = _.find(this._delete, model);
                if (toDelete) {
                    this._delete = _.without(this._delete, toDelete);
                } else {
                    this._add.push(model);
                }
            }
        }, this);

        return models.length === 1 ? models[0] : models;
    },

    /**
     * Keeps track of the removed models.
     *
     * @param {Object|Object[]|Data/Bean|Data/Bean[]} models The models to
     *   remove.
     * @param {Object} options A hash of options.
     * @return {Data/Bean|Data/Bean[]} The removed models.
     * @memberOf Data/BeanCollection
     * @instance
     */
    remove: function (models, options) {
        models = Backbone.Collection.prototype.remove.call(this, models, options);

        if (_.isUndefined(models) || !this.link) {
            return models;
        }

        models = _.isArray(models) ? models : [models];

        _.each(models, (model) => {
            if (!model) {
                return;
            }

            if (model.isNew()) {
                this._create = _.without(this._create, model);
            } else if (_.contains(this._add, model)) {
                this._add = _.without(this._add, model);
            } else {
                this._delete.push(model);
            }
        }, this);

        return models.length === 1 ? models[0] : models;
    },

    /**
     * Keeps track of the unsynced changes on this collection.
     *
     * @param {Object|Object[]|Data/Bean|Data/Bean[]} models The models to
     *   reset the collection with.
     * @param {Object} options A hash of options.
     * @return {Data/Bean|Data/Bean[]} The model(s) you have reset the
     *   collection with.
     * @memberOf Data/BeanCollection
     * @instance
     */
    reset: function(models, options) {
        this.resetDelta();

        return Backbone.Collection.prototype.reset.call(this, models, options);
    },

    /**
     * Gets a hash of unsynced changes operated on the collection.
     *
     * Adds the relationship fields for records to be linked.
     *
     * @return {Object} A hash representing the unsynced changes.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getDelta: function() {
        let delta = {};
        delta.create = _.invoke(this._create, 'toJSON');

        // Gets the link field name of the relationship between the parent bean
        // and the collection from this collection's module vardefs.
        let oppositeLink = SUGAR.App.data.getOppositeLink(this.link.bean.module, this.link.name);
        let relationshipFields = SUGAR.App.data.getRelationFields(this.module, oppositeLink);

        delta.add = _.map(this._add, (model) => {
            let objToAdd = {id: model.id};
            _.each(relationshipFields, (relationshipField) => {
                if (model.get(relationshipField)) {
                    objToAdd[relationshipField] = model.get(relationshipField);
                }
            });

            return objToAdd;
        });

        delta.delete = _.pluck(this._delete, 'id');

        return delta;
    },

    /**
     * Checks if there is anything in the deltas.
     *
     * @return {boolean} `true` if some records are to be created, linked
     * or unlinked to the bean.
     */
    hasDelta: function() {
        return !_.all([this._add, this._delete, this._create], _.isEmpty);
    },

    /**
     * Resets the delta object representing the unsaved collection changes.
     *
     * @memberOf Data/BeanCollection
     * @instance
     */
    resetDelta: function() {
        this._create = [];
        this._add = [];
        this._delete = [];
    },

    /**
     * Disposes this collection.
     *
     * @memberOf Data/BeanCollection
     * @instance
     */
    dispose: function() {
        PluginManager.detach(this, 'collection');
    },

    /**
     * Prepares the model to be added to the collection.
     *
     * @param {Data/Bean} model Model to be added to the collection.
     * @param {Object} [options] A hash of options.
     * @return {Data/Bean} prepared model.
     * @private
     * @memberOf Data/BeanCollection
     * @instance
     */
    _prepareModel: function(model, options) {
        var searchInfo = model._search;
        delete model._search;

        model = Backbone.Collection.prototype._prepareModel.call(this, model, options);
        if (model && !model.link) model.link = this.link;
        if (searchInfo) {
            /**
             * FTS search results.
             *
             * Example:
             * ```
             * {
             *     highlighted: {
             *         account_name: {
             *             label: 'LBL_ACCOUNT_NAME',
             *             module: "Leads",
             *             text: 'Kings Royalty &lt;span class="highlight"&gt;Trust&lt;/span&gt;'
             *         }
             *     },
             *     score: 1
             * }
             * ```
             *
             * @memberOf Data/Bean
             * @type {Object}
             * @name searchInfo
             * @instance
             */
            model.searchInfo = searchInfo;
        }
        return model;
    },

    /**
     * Fetches beans. See {@link Data/BeanCollection#paginate} for details
     * about pagination options.
     *
     * Only one fetch request can be executed at a time - previous fetch
     * requests will be aborted.
     *
     * @param {Object} [options] Fetch options.
     * @param {boolean} [options.relate] `true` if relationships should be
     *   fetched. `false` otherwise.
     * @param {boolean} [options.myItems] `true` if only records assigned to
     *   the current user should be fetched. `false` otherwise.
     * @param {boolean} [options.favorites] `true` if favorited records should
     *   be fetched. `false` otherwise.
     * @param {boolean} [options.add] `true` if new records should be appended
     *   to the collection. `false` otherwise.
     * @param {string} [options.query] Search query string.
     * @param {Function} [options.success] The success callback to execute.
     * @param {Function} [options.error] The error callback to execute.
     * @return {SUGAR.HttpRequest} The created fetch request.
     * @memberOf Data/BeanCollection
     * @instance
     */
    fetch: function(options) {
        options = _.extend({}, this.getOption(), options);

        this.abortFetchRequest();
        /**
         * Field names.
         *
         * A list of fields that are populated on collection members.
         * This property is used to build the `fields` URL parameter when
         * fetching beans.
         *
         * @memberOf Data/BeanCollection
         * @name fields
         * @type {Array}
         * @instance
         */
        options.fields = this.fields = options.fields || this.fields || null;
        options.myItems = _.isUndefined(options.myItems) ? this.myItems : options.myItems;
        options.favorites = _.isUndefined(options.favorites) ? this.favorites : options.favorites;
        options.query = _.isUndefined(options.query) ? this.query : options.query;

        // FIXME SC-5596: This option is temporary, and was added as part of
        // SC-2703. It will be removed when we update our sidecar components
        // to listen on `update` instead of reset.
        options.reset = _.isUndefined(options.reset) ? true : options.reset;

        this._activeFetchRequest = Backbone.Collection.prototype.fetch.call(this, options);
        return this.getFetchRequest();
    },

    /**
     * Gets the currently active fetch request.
     *
     * @return {SUGAR.HttpRequest} The currently active http fetch request.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getFetchRequest: function () {
        if (_.isFunction(this._activeFetchRequest)) {
            return this._activeFetchRequest();
        } else {
            return this._activeFetchRequest;
        }
    },

    /**
     * Aborts the currently active fetch request.
     *
     * @memberOf Data/BeanCollection
     * @instance
     */
    abortFetchRequest: function () {
        var activeFetchRequest = this.getFetchRequest();
        if (activeFetchRequest) {
            SUGAR.App.api.abortRequest(activeFetchRequest.uid);
            this._activeFetchRequest = null;
        }
    },

    /**
     * Resets pagination properties on this collection to initial values.
     *
     * @memberOf Data/BeanCollection
     * @instance
     */
    resetPagination: function() {
        this.offset = this.getOption('offset') || 0;
        this.next_offset = 0;
        this.page = this.getOption('page') || 1;
    },

    /**
     * Paginates a collection. This methods calls
     * {@link Data/BeanCollection#fetch}, hence it
     * supports the same options as well as the one described below.
     *
     * @param {Object} [options] Fetch options.
     * @param {number} [options.page=1] Page index from the current page to
     *   paginate to.
     * @memberOf Data/BeanCollection
     * @instance
     */
    paginate: function(options) {
        options = options || {};
        var maxSize = options.limit || SUGAR.App.config.maxQueryResult;
        options.page = options.page || 1;

        // Since we're paginating, we want the Collection.set method to be
        // invoked instead of `reset`.
        options.reset = _.isUndefined(options.reset) ? false : options.reset;

        // fix page number since our offset is already at the end of the collection subset
        options.page--;

        if (maxSize && _.isNumber(this.offset)) {
            options.offset = this.offset + (options.page * maxSize);
        }

        if (options.add){
            options = _.extend({update:true, remove:false}, options);
        }

        this.fetch(options);
    },

    /**
     * Fetches the total amount of records on the bean collection, and sets
     * it on the {@link Data/BeanCollection#total} property.
     *
     * Returns the total amount of filtered records if a `filterDef`
     * property is set on the collection.
     *
     * @param {Object} [options] Fetch total options.
     * @param {Function} [options.success] Success callback.
     * @param {Function} [options.complete] Complete callback.
     * @param {Function} [options.error] Error callback.
     * @return {SUGAR.HttpRequest|undefined} Result of {@link SUGAR.Api#call},
     *   or `undefined` if {@link Data/BeanCollection#total} is not `null`.
     * @memberOf Data/BeanCollection
     * @instance
     */
    fetchTotal: function(options) {
        options = options || {};

        if (!_.isNull(this.total) && _.isFunction(options.success)) {
            options.success.call(this, this.total);
            return;
        }

        options.success = _.wrap(options.success, _.bind(function(orig, data) {
            this.total = parseInt(data.record_count, 10);
            if (orig && _.isFunction(orig)) {
                orig(this.total);
            }
        }, this));

        var module = this.module;
        var data = null;
        options.filter = options.filterDef || this.filterDef;

        if (this.link) {
            data = {
                id: this.link.bean.id,
                link: this.link.name
            };
            module = this.link.bean.module;
        }

        var callbacks = _.pick(options, 'success', 'complete', 'error');
        options = _.omit(options, 'success', 'complete', 'error');

        return SUGAR.App.api.count(module, data, callbacks, options);
    },

    /**
     * A convenience method that checks to see if there are at least the
     * amount of records passed in `amount`. Also passes to a provided
     * success callback the length of records up to `amount`, and if there
     * are more records to be fetched (`hasMore`).
     *
     * Fetches the partial amount of filtered records if a `filterDef`
     * property is set on the collection.
     *
     * @param {number} amount The number of records to check if there are a
     *   minimum of.
     * @param {Object} [options] Fetch partial total options.
     * @param {Object} [options.filterDef] Filter definition to be applied.
     * @param {Function} [options.success] Success callback.
     * @param {Function} [options.complete] Complete callback.
     * @param {Function} [options.error] Error callback.
     * @return {SUGAR.HttpRequest} Result of {@link SUGAR.Api#call}.
     * @memberOf Data/BeanCollection
     * @instance
     */
    hasAtLeast: function(amount, options) {
        options = options || {};
        var method = 'read';
        options.fields = ['id'];
        delete options.view;
        options.silent = true;
        options.success = _.wrap(options.success, _.bind(function(orig, data) {
            var length = data.records.length;
            var hasAtLeastAmount = length >= amount;
            var properties = {
                length: length,
                hasMore: data.next_offset !== -1
            };

            if (_.isFunction(orig)) {
                orig(hasAtLeastAmount, properties);
            }

            this.reset(null, {silent: true});
        }, this));

        var module = this.module;
        var data = null;
        var endpoint = 'records';
        options.filter = options.filterDef || this.filterDef;
        options.limit = options.limit || amount;

        if (this.link) {
            data = {
                id: this.link.bean.id,
                link: this.link.name
            };
            module = this.link.bean.module;
            endpoint = 'relationships';
        }

        var callbacks = _.pick(options, 'success', 'complete', 'error');
        options = _.omit(options, 'success', 'complete', 'error');
        options = SUGAR.App.data.parseOptionsForSync(method, this, options);

        return SUGAR.App.api[endpoint](method, module, data, options.params, callbacks, options.apiOptions);
    },

    /**
     * Gets the current page of collection being displayed depending on the
     * offset.
     *
     * @param {Object} [options] Fetch options used when paginating.
     * @param {number} [options.limit=App.config.maxQueryResult] The size of
     *   each page.
     * @return {number} The current page number.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getPageNumber: function(options) {
        var pageNumber = 1;
        var maxSize = SUGAR.App.config.maxQueryResult;
        if(options){
            maxSize = options.limit || maxSize;
        }
        if (this.offset && maxSize) {
            pageNumber = Math.ceil(this.offset / maxSize);
        }
        return pageNumber;
    },

    /**
     * Returns string representation useful for debugging.
     *
     * Format:
     * <code>coll:[module-name]-[length]</code>  or
     * <code>coll:[related-module-name]/[id]/[module-name]-[length]</code>
     * if it's a collection of related beans.
     *
     * @return {string} The string representation of this collection.
     * @memberOf Data/BeanCollection
     * @instance
     */
    toString: function() {
        return "coll:" + (this.link ?
            (this.link.bean.module + "/" + this.link.bean.id + "/") : "") +
            this.module + "-" + this.length;
    },

    /**
     * Returns the next model in a collection, paginating if needed.
     *
     * @param {Object} current Current model or id of a model.
     * @param {Object} callback Callback for success call.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getNext: function(current, callback) {
        var ind = -1;
        var nextFn = () => { callback.apply(this, [this.at(ind + 1), 'next']); };

        if (this.hasNextModel(current)) {
            ind = this.getModelIndex(current);
            if (ind + 1 >= this.length) {
                this.paginate({
                    add: true,
                    success: nextFn,
                });
            } else {
                nextFn();
            }
        }
    },

    /**
     * Finds the previous model in a collection and calls a function on it.
     *
     * @param {Object} current Current model or id of a model.
     * @param {Function} callback Callback for success call.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getPrev: function (current, callback) {
        var ind = -1,
            result = null,
            self = this;
        if (this.hasPreviousModel(current)) {
            ind = this.getModelIndex(current);
            result = this.at(ind - 1);
        }
        callback.apply(self, [result, 'prev']);
    },

    /**
     * Checks whether is there next model in collection.
     *
     * @param {Object} current Current model or id of a model.
     * @return {boolean} `true` if has next model, `false` otherwise.
     * @memberOf Data/BeanCollection
     * @instance
     */
    hasNextModel: function(current) {
        var index = this.getModelIndex(current),
            offset = !_.isUndefined(this.next_offset) ? parseInt(this.next_offset, 10) : -1;
        return index >= 0 && ((this.length > index + 1 ) || offset !== -1);
    },

    /**
     * Checks whether is there previous model in this collection.
     *
     * @param {Object} current Current model or id of a model.
     * @return {boolean} `true` if has previous model, `false` otherwise.
     * @memberOf Data/BeanCollection
     * @instance
     */
    hasPreviousModel: function (current) {
        return this.getModelIndex(current) > 0;
    },

    /**
     * Returns the index of the model in this collection.
     *
     * @param {Object} model Current model.
     * @return {number} The index of the passed `model` in this array.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getModelIndex: function (model) {
        return this.indexOf(this.get(model.id));
    },

    /**
     * Sets the default fetch options (one or many) on the model.
     *
     * @param {string|Object} key The name of the attribute, or a hash of
     *   attributes.
     * @param {*} [val] The default value for the `key` argument.
     * @return {Data/BeanCollection} This instance.
     * @memberOf Data/BeanCollection
     * @instance
     */
    setOption: function(key, val) {
        var attrs;
        if (_.isObject(key)) {
            attrs = key;
        } else {
            (attrs = {})[key] = val;
        }

        _.extend(this._persistentOptions, attrs);
        return this;
    },

    /**
     * Unsets a default fetch option (or all).
     *
     * @param {string|Object} [key] The name of the option to unset, or
     *   nothing to unset all options.
     * @return {Data/BeanCollection} This instance.
     * @memberOf Data/BeanCollection
     * @instance
     */
    unsetOption: function(key) {
        if (key) {
            this.setOption(key, void 0);
        } else {
            this._persistentOptions = {};
        }

        return this;
    },

    /**
     * Gets one or all persistent fetch options.
     *
     * @param {string|Object} [key] The name of the option to retrieve, or
     *   nothing to retrieve all options.
     * @return {*} A specific option, or the list of options.
     * @memberOf Data/BeanCollection
     * @instance
     */
    getOption: function(key) {
        if (key) {
            return this._persistentOptions[key];
        }
        return this._persistentOptions;
    },

    /**
     * Clones the collection including the {@link Data/BeanCollection#link} and
     * all the persistent options.
     *
     * @return {Data/BeanCollection} The new collection with an identical
     *   list of models as this one.
     * @memberOf Data/BeanCollection
     * @instance
     */
    clone: function() {
        var newCol = Backbone.Collection.prototype.clone.call(this);
        newCol.link = _.clone(this.link);
        newCol.setOption(_.clone(this.getOption()));
        return newCol;
    }
});

module.exports = BeanCollection;
