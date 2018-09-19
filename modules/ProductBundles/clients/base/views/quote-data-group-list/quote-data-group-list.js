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
 * @class View.Views.Base.ProductBundles.QuoteDataGroupListView
 * @alias SUGAR.App.view.views.BaseProductBundlesQuoteDataGroupListView
 * @extends View.Views.Base.View
 */
({
    /**
     * @inheritdoc
     */
    events: {
        'click [name="edit_row_button"]': '_onEditRowBtnClicked',
        'click [name="delete_row_button"]': '_onDeleteRowBtnClicked'
    },

    /**
     * @inheritdoc
     */
    plugins: [
        'Editable',
        'ErrorDecoration',
        'MassCollection',
        'SugarLogic',
        'QuotesLineNumHelper'
    ],

    /**
     * @inheritdoc
     */
    className: 'quote-data-group-list',

    /**
     * Array of fields to use in the template
     */
    _fields: undefined,

    /**
     * The colspan value for the list
     */
    listColSpan: 0,

    /**
     * The colspan value for empty rows listColSpan + 1 since no left column
     */
    emptyListColSpan: 0,

    /**
     * Array of left column fields
     */
    leftColumns: undefined,

    /**
     * Array of left column fields
     */
    leftSaveCancelColumn: undefined,

    /**
     * List of current inline edit models.
     */
    toggledModels: null,

    /**
     * Object containing the row's fields
     */
    rowFields: {},

    /**
     * ProductBundleNotes QuoteDataGroupList metadata
     */
    pbnListMetadata: undefined,

    /**
     * QuotedLineItems QuoteDataGroupList metadata
     */
    qliListMetadata: undefined,

    /**
     * ProductBundleNotes Description field metadata
     */
    pbnDescriptionMetadata: undefined,

    /**
     * Track all the SugarLogic Contexts that we create for each record in bundle
     *
     * @type {Object}
     */
    sugarLogicContexts: {},

    /**
     * Track the module dependencies for the line item, so we dont have to fetch them every time
     *
     * @type {Object}
     */
    moduleDependencies: {},

    /**
     * If this QuoteDataGroupList is the default group list view, or regular header/footer group view
     */
    isDefaultGroupList: undefined,

    /**
     * If this view is currently in the /create view or not
     */
    isCreateView: undefined,

    /**
     * If this view is in the /create view coming from Opportunities Convert to Quote
     */
    isOppsConvert: undefined,

    /**
     * In Convert to Quote, if the RLI models have been added to the Quote yet
     */
    addedConvertModels: undefined,

    /**
     * CSS Classes for sortable rows
     */
    sortableCSSClass: 'sortable ui-sortable',

    /**
     * CSS Classes for non-sortable rows
     */
    nonSortableCSSClass: 'not-sortable',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var parentModelModule;
        this.pbnListMetadata = app.metadata.getView('ProductBundleNotes', 'quote-data-group-list');
        this.qliListMetadata = app.metadata.getView('Products', 'quote-data-group-list');

        this.pbnDescriptionMetadata = _.find(this.pbnListMetadata.panels[0].fields, function(field) {
            return field.name === 'description';
        }, this);

        // make sure we're using the layout's model
        options.model = options.model || options.layout.model;
        // get the product_bundle_items collection from the model
        options.collection = options.model.get('product_bundle_items');

        // use the same massCollection from the Quotes QuoteDataListHeaderView
        var quoteDataListHeaderComp;
        if (options.layout && options.layout.layout) {
            quoteDataListHeaderComp =  options.layout.layout.getComponent('quote-data-list-header');
            if (quoteDataListHeaderComp) {
                options.context.set('mass_collection', quoteDataListHeaderComp.massCollection);
            }
        }

        this.listColSpan = options.layout.listColSpan;
        this.emptyListColSpan = this.listColSpan + 1;

        this._super('initialize', [options]);

        this.isDefaultGroupList = this.model.get('default_group');

        this.isCreateView = this.context.parent.get('create') || false;

        parentModelModule = this.context.parent.get('parentModel') ?
            this.context.parent.get('parentModel').get('_module') : '';

        this.isOppsConvert = this.isCreateView &&
            this.context.parent.get('convert') &&
            (parentModelModule == 'RevenueLineItems' ||
            parentModelModule == 'Opportunities') &&
            this.context.parent.get('fromLink') != 'quotes';

        this.addedConvertModels = this.context.parent.get('addedConvertModels') || false;

        this.action = 'list';
        this.viewName = this.isCreateView ? 'edit' : 'list';

        this._fields = _.flatten(_.pluck(this.qliListMetadata.panels, 'fields'));

        this.toggledModels = {};
        this.leftColumns = [];
        this.leftSaveCancelColumn = [];
        this.addMultiSelectionAction();

        this.events = _.extend({
            'hidden.bs.dropdown .actions': 'resetDropdownDelegate',
            'shown.bs.dropdown .actions': 'delegateDropdown'
        }, this.events);

        /**
         * Due to BackboneJS, this view would have a wrapper tag around it e.g. QuoteDataGroupHeader.tagName "tr"
         * so this would have also been wrapped in div/tr whatever the tagName was for the view.
         * I am setting this.el to be the Layout's el (QuoteDataGroupLayout) which is a tbody element.
         * In the render function I am then manually appending this list of records template
         * after the group header tr row
         */
        this.el = this.layout.el;
        this.setElement(this.el);

        this.isEmptyGroup = this.collection.length === 0;

        // for each item in the collection, setup SugarLogic
        var collections = this.model.fields['product_bundle_items'].links;
        _.each(collections, function(link) {
            var collection = this.model.getRelatedCollection(link);
            if (collection) {
                this.setupSugarLogicForModelOrCollection(collection);
            }
        }, this);

        // listen directly on the parent QuoteDataGroupLayout
        this.layout.on('quotes:group:create:qli', this.onAddNewItemToGroup, this);
        this.layout.on('quotes:group:create:note', this.onAddNewItemToGroup, this);
        this.layout.on('quotes:sortable:over', this._onSortableGroupOver, this);
        this.layout.on('quotes:sortable:out', this._onSortableGroupOut, this);
        this.layout.on('editablelist:' + this.name + ':cancel', this.onCancelRowEdit, this);
        this.layout.on('editablelist:' + this.name + ':save', this.onSaveRowEdit, this);
        this.layout.on('editablelist:' + this.name + ':saving', this.onSavingRow, this);

        this.context.parent.on('quotes:collections:all:checked', this.onAllChecked, this);
        this.context.parent.on('quotes:collections:not:all:checked', this.onNotAllChecked, this);

        this.collection.on('add remove', this.onNewItemChanged, this);
    },

    /**
     * handler for when the select all checkbox is checked
     */
    onAllChecked: function() {
        //iterate over all of the masscollection checkboxes and check the ones that are unchecked.
        _.each(this.$('div.checkall input'), function(item) {
            var $item = $(item);
            //only trigger if the item isn't checked.
            if (_.isUndefined($item.attr('checked'))) {
                $item.trigger('click');
            }
        });
    },

    /**
     * handler for when the select all checkbox is unchecked
     */
    onNotAllChecked: function() {
        //iterate over all of the masscollection checkboxes and uncheck the ones that are checked.
        _.each(this.$('div.checkall input'), function(item) {
            var $item = $(item);
            //only trigger if the item IS checked.
            if (!_.isUndefined($item.attr('checked'))) {
                $item.trigger('click');
            }
        });
    },

    /**
     * Resets the dropdown css
     * @param e
     */
    resetDropdownDelegate: function(e) {
        var $b = this.$(e.currentTarget).first();
        $b.parent().closest('.action-button-wrapper').removeClass('open');
    },

    /**
     * Fixes z-index for dropdown
     * @param e
     */
    delegateDropdown: function(e) {
        var $buttonGroup = this.$(e.currentTarget).first();
        // add open class to parent list to elevate absolute z-index for iOS
        $buttonGroup.parent().closest('.action-button-wrapper').addClass('open');
    },

    /**
     * Load and cache SugarLogic dependencies for a module
     *
     * @param {Data.Bean} model
     * @return {Array}
     * @private
     */
    _getSugarLogicDependenciesForModel: function(model) {
        var module = model.module;
        if (_.isUndefined(this.moduleDependencies[module])) {
            var dependencies;
            var moduleMetadata;
            //TODO: These dependencies would normally be filtered by view action. Need to make that logic
            // external from the Sugarlogic plugin. Probably somewhere in the SidecarExpressionContext class...
            // first get the module from the metadata
            moduleMetadata = app.metadata.getModule(module) || {};
            // load any dependencies found there
            dependencies = moduleMetadata.dependencies || [];
            // now lets check the record view to see if it has any local ones on it.
            if (moduleMetadata.views && moduleMetadata.views.record) {
                var recordMetadata = moduleMetadata.views.record.meta;
                if (!_.isUndefined(recordMetadata.dependencies)) {
                    dependencies = dependencies.concat(recordMetadata.dependencies);
                }
            }

            // cache the results so we don't have to do this expensive lookup any more
            this.moduleDependencies[module] = dependencies;
        }

        return this.moduleDependencies[module];
    },

    /**
     * Setup dependencies for a specific model.
     *
     * @param {Data.Bean} model
     * @param {Data.Collection} collection
     * @param {Object} options
     */
    setupSugarLogicForModelOrCollection: function(modelOrCollection) {
        var slContext;
        var isCollection = (modelOrCollection instanceof app.data.beanCollection);
        var dependencies = this._getSugarLogicDependenciesForModel(modelOrCollection);
        if (_.size(dependencies) > 0) {
            slContext = new SUGAR.expressions.SidecarExpressionContext(
                this,
                isCollection ? new modelOrCollection.model() : modelOrCollection,
                isCollection ? modelOrCollection : false
            );
            slContext.initialize(dependencies);
            var id = isCollection ? modelOrCollection.module : modelOrCollection.get('id') || modelOrCollection.cid;
            this.sugarLogicContexts[id] = slContext;
        }
    },

    /**
     * Handler for when a new QLI/Note row has been added and then canceled
     *
     * @param {Data.Bean} rowModel The row collection model that was created and now canceled
     */
    onCancelRowEdit: function(rowModel) {
        var rowId;

        if (rowModel.isNew()) {
            rowId = rowModel.cid;
            this.collection.remove(rowModel);

            if (!_.isUndefined(this.sugarLogicContexts[rowId])) {
                // cleanup any sugarlogic contexts
                this.sugarLogicContexts[rowId].dispose();
            }

            // if we're showing line numbers, and the model we canceled was a Product
            if (this.showLineNums && rowModel.module === 'Products') {
                // reset the line_num count on the collection from QuotesLineNumHelper plugin
                this.resetGroupLineNumbers(this.model.cid, this.collection);
            }
        }

        this.onNewItemChanged();
    },

    /**
     * Handles when a row is saved. Since newly added (but not saved) rows have temporary
     * id's assigned to them, this is needed to go back and fix row id html attributes and
     * also resets the rowFields with the new model's ID so rows toggle properly
     *
     * @param {Data.Bean} rowModel
     */
    onSaveRowEdit: function(rowModel) {
        var modelId = rowModel.cid;
        var modelModule = rowModel.module;
        var quoteId = rowModel.get('quote_id');
        var productId = rowModel.get('id');
        var quoteModel;

        this.toggleCancelButton(false, rowModel.cid);
        this.toggleRow(modelModule, modelId, false);
        this.onNewItemChanged();

        // when a new row is added if it does not have quote_id already, set it
        if (rowModel.module === 'Products' && _.isUndefined(quoteId)) {
            quoteModel = this.context.get('parentModel');

            if (quoteModel) {
                quoteId = quoteModel.get('id');

                app.api.relationships('create', 'Products', {
                    id: productId,
                    link: 'quotes',
                    relatedId: quoteId,
                    related: {
                        quote_id: quoteId
                    }
                }, null, {
                    success: _.bind(function(response) {
                        var record = response.record;
                        var relatedRecord = response.related_record;
                        var pbItems = this.model.get('product_bundle_items');
                        var quoteModel = this.context.get('parentModel');

                        _.each(pbItems.models, function(itemModel) {
                            if (itemModel.get('id') === record.id) {
                                itemModel.setSyncedAttributes(record);
                                itemModel.set(record);
                            }
                        }, this);

                        if (quoteModel) {
                            quoteModel.setSyncedAttributes(relatedRecord);
                            quoteModel.set(relatedRecord);
                        }

                    }, this)
                });
            }
        }
    },

    /**
     * Handles when the row is being saved but has not been saved fully yet
     *
     * @param {boolean} disableCancelBtn If we should disable the button or not
     * @param {string} rowModelCid The model.cid of the row that is saving
     */
    onSavingRow: function(disableCancelBtn, rowModelCid) {
        // todo: SFA-4541 needs to add code in here to toggle fields to readonly
        this.toggleCancelButton(disableCancelBtn, rowModelCid);
    },

    /**
     * Toggles the cancel button disabled or not
     *
     * @param {boolean} disable If we should disable the button or not
     * @param {string} rowModelCid The model.cid of the row that needs its cancel button toggled
     */
    toggleCancelButton: function(disable, rowModelCid) {
        var cancelBtn = _.find(this.fields, function(field) {
            return field.name == 'inline-cancel' && field.model.cid === rowModelCid;
        });
        if (cancelBtn) {
            cancelBtn.setDisabled(disable);
        }
    },

    /**
     * Called when a group's Create QLI or Create Note button is clicked
     *
     * @param {Data.Bean} groupModel The ProductBundle model
     * @param {Object} prepopulateData Any data to prepopulate the model with - coming from Opps Convert
     * @param {string} linkName The link name of the new item to create: products or product_bundle_notes
     */
    onAddNewItemToGroup: function(linkName, prepopulateData) {
        var relatedModel = app.data.createRelatedBean(this.model, null, linkName);
        var quoteModel = this.context.get('parentModel');
        var maxPositionModel;
        var position = 0;
        var $relatedRow;
        var moduleName = linkName === 'products' ? 'Products' : 'ProductBundleNotes';
        var modelData = {};
        var groupLineNumObj;
        // these quoteModel values will be overwritten if prepopulateData
        // already has currency_id or base_rate already set
        var currencyId = quoteModel.get('currency_id');
        var baseRate = quoteModel.get('base_rate');
        var newLineNum;

        prepopulateData = prepopulateData || {};

        if (this.collection.length) {
            // get the model with the highest position
            maxPositionModel = _.max(this.collection.models, function(model) {
                return +model.get('position');
            });

            // get the position of the highest model's position and add one to it
            position = +maxPositionModel.get('position') + 1;
        }

        // if the data has a _module, remove it
        if (!_.isEmpty(prepopulateData)) {
            delete prepopulateData._module;
        }

        if (prepopulateData && prepopulateData._forcePosition) {
            // initialize new line_num to 0
            newLineNum = 0;

            // increment the new line number to 1 and set that on prepopulateData
            prepopulateData.line_num = ++newLineNum;

            // since we're forcing a new position, we need to update all models in the collection with
            // a new position and new line_num
            _.each(this.collection.models, function(model) {
                var pos = model.get('position');
                // if an existing model's position is >= prepopulateData's position,
                // we want to move all those positions up one so prepopulateData can fit
                if (pos >= prepopulateData.position) {
                    // increment the position by one and set on the model
                    model.set('position', ++pos);
                    this.collection._resavePositions = true;
                }
                if (this.showLineNums && model.module === 'Products') {
                    // if this is also a Product row, update the line_num for the row
                    model.set('line_num', ++newLineNum);
                }
            }, this);

            // set position to be the prepopulateData position for later
            position = prepopulateData.position;

            // remove this property
            delete prepopulateData._forcePosition;
        } else {
            // if there's no propopulate data nor _forcePosition
            if (this.showLineNums && relatedModel.module === 'Products') {
                // get the line_num count object from QuotesLineNumHelper plugin
                groupLineNumObj = this.getGroupLineNumCount(this.model.cid);
                // add the new line number to the model
                modelData.line_num = groupLineNumObj.ct++;
            }
        }

        // defers to prepopulateData
        modelData = _.extend({
            _module: moduleName,
            _link: linkName,
            position: position,
            currency_id: currencyId,
            base_rate: baseRate,
            quote_id: quoteModel.get('id')
        }, prepopulateData);

        relatedModel.module = moduleName;

        // set a few items on the model
        relatedModel.set(modelData);

        // tell the currency field, not to set the default currency
        relatedModel.ignoreUserPrefCurrency = true;

        // this model's fields should be set to render
        relatedModel.modelView = 'edit';

        // add model to toggledModels to be toggled next render
        this.toggledModels[relatedModel.cid] = relatedModel;

        // adding to the collection will trigger the render
        this.collection.add(relatedModel);

        $relatedRow = this.$('tr[name="' + relatedModel.module + '_' + relatedModel.id + '"]');
        if ($relatedRow.length) {
            if (this.isCreateView) {
                $relatedRow.addClass(this.sortableCSSClass);
            } else {
                $relatedRow.addClass(this.nonSortableCSSClass);
            }
        }

        this.onNewItemChanged();
    },

    /**
     * Handles updating if we should show the empty row when QLI/Notes have
     * been created or canceled before saving
     */
    onNewItemChanged: function() {
        this.isEmptyGroup = this.collection.length === 0;
        this.toggleEmptyRow(this.isEmptyGroup);
    },

    /**
     * Handles when this group receives a sortover event that the user
     * has dragged an item into this group
     *
     * @param {jQuery.Event} evt The jQuery sortover event
     * @param {Object} ui The jQuery Sortable UI Object
     * @private
     */
    _onSortableGroupOver: function(evt, ui) {
        // When entering a new group, always hide the empty row
        this.toggleEmptyRow(false);
    },

    /**
     * Handles when this group receives a sortout event that the user has
     * dragged an item out of this group
     *
     * @param {jQuery.Event} evt The jQuery sortout event
     * @param {Object} ui The jQuery Sortable UI Object
     * @private
     */
    _onSortableGroupOut: function(evt, ui) {
        var isSenderNull = _.isNull(ui.sender);
        var isSenderSameGroup = isSenderNull ||
            ui.sender.length && ui.sender.get(0) === this.el;

        // if the group was originally empty, show the empty row
        // if the group was not empty and had more than one row in it, hide the empty row
        var showEmptyRow = this.isEmptyGroup;

        // if there is only one item in this group, and the out event happens on a group that is the line item's
        // original group, and the existing single row is currently hidden,
        // set showEmptyRow = true so we show the Click + message
        if (this.collection.length === 1 &&
            isSenderSameGroup && $(ui.item.get(0)).css('display') === 'none') {
            showEmptyRow = true;
        }

        this.toggleEmptyRow(showEmptyRow);
    },

    /**
     * Toggles showing and hiding the empty-row message row
     *
     * @param {boolean} showEmptyRow True to show the empty row, false to hide it
     */
    toggleEmptyRow: function(showEmptyRow) {
        if (showEmptyRow) {
            this.$('.empty-row').removeClass('hidden');
        } else {
            this.$('.empty-row').addClass('hidden');
        }
    },

    /**
     * @inheritdoc
     */
    render: function() {
        this._super('render');

        // update isEmptyGroup after render and make sure we toggle the row properly
        this.isEmptyGroup = this.collection.length === 0;
        this.toggleEmptyRow(this.isEmptyGroup);
    },

    /**
     * Overriding _renderHtml to specifically place this template after the
     * quote data group header
     *
     * @inheritdoc
     */
    _renderHtml: function() {
        var $el = this.$('tr.quote-data-group-header');
        var $trs;
        if ($el.length) {
            $trs = this.$('tr.quote-data-group-list');
            if ($trs.length) {
                // if there are already quote-data-group-list table rows remove them
                $trs.remove();
            }
            $el.after(this.template(this));
        } else {
            this.$el.html(this.template(this));
        }
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var qliModels;

        this._super('_render');

        // set row fields after rendering to prep if we need to toggle rows
        this._setRowFields();

        // if this is the create view, and we're coming from Opps convert to Quote,
        // and we have not added the RLI models
        if (this.isCreateView && this.isOppsConvert && !this.addedConvertModels) {
            qliModels = this.context.parent.get('relatedRecords');

            _.each(qliModels, function(qliModel) {
                this.onAddNewItemToGroup('products', qliModel.toJSON());
            }, this);

            //be sure to set this on the parent as well so new groups don't try to do this.
            this.context.parent.set('addedConvertModels', true);
            this.addedConvertModels = true;
        }

        if (!_.isEmpty(this.toggledModels)) {
            _.each(this.toggledModels, function(model, modelId) {
                this.toggleRow(model.module, modelId, true);
            }, this);
        }
    },

    /**
     * Handles when the Delete button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onEditRowBtnClicked: function(evt) {
        var row = this.isolateRowParams(evt);

        if (!row.id || !row.module) {
            return false;
        }

        this.toggleRow(row.module, row.id, true);
    },

    /**
     * Handles when the Delete button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onDeleteRowBtnClicked: function(evt) {
        var row = this.isolateRowParams(evt);

        if (!row.id || !row.module) {
            return false;
        }

        app.alert.show('confirm_delete', {
            level: 'confirmation',
            title: app.lang.get('LBL_ALERT_TITLE_WARNING') + ':',
            messages: [app.lang.get('LBL_ALERT_CONFIRM_DELETE')],
            onConfirm: _.bind(function() {
                app.alert.show('deleting_line_item', {
                    level: 'info',
                    messages: [app.lang.get('LBL_ALERT_DELETING_ITEM', 'ProductBundles')]
                });
                this._onDeleteRowModelFromList(this.collection.get(row.id));
            }, this)
        });
    },

    /**
     * Called when deleting a row is confirmed, this removes the model
     * from the collection and resets the group's line numbers
     *
     * @param {Data.Bean} deletedRowModel The model being deleted
     * @private
     */
    _onDeleteRowModelFromList: function(deletedRowModel) {
        deletedRowModel.destroy({
            success: _.bind(function() {
                app.alert.dismiss('deleting_line_item');
                app.alert.show('deleted_line_item', {
                    level: 'success',
                    autoClose: true,
                    messages: app.lang.get('LBL_DELETED_LINE_ITEM_SUCCESS_MSG', 'ProductBundles')
                });
            }, this)
        });
        this.layout.trigger('quotes:line_nums:reset', this.layout.groupId, this.layout.collection);
    },

    /**
     * Parse out a row module and ID
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    isolateRowParams: function(evt) {
        var $ulEl = $(evt.target).closest('ul');
        var rowParams = {};

        if ($ulEl.length) {
            rowParams.module = $ulEl.data('row-module');
            rowParams.id = $ulEl.data('row-model-id');
        }

        return rowParams;
    },

    /**
     * Toggle editable selected row's model fields.
     *
     * @param {string} rowModule The row model's module.
     * @param {string} rowModelId The row model's ID
     * @param {boolean} isEdit True for edit mode, otherwise toggle back to list mode.
     */
    toggleRow: function(rowModule, rowModelId, isEdit) {
        var toggleModel;
        var $row;

        this.context.parent.trigger('quotes:item:toggle', isEdit, rowModelId);
        toggleModel = this.collection.find(function(model) {
            return (model.cid == rowModelId || model.id == rowModelId);
        });

        if (isEdit) {
            if (_.isUndefined(toggleModel)) {
                // its not there any more, so remove it from the toggledModels and return out from this method
                delete this.toggledModels[rowModelId];
                return;
            } else {
                toggleModel.modelView = 'edit';
                this.toggledModels[rowModelId] = toggleModel;
            }
        } else {
            if (this.toggledModels[rowModelId]) {
                this.toggledModels[rowModelId].modelView = 'list';
            }
            delete this.toggledModels[rowModelId];
        }

        $row = this.$('tr[name=' + rowModule + '_' + rowModelId + ']');
        $row.toggleClass('tr-inline-edit', isEdit);
        this.toggleFields(this.rowFields[rowModelId], isEdit);

        if (isEdit) {
            //disable drag/drop for this row
            $row.addClass('not-sortable');
            $row.parent().sortable({
                cancel: '.not-sortable'
            });
            $row.removeClass('ui-sortable');

            //trigger sugarlogic
            this.context.trigger('list:editrow:fire', toggleModel);
        } else if ($row.hasClass('not-sortable')) {
            // if this is not edit mode and row still has not-sortable (from being a brand new row)
            // then remove the not-sortable and add the sortable classes
            $row.removeClass('not-sortable');
            $row.addClass('sortable ui-sortable');

            //since this is a new row, we also need to set the record-id attribute on the row
            $row.attr('record-id', toggleModel.get('id'));
        }
    },

    /**
     * Set, or reset, the collection of fields that contains each row.
     *
     * This function is invoked when the view renders. It will update the row
     * fields once the `Pagination` plugin successfully fetches new records.
     *
     * @private
     */
    _setRowFields: function() {
        this.rowFields = {};
        _.each(this.fields, function(field) {
            if (field.model && field.model.cid && _.isUndefined(field.parent)) {
                this.rowFields[field.model.cid] = this.rowFields[field.model.cid] || [];
                this.rowFields[field.model.cid].push(field);
            }
        }, this);
    },

    /**
     * Overriding to allow panels to come from whichever module was passed in
     *
     * @inheritdoc
     */
    getFieldNames: function(module) {
        var fields = [];
        var panels;
        module = module || this.context.get('module');

        if (module === 'Quotes' || module === 'Products') {
            panels = _.clone(this.qliListMetadata.panels);
        } else if (module === 'ProductBundleNotes') {
            panels = _.clone(this.pbnListMetadata.panels);
        }

        if (panels) {
            fields = _.reduce(_.map(panels, function(panel) {
                var nestedFields = _.flatten(_.compact(_.pluck(panel.fields, 'fields')));
                return _.pluck(panel.fields, 'name').concat(
                    _.pluck(nestedFields, 'name')).concat(
                    _.flatten(_.compact(_.pluck(panel.fields, 'related_fields'))));
            }), function(memo, field) {
                return memo.concat(field);
            }, []);
        }

        fields = _.compact(_.uniq(fields));

        var fieldMetadata = app.metadata.getModule(module, 'fields');
        if (fieldMetadata) {
            // Filter out all fields that are not actual bean fields
            fields = _.reject(fields, function(name) {
                return _.isUndefined(fieldMetadata[name]);
            });

            // we need to find the relates and add the actual id fields
            var relates = [];
            _.each(fields, function(name) {
                if (fieldMetadata[name].type == 'relate') {
                    relates.push(fieldMetadata[name].id_name);
                } else if (fieldMetadata[name].type == 'parent') {
                    relates.push(fieldMetadata[name].id_name);
                    relates.push(fieldMetadata[name].type_name);
                }
                if (_.isArray(fieldMetadata[name].fields)) {
                    relates = relates.concat(fieldMetadata[name].fields);
                }
            });

            fields = _.union(fields, relates);
        }

        return fields;
    },

    /**
     * Adds the left column fields
     */
    addMultiSelectionAction: function() {
        var _generateMeta = function(buttons, disableSelectAllAlert) {
            return {
                'type': 'fieldset',
                'fields': [
                    {
                        'type': 'quote-data-actionmenu',
                        'buttons': buttons || [],
                        'disable_select_all_alert': !!disableSelectAllAlert
                    }
                ],
                'value': false,
                'sortable': false
            };
        };
        var buttons = this.meta.selection.actions;
        var disableSelectAllAlert = !!this.meta.selection.disable_select_all_alert;
        this.leftColumns.push(_generateMeta(buttons, disableSelectAllAlert));

        this.leftSaveCancelColumn.push({
            'type': 'fieldset',
            'label': '',
            'sortable': false,
            'fields': [{
                type: 'quote-data-editablelistbutton',
                label: '',
                tooltip: 'LBL_CANCEL_BUTTON_LABEL',
                name: 'inline-cancel',
                icon: 'fa-close',
                css_class: 'btn-invisible inline-cancel ellipsis_inline'
            }]
        });

        // if this is the create view, do not add a save button
        if (!this.isCreateView) {
            this.leftSaveCancelColumn[0].fields.push({
                type: 'quote-data-editablelistbutton',
                label: '',
                tooltip: 'LBL_SAVE_BUTTON_LABEL',
                name: 'inline-save',
                icon: 'fa-check-circle',
                css_class: 'btn-invisible inline-save ellipsis_inline'
            });
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (this.context && this.context.parent) {
            this.context.parent.off('quotes:collections:all:checked', null, this);
            this.context.parent.off('quotes:collections:not:all:checked', null, this);
        }

        _.each(this.sugarLogicContexts, function(slContext) {
            slContext.dispose();
        });
        this._super('_dispose');
        this.rowFields = null;
        this.sugarLogicContexts = {};
        this.moduleDependencies = {};
    }
})
