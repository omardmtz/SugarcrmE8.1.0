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
 * This plugin handles a collection (called the mass collection) of models.
 * It creates the mass collection in the context of the view it's attached to
 * and then provide convenient methods to `add` and `remove` models.
 *
 * The way to use it is to trigger the following context events:
 *  -`mass_collection:add` To add the model passed in argument to the mass
 *    collection.
 *  -`mass_collection:add:all` To add all models of the collection in the mass
 *     collection.
 *  -`mass_collection:remove` To remove the model passed in arguments from the
 *    mass collection.
 *  -`mass_collection:remove:all` To remove all models in the collection from
 *    the mass collection.
 */
(function(app) {
    app.events.on('app:init', function() {
        app.plugins.register('MassCollection', ['view'], {
            onAttach: function() {
                this.on('init', this._initMassCollectionPlugin, this);
            },

            /**
             * Initializes the plugin if the view metadata has selection type
             * set to `multi`.
             *
             * @private
             */
            _initMassCollectionPlugin: function() {
                if (!this.meta || !this.meta.selection || this.meta.selection.type !== 'multi') {
                    return;
                }
                this.createMassCollection();
                this._preselectModels();
                this._initTemplates();
                this._bindMassCollectionEvents();
                this.on('render', this._onViewRender, this);
            },

            /**
             * Callback on view `render` that triggers an `all:check` event if
             * all records in the collection are checked.
             *
             * @private
             */
            _onViewRender: function() {
                if (this.collection.length !== 0) {
                    var event = this._isAllChecked() ? 'all:checked' : 'not:all:checked';
                    this.massCollection.trigger(event);
                    this.toggleSelectAllAlert();
                }
            },

            /**
             * Creates the mass collection and set it in the context.
             *
             * @return {Data.BeanCollection} massCollection The mass collection.
             */
            createMassCollection: function() {
                this.massCollection = this.context.get('mass_collection');
                if (!this.massCollection) {
                    this.massCollection = this.context.get('collection').clone();
                    this.context.set('mass_collection', this.massCollection);
                }
            },

            /**
             * Adds preselected model to the mass collection.
             *
             * Because we only have a list of ids, and in order to display the
             * selected records we need the names, we have to fetch the names.
             *
             * @private
             */
            _preselectModels: function() {
                this.preselectedModelIds = this.context.get('preselectedModelIds');
                if (_.isEmpty(this.preselectedModelIds)) {
                    return;
                }
                if (!_.isArray(this.preselectedModelIds)) {
                    this.preselectedModelIds = [this.preselectedModelIds];
                }

                var preselectedCollection = app.data.createBeanCollection(this.module);
                preselectedCollection.fetch({
                    fields: ['name'],
                    params: {
                        filter: [
                            {'id': {'$in': this.preselectedModelIds}}
                        ]
                    },
                    success: _.bind(function(collection) {
                        this.addModel(collection.models);
                    }, this)
                });
            },

            /**
             * Binds mass collection events listeners.
             *
             * @private
             */
            _bindMassCollectionEvents: function() {
                this.context.on('mass_collection:add', this.addModel, this);
                this.context.on('mass_collection:add:all', this.addAllModels, this);
                this.context.on('mass_collection:remove', this.removeModel, this);
                this.context.on('mass_collection:remove:all', this.removeAllModels, this);
                this.context.on('mass_collection:clear', this.clearMassCollection, this);
                this.context.on('toggleSelectAllAlert', this.toggleSelectAllAlert, this);

                // Resets the mass collection on collection reset for non
                // independent mass collection.
                this.collection.on('reset', function() {
                    if (this.disposed || this.independentMassCollection) {
                        return;
                    }
                    this.clearMassCollection();
                }, this);

                this.collection.on('add', function() {
                    if (!this.disposed && !this._isAllChecked()) {
                        this.massCollection.trigger('not:all:checked');
                    }
                }, this);
            },

            /**
             * Adds a model or a list of models to the mass collection.
             *
             * @param {Data.Bean|Array} models The model or the list of models
             *   to add.
             */
            addModel: function(models) {
                models = _.isArray(models) ? models : [models];
                this.massCollection.add(models);
                if (this._isAllChecked()) {
                    this.massCollection.trigger('all:checked');
                }
            },

            /**
             * Adds all models of the view collection to the mass collection.
             */
            addAllModels: function() {
                if (!this.independentMassCollection) {
                    this.massCollection.reset(this.collection.models);
                } else {
                    this.massCollection.add(this.collection.models);
                }
                this.massCollection.trigger('all:checked');
            },

            /**
             * Removes a model or a list of models from the mass collection.
             *
             * @param {Data.Bean|Array} models The model or the list of models
             *   to remove.
             */
            removeModel: function(models) {
                models = _.isArray(models) ? models : [models];
                this.massCollection.remove(models);
                if (!this._isAllChecked()) {
                    this.massCollection.trigger('not:all:checked');
                }
            },

            /**
             * Removes all models of the view collection from the mass
             * collection.
             */
            removeAllModels: function() {
                if (!this.independentMassCollection) {
                    this.clearMassCollection();
                } else {
                    this.massCollection.remove(this.collection.models);
                    this.massCollection.trigger('not:all:checked');
                }
            },

            /**
             * Clears the mass collection.
             */
            clearMassCollection: function() {
                this.massCollection.entire = false;
                this.massCollection.reset();
                this.massCollection.trigger('not:all:checked');
            },

            /**
             * Checks if all models of the view collection are in the mass
             * collection.
             *
             * @return {boolean} allChecked `true` if all models of the view
             *   collection are in the mass collection.
             * @private
             *
             */
            _isAllChecked: function() {
                if (this.massCollection.length < this.collection.length) {
                    return false;
                }
                var allChecked = _.every(this.collection.models, function(model) {
                    return this.massCollection.get(model.id);
                }, this);

                return allChecked;
            },

            /**
             * Shows or hides the appropriate alert based on the state of the mass collection.
             *
             * FIXME: This method will be removed by SC-3999 because alerts
             * displayed within a list view are to be removed.
             */
            toggleSelectAllAlert: function() {
                var alert;
                if (!this.collection.length) {
                    return;
                }
                var selectedRecordsInPage = _.intersection(this.massCollection.models, this.collection.models);
                if (selectedRecordsInPage.length === this.collection.length) {
                    if (this.collection.next_offset > 0) {
                        alert = this._buildAlertForEntire();
                    } else {
                        alert = this._buildAlertForReset();
                    }
                } else if (this.massCollection.entire) {
                    alert = this._buildAlertForReset();
                }
                if (alert) {
                    this.layout.trigger('list:alert:show', alert);
                } else {
                    this.layout.trigger('list:alert:hide');
                }
            },



            /**
             * Builds the DOM alert with an event for resetting the mass collection.
             *
             * @param {number} [offset] The collection offset.
             * @return {jQuery} The alert content.
             * @protected
             *
             * FIXME: This method will be removed by SC-3999 because alerts
             * displayed within a list view are to be removed.
             */
            _buildAlertForReset: function(offset) {
                var self = this;
                var alert = $('<span></span>').append(this._selectedOffsetTpl({
                    offset: offset,
                    num: this.massCollection.length,
                    all_selected: this.massCollection.length === this.massCollection.maximum
                }));
                alert.find('[data-action=clear]').each(function() {
                    var $el = $(this);
                    $el.on('click', function() {
                        self.context.trigger('mass_collection:clear');
                        $el.off('click');
                    });
                    app.accessibility.run($el, 'click');
                });
                return alert;
            },

            /**
             * Builds the DOM alert with event for selecting all records.
             *
             * @return {jQuery} The alert content.
             * @protected
             *
             * FIXME: This method will be removed by SC-3999 because alerts
             * displayed within a list view are to be removed.
             */
            _buildAlertForEntire: function() {
                var self = this;
                var alert = $('<span></span>').append(this._selectAllTpl({
                        num: this.massCollection.length,
                        link: this._selectAllLinkTpl
                    }));
                alert.find('[data-action=select-all]').each(function() {
                    var $el = $(this);
                    $el.on('click', function() {
                        self.massCollection.entire = true;
                        self.getTotalRecords();
                        $el.off('click');
                    });
                    app.accessibility.run($el, 'click');
                });
                return alert;
            },

            /**
             * Fetch api to retrieve the entire filtered set.
             */
            getTotalRecords: function() {
                var limit = (this.meta.selection.isLinkAction && app.config.maxRecordLinkFetchSize) ||
                    app.config.maxRecordFetchSize;
                var fields = ['id'];

                // if any of the buttons require additional fields, add them to the list
                _.each(this.meta.selection.actions, function(button) {
                    if (_.isArray(button.related_fields)) {
                        fields = _.union(fields, button.related_fields);
                    }
                }, this);

                var options = {
                    fields: fields,
                    limit: limit,
                    // use the last filterDef applied to the collection
                    filter: this.context.get('collection').filterDef,
                    showAlerts: true,
                    success: _.bind(function(collection) {
                        if (_.isEmpty(collection.filterDef)) {
                            // This property represents the maximum number of
                            // records that the user can select. We need it to
                            // add or remove the `all` word in the `selectAll`
                            // alert.
                            this.massCollection.maximum = this.massCollection.length;
                        }
                        this.toggleSelectAllAlert();
                    }, this)
                };

                _.extend(this.massCollection, {
                    orderBy: this.context.get('collection').orderBy,
                });

                this.massCollection.trigger('massupdate:estimate');
                this.massCollection.fetch(options);
            },

            /**
             * Initialize templates.
             *
             * @return {Field.ActionMenuField} Instance of this field.
             * @template
             * @protected
             *
             * FIXME: This method will be removed by SC-3999 because alerts
             * displayed within a list view are to be removed.
             */
            _initTemplates: function() {
                this._selectedOffsetTpl = app.template.getView('list.selected-offset') ||
                    app.template.getView('list.selected-offset', this.module);

                //FIXME: This should be move to a partial template when we are
                // going to move plugins to the clients folder.
                this._selectAllLinkTpl = new Handlebars.SafeString(
                    '<button type="button" class="btn btn-link btn-inline" data-action="select-all">' +
                        app.lang.get('LBL_LISTVIEW_SELECT_ALL_RECORDS') +
                        '</button>'
                );
                this._selectAllTpl = app.template.compile(null, app.lang.get('TPL_LISTVIEW_SELECT_ALL_RECORDS'));

                return this;
            },

            /**
             * Unbind events on dispose.
             */
            onDetach: function() {
                $(window).off('resize.' + this.cid);
                this.context.off('mass_collection:add', null, this);
                this.context.off('mass_collection:add:all', null, this);
                this.context.off('mass_collection:remove', null, this);
                this.context.off('mass_collection:remove:all', null, this);
                this.context.off('toggleSelectAllAlert', null, this);
                this.context.off('mass_collection:clear', null, this);
            }
        });
    });
})(SUGAR.App);
