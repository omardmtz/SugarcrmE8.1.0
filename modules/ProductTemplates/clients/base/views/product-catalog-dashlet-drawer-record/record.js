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
({
    extendsFrom: 'BaseRecordView',

    /**
     * If this is initialized inside a create view
     */
    isCreateView: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var i;
        var j;
        var panel;
        var field;
        var moduleName;
        var addBtn = _.find(options.meta.buttons, function(btn) {
            return btn.name === 'add_to_quote_button';
        });
        var removeAddBtn = false;
        var userACLs;
        var oppsConfig;
        var secondaryModule;
        var showOnModules = _.keys(addBtn.showOnModules);
        var showOnViews;
        var layoutName;
        var routerFrags;

        // need to use router because if we're on Home or another module and use the megamenu
        // to create an Opp or Quote, it shows the previous module we're in, not the current.
        routerFrags = app.router.getFragment().split('/');
        moduleName = routerFrags[0];

        this.isCreateView = routerFrags[1] === 'create';

        // check to see if there's an add button and if this module is not in the list
        // to show the add button
        if (addBtn) {
            // only the list 'records' layout is empty
            layoutName = routerFrags[1] || 'records';
            showOnViews = addBtn.showOnModules[moduleName];

            if (!_.contains(showOnModules, moduleName)) {
                // if this module is not in the list of metadata 'showOnModules' array, remove it
                removeAddBtn = true;
            }

            if (!removeAddBtn) {
                if (!_.contains(showOnViews, layoutName)) {
                    // if this view is not in the list of metadata 'showOnModules' views doublecheck
                    // if layoutName is 36 characters long and we show on record then allow the add button,
                    // otherwise remove it
                    if (!(layoutName.length === 36 && _.contains(showOnViews, 'record'))) {
                        // if this layoutName is an actual record ID hash
                        removeAddBtn = true;
                    }
                }
            }

            if (!removeAddBtn) {
                // we need to check other conditions to remove the add button
                oppsConfig = app.metadata.getModule('Opportunities', 'config');
                userACLs = app.user.getAcls();

                if (moduleName === 'Opportunities') {
                    if (oppsConfig.opps_view_by === 'RevenueLineItems') {
                        // if Opps+RLI mode, check ACLs on RLIs not Opps
                        secondaryModule = 'RevenueLineItems';
                    } else {
                        // if in Opps only mode, remove the add button
                        removeAddBtn = true;
                    }
                } else if (moduleName === 'Quotes') {
                    secondaryModule = 'Products';
                }

                if (_.has(userACLs[moduleName], 'edit') ||
                    _.has(userACLs[secondaryModule], 'access') ||
                    _.has(userACLs[secondaryModule], 'edit')) {
                    // if the user doesn't have access to edit Opps or Quotes,
                    // or user doesn't have access or edit priveleges for RLIs/QLIs, remove the add button
                    removeAddBtn = true;
                }
            }

            if (removeAddBtn) {
                options.meta.buttons = _.without(options.meta.buttons, addBtn);
            }
        }
        options.name = 'record';

        for (i = 0; i < options.meta.panels.length; i++) {
            panel = options.meta.panels[i];
            for (j = 0; j < panel.fields.length; j++) {
                field = panel.fields[j];
                field.readonly = true;
            }
        }

        this._super('initialize', [options]);
    },

    /**
     * Overriding this function to just listen to the buttons on the record
     *
     * @inheritdoc
     */
    delegateButtonEvents: function() {
        this.context.on('button:cancel_button:click', this._drawerCancelClicked, this);
        this.context.on('button:add_to_quote_button:click', this._drawerAddToQuoteClicked, this);
    },

    /**
     * Handles when the Cancel button is clicked in the ProductCatalogDashlet drawer.
     * It just triggers the event that the tree should re-enable, and closes the drawer.
     *
     * @private
     */
    _drawerCancelClicked: function() {
        app.controller.context.trigger('productCatalogDashlet:add:complete');
        app.drawer.close();
    },

    /**
     * Handles when the Add To Quote button is clicked in the ProductCatalogDashlet drawer.
     * It strips out unnecessary ProductTemplate fields and sends the data to the context.
     *
     * @private
     */
    _drawerAddToQuoteClicked: function() {
        var data = this.model.toJSON();

        data.position = 0;
        data._forcePosition = true;

        // copy Template's id and name to where the QLI expects them
        data.product_template_id = data.id;
        data.product_template_name = data.name;

        // remove ID/etc since we dont want Template ID to be the record id
        delete data.id;
        delete data.date_entered;
        delete data.date_modified;

        // close this drawer first, then trigger event
        app.drawer.close();

        // need to trigger on app.controller.context because of contexts changing between
        // the PCDashlet, and Opps create being in a Drawer, or as its own standalone page
        // app.controller.context is the only consistent context to use
        if (this.isCreateView) {
            // immediately send event
            app.controller.context.trigger('productCatalogDashlet:add', data);
        } else {
            // any other view we need to wait for the drawer to close, then trigger the event
            _.delay(function() {
                app.controller.context.trigger('productCatalogDashlet:add', data);
            }, 750);
        }
    }
})
