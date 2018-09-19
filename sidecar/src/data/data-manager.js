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
const Utils = require('utils/utils');
const Bean = require('data/bean');
const BeanCollection = require('data/bean-collection');
const MixedBeanCollection = require('data/mixed-bean-collection');

/**
 * The data manager handles the beans and collections life cycle. It provides
 * an API to declare data classes, instantiate them, and synchronize the data
 * with the server. It relies on the data structure defined by the application
 * metadata.
 *
 * ** The data manager provides:**
 *
 * - An interface to declare bean and collection classes from metadata
 * - Factory methods for creating instances of beans and bean collections
 * - Factory methods for creating instances of bean relations and relation
 *   collections
 * - A custom implementation of `Backbone.sync`
 *
 * **Data model metadata**
 *
 * The metadata is used to describe the data model. It contains information
 * about module fields and relationships.
 * From the following sample metadata, the data manager would declare two
 * classes: Opportunities and Contacts.
 * ```
 * var metadata = {
 *     modules: {
 *         Opportunities: {
 *             fields: {
 *                 name: { ... },
 *                 ...
 *             }
 *         },
 *         Contacts: { ... }
 *    },
 *    relationships: {
 *        opportunities_contacts: { ... },
 *        ...
 *    }
 * };
 * ```
 *
 * **Working with beans**
 *
 * Declare bean classes from metadata payload.
 * `declareModels` should be called at application start-up and whenever the
 * metadata changes:
 * ```
 * const DataManager = require('data/data-manager');
 * DataManager.declareModels(metadata);
 * ```
 * You may now create bean instances using factory methods.
 *
 * ```
 * var opportunity = DataManager.createBean(
 *     'Opportunities',
 *     { name: 'Cool opportunity' }
 * );
 * // You can save a bean using the standard `Backbone.Model.save` method.
 * // The save method will use the data manager's sync method to communicate
 * // changes to the remote server.
 * opportunity.save();
 *
 * // Create an empty collection of contacts.
 * var contacts = DataManager.createBeanCollection('Contacts');
 * // Fetch a list of contacts
 * contacts.fetch();
 * ```
 *
 * **Working with relationships**
 *
 * ```
 * var attrs = {
 *     firstName: 'John',
 *     lastName: 'Smith',
 *     // relationship field
 *     opportunityRole: 'Influencer'
 * }
 * // Create a new instance of a contact related to an existing opportunity
 * var contact = DataManager.createRelatedBean(opportunity, null, 'contacts', attrs);
 * // This will save the contact and create the relationship
 * contact.save(null, { relate: true });
 *
 * // Create an instance of contact collection related to an existing opportunity
 * var contacts = DataManager.createRelatedCollection(opportunity, 'contacts');
 * // This will fetch related contacts
 * contacts.fetch({ relate: true });
 *
 * ```
 *
 * @module Data/DataManager
 */

// Bean class cache
var _models = {};

// Bean collection class cache
var _collections = {};

/**
 * Gets the relationship name given the module and the link name.
 *
 * @param {string} module The module name.
 * @param {string} link The link name.
 * @return {string|boolean} relationship The relationship name or `false` if it
 *   is empty or undefined.
 * @private
 */
const getRelationshipName = function(module, link) {
    let vardefs = SUGAR.App.metadata.getModule(module);
    let relationship = vardefs.fields[link] && vardefs.fields[link].relationship;
    if (!relationship) {
        return false;
    }

    return relationship;
};

/**
 * @alias module:Data/DataManager
 */
const DataManager = _.extend({
    /**
     * Reference to the base bean model class constructor. Defaults to
     * {@link Data/Bean}.
     *
     * @type {Function}
     */
    beanModel: Bean,

    /**
     * Reference to the base bean collection class constructor. Defaults to
     * {@link Data/BeanCollection}.
     *
     * @type {Function}
     */
    beanCollection: BeanCollection,

    /**
     * Reference to the base mixed bean collection class constructor. Defaults
     * to {@link Data/MixedBeanCollection}.
     *
     * @type {Function}
     */
    mixedBeanCollection: MixedBeanCollection,

    /**
     * Initializes the data manager.
     */
    init: function() {
        var sync = _.bind(this.sync, this);
        this.beanModel.prototype.sync = sync;
        this.beanCollection.prototype.sync = sync;

        SUGAR.App.events.register(
            /**
             * Fires when the sync operation starts.
             *
             * Three parameters are passed to the callback:
             *
             *  * operation name (`method`)
             *  * reference to the model/collection
             *  * options
             *
             * ```
             * const Events = require('core/events');
             * Events.on('data:sync:start', function(method, model, options) {
             *     SUGAR.App.logger.debug('Started operation ' + method + ' on ' + model);
             * });
             * ```
             * @event data:sync:start
             */
            "data:sync:start",
            this
        );

        SUGAR.App.events.register(
            /**
             * Fires when the sync operation ends.
             *
             * Four parameters are passed to the callback:
             *
             *  * operation name (`method`)
             *  * reference to the model/collection
             *  * options
             *  * request (SUGAR.Api.HttpRequest)
             *
             * ```
             * const Events = require('core/events');
             * Events.on('data:sync:complete', function(method, model, options, request) {
             *     SUGAR.App.logger.debug("Finished operation " + method + " on " + model);
             * });
             * ```
             * @event data:sync:complete
             */
            "data:sync:complete",
            this
        );

        SUGAR.App.events.register(
            /**
             * Fires when the sync operation ends successfully.
             *
             * Four parameters are passed to the callback:
             *
             *  - operation name (`method`)
             *  - reference to the model/collection
             *  - options
             *  - request (SUGAR.Api.HttpRequest)
             *
             * ```
             * const Events = require('core/events');
             * Events.on('data:sync:success', function(method, model, options, request) {
             *     SUGAR.App.logger.debug('Finished operation ' + method + ' on ' + model);
             * });
             * ```
             * @event data:sync:success
             */
            "data:sync:success",
            this
        );

        SUGAR.App.events.register(
            /**
             * Fires when the sync operation ends unsuccessfully.
             *
             * Four parameters are passed to the callback:
             *
             *  * operation name (`method`)
             *  * reference to the model/collection
             *  * options
             *  * error (SUGAR.Api.HttpError)
             *
             * ```
             * const Events = require('core/events');
             * Events.on('data:sync:error', function(method, model, options, error) {
             *     SUGAR.App.logger.debug('Operation failed ' + method + ' on ' + model);
             * });
             * ```
             * @event data:sync:error
             */
            "data:sync:error",
            this
        );

        SUGAR.App.events.register(
            /**
             * Fires when the sync operation was aborted.
             *
             * Four parameters are passed to the callback:
             *
             *  * operation name (`method`)
             *  * reference to the model/collection
             *  * options
             *  * request {@link SUGAR.Api.HttpRequest}
             *
             * ```
             * const Events = require('core/events');
             * SUGAR.App.events.on('data:sync:abort', function(method, model, options, request) {
             *     SUGAR.App.logger.debug('Operation aborted ' + method + ' on ' + model);
             * });
             * ```
             * @event data:sync:abort
             */
            'data:sync:abort',
            this
        );
    },

    /**
     * Resets class declarations.
     *
     * @param {string} [module] The module name from which to remove the bean
     *   and collection class. If not specified, resets bean and collection
     *   classes of all modules.
     */
    reset: function (module) {
        this.resetModel(module);
        this.resetCollection(module);
    },

    /**
     * Resets bean class declarations.
     *
     * @param {string} [module] The module name from which to remove the bean
     *   class. If not specified, resets bean classes of all modules.
     */
    resetModel: function (module) {
        if (module) {
            delete _models[module];
        } else {
            _models = {};
        }
    },

    /**
     * Resets collection class declarations.
     *
     * @param {string} [module] The module name from which to remove the
     *   collection class. If not specified, resets collection classes of all
     *   modules.
     */
    resetCollection: function (module) {
        if (module) {
            delete _collections[module];
        }  else {
            _collections = {};
        }
    },

    /**
     * Declares bean model and collection classes for a given module and caches
     * them.
     *
     * @param {string} moduleName Module name.
     * @param {Object} module Module metadata object.
     * @param {string} platform The platform name.
     * @param {Object} [modelController] The bean controller to declare.
     * @param {Object} [collectionController] The collection controller to declare.
     */
    declareModel: function(moduleName, module, platform, modelController, collectionController) {
        // Bug 54814 init fields to something sane if module metadata is empty
        platform = platform || SUGAR.App.config.platform || 'base';

        this.declareModelClass(moduleName, module, platform, modelController);
        this.declareCollectionClass(moduleName, platform, collectionController);
    },

    /**
     * Declares the bean model class for a given module.
     *
     * @param {string} moduleName The module name.
     * @param {string} module The module metadata.
     * @param {string} platform The platform name.
     * @param {Object} [modelController] The model controller.
     * @return {Function} The created class.
     */
    declareModelClass: function(moduleName, module, platform, modelController) {
        var platformNamespace = Utils.capitalize(platform);
        moduleName = moduleName || platformNamespace + 'Model';

        this.resetModel(moduleName);

        var fields = module ? module.fields : {};
        var defaults = null;

        _.each(_.values(fields), function(field) {
            if (!_.isUndefined(field['default'])) {
                if (defaults === null) {
                    defaults = {};
                }
                defaults[field.name] = field['default'];
            }
        });

        var baseProperties = {
            /**
             * The hash of field names and default values.
             *
             * This hash should be used instead of the `Backbone.Model#default`
             * property. Setting default values is done in
             * {@link Data/Bean#initialize} only if the given model is new.
             *
             * @memberOf Data/Bean
             * @type {Object}
             * @instance
             */
            _defaults: defaults,

            /**
             * The module name.
             *
             * @memberOf Data/Bean
             * @type {string}
             * @instance
             * @readonly
             */
            module: moduleName,

            /**
             * The list of fields and their vardefs.
             *
             * @memberOf Data/Bean
             * @type {Object}
             * @instance
             * @readonly
             */
            fields: fields
        };

        var superModel = _models[platformNamespace + 'Model'] ||
            _models.BaseModel ||
            this.beanModel;

        modelController = _.extend(modelController || {}, baseProperties);

        return Utils.extendClass(_models, superModel, moduleName, modelController, platformNamespace);
    },

    /**
     * Declares bean collection class for a given module.
     *
     * @param {string} moduleName The module name.
     * @param {string} platform The platform name.
     * @param {Object} [collectionController] The controller.
     * @return {Function} The created class.
     */
    declareCollectionClass: function(moduleName, platform, collectionController) {
        var platformNamespace = Utils.capitalize(platform);
        var modelName = moduleName || platformNamespace + 'Model';
        var model = _models[modelName];

        moduleName = moduleName || platformNamespace + 'Collection';

        if (!model) {
            return;
        }
        this.resetCollection(moduleName);

        var baseCollectionProperties = {
            model: model,

            /**
             * The module name.
             *
             * @memberOf Data/BeanCollection
             * @type {string}
             * @instance
             * @readonly
             */
            module: moduleName,


            /**
             * Current collection offset for pagination.
             *
             * @memberOf Data/BeanCollection
             * @type {number}
             * @instance
             * @readonly
             */
            offset: 0
        };

        var superCollection = _collections[platformNamespace + 'Collection'] ||
            _collections.BaseCollection ||
            this.beanCollection;

        collectionController = _.extend(collectionController || {}, baseCollectionProperties);

        return Utils.extendClass(
            _collections,
            superCollection,
            moduleName,
            collectionController,
            platformNamespace
        );
    },

    /**
     * Merges a model with its metadata.
     *
     * @param {string} name The module name.
     * @param {Object} [module] The module metadata.
     */
    mergeModel: function(name, module) {
        var fields = module ? module.fields : {};
        var defaults = null;

        _.each(_.values(fields), function(field) {
            if (!_.isUndefined(field['default'])) {
                if (defaults === null) {
                    defaults = {};
                }
                defaults[field.name] = field['default'];
            }
        });

        var baseProperties = {
            /**
             * Same as {@link Data/Bean#_defaults}
             * @inheritdoc
             */
            _defaults: defaults,

            /**
             * Same as {@link Data/Bean#module}
             * @inheritdoc
             */
            module: name,

            /**
             * Same as {@link Data/Bean#fields}
             * @inheritdoc
             */
            fields: fields
        };

        _.extend(_models[name].prototype, baseProperties);
    },

    /**
     * Gets all bean classes.
     *
     * @return {Object} The hash of bean classes.
     */
    getModelClasses: function() {
        return _models;
    },

    /**
     * Gets all collection classes.
     *
     * @return {Object} The hash of collection classes.
     */
    getCollectionClasses: function() {
        return _collections;
    },

    /**
     * Declares bean models and collections classes for each module definition.
     *
     * Data manager uses {@link Core.MetadataManager#getModules} method to get
     * metadata if `modules` parameter is not specified.
     *
     * @param {Object} [modules] metadata hash in which keys are module names
     *   and values are module definitions.
     */
    declareModels: function(modules) {
        modules = modules || SUGAR.App.metadata.getModules();
        _.each(modules, function(module, name) {
            if (!_models[name]) {
                this.declareModel(name, module);
            } else {
                this.mergeModel(name, module);
            }
        }, this);
    },

    /**
     * Gets a bean class.
     *
     * @param {string} module The module name to get the bean class from.
     * @return {Object} The bean class for the given `module`, or
     *   `this.beanModel` if not found.
     */
    getBeanClass: function(module) {
        return _models[module] || this.beanModel;
    },

    /**
     * Creates an instance of a bean. Example of usage:
     *
     * ```
     * // Create an account bean. The account's name property will be set to
     * // "Acme".
     * const DataManager = require('data/data-manager');
     * var account = DataManager.createBean('Accounts', { name: 'Acme' });
     *
     * // Create a team set bean with a given ID
     * var teamSet = DataManager.createBean('TeamSets', { id: 'xyz' });
     *
     * ```
     *
     * @param {string} module The module name.
     * @param {Object} [attrs] Initial values of bean attributes, which
     *   will be set on the bean.
     * @param {Object} [options] A hash of options.
     * @return {Data/Bean} A new instance of a bean.
     */
    createBean: function(module, attrs, options) {
        return _models[module] ? new _models[module](attrs, options) : new DataManager.beanModel(attrs, options);
    },

    /**
     * Creates instance of a bean collection. Example of usage:
     *
     * ```
     * const DataManager = require('data/data-manager');
     * // Creates an empty collection of account beans.
     * var accounts = DataManager.createBeanCollection('Accounts');
     * ```
     *
     * @param {string} module The module name.
     * @param {Data/Bean[]} [models] Initial array or collection of models.
     * @param {Object} [options] A hash of options.
     * @return {Data/BeanCollection} A new instance of a bean collection.
     */
    createBeanCollection: function(module, models, options) {
        return _collections[module] ?
            new _collections[module](models, options) :
            new DataManager.beanCollection(models, options);
    },

    /**
     * Creates an instance of related {@link Data/Bean} or updates an
     * existing bean with link information.
     *
     * ```
     * // Create a new contact related to the given opportunity.
     * const DataManager = require('data/data-manager');
     * var contact = DataManager.createRelatedBean(opportunity, '1', 'contacts', {
     *    'first_name': 'John',
     *    'last_name': 'Smith',
     *    'contact_role': 'Decision Maker'
     * });
     * contact.save(null, { relate: true });
     * ```
     *
     * @param {Data/Bean} bean1 Instance of the first bean.
     * @param {Data/Bean|string} beanOrId2 Instance or ID of the second
     *   bean. A new instance is created if this parameter is `null`.
     * @param {string} link Relationship link name.
     * @param {Object} [attrs] Bean attributes hash.
     * @return {Data/Bean} A new instance of the related bean or existing
     *   bean instance updated with relationship link information.
     */
    createRelatedBean: function(bean1, beanOrId2, link, attrs) {
        var relatedModule = this.getRelatedModule(bean1.module, link);

        attrs = attrs || {};
        if (_.isString(beanOrId2)) {
            attrs.id = beanOrId2;
            beanOrId2 = this.createBean(relatedModule, attrs);
        }
        else if (_.isNull(beanOrId2)) {
            beanOrId2 = this.createBean(relatedModule, attrs);
        }
        else {
            beanOrId2.set(attrs);
        }

        /**
         * Relationship link information.
         *
         * <pre>
         * {
         *     name: link name,
         *     bean: reference to the related bean
         *     isNew: flag indicating that it is a new relationship
         * }
         * </pre>
         *
         * The `link.isNew` flag is used to distinguish between an existing
         * relationship and a relationship that is about to be created. Please
         * refer to REST API specification for details. In brief, REST API
         * supports creating a new relationship for two existing records as
         * well as updating an existing relationship (updating relationship
         * fields). The `link.isNew` flag equals to `true` by default. The flag
         * is set to `false` by data manager once a relationship is created and
         * whenever relationships are fetched from the server.
         *
         * @memberOf Data/Bean
         * @type {Object}
         * @name link
         * @instance
         */
        beanOrId2.link = {
            name: link,
            bean: bean1,
            isNew: true
        };

        beanOrId2.setOption('relate', true);

        return beanOrId2;
    },

    /**
     * Creates a new instance of related bean collection.
     *
     * ```
     * // Create contacts collection for an existing opportunity.
     * const DataManager = require('data/data-manager');
     * var contacts = DataManager.createRelatedCollection(opportunity, 'contacts');
     * contacts.fetch({ relate: true });
     *
     * ```
     *
     * The newly created collection is cached in the given bean instance.
     *
     * @param {Data/Bean} bean Bean to link the related beans to.
     * @param {string} link Relationship link name.
     * @param {Array|Data/BeanCollection} [models] An array of related beans to
     *   populate the newly created collection with.
     * @return {Data/BeanCollection} The created bean collection.
     */
    createRelatedCollection: function(bean, link, models) {
        var relatedModule = this.getRelatedModule(bean.module, link);
        var collection = this.createBeanCollection(relatedModule, models, {
            /**
             * Link information.
             *
             * <pre>
             * {
             *     name: link name,
             *     bean: reference to the related bean
             * }
             * </pre>
             *
             * @memberOf Data/BeanCollection
             * @instance
             */
            link: {
                name: link,
                bean: bean
            }
        });

        collection.setOption('relate', true);

        bean._setRelatedCollection(link, collection);
        return collection;
    },

    /**
     * Creates a collection of beans of different modules.
     *
     * @param {Array|Data/BeanCollection} [models] A list of models to
     *   populate the new collection with.
     * @param {Object} [options] A hash of options.
     * @return {Data/MixedBeanCollection} Collection of mixed modules
     *   collection.
     */
    createMixedBeanCollection: function(models, options) {
        return new DataManager.mixedBeanCollection(models, options);
    },

    /**
     * Checks if a given module can have multiple relationships via a given
     * link.
     *
     * @param {string} module Name of the module to do the check for.
     * @param {string} link Relationship link name.
     * @return {boolean} `true` if the module's link is a
     *   `many`-type relationship, `false` otherwise.
     */
    canHaveMany: function(module, link) {
        var meta = SUGAR.App.metadata.getModule(module);
        if (!meta || !meta.fields || !meta.fields[link]) {
            return false;
        }
        if (meta.fields[link].link_type) {
            return meta.fields[link].link_type === 'many';
        }
        var name = meta.fields[link].relationship;
        var relationship = SUGAR.App.metadata.getRelationship(name);
        var t = relationship.relationship_type.split('-');
        var type;
        if (meta.fields[link].side) {
            type = meta.fields[link].side === 'left' ? t[0] : t[2];
        } else if (relationship.lhs_module !== relationship.rhs_module) {
            type = module === relationship.rhs_module ? t[0] : t[2];
        } else {
            type = (t[0] === 'many' || t[2] === 'many') ? 'many' : 'one';
        }
        return type === 'many';
    },

    /**
     * Gets fields of type `relate` for a given link.
     *
     * Example:
     *
     * Assumptions:
     * - We have 2 modules `Accounts` and `Cases`.
     * - `Accounts` has a link field named `cases`, which represents a 1-to-many
     *   relationship between the 2 modules: A case is linked to one account and
     *   a account can be linked to many cases.
     *
     * To retrieve the field that matches this relationship on the Cases side,
     * you can call:
     * ```
     * var relateField = DataManager.getRelateField('Accounts', 'cases');
     * ```
     * and you would get the definition of the Cases field that exposes the
     * parent Account bean.
     *
     * @param {string} parentModuleName Name of the module that has a link field
     *   named `link`.
     * @param {string} link Link name.
     * @return {Array} Definitions of the `relate` fields if found or empty
     *   array if not found.
     */
    getRelateFields: function(parentModuleName, link) {
        //Overridden to provide safeguard against exception filed in NOMAD-2979
        //We couldn't reproduce the issue or find out exact place where data/metadata is inconsistent
        var parentModuleDef = SUGAR.App.metadata.getModule(parentModuleName);
        if (!parentModuleDef.fields[link]) {
            SUGAR.App.logger.error(`Calling 'getRelateFields' on '${parentModuleName}' with link '${link}' but no fields` +
                ` have been found. Please fix your metadata.`);
            return [];
        }

        var relationship = parentModuleDef.fields[link].relationship;
        var relatedModule = this.getRelatedModule(parentModuleName, link);
        var fields = SUGAR.App.metadata.getModule(relatedModule).fields;

        // Find the opposite link field on related module
        var f = _.find(fields, function(field) {
            return field.type == "link" && field.relationship == relationship;
        });

        if (f) {
            f = _.filter(fields, function(field) {
                return field.type == "relate" && field.link == f.name;
            });
        }

        return f || [];
    },

    /**
     * Gets relationship fields for a complex relationship.
     *
     * Some relationships may have relationship fields, that only makes sense in
     * the context of the relationship between 2 modules.
     * Use the the following, to get the relationships fields definition of a
     * relationship:
     * ```
     * let relationshipFields = DataManager.getRelationshipFields('Opportunities', 'contacts');
     * ```
     *
     * @param {string} parentModule Name of the module that has a link field
     *   called `link`.
     * @param {string} link Link name.
     * @return {Array} Relationship fields.
     * @deprecated
     */
    getRelationshipFields: function(parentModule, link) {
        SUGAR.App.logger.warn('DataManager#getRelationshipFields is deprecated since 7.10. It will be removed ' +
            'in a future release. Please use DataManager#getRelationFields instead');
        var ff = null;
        var linkField = SUGAR.App.metadata.getModule(parentModule).fields[link];
        if (linkField.rel_fields) {
            var relationship = linkField.relationship;
            var relatedModule = this.getRelatedModule(parentModule, link);
            var fields = SUGAR.App.metadata.getModule(relatedModule).fields;

            // Find the opposite link field on related module
            var f = _.find(fields, function(field) {
                return field.type == "link" && field.relationship == relationship;
            });

            // Find relationship_info field
            if (f) {
                f = _.find(fields, function(field) {
                    return field.link == f.name && field.link_type == "relationship_info";
                });
            }

            // Extract relationship fields
            if (f && f.relationship_fields) {
                var fieldNames = _.keys(linkField.rel_fields);
                _.each(f.relationship_fields, function(field, name) {
                    if (_.include(fieldNames, name)) {
                        if (!ff) ff = [];
                        ff.push(field);
                    }
                });
            }
        }

        return ff;

    },

    /**
     * Gets relationship fields for a complex relationship.
     *
     * Some relationships may have relationship fields, that only makes sense in
     * the context of the relationship between 2 modules.
     * Use the the following, to get the relationships fields definition of a
     * relationship:
     * ```
     * let relationshipFields = DataManager.getRelationFields('Opportunities', 'contacts');
     * ```
     *
     * @param {string} module Name of the module that has a link field
     *   called `link`.
     * @param {string} link Link name.
     * @return {Array|boolean} Relationship fields. `false` if no fields are
     *   found.
     */
    getRelationFields: function(module, link) {
        let moduleFields = SUGAR.App.metadata.getModule(module) && SUGAR.App.metadata.getModule(module).fields;
        if (!moduleFields) {
            return false;
        }

        let fields = _.filter(moduleFields, (field) => {
            return field.link === link && !_.isUndefined(field.rname_link);
        });

        if (!fields) {
            return false;
        }

        return _.pluck(fields, 'name');
    },

    /**
     * Given a module and a link field name, this method gets the link field
     * name of the other module of the relationship.
     *
     * @param {string} module The module name.
     * @param {string} link The link name for the given module.
     * @return {string|boolean} oppositeLink The link field name of the other module
     *   of the relationship. `false` if not found.
     */
    getOppositeLink: function(module, link) {
        let relationshipName = getRelationshipName(module, link);
        let relatedModule = this.getRelatedModule(module, link);
        let relatedVardefs = SUGAR.App.metadata.getModule(relatedModule);
        if (!relatedVardefs || !relatedVardefs.fields) {
            return false;
        }

        let oppositeLink = _.findWhere(relatedVardefs.fields, {type: 'link', relationship: relationshipName});

        if (!oppositeLink) {
            return false;
        }

        return oppositeLink.name;
    },

    /**
     * Gets related module name.
     *
     * @param {string} module Name of the parent module.
     * @param {string} link Relationship link name.
     * @return {string|boolean} The name of the related module. `false` if not
     *   found.
     */
    getRelatedModule: function(module, link) {
        var meta = SUGAR.App.metadata.getModule(module);
        if (!meta || !meta.fields || !meta.fields[link]) {
            return false;
        }

        let relationship = SUGAR.App.metadata.getRelationship(getRelationshipName(module, link));
        if (!relationship) {
            return meta.fields[link].module || false;
        }

        return meta.fields[link].module || (module === relationship.rhs_module ?
            relationship.lhs_module : relationship.rhs_module);
    },

    /*
     * Returns field definition of a given field.
     *
     * @param {string} module The module the field belongs to.
     * @param {string} idFieldName Name of the field to retrieve.
     */
    getRelatedNameField: function(module, idFieldName) {
        return _.find(SUGAR.App.metadata.getModule(module).fields, function(field) {
            if (field.name !== idFieldName && field.id_name === idFieldName) {
                return field;
            }
        }, this);
    },

    /**
     * Gets editable fields.
     *
     * @param {Data/Bean|Data/BeanCollection} model The bean or collection to
     *   get fields from.
     * @param {Array} [fields] Field names to be checked.
     * @return {Object} Hash of editable fields.
     */
    getEditableFields: function(model, fields) {
        var editableFields = ['id'], //Always have the id included (without the id, the routing will not work correctly)
            ignoreTypeList = ["parent", "relate"];

        fields = fields || [];

        // No fields were specified, try the model's attributes instead
        if (!fields.length) {
            fields = _.keys(model.attributes);
        }

        // Editable fields are fields that are either DB fields, such as
        // name, or related fields that do have a real DB field behind them,
        // such as opportunity_role (contact_role), that the user has access to edit.
        // The following code will filter out fields such as assigned_user_name.
        _.each(fields, function(fieldName) {
            var fieldValue;

            if(model.has(fieldName) && // Model has that field AND
                (model.fields[fieldName] && // Field exists in the model AND
                    (!model.fields[fieldName].source || // (The field does not have a source specified OR
                        model.fields[fieldName].source !== 'non-db' || // the field's source is something other than 'non-db' OR
                        !model.fields[fieldName].type || // The field does not have a field type specified OR
                        ignoreTypeList.indexOf(model.fields[fieldName].type) === -1)) && // The field's source is 'non-db', but the field's type is not in our ignore list) AND
                Acl.hasAccessToModel("edit", model, fieldName)) { // The user has access to edit the field

                fieldValue = model.get(fieldName);
                //FIXME: This if condition is deprecated. It relies on a
                //format created by VirtualCollection Mango plugin which
                //sidecar does not know about and needs to be be removed.
                if (fieldValue && (model.fields[fieldName].type === 'collection') &&
                    !_.isEmpty(fieldValue.links)) {
                    _.each(fieldValue.links, function(link) {
                        editableFields.push(link.link.name);
                    });
                } else {
                    editableFields.push(fieldName);
                }
            }
        });

        return model.toJSON({
            fields: editableFields
        });
    },

    /**
     * Custom implementation of `Backbone.sync` pattern. Syncs models with
     * the remote server using {@link SUGAR.Api}.
     *
     * @param {string} method The CRUD method: 'create', 'read', 'update', or
     *   'delete'.
     * @param {Data/Bean|Data/BeanCollection} model The bean/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @return {Sugar.HttpRequest|boolean} The sync request, or `false` if you
     *   attempted to load a linked context with no id on a linked bean.
     * @fires <b>data:sync:start</b> globally and on the bean, before the
     *   sync call is made.
     */
    sync: function(method, model, options) {
        SUGAR.App.logger.trace('data-sync-' + (options.relate ? 'relate-' : '') + method + ": " + model);
        options       = this.parseOptionsForSync(method, model, options);
        var callbacks = this.getSyncCallbacks(method, model, options),
            request = null;

        model.dataFetched = false;
        // trigger global data:sync:start event
        this.trigger("data:sync:start", method, model, options);

        /**
         * Fires on model when the sync operation starts.
         *
         * Two parameters are passed to the callback:
         *
         *  * operation name (`method`)
         *  * options
         *
         * ```
         * model.on('data:sync:start', function(method, options) {
         *     SUGAR.App.logger.debug('Started operation ' + method + ' on ' + model);
         * });
         * ```
         * @event data:sync:start
         */
        model.trigger("data:sync:start", method, options);

        if (_.isFunction(options.endpoint)) {
            request = options.endpoint(
                method,
                model,
                options,
                callbacks
            );
        }
        else if (model.link && model.link.bean && options.relate === true) {
            // Related data is an object should contain:
            // - related bean (including relationship fields) in case of create method
            // - just relationship fields in case of update method
            // - null for read/delete method
            var relatedData = null;
            if (method == "create" || method == "update") {
                // Pass all fields: bean fields + relationship fields
                relatedData = this.getEditableFields(model, options.fields);
                // Change 'update' method to 'create' if the relationship is a new one
                if (method == "update" && model.link.isNew) {
                    method = "create";
                }
            }

            if (!model.link.bean.id) {
                SUGAR.App.logger.error('Attempted to load linked context with no id on the linked bean: ' +
                    model.link.bean);
                return false;
            }

            request = SUGAR.App.api.relationships(
                method,
                model.link.bean.module,
                {
                    id: model.link.bean.id,
                    link: model.link.name,
                    relatedId: model.id,
                    related: relatedData
                },
                options.params,
                callbacks,
                options.apiOptions
            );
        }
        else if (options.favorite) {

            request = SUGAR.App.api.favorite(
                model.module,
                model.id,
                model.isFavorite(),
                callbacks,
                options.apiOptions
            );
        } else if (options.following) {
            request = SUGAR.App.api.follow(
                model.module,
                model.id,
                model.get('following'),
                callbacks,
                options.apiOptions
            );
        } else if (options.collectionField) {
            request = SUGAR.App.api.collection(
                options.module,
                {
                    id: options.beanId,
                    field: options.collectionField
                },
                options.params,
                callbacks,
                options.apiOptions
            );
        } else {
            // Use global search API whenever a query is specified or a mixed collection is used
            if (options.query || (model instanceof MixedBeanCollection)) {
                request = SUGAR.App.api.search(
                    options.params,
                    callbacks,
                    options.apiOptions
                );
            }
            else if (model.module) {
                request = SUGAR.App.api.records(
                    method,
                    model.module,
                    method == "update" || method == "create" ? this.getEditableFields(model, options.fields) : model.attributes,
                    options.params,
                    callbacks,
                    options.apiOptions
                );
            } else {
                SUGAR.App.logger.error("Unable to sync model with no module");
            }
        }

        return request;
    },

    /**
     * Builds and returns the options to pass to the `sync` request.
     *
     * @param  {string} method The CRUD method.
     * @param {Data/Bean|Data/BeanCollection} model The bean/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @return {Object} A hash of options.
     */
    parseOptionsForSync: function(method, model, options) {
        options = options || {};
        options.params = _.extend({}, options.params);
        options.filterDef = options.filter || model.filterDef;
        options.method = method;

        if (options.view && _.isString(options.view) && method == "read") {
            options.params.view = options.view;
        }

        if (options.fields && method == "read") {
            options.params.fields = options.fields.join(",");
        }

        // Track as recently viewed
        if(options.viewed === true){
            options.params.viewed = "1";
        }

        if ((method == "read") && (model instanceof BeanCollection)) {
            if (options.offset && options.offset !== 0) {
                options.params.offset = options.offset;
            }

            if (options.limit || (SUGAR.App.config && SUGAR.App.config.maxQueryResult)) {
                options.params.max_num = options.limit || SUGAR.App.config.maxQueryResult;
            }

            if (model.orderBy && model.orderBy.field) {
                options.params.order_by = model.orderBy.field + ":" + model.orderBy.direction;
            }

            if (options.myItems === true) {
                options.params.my_items = "1";
            }

            if (options.favorites === true) {
                options.params.favorites = "1";
            }

            if (!_.isEmpty(options.filterDef)) {
                var filterDef = Utils.deepCopy(options.filterDef);

                // We want to assign to params.filter the filter definition
                // itself (the value assigned to the "filter" key).
                if (_.has(filterDef, "filter")) {
                    filterDef = filterDef.filter;
                }

                if (!_.isArray(filterDef)) {
                    filterDef = [filterDef];
                }
                options.params.filter = filterDef;
            }

            if (!_.isEmpty(options.query)) {
                options.params.q = options.query;
                if (_.isEmpty(options.module_list) && model.module) {
                    // Set module list to be the collection's module
                    options.module_list = [model.module];
                }
            }

            if (options.module_list) {
                options.params.module_list = options.module_list.join(",");
            }
        }

        if ((method === 'update') && (options.lastModified)) {
            options.apiOptions = options.apiOptions || {};
            options.apiOptions.headers = options.apiOptions.headers || {};
            options.apiOptions.headers['X-TIMESTAMP'] = options.lastModified;
        }

        return options;
    },

    /**
     * Gets the `sync` callback functions.
     *
     * @param  {string} method The CRUD method.
     * @param {Data/Bean|Data/BeanCollection} model The bean/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @return {Object} A hash containing the fallowing callback functions:
     *   'success', 'error', 'complete', 'abort'.
     */
    getSyncCallbacks: function(method, model, options) {
        return {
            success: this.getSyncSuccessCallback(method, model, options),
            error: this.getSyncErrorCallback(method, model, options),
            complete: this.getSyncCompleteCallback(method, model, options),
            abort: this.getSyncAbortCallback(method, model, options)
        };
    },

    /**
     * Gets the `success` callback function for the {@link #sync} method.
     *
     * @param  {string} method The CRUD method.
     * @param {Data/Bean|Data/BeanCollection} model The bean/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @return {Function} The `success` callback function.
     * @fires <b>data:sync:success</b> globally and on the bean, once the
     *   sync call is made and is successful.
     */
    getSyncSuccessCallback: function(method, model, options) {
        var self = this;
        return function(data, request) {
            model.inSync = true;
            model.original_assigned_user_id = model.get("assigned_user_id");
            if ((method == "read") && (model instanceof BeanCollection)) {
                var offset = options.offset || 0;
                data = data || {};

                if (_.isNumber(data.next_offset)) {
                    model.offset = data.next_offset != -1 ? data.next_offset : offset + (data.records || []).length;
                    model.next_offset = data.next_offset;
                    model.page = model.getPageNumber(options);
                }

                if (!options.update) {
                    // We need to invalidate the cached version of the total
                    // when a reset is fired on the collection from actions
                    // such as filtering. If however, the total is being
                    // populated from the server response, that will be
                    // used instead.
                    model.total = _.isNumber(data.total) ? data.total : null;
                }

                if (model instanceof MixedBeanCollection) {
                    // We need `xmod_aggs` property which are the facets
                    // for search.
                    model.xmod_aggs = data.xmod_aggs || null;
                    model.tags = data.tags || null;

                    // In a case of a collection field fetch, `next_offset` is
                    // an object containing the next_offset of each link.
                    if (_.isObject(data.next_offset)) {
                        model.offset = data.next_offset;
                        model.next_offset = data.next_offset;
                    }
                }

                data = data.records || [];

                // Update collection filter/search properties on success
                self._updateCollectionProperties(model, options);
            }

            if (options.relate === true) {
                // Reset the flag to indicate that relationship(s) do exist.
                model.link.isNew = false;

                if (method != "read") {
                    // The response for create/update/delete relationship contains updated beans
                    if (model.link.bean) {
                        var syncedAttributes = model.link.bean.getSynced(),
                            updatedAttributes = _.reduce(data.record, function(memo, val, key) {
                                if (!_.isEqual(syncedAttributes[key], val)) {
                                    memo[key] = val;
                                }
                                return memo;
                            }, {});
                        model.link.bean.set(updatedAttributes);
                        //Once parent model is reset, reset internal synced attributes as well
                        model.link.bean.setSyncedAttributes(data.record);
                    }

                    data = data.related_record;
                    // Attributes will be set automatically for create/update but not for delete
                    // Also, break the link
                    if (method == "delete") {
                        model.set(data);
                        delete model.link;
                    }
                }
            }

            model.dataFetched = true;
            if (options.success) options.success(data);
            // trigger global data:sync:success event
            self.trigger("data:sync:success", method, model, options, request);
            model.inSync = null;
        };

    },

    /**
     * Gets the `error` callback function for the sync {@link #sync} method.
     *
     * Triggers the global `data:sync:complete` event (registered on
     * {@link Core.Events SUGAR.App.events}), as well as on the `model`.
     *
     * Executes the abort callback if we are aborting from a previous
     * collection fetch request.
     *
     * @param {string} method The CRUD method.
     * @param {Data/Bean|Data/BeanCollection} model The model/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @param {Object} [options.error] Custom `error` callback function to be
     *   executed.
     * @return {Function} The wrapped `error` callback function.
     * @fires <b>data:sync:error</b> globally and on the bean, if the
     *   sync call returned an error.
     */
    getSyncErrorCallback: function(method, model, options) {
        return _.bind(function(error) {
            if (error.request && error.request.aborted) {
                var abortCallback = this.getSyncAbortCallback(method, model, options);
                return abortCallback(error.request);
            }
            SUGAR.App.error.handleHttpError(error, model, options);
            this.trigger('data:sync:error', method, model, options, error);
            /**
             * Fires on model when the sync operation ends unsuccessfully.
             *
             * Three parameters are passed to the callback:
             *
             *  - operation name (`method`)
             *  - options
             *  - error {@link SUGAR.Api.HttpError}
             *
             * ```
             * model.on('data:sync:error', function(method, options, error) {
             *     SUGAR.App.logger.debug('Operation failed:' + method + ' on ' + model);
             * });
             * ```
             *
             * @event data:sync:error
             */
            model.trigger('data:sync:error', method, options, error);

            if (_.isFunction(options.error)) {
                options.error(error);
            }
        }, this);
    },

    /**
     * Gets the `complete` callback function for the sync {@link #sync} method.
     *
     * @param {string} method The CRUD method.
     * @param {Data/Bean|Data/BeanCollection} model The bean/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @param {Object} [options.complete] Custom `complete` callback
     *   function to be executed.
     * @return {Function} The wrapped `complete` callback function.
     * @fires <b>data:sync:complete</b> globally and on the bean/collection,
     *   once the sync call was complete.
     */
    getSyncCompleteCallback: function(method, model, options) {
        return _.bind(function(request) {
            this.trigger('data:sync:complete', method, model, options, request);
            /**
             * Fires on model when the sync operation ends.
             *
             * Three parameters are passed to the callback:
             *
             *  - operation name (`method`)
             *  - options
             *  - request {@link SUGAR.Api.HttpRequest}
             *
             * ```
             * model.on('data:sync:complete', function(method, options, request) {
             *     SUGAR.App.logger.debug('Finished operation ' + method + ' on ' + model);
             * });
             * ```
             *
             * @event data:sync:complete
             */
            model.trigger('data:sync:complete', method, options, request);

            if (_.isFunction(options.complete)) {
                options.complete(request);
            }

            // Prevent memory leaking
            options.previousModels = null;
            options = {};
        }, this);
    },

    /**
     * Gets the `abort` callback function for the sync {@link #sync} method.
     *
     * @param {string} method The CRUD method.
     * @param {Data/Bean|Data/BeanCollection} model The model/collection to
     *   be synced/read.
     * @param {Object} [options] A hash of options.
     * @param {Object} [options.abort] Custom `abort` callback
     *   function to be executed.
     * @return {Function} The wrapped `abort` callback function.
     * @fires <b>data:sync:abort</b> globally and on the bean/collection, if the
     *   sync request was aborted.
     */
    getSyncAbortCallback: function(method, model, options) {
        return _.bind(function(request) {
            this._updateCollectionProperties(model, options);
            this.trigger('data:sync:abort', method, model, options, request);
            /**
             * Fires on model when the sync operation ends.
             *
             * Three parameters are passed to the callback:
             *
             *  - operation name (`method`)
             *  - options
             *  - request {@link SUGAR.Api.HttpRequest}
             *
             * ```
             * model.on('data:sync:abort', function(method, options, request) {
             *     SUGAR.App.logger.debug('Operation aborted ' + method + ' on ' + model);
             * });
             * ```
             *
             * @event data:sync:abort
             */
            model.trigger('data:sync:abort', method, options, request);
        }, this);
    },

    /**
     * Updates various properties on the bean collection passed.
     *
     * @param {Data/BeanCollection} model The collection.
     * @param {Object} [options] A hash of options.
     * @private
     */
    _updateCollectionProperties: function (model, options) {
        options = options || {};

        /**
         * Flag indicating if a collection contains items assigned to the
         * current user.
         *
         * @memberOf Data/BeanCollection
         * @type {boolean}
         * @name myItems
         * @instance
         * @readonly
         */
        model.myItems = options.myItems;

        /**
         * Flag indicating if a collection contains current user's favorite
         * items.
         *
         * @memberOf Data/BeanCollection
         * @type {boolean}
         * @name favorites
         * @instance
         * @readonly
         */
        model.favorites = options.favorites;

        /**
         * Search query.
         *
         * @memberOf Data/BeanCollection
         * @type {string}
         * @name query
         * @instance
         * @readonly
         */
        model.query = options.query;

        /**
         * List of modules searched.
         *
         * @memberOf Data/MixedBeanCollection
         * @type {string}
         * @name modelList
         * @instance
         * @readonly
         */
        model.modelList = options.modelList;

        /**
         * Filter definition to filter the collection by.
         *
         * @memberOf Data/BeanCollection
         * @type {Array}
         * @name filterDef
         * @instance
         */
        model.filterDef = options.filterDef;
    }
}, Backbone.Events);

module.exports = DataManager;
