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
 * @class View.Layouts.Base.Quotes.QuoteDataListGroupsLayout
 * @alias SUGAR.App.view.layouts.BaseQuotesQuoteDataListGroupsLayout
 * @extends View.Views.Base.Layout
 */
({
    /**
     * @inheritdoc
     */
    tagName: 'table',

    /**
     * @inheritdoc
     */
    className: 'table dataTable quote-data-list-table',

    /**
     * Array of records from the Quote data
     */
    records: undefined,

    /**
     * An Array of ProductBundle IDs currently in the Quote
     */
    groupIds: undefined,

    /**
     * Holds the layout metadata for ProductBundlesQuoteDataGroupLayout
     */
    quoteDataGroupMeta: undefined,

    /**
     * The Element tag to apply jQuery.Sortable on
     */
    sortableTag: 'tbody',

    /**
     * The ID of the default group
     */
    defaultGroupId: undefined,

    /**
     * If this layout is currently in the /create view or not
     */
    isCreateView: undefined,

    /**
     * Contains any current bulk save requests being processed
     */
    currentBulkSaveRequests: undefined,

    /**
     * Counter for how many bundles are being saved
     */
    bundlesBeingSavedCt: undefined,

    /**
     * Array that holds any current api requests
     */
    saveQueue: undefined,

    /**
     * If this is initializing from a Quote's "Copy" functionality
     */
    isCopy: undefined,

    /**
     * Keeps track of the number of items to be copied during a Quote's "Copy" functionality
     */
    copyItemCount: undefined,

    /**
     * Keeps track of the number of bundles to be copied during a Quote's "Copy" functionality
     */
    copyBundleCount: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.saveQueue = [];
        this.groupIds = [];
        this.currentBulkSaveRequests = [];
        this.quoteDataGroupMeta = app.metadata.getLayout('ProductBundles', 'quote-data-group');
        this.bundlesBeingSavedCt = 0;
        this.isCreateView = this.context.get('create') || false;
        this.isCopy = this.context.get('copy') || false;
        this.copyItemCount = 0;
        this.copyBundleCount = 0;

        //Setup the neccesary child context before data is populated so that child views/layouts are correctly linked
        var pbContext = this.context.getChildContext({link: 'product_bundles'});
        pbContext.set('create', this.isCreateView);
        pbContext.prepare(false, true);

        this.before('render', this.beforeRender, this);
        this.on('list:scrollLock', this._scrollLock, this);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        var userACLs = app.user.getAcls();

        this.model.on('change:show_line_nums', this._onShowLineNumsChanged, this);
        this.model.on('change:bundles', this._onProductBundleChange, this);
        this.context.on('quotes:group:create', this._onCreateQuoteGroup, this);
        this.context.on('quotes:group:delete', this._onDeleteQuoteGroup, this);
        this.context.on('quotes:selected:delete', this._onDeleteSelectedItems, this);
        this.context.on('quotes:defaultGroup:create', this._onCreateDefaultQuoteGroup, this);
        this.context.on('quotes:defaultGroup:save', this._onSaveDefaultQuoteGroup, this);

        if (!(_.has(userACLs.Quotes, 'edit') ||
                _.has(userACLs.Products, 'access') ||
                _.has(userACLs.Products, 'edit'))) {
            // only listen for PCDashlet if this is Quotes and user has access
            // to both Quotes and Products
            // need to trigger on app.controller.context because of contexts changing between
            // the PCDashlet, and Opps create being in a Drawer, or as its own standalone page
            // app.controller.context is the only consistent context to use
            app.controller.context.on('productCatalogDashlet:add', this._onProductCatalogDashletAddItem, this);
        }

        // check if this is create mode, in which case add an empty array to bundles
        if (this.isCreateView) {
            this._onProductBundleChange(this.model.get('bundles'));

            if (this.isCopy) {
                this.copyItemCount = this.context.get('copyItemCount');

                if (this.copyItemCount) {
                    this.toggleCopyAlert(true);
                }

                // set this function to happen async after the alert has been displayed
                _.delay(_.bind(function() {
                    this._setCopyQuoteData();
                }, this), 250);
            }
        } else {
            this.model.once('sync', function(model) {
                var bundles = this.model.get('bundles');
                this._checkProductsQuoteLink();

                if (bundles.length == 0) {
                    this._onProductBundleChange(bundles);
                }
            }, this);
        }
    },

    /**
     * Toggles showing and hiding the "Copying QLI" alert when using the Copy functionality
     *
     * @param {boolean} showAlert True if we need to show alert, false if we need to dismiss it
     */
    toggleCopyAlert: function(showAlert) {
        var alertId = 'quotes_copy_alert';
        var titleLabel;

        if (showAlert) {
            titleLabel = this.copyItemCount > 8 ?
                'LBL_QUOTE_COPY_ALERT_MESSAGE_LONG_TIME' :
                'LBL_QUOTE_COPY_ALERT_MESSAGE';

            app.alert.show(alertId, {
                level: 'process',
                closeable: false,
                autoClose: false,
                title: app.lang.get(titleLabel, 'Quotes')
            });
        } else {
            app.alert.dismiss(alertId);
        }
    },

    /**
     * Handles decrementing the total copy item count and
     * checks if we need to dismiss the copy alert, or
     * decrements the copy bundle count and checks if we need to render
     *
     * @param {boolean} bundleComplete True if we're completing a bundle
     */
    completedCopyItem: function(bundleComplete) {
        this.copyItemCount--;
        if (this.copyItemCount === 0) {
            this.toggleCopyAlert(false);
        }

        if (bundleComplete) {
            this.copyBundleCount--;
            if (this.copyBundleCount === 0) {
                this.render();
            }
        }
    },

    /**
     * Handles grabbing the relatedRecords passed in from the context, creating the ProductBundle groups,
     * and adding items into those groups
     *
     * @private
     */
    _setCopyQuoteData: function() {
        var relatedRecords = this.context.get('relatedRecords');
        var defaultGroup = this._getComponentByGroupId(this.defaultGroupId);

        this.copyBundleCount = relatedRecords.length;

        // loop over the bundles
        _.each(relatedRecords, function(record) {
            // check if this record is the "default group"
            if (record.default_group) {
                _.each(record.product_bundle_items, function(pbItem) {
                    // set the item to use the edit template for quote-data-editablelistbutton
                    pbItem.modelView = 'edit';

                    // add this model to the toggledModels for edit view
                    defaultGroup.quoteDataGroupList.toggledModels[pbItem.cid] = pbItem;

                    // update the copy item number
                    this.completedCopyItem();
                }, this);

                // add the whole collection of PBItems to the list collection at once
                defaultGroup.quoteDataGroupList.collection.add(record.product_bundle_items);

                // update the existing default group
                this._updateDefaultGroupWithNewData(defaultGroup, record);

                // update the copy bundle number
                this.completedCopyItem(true);
            } else {
                // listen for a new group being created during the _onCreateQuoteGroup function
                this.context.once(
                    'quotes:group:create:success',
                    _.bind(this._onCopyQuoteDataNewGroupedCreateSuccess, this, record),
                    this
                );

                // create a new quote group
                this._onCreateQuoteGroup();
            }
        }, this);
    },

    /**
     * Called during a Quote record "Copy" to set a group's record data on the model
     * and adds any items to the group's collection
     *
     * @param {Object} record The ProductBundle JSON data to set on the model
     * @param {Data.Bean} pbModel The ProductBundle Model
     * @private
     */
    _onCopyQuoteDataNewGroupedCreateSuccess: function(record, pbModel) {
        var group = this._getComponentByGroupId(pbModel.cid);

        // set the group's name on the model
        group.model.set({
            name: record.name
        });

        // loop over each product bundle item and add it to the group rows
        _.each(record.product_bundle_items, function(pbItem) {
            // set the item to use the edit template for quote-data-editablelistbutton
            pbItem.modelView = 'edit';

            // add this model to the toggledModels for edit view
            group.quoteDataGroupList.toggledModels[pbItem.cid] = pbItem;

            // update the copy item number
            this.completedCopyItem();
        }, this);

        // add the whole collection of PBItems to the list collection at once
        group.quoteDataGroupList.collection.add(record.product_bundle_items);

        // update the copy bundle number
        this.completedCopyItem(true);

        // update the group line number counts
        group.trigger('quotes:line_nums:reset');
    },

    /**
     * Listens for the Product Catalog Dashlet to sent ProductTemplate data
     *
     * @param {Object} productData The ProductTemplate data to convert to a QLI
     * @private
     */
    _onProductCatalogDashletAddItem: function(productData) {
        var defaultGroup = this._getComponentByGroupId(this.defaultGroupId);

        if (defaultGroup) {
            // trigger event on default group to add the product data
            defaultGroup.trigger('quotes:group:create:qli', 'products', productData);
        }

        // trigger event on the context to let dashlet know this is done adding the product
        app.controller.context.trigger('productCatalogDashlet:add:complete');
    },

    /**
     * Checks all Products in bundles to make sure each Product has quote_id set
     *
     * @private
     */
    _checkProductsQuoteLink: function() {
        var quoteId = this.model.get('id');
        var bundles = this.model.get('bundles');
        var prodId;
        var pbItems;
        var bulkRequest;
        var bulkUrl;
        var bulkCalls = [];

        _.each(bundles.models, function(pbModel) {
            pbItems = pbModel.get('product_bundle_items');

            _.each(pbItems.models, function(itemModel) {
                if (itemModel.module === 'Products') {
                    prodId = itemModel.get('id');

                    // if the product exists but doesn't have a quote ID saved, save it
                    if (prodId && _.isEmpty(itemModel.get('quote_id'))) {
                        bulkUrl = app.api.buildURL('Products/' + prodId + '/link/quotes/' + quoteId);
                        bulkRequest = {
                            url: bulkUrl.substr(4),
                            method: 'POST',
                            data: {
                                id: prodId,
                                link: 'quotes',
                                relatedId: quoteId,
                                related: {
                                    quote_id: quoteId
                                }
                            }
                        };
                        bulkCalls.push(bulkRequest);
                    }
                }
            }, this);
        }, this);

        if (bulkCalls.length) {
            app.api.call('create', app.api.buildURL(null, 'bulk'), {
                requests: bulkCalls
            }, null, {
                success: _.bind(function(bulkResponses) {
                    _.each(bulkResponses, function(response) {
                        var record = response.contents.record;
                        var relatedRecord = response.contents.related_record;
                        var bundles = this.model.get('bundles');

                        _.each(bundles.models, function(pbModel) {
                            var pbItems = pbModel.get('product_bundle_items');
                            _.each(pbItems.models, function(itemModel) {
                                if (itemModel.get('id') === record.id) {
                                    // update the product model
                                    this._updateModelWithRecord(itemModel, record);
                                }
                            }, this);
                        }, this);

                        // update the quote model
                        this._updateModelWithRecord(this.model, relatedRecord);
                    }, this);
                }, this)
            });
        }
    },

    /**
     * Handles when the show_line_nums attrib changes on the Quotes model, triggers if
     * line numbers should be shown or not
     *
     * @param {Data.Bean} model The Quotes Bean the change happened on
     * @param {boolean} showLineNums If the line nums should be shown or not
     * @private
     */
    _onShowLineNumsChanged: function(model, showLineNums) {
        this.context.trigger('quotes:show_line_nums:changed', showLineNums);
    },

    /**
     * Handles the quotes:defaultGroup:create event from a separate layout context
     * and triggers the correct create event on the default group to add a new item
     *
     * @param {string} itemType The type of item to create: 'qli' or 'note'
     * @private
     */
    _onCreateDefaultQuoteGroup: function(itemType) {
        //Ensure the default group exists
        if (!this.defaultGroupId) {
            this.model.get('bundles').add(this._getDefaultGroupModel());
        }
        var linkName = itemType == 'qli' ? 'products' : 'product_bundle_notes';
        var group = this._getComponentByGroupId(this.defaultGroupId);
        group.trigger('quotes:group:create:' + itemType, linkName);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var sortableItems;
        var cssClasses;

        this._super('_render');

        sortableItems = this.$(this.sortableTag);
        if (sortableItems.length) {
            _.each(sortableItems, function(sortableItem) {
                $(sortableItem).sortable({
                    // allow draggable items to be connected with other tbody elements
                    connectWith: 'tbody',
                    // allow drag to only go in Y axis direction
                    axis: 'y',
                    // the items to make sortable
                    items: 'tr.sortable',
                    // make the "helper" row (the row the user actually drags around) a clone of the original row
                    helper: 'clone',
                    // adds a slow animation when "dropping" a group, removing this causes the row
                    // to immediately snap into place wherever it's sorted
                    revert: true,
                    // the CSS class to apply to the placeholder underneath the helper clone the user is dragging
                    placeholder: 'ui-state-highlight',
                    // handler for when dragging starts
                    start: _.bind(this._onDragStart, this),
                    // handler for when dragging stops; the "drop" event
                    stop: _.bind(this._onDragStop, this),
                    // handler for when dragging an item into a group
                    over: _.bind(this._onGroupDragTriggerOver, this),
                    // handler for when dragging an item out of a group
                    out: _.bind(this._onGroupDragTriggerOut, this),
                    // the cursor to use when dragging
                    cursor: 'move'
                });
            }, this);
        }

        //wrap in container div for scrolling
        if (!this.$el.parent().hasClass('flex-list-view-content')) {
            cssClasses = 'flex-list-view-content';
            if (this.isCreateView) {
                cssClasses += ' create-view';
            }
            this.$el.wrap(
                '<div class="' + cssClasses + '"></div>'
            );
            this.$el.parent().wrap(
                '<div class="flex-list-view scroll-width left-actions quote-data-table-scrollable"></div>'
            );
        }
    },

    /**
     * Event handler for the sortstart "drag" event
     *
     * @param {jQuery.Event} evt The jQuery sortstart event
     * @param {Object} ui The jQuery Sortable UI Object
     * @private
     */
    _onDragStart: function(evt, ui) {
        // clear the current displayed tooltip
        app.tooltip.clear();
        // disable any future tooltips from appearing until drag stop has occurred
        app.tooltip._disable();
    },

    /**
     * Event handler for the sortstop "drop" event
     *
     * @param {jQuery.Event} evt The jQuery sortstop event
     * @param {Object} ui The jQuery Sortable UI Object
     * @private
     */
    _onDragStop: function(evt, ui) {
        var $item = $(ui.item.get(0));
        var oldGroupId = $item.data('group-id');
        var newGroupId = $($item.parent()).data('group-id');
        // check if the row is in edit mode
        var isRowInEdit = $item.hasClass('tr-inline-edit');
        var triggerOldGroup = false;
        var oldGroup;
        var newGroup;
        var rowId;
        var saveDefaultGroup;
        var existingRows;
        var newPosition;

        // get the new group (may be the same group)
        newGroup = this._getComponentByGroupId(newGroupId);

        // make sure item was dropped in a different group than it started in
        if (oldGroupId !== newGroupId) {
            // since the groups are different, also trigger events for the old group
            triggerOldGroup = true;

            // get the row id from the name="Products_modelID" attrib
            rowId = $item.attr('name').split('_')[1];

            // get if we need to save the new default group list or not
            saveDefaultGroup = newGroup.model.isNew() || false;

            // get the old and new quote-data-group components
            oldGroup = this._getComponentByGroupId(oldGroupId);

            existingRows = newGroup.$('tr.quote-data-group-list:not(:hidden):not(.empty-row)');
            newPosition = _.findIndex(existingRows, function(item) {
                return ($(item).attr('name') == $item.attr('name'));
            });

            this._moveItemToNewGroup(oldGroupId, newGroupId, rowId, isRowInEdit, newPosition, true, true);
        } else {
            // get the requests from updated rows
            this.currentBulkSaveRequests = this.currentBulkSaveRequests.concat(this._updateRowPositions(newGroup));
        }

        // only make the bulk call if there are actual requests, if user drags row
        // but puts it in same place there should be no updates
        if (!this.isCreateView && !_.isEmpty(this.currentBulkSaveRequests)) {
            if (triggerOldGroup) {
                // trigger group changed for old group to check themselves
                oldGroup.trigger('quotes:group:changed');
                // trigger save start for the old group
                oldGroup.trigger('quotes:group:save:start');
                // trigger the group to reset it's line numbers
                oldGroup.trigger('quotes:line_nums:reset', oldGroup.groupId, oldGroup.collection);
            }

            // trigger group changed for new group to check themselves
            newGroup.trigger('quotes:group:changed');
            // trigger save start for the new group
            newGroup.trigger('quotes:group:save:start');
            // trigger the group to reset it's line numbers
            newGroup.trigger('quotes:line_nums:reset', newGroup.groupId, newGroup.collection);

            if (saveDefaultGroup) {
                this._saveDefaultGroupThenCallBulk(oldGroup, newGroup, this.currentBulkSaveRequests);
            } else {
                this._callBulkRequests(_.bind(this._onSaveUpdatedGroupSuccess, this, oldGroup, newGroup));
            }
        }

        // re-enable tooltips in the app
        app.tooltip._enable();
    },

    /**
     * Temporarily overwrites the css from the .scroll-width class so that
     * row field dropdown menues aren't clipped by overflow-x property.
     */
    _scrollLock: function(lock) {
        var $content = this.$el.parent('.flex-list-view-content');
        if (lock) {
            $content.css({'overflow-y': 'visible', 'overflow-x': 'hidden'});
        } else {
            $content.removeAttr('style');
        }
        $content.toggleClass('scroll-locked', lock);
    },

    /**
     * Moves all items from mass_collection into a new group
     * based on the `newGroupData` info
     *
     * @param {Object} newGroupData The new ProductBundle to
     *      be used to move the mass_collection items into
     */
    moveMassCollectionItemsToNewGroup: function(newGroupData) {
        var newGroupId = newGroupData.related_record.id;
        var massCollection = this.context.get('mass_collection');
        var oldGroupId;
        var isRowInEdit;
        var modelCt = {};
        var updateLinkBean;
        var positionCt = 0;

        // since model.link.bean is the same exact reference to a group's model across all models
        // in a group, if multiple items in the same group are moved, we have to only update the
        // model to the new model.link.bean when it's the last model in the group being moved. If we
        // update a model.link.bean, it will change all other model.link.bean references in that group,
        // so we have to count all the models in a group, and only update the model.link.bean when it's
        // the last model we're updating for that group
        _.each(massCollection.models, function(model) {
            oldGroupId = model.link.bean.id;
            if (modelCt[oldGroupId]) {
                modelCt[oldGroupId]++;
            } else {
                modelCt[oldGroupId] = 1;
            }
        }, this);

        _.each(massCollection.models, function(model) {
            // get the old Group ID from the model link
            oldGroupId = model.link.bean.id;
            // get if the row was in Edit mode if modelView exists and is set to 'edit'
            isRowInEdit = model.modelView && model.modelView === 'edit' || false;
            // set selected to false since this model will no longer be in the mass collection
            model.selected = false;
            // decrement the model count for this group
            modelCt[oldGroupId]--;
            // updateLinkBean should only be true when this is the last model in the group (modelCt === 0)
            updateLinkBean = modelCt[oldGroupId] === 0;

            model.set('position', positionCt++);

            this._moveItemToNewGroup(oldGroupId, newGroupId, model.cid, isRowInEdit, undefined, updateLinkBean, false);
        }, this);

        // the items have all been moved on the frontend now call the BulkAPI
        // to flush out this.currentBulkSaveRequests to update the server
        this._callBulkRequests(_.bind(this._onSaveUpdatedMassCollectionItemsSuccess, this));
        if (massCollection) {
            massCollection.reset();
        }
    },

    /**
     * Handles the success call from moving MassCollection items to a new group
     *
     * @param {Object} bulkResponses Response data from the bulk requests
     * @private
     */
    _onSaveUpdatedMassCollectionItemsSuccess: function(bulkResponses) {
        _.each(bulkResponses, function(data) {
            var record = data.contents.record;
            var relatedRecord = data.contents.related_record;
            var newGroup;
            var model;

            // if data.contents.record was empty but contents has an id (old group GET request)
            if (_.isUndefined(record) && (data.contents.id && data.contents.hasOwnProperty('date_modified'))) {
                // this is a GET request
                record = data.contents;
            }

            newGroup = this._getComponentByGroupId(record.id);
            if (newGroup) {
                // check if record is the one on this collection
                if (newGroup.model && record && newGroup.model.get('id') === record.id) {
                    this._updateModelWithRecord(newGroup.model, record);
                }
                if (relatedRecord) {
                    // check if the related_record is in the newGroup
                    model = newGroup.collection.get(relatedRecord.id);
                    if (model) {
                        this._updateModelWithRecord(model, relatedRecord);
                    }
                }
            }
        }, this);
    },

    /**
     * Updates the syncedAttributes and attributes of a `model` with the `record` data
     *
     * @param {Data.Bean} model The model to be updated
     * @param {Object} record The data to set on the model
     * @private
     */
    _updateModelWithRecord: function(model, record) {
        if (model) {
            // remove any empty product_Bundle_items data
            if (record.hasOwnProperty('product_bundle_items') && _.isEmpty(record.product_bundle_items)) {
                delete record.product_bundle_items;
            }

            model.setSyncedAttributes(record);
            model.set(record);
        }
    },

    /**
     * Moves an item with `itemId` from `oldGroupId` to the `newGroupId` ProductBundle
     *
     * @param {string} oldGroupId The ID of the old ProductBundle to move the item from
     * @param {string} newGroupId The ID of the new ProductBundle to move the item to
     * @param {string} itemId The ID of the item to move
     * @param {boolean} isRowInEdit If the row to move is in edit mode or not
     * @param {number|undefined} [newPos] The new position to place the item in
     * @param {boolean} updateLinkBean If we should update the model's link bean or not
     * @param {boolean} updatePos If we should update the model's position or not
     * @private
     */
    _moveItemToNewGroup: function(oldGroupId, newGroupId, itemId, isRowInEdit, newPos, updateLinkBean, updatePos) {
        var oldGroup = this._getComponentByGroupId(oldGroupId);
        var newGroup = this._getComponentByGroupId(newGroupId);
        var rowModel = oldGroup.collection.get(itemId);
        var url;
        var linkName;
        var bulkMoveRequest;
        var oldGroupModelId = oldGroup.model.id;
        var newGroupModelId = newGroup.model.id;
        var itemModelId = rowModel.id;

        // if newPos is not passed in, make it the newGroup collection length
        newPos = _.isUndefined(newPos) ? newGroup.collection.length : newPos;

        // set the new position, so it's only set when the item is saved via the relationship change
        // and not again for the position update
        rowModel.set('position', newPos);

        // remove the rowModel from the old group
        oldGroup.removeRowModel(rowModel, isRowInEdit);

        // add rowModel to the new group
        newGroup.addRowModel(rowModel, isRowInEdit);

        if (updateLinkBean) {
            // update the link on all the models in the new group collection to be the newGroup's model
            _.each(newGroup.collection.models, function(newGroupCollectionModel) {
                newGroupCollectionModel.link = {
                    bean: newGroup.model,
                    isNew: newGroupCollectionModel.link.isNew,
                    name: newGroupCollectionModel.link.name
                };
            }, this);
        }

        if (updatePos) {
            // get the requests from updated rows for old and new group
            this.currentBulkSaveRequests = this.currentBulkSaveRequests.concat(this._updateRowPositions(oldGroup));
            this.currentBulkSaveRequests = this.currentBulkSaveRequests.concat(this._updateRowPositions(newGroup));
        }

        // move the item to the new group
        linkName = rowModel.module === 'Products' ? 'products' : 'product_bundle_notes';
        url = app.api.buildURL('ProductBundles/' + newGroupModelId + '/link/' + linkName + '/' + itemModelId);
        bulkMoveRequest = {
            url: url.substr(4),
            method: 'POST',
            data: {
                id: newGroupModelId,
                link: linkName,
                relatedId: itemModelId,
                related: {
                    position: newPos
                }
            }
        };

        // add the group switching call to the newPos element of the bulk requests
        // so position "0" will be the 0th element in currentBulkSaveRequests
        this.currentBulkSaveRequests.splice(newPos, 0, bulkMoveRequest);

        // get the new totals after everything has happened for the old group
        url = app.api.buildURL('ProductBundles/' + oldGroupModelId);
        bulkMoveRequest = {
            url: url.substr(4),
            method: 'GET'
        };
        this.currentBulkSaveRequests.push(bulkMoveRequest);

        // update the line numbers in the groups
        oldGroup.trigger('quotes:line_nums:reset', oldGroup.groupId, oldGroup.collection);
        newGroup.trigger('quotes:line_nums:reset', newGroup.groupId, newGroup.collection);
    },

    /**
     * Handles saving the default quote group when a user adds a new QLI/Note to an unsaved default group
     * and clicks the save button from the new QLI/Note row
     *
     * @param {Function} successCallback Callback function sent from the QuoteDataEditablelistField so the field
     *      knows when the group save is successful and the field can continue saving the new row model
     * @private
     */
    _onSaveDefaultQuoteGroup: function(successCallback) {
        var group = this._getComponentByGroupId(this.defaultGroupId);

        app.alert.show('saving_default_group_alert', {
            level: 'success',
            autoClose: false,
            messages: app.lang.get('LBL_SAVING_DEFAULT_GROUP_ALERT_MSG', 'Quotes')
        });

        app.api.relationships('create', 'Quotes', {
            'id': this.model.get('id'),
            'link': 'product_bundles',
            'related': {
                position: 0,
                default_group: true
            }
        }, null, {
            success: _.bind(function(group, successCallback, serverData) {
                app.alert.dismiss('saving_default_group_alert');

                this._updateDefaultGroupWithNewData(group, serverData.related_record);

                // call the callback to continue the save stuff
                successCallback();
            }, this, group, successCallback)
        });
    },

    /**
     * Updates a group with the latest server data, updates the model, groupId, and DOM elements
     *
     * @param {View.QuoteDataGroupLayout} group The QuoteDataGroupLayout to update
     * @param {Object} recordData The new record data from the server
     * @private
     */
    _updateDefaultGroupWithNewData: function(group, recordData) {
        if (this.defaultGroupId !== group.model.cid) {
            // remove the old default group ID from groupIds
            this.groupIds = _.without(this.groupIds, this.defaultGroupId);
            // add the new group ID so we dont add the default group twice
            this.groupIds.push(group.model.cid);
        }
        // update defaultGroupId with new id
        this.defaultGroupId = group.model.cid;
        // set the new data on the group model
        group.model.set(recordData);
        // update groupId with new id
        group.groupId = this.defaultGroupId;
        // update the group's dom tbody el with the correct group id
        group.$el.attr('data-group-id', this.defaultGroupId);
        // update the tr's inside the group's dom tbody el with the correct group id
        group.$('tr').attr('data-group-id', this.defaultGroupId);
    },

    /**
     * Handles saving the default quote data group if it has not been saved yet,
     * then when that save success returns, it calls save on all the bulk requests
     * with the new proper group ID
     *
     * @param {View.QuoteDataGroupLayout} oldGroup The old QuoteDataGroupLayout
     * @param {View.QuoteDataGroupLayout} newGroup The new QuoteDataGroupLayout - default group that needs saving
     * @param {Array} bulkSaveRequests The array of bulk save requests
     * @private
     */
    _saveDefaultGroupThenCallBulk: function(oldGroup, newGroup, bulkSaveRequests) {
        var newGroupOldId = newGroup.model.get('id');

        app.alert.show('saving_default_group_alert', {
            level: 'success',
            autoClose: false,
            messages: app.lang.get('LBL_SAVING_DEFAULT_GROUP_ALERT_MSG', 'Quotes')
        });

        app.api.relationships('create', 'Quotes', {
            'id': this.model.get('id'),
            'link': 'product_bundles',
            'related': _.extend({
                position: 0
            }, newGroup.model.toJSON())
        }, null, {
            success: _.bind(this._onDefaultGroupSaveSuccess, this, oldGroup, newGroup, bulkSaveRequests, newGroupOldId)
        });
    },

    /**
     * Called when the default group has been saved successfully and we have the new proper group id. It
     * updates all the bulk requests replacing the old "fake" group ID with the new proper DB-saved group ID,
     * updates newGroup with the new data and group ID and calls the save on the remaining bulk requests
     *
     * @param {View.QuoteDataGroupLayout} oldGroup The old QuoteDataGroupLayout
     * @param {View.QuoteDataGroupLayout} newGroup The new QuoteDataGroupLayout
     * @param {Array} bulkSaveRequests The array of bulk save requests
     * @param {string} newGroupOldId The previous "fake" group ID for newGroup
     * @param {Object} serverData The server response from saving the newGroup
     * @private
     */
    _onDefaultGroupSaveSuccess: function(oldGroup, newGroup, bulkSaveRequests, newGroupOldId, serverData) {
        var newId = serverData.related_record.id;
        app.alert.dismiss('saving_default_group_alert');

        // update all the bulk save requests that have the old newGroup ID with the newly saved group ID
        _.each(bulkSaveRequests, function(req) {
            req.url = req.url.replace(newGroupOldId, newId);
        }, this);

        this._updateDefaultGroupWithNewData(newGroup, serverData.related_record);

        // call the remaining bulk requests
        this._callBulkRequests(_.bind(this._onSaveUpdatedGroupSuccess, this, oldGroup, newGroup));
    },

    /**
     * Calls the bulk request endpoint with an array of requests
     *
     * @param {Function} [successCallback] The success callback function
     * @private
     */
    _callBulkRequests: function(successCallback) {
        var successWrapper = {
            success: _.bind(this.handleSaveQueueSuccess, this, successCallback)
        };
        var apiCall = app.api.call('create', app.api.buildURL(null, 'bulk'), {
            requests: this.currentBulkSaveRequests
        }, successWrapper);
        var saveQueueObj = {
            callReturned: false,
            customSuccess: {},
            request: apiCall,
            responseData: {}
        };

        this.saveQueue.push(saveQueueObj);

        // reset currentBulkSaveRequests
        this.currentBulkSaveRequests = [];
    },

    /**
     * Handles all responses that are returned by the save queue
     *
     * @param {Function|undefined} customSuccess The custom success handler function that should be called next
     * @param {Object} responseData The response returned by the server for a call
     * @param {HttpRequest} httpRequest The HTTP Request that is returning from the api call
     */
    handleSaveQueueSuccess: function(customSuccess, responseData, httpRequest) {
        if (this.saveQueue.length && this.saveQueue[0].request === httpRequest) {
            // there are items in the save queue and the httpRequest
            // that was returned exactly matches the next item in the saveQueue

            // removes this.saveQueue[0] from the array, since the request being
            // processed is the current top of the saveQueue, we don't need to do
            // anything with it just shift it off the array
            this.saveQueue.shift();

            if (_.isFunction(customSuccess)) {
                // if this has been returned in the proper order
                customSuccess(responseData);
            }

            // now that the latest request has been processed, check if other
            // items in the saveQueue need to be processed or not
            this._processSaveQueue();
        } else {
            // the httpRequest being returned does not match the next request that
            // should be processed, so save it for later
            _.some(this.saveQueue, function(queueObj) {
                if (queueObj.request === httpRequest) {
                    queueObj.callReturned = true;
                    queueObj.customSuccess = customSuccess;
                    queueObj.responseData = responseData;
                    return true;
                }
                return false;
            }, this);
        }
    },

    /**
     * Handles checking if more items in `this.saveQueue` need to be processed and then processes them
     * calling itself again to make sure any remaining items get checked and processed.
     *
     * @private
     */
    _processSaveQueue: function() {
        var saveQueueObj;

        // check if the next first request in the saveQueue has returned
        // and needs to be processed or not
        if (this.saveQueue.length && this.saveQueue[0].callReturned) {
            // there are api calls still in the saveQueue and now
            // the first one already has response data that needs to be handled

            // removes this.saveQueue[0] from the array and places it into saveQueueObj
            saveQueueObj = this.saveQueue.shift();

            if (_.isFunction(saveQueueObj.customSuccess)) {
                // check if this had previously been returned out of order and
                // is now the first item in saveQueue it will have customSuccess saved
                saveQueueObj.customSuccess(saveQueueObj.responseData);
            }

            this._processSaveQueue();
        }
    },

    /**
     * The success event handler for when a user reorders or moves an item to a different group
     *
     * @param {View.QuoteDataGroupLayout} oldGroup The old QuoteDataGroupLayout
     * @param {View.QuoteDataGroupLayout} newGroup The new QuoteDataGroupLayout
     * @param {Array} bulkResponses The responses from each of the bulk requests
     * @protected
     */
    _onSaveUpdatedGroupSuccess: function(oldGroup, newGroup, bulkResponses) {
        var deleteResponse = _.find(bulkResponses, function(resp) {
            return resp.contents.id && !resp.contents.hasOwnProperty('date_modified');
        });
        var deletedGroupId = deleteResponse && deleteResponse.contents.id;
        var deletedGroup;
        var newGroupBundle;
        var deletedGroupBundle;
        var bundles;

        if (oldGroup) {
            oldGroup.trigger('quotes:group:save:stop');
        }
        newGroup.trigger('quotes:group:save:stop');

        // remove the deleted group if it exists
        if (deletedGroupId) {
            app.alert.dismiss('deleting_bundle_alert');
            app.alert.show('deleted_bundle_alert', {
                level: 'success',
                autoClose: true,
                messages: app.lang.get('LBL_DELETED_BUNDLE_SUCCESS_MSG', 'Quotes')
            });

            // get the deleted group
            deletedGroup = this._getComponentByGroupId(deletedGroupId);
            // get the bundle for the deleted group
            deletedGroupBundle = deletedGroup.model.get('product_bundle_items');
            // get the bundle for the new group
            newGroupBundle = newGroup.model.get('product_bundle_items');
            // add the deleted group's models to the new group
            _.each(deletedGroupBundle.models, function(model) {
                newGroupBundle.add(model);
                model.link = {
                    bean: newGroup.model,
                    isNew: model.link.isNew,
                    name: model.link.name
                };
            }, this);
        }

        _.each(bulkResponses, _.bind(function(oldGroup, newGroup, data) {
            var record = data.contents.record;
            var relatedRecord = data.contents.related_record;
            var model;
            var isGetRequest = false;
            // remove position and line_num fields if they exist
            relatedRecord = _.omit(relatedRecord, 'position', 'line_num');

            // if data.contents.record was empty but contents has an id (DELETE and GET) and date_modified (only GET)
            if (_.isUndefined(record) && (data.contents.id && data.contents.hasOwnProperty('date_modified'))) {
                // this is a GET request
                isGetRequest = true;
                record = data.contents;
            }

            // on DELETE record and relatedRecord will both be missing
            // on GET ProductBundles relatedRecord will not exist but isGetRequest should be set above
            // on any other request, relatedRecord will be set
            if (record && (relatedRecord || isGetRequest)) {
                // only update if there are new records to update with
                if (oldGroup && !oldGroup.disposed) {
                    // check if record is the one on this collection
                    if (oldGroup.model && record && oldGroup.model.get('id') === record.id) {
                        this._updateModelWithRecord(oldGroup.model, record);
                    }
                    // if oldGroup exists, check if the related_record is in the oldGroup
                    model = oldGroup.collection.get(relatedRecord.id);
                    if (model) {
                        this._updateModelWithRecord(model, relatedRecord);
                    }
                }
                if (newGroup) {
                    // check if record is the one on this collection
                    if (newGroup.model && record && newGroup.model.get('id') === record.id) {
                        this._updateModelWithRecord(newGroup.model, record);
                    }
                    // check if the related_record is in the newGroup
                    model = newGroup.collection.get(relatedRecord.id);
                    if (model) {
                        this._updateModelWithRecord(model, relatedRecord);
                    }
                }
            }
        }, this, oldGroup, newGroup), this);

        if (deletedGroupId) {
            // remove the deleted group ID from the main groupIds
            this.groupIds = _.without(this.groupIds, deletedGroupId);
            // get the main bundles collection
            bundles = this.model.get('bundles');
            // remove the deleted group's model from the main bundles
            bundles.remove(deletedGroup.model);

            // dispose the group
            deletedGroup.dispose();
            // remove the component from the layout
            this.removeComponent(deletedGroup);

            // once new items are added to the default group, update the group's line numbers
            newGroup.trigger('quotes:line_nums:reset', newGroup.groupId, newGroup.collection);
        }
    },

    /**
     * Iterates through all rows in a group and updates the positions for the rows if necessary
     *
     * @param {View.QuoteDataGroupLayout} dataGroup The group component
     * @return {Array}
     * @protected
     */
    _updateRowPositions: function(dataGroup) {
        var retCalls = [];
        var rows = dataGroup.$('tr.quote-data-group-list:not(:hidden):not(.empty-row)');
        var $row;
        var rowNameSplit;
        var rowId;
        var rowModule;
        var rowModel;
        var url;
        var linkName;
        var dataGroupModelId;
        var itemModelId;

        _.each(rows, _.bind(function(dataGroup, retObj, row, index) {
            $row = $(row);
            rowNameSplit = $row.attr('name').split('_');
            rowModule = rowNameSplit[0];
            rowId = rowNameSplit[1];

            rowModel = dataGroup.collection.get(rowId);
            if (rowModel.get('position') != index && !rowModel.isNew()) {
                dataGroupModelId = dataGroup.model.id;
                itemModelId = rowModel.id;
                linkName = rowModule === 'Products' ? 'products' : 'product_bundle_notes';
                url = app.api.buildURL('ProductBundles/' + dataGroupModelId + '/link/' + linkName + '/' + itemModelId);
                retCalls.push({
                    url: url.substr(4),
                    method: 'PUT',
                    data: {
                        position: index
                    }
                });

                rowModel.set('position', index);
            }
        }, this, dataGroup, retCalls), this);

        if (retCalls.length) {
            // if items have changed positions, sort the collection
            // using the collection.comparator compare function
            dataGroup.collection.sort();
        }
        return retCalls;
    },

    /**
     * Gets a quote-data-group component by the group ID
     *
     * @param {string} groupId The group's id
     * @protected
     */
    _getComponentByGroupId: function(groupId) {
        // since groupId could be the cid or the model.id we should check both places
        return _.find(this._components, function(group) {
            return group.name === 'quote-data-group' &&
                (group.groupId === groupId || (group.model && group.model.id === groupId));
        });
    },

    /**
     * Handles when user drags an item into/over a group
     *
     * @param {jQuery.Event} evt The jQuery sortover event
     * @param {Object} ui The jQuery Sortable UI Object
     * @protected
     */
    _onGroupDragTriggerOver: function(evt, ui) {
        var groupId = $(evt.target).data('group-id');
        var group = this._getComponentByGroupId(groupId);
        if (group) {
            group.trigger('quotes:sortable:over', evt, ui);
        }
    },

    /**
     * Handles when user drags an item out of a group
     *
     * @param {jQuery.Event} evt The jQuery sortout event
     * @param {Object} ui The jQuery Sortable UI Object
     * @private
     */
    _onGroupDragTriggerOut: function(evt, ui) {
        var groupId = $(evt.target).data('group-id');
        var group = this._getComponentByGroupId(groupId);
        if (group) {
            group.trigger('quotes:sortable:out', evt, ui);
        }
    },

    /**
     * Removes the sortable plugin from any rows that have the plugin added
     * so we don't add plugin multiple times and for dispose cleanup
     */
    beforeRender: function() {
        var groups = this.$(this.sortableTag);
        if (groups.length) {
            _.each(groups, function(group) {
                if ($(group).hasClass('ui-sortable')) {
                    $(group).sortable('destroy');
                }
            }, this);
        }
    },

    /**
     * Creates the default ProductBundles Bean with default group ID
     *
     * @return {Data.Bean}
     * @protected
     */
    _getDefaultGroupModel: function() {
        var defaultGroup = this._createNewProductBundleBean(null, 0, true);
        // if there is not a default group yet, add one
        this.defaultGroupId = defaultGroup.cid;
        return defaultGroup;
    },

    /**
     * Creates a new ProductBundle Bean
     *
     * @param {String) groupId The groupId to use, if not passed in, will generate a new UUID
     * @param {number) newPosition The position to use for the group
     * @param {boolean) isDefaultGroup If this group is the default group or not
     * @return {Data.Bean}
     * @protected
     */
    _createNewProductBundleBean: function(groupId, newPosition, isDefaultGroup) {
        newPosition = newPosition || 0;
        isDefaultGroup = isDefaultGroup || false;
        return app.data.createBean('ProductBundles', {
            _module: 'ProductBundles',
            _action: 'create',
            _link: 'product_bundles',
            default_group: isDefaultGroup,
            currency_id: this.model.get('currency_id'),
            base_rate: this.model.get('base_rate'),
            product_bundle_items: [],
            product_bundle_notes: [],
            position: newPosition
        });
    },

    /**
     * Handler for when quote_data changes on the model
     *
     * @param {Backbone.Model|Data.MixedBeanCollection} productBundles The quote_data object that changed
     * @protected
     */
    _onProductBundleChange: function(productBundles) {
        var hasDefaultGroup = false;
        var defaultGroupModel;

        // after adding and deleting models, the change event is like its change for the model, where the
        // model is the first param and not the actual value it's self.
        if (productBundles instanceof Backbone.Model) {
            productBundles = productBundles.get('bundles');
        }

        // check to see if there's a default group in the bundle
        if (productBundles && productBundles.length > 0) {
            hasDefaultGroup = _.some(productBundles.models, function(bundle) {
                return bundle.get('default_group');
            });
        }

        if (!hasDefaultGroup) {
            defaultGroupModel = this._getDefaultGroupModel();
            // calling unshift on the collection with silent so it doesn't
            // cause this function to be triggered again halfway thru
            productBundles.unshift(defaultGroupModel);
        } else {
            // default group exists, get the ID
            defaultGroupModel = _.find(productBundles.models, function(bundle) {
                return bundle.get('default_group');
            });
            this.defaultGroupId = defaultGroupModel.cid;
        }

        productBundles.each(function(bundle) {
            if (!_.contains(this.groupIds, bundle.cid)) {
                this.groupIds.push(bundle.cid);
                this._addQuoteGroupToLayout(bundle);
            }
        }, this);

        if (!this.isCopy) {
            this.render();
        }
    },

    /**
     * Adds the actual quote-data-group layout component to this layout
     *
     * @param {Object} [bundle] The ProductBundle data object
     * @private
     */
    _addQuoteGroupToLayout: function(bundle) {
        var pbContext = this.context.getChildContext({link: 'product_bundles'});
        var groupLayout = app.view.createLayout({
            context: pbContext,
            meta: this.quoteDataGroupMeta,
            type: 'quote-data-group',
            layout: this,
            module: 'ProductBundles',
            model: bundle
        });

        groupLayout.initComponents(undefined, pbContext, 'ProductBundles');
        this.addComponent(groupLayout);
    },

    /**
     * Handles the quotes:group:create event
     * Creates a new empty quote data group and renders the groups
     *
     * @private
     */
    _onCreateQuoteGroup: function() {
        var bundles = this.model.get('bundles');
        var nextPosition = 0;
        var highestPositionBundle = bundles.max(function(bundle) {
            return bundle.get('position');
        });
        var newBundle;

        this.bundlesBeingSavedCt++;
        // handle on the off chance that no bundles exist on the quote.
        if (!_.isEmpty(highestPositionBundle)) {
            nextPosition = parseInt(highestPositionBundle.get('position')) + this.bundlesBeingSavedCt;
        }

        if (this.isCreateView) {
            // do not perform saves on create view
            newBundle = this._createNewProductBundleBean(undefined, nextPosition, false);
            // set the _justSaved flag so the new bundle header starts in edit mode
            newBundle.set('_justSaved', true);
            // ignore preferred currency so that we keep the selected currency.
            newBundle.ignoreUserPrefCurrency = true;
            // add the new bundle which will add it to the layout and groupIds
            bundles.add(newBundle);
            // trigger that the group create was successful and pass the new group data
            this.context.trigger('quotes:group:create:success', newBundle);
        } else {
            app.alert.show('adding_bundle_alert', {
                level: 'info',
                autoClose: false,
                messages: app.lang.get('LBL_ADDING_BUNDLE_ALERT_MSG', 'Quotes')
            });

            app.api.relationships('create', 'Quotes', {
                'id': this.model.get('id'),
                'link': 'product_bundles',
                'related': {
                    currency_id: this.model.get('currency_id'),
                    base_rate: this.model.get('base_rate'),
                    position: nextPosition
                }
            }, null, {
                success: _.bind(this._onCreateQuoteGroupSuccess, this)
            });
        }
    },

    /**
     * Success callback handler for when a quote group is created
     *
     * @param {Object} newBundleData The new Quote group data
     * @private
     */
    _onCreateQuoteGroupSuccess: function(newBundleData) {
        this.bundlesBeingSavedCt--;
        app.alert.dismiss('adding_bundle_alert');

        app.alert.show('added_bundle_alert', {
            level: 'success',
            autoClose: true,
            messages: app.lang.get('LBL_ADDED_BUNDLE_SUCCESS_MSG', 'Quotes')
        });

        var bundles = this.model.get('bundles');
        // make sure that the product_bundle_items array is there
        if (_.isUndefined(newBundleData.related_record.product_bundle_items)) {
            newBundleData.related_record.product_bundle_items = [];
        }
        newBundleData.related_record._justSaved = true;
        // now add the new record to the bundles collection
        bundles.add(newBundleData.related_record);

        if (this.model.get('show_line_nums')) {
            // if show_line_nums is true, trigger the event so the new group will add the line_num field
            this.context.trigger('quotes:show_line_nums:changed', true);
        }

        // trigger that the group create was successful and pass the new group data
        this.context.trigger('quotes:group:create:success', newBundleData);
    },

    /**
     * Called when line items have been selected and user has clicked Delete Selected.
     * It prepares the group lists and models to be deleted and adds GET requests
     * for each group after the deletes
     *
     * @param {Data.MixedBeanCollection} massCollection The mass_collection from the quote data list
     * @private
     */
    _onDeleteSelectedItems: function(massCollection) {
        var bulkRequests = [];
        var groupsToUpdate = [];
        var rowId;
        var groupId;
        var groupLayout;
        var url;

        _.each(massCollection.models, function(model) {
            if (model.link) {
                groupId = model.link.bean.id;
                rowId = model.get('id');

                // add the group ID to update the group later
                groupsToUpdate.push(groupId);

                // get the QuoteDataGroupLayout component
                groupLayout = this._getComponentByGroupId(groupId);

                // remove this row from the list's toggledModels if it exists
                delete groupLayout.quoteDataGroupList.toggledModels[rowId];

                url = app.api.buildURL(model.module + '/' + rowId);
                bulkRequests.push({
                    url: url.substr(4),
                    method: 'DELETE'
                });
            }
        }, this);

        // make sure the groups are only in here once
        groupsToUpdate = _.uniq(groupsToUpdate);

        _.each(groupsToUpdate, function(groupIdToUpdate) {
            url = app.api.buildURL('ProductBundles' + '/' + groupIdToUpdate);
            bulkRequests.push({
                url: url.substr(4),
                method: 'GET'
            });
        }, this);

        if (bulkRequests.length) {
            this.currentBulkSaveRequests = bulkRequests;
            this._callBulkRequests(_.bind(this._onDeleteSelectedItemsSuccess, this, massCollection));
        }
    },

    /**
     * Called on success after _onDeleteSelectedItems sets up models to be deleted. This function
     * removes deleted models from the MassCollection and the group's layout, and updates group
     * models with updated data.
     *
     * @param {Data.MixedBeanCollection} massCollection The mass_collection from the quote data list
     * @param {Array} bulkRequests The results from the BulkAPI calls
     * @private
     */
    _onDeleteSelectedItemsSuccess: function(massCollection, bulkRequests) {
        var model;
        var groupId;
        var groupLayout;
        var $checkAllCheckbox = this.$('.checkall input').first();

        if ($checkAllCheckbox.length) {
            // uncheck the CheckAll box after items are deleted
            $checkAllCheckbox.attr('checked', false);
        }

        app.alert.dismiss('deleting_line_item');
        app.alert.show('deleted_line_item', {
            level: 'success',
            autoClose: true,
            messages: [
                app.lang.get('LBL_DELETED_ITEMS_SUCCESS_MSG', this.module)
            ]
        });
        _.each(bulkRequests, function(request) {
            model = massCollection.get(request.contents.id);

            if (model) {
                // the request was for a model in the massCollection
                groupId = model.link.bean.id;
                // get the QuoteDataGroupLayout component
                groupLayout = this._getComponentByGroupId(groupId);
                // remove the model from the group layout
                groupLayout.collection.remove(model);
                // remove the model from the massCollection
                massCollection.remove(model);
            } else {
                // the request was to update a Bundle group
                groupId = request.contents.id;
                // get the QuoteDataGroupLayout component
                groupLayout = this._getComponentByGroupId(groupId);
                // update the group's model with the latest contents data
                this._updateModelWithRecord(groupLayout.model, request.contents);
                // trigger the line nums to be recalculated
                groupLayout.trigger('quotes:line_nums:reset', groupLayout.groupId, groupLayout.collection);
            }
        }, this);
    },

    /**
     * Deletes the passed in ProductBundle
     *
     * @param {ProductBundlesQuoteDataGroupLayout} groupToDelete The group layout to delete
     * @private
     */
    _onDeleteQuoteGroup: function(groupToDelete) {
        var groupId = groupToDelete.model.id;
        var groupName = groupToDelete.model.get('name') || '';

        app.alert.show('confirm_delete_bundle', {
            level: 'confirmation',
            autoClose: false,
            messages: app.lang.get('LBL_DELETING_BUNDLE_CONFIRM_MSG', 'Quotes', {
                groupName: groupName
            }),
            onConfirm: _.bind(this._onDeleteQuoteGroupConfirm, this, groupId, groupName, groupToDelete)
        });
    },

    /**
     * Handler for when the delete quote group confirm box is confirmed
     *
     * @param {string} groupId The model ID of the deleted group
     * @param {string} groupName The model name of the deleted group
     * @param {View.Layout} groupToDelete The Layout for the deleted group
     * @private
     */
    _onDeleteQuoteGroupConfirm: function(groupId, groupName, groupToDelete) {
        var defaultGroup = this._getComponentByGroupId(this.defaultGroupId);
        var bulkRequests = [];
        var bundleItems;
        var positionStart;
        var linkName;
        var url;

        app.alert.show('deleting_bundle_alert', {
            level: 'info',
            autoClose: false,
            messages: app.lang.get('LBL_DELETING_BUNDLE_ALERT_MSG', 'Quotes', {
                groupName: groupName
            })
        });

        if (this.isCreateView) {
            this._removeGroupFromLayout(groupId, groupToDelete);
        } else {
            if (groupToDelete.model && groupToDelete.model.has('product_bundle_items')) {
                bundleItems = groupToDelete.model.get('product_bundle_items');
            }

            // remove any unsaved models
            _.each(bundleItems.models, _.bind(function(bundleItems, groupToDelete, model, key, list) {
                // in _.each, if list is an object, model becomes undefined and list becomes
                // an array with the last model
                model = model || list[0];
                if (model.isNew()) {
                    delete groupToDelete.quoteDataGroupList.toggledModels[model.cid];
                    bundleItems.remove(model);
                }
            }, this, bundleItems, groupToDelete), this);

            if (defaultGroup.model && defaultGroup.model.has('product_bundle_items')) {
                positionStart = defaultGroup.model.get('product_bundle_items').length;
            }

            if (bundleItems && bundleItems.length > 0) {
                _.each(bundleItems.models, _.bind(function(groupId, bulkRequests, posStart, model, index, list) {
                    linkName = (model.module === 'Products' ? 'products' : 'product_bundle_notes');
                    url = app.api.buildURL('ProductBundles/' + groupId + '/link/' +
                        linkName + '/' + model.id);

                    posStart += index;
                    model.set('position', posStart);

                    bulkRequests.push({
                        url: url.substr(4),
                        method: 'POST',
                        data: {
                            id: groupId,
                            link: linkName,
                            relatedId: model.id,
                            related: {
                                position: posStart
                            }
                        }
                    });
                }, this, defaultGroup.model.id, bulkRequests, positionStart));
            }

            url = app.api.buildURL('ProductBundles/' + groupId);

            bulkRequests.push({
                url: url.substr(4),
                method: 'DELETE'
            });

            this.currentBulkSaveRequests = bulkRequests;
            if (defaultGroup.model.isNew()) {
                this._saveDefaultGroupThenCallBulk(groupToDelete, defaultGroup, bulkRequests);
            } else {
                this._callBulkRequests(_.bind(this._onSaveUpdatedGroupSuccess, this, groupToDelete, defaultGroup));
            }
        }
    },

    /**
     * Removes a group from the layout
     *
     * @param {string} groupId The model ID of the deleted group
     * @param {View.Layout} groupToDelete The Layout for the deleted group
     * @private
     */
    _removeGroupFromLayout: function(groupId, groupToDelete) {
        app.alert.dismiss('deleting_bundle_alert');

        var bundles = this.model.get('bundles');
        bundles.remove(groupToDelete.model);

        this.groupIds = _.without(this.groupIds, groupId);

        // dispose the group
        groupToDelete.dispose();
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        this.beforeRender();
        if (app.controller && app.controller.context) {
            app.controller.context.off('productCatalogDashlet:add', null, this);
        }
        this._super('_dispose');
    }
})
