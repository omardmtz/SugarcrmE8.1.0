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
 * @class View.Layouts.Base.SelectionListModuleSwitchLayout
 * @alias SUGAR.App.view.layouts.BaseSelectionListModuleSwitchLayout
 * @extends View.Layouts.Base.SelectionListLayout
 */
({
    extendsFrom: 'SelectionListLayout',

    /**
     * Build the module list for the dropdown from filterList attribute that is set
     * on the context.
     * @inheritdoc
     * @param {Object} options
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this._filterList = this.context.get('filterList');
        this.context.set('filterList', this._buildModuleSwitchList(this._filterList));

        this.context.on('selection-list:reload', this.reload, this);
    },

    /**
     * Given a list of modules, it checks to see if the user has access to those modules
     * and returns a list in a form that Select2 dropdown accepts.
     * @param {Array} modules - List of modules to be displayed in the dropdown.
     * @return {Array}
     * @private
     */
    _buildModuleSwitchList: function(modules) {
        var filter = [];

        _.each(modules, function(module) {
            if (app.acl.hasAccess('list', module)) {
                filter.push({id: module, text: app.lang.get('LBL_MODULE_NAME', module)});
            }
        }, this);

        return filter;
    },

    /**
     * Reload this drawer layout.
     * @param {string} module
     */
    reload: function(module) {
        var self = this;
        // Need to defer so that we do not reload and dispose the drawer before all event
        // callbacks have completely finished.
        _.defer(function() {
            app.drawer.load({
                layout: 'selection-list-module-switch',
                context: {
                    module: module,
                    fields: self.context.get('fields'),
                    filterOptions: self.context.get('filterOptions'),
                    filterList: self._filterList
                }
            });
        });
    }
})
