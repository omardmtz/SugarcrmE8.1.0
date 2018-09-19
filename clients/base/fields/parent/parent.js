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
 * @class View.Fields.Base.ParentField
 * @alias SUGAR.App.view.fields.BaseParentField
 * @extends View.Fields.Base.RelateField
 */
({
    extendsFrom: 'RelateField',
    fieldTag: 'input.select2[name=parent_name]',
    typeFieldTag: 'select.select2[name=parent_type]',
    plugins: ['FieldDuplicate'],

    /**
     * @inheritDoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        //FIXME: BR-4575 will remove this.
        this.fieldDefs = _.extend(this.fieldDefs || {}, {link: 'parent'});

        /**
         * A hash of available modules in the parent type dropdown matching
         * the modules names with their label.
         *
         * @property {Object}
         * @private
         */
        this._modules = app.lang.getAppListStrings(this.def.options);

        /**
         * The hash of available modules in the parent type dropdown, filtered
         * according to list acls.
         *
         * @property {Object}
         */
        this.moduleList = {};

        this._filterModuleList(this._modules);
    },

    _isErasedField: function() {
        if (this.model && this.model.has('parent')) {
            var link = this.model.get('parent');
            if (_.isEmpty(link._erased_fields)) {
                return false;
            }
            var parentType = link.parent_type || this.model.get('parent_type');
            if (parentType) {
                return app.utils.isNameErased(app.data.createBean(parentType, link));
            }
        }

        return false;
    },

    /**
     * Calls {@link View.Fields.Base.RelateField#_render} and renders the select2
     * module dropdown.
     *
     * @inheritDoc
     */
    _render: function() {
        var self = this;
        var moduleName = this.getSearchModule();
        var module = _.pick(this._modules, moduleName);
        if (module && !app.acl.hasAccess('list', moduleName)) {
            this.noAccessModule = {key: moduleName, value: module[moduleName]};
        }

        this._super("_render");

        /**
         * Only allow modification of the select2 functionality under the specified templates
         */
        var allowedTpls = ['edit', 'massupdate'];
        if (_.contains(allowedTpls, this.tplName)) {
            var inList = (this.view.name === 'recordlist') ? true : false;

            this.$(this.typeFieldTag).select2({
                dropdownCssClass: inList?'select2-narrow':'',
                containerCssClass: inList?'select2-narrow':'',
                width: inList?'off':'100%',
                minimumResultsForSearch: 5
            }).on('change', function(e) {
                var module = e.val;
                if (self.noAccessModule && module !== self.noAccessModule) {
                    delete self.noAccessModule;
                }
                self.setValue({
                    id: '',
                    value: '',
                    module: module
                });
                self.$(self.fieldTag).select2('val', '');
            }).on('select2-focus', _.bind(_.debounce(this.handleFocus, 0), this));

            var domParentTypeVal = this.$(this.typeFieldTag).val();
            if(this.model.get(this.def.type_name) !== domParentTypeVal) {
                this.model.setDefault(this.def.type_name, domParentTypeVal);
                this._createSearchCollection();
            }

            if(app.acl.hasAccessToModel('edit', this.model, this.name) === false) {
                this.$(this.typeFieldTag).select2("disable");
            } else {
                this.$(this.typeFieldTag).select2("enable");
            }
        } else if(this.tplName === 'disabled'){
            this.$(this.typeFieldTag).select2('disable');
        }
        return this;
    },
    _getRelateId: function() {
         return this.model.get("parent_id");
     },
    format: function(value) {
        var module;
        this.def.module = this.getSearchModule();

        if (this.def.module) {
            module = app.lang.getModuleName(this.def.module);
        }

        this.context.set('record_label', {
            field: this.name,
            label: (this.tplName === 'detail') ? module : app.lang.get(this.def.label, this.module)
        });

        var parentCtx = this.context && this.context.parent,
            setFromCtx;

        if (value) {
            this._valueSetOnce = true;
        }

        setFromCtx = !value && !this._valueSetOnce && parentCtx && _.isEmpty(this.context.get('model').link) &&
            this.view instanceof app.view.views.BaseCreateView &&
            _.contains(_.keys(app.lang.getAppListStrings(this.def.parent_type)), parentCtx.get('module')) &&
            this.module !== this.def.module;

        if (setFromCtx) {
            this._valueSetOnce = true;
            var model = parentCtx.get('model');
            // FIXME we need a method to prevent us from doing this
            // FIXME the setValue receives a model but not a backbone model...
            var attributes = model.toJSON();
            attributes.silent = true;
            this.setValue(attributes);
            value = this.model.get(this.name);

            // FIXME we need to iterate over the populated_ that isn't working now
        }

        return this._super('format', [value]);

    },

    /**
     * Filters the module list according to list acls.
     *
     * @param {Object} A hash of module names matching with their label.
     * @private
     */
    _filterModuleList: function(modules) {
        var filteredModules = _.filter(_.keys(modules), function(module) {
            return app.acl.hasAccess('list', module);
        });
        this.moduleList = _.pick(modules, filteredModules);
    },

    /**
     * @override
     */
    setValue: function(models) {
        if (!models) {
            return;
        }
        models = _.isArray(models) ? models : [models];
        _.each(models, _.bind(function(model) {

            var silent = model.silent || false,
            // FIXME we shouldn't make this assumption and this method should
            // receive a true Backbone.Model or Data.Bean
                module = model.module || model._module;


            if (app.acl.hasAccessToModel(this.action, this.model, this.name)) {
                if (module) {
                    this.model.set('parent_type', module, {silent: silent});
                    this._createSearchCollection();
                }
                // only set when we have an id on the model, as setting undefined
                // is causing issues with the warnUnsavedChanges() method
                if (!_.isUndefined(model.id)) {
                    // FIXME we shouldn't rely on model.value... and hack the full_name here until we fix it properly
                    // SC-4196 will fix this.
                    var value = model.value || model[this.def.rname || 'name'] || model['full_name'] ||
                        app.utils.formatNameLocale(model);
                    var forceUpdate = _.isEmpty(this.model.get(this.def.name)) && _.isEmpty(value);
                    this.model.set(
                        {
                            'parent_id': model.id,
                            'parent_name': value,
                            'parent': model
                        },
                        {silent: silent}
                    );
                    if (forceUpdate) {
                        this._updateField();
                    }
                }
            }
        }, this));


        // TODO we should support the auto populate of other fields like we do on normal relate.js
    },
    /**
     * Is this module available as an option to be set as parent type?
     * @param module {string}
     * @return {boolean}
     */
    isAvailableParentType: function(module) {
        var moduleFound = _.find(this.$(this.typeFieldTag).find('option'), function(dom) {
            return $(dom).val() === module;
        });
        return !!moduleFound;
    },
    getSearchModule: function() {
        return this.model.get('parent_type') || this.$(this.typeFieldTag).val();
    },
    getPlaceHolder: function() {
        return  app.lang.get('LBL_SEARCH_SELECT', this.module);
    },
    unbindDom: function() {
        this.$(this.typeFieldTag).select2('destroy');
        this._super("unbindDom");
    },

    /**
     * @inheritdoc
     * Avoid rendering process on select2 change in order to keep focus.
     */
    bindDataChange: function() {
        this._super('bindDataChange');
        if (this.model) {
            this.model.on('change:parent_type', function() {
                var plugin = this.$(this.typeFieldTag).data('select2');
                if (_.isEmpty(plugin) || !plugin.searchmore) {
                    this.render();
                } else {
                    this.$(this.typeFieldTag).select2('val', this.model.get('parent_type'));
                }
            }, this);
        }
    },

    /**
     * Handler to refresh search collection when merging duplicates.
     *
     * Called from {@link app.plugins.FieldDuplicate#_onFieldDuplicate}
     */
    onFieldDuplicate: function() {
        if (_.isEmpty(this.searchCollection) ||
            this.searchCollection.module !== this.getSearchModule()
        ) {
            this._createSearchCollection();
        }
    },

    /**
     * We do not support this field for preview edit
     * @inheritdoc
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');

        if (this.view.name === 'preview' && this.action !== 'erased') {
            this.template = app.template.getField('parent', 'detail', this.model.module);
        }
    }
})
