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
 * Relate field provides a link to a module that is set in the definition of
 * this field metadata.
 *
 * This field requires at least the follow definitions to be exist in the
 * field:
 *
 * ```
 * array(
 *     'name' => 'account_name',
 *     'rname' => 'name',
 *     'id_name' => 'account_id',
 *     'module' => 'Accounts',
 *     'link' => true,
 *     //...
 * ),
 * ```
 *
 * The field also support a `populate_list` to update other fields in the
 * current model from other fields of the selected model.
 *
 * ```
 * array(
 *     //...
 *     'populate_list' => array(
 *         'populate_list' => array(
 *         'billing_address_street' => 'primary_address_street',
 *         'billing_address_city' => 'primary_address_city',
 *         'billing_address_state' => 'primary_address_state',
 *         'billing_address_postalcode' => 'primary_address_postalcode',
 *         'billing_address_country' => 'primary_address_country',
 *         'phone_office' => 'phone_work',
 *         //...
 *
 *     ),
 * )
 * ```
 *
 * This field allows you to configure the minimum chars that trigger a search
 * when using the typeahead feature.
 *
 * ```
 * array(
 *     //...
 *     'minChars' => 3,
 * )
 * ```
 *
 * TODO: we have a mix of properties here with camelCase and underscore.
 * Needs to be addressed.
 *
 * @class View.Fields.Base.RelateField
 * @alias SUGAR.App.view.fields.BaseRelateField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldTag: 'input.select2',

    /**
     * Initializes field and binds all function calls to this
     * @param {Object} options
     */
    initialize: function(options) {
        /**
         * Boolean used for the 'allowClear' select2 option.
         *
         * @property {boolean}
         * @protected
         */
        this._allow_single_deselect = true;
        /**
         * Minimum input characters to trigger the search. Used for
         * `minimumInputLength` select2 option.
         *
         * @property {number}
         * @protected
         */
        this._minChars = options.def.minChars || 1;
        /**
         * Separator used by select2 to separate values. Used for `separator`
         * select2 option.
         *
         * @property {string}
         * @protected
         */
        this._separator = '|';
        /**
         * Maximum number of records the user can select.
         *
         * @property {number}
         * @protected
         */
        this._maxSelectedRecords = 20;

        if (_.property('link')(options.def) && !_.isBoolean(options.def.link)) {
            app.logger.warn('The `link` property passed in the viewDefs must be a boolean. Hence, `link`' +
                ' will be set to `true` by default.');
        }

        this._super('initialize', [options]);

        // A relate field displays a link by default.
        this.viewDefs = _.defaults(this.viewDefs || {}, {link: true});
        /**
         * The template used for a pill in case of multiselect field.
         *
         * @property {Function}
         * @private
         */
        this._select2formatSelectionTemplate = app.template.getField('relate', 'pill', this.module);

        var populateMetadata = this._getPopulateMetadata();

        if (_.isEmpty(populateMetadata)) {
            return;
        }
        _.each(this.def.populate_list, function(target, source) {
            if (_.isUndefined(populateMetadata.fields[source])) {
                app.logger.error('Fail to populate the related attributes: attempt to access undefined key - ' +
                this.getSearchModule() + '::' + source);
            }
        }, this);

        this._createSearchCollection();
    },

    /**
     * Gets the proper module's metadata to use during auto populating fields
     *
     * @return {*|Object}
     * @protected
     */
    _getPopulateMetadata: function() {
        return app.metadata.getModule(this.getSearchModule());
    },

    /**
     * Creates a Filters BeanCollection to easily apply filters.
     * The user must have `list` access to the search module to create a
     * {@link Data.Base.FiltersBeanCollection}.
     *
     * @protected
     */
    _createFiltersCollection: function(options) {
        var searchModule = this.getSearchModule();

        if (!app.acl.hasAccess('list', searchModule)) {
            app.logger.debug('No "list" access to ' + searchModule + ' so skipping the creation of filter.');
            return;
        }

        if (app.metadata.getModule('Filters') && searchModule) {
            this.filters = app.data.createBeanCollection('Filters');
            this.filters.setModuleName(searchModule);
            this.filters.setFilterOptions(this.getFilterOptions());
            this.filters.load(options);
        }
    },
    /**
     * Creates a {@link Data.BeanCollection} for the search results pertaining
     * to the search module.
     *
     * @protected
     */
    _createSearchCollection: function() {
        var searchModule = this.getSearchModule();
        if (searchModule && app.metadata.getModule(searchModule)) {
            this.searchCollection = app.data.createBeanCollection(searchModule);
        } else {
            this.searchCollection = null;
        }
    },

    /**
     * Bind the additional keydown handler on select2
     * search element (affected by version 3.4.3).
     *
     * Invoked from {@link app.plugins.Editable}.
     * @param {Function} callback Callback function for keydown.
     */
    bindKeyDown: function(callback) {
        var $dropdown = this.$(this.fieldTag);
        $dropdown.on('keydown.record', {field: this}, callback);
        var plugin = $dropdown.data('select2');
        if (plugin) {
            plugin.focusser.on('keydown.record', {field: this}, callback);
            plugin.search.on('keydown.record', {field: this}, callback);
        }
    },

    /**
     * Unbind the additional keydown handler.
     *
     * Invoked from {@link app.plugins.Editable}.
     * @param {Function} callback Callback function for keydown.
     */
    unbindKeyDown: function(callback) {
        var $dropdown = this.$(this.fieldTag);
        $dropdown.off('keydown.record', callback);
        var plugin = $dropdown.data('select2');
        if (plugin) {
            plugin.search.off('keydown.record', callback);
        }
    },

    focus: function() {
        if (this.action !== 'disabled') {
            //Need to defer to ensure that all the related elements have finished
            //rendering before attempting to open the dropdown.
            _.defer(_.bind(function() {
                this.$(this.fieldTag).first().select2('open');
            }, this));
        }
    },

    /**
     * //FIXME: We shouldn't have this method. SC-4121 will address this.
     * Creates the css classes to set to the select2 plugin.
     *
     * @return {string}
     * @private
     */
    _buildCssClasses: function() {
        var cssClasses = [];
        if (this.view.name === 'recordlist') {
            cssClasses.push('select2-narrow');
        }
        if (this.type === 'parent') {
            cssClasses.push('select2-parent');
        }
        if (this.def.isMultiSelect) {
            cssClasses.push('select2-choices-pills-close same-size-pills');
        }
        return cssClasses.join(' ');
    },

    /**
     * Renders relate field
     */
    _render: function() {
        var searchModule = this.getSearchModule();
        this._updateErasedPlaceholder();

        this._super('_render');

        //FIXME remove check for tplName SC-2608
        switch(this.tplName) {
            case 'edit':
            case 'massupdate':
                // `searchModule` can be undefined for a parent field when there
                // is no value set (ie in create mode). In that case, we don't
                // want to render the dropdown disabled.
                if (!_.isUndefined(searchModule)) {
                    if (!app.acl.hasAccess('list', searchModule) ||
                        !_.contains(app.metadata.getModuleNames(), searchModule)) {
                        this._renderDisabledDropdown();
                        break;
                    }
                }
                if (_.isUndefined(this.filters)) {
                    this._createFiltersCollection({
                        success: _.bind(function() {
                            if (!this.disposed) {
                                this._renderEditableDropdown();
                            }
                        }, this)
                    });
                } else {
                    this._renderEditableDropdown();
                }
                break;
            case 'disabled':
                this._renderDisabledDropdown();
                break;
        }
        return this;
    },

    /**
     * Renders the editable dropdown using the `select2` plugin.
     *
     * Since a filter may have to be applied on the field, we need to fetch
     * the list of filters for the current module before rendering the dropdown
     * (and enabling the searchahead feature that requires the filter
     * definition).
     *
     * @private
     */
    _renderEditableDropdown: function() {
        var self = this;
        var $dropdown = this.$(this.fieldTag);

        $dropdown.select2(this._getSelect2Options())
            .on('select2-open', _.bind(this._onSelect2Open, this))
            .on('searchmore', function() {
                $(this).select2('close');
                self.openSelectDrawer();
            })
            .on('change', _.bind(this._onSelect2Change, this));

        var plugin = $dropdown.data('select2');
        if (plugin && plugin.focusser) {
            plugin.focusser.on('select2-focus', _.bind(_.debounce(this.handleFocus, 0), this));
        }
    },

    /**
     * Renders the dropdown in disabled mode.
     *
     * @private
     */
    _renderDisabledDropdown: function() {
        var loadingLabel = app.lang.get('LBL_LOADING', this.module);
        var $dropdown = this.$(this.fieldTag);

        $dropdown.select2({
            width: '100%',
            initSelection: function(el, callback) {
                var $el = $(el),
                    id = $el.val(),
                    text = $el.data('rname');
                callback({id: id, text: text});
            },
            formatInputTooShort: function() {
                return '';
            },
            formatSearching: function() {
                return loadingLabel;
            },
            placeholder: this.getPlaceHolder(),
            allowClear: this._allow_single_deselect,
            minimumInputLength: this._minChars,
            query: _.bind(this.search, this)
        });
        $dropdown.select2('disable');
    },

    /**
     * Handler for when the select2 dropdown change event
     *
     * @param e
     * @private
     */
    _onSelect2Change: function(e) {
        var $el = $(e.target);
        var plugin = $el.data('select2');
        var id = e.val;

        if (_.isUndefined(id)) {
            return;
        }

        // For multiselect fields, we update the data-rname attributes
        // so it stays in sync with the id list, and allows us to use
        // 'setValue' method. The use of 'setValue' method is required
        // to re-render the field.
        if (this.def.isMultiSelect) {
            var dataRname = plugin.opts.element.data('rname');
            dataRname = dataRname ? dataRname.split(this._separator) : [];
            var ids = $el.select2('val');

            if (e.added) {
                dataRname.push(e.added.text);
            } else if (e.removed) {
                dataRname = _.without(dataRname, e.removed.text);
            } else {
                return;
            }
            var models = _.map(ids, function(id, index) {
                return {id: id, value: dataRname[index]};
            });

            this.setValue(models);
            return;
        }

        var value = (id) ? plugin.selection.find('span').text() : $el.data('rname');
        var collection = plugin.context;
        var attributes = {};
        if (collection && !_.isEmpty(id)) {
            // if we have search results use that to set new values
            var model = collection.get(id);
            attributes.id = model.id;
            attributes.value = model.get(this.getRelatedModuleField());
            _.each(model.attributes, function(value, field) {
                if (app.acl.hasAccessToModel('view', model, field)) {
                    attributes[field] = attributes[field] || model.get(field);
                }
            });
        } else if (e.currentTarget.value && value) {
            // if we have previous values keep them
            attributes.id = value;
            attributes.value = e.currentTarget.value;
        } else {
            // default to empty
            attributes.id = '';
            attributes.value = '';
        }

        this.setValue(attributes);
    },

    /**
     * Entry point for child classes to hook in and add/update to the base select2 options
     *
     * @return {{}}
     * @protected
     */
    _getSelect2Options: function() {
        return _.extend({}, {
            width: this.view.name === 'recordlist' ? 'off' : '100%',
            dropdownCssClass: _.bind(this._buildCssClasses, this),
            multiple: !!this.def.isMultiSelect,
            containerCssClass: _.bind(this._buildCssClasses, this),
            separator: this._separator,
            initSelection: _.bind(this._onInitSelect, this),
            formatInputTooShort: function() {
                return '';
            },
            formatSelection: _.bind(this._onFormatSelection, this),
            formatSearching: app.lang.get('LBL_LOADING', this.module),
            placeholder: this.getPlaceHolder(),
            allowClear: this._allow_single_deselect,
            minimumInputLength: this._minChars,
            maximumSelectionSize: 20,
            query: _.bind(this.search, this)
        });
    },

    /**
     * Callback for select2 `initSelection` property.
     *
     * @param {HTMLElement} el The select2 element that stores values.
     * @param {Function} callback select2 callback to initialize the plugin.
     * @private
     */
    _onInitSelect: function(el, callback) {
        var $el = $(el),
            id = $el.val(),
            text = $el.data('rname');

        if (!this.def.isMultiSelect) {
            return callback({id: id, text: text});
        }
        var ids = id.split(this._separator);
        text = text.split(this._separator);
        callback(_.map(ids, function(value, index) {
            return {id: value, text: text[index]};
        }));
    },

    /**
     * Callback for select2 `formatSelection` property.
     *
     * @param {Object} obj object containing the item name.
     * @return {string} A string containing template for a pill.
     *
     * @private
    */
    _onFormatSelection: function(obj) {
        var ctx = {};
        //TODO We should investigate why it's sometimes `text` and
        //sometimes `id` and make it always same if possible.
        ctx.text = obj.text || obj.id;
        return this._select2formatSelectionTemplate(ctx);
    },

    /**
     * Callback when select2 plugin opens.
     * @private
     * @param {Event} e The `click` event.
     */
    _onSelect2Open: function(e) {
        var plugin = this.$(e.currentTarget).data('select2');
        if (plugin.searchmore) {
            return;
        }
        var label = app.lang.get('LBL_SEARCH_AND_SELECT_ELLIPSIS', this.module);
        var $tpl = $('<div/>').addClass('select2-result-label').html(label);
        var onMouseDown = function() {
            plugin.opts.element.trigger($.Event('searchmore'));
            plugin.close();
        };
        var $content = $('<li class="select2-result">').append($tpl).mousedown(onMouseDown);
        plugin.searchmore = $('<ul class="select2-results">').append($content);
        plugin.dropdown.append(plugin.searchmore);
    },

    /**
     * Builds the route for the relate module's record.
     * @param {String} module The related module.
     * @param {String} id The record id to link to.
     *
     * TODO since base.js has a build href, we should try to reuse code or
     * extend this one from other "link" field
     */
    buildRoute: function(module, id) {
        if (_.isUndefined(id) || !this.viewDefs.link) {
            return;
        }

        var oldModule = module;
        // This is a workaround until bug 61478 is resolved to keep parity with 6.7
        if (module === 'Users' && this.context.get('module') !== 'Users') {
            module = 'Employees';
        }

        if (_.isEmpty(module)) {
            return;
        }

        var relatedRecord = this.model.get(this.fieldDefs.link);
        var action = this.viewDefs.route ? this.viewDefs.route.action : 'view';

        if (relatedRecord && app.acl.hasAccess(action, oldModule, {acls: relatedRecord._acl})) {
            this.href = '#' + app.router.buildRoute(module, id);
            //FIXME SC-6128 will remove this deprecated block.
        } else if (!relatedRecord) {
            this.href = this.href = '#' + app.router.buildRoute(module, id);
        } else {
            // if no access to module, remove the href
            this.href = undefined;
        }
    },

    // Derived controllers can override these if related module and id in another
    // place.
    _buildRoute: function () {
        this.buildRoute(this.getSearchModule(), this._getRelateId());
    },
    _getRelateId: function () {
        return this.model.get(this.def.id_name);
    },

    /**
     * @inheritdoc
     *
     * When there is no value set and we are in a create view, we try to check
     * if the parent context module matches this relate field. If it matches,
     * we pre-populate with that data.
     *
     * FIXME: the relate field should use this method to pre-populate the
     * values without touching the model or else we need to use silent to
     * prevent the warning of unsaved changes, consequently we can't bind
     * events like `change` to it.
     *
     * TODO: the model might not have the field that we are relating to. On
     * those corner cases, we need to fetch from the server that information.
     *
     * @return {String} This field's value. Need to change to object with all
     *   data that we need to render the field.
     */
    format: function(value) {

        var parentCtx = this.context && this.context.parent,
            setFromCtx;

        if (value) {
            /**
             * Flag to indicate that the value has been set from the context
             * once, so if later the value is unset, we don't set it again on
             * {@link #format}.
             *
             * @type {boolean}
             * @protected
             */
            this._valueSetOnce = true;
        }

        // This check sees if we should populate the field from the context.
        // Note that this is a different condition from if we should populate
        // the field from a parent model.
        //
        // Also note that readonly fields are not automatically populated from
        // the context.
        setFromCtx = value === null && !this.fieldDefs.readonly &&
            !this._valueSetOnce && parentCtx && _.isEmpty(this.context.get('model').link) &&
            this.view instanceof app.view.views.BaseCreateView &&
            parentCtx.get('module') === this.def.module &&
            this.module !== this.def.module;

        if (setFromCtx) {
            this._valueSetOnce = true;
            var model = parentCtx.get('model');
            // FIXME we need a method to prevent us from doing this
            this.def.auto_populate = true;
            // FIXME the setValue receives a model but not a backbone model...
            this.setValue(model.toJSON());
            // FIXME we need to iterate over the populated_ that is causing
            // unsaved warnings when doing the auto populate.
        }

        if (!this.def.isMultiSelect && this.action !== 'edit' && !this.context.get('create')) {
            this._buildRoute();
        }

        var idList = this.model.get(this.def.id_name);
        if (_.isArray(value)) {
            this.formattedRname = value.join(this._separator);
            this.formattedIds = idList.join(this._separator);
        } else {
            this.formattedRname = value;
            this.formattedIds = idList;
        }

        return value;
    },

    /**
     * Sets the value in the field.
     *
     * @param {Object|Array} models The source models attributes.
     */
    setValue: function(models) {
        if (!models) {
            return;
        }
        var isErased = false;
        var updateRelatedFields = true,
            values = {};
        if (_.isArray(models)) {
            // Does not make sense to update related fields if we selected
            // multiple models
            updateRelatedFields = false;
        } else {
            models = [models];
        }

        values[this.def.id_name] = [];
        values[this.def.name] = [];
        if (this.fieldDefs.link) {
            values[this.fieldDefs.link] = [];
        }

        _.each(models, _.bind(function(model) {
            values[this.def.id_name].push(model.id);
            //FIXME SC-4196 will fix the fallback to `formatNameLocale` for person type models.
            values[this.def.name].push(model[this.getRelatedModuleField()] ||
                app.utils.formatNameLocale(model) || model.value);
            if (this.fieldDefs.link) {
                values[this.fieldDefs.link].push(model);
            } else {
                isErased = app.utils.isNameErased(app.data.createBean(model._module, model));
            }
        }, this));

        // If it's not a multiselect relate, we get rid of the array.
        if (!this.def.isMultiSelect) {
            values[this.def.id_name] = values[this.def.id_name][0];
            values[this.def.name] = values[this.def.name][0];
            if (this.fieldDefs.link) {
                values[this.fieldDefs.link] = values[this.fieldDefs.link][0];
            } else {
                this._nameIsErased = isErased;
            }
        }

        //In case of selecting an erased value twice, we need to force a re-render to show the erased placeolder.
        var forceUpdate = _.isEmpty(this.model.get(this.def.name)) && _.isEmpty(values[this.def.name]);

        this.model.set(values);

        if (updateRelatedFields) {
            //Force an update to the link field as well so that SugarLogic and other listeners can react
            if (this.fieldDefs.link && _.isEmpty(values[this.fieldDefs.link]) && forceUpdate) {
                this.model.trigger('change:' + this.fieldDefs.link);
            }
            this.updateRelatedFields(models[0]);
        }

        if (forceUpdate) {
            this._updateField();
        }
    },

    _updateErasedPlaceholder: function() {
        //Handle erased field placehilders
        // Show a custom placeholder if the field's content has been erased
        if (this._isErasedField()) {
            this.hasErasedPlaceholder = true;
        } else {
            this.hasErasedPlaceholder = false;
        }
    },

    /**
     * Handles update of related fields.
     *
     * @param {Object} model The source model attributes.
     */
    updateRelatedFields: function(model) {
        var newData = {},
            self = this;
        _.each(this.def.populate_list, function(target, source) {
            source = _.isNumber(source) ? target : source;
            if (!_.isUndefined(model[source]) && app.acl.hasAccessToModel('edit', this.model, target)) {
                var before = this.model.get(target),
                    after = model[source];

                if (before !== after) {
                    newData[target] = model[source];
                }
            }
        }, this);

        if (_.isEmpty(newData)) {
            return;
        }

        // if this.def.auto_populate is true set new data and doesn't show alert message
        if (!_.isUndefined(this.def.auto_populate) && this.def.auto_populate == true) {
            // if we have a currency_id, set it first to trigger the currency conversion before setting
            // the values to the model, this prevents double conversion from happening
            if (!_.isUndefined(newData.currency_id)) {
                this.model.set({currency_id: newData.currency_id});
                delete newData.currency_id;
            }
            this.model.set(newData);
            return;
        }

        // load template key for confirmation message from defs or use default
        var messageTplKey = this.def.populate_confirm_label || 'TPL_OVERWRITE_POPULATED_DATA_CONFIRM',
            messageTpl = Handlebars.compile(app.lang.get(messageTplKey, this.getSearchModule())),
            fieldMessageTpl = app.template.getField(
                this.type,
                'overwrite-confirmation',
                this.model.module),
            messages = [],
            relatedModuleSingular = app.lang.getModuleName(this.def.module);

        _.each(newData, function(value, field) {
            var before = this.model.get(field),
                after = value;

            if (before !== after) {
                var def = this.model.fields[field];
                messages.push(fieldMessageTpl({
                    before: before,
                    after: after,
                    field_label: app.lang.get(def.label || def.vname || field, this.module)
                }));
            }
        }, this);

        app.alert.show('overwrite_confirmation', {
            level: 'confirmation',
            messages: messageTpl({
                values: new Handlebars.SafeString(messages.join(', ')),
                moduleSingularLower: relatedModuleSingular.toLowerCase()
            }),
            onConfirm: function() {
                // if we have a currency_id, set it first to trigger the currency conversion before setting
                // the values to the model, this prevents double conversion from happening
                if (!_.isUndefined(newData.currency_id)) {
                    self.model.set({currency_id: newData.currency_id});
                    delete newData.currency_id;
                }
                self.model.set(newData);
            }
        });
    },

    /**
     * @override
     */
    _isErasedField: function() {
        if (!this.model) {
            return false;
        }

        var def = this.fieldDefs;
        var link = this.model.get(def.link);

        if (link) {
            var recordField = app.metadata.getField({
                module: def.module,
                name: def.rname
            });

            if (recordField && recordField.type === 'fullname') {
                return app.utils.isNameErased(app.data.createBean(def.module, link));
            } else {
                return _.contains(link._erased_fields, def.rname);
            }
        } else {
            return this._nameIsErased || this._super('_isErasedField');
        }
    },

    /**
     * Opens the selection drawer.
     *
     * Note that if the field definitions have a `filter_relate` property, it
     * will open the drawer and filter by this relate field.
     *
     *     @example a Revenue Line Item is associated to an account and to an
     *      opportunity. If I want to open a drawer to select an opportunity
     *      with an initial filter that filters opportunities by the account
     *      associated to the revenue line item, in the field definitions I can
     *      specify:
     *      ```
     *      'filter_relate' => array(
     *          'account_id' => 'account_id',
     *      ),
     *      ```
     *      The key is the field name in the Revenue Line Items record,
     *      the value is the field name in the Opportunities record.
     */
    openSelectDrawer: function() {
        var layout = 'selection-list';
        var context = {
            module: this.getSearchModule(),
            fields: this.getSearchFields(),
            filterOptions: this.getFilterOptions()
        };

        if (!!this.def.isMultiSelect) {
            layout = 'multi-selection-list';
            _.extend(context, {
                preselectedModelIds: _.clone(this.model.get(this.def.id_name)),
                maxSelectedRecords: this._maxSelectedRecords,
                isMultiSelect: true
            });
        }

        app.drawer.open({
            layout: layout,
            context: context
        }, _.bind(this.setValue, this));
    },

    /**
     * Gets the list of fields to search by in the related module.
     *
     * @return {Array} The list of fields.
     */
    getSearchFields: function() {
        return _.union(['id', this.getRelatedModuleField()], _.keys(this.def.populate_list || {}));
    },

    /**
     * Gets the related field name in the related module record.
     *
     * Falls back to `name` if not defined.
     *
     * @return {String} The field name.
     */
    getRelatedModuleField: function() {
        return this.def.rname || 'name';
    },

    /**
     * @inheritdoc
     *
     * We need this empty so it won't affect refresh the select2 plugin
     */
    bindDomChange: function () {
    },

    /**
     * Gets the correct module to search based on field/link defs.
     *
     * If both `this.def.module` and `link.module` are empty, fall back onto the
     * metadata manager to get the proper module as a last resort.
     *
     * @return {String} The module to search on.
     */
    getSearchModule: function () {
        // If we have a module property on this field, use it
        if (this.def.module) {
            return this.def.module;
        }

        // No module in the field def, so check if there is a module in the def
        // for the link field
        var link = this.fieldDefs.link && this.model.fields && this.model.fields[this.fieldDefs.link] || {};
        if (link.module) {
            return link.module;
        }

        // At this point neither the def nor link field def have a module... let
        // metadata manager try find it
        return app.data.getRelatedModule(this.model.module, this.fieldDefs.link);
    },
    getPlaceHolder: function () {
        var searchModule = this.getSearchModule(),
            searchModuleLower = searchModule.toLocaleLowerCase(),
            module = app.lang.getModuleName(searchModule, {defaultValue: searchModuleLower});

        return app.lang.get('LBL_SEARCH_SELECT_MODULE', this.module, {
            module: new Handlebars.SafeString(module)
        });
    },

    /**
     * Formats the filter options.
     *
     * @param {Boolean} force `true` to force retrieving the filter options
     *   whether or not it is available in memory.
     * @return {Object} The filter options.
     */
    getFilterOptions: function(force) {
        if (this._filterOptions && !force) {
            return this._filterOptions;
        }
        this._filterOptions = new app.utils.FilterOptions()
            .config(this.def)
            .setInitialFilter(this.def.initial_filter || '$relate')
            .populateRelate(this.model)
            .format();
        return this._filterOptions;
    },

    /**
     * Builds the filter definition to pass to the request when doing a quick
     * search.
     *
     * It will combine the filter definition for the search term with the
     * initial filter definition. Both are optional, so this method may return
     * an empty filter definition (empty `array`).
     *
     * @param {String} searchTerm The term typed in the quick search field.
     * @return {Array} The filter definition.
     */
    buildFilterDefinition: function(searchTerm) {
        if (!app.metadata.getModule('Filters') || !this.filters) {
            return [];
        }
        var filterBeanClass = app.data.getBeanClass('Filters').prototype,
            filterOptions = this.getFilterOptions() || {},
            filter = this.filters.collection.get(filterOptions.initial_filter),
            filterDef,
            populate,
            searchTermFilter,
            searchModule;

        if (filter) {
            populate = filter.get('is_template') && filterOptions.filter_populate;
            filterDef = filterBeanClass.populateFilterDefinition(filter.get('filter_definition') || {}, populate);
            searchModule = filter.moduleName;
        }

        searchTermFilter = filterBeanClass.buildSearchTermFilter(searchModule || this.getSearchModule(), searchTerm);

        return filterBeanClass.combineFilterDefinitions(filterDef, searchTermFilter);
    },

    /**
     * Searches for related field.
     * @param event
     */
    search: _.debounce(function(query) {
        var term = query.term || '',
            self = this,
            searchModule = this.getSearchModule(),
            params = {},
            limit = self.def.limit || 5,
            relatedModuleField = this.getRelatedModuleField();

        if (query.context) {
            params.offset = this.searchCollection.next_offset;
        }
        params.filter = this.buildFilterDefinition(term);

        this.searchCollection.fetch({
            //Don't show alerts for this request
            showAlerts: false,
            update: true,
            remove: _.isUndefined(params.offset),
            reset: _.isUndefined(params.offset),
            fields: this.getSearchFields(),
            context: self,
            params: params,
            limit: limit,
            success: function(data) {
                var fetch = {results: [], more: data.next_offset > 0, context: data};
                if (fetch.more) {
                    var fieldEl = self.$(self.fieldTag),
                    //For teamset widget, we should specify which index element to be filled in
                        plugin = (fieldEl.length > 1) ? $(fieldEl.get(self._currentIndex)).data("select2") : fieldEl.data("select2"),
                        height = plugin.searchmore.children("li:first").children(":first").outerHeight(),
                    //0.2 makes scroll not to touch the bottom line which avoid fetching next record set
                        maxHeight = height * (limit - .2);
                    plugin.results.css("max-height", maxHeight);
                }
                _.each(data.models, function (model, index) {
                    if (params.offset && index < params.offset) {
                        return;
                    }
                    fetch.results.push({
                        id: model.id,
                        text: model.get(relatedModuleField) + ''
                    });
                });
                if (query.callback && _.isFunction(query.callback)) {
                    query.callback(fetch);
                }
            },
            error: function() {
                if (query.callback && _.isFunction(query.callback)) {
                    query.callback({results: []});
                }
                app.logger.error("Unable to fetch the bean collection.");
            }
        });
    }, app.config.requiredElapsed || 500),

    /**
     * @inheritdoc
     * Avoid rendering process on select2 change in order to keep focus.
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change', function() {
                this.getFilterOptions(true);
            }, this);

            this.model.on('change:' + this.name, this._updateField, this);
        }
    },

    _updateField: function() {
        if (this.disposed) {
            return;
        }
        var $dropdown = this.$(this.fieldTag);
        if (!_.isEmpty($dropdown.data('select2'))) {
            var value = this.model.get(this.def.name);
            value = _.isArray(value) ? value.join(this._separator) : value;
            value = value ? value.trim() : value;
            if (this._isErasedField()) {
                value = app.lang.getAppString('LBL_VALUE_ERASED');
            }

            $dropdown.data('rname', value);

            // `id` can be an array of ids if the field is a multiselect.
            var id = this.model.get(this.def.id_name);
            if (_.isEqual($dropdown.select2('val'), id)) {
                return;
            }

            $dropdown.select2('val', id);
        } else {
            this.render();
        }
    },


    unbindDom: function() {
        this.$(this.fieldTag).select2('destroy');
        app.view.Field.prototype.unbindDom.call(this);
    }

})
