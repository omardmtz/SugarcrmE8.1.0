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

/**
 * Base bean class. Beans extend `Backbone.Model`.
 *
 * Use {@link Data.DataManager} to create instances of beans.
 *
 * **CRUD**
 *
 * Use standard Backbone's `fetch`, `save`, and `destroy`
 * methods to perform CRUD operations on beans. See the
 * {@link Data.DataManager} class for details.
 *
 * **Validation**
 *
 * This class does not override `Backbone.Model.validate`.
 * The validation is done in the `save` method. If the bean is invalid the save
 * is rejected. Use {@link Data/Bean#isValidAsync} to check if the bean
 * is valid in other situations. Failed validations trigger an
 * `app:error:validation:<field-name>` event.
 *
 * @module Data/Bean
 * @class
 */

const PluginManager = require('core/plugin-manager');
const Utils = require('utils/utils');
const Validation = require('data/validation');

/**
 * Calls a given callback function with the result of validating this model.
 *
 * @param {Array|Object} fields A hash of field definitions or array of field
 *   names to validate.
 * @param {Function} callback Function called with the `isValid` flag once the
 *   validation is complete.
 * @private
 */
Backbone.Model.prototype.doValidate = function(fields, callback) {
    callback(this.isValid());
};

/**
 * Adds validation errors to the passed in error object.
 *
 * @param {Object} errors Validation errors object to fill in.
 * @param {Object} result Validation result.
 * @param {string} fieldName Name of the field that failed validation.
 * @param {string} validatorName Name of the validator that failed.
 * @private
 */
function _addValidationError(errors, result, fieldName, validatorName) {
    if (_.isUndefined(result)) return;

    if (_.isUndefined(errors[fieldName])) {
        errors[fieldName] = {};
    }
    errors[fieldName][validatorName] = result;
}

const Bean = Backbone.Model.extend({
    /**
     * Extends `Backbone.Model#constructor`. Attaches model plugins to allow
     * `initialize()` to be overridden.
     *
     * @param {Object} [attributes] Standard Backbone model attributes.
     * @param {Object} [options] Standard Backbone model options.
     * @memberOf Data/Bean
     * @instance
     */
    constructor: function(attributes, options) {
        PluginManager.attach(this, 'model');
        Backbone.Model.prototype.constructor.call(this, attributes, options);
    },

    /**
     * Initializes this bean. Extends `Backbone.Model#initialize`.
     *
     * @param {Object} [attributes] Standard Backbone model attributes.
     * @memberOf Data/Bean
     * @instance
     */
    initialize: function(attributes) {
        Backbone.Model.prototype.initialize.call(this, attributes);

        // assume our attributes from creation are synced
        this.setSyncedAttributes(this.attributes);

        this._bindEvents();
        this._relatedCollections = this._relatedCollections || null;

        /**
         * The request object that is currently syncing against the server.
         *
         * This object is needed to determine if a fetch request should be
         * aborted for the collection (e.g. if a new fetch request returns a
         * response prior to a previous fetch request).
         *
         * @private
         * @memberOf Data/Bean
         * @type {SUGAR.Api.HttpRequest}
         */
        this._activeFetchRequest = null;

        // Populate with default values only if the model is new and has not yet been populated
        if (this.isNew() && this._defaults) {
            _.each(this._defaults, function(value, key) {
                if (!this.has(key)) {
                    this.set(key, value, { silent: true });
                }
            }, this);
        }

        //Clone the fields to allow dynamic changes to vardefs per bean instance
        if (this.fields) {
            this.fields = Utils.deepCopy(this.fields);
        }

        this.addValidationTask('sidecar', _.bind(this._doValidate, this));
    },

    /**
     * Extends `Backbone.Model#fetch`.
     *
     * Only one fetch request can be executed at a time - previous fetch
     * requests will be aborted.
     *
     * @param {Object} [options] Fetch options.
     * @param {Function} [options.success] The success callback to execute.
     * @param {Function} [options.error] The error callback to execute.
     * @return {SUGAR.Api.HttpRequest} The active fetch request.
     * @memberOf Data/Bean
     * @instance
     */
    fetch: function(options) {
        options = _.extend({}, this.getOption(), options);
        this.abortFetchRequest();
        this._activeFetchRequest = Backbone.Model.prototype.fetch.call(this, options);
        return this._activeFetchRequest;
    },

    /**
     * Retrieves the currently active fetch request.
     *
     * @return {SUGAR.Api.HttpRequest} The active fetch request.
     * @memberOf Data/Bean
     * @instance
     */
    getFetchRequest: function() {
        return this._activeFetchRequest;
    },

    /**
     * Aborts the currently active fetch request.
     *
     * @memberOf Data/Bean
     * @instance
     */
    abortFetchRequest: function() {
        var req = this.getFetchRequest();
        if (req) {
            SUGAR.App.api.abortRequest(req.uid);
        }
    },

    /**
     * Overrides `Backbone.Model#set` to add specific logic for `collection`
     * fields.
     *
     * @param {string|Object} key The key. Can also be an object with the
     *   key/value pair.
     * @param {string} val The value to set.
     * @param {Object} options A hash of options.
     * @return {Data/Bean} This bean instance.
     * @memberOf Data/Bean
     * @instance
     */
    set: function (key, val, options) {
        if (_.isUndefined(key) || _.isNull(key)) {
            return this;
        }

        var attrs;
        if (typeof key === 'object') {
            attrs = key;
            options = val;
        } else {
            (attrs = {})[key] = val;
        }

        options = options || {};

        var collections = this.getCollectionFields(attrs);
        attrs = _.omit(attrs, _.keys(collections));
        Backbone.Model.prototype.set.call(this, attrs, options);
        this._setCollectionFieldValues(collections, options);

        return this;
    },

    /**
     * Extends `Backbone.Model#get` to create a mixed bean collection for
     * `collection` fields if there is none yet.
     *
     * @param {string} attr The attribute name.
     * @return {string} The value of the requested attribute.
     * @memberOf Data/Bean
     * @instance
     */
    get: function(attr) {
        var value = Backbone.Model.prototype.get.call(this, attr);

        // If the field is not a `collection` field
        // or the field has been initialized.
        if (!this.fields || !this.fields[attr] || this.fields[attr].type !== 'collection' ||
            value instanceof SUGAR.App.MixedBeanCollection) {
            return value;
        }

        // If the field is a `collection` field and has not been initialized.
        value = this._createMixedBeanCollectionField(attr);
        Backbone.Model.prototype.set.call(this, _.object([attr], [value]), {silent: true});

        return value;
    },

    /**
     * Adds `collection` fields records to the mixed bean collection. If
     * a mixed bean collection does not exist yet, it will be created.
     *
     * @param {Object} collections Object containing collections fields
     *   attributes.
     * @param {Object} options A hash of options.
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _setCollectionFieldValues: function (collections, options) {
        _.each(collections, function (records, key) {
            //If a collection field is being set to a collection, we should just accept it.
            if (records instanceof SUGAR.App.MixedBeanCollection) {
                if (this.get(key) !== records) {
                    this.stopListening(this.get(key));
                    Backbone.Model.prototype.set.call(this, _.object([key], [records]), options);
                    this.listenTo(records, 'update reset', function (collection, options) {
                        this.trigger('change:' + key, this, collection, options);
                    });

                    this.off('sync', records.resetDelta, records);
                    this.on('sync', records.resetDelta, records);
                }
            } else {
                var colOptions = {};
                var collection = this.get(key);

                //Record list populated from a `collection` field response
                if (_.isObject(records) && records.records) {
                    colOptions = _.extend(colOptions, _.omit(records, 'records'));
                    records = records.records;
                }

                //We need a collection to add the models to.
                _.each(colOptions, function (v, k) {
                    collection[k] = v;
                });

                collection.offset = collection.next_offset;
                collection.reset(records, options);
            }
        }, this);
    },

    /**
     * Creates a mixed bean collection passing the related link bean
     * collections of this `collection` field.
     *
     * @param {string} field A `collection` type field.
     * @param {Object[]|Data/Bean[]} models The models to add to the collection.
     * @return {Data/MixedBeanCollection} The newly created mixed bean
     *   collection.
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _createMixedBeanCollectionField: function (field, models) {
        var fieldDef = this.fields[field];
        if (fieldDef && fieldDef.links) {
            var links = fieldDef.links;
            if (_.isString(links)) {
                links = [links];
            }

            var linkCollections = {};
            _.each(links, function (link) {
                if (_.isObject(link) && _.has(link, 'name')) {
                    link = link.name;
                }

                if (_.isString(link)) {
                    linkCollections[link] = this.getRelatedCollection(link);
                }
            }, this);
            var collection = SUGAR.App.data.createMixedBeanCollection(models || [],
                {links: linkCollections, parentBean: this, collectionField: field}
            );

            this.listenTo(collection, 'update reset', function(collection, options) {
                this.trigger('change:' + field, this, collection, options);
            });

            this.off('sync', collection.resetDelta, collection);
            this.on('sync', collection.resetDelta, collection);

            return collection;
        }
    },

    /**
     * Binds events on {@link Data/Bean the model}.
     *
     * @protected
     * @memberOf Data/Bean
     * @instance
     */
    _bindEvents: function() {
        this.on('sync', function() {
            this._checkAcl();
            this.setSyncedAttributes(this.attributes);
        }, this);
    },

    /**
     * Checks if the `_acl` attribute has changed from its synced value on
     * {@link Data/Bean the model}.
     *
     * @fires <b>acl:change</b> If the at least one field had its ACLs changed.
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _checkAcl: function() {
        var changedFieldAcls = this._checkFieldAcls();

        if (_.size(changedFieldAcls) || !_.isEqual(
            _.omit(this.get('_acl'), 'fields'),
            _.omit(this.getSynced('_acl'), 'fields')
        )) {
            this.trigger('acl:change', changedFieldAcls);
        }
    },

    /**
     * Triggers the `acl:change:<fieldname>` event on all the fields whose
     * ACLs have changed. All events are triggered on
     * {@link Data/Bean the model}.
     *
     * @return {Object} The hash of fields that had ACL changes.
     * @fires <b>acl:change:<fieldname></b> For each field whose ACL changed.
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _checkFieldAcls: function () {
        var changedFieldAcls = {};
        var fieldsProp = _.property('fields');
        var syncedFieldAcls = fieldsProp(this.getSynced('_acl')) || {};
        var fieldAcls = fieldsProp(this.get('_acl')) || {};
        var fields = _.extend({}, syncedFieldAcls, fieldAcls);

        _.each(fields, function (field, fieldName) {
            if (!_.isEqual(syncedFieldAcls[fieldName], fieldAcls[fieldName])) {
                this.trigger('acl:change:' + fieldName);
                changedFieldAcls[fieldName] = true;
            }
        }, this);

        return changedFieldAcls;
    },

    /**
     * Disposes this bean.
     *
     * @memberOf Data/Bean
     * @instance
     */
    dispose: function() {
        PluginManager.detach(this, 'model');
    },

    /**
     * Caches a collection of related beans in this bean instance.
     *
     * @param {string} link Relationship link name.
     * @param {Data/BeanCollection} collection A collection of related beans to
     *   cache.
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _setRelatedCollection: function(link, collection) {
        if (!this._relatedCollections) this._relatedCollections = {};
        this._relatedCollections[link] = collection;
    },

    /**
     * Gets a collection of related beans.
     *
     * This method returns a cached in memory instance of the collection. If
     * the collection doesn't exist in the cache, it will be created using the
     * {@link Data.DataManager#createRelatedCollection} method.
     * Use the {@link Data.DataManager#createRelatedCollection} method to get a
     * new instance of a related collection.
     *
     * Example of usage:
     * ```
     * var contacts = opportunity.getRelatedCollection('contacts');
     * contacts.fetch({ relate: true });
     * ```
     *
     * @param {string} link Relationship link name.
     * @return {Data/BeanCollection} Previously created collection or a new
     *   collection of related beans.
     * @memberOf Data/Bean
     * @instance
     */
    getRelatedCollection: function(link) {
        if (this._relatedCollections && this._relatedCollections[link]) {
            return this._relatedCollections[link];
        }

        return SUGAR.App.data.createRelatedCollection(this, link);
    },

    /**
     * Validates a bean asynchronously.
     *
     * This method simply runs validation on the bean and calls the callback
     * with the result - it does not fire any events or display any alerts.
     * If you need events and alerts, use {@link Data/Bean#doValidate}.
     *
     * Validation is view-agnostic.
     *
     * Note: This method is different from `Backbone.Model#isValid`
     * which does not support the asynchronous validation we require.
     *
     * @param {string[]|Object} [fields=this.fields] A hash of field
     *   definitions or array of field names to validate. If not specified, all
     *   fields will be validated. Keys are field names, values are field
     *   definitions (combination of viewdefs and vardefs).
     * @param {Function} [callback] Function called with `isValid` flag and
     *   any errors once the validation is complete.
     * @memberOf Data/Bean
     * @instance
     */
    isValidAsync: function(fields, callback) {
        fields = fields || this.fields;

        async.waterfall(
            // run all validation tasks
            _.flatten([
                function(waterfallCallback) {
                    waterfallCallback(null, fields, {});
                },
                _.sortBy(this._validationTasks)
            ]),
            // waterfall callback
            function(didWaterfallFail, fields, errors) {
                if (!didWaterfallFail) {
                    var isValid = _.isEmpty(errors);
                    if (_.isFunction(callback)) {
                        callback(isValid, errors);
                    }
                }
            }
        );
    },

    /**
     * Validates a bean asynchronously - firing events on start, complete,
     * and failure.
     *
     * This method is called before {@link Data/Bean#save}.
     *
     * @param {Array|Object} [fields] A hash of field definitions or array
     *   of field names to validate. If not specified, all fields will be
     *   validated. View-agnostic validation will be run. Keys are field
     *   names, values are field definitions (combination of viewdefs and
     *   vardefs).
     * @param {Function} [callback] Function called with `isValid` flag once
     *   the validation is complete.
     * @fires <b>validation:success</b> If validation passes.
     * @fires <b>error:validation</b> If validation fails.
     * @fires <b>validation:complete</b> When validation is finished.
     * @memberOf Data/Bean
     * @instance
     */
    doValidate: function(fields, callback) {
        var self = this;

        this.trigger('validation:start');

        this.isValidAsync(fields, function(isValid, errors) {
            if (isValid) {
                self.trigger('validation:success');
            }
            self.trigger('validation:complete', self._processValidationErrors(errors));

            if (_.isFunction(callback)) {
                callback(isValid);
            }
        });
    },

    /**
     * Validates fields.
     *
     * @param {Array|Object} fields A hash of field definitions or array of
     *   field names to validate.
     * @param {Object} errors Hashmap of field names to error definitions,
     *   which may be either primitive types or objects, depending on validator.
     * @param {Function} callback Async.js waterfall callback.
     *
     * Example:
     * ```
     * {
     *    first_name: {
     *       maxLength: 20,
     *       someOtherValidatorName: { some complex error definition... }
     *    },
     *    last_name: {
     *       required: true
     *    }
     * }
     * ```
     *
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _doValidate: function(fields, errors, callback) {
        var value;

        // fields can be either array or object
        _.each(fields, function(field, fieldName) {
            if (_.isString(field)) {
                fieldName = field;
                field = this.fields[fieldName];
            }

            value = this.get(fieldName);

            if (field) { // Safeguard against missing field definitions
                _addValidationError(errors,
                    Validation.requiredValidator(field, field.name, this, value), fieldName, 'required');

                if (value || value === 0) { // "0" must have validation
                    _.each(Validation.validators, function(validator, validatorName) {
                        // FIXME: remove this when Data/Validation.validators.url is removed
                        if (validatorName === 'url') {
                            return;
                        }

                        _addValidationError(errors, validator(field, value, this), fieldName, validatorName);
                    }, this);
                }
            }
        }, this);

        callback(null, fields, errors);
    },

    /**
     * Adds a validation task to the validation waterfall.
     *
     * @param {string} taskName The name of the task.
     * @param {Function} validate The validation task.
     * @memberOf Data/Bean
     * @instance
     */
    addValidationTask: function(taskName, validate) {
        this._validationTasks = this._validationTasks || {};

        this._validationTasks[taskName] = validate;
    },

    /**
     * Removes a specified validation task from the bean.
     *
     * @param {string} taskName The name of the task.
     * @memberOf Data/Bean
     * @instance
     */
    removeValidationTask: function(taskName) {
        if (this._validationTasks) {
            this._validationTasks = _.omit(this._validationTasks, taskName);
        }
    },

    /**
     * Processes validation errors and triggers validation error events.
     *
     * @param {Object} errors Validation errors.
     * @return {boolean} `true` if `errors` is empty; `false` otherwise.
     * @private
     * @memberOf Data/Bean
     * @instance
     */
    _processValidationErrors: function(errors) {
        var isValid = true;
        if (!_.isEmpty(errors)) {
            SUGAR.App.error.handleValidationError(this, errors);
            _.each(errors, function(fieldErrors, fieldName) {
                this.trigger("error:validation:" + fieldName, fieldErrors);
            }, this);
            this.trigger("error:validation", errors);
            isValid = false;
        }

        return isValid;
    },

    /**
     * Overrides `Backbone.Model#save`
     * so we can run asynchronous validation outside of the
     * standard validation loop.
     *
     * This method checks if this bean is valid only if `options` hash
     * contains `fieldsToValidate` parameter.
     *
     * @param {Object} [attributes] The model attributes.
     * @param {Object} [options] Standard Backbone save options.
     * @param {Array} [options.fieldsToValidate] List of field names to
     *   validate.
     * @return {SUGAR.Api.HttpRequest} Returns an HTTP request if
     *   there are no fields to validate or `undefined` if validation needs
     *   to happen first.
     * @memberOf Data/Bean
     * @instance
     */
    save: function(attributes, options) {
        if (!options || !options.fieldsToValidate) {
            return Backbone.Model.prototype.save.call(this, attributes, options);
        }

        this.doValidate(options.fieldsToValidate, (isValid) => {
            if (isValid) {
                return Backbone.Model.prototype.save.call(this, attributes, options);
            }
        });
    },

    /**
     * Checks if this bean can have attachments.
     *
     * @return {boolean} `true` if this bean's field definition has
     *   an `attachment_list` field.
     * @memberOf Data/Bean
     * @instance
     */
    canHaveAttachments: function() {
        return _.has(this.fields, 'attachment_list');
    },

    /**
     * Fetches a list of files (attachments).
     *
     * @param {Object} callbacks Hash of callbacks.
     * @param {Function} [callbacks.success] Called on success.
     * @param {Function} [callbacks.error] Called on error.
     * @param {Function} [callbacks.complete] Called on completion.
     * @param {Object} [options] Request options. See
     *   {@link SUGAR.Api#file} for details.
     * @return {Object} XHR object.
     * @memberOf Data/Bean
     * @instance
     */
    getFiles: function(callbacks, options) {
        options = options || {};
        // The token will be passed in the header
        options.passOAuthToken = false;
        return SUGAR.App.api.file('read', {
            module: this.module,
            id: this.id
        }, null, callbacks, options);
    },

    /**
     * Copies fields from a given bean into this bean.
     *
     * This method does not copy the `id` field, `link`-type fields, or fields
     * whose values are auto-incremented (metadata field definition has
     * `auto_increment === true`).
     *
     * @param {Data/Bean} source The bean to copy the fields from.
     * @param {Array} [fields] The fields to copy. All fields are copied if
     *   not specified.
     * @param {Object} [options] Standard Backbone options that should be
     *   passed to the `Backbone.Model#set` method.
     * @memberOf Data/Bean
     * @instance
     */
    copy: function(source, fields, options) {
        var attrs = {};
        var vardefs = SUGAR.App.metadata.getModule(this.module).fields;
        fields = fields || _.pluck(vardefs, "name");

        // Iterate over fields and copy everything except auto_increment fields, links, ID,
        // or any field with an explicit duplicate_on_record_copy set to 'no'
        _.each(fields, function(name) {
                var def = vardefs[name],
                    permitCopy;

                if (!def || def.duplicate_on_record_copy === 'no') {
                    return;
                }

                permitCopy = (def.duplicate_on_record_copy === 'always') ||
                    (name !== 'id' && def.type !== 'link' &&
                        !def.auto_increment);

                if (permitCopy && source.has(name)) {

                    var value = source.get(name);
                    // Perform deep copy in case the value is not a primitive type
                    if (_.isObject(value)) {
                        value = Utils.deepCopy(value);
                    }
                    attrs[name] = value;
                }
            }
        );

        this.set(attrs, options);
        this.isCopied = true;
    },

    /**
     * Checks whether this bean was populated as a result of a copy.
     *
     * @return {boolean} `true` if this bean was populated as a result of a
     *   copy; `false` otherwise.
     * @memberOf Data/Bean
     * @instance
     */
    isCopy: function() {
        return (this.isCopied === true);
    },

    /**
     * Uploads a file.
     *
     * @param {string} fieldName Name of the file field.
     * @param {Array} $files List of DOM elements that contain file inputs.
     * @param {Object} [callbacks={}] Callback hash.
     * @param {Object} [options={}] Upload options. See the
     *   {@link SUGAR.Api#file} method for details.
     * @return {Object} XHR object.
     * @memberOf Data/Bean
     * @instance
     */
    uploadFile: function(fieldName, $files, callbacks, options) {
        callbacks = callbacks || {};
        options = options || {};

        return SUGAR.App.api.file(
            'create',
            {
                //Set id to temp if we save a temporary file to reach correct API
                id: (options.temp !== true) ? this.id : 'temp',
                module: this.module,
                field: fieldName
            },
            $files,
            callbacks,
            options
        );
    },

    /**
     * Favorites or unfavorites this bean.
     *
     * @param {boolean} flag If `true`, marks this bean as a favorite.
     * @param {Object} [options] Standard Backbone options for
     *   `Backbone.Model#save` operation.
     * @return {SUGAR.Api.HttpRequest} The request to update this bean.
     * @memberOf Data/Bean
     * @instance
     */
    favorite: function(flag, options) {
        options = options || {};
        options.favorite = true;
        return this.save({ my_favorite: !!flag }, options);
    },

    /**
     * Subscribes or unsubscribes to record changes.
     *
     * @param {boolean} flag If `true`, subscribes to record changes. If
     *   `false`, unsubscribes from record changes.
     * @param {Object} [options={}] Options for `Backbone.Model#save`.
     * @return {Object} `jqXHR` object or `false` if error occurs.
     * @memberOf Data/Bean
     * @instance
     */
    follow: function(flag, options) {
        options = options || {};
        flag = flag || false;
        options.following = true;
        return this.save({ following: flag }, options);
    },

    /**
     * Checks if this bean is favorited.
     *
     * @return {boolean} `true` if this bean is favorited, `false`
     *   otherwise.
     * @memberOf Data/Bean
     * @instance
     */
    isFavorite: function() {
        var flag = this.get("my_favorite");
        return (flag === "1" || flag === true);
    },

    /**
     * Calculates the difference between backup and changed model for restoring
     * model.
     *
     * @param {Object} original Hash of original (backed up) values.
     * @param {Array} exclude List of fields to exclude from comparison.
     * @return {Object} Difference between original and the current model
     *   attributes.
     * @memberOf Data/Bean
     * @instance
     */
    getChangeDiff: function(original, exclude) {
        var diff = {};
        original = original || {};
        exclude = exclude || [];

        _.each(this.attributes, function(value, key) {
            if (_.contains(exclude, key)) return;
            var previousValue = original[key];
            if (!_.isEqual(previousValue, value)) {
                diff[key] = previousValue;
            }
        });

        return diff;
    },

    /**
     * Checks if this bean has changed.
     *
     * @param {string} [attr] The attribute to check. If not specified,
     *   checks all attributes.
     * @return {boolean} `true` if at least one attribute has changed.
     * @memberOf Data/Bean
     * @instance
     */
    hasChanged: function(attr) {
        if (this.get(attr) instanceof SUGAR.App.MixedBeanCollection) {
            return this.get(attr).hasDelta();
        }

        return Backbone.Model.prototype.hasChanged.call(this, attr);
    },

    /**
     * Returns an object of attributes, containing what needs to be sent to
     * the server when saving the bean . This method is called when
     * `JSON.stringify()` is called on this bean.
     *
     * @param {Object} [options] Serialization options.
     * @param {Object} [options.fields] List of field names to be included
     *   in the object of attributes. It retrieves all fields by default.
     * @return {Object} A hashmap of attribute names to attribute values.
     * @memberOf Data/Bean
     * @instance
     */
    toJSON: function(options) {
        var fields = (options && options.fields) ? options.fields : _.keys(this.attributes),
            json = {};

        _.each(fields, function(fieldName) {
            let val = this.get(fieldName);
            if (val instanceof SUGAR.App.MixedBeanCollection) {
                _.each(val.getDelta(), (val, linkName) => {
                    json[linkName] = val;
                });
            } else {
                if (_.isObject(val) && _.isFunction(val.toJSON)) {
                    SUGAR.App.logger.warn('Calling `toJSON` on object attributes is deprecated in 7.9 and will be ' +
                        'removed in 7.10');
                    val = val.toJSON(options);
                }

                json[fieldName] = val;
            }
        }, this);

        return json;
    },

    /**
     * Returns a string representation useful for debugging, in the form
     * `bean:[module-name]/[id]`.
     *
     * @return {string} A string representation of this bean.
     * @memberOf Data/Bean
     * @instance
     */
    toString: function() {
        return "bean:" + this.module + "/" + (this.id ? this.id : "<no-id>");
    },

    /**
     * Reverts model attributes to the last values from last sync or values on creation.
     *
     * @fires <b>attributes:revert</b> If `options.silent` is falsy.
     *
     * @param {Object} options Options are passed onto set.
     * @param {boolean} [options.silent=false] If `true`, do not trigger
     *   `attributes:revert`.
     * @memberOf Data/Bean
     * @instance
     */
    revertAttributes: function(options) {
        options = options || {};
        options.revert = true;
        var changedAttr = this.changedAttributes(this.getSynced());
        this.set(Utils.deepCopy(changedAttr) || {}, options);
        if (!options.silent) {
            this.trigger('attributes:revert');
        }
    },

    /**
     * Gets changed attributes.
     *
     * @param {Object} [attrs] A hash of attributes to compare the current
     *   bean attributes against.
     * @return {Object|boolean} `false` if nothing has changed. An object
     *   containing the attributes passed in parameters that are different
     *   from the bean ones.
     * @memberOf Data/Bean
     * @instance
     */
    changedAttributes: function (attrs) {
        let collections = this.getCollectionFields(attrs);
        if (!_.isUndefined(attrs)) {
            attrs = _.omit(attrs, _.keys(collections));
        }

        let changed = Backbone.Model.prototype.changedAttributes.call(this, attrs);

        _.each(collections, (val, key) => {
            if (this.get(key).hasDelta()) {
                changed = changed || {};
                changed[key] = val;
            }
        }, this);

        return changed;
    },

    /**
     * Sets internal synced attribute hash that's used in `revertAttributes`.
     *
     * @param {Object} attributes Attributes of model to setup.
     * @memberOf Data/Bean
     * @instance
     */
    setSyncedAttributes: function(attributes) {
        this._syncedAttributes = attributes ? Utils.deepCopy(attributes) : {};
    },

    /**
     * Gets the value of the synced attribute for the given key. If no key
     * is passed, all synced attributes are returned.
     *
     * @param {string} [key] The attribute name.
     * @return {*} The synced attribute's value.
     * @memberOf Data/Bean
     * @instance
     */
    getSynced: function(key) {
        return key ? this._syncedAttributes[key] : this._syncedAttributes;
    },

    /**
     * Gets the default value of an attribute.
     *
     * @param {string} [key] The name of the attribute. If unspecified, the
     *   default values of all attributes are returned.
     * @return {*} The default value if you passed a `key`, or the hash of
     *   default values.
     * @memberOf Data/Bean
     * @instance
     */
    getDefault: function(key) {
        var defaults = _.clone(this._defaults) || {};
        if (_.isUndefined(key)) {
            return defaults;
        }
        return defaults[key];
    },

    /**
     * Sets the default values (one or many) on the model, and fill in
     * undefined attributes with the default values.
     *
     * @param {string|Object} key The name of the attribute, or a hash of
     *   attributes.
     * @param {*} [val] The default value for the `key` argument.
     * @return {Data/Bean} This bean instance.
     * @memberOf Data/Bean
     * @instance
     */
    setDefault: function(key, val) {
        var attrs;
        if (_.isObject(key)) {
            attrs = key;
        } else {
            (attrs = {})[key] = val;
        }
        this._defaults = _.extend({}, this._defaults, attrs);
        this.attributes = _.defaults(this.attributes, attrs);
        return this;
    },

    /**
     * Sets the default fetch options (one or many) on the model.
     *
     * @param {string|Object} key The name of the option, or an hash of
     * options.
     * @param {*} [val] The value for the `key` option.
     * @return {Data/Bean} This bean instance.
     * @memberOf Data/Bean
     * @instance
     */
    setOption: function(key, val) {
        var attrs;
        if (_.isObject(key)) {
            attrs = key;
        } else {
            (attrs = {})[key] = val;
        }

        /**
         * List of persistent fetch options.
         *
         * @type {Object}
         * @private
         * @memberOf Data/Bean
         */
        this._persistentOptions = _.extend({}, this._persistentOptions, attrs);
        return this;
    },

    /**
     * Unsets a default fetch option (or all).
     *
     * @param {string|Object} [key] The name of the option to unset, or
     *   nothing to unset all options.
     * @return {Data/Bean} This bean instance.
     * @memberOf Data/Bean
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
     * @param {string|Object} [key] The name of the option to retrieve. If
     *   unspecified, retrieves all options.
     * @return {*} A specific option, or the list of options.
     * @memberOf Data/Bean
     * @instance
     */
    getOption: function(key) {
        if (key) {
            return this._persistentOptions[key];
        }
        return this._persistentOptions;
    },

    /**
     * Gets all fields of a given type.
     *
     * @param {string} type The type of the field to search for.
     * @return {Array} List of fields filtered by the given type.
     * @memberOf Data/Bean
     * @instance
     */
    fieldsOfType: function(type) {
        return _.where(this.fields, {type: type});
    },

    /**
     * Gets a hash of collection fields attributes.
     *
     * @param {Object} [attrs=this.attributes] The hash of attributes to get
     *   the collection fields from.
     * @return {Object} A hash of collection fields attributes.
     * @memberOf Data/Bean
     * @instance
     */
    getCollectionFields: function(attrs) {
        return _.pick(attrs, _.pluck(this.fieldsOfType('collection'), 'name'));
    },

    /**
     * Merges changes into a bean's attributes.
     *
     * The default implementation overrides attributes with changes.
     *
     * @param {Object} attributes Bean attributes.
     * @param {Object} changes Object hash with changed attributes.
     * @param {string} [module] Module name.
     * @return {Object} Merged attributes.
     * @memberOf Data/Bean
     * @instance
     */
    merge: function(attributes, changes, module) {
        return _.extend(attributes, changes);
    }
});

module.exports = Bean;
