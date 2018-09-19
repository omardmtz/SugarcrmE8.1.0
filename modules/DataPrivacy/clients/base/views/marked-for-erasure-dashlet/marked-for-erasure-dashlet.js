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
 * @class View.Views.Base.DataPrivacy.MarkedForErasureDashlet
 * @alias SUGAR.App.view.views.BaseDataPrivacyMarkedForErasureDashlet
 * @extends View.Views.Base.Dashlet
 *
 */
(function() {
    /**
     *
     * Given a starting layout will recusively find a child layout with a matching name.
     * The use of this function is an anti-pattern and SHOULD NOT BE COPIED OR REPLICATED!
     *
     * Normally events should be used across the common shared objects (model, collection, context).
     * Finding a specific layout somewhere on the page to set a value or call a function on creates tight coupling
     * between the components. Rather than this view doing anything with the subpanel views/layouts,
     * the subpanel layouts and views should be listening to the proper update/sync events on the collections/contexts
     * that they are attached to and re-render or update themselves appropriately.
     *
     * @param {Layout} layout
     * @param {string} name
     * @return {Component|undefined}
     */
    var findLayout = function(layout, name) {
        if (!layout.getComponent) {
            return;
        }
        var comp = layout.getComponent(name);

        if (!comp) {
            _.find(layout._components, function(subComp) {
                var found = findLayout(subComp, name);
                if (found) {
                    comp = found;
                }
            });
        }

        return comp;
    };
    return {
        plugins: ['Dashlet'],

        events: {
            'click .more': 'loadMore'
        },

        initialize: function(options) {
            this._super('initialize', arguments);
            this.relContexts = {};
            this.render = _.bind(_.debounce(this.render, 100), this);
        },

        initDashlet: function() {
            this.listenTo(this.model, 'change:fields_to_erase', this.formatData);
            this.listenTo(this.model, 'sync', this.formatData);
        },

        /**
         * Should be called when a related data set changes. Will run through the related collections and
         * fields_to_erase from the parent record and format the data in preparation for rendering.
         * Will also trigger a render.
         *
         * This can be optomized later to only update/render the components that changed.
         * This is a simplistic initial implementation.
         */
        formatData: function() {
            this.notApplicable = this.model.get('type') !== 'Request to Erase Information';
            if (this.notApplicable) {
                this.values = false;
            } else {
                var values = this.model.get('fields_to_erase') || {};
                this.values = _.map(values, function(ids, link) {
                    var module = app.data.getRelatedModule(this.model.module, link);
                    var recordCount = _.size(ids);
                    var erased = {
                        link: link,
                        module: module,
                        count: recordCount,
                        models: {},
                        label: app.lang.getModuleName(module, {plural: true})
                    };

                    var ctx = this.listenToRelatedContext(link);
                    if (recordCount > 0 && ctx.get('collapsed')) {
                        // This one is pretty optional. It forces subpanels
                        // open so we can get the names of the models marked for erasure.
                        ctx.set('collapsed', false);
                    }
                    var col = ctx.get('collection');
                    if (col) {
                        col.each(function(model) {
                            if (model.id && ids[model.id]) {
                                erased.models[model.id] = {
                                    model: model,
                                    count: _.size(ids[model.id]),
                                    nameFieldDef: _.extend(model.fields.name, {link: true})
                                };
                            }
                        });
                        if (!_.isEmpty(_.without(ids, _.keys(erased.models))) &&
                            col.next_offset > -1 && col.dataFetched
                        ) {
                            erased.hasMore = true;
                        }
                    }

                    return erased;
                }, this);
            }
            this.render();
        },

        /**
         *  Given a link name, will find and attach the appropriate listeners to that related context
         * @param {string} link
         * @return {Context}
         */
        listenToRelatedContext: function(link) {
            var context = this.context.parent || this.context;
            if (!this.relContexts[link] && context.get('module') == 'DataPrivacy') {
                this.relContexts[link] = context.getChildContext({link: link});
                if (this.relContexts[link].get('collection')) {
                    this.listenTo(this.relContexts[link].get('collection'), 'sync', this.formatData);
                }
            }

            return this.relContexts[link];
        },

        /**
         * Paginate the clicked collection
         *
         * None of the below logic should be neccesary other than calling collection paginate.
         * The subpanel view is triggered instead to allow the required subpanel success callbacks to trigger.
         * In the future, these should be refactored to listen for collection update events.
         * @param e
         * @return {*}
         */
        loadMore: function(e) {
            e.preventDefault();
            var link = $(e.target).data('link');
            if (link && this.relContexts[link]) {
                var subpanel = this.getSubpanelForLink(link);
                if (subpanel) {
                    var footer = _.find(subpanel._components, function(view) {
                        return view.showMoreRecords;
                    });
                    if (footer) {
                        return footer.showMoreRecords();
                    }
                    var listView = _.find(subpanel._components, function(view) {
                        return view.getNextPagination;
                    });
                    if (listView) {
                        return listView.getNextPagination();
                    }
                }
            }
            //We couldn't find an appropriate subpanel, paginate ourselves
            this.relContexts[link].get('collection').paginate({add: true});
        },

        /**
         * Because subpanels do not listen for or handle the collection's update event properly
         * we must trigger the pagination from the subpanel.
         * @param link
         * @return {Mixed}
         */
        getSubpanelForLink: function(link) {
            var topLayout = this.closestComponent('sidebar');
            if (topLayout) {
                var main = topLayout.getComponent('main-pane');
                if (main) {
                    var subPanelLayout = findLayout(main, 'subpanels');
                    if (subPanelLayout) {
                        return _.find(subPanelLayout._components, function(comp) {
                            return comp.context.get('link') === link;
                        });
                    }
                }
            }
        },

        loadData: function() {
            this.formatData();
        }
    };
})()
