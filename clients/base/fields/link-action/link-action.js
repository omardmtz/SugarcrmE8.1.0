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
 * "Link existing record" action used in Subpanels.
 *
 * It needs to be sticky so that we keep things lined up nicely.
 *
 * @class View.Fields.Base.LinkActionField
 * @alias SUGAR.App.view.fields.BaseLinkActionField
 * @extends View.Fields.Base.StickyRowactionField
 */
({
    extendsFrom: 'StickyRowactionField',
    events: {
        'click a[name=select_button]': 'openSelectDrawer'
    },

    /**
     * Click handler for the select action.
     *
     * Opens a drawer for selecting records to link to the current record.
     */
    openSelectDrawer: function() {
        if (this.isDisabled()) {
            return;
        }

        app.drawer.open(
            this.getDrawerOptions(),
            _.bind(this.selectDrawerCallback, this)
        );
    },

    /**
     * Format drawer options used by {@link #openSelectDrawer}.
     *
     * By default it uses {@link View.Layouts.Base.SelectionListLayout} layout.
     * You can extend this method if you need to pass more or different options.
     *
     * @return {Object}
     * @return {string} return.module The module to select records from.
     * @return {Object} return.parent The parent context of the selection list
     *                                context to pass to the drawer.
     * @return {Data.Bean} return.recParentModel The current record to link to.
     * @return {string} return.recLink The relationship link.
     * @return {View.View} return.recView The view for the selection list.
     * @return {Backbone.Model} return.filterOptions The filter options object.
     * */
    getDrawerOptions: function() {
        var parentModel = this.context.get('parentModel');
        var linkModule = this.context.get('module');
        var link = this.context.get('link');

        var filterOptions = new app.utils.FilterOptions().config(this.def);
        filterOptions.setInitialFilter(this.def.initial_filter || '$relate');
        filterOptions.populateRelate(parentModel);

        return {
            layout: 'multi-selection-list-link',
            context: {
                module: linkModule,
                recParentModel: parentModel,
                recLink: link,
                recContext: this.context,
                recView: this.view,
                independentMassCollection: true,
                filterOptions: filterOptions.format()
            }
        };
    },

    /**
     * Callback method used when the drawer is closed.
     *
     * If a record has been selected, it makes a request to the server to link
     * it to the parent record.
     * On success, it refreshes the subpanel collection so the new record
     * appears in the subpanel.
     *
     * Finally, it expands the subpanel context by setting the `collapsed`
     * property to `false`.
     *
     * @param {Data.Bean} model The selected record to link to parent record.
     */
    selectDrawerCallback: function(model) {
        if (!model) {
            return;
        }

        var parentModel = this.context.get('parentModel');
        var link = this.context.get('link');

        var relatedModel = app.data.createRelatedBean(parentModel, model.id, link),
            options = {
                //Show alerts for this request
                showAlerts: true,
                relate: true,
                success: _.bind(function() {
                    //We've just linked a related, however, the list of records from
                    //loadData will come back in DESC (reverse chronological order
                    //with our newly linked on top). Hence, we reset pagination here.
                    this.context.get('collection').resetPagination();
                    this.context.set('collapsed', false);
                }, this),
                error: function() {
                    app.alert.show('server-error', {
                        level: 'error',
                        messages: 'ERR_GENERIC_SERVER_ERROR'
                    });
                }
            };
        relatedModel.save(null, options);
    },

    /**
     * Check if link action should be disabled or not.
     *
     * The side effect of linking another record on a required relationship is
     * that the record could be already linked to a record and in that case we
     * would delete this existing link.
     *
     * @return {boolean} `true` if it should be disabled, `false` otherwise.
     * @override
     */
    isDisabled: function() {
        if (this._super('isDisabled')) {
            return true;
        }
        var link = this.context.get('link'),
            parentModule = this.context.get('parentModule'),
            required = app.utils.isRequiredLink(parentModule, link);
        return required;
    }
})
