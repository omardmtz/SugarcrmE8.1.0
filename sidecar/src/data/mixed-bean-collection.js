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

/**
 * Mixed collection class.
 *
 * Supports wrapping multiple related collections by link to allow aggregated
 * interactions across multiple bean relationships. Does not support direct
 * fetch at this time except in search mode.
 *
 * **Filtering and searching**
 *
 * The collection's {@link Data/BeanCollection#fetch} method supports filter and
 * search options. For example, to search across accounts, opportunities, and
 * contacts for favorite records that have the string `"Acme"` in their
 * searchable fields:
 * ```
 * const DataManager = require('data/data-manager');
 * var records = SUGAR.App.data.getMixedBeanCollection();
 * records.fetch({
 *     favorites: true,
 *     query: 'Acme',
 *     module_list: 'Accounts,Opportunities,Contacts'
 * });
 * ```
 *
 * @module Data/MixedBeanCollection
 * @class
 * @extends Data/BeanCollection
 */
module.exports = BeanCollection.extend({
    /**
     * Creates a bean collection for each one of the links passed in the options.
     * The mixed bean collection will keep those collection in sync with
     * the mixed bean collection.
     *
     * @param {Array} models The initial models of the mixed bean collection.
     * @param {Object} [options] A hash of options.
     * @param {Array} [options.links] The links related to the mixed bean
     *   collection. A link is a collection of a particular module linked to a
     *   Bean. It represents a 1-to-many or many-to-many relationship. If this
     *   argument is provided, the mixed bean collection will handle
     *   synchronization between its records and the ones in its link
     *   collections. This allows a bean to have a mixed bean collection as part
     *   of its attributes to handle modification on its 1-to-many and
     *   many-to-many relationships.
     * @param {Data/Bean} [options.parentBean] The parent bean. It is required
     *   in the case where the MixedBeanCollection is used for a collection
     *   field.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    initialize: function (models, options) {
        this._linkedCollections = {};

        options = options || {};
        if (options.links) {
            if (!options.parentBean) {
                SUGAR.App.logger.error('You cannot instantiate a mixed-bean-collection with linked collections ' +
                    'but without a parent bean.');
                return;

            }

            /**
             * The parent bean. It exists in the case where a
             * MixedBeanCollection is used for a collection field.
             *
             * @property {Data/Bean}
             * @private
             */
            this._parentBean = options.parentBean;
            /**
             * The name of the collection field for which the mixed bean
             * collection is used.
             *
             * @property {string}
             * @private
             */
            this._collectionField = options.collectionField;

            _.each(options.links, function(collection, link) {
                //Check if collection is a module name rather than an existing collection
                if (_.isString(collection)) {
                    this._linkedCollections[link] = SUGAR.App.data.createBeanCollection(collection);
                } else {
                    this._linkedCollections[link] = collection;
                }

                this.listenTo(this._linkedCollections[link], 'add', this._onLinkAdd);
                this.listenTo(this._linkedCollections[link], 'remove', this._onLinkRemove);
                this.listenTo(this._linkedCollections[link], 'reset', this._onLinkReset);
            }, this);

            delete options.links;
        }

        BeanCollection.prototype.initialize.call(this, models, options);
    },

    /**
     * Sets the `model` class to match the given model.
     *
     * @param {Data/Bean|Object} model The bean to be added to the collection.
     * @param {Object} [options] A hash of options.
     * @return {Data/Bean} The prepared bean.
     * @private
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    _prepareModel: function (model, options) {
        var module = model instanceof Bean ? model.module : model._module;
        this.model = SUGAR.App.data.getBeanClass(module);
        return BeanCollection.prototype._prepareModel.call(this, model, options);
    },

    /**
     * Adds a bean to this collection when it got added to a
     * linked collection.
     *
     * @param {Data/Bean} model The bean to add.
     * @param {Data/BeanCollection} collection The collection where the
     *   bean got added.
     * @param {Object} [options] A hash of options.
     * @private
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    _onLinkAdd: function(model, collection, options) {
        if (this._updating) {
            return;
        }

        BeanCollection.prototype.set.call(this, model, {merge: false, add: true, remove: false});
    },

    /**
     * Removes a bean from this collection when it gets removed
     * from a linked collection.
     *
     * @param {Data/Bean} model The bean to remove.
     * @param {Data/BeanCollection} collection The collection from which the
     *   bean got removed.
     * @param {Object} [options] A hash of options.
     * @private
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    _onLinkRemove: function (model, collection, options) {
        if (this._updating) {
            return;
        }

        BeanCollection.prototype.remove.call(this, model);
    },

    /**
     * Updates this collection when a linked collection gets reset.
     *
     * @param {Data/BeanCollection} collection The linked collection that
     *   got reset.
     * @private
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    _onLinkReset: function (collection) {
        let groupedBeans = _.groupBy(this.models, function (model) {
            return model instanceof Bean ? model.get('_link') : model._link;
        });

        let linkName = _.findKey(this._linkedCollections, val => val === collection);

        let removeOptions = {};
        let setOptions = {merge: false, add: true, remove: false};
        // If `_updating` is `true`, it means we called `reset` on this
        // collection, so we do not want to trigger `add` neither `remove`.
        if (this._updating) {
            _.extend(removeOptions, {silent: true});
            _.extend(setOptions, {silent: true});
        }

        BeanCollection.prototype.remove.call(this, groupedBeans[linkName], removeOptions);
        BeanCollection.prototype.set.call(this, collection.models, setOptions);
    },

    /**
     * Adds models to the matching linked collections.
     *
     * @param {Data/Bean[]|Object[]} models The beans to be added to the
     *   collection.
     * @param {Object} [options] A hash of options.
     * @return {Data/Bean[]} The beans added to the collection.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    set: function (models, options) {
        // We don't call the BeanCollection method because a mixed bean
        // collection does not need to track it's own deltas.
        models = Backbone.Collection.prototype.set.call(this, models, options);
        if (!this._parentBean) {
            return models;
        }

        this._updating = true;
        options = options || {};

        if (!_.isUndefined(models) && !_.isArray(models)) {
            models = [models];
        }

        _.each(models, function (model) {
            var link = model.get('_link');
            if (!link) {
                return;
            }

            if (this._linkedCollections[link]) {
                this._linkedCollections[link].add(model, options);
            }
        }, this);

        this._updating = false;

        return models;
    },

    /**
     * Removes models from the matching linked collections.
     *
     * @param {Data/Bean[]|Object[]} models The beans to be removed from the
     *   collection.
     * @param {Object} [options] A hash of options.
     * @return {Data/Bean[]} The beans removed from the collection.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    remove: function (models, options) {
        // We don't call the BeanCollection method because a mixed bean
        // collection does not need to track it's own deltas.
        models = Backbone.Collection.prototype.remove.call(this, models, options);

        if (!this._parentBean) {
            return models;
        }

        this._updating = true;
        options = options || {};

        if (!_.isUndefined(models) && !_.isArray(models)) {
            models = [models];
        }

        _.each(models, function (model) {
            var link = model.get('_link');

            if (link) {
                if (this._linkedCollections[link]) {
                    this._linkedCollections[link].remove(model, options);
                }
            } else {
                _.each(this._linkedCollections, function (collection) {
                    var modelModule = model instanceof Bean ? model.get('module') : model._module;
                    if (collection.module === modelModule) {
                        collection.remove(model, options);
                    }
                });
            }
        }, this);

        this._updating = false;

        return models;
    },

    /**
     * Resets all linked collections.
     *
     * @param {Data/Bean[]|Object[]} models The beans to reset the
     *   collection with.
     * @param {Object} [options] A hash of options.
     * @return {Data/Bean[]} The beans set to the collection.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    reset: function (models, options) {
        if (!this._parentBean) {
            // We don't call the BeanCollection method because a mixed bean
            // collection does not need to track it's own deltas.
            return Backbone.Collection.prototype.reset.call(this, models, options);
        }

        options = options ? _.clone(options) : {};
        if (!_.isUndefined(models) && !_.isArray(models)) {
            models = [models];
        }

        var sortedBeans = _.groupBy(models, function (model) {
            return model instanceof Bean ? model.get('_link') : model._link;
        });

        this._updating = true;
        _.each(this._linkedCollections, function(collection, link) {
            // When a collection field's collection is reset silently, we still
            // want the linked collections to trigger `reset` so `_onLinkReset`
            // is called and the records of this mixed bean collection reflects
            // the ones presents in the linked collection.
            collection.reset(sortedBeans[link], _.omit(options, 'silent'));
        }, this);
        this._updating = false;
        if (!options.silent) {
            this.trigger('reset', this, options);
        }

        return models;
    },

    /**
     * Gets changes made on the linked collections.
     *
     * @return {Object} The object representing the changes made on the
     * linked collections since the last sync.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    getDelta: function() {
        var result = {};
        // TODO: SC-6145 will add `add` and `delete` arrays.
        _.each(this._linkedCollections, (val, linkName) => {
            if (!val.hasDelta()) {
                return;
            }

            result[linkName] = val.getDelta();
        }, this);

        return result;
    },

    /**
     * Checks if at least one of the linked collections has a delta.
     *
     * @return {boolean} `true` if at least 1 linked collection has a delta.
     */
    hasDelta: function() {
        return _.some(this._linkedCollections, (coll) => {
            return coll.hasDelta();
        });
    },

    /**
     * Resets the delta object on each linked collection.
     *
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    resetDelta: function() {
        _.each(this._linkedCollections, (val, linkName) => {
            val.resetDelta();
        });
    },

    /**
     * Fetches records.
     *
     * This method performs global search across multiple modules.
     *
     * @param {Object} [options] Fetch options.
     * @param {string} [options.module_list] Comma-delimited list of
     *   modules to search across. The default is a list of all displayable
     *   modules.
     * @return {SUGAR.HttpRequest} The created fetch request.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    fetch: function(options) {
        options = options || {};
        // We set a list of all modules by default
        options.module_list = this.module_list = options.module_list || this.module_list || SUGAR.App.metadata.getModuleNames({filter: 'visible'});
        return BeanCollection.prototype.fetch.call(this, options);
    },

    /**
     * Groups models by module name.
     *
     * @return {Object} Sets of models. Key is module name, value is array of
     *   models.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    groupByModule: function() {
        return _.groupBy(this.models, function(model) {
            return model.module;
        });
    },

    /**
     * Returns string representation of this collection.
     *
     * The format is:
     * ```mcoll:[length]```
     *
     * @return {string} String representation of this collection.
     * @memberOf Data/MixedBeanCollection
     * @instance
     */
    toString: function() {
        return "mcoll:" + this.length;
    },

    /**
     * Paginates the collection. In the use case of a collection field, we
     * pass `collectionField` option in order to hit the collection API.
     *
     * @param {Object} [options] A hash of options.
     * @param {string[]} [options.storedFilter] An array of existing filters to
     *   be applied.
     * @param {string} [options.view] If you are paginating a collection field,
     *   passing the `view` option will make sure we read the field definition
     *   specified in the view defs. It is especially important in order to use
     *   the same `order_by` value that was used for the first fetch of the
     *   collection.
     * @return {SUGAR.HttpRequest} The created fetch request.
     */
    paginate: function(options) {
        options = options || {};
        if (this._parentBean) {
            _.extend(options,
                {
                    collectionField: this._collectionField,
                    beanId: this._parentBean.id,
                    module: this._parentBean.module,
                    offset: this.offset
                }
            );
        }

        return BeanCollection.prototype.paginate.call(this, options);
    },

    /**
     * In a use case of a collection field, we set the `offset` and
     * `next_offset` to an empty object. In other use cases, we call the parent.
     */
    resetPagination: function() {
        if (!this._parentBean) {
            return BeanCollection.prototype.resetPagination.call(this);
        }

        this.offset = this.next_offset = {};
    }
});
