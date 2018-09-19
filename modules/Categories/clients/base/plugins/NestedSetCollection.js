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
(function(app) {
    /**
     * Define a new collection and model type - NestedSetCollection and NestedSetBean that is used
     * as interface for manipulating tree data via tree API.
     */
    app.events.on('app:init', function() {

        /**
         *  Bean class that implement nested set.
         *
         * @class Data.NestedSetBean
         * @alias SUGAR.App.NestedSetBean
         * @extends Data.Bean
         */
        app.augment('NestedSetBean', app.Bean.extend({

            /**
             * Collection of children.
             * @property {Data.NestedSetCollection}
             */
            children: null,

            /**
             * @inheritdoc
             */
            initialize: function(attributes, options) {
                app.Bean.prototype.initialize.call(this, attributes);
                this.on('sync', function(model, data, options, request) {
                    if (!_.isUndefined(model.children.rootCollection)) {
                        app.events.trigger(
                            'app:nestedset:sync:complete',
                            model.children.rootCollection
                        );
                    }
                });
            },

            /**
             * Create callback object.
             * @param {Object} options
             * @return {Object} Callbacks.
             * @protected
             */
            _initCallback: function(options) {
                var callback = {};
                callback.success = options.success || null;
                callback.complete = options.complete || null;
                callback.error = options.error || null;
                return callback;
            },

            /**
             * @inheritdoc
             */
            set: function(data, options) {
                if (_.isObject(data) &&
                    !_.isEmpty(data.children) &&
                    _.isNull(this.children)
                ) {
                    this.children = this.collection.clone();
                    this.children.module = this.collection.module;

                    this.children.rootCollection =
                        this.collection.rootCollection ?
                            this.collection.rootCollection :
                            this.collection;

                    this.children.reset(data.children.records);
                    data = _.omit(data, 'children');
                }
                app.Bean.prototype.set.apply(this, arguments);

                this.module = data['_module'] || this.collection.module;
                if (!_.isUndefined(app.metadata.getModule(this.module))) {
                    this.fields = app.metadata.getModule(this.module).fields;
                }
                return this;
            },

            /**
             * Return children nodes for selected record.
             * @param {Object} options
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @return {Data.NestedSetCollection}
             * @todo: Impletent method to return NestedSetCollection
             */
            getChildren: function(options) {
                options = options || {};
                options.callback = this._initCallback(options);

                var url = app.api.buildURL(
                    this.module || this.collection.module,
                    [this.get('id'), 'children'].join('/')
                );

                app.api.call('read', url, {}, options.callback);
            },

            /**
             * Return next sibling of selected record.
             * @param {Object} options
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @return {Data.NestedSetBean}
             * @todo: Impletent method to return NestedSetBean
             */
            getNext: function(options) {
                options = options || {};
                options.callback = this._initCallback(options);

                var url = app.api.buildURL(
                    this.module || this.collection.module,
                    [this.get('id'), 'next'].join('/')
                );

                app.api.call('read', url, {}, options.callback);
            },

            /**
             * Return previous sibling of selected record.
             * @param {Object} options
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @return {Data.NestedSetBean}
             * @todo: Impletent method to return NestedSetBean
             */
            getPrev: function(options) {
                options = options || {};
                options.callback = this._initCallback(options);

                var url = app.api.buildURL(
                    this.module || this.collection.module,
                    [this.get('id'), 'prev'].join('/')
                );

                app.api.call('read', url, {}, options.callback);
            },

            /**
             * Return parent node of selected record.
             * @param {Object} options
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @return {Data.NestedSetBean}
             * @todo: Impletent method to return NestedSetBean
             */
            getParent: function(options) {
                options = options || {};
                options.callback = this._initCallback(options);

                var url = app.api.buildURL(
                    this.module || this.collection.module,
                    [this.get('id'), 'parent'].join('/')
                );

                app.api.call('read', url, {}, options.callback);
            },

            /**
             * Return full path of selected record.
             * @param {Object} options
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @return {Data.NestedSetCollection}
             * @todo: Impletent method to return NestedSetCollection
             */
            getPath: function(options) {
                options = options || {};
                options.callback = this._initCallback(options);

                var url = app.api.buildURL(
                    this.module || this.collection.module,
                    [this.get('id'), 'path'].join('/')
                );

                app.api.call('read', url, {}, options.callback);
            },

            /**
             * Move record as previous sibling of target.
             * @param {Object} options
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @see NestedSetCollection.moveBefore
             */
            moveBefore: function(options) {
                options = options || {};
                options.record = this.get('id');

                if (_.isUndefined(this.collection)) {
                    return;
                }

                this.collection.moveBefore(options);
            },

            /**
             * Move record as next sibling of target.
             * @param {Object} options
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @see NestedSetCollection.moveAfter
             */
            moveAfter: function(options) {
                options = options || {};
                options.record = this.get('id');

                if (_.isUndefined(this.collection)) {
                    return;
                }

                this.collection.moveAfter(options);
            },

            /**
             * Move record as as first child of target.
             * @param {Object} options
             * @param {Object|int} options.target Object or id of parent bean in which record should be first node.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @see NestedSetCollection.moveFirst
             */
            moveFirst: function(options) {
                options = options || {};
                options.record = this.get('id');

                if (_.isUndefined(this.collection)) {
                    return;
                }

                this.collection.moveFirst(options);
            },

            /**
             * Move record as as last child of target.
             * @param {Object} options
             * @param {Object|int} options.target Object or id of parent bean in which record should be last node.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             * @see NestedSetCollection.moveLast
             */
            moveLast: function(options) {
                options = options || {};
                options.record = this.get('id');

                if (_.isUndefined(this.collection)) {
                    return;
                }

                this.collection.moveLast(options);
            }

        }), false);

        /**
         * Bean collection class that implement nested set.
         * @class NestedSetCollection
         * @alias SUGAR.App.NestedSetCollection
         * @extends Data.BeanCollection
         */
        app.augment('NestedSetCollection', app.BeanCollection.extend({

            /**
             * @inheritdoc
             */
            model: app.NestedSetBean,

            /**
             * @property {string} Flat js tree.
             */
            jsonTree: null,

            /**
             * @property {string} Uid of root node.
             */
            root: null,

            /**
             * @inheritdoc
             */
            offset: -1,

            /**
             * Create callback object.
             * @param {Object} options
             * @return {Object} Callbacks.
             */
            _initCallback: function(options) {
                var self = this,
                    callback = {};

                callback.complete = options.complete || null;
                callback.error = options.error || null;

                // sync collection with backend on success
                callback.success = _.bind(function(data, response) {
                    this.tree({
                        success: _.bind(function() {
                            if (_.isFunction(options.success)) {
                                options.success(data, response);
                            }
                            app.events.trigger('app:nestedset:sync:complete', self);
                        }, this),
                        error: _.bind(function(collection, error) {
                            if (_.isFunction(options.error)) {
                                options.error(error);
                            }
                        }, this)
                    });
                }, this);

                return callback;
            },

            /**
             * @inheritdoc
             *
             * Override BeanCollection.parse method
             * and store collection JSON raw tree data.
             */
            parse: function(response, options) {
                this.jsonTree = response;
                return app.BeanCollection.prototype.parse.apply(this, arguments);
            },

            /**
             * @inheritdoc
             */
            sync: function(method, model, options) {
                var callbacks = app.data.getSyncCallbacks(method, model, options);
                app.api.call(method, this.url, options, callbacks);
            },

            /**
             * Reset _childsById property and remove models from root collection.
             *
             * @inheritdoc
             */
            _reset: function() {
                if (this.length && !_.isEmpty(this.rootCollection)) {
                    _.each(this.models, function(model) {
                        model = this.rootCollection.getChild(model);
                        if (model) {
                            delete this.rootCollection._childsById[model.id];
                            delete this.rootCollection._childsById[model.cid];
                        }
                    },this);
                }

                this._childsById = {};

                app.BeanCollection.prototype._reset.apply(this, arguments);
                return this;
            },

            /**
             * Generate _childsById property for root collection.
             *
             * @inheritdoc
             */
            add: function(models, options) {
                app.BeanCollection.prototype.add.apply(this, arguments);
                if (this.length) {
                    var rootCollection = this.rootCollection || this;
                    _.each(this.models, function(model) {
                        rootCollection._childsById[model.cid] = model;
                        if (model.id != null) rootCollection._childsById[model.id] = model;
                    });
                }
            },

            /**
             * Remove models from root collection.
             *
             * @inheritdoc
             */
            remove: function(models, options) {
                var rootCollection = this.rootCollection || this,
                    i, l, model;

                for (i = 0, l = models.length; i < l; i++) {
                    model = rootCollection.getChild(models[i]);
                    if (!model) continue;
                    delete rootCollection._childsById[model.id];
                    delete rootCollection._childsById[model.cid];
                }

                app.BeanCollection.prototype.remove.apply(this, arguments);
                return this;
            },

            /**
             * Get a model from all children by id.
             * @param {Backbone.Model|String} obj Object to get.
             * @return {Data.Bean}
             */
            getChild: function(obj) {
                if (obj == null) return void 0;

                var rootCollection = this.rootCollection || this;

                rootCollection._idAttr ||
                    (rootCollection._idAttr = rootCollection.model.prototype.idAttribute);

                return rootCollection._childsById[
                    obj.id || obj.cid || obj[rootCollection._idAttr] || obj
                ];
            },

            /**
             * Load a nestedset data collection.
             * @param {Object} options
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            tree: function(options) {
                this.url = app.api.buildURL(
                    this.module,
                    [this.root, 'tree'].join('/')
                );

                this.fetch(options);
            },

            /**
             * Setup options for insert actions.
             * @param {Object} options
             * @return {Object} options
             * @protected
             */
            _prepareInsertOptions: function(options) {
                options = options || {};

                options.data = options.data || {name: 'New node'};

                options.target = options.target || null;
                options.target = _.isObject(options.target) ?
                    options.target.get('id') :
                    options.target;

                options.callback = this._initCallback(options);

                return options;
            },

            /**
             * Append new record to target as last child.
             * @param {Object} options
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Object} options.data (optional) Data of new bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            append: function(options) {
                options = this._prepareInsertOptions(options);
                options.target = _.isEmpty(options.target) ?
                    this.root :
                    options.target;

                var url = app.api.buildURL(
                    this.module,
                    ['append', options.target].join('/')
                );

                app.api.call('create', url, options.data, options.callback);
            },

            /**
             * Prepend new record to target as first child.
             * @param {Object} options
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Object} options.data (optional) Data of new bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            prepend: function(options) {
                options = this._prepareInsertOptions(options);
                options.target = _.isEmpty(options.target) ?
                    this.root :
                    options.target;

                var url = app.api.buildURL(
                    this.module,
                    ['prepend', options.target].join('/')
                );

                app.api.call('create', url, options.data, options.callback);
            },

            /**
             * Insert new record as previous sibling of target.
             * @param {Object} options
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Object} options.data (optional) Data of new bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            insertBefore: function(options) {
                options = this._prepareInsertOptions(options);

                if (_.isEmpty(options.target)) {
                    return;
                }

                var url = app.api.buildURL(
                    this.module,
                    ['insertbefore', options.target].join('/')
                );

                app.api.call('create', url, options.data, options.callback);
            },

            /**
             * Insert new record as next sibling of target.
             * @param {Object} options
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Object} options.data (optional) Data of new bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            insertAfter: function(options) {
                options = this._prepareInsertOptions(options);

                if (_.isEmpty(options.target)) {
                    return;
                }

                var url = app.api.buildURL(
                    this.module,
                    ['insertafter', options.target].join('/')
                );

                app.api.call('create', url, options.data, options.callback);
            },

            /**
             * Setup options for move actions.
             * @param {Object} options
             * @return {Object} options
             * @protected
             */
            _prepareMoveOptions: function(options) {
                options = options || {};

                options.record = options.record || null;
                options.record = _.isObject(options.record) ?
                    options.record.get('id') :
                    options.record;

                options.target = options.target || null;
                options.target = _.isObject(options.target) ?
                    options.target.get('id') :
                    options.target;

                options.callback = this._initCallback(options);

                return options;
            },

            /**
             * Move record as previous sibling of target.
             * @param {Object} options
             * @param {Object|int} options.record Bean object or id of bean to move.
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            moveBefore: function(options) {
                options = this._prepareMoveOptions(options);

                if (_.isEmpty(options.record) || _.isEmpty(options.target)) {
                    return;
                }

                var url = app.api.buildURL(
                    this.module,
                    [options.record, 'movebefore', options.target].join('/')
                );

                app.api.call('update', url, {}, options.callback);
            },

            /**
             * Move record as next sibling of target.
             * @param {Object} options
             * @param {Object|int} options.record Bean object or id of bean to move.
             * @param {Object|int} options.target Target object or id of target bean.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            moveAfter: function(options) {
                options = this._prepareMoveOptions(options);

                if (_.isEmpty(options.record) || _.isEmpty(options.target)) {
                    return;
                }

                var url = app.api.buildURL(
                    this.module,
                    [options.record, 'moveafter', options.target].join('/')
                );

                app.api.call('update', url, {}, options.callback);
            },

            /**
             * Move record as as first child of target.
             * @param {Object} options
             * @param {Object|int} options.record Bean object or id of bean to move.
             * @param {Object|int} options.target Object or id of parent bean in which record should be first node.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            moveFirst: function(options) {
                options = this._prepareMoveOptions(options);

                if (_.isEmpty(options.record) || _.isEmpty(options.target)) {
                    return;
                }

                var url = app.api.buildURL(
                    this.module,
                    [options.record, 'movefirst', options.target].join('/')
                );

                app.api.call('update', url, {}, options.callback);
            },

            /**
             * Move record as as last child of target.
             * @param {Object} options
             * @param {Object|int} options.record Bean object or id of bean to move.
             * @param {Object|int} options.target Object or id of parent bean in which record should be last node.
             * @param {Function} options.success (optional) Callback function on success.
             * @param {Function} options.complete (optional) Callback function on complete.
             * @param {Function} options.error (optional) Callback function on error.
             */
            moveLast: function(options) {
                options = this._prepareMoveOptions(options);

                if (_.isEmpty(options.record) || _.isEmpty(options.target)) {
                    return;
                }

                var url = app.api.buildURL(
                    this.module,
                    [options.record, 'movelast', options.target].join('/')
                );

                app.api.call('update', url, {}, options.callback);
            }

        }), false);

        /**
         *
         */
        app.plugins.register('NestedSetCollection', ['view', 'field'], {
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    this.collection = new app.NestedSetCollection();
                    this.collection.module = this.dataProvider || null;
                    this.collection.root = this.root || null;
                }, this);
            }
        });
    });
})(SUGAR.App);
