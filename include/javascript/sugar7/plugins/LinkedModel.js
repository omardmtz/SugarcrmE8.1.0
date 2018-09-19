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
    app.events.on('app:init', function() {
        app.plugins.register('LinkedModel', ['view', 'field'], {

            /**
             * Create a new linked Bean model which is related to the parent
             * bean model. It populates related fields from the parent bean
             * if it's already fetched.
             * All related fields are defined in the relationship metadata.
             * If the related field contains the auto-populated fields,
             * it also copies the auto-populate fields.
             *
             * @param {Object} parentModel Parent Bean Model.
             * @param {String} link Name of relationship link.
             * @return {Object} A new instance of the related or regular bean.
             */
            createLinkModel: function(parentModel, link) {
                if (this.context && this.context.parent && this.context.parent.isCreate())
                {
                    return app.data.createBean(app.data.getRelatedModule(parentModel.module, link));
                }

                var model = app.data.createRelatedBean(parentModel, null, link),
                    relatedFields = app.data.getRelateFields(parentModel.module, link),
                    parentModule = app.metadata.getModule(parentModel.module);

                if (parentModule && parentModule.fields[link] && parentModule.fields[link].populate_list) {
                    model.relatedAttributes = model.relatedAttributes || {};
                    this._parsePopulateList(parentModule.fields[link].populate_list, parentModel, model);
                }

                if (!_.isEmpty(relatedFields)) {
                    model.relatedAttributes = model.relatedAttributes || {};
                    _.each(relatedFields, function(field) {
                        var attrs = {};
                        var parentValue = parentModel.get(field.rname);
                        if (!parentValue && parentModel.fields[field.rname] &&
                            parentModel.fields[field.rname].type == 'fullname'
                        ) {
                            parentValue = parentModel.get('full_name')
                                || app.utils.formatNameLocale(parentModel.attributes);
                        }
                        attrs[field.name] = parentValue;
                        attrs[field.id_name] = parentModel.get('id');
                        if (field.link) {
                            attrs[field.link] = parentModel.toJSON();
                        }
                        model.set(attrs);
                        model.relatedAttributes[field.name] = parentModel.get(field.rname);
                        model.relatedAttributes[field.id_name] = parentModel.get('id');

                        if (field.populate_list) {
                            this._parsePopulateList(field.populate_list, parentModel, model);
                        }
                    }, this);
                }
                this.populateParentFields(model, parentModel);

                return model;
            },

            /**
             * Utility Method to parse a populate_list array of fields
             *
             * @param {Array|Object} populate_list
             * @param {Backbone.Model} parentModel
             * @param {Backbone.Model} model
             * @private
             */
            _parsePopulateList: function(populate_list, parentModel, model) {
                _.each(populate_list, function(target, source) {
                    source = _.isNumber(source) ? target : source;
                    if (
                        !_.isUndefined(parentModel.get(source)) &&
                        app.acl.hasAccessToModel('edit', model, target)
                    ) {
                        model.set(target, parentModel.get(source));
                        model.relatedAttributes[target] = parentModel.get(source);
                    }
                }, this);
            },

            /**
             * Event handler for the create button that launches UI for
             * creating a related record.
             * For sidecar modules, this means a drawer is opened with the
             * create dialog inside.
             * For BWC modules, this means we trigger a create route to enter
             * BWC mode.
             *
             * @param {String} module Module name.
             */
            createRelatedRecord: function(module, link) {
                var bwcExceptions = ['Emails'],
                    moduleMeta = app.metadata.getModule(module);

                if (moduleMeta && moduleMeta.isBwcEnabled && !_.contains(bwcExceptions, module)) {
                    this.routeToBwcCreate(module);
                } else {
                    this.openCreateDrawer(module, link);
                }
            },

            /**
             * Route to Create Related record UI for a BWC module.
             *
             * @param {String} module Module name.
             */
            routeToBwcCreate: function(module) {
                var proto = Object.getPrototypeOf(this);
                if (_.isFunction(proto.routeToBwcCreate)) {
                    return proto.routeToBwcCreate.call(this, module);
                }
                var parentModel = this.context.parent.get('model'),
                    link = this.context.get('link');
                app.bwc.createRelatedRecord(module, parentModel, link);
            },

            /**
             * For sidecar modules, we create new records by launching
             * a create drawer UI.
             *
             * @param {String} module Module name.
             * @param {String} link Link name.
             */
            openCreateDrawer: function(module, link) {
                var proto = Object.getPrototypeOf(this);
                if (_.isFunction(proto.openCreateDrawer)) {
                    return proto.openCreateDrawer.call(this, module, link);
                }
                link = link || this.context.get('link');
                //FIXME: `this.context` should always be used - SC-2550
                var context = (this.context.get('name') === 'tabbed-dashlet') ?
                    this.context : (this.context.parent || this.context);
                var parentModel = context.get('model') || context.parent.get('model'),
                    model = this.createLinkModel(parentModel, link),
                    self = this;
                app.drawer.open({
                    layout: 'create',
                    context: {
                        create: true,
                        module: model.module,
                        model: model
                    }
                }, function(context, model) {
                    if (!model) {
                        return;
                    }

                    self.trigger('linked-model:create', model);
                });
            },

            //If this is being created through a subpanel or dashlet as a child of another record
            //default to populating the parent field as that record
            populateParentFields: function(model, parentModel) {
                var parentModule = parentModel.module || parentModel.get("module") || parentModel.get("_module");
                _.each(model.fields, function(def) {
                    if (def.type && def.type === 'parent') {
                        if (app.lang.getAppListStrings(def.options)[parentModule]) {
                            var attributes = {};
                            attributes[def.type_name] = parentModule;
                            if (parentModel.get('id')) {
                                attributes[def.id_name] = parentModel.get('id');
                                attributes[def.name] = app.utils.getRecordName(parentModel);
                                attributes.parent = parentModel.toJSON();
                            }
                            model.set(attributes);
                        }
                    }
                });

                //Special case for contacts. Notes should attach to the account rather than the contact
                if (parentModule == "Contacts" && parentModel.get("account_id") && model.get("contact_id")) {
                    model.set({
                        parent_type : "Accounts",
                        parent_id : parentModel.get("account_id"),
                        parent_name: parentModel.get('account_name')
                    });
                }
            }
        });
    });
})(SUGAR.App);
